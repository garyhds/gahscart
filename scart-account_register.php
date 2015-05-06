<?php
/*-- filename: gahscart/scart-account_register.php
      - Wed 25 Dec 2013 09:03:52 PST 
      - cloned from: gahscart/scart-prod_index.php
      - updated for gahscart/include/scart_forms.js
     Intro PHP/SQL lesson 15 Final Project - Part 2
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
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>Account Register</title>
   <meta charset="utf-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Customer Account Registration</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<!--  html data entry form to register a new account -->
<div>
   <p>
   <!--  <div class="tableContainer"> -->
   <table>
      <!--  html data entry form new account status and page alignment -->
      <form name="register" onSubmit="return getFormData()" 
            action="scart-account_addentry.php" method="post">
         <div class="tableRow">
            <label for="registration">Registration Status: </label>
               Register Here - ALL Fields REQUIRED!
         </div>
      <!--  cust_fname input -->
         <div class="tableRow">
            <label for="cust_fname">First Name: </label>
               <input type="text" name="fname" id="cust_fname" size="12" placeholder="Jethro">
         </div>
      <!--  cust_lname input -->
         <div class="tableRow">
            <label for="cust_lname">Last Name: </label>
               <input type="text" name="lname" id="cust_lname" size="12" placeholder="Gibbs">
         </div>
      <!--  cust_address input -->
         <div class="tableRow">
            <label for="cust_address">Street Address: </label>
               <input type="text" name="address" id="cust_address" size="40" placeholder="14132 Firestone Blvd">
         </div>
      <!--  cust_city input -->
         <div class="tableRow">
            <label for="cust_city">City: </label>
               <input type="text" name="city" id="cust_city" size="30" placeholder="Thousand Oaks">
         </div>
      <!--  cust_state input -->
         <div class="tableRow">
            <label for="cust_state">State: </label>
               <input type="text" name="state" id="cust_state" size="20" placeholder="California">
         </div>
      <!--  cust_zip input -->
         <div class="tableRow">
            <label for="cust_zip">Full Zipcode: </label>
               <input type="text" name="zip" id="cust_zip" size="10" placeholder="74169-0360">
         </div>
      <!--  cust_phone input -->
         <div class="tableRow">
            <label for="cust_phone">Contact Telephone: </label>
               <input type="text" name="phone" id="cust_phone" size="20" placeholder="805-555-1213">
         </div>
      <!--  cust_email input -->
         <div class="tableRow">
            <label for="cust_email">Email Address: </label>
               <input type="text" name="email" id="cust_email" size="25" placeholder="fullname@somewhere.com">
         </div>
      <!--  cust_passwd input -->
         <div class="tableRow">
            <label for="cust_passwd">Account Password: </label>
               <input type="text" name="passwd" id="cust_passwd" size="8" placeholder="8 chars max">
         </div>
      <!--  html data entry form new account status and page alignment -->
      <!--  submit/check or edit/resubmit/accept input -->
         <div class="tableRow">
            <label for="submit">&nbsp;&nbsp;</label>
            <!--  <input type="submit" value="submit to register">   -->
            <input type="submit" id="submit" value="submit to register">
         </div>
      </form>
   <!--  </div> class="tableContainer"> -->
   </table>
   </p>
</div>

<!--  html end table and page reference to return to products index page -->
<p>
<button style="background-color:darkorange" onclick="window.location.href='scart-products_index.php'">
        Cancel Registration and Return to Main Page</button><br /><br />
</p>
</body>

</html>
