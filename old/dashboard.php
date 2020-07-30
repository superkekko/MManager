<?php
include('session.php');
include('header.html');
include('nav.php');
include('./locale/'.$_SESSION['lang'].'.php');

if (isset($_POST['user'])) {
    $user = $_POST['user'];}
else {$user = '%';}

$sql_user    = "select usr_id from user where valid = 'S' order by usr_id";
$result_user = mysqli_query($db, $sql_user);

if (isset($_POST['dat_ini'])) {
    $dat_ini = $_POST['dat_ini'];}
else {$dat_ini = date("Y").'-01-01';}

if (isset($_POST['dat_fin'])) {
    $dat_fin = $_POST['dat_fin'];}
else {$dat_fin = date("Y").'-12-31';}

if (isset($_POST['categ'])) {
    $categ = $_POST['categ'];}
else {$categ = '%';}

$sql_cat    = "select * from category where parent_id = cat_id order by cat_name";
$result_cat = mysqli_query($db, $sql_cat);

function getParentCat($db, $cat) {
	  $sql_parent_cat    = "select * from category where parent_id = $cat and parent_id <> cat_id order by cat_name";
    $result_parent_cat = mysqli_query($db, $sql_parent_cat);
    return $result_parent_cat;
}

function getCatName($db, $cat_id) {
	$sql_name_cat    = "select * from category where cat_id = $cat_id";
    $result_name_cat = mysqli_query($db, $sql_name_cat);
	while ($row_cat = mysqli_fetch_array($result_name_cat, MYSQLI_ASSOC)) {
	$cat_name_res = $row_cat['cat_name'];
	}
    return $cat_name_res;
}

if (isset($_POST['type'])) {
    $type = $_POST['type'];}
else {$type = '%';}

$income = $lang['11'];
$outcome = $lang['12'];
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['11'];?>', '<?php echo $lang['15'];?>'],
 <?php
$sql_tot    = "select case when t.type='P' then '$income' else '$outcome' end as type, abs(sum(t.val)) as val from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type like '$type' group by t.type order by t.type";
$result_tot = mysqli_query($db, $sql_tot);
while ($row = mysqli_fetch_array($result_tot)) {
    echo "['" . $row['type'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 chartArea: {
   height: "75%",
   left: "5%",
   top: "5%",
   width: "100%"
  },
 legend: 'none',
 pieHole: 0.4,
<?php
$sql_col_tot = "select case when t.type='P' then '#2ecc71' else '#e74c3c' end as color from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type like '$type' group by t.type order by t.type";
$result_col_tot = mysqli_query($db, $sql_col_tot);
$color = "";
while ($row = mysqli_fetch_array($result_col_tot)) {
$color .= '\''.$row['color'].'\',';
}
echo 'colors: ['.substr($color,0,strlen($color)-1).']';
?>
 };
 var chart = new google.visualization.PieChart(document.getElementById("tot_chart"));
 chart.draw(data, options);
 }
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['14'];?>', '<?php echo $lang['15'];?>'],
 <?php
$sql_tot_cat    = "select case when t.parent_id <> 0 then concat(t.parent_cat, ' (', t.cat_name, ')') else t.cat_name end as cat_name, abs(sum(t.val)) as val from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type = 'N' group by t.cat_id , case when t.parent_id <> 0 then concat(t.parent_cat, ' (', t.cat_name, ')') else t.cat_name end , t.color order by case when t.parent_id <> 0 then concat(t.parent_cat, ' (', t.cat_name, ')') else t.cat_name end";
$result_tot_cat = mysqli_query($db, $sql_tot_cat);
while ($row = mysqli_fetch_array($result_tot_cat)) {
    echo "['" . $row['cat_name'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 chartArea: {
   height: "75%",
   left: "5%",
   top: "5%",
   width: "100%"
  },
 legend: 'none',
 pieHole: 0.4,
<?php
$sql_col_cat = "select t.color as color from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type = 'N' group by t.cat_id , case when t.parent_id <> 0 then concat(t.parent_cat, ' (', t.cat_name, ')') else t.cat_name end , t.color order by case when t.parent_id <> 0 then concat(t.parent_cat, ' (', t.cat_name, ')') else t.cat_name end";
$result_col_cat = mysqli_query($db, $sql_col_cat);
$color = "";
while ($row = mysqli_fetch_array($result_col_cat)) {
$color .= '\''.$row['color'].'\',';
}
echo 'colors: ['.substr($color,0,strlen($color)-1).']';
?>
 };
 var chart = new google.visualization.PieChart(document.getElementById("cat_chart"));
 chart.draw(data, options);
 }
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['17'];?>', '<?php echo $lang['15'];?>'],
 <?php
