<?php
/*-- filename: gahscart/scart-products_index.php
      - Wed 25 Dec 2013 09:03:52 PST 
      - renamed from gahscart/scart-scart-prod_index.php
      - updated for gahscart/scart-account_register.php
      - Mon Dec  9 16:49:41 PST 2013
      - cloned from: gahblog2/blogindex.php
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */
#--------------------------#
#   User variables         #
#--------------------------#
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("ost_library/webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   $table_name = "scart_products";         // scart table product information
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
   <!--  adding some css for table asthetics 2nd use -->
   <!-- <link rel="stylesheet" href="lab10_o1-blogentry.css"> -->
   <link rel="stylesheet" href="include/scart_forms.css">
</head>
<body>
<!--  html page table headers to view current blog entries -->
<h1>Book Store Products</h1>
<?php
   $command = "select datediff('2015-12-24', now());";
   $result = mysql_query($command);
   print "<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<B>NOTICE:&nbsp;&nbsp;</b>".mysql_result($result, 0);
   print "&nbsp; <b>DAYS UNTIL</b>&nbsp; Christmas 2015! <br /><br /></div>" ;
?>
<div>
   <!-- was: href="../lesson10-12_blog/lab10_o1-blogentry.html" -->
   <!-- was: <a href="blogaddform.php">Add new blog entry</a><br /><br /> -->
   <button onclick="window.location.href='scart-account_login.php'">
           Login to Account</button>
   <span>&nbsp;&nbsp;&nbsp;&nbsp;AND/OR:&nbsp;&nbsp;&nbsp;</span>
   <button onclick="window.location.href='scart-account_register.php'">
           Register New Account</button><br /><br />
</div>
<table border="1">
   <tr>
      <th>BookID</th>
      <th>Book Title</th>
      <th>Book Price</th>
      <th>Book Release Date</th>
   </tr>

<?php     //    mysql_close($db);

/* php current blog entry listing with link to detail    */
   $command = "select *, date_format(date_released, '%W, %m/%d/%Y') as formated_date from $table_name;";
// echo "<br> $command <br>";
   $result = mysql_query($command);
   while ($data = mysql_fetch_object($result)) {
      /* mysql format the date_post something like "Monday, 11/04/2006 - 12:31 PM CST"  */
      /* php functions, create a date object, then format the output  */
      // $date_formated=date_format(date_create($data->date_post), "l, m-d-Y - G:i A T");
//      print "<tr><td><a href='blogshow.php?postid=$data->postid'".">".$data->postid."</a></td>";
      print "<td>".$data->bookid."</td>";
      print "<td>".$data->book_name."</td>";
      print "<td>".$data->price."</td>";
      print "<td>".$data->formated_date."</td></tr>\n";
   }    
//    print "Record successfully inserted into $table_name. <br>";
/*-- before implementation.  now in library include file
    mysql_close($db);
 */
    mysql_close($connID);
?>

<!--  html end table and page reference to add new blog entries -->
</table>
</body>

</html>
