<?php

namespace sistema\Middleware;

use Pecee\Http\Middleware\IMiddleware;
use Pecee\Http\Request;
use sistema\Nucleo\Helpers;

class AutenticacaoMiddleware implements IMiddleware {
    public function handle(Request $request): void {
        if (!Helpers::usuarioPodeAcessar()) {
            Helpers::redirecionar('403');
        }
    }
}
