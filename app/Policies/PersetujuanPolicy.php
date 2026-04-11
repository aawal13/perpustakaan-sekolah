<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Persetujuan;
use Illuminate\Auth\Access\HandlesAuthorization;

class PersetujuanPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Persetujuan');
    }

    public function view(AuthUser $authUser, Persetujuan $persetujuan): bool
    {
        return $authUser->can('View:Persetujuan');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Persetujuan');
    }

    public function update(AuthUser $authUser, Persetujuan $persetujuan): bool
    {
        return $authUser->can('Update:Persetujuan');
    }

    public function delete(AuthUser $authUser, Persetujuan $persetujuan): bool
    {
        return $authUser->can('Delete:Persetujuan');
    }

    public function restore(AuthUser $authUser, Persetujuan $persetujuan): bool
    {
        return $authUser->can('Restore:Persetujuan');
    }

    public function forceDelete(AuthUser $authUser, Persetujuan $persetujuan): bool
    {
        return $authUser->can('ForceDelete:Persetujuan');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Persetujuan');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Persetujuan');
    }

    public function replicate(AuthUser $authUser, Persetujuan $persetujuan): bool
    {
        return $authUser->can('Replicate:Persetujuan');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Persetujuan');
    }

}