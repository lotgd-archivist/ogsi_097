<?php
/************************************************************
LE POZIONI DELL'ALCHIMISTA
autore  :   chumkiu
data    :   17/9/2005
versione:   0.9
commenti:   Un evento tutto sommato positivo! Il più delle volte non provoca danni
        e comunque non si rischia la morte. I reincarnati, a seconda del numero di reincarnazioni,
        sono leggermente più sfavoriti nell'evento della pozione nera
        dove i non reincarnati trovano punti attacco e difesa permanenti!
        Nel caso sfavorevole per i reincarnati gli si azzera attacco e difesa per pochi turni!
        E' vero che i non-reincarnati avrebbero sicuramente benefici dalla nera,
        ma è anche vero che la nera è difficile che ci sia(1 su 15), quindi mi sembra giusto così!
        (poi fate vobis)
        La nera dà benefici solo nel caso in cui è mischiata con tutto!
        (e povero il pollo che proverà tutte le combinazioni :) )
        Gli elfi non hanno benefici dalla pozione viola, che dà lo stesso premio di
        lonestrider quando canta la sua canzone della foresta!

        Decommentate le stringhe che aumentano i punti permanenti
        Io in locale non le ho.
************************************************************** */

// ES sta per Evento Speciale, così non corro rischi di utlizzare variabili gia' esistenti :)
page_header("Le pozioni");
output("`n`n`c`b`@<font size=\"+1\" color=\"#aa00aa\">Le Pozioni</font>`0`b`c`n`n", true);
// numero di pozioni che troverà nella borsetta
$ndp_ES= e_rand(1,3);
shuffle($pozione_ES= array("rosso", "blu", "giallo"));
if($_GET['op']=="") {
$session['user']['specialinc']="alchimista.php";
output("`n`@Mentre girovaghi per la foresta, trovi sotto un albero una borsa di cuoio!`n");
output("Ti guardi intorno e pare non ci sia nessuno, ti avvicini ad essa e frettolosamente la apri.`n");
output("Trovi `b" . $ndp_ES . " boccett" . (($ndp_ES==1)? "a di colore " . colora_ES($pozione_ES[0]) . "`b. Ce ne sono altre ma sembrano vuote"  : "e`b:`n`n"));
addnav("Bevi quella di colore " . colora_ES($pozione_ES[0]) . "", "forest.php?op=$pozione_ES[0]");
    if($ndp_ES>1) {
    output("una di colore " . colora_ES($pozione_ES[0]) . "`n");
    output("una di colore " . colora_ES($pozione_ES[1]) . "`n");
    addnav("Bevi quella di colore " . colora_ES($pozione_ES[1]) . "", "forest.php?op=$pozione_ES[1]");
        if($ndp_ES==3) {
        output("una di colore " . colora_ES($pozione_ES[2]) . "`n");
        addnav("Bevi quella di colore " . colora_ES($pozione_ES[2]) . "", "forest.php?op=$pozione_ES[2]");
            if(e_rand(1,10)<=2) {
            $nero_ES= true;
            output("Trovi inoltre una pozione `9nera`@.`n`n");
            addnav("Bevi quella `9nera", "forest.php?op=nero");
            addnav("Mischia `9nero`0 e " . colora_ES($pozione_ES[0]) . "", "forest.php?op=nero$pozione_ES[0]");
            addnav("Mischia `9nero`0 e " . colora_ES($pozione_ES[1]) . "", "forest.php?op=nero$pozione_ES[1]");
            addnav("Mischia `9nero`0 e " . colora_ES($pozione_ES[2]) . "", "forest.php?op=nero$pozione_ES[2]");
            }
        addnav("Mischia " . colora_ES($pozione_ES[2]) . " e " . colora_ES($pozione_ES[1]) . "", "forest.php?op=$pozione_ES[2]" . "$pozione_ES[1]");
        addnav("Mischia " . colora_ES($pozione_ES[2]) . " e " . colora_ES($pozione_ES[0]) . "", "forest.php?op=$pozione_ES[2]" . "$pozione_ES[0]");
        }
    addnav("Mischia " . colora_ES($pozione_ES[1]) . " e " . colora_ES($pozione_ES[0]) . "", "forest.php?op=$pozione_ES[1]" . "$pozione_ES[0]");
    if($ndp_ES==3) addnav("Mischia `@T`^u`#t`0t`\$o", "forest.php?op=tutt" . ((!$nero_ES)? "e" : "o"));
    }

addnav("Lascia perdere", "forest.php?op=annulla");

output("`n`nProbabilmente era una borsetta di un alchimista, o di una strega... ma non importa!`n");
output("Ciò che importa è che il suo contenuto potrebbe essere molto interessante!!`n");
output("Certo può essere pericoloso giocare al piccolo alchimista... Vuoi rischiare?");
}

