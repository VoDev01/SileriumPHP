<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use ComponentsInputs\SearchForm\SearchFormInputs;
use ComponentsInputs\SearchForm\SearchFormQueryInputs;

class SellerController extends Controller
{
    public function index()
    {
        return view('seller.index');
    }
    public function account()
    {
        if(session('seller_id') != null)
            return view('seller.account');
        else
            return redirect()->route('seller.login');
    }
}
