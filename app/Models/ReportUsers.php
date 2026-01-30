<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportUsers extends Model
{
    use HasFactory;
    public $table = "report_users";

    public function by_user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(Users::class, 'reported_user_id');
    }
}
