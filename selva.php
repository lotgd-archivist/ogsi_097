<?php
/*****************************************/
/* La selva oscura                       */
/* ---------------                       */
/* Written by Excalibur for www.ogsi.it  */
/*     excalthesword@fastwebnet.it       */
/*****************************************/


require_once "common.php";
checkday();
if (!isset($session)) exit();
page_header("La Selva Oscura");
$session['user']['locazione'] = 171;
$sex = $session['user']['sex'];
if ($session['user']['quest']>2 || $session['user']['turns']<1) {
    page_header("La Selva Oscura");
    $_GET['op']="ferma";
    output("`3Sei troppo stanco per affrontare altre avventure oggi. Riprova un altro giorno.");
    addnav("Torna al Villaggio","village.php");
}

else if ($_GET['op']==""){
page_header("La Selva Oscura");
output("`3Oggi è una giornata speciale, ti sei deciso ad esplorare la zona ad est di Castel Excalibur che avevi sempre ");
output("`3evitato ... le voci che circolano al villaggio sulla `5Selva Oscura`3 non ti invogliavano di certo ad addentrarti ");
output("`3in quella che viene comunemente definita `4La Zona di Non Ritorno`3.`nMa oggi hai finalmente trovato il coraggio ");
output("`3di superare tutte le tue paure e ti sei convinto che tutte le voci al riguardo della `5Selva Oscura`3 sono state ");
output("`3diffuse da quelle donnicciole che vivono al villaggio e che osano farsi chiamare Guerrieri. `n`n");
output("`3Mentre ti inoltri nella landa a te sconosciuta, noti che i rami degli alberi si fanno sempre più fitti, e che la ");
output("`3luce del sole fa sempre più fatica ad penetrare il folto fogliame. Il coraggio sta per abbandonarti, non sei mai ");
output("stato un amante dell'oscurità, e quando stai per voltarti per tornare sui tuoi passi, noti delle luci che si muovono ");
output("poco più avanti.`nSpinto dalla curiosità ti inoltri ulteriormente nella fitta foresta e quando sei in prossimità delle ");
output("luci scopri con sorpresa che sono delle piccole fate che svolazzano intorno ad una casetta costruita in mezzo a questa ");
output("selva. `n`n Cosa vuoi fare ?");
addnav("Scappa","selva.php?op=scappa");
addnav("Osserva Meglio","selva.php?op=osserva");
addnav("Cattura una Fata","selva.php?op=cattura");
addnav("Vai alla Casa","selva.php?op=casa");
addnav("Torna al Villaggio","village.php");
}

else if ($_GET['op']=="scappa"){
page_header("La Fuga del Fifone");
output("`3Le fate ti hanno sempre messo i brividi, e quest'occasione non è differente dalle altre. `nDecidi di non sfidare ");
output("la tua buona stella, ti giri e strisciando come un verme ti allontani ripercorrendo la strada che conduce al villaggio.`n");
addnav("Torna al Villaggio","village.php");
}

else if ($_GET['op']=="osserva"){
page_header("L'Osservatore");
output("`3Ti acquatti dietro un cespuglio e sdraiandoti sul terreno osservi con calma la strana danza delle fate.`n");
output("Quella che ad un'osservazione distratta sembra un'agitazione senza senso inizia ad assumere un significato. ");
output("E' una danza propiziatoria delle fate per ingraziarsi lo `6Spirito della Foresta`3, e tu sei il fortunato spettatore.`n`n");
output("Percepisci la magia che si sta creando, è palpabile nell'aria, e forse potresti approfittare della situazione. ");
output("Sai che potrebbe essere rischioso se le fate ti scoprissero a spiare il loro rituale, forse la nomea della foresta ");
output("è opera loro e sei ancora in tempo a tornare al villaggio, ma la prospettiva di trovare qualcosa di prezioso è allettante.`n`n Cosa vuoi fare ?`n");
addnav("Torna al Villaggio","village.php");
addnav("Osserva il Rituale","selva.php?op=rituale");
}

