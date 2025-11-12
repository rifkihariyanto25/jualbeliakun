<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Hero extends Model
{
    protected $fillable = [
        'hero_name',
        'url',
        'hero_image',
        'total_skins'
    ];

    public function skins(): HasMany
    {
        return $this->hasMany(Skin::class);
    }
}
