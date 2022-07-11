<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tokens extends Model
{
    protected $table = 'tokens';
    public $timestamps = false;
    protected $fillable = [
        'id_token',
        'token',
        'descripcion',
        'ip',
        'dominio'
    ];
}
