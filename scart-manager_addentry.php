<?php
/*-- filename: gahscart/scart-manager_addentry.php
      - Sat Jun  7 11:40:25 PDT 2014
      - updated gahscart/scart-manager_produpdate.php
      - updated gahscart/include/scart_forms.js
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   Functions              #
#--------------------------#
/*-- new functions: gahscart/scart-manager_addentry.php  */
function check_action ($form_array) {
/* should be 1 of 2 values and !null
   action="scart-products_addorder.php?action=checkout" method="post">
   onclick=\"window.location.href='scart-products_addorder.php?action=cancel'\"
*/
   if (isset($form_array['form_action'])) {
      return 1;
   }
   else return 0;
}
function check_input ($form_array) {
/* checking post array for good data to insert
   no list items to figure, i.e. which radio button selected,
*/
   if ($form_array['prod_bookid'] && 
       $form_array['prod_bookname'] && 
       $form_array['prod_bookprice'] && 
       $form_array['prod_release2'] && 
       $form_array['form_action']) {
      // print '<script type="text/javascript">';
      // print 'alert("if check_input POST is true => return 1")';
      // print '</script>';  
      return 1;
   }
   else return 0;
}
function new_prodid ($connID,$data_base,$table_name) {
   // start vars test popup. should be here
   $cmdmsg="Now in scart-manager_addentry, function =  new_prodid ";
   $cmdmsg1="-- database is -- "; // .$data_base;
   $cmdmsg2="--  table  is  -- "; // .$table_name;
   print '<script type="text/javascript">';
   print 'alert("'.$cmdmsg.' !\n'.$cmdmsg1.$data_base.' !\n'
                  .$cmdmsg2.$table_name.' !\n")';
   print '</script>';  
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
   //  $table_name = "scart_customers";     // scart table customer information
   $database = "ghornbec";              // my database, same userid as sandbox login
   $table_products = "scart_products";  // scart table product information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>Product Entry Add-Edit</title>
   <meta charset="UTF-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Product Management - Add/Edit</h1>
<?php
   /*  html page banner for adding a new account */
   $command = "select datediff('2014-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
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
   if (check_action($_POST)) {
       $current_action = $_POST['form_action'];
       switch ($current_action) { 
         case 'edititem':
              $action_formtitle= "Edit Item";
              // print '<script type="text/javascript">';
              // print 'alert("Here we are in scart-manager_addentry - case edititem !\n")';
              // print '</script>';
              // /*  php-mysql commands to display current product data and retrieve updates
              // start vars test popup. for current product data
              $checkoutmsg   = "get variable should be (edititem)";
              $checkoutvalue = "get action variable is = ";
              // print '<script type="text/javascript">';
              // print 'alert("'.$checkoutmsg.' !\n'.$checkoutvalue.$current_action.' !\n")';
              // print '</script>';
              /* foreach ($_POST as $key => $val) {
                  print '<script type="text/javascript">';
                  print 'alert("edititem - key => value is !\n  '.$key.' => '.$val.' !\n")';
                  print '</script>';
              } */
              if (check_input($_POST)) {
                 /* Here are the post array variables ......
                    Array ( [prod_bookid] => 7
                            [prod_bookname] => HTML & CSS: Design and Build Websites
                            [prod_bookprice] => 123.45
                            [prod_release2] => 2011-11-08
                            [form_action] => additem  )
                    Done with the post array variables ......
                   */
                 // html form data OK add to insert command
                 $bookid_field      = $_POST['prod_bookid']; // auto_increment record field 1
                 $bookname_field    = $_POST['prod_bookname'];  // record field 2
                 $bookprice_field   = $_POST['prod_bookprice']; // record field 3
                 $bookrelease_field = $_POST['prod_release2'];  // record field 4
/*
                 $command = "insert into $table_products values 
                            ('','".addslashes($bookname_field)."',
                                '".addslashes($bookprice_field)."',
                                '".addslashes($bookrelease_field)."');";
*/
                 $command = "update $table_products set 
                                   book_name='".addslashes($bookname_field)."',
                                       price='".addslashes($bookprice_field)."',
                               date_released='".addslashes($bookrelease_field)."'
                               where bookid=$bookid_field;";
                 // echo $command;
                 $result = mysql_query($command);
                 if (!$result) {
                    // usually a duplicate error, but could be something else
                    print '<script type="text/javascript">';
                    print 'alert("update result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
                           .mysql_error($connID).'\nRecord NOT updated in '.$table_products.' !\n")';
                    print '</script>';  
                    // exit();  
                 } else {
                    $bookid_field    = $_POST['prod_bookid']; // auto_increment record field 1
                    $selected_action = $_POST['form_action']; // form action selected
                    // start vars test popup. next orderid for orders completed
                    print '<script type="text/javascript">';
                    print 'alert("product UPDATED successfully in '
                                  .$table_products.'!\n\nPlease reference product id '
                                  .$bookid_field.'\nwhen updating!\n\n")';
                    print '</script>';
                 }  // end of if (!$result) 
              } else {
                 // start vars test popup. next orderid for orders completed
                 print '<script type="text/javascript">';
                 print 'alert("update product --failed--!\n no product id generated try again!\n")';
                 print '</script>';
                 // finish vars test popup. next orderid for orders completed
                 // }  //end of if ($newprodid)
              }  // end of if (check_input($_POST))
              break; 
         case 'additem': 
              $action_formtitle= "Add Item";
              // print '<script type="text/javascript">';
              // print 'alert("Here we are in scart-manager_addentry - case additem !\n")';
              // print '</script>';
              /* foreach ($_POST as $key => $val) {
                  print '<script type="text/javascript">';
                  print 'alert("additem - key => value is !\n  '.$key.' => '.$val.' !\n")';
                  print '</script>';
              } */
              if (check_input($_POST)) {
                 /* Here are the post array variables ......
                    Array ( [prod_bookid] => 7
                            [prod_bookname] => HTML & CSS: Design and Build Websites
                            [prod_bookprice] => 123.45
                            [prod_release2] => 2011-11-08
                            [form_action] => additem  )
                    Done with the post array variables ......
                   */
                 // html form data OK add to insert command
                 $bookname_field    = $_POST['prod_bookname'];  // record field 2
                 $bookprice_field   = $_POST['prod_bookprice']; // record field 3
                 $bookrelease_field = $_POST['prod_release2'];  // record field 4
                 $command = "insert into $table_products values 
                            ('','".addslashes($bookname_field)."',
                                '".addslashes($bookprice_field)."',
                                '".addslashes($bookrelease_field)."');";
                 // echo $command;
                 $result = mysql_query($command);
                 if (!$result) {
                    // usually a duplicate error, but could be something else
                    print '<script type="text/javascript">';
                    print 'alert("insert result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
                           .mysql_error($connID).'\nRecord NOT inserted into '.$table_products.' !\n")';
                    print '</script>';  
                    // exit();  
                 } else {
                    $bookid_field    = $_POST['prod_bookid']; // auto_increment record field 1
                    $selected_action = $_POST['form_action']; // form action selected
                    // start vars test popup. next orderid for orders completed
                    print '<script type="text/javascript">';
                    print 'alert("new product INSERTED successfully into '
                                  .$table_products.'!\n\nPlease reference product id '
                                  .$bookid_field.'\nwhen updating!\n\n")';
                    print '</script>';
                 }  // end of if (!$result) 
              } else {
                 // start vars test popup. next orderid for orders completed
                 print '<script type="text/javascript">';
                 print 'alert("new product --failed--!\n no product id generated try again!\n")';
                 print '</script>';
                 // finish vars test popup. next orderid for orders completed
                 // }  //end of if ($newprodid)
              }  // end of if (check_input($_POST))
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
     // print '<script type="text/javascript">';
     // print 'alert("Here we are at the end scart-manager_addentry - case edititem !\n")';
     // print '</script>';
     echo "get help something is wrong with form data";
     exit();
   }
   mysql_close($connID);
?>

<!--  html display for data entered from the form to to edit/add a product -->
<div>
   <p>
   <!--  <div class="tableContainer"> -->
   <table>
      <!--  html data entry form new account status and page alignment -->
      <div class="tableRow">
           Status: <?php echo $action_formtitle?> -- Completed Fields!
      </div>
      <!--  scart_products->bookid auto_increment for inserts -->
      <div class="tableRow">
           Book ID: <?php echo $bookid_field?> 
      </div>
      <!--  scart_products->book_name inserts/updates -->
      <div class="tableRow">
           Book Title: <?php echo $bookname_field?>
      </div>
      <!--  scart_products->prod_bookprice inserts/updates  -->
      <div class="tableRow">
           Book Price: <?php echo $bookprice_field?>
      </div>
      <!--  scart_products->prod_release inserts/updates -->
      <!--  jsdatepick-calendar function new JsDatePick calendar pop-up text field date input -->
      <div class="tableRow">
           Release Date: <?php echo $bookrelease_field?>
      </div>
   <!--  </div> class="tableContainer"> -->
   </table>
   </p>
</div>

<!--  html end table and page reference to return to products index page -->
<p>
<button style="background-color:lightgreen" onclick="window.location.href='scart-manager_products.php'">
        Continue Product Editing! Return to Products Page</button><br /><br />
</p>
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
