protected function redirectTo($request)
{
    if (! $request->expectsJson()) {
        return route('welcome');  // This will redirect to '/' as defined in your routes
    }
}