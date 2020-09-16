<?php
include('../session.php');

//return json data for mysql tables

if (isset($_GET['transaction'])){
	// movements
	$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('mov_id', mov_id, 'cat_id', cat_id, 'val', val, 'dat_mov', dat_mov, 'usr_mov', usr_mov, 'note', note) ORDER BY dat_mov desc),']') as value FROM mov";
	$sql_exec = mysqli_query($db, $sql_query);
	$sql_exec = mysqli_query($db, $sql_query);
	$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);

	header('Content-Type: application/json');
	echo $sql_row['value'];
}elseif(isset($_GET['categories'])){
	// categories
	$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('cat_id', cat_id, 'cat_name', cat_name, 'parent_id', parent_cat, 'income', case when income='S' then TRUE ELSE FALSE end, 'num_mov', num_mov, 'color', concat('#',color), 'keyword', keyword)),']') as value FROM cat";
	$sql_exec = mysqli_query($db, $sql_query);
	$sql_exec = mysqli_query($db, $sql_query);
	$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);

	header('Content-Type: application/json');
	echo $sql_row['value'];
}elseif(isset($_GET['users'])){
	// users
	$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('usr_id', usr_id, 'email', email, 'tms_upd', tms_upd, 'admin', case when admin='S' then TRUE ELSE FALSE end, 'valid', case when valid='S' then TRUE ELSE FALSE end, 'color', concat('#',color))),']') as value FROM user";
	$sql_exec = mysqli_query($db, $sql_query);
	$sql_exec = mysqli_query($db, $sql_query);
	$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);

	header('Content-Type: application/json');
	echo $sql_row['value'];
}

?>