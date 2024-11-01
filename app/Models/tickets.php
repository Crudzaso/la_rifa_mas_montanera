<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class tickets extends Model
{
    use HasFactory, Notifiable;

    public function raffles() {
        return $this->belongsTo(raffles::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function winner(){
        return $this->hasOne(winner::class);
    }
    protected $fillable = [
        'ticket_number',
        'purchase_date',
    ];

}
