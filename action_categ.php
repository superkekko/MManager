<?php

include('session.php');

if (isset($_POST['action'])){
  if ($_POST['action'] == 'edit') {
    $val = $_POST['value'];
    $pk = $_POST['pk'];

    if($_POST['col'] == 0){
      //$sql_query = "update user set usr_id='$val' where usr_id='$pk'";
      echo 'ERR - id non modificabile';
      //$sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 1){
      $sql_query = "update cat set cat_name='$val' where cat_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 2){
      $sql_query = "";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 3){
      if($val == 'true'){
        $val='S';
      }else if($val == 'false'){
        $val='N';
      };
      $sql_query = "update cat set income='$val' where cat_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 5){
      $sql_query = "update cat set color=substr('$val',2,6) where cat_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    }else if($_POST['col'] == 6){
      $sql_query = "update cat set keyword='$val' where cat_id=$pk";
      echo $sql_query;
      $sql_exec = mysqli_query($db, $sql_query);
    };
  }elseif ($_POST['action'] == 'save') {
    $usrid = $_POST['usrid'];
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

    $sql_query = "insert into user (usr_id, email, passwd, admin, valid, color) values ('$usrid', '$email', sha1('$password'), '$admin', '$valid', substr('$color',2,6))";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
  }elseif ($_POST['action'] == 'delete') {
    $pk = $_POST['pk'];
    $sql_query = "delete from user where usr_id ='$pk'";
    echo $sql_query;
    $sql_exec = mysqli_query($db, $sql_query);
  };
};

// get detail
$sql_query = "SELECT CONCAT('[', GROUP_CONCAT(JSON_OBJECT('id', cat_id, 'name', cat_name, 'parent', parent_cat, 'income', case when income='S' then TRUE ELSE FALSE end, 'movements', num_mov, 'color', concat('#',color), 'keyword', keyword)),']') as value FROM cat";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
//echo $sql_row['value'];

$file = fopen('results_cat.json', 'w');
fwrite($file, $sql_row['value']);
fclose($file);
?>
