<?php
header('Content-Type: application/json');

// Color del tema (morado por defecto)
$themeColor = isset($_GET['color']) ? $_GET['color'] : '#7c3aed';

$manifest = [
    "name" => "Nexosyne Tools",
    "short_name" => "Nexosyne",
    "start_url" => "index.php",
    "display" => "standalone",
    "orientation" => "portrait",
    "background_color" => "#ffffff", // Fondo blanco para que coincida con tu splash de PHP
    "theme_color" => $themeColor,
    "scope" => "/",
    "icons" => [
        [
            "src" => "assets/img/192.png", 
            "sizes" => "192x192",
            "type" => "image/png",
            "purpose" => "any"
        ],
        [
            "src" => "assets/img/512.png",
            "sizes" => "512x512",
            "type" => "image/png",
            "purpose" => "maskable"
        ]
    ]
];

echo json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>