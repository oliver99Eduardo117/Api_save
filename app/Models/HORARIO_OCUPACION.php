<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HORARIO_OCUPACION extends Model
{
    protected $table = 'HORARIO_OCUPACION';

    protected $fillable = [
        'ID_OCUPACION',
        'HORA_INICIO',
        'HORA_FIN',
        'DIA_OCUPACION'
    ];
}
