<html lang="en">
<!-- filename: gahscart/scart-orders_msgphpmail.php
      Fri 05 Dec 2014 12:29:31 PST 
      reference: http://www.tutorialspoint.com/php/php_sending_emails.htm
      - simple email confirmation message.  Order ID to login and print invoice.

Sending plain text email:
  mail ( to, subject, message, headers, parameters );
  to 	     Required. Specifies the receiver / receivers of the email
  subject    Required. Specifies the subject of the email. This parameter cannot contain any newline characters
  message    Required. Defines the message to be sent. Each line should be separated with a LF (\n).
		       Lines should not exceed 70 characters
  headers    Optional. Specifies additional headers, like From, Cc, and Bcc. 
		       The additional headers should be separated with a CRLF (\r\n)
  parameters Optional. Specifies an additional parameter to the sendmail program
 -->
<head>
   <title>Email Confimation Message</title>
   <meta charset="utf-8">
</head>
<body>
<?php
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie and session handling functions
   include("ost_library/checkCookies.php");
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
/* session and customer order variables for confirmation email  */
   $neworderid = $_SESSION['newOrder_ID'];      // SESSION variable next order id
   $custcartid = $_SESSION['custCart_ID'];      // SESSION variable cart items
   $emailaddress = $_SESSION['customer_email']; // SESSION variable customer email address

   $to = "garyh.ds@gmail.com";  // "garyh.ds@gmail.com"; // for custid 23
   $subject = 'Order Number '.$neworderid.' in-process';
   $message = "\nNew Order Number ".$neworderid." has been confirmed, ";
   $message .= "processed, and shipping is in process|\n\n";
   $message .= "TO email should be --".$emailaddress."-- for line3.\n\n";
   $header = "From:datasol@silcom.com \r\n";
   $header .= "Cc:datasol@silcom.com \r\n";
   $retval = mail ($to,$subject,$message,$header);
   if( $retval == true )  {
      // "Message sent successfully...";
      print '<script type="text/javascript">';
      print 'alert("Message sent successfully order number '.$neworderid.' ... \n\n")';
      print '</script>';  
   } else {
      // "Message could not be sent...";
      print '<script type="text/javascript">';
      print 'alert("Message could not be sent ... \n\n")';
      print '</script>';  
   }

/*  return page for confirmation email request */
   if ( isset($_GET['action']) && ($_GET['action'] == 'email_continue') ) {
	print '<script type="text/javascript">';
	print "window.location.href='scart-products_select.php'";
	print '</script>';
   } else {
	print '<script type="text/javascript">';
	print "window.location.href='scart-account_logout.php'";
	print '</script>';
   }
?>
</body>
</html>
