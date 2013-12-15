<?

error_reporting(E_ALL);
session_start();

include("includes/config.php");
include("includes/functions.php");
include("menu.php");
// --- Si deja identifie 

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
	include("header.php");
}

$testsql=$bdd->prepare("SELECT DISTINCT version  FROM  `changelog` ORDER BY  `changelog`.`datelog` DESC ");
$testsql->execute();




while($test = $testsql->fetch()) {

	//$date = new DateTime($test['datelog']);
	// - 
	echo '</br>';
	echo '<center><div id="changelog">';
	echo '</br>';	
	echo '<p id="titrelog">Version : '.$test['version'].'</a></p>';
	echo '<ul>';
	

	$sql=$bdd->prepare("SELECT * FROM  `changelog` WHERE version='".$test['version']."' ORDER BY  datelog DESC ");
	$sql->execute();
	
	while ($result = $sql->fetch()) {
		$date = new DateTime($result['datelog']);
		echo '<center><li><p id="log">'.$date->format('d/m/y').' - '.nl2br($result['commentaire']).'</p></li></center>';
	}
echo '</br></ul></div>
		</center>';
}



?>