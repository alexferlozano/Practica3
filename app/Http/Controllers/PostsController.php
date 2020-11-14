<?php

namespace App\Http\Controllers;

use App\posts;
use Illuminate\Http\Request;
use App\comentario;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->user()->tokenCan('user:post'))
        {
            return posts::all(); 
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
    public function store(Request $request)
    {
        if($request->user()->tokenCan('user:post'))
        {
            $post = posts::create([
                'titulo'=>$request->titulo,
                'user_id'=>$request->user()->id,
                'descripcion'=>$request->descripcion,
                'autor'=>$request->user()->name,
            ]);
            return response()->json($post,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\posts  $post
     * @return \Illuminate\Http\Response
     */
    public function show(post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\posts  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\posts  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        if($request->user()->tokenCan('user:post') && posts::findorFail($id)->user_id==$request->user()->id)
        {
            $post=posts::findorFail($id);
            $post->titulo = $request->has('titulo') ? $request->get('titulo') : $post->titulo;
            $post->descripcion = $request->has('descripcion') ? $request->get('descripcion') : $post->descripcion;
            $post->autor = $request->has('autor') ? $request->get('autor') : $post->autor;
            $post->save();
            return response()->json($post,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\posts  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id,Request $request)
    {
        if($request->user()->tokenCan('admi:delete'))
        {
            $post = posts::findorFail($id);
            DB::table('comentarios')->where('post_id','=',$id)->delete();
            $post->delete();
            return response()->json("El post $post->titulo ha sido eliminado",200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
    public function buscar(int $id,Request $request)
    {
        if($request->user()->tokenCan('user:post'))
        {
            $post = posts::findorFail($id);
            return response()->json($post,200);
        }
        else
        {
            return abort(401,"Tienes 0 permiso de estar aqui");
        }
    }
}
