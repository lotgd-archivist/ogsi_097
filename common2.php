<?php
// Xtramus controllo multiaccounts
function saveaction($posizione,$id,$durata=FALSE) {
    global $session;
    $uid= $session['user']['uniqueid'];
    $ip= $_SERVER['REMOTE_ADDR'];
    $scadenza= time()+ (!$scadenza? 3600 : $scadenza);
    $actid= $session['user']['acctid'];
    //cancello record precedenti dello stesso pg nella stessa casa o terra
    $query= "delete from controluser where posizione=\"$posizione\" && idtarget=$id && pg=$actid";
    db_query($query) or
    (db_error(LINK));
    $query= "insert into controluser (posizione,idtarget,pg,userid,ip,scadenza) VALUES('$posizione',$id,$actid,'$uid','$ip',$scadenza)";
    db_query($query) or die(db_error(LINK));
}

function ismultiaction($posizione,$id) {
    global $session;
    //elimino righe scadute
    $query="delete from controluser where scadenza<" . time();
    db_query($query) or die(db_error(LINK));
    //controllo
    $uid= $session['user']['uniqueid'];
    $ip= $_SERVER['REMOTE_ADDR'];
    $actid= $session['user']['acctid'];
    $query= "select * from controluser where posizione=\"$posizione\" && (ip=\"$ip\" || userid=\"$uid\") && idtarget=$id && pg!=$actid";
    $result = db_query($query) or die(db_error(LINK));
    if(db_num_rows($result)>0) {
        debuglog("Bloccato dal sistema antimultiaccount per $posizione $id");
        return true;
    }
    else {
        //controllo mail
        $query= "SELECT controluser.pg, accounts.emailaddress from controluser,accounts where controluser.pg=accounts.acctid && accounts.emailaddress=\"".$session['user']['emailaddress']."\" && controluser.pg!=$actid && controluser.idtarget=$id && controluser.posizione=\"$posizione\"";
        $result = db_query($query) or die(db_error(LINK));
        if(db_num_rows($result)>0) {
            //output("mail uguale!");
            debuglog("Bloccato dal sistema antimultiaccount per $posizione $id: mail uguale!");
            return true;
        }
        return false;
    }
}

//Luke: accounts dynamic
//funzione assegnazione premi player
function assign_to_pg($acctid,$type,$amount,$text=null){
    $sql = "INSERT INTO accounts_dynamic (acctid,type,amount,text) VALUES ('$acctid','$type','$amount','$text')";
    db_query($sql) or die(db_error($link));
}
//funzione ritiro premi player
function pickup_to_pg(){
    global $session;
    $sql = "SELECT * FROM accounts_dynamic WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        if($row['text']!=null)$text=$row['text'];
        if($row['type']=='gold'){
            $session['user']['gold']+=$row['amount'];
            output("`n`6Ricevi `^`b".$row['amount']."`b`6 Oro.`n`2$text`n");
        }elseif($row['type']=='gems'){
            $session['user']['gems']+=$row['amount'];
            output("`n`7Ricevi `&`b".$row['amount']."`b`7 Gemme.`n`2$text`n");
        }elseif($row['type']=='pc'){
            $session['user']['punti_carriera']+=$row['amount'];
            output("`n`2Ricevi `@`b".$row['amount']."`b`2 Punti Carriera.`n`3$text`n");
        }elseif($row['type']=='hp'){
            $session['user']['maxhitpoints']+=$row['amount'];
            output("`n`3Ricevi `#`b".$row['amount']."`b`3 Punti Vita Permanenti.`n`2$text`n");
        }elseif($row['type']=='fama'){
            $session['user']['fama3mesi']+=$row['amount'];
            output("`n`8Ricevi `(`b".$row['amount']."`b`8 Punti Fama.`n`3$text`n");
        }else{
            $mailmessage = "`^PG: ".$session['user']['acctid']."`n`^Type: ".$row['type']."`n`^Amount: ".$row['amount']."`n";
            //systemmail(1,"`2PROBLEMA PICKUP.`2",$mailmessage);
            report(3,"`2PROBLEMA PICKUP",$mailmessage,"problempickup");
        }
        $mailmessage = "`^".$row['type'].'  : '.$row['amount']."`n$text`n";
        systemmail($session['user']['acctid'],"`2Ricevi ".$row['type'].".`2",$mailmessage);
    }
    $sqlogg = "DELETE FROM accounts_dynamic WHERE acctid = '".$session['user']['acctid']."'";
    db_query($sqlogg) or die(db_error(LINK));
}

//Luke: funzione crea draghi
function crea_drago($eta_min,$eta_max,$tipo_min,$tipo_max,$dove,$posizione=0){
    /*  Tabella Dove Draghi
    0 = Player
    1 = Mercato paese
    3 = Draghi liberi livello basso
    4 = Mercante Città dei draghi
    5 = Draghi liberi livello medio
    6 = Draghi liberi livello alto

    Tabella Posizione
    0 = Vulcano
    1 2 3 4
    Le varie terre leggendarie

    */
    $tipo=rand($tipo_min,$tipo_max);
    $tipo_drago=array(Nero,Rosso,Blu,Verde,Bianco,Zombie,Scheletro,Bronzo,Argento,Oro,Platino);
    $tipo_drago_db=$tipo_drago[$tipo];
    $eta=rand($eta_min,$eta_max);
    $eta_drago=array(cucciolo,giovane,adulto,anziano,antico);
    $eta_drago_db=$eta_drago[$eta];
    $soffio=rand(0,4);
    $soffio_drago=array(fuoco,gelo,acido,morte,fulmine);
    $soffio_drago_db=$soffio_drago[$soffio];
    $danno_soffio=rand(10,15);
    $attacco=rand(5,10);
    $difesa=rand(5,10);
    $vita=rand(20,30);
    $carattere=rand(5,15);
    $bonus=rand(1,5);
    $aspetto=rand(0,4);
    $aspetto_drago=array(Pessimo,Brutto,Normale,Buono,Ottimo);
    $aspetto_drago_db=$aspetto_drago[$aspetto];
    $crescita=rand(10,12);
    $aspetto_bonus=intval(($aspetto+1)*$crescita);
    //due righe prima
    if($eta==1 OR $eta==2 OR $eta==3 OR $eta==4){
        $asp_bon=intval($aspetto_bonus/2);
        $attacco=$attacco+rand($asp_bon,$aspetto_bonus);
        $difesa+=rand($asp_bon,$aspetto_bonus);
        $danno_soffio+=rand($asp_bon,$aspetto_bonus);
        $carattere+=rand($asp_bon,$aspetto_bonus);
        $vita+=$aspetto_bonus;
    }
    if($eta==2 OR $eta==3 OR $eta==4){
        $asp_bon=intval($aspetto_bonus/2);
        $attacco=$attacco+rand($asp_bon,$aspetto_bonus);
        $difesa+=rand($asp_bon,$aspetto_bonus);
        $danno_soffio+=rand($asp_bon,$aspetto_bonus);
        $carattere+=rand($asp_bon,$aspetto_bonus);
        $vita+=$aspetto_bonus;
    }
    if($eta==3 OR $eta==4){
        $asp_bon=intval($aspetto_bonus/2);
        $attacco=$attacco+rand($asp_bon,$aspetto_bonus);
        $difesa+=rand($asp_bon,$aspetto_bonus);
        $danno_soffio+=rand($asp_bon,$aspetto_bonus);
        $carattere+=rand($asp_bon,$aspetto_bonus);
        $vita+=$aspetto_bonus;
    }
    if($eta==4){
        $asp_bon=intval($aspetto_bonus/2);
        $attacco=$attacco+rand($asp_bon,$aspetto_bonus);
        $difesa+=rand($asp_bon,$aspetto_bonus);
        $danno_soffio+=rand($asp_bon,$aspetto_bonus);
        $carattere+=rand($asp_bon,$aspetto_bonus);
        $vita+=$aspetto_bonus;
    }

    if ($soffio==0){
        $danno_soffio+=15;
    }
    if($soffio==1){
        $danno_soffio+=5;
        $attacco=$attacco+10;
    }
    if($soffio==2){
        $carattere+=15;
    }
    if($soffio==3){
        $attacco=$attacco+15;
    }
    if($soffio==4){
        $attacco=$attacco+5;
        $carattere+=5;
        $danno_soffio+=5;
    }
    if ($tipo==0){
        $attacco+=5;
    }
    if($tipo==1){
        $danno_soffio+=5;
    }
    if($tipo==2){
        $difesa+=5;
    }
    if($tipo==3){
        $vita+=10;
    }
    if($tipo==4){
        $vita+=5;
        $carattere+=5;
    }
    if($tipo==5){
        $attacco+=10;
    }
    if($tipo==6){
        $attacco=$attacco+5;
        $danno_soffio+=5;
        $carattere+=5;
    }
    if($tipo==7){
        $difesa+=10;
        $carattere+=5;
    }
    if($tipo==8){
        $difesa+=5;
        $vita+=5;
        $carattere+=5;
    }
    if($tipo==9){
        $attacco=$attacco+5;
        $difesa+=5;
        $danno_soffio+=5;
        $vita+=5;
        $crescita+=1;
    }
    if($tipo==10){
        $attacco=$attacco+5;
        $difesa+=5;
        $danno_soffio+=5;
        $vita+=5;
        $carattere+=5;
        $crescita+=1;
    }
    $terreno_nome=rand(0,1);
    $terreno=array(volo,terra);
    $terreno_db=$terreno[$terreno_nome];
    $valore=$attacco+$difesa+$vita+$danno_soffio+$bonus+$carattere+($aspetto_bonus*($eta+1));
    $sql = "INSERT INTO draghi (tipo_drago,eta_drago,tipo_soffio,danno_soffio,attacco,difesa,vita,vita_restante,carattere,dove,combatte,bonus,valore,aspetto,crescita,posizione)
VALUES ('$tipo_drago_db','$eta_drago_db','$soffio_drago_db','$danno_soffio','$attacco','$difesa','$vita','$vita','$carattere','$dove','$terreno_db','$bonus','$valore','$aspetto_drago_db','$crescita','$posizione')";
    db_query($sql) or die(db_error($link));
}
//Luke: fine funzione crea draghi

