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
use App\Models\EnvioProcedimiento;

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

            if($r->roles<1){
                return response()->json(["status"=>422, 'errors'=>["roles" =>["No se ha asignado ningún destino."]]]);
            }

            $msjval = Utilidades::MensajesValidaciones();
            $validador = Validator::make($r->all(), [
                'iso' => 'required|string|max:10',
                'codigo' => 'required|string|max:255',
                'formato' => 'required|string|max:255',     
                'roles' => 'required',
                'fecha' => 'required'

            ], $msjval);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }
            $fechaVisualizacion = Utilidades::FormatearFecha("d/m/Y", $r->fecha, "Y-m-d");

            $data = array(
                'iso'       => $r->iso,
                'codigo'    => $r->codigo,
                'formato'   => $r->formato,
                'RolId'     => $r->roles,
                'fechaLimiteVisualizacion' => $fechaVisualizacion
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
        return DB::table('procedimientos as p')
        ->join('roles as r', 'p.RolId', '=', 'r.id')
        ->select(
            'p.id as ProcedimientoId',
            'p.iso as Iso',
            'p.codigo as Codigo',
            'p.formato as Formato',
            'p.layout as Layout',
            'p.entregable as Entregable',
            'p.fechaLimiteVisualizacion as FechaVisualizacion',
            'p.RolId as RolId',
            'r.nombre as Rol'
        )
        ->get();
    }

    public function obtener(Request $r){

        return DB::table('procedimientos as p')
        ->join('roles as r', 'p.RolId', '=', 'r.id')
        ->where('p.Id', $r->id)
        ->select(
            'p.id as ProcedimientoId',
            'p.iso as Iso',
            'p.codigo as Codigo',
            'p.formato as Formato',
            'p.layout as Layout',
            'p.entregable as Entregable',
            'p.fechaLimiteVisualizacion as FechaVisualizacion',
            'p.RolId as RolId',
            'r.nombre as Rol'
        )
        
        ->first();
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
            $procedimiento = null;
            if($r->tipo==null){
                $procedimiento = EnvioProcedimiento::where('id', $r->id)->first();
            }else{
                $procedimiento = Procedimiento::where('id', $r->id)->first();
            }
            
            $rutaArchivo = "Procedimientos/";
            if($r->tipo=="l"&& $procedimiento->layout!=null){
                $rutaArchivo.= $procedimiento->layout;
            }elseif($r->tipo=="e" && $procedimiento->entregable!=null){
                $rutaArchivo.= $procedimiento->entregable;
            }else{
               //descarga de recibidos
               $rutaArchivo.= "Seguimiento/".$procedimiento->entregableDestino;
            }    
            if(Storage::disk('archivos')->exists($rutaArchivo)){
                return Storage::disk('archivos')->download($rutaArchivo);
            }           

        }catch(Exception $ex ){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            echo 'No se encuentra el archivo.';
        }
   
    }


    public function EnvioADestino(Request $r){
        try{            
            if($r->op == 'I'){
                //insercion masiva
                $usuarios = $r->usuariosId;
                $procedimientoId = $r->procedimientoId;

                $data = [];

                foreach($usuarios as $us){
                    $data[] = [
                                "procedimientosId"=> $procedimientoId,
                                "estadoId" => 1,
                                "usuarioId" => $us
                    ];
                  
                }
                EnvioProcedimiento::insert($data);
                return response()->json(["status"=>200, "msj"=>"success"]);

            }else if($r->op == 'S'){
                //enviar archivo firmado
                $user = Auth::user();
                //subir archivo
                $procedimiento = Procedimiento::where('id', $r->ProcedimientoId )->first();
                $rules = ['layout' => 'required|mimes:pdf,doc,docx'];
                $msjval = Utilidades::MensajesValidaciones();
                $validateFile = Validator::make($r->all(), $rules, $msjval);
                if($validateFile->fails()){
                    return response()->json(["status" => 422, 'errors'=>$validateFile->errors()]);
                }
                $rutalayout = "Procedimientos/Seguimiento";
                $layout = $r->layout; 
                $nombreLayout = $procedimiento->iso.'-'.$user->name. '.' . $layout->getClientOriginalExtension();         
                Storage::disk('archivos')->putFileAs($rutalayout, $layout, $nombreLayout); 
                //$data = array_merge($data, ['layout' => $nombreLayout]);            
                //actualizacion individual
                EnvioProcedimiento::where('id',$r->EnvioId)
                ->update([
                    "entregableDestino"    =>  $nombreLayout,
                    "estadoId"      =>  2//SUBIDO
                ]);
                return response()->json(["status"=>200, "msj"=>"success"]);
                
            }else{
                //cerrar
                EnvioProcedimiento::where($r->EnvioId)
                ->update([
                    "estadoId"      =>  3
                ]);
                return response()->json(["status"=>200, "msj"=>"success"]);
            }

        }catch(Exception $ex ){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en el envio del procedimiento a destino"]);
        }

    }

    public function VisualizarDestinos(Request $r){
        return DB::table('roles as r')
        ->join('users as u', 'r.id', '=', 'u.roles_id')
        ->join('recursos as rec', 'u.recursos_id', '=', 'rec.id')
        ->join('cargos as c', 'rec.cargos_id', '=', 'c.id')
        ->select(
            'u.id as UsuarioId',
            DB::raw("CONCAT(rec.nombres, ' ', rec.apellido_paterno, ' ', rec.apellido_materno) as Nombre"),
            'c.nombre as Cargo'
        )
        ->where('r.id', '=', $r->RolId)
        ->get();
    }

    public function ListarDocumentosRol(Request $r){
        $user = Auth::user();
        return DB::table('procedimientos_envios as pe',  'u.id', 'pe.usuarioId')
        ->join('procedimientos as p', 'p.id', 'pe.procedimientosId') 
        ->join('estados_procedimiento as ep', 'pe.estadoId', '=', 'ep.id')
        ->join('users as u', 'pe.usuarioId',  'u.id')  
        ->join('roles as r', 'u.roles_id', '=', 'r.id')  
        ->where('u.id', '=', $user->id)  
       ->whereRaw('current_date() < p.fechaLimiteVisualizacion')
        ->select(
            'p.id as ProcedimientoId',
            'p.iso as Iso',
            'p.codigo as Codigo',
            'p.formato as Formato',
            'p.layout as Layout',
            'p.entregable as Entregable',
            'p.fechaLimiteVisualizacion as FechaVisualizacion',
            'p.RolId as RolId',
            'r.nombre as Rol',
            'ep.id as EstadoId',
            'ep.nombre as Estado',
            'pe.entregableDestino'
        )
        ->get();
    }

    public function VisualizarDocumentosUsuario(Request $r){
        return DB::table('procedimientos_envios as pe')
        ->select(
            'pe.id as ProcedimientoEnvioId',
            'proc.id as ProcedimientoId',
            'us.id as UsuarioId',
            'proc.iso as Iso',
            'proc.codigo as Codigo',
            'proc.formato as Formato',
            'proc.layout as Layout',
            'ep.id as EstadoId',
            'ep.nombre as Estado'
        )
        ->join('procedimientos as proc', 'pe.procedimientosId', '=', 'proc.id')
        ->join('estados_procedimiento as ep', 'pe.estadoId', '=', 'ep.id')
        ->join('users as us', 'pe.usuarioId', '=', 'us.id')
        ->where('pe.usuarioId', '=', $r->UsuarioId)
        ->where('proc.id', '=', $r->ProcedimientoId)      
        ->first();
    }


    public function RecepcionProcedimientos(Request $r){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        $menu = Modulo::GenerarMenu($rol->id);
        return view('Admin.procedimientos.VisualizacionDocumentos', compact('user', 'rol', 'menu'));
    }


    public function ListarDocumentosProcedimientoInvolucrados(Request $r){
        $user = Auth::user();
        return DB::table('procedimientos_envios as pe',  'u.id', 'pe.usuarioId')
        ->join('procedimientos as p', 'p.id', 'pe.procedimientosId') 
        ->join('estados_procedimiento as ep', 'pe.estadoId', '=', 'ep.id')
        ->join('users as u', 'pe.usuarioId',  'u.id')  
        ->join('recursos as rec', 'u.recursos_id', 'rec.Id')
        ->join('roles as r', 'u.roles_id', '=', 'r.id')  
        ->where('p.Id', $r->ProcedimientoId)  
        ->select(
            'pe.id as ProcedimientoEnvioId',
            DB::raw("concat(rec.nombres, ' ', rec.apellido_paterno, ' ', rec.apellido_materno) as Nombre") ,
            'ep.id as EstadoId',
            'ep.nombre as Estado',
            'pe.entregableDestino'
        )
        ->get();
    }



}
