<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrderPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'user', 'seller'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function viewAny(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'seller'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function update(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'user'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user)
    {
        return $user->hasRoles('user')
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'user'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function forceDelete(User $user)
    {
        return $user->hasRoles(['admin', 'moderator'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
