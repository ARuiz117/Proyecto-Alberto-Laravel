<?php

namespace App\Http\Controllers;

use App\Services\SteamService;
use Illuminate\Http\Request;

class TrailerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Obtener trailer de un juego desde Steam
     */
    public function obtenerTrailer(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        $resultado = SteamService::getGameInfo($request->titulo);

        return response()->json($resultado);
    }

    /**
     * Obtener screenshots de un juego desde Steam
     */
    public function obtenerScreenshots(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
        ]);

        $resultado = SteamService::getGameScreenshots($request->titulo);

        return response()->json($resultado);
    }
}
