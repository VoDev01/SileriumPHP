<?php

namespace App\Providers;

use App\Models\ApiUser;
use App\Models\BannedApiUser;
use App\Models\User;
use App\Models\BannedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Laravel\Passport\Passport;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        'App\Models\Order' => 'App\Policies\OrderPolicy',
        'App\Models\Product' => 'App\Policies\ProductPolicy',
        'App\Models\Review' => 'App\Policies\ReviewPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
 
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        VerifyEmail::toMailUsing(function ($notifiable, $url)
        {
            return (new MailMessage)
                ->subject('Подтверждение email')
                ->line('Нажмите на ссылку для подтвеждения email.')
                ->action('Подтвердить', $url);
        });

        Gate::define('banned', function (User|ApiUser $user)
        {
            $className = explode('\\', get_class($user)); 
            if(end($className) == 'User')
            {
                $banned = BannedUser::where('user_id', $user->ulid)->get()->first();
            }
            else
            {
                $banned = BannedApiUser::where('api_user_id', $user->id)->get()->first();
            }
            if ($banned !== null)
            {
                $diff = true;
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

                return $diff ? Response::allow() : Response::deny();
            }
            else
                return Response::allow();
        });

        Gate::define('access-admin-moderator', function (User|ApiUser $user)
        {
            if ($user->hasRoles(['admin', 'moderator']))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-all', function (User|ApiUser $user)
        {
            if ($user->hasRoles(['user', 'seller', 'admin', 'moderator']))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-admin', function (User|ApiUser $user)
        {
            if ($user->hasRoles('admin'))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-seller', function (User|ApiUser $user)
        {
            if ($user->hasRoles('seller'))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-seller-admin', function (User|ApiUser $user)
        {
            if ($user->hasRoles(['admin', 'seller', 'moderator']))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });
        
        Gate::define('access-user', function (User|ApiUser $user) {
            if($user->hasRoles('user'))
                return Response::allow();
            else
                return Response::deny();
        });
    }
}
