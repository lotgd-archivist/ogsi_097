<?php
require_once("common.php");
require_once("common2.php");
checkday();

page_header("Il Tavolo di Dag Durnick");
$session['user']['locazione'] = 115;
output("<span style='color: #9900FF'>",true);
output("`c`bIl Tavolo di Dag Durnick`b`c");

if ($_GET['op']=="list"){
    output("Dag pesca un libretto rilegato in pelle da sotto il mantello, lo apre ad una certa pagina e lo alza in modo che tu possa vederlo.`n`n");
    output("`c`bElenco delle Taglie`b`c`n");
    $sql = "SELECT name,alive,sex,level,laston,loggedin,bounty,location,jail,lastip FROM accounts WHERE bounty>0 AND superuser = 0 AND lastip <> '".$session['user']['lastip']."' ORDER BY bounty DESC";
    $result = db_query($sql) or die(sql_error($sql));
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Taglia</b></td><td><b>Livello</b></td><td><b>Vivo</b></td><td><b>Nome</b></td><td><b>Posizione</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td><b>Ultimo collegamento</b></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        output("`^".$row['bounty']."`0");
        output("</td><td>",true);
        output("`@".$row['level']."`0");
        output("</td><td>",true);
        output($row['alive']?"`!Si`0":"`\$No`0");
        output("</td><td>",true);
        output("`&".$row['name']."`0");
        if ($session['user']['loggedin']) output("</a>",true);
        output("</td><td>",true);
        $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);
        if ($row['jail']){
            output ("`!In Prigione");
        }else{
            if (!$row['alive']) {
                 output("`\$Morto");
            }else{
                 if ($row['location']==0) output($loggedin?"`#Collegato`0":"`3Nei Campi`0");
                 if ($row['location']==1) output("`%Locanda alla Testa del Cinghiale`0");
                 if ($row['location']==2) output("`6In una Casa`0");
                 if ($row['location']==3) output("`2Nel Dormitorio`0");
                 if ($row['location']==11) output("`RNelle Terre dei Draghi`0");
            }
        }
        output("</td><td>",true);
        //output($row[sex]?"`!Femmina`0":"`!Maschio`0");
        output($row[sex]?"<img src=\"images/female.gif\">":"<img src=\"images/male.gif\">",true);
        output("</td><td>",true);
        //AGGIORNAMENTO PHP 5
        //$laston=round((strtotime("0 days")-strtotime($row['laston'])) / 86400,0)." giorni";
        $laston=round((strtotime(date("r"))-strtotime($row[laston])) / 86400,0)." days";
        if (substr($laston,0,2)=="1 ") $laston="1 giorno";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Oggi";
        if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("r")."-1 day"))) $laston="Ieri";
        if ($loggedin) $laston="Ora";
        output($laston);
        output("</td></tr>",true);
    }
    output("</table>",true);
}else if ($_GET['op']=="addbounty"){
    if ($session['user']['bounties'] >= getsetting("maxbounties",5)) {
        output("Dag ti rivolge uno sguardo penetrante. `7\"Pensi che io sia un'assassino o qualcosa del genere? Hai già oferto più che abbastanza taglie per oggi. Ora vattene prima che metta io una taglia su di te per avermi importunato.`n`n");
    } else {
        $fee = getsetting("bountyfee",10);
        if ($fee < 0 || $fee > 100) {
            $fee = 10;
            savesetting("bountyfee",$fee);
        }
        $min = getsetting("bountymin",50);
        $max = getsetting("bountymax",400);
        output("Dag Durnick guarda verso di te e si aggiusta la pipa in mocca con i denti.`n`7\"Così vuoi mettere una taglia eh? Giusto perché tu lo sappia, deve essere legale ammazzarlo, deve essere almeno di livello " . getsetting("bountylevel",3) . ", e non deve avere troppe taglie in sospeso o essere attaccato troppo di frequente, perciò se non è su questa lista, non si accettano contratti! Non abbiamo un mattatoio qui, facciamo ... uhm ... affari. E c'è anche un " . getsetting("bountyfee",10) . "% di tassa per ogni taglia offerta.\"`n`n");
        output("<form action='dag.php?op=finalize' method='POST'>",true);
        output("`2Bersaglio: <input name='contractname'>`n", true);
        output("`2Valore della Taglia: <input name='amount' id='amount' width='5'>`n`n",true);
        output("<input type='submit' class='button' value='Concludi Contratto'></form>",true);
        addnav("","dag.php?op=finalize");
    }
}elseif ($_GET['op']=="finalize") {
    //$name = "%" . rawurldecode($_POST['contractname']) . "%";
    if ($_GET['subfinal']==1){
        $sql = "SELECT acctid,name,login,level,locked,age,dragonkills,pk,experience,bounty FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['contractname'])))."' AND locked=0";
        //output($sql);
    }else{
        $contractname = stripslashes(rawurldecode($_POST['contractname']));
        $name="%";
        for ($x=0;$x<strlen($contractname);$x++){
            $name.=substr($contractname,$x,1)."%";
        }
        $sql = "SELECT acctid,name,login,level,locked,age,dragonkills,pk,experience,bounty FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
    }
    $result = db_query($sql);
    if (db_num_rows($result) == 0) {
        output("Dag Durnick ti guarda storto, `7\"Non conosco nessuno con questo nome. Forse dovresti tornare quando avrai qualcuno che esiste in mente!\"");
    } elseif(db_num_rows($result) > 100) {
        output("Dag Durnick si gratta la testa confuso, `7\"Hai descritto mezza città! Perché non mi dai un nome decente?\"");
    } elseif(db_num_rows($result) > 1) {
        output("Dag Durnick controlla per un attimo nella sua lista, `7\"Ce ne sono un paio che potrebbero corrispondere. Di quale stai parlando?\"`n");
        output("<form action='dag.php?op=finalize&subfinal=1' method='POST'>",true);
        output("`2Bersaglio: <select name='contractname'>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<option value=\"".rawurlencode($row['name'])."\">".preg_replace("'[`].'","",$row['name'])."</option>",true);
        }
        output("</select>`n`n",true);
        output("`2Valore della Taglia: <input name='amount' id='amount' width='5' value='".$_POST['amount']."'>`n`n",true);
        output("<input type='submit' class='button' value='Concludi Contratto'></form>",true);
        addnav("","dag.php?op=finalize&subfinal=1");
    } else {
        // Now, we have just the one, so check it.
        $row  = db_fetch_assoc($result);
        if ($row['locked']) {
            output("Dag Durnick ti guarda storto, `7\"Non conosco nessuno con questo nome. Forse dovresti tornare quando avrai qualcuno che esiste in mente!\"");
        } elseif ($row['login'] == $session['user']['login']) {
            output("Dag Durnick si da una manata sul ginocchio ridendo sguaiatamente, `7\"Vuoi mettere una taglia sulla tua testa? Non ho intenzione di aiutare un suicida!\"");
        } elseif ($row['level'] < getsetting("bountylevel",3) ||
                  ($row['age'] < getsetting("pvpimmunity",5) &&
                   $row['dragonkills'] == 0 && $row['pk'] == 0 &&
                   $row['experience'] < getsetting("pvpminexp",1500))) {
            output("Dag Durnick ti guarda infuriato,`7\"Ti ho detto che non sono un assassino. Quel bersaglio non merita una taglia. Fuori dai piedi!\"");
        } else {
            // All good!
            $amt = abs((int)$_POST['amount']);
            $min = getsetting("bountymin", 50) * $row['level'];
            $max = getsetting("bountymax", 400) * $row['level'];
            $fee = getsetting("bountyfee",10);
            if ($amt < $min) {
                output("Dag Durnick si acciglia, `7\"Pensi che lavorerò per questa miseria? Ripensaci e torna quando avrai voglia di spendere dei soldi veri. Devono essere almeno " . $min . " pezzi d'oro perché ci perda del tempo.\"");
            } elseif ($session['user']['gold'] <round($amt*1.1,0)) {
                output("Dag Durnick si acciglia, `7\"Non hai abbastanza soldi per questo contratto. Farmi perdere tempo in questo modo! Dovrei metterla su di TE una taglia!");
            } elseif ($amt + $row['bounty'] > $max) {
                output("Dag guarda la pila di monete e la lascia dove si trova. `7\"Non accetto il contratto. È più di quanto `^".$row['name']." `7valga e lo sai. Non sono un dannato assassino. Ha già una taglia di {$row['bounty']} sulla sua testa. Potrei alzarla al massimo fino a $max, dopo aver ricevuto il mio $fee% ovviamente\"`n`n");
            } else {
                output("Spingi l'oro verso Dag Durnick, che rapidamente li spazza via dal tavolo. `7\"Mi prenderò da qui il mio $fee% E farò girare la voce che vuoi che ci si occupi di `^{$row['name']} `7. Sii paziente e tieni d'occhio le notizie.\"`n`n");
                $session['user']['bounties']++;
                $cost = round($amt*1.1,0);
                $session['user']['gold']-=$cost;
                debuglog("ha speso $cost oro per una taglia da $amt su ",$row['acctid']);
                $sql = "UPDATE accounts SET bounty=bounty+$amt WHERE login='".$row['login']."'";
                db_query($sql);
                report(3,"`!Nuova taglia","`\$".$session['user']['name']." ha messo una taglia su di ".$row['name']."!","taglie");
            }
        }
    }
}else{
    output("Ti avvicini a Dag Durnick, che neppure si degna di guardarti. Tira una grossa boccata dalla sua pipa.`n");
    output("`7\"Probabilmente sei qui per sapere se c'è una taglia sulla tua testa, eh?.\"`n`n");
    if ($session['user']['bounty']>0){
        output("\"`3Beh, sembra che ci sia una taglia di `^".$session['user']['bounty']." pezzi d'oro`3 sulla tua testa al momento. Credo che dovresti guardarti le spalle.\"");
    }else{
        output("\"`3Non ci sono taglie su di te. Ti suggerisco di continuare così.\"");
    }
    addnav("Lista dei Ricercati","dag.php?op=list");
    addnav("Offri una Taglia","dag.php?op=addbounty");
}
if ($_GET['op'] != '')
    addnav("Parla con Dag Durnick", "dag.php");
addnav("Torna alla Locanda","inn.php");

// Whoops, forgot this when you changed from <font> to <span>
output("</span>",true);

page_footer();
?>