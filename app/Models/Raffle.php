<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Raffle extends Model
{
    protected $fillable = [
        'title',
        'description',
        'url_image',
        'prize',
        'start_date',
        'end_date',
        'price_tickets',
        'total_tickets',
        'tickets_sold',
        'status',
        'user_id'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    // Relación con User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // verificar boletos restantes
    public function getRemainingTicketsAttribute()
    {
        return $this->total_tickets - $this->tickets_sold;
    }

    // verificar si hay boletos disponibles
    public function getHasAvailableTicketsAttribute()
    {
        return $this->remaining_tickets > 0;
    }

    // filtrar rifas activas
    public function scopeActive($query)
    {
        return $query->where('status', 'ongoing');
    }
}
