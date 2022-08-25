<?php
require_once("common.php");
require_once("common2.php");
addcommentary();
page_header("Capanno di Caccia");
$session['user']['locazione'] = 145;

if ($_GET['op1'] == "data"){
   $data = $_POST['data'];
   savesetting("datagdr", $data);
}
//script per il prelievo in automatico dei punti donazione
$sql = "SELECT COUNT( approvato ) AS totale FROM votazioni WHERE prelevato='no' AND controllato='si' AND approvato='si' AND nome='".$session['user']['login']."'";
$resulta = db_query($sql) or die(db_error(LINK));
$result = db_fetch_assoc($resulta);
if ($result[totale]> 0){
    $session['user']['donation'] += $result[totale];
    $sqlu = "UPDATE votazioni SET prelevato='si' WHERE nome='".$session['user']['login']."' AND controllato='si' AND approvato='si'";
    db_query($sqlu) or die(db_error(LINK));
    output("`b`c`VGli dei ti hanno dato $result[totale] punti donazione!`0`c`b`n`n");
    debuglog(" ha guadagnato ".$result[totale]." punti donazione grazie ai voti dati.");
}
//fine script
$pointsavailable = $session['user']['donation']-$session['user']['donationspent'];
$entry = ($session['user']['donation'] > 0) ||
($session['user']['superuser'] & SU_EDIT_COMMENTS);
if ($pointsavailable < 0) $pointsavailable = 0; // something weird.
addnav("Azioni");
if(!$_GET['op']){
    //modificare nome con id tra qualche settimana
    output("`b`c`!Capanno di Caccia`0`c`b`n`n");
    output("`7Segui uno stretto sentiero che si allontana dalle stalle e giunge ad un rustico Capanno da Caccia.`n Una guardia ti ferma sulla porta e ti chiede di vedere la tua tessera di socio.`n");
    if ($entry){
        output("Dopo che gliel'hai mostrata dice, \"`3Molto bene sir, benvenuto al Capanno di Caccia di J. C. Petersen. Ti sei guadagnato", ($session['user']['sex']?"a ":"o "));
        output("`^".$session['user']['donation']."`3  punti e ne hai ancora `^$pointsavailable`3 da spendere.\" `7e ti lascia entrare.`n`n");
        output("Entri in un salone dominato da un enorme camino all'estremintà opposta. Le pareti di legno sono rivestite di armi, scudi e trofei di caccia imbalsamati, incluse le teste di parecchi draghi che sembrano muoversi nella luce tremolante.`n`n");
        output("Molte sedie di pelle dall'alto schienale riempiono la stanza. Sulla sedia più vicina al camino siede J. C. Petersen che sta leggendo un pesante volume intitolato \"`&L'Alchimia Oggi`7\".`n`n");
        output("Mentre ti avvicini un grosso cane da caccia ai suoi piedi alza la testa e ti osserva. Riconoscendoti ritorna ad accucciarsi per dormire.`n`n");
        output("Nelle vicinanze altri rudi cacciatori discutono tranquillamente:`n");
        viewcommentary("hunterlodge","Discuti tranquillamente",25,5);

    }else{
        $iname = getsetting("innname", LOCATION_INN);
        output("Estrai la tua Tessera di Ubriacone Incallito della Locanda alla Testa di Cinghiale, con 9 delle 10 caselline forate con un minuscolo profilo della testa di Cedrik.`n`n");
        output("La guardia gli getta uno sguardo, ti consiglia di non bere così tanto, e ti rispedisce al villaggio.");
    }
    addnav("P?Presenta amici","referral.php");
    addnav("D?Descrizione punti","lodge.php?op=points");
    //addnav("S?Situazione voti","lodge.php?op=voti");
    addnav("S?Bonus acquisiti","lodge.php?op=acquistati");
    //if($session[user][superuser]>0)
    addnav("G?GDR","lodge.php?op=gdr");
    addnav("V?Torna al Villaggio","village.php");
    addnav("Usa Punti");
    addnav("Oggetti","");
    if (donazioni('polvere_fata')==false){
        addnav("P?Polvere magica di fata (50)","lodge.php?op=polvere");
    }
    if (donazioni('sconto_cura')==true AND donazioni_usi('sconto_cura')==0){
        addnav("R?Rinnova Golinda card (100)","lodge.php?op=rinnovo_cura");
    }
    if (donazioni('sconto_cura')==false){
        addnav("G?Golinda card (200)","lodge.php?op=sconto_cura");
    }
    if (donazioni('turni')==false){
        addnav("5?5 turni extra (200)","lodge.php?op=turni");
    }

    if (donazioni('pass_draghi')==false){
        addnav("a?Pass ambasciata (250)","lodge.php?op=pass_draghi");
    }
    if (donazioni('sentiero_segreto')==false){
        addnav("S?Sentiero segreto (300)","lodge.php?op=sentiero");
    }
    addnav("Razze","");
    if (donazioni('gigante_tempesta')==false){
        addnav("G?Gigante tempeste (400)","lodge.php?op=gigante");
    }else{
        if (donazioni_usi('gigante_tempesta')==0){
            addnav("G?Gigante tempeste (200)","lodge.php?op=giganter");
        }
    }
    if (donazioni('centauro')==false){
        addnav("C?Centauro (400)","lodge.php?op=centauro");
    }else{
        if (donazioni_usi('centauro')==0){
            addnav("C?Centauro (200)","lodge.php?op=centauror");
        }
    }
    if (donazioni('barbaro')==false AND ($session['user']['dragonkills'] > 4 OR $session['user']['reincarna'] > 0)){
        addnav("B?Barbaro (600)","lodge.php?op=barbaro");
    }else{
        if (donazioni_usi('barbaro')==0 AND ($session['user']['dragonkills'] > 4 OR $session['user']['reincarna'] > 0)){
            addnav("B?Barbaro (300)","lodge.php?op=barbaror");
        }
    }
    if (donazioni('amazzone')==false AND ($session['user']['dragonkills'] > 4 OR $session['user']['reincarna'] > 0)){
        addnav("A?Amazzone (600)","lodge.php?op=amazzone");
    }else{
        if (donazioni_usi('amazzone')==0 AND ($session['user']['dragonkills'] > 4 OR $session['user']['reincarna'] > 0)){
            addnav("A?Amazzone (300)","lodge.php?op=amazzoner");
        }
    }
    if (donazioni('licantropo')==false AND ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0)){
        addnav("L?Licantropo (800)","lodge.php?op=licantropo");
    }else{
        if (donazioni_usi('licantropo')==0 AND ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0)){
            addnav("L?Licantropo (400)","lodge.php?op=licantropor");
        }
    }
    if (donazioni('minotauro')==false AND ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0)){
        addnav("M?Minotauro (800)","lodge.php?op=minotauro");
    }else{
        if (donazioni_usi('minotauro')==0 AND ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0)){
            addnav("M?Minotauro (400)","lodge.php?op=minotauror");
        }
    }