//Maximus: funzione calcolo zaino pieno
function zainoPieno ($acctid) {
    global $session;
    $limite_zaino = getsetting("limite_zaino",20);
    $sqlmateriali = "SELECT COUNT(idplayer) AS quantita FROM zaino WHERE idplayer ='".$session['user']['acctid']."'";
    $resultmateriali = db_query($sqlmateriali ) or die(db_error(LINK));
    $quantita_zaino = db_fetch_assoc($resultmateriali);
    if ($quantita_zaino['quantita'] >= $limite_zaino) {
        return true;
    } else {
        return false;
    }
}
//Maximus: fine funzione calcolo zaino pieno

//Luke : funzioni per donazioni
function donazioni($nome){
    global $session;
    $sql = "SELECT COUNT(idplayer) AS comprato FROM donazioni WHERE nome = '$nome' AND idplayer='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $rowd = db_fetch_assoc($result);
    if ($rowd[comprato]==1){
        return true;
    } else {
        return false;
    }
}

function donazioni_usi($nome){
    global $session;
    $sql = "SELECT usi FROM donazioni WHERE nome = '$nome' AND idplayer='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    return $row[usi];
}
function donazioni_usi_up($nome,$valore){
    global $session;
    $sql = "UPDATE donazioni SET usi='$valore' where idplayer = '".$session['user']['acctid']."' AND nome = '$nome' ";
    db_query($sql) or die(db_error(LINK));
}
//Luke : fine

// Excalibur: function GardePond
function get_pref_pond($prefs){
    global $session;
    $sql = "SELECT value1 FROM items WHERE class = 'pond' AND name = '$prefs' AND owner='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if (db_num_rows($result)==0){
        return 0;
    } else {
        return $row['value1'];
    }
}

function set_pref_pond($prefs,$value){
    global $session;
    $sql = "SELECT * FROM items WHERE owner = '".$session['user']['acctid']."' AND class = 'pond' AND name='$prefs'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_affected_rows()==0){
       $sql = "INSERT INTO items (id,name,class,owner,value1,value2,gold,gems,description,hvalue,buff,tempo)
                           VALUES('','$prefs','pond','".$session['user']['acctid']."','$value','','','','','','','')";
       $result = db_query($sql) or die(db_error(LINK));
    }else{
      $sql = "UPDATE items SET value1='$value' WHERE owner = '".$session['user']['acctid']."' AND class = 'pond' AND name='$prefs'";
      $result = db_query($sql) or die(db_error(LINK));
    }
}
//Excalibur: end GardenPond

// Sook: funzioni morte strega
function get_morte_strega(){
    global $session;
    $sql = "SELECT value1 FROM items WHERE class = 'morte_strega' AND owner='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if (db_num_rows($result)==0){
        return 0;
    } else {
        return $row['value1'];
    }
}

function set_morte_strega($value){
    global $session;
    $sql = "SELECT * FROM items WHERE owner = '".$session['user']['acctid']."' AND class = 'morte_strega'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_affected_rows()==0){
       $sql = "INSERT INTO items (id,name,class,owner,value1,value2,gold,gems,description,hvalue,buff,tempo)
                           VALUES('','','morte_strega','".$session['user']['acctid']."','$value','','','','','','','')";
       $result = db_query($sql) or die(db_error(LINK));
    }else{
      $sql = "UPDATE items SET value1='$value' WHERE owner = '".$session['user']['acctid']."' AND class = 'morte_strega'";
      $result = db_query($sql) or die(db_error(LINK));
    }
}
// Sook: fine funzioni morte strega

function pvpwarning($dokill=false) {
    global $session;
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
    if ($session['user']['age'] <= $days &&
    $session['user']['dragonkills'] == 0 &&
    $session['user']['reincarna'] == 0 &&
    $session['user']['user']['pk'] == 0 &&
    $session['user']['experience'] <= $exp) {
        if ($dokill) {
            output("`n`\$Attenzione!`^ Attualmente disponi ancora dell'immunità contro i combattimenti Player vs Player (PvP) ma, dal momento che hai scelto di attaccare un altro giocatore, hai perso quest'immunità!!`n`n");
            $session['user']['pk'] = 1;
        } else {
            output("`n`\$Attenzione!`^ I giocatori sono immuni dai combattimenti Player vs Player (PvP) duranto l'arco dei primi `&$days`^ giorni di gioco o fino a quando hanno raggiunto `&$exp`^ punti di esperienza o se attaccano un altro giocatore.  `nSe deciderai di procedere con l'attacco, perderai immediatamente quest'immunità!`n`n");
        }
    }
}

//Sook, funzione report per superuser
function report($superuser,$subject,$body,$tipo=""){
    global $session;
    if(!preg_match("/^[a-zA-Z0-9_]+$/",$tipo)) $tipo="";
    $tipo=strtolower($tipo);
    if ($tipo!="") {
        $k=0;
        //$sql="SELECT * FROM suprefs WHERE acctid=".$session['user']['acctid'];
        $sql="SELECT * FROM suprefs WHERE 1 LIMIT 1";
        //echo $sql."<br>";
        $result = db_query($sql) or die(db_error(LINK));
        //echo "Numero righe: ".db_num_rows($result)."<br>";
        if (db_num_rows($result) != 0){
           $row = db_fetch_assoc($result);
           if (!array_key_exists($tipo, $row)) {
               //print_r(array_keys($row));
               $sql="ALTER TABLE suprefs ADD {$tipo} TINYINT (1) DEFAULT '1' NOT NULL";
               @mysql_query($sql);
           }
        }
    }
/* VECCHIA ROUTINE
    $sql="SELECT acctid FROM accounts WHERE superuser >= ".$superuser;
    $result = db_query($sql) or die(db_error(LINK));
    for($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($tipo!="") {
            $sql2="SELECT ".$tipo." AS chec FROM suprefs WHERE acctid = ".$row[acctid];
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
        } else {
            $row2['chec']=1;
        }
        if($row2['chec']==1) systemmail($row['acctid'],$subject,$body,0);
    }
*/
// NUOVA ROUTINE
    //print"Tipo report: ".$tipo."<br>";
    if ($tipo!="") {
       $sql="SELECT acctid FROM accounts WHERE superuser >= ".$superuser;
       //echo $sql."<br>";
       $result = db_query($sql) or die(db_error(LINK));
       //echo db_num_rows($result)."<br>";
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for($i=0;$i<db_num_rows($result);$i++){
           $row = db_fetch_assoc($result);
           $sql2 = "SELECT ".$tipo." AS chec FROM suprefs WHERE acctid = ".$row['acctid'];
           //echo "Prima parte ".$sql2."<br>";
           $result2 = db_query($sql2) or die(db_error(LINK));
           $row2 = db_fetch_assoc($result2);
           if($row2['chec']==1){
              $subject = safeescape($subject);
              $subject=str_replace("\n","",$subject);
              $subject=str_replace("`n","",$subject);
              $body = safeescape($body);
              $sql3 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent)
                       VALUES ('0','".(int)$row['acctid']."','$subject','$body',now())";
              //echo $sql3."<br>";
              db_query($sql3) or die(db_error(LINK));
           }
       }
    }else{
       $sql="SELECT acctid FROM accounts WHERE superuser >= ".$superuser;
       $result = db_query($sql) or die(db_error(LINK));
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for($i=0;$i<db_num_rows($result);$i++){
           $row = db_fetch_assoc($result);
           $subject = safeescape($subject);
           $subject=str_replace("\n","",$subject);
           $subject=str_replace("`n","",$subject);
           $body = safeescape($body);
           $sql3 = "INSERT INTO mail (msgfrom,msgto,subject,body,sent)
                    VALUES ('0','".(int)$row['acctid']."','$subject','$body',now())";
           //echo $sql3."<br>";
           db_query($sql3) or die(db_error(LINK));

       }
    }
// FINE NUOVA ROUTINE
}
//Sook, fine

