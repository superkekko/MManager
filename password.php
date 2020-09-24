<?php
if (!isset($user)) {
    $user = $_SESSION['login_user'];
}

$sql_admin    = "select * from user where usr_id='$user' and admin='S'";
$result_admin = mysqli_query($db, $sql_admin);
$count_admin  = mysqli_num_rows($result_admin);


if (isset($_POST['ch_pwd'])) {
    $pwd     = mysqli_real_escape_string($db, $_POST['ch_pwd']);
    $sql_pwd = "update user set passwd = sha1('$pwd') where usr_id='$user'";
    mysqli_query($db, $sql_pwd);
}
?>