<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    use HasFactory;

    protected $table =  "procedimientos";
    protected $primaryKey = "id";

    protected $fillable = ['iso', 'codigo', 'formato', 'layout', 'entregable', 'RolId', 'fechaLimiteVisualizacion'];

    
}
