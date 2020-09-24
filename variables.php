<?php

//variables
$cur_yea = strftime("%Y");
$cur_month = strftime("%m");
$cur_user = $_SESSION['login_user'];
//get previous 6 months
for ($i = 5; $i >= 0; $i--) {
  $months[] = date("Y-n", strtotime( date( 'Y-n-01' )." -$i months"));
  $months_desc[] = ucwords(strftime('%B - %Y', strtotime(date("Y-m", strtotime( date( 'Y-m-01' )." -$i months")))));
}
$color = array("text-primary","text-success","text-info","text-warning","text-danger");

// get year balance
$sql_query = "select sum(val) as value from movement where year(dat_mov)=$cur_yea";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
$balance_year_no_format = $sql_row['value'];
$balance_year = number_format($sql_row['value'], 2, ',', '.');

// get month balance
$sql_query = "select sum(val) as value from movement where year(dat_mov)=$cur_yea and month(dat_mov)=$cur_month";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
$balance_month_no_format = $sql_row['value'];
$balance_month = number_format($sql_row['value'], 2, ',', '.');

// get month income
$sql_query = "select sum(val) as value from movement where year(dat_mov)=$cur_yea and month(dat_mov)=$cur_month and type='P'";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
$income_month = number_format($sql_row['value'], 2, ',', '.');

// get month expenses
$sql_query = "select sum(val) as value from movement where year(dat_mov)=$cur_yea and month(dat_mov)=$cur_month and type='N'";
$sql_exec = mysqli_query($db, $sql_query);
$sql_row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC);
$expenses_month = number_format($sql_row['value'], 2, ',', '.');

// get detail for users
$sql_query = "SELECT round(sum(val)/(select sum(val) from movement where TYPE='N' and year(dat_mov)=$cur_yea and month(dat_mov)=$cur_month)*100,0) as value, m.usr_mov, u.color from movement m join user u ON m.usr_mov=u.usr_id where TYPE='N' and YEAR(m.dat_mov)=$cur_yea and MONTH(m.dat_mov)=$cur_month group BY m.usr_mov order BY m.usr_mov";
$sql_exec = mysqli_query($db, $sql_query);
$data_usr=array();
while ($row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC)) {
    $data_usr[]= array_map('ucfirst', $row);
};

// get detail for category
$sql_query = "SELECT round(sum(val)/(select sum(val) from movement where TYPE='N' and year(dat_mov)=$cur_yea)*100,0) as value, c.cat_name, c.color from movement m join cat c ON m.cat_id=c.cat_id where TYPE='N' and YEAR(m.dat_mov)=$cur_yea group BY c.cat_name order BY round(sum(val)/(select sum(val) from movement where TYPE='N' and year(dat_mov)=$cur_yea)*100,0) desc";
$sql_exec = mysqli_query($db, $sql_query);
$cat_value=array();
while ($row = mysqli_fetch_array($sql_exec, MYSQLI_ASSOC)) {
    $cat_value[]= array_map('ucfirst', $row);
};
?>