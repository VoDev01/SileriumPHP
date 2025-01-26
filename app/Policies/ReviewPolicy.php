<?php

namespace App\Policies;

use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class ReviewPolicy
{
    use HandlesAuthorization;

    public function view(?User $user, Review $review)
    {
        return optional($user)->id === $review->user_id && $user->hasRoles(['admin', 'moderator', 'user', 'seller']);
    }

    public function viewAny(?User $user, Review $review)
    {
        return optional($user)->id === $review->user_id && $user->hasRoles(['admin', 'moderator', 'user', 'seller']);
    }

    public function update(User $user, Review $review)
    {
        return $user->id === $review->user_id && $user->hasRoles(['admin', 'moderator', 'user'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function create()
    {
        return Auth::check()
            ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function delete(User $user, Review $review)
    {
        return $user->id === $review->user_id && $user->hasRoles(['admin', 'moderator', 'user'])
            ? Response::allow()
            : Response::denyAsNotFound();
    }
}
