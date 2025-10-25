<?php

namespace App\Http\Controllers;

use App\Models\BannedUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannedController extends Controller
{
    public function banned()
    {
        $ban = BannedUser::where('user_id', Auth::user()->ulid)->get()->first();
        return view('banned', ['ban' => $ban]);
    }
}
