<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\Users\APIUserSearchRequest;
use App\Models\User;
use Illuminate\Http\Request;


class APIUsersController extends Controller
{
    public function search(APIUserSearchRequest $request)
    {
        $validated = $request->validated();
        if(!array_key_exists('id', $validated))
        {
            $validated['id'] = null;
        }
        if(!array_key_exists('loadWith', $validated))
        {
            $users = User::where("email", 'like', '%'.$validated['email'].'%')->orWhere('ulid', $validated['id'])->get();
            return response()->json(['users' => $users->toArray()], 200);
        }
        else if(array_key_exists('loadWith', $validated) && !isset($validated['loadWith']))
        {   
            $users = User::where("email", 'like', '%'.$validated['email'].'%')->orWhere('ulid', $validated['id'])->get();
            return response()->json(['users' => $users->toArray()], 200);
        }
        else
        {
            $loadWithArr = explode(', ', $validated['loadWith']);
            $usersQuery = User::with($loadWithArr);
        }
        $users = $usersQuery->where("email", 'like', '%'.$validated['email'].'%')->orWhere('ulid', $validated['id'])->get();
        if($users != null)
            return response()->json(['users' => $users->toArray()], 200);
        else
            return response()->json(['message' => 'Не было найдено пользователей с данными запросами.'], 404);
    }
}
