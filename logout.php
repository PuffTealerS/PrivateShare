<?php
//					//
//Author : @Seriesme//
//					//
session_start();
include("includes/config.php");
include("includes/functions.php");

//On vérifie que l'utilsateur est bien identifié
if (!empty($_SESSION['id']) AND !empty($_SESSION['username'])) {

if($logip==1) { $ip = get_ip (); }
else { $ip ='0.0.0.0'; }
$action = 'Log out';
$date=date("Y-m-d");
$time=date("H:i:s");
$log = $bdd->prepare('INSERT INTO logs(uid, username, ip, action, date, time) VALUES(:uid, :username, :ip, :action, :date, :time)');
$log->execute(array('uid' => $_SESSION['id'], 'username' => $_SESSION['username'], 'ip' => $ip, 'action' => $action, 'date' => $date, 'time' => $time));

vider_cookie();
session_destroy();

header ("Refresh: 3;URL=login.php");
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <title>Log out</title>
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/signin2.css" rel="stylesheet">
 </head>

  <body>

    <div class="container">

	  <div class="alert alert-dismissable alert-danger">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Successfully log out!</strong> You will be redirect soon.
            </div>
<div class="progress progress-striped active">
                <div class="progress-bar progress-bar-info" style="width: 100%"></div>
              </div>
    </div> 
  </body>
</html>

<?php

}
else { header ("Location: index.php");}
?>