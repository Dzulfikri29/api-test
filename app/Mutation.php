<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mutation extends Model
{
    protected $fillable = [
        'date',
        'amount',
        'type',
        'source',
        'note',
    ];
}
