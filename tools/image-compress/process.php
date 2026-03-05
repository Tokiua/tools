<?php
/**
 * Nexosyne Image Engine - Zero Storage
 * Forzamos la reducción de peso limpiando binarios y ajustando calidad.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_FILES['images']['name'][0])) {
    
    $mode = $_POST['mode'] ?? 'lossless';
    $files = $_FILES['images'];
    $totalFiles = min(count($files['name']), 3); // Límite de 3
    
    $multiple = $totalFiles > 1;

    if ($multiple) {
        $zip = new ZipArchive();
        $zipName = 'Nexosyne_Opt_' . time() . '.zip';
        $zipPath = sys_get_temp_dir() . '/' . $zipName;
        $zip->open($zipPath, ZipArchive::CREATE);
    }

    for ($i = 0; $i < $totalFiles; $i++) {
        $tmpName = $files['tmp_name'][$i];
        if (empty($tmpName)) continue;

        $originalName = $files['name'][$i];
        $originalSize = filesize($tmpName);
        $info = getimagesize($tmpName);
        $mime = $info['mime'];

        // Cargar imagen
        switch ($mime) {
            case 'image/jpeg': $img = imagecreatefromjpeg($tmpName); break;
            case 'image/png':  $img = imagecreatefrompng($tmpName); break;
            case 'image/webp': $img = imagecreatefromwebp($tmpName); break;
            default: continue 2;
        }

        // Calidad base para reducir peso
        // Lossless: 80% (mantiene nitidez pero limpia metadatos)
        // Lossy: 50% (compresión fuerte para web)
        $quality = ($mode === 'lossy') ? 50 : 80;

        ob_start();
        if ($mime === 'image/jpeg') {
            imagejpeg($img, NULL, $quality);
        } elseif ($mime === 'image/png') {
            imagealphablending($img, false);
            imagesavealpha($img, true);
            // PNG usa escala 0-9. 9 es máxima compresión de archivo.
            imagepng($img, NULL, ($mode === 'lossy' ? 9 : 7));
        } elseif ($mime === 'image/webp') {
            imagewebp($img, NULL, $quality);
        }
        $data = ob_get_clean();
        imagedestroy($img);

        if ($multiple) {
            $zip->addFromString('nexosyne_' . $originalName, $data);
        } else {
            // Entrega directa para una sola imagen
            header("Content-Type: $mime");
            header("Content-Disposition: attachment; filename=\"nexosyne_$originalName\"");
            header("Content-Length: " . strlen($data));
            echo $data;
            exit;
        }
    }

    if ($multiple) {
        $zip->close();
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="Nexosyne_Batch_Optimized.zip"');
        header('Content-Length: ' . filesize($zipPath));
        readfile($zipPath);
        unlink($zipPath); // Borramos del temporal
        exit;
    }
}