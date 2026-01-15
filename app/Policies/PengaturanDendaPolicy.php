<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\PengaturanDenda;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class PengaturanDendaPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:PengaturanDenda');
    }

    public function view(AuthUser $authUser, PengaturanDenda $pengaturanDenda): bool
    {
        return $authUser->can('View:PengaturanDenda');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:PengaturanDenda');
    }

    public function update(AuthUser $authUser, PengaturanDenda $pengaturanDenda): bool
    {
        return $authUser->can('Update:PengaturanDenda');
    }

    public function delete(AuthUser $authUser, PengaturanDenda $pengaturanDenda): bool
    {
        return $authUser->can('Delete:PengaturanDenda');
    }

    public function restore(AuthUser $authUser, PengaturanDenda $pengaturanDenda): bool
    {
        return $authUser->can('Restore:PengaturanDenda');
    }

    public function forceDelete(AuthUser $authUser, PengaturanDenda $pengaturanDenda): bool
    {
        return $authUser->can('ForceDelete:PengaturanDenda');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:PengaturanDenda');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:PengaturanDenda');
    }

    public function replicate(AuthUser $authUser, PengaturanDenda $pengaturanDenda): bool
    {
        return $authUser->can('Replicate:PengaturanDenda');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:PengaturanDenda');
    }
}
