<?php

include('../session.php');

//action to edit table

//movements
if ($_POST['action'] == 'mov-edit') {
	$val = $_POST['value'];
	$col = $_POST['col'];
	$pk = $_POST['pk'];
	if ($col == 'val' && $val < 0){
		$sql_query = "update movement SET $col='$val', type='N' where mov_id=$pk";
	}elseif($col == 'val' && $val >= 0){
		$sql_query = "update movement SET $col='$val', type='P' where mov_id=$pk";
	}else{
		$sql_query = "update movement SET $col='$val' where mov_id=$pk";
	}
	echo $sql_query;
	$sql_exec = mysqli_query($db, $sql_query);
}elseif ($_POST['action'] == 'mov-save') {
	$cat = $_POST['cat'];
	$value = $_POST['value'];
	$date = $_POST['date'];
	$usr = $_POST['usr'];
	$note = $_POST['note'];

	if ($value < 0)
	{
	  $type = 'N';
	}else{
	  $type = 'P';
	}

	$sql_query = "insert into movement (cat_id, val, type, dat_mov, usr_mov, note, usr_id) values ($cat, $value, '$type', '$date', '$usr', '$note', '$usr')";
	echo $sql_query;
	$sql_exec = mysqli_query($db, $sql_query);
}elseif ($_POST['action'] == 'mov-delete') {
	$pk = $_POST['pk'];
	$sql_query = "delete from movement where mov_id in ($pk)";
	echo $sql_query;
	$sql_exec = mysqli_query($db, $sql_query);
//categories
}elseif ($_POST['action'] == 'cat-edit') {
	$val = $_POST['value'];
	$col = $_POST['col'];
	$pk = $_POST['pk'];
	if ($col == 'color'){
		$sql_query = "update cat set $col=substr('$val',2,6) where cat_id=$pk";
	}elseif($col == 'income'){
		if($val == 'true'){
			$val='S';
		}else if($val == 'false'){
			$val='N';
		};
		$sql_query = "update cat set $col='$val' where cat_id=$pk";
	}else{
		$sql_query = "update cat SET $col='$val' where cat_id=$pk";
	}
	echo $sql_query;
	$sql_exec = mysqli_query($db, $sql_query);
}elseif ($_POST['action'] == 'cat-save') {
    $parent_id = $_POST['parent_id'];
    $cat_name = $_POST['cat_name'];
    $color = $_POST['color'];
    $income = $_POST['income'];
    $keyword = $_POST['keyword'];

    if($income == 'true'){
      $income='S';
    }else if($income == 'false'){
      $income='N';
    };
	
	if($parent_id == 9999){
    $sql_query = "select AUTO_INCREMENT as value from INFORMATION_SCHEMA.TABLES where TABLE_NAME = 'category'";
    $sql_exec = mysqli_query($db, $sql_query);
	$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
	$max_cat_id = $sql_row['value'];
	
	$parent_id = $max_cat_id;
	echo $parent_id;
    };

    $sql_query = "insert into category (parent_id, cat_name, color, income, keyword) values ($parent_id, '$cat_name', substr('$color',2,6), '$income', '$keyword')";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
}elseif ($_POST['action'] == 'cat-delete') {
    $pk = $_POST['pk'];
    $sql_query = "delete from category where cat_id =$pk";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
//users
}elseif ($_POST['action'] == 'usr-edit') {
	$val = $_POST['value'];
	$col = $_POST['col'];
	$pk = $_POST['pk'];
	if ($col == 'color'){
		$sql_query = "update user set $col=substr('$val',2,6) where usr_id='$pk'";
	}elseif($col == 'admin' || $col == 'valid'){
		if($val == 'true'){
			$val='S';
		}else if($val == 'false'){
			$val='N';
		};
		$sql_query = "update user set $col='$val' where usr_id='$pk'";
	}elseif($col == 'password'){
		$sql_query = "update user set $col=sha1('$val') where usr_id='$pk'";
	}else{
		$sql_query = "update movement SET $col='$val' where mov_id=$pk";
	}
	echo $sql_query;
	$sql_exec = mysqli_query($db, $sql_query);
}elseif ($_POST['action'] == 'usr-save') {
    $usr_id = $_POST['usr_id'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $admin = $_POST['admin'];
    $valid = $_POST['valid'];
    $color = $_POST['color'];

    if($valid == 'true'){
      $valid='S';
    }else if($valid == 'false'){
      $valid='N';
    };
    if($admin == 'true'){
      $admin='S';
    }else if($admin == 'false'){
      $admin='N';
    };

    $sql_query = "insert into user (usr_id, email, passwd, admin, valid, color) values ('$usr_id', '$email', sha1('$password'), '$admin', '$valid', substr('$color',2,6))";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
}elseif ($_POST['action'] == 'usr-delete') {
    $pk = $_POST['pk'];
    $sql_query = "delete from user where usr_id ='$pk'";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
};

?>