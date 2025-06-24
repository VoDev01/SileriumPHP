<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function view()
    {
        return Response::allow();
    }

    public function viewAny()
    {
        return Response::allow();
    }

    public function update(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'seller'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'seller'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user)
    {
        return $user->hasRoles(['admin', 'moderator', 'seller'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
