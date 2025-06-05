<?php
// Create middleware: php artisan make:middleware CheckPermission

// app/Http/Middleware/CheckPermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        if (!auth()->user()->can($permission)) {
            return response()->json([
                'message' => 'You do not have permission to perform this action.',
                'required_permission' => $permission,
                'your_permissions' => auth()->user()->getAllPermissions()->pluck('name')
            ], 403);
        }

        return $next($request);
    }
}

// Register this middleware in bootstrap/app.php (Laravel 11+)
// Add this to your bootstrap/app.php file:

/*
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'permission' => \App\Http\Middleware\CheckPermission::class,
    ]);
})
*/

// OR if you're using older Laravel version, add to app/Http/Kernel.php:
/*
protected $middlewareAliases = [
    // ... other middleware
    'permission' => \App\Http\Middleware\CheckPermission::class,
];
*/