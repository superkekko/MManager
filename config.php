<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'mmanager');
define('DB_PASSWORD', 'mmanager');
define('DB_DATABASE', 'mmanager');
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>
