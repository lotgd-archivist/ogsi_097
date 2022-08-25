<?php
require_once("common.php");
require_once("common2.php");
page_header("Azzeramento Tabella Torneo");
if ($_GET['op']==""){
    output("`\$`n`n`c<font size='+1'>ATTENZIONE !!! Stai per cancellare la Tabella del Torneo.`n",true);
    output("Sei sicuro al 100% di volerlo fare ?</font>`c",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("A?`\$Azzera la Tabella Torneo","torneoresetpremi.php?op=azzera");
    addnav("P?`^Consegna Premi","torneoresetpremi.php?op=premi");
    addnav("C?`(Concludi il Torneo","torneoresetpremi.php?op=termina");
}
if ($_GET['op']=="azzera"){
    output("`@<font size='+1'>`c`n`nQuesta è l'ultima possibilità che hai. `nOltre questo punto non potrai tornare indietro.`n",true);
    output("`\$Vuoi VERAMANTE azzerare la Tabella del Torneo ?`c</font>",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("A?`\$Azzera la Tabella Torneo","torneoresetpremi.php?op=sicuro");
}
if ($_GET['op']=="termina"){
    output("`c`\$Vuoi VERAMANTE terminare il Torneo adesso ?`c",true);
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("T?`\$Termina il Torneo","torneoresetpremi.php?op=terminasicuro");
}
if ($_GET['op']=="terminasicuro"){
    $sql = "SELECT * FROM torneopremi ORDER BY torneo DESC LIMIT 3";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        $sql = "INSERT INTO torneopremi (acctid,torneo,torneopoints) SELECT acctid,torneo,torneopoints FROM accounts WHERE torneo>0 AND superuser = 0";
        $result = db_query($sql) or die(db_error(LINK));
        $sql3 = "UPDATE accounts SET torneo='0', torneopoints=''";
        db_query($sql3);
        output("`n`@Il Torneo è stato chiuso! Si può procedere con la premiazione !!");
    } else {
        output("`n`\$L'ultimo torneo non è ancora stato premiato! Procedere prima con quella premiazione o con l'annullamento della gara!!");
    }
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
}
if ($_GET['op']=="sicuro"){
//    $sql="UPDATE accounts SET torneo='0',torneopoints=''";
    $sql = "DELETE FROM torneopremi WHERE 1";
    db_query($sql);
//    $session['user']['torneo']=0;
//    $session['user']['torneopoints']="";
    if (db_affected_rows()>0){
//        output("`^Righe modificate: ". db_affected_rows()." !");
        output("`^Righe Cancellate: ". db_affected_rows()." !");
    }else{
//        output("`#Tabella non aggiornata: $sql");
        output("`#Tabella non resettata: $sql3");
    }
    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    output("`n`@Azzeramento Tabella Torneo effettuato !!");
}
if ($_GET['op']=="premi"){
    $puntiprimo= -1;
    $puntisecondo= -1;
    $puntiterzo= -1;
    $firstgem=10;
    $firsthp=5;
    $secondgem=8;
    $secondhp=4;
    $thirdgem=5;
    $thirdhp=3;
    output("`c`b`&La Distribuzione dei Premi del Torneo di LoGD`b`c`n");
//    $sql = "SELECT acctid,name,sex,gems,maxhitpoints,torneo,fama_mod,fama3mesi FROM accounts WHERE torneo > 0  ORDER BY torneo DESC LIMIT 3";
    $sql = "SELECT b.acctid,a.name,a.sex,a.gems,a.maxhitpoints,b.torneo,a.fama_mod,a.fama3mesi FROM accounts a, torneopremi b WHERE b.acctid=a.acctid AND b.torneo > 0  ORDER BY torneo DESC, acctid ASC LIMIT 3";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("`c`&Nessun Giocatore si è iscritto al Torneo`0`c");
    }else {
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i = 0;$i < db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            output("`^".$row['name']."`3 ha `&".$row['gems']."`3 gemme, `@".$row['maxhitpoints']."`3 HP Permanenti e `\$".$row['fama3mesi']."`3 Punti Fama`n");
            $account=$row['acctid'];
            if ($i==0){
                $puntiprimo=$row['torneo'];
                //$hps=$row['maxhitpoints']+$firsthp;
                $hps=$firsthp;
                //$gemme=$row['gems']+$firstgem;
                $gemme=$firstgem;
                //Luke: modifica contatore fama
                $fama = 5000*$row['fama_mod'];
                $famanew = $fama + $row['fama3mesi'];
                $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (1° Classificato). Adesso ha $famanew punti fama";
                $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                db_query($sqlfama);
                //Luke: fine mod fama
                $mailmessage = "`@Hai vinto il Primo Premio del torneo di LoGD !!!`nTi sei aggiudicato $firstgem Gemme e $firsthp HP `b`iPermanenti`b`i !!!";
                $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^1° posto`# nel `@Torneo di LoGD`#.`n
                `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$firstgem gemme`# e `&$firsthp HP Permanenti !!");
            }else if ($i==1){
                $puntisecondo=$row['torneo'];
//controllo ex-equo col primo
                if ($puntiprimo==$puntisecondo) {
                    //$hps=$row['maxhitpoints']+$firsthp;
                    $hps=$firsthp;
                    //$gemme=$row['gems']+$firstgem;
                    $gemme=$firstgem;
                    //Luke: modifica contatore fama
                    $fama = 5000*$row['fama_mod'];
                    $famanew = $fama + $row['fama3mesi'];
                    $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (1° Classificato). Adesso ha $famanew punti fama";
                    $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                    db_query($sqlfama);
                    //Luke: fine mod fama
                    $mailmessage = "`@Hai vinto il Primo Premio del torneo di LoGD !!!`nTi sei aggiudicato $firstgem Gemme e $firsthp HP `b`iPermanenti`b`i !!!";
                    $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                    systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                    addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^1° posto`# nel `@Torneo di LoGD`#.`n
                    `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$firstgem gemme`# e `&$firsthp HP Permanenti !!");
                } else {
                    //$hps=$row['maxhitpoints']+$secondhp;
                    $hps=$secondhp;
                    //$gemme=$row['gems']+$secondgem;
                    $gemme=$secondgem;
                    //Luke: modifica contatore fama
                    $fama = 3000*$row['fama_mod'];
                    $famanew = $fama+$row['fama3mesi'];
                    $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (2° Classificato). Adesso ha $famanew punti fama";
                    $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                    db_query($sqlfama);
                    //Luke: fine mod fama
                    //output("`#{$row['name']} ha {$row['gems']} gemme e {$row['maxhitpoints']} HP Permanenti `n");
                    $mailmessage = "`@Hai vinto il Secondo Premio del torneo di LoGD !!!`nTi sei aggiudicato $secondgem Gemme e $secondhp HP `b`iPermanenti`b`i !!!";
                    $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                    systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                    addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^2° posto`# nel `@Torneo di LoGD`#.`n
                    `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$secondgem gemme`# e `&$secondhp HP Permanenti !!");
                }
            }else if ($i==2){
                $puntiterzo=$row['torneo'];
//controllo ex-equo col primo e col secondo
                if ($puntiprimo==$puntiterzo) {
                    //$hps=$row['maxhitpoints']+$firsthp;
                    $hps=$firsthp;
                    //$gemme=$row['gems']+$firstgem;
                    $gemme=$firstgem;
                    //Luke: modifica contatore fama
                    $fama = 5000*$row['fama_mod'];
                    $famanew = $fama + $row['fama3mesi'];
                    $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (1° Classificato). Adesso ha $famanew punti fama";
                    $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                    db_query($sqlfama);
                    //Luke: fine mod fama
                    $mailmessage = "`@Hai vinto il Primo Premio del torneo di LoGD !!!`nTi sei aggiudicato $firstgem Gemme e $firsthp HP `b`iPermanenti`b`i !!!";
                    $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                    systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                    addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^1° posto`# nel `@Torneo di LoGD`#.`n
                    `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$firstgem gemme`# e `&$firsthp HP Permanenti !!");
                } elseif ($puntisecondo==$puntiterzo) {
                    //$hps=$row['maxhitpoints']+$secondhp;
                    $hps=$secondhp;
                    //$gemme=$row['gems']+$secondgem;
                    $gemme=$secondgem;
                    //Luke: modifica contatore fama
                    $fama = 3000*$row['fama_mod'];
                    $famanew = $fama+$row['fama3mesi'];
                    $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (2° Classificato). Adesso ha $famanew punti fama";
                    $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                    db_query($sqlfama);
                    //Luke: fine mod fama
                    $mailmessage = "`@Hai vinto il Secondo Premio del torneo di LoGD !!!`nTi sei aggiudicato $secondgem Gemme e $secondhp HP `b`iPermanenti`b`i !!!";
                    $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                    systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                    addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^2° posto`# nel `@Torneo di LoGD`#.`n
                    `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$secondgem gemme`# e `&$secondhp HP Permanenti !!");
                } else {
                    //$hps=$row['maxhitpoints']+$thirdhp;
                    $hps=$thirdhp;
                    //$gemme=$row['gems']+$thirdgem;
                    $gemme=$thirdgem;
                    //Luke: modifica contatore fama
                    $fama = 2000*$row['fama_mod'];
                    $famanew = $fama+$row['fama3mesi'];
                    $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (3° Classificato). Adesso ha $famanew punti fama";
                    $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                    db_query($sqlfama);
                    //Luke: fine mod fama
                    //output("`@{$row['name']} ha {$row['gems']} gemme e {$row['maxhitpoints']} HP Permanenti `n");
                    $mailmessage = "`@Hai vinto il Terzo Premio del torneo di LoGD !!!`nTi sei aggiudicato $thirdgem Gemme e $thirdhp HP `b`iPermanenti`b`i !!!";
                    $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                    systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                    addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^3° posto`# nel `@Torneo di LoGD`#.`n
                    `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$thirdgem gemme`# e `&$thirdhp HP Permanenti !!");
                }
            }
            //Sook, vecchia premiazione
            /*
            $sql = "UPDATE accounts SET gems = '".$gemme."' WHERE acctid = '".$account."'";
            $result1=db_query($sql) or die(db_error(LINK));
            $sql = "UPDATE accounts SET maxhitpoints = '".$hps."' WHERE acctid = '".$account."'";
            $result2=db_query($sql) or die(db_error(LINK));
            $sql = "UPDATE accounts SET fama3mesi = '".$famanew."' WHERE acctid = '".$account."'";
            $result3=db_query($sql) or die(db_error(LINK));
            output("`^".$row['name']."`3 ha adesso `&$gemme`3 gemme, `@$hps`3 HP massimi e `\$$famanew`3 Punti Fama. `n`n");
            */
            //Sook, nuova premiazione
            assign_to_pg($row[acctid],'fama',$fama);
            assign_to_pg($row[acctid],'gems',$gemme);
            assign_to_pg($row[acctid],'hp',$hps);
        }
    }
    //controllo ex-equo terzo posto
    $idterzo=$row['acctid'];
    //$sql = "SELECT acctid,name,sex,gems,maxhitpoints,torneo,fama_mod,fama3mesi FROM accounts WHERE torneo = $puntiterzo AND acctid!='".$row['acctid']."'";
    $sql = "SELECT b.acctid,a.name,a.sex,a.gems,a.maxhitpoints,b.torneo,a.fama_mod,a.fama3mesi FROM accounts a, torneopremi b WHERE b.acctid=a.acctid AND b.torneo=$puntiterzo ORDER BY acctid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i = 0;$i < db_num_rows($result);$i++) {
        if ($row['acctid'] > $idterzo) {
            //controllo ex-equo col primo e col secondo
            if ($puntiprimo==$puntiterzo) {
                //$hps=$row['maxhitpoints']+$firsthp;
                $hps=$firsthp;
                //$gemme=$row['gems']+$firstgem;
                $gemme=$firstgem;
                //Luke: modifica contatore fama
                $fama = 5000*$row['fama_mod'];
                $famanew = $fama + $row['fama3mesi'];
                $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (1° Classificato). Adesso ha $famanew punti fama";
                $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                db_query($sqlfama);
                //Luke: fine mod fama
                $mailmessage = "`@Hai vinto il Primo Premio del torneo di LoGD !!!`nTi sei aggiudicato $firstgem Gemme e $firsthp HP `b`iPermanenti`b`i !!!";
                $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^1° posto`# nel `@Torneo di LoGD`#.`n
                `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$firstgem gemme`# e `&$firsthp HP Permanenti !!");
            } elseif ($puntisecondo==$puntiterzo) {
                //$hps=$row['maxhitpoints']+$secondhp;
                $hps=$secondhp;
                //$gemme=$row['gems']+$secondgem;
                $gemme=$secondgem;
                //Luke: modifica contatore fama
                $fama = 3000*$row['fama_mod'];
                $famanew = $fama+$row['fama3mesi'];
                $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (2° Classificato). Adesso ha $famanew punti fama";
                $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                db_query($sqlfama);
                //Luke: fine mod fama
                $mailmessage = "`@Hai vinto il Secondo Premio del torneo di LoGD !!!`nTi sei aggiudicato $secondgem Gemme e $secondhp HP `b`iPermanenti`b`i !!!";
                $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^2° posto`# nel `@Torneo di LoGD`#.`n
                `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$secondgem gemme`# e `&$secondhp HP Permanenti !!");
            } else {
                //$hps=$row['maxhitpoints']+$thirdhp;
                $hps=$thirdhp;
                //$gemme=$row['gems']+$thirdgem;
                $gemme=$thirdgem;
                //Luke: modifica contatore fama
                $fama = 2000*$row['fama_mod'];
                $famanew = $fama+$row['fama3mesi'];
                $message="guadagna $fama fama, $hps hp e $gemme gemme al torneo (3° Classificato). Adesso ha $famanew punti fama";
                $sqlfama = "INSERT INTO debuglog VALUES(0,now(),$account,0,'".addslashes($message)."')";
                db_query($sqlfama);
                //Luke: fine mod fama
                //output("`@{$row['name']} ha {$row['gems']} gemme e {$row['maxhitpoints']} HP Permanenti `n");
                $mailmessage = "`@Hai vinto il Terzo Premio del torneo di LoGD !!!`nTi sei aggiudicato $thirdgem Gemme e $thirdhp HP `b`iPermanenti`b`i !!!";
                $mailmessage .= "`nGuadagni anche $fama Punti Fama!!!";
                systemmail($row['acctid'],"`%Complimenti !!! Hai vinto al Torneo",$mailmessage);
                addnews("`#{$row['name']} `#si è classificat".($row['sex']?"a":"o")." al `^3° posto`# nel `@Torneo di LoGD`#.`n
                `#{$row['name']} `#si è aggiudicat".($row['sex']?"a":"o")." `^$thirdgem gemme`# e `&$thirdhp HP Permanenti !!");
            }
            //Sook, nuova premiazione
            assign_to_pg($row[acctid],'fama',$fama);
            assign_to_pg($row[acctid],'gems',$gemme);
            assign_to_pg($row[acctid],'hp',$hps);
        }
    }

    addnav("G?`#Torna alla Grotta","superuser.php");
    addnav("M?`@Torna alla Mondanità","village.php");
    addnav("A?`^Azzera Torneo","torneoresetpremi.php");
    output("`n`\$`bPremi distribuiti !!`b");
}
page_footer();
?>