<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worksheet;

class WorksheetPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('read worksheets');
    }

    public function view(User $user, Worksheet $worksheet): bool
    {
        return $user->can('read worksheets');
    }

    public function create(User $user): bool
    {
        return $user->can('create worksheets');
    }

    public function update(User $user, Worksheet $worksheet): bool
    {
        return $user->can('update worksheets');
    }

    public function delete(User $user, Worksheet $worksheet): bool
    {
        return $user->can('delete worksheets');
    }
}
