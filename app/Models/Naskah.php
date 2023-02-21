<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;

class Naskah extends Model
{
    use Searchable;

    protected $table = 'penerbitan_naskah';
    protected $guarded = [];
    // protected $keyType = 'string';
    // public $incrementing = false;
}
