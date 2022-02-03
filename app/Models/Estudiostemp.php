<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiostemp extends Model
{
    protected $fillable = [
        'id','fecha','folio','doctor','paciente','servicio','met_pago','CFDI','subtotal','descuento','iva','total'
    ];
}
