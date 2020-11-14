<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\permisos;
use Illuminate\Support\Facades\DB;
use App\posts;
use App\comentarios;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable;

class AuthController extends Controller
{
    public function registro(Request $request)
    {
        $request->validate(
           [
               'name'=>'required',
               'email'=>'required|email',
               'password'=>'required',
               'edad'=>'required'
           ]);

           $usuario= new User();
           $usuario->name=$request->name;
           $usuario->email=$request->email;
           $usuario->password=Hash::make($request->password);
           $usuario->edad=$request->edad;
           $usuario->confirmation_code=Str::random(15);
           if($usuario->save())
           {
               
           $data=array(
            'email'=>$usuario->email,
            'name'=>$usuario->name,
            'confirmation_code'=>$usuario->confirmation_code

           );
            Mail::send('emails.correoconfirmacion', $data, function($message) use ($data){
                $message->from('19170025@uttcampus.edu.mx','Alex Lozano');
                $message->to($data['email'], $data['name'])->subject('Por favor confirma tu correo');
            });
               return response()->json($usuario,201);
           }
            return abort(400,"Error al crear usuario");
    }

    public function verificar(string $code)
    {
        $user = User::where('confirmation_code', $code)->first();

        if (! $user)
            return redirect('/');

        $user->confirmado = true;
        $user->confirmation_code = null;
        $user->save();

        return abort("Tu correo se ha verificado correctamente",201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales son incorrectas'],
            ]);
        }
        if($user->confirmado==false)
        {
            return response()->json("Necesitas verificar la cuenta primero crack");
        }
        if($user->rol=='admin')
        {
            $array=DB::table('permisos')->select('permiso')->take(7)->get()->pluck('permiso')->toArray();
            $token=$user->createToken($request->email,$array)->plainTextToken;
        }
        else
        {
            $array=DB::table('permisos')->select('permiso')->where('permiso','like','user%')->take(4)->get()->pluck('permiso')->toArray();
            $token=$user->createToken($request->email,$array)->plainTextToken;
        }
        return response()->json(["token"=>$token],201);
    }

    public function logout(Request $request)
    {
        return response()->json(["eliminados"=>$request->user()->tokens()->delete()],201);
    }

    public function usuarios(Request $request)
    {
        if($request->user()->tokenCan('admi:list'))
        {
            return response()->json(["Lista de usuarios"=>User::all()],200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        } 
    }

    public function eliminarUsuario(int $id,Request $request)
    {
        if($request->user()->tokenCan('admi:delete'))
        {
            $user=User::findorFail($id);
            $user->tokens()->delete();
            $post=posts::where('user_id','=',$user->id);
            if($post!=null)
            {
                $post->delete();
            }
            $comentario=comentarios::where('user_id','=',$user->id);
            if($comentario!=null)
            {
                $comentario->delete();
            }
            $user->delete();
            return response()->json(["El usuario $user->name ha sido eliminado del sistema","token"=>$user->tokens()->delete()],200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    public function editarDatos(Request $request,int $id)
    {
        if($request->user()->tokenCan('admi:permiso'))
        {
            $user=User::findorFail($id);
            $user->name = $request->has('name') ? $request->get('name') : $user->name;
            $user->email = $request->has('email') ? $request->get('email') : $user->email;
            $user->edad = $request->has('edad') ? $request->get('edad') : $user->edad;
            $user->rol = $request->has('rol') ? $request->get('rol') : $comentario->rol;
            $user->save();
            return response()->json($user,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
}
