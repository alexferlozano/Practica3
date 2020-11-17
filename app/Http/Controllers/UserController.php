<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

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
}
