<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class APIUsersController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find(string $load_with = "", string $email = "", string $id = null, string $phone = null)
    {
        if($load_with == "")
            $users = User::where("email", $email)->get();
        else if ($load_with == "orders")
            $users = User::with("orders")->where("email", $email)->get();
        else if($load_with == "roles")
            $users = User::with("roles:role")->where("email", $email)->get();
        else if($load_with == "reviews")
            $users = User::with("reviews.product")->where("email", $email)->get();
        else
            $users = User::where("email", $email)->get();
        return response()->json(["users" => $users]);
    }
}
