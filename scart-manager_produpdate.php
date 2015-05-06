<?php
/*-- filename: gahscart/scart-manager_produpdate.php
      - Sun 11 May 2014 09:49:01 PDT 
      - cloned from gahscart/scart-scart-account_register.php
      -    and from gahscart/scart-scart-account_addentry.php
      - called from updated gahscart/scart-manager_products.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
     ------------------------------------------------------------------
     Adapted the following Javascript Calendar - date election function
      - Mon May 2014 19 14:45:06 PDT 
      - installed from downloaded zip to include/jsdatepick-calendar
      - Here Copy-paste, modified 3x libraries path
      - moved function new JsDatePick( ... ) to include/scart_forms.js
<!-- 
	Copyright 2010 Itamar Arjuan
	jsDatePick is distributed under the terms of the GNU General Public License.
	****************************************************************************************
	Copy paste these 2 lines of code to every page you want the calendar to be available at
-->
<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.min.css" />
<!-- 
	OR if you want to use the calendar in a right-to-left website
	just use the other CSS file instead and don't forget to switch g_jsDatePickDirectionality variable to "rtl"!
	
	<link rel="stylesheet" type="text/css" media="all" href="jsDatePick_ltr.css" />
-->
<script type="text/javascript" src="jquery.1.4.2.js"></script>
<script type="text/javascript" src="jsDatePick.jquery.min.1.3.js"></script>
<!-- 
	After you copied those 2 lines of code , make sure you take also the files into the same folder :-)
    Next step will be to set the appropriate statement to "start-up" the calendar on the needed HTML element.
    
    The first example of Javascript snippet is for the most basic use , as a popup calendar
    for a text field input.
-->
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
   if (isset($form_array['action'])) {
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
/* checking post array for good data to insert
   no list items to figure, i.e. which radio button selected,
*/
   if ($form_array['prod_bookname'] && 
       $form_array['prod_bookprice'] && 
       $form_array['prod_release']) {
      return 1;
   }
   else return 0;
}
function new_prodid ($connID,$data_base,$table_name) {
   // start vars test popup. should be here
   $cmdmsg="Now in scart-manager_produpdate, function = new_prodid ";
   $cmdmsg1="-- database is -- "; // .$data_base;
   $cmdmsg2="--  table  is  -- "; // .$table_name;
   // print '<script type="text/javascript">';
   // print 'alert("'.$cmdmsg.' !\n'.$cmdmsg1.$data_base.' !\n'
   //                .$cmdmsg2.$table_name.' !\n")';
   // print '</script>';  
   // exit();  
   // finish vars test popup. should be here
   // auto-increment orderid, retrieve next value
   // database name is 'ghornbec' and table name is 'scart_purchases'
   $command = "select auto_increment from information_schema.tables 
                where table_schema = '$data_base' and table_name = '$table_name'";
   $result = mysql_query($command);
   if ($result) {
        $nextprodid = mysql_result($result, 0);
        return $nextprodid;  // return next orderid
   } else {
        print '<script type="text/javascript">';
        print 'alert("query result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
                     .mysql_error($connID).'\nNo results found - Returning empty!\n")';
        print '</script>';  
        // exit();  
        return 0;
   }
}

#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   // $table_name = "scart_products";         // scart table product information
   // $table_orders   = "scart_neworders";  // scart table pending order information
   $database = "ghornbec";        // my database, same userid as sandbox login
   $table_products = "scart_products";   // scart table product information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>The Product Updates</title>
   <meta charset="utf-8">
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
<!-- Copyright 2010 Itamar Arjuan
   ****************************************************************************************
   Copy-paste, modified pathes for 3x libraries
 -->
   <link rel="stylesheet" type="text/css" media="all" href="include/jsdatepick-calendar/jsDatePick_ltr.min.css" />
   <script type="text/javascript" src="include/jsdatepick-calendar/jquery.1.4.2.js"></script>
   <script type="text/javascript" src="include/jsdatepick-calendar/jsDatePick.jquery.min.1.3.js"></script>
<!--
   /* include/scart_forms.js function getProdFormData() backup variable assignment */
   /* the calendar-datepicker form data input fix. duplicate hidden saved values  */
   document.getElementById("prod_release2").value = document.getElementById("prod_release").value;
 -->
   <script type="text/javascript">
           window.onload = function() {
                  g_globalObject = new JsDatePick({
                           useMode:2,
                           target:"prod_release",
                           dateFormat:"%Y-%m-%d"
                  });
           };
   </script>
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Product Updates</h1>
<?php
   $command = "select datediff('2014-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2014! <br /><br /></div>" ;
