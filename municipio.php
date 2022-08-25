<?php
require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));
addcommentary();
checkday();
page_header("Il Municipio");
$session['user']['locazione'] = 155;
/*test
$sql = "SELECT acctid,name,goldinbank,gold,login FROM accounts";
$result = db_query($sql);
output("$row[goldinbank]");
//test fine */

//Excalibur: cancellazione striscione Sindaco
if ($session['user']['superuser'] > 2){
    output("<big>`SCancello lo striscione del Sindaco?`n",true);
    output("<big><a href=municipio.php?op=killstr>`@Si</a></big></big>`n`n`n",true);
    addnav("","municipio.php?op=killstr");
}
if ($_GET['op'] == "killstr"){
    savesetting("striscione_testo"," ");
    savesetting("striscione_sindaco","no");
    savesetting("striscione_acctid","0");
}
//Excalibur: fine cancellazione striscione Sindaco

//Festa, Sook, 1° parte (impostazione data)
$festa=0;
$data = date("m-d");
$datadomani = date("m-d", mktime(0,0,0,date("m"),date("d")+1));
$data2g = date("m-d", mktime(0,0,0,date("m"),date("d")+2));
$data3g = date("m-d", mktime(0,0,0,date("m"),date("d")+3));
//$datafesta = "03-13";
if ($data==getsetting("festa","no") /*OR $data==$datafesta*/) $festa=1;
if ($datadomani==getsetting("festa","no") /*OR $datadomani==$datafesta*/) $festa=2;
if ($data2g==getsetting("festa","no") /*OR $data2g==$datafesta*/) $festa=3;
if ($data3g==getsetting("festa","no") /*OR $data3g==$datafesta*/) $festa=4;

