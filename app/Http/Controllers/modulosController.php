<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\Utilidades;

class ModulosController extends Controller
{
    public function index(){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        $menu = Modulo::GenerarMenu($rol->id);
        return view('Admin.Sistema.Acceso.modulos', compact('user', 'rol', 'menu'));
    }

    public function save(Request $r){
        try{
            $msjval = Utilidades::MensajesValidaciones();
            $validador = Validator::make($r->all(), [
                'nombre' => 'required|string|max:255',
                'ruta' => 'max:255',
                'icono' => 'max:255',            
            ], $msjval);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            $data = array(
                'nombre' => $r->nombre,
                'ruta' => $r->ruta,
                'icono' => $r->icono,
            );    
            
            if($r->modulo!=-1){
                $data = array_merge($data, ["modulo_padre_id"=> $r->modulo]);
            }else{
                $data = array_merge($data, ["modulo_padre_id"=> null]);
            }

            if($r->id==null){
               Modulo::create($data);
            }else{
                Modulo::where('id', $r->id)->update($data);
            }

            return response()->json(["status"=>200, "msj"=>"success"]);

        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }
    }

    public function listar(){
        return Modulo::all();
    }

    public function obtener(Request $r){
        return Modulo::where('id', $r->id)->first();
    }

    public function delete(Request $r){
        try{
            Modulo::where('id', $r->id)->delete();     
            return response()->json(["status"=>200, "msj"=>"success"]);
        }catch (QueryException $ex) {
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());       
            return response()->json(["status"=>500, "msj" => "error en delete"]);
        }
    }


    public function ListarModulosSelect(Request $r){
        return DB::table('modulos')
                ->select('id', 'nombre as text')
                ->get();

    }


    public function ListarModulosHijosSelect(Request $r){
        return DB::table('modulos')
                ->select('id', 'nombre as text')
                ->where('ruta','<>', '#')
                ->wherenotnull('ruta')
                ->get();

    }
   

}
