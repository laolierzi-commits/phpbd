<?php
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');
error_reporting(0);

// ==================== CONFIGURATION ====================
define('AUTH_PASSWORD_HASH', '$2y$10$YourBcryptHashHere'); // bcrypt hash - CHANGE THIS!
define('AUTH_USERNAME', 'admin'); // Username - CHANGE THIS!
// Base directory - Auto-detects web root based on server
// Auto-detection works for most servers: Apache, Nginx, IIS, etc.
// 
// Manual configuration examples (uncomment and modify if auto-detect fails):
// Linux Apache:    define('BASE_DIR', '/var/www/html');
// Linux Nginx:     define('BASE_DIR', '/usr/share/nginx/html');
// cPanel:          define('BASE_DIR', '/home/username/public_html');
// XAMPP (Win):     define('BASE_DIR', 'C:\\xampp\\htdocs');
// WAMP (Win):      define('BASE_DIR', 'C:\\wamp64\\www');
// AppServ (Win):   define('BASE_DIR', 'C:\\AppServ\\www');
// MAMP (Mac):      define('BASE_DIR', '/Applications/MAMP/htdocs');
// Laragon (Win):   define('BASE_DIR', 'C:\\laragon\\www');
define('BASE_DIR', $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__)); // Auto-detect web root
define('MAX_UPLOAD_SIZE', 10485760); // 10MB
// SECURITY: Removed PHP and executable extensions to prevent code execution
define('ALLOWED_EXTENSIONS', ['txt', 'html', 'css', 'js', 'json', 'xml', 'log', 'md', 'csv', 'ini', 'yaml', 'yml']);
// SECURITY: Blocked dangerous extensions - NEVER allow these
define('BLOCKED_EXTENSIONS', ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'phar', 'exe', 'sh', 'bat', 'cmd', 'com', 'scr', 'vbs', 'jar', 'war', 'dll', 'so', 'dylib', 'app']);
define('ALLOWED_IPS', []); // Empty = allow all, or ['127.0.0.1', '192.168.1.100']
define('MAX_LOGIN_ATTEMPTS', 5); // Max failed logins before lockout
define('LOCKOUT_TIME', 900); // 15 minutes lockout
define('LOG_FILE', __DIR__ . '/.fm_audit.log'); // Audit log location
define('SESSION_TIMEOUT', 1800); // 30 minutes
define('MAX_FILE_OPERATIONS_PER_MINUTE', 20); // Rate limiting
define('MAX_FILE_OPERATIONS_PER_IP_PER_MINUTE', 30); // Per-IP rate limiting
define('STRICT_MIME_CHECK', true); // ALWAYS enabled for security - DO NOT DISABLE

// ==================== SECURITY FUNCTIONS ====================
function getClientIP() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

function checkIPWhitelist() {
    if (empty(ALLOWED_IPS)) {
        return true;
    }
    return in_array(getClientIP(), ALLOWED_IPS);
}

function auditLog($action, $details = '') {
    $timestamp = date('Y-m-d H:i:s');
    $ip = getClientIP();
    $user = $_SESSION['username'] ?? 'anonymous';
    $logEntry = "[$timestamp] [$ip] [$user] $action";
    if ($details) {
        $logEntry .= " - $details";
    }
    @file_put_contents(LOG_FILE, $logEntry . PHP_EOL, FILE_APPEND | LOCK_EX);
}

function isLockedOut() {
    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = 0;
        $_SESSION['last_attempt'] = 0;
    }
    
    if ($_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS) {
        $timeSinceLastAttempt = time() - $_SESSION['last_attempt'];
        if ($timeSinceLastAttempt < LOCKOUT_TIME) {
            return true;
        } else {
            $_SESSION['login_attempts'] = 0;
        }
    }
    return false;
}

function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken() {
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $_POST['csrf_token']);
}

function checkSessionTimeout() {
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        return false;
    }
    $_SESSION['last_activity'] = time();
    return true;
}

