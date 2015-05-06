<?php
/*-- filename: gahscart/scart-account_options.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
      - Mon Dec 15 13:50:56 PST 2014
      - cloned from:gahscart/scart-manager_options.php
      - called from gahscart/scart-account_login.php
     RE; Implemented Some Customer Account Options
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_name = "scart_products";         // scart table product information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>Account Options</title>
   <meta charset="utf-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Account Options</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<!--  html data entry form new order with status and page alignment -->
   <p>
   <!--  <div class="tableContainer"> -->
   <table>
      <!--  html scart-account_options's page with css alignments -->
         <div class="tableRow">
            <label for="acct_admin"> Actions, </label>
               select an option from below and proceed!
         </div>
         <br />
      <!--  Option 1: orders review and display/print scart-account_options.php -->
         <div class="tableRow">
            <label for="acct_orders">Review Orders: </label>
                 <span>&nbsp;&nbsp;</span>
                 <button onclick="window.location.href='scart-account_history.php'">
                         All Orders</button>
         </div>
      <!--  Option 2: cart review and display/order scart-account_options.php -->
         <div class="tableRow">
            <label for="acct_cart">Review Cart: </label>
                 <span>&nbsp;&nbsp;</span>
                 <button onclick="window.location.href='scart-orders_status.php'">
                         Current Items</button>
         </div>
      <!--  Option 3: order items scart-account_options.php -->
         <div class="tableRow">
            <label for="acct_cart">Order Books: </label>
                 <span>&nbsp;&nbsp;</span>
                 <button onclick="window.location.href='scart-orders_status.php'">
                         Additional Items</button>
         </div>
         <br />
   <!--  </div> class="tableContainer"> -->
   </table>
   </p>
</div>

<!--  html end table and page reference to return to products index page -->
<p>
<button style="background-color:darkorange" onclick="window.location.href='scart-account_logout.php'">
        Cancel Account Admin and Logout</button><br /><br />
</p>
</body>

</html>
