<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comensal extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comensals';

    protected $fillable = [
        'nombre',
        'correo',
        'telefono',
        'direccion',
        'state'
    ];

    protected $dates = ['deleted_at'];

    // Relaciones
    public function reserva()
    {
        return $this->hasMany(\App\Models\Reserva::class, 'comensal_id');
    }
}