function forest($noshowmessage=false) {
    global $session,$playermount;
    $conf = unserialize($session['user']['donationconfig']);
    //Excalibur: modifica per voto web
    /*
    $ora = strftime("%H",time());
    if (( (getsetting("topwebid", 0) != "") AND (strtotime($session['user']['lastwebvote']) < strtotime(date("Y-n-j")-86400) ) ) OR $session['user']['donation']==0) {
        if($session['user']['donation'] == 0) $session['user']['donation']=1;
        addnav("Miglior Sito");
        addnav("V?`&Vota per OGSI", "village1.php");
    }
    */
    //Excalibur: fine modifica per voto web

    addnav("`iGuaritore`i");
    if ($conf['healer'] || $session['user']['acctid']==getsetting("hasegg",0)) {
        addnav("G?`6Capanna di Golinda","healer.php");
    } else {
        addnav("G?`6 Guaritore","healer.php");
    }
    addnav("`iCerca Creature`i");
    addnav("T?`4Trova Qualcosa da Uccidere","forest.php?op=search");
    if ($session['user']['level']>1)
    addnav("V?`4Visita i bassifondi","forest.php?op=search&type=slum");
    addnav("B?`4Vai in cerca di Brividi","forest.php?op=search&type=thrill");
    if ($session['user']['superuser']>1) addnav("C?`^Cerca un Evento Speciale","forest.php?op=search&type=event");
    if ($playermount['tavern']>0) addnav("`\$Porta {$playermount['mountname']} `\$alla Taverna del Cavallo Nero","forest.php?op=darkhorse");
    addnav("`iSentieri`i");
    addnav("x?`2Castel Excalibur","castelexcal.php");
    addnav("M?`2Il Monastero","monastero.php");
    addnav("L?`2La Collina","eslotto.php");
    // Maximus - Inizio
    addnav("E?`2Esplora il Bosco","foresta.php");
    addnav("F?`2Falegnameria","falegnameria.php");
    // Maximus - Fine
    addnav("V?`2Sentiero del Villaggio","village.php");
    if ($session['user']['level']==8 && $session['user']['reincarna']<1) {addnav("`@`bSegui le Grida`b","fanciulla.php");}
    addnav("","forest.php");
    if ($session['user']['level']>=15  && $session['user']['seendragon']==0){
        addnav("D?`@Cerca il Drago Verde","forest.php?op=dragon");
    }
    addnav("`iAltro`i");
    $sql = "SELECT * FROM mappe_foresta_player WHERE acctid=".$session['user']['acctid'];
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0) addnav("`VEsplora luoghi speciali","esplora.php");
    addnav("P?`3I Bagni Pubblici","outhouse.php");
    addnav("I?`3Il Giardino Incantato","enchanted.php");
    if ($session['user']['turns']<=1 AND $session['user']['agedrago'] < 59) addnav("`5Casa della Strega","hexe.php");
    if ($noshowmessage!=true){
        output("`c`7`bLa Foresta`b`0`c");
        output("La foresta, casa di creature malefiche e malvagie di ogni sorta.`n`n");
        output("Le fitte fronde riducono il campo visivo a pochi metri in gran parte della zona.  ");
        output("Il sentiero è quasi invisibile anche ai tuoi occhi esperti. Ti muovi silenziosamente come ");
        output("una brezza leggera sullo spesso strato di muschio che ricopre il terreno, attento a non calpestare ");
        output("un rametto o uno dei tanti frammenti di ossa sbiancate che perforano il terreno della foresta, per non ");
        output("rivelare la tua presenza ad una delle vili bestie che vagabondano per la foresta.");
    }
    //Maximus Inizio: Usura Arma e Armatura
    if ($session['user']['usura_arma']==0 && $session['user']['weapondmg'] > 0) {
        output("`n`n`bDopo l'ultimo combattimento il tuo ".$session['user']['weapon']." è talmente usurato che lo devi buttare via, ti ritrovi ancora una volta a usare i Pugni...`b");
        debuglog("si ritrova a combattere con i pugni per la troppa usura di ".$session['user']['weapon']);
        $session['user']['weapon']='Pugni';
        $session['user']['usura_arma']=999;
        $session['user']['attack']-=$session['user']['weapondmg'];
        $session['user']['weaponvalue']=0;
        $session['user']['weapondmg']=0;
    }
    if ($session['user']['usura_armatura']==0 && $session['user']['armorvalue'] > 0) {
        output("`n`n`bDopo l'ultimo combattimento il tuo ".$session['user']['armor']." è talmente usurato che lo devi buttare via, ti ritrovi ancora una volta a usare la T-Shirt...`b");
        debuglog("si ritrova a difendersi con la t-shirt per la troppa usura di ".$session['user']['armor']);
        $session['user']['armor']='T-Shirt';
        $session['user']['usura_armatura']=999;
        $session['user']['defence']-=$session['user']['armordef'];
        $session['user']['armordef'] = 0;
        $session['user']['armorvalue'] = 0;
    }
    //Maximus Fine: Usura Arma e Armatura
    //Sook: Usura Oggetti
    if ($session['user']['oggetto']!=0) {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['oggetto']."'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        if ($rowo['usura'] == 0) {
            output("`n`n`bIl tuo ".$rowo['nome']." si è spaccato in più pezzi, adesso non hai più un oggetto in mano...`b");
            $session['user']['attack'] -= $rowo['attack_help'];
            $session['user']['defence'] -= $rowo['defence_help'];
            $session['user']['bonusattack'] -= $rowo['attack_help'];
            $session['user']['bonusdefence'] -= $rowo['defence_help'];
            if ($rowo['usuramagica']!=0) $session['user']['maxhitpoints'] -= $rowo['hp_help'];
            if ($rowo['usuramagica']!=0) $session['user']['hitpoints'] -= $rowo['hp_help'];
            if ($rowo['usuramagica']!=0) $session['user']['turns'] = $session['user']['turns'] - $rowo['turns_help'];
            if ($rowo['usuramagica']!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];
            debuglog("ha rotto ".$rowo['nome']." per eccessiva usura");
            $sql = "DELETE FROM oggetti WHERE id_oggetti='{rowo[id_oggetti]}'";
            db_query($sql) or die(db_error(LINK));
            $session['user']['oggetto']=0;
        }
    }
    if ($session[user][zaino]!=0) {
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['zaino']."'";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resulto);
        if ($rowo['usura'] == 0) {
            output("`n`n`bIl tuo ".$rowo['nome']." si è spaccato in più pezzi, adesso non hai più un oggetto nello zaino...`b");
            debuglog("ha rotto ".$rowo['nome']." per eccessiva usura");
            $sql = "DELETE FROM oggetti WHERE id_oggetti='{rowo[id_oggetti]}'";
            db_query($sql) or die(db_error(LINK));
            $session['user']['zaino']=0;
        }
    }
    //Sook: Fine Usura Oggetti
    if ($session['user']['superuser']>=1){
        output("`n`nEventi Speciali x SUPER-UTENTE:`n");
        $d = dir("special");
        $allevents= Array();
        while (false !== ($entry = $d->read())){
            if (substr($entry,0,1)!="."){
               //Xtramus ordine alfabetico
                //output("<a href='forest.php?specialinc=$entry'>$entry</a>`n", true);
                $allevents[]="<a href='forest.php?specialinc=$entry'>$entry</a>`n";

                addnav("","forest.php?specialinc=$entry");
            }
        }
        sort($allevents);
        foreach($allevents as $v) {
               output($v,true);
        }
        unset($allevents);
        unset($v);
    }
}

//Excalibur: nuova funzione checkban
function checkban($login=false){
    global $session, $_SERVER, $_COOKIE;
    if ($session['banoverride']) return false;
    if ($login===false){
        $ip=$_SERVER[REMOTE_ADDR];
        $id=$_COOKIE[$DB_NAME];
        //echo "<br>Orig output: $ip, $id<br>";
    }else{
        $sql = "SELECT lastip,uniqueid,login,banoverride FROM accounts WHERE login='$login'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row['banoverride']){
            $session['banoverride']=true;
            //echo "`nYou are absolved of your bans, son.";
            return false;
        }else{
            //echo "`nNo absolution here, son.";
        }
        db_free_result($result);
        $ip=$row['lastip'];
        $id=$row['uniqueid'];
        $name=$row['login'];
        //echo "<br>Secondary output: $ip, $id<br>";
    }
    $sql = "select * from bans where ((substring('$ip',1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (uniqueid='$id' AND uniqueid<>'') OR (bannedchar='$name' AND bannedchar<>'')) AND (banexpire='0000-00-00' OR banexpire>'".date("Y-m-d")."')";
    //echo $sql;
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        // $msg.=$session['message'];
        $session=array();
        //$session['message'] = $msg;
        //echo "Session Abandonment";
        $session[message].="`n`SSei stato bannato da questo sito web:`n";
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $session['message'].=$row['banreason'];
            if ($row['banexpire']=="0000-00-00") $session['message'].="  `SQUESTO BAN È PERMANENTE!!`0";
            if ($row['banexpire']!="0000-00-00") $session['message'].="  `^Questo ban sarà rimosso il ".date("d M, Y",strtotime($row[banexpire]))."`0";
            $session['message'].="`n";
        }
        $session['message'].="`GSe vuoi, puoi appellarti a questo ban con il link delle petizioni.";
        header("Location: index.php");
        exit();
    }
    db_free_result($result);
}
//Excalibur:fine nuova funzione checkban

