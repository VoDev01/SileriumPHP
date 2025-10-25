<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\APIUser;
use App\Models\BannedUser;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;
use App\Actions\SetConfigAction;
use League\Flysystem\Filesystem;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use Spatie\Dropbox\Client as DropboxClient;
use Spatie\FlysystemDropbox\DropboxAdapter;
use Illuminate\Filesystem\FilesystemAdapter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrapFive();
        Passport::enablePasswordGrant();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));

        Storage::extend('dropbox', function ($app, $config)
        {
            if (Carbon::now()->diffInSeconds($config['access_token_expires_in'], false) <= 0)
            {
                $response = json_decode(Http::asForm()->post(env('DROPBOX_API_URI') . '/oauth2/token', [
                    'refresh_token' => env('DROPBOX_REFRESH_TOKEN'),
                    'grant_type' => 'refresh_token',
                    'client_secret' => env('DROPBOX_SECRET'),
                    'client_id' => env('DROPBOX_KEY')
                ])->body(), true);

                $config['access_token'] = $response['access_token'];

                SetConfigAction::set(
                    'filesystems',
                    'dropbox',
                    'access_token',
                    $response['access_token']
                );

                SetConfigAction::set(
                    'filesystems',
                    'dropbox',
                    'access_token_expires_in',
                    Carbon::now()->addSeconds($response['expires_in'])->format('d-m-Y H:i:s')
                );
            }
            $adapter = new DropboxAdapter(new DropboxClient($config['access_token']));

            return new FilesystemAdapter(
                new Filesystem($adapter, $config),
                $adapter,
                $config
            );
        });
    }
}
