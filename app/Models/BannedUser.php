<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\BannedUser
 *
 * @property int $id
 * @property string $user_id
 * @property string|null $userIp
 * @property string $admin_id
 * @property string $reason
 * @property int $duration
 * @property string $timeType
 * @property \Illuminate\Support\Carbon $bannedAt
 * @property-read \App\Models\User|null $user
 * @method static \Database\Factories\BannedUserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereAdminId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereBannedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereTimeType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BannedUser whereUserIp($value)
 * @mixin \Eloquent
 */
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
