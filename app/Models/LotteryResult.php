<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotteryResult extends Model
{
    protected $fillable = [
        'lottery',
        'slug',
        'date',
        'result',
        'series',
    ];
}
