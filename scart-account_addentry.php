<?php
/*-- filename: gahscart/scart-account_addentry.php
      - Fri 27 Dec 2013 14:53:09 PST 
      - updated gahscart/scart-account_register.php
      - updated gahscart/include/scart_forms.js
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   Functions              #
#--------------------------#
/* 2015-04-10, customers primary key updates. ghornbec_graded15-1b_20150217.txt */
// gah database function 
function check_registration ($form_array,$connID,$database,$table_name) {
// select cust_userid, cust_fname, cust_lname, custid from scart_customers
//  where cust_fname like 'Gar%' and cust_lname = 'Hornbeck'
   $custfname = $form_array['fname'];
   $custlname = $form_array['lname'];
   $command = "select cust_userid, cust_fname, cust_lname, custid from $table_name 
	       where cust_fname = '$custfname' and cust_lname = '$custlname'";
   $result = mysql_query($command);
   if ($result) {
       // found registered customer (at least 1) - should be only 1
       $registered_custuserid = mysql_result($result, 0, "cust_userid");
       return $registered_custuserid;  // return existing cust_userid
   } else {
       // found none - can insert new cust_userid
       return 0;
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
function get_userid ($form_array,$connID,$database,$table_name) {
/* inventing a controlled (clean) userid for login lookup 
   is <= max 8 lower case characters
    - 1st character of first name plus upto 1st 7 characters of last name
   gah 2015-04-10, customers primary key update
*/
   $nextcustid = next_custid ($connID,$database,$table_name); // test id 999
   $fnameid = substr(strtolower(trim($form_array['fname'])), 0, 1);
   $lnameid = substr(strtolower(trim($form_array['lname'])), 0, 7);
   $siteuserid = $fnameid.trim($lnameid).$nextcustid;

   return $siteuserid;  // continue no existing userid
}
/* 2015-04-10, customers primary key updates. ghornbec_graded15-1b_20150217.txt */
/* cloned from ost_library/checkCookies function new_orderid ( ... ) */
// gah database function retrieve next auto-increment custid
function next_custid ($connID,$database,$table_name) {
   $command = "select auto_increment from information_schema.tables 
                where table_schema = '$database' and table_name = '$table_name'";
   $result = mysql_query($command);
   if ($result) {
        $nextcustid = mysql_result($result, 0);
        return $nextcustid;  // return next orderid
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
   // $database = "ghornbec";    // my database, same userid as sandbox login
   $database = "gah_ost";        // test site fedora72 database name
   $table_name = "scart_customers";         // scart table customer information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
?>

<html lang="en">
<head>
   <title>Account Entry Added</title>
   <meta charset="UTF-8">
   <!--  adding <link rel="stylesheet" href="...> for table asthetics -->
   <!--  adding <script src="....js"></script> for data input control -->
   <link rel="stylesheet" href="include/scart_forms.css">
   <script type="text/javascript" src="include/scart_forms.js"></script>
</head>
<body>
<!--  html page table to register a new account and collect data entries -->
<h1>Customer Account Registration</h1>
<?php
   /*  html page banner for adding a new account */
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;

   /*  php-mysql commands to check account registration */
   if ($custuserid = check_registration($_POST,$connID,$database,$table_name)) {
      // accont currently registered - cust_fname, cust_lname, cust_userid on file
      $custfname = $_POST['fname'];
      $custlname = $_POST['lname'];
      print '<script type="text/javascript">';
      print 'alert("Current registration for customer '.$custfname.' '
		    .$custlname.' exists with username '
		    .$custuserid.'\n\nNew registration not required, going to Login page!\n\n")';
      print '</script>';  
      // account currently registered - go login
      print '<script type="text/javascript">';
      print "window.location.href='scart-account_login.php'";
      print '</script>';
      exit();
   }

   /*  php-mysql commands to insert a new account */
   if (check_input($_POST)) {
      $default_status = "active";
      $bookstoreid = get_userid($_POST,$connID,$database,$table_name);
      $phpnow = date("Y-m-d H:i:s"); 
      $command = "insert into $table_name
		  ( cust_fname,cust_lname,cust_address,cust_city,cust_state,cust_zip,
		    cust_phone,cust_email,cust_status,cust_userid,cust_passwd,cust_stamp )
		  values( '".addslashes($_POST['fname'])."',
                    '".addslashes($_POST['lname'])."',
                    '".addslashes($_POST['address'])."',
                    '".addslashes($_POST['city'])."',
                    '".addslashes($_POST['state'])."',
                    '".addslashes($_POST['zip'])."',
                    '".addslashes($_POST['phone'])."',
                    '".addslashes($_POST['email'])."',
                    '".addslashes($default_status)."',
                    '".addslashes($bookstoreid)."',
                    '".addslashes($_POST['passwd'])."',
                    '".addslashes($phpnow)."' );";
      $result = mysql_query($command);
      if (!$result) {
        // usually a duplicate error, but could be something else
        echo " ===== Error Encountered ===== <br/>";
        echo " ----------- <br/>";
        echo mysql_errno($connID) . ": " . mysql_error($connID). "<br/>";
        echo " ----------- <br/>";
        print "Record NOT inserted into $table_name. <br>";
      } else {
        // usually a duplicate error, but could be something else
        echo " ----------- <br/>";
        print "New Account Registered on $phpnow for $bookstoreid. <br>";
        print "Record INSERTED successfully into $table_name. <br>";
        echo " ----------- <br/>";
      }
   } else {
     // get help something is wrong with form data
   }
   mysql_close($connID);
?>

<!--  html end table and page reference to return to products index page -->
<p>
<button style="background-color:lightgreen" onclick="window.location.href='scart-account_index.php'">
        Continue to Login</button><br /><br />
</p>
</body>

</html>
