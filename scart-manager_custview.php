<?php
/*-- filename: gahscart/scart-manager_custview.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
     Modified,  Fri Jan 23 14:47:45 PST 2015
      - rearrange case statement for list customer all details or all orders 
      - clean code for final submission
     Initialized, Fri 23 May 2014 15:26:36 PDT
      - cloned from gahscart/scart-manager_produpdate.php
      - called from updated gahscart/scart-manager_customers.php
 */
#--------------------------#
#   Functions              #
#--------------------------#
/*-- new functions: gahscart/scart-manager_produpdate.php  */
function check_action ($form_array) {
/* should be 1 of 2 values and !null
   action="scart-products_addorder.php?action=checkout" method="post">
   onclick=\"window.location.href='scart-products_addorder.php?action=cancel'\"
*/
   if (isset($form_array['submitview'])) {
      return 1;
   }
   else return 0;
}
function check_select ($form_array) {
/* checking post array for good data to insert
   which radio button selected, retrieve row_number, and display values to update
*/
   if (isset($form_array['radio'])) {
       $selected_row = $form_array['radio'];
       return $selected_row;  // return product (row) selected_row
   }  else {
      return 0;  // no product selected try again
   }
}

function check_input ($form_array) {
   if ($form_array['fname'] && $form_array['lname']  && 
       $form_array['address'] && $form_array['city'] &&
       $form_array['state'] && $form_array['zip'] &&
       $form_array['phone'] && $form_array['email'] &&
       $form_array['passwd']) {
      return 1;
   }
   else return 0;
}
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_name = "scart_products";       // scart table product information
   // $table_orders   = "scart_neworders";  // scart table pending order information
   $table_products  = "scart_products";     // scart table product information
   $table_purchases = "scart_purchases";    // scart table purchase information
   $table_customers = "scart_customers";    // scart table customer information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>Customer Details</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Customer Details</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<div>
   <!-- was: href="../lesson10-12_blog/lab10_o1-blogentry.html" -->
   <!-- was: <a href="blogaddform.php">Add new blog entry</a><br /><br /> -->
   <button onclick="window.location.href='scart-manager_customers.php'">
           Cancel View and Return Customers</button>
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
   <button onclick="window.location.href='scart-manager_options.php'">
           Cancel View and Return Options</button><br /><br />
</div>
<?php     
   /*  scart-manager_customers form pick a customer view details or show orders */
   if (check_action($_POST)) {
       $current_action = $_POST['submitview'];
       /*  php-mysql commands to retrieve item (row) selected */
       $selected_row = check_select ($_POST); // form radio button selected
       if ($selected_row) {
           // $selected_rownumber=hidden row_number radio buttened form listing of customers";
           $selected_rownumber=$_POST['radio'];
           $custid_form = "custid".$selected_rownumber;  // record field 1
           $custid_field  = $_POST[$custid_form];  // record field 1
           // $selected_rownumber=hidden row_number radio buttened form listing of customers";
           // the list customer information chosen
           switch ($current_action) { 
             case 'view selected':
		  // <!--  html column headers account details -->
		  print '<table border="1"><tr>';
		  print '<th><spam>Customer<br />ID</span></th>';
		  print '<th>First Name</th>';
		  print '<th>Last Name</th>';
		  print '<th><span>Account<br />Status</span></th>';
		  print '<th><span>Account<br />Username</span></th>';
		  print '<th><span>Account<br />Password</span></th>';
		  print '<th>Status Updated</th></tr>';
		  $command = "select * from $table_customers where custid=$custid_field;";
		  $result = mysql_query($command);
		  if (!$result) {
		      // usually a duplicate error, but could be something else
		      print '<script type="text/javascript">';
		      print 'alert("no result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
				   .mysql_error($connID).'\nRecord NOT FOUND in '.$table_products.' !\n")';
		      print '</script>';  
		      // exit();  
		  } else {
		      // check for 1 row returned
		      if (mysql_num_rows($result) == 1) {
			  $row = mysql_fetch_object($result);
			  // customer info 1 row of customer details and at least 1 of purchases
			  print "<tr><td>".$row->custid."</td>";      // record field 1
			  print "<td>".$row->cust_fname."</td>";      // record field 2
			  print "<td>".$row->cust_lname."</td>";      // record field 3
			  print "<td>".$row->cust_status."</td>";     // record field 4
			  print "<td>".$row->cust_userid."</td>";     // record field 5
			  print "<td>".$row->cust_passwd."</td>";     // record field 6
			  print "<td>".$row->cust_stamp."</td></tr>"; // record field 7
                          // customer info 2 row of 3 customer details
                          print "<tr><td></td>";                      // row 2 indent
                          print '<td colspan="6">'.$row->cust_address.", ";   // address field 8
                          print "&nbsp;".$row->cust_city;                     // city field 9
                          print ",&nbsp;".$row->cust_state;                   // state field 10
                          print "&nbsp;".$row->cust_zip."</td></tr>";         // zip field 11
                          // customer info 3 row of 3 customer details
                          print "<tr><td></td>";                      // row 3 indent
                          print '<td colspan="6">'.$row->cust_phone.", ";     // contact field 12
                          print "&nbsp;".$row->cust_email."</td></tr>";       // contact field 13
		      } else {
			  // expected 1 customer record and form more than 1 
			  print '<script type="text/javascript">';
			  print 'alert("customer details --failed--!\n more than 1 customer id found get help\n")';
			  print '</script>';
		      }  // end of if 1 customer found
                  } // end of 1 customer details extended information or purchases detail
		  // <!--  html end table and page reference to add new blog entries -->
		  print '</table>';
                  break; 
             case 'include selected purchases': 
		  // email confirmation and continue ost_library/checkCookies.php function result
		  print '<script type="text/javascript">';
		  print "window.location.href='scart-account_history.php?custid_field=$custid_field'";
		  print '</script>';
                  break; 
             default:
                  /*  something is unexpected coming from neworders checkout */
                  print '<script type="text/javascript">';
                  print 'alert("Here we are in scart-manager_custview - case default !\n
                                OOPS! Not as expected. Get help and/or Try again.\n")';
                  print '</script>';
                  // echo "OOPS! Not as expected. Try again.";
           }  // end of at least 1 customer selection requested

       mysql_close($connID);
       } // $selected_rownumber=hidden row_number radio buttened form listing of customers";
   } else {
     echo "get help something is wrong with customers form view details";
     exit();
   }
?>

<!--  html end table and page reference to return to products index page -->
<p>
<!--  <button style="background-color:darkorange" onclick="window.location.href='scart-products_index.php'"> -->
<button style="background-color:darkorange" onclick="history.back()">
        Cancel View and Return to Previous Page</button><br /><br />
</p>
</body><!--  session_start at top before any html code, get any variables set? -->
<!--  session_start at top before any html code, Print $_session array -->
</html>
