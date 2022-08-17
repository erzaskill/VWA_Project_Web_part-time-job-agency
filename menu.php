<?php
session_start(); //Da mi to me unique id sessionu (browseru)
?>
<table class="menu2" style="border-collapse: unset !important;">
    <tr>
        <td class="td_menu"><a href="index.php?stranka=smeny">Směny</a></td>
        <td class="td_menu"><a href="index.php?stranka=firmy">Firmy</a></td>
        <?php
        if (isset($_SESSION["id"])) { //pokud mam sve id (po prihlaseni)
            echo '<td class="td_menu"><a href="index.php?stranka=profil">Můj profil</a></td>';
            echo '<td class="td_menu"><a href="index.php?stranka=logout">Odhlásit se</a></td>';
        } else {
            echo '<td class="td_menu"><a href="index.php?stranka=registrace">Registrace</a></td>';
            echo '<td class="td_menu"><a href="index.php?stranka=login">Přihlásit se</a></td>';
        }
        ?>

    </tr>
</table>
<!--- => Muzu zmacknout F5 pro reload stranky, a nic to neposle a nesubmitne (kdybych pridaval treba firmu, a po refreshnul, tak mi ji prida znova)--->
<script> 
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>
