<?php
/**
 * Created by PhpStorm.
 * --------------------------------------------
 * User: garyh  * Date: Sun 07 Dec 2014 18:23:50 PST 
 * # enhanced ost_library/checkCookies.php for all cart data handling functions
 * (1) cookie functions - 3x 
 *     - savesession_scartcookies($selecteditemarray)
 *     -    retrieve_scartcookies($selectedcustid)
 *     -      remove_scartcookies()
 * (2) mysql functions  - 6x
 *     - new_orderid($connID,$data_base,$table_name)
 *     - get_emailaddress($connID,$data_base,$table_name,$custid)
 *     - add_table_data($connID,$database)
 *     - print_order_detail($orderid)
 *     - get_product_name($pid)
 *     - get_price($pid)
 * (3) session var functions - 7x
 *     - get_order_total()
 *     - get_cartvars()
 *     - clear_cartvars()
 *     - addtocart($pid,$q)
 *     - product_exists($pid)
 *     - remove_product($pid)
 *     - change_quantity($pid,$q)
 * --------------------------------------------
 * User: garyh  * Date: 10/5/14  * Time: 5:12 PM
 * # enhanced ost_library/checkCookies.php for all cookie handling functions
 * (1) added   function savesession_scartcookies ($selectedcustid)
 * (2) removed function addselect_scartcookies ($selecteditemarray)
 * (3) added 6 new session handling functions for manupilating the shopping cart
 * --------------------------------------------
 * User: garyh  * Date: 9/1/14  * Time: 9:47 PM
 * # implement ost_library/checkCookies.php for all cookie handling functions
 * include("ost_library/checkCookies.php");
 * (1) scart_orders_status.php     - function retrieve_scartcookies  ($selectedcustid)
 * (2) scart-products_addorder.php - function addselect_scartcookies ($selecteditemarray)
 */
#--------------------------#
#   Functions              #
#--------------------------#
#---  cookie   --  3x  ----#
#--------------------------#
/* 2014-09-01, gah.  start code fragment from scart-orders_status.php */
/* (1) cookie functions 3x - 1 of 3  */
/* save session variables for subsequent cart reconstruction */
function savesession_scartcookies ($selecteditemarray) {
    $cartitemcount = count($selecteditemarray);
    if ( isset($_SESSION['custCart_ID']) ) {
	$cartItems = $_SESSION['custCart_ID'];  // the customer saved COOKIE cart items array
    } else {
	$cartItems = "cartItems".$_SESSION['custID'];  // custid specific $_COOKIE array
    }
    $cartArray = array(); // initialize customer shopping cart with current items
    // add $_SESSION cart array, jason encode, and set the new cookie
    array_push($cartArray, $selecteditemarray);  // push next item onto cart array
    $json = json_encode($cartArray, true);  //
    // setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/'); // 86400 = 1 day
    setcookie($cartItems, $json, time() + (86400 * 5), '/');  // 86400-1day, public-root
    return $cartitemcount;  // customer shopping cart item stored for return 
}  // end of function savesession_scartcookies
/* (1) cookie functions 3x - 2 of 3  */
/* check for previously built cart and reconstruct */
function retrieve_scartcookies ($selectedcustid) {
/* retrieve and reconstruct customer shopping cart array */
// $newitem = array("index" => $nextitem, "bookid" => 6, "qty" => 30 );
// $newitem = array();   // individual cart items
    $cartItems = "cartItems".$selectedcustid;  // custid specific $_COOKIE array
    $_SESSION['custCart_ID'] = $cartItems;  // the SESSION and COOKIE customer cart items array
    // $cartArray = array(); // initialize to 0 then load shopping cart with current items
    if( !isset($_COOKIE[$cartItems]) ) {
        print '<script type="text/javascript">';
        print 'alert("shopping cart for custid => '.$cartItems.' is not set !\n")';
        print '</script>';
    } else {
        // get the cookie back
        $scart_cookie = $_COOKIE[$cartItems];
        $scart_cookie = stripslashes($scart_cookie);
        $savedCartArray = json_decode($scart_cookie, true);
	// $_SESSION[$cartItems] = $savedCartArray; // initialize to 0 then load shopping cart with current items
	$cartArray = array();  // initialize to 0 then load shopping cart with current items
        /* variables for current scart-orders_checkout.php  */
        $sesscustid = $selectedcustid; // $_SESSION['custID'] for kolson = 12
        $savedCartArraylength=count($savedCartArray);  // 2-dimensional array
        // for current customer check and load scart items and return the array
        if ( $savedCartArraylength > 0 ) {
            foreach ($savedCartArray as $key => $val) {
                // found customer scart item, print to list
		foreach ($savedCartArray[$key] as $key2 => $val2) {
                // if ( $savedCartArray[$key]["custid"] == $sesscustid ) {
                    $newitem = array();   // individual cart line items
                    foreach ($savedCartArray[$key][$key2] as $field => $contents) {
                        $newitem[$field] = $contents;   // add next item element to line item
		    } // end of customer shopping cart items
		    array_push($cartArray, $newitem);   // push each existing item onto cart                     }
		} // end of customer shopping cart items
	    }  // end of $savedCartArray;  // customer shopping cart items
        } else {
            print '<script type="text/javascript">';
            print 'alert("shopping cart for custid => '.$sesscustid.' is empty !\n")';
            print '</script>';
        }
    }  // end of retrieve_scartcookies items - with or without items
    $_SESSION[$cartItems] = $cartArray; // initialize to 0 then load shopping cart with current items
    return $cartArray;  // return >0 length array - customer shopping cart items
}
/* (1) cookie functions 3x - 3 of 3  */
/* remove cookie variables for rebuilding cart from empty contents */
function remove_scartcookies () {
    // Should be at least 3 variables containing customer specific COOKIE name
    // 1 - $cartItems = "cartItems".$selectedcustid;  // custid specific $_COOKIE array
    // 2 - $cartItems = "cartItems".$_SESSION['custID'];  // custid specific $_COOKIE array
    $cartItems = $_SESSION['custCart_ID'];  // the customer saved COOKIE cart items array
    if ( isset($_COOKIE[$cartItems]) ) {
	 unset($_COOKIE[$cartItems]);
	 setcookie($cartItems, '', time() - 3600, '/');  // have to set to null and past time out
	 $result_val = 1;  // COOKIE found and unset
    } else {
	 $result_val = 0;  // COOKIE not found and unset
    }
    return $result_val;  // customer shopping cart item stored for return 
}  // end of function remove_scartcookies
/* 2014-09-01, gah.  finish code fragment from scart-products_addorder.php */

