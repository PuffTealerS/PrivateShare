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

//Si déjà identifié 
if (isset($_SESSION['id']) && isset($_SESSION['username'])) { header ("Location: index.php"); }

elseif($register==1) {

echo '<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <title>Sign up</title>
    <link href="css/signin.css" rel="stylesheet">
    <link href="css/signin2.css" rel="stylesheet">
 </head>

  <body>

    <div class="container">';
	
//Si formulaire non remplis
if (empty($_POST['formsent'])) {

echo '
	<form method="post" action="register.php" class="form-signin">
        <input type="text" name="username" class="form-control" placeholder="Username" autofocus>
		<input type="email" name="email" class="form-control" placeholder="Email" autofocus>
        <input type="password" name="password" class="form-control" placeholder="Password">
		<input type="password" name="password_verification" class="form-control" placeholder="Password verification">
        <input type="submit" class="btn btn-lg btn-primary btn-block" name="formsent" value="Sign up" />
      </form>'; }

else {
//Variables
$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : null;
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;
$password_verif = isset($_POST['password_verification']) ? $_POST['password_verification'] : null;
$i = 0;

	
//Check empty email
if(empty($email)) {
echo '<div class="alert alert-dismissable alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Email missing!</strong> Please enter a mail address.
            </div>'; 
$i++; }

//Check empty password/password_verif
if(empty($password) OR empty($password_verif)) {
echo '<div class="alert alert-dismissable alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Case missing!</strong> Please check your password and password verification case.
            </div>'; 
$i++;}


//Check username size
$usercheck = strlen($username);
if($usercheck < 5 || $usercheck > 30){
echo '<div class="alert alert-dismissable alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Password error!</strong> Your password need to be into 5 and 30 chars.
            </div>'; 
$i++; }


//Check if username is already in DB
$query=$bdd->prepare('SELECT COUNT(*) AS nbr FROM users WHERE username =:username');
$query->bindValue(':username',$username, PDO::PARAM_STR);
$query->execute();
$pseudo_free=($query->fetchColumn()==0)?1:0;
$query->CloseCursor();

    if(!$pseudo_free)
    {
        echo '<div class="alert alert-dismissable alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Username error!</strong> This username is already in use.
            </div>';
        $i++;
    }

//Check email format
if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
echo 'Adresse email incorecte !<br />';
$i++; }

//Check if email is already in DB
$query=$bdd->prepare('SELECT COUNT(*) AS nbr FROM users WHERE email =:email');
$query->bindValue(':email',$email, PDO::PARAM_STR);
$query->execute();
$email_free=($query->fetchColumn()==0)?1:0;
$query->CloseCursor();

    if(!$email_free)
    {
        echo '<div class="alert alert-dismissable alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Email error!</strong> This email is already in use.
            </div>';
        $i++;
    }
	
//Check Password

if($password != $password_verif) {
echo '<div class="alert alert-dismissable alert-warning">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Password error!</strong> Your password and password verification are different.
            </div>';
$i++; }



// Si tous est ok
if ($i==0) {
// Hachage du mot de passe
$pass_hache = sha1($password);
//$cle = md5(microtime(TRUE)*100000);

$rank = 2;
 
// Insertion SQL
$req = $bdd->prepare('INSERT INTO users(username, rank, password, email, actived) VALUES(:username, :rank, :password, :email, 1)');
$req->execute(array('username' => $username, 'rank' => $rank, 'password' => $pass_hache, 'email' => $email));
	
	//Envoye email activation
	/*$to = ''.$email.''; 
	$subject = 'Bienvenue sur '.$sitename.' - Actication n&eacute;cessaire !';
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
 
	$mail = mail($to, $subject, $message, $entete); //marche

	if(!$mail) { echo 'Impossible d\'envoyer l\'email, merci de contacter l\administrateur.<br />'; }*/

	header ("Refresh: 1;URL=login.php");
	echo '<div class="alert alert-dismissable alert-success">
              <button type="button" class="close" data-dismiss="alert">&times;</button>
              <strong>Successfully sign up!</strong> You will be redirect soon.
            </div>';
	}	
}

}

else { header ("Location: index.php"); }

echo '</div> 
  </body>
</html>';
?>