//    $dkdonazioni = $session['user']['dragonkills'] + min($session['user']['reincarna'],5);

    if (donazioni('titano')==false AND ($session['user']['dragonkills'] > 14 OR $session['user']['reincarna'] > 0)){
        addnav("T?Titano (1000)","lodge.php?op=titano");
    }else{
//        if (donazioni_usi('titano')==0 AND ($dkdonazioni > 14 OR $session['user']['reincarna'] > 0)){
        if (donazioni_usi('titano')==0 AND ($session['user']['dragonkills'] > 14 OR $session['user']['reincarna'] > 0)){
            addnav("T?Titano (500)","lodge.php?op=titanor");
        }
    }
    if (donazioni('demone')==false AND ($session['user']['dragonkills'] > 14 OR $session['user']['reincarna'] > 0)){
        addnav("D?Demone (1000)","lodge.php?op=demone");
    }else{
//        if (donazioni_usi('demone')==0 AND ($dkdonazioni > 14 OR $session['user']['reincarna'] > 0)){
        if (donazioni_usi('demone')==0 AND ($session['user']['dragonkills'] > 14 OR $session['user']['reincarna'] > 0)){
            addnav("D?Demone (500)","lodge.php?op=demoner");
        }
    }
    if (donazioni('eletto')==false AND ($session['user']['dragonkills'] > 14 OR $session['user']['reincarna'] > 0)){
        addnav("E?Eletto (1000)","lodge.php?op=eletto");
    }else{
//        if (donazioni_usi('eletto')==0 AND ($dkdonazioni > 14 OR $session['user']['reincarna'] > 0)){
        if (donazioni_usi('eletto')==0 AND ($session['user']['dragonkills'] > 14 OR $session['user']['reincarna'] > 0)){
            addnav("E?Eletto (500)","lodge.php?op=elettor");
        }
    }
    if (donazioni('chansonnier')==true){
       addnav("C?Cantastorie (Bonus GDR)","lodge.php?op=chansonnier");
    }
    addnav("Specialità","");
    if (donazioni('elementale')==false AND ($session['user']['dragonkills'] > 3 OR $session['user']['reincarna'] > 0)){
        addnav("E?Elementale (300)","lodge.php?op=elementale");
    }else{
        if (donazioni_usi('elementale')==0 AND ($session['user']['dragonkills'] > 3 OR $session['user']['reincarna'] > 0)){
            addnav("E?Elementale (150)","lodge.php?op=elementaler");
        }
    }
    if (donazioni('bardo')==false AND ($session['user']['dragonkills'] > 7 OR $session['user']['reincarna'] > 0)){
        addnav("B?Bardo (400)","lodge.php?op=bardo");
    }else{
        if (donazioni_usi('bardo')==0 AND ($session['user']['dragonkills'] > 7 OR $session['user']['reincarna'] > 0)){
            addnav("B?Bardo (200)","lodge.php?op=bardor");
        }
    }
    if (donazioni('barbarian')==false AND ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0)){
        addnav("B?Barbaro (500)","lodge.php?op=barbarian");
    }else{
        if (donazioni_usi('barbarian')==0 AND ($session['user']['dragonkills'] > 9 OR $session['user']['reincarna'] > 0)){
            addnav("B?Barbaro (250)","lodge.php?op=barbarianr");
        }
    }
    //Area Admin
    if ($session['user']['superuser'] > 0 AND $_GET['op']!='verifica'){
        addnav("Admin Area");
        addnav("V?Verifica voti","lodge.php?op=verifica");
    }
}else if ($_GET['op']=="points"){
    addnav("T?Torna al Capanno","lodge.php");
    output("`b`3Punti:`b`n`n");
    output("`7Per ogni EURO donato, l'account che ha effettuato la donazione riceverà 100 punti sostenitore nel gioco. \"`&Ma cosa sono i punti,\" `7chiedi? I punti possono essere riscattati per ottenere vari vantaggi nel gioco. Troverai accesso a questi vantaggi nella Capanno di Caccia. Con il passare del tempo, altri vantaggi verranno probabilmente aggiunti, e potranno essere acquistati quando saranno resi disponibili.`n`n");
    output("Donando anche un solo euro otterrai la tessera di appartenenza alla Capanno di Caccia, un'area riservata esclusivamente ai sostenitori. Le donazioni sono accettate solamente con incrementi di un euro.`n`n");
    output("\"`2Ma non ho accesso ad un account PayPal!\"`7 C'è la possibilità di donare tramite `\$POSTE PAY `7andano in posta a pagare, contattaci a `6luke@ogsi.it`7 per avere maggiori info!`n");
    output("\"`2Ma se non posso fare donazioni al vostro splendido progetto!`7\"`nBeh, esiste un altro modo per ottenere punti: portando altre persone al nostro sito! Otterrai 10 punti per ogni persona che hai condotto qui e che raggiunge il livello 15. Anche una sola persona che raggiunge il livello 10 ti farà ottenere l'accesso alla Capanno di Caccia.`n`n");
    output("Inoltre, potrai guadagnare punti sostenitore contribuendo in altri modi che l'amministrazione potrà specificare. Perciò non disperare se non puoi inviare denaro, ci saranno sempre alternative non monetarie per guadagnare punti.`n`n");
    output("Puoi guadagnare punti anche trovando bug nel game. Qualunque cosa, da un semplice malfunzionamento, sino ad un nuovo player con poteri da admin. Se non funziona come dovrebbe, segnalacelo con il link Richiesta d'Aiuto.
Sii il più descrittivo possibile quando segnali un errore (copia/incolla è uno dei migliori strumenti da usare).
Più il problema è grave, maggiori saranno i punti che guadagnerai.
Nota bene: se qualcosa non funziona come piacerebbe a te, o se non ti sembra \"giusto\", non viene conteggiato come \"bug\", è semplicemente un punto di vista, e sarà molto probabile che non riceverai punti per una segnalazione del genere. `n`n");
    output("`b`3Acquisti attualmente disponibili:`0`b`n");
    output("`b`!Oggetti:`0`b`n");
    output("`vT`8 - Una bottiglia di Polvere di Fata e può essere imprevedibile. Questa costa 50 punti`0`n");
    output("`vT`\$*`8 - Golinda card,per essere curati direttamente da Golinda, 50% di sconto per 30 giorni (di gioco) . Questa costa 200 punti`0`n");
    output("`vT`8 - 5 turni addizionali ogni giorno per i prossimi 30 giorni ( di gioco ) . Questo costa 200 punti`0`n");
    output("`9P`8 - Pass, un pass che da diritto ad entrare alcune volte la settimana nell'ambasciata. Questa costa 250 punti`0`n");
    output("`9P`8 - Mappa segreta della terra dei draghi. Questa costa  300 punti`0`n");
    output("`b`!Razze:`0`b`n");
    output("`^R`\$*`8 - Gigante della Tempesta; I Signori delle caverne oltre le montagne che circondano la città di Mardork. Questa costa 400 punti`0`n");
    output("`^R`\$*`8 - Centauro; I dominatori delle praterie abilissimi ed indomiti, sono audaci combattenti. Questa costa 400 punti`0`n");
    output("`^R`\$*`8 - Barbaro delle pianure (solo maschi e 5 DK o 1 reincarnazione); I Barbari esperti combattenti delle desolate pianure centrali. Questa costa 600 punti`0`n");
    output("`^R`\$*`8 - Amazzone (solo femmine e 5 DK o 1 reincarnazione); Le amazzoni tribù di donne cacciatrici che vivono in simbiosi con la natura. Questa costa 600 punti`0`n");
    output("`^R`\$*`8 - Licantropo (10 DK o 1 reincarnazione); Famigerati uomini lupo che nelle notti di luna piena terrorizzano campagne e città. Questa costa 800 punti`0`n");
    output("`^R`\$*`8 - Minotauro (10 DK o 1 reincarnazione); Robusti uomini con la testa di toro, famosi per la loro ferocia in combattimento. Questa costa 800 punti`0`n");
    output("`^R`\$*`8 - Titano (solo Sgrios e 15 DK o 1 reincarnazione); Razza mitica creata da `^Sgrios `8in perenne lotta contro il male. Questa costa 1000 punti`0`n");
    output("`^R`\$*`8 - Demone (solo Karnak e 15 DK o 1 reincarnazione); Incarnazione fisica del male, `\$Karnak `8vuole dominare il mondo. Questa costa 1000 punti`0`n");
    output("`^R`\$*`8 - Eletto (solo DragoVerde e 15 DK o 1 reincarnazione); I Dominatori di Draghi per eccellenza, originari della mitica Eld. Questa costa 1000 punti`0`n");
    output("`b`!Specialità:`0`b`n");
    output("`^R`\$*`8 - Elementale (4 DK o 1 reincarnazione); Puoi dominare gli elementi, e sfruttarli a tuo vantaggio. Questa costa 300 punti`0`n");
    output("`^R`\$*`8 - Bardo (8 DK o 1 reincarnazione); Sei un artista, sai intrattenere folle di persone, e sfruttare al meglio questo tuo talento. Questa costa 400 punti`0`n");
    output("`^R`\$*`8 - Barbaro (10 DK o 1 reincarnazione); Hai imparato a dominare la tua forza impressionante, sei una macchina da guerra! Questa costa 500 punti`0`n");
    output("`b`!Extra:`0`b`n");
    //output("`9P`8 - Titolo, aggiungere un titolo personalizzato al proprio nome. Questo costa 50 punti`0`n");
    output("`9P`8 - Nome drago, personalizzare il nome del proprio drago. Questo costa 100 punti`0`n");
    output("`n`# Legenda:`n");
    output("`vT`7 = Temporaneo, dura un periodo di tempo limitato in genere giorni di gioco e contano solo quelli giocati!`n");
    output("`9P`7 = Permanente, dura per sempre!`n");
    output("`^R`7 = Reincarnazione, dura fino a quando non vi reincarnate!`n");
    output("`\$*`7 = Rinnovabile, non dovete ricomprarlo ma rinnovarlo in genere al 50% del valore se non specificato diversamente!`n");

}
if ($_GET['op']=="sentiero"){
    output("`7Vuoi una mappa segreta che indica una percorso per la città dei draghi che ti farà risparmiare molto tempo per solo 300 punti donazione?`n");
    addnav("S?Si","lodge.php?op=sentierosi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="sentierosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 300){
        output("Hai comprato la mappa segreta !");
        $session['user']['donationspent'] +=300;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('sentiero_segreto','".$session['user']['acctid']."','P')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato sentiero per 300 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="polvere"){
    output("`7Polvere magica, le fate sono note per la loro bontà, curano e proteggono. Vuoi la polvere per solo 50 punti donazione?`n");
    addnav("S?Si","lodge.php?op=polveresi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="polveresi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 50){
        output("Hai comprato la polvere di fata, va usata la mattina appena svegli!");
        $session['user']['donationspent'] +=50;
        $durata=e_rand(30,60);
        $sql = "INSERT INTO donazioni (nome,idplayer,usi,tipo)
                     VALUES ('polvere_fata','".$session['user']['acctid']."','".$durata."','T')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato polvere_fata per 50 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }

}
if ($_GET['op']=="turni"){
    output("`75 turni, da usare ogni giorno per i prossimi 30 giorni (di gioco). Vuoi i turni extra per solo 200 punti donazione?`n");
    addnav("S?Si","lodge.php?op=turnisi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="turnisi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 200){
        output("Hai comprato 5 turni extra per i prossimi 30 giorni (di gioco)!");
        $session['user']['donationspent'] +=200;
        $sql = "INSERT INTO donazioni (nome,idplayer,usi,tipo)
                     VALUES ('turni','".$session['user']['acctid']."','30','T')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato turni_extra per 200 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }

}
if ($_GET['op']=="sconto_cura"){
    output("`2Golinda card, un abbonamento che da diritto a interessanti sconti presso la capanna del guaritore.`n`@Vuoi la card per solo 200 punti donazione?`n");
    addnav("S?Si","lodge.php?op=sconto_curasi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="sconto_curasi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 200){
        output("Hai comprato la Golinda card !");
        $session['user']['donationspent'] +=200;
        $sql = "INSERT INTO donazioni (nome,idplayer,usi,tipo)
                     VALUES ('sconto_cura','".$session['user']['acctid']."','30','T*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato sconto_cura per 200 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }

}
if ($_GET['op']=="rinnovo_cura"){
    output("`2Rinnova la tua Golinda card, per ottenere le fantistiche cure di Golinda.`n`@Vuoi rinnovare la card per solo 100 punti donazione?`n");
    addnav("S?Si","lodge.php?op=rinnovo_curasi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="rinnovo_curasi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 100){
        output("Hai rinnovato la Golinda card !");
        $session['user']['donationspent'] +=100;
        donazioni_usi_up('sconto_cura','30');
        debuglog(" ha rinnovato sconto_cura per 100 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }

}
//pass_draghi
if ($_GET['op']=="pass_draghi"){
    output("`7Pass, un pass che da diritto ad entrare alcune volte la settimana nell'ambasciata. Vuoi il pass per solo 250 punti donazione?`n");
    addnav("S?Si","lodge.php?op=pass_draghisi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="pass_draghisi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 250){
        output("Hai comprato il pass per la città dei draghi !");
        $session['user']['donationspent'] +=250;
        $sql = "INSERT INTO donazioni (nome,idplayer,usi,tipo)
                     VALUES ('pass_draghi','".$session['user']['acctid']."','3','P')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato pass_draghi per 250 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }

}
//gigante
if ($_GET['op']=="gigante"){
    output("`7Vuoi diventare un gigante delle tempeste per solo 400 punti donazione?`n");
    addnav("S?Si","lodge.php?op=gigantesi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="gigantesi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 400){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 13;
        $session['user']['attack']+=1;
        $session['user']['rockskinuses']+=1;
        $session['user']['donationspent'] +=400;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('gigante_tempesta','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato gigante per 400 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="giganter"){
    output("`7Vuoi diventare un gigante delle tempeste per solo 200 punti donazione?`n");
    addnav("S?Si","lodge.php?op=gigantesir");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="gigantesir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 200){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 13;
        $session['user']['attack']+=1;
        $session['user']['rockskinuses']+=1;
        $session['user']['donationspent'] +=200;
        donazioni_usi_up('gigante_tempesta',1);
        debuglog(" ha rinnovato gigante per 200 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//centauro
if ($_GET['op']=="centauro"){
    output("`7Vuoi diventare un centauro per 400 punti donazione?`n");
    addnav("S?Si","lodge.php?op=centaurosi");
    addnav("N?No","lodge.php");

}elseif ($_GET['op']=="centaurosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 400){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 18;
        $session['user']['muscleuses']+=1;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=400;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('centauro','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato centauro per 400 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="centauror"){
    output("`7Vuoi diventare un centauro per 200 punti donazione?`n");
    addnav("S?Si","lodge.php?op=centaurosir");
    addnav("N?No","lodge.php");

}elseif ($_GET['op']=="centaurosir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 200){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 18;
        $session['user']['muscleuses']+=1;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=200;
        donazioni_usi_up('centauro',1);
        debuglog(" ha rinnovato centauro per 200 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//barbaro
if ($_GET['op']=="barbaro"){
    if($session['user']['sex']==0){
        output("`7Vuoi diventare un barbaro delle pianure per solo 600 punti donazione?`n");
        addnav("S?Si","lodge.php?op=barbarosi");
        addnav("N?No","lodge.php");
    }else{
        output("`7Sei una femminuccia non puoi diventare un BARBARO !`n");
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="barbarosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 600){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 14;
        $session['user']['attack']+=2;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=600;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('barbaro','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato barbaro per 600 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="barbaror"){
    if($session['user']['sex']==0){
        output("`7Vuoi diventare un barbaro delle pianure per solo 300 punti donazione?`n");
        addnav("S?Si","lodge.php?op=barbarosir");
        addnav("N?No","lodge.php");
    }else{
        output("`7Sei una femminuccia non puoi diventare un BARBARO !`n");
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="barbarosir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 300){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 14;
        $session['user']['attack']+=2;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=300;
        donazioni_usi_up('barbaro',1);
        debuglog(" ha rinnovato barbaro per 300 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//amazzone
if ($_GET['op']=="amazzone"){
    if($session['user']['sex']==1){
        output("`7Vuoi diventare una amazzone per solo 600 punti donazione?`n");
        addnav("S?Si","lodge.php?op=amazzonesi");
        addnav("N?No","lodge.php");
    }else{
        output("`7Sei un maschietto non puoi diventare una AMAZZONE !`n");
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="amazzonesi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 600){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 15;
        $session['user']['natureuses']+=1;
        $session['user']['attack']+=1;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=600;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('amazzone','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato amazzone per 600 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="amazzoner"){
    if($session['user']['sex']==1){
        output("`7Vuoi diventare una amazzone per solo 300 punti donazione?`n");
        addnav("S?Si","lodge.php?op=amazzonesir");
        addnav("N?No","lodge.php");
    }else{
        output("`7Sei un maschietto non puoi diventare una AMAZZONE !`n");
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="amazzonesir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 300){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 15;
        $session['user']['natureuses']+=1;
        $session['user']['attack']+=1;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=300;
        donazioni_usi_up('amazzone',1);
        debuglog(" ha rinnovato amazzone per 300 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//licantropo
if ($_GET['op']=="licantropo"){
    output("`7Vuoi diventare un licantropo per 800 punti donazione?`n");
    addnav("S?Si","lodge.php?op=licantroposi");
    addnav("N?No","lodge.php");

}elseif ($_GET['op']=="licantroposi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 800){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 19;
        $session['user']['natureuses']+=1;
        $session['user']['attack']+=2;
        $session['user']['defence']+=2;
        $session['user']['donationspent'] +=800;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('licantropo','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato licantropo per 800 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="licantropor"){
    output("`7Vuoi diventare un licantropo per 400 punti donazione?`n");
    addnav("S?Si","lodge.php?op=licantroposir");
    addnav("N?No","lodge.php");

}elseif ($_GET['op']=="licantroposir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 400){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 19;
        $session['user']['natureuses']+=1;
        $session['user']['attack']+=2;
        $session['user']['defence']+=2;
        $session['user']['donationspent'] +=400;
        donazioni_usi_up('licantropo',1);
        debuglog(" ha rinnovato licantropo per 400 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//minotauro
if ($_GET['op']=="minotauro"){
    output("`7Vuoi diventare un minotauro per 800 punti donazione?`n");
    addnav("S?Si","lodge.php?op=minotaurosi");
    addnav("N?No","lodge.php");

}elseif ($_GET['op']=="minotaurosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 800){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 20;
        $session['user']['muscleuses']+=1;
        $session['user']['attack']+=3;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=800;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('minotauro','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato minotauro per 800 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="minotauror"){
    output("`7Vuoi diventare un minotauro per 400 punti donazione?`n");
    addnav("S?Si","lodge.php?op=minotaurosir");
    addnav("N?No","lodge.php");

}elseif ($_GET['op']=="minotaurosir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 400){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 20;
        $session['user']['muscleuses']+=1;
        $session['user']['attack']+=3;
        $session['user']['defence']+=1;
        $session['user']['donationspent'] +=400;
        donazioni_usi_up('minotauro',1);
        debuglog(" ha rinnovato minotauro per 400 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//demone
if ($_GET['op']=="demone"){
    if($session['user']['dio']==2){
        output("`7Vuoi diventare un demone per 1000 punti donazione?`n");
        addnav("S?Si","lodge.php?op=demonesi");
        addnav("N?No","lodge.php");
    }else{
        if ($session['user']['dio']==3){
           output("`7Sei un adoratore del DRAGO VERDE non puoi diventare DEMONE !`n");
        }elseif ($session['user']['dio']==3){
           output("`7Sei un adoratore di SGRIOS non puoi diventare DEMONE !`n");
        }else{
            output("`7Sei un agnostico, non puoi diventare DEMONE !`n");
        }
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="demonesi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 1000){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 17;
        $session['user']['darkartuses']+=2;
        $session['user']['attack']+=2;
        $session['user']['defence']+=2;
        $session['user']['donationspent'] +=1000;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('demone','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato demone per 1000 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="demoner"){
    if($session['user']['dio']==2){
        output("`7Vuoi diventare un demone per 500 punti donazione?`n");
        addnav("S?Si","lodge.php?op=demonesir");
        addnav("N?No","lodge.php");
    }else{
            if ($session['user']['dio']==3){
           output("`7Sei un adoratore del DRAGO VERDE non puoi diventare DEMONE !`n");
        }elseif ($session['user']['dio']==3){
           output("`7Sei un adoratore di SGRIOS non puoi diventare DEMONE !`n");
        }else{
            output("`7Sei un agnostico, non puoi diventare DEMONE !`n");
        }
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="demonesir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 500){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 17;
        $session['user']['darkartuses']+=2;
        $session['user']['attack']+=2;
        $session['user']['defence']+=2;
        $session['user']['donationspent'] +=500;
        donazioni_usi_up('demone',1);
        debuglog(" ha rinnovato demone per 500 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//eletto
if ($_GET['op']=="eletto"){
    if($session['user']['dio']==3){
        output("`7Vuoi diventare un eletto per 1000 punti donazione?`n");
        addnav("S?Si","lodge.php?op=elettosi");
        addnav("N?No","lodge.php");
    }else{
        if ($session['user']['dio']==1){
           output("`7Sei un adoratore di SGRIOS non puoi diventare ELETTO !`n");
        }elseif ($session['user']['dio']==2){
           output("`7Sei un adoratore di KARNAK non puoi diventare ELETTO !`n");
        }else{
            output("`7Sei un agnostico, non puoi diventare ELETTO !`n");
        }        
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="elettosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 1000){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 22;
        $session['user']['natureuses']+=2;
        $session['user']['attack']+=2;
        $session['user']['defence']+=3;
        $session['user']['donationspent'] +=1000;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('eletto','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato eletto per 1000 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="elettor"){
    if($session['user']['dio']==3){
        output("`7Vuoi diventare un eletto per 500 punti donazione?`n");
        addnav("S?Si","lodge.php?op=elettosir");
        addnav("N?No","lodge.php");
    }else{
            if ($session['user']['dio']==1){
           output("`7Sei un adoratore di SGRIOS non puoi diventare ELETTO !`n");
        }elseif ($session['user']['dio']==2){
           output("`7Sei un adoratore di KARNAK non puoi diventare ELETTO !`n");
        }else{
            output("`7Sei un agnostico, non puoi diventare ELETTO !`n");
        }        
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="elettosir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 500){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 22;
        $session['user']['natureuses']+=2;
        $session['user']['attack']+=2;
        $session['user']['defence']+=3;
        $session['user']['donationspent'] +=500;
        donazioni_usi_up('eletto',1);
        debuglog(" ha rinnovato eletto per 500 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//titano
if ($_GET['op']=="titano"){
    if($session['user']['dio']==1){
        output("`7Vuoi diventare un titano per 1000 punti donazione?`n");
        addnav("S?Si","lodge.php?op=titanosi");
        addnav("N?No","lodge.php");
    }else{
        if ($session['user']['dio']==3){
           output("`7Sei un adoratore del DRAGO VERDE non puoi diventare TITANO !`n");
        }elseif ($session['user']['dio']==2){
           output("`7Sei un adoratore di KARNAK non puoi diventare TITANO !`n");
        }else{
            output("`7Sei un agnostico, non puoi diventare TITANO !`n");
        }
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="titanosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 1000){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 16;
        $session['user']['muscleuses']+=2;
        $session['user']['attack']+=3;
        $session['user']['defence']+=2;
        $session['user']['donationspent'] +=1000;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('titano','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato titano per 1000 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="titanor"){
    if($session['user']['dio']==1){
        output("`7Vuoi diventare un titano per 500 punti donazione?`n");
        addnav("S?Si","lodge.php?op=titanosir");
        addnav("N?No","lodge.php");
    }else{
        if ($session['user']['dio']==3){
           output("`7Sei un adoratore del DRAGO VERDE non puoi diventare TITANO !`n");
        }elseif ($session['user']['dio']==2){
           output("`7Sei un adoratore di KARNAK non puoi diventare TITANO !`n");
        }else{
            output("`7Sei un agnostico, non puoi diventare TITANO !`n");
        }
        addnav("T?Torna al Capanno","lodge.php");
    }
}elseif ($_GET['op']=="titanosir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 500){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session['user']['race'] = 16;
        $session['user']['muscleuses']+=2;
        $session['user']['attack']+=3;
        $session['user']['defence']+=2;
        $session['user']['donationspent'] +=500;
        donazioni_usi_up('titano',1);
        debuglog(" ha rinnovato titano per 500 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//cantastorie
if ($_GET['op']=="chansonnier"){
    output("`^Vuoi diventare un Cantastorie utilizzando il bonus della vincita del concorso GDR ?`n");
    addnav("S?Si","lodge.php?op=chansonniersi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="chansonniersi"){
    addnav("T?Torna al Capanno","lodge.php");
    output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
    $session['user']['race'] = 21;
    //donazioni_usi_up('chansonnier',1);
    debuglog(" ha scelto cantastorie con il bonus GDR.");
}
//elementale
if ($_GET['op']=="elementale"){
    output("`7Vuoi apprendere i segreti degli elementi per 300 punti donazione?`n");
    addnav("S?Si","lodge.php?op=elementalesi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="elementalesi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 300){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session[user][specialty]=0;
        $session['user']['donationspent'] +=300;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('elementale','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato elementale per 300 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="elementaler"){
    output("`7Vuoi apprendere i segreti degli elementi per 150 punti donazione?`n");
    addnav("S?Si","lodge.php?op=elementalesir");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="elementalesir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 150){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session[user][specialty]=0;
        $session['user']['donationspent'] +=150;
        donazioni_usi_up('elementale',1);
        debuglog(" ha rinnovato elementale per 150 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//bardo
if ($_GET['op']=="bardo"){
    output("`7Vuoi apprendere i segreti del bardo per 400 punti donazione?`n");
    addnav("S?Si","lodge.php?op=bardosi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="bardosi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 400){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session[user][specialty]=0;
        $session['user']['donationspent'] +=400;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('bardo','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato bardo per 400 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="bardor"){
    output("`7Vuoi apprendere i segreti del bardo per 200 punti donazione?`n");
    addnav("S?Si","lodge.php?op=bardosir");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="bardosir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 200){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session[user][specialty]=0;
        $session['user']['donationspent'] +=200;
        donazioni_usi_up('bardo',1);
        debuglog(" ha rinnovato bardo per 200 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//barbarian
if ($_GET['op']=="barbarian"){
    output("`7Vuoi apprendere i segreti del barbaro per 500 punti donazione?`n");
    addnav("S?Si","lodge.php?op=barbariansi");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="barbariansi"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 500){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session[user][specialty]=0;
        $session['user']['donationspent'] +=500;
        $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('barbarian','".$session['user']['acctid']."','R*')";
        db_query($sql) or die(db_error($link));
        debuglog(" ha comprato barbarian per 500 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
if ($_GET['op']=="barbarianr"){
    output("`7Vuoi apprendere i segreti del barbaro per 250 punti donazione?`n");
    addnav("S?Si","lodge.php?op=barbariansir");
    addnav("N?No","lodge.php");
}elseif ($_GET['op']=="barbariansir"){
    addnav("T?Torna al Capanno","lodge.php");
    if($pointsavailable >= 250){
        output("`3J. C. Petersen sorride, \"`\$Il tuo corpo porterà a termine la trasformazione al prossimo Nuovo Giorno ... Ti consiglio di riposare.`3\"");
        $session[user][specialty]=0;
        $session['user']['donationspent'] +=250;
        donazioni_usi_up('barbarian',1);
        debuglog(" ha rinnovato barbarian per 250 PD.");
    }else{
        output("Non hai abbastanza punti donazione !");
    }
}
//riverificare quando abbiamo anche l'anno
if($_GET['op']=='voti'){
    $sql = "SELECT COUNT( approvato ) AS totale,approvato,controllato,week FROM votazioni GROUP BY week ORDER BY week DESC ";
    $result = db_query($sql) or die(db_error(LINK));

    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td align='center'>`bSettimana`b</td><td align='center'>`bVoti`b</td><td align='center'>`bTuoi Voti`b</td><td align='center'>`bApprovato`b</td><td align='center'>`bControllato`b</td></tr>", true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $verifica = db_fetch_assoc($result);
        $sql = "SELECT COUNT( approvato ) AS totale FROM votazioni WHERE nome='".$session['user']['login']."' AND week='".$verifica[week]."'";
        $resultv = db_query($sql) or die(db_error(LINK));
        $tuoi = db_fetch_assoc($resultv);
        output("<tr align=center class='" . ($i % 2?"trlight":"trdark") . "'><td>$verifica[week]</td><td>$verifica[totale]</td><td>$tuoi[totale]</td><td>$verifica[approvato]</td><td>$verifica[controllato]</td></tr>", true);
    }
    output("</table>", true);
    output("`n`n`7Ogni voto da diritto a 1 punto donazione, che però devono essere approvati dagli Admin!`n");
    output("Per essere considerata valida, la settimana deve soddisfare i seguenti requisiti:`nIl totale voti della settimana deve essere al massimo inferiore del 10 % rispetto al totale della settimana che trovate nella classifica su Migliorsito.`n");

    addnav("T?Torna al Capanno","lodge.php");

}
if($_GET['op']=='verifica'){
    $sql = "SELECT COUNT( approvato ) AS totale,week FROM votazioni WHERE controllato = 'no' GROUP BY week ORDER BY week DESC ";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead'><td colspan=2 align='center'>`bSettimana`b</td><td align='center'>`bVoti`b</td><td align='center'>`bApprova`b</td><td align='center'>`bNon approvato`b</td></tr>", true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $verifica = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>" . ($i + 1) . ".</td><td>$verifica[week]</td><td allign=center>$verifica[totale]</td><td><A href=lodge.php?op=approva&week=$verifica[week]>Approva</a></td><td><A href=lodge.php?op=nonapprova&week=$verifica[week]>Non approvare</a></td></tr>", true);
        addnav("","lodge.php?op=approva&week=$verifica[week]");
        addnav("","lodge.php?op=nonapprova&week=$verifica[week]");
    }
    output("</table>", true);
    addnav("T?Torna al Capanno","lodge.php");

}
if($_GET['op']=='approva'){
    $sql = "SELECT * FROM votazioni WHERE week = '".$_GET[week]."' ";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $sqlu = "UPDATE votazioni SET approvato='si',controllato='si' WHERE week='".$_GET[week]."'";
        db_query($sqlu) or die(db_error(LINK));
    }
    output("Fatto !");
    addnav("T?Torna al Capanno","lodge.php");
}if($_GET['op']=='nonapprova'){
    $sql = "SELECT * FROM votazioni WHERE week = '".$_GET[week]."' ";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $sqlu = "UPDATE votazioni SET approvato='no',controllato='si' WHERE week='".$_GET[week]."'";
        db_query($sqlu) or die(db_error(LINK));
    }
    output("Fatto !");
    addnav("T?Torna al Capanno","lodge.php");
}
if ($_GET['op']=="acquistati"){
    $sql = "SELECT * FROM donazioni WHERE idplayer= '".$session['user']['acctid']."'";
    $result = db_query($sql);
    output("<table border='0' cellpadding='5' cellspacing='0'>",true);
    output("<tr><td>Nome</td><td>Tipo</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $sql = "SELECT acctid,name,donation,donationspent FROM accounts WHERE acctid='".$row['idplayer']."'";
        $resulta = db_query($sql);
        $rowa = db_fetch_assoc($resulta);

        output("<tr class='".($i%2?"trlight":"trdark")."'>",true);
        //output("`V{$rowa['acctid']}`0",true);
        //output("</td><td>`^{$rowa['name']}`0",true);
        output("<td>`#".$row['nome']."`0</td>",true);
        //output("<td>`@".$row['usi']."`0</td>",true);
        output("<td>`%".$row['tipo']."`0</td>",true);
        output("</tr>",true);

    }
    output("</table>",true);
    output("`n`# Legenda:`n");
    output("`%T`7 = Temporaneo, dura un periodo di tempo limitato in genere giorni di gioco e contano solo quelli giocati!`n");
    output("`%P`7 = Permanente, dura per sempre!`n");
    output("`%R`7 = Reincarnazione, dura fino a quando non vi reincarnate!`n");
    output("`\$*`7 = Rinnovabile, non dovete ricomprarlo ma rinnovarlo in genere al 50% del valore se non specificato diversamente!`n");

    addnav("C?Capanno","lodge.php");
}
if ($_GET['op']=="gdr"){
    if ($session['user']['superuser'] > 2){
        output("`@Scrivi data scadenza torneo GDR`n");
        output("<form action='lodge.php?op1=data' method='POST'><input name='data' value='".getsetting("datagdr","0")."'><input type='submit' class='button' value='Data Scadenza Torneo'></form>`n",true);
        addnav("","lodge.php?op1=data");
    }
    output("`&Concorso GDR !`n");
    output("`%Questo concorso consiste nello scrivere un racconto, il racconto `\$DEVE`% narrare le gesta del vostro personaggio, oppure `\$DEVE`% essere ambientato nel mondo di Legend of the Green Dragon. `n( Esempio: la leggenda del cavaliere nero, la storia di Golinda, le avventure di Heimdall ecc. ecc.)`n");
    output("`%Il brano non deve superare le `\$2000 `%parole e verrà letto dagli admin e a loro insindacabile giudizio valutato.`n");
    output("`%Tutti i brani arrivati entro il `6".getsetting("datagdr","30/11/2007")." `%parteciperanno alla premiazione che avverrà entro pochi giorni dalla scadenza.`n");
    output("`%Il player che ha inviato il brano primo classificato riceverà 50 punti donazione 50.000 punti fama e accesso ad una razza speciale unica.`n");
    output("`%Il player che ha inviato il brano secondo classificato riceverà 25.000 punti fama e il golinda pass.`n");
    output("`%Il player che ha inviato il brano terzo classificato riceverà 10.000 punti fama e una bottiglia di polvere di fata.`n");
    output("`%Inoltre i tre brani primi classificati saranno pubblicati nella biblioteca, accessibili a tutti i player.`n");
    output("`#Ogni player può inviare 1 brano solo, quindi se possedete più PG inviatelo con quello a cui volete vengano assegnati i premi in caso di vittoria!.`n");
    output("`\$I brani non attinenti al mondo di Legend of the Green Dragon non parteciperanno al contest!");
    addnav("I?Invia brano","lodge.php?op=invia_gdr");
    addnav("C?Capanno","lodge.php");
}
if ($_GET['op']=="invia_gdr"){
    output("`%Concorso trimestrale GDR !`n");
    output("`#Invia il tuo brano !`n");
    $sql = "SELECT * FROM gdr_contest WHERE voto='0' AND acctid='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(sql_error($sql));
    if(db_num_rows($result)==0){
        output("<form action='lodge.php?op=ricevi_gdr' method='POST'>",true);
        output("`bBrano:`b`nTitolo: <input name='titolo'>`n<textarea name=brano rows=15 cols=70></textarea>`n<input type='submit' class='button' value='Invia'>",true);
        output("</form>",true);
        addnav("C?Capanno","lodge.php");
        addnav("","lodge.php?op=ricevi_gdr");
    }else{
        output("`\$Hai già inviato un brano per questo concorso !`n");
        addnav("C?Capanno","lodge.php");
    }
}
if ($_GET['op']=="ricevi_gdr"){
    output("`%Grazie per aver inviato il tuo brano!`n");
    $sqldr="INSERT INTO gdr_contest (nome,acctid,data,testo,titolo) VALUES ('".$session['user']['login']."','".$session['user']['acctid']."',FROM_UNIXTIME(UNIX_TIMESTAMP()),'".$_POST['brano']."','".$_POST['titolo']."')";
    db_query($sqldr);
    report(2,"`8Nuovo Brano GDR!`0","`&".$session['user']['login']."`2 ha inviato un nuovo brano GDR, intitolato: ".$_POST['titolo']."`2. Ricordati di visionarlo e votarlo!","branoGDR");
    addnav("C?Capanno","lodge.php");
}
page_footer();
?>