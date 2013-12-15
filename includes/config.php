<?php

// --- Merci � Seriesme !
//


try
{
	// --- Connexion BDD
	// --- Exemple : $bdd = new PDO('mysql:host=localhost;dbname=privateshare', 'root', '123456');

// --- A modifier

$bdd = new PDO();


$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}



// --- Files Directory : Les valeurs sont des exemples !
// --- A modifier

$numberurl = 0; /* D�finir le nombre de baseurl (Attention, on par de 0; si vous en avez 3 vous devez �crire 2!) */
$baseurl[0] = '/var/www/cakebox/downloads/'; /* Chemin du dossier de t�l�chargement | Ne pas oublier le / !!! */
//$baseurl[1] = 'var/www/cakebox/downloads/';
//$baseurl2 = '';
//$baseurl3 = '';


// --- Config : Les valeurs sont des exemples !
// --- A modififer

//Nom du site web 
$sitename = 'Private Share'; 
//URL du site web | Ne pas oublier le / � la fin !
$siteurl = 'http://localhost/PrivateShare/'; 
//Adresse email d'envoi
$siteemail = 'admin@admin.fr';
//Directory URL ex: http://mysite/test => you setup like this  $urldirectory = 'test/'; 
$urldirectory = 'PrivateShare/'; 



// --- Register
$register = 1; // 1 => Inscription ouverte | 0 => Inscription ferm�e

// --- Log ip
$logip = 1; // 0 => Pas de log d'ip | 1 => Log des ips !
?>