<?php
/**
 * Image Converter Pro - Nexosyne Zero-Storage
 * Flujo: Cliente -> RAM -> Cliente (Sin dejar rastro en disco)
 */

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $targetFormat = strtolower($_POST['format'] ?? 'webp');
    $tmpPath = $file['tmp_name'];

    if (!file_exists($tmpPath)) {
        echo json_encode(['success' => false, 'error' => 'Archivo no detectado.']);
        exit;
    }

    // 1. Cargar imagen a la memoria RAM desde el temporal nativo de PHP
    $mime = mime_content_type($tmpPath);
    switch ($mime) {
        case 'image/jpeg': $img = imagecreatefromjpeg($tmpPath); break;
        case 'image/png':  $img = imagecreatefrompng($tmpPath); break;
        case 'image/webp': $img = imagecreatefromwebp($tmpPath); break;
        case 'image/bmp':  $img = imagecreatefrombmp($tmpPath); break;
        case 'image/gif':  $img = imagecreatefromgif($tmpPath); break;
        default:
            echo json_encode(['success' => false, 'error' => 'Formato no soportado.']);
            exit;
    }

    // 2. Iniciar Buffer de salida (Captura la imagen en RAM)
    ob_start(); 
    $contentType = '';

    switch ($targetFormat) {
        case 'webp':
            imagepalettetotruecolor($img);
            imagealphablending($img, true);
            imagesavealpha($img, true);
            imagewebp($img, null, 80);
            $contentType = 'image/webp';
            break;
        case 'jpg':
        case 'jpeg':
            $bg = imagecreatetruecolor(imagesx($img), imagesy($img));
            imagefill($bg, 0, 0, imagecolorallocate($bg, 255, 255, 255));
            imagecopy($bg, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));
            imagejpeg($bg, null, 85);
            imagedestroy($bg);
            $contentType = 'image/jpeg';
            break;
        case 'png':
            imagepalettetotruecolor($img);
            imagesavealpha($img, true);
            imagepng($img, null);
            $contentType = 'image/png';
            break;
        case 'ico':
            $tmpIco = imagecreatetruecolor(32, 32);
            imagealphablending($tmpIco, false);
            imagesavealpha($tmpIco, true);
            imagecopyresampled($tmpIco, $img, 0, 0, 0, 0, 32, 32, imagesx($img), imagesy($img));
            imagepng($tmpIco, null);
            imagedestroy($tmpIco);
            $contentType = 'image/x-icon';
            break;
        case 'bmp':
            imagebmp($img, null);
            $contentType = 'image/bmp';
            break;
    }

    $rawImage = ob_get_clean(); // Obtenemos los bits de la RAM
    imagedestroy($img); // Destruimos el recurso en el servidor inmediatamente

    // 3. Enviamos el resultado como Data URI (Base64)
    echo json_encode([
        'success' => true,
        'name' => pathinfo($file['name'], PATHINFO_FILENAME) . "_converted." . $targetFormat,
        'url' => 'data:' . $contentType . ';base64,' . base64_encode($rawImage)
    ]);
}