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
            // Lista de App IDs conocidos para juegos populares (solo los de la BD)
            $knownAppIds = [
                'witcher' => 292030,
                'the witcher' => 292030,
                'the witcher 3' => 292030,
                'gta' => 271590,
                'grand theft auto' => 271590,
                'gta v' => 271590,
                'red dead' => 1174180,
                'red dead redemption 2' => 1174180,
                'cyberpunk' => 633630,
                'cyberpunk 2077' => 633630,
                'elden ring' => 1245620,
                'hollow knight' => 367520,
                'celeste' => 504230,
                'stardew valley' => 413150,
                'undertale' => 391540,
                'cuphead' => 267930,
            ];
            
            $gameNameLower = strtolower($gameName);
            $appId = null;
            
            // Buscar App ID conocido
            foreach ($knownAppIds as $gameKey => $id) {
                if (strpos($gameKey, $gameNameLower) !== false || strpos($gameNameLower, $gameKey) !== false) {
                    $appId = $id;
                    break;
                }
            }
            
            // Si no hay App ID conocido, intentar con la API de búsqueda
            if (!$appId) {
                // Añadir retraso para evitar bloqueo
                usleep(700000); // 0.7 segundos (aumentado)
                
                $searchUrl = 'https://store.steampowered.com/api/storesearch/?term=' . urlencode($gameName) . '&l=spanish&cc=ES';
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'application/json, text/plain, */*',
                        'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1'
                    ])
                    ->get($searchUrl);
                
                if ($response->successful() && isset($response['items']) && count($response['items']) > 0) {
                    $appId = $response['items'][0]['id'];
                }
            }
            
            // Obtener detalles del juego si tenemos App ID
            if ($appId) {
                // Añadir retraso para evitar bloqueo
                usleep(500000); // 0.5 segundos (aumentado)
                
                $detailsUrl = 'https://store.steampowered.com/api/appdetails?appids=' . $appId . '&l=spanish&cc=ES';
                $detailsResponse = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
                        'Accept' => 'application/json, text/plain, */*',
                        'Accept-Language' => 'es-ES,es;q=0.9,en;q=0.8',
                        'Accept-Encoding' => 'gzip, deflate, br',
                        'Connection' => 'keep-alive',
                        'Upgrade-Insecure-Requests' => '1'
                    ])
                    ->get($detailsUrl);
                
                if ($detailsResponse->successful()) {
                    $gameData = $detailsResponse[$appId]['data'] ?? null;
                    
                    if ($gameData) {
                        // Buscar trailer en movies
                        if (isset($gameData['movies']) && count($gameData['movies']) > 0) {
                            // Steam ya no usa MP4/WebM directos, usa HLS/DASH streaming
                            // Pero podemos intentar extraer URLs directas de los streams HLS
                            foreach ($gameData['movies'] as $movie) {
                                if (isset($movie['hls_h264'])) {
                                    $hlsUrl = $movie['hls_h264'];
                                    
                                    // Obtener el archivo .m3u8
                                    $m3u8Content = @file_get_contents($hlsUrl);
                                    if ($m3u8Content) {
                                        // Buscar la resolución más alta disponible
                                        preg_match('/RESOLUTION=(\d+x\d+)/', $m3u8Content, $resolutionMatches);
                                        preg_match('/(https:\/\/[^\s]+\.m3u8)/', $m3u8Content, $playlistMatches);
                                        
                                        if (isset($playlistMatches[1])) {
                                            // Devolver la URL del playlist HLS (funciona en muchos navegadores modernos)
                                            return [
                                                'success' => true,
                                                'trailer_url' => $playlistMatches[1],
                                                'app_id' => $appId,
                                            ];
                                        }
                                        
                                        // Si no hay playlist secundario, devolver el HLS principal
                                        return [
                                            'success' => true,
                                            'trailer_url' => $hlsUrl,
                                            'app_id' => $appId,
                                        ];
                                    }
                                }
                            }
                            
                            // Si no se puede procesar HLS, devolver error
                            return [
                                'success' => false,
                                'error' => 'No se pudo procesar el trailer de Steam',
                                'app_id' => $appId,
                            ];
                        }
                        
                        // Si no hay trailer, devolver éxito sin URL
                        return [
                            'success' => false,
                            'error' => 'No se encontró trailer para este juego',
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
