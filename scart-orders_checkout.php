<?php
/*-- filename: gahscart/scart-orders_checkout.php
      Wed 26 Nov 2014 09:50:51 PST
      - rebuild cart array from $_SESSION vars, was $_GET vars
      - line 81    $data = array();   // individual cart line items
      Sun Jul  6 14:15:25 PDT 2014
      - new functionality to include JSON-Cookies for Multiple-item Shopping Cart Contents
       - function retrieve_scartcookies ($selectedcustid)
       - window.location='scart-orders_checkout.php?".http_build_query($custCart)."'
      Wed Mar 26 15:18:03 PDT 2014 
      - cloned from gahscart/scart-products_select.php
      - updated for selecting a product for the shopping cart
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie handling functions
   include("ost_library/checkCookies.php");
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_orders   = "scart_neworders";  // scart table pending order information
   $table_products = "scart_products";   // scart table product information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>
<html lang="en">
<head>
   <title>The Cart List</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Orders Shopping Cart</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<div>
   <!-- was: href="../lesson10-12_blog/lab10_o1-blogentry.html" -->
   <!-- was: <a href="blogaddform.php">Add new blog entry</a><br /><br /> -->
   <button onclick="window.location.href='scart-account_logout.php?action=scart_store'">
           Save Cart and Logout</button>
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
   <button onclick="window.location.href='scart-account_logout.php?action=scart_clear'">
           Cancel Cart and Logout</button><br /><br />
</div>
<!--  html data entry form new order with status and page alignment -->
<table border="1">
   <tr>
      <th><span>Item<br />No.</span></th>
      <th><span>Delete<br />Item</span></th>
      <th><span>Update<br />Qty</span></th>
      <th><span>New <br />Qty</span></th>
      <th><span>Part<br />No.</span></th>
      <th>Book Title</th>
      <th>Price</th>
      <th>Entered Date</th>
   </tr>

   <?php     //    mysql_close($db);
   /* php current session variables    */
      $sesscustid = $_SESSION['custID'];      // => 23
      $login_time = $_SESSION['login_time'];  // [login_time] => 2014-03-27 18:27:04
      $cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
      $num_rows = count($_SESSION[$cart]);
   // retireve cart item contents array
   $data = get_cartvars();   // individual cart line items
   ?>

   <div>
   <p>
   <!--  html data entry form new account status and page alignment -->
   <form name="order" onSubmit="return getOrderData()" 
         action="scart-products_addorder.php?action=checkout" method="post">
   <?php     //    mysql_close($db);

/* ------------------------------------------- */
   // display associative array of cart items
   $rowcounter = 0; // initialize to array index start
   for ($row = 0; $row < count($data); $row++) {
       $rowcounter++;
       $rownumber=sprintf("%02d",$rowcounter);   // starts at 01
       // column 1 cart item (row) number
          print "<tr><td>".$rownumber."</td>";
       // column 2 radio button, default unselected selects only 1 of all in form same "id"
       // $raddelid="raddel.$rownumber;  // e.g. raddel01
       $raddelid="raddel"; // needs to be same name for one select from form
          print "<td>";
          print '<label for="'.$raddelid.'"></label>';
          print '   <input type="radio" name="'.$raddelid.'" id="'.$raddelid.'" value="'.$rownumber.'">';
          print "</td>";
       // column 3 radio edit row quantity button, default unselected selects only 1 of all in form same "id"
       // $radeditid="radedit".$rownumber;  // e.g. radedit01
       $radeditid="radedit"; // needs to be same name for one select from form
          print "<td>";
          print '<label for="'.$radeditid.'"></label>';
          print '   <input type="radio" name="'.$radeditid.'" id="'.$radeditid.'" value="'.$rownumber.'">';
          print "</td>";
       // column 4 edit quantity input, default initial quantity
       $qtyid="qty".$rownumber;
       $rowqty = $data[$row]['qty']; // cookie (json) row quantity
          print "<td>";
          print '<label for="'.$qtyid.'"></label>';
          print '   <input type="text" name="'.$qtyid.'" id="'.$qtyid.'" size="2" value="'.$rowqty.'">';
          print "</td>";
       // column 5 row product part number
       $ords_bookid="ords_bookid".$rownumber;
       $rowbookid = $data[$row]['bookid']; // cookie (json) row quantity
          print "<td>".$rowbookid."</td>";
          print '<input type="hidden" name="'.$ords_bookid.'" value="'.$rowbookid.'">';
       // column 6 and 7 row product description and price
       $currentbookid = $data[$row]['bookid'];
       $command = "select * from $table_products where bookid=$currentbookid;";
       // echo "<br /> $command <br />";
       $result = mysql_query($command)
              or die ("Query '$command' failed with error message: \"" . mysql_error () . '"');
       $numrows = $result[0];       
       if ($numrows = 1) {
          $tabledata = mysql_fetch_object($result);
          print "<td>".$tabledata->book_name."</td>"; // column 6 row product description
          print "<td>".$tabledata->price."</td>";     // column 7 row product price
       } else {
          echo "<br />OOPS! ...count(dollarsign-tabledata) != 1 ...<br />";
          echo "<br /> ---  count(dollarsign-tabledata) = ".count($tabledata)."<br />";
          echo "<br />GOOD BYE NOW! ...<br />";
          exit();
       }
       // column 8 row product date selected is $_SESSION['login_time']
          print "<td>".$_SESSION['login_time']."</td></tr>\n";
   } // end of single row for statement