$sql_tot_usr_spe    = "select t.usr_mov, abs(sum(t.val)) as val from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type = 'N' group by t.usr_mov, t.usr_color order by t.usr_mov";
$result_tot_usr_spe = mysqli_query($db, $sql_tot_usr_spe);
while ($row = mysqli_fetch_array($result_tot_usr_spe)) {
    echo "['" . $row['usr_mov'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 chartArea: {
   height: "75%",
   left: "5%",
   top: "5%",
   width: "100%"
  },
 legend: 'none',
 pieHole: 0.4,
<?php
$sql_col_cat = "select t.usr_color from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type = 'N' group by t.usr_mov, t.usr_color order by t.usr_mov";
$result_col_cat = mysqli_query($db, $sql_col_cat);
$color = "";
while ($row = mysqli_fetch_array($result_col_cat)) {
$color .= '\''.$row['usr_color'].'\',';
}
echo 'colors: ['.substr($color,0,strlen($color)-1).']';
?>
 };
 var chart = new google.visualization.PieChart(document.getElementById("tot_usr_spe_chart"));
 chart.draw(data, options);
 }
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['17'];?>', '<?php echo $lang['15'];?>'],
 <?php
$sql_tot_usr_ent    = "select t.usr_mov, abs(sum(t.val)) as val from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type = 'P' group by t.usr_mov, t.usr_color order by t.usr_mov";
$result_tot_usr_ent = mysqli_query($db, $sql_tot_usr_ent);
while ($row = mysqli_fetch_array($result_tot_usr_ent)) {
    echo "['" . $row['usr_mov'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 chartArea: {
   height: "75%",
   left: "5%",
   top: "5%",
   width: "100%"
  },
 legend: 'none',
 pieHole: 0.4,
<?php
$sql_col_cat = "select t.usr_color from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and type = 'P' group by t.usr_mov, t.usr_color order by t.usr_mov";
$result_col_cat = mysqli_query($db, $sql_col_cat);
$color = "";
while ($row = mysqli_fetch_array($result_col_cat)) {
$color .= '\''.$row['usr_color'].'\',';
}
echo 'colors: ['.substr($color,0,strlen($color)-1).']';
?>
 };
 var chart = new google.visualization.PieChart(document.getElementById("tot_usr_ent_chart"));
 chart.draw(data, options);
 }
</script>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['20'];?>', '<?php echo $lang['11'];?>', '<?php echo $lang['12'];?>'],
 <?php
$sql_prep   = "SET sql_mode = ''";
$result_prep = mysqli_query($db, $sql_prep);

$sql_det_month    = "select concat(month(t.dat_mov), '/', year(t.dat_mov)) as time, (select ifnull(abs(sum(tt.val)), 0) from total tt where tt.type = 'P' and month(tt.dat_mov) = month(t.dat_mov) and year(tt.dat_mov) = year(t.dat_mov) and tt.usr_mov like '$user' and tt.cat_id like '$categ' and tt.type like '$type') as val_pos, (select ifnull(abs(sum(tt.val)), 0) from total tt where tt.type = 'N' and month(tt.dat_mov) = month(t.dat_mov) and year(tt.dat_mov) = year(t.dat_mov) and tt.usr_mov like '$user' and tt.cat_id like '$categ' and tt.type like '$type') as val_neg from total t where t.dat_mov between '$dat_ini' and '$dat_fin' and t.usr_mov like '$user' and t.cat_id like '$categ' and t.type like '$type' group by concat(month(t.dat_mov), '/', year(t.dat_mov)) order by year(t.dat_mov) , month(t.dat_mov)";
$result_det_month = mysqli_query($db, $sql_det_month);
while ($row = mysqli_fetch_array($result_det_month)) {
    echo "['" . $row['time'] . "'," . $row['val_pos'] . "," . $row['val_neg'] . "],";
}
?>
    ]);
 var options = {
 colors: ['#2ecc71', '#e74c3c'],
 legend: 'none'
 };
 var chart = new google.visualization.ColumnChart(document.getElementById("det_month_chart"));
 chart.draw(data, options);
 }
