<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnvioProcedimiento extends Model
{
    use HasFactory;

    protected $table =  "procedimientos_envios";
    protected $primaryKey = "id";

    protected $fillable = ['procedimientosId', 'estadoId', 'usuarioId', 'entregableDestino'];

    public $timestamps = false;





}
