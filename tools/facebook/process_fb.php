<?php
header('Content-Type: application/json');
error_reporting(0); // Evita que basura de errores rompa el JSON

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['url'])) {
    $targetUrl = trim($_POST['url']);
    
    // Intentaremos con este motor que es el más estable actualmente
    $apiUrl = "https://api.download.savetube.me/info?url=" . urlencode($targetUrl);

    $response = null;

    // MÉTODO 1: cURL (El estándar)
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Ignora errores de certificado en XAMPP
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/122.0.0.0');
    $response = curl_exec($ch);
    curl_close($ch);

    // MÉTODO 2: Plan B si cURL falló (file_get_contents)
    if (!$response) {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n"
            ],
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];
        $context = stream_context_create($opts);
        $response = file_get_contents($apiUrl, false, $context);
    }

    if ($response) {
        $data = json_decode($response, true);
        $source = isset($data['data']) ? $data['data'] : $data;

        if (isset($source['url']) || isset($source['medias'])) {
            $links = [];
            $thumbnail = $source['thumbnail'] ?? $source['thumb'] ?? '';

            if (isset($source['medias']) && is_array($source['medias'])) {
                foreach ($source['medias'] as $m) {
                    if ($m['extension'] === 'mp4') {
                        $links[] = [
                            'url' => $m['url'],
                            'quality' => (strtoupper($m['quality']) ?: 'HD'),
                            'filename' => 'Nexosyne_FB.mp4'
                        ];
                    }
                }
            }

            // Si no hay lista pero hay URL principal
            if (empty($links) && !empty($source['url'])) {
                $links[] = ['url' => $source['url'], 'quality' => 'HD VIDEO', 'filename' => 'Nexosyne_FB.mp4'];
            }

            if (!empty($links)) {
                echo json_encode(['success' => true, 'thumbnail' => $thumbnail, 'links' => $links]);
                exit;
            }
        }
    }

    echo json_encode([
        'success' => false, 
        'error' => 'No se pudo conectar con el motor. Intenta con un link de video PÚBLICO o verifica que el video no sea de un grupo privado.'
    ]);

} else {
    echo json_encode(['success' => false, 'error' => 'URL vacía']);
}