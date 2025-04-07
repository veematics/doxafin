<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy extends BasePolicy
{
    private string $module = 'users';

    public function viewAny(User $user): bool
    {
        return $this->canView($user, $this->module);
    }

    public function view(User $user, User $model): bool
    {
        return $this->canView($user, $this->module);
    }

    public function create(User $user): bool
    {
        return $this->canEdit($user, $this->module);
    }

    public function update(User $user, User $model): bool
    {
        return $this->canEdit($user, $this->module);
    }

    public function delete(User $user, User $model): bool
    {
        return $this->canDelete($user, $this->module);
    }

    public function approve(User $user, User $model): bool
    {
        return $this->canApprove($user, $this->module);
    }
}