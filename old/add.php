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

$sql_cat    = "select * from category where parent_id = cat_id order by cat_name";
$result_cat = mysqli_query($db, $sql_cat);

function getParentCat($db, $cat) {
	$sql_parent_cat    = "select * from category where parent_id = $cat and parent_id <> cat_id order by cat_name";
    $result_parent_cat = mysqli_query($db, $sql_parent_cat);
    return $result_parent_cat;
}

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

if (isset($_POST['id_mov'])) {
    $id_mov = mysqli_real_escape_string($db, $_POST['id_mov']);
}

$mov_id = 0;

if (isset($_GET['mod_mov_id'])){
 $mov_id = mysqli_real_escape_string($db, $_GET['mod_mov_id']);
 $sql_mod_mov = "select * from movement where mov_id = $mov_id";
 $result_mod_mov = mysqli_query($db, $sql_mod_mov);
 while ($res_mod_mov = mysqli_fetch_array($result_mod_mov, MYSQLI_ASSOC)){
 $cat_mov = $res_mod_mov ['cat_id'];
 
 $sql_cat_mov    = "select * from category where cat_id = $cat_mov";
 $result_cat_mov = mysqli_query($db, $sql_cat_mov);
 while ($res_cat_mov = mysqli_fetch_array($result_cat_mov, MYSQLI_ASSOC)){
 if ($res_cat_mov['cat_id'] == $res_cat_mov['parent_id']){
  $cat_name_mov = $res_cat_mov['cat_name'];
 }else{
  $cat_name_mov = '- '.$res_cat_mov['cat_name'];
 }}
 $val_mov = $res_mod_mov ['val'];
 $dat_mov = $res_mod_mov ['dat_mov'];
 $note_mov = $res_mod_mov ['note'];
 $usr_mov = $res_mod_mov ['usr_mov'];}
}

if (isset($cat) & isset($value) & isset($date) & isset($note) & isset($usr_mov) & isset($id_mov)) {
    $sql_mov_mod    = "update movement set cat_id = $cat, val = $value, type = '$type', dat_mov = '$date', note = '$note', usr_mov = '$usr_mov', usr_id = '$user' where mov_id = $id_mov";
    $result_mov_mod = mysqli_query($db, $sql_mov_mod);
}
elseif (isset($cat) & isset($value) & isset($date) & isset($note) & isset($usr_mov) & !isset($id_mov)) {
    $sql_mov    = "insert into movement (cat_id, val, type, dat_mov, note, usr_mov, usr_id) values ($cat, $value, '$type', '$date', '$note', '$usr_mov', '$user')";
    $result_mov = mysqli_query($db, $sql_mov);
}

if (isset($result_mov)) {
    if (!$result_mov) {
        $error = $lang['23'];
    }
}

if (isset($result_mov_mod)) {
    if (!$result_mov_mod) {
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

<div class="container col-xs-10 col-sm-8 col-md-6 col-xs-offset-1 col-sm-offset-2 col-md-offset-3" style="margin-top:20px; margin-bottom:20px">
  <div class="row">
      <form name="form_insert" method="post" action="add.php" role="form" enctype="multipart/form-data">
        <fieldset>
          <div class="form-group">
		    <label for="category"><?php echo $lang['14'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
			<select name="category" type="category"  id="category" class="form-control">
			<?php if ($mov_id != 0){echo '<option value="'.$cat_mov.'">'.$cat_name_mov.'</option>';}?>
			<option value=" "> </option>
			<?php
while ($row_cat = mysqli_fetch_array($result_cat, MYSQLI_ASSOC)) {
    echo '<option value="' . $row_cat['cat_id'] . '">' . $row_cat['cat_name'] . '</option>';
    if (getParentCat($db, $row_cat['cat_id'])) {
		$parent_cat = getParentCat($db, $row_cat['cat_id']);
		while ($row_parent_cat = mysqli_fetch_array($parent_cat, MYSQLI_ASSOC)) {
        echo '<option value="' . $row_parent_cat['cat_id'] . '"> - ' . $row_parent_cat['cat_name'] . '</option>';
		}
    }
}
?>
			</select></div>
          </div>
          <div class="form-group">
		    <label for="value"><?php echo $lang['15'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-piggy-bank"></span></span>
			<?php if ($mov_id != 0){echo '<input name="value" type="number" min="-99999" max="99999" step="0.01" id="value" value ="'.$val_mov.'" class="form-control">';}
			else {echo '<input name="value" type="number" min="-99999" max="99999" step="0.01" id="value" class="form-control" placeholder="-100,00 &euro;">';}?>
          </div></div>
          <div class="form-group">
		    <label for="date"><?php echo $lang['24'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <?php if ($mov_id != 0){echo '<input name="date" type="date" id="date" min="2000-01-01" class="form-control" value="'.$dat_mov.'">';}
			else {echo '<input name="date" type="date" id="date" min="2000-01-01" class="form-control" value="'.date("Y-m-d").'">';}?>
          </div></div>
          <div class="form-group">
		    <label for="user"><?php echo $lang['17'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			<select name="user" type="user"  id="user" class="form-control">
			<?php if ($mov_id != 0){echo '<option value="'.$usr_mov.'">'.$usr_mov.'</option>';}?>
			<?php if ($mov_id != 0 and $user != $usr_mov){
			echo '<option value="'.$user.'">'.$user.'</option>';
			}?>
			<?php if ($mov_id == 0){
			echo '<option value="'.$user.'">'.$user.'</option>';
			}?>
			<?php
while ($row_user = mysqli_fetch_array($result_user, MYSQLI_ASSOC)) {
	if ($row_user['usr_id'] != $usr_mov) {
    echo '<option value="' . $row_user['usr_id'] . '">' . $row_user['usr_id'] . '</option>';
	}
}
?>
			</select>
          </div></div>
          <div class="form-group">
		    <label for="note"><?php echo $lang['25'];?></label>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span></span>
            <?php if ($mov_id != 0){echo '<textarea name="note" type="note" id="note" class="form-control" placeholder=" ">'.$note_mov.'</textarea>';}
			else{echo'<textarea name="note" type="note" id="note" class="form-control" placeholder=" "></textarea>';}?>
          </div></div>
		  <?php if ($mov_id != 0){echo '<input type="hidden" id="id_mov" name="id_mov" value="'.$mov_id.'">';}?>
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