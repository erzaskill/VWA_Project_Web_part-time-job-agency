<?php
include "database.php";
$userID = $_SESSION["id"];

if (!empty($_POST['profil'])) { //update profilu
  $jmeno = trim(htmlspecialchars($_POST['jmeno'])); //s protekci htmlspecialchars(vezme to <> jako text - nemuzu formatovat), bez mezer
  $prijmeni = trim(htmlspecialchars($_POST['prijmeni']));
  $datum_narozeni = $_POST['datum_narozeni'];
  $adresa = $_POST['adresa'];
  $heslo = $_POST['heslo'];
  $telefonni_cislo = $_POST['telefonni_cislo'];
  $sql = "UPDATE users SET jmeno = '" . $jmeno . "', prijmeni = '" . $prijmeni . "', datum_narozeni = '" . $datum_narozeni . "',";
  $sql .= "adresa = '" . $adresa . "', heslo = '" . $heslo . "', telefonni_cislo = '" . $telefonni_cislo . "' WHERE `id` = '" . $userID . "';";

  $result = mysqli_query($conn, $sql); //vykona sql query
  if ($result) { //pokud neco updatnu
    echo "Údaje úspěšně změněny. ";
  } else { //pokud se stala chyba s updatem
    echo "Chyba. ";
  }
}


$sql = "SELECT * FROM `users` WHERE `id` = '" . $userID . "';"; //vezme to data od zalogovaneho usera
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_row($result); //vezmeme si ten jeden radek

$jmeno = $row[1];
$prijmeni = $row[2];
$email = $row[3];
$heslo = $row[4];
$telefonni_cislo = $row[5];
$datum_narozeni = $row[6];
$adresa = $row[7];



