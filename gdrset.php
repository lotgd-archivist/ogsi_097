<?php
require_once("common.php");
page_header("Scelta GDR");
if ($_GET['op'] == ""){
    output("`@Questo server è dedicato al GDR, ed in alcune aree del game si deve interpretare il proprio personaggio.");
    output("`nDevi decidere, ora, se vuoi partecipare in maniera attiva, sottostando alle regole intrinseche del GDR, ");
    output("oppure se non sei interessato. Questa scelta comporta l'accesso preferenziale a due piazze differenti, una ");
    output("dedicata al GDR, un'altra dove i commenti saranno `iliberi`i (pur sottostando alle regole indicate nel ");
    output("Regolamento).`n`nLa scelta che opererai ora sarà definitiva e non reversibile (a meno dell'intervento ");
    output("di un Admin), e questo ti consentirà di accedere direttamente alla piazza a cui sei interessato, ma ");
    output("avrai comunque la possibilità di accedere all'altra.`n`n");
    output("<form action='gdrset.php?op=scelta' method='POST'>",true);
    output("<input type='radio' name='scegli' value='si'>SI`n`\$",true);
    output("<input type='radio' name='scegli' value='no'>NO`n",true);
    addnav("","gdrset.php?op=scelta");
    output("`n<input type='submit' class='button' value='Fai la tua scelta'>`n`n",true);
}elseif ($_GET['op'] == "scelta") {
    $scelta = $_POST['scegli'];
    if ($scelta == "si"){
        output("`@Hai scelto di partecipare al GDR.`n`nSei sicuro della tua scelta ?");
        addnav("Si, sono sicuro","gdrset.php?op=si");
        addnav("No, ci ho ripensato","gdrset.php");
    }else{
        output("`@Hai scelto di non partecipare al GDR. Sei sicuro della tua scelta ?");
        addnav("Si, sono sicuro","gdrset.php?op=no");
        addnav("No, ci ho ripensato","gdrset.php");
    }
}elseif ($_GET['op'] == "si") {
    $session['user']['gdr'] = "si";
    output("`@Scelta impostata! Ora puoi proseguire e raggiungere la piazza`n");
    addnav("Piazza del Villaggio","village.php");
}elseif ($_GET['op'] == "no") {
    $session['user']['gdr'] = "no";
    output("`@Scelta impostata! Ora puoi proseguire e raggiungere la piazza`n");
    addnav("Piazza del Villaggio","village1.php");
}
page_footer();
?>