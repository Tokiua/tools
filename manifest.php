<?php
header('Content-Type: application/json');
// El color del tema (barra superior) sigue siendo dinámico
$color = isset($_GET['color']) ? $_GET['color'] : '#7c3aed';

echo json_encode([
  "name" => "Nexosyne Tools",
  "short_name" => "Nexosyne",
  "description" => "Suite de herramientas por Nexosyne",
  "start_url" => "index.php", 
  "scope" => "/",
  "display" => "standalone",
  // FORZAMOS FONDO BLANCO PARA EL SPLASH NATIVO
  "background_color" => "#ffffff", 
  "theme_color" => $color,
  "orientation" => "portrait",
  "icons" => [
    [
      "src" => "assets/img/192.png",
      "sizes" => "192x192",
      "type" => "image/png",
      "purpose" => "any maskable"
    ],
    [
      "src" => "assets/img/512.png",
      "sizes" => "512x512",
      "type" => "image/png",
      "purpose" => "any"
    ]
  ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);