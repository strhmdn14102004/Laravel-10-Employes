<?php

namespace App\Policies;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmployeePolicy
{
    use HandlesAuthorization;

    public function view(User $user, Employee $employee)
    {
        return $user->id === $employee->user_id;
    }

    public function update(User $user, Employee $employee)
    {
        return $user->id === $employee->user_id;
    }

    public function delete(User $user, Employee $employee)
    {
        return $user->id === $employee->user_id;
    }
}