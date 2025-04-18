<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reserva extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'reservas';

    protected $fillable = [
        'comensal_id',
        'mesa_id',
        'fecha',
        'hora',
        'numero_de_personas',
    ];

    protected $dates = ['deleted_at'];

    // Relaciones
    public function comensal()
    {
        return $this->belongsTo(\App\Models\Comensal::class, 'comensal_id');
    }

    public function mesa()
    {
        return $this->belongsTo(\App\Models\Mesa::class, 'mesa_id');
    }
}