else if ($_GET['op']=="rituale"){
    page_header("Il Rituale delle Fate");
    $session['user']['quest']+=1;
    switch(e_rand(1,8)){
    case 1:
    case 2:
    output("`3Dopo un paio d'ore di osservazione, giungi alla conclusione che quello che sembrava un rituale magico altro non è che ");
    output("una danza senza alcun significato magico. Quelle dannate fate ti hanno fatto sprecare una buona occasione per fare ");
    output("esperienza nella foresta uccidendo qualche creatura. Decidi quindi di tornartene al villaggio.`n");
    $session['user']['turns']--;
    debuglog("perde 1 combattimento nella selva");
    addnav("Torna al Villaggio","village.php");
    break;
    case 3:
    case 4:
    output("`3Dopo qualche minuto di osservazione ti accorgi che la danza delle fate si fa più frenetica e che una strana nebbiolina ");
    output("si sta formando al centro della formazione. Ti sembra di scorgere il contorno di una figura in mezzo alla nebbia, e dopo qualche ");
    output("istante si rivela la figura di un".($sex?" uomo bellissimo":"a donna bellissima")." che ti viene incontro e ti dice: ");
    output("\"`2Mi".($sex?"a cara":"o caro")." `6".($session['user']['name'])."`2, sono stupit".($sex?"o":"a")." di vederti qui, ");
    output("questo rituale non è riservato ai mortali, ma oramai quello che è fatto è fatto, e non si può più cambiare. Ti chiedo però di ");
    output("abbandonare questo luogo `6immediatamente`2, perchè altrimenti rischieresti la morte. L'aver assistito alla danza delle fate ti ha ");
    output("portato dei benefici, ma non indugiare oltre, torna al tuo villaggio e non far parola con nessuno di ciò che hai visto.\"`3 ");
    output("Le parole dell".($sex?"'uomo":"a donna")." sono come ipnotiche, e seguendo il suo consiglio te ne torni al villaggio.`n`n");
    $lifebonus=intval(e_rand(5,20)*$session['user']['maxhitpoints']/1000);
    if ($lifebonus==0)$lifebonus=5;
    $charmbonus=e_rand(2,4);
    $gembonus=e_rand(1,2);
    $session['user']['maxhitpoints']+=$lifebonus;
    $session['user']['hitpoints']+=$lifebonus;
    $session['user']['charm']+=$charmbonus;
    $session['user']['gems']+=$gembonus;
    output("`3Hai guadagnato`6`b ". $lifebonus ."`b `3HP `5PERMANENTI!!!`n");
    output("`3Hai guadagnato`6`b ". $charmbonus ."`b `3Punti Fascino!!!`n");
    output("`3Ti ritrovi con`6`b ". $gembonus ."`b `3gemme in più in tasca!!!`n");
    addnav("Torna al Villaggio","village.php");
    debuglog("guadagna $lifebonus HP permanenti, $charmbonus punti fascino e $gembonus gemme nella selva");
    break;
    case 5:
    case 6:
    output("`3Dopo qualche minuto di osservazione ti accorgi che la danza delle fate si fa più frenetica e che una strana nebbiolina ");
    output("si sta formando al centro della formazione. Ti sembra di scorgere il contorno di una figura in mezzo alla nebbia, e dopo qualche ");
    output("istante si rivela la figura di un".($sex?" uomo bellissimo":"a donna bellissima")." che ti viene incontro e ti dice: ");
    output("\"`2Mi".($sex?"a cara":"o caro")." `6".($session['user']['name'])."`2, sono stupit".($sex?"o":"a")." di vederti qui, ");
    output("questo rituale non è riservato ai mortali, ma oramai quello che è fatto è fatto, e non si può più cambiare. Ti chiedo però di ");
    output("abbandonare questo luogo `6immediatamente`2, perchè altrimenti rischieresti la morte. L'aver assistito alla danza delle fate ti ha ");
    output("portato dei vantaggi, ma non indugiare oltre, torna al tuo villaggio e non far parola con nessuno di ciò che hai visto.\"`3 ");
    output("Le parole dell".($sex?"'uomo":"a donna")." sono come ipnotiche, e seguendo il suo consiglio te ne torni al villaggio.`n`n");
    $lifebonus=intval(e_rand(5,20)*$session['user']['maxhitpoints']/1000);
    $goldbonus=e_rand(100,200)*$session['user']['level'];
    $gembonus=e_rand(1,2);
    if ($lifebonus==0)$lifebonus=5;
    $session['user']['maxhitpoints']+=$lifebonus;
    $session['user']['hitpoints']+=$lifebonus;
    $session['user']['gold']+=$goldbonus;
    $session['user']['gems']+=$gembonus;
    output("`3Hai guadagnato`6`b ". $lifebonus ."`b `3HP `5PERMANENTI!!!`n");
    output("`3Hai guadagnato`6`b ". $goldbonus ."`b `3pezzi d'oro!!!`n");
    output("`3Ti ritrovi con`6`b ". $gembonus ."`b `3gemme in più in tasca!!!`n");
    debuglog("guadagna $lifebonus HP permanenti, $goldbonus oro e $gembonus gemme nella selva");
    addnav("Torna al Villaggio","village.php");
    break;
    case 7:
    output("`3Dopo qualche minuto di osservazione ti accorgi che la danza delle fate si fa più frenetica e che una strana nebbiolina ");
    output("si sta formando al centro della formazione. Ti sembra di scorgere il contorno di una figura in mezzo alla nebbia, e dopo qualche ");
    output("istante si rivela la figura di un essere mostruoso che tra gorgoglii e suoni terribili si dirige verso di te ti dice: ");
    output("\"`6".($session['user']['name'])."`5, chomme hai oshato dishturbghare il gnoshtro rithugle ? Adhesho paggherhai per la thua sfhrontatessha!!`3\"");
    output("Detto questo emette un suono gutturale e dalla bocca esce un fetore impressionante che ti tramortisce e ti lascia svenuto per un tempo indefinito.");
    output("Al tuo risveglio ti trovi nei pressi del villaggio. Le forze ti hanno abbandonato quasi completamente, e scopri di aver perso tutto l'oro che ");
    output("avevi con te. Non è stata una buona idea quella di importunare le fate.`n`n");
    debuglog("perde {$session['user']['gold']} oro nella selva");
    $session['user']['hitpoints']=5;
    $session['user']['turns']=0;
    $session['user']['gold']=0;
    output("`4Hai perso tutto il tuo oro!!!`n");
    output("Sei rimasto con soli 5 HP!!!`n");
    output("Non ti resta neanche un combattimento a disposizione`n");
    addnav("Torna al Villaggio","village.php");
    break;
    case 8:
    output("`3Dopo qualche minuto di osservazione ti accorgi che la danza delle fate si fa più frenetica e che una strana nebbiolina ");
    output("si sta formando al centro della formazione. Ti sembra di scorgere il contorno di una figura in mezzo alla nebbia, e dopo qualche ");
    output("istante si rivela la figura di un".($sex?" uomo bellissimo":"a donna bellissima")." che ti viene incontro e ti dice: ");
    output("\"`2Mi".($sex?"a amata":"o amato")." `6".($session['user']['name'])."`2, ho atteso tanto tempo la tua venuta. ");
    output("Il mio amore per te ha reso possibile la mia reincarnazione, e adesso potrò finalmente dedicarmi a te, amore mio!\"`3.`n Le sue parole ");
    output("sono ipnotiche, e docile come un agnellino l".($sex?"o":"a")." segui all'interno della casa, dove un letto a forma di cuore fa bella mostra. ");
    output("Vi adagiate sul letto, e ".($sex?"lui":"lei")." ti spoglia dolcemente e ti massaggia delicatamente. Senti crescere il tuo desiderio ");
    output("e ti lasci trasportare dall'eccitazione. Dopo ore di passione scivoli in un sonno profondo, e al tuo risveglio ti ritrovi solo.`n Un biglietto al tuo ");
    output("fianco ti fa intuire che ".($sex?"lui":"lei")." sia sparit".($sex?"o":"a")." per sempre dalla tua vita. Con una nota di ");
    output("dolore nel cuore leggi il biglietto che recita:\"`6Il tuo amore ha spezzato l'incantesimo che mi teneva prigioner".($sex?"o":"a").", sono ");
    output("finalmente liber".($sex?"o":"a")." ma non posso rimanere al tuo fianco, ho altri compiti da svolgere in questo mondo. Per ricompensarti ti ho ");
    output("lasciato alcuni doni, so che non potranno compensarti, ma spero possano servirti per non dimenticarmi.\"`3`n`n");
    $lifebonus=intval(e_rand(10,30)*$session['user']['maxhitpoints']/1000);
    if ($lifebonus==0)$lifebonus=5;
    $charmbonus=intval($session['user']['charm']*0.1);
    if ($charmbonus==0)$charmbonus=1;
    $goldbonus=e_rand(200,400)*$session['user']['level'];
    $gembonus=e_rand(3,5);
    $session['user']['maxhitpoints']+=$lifebonus;
    $session['user']['hitpoints']+=$lifebonus;
    $session['user']['gold']+=$goldbonus;
    $session['user']['gems']+=$gembonus;
    $session['user']['charm']+=$charmbonus;
    output("`3Hai guadagnato`6`b ". $lifebonus ." `b`3HP `5PERMANENTI!!!`n");
    output("`3Hai guadagnato`6`b ". $goldbonus ." `b`3pezzi d'oro!!!`n");
    output("`3Ti ritrovi con`6`b ". $gembonus ." `b`3gemme in più in tasca!!!`n");
    output("`3Hai guadagnato`6`b ". $charmbonus ." `b`3punti di fascino!!!`n");
    debuglog("guadagna $lifebonus HP permanenti, $goldbonus oro, $gembonus gemme e $charmbonus punti fascino nella selva");
    addnav("Torna al Villaggio","village.php");
    break;
    }
}

