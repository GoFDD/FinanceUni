<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use App\Http\Middleware\Cors;
use App\Http\Middleware\Authenticate;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * These middleware são executados em todas as requisições.
     */
    protected $middleware = [
        Cors::class, 
    ];

    /**
     * Middleware groups para rotas.
     */
    protected $middlewareGroups = [
        'web' => [
        ],

        'api' => [
            'throttle:api',
            'bindings',
        ],
    ];

    /**
     * Middleware individuais 
     */
    protected $routeMiddleware = [
        'auth' => Authenticate::class,
        'cors' => Cors::class,
    ];
}
