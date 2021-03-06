<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HORARIO_DISPONIBLE extends Model
{
    protected $table = 'HORARIO_DISPONIBLE';
    public $timestamps = false;
    protected $fillable = [
        'DIA',
        'HORA_INICIO',
        'HORA_FIN',
        'ID_DOCENTE'
    ];
}
