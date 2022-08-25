<?php
require_once("common.php");
?>
<script type="text/javascript">
function selectAll(x) {
for(var i=0,l=x.form.length; i<l; i++)
if(x.form[i].type == 'checkbox' && x.form[i].name != 'sAll')
x.form[i].checked=x.form[i].checked?false:true
}
</script>
<?php
$giorno_chiusura_voto = 20;
$account = getsetting("idgestoreagenzia",3);
$sqlsex = "SELECT sex FROM accounts WHERE acctid = $account";
$resultsex = db_query($sqlsex) or die(sql_error($sqlsex));
$rowsex = db_fetch_assoc($resultsex);
$sesso = $rowsex['sex'];
$gestnome = getsetting("nomegestoreagenzia","Excalibur");
$gestnome1 = "`&".$gestnome;
if (date("d") > $giorno_chiusura_voto AND getsetting("datagestioneagenzia",200802) < date("Ym") ){
    $sql = "SELECT votato, COUNT(*) AS eletto
            FROM agenzia_voti
            WHERE data = ".date("Ym")."
            GROUP BY votato
            ORDER BY eletto DESC LIMIT 1";
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    $sql1 = "SELECT login FROM accounts WHERE acctid = ".$row['votato'];
    $result1 = db_query($sql1) or die(sql_error($sql1));
    $row1 = db_fetch_assoc($result1);
    savesetting("idgestoreagenzia",$row['votato']);
    savesetting("nomegestoreagenzia",$row1['login']);
    savesetting("datagestioneagenzia",date("Ym"));
}
page_header("Agenzia Matrimoniale di ".$gestnome);
$session['user']['locazione'] = 103;
output("`b`c`@Agenzia Matrimoniale di ".$gestnome1."`0`c`b`n");
$costo = 50; //costo della ricerca anima gemella
if ($_GET['op']=="vota1"){
    output("`3Qui puoi esprimere il tuo voto per chi deve gestire l'Agenzia Matrimoniale.`n");
    output("Scrivi il nome (senza titoli) di chi vorresti gestisse l'Agenzia`n");
    output("<form action='agenziamatrimoniale.php?op=vota2' method='POST'><input name='account' value=''><input type='submit' class='button' value='Scegli Gestore'></form>`n`n",true);
    addnav("","agenziamatrimoniale.php?op=vota2");
}elseif ($_GET['op']=="vota2"){
    $sql = "SELECT acctid, name FROM accounts WHERE name LIKE '%".$_POST['account']."%' LIMIT 20";
    $result = db_query($sql) or die(sql_error($sql));
    $countrow = db_num_rows($result);
    //echo "SQL = ".$sql."<br>Numero righe: ".$countrow;
    if ($countrow == 0){
        output("`3Non ho trovato nessun giocatore con un nome simile a quello che hai inserito.`nRiprova.`n");
        addnav("Torna all'inizio","agenziamatrimoniale.php");
    }elseif ($countrow > 1){
        output("`3C'è più di un giocatore con un nome simile a quello che hai indicato.`n");
        output("Chi tra questi è quello che vuoi votare? (clicca sul nome)`n");
        for ($i = 0; $i < $countrow; $i++){
             $row = db_fetch_assoc($result);
             output("<a href=agenziamatrimoniale.php?op=vota3&acctid=".$row['acctid'].">".$row['name']."</a>`n",true);
             addnav("","agenziamatrimoniale.php?op=vota3&acctid=".$row['acctid']);
        }
        addnav("Torna all'inizio","agenziamatrimoniale.php");
    }else{
        output("`3Ho trovato un solo player con un nome simile a quello che hai inserito.`n");
        output("Se è lui che intendi votare clicca sul nome.`n`n");
        $row = db_fetch_assoc($result);
        output("<a href=agenziamatrimoniale.php?op=vota3&acctid=".$row['acctid'].">".$row['name']."</a>",true);
        addnav("","agenziamatrimoniale.php?op=vota3&acctid=".$row['acctid']);
        addnav("Torna all'inizio","agenziamatrimoniale.php");
    }
}elseif ($_GET['op']=="vota3"){
    $sql = "SELECT name FROM accounts WHERE acctid = ".$_GET['acctid'];
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    output("`3È ".$row['name']." `3il giocatore che vuoi votare per gestire l'Agenzia Matrimoniale?`n");
    addnav("`@Si","agenziamatrimoniale.php?op=vota4&acctid=".$_GET['acctid']);
    addnav("`\$No","agenziamatrimoniale.php");
}elseif ($_GET['op']=="vota4"){
    $sql = "INSERT INTO agenzia_voti
            VALUES ('','".$session['user']['acctid']."','".$_GET['acctid']."','".date("Ym")."')";
    $result = db_query($sql) or die(sql_error($sql));
    output("`^Voto registrato !!`n");
    addnav("Torna all'inizio","agenziamatrimoniale.php");
}elseif ($session['user']['acctid'] == $account AND $_GET['op']==""){
    //Sezione di gestione di Liala
    output("`3Salve ".$gestnome1."`3, bentornat".($sesso?"a":"o")." nella tua agenzia! Ora vediamo se ci sono richieste di ");
    output("consulenza per te.`n`n");
    $sql = "SELECT a.*,
            s.name AS nome,
            s.dragonkills AS dk,
            s.reincarna,
            b.bio AS bio
            FROM agenzia_matrimoni a,accounts s,bio b
            WHERE a.acctid=s.acctid
            AND   b.bioacctid=s.acctid
            ORDER BY data ASC LIMIT 1";
    $result = db_query($sql) or die(sql_error($sql));
    if (db_num_rows($result) == 0){
        output("`3Che peccato, nessuna richiesta! Dovresti fare un po' di pubblicità all'agenzia, o forse è dovuto ");
        output("al fatto che sei troppo efficiente ed hai evaso tutte le richieste !!`n`n");
        output("Non c'è nulla da fare qui, puoi tornare a sbrigare i tuoi doveri di cittadin".($sesso?"a":"o").".`n`n");
        addnav("Torna ai Giardini","gardens.php");
    }else{
        $row = db_fetch_assoc($result);
        $session['prescelto'] = array();
        $session['prescelto'] = $row;
        output("Bene, bene, bene ...`n`nHo selezionato per te una richiesta di aiuto.`nEcco un");
        output(($session['user']['sex']?"a gentil donzella":" baldo giovane")." che ha pagato per ricevere i tuoi ");
        output("servigi:`n`n`(".$row['nome']."`3`nrazza: ".$races[$row['razza']]."`3`n");
        if ($row['reincarna']) output("`3Reincarnato`n");
        if ($row['dk']) output("`3Ha ucciso il `@Drago Verde `^".$row['dk']." `3volte`n");
        if ($row['bio']) output("`3Dice di se: `^".$row['bio']."`3.`n`n");
        output("Vediamo chi suggerisce il nostro archivio come candidato ideale ");
        output("per ".($session['user']['sex']?"la nostra amica":"il nostro amico").".`n`n");
        addnav("Elabora la richiesta","agenziamatrimoniale.php?op=elabora");
    }
}elseif($session['user']['acctid'] == $account AND $_GET['op']=="elabora"){
    //print_r ($session['prescelto']);
    if ($session['prescelto']['razza']==1){
        $notwork1 = 5;
        $notwork2 = 0;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==2){
        $notwork1 = 6;
        $notwork2 = 7;
        $notwork3 = 8;
        $notwork4 = 9;
        $notwork5 = 11;
        $notwork6 = 12;
    }elseif ($session['prescelto']['razza']==3){
        $notwork1 = 6;
        $notwork2 = 8;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==4){
        $notwork1 = 7;
        $notwork2 = 0;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==5){
        $notwork1 = 1;
        $notwork2 = 0;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==6){
        $notwork1 = 2;
        $notwork2 = 3;
        $notwork3 = 10;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==7){
        $notwork1 = 2;
        $notwork2 = 4;
        $notwork3 = 10;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==8){
        $notwork1 = 2;
        $notwork2 = 3;
        $notwork3 = 10;
        $notwork4 = 12;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==9){
        $notwork1 = 2;
        $notwork2 = 3;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==10){
        $notwork1 = 6;
        $notwork2 = 7;
        $notwork3 = 8;
        $notwork4 = 12;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==11){
        $notwork1 = 2;
        $notwork2 = 0;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }elseif ($session['prescelto']['razza']==12){
        $notwork1 = 2;
        $notwork2 = 8;
        $notwork3 = 10;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }else{
        $notwork1 = 0;
        $notwork2 = 0;
        $notwork3 = 0;
        $notwork4 = 0;
        $notwork5 = 0;
        $notwork6 = 0;
    }


    $sql = "SELECT a.*, b.bio 
            FROM accounts a,bio b
            WHERE a.acctid=b.bioacctid
            AND a.charisma <> '4294967295'
            AND ABS(".$session['prescelto']['fascino']." - 'a.charm') < 10
            AND a.sex <> ".$session['prescelto']['sesso']."
            AND a.lastip <> '".$session['prescelto']['ip']."'
            AND a.uniqueid <> '".$session['prescelto']['id']."'
            AND a.superuser = 0
            AND a.race <> ".$notwork1."
            AND a.race <> ".$notwork2."
            AND a.race <> ".$notwork3."
            AND a.race <> ".$notwork4."
            AND a.race <> ".$notwork5."
            AND a.race <> ".$notwork6."
            ORDER BY RAND() LIMIT 10";
    //print $sql;
    $result = db_query($sql) or die(sql_error($sql));
    if (db_num_rows($result) == 0){
        output("<big>`b`%Non ho trovato nessun player con le caratteristiche adatte !!!`b</big>`n`n",true);
        output("`3Purtroppo sarai tu a dover dare la triste notizia a `(".$session['prescelto']['nome']."`3, anche se ");
        output("non sarà piacevole.`nPurtroppo è il lato negativo dell'impegno che ti sei accollata.`nMa non ");
        output("prendertela, vedrai che andrà meglio con la prossima richiesta!`n`n");
        $sql = "DELETE FROM agenzia_matrimoni WHERE acctid = ".$session['prescelto']['acctid'];
        $result = db_query($sql) or die(sql_error($sql));
        systemmail($session['prescelto']['acctid'],"$gestnome1`\$ ha esaminato la tua richiesta",
        "`@".$session['prescelto']['nome'].", $gestnome1 `@a breve ti invierà i risultati della sua ricerca, per trovare la tua anima gemella.`n");
        addnav("Torna ai Giardini","gardens.php");
        addnav("Richiesta Successiva","agenziamatrimoniale.php");
    }else{
        output("`3Candidato: ".$session['prescelto']['nome']."`3`nrazza: ".$races[$session['prescelto']['razza']]."`3`n");
        if ($session['prescelto']['reincarna']) output("`3Reincarnato`n");
        if ($session['prescelto']['dk']) output("`3Ha ucciso il `@Drago Verde `^".$session['prescelto']['dk']." `3volte`n");
        if ($session['prescelto']['bio']) output("`3Dice di se: `^".$session['prescelto']['bio']."`3.`n`n");
        output("`3Ecco una vasta scelta di possibili nomi che sono compatibili con le caratteristiche di ");
        output("`@".$session['prescelto']['nome']."`3. Ora a te decidere chi consigliar".($session['user']['sex']?"le":"gli").".`n`n");
        output("<form method='POST' action='agenziamatrimoniale.php?op=scelta'>",true);
        output("<table><tr><td>`b`&Scegli`b`0</td><td>`b`&Nome`b`0</td><td>`b`&Razza`b`0</td><td>`b`#Reincarnato`b`0</td>
                <td align='right'>`b`@DK`b`0</td><td align='center'>`b`^Biografia`b`0</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $row = db_fetch_assoc($result);
            output("<tr><td><input type='checkbox' name='scegli[]' value=".$row['acctid']."></td>",true);
            output("<td>`&".$row['name']."</td>",true);
            output("<td>".$races[$row['race']]."</td>",true);
            output("<td align='right'>`#".$row['reincarna']." volte</td>",true);
            output("<td align='right'>`@".$row['dragonkills']." volte</td>",true);
            output("<td>`^".$row['bio']."</td></tr>",true);
        }
        output("</table>",true);
        addnav("","agenziamatrimoniale.php?op=scelta");
        output("<br><input type='checkbox' name='sAll' onclick='selectAll(this)' /> `@<b>Seleziona tutti</b>",true);
        output("`n`n<input type='submit' class='button' value='Seleziona Pretendenti'></form>`n`n",true);
        output("`nPuoi selezionarne uno solo, qualcuno, o anche tutti. La scelta spetta a te, basati sul tuo instinto, ");
        output("sulle tue percezioni, sulle tue sensazioni, lasciati guidare dal tuo cuore, e fa quel che ritieni giusto.`n");
        output("La scelta definitiva spetterà comunque a `@".$session['prescelto']['nome']."`3.`n`n`n");
    }
    //addnav("Torna ai Giardini","gardens.php");
}elseif($session['user']['acctid'] == $account AND $_GET['op']=="scelta"){
    switch(e_rand(1,4)){
        case 1:
        output("`3Ottima scelta! Sono in totale accordo, i nomi che hai scelto saranno perfetti per ");
        output("`@".$session['prescelto']['nome']."`3.`n");
        break;
        case 2:
        output("`3Bene! Sono proprio dei nomi azzeccati quelli che hai scelto saranno perfetti per ");
        output("`@".$session['prescelto']['nome']."`3.`n");
        break;
        case 3:
        output("`3Mhhhh ... non sono completamente d'accordo, ma qualcuno dei nomi scelti sarà perfetto per ");
        output("`@".$session['prescelto']['nome']."`3.`n");
        break;
        case 4:
        output("`3Ok, avresti potuto scegliere di meglio, ma sicuramente qualcuno dei nomi scelti sarà adatto per ");
        output("`@".$session['prescelto']['nome']."`3.`n");
        break;
    }
    output("Ora ".($session['user']['sex']?"le":"gli")." invierò un messaggio sottoponendo i nomi che hai ");
    output("scelto e ... se son rose fioriranno !!`n`n Ecco i nomi che hai deciso di sottoporre a ");
    output("`@".$session['prescelto']['nome']."`3:`n`n");
    $mailmessage = "`@La dolce ".$gestnome1." `@ha analizzato la tua richiesta ed ecco i nomi che tanto ";
    $mailmessage .= "agognavi!!`n`n";
    $mailtitle = "`^Le proposte di ".$gestnome1;
    for ($i=0; $i < sizeof($_POST['scegli']); $i++){
        $sql = "SELECT name, acctid FROM accounts WHERE acctid = ".$_POST['scegli'][$i];
        $result = db_query($sql) or die(sql_error($sql));
        $row = db_fetch_assoc($result);
        $mailmessage .= "`%".($i + 1).". `&".$row['name']."`n";
        output("`(".($i + 1).". `&".$row['name']."`n");
    }
    $mailmessage .= "`n`@Ti auguriamo un sereno e felice futuro con la persona che sceglierai di corteggiare`n`n";
    $mailmessage .= $gestnome1." `@e `\$lo Staff di LoGD`n";
    systemmail($session['prescelto']['acctid'],$mailtitle,$mailmessage);
    $sql = "DELETE FROM agenzia_matrimoni WHERE acctid = ".$session['prescelto']['acctid'];
    $result = db_query($sql) or die(sql_error($sql));
    addnav("Torna ai Giardini","gardens.php");
}elseif ($_GET['op'] == "verificavoti"){
    $sql = "SELECT a.name
            FROM accounts a
            LEFT JOIN agenzia_voti v ON a.acctid=v.acctid
            WHERE v.data = ".date("Ym")." AND v.votato = ".$_GET['acc'];
    //echo "Query SQL: ".$sql;
    $result = db_query($sql) or die(sql_error($sql));
    $countrow = db_num_rows($result);
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr class='trhead'><td align='center'>`bVoti per ".$_GET['nome']."`b</td></tr>",true);
    for ($i = 0; $i < $countrow; $i++){
        $row = db_fetch_assoc($result);
        output("<tr><td>".$row['name']."</td></tr>",true);
    }
    output("</table>",true);
    addnav("Torna all'inizio","agenziamatrimoniale.php");
}else{
    //Sezione per i player che chiedono consulenza
    if ($session['user']['charisma'] == 4294967295){
        output("`%Cosa ci fai qui? Sei già sposato e non hai la necessità di rivolgerti a ".$gestnome1." ");
        output("`%per i suoi servizi!!`n");
        addnav("Torna ai Giardini","gardens.php");
    }elseif ($_GET['op']==""){
        if ($session['user']['charm'] < 5){
            output($gestnome1." `2ti osserva attentamente mentre ti avvicini a lei e dopo aver scosso la testa ti dice:`n`n");
            output("\"`#Mi spiace, ma non posso fare nulla per te. Il tuo fascino è talmente basso che nessuno ti ");
            output("accetterebbe come ".($session['user']['sex']?"moglie":"marito")."`nCerca di migliorare il ");
            output("tuo aspetto esteriore prima di tornare da me, e vedrò di aiutarti nella tua ricerca della felicità`2\".`n`n");
            output("Scornat".($session['user']['sex']?"a":"o").", non ti resta che andartene e cercare di seguire il suo consiglio.`n`n");
            addnav("Torna ai Giardini","gardens.php");
        }else{
            $sql = "SELECT acctid FROM agenzia_matrimoni WHERE acctid = ".$session['user']['acctid'];
            $result = db_query($sql) or die(sql_error($sql));
            if (db_num_rows($result) == 0){
               output("`2Entri in un appartamento graziosamente arredato, dove il tocco ".($sesso?"femminile":"di buon gusto"));
               output("del".($sesso?"la":"")." padron".($sesso?"a":"e"));
               output(" di casa ha pervaso ogni angolo. Delicati soprammobili di ogni forma, le pareti colorate con ");
               output("tenui colori, tende ricamate ad ogni finestra ... ogni oggetto riflette la personalità di $gestnome1.`n`n");
               output("\"`#Benvenuto ".$session['user']['name']."`# nella mia umile dimora!`2\"`nTi volti in direzione ");
               output("della voce e scopri un".($sesso?"a stupenda creatura":"o scultoreo individuo").", ".$gestnome1."`2, ");
               output(($sesso?"la proprietaria":"il proprietario")." dell'agenzia matrimoniale.`n`n");
               output("\"`#Se ti sei decis".($session['user']['sex']?"a":"o")." a venire da me è perchè sei alla ricerca dell'anima gemella, ed in tutta ");
               output("onestà ti posso rassicurare che sei nel posto giusto. Ti chiedo solo un piccolo contributo, che ");
               output("finirà nelle casse del Municipio che mi ospita gentilmente in questa casa, per il costo di gestione ");
               output("degli archivi. Sono solo ".$costo." pezzi d'oro, ma ti assicuro che non rimarrai delus".($session['user']['sex']?"a":"o").", ed il nome ");
               output("che ti darò sarà quello della persona che cercavi da molto tempo`2\".`n");
               if ($session['user']['charm'] < 10){
                   output("`n\"`#Sappi comunque che il tuo fascino è decisamente scarso, e ci sono buone probabilità che ");
                   output("il tuo matrimonio duri la vita di una farfalla. Ti consiglio di curare maggiormente il tuo aspetto ");
                   output("ma se deciderai di usufruire comunque dei miei servigi, cercherò di fare del mio meglio`2\".`n");
               }elseif ($session['user']['charm'] > 200){
                   output("`n\"`#Permettimi di farti i miei complimenti per il tuo splendido aspetto, è raro trovare ");
                   output("un".($session['user']['sex']?"a guerriera":" guerriero")." di siffatta beltà`2\".`n`n");
               }
               output("`(Cosa decidi di fare?`n");
               addnav("Lascia Perdere","gardens.php");
               if ($session['user']['gold'] > 49){
                   addnav("Paga la consulenza","agenziamatrimoniale.php?op=ricerca");
               }else{
                   addnav("Non hai oro, vai ai Giardini","gardens.php");
               }
            }else{
               output("".$gestnome1." `%non ha ancora evaso la tua richiesta !!`nSii paziente e presto riceverai per posta ");
               output("il nome tanto agognato !`n");
               addnav("Torna ai Giardini","gardens.php");
            }
        }
    }elseif($_GET['op']=="ricerca"){
        $session['user']['gold'] -=50;
        output("".$gestnome1." `2 raccoglie il tuo oro e lo versa nella fessura di una cassettina sulla scrivania. Prende ");
        output("quindi un modulo ed inizia a compilarlo con i tuoi dati. Al termine dell'operazione scorre ");
        output("velocemente il foglio leggendo sottovoce quanto scritto, e completata l'operazione alza lo sguardo ");
        output("e rivolgendoti un ampio e solare sorriso, che accentua la sua bellezza, dice:`n`n");
        output("\"`#Bene, dammi il tempo di confrontare la tua scheda con quelle dei potenziali partner. Non è una ");
        output("decisione da prendere alla leggera, per cui potrebbe volerci qualche giorno. Appena avrò trovato ");
        output("quella che ritengo possa essere la tua anima gemella ti invierò una missiva contenente il nome ");
        output(($session['user']['sex']?"del fortunato":"della fortunata")." che ho identificato.`n`n");
        output("Buona giornata ".$session['user']['name']."`#!`2\"`n`nSoddisfatt".($sesso?"a":"o")." della piacevole chiaccherata ");
        output("ti alzi e salutando ".$gestnome1." `2 torni ai giardini.`n");
        $sql = "INSERT INTO agenzia_matrimoni
               (acctid,sesso,razza,fascino,charisma,data,id,ip)
               VALUES(
               '".$session['user']['acctid']."',
               '".$session['user']['sex']."',
               '".$session['user']['race']."',
               '".$session['user']['charm']."',
               '".$session['user']['charisma']."',
               NOW(),
               '".$session['user']['uniqueid']."',
               '".$session['user']['lastip']."'
               )";
        $result = db_query($sql) or die(sql_error($sql));
        addnav("Torna ai Giardini","gardens.php");
    }

}
if ($session['user']['superuser'] > 2 AND $_GET['az'] == ""){
    output("`n`n`2Gestore attuale Agenzia Matrimoniale: ".$gestnome1."`n");
    output("`2Account ID del Gestore: ".$account."`n");
    output("<form action='agenziamatrimoniale.php?az=cambia' method='POST'><input name='account' value=''><input type='submit' class='button' value='Scegli AccountID'>`n",true);
    addnav("","agenziamatrimoniale.php?az=cambia");
}elseif ($session['user']['superuser'] > 2 AND $_GET['az'] == "cambia"){
    $account = $_POST['account'];
    if ($account == "") $account = "0";
    $sql = "SELECT login, name FROM accounts WHERE acctid = ".$account;
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    if (db_num_rows($result) == 0){
        output("`n`n`b`^L'account che hai immesso non corrisponde a nessun giocatore !!!!`n`n");
        output("`\$Controlla di averlo digitato correttamente (hai scritto `&$account`\$), e riscrivilo.`b`n`n");
        addnav("Torna all'inizio","agenziamatrimoniale.php");
    }else{
        $gestnome = $row['login'];
        savesetting("idgestoreagenzia",$account);
        savesetting("nomegestoreagenzia",$gestnome);
        output("`n`n`@Ho modificato il gestore dell'agenzia, ora è `&".$row['name']."`@, ed il suo Account ID è `&$account`@.`n");
        addnav("Torna all'inizio","agenziamatrimoniale.php");
    }
}
$sql = "SELECT * FROM agenzia_voti WHERE acctid = ".$session['user']['acctid']."
        AND data = ".date("Ym");