#--------------------------#
#---  mysql    --  6x  ----#
#--------------------------#
/* 2014-10-05, gah. start code fragments from qualitycodes.com building a shopping cart */
/* (2) mysql functions 6x - 1 of 6 */
// gah database function retrieve next auto-increment orderid
function new_orderid ($connID,$data_base,$table_name) {
   $command = "select auto_increment from information_schema.tables 
                where table_schema = '$data_base' and table_name = '$table_name'";
   $result = mysql_query($command);
   if ($result) {
        $nextorderid = mysql_result($result, 0);
        return $nextorderid;  // return next orderid
   } else {
        print '<script type="text/javascript">';
        print 'alert("query result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
                     .mysql_error($connID).'\nNo results found - Returning empty!\n")';
        print '</script>';  
        // exit();  
        return 0;
   }
}
/* (2) mysql functions 6x - 2 of 6 */
// gah database function retrieve customer email address for notifications
function get_emailaddress ($connID,$data_base,$table_name,$custid) {
	$custid=intval($custid);
	$command = "select * from $table_name where custid = $custid";
   $result = mysql_query($command);
   if ($result) {
        $emailaddress = mysql_result($result, 0, cust_email);
        // next alert message print 'alert("new_orderid command is \n\n'.$command.'\n")';
        print '<script type="text/javascript">';
        print 'alert("Retrieved emailaddress from '.$table_name.' for custid '
		      .$custid.'\n\nMessage being emailed to '.$emailaddress.'\n")';
        print '</script>';  

        return $emailaddress;  // return customer email address
   } else {
        print '<script type="text/javascript">';
        print 'alert("query result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
                     .mysql_error($connID).'\nNo results found - Returning empty!\n")';
        print '</script>';  
        // exit();  
        return 0;
   }
}
/* (2) mysql functions 6x - 3 of 6 */
// gah database function insert new order data in tables
function add_table_data($connID,$database){
    // new order data in tables
	$order_info  = "scart_purchases";        // purchases table - order header info
	$order_items = "scart_purchdetail";      // purchdetail table - order item info
	$nextorderid = $_SESSION['newOrder_ID']; // SESSION variable for next order id
    // session order information references
	$cart = $_SESSION['custCart_ID'];       // the SESSION and COOKIE customer cart items array
	$orderitems  = count($_SESSION[$cart]); // number of items on the order
	$currentcust = $_SESSION['custID'];     // => 23
	$currentuser = $_SESSION['strUser'];    // => ghornbec
	$order_stamp = $_SESSION['login_time']; // => 2014-12-07 15:58:49
	$ordertotal = get_order_total();    // current carts (session vars) order total
	// insert command table1 new order header information
	$cmdorder = "insert into $order_info (custid, order_quantity, order_total, cust_userid, order_stamp)
		     values ($currentcust, $orderitems, $ordertotal, '$currentuser', '$order_stamp');";
	$result = mysql_query($cmdorder);
	if ($result) {
	    // insert good table1 new order header information
	    // insert command table2 new order item details
	    // retireve cart item contents array
	    $data = get_cartvars();   // individual cart line items
	    // =========   start order items data records from $_SESSION['custCart_ID'] => cartItems23   =========
	    for ($row = 0; $row < count($data); $row++) {
		 $rowbookid = $data[$row]['bookid']; // current row bookid
		 $rowprice = get_price($rowbookid);  // current row price
		 $rowqty = $data[$row]['qty'];       // current row quantity
		 $rowitem = intval($row+1);          // current row order line item
		 $cmditems = "insert into $order_items 
				(order_quantity, bookid, orderid, line_item, order_price, order_stamp)
			 values ($rowqty, $rowbookid, $nextorderid, $rowitem, $rowprice, '$order_stamp');";
		 $result2 = mysql_query($cmditems);
		 if ($result2) {
		    /* echo "New record created successfully";  */
		    // print '<script type="text/javascript">';
		    // print 'alert("insert order_item number '.$row.' \n\insert into '
			//	.$order_items.' for bookid id '.$rowbookid.'\n\n")';
		    // print '</script>';  
		 } else {
		    // usually a duplicate error, but could be something else
		    print '<script type="text/javascript">';
		    print 'alert("query result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
				 .mysql_error($connID).'\nNo results found - Returning empty!\n")';
		    print '</script>';  
		 }
	    } // end of single row for statement
	    // =========   finish order items data records from $_SESSION['custCart_ID'] => cartItems23   =========
	// echo "New record created successfully";
	    print '<script type="text/javascript">';
	    print 'alert("New order inserted into '
		    .$order_info.'\n\n for new order id '.$nextorderid.'\n\n")';
	    print '</script>';  
	    return $nextorderid;
	} else {
	    print '<script type="text/javascript">';
	    print 'alert("query result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
			  .mysql_error($connID).'\nNo results found - Returning empty!\n")';
	    print '</script>';  
	    return 0;
	}
}
/* (2) mysql functions 6x - 4 of 6 */
// gah database function retrieve product description
function get_product_name($pid){
	$products = "scart_products";  // products table
	$serial   = "bookid";          // products key
	$name     = "book_name";       // products name
	$result=mysql_query("select $name from $products where $serial=$pid")
              or die ("Query $products and pid - $pid - failed error message: \"" . mysql_error () . '"');
	$row=mysql_fetch_array($result);
	return $row[$name];
}
/* (2) mysql functions 6x - 5 of 6 */
// gah database function retrieve product price
function get_price($pid){
	$products = "scart_products";  // products table
	$serial   = "bookid";          // products key
	$result=mysql_query("select price from $products where $serial=$pid")
              or die ("Query '$products' and '$pid' failed with error message: \"" . mysql_error () . '"');
	$row=mysql_fetch_array($result);
	return $row['price'];          // products price
}
/* (2) mysql functions 6x - 6 of 6 */
// gah database function retrieve order detail items
function print_order_detail($orderid){
    // product data in table
	$order_items = "scart_purchdetail";      // purchdetail table - order item info
    // retrieve command table order details
	$intorderid = intval($orderid);   // => 23  mysql value in integer
	$cmdorder = "select * from $order_items where orderid = $intorderid order by line_item asc;";
	$result2 = mysql_query($cmdorder)
              or die ("Query '$cmdorder' failed with error message: \"" . mysql_error () . '"');
	$num_rows2 = mysql_num_rows($result2);
	// $row_info = mysql_fetch_array($result2);
	if ( $num_rows2  >= 1 ) {
	    // =========   start order items data records  =========
	    $row = 0;  // line_item values
	    while ($row_info = mysql_fetch_object($result2)) {
		   $data[$row]['item']   = $row_info->line_item; // current row item number
		   $data[$row]['qty']    = $row_info->order_quantity; // current row quantity
		   $data[$row]['bookid'] = $row_info->bookid; // current row bookid
		   $data[$row]['price']  = $row_info->order_price; // current row book price
		   $row ++;  // next line_item values
	    }
	    return $data;  // return order details
	} else {
	    print '<script type="text/javascript">';
	    print 'alert("query result mysql_errno is = '.mysql_errno($connID).' !\nmysql_errormsg = '
			  .mysql_error($connID).'\nNo results found - Returning empty!\n")';
	    print '</script>';  
	    // exit();  
	    return 0;      // return failed order detail
   }
}

