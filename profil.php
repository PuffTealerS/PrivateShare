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
if(!empty($_POST['submit'])) {
$cpassword = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : null;
$newpassword = isset($_POST['passwordnew']) ? htmlspecialchars($_POST['passwordnew']) : null;
$newpassword2 = isset($_POST['passwordnewverification']) ? htmlspecialchars($_POST['passwordnewverification']) : null;
$email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : null;

//Email
if(empty($newpassword)&&empty($newpassword2)&&!empty($email)) {
$sqlp = $bdd->prepare('SELECT password FROM users WHERE id = :id');
$sqlp->execute(array(':id' => $_SESSION['id']));
$sqlpass = $sqlp->fetch();

$pass_hache = sha1($cpassword);

if($sqlpass['password']==$pass_hache) {
$sqlemail = $bdd->prepare('UPDATE users SET email = :email WHERE id = :id');
$sqlemail->execute(array('email' => $email, 'id' => $_SESSION['id']));
header ("Refresh: 3;URL=profile.php");
include("header.php");
include("menu.php");
echo '<div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Panel d\'informations de '.$_SESSION['username'].'</h3>
              </div>
              <div class="panel-body">
			  <div class="col-lg-4">
				<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Well done!</strong> You successfully changed <u>your email</u>!
				</div>
			</div>		  
			</div>
           </div>';
}
else {
header ("Refresh: 3;URL=profile.php");
include("header.php");
include("menu.php");
echo '<div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Panel d\'informations de '.$_SESSION['username'].'</h3>
              </div>
              <div class="panel-body">
			  <div class="col-lg-4">
				<div class="alert alert-dismissable alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Oh snap!</strong> <u>Your password is incorrect</u>.
				</div>
			</div>		  
			</div>
           </div>';
}
}

elseif($newpassword==$newpassword2) {
$sqlp2 = $bdd->prepare('SELECT password FROM users WHERE id = :id');
$sqlp2->execute(array(':id' => $_SESSION['id']));
$sqlpass = $sqlp2->fetch();

$pass_hache = sha1($cpassword);

if($sqlpass['password']==$pass_hache) {
$pass_hash = sha1($newpassword);
$sqlnpass = $bdd->prepare('UPDATE users SET password = :password, email = :email WHERE username = :username');
$sqlnpass->execute(array('password' => $pass_hash, 'email' => $email, 'username' => $_SESSION['username']));
header ("Refresh: 3;URL=profile.php");
include("header.php");
include("menu.php");
echo '<div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Panel d\'informations de '.$_SESSION['username'].'</h3>
              </div>
              <div class="panel-body">
			  <div class="col-lg-4">
				<div class="alert alert-dismissable alert-success">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Well done!</strong> You successfully changed <u>your email and password</u>!
				</div>
			</div>		  
			</div>
           </div>';
}
else {
header ("Refresh: 3;URL=profile.php");
include("header.php");
include("menu.php");
echo '<div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Panel d\'informations de '.$_SESSION['username'].'</h3>
              </div>
              <div class="panel-body">
			  <div class="col-lg-4">
				<div class="alert alert-dismissable alert-danger">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Oh snap!</strong> <u>Your password is incorrect</u>.
				</div>
			</div>		  
			</div>
           </div>';
}
}

else {
header ("Refresh: 3;URL=profile.php");
include("header.php");
include("menu.php");
echo '<div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Panel d\'informations de '.$_SESSION['username'].'</h3>
              </div>
              <div class="panel-body">
			  <div class="col-lg-4">
				<div class="alert alert-dismissable alert-warning">
				<button type="button" class="close" data-dismiss="alert">&times;</button>
				<strong>Warning!</strong> <u>Your password and password verification are different</u>.
				</div>
			</div>		  
			</div>
           </div>';
}

}

else {
include("header.php");
include("menu.php");

$sql = $bdd->prepare('SELECT email FROM users WHERE id = :id');
$sql->execute(array(':id' => $_SESSION['id']));
$sqlemail = $sql->fetch();

?>
		  <div class="panel panel-info">
              <div class="panel-heading">
                <h3 class="panel-title">Panel d'informations de <?php echo $_SESSION['username']; ?></h3>
              </div>
              <div class="panel-body">
			  
				<!-- Panel CONTENT -->
				<div class="show"><a id="ouvrir" href="#"><img src="img/edit_profile.png" /> <b>Modifier mon profil</b></a></div>
				<section class="toggle_container">
				<form method="post" action="profile.php">
				<p>
				<input class="form-control input-sm" style="width:180;height:30;" type="email" name="email" value="<?php echo $sqlemail['email']; ?>" /> 
				<input class="form-control input-sm" style="width:180;height:30;" type="password" name="password" id="inputSmall" placeholder="Current password" />
				<input class="form-control input-sm" style="width:180;height:30;" type="password" name="passwordnew" id="inputSmall" placeholder="New password" />
				<input class="form-control input-sm" style="width:180;height:30;" type="password" name="passwordnewverification" id="inputSmall" placeholder="New password verification" />
				<input type="submit" class="btn btn-primary" id="submit" name="submit" value="Valider" /><br />
				</p>
				</form>
				<a id="fermer" href="#"># Close</a>
				</section><br />
				<table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th width="71%">Action</th>
                    <th width="12%">IP</th>
                    <th width="8%">Heure</th>
                    <th width="9%">Date</th>
                  </tr>
                </thead>
                <form action="uploadpicture.php" method="post"
				enctype="multipart/form-data">
				<label for="file">Ajouter un avatar:</label>
				<input type="file" name="file" id="file">
				<input type="submit" name="submit" value="Envoyer"><br>
				</form>

                <tbody>
<?php
$sqllog = $bdd->prepare('SELECT action, date, time, ip FROM logs WHERE uid = :uid ORDER BY date AND time');
$sqllog->execute(array('uid' => $_SESSION['id'])) or die(header ('Location: profile.php'));
echo '</br>';
while($slog = $sqllog->fetch()) {
		echo'<tr>
				<td>'.$slog['action'].'</td>
				<td>'.$slog['ip'].'</td>
				<td>'.$slog['time'].'</td>
				<td>'.$slog['date'].'</td>
			</tr>';
}
echo '</tbody>
	</table>
	</div>
           </div>';
}
}
//Sinon
else { header ("Location: login.php"); }

include("footer.php");
?>