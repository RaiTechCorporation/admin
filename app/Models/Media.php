<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'disk',
        'mime',
        'size',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        if (!$this->path) {
            return null;
        }

        if ($this->disk === 's3') {
            return \Storage::disk('s3')->url($this->path);
        }

        return config('app.url') . '/storage/' . $this->path;
    }
}
