<?php

include('../session.php');

// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', mov_id, 'category', cat_name, 'value', val, 'date', dat_mov, 'user', usr_mov, 'note', note)),']') as value FROM mov";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
//echo $sql_row['value'];

$file = fopen('results.json', 'w');
fwrite($file, $sql_row['value']);
fclose($file);
?>