/* 2014-10-05, gah. start code fragments from qualitycodes.com building a shopping cart */

#--------------------------#
#---  session  --  7x  ----#
#--------------------------#
/* 2014-10-05, gah. start code fragments from qualitycodes.com building a shopping cart */
/* (3) session var functions - 1 of 7 */
// gah modifications session variables functions 
function get_order_total(){
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
	$max=count($_SESSION[$cart]);
	$sum=0;
	for($i=0;$i<$max;$i++){
		$pid=$_SESSION[$cart][$i]['bookid'];
		$q=$_SESSION[$cart][$i]['qty'];
		$price=get_price($pid);
		$sum+=$price*$q;
	}
	return $sum;
}
/* (3) session var functions - 2 of 7 */
function get_cartvars(){
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
	// Check for session array with current cart items 
	if ( isset($_SESSION[$cart]) ) {
	    // build an associative array of cart items
	    $cartdata = array();   // individual cart line items
	    foreach ($_SESSION[$cart] as $key => $val) {
		// found customer scart item, print to list
		foreach ($_SESSION[$cart][$key] as $field => $contents) {
			$cartdata[$key][$field] = $contents;   // add next item element to line item
		}
	    }
	} // end check for session array with current cart items
	return $cartdata;
}
/* (3) session var functions - 3 of 7 */
function clear_cartvars(){
	// clear an associative array of cart items
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
	unset($_SESSION[$cart]);
	if (count($_SESSION['custCart_ID']) > 1 )  {
	    return 1;  // failed not reset for some reason
	} else {
	    return 0;  // success reset
	}
}
/* (3) session var functions - 4 of 7 */
// session variable functions gah modifications
function addtocart($pid,$q){
	// $newitem = array("index" => $nextitem, "bookid" => 01 , "qty" => 100" );
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
	$newitem=array();
	
	// checks for bad pid - missing or existing
	if($pid<1 or $q<1) return;  // pid missing return no message
	
	if(is_array($_SESSION[$cart])){
		if(product_exists($pid)) {
		   print '<script type="text/javascript">';
		   print 'alert("Error - from check cart contents - product_exists for pid = '
				 .$pid.'!\n--- Cart Not Changed, Use Edit Quantity!\n\n")';
		   print '</script>';
                   return $newitem;  // pid existing return empty array no message
		}
		$max=count($_SESSION[$cart]);
		$_SESSION[$cart][$max]['bookid']=$pid;
		$_SESSION[$cart][$max]['qty']=$q;
		$newitem = $_SESSION[$cart][$max];
		
	} else {
		$_SESSION[$cart]=array();
		$_SESSION[$cart][0]['bookid']=$pid;
		$_SESSION[$cart][0]['qty']=$q;
		$newitem = $_SESSION[$cart][0];
	}
	return $newitem;
}
/* (3) session var functions - 5 of 7 */
function product_exists($pid){
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
	$pid=intval($pid);
	$max=count($_SESSION[$cart]);
	$flag=0;
	for($i=0;$i<$max;$i++){
		if($pid==$_SESSION[$cart][$i]['bookid']){
			$flag=1;
			// break;
		}
	}
	return $flag;
}
/* (3) session var functions - 6 of 7 */
function remove_product($pid){
	// scart-products_addorder.php?action=checkout -- id="submit_delitem" 
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
        $returnpid = 0;  // default 0 failed
	$pid=intval($pid);
	$max=count($_SESSION[$cart]);
	for($i=0;$i<$max;$i++){
		if($pid==$_SESSION[$cart][$i]['bookid']){
			unset($_SESSION[$cart][$i]);
                        $returnpid = $pid;  // success > 0
			break;
		}
	}
	$_SESSION[$cart]=array_values($_SESSION[$cart]);
        return $returnpid;  // default 0 failed, success > 0
}
/* (3) session var functions - 7 of 7 */
function change_quantity($pid,$q){
	// scart-products_addorder.php?action=checkout -- id="submit_editqty" 
	$cart = $_SESSION['custCart_ID'];  // the SESSION and COOKIE customer cart items array
	$pid=intval($pid);
	$max=count($_SESSION[$cart]);
	if(product_exists($pid)) {
	  for($i=0;$i<$max;$i++){
		if($pid==$_SESSION[$cart][$i]['bookid']){
		   $_SESSION[$cart][$i]['qty']=$q;
		   // break;
		}
	  }
	   print '<script type="text/javascript">';
	   print 'alert("Quantity Updated!\n--- New Quantity = '.$q.'!\n\n")';
	   print '</script>';
           return $pid;  // pid existing return empty array no message
	}

}
/* 2014-10-05, gah. finish code fragments from qualitycodes.com building a shopping cart */

?>
