<?php

namespace App\Http\Controllers;

use App\Models\Category;
use GuzzleHttp\Psr7\MimeType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        if (Gate::allows('accessAdminModerator', Auth::user()))
        {
            return redirect('/admin/index');
        }
        else if(Gate::allows('accessSeller', Auth::user()))
        {
            return redirect('/seller/account');
        }
        else
        {
            return view('home', ['categories' => [
                'smartphones' => Category::where('pageName', 'smartphones')->get()->first(),
                'hardware' => Category::where('pageName', 'hardware')->get()->first(),
                'monitors' => Category::where('pageName', 'monitors')->get()->first(),
                'laptops' => Category::where('pageName', 'laptops')->get()->first(),
            ]]);
        }
    }

    public function documentation(string $url = null)
    {
        $url = $url !== null ? 'documentation/' . $url : $url;
        return response(Storage::disk('docs')->get($url ?? 'index.html'))
        ->withHeaders(['Content-Type' => MimeType::fromFilename($url ?? 'index.html')]);
    }
}
