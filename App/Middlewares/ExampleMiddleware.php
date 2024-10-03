<?php

namespace App\Middlewares;

use Core\Middleware;

class ExampleMiddleware extends Middleware
{
    public function handle(callable $next)
    {
        $authorized = true;

        if (!$authorized) {
            return $this->error();
        }

        return $next();
    }
}
