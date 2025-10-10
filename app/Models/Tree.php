<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tree extends Model
{
    protected $fillable = [
        'asset_id',
        'type',
        'x',
        'y',
        'dead',
        'latin_name',
        'common_name',
        'crown_height',
        'tree_species',
    ];

    protected $casts = [
        'dead' => 'boolean',
        'x' => 'decimal:7',
        'y' => 'decimal:7',
    ];

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TreeImage::class);
    }

    public function approvedImages(): HasMany
    {
        return $this->hasMany(TreeImage::class)->where('approved', true);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating');
    }
}
