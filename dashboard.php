<?php
include('session.php');
include('header.html');
include('nav.php');
include('./locale/'.$_SESSION['lang'].'.php');

if (isset($_GET['year'])) {
    $year = mysqli_real_escape_string($db, urldecode ($_GET['year']));
} else {
    $year = date('Y');
}

$sql_cat_view    = "select * from category where income <> 'S' order by parent_id, cat_id";
$result_cat_view = mysqli_query($db, $sql_cat_view);

$sql_cat_view_2    = "select * from category where income <> 'S' order by parent_id, cat_id";
$result_cat_view_2 = mysqli_query($db, $sql_cat_view_2);

$cat_set = 0;
$cat_set_2 = 0;

if (isset($_GET['cat'])) {
	if ($_GET['cat'] == 0){
	$sql_cat_tot    = "select group_concat(cat_id separator ',') as cat from category order by parent_id, cat_id";
	$result_cat_tot = mysqli_query($db, $sql_cat_tot);
	$row = mysqli_fetch_array($result_cat_tot);
    $cat_view = $row['cat'];
	}else{
    $cat_view = mysqli_real_escape_string($db, $_GET['cat']);
	$cat_set = mysqli_real_escape_string($db, $_GET['cat']);
	$cat_set_2 = 1;}
} else {
	$sql_cat_tot    = "select group_concat(cat_id separator ',') as cat from category order by parent_id, cat_id";
	$result_cat_tot = mysqli_query($db, $sql_cat_tot);
	$row = mysqli_fetch_array($result_cat_tot);
    $cat_view = $row['cat'];
}

$sql_usr_view    = "select usr_id from user order by usr_id";
$result_usr_view = mysqli_query($db, $sql_usr_view);

$sql_usr_view_2    = "select usr_id from user order by usr_id";
$result_usr_view_2 = mysqli_query($db, $sql_usr_view_2);

$usr_set = ' ';
$usr_set_2 = 0;

if (isset($_GET['usr'])) {
	if ($_GET['usr'] == '0'){
	$sql_usr_tot    = "select group_concat(usr_id separator '\',\'') as usr from user order by usr_id";
	$result_usr_tot = mysqli_query($db, $sql_usr_tot);
	$row = mysqli_fetch_array($result_usr_tot);
    $usr_view = $row['usr'];}
    else {$usr_view = mysqli_real_escape_string($db, urldecode ($_GET['usr']));
	$usr_set = mysqli_real_escape_string($db, urldecode ($_GET['usr']));
	$usr_set_2 = 1;}
} else {
	$sql_usr_tot    = "select group_concat(usr_id separator '\',\'') as usr from user order by usr_id";
	$result_usr_tot = mysqli_query($db, $sql_usr_tot);
	$row = mysqli_fetch_array($result_usr_tot);
    $usr_view = $row['usr'];
}

$income = $lang['11'];
$outcome = $lang['12'];

$inout_set = 0;

if (isset($_GET['inout'])) {
	if ($_GET['inout'] == 0){
	$inout = "P','N";
	}else {
    $inout = mysqli_real_escape_string($db, urldecode ($_GET['inout']));
	$inout_set = 1;}
} else {
    $inout = "P','N";
}
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['11'];?>', '<?php echo $lang['15'];?>'],
 <?php
