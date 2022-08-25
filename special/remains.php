<?php
/* Skeletal Remains v1.1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)

Version History
1.0 original version
1.1 bug fix (would not award gold nor gem(s) upon defeating the warrior)
*/
if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`#Mentre girovaghi per la foresta, ti imbatti nei resti scheletrici di quello che una volta è stato probabilmente ");
    output("un compagno di avventure.  I resti sembrano intatti e ti immagini che qualunque tesoro egli avesse con se ");
    output("è ancora probabilmente tra le ossa che giacciono ai tuoi piedi.`n`nCosa decidi di fare?`n`n");
    addnav("`%Controlla le Spoglie","forest.php?op=desecrate");
    addnav("`#Lascia il Corpo in Pace","forest.php?op=leave");
    if ($session['user']['turns'] > 3) {
        addnav("`@Dai una Sepoltura alle Spoglie (costo 4 turni)","forest.php?op=bury");
    }
    $session['user']['specialinc']="remains.php";
} elseif ($_GET['op']=="bury"){
    $session['user']['turns']-=4;
// The following two lines are specific to lgd.tod-online.com
    $session['user']['evil']-=10;
    output("`#Passi una buona parte della giornata a scavare una fossa per l'eroe caduto.  Ti astieni dal prelevare ");
    output("le ricchezze quando si tratta di furto, anche dai morti.`n`nDopo tutto, non desideri che i morti siano ");
    output("alla tua mercè come anche delle creature della foresta.`n`n");
    $rand = rand(1,3);
    if ($rand != 1) {
        output("Mentre ti prepari ad abbandonare la tomba, una piccola fata appare di fronte a te. `n\"`@Ciò che hai ");
        output("fatto è stato un gesto nobile, e come tale va ricompensato.`#\"`n`n");
        $reward = rand(1,8);
        switch ($reward) {
            case 1:
            case 2:
            case 3:
                output("`^La fata ti dona una gemma !");
                debuglog("riceve una gemma per aver sepolto un corpo");
                $session['user']['gems']++;
                break;
            case 4:
            case 5:
            case 6:
                $cash = rand(($session['user']['level']*20),($session['user']['level']*40));
                output("`^La fatina ti dona $cash pezzi d'oro !");
                debuglog("riceve $cash oro per aver sepolto un corpo");
                $session['user']['gold']+=$cash;
                break;
            case 7:
            case 8:
                output("`^La fatina ti dona due gemme !");
                debuglog("riceve due gemme per aver sepolto un corpo");
                $session['user']['gems']+=2;
                break;
        }
    } else {
        output("`^Ti senti felice per il gesto compiuto.`n");
        output("Guadagni due punti fascino !");
        debuglog("guadagna due punti fascino per aver sepolto un corpo");
        $session['user']['charm']+=2;
    }
    $session['user']['specialinc']="";
} elseif ($_GET['op']=="leave"){
    output("`%Qualcuno o qualcosa deve aver ucciso questo guerriero, ed è probabile che sia nelle vicinanze. È più sicuro ");
    output("andarsene e non disturbarlo.`n`n");
    $session['user']['specialinc']="";
} elseif ($_GET['op']=="desecrate"){
    $session['user']['turns']--;
    output("`4Inizi a frugare in ciò che resta del corpo, cercando di arricchirti facilmente.`n");
    $session['user']['clean'] += 2;
    $session['user']['evil']+=5;
    switch (rand(1,4)) {
        case 1:
            $gem_gain = rand(1,4);
            $gold_gain = rand($session['user']['level']*10,$session['user']['level']*20);
            $gemword = "gemme";
            if ($gem_gain == 1) $gemword = "gemma";
            output("`%Frugare il corpo in cerca di ricchezze ha pagato (almeno dal punto di vista monetario)! Trovi `&$gem_gain $gemword ");
            output("`%e `&$gold_gain pezzi d'oro `%!!`n`n");
            $session['user']['gems']+=$gem_gain;
            $session['user']['gold']+=$gold_gain;
            $session['user']['specialinc']="";
            debuglog("ruba $gem_gain gemme e $gold_gain oro da un corpo");
            break;
        case 2:
        case 3:
            output("`%Frughi velocemente il corpo, ma non trovi nulla se non qualche arma arrugginita e qualche vestito sdrucito. ");
            output("Forse la prossima volta sarai più fortunato.`n`n");
            $session['user']['specialinc']="";
            debuglog("ha saccheggiato un corpo e non ha trovato nulla");
            break;
        case 4:
            output("`%Mentre saccheggi il cadavere, un movimento improvviso cattura la tua attenzione. `\$`bFai un salto ");
            output("all'indietro `b`%per vedere il cadavere che si alza in piedi. Le orbite vuote ti puntano con un `\$bagliore rossastro`%. ");
            output("Se potesse parlare, stai sicuro che ti maledirebbe, ma il malefico rumore di sfregamento osso contro osso ");
            output("è sufficiente a trasmetterti i brividi lungo la spina dorsale.`n`n");
            $badguy = array(
                "creaturename"=>"`\$Guerriero Scheletrico`0",
                "creaturelevel"=>$session['user']['level']+1,
                "creatureweapon"=>"Arma Arrugginita",
                "creatureattack"=>$session['user']['attack']+2,
                "creaturedefense"=>$session['user']['defence']+2,
                "creaturehealth"=>round($session['user']['maxhitpoints']*1.25,0),
                "diddamage"=>0);
            $session['user']['badguy']=createstring($badguy);
            $session['user']['specialinc']="remains.php";
            $_GET['op']="fight";
            break;
    }
}
if ($_GET['op']=="run"){
    if (e_rand()%3 == 0){
        output ("`c`b`&Sei riuscito a sfuggire alla minaccia dello scheletro!`0`b`c`n");
        $_GET['op']="";
    }else{
        output("`c`b`\$Non sei riuscito a sfuggire al `@Guerriero Scheletrico!`0`b`c");
        $_GET['op']="fight";
    }
}
if ($_GET['op']=="fight"){
    $battle=true;
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="remains.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            output("`n`3Dopo un duro scontro, sei riuscito a battere il `\$Guerriero Scheletrico`3. `nForse adesso riuscirai ");
            output("a perquisire con calma i resti in cerca di qualche tesoro.`n`n");
            output("O almeno, fino a che il prossimo esploratore si imbatterà nel corpo in cerca di oro.`n");
            debuglog("ha sconfitto il Guerriero Scheletrico dopo averlo disturbato");
            if (rand(1,2)==1) {
                $gem_gain = rand(1,4);
                $gold_gain = rand($session['user']['level']*10,$session['user']['level']*20);
                $gemword = "gemme";
                if ($gem_gain == 1) $gemword="gemma";
                output("`#Dopo aver battuto la minaccia rappresentata dallo scheletro, raccogli felice la tua ricompensa.`n
                Hai trovato `&$gem_gain $gemword ");
                output("`#e `^$gold_gain pezzi d'oro`# !!`n`n");
                $session['user']['gems']+=$gem_gain;
                $session['user']['gold']+=$gold_gain;
            } else {
                output("`3Dopo la dura battaglia, non trovi niente di valore da raccogliere purtroppo.`n`n");
            }
            // The below is because I don't know how to pull the creature experience out of the table.
            // Its actually 10% more than the creature experience, since the level is one higher
            $skelevel = $session['user']['level']+1;
            switch ($skelevel) {
                case 2:
                    $exp_gain = 26;
                    break;
                case 3:
                    $exp_gain = 37;
                    break;
                case 4:
                    $exp_gain = 50;
                    break;
                case 5:
                    $exp_gain = 61;
                    break;
                case 6:
                    $exp_gain = 73;
                    break;
                case 7:
                    $exp_gain = 85;
                    break;
                case 8:
                    $exp_gain = 98;
                    break;
                case 9:
                    $exp_gain = 111;
                    break;
                case 10:
                    $exp_gain = 125;
                    break;
                case 11:
                    $exp_gain = 140;
                    break;
                case 12:
                    $exp_gain = 155;
                    break;
                case 13:
                    $exp_gain = 172;
                    break;
                case 14:
                    $exp_gain = 189;
                    break;
                case 15:
                    $exp_gain = 208;
                    break;
                case 16:
                    $exp_gain = 228;
                    break;
            }
            output("Guadagni $exp_gain punti esperienza per la battaglia disputata.`n`n");
            $session['user']['experience']+=$exp_gain;
            $session['user']['specialinc']="";
        } elseif ($defeat){
            $badguy=array();
            $session['user']['badguy']="";
            debuglog("è stato ucciso dal Guerriero Scheletrico");
            output("`n`7Sei stato battuto dal `\$Guerriero Scheletrico !!`n`n`7Perdi il `^`b10%`b`7 della tua esperienza. ");
            output("`&Inoltre, ");
            $gem_loss = rand(1,4);
            if ($gem_loss >= $session['user']['gems']) {
                output("`&hai perso `bTUTTE`b le tue gemme nella battaglia, assieme a tutto il tuo oro!`n`n");
                $session['user']['gems']=0;
            } else {
                output("hai perso `b$gem_loss`b delle tue gemme nello scontro, assieme a tutto il tuo oro!`n`n");
                $session['user']['gems']-=$gem_loss;
            }
            addnav("`^Notizie Giornaliere","news.php");
            $session['user']['alive']=false;
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            $session['user']['specialinc']="";
            addnews("`@".$session['user']['name']." `%è andato incontro ad una fine prematura. Tutto il villaggio se ne dispiace.");
        } else {
            fightnav(true,true);
        }

}
?>