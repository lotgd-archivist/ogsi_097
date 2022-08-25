<?php
require_once "common.php";

if ($session['user']['alive']) redirect("village.php");

page_header("Il Cimitero");
$session['user']['locazione'] = 135;
checkday();
$session['bufflist']=array();
$session['user']['drunkenness'] = 0;
$max = $session['user']['level'] * 5 + 50;
$favortoheal = round(10 * ($max-$session['user']['soulpoints'])/$max);

if ($_GET['op']==""){
    output("`)`c`bIl Cimitero`b`c");
    output("`nIl tuo spirito vaga in un cimitero abbandonato, dove sterpaglie spinose si protendono come mani scheletriche cercando
    di afferrarti nel tuo fluttuare etereo. Tutt'attorno giacciono abbandonati e divelti, i resti scheggiati di lapidi e sarcofagi.
    Puoi quasi udire i lamenti strazianti delle anime intrappolate in questo limbo senza tempo e senza speranza.
    `n`n Al centro del cimitero sorge un antico Mausoleo, il cui aspetto consunto e sbrecciato tradisce l'appertenenza ad un'epoca
    lontana e ormai dimentica. Sinistri gargoyle ghignanti adornano gli angoli del tetto : i loro occhi vuoti sembrano seguirti e le loro 
    bocche irte di affilati denti di pietra irridono la tua vacua presenza. `nSopra l'architrave del tetro ingresso una scritta, che il 
    tempo ha reso a mala pena leggibile, recita :  `\$Ramius il Signore della Morte`).");

    addnav("T?Trova qualcosa da tormentare","graveyard.php?op=search");
    addnav("M?Entra nel Mausoleo","graveyard.php?op=enter");
    addnav("E?Elenco Guerrieri","list.php");
    addnav("Vai al Tunnel","tunnel.php");
    addnav("O?Torna tra le Ombre","shades.php");
}elseif ($_GET['op']=="enter"){
    output("`)`b`cIl Mausoleo`c`b");
    output("`nVarchi la soglia dell'antico Mausoleo e ti ritrovi in una fredda camera di marmo. L'aria intorno a te è permeata del
    gelo della morte stessa che, come una cappa di piombo, ti opprime con un peso indicibile. Dall'oscurità due neri occhi scrutano 
    la tua anima e freddi artigli penetrano nella tua mente colmandola delle parole di `\$Ramius il Signore della Morte`) :`n`n ");
    output(" \"`7Le tue spoglie mortali ti hanno abbandonato. Puoi vagare per l'eternità in queste oscure lande o puoi servirmi. 
    Tormenta le anime di coloro che hanno eluso la mia presa e guadagna i miei favori. Se saprai soddisfarmi, grande sarà la tua ricompensa!  `)\"");
    addnav("Chiedi a `\$Ramius`0 quanto vale la tua anima","graveyard.php?op=question");
    addnav("S?Ripristina la tua Anima ($favortoheal favori)","graveyard.php?op=restore");

    addnav("Torna al Cimitero","graveyard.php");
}elseif ($_GET['op']=="restore"){
    output("`)`b`cAl cospetto del `\$Signore della Morte`)`c`b");
	output("`nTimoroso di fronte al suo cospetto, ti inginocchi prostrandoti ai piedi del `\$Signore della Morte`) fin quando la tua fronte tocca la nuda terra e senza osare alzare lo sguardo mormori quasi balbettando la tua domanda. `n");
    if ($session['user']['soulpoints']<$max){
        if ($session['user']['deathpower']>=$favortoheal){
            output("`n`\$Ramius`) sogghigna per la tua debolezza che ti vede dover ricorrere al suo intervento, ma poichè hai abbastanza favori da riscuotere, esaudisce la tua richiesta al misero costo di `4$favortoheal`) favori.");
            $session['user']['deathpower']-=$favortoheal;
            $session['user']['soulpoints']=$max;
            debuglog("spende $favortoheal per ristorare la sua anima mentre è da Ramius");
        }else{
            output("`n`\$Ramius`) si inalbera per la tua presuntuosa richiesta, ti maledice e ti caccia dal Mausoleo. Devi guadagnare più favori prima che Egli ti conceda il suo intervendo esaudendo il tuo desiderio.");
        }
    }else{
        output("`n`\$Ramius`) indignato mormora qualcosa a proposito di, \"`7Questo poveracci".($row[sex]?"a":"o")." è mort".($row[sex]?"a":"o")." anche nella mente, non solo nel corpo!`)\"`n`n");
        output("Forse dovresti aver veramente `ibisogno`i di ripristinare la tua anima, prima di disturbare il `\$Signore della Morte`).");
    }
    addnav("Chiedi a `\$Ramius`0 quanto vale la tua anima","graveyard.php?op=question");
    //addnav("Restore Your Soul ($favortoheal favor)","graveyard.php?op=restore");

    addnav("Torna al Cimitero","graveyard.php");
}elseif ($_GET['op']=="question"){
	output("`)`b`cAl cospetto del `\$Signore della Morte`)`c`b");
	output("`nTimoros".($row[sex]?"a":"o")." di fronte al suo cospetto, ti inginocchi prostrandoti ai piedi del `\$Signore della Morte`) fin quando la tua fronte tocca la nuda terra e senza osare alzare lo sguardo mormori quasi balbettando la tua domanda. `n");
    output("Una voce cavernosa ti rimbomba nella mente stordendoti con il tono suo grave: `n`n ");
	if ($session['user']['deathpower']>=100) {
	    output("\"`7L'impegno che hai messo nel compito che ti ho assegnato è stato notevole e mi hai veramente impressionato. `nTi concedo pertanto la possibilità di ritornare da vivo nel mondo dei mortali.`)\"");
        addnav("Favori di Ramius");
        addnav("Tormenta un nemico (25 favori)","graveyard.php?op=haunt");
        addnav("Resurrezione (100 favori)","newday.php?resurrection=true");
        addnav("Altro");
    }elseif ($session['user']['deathpower'] >= 25){
        output("\"`7Non ti sei impegnat".($row[sex]?"a":"o")." più di tanto nel compito che ti ho assegnato, per cui mi hai solo moderatamente impressionato. `nPer ora ti concedo un piccolo favore, ma continua il tuo compito e potrò esaudire meglio i tuoi desideri.`)\"");
        addnav("Favori di Ramius");
        addnav("Tormenta un nemico (25 favori)","graveyard.php?op=haunt");
        addnav("Altro");
    }else{
        output("\"`7L'impegno che hai messo nel compito che ti ho assegnato è stato molto scarso, non mi hai per nulla impressionato. `nContinua il tuo compito con più tenacia e forse potremo riparlarne.`)\"");
    }
    output("`n`nHai `6{$session['user']['deathpower']}`) favori non riscossi con `\$Ramius`).");
    addnav("Chiedi a `\$Ramius`0 quanto vale la tua anima","graveyard.php?op=question");
    addnav("Ripristina la tua Anima ($favortoheal favori)","graveyard.php?op=restore");

    addnav("Torna al Cimitero","graveyard.php");
}elseif ($_GET['op']=="haunt"){
	output("`)`b`cAl cospetto del `\$Signore della Morte`)`c`b");
    output("`n`\$Ramius`) è impressionato da come hai eseguito i suoi voleri, pertanto ti concede il potere di tormentare il tuo più acerrimo nemico.`n`n");
    output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
    addnav("","graveyard.php?op=haunt2");
    output("Chi vorresti tormentare? <input name='name' id='name'> <input type='submit' class='button' value='Cerca'>",true);
    output("</form>",true);
    output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    addnav("Torna al Mausoleo","graveyard.php?op=enter");
}elseif ($_GET['op']=="haunt2"){
	output("`)`b`cAl cospetto del `\$Signore della Morte`)`c`b");
    $string="%";
    for ($x=0;$x< strlen($_POST['name']);$x++){
        $string .= substr($_POST['name'],$x,1)."%";
    }
    $sql = "SELECT login,name,level FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY level,login";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`\$Ramius`) non è riuscito a trovare nessuno con il nome che gli hai suggerito.");
    }elseif(db_num_rows($result)>100){
        output("`n`\$Ramius`) ti consiglia di restringere il campo di ricerca del nome di chi vuoi tormentare.");
        output("<form action='graveyard.php?op=haunt2' method='POST'>",true);
        addnav("","graveyard.php?op=haunt2");
        output("Chi vorresti tormentare? <input name='name' id='name'> <input type='submit' class='button' value='Cerca'>",true);
        output("</form>",true);
        output("<script language='JavaScript'>document.getElementById('name').focus()</script>",true);
    }else{
        output("`n`\$Ramius`) ti potrà permettere di tormentare uno di questi guerrieri:`n`n");
        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
        output("<tr class='trhead'><td>Nome</td><td>Livello</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i< db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='graveyard.php?op=haunt3&name=".HTMLEntities($row['login'])."'>",true);
            output($row['name']);
            output("</a></td><td>",true);
            output($row['level']);
            output("</td></tr>",true);
            addnav("","graveyard.php?op=haunt3&name=".HTMLEntities($row['login']));
        }
        output("</table>",true);
    }
    addnav("Chiedi a `\$Ramius`0 quanto vale la tua Anima","graveyard.php?op=question");
    addnav("Ripristina la tua Anima ($favortoheal favori)","graveyard.php?op=restore");
    addnav("Torna al Mausoleo","graveyard.php?op=enter");
}elseif ($_GET['op']=="haunt3"){
    output("`)`b`cAl cospetto del `\$Signore della Morte`)`c`b");
    $sql = "SELECT name,level,hauntedby,acctid FROM accounts WHERE login='{$_GET['name']}'";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if ($row['hauntedby']!=""){
            output("`nQuesto guerriero oggi è già stato tormentato, devi sceglierne un altro tra tutti i tuoi nemici.");
        }else{
            $session['user']['deathpower']-=25;
            $roll1 = e_rand(0,$row['level']);
            $roll2 = e_rand(0,$session['user']['level']);
            if ($roll2>$roll1){
                output("`nHai tormentato con successo `7{$row['name']}`)!");
                debuglog("ha tormentato con successo ",$row['acctid']);
                $sql = "UPDATE accounts SET hauntedby='{$session['user']['name']}' WHERE login='{$_GET['name']}'";
                db_query($sql);
                addnews("`7{$session['user']['name']}`) ha tormentato `7{$row['name']}`)!");
                systemmail($row['acctid'],"`)Sei stato tormentato","`)Sei stato tormentato da {$session['user']['name']}");
            }else{
                addnews("`7{$session['user']['name']}`) ha tentato inutilmente di tormentare `7{$row['name']}`)!");
                debuglog("tenta inutilmente di tormentare ",$row['acctid']);
                switch (e_rand(0,5)){
                case 0:
                    output("`nProprio quando tentavi di tormentare `7{$row['name']}`) per bene, ha starnutito e non si è accorto di niente.");
                    break;
                case 1:
                    output("`nTormenti molto bene `7{$row['name']}`) ma sfortunatamente sta dormendo e non si accorge neppure della tua presenza.");
                    break;
                case 2:
                    output("`nStai per tormentare `7{$row['name']}`), ma inciampi sulla tua coda fantasma e finisci di, uhm... faccia... a terra.");
                    break;
                case 3:
                    output("`nVai a tormentare `7{$row['name']}`) nel sonno, ma ti guarda e si volta dall'altra parte borbottando qualcosa a proposito di non mangiare salsicce subito prima di andare a dormire.");
                    break;
                case 4:
                    output("`nSvegli `7{$row['name']}`) che ti guarda per un momento prima di dichiarare, \"Forte!\" e cercare di acchiapparti.");
                    break;
                case 5:
                    output("`nVai a spaventare `7{$row['name']}`), ma scorgi il tuo riflesso in uno specchio e cadi nel panico alla vista di un fantasma!");
                    break;
                }
            }
        }
    }else{
        output("`n`\$Ramius`) è stato distratto da un'anima dannata e ha perso la concentrazione su questo guerriero, ora non ti è possibile tentare di tormentarla.");
    }
    addnav("Chiedi a `\$Ramius`0 quanto vale la tua anima","graveyard.php?op=question");
    addnav("Ripristina la tua Anima ($favortoheal favori)","graveyard.php?op=restore");
    addnav("Torna al Mausoleo","graveyard.php?op=enter");
}
if ($session['user']['deathpower']>500){
    output("`n`n`b`#Purtroppo hai superato il limite massimo di favori possibili e `\$Ramius`# li ha riportati entro tale limite.`b`n");
    $session['user']['deathpower'] = 500;
}
if ($_GET['op']=="search"){
	output("`)`c`bIl Cimitero`b`c");
    if ($session['user']['gravefights']<=0){
        output("`n`)Per oggi hai esaurito il potere nel Cimitero a te concesso da `\$Ramius il Signore della Morte`), la tua anima ora non è più in grado di sostenere altri combattimenti in quest'altra vita.`0");
        $_GET['op']="";
        addnav("T?Trova qualcosa da tormentare","graveyard.php?op=search");
    	addnav("M?Entra nel Mausoleo","graveyard.php?op=enter");
    	addnav("E?Elenco Guerrieri","list.php");
    	addnav("Vai al Tunnel","tunnel.php");
    	addnav("O?Torna tra le Ombre","shades.php");
    }else{
        $session['user']['gravefights']--;
        $battle=true;
        $sql = "SELECT * FROM creatures WHERE location=1 ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $badguy = db_fetch_assoc($result);
        $level = $session['user']['level'];
        $shift = 0;
        if ($level < 5) $shift = -1;
        $badguy['creatureattack'] = 9 + $shift + (int)(($level-1) * 1.5);
        // Make graveyard creatures easier.
        $badguy['creaturedefense'] = (int)((9 + $shift + (($level-1) * 1.5)) * .7);
        $badguy['creaturehealth'] = $level * 5 + 50;
        $badguy['creatureexp'] = e_rand(10 + round($level/3),20 + round($level/3));
        $badguy['creaturelevel'] = $level;
        //output("`#DEBUG: Creature level: {$badguy['creaturelevel']}`n");
        //output("`#DEBUG: Creature attack: {$badguy['creatureattack']}`n");
        //output("`#DEBUG: Creature defense: {$badguy['creaturedefense']}`n");
        //output("`#DEBUG: Creature health: {$badguy['creaturehealth']}`n");
        //output("`#DEBUG: Creature exp: {$badguy['creatureexp']}`n");
        $session['user']['badguy']=createstring($badguy);
    }
}
if ($_GET['op']=="fight" || $_GET['op']=="run"){
    if ($_GET['op']=="run"){
        if (e_rand(0,2)==1) {
            output("`\$Ramius`) ti maledice per la tua codardia.`n`n");
            $favor = 5 + e_rand(0, $session['user']['level']);
            if ($favor > $session['user']['deathpower'])
                $favor = $session['user']['deathpower'];
            if ($favor > 0) {
                output("`)Hai `\$PERSO `^$favor`) favori con `\$Ramius.");
                debuglog("ha perso $favor da Ramius per la sua codardia");
                $session['user']['deathpower']-=$favor;
            }
            addnav("Torna al Cimitero","graveyard.php");
        } else {
            output("`)Mentre tenti di defilarti, vieni riportato a combattere!`n`n");
            $battle=true;
        }
    } else {
        $battle = true;
    }
}