function showform($layout,$row,$nosave=false){
    global $output;
    output("<table>",true);
    while(list($key,$val)=each($layout)){
        $info = split(",",$val);
        if ($info[1]=="title"){
            output("<tr><td colspan='2' bgcolor='#666666'>",true);
            output("`b`^$info[0]`0`b");
            output("</td></tr>",true);
        }else{
            output("<tr><td nowrap valign='top'>",true);
            output("$info[0]");
            output("</td><td>",true);
        }
        switch ($info[1]){
            case "title":

                break;
            case "enum":
                reset($info);
                list($k,$v)=each($info);
                list($k,$v)=each($info);
                $output.="<select name='$key'>";
                while (list($k,$v)=each($info)){
                    $optval = $v;
                    list($k,$v)=each($info);
                    $optdis = $v;
                    $output.="<option value='$optval'".($row[$key]==$optval?" selected":"").">".HTMLEntities2("$optval : $optdis")."</option>";
                }
                $output.="</select>";
                break;
            case "password":
                $output.="<input type='password' name='$key' value='".HTMLEntities2($row[$key])."'>";
                break;
            case "bool":
                $output.="<select name='$key'>";
                $output.="<option value='0'".($row[$key]==0?" selected":"").">No</option>";
                $output.="<option value='1'".($row[$key]==1?" selected":"").">Si</option>";
                $output.="</select>";
                break;
            case "hidden":
                $output.="<input type='hidden' name='$key' value=\"".HTMLEntities2($row[$key])."\">".HTMLEntities2($row[$key]);
                break;
            case "viewonly":
                output(dump_item($row[$key]), true);
                //          output(str_replace("{","<blockquote>{",str_replace("}","}</blockquote>",HTMLEntities2(preg_replace("'(b:[[:digit:]]+;)'","\\1`n",$row[$key])))),true);
                break;
            case "int":
                $output.="<input name='$key' value=\"".HTMLEntities2($row[$key])."\" size='5'>";
                break;
            case "text":
                $output.=("<textarea class='input' name='$key' cols='40' rows='5'>".HTMLEntities2($row[$key])."</textarea>");
                output("`n".HTMLEntities($row[$key])."`n");
                break;
            default:
                $output.=("<input size='50' name='$key' value=\"".HTMLEntities2($row[$key])."\">");
                //output("`n$val");
        }
        output("</td></tr>",true);
    }
    output("</table>",true);
    if ($nosave) {} else output("<input type='submit' class='button' value='Salva'>",true);

}

function manutenzione($manu){
    global $session,$revertsession,$REQUEST_URI;
    if ($manu == 1 AND $session['user']['superuser'] < 3){
        $session=$revertsession;
        $session['user']['restorepage']=$REQUEST_URI;
        $session['allowednavs']=array();
        addnav("","village.php");
        redirect("village.php");
    }
}

function ordinal($val){
    //$exceptions = array(1=>"st",2=>"nd",3=>"rd",11=>"th",12=>"th",13=>"th");
    $x = ($val % 100);
    if (isset($exceptions[$x])){
        return $val.$exceptions[$x];
    }else{
        $x = ($val % 10);
        if (isset($exceptions[$x])){
            return $val.$exceptions[$x];
        }else{
            return $val."°";
        }
    }
}

