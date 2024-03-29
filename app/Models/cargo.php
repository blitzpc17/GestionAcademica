<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;
     
    protected $table =  "cargos";
    protected $primaryKey = "id";

    protected $fillable = ['nombre'];

    public $timestamps = false;
}
