<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    protected $fillable = [
        'sources_id',
        'category',
        'title',
        'summary',
        'content',
        'image_url',
        'published_at'
    ];
}
