<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use DB;

use App\Models\Modulo;
use App\Models\Rol;
use App\Models\Utilidades;
use App\Models\Procedimiento;

class ProcedimientosController extends Controller
{
    public function index(){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        /*$menu = Modulo::GenerarMenu($rol->id);*/
        return view('Admin.procedimientos.Captura_Consulta', compact('user', 'rol', /*'menu'*/));
    }

    public function save(Request $r){
        try{
            $msjval = Utilidades::MensajesValidaciones();
            $validador = Validator::make($r->all(), [
                'iso' => 'required|string|max:10',
                'codigo' => 'required|string|max:255',
                'formato' => 'required|string|max:255',
                'layout' => 'required|mimes:pdf,doc,docx'
            ], $msjval);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            //subir layout
            $rutalayout = "Admin/Files/Procedimientos/{$r->codigo}";
            $layout = $r->layout;
            $nombreLayout = rand() . '.' . $layout->getClientOriginalExtension();    
            if($r->id==null || $layout!=null){              
                
                /*if(!Storage::exists($rutalayout)){
                    Storage::makeDirectory($rutalayout, 0775, true);
                }*/

               // $layout->move(public_path($rutalayout), $nombreLayout);  
               Storage::disk('procedimientos')->put($nombreLayout, $layout);             
            }

            $data = array(
                'iso' => $r->iso,
                'codigo' => $r->codigo,
                'formato' => $r->formato,
                'layout' => $nombreLayout
            );          

            if($r->id==null){
               Procedimiento::create($data);
            }else{
                Procedimiento::where('id', $r->id)->update($data);
            }

            return response()->json(["status"=>200, "msj"=>"success"]);

        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }
    }

    public function listar(){
        return Procedimiento::all();
    }

    public function obtener(Request $r){
        return Procedimiento::where('id', $r->id)->first();
    }

    public function delete(Request $r){
        try{
            Procedimiento::where('id', $r->id)->delete();
            //eliminar archivo
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


    public function DescargarArchivos(Request $r){
        
        $rutaArchivo = 'public/Admin/Files/Procedimientos/COD-23-RCUI/' . $r->nombreArchivo;
       // dd($rutaArchivo);
        return Storage::download($rutaArchivo);
        dd(Storage::disk('public')->exists($rutaArchivo));

        if (Storage::disk('public')->exists('Admin/Files/Procedimientos/COD-23-RCUI/{$r->nombreArchivo}') ){
            return response()->json(["status" => 400, "msj" => "No se encontro el archivo solicitado." ]);
        }
     /*   if (file_exists($rutaArchivo)) {
            return response()->download($rutaArchivo, $r->nombreArchivo);
        } else {          
            return response()->json(["status" => 400, "msj" => "No se encontro el archivo solicitado." ]);
        }*/

        //return Storage::download('file.jpg');
    }



}
