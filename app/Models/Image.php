<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable = [
        'category_id',
        'hash',
        'original_name',
        'path',
        'mime',
        'size',
    ];

    protected $appends = ['url'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->path);
    }

    public static function hashFor(\Illuminate\Http\UploadedFile $file): string
    {
        return hash_file('sha256', $file->getRealPath());
    }
}
