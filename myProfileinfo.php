<?php

session_start();
if(!isset($_SESSION['user_id'])) header("location: index.php");

$_SESSION['message']='';
$_SESSION['znaleziono']=0;
$flag=0;
if ( !isset($_SESSION['logged_in'])) {
  $_SESSION['message'] = "Nie jestes zalogowny/a";    
  header("location: index.php");
}
else
{
$id=$_SESSION['user_id'];

$mysqli=new mysqli('localhost', 'root', '', 'rezerwacje');
$result = $mysqli->query("SELECT * FROM uzytkownicy WHERE id_user='$id'"); 

$dane=$result->fetch_assoc();
$data=$mysqli->real_escape_string(date('YYYY-mm-dd'));
$result2 = $mysqli->query("SELECT id_rezerwacji, godzina, data from seanse, rezerwacje where rezerwacje.id_uzytkownika='$id' AND rezerwacje.id_seansu=seanse.id_seansu AND seanse.data>='$data' ");
if(isset($_POST['id_anulowania'])){
  $usun=$_POST['id_anulowania'];
  if($mysqli->query("call usunrezerwacje('$usun')"))
  header("location: myProfileinfo.php");
else 
 $_SESSION['message'] = "Usuwanie nie powiodło się"; 
}


}

?>

<link rel="stylesheet" href="form.css" type="text/css">
<!DOCTYPE html>
<html>
<div class="body-content">
 <ul class="tab-group">
        <li class="tab"><a href="login.php">Log out</a></li>
        <li class="tab "><a href="seanse1.php">Rezerwuj</a></li>
        <li class="tab "><a href="myProfileinfo.php">Moje rezerwacje</a></li>
        <?php
        if(!$_SESSION['typ']=="0"){
          ?>
        <li class="tab"><a href="admin.php">Dodaj film</a></li>
        <li class="tab "><a href="admin1.php">Dodaj seans</a></li>
        <li class="tab "><a href="seanse.php">Seanse w salach</a></li>
        <?php }
        ?>
</ul>

  <div class="module">
      <h1>Twoje aktualne rezerwacje</h1>
  <div class="alert alert-error"><?= $_SESSION['message']?></div>
  <div class="rez">
  <center>
<?php
echo "Zalogowany/a jako:  ";
if(!$_SESSION['typ']=="0") echo " admin:  ";
echo $dane['imie'];
echo "  ";
echo $dane['nazwisko'];
echo "<br><br>";

$result = $mysqli->query("SELECT * FROM rezerwacje, seanse, filmy WHERE rezerwacje.id_uzytkownika='$id' AND seanse.id_seansu=rezerwacje.id_seansu AND filmy.id_filmu=seanse.id_filmu AND seanse.data>=CURRENT_DATE"); 

echo "Twoje rezerwacje:  <br>";
echo "</center>";
while($row=$result->fetch_assoc()){
  if(($row['data']>date('Y-m-d'))||(($row['data']==date('Y-m-d')&&$row['godzina']>date('H:i:s')))){
  ?>
  <div class="rezz">
  <?php
echo "<button style="."width:300px"." class="."button".">";
echo "Nr rezerwacji: ".$row['id_rezerwacji'];
echo "</button>";
echo "<br>";
echo $row['tytul'];
echo "<br>";
echo "data: ".date('d.m.Y', strtotime($row['data']))."r.  ";
echo "   godzina: ".date('G:i', strtotime($row['godzina']));
echo "<br>";
echo "Sala: ".$row['id_sali'];
echo "<br>";
?>

<?php
$idrezerwacji=$row['id_rezerwacji'];
$result1 = $mysqli->query("SELECT rzad, miejsce from bilety where id_rezerwacji='$idrezerwacji'"); 
$a=1;
while($ros=$result1->fetch_assoc()){
echo "Bilet ".$a."   ";
echo " - rząd: ".$ros['rzad']."   ";
echo "    miejsce: ".$ros['miejsce'];
echo "<br>";
$a++;
}
echo"</div>";
}
}
?>

<ul class="tab-group">
        

<form class="form" action="#" method="post" enctype="multipart/form-data" autocomplete="off">
<br>
<br>
Anuluj rezerwację:
<?php
echo "<select name='id_anulowania'>";
if($result2){
$a=1;
while($ros=$result2->fetch_assoc()){
if(($ros['data']>date('Y-m-d'))||(($ros['data']==date('Y-m-d')&&$ros['godzina']>date('H:i:s')))){
echo "<option value ='".$ros['id_rezerwacji']."' >" .$ros['id_rezerwacji']."</option>";}
$i=$i+1;
}
echo "</select>"; 
}
?>
<input type="submit" value="Potwierdz anulowanie" name="anuluj" class="btn btn-block btn-primary"/>

</ul>
</form>
</div>
</div>

  </div>
</div>
</html>