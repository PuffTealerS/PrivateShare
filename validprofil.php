  <?php
  error_reporting(E_ALL);
  session_start();
  include("includes/config.php");
  include("includes/functions.php");

  // --- Si identifie
  //
  if (isset($_SESSION['id']) && isset($_SESSION['username'])) {

    include("header.php");

  }


  // --- Upload de l'avatar
  //

  // S'il y a un fichier
  if ($_FILES["file"]["name"]!=NULL) {
    
      // Verification extension fichier
      $allowedExts = array("gif", "jpeg", "jpg", "png");
      $temp = explode(".", $_FILES["file"]["name"]);
      $extension = end($temp);
      if ((($_FILES["file"]["type"] == "image/gif")
        || ($_FILES["file"]["type"] == "image/jpeg")
        || ($_FILES["file"]["type"] == "image/jpg")
        || ($_FILES["file"]["type"] == "image/pjpeg")
        || ($_FILES["file"]["type"] == "image/x-png")
        || ($_FILES["file"]["type"] == "image/png"))
        && ($_FILES["file"]["size"] < 60000)
        && in_array($extension, $allowedExts))
      {

        // Si erreur
        if ($_FILES["file"]["error"] > 0){

          echo "Return Code: " . $_FILES["file"]["error"] . "<br>";

        }

        // Si fichier deja existant
        if (file_exists($_FILES["file"]["name"])){

            echo $_FILES["file"]["name"] . " déjà existant ";

        }
        else{

          // On enregistre le fichier  
          move_uploaded_file($_FILES["file"]["tmp_name"],"img/avatar/".$_FILES["file"]["name"]);
          $first = $_FILES["file"]["name"];
          $seconde = $_SESSION['id'];
          $avatar_return = "Avatar - ".$_FILES["file"]["name"]." à été uploadé";
          $sql = $bdd->prepare('UPDATE users SET avatar=:first WHERE id=:seconde');
          $sql->bindValue(':first', $first, PDO::PARAM_STR);
          $sql->bindValue(':seconde', intval($seconde), PDO::PARAM_INT);
          $sql->execute();

        }

      }
      else{

         echo "Invalid file";
      }
    
  }
  else{

   $avatar_return = "Avatar - pas de modification";
   
  }

  // --- S'il y a modification du mot de passe
  //

  if ($_POST['oldp']!=NULL || $_POST['newp1']!=NULL || $_POST['newp2']!=NULL) {

    if ($_POST['newp1']==$_POST['newp2']) {

      $mdp_sql = $bdd->prepare('SELECT password FROM users where id=:id_user');
      $mdp_sql->bindValue(':id_user', $_SESSION['id'], PDO::PARAM_INT);
      $mdp_sql->execute();
      $result_sql = $mdp_sql->fetch();
      $oldp = sha1($_POST['oldp']);

      if ($oldp=$result_sql) {
          $taille_mdp = strlen($_POST['newp1']);
            if ($taille_mdp > 5) {

              $sql_mdp = $bdd->prepare('UPDATE users SET password=:mdp WHERE id=:seconde');
              $sql_mdp->bindValue(':mdp', sha1($_POST['newp1']), PDO::PARAM_STR);
              $sql_mdp->bindValue(':seconde', $_SESSION['id'], PDO::PARAM_INT);
              $sql_mdp->execute();

              $return_mdp = 'Mot de passe - modfication effectuée';   

            } 
            else {

              $return_mdp = 'Mot de passe - nouveau mot de passe trop court';
            }
      } 
      else {

        $return_mdp = 'Mot de passe - ancien mot de passe incorrect';
      }

    } 
    else {

      $return_mdp = 'Mot de passe - les 2 mots de passe ne sont pas identiques';
    }

  } 
  else {

    $return_mdp = 'Mot de passe - pas de modification';
  }


  // --- S'il y a modification du mail
  //

  if ($_POST['newm']!=NULL) {

    if(!filter_var($_POST['newm'], FILTER_VALIDATE_EMAIL)) {

      $return_mail = 'Mail - adresse mail incorrecte';
    }
    else {
              $sql_mail = $bdd->prepare('UPDATE users SET email=:mail WHERE id=:seconde');
              $sql_mail->bindValue(':mail', $_POST['newm'], PDO::PARAM_STR);
              $sql_mail->bindValue(':seconde', $_SESSION['id'], PDO::PARAM_INT);
              $sql_mail->execute();
              $return_mail = 'Mail - modification effectuée : '.$_POST['newm'];
    }
  }
  else {

    $return_mail = 'Mail - pas de modification';
  }

  echo '<center>
          <div id="modifprofil">
            <p>'.$avatar_return.'</p>
            <p>'.$return_mail.'</p>
            <p>'.$return_mdp.'</p>
            </br>
            <p id="redirection">Redirection en cours ...</p>
          </div>
        </center>';
  
  
  header ("Refresh: 3;URL=compte.php"); 
  ?>