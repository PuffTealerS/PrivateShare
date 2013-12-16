<?php
// Configuration : PrivateShare
// Merci � seriasme
//______________________________________________________________________



// --- Connexion � la bdd
// --- Modifiez la valeur $bdd
//	   Exemple : $bdd = new PDO('mysql:host=localhost;dbname=privateshare', 'root', '123456');
try {

	$bdd = new PDO();
	$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);

} catch (Exception $e) {
        
        die('Erreur : ' . $e->getMessage());
        
}

// --- Repertoire contenant les fichiers (les valeurs sont des exemples)
//
$numberurl = 0; //D�finir le nombre de baseurl (Attention, on par de 0; si vous en avez 3 vous devez �crire 2!)
$baseurl[0] = '/var/www/cakebox/downloads/'; //Chemin du dossier de t�l�chargement | Ne pas oublier le / !!!
//$baseurl[1] = '';
//$baseurl[2] = '';
//$baseurl[3] = '';

// --- Nom du site web 
//
$sitename = 'Private Share'; 

// --- URL du site web | Ne pas oublier le / � la fin !
//
$siteurl = 'http://localhost/PrivateShare/'; 

// --- Adresse email d'envoi
//
$siteemail = ''; 

// --- Directory URL ex: http://mysite/test => vous installez comme ceci $urldirectory = 'test/';
//
$urldirectory = 'PrivateShare/'; 

// --- Inscription
//
$register = 1; // 1 => Inscription ouverte | 0 => Inscription ferm�

// --- LogIP
//
$logip = 1; // 0 => Pas de log d'ip | 1 => Log des ips !
?>