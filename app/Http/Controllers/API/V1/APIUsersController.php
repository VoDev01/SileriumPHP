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
    public function find(string $email, string $load_with = "", string $name = null, string $surname = null, string $id = null, string $phone = null)
    {
        if($load_with == "")
        {
            $users = User::where("email", $email)->get();
            return response()->json($users, 200);
        }
        else if ($load_with == "orders")
            $usersQuery = User::with("orders");
        else if($load_with == "roles")
            $usersQuery = User::with("roles");
        else if($load_with == "reviews")
            $usersQuery = User::with("reviews.product");
        $users = $usersQuery->where("email", $email)->get();
        return response()->json($users, 200);
    }
}
