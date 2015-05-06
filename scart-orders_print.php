<?php
/*-- filename: gahscart/scart-orders_print.php
      Sat Dec 20 21:21:27 PST 2014 
      create from scart-orders_accept.php and modify for
      - account review orders and view/reprint single orders
      - order data retrieved and printed from database tables
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
   <title>The Order Print</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Order Print</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
/* php current session variables    */
   // build an associative array of order items
   // $data = array();   // individual order line items from database
   // retireve database order items array
   if ( isset($_GET['printid']) ) {
      $printid = $_GET['printid'];
      $intprintid = intval($printid);     // => 23  mysql value in integer
      // orderid passed correctly and order details array OK, now misc customer info
      // $sesscustid = $_SESSION['custID'];    // => 23  is string as session variables
      // $intcustid = intval($sesscustid);     // => 23  mysql value in integer
      $login_time = $_SESSION['login_time'];  // [login_time] => 2014-03-27 18:27:04
      // $cmdcust = "select * from $table_customers where custid = $intcustid ;";
      // select * from scart_purchases where orderid=48;
      $cmdcust  = "select a.*, b.cust_fname, b.cust_lname from scart_purchases as a, ";
      $cmdcust .= "scart_customers as b where a.custid=b.custid "; 
      $cmdcust .= "and a.orderid=$intprintid;";
      $result1 = mysql_query($cmdcust);
      $num_rows = mysql_num_rows($result1);
      $row = mysql_fetch_array($result1);
      if ( $num_rows == 1 ) {
	   // need single customer record - here is equal 1 continue
	   $cust_fname = $row['cust_fname'];
	   $cust_lname = $row['cust_lname'];
	   // retrieve customer order info - build wummary table
	   $cmdorders = "select * from $table_purchases where orderid = $intprintid ;";

	   $result2 = mysql_query($cmdorders)
              or die ("Query '$cmdorders' failed with error message: \"" . mysql_error () . '"');

	   $num_rows2 = mysql_num_rows($result2);
	   $row_info = mysql_fetch_array($result2);
	   if ( $num_rows2 == 1 ) {
		// need single order record - here is equal 1 continue
		$order_quantity = $row_info['order_quantity'];
		$order_total    = $row_info['order_total'];
		$order_stamp    = $row_info['order_stamp'];
		print '<script type="text/javascript">';
		print 'alert("order summmary found --\n\n'
			    .'for customer '.$cust_fname.' '.$cust_lname.'\n'
			    .'for printid = '.$intprintid.'\n")';
		print '</script>';  
	   } else {
		// found customer record (s) - continuing
	   }

	   // retrieve customer order info - build detail order items array
	   $data = print_order_detail($intprintid);  // request database order details
	   if ( !empty($data) ) {
		// found order detail (s) - continuing
		print '<script type="text/javascript">';
		print 'alert("custid '.$intcustid.' order details available\n\nfound '
			  .$num_rows.' items for orderid = '.$intprintid.'
			  display of order details to follow!\n\n")';
		print '</script>';  
	   } else {
		// found no order detail (s) - need to bail to a controlled rest point
		print '<script type="text/javascript">';
		print 'alert("custid '.$intcustid.' no order details available\n\nfound '
			  .$num_rows.' items for orderid = '.$intprintid.'
			  Returning to summary page!\n\n")';
		print '</script>';  
		print '<script type="text/javascript">';
		print 'window.history.back()';
		print '</script>';  
	   }
      } else {
        // need single customer record - here is not equal 1
        print '<script type="text/javascript">';
        print 'alert("requires 1 customer record \n\nfound '
		     .$num_rows.' for custid = '.$intcustid.'\n\n")';
        print '</script>';  
      }
      // conitnue return 1;
   } else {
      // fail return 0;
      print '<script type="text/javascript">';
      print 'alert("requires at least 1 order record (item) \n\nfound '
		.$intprintid.' = $_GET printid\n\n")';
      print '</script>';  
   }

   /*  $_GET vars provided from scart-manager_custview.php */
   /*  $_SESSION['custStatus'] = $strCustStatus; */
   if ( $_SESSION['custStatus'] == "manager" ) {
	// $sesscustid = $_GET['custid_field'];  // => 23  is string as session variables
        // $_SESSION['custFname'] = $strCustFname;
        // $_SESSION['custLname'] = $strCustLname;
        $mgrfname = $_SESSION['custFname'];   // => manager login sring as session variables
        $mgrlname = $_SESSION['custLname'];   // => manager login sring as session variables
	print '<br /><span style="background-color:yellow;">Please Note: Review by Administrator ' 
                           .$mgrfname.' '.$mgrlname.'</span><br />';
   }
   echo "<br />&nbsp;&nbsp; Order Information for: $cust_fname $cust_lname  <br />";
   echo "&nbsp;&nbsp; Ordered  $order_quantity  Items on Order Number  $intprintid  <br />";
   echo "&nbsp;&nbsp; Date Processed : $order_stamp  <br />";

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
       //$rownumber=sprintf("%02d",$rowcounter);   // starts at 01
       // column 1 cart item (row) number
       $rowitemnumber=sprintf("%02d",$data[$row]['item']); // row item number
          print '<tr><td style="background-color:white; border:1px solid green;">'.$rowitemnumber.'</td>';
       // column 2 quantity ordered
       $qtyid="qty".$rownumber;
       $rowqty = $data[$row]['qty']; // cookie (json) row quantity
          print '<td style="background-color:white; border:1px solid green; text-align:center;">'.$rowqty.'</td>';
       // column 3 row product part number
       $ords_bookid="ords_bookid".$rownumber;
       $rowbookid = $data[$row]['bookid']; // cookie (json) row quantity
          print '<td style="background-color:white; border:1px solid green;">'.$rowbookid.'</td>';
       // column 4 row product description
       $currentbookname = get_product_name($rowbookid);  // get the book name for order detail
          print '<td style="background-color:white; border:1px solid green;">'.$currentbookname.'</td>';          // column 4 row product description
       // column 5 row product price
       $roworderprice = number_format($data[$row]['price'],2);  // column 5 row product price
          print '<td style="background-color:white; border:1px solid green; text-align:right;">'
		    .$roworderprice.'</td>';
       // column 6 row extended price 
       $extdprice = $roworderprice * $rowqty; // column 6 row extended price
       $ordertotal += $extdprice;             // column 6 order total extended price
       $extdpriceid="extdprice".$rownumber;
          print '<td style="background-color:white; border:1px solid green; text-align:right;">'
		     .number_format($extdprice,2).'</td>';
          print '</tr>';
  } // end of single row for statement
