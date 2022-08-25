<?php
require_once "common.php";
$cost = $session['user']['level']*20;
$charm = $session['user']['charm']; //luke
$caso = e_rand(0,10);
if ($_GET['op']=="dex1"){
    page_header("Lato destro della parete rocciosa");
    if ($caso <= 5) {
         output("`5Prosegui lungo la parete di destra, cammini per quasi 30 minuti finchè arrivi a un vicolo cieco.");
         output("`5`nProvi a tornare indietro esaminando meglio la parete e dannazione trovi una grotta nascosta tra le frasche.");
         addnav("Entra nella grotta","lacosa2.php?op=grotta");
         addnav("Torna al Villaggio","village.php");
    }else{
         output("`5Prosegui lungo la parete di destra, cammini per quasi 30 minuti finchè arrivi a un vicolo cieco,
         non resta che tornare al paese.");
         addnav("Torna al Villaggio","village.php");
    }
}elseif ($_GET['op']=="sin1"){
        page_header("Lato sinistro della parete rocciosa");
        output("`5Prosegui lungo la parete di sinistra, cammini per quasi 10 minuti finchè in lontananza noti l'entrata di una carverna `$ sorvegliata da 4 goblin.");
        output("`5`nCosa fai.");
        addnav("Attacca i 4 Goblin","goblin4.php");
        addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op']=="scase"){
        page_header("La stanza del tesoro");
        $exp=$session['user']['experience']*0.8;
        output("`5 Entri in una stanza buia, vedi molte monete ammassate al centro e qualche gemma.");
        output("`5`nHai trovato la `STANZA DEL TESORO.");
        output("`5`n`nHai preso 10.000 pezzi d'oro.");
        $session['user']['quest'] += 3;
        $session['user']['gold']+=10000;
        debuglog("trova 10000 oro e 3 gemme nella stanza del tesoro");
        output("`n`nHai guadagnato $exp punti esperienza.`n`n");
        $session['user']['experience']+=$exp;
        addnews($session['user']['name']." ha preso il tesoro nella caverna dei goblin.");
        output("`5`nSei molto stanco è ora di tornare a casa.");
        addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op']=="grotta"){
        page_header("La grotta");
        output("`5 Entri nella grotta, cammini in un tunnell abbastanza angusto. Prosegui per qualche minuto,
        rumori improvvisi e voci lontane ti fanno preoccupare.");
        output("`5`nArrivi ad un bivio `$ Dove vai ?.");
        addnav("Scendi le scale","covore.php");
        addnav("Vai a destra","lacosa2.php?op=dex2");
        addnav("Vai a sinistra","lacosa2.php?op=sin2");
}elseif ($_GET['op']=="dex2"){
        page_header("Baratro");
        $exp=$session['user']['experience']*0.6;
        output("`5Prosegui verso destra e a un certo punto ti sparisce il terreno sotto i piedi ..... CADI.`n");
        output("`5Sei morto.`n");
        output("Perdi `btutto`b l'oro e la metà delle gemme che avevi con te !!!`n");
        output("Almeno hai imparato qualche cosa riguardo alle grotte guadagnato $exp punti esperienza.`n`n");
        output("`3Puoi continuare a giocare domani`n");
        $session['user']['experience']+=$exp;
        $session['user']['alive']=false;
        $session['user']['hitpoints']=0;
        $goldlost=$session['user']['gold'];
        $gemlost=$session['user']['gems'];
        $session['user']['gold']=0;
        $gemlost=$session['user']['gems']-round($session['user']['gems']/2);
        $session['user']['gems']=round($session['user']['gems']/2);
        $session['user']['quest'] += 1;
        addnav("Daily News","news.php");
        debuglog("è morto cercando la stanza del tesoro e ha perso $goldlost oro e $gemlost gemme");
        addnews($session['user']['name']." è caduto in un baratro nella caverna dei goblin.");
        addnav("Torna al villaggio","village.php");

}elseif ($_GET['op']=="sin2"){
        page_header("Prosegui per il corridoio di sinistra");
        output("Cammini lungo il corridoio di sinistra e arrivi a una porta chiusa.");
        if ($session['user']['thievery']>=3) {
            output("`nLe tue abilità da scassinatore ti permettono di scassinare la porta");
            addnav("Scassina la serratura","lacosa2.php?op=scase");
            addnav("Sfonda la porta","sfopo.php");
            addnav("Torna indietro","lacosa2.php?op=grotta");
        }else{
            addnav("Sfonda la porta","sfopo.php");
            addnav("Torna indietro","lacosa2.php?op=grotta");
        }
}else{
    page_header("Continua la corsa");
    output("Infuriato ti alzi e ricominci ad inseguire il Goblin. È svelta quella dannata creatura, e nel sottobosto è avvantaggiata.");
    output("`n`5La foresta è fitta e la luce inizia a faticare ad infiltrarsi, all'improvviso ti ritrovi davanti ad una parete di roccia.`n");
    output("Ti guardi a destra e sinistra, del Goblin nessuna traccia.`$ Dove vai ?");
    if ($session['user']['thievery']>=3 OR $session['user']['race'] == 4){
        output("`nLe tue abilità ti fanno notare un buco nella parete, e noti delle impronte, sicuramente del goblin");
        addnav("E?Entra nella Grotta","lacosa2.php?op=grotta");
        addnav("T?Torna al Villaggio","village.php");
        addnav("D?Vai a Destra","lacosa2.php?op=dex1");
        addnav("S?Vai a Sinistra","lacosa2.php?op=sin1");
    }else{
        addnav("T?Torna al Villaggio","village.php");
        addnav("D?Vai a Destra","lacosa2.php?op=dex1");
        addnav("S?Vai a Sinistra","lacosa2.php?op=sin1");
    }
}
page_footer();
?>