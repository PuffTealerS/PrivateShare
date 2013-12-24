<?php
//										   //
//WANT TO MAKE A DONATION FOR THIS SCRIPT ?//
//      BTC DONATION AVAILABLE : 		   //
//   1AsQK6bKFLexyKQ3naXMUnGyDeDXvN4563    //
//
//Author : @Seriesme
//
//<meta http-equiv="refresh" content="10;index.php" />

function poids($rep)
{
    $r = opendir($rep);
    while( $dir=readdir($r) )
    {
        if( !in_array($dir, array("..", ".")) )
        {
            if( is_dir("$rep/$dir") )
            {
                $t += poids("$rep/$dir");
            }
            else
            {
                $t += filesize("$rep/$dir");
            }
        }
    }
    closedir($r);
    return $t;
}

function unite($valeur)
{
    if( $valeur >= pow(1024, 3) )
    {
        $valeur = round( $valeur / pow(1024, 3), 2);
        return $valeur; //'go';
    }
    elseif( $valeur >=  pow(1024, 2) )
    {
        $valeur = round( $valeur / pow(1024, 2), 2);
        return $valeur; //'mo';
    }
    else
    {
        $valeur = round( $valeur / 1024, 2);
        return $valeur; //' ko';
    }
}


//header('Refresh: 5; url=index.php'); 
error_reporting(E_ALL);
include("includes/config.php");
include("includes/functions.php");
include("header.php");
include("menu.php");

// --- On vide les tables
$delete=$bdd->prepare('TRUNCATE TABLE list');
$delete->execute();


$count=0;		

for($count=0;$count<=$numberurl;$count++)
{
if($dossier = opendir(''.$baseurl[$count].'')) {



	while(false !== ($fichier = readdir($dossier))) {

			if($fichier != '.' && $fichier != '..' && $fichier != 'index.php' && $fichier != '.session' && $fichier != '.htaccess' && $fichier != '.htpasswd') {
				
				$url=''.$baseurl[$count].''.$fichier.'';
				$datetime= date ("Y-m-d H:i:s", filemtime($url));
				$name= clean_name($fichier);
				$cat=0;
				

				//									//
				//On test les différentes catégories//
				//									//
				
				//Test Film
				$F=detectF($fichier);
				if($F!=0) {
						//Film VF ou VOSTFR
						$FV=detectV($fichier);
						if($FV==1) { $cat='014'; } //VOSTFR
						if($FV!=1) { $cat='024'; } //Film non VOSTFR
						  }
				//Test Series
				$S=detectS($fichier);
				if($S!=0) {
						//Series VF ou VOSTFR ?
						$SV=detectV($fichier);
						if($SV==2) { $cat='021'; } //FRENCH
						if($SV==1) { $cat='011'; } //VOSTFR
						else { $cat='021'; }
						  }
						
				//Test HD
				$HD=detectHD($fichier);
				if($HD!=0) {
							//Film HD ?
							$FHD=detectF($fichier);
							if($FHD!=0) {
										if($FHD!=0 && $HD==1) { $cat='100'; } //Film HD 720p
										if($FHD!=0 && $HD==2) { $cat='200'; } //Film HD 1080p
										}
							//Series ?
							$SHD=detectS($fichier);
							if($SHD!=0) {
									//Series HD VF ou VOSTFR ?
									$SHDVF=detectV($fichier);
									if($SHDVF==2) { $cat='120'; } //FRENCH
									if($SHDVF==1) { $cat='121'; } //VOSTFR
										}	
							}
							
				//Test Animé
				$AN=detectA($fichier);
				if($AN!=0) { $cat='223'; }
				
				//Test Documentaire
				$DOC=detectD($fichier);
				if($DOC!=0) {
							//DOC HD ?
							$HDOC=detectHD($fichier);
							if($HDOC!=0) { $cat='203'; }
							if($HDOC==0) { $cat='003'; }
							}
				
				//Test Ebooks
				$EB=detectEbook($fichier);
				if($EB!=0) { $cat='005'; }
				
				//Test FLAC
				$FLAC=detectFLAC($fichier);
				if($FLAC!=0) { $cat='002'; }

				    //Test Mac OSX
                    $MAC=detectMac($fichier);
                    if($MAC!=0) { $cat= '026'; }
				
				// --- Sous dossier
				if(is_dir($url)) {
					$f=1;
					$size='Dossier';
					$poid = poids($url);
					$sizef = unite($poid);
					$req = $bdd->prepare('INSERT INTO list(fichier, name, folder, size, sizefolder, cat, logtime, datetime, baseurl) VALUES(:fichier, :name, :folder, :size, :sizef, :cat, NOW(), :datetime, :baseurl)');
					$req->execute(array('fichier' => $fichier, 'name' => $name, 'folder' => $f, 'size' => $size, 'sizef'=>$sizef, 'cat' => $cat, 'datetime' => $datetime, 'baseurl' => $count)); }
				
				// --- Si c'est un fichier	
				if(is_file($url)) {	
					$f=0;
					$size= filesize($url);
					$fsize= convertFileSize($size);
					$req = $bdd->prepare('INSERT INTO list(fichier, name, folder, size, cat, logtime, datetime, baseurl) VALUES(:fichier, :name, :folder, :size, :cat, NOW(), :datetime, :baseurl)');
					$req->execute(array('fichier' => $fichier, 'name' => $name, 'folder' => $f, 'size' => $fsize, 'cat' => $cat, 'datetime' => $datetime, 'baseurl' => $count)); }
						
				
				
				} 
			}


	closedir($dossier);
  
	}
  
else { echo 'Le dossier n\' a pas pu être ouvert'; }

}

$affichefilm=$bdd->prepare('SELECT * FROM `list` WHERE (`cat`=200 OR `cat`=024) AND `sizefolder`< 50 ORDER BY datetime DESC LIMIT 0, 5');
$affichefilm->execute();

//$folder ='img/thumbnails/';
//clearFolder($folder);
$n=0;

echo '<center><h3>Mise à jour de la BDD</h3>';
while($film = $affichefilm->fetch()) {

	$arrayFilm[$n] = nomfilm($film['name']);
	$arrayFilm[$n] = str_replace(' ', '+', $arrayFilm[$n]);
    $urlimage = recupererimage2($arrayFilm[$n]);
 
 	if ($urlimage == "http://image.tmdb.org/t/p/w500'") {

    $arrayFilm[$n] = str_replace('+', ' ', $arrayFilm[$n]);
    $code = recuperercode($arrayFilm[$n]);
    $urlimage = recupererimage($code);
	
	}

   	$arrayFilm[$n] = str_replace(" ", "", $arrayFilm[$n]);
    $content = file_get_contents($urlimage);
	file_put_contents('img/thumbnails/'.$n.'.jpg', $content);
	$file_src='img/thumbnails/'.$n.'.jpg';
    echo '<img src="'.$file_src.'"" alt="coucou" height="100" width="75">';
	$n++;
}

echo '
<p>Redirection en cours ...</p>
</center>';
header('Refresh: 5; url=index.php'); 
//--- Recuperer le dernier id
$lastid = $bdd->lastInsertId();
$sqllastid = $bdd->prepare('UPDATE config SET lastid = :lastid');
$sqllastid->execute(array('lastid' => $lastid));

?>