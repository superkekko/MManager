<?php

if(isset($_FILES['file']['tmp_name'])) {
  if(is_uploaded_file($_FILES['file']['tmp_name'])) {
    error_log('EE - inizio lettura file');
    $filename='import.log';
    $log = "log ".date('d/m/Y h:m:s')."\n";
    $err=0;
    $file = $_FILES['file']['tmp_name'];
    $handle = fopen($file, "r");
    $c = 0;
    $i = 0;

    $sql_del = "delete from importcsv";
    mysqli_query($db, $sql_del);

    while(($filesop = fgetcsv($handle, 1000, ";")) !== false) {
      $dat_mov = mysqli_real_escape_string($db, $filesop[0]);
      $description = mysqli_real_escape_string($db, $filesop[1]);
      $user = mysqli_real_escape_string($db, $filesop[2]);
      $value = str_replace('.', '', mysqli_real_escape_string($db, $filesop[3]));

      if ($i>0){
        $sql_file = "INSERT INTO importcsv (dat_mov, description, value, usr_mov) VALUES ('$dat_mov', '$description', '$value', '$user')";
        error_log('EE - '.$sql_file);
        $result_sql = mysqli_query($db, $sql_file);
        if (!$result_sql){
          $log .= "not loaded (".mysqli_error($db)."): ".$dat_mov." | ".$description." | ".$user." | ".$value."\n";
        }
      }
      $i=$i+1;
    }
    $sql_file = "CALL csv_import()";
    mysqli_query($db, $sql_file);

    $log .="\n";

    $sql_ver = "select * from importcsv c where c.mov_imp = ''";
    $result_ver = mysqli_query($db, $sql_ver);
    $count      = mysqli_num_rows($result_ver);
    if ($count > 0){
      while ($res_usr = mysqli_fetch_array($result_ver, MYSQLI_ASSOC)){
        $log .= "not imported: ".$res_usr['dat_mov']." | ".$res_usr['description']." | ".$res_usr['usr_mov']." | ".$res_usr['value']."\n";
      }
    }
    error_log('EE - '.$log);
    file_put_contents($filename, $log);
    $log = true;
    }
  }

?>