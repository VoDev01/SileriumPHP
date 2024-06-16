<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Models\User;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function(object $notifiable, string $url){
            return (new MailMessage)
                ->subject('Подтверждение email')
                ->line('Нажмите на ссылку для подтвеждения email')
                ->action('Подтвердить', $url);
        });
        Gate::define('access-admin-panel', function(User $user){
            foreach($user->roles as $role)
            {
                if($role->role == 'admin' || $role->role == 'moderator')
                    return true;
                else
                    continue;
            }
            return false;
        });
    }
}
