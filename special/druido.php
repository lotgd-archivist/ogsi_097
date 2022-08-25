<?php
/**ss**********************ss***************
/ Druid (Forest Special)- By Mortimer & Strider
/ 27Jan2004 for the Legendgard Elite (LoGD)
/ version 1.5
Strider's Changes for this version
- cleaned up a few errors
- rounded the numbers for clarity.
- added debugging for tracking
First created 5Jan2004  -  By Strider and storyline by Mortimer
// Based on Strider's fixes for "The Burning Bush" by Robert (Maddnets LoGD)
- - - - - Special thanks to Robert - - - - - -
**ss**************************ss************/
// -Originally by: Strider & Mortimer
// Feb 2004  - Legendgard Script Release
// Apr 2004 - Translated to Italian by Excalibur
if (!isset($session)) exit();

if ($_GET['op']==""){
    output("`n`2Entri in una radura, dominata dalla più grande `@Quercia `2che tu abbia mai visto.`n`n");
    output("`2Sotto l'albero la figura magra di un uomo molto anziano con un lungo bastone nodoso si erge in piedi dando la sensazione `n");
    output("`2di possedere una grande potenza e una immensa forza magica che puoi quasi vedere aleggiare intorno a lui. `n");
    output("`2Un corvo svolazza sopra la sua testa e, ricordando le antiche leggende, riconosci immediatamente in lui un `3Druido Sacerdote`2.`n");
    output("`2E' il mitico `%Cathbad`2, famosissimo per le sue profezie e dalla indubbia autorità visto che la cui parola è rispettata da tutti e ha `nuna enorme influenza persino sulle decisioni prese dai re.`n`n");
    output("`2Egli ti offre una tazza di `^Brodo Rincuorante. `2Insicuro della sua benevolenza, cosa decidi di fare? ");
    addnav("A?`2Accetta il Brodo","forest.php?op=take");
    addnav("R?`\$Rifiuta il Brodo","forest.php?op=dont");
    $session['user']['specialinc']="druido.php";
}else if ($_GET['op']=="take"){
		$ident_armatura=array();
		$ident_armatura = identifica_armatura();
		$articoloarmatura = $ident_armatura['articolo'];
        output("`n`2Prendi la `^Tazza `2 e la porti alle labbra. Dopo un momento di esitazione, bevi il liquido ribollente velocemente.");
        output("`n`2 Senti il `^Brodo `2scorrerti lungo la gola come una fiamma liquida e bruciare dentro il tuo corpo. `n");
        if (!zainoPieno($session['user']['acctid'])){
            $caso=e_rand(1,11);
        } else {
            $caso=e_rand(1,10);
        }
        switch($caso){
            case 1:
                output(" `2La pozione magica appena bevuta ti riempie di `6Energia`2.`n`nRicevi `^2`2 turni di combattimento!`n");
                debuglog("guadagna 2 combattimenti dal Druido");
                $session['user']['turns']+=2;
                break;
            case 2:
                output(" `2Il liquido magico acuisce la tua vista e vedi cose che prima ti sarebbero sfuggite.`n`nTrovi `&1 gemma `2sul terreno ai tuoi piedi!`n");
                debuglog("guadagna 1 Gemma dal Druido");
                $session['user']['gems']+=2;
                break;
            case 3:
                output(" `2Nel brodo c'erano sicuramente delle ostriche che aumentano la tua bellezza!`n`n");
                output(" `2Guadagni `^5`2 punti fascino!");
                debuglog("guadagna 5 punti fascino dal Druido");
                $session['user']['charm']+=5;
                break;
            case 4: case 5:
                output(" `2Una sferzata di `6Energia`2 si propaga nel tuo corpo e senti la tua forza crescere dentro di te!`n`n");
                output(" `2I tuoi HP massimi sono `b`6permanentemente`b aumentati`2 di `^1`2!");
                debuglog("guadagna 1 HP dal Druido");
                $session['user']['maxhitpoints']++;
                $session['user']['hitpoints']++;
                break;
            case 6:
                output(" `2Una intensa nausea ti prende lo stomaco e quasi vomiti a causa del terribile sapore di ciò che hai ingurgitato!`n`n");
                output(" `2I tuoi HP massimi sono `b`6permanentemente`b diminuiti`2 di `^1`2!");
                debuglog("perde 1 HP dal Druido");
                $session['user']['maxhitpoints']--;
                $session['user']['hitpoints']--;
                break;
            case 7:
                //ss//let's fix this so it offers a round number and something a little more trackable.
                $expgain = round($session['user']['experience']*0.05);
                if ($expgain < 10) $expgain=10;
                $session['user']['experience']+=$expgain;
                output(" `2La tua fede nella pozione magica del `3Druido`2 è stata ben riposta, pellegrino!`n`n");
                debuglog("guadagna $expgain exp dal Druido");
                output(" `2Guadagni `^$expgain`2 punti esperienza!`n");
                break;
            case 8:
                output(" `2Il liquido è talmente bollente che ti scotti la lingua e rovesci il brodo inzozzando completamente $articoloarmatura `#".$session['user']['armor']."`2!`n`nPerdi `^2`2 punti fascino!");
                $session['user']['charm']-=2;
                break;
            case 9:
                //ss//let's fix this so it offers a round number and something a little more trackable.
                $loss = round($session['user']['hitpoints']*.8);
                output(" `2Dopo aver svuotato completamente la ciotola senti le tue forze esaurirsi e venire meno.`n`n Perdi `^$loss`2 HP!");
                $session['user']['hitpoints']-=$loss;
                break;
            case 10:
                output(" `2Il liquido magico prosciuga parte delle tue `6Energie`2.`n`nPerdi `^1`2 combattimento!`n");
                $session['user']['turns']--;
                break;
            case 11:
            if (!zainoPieno($session['user']['acctid'])) {
                $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('2','{$session['user']['acctid']}')";
                db_query($sqldr);
                output("`2Le capacità magiche del brodo ti acuiscono notevolmente la vista. `n");
                output("Infatti subito dopo aver salutato l'anziano `3Druido`2, sul sentiero innanzi a te intravedi tra le sterpaglie una scaglia di `(rame`2 che raccogli e riponi nelllo zaino!`n`n");
                debuglog("trova 1 scaglia di rame dal Druido");
            }else{
                output("`2Le capacità magiche del brodo ti acuiscono notevolmente la vista. ");
                output("Infatti subito dopo aver salutato l'anziano `3Druido`2, sul sentiero innanzi a te trovi una scaglia di `(rame`2, ma non avendo spazio nello zaino non puoi raccoglierla!`n`n");
            }
           break;
        }
    }else{
    output("`n`2Non confidando nelle arti magiche del vecchio `3Druido`2, declini l'offerta e ti volti per andartene. `n");
        switch(e_rand(1,9)){
            case 1: case 8:
                $expgain = round($session['user']['experience']*0.05);
                if ($expgain < 10) $expgain=10;
                $session['user']['experience']+=$expgain;
                output(" `2Sulle prime l'anziano Sacerdote sembra offeso dal tuo rifiuto
                mentre ti affretti a porgere le tue più umili scuse allontanandoti lentamente.`n Il saggio Druido le accetta e ti benedice
                mentre ti allontani da questo luogo sacro. La fortuna non ti ha voltato le spalle questa volta!`n`n");
                debuglog("guadagna $expgain exp dal Druido");
                output(" `2Guadagni `^$expgain `2punti esperienza per il pericolo scampato!`n");
                break;
            case 2: case 5:
                output(" `2Il `3Druido`2 si sente insultato dal tuo rifiuto e ti scaglia addosso la ciotola di ");
                output(" `2brodo bollente sporcandoti dalla testa ai piedi.`n`n");
                output(" `2Perdi `^2`2 punti fascino!");
                debuglog("perde 2 punti fascino dal Druido");
                $session['user']['charm']-=2;
                break;
            case 3: case 7:
                $loss = round($session['user']['hitpoints']*.8);
                $session['user']['hitpoints']-=$loss;
                output("`2Mentre tenti di allontanarti inciampi e cadi malamente, perdendo `^$loss`2 HP!`n");
                if ($session['user']['hitpoints']<=0){
                    $session['user']['alive']=false;
                    addnav("`^Notizie Giornaliere","news.php");
                    debuglog("muore per mancanza di hp dopo aver rifiutato brodo Druido");
                    addnews("`2".$session['user']['name']."`5 è stato ritrovato morto sotto un'enorme quercia.");
                }
                break;
            case 4: case 6:
                output("`2Preoccupato per la tua incolumità, scappi a rotta di collo girovagando per ore. perdi `^1`2 turno di combattimento!`n");
                $session['user']['turns']--;
                break;
            case 9:
                output("`2Il `3Druido`2 infuriato ti lancia contro una maledizione.`n La tua vista si annebbia fino a che gli
                artigli della morte ti ghermiscono e ti trascinano nella `\$Terra delle Ombre.`n");
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                debuglog("muore per aver rifiutato brodo Druido");
                addnav("`^Notizie Giornaliere","news.php");
                addnews("`2".$session['user']['name']."`5 è stato ritrovato morto sotto un'enorme quercia con il volto straziato dal terrore.");
                break;
        }
    }

?>