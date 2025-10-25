<?php

namespace App\Providers;

use App\Models\User;
use App\Models\APIUser;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Hash;

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
        'App\Models\Review' => 'App\Policies\ReviewPolicy',
        'App\Models\User' => 'App\Policies\UserPolicy'
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

        Auth::viaRequest('custom-token', function (Request $request)
        {
            $token = $request->header('Token');
            return User::where('token', $token)->get()->first();
        });

        Auth::viaRequest('custom-api-user', function (Request $request)
        {
            $user = APIUser::where('api_key', $request->header('API-Key'))
                ->get()
                ->first();

            if ($user === null)
                return null;

            return Hash::check($request->header('API-Secret'), $user->secret) ? $user : null;
        });
    }
}
