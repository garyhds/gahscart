<?php
/*-- filename: gahscart/scart-account_logout.php
      - Sat Jan 10 13:14:06 PST 2015
      - enhanced logout routine. to save and destroy session vars
        - either save before destroying session vars
        - or don't save before destroying session vars
      - complements library routine.  checkLogin and set session vars
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie handling functions
   include("ost_library/checkCookies.php");
# setup in ost_library/checkLogin.php login success
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#

   if ( isset($_GET['action']) && $_GET['action'] == "scart_store" ) {
      // - either save before destroying session vars
      $current_action = $_GET['action'];
      $data = get_cartvars();   // individual cart line items
      if ( count($data) > 0 ) {
         $saved_items = savesession_scartcookies($data);
	 print '<script type="text/javascript">';
	 print 'alert(" -- Logging Out! --\n Shopping Cart action = '
			.$current_action.' with '.$saved_items.' items Saved Temporarily!\n")';
	 print '</script>';
      } else {
	 print '<script type="text/javascript">';
	 print 'alert(" -- Logging Out! --\n Shopping Cart action = '
			.$current_action.' with cart empty No items Saved Temporarily!\n")';
	 print '</script>';
      }
   } elseif ( isset($_GET['action']) && $_GET['action'] == "scart_clear" )  {
      // - remove cookie before destroying session vars
      $current_action = $_GET['action'];
      // $return_val2 = 10; // temporary while testing function calls
      $return_val2 = remove_scartcookies(); // ost_library/checkCookies.php function result
      if (!$return_val2 > 0) {
	  print '<script type="text/javascript">';
	  print 'alert(" -- Failed! --\n Shopping Cart Temporary Storage contents = '
	                 .$return_val2.' all items NOT Cleared !\n")';
	  print '</script>';
      } else {
	  print '<script type="text/javascript">';
	  print 'alert(" -- Success! --\n Shopping Cart Temporary Storage contents = '
	                 .$return_val2.' all items Cleared !\n")';
	  print '</script>';
      }
   } elseif ( !isset($_GET['action']) )  {
      // - just logout and leave the session
      $current_action = "Leaving Store";
      print '<script type="text/javascript">';
      print 'alert(" -- Manager Logging Out! --\n Shopping Cart action = - '
		    .$current_action.' -\n Shopping Cart(s) Left As-Is!\n")';
      print '</script>';
   }

   $_SESSION['login_success']="";
   session_unset();
   session_destroy();
?>
<html lang="en">
<head>
   <title>OST Logout</title>
   <meta charset="utf-8">
</head>
<body>
   <div align=center>

<?php
   echo '<h2>Logout is Complete</h2>';
   echo '<h3>Have a Nice Day!</h3>';
   //echo "<meta http-equiv=\"refresh\" content=\"0;url=index.php\">";
   echo "<a href=scart-account_login.php>Please Click Here To Login</a>";
?>

   </div>
</body>
</html> 
