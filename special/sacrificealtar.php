<?php

/* *******************
Altar of Sacrifice
Written by TheDragonReborn
    Based on Forest.php
******************* */

$specialbat="sacrificealtar.php";
$allowflee=true;
$allowspecial=true;
if ($_GET['op']==""){
    output("`@Mentre girovaghi per la foresta, ti imbatti in un altare di pietra, scolpito ");
    output("in roccia basaltica a lato di un albero secolare. Ti avvicini, e puoi vedere ");
    output("le macchie di sangue essiccato di secoli di sacrifici. Questo è un luogo speciale, ");
    output("e tu puoi percepire una presenza divina. `n");
    output("Dovresti offrire un sacrifici agli dei, per non offenderli.");
    output("`n`nCosa vuoi fare?");

    output("`n`n<a href='forest.php?op=Sacrifice&type=Yourself'>`\$Offri te stesso</a>`n<a href='forest.php?op=Sacrifice&type=Creature&Difficulty=Strong'>`%Offri un mostro potente</a>`n<a href='forest.php?op=Sacrifice&type=Creature&Difficulty=Moderate'>`%Offri un mostro forte</a>".($session['user']['level']!=1?"`n<a href='forest.php?op=Sacrifice&type=Creature&Difficulty=Weak'>`%Offri un mostro debole</a>":"")."`n<a href='forest.php?op=Sacrifice&type=Flowers'>`^Offri Fiori</a>`n`n<a href='forest.php?op=Leave'>`@Lascia l'altare</a>",true);
    addnav("T?`\$Offri Te Stesso","forest.php?op=Sacrifice&type=Yourself");
    addnav("P?`%Offri un Mostro Possente","forest.php?op=Sacrifice&type=Creature&Difficulty=Strong");
    addnav("F?`%Offri un Mostro Forte","forest.php?op=Sacrifice&type=Creature&Difficulty=Moderate");
    if ($session['user']['level'] != 1) addnav("D?`%Offri un Mostro Debole","forest.php?op=Sacrifice&type=Creature&Difficulty=Weak");
    addnav("O?`^Offri Fiori","forest.php?op=Sacrifice&type=Flowers");
    addnav("L?`n`@Lascia l'altare","forest.php?op=Leave");
    addnav("","forest.php?op=Sacrifice&type=Yourself");
    addnav("","forest.php?op=Sacrifice&type=Creature&Difficulty=Strong");
    addnav("","forest.php?op=Sacrifice&type=Creature&Difficulty=Moderate");
if ($session['user']['level'] != 1)    addnav("","forest.php?op=Sacrifice&type=Creature&Difficulty=Weak");
    addnav("","forest.php?op=Sacrifice&type=Flowers");

    addnav("","forest.php?op=Leave");
    $session['user']['specialinc']=$specialbat;
}elseif ($_GET['op']=="Sacrifice"){
        if ($session['user'][charisma]>0){
            if ($session['user'][marriedto]==4294967295){
                $amore=($session['user']['sex']?Seth:Violet);
            }else{
                $sql ="SELECT name FROM accounts WHERE acctid = {$session['user'][marriedto]}";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $amore=$row['name'];
                }
            }else $amore="lontano";

        if ($_GET['type']=="Yourself"){
        output("`@Posi le tue cose per terra e ti sdrai sull'altare. Alzando la tua ".$session['user']['weapon'].", ");
        output("pensi al tuo amore `%$amore`@. Quindi, senza ulteriore indugio, ");
        output("conficchi la tua ".$session['user']['weapon']." nel tuo cuore. Mentre l'oscurità ti avvolge, ");
        switch(e_rand(1,15)){
        case 1:
        case 2:
        case 3:
            output("pensi che il tuo gesto abbia appagato gli Dei per rendere il mondo ");
            output("un posto migliore...`n`n");
            output("Sfortunatamente, tu non ci sarai per vederlo. Sei morto!");
            output("`n`n`^Sei morto!`n");
            output("Perdi tutto il tuo oro!`n");
            output("Perdi il 5% della tua esperienza.`n");
            output("Potrai continuare a combattere domani.");
            debuglog("muore, perde 5% exp e {$session['user']['gold']} all'altare dei sacrifici");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']*=0.95;
            $session['user']['gold'] = 0;
            addnav("Notizie Giornaliere","news.php");
            addnews($session['user']['name']." è stato trovat".($session['user']['sex']?"a":"o").", mort".($session['user']['sex']?"a":"o").", su un altare nella foresta.");
            break;
        case 4:
        case 5:
            output("il cielo inizia ad arrossarsi per la rabbia degli Dei. Non sono così creduloni come avevi pensato. Gli Dei ");
            output("sanno cosa ti aspettavi. Nessuno è talmente idiota da sacrificare se stesso senza pensare ");
            output("che otterrà qualche favore in cambio di ciò. Una sfera fiammeggiante scende dal cielo e trasforma ");
            output("il tuo corpo in cenere, portando con se alcune delle tue abilità di attacco e difesa.  Bene, ");
            output("questo è tutto ciò che hai ottenuto cercando di imbrogliare gli Dei.");
            output("`n`n`^Sei morto!`n");
            output("Hai perso tutto il tuo oro!`n");
            output("Perdi il 10% della tua esperienza!`n");
            output("Potrai continuare a giocare domani.");
            debuglog("muore, perde 10% exp e {$session['user']['gold']} oro all'altare dei sacrifici");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']*=0.9;
            $session['user']['gold'] = 0;
            if ($session['user']['attack'] > 1){
                output("Perdi 1 punto di Attacco Permanente (se ne hai)!`n");
                $session['user']['attack']--;
                if($session['user']['bonusattack']>0) $session['user']['bonusattack']--;
                }
            if ($session['user']['defence'] > 1){
                output("Perdi 1 punto di Difesa Permanente (se ne hai)!`n");
                $session['user']['defence']--;
                if($session['user']['bonusdefence']>0) $session['user']['bonusdefence']--;
                }
            addnav("`6Notizie Giornaliere","news.php");
            addnews($session['user']['name']." è stat".($session['user']['sex']?"a":"o")." trovat".($session['user']['sex']?"a":"o")." carbonizzat".($session['user']['sex']?"a":"o")." sopra un altare.");
            break;
        case 6:
        case 7:
        case 8:
        case 9:
            output("vedi pulsare una luce diffusa, che lentamente prende forma e assomiglia alla figura di un vecchio.`n`n");
            output("\"`#Mio amato figliolo ,\"`@ dice, \"`#hai compiuto l'ultimo, estremo ");
            output("sacrificio per me, e per questo, Io ti ricompenserò.`@\"`n`n");
            output("Egli alza la sua mano e la scorre lungo tutto il tuo corpo, senza mai appoggiarla.");
            output("Senti una specie di calore attraversarti e tutto inizia ad essere più chiaro. Ti alzi, ");
            output("e noti che la ferita della ".$session['user']['weapon']." è stata completamente guarita. Cerchi allora ");
            output("il vecchio, ma sembra essere svanito nel nulla.`n`n");
            output("Raccogli le tue cose e ti prepari a partire. Camminando di fianco ad una pozza d'acqua, noti ");
            output("il tuo riflesso. Sembri più ".($session['user']['sex']?bella:bello))." di prima ";
            output("Deve essere stato il dono che gli Dei ti hanno fatto.`n`n");
            output("`^Guadagni 5 punti fascino!");
            $session['user']['charm']+=5;
            debuglog("guadagna 5 punti di fascino all'altare dei sacrifici");
            break;
        case 10:
        case 11:
        case 12:
        case 13:
            output("vedi pulsare una luce diffusa, che lentamente prende forma e assomiglia alla figura di un vecchio.`n`n");
            output("\"`#Mio amato figliolo ,\"`@ dice, \"`#hai compiuto l'ultimo, estremo ");
            output("sacrificio per me, e per questo Io ti ricompenserò.`@\"`n`n");
            output("Egli alza la sua mano e la scorre lungo tutto il tuo corpo, senza mai appoggiarla.");
            output("Senti una specie di calore attraversarti e tutto inizia ad essere più chiaro. Ti alzi, ");
            output("e noti cha la ferita della ".$session['user']['weapon']." è stata completamente guarita. Cerchi allora ");
            output("il vecchio, ma sembra essere svanito nel nulla.`n`n");
            output("Mentre ti allontani dalla radura, ti accorgi che che il tuo vigore è aumentato un po'.");
            $reward=intval($session['user']['maxhitpoints'] * 0.03);
            if ($reward == 0) $reward=1;
            if ($reward > 5) $reward=5;
            output("`n`n`^Il massimo dei tuoi punti vita sono stati `bpermanentemente`b aumentati di $reward punti!");
            debuglog("guadagna $reward HP Permanenti all'altare dei sacrifici");
            $session['user']['maxhitpoints']+=$reward;
            break;
        case 14:
        case 15:
            output("vedi pulsare una luce diffusa, che lentamente prende forma e assomiglia alla figura di un vecchio.`n`n");
            output("\"`#Mio amato figliolo ,\"`@ dice, \"`#hai compiuto l'ultimo, estremo ");
            output("sacrificio per me, e per questo Io ti ricompenserò.`@\"`n`n");
            output("Egli alza la sua mano e la scorre lungo tutto il tuo corpo, senza mai appoggiarla. ");
            output("Senti una specie di calore attraversarti e tutto inizia ad essere più chiaro. Ti alzi, ");
            output("e noti cha la ferita della ".$session['user']['weapon']." è stata completamente guarita. Cerchi allora ");
            output("il vecchio, ma sembra essere svanito nel nulla.`n`n");
            output("Mentre ti allontani dalla radura, ti sembra che i tuoi muscoli si siano ingrossati.");
            output("`n`n`^Guadagni 1 punto Attacco e 1 punto Difesa `i`bPermanenti`i`b!");
            $session['user']['attack']++;
            $session['user']['defence']++;
            $session['user']['bonusattack']++;
            $session['user']['bonusdefence']++;
            break;
        }

    }elseif ($_GET['type']=="Creature"){
    $session['user']['clean'] += 1;
    output("Decidendo di sacrificare una sfortunata creatura agli Dei, ti dirigi nella foresta cercando un'offerta adatta.`n");
    $session['user']['turns']--;
              $battle=true;
            if (e_rand(0,2)==1){
                $plev = (e_rand(1,5)==1?1:0);
                $nlev = (e_rand(1,3)==1?1:0);
            }else{
              $plev=0;
                $nlev=0;
            }

            if ($Difficulty=="Weak"){
              $nlev++;
                output("`\$Vai nella parte di foresta dove sai di trovare avversari facili da battere.`0`n");
            }

            if ($Difficulty=="Strong"){
              $plev++;
                output("`\$Ti dirigi nella parte di foresta dove ci sono le creature dei tuoi incubi, sperando di trovarne uno ferito.`0`n");
            }
            $targetlevel = ($session['user']['level'] + $plev - $nlev );
            if ($targetlevel<1) $targetlevel=1;
            $sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $badguy = db_fetch_assoc($result);
            $expflux = round($badguy['creatureexp']/10,0);
            $expflux = e_rand(-$expflux,$expflux);
            $badguy['creatureexp']+=$expflux;

            //make badguys get harder as you advance in dragon kills.
            //output("`#Debug: badguy gets `%$dk`# dk points, `%+$atkflux`# attack, `%+$defflux`# defense, +`%$hpflux`# hitpoints.`n");
            $badguy['playerstarthp']=$session['user']['hitpoints'];
            $dk = 0;

            while(list($key, $val)=each($session['user']['dragonpoints'])) {
                if ($val=="at" || $val=="de") $dk++;
            }

            $dk += (int)(($session['user']['maxhitpoints']-
                ($session['user']['level']*10))/5);
            if (!$beta) $dk = round($dk * 0.25, 0);
            else $dk = round($dk,0);

            $atkflux = e_rand(0, $dk);
            if ($beta) $atkflux = min($atkflux, round($dk/4));
            $defflux = e_rand(0, ($dk-$atkflux));
            if ($beta) $defflux = min($defflux, round($dk/4));
            $hpflux = ($dk - ($atkflux+$defflux)) * 5;
            $badguy['creatureattack']+=$atkflux;
            $badguy['creaturedefense']+=$defflux;
            $badguy['creaturehealth']+=$hpflux;
            if ($beta) {
                $badguy['creaturedefense']*=0.66;
                $badguy['creaturegold']*=(1+(.05*$dk));
                if ($session['user']['race']==4) $badguy['creaturegold']*=1.1;
            } else {
                if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;
            }
            $badguy['diddamage']=0;
            $session['user']['badguy']=createstring($badguy);
            if ($beta) {
                if ($session['user']['superuser']>=3){
                    output("Debug: $dk dragon points.`n");
                    output("Debug: +$atkflux attack.`n");
                    output("Debug: +$defflux defense.`n");
                    output("Debug: +$hpflux health.`n");
                        $session['user']['specialinc']="sacrificealter2.php";
                }
            }
    }elseif ($_GET['type']=="Flowers"){
        if (!$_GET['flower']){
            $session['user']['turns']--;
            output("`@Ti avventuri nella foresta cercando dei fiori selvatici e finalmente, ti imbatti in una zona con molte specie differenti. Ci sono`$ Rose`@, `&Margherite`@, e `^Denti di Leone`@.`n Quale vuoi offrire?");
            output("`n`n<a href='forest.php?op=Sacrifice&type=Flowers&flower=Roses'>`\$Offri le Rose</a>`n<a href='forest.php?op=Sacrifice&type=Flowers&flower=Daisies'>`&Offri le Margherite</a>`n<a href='forest.php?op=Sacrifice&type=Flowers&flower=Dandelions'>`^Offri i Denti di Leone</a>`n`n<a href='forest.php?op='>`@Ritorna all'Altare</a>",true);
            addnav("R?`\$Offri le Rose","forest.php?op=Sacrifice&type=Flowers&flower=Roses");
            addnav("M?`&Offri le Margherite","forest.php?op=Sacrifice&type=Flowers&flower=Daisies");
            addnav("D?`^Offri i Denti di Leone","forest.php?op=Sacrifice&type=Flowers&flower=Dandelions");
            addnav("A?`n`@Ritorna all'Altare","forest.php?op=");
            addnav("","forest.php?op=Sacrifice&type=Flowers&flower=Roses");
            addnav("","forest.php?op=Sacrifice&type=Flowers&flower=Daisies");
            addnav("","forest.php?op=Sacrifice&type=Flowers&flower=Dandelions");
            addnav("","forest.php?op=");
            $session['user']['specialinc']=$specialbat;
        }else{
            if ($_GET['flower']=="Roses"){
                output("`@Posi le rose sull'altare come sacrificio. reclini il capo per pregare gli Dei, chiedendo Loro ");
                output("di accettare il tuo sacrificio. Rialzando la testa per guardare l'altare, ");
                switch(e_rand(1,12)){
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                        output("vedi un `^Coniglio Rabbioso`@! Non avrai veramente pensato che gli Dei, che hanno un altare intriso ");
                        output("di sangue accettassero un sacrificio di fiori? Veramente, che genere di idiota potrebbe essere così ");
                        output("stupido da fare ciò? Ora, stai per andare incontro alla morte, che ti aspetta con grandi e affilate ");
                        output("zanne appuntite!");
                        output("`n`n`^Sei stato maciullato dal `\$Coniglio Rabbioso`^!`n");
                        output("Perdi tutto il tuo oro!`n");
                        output("Perdi il 10% della tua esperienza!");
                        output("Potrai continuare a giocare domani.");
                        debuglog("muore e perde {$session['user']['gold']} oro e 10% exp all'altare dei sacrifici");
                        $session['user']['alive']=false;
                        $session['user']['hitpoints']=0;
                        $session['user']['experience']*=0.9;
                        $session['user']['gold'] = 0;
                        addnav("`^Notizie Giornaliere","news.php");
                        addnews($session['user']['name']." è stato trovat".($session['user']['sex']?"a":"o")."... martoriat".($session['user']['sex']?"a":"o")." da un coniglio!");
                        break;
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                        output("vedi una donna bellissima di fianco a te.`n`n");
                        output(" dice, \"`# Ti ringrazio per queste rose. So ");
                        output("che hai avuto una vita difficile, così ho deciso di farti un dono.`@\"`n`n");
                        output("Ella appoggia la sua mano sulla tua testa, e senti un calore avvolgere il tuo corpo. Quando leva ");
                        output("la sua mano dalla tua testa, ti dice di guardare nella pozza d'acqua a lato dell'altare. Cammini ");
                        output("fino alla pozza e la osservi. Ti accorgi di essere più ".($session['user']['sex']?bella:bello));
                        output(" di prima. Ti giri verso l'altare per scoprire che la Dea è svanita nel nulla. Qual'era il suo nome?");
                        output("`n`n`^Guadagno 5 punti di fascino!");
                        debuglog("guadagna 5 punti fascino all'altare dei sacrifici");
                        $session['user']['charm']+=5;
                        break;
                    case 9:
                    case 10:
                    case 11:
                    case 12:
                        output("vedi una donna bellissima di fianco a te.`n`n");
                        output(" dice, \"`# Ti ringrazio per queste rose. So ");
                        output("che hai avuto una vita difficile, così ho deciso di farti un dono.`@\"`n`n");
                        output("Ti dice di guardare nella pozza d'acqua a lato dell'altare. Cammini ");
                        output("fino alla pozza e la osservi. Trovi delle gemme nella pozza! Ti giri verso l'altare per scoprire che la Dea ");
                        output("è svanita nel nulla. Come si chiamava?");
                        output("`n`n`^Hai trovato `%DUE`^ gemme!");
                        debuglog("guadagna 2 gemme all'altare dei sacrifici");
                        $session['user']['gems']+=2;
                        break;

                }
            }
            elseif ($_GET['flower']=="Daisies"){
                output("`@Posi le margherite sull'altare come sacrificio. Reclini il capo per pregare gli Dei, chiedendo ");
                output("loro di accettare questo sacrificio. Alzando lo sguardo per osservare l'altare, ");
                switch(e_rand(1,12)){
                    case 1:
                    case 2:
                    case 3:
                        output("noti che le margherite si sono trasformate in una `\^Gigantesca Pianta Carnivora`@, e non sembra si accontenti di mosche. ");
                        output("Prima che tu riesca a scappare, o estrarre la tua arma, la pianta ti ha inghiottito nella sua bocca. Stai per essere ");
                        output("digerito lentamente per un periodo di 100 anni. Ripensa ai tuoi errori, hai tutto il tempo necessario.");
                        output("`n`n`^Sei stato mangiato da una `\$Gigantesca Pianta Carnivora`^!`n");
                        output("Perdi tutto il tuo oro!`n");
                        output("Perdi il 10% della tua esperienza!");
                        output("Potrai continuare a giocare domani.");
                        debuglog("muore e perde {$session['user']['gold']} oro e 10% exp all'altare dei sacrifici");
                        $session['user']['alive']=false;
                        $session['user']['hitpoints']=0;
                        $session['user']['experience']*=0.9;
                        $session['user']['gold'] = 0;
                        addnav("`^Notizie Giornaliere","news.php");
                        addnews($session['user']['name']." ha perso l'arma di fianco ad una enorme pianta, ma questo è tutto ciò che si sa al riguardo di ".($session['user']['sex']?lei:lui).".");
                        break;
                    case 4:
                    case 5:
                    case 6:
                        output("vedi una ragazza seduta sull'altare con in mano le margherite.`n`n");
                        output("\"`#M'ama, non m'ama. Mi ama, non mi ama,`@\" dice staccando i petali ");
                        output("del tuo dono per gli Dei.`n`n");
                        output("La osservi con stupore mentre stacca l'ultimo petalo.`n`n");

                        if (e_rand(0,1)==0){
                            output("\"`#Non mi ama. COSA?!`@\" grida iniziando a piangere. Scende dall'altare e corre ");
                            output("via nella foresta, sfiorandoti mentre scappa. Senti che il suo dolore ti ha reso meno ");
                            output("affascinante.`n`n");
                            output("`^Perdi un punto fascino!");
                            debuglog("perde 1 punto fascino all'altare dei sacrifici");
                            $session['user']['charm']--;
                        }else{
                            output("\"`#Mi ama. Oh, si! Mi ama, mi ama!`@\" continua a ripetere saltando in uno stato di pura di gioia. ");
                            output("Scende dall'altare e corre verso la foresta, sfiorandoti mentre scappa. Senti che la sua gioia ");
                            output("ti ha contagiato, rendendoti più affascinante.`n`n");
                            output("`^Guadagni 1 punto fascino!");
                            debuglog("guadagna 1 punto fascino all'altare dei sacrifici");
                            $session['user']['charm']++;
                        }
                        break;
                    case 7:
                    case 8:
                    case 9:
                    case 10:
                    case 11:
                    case 12:
                        $reward=e_rand($session['user']['experience']*0.025+10, $session['user']['experience']*0.1+10);
                        output("vedi una donna bellissima di fianco a te.`n`n");
                        output("\"`#".($session['user']['sex']?Figlia:Figlio)." mi".($session['user']['sex']?a:o).",`@\" dice, \"`#Ti ringrazio per questo bouquet di margherite. ");
                        output("So che hai avuto una vita travagliata, ed ecco un regalo per te.`@\"`n`n");
                        output("Ti da quello che sembra un pezzo di pane dolce, e ti dice di mangiarlo. Per non offenderla, ");
                        output("inizi ad addentare il pezzo di pane, lo mastichi e lo ingoi. Ad un tratto, percepisci una maggior conoscenza ");
                        output("pervadere la tua mente. Chiudi gli occhi per un secondo, e quando li riapri, la Dea è scomparsa. ");
                        output("Come si chiamava?");
                        output("`n`n`^Guadagni $reward punti esperienza!`n");
                        debuglog("guadagna $reward exp all'altare dei sacrifici");
                        $session['user']['experience']+=$reward;
                        break;
                    }

                }elseif ($_GET['flower']=="Dandelions"){
                output("`@Posi i denti di leone sull'altare in segno di sacrificio. Reclini il capo per pregare gli Dei, chiedendo ");
                output("loro di accettere questo sacrificio. Rialzi lo sguardo per osservare l'altare, e ");
                switch(e_rand(1,5)){
                    case 1:
                    case 2:
                        output("vedi una Dea che osserva con disapprovazione la tua offerta. Di colpo si gira verso di te sprizzando fiamme. ");
                        output("Ti si avvicina piena di rabbia!");
                        output("`n`n\"`#A `iERBACCIA`i!! Offri dell'`ierbaccia`i alle tue Divinità! Attento! Non meriti di vivere!`@\"");
                        output("dice, e quindi lancia delle sfere di fuoco contro di te.`n`n");
                        output("La prima ti attraversa completamente, incenerendo il tuo torace, lasciandoti già morto con braccia, gambe,");
                        output("e testa. Mentre la tua testa cade al suolo e rotola via, la Dea la ferma con il piede, la raccoglie, e ");
                        output("guarda nei tuoi occhi. `n`n");
                        output("\"`#Bene, ".$session['user']['name'].", penso tu abbia imparato a non insultare mai più gli Dei, vero?`@\"");
                        output("`n`nMentre il tuo spirito fluttua verso l'Ade, pensi \"`&Si sbagliano, ma credo non contino ");
                        output("le intenzioni...`@\"`n`n");
                        output("`^Sei morto!`n");
                        output("Perdi tutto il tuo oro!`n");
                        output("La lezione di vita che hai appreso oggi compensa l'esperienza che avresti perso.");
                        debuglog("muore e perde {$session['user']['gold']} oro all'altare dei sacrifici");
                        $session['user']['alive']=false;
                        $session['user']['hitpoints']=0;
                        $session['user']['gold'] = 0;
                        addnav("`^Notizie Giornaliere","news.php");
                        addnews($session['user']['name'].", la sua testa è stata trovata... infilzata su una lancia nei pressi di un altare.");
                        break;
                    case 3:
                    case 4:
                    case 5:
                        output("vedi il tuo sacrificio andarsene in fiamme. Il fuoco avviluppa i denti di leone. Quando sono ridotti in cenere, ti avvicini ");
                        output("e spazzi via le braci.");
                        switch(e_rand(1,3)){
                            case 1:
                                output("`iNon è rimasto nulla!`i Gli Dei devono aver rifiutato il tuo sacrificio. Le tue mani sono tutte ");
                                output("martoriate per aver raccolto le bocche di leone. Oh beh, erano comunque erbacce...");
                                break;
                            case 2:
                            case 3:
                                output("`iTrovi una gemma!`i Gli Dei devono aver accettato il tuo sacrificio. Le tue mani sono tutte ");
                                output("martoriate per aver raccolto le bocche di leone, ma ne è valsa la pena!");
                                output("`n`n`^ Trovi `%UNA`^ gemma!");
                                $session['user']['gems']+=1;
                                debuglog("trova 1 gemma all'altare dei sacrifici");
                                break;
                        }
                }
            }
        }
    }
}elseif ($_GET['op']=="Leave"){
  output("`#Questo è un luogo sacro, per Dei e religiosi, meglio che torni sui tuoi passi prima di far infuriare gli Dei ");
  output("soffermandoti vicino al loro sacro altare.");
}elseif ($_GET['op']=="won"){
    $badguy = createarray($session['user']['badguy']);
    if ($_GET['difficulty']=="Strong") $dif="Strong";
    if ($_GET['difficulty']=="Moderate") $dif="Moderate";
    if ($_GET['difficulty']=="Weak") $dif="Weak";
    output("`@Trascini la tua preda, un enorme `^".$badguy['creaturename']."`@, fino all'altare di pietra. Depositi la sua carcassa sopra ");
    output("l'altare, e fai sgorgare il sangue sacrificale. quando hai terminato, ");
    switch(e_rand(1,15)){
        case 1:
        case 2:
            if ($dif=="Weak")$deltaexp=5;
            if ($dif=="Moderate")$deltaexp=10;
            if ($dif=="Strong")$deltaexp=15;
            output("`i il `^".$badguy['creaturename']."`@ torna in vita!`i Adesso ha zanne e artigli, e sembra affamato. Peccato tu lo abbia già ");
            output("ucciso, perchò non puoi uccidere qualcosa che è già morto. Avresti dovuto sapere che gli Dei ");
            output("non avrebbero accettato un sacrificio di questo genere. Quello sull'altare era sangue `iUMANO`i.`n`n Gli Dei vogliono sangue, e ");
            output("lo otterrano da te, che ti piaccia o no.");
            output("`n`n`^Sei morto!`n");
            output("Gli Dei apprezzano anche lo splendente metallo giallo, e ti sottraggono tutto l'oro!`n");
            output("Perdi il $deltaexp% della tua esperienza.`n");
            output("Potrai continuare a giocare domani.");
            debuglog("muore e perde $deltaexp% exp e ".$session['user']['gold']." oro all'altare dei sacrifici");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            if ($dif=="Weak")$session['user']['experience']*=0.95;
            if ($dif=="Moderate")$session['user']['experience']*=0.9;
            if ($dif=="Strong")$session['user']['experience']*=0.85;
            $session['user']['gold'] = 0;
            addnav("`^Notizie Giornaliere","news.php");
            addnews($session['user']['name']." è mort".($session['user']['sex']?"a":"o")." e i suoi resti erano in condizioni pietose quando sono stati trovati...");
        break;
        case 3:
        case 4:
        case 5:
            if ($dif=="Weak"){
                $reward = 1;
                $rewardnum="UNA gemma";
            }
            if ($dif=="Moderate"){
                $reward = 2;
                $rewardnum="DUE gemme";
            }
            if ($dif=="Strong"){
                $reward = 3;
                $rewardnum="TRE gemme";
            }
            output("bisbigli una preghiera per lo spirito di `^".$badguy['creaturename']."`@. Ti giri e ti inginocchi per lavarti le mani in una ");
            output("piccola pozza d'acqua. Quando hai terminato, ti rialzi e ti giri verso l'altare. `iIl `^".$badguy['creaturename']."`@ è ");
            output("sparito!`i Al suo posto c'è una sacca. Ti avvicini e la apri. Dentro trovi $rewardnum! Gli Dei ");
            output("devono aver accettato il tuo sacrificio, e ti hanno ricompensato per i tuoi servigi.");
            output("`n`n`^Trovi `%".$rewardnum."`^!`n");
            debuglog("guadagna $rewardnum all'altare dei sacrifici");
            //by Excalibur
            $session['user']['specialinc']="";
            $session['user']['gems'] +=$reward;
            break;
        case 6:
        case 7:
        case 8:
            if ($dif=="Weak"){
                $reward = e_rand(300, 500);
                $bag="un borsellino.";
            }
            if ($dif=="Moderate"){
                $reward = e_rand(500, 800);
                $bag="una borsa.";
            }
            if ($dif=="Strong"){
                $reward = e_rand(800, 1500);
                $bag="una valigia.";
            }
            output("bisbigli una preghiera per lo spirito di  `^".$badguy['creaturename']."`@. Ti giri e ti inginocchi per lavarti le mani in una ");
            output("piccola pozza d'acqua. Quando hai terminato, ti rialzi e ti giri verso l'altare. `iIl `^".$badguy['creaturename']."`@ è ");
            output("sparito!`i Al suo posto c'è $bag Ti avvicini e l'apri. Dentro trovi $reward pezzi d'oro! Gli Dei ");
            output("devono aver apprezzato il tuo sacrificio, e ti hanno ricompensato per i tuoi servigi.");
            output("`n`n`^Trovi $reward pezzi d'oro!`n");
            $session['user']['gold'] += $reward;
            debuglog("guadagna $reward oro all'altare dei sacrifici");
            //by Excalibur
            $session['user']['specialinc']="";
            break;
        case 9:
        case 10:
        case 11:
        case 12:
            if ($dif=="Weak")$reward = 4;
            if ($dif=="Moderate")$reward = 5;
            if ($dif=="Strong")$reward = 6;
            output("incroci le mani sulla carcassa per pregare, ma quando le tue mani toccano la carne del corpo ");
            output("morto del ".$badguy['creaturename'].", senti una nuova energia salire dalle braccia in tutto il tuo corpo. La tua debolezza è svanita, e ");
            output("la tua stanchezza cancellata. Gli Dei ti hanno ridato forza per altri $reward combattimenti nella foresta!");
            output("`n`n`^Guadagni $reward combattimenti extra!!");
            debuglog("guadagna $reward combattimenti all'altare dei sacrifici");
            $session['user']['turns']+=$reward;
            //By Excalibur
            $session['user']['specialinc']="";
            break;
        case 13:
        case 14:
            if ($dif=="Weak")$charmloss = 1;
            if ($dif=="Moderate")$charmloss = 2;
            if ($dif=="Strong")$charmloss = 3;
            output("la carcassa che inizia a gonfiarsi come se fosse riempita d'aria! Diventa sempre più grande. Sei troppo stupito per muoverti. ");
            output("Finalmente il corpo del `^".$badguy['creaturename']."`@ esplode, coprendoti di sangue e brandelli. Il sacrificio non deve essere stato sufficiente, ");
            output("e sei stato punito per questo.");
            output("`n`n`^Perdi ".$charmloss." punti fascino!");
            debuglog("perde $charmloss punti fascino all'altare dei sacrifici");
            $session['user']['charm']-=$charmloss;
            if ($session['user']['charm']<0) $session['user']['charm']=0;
            //By Excalibur
            $session['user']['specialinc']="";
            break;
        case 15:
            if ($dif=="Weak")$deltaexp=5;
            if ($dif=="Moderate")$deltaexp=10;
            if ($dif=="Strong")$deltaexp=15;
            output("`\$Il cielo vira verso il rosso. `@Temi di aver innervosito gli Dei, e ti giri per andartene. Mentre sei in procinto di ");
            output("muovere il primo passo e lasciare questo luogo, un fulmine cade dal cielo e si abbatte su di te. Vieni scagliato indietro, e ");
            output("quando colpisci il suolo, vieni impalato dalla tua `%".$session['user']['weapon']."`@. Non è bello prendersi gioco degli Dei");
            output("con un'offerta così misera, e lo hai scoperto nel peggiore dei modi.");
            output("`n`n`^Sei morto!`n");
            output("Hai perso tutto il tuo oro!`n");
            output("Hai perso il $deltaexp% della tua esperienza!`n");
            output("Potrai continuare a giocare domani.");
            debuglog("muore e perde $deltaexp% exp e {$session['user']['gold']} oro all'altare dei sacrifici");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            if ($dif=="Weak")$session['user']['experience']*=0.9;
            if ($dif=="Moderate")$session['user']['experience']*=0.85;
            if ($dif=="Strong")$session['user']['experience']*=0.8;
            $session['user']['gold'] = 0;
            addnav("`^Notizie Giornaliere","news.php");
            addnews("Il corpo carbonizzato e contorto di ".$session['user']['name']." è stato trovato impalato da ".$session['user']['weapon'].".");
            break;
    }
}
if ($_GET['op']=="run"){

    if (e_rand()%3 == 0){
        output ("`c`b`&Sei riuscito a fuggire dal tuo avversario!`0`b`c`n");
        $_GET['op']="";
        output("Ti sei comportato da codardo, ed inoltre, hai dimenticato dove si trova l'altare. Probabilmente non sacrificherai ");
        output("nulla, mai, in nessun luogo. Ricordati, tutto ciò per colpa tua.");
    }else{
        output("`c`b`\$Non sei riuscito a seminare il tuo avversario!`0`b`c");
    }
}

