<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use DB;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\Cargo;
use App\Models\Utilidades;

class CargosController extends Controller
{
    public function index(){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        /*$menu = Modulo::GenerarMenu($rol->id);*/
        return view('Admin.sistema.catalogos.cargos', compact('user', 'rol'/*, 'menu'*/));
    }

    public function save(Request $r){
        try{
            $msjVal = Utilidades::MensajesValidaciones();

            $validador = Validator::make($r->all(), [
                'nombre' => 'required|string|max:255'
            ], $msjVal);          

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            $data = array(
                'nombre' => $r->nombre
            );          

            if($r->id==null){
               Cargo::create($data);
            }else{
                Cargo::where('id', $r->id)->update($data);
            }

            return response()->json(["status"=>200, "msj"=>"success"]);

        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }
    }

    public function listar(){
        return Cargo::all();
    }

    public function obtener(Request $r){
        return Cargo::where('id', $r->id)->first();
    }

    public function delete(Request $r){
        try{
            Cargo::where('id', $r->id)->delete();
            return response()->json(["status"=>200, "msj"=>"success"]);
        }catch (QueryException $ex) {
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());       
            return response()->json(["status"=>500, "msj" => "error en delete"]);
        }
    }


    public function ListarCargosSelect(Request $r){
        return DB::table('cargos')
                ->select('id', 'nombre as text')
                ->get();

    }

}
