<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class Modulo extends Model
{
    use HasFactory;

    protected $table =  "modulos";
    protected $primaryKey = "id";

    protected $fillable = ['nombre', 'ruta', 'icono', 'modulo_padre_id'];

    public static function GenerarMenu($rolId){

        $modulos = DB::table('modulos as mod')->get();

        $data = DB::table('permisos as rp')
            ->join('modulos as m', 'rp.modulos_id','m.id')             
            ->where('rp.roles_id', $rolId)
            ->select('m.id as idHijo','m.nombre as moduloHijo', 'm.ruta as rutaHijo', 'm.modulo_padre_id as padreId' )
            ->get();

        $primerNivel = $data->map(function($item) use ($modulos) {
            $padre = $modulos->where('id', $item->padreId)->first();  
            if($padre->ruta=='#'){
                return $modulos->where('id', $item->idHijo)->first();
            }

            return $padre;
                
        })->unique('id');

        $padres = $primerNivel->map(function($item) use ($modulos){                 
            return $modulos->where('id', $item->modulo_padre_id)->first(); 
        })->unique('id');



        return array("hijos"=>$data, "primerNivel" => $primerNivel, "padres" => $padres);

           
    }

}
