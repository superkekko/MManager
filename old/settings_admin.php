<?php

$web_ver = '1.9.2';

$error     = "";
$error_cat = "";
$parent_id = 0;

$sql_cat    = "select * from category order by parent_id, cat_id";
$result_cat = mysqli_query($db, $sql_cat);

if (!isset($user)) {
    $user = $_SESSION['login_user'];
}

use \Colors\RandomColor;
$usr_color=RandomColor::one(array('format'=>'hex'));
$usr_color=substr($usr_color,1,6);

$color=RandomColor::one(array('format'=>'hex'));
$color=substr($color,1,6);

if (isset($_POST['usr_color']) & isset($_POST['usr']) & isset($_POST['email']) & isset($_POST['pwd']) & isset($_POST['enable']) & isset($_POST['admin']) & !isset($_GET['mod_usr_id'])) {
    $usr_color_commit    = mysqli_real_escape_string($db, $_POST['usr_color']);
	$usr    = mysqli_real_escape_string($db, $_POST['usr']);
    $email  = mysqli_real_escape_string($db, $_POST['email']);
    $pwd    = mysqli_real_escape_string($db, $_POST['pwd']);
    $enable = mysqli_real_escape_string($db, $_POST['enable']);
    $admin  = mysqli_real_escape_string($db, $_POST['admin']);
    
    $sql_ver_usr = "select * from user where usr_id = '$usr'";
    $result_ver  = mysqli_query($db, $sql_ver_usr);
    $count       = mysqli_num_rows($result_ver);
    
    if ($count == 0) {
        $sql_usr = "insert into user (usr_id, email, passwd, valid, admin, color) values ('$usr', '$email', sha1('$pwd'), '$enable', '$admin', '$usr_color_commit')";
        mysqli_query($db, $sql_usr);
        $error = $lang['47'];
    } else {
        $error = $lang['48'];
    }
}

if (isset($_POST['usr_color']) & isset($_POST['usr']) & isset($_POST['email']) & isset($_POST['pwd']) & isset($_POST['enable']) & isset($_POST['admin']) & isset($_GET['mod_usr_id'])) {
    $usr_color_commit    = mysqli_real_escape_string($db, $_POST['usr_color']);
	$usr    = mysqli_real_escape_string($db, $_POST['usr']);
    $email  = mysqli_real_escape_string($db, $_POST['email']);
    $pwd    = mysqli_real_escape_string($db, $_POST['pwd']);
    $enable = mysqli_real_escape_string($db, $_POST['enable']);
    $admin  = mysqli_real_escape_string($db, $_POST['admin']);
    
    $sql_usr = "update user set email='$email', valid='$enable', admin='$admin', color='$usr_color_commit' where usr_id='$usr'";
    mysqli_query($db, $sql_usr);
    
    if ($pwd <> '') {
        $sql_usr = "update user set passwd=sha1('$pwd') where usr_id='$usr'";
        mysqli_query($db, $sql_usr);
    }
    
    $page_name = basename($_SERVER['PHP_SELF']);
    //$error     = $lang['49'];
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $page_name . '">';
}

if (isset($_GET['del_usr_id'])) {
    $usr_delete  = mysqli_real_escape_string($db, $_GET['del_usr_id']);
    $sql_del_usr = "delete from user where usr_id='$usr_delete'";
    mysqli_query($db, $sql_del_usr);
}

if (isset($_GET['mod_usr_id'])) {
    $usr_mod        = mysqli_real_escape_string($db, $_GET['mod_usr_id']);
    $sql_mod_usr    = "select * from user where usr_id='$usr_mod'";
    $result_usr_mod = mysqli_query($db, $sql_mod_usr);
    $row_usr_mod    = mysqli_fetch_array($result_usr_mod, MYSQLI_ASSOC);
}

$sql_list_usr    = "select * from user";
$result_list_usr = mysqli_query($db, $sql_list_usr);

