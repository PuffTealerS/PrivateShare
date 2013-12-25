<script text="javascript">


function switchDiv()
{
divInfo = document.getElementById('divprincipal');
if (divInfo.style.display == 'none')
divInfo.style.display = 'block';
else
divInfo.style.display = 'none';
}
</script>

<?php


error_reporting(E_ALL);
session_start();
include("includes/config.php");
include("includes/functions.php");
include("header.php");
include ("menu.php");

// --- Si deja connecte
//
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {


	$sqlavatar = $bdd->prepare('SELECT * FROM users WHERE id = :id');
	$sqlavatar->execute(array(':id' => $_SESSION['id']));
	$sqlav = $sqlavatar->fetch();
//	echo $sqlav['avatar'];

// <input type="submit" name="submit" value="Envoyer">
	echo '
	<center>
	<div id="profilborder">
		<form action="validprofil.php" method="post" enctype="multipart/form-data">

			<h2>Aperçu du compte</h2>
			</br>
			<img src="img/avatar/'.$sqlav['avatar'].'">
			</br>
			</br>
			<p>Utilisateur: '.$sqlav['username'].'</p>
			<p>Mail: '.$sqlav['email'].'</p>
			
			<div id="titre">
				<button type="button" onclick="switchDiv()">éditer informations personnelles</button>

					<div id="divprincipal" >
						</br>
						<div id="modif_avatar">
						<p id="titrecompte">Modifier avatar</p>
						<label for="file"></label>
						<input type="file" name="file" id="file"/></br>
						</div>
						</br>
						<div id="modif_password" >
						<p id="titrecompte">Modifier mot de passe</p>
						<p id="label">Ancien mot de passe:</p>
						<input type="password" name="oldp">
						</br>
						<p id="label">Nouveau mot de passe:</p>
						<input type="password" name="newp1">
						</br>
						<p id="label">Répéter nouveau mot de passe:</p>
						<input type="password" name="newp2">
						</div>
						</br>
						<div id="modif_mail">
						<p id="titrecompte">Modifier Mail</p>
						<p id="label">Nouveau mail:</p>
						<input type="text" name="newm"><br>
						</div>
						</br>
						<input type="submit" name="submit" value="Enregistrer les modifications">

					</div>
			</div>

			
			</br>
	</form>
	</div>
	</center>
	';

}
?>