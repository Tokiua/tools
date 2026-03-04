<?php
header('Content-Type: application/json');

$color = isset($_GET['color']) ? $_GET['color'] : '#7c3aed';

echo json_encode([
  "name" => "Nexosyne Tools",
  "short_name" => "Nexosyne",
  "start_url" => "/herramienta/index.php",
  "scope" => "/herramienta/",
  "display" => "standalone",
  "background_color" => $color,
  "theme_color" => $color,
  "icons" => [
    [
      "src" => "/herramienta/assets/img/192.png",
      "sizes" => "192x192",
      "type" => "image/png"
    ],
    [
      "src" => "/herramienta/assets/img/512.png",
      "sizes" => "512x512",
      "type" => "image/png"
    ]
  ]
], JSON_UNESCAPED_SLASHES);