else if ($_GET['op']=="cattura"){
    page_header("La Cattura della Fata");
if ($session['user']['quest']>1) {
    page_header("La Selva Oscura");
    $_GET['op']="stoppi";
    redirect("selva.php?op=stop");
    output("`3Sei troppo stanco per affrontare altre avventure oggi. Riprova un altro giorno.");
    addnav("Torna al Villaggio","village.php");
}
    output("`3Decidi di tentare la cattura di una delle fate danzanti. Ti prepari a gettarti in mezzo al gruppo dei piccoli esserini ");
    output("per catturarne uno, il cuore batte furiosamente per l'eccitazione ed estrai dallo zaino un barattolo di vetro dove riporrai la preda. `n");
    output("Salti fuori dal tuo nascondiglio urlando ed agitandoti come un forsennato nel tentativo di cogliere di sorpresa le fate ");
    output("e la tua strategia si rivela vincente. Richiudi il barattolo con al suo interno una piccola fata che si agita inutilmente ");
    output("nel tentativo di liberarsi dalla prigionia, e ti allontani velocemente. Raggiunta una piccola radura ti fermi ad osservare ");
    output("la tua preda, che si rivolge a te con voce soave:\"`5Liberami nobile Cavaliere, hai interrotto un rituale molto importante ");
    output("per la sopravvivenza della foresta e del tuo stesso villaggio. Le conseguenze se non fosse portato a termine potrebbero essere ");
    output("catastrofiche`3\".`n Sai che il valore della piccola fata al mercato nero del villaggio è molto alto, ma l'implorazione del ");
    output("piccolo essere ha incrinato la dura scorza del guerriero. Cosa vuoi fare ?`n`n");
    addnav("Libera la Fata","selva.php?op=libera");
    addnav("Vendila","selva.php?op=vendila");
    }

