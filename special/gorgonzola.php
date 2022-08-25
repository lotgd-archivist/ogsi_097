<?php
/*
Poker's Quest - Forest Special
By Excalibur (www.ogsi.it) and Poker (www.ogsi.it)
version 0.9 june 28, 2004
Most recent copy available at http://dragonprime.cawsquad.net
Translated in italian by Excalibur www.ogsi.it/logd
*/

if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`n`n`2Stai nervosamente aggirandoti per la foresta alla ricerca di qualche avversario degno della tua fama
    quando in una radura poco distante noti una casa che emette un odore nauseabondo, dal cui comignolo esce un rivolo
    di fumo. Ti avvicini e ti rendi conto che la casa è fatta di gorgonzola, della specie con i vermi !!`n Cosa decidi
    di fare ?`n");
    addnav("Bussa alla Porta","forest.php?op=bussa");
    addnav("Osserva dalla Finestra","forest.php?op=osserva");
    addnav("Prosegui (Il Gorgonzola non l'hai mai digerito)","forest.php?op=esci");
    $session['user']['specialinc']="gorgonzola.php";
}else if ($_GET['op']=="bussa"){
    output("`n`n`%La porta si apre e nella penombra noti un cavaliere dall'aspetto fiero e con le gote colorite che ti
    fa entrare. All'interno c'è una tavola imbandita dove 7 commensali ti salutano calorosamente gridando
    \"`@DRAGO !!`%\" ed invitandoti ad unirti a loro. Stranamente i posti a tavola sono 8, come se il tuo arrivo fosse
    previsto ed atteso.`nCosa fai ?`n");
    addnav("Accetta l'invito (sei affamato)","forest.php?op=accetto");
    addnav("Declina l'invito (mai mangiare con gli sconosciuti)","forest.php?op=declino");
    addnav("Gridi DRAGO !!","forest.php?op=drago");
    $session['user']['specialinc']="gorgonzola.php";
}else if ($_GET['op']=="accetto"){
    output("`%Non appena ti accomodi al desco imbandito scopri che tra i commensali siede anche il malvagio `\$Poker`%
    e un brivido scorre lungo la tua spina dorsale ... tra le mani ha una strana pietra con cui giocherella nervosamente.
    Vedendoti agitato ti propone un gioco a cui non puoi sottrarti, in palio c'è la tua vita !!");
    addnav("Accetta di Giocare","forest.php?op=gioca");
    addnav("Rifiuta di Giocare","forest.php?op=nongioca");
    $session['user']['specialinc']="gorgonzola.php";
}else if ($_GET['op']=="gioca" OR $_GET['op']=="nongioca"){
    if ($_GET['op']=="nongioca") output("`%Non puoi sottrarti al gioco di Poker !!!`n");
    output("`%Poker ti da in mano un dado e tu, ancora intimorito dalla sua fama e non riuscendo a biascicare una sola parola,
    ti ritrovi costretto a tirare il dado !!`n Dopo aver rotolato sulla tavola il dado si ferma e ... ");
    switch (e_rand(0,6)){
        case 0:
        output("`b`#INCREDIBILE !!!`b `%`n`nIl dado rimane in bilico su uno spigolo !!! Una cosa del genere non era mai successa !!!
        È un segno del destino, e Poker, il cui secondo nome è `^\"L'interprete dei segni del Destino\"`% sa perfettamente cosa
        deve fare in una situazione del genere. Compie un gesto scaramantico e infilando le dita nel boccale di birra davanti
        a se estrae una `\$Pietra`% ... la `bSUA`b pietra, e prima che tu possa dire o fare qualcosa te la infila nella tasca.`n
        Sei diventato lo sfortunato possessore della $pietre[1] !!!!");
        debuglog("riceve la Pietra di Poker direttamente dal suo creatore");
        $flagstone = 0;
        $id=$session['user']['acctid'];
        $sqlzk="SELECT * FROM pietre WHERE owner=$id";
        $resultzk = db_query($sqlzk) or die(db_error(LINK));
        if (db_num_rows($resultzk) != 0){
           $pot = db_fetch_assoc($resultzk);
           $flagstone=$pot['pietra'];
        }
        if ($flagstone > 0){
           $sqlk = "DELETE FROM pietre WHERE pietra = '$flagstone' AND owner = '$id'";
           $resultk = db_query($sqlk) or die(db_error(LINK));
           output("`n`n`%Purtroppo, non potendo avere più di una pietra, la precedente $pietre[$flagstone] `%ti viene tolta !!!`n`n");
           debuglog("`#gli viene tolta la $pietre[$flagstone]`#, non potendo avere più di una pietra alla volta`0");
        }
        $sqlr="SELECT pietra,owner FROM pietre WHERE pietra = '1'";
        $resultr = db_query($sqlr) or die(db_error(LINK));
        $rowr = db_fetch_assoc($resultr);
        if (db_num_rows($resultr) == 0) {
            $sqlp="INSERT INTO pietre (pietra,owner) VALUES ('1','$id')";
            db_query($sqlp);
        }else{
            $account=$rowr['owner'];
            $sqlpr="UPDATE pietre SET owner = '$id' WHERE pietra = '1'";
            db_query($sqlpr);
            $mailmessage = "`^{$session['user']['name']} `@ha incontrato `&Poker`@ che ha pensato bene di dare a lui la tua  {$pietre[1]} `@!! È il tuo giorno fortunato.";
            systemmail($account,"`2La tua pietra è ora nella mani di `@{$session['user']['name']} `2",$mailmessage);
        }
        $session['user']['turns']-=1;
        addnav("Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
        break;
        case 1:
        output("`% mostra sulla faccia superiore il numero `^1`%. Non è un buon segno. Poker osserva
        la cifra sul dado e dice \"`@Sono spiacente per te car" . ($session['user']['sex']?"a":"o") . "
        {$session['user']['name']}, ma con questo risultato perdi 2 combattimenti`nScornato e con le pive nel sacco
        ti allontani dalla casa.");
        $session['user']['turns']-=2;
        if ($session['user']['turns'] < 0) $session['user']['turns']=0;
        debuglog("perde 2 turni foresta con Poker");
        addnav("Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
        break;
        case 2:
        output("`% mostra sulla faccia superiore il numero `^2`%. Non è un buon segno. Poker osserva
        la cifra sul dado e dice \"`@Sono spiacente per te car".($session['user']['sex']?"a":"o")." {$session['user']['name']}, ma
        con questo risultato perdi 1 HP permanente !!\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nScornato e con le pive nel sacco ti allontani dalla casa.");
        $session['user']['maxhitpoints']-=1;
        $session['user']['hitpoints']-=1;
        debuglog("perde 1 HP permanente da Poker");
        addnav("Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
        break;
        case 3:
        output("`% mostra sulla faccia superiore il numero `^3`%. Chissà se è un bene. Poker osserva
        la cifra sul dado e dice \"`@Mhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." {$session['user']['name']},
        con questo risultato guadagni 1 HP permanente !!\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani dalla casa.");
        $session['user']['maxhitpoints']+=1;
        $session['user']['hitpoints']+=1;
        debuglog("guadagna 1 HP permanente da Poker");
        addnav("Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
        break;
        case 4:
        output("`% mostra sulla faccia superiore il numero `^4`%. Chissà se è un bene. Poker osserva
        la cifra sul dado e dice \"`@Mhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." {$session['user']['name']},
        con questo risultato guadagni 3 combattimenti in foresta !!\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani dalla casa.");
        $session['user']['turns']+=3;
        debuglog("guadagna 3 turni foresta da Poker");
        addnav("Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
        break;
        case 5:
        $oro=round(e_rand(100,200)*$session['user']['level']);
        output("`% mostra sulla faccia superiore il numero `^5`%. Chissà se è un bene. Poker osserva
        la cifra sul dado e dice \"`@Mhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." {$session['user']['name']},
        con questo risultato guadagni $oro pezzi d'oro !!\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani dalla casa.");
        $session['user']['gold']+=$oro;
        debuglog("guadagna $oro oro da Poker");
        addnav("Torna alla Foresta","forest.php");
        $session['user']['specialinc']="";
        break;
        case 6:
        $gemme=e_rand(1,3);
        output("`% mostra sulla faccia superiore il numero `^6`%. Chissà se è un bene. Poker osserva
        la cifra sul dado e dice \"`@Mhhhhhh ... buon per te car".($session['user']['sex']?"a":"o")." {$session['user']['name']},
        con questo risultato guadagni $gemme gemme !!\"`%. Detto ciò ti congeda e ti invita a riprendere la tua
        strada. `nFelice per il premio ricevuto intoni canti di gioia e ti allontani dalla casa.");
        debuglog("guadagna $gemme gemme da Poker");
        addnav("Torna alla Foresta","forest.php");
        $session['user']['gems']+=$gemme;
        $session['user']['specialinc']="";
        break;
        }
}else if ($_GET['op']=="declino"){
      output("`%In maniera rispettosa declini l'invito ritenendo la combriccola della casa alquanto squinternata, e fai
      per voltarti e tornare alla foresta quando il cavaliere pronunciando alcune strane parole ti blocca ogni movimento.
      Incapace di muovere un solo muscolo del tuo corpo inizi a sentire la tua mente oscurarsi e dopo qualche istante cadi
      in stato di incoscienza.`n");
      $hppersi=$session['user']['maxhitpoints']/2;
      $session['user']['hitpoints']-=$hppersi;
      if ($session['user']['hitpoints'] > 0){
         output("`%Ti risvegli nella foresta con il fisico debilitato, hai perso $hppersi HP e una visita dal guaritore è
         la cosa migliore che tu possa fare.`nTi riproponi di tentare un approccio differente la prossima volta che
         incontrerai questa gente, forse sei stato scortese a non accettare il loro invito.");
         debuglog("perde $hppersi da Poker");
         addnav("Torna alla Foresta","forest.php");
         $session['user']['specialinc']="";
      }else{
         output("`%La maledizione lanciata dal misterioso cavaliere ha causato la tua morte !!`n
         Perdi tutto l'oro che avevi con te e il 10% della tua esperienza !!");
         $oroperso = $session['user']['gold'];
         $session['user']['gold']=0;
         $session['user']['experience']*=0.9;
         $session['user']['hitpoints']=0;
         $session['user']['alive']=false;
         $session['user']['specialinc']="";
         debuglog("muore, perde $oroperso oro, il 10% exp da Poker");
         addnav("Notizie Giornaliere","news.php");
      }
}else if ($_GET['op']=="drago"){
output("`\$Subito dopo aver pronunciato ad alta voce la parola `@DRAGO`\$ senti alle tue spalle un vento caldo e
qualcuno o qualcosa che ti batte sulla spalla. L'invito a girarti è esplicito, ma il terrore ti ha come bloccato e
quando finalmente fai dietro-front sai già chi troverai.`n`n Il `b`!Drago Blu`b `\$ che ti si pare davanti è di
dimensioni ridotte rispetto agli standard dei draghi, ma è pur sempre alto il doppio di te, e le sue fauci non
promettono nulla di buono.`n`nTi prepari quindi allo scontro conscio di avere pochissime speranze di sopravvivere ...");
            $badguy = array(
                "creaturename"=>"`!Drago Blu`0",
                "creaturelevel"=>$session[user][level]+2,
                "creatureweapon"=>"`^Fiammata Ustionante`0",
                "creatureattack"=>$session['user']['attack']+5,
                "creaturedefense"=>$session['user']['defence']+5,
                "creaturehealth"=>round($session['user']['maxhitpoints']*1.5,0),
                "diddamage"=>0);
            $session['user']['badguy']=createstring($badguy);
            $session['user']['specialinc']="gorgonzola.php";
            $_GET['op']="fight";

}else if ($_GET['op']=="osserva"){
    switch (e_rand(1,3)){
    case 1:
        output("`@Ti avvicini alla finestra per osservare l'interno della strana casa, ma l'interno è in penombra e
        noti solo alcune figure sedute attorno ad un tavolo. Dopo qualche istante la tua vista si adatta all'oscurità
        e ti rendi conto che sono alcuni cavalieri del villaggio che stanno facendo baldoria. La finestra è socchiusa
        e sul davanzale noti una borsellino gonfio. La tentazione è forte ... cosa fai ?");
        addnav("Prendi il Borsellino","forest.php?op=ruba");
        addnav("Lascialo e bussa alla porta","forest.php?op=bussa");
        $session['user']['specialinc']="gorgonzola.php";
    break;
    case 2:
        output("`#Mentre cerchi di capire chi c'è all'interno della casa, il nauseabondo tanfo emanato dal gorgonzola
        di cui è fatta la casa inizia a fare effetto. Lucine colorate ti appaiono davanti agli occhi, e dopo qualche
        istante l'oscurità ti ghermisce. `n`n Quando ti risvegli scopri di trovarti all'interno della casa, circondato
        da 7 cavalieri che ti scrutano con fare curioso. Uno di loro, quello che sembra essere il più simpatico, ti
        propone di unirti alla tavolata e cenare con loro. `nCosa fai ?");
        addnav("Accetta l'invito (sei affamato)","forest.php?op=accetto");
        addnav("Declina l'invito (mai mangiare con gli sconosciuti)","forest.php?op=declino");
        $session['user']['specialinc']="gorgonzola.php";
    break;
    case 3:
        output("`#Mentre cerchi di capire chi c'è all'interno della casa, il nauseabondo tanfo emanato dal gorgonzola
        di cui è fatta la casa inizia a fare effetto. Lucine colorate ti appaiono davanti agli occhi, e dopo qualche
        istante l'oscurità ti ghermisce. `n`n Ti risvegli adagiato ad un albero della foresta, e senti qualcosa di
        duro nella tasca posteriore. Infili la mano e scopri con orrore che è la $pietre[1] `#!!! `n Nello stesso istante
        una risata satanica echeggia nella foresta \"`\$Mai amato i curiosi ! AH AH AH`#\".`n`n");
        output("Purtroppo per te, il possesso di tale pietra comporta la perdita di 1 turno di combattimento al giorno.
        Tale evento infausto proseguirà fino a che un altro esploratore altrettanto sfortunato cadrà nella trappola che
        oggi ha `ipescato`i te. Consolati pensando che non hai perso ne oro ne gemme, e che i tuoi HP sono stati
        riportati al massimo.");
        if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) {
           $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
        }
        $flagstone = 0;
        $id=$session['user']['acctid'];
        $sqlzk="SELECT * FROM pietre WHERE owner=$id";
        $resultzk = db_query($sqlzk) or die(db_error(LINK));
        if (db_num_rows($resultzk) != 0){
           $pot = db_fetch_assoc($resultzk);
           $flagstone=$pot['pietra'];
        }
        if ($flagstone > 0){
           $sqlk = "DELETE FROM pietre WHERE pietra = '$flagstone' AND owner = '$id'";
           $resultk = db_query($sqlk) or die(db_error(LINK));
        }
        $sqlr="SELECT pietra,owner FROM pietre WHERE pietra = '1'";
        $resultr = db_query($sqlr) or die(db_error(LINK));
        $rowr = db_fetch_assoc($resultr);
        if (db_num_rows($resultr) == 0) {
            $sqlp="INSERT INTO pietre (pietra,owner) VALUES ('1','$id')";
            db_query($sqlp);
        }else{
            $account=$rowr['owner'];
            $sqlpr="UPDATE pietre SET owner = '$id' WHERE pietra = '1'";
            db_query($sqlpr);
            $mailmessage = "`^{$session['user']['name']} `@ha incontrato `&Poker`@ che ha pensato bene di dare a lui la tua  {$pietre[1]} `@!! È il tuo giorno fortunato.";
            systemmail($account,"`2La tua pietra è ora nella mani di `@{$session['user']['name']} `2",$mailmessage);
        }
        $session['user']['specialinc']="";
    break;
    }
}else if ($_GET[op]=="ruba"){
    switch(e_rand(1,3)){
    case 1: case 2:
    output("`%La tentazione è troppo forte, e dopo esserti sincerato che nessuno degli occupanti della casa ti abbia
    notato, allunghi la mano e ti impossessi del borsellino. Con il cuore che ti batte in gola ti allontani velocemente
    dalla casa, e quando pensi di aver messo una ragionevole distanza tra te e la strana costruzione ti fermi e apri
    il borsellino.`n`n`@ Al suo interno trovi ");
    $goldgain=$session['user']['level']*e_rand(50,100);
    $gemgain=e_rand(2,4);
    $session['user']['gold']+=$goldgain;
    $session['user']['gems']+=$gemgain;
    output("`^`b$goldgain`b`@ pezzi d'oro e `&`b$gemgain`b`@ gemme !!!`n`n");
    output("Dopo un simile gesto, di una viltà inaudita, inizi a sentirti in colpa, ma il peso delle gemme e dell'oro
    nelle tue tasche hanno la meglio sulla tua coscienza ed un sorriso maligno si dipinge sulle tue labbra. `n
    Anche tu sei entrato a far parte della cerchia dei ricercati, la tua cattiveria ha subito
    un'impennata, meglio non farsi beccare dallo sceriffo.");
    $session['user']['evil']+=100;
    $session['user']['specialinc']="";
    debuglog("ruba il borsellino con $goldgain oro, $gemgain gemme, ma riceve 100 punti cattiveria da Poker");
    addnav("Torna alla Foresta","forest.php");
    break;
    case 3:
    output("`%La tentazione è troppo forte, e dopo esserti sincerato che nessuno degli occupanti della casa ti abbia
    notato, allunghi la mano e ti impossessi del borsellino. `nCon il cuore che ti batte in gola ti allontani velocemente
    dalla casa, ma i suoi occupanti si sono accorti del furto e ti hanno seguito !! In un batter d'occhio di hanno
    circondato, ed essendo in superiorità ti sopraffaggono. `nDopo aver infierito in maniera brutale, ti abbandonano in
    fin di vita, senza un solo pezzo d'oro in tasca ");
    $oroperso = $session['user']['gold'];
    $session['user']['gold']=0;
    $session['user']['hitpoints']=1;
    $gem_loss = rand(1,4);
    $testo = "perde $oroperso oro e resta con 1 HP da Poker";
    if ($session['user']['gems'] != 0){
    if ($gem_loss >= $session['user']['gems']) {
        output("e ti sottraggono `bTUTTE`b le tue gemme !`n`n");
        $gem_loss = $session['user']['gems'];
        $session['user']['gems']=0;
    } else {
        output("e ti sottraggono `b$gem_loss`b delle tue preziose gemme !`n`n");
        $session['user']['gems']-=$gem_loss;
    }
    debuglog("$testo e perde anche $gem_loss");
    }else {
        output("e con la cattiveria per il tuo vile, seppur non concluso, gesto aumentata. Meglio non farsi trovare
        dallo sceriffo nei prossimi giorni.");
        debuglog("$testo e guadagna 100 punti cattiveria");
        $session['user']['evil']+=100;
    }
    $session['user']['specialinc']="";
    addnav("Torna alla Foresta","forest.php");
    break;
}
}else if ($_GET[op]=="esci"){
      output("`3Seppur cosciente della grande opportunità che ti è stata data dalla Dea Bendata, decidi di non avvicinarti
      alla casa. Mentre ti stai allontanando senti un brivido gelido percorrere la tua schiena, ed una voce riecheggia
      nella foresta \"`%Pensavi forse di allontanarti indisturbat".($session['user']['sex']?"a":"o")."
      {$session['user']['name']} ?`3\". Nel medesimo istante senti materializzarsi qualcosa nella tua tasca: è la
      {$pietre[1]} `3!!! `n`n Per la tua immensa codardia perdi anche 2 turni di combattimento !!");
        $flagstone = 0;
        $id=$session['user']['acctid'];
        $sqlzk="SELECT * FROM pietre WHERE owner=$id";
        $resultzk = db_query($sqlzk) or die(db_error(LINK));
        if (db_num_rows($resultzk) != 0){
           $pot = db_fetch_assoc($resultzk);
           $flagstone=$pot['pietra'];
        }
        if ($flagstone > 0){
           $sqlk = "DELETE FROM pietre WHERE pietra = '$flagstone' AND owner = '$id'";
           $resultk = db_query($sqlk) or die(db_error(LINK));
        }
        $sqlr="SELECT pietra,owner FROM pietre WHERE pietra = '1'";
        $resultr = db_query($sqlr) or die(db_error(LINK));
        $rowr = db_fetch_assoc($resultr);
        if (db_num_rows($resultr) == 0) {
            $sqlp="INSERT INTO pietre (pietra,owner) VALUES ('1','$id')";
            db_query($sqlp);
        }else{
            $account=$rowr['owner'];
            $sqlpr="UPDATE pietre SET owner = '$id' WHERE pietra = '1'";
            db_query($sqlpr);
            $mailmessage = "`^{$session['user']['name']} `@ha incontrato `&Poker`@ che ha pensato bene di dare a lui la tua  {$pietre[1]} `@!! È il tuo giorno fortunato.";
            systemmail($account,"`2La tua pietra è ora nella mani di `@{$session['user']['name']} `2",$mailmessage);
        }
      $session['user']['turns']-=2;
      $session['user']['specialinc']="";
      addnews("`^".$session['user']['name']." `#ha sfidato la sorte e si ritrova con la {$pietre[1]}`# in tasca !!!");
      debuglog("si becca la Pietra di Poker e perde due turni foresta rinunciando all'evento di Poker");
      addnav("Torna alla Foresta","forest.php");
}
if ($_GET[op]=="run"){
    if (e_rand()%5 == 0){
        output ("`n`n`c`b`&Sei riuscito a sfuggire alle fiammate del `!Drago BLu!`0`b`c`n");
        $_GET[op]="";
    }else{
        output("`n`n`c`b`\$Non sei riuscito a sfuggire al `!Drago BLu!`0`b`c");
        $_GET[op]="fight";
    }
}
if ($_GET['op']=="fight"){
    $battle=true;
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="gorgonzola.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            $exp_gain=$session['user']['level']*150;
            $gem_gain = rand(1,4);
            $gold_gain = rand(300,400)*$session['user']['level'];
            output("`n`3Dopo un duro scontro, sei riuscito ad avere la meglio sul `!Drago Blu`3.`n");
            output("`^Guadagni $exp_gain punti esperienza per la battaglia disputata.`n`n");
            output("All'interno del ventre del drago trovi anche $gem_gain gemme e $gold_gain pezzi d'oro !!!");
            debuglog("batte il Drago Blu e guadagna $exp_gain exp, $gem_gain gemme e $gold_gain oro da Poker");
            $session['user']['gold']+=$gold_gain;
            $session['user']['gems']+=$gem_gain;
            $session['user']['experience']+=$exp_gain;
            $session['user']['specialinc']="";
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']="";
            $testo="è stato ucciso dal Drago Blu";
            output("`n`7Sei stato battuto dal `!Drago Blu !!`n`n`7Perdi il `^`b10%`b`7 della tua esperienza. ");
            output("`&Inoltre, ");
            $gem_loss = rand(1,4);
            if ($gem_loss >= $session['user']['gems']) {
                output("`&hai perso `bTUTTE`b le tue gemme nella battaglia, assieme a tutto il tuo oro!`n`n");
                $testo=$testo.", perde {$session['user']['gems']} gemme";
                $session['user']['gems']=0;
            } else {
                output("hai perso `b$gem_loss`b delle tue gemme nello scontro, assieme a tutto il tuo oro!`n`n");
                $testo=$testo.", perde $gem_loss gemme";
                $session['user']['gems']-=$gem_loss;
            }
            addnav("`^Notizie Giornaliere","news.php");
            $gold_loss = $session['user']['gold'];
            debuglog("$testo, $gold_loss oro e il 10% di exp nell'evento di Poker");
            $session['user']['alive']=false;
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            $session['user']['specialinc']="";
            addnews("`@".$session['user']['name']." `3ha incontrato il `!Drago Blu `3e ne è uscito cotto in salmì !!!");
        } else {
            fightnav(true,true);
        }
}
?>