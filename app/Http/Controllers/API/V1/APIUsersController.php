<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class APIUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function find(string $email, string $load_with = "", string $name = null, string $surname = null, string $id = null, string $phone = null)
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
        return response()->json($users, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
