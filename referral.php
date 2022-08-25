<?php
require_once "common.php";

if ($session['user']['loggedin']){
    page_header("Referral Page");
    addnav("T?Torna alla casetta","lodge.php");
    output("Riceverai automaticamente 10 punti per ogni persona che porti a questo sito e che raggiunge il livello 15.
    `n`n
    Come può il sito sapere che ho portato una persona?`n
    Facile! Quando dirai ad un tuo amico di questo sito, fallo con il seguente link:`n`n
  ".getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']))."referral.php?r=". rawurlencode($session['user']['login'])."`n`n
    Se lo fai, il sito saprà che tu sei quello che li ha condotti qui. Quando raggiungeranno il livello 15 per la prima volta, otterrai i tuoi punti!");

    $sql = "SELECT name,level,refererawarded FROM accounts WHERE referer={$session['user']['acctid']} ORDER BY dragonkills,level";
  //    output($sql);
    $result = db_query($sql);
    output("`n`nAccount che hai portato::`n<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nome</td><td>Livello</td><td>Guadagnato?</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
        output($row['name']);
        output("</td><td>{$row['level']}</td><td>".($row['refererawarded']?"`@Si!`0":"`\$No!`0")."</td></tr>",true);
    }
    if (db_num_rows($result)==0){
        output("<tr><td colspan='3' align='center'>`iNone!</td><?tr>",true);
    }
    output("</table>",true);
    page_footer();
}else{
    page_header("Benvenuto a Legend of the Green Dragon");
    output("`@Legend of the Green Dragon è il remake del classico gioco per BBS Legend of the Red Dragon. Avventurati nel classico mondo di quello che è stato considerato uno dei primi veri giochi RPG multiplayer!
    ");
    addnav("Crea un personaggio","create.php?r=".HTMLEntities($_GET['r']));
    addnav("Pagina Login","index.php");
    page_footer();
}?>