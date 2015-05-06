<?php
/*-- filename: gahscart/scart-account_login.php
      - Sun 26 Jan 2014 14:17:06 PST  
      - copy/mofify gahscart/scart-account_register.php
     Session20140126_login-formsref
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   $table_name = "scart_customers";         // scart table customer information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
   // $_SESSION['login_success']="";  // reset login success session variable
   // session_unset();   // unset($_SESSION['name']); // will delete just the name data
   session_destroy(); // will delete ALL data associated with that user.
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>Account Login</title>
   <meta charset="utf-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Customer Account Login</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<!--  html login form for a registered customer account -->
<table width="400" border="0" cellpadding="0" cellspacing="1">
   <tr>
      <!--  html data entry form account lgoin and page alignment -->
      <form name="login" onSubmit="return getLoginData()" 
            action="ost_library/checkLogin.php" method="post">
      <!--  <form name="form1" method="post" action="checklogin.php"> -->
      <td>
         <table width="100%" border="0" cellpadding="3" cellspacing="1">
            <tr>
               <td colspan="3"><strong>Member Login </strong></td>
            </tr>
            <tr>
               <!--  cust_userid input -->
               <td colspan="3">
                  <label for="cust_userid">Account Username: &nbsp;</label>
                     <input type="text" name="userid" id="cust_userid" size="8" placeholder="8 chars max">
               </td>
            </tr>
            <tr>
               <!--  cust_passwd input -->
               <td colspan="3">
                  <label for="cust_passwd">Account &nbsp;Password: &nbsp;</label>
                     <input type="text" name="passwd" id="cust_passwd" size="8" placeholder="8 chars max">
               </td>
            </tr>
            <tr>
               <!--  submit login input or bail out for more something  -->
               <td>
                  <label for="submitlog">&nbsp;&nbsp;</label>
                     <input type="submit" name="submitlog" id="submitlog" value="login">
               </td>
               <td>
                  <label for="submitreg">&nbsp;&nbsp;</label>
                     <input type="button" id="submitreg" 
                            onClick="window.location.href='scart-account_register.php?remarks='" 
                            value="register">
               </td>
               <td>
                  <label for="submitret">&nbsp;&nbsp;</label>
                     <input type="button" id="submitret" 
                            onClick="getLoginInfo()" value="retrieve password">
               </td>
            </tr>
         </table>
      </td>
               <!--  submit logon input username and password entered  -->
               <input type="hidden" name="go" value="go">
      </form>
   </tr>
</table>

<!--  html end table and page reference to return to products index page -->
<p>
<button style="background-color:darkorange" onclick="window.location.href='scart-products_index.php'">
        Cancel Registration and Return to Main Page</button><br /><br />
</p>
</body>

</html>
