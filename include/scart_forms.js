/*-- filename: gahscart/include/scart_forms.js
      - Wed 25 Dec 2013 09:03:52 PST 
      - functions from: gah_javascript2/...lab14_o2-todoFeatures.js
      - updated for gahscart/include/scart_forms.js
     Intro PHP/SQL lesson 15 Final Project - Part 2
 */

/* main initialize setup for main display page */
/* initialize the function init() to activate the submitButton
   equivalent to <body onload="init()"> in scart-account_register.php
 */
window.onload = init;

/* what to initialize with main display page load */
function init() {
   var submitButton = document.getElementById("submit");
   if (submitButton) submitButton.onclick = getFormData;  // initialize submit form and await more
// alert("submitButton is initialized");  // init confirm alert 
}

/* customer registration form data input.  check form for values, then display on page */
// function get the html form data and check for content
function getFormData() {
   var cust_fname = document.getElementById("cust_fname").value;
   if (checkInputText(cust_fname, "first name - REQUIRED!")) return false;
   
   var cust_lname = document.getElementById("cust_lname").value;
   if (checkInputText(cust_lname, "last name - REQUIRED!")) return false;
   
   var cust_address = document.getElementById("cust_address").value;
   if (checkInputText(cust_address, "street address - REQUIRED!")) return false;
   
   var cust_city = document.getElementById("cust_city").value;
   if (checkInputText(cust_city, "city - REQUIRED!")) return false;
   
   var cust_state = document.getElementById("cust_state").value;
   if (checkInputText(cust_state, "state - REQUIRED!")) return false;
   
   var cust_zip = document.getElementById("cust_zip").value;
   if (checkInputText(cust_zip, "zipcode - REQUIRED!")) return false;
   
   var cust_phone = document.getElementById("cust_phone").value;
   if (checkInputText(cust_phone, "contact telephone - REQUIRED!")) return false;
   
   var cust_email = document.getElementById("cust_email").value;
   if (checkInputText(cust_email, "email address - REQUIRED!")) return false;
   
   var passwd = document.getElementById("cust_passwd").value;
   if (checkInputText(passwd, "password - REQUIRED!")) return false;
}

/* login form data input.  check form for values, then display on page */
// function get the html form data and check for content
function getLoginData() {
   var userid = document.getElementById("cust_userid").value;
   if (checkInputText(userid, "Account username - REQUIRED!")) return false;
   
   var passwd = document.getElementById("cust_passwd").value;
   if (checkInputText(passwd, "Account password - REQUIRED!")) return false;
}
// function to retrieve login information from customer support
function getLoginInfo() {
   var custserv  = "To Retrieve Your Login Information\n";
       custserv += "\t Call Customer Service";
   alert(custserv);
}

/* neworders product selection scart form data input.  check form for values, then display on page */
// function get the html form data and check for content
function getOrderData() {
// alert('hi');
   // the form row number is a string version of an integer
   var rowid_str = document.getElementById("row_number").value;
   // the form quantity update box checked for the row number 
   var chkboxid = "chkbox" + rowid_str;
   var chkboxset = document.getElementById(chkboxid).checked;
   if (chkboxset) {
       // using new form quantity needs to be numeric >=1 and <= 999
       var qtyid = "qty" + rowid_str;
       var formqty_str = document.getElementById(qtyid).value;
       var formqty_int = parseInt(formqty_str,10);
       if (formqty_int >= 1 && formqty_int <= 999) {
           // numeric value OK, continue no error message
           alert("Order 1-999 - ACCEPTED!\nUsing new order quantity = \t" + formqty_int);  // init confirm alert 
       } else {
           // numeric value NOT OK, continue with error message
           if (checkInputText("", "Order 1-999 - REQUIRED!\nPlease fix "+ formqty_int)) return false;
       }
   } else {
       // current table quantity assumed to be numeric >=1 and <= 999
       // numeric value OK, continue no error message
       var ords_qtyid  = "ords_quantity" + rowid_str;
       var ords_qtyset = document.getElementById(ords_qtyid).value;
       alert("Order 1-999 - ACCEPTED!\nUsing saved order quantity = \t" + ords_qtyset);  // init confirm alert 
   }
}

