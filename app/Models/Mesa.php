<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mesa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'mesas';

    protected $fillable = [
        'numero_mesa',
        'capacidad',
        'ubicacion',
        'state'
    ];

    protected $dates = ['deleted_at'];
}