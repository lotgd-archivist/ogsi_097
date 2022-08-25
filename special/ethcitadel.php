<?php
/******************************************************
Eth's Citadel - Forest Special Event
Author: Eth - eth(at)50free.net
Version: 1.30
About: Allows the player to explore a ruined fortress
in the forest. There is a chance to discover rare and
powerful weapons/armor, gold/gems, buffs, and death!

Installation: Drop into your /special directory!

To-Do List:
1.) Improve the Story Line

Known Bugs:
None - All Squashed into a fine gooey paste :)
But just in case, I'll keep a can of RAID handy.
******************************************************/
if (!isset($session)) exit();

if ($session['user']['turns'] > 0){
   /*********************************************
   Below are the variables that control this mod
   Feel free to adjust them at whim
   **********************************************/
   //Generator for Weapon Names
   $randnumber = e_rand (0,12);
   $randname = array("FrantumaTeschi","Spadone di Serse","Brightstar","Pugnale Cremisi","Spadone di Mithril","Kriss di Kor","Stiletto di Luna","Bastone di Magus","Dente di Drago","Essicca Anime","Tocco dell'Inverno","Spadone della Fenice","Morso della Tigre","Lama del Cercatore");
   $weaponname = $randname[$randnumber];
   //check for user level - based on a suggestion by Excalibur@Dragonprime
   //they warrant a bit of adjustment, change as you see fit
   if ($session['user']['level']<8){
   $weapondmg = e_rand(1,3);
   $weaponvalue = e_rand(1000,2500);
   }else{
   $weapondmg = e_rand(2,8);
   $weaponvalue = e_rand(3000,4500);
   }
   //generator for Armor Names
   $randarmnumber = e_rand(0,12);
   $randarmname = array("Cotta Dorata","Piastra di Mithril","Maglia di Smeraldo","Armatura di Kor","Tunica Mistica","Corazza d'Inverno","Maglia del Drago","Mantella dei Re","Grande Corazza del Campione","Mantella di Kendaer","Armatura di Serse","Anima Protettrice","Pelle di Tigre");
   $armorname = $randarmname[$randarmnumber];
   //check for user level, same comments apply as the above weapon level check
   if ($session['user']['level']<8){
   $armordfs = e_rand(1,3);
   $armorvalue = e_rand(1000,2500);
   }else{
   $armordfs = e_rand(2,8);
   $armorvalue = e_rand(3000,4500);
   }
   //Other Variables that can be adjusted
   $goldloss = round($session['user']['gold']*.50);
   $gemloss = round($session['user']['gems']*.50);
   //ringboost = rounds the buff lasts for
   $ringboost = e_rand(5,13);
   $treasuregold = e_rand(200,2000);
   $treasuregems = e_rand(1,3);
   $fountainhp = e_rand(3,11);
   $fountainweapon = e_rand(1,3);
   $fountainpoison = e_rand(10,17);
   //just for admin testing purposes
   $versionnumber = "1.35";

   if ($_GET['op']==""){
   output("`n`nMentre girovaghi senza meta alcuna nelle zone più oscure e profonde della foresta, ti imbatti nelle rovine di
   un'antica fortezza. `nDal simbolo scolpito sul cancello arrugginito che conduce all'interno, scopri di essere giunto alla
   leggendaria Cittadella di Eth.`n`n");
   output("Le antiche leggende raccontano di nascondigli che celano favolosi tesori, magie potenti, armi leggendarie, e trappole mortali.`n`n");
   output("Guardandoti intorno tra le mura diroccate, noti anche una parete di antichi mattoni dove altri avventurieri che ti hanno preceduto hanno scarabocchiato i loro messaggi per i posteri.`n`n");
   output("Ti fermi a riflettere per un attimo indeciso su cosa fare. Trovare uno dei preziosi tesori della leggendaria Cittadella ti porterebbe gloria e fama e,
   probabilmente, un notevole aiuto contro ogni nemico che oserà incrociare il tuo cammino.`n`n");
   output("Esplorerai queste antiche rovine alla ricerca di fama e gloria o non approfitterai dell'opportunità che ti si presenta di fronte proseguendo per la tua strada?`n`n");

   //Function I stuck in to keep track of the version number - Feel free to remove it
   if ($session['user']['superuser']>=2){output("`^Admin Reference - Version `b$versionnumber`b`n`n");}

   if ($session['user']['turns'] > 0) {
      addnav("E?`\$Esplora le Rovine","forest.php?op=search");
   } else {
      output("`n`2Purtroppo sei troppo stanco per affrontare altre avventure oggi.`n`n");
   }
   addnav("S?`&Scrivi sul Muro","forest.php?op=message");
   addnav("F?`@Torna alla Foresta","forest.php?op=leave");

   /***************************************************
   Below are some Admin Functions I threw in to test
   out the various aspects of this mod. Uncomment them
   them if you wish to use them. Or, feel free to remove
   them altogether!
   ****************************************************/
   /*
   if ($session['user']['superuser']>=3)
   {
   addnav("`^Admin Functions`^","");
   addnav("Test Weapon","forest.php?op=weapontake");
   addnav("Test Armor","forest.php?op=armortake");
   addnav("Test Fountain - Drink","forest.php?op=drink");
   addnav("Test Fountain - Dip","forest.php?op=dipweapon");
   }
   */

   $session['user']['specialinc'] = "ethcitadel.php";


   }elseif ($_GET['op']=="search"){
   $session['user']['turns']-= 1;
   $searchruins = e_rand(1,8);
   switch ($searchruins)
   {
   case 1:
   //Lets check and see if they already have a weapon found here
   if (strchr($session['user']['weapon'],"Leggendari")){
   output("`n`n`^Stanza delle Leggende`n`n");
   output("`&Hai già esplorato meticolosamente questa stanza e hai già reclamato l'arma leggendaria in essa contenuta.
   Sarebbe prudente andarsene prima di risvegliare la rabbia delle antiche forze a guardia della Cittadella.");
   addnav("E?`\$Esplora Ancora","forest.php?op=search");
   addnav("A?`@Abbandona la Cittadella","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   }else{
   //I guess not...
   // viene temporaneamente incrementato il numero dei turni per consentire di afferrare l'arma all'ultimo turno.
   // Il numero dei turni verrà decrementato poi nei sottocasi
   $session['user']['turns']+= 1;
   output("`n`n`&Dopo aver strisciato nel buio di lungo un serpeggiante corridoio nel cuore stesso della cittadella,
   una luce attira la tua attenzione e raggiungi una larga sala, debolmente illuminata. Al centro della stanza vedi un brillante colonna,
   e sul piedistallo, a mala pena visibile, intravedi uno strano oggetto appoggiato sopra di esso.`n`n");
   output("`&Guardando più attentamente scorgi la sagoma di un'arma. `nChe sia questa una delle antiche `^armi leggendarie `&
   di cui i bardi raccontano meraviglie nelle loro canzoni ?`n`n");
   output("La decisione spetta a te. Sfiderai la sorte e tenterai di impadronirti di quest'Arma ? Oppure la lascerai dove si trova
   confidando nella tua fedele `#".$session['user']['weapon']."?");
   addnav("A?`@Afferra l'Arma","forest.php?op=weapontake");
   addnav("`\$Lasciala Stare","forest.php?op=weaponleave");
   $session['user']['specialinc'] = "ethcitadel.php";
   }
   break;
   case 2:
   output("`n`nDopo infinite ore di inutili esplorazioni nei meandri di infiniti corridoi e nelle stanze del labirinto della Cittadella,
   riemergi senza nulla di interessante che sia valso gli sforzi compiuti, se non tagli e contusioni. Esausto e depresso,
   devi decidere se esplorare ancora più a fondo le stanze o se stancamente dirigerti verso le amiche fronde della foresta.`n`n");
   $session['user']['clean'] += 1;
   addnav("E?`\$Esplora Ancora","forest.php?op=search");
   addnav("A?`@Abbandona la Cittadella","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   break;
   case 3:
   output("`n`n`&Rovistando fra le rovine di quella che sembra essere stata un principesca e sfarzosa camera da letto,
   trovi una vecchia cassa arrugginita. `nLa apri faticosamente e al suo interno vedi `^$treasuregold pezzi d'oro `&e `\$$treasuregems gemme!`n`n");
   output("`&Dopo aver messo al sicuro nelle tue tasche il tesoro appena trovato, devi decidere se proseguire ancora con l'esplorazione tra le rovine.`n`n");
   $session['user']['gold']+=$treasuregold;
   $session['user']['gems']+=$treasuregems;
   debuglog("trova $treasuregold oro e $treasuregems gemme nella Cittadella");
   addnav("E?`\$Esplora Ancora","forest.php?op=search");
   addnav("A?`@Abbandona la Cittadella","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   break;
   case 4:
   //While I don't really like this one, I added it in anyways (my wife pestered me for it).
   output("`n`nLa tua frenetica ricerca tra le macerie della Cittadella ti ricompensa con un misero quanto opaco anello d'argento.
   Pensando di poterlo rivendere per qualche pezzo d'oro al villaggio, inizia a lucidarne la superficie sfregandolo con forza.`n`n");
   output("Improvvisamente l'anello si illumina di una brillante luce blu, accecandoti per un attimo.
   `nQuando riacquisti la vista, l'anello è sparito, ma ti senti energizzato e più forte che mai!`n`n");
   addnav("E?`\$Esplora Ancora","forest.php?op=search");
   addnav("A?`@Abbandona la Cittadella","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   $session['bufflist']['citadel'] = array("name"=>"La Forza dell'Anello","rounds"=>$ringboost,"wearoff"=>"La magia dell'anello si esaurisce...","atkmod"=>1.3,"roundmsg"=>"L'Anello della Cittadella incrementa la tua forza","activate"=>"offense");
   addnews("".$session['user']['name']." `&ha incontrato delle strane forze mentre effettuava delle ricerche nella `^Cittadella`&!`&");
   break;
   case 5:
   //Gee, have  we been here before? Let's find out!
   if (strchr($session['user']['armor'],"Leggendari")){
   output("`n`n`^L'Armeria Abbandonata`n`n");
   output("`&Sei già stato in questa stanza rovistandola da cima a fondo e hai già reclamato il prezioso tesoro in essa contenuta.
   Meglio muoversi, non essendoci più nulla da fare qui.`n`n");
   addnav("E?`\$Esplora Ancora","forest.php?op=search");
   addnav("A?`@Abbandona la Cittadella","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   }else{
   // viene temporaneamente incrementato il numero dei turni per consentire di afferrare l'armatura all'ultimo turno.
   // Il numero dei turni verrà decrementato poi nei sottocasi
   $session['user']['turns']+= 1;
   output("`n`nTi sei imbattuto in quella che anticamente doveva essere una ricca armeria.
   Mucchi di elmi, armature, cotte di maglia, corazze di ferro e di cuoio arrugginite e malridotte giacciono abbandonate e accatastate tra le rovine.
   Tuttavia, noti un bagliore in una piccola stanza attira la tua attenzione.`n`nChe ci sia un tesoro in attesa di essere scoperto ?`n`n");
   addnav("E?`^Esamina il Bagliore","forest.php?op=armortake");
   addnav("A?`\$Abbandona la Stanza","forest.php?op=armorleave");
   $session['user']['specialinc'] = "ethcitadel.php";
   }
   break;
   case 6:
   output("`n`nMentre frughi in un piccolo alloggiamento in un muro, la parete si sgretola e mucchi di pietre si rovesciano su di te seppellendoti.`n`n");
   output("Il tuo corpo è orribilmente schiacciato dalle macerie e perdi sangue copiosamente.
   `nNon passa molto tempo prima che tu perda i sensi e dopo una rapida agonia senza sofferenze muori.`n`n");
   output("Forse presterai più attenzione la prossima volta ...`n`n");
   $session['user']['clean'] += 1;
   $session['user']['gold']-=$goldloss;
   $session['user']['hitpoints']=0;
   $session['user']['alive']=false;
   addnav("N?`^Notizie Giornaliere","news.php");
   addnav("T?`\$Terra delle Ombre","shades.php");
   addnews("".$session['user']['name']." `&è mort".($session['user']['sex']?"a":"o")." mentre effettuava ricerche tra le rovine della `6Cittadella `&nella foresta!`&");
   debuglog("è morto in seguito al crollo di un muro nella Cittadella");
   break;
   case 7:
   output("`n`nNel corso delle tue esplorazioni, ti imbatti in un piccolo cortile. `nSebbene ci siano colonne rovesciate e
   pareti sbriciolate ovunque, al centro si erge una fontana di splendida fattura, che non mostra assolutamente l'usura del tempo.`n`n");
   output("Ti avvicini alla fontana e noti che l'acqua risplende riflettendo una tenue luce blu. L'acqua è sicuramente dotata di poteri magici!`n`n");
   output("Forse berne un sorso o immergere la tua arma nell'acqua potrebbe portarti dei benefici. Cosa decidi di fare ?`n`n");
   // viene temporaneamente incrementato il numero dei turni per consentire di bere l'acqua o immergere l'arma nella fontana
   // all'ultimo turno. il numero dei turni verrà decrementato poi nei singoli sottocasi
   $session['user']['turns']+= 1;
   addnav("`&Bevi l'Acqua","forest.php?op=drink");
   addnav("`^Immergi l'Arma","forest.php?op=dipweapon");
   addnav("`\$Abbandona il Cortile","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   break;
   case 8:
   output("`n`nDopo infinite ore di esplorazione degli innumerevoli corridoi e delle varie stanze del labirinto della Cittadella,
   riemergi con nulla da mostrare per i tuoi immani sforzi, se non tagli e contusioni. Esausto e depresso,
   decidi di dirigerti nuovamente verso la foresta.`n`n");
   $session['user']['clean'] += 1;
   addnav("A?`@Abbandona la Cittadella","forest.php");
   break;
   }
   }elseif ($_GET['op']=="message"){
   //Little chat area
   output("`n`nIncisi nel muro di mattoni ci sono i messaggi lasciati dagli esploratori che ti hanno preceduto.`n`n");
   viewcommentary("citadeleth","Incidi",25);
   addnav("`@Torna alla Cittadella","forest.php");
   $session['user']['specialinc'] = "ethcitadel.php";

   //Weapon Selection
   //You either get a shiny new toy, or a devious trap >:)
   }elseif ($_GET['op']=="weapontake"){

   $session['user']['turns']-= 1;
   $legendchance = e_rand(1,6);
   switch ($legendchance)
   {
   case 1: case 2: case 3: case 4:
   output("`n`nGetti via la tua vecchia arma in favore del `b$weaponname`b.`n`nSenti già il leggendario potere scorrere nelle tue vene!`n`n
   Presto sarai invidiato da tutti gli abitanti del villaggio!`n`n");

   $session['user']['weapon']="Leggendario ".$weaponname;
   $session['user']['attack']+=$weapondmg;
   $session['user']['weapondmg']+=$weapondmg;
   $session['user']['weaponvalue']+=$weaponvalue;
   // Maximus Inizio
   $session['user']['usura_arma'] = intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
   // Maximus Fine
   addnews("".$session['user']['name']."`& ha trovato il `%Leggendario `^$weaponname`& mentre cercava tra le rovine della `6Cittadella`&!");
   debuglog("trova $weaponname nella Cittadella");
   addnav("`@Torna alla Foresta","forest.php");
   break;
   case 5: case 6:
   output("`n`nMentre ti avvicini alla colonna di luce, la tua arma si sgretola velocemente e si riduce in polvere.
   La colonna emette un bagliore accecante, e quando riacquisti la vista, non trovi più `i`bnulla`i`b!
   Era solo un'illusione, una trappola per gli avidi avventurieri!`n`n");
   //Damage is based on your level.
   //I did this to be fair to my players. Change the value if you wish
   $session['user']['clean'] += 1;
   $curseweapon= "Pugni";
   $session['user']['weapon']=$curseweapon;
   $session['user']['attack']-=$session['user']['weapondmg'];
   $session['user']['weapondmg']=0;
   $session['user']['weaponvalue']=0;
   //Maximus Inizio
   $session['user']['usura_arma']=999;
   //Maximus Fine
   debuglog("perde la propria arma alla Cittadella");
   addnews("".$session['user']['name']."`& è caduto preda di una trappola mentre cercava tra le rovine della `6Cittadella`&!");
   addnav("`@Torna alla Foresta","forest.php");
   break;
   }
   //Aww fooey, they chose not to chance it!
   }elseif ($_GET['op']=="weaponleave"){
   $session['user']['turns']-= 1;
   output("`n`nNon volendo rischiare di risvegliare l'ira degli antichi poteri che custodiscono la Cittadella,
   decidi che è più saggio mantenere la tua vecchia arma `ncompagna di tante avventure e che non ti ha mai tradito nel momento del bisogno.");
   output("`nConvinto di aver preso la decisione più saggia, ti avvi verso la foresta intraprendendo il viaggio di ritorno.`n`n");
   addnav("`@Torna alla Foresta","forest.php");

   //So, looking for some armor are you? Take your chances then...
   //Shiny toy or devious trap? Muwhahaha.
   }elseif ($_GET['op']=="armortake"){
   $session['user']['turns']-= 1;
   $armorchance = e_rand(1,6);
   switch ($armorchance)
   {
   case 1: case 2: case 3: case 4:
   output("`n`n`&Entrando nella stanza, trovi `^$armorname `&appoggiata sopra un piedistallo.`n`n
   Lentamente e con nostalgia ti togli la tua vecchia armatura fedele compagna di tante battaglie e indossi la `^$armorname.`n`n`&Senti immediatamente l'immenso potere di cui è dotata esercitare la sua protezione!`n`n");
   $session['user']['armor'] = "Leggendaria ".$armorname;
   $session['user']['defence']+=$armordfs;
   $session['user']['armordef']+=$armordfs;
   $session['user']['armorvalue']+=$armorvalue;
   // Maximus Inizio
   $session['user']['usura_armatura'] = intval($session['user']['armordef'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
   // Maximus Fine
   debuglog("trova %$armorname nella Cittadella");
   addnews("".$session['user']['name']."`& ha scoperto la `%Leggendaria `^$armorname`& mentre esplorava le rovine della `6Cittadella`&!");
   addnav("`@Torna alla Foresta","forest.php");
   break;
   case 5: case 6:
   output("`n`n`&Mentre entri nella piccola stanza, vieni accecato da un `^flash di brillante luce color oro`&.
   Disorientato e con un leggero senso di nausea, fuggi velocemente uscendo di corsa dalla stanza.`n`n");
   output("`&Quando riacquisti la vista, ti rendi conto che la tua preziosa armatura si è `@disintegrata! `&Era una trappola!`n`n");
   $session['user']['clean'] += 1;
   $session['user']['armor'] = "T-shirt";
   $session['user']['defence']-=$session['user']['armordef'];
   $session['user']['armordef'] = 0;
   $session['user']['armorvalue'] = 0;
   //Maximus Inizio
   $session['user']['usura_armatura']=999;
   //Maximus Fine
   debuglog("perde la propria armatura alla Cittadella");
   addnews("".$session['user']['name']."`& è caduto vittima di una trappola mentre esplorava le rovine della `6Cittadella`&!");
   addnav("`@Torna alla Foresta","forest.php");
   break;
   }
   //Nope, aint gonna risk it. Gonna leave the armor behind
   }elseif ($_GET['op']=="armorleave"){
   $session['user']['turns']-= 1;
   output("`n`nNon volendo rischiare di essere preda dell'ira degli antichi poteri che custodiscono la Cittadella,
   decidi che è più saggio tenere la tua vecchia armatura. Sicuro di aver preso la giusta decisione,
   intraprendi il viaggio di ritorno.`n`n");
   addnav("`@Torna alla Foresta","forest.php");

   //The hell with you all, I'm juts gonna leave altogether!
   }elseif ($_GET['op']=="leave"){
   output("`n`nNon sei mai stato un guerriero coraggioso, perciò abbandoni quelle rovine fatiscenti e ripercorri la strada che ti riporta
   alla foresta.`n`n");
   output("Forse un giorno troverai il coraggio e la forza per ritornare.`n`n");
   addnav("`@Torna alla Foresta","forest.php");

   //I'm thirsty...
   }elseif ($_GET['op']=="drink"){
   $session['user']['turns'] -= 1;
   output("`n`nDecidi di tentare la sorte bevendo l'acqua della fontana ...`n`n");
   $fountainswitch = e_rand(1,3);
   switch ($fountainswitch)
   {
   case 1:
   output("`&I tuoi `^HP massimi `&sono aumentati di `^$fountainhp!");
   $session['user']['maxhitpoints']+=$fountainhp;
   $session['user']['hitpoints']+=$fountainhp;
   addnav("`@Torna alla Foresta","forest.php");
   break;
   case 2:
   output("Inizi a contorcerti per il dolore. La nausea prende il sopravvento su di te e ti ritrovi incapace di reggerti in piedi!`n`n");
   output("L'acqua era avvelenata!`n`n");
   //let's make sure they have enough hitpoints first
   //note: if you changed the max value in $fountainpoison, adjust this to match!
   if ($session['user']['hitpoints']<=17){
   output("`^Perdi`& un combattimento e i tuoi HP sono ridotti a `#UNO`&!`n`n");
   $session['user']['hitpoints']=1;
   addnav("`@Torna alla Foresta","forest.php");
   }else{
   output("`^Perdi`& un combattimento e `#$fountainpoison `&HP!`n`n");
   $session['user']['hitpoints']-=$fountainpoison;
   addnav("`@Torna alla Foresta","forest.php");
   }
   break;
   case 3:
   output("Le acque della fontana ti portano nuove conoscenze!`n`n");
   increment_specialty();
   break;
   }
   //hope this is stainless steel
   }elseif ($_GET['op']=="dipweapon"){
   $session['user']['turns']-= 1;
   //lets check to see if a blessing has already been granted
   if (strchr($session['user']['weapon'],"Benedetta")){
   output("`n`n`&La tua `b".$session['user']['weapon']."`b è già stata benedetta dalle `#acque della fontana`&!`n`n");
   addnav("E?`\$Esplora Ancora","forest.php");
   addnav("`@Torna alla Foresta","forest.php?op=leave");
   $session['user']['specialinc'] = "ethcitadel.php";
   }else{
   //nope, I guess not. Well, let's apply one then!
   output("`n`nCon cautela bagni la tua arma nelle acque della fontana. L'acqua ribolle in una moltitudine di schizzi azzurrognoli e la tua ".$session['user']['weapon']." inizia a risplendere!`n`n");
   output("La tua arma guadagna +$fountainweapon punti attacco!");
   debuglog("benedice la propria arma alla fontana della Cittadella");
   $newname = $session['user']['weapon']." Benedetta";
   $session['user']['weapon']=$newname;
   $session['user']['weapondmg']+=$fountainweapon;
   $session['user']['attack']+=$fountainweapon;
   // Maximus Inizio
   $session['user']['usura_arma'] = intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
   // Maximus Fine
   addnav("`@Torna alla Foresta","forest.php");
   }
   }
}else{
   output("`5Sei molto stanco e non hai più turni a disposizione, sei quindi costretto tuo malgrado, ad abbandonare la Cittadella`n");
   addnav("Torna in Foresta","forest.php");
}
?>