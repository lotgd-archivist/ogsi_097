<?php
require_once "common.php";
page_header("Test Trova Oggetto");
output("`3Decidi di compiere un ultimo sforzo per aiutare la ragazza a raggiungere il suo villaggio. Attraversi il ponte ");
output("sospeso sul crepaccio e quando giungi dall'altra parte scopri la vera natura del `5Nero Cavaliere`3.`n`n ");
output("Altro non è che un fantoccio piazzato per spaventare i guerrieri meno coraggiosi. Ti giri per scoprire che ");
output("la leggiadra fanciulla è già al tuo fianco, e guardandola con fare interrogativo lei ti dice: \"`6Questa è ");
output("stata solo l'ultima prova per provare il tuo coraggio. Sei degno di entrare nel nostro villaggio e poter ");
output("sfruttare le opportunità che i vari negozi che vi troverai ti offrono. Sappi sfruttare l'occasione che ti è stata ");
output("concessa, non avrai un'altra chance fino alla prossima uccisione del `2`bDrago Verde`b`6.\"`n");
$casoogg=e_rand(99,99);
$casoogg1=e_rand(99,99);
if ($casoogg>94){
    $dove=101;
    if ($casoogg1>94){$dove+=1;}
    $sql = "SELECT * FROM oggetti WHERE dove = '$dove' ORDER BY livello DESC,nome DESC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        //Caso in cui non ci sono oggetti nel contenitore richiesto
        output("`3Il fabbro ha fatto i conti senza l'oste, purtroppo non ha più neanche un oggetto.`n");
        output("Sei stato estremamente sfortunato `2{$session[user][name]}`3, sarà per la prossima volta.");
    }else{
        $caso_ogg = e_rand(0,db_num_rows($result));
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
            if ($i == $caso_ogg) {
                output ("Davanti a te compare ...`n`n");
                output ("Oggetto :" . $row[nome] . "`n`n");
                output ("Descrizione:" . $row[descrizione] . "`n`n");
            }
        }
            if ($session['user']['zaino'] == "0") {
                output ("Raccogli l'oggetto e lo metti nello zaino.`n`n");
                $session['user']['zaino'] = $row[id_oggetti];
                $oggetto_id = $row[id_oggetti];
                $sqlu = "UPDATE oggetti SET dove=0 WHERE id_oggetti='$oggetto_id'";
                db_query($sqlu) or die(db_error(LINK));
            }else{
                output ("Sfortunatamente non hai posto nello zaino e lo devi lasciare quì.`n`n");
            }
    }
}


addnav("Torna al Villaggio","village.php");
addnav("Test Trova Oggetto","testogg.php");
page_footer();
?>