if ($battle){
    //make some adjustments to the user to put them on mostly even ground with the undead guy.
    $originalhitpoints = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $session['user']['soulpoints'];
    $originalattack = $session['user']['attack'];
    $originaldefense = $session['user']['defence'];
    $session['user']['attack'] = 10 + round(($session['user']['level'] - 1) * 1.5);
    $session['user']['defence'] = 10 + round(($session['user']['level'] - 1) * 1.5);
    if ($session['user']['hashorse']!=0) {
        $session['user']['attack'] *= $playermount['tormentatkmod'];
        $session['user']['defence'] *= $playermount['tormentdefmod'];
    }
    include("battle.php");
    //reverse those adjustments, battle calculations are over.
    $session['user']['attack'] = $originalattack;
    $session['user']['defence'] = $originaldefense;
    $session['user']['soulpoints'] = $session['user']['hitpoints'];
    $session['user']['hitpoints'] = $originalhitpoints;
    if ($victory) {
        output("`b`&{$badguy['creaturelose']}`0`b`n");
        output("`b`\$Hai tormentato {$badguy['creaturename']}!`0`b`n");
        output("`#Ricevi `^{$badguy['creatureexp']}`# favori con `\$Ramius`#!`n`0");
        debuglog("riceve {$badguy['creatureexp']} favori per aver tormentato un'anima");
        $session['user']['deathpower']+=$badguy['creatureexp'];
        $badguy=array();
        $_GET['op']="";
        addnav("T?Trova qualcosa da tormentare","graveyard.php?op=search");
    	addnav("M?Entra nel Mausoleo","graveyard.php?op=enter");
    	addnav("E?Elenco Guerrieri","list.php");
    	addnav("Vai al Tunnel","tunnel.php");
    	addnav("O?Torna tra le Ombre","shades.php");
    }else{
        if ($defeat){
            //addnav("Return to the shades","shades.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"her":"him"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"she":"he"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);

            addnews("`)".$session['user']['name']."`) è stat".($session['user']['sex']?"a":"o")." sconfitt".($session['user']['sex']?"a":"o")." nel cimitero da {$badguy['creaturename']}`n$taunt");
            output("`b`&Sei stat".($session['user']['sex']?"a":"o")." sconfitt".($session['user']['sex']?"a":"o")." da `%{$badguy['creaturename']} `&!!!`n");
            debuglog("è stato sconfitto da un'anima al cimitero");
            output("Hai esaurito i poteri temporanei concessi a te da `\$Ramius il Signore della Morte`), non puoi più tormentare altre anime per oggi.");
            $session['user']['gravefights']=0;
            addnav("Torna al Cimitero","graveyard.php");
        }else{
            addnav("T?Tormenta","graveyard.php?op=fight");
            addnav("F?Fuggi","graveyard.php?op=run");
            if (getsetting("autofight",0)){
               addnav("AutoFight");
               addnav("`^5 Rounds","graveyard.php?op=fight&auto=five");
               addnav("M?`^Fino Alla Morte","graveyard.php?op=fight&auto=full");
            }

        }
    }
}



page_footer();
?>