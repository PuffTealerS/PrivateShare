<?php 
//										   //
//WANT TO MAKE A DONATION FOR THIS SCRIPT ?//
//      BTC DONATION AVAILABLE : 		   //
//   1AsQK6bKFLexyKQ3naXMUnGyDeDXvN4563    //
//
//Author : @Seriesme
//
session_start();
error_reporting(E_ALL);
set_time_limit(0);
include("includes/config.php");
include("includes/functions.php");

//Si déjà identifié 
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {


if(!empty($_GET['file']) ) {

$burl=isset($_GET['b']) ? intval($_GET['b']) : null;

$nfile=isset($_GET['file']) ? $_GET['file'] : null;

$file=cleandl($nfile);

$url=''.$baseurl[$burl].''.$file.'';


if (file_exists($url)) {
	if($logip==1) { $ip = get_ip (); }
	else { $ip ='0.0.0.0'; }
	$action = 'Download:    '.$nfile.'';
	$date=date("Y-m-d");
	$time=date("H:i:s");
	$log = $bdd->prepare('INSERT INTO logs(uid, username, action, date, time, ip) VALUES(:uid, :username, :action, :date, :time, :ip)');
	$log->execute(array(':uid' => $_SESSION['id'], ':username' => $_SESSION['username'], ':action' => $action, ':date' => $date, ':time' => $time, ':ip' => $ip));
	session_write_close();
	if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
	header("Pragma: public"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); 
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=\"".basename($file)."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($url));
	ob_end_clean();
	flush();
	readfile("$url");
	exit();
	}

}

elseif(!empty($_GET['sfile'])) {

$nfile=isset($_GET['sfile']) ? $_GET['sfile'] : null;

$burl=isset($_GET['b']) ? intval($_GET['b']) : null;

$file=cleandl($nfile);

$url=''.$baseurl[$burl].''.$file.'';

$array = explode('/',$file);
$result=count($array);
$result--;


if (file_exists($url)) {
	if($logip==1) { $ip = get_ip (); }
	else { $ip ='0.0.0.0'; }
	$action = 'Download:    '.$array[''.$result.''].'';
	$date=date("Y-m-d");
	$time=date("H:i:s");
	$log = $bdd->prepare('INSERT INTO logs(uid, username, action, date, time, ip) VALUES(:uid, :username, :action, :date, :time, :ip)');
	$log->execute(array(':uid' => $_SESSION['id'], ':username' => $_SESSION['username'], ':action' => $action, ':date' => $date, ':time' => $time, ':ip' => $ip));
	session_write_close();
    if(ini_get('zlib.output_compression'))
		ini_set('zlib.output_compression', 'Off');
	header("Pragma: public"); 
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false); 
	header("Content-Type: application/force-download");
	header("Content-Disposition: attachment; filename=\"".basename($array[''.$result.''])."\";" );
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($url));
	ob_end_clean();
	flush();
	readfile("$url");
	exit();
}

}


else { header ("Location: login.php");}

}
//Sinon
else { header ("Location: login.php");}


?>