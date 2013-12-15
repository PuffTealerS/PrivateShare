<?php

error_reporting(E_ALL);
session_start();

include("includes/config.php");
include("includes/functions.php");
header ("Refresh: 2;URL=index.php");
// --- Si deja identifie 
if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['rank']==1) {
$log = isset($_GET['log']) ? intval($_GET['log']) : null;
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;
include("header.php");

// --- Recuperer la date du jour, la version et le commentaire

$datetime= date ("Y-m-d H:i:s");
$comment = $_POST[nom];
$listversion = $_POST[listeversion];

// --- Ajout d'une nouvelle version ou modification d'une version


if ($listversion !='Modification de version') {

	$version = $_POST[listeversion];
	echo '<center><div id="newversion">
	<p>Modifcation de la version: '.$version.'</p>
	<p>Ajout du changelog: '.$comment.'</p></div></center>';


} else {

	$version = $_POST[version];
	echo '<div id="newversion">
	<center><p>Ajout de la nouvelle version: '.$version.'</p>
	<p id="commentnew"> '.$comment.'</p></div></center>';

}
	
	$testsql=$bdd->prepare("INSERT INTO changelog (version, datelog, commentaire) VALUES (?, ?, ?)");
	$testsql->bindParam(1, $version);
	$testsql->bindParam(2, $datetime);
	$testsql->bindParam(3, $comment);
	$testsql->execute();

// --- Ajout dans la base de donnee




	/*echo 'dans le else';
	$version = $_POST[listeversion];
	$sql=$bdd->prepare("SELECT commentaire FROM  changelog where version='".$version."'");
	$sql->execute();
	while($test = $sql->fetch()) {
		echo 'couc';
		echo $test[commentaire];
	$testsql=$bdd->prepare("UPDATE changelog SET commentaire='".$test[commentaire].$comment."' WHERE version='".$version."'");
	$testsql->execute();
	}*/
	}
	else { header ("Location: index.php"); }
?>


