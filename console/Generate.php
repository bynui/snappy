<?php

namespace Console;

class Generate extends Command{

    public function name(): string{
        return "generate";
    }

    public function handle(array $args): void{
        $target = $args[0] ?? null;

        if (!$target || !str_contains($target, ":")) {
            $this->error("Use type:Name");
            return;
        }

        [$type, $name] = explode(":", $target);
        $flags = array_slice($args, 1);

        match ($type) {
            "controller" => $this->makeController($name, $flags),
            "model" => $this->makeModel($name),
            "middleware" => $this->makeMiddleware($name),
            default => $this->error("Unknown type")
        };
    }

    /* ---------- CONTROLLER ---------- */

    private function makeController(string $name, array $flags){
        
        $model = null;
        $withMiddleware = false;

        foreach ($flags as $f) {
            if ($f === "--with-model") {
                $model = $name;
            }

            if (str_starts_with($f, "--with-model:")) {
                $model = explode(":", $f)[1];
            }

            if ($f === "--with-middleware") {
                $withMiddleware = true;
            }
        }

        if ($model) {
            $this->makeModel($model);
        }

        if ($withMiddleware) {
            $this->makeMiddleware($name);
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
{$property}{$constructor}}
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

}
?>
PHP;

        file_put_contents($path, $content);
        $this->info("Model created: {$name}");
    }

    /* ---------- MIDDLEWARE ---------- */

    private function makeMiddleware(string $name)
    {
        $path = "src/middleware/{$name}.php";

        if (file_exists($path)) return;

        $content = <<<PHP
<?php

namespace Middleware;

use Core\\Middleware;

class {$name} extends Middleware{

}
?>
PHP;

        file_put_contents($path, $content);
        $this->info("Middleware created: {$name}");
    }
}
