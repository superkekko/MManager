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

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // username and password sent from form 
    
    $myusername = mysqli_real_escape_string($db, $_POST['username']);
    $mypassword = mysqli_real_escape_string($db, $_POST['password']);
    
    $sql    = "SELECT usr_id FROM user WHERE usr_id = '$myusername' and passwd = sha1('$mypassword') and valid = 'S'";
    $result = mysqli_query($db, $sql);
    $row    = mysqli_fetch_array($result, MYSQLI_ASSOC);
    
    $count = mysqli_num_rows($result);
    
    // If result matched $myusername and $mypassword, table row must be 1 row
    
    if ($count == 1) {
        //session_register("myusername");
        $_SESSION['login_user'] = $myusername;
        
        $sql_1 = "UPDATE user SET tms_upd = now() WHERE usr_id = '$myusername'";
        mysqli_query($db, $sql_1);
        
        header("location: dashboard.php?year=" . date('Y'));
    } else {
        $sql_2 = "INSERT INTO log (note, tms) VALUES ('login attempt using $myusername:$mypassword', now())";
        mysqli_query($db, $sql_2);
        $error = $lang['04'];
    }
}
?>

<div class="container">
  <div class="row" style="margin-top:20px">
    <div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
      <form name="form_login" method="post" action="index.php" role="form">
        <fieldset>
          <h2>MManager</h2>
          <div class="form-group">
            <input name="username" type="text" id="user_id" class="form-control" placeholder="<?php echo $lang['01']?>">
          </div>
          <div class="form-group">
            <input type="password" name="password" id="password" class="form-control" placeholder="<?php echo $lang['02']?>">
          </div>
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <input type="submit" name="submit" value="<?php echo $lang['03']?>" class="btn btn-default">
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6"> <?php
echo $error;
?></div>
          </div>
        </fieldset>
      </form>
    </div>
  </div>
</div>
</body>
</html>