if ($_GET['op']=="fight" || $_GET['op']=="run"){
    $battle=true;
}
if ($battle){
  require("battle.php");
    if ($victory){
    $badguy = createarray($session['user']['badguy']);
    if (getsetting("dropmingold",0)){
            $badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);
        }else{
            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);
        }
        $expbonus = round(
            ($badguy[creatureexp] *
                (1 + .25 *
                    ($badguy[creaturelevel]-$session['user']['level'])
                )
            ) - $badguy[creatureexp],0
        );
        output("`b`&".$badguy['creaturelose']."`0`b`n");
        output("`b`\$Hai ucciso ".$badguy['creaturname']."!`0`b`n");
        output("`#Ricevi `^".$badguy['creaturegold']."`# pezzi d'oro!`n");
        if ($badguy['creaturegold']) {
            debuglog("riceve da ".$badguy['creaturegold']." pezzi d'oro per l'uccisione del mostro.");
        }
        if (e_rand(1,25) == 1) {
          output("`&Trovi una GEMMA!`n`#");
          $session['user']['gems']++;
          debuglog("trova una gemma quando ha ucciso il mostro da sacrificare all'altare.");
        }
        if ($expbonus>0){
          output("`#***A causa della difficoltà di questo combattimento, ricevi in aggiunta `^$expbonus`# punti esperienza! `n($badguy[creatureexp] + ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");
          $dif="Strong";
        }else if ($expbonus<0){
          output("`#***A causa della semplicità di questo combattimento, sei penalizzato di `^".abs($expbonus)."`# punti esperienza! `n($badguy[creatureexp] - ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");
          $dif="Weak";
        } else  $dif="Moderate";
        output("Ricevi `^".($badguy[creatureexp]+$expbonus)."`# punti totali d'esperienza!`n`0");
        $session['user']['gold']+=$badguy['creaturegold'];
        $session['user']['experience']+=($badguy[creatureexp]+$expbonus);
        $creaturelevel = $badguy[creaturelevel];
        $_GET['op']="";
        //if ($session['user']['hitpoints'] == $session['user']['maxhitpoints']){
        if ($badguy['diddamage']!=1){
            if ($session['user']['level']>=getsetting("lowslumlevel",4) || $session['user']['level']<=$creaturelevel){
                output("`b`c`&~~ Combattimento Perfetto! ~~`\$`n`bRicevi un turno extra!`c`0`n");
                $session['user']['turns']++;
            }else{
                output("`b`c`&~~ Combattimento Perfetto! ~~`b`\$`nUn combattimento più difficile ti avrebbe ricompensato con un turno extra.`c`n`0");
            }
        }
        $dontdisplayforestmessage=true;
        addhistory(($badguy['playerstarthp']-$session['user']['hitpoints'])/max($session['user']['maxhitpoints'],$badguy['playerstarthp']));
        $badguyname=$badguy['creaturename'];
        $badguy=array();
