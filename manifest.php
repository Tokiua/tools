<?php
header('Content-Type: application/json');

$name = "Nexosyne Tools";
$color = isset($_GET['color']) ? $_GET['color'] : '#7c3aed';

echo json_encode([
    "name" => $name,
    "short_name" => "Nexosyne",
    "start_url" => "/index.php",
    "display" => "standalone",
    "background_color" => $color,
    "theme_color" => $color,
    "icons" => [
        [
            "src" => "/assets/img/192x192.png",
            "sizes" => "192x192",
            "type" => "image/png"
        ],
        [
            "src" => "/assets/img/512x512.png",
            "sizes" => "512x512",
            "type" => "image/png"
        ]
    ]
], JSON_UNESCAPED_SLASHES);