if (isset($_POST['color']) & isset($_POST['cat_name']) & isset($_POST['income']) & isset($_POST['parent_id']) & isset($_POST['cat_key']) & !isset($_GET['mod_cat_id'])) {
    $cat_color     = mysqli_real_escape_string($db, $_POST['color']);
    $cat_name  = mysqli_real_escape_string($db, $_POST['cat_name']);
    $parent_id = mysqli_real_escape_string($db, $_POST['parent_id']);
    $cat_key = mysqli_real_escape_string($db, $_POST['cat_key']);
	
    if ($parent_id == " ") {
        $sql_pid    = "select max(cat_id) as cat_id_max from category";
        $result_pid = mysqli_query($db, $sql_pid);
        $row_pid    = mysqli_fetch_array($result_pid, MYSQLI_ASSOC);
        $parent_id  = $row_pid['cat_id_max'] + 1;
    }
    $income = mysqli_real_escape_string($db, $_POST['income']);
    
    $sql_ver    = "select * from category where cat_name = '$cat_name'";
    $result_ver = mysqli_query($db, $sql_ver);
    $count      = mysqli_num_rows($result_ver);
    
    if ($count == 0) {
        $sql_usr = "insert into category (color, cat_name, parent_id, income, keyword) values ('$cat_color', '$cat_name', $parent_id, '$income', '$cat_key')";
        mysqli_query($db, $sql_usr);
        $error_cat = $lang['50'];
    } else {
        $error_cat = $lang['51'];
    }
}

if (isset($_POST['color']) & isset($_POST['cat_name']) & isset($_POST['income']) & isset($_POST['parent_id']) & isset($_POST['cat_key']) & isset($_GET['mod_cat_id'])) {
    $cat       = mysqli_real_escape_string($db, $_GET['mod_cat_id']);
    $cat_color     = mysqli_real_escape_string($db, $_POST['color']);
    $cat_name  = mysqli_real_escape_string($db, $_POST['cat_name']);
    $parent_id = mysqli_real_escape_string($db, $_POST['parent_id']);
    $income    = mysqli_real_escape_string($db, $_POST['income']);
    $cat_key = mysqli_real_escape_string($db, $_POST['cat_key']);
	
    $sql_usr = "update category set color='$cat_color', cat_name='$cat_name', parent_id=$parent_id, income='$income', keyword = '$cat_key' where cat_id=$cat";
    mysqli_query($db, $sql_usr);
    
    $page_name = basename($_SERVER['PHP_SELF']);
    //$error     = $lang['52'];
    echo '<META HTTP-EQUIV="Refresh" Content="0; URL=' . $page_name . '">';
}

if (isset($_GET['del_cat_id'])) {
    $cat_delete  = mysqli_real_escape_string($db, $_GET['del_cat_id']);
    $sql_del_cat = "delete from category where cat_id=$cat_delete";
    mysqli_query($db, $sql_del_cat);
}

$sql_list_cat    = "select * from cat order by parent_id, cat_id";
$result_list_cat = mysqli_query($db, $sql_list_cat);

if (isset($_GET['mod_cat_id'])) {
    $cat_mod        = mysqli_real_escape_string($db, $_GET['mod_cat_id']);
    $sql_mod_cat    = "select * from cat where cat_id=$cat_mod";
    $result_cat_mod = mysqli_query($db, $sql_mod_cat);
    $row_cat_mod    = mysqli_fetch_array($result_cat_mod, MYSQLI_ASSOC);
}

$sql_version    = "select max(db) as db from mversion";
$result_version = mysqli_query($db, $sql_version);
$row_ver    = mysqli_fetch_array($result_version, MYSQLI_ASSOC);

$directory = "./update/";
$dir = opendir($directory);

scandir($directory );
$update = glob('./update/ver*.sql');
$message = '';