function closetags($string, $tags) {
    $tags = explode('`',$tags);
    reset($tags);
    while (list($key,$val)=each($tags)){
        $siht = trim($siht);
        if ($siht=='') continue;
        if (substr_count($string,'`'.$siht)%2) $string .= '`'.$siht;
    }
    return $string;
}
function assegna_mercenario($luogo,$vettore,$pagato){
    global $session;
    // Se $pagato false, vuol dire che ha guadagnato i servizi di un mercenario combattendo
    // in questo caso si imposta $startcasi a 3 per saltare i casi in cui la gemma viene persa
    // e $merc_azn a null per non visualizzare l'azione con cui il mercenario afferra la gemma
    if ( $pagato ) {
       output("`n`2Sei indecis".($session[user][sex]?"a":"o")." su quale mercenario scegliere, perciò dici loro di mettersi in fila, volti loro le spalle e lanci `&una gemma`2 in aria dietro di te.  `n ");
       output(" Colui che riuscirà ad afferrarla avrà l'onore di accompagnarti nelle tue peripezie e combattere al tuo fianco in battaglia se risulterà necessario ...`n`n ");
       $session['user']['gems']--;
       debuglog("Paga 1 gemma ai mercenari del$luogo ");
       $startcasi = 1;
       $merc_azio=array(' ','con un balzo afferra al volo la `&gemma`2,','strappa agli altri mercenari la `&gemma`2, ','raggiunge la `&gemma`2 per primo, ',
                        'spintonando si fà largo tra gli altri mercenari e prende la `&gemma`2, ','riesce a trovare la `&gemma`2 prima di tutti gli altri, ',
                        'raccoglie la `&gemma`2 che rimbalza davanti ai suoi piedi, ');
       // Selezione casuale dell'azione con cui il mercenario afferra la gemma
       $merc_azn = e_rand(1,count($merc_azio)-1);
    }else{
        $startcasi = 3;
        $merc_azn = "" ;
    }
    $merc_defmod = 0.0 ;
    $merc_atkmod = 0.0 ;

    // Costruzione array mercenari, importante: devono avere stesso numero di campi.  Un campo per ogni razza gestita.
    // N.B. gli elementi di indice zero sono impostati vuoti
    // Costruzione array nome
    $merc_nome=array(' ','Tryxlk  ','Grimlok ','Marrty  ','Gimbli  ','Dithas  ','Bjiorn  ','Walen   ','Chondos ','Ravaaga ','Dagnar  ','Aleyers ',
                     'Tasha   ','Conan   ','Thoaron ','Warren  ','Granros ','Vicamos ','Reha    ','Kevam   ','Belen   ','Lonsten ','Glaoman ');
    // Costruzione array articolo per razza
    $merc_arti=array(' ','il ','l\' ','il ','il ','il ','il ','l\' ','il ','il ','la ','l\' ','il ','il ','il ','l\' ','il ','il ','il ','il ','il ','il ','l\' ');
    // Costruzione array razza
    $merc_race=array(' ','`2Troll','`^Elfo','`&Cavaliere','`#Nano','`3Druido','`@Goblin','`%Orco','`$Vampiro','`5Lich','`&Fanciulla delle Nevi','`4Oni','`3Satiro',
                     '`#Gigante delle Tempeste','`$Barbaro','`%Amazzone','`^Titano','`$Demone','`(Centauro','`8Licantropo','`)Minotauro','`^Cantastorie','`@Eletto');
    // Costruzione array aggettivo
    $merc_aggp=array(' ','Valoroso ','Arciere ','Veterano ','Arcigno ','Saggio ','Ardito ','Indomito ','Crudele ','Audace ','Incantatrice ','Impavido ','Fedele ','Robusto ',
                     'Spietato ','Coraggiosa ','Leale ','Sanguinario ','Temerario ','Feroce ','Vigoroso ','Esperto ','Virtuoso ');
    // Costruzione array aggettivo negativo
    $merc_aggn=array(' ','Vile ','Disarmato ','Pivello ','Pappamolle ','Ignorante ','Codardo ','Pauroso ','Dolce ','Prudente ','Debole ','Pavido ','Infedele ','Gracile ',
                     'Misericordioso ','Timorosa ','Sleale ','Compassionevole ','Timido ','Mite ','Fiacco ','Ubriaco ','Infame ');
    // Costruzione array messaggio di combattimento
    $merc_rmsg=array(' ','I colpi di clava del Troll sono terrificanti e spaventano il nemico. ',
                     'La mira dell\'Elfo è famosa, le sue frecce colpiscono in pieno {badguy}. ',
                     'Il Veterano estrae la sua spada e combatte con frenesia. ',
                     'I colpi di ascia del Nano sono implacabili e i suoi fendenti affilati stroncano il nemico. ',
                     'Il Druido polverizza gli avversari con le arti magiche dei suoi incantesimi. ',
                     'I frutti tentatori offerti dal Goblin attirano il tuo avversario sotto i tuoi colpi. ',
                     'L\'Orco scaglia vigorosi colpi contro il nemico smorzandone la capacità combattiva. ',
                     'La sete di sangue del Vampiro gli consente di assalire il nemico con violenza inaudita. ',
                     'Il salmodiare parole magiche del Lich terrorizza {badguy} che si mette sulla difensiva. ',
                     'La bellezza della Fanciulla delle nevi ammalia il nemico che resta semiparalizzato. ',
                     'L\'Oni con poderosi colpi di mazza ferrata indebolisce il nemico. ',
                     'Il Satiro attacca {badguy} con determinazione tramite una ubriacante danza di combattimento. ',
                     'Il Gigante torreggia sopra l\'avversari che, timoroso, fatica a reggere i tuoi assalti. ',
                     'Il Barbaro attacca {badguy} come un forsennato e lo obbliga solo a difendersi. ',
                     'Le frecce scagliate dall\'Amazzone colpiscono impietosamente {badguy} indebolendolo. ',
                     'Il Titano afferra {badguy} e con uno sforzo immane lo rende vulnerabile ai tuoi assalti. ',
                     'Il Demone ti aiuta nello scontro cercando di strappare l\'anima del tuo nemico. ',
                     '{badguy} è in grosse difficoltà nello schivare i colpi poderosi e precisi del Centauro. ',
                     'Il Licantropo azzanna e artiglia il nemico massacrandolo. ',
                     'Il Minotauro carica gli avversari a testa bassa come un toro infuriato costringendoli alla difensiva. ',
                     'I racconti del Cantastorie distraggono il nemico che resta in balia dei tuoi colpi. ',
                     'L`Eletto affronta a viso aperto il nemico e lo incalza senza tregua. ');
    // Costruzione array messaggio di ritiro dalla battaglia
    $merc_woff=array(' ','Sorge il sole, un raggio solare colpisce il Troll che si pietrifica. ',
                     'L\'Elfo ha finito le sue frecce e decide di tornare nel suo clan. ',
                     'Il Cavaliere non riesce più a reggere il ritmo e si ritira per riposarsi. ',
                     'L\'ascia del Nano ha perso il suo filo ed egli si ferma a cercare una cote. ',
                     'Il Druido esaurisce gli ingredienti per le sue arti magiche e va in cerca di vischio. ',
                     'Il Goblin è stanco e si ferma per riprendere fiato. ',
                     'L\'Orco preferisce tornare nel sua palude per dedicarsi ad altre attività. ',
                     'La sete di sangue del Vampiro si è placata. ',
                     'Il Lich ritrova la sua anima e inizia la sua metamorfosi. ',
                     'La Fanciulla delle nevi si scioglie al sole lasciandoti solo contro il nemico. ',
                     'La mazza dell\'Oni si arrugginisce ed egli non può più combattere al tuo fianco. ',
                     'Il Satiro decide che è meglio andare a danzare con le ninfe. ',
                     'Al Gigante è venuta fame ed egli decide di andare a cercare di cibo. ',
                     'Il Barbaro preferisce andare a civilizzarsi e ti abbandona al tuo destino. ',
                     'Povera Amazzone, si deve ritirare dalla lotta perchè il suo arco si è rotto ! ',
                     'Il Titano esaurisce le sue energie con un ultimo sforzo. ',
                     'Il Demone ha saziato la sua fame di anime per oggi. ',
                     'Il Centauro si è azzoppato e resti solo in battaglia. ',
                     'La luna piena è sparita e con essa il supporto del Licantropo. ',
                     'Il Minotauro si è perso nel labirinto della foresta e resti da solo. ',
                     'Al Cantastorie si è seccata la gola. ',
                     'L\'Eletto perde le sue forze e scompare dalla tua vista. ');
    // Costruzione array messaggio di combattimento negativo
    $merc_rmsn=array(' ','Il Troll trema come una foglia di fronte al nemico che prende maggior coraggio contro di te. ',
                     'Senza arco l\'Elfo non può aiutarti nel combattimento. ',
                     'Il Pivello estrae la sua spada e ci inciampa miseramente tra le risate generali. ',
                     'Il Nano non riesce a brandeggiare la sua ascia troppo pesante e non impensierisce {badguy}. ',
                     'Il Druido non conosce le giuste formule per i suoi incantesimi e ti trasforma momentaneamente in un rospo. ',
                     'Il Goblin ha paura di affrontare {badguy} e corre a nascondersi dietro un cespuglio. ',
                     'L\'Orco si nasconde dietro di te tremando come una foglia e impacciandoti nei movimenti. ',
                     'Il Vampiro sente profumo di aglio e scappa via terrorizzato. ',
                     'Il Lich resta ammutolito dalla paura di affrontare il nemico. ',
                     'La gracile Fanciulla ti salta in braccio supplicandoti di proteggerla da {badguy}. ',
                     'L\'Oni scappa alla prima schermaglia lasciandoti solo di fronte al nemico. ',
                     'Il Satiro distratto dalle sue ultime conquiste amorose ti ostacola nelle tue manovre di combattimento. ',
                     'Il Gigante si fà piccolo piccolo di fronte al nemico mettendoti in grosse difficoltà. ',
                     'Il Barbaro si ritira di fronte al nemico animato da buoni sentimenti. ',
                     'La paura dell\'Amazzone è tale che sbaglia mira e ti colpisce alle spalle. ',
                     'Il Titano tradisce le tue intenzioni mettendoti in balia degli attacchi del tuo nemico. ',
                     'Preso da rimorsi di coscienza, il Demone aiuta angelicamente {badguy} a sostenere il tuo assalto. ',
                     'Timidamente il Centauro cerca di fronteggiare {badguy} ma ti lascia esposto ai colpi precisi del tuo avversario. ',
                     'Il Licantropo fugge di fronte al nemico come un agnellino impaurito. ',
                     'Il Minotauro carica debolmente gli avversari come un vitello, ma sbaglia mira e ti viene addosso. ',
                     'Il Cantastorie ubriaco inciampa nei tuoi piedi e ti impedisce di combattere al meglio delle tue possibilità. ',
                     'L\'Eletto infame ha sabotato le tue armi che perdono di efficacia. ');
    // Termine costruzione array mercenari

    // Selezione casuale del mercenario, del numero dei turni e delle caratteristiche in attacco e difesa
    $merc_num = e_rand(1,count($merc_nome)-1);
    $merc_comp=$merc_nome[$merc_num].$merc_arti[$merc_num].$merc_race[$merc_num] ;
    $mercenario= $merc_race[$merc_num].' '.$merc_aggp[$merc_num];
    $mercturns=(e_rand(15,25));
    $merc_defmod = 1+(e_rand(1,6)*0.1);
    $merc_atkmod = 1+(e_rand(1,6)*0.1);
    $merc_wearoff = $merc_woff[$merc_num] ;
    $merc_roundmsg = $merc_rmsg[$merc_num] ;
    $merc_caso = e_rand(1,20);
	$casimax = 20 ;
                
    $pietradipoker = verificapietrapoker(); 
                
    // Si ha il 5% di probabilità che il mercenario non sia un aiuto ma una penalizzazione in questo caso si usano gli aggettivi e i messaggi di combattimento negativi
    
    switch (e_rand($startcasi, $casimax)) {
        case 1:
            // La gemma va persa e Louie direttamente ti offre i suoi servizi
            if ($pietradipoker) {
            	output("La `\$Pietra di Poker`2 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna si accanisce su di te. ");
            }	
            output("Nessun mercenario riesce a trovare la `&gemma`2 e si scatena un furibondo parapiglia. `n");
            output("Attonit".($session[user][sex]?"a":"o")." resti a guardare i mercenari che si azzuffano tra di loro, rimpiangendo la `&gemma`2 buttata inutilmente. `n");
            output("`&Louie`2 si fà una grassa risata ma ti tranquillizza: offrirà lui stesso in prima persona i suoi servigi combattendo al tuo fianco per un po' ! `n`n");
            $merc_defmod = 1.8 ;
            $merc_atkmod = 1.8 ;
            $mercturns = 10 ;
            $merc_roundmsg = 'Louie combatte come una furia non dando tregua al nemico e incalzandolo implacabilmente' ;
            $merc_wearoff = 'Louie ha esaurito il compito e si ritira, toccherà a te proseguire nel combattimento' ;
            $mercenario= 'Capo Mercenari' ;
            $session['bufflist'][$vettore] = array("name"=>$mercenario,"rounds"=>$mercturns,"wearoff"=>$merc_wearoff,"defmod"=>$merc_defmod,"atkmod"=>$merc_atkmod,"roundmsg"=>$merc_roundmsg,"activate"=>"defense");
        break;
        case 2:
            // La gemma va persa e scornato torni da dove sei venuto
            if ($pietradipoker) {
            	output("La `\$Pietra di Poker`2 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna si accanisce su di te. ");
            }
            output("Nessun mercenario riesce a trovare la `&gemma`2 e si scatena un furibondo parapiglia. `n");
            output("Attonit".($session[user][sex]?"a":"o")." resti a guardare i mercenari che si azzuffano tra di loro, rimpiangendo la `&gemma`2 buttata inutilmente. `n");
            output("`&Louie`2 si fà una grassa risata e poi si butta anche lui nella mischia impugnando un nodoso randello cercando di placare gli animi. `n");
            output("Scornat".($session[user][sex]?"a":"o").", torni nel$luogo non osando protestare coi mercenari per la `&gemma`2 andata perduta prima che questi rivolgano le loro attenzioni verso di te. `n");
        break;
        case 3:
            // Il mercenario non si rivela un aiuto ma una penalizzazione. Si usano aggettivi e messaggi di combattimento negativi
            if ($pietradipoker) {
            	output("La `\$Pietra di Poker`2 in tuo possesso mostra tutta la sua influenza negativa e la sfortuna si accanisce su di te. ");
            }
            output("`b`2$merc_comp $merc_aggn[$merc_num]`b`2$merc_azio[$merc_azn] ti offrirà i suoi servigi combattendo al tuo fianco per un po' ! `n`n");
            $merc_defmod = 0.8 ;
            $merc_atkmod = 0.8 ;
            $merc_roundmsg = $merc_rmsn[$merc_num] ;
            $mercenario= $merc_race[$merc_num].' '.$merc_aggn[$merc_num];
            $session['bufflist'][$vettore] = array("name"=>$mercenario,"rounds"=>$mercturns,"wearoff"=>$merc_wearoff,"defmod"=>$merc_defmod,"atkmod"=>$merc_atkmod,"roundmsg"=>$merc_roundmsg,"activate"=>"defense");
        break;
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
        case 9:
        case 10:
        case 11:
        case 12:
        case 13:
        case 14:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
            // Un mercenario offre i suoi servizi
            output("`b`2$merc_comp $merc_aggp[$merc_num]`b`2$merc_azio[$merc_azn] ti offrirà i suoi servigi combattendo al tuo fianco per un po' ! `n`n");
            // Se la razza del mercenario è uguale a quella del pg c'è maggior simbiosi e si aumenta la capacità difensiva
            if ( $session['user']['race'] == $merc_num ) {
                $merc_defmod += 0.2 ;
                output("Valutandovi un istante vi rendete conto che essendo della stessa razza avete ricevuto il medesimo addestramento militare. `n ");
                output("Questo rende la vostra difesa più forte dato che combattendo in coppia, avete maggiore affinità e sincronismo dei vostri movimenti. `n");
            }else{
                output("Fronteggiandovi un istante vi rendete conto che essendo di razze diverse non avete ricevuto il medesimo addestramento militare. `n");
                output("Ma riuscirete lo stesso a compensare la vostra mancanza di abitudine a combattere assieme e i nemici dovranno temervi. `n");
            }
            $session['bufflist'][$vettore] = array("name"=>$mercenario,"rounds"=>$mercturns,"wearoff"=>$merc_wearoff,"defmod"=>$merc_defmod,"atkmod"=>$merc_atkmod,"roundmsg"=>$merc_roundmsg,"activate"=>"defense");
        break;
    }
}

