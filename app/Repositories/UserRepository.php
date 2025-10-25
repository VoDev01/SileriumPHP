<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * Makes user from input
 */
class UserRepository
{
    public static function create(array $validated, string $pfp)
    {
        $userId = User::insertGetId([
            'ulid' => Str::ulid()->toBase32(),
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'birthDate' => Carbon::parse($validated['birthDate'])->format('Y-m-d H:i:s'),
            'country' => $validated['country'],
            'city' => $validated['city'],
            'homeAdress' => $validated['homeAdress'],
            'phone' => $validated['phone'],
            'profilePicture' => $pfp,
            'phoneVerified' => false,
            'email_verified_at' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        $user = User::find($userId);
        return $user;
    }

    public static function update(array $validated, string $pfp)
    {
        $validated['ulid'] = $validated['id'];
        unset($validated['id']);
        $validated = array_filter($validated);
        User::where('ulid', $validated['id'])->update(
            array_merge(
                array_filter($validated, fn($elem) => $elem !== 'ulid' && $elem !== 'birthDate' && $elem !== null),
                [
                    'birthDate' => Carbon::parse($validated['birthDate'])->format('Y-m-d H:i:s'),
                    'profilePicture' => $pfp,
                    'phoneVerified' => false,
                    'email_verified_at' => null,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]
            )
        );
    }

    public static function delete(array|string|int $ids)
    {
        User::destroy($ids);
    }
}