function checkRateLimit() {
    if (!isset($_SESSION['file_operations'])) {
        $_SESSION['file_operations'] = [];
    }
    
    $now = time();
    $_SESSION['file_operations'] = array_filter($_SESSION['file_operations'], function($timestamp) use ($now) {
        return ($now - $timestamp) < 60;
    });
    
    if (count($_SESSION['file_operations']) >= MAX_FILE_OPERATIONS_PER_MINUTE) {
        return false;
    }
    
    $_SESSION['file_operations'][] = $now;
    return true;
}

function checkIPRateLimit() {
    $ip = getClientIP();
    $rateLimitFile = sys_get_temp_dir() . '/fm_ratelimit_' . md5($ip) . '.json';
    
    $data = [];
    if (file_exists($rateLimitFile)) {
        $content = @file_get_contents($rateLimitFile);
        if ($content) {
            $data = json_decode($content, true) ?: [];
        }
    }
    
    $now = time();
    $data = array_filter($data, function($timestamp) use ($now) {
        return ($now - $timestamp) < 60;
    });
    
    if (count($data) >= MAX_FILE_OPERATIONS_PER_IP_PER_MINUTE) {
        auditLog('RATE_LIMIT_IP', "IP rate limit exceeded: $ip");
        return false;
    }
    
    $data[] = $now;
    @file_put_contents($rateLimitFile, json_encode($data), LOCK_EX);
    return true;
}

function detectMaliciousPatterns($content, $filename) {
    // Detect common malicious patterns in file content
    $suspiciousPatterns = [
        '/eval\s*\(/i',
        '/base64_decode\s*\(/i',
        '/exec\s*\(/i',
        '/system\s*\(/i',
        '/passthru\s*\(/i',
        '/shell_exec\s*\(/i',
        '/`[^`]+`/',
        '/proc_open\s*\(/i',
        '/popen\s*\(/i',
        '/curl_exec\s*\(/i',
        '/curl_multi_exec\s*\(/i',
        '/parse_ini_file\s*\(/i',
        '/show_source\s*\(/i',
        '/<\?php/i',
        '/<script[^>]*>.*?<\/script>/is',
        '/javascript:/i',
        '/onclick\s*=/i',
        '/onerror\s*=/i',
        '/onload\s*=/i'
    ];
    
    foreach ($suspiciousPatterns as $pattern) {
        if (preg_match($pattern, $content)) {
            auditLog('MALWARE_DETECTED', "Suspicious pattern found in: $filename");
            return true;
        }
    }
    
    return false;
}

// Fix session path issue
$sessionPath = sys_get_temp_dir() . '/php_sessions';
if (!is_dir($sessionPath)) {
    @mkdir($sessionPath, 0700, true);
}
if (is_dir($sessionPath) && is_writable($sessionPath)) {
    ini_set('session.save_path', $sessionPath);
}

session_start();

