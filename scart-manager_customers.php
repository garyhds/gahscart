<?php
/*-- filename: gahscart/scart-manager_customers.php
      - Thu 22 May 2014 15:26:36 PDT
      - cloned from gahscart/scart-manager_products.php
      - called from updated gahscart/scart-manager_options.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_name = "scart_products";        // scart table product information
   // $table_orders   = "scart_neworders";  // scart table pending order information
   // $table_products = "scart_products";   // scart table product information
   $table_customers = "scart_customers";    // scart table customer information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>The Customer List</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Customer Accounts</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<div>
   <!-- was: href="../lesson10-12_blog/lab10_o1-blogentry.html" -->
   <!-- was: <a href="blogaddform.php">Add new blog entry</a><br /><br /> -->
   <button onclick="window.location.href='scart-manager_options.php'">
           Cancel and Return to Options</button>
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
   <button onclick="window.location.href='scart-account_logout.php'">
           Cancel Session and Logout</button><br /><br />
</div>
<!--  html data entry form new order with status and page alignment -->
<table border="1">
   <tr>
      <th>Select</th>
      <th>ID</th>
      <th>First Name</th>
      <th>Last Name</th>
      <th>Account Status</th>
      <th>Date Status</th>
   </tr>

<?php     //    mysql_close($db);
   /* php current blog entry listing with link to detail    */
   $sesscustid = $_SESSION['custID'];      // => 23/
   $login_time = $_SESSION['login_time'];  // [login_time] => 2014-03-27 18:27:04
   $command = "select * from $table_customers order by custid;";
   // echo "<br /> $command <br />";
   $result = mysql_query($command);
   $num_rows = mysql_num_rows($result);
?>

<div>
<p>
   <!--  html data entry form new account status and page alignment -->
   <form name="product" onSubmit="return getProductData()" 
         action="scart-manager_custview.php?action=details" method="post">
<?php     //    mysql_close($db);
      $rowcounter=0;  // initialize row counter for radiobuttonid
      while ($data = mysql_fetch_object($result)) {
         // column 1 radio button id == radio$rowcounter
         $rowcounter++;  // starts at 1
         $rownumber=sprintf("%02d",$rowcounter);   // starts at 01
         /* <input type="radio" name="fb" id="fb" value="Football" />
              <label for="fb">Football</label>
          */
         // $radioid="radio".$rownumber;  // e.g. radio01
         // column 1 radio button, default unselected selects only 1 of all in form same "id"
         $radioid="radio";  // needs to be same name for one select from form
         print "<tr>";
         print '<td align="center">';
         print '<label for="'.$radioid.'"></label>';
         print '   <input type="radio" name="'.$radioid.'" id="'.$radioid.'"  value="'.$rownumber.'">';
         print "</td>";
         // columns 2-5 product and/or order information
         print "<td>".$data->custid."</td>";
         print "<td>".$data->cust_fname."</td>";
         print "<td>".$data->cust_lname."</td>";
         print "<td>".$data->cust_status."</td>";
         print "<td>".$data->cust_stamp."</td></tr>\n";
         // variables for POST array on submit to order cart, table scart_neworders
         print '<input type="hidden" name="custid'.$rownumber.'" 
                       id="custid'.$rownumber.'" value="'.$data->custid.'">';
         print '<input type="hidden" name="row_numbers" id="row_numbers" value="'.$rownumber.'">';
      }    
         /*--  submit edit selected --*/
         print '<tr><td colspan="2">';
         print '   <label for="submitview">&nbsp;&nbsp;</label>
                   <input type="submit" name="submitview" id="submitview" value="view selected">';
         print "</td>";
         /*--   submit add new item --*/
         print '<td colspan="4">';
         // print '<button onclick="window.location.href=\'scart-manager_custview.php?action=purchases\'">
         //               include selected purchases</button>';
         print '   <label for="submitview">&nbsp;&nbsp;</label>
                   <input type="submit" name="submitview" id="submitdetails" value="include selected purchases">';
         print "</td>";
         // print '"<td colspan="2"></td></tr>"';
?>
   </form>
<!--  </div> class="tableContainer"> -->
</p>
</div>
<?php
//    print "Record successfully inserted into $table_name. <br>";
/*-- before implementation.  now in library include file
    mysql_close($db);
 */
    mysql_close($connID);
?>

<!--  html end table and page reference to add new blog entries -->
</table>
</body>
<!--  session_start at top before any html code, get any variables set? -->
<!--  session_start at top before any html code, Print $_session array -->
</html>
