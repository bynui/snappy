<?php

namespace Middleware;

use Core\Middleware;

class Examples extends Middleware{
    
    public function get($sequence){
        if($sequence == "before"){
            $this::setData("flag.examplebefore", "Example class before");
        }else{
            $this::setData("flag.exampleeafter", "Example class after");
            $this::setData("records.message", "example middleware injected on after");
            $this::setData("records.result.0.new", "new key injected from middleware");
        }
    }

    public function post($sequence){
        if ($sequence == "before"){
            $this::setData("flag.first", "first name edited");
            $this::setData("flag.last", "last name edited");
            $this::setData("flag.email", "email edited");
        }else{
            $this::setData("result.response", 404);
        }
    }
}

?>