?>
<div>
   <!-- was: href="../lesson10-12_blog/lab10_o1-blogentry.html" -->
   <!-- was: <a href="blogaddform.php">Add new blog entry</a><br /><br /> -->
   <button onclick="window.location.href='scart-products_index.php'">
           Cancel Edit and Return</button>
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
   <button onclick="window.location.href='scart-account_logout.php'">
           Cancel Edit and Logout</button><br /><br />
</div>

<?php
   /*  php-mysql commands to insert a new order in purchases */
   if (check_action($_GET)) {
       $current_action = $_GET['action'];
       switch ($current_action) { 
         case 'edititem':
              // print '<script type="text/javascript">';
              // print 'alert("Here we are in scart-manager_produpdate - case edititem !\n")';
              // print '</script>';
              /*  php-mysql commands to display current product data and retrieve updates
              // start vars test popup. for current product data
              $checkoutmsg   = "get variable should be (edititem)";
              $checkoutvalue = "get action variable is = ";
              print '<script type="text/javascript">';
              print 'alert("'.$checkoutmsg.' !\n'.$checkoutvalue.$current_action.' !\n")';
              print '</script>';
              foreach ($_POST as $key => $val) {
                  print '<script type="text/javascript">';
                  print 'alert("key => value is !\n  '.$key.' => '.$val.' !\n")';
                  print '</script>';
              }
              // finish vars test popup. for current product data
              */
              /*  html form header detail for type of action additem or edititem */
              $selected_action = "Edit Item"; // calling form selection
              /*  php-mysql commands to retrieve item (row) selected */
              $selected_row = check_select ($_POST); // form radio button selected
              // start vars test popup. should not be logged in, login_success != true
              // print '<script type="text/javascript">';
              // print 'alert("edititem row_number shoud be NOT be 0 !\n row_number = '
              //                .$selected_row.'!\n")';
              // print '</script>';
              // finish vars test popup. should not be logged in, login_success != true
              if ($selected_row) {
                  // $selected_rownumber="addselected row_number shoud be 1";
                  $selected_rownumber=$_POST['radio'];
                  // print '<script type="text/javascript">';
                  // print 'alert("return value is '.$selected_row.'!\n row_number = '.$selected_rownumber.'!\n")';
                  // print '</script>';
                  /* php-mysql commands to insert product selected into neworders cart
                  Here are the post array variables including hidden ......
                  Array ( [qty01] => 
                          [ords_rownum01] => 01
                          [ords_bookid01] => 1
                          [row_numbers] => 06
                   ==>  row 03 of 06 selected 
                          [radio] => 03
                          [qty03] => 525
                          [ords_rownum03] => 03
                          [ords_bookid03] => 3
                  Done with the post array variables including hidden ......
                  */
                  $bookid_form = "ords_bookid".$selected_rownumber;  // record field 1
                  $bookid_field  = $_POST[$bookid_form];  // record field 1
                  $command = "select * from $table_products where bookid=$bookid_field;";
                  // print '<script type="text/javascript">';
                  // print 'alert("the command is: \n\n '.$command.'!\n")';
                  // print '</script>';
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
                         $row = mysql_fetch_array($result);
                         $bookname_field    = $row['book_name'];     // record field 2
                         $bookprice_field   = $row['price'];         // record field 3
                         $bookrelease_field = $row['date_released']; // record field 4
                      }
                      // start vars test popup. the bookid found for updating
                      print '<script type="text/javascript">';
                      print 'alert("Retrieving current product data from '
                                   .$table_products.'!\n\nFor P/N: '
                                   .$bookid_field.', and Book Title\n'.$bookname_field.'!\n")';
                      print '</script>';
                  }
              } else {
                  // start vars test popup. next orderid for orders completed
                  print '<script type="text/javascript">';
                  print 'alert("new order --failed--!\n no order id generated try again!\n")';
                  print '</script>';
                  // finish vars test popup. next orderid for orders completed
              }
              break; 
         case 'additem': 
              // print '<script type="text/javascript">';
              // print 'alert("Here we are in scart-manager_produpdate - case additem !\n")';
              // print '</script>';
              /*  html form header detail for type of action additem or edititem */
              $selected_action = "Add Item"; // calling form selection
              $bookid_field = new_prodid ($connID,$database,$table_products); // record field 1
              /*  php-mysql commands to delete from a neworders
              $selected_row = check_select ($_POST); // form radio button selected
              // start vars test popup. should not be logged in, login_success != true
              $addselectmsg  = "get variable should be (addselect)";
              $actionvalue = "get action variable is = ";
              print '<script type="text/javascript">';
              print 'alert("'.$addselectmsg.' !\n'
                             .$actionvalue.$current_action.'!\n")';
              print '</script>';
              print '<script type="text/javascript">';
              print 'alert("addselected row_number shoud be 0 or 1 !\n row_number = '
                             .$selected_row.'!\n")';
              print '</script>';
              // finish vars test popup. should not be logged in, login_success != true
               */
              break; 
         /*  something is unexpected coming from neworders checkout */
         default:
              print '<script type="text/javascript">';
              print 'alert("Here we are in scart-manager_produpdate - case default !\n
                            OOPS! Not as expected. Try again.\n")';
              print '</script>';
              // echo "OOPS! Not as expected. Try again.";
       }
   } else {
     echo "get help something is wrong with form data";
   }
