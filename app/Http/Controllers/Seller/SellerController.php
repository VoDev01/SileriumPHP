<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\SellerEditAccountRequest;
use Illuminate\Support\Facades\Auth;
use ComponentsInputs\SearchForm\SearchFormInputs;
use ComponentsInputs\SearchForm\SearchFormQueryInputs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SellerController extends Controller
{
    public function index()
    {
        return view('seller.index');
    }
    public function account()
    {
        $seller = Seller::with('user')->where('id', session('seller_id'))->get()->first();
        return view('seller.account', ['seller' => $seller]);
    }
    public function editAccount()
    {
        return view('seller.edit_account', ['user' => Auth::user()]);
    }
    public function postEditAccount(SellerEditAccountRequest $request)
    {
        $validated = $request->validated();
        $path = null;
        if (isset($validated['logo']))
            $path = Storage::putFile('logo', $validated['logo']);
        DB::update('UPDATE TABLE sellers INNER JOIN users ON sellers.user_id = users.id SET (
            nickname = ?,
            organization_name = ?,
            organization_email = ?,
            organization_type = ?,
            tax_system = ?,
            users.email = ?,
            logo = ?
        ) WHERE users.id = :user_id', [
            ':user_id' => Auth::id(),
            $validated['nickname'],
            $validated['organization_name'],
            $validated['organization_email'],
            $validated['organization_type'],
            $validated['tax_system'],
            $validated['email'],
            $path
        ]);
        return redirect('/seller/account');
    }
}
