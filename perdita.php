<?php
require_once "common.php";
page_header("Perdita oggetti");
// Vediamo se il player ha oggetti
$caso = e_rand(1,2); //se $caso=1 togliamo oggetto equipaggiato se =2 oggetto nello zaino
$oggetto = $session['user']['oggetto'];
$zaino = $session['user']['zaino'];
$sqlo = "SELECT * FROM oggetti WHERE id_oggetti = $oggetto";
        $resultoo = db_query($sqlo) or die(db_error(LINK));
        $rowo = db_fetch_assoc($resultoo);
        $valo = $rowo['valore'];
        $ogg = $rowo['nome'];
        if ($rowo['dove']==30) $oggetto=0; //gli artefatti non possono essere persi
$sqlz = "SELECT * FROM oggetti WHERE id_oggetti = $zaino";
        $resultoz = db_query($sqlz) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultoz);
        $valz = $rowz['valore'];
        $zai = $rowz['nome'];
        if ($rowz['dove']==30) $zaino=0; //gli artefatti non possono essere persi
if ($oggetto!=0 || $zaino!=0) { // Il player ha almeno 1 oggetto
    $chance=e_rand(1,100);
    if ($oggetto!=0 && $zaino!=0) {
        $chance += 5;  //Il player ha due oggetti, aumentiamo le chance di fargliene perdere uno
    }
    if ($rowo['livello']==$rowz['livello']) {
        $chance += 1; // Oggetti stesso livello, aumentiano le chance di perdita
    }
    if ($chance > 95) { // Bene, è giunto il momento di depredare il giocatore ;-)
        if ($rowo['valore']!=0 && $rowz['valore']!=0){
            $sfiga = e_rand(0,2);
            if ((($rowz['livello']+$sfiga) >= $rowo['livello'] AND $oggetto!=0) OR $zaino==0) {
                $rimbo=intval(e_rand(25,50)/100*$valo)+1;
                $oid=$rowo['id_oggetti'];
                output("`5Durante lo scontro con il `2Drago Verde`5 non ti sei accorto di aver perduto il tuo prezioso ");
                output("`3$ogg`5. Probabilmente nella foga di dare il colpo mortale al drago ti si è sfilato ed è ");
                output("andato perduto. Per tua fortuna avevi ancora la ricevuta dell'acquisto che Brax ti aveva rilasciato");
                output(" e grazie ad essa riesci a recuperare `6$rimbo gemme`5.");
                debuglog("ha perduto $ogg quando ha ucciso il Drago Verde e ha avuto $rimbo gemme come rimborso");
                $session['user']['gems']+=$rimbo;
                $caso=0; // Rimetto a zero la variabile caso per evitare di far perdere tutti e due gli oggetti :-)
                //Per pubblicizzare la perdita dell'oggetto al NewDay
                $value = addslashes("`#".$session['user']['name']." `@ha perso `b`^$ogg`b`@ quando ha ucciso il Drago Verde");
                $sql = "INSERT INTO commentary (section,author,comment,postdate)
                VALUES ('Perdita Oggetti',
                '".$rowo['id_oggetti']."',
                '$value',
                NOW())";
                db_query($sql) or die(db_error(LINK));
                //Fine pubblicità
                $oldloc = $rowo['dove_origine'];
                $oid = $rowo['id_oggetti'];
                $sql = "UPDATE oggetti SET dove=$oldloc WHERE id_oggetti=$oid";
                db_query($sql) or die(db_error(LINK));
                // Tolgo i bonus dati dall'oggetto equipaggiato
                $session['user']['oggetto'] = 0;
                $session['user']['attack'] = $session['user']['attack'] - $rowo['attack_help'];
                $session['user']['defence'] = $session['user']['defence'] - $rowo['defence_help'];
                $session['user']['bonusattack'] = $session['user']['bonusattack'] - $rowo['attack_help'];
                $session['user']['bonusdefence'] = $session['user']['bonusdefence'] - $rowo['defence_help'];
                $session['user']['maxhitpoints'] = $session['user']['maxhitpoints'] - $rowo['hp_help'];
                $session['user']['hitpoints'] = $session['user']['hitpoints'] - $rowo['hp_help'];
            }else {
                $rimbz=intval(e_rand(25,50)/100*$valz)+1;
                $oid=$rowz['id_oggetti'];
                output("`5Durante lo scontro con il `2Drago Verde`5 non ti sei accorto di aver perduto il tuo prezioso ");
                output("`3$zai`5. Probabilmente nella foga di dare il colpo mortale al drago ti si è sfilato ed è ");
                output("andato perduto. Per tua fortuna avevi ancora la ricevuta dell'acquisto che Brax ti aveva rilasciato");
                output(" e grazie ad essa riesci a recuperare `6$rimbz gemme`5.");
                debuglog("ha perduto $zai quando ha ucciso il Drago Verde e ha avuto $rimbz gemme come rimborso");
                $session['user']['gems']+=$rimbz;
                $caso=0; // Rimetto a zero la variabile caso per evitare di far perdere tutti e due gli oggetti :-)
                //Per pubblicizzare la perdita dell'oggetto al NewDay
                $value = addslashes("`#".$session['user']['name']." `@ha perso `b`^$zai`b`@ quando ha ucciso il Drago Verde");
                $sql = "INSERT INTO commentary (section,author,comment,postdate)
                VALUES ('Perdita Oggetti',
                '".$rowz['id_oggetti']."',
                '$value',
                NOW())";
                db_query($sql) or die(db_error(LINK));
                //Fine pubblicità
                $oldloc = $rowz['dove_origine'];
                $oid = $rowz['id_oggetti'];
                $sql = "UPDATE oggetti SET dove=$oldloc WHERE id_oggetti=$oid";
                db_query($sql) or die(db_error(LINK));
                $session['user']['zaino'] = 0;
            }
        }else if (($caso==1 && $oggetto!=0) or ($caso==2 && $zaino==0)){
            $rimbo=intval(e_rand(25,50)/100*$valo)+1;
            $oid=$rowo['id_oggetti'];
            output("`5Durante lo scontro con il `2Drago Verde`5 non ti sei accorto di aver perduto il tuo prezioso ");
            output("`3$ogg`5. Probabilmente nella foga di dare il colpo mortale al drago ti si è sfilato ed è ");
            output("andato perduto. Per tua fortuna avevi ancora la ricevuta dell'acquisto che Brax ti aveva rilasciato");
            output(" e grazie ad essa riesci a recuperare `6$rimbo gemme`5.");
            debuglog("ha perduto $ogg quando ha ucciso il Drago Verde e ha avuto $rimbo gemme come rimborso");
            $session['user']['gems']+=$rimbo;
            //Per pubblicizzare la perdita dell'oggetto al NewDay
            $value = addslashes("`#".$session['user']['name']." `@ha perso `b`^$ogg`b`@ quando ha ucciso il Drago Verde");
            $sql = "INSERT INTO commentary (section,author,comment,postdate)
            VALUES ('Perdita Oggetti',
            '".$rowo['id_oggetti']."',
            '$value',
            NOW())";
            db_query($sql) or die(db_error(LINK));
            //Fine pubblicità
            $oldloc = $rowo['dove_origine'];
            $oid = $rowo['id_oggetti'];
            $sql = "UPDATE oggetti SET dove=$oldloc WHERE id_oggetti=$oid";
            db_query($sql) or die(db_error(LINK));
            $session['user']['oggetto'] = 0;
            $session['user']['attack'] = $session['user']['attack'] - $rowo['attack_help'];
            $session['user']['defence'] = $session['user']['defence'] - $rowo['defence_help'];
            $session['user']['bonusattack'] = $session['user']['bonusattack'] - $rowo['attack_help'];
            $session['user']['bonusdefence'] = $session['user']['bonusdefence'] - $rowo['defence_help'];
            $session['user']['maxhitpoints'] = $session['user']['maxhitpoints'] - $rowo['hp_help'];
            $session['user']['hitpoints'] = $session['user']['hitpoints'] - $rowo['hp_help'];
        }else {
            $rimbz=intval(e_rand(25,50)/100*$valz)+1;
            $oid=$rowz['id_oggetti'];
            output("`5Durante lo scontro con il `2Drago Verde`5 non ti sei accorto di aver perduto il tuo prezioso ");
            output("`3$zai`5. Probabilmente nella foga di dare il colpo mortale al drago ti si è sfilato ed è ");
            output("andato perduto. Per tua fortuna avevi ancora la ricevuta dell'acquisto che Brax ti aveva rilasciato");
            output(" e grazie ad essa riesci a recuperare `6$rimbz gemme`5.");
            debuglog("ha perduto $zai quando ha ucciso il Drago Verde e ha avuto $rimbz gemme come rimborso");
            $session['user']['gems']+=$rimbz;
            //Per pubblicizzare la perdita dell'oggetto al NewDay
            $value = addslashes("`#".$session['user']['name']." `@ha perso `b`^$zai`b`@ quando ha ucciso il Drago Verde");
            $sql = "INSERT INTO commentary (section,author,comment,postdate)
            VALUES ('Perdita Oggetti',
            '".$rowz['id_oggetti']."',
            '$value',
            NOW())";
            db_query($sql) or die(db_error(LINK));
            //Fine pubblicità
            $oldloc = $rowz['dove_origine'];
            $oid = $rowz['id_oggetti'];
            $sql = "UPDATE oggetti SET dove=$oldloc WHERE id_oggetti=$oid";
            db_query($sql) or die(db_error(LINK));
            $session['user']['zaino'] = 0;
        }
    }
}else {
    //addnav("È un nuovo giorno","news.php");
    $session['user']['restorepage']="village.php";
    redirect("newday.php");
    }
$session['user']['restorepage']="village.php";
addnav("N?È un Nuovo Giorno","newday.php");
page_footer();
?>