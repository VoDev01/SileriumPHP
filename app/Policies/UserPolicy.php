<?php

namespace App\Policies;

use Carbon\Carbon;
use App\Models\User;
use App\Models\BannedUser;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function banned(User $user)
    {
        $banned = BannedUser::where('user_id', $user->ulid)->get()->first();
        if (isset($banned))
        {
            $diff = false;
            if ($banned->timeType == "seconds")
                $diff = $banned->bannedAt->diffInSeconds(Carbon::now()) <= $banned->duration;
            if ($banned->timeType == "minutes")
                $diff = $banned->bannedAt->diffInMinutes(Carbon::now()) <= $banned->duration;
            else if ($banned->timeType == "hours")
                $diff = $banned->bannedAt->diffInHours(Carbon::now()) <= $banned->duration;
            else if ($banned->timeType == "days")
                $diff = $banned->bannedAt->diffInDays(Carbon::now()) <= $banned->duration;
            else if ($banned->timeType == "years")
                $diff = $banned->bannedAt->diffInYears(Carbon::now()) <= $banned->duration;

            return $diff;
        }
        return false;
    }

    public function accessAdminModerator(User $user)
    {
        return $user->hasRoles(['admin', 'moderator']);
    }

    public function accessAll(User $user)
    {
        return $user->hasRoles(['user', 'seller', 'admin', 'moderator']);
    }

    public function accessAdmin(User $user)
    {
        return $user->hasRoles('admin');
    }

    public function accessSeller(User $user)
    {
        return $user->hasRoles('seller');
    }

    public function accessSellerAdmin(User $user)
    {
        return $user->hasRoles(['admin', 'seller', 'moderator']);
    }

    public function accessUser(User $user)
    {
        return $user->hasRoles('user');
    }
}