/* ------------------------------------------- */
       // last table row - order total
       print '<tr>';
       // last table row - first column combine interior columns 1 -3
       print '<td style="background-color:white; border:1px solid green;" colspan="3">';
       print '   <label for="submit_order">&nbsp;&nbsp;</label>';
       print '</td>';
       /*--  Order Total label  --*/
       // last table row - second column interior columns 4
       print '<td colspan="1" style="background-color:white; border:1px solid green; text-align:right;">'
		    .'Order Total: &nbsp;&nbsp;';
       print '</td>';
       // last table row - third column interior columns 5
       print '<td style="background-color:white; border:1px solid green;">'.'</td>';
       /*--  Order Total  --*/
       // last table row - fourth column interior columns 6
         print '<td style="background-color:white; border:1px solid green; text-align:right;">'
		    .number_format($ordertotal,2).'</td>';
         print '</tr>';
   ?>
<!--   </form>
 -->
   <!--  </div> class="tableContainer"> -->
   </p>
   </div>
<!--  html end table and page reference to add new blog entries -->
</table>
<!--  html end page cleanup - save table data and email confirmation, print and account options -->
<div>
   <p>
   <!-- page end order confirmation and table data inserts -->
   Order Number  <?php echo $intprintid?> ,&nbsp;&nbsp;Processed : <?php echo $order_stamp?><br /><br />
   <!-- page end print and account options -->
   <!-- <button onclick="window.location.href='scart-products_addorder.php?action=email_continue'">
           Email Notification and Continue</button> -->
   &nbsp;&nbsp; Use brower's print function for a copy!
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
<?php
   // Check for session vars for manager or customer 
   if ( $_SESSION['custStatus'] == "manager" ) {
	print '<button onclick="window.location.href=\'scart-manager_options.php\'"> '
		.'Return to Manager Options</button><br /><br />';
   } else {
	print '<button onclick="window.location.href=\'scart-account_options.php\'"> '
		.'Return to Account Options</button><br /><br />';
   }
?>
   </p>
</div>
</body>
<!--  session_start at top before any html code, get any variables set? -->
<!--  session_start at top before any html code, Print $_session array -->
</html>
