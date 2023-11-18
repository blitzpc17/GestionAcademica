<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\Utilidades;

class RolesController extends Controller
{
    public function index(){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        $menu = Modulo::GenerarMenu($rol->id);
        return view('Admin.sistema.catalogos.roles', compact('user', 'rol', 'menu'));
    }

    public function save(Request $r){
        try{
            $msjval = Utilidades::MensajesValidaciones();
            $validador = Validator::make($r->all(), [
                'nombre' => 'required|string|max:255'
            ], $msjval);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            $data = array(
                'nombre' => $r->nombre
            );          

            if($r->id==null){
               Rol::create($data);
            }else{
                Rol::where('id', $r->id)->update($data);
            }

            return response()->json(["status"=>200, "msj"=>"success"]);

        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }
    }

    public function listar(){
        return Rol::all();
    }

    public function obtener(Request $r){
        return Rol::where('id', $r->id)->first();
    }

    public function delete(Request $r){
        try{
            Rol::where('id', $r->id)->delete();
            return response()->json(["status"=>200, "msj"=>"success"]);
        }catch (QueryException $ex) {
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());       
            return response()->json(["status"=>500, "msj" => "error en delete"]);
        }
    }


    public function ListarRolesSelect(Request $r){
        return DB::table('roles')
                ->select('id', 'nombre as text')
                ->get();

    }


}