</script>

<div class="row">
<div class="col-md-12 text-center">
		  <form class="form-inline" name="form_insert" method="post" action="dashboard.php" role="form">
    <div class="form-group">
		<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input name="dat_ini" type="date" id="dat_ini" min="2000-01-01" class="form-control" value="<?php echo $dat_ini?>">
          </div>
		<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            <input name="dat_fin" type="date" id="dat_fin" min="2000-01-01" class="form-control" value="<?php echo $dat_fin?>">
          </div>
			<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
			<select name="user" type="user"  id="user" class="form-control">
			<?php if ($user <> '%') {echo '<option value="'.$user.'">'.$user.'</option>';};?>
			<option value="%"> </option>
			<?php
while ($row_user = mysqli_fetch_array($result_user, MYSQLI_ASSOC)) {
	if ($row_user['usr_id'] != $user) {
    echo '<option value="' . $row_user['usr_id'] . '">' . $row_user['usr_id'] . '</option>';
	}
}
?>
			</select>
          </div>
<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-tag"></span></span>
			<select name="categ" type="category"  id="categ" class="form-control">
			<?php if ($categ <> '%') {echo '<option value="'.$categ.'">'.getCatName($db, $categ).'</option>';};?>
			<option value="%"> </option>
			<?php
while ($row_cat = mysqli_fetch_array($result_cat, MYSQLI_ASSOC)) {
	if ($row_cat['cat_id'] <> $categ) {
    echo '<option value="' . $row_cat['cat_id'] . '">' . $row_cat['cat_name'] . '</option>';
    if (getParentCat($db, $row_cat['cat_id'])) {
		$parent_cat = getParentCat($db, $row_cat['cat_id']);
		while ($row_parent_cat = mysqli_fetch_array($parent_cat, MYSQLI_ASSOC)) {
        echo '<option value="' . $row_parent_cat['cat_id'] . '"> - ' . $row_parent_cat['cat_name'] . '</option>';
		}
    }}
}
?>
			</select></div>
<div class="input-group">
            <span class="input-group-addon"><span class="glyphicon glyphicon-sort"></span></span>
			<select name="type" type="type"  id="type" class="form-control">
			<?php if ($type <> '%' & $type == 'P') {echo '<option value="'.$type.'">'.$income.'</option>';}
			elseif ($type <> '%' & $type == 'N') {echo '<option value="'.$type.'">'.$outcome.'</option>';};?>
			<option value="%"> </option>
			<?php if ($type <> 'P') {echo '<option value="P">'.$income.'</option>';};?>
			<?php if ($type <> 'N') {echo '<option value="N">'.$outcome.'</option>';};?>
			</select></div>
	<input type="submit" name="submit" value="<?php echo $lang['44'];?>" class="btn btn-default">
    </div>
</form>
</div>
</div>

<br>

  <div class="container-fluid">

    <div class="row">
      <div class="col-sm-8">
        <div class="chart-wrapper">
          <div class="chart-title">
            <b><?php echo $lang['21'];?></b>
          </div>
          <div class="chart-stage">
            <div id="grid-1-1">
              <div id="det_month_chart" class="chart"></div>
            </div>
          </div>
	   </div>
      </div>
      <div class="col-sm-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            <b><?php echo $lang['13'];?></b>
          </div>
          <div class="chart-stage">
            <div id="tot_chart" class="chart"></div>
          </div>
        </div>
      </div>
   </div>

    <div class="row">
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            <b><?php echo $lang['18'];?></b>
          </div>
          <div class="chart-stage">
            <div id="tot_usr_spe_chart" class="chart"></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            <b><?php echo $lang['19'];?></b>
          </div>
          <div class="chart-stage">
            <div id="tot_usr_ent_chart" class="chart"></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6 col-md-4">
        <div class="chart-wrapper">
          <div class="chart-title">
            <b><?php echo $lang['16'];?></b>
          </div>
          <div class="chart-stage">
            <div id="cat_chart" class="chart"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
