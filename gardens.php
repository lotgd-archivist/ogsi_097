<?php

// gardenflirt 1.0 by anpera
// uses 'charisma' entry in database to determine how far a love goes, and 'marriedto' to know who with whom. ;)
// no changes necessary in database
// some changes in newday.php, hof.php, dragon.php, and inn.php required and in user.php optional!
// See http://www.anpera.net/forum/viewforum.php?f=27 for details

require_once "common.php";

$buff = array("name"=>"`!Protezione dell'amore","rounds"=>80,"wearoff"=>"`!Ti manca il tuo grande amore!`0","defmod"=>1.2,"roundmsg"=>"Il tuo grande amore ti fa pensare alla tua sicurezza!","activate"=>"defense");

if ($_GET[op]!="amaranti") page_header("I Giardini");
$session['user']['locazione'] = 132;
if ($_GET[op]=="flirt1"){
    if ($session['user']['seenlover']){
          $sql = "SELECT name FROM accounts WHERE locked=0 AND acctid=".$session['user']['marriedto']."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $partner=$row['name'];
        if ($partner=="") $partner = $session['user']['sex']?"`^Seth`0":"`5Violet`0";
        output("Cerchi di prepararti mentalmente a un'avventura travolgente, ma non riesci a dimenticare`3 $partner`0. Forse dovresti aspettare fino a domani.");
        addnav("G?Torna al Giardino","gardens.php");
    } else {
        if ($session['user']['marriedto']==4294967295) {
            output("Rifletti un'altra volta sul tuo matrimonio con ".($session['user']['sex']?"`^Seth`0":"`5Violet`0"));
            output(" e pensi se andare a trovare ".($session['user']['sex']?"lui":"lei")." al bar, o per chi ne varrebbe l");
            output("a pena mettere in discussione il tuo matrimonio.`n");
        }
        if($session['user']['charm']==4294967295) {
            output("Ti convinci che dovresti dedicare un po' più di tempo a tu".($session['user']['sex']?"o marito":"a moglie"));
            output(". Ma quando vai a trovarl".($session['user']['sex']?"o":"a")." nel giardino scopri che anche il resto ");
            output("de".($session['user']['sex']?"gli Uomini":"lle Donne")." qui non sono da disprezzare.`n");
        }
        output("Per chi ti decidi?`n`n");
        //gestione pagine, Sook
        $ppp=200; // Linee da mostrare per pagina
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("Pagina Precedente","gardens.php?op=flirt1&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);

        $sql = "SELECT acctid,name,sex,level,race,login,marriedto,charisma FROM accounts WHERE
        (locked=0) AND
        (npg =0) AND
        (superuser < 1) AND
        (sex <> ".$session['user']['sex'].") AND
        (alive=1) AND
        (acctid <> ".$session['user']['acctid'].") AND
        (laston > '".date("Y-m-d H:i:s",strtotime(date("r")."-346000 sec"))."' OR (charisma=4294967295 AND acctid=".$session['user']['marriedto'].") )
        ORDER BY charm DESC LIMIT $limit";
          $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","gardens.php?op=flirt1&limit=".($page+1)."");
        //addnav();
        output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>",true);
        output(($session['user']['sex']?"<img src=\"images/male.gif\">":"<img src=\"images/female.gif\">")."</td>
                <td><b>Nome</b></td>
                <td><b>Livello</b></td>
                <td><b>Razza</b></td>
                <td><b>Stato</b><td>
                <b>Ops</b></td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $biolink="bio.php?char=".rawurlencode($row['login'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
            addnav("", $biolink);
            if ($session['user']['charisma']<=$row['charisma']) {
                $flirtnum=$session['user']['charisma'];
            }$flirtnum=$row['charisma'];
            if ($row['charisma']<$session['user']['charisma']) {
                $flirtnum=$row['charisma'];
            }
            output("<tr class='".($i%2?"trlight":"trdark")."'><td></td>
                    <td>".$row['name']."</td><td>".$row['level']."</td><td>",true);
            switch($row['race']){
                case 0:
                output("`7Sconosciuto`0");
                break;
                case 1:
                output("`2Troll`0");
                break;
                case 2:
                output("`^Elfo`0");
                break;
                case 3:
                output("`0Uomo`0");
                break;
                case 4:
                output("`#Nano`0");
                break;
                case 5:
                output("`3Druido`0");
                break;
                case 6:
                output("`6Goblin`0");
                break;
                case 7:
                output("`2Orco`0");
                break;
                case 8:
                output("`4Vampiro`0");
                break;
                case 9:
                output("`5Lich`0");
                break;
                case 10:
                output("`&Fanciulla delle Nevi`0");
                break;
                case 11:
                output("`4Oni`0");
                break;
                case 12:
                output("`3Satiro`0");
                break;
                case 13:
                output("`#Gigante delle Tempeste`0");
                break;
                case 14:
                output("`\$Barbaro`0");
                break;
                case 15:
                output("`%Amazzone`0");
                break;
                case 16:
                output("`^Titano`0");
                break;
                case 17:
                output("`\$Demone`0");
                break;
                case 18:
                output("`(Centauro`0");
                break;
                case 19:
                output("`8Licantropo`0");
                break;
                case 20:
                output("`)Minotauro");
                break;
                case 21:
                output("`^Cantastorie");
                break;
                case 22:
                output("`@Eletto");
                break;
            }
            output("</td><td>",true);
            if ($session['user']['acctid']==$row['marriedto'] && $session['user']['marriedto']==$row['acctid']){
                if ($session['user']['charisma']==4294967295 && $row['charisma']==4294967295){
                    output("`@`bTu".($session['user']['sex']?"o marito":"a moglie")."!`b`0");
                }elseif ($flirtnum>=5){
                    output("`\$Proposta di matrimonio!`0");
                } else {
                    output("`^$flirtnum di ".$session['user']['charisma']." flirt contraccambiati!`0");
                }
            } elseif ($session['user']['acctid']==$row['marriedto']) {
                output("Flirta ".$row['charisma']." volte con te");
            } elseif ($session['user']['marriedto']==$row['acctid']) {
                output("I tuoi ultimi ".$session['user']['charisma']." flirt");
            } elseif ($row['marriedto']==4294967295 || $row['charisma']==4294967295){
                output("`iSposato`i");
            } else {
                output("-");
            }
            output("</td><td>[ <a href='$biolink'>Bio</a> | <a href='gardens.php?act=flirt&name=".rawurlencode($row['login'])."'>Flirtare</a> ]</td></tr>",true);
            addnav("","gardens.php?act=flirt&name=".rawurlencode($row['login']));
        }
        output("</table>",true);
        addnav("G?Torna al Giardino","gardens.php");
    }
} elseif ($_GET['act']=="flirt"){
    if ($session['user']['goldinbank']>0) {
       $getgold = round($session['user']['goldinbank']/2);
    }else {
       $getgold = 0;
    }
    $sql = "SELECT acctid,name,experience,charm,charisma,lastip,emailaddress,race,marriedto FROM accounts WHERE login=\"$_GET[name]\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if ($session['user']['charisma']<=$row['charisma']) {
            $flirtnum=$session['user']['charisma'];
        }
        if ($row['charisma']<$session['user']['charisma']) $flirtnum=$row['charisma'];
        if ($session['user']['lastip']==$row['lastip'] OR ($session['user']['emailaddress']==$row['emailaddress'] && $row['emailaddress'])){
            output("`\$`bQuesto non si può!!`b Mica puoi amoreggiare con gli stessi tuoi personaggi o con la tua famiglia!");
            addnav("G?Torna al Giardino","gardens.php");
        } elseif (($session['user']['race']==2 && $row['race']==4) || ($session['user']['race']==4 && $row['race']==2)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi ");
            output("per un po'. Guardando più da vicino, però, noti che elfi e nani forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
// Aggiunta incompatibilità nuove razze by Excalibur
        } elseif (($session['user']['race']==3 && $row['race']==8) || ($session['user']['race']==8 && $row['race']==3)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che uomini e vampiri forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==2 && $row['race']==8) || ($session['user']['race']==8 && $row['race']==2)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che elfi e vampiri forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==7 && $row['race']==4) || ($session['user']['race']==4 && $row['race']==7)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che orchi e nani forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==1 && $row['race']==5) || ($session['user']['race']==5 && $row['race']==1)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che troll e druidi forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==3 && $row['race']==6) || ($session['user']['race']==6 && $row['race']==3)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che uomini e goblin forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==2 && $row['race']==7) || ($session['user']['race']==7 && $row['race']==2)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che elfi e orchi forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==2 && $row['race']==6) || ($session['user']['race']==6 && $row['race']==2)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che elfi e goblin forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
