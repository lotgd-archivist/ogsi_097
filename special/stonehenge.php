<?php
if ($_GET['op']==""){
  output("`#Girovaghi e perlustri la foresta senza direzione, cercando qualcosa da combattere. All'improvviso la foresta si apre in un campo.");
    output("Nel centro vedi un cerchio formato da pietre. Hai trovato il leggendario ");
    output("Stonehenge! Hai sentito i contadini parlare di questo mitico luogo, ma ");
    output("non avevi mai creduto possibile esistesse. Dicevano che il cerchio ha grandi ");
    output("poteri, sebbene non si possano prevedere. Cosa vuoi fare?");
    output("`n`n<a href='forest.php?op=stonehenge'>`@Vai a Stonehenge</a>`n<a href='forest.php?op=leavestonehenge'>`\$Abbandona questo luogo</a>",true);
    addnav("`@Vai a Stonehenge","forest.php?op=stonehenge");
    addnav("`\$Abbandona questo luogo","forest.php?op=leavestonehenge");
    addnav("","forest.php?op=stonehenge");
    addnav("","forest.php?op=leavestonehenge");
    $session['user']['specialinc']="stonehenge.php";
}else if($_GET['op']=="stonehenge"){
    $session['user']['clean'] += 1;
    $session['user']['specialinc']="";
    $rand = e_rand(1,22);
    output("`#Pur sapendo che i poteri delle pietre sono imprevedibili, decidi di sfruttare l'occasione. ");
    output("Cammini fino al centro delle pietre millenarie, pronto a sperimentare l'impressionante potere di Stonehenge. ");
    output("Mentre entri nel cerchio, il cielo diventa scuro, scende la notte, e noti che il terreno sul quale ");
    output("appoggi i piedi è illuminato da una flebile luce viola, come se il terreno stesso si stesse trasmutando ");
    output("in nebbia. Senti un formicolio che avviluppa tutto il tuo corpo. Improvvisamente una luce ");
    output("abbagliante inghiotte il cerchio di pietre, e te con esso. Quando la luce scompare ");
        switch ($rand){
          case 1:
          case 2:
                output(" non sei più a Stonehenge.`n`nTutto intorno a te ci sono le anime di coloro che ");
                output("sono morti in battaglia, per vecchiaia, e in misteriosi incidenti. Ogni anima porta un cartellino ");
                output("che racconta le circostanze della loro dipartita. Ti rendi conto con un crescente senso di sgomento che ");
                output("il circolo di pietre ti ha trasportato nella Landa dei Morti!");
                output("`n`n`^Sei stato mandato nella Terra delle Ombre a causa della tua scelta insensata.`n");
                output("Poichè sei stato trasportato fisicamente nell'Ade, mantieni tutto il tuo oro.`n");
                output("Perdi il 5% della tua esperienza.`n");
                output("Potrai tornare a combattere domani.");
                debuglog("muore a Stonehenge perdendo 5% exp");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['experience']*=0.95;
                addnav("`^Notizie Giornaliere","news.php");
                addnews($session['user']['name']." `2se n'è andat".($session['user']['sex']?"a":"o")." per un po', e coloro che l'hanno cercat".($session['user']['sex']?"a":"o")." non sono più tornati.");
                break;
            case 3:
                output("vedi il corpo di un folle viaggiatore che ha deciso di sfidare il potere di Stonehenge.");
                output("`n`n`^La tua anima è stata estirpata dal tuo corpo!`n");
                output("Poichè il tuo corpo giace a Stonehenge, perdi tutto il tuo oro.`n");
                output("Perdi il 10% della tua esperienza.`n");
                output("Potrai continuare a combattere domani.");
                debuglog("muore a Stonehenge perdendo 10% exp e {$session['user']['gold']} oro");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['experience']*=0.9;
                $session['user']['gold'] = 0;
                addnav("`^Notizie Giornaliere","news.php");
                addnews("`5Il corpo di `2".$session['user']['name']."`5 è stato ritrovato in una radura.");
                break;
            case 4:
            case 5:
            case 6:
                output("senti una bruciante energia percorrere il tuo corpo, i tuoi muscoli sembrano tizzoni ardenti. Quando il terribile dolore termina, noti che i tuoi muscoli sono MOLTO più grossi.");
                $reward = $session['user']['experience'] * 0.2;
                debuglog("guadagna $reward exp a Stonehenge");
                output("`n`n`^Hai guadagnato `7$reward`^ punti esperienza!");
                $session['user']['experience'] += $reward;
                break;
            case 7:
            case 8:
            case 9:
            case 10:
                $reward = e_rand(1, 4);
                 if ($reward == 4) $rewardn = "che ci sono `b`%QUATTRO`^`b gemme";
                else if ($reward == 3) $rewardn = "che ci sono `b`%TRE`^`b gemme";
                else if ($reward == 2) $rewardn = "che ci sono `b`%DUE`^`b gemme";
                else if ($reward == 1) $rewardn = "che c'è `b`%UNA`^`b gemma";
                output("...`n`n`^noti $rewardn sul terreno davanti a te!`n`n");
                $session['user']['gems']+=$reward;
                debuglog("trova $rewardn a Stonehenge");
                break;

            case 11:
            case 12:
            case 13:
                $rewardn = e_rand(3,9);
                output("ti senti molto più sicuro di te stesso.`n`n");
                output("`^Guadagni $rewardn punti di fascino!");
                debuglog("guadagna $rewardn punti fascino a Stonehenge");
                $session['user']['charm'] += $rewardn;
                break;
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                output("Ti senti in piena salute.");
                output("`n`n`^I tuoi hitpoints sono stati riportati al massimo!");
                if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                break;
            case 19:
            case 20:
                output("senti la tua forza salire alle stelle!");
                $reward = intval($session['user']['maxhitpoints'] * 0.1);
                if ($reward > 20) {
                   $reward=20;
                }
                output("`n`n`^I tuoi HitPoints massimi sono stati `bpermanentemente`b aumentati di `7$reward!`n");
                debuglog("guadagna $reward HP permanenti a Stonehenge");
                $session['user']['maxhitpoints'] += $reward;
                if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
                $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                output("`^I tuoi HitPoints sono stati riportati al massimo!");
                }
                break;
            case 21:
            case 22:
                $prevTurns = $session['user']['turns'];
                if ($prevTurns >= 5) $session['user']['turns']-=5;
                else if ($prevTurns < 5) $session['user']['turns']=0;
                $currentTurns = $session['user']['turns'];
                $lostTurns = $prevTurns - $currentTurns;

                output("il giorno volge al termine. Sembra che Stonehenge abbia congelato il tempo per tutta la giornata.`n");
                output("E, come risultato, perdi $lostTurns combattimenti nella foresta!");
                debuglog("perde $lostTurns turni foresta a Stonehenge");
                break;
        }
}else if ($_GET['op']=="leavestonehenge"){
      output("`#Percependo il terribile potere di Stonehenge, decidi di lasciar perdere, e ritorni nella foresta.");
}
?>