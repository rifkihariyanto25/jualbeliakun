<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'rank',
        'highest_rank',
        'winrate',
        'total_matches',
        'honor',
        'glory',
        'immortal',
        'hero_count',
        'skin',
        'price',
        'heroes',
        'skins',
    ];

    protected $casts = [
        'heroes' => 'array',
        'skins' => 'array',
        'winrate' => 'decimal:2',
    ];
}
