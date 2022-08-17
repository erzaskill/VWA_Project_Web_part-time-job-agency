<div id="obsah">
<h1>Směny</h1>
<?php
    include "database.php";
    if ($_POST){
        $id_smeny = $_POST['id_smeny'];
        if(!empty($_POST['odhlasit_se'])){
            $sql = "UPDATE `smena` SET `brigadnik` = NULL WHERE `id` = '".$id_smeny."';";    
        } else {
            $sql = "UPDATE `smena` SET `brigadnik` = '".$_SESSION['id']."' WHERE `id` = '".$id_smeny."';";    
        }  
        $result = mysqli_query($conn, $sql);
    }
    $sql = "SELECT * FROM `smena` S JOIN `firma` F ON F.id = S.firma;";
    $result = mysqli_query($conn, $sql);
    $queryResult = mysqli_num_rows($result); //numbers of rows it found
    //echo $sql;

    if ($queryResult > 0) {
        echo "<table border=1px>";
        echo "<tr class = 'hlavicka'><td>Název směny</td><td>Náplň práce</td><td>Firma</td><td>Pracovní doba</td><td>Začátek smeny</td>";
        echo "<td>Konec směny</td><td>Hodinová mzda</td><td>Mzda za směnu</td><td>Přihlášení</td></tr>";
        while ($row = mysqli_fetch_row($result)) {
            $brigadnik = $row[7];

            $start_time = new DateTime($row[4]);
            $end_time = new DateTime($row[5]);
            $diff = $start_time->diff($end_time);
            $time_diff = date("h:i", strtotime($diff->h . ":" . $diff->i));
            $return_time = $diff->format("%h");
            $hours_int = ($end_time->getTimestamp()-$start_time->getTimestamp())/3600;

            if ($brigadnik == null){
                echo "<tr style='background-color: green'>";
            } else {
                echo "<tr style='background-color: red'>";
            }

            echo "<td>" . $row[1] . "</td>";
            $maxlenght= 20;
            if (strlen($row[2]) > $maxlenght){
                $mensi_popis = substr($row[2], 0, $maxlenght) . " ..."; //pokud bude popis prace delsi nez maxlenght znaku, tak se tam pak napisou 3 tecky
                echo '<td class="CellWithComment">' . $mensi_popis . ' <span class="CellComment">'.$row[2].'</span></td>';
                
            } else {
                echo "<td>" . $row[2] . "</td>";
            }    
            echo "<td>" . $row[9] . "</td>";
            echo "<td>" . $time_diff . " hodin</td>"; //zmenili jsme cas na string a pak string na cas ve spravnem formatu
            echo "<td>" . $start_time->format("H:i") . "</td>";
            echo "<td>" . $end_time->format("H:i") . "</td>";
            echo "<td>" . $row[6] . " Kč</td>";
            echo "<td>" . $hours_int*$row[6] . " Kč</td>";
            echo '<form method="post" action="#">';
            if (isset ($_SESSION['id'])){ 
                if ($brigadnik == null){
                    echo "<td><input type='submit' value='Přihlásit se' /></td>";
                } else {
                    if($brigadnik == $_SESSION['id']){
                        echo "<td><input type='submit' value='Odhlásit se'/></td>";
                        echo '<input type="text" name="odhlasit_se" value="1" style="display:none;">';  
                    } else {
                        echo "<td><input type='submit' value='Odhlásit se' disabled/></td>";
                    }
                }  
            } else{
                echo "<td><input type='submit' value='Přihlásit se' disabled/></td>";    
            }
            echo '<input type="text" name="id_smeny" value="'.$row[0].'" style="display:none;">';
            echo '</form>';
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Směny nenalezeny.";
    }

?>
<br>
</div>