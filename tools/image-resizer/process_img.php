<?php
/**
 * Nexosyne Image Engine - Direct Download
 */

// Limpiar cualquier salida previa para evitar archivos corruptos
ob_start();
ini_set('display_errors', 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    
    $file = $_FILES['image'];
    $w = intval($_POST['width']);
    $h = intval($_POST['height']);
    $format = $_POST['format'];

    if ($file['error'] !== UPLOAD_ERR_OK) exit;

    $info = getimagesize($file['tmp_name']);
    $mime = $info['mime'];

    // Cargar imagen
    switch ($mime) {
        case 'image/jpeg': $src = imagecreatefromjpeg($file['tmp_name']); break;
        case 'image/png':  $src = imagecreatefrompng($file['tmp_name']);  break;
        case 'image/webp': $src = imagecreatefromwebp($file['tmp_name']); break;
        default: exit;
    }

    // Crear lienzo exacto
    $dst = imagecreatetruecolor($w, $h);

    // Transparencia / Fondo
    if ($format === 'png' || $format === 'webp') {
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
        imagefilledrectangle($dst, 0, 0, $w, $h, $transparent);
    } else {
        $white = imagecolorallocate($dst, 255, 255, 255);
        imagefilledrectangle($dst, 0, 0, $w, $h, $white);
    }

    // Redimensionar forzado
    imagecopyresampled($dst, $src, 0, 0, 0, 0, $w, $h, $info[0], $info[1]);

    // Limpiar buffer y enviar cabeceras de descarga
    ob_end_clean();
    $ext = ($format === 'webp') ? 'webp' : (($format === 'png') ? 'png' : 'jpg');
    
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="nexosyne_'.time().'.'.$ext.'"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');

    // Generar archivo directamente al flujo de salida
    if ($format === 'webp') {
        if (function_exists('imagewebp')) imagewebp($dst, null, 80);
        else imagejpeg($dst, null, 90);
    } elseif ($format === 'png') {
        imagepng($dst);
    } else {
        imagejpeg($dst, null, 90);
    }

    imagedestroy($src);
    imagedestroy($dst);
    exit;
}