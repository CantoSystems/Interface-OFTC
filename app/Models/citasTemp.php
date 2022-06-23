<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class citasTemp extends Model
{
    protected $fillable = [
        'paciente','statusCita','fechaCita'
    ];
}