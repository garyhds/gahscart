<?php
/*-- filename: gahscart/scart-orders_accept.php
      Tue 02 Dec 2014 14:09:46 PST 
      create from scart-orders_review.php and modify for
      - insert and mail order view link
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie and session handling functions
   include("ost_library/checkCookies.php");
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   $table_products    = "scart_products";   // scart table product information
# order infornmation pending updates
   $table_customers   = "scart_customers";    // scart table customer information
   $table_purchases   = "scart_purchases";    // scart table pending order information
   $table_purchdetail = "scart_purchdetail";  // scart table pending order items 
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>
<html lang="en">
<head>
   <title>The Order Accept</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Order Accept</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<?php     //    mysql_close($db);
/* php current session variables    */
   $sesscustid = $_SESSION['custID'];      // => 23
   $login_time = $_SESSION['login_time'];  // [login_time] => 2014-03-27 18:27:04
   $cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
   $num_rows = count($_SESSION[$cart]);
   $new_orderid = $_SESSION['newOrder_ID'];   // the SESSION variable for next order id
   $date_accepted = $_SESSION['login_time'];  // the SESSION variable for time stamp
   $ordertotal = 0;                           // column 6 order total extended price

   echo "<br /> Ordered  $num_rows  Items on Order Number  $new_orderid  <br />";
   echo " Processed on  $date_accepted  <br />";
   $ordersum = get_order_total();              // header order total from session cart_array
   $ordersum = number_format($ordersum,2);     // header order total decimal formatted
   echo "<br /> Charges less Tax and Handling, if required  $ordersum  <br />";
   // retireve cart item contents array
   $data = get_cartvars();   // individual cart line items
?>

<!--  html data entry form new order with status and page alignment -->
<table style="border:1px solid green;" >
   <tr>
      <th style="background-color:white; border:1px solid green;"><span>Item</span></th>
      <th style="background-color:white; border:1px solid green;"><span>Quantity</span></th>
      <th style="background-color:white; border:1px solid green;"><span>Part</span></th>
      <th style="background-color:white; border:1px solid green;">Book Title</th>
      <th style="background-color:white; border:1px solid green;">Price</th>
      <th style="background-color:white; border:1px solid green;"><span>Extended</span></th>
   </tr>

   <div>
   <p>
   <!--  html data entry form new account status and page alignment

   <form name="order" onSubmit="return getOrderData()" 
         action="scart-products_addorder.php?action=checkout" method="post">
     -->

   <?php     //    mysql_close($db);

/* ------------------------------------------- */
   // display associative array of cart items
   $rowcounter = 0; // initialize to array index start
   for ($row = 0; $row < count($data); $row++) {
       $rowcounter++;
       $rownumber=sprintf("%02d",$rowcounter);   // starts at 01
       // column 1 cart item (row) number
          print '<tr><td style="background-color:white; border:1px solid green;">'.$rownumber.'</td>';
       // column 2 quantity ordered
       $qtyid="qty".$rownumber;
       $rowqty = $data[$row]['qty']; // cookie (json) row quantity
          print '<td style="background-color:white; border:1px solid green; text-align:center;">'.$rowqty.'</td>';
          print '<input type="hidden" name="'.$qtyid.'" id="'.$qtyid.'" value="'.$rowqty.'">';
       // column 3 row product part number
       $ords_bookid="ords_bookid".$rownumber;
       $rowbookid = $data[$row]['bookid']; // cookie (json) row quantity
          print '<td style="background-color:white; border:1px solid green;">'.$rowbookid.'</td>';
          print '<input type="hidden" name="'.$ords_bookid.'" value="'.$rowbookid.'">';
       // column 4 and 5 row product description and price
       $currentbookid = $data[$row]['bookid'];
       $command = "select * from $table_products where bookid=$currentbookid;";
       // echo "<br /> $command <br />";
       $result = mysql_query($command)
              or die ("Query '$command' failed with error message: \"" . mysql_error () . '"');
       $numrows = $result[0];       
       if ($numrows = 1) {
          $tabledata = mysql_fetch_object($result);
          print '<td style="background-color:white; border:1px solid green;">'.$tabledata->book_name.'</td>';          // column 4 row product description
          print '<td style="background-color:white; border:1px solid green; text-align:right;">'
		    .number_format($tabledata->price,2).'</td>';              // column 5 row product price
	  $extdprice = $data[$row]['qty'] * $tabledata->price; // column 6 row extended price
	  $ordertotal += $extdprice;                           // column 6 order total extended price
       } else {
          echo "<br />OOPS! ...count(dollarsign-tabledata) != 1 ...<br />";
          echo "<br /> ---  count(dollarsign-tabledata) = ".count($tabledata)."<br />";
          echo "<br />GOOD BYE NOW! ...<br />";
          exit();
       }
       // column 6 row extended price 
       $extdpriceid="extdprice".$rownumber;
          print '<td style="background-color:white; border:1px solid green; text-align:right;">'
		     .number_format($extdprice,2).'</td>';
          print '<input type="hidden" name="'.$extdpriceid.'" id="'.$extdpriceid.'value="'.$extdprice.'">';
          print '</tr>';
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
         /*--  submit to order item in current cart --*/
         print '<td style="background-color:white; border:1px solid green;" colspan="3">';
         print '   <label for="submit_order">&nbsp;&nbsp;</label>';
         // print '   <input type="submit" name="submit_order" id="submit_order" value="submit to order">';
         print '</td>';
         /*--  Order Total label  --*/
         print '<td colspan="1" style="background-color:white; border:1px solid green; text-align:right;">'
		    .'Order Total: &nbsp;&nbsp;';
         print '</td>';
         print '<td style="background-color:white; border:1px solid green;">'.'</td>';
         print '<td style="background-color:white; border:1px solid green; text-align:right;">'
		    .number_format($ordertotal,2).'</td>';
         print '<input type="hidden" name="'.$ordertotal.'" id="'.$ordertotal.'value="'.$ordertotal.'">';
         print '</tr>';
   ?>
<!--   </form>
 -->
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
<!--  html end page cleanup - save table data and email confirmation, print and account options -->
<div>
   <p>
   <!-- page end order confirmation and table data inserts -->
   Order Number  <?php echo $new_orderid?>  ,&nbsp;&nbsp;Accepted and Account Charged<br /><br />
   <!-- page end print and account options -->
   <button onclick="window.location.href='scart-products_addorder.php?action=email_continue'">
           Email Notification and Continue</button>
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
   <button onclick="window.location.href='scart-products_addorder.php?action=email_logout'">
           Email Notification and Logout</button><br /><br />
   </p>
</div>
</body>
<!--  session_start at top before any html code, get any variables set? -->
</html>
