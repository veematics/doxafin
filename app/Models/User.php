<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Remove or comment out the Sanctum trait if you don't need API authentication
// use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // Remove HasApiTokens if you don't need API authentication
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
        'avatar'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
