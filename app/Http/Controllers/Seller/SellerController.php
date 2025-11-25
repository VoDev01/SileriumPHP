<?php

namespace App\Http\Controllers\Seller;

use App\Models\Seller;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\SellerEditAccountRequest;
use Illuminate\Support\Facades\Auth;
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
        $seller = Seller::with('user')->where('user_id', Auth::id())->get()->first();
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

        DB::update('UPDATE sellers INNER JOIN users ON sellers.user_id = users.id SET
            nickname = ?,
            organization_name = COALESCE(organization_name, ?),
            organization_email = ?,
            organization_type = COALESCE(organization_type, ?),
            tax_system = COALESCE(tax_system, ?),
            logo = COALESCE(logo, ?)
        WHERE users.id = ?', [
            $validated['nickname'],
            $validated['organization_name'],
            $validated['organization_email'],
            $validated['organization_type'],
            $validated['tax_system'],
            $path,
            Auth::id()
        ]);

        return redirect('/seller/account');
    }
}
