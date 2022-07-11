<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TURNO extends Model
{
    protected $table = 'TURNO';

    protected $fillable = [
        'CLAVE_TURNO',
        'NOMBRE'
    ];
}
