<?php
error_reporting(E_ALL);
session_start();
include("includes/config.php");
include("includes/functions.php");

if (isset($_SESSION['id']) && isset($_SESSION['username'])) {
	include("header.php");
}


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
  if ($_FILES["file"]["error"] > 0){
    echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
    }


    if (file_exists("img/avatar/" . $_FILES["file"]["name"]))
      {
      echo $_FILES["file"]["name"] . " already exists. ";
      }
    else
      {

      move_uploaded_file($_FILES["file"]["tmp_name"],"img/avatar/".$_FILES["file"]["name"]);
	$first = 'img/avatar/'.$_FILES["file"]["name"].'';
	$seconde = $_SESSION['id'];
	$sql = $bdd->prepare('UPDATE users SET avatar=:first WHERE id=:seconde');
	$sql->bindValue(':first', $first, PDO::PARAM_STR);
	$sql->bindValue(':seconde', intval($seconde), PDO::PARAM_INT);
	 $sql->execute();    
    }
  }else {
  echo "Invalid file";
  }

header ("Location: index.php"); 
?>