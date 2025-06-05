<?php

namespace App\Policies;

use App\Models\SparePart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SparePartPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('read sparepart');
    }

    public function view(User $user, SparePart $sparePart): bool
    {
        return $user->can('read sparepart');
    }

    public function create(User $user): bool
    {
        return $user->can('create sparepart');
    }

    public function update(User $user, SparePart $sparePart): bool
    {
        return $user->can('update sparepart');
    }

    public function delete(User $user, SparePart $sparePart): bool
    {
        return $user->can('delete sparepart');
    }
}
