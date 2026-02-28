<?php
/**
 * Nexosyne Image Engine - Zero Storage
 */

// Desactivar errores visibles para no romper el binario de la imagen
ini_set('display_errors', 0);
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    
    $file = $_FILES['image'];
    $w = intval($_POST['width']);
    $h = intval($_POST['height']);
    $format = $_POST['format'];

    if ($file['error'] !== UPLOAD_ERR_OK) exit;

    $info = getimagesize($file['tmp_name']);
    if (!$info) exit;
    $mime = $info['mime'];

    // 1. Cargar imagen a memoria RAM
    switch ($mime) {
        case 'image/jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
        case 'image/png':  $src = imagecreatefrompng($file['tmp_name']);  break;
        case 'image/webp': $src = imagecreatefromwebp($file['tmp_name']); break;
        default: exit;
    }

    // 2. Crear lienzo de destino
    $dst = imagecreatetruecolor($w, $h);

    // Manejo de transparencia
    if ($format === 'png' || $format === 'webp') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
    } else {
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $w, $h, $white);
    }

    // 3. Redimensionar (Proceso en RAM)
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $info[0], $info[1]);

    // 4. Limpiar cualquier buffer previo
    if (ob_get_length()) ob_clean();

    // 5. Cabeceras de descarga directa
    $ext = ($format === 'webp') ? 'webp' : (($format === 'png') ? 'png' : 'jpg');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="nexosyne_'.time().'.'.$ext.'"');
    header('Cache-Control: no-cache');

    // 6. Generar salida directamente al navegador
    if ($format === 'webp') {
        imagewebp($dst, null, 85);
    } elseif ($format === 'png') {
        imagepng($dst);
    } else {
        imagejpeg($dst, null, 85);
    }

    // 7. Liberar memoria RAM inmediatamente
    imagedestroy($src);
    imagedestroy($dst);
    exit;
}