<?php
/* Random Green Dragon Encounter v1 by Timothy Drescher (Voratus)
Current version can be found at Domarr's Keep (lgd.tod-online.com)
This is a simple "forest special" which helps to keep the main idea in mind, by giving any player an
encounter with the Green Dragon, and the results could be deadly.
The following names/locations are server-specific and should be changed:
    Plains of Al'Khadar (and reference to "plains")
    Domarr's Keep (the main city)

Version History
1.0 original version
1.1 tranlated into italian and adapted by Excalibur (www.ogsi.it)
*/
if (!isset($session)) exit();
$session[user][specialinc]="randdragon.php";
if ($_GET['count']==3) {
    output("`@Il Drago Verde si è stufato delle tue lamentele. Ti colpisce con il suo alito infuocato!`n`n");
    output("E tu pensi a cosa sia peggio, il dolore che provi o il tanfo della carne bruciata. Comunque, ti ");
    output("rimane poco da vivere mentre l'oscurità ti avvolge.`n`nSei stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." dal Drago Verde!`n");
    output("Perdi il 10% della tua esperienza, e tutto l'oro che avevi con te.");
    addnews("`%".$session[user][name]."`5 è stat".($session[user][sex]?"a":"o")." ritrovat".($session[user][sex]?"a":"o")." carbonizzato vicino alla grotta del `@Drago Verde!");
    debuglog("muore nell'evento del Drago Verde e perde {$session[user][gold]} oro e 10% exp");
    $session['user']['gold']=0;
    $session[user][experience]=round($session[user][experience]*.9,0);
    $session['user']['alive']=false;
    $session['user']['hitpoints']=0;
    $session['user']['specialinc']="";
    addnav("`^Notizie Giornaliere","news.php");
} else {
    switch($_GET[op]){
        case "":
            output("`@Mentre stai camminando senza meta per la foresta, odi un tremendo ruggito. Il suono ti atterrisce ");
            output("e blocca ogni tuo movimento.`nPercepisci un respiro profondo alle tue spalle. Ancora immobile, percepisci ");
            output("calore infernale provenire da dietro di te. Ti giri lentamente e vedi un enorme Drago Verde di fronte ");
            output("a te.`n`nPensi di essere nei guai.");
            addnav("`\$Attacca il Drago!","forest.php?op=slay");
            addnav("`%Inginocchiati","forest.php?op=cower");
            addnav("`#Parla al Drago","forest.php?op=talk");
            addnav("`^Scappa! Scappa!","forest.php?op=flee");
            $session[user][specialinc]="randdragon.php";
            break;
        case "slay":
            output("`@Estrai fieramente la tua arma e ti prepari ad attaccarel'immonda bestia che ti si para davanti.");
            if ($session['user']['level'] < 15) {
                output("`n`nLanci un grido di battaglia e attacchi il drago!`nPrima che la tua arma giunga a bersaglio, il ");
                output("drago te la strappa di mano con la sua coda.`nQuindi lancia una sfera di fuoco ");
                output("dalle sua fauci fiammeggianti!");
                if (rand(1,4)==1) {
                    output("`n`nLa sfera ti getta a terra. Puoi sentire la tua pelle ribollirea causa dell'intenso ");
                    output("calore a cui è sottoposta.`nDebolmente, rialzi lo sguardo per osservare il Drago Verde ");
                    output("che incombe su di te. Si accuccia sul terreno con il suo enorme ventre vicino a te, quando ");
                    output("all'improvviso una freccia solitaria si conficca sul fianco della mostruosa testa del drago.`n");
                    output("Con un ruggito tremendo, il mostro si dilegua nella foresta.`nGiro lo sguardo per vedere un Elfo ");
                    output("che ti si avvicina, quindi svieni.`n`nTi risvegli poco più tardi in una radura. Le tue ustioni ");
                    output("sono state curate, ma nulla può guarire completamente i danni causati dal respiro di un drago.");
                    output("`nPerdi 2 punti fascino per le ustioni!");
                    addnews("`%".$session[user][name]."`5 è riuscit".($session[user][sex]?"a":"o")." a sopravvivere in qualche modo all'incontro con il Drago Verde.");
                    $session['user']['charm']-=2;
                    $session['user']['turns']-=2;
                    debuglog("perde 2 punti fascino e 2 turni nell'incontro con il Drago Verde");
                    if ($session['user']['turns'] < 0) $session['user']['turns']=0;
                    $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                    $session[user][specialinc]="";
                } else {
                    output("`n`nLa sfera ti getta a terra. Puoi sentire la tua pelle ribollirea causa dell'intenso ");
                    output("calore a cui è sottoposta.`nDebolmente, rialzi lo sguardo per osservare il Drago Verde ");
                    output("che incombe su di te.`nÈ anche l'ultima cosa che vedi prima di essere avvolto dall'oscurità.`n`n");
                    output("Sei stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." dal Drago Verde!");
                    output("Hai perso il 10% della tua esperienza, e tutto l'oro che avevi con te.");
                    addnews("`%".$session[user][name]."`5 è stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." nella foresta dal `@Drago Verde!");
                    debuglog("è ucciso dal Drago Verde e perde {$session[user][gold]} oro e il 10% di exp");
                    $session['user']['gold']=0;
                    $session[user][experience]=round($session[user][experience]*.9,0);
                    $session['user']['alive']=false;
                    $session['user']['hitpoints']=0;
                    $session['user']['specialinc']="";
                    addnav("`^Notizie Giornaliere","news.php");
                }
            } else {
                output("`n`nLanci un grido di battaglia e attacchi il drago!`nPrima che la tua arma giunga a bersaglio, il ");
                output("drago te la strappa di mano con la sua coda.`nQuindi lancia una sfera di fuoco dalle sue fauci ");
                output("nell tua direzione!`nRiesci ad evitarla in qualche modo all'ultimo momento, ");
                output("solo per trovarti faccia a faccia con l'immensa bestia. Poi deridendoti dice: \"`5Non qui.");
                output("`5 Non ora.`@\"`nDetto ciò, la creatura si allontana, scomparendo alla vista. Ti ritrovi solo con i tuoi ");
                output("pensieri e la tua umiltà.");
                $session['user']['specialinc']="";
            }
            break;
        case "cower":
            output("`@Ti inginocchi di fronte al Drago Verde, mendicando per la tua misera vita. `nIl drago sbuffa, emettendo ");
            output("un altro getto di vapori bollenti. `n\"`%Un essere che striscia come te sarebbe sicuramente indigesto.");
            output(" Vattene!`@\"`nDecidi che seguire il consiglio della creatura è il miglior piano attualmente,`n");
            output("perciò ti involi nella foresta, spaventato a morte. `nMorte che hai evitato per un soffio.");
            addnews("`%".$session[user][name]."`5 ha strisciato davanti al `@Drago Verde`5, implorandolo di non essere il suo pasto.");
            $session['user']['charm']--;
            $session['user']['specialinc']="";
            break;
        case "talk":
            output("`@Ti rendi conto che se riesci a porre la sfida sul piano dell'intelletto e della conversazione, forse ");
            output("avrai una possibilità di sopravvivere a quest'incontro. Ora, quello di cui hai bisogno è qualche argomento di conversazione.`n");
            addnav("Argomenti di Conversazione");
            addnav("T?Il Tempo","forest.php?op=weather&count=0");
            addnav("D?Il Drago Verde","forest.php?op=dragon&count=0");
            addnav("Violet","forest.php?op=violet&count=0");
            addnav("Seth","forest.php?op=seth&count=0");
            addnav("Cedrik","forest.php?op=cedrik&count=0");
            addnav("x?Castel Excalibur","forest.php?op=city&count=0");
            addnav("Balbetta","forest.php?op=stutter&count=0");
            break;
        case "weather":
            $count=$_GET['count'];
            $count++;
            output("`@\"`5Che bella giornata, vero?`@\"`nIl drago scuote la testa osservandoti. Un breve sbuffo ");
            output("di vapore bollente esce dalle sue fauci.`n`nForse un altro argomento interessa maggiormente il ");
            output("drago.");
            addnav("Argomenti di Conversazione");
            addnav("D?Il Drago Verde","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("x?Castel Excalibur","forest.php?op=city&count={$count}");
            addnav("Balbetta","forest.php?op=stutter&count={$count}");
            break;
        case "dragon":
            $count=$_GET['count'];
            $count++;
            output("`@\"`5Ah, tu saresti il Drago Verde, huh?`@\"`nIl drago emette un ruggito spacca-orecchie e quindi ");
            output("si lecca le mandibole. `nForse scegliere un altro argomento sarebbe consigliabile.");
            addnav("Argomenti di Conversazione");
            addnav("T?Il Tempo","forest.php?op=weather&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("x?Castel Excalibur","forest.php?op=city&count={$count}");
            addnav("Balbetta","forest.php?op=stutter&count={$count}");
            break;
        case "violet":
            $count=$_GET['count'];
            $count++;
            output("`@\"`5Quella Violet è veramente carina, eh?`@\"`nIl drago annuisce. \"`Sarebbe un delizioso bocconcino ");
            output("quello. È un peccato che non esca mai dalla taverna. Ma tu potresti saziare la mia fame per il momento.`@\"`n");
            output("Meglio pensare a qualcosa d'altro, in fretta.");
            addnav("Argomenti di Conversazione");
            addnav("T?Il Tempo","forest.php?op=weather&count={$count}");
            addnav("D?Il Drago Verde","forest.php?op=dragon&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("x?Castel Excalibur","forest.php?op=city&count={$count}");
            addnav("Balbetta","forest.php?op=stutter&count={$count}");
            break;
        case "seth":
            $count=$_GET['count'];
            $count++;
            output("`@\"`5Seth è veramente un bell'uomo, non credi?`@\"`nIl drago scuote la testa pensieroso.`n");
            output("\"`5Un po' duro da digerire, ci scommetto, ma non abbandona mai la taverna. Tu, invece ...");
            output("`@\"`nIl drago ti squadra affamato. È tempo di cambiare argomento!");
            addnav("Argomenti di Conversazione");
            addnav("T?Il Tempo","forest.php?op=weather&count={$count}");
            addnav("D?Il Drago Verde","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("x?Castel Excalibur","forest.php?op=city&count={$count}");
            addnav("Balbetta","forest.php?op=stutter&count={$count}");
            break;
        case "cedrik":
            $count=$_GET['count'];
            $count++;
            output("`@\"`5Cedrik è di sicuro un tipo tosto, non sei d'accordo?`@\"`nIl drago sbatte lentamente le palpebre.");
            output("\"`5Quel mortale non mi interessa. Tu sembri decisamente più tener".($session[user][sex]?"a":"o").".`@\"`nNon serve essere ");
            output("telepatico per sapere cosa sta pensando, e sai che devi sviare la sua attenzione, ed in fretta.");
            addnav("Argomenti di Conversazione");
            addnav("T?Il Tempo","forest.php?op=weather&count={$count}");
            addnav("D?Il Drago Verde","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("x?Castel Excalibur","forest.php?op=city&count={$count}");
            addnav("Balbetta","forest.php?op=stutter&count={$count}");
            break;
        case "city":
            $count=$_GET['count'];
            $count++;
            output("`@\"`5Castel Excalibur è veramente impressionante!`@\"`nIl drago sbuffa rumorosamente.`n\"`5Quel ");
            output("castello è una spina nel fianco e niente più! Distruggerò le sue fragili mura e lo ");
            output("raderò al suolo! `nTutti conosceranno il mio nome, ed avranno paura di me!`@\"`nBene, sei sicuramente ");
            output("riuscit".($session[user][sex]?"a":"o")." ad irritare la creatura adesso! Forse un cambio di argomento potrebbe aiutarti.");
            addnav("Argomenti di Conversazione");
            addnav("T?Il Tempo","forest.php?op=weather&count={$count}");
            addnav("D?Il Drago Verde","forest.php?op=dragon&count={$count}");
            addnav("Violet","forest.php?op=violet&count={$count}");
            addnav("Seth","forest.php?op=seth&count={$count}");
            addnav("Cedrik","forest.php?op=cedrik&count={$count}");
            addnav("Quale Nome?","forest.php?op=name&count={$count}");
            addnav("Balbetta","forest.php?op=stutter&count={$count}");
            break;
        case "stutter":
            $count=$_GET['count'];
            $count++;
            output("`@Nel tentativo di tirar fuori qualcosa di intelligente e spiritoso da dire, inizi a balbettare in maniera ");
            output("incontrollabile. Il drago ti osserva e ruota gli occhi drammaticamente. Colpisce la tua testa ");
            output("con la sua coda, rendendoti incosciente.`n`nTi risvegli poco dopo, con ");
            output("un grande livido sulla tempia.`n");
            if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']*.1) {
                $session['user']['hitpoints']=$session['user']['maxhitpoints']*.1;
            } else {
                $session['user']['hitpoints']=1;
            }
            $session['user']['turns']-=2;
            if ($session['user']['turns'] < 0) $session['user']['turns']=0;
            addnews("`%".$session[user][name]."`5 è riuscit".($session[user][sex]?"a":"o")." in qualche maniera a sopravvivere ad un incontro con il `@Drago Verde!");
            debuglog("si è salvato da un incontro con il Drago Verde, perdendo molti HP e 2 turni");
            $session['user']['specialinc']="";
            break;
        case "name":
            output("`@\"`5Qual'è il tuo nome, oh possente drago?`@\"`nIl drago ti osserva curioso. \"`5Non saresti ");
            output("in grado di pronunciarlo. Pochi sono in grado di farlo in questo reame ormai, poichè richiede ");
            output("le abilità vocali di un drago a tutti gli effetti, e non ne sono rimasti molti. La nostra grande ");
            output("razza è stata decimata dalle razze inferiori, terrorizzati dall'idea che potessimo distruggerli.`@\"`n");
            output("Il drago distoglie lo sguardo per un attimo, quindi si volge nuovamente a te. \"`5I draghi uccidevano solo per ");
            output("nutrirsi. Ora uccidiamo per sopravvivere.`@\"`n`n\"`5Vattene prima che decida di ucciderti senza ragione.`@\"");
            output("`nTrovandola una buona idea, decidi di allontanarti prima che il drago cambi idea e ti usi come spuntino.");
            addnews("`%".$session[user][name]."`5 è riuscit".($session[user][sex]?"a":"o")." a sopravvivere ad un incontro con il `@Drago Verde!");
            debuglog("è sopravvissuto all'incontro con il Drago Verde");
            $session['user']['specialinc']="";
            break;
        case "flee":
            $results=rand(1,4);
            if ($results==1) {
                output("`@Ti giri per scappare dal possente Drago Verde. Pensi di essere un buon centometrista, ");
                output("e non odi nessun rumore dietro di te.`nTi fermi per guardare indietro e scopri che il drago è scomparso!`n");
                output("Wow! Che fortuna!");
                addnews("`%".$session[user][name]."`5 è riuscit".($session[user][sex]?"a":"o")." a sopravvivere ad un incontro con il `@Drago Verde!");
                $session['user']['specialinc']="";
            } elseif ($results==4) {
                output("`@Ti giri per scappare dal possente Drago Verde. Pensi di essere un buon centometrista, ");
                output("e non odi nessun rumore dietro di te.`nTi fermi per guardare indietro e scopri che il drago è scomparso!`n");
                output("Ritenendoti fortunato, ti rigiri solo per scoprire che il Drago Verde è proprio davanti a te, ");
                output("con le sue enormi fauci aperte che cercano te.`nPrima che tu possa muovere un solo muscolo del tuo corpo, ");
                output("il drago ti ha divorat".($session[user][sex]?"a":"o").".");
                output("Sei mort".($session[user][sex]?"a":"o")."!`nPerdi il 10% della tua esperienza e tutto l'oro che avevi con te.");
                addnews("`%".$session[user][name]."`5 è stat".($session[user][sex]?"a":"o")." divorat".($session[user][sex]?"a":"o")." nella foresta dal `@Drago Verde!`n`3Di lui resta solo un mucchietto di bianche ossa ...");
                $session['user']['gold']=0;
                $session[user][experience]=round($session[user][experience]*.9,0);
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['specialinc']="";
                addnav("`^Notizie Giornaliere","news.php");
            } else {
                output("`@Ti giri per scappare dal possente Drago Verde. Mentre corri, senti un'improvvisa ondata ");
                output("di calore raggiungerti. `nIl drago ti ha scagliato contro una fiammata dalle sue fauci!`n");
                $damage=round($session['user']['maxhitpoints']*.9,0);
                output("Subisci $damage punti di danno a causa della fiammata del drago!");
                $session['user']['hitpoints']-=$damage;
                if ($session['user']['hitpoints'] < 1) {
                    $session['user']['hitpoints']=0;
                    output("Sei mort".($session[user][sex]?"a":"o")."!`nPerdi il 10% della tua esperienza e tutto l'oro che avevi con te!");
                    addnews("`%".$session[user][name]."`5 è stat".($session[user][sex]?"a":"o")." uccis".($session[user][sex]?"a":"o")." nella foresta dal `@Drago Verde!");
                    debuglog("è stato ucciso dopo aver incontrato il Drago Verde");
                    $session['user']['gold']=0;
                    $session[user][experience]=round($session[user][experience]*.9,0);
                    $session['user']['alive']=false;
                    $session['user']['specialinc']="";
                    addnav("`^Notizie Giornaliere","news.php");
                } else {
                    output("Ti rotoli sul terreno per estinguere le fiamme. `n`nQuando ti rendi conto che non sei ");
                    output("mort".($session[user][sex]?"a":"o").", ti guardi in giro e scopri che il drago è scomparso. `nTi ha lasciato in vita appositamente, ");
                    output("o ha pensato che eri troppo cott".($session[user][sex]?"a":"o")."?`nLa domanda rimarrà senza risposta, perciò decidi ");
                    output("di continuare con le tue avventure nella foresta.`nUna visitina dal `&Guaritore`@ è d'obbligo.");
                    addnews("`%".$session[user][name]."`5 è riuscito a malapena a sopravvivere al Drago Verde!");
                    debuglog("è riuscit".($session[user][sex]?"a":"o")." a malapena a sopravvivere dopo aver perso $damage HP con il `@Drago Verde");
                    $session['user']['specialinc']="";
                }
            }
            break;
    }
}
output("</span>",true);
?>