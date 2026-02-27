<?php
// download.php
if (!empty($_GET['url'])) {
    $url = filter_var($_GET['url'], FILTER_VALIDATE_URL);
    $name = !empty($_GET['name']) ? $_GET['name'] : 'video.mp4';

    if ($url) {
        header('Content-Description: File Transfer');
        header('Content-Type: video/mp4');
        header('Content-Disposition: attachment; filename="' . basename($name) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        // Esto lee el archivo de FB y lo envía al usuario por pedazos
        readfile($url);
        exit;
    }
}
echo "Error al procesar la descarga.";