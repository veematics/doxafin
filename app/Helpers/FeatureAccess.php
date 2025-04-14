<?php

namespace App\Helpers;

use App\Models\User;
use App\Models\AppFeature;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class FeatureAccess
{
    private static $cachePrefix = 'user_permissions_';
    private static $cacheDuration = 3600; // 1 hour

    private static function getCacheKey($userId)
    {
        return self::$cachePrefix . $userId;
    }

    public static function cacheUserPermissions($userId)
    {
        // Check if user has SA role
        $isSA = DB::table('roles')
            ->join('role_user', 'roles.id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $userId)
            ->where('roles.name', 'SA')
            // Specify the table name for the id column
            ->select('roles.id')
            ->exists();

        if ($isSA) {
            // Get all features and set full permissions
            $permissions = DB::table('appfeatures')
                ->select([
                    'featureID as feature_id',
                    DB::raw('1 as can_view'),
                    DB::raw('1 as can_create'),
                    DB::raw('1 as can_edit'),
                    DB::raw('1 as can_delete'),
                    DB::raw('1 as can_approve')
                ])
                ->get()
                ->groupBy('feature_id');
        } else {
            // Regular permission calculation
            $permissions = DB::table('feature_role')
                ->join('role_user', 'feature_role.role_id', '=', 'role_user.role_id')
                ->where('role_user.user_id', $userId)
                ->select([
                    'feature_role.feature_id',
                    DB::raw('MIN(feature_role.can_view) as can_view'),
                    DB::raw('MAX(CAST(feature_role.can_create AS SIGNED)) as can_create'),
                    DB::raw('MAX(CAST(feature_role.can_edit AS SIGNED)) as can_edit'),
                    DB::raw('MAX(CAST(feature_role.can_delete AS SIGNED)) as can_delete'),
                    DB::raw('MAX(CAST(feature_role.can_approve AS SIGNED)) as can_approve')
                ])
                ->groupBy('feature_role.feature_id')
                ->get()
                ->groupBy('feature_id');
        }

        Cache::put(self::getCacheKey($userId), $permissions, self::$cacheDuration);
        return $permissions;
    }

    private static function getUserPermissions($userId)
    {
        return Cache::remember(self::getCacheKey($userId), self::$cacheDuration, function () use ($userId) {
            return self::cacheUserPermissions($userId);
        });
    }

    public static function canViewById($userId, $featureId)
    {
        $permissions = self::getUserPermissions($userId);
        if (!isset($permissions[$featureId])) return false;
        
        $viewLevel = $permissions[$featureId]->first()->can_view;
        return is_numeric($viewLevel) && $viewLevel >= 1 && $viewLevel <= 3;
    }

    public static function canCreateById($userId, $featureId)
    {
        $permissions = self::getUserPermissions($userId);
        return isset($permissions[$featureId]) && $permissions[$featureId]->first()->can_create;
    }

    public static function canEditById($userId, $featureId)
    {
        $permissions = self::getUserPermissions($userId);
        return isset($permissions[$featureId]) && $permissions[$featureId]->first()->can_edit;
    }

    public static function canDeleteById($userId, $featureId)
    {
        $permissions = self::getUserPermissions($userId);
        return isset($permissions[$featureId]) && $permissions[$featureId]->first()->can_delete;
    }

    public static function canApproveById($userId, $featureId)
    {
        $permissions = self::getUserPermissions($userId);
        return isset($permissions[$featureId]) && $permissions[$featureId]->first()->can_approve;
    }

    public static function getViewLevelById($userId, $featureId)
    {
        $permissions = self::getUserPermissions($userId);
        return isset($permissions[$featureId]) ? $permissions[$featureId]->first()->can_view : null;
    }

    // Object-based methods
    public static function canView(User $user, AppFeature $feature)
    {
        return self::canViewById($user->id, $feature->featureID);
    }

    public static function canCreate(User $user, AppFeature $feature)
    {
        return self::canCreateById($user->id, $feature->featureID);
    }

    public static function canEdit(User $user, AppFeature $feature)
    {
        return self::canEditById($user->id, $feature->featureID);
    }

    public static function canDelete(User $user, AppFeature $feature)
    {
        return self::canDeleteById($user->id, $feature->featureID);
    }

    public static function canApprove(User $user, AppFeature $feature)
    {
        return self::canApproveById($user->id, $feature->featureID);
    }

    public static function getViewLevel(User $user, AppFeature $feature)
    {
        return self::getViewLevelById($user->id, $feature->featureID);
    }

    public static function clearCache($userId)
    {
        Cache::forget(self::getCacheKey($userId));
    }

    public static function rebuildCache($userId)
    {
        self::clearCache($userId);
        return self::cacheUserPermissions($userId);
    }

    public static function check($userId, $featureName, $permission)
    {
        // Get feature ID by name
        $featureId = Cache::remember('feature_id_' . $featureName, 3600, function () use ($featureName) {
            $feature = AppFeature::where('featureName', $featureName)->first();
            
            // If feature doesn't exist, try to create it 
            if (!$feature && auth()->user() && auth()->user()->hasRole('SA')) {
                // Create feature for system admin only
                $feature = AppFeature::create([
                    'featureName' => $featureName,
                    'displayName' => ucwords(str_replace('_', ' ', $featureName)),
                    'featureIcon' => 'fa-file-text',
                    'featurePath' => 'csv-data', // Updated path without leading slash
                    'url' => '/csv-data', // Updated URL
                    'status' => 1,
                    'parentID' => 0
                ]);
            }
            
            return $feature ? $feature->featureID : null;
        });
    
        if (!$featureId) {
            // For system administrators, allow access even if feature doesn't exist
            if (auth()->user() && auth()->user()->hasRole('SA')) {
                return $permission === 'can_view' ? 1 : true;
            }
            return false;
        }
    
        // Use the established cache key pattern
        $permissions = self::getUserPermissions($userId);
    
        if (!isset($permissions[$featureId])) {
            // For system administrators, allow access even if permissions aren't set
            if (auth()->user() && auth()->user()->hasRole('SA')) {
                return $permission === 'can_view' ? 1 : true;
            }
            return false;
        }

        // For can_view, return the actual value (1, 2, or 3)
        if ($permission === 'can_view') {
            return $permissions[$featureId]->first()->can_view;
        }
        
        // For other permissions, return boolean
        return (bool) $permissions[$featureId]->first()->$permission;
    }
}