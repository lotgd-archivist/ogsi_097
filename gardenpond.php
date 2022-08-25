<?php
/**************************************************/
/* Garden Pond (for LoGD 0.97)                    */
/* ------------                                   */
/* Version 1.07b                                  */
/* Conversion by Excalibur (www.ogsi.it)          */
/* Originally written by Jake Taft (Zanzaras)     */
/* (Based on the Lily Pond by Shawn Strider)      */
/**************************************************/

require_once "common.php";
require_once("common2.php");
addcommentary();
srand ((double) microtime() * 10000000);

$nomedb = getsetting("nomedb","logd");
$city="Rafflingate";
$relaxesallowed=1;
$swimsallowed=1;
$hitpointsgained=30; //Percentage of hitpoints healed in the pond
$hitpointslost=30; //Percentage of hitpoints lost due to falling/diving
$turtlebite=10; //Percentage of damage a turtle bite does
$turnsgained=2; //Turns gained from relaxing
$turnslost=2; //Number of turns lost while unconscious
$charmlost=2; //How much charm is lost to the frogs
$gemsgained=e_rand(-5,5); //Number of gems found in cave
$goldgained=50; //Amount of gold found in the pond (multiplied by player's level)
if ($session['user']['superuser'] > 2){
   $relaxesallowed=10;
   $swimsallowed=10;
   print("<c>Settaggi</c><br>");
   print("%HP guadagnati: ".$hitpointsgained."&nbsp &nbsp &nbsp &nbsp %HP persi: ".$hitpointslost."&nbsp &nbsp &nbsp &nbsp %HP persi (tartarughe): ".$turtlebite."<br>");
   print("Turni guadagnati: ".$turnsgained."&nbsp &nbsp &nbsp &nbsp Turni persi: ".$turnslost."&nbsp &nbsp &nbsp &nbsp Fascino perso: ".$charmlost."<br>");
   print("Gemme guadagnate: ".$gemsgained."&nbsp &nbsp &nbsp &nbsp Oro guadagnato (*lvl): ".$goldgained);
}
global $session;
page_header("Il Laghetto dei Giardini");
$session['user']['locazione'] = 131;
output("`b`c`2Il Laghetto dei Giardini`0`c`b`n");
$hitpointsgained = round($session['user']['hitpoints'] * $hitpointsgained * .01);
$hitpointslost = round($session['user']['hitpoints'] * $hitpointslost * .01);
$turtlebite = round($session['user']['hitpoints'] * $turtlebite * .01);

