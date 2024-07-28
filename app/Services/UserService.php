<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService 
{
    public function make(array $validated_input, string $pfp)
    {
        $userId = User::insertGetId([
            'ulid' => Str::ulid()->toBase32(),
            'name' => $validated_input['name'],
            'surname' => $validated_input['surname'],
            'email' => $validated_input['email'],
            'password' => Hash::make($validated_input['password']),
            'birthDate' => $validated_input['birthDate'],
            'country' => $validated_input['country'],
            'city' => $validated_input['city'],
            'homeAdress' => $validated_input['homeAdress'],
            'phone' => $validated_input['phone'],
            'profilePicture' => $pfp,
            'phoneVerified' => false,
            'emailVerified' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $user = User::find($userId);
        return $user;
    }
}