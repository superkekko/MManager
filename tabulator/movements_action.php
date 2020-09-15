<?php

include('../session.php');

if (isset($_POST['action'])){
  if ($_POST['action'] == 'edit') {
    $val = $_POST['value'];
    $col = $_POST['col'];
    $pk = $_POST['pk'];
    if ($col == 'val' && $val < 0){
        $sql_query = "update movement SET $col='$val', type='N' where mov_id=$pk";
        echo $sql_query;
        $sql_exec = mysqli_query($db, $sql_query);
    }elseif($col == 'val' && $val >= 0){
        $sql_query = "update movement SET $col='$val', type='P' where mov_id=$pk";
        echo $sql_query;
        $sql_exec = mysqli_query($db, $sql_query);
    }else{
        $sql_query = "update movement SET $col='$val' where mov_id=$pk";
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
      $type = 'N';
    }else{
      $type = 'P';
    }

    $sql_query = "insert into movement (cat_id, val, type, dat_mov, usr_mov, note, usr_id) values ($cat, $value, '$type', '$date', '$usr', '$note', '$usr')";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
  }elseif ($_POST['action'] == 'delete') {
    $pk = $_POST['pk'];
    $sql_query = "delete from movement where mov_id in ($pk)";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
  }
};

if (isset($_GET['transaction'])){
// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('mov_id', mov_id, 'cat_id', cat_id, 'val', val, 'dat_mov', dat_mov, 'usr_mov', usr_mov, 'note', note) ORDER BY dat_mov desc),']') as value FROM mov";
$sql_exec = mysqli_query($db, $sql_query);
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo $sql_row['value'];

/*$sql_query = "select mov_id as id, cat_name as category, val as value, dat_mov as date, usr_mov as user, note from mov order by dat_mov desc";
$sql_exec = mysqli_query($db, $sql_query);
$val_det = array();
while($sql_row = mysqli_fetch_array($sql_exec,  MYSQLI_BOTH)) {
    $val_det[]=$sql_row;
  };
  
header('Content-Type: application/json');
echo json_encode($val_det);*/
};

if (isset($_GET['category'])){
// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('cat_id', cat_id, 'cat_name', cat_name) ORDER BY cat_id, parent_cat),']') as value FROM cat";
//$sql_query = "SELECT concat('{', group_concat(CONCAT(cat_id,':\"',cat_name,'\"')) , '}') AS value FROM category";
$sql_exec = mysqli_query($db, $sql_query);
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo $sql_row['value'];
};

if (isset($_GET['user'])){
// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('usr_id', usr_id) ORDER BY usr_id),']') as value FROM user";
//$sql_query = "SELECT concat('{', group_concat(CONCAT(cat_id,':\"',cat_name,'\"')) , '}') AS value FROM category";
$sql_exec = mysqli_query($db, $sql_query);
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);

header('Content-Type: application/json');
echo $sql_row['value'];
};

?>
