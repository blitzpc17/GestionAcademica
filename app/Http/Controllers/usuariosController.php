<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;
use \stdClass;
use Session;
use DB;

use App\Models\Recurso;
use App\Models\Rol;
use App\Models\User;
use App\Models\Modulo;
use App\Models\Utilidades;

class UsuariosController extends Controller
{
    public function authenticate(Request $r){

        try{
            $reglas = [             
                'email' => 'required|email|max:255',
                'password' => 'required|string|max:255|min:8'               
            ];

            $msjval = Utilidades::MensajesValidaciones();

            $validador = Validator::make($r->all(), $reglas, $msjval);

            if($validador->fails()){
                return back()->withErrors($validador)->withInput();
            }

            if(Auth::attempt(['email' => $r->email, 'password' => $r->password])){

                $r->session()->regenerate(); 
              
                return redirect()->intended('admin/home');
               
            }           

            return back()->withErrors([
                'unauthorizate' => 'Los datos ingresados no coinciden con nuestros registros.',
            ])->onlyInput('email');


        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }

    }

    public function logauth(Request $r){        
        Auth::logout();
        Session::flush();
        return redirect()->route('admin.login');
    }

    public function save(Request $r){
        try{
            $reglas = [
                //persona              
                'apellidoPaterno' => 'required|string|max:255',
                'apellidoMaterno' => 'required|string|max:255',
                'nombres' => 'required|string|max:255',
                'matricula' => 'required|string|min:8',
                'telefono' => 'required|string|max:13',
                'domicilio' => 'required|string|max:255',
                'fechaIngreso' => 'required|date',
                'cargoId' => 'required',
                //user
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',               
                'rolId' => 'required'
            ];          

            $messages = Utilidades::MensajesValidaciones();

            $validador = Validator::make($r->all(), $reglas, $messages);

            if($validador->fails()){
                return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
            }

            if($r->id == null||($r->id!=null && !empty($r->password)) ){              

                $rules = [ 'password' => 'required|string|min:8'];
                $validador = Validator::make($r->all(), $rules, $messages);       
                if($validador->fails()){
                    return response()->json(["status" => 422, 'errors'=>$validador->errors()]);
                }
            }

            $dataPersona = array(               
                "apellido_paterno" => $r->apellidoPaterno,
                "apellido_materno" => $r->apellidoMaterno,
                "nombres" => $r->nombres,
                "matricula" => $r->matricula,
                "telefono" => $r->telefono,
                "domicilio" => $r->domicilio,
                "fecha_ingreso" => $r->fechaIngreso,
                "cargos_id" => $r->cargoId                
            );

            $dataUser = array(
                "name" => $r->name,
                "email" => $r->email,
                "password" => bcrypt($r->password),
                "roles_id" => $r->rolId
            );

            if($r->id==null){                
                $result = Recurso::create(array_merge($dataPersona,["baja"=> 0]));    
                $personaId = $result->id;  
                $dataUser = array_merge($dataUser, ["recursos_id"=> $personaId]);
                User::create($dataUser);

            }else{               
                User::where('id', $r->id)->update($dataUser);
                $user = User::where('id', $r->id)->first();
                Recurso::where('id', $user->recursos_id)->update($dataPersona);
            }         

            return response()->json(["status" => 200, "msj"=> "ok"]);

        }catch(Exception $ex){
            Log::error('Error en la clase ' . __CLASS__ . ' en la línea ' . __LINE__ . ': ' . $ex->getMessage());
            return response()->json(["status"=>500, "msj" => "error en save"]);
        }
    } 
    
    public function home(Request $r){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        /*$menu = Modulo::GenerarMenu($rol->id);*/
    return view('Admin.Sistema.Acceso.Home', compact('user', 'rol'/*, 'menu'*/));
    }


    public function gestionUsuariosSistema(){
        $user = Auth::user();
        $rol = Rol::where('id', $user->roles_id)->first();
        /*$menu = Modulo::GenerarMenu($rol->id);*/
        return view('Admin.Sistema.Usuarios.Usuarios_Sistema', compact('user', 'rol'/*, 'menu'*/));
    }

    public function listar(){
        return DB::table('users as us')
            ->join('roles as r', 'us.roles_id','r.id' )
            ->join('recursos as per', 'us.recursos_id', 'per.id')
            ->select('us.id', 'us.name as alias', 'us.email as correo', 'r.nombre as rol', 'per.baja',
                DB::raw("CASE WHEN per.baja = 1 THEN 'DESACTIVADO' ELSE 'ACTIVO' END AS estado"))
            ->get();
    }

    public function obtener(Request $r){
        //usuario y personal data
        $dataUser = DB::table('users as us')
                    ->where('id', $r->id)
                    ->select('us.id', 'us.name', 'us.email', 'us.recursos_id as recursoId', 'us.roles_id as rolesId')
                    ->first();

        $dataRecurso = DB::table('recursos as rec')
                        ->where('id', $dataUser->recursoId)
                        ->select('rec.apellido_paterno as apellidoPaterno', 'rec.apellido_materno as apellidoMaterno', 'rec.nombres as nombres',
                        'rec.matricula', 'rec.telefono', 'rec.domicilio', 'rec.fecha_ingreso as fechaIngreso', 'rec.cargos_id as cargoId')
                        ->first();

        return response()->json(["user" => $dataUser, "recurso" => $dataRecurso]);
    }

    public function desactivar(Request $r){
        DB::table('recursos as rec')
            ->join('users as us', 'rec.id', 'us.recursos_id')
            ->where('us.id', $r->id)
            ->update(["rec.baja"=>$r->activo==1]);

        return response()->json(["status" => 200, "msj"=> "ok"]);
    }


}
