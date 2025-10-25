<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * App\Models\UserCardPaymentCredentials
 *
 * @property int $id
 * @property string $number
 * @property string $expiry
 * @property string $ccv
 * @property string $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Database\Factories\UserCardPaymentCredentialsFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereCcv($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereExpiry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserCardPaymentCredentials whereUserId($value)
 * @mixin \Eloquent
 */
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
