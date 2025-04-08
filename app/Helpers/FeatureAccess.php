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
        $permissions = DB::table('feature_role')
            ->join('role_user', 'feature_role.role_id', '=', 'role_user.role_id')
            ->where('role_user.user_id', $userId)
            ->get([
                'feature_role.feature_id',
                'feature_role.can_view',
                'feature_role.can_create',
                'feature_role.can_edit',
                'feature_role.can_delete',
                'feature_role.can_approve'
            ])->groupBy('feature_id');

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
}