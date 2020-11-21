<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function verUltimosJuegos(Request $request)
    {
        //id ejemplo 76561198402886692, 76561198396037238
        
        $id=$request->id;
        $url='http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=960BB5B9C58DD7518FC7A0161C5ADD02&steamid='.$id.'&format=json';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verPerfil(Request $request)
    {
        $id=$request->id;
        $url='http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=960BB5B9C58DD7518FC7A0161C5ADD02&steamids='.$id.'';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verListaAmigos(Request $request)
    {
        $id=$request->id;
        $url='http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=960BB5B9C58DD7518FC7A0161C5ADD02&steamid='.$id.'&relationship=friend';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
}
