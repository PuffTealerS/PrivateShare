<?php


include("header.php");
include("menu.php");

echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-2" />
<title>Contact - PrivateShare</title>
</head>
<body>
<center>
<div id="contact">
<form id="cform" method="post" action="contact_mail.php">
 <h3 id="fc_titlu">Contact</h3>
 <table id="contact">
 	<tr>
	 	<td><label for="nume">Pseudo: &nbsp;</label></td>
	 	<td><input type="text" name="nume" id="nume" size="18" maxlength="40"/></td>
 	</tr>
 	<tr>
	 	<td><label for="email">Mail: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td>
	 	<td><input type="text" name="email" id="email" size="18" maxlength="58"/></td>
 	</tr>
 	<tr>
	 	<td><label for="subject">Sujet: &nbsp;&nbsp;&nbsp;</label></td> 
	 	<td><input type="text" name="subject" id="subject" size="18" maxlength="70"/></td>
 	</tr>

 </table>
 <br/><label for="message">Message: (<i>maximum 500 caractères maximum</i>)</label><br/>
 <textarea name="message" id="message" cols="35" rows="6"></textarea></br>
 <input type="hidden" name="anti_spam" id="anti_spam" value="" /><br />
 <i>Vérification code:</i> <b id="codas"> </b><br />
 <p>Veuillez rentrer le code de vérification</p>
 <input type="text" name="anti_spam1" id="anti_spam1" value="" size="7" maxlength="7" /><br />
 <input type="submit" value="Send" id="csubmit" />
</form>
<script type="text/javascript" src="contact/contact.js"></script>
</div>
</center>
</body>
</html>';


?>