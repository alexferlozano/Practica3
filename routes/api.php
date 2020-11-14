<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get("post","PostsController@index");
Route::middleware('auth:sanctum')->get("post/{id}","PostsController@buscar")->where("id","[0-9]+");
Route::middleware('auth:sanctum')->post("post","PostsController@store");
Route::middleware('auth:sanctum')->delete("post/{id}","PostsController@destroy")->where("id","[0-9]+");
Route::middleware('auth:sanctum')->put("post/{id}","PostsController@update")->where("id","[0-9]+");

Route::middleware('auth:sanctum')->get("comentarios","ComentariosController@index");
Route::middleware('auth:sanctum')->get("comentarios/{id}","ComentariosController@index")->where("id","[0-9]+");
Route::middleware('auth:sanctum')->get("post/{id}/comentarios","ComentariosController@show")->where("id","[0-9]+");
Route::middleware('auth:sanctum')->post("post/{id}/comentarios","ComentariosController@store")->where("id","[0-9]+");
Route::middleware('auth:sanctum')->delete("post/{id}/comentarios/{id2}","ComentariosController@destroy")->where(["id"=>"[0-9]+","id2"=>"[0-9]+"]);
Route::middleware('auth:sanctum')->put("post/{id}/comentarios/{id2}","ComentariosController@update")->where(["id"=>"[0-9]+","id2"=>"[0-9]+"]);

Route::middleware('auth:sanctum')->delete('/logout','AuthController@logout');
Route::middleware('auth:sanctum')->get('/usuarios','AuthController@usuarios');
Route::middleware('auth:sanctum')->get('/usuarios/perfil','UserController@verPerfil');
Route::middleware('auth:sanctum')->put('/usuarios/perfil/editar','UserController@editarDatos')->middleware('edad', 'privilegio');
Route::middleware('auth:sanctum')->put('/usuarios/editar/{id}','AuthController@editarDatos')->where("id","[0-9]+")->middleware('edad', 'rol');
Route::middleware('auth:sanctum')->delete('/usuarios/eliminar/{id}','AuthController@eliminarUsuario')->where("id","[0-9]+");

Route::post("registro","AuthController@registro")->middleware('edad', 'privilegio');
Route::post("login","AuthController@login");
