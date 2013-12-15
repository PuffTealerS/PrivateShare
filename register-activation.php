<?php
//										   //
//WANT TO MAKE A DONATION FOR THIS SCRIPT ?//
//      BTC DONATION AVAILABLE : 		   //
//   1AsQK6bKFLexyKQ3naXMUnGyDeDXvN4563    //
//
//Author : @Seriesme
//
session_start();
include("includes/config.php");
include("includes/functions.php");
include("header.php");

//Si déjà identifié on le redirige
if (!empty($_SESSION['id']) AND !empty($_SESSION['username'])) { header ("Location: index.php"); }


if(!empty($_GET['log']) && !empty($_GET['code'])) {

$login = isset($_GET['log']) ? $_GET['log'] : null;
$code = isset($_GET['code']) ? $_GET['code'] : null;

//On vérifie que le compte n'est pas déjà validé
$sqlact = $bdd->prepare('SELECT actived FROM users WHERE username = :username');
$sqlact->execute(array('username' => $login)) or die;

$activedornot = $sqlact->fetch();

if($activedornot['actived']==0) {

//On récupere le code d'activation
$sql = $bdd->prepare('SELECT code_activation FROM users WHERE username = :username');
$sql->execute(array('username' => $login)) or die;

$code_activation = $sql->fetch();

//On vérifie que le code est bon
if($code==$code_activation['code_activation']) {

$actived = 1;
$delcode = NULL;

//On valide son compte puis on vide le champ code_activation
$sqlupdate = $bdd->prepare('UPDATE users SET actived = :actived, code_activation = :delcode WHERE username = :username');

$sqlupdate->execute(array('actived' => $actived, 'delcode' => $delcode, 'username' => $login));


echo '<div id="content-form">Votre compte viens d\'&eacute;tre activ&eacute; !<br />Redirection en cours ...</div>';
header ("Refresh: 3;URL=login.php"); }

else { header ("Refresh: 0;URL=index.php"); }
}

else { echo '<div id="content-form">Votre compte est deja valide</div>'; }

}

//Nouveau email de validation
elseif(!empty($_GET['action'])) {
	
	$action = isset($_GET['action']) ? $_GET['action'] : null;

	if($action == 'newvalidation' && empty($_POST['vform'])) {
	
	echo'<div id="content-form">Veuillez rentrer votre adresse email :<br /><br />
		 <form method="post" action="register-activation.php?action=newvalidation"><p>
		 Adresse mail : <input type="email" name="email" /><br />
		 <input type="submit" name="vform" value="valider" />
		 </p></form></div>'; }
		 
		
		if(!empty($_POST['vform'])) {
		
		$email = isset($_POST['email']) ? $_POST['email'] : null;
		
			//On vérifie que le compte n'est pas déjà validé + get username
			$sqlact2 = $bdd->prepare('SELECT * FROM users WHERE email = :email');
			$sqlact2->execute(array('email' => $email)) or die;

			$sql2 = $sqlact2->fetch();

			//Si le compte est bien non validé
			if($sql2['actived']==0) {
			
			$cle2 = md5(microtime(TRUE)*100000);
			
			//Update clé d'activation dans la SQL
			$req = $bdd->prepare('UPDATE users SET code_activation = :code_activation WHERE email = :email');

			$req->execute(array('code_activation' => $cle2, 'email' => $email));

			
				//Envoye email activation
				$to = ''.$email.''; 
				$subject = 'Bienvenue sur '.$sitename.' - Actication nécessaire !';
				$message = '<html>
							<head>
							<title>Bienvenue sur '.$sitename.'</title>
							</head>
							<body>
							<p>Vous devez maintenant valider votre compte<br /><br /><a href="'.$siteurl.'register-activation.php?log='.urlencode($username).'&code='.urlencode($cle).'">ICI</a></p>
							</body>
							</html>';
 
				$entete  = 'MIME-Version: 1.0' . "\r\n";
				$entete .= 'Content-type: text/html; charset=utf-8' . "\r\n";
 
				// En-têtes additionnels
				$entete .= 'From: "'.$sitename.'" <'.$siteemail.'>' . "\r\n";
				$entete .= 'Reply-To: "Admin" <'.$siteemail.'>' . "\r\n";
 
				$mail = mail($to, $subject, $message, $entete); //envoie email

				if(!$mail) { echo '<div id="content-form">Impossible d\envoyer l\'email, merci de contacter l\administrateur.<br /></div>'; }
				
				if($mail) { echo '<div id="content-form">Nous venons de vous envoyer un code d\'activation!</div>'; }
				}
				
				else { echo '<div id="content-form">Votre compte est deja valide !</div>'; }
}

}

else { 
header ("Location: index.php"); }



include("footer.php");
?>