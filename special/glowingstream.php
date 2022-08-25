<?php
if ($_GET['op']==""){
  output("`#Scopri una piccola fonte di acqua lievemente luminosa che scorre sopra delle pietre di un bianco puro. Puoi sentire un potere ");
    output("Magico nell´acqua. Berla potrebbe darti poteri ignoti, o potrebbe avere come effetto ");
    output("di renderti paraplegico. Vuoi provare a bere?");
    output("`n`n<a href='forest.php?op=drink'>Bevi</a>`n<a href='forest.php?op=nodrink'>Non bere</a>",true);
    addnav("`@Bevi","forest.php?op=drink");
    addnav("`\$Non bere","forest.php?op=nodrink");
    addnav("","forest.php?op=drink");
    addnav("","forest.php?op=nodrink");
    $session['user']['specialinc']="glowingstream.php";
}else{
  $session['user']['specialinc']="";
    if ($_GET['op']=="drink"){
      $rand = e_rand(1,10);
        output("`#Sapendo che l´acqua potrebbe rivelarsi letale, decidi di correre il rischio. Chinandoti ");
        output("sulla fonte, bevi un lungo sorso d´acqua fresca. Senti un forte calore ");
        output("crescerti nel petto, ");
        switch ($rand){
          case 1:
                output("`iseguito da un freddo terrificante`i. Sobbalzi e ti artigli il petto mentre senti quelle che ");
                output("immagini essere le mani del mietitore che ti afferrano inesorabilmente il cuore.");
                output("`n`nCollassi accanto alla fonte, notando a malapena che le pietre che avevi visto ");
                output("sono in effetti i teschi sbiancati degli sfortunati avventurieri che ti hanno preceduto.");
                output("`n`nLa tua vista inizia ad oscurarsi mentre giaci fissando il cielo tra ");
                output("le foglie degli alberi. Il tuo respiro diviene pesante e meno frequente ");
                output("mentre il sole ti lambisce la faccia in netto contrasto con il vuoto che sta prendendo posto nel ");
                output("tuo cuore.");
                output("`n`n`^Sei morto per il malefico potere della fonte.`n");
                output("Poiché le creature della foresta conoscono il pericolo di questo luogo, nessuno è qui a depredare il tuo cadavere, puoi tenere il tuo oro.`n");
                output("La lezione di vita che hai ricevuto compensa l´esperienza che avresti dovuto perdere.`n");
                output("Potrai continuare a giocare domani.");
                debuglog("muore alla fonte luminosa");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                addnav("Notizie quotidiane","news.php");
                addnews($session['user']['name']." si è imbattuto in strani poteri nella foresta, e non è stato più visto.");
            break;
            case 2:
                output("`iseguito da un freddo terrificante`i. Sobbalzi e ti artigli il petto mentre senti quelle che ");
                output("immagini essere le mani del mietitore che ti afferrano inesorabilmente il cuore.");
                output("`n`nCollassi accanto alla fonte, notando a malapena che le pietre che avevi visto ");
                output("sono in effetti i teschi sbiancati degli sfortunati avventurieri che ti hanno preceduto.");
                output("`n`nLa tua vista inizia ad oscurarsi mentre giaci fissando il cielo tra ");
                output("le foglie degli alberi. Il tuo respiro diviene pesante e meno frequente ");
                output("mentre il sole ti lambisce la faccia in netto contrasto con il vuoto che sta prendendo posto nel ");
                output("tuo cuore.");
                output("Mentre esali il tuo ultimo respiro, senti un lieve risolino lontano. Trovi la forza di ");
                output("aprire gli occhi, e ti ritrovi a fissare una fatina che, volando proprio sopra la tua faccia ");
                output("ti sta inavvertitamente spruzzando addosso la sua polvere fatata, dandoti la forza di rimetterti ");
                output("nuovamente in piedi. Il tuo movimento spaventa la creaturina, e prima che tu abbia la possibilità ");
                output("di ringraziarla, questa vola via.");
                output("`n`n`^Hai evitato la morte per un soffio, perdi un combattimento nella foresta e molti dei tuoi punti ferita.");
                debuglog("perde un combattimento e molti HP alla fonte luminosa");
                if ($session['user']['turns']>0) $session['user']['turns']--;
                if ($session['user']['hitpoints']>($session['user']['hitpoints']*.1)) $session['user']['hitpoints']=round($session['user']['hitpoints']*.1,0);
            break;
            case 3:
              output("ti senti RINVIGORITO!");
                output("`n`n`^Sei stato completamente guarito e ti senti dentro l´energia per un altro turno nella foresta.");
                debuglog("guadagna 1 turno alla fonte luminosa");
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                $session['user']['turns']++;
                break;
            case 4:
              output("ti senti RICETTIVO! Noti qualcosa che brilla tra i ciottoli che circondano la fonte.");
                output("`n`n`^Trovi una GEMMA!");
                debuglog("trova 1 gemma alla fonte luminosa");
                $session['user']['gems']++;
                break;
            case 5:
            case 6:
            case 7:
              output("ti senti ENERGETICO!");
                output("`n`n`^Ricevi un turno extra di combattimento nella foresta!");
                debuglog("guadagna 1 turno alla fonte luminosa");
                $session['user']['turns']++;
                break;
            default:
              output("ti senti VIGOROSO!");
                output("`n`n`^Sei stato completamente guarito.");
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }else{
      output("`#Temendo il terribile potere dell'acqua, decidi di lasciarla dove si trova e tornare nella foresta.`n");
    }
}
?>