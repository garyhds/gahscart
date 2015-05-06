<?php
/*-- filename: gahscart/ost_library/checkLlogin.php
      - Sun 09 Feb 2014 10:09:44 PST 
      - new library routine.  success to scart-products_index.php
      - complemented by main routine. logout and destroy session vars
 */
#--------------------------#
#   User variables         #
#--------------------------#
# should get assigned session from calling scart-account_login.php so vars presist
# not too sure why or how session vars are used, but not carried into next routine
#   session_start();
# implement ost_library/webdataconnect.inc.php OST mysql-server 
   include("webdataconnect.inc.php");
   $connID = connect_to_mywebdata();
   $table_name = "scart_customers";         // scart table customer information
# required to access session variables, e.g. $_SESSION['login_success']="true";
   session_start();
#--------------------------#
#   Main body              #
#--------------------------#
# reset in scart-account_logout.php for session close
# ost phpinfo() server session.cache_expire Local=180, Master=180 // 180 minutes
# can reset local with session variable session_cache_expire(30); // 30 minutes
   $PHP_SELF=$_SERVER['PHP_SELF'];
   $referred=$_SERVER['HTTP_REFERER'];
   // registered and logged in to scart system 
   if($_SESSION['login_success']!="true"){	
      header("Cache-control: private");
      $submit=$_POST['submit'];
      $go=$_POST['go'];
      // hidden variable to signify userid and passwd submitted, test account values
      if($go=="go"){
         // collect and prep userid and passwd
         $txtUserID=$_POST['userid'];
         $txtUserID=strtolower($txtUserID);
         $txtPasswd=$_POST['passwd'];
         $txtPasswd=strtolower($txtPasswd);
         // verify userid and passwd
         $command = "SELECT * FROM $table_name WHERE";
         $command .= " `cust_userid`='$txtUserID'";
         $command .= " AND `cust_passwd`='$txtPasswd'";
         $result = mysql_query($command);
         $count=mysql_num_rows($result);
         // userid and password combination not found return to login with new login_attempts
         if($count<1){
            $login_attempts=$_SESSION['login_attempts'];  // current attempts
            $login_attempts++;                            // attempt counter++
            $_SESSION['login_attempts']=$login_attempts;  // next attempt
            print '<script type="text/javascript">';
            print 'alert("mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '. mysql_error($connID).'
                          No results found - Try '.$login_attempts.' !\nReturning to account_login page")';
            print '</script>';  
	    // echo "<meta http-equiv=\"refresh\" content=\"0;url=frmCustDetails.php\">";
            // header('Location: '.$referred);
            die('<script type="text/javascript">window.location.href="'.$referred.'";</script>');
	 } else {
	    $count=mysql_num_rows($result);
	 }  // end of 1st:         if($count<1){
	
	 if($count<1){
	    echo "<meta http-equiv=\"refresh\" content=\"0;url=$PHP_SELF?Login=Failed\">";
	    // echo "Failed";
	    exit("returning to '$PHP_REFERRER' ");
	 } else {
            // userid information for login session functions
            $row = mysql_fetch_array($result);
            $intCustID = $row['custid'];
            $_SESSION['custID'] = $intCustID;
	    $_SESSION['strUser'] = $txtUserID;
            // customer cart session array variables
            $_SESSION['custCart_ID'] = "cartItems".$_SESSION['custID'];  // custid specific $_COOKIE array
            // customer status - cust_status  | enum('active','inactive','manager')
            $strCustStatus = $row['cust_status'];
            $_SESSION['custStatus'] = $strCustStatus;
            // customer(manager) session login first and last name - manager different from customer
            $strCustFname = $row['cust_fname'];
            $_SESSION['custFname'] = $strCustFname;
            $strCustLname = $row['cust_lname'];
            $_SESSION['custLname'] = $strCustLname;
	    // $_SESSION['txtPassword'] = $pass;
	    $_SESSION['login_success']="true";
            $unix_login_time = date("Y-m-d H:i:s");
            $_SESSION['login_time'] = $unix_login_time;
            // http://ghornbec.userworld.com/gah_phpsql/gahscart/
            $project_url = 'http://';
            $project_url .= $_SERVER['HTTP_HOST'];
            $project_url .= '/gah_phpsql/gahscart/';
            $_SESSION['project_url'] = $project_url;
	 }  // end of 2nd:         if($count<1){
	
      }  // end of:      if($go=="go"){
}  // end of:if($_SESSION['login_success']!="true"){	

// manager or other for this pass of the exercise, i.e. inactive === active 
if ( $_SESSION['custStatus'] == "manager" ) {
    // header( 'Location: ../scart-manager_options.php' );
    print '<script type="text/javascript">';
    print "window.location.href='../scart-manager_options.php'";
    print '</script>';
} else {
    // logged in and to scart selections 
    // header( 'Location: ../scart-orders_status.php' ) ;
    print '<script type="text/javascript">';
    print "window.location.href='../scart-account_options.php'";
    print '</script>';
}

?>
