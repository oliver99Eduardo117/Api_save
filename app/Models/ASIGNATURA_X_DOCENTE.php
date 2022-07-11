<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ASIGNATURA_X_DOCENTE extends Model
{
    protected $table = 'ASIGNATURA_X_DOCENTE';
    public $timestamps = false;
    protected $fillable = [
        'ASIGNATURA',
        'CAMPUS',
        'ID_DOCENTE',
    ]; 
}
