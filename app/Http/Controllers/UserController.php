<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function editarDatos(Request $request)
    {
        if($request->user()->tokenCan('user:edit'))
        {
            $pass=Hash::make($request->password);
            $id=$request->user()->id;
            $user=User::findorFail($id);
            $user->name = $request->has('name') ? $request->get('name') : $user->name;
            $user->email = $request->has('email') ? $request->get('email') : $user->email;
            $user->password = $request->has('password') ? $pass : $user->password;
            $user->edad = $request->has('edad') ? $request->get('edad') : $user->edad;
            $user->save();
            return response()->json($user,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
    public function verPerfil(Request $request)
    {
        if($request->user()->tokenCan('user:edit'))
        {
            $id=$request->user()->id;
            $user=User::findorFail($id);
            return response()->json($user,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    //1|jLnmjrn6NwYft7H3O55nRhaTUIy2xmzLvaDjZted
    //2|DpnQinITKe45aYM0d8Ylj9GgOpagJ2lA7CFNmCJj
    public function eliminarFoto(Request $request)
    {
        if($request->user()->tokenCan('user:edit'))
        {
            $id=$request->user()->id;
            $user=User::findorFail($id);
            Storage::disk('public')->delete($user->foto);
            $user->foto=null;
            $user->save();
            return response()->json($user,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    public function editarFoto(Request $request)
    {
        if($request->user()->tokenCan('user:edit'))
        {
            $id=$request->user()->id;
            $user=User::findorFail($id);
            if($request->hasFile('foto'))
            {
                Storage::disk('public')->delete($user->foto);
                $path=Storage::disk('public')->putFile('usuarios/',$request->foto);
            }
            else
            {
                $path=$user->foto;
            }
            $user->foto=$path;
            $user->save();
            return response()->json($user,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
}
