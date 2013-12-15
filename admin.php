<?php 
//					//
//Author : @Seriesme//
//					//
session_start();
include("includes/config.php");
include("includes/functions.php");

//Si déjà identifié + Administrateur 
if (isset($_SESSION['id']) && isset($_SESSION['username']) && $_SESSION['rank']==1) {
$log = isset($_GET['log']) ? intval($_GET['log']) : null;
$action = isset($_GET['action']) ? htmlspecialchars($_GET['action']) : null;

			if($action=='purgelog' && !empty($_GET['id'])) {
			$id=isset($_GET['id']) ? intval($_GET['id']) : null;
			
			$req=$bdd->prepare('DELETE FROM logs WHERE uid= :uid');
			$req->execute(array('uid' => $id)) or die (header ('refresh: 2; url=admin.php') . 'Aucun utilisateur n\'est trouvé ! Redirection en cours ...');;
			
			header ('Location: admin.php?log='.$id);
			
			}

	if(!empty($_POST['submitadduser'])) {
		$username=isset($_POST['username']) ? htmlspecialchars($_POST['username']) : null;
		$email=isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;
		$password=isset($_POST['password']) ? htmlspecialchars($_POST['password']) : null;
		
		$pass_hache = sha1($password);
		$rank=2;
		$actived=1;
		
		$req = $bdd->prepare('INSERT INTO users(username, rank, password, email, actived) VALUES(:username, :rank, :password, :email, :actived)');
		$req->execute(array('username' => $username, 'rank' => $rank, 'password' => $pass_hache, 'email' => $email, 'actived' => $actived));
		
		header ("Location: admin.php");
			}
			
			
	elseif(!empty($log)) {
	$sqlu = $bdd->prepare('SELECT username FROM users WHERE id = :id');
	$sqlu->execute(array('id' => $log)) or die(header ('Location: admin.php'));
	$sqll = $sqlu->fetch();
	$sql = $bdd->prepare('SELECT username, action, date, time, ip FROM logs WHERE uid = :uid');
	$sql->execute(array('uid' => $log)) or die(header ('Location: admin.php'));
	include("header.php");
	include("menu.php");
	?>
	<div class="panel panel-danger">
              <div class="panel-heading">
                <h3 class="panel-title">Panel Administration</h3>
              </div>
              <div class="panel-body">
			  <?php echo'<br />Log de '.$sqll['username'].' :'; ?><br />Purger les logs de cet utilisateur&nbsp;<a href="admin.php?action=purgelog&id=<?php echo''.$log.''; ?>" title="Purger les logs"><img src="img/viderlog.png" /></a>
			  <br />
				<table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="71%">Action</th>
                    <th width="12%">IP</th>
                    <th width="8%">Heure</th>
                    <th width="9%">Date</th>
                  </tr>
                </thead>
                <tbody>
		<?php
		while($slog = $sql->fetch()) {
		echo '<tr>';
		echo '<td>'.$slog['action'].'</td>';
		echo '<td>'.$slog['ip'].'</td>';
		echo '<td>'.$slog['time'].'</td>';
		echo '<td>'.$slog['date'].'</td>';
		echo '</tr>';
		}
		echo '</tbody>
	</table>
	</div>
           </div>';
		
	}
	
			
	else {	
include("header.php");
include("menu.php");
?>
<div class="panel panel-danger">
              <div class="panel-heading">
                <h3 class="panel-title">Panel Administration</h3>
              </div>
              <ul>
              <div class="panel-body">
				<div class="show"><a id="ouvrir" href="#"><li><img src="img/adduser.png" /> <b>Ajouter un utilisateur</b></a></li></div>

				<section class="toggle_container">
				<form method="post" action="admin.php">
				<p>	
				<input class="form-control input-sm" style="width:180;height:30;" type="text" name="username" id="login" placeholder="Username" />
				<input class="form-control input-sm" style="width:180;height:30;" type="email" name="email" placeholder="Email" />
				<input class="form-control input-sm" style="width:180;height:30;" type="password" name="password" id="pass" placeholder="Password" />
				<input class="btn btn-primary" type="submit" id="submit" name="submitadduser" value="Valider" /><br />
				</p>
				</form>
				<a id="fermer" href="#"># Close</a>
				</section>
				<br />
				<table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="59%">Pseudo</th>
                    <th width="22%">Email</th>
                    <th width="10%">Logs</th>
                  </tr>
                </thead>
                <tbody>
                	<li><a href="commit.php">Changelog</a></li></ul>

   
   <?php
    $sql = $bdd->prepare('SELECT id, username, email FROM users WHERE username != :username');
    $sql->execute(array('username' => $_SESSION['username'])) or die;
	
	while($sadm = $sql->fetch()) {
			echo '<tr>';
			echo '<td>'.$sadm['username'].'</td>';
			echo '<td>'.$sadm['email'].'</td>';
			echo '<td><a href="admin.php?log='.$sadm['id'].'">Voir ses logs</a></td>';
			echo '</tr>';
			}
		
	echo '</tbody>
	</table>
	</div>
           </div>';
	
	}
	
}


else { header ("Location: index.php"); }

include("footer.php");
?>