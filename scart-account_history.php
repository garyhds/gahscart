<?php
/*-- filename: gahscart/scart-account_history.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
     Modifided,  Tue Jan 20 13:14:45 PST 2015
      - added GET vars when called from gahscart/scart-manager_custview.php
     Initiatized, Wed 17 Dec 2014 13:04:03 PST 
      - cloned from gahscart/scart-manager_customers.php
      - called from updated gahscart/scart-account_options.php
     RE; Implemented Account Order History Display-Print
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie and session handling functions
   include("ost_library/checkCookies.php");
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
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
   /*  $_GET vars provided from scart-manager_custview.php */
   if ( isset($_GET['custid_field']) ) {
	$sesscustid = $_GET['custid_field'];    // => 23  is string as session variables
	print '<div>';
	print '<button onclick="window.location.href=\'scart-manager_options.php\'"> '
		.'Cancel and Return to Options</button>';
	print '<span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>';
	print '<button onclick="window.location.href=\'scart-account_logout.php\'"> '
		.'Cancel View and Logout</button><br /><br />';
	print '</div>';
   } else {
      // "action - with sesscustid not be sent  ...";
	$sesscustid = $_SESSION['custID'];    // => 23  is string as session variables
	print '<div>';
	print '<button onclick="window.location.href=\'scart-account_options.php\'"> '
		.'Cancel and Return to Options</button>';
	print '<span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>';
	print '<button onclick="window.location.href=\'scart-account_logout.php\'"> '
		.'Cancel View and Logout</button><br /><br />';
	print '</div>';
   }
   /* php current customer and session variables    */
   // $sesscustid = $_SESSION['custID'];    // => 23  is string as session variables
   $intcustid = intval($sesscustid);      // => 23  mysql value in integer
   $login_time = $_SESSION['login_time'];  // [login_time] => 2014-03-27 18:27:04
   $cmdcust = "select * from $table_customers where custid = $intcustid ;";
   /*
   // start vars test popup. should not be logged in, $go=="go"
   $cmdmsg="Here is the command = ";
   $numrowsmsg="-- number_rows is -- " .$num_rows;
   print '<script type="text/javascript">';
   print 'alert("'.$commandmsg.' !\n'.$command.' !\n")';
   print '</script>';  
   // exit();  
   // finish vars test popup. should not be logged in, $go=="go"
   */
   $result1 = mysql_query($cmdcust);
   $num_rows = mysql_num_rows($result1);
   $row = mysql_fetch_array($result1);

   if ( $num_rows == 1 ) {
        // need single customer record - here is equal 1 continue
	$cust_fname = $row['cust_fname'];
	$cust_lname = $row['cust_lname'];
        // retrieve customer order info - build wummary table
	$cmdorders = "select * from $table_purchases where custid = $intcustid order by orderid;";
	$result = mysql_query($cmdorders);
	if ( !result ) {
	    print '<script type="text/javascript">';
	    print 'alert("no customer orders \n\nfound '
			.$num_rows.' for custid = '.$intcustid.'\n\n")';
	    print '</script>';  
	} else {
	    // found customer record (s) - continuing
	}
   } else {
        // need single customer record - here is not equal 1
        print '<script type="text/javascript">';
        print 'alert("requires 1 customer record \n\nfound '
		     .$num_rows.' for custid = '.$intcustid.'\n\n")';
        print '</script>';  
   }

   /*  $_GET vars provided from scart-manager_custview.php */
   if ( isset($_GET['custid_field']) ) {
	// $sesscustid = $_GET['custid_field'];  // => 23  is string as session variables
        // $_SESSION['custFname'] = $strCustFname;
        // $_SESSION['custLname'] = $strCustLname;
        $mgrfname = $_SESSION['custFname'];   // => manager login sring as session variables
        $mgrlname = $_SESSION['custLname'];   // => manager login sring as session variables
	print '<br /><span style="background-color:yellow;">Please Note: Review by Administrator ' 
                           .$mgrfname.' '.$mgrlname.'</span><br />';
   }
   echo "<br /> Order History for: $cust_fname $cust_lname  <br />";
   echo " History Status Date: $login_time  <br /><br />";
?>
<!--  html data entry form new order with status and page alignment -->
<table border="1">
   <tr>
      <th><span>Order<br />Number</span></th>
      <th><span>Order<br />ID</span></th>
      <th><span>Total<br />Items</span></th>
      <th><span>Total<br />Price</span></th>
      <th>Order Date</th>
      <th><span>View<br />Details</span></th>
   </tr>
<div>
<p>
   <!--  html data entry form new account status and page alignment -->
   <form name="cust_orders" onSubmit="return getOrderData()" 
         action="scart-products_addorder.php?action=cust_orders" method="post">
<?php     //    mysql_close($db);
      $rowcounter=0;  // initialize row counter for radiobuttonid
      while ($data = mysql_fetch_object($result)) {
         // column 1 rownumber == $rowcounter
         $rowcounter++;  // starts at 1
         $rownumber=sprintf("%02d",$rowcounter);   // starts at 01
         /* <input type="radio" name="fb" id="fb" value="Football" />
              <label for="fb">Football</label>
          */
         // $radioid="radio".$rownumber;  // e.g. radio01
         $radioid="radio";  // needs to be same name for one select from form
         print "<tr>";
         // column 1 radio button, default unselected selects only 1 of all in form same "id"
         print '<td align="right">'.$rownumber.'</td>';
         // column 2 orderid print
         // retain row orderid for radio button select
         print '<td align="right">'.$data->orderid.'</td>';
         print '<input type="hidden" name="orderid'.$rownumber.'" 
                       id="orderid'.$rownumber.'" value="'.$data->orderid.'">';
         // columns 3-5 product and/or order information
         print '<td align="right">'.$data->order_quantity.'</td>';
         print '<td align="right">'.$data->order_total.'</td>';
         print "<td>".$data->order_stamp."</td>";
         // column 6 radio button, default unselected selects only 1 of all in form same "id"
         print '<td align="center">';
         print '<label for="'.$radioid.'"></label>';
         print '   <input type="radio" name="'.$radioid.'" id="'.$radioid.'"  value="'.$rownumber.'">';
         print "</td></tr>\n";
      }    
         // variables for POST array on submit to order cart, table scart_neworders
         print '<input type="hidden" name="row_numbers" id="row_numbers" value="'.$rownumber.'">';
         /*--  submit edit selected --*/
         print '<tr><td align="right" colspan="6">';
         print '   <label for="submitview">&nbsp;&nbsp;</label>
                   <input type="submit" name="submitview" id="submitview" value="view selected">';
         print "</td>";
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
 /* session_start(); 
 echo "<br />Here are the  session variables ...... ";
 echo "<pre>";
 print_r ($_SESSION);
 echo "</pre>";
 echo "Done with the session variables ......<br />";
 //  $_POST['go']; 
 echo "<br />Here are the post array variables ...... ";
 echo "<pre>";
 print_r ($_POST);
 echo "</pre>";
 echo "Done with the post array variables ......<br />";
 // $_SERVER['HTTP_REFERER']; 
 echo "<br />Here are the  server variables ...... <br />";
 echo "<pre>";
 print_r ($_SERVER);
 echo "</pre>";
 echo "<br />Done with the seerver variables ......<br />";
*/
?> 
<!--  session_start at top before any html code, Print $_session array -->
</html>
