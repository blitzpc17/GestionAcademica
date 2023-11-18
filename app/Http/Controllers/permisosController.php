<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\Utilidades;

class PermisosController extends Controller
{
    public function index(){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        $menu = Modulo::GenerarMenu($rol->id);
        return view('Admin.Sistema.Acceso.permisos', compact('user', 'rol', 'menu'));
    }

    public function save(Request $r){
        try{
            $msjval = Utilidades::MensajesValidaciones();
            $validador = Validator::make($r->all(), [
                'rol' => 'numeric|min:0',
                'modulo' => 'numeric|min:0',
                'observacion' => 'max:500',            
            ], $msjval);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            $data = array(
                'roles_id' => $r->rol,
                'modulos_id' => $r->modulo,
                'observacion' => $r->observacion,
            );  
            
            //validar si ya se asigno
            if(Permiso::where('roles_id', $r->rol)->where('modulos_id', $r->modulo)->first()!=null){
                return response()->json(["status" => 422, 'errors'=>["modulo"=>["Ya fue otorgado el permiso a este módulo."]]]);
            }

            if($r->id==null){
               Permiso::create($data);
            }else{
                Permiso::where('id', $r->id)->update($data);
            }

            return response()->json(["status"=>200, "msj"=>"success"]);

        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }
    }

    public function listar(){
        return DB::table('permisos as per')
            ->join('roles as r', 'per.roles_id', 'r.id')
            ->join('modulos as mod', 'per.modulos_id', 'mod.id')
            ->select('per.id', 'r.nombre as rol', 'mod.nombre as modulo')
            ->get();
    }

    public function obtener(Request $r){
        return Permiso::where('id', $r->id)->first();
    }

    public function delete(Request $r){
        try{
            Permiso::where('id', $r->id)->delete();     
            return response()->json(["status"=>200, "msj"=>"success"]);
        }catch (QueryException $ex) {
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());       
            return response()->json(["status"=>500, "msj" => "error en delete"]);
        }
    }
    

}
