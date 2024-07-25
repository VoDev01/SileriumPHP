<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'role'
    ];
    public function users()
    {
        return $this->belongsToMany(User::class, 'users_roles', 'user_id', 'role_id');
    }
}
