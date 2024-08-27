<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Role;
use App\Models\Seller;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @mixin IdeHelperUser
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUlids, Notifiable;
    
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
        'profilePicture'
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
        return $this->belongsToMany(Role::class, 'users_roles', 'role_id', 'user_id');
    }
    public function sellers()
    {
        return $this->belongsToMany(Seller::class, 'sellers_users', 'seller_id', 'user_id');
    }
}
