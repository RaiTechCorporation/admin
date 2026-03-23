<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloseFriend extends Model
{
    use HasFactory;
    public $table = "close_friends";

    public function user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(Users::class, 'friend_id');
    }
}
