<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
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
        $menu = Modulo::GenerarMenu($rol->id);
        return view('Admin.procedimientos.Captura_Consulta', compact('user', 'rol', 'menu'));
    }

    public function save(Request $r){
        try{
            $msjval = Utilidades::MensajesValidaciones();
            $validador = Validator::make($r->all(), [
                'iso' => 'required|string|max:10',
                'codigo' => 'required|string|max:255',
                'formato' => 'required|string|max:255',            
            ], $msjval);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            $data = array(
                'iso' => $r->iso,
                'codigo' => $r->codigo,
                'formato' => $r->formato,
            ); 

            //subir layout
           
            $layout = $r->layout;           
            if($r->id ==null || $layout!=null){   
                $rules = ['layout' => 'required|mimes:pdf,doc,docx'];
                $validateFile = Validator::make($r->all(), $rules, $msjval);
                if($validateFile->fails()){
                    return response()->json(["status" => 422, 'errors'=>$validateFile->errors()]);
                }
                $rutalayout = "Procedimientos";
                $nombreLayout = rand() . '.' . $layout->getClientOriginalExtension();         
                Storage::disk('archivos')->putFileAs($rutalayout, $layout, $nombreLayout); 
                $data = array_merge($data, ['layout' => $nombreLayout]);            
            }

            //subir entregable            
            $entregable = $r->entregable;            
            if($entregable!=null){  
                $rules = ['entregable' => 'required|mimes:pdf,doc,docx'];
                $validateFile = Validator::make($r->all(), $rules, $msjval);
                if($validateFile->fails()){
                    return response()->json(["status" => 422, 'errors'=>$validateFile->errors()]);
                }
                $rutaentregable = "Procedimientos";
                $nombreEntregable = rand() . '.' . $entregable->getClientOriginalExtension();          
                Storage::disk('archivos')->putFileAs($rutaentregable, $entregable, $nombreEntregable);    
                $data = array_merge($data, ['entregable' => $nombreEntregable]);             
            }                     

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
            $procedimiento = Procedimiento::where('id',$r->id)->first();
            Procedimiento::where('id', $r->id)->delete();
            //eliminar archivos
            $rutaArchivo = "Procedimientos";
            Storage::disk('archivos')->deleteDirectory($rutaArchivo);

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
        try{
            $procedimiento = Procedimiento::where('id', $r->id)->first();
            $rutaArchivo = "Procedimientos/";
            if($r->tipo=="l"&& $procedimiento->layout!=null){
                $rutaArchivo.= $procedimiento->layout;
            }elseif($r->tipo=="e" && $procedimiento->entregable!=null){
                $rutaArchivo.= $procedimiento->entregable;
            }else{
                echo 'No se encuentra el archivo.';
                return;
            }    
            if(Storage::disk('archivos')->exists($rutaArchivo)){
                return Storage::disk('archivos')->download($rutaArchivo);
            }           

        }catch(Exception $ex ){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            echo 'No se encuentra el archivo.';
        }
      
        
        
   
    }



}
