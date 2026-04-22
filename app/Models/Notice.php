<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    protected $fillable = ['date', 'heading', 'copy', 'tag', 'ordinamento'];
}
