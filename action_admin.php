<?php

include('session.php');

if (isset($_POST['action'])){
  if ($_POST['action'] == 'edit') {
    $val = $_POST['value'];
    $pk = $_POST['pk'];

    if($val){
      $val='S';
    }else{
      $val='N';
    };

    if($_POST['col'] == 0){
      $sql_query = "update user set usr_id='$val' where usr_id='$pk'";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 1){
      $sql_query = "update user set email='$val' where usr_id='$pk'";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 2){
      $sql_query = "update user set passwd=sha1('$val') where usr_id='$pk'";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 4){
      $sql_query = "update user set admin='$val' where usr_id='$pk'";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 5){
      if($val){
        $val='S';
      }else{
        $val='N';
      }
      $sql_query = "update user set valid='$val' where usr_id='$pk'";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 6){
      $sql_query = "update user set color=substr('$val',2,6) where usr_id='$pk'";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    };
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
    };

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
  };
};

// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', usr_id, 'email', email, 'password', '', 'last_login', tms_upd, 'admin', case when admin='S' then TRUE ELSE FALSE end, 'active', case when valid='S' then TRUE ELSE FALSE end, 'color', concat('#',color))),']') as value FROM user";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
//echo $sql_row['value'];

$file = fopen('results_user.json', 'w');
fwrite($file, $sql_row['value']);
fclose($file);
?>