?>
<!DOCTYPE html>
<div id="obsah">
  <html>

  <head>
    <title>Profil</title>
    <meta charset="utf-8" />
  </head>

  <body> <!--- Tabulka meho profilu --->
    <h1>Můj profil </h1>
    <form method="post" action="#">
      <table>
        <tr>
          <td><b>Jméno: </b> </td>
          <td><input type="text" name="jmeno" value="<?php echo $jmeno ?>"></td>
        </tr>
        <tr>
          <td><b>Příjmení: </b></td>
          <td><input type="text" name="prijmeni" value="<?php echo $prijmeni ?>"></td>
        </tr>
        <tr>
          <td><b>Email: </b></td>
          <td><input type="email" name="email" value="<?php echo $email ?>" readonly></td> <!--- Nemuzes zmenit email --->
        </tr>
        <tr>
          <td><b>Heslo: </b></td>
          <td><input type="password" name="heslo" value="<?php echo $heslo ?>"></td>
        </tr>
        <tr>
          <td><b>Datum narození: </b> </td> <!--- podminka musi ti byt aspon 18 let --->
          <td><input type="date" max="<?php echo (date('Y-m-d', (strtotime('-18 year', strtotime(date("Y-m-d")))))); ?>" name="datum_narozeni" value="<?php echo $datum_narozeni ?>"></td>
        </tr>
        <tr>
          <td><b>Telefonní číslo: </b> </td>
          <td><input type="tel" name="telefonni_cislo" value="<?php echo $telefonni_cislo ?>"></td>
        </tr>
        <tr>
          <td><b>Adresa: </b></td>
          <td><input type="text" name="adresa" value="<?php echo $adresa ?>"></td>
        </tr>
        <tr>
          <td><input type="submit" name="profil" value="Uložit úpravy" /></td>
        </tr>
      </table>
      <h1>Tvoje směny</h1>
      <?php
        if (!empty($_POST['smena'])) { //po kliknuti na odhlaseni z dane smeny si to vezme to idcko
          $id_smeny = $_POST['id_smeny'];
          
          $sql = "UPDATE `smena` SET `brigadnik` = NULL WHERE `id` = '" . $id_smeny . "';"; //odhlaseni brigadnika
          
          $result = mysqli_query($conn, $sql); //provede se sqlko
        }
        $sql = "SELECT * FROM `smena` S JOIN `firma` F ON F.id = S.firma WHERE `brigadnik` = '".$_SESSION['id']."';"; //data pro brigadnika, ktery je prihlasen
        $result = mysqli_query($conn, $sql);
        $queryResult = mysqli_num_rows($result); //numbers of rows it found
        //echo $sql;

        $sum_money = 0;
        $sum_hours = 0;
        echo "<div class=white>";
        if ($queryResult > 0) { //pokud jsou tam nejake data
          echo "<table border=1px>";
          echo "<tr class = 'hlavicka'><td>Název směny</td><td>Náplň práce</td><td>Firma</td><td>Pracovní doba</td><td>Datum</td><td>Začátek smeny</td>";
          echo "<td>Konec směny</td><td>Hodinová mzda</td><td>Mzda za směnu</td><td>Přihlášení</td></tr>";
          while ($row = mysqli_fetch_row($result)) {
            $brigadnik = $row[7];
            

            $start_time = new DateTime($row[4]); //prevedeme na datetime, kvuli pozdejsim
            $end_time = new DateTime($row[5]);
            $diff = $start_time->diff($end_time); //rozdil mezi start a end timem
            $time_diff = date("h:i", strtotime($diff->h . ":" . $diff->i)); //naformatovani rozdilu
            $hours_int = ($end_time->getTimestamp()/*cas v sekundach*/-$start_time->getTimestamp())/3600; //prevede cas v hodinach a minutach na cas v hodinach (7:30=7.5h)
            if(is_null($row[8])){ //pokud datum neni zadano
              $datum = "--.--.----";
            } else{
                $datum = date("d.m.Y",strtotime($row[8])); //datum ve formatu co chceme
            }

            $sum_money+= $hours_int * $row[6]; //vypocet celkem vydelanych penez
            $sum_hours+= $hours_int; //vypocet celkovych odpracovanych hodin

            
            echo "<tr style='background-color: red'>";
        

            echo "<td>" . $row[1] . "</td>"; //nazev smeny
            $maxlenght = 20;
            if (strlen($row[2]) > $maxlenght) { //napln prace
              $mensi_popis = substr($row[2], 0, $maxlenght) . " ..."; //pokud bude popis prace delsi nez maxlenght znaku, tak se tam pak napisou 3 tecky
              echo '<td class="CellWithComment">' . $mensi_popis . ' <span class="CellComment">' . $row[2] . '</span></td>'; //pokud namirime mysi na napln prace, tak se ukaze cely
            } else {
              echo "<td>" . $row[2] . "</td>";
            }
            echo "<td>" . $row[10] . "</td>"; //nazev firmy
            echo "<td>" . $time_diff . " hodin</td>"; //zmenili jsme cas na string a pak string na cas ve spravnem formatu -> pracovni doba
            echo "<td>" . $datum . "</td>"; // datum
            echo "<td>" . $start_time->format("H:i") . "</td>"; //zacatek smeny
            echo "<td>" . $end_time->format("H:i") . "</td>"; //konec smeny
            echo "<td>" . $row[6] . " Kč</td>"; //hodinova mzda
            echo "<td>" . $hours_int * $row[6] . " Kč</td>"; //mzda za smenu
            echo '<form method="post" action="#">';
              
            echo "<td><input type='submit' name='smena' value='Odhlásit se'/></td>"; // odhlaseni se
      
            echo '<input type="text" name="id_smeny" value="' . $row[0] . '" style="display:none;">'; //id smeny, aby jsme mohli odhlasit z dane smeny
            echo '</form>';
            echo "</tr>";
          }

          echo "</table>";
          echo "</div>";
          echo "<h3> Vydělané peníze celkem: " . $sum_money . " Kč.</h3>";
          echo "<h3> Pracovní doba celkem: " . $sum_hours . " hodin.</h3>";
        } else { //pokud nemame zadne smeny prihlaseny
          echo "Směny nenalezeny.";
        }

      ?>
    </form>
    </br>
    <hr>

    </br>

  </body>

  </html>

  </html>
</div>