// Check session timeout
if (isset($_SESSION['auth']) && $_SESSION['auth'] === true) {
    if (!checkSessionTimeout()) {
        auditLog('SESSION_TIMEOUT', 'Session expired');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

session_regenerate_id(true);

// Set secure session cookie parameters
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    ini_set('session.cookie_secure', '1');
}
ini_set('session.cookie_httponly', '1');
ini_set('session.cookie_samesite', 'Strict');

// Enhanced security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
header('X-Permitted-Cross-Domain-Policies: none');
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-ancestors 'none'; base-uri 'self'; form-action 'self';");

// ==================== AUTHENTICATION ====================
if (!checkIPWhitelist()) {
    auditLog('ACCESS_DENIED', 'IP not whitelisted: ' . getClientIP());
    http_response_code(403);
    die('Access denied: IP not authorized');
}

function isAuthenticated() {
    return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
}

function checkAuth() {
    if (!isAuthenticated()) {
        auditLog('UNAUTHORIZED_ACCESS', 'Attempted access without auth');
        header('HTTP/1.0 403 Forbidden');
        exit;
    }
}

if (isset($_POST['login_username'], $_POST['login_password'])) {
    if (isLockedOut()) {
        $remainingTime = LOCKOUT_TIME - (time() - $_SESSION['last_attempt']);
        $loginError = 'Too many failed attempts. Try again in ' . ceil($remainingTime / 60) . ' minutes.';
        auditLog('LOGIN_LOCKED', 'Attempted login while locked out');
    } else {
        $username = $_POST['login_username'];
        $password = $_POST['login_password'];
        
        // For initial setup, generate hash if default is present
        if (AUTH_PASSWORD_HASH === '$2y$10$YourBcryptHashHere') {
            // Temporary fallback for first-time setup
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['auth'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['current_dir'] = BASE_DIR;
                $_SESSION['login_attempts'] = 0;
                session_regenerate_id(true);
                auditLog('LOGIN_SUCCESS', 'User: ' . $username . ' (DEFAULT PASSWORD!)');
                header('Location: ' . $_SERVER['PHP_SELF']);
                exit;
            }
        }
        
        if ($username === AUTH_USERNAME && password_verify($password, AUTH_PASSWORD_HASH)) {
            $_SESSION['auth'] = true;
            $_SESSION['username'] = $username;
            $_SESSION['current_dir'] = BASE_DIR;
            $_SESSION['login_attempts'] = 0;
            session_regenerate_id(true);
            auditLog('LOGIN_SUCCESS', 'User: ' . $username);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['last_attempt'] = time();
            $loginError = 'Invalid credentials';
            auditLog('LOGIN_FAILED', 'User: ' . htmlspecialchars($username) . ' | Attempts: ' . $_SESSION['login_attempts']);
        }
    }
}

if (isset($_GET['logout'])) {
    auditLog('LOGOUT', 'User logged out');
    session_destroy();
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Password hash generator helper
if (isset($_GET['genhash']) && isset($_GET['pwd'])) {
    die('bcrypt hash: ' . password_hash($_GET['pwd'], PASSWORD_BCRYPT));
}

if (!isAuthenticated()) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                background: #1a1b1e;
                color: #e4e6eb;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }
            .login-box {
                background: rgba(255, 255, 255, 0.03);
                border: 1px solid rgba(255, 255, 255, 0.08);
                border-radius: 8px;
                padding: 40px;
                width: 100%;
                max-width: 400px;
            }
            h1 { font-size: 24px; margin-bottom: 24px; text-align: center; }
            input[type="text"],
            input[type="password"] {
                width: 100%;
                background: rgba(0, 0, 0, 0.2);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 4px;
                padding: 12px;
                color: #e4e6eb;
                font-size: 14px;
                margin-bottom: 16px;
            }
            button {
                width: 100%;
                background: rgba(34, 197, 94, 0.15);
                color: #22c55e;
                border: 1px solid rgba(34, 197, 94, 0.3);
                border-radius: 4px;
                padding: 12px;
                font-size: 14px;
                cursor: pointer;
            }
            button:hover { background: rgba(34, 197, 94, 0.25); }
            button:disabled {
                opacity: 0.5;
                cursor: not-allowed;
            }
            .error {
                color: #ef4444;
                font-size: 13px;
                margin-bottom: 16px;
                text-align: center;
            }
            .warning {
                color: #eab308;
                font-size: 12px;
                margin-top: 16px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h1>🔒 Secure File Manager</h1>
            <?php if (isset($loginError)): ?>
                <div class="error"><?= htmlspecialchars($loginError) ?></div>
            <?php endif; ?>
            <form method="post">
                <input type="text" name="login_username" placeholder="Username" autocomplete="username" required <?= isLockedOut() ? 'disabled' : '' ?>>
                <input type="password" name="login_password" placeholder="Password" autocomplete="current-password" required <?= isLockedOut() ? 'disabled' : '' ?>>
                <button type="submit" <?= isLockedOut() ? 'disabled' : '' ?>>Login</button>
            </form>
            <?php if (AUTH_PASSWORD_HASH === '$2y$10$YourBcryptHashHere'): ?>
                <div class="warning">⚠️ Using default credentials! Change immediately!</div>
            <?php endif; ?>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// ==================== SECURITY FUNCTIONS ====================
function validatePath($path) {
    $realPath = realpath($path);
    $baseDir = realpath(BASE_DIR);
    
    if (!$realPath || !$baseDir) {
        return false;
    }
    
    // Block symlinks
    if (is_link($path)) {
        auditLog('SECURITY_VIOLATION', 'Attempted symlink access: ' . $path);
        return false;
    }
    
    // Ensure path is within BASE_DIR (normalize slashes for cross-platform)
    $realPathNorm = str_replace('\\', '/', $realPath);
    $baseDirNorm = str_replace('\\', '/', $baseDir);
    
    if (strpos($realPathNorm, $baseDirNorm) !== 0) {
        auditLog('SECURITY_VIOLATION', 'Path traversal attempt: ' . $path);
        return false;
    }
    
    if (is_file($realPath) || is_dir($realPath)) {
        return $realPath;
    }
    
    return false;
}

function sanitizeFileName($name) {
    $name = basename($name);
    $name = preg_replace('/[^a-zA-Z0-9._-]/', '_', $name);
    if (empty($name) || $name === '.' || $name === '..') {
        return false;
    }
    return $name;
}

function isAllowedExtension($filename) {
    // Remove null bytes and sanitize
    $filename = str_replace("\0", '', $filename);
    $filename = basename($filename);
    
    // Get extension
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    
    // CRITICAL: Block dangerous extensions first (blacklist)
    if (in_array($ext, BLOCKED_EXTENSIONS)) {
        auditLog('BLOCKED_EXTENSION', "Attempt to use blocked extension: .$ext in $filename");
        return false;
    }
    
    // Check for double extensions (e.g., file.php.txt)
    $parts = explode('.', $filename);
    if (count($parts) > 2) {
        // Check all extensions, not just the last one
        for ($i = 1; $i < count($parts); $i++) {
            $extPart = strtolower($parts[$i]);
            // Block if ANY part is a dangerous extension
            if (in_array($extPart, BLOCKED_EXTENSIONS)) {
                auditLog('BLOCKED_EXTENSION', "Blocked double extension: $filename");
                return false;
            }
            // Ensure all parts are in allowed list
            if (!in_array($extPart, ALLOWED_EXTENSIONS)) {
                return false;
            }
        }
    }
    
    // Finally check whitelist
    return in_array($ext, ALLOWED_EXTENSIONS);
}

function validateFileContent($filePath) {
    if (!file_exists($filePath)) {
        return false;
    }
    
    // Read file content for malware scanning
    $content = @file_get_contents($filePath, false, null, 0, 1048576); // Read first 1MB
    if ($content === false) {
        auditLog('SECURITY_VIOLATION', 'Cannot read file for validation: ' . basename($filePath));
        return false;
    }
    
    // Detect malicious patterns
    if (detectMaliciousPatterns($content, basename($filePath))) {
        auditLog('MALWARE_BLOCKED', 'Malicious content detected in: ' . basename($filePath));
        @unlink($filePath); // Delete malicious file immediately
        return false;
    }
    
    // MIME type validation (ALWAYS enabled for security)
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($finfo, $filePath);
    finfo_close($finfo);
    
    // CRITICAL: Block executable MIME types
    $blockedMimes = [
        'application/x-php',
        'text/x-php',
        'application/x-httpd-php',
        'application/x-httpd-php-source',
        'text/x-script.phps',
        'application/x-executable',
        'application/x-msdos-program',
        'application/x-msdownload',
        'application/x-sh',
        'application/x-shellscript'
    ];
    
    if (in_array($mimeType, $blockedMimes)) {
        auditLog('SECURITY_VIOLATION', 'Blocked executable MIME type: ' . $mimeType . ' for ' . basename($filePath));
        @unlink($filePath); // Delete dangerous file
        return false;
    }
    
    // Safe MIME types whitelist
    $allowedMimes = [
        'text/plain',
        'text/html',
        'text/css',
        'text/javascript',
        'application/json',
        'application/xml',
        'text/xml',
        'application/octet-stream',
        'inode/x-empty',
        'application/x-empty',
        'text/csv',
        'application/yaml',
        'text/yaml',
        'text/x-yaml'
    ];
    
    // Allow text/* and reject everything else unless explicitly whitelisted
    if (strpos($mimeType, 'text/') === 0) {
        return true;
    }
    
    if (!in_array($mimeType, $allowedMimes)) {
        auditLog('SECURITY_VIOLATION', 'Invalid MIME type: ' . $mimeType . ' for ' . basename($filePath));
        return false;
    }
    
    return true;
}

// Command execution functionality REMOVED for security

checkAuth();

if (!isset($_SESSION['current_dir']) || !is_dir($_SESSION['current_dir'])) {
    $_SESSION['current_dir'] = __DIR__; // Start from the script location
}

// ==================== FILE UPLOAD HANDLER (before session start for early processing) ====================
// This is intentionally placed here to process uploads before HTML output

$notification = '';
$errorMsg = '';

// Command execution completely removed for security compliance

// ==================== FILE UPLOAD HANDLER ====================
if (isset($_FILES['standard_upload']) && $_FILES['standard_upload']['error'] !== UPLOAD_ERR_NO_FILE) {
    if (!checkRateLimit() || !checkIPRateLimit()) {
        auditLog('RATE_LIMIT', 'Upload rate limit exceeded - IP: ' . getClientIP());
        $errorMsg = 'Rate limit exceeded. Please wait before uploading again.';
    } elseif ($_FILES['standard_upload']['error'] === UPLOAD_ERR_OK) {
        $fileName = basename($_FILES['standard_upload']['name']);
        $tmpPath = $_FILES['standard_upload']['tmp_name'];
        
        if (!isAllowedExtension($fileName)) {
            auditLog('UPLOAD_BLOCKED', $fileName . ' - Extension not allowed');
            $errorMsg = 'Upload failed: File type not allowed';
        } elseif ($_FILES['standard_upload']['size'] > MAX_UPLOAD_SIZE) {
            auditLog('UPLOAD_BLOCKED', $fileName . ' - File too large');
            $errorMsg = 'Upload failed: File too large (max ' . number_format(MAX_UPLOAD_SIZE/1048576, 1) . 'MB)';
        } else {
            $uploadPath = rtrim($_SESSION['current_dir'], '/\\') . DIRECTORY_SEPARATOR . $fileName;
            
            if (file_exists($uploadPath)) {
                $errorMsg = 'Upload failed: File already exists';
            } else {
                // Validate file content before moving
                if (!validateFileContent($tmpPath)) {
                    auditLog('UPLOAD_BLOCKED', $fileName . ' - Invalid content type');
                    $errorMsg = 'Upload failed: Invalid file content';
                } elseif (@move_uploaded_file($tmpPath, $uploadPath)) {
                    @chmod($uploadPath, 0644);
                    auditLog('UPLOAD_SUCCESS', $fileName);
                    $notification = 'File uploaded successfully';
                } else {
                    $errorMsg = 'Upload failed: Could not move file';
                }
            }
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
        $errorMsg = 'Upload error: ' . ($uploadErrors[$_FILES['standard_upload']['error']] ?? 'Unknown error');
        auditLog('UPLOAD_FAILED', 'Error code: ' . $_FILES['standard_upload']['error']);
    }
}

// ==================== POST HANDLERS ====================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Skip CSRF validation for read-only actions (view, edit form display, download)
    // These just display forms or content, not modify data
    $readOnlyActions = ['view', 'edit', 'download', 'rename', 'navigate'];
    $isReadOnly = false;
    
    foreach ($readOnlyActions as $action) {
        if (isset($_POST[$action]) && !isset($_POST['file_content']) && !isset($_POST['new_name'])) {
            $isReadOnly = true;
            break;
        }
    }
    
    // Validate CSRF token for write operations
    if (!$isReadOnly && !validateCSRFToken()) {
        auditLog('CSRF_VIOLATION', 'Invalid CSRF token for action');
        die('Security error: Invalid request token');
    }
    
    // Rate limiting for file operations
    if (isset($_POST['remove']) || isset($_POST['bulk_delete']) || isset($_POST['old_name'])) {
        if (!checkRateLimit() || !checkIPRateLimit()) {
            auditLog('RATE_LIMIT', 'File operation rate limit exceeded - IP: ' . getClientIP());
            die('Rate limit exceeded. Please wait before performing more operations.');
        }
    }
}

if (isset($_POST['navigate'])) {
    $targetDir = $_POST['navigate'];
    $validatedPath = validatePath($targetDir);
    if ($validatedPath && is_dir($validatedPath)) {
        $_SESSION['current_dir'] = $validatedPath;
        $notification = 'Directory changed successfully';
        auditLog('NAVIGATE', $validatedPath);
    } else {
        $errorMsg = 'Invalid directory or access denied';
        auditLog('NAVIGATE_FAILED', $targetDir);
    }
}

if (isset($_POST['remove'])) {
    $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['remove']);
    
    if ($targetPath === false) {
        $errorMsg = 'Delete failed: Invalid path';
    } elseif ($targetPath === BASE_DIR) {
        $errorMsg = 'Delete failed: Cannot delete base directory';
        auditLog('DELETE_BLOCKED', 'Attempted to delete base directory');
    } elseif (is_file($targetPath)) {
        if (@unlink($targetPath)) {
            $notification = 'File deleted';
            auditLog('FILE_DELETE', basename($targetPath));
        } else {
            $errorMsg = 'Delete failed: Permission denied';
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
                auditLog('DIR_DELETE', basename($targetPath));
            } else {
                $errorMsg = 'Delete failed: Could not remove directory';
            }
        } catch (Exception $e) {
            $errorMsg = 'Delete failed: ' . $e->getMessage();
        }
    }
}

if (isset($_POST['old_name'], $_POST['new_name'])) {
    $sourcePath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['old_name']);
    
    if ($sourcePath === false) {
        $errorMsg = 'Rename failed: Source not found';
    } else {
        $newName = sanitizeFileName($_POST['new_name']);
        if ($newName === false) {
            $errorMsg = 'Rename failed: Invalid filename';
        } else {
            $destinationPath = dirname($sourcePath) . DIRECTORY_SEPARATOR . $newName;
            
            if (file_exists($destinationPath)) {
                $errorMsg = 'Rename failed: Target name already exists';
            } elseif (@rename($sourcePath, $destinationPath)) {
                $notification = 'Rename successful';
                auditLog('RENAME', basename($sourcePath) . ' -> ' . $newName);
            } else {
                $errorMsg = 'Rename failed: Permission denied';
            }
        }
    }
}

