<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function verEvolucionPokemon(int $id)
    {
        $url='https://pokeapi.co/api/v2/evolution-chain/'.$id.'/';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verHabilidad(int $id)
    {
        $url='https://pokeapi.co/api/v2/ability/'.$id.'/';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verPokemonID(int $id)
    {
        $url='https://pokeapi.co/api/v2/pokemon/'.$id.'/';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verPokemonNOMBRE(string $nombre)
    {
        $url='https://pokeapi.co/api/v2/pokemon/'.$nombre.'/';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
}