//Hugues : Data del giorno in stile medievale
//         $timestamp è espresso in giorni e serve per calcolare date differenti dalla data in corso
function ottienidatadelgiorno($datagiorno){
		
		$mesedioggi = intval(date("m"));	
	    $meselatino = array(1=>'Ianuarius',
	                           'Februarius',
	                           'Martius',
	                           'Aprilis',
	                           'Maius',
	                           'Iunius',
	                           'Iulius',
	                           'Augustus',
	                           'September',
	                           'October',
	                           'November',
	                           'December');
	    $datadioggi = (date("Y,")-773).", addì ".date("d ").$meselatino[$mesedioggi];
			   
    return $datadioggi;
}

//Hugues : Calcola Stagione 1=inverno 2=primavera 3=estate 4=autunno

function calcolastagione($stagione){
	global $stagione;
    $mesedioggi = intval(date("m"));
    switch ($mesedioggi) {
            case 1: case 2: 
                $stagione=1;
            break;
            case 3: case 4: case 5:
                $stagione=2;
            break;
            case 6: case 7: case 8:
                $stagione=3;
            break;
            case 9: case 10: case 11:
                $stagione=4;
            break;      
            case 12:
                $stagione=1;
            break;      
    }    
    return $stagione;
}   


function commutarazza($scegli) {
        switch ($scegli) {
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
                output("`0Umana`0");
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
                output("`^Licantropo`0");
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
            case 90:
                output("`%M`5o`%d`5e`%r`5a`%t`5o`%r`5e");
                break;
            case 100:
                output("`\$A`(d`^m`@i`#n");
                break;
        }
}

function visualizzaoggetto($oggetto,$valorizza,$testo) {
	
	global $session;	
	
	$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
	
	if ($oggetto == "0") {
	        $ogg = "Nulla";
	} else {
	        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
	        $resultoo = db_query($sqlo) or die(db_error(LINK));
	        $rowo = db_fetch_assoc($resultoo);
	        $ogg = "`@".$rowo['nome']." `0";
	        
	        if ($valorizza) {
	    
				$valvendob = $rowo['valore'];
			    if ($rowo[usuramax] > 0) {
			        $valvendof = $rowo['valore']*$rowo['usura']/$rowo['usuramax'];
			    } else {
			        $valvendof = $rowo['valore'];
			    }
			    if ($rowo[usuramagicamax] > 0) {
			        $valvendom = $rowo['valore']*$rowo['usuramagica']/$rowo['usuramagicamax'];
			    } else {
			        $valvendom = $rowo['valore'];
			    }
			    $valvendo = intval((2 * $valvendob + 2 * $valvendof + $valvendom)/5);
			    if ($rowo['usuramagica']==0 AND $rowo['usuramagicamax']) { 
				    $valvendo=intval($valvendo/2);
				}    
			}
	}
	    
	
	output ("<table>", true);
	output ("<tr><td>`2$testo </td><td>`@$ogg</td></tr>", true);
	if ($oggetto != "0") {
		output ("<tr><td>`6Descrizione: </td><td>`^".$rowo['descrizione']."</td></tr>", true);
		if ($rowo['pregiato']==true) {
			output ("<tr><td>`^E' pregiato </td><td>",true);
		} else {
			output (" </td><td>",true);
		}		
		if ($rowo['potere']==true) {
			output ("`3Ha `#poteri`3 magici da usare </td><td>",true);
		} else {
			output (" </td></td>", true);
		}
		output ("<tr><td> </td></tr>", true);
		output ("<tr><td> </td></tr>", true);
		output ("<tr><td>`3Livello: </td><td>`#".$rowo['livello']."</td></tr>", true);
		output ("<tr><td>`7Aiuto HP: </td><td>`&" . $rowo['hp_help'] . "</td></tr>", true);
		output ("<tr><td>`4Aiuto Attacco: </td><td>`\$" . $rowo['attack_help'] . "</td></tr>", true);
		output ("<tr><td>`6Aiuto Difesa: </td><td>`^" . $rowo['defence_help'] . "</td></tr>", true);
		output ("<tr><td>`8Turni Extra: </td><td>`^" . $rowo['turns_help'] . "</td></tr>", true);
		if ($rowo['gold_help']!=0) {
			output ("<tr><td>`^Oro generato: </td><td>`^" . $rowo['gold_help'] . "/giorno</td></tr>", true);
		}
		if ($rowo['gems_help']!=0) {
			output ("<tr><td>`&Gemme generate: </td><td>`&" . $rowo['gems_help'] . "/giorno</td></tr>", true);
		}
		if ($rowo['special_help']!=0) {
			output ("<tr><td>`5Abilità: </td><td>`@" . $skills[$rowo['special_help']] . "(`^".$rowo['special_use_help']."`@)</td></tr>", true);
		}
		if ($rowo['heal_help']!=0) {
			output ("<tr><td>`5Cura: </td><td>`@" . $rowo['heal_help'] . " hp</td></tr>", true);
		}
		if ($rowo['quest_help']!=0) {
			output ("<tr><td>`5Quest: </td><td>`@" . $rowo['quest_help'] . "punti quest</td></tr>", true);
		}
		if ($rowo['exp_help']!=0) {
			output ("<tr><td>`5Esperienza: </td><td>`@+" . $rowo['exp_help'] . "%</td></tr>", true);
		}
		output ("<tr><td>`2Cariche totali: </td><td>`@" . $rowo['potere'] . "</td></tr>", true);
		output ("<tr><td>`2Cariche residue:</td><td>`@" . $rowo['potere_uso'] . "</td></tr>", true);
		output ("<tr><td>`2Potenziamenti residui:</td><td>`@" . $rowo['potenziamenti'] . "</td></tr>", true);
		if ($rowo['usuramax']>0) {
			output ("<tr><td>`)Usura fisica:</td><td>`(".intval(100-(100*$rowo['usura']/$rowo['usuramax']))."</td></tr>", true);
		} else {
			output ("<tr><td>`)Usura fisica:</td><td>`(Non Soggetto</td></tr>", true);
		}
		if ($rowo['usuramagicamax']>0) {
			output ("<tr><td>`)Usura magica:</td><td>`8".intval(100-(100*$rowo['usuramagica']/$rowo['usuramagicamax']))."</td></tr>", true);
		} else {
			output ("<tr><td>`)Usura magica:</td><td>`8Non Soggetto</td></tr>", true);
		}
		output ("<tr><td>  </td><td>  </td></tr>", true);
		if ($valorizza) {
			output ("<tr><td>`2Valore Vendita: </td><td>`@" . $valvendo . "</td></tr>", true);
		} else {
			output ("<tr><td>`7Valore in Gemme: </td><td>`&".$row['mercatino_gemme']."</td></tr>", true);
	        output ("<tr><td>`7Valore in Oro: </td><td>`&".$row['mercatino_oro']."</td></tr>", true);
		}		
		output ("<tr><td>  </td><td>  </td></tr>", true);
		
	}
	output ("</table>", true);
    
	
}	



function scambiaoggetto($oggetto,$oggettozaino) {
	
	global $session;

	if ($session['user']['dragonkills'] < 10) {
        $livello = 1 + (3 * $session['user']['reincarna']);
    } else if ($session['user']['dragonkills'] >= 10 AND $session['user']['dragonkills'] < 19) {
        $livello = 2 + (3 * $session['user']['reincarna']);
    } else if ($session['user']['dragonkills'] >= 19) {
        $livello = 3 + (3 * $session['user']['reincarna']);
    }
    $ogg = $session['user']['oggetto'];
    $zai = $session['user']['zaino'];
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $ogg";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    $sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $zai";
    $resultz = db_query($sqlz) or die(db_error(LINK));
    $rowz = db_fetch_assoc($resultz);
    if ($livello>=$rowz['livello']){
        if (!$session['user']['oggetto'] AND !$session['user']['zaino']) {
            output ("`2Non possiedi oggetti da scambiare.`n`n");
        } else if (!$session['user']['zaino']) {
            output ("`2Non hai nulla nello zaino.`n`n");
        } else {
            
            output ("`2Hai scambiato l'oggetto equipaggiato (`@".$rowo['nome']."`2) con quello nello zaino (`@".$rowz['nome']."`2).`n`n");
            			
            $session['user']['attack'] -= $rowo['attack_help'];
            $session['user']['defence'] -= $rowo['defence_help'];
            $session['user']['bonusattack'] -= $rowo['attack_help'];
            $session['user']['bonusdefence'] -= $rowo['defence_help'];
            $session['user']['maxhitpoints'] -= $rowo['hp_help'];
            $session['user']['hitpoints'] -= $rowo['hp_help'];
            if ($rowo['usuramagica']!=0 AND $rowo['usuramagicamax']!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];

            $deposito = $session['user']['oggetto'];
            $session['user']['oggetto'] = $session['user']['zaino'];
            $session['user']['zaino'] = $deposito;

            $session['user']['attack'] += $rowz['attack_help'];
            $session['user']['defence'] += $rowz['defence_help'];
            $session['user']['bonusattack'] += $rowz['attack_help'];
            $session['user']['bonusdefence'] += $rowz['defence_help'];
            $session['user']['maxhitpoints'] += $rowz['hp_help'];
            $session['user']['hitpoints'] += $rowz['hp_help'];
            if ($rowz['usuramagica']!=0 AND $rowz['usuramagicamax']!=0) $session['user']['bonusfight'] += $rowz['turns_help'];

            if ($session['user']['hitpoints'] <1) $session['user']['hitpoints'] = 1;
        }
    }else {
        output("`2Purtroppo il livello dell'oggetto nello zaino è troppo alto per te, l'unica cosa che puoi fare è venderlo.");
    }

}

