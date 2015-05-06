<?php
/*-- filename: gahscart/scart-products_addorder.php
 * User: garyh  * Date: 10/5/14  * Time: 5:12 PM
 * # enhanced ost_library/checkCookies.php for all cookie handling functions
 * (1) added   function savesession_scartcookies ($selectedcustid)
 * (2) removed function addselect_scartcookies ($selecteditemarray)
 * (3) added 6 new session handling functions for manupilating the shopping cart
 * --------------------------------------------
 *-- User: garyh  * Date: 9/7/14  * Time: 11:27 PM
 *   # implement ost_library/checkCookies.php for all cookie handling functions
 *   include("ost_library/checkCookies.php");
 *-- Sun Mar 30 11:34:13 PDT 2014
 *   - cloned from gahscart/scart-account_addentry.php
 *-- Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   Functions              #
#--------------------------#
/*-- new functions: gahscart/scart-products_addorder.php  */
function check_action ($form_array) {
/* should be 1 of 2 values and !null
   action="scart-products_addorder.php?action=checkout" method="post">
   onclick=\"window.location.href='scart-products_addorder.php?action=cancel'\"
*/
   if (isset($form_array['action'])) {
      return 1;
   }
   else return 0;
}
function check_input ($form_array) {
/* checking post array for 2 good data to insert
   row_number: could be multiple orders pending, form rows, expecting int 01
   quantity change checkbox selected row length 7, not selected length 6
*/
    if (is_numeric($form_array['row_number'])) {
      if (count($form_array) >= 6 || count($form_array) <= 7 ) {
         return 1;
      }
   } else {
         return 0;
   }
}
// function new_orderid ($connID,$data_base,$table_name) {
// gah database function to ost_library/checkCookies.php modified

function check_select ($form_array) {
/* checking post array for good data to insert
   which radio button selected and quantity entered between 1-999
   retrieve row_number row number and set values to insert
*/
   if (isset($form_array['radio'])) {
       $selected_row = $form_array['radio'];
       return $selected_row;  // return product (row) selected_row
   }  else {
      return 0;  // no product selected try again
   }
}

#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/checkCookies.php for all cookie handling functions
   include("ost_library/checkCookies.php");
# implement ost_library/webdataconnect.inc.php OST mysql-server
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   $database = "ghornbec";        // my database, same userid as sandbox login
   $table_purchases  = "scart_purchases";   // scart table purchased product
   // $table_customers = "scart_customers";  // scart table customer information
   $table_products  = "scart_products";   // scart table product information
# order infornmation pending updates
   $table_customers   = "scart_customers";    // scart table customer information
   // $table_purchases   = "scart_purchases";    // scart table pending order information
   $table_purchdetail = "scart_purchdetail";  // scart table pending order items 
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
 ?>

<html lang="en">
<head>
   <title>Ordering Items</title>
   <meta charset="UTF-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Customer Order Entry</h1>
