<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class ApiUser extends User implements MustVerifyEmail
{
    use HasApiTokens, HasUlids, Notifiable, HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ulid',
        'name',
        'email',
        'password',
        'phone'
    ];

    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime'
    ];

    public function banned()
    {
        return $this->hasOne(BannedApiUser::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'users_roles', 'user_id', 'role_id');
    }

    public function hasRoles(array|string $roles)
    {
        if(gettype($roles) == "string")
        {
            foreach($this->roles()->get() as $role)
                if($role->role == $roles)
                    return true;
            return false;
        }
        else if(gettype($roles) == "array")
        {
            foreach($this->roles()->get() as $role)
            {
                if(in_array($role->role, $roles))
                    return true;
            }
            return false;
        }
    }
}
