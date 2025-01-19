<?php
namespace App\Actions;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserAction 
{
    public static function make(array $input, string $pfp)
    {
        $userId = User::insertGetId([
            'ulid' => Str::ulid()->toBase32(),
            'name' => $input['name'],
            'surname' => $input['surname'],
            'email' => $input['email'],
            'password' => $input['password'],
            'birthDate' => Carbon::parse($input['birthDate'])->format('Y-m-d H:i:s'),
            'country' => $input['country'],
            'city' => $input['city'],
            'homeAdress' => $input['homeAdress'],
            'phone' => $input['phone'],
            'profilePicture' => $pfp,
            'phoneVerified' => false,
            'email_verified_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $user = User::find($userId);
        return $user;
    }
}