//    Add victory possiblilities below:
        //Modifica by Excalibur per togliere '`´ dal nome della creatura (causa problemi con alcuni browser)
        $vowels = array("'", "`", "´","’");
        $onlyconsonants = str_replace($vowels, "", $badguy['creaturename']);
        $badguy['creaturename'] = $onlyconsonants;
        //Fine modifica
        addnav("`@Ritorna all'Altare","forest.php?op=won&difficulty=$dif&badguyname=".$badguy['creaturename']);
        $session['user']['specialinc']=$specialbat;
//    End of Victory Possibilities,
    }elseif ($defeat){
        $badguy = createarray($session['user']['badguy']);
        addnav("`^Notizie Giornaliere","news.php");
        $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $taunt = db_fetch_assoc($result);
        $taunt = str_replace("%s",($session['user']['sex']?"her":"him"),$taunt['taunt']);
        $taunt = str_replace("%o",($session['user']['sex']?"lei":"lui"),$taunt);
        $taunt = str_replace("%p",($session['user']['sex']?"suo":"suo"),$taunt);
        $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
        $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
        $taunt = str_replace("%W",$badguy['creaturname'],$taunt);
        $taunt = str_replace("%w",$session['user']['name'],$taunt);
        addhistory(1);
        addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." nella foresta da ".$badguy['creaturname']."`n$taunt");
        $session['user']['alive']=false;
        debuglog("persi {$session['user']['gold']} pezzi d'oro quando ucciso nella foresta");
        $session['user']['gold']=0;
        $session['user']['hitpoints']=0;
        $session['user']['experience']=round($session['user']['experience']*.9,0);
        $session['user']['badguy']="";
        output("`b`&Sei stato massacrato da `%".$badguy['creaturname']."`&!!!`n");
        output("`4Hai perso tutto l'oro che avevi con te!`n");
        output("`4Hai perso il 10% della tua esperienza!`n");
        output("Potrai continuare a giocare domani.");

        page_footer();
    }else{
        $session['user']['specialinc']=$specialbat;
        $session['user']['badguy']=createstring($badguy);
        fightnav(true,false);
    }
}

?>