<?php
/*-- filename: gahscart/scart-manager_options.php
      - Sun 11 May 2014 09:49:01 PDT
      - cloned from: gahscart/scart-account_register.php
      - called from updated gahscart/ost_library/checkLogin.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
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
   <title>Manager Options</title>
   <meta charset="utf-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Product and Customer Administration</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
   <p>
   <!--  <div class="tableContainer"> -->
   <table>
      <!--  html manager_options's page with css alignments -->
         <div class="tableRow">
            <label for="mgr_admin">Manager Options, </label>
               select below to proceed!
         </div>
         <br />
      <!--  product update functions selects scart-manager_products.php -->
         <div class="tableRow">
            <label for="mgr_prods">Product Updates: </label>
                 <span>&nbsp;&nbsp;</span>
                 <button onclick="window.location.href='scart-manager_products.php'">
                         Select for Updates</button>
         </div>
      <!--  product update functions selects scart-manager_products.php -->
         <div class="tableRow">
            <label for="mgr_updates">Customer Support: </label>
                 <span>&nbsp;&nbsp;</span>
                 <button onclick="window.location.href='scart-manager_customers.php'">
                         Select for Support</button>
         </div>
         <br />
   <!--  </div> class="tableContainer"> -->
   </table>
   </p>
</div>

<!--  html end table and page reference to return to products index page -->
<p>
<button style="background-color:darkorange" onclick="window.location.href='scart-account_logout.php'">
        Cancel Administration and Logout</button><br /><br />
</p>
</body>

</html>
