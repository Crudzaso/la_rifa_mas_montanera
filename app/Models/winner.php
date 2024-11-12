<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class winner extends Model
{
    use HasFactory;

    public function raffles() {
        return $this->belongsTo(raffles::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function ticket(){
        return $this->belongsTo(tickets::class);
    }
    protected $fillable = [
        'prize',
        'awarded_date',
    ];
}
