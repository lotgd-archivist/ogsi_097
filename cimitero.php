<?php
/* *******************
Graveyard
Written by Windminstrel
******************* */
require_once "common.php";
$pvpopen = $session['user']['playerfights'];
if ($pvpopen == 0) {
        page_header("Non puoi oggi");
        addnav("Torna al Villaggio","village.php");
        output("`5`nNon hai abbastanza tempo oggi per cercare avventure così impegnativi.");
}else{
    if ($_GET['op']==""){
       page_header("Il Cimitero");
       output("`2Nel cuore della foresta finisci un un piccolo cimitero. Il cielo sembra scurirsi. Pipistrelli volano vicino al mausoleo.`n");
       output("`^Mentre ti guardi intorno realizzi che potrebbero esserci dei tesori in queste tombe dimenticate da tutti.`n`n");
       output("`^Cosa vuoi fare?`n`n");
       addnav("Lascia dei fiori su una tomba","cimitero.php?op=flowers");
       addnav("Scava per trovare dei tesori nelle tombe","cimitero.php?op=dig");
       addnav("Dissacra il mausoleo","cimitero.php?op=desecrate");
       addnav("Corri via come una piccola bambina","cimitero.php?op=run");
    }elseif ($_GET['op']=="run"){
       $session['user']['playerfights']--;
       page_header("Scappa");
       output("`2Decide che la discrezione è meglio del valore e scappi via`n");
       addnav("Torna alla Foresta","forest.php");
       $session['user']['specialinc']="";
    }elseif ($_GET['op']=="flowers"){
       $session['user']['quest'] += 1;
       $session['user']['playerfights']--;
       page_header("I Fiori");
       $rand = e_rand(0,10);
       output("`2Vai nella foresta e cerchi per alcuni fiori. Dopo una breve ricerca prendi un mazzo di fiori e li metti su una tomba. Chini il capo e dici una breve preghiera per il riposo del corpo nella tomba.`n`n");
       switch ($rand){
           case 0: case 1: case 2: case 3: case 4: case 5:
             output("`2Dopo la preghiera, ti alzi felice di aver fatto qualcosa di buono.`n`n");
             addnav("Torna alla foresta","forest.php");
             $session['user']['turns']++;
           break;
           case 6:case 7:case 8:case 9:case 10:
             output("`^Dopo la preghiera sei sorpreso dal notare uno spirito davanti a te.`n`n");
             output("`2Grazie per la tua gentilezza. Sei veramente uno spirito gentile, e la tua bellezza interna splende come il sole a mezzogiorno.`n`n");
             output("`2Che tu possa essere bello esternamente come lo sei internamente.`n`n");
             output("`^Hai guadagnato 5 punti di fascino!.`n`n");
             $session['user']['turns']++;
             $session['user']['charm']+=5;
             addnav("Torna alla Foresta","forest.php");
           break;
       }
    }elseif ($_GET['op']=="dig"){
       $session['user']['quest'] += 1;
       $session['user']['playerfights']--;
       page_header("Scava");
       $rand = rand(0,15);
       output("`2Trovi una tomba che fa al caso tuo. Dopo alcune ore di lavoro trovi un piccolo sacchetto metallico`n`n");
       switch ($rand){
          case 0:case 1:case 2:case 3:case 4:case 5:
            output("`2Lo rompi con troppa forza e ti ritrovi coperto di chissa cosa conteneva`n`n");
            output("`2Disgustato ritorni alla foresta avendo perso un turno.`n`n");
            if ($session['user']['charm']>0){
               output("`^Sei coperto di un liquido strano perdi un punto di fascino.`n`n");
               $session['user']['charm']--;
            }
            if ($session['user']['turns']>0) $session['user']['turns']--;
            addnav("Torna alla Foresta","forest.php");
          break;
          case 6:case 7:
            $gold = rand($session['user']['level']*10,$session['user']['level']*25);
            output("`^Dopo ore di lavoro trovi $gold monete d'oro.`n`n");
            $session['user']['gold'] += $gold;
            if ($session['user']['turns']>0) $session['user']['turns']--;
            addnav("Torna alla Foresta","forest.php");
          break;
          case 8:case 9:case 10:
            output("`^Dopo ore di lavoro trovi una gemma!`n`n");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            $session['user']['gems']++;
            addnav("Torna alla Foresta","forest.php");
          break;
          case 11:case 12:case 13:case 14: case 15:
            output("`^Hai svegliato un antico guardiano!.`n`n");
            output("<a href=guardian.php>Non ti rimane che combattere</a>`n", true);
            addnav("","guardian.php");
            addnav("Attacca il Guardiano","guardian.php");
          break;
         }
    }elseif ($_GET['op']=="desecrate"){
       $session['user']['quest'] += 1;
       $session['user']['playerfights']--;
       page_header("Il Cimitero");
       output ("La fortuna aiuta gli audaci, e tu sei sicuramente coraggioso a dissacrare questo antico mausoleo`n`n");
       $caso = rand(1,10);
       if ($caso == 1){
          output ("Riesci a trovare il marchingegno che bloccava l'entrata ed evitando la trappola con le punte velenose nel
          terreno riesci ad entrare in quella che sembra la stanza del morto.`n`nVicino ad un sarcofago vedi due grandi forzieri");
          $soldi = rand($session['user']['level']*100,$session['user']['charm']*500);
          $gemme = rand(intval($session['user']['level']*0.2),intval($session['user']['charm']*0.5));
          $session['user']['gold'] += $soldi;
          $session['user']['gems'] += $gemme;
          output ("Non riesci a credere ai tuoi occhi, il tesoro davanti a te è <b style='font-size:18px;color:#FF0000;'>IMMENSO !!!</b>`n`n", true);
          output ("`6Trovi $soldi monete d'oro e $gemme gemme");
          if ($session['user']['login'] == "Darking"
             OR $session['user']['login'] == "Darking"
             OR e_rand(1,30) == 20
          ){
             output("`n`n`(Purtroppo non fai caso ad un marchingegno inserito nei forzieri che spruzza direttamente sul tuo viso ");
             output("un liquido `i`Scorrosivo`(`i che deturpa `b`iORRIBILMENTE`i`b la tua bellezza !!!`n`n`6Ti conviene andare a ");
             output("fare un salto da `&Eros Esotico`6 per recuperare parte dei punti persi !!!`n`n");
             $losecharm = $gemme * 4;
             $session['user']['charm'] -= $losecharm;
             debuglog("perde $losecharm punti fascino");
          }
          debuglog("trova $soldi oro e $gemme gemme dopo aver dissacrato il mausoleo");
          addnav("Torna alla foresta","forest.php");
       }else{
          output ("Riesci a trovare il marchingegno che bloccava l'entrata ed evitando la trappola con le punte velenose nel
          terreno riesci ad entrare in quella che sembra la stanza del morto.`n`nVicino ad un sarcofago vedi due grandi forzieri");
          output ("`n`n`6Non hai fatto più di due passi verso i forzieri che quella che ti sembrava una statua ti attacca");
          output ("`n`n`6Ti giri per fuggire ma noti che altre due di queste creature ti sono alle spalle`n");
          output("<a href='golem.php'>Chiedere di rispiarmarti non servirebbe a molto quindi devi distruggerli</a>", true);
          addnav("","golem.php");
          addnav("Attacca i Golem","golem.php");
       }
    }
}
page_footer();
?>