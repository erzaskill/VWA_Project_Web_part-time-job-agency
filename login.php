<!DOCTYPE html>
<div id="obsah">
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
   <?php
   
if($_POST) {
  $heslo=$_POST['heslo'];
  $email=$_POST['email'];


  include "database.php";
  $sql = "SELECT `id`,`admin` FROM `users` WHERE `email` = '".$email."' AND `heslo` = '".$heslo."';";
  //echo $sql; // testovani
  //exit();
  $result = mysqli_query($conn, $sql);
  $queryResult = mysqli_num_rows($result); //numbers of rows it found
  //echo $sql;
  if ($queryResult > 0){
      $row=mysqli_fetch_row($result);
        $_SESSION['id']=$row[0];
        $_SESSION['admin']=$row[1];
        echo "Úspěšné přihlášení. ";
        $home_url = "http://$_SERVER[HTTP_HOST]/index.php?stranka=profil"; 
        echo("<script>window.location.href = '".$home_url."';</script>"); //prehodi nas to na nas profil po prihlaseni
        exit();
  } else{
    echo "Takový účet neexistuje. ";
  }



}
?>
<!DOCTYPE html>
<html>
  <head>
	 <title>Login</title>
	 <meta charset="utf-8" />
  </head>
  <body>
    <h1>Login: </h1>
    <form method="post" action="#">
      <table>
        <tr>
          <td><b>Email: </b></td>
          <td><input type="email" name="email" ></td>
        </tr>
        <tr>
          <td><b>Heslo: </b></td>
          <td><input type="password" name="heslo" ></td>
        </tr>
        
        
        
          <td><input type="submit" value="Přihlásit se" /></td>
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