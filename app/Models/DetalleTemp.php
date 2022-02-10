<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleTemp extends Model{
    protected $fillable = [
        'codigo','descripcion','um','cantidad','precio_unitario','importe'
    ];

    protected $table = 'detalletemps';
}
