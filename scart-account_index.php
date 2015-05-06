<?php 
/*-- filename: gahscart/ost_library/checkLlogin.php
      - Sun 16 Feb 2014 09:42:21 PST 
      - main index page.  Is the main login page.
      - sets/checks main session vars
 */
#--------------------------#
#   User variables         #
#--------------------------#
# include scart-account_login.php to setup
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
?>
<!doctype html>
<html>
  <head>
    <title>Book Store Main Page</title>
    <meta charset="UTF-8" content="">
  </head>
  <body>
  <?php
  // include('pwc_library/mywebdataconnect.inc.php');
  // connect_to_mywebdata();
     include('scart-account_login.php');
     $_SERVER['login_referrer']=$_SERVER['PHP_SELF'];  // failed retry return to
     if (isset($_SESSION['login_attempts'])) {
        $login_attempts=$_SESSION['login_attempts'];
        $login_attempts++;    // current attempts
        $_SESSION['login_attempts']=$login_attempts;  // attempt counter++
     } else {
        $_SESSION['login_attempts']=0;                 // attempt counter
     }
  ?>
  </body>
</html>