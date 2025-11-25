<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\Seller;
use Illuminate\Support\Str;
use App\Traits\PassportTokenPrint;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Actions\EncodeImageBinaryToBase64Action;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\Models\User
 *
 * @mixin IdeHelperUser
 * @property string $ulid
 * @property int $id
 * @property string $name
 * @property string|null $surname
 * @property string $email
 * @property-write string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $birthDate
 * @property string $country
 * @property string $city
 * @property string $homeAdress
 * @property string|null $phone
 * @property string|null $profilePicture
 * @property int $phoneVerified
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property-read \App\Models\BannedUser|null $banned
 * @property-write mixed $birth_date
 * @property-read \App\Models\UserCardPaymentCredentials|null $cardCredentials
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client> $clients
 * @property-read int|null $clients_count
 * @property mixed $home_adress
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Payment> $payments
 * @property-read int|null $payments_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Refund> $refunds
 * @property-read int|null $refunds_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Review> $reviews
 * @property-read int|null $reviews_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Role> $roles
 * @property-read int|null $roles_count
 * @property-read Seller|null $seller
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasUlids, Notifiable;
    //protected $primaryKey = 'ulid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'name',
        'surname',
        'email',
        'password',
        'country',
        'city',
        'homeAdress',
        'phone',
        'profilePicture',
        'token',
        'expiresIn'
    ];

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthDate' => 'datetime'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }
    public function seller()
    {
        return $this->hasOne(Seller::class);
    }
    public function banned()
    {
        return $this->hasOne(BannedUser::class);
    }
    public function cardCredentials()
    {
        return $this->hasOne(UserCardPaymentCredentials::class);
    }

    public function hasRoles(array|string $roles)
    {
        if (gettype($roles) == "string")
        {
            foreach ($this->roles as $role)
                if ($role->role == $roles)
                    return true;
            return false;
        }
        else if (gettype($roles) == "array")
        {
            foreach ($this->roles as $role)
            {
                if (in_array($role->role, $roles))
                    return true;
            }
            return false;
        }
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value)
        );
    }
    protected function surname(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value)
        );
    }
    protected function country(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value)
        );
    }
    protected function city(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value)
        );
    }
    protected function homeAdress(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value)
        );
    }
    protected function phone(): Attribute
    {
        return Attribute::make(
            get: fn($value) => Crypt::decryptString($value)
        );
    }

    // protected function profilePicture() : Attribute
    // {
    //     return Attribute::get(function($value){
    //         return Storage::url($value);
    //     });
    // }

    public function toArray()
    {
        $users = parent::toArray();
        $users['homeAdress'] = $this->homeAdress;
        return $users;
    }
}
