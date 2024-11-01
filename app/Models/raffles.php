<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class raffles extends Model
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public function ticket(){
        $this->hasMany(tickets::class);
    }

    public function winner(){
        $this->hasMany(winner::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */

}
