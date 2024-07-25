<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerAuthController extends Controller
{
    public function login()
    {
        return view('seller.auth.login');
    }
    public function register()
    {
        return view('seller.auth.register');
    }
    public function post_register(Request $request)
    {
        
    }
}
