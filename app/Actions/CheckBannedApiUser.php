<?php

namespace App\Actions;

use Carbon\Carbon;
use App\Models\APIUser;
use App\Models\BannedUser;

class CheckBannedApiUser
{
    public static function check(APIUser $apiUser)
    {
        $banned = BannedUser::where('user_id', $apiUser->api_key)->get()->first();
        if (isset($banned))
        {
            $diff = false;
            if ($banned->timeType == "seconds")
                $diff = $banned->bannedAt->diffInSeconds(Carbon::now()) >= $banned->duration;
            if ($banned->timeType == "minutes")
                $diff = $banned->bannedAt->diffInMinutes(Carbon::now()) >= $banned->duration;
            else if ($banned->timeType == "hours")
                $diff = $banned->bannedAt->diffInHours(Carbon::now()) >= $banned->duration;
            else if ($banned->timeType == "days")
                $diff = $banned->bannedAt->diffInDays(Carbon::now()) >= $banned->duration;
            else if ($banned->timeType == "years")
                $diff = $banned->bannedAt->diffInYears(Carbon::now()) >= $banned->duration;

            return !$diff;
        }
        return false;
    }
}
