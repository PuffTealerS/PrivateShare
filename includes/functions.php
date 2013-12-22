<?php
require_once(__DIR__.'/api-allocine-helper.php');
include("config.php");


function vider_cookie()
{
    foreach($_COOKIE as $cle => $element)
    {
        setcookie($cle, '', time()-3600);
    }
}

function genactivationid ($longueur = 45) {

    $activeid = "";
	$possible = "012346789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
 
    $longueurMax = strlen($possible);
 
    if ($longueur > $longueurMax) {
        $longueur = $longueurMax;
    }
 
    $i = 0;

    while ($i < $longueur) {
        $caractere = substr($possible, mt_rand(0, $longueurMax-1), 1);
 
            $activeid .= $caractere;
            $i++;
        
    }

    return $activeid;
}

function cleandl($namefile)
{
	$namefile = str_replace('../', '', $namefile);
	$namefile = str_replace('/..', '', $namefile);
	return $namefile;
}

function get_ip()
{
    if ( isset ( $_SERVER['HTTP_X_FORWARDED_FOR'] ) )
    {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    elseif ( isset ( $_SERVER['HTTP_CLIENT_IP'] ) )
    {
        $ip  = $_SERVER['HTTP_CLIENT_IP'];
    }
    else
    {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

function show_folder($path,$b)
{
	$i = 0;
	$array = explode('/',$path);
	$result=count($array);
	echo'<h3 class="panel-title">'; 
	echo '<a href="index.php">Home</a>';
	while ($i != $result)
	{
	echo ' / ';
	echo '<a href="index.php?baseurl='.$b.'&path=';
	for($j=0;$j<=$i;$j++)
	{  echo $array[$j];  }
	echo '">';
	if ($i+1 == $result)
	{	echo '<span style="color:#B1B5B7;"><u>'; }
	echo clean_name($array[$i]);
	if ($i+1 == $result)
	{	echo '</u></span>'; }
	echo '</a>';
	$i++;
	}
	echo '</h3>
              </div>
              <div class="panel-body">
					<table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
					<th width="5%">Cat&eacute;gorie</th>
                    <th width="71%">Nom</th>
                    <th width="8%">Taille</th>
                    <th width="16%">Date</th>
                  </tr>
                </thead>
				<tbody>';
				
	require("config.php");
	$realpath = ''.$baseurl[$b].''.$path.'';
    $files = scandir($realpath);
    foreach ($files as $value)
    {
        if ($value == '.' || $value == '..' || $value == 'index.php'  || $value == '.htaccess'  || $value == '.htpasswd' || $value == '.session')
            continue;
         
		$absolutePath = "$realpath/$value";
        if (is_dir($absolutePath))
        {	
			include("config.php");
			echo '<tr>';
			$namefile= basename($absolutePath);
			$name= clean_name(basename($absolutePath));
					
			echo '<td class="categorie '.detectme($namefile).'"></td>';
			echo '<td>';
			$url = $absolutePath;
            echo '<a href="index.php?baseurl='.$b.'&path='.$path.'/'.$namefile.'">'.$name.'</a></td>';
			echo '<td>Dossier</td>';
			$date= date ("Y-m-d", filemtime($absolutePath));
			$time= date ("H:i:s", filemtime($absolutePath));
			echo '<td>'.$date.' &agrave; '.$time.'</td>';
        }
        elseif (is_file($absolutePath))
        {
			include("config.php");
			echo '<tr>';
			$namefile= basename($absolutePath);
			$name= clean_name(basename($absolutePath));
			$download = str_replace(''.$baseurl[$b].'', '', $absolutePath);
					
			echo '<td class="categorie '.detectme($namefile).'"></td>';
			echo '<td>';
			$url = $absolutePath;
			if(preg_match("/\.mp4/", $url)) { echo'<a href="stream.php?codec=mp4&sfile='.$download.'&b='.$b.'"><img src="./img/play.png" width="13" height="13" title="Visioner '.$name.'"/></a> '; }
			elseif(preg_match("/\.mkv/", $url)) { echo'<a href="stream.php?codec=mkv&sfile='.$download.'&b='.$b.'"><img src="./img/play.png" width="13" height="13" title="Visioner '.$name.'"/></a> '; }
			elseif(preg_match("/\.avi/", $url)) { echo'<a href="stream.php?codec=avi&sfile='.$download.'&b='.$b.'"><img src="./img/play.png" width="13" height="13" title="Visioner '.$name.'"/></a> '; }
			else{}
            echo '<a href="download.php?sfile='.$download.'&b='.$b.'">'.$name.'</a></td>';
			echo '<td>';
			$size= filesize($absolutePath);
			if ($size >= 1024*1024*1024)
			echo round(($size / 1024)/1024/1024, 2) ." Go";
			elseif ($size >= 1024*1024)
			echo round(($size / 1024)/1024, 2) ." Mo";
			elseif ($size >= 1024)
			echo round(($size / 1024), 2) ." ko";
			else
			echo $size ." octets";
			echo '</td>';
			$date= date ("Y-m-d", filemtime($absolutePath));
			$time= date ("H:i:s", filemtime($absolutePath));
			echo '<td>'.$date.' &agrave; '.$time.'</td>';
        }
    }
}

//Detection pour rpatch
function detectme($namefile)
{
	$cat = "cat-divers";
				
					//									//
					//On test les différentes catégories//
					//									//
				
					//Test Film
					$F=detectF($namefile);
					if($F!=0) {
							//Film VF ou VOSTFR
							$FV=detectV($namefile);
							if($FV==1) { $cat= "cat-dvdrip-vostfr"; } //VOSTFR
							if($FV!=1) { $cat= "cat-dvdrip"; } //Film non VOSTFR
							}
					//Test Series
					$S=detectS($namefile);
					if($S!=0) {
							//Series VF ou VOSTFR ?
							$SV=detectV($namefile);
							if($SV==2) { $cat= "cat-tv-vf"; } //FRENCH
							if($SV==1) { $cat= "cat-tv-vostfr"; } //VOSTFR
							else { $cat= "cat-tv-vf"; }
								}
						
					//Test HD
					$HD=detectHD($namefile);
					if($HD!=0) {
								//Film HD ?
								$FHD=detectF($namefile);
								if($FHD!=0) {
											if($FHD!=0 && $HD==1) { $cat= "cat-hd-720p"; } //Film HD 720p
											if($FHD!=0 && $HD==2) { $cat= "cat-hd-1080p"; } //Film HD 1080p
											}
								//Series ?
								$SHD=detectS($namefile);
								if($SHD!=0) {
										//Series HD VF ou VOSTFR ?
										$SHDVF=detectV($namefile);
										if($SHDVF==2) { $cat= "cat-tv-hd-vf"; } //FRENCH
										if($SHDVF==1) { $cat= "cat-tv-hd-vostfr"; } //VOSTFR
											}	
								}
								
					//Test Animé
					$AN=detectA($namefile);
					if($AN!=0) { $cat= "cat-anime"; }
					
					//Test Documentaire
					$DOC=detectD($namefile);
					if($DOC!=0) {
								//DOC HD ?
								$HDOC=detectHD($namefile);
								if($HDOC!=0) { $cat= "cat-doc-hd"; }
								if($HDOC==0) { $cat= "cat-doc"; }
								}
					
					//Test Ebooks
					$EB=detectEbook($namefile);
					if($EB!=0) { $cat= "cat-ebook"; }
					
					//Test FLAC
					$FLAC=detectFLAC($namefile);
					if($FLAC!=0) { $cat= "cat-flac"; }

                    //Test Mac OSX
                    $MAC=detectMac($namefile);
                    if($MAC!=0) { $cat= "cat-apple"; }
					
		//FICHIER NFO
		if(preg_match("#.nfo#i", $namefile)){$cat = "cat-divers";}
		
	return $cat;
}


//Détection animé
function detectA($namefile)
{
	$a=0;
	if(preg_match("#MANGACiTY#i", $namefile)){$a = 1;}
	elseif(preg_match("#MANAMANE#i", $namefile)){$a = 1;}
	elseif(preg_match("#OOKAMI#i", $namefile)){$a = 1;}
	elseif(preg_match("#EPOKE#i", $namefile)){$a = 1;}
	elseif(preg_match("#NEECHAN#i", $namefile)){$a = 1;}
	elseif(preg_match("#SLEEPINGFOREST#i", $namefile)){$a = 1;}
	elseif(preg_match("#SHINK3N#i", $namefile)){$a = 1;}
	elseif(preg_match("#NEEDPEACE#i", $namefile)){$a = 1;}
    elseif(preg_match("#OAD#i", $namefile)){$a = 1;}
    elseif(preg_match("#Tkanime#i", $namefile)){$a = 1;}
    elseif(preg_match("#OchaFansub#i", $namefile)){$a = 1;}
    elseif(preg_match("#fullanime#i", $namefile)){$a = 1;}
    elseif(preg_match("#NiRVANAJ#i", $namefile)){$a = 1;}
	return $a;

}
// Detection Mac
function detectMac($namefile)
{
    $a=0;
    if(preg_match("#Mac#i", $namefile)){$a = 1;}
    elseif(preg_match("#OSX#i", $namefile)){$a = 1;}
    elseif(preg_match("#dmg#i", $namefile)){$a = 1;}
    elseif(preg_match("i#MacOSX#i", $namefile)){$a=1;}

    return $a;
}
//Détection Documentaire
function detectD($namefile)
{
	$d=0;
	if(preg_match("#DOC#i", $namefile)){$d = 1;}
	return $d;
}

//											  //
//											  //
//MERCI A PPDG2 POUR CES FONCTIONS CI DESSOUS //
//      @ http://yourcreation.fr/			  //
//											  //
//											  //
function convertFileSize($bytes)
{
	if ($bytes >= 1024*1024*1024)
	return round(($bytes / 1024)/1024/1024, 2) ." Go";
	
	elseif ($bytes >= 1024*1024)
	return round(($bytes / 1024)/1024, 2) ." Mo";
	
	
	elseif ($bytes >= 1024)
	return round(($bytes / 1024), 2) ." ko";
	
	else
	return $bytes ." octets";
}

//Reformuler le nom
function clean_name($namefile)
{
	$namefile = str_replace('.avi', '', $namefile);
	$namefile = str_replace('.mp4', '', $namefile);
	$namefile = str_replace('.mkv', '', $namefile);
	$namefile = str_replace('.', ' ', $namefile);
	//$namefile = str_replace('-', ' ', $namefile);
	//$namefile = str_replace('_', ' ', $namefile);
	//$namefile =	strtolower($namefile);
	$namefile = ucfirst($namefile); 
	return $namefile;
}


//Détection hD
function detectHD($namefile)
{
	$HD=0;
	if(preg_match("#720#i", $namefile)){$HD = 1;}
	elseif(preg_match("#1080#i", $namefile)){$HD = 2;}
	return $HD;
}

//Détection VOSTFR | VF
function detectV($namefile)
{
	$v=0;
	if(preg_match("#vostfr#i", $namefile)){$v = 1;}
	elseif(preg_match("#french#i", $namefile) || preg_match("#truefrench#i", $namefile)){$v = 2;}
	return $v;
}

//Détection Série
function detectS($namefile)
{
	$s=0;
	if(preg_match("#S0#i", $namefile)){$s = 1;}
	elseif(preg_match("#S1#i", $namefile)){$s = 1;}
	elseif(preg_match("#S2#i", $namefile)){$s = 1;}
	elseif(preg_match("#S3#i", $namefile)){$s = 1;}
	elseif(preg_match("#saison#i", $namefile)){$s = 1;}
	return $s;
}

//Détection Film
function detectF($namefile)
{
	$f=0;
	if(preg_match("#dvdrip#i", $namefile)){$f = 1;}
	elseif(preg_match("#bdrip#i", $namefile)){$f = 1;}
	elseif(preg_match("#brrip#i", $namefile)){$f = 1;}
	elseif(preg_match("#bluray#i", $namefile)){$f = 1;}
	elseif(preg_match("#dvdscr#i", $namefile)){$f = 1;}
	return $f;
}

//Détection Flac
function detectFLAC($namefile)
{
	$f=0;
	if(preg_match("#FLAC#i", $namefile)){$f = 1;}
	return $f;
}

//Détection Ebooks
function detectEbook($namefile)
{
	$f=0;
	if(preg_match("/\.pdf/", $namefile)){$f = 1;}
	if(preg_match("/\.epub/", $namefile)){$f = 1;}
	elseif(preg_match("#ebook#i", $namefile)){$f = 1;}
	return $f;
}

//detection catégorie final
function defineCat($code)
{
    switch($code)
    {
        case '100':$cat = "cat-hd-720p";return $cat;
            break;
        case '111':$cat = "cat-tv-hd-vostfr";return $cat;
            break;
        case '120':$cat = "cat-tv-hd-vf";return $cat;
            break;
        case '121':$cat = "cat-tv-hd-vostfr";return $cat;
            break;
        case '200':$cat = "cat-hd-1080p";return $cat;
            break;
        case '220':$cat = "cat-hd-1080p";return $cat;
            break;
        case '024':$cat = "cat-dvdrip";return $cat;
            break;
        case '004':$cat = "cat-dvdrip";return $cat;
            break;
        case '014':$cat = "cat-dvdrip-vostfr";return $cat;
            break;
        case '011':$cat = "cat-tv-vostfr";return $cat;
            break;
        case '021':$cat = "cat-tv-vf";return $cat;
            break;
        case '005':$cat = "cat-ebook";return $cat;
            break;
        case '025':$cat = "cat-ebook";return $cat;
            break;
        case '002':$cat = "cat-flac";return $cat;
            break;
        case '006':$cat = "cat-apple";return $cat;
            break;
        case '026':$cat = "cat-apple";return $cat;
            break;
        case '007':$cat = "cat-window";return $cat;
            break;
        case '027':$cat = "cat-window";return $cat;
            break;
        case '003':$cat = "cat-doc";return $cat;
            break;
        case '203':$cat = "cat-doc-hd";return $cat;
            break;
        case '103':$cat = "cat-doc-hd";return $cat;
            break;
        case '023':$cat = "cat-doc";return $cat;
            break;
        case '223':$cat = "cat-anime";return $cat;
            break;
        case '123':$cat = "cat-doc-hd";return $cat;
            break;
    	default: $cat = "cat-divers"; return $cat;
    }
}

//detection section
function defineSection($section)
{
    switch($section)
    {
        case 'Films':
        		$table = array("100", "120", "200", "220", "024", "014", "004");
                return $table;
            break;
        case 'Musiques':
        		$table = array("002");
                return $table;
            break;
        case 'eBooks':
        		$table = array("005", "025");
                return $table;
        case 'Series':
        		$table = array("111", "121", "011", "021");
                return $table;
        case 'Autre':
        		$table = array("100", "120", "200", "220", "024", "014", "004", "002", "005", "111", "121", "011", "021", "025");
                return $table;
            break;
    }
}

/*
 * Nettoyer le nom du film afin de ne recuperer que le titre
 */

function nomfilm ($string) {
    

    $string = strtolower($string);    
    $string = str_replace('.', ' ', $string);
    $string = preg_replace('/truefrench/', '', $string);
    $string = str_replace('(', '', $string);
    $string = str_replace(')', '', $string);
    $string = str_replace("'",' ',$string);
    $string = preg_replace('/dts-hdma/','', $string);
    $string = strstr($string, '-', TRUE);
    $string = preg_replace('/limited/', '', $string);
    $string = preg_replace('/x264/', '', $string);
    $string = preg_replace('/brrip/', '', $string);
    $string = preg_replace('/french/', '', $string);
    $string = preg_replace('/dvdrip/', '', $string);
    $string = preg_replace('/hd ma/', '', $string);
    $string = preg_replace('/hd/', '', $string);
    $string = preg_replace('/subforced/', '', $string);
    $string = preg_replace('/fastsub/', '', $string);
    $string = preg_replace('/proper/', '', $string);
    $string = preg_replace('/xvid/', '', $string);
    $string = preg_replace('/dts hdma ac3/', '', $string);
    $string = preg_replace('/dts hdma/', '', $string);
    $string = preg_replace('/dts/', '', $string);
    $string = preg_replace('/1080p/', '', $string);
    $string = preg_replace('/bluray/', '', $string);
    $string = preg_replace('/multi/', '', $string);  
    $string = preg_replace('/multigrps/', '', $string);
    $string = preg_replace('/extended cut/', '', $string);
    $string = preg_replace('/extended/', '', $string);
    $string = preg_replace('/vostfr/','', $string);
    $string = preg_replace('/version cinema/','', $string);
    $string = preg_replace('/ac3/', '', $string);
    $string = preg_replace('/proper/', '', $string);    
    $string = preg_replace('/([0-9]{4})/', '', $string);
    $string = rtrim($string);

    
    return $string;
}

    function recuperercode($nomfilm) {

    	$allohelper = new AlloHelper;

    // Parameters
    $page = 1;
    $count = 1;
    
    try
    {
        // Request
        $data = $allohelper->search($nomfilm, $page, $count);
        
        // No result ?
        if (!$data or count($data->movie) < 1)
            throw new ErrorException('No result for "' . $search . '"');
        
       
        // For each movie result.
        foreach ($data->movie as $i => $movie)
        {

            // i | code | title
            
            $code = $movie->code;
            
            
        }
    }
    
    // Error
    catch (ErrorException $e)
    {
        echo "Error " . $e->getCode() . ": " . $e->getMessage() . PHP_EOL;
    }

    
    return $code;

}
	

    function recupererimage($code) 
    {

    $allohelper = new AlloHelper;
    
    try
    {
        // Request
        $movie = $allohelper->movie($code);
        
        $url=$movie->poster;


    }
    
    // Error
    catch (ErrorException $e)
    {
        echo "Error " . $e->getCode() . ": " . $e->getMessage() . PHP_EOL;
    }
    

    return $url;
}

function recupererimage2($nom) 
{
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://api.themoviedb.org/3/search/movie?query='".$nom."'&api_key=6f2170b54b929e099b2f0ffd8c3d0c79&language=fr");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ch, CURLOPT_HEADER, FALSE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response, true);
$string = "http://image.tmdb.org/t/p/w500". $result['results'][0]['poster_path'] . "'";
return $string;
}


?>