<?php 

// --- Merci à Seriasme
//

error_reporting(E_ALL);
session_start();
include("includes/config.php");
include("includes/functions.php");

// --- Si déjà identifié 
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
$codec=isset($_GET['codec']) ? htmlspecialchars($_GET['codec']) : null;
$file=isset($_GET['file']) ? htmlspecialchars($_GET['file']) : null;
$sfile=isset($_GET['sfile']) ? htmlspecialchars($_GET['sfile']) : null;
$burl=isset($_GET['b']) ? intval($_GET['b']) : null;

if(!empty($codec) && !empty($file)) {

if($logip==1) { $ip = get_ip (); }
else { $ip ='0.0.0.0'; }
$action = 'Streaming:    '.$file.'';
$date=date("Y-m-d");
$time=date("H:i:s");
$log = $bdd->prepare('INSERT INTO logs(uid, username, action, date, time, ip) VALUES(:uid, :username, :action, :date, :time, :ip)');
$log->execute(array(':uid' => $_SESSION['id'], ':username' => $_SESSION['username'], ':action' => $action, ':date' => $date, ':time' => $time, ':ip' => $ip));

if($codec=='mp4') {
if(preg_match("/\.mp4/", $file)) { 
include("header.php");
include("menu.php");
$name=clean_name($file);
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?file='.$file.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <video width="690" height="380" autoplay controls>
				<source src="http://5.135.159.233/cakebox/downloads/'.$file.'" type="video/mp4">
				Your browser does not support the video tag.
				</video>
				</center>
				</div>
           </div>';
		}
else { header ("Location: index.php"); }
	}


elseif($codec=='mkv') {
if(preg_match("/\.mkv/", $file)) { 
if(preg_match('/Chrome/i',$_SERVER['HTTP_USER_AGENT'])){
include("header.php");
include("menu.php");
$name=clean_name($file);
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?file='.$file.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <video width="690" height="380" autoplay controls>
				<source src="http://5.135.159.233/cakebox/downloads/'.$file.'" type="video/mp4">
				Your browser does not support the video tag.
				</video>
				</center>
				</div>
           </div>';
		   }
else {
include("header.php");
include("menu.php");
$name=clean_name($file);
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?file='.$file.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="690px" height="380px" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
	            <param name="src" value="http://5.135.159.233/cakebox/downloads/'.$file.'" />
	            <embed type="video/divx" src="http://5.135.159.233/cakebox/downloads/'.$sfile.'"
	                   width="640" height="360"
	                   pluginspage="http://go.divx.com/plugin/download/">
	            </embed>
				</object>
				</center>
				</div>
           </div>';
		}
		}
else { header ("Location: index.php"); }
}

elseif($codec=='avi') {
if(preg_match("/\.avi/", $file)) { 
include("header.php");
include("menu.php");
$name=clean_name($file);
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?file='.$file.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="690px" height="380px" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
	            <param name="src" value="http://5.135.159.233/cakebox/downloads/'.$file.'" />
	            <embed type="video/divx" src="http://5.135.159.233/cakebox/downloads/'.$sfile.'"
	                   width="640" height="360"
	                   pluginspage="http://go.divx.com/plugin/download/">
	            </embed>
				</object>
				</center>
				</div>
           </div>';
		}
else { header ("Location: index.php"); }
}

else { header ("Location: index.php"); }



}
////////////////////////
elseif(!empty($codec) && !empty($sfile)) {

$array = explode('/',$sfile);
$result=count($array);
$result--;
$name=clean_name($array[''.$result.'']);
if($logip==1) { $ip = get_ip (); }
else { $ip ='0.0.0.0'; }
$action = 'Streaming:    '.$array[''.$result.''].'';
$date=date("Y-m-d");
$time=date("H:i:s");
$log = $bdd->prepare('INSERT INTO logs(uid, username, action, date, time, ip) VALUES(:uid, :username, :action, :date, :time, :ip)');
$log->execute(array(':uid' => $_SESSION['id'], ':username' => $_SESSION['username'], ':action' => $action, ':date' => $date, ':time' => $time, ':ip' => $ip));

if($codec=='mp4') {
if(preg_match("/\.mp4/", $sfile)) { 
include("header.php");
include("menu.php");
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?sfile='.$sfile.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <video width="690" height="380" autoplay controls>
				<source src="http://5.135.159.233/cakebox/downloads/'.$sfile.'" type="video/mp4">
				Your browser does not support the video tag.
				</video>
				</center>
				</div>
           </div>';
		}
else { header ("Location: index.php"); }
	}


elseif($codec=='mkv') {
if(preg_match("/\.mkv/", $sfile)) { 
if(preg_match('/Chrome/i',$_SERVER['HTTP_USER_AGENT'])){
include("header.php");
include("menu.php");
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?sfile='.$sfile.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <video width="690" height="380" autoplay controls>
				<source src="http://5.135.159.233/cakebox/downloads/'.$sfile.'" type="video/mp4">
				Your browser does not support the video tag.
				</video>
				</center>
				</div>
           </div>';
			}
else {
include("header.php");
include("menu.php");
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?sfile='.$sfile.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="690px" height="380px" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
	            <param name="src" value="http://5.135.159.233/cakebox/downloads/'.$sfile.'" />
	            <embed type="video/divx" src="http://5.135.159.233/cakebox/downloads/'.$sfile.'"
	                   width="640" height="360"
	                   pluginspage="http://go.divx.com/plugin/download/">
	            </embed>
				</object>
				</center>
				</div>
           </div>';

		}
		}
else { header ("Location: index.php"); }
}

elseif($codec=='avi') {
if(preg_match("/\.avi/", $sfile)) { 
include("header.php");
include("menu.php");
echo' <div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Streaming de : <a href="download.php?sfile='.$sfile.'&b='.$burl.'" >'.$name.'</a></h3>
              </div>
              <div class="panel-body">
			  <center>
			  <object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="690px" height="380px" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">
	            <param name="src" value="http://5.135.159.233/cakebox/downloads/'.$sfile.'" />
	            <embed type="video/divx" src="http://5.135.159.233/cakebox/downloads/'.$sfile.'"
	                   width="640" height="360"
	                   pluginspage="http://go.divx.com/plugin/download/">
	            </embed>
				</object>
				</center>
				</div>
           </div>';
		}
else { header ("Location: index.php"); }
}

else { header ("Location: index.php"); }



}
//////
else { header ("Location: index.php"); }

}
//Sinon
else { header ("Location: login.php"); }

include("footer.php");
?>