if (isset($_POST['file_to_edit'], $_POST['file_content'])) {
    $editPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['file_to_edit']);
    
    if ($editPath === false || !is_file($editPath)) {
        $errorMsg = 'Edit failed: File not found';
    } elseif (!is_writable($editPath)) {
        $errorMsg = 'Edit failed: File not writable';
    } elseif (filesize($editPath) > MAX_UPLOAD_SIZE) {
        $errorMsg = 'Edit failed: File too large';
    } elseif (@file_put_contents($editPath, $_POST['file_content']) !== false) {
        $notification = 'File saved';
        auditLog('FILE_EDIT', basename($editPath));
    } else {
        $errorMsg = 'Edit failed: Could not write to file';
    }
}

if (isset($_POST['create_file']) && trim($_POST['create_file']) !== '') {
    $fileName = sanitizeFileName($_POST['create_file']);
    
    if ($fileName === false) {
        $errorMsg = 'Create failed: Invalid filename';
    } elseif (!isAllowedExtension($fileName)) {
        $errorMsg = 'Create failed: File type not allowed';
    } else {
        $newFilePath = $_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $fileName;
        
        if (file_exists($newFilePath)) {
            $errorMsg = 'Create failed: File already exists';
        } elseif (!is_writable($_SESSION['current_dir'])) {
            $errorMsg = 'Create failed: Directory not writable';
        } elseif (@file_put_contents($newFilePath, '') !== false) {
            @chmod($newFilePath, 0644);
            $notification = 'File created';
            auditLog('FILE_CREATE', $fileName);
        } else {
            $errorMsg = 'Create failed: Could not create file';
        }
    }
}