// Incompatibilità nuove razze lich-oni-fanciulla delle nevi
        } elseif (($session['user']['race']==2 && $row['race']==11) || ($session['user']['race']==11 && $row['race']==2)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che elfi e oni forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==2 && $row['race']==9) || ($session['user']['race']==9 && $row['race']==2)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che elfi e lich forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==10 && $row['race']==6) || ($session['user']['race']==6 && $row['race']==10)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che fanciulle delle nevi e goblin forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==10 && $row['race']==7) || ($session['user']['race']==7 && $row['race']==10)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che fanciulle delle nevi e lich forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==10 && $row['race']==8) || ($session['user']['race']==8 && $row['race']==10)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che fanciulle delle nevi e vampiri forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==3 && $row['race']==9) || ($session['user']['race']==9 && $row['race']==3)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che umani e lich forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==12 && $row['race']==2) || ($session['user']['race']==2 && $row['race']==12)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che elfi e satiri forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==12 && $row['race']==8) || ($session['user']['race']==8 && $row['race']==12)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che vampiri e satiri forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
        } elseif (($session['user']['race']==12 && $row['race']==10) || ($session['user']['race']==10 && $row['race']==12)){
            output("Stai aspettando `6".$row['name']."`0 nel giardino e ".($session['user']['sex']?"lo":"la")." osservi per un po'. Guardando più da vicino, però, noti che fanciulle delle nevi e satiri forse non potranno mai stare insieme.");
            output(" Così lasci il giardino.");
// Fine aggiunta incompatibilità razze by Excalibur
        } elseif ($session['user']['turns']<1){
            output("Quando finalmente appare `6".$row['name']."`0 nel giardino, improvvisamente ti senti così stanco e distrutto a causa delle numerose battaglie; così decidi di aspettare fino a domani prima di cercare un flirt.`nPer oggi hai consumato i tuoi turni. ");
        } elseif ($session['user']['charm']<=1 && $session['user']['charisma']!=4294967295){
            output("Ti avvicini a `6".$row['name']."`0 e con il fascino di un pidocchio delle piante inizi a parlare con ".($session['user']['sex']?"lui":"lei").". Un po' offes".($session['user']['sex']?"o":"a")." ".$row['name']." si gira e si allontana.`nDovresti migliorare il tuo approccio...");
        } elseif ($row['charm']<=1 && $session['user']['charisma']!=4294967295){
            output("Ti avvicini a `6".$row['name']."`0. Più ti avvicini a ".($session['user']['sex']?"lui":"lei")." , più ".($session['user']['sex']?"lui":"lei")." ti sembra brutt".($session['user']['sex']?"o":"a").". Alla fine ".($session['user']['sex']?"lui":"lei")." ti ripugna talmente tanto, che torni al villaggio di corsa.");
        } elseif (abs($row['charm']-$session['user']['charm'])>10 && $session['user']['charisma']!=4294967295){
            output("Ti avvicini a `6".$row['name']."`0. Iniziate un discorso, ma in qualche modo non siete sulla stessa frequenza d'onda. Non si sviluppa un flirt promettente. Decidi di ritentare più tardi e ti incammini verso il villaggio.");
        } elseif ($session['user']['drunkenness']>66){
            output("Scopri `6".$row['name']."`0 all'ombra sotto un gruppo di alberi e cominci subito a balbettare a ".($session['user']['sex']?"lui":"lei")." facendo sentire la tua puzza di birra. Quando ".($session['user']['sex']?"lui":"lei")." proprio ");
            output("non reagisce e in qualche modo continua a guardare in terra, vuoi alzare la sua testa - e afferri la sterpaglia di spine davanti a te.`n");
            output("Nella tua sbornia pensavi che la sterpaglia fosse ".$row['name']." !! Forse è meglio smaltire la sbornia prima di ritentare.`n`n");
            output("`^Questo errore di è costato un turno ed un punto di fascino!");
            $session['user']['turns']-=1;
            $session['user']['charm']-=1;
        // Se sono entrambi sposati
        } elseif (($session['user']['marriedto']==4294967295 || $session['user']['charisma']==4294967295) && ($row['marriedto']==4294967295 || $row['charisma']==4294967295)) {
            if ($session['user']['marriedto']==$row['acctid'] && $session['user']['acctid']==$row['marriedto']){
                output("`%Porti ".($session['user']['sex']?"tuo marito":"tua moglie")." `6$row[name]`% nel giardino e vi dedicate un po' di tempo entrambi. ");
                output("`nRicevi un punto di fascino.");
                $session['bufflist']['lover']=$buff;
                $session['user']['charm']++;
                $session['user']['seenlover']=1;
            } elseif ($session['user']['charm']==$row['charm']){
                output("`%Ti avvicini a `6".$row['name']."`%. Subito iniziate ad amoreggiare e si sviluppa un discorso interessante. L'intesa con `6".$row['name']."`% è quasi perfetta!");
                output(" Vi allontanate per un po' in un posto appartato");
                output(" e passate delle belle ore insieme. Siccome siete entrambi sposati, vi promettete a vicenda, che mai nessuno verrà a sapere l'accaduto.");
                output("`n`nEntrambi ricevete un punto di fascino!");
                $session['user']['charm']+=1;
                $session['user']['seenlover']=1;
                $sql = "UPDATE accounts SET charm=charm+1 WHERE acctid='".$row['acctid']."'";
                db_query($sql);
                debuglog("guadagna 1 fascino flirtando con ".$row['name'],$row['acctid']);
                systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha passato
                con te delle ore bellissime nel giardino. Avete ricevuto entrambi un punto di fascino e nascondete
                il vostro segreto al vostro partner.");
            } else {
                output("`%Ti avvicini a `6".$row['name']."`% e inizi ad amoreggiare con grande ardore. `6".$row['name']."`% ci sta, ");
                switch(e_rand(4,4)){
                    case 1:
                    case 2:
                    output("`% e siccome siete entrambi sposati vi promettete a vicenda, che mai nessuno verrà a sapere l'accaduto.");
                    output("`n`nPERDETE entrambi un punto di fascino, perchè non riuscite a nascondere la vostra coscienza al vostro partner!");
                    $sql = "UPDATE accounts SET charm=charm-1 WHERE acctid='".$row['acctid']."'";
                    $session['user']['charm']-=1;
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha passato
                    con te delle bellissime ore nel giardino. Avete PERSO entrambi un punto di fascino perchè la
                    vostra coscienza non è rimasta nascosta al vostro partner.");
                    db_query($sql);
                    $session['user']['seenlover']=1;
                    debuglog("perde 1 fascino flirtando con ".$row['name']." pur essendo sposato",$row['acctid']);
                    break;
                    case 3:
                    output("`% e siccome siete entrambi sposati vi promettete a vicenda, che mai nessuno verrà a sapere l'accaduto.");
                    output("`n`nEntrambi ricevete un punto di fascino!");
                    $sql = "UPDATE accounts SET charm=charm+1 WHERE acctid='".$row['acctid']."'";
                    $session['user']['charm']+=1;
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha passato
                    con te delle bellissime ore nel giardino. Avete ricevuto entrambi un punto di fascino e nascondete
                    il vostro segreto al vostro partner.");
                    db_query($sql);
                    $session['user']['seenlover']=1;
                    debuglog("guadagna 1 fascino flirtando con ".$row['name']." pur essendo sposato",$row['acctid']);
                    break;
                    case 4:
                    output(" ma venite scoperti durante il vostro piacere da ".($session['user']['sex']?"tuo marito":"tua moglie").".`nIl patatrac è perfetto.`0`n`n".($session['user']['sex']?"Tuo marito":"Tua moglie")." ti lascia");
                    debuglog("divorzia dal".($session['user']['sex']?" marito":"la moglie")."flirtando ai giardini con".$row['name'],$row['acctid']);
                    if ($session['user']['charisma']==4294967295){
                        //Excalibur: cancellazione matrimonio
                        $sqlmatri = "DELETE FROM matrimoni
                        WHERE acctid1 = ".$session['user']['acctid']."
                        OR acctid2 = ".$session['user']['acctid'];
                        $resultmatri = db_query($sqlmatri) or die(db_error(LINK));
                        //Excalibur: fine matrimonio
                        debuglog("perde anche il 50% dell'oro depositato in banca");
                        output(" e riceve il 50% del tuo patrimonio dalla banca");
                        $session['user']['goldinbank'] /= 2;
                        $sposatocon = $session['user']['marriedto'];
                        $sql = "UPDATE accounts SET marriedto=0, charisma=0, goldinbank = (goldinbank+$getgold) WHERE acctid = '$sposatocon'";
                        db_query($sql);
                        systemmail($session['user']['marriedto'],"`\$Divorzio!`0","`6Hai beccato `&".$session['user']['name']."`6
                        con `&".$row['name']." nel giardino e ".($session['user']['sex']?"la":"lo")." lasci.`nTi vengono conferiti
                        `^$getgold`6 oro dal tuo ex-partner.");
                    }
                    output(".`nPerdi un punto di fascino.");
                    $session['user']['marriedto']=$row['acctid'];
                    $session['user']['charisma']=1;
                    $session['user']['seenlover']=1;
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 è stato colto
                    di sorpresa da ".($session['user']['sex']?"suo marito":"sua moglie")." mentre stava amoreggiando con
                    te.");
                    $session['user']['charm']-=1;
                    addnews("`\$".$session['user']['name']."`\$ è stat".($session[user][sex]?"a":"o")." trovat".($session[user][sex]?"a":"o")." da ".($session['user']['sex']?"suo marito":"sua moglie")." mentre amoreggiava con ".$row['name']." `\$nel giardino ed ora è di nuovo single.");
                    break;
                }
            }
        // Possibilità se solo il player è sposato
        } elseif ($session['user']['marriedto']==4294967295 OR $session['user']['charisma']==4294967295) { // Möglichkeiten, wenn nur selbst verheiratet
            if ($session['user']['marriedto']==4294967295 AND $session['user']['charisma']>=5){
                output("`6".($session['user']['sex']?"Seth":"Violet")." salta fuori dal cespuglio e ti insulta
                pesantemente quando cerchi di avvicinarti a ".$row['name'].". ".($session['user']['sex']?"Lui":"Lei")."
                osserva il tuo \"lavoro di giardinaggio\" già da un po'!`0`n`n".($session['user']['sex']?"Seth":"Violet")." ti
                lascia.`nPerdi un punto di fascino.");
                $session['user']['marriedto']=$row['acctid'];
                $session['user']['charisma']-=1;
                $session['user']['seenlover']=1;
                $session['user']['charm']-=1;
                addnews("`\$".$session['user']['name']."`\$ è stat".($session[user][sex]?"a":"o")." trovat".($session[user][sex]?"a":"o")." da ".($session['user']['sex']?"Seth":"Violet")."
                mentre amoreggiava con ".$row['name']." nel giardino ed ora è di nuovo single.");
                debuglog("flirta ai giardino con ".$row['name']." pur essendo sposato con
                ".($session['user']['sex']?"Seth":"Violet").". Perde 1 fascino e divorzia da
                ".($session['user']['sex']?"Seth":"Violet"));
            } else {
                if ($session['user']['acctid']==$row['marriedto']){
                    output("`%Nonostante tu sia sposat".($session[user][sex]?"a":"o")." stai ai tentativi di corteggiamento di ".$row['name'].". L'intesa è
                    buona e per un attimo dimentichi ".($session['user']['sex']?"tuo marito":"tua moglie").". ");
                } else {
                    output("`%Nonostante tu sia sposat".($session[user][sex]?"a":"o")." contraccambi il flirt. L'intesa è buona e per un attimo dimentichi
                    ".($session['user']['sex']?"tuo marito":"tua moglie").". ");
                }
                switch(e_rand(1,4)){
                    case 1:
                    case 2:
                    case 3:
                    output("`% Ma sai bene che il vostro rapporto non avrà un futuro finchè sei sposat".($session[user][sex]?"a":"o").".");
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha passato con te
                    delle belle ore nel giardino.");
                    $session['user']['seenlover']=1;
                    if ($session['user']['marriedto']==4294967295) $session['user']['charisma']+=1;
                    break;
                    case 4:
                    output(" Ma ".($session['user']['sex']?"lui":"lei")." ti torna in mente ed il senso di colpa ha il
                    sopravvento!`nIl patatrac è perfetto.`0`n`n".($session['user']['sex']?"Tuo marito":"Tua moglie")." ti lascia");
                    if ($session['user']['charisma']==4294967295){
                        output(" e riceve il 50% del tuo patrimonio dalla banca.`nPerdi un punto di fascino");
                        $sql = "UPDATE accounts SET marriedto=0,charisma=0,goldinbank=goldinbank+$getgold WHERE acctid='{$session['user']['marriedto']}'";
                        db_query($sql);
                        //Excalibur: cancellazione matrimonio
                        $sqlmatri = "DELETE FROM matrimoni
                        WHERE acctid1 = ".$session['user']['acctid']."
                        OR acctid2 = ".$session['user']['acctid'];
                        $resultmatri = db_query($sqlmatri) or die(db_error(LINK));
                        //Excalibur: fine matrimonio
                        $session['user']['goldinbank'] /= 2;
                        debuglog("flirta con ".$row['name']." ma viene preso dal senso di colpa e divorzia dal ".($session['user']['sex']?" marito":"la moglie")." perdendo la metà dei soldi in banca");
                        systemmail($session['user']['marriedto'],"`\$Divorzio!`0","`6Hai beccato `&".$session['user']['name']."`6
                        con `&".$row['name']." nel giardino e ".($session['user']['sex']?"la":"lo")." lasci.`nTi vengono
                        conferiti `^$getgold`6 oro dal tuo ex-partner.");
                    }
                    output(".");
                    $session['user']['marriedto']=$row['acctid'];
                    $session['user']['charisma']=1;
                    $session['user']['seenlover']=1;
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha amoreggiato con te
                    ed è stato beccato da ".($session['user']['sex']?"sua moglie":"suo marito").".");
                    $session['user']['charm']-=1;
                    addnews("`\$".$session['user']['name']."`\$ è stat".($session[user][sex]?"a":"o")." trovat".($session[user][sex]?"a":"o")." da ".($session['user']['sex']?"suo marito":"sua
                    moglie")." ed ora è di nuovo single.");
                    debuglog("flirta ai giardini con ".$row['name']." ma viene beccato dal ".($session['user']['sex']?" marito":"la moglie")." e torna single");
                    break;
                }
            }
        // Possibilità se solo l'altro player è sposato
        } elseif ($row['marriedto']==4294967295 || $row['charisma']==4294967295) {
            if ($session['user']['marriedto']==$row['acctid']){
                $session['user']['charisma']+=1;
                $session['user']['seenlover']=1;
                output("`%Amoreggi la `^".$session['user']['charisma']."a`% volta con ".$row['name']." `%, ma sai
                che non sarà contraccambiato finchè ".$row['name']."`% è (ancora) sposat".($session['user']['sex']?"o":"a").".");
            } else {
                output("`%Amoreggi con ".$row['name']."`% e passate un po' di tempo nel giardino.");
                debuglog("amoreggia la ".$session['user']['charisma']."a volta con ".$row['name']." pur sapendo che è
                sposat".($session['user']['sex']?"o":"a"));
                $session['user']['charisma']=1;
                $session['user']['seenlover']=1;
            }
            systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha amoreggiato con
            te nel giardino.");
            $session['user']['marriedto']=$row['acctid'];
        // Entrambi non sposati
        } else {
            if ($session['user']['acctid']==$row['marriedto']){
                if ($flirtnum>=5){
                    output("`c`b`&Matrimonio!`0`b`c");
                    output("`&`n`nNel frattempo vi conoscete talmente bene che durante il vostro $flirtnum flirt
                    decidete di sposarvi!");
                    output(" Il matrimonio è una festa gigantesca! Sapete bene come si festeggia.`n`nD'ora in poi
                    siete marito e moglie!");
                    //Excalibur: inserimento data matrimonio
                    $sqlmatri = "INSERT INTO matrimoni (acctid1,acctid2,data) VALUES ('".$session['user']['acctid']."','".$row['acctid']."',now())";
                    $resultmatri = db_query($sqlmatri) or die(db_error(LINK));
                    //Excalibur: fine matrimonio
                    if (getsetting("paidales",0)>=1){
                        $amt=e_rand(2,6);
                        output("`nAvanzano solo $amt di birra dal banchetto, che gentilmente regalate al bar.");
                        savesetting("paidales",getsetting("paidales",0)+$amt);
                    }
                    $session['user']['charisma']=4294967295;
                    $sql = "UPDATE accounts SET charisma='4294967295',charm=charm+1 WHERE acctid='".$row['acctid']."'";
                    db_query($sql);
                    systemmail($row['acctid'],"`&Matrimonio!`0","`6 Tu e `&".$session['user']['name']."`& vi siete sposati dopo numerosi flirt nel giardino.`nCongratulazioni!");
                    $session['user']['seenlover']=1;
                    $session['bufflist']['lover']=$buff;
                    $session['user']['charm']+=1;
                    $session['user']['donation']+=1;
                    addnews("`%".$session['user']['name']." `&e `%".$row['name']."`& oggi hanno contratto matrimonio!!!");
                    addnav("Lista delle Coppie","hof.php?op=paare");
                } elseif ($flirtnum>0){
                    $session['user']['charisma']+=1;
                    $session['user']['seenlover']=1;
                    $session['user']['charm']+=1;
                    output("`%Amoreggi la `^".$session['user']['charisma']."a `%volta con la tua fiamma ".$row['name']."`%.`n");
                    output("Avete contraccambiato già $flirtnum volte i vostri flirt. Se lo farete 5 volte ".$row['name']." `%promette di sposarti!");
                    output("`n`n`^Ricevi un punto di fascino.");
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha passato
                    con te delle belle ore nel giardino. Con quest'ultimo avete $flirtnum flirt tra di voi. Dal 5°
                    flirt potrete sposarvi!");
                } else {
                    $session['user']['charisma']+=1;
                    $session['user']['seenlover']=1;
                    $session['user']['charm']+=1;
                    output("`%Contraccambi il flirt di ".$row['name']." `%e passi un po' di tempo con
                    ".($session['user']['sex']?"lui":"lei")." nel giardino.`n");
                    systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 contraccambia
                    il tuo flirt e ha passato delle belle ore con te nel giardino.");
                    output("`n`n`^Ricevi un punto di fascino.");
                }
                $session['user']['marriedto']=$row['acctid'];
            } elseif ($session['user']['marriedto']==$row['acctid']){
                debuglog("flirta con ".$row['name']." - ".$row['acctid']." -");
                $session['user']['charisma']+=1;
                $session['user']['seenlover']=1;
                output("`%Amoreggi la `^".$session['user']['charisma']."a`% volta con ".$row['name']." `%e speri che
                il flirt sia contraccambiato.");
                systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 di nuovo ha
                passato delle belle ore con te nel giardino.`nVuoi far vedere una tua reazione?");
            } else {
                output("`%Amoreggi con ".$row['name']."`% e passate un po' di tempo insieme nel giardino.");
                debuglog("flirta con ".$row['name']." - ".$row['acctid']." -");
                systemmail($row['acctid'],"`%Flirt nel giardino!`0","`&".$session['user']['name']."`6 ha passato
                delle belle ore con te nel giardino.");
                $session['user']['charisma']=1;
                $session['user']['seenlover']=1;
                $session['user']['marriedto']=$row['acctid'];
            }
        }
    }else{
        output("`\$Errore:`4 Questo guerriero non è stato trovato. Posso chiederti come sei arrivato qui?");
    }
} elseif ($_GET['op']=="amaranti") {
    page_header("L'Aiuola degli Amaranti");
    addcommentary();
    output("`GTi avvicini con animo assorto all'aiuola, sapendo che i `4fiori di Amaranto `G dal bellisssimo color porpora `G, utilizzati
    dai giardinieri di Rafflingate per scrivere il nome di `i`(Gienah`i`G, hanno un forte valore simbolico.`nIl loro nome deriva dal termine
    greco amarantos `4\"che non appassisce mai\" `G e per tal motivo sai essere i fiori dell'amicizia, della stima reciproca, espressione di
    tutti i sentimenti veri che mai cambiano col trascorrere del tempo, poichè unici ed eterni.`n`nTi inginocchi su un `4cuscino rosso `G e
    trovi accanto ad esso una piccola pergamena dove qualcuno prima di te ha scritto il suo pensiero.`nCon mano tremante ti appresti a
    lasciare il tuo verbo per l'amico che, nell'uccider il `@Verde Drago`G, ha brandito per l'ultima volta la sua spada lucente.`n`n");
    viewcommentary("amaranti","Pregare qui",30,25,"prega");
    addnav("G?Torna ai Giardini","gardens.php");
} else {
    addcommentary();
    checkday();

    output("`b`c`2I giardini`0`c`b");
    output("`n`n`2Corri attraverso un arco fiorito e prosegui percorrendo uno dei tanti sentieri che si intrecciano nei giardini ben
    curati. Dalle aiuole di fiori, che fioriscono anche durante la stagione invernale, alle siepi, le cui ombre potrebbero celare reconditi segreti,
    questi giardini sono un ottimo rifugio per tutti quelli che sono alla ricerca del `@Drago Verde`2. Qui possono dimenticare tutte le loro 
    preoccupazioni e rilassarsi un po'.`n`n");
    if (getsetting("nomedb","logd") == "logd"){
        output("<big>`(Una delle fate che svolazzano qui nel giardino ti si avvicina per ricordarti che questo è un luogo
        per il gioco di ruolo, e che i commenti non dei personaggi dovrebbero essere scritti altrove.</big>",true);
        output("`n`n");
    }
    viewcommentary("gardens","Bisbigliare qui",30,25,"sussurra");
    addnav("Azioni","");
    if ($session['user']['npg'] == 0){
        addnav("Amoreggiare","gardens.php?op=flirt1");
    }
    addnav("Luoghi","");
    if(getsetting("nomedb","logd")=='logd') addnav("L'Aiuola degli Amaranti","gardens.php?op=amaranti");
    addnav("J?La Casetta di Javella","javella.php");
    addnav("V?Le Pietre di Vessa","vessa.php");
    addnav("C?Il Negozio di Cassandra","flowers.php");
    if (getsetting("nomedb","logd") == "logd") addnav("M?Agenzia Matrimoniale","agenziamatrimoniale.php");
}
addnav("T?Torna al Villaggio","village.php");
addnav("Esplora i Giardini");
addnav("L?Il Laghetto","gardenpond.php?op=enter");
page_footer();
?>