<?php
// Configuration : PrivateShare
// Merci à seriasme
//______________________________________________________________________



// --- Connexion à la bdd
// --- Modifiez la valeur $bdd
//	   Exemple : $bdd = new PDO('mysql:host=localhost;dbname=privateshare', 'root', '123456');
try
{
$bdd = new PDO();
$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

// --- Repertoire contenant les fichiers (les valeurs sont des exemples)
//
$numberurl = 0; //Définir le nombre de baseurl (Attention, on par de 0; si vous en avez 3 vous devez écrire 2!)
$baseurl[0] = '/var/www/cakebox/downloads/'; //Chemin du dossier de téléchargement | Ne pas oublier le / !!!
//$baseurl[1] = '';
//$baseurl2 = '';
//$baseurl3 = '';


// --- Configuration (les valeurs sont des exemples)
//
$sitename = 'Private Share'; //Nom du site web 
$siteurl = 'http://localhost/PrivateShare/'; //URL du site web | Ne pas oublier le / à la fin !
$siteemail = ''; //Addresse email d'envoie
$urldirectory = 'PrivateShare2/'; //Directory URL ex: http://mysite/test => you setup like this  $urldirectory = 'test/';

// --- Inscription
//
$register = 1; // 1 => Inscription ouverte | 0 => Inscription fermé

// --- LogIP
//
$logip = 1; // 0 => Pas de log d'ip | 1 => Log des ips !
?>