else if ($_GET['op']=="libera"){
    page_header("La Liberazione della Fata");
    $session['user']['quest']+=2;
    output("`@Impietosito dalle suppliche della fata decidi di liberarla, pensando tristemente al grosso quantitativo di gemme che prende ");
    output("il volo assieme a lei, ma felice di aver ridato la libertà ad un essere tanto grazioso e che assieme alle sue compagne ha tanto ");
    output("a cuore il destino della foresta. `nMentre rimani seduto assorto nei tuoi pensieri, noti un movimento con la coda dell'occhio. ");
    output("Alzi lo sguardo e vedi una decina di fate che si posano sul terreno davanti a te. Ognuna di esse ha con se una gemma!! La fata ");
    output("che hai liberato è tornata con alcune sue compagne e ti hanno portato una gemma in dono per il nobile gesto che hai compiuto.`n`n");
    $gembonus=e_rand(4,6);
    $goldbonus=e_rand(300,600)*$session['user']['level'];
    $session['user']['gold']+=$goldbonus;
    $session['user']['gems']+=$gembonus;
    output("`3Hai guadagnato`6`b ". $goldbonus ." `b`3pezzi d'oro!!!`n");
    output("`3Ti ritrovi con`6`b ". $gembonus ." `b`3gemme in più in tasca!!!`n");
    debuglog("guadagna $goldbonus oro e $gembonus gemme nella selva");
    addnav("Torna al Villaggio","village.php");
}

