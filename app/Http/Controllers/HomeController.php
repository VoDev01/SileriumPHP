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
            // $images = [];
            // foreach ($images as $image)
            // {
            //     $paths = explode('/',  $image);
            //     $idExt = explode('_', $paths[count($paths) - 1])[1];
            //     $categoryId = explode('.', $idExt)[0];
            //     $categoryModel = Category::where('id', $categoryId)->get()->first();
            //     if ($categoryModel !== null)
            //     {
            //         $images["$categoryModel->pageName"] = array(
            //             'model' => $categoryModel,
            //             'image' => $image
            //         );
            //     }
            // }

            $categories = Category::all();

            return view('home', ['categories' => $categories]);
        }
    }
}
