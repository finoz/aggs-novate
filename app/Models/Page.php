<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = [
        'slug',
        'titolo',
        'contenuto',
        'pubblicata',
        'in_menu',
        'ordinamento',
    ];

    protected function casts(): array
    {
        return [
            'contenuto'  => 'array',
            'pubblicata' => 'boolean',
            'in_menu'    => 'boolean',
        ];
    }

    public function scopePubblicate(Builder $query): Builder
    {
        return $query->where('pubblicata', true);
    }

    public function scopeInMenu(Builder $query): Builder
    {
        return $query->where('pubblicata', true)->where('in_menu', true);
    }
}
