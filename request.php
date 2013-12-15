<?php 
//					//
//Author : @Seriesme//
//					//
session_start();
include("includes/config.php");
include("includes/functions.php");

//Si déjà identifié
if (!empty($_SESSION['id']) && !empty($_SESSION['username'])) {


//					//
//VUS ADMINISTRATEUR//
// 					//
$sqladm = $bdd->prepare('SELECT rank FROM users WHERE id = :id');
$sqladm->execute(array('id' => $_SESSION['id']));
$sqlr = $sqladm->fetch();

//Si administrateur
if($_SESSION['rank']==1) {

	$rtid = isset($_GET['rtid']) ? intval($_GET['rtid']) : null;
	$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;

	//Vus dans une requéte
	if(!empty($_GET['uid']) && !empty($_GET['tid']) || !empty($_GET['atid'])) {
	
	$tid = isset($_GET['tid']) ? intval($_GET['tid']) : null;
	$uid = isset($_GET['uid']) ? intval($_GET['uid']) : null;
	
	include("header.php");
	include("menu.php");
	
	//Si il répond a une requête
	if(!empty($_POST['ticketreply']) && !empty($_GET['atid'])) {
	
	
		$tid = isset($_GET['atid']) ? intval($_GET['atid']) : 0;
		$ouid = isset($_GET['ouid']) ? intval($_GET['ouid']) : 0;
		$fmessage = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
		$date=date("d-m-y");
		$time=date("H:i");
		
		$message='Message de '.$_SESSION['username'].' le '.$date.' à '.$time.' :<br /><br />'.$fmessage.'';
		
		//Insert sql message
		$req = $bdd->prepare('INSERT INTO request(uid, tid, message) VALUES(:uid, :tid, :message)');
		$req->execute(array('uid' => $ouid, 'tid' => $tid, 'message' => $message));
		
		//Update status
		$status=1;
		$update = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
		$update->execute(array('status' => $status, 'uid' => $ouid, 'tid' => $tid));
		
		
		echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Message envoyé</u>
						</div>
						</div>
			</div>';
		echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php")\',2000);</script>';
	
	}
	
	//Sinon on affiche la requête
	else{

	//Récupere titre + status 
	$sqltitle = $bdd->prepare('SELECT titre, status FROM request_title WHERE uid = :uid AND tid = :tid');
	$sqltitle->execute(array('uid' => $uid, 'tid' => $tid));
	$stitle = $sqltitle->fetch() or die (header ('refresh: 2; url=request.php') . 'Aucun ticket n\'est trouvé ! Redirection en cours ...');
	
	//On affiche le titre + status
	echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<br />';
	if($stitle['status']==0) { echo'<span class="label label-success">Requête ouverte</span>'; } 
		if($stitle['status']==3) { echo'<span class="label label-info">Nouveau message</span>'; }
	
	echo '<br /><br /><form method="post" action="request.php?atid='.$tid.'&ouid='.$uid.'">
		  <p>
		  <input class="form-control" type="text" name="titre" size="100" placeholder="'.$stitle['titre'].'" style="width:450px;" />
		  <br /><br />';

	//Récupere message
	$sqlmsg = $bdd->prepare('SELECT id, message FROM request WHERE uid = :uid AND tid = :tid ORDER BY id DESC');
	$sqlmsg->execute(array(':uid' => $uid, ':tid' => $tid));
	
		//On affiche les messages
		while($stid = $sqlmsg->fetch()) {
			echo ''.$stid['message'].' <br /><hr width="37%" />';
		}
		
		
			//On créer un nouveau message en fonction du status
			if($stitle['status']!=2) {echo '<br /><label for="message">Répondre à la requête :</label></br />
				<textarea class="form-control" name="message" id="message" rows="10" cols="110" style="width:450px;">Veuillez taper votre message ici.</textarea><br />
				
				<input class="btn btn-primary" type="submit" name="ticketreply" value="Envoyer" />
				</p>
				</form><br /><span class="label label-danger"><a href="request.php?action=close&rtid='.$tid.'&cuid='.$uid.'"><b>Fermer la requête !</b></a></span><br /><br />';}
				
				else{echo'<br /><br />';}
				
			echo'</center></div>
			</div>';
		}
	
	
	}
	
	//
	//FIN VUS DANS UNE REQUETE
	//
	
	

	//On récupere les requétes ouverte
	$sqltid = $bdd->prepare('SELECT id, uid, tid, titre, status FROM request_title WHERE status = :status OR status = :status1 OR status = :status2 ORDER BY id DESC');
	$sqltid->execute(array('status' => '0', 'status1' => '1', 'status2' => '3'));
	
	if(empty($_POST['ticketreply']) && empty($_GET['tid']) && empty($_GET['action'])) {
	
		include("header.php");
		include("menu.php");
		
			echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;tes</h3>
              </div>
              <div class="panel-body">
			<br />Requêtes ouvertes :<br /><br />';
	
			while($tick1 = $sqltid->fetch()) {
				if($tick1['status']==0) { echo'<span class="label label-success"><a href="request.php?uid='.$tick1['uid'].'&tid='.$tick1['tid'].'">'.$tick1['titre'].'</a></span><br /><br />'; } 
				if($tick1['status']==1) { echo'<span class="label label-success"><a href="request.php?uid='.$tick1['uid'].'&tid='.$tick1['tid'].'">'.$tick1['titre'].'</a></span><br /><br />'; } 
				if($tick1['status']==3) { echo'<span class="label label-info"><a href="request.php?uid='.$tick1['uid'].'&tid='.$tick1['tid'].'">'.$tick1['titre'].'</a></span><br /><br />'; }
											}
									
				echo '</div>
							</div>';
						}

		// on récupére les requêtes fermées

		$sqltid_f = $bdd->prepare('SELECT id, uid, tid, titre, status FROM request_title WHERE status = :status OR status = :status1 OR status = :status2 ORDER BY id DESC');
		$sqltid->execute(array('status' => '2', 'status1' => '1','status2' => '2'));
	
	if(empty($_POST['ticketreply']) && empty($_GET['tid']) && empty($_GET['action'])) {
	
		include("header.php");
		include("menu.php");
		
			echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;tes</h3>
              </div>
              <div class="panel-body">
			<br />Requêtes fermées :<br /><br />';
	
			while($tick1 = $sqltid->fetch()) {
				
				if($tick1['status']==2) { echo'<span class="label label-warning"><a href="request.php?uid='.$tick1['uid'].'&tid='.$tick1['tid'].'">'.$tick1['titre'].'</a></span><br /><br />'; }

											}
									
				echo '</div>
							</div>';
						}

						
			
		//
		//ON REOUVRE LE TICKET
		//
		elseif($action=='reopen' && !empty($rtid) && !empty($_GET['cuid'])) {
		
		$uid = isset($_GET['cuid']) ? intval($_GET['cuid']) : null;

		//On vérifie le tid
		$sqltid = $bdd->prepare('SELECT tid, titre FROM request_title WHERE uid = :uid AND tid = :tid');
		$sqltid->execute(array('uid' => $uid, 'tid' => $rtid));
		$stid = $sqltid->fetch() or die (header ('refresh: 2; url=request.php') . 'Aucun ticket n\'est trouvé ! Redirection en cours ...');

		//On modifie le status du ticket
		$status=0;
		$sqlstatus = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
		$sqlstatus->execute(array('status' => $status, 'uid' => $uid, 'tid' => $rtid));

		include("header.php");
		include("menu.php");
		echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stid['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Ticket réouvert</u>
						</div>
						</div>
			</div>';
		echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php?tid='.$rtid.'")\',2000);</script></center></div>';
		}

 
 
		//
		//ON FERME LE TICKET
		// 
		elseif($action=='close' && !empty($rtid) && !empty($_GET['cuid'])) {
		
		$uid = isset($_GET['cuid']) ? intval($_GET['cuid']) : null;

		//On vérifie le tid
		$sqltid = $bdd->prepare('SELECT tid, titre FROM request_title WHERE uid = :uid AND tid = :tid');
		$sqltid->execute(array('uid' => $uid, 'tid' => $rtid));
		$stid = $sqltid->fetch() or die (header ('refresh: 2; url=request.php') . 'Aucun ticket n\'est trouvé ! Redirection en cours ...');

		//On modifie le status du ticket
		$status=2;
		$sqlstatus = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
		$sqlstatus->execute(array('status' => $status, 'uid' => $uid, 'tid' => $rtid));

		include("header.php");
		include("menu.php");
		echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stid['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Ticket fermé</u>
						</div>
						</div>
			</div>';
		echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php")\',2000);</script></center></div>';
		}
		
}

//
//FIN DE LA VUS ADMINISTRATEUR
//

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : null;
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;
$rtid = isset($_GET['rtid']) ? intval($_GET['rtid']) : null;


//
//ON LISTE LES TICKETS
//
if (empty($tid) && empty($_POST['formsupport']) && empty($action) && $sqlr['rank']!=1) {

//On récupere son ticket tid
$sqltid = $bdd->prepare('SELECT tid FROM request_title WHERE uid = :uid');
$sqltid->execute(array('uid' => $_SESSION['id']));

$ftid = $sqltid->fetch();
include("header.php");
include("menu.php");

echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Mes requ&ecirc;te</h3>
              </div>
              <div class="panel-body">
			  <center>';
			  
//Si ticket déjà crée
if($ftid['tid']>0) {

	$sqltick = $bdd->prepare('SELECT id, tid, titre, status FROM request_title WHERE uid = :uid ORDER BY id DESC');
	$sqltick->execute(array('uid' => $_SESSION['id']));
	
	echo '<br />Mes Requêtes :<br /><br />';
	
	while($tick = $sqltick->fetch()) {
		if($tick['status']==0) { echo'<span class="label label-success"><a href="request.php?tid='.$tick['tid'].'">'.$tick['titre'].'</a></span><br /><br />'; } 
		if($tick['status']==1) { echo'<span class="label label-info"><a href="request.php?tid='.$tick['tid'].'">'.$tick['titre'].'</a></span><br /><br />'; }
		if($tick['status']==2) { echo'<span class="label label-danger"><a href="request.php?tid='.$tick['tid'].'">'.$tick['titre'].'</a></span><br /><br />'; }
	}

		}
		
if($sqlr['rank']!=1) {
echo '<br /><hr width="40%" /><br />
<label class="control-label" >Créer une nouvelle requête :</label>
<form method="post" action="request.php">
<input class="form-control" type="text" name="titre" size="100" placeholder="Nom du film/series + langue" style="width:450px;"/>
<br /><br />
<label class="control-label">Description :</label><br />
<textarea class="form-control" name="message" id="message" rows="10" cols="110" style="width:450px;">
Veuillez donner des informations supplémentaires sur votre requête.
</textarea><br />
<input class="btn btn-primary" type="submit" name="formsupport" value="Envoyer" />

</form>';  }		


echo '</center></div>
            </div>';
		}//FIN




//
//VUS DANS UN TICKET
//
elseif (!empty($tid) && $sqlr['rank']!=1) {
	include("header.php");
	include("menu.php");
	
	
	//Si il répond au ticket
	if(!empty($_POST['ticketreply']) && !empty($_GET['tid'])) {
	
		$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
		$fmessage = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;
		$date=date("d-m-y");
		$time=date("H:i");
		
		$message='Message de '.$_SESSION['username'].' le '.$date.' à '.$time.' :<br /><br />'.$fmessage.'';
		
		//Insert sql message
		$req = $bdd->prepare('INSERT INTO request(uid, tid, message) VALUES(:uid, :tid, :message)');
		$req->execute(array('uid' => $_SESSION['id'], 'tid' => $tid, 'message' => $message));
		
		//Update status
		$status=3;
		$update = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
		$update->execute(array('status' => $status, 'uid' => $_SESSION['id'], 'tid' => $tid));
		
		echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Message envoyé</u>
						</div>
						</div>
			</div>';
		echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php")\',2000);</script>';
	
	}
	
	//On affiche le ticket
	else{

	//Récupere titre + status 
	$sqltitle = $bdd->prepare('SELECT titre, status FROM request_title WHERE uid = :uid AND tid = :tid');
	$sqltitle->execute(array('uid' => $_SESSION['id'], 'tid' => $tid));
	$stitle = $sqltitle->fetch() or die (header ('refresh: 2; url=request.php') . 'Aucun ticket n\'est trouvé ! Redirection en cours ...');
	
	//On affiche le titre + status
	echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Ma requ&ecirc;te: '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>';
	if($stitle['status']==0) { echo'<span class="label label-success">Requête ouverte</span>'; } 
		if($stitle['status']==1) { echo'<span class="label label-info">Nouveau message</span>'; }
		if($stitle['status']==2) { echo'<span class="label label-danger">Requête fermé!</span><br /><br /><span class="label label-success"><a href="request.php?action=reopen&rtid='.$tid.'">Vous pouvez la ré-ouvrir en cliquant ici !</a></span>'; }
	
	echo '<br /><br /><form method="post" action="request.php?tid='.$tid.'">
		  <input class="form-control" type="text" name="titre" size="100" placeholder="'.$stitle['titre'].'" style="width:450px;" />
		  <br /><br />';

	//Récupere message
	$sqlmsg = $bdd->prepare('SELECT id, message FROM request WHERE uid = :uid AND tid = :tid ORDER BY id DESC');
	$sqlmsg->execute(array(':uid' => $_SESSION['id'], ':tid' => $tid));
	
		//On affiche les messages
		while($stid = $sqlmsg->fetch()) {
			echo ''.$stid['message'].' <br /><hr width="37%" />';
		}
		
		
			//On créer un nouveau message en fonction du status
			if($stitle['status']!=2) {echo '<br /><label class="control-label" for="message">Répondre à la requête :</label></br />
				<textarea class="form-control" name="message" id="message" rows="10" cols="110" style="width:450px;">Veuillez taper votre message ici.</textarea><br />
				<input class="btn btn-primary" type="submit" name="ticketreply" value="Envoyer" />
				</form><br /><span class="label label-danger"><a href="request.php?action=close&rtid='.$tid.'"><b>Fermer la requête !</b></a></span><br /><br />';}
				
				else{echo'<br /><br />';}
				
				//On update le status
				$sqlupstatus = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
				$sqlupstatus->execute(array(':status' => '0', ':uid' => $_SESSION['id'], ':tid' => $tid));
				}
		
		}
		
		
		
//		
//NOUVELLE REQUETE
//
elseif (!empty($_POST['formsupport']) && $sqlr['rank']!=1) {
$titre = isset($_POST['titre']) ? htmlspecialchars($_POST['titre']) : null;
$fmessage = isset($_POST['message']) ? htmlspecialchars($_POST['message']) : null;

$date=date("d-m-y");
$time=date("H:i");


$message='Message de '.$_SESSION['username'].' le '.$date.' à '.$time.' :<br /><br />'.$fmessage.'';

//Récupere tid
$sqltid2 = $bdd->prepare('SELECT tid FROM request_title WHERE uid = :uid');
$sqltid2->execute(array(':uid' => $_SESSION['id']));
$tid0 = $sqltid2->fetch();

$status = 0;
$count = 0;

//Si tid = 0
if($tid0['tid']==0) { $count++; }

else {
$sqltid = $bdd->prepare('SELECT tid FROM request_title WHERE uid = :uid');
$sqltid->execute(array(':uid' => $_SESSION['id']));

//Incrémente tid
while($stid = $sqltid->fetch()) { $count++; }
$count++;
}


//Insert sql request_title
$req = $bdd->prepare('INSERT INTO request_title(uid, tid, titre, status) VALUES(:uid, :tid, :titre, :status)');
$req->bindParam('uid', $_SESSION['id'], PDO::PARAM_INT);
$req->bindParam('tid', $count, PDO::PARAM_INT);
$req->bindParam('titre', $titre, PDO::PARAM_STR);
$req->bindParam('status', $status, PDO::PARAM_INT);
$req->execute();

//Insert sql message
$req = $bdd->prepare('INSERT INTO request(uid, tid, message) VALUES(:uid, :tid, :message)');
$req->bindParam('uid', $_SESSION['id'], PDO::PARAM_INT);
$req->bindParam('tid', $count, PDO::PARAM_INT);
$req->bindParam('message', $message, PDO::PARAM_STR);
$req->execute();

header ('refresh: 2; url=request.php');
include("header.php");
include("menu.php");
echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Nouveau ticket crée </u>
						</div>
						</div>
			</div>';
		echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php")\',2000);</script>';



}



//
//ON REOUVRE LE TICKET
//
elseif($action=='reopen' && !empty($rtid) && $sqlr['rank']!=1) {

//On vérifie le tid
$sqltid = $bdd->prepare('SELECT tid FROM request_title WHERE uid = :uid AND tid = :tid');
$sqltid->execute(array('uid' => $_SESSION['id'], 'tid' => $rtid));
$stid = $sqltid->fetch() or die (header ('refresh: 2; url=request.php') . 'Aucun ticket n\'est trouvé ! Redirection en cours ...');

//On modifie le status du ticket
$status=0;
$sqlstatus = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
$sqlstatus->execute(array('status' => $status, 'uid' => $_SESSION['id'], 'tid' => $rtid));

include("header.php");
include("menu.php");
echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Ticket réouvert</u>
						</div>
						</div>
			</div>';
echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php?tid='.$rtid.'")\',2000);</script></center></div>';
   }

 
 
//
//ON FERME LE TICKET
// 
elseif($action=='close' && !empty($rtid) && $sqlr['rank']!=1) {

//On vérifie le tid
$sqltid = $bdd->prepare('SELECT tid FROM request_title WHERE uid = :uid AND tid = :tid');
$sqltid->execute(array('uid' => $_SESSION['id'], 'tid' => $rtid));
$stid = $sqltid->fetch() or die (header ('refresh: 2; url=request.php') . 'Aucun ticket n\'est trouvé ! Redirection en cours ...');

//On modifie le status du ticket
$status=2;
$sqlstatus = $bdd->prepare('UPDATE request_title SET status = :status WHERE uid = :uid AND tid = :tid');
$sqlstatus->execute(array('status' => $status, 'uid' => $_SESSION['id'], 'tid' => $rtid));

include("header.php");
include("menu.php");
echo '<div class="panel panel-warning">
              <div class="panel-heading">
                <h3 class="panel-title">Requ&ecirc;te '.$stitle['titre'].'</h3>
              </div>
              <div class="panel-body">
			  <center>
			<div class="alert alert-dismissable alert-success">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Perfect!</strong> <u>Ticket fermé</u>
						</div>
						</div>
			</div>';
echo '<script type=\'text/javascript\'>setTimeout(\'window.location.replace("request.php")\',2000);</script></center></div>';
   }
 

 
}
//Sinon non identifié
else { header ("Location: login.php"); }

echo'</center>';
include("footer.php");
?>