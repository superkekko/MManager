<?php

include('./locale/'.$_SESSION['lang'].'.php');

include ('RandomColor.php');

$page_name = basename($_SERVER['PHP_SELF']);
$year      = date('Y');

?>
<body>
<nav role="navigation" class="navbar navbar-default">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
        <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <?php
echo '<a href="dashboard.php?year=' . $year . '" class="navbar-brand">MManager</a>';
?>
   </div>
    <!-- Collection of nav links and other content for toggling -->
    <div id="navbarCollapse" class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li <?php
if ($page_name == 'dashboard.php') {
    echo 'class="active"';
}
;
?>><a href="dashboard.php?year=<?php
echo date('Y');
?>"><?php echo $lang['05'];?></a></li>
            <li <?php
if ($page_name == 'add.php') {
    echo 'class="active"';
}
;
?>><a href="add.php"><?php echo $lang['06'];?></a></li>
            <li <?php
if ($page_name == 'transaction.php') {
    echo 'class="active"';
}
;
?>><a href="transaction.php"><?php echo $lang['07'];?></a></li>
            <li <?php
if ($page_name == 'settings.php') {
    echo 'class="active"';
}
;
?>><a href="settings.php"><?php echo $lang['08'];?></a></li>
        </ul>
        <ul class="nav navbar-nav navbar-right">
            <li><a href="logout.php"><?php echo $lang['09'];?></a></li>
        </ul>
    </div>
</nav>