else if($_GET['op']!="") {
$session['user']['specialinc']="";

    if($_GET['op']=="rosso") {
    output("`@Dopo averci pensato attentamente prendi la boccetta `b`\$Rossa`b`@, chiudi gli occhi e la bevi d'un fiato`n");
    output("Dopo che l'ultima goccia ti scivola in gola riapri gli occhi e ti rendi conto che`n");
        $caso = e_rand(1,10);
        if($caso<=5) {
            output("riesci a comprendere alcuni segreti delle `b`\$Arti Oscure`b `@! Guadagni un punto di utilizzo.");
            /* ********** PUNTO PERMANENTE NELLE ARTI OSCURE **************** */
            $session['user']['darkarts']+=3;
            $session['user']['darkartuses']+=1;
        }
        elseif ($caso>5 AND $caso<9){
            output("la tua vista diventa più acuta e tra il fogliame della foresta trovi`b `&una gemma`b!");
            /* ************ UNA GEMMA ************** */
            $session['user']['gems']++;
        }else {
            output("le budella iniziano a fare una strana danza ... ti si torcono provocandoti dolori lancinanti!!!`n");
            output("Quando finalmente il dolore si attenua ti rendi conto che hai perso un HP `b`ipermanente`b`i!!!");
            $session['user']['maxhitpoints']--;
            $session['user']['hitpoints']--;
        }
    }

    else if($_GET['op']=="giallo") {
    /* uso lo spazio specialmisc per memorizzare che ha già avuto esperienza!
    So che non rimane per sempre, almeno finché non incontra un evento che lo usa
    (per esempio il vecchio che gioca a "indovina il numero"), ma credo che sia carino
    lo stesso :) */
        if($session['user']['specialmisc']=="pozione gialla") {
        output("`0Hai già imparato che il `^Giallo`0 non è sempre il colore dell'`^oro`0!`n");
        output("Non scopri nulla di nuovo");
        }
        else {
        /* ****************GUADAGNO ESPERIENZA ********************* */
        $gainxp_ES= round($session['user']['level']*10*e_rand(3,7));
        output("`@Senza indugio prendi la boccetta `^Gialla`@, probabilmente perché è il colore dell'`^ORO `@ che ti attrae!`n");
        output("Forse ti aspetti di arricchirti facilmente dopo aver bevuto questa pozione!`n");
        output("Ora hai imparato che non sempre il giallo ha a che fare con l'`^oro!`n");
        output("`@Guadagni `^" . $gainxp_ES . " punti esperienza!");
        $session['user']['specialmisc']="pozione gialla";
        $session['user']['experience']+= $gainxp_ES;
        }

    }

    else if($_GET['op']=="blu") {
    output("`@Stringi la boccetta `#Blu`@ e la bevi d'un fiato!`n");
    output("Dopo pochi secondi ti senti stranamente più buono verso il prossimo:");
    output("La tua cattiveria è diminuita");
    /* ********************PUNTI CATTIVERIA IN MENO ****************** */
    $gain = e_rand(1,10);
    $session['user']['evil']-=$gain;
    }

    else if($_GET['op']=="rossoblu" || $_GET['op']=="blurosso") {
    //gli elfi non hanno vantaggi
    output("`@Traffichi con le boccette che hai trovato nella borsetta, mischi la `\$Rossa`@ con la `#Blu`@ con la consapevolezza`n");
    output("che potrebbe esplodere!! Viene fuori un vomitevole intruglio <font color=\"#960096\"><b>Viola</b></font>, che con un pò di corraggio riesci a buttar giù!<br>",true);
    output("Il sapore è così disgustoso che svieni! `bRimani svenuto per un turno`b e quando riprendi i sensi...`n");
        if($session['user']['race']!=2) {
        output("`n`nscopri che l'intruglio ti ha reso particolarmente potente!!`n `bTi senti `#IMBATTIBILE!`n`@ E sei così in forma che recuperi il tempo perso`b");
    /* ******************* BUFFLIST POZIONE VIOLA ATTACCO (SIMILE CANZONE DI LONESTRIDER) ************ */
    // uso il 401 tanto gli elfi non possono averlo
        $session['bufflist'][401] = array("name"=>"`@Forza della pozione","rounds"=>5,"wearoff"=>"La pozione ha esaurito l'effetto...","atkmod"=>2,"roundmsg"=>"La pozione che hai creato ti rende potente.","activate"=>"offense");
        }
        else {
        output("`n`n`\$Scopri che ti ha prosciugato tutte le forze!`@ Forse non è stata una buona idea bere quell'intruglio! `nIl tuo corpo da elfo potrebbe non sopportare intrugli impuri e sapori così forti!! `nHai i sensi delicati tu!");
        $session['user']['hitpoints']=1;
        $session['user']['turns']--;
        }
    }

    else if($_GET['op']=="blugiallo" || $_GET['op']=="gialloblu") {
        output("`0Mischi la pozione `b`#Blu`b`0 con la `^`bGialla`b`0, prevedibilmente si forma un liquido `@`bVerde!`b`0`n");
        output("Paurosamente `@`bVerde!`b`0... della stessa tonalità del leggendario drago!`n");
        output("Titubante tracanni il liquido ma.... ");
        $caso = e_rand(1,10);
        if($caso>=9) {
            $gain = e_rand(1,5);
            output("ti senti bene, anzi...`n `@`ble tue ferite sembrano dissolversi`b`n`n");
            output("</span><font color=\"#00ff00\" size=+1>Sembra inoltre che tu sia diventato più vitale! Guadagni $gain HP permanenti</font>",true);
            $session['user']['maxhitpoints']+=$gain;
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
            debuglog("guadagna $gain HP permanenti con le pozioni");
        }else{
            output("ti senti come prima, non è successo nulla ...`n `@`bSembra che la pozione non abbia avuto alcun effetto.`b`n`n");
        }
    }

    else if($_GET['op']=="rossogiallo" || $_GET['op']=="giallorosso") {
        output("`@Prendi la boccetta di colore `\$Rosso`@ e quella di colore `^Giallo`@! I colori ti ispirano molto!`n");
        output("Mescoli le due boccette e vien fuori un intruglio `(`bARANCIONE`b`@");
        output("`nCon grande sicurezza bevi l'intruglio e senti un potere immenso entrare in te!`n");
        // e i qui i romani saranno contenti (un pò meno i laziali) :)))
        output("Hai dentro di te la `5Protezione della Lupa!!");
        /* ************ BUFFLIST PROTEZIONE DELLA LUPA (DIFESA) **************** */
        $session['bufflist'][369] = array("name"=>"`5Protezione della lupa","rounds"=>10,"wearoff"=>"La lupa non è più dentro di te","defmod"=>1.5,"roundmsg"=>"La lupa ti protegge contro gli attacchi.","activate"=>"defense");
    }

    else if($_GET['op']=="tutto") {
    //Si mischia tutto! nera compresa
    output("`@Azzardi a mischiare tutto! Il liquido `b`\$Rosso`@`b, il `b`#Blu`@`b, il `b`^Giallo`@`b e il `9`bNero`b`@!!");
    output("`nL'intruglio diventa fluorescente! Hai paura, ma ormai è troppo tardi per tornare indietro!`n");
    output("Sei un guerriero e i guerrieri sono coraggiosi! Potresti diventare fortissimo, oppure essere colpito da una malattia devastante, lo scoprirai molto presto!`n");
    output("Bevi il liquido fluorescente, la tua gola pulsa e il colore della pozione si può vedere attraverso essa!");
    // e agevoliamo un pò i piccini va
        if(e_rand(0,$session['user']['reincarna'])==0) {
            output("`n`n`b`^IL TUO CORAGGIO è STATO PREMIATO!!`b`n");
            output("`@La pozione che hai creato ti rende più forte... estremamente più forte!`n");
            output("Guadagni `b`^1 punto attacco PERMANENTE!!`b");
            $session['user']['bonusattack']++;
            $session['user']['attack']++;
            debuglog("guadagna 1 punto attacco permanente con l'alchimista");
            if(e_rand(0,$session['user']['reincarna']+2)==0) {
                output("`n`@Guadagni `b`^1 punto difesa PERMANENTE!!`b");
                $session['user']['bonusdefence']++;
                $session['user']['defence']++;
                debuglog("guadagna un punto difesa permanente con l'alchimista");
            }
        }else {
            output("`n`n`\$Ti è andata male! Non ti ispirava certo una pozione `9nera`\$ ma hai voluto rischiare lo stesso!");
            output("`nL'intruglio si impossessa del tuo corpo, ti prosciuga le forze e ti stordisce`n");
            output("Perdi due turni ed è meglio che stai lontano dalla foresta se non sei resistente!`n");
            output("La pozione ti ha drogato e `bhai i riflessi ridotti a zero`b!!`n");
            $session['user']['turns']-=2;
            $session['user']['hitpoints']=1;
            /* ************ I PLURIREINCARNATI RISCHIANO DI PERDERE HP PERMANENTI ************ */
            if(e_rand(0,$session['user']['reincarna'])>1) {
                $loss = e_rand(1,5);
                output("Eppure dovresti saperlo che è pericoloso trafficare con cose che non conosci!`n");
                output("Questo smacco morale ti deprime, e il tuo organismo ne risente!`n`n");
                output("`bPERDI $loss HP PERMANENTI`b");
                $session['user']['maxhitpoints']-=$loss;
                debuglog("Perde $loss HP permanenti con le pozioni");
            }
            /* *************** BUFFLIST RIFLESSI A ZERO (DIFESA) *****************+ */
            $session['bufflist'][369] = array("name"=>"`5Droga nel tuo sangue!","rounds"=>4,"wearoff"=>"Questa botta ti ha ripreso! La droga ha esaurito il suo effetto","defmod"=>0.1,"atkmod"=>0.1,"roundmsg"=>"I riflessi drogati non ti permettono di fare granché","activate"=>"defense");
        }

    }else if($_GET[op]=="tutte") {
        // mischia tutto senza la nera
        output("`@Ti diverte giocare al piccolo alchimista! Mischi un pò le tre boccette... agiti la boccetta...`n`n");
        output("`b`\$e il tuo cocktail ESPLODE!!`@`b`n`n");
        output("Nulla di preoccupante... perdi solo un pò di punti ferita e un turno foresta per riprenderti dallo shock.");
        $session['user']['turns']--;
        $session['user']['hitpoints']=max(1,round($session['user']['hitpoints']*0.5));
    }else if($_GET['op']=="annulla") {
        $session['user']['specialinc']="";
        output("`@Ma si... la pietra filosofale è solo una leggenda! Non si ottiene niente bevendo o mischiando intrugli sconosciuti.");
        output("`nRitorni quindi sulla tua strada");
    }else {
        // nei casi in cui c'è la nera da sola o mischiata con una sola pozione
        //la pozione nera non fa fare nulla in nessun caso tranne....
        output("`@Questa strana pozione `9`bNera`b`@ non ti convince! Ma vuoi provare lo stesso!`n");
        $black_ES= explode("nero", $_GET['op']);
        if($black_ES[1]!="") {
            output("Mischi la `b`9Nera`b`@ con quella di colore " . colora_ES($black_ES[1]) . " e sei ansioso di sapere cosa succede");
        }
        output("`nFortunatamente (o sfortunatamente) scopri che non è successo nulla!`n`n");
        output("`bLa pozione non ha avuto alcun effetto su di te!!`b");
    }
addnav("Ritorna in foresta", "forest.php");
}

function colora_ES($f) {
$f= ($f=="rosso")? "`\$Rosso`@" : (($f=="blu")?  "`#Blu`@" : "`^Giallo`@");
return $f;
}

?>