/* ------------------------------------------- */

         /* stick into POST array for submit to order displayed data from scart_neworders */
         print '<input type="hidden" name="ords_quantity'.$rownumber.'" 
                       id="ords_quantity'.$rownumber.'" value="'.$data->order_quantity.'">';
         // print '<input type="hidden" name="ords_bookid'.$rownumber.'" value="'.$data->bookid.'">';
         print '<input type="hidden" name="ords_bookname'.$rownumber.'" value="'.$data->book_name.'">';
         print '<input type="hidden" name="ords_price'.$rownumber.'" value="'.$data->price.'">';
         print '<input type="hidden" name="row_number" id="row_number" value="'.$rowcounter.'">';
//      }    
         /*--  submit to delete item or submit to edit quantity --*/
         print '<tr>';
         /*--  submit to delete item  --*/
         print '<td colspan="2">';
         print '   <label for="submit_delitem">&nbsp;&nbsp;</label>';
         print '   <input type="submit" name="submit_delitem" id="submit_delitem" value="delete item">';
         print '</td>';
         /*--  submit to edit quantity --*/
         print '<td colspan="2">';
         print '   <label for="submit_editqty">&nbsp;&nbsp;</label>';
         print '   <input type="submit" name="submit_editqty" id="submit_editqty" value="update qty">';
         print '</td>';
         /*--  submit to select another item or submit to clear cart --*/
         print '<td colspan="3">';
         /*--  submit to select another item  --*/
         print '   <label for="nextitem">&nbsp;&nbsp;</label>';
         print '   <input type="button" id="nextitem" 
                          onclick="window.location.href=\'scart-products_select.php\'"
                          value="add another item">';
         /*--  submit to clear cart --*/
         print '   <label for="submit_clearcart">&nbsp;&nbsp;</label>';
         print '   <input type="submit" name="submit_clearcart" id="submit_clearcart" value="clear cart">';
         print "</td>";
         /*--  submit to select another item or submit to clear cart --*/
         print '<td colspan="1">';
         print '   <label for="submit_order">&nbsp;&nbsp;</label>';
         print '   <input type="submit" name="submit_review" id="submit_review" value="submit to review">';
         print '</td>';
         print '</tr>';
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
<?php
   // Check for session vars for manager or customer 
   if ( $_SESSION['custStatus'] == "manager" ) {
	print '<br /><button onclick="window.location.href=\'scart-manager_options.php\'"> '
		.'Return to Manager Options</button><br /><br />';
   } else {
	print '<br /><button onclick="window.location.href=\'scart-account_options.php\'"> '
		.'Return to Account Options</button><br /><br />';
   }
?>
<!--  session_start at top before any html code, Print $_session array -->
</html>
