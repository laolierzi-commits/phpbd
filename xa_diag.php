<?php
@ini_set('display_errors','0');@error_reporting(0);
function xa_post($k,$d=null){return isset($_POST[$k])?$_POST[$k]:$d;}
function xfn($f){if(!function_exists($f))return false;$d=@ini_get('disable_functions');if($d&&in_array(strtolower($f),array_map('trim',explode(',',strtolower($d)))))return false;return true;}
function xrun($cmd){$cmd.=' 2>&1';if(xfn('shell_exec')){$r=@shell_exec($cmd);if($r!==null&&$r!==false)return $r;}if(xfn('exec')){$o=array();@exec($cmd,$o);if($o)return implode("\n",$o);}foreach(array('system','passthru') as $f)if(xfn($f)){ob_start();@$f($cmd);$r=ob_get_clean();if($r!=='')return $r;}if(xfn('popen')){$h=@popen($cmd,'r');if($h){$r='';while(!feof($h))$r.=fread($h,4096);pclose($h);return $r;}}return null;}
function xwhich($bin){$r=xrun('command -v '.escapeshellarg($bin).' || which '.escapeshellarg($bin));$r=trim((string)$r);return $r!==''&&strpos($r,'not found')===false?$r:false;}
function xver($cmd){$r=xrun($cmd);if($r===null)return '-';$r=trim($r);$lines=preg_split('/\r?\n/',$r);return $lines?trim($lines[0]):'-';}

$mailResult='';$mailErr='';
if(isset($_POST['test_mail'])){
    $to=trim($_POST['to']);$from=trim($_POST['from']);$subj=trim($_POST['subj']);$body=$_POST['body'];
    if(!filter_var($to,FILTER_VALIDATE_EMAIL))$mailErr='Invalid recipient email';
    elseif(!xfn('mail'))$mailErr='mail() function is disabled on this server';
    else{
        $hdr='';
        if($from!=='')$hdr.="From: $from\r\nReply-To: $from\r\n";
        $hdr.="X-Mailer: PHP/".phpversion()."\r\nMIME-Version: 1.0\r\nContent-Type: text/plain; charset=UTF-8\r\n";
        $ok=@mail($to,$subj,$body,$hdr);
        $mailResult=$ok?"Mail handed off to MTA successfully (recipient: $to). Check inbox/spam.":"mail() returned false. Check MTA / sendmail_path.";
        if(!$ok)$mailErr=$mailResult;
    }
}

// PHP extensions / classes / functions to test
$phpChecks=array(
    'zip (ZipArchive)'=>class_exists('ZipArchive'),
    'zlib (gzopen)'=>function_exists('gzopen'),
    'bz2 (bzopen)'=>function_exists('bzopen'),
    'phar (Phar)'=>class_exists('Phar'),
    'rar (RarArchive)'=>class_exists('RarArchive'),
    'PharData (tar/gz/bz2)'=>class_exists('PharData'),
    'curl'=>function_exists('curl_init'),
    'openssl'=>function_exists('openssl_encrypt'),
    'mbstring'=>function_exists('mb_strlen'),
    'iconv'=>function_exists('iconv'),
    'gd'=>function_exists('imagecreate'),
    'fileinfo'=>function_exists('finfo_open'),
    'mysqli'=>function_exists('mysqli_connect'),
    'pdo'=>class_exists('PDO'),
    'sockets'=>function_exists('socket_create'),
    'ftp'=>function_exists('ftp_connect'),
    'mail()'=>xfn('mail'),
    'imap'=>function_exists('imap_open'),
);

// Exec functions
$execChecks=array('shell_exec','exec','system','passthru','popen','proc_open');

// External CLI tools to detect
$cliTools=array(
    'zip'=>'zip -v',
    'unzip'=>'unzip -v',
    'rar'=>'rar',
    'unrar'=>'unrar',
    '7z'=>'7z i',
    'tar'=>'tar --version',
    'gzip'=>'gzip --version',
    'gunzip'=>'gunzip --version',
    'bzip2'=>'bzip2 --version',
    'xz'=>'xz --version',
    'curl'=>'curl --version',
    'wget'=>'wget --version',
    'sendmail'=>'sendmail -h',
    'ssmtp'=>'ssmtp -V',
    'msmtp'=>'msmtp --version',
    'mail'=>'mail -V',
    'mailx'=>'mailx -V',
    'php'=>'php -v',
    'python'=>'python --version',
    'python3'=>'python3 --version',
    'perl'=>'perl --version',
    'git'=>'git --version',
);