if (isset($_POST['create_folder']) && trim($_POST['create_folder']) !== '') {
    $folderName = sanitizeFileName($_POST['create_folder']);
    
    if ($folderName === false) {
        $errorMsg = 'Create failed: Invalid folder name';
    } else {
        $newFolderPath = $_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $folderName;
        
        if (file_exists($newFolderPath)) {
            $errorMsg = 'Create failed: Folder already exists';
        } elseif (!is_writable($_SESSION['current_dir'])) {
            $errorMsg = 'Create failed: Directory not writable';
        } elseif (@mkdir($newFolderPath, 0755)) {
            $notification = 'Folder created';
            auditLog('DIR_CREATE', $folderName);
        } else {
            $errorMsg = 'Create failed: Could not create folder';
        }
    }
}

if (isset($_POST['bulk_delete']) && isset($_POST['selected_items']) && is_array($_POST['selected_items'])) {
    $deleted = 0;
    $failed = 0;
    
    foreach ($_POST['selected_items'] as $item) {
        $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $item);
        
        if ($targetPath === false || $targetPath === BASE_DIR) {
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
        auditLog('BULK_DELETE', "$deleted item(s) deleted");
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
            
            auditLog('BULK_DOWNLOAD', count($_POST['selected_items']) . ' item(s)');
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

if (isset($_POST['download'])) {
    $targetPath = validatePath($_SESSION['current_dir'] . DIRECTORY_SEPARATOR . $_POST['download']);
    
    if ($targetPath === false) {
        $errorMsg = 'Download failed: Invalid path';
    } elseif (is_file($targetPath)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($targetPath) . '"');
        header('Content-Length: ' . filesize($targetPath));
        readfile($targetPath);
        exit;
    } elseif (is_dir($targetPath)) {
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
            }
        }
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
$fileContent = null;
$viewContent = null;

if ($fileToEdit) {
    $editPath = validatePath($currentDirectory . '/' . $fileToEdit);
    if ($editPath && is_file($editPath) && filesize($editPath) <= MAX_UPLOAD_SIZE) {
        $fileContent = @file_get_contents($editPath);
    } else {
        $errorMsg = 'Cannot edit: File too large or invalid';
        $fileToEdit = null;
    }
}

if ($fileToView) {
    $viewPath = validatePath($currentDirectory . '/' . $fileToView);
    if ($viewPath && is_file($viewPath) && filesize($viewPath) <= MAX_UPLOAD_SIZE) {
        $viewContent = @file_get_contents($viewPath);
    } else {
        $errorMsg = 'Cannot view: File too large or invalid';
        $fileToView = null;
    }
}

// Terminal/command execution completely removed for security
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FILE MANAGER</title>
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
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .header h1 {
            font-size: 16px;
            font-weight: 500;
            color: #e4e6eb;
        }
        
        .logout-btn {
            background: rgba(239, 68, 68, 0.15);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.3);
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 12px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        
        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.25);
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
        
        .alert-warning {
            background: rgba(234, 179, 8, 0.1);
            border-color: rgba(234, 179, 8, 0.3);
            color: #eab308;
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
    <div class="header">
        <h1>FILE MANAGER</h1>
        <a href="?logout" class="logout-btn">Logout</a>
    </div>
    <p class="subtitle">Secure file management system</p>

    <?php if (AUTH_PASSWORD_HASH === '$2y$10$YourBcryptHashHere'): ?>
        <div class="alert alert-danger">
            <strong>⚠️ SECURITY WARNING:</strong> Using default password! Generate new hash: 
            <code>?genhash&pwd=YourNewPassword</code>
        </div>
    <?php endif; ?>

    <div class="alert alert-success">
        <strong>🛡️ Security Enhancements Active:</strong> 
        PHP/executable uploads blocked | 
        Malware pattern detection enabled | 
        IP-based rate limiting active | 
        Strict MIME validation enforced
    </div>

    <div class="alert alert-warning">
        <strong>Security:</strong> Max file size: <?= number_format(MAX_UPLOAD_SIZE/1048576, 1) ?>MB | 
        Allowed types: <?= htmlspecialchars(implode(', ', ALLOWED_EXTENSIONS), ENT_QUOTES, 'UTF-8') ?> | 
        Base: <?= htmlspecialchars(basename(BASE_DIR), ENT_QUOTES, 'UTF-8') ?> | 
        IP Whitelist: <?= empty(ALLOWED_IPS) ? 'All IPs' : 'Restricted' ?> | 
        Session timeout: <?= SESSION_TIMEOUT/60 ?> min | 
        Rate limit: <?= MAX_FILE_OPERATIONS_PER_MINUTE ?>/min (session), <?= MAX_FILE_OPERATIONS_PER_IP_PER_MINUTE ?>/min (IP)
    </div>

    <?php if ($notification): ?>
        <div class="alert alert-success"><?= htmlspecialchars($notification, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>
    
    <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMsg, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Current Directory</div>
        <form method="post" class="input-group">
            <input type="text" name="navigate" value="<?= htmlspecialchars($currentDirectory, ENT_QUOTES, 'UTF-8') ?>" placeholder="Enter path...">
            <button class="btn" type="submit">Navigate</button>
        </form>
    </div>

    <div class="grid-2">
        <div class="section">
            <div class="section-title">Upload File</div>
            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <div class="input-group">
                    <input type="file" name="standard_upload" required>
                    <button class="btn btn-primary" type="submit">Upload</button>
                </div>
                <p style="margin-top: 8px; font-size: 11px; color: #9ca3af;">
                    Max: <?= number_format(MAX_UPLOAD_SIZE/1048576, 1) ?>MB | 
                    Types: <?= implode(', ', array_slice(ALLOWED_EXTENSIONS, 0, 5)) ?><?= count(ALLOWED_EXTENSIONS) > 5 ? '...' : '' ?>
                </p>
            </form>
        </div>

        <div class="section">
            <div class="section-title">Create New</div>
            <form method="post" class="input-group">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <input type="text" name="create_file" placeholder="New file name...">
                <button class="btn btn-create" type="submit">File</button>
            </form>
            <form method="post" class="input-group">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <input type="text" name="create_folder" placeholder="New folder name...">
                <button class="btn btn-create" type="submit">Folder</button>
            </form>
        </div>
    </div>

    <?php if ($fileToView && $viewContent !== null): ?>
        <div class="section">
            <div class="section-title">Viewing: <?= htmlspecialchars($fileToView, ENT_QUOTES, 'UTF-8') ?></div>
            <textarea readonly><?= htmlspecialchars($viewContent, ENT_QUOTES, 'UTF-8') ?></textarea>
        </div>
    <?php endif; ?>

    <?php if ($fileToEdit !== null && $fileContent !== null): ?>
        <div class="section">
            <div class="section-title">Editing: <?= htmlspecialchars($fileToEdit, ENT_QUOTES, 'UTF-8') ?></div>
            <form method="post">
                <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                <input type="hidden" name="file_to_edit" value="<?= htmlspecialchars($fileToEdit, ENT_QUOTES, 'UTF-8') ?>">
                <textarea name="file_content"><?= htmlspecialchars($fileContent, ENT_QUOTES, 'UTF-8') ?></textarea>
                <div style="margin-top: 12px;">
                    <button class="btn btn-primary" type="submit">Save Changes</button>
                </div>
            </form>
        </div>
    <?php endif; ?>



    <?php if ($currentDirectory !== BASE_DIR): ?>
        <form method="post">
            <button name="navigate" value="<?= htmlspecialchars(dirname($currentDirectory), ENT_QUOTES, 'UTF-8') ?>" class="btn" style="margin-bottom: 12px;">← Parent Directory</button>
        </form>
    <?php endif; ?>

    <form method="post" id="file-form">
        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
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
                    <input type="checkbox" name="selected_items[]" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" onclick="updateBulkActions()">
                </td>
                <td>
                    <?php if ($itemToRename === $item): ?>
                        </form>
                        <form method="post" class="rename-form">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            <input type="hidden" name="old_name" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
                            <input type="text" name="new_name" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>">
                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                        </form>
                        <form method="post" id="file-form">
                        <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                    <?php else: ?>
                        <span class="file-icon"><?= $isDirectory ? '📁' : '📄' ?></span>
                        <span class="file-name"><?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?></span>
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
                            <button name="navigate" value="<?= htmlspecialchars($fullPath, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm">Open</button>
                        </form>
                    <?php else: ?>
                        <form method="post">
                            <button name="view" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm">View</button>
                        </form>
                        <form method="post">
                            <button name="edit" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm">Edit</button>
                        </form>
                    <?php endif; ?>
                        <form method="post">
                            <button name="download" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm">Download</button>
                        </form>
                        <form method="post">
                            <button name="rename" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-sm">Rename</button>
                        </form>
                        <form method="post">
                            <input type="hidden" name="csrf_token" value="<?= generateCSRFToken() ?>">
                            <button name="remove" value="<?= htmlspecialchars($item, ENT_QUOTES, 'UTF-8') ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this item?')">Delete</button>
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
