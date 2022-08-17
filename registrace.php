<!DOCTYPE html>
<div id="obsah">
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
   <?php  
if($_POST) { //po kliknuti na odeslat
  $jmeno = trim(htmlspecialchars($_POST['jmeno'])); //s protekci htmlspecialchars(vezme to <> jako text - nemuzu formatovat), bez mezer
  $prijmeni = trim(htmlspecialchars($_POST['prijmeni']));    
  $datum_narozeni=$_POST['datum_narozeni'];
  $adresa=$_POST['adresa'];
  $heslo=$_POST['heslo'];
  $telefonni_cislo=$_POST['telefonni_cislo'];
  $email=$_POST['email'];


  include "database.php";
  $sql="SELECT `email` FROM `users` WHERE `email` = '".$email."';"; //sqlko, protekce proti stejne emailove adrese (nemuzu se zaregistrovat 2x na stejny email)
  $result=mysqli_query($conn, $sql);
  $queryResult = mysqli_num_rows($result); //numbers of rows it found
  if ($queryResult > 0){
    echo "Tento email je už používán! ";
  } else { // pokud email neni vyuzity - vytvori se novy uzivatel
    $sql="INSERT INTO users (jmeno, prijmeni, datum_narozeni, adresa, telefonni_cislo, email, heslo)"; 
    $sql.="values('".$jmeno."','".$prijmeni."','".$datum_narozeni."','".$adresa."','".$telefonni_cislo."','".$email."','".$heslo."')"; 
    $result=mysqli_query($conn,$sql);
    if ($result==1){
      echo "Účet úspěšně vytvořen. ";
    } else {
      echo "error";
    }
  }

}
?>
<!DOCTYPE html>
<html>
  <head>
	 <title>Registrace</title>
	 <meta charset="utf-8" />
  </head>
  <body>
    <h1>Registrace: </h1>
    <form method="post" action="#">
      <table>
        <tr>
          <td><b>Jméno: </b> </td>
          <td><input type="text" name="jmeno" ></td>
        </tr>
        <tr>
          <td><b>Příjmení: </b></td>
          <td><input type="text" name="prijmeni" ></td>
        </tr>  
        <tr>
          <td><b>Email: </b></td>
          <td><input type="email" name="email" ></td>
        </tr>
        <tr>
          <td><b>Heslo: </b></td>
          <td><input type="password" name="heslo" ></td>
        </tr>
        <tr>
            <td><b>Datum narození: </b> </td> <!--- podminka musi ti byt aspon 18 let --->
            <td><input type="date" max="<?php echo (date('Y-m-d', (strtotime('-18 year', strtotime(date("Y-m-d")))))); ?>" name="datum_narozeni"></td>
        </tr>
        <tr>
            <td><b>Telefonní číslo: </b> </td>
            <td><input type="tel" name="telefonni_cislo"></td>
        </tr>
        <tr>
          <td><b>Adresa: </b></td>
          <td><input type="text" name="adresa" ></td>
        </tr>         
        <tr>
          <td><input type="submit" value="ODESLAT" /></td>
        </tr>
      </table>      
    </form>
    </br>
    <hr>
    
    </br>

   </body>
</html>
</html>
</div>