else if ($_GET['op']=="vendila"){
    page_header("Il Mercenario vende la Fata");
    $session['user']['quest']+=2;
    output("`3Nonostante tutto sei un guerriero, ed un guerriero non si lascia impietosire dalle suppliche di una fata. `nDecidi di tornare al ");
    output("villaggio per venderla a Brax, le proprietà magiche degli oggetti che vende pare siano dovute alla polvere di fata che Brax sparge ");
    output("su di essi recitando formule magiche. `n");
    switch(e_rand(1,5)) {
    case 1:
    $gembonus=e_rand(8,12);
    $charmbonus=e_rand(10,20);
    output("Giungi finalmente all'Emporio Magico di Brax, e dopo una lunga contrattazione concordate il prezzo della fata: `b`6$gembonus gemme`b`3.`n");
    output("Ma nell'animo sai di non aver fatto la cosa giusta, e il tuo aspetto esteriore ne risente. Perdi `b`^$charmbonus punti di fascino`3`b.`n`n");
    $session['user']['gems']+=$gembonus;
    $session['user']['charm']-=$charmbonus;
    if ($session['user']['charm']<$charmbonus)  $session['user']['charm']=0;
    debuglog("guadagna $gembonus vendendo la fata, ma perde $charmbonus nella selva");
    addnav("Torna al Villaggio","village.php");
    break;
    case 2:
    case 3:
    case 4:
    case 5:
    output("`3Intraprendi il viaggio di ritorno ma hai la sensazione che qualcosa non vada per il verso giusto. Dalle tue spalle giunge ");
    output("un ronzio sordo che cresce di volume. Ti rendi conto che proviene dal tuo zaino, dove hai messo il barattolo con la fata. ");
    output("Te lo sfili dalle spalle, estrai il barattolo e vedi la fata che agita ad una velocità incredibile le piccole ali. Il ronzio ");
    output("cresce ancora di intensità e l'ultima cosa che vedi è il ghigno della fata che, guardantoti dritto negli occhi, imprime un'ultima ");
    output("accelerazione al battito delle sue ali. `nIn quel momento il barattolo di vetro esplode in miliardi di frammenti che penetrano ");
    output("in profondità nelle tue carni, bucando il tuo cuore che cessa di battere.`n`n `4`bSei MORTO!!!`b`n");
    output("`5Hai perso tutto il tuo oro!!!`n");
    output("`@Hai perso il 10% della tua esperienza!!!`n");
    if ($session['user']['gems']>0){
        $session['user']['gems']-=1;
        debuglog("muore e perde {$session['user']['gold']} oro, 10% exp e 1 gemma nella selva");
        output("`1La fata prima di tornare dalle sue compagne fruga nelle tue tasche e ti ruba 1 gemma!!!`n");
    }
    debuglog("muore e perde {$session['user']['gold']} oro e 10% exp nella selva");
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    $session['user']['gold']=0;
    $session['user']['experience']*=0.9;
    addnav("Terra delle Ombre","shades.php");
    break;
    }
}
else if ($_GET['op']=="casa"){
page_header("La Casa");
if ($session['user']['quest']>1) {
    page_header("La Selva Oscura");
    $_GET['op']="stoppi";
    redirect("selva.php?op=stop");
}
    if ($_GET['op1']==""){
    output("`3Ti avvicini alla casa evitando di farti scorgere dalle fate, che assorte nella loro danza non ti notano. Apri con ");
    output("cautela la porta e ti intrufoli all'interno. ");
    }
output("`3Noti immediatamente il grande disordine che regna nella stanza. Avanzi ");
output("di cibo, ossa (umane ?) sono sparse ogni dove e la tua spavalderia sparisce immediatamente. Una botola aperta sul ");
output("pavimento sembra scendere verso l'inferno, e senti un sommesso russare proveniente dal sottosuolo. `nCosa fai?`n");
addnav("Scendi in Cantina","selva.php?op=labirinto");
addnav("Scappa !!!","selva.php?op=run");
}

