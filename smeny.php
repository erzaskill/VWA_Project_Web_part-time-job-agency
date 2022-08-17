<div id="obsah">
<h1>Směny</h1>
<?php
    include "database.php";
    if ($_POST){
        
        if (!empty($_POST['smazat'])){ //pokud kliknes na delete tlacitko
            $id_smeny=$_POST['id']; //vezme si to idcko
            $sql = "DELETE FROM `smena` WHERE `id` = '".$id_smeny."';"; //smaze smenu s id, kterou jsme si vzali
        }elseif (!empty($_POST['pridat'])){ //pokud kliknes na pridani - vezme si to vsechny informace a insertnout se do databaze
            $nazev_smeny=$_POST['nazev_smeny'];
            $popis_prace=$_POST['popis_prace'];
            $firma= $_POST['firma'];
            $cas_zacatku=$_POST['cas_zacatku'];
            $cas_konce=$_POST['cas_konce'];
            $hodinova_mzda=$_POST['hodinova_mzda'];
            $datum = $_POST['datum'];

            $sql = "INSERT INTO `smena` (`nazev`,`popis_prace`,`firma`,`cas_zacatku`,`cas_konce`,`hodinova_mzda`, `datum`) VALUES('".$nazev_smeny."','".$popis_prace."'";
            $sql.=", '".$firma."','".$cas_zacatku."','".$cas_konce."','".$hodinova_mzda."','".$datum."');";

        } elseif (!empty($_POST['odhlasit_se'])) { //pokud klidnes na odhlasit se, smena uz nebude mit prihlaseneho uzivatele k ni
            $id_smeny = $_POST['id'];
            $sql = "UPDATE `smena` SET `brigadnik` = NULL WHERE `id` = '".$id_smeny."';";
        } elseif(!empty($_POST['prihlasit_se'])){ //pokud kliknes na prihlasit se, smene se prideli dany uzivatel
            $id_smeny = $_POST['id'];
            $sql = "UPDATE `smena` SET `brigadnik` = '".$_SESSION['id']."' WHERE `id` = '".$id_smeny."';";     
        } else { //Pokud to neni zadna z moznosti nahore - jedna se pak o update dat, vezme si to data, ktere se pak updatnout
            $id_smeny=$_POST['id'];
            $nazev_smeny=$_POST['nazev_smeny'];
            $popis_prace=$_POST['popis_prace'];
            $firma= $_POST['firma']; 
            $cas_zacatku=$_POST['cas_zacatku'];
            $cas_konce=$_POST['cas_konce'];
            $hodinova_mzda=$_POST['hodinova_mzda'];
            $datum = $_POST['datum'];

            $sql = "UPDATE `smena` SET `nazev` = '".$nazev_smeny."', `popis_prace` = '".$popis_prace."', `firma` = '".$firma."'";
            $sql.= ",`cas_zacatku` = '".$cas_zacatku."',`cas_konce` = '".$cas_konce."',`hodinova_mzda` = '".$hodinova_mzda."', `datum` = '".$datum."' WHERE `id` = '".$id_smeny."';";
        }
        $result = mysqli_query($conn, $sql); //vsechno z toho nahore co se mohlo stat se vezme a da do $results
    }
    $sql = "SELECT `id`,`nazev` FROM `firma`;"; //vezmeme id a nazev firmy
    $options = array(); 
    $result = mysqli_query($conn, $sql);
    while($x = mysqli_fetch_row($result)){
        array_push($options, $x); //vlozeni kazdeho radku z sqlka do nabidky
    }

    
    $usedDates = array();
    if(!empty($_SESSION['id'])){ //pokud jsem prihlasen
    $sql = "SELECT `datum` FROM `smena` WHERE `brigadnik` = '".$_SESSION['id']."';"; //vezmi datum, kde kteremu je prihlaseny user
    $result = mysqli_query($conn, $sql);
    while($x = mysqli_fetch_row($result)){
        array_push($usedDates, $x); //vlozeni kazdeho radku s daty do pole
    }
    }  
    

    $sql = "SELECT * FROM `smena` S JOIN `firma` F ON F.id = S.firma;";
    $result = mysqli_query($conn, $sql);
    $queryResult = mysqli_num_rows($result); //numbers of rows it found
    //echo $sql;


        //Testovani zda jsem admin
        if(empty($_SESSION['admin'])){//pokud nejsem prihlaseny 
            $_SESSION['admin'] = '0';
        }
        echo "<div class=white>";
        if ($_SESSION['admin'] == "1"){ //pokud jsem admin
            echo "<table border=1px>";

            echo "<tr class = 'hlavicka'><td>Název směny</td><td>Náplň práce</td><td>Firma</td><td>Datum</td><td>Začátek smeny</td>";
            echo "<td>Konec směny</td><td>Hodinová mzda</td><td>Přihlášení</td><td>Uložit</td><td>Smazat</td></tr>";

            echo "<tr>";
            echo '<form method="post" action="#">';
            echo "<td><input type='text' name='nazev_smeny' ></td>";
            echo "<td><input type='text' name='popis_prace' ></td>";
            echo "<td>";
                echo "<select name='firma'>"; //dropdown
                foreach($options as $firma){ //kazdy radek z pole options dame do $firma - pro insert
                    echo "<option value='".$firma[0]."'>".$firma[0].' '.$firma[1]."</option>"; //value je id firmy, v dropdownu vidime id a nazev firmy
                }

                
                

            echo "</select></td>";
            //echo "<td><input type='number' disabled></td>"; 
            
            echo "<td><input type='date' name='datum' ></td>";
            echo "<td><input type='time' name='cas_zacatku' ></td>";
            echo "<td><input type='time' name='cas_konce' ></td>";
            echo "<td><input type='number' name='hodinova_mzda' ></td>";
            //echo "<td><input type='number' disabled></td>";
            echo "<td></td>";
            echo "<td><input type='submit' name='pridat' value='➕' /></td>";
            echo "<td></td>";
            echo '</form>';
            echo '</tr>';

            if ($queryResult > 0) {
            while ($row = mysqli_fetch_row($result)) {
                $brigadnik = $row[7];

                $start_time = new DateTime($row[4]);
                $end_time = new DateTime($row[5]);
                $diff = $start_time->diff($end_time);
                $time_diff = date("h:i", strtotime($diff->h . ":" . $diff->i));
                $hours_int = ($end_time->getTimestamp()-$start_time->getTimestamp())/3600;

                if ($brigadnik == null){ //zeleny pokud tam neni signuty brigadnik, cerveny pokud je
                    echo "<tr style='background-color: green'>";
                } else {
                    echo "<tr style='background-color: red'>";
                }

                echo '<form method="post" action="#">';
                echo "<td><input type='text' name='nazev_smeny' value='" . $row[1] . "'></td>"; // nazev smeny s konkretni value, kterou muzeme pak updatovat
                echo "<td><input type='text' name='popis_prace' value='" . $row[2] . "'></td>"; // popis prace
                echo "<td>";
                echo "<select name='firma'>"; //dropdown
                foreach($options as $firma){
                    if($row[3]==$firma[0]){ //prednastaveni hodnoty v dropdownu - pokud id firmy ve smene souhlasi s if firmy v options, tak se to preselectne
                        echo "<option selected value='".$firma[0]."'>".$firma[0].' '.$firma[1]."</option>";  //prednastaveni 
                    } else {
                        echo "<option value='".$firma[0]."'>".$firma[0].' '.$firma[1]."</option>"; //ostatni hodnoty, ktere jsou v dropdownu
                    }

                }

                echo "</select></td>";
                //echo "<td><input type='text' value='" . $time_diff . "' disabled></td>"; 

                echo "<td><input type='date' value='" . $row[8] . "' name='datum' ></td>"; //datum
                echo "<td><input type='time' name='cas_zacatku' value='" . $row[4] . "'></td>"; //cas zacatku
                echo "<td><input type='time' name='cas_konce' value='" . $row[5] . "'></td>"; //konec 
                echo "<td><input type='number' name='hodinova_mzda' value='" . $row[6] . "'></td>"; //hodiva mzda
                //echo "<td><input type='number' value='" . $hours_int*$row[6] . "' disabled></td>"; //zbytecny - zabiralo to spoustu mista
                echo "<input type='text' name='id' style='display:none;' value='" . $row[0] . "'>"; //nevidetelny input, ktery ma v sobe id smeny
                if (isset ($_SESSION['id'])){ //pokud jsem prihlaseny
                    if ($brigadnik == null){ //pokud se nikdo neprihlasil na smenu
                        $match = 0;
                        foreach($usedDates as $listDate){ //jdeme skrz data prihlaseneho uzivatel
                            if($listDate[0]==$row[8]){ //pokud datum smeny se shoduje s datem, kde uz je uzivatel prihlasen
                                $match = 1;
                            }
                        }
                        if ($match == 1){ //pokud uz je uzivatel prihlasen na dane datum
                            echo "<td><input type='submit' name='prihlasit_se' value='Přihlásit se' disabled/></td>"; //nemuze se prihlasit
                        } else {
                            echo "<td><input type='submit' name='prihlasit_se' value='Přihlásit se'/></td>"; //muze se prihlasit
                        }
                    } else { //pokud uz nejaky uzivatel se prihlasil na tuto smenu
                        if($brigadnik == $_SESSION['id']){ // ses ten uzivatel ty?
                            echo "<td><input type='submit' name='odhlasit_se' value='Odhlásit se'/></td>";  //muzes se odhlasit
                        } else {
                            echo "<td><input type='submit' value='Odhlásit se' disabled/></td>"; //nemuzes se odhlasit - je to zasedle
                        }
                    }  
                } else{ //pokud nejsi prihlaseny vubec
                    echo "<td><input type='submit' value='Přihlásit se' disabled/></td>";    
                }
                
                echo "<td><input type='submit' name='ulozit' value='✔' /></td>"; //ulozit
                echo "<td><input type='submit' name='smazat' value='❌' /></td>"; //smazat
                echo '</form>';
                echo '</tr>';
            }
            echo "</table>";
            echo "</div>";
        } else { //pokud nemame zadne smeny v databazi
            echo "</table>";
            echo "</div>";
            echo "Směny nenalezeny.";
        }
        } else { //pokud nejsi admin
            echo "<table border=1px>";
            echo "<tr class = 'hlavicka'><td>Název směny</td><td>Náplň práce</td><td>Firma</td><td>Pracovní doba</td><td>Datum</td><td>Začátek smeny</td>";
            echo "<td>Konec směny</td><td>Hodinová mzda</td><td>Mzda za směnu</td><td>Přihlášení</td></tr>";
            
            
            while ($row = mysqli_fetch_row($result)) {
                $brigadnik = $row[7];

                $start_time = new DateTime($row[4]);
                $end_time = new DateTime($row[5]);
                $diff = $start_time->diff($end_time);
                $time_diff = date("H:i ", strtotime($diff->h . ":" . $diff->i));           
                $hours_int = ($end_time->getTimestamp()-$start_time->getTimestamp())/3600;
                
                $datum = date("d.m.Y",strtotime($row[8]));
                
                
                if ($brigadnik == null){
                    echo "<tr style='background-color: green'>";
                } else {
                    echo "<tr style='background-color: red'>";
                }
                echo '<form method="post" action="#">';                
                echo "<td>" . $row[1] . "</td>";
                $maxlenght= 20;
                if (strlen($row[2]) > $maxlenght){ //pokud popis je delsi nez maxlenght
                    $mensi_popis = substr($row[2], 0, $maxlenght) . " ..."; //pokud bude popis prace delsi nez maxlenght znaku, tak se tam pak napisou 3 tecky
                    echo '<td class="CellWithComment">' . $mensi_popis . ' <span class="CellComment">'.$row[2].'</span></td>';
                    
                } else { //pokud ne
                    echo "<td>" . $row[2] . "</td>";
                }
                echo "<td>" . $row[10] . "</td>"; //nazev firmy
                echo "<td>" . $time_diff . "</td>"; //Pracovni doba
                echo "<td>" . $datum . "</td>"; //datum
                echo "<td>" . $row[4] . "</td>"; //Zacatek smeny
                echo "<td>" . $row[5] . "</td>"; // Konec smeny
                echo "<td>" . $row[6] . "</td>"; // Hodinova mzda
                echo "<td>" . $hours_int*$row[6] . "</td>"; //Penize za smenu
                echo "<input type='text' name='id' style='display:none;' value='" . $row[0] . "'>"; //id smeny
                if (isset ($_SESSION['id'])){ //pokud si prihlaseny
                    if ($brigadnik == null){ //pokud nikdo neni prihlasen k teto smene
                        $match = 0;
                        foreach($usedDates as $listDate){ //viz nahore
                            if($listDate[0]==$row[8]){
                                $match = 1;
                            }
                        }
                        if ($match == 1){
                            echo "<td><input type='submit' name='prihlasit_se' value='Přihlásit se' disabled/></td>"; 
                        } else {
                            echo "<td><input type='submit' name='prihlasit_se' value='Přihlásit se'/></td>"; 
                        }

                    } else {
                        if($brigadnik == $_SESSION['id']){
                            echo "<td><input type='submit' name='odhlasit_se' value='Odhlásit se'/></td>";  
                        } else {
                            echo "<td><input type='submit' value='Odhlásit se' disabled/></td>";
                        }
                    }  
                } else{
                    echo "<td><input type='submit' value='Přihlásit se' disabled/></td>";    
                }
                echo '</form>';
                echo '</tr>';

            }

            echo "</table>";
            }
    

?>

<br>
</div>
