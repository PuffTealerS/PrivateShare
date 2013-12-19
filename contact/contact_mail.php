<?php
// Contact Form - http://coursesweb.net/php-mysql/
if(!isset($_SESSION)) session_start();        // starts session, if not already started
if(!headers_sent()) header('Content-type: text/html; charset=utf-8');            // sets header for UITF-8 encoding

/** HERE ADD YOUR DATA **/

$cmail['to'] = '2000salsa@gmail.com';			// Receiver e-mail address (to which the email will be send)

// If you want to use the SMTP server from GMail, set the value 1 at GMAIL constant
// Add your GMail address at the GMAIL_USER, and add the password for this e-mail at GMAIL_PASS
// If you want to use the local mail server, let GMAIL to 0
define('GMAIL', 1);
define('GMAIL_USER', '2000salsa@gmail.com');
define('GMAIL_PASS', '$13126Yellow');

      /* From here no need to modify */

// Check the anti-spamm code
if(isset($_POST['anti_spam']) && isset($_POST['anti_spam1']) && $_POST['anti_spam']==$_POST['anti_spam1']) {
  // Check if all necessary data are received by post
  if(isset($_POST['nume']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['message'])) {
    // removes external whitespace and tags
    $_POST = array_map("trim", $_POST);
    $_POST = array_map("strip_tags", $_POST);

    // gets form data
    $cmail['nume'] = $_POST['nume'];
    $cmail['from'] = $_POST['email'];
    $cmail['subject'] = $_POST['subject'];
    $cmail['body'] = 'E-mail from Contact form, sent by: '. $cmail['nume'] ."\n His /Her e-mail address: ". $cmail['from']. "\n IP: ". $_SERVER['REMOTE_ADDR']. "\n\n"
      .'message: '. $_POST['message']; 

    // include the class that sends the email
    include('sendmail/class.sendemail.php');

    // create an object of sendEMail class that sends the email
    $obEMail = new sendEMail($cmail);
    $re = $obEMail->re;
  }
  else $re = 'Error: not all form fields.';
}
else $re = 'Error: Incorrect verification code.';

echo $re;           // output the resulted message