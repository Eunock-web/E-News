<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Articles extends Model
{
    use HasFactory;
    protected $fillable = [
        'sources_id',
        'category',
        'title',
        'summary',
        'content',
        'url_image',
        'published_at'
    ];
}
