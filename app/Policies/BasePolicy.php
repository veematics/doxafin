<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

abstract class BasePolicy
{
    use HandlesAuthorization;

    protected function checkPermission(User $user, string $permission): bool
    {
        return $user->hasPermission($permission);
    }

    protected function checkRole(User $user, string $role): bool
    {
        return $user->hasRole($role);
    }

    protected function canView(User $user, string $module): bool
    {
        return $user->hasPermission($module . '.can_view');
    }

    protected function canEdit(User $user, string $module): bool
    {
        return $user->hasPermission($module . '.can_edit');
    }

    protected function canDelete(User $user, string $module): bool
    {
        return $user->hasPermission($module . '.can_delete');
    }

    protected function canApprove(User $user, string $module): bool
    {
        return $user->hasPermission($module . '.can_approve');
    }
}