?>

<!--  html data entry form to register a new account -->
<div>
   <p>
   <!--  <div class="tableContainer"> -->
   <table>
      <!--  html data entry form new account status and page alignment -->
      <form name="produpdates" onSubmit="return getProdFormData()" 
            action="scart-manager_addentry.php" method="post">
         <div class="tableRow">
            <label for="productupd">Status: </label>
               <?php echo $selected_action?> -- Complete All Fields!
         </div>
      <!--  scart_products->bookid auto_increment for inserts -->
         <div class="tableRow">
            <label for="prod_bookid">Book ID: </label>
               <input type="text" readonly="readonly" name="prod_bookid" id="prod_bookid" 
                      size="3" value="<?php echo $bookid_field?>"> 
                   <span style="font-size:10pt; padding-left:10px" > * read-only ID</span>
         </div>
      <!--  scart_products->book_name inserts/updates -->
         <div class="tableRow">
            <label for="prod_bookname">Book Title: </label>
               <input type="text" name="prod_bookname" id="prod_bookname" size="50" 
                      placeholder="Mastering Web Application Development with JS" value="<?php echo $bookname_field?>">
         </div>
      <!--  scart_products->prod_bookprice inserts/updates  -->
         <div class="tableRow">
            <label for="prod_bookprice">Book Price: </label>
               <input type="text" name="prod_bookprice" id="prod_bookprice" size="7" 
                      placeholder="123.45" value="<?php echo $bookprice_field?>">
         </div>
      <!--  scart_products->prod_release inserts/updates -->
      <!--  jsdatepick-calendar function new JsDatePick calendar pop-up text field date input -->
         <div class="tableRow">
            <label for="prod_release">Release Date: </label>
               <input type="text" name="prod_release" id="prod_release" size="10" 
                      placeholder="2014-06-07" value="<?php echo $bookrelease_field?>">
              <!--  jsdatepick-calendar function new JsDatePick  calendar pop-up form field date input -->
              <input type="hidden" name="prod_release2" id="prod_release2" size="10" value="<?php echo $bookrelease_field?>">
         </div>
      <!--  html data entry form new account status and page alignment -->
      <!--  submit/check or edit/resubmit/accept input -->
         <div class="tableRow">
            <label for="submit">&nbsp;&nbsp;</label>
            <!--  <input type="submit" value="submit to register">   -->
            <input type="submit" id="submit" value="<?php echo $selected_action?>">
         </div>
      <!--  requested action for updating and/or inserting  -->
         <input type="hidden" name="form_action" value="<?php echo $current_action?>">
      </form>
   <!--  </div> class="tableContainer"> -->
   </table>
   </p>
</div>
<!--  html end table and page reference to return to products index page -->

<?php 
 /* session_start(); 
 echo "<br />Here are the  session variables ...... ";
 echo "<pre>";
 print_r ($_SESSION);
 echo "</pre>";
 echo "Done with the session variables ......<br />";
 // $_POST['go']; 
 echo "<br />Here are the  post array variables ...... ";
 echo "<pre>";
 print_r ($_POST);
 echo "Done with the post array variables ......<br />";
 echo "</pre>";
 // $_SERVER['HTTP_REFERER']; 
 echo "<br />Here are the  server variables ...... <br />";
 echo "<pre>";
 print_r ($_SERVER);
 echo "</pre>";
 echo "<br />Done with the seerver variables ......<br />";
*/
?> 
<!--  session_start at top before any html code, Print $_session array -->
</body>
</html>
