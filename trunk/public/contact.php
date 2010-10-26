<?php
// contact form 
// url's :
//			contact/send
//			contact/

// load menu
$core->view->use_page('menu');

if ( $action == 'send' )
{
	// form module should be applied here
	$message = 
		"naam : " 		. $_POST['naam'] ."\n".
		"voornaam : " 	. $_POST['voor'] ."\n".
		"straat : " 	. $_POST['straat'] ."\n".
		"nr : " 		. $_POST['nr'] ."\n".
		"postcode : " 	. $_POST['postcode'] ."\n".
		"plaats : " 	. $_POST['plaats'] ."\n".
		"tel : " 		. $_POST['tel'] ."\n".
		"email : " 		. $_POST['email'] ."\n".
		"extra : " 		. $_POST['extra'] ."\n";
		
	$headers = 'From: webmaster@fortunes.be' . "\r\n" .
			   'Reply-To: webmaster@fortunes.be' . "\r\n" .
			   'X-Mailer: PHP/' . phpversion();
	
	mail('info@fortunes.be', 'contact via fortunes site op' . date('Js') , $message, $headers);

	$core->view->use_page('mail_send_page');
}

// no submit yet
else
{
	// load form
	$core->view->use_page('contact_form');
}
?>