if (isset($_POST['update'])) {
while (($file = readdir($dir)) !== false) {
  $filename = $directory . $file;
  $type = filetype($filename);
  $ver = strpos($filename, 'ver');
  if ($type == 'file' & $ver !== false) {
  $num_ver = substr($filename, $ver+4, strpos($filename, '.sql')-($ver+4));
  if ($num_ver > $row_ver['db']){
  $contents = file_get_contents($filename);
  $querys = explode("\n", $contents);
    foreach ($querys as $q) {
      $q = trim($q);
      if (strlen($q)) {
        $message .= substr($q,0,40).' -> ';
		$result = mysqli_query($db, $q);
		if ($result){
	     $message .= 'OK';
		}else{
		 $message .= mysqli_error($db);
		};
		 $message .= '<br><br>';
		}
	}
  }
	unlink($filename);
	} 
}
}
?>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $lang['53'];?></div>
    <div class="panel-body"><form name="form_insert" method="post" action="settings.php<?php
if (isset($_GET['mod_usr_id'])) {
    echo '?mod_usr_id=' . $row_usr_mod['usr_id'];
}
;
?>" role="form">
        <div class="row">
		    <div class="col-xs-2">
            <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></span>
					<input name="usr_color" id="usr_color" class="form-control jscolor" <?php
if (isset($_GET['mod_usr_id'])) {
    echo 'value="' . $row_usr_mod['color'] . '"';
} else {
    echo 'value="'.$usr_color.'"';
}
;
?>></div></div>
            <div class="col-xs-2">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" name="usr" id="usr" class="form-control" <?php
if (isset($_GET['mod_usr_id'])) {
    echo 'readonly="readonly" value="' . $row_usr_mod['usr_id'] . '"';
} else {
    echo 'placeholder="'.$lang['01'].'"';
}
;
?>></div></div>
					<div class="col-xs-2">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
					<input type="email" name="email" id="email" class="form-control" <?php
if (isset($_GET['mod_usr_id'])) {
    echo 'value="' . $row_usr_mod['email'] . '"';
} else {
    echo 'placeholder="'.$lang['54'].'"';
}
;
?>></div></div>
					<div class="col-xs-2">
                <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-sunglasses"></span></span>
					<input type="password" name="pwd" id="pwd" class="form-control" placeholder="<?php echo $lang['02'];?>"></div></div>
					<div class="col-xs-2">
				<div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-eye-close"></span></span>
					<select type="text" name="enable" id="enable" class="form-control">
			       <?php
if (isset($_GET['mod_usr_id'])) {
    echo '<option value="' . $row_usr_mod['valid'] . '">';
    if ($row_usr_mod['valid'] == 'S') {
        echo $lang['55'].'</option>
							<option value=" ">'.$lang['56'].'</option>';
    } else {
        echo $lang['56'].'</option>
							<option value="S">'.$lang['55'].'</option>';
    }
    ;
} else {
    echo '<option value="S">'.$lang['55'].'</option>
					<option value=" ">'.$lang['56'].'</option>';
}
;
?>
					</select></div></div>
					<div class="col-xs-2">
				<div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-briefcase"></span></span>
					<select type="text" name="admin" id="admin" class="form-control">
			       <?php
if (isset($_GET['mod_usr_id'])) {
    echo '<option value="' . $row_usr_mod['admin'] . '">';
    if ($row_usr_mod['admin'] == 'S') {
        echo $lang['57'].'</option>
							<option value="N">'.$lang['17'].'</option>';
    } else {
        echo $lang['17'].'</option>
							<option value="S">'.$lang['57'].'</option>';
    }
    ;
} else {
    echo '<option value="S">'.$lang['57'].'</option>
					<option value="N">'.$lang['17'].'</option>';
}
;
?>
					</select></div></div>
					<div class="col-xs-1">	
					<input type="submit" name="submit" value="<?php
if (isset($_GET['mod_usr_id'])) {
    echo $lang['58'].'"';
} else {
    echo $lang['59'].'"';
}
;
?>" class="btn btn-default"></div>
        </div>
		<div> <?php
