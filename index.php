<?php 
//					//
//Author : @Seriesme//
//					//
error_reporting(E_ALL);
session_start();
include("includes/config.php");
include("includes/functions.php");

//Si déjà identifié 
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
include("header.php");
//On récupére le logtime
$sqltime=$bdd->prepare('SELECT logtime FROM list ORDER BY id LIMIT 1');
$sqltime->execute();
$sqllogtime = $sqltime->fetch();
$logtime = $sqllogtime['logtime'];
$sqlid=$bdd->prepare('SELECT lastid FROM config');
$sqlid->execute();
$sqllastid = $sqlid->fetch();
$lastid=$sqllastid['lastid'];
$npage=ceil($lastid/50);
$countid=0;

// --- Requete possible pour prendre les dossiers : 
// SELECT fichier, name, folder, size, cat, datetime, baseurl FROM `list` WHERE `cat`=200 AND `name` NOT LIKE '%Studio%' AND `name` NOT LIKE '%2%-%2%' ORDER BY datetime DESC LIMIT 0, 5

// --- Recuperation des 5 premiers 1080p dans la bdd
$testsql=$bdd->prepare('SELECT fichier, name, folder, size, sizefolder, cat, datetime, baseurl FROM `list` WHERE (`cat`=200 OR `cat`=024) AND `sizefolder`<50 ORDER BY datetime DESC LIMIT 0, 5');
$testsql->execute();



//On récupere la liste de fichiers + dossiers
if(empty($_GET['id'])) {
$sql = $bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list ORDER BY datetime DESC LIMIT 0, 50');
$sql->execute();   
}
else { 
$id=intval($_GET['id']);
$first=$id*50;
$seconde=50;
$sql = $bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list ORDER BY datetime DESC LIMIT :first, :seconde');
$sql->bindValue(':first', intval($first), PDO::PARAM_INT);
$sql->bindValue(':seconde', intval($seconde), PDO::PARAM_INT);
$sql->execute();
}

