<?php
protected $listen = [
    \Illuminate\Auth\Events\Login::class => [
        \App\Listeners\RebuildUserPermissionsCache::class,
    ],
];