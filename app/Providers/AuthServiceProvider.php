<?php

namespace App\Providers;

use App\Models\User;
use App\Models\BannedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;
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

        VerifyEmail::toMailUsing(function ($notifiable, $url)
        {
            return (new MailMessage)
                ->subject('Подтверждение email')
                ->line('Нажмите на ссылку для подтвеждения email.')
                ->action('Подтвердить', $url);
        });

        Gate::define('banned', function (User $user)
        {
            $banned = BannedUser::where('user_id', $user->ulid)->get()->first();
            if (null !== $banned)
            {
                $diff = true;
                if ($banned->timeType == "seconds")
                    $diff = Carbon::now()->diffInSeconds($banned->banTime) == 0;
                else if ($banned->timeType == "hours")
                    $diff = Carbon::now()->diffInHours($banned->banTime) == 0;
                else if ($banned->timeType == "days")
                    $diff = Carbon::now()->diffInDays($banned->banTime) == 0;
                else if ($banned->timeType == "years")
                    $diff = Carbon::now()->diffInYears($banned->banTime) == 0;

                return $diff ? Response::deny() : Response::allow();
            }
            else
                return Response::allow();
        });

        Gate::define('access-admin-panel', function (User $user)
        {
            if ($user->hasRoles(['admin', 'moderator']))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-api', function (User $user)
        {
            if ($user->hasRoles(['user', 'seller', 'admin', 'moderator']))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-admin-api', function (User $user)
        {
            if ($user->hasRoles('admin'))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-seller-api', function (User $user)
        {
            if ($user->hasRoles('seller'))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });

        Gate::define('access-seller-admin-api', function (User $user)
        {
            if ($user->hasRoles(['admin', 'seller', 'moderator']))
                return Response::allow();
            else
                return Response::denyAsNotFound();
        });
    }
}