include("menu.php");
echo '<center><font color="white"><b>Derniers films 1080p disponibles : </b></font></center>';
echo '</br>';
echo '<center>';
echo '<div id=imageminiature>';
$n=0;
while($test = $testsql->fetch()) {
	
	//echo '<div width=500 heigh=300 id=imageminiatures>';
	if ($test['size']=='Dossier') {
		echo'<a href="index.php?baseurl='.$test['baseurl'].'&path='.$test['fichier'].'"><img src=img/thumbnails/'.$n.'.jpg height="225" width="175"></a>';
		//echo $test['fichier'].'/'.$test['name'];
	} else {
		echo'<a href="download.php?file='.$test['fichier'].'&b='.$test['baseurl'].'"><img src=img/thumbnails/'.$n.'.jpg height="225" width="175"></a>';
	
	}
	//echo '</div>';
	$n++;
}
echo '</div>';
echo '</center>';
echo '</br>';
?>
			<div class="panel panel-primary">
				<center><font size="5" color="red">Le Streaming est de nouveau fonctionnel !</font></center>
              	<center><font size="3" color="white">Vous pouvez ajouter un avatar depuis "Profil". Un avatar est mis par défaut, libre à vous de le changer.</font></center>
              	<center><font size="2" color="white">Il est conseillé d'utiliser la dernière version de Divx Player Web disponible : <a href="http://www.divx.com/fr/software/web-player>">ici</a></font></center>
              	<div class="panel-heading">

		<?php
		

		
		//Si on est sur l'index (pas dans un dossier)
		if(empty($_GET['baseurl']) && empty($_GET['path'])) {
		?>
		<h3 class="panel-title">Home </h3>
              </div>
              <div class="panel-body">
					<table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
					<th width="5%">Cat&eacute;gorie</th>
                    <th width="71%">Nom</th>
                    <th width="10%">Taille</th>
                    <th width="14%">Date</th>
                  </tr>
                </thead>
				<tbody>
		<?php
		//On lance la boucle
		while($stid = $sql->fetch()) {
			
			//On récupére la catégorie
			$cat2=defineCat($stid['cat']);
			
			//Date format
			$date = new DateTime(''.$stid['datetime'].'');
			$count= isset($count) ? $count : 0;
			$url=''.$baseurl[$stid['baseurl']].''.$stid['fichier'].'';
			
			//Si dossier
			if($stid['folder']==1) {
			echo'<tr>
				<td class="categorie '.$cat2.'"></td>
				<td><a href="index.php?baseurl='.$stid['baseurl'].'&path='.$stid['fichier'].'">'.$stid['fichier'].'</a></td>
				<td><center>'.$stid['size'].'    <img src=img/folder.png width="25" height="20"></center></td>			
				<td><center>'.$date->format('d M').' &agrave; '.$date->format('H:i').'</center></td>
				</tr>';
									}
			
			//Si c'est un fichier
			if($stid['folder']==0) {
			echo'<tr>
				<td class="categorie '.$cat2.'"></td>
				<td>';
				
				if(preg_match("/\.mp4/", $stid['fichier'])) { echo'<a href="stream.php?codec=mp4&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visionner '.$stid['name'].'"/></a> '; }
				elseif(preg_match("/\.mkv/", $stid['fichier'])) { echo'<a href="stream.php?codec=mkv&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visionner '.$stid['name'].'"/></a> '; }
				elseif(preg_match("/\.avi/", $stid['fichier'])) { echo'<a href="stream.php?codec=avi&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visionner '.$stid['name'].'"/></a> '; }
				else{}
				echo'<a href="download.php?file='.$stid['fichier'].'&b='.$stid['baseurl'].'">'.$stid['name'].'</a></td>
				<td><center><b>'.$stid['size'].'</b></center></td>		
				<td><center>'.$date->format('d M').' &agrave; '.$date->format('H:i').'</center></td>
				</tr>';
									}
									}//FIN DE LA BOUCLE
		echo '</tbody>
			</table>';
			
			//if(empty($lastid)) { echo'<div id="alert"><img src="./img/ajax-loader.gif" /> Le rechargement des fichiers est en cours!</div>'; }
			if($npage!=0) 
			{
			echo '<ul class="pagination">';
				if (empty($_GET['id']) || $_GET['id']==0)
				{
					echo '<li class="disabled"><a href="#">&laquo;</a></li>';
				}
				else
				{
					echo '<li><a href="index.php?id='.($_GET['id']-1).'">&laquo;</a></li>';
				}
					while($countid!=$npage) 
					{
						$echoid = $countid + 1;
						if(empty($_GET['id']) || $_GET['id']==0) 
						{ 
							if($countid==0) 
							{
								echo '<li class="active"><a href="index.php?id='.$countid.'">'.$echoid.'</a></li>';
							} 
							else 
							{
								echo '<li><a href="index.php?id='.$countid.'">'.$echoid.'</a></li>';
							} 
						}
						else if($countid==$_GET['id']) 
						{
							echo '<li class="active"><a href="index.php?id='.$countid.'">'.$echoid.'</a></li>';
						}
						else 
						{
							echo '<li><a href="index.php?id='.$countid.'">'.$echoid.'</a></li>';
						}
						$countid++;  
					}
				if ($_GET['id']==($npage-1))
				{
					echo '<li class="disabled"><a href="#">&raquo;</a></li>';
				}
				else
				{
					echo '<li><a href="index.php?id='.($_GET['id']+1).'">&raquo;</a></li>';
				}
				echo '</ul>
								</div>
		</div>';
			} 
		} // FIN 
		
		// ON EST DANS UN DOSSIER
		else {
			$baseurl=isset($_GET['baseurl']) ? htmlspecialchars($_GET['baseurl']) : null;
			$path=isset($_GET['path']) ? htmlspecialchars($_GET['path']) : null;
			show_folder($path,$baseurl);
			echo '</tbody>
			</table>
					</div>
		</div>
			';
			}
		
	include("footer.php");
}
//Sinon
else { header ("Location: login.php"); }

?>