<?php

if (!defined('DB_SERVER')){
  define('DB_SERVER', 'localhost');
}
if (!defined('DB_USERNAME')){
  define('DB_USERNAME', 'mmanager');
}
if (!defined('DB_PASSWORD')){
  define('DB_PASSWORD', 'mmanager');
}
if (!defined('DB_DATABASE')){
  define('DB_DATABASE', 'mmanager');
}

$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
