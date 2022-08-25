<?php
/********************
Gold-mine
Written by Ville Valtokari
********************/
require_once("common.php");
require_once("common2.php");
if ($session['user']['win1'] != 9) {
   $session['hashorse'] = $session['user']['hashorse'];
   $session['enter'] = 0;
   $session['die'] = 0;
   $session['save'] = 0;
   if ($session['hashorse']) {
      // Add a 10% chance that they tether their horse anyway.
      $session['enter'] = $playermount['mine_canenter'];
      if (e_rand(1,10) == 1) $session['enter']=0;
      if ($session['enter']) {
        // The horse cannot die or save you if it cannot enter.
        $session['die'] = $playermount['mine_candie'];
        $session['save'] = $playermount['mine_cansave'];
     }
   }
//output("Horse can enter: {$session['enter']}`n");
//output("Horse can die  : {$session['die']}`n");
//output("Horse can save : {$session['save']}`n");
}
//echo("WIN1 =  {$session['user']['win1']}<br>");
if ($_GET['op']==""){
    $session['user']['win1']=9;
    $session['user']['specialinc']="goldmine.php";
    output("`2Trovi una vecchia miniera abbandonata nella foresta. Ci sono dei vecchi strumenti per scavare `n
    \"`5Chissà se funzionano ancora`2\" ti domandi.`n`n");
    output("`^Mentre ti guardi intorno capisci che sarà un sacco di lavoro. In verità userai un turno se provi a
    scavare.`n`n");
    output("`^Guardando più attentamente noti che ci sono degli scavi occasionali nella miniera.`n`n");
    addnav("Scava per oro e gemme","forest.php?op=mine");
    addnav("","forest.php?op=mine");
    addnav("Ritorna alla foresta","forest.php?op=no");
    addnav("","forest.php?op=no");

}else if ($_GET['op']=="no"){
    output("`2No, non hai tempo da perdere con un modo così lento di fare soldi e gemme, e così lasci la miniera ...`n");
    $session['user']['specialinc']="";

} elseif ($_GET['op']=="mine") {
    $session['user']['specialinc']="goldmine.php";
    if ($session['user']['turns']<=0) {
        output("`2Sei troppo stanco per scavare ...`n");
        $session['user']['specialinc']="";
    } else {
        // Horsecanenter is a percent, so, if rand(1-100) > enterpercent,
        // tether it.  Set enter percent to 0 (the default), to always tether.
        if (e_rand(1, 100) > $session['enter']) {
            $anifuori = 1;
            if ($playermount['mine_tethermsg']) {
                output($playermount['mine_tethermsg']."`n");
            } else {
                output("`&Vedendo che l'ingresso della miniera è troppo stretto per il tuo {$playermount['mountname']}`&, lo leghi a fianco dell'entrata.`n");
            }
        } else $anifuori = 0;
        $rand = e_rand(1,25);
        output("`2Prendi gli attrezzi per scavare e incominci...`n`n");
        $session['user']['clean'] += 2;
        switch ($rand){

          case 1:case 2:case 3:case 4: case 5:
            output("`2Dopo alcune ore di duro lavoro trovi solo pietre senza valore ed un teschio ...`n`n");
            output("`^Perdi un turno mentre scavavi.`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            break;
          case 6: case 7: case 8:case 9: case 10:
            $gold = e_rand($session['user']['level']*100, $session['user']['level']*200);
            output("`^Dopo alcune ore di duro lavoro trovi $gold pezzi d'oro!`n`n");
            $session['user']['gold'] += $gold;
            output("`^Perdi un turno mentre scavavi.`n`n");
            debuglog("trova $gold pezzi d'oro nella miniera");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            break;
          case 11: case 12: case 13: case 14: case 15:
            $gems = intval(e_rand(2, $session['user']['level']/5+2));
            output("`^Dopo alcune ore di duro lavoro trovi $gems gemme!`n`n");
            debuglog("trova $gems gemme nella miniera");
            $session['user']['gems'] += $gems;
            output("`^Perdi un turno mentre scavavi.`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            break;
          case 16: case 17: case 18:
            $gold = e_rand($session['user']['level']*150, $session['user']['level']*300);
            $gems = intval(e_rand(2, $session['user']['level']/3+2));
            output("`^Hai trovato una ricca vena!`n`n");
            output("`^Dopo alcune ore di duro lavoro, trovi $gems gemme e $gold pezzi d'oro!`n`n");
            $session['user']['gems'] += $gems;
            $session['user']['gold'] += $gold;
            debuglog("trova $gold pezzi d'oro e $gems gemme nella miniera");
            output("`^Perdi un turno mentre scavavi.`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            break;
          case 19: case 20:
//output("Horse can enter: {$session['enter']}`n");
//output("Horse can die  : {$session['die']}`n");
//output("Horse can save : {$session['save']}`n");
//output("Animale Fuori  : $anifuori`n");
            $session['user']['win1']=0;
            output("`2Dopo molto duro lavoro credi di aver trovato una `&grandissima`2 vena di gemme e oro.`n");
            output("`2Ansioso di diventare ricco, raggiungi la vena e colpisci più forte che puoi.`n`n");
            output("`7Sfortunatamente la tua esuberanza causa il crollo di una parte della miniera.`n");
            // Dwarves are very wiley so will only ever die in the mines
            // infrequently.
            if ($session['user']['race'] != 4) {
                $dead = 1;
                // Non dwarves will survive on luck 10% of the time.
                if (e_rand(1,10) == 1) $dead = 0;
            } else {
                // Dwarves can only die 5% of the time.
                if (e_rand(1,20) == 1) $dead = 1;
            }
            // Now, if the player died, see if their horse save them
            if ($dead && !$anifuori) {
                if (e_rand(1,100) < $session['save']) {
                    $dead = 0;
                    $horsesave = 1;
                }
            }
            // If we are still dead, see if the horse dies too.
            if ($dead && !$anifuori) {
                if (e_rand(1,100) < $session['die']) $horsedead = 1;
           }

            $session['user']['specialinc']="";
            if ($dead) {
                output("<big><big>`&Sei stato seppellito da tonnellate di rocce!`n`nForse il prossimo avventuriero troverà il
                tuo corpo e gli darà degna sepoltura.`n`n</big></big>",true);
                if ($session['hashorse']){
                    if ($horsedead) {
                        if ($playermount['mine_deathmessage']){
                            output($playermount['mine_deathmessage']);
                        }else{
                            debuglog("perde {$playermount['mountname']} nel crollo della miniera");
                            output("Le ossa del tuo {$playermount['mountname']} sono state seppellite accanto alle tue.`n");
                            $session['user']['hashorse'] = 0;
                            if(isset($session['bufflist']['mount'])) unset($session['bufflist']['mount']);
                        }
                    } else if ($anifuori) {
                        //if (!$session['enter']) {
                            output("`&Fortunatamente hai lasciato il tuo {$playermount['mountname']} legato fuori dalla miniera.
                            Sai che è addestrato a tornare al villaggio.`n");
                        } else {
                            output("`&Il tuo {$playermount['mountname']} riesce a scappare prima di essere travolto dal crollo.
                            Sai che è addestrato a tornare al villaggio.`n");
                        }
                    //}
                }
                $exp=$session['user']['experience']*0.3;
                output("`n`@Almeno hai imparato qualche cosa riguardo alle miniere e da questa esperienza hai guadagnato $exp punti esperienza.`n`n");
                output("`4Potrai continuare a giocare domani.`n");
                $session['user']['experience']+=$exp;
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                debuglog("muore nel crollo della miniera, perde {$session['user']['gold']} oro e {$session['user']['gems']} gemme");
                output("`n<big><big><big>`\$Perdi TUTTO l'`^ORO`\$ che avevi con te e anche TUTTE le `&GEMME`\$!!!</big></big></big>`n",true);
                $session['user']['gold']=0;
                $session['user']['gems']=0;
                addnav("Notizie Giornaliere","news.php");
                addnews($session['user']['name']." è stat".($session['user']['sex']?"a":"o")." seppellit".($session['user']['sex']?"a":"o")." quando è crollata la miniera.");
            } else {
                if ($session['user']['race'] == 4) {
                    output("Ma altrettanto fortunatamente le tue abilità di nano ti hanno permesso di salvarti!`n");
                } elseif ($horsesave) {
                    if ($playermount['mine_savemsg']) {
                        output($playermount['mine_savemsg']."`n");
                    }else {
                        output("Il tuo {$playermount['mountname']} è riuscito a portarti fuori prima del crollo!!`n");
                    }
                } else {
                    output("Per pura fortuna sei riuscito a fuoriuscire dalla frana intatto!`n");
                }
                output("`n`5La morte scampata per miracolo ti ha talmente impaurito che non te la senti di affrontare
                `nnessun'altra creatura per oggi.`n");
                debuglog("scampa al crollo della miniera e perde tutti i turni");
                $session['user']['turns']=0;
            }
            break;
        case 21: case 22:
            output("`^Hai trovato una vena di ferro!`n`n");
            output("`^Dopo alcune ore di duro lavoro trovi 1 scaglia di ferro!`n`n");
            debuglog("trova 1 scaglia di ferro nella miniera");
            output("`^Perdi un turno mentre scavavi.`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            if (!zainoPieno($session['user']['acctid'])){
               output("`#È stata proprio una giornata fortunata!`n`n");
               $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('1','{$session['user']['acctid']}')";
               db_query($sqldr);
            } else {
               output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
               output("%Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
               output("lo zaino e far posto ad eventuali materiali che potresti trovare nella foresta.`n");
            }
            break;
        case 23: case 24:
            output("`^Hai trovato una vena di rame!`n`n");
            output("`^Dopo alcune ore di duro lavoro trovi 1 scaglia di rame!`n`n");
            debuglog("trova 1 scaglia di rame nella miniera");
            output("`^Perdi un turno mentre scavavi.`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            if (!zainoPieno($session['user']['acctid'])){
               $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('2','{$session['user']['acctid']}')";
               db_query($sqldr);
            } else {
               output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
               output("%Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
               output("lo zaino e far posto ad eventuali materiali che potresti trovare nella foresta.`n");
            }
            break;
        case 25:
            output("`^Hai trovato una ricetta mmmm, sembra molto interessante!`n`n");
            output("`^Dopo alcune ore di duro lavoro trovi 1 ricetta!`n`n");
            debuglog("trova 1 ricetta id 6 ");
            output("`^Perdi un turno mentre scavavi.`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['specialinc']="";
            if (!zainoPieno($session['user']['acctid'])){
               $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('6','{$session['user']['acctid']}')";
               db_query($sqldr);
            } else {
               output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
               output("%Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
               output("lo zaino e far posto ad eventuali ricette che potresti trovare nella foresta.`n");
            }
            break;

        }
    }
}
?>