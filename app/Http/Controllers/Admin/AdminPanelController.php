<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use App\Actions\SetEnvAction;
use GuzzleHttp\Psr7\MimeType;
use App\Actions\SetConfigAction;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Actions\EncodeImageBinaryToBase64Action;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminPanelController extends Controller
{
    public function index()
    {
        return view("admin.index");
    }
    public function profile()
    {
        if(!Auth::check())
            throw new NotFoundHttpException;
        $user = User::find(Auth::id());
        return view("admin.profile", ['user' => $user]);
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function documentation(string $url = null)
    {
        $url = $url ?? 'index.html';
        return response(File::get(storage_path('docs') . '/' . trim($url)))
            ->withHeaders(['Content-Type' => MimeType::fromFilename($url)]);
    }

    public function coverage(string $url = null)
    {
        $url = $url ?? 'index.html';
        return response(File::get(App::basePath('tests/reports/coverage') . '/' . trim($url)))
            ->withHeaders(['Content-Type' => MimeType::fromFilename($url)]);
    }

    public function controlAuthDropbox()
    {
        $dropboxAuthUrl = "
            https://www.dropbox.com/oauth2/authorize?client_id=" .
            env('DROPBOX_KEY') .
            "&token_access_type=offline&response_type=code" .
            "&redirect_uri=" . env('APP_URL') . "/admin/receive_dropbox_token";

        return view("admin.control_api.dropbox", ['dropboxAuthUrl' => $dropboxAuthUrl, 'refreshToken' => env('DROPBOX_REFRESH_TOKEN')]);
    }

    public function revokeDropboxToken()
    {
        $response = Http::asForm()->withHeaders(['Authorization: Bearer' . config('filesystems.disks.dropbox.access_token')])
            ->post(env('DROPBOX_API_URI') . '/2/auth/token/revoke')->body();

        file_put_contents(
            App::basePath() . '/.env',
            preg_replace(
                "/DROPBOX_REFRESH_TOKEN=\"{0,1}.*\"{0,1}/",
                "DROPBOX_REFRESH_TOKEN=\"\"",
                file_get_contents(App::basePath() . '/.env')
            )
        );
        return redirect()->back();
    }

    public function receiveDropboxToken(Request $request)
    {
        $response = json_decode(Http::asForm()->post(env('DROPBOX_API_URI') . '/oauth2/token', [
            'code' => $request->code,
            'grant_type' => 'authorization_code',
            'client_secret' => env('DROPBOX_SECRET'),
            'client_id' => env('DROPBOX_KEY'),
            'redirect_uri' => env('APP_URL') . '/admin/receive_dropbox_token'
        ])->body(), true);

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

        SetEnvAction::set('DROPBOX_REFRESH_TOKEN', $response['refresh_token']);

        return redirect('/admin/control_auth_dropbox');
    }
}