<?php
   /*  html page banner for adding a new account */
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;

   /*  php-mysql commands to insert a new order in purchases */
   if (check_action($_GET)) {
       $current_action = $_GET['action'];
       switch ($current_action) { 
         case 'addselect': 
              /*  source scart-products_select.php 
                  <form name="product" onSubmit="return getSelectData()" 
                       action="scart-products_addorder.php?action=addselect" method="post">
               */
		  $selected_row = check_select ($_POST); // form radio button selected
		  // start vars test popup. should not be logged in, login_success != true
		  $addselectmsg  = "get variable should be (addselect)";
		  $actionvalue = "get action variable is = ";
		  // finish vars test popup. should not be logged in, login_success != true
		  if ($selected_row) {
		      // $selected_rownumber="addselected row_number shoud be 1";
		      $selected_rownumber=$_POST['radio'];
		      $quantity_field = "qty".$selected_rownumber;          // input field 2 to session 2
		      $bookid_field   = "ords_bookid".$selected_rownumber;  // input field 4 to session 1
		      $pid = $_POST[$bookid_field]; 
		      $q = $_POST[$quantity_field]; 
		      // now session array vars for working cart contents - add next pick session array vars
		      $newitem_array = addtocart($pid,$q);  // add to cart session vars array
		      $custCart = $_SESSION['custCart_ID'];  // the SESSION customer cart items array
		      $custCartlength = count($custCart);  // number of current items
		      $_SESSION['cartItems_count'] = $custCartlength;  // number of current items
		      // did this work 
		      print '<script type="text/javascript">';
		      print 'alert("next item  ADDED to Session Cart_ID '
				    .$custCart.'!\n\nReturning to Shopping Cart with '
				    .$cartItems_count.' Total items!\n\nReturning to Shopping Cart with '
				    .$q.' units of new P/N '.$pid.'!\n\n")';
		      print '</script>';
		      // exit("<br />Exit2 from scart_-products_addorder.php  and pid = ($pid) and q = ($q)");

	      } else {
		      // source scart-products_select.php 
		      // start vars test popup. next itemid for cart completed
		      print '<script type="text/javascript">';
		      print 'alert("scart-products_addorder.php  newitem_array variables --failed--!\n -'
				    .$selected_row.'- select_row does not match!\n -'
				    .$selected_rownumber.'- select_rownumber!\n ")';
		      print '</script>';
		      // finish vars test popup. next itemid for cart completed
	      }
              break; 
         case 'checkout': 
              /*  source scart-orders_checkout.php 
                  <form name="order" onSubmit="return getOrderData()" 
                      action="scart-products_addorder.php?action=checkout" method="post">
               */
	      if (isset($_POST['submit_delitem'])) {
		  // <input type="submit" name="submit_delitem" id="submit_delitem" value="delete item">
		  // update action function remove_product($pid)
                  $checkout_itemid = $_POST['raddel'];
                  $book_index = "ords_bookid".$checkout_itemid;
                  $pid = $_POST[$book_index];
                  $return_pid = remove_product($pid); // ost_library/checkCookies.php function result
		  if ($return_pid > 0) {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Success! --\n Part No.'
		                   .$return_pid.' Removed from shopping cart!\n")';
		      print '</script>';
		  } else {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Failed! --\n input Part No.'
		                   .$return_pid.' Not Removed from shopping cart!\n\n  Input key '
		                   .$book_index.' for input P/N '.$pid.'!\n\n")';
		      print '</script>';
		      // echo "<br />remove_product input  value($pid) for key($book_index)";
		      // echo "<br />remove_product output value($return_pid)";
		  }
	      } elseif (isset($_POST['submit_editqty']))  {
		  // <input type="submit" name="submit_clearcart" id="submit_clearcart" value="clear cart">
		  // update action function change_quantity($pid,$q)
                  $checkout_itemid = $_POST['radedit'];
                  $book_index = "ords_bookid".$checkout_itemid;
                  $pid = $_POST[$book_index];
                  $book_qty = "qty".$checkout_itemid;
                  $q = $_POST[$book_qty];
                  $return_pid = change_quantity($pid,$q); // ost_library/checkCookies.php function result
		  if ($return_pid > 0) {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Success! --\n Part No.'
		                   .$return_pid.' Changed quantity to '.$q.'!\n")';
		      print '</script>';
		  } else {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Failed! --\n input Part No.'
		                   .$return_pid.' Not Changed to '.$q.'!\n\n  Input key '
		                   .$book_index.' for input P/N '.$pid.'!\n\n")';
		      print '</script>';
		      // echo "<br />remove_product input  value($pid) for key($book_index)";
		      // echo "<br />remove_product output value($return_pid)";
		  }
	      } elseif (isset($_POST['submit_review']))  {
		  //  <input type="submit" name="submit_review" id="submit_review" value="submit to review">
		  // function new_orderid ($connID,$data_base,$table_name)
		  // return $nextorderid;  // return next orderid
		  $nextorderid = new_orderid ($connID,$database,$table_purchases); // test id 999
		  print '<script type="text/javascript">';
		  print 'alert("submit_review Succeeded.  Next OrderID '
		               .$nextorderid.'\nAssigned with order submission and acceptance!\n")';
		  print '</script>';
		  //  scart-orders_review.php?
		  $_SESSION['newOrder_ID'] = $nextorderid;  // SESSION variable for next order id
		  print '<script type="text/javascript">';
		  print "window.location.href='scart-orders_review.php'";
		  print '</script>';
	      } elseif (isset($_POST['submit_clearcart']))  {
		  // <input type="submit" name="submit_clearcart" id="submit_clearcart" value="submit to order">
		  // customer cart session variables
                  $return_val = clear_cartvars(); // ost_library/checkCookies.php function result
		  if (!$return_val > 0) {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Success! --\n Shopping Cart contents = '
		                   .$return_val.' all items removed !\n")';
		      print '</script>';
		  } else {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Failed! --\n Shopping Cart contents = '
		                   .$return_val.' all items NOT Removed !\n")';
		      print '</script>';
		      // echo "<br />remove_product input  value($pid) for key($book_index)";
		      // echo "<br />remove_product output value($return_pid)";
		  }
		  // customer cart cookie variables
                  $return_val2 = remove_scartcookies(); // ost_library/checkCookies.php function result
		  if (!$return_val2 > 0) {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Success! --\n Shopping Cart Temporary Storage contents = '
		                   .$return_val2.' all items removed !\n")';
		      print '</script>';
		  } else {
		      print '<script type="text/javascript">';
		      print 'alert(" -- Failed! --\n Shopping Cart Temporary Storage contents = '
		                   .$return_val2.' all items NOT Removed !\n")';
		      print '</script>';
		      // echo "<br />remove_product input  value($pid) for key($book_index)";
		      // echo "<br />remove_product output value($return_pid)";
		  }
		  //  startover from account options
		  print '<script type="text/javascript">';
		  print "window.location.href='scart-account_options.php'";
		  print '</script>';

	      } elseif (isset($_POST['submit_order']))  {
	      // from review order action scart-orders_review.php
	      //  <input type="submit" name="submit_order" id="submit_order" value="submit to order">
	      // function get_emailaddress ($connID,$data_base,$table_name,$custid)
		      $currentcust = $_SESSION['custID'];   // => 23
		      $emailaddress = get_emailaddress ($connID,$database,$table_customers,$currentcust);
		      print '<script type="text/javascript">';
		      print 'alert(" -- Success! -- Order pending notification! \n To customer email address... '
		                   .$emailaddress.' \n\nContinuing ... !\n")';
		      print '</script>';
		      //  scart-orders_review.php?
		      $_SESSION['customer_email'] = $emailaddress;  // SESSION variable customer email address
		      print '<script type="text/javascript">';
		      print "window.location.href='scart-orders_accept.php'";
		      print '</script>';

	      } else {
		  //no button pressed
	      }
              break; 
         case 'email_continue': 
              /*  source scart-orders_accept.php
		  <button onclick="window.location.href='scart-products_addorder.php?action=email_continue'">
			  Email Notification and Continue</button>
               */
	      // insert session vars into tables ost_library/checkCookies.php function result
	      $nextorderid = add_table_data($connID,$database);  // insert vars and return function result
	      $emailaddress = $_SESSION['customer_email']; // SESSION variable customer email address
	      print '<script type="text/javascript">';
	      print 'alert(" -- Email and Continue! -- \n'
	                   .$emailaddress.' ... \n\t new order id ... '
	                   .$nextorderid.' ... \n\n Continuing .... Cart Maintenance!\n")';
	      print '</script>';
	      // clear session vars ost_library/checkCookies.php function result
	      $return_val = clear_cartvars(); // clear vars and return function result
	      if ( !$return_val > 0 ) {
		   print '<script type="text/javascript">';
		   print 'alert(" -- Success! --\n Shopping Cart contents = '
				  .$return_val.' all items removed !\n")';
		   print '</script>';
	      } else {
		   print '<script type="text/javascript">';
		   print 'alert(" -- Failed! --\n Shopping Cart contents = '
				  .$return_val.' all items NOT Removed !\n")';
		   print '</script>';
	      }
	      // customer cart cookie variables
              $return_val2 = remove_scartcookies(); // ost_library/checkCookies.php function result
	      if (!$return_val2 > 0) {
		   print '<script type="text/javascript">';
		   print 'alert(" -- Success! --\n Shopping Cart Temporary Storage contents = '
		                 .$return_val2.' all items removed !\n")';
		   print '</script>';
	      } else {
		   print '<script type="text/javascript">';
		   print 'alert(" -- Failed! --\n Shopping Cart Temporary Storage contents = '
		                 .$return_val2.' all items NOT Removed !\n")';
		   print '</script>';
	      }
	      // email confirmation and continue ost_library/checkCookies.php function result
	      print '<script type="text/javascript">';
	      print "window.location.href='scart-orders_msgphpmail.php?action=email_continue'";
	      print '</script>';

              break; 
         case 'email_logout': 
              /*  source scart-orders_accept.php
		  <button onclick="window.location.href='scart-products_addorder.php?action=email_logout'">
			  Email Notification and Logout</button>
               */
	      // insert session vars into tables ost_library/checkCookies.php function result
	      $nextorderid = add_table_data($connID,$database);  // insert vars and return function result
	      $emailaddress = $_SESSION['customer_email']; // SESSION variable customer email address
	      print '<script type="text/javascript">';
	      print 'alert(" -- Email and Logout! -- \n'
	                   .$emailaddress.' ... \n\t new order id ... '
	                   .$nextorderid.' ... \n\n Continuing .... Cart Maintenance!\n")';
	      print '</script>';
	      // clear session vars ost_library/checkCookies.php function result
	      $return_val = clear_cartvars(); // clear vars and return function result
	      if ( !$return_val > 0 ) {
		   print '<script type="text/javascript">';
		   print 'alert(" -- Success! --\n Shopping Cart contents = '
				  .$return_val.' all items removed !\n")';
		   print '</script>';
	      } else {
		   print '<script type="text/javascript">';
		   print 'alert(" -- Failed! --\n Shopping Cart contents = '
				  .$return_val.' all items NOT Removed !\n")';
		   print '</script>';
	      }
	      // email confirmation and continue ost_library/checkCookies.php function result
	      print '<script type="text/javascript">';
	      print "window.location.href='scart-orders_msgphpmail.php?action=email_logout'";
	      print '</script>';

              break; 
         case 'cust_orders': 
              /*  sources scart-orders_accept.php or scart-orders_history.php
		// --  scart-orders_accept.php -- //
		<form name="cust_orders" onSubmit="return getOrderData()" 
			    action="scart-products_addorder.php?action=cust_orders" method="post">
		// --  scart-orders_history.php -- //
		<form name="cust_orders" onSubmit="return getOrderData()" 
			    action="scart-products_addorder.php?action=cust_orders" method="post">
               */
	      // $nextorderid = $_SESSION['newOrder_ID'];     // SESSION variable for next order id
	      // $emailaddress = $_SESSION['customer_email']; // SESSION variable customer email address
              $selected_row = check_select ($_POST); // form radio button selected
              if ($selected_row) {
                  // $selected_rownumber="addselected row_number shoud be 1";
                  $selected_rownumber=$_POST['radio'];
                  $orderid_field = "orderid".$selected_rownumber;  // input field 2 to session 2
                  $orderprintid = $_POST[$orderid_field]; 
                  // do this from scart-orders_print.php. extract from database and build order array
                  // $orderdetail_array = print_order_detail($orderid);  // construct an order details array
	      }
              // should be apporpriate form row - print, from browser and email and continue
	      print '</script>';
	      print '<script type="text/javascript">';
	      print "	     window.location.href='scart-orders_print.php?printid=$orderprintid'";
	      print '</script>';

		  break; 
         /*  something is unexpected coming from neworders checkout */
         default:
              echo "OOPS! Not as expected. Try again.";
       }
   } else {
     echo "get help something is wrong with form data";
   }

/*  php-mysql commands to insert a new order in purchases */
//  if (check_action($_GET)) {
// window.location.href='scart-orders_checkout.php';
// require_once("scart-orders_checkout.php");
   print '<script type="text/javascript">';
   print "window.location.href='scart-orders_checkout.php'";
   print '</script>';
?>

</body>
</html>
