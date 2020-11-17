<?php

namespace App\Http\Controllers;

use App\User;
use App\posts;
use App\comentarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class ComentariosController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->tokenCan('user:coment'))
        {
            return comentarios::all();
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,int $id)
    {
        if($request->user()->tokenCan('user:coment'))
        {
            $post=posts::findorFail($id);
            $comentario=comentarios::create([
                'post_id'=>$post->id,
                'user_id'=>$request->user()->id,
                'descripcion'=>$request->descripcion
            ]);
            $u1=User::findorFail($post->user_id);
            $u2=User::findorFail($comentario->user_id);
            $data1=array(
                'email'=>$u1->email,
                'name'=>$u1->name,
                'titulo'=>$post->titulo,
                'descripcion'=>$comentario->descripcion,
                'user'=>$u2->name
               );
            $data2=array(
                'email'=>$u2->email,
                'name'=>$u2->name,
                'titulo'=>$post->titulo
               );
                Mail::send('emails.autorcomentario', $data2, function($message) use ($data2){
                    $message->from('19170025@uttcampus.edu.mx','Alex Lozano');
                    $message->to($data2['email'], $data2['name'])->subject('Has comentado un post');
                });
                Mail::send('emails.autorpost', $data1, function($message) use ($data1){
                    $message->from('19170025@uttcampus.edu.mx','Alex Lozano');
                    $message->to($data1['email'], $data1['name'])->subject('Han comentado en tu post');
                });
            return response()->json($comentario,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\comentarios  $comentario
     * @return \Illuminate\Http\Response
     */
    public function show(int $id, Request $request)
    {
        if($request->user()->tokenCan('user:coment'))
        {
            $post=posts::findorFail($id);
            $comentario=DB::table('comentarios')->select('id','post_id','descripcion')->where('post_id','=',$post->id)->get();
            return response()->json($comentario,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\comentarios  $comentario
     * @return \Illuminate\Http\Response
     */
    public function edit(comentario $comentario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\comentarios  $comentario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id,int $id2)
    {
        $post=posts::findorFail($id);
        if($request->user()->tokenCan('user:coment') && comentarios::findorFail($id2)->user_id==$request->user()->id)
        {
            $comentario=comentarios::findorFail($id2);
            $comentario->post_id = $request->has('post_id') ? $request->get('post_id') : $comentario->post_id;
            $comentario->descripcion = $request->has('descripcion') ? $request->get('descripcion') : $comentario->descripcion;
            $comentario->save();
            return response()->json($comentario,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\comentarios  $comentario
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,int $id2,Request $request)
    {
        if($request->user()->tokenCan('user:coment') && comentarios::findorFail($id2)->user_id==$request->user()->id)
        {
            $post=posts::findorFail($id);
            $comentario=comentarios::findorFail($id2);
            $comentario->delete();
            return response()->json("El comentario $comentario->id ha sido eliminado",200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
    public function buscar(int $id,Request $request)
    {
        if($request->user()->tokenCan('user:coment'))
        {
            $comentario = comentarios::findorFail($id);
            return response()->json($comentario,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
}