else if ($_GET['op']=="run" || $_GET['op']=="fight"){
page_header("La Fata Sgraziata");
if ($_GET['op']=="run"){

//Setup della Fata Sgraziata
                        $badguy = array("creaturename"=>"`@La Fata Sgraziata`0"
                        ,"creaturelevel"=>13
                        ,"creatureweapon"=>"Bacchetta dell'Orrore"
                        ,"creatureattack"=>40
                        ,"creaturedefense"=>35
                        ,"creaturehealth"=>300
                        , "diddamage"=>0);
    $points = 0;
    while(list($key,$val)=each($session['user']['dragonpoints'])){
        if ($val=="at" || $val == "de") $points++;
    }
    // Now, add points for hitpoint buffs that have been done by the dragon
    // or by potions!
    $points += (int)(($session['user']['maxhitpoints'] - 150)/5);

    // Okay.. *now* buff the dragon a bit.
    if ($beta)
        $points = round($points*1.5,0);
    else
        $points = round($points*.75,0);

    $atkflux = e_rand(0, $points);
    $defflux = e_rand(0,$points-$atkflux);
    $hpflux = ($points - ($atkflux+$defflux)) * 7;
    $badguy['creatureattack']+=$atkflux;
    $badguy['creaturedefense']+=$defflux;
    $badguy['creaturehealth']+=$hpflux;
    $session['user']['badguy']=createstring($badguy);

output("`2Decidi che non è il caso di indugiare ulteriormente ed esci di fretta dalla casa. Appena fuori noti non orrore ");
output("che le deliziose creature sono sparite e al loro posto c'è un'enorme fata, se così si può chiamare quell'essere ");
output("sgraziato ed mostruoso, che ti sta aspettando roteando la sua bacchetta. `n");
output("Non puoi sottrarti allo scontro, la fata ti sbarra la strada, estrai allora la tua ".$session['user']['weapon']." e ti prepari ");
output("al combattimento. `n");
}
$battle=true;
if ($battle) {
   include_once("battle.php");
   $_GET['op']="fight";
        if($victory) {
                if ($badguy['diddamage'] != 1) $flawless = 1;
                $session['user']['quest']+=2;
                $_GET['op']="vittoria";
                output("`1Hai sconfitto `^".$badguy['creaturename'].".`n");
                addnews("`%".$session['user']['name']."`5 ha sconfitto `^".$badguy['creaturename']." `%nella Selva Oscura.");
                $expbonus=intval($session['user']['experience']*0.2);
                $gembonus=e_rand(5,15);
                $goldbonus=e_rand(400,800)*$session['user']['level'];
                output("`1Il tesoro che la `^".$badguy['creaturename']." `1custodiva è immenso!!`n`n");
                output("`1Di fianco al suo cadavere trovi `6`b$gembonus gemme`b`1 e `6`b$goldbonus pezzi d'oro`b`1!!!`n");
                output("`1Guadagni anche `6`b$expbonus`b`1 punti esperienza!!!`n");
                if ($flawless){
                    output("Per aver disputato un combattimento perfetto guadagni altre `65 gemme`1");
                    debuglog("guadagna 5 gemme x combattimento perfetto contro Fata Sgraziata nella selva");
                    $gembonus+=5;
                }
                debuglog("guadagna $gembonus gemme, $goldbonus oro e $expbonus exp uccidendo la Fata Sgraziata nella selva");
                $session['user']['experience']+=$expbonus;
                $session['user']['gold']+=$goldbonus;
                $session['user']['gems']+=$gembonus;
                $battle=false;
                $badguy=array();
                $session['user']['badguy']="";
                }
                else{
                if ($defeat) {
                        $_GET['op']="";
                        $session['user']['quest']+=2;
                        output("`nSei stato sconfitto da `^".$badguy['creaturename'].".`n");
                        addnews("`%".$session['user']['name']."`5 è stat".($sex?"a":"o")." uccis".($sex?"a":"o")." da `^".$badguy['creaturename']."`5 nella `2Selva Oscura.");
                        debuglog("perde {$session['user']['gold']} oro e 15% exp ucciso dalla Fata Sgraziata nella selva");
                        output("`2Perdi tutto l'oro che avevi con te!!!`n");
                        output("Perdi il 15% della tua esperienza!!!`n");
                        $session['user']['gold']=0;
                        $session['user']['experience']*=0.85;
                        $session['user']['hitpoints']=0;
                        $battle=false;
                        $badguy=array();
                        $session['user']['badguy']="";
                        addnav("Terra delle Ombre","shades.php");

                }
                else   {
                        fightnav(true,false);
                        }
                }
}
if ($_GET['op']=="vittoria" ) addnav("Torna al Villaggio","village.php");
}
else if ($_GET['op']=="scendisotto"){
page_header("La Cantina Infernale");
output("`3Arrivi infine in una specie di antro naturale dove l'aria pesante è impregnata di zolfo. Sul fondo intravedi ");
output("la sagoma di una creatura enorme, origine del russare che udivi da sopra. Ti avvicini ulteriormente e con ");
output("orrore scopri che è un `4Drago Rosso!`3. Cosa decidi di fare?");
addnav("Torna di sopra","selva.php?op=casa&op1=salita");
addnav("Attacca il Drago","selva1.php?op=drago&op1=fight2");
}
else if ($_GET['op']=="labirinto"){
page_header("Il Labirinto");
output("`3Inizi a scendere le scale scavate nel terreno, e ad ogni passo la temperatura si fa sempre più rovente.`n ");
switch(e_rand(1,6)){
case 1:
output("`3Il buio ti impedisce di vedere dove cammini, e dopo aver trascorso la giornata a girovagare nel dedalo di ");
output("gallerie ritrovi finalmente la scalinata che ti riconduce all'esterno, dove nel frattempo si è fatta sera. ");
output("`n`nHai perso tutti i tuoi turni foresta!!");
debuglog("perde tutti i combattimenti nel labirinto della selva");
$session['user']['turns']=0;
addnav("Torna al Villaggio","village.php");
break;
case 2:
output("`3Il buio ti impedisce di vedere dove appoggi il piedi, e non vedi la profonda spaccatura nel terreno. Il rumore ");
output("dell'osso della gamba che si frattura è l'ultima cosa che senti. Perdi conoscenza e quando ti svegli vedi ");
output("il viso familiare di Excalibur che ti sorregge e ti sta riportando al villaggio. `n");
if (round($session['user']['maxhitpoints']/$session['user']['level'])!=1){
    if ($session['user']['maxhitpoints']<=200){
        $vitapersa="1%";
        $lifelost=intval($session['user']['maxhitpoints']*0.01);
    }
    else if ($session['user']['maxhitpoints']>200 AND $session['user']['maxhitpoints']<=250){
        $vitapersa="3%";
        $lifelost=intval($session['user']['maxhitpoints']*0.03);
    }
    else if ($session['user']['maxhitpoints']>250 AND $session['user']['maxhitpoints']<=300){
        $vitapersa="4%";
        $lifelost=intval($session['user']['maxhitpoints']*0.04);
    }
    else if ($session['user']['maxhitpoints']>300 AND $session['user']['maxhitpoints']<=400){
        $vitapersa="7%";
        $lifelost=intval($session['user']['maxhitpoints']*0.07);
    }
    else if ($session['user']['maxhitpoints']>400 AND $session['user']['maxhitpoints']<=500){
        $vitapersa="9%";
        $lifelost=intval($session['user']['maxhitpoints']*0.09);
    }
    else if ($session['user']['maxhitpoints']>500) {
        $vitapersa="10%";
        $lifelost=intval($session['user']['maxhitpoints']*0.1);
    }
    $session['user']['maxhitpoints']-=$lifelost;
    output("Hai perso il $vitapersa dei tuoi HP, cioè `6".$lifelost." HP `i`bPermanenti`i`b`3!!`n");
    debuglog("perde $lifelost HP permanenti nel labirinto della selva");

}
output("Hai perso tutti i tuoi turni foresta!!`n");
if ($lifelost>$session['user']['hitpoints']) $lifelost=$session['user']['hitpoints']-1;
$session['user']['hitpoints']-=$lifelost;
$session['user']['turns']=0;
debuglog("perde tutti i combattimenti e $lifelost nel labirinto della selva");
addnav("Torna al Villaggio","village.php");
break;
case 3:
output("`3Il buio ti impedisce di vedere appoggi il piedi, e non vedi la profonda spaccatura nel terreno. `nIl rumore ");
output("del tuo cranio che si spacca contro la roccia è l'ultima cosa che senti. `nSei `b`4MORTO!!!`b`3`n");
output("Per tua fortuna nessuna creatura nel labirinto è interessata al tuo oro, che mantieni`n");
output("`5Hai perso il `3`b10%`5`b della tua esperienza!!!`n");
$session['user']['experience']*=0.9;
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
debuglog("muore e perde 10% exp nel labirinto della selva");
addnav("Terra delle Ombre","shades.php");
break;
case 4:
case 5:
case 6:
if ($session['user']['quest']>1) {
    page_header("La Selva Oscura");
    $_GET['op']="stop";
    output("`3Sei troppo stanco per affrontare altre avventure oggi. Riprova un altro giorno.");
    addnav("Torna al Villaggio","village.php");
}
$_GET['op']="scendisotto";
output("Prosegui nel dedalo di corridoi ed infine intravedi una luce in fondo alla galleria che stai percorrendo.`n");
addnav("Vai alla Luce","selva.php?op=scendisotto");
break;
}
}
else if($_GET['op']="stop"){
    output("`3Sei troppo stanco per affrontare altre avventure oggi. Riprova un altro giorno.");
    addnav("Torna al Villaggio","village.php");
}
page_footer();

?>