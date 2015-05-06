<?php
/*-- filename: gahscart/scart-orders_msgconfirm.php
      Wed 03 Dec 2014 15:09:57 PST 
      reference: http://phpmailer.worxware.com/?pg=tutorial
      - simple email confirmation message.  Order ID to login and print invoice.
----------
	Save this as a php file and change the values to your values, of course. 
	Here is an explanation of what each stanza in this script does:
	- $mail->IsSMTP();  //  This sets up STMP-Server as method to send out email(s).
	
	- $mail->Host = "smtp.example.com";  // Just replace it with your own SMTP server address.
	 You can even specify more then one: just separate them with a semicolon (;):
	 "smtp.example.com;smtp2.example.com". If the first one fails, the second one will be used, instead.

	Enter the address that the e-mail should appear to come from.
	 You can use any address that the SMTP server will accept as valid.
	- $mail->From = "from@example.com";

	If displaying only an e-mail address in the "From" field is too simple, 
	you can add another line of code to give the address an associated name.
	This will add 'Your Name' to the from address, so that the recipient will 
	know the name of the person who sent the e-mail.  The following line
	- $mail->FromName = "Your Name"; \\ [Andy Prevost:] 

	with PHPMailer version 5.0.0, you can now achieve this with one command:
	- $mail->SetFrom("from@example.com","Your Name");

	The following will add the to address, the address to which the e-mail will be sent.
	  You must use a valid e-mail here, of course, if only so that you can verify that
	  your PHPMailer test worked. It's best to use your own e-mail address here for this
	  inintial test. As with the "From" field, you may provide a name for the recipient.
	This is done somewhat differently:
	  $mail->AddAddress("myfriend@example.net","Friend's name");
	  $mail->AddAddress("myfriend@example.net");

	Setting the subject and body is done next. $mail->WordWrap = 50;
	  is a feature of PHPMailer to word-wrap your message limiting all lines to a
	  maximum length of X characters, even if not set as body= .... 
	Admittedly, there's not much point in wrapping the text in a message as brief
	  as the one in this example.

	Finally, we send out the e-mail, once all necessary information has been provided. 
	This is done with $return = $mail->Send();. 
	In this example script, it's combined with an error message;
	  if Send() fails, it'll return false and you can catch it and display an error message.
	  This is done in the last lines. Or, in case of success, it displays a kind of "done " message.

 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie and session handling functions
   include("ost_library/checkCookies.php");
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   $table_products    = "scart_products";   // scart table product information
# order infornmation pending updates
   $table_customers   = "scart_customers";    // scart table customer information
   $table_purchases   = "scart_purchases";    // scart table pending order information
   $table_purchdetail = "scart_purchdetail";  // scart table pending order items 
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>
<html lang="en">
<head>
   <title>Email Confimation Message</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries
  <h1>Book Store Orders Shopping Cart</h1>
	<br/> Email home page check .... <br/>
	<br/>  .... home page .... <br/>
	<br/> Email diagnostics should be next .... <br/>
 -->
<?php     //    mysql_close($db);
/* php current session variables    */

// require("include/PHPMailer-master/class.phpmailer.php");
require("include/PHPMailer-master/PHPMailerAutoload.php");

$mail = new PHPMailer();

// Use the debug functionality of the class to see what's going on in your connections.
//  To do that, set the debug level in your script. For example:

/*
$mail->SMTPDebug = 1;
$mail->isSMTP();  // telling the class to use SMTP
$mail->SMTPAuth   = true;                // enable SMTP authentication
$mail->Port       = 26;                  // set the SMTP port
$mail->Host       = "mail.fglinc.com"; // SMTP server
$mail->Username   = "garyh@fglinc.com"; // SMTP account username
$mail->Password   = "853_Corp";     // SMTP account password

// Notes on this:
// $mail->SMTPDebug = 0; ... will disable debugging (you can also leave this out completely, 0 is the default)
// $mail->SMTPDebug = 1; ... will echo errors and server responses
// $mail->SMTPDebug = 2; ... will echo errors, server responses and client messages

*/
$mail->IsSMTP();  // telling the class to use SMTP
$mail->Host     = "smtp.impulse.net"; // SMTP server

$mail->From     = "datasol@silcom.com";
$mail->AddAddress("datasol@silcom.com");

$mail->Subject  = "First PHPMailer Message";
$mail->Body     = "Hi! \n\n This is my first e-mail sent through PHPMailer.";
$mail->WordWrap = 50;

if(!$mail->Send()) {
  echo 'Message was not sent.';
  echo 'Mailer error: ' . $mail->ErrorInfo;
} else {
  echo 'Message has been sent.';
}

?>

<!--  session_start at top before any html code, Print $_session array
	<br/> Email diagnostics should be above .... <br/>
	<br/> End home page mail check .... <br/>
 -->
</body>
</html>
