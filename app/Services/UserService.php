<?php
namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserService 
{
    public static function makeEncrypted(array $validated_input, string $pfp)
    {
        $userId = User::insertGetId([
            'ulid' => Str::ulid()->toBase32(),
            'name' => Crypt::encryptString($validated_input['name']),
            'surname' => Crypt::encryptString($validated_input['surname']),
            'email' => Crypt::encryptString($validated_input['email']),
            'password' => Hash::make($validated_input['password']),
            'birthDate' => Carbon::parse($validated_input['birthDate'])->format('Y-m-d H:i:s'),
            'country' => Crypt::encryptString($validated_input['country']),
            'city' => Crypt::encryptString($validated_input['city']),
            'homeAdress' => Crypt::encryptString($validated_input['homeAdress']),
            'phone' => Crypt::encryptString($validated_input['phone']),
            'profilePicture' => $pfp,
            'phoneVerified' => false,
            'emailVerified' => false,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $user = User::find($userId);
        return $user;
    }
    public static function getDecrypted(string $email)
    {
        $user = User::where('email', $email)->first();
        $user->name = Crypt::decryptString($user->name);
        $user->surname = Crypt::decryptString($user->surname);
        $user->email = Crypt::decryptString($user->email);
        $user->birthDate = Crypt::decryptString($user->birthDate);
        $user->country = Crypt::decryptString($user->country);
        $user->city = Crypt::decryptString($user->city);
        $user->homeAdress = Crypt::decryptString($user->homeAdress);
        $user->phone = Crypt::decryptString($user->phone);
        return $user;
    }
}