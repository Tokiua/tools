<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $file = $_FILES['image'];
    $targetFormat = $_POST['format'] ?? 'webp';
    
    // Validar tipo de archivo
    $mime = mime_content_type($file['tmp_name']);
    $allowedMimes = ['image/jpeg', 'image/png', 'image/webp'];
    
    if (!in_array($mime, $allowedMimes)) {
        echo json_encode(['success' => false, 'error' => 'Formato no soportado.']);
        exit;
    }

    // Crear recurso de imagen según el origen
    switch ($mime) {
        case 'image/jpeg': $img = imagecreatefromjpeg($file['tmp_name']); break;
        case 'image/png':  $img = imagecreatefrompng($file['tmp_name']); break;
        case 'image/webp': $img = imagecreatefromwebp($file['tmp_name']); break;
    }

    // Preparar el nombre del archivo de salida
    $newName = pathinfo($file['name'], PATHINFO_FILENAME) . "_nexosyne." . $targetFormat;
    $outputDir = 'temp_converted/'; // Asegúrate de crear esta carpeta con permisos de escritura
    
    if (!is_dir($outputDir)) mkdir($outputDir, 0777, true);
    
    $outputPath = $outputDir . uniqid() . "_" . $newName;

    // Convertir y Guardar
    switch ($targetFormat) {
        case 'webp': imagewebp($img, $outputPath, 80); break;
        case 'jpg':  imagejpeg($img, $outputPath, 85); break;
        case 'png':  imagepng($img, $outputPath); break;
    }

    imagedestroy($img);

    echo json_encode([
        'success' => true,
        'url' => $outputPath,
        'name' => $newName
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'No se recibió ninguna imagen.']);
}