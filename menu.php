	<div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="index.php" class="navbar-brand">Private-Share</a>
        </div>
        <div class="navbar-collapse collapse" id="navbar-main">
          <ul class="nav navbar-nav">
					<?php
						include("includes/config.php");
						$path = $_SERVER['PHP_SELF']; 
						$filepath = basename ($path); 
						$cat = 0; // 0 = Accueil

						if ($filepath == "search.php")
						{ 
							if(!empty($_GET['cat']))
							{
								$postcat=isset($_GET['cat']) ? htmlspecialchars($_GET['cat']) : null;
								if ($postcat == "Films") { $cat = 1; } // 1 = Films
								else if ($postcat == "Series") { $cat = 2; } // 2 = Series
								else if ($postcat == "Doc") { $cat = 3; } // 3 = Doc
								else if ($postcat == "Anime") { $cat = 4; } // 4 = Anime
							}
							else //On est juste sur la page de recherche
							{
								$cat = 5; // 5 = Pas de recherche
							}
						}
						if ($filepath == "profile.php")
							{ $cat = 6; } // 6 = Profile
						if ($filepath == "request.php")
							{ $cat = 7; } // 7 = Requêtes
						if ($filepath == "admin.php")
							{ $cat = 8; } // 8 = Admin
							
						$sqlid=$bdd->prepare('SELECT lastid FROM config');
						$sqlid->execute();
						$sqllastid = $sqlid->fetch();
						$lastid=$sqllastid['lastid'];
					?>
                      <li <?php if ($cat == 1) { echo'class="active"'; } ?>><a href="search.php?cat=Films">Films</a></li>
                      <li <?php if ($cat == 2) { echo'class="active"'; } ?>><a href="search.php?cat=Series">Séries</a></li>
					  <li <?php if ($cat == 3) { echo'class="active"'; } ?>><a href="search.php?cat=Doc">Docs</a></li>
					  <li <?php if ($cat == 4) { echo'class="active"'; } ?>><a href="search.php?cat=Anime">Animes</a></li>
					  <li <?php if ($cat == 5) { echo'class="active"'; } ?>><a href="search.php">Rechercher</a></li>

                    <ul class="nav navbar-nav navbar-right">
						<form class="navbar-form navbar-left" action="search.php" method="post">
                    <input type="text" name="search" class="form-control col-lg-8" placeholder="Recherche rapide">
                    </form>
                      <li <?php if ($cat == 6) { echo'class="active"'; } ?>><a href="profile.php">Profil </a></li>
				  <?php //Administrateur
				  if($_SESSION['rank']==1) { 
				  $countreq=0;
				  $sqlcount = $bdd->prepare('SELECT id, status FROM request_title WHERE status = :status OR status = :status2');
				  $sqlcount->execute(array('status' => '3', 'status2' => '0'));
				  while($tick1 = $sqlcount->fetch()) { $countreq++; } // On compte le nombre de requêtes ouverte
				  if($countreq==1) { if ($cat == 7) { echo'<li class="active">'; } else { echo '<li>'; } echo'<a id="req" href="request.php">Requ&ecirc;te <span class="badge">'.$countreq.'</span></a></li>'; }
				  elseif($countreq!=1&&$countreq!=0) { if ($cat == 7) { echo'<li class="active">'; } else { echo '<li>'; } echo'<a href="request.php">Requ&ecirc;tes <span class="badge">'.$countreq.'</span></a></li>'; }
				  else { if ($cat == 7) { echo'<li class="active">'; } else { echo '<li>'; } echo'<a href="request.php">Requ&ecirc;te <span class="badge"></span></a></li>'; }
				  if ($cat == 8) { echo'<li class="active">'; } else { echo '<li>'; }
					  	

				  echo'<a href="admin.php">Admin</a></li>';

					  		echo '<li><a href="run.php"><img src="img/refresh.png" width="18" height="18" /></a></li>';

					  	
				  
				  }

				  //Utilisateur est non Administrateur
				  else { 
				  $countreq=0;
				  $sqlcount = $bdd->prepare('SELECT id, status FROM request_title WHERE status = :status');
				  $sqlcount->execute(array('status' => '1'));
				  while($tick1 = $sqlcount->fetch()) { $countreq++; } // On compte le nombre de requétes ouverte
				  if($countreq==1) { if ($cat == 7) { echo'<li class="active">'; } else { echo '<li>'; } echo'<a href="request.php">Requ&ecirc;te <span class="badge">'.$countreq.'</span></a></li>'; }
				  elseif($countreq!=1&&$countreq!=0) { if ($cat == 7) { echo'<li class="active">'; } else { echo '<li>'; } echo'<a href="request.php">Requ&ecirc;tes <span class="badge">'.$countreq.'</span></a></li>'; }
				  else { if ($cat == 7) { echo'<li class="active">'; } else { echo '<li>'; } echo'<a href="request.php">Requ&ecirc;te <span class="badge"></span></a></li>'; } 
				  } ?>
					  <li><a href="logout.php">Logout</a></li>
			</ul>
        </div>
      </div>
    </div>
	
	<div class="container">

      <div class="bs-docs-section clearfix">
        <div class="row">
          <div class="col-lg-12">
		  <br /><br /><br /><br />


