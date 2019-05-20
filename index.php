
<?php
session_start();
$_SESSION['typ']="0";
$_SESSION['user_id'] = '';
$_SESSION['logged_in']=false;
$_SESSION['message']=' ';
$mysqli=new mysqli('localhost', 'root', '','pizzerino' );
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $login=$mysqli->real_escape_string($_POST['email']);
    $haslo=md5($_POST['password']);

    $sql = "SELECT * FROM uzytkownik WHERE login='$login'";
    $result = $mysqli->query( $sql ); 
 
$user = $result->fetch_assoc();

    if ( $haslo== $user['haslo']) {
        $_SESSION['user_id'] = $user['idUzytkownik'];
        $_SESSION['logged_in']=true;
        if($user['typ']=="1") 
          {
            $_SESSION['typ']="1";
            header("location: admin.php");
          }
          else
        header("location: myProfileinfo.php");
}
    else  $_SESSION['message'] = "Błędne hasło!";

}

?>

<link rel="stylesheet" href="form.css" type="text/css">
<div class="body-content">
 <ul class="tab-group">
        <li class="tab"><a href="form.php">Sign Up</a></li>
        <li class="tab "><a href="login.php">Log In</a></li>
      </ul>
  <div class="module">
    <h1>Zaloguj się</h1>
    <form class="form" action="login.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="alert alert-error"><?= $_SESSION['message']?></div>
      <input type="email" placeholder="Email" name="email" required />
      <input type="password" placeholder="Password" name="password" autocomplete="new-password" required />
      <input type="submit" value="Log in" name="register" class="btn btn-block btn-primary" />
    </form>
  </div>
</div>
