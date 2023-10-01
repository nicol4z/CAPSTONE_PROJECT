<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Solicitante;
use App\Models\Empleado;
use App\Models\Role;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller



{

    public function usuarios ()
    {

        $usuarios = User::with('empleado','solicitante')->get();

        return $usuarios;

    }




    public function login(Request $request)
    {
       
        try {
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Error de validación, verifique que los datos estén correctamente ingresados.',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Error al ingresar, las credenciales de acceso son incorrectas o el usuario no está registrado en el sistema.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            if(auth()->check() && (auth()->user()->estado == 0)){
                Auth::logout();

                return response()->json([
                    'status' => false,
                    'message' => 'Tu cuenta ha sido suspendida, por favor contáctese con el Administrador.'
                ], 403);


        }

            $token = $user->createToken("myapptoken")->plainTextToken;




            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'user' => $user,
                'token' => $token
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request){
        User::whereId($request->user_id)->update([
            'estado' => $request->estado
        ]);

        $personal = Empleado::where('correo',$request->correo);
        if($personal != null){

            $personal->update([
                'estado' => $request->estado
            ]);

        }

        return response()->json([
            'status' => true,
            'message' => 'Estado actualizado del usuario',
        ], 200);
    }
    
    public function updateSolicitanteStatusFromController($correo,$estado){
        $user = User::where('email',$correo);
        if($user != null){
            $user->update(['estado' => $estado]);
        }
    }
    public function updateStatusFromController($correo,$estado){
        $user = User::where('email',$correo);
        if($user != null){
            $user->update(['estado' => $estado]);
        }
    }
    public function detalles_Usuario(){
       
        if(auth()->user()->rut != null){
            $empleado = Empleado::find(Auth::user()->rut);
            return $empleado;
        }
        else if(auth()->user()->rut_solicitante != null){
            $solicitante = Solicitante::find(Auth::user()->rut_solicitante);
            return $solicitante;
        }
        else{
            return Auth::user();
        }

        return;


    }
    public function updatePassword(Request $request)
    {
        $respuesta = [];
            # Validation
            $request->validate([
                'old_password' => 'required',
                'new_password' => 'required|confirmed',
            ]);


            #Match The Old Password
            if(!Hash::check($request->old_password, auth()->user()->password)){
                return back()->with("error", "La contraseña actual no coincide");
            }


            #Update the new Password
            User::whereId(auth()->user()->id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            array_push($respuesta, ['status' => 'la contraseña a sido actualizada exitosamente']);
            return response()->json($respuesta);
    }
    public function updatePasswordAdmin(Request $request)
    {
        $respuesta = [];
            # Validation
            $request->validate([
                'new_password' => 'required|confirmed',
            ]);




            #Update the new Password
            User::whereId($request->user_id)->update([
                'password' => Hash::make($request->new_password)
            ]);
            array_push($respuesta, ['status' => 'la contraseña a sido actualizada exitosamente']);
            return response()->json($respuesta);
    }


    public function registerFromController($nombre,$apellido,$correo,$rut,$rol){

        $user = User::create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $correo,
            'password' => bcrypt($rut),
            'role' => $rol,
            'rut'=>$rut,
            'rut_solicitante'=> null,
        ]);
        return response('Creado con exito',201);

    }
    public function registerSolicitanteFromController($nombre,$apellido,$correo,$rut,$rol){

        $user = User::create([
            'nombre' => $nombre,
            'apellido' => $apellido,
            'email' => $correo,
            'password' => bcrypt($rut),
            'role' => $rol,
            'rut_solicitante'=>$rut,
            'rut'=> null
        ]);
        return response('Creado con exito',201);

    }



    public function register(Request $request){
        $fields = $request->validate([
            'nombre' => 'required|string',
            'apellido' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed',
            'role' => 'required',

        ]);
        $user = User::create([
            'nombre' => $fields['nombre'],
            'apellido' => $fields['apellido'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role' => $fields['role'],
            'rut' => $request->rut
        ]);
       $empleado = $user->empleado;


         $token = $user->createToken("myapptoken")->plainTextToken;
         $response = [
             'user' => $user,
             'token' => $token
         ];

        return response($response,201);
    }

    public function logout(Request $request){
        auth()->user()->tokens()->delete();
        return ['message' => 'Sesión cerrada'];
    }



    public function syncUsers(){
        
        $empleados = Empleado::all();
        $solicitantes = Solicitante::all();
        $roles = Role::all();
   
                foreach($empleados as $empleado){
              
                    $usuario = User::where('rut',$empleado->rut_empleado)->first();
                    if($usuario == null){

                        $rol = $roles->where('descripcion', $empleado->rol)->first();
                        
                        $user = User::create([
                            'nombre' => $empleado->nombre,
                            'apellido' => $empleado->apellido,
                            'email' => $empleado->correo,
                            'password' => bcrypt($empleado->rut_empleado),
                            'role' =>  $rol->id_rol,
                            'rut' => $empleado->rut_empleado
                        ]);
                    }
                }

                foreach($solicitantes as $solicitante){
              
                    $usuario = User::where('rut_solicitante',$solicitante->rut_solicitante)->first();
                    if($usuario == null){

                        $rol = $roles->where('descripcion', 'Solicitante')->first();
                        
                        $user = User::create([
                            'nombre' => $solicitante->nombre,
                            'apellido' => $solicitante->primer_apellido,
                            'email' => $solicitante->correo,
                            'password' => bcrypt($solicitante->rut_solicitante),
                            'role' =>  $rol->id_rol,
                            'rut_solicitante' => $solicitante->rut_solicitante
                        ]);
                    }
                }
            }
}