$serverInfo=array(
    'PHP Version'=>phpversion(),
    'PHP SAPI'=>php_sapi_name(),
    'OS'=>php_uname(),
    'Server'=>isset($_SERVER['SERVER_SOFTWARE'])?$_SERVER['SERVER_SOFTWARE']:'-',
    'User'=>xfn('posix_getpwuid')&&function_exists('posix_geteuid')?@posix_getpwuid(@posix_geteuid())['name']:get_current_user(),
    'Document Root'=>isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:'-',
    'Disabled functions'=>(@ini_get('disable_functions')?:'(none)'),
    'open_basedir'=>(@ini_get('open_basedir')?:'(none)'),
    'safe_mode'=>(@ini_get('safe_mode')?'On':'Off'),
    'upload_max_filesize'=>@ini_get('upload_max_filesize'),
    'post_max_size'=>@ini_get('post_max_size'),
    'memory_limit'=>@ini_get('memory_limit'),
    'max_execution_time'=>@ini_get('max_execution_time'),
    'sendmail_path'=>(@ini_get('sendmail_path')?:'(not set)'),
    'SMTP'=>(@ini_get('SMTP')?:'(not set)'),
    'smtp_port'=>(@ini_get('smtp_port')?:'(not set)'),
);

function H($s){return htmlentities((string)$s,ENT_QUOTES,'UTF-8');}
function badge($ok,$yes='YES',$no='NO'){return '<span class="b '.($ok?'b-ok':'b-no').'">'.($ok?$yes:$no).'</span>';}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>XANNYANAXIUM SERVER DIAGNOSTICS</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#1a1b1e;color:#e4e6eb;font:13px/1.5 -apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif;min-height:100vh;padding:20px}
.container{max-width:1200px;margin:0 auto}
h1{font-size:18px;font-weight:500;margin-bottom:4px}
.subtitle{color:#9ca3af;margin-bottom:20px}
.section{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:4px;padding:14px;margin-bottom:12px}
.section-title{font-weight:500;margin-bottom:10px;color:#e4e6eb;font-size:14px}
table{width:100%;border-collapse:collapse;font-size:13px}
th,td{padding:6px 10px;text-align:left;border-bottom:1px solid rgba(255,255,255,.05)}
th{color:#9ca3af;font-weight:500;font-size:12px;background:rgba(255,255,255,.03)}
td.k{color:#9ca3af;width:240px}
td.v{font-family:'SF Mono',Monaco,Consolas,monospace;font-size:12px;color:#e4e6eb;word-break:break-all}
.b{display:inline-block;padding:2px 8px;border-radius:3px;font-size:11px;font-weight:600;letter-spacing:.5px}
.b-ok{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.3)}
.b-no{background:rgba(239,68,68,.15);color:#ef4444;border:1px solid rgba(239,68,68,.3)}
.b-warn{background:rgba(245,158,11,.15);color:#f59e0b;border:1px solid rgba(245,158,11,.3)}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:768px){.grid-2{grid-template-columns:1fr}body{padding:12px}}
.input,textarea{background:rgba(0,0,0,.3);border:1px solid rgba(255,255,255,.1);border-radius:4px;padding:6px 10px;color:#e4e6eb;font:13px inherit;outline:0;width:100%}
textarea{font-family:'SF Mono',Monaco,Consolas,monospace;font-size:12px;min-height:80px;resize:vertical}
.input:focus,textarea:focus{border-color:rgba(255,255,255,.2)}
.row{display:flex;gap:8px;margin-bottom:8px}
.row label{width:120px;color:#9ca3af;font-size:12px;align-self:center}
.btn{background:rgba(34,197,94,.15);color:#22c55e;border:1px solid rgba(34,197,94,.3);border-radius:4px;padding:6px 14px;font:13px inherit;cursor:pointer;transition:.15s}
.btn:hover{background:rgba(34,197,94,.25)}
.alert{padding:10px 12px;border-radius:4px;margin-bottom:12px;border:1px solid;font-size:13px}
.alert-ok{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.3);color:#22c55e}
.alert-err{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#ef4444}
.cli-path{color:#22c55e;font-size:11px;font-family:'SF Mono',Monaco,Consolas,monospace}
.cli-ver{color:#9ca3af;font-size:11px;font-family:'SF Mono',Monaco,Consolas,monospace}
</style></head><body><div class="container">
<h1>XANNYANAXIUM SERVER DIAGNOSTICS</h1>
<p class="subtitle">PHP extensions, archive tools, and mail sender check</p>

<div class="section"><div class="section-title">Server Info</div>
<table><tbody>
<?php foreach($serverInfo as $k=>$v):?><tr><td class="k"><?=H($k)?></td><td class="v"><?=H($v)?></td></tr><?php endforeach;?>
</tbody></table>
</div>

<div class="grid-2">
<div class="section"><div class="section-title">PHP Extensions / Functions</div>
<table><tbody>
<?php foreach($phpChecks as $k=>$ok):?><tr><td class="k"><?=H($k)?></td><td><?=badge($ok)?></td></tr><?php endforeach;?>
</tbody></table>
</div>

<div class="section"><div class="section-title">Command Execution Functions</div>
<table><tbody>
<?php foreach($execChecks as $f):?><tr><td class="k"><?=H($f)?>()</td><td><?=badge(xfn($f))?></td></tr><?php endforeach;?>
</tbody></table>
</div>
</div>

<div class="section"><div class="section-title">External CLI Tools (archive / mail / misc)</div>
<?php $anyExec=false;foreach($execChecks as $f)if(xfn($f)){$anyExec=true;break;}?>
<?php if(!$anyExec):?><div class="alert alert-err">No exec functions available; CLI tools cannot be probed.</div>
<?php else:?>
<table><thead><tr><th style="width:140px">Tool</th><th style="width:90px">Status</th><th style="width:auto">Path</th><th>Version</th></tr></thead><tbody>
<?php foreach($cliTools as $bin=>$verCmd):
    $path=xwhich($bin);$ver=$path?xver($verCmd):'';
?>
<tr>
<td class="k"><?=H($bin)?></td>
<td><?=badge((bool)$path,'FOUND','MISSING')?></td>
<td class="cli-path"><?=H($path?:'-')?></td>
<td class="cli-ver"><?=H($ver?:'-')?></td>
</tr>
<?php endforeach;?>
</tbody></table>
<?php endif;?>
</div>

<div class="section"><div class="section-title">Archive Capability Summary</div>
<table><tbody>
<?php
$canZipExt=class_exists('ZipArchive');
$canZipCli=$anyExec&&xwhich('zip')&&xwhich('unzip');
$canRarExt=class_exists('RarArchive');
$canRarCli=$anyExec&&(xwhich('rar')||xwhich('unrar'));
$canTarCli=$anyExec&&xwhich('tar');
$can7zCli=$anyExec&&xwhich('7z');
$canPhar=class_exists('PharData');
$rows=array(
 'Create/Extract ZIP (PHP)'=>$canZipExt,
 'Create/Extract ZIP (CLI)'=>$canZipCli,
 'Create/Extract RAR (PHP ext)'=>$canRarExt,
 'Create/Extract RAR (CLI)'=>$canRarCli,
 'Create/Extract TAR/GZ/BZ2 (CLI)'=>$canTarCli,
 'Create/Extract TAR/GZ/BZ2 (PHP PharData)'=>$canPhar,
 'Create/Extract 7z (CLI)'=>$can7zCli,
);
foreach($rows as $k=>$ok):?><tr><td class="k"><?=H($k)?></td><td><?=badge($ok,'AVAILABLE','UNAVAILABLE')?></td></tr><?php endforeach;?>
</tbody></table>
</div>

<div class="section"><div class="section-title">Mail Sender Test</div>
<?php if($mailResult):?><div class="alert <?=$mailErr?'alert-err':'alert-ok'?>"><?=H($mailResult)?></div><?php endif;?>
<table style="margin-bottom:12px"><tbody>
<tr><td class="k">mail() function</td><td><?=badge(xfn('mail'))?></td></tr>
<tr><td class="k">sendmail_path</td><td class="v"><?=H(@ini_get('sendmail_path')?:'(not set)')?></td></tr>
<tr><td class="k">sendmail binary</td><td><?php $sm=xwhich('sendmail');?><?=badge((bool)$sm,'FOUND','MISSING')?> <span class="cli-path"><?=H($sm?:'')?></span></td></tr>
<tr><td class="k">SMTP host (Win)</td><td class="v"><?=H(@ini_get('SMTP')?:'(not set)')?></td></tr>
<tr><td class="k">smtp_port</td><td class="v"><?=H(@ini_get('smtp_port')?:'(not set)')?></td></tr>
</tbody></table>
<form method="post">
<div class="row"><label>To</label><input class="input" type="email" name="to" required value="<?=H(xa_post('to',''))?>" placeholder="recipient@example.com"></div>
<div class="row"><label>From</label><input class="input" type="text" name="from" value="<?=H(xa_post('from',''))?>" placeholder="sender@example.com (optional)"></div>
<div class="row"><label>Subject</label><input class="input" type="text" name="subj" value="<?=H(xa_post('subj','XA Mail Test '.date('Y-m-d H:i:s')))?>"></div>
<div class="row" style="align-items:flex-start"><label>Body</label><textarea name="body" placeholder="Test message body..."><?=H(xa_post('body','Hello from PHP mail() on '.gethostname().' at '.date('c')))?></textarea></div>
<div style="text-align:right"><button class="btn" type="submit" name="test_mail" value="1">Send Test Mail</button></div>
</form>
</div>

</div>
</body></html>
