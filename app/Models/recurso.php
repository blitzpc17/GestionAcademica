<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recurso extends Model
{
    use HasFactory;

    protected $table =  "recursos";
    protected $primaryKey = "id";

    protected $fillable = 
        [
            'apellido_paterno', 
            'apellido_materno', 
            'nombres',
            'matricula',
            'telefono',
            'domicilio',            
            'fecha_ingreso',
            'cargos_id',
            'baja',
            'fecha_baja'
        ];

}
