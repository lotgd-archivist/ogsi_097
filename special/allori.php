<?php
/*
Dapne's Quest - Forest Special
By Daphne (www.ogsi.it) and Poker (www.ogsi.it)
version 1.0 june 28, 2004
Corretto da Excalibur (www.ogsi.it)
*/

if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`n`n`2Improvvisamente ti accorgi di aver imboccato uno strano sentiero. Ti ritrovi in un viale di rigogliosi
    alberi di alloro dove le loro radici, intersecandosi e diramandosi, vanno a formare strane geometrie.`n`n
    Rimani impietrit".($session[user][sex]?"a":"o")." a fissare l’immagine che ti si pone davanti: dalla più imponente delle piante senti giungere un
    lamento e ai suoi piedi un uomo circondato da un'aurea divina ti fissa con astio e sembra stia pensando se attaccarti o meno.`n`n
    Il lamento che odi è il pianto di `@Daphne`2, ninfa dei fiumi da millenni trasformata in un albero per poter sfuggire alle proposte
    amorose di un dio seduttore e presuntuoso. Ed è da quel giorno che `@Daphne`2 è in attesa che qualcuno la liberi dal sortilegio a cui è stata costretta a sottoporsi,
    ma l’unico modo per rompere l'incantesimo è sconfiggere il `^Febo Apollo`2, seduto lì a guardia all’albero che imprigiona la donna da lui amata perdutamente.`n
    `n".$session[user][name]." `2sarai tanto generos".($session[user][sex]?"a":"o")." da aiutare una povera donna schiava di un amore da lei non ricambiato?`n");
    addnav("Aiuta `@Daphne`!","forest.php?op=aiuta");
    addnav("Parla con `^Apollo`!","forest.php?op=parla");
    addnav("Torna da dove sei venut".($session[user][sex]?"a":"o")."","forest.php?op=esci");
    $session['user']['specialinc']="allori.php";
}else if ($_GET['op']=="aiuta"){
output("`n`^Apollo`2 senza neanche proferire parola ti assale non appena fai cenno di avvicinarti a `@Daphne`2!...`n A questo punto non ti resta altro
da fare che affrontare in un combattimento all'ultimo sangue la divinità !`n");
            $badguy = array(
                "creaturename"=>"`^Apollo`0",
                "creaturelevel"=>$session['user']['level']+2,
                "creatureweapon"=>"`^Raggi di sole`0",
                "creatureattack"=>$session['user']['attack']+5,
                "creaturedefense"=>$session['user']['defence']+5,
                "creaturehealth"=>round($session['user']['maxhitpoints']*1.5,0),
                "diddamage"=>0);
            $session['user']['badguy']=createstring($badguy);
            $session['user']['specialinc']="allori.php";
            $_GET['op']="fight";
}else if ($_GET['op']=="parla"){
    output("`n`^Apollo`2 ti studia attentamente... poi schiarendosi la voce ti propone una sfida.`n`n
    \"".$session[user][name]." `^Io sono un essere divino e, come tale, imbattibile ! `nVoglio però dare a te misero essere inferiore una possibilità : hai solo un modo per andartene illes".($session[user][sex]?"a":"o")." ed è gareggiare con me in una gara di flauto. Come puoi vedere quì ce ne sono due,
    uno `&bianco`^ e uno `xnero`^, sappi che uno dei due è avvelenato. Quale scegli?`2\"");
    addnav("Flauto `&Bianco`!","forest.php?op=bianco");
    addnav("Flauto `xNero`!","forest.php?op=nero");
    addnav("Provi a svignartela","forest.php?op=scappa");
    $session['user']['specialinc']="allori.php";
}else if ($_GET['op']=="bianco" OR $_GET['op']=="nero" OR $_GET['op']=="scappa"){
	$limite=6;
    if ($_GET['op']=="scappa") {
    	$flauto=e_rand(0,1);
    	$limite=5;
    	if ($flauto=0) {
			$descflauto="`&bianco`2";
		}else {	
			$descflauto="`xnero`2";
		}	
       output("`n`^Ignobile creatura non scappare .... ".$session[user][name]." `^non tentare di fare ".($session[user][sex]?"la furba":"il furbo")." con me ! Non puoi sfuggirmi e la mia punizione sarà tremenda!`n");
    }
    if ($_GET['op']=="bianco") {
    	$descflauto="`&bianco`2";
	}else {	if ($_GET['op']=="nero") {
    			$descflauto="`xnero`2";
			}
	}	
    output("`n`2Osservi i due flauti preoccupat".($session[user][sex]?"a":"o")."... Sei come impietrit".($session[user][sex]?"a":"o")." di fronte agli occhi fiammeggianti d'odio di `^Apollo`2 e tremando allunghi la tua mano ");
    output("verso uno dei due flauti sperando nella buonasorte ... `n");
    $flauto=e_rand(0,$limite);
    if ($flauto>2) {
         output("`n`2Avvicini le labbra al flauto $descflauto e provi a suonare un motivo, tremando come una foglia, ma non ti succede nulla. `n");
         output("`n".$session[user][name]." `^ questa volta la fortuna è stata dalla tua parte, hai scelto il flauto giusto! `n`2Il `^Febo Apollo`2 ha deciso di risparmiarti la vita e con un gesto rapido e ti indica la strada per ");
         output("abbandonare quel luogo e tornare alla foresta. Non hai salvato `@Daphne`2 ma almeno sei sopravvissut".($session[user][sex]?"a":"o")." a questa terribile avventura!`n`n");
         debuglog("Suona un Flauto da Apollo e ne esce vivo");
    }else {
    output("`n`2Ti risvegli nella foresta gravemente ferit".($session[user][sex]?"a":"o").", ti rendi conto di essere svenut".($session[user][sex]?"a":"o")." ma non sai ");
    output("per né quanto tempo né ti ricordi cosa ti ha fatto perdere i sensi.");
    $session['user']['hitpoints']=1;
    $gem_loss = rand(1,4);
    if ($session['user']['gems'] != 0){
        if ($gem_loss >= $session['user']['gems']) {
            output("Hai un brutto presentimento e inizi subito a frugare nelle tue tasche e, Orrore ! Ti accorgi di aver perso `^tutte`2 le tue `&gemme`2 tanto faticosamente accumulate!`n`n");
            $gem_loss = $session['user']['gems'];
            $session['user']['gems']=0;
        } else {
            output("Frugando nelle tue tasche ti accorgi di aver perso `^$gem_loss`2 delle tue preziose `&gemme`2 !`n`n");
            $session['user']['gems']-=$gem_loss;
        }
        debuglog("Suona un Flauto e resta con 1 hp da Apollo e perde anche $gem_loss");
    }else {
        debuglog("resta con 1 hp da Apollo");
    }
    }
    addnav("Torna alla Foresta","forest.php");
    $session['user']['specialinc']="";
}else if ($_GET['op']=="esci"){
    $uscita=e_rand(0,6);
    $fascino=e_rand(2,6);
    if ($uscita>4){
      output("`n`2Decidi di indietreggiare fino ai margini della foresta temendo di non poter in alcun modo contrastare vittoriosamente un dio. ");
      output("`n“`#Tanto, pensi, nessuno lo scoprirà mai!`#”.`n`n`2Ti sbagliavi: gli alberi d'alloro che delimitano ");
      output("il viale non sono altro che esploratori senza nerbo come te che arrivati qui pensarono ");
      output("bene di scappare e vennero trasformati in piante secolari per la loro codardia. Improvvisamente ti senti impossibilitat".($session[user][sex]?"a":"o")." a muoverti e in men che non si dica ");
      output("il tuo corpo si trasforma in un alloro, mentre la tua anima scivola nell’Oltretomba.`n");
      output("`n`\$Perdi tutto l'oro che avevi con te, il `^10%`\$ della tua esperienza e `^$fascino`\$ punti fascino!!");
      $oroperso = $session['user']['gold'];
         $session['user']['gold']=0;
         $session['user']['experience']*=0.9;
         $session['user']['hitpoints']=0;
         $session['user']['alive']=false;
         $session['user']['charm']-=$fascino;
         $session['user']['specialinc']="";
         addnews("`@".$session['user']['name']." `3 ha incontrato il `^Dio Apollo `3che gliele ha suonate!!!");
         debuglog("muore, perde $oroperso oro, il 10% exp e 2 punti fascino da Apollo");
         addnav("Notizie Giornaliere","news.php");
      }else{
      output("`n`2Decidi di indietreggiare fino ai margini della foresta temendo di non poter in alcun modo contrastare vittoriosamente un dio. ");
      output("`n“`#Tanto, pensi, nessuno lo scoprirà mai!`2”. `n`nIl `^Febo Apollo`2 ti guarda allontanarti senza battere ");
      output("ciglio poi urla nella tua direzione `n`n\"`^Vattene Vigliacc".($session[user][sex]?"a":"o").", Vattene pure indisturbat".($session[user][sex]?"a":"o")." ");
      output(" ".$session['user']['name']."`2\".`n`nTi senti in colpa, ma per tua fortuna sei sopravvissut".($session[user][sex]?"a":"o")." a questo terribile incontro e sei ancora ");
      output("viv".($session['user']['sex']?"a":"o")."!`n");
      addnav("Torna alla Foresta","forest.php");
      $session['user']['specialinc']="";
      }
}
if ($_GET[op]=="run"){
    if (e_rand()%5 == 0){
        output ("`n`n`c`b`&Sei riuscit".($session[user][sex]?"a":"o")." a sfuggire ai raggi di sole del `^Dio Apollo!`0`b`c`n");
        $_GET[op]="";
    }else{
        output("`n`n`c`b`\$Non sei riuscit".($session[user][sex]?"a":"o")." a sfuggire al `^Dio Apollo!`0`b`c");
        $_GET[op]="fight";
    }
}
if ($_GET['op']=="fight"){
    $battle=true;
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="allori.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            $exp_gain=$session['user']['level']*150;
            $gem_gain = rand(1,3);
            $fascino = rand(2,4);
            $gold_gain = rand(300,400)*$session['user']['level'];
            output("`n`2Non appena `^Apollo`2 si accascia a terra esangue mormorando \"`^Chi, amando, insegue le gioie della bellezza fugace riempie la mano di fronde e coglie bacche amare...\" `2il cielo si colora improvvisamente d’`^oro`2, ");
            output("gli alberi attorno a `@Daphne`2 aprono i loro rami mostrandoti una portentosa magia. L’alloro ");
            output("imponente stacca lentamente le radici dal terreno, una radice destra si dilunga nell’aria, ");
            output("avvolta da un pulviscolo luccicante fino a trasformarsi in una gamba; lo stesso accade con la ");
            output("radice a sinistra. Un alto ramo, avvolto nella stessa polvere brillantinata, ");
            output("incredibilmente diventa un candido braccio, subito accompagnato da un altro ramo vicino ");
            output("che compie la stessa trasformazione. Quelle che prima erano foglie verdi iniziano a tremolare, ");
            output("si colorano di nero, si assottigliano fino a diventare una massa indistinta di colore, ");
            output("ma ben presto ti accorgi che sono capelli. Il silenzio e la quiete ti avvolgono, rendendoti ");
            output("testimone di una portentosa trasformazione. Ai tuoi piedi ora c’è una pallida fanciulla, vestita solo ");
            output("della sua lunga chioma corvina. Copiose, ma silenziose lagrime rigano il suo volto, ma sono ");
            output("lacrime di gioia per aver riacquistato la libertà tanto desiderata in secoli di prigionia.`n`n");
            output("`2Guadagni `^$exp_gain`2 punti esperienza per la battaglia disputata.`n`n");
            output("`@Daphne`2 per ringraziarti ti dona anche `^$gem_gain `&gemme`2 e `^$gold_gain`2 pezzi d'`^oro`2 !!!");
            output("Ti senti particolarmente seren".($session[user][sex]?"a":"o")." e guadagni anche `^$fascino`2 punti di fascino!!!");
            debuglog("batte Apollo e guadagna $exp_gain exp, $gem_gain gemme e $gold_gain oro e $fascino di fascino da Apollo");
            addnews("`@".$session['user']['name']." `3ha incontrato il `^Dio Apollo `3e ne è uscit".($session[user][sex]?"a":"o")." più bell".($session[user][sex]?"a":"o")." di prima !!!");
            $session['user']['gold']+=$gold_gain;
            $session['user']['gems']+=$gem_gain;
            $session['user']['charm']+=$fascino;
            $session['user']['experience']+=$exp_gain;
            $session['user']['specialinc']="";
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']="";
            $testo="è stato ucciso dal `^Dio Apollo`";
            output("`n`7Sei stat".($session[user][sex]?"a":"o")." battut".($session[user][sex]?"a":"o")." dal `^Dio Apollo !!`n`n`7Perdi il `^`b10%`b`7 della tua esperienza. ");
            output("Inoltre, ");
            $gem_loss = rand(1,3);
            if ($gem_loss >= $session['user']['gems']) {
                output("hai perso `^tutte`7 le tue `&gemme`7 nella battaglia, assieme a tutto il tuo `^oro`7!`n`n");
                $testo=$testo.", perde {$session['user']['gems']} gemme";
                $session['user']['gems']=0;
            } else {
                output("hai perso `^$gem_loss`7 delle tue `&gemme`7 nello scontro, assieme a tutto il tuo `^oro`7!`n`n");
                $testo=$testo.", perde $gem_loss gemme";
                $session['user']['gems']-=$gem_loss;
            }
            addnav("`^Notizie Giornaliere","news.php");
            $gold_loss = $session['user']['gold'];
            debuglog("$testo, $gold_loss oro e il 10% di exp nell'evento di Apollo");
            $session['user']['alive']=false;
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            $session['user']['specialinc']="";
            addnews("`@".$session['user']['name']." `3ha incontrato il `^Dio Apollo `3e ne è uscit".($session[user][sex]?"a":"o")." inalberat".($session[user][sex]?"a":"o")." !!!");
        } else {
            fightnav(true,true);
        }
}
?>