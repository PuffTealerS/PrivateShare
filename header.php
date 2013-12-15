<!DOCTYPE html>
<html lang="en">
  <head>
    <title><?php echo $sitename; ?></title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/bootstrap.css" media="screen">
    <link rel="stylesheet" href="css/font-awesome.min.css">
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script type="text/javascript">
	$(document).ready(function(){
	var $contenu = $('.toggle_container');
	$contenu.hide();
	$('div.show').click(function(){
	$(this).toggleClass('active').next().slideToggle('slow');
	return false;
	});
	$('#ouvrir').click(function() {
	$contenu.show('slow');
	return false;
	});
	$('#fermer').click(function() {
	$contenu.hide('slow');
	return false;
	});
	});
	</script>
  </head>
  <body>