<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class APIUser extends Model
{
    use HasFactory;

    protected $table = 'api_users';    

    protected $fillable = [
        'api_key',
        'secret'
    ];

    protected function api_key(): Attribute
    {
        return new Attribute(
            get: fn($value) => Crypt::decrypt($value),
            set: fn($value) => Crypt::encrypt($value)
        );
    } 
    protected function secret(): Attribute
    {
        return new Attribute(
            set: fn($value) => Hash::make($value, ['rounds' => 10])
        );
    } 
}
