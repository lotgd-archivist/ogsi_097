<?php
/********************
EVENTO SPECIALE
Incontri Casuali di Draghi Liberi
Written by Maximus for www.ogsi.it
*********************/

if (!isset($session)) exit();

switch($_GET[op]){
    case "":
         $session['user']['specialinc'] = "draghirandom.php";
         output("`@Stai passeggiando nella foresta per cercare qualche mostro da affrontare e ti imbatti in una piccola radura che costeggia una piccola collina.`n");
         output("`@Esplorando bene la zona scopri l'entrata di una grotta che a prima vista sembra molto profonda e pericolosa.`n");
         output("`@`nCosa decidi di fare?");
         addnav("`@Entra nella Grotta","forest.php?op=grotta");
         addnav("`\$Torna alla Foresta","forest.php?op=esci");
         break;
    case "esci":
         output("`@La curiosità non è il tuo forte, decidi di non perdere tempo ad esplorare grotte e torni nella foresta.");
         break;
    case "torna":
         output("`@Credi sia meglio lasciar stare dov'è il Drago e ritorni nella foresta.");
         break;
    case "scappa":
         output("`@Butti la torcia addosso al Drago per creare un diversivo e fortunatamente riesci a scappare dalle sue grinfie, per la paura che hai preso PERDI un punto fascino.");
         $session['user']['charm']--;
         if ($session['user']['charm'] <0) $session['user']['charm']=0;
         break;
    case "grotta":
         $session['user']['specialinc'] = "draghirandom.php";
         $sql = "SELECT * FROM draghi WHERE dove = 3 ORDER BY RAND()";
         $result = db_query($sql) or die(db_error(LINK));
         if (db_num_rows($result) == 0) {
            $tipo=e_rand(0,4);
            $tipo_drago=array(Rosso,Nero,Blu,Bianco,Zombie);
            $tipo_drago_db=$tipo_drago[$tipo];
            $eta=e_rand(0,4);
            $eta_drago=array(cucciolo,giovane,adulto,anziano,antico);
            $eta_drago_db=$eta_drago[$eta];
            $soffio=e_rand(0,4);
            $soffio_drago=array(fuoco,gelo,acido,morte,fulmine);
            $soffio_drago_db=$soffio_drago[$soffio];
            $danno_soffio=e_rand(40,120);
            $attacco=e_rand(20,60);
            $difesa=e_rand(40,80);
            $vita=e_rand(200,400);
            $carattere=e_rand(90,240);
            $bonus=e_rand(10,20);
            $valore=  ($attacco+$difesa+($vita/10)+($danno_soffio/2)+$bonus-$carattere)/2;
            $sql = "INSERT INTO draghi (tipo_drago,eta_drago,tipo_soffio,danno_soffio,attacco,difesa,vita,vita_restante,carattere,dove,combatte,bonus,valore,aspetto,crescita)
            VALUES ('$tipo_drago_db','$eta_drago_db','$soffio_drago_db','$danno_soffio','$attacco','$difesa','$vita','$vita','$carattere','3','volo','$bonus','$valore','normale','20')";
            db_query($sql) or die(db_error($link));
            $sql = "SELECT * FROM draghi WHERE dove = 3 ORDER BY RAND()";
            $result = db_query($sql) or die(db_error(LINK));
         }
         $row = db_fetch_assoc($result);
         $tipodrago = $row[tipo_drago];
/*         if ($row[tipo_drago] == 'Rosso') $tipodrago = "`\$".$tipodrago;
         if ($row[tipo_drago] == 'Nero') $tipodrago = "`9".$tipodrago;
         if ($row[tipo_drago] == 'Blu') $tipodrago = "`!".$tipodrago;
         if ($row[tipo_drago] == 'Bianco') $tipodrago = "`&".$tipodrago;
         if ($row[tipo_drago] == 'Zombie') $tipodrago = "`0".$tipodrago;
         if ($row[tipo_drago] == 'Verde') $tipodrago = "`@".$tipodrago;
         if ($row[tipo_drago] == 'Scheletro') $tipodrago = "`8".$tipodrago;
         if ($row[tipo_drago] == 'Bronzo') $tipodrago = "`(".$tipodrago;
         if ($row[tipo_drago] == 'Argento') $tipodrago = "`7".$tipodrago;
         if ($row[tipo_drago] == 'Oro') $tipodrago = "`6".$tipodrago;
*/         $tipodrago = coloradrago($row[tipo_drago]).$tipodrago;
         if ($session['user']['superuser'] > 0) {
            output("`&DEBUG ROWID ---> ".$row[id].".`n`n");
            output("`&DEBUG TIPODRAGO ---> ".$row[id].".`n`n");
         }
         output("`@Accendi una torcia e avanzi coraggiosamente nella grotta buia, la pendenza ti porta verso il basso.`n");
         output("`@Cammini per alcuni minuti e l'aria comincia a diventare quasi irrespirabile ma la tua risolutezza ti porta ad andare avanti.");
         output("`@Svoltato l'angolo pensi che forse era meglio se oggi restavi al villaggio, davanti a te c'è un Drago ".$tipodrago."`@ e sembra proprio che ti stava aspettando...");
         if ($session['user']['id_drago'] != 0) {
            output("`@`nFortunatamente il TUO Drago è con te ed è pronto a difenderti.");
            addnav("`&Attacca il Drago","forest.php?op=attacca&id_drago=$row[id]");
         } else {
            if ($session['user']['reincarna'] != 0) {
               output("`@`nForse è la giornata buona per provare ad addomesticare un Drago...");
               addnav("`@Addomestica il Drago","forest.php?op=addomestica&id_drago=$row[id]");
            }
         }
         addnav("`^Parla al Drago","forest.php?op=parla&id_drago=$row[id]");
         addnav("`\$Scappa!","forest.php?op=scappa");
         break;
   case "parla":
        $sql = "SELECT * FROM draghi WHERE id = '".$_GET[id_drago]."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
/*        if ($row[tipo_drago] == 'Rosso') $colore = "`\$";
        if ($row[tipo_drago] == 'Nero') $colore = "`9";
        if ($row[tipo_drago] == 'Blu') $colore = "`!";
        if ($row[tipo_drago] == 'Bianco') $colore = "`&";
        if ($row[tipo_drago] == 'Zombie') $colore = "`0";
        if ($row[tipo_drago] == 'Verde') $colore = "`@";
        if ($row[tipo_drago] == 'Scheletro') $colore = "`8";
        if ($row[tipo_drago] == 'Bronzo') $colore = "`(";
        if ($row[tipo_drago] == 'Argento') $colore = "`7";
        if ($row[tipo_drago] == 'Oro') $colore = "`6";
*/        $colore = coloradrago($row[tipo_drago]);
        output("`@Ti avvicini cauto facendo attenzione a non fare movimenti bruschi e cominci a parlare cercando di essere MOLTO gentile:");
        output("`n`n`^\"Salve, o Possente...io sono {$session['user']['name']} `^e chiedo scusa per averti disturbato, posso rimediare in qualche modo?\"");
        output("`@`n`nIl Drago ti squadra per bene poi sbuffa soffi di ".$row['tipo_soffio']." per fortuna non verso di te...");
        output("`n`n".$colore."\"Bene, mortale...Si, c'è una cosa che puoi fare per me...\"`n");
        $caso = e_rand(0,3);
        switch ($caso) {
        case "0";
             output("`@`nPrende un pò delle sue gemme e te le porge");
             output("`n`n".$colore."\"Prendi queste e non disturbarmi mai più, altrimenti potrei non essere così generoso la prossima volta...\"");
             output("`@`n`nAfferri le gemme, ringrazi il Drago ed esci il più velocemente possibile della grotta.");
             $gain = e_rand (1,4);
             $session[user][gems]+=$gain;
             output("`@`n`nHai guadagnato ben `b".$gain."`b gemma/e!!");
             debuglog("guadagna $gain gemma/e incontrando un drago nella foresta");
             break;
        case "1";
             output("`@`nPrende un pò del suo oro e te le porge");
             output("`n`n".$colore."\"Prendi questo e non disturbarmi mai più, altrimenti potrei non essere così generoso la prossima volta...\"");
             output("`@`n`nAfferri l'oro, ringrazi il Drago ed esci il più velocemente possibile della grotta.");
             $gain = e_rand ($session[user][level]*10,$session[user][level]*100);
             $session[user][gold]+=$gain;
             output("`@`n`nHai guadagnato ben `b".$gain."`b pezzi d'oro!!");
             debuglog("guadagna $gain oro incontrando un drago nella foresta");
             break;
        case "2";
             output("`@`nSquadra il borsellino delle tue gemme");
             output("`n`n".$colore."\"Potresti darmi un pò delle tue preziose gemme per poi non disturbarmi mai più, altrimenti potrei non essere così generoso la prossima volta...\"");
             if ($session[user][gems]==0) {
                 output("`@`n`nMostri con un pò di imbarazzo il borsellino vuoto al Drago che impietosito ti regala una gemma...");
                 output("`@`n`nTuttavia non è stata una giornata sfortunata.");
                 $session[user][gems]++;
                 debuglog("guadagna una gemma incontrando un drago nella foresta");
             } else {
                 $loss = e_rand (1,4);
                 if ($session[user][gems]<=$loss) {
                    output("`@`n`nAfferri il borsellino e le porgi al Drago, lo ringrazi ed esci il più velocemente possibile della grotta.");
                    $loss=$session[user][gems];
                 } else {
                    output("`@`n`nAfferri alcune gemme e le porgi al Drago, lo ringrazi ed esci il più velocemente possibile della grotta.");
                 }
                 $session[user][gems]-=$loss;
                 output("`@`n`nHai perso `b".$loss."`b gemma/e!!");
                 debuglog("perde $loss gemme incontrando un drago nella foresta");
             }
             break;
        case "3";
             output("`@`nSquadra il borsellino del tuo oro");
             output("`n`n".$colore."\"Potresti darmi un pò del tuo prezioso oro per poi non disturbarmi mai più, altrimenti potrei non essere così generoso la prossima volta...\"");
             if ($session[user][gold]==0) {
                 output("`@`n`nMostri con un pò di imbarazzo il borsellino vuoto al Drago che impietosito ti regala dell'oro...");
                 $gain = e_rand($session[user][level]*10,$session[user][level]*100);
                 output("`@`n`nTuttavia non è stata una giornata sfortunata, hai guadagnato {$gain} oro.");
                 $session[user][gold]+=$gain;
                 debuglog("guadagna $gain oro incontrando un drago nella foresta");
             } else {
                 $loss = e_rand($session[user][level]*10,$session[user][level]*100);
                 if ($session[user][gold]<=$loss) {
                    output("`@`n`nAfferri il borsellino e le porgi al Drago, lo ringrazi ed esci il più velocemente possibile della grotta.");
                    $loss=$session[user][gold];
                 } else {
                    output("`@`n`nAfferri alcune monete e le porgi al Drago, lo ringrazi ed esci il più velocemente possibile della grotta.");
                 }
                 $session[user][gold]-=$loss;
                 output("`@`n`nHai perso `b".$loss."`b oro!!");
                 debuglog("perde $loss oro incontrando un drago nella foresta");
             }
             break;
        }
        break;
   case "addomestica":
        $sql = "SELECT * FROM draghi WHERE id = '".$_GET[id_drago]."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($session['user']['superuser'] > 0) output("`&DEBUG --> PLAYER {$session['user']['cavalcare_drago']} DRAGO {$row[carattere]}`n`n");
        $colore = coloradrago($row[tipo_drago]);
        output("`@Guardi meglio da lontano il Drago cercando di capire se sei alla sua altezza...");
        if ($session['user']['cavalcare_drago']>=$row['carattere']) {
           output("`@`nDopo alcuni sguardi ti senti sicuro, ti avvicini a lui minaccioso e provi ad addomesticarlo.");
           $caso=e_rand(1,10);
           if ($caso<=3) {
              output("`@`nBravo, ci sei riuscito! Ora possiedi un VERO Drago!!");
              $session['user']['id_drago'] = $row[id];
              $user_id=$session['user']['acctid'];
              $sql = "UPDATE draghi SET user_id='$user_id',dove=0 WHERE id=$row[id]";
              db_query($sql) or die(db_error(LINK));
              debuglog("ha addomesticato il drago {$row['id']} nella foresta");
           } else {
              output("`@`nDopo svariati tentativi non riesci domarlo, prima che si arrabbi veramente ti dai alla fuga...");
           }
        } else {
           $session['user']['specialinc'] = "draghirandom.php";
           output("`@`nDopo alcuni sguardi non ti senti molto sicuro, forse è meglio pensare ad altre opzioni...");
           addnav("`@Parla al Drago","forest.php?op=parla&id_drago=$row[id]");
           addnav("`\$Scappa!","forest.php?op=scappa");
        }
        break;
   case "addomestica2":
        $sql = "SELECT * FROM draghi WHERE id = '".$_GET[id_drago]."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($session['user']['superuser'] > 0) output("`&DEBUG --> PLAYER {$session['user']['cavalcare_drago']} DRAGO {$row[carattere]}`n`n");
        $colore = coloradrago($row[tipo_drago]);
        output("`@Ti avvicini al Drago battutto e provi ad addomesticarlo...");
        if ($session['user']['cavalcare_drago']>=$row['carattere']) {
           $caso=e_rand(1,10);
           if ($caso<=3) {
              output("`@`nBravo, ci sei riuscito! Fai una carezza al tuo vecchio Drago e lo lasci in libertà. Ora possiedi un NUOVO Drago!!");
              $olddrago = $session['user']['id_drago'];
              $session['user']['id_drago'] = $row[id];
              $user_id=$session['user']['acctid'];
              $sql = "UPDATE draghi SET user_id='$user_id',dove=0 WHERE id=$row[id]";
              db_query($sql) or die(db_error(LINK));
              debuglog("ha addomesticato il drago {$row['id']} nella foresta dopo averlo battuto");

              $sql = "UPDATE draghi SET user_id='0',dove=3 WHERE id=$olddrago";
              db_query($sql) or die(db_error(LINK));
           } else {
              output("`@`nDopo svariati tentativi non riesci domarlo, prima che si arrabbi veramente ti allontani dalla grotta...");
           }
        } else {
           $session['user']['specialinc'] = "draghirandom.php";
           output("`@`nDopo alcuni sguardi non ti senti alla sua altezza, meglio rinunciare...");
           output("`@`nNon avento più nulla da fare in questa grotta, torni ai tuoi impegni nella foresta.");
        }
        break;
   case "attacca":
        $sql = "SELECT * FROM draghi WHERE id = '".$_GET[id_drago]."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $sql = "SELECT * FROM draghi WHERE user_id = '".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        $rowd = db_fetch_assoc($result);
        $caso_player_att = e_rand(1, 20);
        $caso_player_dif = e_rand(1, 20);
        $attacco_drago_player= $session['user']['cavalcare_drago']+$rowd[attacco]+$caso_player_att;
        $difesa_drago_player=$session['user']['cavalcare_drago']+$rowd[difesa]+$caso_player_dif;
        //nella grotta non possono volare
        /*
        if($rowd[combatte]==volo){
            $attacco_drago_player=$attacco_drago_player+$rowd[bonus];
            $difesa_drago_player=$difesa_drago_player+$rowd[bonus];
        }
        */
        $caso_cpu_att = e_rand(1, 20);
        $caso_cpu_dif = e_rand(1, 20);
        $attacco_drago_cpu=intval($row[carattere]/2)+$row[attacco]+$caso_cpu_att;
        $difesa_drago_cpu=intval($row[carattere]/2)+$row[difesa]+$caso_cpu_dif;
        //nella grotta non possono volare
        /*
        if($row[combatte]==volo){
            $attacco_drago_cpu=$attacco_drago_cpu+$row[bonus];
            $difesa_drago_cpu=$difesa_drago_cpu+$row[bonus];
        }
        */
        $danni_player=$attacco_drago_player-$difesa_drago_cpu;
        $danni_cpu=$attacco_drago_cpu-$difesa_drago_player;
        if ($danni_player<0){
            $danni_player=0;
        }
        if ($danni_cpu<0){
            $danni_cpu=0;
        }
        output("`@Mandi il tuo Drago all'attacco, dopo una furiosa lotta...`n");
        if($danni_player>$danni_cpu){
            $exp_drago=e_rand(1, 2);
            output("`6hai VINTO lo scontro causando $danni_player danni e subendo $danni_cpu danni e hai guadagnato $exp_drago punti abilità cavalcare draghi!`n");
            if ($session['user']['superuser'] > 0) output("`&DEBUG --> Player:$attacco_drago_player/$difesa_drago_player cpu:$attacco_drago_cpu/$difesa_drago_cpu`n");
            $session['user']['cavalcare_drago'] += $exp_drago;
            $vita_restante=$rowd[vita_restante]-$danni_cpu;
            $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
            $carattere=$row[carattere]+$exp_drago;
            $sql = "UPDATE draghi SET carattere='$carattere' WHERE id='".$_GET[id_drago]."'";
            $row['carattere']=$carattere;
            db_query($sql) or die(db_error(LINK));
            output("`@`n`nOsservi il Drago battuto, da come ha combattuto riesci a confrontare alcune caratteristiche con quello che possiedi...`n");
            output ("`n`n", true);
            output ("<table>", true);
            output ("<tr><td>`2Aspetto:</td><td>`@".$row['aspetto']."</td></tr>", true);
            if ($row['attacco'] > $rowd['attacco']) {
               output ("<tr><td>`4Attacco: </td><td>`\$ MIGLIORE </td></tr>", true);
            } else {
                if ($row['attacco'] < $rowd['attacco']) {
                   output ("<tr><td>`4Attacco: </td><td>`\$ PEGGIORE </td></tr>", true);
                } else {
                   output ("<tr><td>`4Attacco: </td><td>`\$ SIMILE </td></tr>", true);
                }
            }
            if ($row['difesa'] > $rowd['difesa']) {
               output ("<tr><td>`6Difesa: </td><td>`^ MIGLIORE </td></tr>", true);
            } else {
                if ($row['difesa'] < $rowd['difesa']) {
                   output ("<tr><td>`6Difesa: </td><td>`^ PEGGIORE </td></tr>", true);
                } else {
                   output ("<tr><td>`6Difesa: </td><td>`^ SIMILE </td></tr>", true);
                }
            }
            if ($row['vita'] > $rowd['vita']) {
               output ("<tr><td>`7Vita: </td><td>`& MIGLIORE </td></tr>", true);
            } else {
                if ($row['vita'] < $rowd['vita']) {
                   output ("<tr><td>`7Vita: </td><td>`& PEGGIORE </td></tr>", true);
                } else {
                   output ("<tr><td>`7Vita: </td><td>`& SIMILE </td></tr>", true);
                }
            }
            output ("<tr><td>`2Tipo soffio: </td><td>`@".$row['tipo_soffio']."</td></tr>", true);
            if ($row['danno_soffio'] > $rowd['danno_soffio']) {
               output ("<tr><td>`2Danno_soffio: </td><td>`@ MIGLIORE </td></tr>", true);
            } else {
                if ($row['danno_soffio'] < $rowd['danno_soffio']) {
                   output ("<tr><td>`2Danno_soffio: </td><td>`@ PEGGIORE </td></tr>", true);
                } else {
                   output ("<tr><td>`2Danno_soffio: </td><td>`@ SIMILE </td></tr>", true);
                }
            }
            output ("</table>", true);
            output ("`n`n", true);
            output("`@`n`nSe vuoi puoi provare ad addomesticarlo, ma attenzione, nel caso tu ci riesca, il Drago che possiedi tornerà in libertà`n");
            addnav("`@Addomestica il Drago","forest.php?op=addomestica2&id_drago=$row[id]");
        }else {
            if ($danni_cpu == 0) $danni_cpu = 1;
	        output("`6hai PERSO lo scontro causando $danni_player danni e subendo $danni_cpu danni!`n");
            if ($session['user']['superuser'] > 0) output("`&DEBUG --> Player:$attacco_drago_player/$difesa_drago_player cpu:$attacco_drago_cpu/$difesa_drago_cpu`n");
            $vita_restante=$rowd[vita_restante]-$danni_cpu;
            $sql = "UPDATE draghi SET vita_restante='$vita_restante' WHERE user_id='".$session['user']['acctid']."'";
            db_query($sql) or die(db_error(LINK));
        }
        if ($session['user']['superuser'] > 0) {
           output ("`n`nDEBUG`n`n", true);
           output ("<table>", true);
           output ("<tr><td>`7Caratteristiche: </td><td>Incontrato</td><td>Posseduto</td></tr>", true);
           output ("<tr><td>`7Nome: </td><td>`&".$row['nome']."</td><td>`&".$rowd['nome']."</td></tr>", true);
           output ("<tr><td>`7Carattere: </td><td>`&".$row['carattere']."</td><td>`&".$rowd['carattere']."</td></tr>", true);
           output ("<tr><td>`4Attacco: </td><td>`\$".$row['attacco'] . "</td><td>`\$".$rowd['attacco'] . "</td></tr>", true);
           output ("<tr><td>`6Difesa: </td><td>`^".$row['difesa'] . "</td><td>`^".$rowd['difesa'] . "</td></tr>", true);
           output ("<tr><td>`7Vita: </td><td>`&".$row['vita']."</td><td>`&".$rowd['vita']."</td></tr>", true);
           output ("<tr><td>`2Tipo soffio: </td><td>`@".$row['tipo_soffio']."</td><td>`@".$rowd['tipo_soffio']."</td></tr>", true);
           output ("<tr><td>`2Danno_soffio:</td><td>`@".$row['danno_soffio']."</td><td>`@".$rowd['danno_soffio']."</td></tr>", true);
           output ("<tr><td>`2Territorio preferito:</td><td>`@".$row['combatte']."</td><td>`@".$rowd['combatte']."</td></tr>", true);
           output ("<tr><td>`2Bonus territorio:</td><td>`@".$row['bonus']."</td><td>`@".$rowd['bonus']."</td></tr>", true);
           output ("<tr><td>`2Aspetto:</td><td>`@".$row['aspetto']."</td><td>`@".$rowd['aspetto']."</td></tr>", true);
           output ("<tr><td>  </td><td>  </td><td>  </td></tr>", true);
           output ("</table>", true);
        }
        $session['user']['specialinc'] = "draghirandom.php";
        addnav("`\$Torna alla Foresta","forest.php?op=torna");
        break;
}

?>