$op = $_GET['op'];
switch ($op){
       case "enter":
       if ($nomedb == "logd"){
          output("`2Cammini fino alla riva del laghetto mentre i grilli friniscono componendo allegre armonie ed un pesciolino guizza sul pelo dell'acqua.");
          output("Ahhh... il luogo perfetto per riposare e rilassarsi al termine di una dura giornata ricca di avventure e pericoli trascorsa nella foresta.");
       }else{
         output("`3Ti avvicini al laghetto dopo aver percorso un sentiero formato da pietre che attraversa un prato verde di erba fresca e profumata.`n");
         output("E' un lago fatato, un luogo di pace, al cui centro nel profondo, si trova una grotta asciutta e calda al riparo ");
         output("dalle intemperie. Al di sopra di tutto troneggia una incantevole cascata d’acqua che sgorga impetuosa da ");
         output("una ripida parete.`nTutto è avvolto da soffici sbuffi di nebbia azzurrina.`n`n");
         viewcommentary("gardenlaghetto","sussurra",20,5,"dice");
       }
       addnav("Laghetto dei Giardini");
       addnav("Goditi lo Scenario","gardenpond.php?op=enjoy");
       addnav("Fai una Nuotata","gardenpond.php?op=swim");
       addnav("Abbandona il Laghetto");
       addnav("Torna ai Giardini","gardens.php");
       break;

       case "swim":
       if (get_pref_pond("swims") < $swimsallowed){
          output("`2Le acque cristalline e limpide sono un vera tentazione, e decidi di farti una rapida nuotata.");
          output("Lasci sulla riva tutta la tua attrezzatura ed entri nell'acqua guardandoti attorno. All'estremità nord del laghetto un piccola cascata ne alimenta le acque e pensi che potrebbe essere divertente andare a darci un'occhiata.");
          output("Alla tua sinistra, si erge una grande quercia e noti una fune legata ad uno dei suoi rami più rigogliosi. Quasi sicuramente questa fune è già stata utilizzata da qualcuno per divertimento. Naturalmente potresti anche solo concederti una semplice nuotata.");
          output("`n`nTi fermo un attimo per decidere decidere cosa fare.");
          addnav("Opzioni");
          addnav("Nuota","gardenpond.php?op=swimwade");
          addnav("Lancio dalla Fune","gardenpond.php?op=swimrope");
          addnav("Vai alle Cascate","gardenpond.php?op=swimwaterfall");
          addnav("Abbandona l'Acqua");
          addnav("Torna a riva e prendi le tue cose","gardenpond.php?op=enter");
          addnav("Torna ai Giardini","gardens.php");
       }else{
          output("`7Ti piacerebbe rimetterti a nuotare, ma sei veramente stanc".($session[user][sex]?"a":"o")." e pensi sia meglio aspettare e riprovare domani.");
          addnav("Ritorna","gardenpond.php?op=enter");
       }
       break;

       case "swimrope":
       $numberofswims = get_pref_pond("swims")+1;
       set_pref_pond("swims",$numberofswims);
       Output("`2Poichè la fune è sospesa sull'acqua, prendi la rincorsa e ti lanci per afferrarla, ");
       $roperesult=e_rand(1,6);
       switch($roperesult){
           case 1:
           output("`2ma chissà come non riesci nel tuo intento!`n`n Cadi come un sasso nelle acque basse vicino alla riva del lago, ferendoti alle gambe nella caduta.`n`n");
           output("Decidi che ti sei `5divertit".($session[user][sex]?"a":"o")."`2 abbastanza per oggi e zoppicando torni a riva.`n`n");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           $session['user']['hitpoints']-=$hitpointslost;
           if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
           if ($hitpointslost > 1) output("`6Ti sei procurat".($session[user][sex]?"a":"o")." `4$hitpointslost`6 punti di danno e `4zoppicherai`6 per un po' fino a che le gambe non saranno guarite.");
           if ($hitpointslost == 1) output("`6Ti sei procurat".($session[user][sex]?"a":"o")." `4$hitpointslost`6 punti di danno e `4zoppicherai`6 per un po' fino a che le gambe non saranno guarite.");
           $session['bufflist']['gardenpond1'] = array("name"=>"`4Gambe Ferite",
                                                  "rounds"=>10,
                                                  "wearoff"=>"Il dolore inizia lentamente a passare.",
                                                  "atkmod"=>.85,
                                                  "defmod"=>.85,
                                                  "roundmsg"=>"Zoppichi sulle gambe ferite!",
                                                  "activate"=>"roundstart"
                                                      );

           break;

           case 2:
           output("`2riesci nel tuo intento, la afferri a mezz'aria e dondoli nel vuoto.`n`n Ma, mentre stai incominciando a divertirti, la vecchia fune si spezza, facendoti precipitare in una zona poco profonda del lago!");
           output("Lo spavento preso è notevole, resti un po' shoccat".($session[user][sex]?"a":"o")." dall'episodio, ma non riporti alcuna ferita anche se pensi di esserti `5divertit".($session[user][sex]?"a":"o")."`2 abbastanza per oggi.");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           break;

           case 3:case 4:case 5:
           output("`2riesci nel tuo intento, la afferri a mezz'aria e ti lasci dondolare nel vuoto fino a raggiungere la zona più profonda del laghetto.`n`n  <big>`%`b`cPalla di Cannone!`b`c</big>`n`n`2A questo punto ti lanci nel vuoto, effettui una capriola in aria e ti tuffi in acqua con una entrata perfetta! Non ti divertivi così tanto da quando eri bambino, e quindi ripeti il gioco più volte.",true);
           output("Trascorri parecchio tempo a nuotare e tuffarti nel laghetto lanciandoti dalla vecchia fune, sino a quando le tue dita iniziano a raggrinzirsi e così felice della pausa rilassante decidi che sia meglio ritornare a riva e uscire dall'acqua.`n`n");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           $session['user']['turns']+=$turnsgained;
           //$session['user']['spirits']=2;
           if ($turnsgained > 1) output("`6Il tuo morale è sollevato e guadagni `^$turnsgained`6 turni extra!`n");
           if ($turnsgained == 1) output("`6Il tuo morale è sollevato e guadagni `^un turno extra`7!`n");
           break;

           case 6:
           output("`2riesci nel tuo intento, la afferri a mezz'aria e dondoli nel vuoto!`n`n Quando ti lasci andare per tuffarti nel laghetto, un luccichio `6giallo`2 cattura la tua attenzione.");
           output("Colpisci l'acqua con un tonfo e nuoti in profondità per andare a vedere di che cosa si tratti. Sotterrata nel limo, ");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           if ($goldgained > 1){
              output("`2trovi una borsa di pelle contentente alcune monete d'oro! La afferri e torni a riva per valutare al meglio il piccolo tesoro trovato!`n`n");
              output("`6Hai trovato `^$goldgained`6 pezzi d'oro!");
              $session['user']['gold']+=$goldgained;
              debuglog("trova $goldgained pezzi d'oro nel laghetto dei giardini.");
           }
           if ($goldgained == 1){
              output("`2trovi `^$goldgained`6 antica moneta d'oro. La afferri e torni a riva per poterla ripulire dalla fanghiglia e guardarla meglio!`n`n");
              $session['user']['gold']+=$goldgained;
              debuglog("trova $goldgained pezzo d'oro nel laghetto dei giardini.");
           }
           if ($goldgained < 1){
              $goldgained = 0;
              output("`2trovi una borsa di quelle usate per contenere monete d'oro! La afferri e torni a riva sperando di aver trovato un piccolo tesoro, ma `n");
              output("`2quando la apri, resti delus".($session[user][sex]?"a":"o")." nel vedere che contiene solo dei piccoli, piatti sassi dipinti! Rabbiosamente, lanci la borsa con le pietre nuovamente nel laghetto!");
              debuglog("ha trovato una borsa con delle pietre color oro al laghetto.");
           }
           break;
       }
       break;

       case "swimwaterfall":
       $numberofswims = get_pref_pond("swims")+1;
       set_pref_pond("swims",$numberofswims);
       output("`2Ti fai strada fino a raggiungere la piccola rupe ed inizi a scalarla.`n`n");
       $waterfallresult=e_rand(1,12);
       switch($waterfallresult){
           case 1:case 2:case 3:
           $session['user']['hitpoints']-=$hitpointslost;
           if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
           $session['user']['turns']-=$turnslost;
           if ($session['user']['turns'] < 1) $session['user']['turns'] = 0;
           output("`2Sei quasi a metà della salita quando scivoli su un sasso instabile e cadi per diversi metri sul terreno atterrando sul didietro.");
           output("Giaci là per qualche minuto, dolorante, prima di riuscire lentamente a rialzarti.`n`n");
           output("Decidi di lasciar perdere il nuoto per oggi e torni dolorosamente alle tue attrezzature.`n`n");
           if ($hitpointslost > 1) output("`6Hai perso `4$hitpointslost`6 HitPoint nella caduta.");
           if ($hitpointslost == 1) output("`6Hai perso `4$hitpointslost`6 HitPoint nella caduta.");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           break;

           case 4:case 5:case 6:
           output("`2Raggiungi la cima e trovi un bel tronco per gettarti dalla cascata.");
           output("Proprio mentre stai raggiungendo il bordo, il tronco colpisce qualcosa e ti fa perdere l'equilibrio, disarcionandoti da esso.");
           output("Precipiti nelle acque bianche di schiuma e sulle rocce sottostanti della cascata.`n`nRiesci in qualche modo a nuotare fino alla riva più vicina, dove crolli esaust".($session[user][sex]?"a":"o")." e dolorante.`n`n");
           output("Ne hai avuto decisamente abbastanza di `5\"divertimento\"`2 per oggi, e ritorni mestamente alla tua attrezzatura.`n`n");
           if ($hitpointslost > 1 and $turnslost > 1) output("`6Hai perso `4$hitpointslost`6 HitPoint durante la traversia e hai speso `4$turnslost`6 turni per recuperare la forma fisica..");
           if ($hitpointslost == 1 and $turnslost > 1) output("`6Hai perso `4$hitpointslost`6 HitPoint durante la traversia e hai speso `4$turnslost`6 turni per recuperare la forma fisica..");
           if ($hitpointslost > 1 and $turnslost == 1) output("`6Hai perso `4$hitpointslost`6 HitPoint durante la traversia e hai speso `4$turnslost`6 turno per recuperare la forma fisica..");
           if ($hitpointslost == 1 and $turnslost == 1) output("`6Hai perso `4$hitpointslost`6 HitPoint durante la traversia e hai speso `4$turnslost`6 turno per recuperare la forma fisica..");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           $session['user']['hitpoints']-=$hitpointslost;
           if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
           $session['user']['turns']-=$turnslost;
           if ($session['user']['turns'] < 1) $session['user']['turns'] = 0;
           break;

           case 7:case 8:case 9:case 10:
           output("`2Raggiungi la cima e trovi un bel tronco per gettarti dalla cascata");
           output("Proprio mentre passi il bordo, salti giù da tronco e voli a colpire la superficie dell'acqua sottostante con un gran botto!`n`n");
           output("Quella si che era una `5bomba`2! Non ti divertivi così tanto da quand'eri ragazzin".($session[user][sex]?"a":"o")."! Sei così eccitat".($session[user][sex]?"a":"o")." e caric".($session[user][sex]?"a":"o")." di adrenalina, che decidi di tornare in foresta per un altro round di mazzuolamenti!`n`n");
           if ($turnsgained > 1) output("`6Guadagni `^$turnsgained`6 turni per l'adrenalina!");
           if ($turnsgained == 1) output("`6Guadagni `^$turnsgained`6 turno per l'adrenalina!");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           $session['user']['turns']+=$turnsgained;
           break;

           case 11:case 12:
           output("`2Raggiungi la cima e trovi un bel tronco per gettarti dalla cascata.");
           output("Proprio mentre stai per raggiungere il bordo, il tronco colpisce qualcosa e ti fa perdere l'equilibrio, disarcionandoti da esso.`n`n");
           output("Mentre stai cadendo nell'acqua, il tronco sbatte contro di te, facendoti finire in una caverna nascosta dietro le cascate.`n`n");
           output("Ti guardi attorno e trovi un antico scheletro che sta stringendo una lacera borsa di cuoio. ");
           if ($gemsgained > 1) output("`6La apri e al suo interno trovi `^$gemsgained`6 gemme!`n`n");
           if ($gemsgained == 1) output("`6La apri e al suo interno trovi `^$gemsgained`6 gemma!`n`n");
           if ($gemsgained < 1){
              $gemsgained = 0;
              output("`6La apri per trovare solo dell'immondizia e alcuni scarafaggi!");
           }
           addnav("Esamina il muro della caverna","gardenpond.php?op=cavewall");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           $session['user']['gems']+=$gemsgained;
           debuglog("trova $gemsgained gemma(e) nella caverna dietro le cascate del laghetto dei giardini.");
           break;
       }
       break;

       case "cavewall":
       output("`2Noti un pezzo di gesso tra i resti e valuti se lasciare un messaggio sui muri lisci della caverna prima di andartene.`n`n");
       viewcommentary("gardenwaterfallcave","Scrivi sul muro",20,5,"ha scritto");
       addnav("Torna a Riva","gardenpond.php?op=enter");
       break;

       case "swimwade":
       $numberofswims = get_pref_pond("swims")+1;
       set_pref_pond("swims",$numberofswims);
       $waderesult=e_rand(1,5);
       switch($waderesult){
           case 1:case 2:case 3:
           output("`2Nuoti nelle acque limpide e cristalline e galleggi in totale relax.`n`n");
           output("Dopo un po' ti accorgi che le dita delle mani e dei piedi si sono raggrinzite e decidi di uscire dall'acqua.");
           output("Avvicinandoti alla riva, scopri che alcune delle tue ferite sono state guarite!`n`n`n");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           if ($hitpointsgained > 1) output("`6Sei stat".($session[user][sex]?"a":"o")." guarit".($session[user][sex]?"a":"o")." per `^$hitpointsgained`6 HitPoint!");
           if ($hitpointsgained == 1) output("`6Sei stat".($session[user][sex]?"a":"o")." guarit".($session[user][sex]?"a":"o")." per `^$hitpointsgained`6 HitPoint!");
           $session['user']['hitpoints']+=$hitpointsgained;
           if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
           break;
           case 4:case 5:
           output("`2Nuoti nelle acque limpide e cristalline e galleggi in totale relax.`n`n");
           output("In effetti, sei talmente rilassat".($session[user][sex]?"a":"o")." che non ti accorgi della tartaruga in avvicinamento che ti morde il piede! Qualche secondo più tardi, il tuo relax viene interrotto bruscamente con molto dolore!");
           output("Ritorni velocemente a riva ed ispezioni le tue povere dita!`n`nDecidi che per oggi ne hai avuto abbastanza di nuotare nel laghetto.`n`n`n");
           addnav("Torna a Riva","gardenpond.php?op=enter");
           if ($turtlebite < 1) $turtlebite = 1;
           if ($turtlebite > 1) output("`6Perdi `4$turtlebite`6 HitPoint per le mascelle della tartaruga addentatrice!");
           if ($turtlebite == 1) output("`6Perdi `4$turtlebite`6 HitPoint per le mascelle della tartaruga addentatrice!");
           $session['user']['hitpoints']-=$turtlebite;
           if ($session['user']['hitpoints'] < 1) $session['user']['hitpoints'] = 1;
           break;
       }
       break;

       case "enjoy":
       if (get_pref_pond("relaxes") >= $relaxesallowed){
          output("`7Ti sdrai a fianco del laghetto e apprezzi la pace e la tranquillità quando le libellule iniziano a svolazzare sulla superficie dell'acqua.");
          output("Un pesce salta fuori dall'acqua, gli uccellini cinguettano e i fiori profumatissimi e variopinti sono sempre così belli in questo luogo di pace. Ti sdrai e all'ombra e sonnecchi per una mezz'oretta, quindi con un sospiro, ti rialzi e torni al mondo reale.");
          addnav("Torna a Riva","gardenpond.php?op=enter");
       }else{
          $numberofrelaxes = get_pref_pond("relaxes")+1;
          set_pref_pond("relaxes",$numberofrelaxes);
          addnav("Ritorna","gardenpond.php?op=enter");
          $rand = e_rand(1,10);
          switch ($rand){
          case 1:
          output("`2Ti sdrai sopra un letto di fiori a fianco del laghetto apprezzando la vista ed il profumo dei giardini, quando all'improvviso un'ape atterra sul tuo naso.");
          output("Cerchi velocemente di allontanarla, ma mentri lo fai lei ti `4punge`2 su una guancia!");
          output("Subito la guancia punta inizia a gonfiarsi anche se tu il più velocemente possibile ne hai estratto il pungiglione.");
          output("`n`n`^OUCH! Quel pungiglione d'ape fa veramente male!");
          $session['bufflist']['gardenpond2'] = array("name"=>"`^Pungiglioni d'ape!",
                                                 "rounds"=>5,
                                                 "wearoff"=>"La guancia si sgonfia e il dolore scompare",
                                                 "atkmod"=>.9,
                                                 "roundmsg"=>"La guancia gonfia ti impedisce di attaccare al meglio delle tue possibilità!",
                                                 "activate"=>"roundstart"
                                                 );
          break;

          case 2: output("`2Ahh, il calmo e rilassante laghetto dei giardini.");
          output("Chi ha bisogno di gironzolare nella scura e mortale foresta in cerca di malvagie creature da uccidere?");
          output("Dopo qualche minuto di pace e tranquillità vieni assalit".($session[user][sex]?"a":"o")." dalla noia.");
          output("Il tuo spirito da guerrier".($session[user][sex]?"a":"o")." ti esorta ad entrare in azione, non puoi star ferm".($session[user][sex]?"a":"o")." e inattiv".($session[user][sex]?"a":"o")." a lungo, così abbandoni il laghetto e ti avvi verso la nera foresta in cerca di nuove avventure.");
          output("`n`n`^Guadagni un turno!");
          $session['user']['turns']++;
          break;

          case 3:
          output("`2Ahh, il calmo e rilassante laghetto dei giardini.");
          output("Decidi di sdraiarti e dedicarti alla meditazione per liberare la tua testa dalla confusione e dai rumori del mondo esterno.");
          output("Da qualche parte, nel profondo della foresta, un'immagine di terre lontane ti appare nella mente.");
          output("Il tuo spirito si rinsalda e ascolti le canzoni ed i sussurri della foresta portati da una dolce brezza che ti carezza la pelle.`n`n");
          output("Ti senti ristorat".($session[user][sex]?"a":"o")." e trova nuova concentrazione per le prossime battaglie che dovrai affrontare!`n`n");
          if ($turnsgained > 1) output("`^Guadagni $turnsgained turni!`n");
          if ($turnsgained == 1) output("`^Guadagni un turno!`n");
          $session['bufflist']['gardenpond3'] = array("name"=>"`#Meditazione",
                                                     "rounds"=>5,
                                                     "wearoff"=>"I rumori del mondo circostante sgretolano la tua concentrazione.",
                                                     "atkmod"=>1.1,
                                                     "roundmsg"=>"Con la mente sgombra e calma, ti getti sul nemico!",
                                                     "activate"=>"roundstart"
                                                     );
          $session['user']['turns']+= $turnsgained;
          break;

          case 4:
          output("`2Ti sdrai e dopo un profondo respiro ti senti particolarmente `%percettivo`2!");
          output("Noti qualcosa che luccica alla luce del sole nell'acqua limpida vicino a riva.");
          output("Ti avvicini all'acqua e trovi un borsellino di pelle contenente alcuni pezzi d'oro.");
          if ($goldgained > 1){
             output("`n`n`2Hai trovato `6$goldgained `2pezzi d'oro con incise delle rune elfiche!");
             $session['user']['gold']+=$goldgained;
             debuglog("trova un borsellino con $goldgained pezzi d'oro nel laghetto dei giardini.");
          }
          if ($goldgained == 1){
             output("`n`n`2Hai trovato `61 `2pezzo d'oro con incise delle rune elfiche!");
             $session['user']['gold']+=$goldgained;
             debuglog("trova un borsellino con $goldgained pezzo d'oro nel laghetto dei giardini.");
          }
          if ($goldgained < 1){
             $goldgained = 0;
             output("`2Apri il borsellino ma al suo interno trovi solo delle piccole, piatte pietre colorate d'oro! Rabbiosamente, rigetti il sacchetto in pelle nel laghetto!");
          }
          break;

          case 5: output("`2La tranquillità e la bellezza di questo luogo fluiscono in te.");
          output("Ti appoggi contro il tronco di una betulla, addormentandoti alla melodia delle onde che con il suo dolce suono avvolge il laghetto.`n`n");
          output("`^Ti risvegli dal tuo sonno con rinnovata energia!`n");
          $session['bufflist']['gardenpond4'] = array("name"=>"`#Rinnovata Energia",
                                                     "rounds"=>5,
                                                     "wearoff"=>"Inizi a sentirti stanc".($session[user][sex]?"a":"o")." nuovamente.",
                                                     "atkmod"=>1.1,
                                                     "roundmsg"=>"Con rinnovata energia, ti lanci contro il nemico!",
                                                     "activate"=>"roundstart"
                                                     );
          break;

          case 6:
          output("`2Camminando sulle sponde del laghetto, noti con la coda dell'occhio una `@rana `2saltare nell'acqua.");
          output("Ti chiedi cosa altro si possa nascondere sotto quelle acque tranquille.");
          output("Forse un giorno lo scoprirai.");
          break;

          case 7:
          output("`2Camminando sulle sponde del laghetto, noti con la coda dell'occhio una bellissima ragazza elfica che ti sta osservando.");
          output("Stai per iniziare a parlare ma lei svanisce senza lasciar traccia di sé.");
          break;

          case 8:
          output("`2Stai camminando sulle rive del laghetto quando, improvvisamente, vedi il `!Padrone del Reame `2camminare tra gli alberi!");
          output("Non hai mai visto una tale imponente, potente figura prima d'ora! Egli si gira verso di te e la tua risolutezza si scioglie come neve al sole! Scappi dalla possente gigante che cammina verso di te!");
          addnews("`^".$session['user']['name']." `3è fuggit".($session[user][sex]?"a":"o")." come un".($session[user][sex]?"a":"")." bimb".($session[user][sex]?"a":"o")." impaurit".($session[user][sex]?"a":"o")." dopo aver visto il `!Padrone del Reame`3 nei giardini di `^$city!");
          break;

          case 9:
          output("`2Ti sdrai ed inali il dolce profumo delle rose, tulipani e lillà e stiracchi le braccia. Tutto questo è veramente rilassante.");
          output("Mentre ti prepari ad andartene, tutto il tuo corpo viene percorso da una scarica di energia.`n`n");
          output("`^I tuoi riflessi sono stati magicamente potenziati dal profumo dei fiori!");
          $session['bufflist']['gardenpond5'] = array("name"=>"`#Riflessi Veloci",
                                                     "rounds"=>5,
                                                     "wearoff"=>"Il profumo dei fiori scompare dalla tua memoria.",
                                                     "defmod"=>1.1,
                                                     "roundmsg"=>"Il profumo dei fiori dei giardini velocizzano i tuoi riflessi!",
                                                     "activate"=>"roundstart"
                                                     );
          break;

          case 10:
          output("`7Inciampi nell'argine del laghetto e cadi nell'acqua sollevando degli spruzzi.");
          output("Lamentandoti del colpo di sfortuna, ti rialzi in piedi e fai un passo verso la riva solo per inciampare un'altra volta e cadere sopra una roccia.");
          output("E mentre giaci priv".($session[user][sex]?"a":"o")." di sensi metà dentro e metà fuori dall'acqua, una intera famiglia di rane decide di cercare rifugio nella tua armatura. Quando ti risvegli i verdi anfibi abbandonano velocemente la loro nuova casa, ma la loro saliva ti provoca una allergia che ti lascia alcune verruche sulla pelle come ricordo.`n`n");
          output("`4Perdi $charmlost punti fascino a causa delle verruche che peggiorano notevolmente il tuo aspetto.");
          $session['user']['charm']-= $charmlost;
          addnews("`3Pover".($session[user][sex]?"a":"o")." `^".$session['user']['name']." `3ha dormito con le rane e si è risvegliat".($session[user][sex]?"a":"o")." un po' acquitrinos".($session[user][sex]?"a":"o").".");
          break;
          }
       }
       break;
}

page_footer();
?>