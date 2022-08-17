<!DOCTYPE html>
<html lang="cs" dir="ltr">
  <head> 
    <meta charset="windows-1250">
    <title>EZ Jobs Agency</title>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/menu.css" />
    <link rel="stylesheet" type="text/css" href="css/patka.css" />
    <link rel="stylesheet" type="text/css" href="css/basic.css" />
    <link rel="stylesheet" type="text/css" href="css/obsah.css" />
    
    
    
  </head> 
  
  
  <body>
  <div> 
      <div class="obrazek"> <!--ZAČÁTEK HLAVIČKA -->
      </div> <!--KONEC HLAVIČKA -->
    
    
    <div class="pas">
    <div id="menu"> <!-- ZACATEK MENU-->
           <?php  require("menu.php");   ?>
    </div> <!-- KONEC MENU-->
    
    
          <?php
            /*echo session_id(); //session id
            echo "     ";
            if (!empty($_SESSION['id'])){
              echo $_SESSION['id']; //id usera po prihlaseni
            }*/
            if (isset($_GET['stranka']))    //testuju zda jde o prvni zobrazeni, bez kliknuti na menu 
            {
               require($_GET['stranka'].".php");
            
                    //  @require($_GET['stranka'].".php");//potlačí výpis chyby 
             }
             else{
                  require("smeny.php");
             
             }
          
  
          
          
            ?>
    
    
    <div id="obsah">
        <div class="obsah">
        </div>

    </div> <!-- KONEC OBSAH-->
    </div>

    <div id="patka">
      <table class="patka">
        <tr>
            <td class="l_td">&copy; Erik Praunsperger</td>
    
            <td class="r_td">
                <script>
                    promenna = new Date(); // vytvoří proměnnou obsahující aktuální datum
                    rok = promenna.getYear() + 1900; // počítá se 1900 roků před aktualním rokem
                    mesic = promenna.getMonth() + 1;//leden je 0
                    denVMesici = promenna.getDate();
                    document.write("Dnes je " + denVMesici + ". " + mesic + ". " + rok);
                </script>
            </td>
        </tr>
      </table>
    </div> <!-- KONEC PATIČKA-->
  </div> 
    


  </body>
</html>