<?php
set_time_limit(180);
ini_set('memory_limit', '512M');

if (isset($_GET['check'])) {
    header('Content-Type: application/json');
    $apiUrl = "https://www.tikwm.com/api/?url=" . urlencode($_GET['check']);
    $ch = curl_init($apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    echo curl_exec($ch);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['url'])) {
    $apiUrl = "https://www.tikwm.com/api/?url=" . urlencode($_POST['url']);
    $data = json_decode(file_get_contents($apiUrl), true);

    if ($data['code'] === 0) {
        $item = $data['data'];
        $format = $_POST['format'];
        $fileName = 'Nexosyne_' . time();

        if ($format === 'images-zip' && isset($item['images'])) {
            $zip = new ZipArchive();
            $zipFile = tempnam(sys_get_temp_dir(), 'zip');
            if ($zip->open($zipFile, ZipArchive::CREATE)) {
                foreach ($item['images'] as $i => $img) {
                    $zip->addFromString("foto_" . ($i + 1) . ".jpg", file_get_contents($img));
                }
                $zip->close();
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="'.$fileName.'.zip"');
                readfile($zipFile);
                unlink($zipFile);
                exit;
            }
        }

        $finalUrl = ($format === 'no-wm') ? $item['play'] : (($format === 'wm') ? $item['wmplay'] : $item['music']);
        $ext = ($format === 'audio') ? '.mp3' : '.mp4';
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.$fileName.$ext.'"');
        $ch = curl_init($finalUrl);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        curl_close($ch);
    }
}