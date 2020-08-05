<?php

include('session.php');

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
      if ($val < 0)
      {
        $type = N;
      }else{
        $type = P;
      }
      $sql_query = "update movement set val='$val', type='$type' where mov_id=$pk";
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
  }elseif ($_POST['action'] == 'save') {
    $cat = $_POST['cat'];
    $value = $_POST['value'];
    $date = $_POST['date'];
    $usr = $_POST['usr'];
    $note = $_POST['note'];

    if ($value < 0)
    {
      $type = N;
    }else{
      $type = P;
    }

    $sql_query = "SELECT cat_id FROM cat where cat_name='$cat'";
    $sql_exec = mysqli_query($db, $sql_query);
    $sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
    $cat_id = $sql_row['cat_id'];

    $sql_query = "insert into movement (cat_id, val, type, dat_mov, usr_mov, note, usr_id) values ($cat_id, $value, '$type', '$date', '$usr', '$note', '$usr')";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
  }elseif ($_POST['action'] == 'delete') {
    $pk = $_POST['pk'];
    $sql_query = "delete from movement where mov_id in ($pk)";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
  }
};

// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', mov_id, 'category', cat_name, 'value', val, 'date', dat_mov, 'user', usr_mov, 'note', note) ORDER BY dat_mov desc),']') as value FROM mov";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
//echo $sql_row['value'];

$file = fopen('results.json', 'w');
fwrite($file, $sql_row['value']);
fclose($file);
?>
