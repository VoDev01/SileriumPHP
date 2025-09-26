<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Psr7\MimeType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class AdminPanelController extends Controller
{
    public function index()
    {
        return view("admin.index");
    }
    public function profile()
    {
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
        return response(File::get(storage_path('docs') . '/' . $url))
        ->withHeaders(['Content-Type' => MimeType::fromFilename($url)]);
    }
}
