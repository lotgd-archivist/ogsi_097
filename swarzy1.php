<?php
require_once "common.php";
// edit train.php
// after this line -> output("`n`nYour master is `^$master[creaturename]`0.");
// add this line -> addnav("Train for a While","trainrm.php");
page_header("Palestra d'Allenamento di Swarzy");
output("`@`c`bPalestra d'Allenamento di Swarzy`b`c");
if ($_GET['op']=="train"){

    $session['user']['turns']-=1;
    $exp = $session['user']['level']*e_rand(5,12)+e_rand(0,9);
    $session['user']['experience']+=$exp;
    $sql = "SELECT * FROM masters WHERE creaturelevel = ".$session['user']['level'];
    $result = db_query($sql) or die(sql_error($sql));

if (db_num_rows($result) > 0){
    $master = db_fetch_assoc($result);
    $mast = $master['creaturename'];
}
    output("`^Ti alleni per mezz'ora con  $mast.`n");
    output("`^Guadagni $exp punti esperienza!`n");
    addnav("Torna alla Palestra","swarzy.php");
}
else{
    if ($session['user']['turns'] < 1){
        output("`n`n`%Sei troppo stanco per allenarti oggi!");
        addnav("Torna alla Palestra","train.php");
    }
    else{
        output("`#Entri nella palestra e ti guardi intorno.`n");
        output("All'interno ci sono spade, armature e istruttori.`n");
        output("Ogni sessione d'allenamento ti costerà un combattimento.");
        addnav("Allenati per un po'","swarzy1.php?op=train");
        addnav("Torna alla Palestra","swarzy.php");
     }
}
page_footer();
?>