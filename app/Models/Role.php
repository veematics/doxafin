<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function features()
    {
        return $this->belongsToMany(AppFeature::class, 'feature_role', 'role_id', 'feature_id', 'id', 'featureID')
            ->withPivot('can_view', 'can_create', 'can_edit', 'can_delete', 'can_approve', 'additional_permissions')
            ->withTimestamps();
    }

    public function hasPermissionTo(string $featureName, string $permission): bool
    {
        $validPermissions = ['can_view', 'can_create', 'can_edit', 'can_delete', 'can_approve'];
        if (!in_array($permission, $validPermissions)) {
            //\Log::warning("Invalid standard permission string used: {$permission}");
            return false;
        }
        $this->loadMissing('features');
        $feature = $this->features->firstWhere('featureName', $featureName);

        $pivotAccessor = $this->features()->getPivotAccessor();

        return $feature && isset($feature->$pivotAccessor) && $feature->$pivotAccessor->{$permission} === true;
    }

    public function hasFeaturePermissionOption(string $featureName, string $permissionKey, ?string $optionValue = null): bool
    {
        $this->loadMissing('features');
        $feature = $this->features->firstWhere('featureName', $featureName);

        if (!$feature) {
            return false;
        }

        $pivotAccessor = $this->features()->getPivotAccessor();
        $additionalPermissions = $feature->$pivotAccessor->additional_permissions;

        if (is_string($additionalPermissions)) {
            $additionalPermissions = json_decode($additionalPermissions, true);
        }

        if (!is_array($additionalPermissions) || !Arr::has($additionalPermissions, $permissionKey)) {
            return false;
        }

        $grantedOptions = Arr::get($additionalPermissions, $permissionKey);

        if (is_null($optionValue)) {
            return true;
        }

        if (is_array($grantedOptions)) {
            return in_array($optionValue, $grantedOptions);
        }

        return $grantedOptions == $optionValue;
    }
}