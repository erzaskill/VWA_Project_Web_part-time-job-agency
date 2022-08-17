<div id="obsah">
<h1>Firmy</h1>
<?php
    include "database.php";
    if ($_POST){
        
        //Smazani firmy
        if (!empty($_POST['smazat'])){ //po kliknuti na smazat
            $id_firmy=$_POST['id'];
            $sql = "DELETE FROM `firma` WHERE `id` = '".$id_firmy."';"; //Smazani specificke firmy s danym ID

        //Pridani firmy
        }elseif (!empty($_POST['pridat'])){
            $nazev_firmy=$_POST['nazev_firmy'];
            $ico=$_POST['ico'];
            $web=$_POST['web'];
            $email=$_POST['email'];

            $sql = "INSERT INTO `firma` (`nazev`,`ico`,`web`,`email`) VALUES('".$nazev_firmy."','".$ico."', '".$web."','".$email."');";
            //Pridani firmy do databaze

        } else {
            $id_firmy=$_POST['id'];
            $nazev_firmy=$_POST['nazev_firmy'];
            $ico=$_POST['ico'];
            $web=$_POST['web'];
            $email=$_POST['email'];
            $sql = "UPDATE `firma` SET `nazev` = '".$nazev_firmy."', `ico` = '".$ico."', `web` = '".$web."', `email` = '".$email."'";
            $sql.= "WHERE `id` = '".$id_firmy."';";
            //Updatne specifickou firmu s danym ID.
        }
        $result = mysqli_query($conn, $sql); //Jen pro testovani - ukaze nam kolik veci se zmenilo - vzdycky bude 1, exectne to danou sql query do databaze
    }
    $sql = "SELECT * FROM `firma` ORDER BY `nazev`;";
    $result = mysqli_query($conn, $sql); //Ma v sobe cely ten select
    $queryResult = mysqli_num_rows($result); //numbers of rows it found
    //echo $sql;

        //Testovani zda si admin
        if(empty($_SESSION['admin'])){ //pokud nejsem prihlaseny
            $_SESSION['admin'] = '0';
        }
        echo "<div class=white>";
        if ($_SESSION['admin'] == "1"){
            
            //tabulka pro upravu, pridani
            echo "<table border=1px>";
            echo "<tr class = 'hlavicka'><td>Název firmy</td><td>IČO</td><td>Webová stránka</td><td>Email</td><td>Uložit</td><td>Smazat</td></tr>";

            echo '<form method="post" action="#">';
            echo "<td><input type='text' name='nazev_firmy' ></td>";
            echo "<td><input type='text' name='ico' ></td>";
            echo "<td><input type='url' name='web' ></td>";
            echo "<td><input type='email' name='email' ></td>";
            echo "<td><input type='submit' name='pridat' value='➕' /></td>";
            echo "<td></td>";
            echo '</form>';
            echo '</tr>';


            if ($queryResult > 0) {
            while ($row = mysqli_fetch_row($result)) { //postupne prochazi radky resultu
                echo '<form method="post" action="#">';
                echo "<td><input type='text' name='nazev_firmy' value='" . $row[1] . "'></td>";
                echo "<td><input type='text' name='ico' value='" . $row[2] . "'></td>";
                echo "<td><input type='url' name='web' value='" . $row[3] . "'></td>";
                echo "<td><input type='email' name='email' value='" . $row[4] . "'></td>";
                echo "<input type='text' name='id' style='display:none;' value='" . $row[0] . "'>"; //nevidetelny input, ktery ma v sobe id firmy
                echo "<td><input type='submit' name='ulozit' value='✔' /></td>";
                echo "<td><input type='submit' name='smazat' value='❌' /></td>";
                echo '</form>';
                echo '</tr>';
            }
            echo "</table>";
            echo "</div>";
        } else {

            echo "</table>";
            echo "</div>";
            echo "Firmy nenalezeny.";
            
        }
        } else { //pokud nejsi admin
            echo "<table border=1px>";
            echo "<tr class = 'hlavicka'><td>Název firmy</td><td>IČO</td><td>Webová stránka</td><td>Email</td></tr>";
            while ($row = mysqli_fetch_row($result)) {                
                echo "<td>" . $row[1] . "</td>";
                echo "<td>" . $row[2] . "</td>";
                echo "<td>" . $row[3] . "</td>";
                echo "<td>" . $row[4] . "</td>";
                echo '</tr>';
            }
            echo "</table>";
            }
    

?>

<br>
</div>
