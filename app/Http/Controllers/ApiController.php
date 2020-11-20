<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function verUltimosJuegos()
    {
        $url='http://api.steampowered.com/IPlayerService/GetRecentlyPlayedGames/v0001/?key=960BB5B9C58DD7518FC7A0161C5ADD02&steamid=76561198396037238&format=json';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verPerfil()
    {
        $url='http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=960BB5B9C58DD7518FC7A0161C5ADD02&steamids=76561198396037238';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
    public function verListaAmigos()
    {
        $url='http://api.steampowered.com/ISteamUser/GetFriendList/v0001/?key=960BB5B9C58DD7518FC7A0161C5ADD02&steamid=76561198396037238&relationship=friend';
        $response=Http::timeout(10)->get($url)->json();
        return $response;
    }
}
