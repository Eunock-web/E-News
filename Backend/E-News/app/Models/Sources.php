<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sources extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'url_logo',
        'is_active'
    ];

    /**
     * Relation avec les articles de cette source
     */
    public function articles(): HasMany
    {
        return $this->hasMany(Articles::class, 'source_id');
    }
}