function valutaoggetto($oggetto) {
	
	if ($oggetto == "0") {
	        $ogg = "Nulla";
	} else {
	        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
	        $resultoo = db_query($sqlo) or die(db_error(LINK));
	        $rowo = db_fetch_assoc($resultoo);
	        $ogg = "`@".$rowo['nome']." `0";
		
	}
	
	$valvendb = $rowo['valore'];
	if ($rowo[usuramax] > 0) {
		$valvendf = $rowo['valore']*$rowo['usura']/$rowo['usuramax'];
	} else {
	    $valvendf = $rowo['valore'];
	}
	if ($rowo[usuramagicamax] > 0) {
		$valvendm = $rowo['valore']*$rowo['usuramagica']/$rowo['usuramagicamax'];
	} else {
	    $valvendm = $rowo['valore'];
	}
	$valvendz = intval((2 * $valvendb + 2 * $valvendf + $valvendm)/10);
	if ($rowo['usuramagica']==0 AND $rowo[usuramagicamax] > 0) $valvendz=intval($valvendz/2);
	output ("`@Puoi mettere in vendita solo l'oggetto che hai nello zaino!`n`n");
	if ($rowo[dove]!=30) output("<form action='fabbro_mercatino.php?og=vendiz&id=$rowz[id_oggetti]&value=$valvendz' method='POST'>",true);
	if ($rowo[dove]!=30) addnav("", "fabbro_mercatino.php?og=vendiz&id=$rowz[id_oggetti]&value=$valvendz");
	output ("<table>", true);
	output ("<tr><td>`6Nello zaino hai : </td><td>`b`^" . $ogg . "`b</td><td>Oro: <input name='oro' size='3'></td><td>Gemme: <input name='gemme' size='3'></td>",true);
	if ($rowo[dove]!=30) {
		output ("<td><input type='submit' class='button' value='Proponi'></td>",true);
	} else {
	    output ("<td>Gli artefatti non possono essere venduti</td>",true);
	}
	output ("</tr>", true);
	output ("</table>", true);
	if ($rowo[dove]!=30) output("</form>",true);
	output ("`nBrax valuta questo oggetto `&$valvendz gemme`@.`n");
}

function visualizzadrago($drago) {
	
	global $session;
	global $row;	

	$sqlo = "SELECT * FROM draghi WHERE id = $drago ";
 	$resulto = db_query($sqlo) or die(db_error(LINK));
    $row = db_fetch_assoc($resulto);
    $coloredrago = coloradrago($row['tipo_drago']);
    output ("<table>", true);
    output ("<tr><td>`7Nome: </td><td>`&".$row['nome_drago']."</td></tr>", true);
    output ("<tr><td>`6Tipo: </td><td>".$coloredrago."".$row['tipo_drago']."</td></tr>", true);
    output ("<tr><td>`3Eta: </td><td>`#".$row['eta_drago']."</td></tr>", true);
    output ("<tr><td>`7Carattere: </td><td>`&".$row['carattere']."</td><td>`7Cavalcare draghi: </td><td>`&".$session['user']['cavalcare_drago']."</td></tr>", true);
    output ("<tr><td>`4Attacco: </td><td>`\$".$row['attacco'] . "</td></tr>", true);
    output ("<tr><td>`6Difesa: </td><td>`^".$row['difesa'] . "</td></tr>", true);
    output ("<tr><td>`8Vita: </td><td>`(".$row['vita']."</td></tr>", true);
    output ("<tr><td>`2Tipo soffio: </td><td>`@".$row['tipo_soffio']."</td></tr>", true);
    output ("<tr><td>`4Danno_soffio:</td><td>`\$".$row['danno_soffio']."</td></tr>", true);
    output ("<tr><td>`5Territorio preferito:</td><td>`%".$row['combatte']."</td></tr>", true);
    output ("<tr><td>`7Bonus territorio:</td><td>`&".$row['bonus']."</td></tr>", true);
    output ("<tr><td>`6Aspetto:</td><td>`^".$row['aspetto']."</td></tr>", true);
    output ("<tr><td>`3Valore:</td><td>`#".$row['valore']." `3gemme</td></tr>", true);
    output ("<tr><td>  </td><td>  </td></tr>", true);
    output ("</table>", true);

}

function valutadrago($drago,$coeffvendi) {
	
	global $session;	
	global $row;
	
	visualizzadrago($drago);

    $valvendo = intval(($row['valore']-$row['bonus_erold_valore']) / $coeffvendi);
     
    if ($row['bonus_erold_valore'] > 0) {
		output ("`n`^Noto però che `@Erold `^ha effettuato un trattamento sul tuo Drago, per cui mi vedrò costretto a decurtare `#`b".$row['bonus_erold_valore']."`^`b gemme dalla sua valutazione`n");
	}    
    output("`n`^Se vuoi fare un affare, potrei acquistare il tuo bellissimo Drago per diciamo ....  `#`b$valvendo`^`b gemme `n"); 

}

Function coloradrago($tipodrago) {
				
		if ($tipodrago == 'Rosso') $coloredrago = "`\$";
        if ($tipodrago == 'Nero') $coloredrago = "`9";
        if ($tipodrago == 'Blu') $coloredrago = "`!";
        if ($tipodrago == 'Verde') $coloredrago = "`@";
        if ($tipodrago == 'Bianco') $coloredrago = "`&";
        if ($tipodrago == 'Zombie') $coloredrago = "`0";
        if ($tipodrago == 'Scheletro') $coloredrago = "`8";
        if ($tipodrago == 'Bronzo') $coloredrago = "`(";
        if ($tipodrago == 'Argento') $coloredrago = "`7";
        if ($tipodrago == 'Oro') $coloredrago = "`6";
        if ($tipodrago == 'Platino') $coloredrago = "`R";
                     
        return $coloredrago ;

}


Function msgscherno($badguy) {

	global $session;
	
	$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
    $taunt = db_fetch_assoc($result);
    $taunt = str_replace("%x",($session['user']['weapon']),$taunt[taunt]);
    $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
    $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
    $taunt = str_replace("%w",$session['user']['name'],$taunt);
    
    addnews("`%".$session['user']['name']."`5 ha sfidato il suo maestro, `^".$badguy['creaturename']."`5 ed ha perso!`n$taunt");

}

Function assegnapigpen($name) {
	
	global $session;
	
	savesetting("pigpen",addslashes("$name"));
  	$y = substr($session['user']['name'], 0, 1);
	if ($y == "`") {
		$newtitle = substr($session['user']['name'], 0, 2)."PigPen";
	}else{
		$newtitle="PigPen";
	}
	$n = $session['user']['name'];
	$x = strpos($n,$session['user']['title']);
	if ($x!==false){
		$regname=substr($n,$x+strlen($session['user']['title']));
		$session['user']['name'] = substr($n,0,$x).$newtitle.$regname;
		$session['user']['title'] = $newtitle;
	}else{
		$regname = $session['user']['name'];
		$session['user']['name'] = $newtitle." ".$session['user']['name'];
		$session['user']['title'] = $newtitle;
   }
}

Function calcolafascino() {
	
	global $session;
	
	$sql = "SELECT count(*) as numgiocatori, sum(charm) as totfascino, max(charm) as maxfascino FROM accounts WHERE dragonkills>0 and superuser=0 ";
    $result = db_query($sql) or die(db_error(LINK));   
	$row = db_fetch_assoc($result);
	$numgiocatori = $row['numgiocatori'];
	$totfascino = $row['totfascino'];
	$maxfascino = $row['totfascino'];
	
	$mediafascino = intval($totfascino / $numgiocatori);
	$primolimite = intval($mediafascino / 2);
	$secondolimite = $mediafascino;
	$fasciasup = intval(($maxfascino - $mediafascino) / 5 );
	$terzolimite = $mediafascino + $fasciasup;
	$quartolimite = $terzolimite + $fasciasup;
	$quintolimite = $quartolimite + $fasciasup;
	$sestolimite = $quintolimite + $fasciasup;
	$settimolimite = $maxfascino;
	
	$fascino = $session['user']['charm'];

	if ($fascino == 0) {
		$fascia = 0 ;
	} else {		
		if ($fascino > 0 AND $fascino < $primolimite ) { 
			$fascia = 1 ;
		} else {
			if ($fascino > $primolimite AND $fascino < $secondolimite ) { 
				$fascia = 2 ;  		
			} else {
				if ($fascino > $secondolimite AND $fascino < $terzolimite ) { 
					$fascia = 3 ;
   				} else {
					if ($fascino > $terzolimite AND $fascino < $quartolimite ) { 
   						$fascia = 4 ;		
					} else {
						if ($fascino > $quartolimite AND $fascino < $quintolimite ) { 
							$fascia = 5 ;		    				
						} else {
							if ($fascino > $quintolimite AND $fascino < $sestolimite ) { 
								$fascia = 6 ;		    					
							} else {
   								if ($fascino > $sestolimite AND $fascino < $settimolimite ) {
									$fascia = 7 ;
								} else {
   									if ($fascino == $settimolimite OR $fascino > $settimolimite) {	
    									$fascia = 8 ;	
    								}
    							}	
							}
						}
					}
				}
			}
		}
	}

	return $fascia ;

}

