<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Avviso extends Model
{
    protected $fillable = [
        'titolo',
        'contenuto',
        'data_pubblicazione',
        'pubblicato',
    ];

    protected function casts(): array
    {
        return [
            'data_pubblicazione' => 'date',
            'pubblicato' => 'boolean',
        ];
    }

    /** Scope: solo avvisi pubblicati */
    public function scopePublicati(Builder $query): Builder
    {
        return $query->where('pubblicato', true);
    }
}