$result = db_query($sql) or die(sql_error($sql));
$countrow1 = db_num_rows($result);
if ($countrow1 == 0
    AND date("d") >= ($giorno_chiusura_voto - 5)
    AND date("d") < ($giorno_chiusura_voto + 1)
    AND $session['user']['dragonkills'] > 0){
    addnav("Vota il Gestore","agenziamatrimoniale.php?op=vota1");
}elseif ($countrow1 != 0
        AND date("d") >= ($giorno_chiusura_voto - 5)
        AND date("d") < ($giorno_chiusura_voto + 3)) {
    output("`S`cVoti per eleggere il gestore dell'Agenzia Matrimoniale`c`n`n");
    output("<table cellspacing=2 cellpadding=2 align='center'>",true);
    output("<tr bgcolor='#FF0000'><td>`&`bNome`b</td><td>`&`bVoti`b</td>",true);
    if ($session['user']['superuser'] > 2){
        output("<td>`&`bAcctID`b</td>",true);
    }
    output("</tr>",true);
    $sql = "SELECT a.login, a.acctid, a.name, v.votato, COUNT(*) AS eletto
            FROM agenzia_voti v
            LEFT JOIN accounts a ON a.acctid=v.votato
            WHERE v.data = ".date("Ym")."
            GROUP BY v.votato
            ORDER BY eletto DESC LIMIT 30";
    $result = db_query($sql) or die(sql_error($sql));
    $countrow = db_num_rows($result);
    for ($i = 0; $i < $countrow; $i++){
         $row = db_fetch_assoc($result);
         if($row['votato'] == $session['user']['acctid']){
             output("<tr bgcolor='#006600'>", true);
         }else{
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
         }
         output("<td>".$row['name']."</td><td align='right'>".$row['eletto']."</td>",true);
         if ($session['user']['superuser'] > 2){
             output("<td>
                     <a href='agenziamatrimoniale.php?op=verificavoti&acc=".$row['votato']."&nome=".$row['login']."'>
                     Verifica</a></td>",true);
             addnav("","agenziamatrimoniale.php?op=verificavoti&acc=".$row['votato']."&nome=".$row['login']);
         }
         output("</tr>",true);
    }
    output("</table>",true);
}else{
    output("`n`n`GLa prossima elezione per decidere chi sarà il gestore dell'Agenzia`nMatrimoniale verrà indetta ");
    output("a partire dal giorno `A".($giorno_chiusura_voto - 5)."`G");
    if (date("d") < ($giorno_chiusura_voto - 4)){
        output("del mese in corso.");
    }else{
        output("del mese prossimo.");
    }
    output("`n`n");
}
page_footer();
?>