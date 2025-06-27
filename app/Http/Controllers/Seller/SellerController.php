<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use Auth;
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
        {
            if(Seller::with('user')->where('id', session('seller_id'))->get()->first()->user->id == Auth::id())
                return view('seller.account');
            else
                return redirect()->route('seller.login');
        }
        else
            return redirect()->route('seller.login');
    }
}
