<?php
include('config.php');
include('header.html');

session_start();

if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
     $_SESSION['lang'] = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
} 
if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $_SESSION['lang'] = 'en';
};

include('./locale/'.$_SESSION['lang'].'.php');

$message = "";

$sql    = "SELECT usr_id FROM user";
$result_ck = mysqli_query($db, $sql);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
$querys = explode("\n", file_get_contents("./install.sql"));
    foreach ($querys as $q) {
      $q = trim($q);
      if (strlen($q)) {
        $message .= substr($q,0,20).' -> ';
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

?>

<div class="container">
  <div class="row" style="margin-top:20px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
      <form name="install" method="post" action="install.php" role="form">
        <fieldset>
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <input type="submit" name="install" value="<?php echo $lang['70']?>" class="btn btn-default <?php if($result_ck){echo 'disabled';};?>"></div>
          </div>
        </fieldset>
      </form>
			<?php if($message <> ''){ echo '<br>
            <div class="panel panel-default">
			<div class="panel-body">'.$message.'<br>
			<a href=./index.php>'.$lang['72'].'</a><br>'.$lang['73'].'</div></div>';};?>
    </div>
  </div>
</div>
</body>
</html>