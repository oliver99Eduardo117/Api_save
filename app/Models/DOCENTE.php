<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DOCENTE extends Model
{
    protected $table = 'DOCENTE';
    public $timestamps = false;
    protected $fillable = [
        'ID',
        'PERIODO',
        'DESCRIPCION',
        'CORREO',
        'TELEFONO'
    ];
}
