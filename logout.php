<!DOCTYPE html>
<div id="obsah">
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
   <?php
  
 
if($_POST) {
    session_unset();
    session_destroy(); //odstrani vsechny variables na dane session
    $home_url = "http://$_SERVER[HTTP_HOST]/index.php?stranka=login";
    echo("<script>window.location.href = '".$home_url."';</script>");//po odhlaseni nas to prehodi na login
    exit();
}
?>
<!DOCTYPE html>
<html>
  <head>
	 <title>Logout</title>
	 <meta charset="utf-8" />
  </head>
  <body>
    <h1>Logout: </h1>
    <form method="post" action="#">
      <table>
        
        
          <input type="text" name="none" style="display:none;"> <! --- ugly fix - sumbit button potrebuje input --->
        
          <td><input type="submit" value="Odhlásit se" /></td>
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