echo $error;
?></div>
    </form>
<br>
<table class="table table-striped">
    <thead>
        <tr>
            <th><?php echo $lang['01'];?></a></th>
            <th><?php echo $lang['54'];?></th>
            <th><?php echo $lang['60'];?></th>
			<th><?php echo $lang['61'];?></th>
			<th><?php echo $lang['62'];?></th>
			<th><?php echo $lang['63'];?></th>
        </tr>
    </thead>
    <tbody>
	 <?php
while ($row_usr = mysqli_fetch_array($result_list_usr, MYSQLI_ASSOC)) {
    echo '<tr>
	   <td>' . $row_usr['usr_id'] . '</td>
	   <td>' . $row_usr['email'] . '</td>
	   <td>' . date('d/m/Y', strtotime($row_usr['tms_upd'])) . '</td>
	   <td>';
    if ($row_usr['valid'] == 'S') {
        echo $lang['64'];
    } else {
        echo $lang['65'];
    }
    ;
    echo '</td>
	   <td>';
    if ($row_usr['admin'] == 'S') {
        echo $lang['64'];
    } else {
        echo $lang['65'];
    }
    ;
    echo '</td>';
    if ($row_usr['usr_id'] == $user) {
        echo '<td>'.$lang['41'].' | <a href="?mod_usr_id=' . $row_usr['usr_id'] . '">'.$lang['58'].'</a></td></tr>';
    } else {
        echo '<td><a href="?del_usr_id=' . $row_usr['usr_id'] . '">'.$lang['41'].'</a> | <a href="?mod_usr_id=' . $row_usr['usr_id'] . '">'.$lang['58'].'</a></td></tr>';
    }
    ;
}
?>
	</tbody>
</table>
</div>
</div>
</div>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $lang['66'];?></div>
    <div class="panel-body"><form name="form_insert" method="post" action="settings.php<?php
if (isset($_GET['mod_cat_id'])) {
    echo '?mod_cat_id=' . $row_cat_mod['cat_id'];
}
;
?>" role="form">
        <div class="row">
		    <div class="col-xs-2">
            <div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-eye-open"></span></span>
					<input name="color" id="color" class="form-control jscolor" <?php
if (isset($_GET['mod_cat_id'])) {
    echo 'value="' . $row_cat_mod['color'] . '"';
} else {
    echo 'value="'.$color.'"';
}
;
?>></div></div>
                    <div class="col-xs-2">
				<div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
                    <input type="text" name="cat_name" id="cat_name" class="form-control" <?php
if (isset($_GET['mod_cat_id'])) {
    echo 'value="' . $row_cat_mod['cat_name'] . '"';
} else {
    echo 'placeholder="'.$lang['14'].'"';
}
;
?>></div></div>
					<div class="col-xs-2">
				<div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-flag"></span></span>
			<select type="text" name="parent_id" id="parent_id" class="form-control" <?php
if ($row_cat_mod['have_ch'] == 'S') {
    echo 'readonly="readonly"';
}
?>>
			<?php
if (isset($_GET['mod_cat_id'])) {
    echo '<option value="' . $row_cat_mod['parent_id'] . '">' . $row_cat_mod['parent_cat'] . '</option>';
} else {
    echo '<option value=" ">'.$lang['68'].'</option>';
}
;
?>
			<?php
while ($row_cat = mysqli_fetch_array($result_cat, MYSQLI_ASSOC)) {
    if ($row_cat['cat_id'] == $row_cat['parent_id'] & $row_cat_mod['parent_id'] <> $row_cat['cat_id']) {
        echo '<option value="' . $row_cat['cat_id'] . '">' . $row_cat['cat_name'] . '</option>';
    }
}
?>
			</select></div></div>
                    <div class="col-xs-3">
				<div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
                    <input type="text" name="cat_key" id="cat_key" class="form-control" <?php
