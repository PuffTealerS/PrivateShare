<?php

error_reporting(E_ALL);
session_start();
include("includes/config.php");
include("includes/functions.php");
include("header.php");
//Si déjà identifié + Administrateur 

if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['rank']==1) {
$log = isset($_GET['log']) ? intval($_GET['log']) : null;
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;

		
		

//echo '<center><h1>Changelog - PrivateShare</h1></center>';
//<!-- Formulaire d'ajout de changelog -->
//<!-- NE PAS OUBLIER D'ENLEVER LES BALISES CENTER ET DE FAIRE LA MODIFICATION DE CENTRE EN CSS -->

$sql = $bdd->prepare('SELECT DISTINCT version FROM changelog');
$sql -> execute();
$n = 0;

while($test = $sql->fetch()) {
	
	$tab_version[$n] = $test['version'];
	$n++;
}

$tailletab = count($tab_version);

// --- Taille du tableau 


echo '

		<center><h1>Changelog - PrivateShare</h1></center>
		
		<div id="formulaire_changelog">
			<center>
			<form action="form.php" method="post">
				<table width="600" height="300">
					<tr>
						<td><center>Version: </br><input name="version" type="text" name="version"></center></td>
						<td><center>';
						echo '<select name="listeversion">
							<option selected>Modification de version</option>';

							for ($i=0; $i<$tailletab; $i++) {

									echo '<option>'.$tab_version[$i].'</option>';

							} 

					echo '</select></center></td> 
					</tr>
					<tr>
						<td colspan=2><center>Commentaire: </br><TEXTAREA name="nom" rows=4 cols=40></TEXTAREA></br></center></td>

					</tr>
					<tr>
						<td colspan=2><center><input type="submit"></center></td>
					</tr>
				</table>
				
			</form>
			</center>
		</div>';
	}
	else { header ("Location: index.php"); }
?>