<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedApiUser extends Model
{
    use HasFactory;

    protected $table = 'banned_api_users';

    protected $fillable = [
        'api_user_id',
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

    public function apiUser()
    {
        return $this->belongsTo(ApiUser::class, null, null, 'banned');
    }
}