if ($festa==1) output("<big>`n`n`c`b`^FESTA COMUNALE DI RAFFLINGATE`b`c`n`0</big>
    `c`(Il sindaco ha organizzato oggi una festa comunale, corri a scoprire quali piacevoli sorprese ti stanno attendendo...`c`0`n`n",true);
if ($festa==2) output("<big>`n`n`c`b`^FESTA COMUNALE DI RAFFLINGATE`b`c`n`0</big>
    `c`(Il sindaco ha organizzato per domani una festa comunale`c`0`n`n",true);
if ($festa==3) output("<big>`n`n`c`b`^FESTA COMUNALE DI RAFFLINGATE`b`c`n`0</big>
    `c`(Fra 2 giorni per volere del sindaco si terrà una festa comunale`c`0`n`n",true);
if ($festa==4) output("<big>`n`n`c`b`^FESTA COMUNALE DI RAFFLINGATE`b`c`n`0</big>
    `c`(Fra 3 giorni per volere del sindaco si terrà una festa comunale`c`0`n`n",true);

//Fine festa

$scaglie_ferro = getsetting("scagliemetallo",0);
$scaglie_rame = getsetting("scaglierame",0);
$carbone = getsetting("carbone",0);
$argento = getsetting("argento",0);
$oro = getsetting("oro",0);
$sferro = getsetting("sferro",0);
$srame = getsetting("srame",0);
$scarbone = getsetting("scarbone",0);
$sargento = getsetting("sargento",0);
$soro = getsetting("soro",0);
$val_scaglie_metallo = 10*(1000-(10*$scaglie_ferro));
$val_scaglie_rame = 10*(2200-(10*$scaglie_rame));
$val_carbone = 10*(600-(10*$carbone));
$val_argento = 10*(5000-(10*$argento));
$val_oro = 10*(8400-(10*$oro));
if ($val_scaglie_metallo < 10) $val_scaglie_metallo=10;
if ($val_carbone < 10) $val_carbone=10;
if ($val_scaglie_rame < 10) $val_scaglie_rame=10;
if ($val_argento < 100) $val_argento=100;
if ($val_oro < 500) $val_oro=500;
if ($_GET['op']=='slogan') {
    if(getsetting("striscione_acctid",0)!=$session[user][acctid]){
        addnav("Fai offerta","municipio.php?op=slogan_offerta");
        output("Il nano prosegue \"`#Al momento l'offerta maggiore per lo striscione dei prossimi 4 giorni è di".getsetting("striscione_offerta",0)." pezzi d'oro.`0\"`n`n");

    }else{
        output("Il nano dice \"`#Al momento l'offerta maggiore è la tua.`0\"`n`n");

    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="slogan_offerta"){
    if($_GET[az]=='offerta'){
        if($_POST[offerta]>getsetting("striscione_offerta",0)){

            if($session[user][gold]>=$_POST[offerta]){
                $session[user][gold]-=$_POST[offerta];
                savesetting("oro_tasse",($_POST[offerta]+getsetting("oro_tasse",'0')));
                output("Il nano dice \"`#Allora inizio a preparare il tuo striscione tra poco lo vedrai appeso!\"`n`n");
                savesetting("striscione_offerta",$_POST[offerta]);
                savesetting("striscione_acctid",$session[user][acctid]);
                savesetting("striscione_testo",$_POST[testo]);
            }else{
                output("Il nano dice \"`#Non hai abbastanza soldi ... non fare il furbo!!\"`n`n");
            }
        }else{
            output("Il nano dice \"`#Ma hai offerto meno soldi.... maaahhh  ... non fare il furbo!!\"`n`n");
        }
    }else{
        output("<form action='municipio.php?op=slogan_offerta&az=offerta' method='POST'>",true);
        addnav("","municipio.php?op=slogan_offerta&az=offerta");
        output("`bPezzi d'oro: <input name='offerta' value='".getsetting("striscione_offerta",0)."' maxlength='10' size='6'>`n
        `bTesto striscione : <input name='testo' maxlength='50' size='35'>`n
        <input type='submit' class='button' value='Offri'>",true);
        output("</form>",true);
    }
    addnav("Torna all'entrata","municipio.php");

}elseif($_GET['op']=="nuovi"){
    if($session['user']['cittadino']=="Si"){
        output("Una anziana signora dall'aria stanca ti dice \"`#Sei gia un cittadino se vuoi informazioni vai allo sportello dei cittadini.`0\"`n`n");
/*    }elseif ($session['user']['dragonkills'] > 0){
        output("Un'anziana signora dall'aria stanca ti chiede \"`#Vuoi diventare un cittadino di RafflinGate ?`0\".`n`n");
        addnav("Diventa cittadino","municipio.php?op=diventa_cittadino");
*/  }else {
        output("Un'anziana signora dall'aria stanca ti chiede \"`#Vuoi diventare un cittadino di RafflinGate ?`0\"`n`n");
        addnav("Diventa cittadino","municipio.php?op=diventa_cittadino");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="diventa_cittadino"){
    if($session['user']['evil']>75){
        output("Lo sceriffo che stava passando in municipio per caso si avvicina alla signora e gli sussurra qualche cosa!`n La signora riprende a parlare \"`#Lo sceriffo dice di non farti diventare cittadino, ti sta tenendo d'occhio, riga dritto e poi ritorna da me!`0\"`n`n");
    }else{
        output("La signora riprende a parlare \"`#Diventare un cittadino di Rafflingate, offre alcuni vantaggi. Potrai accedere a diversi servizi speciali e costa 500 pezzi d'oro che pagherai assieme alle tasse decise dal sindaco, tasse che attualmente sono : ".getsetting("tasse",'2')." pezzi d'oro al giorno.`0\"`n`n");
        addnav("Richiedi cittadinanza","municipio.php?op=richiedi_cittadinanza");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="richiedi_cittadinanza"){
      if($session[user][gold]>=500){
        output("La signora inizia a compilare dei moduli molto complessi, poi ti passa il documento e dice:  \"`#Devi firmare li!`0\"`n`n");
        addnav("Firma","municipio.php?op=firma");
        //addnav("Torna all'entrata","municipio.php");
    }else output("La signora ti guarda perplessa e vedendo che con te non hai i soldi necessari torna indispettita al suo lavoro.`n`n");
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="firma"){
    output("Firmi il documento e lo restituisci alla signora:  \"`#Bene da ora sei un cittadino di RafflinGate!!`0\"`n`n");
    debuglog("paga 500 monete per diventare cittadino");
    $session['user']['cittadino']="Si";
    //Barik commento da 122 a 125 e lascio la query sotto...accreditate 2 volte le 500 monete
    /*$cittadinoprice = 500;
    $session['user']['gold']-=$cittadinoprice;
    $sql = "UPDATE accounts SET gold=gold-$cittadinoprice WHERE acctid = ".$session[user][acctid];
    db_query($sql) or die(sql_error($sql));*/
    addnav("Torna all'entrata","municipio.php");
    $sqli = "INSERT INTO tasse (acctid,oro) VALUES ('".$session['user']['acctid']."','500')";
    $resulti=db_query($sqli);
}elseif($_GET['op']=="cittadini"){
    if($session['user']['cittadino']=="Si"){
        output("Ti rechi allo sportello dei cittadini, un giovane elfo dice:  \"`#Cosa posso fare per lei ?`0\"`n`n");
        addnav("Tasse","municipio.php?op=tasse");
        addnav("Tributi","municipio.php?op=tributi");
        addnav("Rinuncia cittadinanza","municipio.php?op=rinuncia");
        addnav("Approvazione sindaco","municipio.php?op=approvazione");
        addnav("Voce del popolo","municipio.php?op=voce");
    }else{
        output("Ti rechi allo sportello dei cittadini, un giovane elfo dice:  \"`#Questo è lo sportello per i cittadini, tu non lo sei, vai allo sportello dei nuovi arrivi !`0\"`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="tributi"){
    if($session['user']['gold']>=500){
        output("Un vecchio orco dice:  \"`#hohoho io racciolgio i trebuti pel cindraco, pui trebutare cinquicento ");
        addnav("Versa 500 oro tributo","municipio.php?op=versa_tributo&op1=500");
        if($session['user']['gold']>=1000){
            output("milla ");
            addnav("Versa 1000 oro tributo","municipio.php?op=versa_tributo&op1=1000");
        }
        if($session['user']['gold']>=2500){
            output("o dumilecenqecinti ");
            addnav("Versa 2500 oro tributo","municipio.php?op=versa_tributo&op1=2500");
        }
    output("ori pre volt vi beni ?`0\"`n`n");
    }
    if($session['user']['gold']<500){
        output("Un vecchio orco dice:  \"`#hohoho tunò ha manco cenqecinti ori tu poveri pù di orczo hahahahaha.`0\"`n`n");
    }

    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="versa_tributo"){
    $tributo = $_GET['op1'];
    $session['user']['gold']-=$tributo;
    savesetting("oro_tasse",($tributo+getsetting("oro_tasse",'0')));
    debuglog("ha versato ".$tributo." oro come tributo in municipio");
    $caso = e_rand(1,100);
    output("`n`6Il sindaco ti ringrazia sentitamente per il tuo gesto!`n`n");
    if($caso<16 AND $tributo==2500){
        $session['user']['evil']--;
        output("`n`6La fortuna ti sorride ora ti senti meno cattivo!`n`n");
        debuglog("ha perso un punto cattiveria grazie al tributo in municipio");
    }
    addnav("Torna all'entrata","municipio.php");
    addnav("Sportello Tributi","municipio.php?op=tributi");
}elseif($_GET['op']=="voce"){
    $sql = "SELECT * FROM municipio_voce WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if(!$row[voce]){
        output("`n`#Attualmente tu stai non stai richiedendo nulla!`n`n`n");
    }else{
        output("`n`6Attualmente tu stai richiedendo : `#$row[voce]`n`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
    output("`n`@Seleziona quello che ritieni necessario per Rafflingate : ");
    $richiesta=array('Seleziona','Letti','Pasti','Meno tasse','Picconi','Accette');
    output("<form action='municipio.php?op=cambia_voce' method='POST'>",true);
    addnav("","municipio.php?op=cambia_voce");
    output("<select name=voce>
    <option value=''>Seleziona</option>
    <option value='Letti'>Letti</option>
    <option value='Pasti'>Pasti</option>
    <option value='Meno tasse'>Meno tasse</option>
    <option value='Picconi'>Picconi</option>
    <option value='Accette'>Accette</option>
    <option value='Festa'>Festa</option>",true);
    if(moduli('caserma')=='on') output("<option value='Guerra'>Guerra</option>",true);
    output("</select>",true);
    output("<input type='submit' class='button' value='Imposta'>",true);
    output("</form>",true);
}elseif($_GET['op']=="cambia_voce"){
    $sql = "SELECT * FROM municipio_voce WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if(!$row['acctid']){
        $sqli = "INSERT INTO municipio_voce (acctid,voce) VALUES ('".$session['user']['acctid']."','".$_POST['voce']."')";
        $resulti=db_query($sqli);
    }else{
        $sqlupdate = "UPDATE municipio_voce SET voce = '".$_POST['voce']."' WHERE acctid=".$session['user']['acctid'];
        db_query($sqlupdate) or die(db_error(LINK));
    }
    if(!$_POST[voce]){
        output("`n`#Non richiedi nulla!n`n`n");
    }else{
        output("`n`@Ora richiedi :`!$_POST[voce]`n`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="approvazione"){
    $sql = "SELECT * FROM municipio_approvazione";
    $result = db_query($sql) or die(db_error(LINK));
    $tot=db_num_rows($result);
    $sql = "SELECT * FROM municipio_approvazione WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if(!$row[approva])  {
        $sqli = "INSERT INTO municipio_approvazione (acctid,approva) VALUES ('".$session['user']['acctid']."','No')";
        $resulti=db_query($sqli);
    }
    $sql = "SELECT * FROM municipio_approvazione WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`n`6Attualmente tu stai approvando il sindaco : `#$row[approva]`n`n`n");
    $sql = "SELECT count(acctid) AS tota FROM municipio_approvazione WHERE approva='Si'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $tota=$row[tota];
    $sql = "SELECT count(acctid) AS totn FROM municipio_approvazione WHERE approva='No'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $totn=$row[totn];
    $pera=round($tota/$tot*100,1);
    savesetting("approvazione_sindaco",$pera);
    $pern=round($totn/$tot*100,1);
    output("`n`4 Approvato da  : `@$tota`v cittadini ( $pera % )`n`n");
    output("`n`@ Non approvato da  : `\$$totn`v cittadini ( $pern % )`n`n");
    addnav("Approva","municipio.php?op=approva");
    addnav("Non approvare","municipio.php?op=nonapprova");
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="approva"){
    $sqlupdate = "UPDATE municipio_approvazione SET approva = 'Si' WHERE acctid=".$session['user']['acctid'];
    db_query($sqlupdate) or die(db_error(LINK));
    output("`n`4 Ora approvi il sindaco`n`n");
    addnav("Torna all'entrata","municipio.php");
    $sql = "SELECT * FROM municipio_approvazione";
    $result = db_query($sql) or die(db_error(LINK));
    $tot=db_num_rows($result);
    $sql = "SELECT count(acctid) AS tota FROM municipio_approvazione WHERE approva='Si'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $tota=$row[tota];
    $pera=round($tota/$tot*100,1);
    savesetting("approvazione_sindaco",$pera);
}elseif($_GET['op']=="nonapprova"){
    $sqlupdate = "UPDATE municipio_approvazione SET approva = 'No' WHERE acctid=".$session['user']['acctid'];
    db_query($sqlupdate) or die(db_error(LINK));
    output("`n`3 Ora non approvi il sindaco`n`n");
    addnav("Torna all'entrata","municipio.php");
    $sql = "SELECT * FROM municipio_approvazione";
    $result = db_query($sql) or die(db_error(LINK));
    $tot=db_num_rows($result);
    $sql = "SELECT count(acctid) AS tota FROM municipio_approvazione WHERE approva='Si'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $tota=$row[tota];
    $pera=round($tota/$tot*100,1);
    savesetting("approvazione_sindaco",$pera);
}elseif($_GET['op']=="tasse"){
    $sql = "SELECT * FROM tasse WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if($row[oro]>0){
        output("Il giovane elfo estrae un tabulato e con una matita segna dei numeri effettua dei conti e poi dice:  \"`#Lei deve pagare `\$".$row['oro']." `#monete d'oro!`0\"`n`n");
        output("<form action='municipio.php?op=paga_tasse' method='POST'>",true);
        addnav("","municipio.php?op=paga_tasse");
        output("`bPezzi d'oro che vuoi pagare `b`i(digita 0 o niente per pagare il massimo)`i`b:`b <input name='tasse' maxlength='7' size='6'>
        <input type='submit' class='button' value='Paga'>",true);
        output("</form>",true);
    } else {
        output("Il giovane elfo estrae un tabulato e con una matita segna dei numeri effettua dei conti e poi dice:  \"`#Vedo che lei è a posto con le tasse. Bravo, continui così!`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="paga_tasse"){
    if ($_POST[tasse] >= 0) {
        $sql = "SELECT * FROM tasse WHERE acctid=".$session['user']['acctid'];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($_POST[tasse]==0) $_POST[tasse] = min ($session['user']['gold'], $row['oro']);
        if($_POST[tasse]>$session[user][gold]){
            output("Il giovane elfo dice : \"`#Sembra che nun abbia i soldi!`0\"`n`n");
        }else{
            $pagato=$_POST[tasse];
            if($pagato>$row[oro]){
                output("Il giovane elfo dice : \"`6Sono troppi soldi! Non sei molto sveglio in matematica!`0\"`n`n");
                $pagato=$row[oro];
            }
            output("Il giovane elfo prende i soldi ( $pagato ) che devi al municipio e dice : \"`#Ben fatto!`0\"`n`n");
            if($pagato>=$row[oro])unset($session['bufflist']['esattore']);
            $session[user][gold]-=$pagato;
            $da_pagare=$row[oro]-$pagato;
            debuglog("paga $pagato oro di tasse al municipio");
            $sqlupdate = "UPDATE tasse SET oro = '".$da_pagare."' WHERE acctid=".$session['user']['acctid'];
            db_query($sqlupdate) or die(db_error(LINK));
            savesetting("oro_tasse",($pagato+getsetting("oro_tasse",'0')));
        }
        addnav("Torna all'entrata","municipio.php");
    } else {
        output("`#Furbetto !!!`n");
        debuglog("Ha fatto il furbo mettendo valore negativo nel pagare tasse!");
        addnav("Tasse","municipio.php?op=tasse");
    }
}elseif($_GET['op']=="rinuncia"){
    output("Il giovane elfo ti guarda stralunato e dice:  \"`#Costa `$250 `#monete d'oro sbrigare la pratica.`0\"`n`n");
    if($session[user][gold]>=$row[oro])addnav("Conferma rinuncia","municipio.php?op=conferma_rinuncia");
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="conferma_rinuncia"){
    $sql = "SELECT * FROM tasse WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if($row[oro]>0){
        output("Il giovane elfo estrae un elenco di nomi, ti guarda e dice :  \"`#Non fare il furbo, paga le tasse che devi, poi sarai libero!`0\"`n`n");
        addnav("Torna all'entrata","municipio.php");
    }else{
        if($session[user][gold]>=250){
            output("Il giovane elfo estrae un elenco di nomi e con una matita segna il tuo in fondo:  \"`#Fatto ora sei libero!`0\"`n`n");
            $session[user][gold]-=250;
            debuglog("paga 250 oro per rinunciare alla cittadinanza");
            db_query("DELETE FROM tasse WHERE acctid=".$session['user']['acctid']);
            $session['user']['cittadino']="No";
        }else{
            output("Il giovane elfo dice:  \"`#Sceriffoooo questoooo quìiii faaaa il furboooo non vuoleeee pagareeeeeee!!!`0\"`n`n");
        }
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="tutore"){
    // il premio va ritirato entro la reincarnazione del newby
    if($session['user']['cittadino']=="Si" AND ($session['user']['dragonkills']>9 OR $session['user']['reincarna']>0)){
        $sql = "SELECT * FROM tutore WHERE tutor_id='".$session['user']['acctid']."' AND tutor_pagato='No'";
        $result = db_query($sql);
        if(db_num_rows($result)==0){
            output("Un anziano umano ti guarda, e dice:  \"`6Bene cittadino, allora vuoi diventare un tutore?`0\"`n`n");
            addnav("Si","municipio.php?op=si_tutore");
        }else{
            $row = db_fetch_assoc($result);
            $sql = "SELECT name,dragonkills FROM accounts WHERE acctid=".$row['newbi_id'];
            $result = db_query($sql);
            $rown = db_fetch_assoc($result);
            if($row['accettato']=='No'){
                output("L'anziano umano ti dice:  \"`6Sei il tutore di $rown[name]`6 ma ancora non ti ha accettato come tale!`0\"`n`n");
                output("L'anziano prosegue:  \"`6Se vuoi puoi rinunciare e poi potrai cercare un nuovo discepolo!`0\"`n`n");
                addnav("Rinuncia","municipio.php?op=rinuncia_tutor&id=$row[id]");
            }else{
                if($row['newbi_pagato']=='No'){
                    output("L'anziano umano ti dice:  \"`6Sei il tutore di $rown[name]`6 ma ancora non ha completato il suo percorso di crescita, aiutalo un po!!! Deve uccidere ancora ".(5-$rown['dragonkills'])." volte il `@Drago Verde`6 per terminare il suo corso!`0\"`n`n");
                    addnav("Rinuncia","municipio.php?op=rinuncia_tutor&id=".$row['id']);
                }else{
                    output("L'anziano umano ti dice:  \"`6Complimenti sei il tutore di $rown[name]`6 e ora può stare in piedi sulle sue gambe senza avere bisogno del tuo aiuto! Per il lavoro che hai fatto la comunità ti ripaga con 10.000 pezzi d'oro e 10 gemme!`0\"`n`n");
                    $session['user']['gold']+=10000;
                    $session['user']['gems']+=10;
                    $sqlu = "UPDATE tutore SET tutor_pagato='Si' WHERE id=".$row['id'];
                    db_query($sqlu) or die(db_error(LINK));
                    debuglog("Riceve il premio per aver fatto da tutore a ".$rown['name']);
                    $sqld = "DELETE FROM tutore WHERE newbi_pagato='Si' AND tutor_pagato='Si'";
                    db_query($sqld) or die(db_error(LINK));
                }
            }
        }
    }elseif($session['user']['cittadino']=="Si" AND $session['user']['reincarna']==0){
        $sql = "SELECT * FROM tutore WHERE newbi_id='".$session['user']['acctid']."'";
        $result = db_query($sql);
        if(db_num_rows($result)==0){
            output("Un anziano umano ti guarda, e dice:  \"`6Corri giovane finchè hai l'energia ... corriiii ......?`0\"`n`n");
        }else{
            $row = db_fetch_assoc($result);
            $sql = "SELECT name FROM accounts WHERE acctid=".$row['tutor_id'];
            $result = db_query($sql);
            $rown = db_fetch_assoc($result);
            if($row['accettato']=='No' AND $session['user']['dragonkills']<2){
                output("L'anziano umano ti dice:  \"`6Il tuo tutore è $rown[name]`6, ma non lo hai ancora accettato come tale, vuoi diventare il suo allievo?`0\"`n`n");
                addnav("Si","municipio.php?op=accetta_tutore&id=$row[id]");
                addnav("No","municipio.php?op=rifiuta_tutore&id=$row[id]");
            }else{
                if($row['newbi_pagato']=='No'){
                    if($session['user']['dragonkills']>4 AND $session['user']['reincarna']==0){
                        output("L'anziano umano ti dice:  \"`6Complimenti hai completato il tuo percorso ecco una bella ricompensa!!!`0\"`n`n");
                        $session['user']['gold']+=30000;
                        $session['user']['gems']+=10;
                        $session['user']['experience']+=(100*$session['user']['level']);
                        $sqlu = "UPDATE tutore SET newbi_pagato='Si' WHERE id='".$row['id']."'";
                        db_query($sqlu) or die(db_error(LINK));
                        debuglog("Riceve il premio per aver completato il tutorato con ".$rown['name']);
                        $sqld = "DELETE FROM tutore WHERE newbi_pagato='Si' AND tutor_pagato='Si'";
                        db_query($sqld) or die(db_error(LINK));
                    }else{
                        output("`2Devi uccidere ancora `6".(5-$session['user']['dragonkills'])." `2volte il `@Drago Verde `2per terminare la tua missione.`n`n");
                        output("`2Ti ricordo che il tuo tutore è `^".$rown['name'].".`n`n");
                    }
                }else{
                    output("L'anziano umano ti dice:  \"`6Complimenti hai completato il percorso d'apprendimento con il tuo tutore $rown[name]!`0\"`n`n");
                }
            }
        }
    }else{
        output("Un anziano umano ti guarda, sospira e dice:  \"`6Solo i cittadini che hanno ucciso almeno 10 volte il `@Drago Verde`6 possono divetare tutori!`0\"`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="accetta_tutore"){
    $sql = "SELECT * FROM tutore WHERE id='".$_GET[id]."'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $sql = "SELECT name FROM accounts WHERE acctid=".$row['tutor_id'];
    $result = db_query($sql);
    $rown = db_fetch_assoc($result);
    output("Un anziano umano ti guarda, sospira e dice:  \"`6Bene hai accettato $rown[name]`6 come tuo tuo tutore!`0\"`n`n");
    $sqlu = "UPDATE tutore SET accettato='Si' WHERE id='".$_GET[id]."'";
    db_query($sqlu) or die(db_error(LINK));
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="rifiuta_tutore"){
    $sql = "SELECT * FROM tutore WHERE id='".$_GET[id]."'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $sql = "SELECT name FROM accounts WHERE acctid=".$row['tutor_id'];
    $result = db_query($sql);
    $rown = db_fetch_assoc($result);
    output("Un anziano umano ti guarda, sospira e dice:  \"`6Hai rifiutato $rown[name]`6 come tuo tuo tutore!`0\"`n`n");
    db_query("DELETE FROM tutore WHERE id='".$_GET[id]."'");
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="rinuncia_tutor"){
    output("L'anziano umano ti guarda, sospira e dice:  \"`6Sei certo della decisione di abbandonare?`0\"`n`n");
    addnav("`@No","municipio.php");
    addnav("`\$Si","municipio.php?op=rinuncia_tutore&id=".$_GET['id']);
}elseif($_GET['op']=="rinuncia_tutore"){
    $sql = "SELECT * FROM tutore WHERE id='".$_GET[id]."'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $sql = "SELECT name FROM accounts WHERE acctid=".$row['newbi_id'];
    $result = db_query($sql);
    $rown = db_fetch_assoc($result);
    output("Un anziano umano ti guarda, sospira e dice:  \"`6Hai abbandonato $rown[name]`6 al suo destino!`0\"`n`n");
    db_query("DELETE FROM tutore WHERE id='".$_GET[id]."'");
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="si_tutore"){
    output("Un anziano umano ti guarda, e dice:  \"`6Allora, devi sapere che diventare tutore di un giovane del nostro villaggio è un impegno importante, al giovane che prenderai in custodia dovrai insegnerai tutto quello che sai e nei limiti del possibile aiutarlo, per questo verrai ricompensato profumatamente! `0\"`n`n");
    output("`6Vuoi ancora farti carico di questo importante compito ? `0\"`n`n");
    addnav("Si","municipio.php?op=sisi_tutore");
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="sisi_tutore"){
    output("L'anziano umano dice:  \"`6Bene sei proprio determinato, allora dimmi chi è il giovane al quale vuoi fare da tutore? `0\"`n`n");
    output("<form action='municipio.php?op=cerca_tutore' method='POST'>",true);
    addnav("","municipio.php?op=cerca_tutore");
    output("`bNome del giovane: <input name='name'> `n<input type='submit' class='button' value='Cerca'>",true);
    output("</form>",true);
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="cerca_tutore"){
    $search="%";
    for ($i=0;$i<strlen($_POST['name']);$i++){
        $search.=substr($_POST['name'],$i,1)."%";
    }
    $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search' AND dragonkills='0' AND reincarna='0'";
    $result = db_query($sql);
    output("L'anziano umano dice:  \"`6Seleziona il nome corretto di colui per cui vuoi diventare tutore\"`n`n");
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<a href='municipio.php?op=conferma_tutore&id={$row['acctid']}'>",true);
        output($row['name']);
        output("</a>`n",true);
        addnav("","municipio.php?op=conferma_tutore&id={$row['acctid']}");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="conferma_tutore"){
    $sql = "SELECT name FROM accounts WHERE acctid=".$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    output("L'anziano umano dice:  \"`6Bene sei diventato il tutore di $row[name]`6, ora manda il giovane quì da me che gli devo parlare.\"`n`n");
    $sql = "INSERT INTO tutore
              (tutor_id,newbi_id)
              VALUES('".$session['user']['acctid']."',
                '".$_GET[id]."')";
    db_query($sql) or die(db_error(LINK));
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="elezioni"){
    if(
        ($session['user']['cittadino']=="Si" AND $session['user']['dragonkills'] > 0)
        OR
        ($session['user']['cittadino']=="Si" AND $session['user']['reincarna'] > 0)
    ){
        $g_s=date('d');
        if ($g_s>4){
            output("Una guardia ti dice :  \"`6Oggi è il $g_s del mese le votazioni si tengono solamente tra il giorno 1 e il giorno 4 puoi solo candidarti!\"`n`n");
            addnav("Candidati", "municipio.php?op=candidati");
            addnav("Elenco candidati", "municipio.php?op=elenco_candidati");
        }else{
            output("Una guardia ti dice :  \"`6Oggi è il $g_s del mese, puoi andare a votare!\"`n`n");
            addnav("Vai a votare", "municipio.php?op=vota");
            addnav("Exit poll", "municipio.php?op=exitpoll");
        }
    }else{
        output("Una guardia ti dice :  \"`6Oggi è il $g_s del mese e si tengono le votazioni ..... ma tu non sei un cittadino che ha ucciso almeno una volta il drago ... sciò sciò ... via di quì!\"`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="candidati"){
    $sql = "SELECT acctid FROM elezione_candidati WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql);
    if(db_num_rows($result)>0){
        output("Una guardia ti dice :  \"`6Tu ti sei già candidato, sarai il prossimo sindaco me lo sento!\" `0e ti guarda di sbieco!`n`n");
    }else{
        output("Una guardia ti dice :  \"`6Così vuoi candidarti per la prossima elezione, bene bene, per poterti candidare alla prossima elezione devi pagare 5.000 pezzi d'oro!\" `0e ti guarda con uno sorrisetto fastidioso.`n`n");
        if($session[user][gold]>=5000){
            addnav("Paga 5000", "municipio.php?op=conferma_candidati");
        }
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="conferma_candidati"){
    savesetting("oro_tasse",(5000+getsetting("oro_tasse",'0')));
    $session['user']['gold']-=5000;
    output("La guardia prende i tuoi soldi e dice :  \"`6Ecco il futuro sindaco in bocca al lupo ".$session['user']['name']."!\" `0e si mette sugli attenti!`n`n");
    $sql = "INSERT INTO elezione_candidati
              (acctid,nome)
              VALUES('".$session['user']['acctid']."',
                '".$session['user']['login']."')";
    db_query($sql) or die(db_error(LINK));
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="elenco_candidati"){
    output("Su una grossa lavagna sono scritti con un gessetto i nomi dei candidati alla carica di sindaco.`0`n`n");
    $sql = "SELECT * FROM elezione_candidati";
    $result = db_query($sql);
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bNome candidato`b</td></tr>", true);
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>".$row['nome']."</td></tr>", true);
    }
    output("</table>`n", true);

    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="vota"){
    $sql = "SELECT * FROM elezione_votanti where acctid=".$session['user']['acctid'];
    $result = db_query($sql);
    if(db_num_rows($result)==0){
        $sql = "SELECT * FROM elezione_votanti where ip='".$session['user']['lastip']."' OR uniqueid='".$session['user']['uniqueid']."'";
        $result = db_query($sql);
        if(db_num_rows($result)==0){
            output("Su una grossa lavagna sono scritti con un gessetto i nomi dei candidati alla carica di sindaco.`0`n`n");
            output("Nome candidato - Numero voti - Vota `n");
            $sql = "SELECT * FROM elezione_candidati";
            $result = db_query($sql);
            output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bNome candidato`b</td><td>`bNumero voti`b</td><td>`bVota`b</td></tr>", true);
            if (db_num_rows($result) == 0) {
                output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
            }

            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                if ($row['name'] == $session['user']['name']) {
                    output("<tr bgcolor='#007700'>", true);
                } else {
                    output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
                }
                output("<td>".$row['nome']."</td>", true);
                output("<td>".$row['voti']."</td>", true);
                output("<td><a href='municipio.php?op=vota_candidato&id={$row['acctid']}'>`0Vota</a></td></tr>", true);

                addnav("","municipio.php?op=vota_candidato&id={$row['acctid']}");
            }
            output("</table>`n", true);
        }else{
            output("La guardia dice :  \"`6Da questo IP/ID hanno già votato purtroppo non puoi votare!`n`n");

        }
    }else{
        output("La guardia dice :  \"`6Hai già votato non fare il furbo!`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="vota_candidato"){
//Sook, inserito controllo per evitare che personaggi in multiaccount autorizzato possano votare lo stesso candidato
    $nullo=0;
    $sql0 = "SELECT * FROM allowmulti WHERE (acctid1='{$session['user']['acctid']}' OR acctid2='{$session['user']['acctid']}')";
    $result0 = db_query($sql0) or die(db_error(LINK));
    $countrow = db_num_rows($result0);
    for ($i=0; $i<$countrow; $i++){
        $row0 = db_fetch_assoc($result0);
        if ($row0[acctid1]==$session['user']['acctid']) {
            $target=$row0[acctid2];
        } else {
            $target=$row0[acctid1];
        }
        $sql1 = "SELECT acctid, votato FROM elezione_votanti WHERE acctid=".$target." AND votato = ".$_GET['id'];
        $result1 = db_query($sql1);
        if (db_num_rows($result1)>0) $nullo=1;
    }
    $sql = "SELECT * FROM elezione_candidati where acctid=".$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    if ($nullo==1) {
        output("`^Un personaggio in multiaccount autorizzato ha già votato per ".$row[nome]."`^!`n`nIl tuo voto è stato pertanto dichiarato nullo!!!`0`n`n");
        debuglog("vota all'elezione del Sindaco di Rafflingate ma questo voto è nullo per multiaccount autorizzato",$_GET['id']);
        $_GET['id']=0;
    } else {
        output("Hai votato per $row[nome].`0`n`n");
        debuglog("vota all'elezione del Sindaco di Rafflingate",$_GET['id']);
        $voti=$row[voti]+1;
        db_query("UPDATE elezione_candidati SET voti='$voti' WHERE acctid=".$_GET['id']);
    }
//fine controllo su multiaccount
    $sql = "INSERT INTO elezione_votanti
              (acctid,ip,uniqueid,votato)
              VALUES('".$session['user']['acctid']."','".$session['user']['lastip']."','".$session['user']['uniqueid']."','".$_GET['id']."')";
    db_query($sql) or die(db_error(LINK));
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="exitpoll"){
    output("Su una grossa lavagna sono scritti con un gessetto i nomi dei candidati alla carica di sindaco.`0`n`n");
    output("Nome candidato - Numero voti`n");
    $sql = "SELECT * FROM elezione_candidati order by voti";
    $result = db_query($sql);

    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bNome candidato`b</td><td>`bNumero voti`b</td></tr>", true);
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun giocatore trovato`0</td></tr>", true);
    }

    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['name'] == $session['user']['name']) {
            output("<tr bgcolor='#007700'>", true);
        } else {
            output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
        }
        output("<td>".$row['nome']."</td>", true);
        output("<td>".$row['voti']."</td></tr>", true);
    }
    output("</table>`n", true);

    if($session['user']['superuser'] > 2) {
        output("`n`n`7Controllo voti personaggi (solo per admin):`n`n");
        $sql = "SELECT acctid, votato FROM elezione_votanti";
        $result = db_query($sql);
        $countrow = db_num_rows($result);
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bElettore`b</td><td>`bPreferenza`b</td></tr>", true);
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&Nessun voto trovato`0</td></tr>", true);
        }
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $sql1 = "SELECT name FROM accounts WHERE acctid=".$row['acctid'];
            $result1= db_query($sql1);
            $countrow1 = db_num_rows($result1);
            for ($j=0; $j<$countrow1; $j++){
                $row1 = db_fetch_assoc($result1);    
            }
            if($row['votato']!=0) {
                $sql2 = "SELECT name FROM accounts WHERE acctid=".$row['votato'];
                $result2 = db_query($sql2);
                $countrow2 = db_num_rows($result2);
                for ($j=0; $j<$countrow2; $j++){
                    $row2 = db_fetch_assoc($result2);    
                }
            }else{
                $row2['name'] = "`&Voto Nullo";
            }
            output("<tr class='" . (($i) % 2?"trlight":"trdark") . "'>", true);
            output("<td>".$row1['name']."</td>", true);
            output("<td>".$row2['name']."</td></tr>", true);
        }
        output("</table>`n", true);
    }

    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="sindaco"){
    if(getsetting("sindaco",'')==$session['user']['acctid'] OR $session['user']['superuser']>2){
        output("La tua segretaria ti da il benvenuto in ufficio, oggi c'è molto da fare.`0`n`n");
        addnav("Cassaforte", "municipio.php?op=cassaforte");
        addnav("Tasse", "municipio.php?op=tasse_sindaco");
        addnav("Eventi", "municipio.php?op=eventi");
        addnav("Acquisti", "municipio.php?op=acquisti_sindaco");
        addnav("Servizi ai cittadini", "municipio.php?op=servizi_sindaco");
        addnav("Situazione Dormitorio","municipio.php?op=sindaco&op1=lista");
    }
    $sql = "SELECT name from accounts where acctid=".getsetting("sindaco","");
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    output("L'attuale sindaco è : $row[name]");
    addnav("Torna all'entrata","municipio.php");
    if(getsetting("sindaco",'')==$session['user']['acctid'] OR $session['user']['superuser']>2){
        output("`n`n`6Puoi mettere uno striscione in piazza `4(tra 24 ore verrà rimosso)`n`6
        <form action='municipio.php?op=slogan_sindaco' method='POST'>",true);
        addnav("","municipio.php?op=slogan_sindaco");
        output("`bTesto striscione : <input name='testo' maxlength='50' size='35'>`n
            <input type='submit' class='button' value='Imposta'>",true);
        output("</form>",true);
    }
    if($_GET['op1']=="lista"){
         $now = time();
         $divider = (60*60*24);
         $sql = "SELECT name,laston,acctid,login
                 FROM accounts
                 WHERE location = 3
                 AND '".date("Y-m-d H:i:s",strtotime(date("r")."-15 days"))."' > laston ORDER BY laston ASC LIMIT 50";
         $result = db_query($sql);
         output("<table cellspacing=2 cellpadding=2 align='center'>",true);
         output("<tr bgcolor='#FF0000'><td colspan=2 align='center'>`&<b>I più dormiglioni di Rafflingate</b>`0</td></tr>",true);
         output("<tr  bgcolor='#888888'><td align='center'><b>`&Nome</b>`0</td>
         <td align='center'><b>`&Giorni</b>`0</td><td><b>`&Op.</b>`0</td></tr>",true);
         $countrowkkk = db_num_rows($result);
         for ($kkk=0; $kkk<$countrowkkk; $kkk++){
         //for ($kkk=0; $kkk<db_num_rows($result);$kkk++){
              $row = db_fetch_assoc($result);
              output("<tr class='".($kkk % 2?"trlight":"trdark")."'>
                      <td align='center'>".$row['name']."</td>
                      <td align='right'>`@".intval(($now-strtotime($row['laston']))/$divider)."</td>
                      <td><a href=municipio.php?op=sindaco&op1=sloggia&acctid=".$row['acctid']."&nome=".$row['login'].">Sloggia</a></td>
                      </tr>",true);
              addnav("","municipio.php?op=sindaco&op1=sloggia&acctid=".$row['acctid']."&nome=".$row['login']);
         }
         output("</table>",true);
    }elseif($_GET['op1']=="sloggia"){
         output("`%Stai per scacciare dal dormitorio `@".$_GET['nome']."`%, sei sicuro?`n`n");
         output("<a href=municipio.php?op=sindaco&op1=sloggiasi&acctid=".$_GET['acctid']."&nome=".$_GET['nome'].">Si</a><br>",true);
         output("<a href=municipio.php?op=sindaco>No</a>",true);
         addnav("","municipio.php?op=sindaco&op1=sloggiasi&acctid=".$_GET['acctid']."&nome=".$_GET['nome']);
         addnav("","municipio.php?op=sindaco");
    }elseif($_GET['op1']=="sloggiasi"){
         savesetting("letti_usati",getsetting("letti_usati",0)-1);
         $sql = "UPDATE accounts SET location=0 WHERE acctid = ".$_GET['acctid'];
         db_query($sql) or die(sql_error($sql));
         output("`\$Hai appena scacciato dal dormitorio `&".$_GET['nome']." `\$!!!!`n`n");
    }
}elseif($_GET['op']=="slogan_sindaco"){
    output("`6Il nano responsabile degli striscioni, inizia a prepararlo");
    savesetting("striscione_sindaco",$_POST[testo]);
    //$scadenza=time()+86400;
    $scadenza=strtotime(date("r"))+86400;
    savesetting("stri_sin_data",$scadenza);
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="acquisti_sindaco"){
    addnav("Pasti mensa", "municipio.php?op=acquista_pastimensa");
    addnav("Picconi", "municipio.php?op=acquista_picconi");
    addnav("Accette", "municipio.php?op=acquista_accette");
    addnav("Letti", "municipio.php?op=acquista_letti");
    if (getsetting("festa","no")=="no") addnav("Organizza una festa", "municipio.php?op=festa");
    if(moduli('caserma')=='on' AND getsetting("campagna_militare","off")=="off") addnav("Campagna militare", "municipio.php?op=campagna");
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    output("Puoi acquistare i seguenti materiali`n`n");
    output("Puoi acquistare 200 pasti a 5000 pezzi d'oro`n`n");
    output("Puoi acquistare 5 picconi a 40000 pezzi d'oro`n`n");
    output("Puoi acquistare 5 accette a 50000 pezzi d'oro`n`n");
    output("Puoi acquistare 5 letti a 10000 pezzi d'oro`n`n");
    if (getsetting("campagna_militare","off")=="off") output("`\$Puoi finanziare una campagna militare a 100000 pezzi d'oro`n`n`0");
    if (getsetting("festa","no")=="no") output("Puoi organizzare una festa comunale spendendo 200000 pezzi d'oro`n`n");
    output("Pasti disponibili ".getsetting("pasti_mensa",0)."`n`n");
    output("Letti disponibili ".getsetting("posti_letto",0)."`n`n");
    //output("Picconi disponibili ".getsetting("picconi_bazar",0)."`n`n");
    //output("Accette disponibili ".getsetting("accette_bazar",0)."`n`n");
}elseif($_GET['op']=="campagna"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    if(getsetting("oro_tasse",'0')>=100000){
        savesetting("campagna_militare","on");
        savesetting("oro_tasse",(getsetting("oro_tasse",'0')-100000));
        output("`\$Hai finanziato una campagna militare, avvisa i tuoi militari!");
        addnav("Torna all'entrata","municipio.php");
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="acquista_letti"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    if(getsetting("oro_tasse",'0')>=10000){
        savesetting("posti_letto",getsetting("posti_letto",0)+5);
        savesetting("oro_tasse",(getsetting("oro_tasse",'0')-10000));
        output("`\$Hai comprato 5 letti!");
        addnav("Torna all'entrata","municipio.php");
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="acquista_picconi"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    if(getsetting("oro_tasse",'0')>=40000){
        savesetting("oro_tasse",(getsetting("oro_tasse",'0')-40000));
        output("`\$Hai comprato 5 picconi!");
        addnav("Torna all'entrata","municipio.php");
        //savesetting("picconi_bazar",getsetting("picconi_bazar",0)+10);
        for ($oc = 0;$oc < 10;$oc++) {
            $pot = mt_rand(10,20);
            $potn = 0;
            $att = 0;
            $dif = 0;
            $turn = 0;
            $valorernd = mt_rand(2,6);
            $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+$valorernd;
            $usuraextra = e_rand(0,20);
            $durata = 50 + 20*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
            $livello = ceil($valore/30);
            $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=115 ORDER BY rand() LIMIT 1";
            $resultno = db_query($sqlno) or die(db_error(LINK));
            $rowno = db_fetch_assoc($resultno);
            $nome="Piccone da minatore";
            $desc="Piccone per lavorare in miniera";
            $resultno = db_query($sqlno) or die(db_error(LINK));
            $rowno = db_fetch_assoc($resultno);
            $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help, usura, usuramax, usuraextra)
                        VALUES ('{$nome}','{$desc}','1','1','$livello','$valore','$potn','$att','$dif','$turn', '$durata', '$durata', '$usuraextra')";
            db_query($sql);
        }
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="acquista_accette"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    if(getsetting("oro_tasse",'0')>=50000){
        savesetting("oro_tasse",(getsetting("oro_tasse",'0')-50000));
        output("`\$Hai comprato 5 accette!");
        addnav("Torna all'entrata","municipio.php");
        //savesetting("accette_bazar",getsetting("accette_bazar",0)+10);
        for ($oc = 0;$oc < 10;$oc++) {
            $pot = mt_rand(10,20);
            $potn = 0;
            $att = 0;
            $dif = 0;
            $turn = 0;
            $valorernd = mt_rand(4,10);
            $valore = ($pot*$potn)+($att*10)+($dif*10)+($turn*6)+$valorernd;
            $usuraextra = 50 + e_rand(0,20);
            $durata = 50 + 20*$valore + 100*$att + 100*$dif + 10*$turn + $usuraextra;
            $livello = ceil($valore/30);
            $sqlno = "SELECT * FROM  oggetti_nomi where serbatoio=116 ORDER BY rand() LIMIT 1";
            $resultno = db_query($sqlno) or die(db_error(LINK));
            $rowno = db_fetch_assoc($resultno);
            $nome="Ascia da boscaiolo";
            $desc="Ascia per abbattere gli alberi";
            $resultno = db_query($sqlno) or die(db_error(LINK));
            $rowno = db_fetch_assoc($resultno);
            $sql="INSERT INTO oggetti (nome, descrizione, dove, dove_origine, livello, valore, potenziamenti,attack_help,defence_help,turns_help, usura, usuramax, usuraextra)
                        VALUES ('{$nome}','{$desc}','1','1','$livello','$valore','$potn','$att','$dif','$turn', '$durata', '$durata', '$usuraextra')";
            db_query($sql);
        }
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="acquista_pastimensa"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    if(getsetting("oro_tasse",'0')>=5000){
        savesetting("pasti_mensa",getsetting("pasti_mensa",0)+200);
        savesetting("oro_tasse",(getsetting("oro_tasse",'0')-5000));
        output("`\$Hai comprato 200 pasti!");
        addnav("Torna all'entrata","municipio.php");
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
    //Festa, Sook, 2°parte (organizzazione festa)
}elseif($_GET['op']=="festa"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    if(getsetting("oro_tasse",'0')>=200000){
        $datafesta = date("m-d", mktime(0,0,0,date("m"),date("d")+3));
        savesetting("festa",$datafesta);
        savesetting("oro_tasse",(getsetting("oro_tasse",'0')-200000));
        output("`\$La festa sarà organizzata fra 3 giorni!");
        addnav("Torna all'entrata","municipio.php");
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
    //fine festa
}elseif($_GET['op']=="cassaforte"){
    $tt=getsetting("oro_tasse",'0');
    output("Il cassiere ti dice che nella cassaforte ci sono `6$tt `0pezzi d'oro.`0`n`n");
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="tasse_sindaco"){
    $sql = "SELECT SUM(oro) AS tot from tasse";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    output("I cittadini devono versare `6$row[tot] `0pezzi d'oro nelle casse del municipio, non credi sia ora di farli pagare ?`n`n");
    if($_GET[az]=='cambia_tasse'){
        if (number_format($_POST[tasse],0,'.','')!=$_POST[tasse] OR number_format($_POST[esattore],0,'.','')!=$_POST[esattore]) {
            $_POST[tasse]=11;
            $_POST[esattore]=11;
        }
        if(($_POST[tasse]>=1 AND $_POST[tasse]<=10) AND ($_POST[esattore]>=50 AND $_POST[esattore]<=5000 OR $_POST[esattore]==0)){
            savesetting("tasse",$_POST[tasse]);
            savesetting("esattore",$_POST[esattore]);
            savesetting("blocco_int_evas",$_POST[interessi]);
            output("`@Nuovi valori impostati`0`n`n");
        }else{
            output("`\$Un valore impostato è errato`0`n`n");
        }
    }
    $tasse = getsetting("tasse",'2');
    $esattore = getsetting("esattore",'0');
    $interessi = getsetting("blocco_int_evas",0);
    output("Attualmente hai impostato le tasse a `5$tasse `0pezzi d'oro al giorno.`n");
    if ($esattore>0) {
        output("L'esattore ha ordine di occuparsi di chi ha un debito col fisco superiore a `5$esattore `0pezzi d'oro.`n`n");
    } else {
        output("L'esattore ha ordine di non occuparsi dei debitori.`n`n");
    }
    if ($interessi>0) {
        output("I banchieri hanno l'ordine di non pagare interessi agli evasori.`n`n");
    } else {
        output("I banchieri possono pagare interessi agli evasori.`n`n");
    }
    output("<form action='municipio.php?op=tasse_sindaco&az=cambia_tasse' method='POST'>",true);
    addnav("","municipio.php?op=tasse_sindaco&az=cambia_tasse");
    output("`bNuovo livello tasse ( tra 1 e 10 monete giorno ): <input name='tasse' value='$tasse' maxlength='2' size='3'>`n
    Limite esattore ( 0, oppure tra 50 e 5000 monete di debito ): <input name='esattore' value='$esattore' maxlength='4' size='4'> (Con 0 l'esattore è disabilitato)`n
    I banchieri devono bloccare gli interessi agli evasori: <select name='interessi'><option value='0'".(getsetting("blocco_int_evas",0)=="0"?" selected":"").">No</option><option value='1'".(getsetting("blocco_int_evas",0)=="1"?" selected":"").">Sì</option></select>`n
    <input type='submit' class='button' value='Imposta'>",true);
    output("</form>",true);
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    addnav("Sfoglia il Registro Esattoriale","municipio.php?op=registro");
}elseif($_GET['op']=="registro"){
    output("`3Sfogli il `&Registro Esattoriale `3e controlli quanto devono i cittadini:`n`n");
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`b`cNome`c`b</td><td>`b`cTasse`c`b</td></tr>", true);
    $sql = "SELECT a.*,b.name FROM tasse a, accounts b WHERE b.cittadino='Si' AND b.superuser=0 AND a.acctid=b.acctid ORDER BY a.oro DESC, b.dragonkills DESC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("<tr><td colspan=4 align='center'>`&Nessun cittadino trovato`0</td></tr>", true);
    } else {
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'>", true);
            output("<td>".$row['name']."</td><td align='right'>`^".$row[oro]." `3Pezzi d'oro`0</td></tr>", true);
        }
    }
    output("`3</table>`n", true);
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    addnav("Torna alla gestione delle tasse", "municipio.php?op=tasse_sindaco");
}elseif($_GET['op']=="servizi_sindaco"){
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
    switch ($_GET[az]) {
        case "apridormitorio":
            output("`^Il dormitorio ora è `b`@APERTO`b");
            savesetting("dormitorio_aperto","si");
        break;
        case "chiudidormitorio":
            output("`^Il dormitorio ora è `b`\$CHIUSO`b");
            savesetting("dormitorio_aperto","no");
        break;
        case "aprimensa":
            output("`^La mensa ora è `b`@APERTA`b");
            savesetting("mensa_aperta","si");
        break;
        case "chiudimensa":
            output("`^La mensa ora è `b`\$CHIUSA`b");
            savesetting("mensa_aperta","no");
        break;
        case "":
            output("Puoi aprire o chiudere ai cittadini la mensa e il dormitorio");
            addnav("Opzioni");
            addnav("Apri Dormitorio","municipio.php?op=servizi_sindaco&az=apridormitorio");
            addnav("Chiudi Dormitorio","municipio.php?op=servizi_sindaco&az=chiudidormitorio");
            addnav("Apri Mensa","municipio.php?op=servizi_sindaco&az=aprimensa");
            addnav("Chiudi Mensa","municipio.php?op=servizi_sindaco&az=chiudimensa");
        break;
    }
}elseif($_GET['op']=="eventi_acquisto"){
    $sql = "SELECT * FROM eventi WHERE id='".getsetting("evento_sindaco",'0')."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    savesetting("relazione_eythgim",getsetting("relazione_eythgim",50)+$row[relazione]);
    savesetting("evento_sindaco","0");
    savesetting("oro_tasse",($row[premio]+getsetting("oro_tasse",'0')));
    savesetting("s".$row[obbiettivo],getsetting("s".$row[obbiettivo],0)-$row[obbiettivo_valore]);
    output("Complimenti hai completato la missione.`n",true);
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="eventi_tributo"){
    $sql = "SELECT * FROM eventi WHERE id='".getsetting("evento_sindaco",'0')."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    savesetting("oro_tasse",(getsetting("oro_tasse",'0')-$row[obbiettivo_valore]));
    output("Complimenti hai completato la missione.`n",true);
    savesetting("relazione_eythgim",getsetting("relazione_eythgim",50)+$row[relazione]);
    savesetting("evento_sindaco","0");
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="eventi_dono_accetta"){
    output("Complimenti hai completato la missione accettando.`n",true);
    $sql = "SELECT * FROM eventi WHERE id='".getsetting("evento_sindaco",'0')."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    savesetting("relazione_eythgim",getsetting("relazione_eythgim",50)+$row[relazione]);
    savesetting("evento_sindaco","0");
    savesetting("oro_tasse",($row[premio]+getsetting("oro_tasse",'0')));
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="eventi_dono_rifiuta"){
    output("Complimenti hai completato la missione rifiutando.`n",true);
    $sql = "SELECT * FROM eventi WHERE id='".getsetting("evento_sindaco",'0')."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    savesetting("relazione_eythgim",getsetting("relazione_eythgim",50)-(4*$row[relazione]));
    savesetting("evento_sindaco","0");
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="eventi"){
    if(getsetting("relazione_eythgim",'0')>100)savesetting("relazione_eythgim",100);
    $sql = "SELECT * FROM eventi WHERE id='".getsetting("evento_sindaco",'0')."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if(!$row[nome]){
        output("Nessun evento in corso.");
    }else{
        output("Evento attuale.`n`n");
        output(" $row[nome]`n");
        output("$row[descrizione]`n`n Oro in cassaforte : ".getsetting("oro_tasse",'0')."`n`n");
        if(getsetting("scadenza_evento_s",'0')>=0){
            output("Ti rimangono : ".getsetting("scadenza_evento_s",'0')." giorni per portare a termine la missione!`n`n");
        }
    }
    // inizio gestione eventi
    if ($row[tipo]=='acquisto'){
        if(getsetting("s$row[obbiettivo]",0)>=$row[obbiettivo_valore]){
            output("Materiale richiesto disponibile: <A href=municipio.php?op=eventi_acquisto>`bInvialo`b </a>`n",true);
            addnav("", "municipio.php?op=eventi_acquisto");
        }
    }
    if ($row[tipo]=='tributo'){
        if(getsetting("oro_tasse",0)>=$row[obbiettivo_valore]){
            output("Oro richiesto disponibile: <A href=municipio.php?op=eventi_tributo>`bInvialo`b </a>`n",true);
            addnav("", "municipio.php?op=eventi_tributo");
        }
    }
    if ($row[tipo]=='dono'){
        output("<A href=municipio.php?op=eventi_dono_accetta>`bAccetta`b </a>",true);
        output("<A href=municipio.php?op=eventi_dono_rifiuta>`bRifiuta`b </a>`n",true);
        addnav("", "municipio.php?op=eventi_dono_accetta");
        addnav("", "municipio.php?op=eventi_dono_rifiuta");
    }
    //fine gestione eventi
    // Inizio tabella Materiali
    addnav("", "municipio.php?op=comprasm");
    addnav("", "municipio.php?op=comprasr");
    addnav("", "municipio.php?op=compraca");
    addnav("", "municipio.php?op=compraar");
    addnav("", "municipio.php?op=compraor");
    output("`n`n<table cellspacing=0 cellpadding=2 align='center'>", true);
    output("<tr class='trhead' align='center'><td>`bMateriale`b</td><td>`bQuantità`b</td><td>`bValore oro 10 pezzi`b</td><td>Ops</td><td>Magazzino</td></tr>", true);
    // Ferro
    output("<tr class='trlight'><td>Scaglie di ferro</td><td>$scaglie_ferro</td><td>$val_scaglie_metallo</td>",true);
    output("<td><A href=municipio.php?op=comprasm>Compra </a>",true);
    output("<td align='center'>$sferro",true);
    output("</td></tr>", true);
    // Rame
    output("<tr class='trdark'><td>Scaglie di rame</td><td>$scaglie_rame</td><td>$val_scaglie_rame</td>",true);
    output("<td><A href=municipio.php?op=comprasr>Compra </a>",true);
    output("<td align='center'>$srame",true);
    output("</td></tr>", true);
    // Carbone
    output("<tr class='trlight'><td>Carbone</td><td>$carbone</td><td>$val_carbone</td>",true);
    output("<td><A href=municipio.php?op=compraca>Compra </a>",true);
    output("<td align='center'>$scarbone",true);
    output("</td></tr>", true);
    // Argento
    output("<tr class='trdark'><td>Argento</td><td>$argento</td><td>$val_argento</td>",true);
    output("<td>",true);
    output("<A href=municipio.php?op=compraar>Compra </a>",true);
    output("<td align='center'>$sargento",true);
    output("</td></tr>", true);
    // Oro
    output("<tr class='trlight'><td>Oro</td><td>$oro</td><td>$val_oro</td>",true);
    output("<td>",true);
    output("<A href=municipio.php?op=compraor>Compra </a>",true);
    output("<td align='center'>$soro",true);
    output("</td></tr>", true);
    output("</table>", true);
    // Fine tabella Materiali
    output ("`n`n");
    addnav("Torna all'ufficio","municipio.php?op=sindaco");
}elseif($_GET['op']=="compraca"){
    addnav("Eventi", "municipio.php?op=eventi");
    if (getsetting("oro_tasse",'0')>=$val_carbone){
        if ($carbone >= 10) {
            output("Oberon afferra i tuoi ".$val_carbone." pezzi d'oro e ti da 10 pezzi di carbone in cambio.`n`n");
            savesetting("oro_tasse",getsetting("oro_tasse",0)-$val_carbone);
            if (getsetting("carbone",0) - 10 < 10) {
                savesetting("carbone","0");
            } else {
                savesetting("carbone",getsetting("carbone",0)-10);
            }
            savesetting("scarbone",getsetting("scarbone",0)+10);
        } else {
            output("Purtroppo Oberon ha già venduto tutto il carbone che aveva, sei arrivato troppo tardi.`n");
            output("Il carbone è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con del carbone.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\"`n`n");
    }
}elseif($_GET['op']=="comprasr"){
    addnav("Eventi", "municipio.php?op=eventi");
    if (getsetting("oro_tasse",'0')>=$val_scaglie_rame){
        if ($scaglie_rame >= 10) {
            output("Oberon afferra i tuoi ".$val_scaglie_rame." pezzi d'oro e ti da 10 scaglie di rame in cambio.`n`n");
            savesetting("oro_tasse",getsetting("oro_tasse",0)-$val_scaglie_rame);
            if (getsetting("scaglierame",0) - 10 < 10) {
                savesetting("scaglierame","0");
            } else {
                savesetting("scaglierame",getsetting("scaglierame",0)-10);
            }
            savesetting("srame",getsetting("srame",0)+10);
        } else {
            output("Purtroppo Oberon ha già venduto tutto il rame che aveva, sei arrivato troppo tardi.`n");
            output("Il rame è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle scaglie di rame.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\"`n`n");
    }
}elseif($_GET['op']=="comprasm"){
    addnav("Eventi", "municipio.php?op=eventi");
    if (getsetting("oro_tasse",'0')>=$val_scaglie_metallo){
        if ($scaglie_ferro >= 10) {
            output("Oberon afferra i tuoi ".$val_scaglie_metallo." pezzi d'oro e ti da 10 scaglie di ferro in cambio.`n`n");
            savesetting("oro_tasse",getsetting("oro_tasse",0)-$val_scaglie_metallo);
            if (getsetting("scagliemetallo",0) - 10 < 10) {
                savesetting("scagliemetallo","0");
            } else {
                savesetting("scagliemetallo",getsetting("scagliemetallo",0)-10);
            }
            savesetting("sferro",getsetting("sferro",0)+10);
        } else {
            output("Purtroppo Oberon ha già venduto tutte le scaglie di ferro che aveva, sei arrivato troppo tardi.`n");
            output("Il ferro è abbastanza comune, non dovrebbe tardare ad arrivare qualche avventuriero con delle scaglie di ferro.`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\"`n`n");
    }
}elseif($_GET['op']=="compraar"){
    addnav("Eventi", "municipio.php?op=eventi");
    if (getsetting("oro_tasse",'0')>=$val_argento){
        if ($argento >= 10) {
            output("Oberon afferra i tuoi ".$val_argento." pezzi d'oro e ti da 10 scaglie d'argento in cambio.`n`n");
            savesetting("oro_tasse",getsetting("oro_tasse",0)-$val_argento);
            if (getsetting("argento",0) - 10 < 10) {
                savesetting("argento","0");
            } else {
                savesetting("argento",getsetting("argento",0)-10);
            }
            savesetting("sargento",getsetting("sargento",0)+10);
        } else {
            output("Purtroppo Oberon ha già venduto tutte le scaglie d'argento che aveva, sei arrivato troppo tardi.`n");
            output("L'argento è abbastanza raro non arriverà prima di qualche giorno!`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\"`n`n");
    }
}elseif($_GET['op']=="compraor"){
    addnav("Eventi", "municipio.php?op=eventi");
    if (getsetting("oro_tasse",'0')>=$val_oro){
        if ($oro >= 10) {
            output("Oberon afferra i tuoi ".$val_oro." pezzi d'oro e ti da 10 scaglie d'oro in cambio.`n`n");
            savesetting("oro_tasse",getsetting("oro_tasse",0)-$val_oro);
            if (getsetting("oro",0) - 1 < 10) {
                savesetting("oro","0");
            } else {
                savesetting("oro",getsetting("oro",0)-10);
            }
            savesetting("soro",getsetting("soro",0)+10);
        } else {
            output("Purtroppo Oberon ha già venduto tutte le scaglie d'oro che aveva, sei arrivato troppo tardi.`n");
            output("L'oro è molto raro non arriverà prima di qualche settimana!`n`n");
        }
    }else{
        output("Oberon accarezza la sua pesante mazza ferrata, ti guarda truce, e agitando l'indice sul tuo muso dice :\"Non fare il furbo con me !\"`n`n");
    }
}elseif($_GET['op']=="mensa"){
    if($session['user']['cittadino']=="Si"){
        $sql = "SELECT * FROM tasse WHERE acctid=".$session['user']['acctid'];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if($row[oro]>getsetting("esattore",0) AND getsetting("esattore",0)>0) $evasore=1;
        if($session['user']['evil']>75){
            output("Il vicesceriffo controlla la mensa non ti fa entrare la tua cattiveria è troppo alta .`n`n");
            addnav("Torna all'entrata","municipio.php");
        }elseif($evasore){
            output("L'esattore delle tasse controlla il corridoio che porta alla mensa, certamente pignorerà i tuoi soldi se ti vedrà pagare un pasto... Meglio girare al largo.`n`n");
            addnav("Torna all'entrata","municipio.php");
        }else{
            if (getsetting("mensa_aperta","si")=="si") {
                $pricebase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 10);
                addnav("Torna all'entrata","municipio.php");
                output("Dopo esserti accomodat".($session[user][sex]?"a":"o")." ad un tavolo afferri un menù e inizi a scorrerlo.`n`n");
                output("<big><big>`b`c`^-=-=-=MENU=-=-=-`c`b</big></big>",true);
                output("`c`6Pasti disponibili `6(".getsetting("pasti_mensa",0).") `c",true);
                if(getsetting("pasti_mensa",0)>1){
                    output("<a href=\"municipio.php?op=mensa_pasto\">`!`cPasto........".($pricebase+5)." Pezzi d'Oro`c</a>",true);
                }else{
                    output("`cNon ci sono pasti completi disponibili");
                }
                addnav("","municipio.php?op=mensa_pasto");

                $pasti=ceil($session['user']['hunger']/40);
                if($pasti>1){
                    if(getsetting("pasti_mensa",0)>$pasti){
                        output("<a href=\"municipio.php?op=mensa_pasto_completo&pasti=$pasti\">`@`cPasto completo ($pasti pasti)........".($pricebase+5)*$pasti." Pezzi d'Oro`c</a>",true);
                    }else{
                        output("`cNon ci abbastanza pasti per sfamarti completamente");
                    }
                    addnav("","municipio.php?op=mensa_pasto_completo&pasti=$pasti");
                }
            }else{
                addnav("Torna all'entrata","municipio.php");
                output("La mensa è chiusa oggi. Forse dovresti parlarne con il sindaco.`n`n");
            }
        }
    }else{
        output("Servizio riservato ai soli cittadini.`n`n");
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="mensa_pasto_completo"){
    $pricebase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 10);
    if ($session['user']['gold'] >= (($pricebase+5)*$_GET[pasti])){
        if ($session['user']['hunger']>-10){
            output("`#Divori i pasti in pochi secondi come un morto di fame ... ma `bSEI`b un".($session[user][sex]?"a":"o")." mort".($session[user][sex]?"a":"o")." di fame!`n");
            $session['user']['hunger']-=(40*$_GET[pasti]);
            $session['user']['gold']-=(($pricebase+5)*$_GET[pasti]);
            savesetting("oro_tasse",getsetting("oro_tasse",0)+(($pricebase+5)*$_GET[pasti]));
            addnav("Torna all'entrata","municipio.php");
            savesetting("pasti_mensa",getsetting("pasti_mensa",0)-$_GET[pasti]);
        }else{
            addnav("Torna all'entrata","municipio.php");
            output("`%Sei troppo pien".($session[user][sex]?"a":"o")." per poter ingollare anche una sola porzione!");
        }
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="mensa_pasto"){
    $pricebase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 10);
    if ($session['user']['gold'] >= ($pricebase+5)){
        if ($session['user']['hunger']>-10){
            output("`@Divori il pasto in pochi secondi come un povero affamato ... ma in fondo `bSEI`b un".($session[user][sex]?"a":"o")." pover".($session[user][sex]?"a":"o")." affamat".($session[user][sex]?"a":"o")."!`n");
            $session['user']['hunger']-=40;
            $session['user']['gold']-=($pricebase+5);
            savesetting("oro_tasse",getsetting("oro_tasse",0)+($pricebase+5));
            addnav("`^Ancora!", "municipio.php?op=mensa");
            addnav("Torna all'entrata","municipio.php");
            savesetting("pasti_mensa",getsetting("pasti_mensa",0)-1);
        }else{
            addnav("Torna all'entrata","municipio.php");
            output("`%Sei troppo pien".($session[user][sex]?"a":"o")." per poter ingollare anche una sola porzione!");
        }
    }else{
        output("`\$Non te lo puoi permettere!");
        addnav("Torna all'entrata","municipio.php");
    }
    /*
}elseif($_GET['op']=="bazar"){
    if($session['user']['cittadino']=="Si"){
    addnav("Torna all'entrata","municipio.php");
    output("Quà vendono alcuni oggetti solo per i cittadini.`n`n");
    output("<big><big>`b`c`^-=-=-=OGGETTI=-=-=-`c`b</big></big>",true);
    if(getsetting("picconi_bazar",0)>0){
    output("<a href=\"municipio.php?op=bazar_picconi\">`c `6(".getsetting("picconi_bazar",0).") `2Piccone........ 500 Pezzi d'Oro`c</a>",true);
    }else{
    output("`cNon ci sono picconi disponibili");
    }
    if(getsetting("accette_bazar",0)>0){
    output("<a href=\"municipio.php?op=bazar_accette\">`c `6(".getsetting("accette_bazar",0).") `2Accetta........ 500 Pezzi d'Oro`c</a>",true);
    }else{
    output("`cNon ci sono accette disponibili");
    }
    addnav("","municipio.php?op=bazar_accette");
    addnav("","municipio.php?op=bazar_picconi");
    }else{
    output("Servizio riservato ai soli cittadini.`n`n");
    addnav("Torna all'entrata","municipio.php");
    }
}elseif($_GET['op']=="bazar_picconi"){
    if ($session['user']['gold'] >= 500){
    if ($session['user']['hunger']>-10){
    output("`@Divori il pasto in pochi secondi come un povero affamato ... ma in fondo `bSEI`b un".($session[user][sex]?"a":"o")." pover".($session[user][sex]?"a":"o")." affamat".($session[user][sex]?"a":"o")."!`n");
    $session['user']['hunger']-=40;
    $session['user']['gold']-=($pricebase+5);
    addnav("`^Ancora!", "municipio.php?op=mensa");
    savesetting("pasti_mensa",getsetting("pasti_mensa",0)-1);
}else{
addnav("Torna all'entrata","municipio.php");
output("`%Sei troppo pien".($session[user][sex]?"a":"o")." per poter ingollare anche una sola porzione!");
}
}else{
output("`\$Non te lo puoi permettere!");
addnav("Torna all'entrata","municipio.php");
}
*/
}elseif($_GET['op']=="dormitorio"){
    if($session['user']['cittadino']=="Si"){
        $sql = "SELECT * FROM tasse WHERE acctid=".$session['user']['acctid'];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if($row[oro]>getsetting("esattore",0) AND getsetting("esattore",0)>0) $evasore=1;
        if($session['user']['evil']>75){
            output("Il vicesceriffo controlla il dormitorio e non ti fa entrare perchè la tua cattiveria è troppo alta.`n`n");
        }elseif($evasore){
            output("L'esattore delle tasse controlla il corridoio che porta al dormitorio, certamente pignorerà i tuoi soldi se ti vedrà pagare l'entrata... Meglio girare al largo.`n`n");
        }else{
            if (getsetting("dormitorio_aperto","si")=="si") {
                $pl=getsetting("posti_letto",10);
                $lu=getsetting("letti_usati",0);
                $pricebase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 10)+3;
                output("Entri nel dormitorio pubblico di Rafflingate, è molto sporco e dislocato nello scantinato del municipio.`n");
                output("Comunque sapere che le guardie del municipio veglieranno sul tuo sonno ti farà dormire sonni tranquilli.`n");
                output("Posti letto totali: $pl`n");
                output("Posti letto occupati: $lu`n");
                output("Costo di una notte: $pricebase`n");
                if($pl>$lu AND $session['user']['gold']>=$pricebase)addnav("Dormi","municipio.php?op=dormi");
            }else{
                output("Il dormitorio è chiuso oggi. Forse dovresti parlarne con il sindaco.`n`n");
            }
        }
    }else{
        output("Servizio riservato ai soli cittadini.`n`n");
    }
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="dormi"){
    //Sook, conteggio letti usati
    $sqllu="SELECT count(acctid) AS lu FROM accounts WHERE location=3";
    $resultlu = db_query($sqllu);
    $rowlu = db_fetch_assoc($resultlu);
    savesetting("letti_usati",$rowlu[lu],0);
    if ($rowlu[lu] >=getsetting("posti_letto",10)) {
        output("`\$Spiacente, l'ultimo letto è stato appena assegnato a qualcuno più svelto di te.`n`n");
        addnav("Torna all'entrata","municipio.php");
    } else {
    //fine conteggio letti
        if ($session[user][loggedin]){
            /*
            //Excalibur: registrazione dei tempi di connessione
            // per chi è rimasto connesso per più di X ore (X = 2)
            $check = getsetting("onlinetime",7200); //secondi oltre i quali viene registrato il tempo di connessione  7200 = 2 ore
            if ( (strtotime($session['user']['laston']) - strtotime($session['user']['lastlogin']) ) > $check) {
                $sql1 = "INSERT INTO furbetti
                         (type,acctid,logintime,logouttime)
                         VALUES ('time','".$session['user']['acctid']."','".$session['user']['lastlogin']."','".$session['user']['laston']."')";
                $result1 = db_query($sql1) or die(db_error(LINK));
            }
            //Excalibur: fine
            */
            savesetting("letti_usati",getsetting("letti_usati",0)+1);
            $pricebase=($session['user']['dragonkills'] * 2) + ($session['user']['reincarna'] * 10)+3;
            debuglog("ha speso $pricebase oro per una stanza al dormitorio");
            debuglog("va a dormire al dormitorio");
            savesetting("oro_tasse",getsetting("oro_tasse",0)+($pricebase));
            $session['user']['loggedin']=0;
            $session['user']['sconnesso']=0;
            $session['user']['location']=3;
            $session['user']['locazione']=0;
            $session['user']['gold']-=$pricebase;
            $sql = "UPDATE accounts SET loggedin=0,sconnesso=0,location=3,gold=gold-$pricebase WHERE acctid = ".$session['user']['acctid'];
            db_query($sql) or die(sql_error($sql));
        }
        saveuser();
        $session=array();
        redirect("index.php");
    }
}elseif($_GET['op']=="risveglio"){
    $caso = mt_rand(1,30);
    if($caso==1){
        output("`3Sfortunatamente hai preso un letto infestato dalle termiti aggressive del legno , hai dormito male e il letto ora è inutilizzabile!");
        savesetting("posti_letto",getsetting("posti_letto",0)-1);
        $session['user']['hitpoints']=intval($session['user']['hitpoints']*0.85);
    }elseif($caso==2){
        output("`3Sfortunatamente hai preso un letto deboluccio, forse tutto quello che hai mangiato ieri non
        ha aiutato, e il letto si è sfondato! Ti vengono addebitati `^2.500`3 pezzi d'oro sulle tue tasse!");
        savesetting("posti_letto",getsetting("posti_letto",0)-1);
        $sqlupdate = "UPDATE tasse SET oro = oro+2500 WHERE acctid=".$session['user']['acctid'];
        db_query($sqlupdate) or die(db_error(LINK));
        if (db_affected_rows()==0){
            $sqlupdate = "INSERT INTO tasse (acctid,oro) VALUES('".$session['user']['acctid']."','2500')";
            $result = db_query($sqlupdate) or die(db_error(LINK));
        }
        $mailmessage = "`@Il letto sul quale hai dormito, forse a causa del tuo peso eccessivo, si è sfondato.`n
        Ti verranno addebitati `^2.500`@ pezzi d'oro sulle tasse da pagare per il rimborso.`n`n`&Buona Giornata !!!!";
        systemmail($session['user']['acctid'],"`#Hai distrutto il tuo letto!!!",$mailmessage);
    }else{
        output("`3Ti risvegli dopo una lunga dormita, hai tutte le pieghe del cuscino sulla faccia!");
    }
    savesetting("letti_usati",getsetting("letti_usati",0)-1);
    addnav("Torna all'entrata","municipio.php");
}elseif($_GET['op']=="infilati"){
    $session['user']['turns']-=1;
    output("`3Riesci a sgattaiolare verso l'entrata del dormitorio!`n");
    output("`3Ti trovi in una stanza che da sul dormitorio al centro una guardia addormentata sonnecchia appoggiata alla scrivania!`n");
    addnav("Prova a passare","municipio.php?op=prosegui");
    addnav("Rinuncia","municipio.php");
}elseif($_GET['op']=="prosegui"){
    $caso = mt_rand(1,3);
    if($caso==1){
        $dc = mt_rand(1,3);
        output("`#AAAAARGGHHH!! Il rumore dei tuoi passi ha svegliato la guardia!`3`n");
        $dkb = round($session['user']['dragonkills']*.2+$session['user']['reincarna']*.1);
        $badguy = array("creaturename"=>"`\$Guardia`0"
        ,"creaturelevel"=>2+$session['user']['reincarna']+$dc
        ,"creatureweapon"=>"`\$Spada`0"
        ,"creatureattack"=>intval(2+$dc+$session['user']['reincarna'])
        ,"creaturedefense"=>intval(2+$dc+$session['user']['reincarna'])
        ,"diddamage"=>0);
        $userlevel=$session['user']['level'];
        $userattack=$session['user']['attack'];
        $userhealth=$session['user']['maxhitpoints'];
        $userdefense=$session['user']['defense'];
        $badguy['creaturelevel']+=$userlevel;
        $badguy['creatureattack']+=$userattack;
        $badguy['creaturehealth']=intval($userhealth*1.6);
        $badguy['creaturedefense']+=$userdefense;
        $session['user']['badguy']=createstring($badguy);
        $session['bufflist']['guradia_dormitorio'] = array(
        "startmsg"=>"`n`%La ferita inferta sta sanguinando!`n`n",
        "name"=>"`%Fendente poderoso`0",
        "rounds"=>$session['user']['level'],
        "wearoff"=>"La guardia si accascia a terra.",
        "minioncount"=>intval($session['user']['level']/2)+$dc,
        "mingoodguydamage"=>1,
        "maxgoodguydamage"=>2+$dkb,
        "effectmsg"=>"Il sangue perso causa la perdita di {damage} HitPoint.",
        "effectnodmgmsg"=>"Fortunatamente la ferita non ha preso una vena.",
        "activate"=>"roundstart",
        );
        addnav("Combatti","municipio.php?op=fight");
    }else{
        output("`6 Sei riuscito a passare senza svegliare la guardia!`3`n");
        addnav("Dormitorio","municipio.php?op=lista_pvp");

    }
}elseif($_GET['op']=="lista_pvp"){
    addnav("Ricarica la lista","municipio.php?op=lista_pvp");
    output("Sei arrivato al dormitorio, ora puoi attaccare chiunque dorma al suo interno.");
    $pvptime = getsetting("pvptimeout",600);
    $pvptimeout = date("Y-m-d H:i:s",strtotime(date("r")."-$pvptime seconds"));
    pvpwarning();
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
    $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE
                (locked=0) AND
                (level >= ".($session[user][level]-1)." AND level <= ".($session[user][level]+2).") AND
                (alive=1 AND location=3) AND
                (lastip <> '".$session['user']['lastip']."') AND (emailaddress <> '".$session['user']['emailaddress']."') AND
                (age >= $days OR dragonkills > 0 OR reincarna > 0 OR pk > 0 OR experience > $exp) AND
                (laston < '".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))."' OR loggedin=0) AND
                (acctid <> ".$session[user][acctid].")
                ORDER BY level DESC";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Ops</td></tr>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $biolink = "bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
        addnav("", $biolink);
        if($row[pvpflag]>$pvptimeout){
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | `i(Attaccato di recente)`i ]</td></tr>",true);
        }else{
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvp.php?op5=locanda&act=attack&bg=1&name=".rawurlencode($row[login])."'>Attacca</a> ]</td></tr>",true);
            addnav("","pvp.php?op5=locanda&act=attack&bg=1&name=".rawurlencode($row[login]));
        }
    }
    output("</table>",true);
    addnav("Torna all'entrata","municipio.php");
}elseif ($_GET['op']=='battle' || $_GET['op']=='run') {
    if ($_GET['op']=='run') {
        output("Non riesci a fuggire alla guardia!!");
        $_GET['op']="fight";
    }
}elseif ($_GET['op']=='fight') {
    $battle=true;
}else{
// manutenzione tabella tasse e cittadini, Sook
    $sql = "SELECT acctid FROM tasse";
    $result = db_query($sql) or die(db_error(LINK));
    $cit=db_num_rows($result);
    for ($i=0; $i<$cit; $i++){
        $row = db_fetch_assoc($result);
        $sql2 = "SELECT cittadino FROM accounts WHERE acctid=".$row[acctid]." AND superuser=0 AND cittadino='Si'";
        $result2 = db_query($sql2) or die(db_error(LINK));
        $countrow=db_num_rows($result2);
        if ($countrow==0) {
            $sql3="DELETE FROM tasse WHERE acctid=".$row[acctid];
            db_query($sql3) or die(db_error(LINK));
        }
    }
//fine manutenzione    

    if(getsetting("striscione_sindaco",0)!='no' AND getsetting("stri_sin_data",0)>strtotime(date("r"))){
        output("`b`c`4Uno striscione all'entrata del municipio riporta:`n\"`@ Annuncio del sindaco $nome `4\"`0`c`b`n",true);
        output("`b`c<big>`0".stripslashes(getsetting("striscione_sindaco",0))."</big>`c`b`n`n",true);
    }
    if(getsetting("striscione_acctid",0)>0){
        $sqlnome="SELECT name FROM accounts WHERE acctid=".getsetting("striscione_acctid",0);
        $resultnome=db_query($sqlnome);
        $dep=db_fetch_assoc($resultnome);
        $nome=$dep['name'];
        output("`b`c`@Uno striscione all'entrata del municipio riporta:`n\"`6 Annuncio del candidato sindaco $nome `@\"`0`c`b`n",true);
        output("`b`c<big>`0".stripslashes(getsetting("striscione_testo",0))."</big>`c`b`n`n",true);
    }
    $sql = "SELECT acctid FROM tasse";
    $result = db_query($sql) or die(db_error(LINK));
    $cit=db_num_rows($result);
    output("`#Entri nel municipio di RafflinGate, molta gente indaffarata corre nei vari uffici, ed alcuni abitanti sono in coda agli sportelli.`n`n");
    output("`@Su una lavagnetta appesa c'è scritto `6\" Cittadini di Rafflingate : `\$".$cit." `6\"`n");
    output("`@Livello di approvazione del sindaco : `6".getsetting("approvazione_sindaco",0)."`@ %`n");
    $sql = "SELECT count(acctid) AS tot,voce FROM municipio_voce GROUP BY voce ORDER BY tot DESC";
    $result = db_query($sql) or die(db_error(LINK));
    output("`@Voce del popolo : `6".$row[voce]."`n");

    addnav("Sportello nuovi arrivi", "municipio.php?op=nuovi");
    addnav("Sportello cittadini", "municipio.php?op=cittadini");
    addnav("Sportello tutore", "municipio.php?op=tutore");
    addnav("Sportello elezioni", "municipio.php?op=elezioni");
    addnav("Ufficio Catasto", "municipio_ufficio_catasto.php");
    addnav("Ufficio del sindaco", "municipio.php?op=sindaco");
    addnav("Torna al Villaggio","village.php");
    addnav("","");
    $sql = "SELECT * FROM tasse WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $esattore = getsetting("esattore",'0');
    if($row[oro]<=$esattore OR $esattore==0){
        addnav("Mensa", "municipio.php?op=mensa");
        addnav("Dormitorio", "municipio.php?op=dormitorio");
    }else{
        output("`n`\$La mensa e il dormitorio sono disponibili a chi paga le tasse ti dice una guardia!");
    }
    if ($session['user']['evil']>75 AND $session['user']['turns']>1) {
        addnav("`4Infilati nel dormitorio","municipio.php?op=infilati");
        output("`n`n`\$Tentare di sgattaiolare nel dormitorio per provare ad uccidere qualche buon cittadino costa 1 turno!`n");

    }
    //addnav("Bazar", "municipio.php?op=bazar");
    $sql = "SELECT acctid FROM elezione_candidati WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql);
    if(db_num_rows($result)>0){
        output("`n`#Noti un vecchio nano che da degli ordini a due goblin piccolini che stanno appendendo uno striscione nell'entrata del municipio. Il nano ti guarda
        e dice :  \"`6Ma tu sei uno dei candidati vuoi comprare questo striscione e mettere il tuo slogan?\"`n`n");
        addnav("","");
        addnav("Striscione", "municipio.php?op=slogan");

    }
    output("`n`n");
    viewcommentary("municipio","Partecipa al comizio",30,25);
}
if ($battle) {
    include_once("battle.php");
    if($victory) {
        if ($session['user']['hitpoints'] > 0){
            unset($session['bufflist']['guardia_dormitorio']);
            $badguy = createarray($session['user']['badguy']);
            $exp = array(1=>14,26,37,50,61,73,85,98,111,125,140,155,172,189,208,228,250,275,310,348);
            output("Hai battuto `^".$badguy['creaturename'].".`n");
            $guadagno=round($exp[$badguy['creaturelevel']])*3;
            output("Hai guadagnato $guadagno punti esperienza !!!`n");
            addnews("`%".$session['user']['name']."`@ è stato attaccato da ".$badguy['creaturename']. "`@, mentre tentava di intrufolarsi nel dormitorio!! E ha vinto!");
            $session['user']['evil']+=20;
            $session['user']['experience']+=$guadagno;
            $session['user']['badguy']="";

            ////mettere le varie condizioni in base al mostro per tornare alla zona da cui si proveniva
            if($badguy['creaturename']=="`\$Guardia`0"){
                addnav("Dormitorio","municipio.php?op=lista_pvp");
                addnav("Rinuncia","municipio.php");
            }
        }else{
            output("`4Sei morto!!`n`n");
            $session['user']['evil']+=20;
            $session['user']['experience']*=0.95;
            $session['user']['badguy']="";
            $session['bufflist']['guardia_dormitorio'] = array();
            $session['user']['alive']=false;
            addnav("Notizie Giornaliere","news.php");
        }
    }else   {
        if ($defeat) {
            $session['user']['evil']+=20;
            $badguy = createarray($session['user']['badguy']);
            output("`4Mentre cadi a terra `^".$badguy['creaturename']. "`4 ride, per quanto `^".$badguy['creaturename']. "`4 possa ridere!`n");
            output("`\$Hai perso il `b`^5%`b`\$ della tua esperienza !!!`n");
            output("Inoltre perdi tutto l'oro che avevi con te !!!`n`n");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"sua":"suo"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"lei":"lui"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);
            addnews("`%".$session['user']['name']."`6 è stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." da ".$badguy['creaturename']. "`6, mentre tentava di intrufolarsi nel dormitorio!!`n$taunt");
            debuglog("è stato ucciso da un ".$badguy['creaturename']. " mentre attaccava il paese di Eythgim. Perde 5% exp e ".$session['user']['gold']." oro");
            addnav("Notizie Giornaliere","news.php");
            $session['user']['experience']*=0.95;
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['gold']=0;
            $session['user']['dove_sei'] = 0;
            savesetting("attacchi_persi","".(getsetting("attacchi_persi","")+1)."");
        }else{
            fightnav(true,false);
        }
    }
}

page_footer();
?>