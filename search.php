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
if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

	//								 //
	//Recherche mot clés + catégories//
	//								 //
	if(!empty($_POST['search']) && !empty($_POST['fsent'])) {
	
	$search=isset($_POST['search']) ? htmlspecialchars($_POST['search']) : null;
	
		//Si catégorie choisi
		if(!empty($_POST['cat'])) { 
		
				$cat=isset($_POST['cat']) ? htmlspecialchars($_POST['cat']) : null;
				$sql=$bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list WHERE cat = :cat AND name LIKE :search ORDER BY datetime DESC');
				$sql->execute(array('cat' => $cat, ':search' => '%' . $search . '%'));  
									}
									
		//Si pas de catégorie
		if(empty($_POST['cat'])) { 

				$sql=$bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list WHERE name LIKE :search ORDER BY datetime DESC');
				$sql->execute(array(':search' => '%' . $search . '%'));  
									}	
 
			//Header + debut tableau
			include("header.php");
			include("menu.php");
			
			echo '<div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">Resultats de recherche pour : '.$search.'</h3>
              </div>
              <div class="panel-body">';
			  
			  if ($stid = $sql->fetch()) {
			echo '<form class="navbar-form navbar-left" action="search.php" method="post">
						<input class="form-control" style="width:180;height:33;" id="focusedInput" type="text" name="search" value="Nouvelle recherche ...">
						<select class="form-control" name="cat" style="width:130;height:33;">
						<option value="" selected>Pas de filtre</option>
						<optgroup label="Séries">
						<option value="021">VF</option>
						<option value="011">VOSTFR</option>
						<option value="120">HD VF</option>
						<option value="121">HD VOSTFR</option>
						</optgroup>
						<optgroup label="Film">
						<option value="024">VF</option>
						<option value="014">VOSTFR</option>
						<option value="100">HD 720p</option>
						<option value="200">HD 1080p</option>
						</optgroup>
						<optgroup label="Doc">
                        <option value="003">Doc</option>
                        <option value="203">Doc HD</option>
                        </optgroup>
                        <option value="223">Animé</option>
						</select>
					<input type="submit" class="btn btn-primary" id="submit" name="fsent" value="Valider" />
				 </form>
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
				//ON LANCE LA BOUCLE
				do {
						//On récupére la catégorie
						$cat2=defineCat($stid['cat']);
						//Date format
						$date = new DateTime(''.$stid['datetime'].'');
			
						//Si dossier
						if($stid['folder']==1) {
						echo'<tr>
								<td class="categorie '.$cat2.'"></td>
								<td><a href="index.php?baseurl='.$stid['baseurl'].'&path='.$stid['fichier'].'">'.$stid['fichier'].'</a></td>
								<td>'.$stid['size'].'</td>			
								<td>'.$date->format('Y-m-d').' &agrave; '.$date->format('H:i:s').'</td>
							</tr>';
												}
			
						//Si c'est un fichier
						if($stid['folder']==0) {
						echo'<tr>
							<td class="categorie '.$cat2.'"></td>
							<td>';
							if(preg_match("/\.mp4/", $stid['fichier'])) { echo'<a href="stream.php?codec=mp4&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							elseif(preg_match("/\.mkv/", $stid['fichier'])) { echo'<a href="stream.php?codec=mkv&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							elseif(preg_match("/\.avi/", $stid['fichier'])) { echo'<a href="stream.php?codec=avi&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							else{}
							echo'<a href="download.php?file='.$stid['fichier'].'&b='.$stid['baseurl'].'">'.$stid['name'].'</a></td>
							<td>'.$stid['size'].'</td>			
							<td>'.$date->format('Y-m-d').' &agrave; '.$date->format('H:i:s').'</td>
							</tr>';
												}
						//FIN DE LA BOUCLE
					} while ($stid = $sql->fetch());
					
					echo '</tbody>
				</table>';
			}
			else // PAS DE RESULTATS
			{
				echo '<div class="alert alert-dismissable alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Try again!</strong> <u>Aucun '.strtolower($echocat).' trouvé</u>
						</div>
						<form class="navbar-form navbar-left" action="search.php" method="post">
						<input class="form-control" style="width:180;height:33;" id="focusedInput" type="text" name="search" value="Nouvelle recherche ...">
						<select class="form-control" name="cat" style="width:130;height:33;">
						<option value="" selected>Pas de filtre</option>
						<optgroup label="Séries">
						<option value="021">VF</option>
						<option value="011">VOSTFR</option>
						<option value="120">HD VF</option>
						<option value="121">HD VOSTFR</option>
						</optgroup>
						<optgroup label="Film">
						<option value="024">VF</option>
						<option value="014">VOSTFR</option>
						<option value="100">HD 720p</option>
						<option value="200">HD 1080p</option>
						</optgroup>
						<optgroup label="Doc">
                        <option value="003">Doc</option>
                        <option value="203">Doc HD</option>
                        </optgroup>
                        <option value="223">Animé</option>
						</select>
					<input type="submit" class="btn btn-primary" id="submit" name="fsent" value="Valider" />
				 </form>';
			}
			
			echo '</div>
            </div>';
		
	}
	
	//									 //
	//Recherche par Catégories uniquement//
	//									 //
	elseif(empty($_POST['search']) && empty($_POST['fsent']) && !empty($_GET['cat'])) {
	
		$cat=isset($_GET['cat']) ? htmlspecialchars($_GET['cat']) : null;
		$echocat=isset($_GET['cat']) ? htmlspecialchars($_GET['cat']) : null;
		
			if($cat=='Films') {
					
					$cat='014';
					$cat1='024';
					$cat2='100';
					$cat3='200';
					$sql=$bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list WHERE cat = :cat OR cat = :cat1 OR cat = :cat2 OR cat = :cat3 ORDER BY datetime DESC');
					$sql->execute(array(':cat' => $cat, ':cat1' => $cat1, ':cat2' => $cat2, ':cat3' => $cat3)); 
					
								}
				
			elseif($cat=='Series') {
					
					$cat='011';
					$cat1='021';
					$cat2='120';
					$cat3='121';
					$sql=$bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list WHERE cat = :cat OR cat = :cat1 OR cat = :cat2 OR cat = :cat3 ORDER BY datetime DESC');
					$sql->execute(array(':cat' => $cat, ':cat1' => $cat1, ':cat2' => $cat2, ':cat3' => $cat3)); 
					
								}
								
			elseif($cat=='Doc') {
					
					$cat='003';
					$cat1='203';
					$sql=$bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list WHERE cat = :cat OR cat = :cat1 ORDER BY datetime DESC');
					$sql->execute(array(':cat' => $cat, ':cat1' => $cat1)); 
					
								}
								
			elseif($cat=='Anime') {
					
					$cat='223';
					$sql=$bdd->prepare('SELECT fichier, name, folder, size, cat, datetime, baseurl FROM list WHERE cat = :cat ORDER BY datetime DESC');
					$sql->execute(array(':cat' => $cat)); 
					
								}								
								
			else { header ("Location: login.php"); }
			
			include("header.php");			
			include("menu.php");
			
			
			
	    echo '<div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">Liste des '.strtolower($echocat).'</h3>
              </div>
              <div class="panel-body">';
			  
			 
			if ($stid = $sql->fetch()) {
			echo '
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
				//ON LANCE LA BOUCLE
				do {
						//On récupére la catégorie
						$cat2=defineCat($stid['cat']);
						//Date format
						$date = new DateTime(''.$stid['datetime'].'');
			
						//Si dossier
						if($stid['folder']==1) {
						echo'<tr>
								<td class="categorie '.$cat2.'"></td>
								<td><a href="index.php?baseurl='.$stid['baseurl'].'&path='.$stid['fichier'].'">'.$stid['fichier'].'</a></td>
								<td>'.$stid['size'].'</td>			
								<td>'.$date->format('Y-m-d').' &agrave; '.$date->format('H:i:s').'</td>
							</tr>';
												}
			
						//Si c'est un fichier
						if($stid['folder']==0) {
						echo'<tr>
							<td class="categorie '.$cat2.'"></td>
							<td>';
							if(preg_match("/\.mp4/", $stid['fichier'])) { echo'<a href="stream.php?codec=mp4&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							elseif(preg_match("/\.mkv/", $stid['fichier'])) { echo'<a href="stream.php?codec=mkv&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							elseif(preg_match("/\.avi/", $stid['fichier'])) { echo'<a href="stream.php?codec=avi&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							else{}
							echo'<a href="download.php?file='.$stid['fichier'].'&b='.$stid['baseurl'].'">'.$stid['name'].'</a></td>
							<td>'.$stid['size'].'</td>			
							<td>'.$date->format('Y-m-d').' &agrave; '.$date->format('H:i:s').'</td>
							</tr>';
												}
						//FIN DE LA BOUCLE
					} while ($stid = $sql->fetch());
					
					echo '</tbody>
				</table>';
			}
			else // PAS DE RESULTATS
			{
				echo '<div class="alert alert-dismissable alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Try again!</strong> <u>Aucun '.strtolower($echocat).' trouvé</u>
						</div>
						<form class="navbar-form navbar-left" action="search.php" method="post">
						<input class="form-control" style="width:180;height:33;" id="focusedInput" type="text" name="search" value="Nouvelle recherche ...">
						<select class="form-control" name="cat" style="width:130;height:33;">
						<option value="" selected>Pas de filtre</option>
						<optgroup label="Séries">
						<option value="021">VF</option>
						<option value="011">VOSTFR</option>
						<option value="120">HD VF</option>
						<option value="121">HD VOSTFR</option>
						</optgroup>
						<optgroup label="Film">
						<option value="024">VF</option>
						<option value="014">VOSTFR</option>
						<option value="100">HD 720p</option>
						<option value="200">HD 1080p</option>
						</optgroup>
						<optgroup label="Doc">
                        <option value="003">Doc</option>
                        <option value="203">Doc HD</option>
                        </optgroup>
                        <option value="223">Animé</option>
						</select>
					<input type="submit" class="btn btn-primary" id="submit" name="fsent" value="Valider" />
				 </form>';
			}
			
			echo '</div>
            </div>';
	}
	
	//				  //
	//Recherche Rapide//
	//				  //
	elseif(!empty($_POST['search']) && empty($_POST['fsent']) && empty($_GET['cat'])) {
	$search = isset($_POST['search']) ? htmlspecialchars($_POST['search']) : null;
	$sql=$bdd->prepare('SELECT * FROM list WHERE name LIKE :search ORDER BY datetime DESC');
	$sql->execute(array(':search' => '%' . $search . '%'));  
	include("header.php"); 
	include("menu.php");
	echo '<div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">Resultats de recherche pour : '.$search.'</h3>
              </div>
              <div class="panel-body">';
			  
			  if ($stid = $sql->fetch()) {
			echo '<form class="navbar-form navbar-left" action="search.php" method="post">
						<input class="form-control" style="width:180;height:33;" id="focusedInput" type="text" name="search" value="Nouvelle recherche ...">
						<select class="form-control" name="cat" style="width:130;height:33;">
						<option value="" selected>Pas de filtre</option>
						<optgroup label="Séries">
						<option value="021">VF</option>
						<option value="011">VOSTFR</option>
						<option value="120">HD VF</option>
						<option value="121">HD VOSTFR</option>
						</optgroup>
						<optgroup label="Film">
						<option value="024">VF</option>
						<option value="014">VOSTFR</option>
						<option value="100">HD 720p</option>
						<option value="200">HD 1080p</option>
						</optgroup>
						<optgroup label="Doc">
                        <option value="003">Doc</option>
                        <option value="203">Doc HD</option>
                        </optgroup>
                        <option value="223">Animé</option>
						</select>
					<input type="submit" class="btn btn-primary" id="submit" name="fsent" value="Valider" />
				 </form>
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
				//ON LANCE LA BOUCLE
				do {
						//On récupére la catégorie
						$cat2=defineCat($stid['cat']);
						//Date format
						$date = new DateTime(''.$stid['datetime'].'');
			
						//Si dossier
						if($stid['folder']==1) {
						echo'<tr>
								<td class="categorie '.$cat2.'"></td>
								<td><a href="index.php?baseurl='.$stid['baseurl'].'&path='.$stid['fichier'].'">'.$stid['fichier'].'</a></td>
								<td>'.$stid['size'].'</td>			
								<td>'.$date->format('Y-m-d').' &agrave; '.$date->format('H:i:s').'</td>
							</tr>';
												}
			
						//Si c'est un fichier
						if($stid['folder']==0) {
						echo'<tr>
							<td class="categorie '.$cat2.'"></td>
							<td>';
							if(preg_match("/\.mp4/", $stid['fichier'])) { echo'<a href="stream.php?codec=mp4&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							elseif(preg_match("/\.mkv/", $stid['fichier'])) { echo'<a href="stream.php?codec=mkv&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							elseif(preg_match("/\.avi/", $stid['fichier'])) { echo'<a href="stream.php?codec=avi&file='.$stid['fichier'].'&b='.$stid['baseurl'].'"><img src="./img/play.png" width="16" height="16" title="Visioner '.$stid['name'].'"/></a> '; }
							else{}
							echo'<a href="download.php?file='.$stid['fichier'].'&b='.$stid['baseurl'].'">'.$stid['name'].'</a></td>
							<td>'.$stid['size'].'</td>			
							<td>'.$date->format('Y-m-d').' &agrave; '.$date->format('H:i:s').'</td>
							</tr>';
												}
						//FIN DE LA BOUCLE
					} while ($stid = $sql->fetch());
					
					echo '</tbody>
				</table>';
			}
			else // PAS DE RESULTATS
			{
				echo '<div class="alert alert-dismissable alert-warning">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						<strong>Try again!</strong> <u>Aucun '.strtolower($echocat).' trouvé</u>
						</div>
						<form class="navbar-form navbar-left" action="search.php" method="post">
						<input class="form-control" style="width:180;height:33;" id="focusedInput" type="text" name="search" value="Nouvelle recherche ...">
						<select class="form-control" name="cat" style="width:130;height:33;">
						<option value="" selected>Pas de filtre</option>
						<optgroup label="Séries">
						<option value="021">VF</option>
						<option value="011">VOSTFR</option>
						<option value="120">HD VF</option>
						<option value="121">HD VOSTFR</option>
						</optgroup>
						<optgroup label="Film">
						<option value="024">VF</option>
						<option value="014">VOSTFR</option>
						<option value="100">HD 720p</option>
						<option value="200">HD 1080p</option>
						</optgroup>
						<optgroup label="Doc">
                        <option value="003">Doc</option>
                        <option value="203">Doc HD</option>
                        </optgroup>
                        <option value="223">Animé</option>
						</select>
					<input type="submit" class="btn btn-primary" id="submit" name="fsent" value="Valider" />
				 </form>';
			}
			
			echo '</div>
            </div>';
		
		}
	
	//											//
	//Sinon affichage du formulaire de recherche//
	//											//
	else{ 
	include("header.php"); 
	include("menu.php"); 
	echo '<div class="panel panel-success">
              <div class="panel-heading">
                <h3 class="panel-title">Page de recherche</h3>
              </div>
              <div class="panel-body">
						<form class="navbar-form navbar-left" action="search.php" method="post">
						<input class="form-control" style="width:180;height:33;" id="focusedInput" type="text" name="search" value="Nouvelle recherche ...">
						<select class="form-control" name="cat" style="width:130;height:33;">
						<option value="" selected>Pas de filtre</option>
						<optgroup label="Séries">
						<option value="021">VF</option>
						<option value="011">VOSTFR</option>
						<option value="120">HD VF</option>
						<option value="121">HD VOSTFR</option>
						</optgroup>
						<optgroup label="Film">
						<option value="024">VF</option>
						<option value="014">VOSTFR</option>
						<option value="100">HD 720p</option>
						<option value="200">HD 1080p</option>
						</optgroup>
						<optgroup label="Doc">
                        <option value="003">Doc</option>
                        <option value="203">Doc HD</option>
                        </optgroup>
                        <option value="223">Animé</option>
						</select>
					<input type="submit" class="btn btn-primary" id="submit" name="fsent" value="Valider" />
				 </form>';
			  
		echo '</div>
            </div>';
		}
}
//Sinon
else { header ("Location: login.php"); }

include("footer.php");
?>