/* neworders product select form data input.  check form for values, then display on page */
// function get the html form data and check for content
function getSelectData() {
   // javascript vars test popup. no php print script...script
   // alert("Here we are in scart_forms javascript - getSelectData function!\n\n");
   // exit();  
   // the form row number, from selected radio button value is a string version of an integer
   // var radioid_len = document.getElementById("radio").length;
   var radioid_len = document.product.radio.length;
   for (i = 0; i <radioid_len; i++) {
       if (document.product.radio[i].checked) {
           var radioid_str = document.product.radio[i].value;
       }
   }
   if (radioid_str) {
       // alert("exists");
       // radio button selected, value is the row with the order quantity
       // var rowid_str = document.getElementById("radio").value;
       // the form quantity update box checked for the row number 
       var qtyboxid = "qty" + radioid_str;
       var formqty_str = document.getElementById(qtyboxid).value;
       var formqty_int = parseInt(formqty_str,10);
       // using new form quantity needs to be numeric >=1 and <= 999
       if (formqty_int >= 1 && formqty_int <= 999) {
           // numeric value OK, continue no error message
           alert("Order 1-999 - ACCEPTED!\nUsing new order quantity = \t" + formqty_int);  // init confirm alert 
       } else {
           // numeric value NOT OK, continue with error message
           if (checkInputText("", "Order 1-999 - REQUIRED!\nPlease fix "+ formqty_int)) return false;
       }
   } else {
       // alert("does not exist");
   }
   if (checkInputText(radioid_str, "Please select an item - REQUIRED!")) return false;
}

/* product updates product select form data input.  check form for values, then display on page */
// function get the html form data and check for content
function getProductData() {
   // javascript vars test popup. no php print script...script
   // alert("Here we are in scart_forms javascript - getProductData function!\n\n");
   // exit();  
   // the form row number, from selected radio button value is a string version of an integer
   // var radioid_len = document.getElementById("radio").length;
   var radioid_len = document.product.radio.length;
   for (i = 0; i <radioid_len; i++) {
       if (document.product.radio[i].checked) {
           var radioid_str = document.product.radio[i].value;
       }
   }
   if (radioid_str) {
       // alert("radio button exists");
       /*
       // radio button selected, value is the row with the order quantity
       // var rowid_str = document.getElementById("radio").value;
       // the form quantity update box checked for the row number 
       var qtyboxid = "qty" + radioid_str;
       var formqty_str = document.getElementById(qtyboxid).value;
       var formqty_int = parseInt(formqty_str,10);
       // using new form quantity needs to be numeric >=1 and <= 999
       if (formqty_int >= 1 && formqty_int <= 999) {
           // numeric value OK, continue no error message
           alert("Order 1-999 - ACCEPTED!\nUsing new order quantity = \t" + formqty_int);  // init confirm alert 
       } else {
           // numeric value NOT OK, continue with error message
           if (checkInputText("", "Order 1-999 - REQUIRED!\nPlease fix "+ formqty_int)) return false;
       }
       */
   } else {
       // alert("radio button does not exist");
   }
   if (checkInputText(radioid_str, "Please select an item - REQUIRED!")) return false;
}
/* product form data input.  check form for values, then display on page */
// function get the html form data and check for content
function getProdFormData() {
   // javascript vars test popup. no php print script...script
   // alert("Here we are in scart_forms javascript - getProdFormData function!\n\n");
   // exit();  
   var prod_bookname = document.getElementById("prod_bookname").value;
   if (checkInputText(prod_bookname, "Appropriate Book Title - REQUIRED!")) return false;
   
   var prod_bookprice = document.getElementById("prod_bookprice").value;
   if (checkInputText(prod_bookprice, "Appropriate Book Price, e.g. 123.20 - REQUIRED!")) {
       return false;
   } else {    
       var regex  = /^\d+(?:\.\d{0,2})$/;
       var numStr = prod_bookprice;  // "123.20";
       if (regex.test(numStr)) {
           // alert("Number is valid");
       } else {
           // alert("Number is not valid, format is nnn.nn, e.g. 123.20");
           return false;
       }
   }
   
   var prod_release = document.getElementById("prod_release").value;
   if (checkInputText(prod_release, "Book Release Date - REQUIRED!")) return false;

   /* product form data input.  check form for values, then display on page */
   // function get html form date from pop-up calendar-datepicker
   /* **********************************************************************************
      --- Copyright 2010 Itamar Arjuan ---
      <script type="text/javascript">  window.onload = function(){ ..... } </script>
      <!-- Copy-paste, modified 2x - target:"inputField", dateFormat:"%Y-%m-%d" -->
      **********************************************************************************
      window.onload = function() {
             new JsDatePick({
                       useMode:2,
                       target:"prod_release",
                       dateFormat:"%Y-%m-%d"
             });
      };
    */
   /* the calendar-datepicker form data input fix. duplicate hidden saved values  */
   // alert(document.getElementById("prod_release2").value);
   document.getElementById("prod_release2").value = document.getElementById("prod_release").value;
   // alert(document.getElementById("prod_release2").value);
   /*
   alert("Here we are leaving scart_forms javascript - getProdFormData function!\n\n");
   alert("\nprod_bookname = "  + prod_bookname +
         "\nprod_bookprice = " + prod_bookprice +
         "\nprod_release = "   + prod_release +
         "\nprod_release2 = "  + document.getElementById("prod_release2").value +
         "\n");
   */
}

/* common function for check form for values, then display on page */
// function check input for missing form data
function checkInputText(value, msg) {
   if (value == null || value == "" ) {
      alert(msg);
      return true;  // missing field contents
   }
   return false;    // complete field contents
}
