<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Articles extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'source_id',
        'category',
        'title',
        'summary',
        'content',
        'url_image',
        'published_at'
    ];

    /**
     * Relation avec la source de l'article
     */
    public function source(): BelongsTo
    {
        return $this->belongsTo(Sources::class, 'source_id');
    }
}
