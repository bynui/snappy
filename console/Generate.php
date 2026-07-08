<?php

namespace Console;

class Generate extends Command{

    private function normalizeName(string $name): string{
        $name = trim($name);

        if ($name === '') {
            return $name;
        }

        return ucfirst($name);
    }

    private function isValidClassName(string $name): bool{
        return (bool) preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name);
    }

    private function validateClassName(string $name, string $entity): bool{
        if ($name === '') {
            $this->error("{$entity} name is required. Use type:Name");
            return false;
        }

        if (!$this->isValidClassName($name)) {
            $this->error(
                "Invalid {$entity} name '{$name}'. Use a valid PHP class name: start with a letter or underscore, then only letters, numbers, or underscores (no spaces or dashes)."
            );
            return false;
        }

        return true;
    }

    public function name(): string{
        return "generate";
    }

    public function handle(array $args): void{
        $target = $args[0] ?? null;

        if (!$target || !str_contains($target, ":")) {
            $this->error("Use type:Name");
            return;
        }

        [$type, $name] = explode(":", $target, 2);
        $type = strtolower(trim($type));
        $name = $this->normalizeName($name);

        $entity = match ($type) {
            "controller" => "Controller",
            "model" => "Model",
            "middleware" => "Middleware",
            default => null
        };

        if (!$entity) {
            $this->error("Unknown type");
            return;
        }

        if (!$this->validateClassName($name, $entity)) {
            return;
        }

        $flags = array_slice($args, 1);

        match ($type) {
            "controller" => $this->makeController($name, $flags),
            "model" => $this->makeModel($name),
            "middleware" => $this->makeMiddleware($name, ['get', 'post', 'put', 'patch', 'delete'])
        };
    }

    /* ---------- CONTROLLER ---------- */

    private function makeControllerMethods(array $methods){
        $methods = array_map(function($method){
            $mapCode = "        \$this->map([\n            \"/\" => function(\$callback){\n            }\n        ]);";
            return "    public function {$method}(){\n{$mapCode}\n    }\n";
        }, $methods);

        return implode("\n", $methods);
    }

    private function makeMiddlewareMethods(array $methods){
        $methods = array_map(function($method){
            return "    public function {$method}(\$sequence){\n    }\n";
        }, $methods);

        return implode("\n", $methods);
    }

    private function makeController(string $name, array $flags){
        
        $model = null;
        $withMiddleware = false;
        $methods = [];

        foreach ($flags as $f) {
            if ($f === "--with-model") {
                $model = $name;
            }

            if ($f === "--with-methods") {
                $methods = ['get', 'post', 'put', 'patch', 'delete'];
            }

            if ($f === "--with-middleware") {
                $withMiddleware = true;
            }

            if (str_starts_with($f, "--with-model:")) {
                $model = $this->normalizeName(explode(":", $f, 2)[1] ?? '');

                if (!$this->validateClassName($model, "Model")) {
                    return;
                }
            }

            if (str_starts_with($f, "--with-methods:")) {
                $methodList = explode(",", explode(":", $f, 2)[1] ?? '');
                $methodList = array_map('trim', $methodList);
                $methodList = array_filter($methodList, fn($m) => in_array(strtolower($m), ['get', 'post', 'put', 'patch', 'delete']));

                if (empty($methodList)) {
                    $this->error("No valid methods specified for --with-methods");
                    return;
                }

                $methods = $methodList;
            }
        }

        if ($model) {
            $this->makeModel($model);
        }

        if ($withMiddleware) {
            $this->makeMiddleware($name, $methods);
        }

        $path = "src/controller/{$name}.php";

        if (file_exists($path)) {
            $this->error("Controller exists");
            return;
        }

        $useModel = "";
        $property = "";
        $constructor = "";

        if ($model) {
            $useModel = "use Model\\{$model} as {$model}Model;\n";
            $property = "    private \$model = null;\n\n";
            $constructor = <<<PHP
    function __construct(){
        \$this->model = new {$model}Model();
    }

PHP;
        }

        $content = <<<PHP
<?php

namespace Controller;

use Core\\Controller;
{$useModel}
class {$name} extends Controller{
{$property}{$constructor}{$this->makeControllerMethods($methods)}
}
?>
PHP;

        file_put_contents($path, $content);
        $this->info("Controller created: {$name}");
    }

    /* ---------- MODEL ---------- */

    private function makeModel(string $name)
    {
        $path = "src/model/{$name}.php";

        if (file_exists($path)) return;

        $content = <<<PHP
<?php

namespace Model;

use Core\\Model;

class {$name} extends Model{

    public function yourFunction(\$param): void{
        \$sql = "sql query here";
        \$this->execute(\$sql, \$param);
    }

}
?>
PHP;

        file_put_contents($path, $content);
        $this->info("Model created: {$name}");
    }

    /* ---------- MIDDLEWARE ---------- */

    private function makeMiddleware(string $name, array $methods = [])
    {
        $path = "src/middleware/{$name}.php";

        if (file_exists($path)) return;

        $content = <<<PHP
<?php

namespace Middleware;

use Core\\Middleware;

class {$name} extends Middleware{
{$this->makeMiddlewareMethods($methods)}
}
?>
PHP;

        file_put_contents($path, $content);
        $this->info("Middleware created: {$name}");
    }
}