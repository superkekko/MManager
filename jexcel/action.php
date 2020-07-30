<?php

include('../session.php');

if (isset($_POST['action'])){
  if ($_POST['action'] == 'edit') {
    $val = $_POST['value'];
    $pk = $_POST['pk'];

    if($_POST['col'] == 1){
      $sql_query = "SELECT cat_id FROM cat where cat_name='$val'";
      $sql_exec = mysqli_query($db, $sql_query);
      $sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
      $cat_id = $sql_row['cat_id'];

      $sql_query = "update movement set cat_id=$cat_id where mov_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }
    if($_POST['col'] == 2){
      $sql_query = "update mov set val='$val' where mov_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }
    if($_POST['col'] == 3){
      $sql_query = "update mov set dat_mov='$val' where mov_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }
    if($_POST['col'] == 4){
      $sql_query = "update mov set usr_mov='$val' where mov_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }
    if($_POST['col'] == 5){
      $sql_query = "update mov set note='$val' where mov_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }
  }
};

// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', mov_id, 'category', cat_name, 'value', val, 'date', dat_mov, 'user', usr_mov, 'note', note)),']') as value FROM mov";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
//echo $sql_row['value'];

$file = fopen('results.json', 'w');
fwrite($file, $sql_row['value']);
fclose($file);
?>
