<?php
/**
 * Nexosyne Alpha Studio - Motor de Renderizado v2.1
 * Enfoque: Transparencia total garantizada.
 */

header('Content-Type: application/json');

// --- CONFIGURACIÓN NEXOSYNE ---
// Asegúrate de usar tus credenciales correctas
$cloudName = "dnuqkozgl";
$apiKey    = "524949811433816";
$apiSecret = "2FfIZpP3ImUymYgGpq0Gq9S69DE"; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    
    // Validar archivo básico
    $file = $_FILES['image'];
    if ($file['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'error' => 'Error al subir archivo binario.']);
        exit;
    }

    $timestamp = time();
    
    // --- PASO CLAVE: PARÁMETROS DE SUBIDA ---
    // 1. background_removal=cloudinary_ai : Activa la IA para remover fondo.
    // 2. format=png : Fuerza que el archivo guardado sea PNG, soportando transparencia.
    $params = "background_removal=cloudinary_ai&format=png&timestamp=" . $timestamp;
    
    // Generar firma de seguridad
    $signature = sha1($params . $apiSecret);

    // Preparar llamada cURL
    $ch = curl_init("https://api.cloudinary.com/v1_1/$cloudName/image/upload");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, [
        'file' => new CURLFile($file['tmp_name']),
        'api_key' => $apiKey,
        'timestamp' => $timestamp,
        'signature' => $signature,
        'background_removal' => 'cloudinary_ai',
        'format' => 'png' // Forzar formato de destino
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    // Ejecutar y decodificar respuesta
    $response = curl_exec($ch);
    $data = json_decode($response, true);
    curl_close($ch);

    // --- RESPUESTA AL FRONT-END ---
    if (isset($data['secure_url'])) {
        // La URL devuelta ya tendrá el fondo removido y será un PNG.
        echo json_encode([
            'success' => true,
            'token' => base64_encode($data['secure_url']) // Codificamos por seguridad
        ]);
    } else { 
        // Cloudinary devolvió un error (add-on no activo, créditos agotados, etc.)
        $errorMsg = $data['error']['message'] ?? 'Error desconocido en Nexosyne Cloud.';
        echo json_encode([
            'success' => false, 
            'error' => $errorMsg
        ]); 
    }
} else {
    // Solicitud no válida
    echo json_encode(['success' => false, 'error' => 'Inyección de asset inválida.']);
}