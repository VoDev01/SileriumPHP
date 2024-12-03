<?php

namespace App\Http\Controllers\Seller;

use Str;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Services\ValidatePasswordHashService;
use App\Http\Requests\Seller\SellerLoginRequest;
use App\Http\Requests\Seller\SellerRegisterRequest;

class SellerAuthController extends Controller
{
    public function login()
    {
        return view('seller.auth.login');
    }
    public function postLogin(SellerLoginRequest $request)
    {
        $validated = $request->validated();
        $user = User::where('email', $validated["email"])->first();
        $seller_id = Seller::where('user_id', $user->id)->first()->id;
        $response = ValidatePasswordHashService::validate($request, $validated['password'], $user);
        if($response['success'])
        {
            $request->session()->put('seller_id', $seller_id);
            Auth::login($user);
            return response()->json(['redirect' => '/seller/account']);
        }
        else
            return response()->json($response, 422);
    }
    public function logout(Request $request)
    {
        $request->session()->forget('seller_id');
        Auth::logout(Auth::user());
        return redirect()->route('seller.login');
    }
    public function register()
    {
        return view('seller.auth.register');
    }
    public function postRegister(SellerRegisterRequest $request)
    {
        $validated = $request->validated();
        $userId = User::where("email", $validated["email"])->first()->id;
        if ($request->logo != null) {
            $logoPath = Storage::putFile('logo', $validated->logo);
        } else {
            $logoPath = '\\images\\logo\\default_logo.png';
        }
        Seller::insert([
            "ulid" => Str::ulid()->toBase32(),
            "nickname" => $validated["nickname"],
            "organization_name" => $validated["organization_name"],
            "organization_email" => $validated["organization_email"],
            "logo" => $logoPath,
            "organization_type" => $validated["organization_type"],
            "tax_system" => $validated["tax_system"],
            "user_id" => $userId
        ]);
        return redirect()->route("seller.profile");
    }
}
