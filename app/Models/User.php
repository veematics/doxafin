<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

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

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }


    public function hasRole(string $role): bool
    {
        return $this->roles->pluck('name')->contains($role);
    }
    public function hasPermissionTo(string $featureName, string $permission): bool
    {
        $this->loadMissing('roles.features');
        foreach ($this->roles as $role) {
            if ($role->hasPermissionTo($featureName, $permission)) {
                return true;
            }
        }
        return false;
    }

    public function hasFeaturePermissionOption(string $featureName, string $permissionKey, ?string $optionValue = null): bool
    {
        $this->loadMissing('roles.features');
        foreach ($this->roles as $role) {
            if ($role->hasFeaturePermissionOption($featureName, $permissionKey, $optionValue)) {
                return true;
            }
        }
        return false;
    }

    public function hasPermission(string $permission): bool
    {
        list($featureName, $permissionType) = explode('.', $permission);
        
        return $this->roles()
            ->join('feature_role', 'roles.id', '=', 'feature_role.role_id')
            ->join('appfeatures', 'appfeatures.featureID', '=', 'feature_role.feature_id')
            ->where(function ($query) use ($featureName, $permissionType) {
                $query->where('appfeatures.featureName', $featureName)
                      ->where('feature_role.' . $permissionType, true);
            })
            ->exists();
    }

    public function hasPermissionForFeature(int $featureId, string $permissionType): bool
    {
        return $this->roles()
            ->join('feature_role', 'roles.id', '=', 'feature_role.role_id')
            ->where('feature_role.feature_id', $featureId)
            ->where('feature_role.' . $permissionType, true)
            ->exists();
    }
}
