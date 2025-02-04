<?php

namespace App\Models;

use Crypt;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCardPaymentCredentials extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'number',
        'expiry',
        'ccv',
        'user_id',
        'updated_at',
        'created_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function number() : Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }
    public function expiry() : Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }
    public function ccv() : Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value),
            set: fn($value) => Crypt::encryptString($value)
        );
    }
}
