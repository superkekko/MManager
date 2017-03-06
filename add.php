<?php
if (file_exists('upload.log')) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename('upload.log').'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('upload.log'));
    readfile('upload.log');
	unlink('upload.log');
    exit;
}
if (file_exists('import.log')) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename('import.log').'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize('import.log'));
    readfile('import.log');
	unlink('import.log');
    exit;
}

include('session.php');
include('header.html');
include('nav.php');
include('./locale/'.$_SESSION['lang'].'.php');

$error = "";

$sql_cat    = "select * from category order by parent_id, cat_id";
$result_cat = mysqli_query($db, $sql_cat);

if (!isset($user)) {
    $user = $_SESSION['login_user'];
}

$sql_user    = "select usr_id from user where valid = 'S' and usr_id <> '$user' order by usr_id";
$result_user = mysqli_query($db, $sql_user);

if (isset($_POST['category'])) {
    $cat = mysqli_real_escape_string($db, $_POST['category']);
}
if (isset($_POST['value'])) {
    $value = str_replace(',', '.', mysqli_real_escape_string($db, $_POST['value']));
    if ($value < 0) {
        $type = 'N';
    } else {
        $type = 'P';
    }
}
if (isset($_POST['date'])) {
    $date = mysqli_real_escape_string($db, $_POST['date']);
}
if (isset($_POST['note'])) {
    $note = mysqli_real_escape_string($db, $_POST['note']);
}
if (isset($_POST['user'])) {
    $usr_mov = mysqli_real_escape_string($db, $_POST['user']);
}

if (isset($cat) & isset($value) & isset($date) & isset($note) & isset($usr_mov)) {
    $sql_mov    = "insert into movement (cat_id, val, type, dat_mov, note, usr_mov, usr_id) values ($cat, $value, '$type', '$date', '$note', '$usr_mov', '$user')";
    $result_mov = mysqli_query($db, $sql_mov);
}

if (isset($result_mov)) {
    if (!$result_mov) {
        $error = $lang['23'];
    }
}

if(isset($_FILES['file']['tmp_name'])) {
if(is_uploaded_file($_FILES['file']['tmp_name']))
{
 $err=0;
 $file = $_FILES['file']['tmp_name'];
 $handle = fopen($file, "r");
 $c = 0;
 $i = 0;
 
 $sql_del = "delete from importcsv";
 mysqli_query($db, $sql_del);
 
 while(($filesop = fgetcsv($handle, 1000, ";")) !== false)
 {
 $dat_mov = mysqli_real_escape_string($db, $filesop[0]);
 $description = mysqli_real_escape_string($db, $filesop[1]);
 $value = str_replace('.', '', mysqli_real_escape_string($db, $filesop[2]));

 if ($i>0){ 
 $sql_file = "INSERT INTO importcsv (dat_mov, description, value, usr_mov) VALUES ('$dat_mov', '$description', '$value', '$user')";
 $result_sql = mysqli_query($db, $sql_file);
 if (!$result_sql){
 if ($err==0){
 $filename='upload.log';
 $log = "Upload log ".date('d/m/Y h:m:s')."\n";
 $err = 1;
 }
 $log .= "not loaded (".mysqli_error($db)."): ".$dat_mov." | ".$description." | ".$value."\n";
 }
 }
 $i=$i+1;
 if ($err==1){
 file_put_contents($filename, $log);
 }
 }
 $sql_file = "CALL csv_import()";
 mysqli_query($db, $sql_file);
 
 $sql_ver = "select * from importcsv c where c.mov_imp = ''";
 $result_ver = mysqli_query($db, $sql_ver);
 $count      = mysqli_num_rows($result_ver);
 echo $count;
 if ($count > 0){
 $filename_imp='import.log';
 $imp = "Import log ".date('d/m/Y h:m:s')."\n";
 while ($res_usr = mysqli_fetch_array($result_ver, MYSQLI_ASSOC)){
 $imp .= "not imported: ".$res_usr['dat_mov']." | ".$res_usr['description']." | ".$res_usr['value']."\n";
 }
 file_put_contents($filename_imp, $imp);
 }
 echo '<META HTTP-EQUIV="Refresh" Content="0; URL=add.php">';
}
}

?>

<div class="container col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3" style="margin-top:20px; margin-bottom:20px">
  <div class="row">
      <form name="form_insert" method="post" action="add.php" role="form" enctype="multipart/form-data">
        <fieldset>
          <div class="form-group">
		    <label for="category"><?php echo $lang['14'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
			<select name="category" type="category"  id="category" class="form-control">
			<option value=" "> </option>
			<?php
while ($row_cat = mysqli_fetch_array($result_cat, MYSQLI_ASSOC)) {
    if ($row_cat['cat_id'] == $row_cat['parent_id']) {
        echo '<option value="' . $row_cat['cat_id'] . '">' . $row_cat['cat_name'] . '</option>';
    } else {
        echo '<option value="' . $row_cat['cat_id'] . '"> - ' . $row_cat['cat_name'] . '</option>';
    }
}
?>
			</select></div>
          </div>
          <div class="form-group">
		    <label for="value"><?php echo $lang['15'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank"></span></span>
            <input name="value" type="number" min="-99999" max="99999" step="0.01" id="value" class="form-control" placeholder="-100,00 &euro;">
          </div></div>
          <div class="form-group">
		    <label for="date"><?php echo $lang['24'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input name="date" type="date" id="date" min="2000-01-01" class="form-control" value="<?php
echo date("Y-m-d");
?>">
          </div></div>
          <div class="form-group">
		    <label for="user"><?php echo $lang['17'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			<select name="user" type="user"  id="user" class="form-control">
			<option value="<?php
echo $user;
?>"><?php
echo $user;
?></option>
			<?php
while ($row_user = mysqli_fetch_array($result_user, MYSQLI_ASSOC)) {
    echo '<option value="' . $row_user['usr_id'] . '">' . $row_user['usr_id'] . '</option>';
}
?>
			</select>
          </div></div>
          <div class="form-group">
		    <label for="note"><?php echo $lang['25'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
            <textarea name="note" type="note" id="note" class="form-control" placeholder=" "></textarea>
          </div></div>
          <div class="form-group">
		    <label for="file"><?php echo $lang['75'];?> <a href="#" class="tooltip-large" title="<?php echo $lang['77'];?>"><span class="glyphicon glyphicon-info-sign"></span></a></label>
			<input type="file" name="file" class="filestyle" data-buttonBefore="true" data-buttonText="<?php echo $lang['76'];?>">
          </div>
            <div><input type="submit" name="submit" value="<?php echo $lang['44'];?>" class="btn btn-default"> 
			<input type="reset" name="submit" value="<?php echo $lang['45'];?>" class="btn btn-default"></div>
			<div <?php
echo $error;
?></div>
        </fieldset>
      </form>
</div>
</div>
</body>
</html>