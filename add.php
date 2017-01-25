<?php
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
?>

<div class="container">
  <div class="row" style="margin-top:20px; margin-bottom:20px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
      <form name="form_insert" method="post" action="add.php" role="form">
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
            <div><input type="submit" name="submit" value="<?php echo $lang['44'];?>" class="btn btn-default"> 
			<input type="reset" name="submit" value="<?php echo $lang['45'];?>" class="btn btn-default"></div>
			<div <?php
echo $error;
?></div>
        </fieldset>
      </form>
    </div>
</div>
</div>
</body>
</html>