<?php
include('session.php');
include('header.html');
include('nav.php');
include('./locale/'.$_SESSION['lang'].'.php');

if (isset($_GET['year'])) {
    $year = mysqli_real_escape_string($db, $_GET['year']);
} else {
    $year = date('Y');
}

$sql_cat    = "select * from category order by parent_id, cat_id";
$result_cat = mysqli_query($db, $sql_cat);

if (isset($_POST['category'])) {
    $cat = mysqli_real_escape_string($db, $_POST['category']);
} else {
    $cat = 1;
}
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
 <script type="text/javascript">
 google.load("visualization", "1", {packages:["corechart"]});
 google.setOnLoadCallback(drawChart);
 function drawChart() {
 var data = google.visualization.arrayToDataTable([
      ['Ingresso', 'Importo'],
 <?php
 $income = $lang['11'];
 $outcome = $lang['12'];
$sql_tot    = "select case when income = 'P' then '$income' else '$outcome' end as income, val from tot where year = $year order by income";
$result_tot = mysqli_query($db, $sql_tot);
while ($row = mysqli_fetch_array($result_tot)) {
    echo "['" . $row['income'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 title: '<?php echo $lang['13'];?>',
 legend: 'none',
 <?php
$sql_ver    = "select val from tot where year = $year and income='P'";
$result_ver = mysqli_query($db, $sql_ver);
$count      = mysqli_num_rows($result_ver);
if ($count == 1) {
    echo "slices: {
            0: { color: 'green' },
            1: { color: 'red' }
          }
 };";
} else {
    echo "slices: {
            0: { color: 'red' },
            1: { color: 'green' }
          }
 };";
}
;
?>
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
$sql_tot_cat    = "select cat_name, abs(val) as val from tot_cat where income <> 'S' and year = $year order by cat_id";
$result_tot_cat = mysqli_query($db, $sql_tot_cat);
while ($row = mysqli_fetch_array($result_tot_cat)) {
    echo "['" . $row['cat_name'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 title: '<?php echo $lang['16'];?>',
 legend: 'none'
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
$sql_tot_usr_spe    = "select usr_mov, abs(val) as val from tot_usr where income <> 'S' and year = $year order by usr_mov";
$result_tot_usr_spe = mysqli_query($db, $sql_tot_usr_spe);
while ($row = mysqli_fetch_array($result_tot_usr_spe)) {
    echo "['" . $row['usr_mov'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 title: '<?php echo $lang['18'];?>',
 legend: 'none'
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
$sql_tot_usr_ent    = "select usr_mov, abs(val) as val from tot_usr where income = 'S' and year = $year order by usr_mov";
$result_tot_usr_ent = mysqli_query($db, $sql_tot_usr_ent);
while ($row = mysqli_fetch_array($result_tot_usr_ent)) {
    echo "['" . $row['usr_mov'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 title: '<?php echo $lang['19'];?>',
 legend: 'none'
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
$sql_det_month    = "select * from tot_eu where year = $year order by month";
$result_det_month = mysqli_query($db, $sql_det_month);
while ($row = mysqli_fetch_array($result_det_month)) {
    echo "['" . $row['month'] . "'," . $row['income'] . "," . $row['outcome'] . "],";
}
?>
    ]);
 var options = {
 title: '<?php echo $lang['21'];?>',
 colors: ['green', 'red'],
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
$sql_cat    = "select group_concat(distinct cat_name order by cat_id separator '\',\'') as cat from tot_cat_date where year in ($year, 0) and income <> 'S'";
$result_cat = mysqli_query($db, $sql_cat);
$row        = mysqli_fetch_array($result_cat);
echo "'" . $row['cat'] . "'";
?>],
 <?php
$sql_det_cat    = "select month, group_concat(val order by cat_id separator ',') as val from (select sum(val) as val, cat_name, cat_id, income, month from tot_cat_date where year in ($year,0) and income <> 'S' group by cat_name, cat_id, income, month) t group by month";
$result_det_cat = mysqli_query($db, $sql_det_cat);
while ($row = mysqli_fetch_array($result_det_cat)) {
    echo "['" . $row['month'] . "'," . $row['val'] . "],";
}
?>
    ]);
 var options = {
 title: '<?php echo $lang['22'];?>',
 legend: 'none',
 isStacked: true
 };
 var chart = new google.visualization.ColumnChart(document.getElementById("det_cat_chart"));
 chart.draw(data, options);
 }
</script>

<div class="row">
<div class="col-md-12 text-center">
    <?php
echo '<p>'.$lang['10'].' <a href=?year=' . (date('Y') - 2) . '>' . (date('Y') - 2) . '</a> - <a href=?year=' . (date('Y') - 1) . '>' . (date('Y') - 1) . '</a> - <a href=?year=' . date('Y') . '>' . date('Y') . '</a>';
?></p>
</div>

<div class="row">
  <div class="clearfix"></div>
  <div class="col-md-6">
    <div id="tot_chart" class="chart"></div>
  </div>
  <div class="col-md-6">
    <div id="cat_chart" class="chart"></div>
  </div>
</div>

<div class="row">
  <div class="clearfix"></div>
  <div class="col-md-6">
    <div id="tot_usr_ent_chart" class="chart"></div>
  </div>
  <div class="col-md-6">
    <div id="tot_usr_spe_chart" class="chart"></div>
  </div>
</div>

<div class="row">
  <div class="clearfix"></div>
  <div class="col-md-6">
    <div id="det_month_chart" class="chart"></div>
  </div>
  <div class="col-md-6">
    <div id="det_cat_chart" class="chart"></div>
  </div>
</div>

</body>
</html>