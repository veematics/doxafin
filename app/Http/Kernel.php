protected $routeMiddleware = [
    // ... existing middlewares ...
    'check.permission' => \App\Http\Middleware\CheckPermission::class,
];