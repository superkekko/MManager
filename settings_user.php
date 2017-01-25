<?php

$confirm = "";

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
    $confirm = $lang['46'];
}
?>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $lang['42'];?></div>
    <div class="panel-body"><form name="form_insert" method="post" action="settings.php" role="form">
        <div class="row">
            <div class="col-xs-8">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-sunglasses"></span></span>
                    <input type="password" class="form-control" name="ch_pwd" id="ch_pwd" placeholder="<?php echo $lang['43'];?>"></div></div>
            <div class="col-xs-2">
					<input type="submit" name="submit" value="<?php echo $lang['44'];?>" class="btn btn-default"></div>
	</div>
	<div> <?php
echo $confirm;
?></div>
    </form>
	</div>
</div>
</div>
</div>