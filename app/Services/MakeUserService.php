<?php
namespace App\Services;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class MakeUserService 
{
    public static function make(array $validated_input, string $pfp)
    {
        $user = User::create([
            'name' => $validated_input['name'],
            'surname' => $validated_input['surname'],
            'email' => $validated_input['email'],
            'password' => Hash::make($validated_input['password']),
            'country' => $validated_input['country'],
            'birthDate' => $validated_input['birthDate'],
            'city' => $validated_input['city'],
            'homeAdress' => $validated_input['homeAdress'],
            'phone' => $validated_input['phone'],
            'profilePicture' => $pfp,
            'phoneVerified' => false,
            'emailVerified' => false,
            'created_at' => Carbon::now(),
            'update_at' => Carbon::now()
        ]);
        return $user;
    }
}