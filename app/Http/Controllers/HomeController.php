<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function index()
    {
        if (Gate::allows('accessAdminModerator', Auth::user()))
        {
            return redirect('/admin/index');
        }
        else if (Gate::allows('accessSeller', Auth::user()))
        {
            return redirect('/seller/account');
        }
        else
        {
            $categories = Category::all();

            return view('home', ['categories' => $categories]);
        }
    }
}
