<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class SteamService
{
    /**
     * Obtener información del juego desde Steam API
     */
    public static function getGameInfo($gameName)
    {
        try {
            // Buscar el juego por nombre en Steam
            $searchUrl = 'https://store.steampowered.com/api/storesearch/?term=' . urlencode($gameName) . '&l=spanish&cc=ES';
            
            $response = Http::timeout(10)->get($searchUrl);
            
            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                $appId = $response['items'][0]['id'];
                
                // Obtener detalles del juego incluyendo trailer
                $detailsUrl = 'https://store.steampowered.com/api/appdetails?appids=' . $appId . '&l=spanish&cc=ES';
                $detailsResponse = Http::timeout(10)->get($detailsUrl);
                
                if ($detailsResponse->successful()) {
                    $gameData = $detailsResponse[$appId]['data'] ?? null;
                    
                    if ($gameData && isset($gameData['movies']) && count($gameData['movies']) > 0) {
                        // Obtener el primer trailer
                        $trailer = $gameData['movies'][0];
                        
                        return [
                            'success' => true,
                            'trailer_url' => $trailer['webm']['max'] ?? $trailer['mp4']['max'] ?? null,
                            'app_id' => $appId,
                        ];
                    }
                }
            }
            
            return ['success' => false, 'error' => 'No se encontró el juego en Steam'];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Obtener imágenes del juego desde Steam API
     */
    public static function getGameScreenshots($gameName)
    {
        try {
            // Buscar el juego por nombre en Steam
            $searchUrl = 'https://store.steampowered.com/api/storesearch/?term=' . urlencode($gameName) . '&l=spanish&cc=ES';
            
            $response = Http::timeout(10)->get($searchUrl);
            
            if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                $appId = $response['items'][0]['id'];
                
                // Obtener detalles del juego incluyendo screenshots
                $detailsUrl = 'https://store.steampowered.com/api/appdetails?appids=' . $appId . '&l=spanish&cc=ES';
                $detailsResponse = Http::timeout(10)->get($detailsUrl);
                
                if ($detailsResponse->successful()) {
                    $gameData = $detailsResponse[$appId]['data'] ?? null;
                    
                    if ($gameData && isset($gameData['screenshots']) && count($gameData['screenshots']) > 0) {
                        $screenshots = [];
                        
                        // Obtener hasta 5 screenshots
                        foreach (array_slice($gameData['screenshots'], 0, 5) as $screenshot) {
                            $screenshots[] = [
                                'path_thumbnail' => $screenshot['path_thumbnail'] ?? null,
                                'path_full' => $screenshot['path_full'] ?? null,
                            ];
                        }
                        
                        return [
                            'success' => true,
                            'screenshots' => $screenshots,
                            'app_id' => $appId,
                        ];
                    }
                }
            }
            
            return ['success' => false, 'error' => 'No se encontraron imágenes', 'screenshots' => []];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage(), 'screenshots' => []];
        }
    }
}
