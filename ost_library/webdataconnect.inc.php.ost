<?php    //  -*- C++ -*-
// Original Source - kofler.cc general/mylibraryconnect.inc.php
// FglWebdata Source - ../fgl_library/mywebdataconnect.inc.php
// 2003dec05, gah - setup function connect_to_mywebdata()

// connect to MySQL, activate database 'mywebdata';
// in case of a connection error, show a complete HTML
// document with a short error message

function connect_to_mywebdata() {
// https://students.oreillyschool.com 
   $host = "sql.useractive.com";  // the server where the database resides
   $user = "ghornbec";            // sandbox user login
   $pw = "1001san";               // sandbox password
   $database = "ghornbec";        // my database, same userid as sandbox login

   $connID = @mysql_connect($host, $user, $pw);

   if ($connID) {
     mysql_select_db($database);  // default database
     return $connID;
   }
   else {
      echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0//EN\">
            <html><head>
            <title>Sorry, no connection ...</title>
            <body><p>Sorry, no connection to database ...</p></body>
            </html>\n"; 
      exit();                        // quit PHP interpreter
   }
}

?>
