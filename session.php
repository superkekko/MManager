<?php
include('config.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$user_check = $_SESSION['login_user'];

$ses_sql = mysqli_query($db, "select usr_id from user where usr_id = '$user_check' and valid='S'");
$row = mysqli_fetch_array($ses_sql, MYSQLI_ASSOC);
$login_session = $row['usr_id'];

if (!isset($login_session)) {
    header("location: login.html");
}
?>
