<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedUser extends Model
{
    use HasFactory;

    protected $table = 'banned_users';

    protected $fillable = [
        'user_id',
        'userIp',
        'admin_id',
        'reason',
        'duration',
        'timeType',
        'bannedAt'
    ];

    protected $casts = [
        'bannedAt' => 'datetime'
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, null, null, 'banned');
    }
}
