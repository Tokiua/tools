<?php
/**
 * Nexosyne Tools - TikTok Processor
 * Política: Cero almacenamiento en servidor. 
 * Todo se procesa en memoria y se entrega mediante streaming.
 */

ini_set('memory_limit', '512M'); // Suficiente para procesar imágenes en memoria
set_time_limit(0);

// --- 1. LÓGICA DE ANÁLISIS (AJAX / GET) ---
if (isset($_GET['check'])) {
    $url = $_GET['check'];
    
    // API de TikWM (Excelente para obtener datos de autor, video y fotos)
    $apiUrl = "https://www.tikwm.com/api/?url=" . urlencode($url);
    
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $raw = json_decode($response, true);
    $d = $raw['data'] ?? null;

    header('Content-Type: application/json');

    if ($d) {
        // Detectar si es video o carrusel de imágenes
        $is_video = !isset($d['images']) || empty($d['images']);
        
        echo json_encode([
            'id'            => $d['id'],
            'title'         => $d['title'],
            'cover'         => $d['cover'],
            'author_name'   => $d['author']['unique_id'] ?? 'TikTokUser',
            'author_avatar' => $d['author']['avatar'] ?? '',
            'is_video'      => $is_video,
            'video'         => $is_video ? $d['play'] : null,
            'images'        => !$is_video ? $d['images'] : null,
            'music'         => $d['music']
        ]);
    } else {
        echo json_encode(['error' => 'No se pudo obtener la información.']);
    }
    exit;
}

// --- 2. DESCARGA DE VIDEO/AUDIO (STREAMING DIRECTO) ---
if (isset($_POST['action']) && $_POST['action'] === 'download_stream') {
    $fileUrl = $_POST['url'];
    $name = preg_replace('/[^A-Za-z0-9_\-\.]/', '_', $_POST['name']); // Limpiar nombre de archivo

    // Configurar cabeceras para forzar descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $name . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    
    // El servidor actúa como un puente (Bridge)
    // No guarda el archivo, lo lee del origen y lo envía al usuario al mismo tiempo
    $handle = fopen($fileUrl, "rb");
    if ($handle) {
        while (!feof($handle)) {
            echo fread($handle, 8192);
            flush();
        }
        fclose($handle);
    }
    exit;
}

// --- 3. DESCARGA DE IMÁGENES (ZIP VOLÁTIL) ---
if (isset($_POST['action']) && $_POST['action'] === 'download_images_zip') {
    $images = json_decode($_POST['images'], true);
    $id = $_POST['id'];
    $zipName = "nexosyne_fotos_" . $id . ".zip";

    // Creamos un archivo temporal en el sistema para el ZIP
    $tempFile = tempnam(sys_get_temp_dir(), 'nex_zip');

    $zip = new ZipArchive();
    if ($zip->open($tempFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        foreach ($images as $index => $imgUrl) {
            // Descargar cada imagen a la memoria e insertarla en el ZIP
            $imgContent = file_get_contents($imgUrl);
            if ($imgContent) {
                $zip->addFromString("nexosyne_foto_" . ($index + 1) . ".jpg", $imgContent);
            }
        }
        $zip->close();

        // Entregar el ZIP y luego destruirlo
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        header('Content-Length: ' . filesize($tempFile));
        header('Pragma: no-cache');
        header('Expires: 0');
        
        readfile($tempFile);
        
        // ELIMINACIÓN: Borra el rastro del servidor inmediatamente
        unlink($tempFile);
    } else {
        die("Error creando el paquete de imágenes.");
    }
    exit;
}