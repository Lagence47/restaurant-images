<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'hash',
        'original_name',
        'categorie',
        'path',
        'mime_type',
        'size',
    ];

    /**
     * URL publique pour accéder à l'image
     */
    public function getUrlAttribute(): string
    {
        return url("/api/v1/images/{$this->hash}");
    }
}
