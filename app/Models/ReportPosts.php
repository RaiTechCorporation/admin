<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportPosts extends Model
{
    use HasFactory;
    public $table = "report_posts";

    public function by_user()
    {
        return $this->belongsTo(Users::class, 'user_id');
    }
    public function post()
    {
        return $this->belongsTo(Posts::class, 'post_id');
    }
}
