<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'id',
        'payment_id',
        'user_id',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
