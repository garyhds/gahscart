<?php
/*-- filename: gahscart/scart-orders_status.php
 *-- User: garyh  * Date: 9/7/14  * Time: 11:27 PM
 *   # implement ost_library/checkCookies.php for all cookie handling functions
 *   include("ost_library/checkCookies.php");
 *-- Sat Jul  5 11:15:25 PDT 2014
 *   - new function to check JSON-Cookies for Shopping Cart Content, then return cart items
 *   - function retrieve_scartcookies ($selectedcustid)
 *   - window.location='scart-orders_checkout.php?".http_build_query($custCart)."'
 *-- Mon Mar 17 16:06:32 PDT 2014
 *   - new php script to check shopping for pending items
 *   - ntro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   Functions              #
#--------------------------#

#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie handling functions
  include("ost_library/checkCookies.php");
# implement ost_library/webdataconnect.inc.php OST mysql-server
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_orders   = "scart_neworders";  // scart table for new orders pending
   $table_products = "scart_products";   // scart table product information

# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>
<html lang="en">
<head>
   <title>Order Status Check</title>
   <meta charset="utf-8">
</head>
<body>
<!--  html page table headers to view current blog entries -->
<?php
# reset in scart-account_logout.php for session close
# ost phpinfo() server session.cache_expire Local=180, Master=180 // 180 minutes
# can reset local with session variable session_cache_expire(30); // 30 minutes
   // registered and logged in to scart system 
   if($_SESSION['login_success']="true"){	
      $txtCustID=$_SESSION['custID'];  // session variable current custid
      $custCart = retrieve_scartcookies($txtCustID); // retrieve current customer shopping cart
      $custCartID = $_SESSION['custCart_ID'];  // session array id
      $_SESSION[$custCartID] = $custCart;      // session array for current cart items
      $custCartlength=count($custCart);   // number of current items
      $_SESSION['cartItems_count'] = $custCartlength;  // number of current items
      // userid and pasword combination not found return to login with new login_attempts
      if($custCartlength<1){
	   $login_attempts=$_SESSION['login_attempts'];  // current attempts
	   $login_attempts++;                            // attempt counter++
	   $_SESSION['login_attempts'] = $login_attempts;  // next attempt
	   /* nothing in the current customer shopping cart array */ 
	   print '<script type="text/javascript">';
	   print '    alert("for customer '.$txtCustID
			    . $custCartlength.' existing items in cart!\n 
			     transferring to scart-products_select!")';
	   print '</script>';  
	   print '<script type="text/javascript">';
	   print "    window.location='scart-products_select.php';</script>";
	   print '</script>';  
         } else {
           print '<script type="text/javascript">';
	   print '    alert("for customer '.$txtCustID
			    . $custCartlength.' existing items in cart!\n 
			     transferring to scart-orders_checkout!")';
           print '</script>';
	   print '<script type="text/javascript">';
	   print "    window.location.href='scart-orders_checkout.php'";
	   print '</script>';
         }
   } else {
      // start vars test popup. should not be logged in, login_success != true
      $successmsg   ="login_success not-true. is = ";
      $successflag  =$_SESSION['login_success'];
      $attemptsmsg  ="login_attempts = ";
      $attemptvalue =$_SESSION['login_attempts'];
      $submitmsg="submit post is = ";
      $gomsg    ="go post is = ";
      $attemptcount=$_SESSION['login_attempts'];
      print '<script type="text/javascript">';
      print 'alert("'.$successmsg.$successflag.' !\n'.$attemptsmsg.$attemptvalue.' !\n'
                     .$submitmsg.$submit.' !\n'.$gomsg.$go.' !\n")';
      print '</script>';  
      // finish vars test popup. should not be logged in, login_success != true
   }
?>
</body>
</html>