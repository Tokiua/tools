<?php
header('Content-Type: application/json');

$color = isset($_GET['color']) ? $_GET['color'] : '#7c3aed';

echo json_encode([
  "name" => "Nexosyne Tools",
  "short_name" => "Nexosyne",
  "description" => "Suite completa de herramientas Nexosyne",
  "start_url" => "/", // Esto asegura que siempre inicie en el home del subdominio
  "scope" => "/",     // Esto permite que la PWA controle todo el sitio completo
  "display" => "standalone",
  "background_color" => $color,
  "theme_color" => $color,
  "orientation" => "portrait",
  "icons" => [
    [
      "src" => "/assets/img/192.png", // Usar rutas absolutas desde la raíz
      "sizes" => "192x192",
      "type" => "image/png",
      "purpose" => "any maskable"
    ],
    [
      "src" => "/assets/img/512.png",
      "sizes" => "512x512",
      "type" => "image/png",
      "purpose" => "any"
    ]
  ]
], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);