/*
Function identificaarmatura() {
 	
 	global $session;
 
 	$sql = "SELECT armorname,armorart FROM armor WHERE level=".$session['user']['dragonkills']." AND defense=".$session['user']['armordef'];
  	$result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($session['user']['armordef'] <= 0){
    	
    	$pos = strpos($session['user']['armor']," -1");
    	output(" -> $pos <- ");
    	if (strpos($session['user']['armor']," -1") == false ) {
    		$articoloarmatura = $row[armorart] ;	//armatura rovinata da Faber
    	}else{
    		$articoloarmatura = 'la tua' ;			//armatura maledetta
    	}	
    }else{
    	$articoloarmatura = $row[armorart] ;
	}

	return $articoloarmatura ;
}
*/

Function identifica_armatura() {
	
	global $session,$ident_armatura;
	
	if ($session['user']['dragonkills'] > 36){
    	$draghiuccisi = 36 ; 
    }else{
    	$draghiuccisi = $session['user']['dragonkills'] ;
    }
	
    if ($session['user']['armordef'] == 0){
    	if (strpos($session['user']['armor']," -1") == true ) {  		
    			$difesa_armatura = $session['user']['armordef'] + 1 ; //Armatura rovinata da Faber
    			$sql = "SELECT armorname,armoraggpos FROM armor WHERE defense = $difesa_armatura AND level = $draghiuccisi " ;
   				$result = db_query($sql) or die(db_error(LINK));
    			$row = db_fetch_assoc($result);
    			$ident_armatura['articolo'] = $row[armoraggpos] ;
    			$ident_armatura['maledetta'] = false ;
    			$ident_armatura['t-shirt'] = false ;	
    	}else{
    		if (strchr($session['user']['armor'],"Maledetta")) {
    			$ident_armatura['articolo'] = "la tua" ; //Armatura Maledetta trovata nella Cittadella
				$ident_armatura['maledetta'] = true ;
				$ident_armatura['tshirt'] = false ;
			}else{	
	   			if ($session['user']['armordef'] == 0){
    				$ident_armatura['articolo'] = "la tua" ; //T-Shirt Standard
					$ident_armatura['maledetta'] = false ;
					$ident_armatura['tshirt'] = true ;
				}
			}
		}
	}else{
    	if (strchr($session['user']['armor'],"Maledetta")) {
    		$ident_armatura['articolo'] = "la tua" ; //Armatura Maledetta trovata nella Cittadella
			$ident_armatura['maledetta'] = true ;
		}else{	
			if (strpos($session['user']['armor']," -1") == true ) {  		
    			$difesa_armatura = $session['user']['armordef'] + 1 ; //Armatura rovinata da Faber
    			$sql = "SELECT armorname,armoraggpos FROM armor WHERE defense = $difesa_armatura AND level = $draghiuccisi " ;
   				$result = db_query($sql) or die(db_error(LINK));
    			$row = db_fetch_assoc($result);
    			$ident_armatura['articolo'] = $row[armoraggpos] ;
    			$ident_armatura['maledetta'] = false ;
    			$ident_armatura['t-shirt'] = false ;
    		}else{	
    			if (strchr($session['user']['armor'],"Leggendaria")) {
    				$ident_armatura['articolo'] = "la tua" ; //Armatura trovata nella Cittadella
					$ident_armatura['maledetta'] = false ;
					$ident_armatura['tshirt'] = false ;
				}else{
    				if (strpos($session['user']['armor']," +1") == true ) {
    					$difesa_armatura = $session['user']['armordef'] - 1 ; //Armatura potenziata da Faber
    					$sql = "SELECT armorname,armoraggpos FROM armor WHERE defense = $difesa_armatura AND level = $draghiuccisi " ;
   						$result = db_query($sql) or die(db_error(LINK));
    					$row = db_fetch_assoc($result);
    					$ident_armatura['articolo'] = $row[armoraggpos] ;
    					$ident_armatura['maledetta'] = false ;
    					$ident_armatura['tshirt'] = false ;	
       				}else{	
    					$sql = "SELECT armorname,armoraggpos FROM armor WHERE level = $draghiuccisi AND defense=".$session['user']['armordef'] ;
   						$result = db_query($sql) or die(db_error(LINK));
    					$row = db_fetch_assoc($result);
    					$ident_armatura['articolo'] = $row[armoraggpos] ; //Armatura Standard
    					$ident_armatura['maledetta'] = false ;
    					$ident_armatura['tshirt'] = false ;
    				}
    			}
    		}	
		}
	}
	return $ident_armatura ;
}

Function identifica_arma() {
	
	global $session,$ident_arma;
	
	if ($session['user']['dragonkills'] > 36){
    	$draghiuccisi = 36 ; 
    }else{
    	$draghiuccisi = $session['user']['dragonkills'] ;
    }
	
    if ($session['user']['weapondmg'] == 0){
    
    	if (strchr($session['user']['weapon'],"Maledetto")) {
    		$ident_arma['articolo'] = "il tuo" ; //Arma Maledetta trovata nella Cittadella
			$ident_arma['maledetta'] = true ;
			$ident_arma['pugni'] = false ;
		}else{	
	     	if (strpos($session['user']['weapon']," -1") == true ) {  		
    			$attacco_arma = $session['user']['weapondmg'] + 1 ; //Arma rovinata da Faber
    			$sql = "SELECT weaponname,weaponaggpos FROM weapons WHERE damage = $attacco_arma AND level = $draghiuccisi " ;
   				$result = db_query($sql) or die(db_error(LINK));
    			$row = db_fetch_assoc($result);
    			$ident_arma['articolo'] = $row[weaponaggpos] ;
    			$ident_arma['maledetta'] = false ;
    			$ident_arma['pugni'] = false ;	
    		}else{
    			if ($session['user']['weapondmg'] == 0){
    				$ident_arma['articolo'] = "i tuoi" ; //Pugni Standard
					$ident_arma['maledetta'] = false ;
					$ident_arma['pugni'] = true ;
				}
			}		
    	}	
    }else{
    	if (strchr($session['user']['weapon'],"Leggendario")) {
    		$ident_arma['articolo'] = "il tuo" ; //Arma trovata nella Cittadella
			$ident_arma['maledetta'] = false ;
			$ident_arma['pugni'] = false ;
		}else{
			if ((strchr($session['user']['weapon'],"allenamento")) OR (strchr($session['user']['weapon'],"Spada Lucente della Caverna Oscura"))) {
    			$ident_arma['articolo'] = "la tua" ; //Arma prestata dai maestri o Spada Lucente trovata nell'evento del Beholder
				$ident_arma['maledetta'] = false ;
				$ident_arma['pugni'] = false ;
			}else{
				if (strpos($session['user']['weapon']," +10") == true ) {
    				$attacco_arma = $session['user']['weapondmg'] - 10 ; //Arma potenziata dal veleno dell'Idra
    				$sql = "SELECT weaponname,weaponaggpos FROM weapons WHERE damage = $attacco_arma AND level = $draghiuccisi " ;
   					$result = db_query($sql) or die(db_error(LINK));
    				$row = db_fetch_assoc($result);
    				$ident_arma['articolo'] = $row[weaponaggpos] ;
    				$ident_arma['maledetta'] = false ;
    				$ident_arma['pugni'] = false ;	
       			}else{
    				if (strpos($session['user']['weapon']," +1") == true ) {
	    				$attacco_arma = $session['user']['weapondmg'] - 1 ; //Arma potenziata da Faber
	    				$sql = "SELECT weaponname,weaponaggpos FROM weapons WHERE damage = $attacco_arma AND level = $draghiuccisi " ;
	   					$result = db_query($sql) or die(db_error(LINK));
	    				$row = db_fetch_assoc($result);
	    				$ident_arma['articolo'] = $row[weaponaggpos] ;
	    				$ident_arma['maledetta'] = false ;
	    				$ident_arma['pugni'] = false ;	
	       			}else{
	       				if (strpos($session['user']['weapon']," -1") == true ) {  		
			    			$attacco_arma = $session['user']['weapondmg'] + 1 ; //Arma rovinata da Faber
			    			$sql = "SELECT weaponname,weaponaggpos FROM weapons WHERE damage = $attacco_arma AND level = $draghiuccisi " ;
			   				$result = db_query($sql) or die(db_error(LINK));
			    			$row = db_fetch_assoc($result);
			    			$ident_arma['articolo'] = $row[weaponaggpos] ;
			    			$ident_arma['maledetta'] = false ;
			    			$ident_arma['pugni'] = false ;
			    		}else{	
			    			$sql = "SELECT weaponname,weaponaggpos FROM weapons WHERE level = $draghiuccisi AND damage=".$session['user']['weapondmg'] ;
		   					$result = db_query($sql) or die(db_error(LINK));
		    				$row = db_fetch_assoc($result);
		    				$ident_arma['articolo'] = $row[weaponaggpos] ; //Arma Standard
		    				$ident_arma['maledetta'] = false ;
		    				$ident_arma['pugni'] = false ;
		    			}	
    				}
    			}
    		}	
    	}	
	}

	return $ident_arma ;
}

Function verificapietrapoker() {
	
	global $session;
	
	$pietradipoker = false ;
	$sql="SELECT * FROM pietre WHERE pietra=1 AND owner=".$session['user']['acctid'] ;
    $result = db_query($sql);
    if (db_num_rows($result) != 0) {
    	$pietradipoker = true ;           
    }
	
	return $pietradipoker ;

}

?>