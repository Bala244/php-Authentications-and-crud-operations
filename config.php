<?php


define('DB_SERVER', 'sql304.epizy.com');
define('DB_USERNAME', 'epiz_27121939');
define('DB_PASSWORD', 'ZYqWomDUnr');
define('DB_NAME', 'epiz_27121939_demo');
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>