$sql_tot    = "select case when income = 'P' then '$income' else '$outcome' end as income, t.val from (select sum(val) as val, income, year from tot where income in ('$inout') and usr_mov in ('$usr_view') and cat_id in ($cat_view) group by income, year) t where t.year = $year order by t.income";
$result_tot = mysqli_query($db, $sql_tot);
while ($row = mysqli_fetch_array($result_tot)) {
    echo "['" . $row['income'] . "'," . $row['val'] . "],";
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
$sql_col_tot = "select case when income = 'P' then '#2ecc71' else '#e74c3c' end as color from (select sum(val) as val, income, year from tot where income in ('$inout') and usr_mov in ('$usr_view') and cat_id in ($cat_view) group by income, year) t where t.year = $year order by t.income";
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
$sql_tot_cat    = "select cat_name, cat_id, sum(abs(val)) as val from tot_cat where income <> 'S' and usr_mov in ('$usr_view') and cat_id in ($cat_view) and year = $year group by cat_name, cat_id order by cat_id";
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
$sql_col_cat = "select concat('#',t.color) as color from (select distinct color, cat_id from tot_cat where income <> 'S' and usr_mov in ('$usr_view') and cat_id in ($cat_view) and year = $year order by cat_id) t";
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
$sql_tot_usr_spe    = "select usr_mov, sum(abs(val)) as val from tot_usr where income <> 'S' and usr_mov in ('$usr_view') and cat_id in ($cat_view) and year = $year group by usr_mov order by usr_mov";
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
$sql_col_cat = "select concat('#',t.usr_color) as usr_color from (select distinct usr_color, usr_mov from tot_usr where income <> 'S' and usr_mov in ('$usr_view') and cat_id in ($cat_view) and year = 2017 order by usr_mov) t";
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
$sql_tot_usr_ent    = "select usr_mov, sum(abs(val)) as val from tot_usr where income = 'S' and usr_mov in ('$usr_view') and cat_id in ($cat_view) and year = $year group by usr_mov order by usr_mov";
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
$sql_col_cat = "select concat('#',t.usr_color) as usr_color from (select distinct usr_color, usr_mov from tot_usr where income = 'S' and usr_mov in ('$usr_view') and cat_id in ($cat_view) and year = $year order by usr_mov) t";
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
$sql_det_month    = "select sum(income) as income, sum(outcome) as outcome, month from tot_eu where year in (0,$year) and usr_mov in ('$usr_view', ' ') and cat_id in ($cat_view, 0) group by month order by CAST(month AS UNSIGNED)";
$result_det_month = mysqli_query($db, $sql_det_month);
while ($row = mysqli_fetch_array($result_det_month)) {
    echo "['" . $row['month'] . "'," . $row['income'] . "," . $row['outcome'] . "],";
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

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['<?php echo $lang['20'];?>', <?php
$sql_cat    = "select group_concat(distinct concat('\'',cat_name,'\',{role:\'style\'}') order by cat_id separator ',') as cat from tot_cat_date where year in ($year, 0) and income <> 'S' and cat_id in ($cat_view) and usr_mov in ('$usr_view', ' ')";
$result_cat = mysqli_query($db, $sql_cat);
$row        = mysqli_fetch_array($result_cat);
echo $row['cat'];
?>],
 <?php
$sql_det_cat    = "select month, group_concat(concat(val,',\'#',color,'\'') order by cat_id separator ',') as val from (select sum(val) as val, cat_name, cat_id, color, income, month from tot_cat_date where year in ($year,0) and income <> 'S' and cat_id in ($cat_view) and usr_mov in ('$usr_view', ' ') group by cat_name, cat_id, color, income, month) t group by month";
$result_det_cat = mysqli_query($db, $sql_det_cat);
while ($row = mysqli_fetch_array($result_det_cat)) {
    echo "['" . $row['month'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 legend: 'none',
 isStacked: true
 };
 var chart = new google.visualization.ColumnChart(document.getElementById("det_cat_chart"));
 chart.draw(data, options);
 }
</script>

<script>
function refreshPage_inout(passValue){
//do something in this function with the value
 window.location="dashboard.php?year=<?php echo $year;?>&cat=<?php if ($cat_set_2==0){echo 0;}else{echo $cat_view;};?>&usr=<?php if ($usr_set_2==0){echo 0;}else{echo $usr_view;};?>&inout="+passValue
}
function refreshPage_year(passValue){
//do something in this function with the value
 window.location="dashboard.php?year="+passValue+"&cat=<?php echo $cat_view;?>"
}
function refreshPage_cat(passValue){
//do something in this function with the value
 window.location="dashboard.php?year=<?php echo $year;?>&cat="+passValue+"&usr=<?php if ($usr_set_2==0){echo 0;}else{echo $usr_view;};?>&inout=<?php if ($inout_set==0){echo 0;}else{echo $inout_view;};?>"
}
function refreshPage_usr(passValue){
//do something in this function with the value
 window.location="dashboard.php?year=<?php echo $year;?>&cat=<?php if ($cat_set_2==0){echo 0;}else{echo $cat_view;};?>&usr="+passValue+"&inout=<?php if ($inout_set==0){echo 0;}else{echo $inout_view;};?>"
}
</script>

<div class="row">
<div class="col-md-12 text-center">
		  <form class="form-inline">
    <div class="form-group">
        <label for="inputType" class="control-label"><b><?php echo $lang['10'];?></b></label>
        <form name="form_insert" method="post" action="dashboard.php" role="form">
			<select name="year" type="year"  id="year" class="form-control" onchange="refreshPage_year(this.value);">
			<option value="<?php echo $year;?>"><?php echo $year;?></option>
			<?php if ($year == date('Y')){ 
			echo '<option value="'.(date('Y')-1).'">'.(date('Y')-1).'</option>';
			echo '<option value="'.(date('Y')-2).'">'.(date('Y')-2).'</option>';}
			elseif ($year == date('Y')-1){ 
			echo '<option value="'.(date('Y')).'">'.(date('Y')).'</option>';
			echo '<option value="'.(date('Y')-2).'">'.(date('Y')-2).'</option>';}
			else { 
			echo '<option value="'.(date('Y')).'">'.(date('Y')).'</option>';
			echo '<option value="'.(date('Y')-1).'">'.(date('Y')-1).'</option>';}
			?>
			</select>
			<select name="category" type="category"  id="category" class="form-control" onchange="refreshPage_cat(this.value);">
			<?php if ($cat_set != 0){
			while ($row_cat_view = mysqli_fetch_array($result_cat_view, MYSQLI_ASSOC)) {
    if ($row_cat_view['cat_id'] == $row_cat_view['parent_id'] & $row_cat_view['cat_id'] == $cat_set) {
        echo '<option value="' . $row_cat_view['cat_id'] . '">' . $row_cat_view['cat_name'] . '</option>';
    } elseif ($row_cat_view['cat_id'] == $cat_set) {
        echo '<option value="' . $row_cat_view['cat_id'] . '"> - ' . $row_cat_view['cat_name'] . '</option>';
    }
}}?>
			<option value="0"><?php echo $lang['14'];?></option>
			<?php
while ($row_cat_view_2 = mysqli_fetch_array($result_cat_view_2, MYSQLI_ASSOC)) {
    if ($row_cat_view_2['cat_id'] == $row_cat_view_2['parent_id'] & $row_cat_view_2['cat_id'] != $cat_set) {
        echo '<option value="' . $row_cat_view_2['cat_id'] . '">' . $row_cat_view_2['cat_name'] . '</option>';
    } elseif ($row_cat_view_2['cat_id'] != $cat_set) {
        echo '<option value="' . $row_cat_view_2['cat_id'] . '"> - ' . $row_cat_view_2['cat_name'] . '</option>';
    }
}
?></select>
<select name="usr" type="usr"  id="usr" class="form-control" onchange="refreshPage_usr(this.value);">
			<?php if ($usr_set != ' '){
			while ($row_usr_view = mysqli_fetch_array($result_usr_view, MYSQLI_ASSOC)) {
    if ($row_usr_view['usr_id'] == $usr_set) {
        echo '<option value="' . $row_usr_view['usr_id'] . '">' . $row_usr_view['usr_id'] . '</option>';}
}}?>
			<option value="0"><?php echo $lang['17'];?></option>
			<?php
while ($row_usr_view_2 = mysqli_fetch_array($result_usr_view_2, MYSQLI_ASSOC)) {
    if ($row_usr_view_2['usr_id'] != $usr_set) {
        echo '<option value="' . $row_usr_view_2['usr_id'] . '">' . $row_usr_view_2['usr_id'] . '</option>';}
}
?></select>
<select name="inout" type="inout"  id="inout" class="form-control" onchange="refreshPage_inout(this.value);">
			<?php if ($inout == 'P'){
			echo '<option value="P">'.$income.'</option>';
			echo '<option value="0"> </option>';
			echo '<option value="N">'.$outcome.'</option>';}
			elseif ($inout == 'N'){ 
			echo '<option value="N">'.$outcome.'</option>';
			echo '<option value="0"> </option>';
			echo '<option value="P">'.$income.'</option>';}
			else { 
			echo '<option value="0"> </option>';
			echo '<option value="N">'.$outcome.'</option>';
			echo '<option value="P">'.$income.'</option>';}
			?>
			</select>
</form>
    </div>
</form>
</div>
 

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
      <div class="col-sm-12">
        <div class="chart-wrapper">
          <div class="chart-title">
		  <b><?php echo $lang['22'];?></b>
          </div>
          <div class="chart-stage">
            <div id="grid-1-1">
              <div id="det_cat_chart" class="chart"></div>
            </div>
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