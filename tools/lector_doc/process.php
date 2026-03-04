<?php
/**
 * LUMINA STREAM - PDF BRIDGE
 * Procesa PDF y los entrega como flujo binario sin almacenamiento.
 */

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['documento'])) {
    $file = $_FILES['documento'];
    $tmpPath = $file['tmp_name'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    // Seguridad: Validar que sea PDF en el servidor
    if ($ext !== 'pdf') {
        http_response_code(400);
        echo "Error: Solo se admiten archivos PDF.";
        exit;
    }

    // Entregamos el contenido directamente desde el buffer temporal de PHP
    header('Content-Type: application/pdf');
    header('Content-Disposition: inline; filename="lumina_render.pdf"');
    
    // Leemos y enviamos el archivo directamente a la salida
    readfile($tmpPath);
    
    // PHP borrará el archivo en $tmpPath automáticamente al terminar la ejecución
    exit;
}