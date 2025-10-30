<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sources extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'url_logo',
        'is_active'
    ];
}
