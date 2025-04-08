protected function boot()
    {
        parent::boot();

        Event::listen('Illuminate\Auth\Events\Login', function ($event) {
            FeatureAccess::cacheUserPermissions($event->user->id);
        });
    }