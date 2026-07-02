<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    protected static function booted(): void
    {
        static::creating(function (Category $c) {
            if (empty($c->slug)) $c->slug = Str::slug($c->name);
        });
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }
}
