<?php
/*-- filename: gahscart/scart-products_select.php
      - Tue 25 Feb 2014 15:13:15 PST 
      - cloned from gahscart/scart-products_index.php
      - updated for building the products shopping cart
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_name = "scart_products";         // scart table product information
   $table_orders   = "scart_neworders";  // scart table pending order information
   $table_products = "scart_products";   // scart table product information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>The Product List</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Product Shopping Cart</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
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
      <th>Item</th>
      <th>Qty</th>
      <th>P/N</th>
      <th>Book Title</th>
      <th>Price</th>
      <th>Release Date</th>
   </tr>

<?php     //    mysql_close($db);
   /* php current blog entry listing with link to detail    */
   $sesscustid = $_SESSION['custID'];      // => 23/
   $login_time = $_SESSION['login_time'];  // [login_time] => 2014-03-27 18:27:04
   $command = "select *, date_format(date_released, '%W, %m/%d/%Y') as formated_date
                 from $table_products;";
   // echo "<br /> $command <br />";
   $result = mysql_query($command);
   $num_rows = mysql_num_rows($result);
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
?>

<div>
<p>
   <!--  html data entry form new account status and page alignment -->
   <form name="product" onSubmit="return getSelectData()" 
         action="scart-products_addorder.php?action=addselect" method="post">
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
         print "<td>";
         print '<label for="'.$radioid.'"></label>';
         print '   <input type="radio" name="'.$radioid.'" id="'.$radioid.'" value="'.$rownumber.'">';
         print "</td>";
         // column 2 quantity 2 digits, initialize with rowcounter
         $qtyid="qty".$rownumber;  
         print "<td>";
         print '<label for="'.$qtyid.'"></label>';
         print '   <input type="text" name="'.$qtyid.'" id="'.$qtyid.'" size="2">';
         print "</td>";
         // columns 3-6 product and/or order information
         print "<td>".$data->bookid."</td>";
         print "<td>".$data->book_name."</td>";
         print "<td>".$data->price."</td>";
         print "<td>".$data->formated_date."</td></tr>\n";
         // variables for POST array on submit to order cart, table scart_neworders
         print '<input type="hidden" name="ords_rownum'.$rownumber.'" 
                       id="ords_rownum'.$rownumber.'" value="'.$rownumber.'">';
         print '<input type="hidden" name="ords_bookid'.$rownumber.'" value="'.$data->bookid.'">';
         print '<input type="hidden" name="row_numbers" id="row_numbers" value="'.$rownumber.'">';
      }    
         /*--  submit/check or edit/resubmit/accept input --*/
         print '<tr><td colspan="3">';
         print '   <label for="submit_sel">&nbsp;&nbsp;</label>
                   <input type="submit" name="submit_sel" id="submit_sel" value="submit selection">';
         print "</td>";
         /*--  bail out of the form and check current cart contents --*/
         print '<td>';
	 print '<button onclick="window.location.href=\'scart-orders_checkout.php\'">';
         print '  view current selections</button>';
         print '</td>';
         print '<td colspan="2"></td></tr>';
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