if (isset($_GET['mod_cat_id'])) {
    echo 'value="' . $row_cat_mod['keyword'] . '"';
} else {
    echo 'placeholder="'.$lang['78'].'"';
}
;
?>></div></div>
					<div class="col-xs-2">
				<div class="input-group">
                    <span class="input-group-addon"><span class="glyphicon glyphicon-transfer"></span></span>
					<select type="text" name="income" id="income" class="form-control" <?php
if ($row_cat_mod['num_mov'] <> 0) {
    echo 'readonly="readonly"';
}
?>>
			       <?php
if (isset($_GET['mod_cat_id'])) {
    echo '<option value="' . $row_cat_mod['income'] . '">';
    if ($row_cat_mod['income'] == 'S') {
        echo $lang['11'].'</option>
							<option value=" ">'.$lang['12'].'</option>';
    } else {
        echo $lang['12'].'</option>
							<option value="S">'.$lang['11'].'</option>';
    }
    ;
} else {
    echo '<option value="S">'.$lang['11'].'</option>
					<option value=" ">'.$lang['12'].'</option>';
}
;
?>
					</select></div></div>
					<div class="col-xs-1">
					<input type="submit" name="submit" value="<?php
if (isset($_GET['mod_cat_id'])) {
    echo $lang['58'].'"';
} else {
    echo $lang['59'].'"';
}
;
?>" class="btn btn-default"></div>
        </div>
		<div> <?php
echo $error_cat;
?></div>
    </form>

<br>

<table class="table table-striped">
    <thead>
        <tr>
			<th><?php echo $lang['14'];?></th>
            <th><?php echo $lang['68'];?></th>
            <th><?php echo $lang['11'];?></th>
			<th><?php echo $lang['69'];?></th>
			<th><?php echo $lang['78'];?></th>
			<th><?php echo $lang['63'];?></th>
        </tr>
    </thead>
    <tbody>
	 <?php
while ($row_cat = mysqli_fetch_array($result_list_cat, MYSQLI_ASSOC)) {
    echo '<tr>
	   <td style="color:#' . $row_cat['color'] . ';">' . $row_cat['cat_name'] . '</td>
	   <td>' . $row_cat['parent_cat'] . '</td>
	   <td>';
    if ($row_cat['income'] == 'S') {
        echo $lang['64'];
    } else {
        echo $lang['65'];
    }
    ;
    echo '</td>
	   <td>' . $row_cat['num_mov'] . '</td><td>' . $row_cat['keyword'] . '</td>';
    if ($row_cat['num_mov'] > 0 || $row_cat['have_ch'] == 'S') {
        echo '<td>'.$lang['41'].' | <a href="?mod_cat_id=' . $row_cat['cat_id'] . '">'.$lang['58'].'</a></td></tr>';
    } else {
        echo '<td><a href="?del_cat_id=' . $row_cat['cat_id'] . '">'.$lang['41'].'</a> | <a href="?mod_cat_id=' . $row_cat['cat_id'] . '">'.$lang['58'].'</a></tr>';
    }
    ;
}
?>
	</tbody>
</table>
</div>
</div>
</div>

<div class="col-md-12">
<div class="panel panel-default">
    <div class="panel-heading"><?php echo $lang['71'].' (db_ver: '.$row_ver['db'].' web_ver: '.$web_ver.')';?></div>
    <div class="panel-body"><form name="update" method="post" action="settings.php" role="form">
        <fieldset>
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
			  <input type="hidden" name="update" id="update" value="S">
              <input type="submit" name="submit" value="<?php echo $lang['71']?>" class="btn btn-default <?php if($update){}else{echo 'disabled';};?>">
            </div>
          </div>
        </fieldset>
      </form>
			<?php if($message <> ''){ echo '<br>
            <div class="panel panel-default">
			<div class="panel-body">'.$message.$lang['74'].'</div></div>';};?>
</div>
</div>
</div>