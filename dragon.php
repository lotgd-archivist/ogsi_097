<?php
require_once "common.php";
require_once "common2.php";
page_header("Il Drago Verde!");
$session['user']['locazione'] = 118;
$etadrago = array(
1=>"Cucciolo",
2=>"Giovane",
3=>"Adulto",
4=>"Anziano",
5=>"Antico"
);
$dio = $session['user']['dio'];
$drakil = $session['user']['dragonkills'];
if ($_GET['op']==""){
    output("`n`n`\$Cercando di sconfiggere la paura che, oltre a farti tremare le gambe, instilla in te il desiderio di fuggire lontano, entri con cautela nella caverna, con ");
    output("la speranza di trovare il grande `@Drago Verde `\$ addormentato per poterlo uccidere facilmente e senza correre rischi. ");
    output("Purtroppo la fortuna non è dalla tua parte, poiché non appena svolti un angolo della caverna trovi l'enorme animale accovacciato su un grande mucchio di ossa, intento a stuzzicarsi ");
    output("i denti con quella che sembra essere una costola umana.`n`n");
    $badguy = array("creaturename"=>"`@Il Drago Verde`0","creaturelevel"=>18,"creatureweapon"=>"Enormi Fauci Fiammeggianti","creatureattack"=>45,"creaturedefense"=>25,"creaturehealth"=>300, "diddamage"=>0);
    //toughen up each consecutive dragon.
    //      $atkflux = e_rand(0,$session['user']['dragonkills']*2);
    //      $defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
    //      $hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
    //      $badguy['creatureattack']+=$atkflux;
    //      $badguy['creaturedefense']+=$defflux;
    //      $badguy['creaturehealth']+=$hpflux;

    // First, find out how each dragonpoint has been spent and count those
    // used on attack and defense.
    // Coded by JT, based on collaboration with MightyE
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


if ($session['user']['reincarna'] > 0 AND $_GET['op1']!="smart"){
   //output("Routine Modificata`n");
   $reincaatt = 0;
   $reincadef = 0;
   $reinca=round(($session['user']['reincarna']/2)*18);
   if ($reinca < 18) $reinca = 18;
   if ($session['user']['bonusattack'] > $reinca) {
      $reincaatt = $reinca+1;
   } else $reincaatt = ($session['user']['bonusattack'] / 2) + 1;
   if ($session['user']['bonusdefence'] > $reinca) {
      $reincadef = $reinca+1;
   } else {
   $reincadef = ($session['user']['bonusdefence'] / 2) + 1;
   }
   $atkflux = e_rand(2,($session['user']['dragonkills']+$reinca)) + e_rand($reincaatt,($session['user']['bonusattack']));
   $defflux = e_rand(2,($session['user']['dragonkills']-intval($atkflux/2) + $reinca)) + e_rand($reincadef,($session['user']['bonusdefence']));
   $hpflux = (($session['user']['dragonkills'] - ($atkflux+$defflux)) * 4) + e_rand(2,($reinca*$session['user']['maxhitpoints']/5));
   $atkflux += $points;
   $defflux += $points;
   $hpflux += $points;
   //$atkflux -= 2;
   //$defflux -= 2;
   //aiuto nel caso si sia stati sconfitti dal maestro le volte precedenti
   $hpflux = round($hpflux*0.9);
   $hpflux -= ($session['user']['helpmaster']*3);
   $atkflux -= ($session['user']['helpmaster']*3);
   $defflux -= ($session['user']['helpmaster']*3);
}elseif ($session['user']['reincarna'] == 0 AND $_GET['op1']!="smart"){
   //output("Routine Normale`n");
   //Modifica di Excalibur per tener conto dei PA e PD PERMANENTI
   $bnsatk=(int)(($session['user']['bonusattack']/1.5)+0.99);
   $bnsdef=(int)(($session['user']['bonusdefence']/1.5)+0.99);
   // Fine modifica ... vedi anche sotto
   //luke per potenziare un po il drago verde per i reincarnati
   $bnsatk+=(int)(($session['user']['reincarna']* e_rand(($bnsatk/2), $bnsatk))/4);
   $bnsdef+=(int)(($session['user']['reincarna']* e_rand(($bnsdef/2), $bnsdef))/4);
   //fine modifica luke
   $atkflux = e_rand($points, ($bnsatk+$points));
   $defflux = e_rand($points,($points-$atkflux+$bnsdef));
   $hpflux = ($points + $bnsatk + $bnsdef - ($atkflux+$defflux)) * 5;
}else{
   $atkflux= $session['user']['attack'];
   $defflux= $session['user']['defence'];
   $hpflux= $session['user']['hitpoints'];
}
    $badguy['creatureattack']+=$atkflux;
    $badguy['creaturedefense']+=$defflux;
    $badguy['creaturehealth']+=$hpflux;
    if ($badguy['creaturehealth'] > (2*$session['user']['maxhitpoints'])) $badguy['creaturehealth'] = 2*$session['user']['maxhitpoints'];
    if ($_GET['op1']=="smart"){
       $badguy['creatureattack'] = $atkflux;
       $badguy['creaturedefense'] = $defflux;
       $badguy['creaturehealth'] = $hpflux;
    }
    $session['user']['badguy']=createstring($badguy);
    $battle=true;
}else if($_GET['op']=="prologue1"){
    output("`@Vittoria!`n`n");
    $flawless = 0;
    if ($_GET['flawless']) {
        $flawless = 1;
        output("`b`c`&~~ Combattimento Perfetto ~~`0`c`b`n`n");
    }
    if ($dio == 3 OR $dio == 0){
       if ($dio == 3){
           output("`2Ce l'hai fatta! Durante lo scontro ad un certo punto sei stato colto dal dubbio, dall'incertezza,
           ma poi sei riuscito a recuperare la calma e la determinazione, ed hai adempiuto ai tuoi doveri di seguace della `@Gilda del Drago`2.`n`n
           Il Possente animale giace per terra, apparentemente esanime. Sai perfettamente che finge, ma ti rincuora essere consapevole
           che hai rispettato i suoi voleri, ed ora attendi la ricompensa che ne verrà.`nIl Primo tra i Draghi si rialza,
           e tu fingi di essere sorpreso, come ti è stato insegnato. Così vuole il `@Drago Verde`2, e così tu ti comporti. Impressionato,
           senti per la prima volta la sua voce, e ti accorgi che è identica a come l'hai percepita nella tua mente e nel
           tuo cuore durante le Celebrazioni alle quali hai assistito nel luogo di culto che frequenti.`n`n");
       } else {
          output("`2Davanti a te giace immobile il grande `@Drago Verde`2, il suo pesante respiro sembra acido per i tuoi polmoni.  ");
          output("E tu, coperto dalla testa ai piedi dal suo sangue nero, ne fissi attentamente l'agonia, quando la grande bestia inizia a muovere la bocca. ");
          output("Balzi rapidamente indietro, furente con te stesso per esserti lasciato ingannare ");
          output("dalla sua morte simulata, e ti aspetti che la sua possente coda inizi a muoversi per colpirti. Ma non lo fa. ");
          output(" Invece, il drago inizia a parlare.`n`n");
       }
       output("\"`^Perché sei venuto qui mortale? Che cosa ti ho fatto?`2\" dice con sforzo evidente.  ");
       output("\"`^Da sempre la mia razza viene cacciata e distrutta. Perché? A causa di storie di terre lontane ");
       output("che parlano di draghi che predano i più deboli? Io ti dico che tali storie provengono solo da equivoci ");
       output("su di noi, e non dal fatto che divoriamo i vostri figli.`2\"  La bestia fa una pausa, respirando pesantemente prima di continuare, ");
       output("\"`^Ti dirò un segreto. Dietro di me ci sono le mie uova. Si schiuderanno, e i piccoli combatteranno ");
       output("tra loro. Solo un piccolo sopravviverà, il più forte tra i suoi fratelli, crescerà in fretta e diventerà ");
       output("potente quanto me e sarà temuto come lo sono io ora.`2\" Il respiro della grande bestia diventa sempre più affannoso e irregolare.`n`n");
       if ($dio == 3){
          output("`2Rispettando il rituale, rispondi come ti è stato insegnato.`n`n");
       }
       output("\"`#Perchè mi dici questo? Non sai che ora distruggerò le tue uova?`2\" domandi.`n`n");
       if ($dio == 3){
          output("`2Ti fa uno strano effetto, sai che da quelle uova non uscirà nessun cucciolo, e che semplicemente
          il `@Grande Drago Verde `2si rialzerà entro breve, ma reciti comunque la tua parte.`n`n");
       }
       output("\"`^No, non lo farai, perché io conosco un altro segreto che tu non conosci.`2\"`n`n");
       output("\"`#Dimmi allora oh potente bestia!`2\"`n`n");
       output("La grande bestia fa una pausa, raccogliendo le sue ultime energie. \"`^La tua razza non può sopportare il sangue ");
       output("della mia. Se anche sopravvivi, sarai un umano debole, a malapena in grado di impugnare un'arma, la tua mente ");
       output("svuotata di tutto ciò che hai appreso. No, non sei una minaccia per la mia prole, perché sei già morto!`2\"`n`n");
       if ($dio == 3){
          output("`2Rendendoti conto che la tua vista comincia a diventare offuscata esci di fretta dalla grotta,
          il Possente non deve vederti in questo momento di debolezza, o potrebbe cambiare idea e straziare le
          tue carni.`nSubito fuori per prima cosa spezzi la tua arma, che dopo aver assaggiato il sangue della più
          nobile delle creature non dovrà essere mai più contaminata da nulla.`nArrivato vicino ad un ruscello abbandoni
          la tua armatura e spalmi sul tuo corpo tutto il sangue del drago che puoi, perchè il suo potere entri in te.
          Dopo di che, tutto diventa nero.`n`n");
       }else{
          output("Rendendoti conto che la tua vista si sta già sfuocando, fuggi dalla caverna, cercando di raggiungere ");
          output("la capanna del guaritore prima che sia troppo tardi. Da qualche parte lungo la strada perdi la tua arma, ed infine ");
          output("inciampi in una pietra in un torrentello, con la vista ormai limitata ad un piccolo cerchio che sembra galleggiare intorno a te ");
          output(" Mentre giaci, guardando in alto verso gli alberi, credi di sentire non distante i suoni ");
          output("del villaggio. Il tuo ultimo pensiero è che sebbene tu abbia sconfitto il drago, rifletti sull'ironia ");
          output("del fatto che esso abbia sconfitto te.`n`n");
          output("Mentre la vista ti si spegne, lontano, nella tana del drago, un uovo si ribalta di lato, ed una piccola crepa ");
          output("compare nel suo spesso guscio.");
          if ($flawless) {
              output("Cadi in avanti, e ti sovviene all'ultimo momento che sei riuscito almeno ad afferrare alcuni dei tesori del Drago, così forse non è stata una sconfitta totale.");
          }
       }
    }elseif ($dio == 2){
       output("`2Il corpo del drago, esanime, giace davanti a te.`nSenti il tuo sangue ribollire: odio, ira,
       sete di sangue ... soddisfazione.`nSai di aver compiuto il volere del tuo dio, e questo ti fa sentire potente
       come non mai.`nDalla testa ai piedi sei di un unico colore: `\$rosso sangue`2. Per fortuna, la maggior parte
       non è tuo, ma della Bestia.`nSangue ... il nettare di Karnak ... lo senti penetrare in ogni tua ferita,
       in ogni poro del tuo corpo, lo senti bruciare come fuoco.`nMentre, ebbro di potenza, osservi il tuo corpo,
       il Drago si muove. Sussulti, prendi nuovamente in mano l’arma, ma dal suo sguardo comprendi che è solo un
       respiro quello che gli rimane.`nCon tua grande sorpresa, il drago scoppia a ridere.`nLo guardi con odio ...
       vorresti staccargli la testa dal corpo, ma prima hai una domanda da porre:`n`n- `#Perché ridi, maledetto?`n
       `2- `^Tu, seguace di `\$Karnak`^, mi chiedi perchè sto ridendo? Dovresti sapere che il Sangue è sacro a
       `\$Karnak `2...`n- `#Cosa intendi?`n`2- `^Stupido, non capisci che ...`n`n`2Ma prima che la bestia finisca
       la frase, crolla di peso sul terreno, morto.`nColmo di rabbia, lo decapiti con la tua arma, e dal suo collo
       mozzato un rivolo di sangue sgorga, fino a giungere ad un uovo, che ha un tremito.`nRidi, sadico, hai portato
       avanti la maledizione di `\$Karnak`2. Da quell’uovo il Drago rinascerà, con come unico destino quello di essere
       ucciso di nuovo.`n`nPoi, sgrani gli occhi, e comprendi.`nIl sangue del drago ha trasmesso la maledizione
       anche a te ...`n`nSenti le forze svanire e comprendi che morirai, ma senza poter morire. Barcolli, inciampi.
       Hai lasciato la tua arma piantata nel corpo del drago, ma non hai le forze per andarla a riprendere.`nNon
       riesci a respirare, devi arrivare ad una fonte, a dell’acqua. Devi mondarti dal sangue maledetto, prima che
       sia troppo tardi. Abbandoni la tua armatura, per muoverti più velocemente.`nGiungi ad un basso ruscello e
       ci cadi dentro, perdendo i sensi.`n");
    }else{
      output("`2Giustizia è stata fatta! Il volere di `^Sgrios `2si è compiuto!`nLa battaglia è stata dura, ma finalmente
	    hai mondato questo luogo.Il possente `@Verde Drago`2 ha perso tutta la sua maestosità ed ora giace agonizzante ai tuoi
	    piedi.`nTi avvicini, pronto a sferrare il colpo di grazia. il `^Portatore di Luce`2 chiede Giustizia, non Vendetta, 
	    e quindi pur avendo compiuto durante la sua esistenza terribili atrocità, il terribile animale non dovrà soffrire più 
	    del necessario. Ma quando alzi l’arma, il Grande `@Drago Verde`2 si erge nuovamente sovrastandoti con tutta la sua 
	    altezza. Ti rimetti inguardia pronto a combattere un ultimo assalto, ma con tua sorpresa, il mostro parla.
	    `n`n- `@Il tuo signore predica la vita, perché dunque tu mi uccidi,seguace di `^Sgrios `@?`n`2
	    - `#Troppe vite innocenti hai falcidiato, Potente Drago, e il compito che mi è stato affidato ogi è quello di mettere la tua
	    vita sull’altro piatto della bilancia del giudizio divino, affinché sia la Giustizia a trionfare.`n`n`2La Belva scoppia a ridere.`n`n
	    - `@Questo mi suona di Vendetta, seguace di `^Sgrios `@, non di Giustizia. Qualcuno è forse mai venuto a parlarmi per conoscere la mia storia? 
	    Qualcuno si è mai chiesto il motivo di così tanti cadaveri innanzi alla mia tana, o sul perché io abbia ucciso così tanti guerrieri? 
	    Nessuno. E ora tu mi parli di giustizia ...
	    `n`2- `#Non mi farò trarre in inganno dalle tue perniciose parole, mostro, tu tenti soltanto di salvarti la vita, 
	    ma io porterò la Luce in questo luogo impuro.
	    `n`2- `@Cerco solo di aprirti gli occhi, perché forse la troppa luce ti ha accecato.`n`2- `#I miei occhi non sono
	    mai stati tanto aperti, blasfema creatura. Le tue parole sono soltanto mendaci, poiché se dicessi il vero non mi
	    avresti attaccato appena dentro alla tua tana, ma avresti tentato di parlarmi. Ed ora finiscila, non ascolterò
	    nessuna altra delle tue menzogne.`n Preparati ad essere giudicato dal Sommo `^Sgrios `2, `@Verde Drago`2!
	    `n`n`2Il `@Drago Verde`2, furioso, si avventa su di te, ma con un rapido colpo lo trafiggi, facendolo cadere esanime ai tuoi piedi.
	    `nSenti un rumore come di qualcosa che si rompe ed alla tua destra noti che un uovo si è schiuso, con un piccolo draghetto 
	    che fa capolino da esso. `nTi avvicini, con l’intenzione di estinguere totalmente quella specie maledetta, ma poi,
	    ripensando agli insegnamenti del `^Giusto`2, decidi che non puoi colpire una creatura priva di colpa.
	    `nIncamminandoti verso l'uscita della caverna, ti accorgi che il tuo equipaggiamento si sta sciogliendo: il sangue 
	    della Bestia è corrosivo, e tu ne sei ricoperto da capo a piedi. Abbandoni arma e armatura, ormai inservibili, e corri a più non
	    posso verso un ruscello: devi ripulirti.`nIl sangue acido della creatura incomincia a bruciare le tue carni,
	    il dolore è insopportabile. Quando giungi finalmente in prossimità dell’acqua la sofferenza ha oramai pervaso
	    la tua debole mente e perdi i sensi.`n`n");
    }
    addnav("È un nuovo giorno","perdita.php");
    $sql = "describe accounts";
    $result = db_query($sql) or die(db_error(LINK));
    $hpgain = $session['user']['maxhitpoints'] - ($session['user']['level']*10);
    $nochange=array("acctid"=>1
    ,"charisma"=>1
    ,"name"=>1
    ,"sex"=>1
    ,"password"=>1
    ,"marriedto"=>1
    ,"title"=>1
    ,"login"=>1
    ,"dragonkills"=>1
    ,"locked"=>1
    ,"loggedin"=>1
    ,"superuser"=>1
    ,"gems"=>1
    ,"hashorse"=>1
    ,"mountname"=>1
    ,"gentime"=>1
    ,"gentimecount"=>1
    ,"lastip"=>1
    ,"uniqueid"=>1
    ,"dragonpoints"=>1
    ,"laston"=>1
    ,"prefs"=>1
    ,"lastmotd"=>1
    ,"emailaddress"=>1
    ,"emailvalidation"=>1
    ,"gensize"=>1
    ,"bestdragonage"=>1
    ,"dragonage"=>1
    ,"donation"=>1
    ,"donationspent"=>1
    ,"donationconfig"=>1
    ,"bio"=>1
    ,"charm"=>1
    ,"banoverride"=>1 // jt
    ,"referer"=>1 //jt
    ,"refererawarded"=>1 //jt
    ,"lastwebvote"=>1
    ,"ctitle"=>1
    ,"beta"=>1
    ,"casa"=>1
    ,"reincarna"=>1
    ,"bonusattack"=>1
    ,"bonusdefence"=>1
    ,"oggetto"=>1
    ,"zaino"=>1
    ,"bonusfight"=>1
    ,"evil"=>1
    ,"bankrobbed"=>1
    ,"torneo"=>1
    ,"torneopoints"=>1
    ,"dio"=>1
    ,"carriera"=>1
    ,"punti_carriera"=>1
    ,"punti_generati"=>1
    ,"cambio_carriera"=>1
    ,"house"=>1
    ,"housekey"=>1
    ,"pietra"=>1
    ,"tempelgold"=>1
    ,"medhunt"=>1
    ,"medallion"=>1
    ,"medpoints"=>1
    ,"medfind"=>1
    ,"punch"=>1
    ,"geminhouse"=>1
    ,"id_drago"=>1
    ,"cavalcare_drago"=>1
    ,"turni_drago"=>1
    ,"fama3mesi"=>1
    ,"fama_anno"=>1
    ,"fama_mod"=>1
    ,"euro"=>1
    ,"manager"=>1
    ,"land"=>1
    ,"farms"=>1
    ,"slaves"=>1
    ,"minatore"=>1
    ,"suppliche"=>1
    ,"cittadino"=>1
    ,"compleanno"=>1
    ,"pvpkills"=>1
    ,"pvplost"=>1
    ,"messa"=>1
    ,"camuffa"=>1
    ,"premioindovinello"=>1
    ,"boscaiolo"=>1
    ,"falegname"=>1
    ,"lasthit"=>1
    ,"voodoouses"=>1
    ,"fagioli"=>1
    ,"fagiolitry"=>1
    ,"nocomment"=>1
    ,"stealth"=>1
    ,"gift"=>1
    ,"gdr"=>1
    ,"labsolved"=>1
    ,"sconnesso"=>1
    );
    if (date("m-d")!="12-25" AND date("m-d")!="02-14"){
      $nochange['gift'] = 0;
    }

    //Luke: modifica contatore fama
    $fama=100;
    if($session['user']['reincarna']==0)$fama = (3000-(100*$session['user']['level']))*$session['user']['fama_mod'];
    if($fama<100)$fama=100;
    $fama += intval($session['user']['minatore']*$session['user']['fama_mod']);
    $session['user']['fama3mesi'] += $fama;
    debuglog("guadagna $fama fama al passaggio di DK. Adesso ha ".$session['user']['fama3mesi']." punti fama");
    //Luke: fine mod fama

    //Excalibur: mod per invecchiamento drago
    $session['user']['dragonage'] = $session['user']['age'];
    if ($session['user']['dragonage'] <  $session['user']['bestdragonage'] ||
    $session['user']['bestdragonage'] == 0) {
        $session['user']['bestdragonage'] = $session['user']['dragonage'];
    }
    //Excalibur: fine mod draghi
    $etadk=$session['user']['age'];
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($nochange[$row['Field']]){

        }else{
            $session['user'][$row['Field']] = $row["Default"];
        }
    }
    $session['bufflist'] = array();

    $session['user']['gold'] = getsetting("newplayerstartgold",50);

    //Excalibur: Modifica per usura
    $session['user']['usura_arma']=999;
    $session['user']['usura_armatura']=999;
    //Excalibur: fine modifica usura

    $session['user']['gold']+=getsetting("newplayerstartgold",50)*$drakil;
    if ($session['user']['gold']>(6*getsetting("newplayerstartgold",50))){
        $session['user']['gold']=6*getsetting("newplayerstartgold",50);
    }

    //Excalibur: modifica per crescita drago
    if ($session['user']['id_drago'] != 0) {
        $invecchia=e_rand(1,2);
        $sqlseteta = "UPDATE draghi SET invecchia = invecchia+$invecchia WHERE id = ".$session['user']['id_drago'];
        $resultseteta=db_query($sqlseteta) or die(db_error(LINK));
        $sql = "SELECT crescita, aspetto, eta_drago, invecchia FROM draghi WHERE id = ".$session['user']['id_drago'];
        $result=db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result) or die(db_error(LINK));
        if (e_rand(1,100) < $row[invecchia]) {
            //print("Età Drago: ".$row['eta_drago']." Aspetto: ".$row['aspetto']." Crescita: ".$row['crescita']."<br>");
            if ($row['eta_drago'] != "antico" ){
                output("`2Congratulazioni !!! Il tuo drago è cresciuto ed è passato da ".$row['eta_drago']);
                //output("a ".($row['eta_drago']+1)."!!!!`n`n");
                $sqlseteta = "UPDATE draghi SET eta_drago = eta_drago+1 WHERE id = ".$session['user']['id_drago'];
                $resultseteta=db_query($sqlseteta) or die(db_error(LINK));
                $sql = "SELECT crescita, aspetto, eta_drago, invecchia FROM draghi WHERE id = ".$session['user']['id_drago'];
                $result=db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result) or die(db_error(LINK));
                output("a `&".$row['eta_drago']."`2!!!!`n`n");
                if ($row['aspetto'] == "Pessimo") {
                    $bonuseta = intval(1.1 * $row['crescita']);
                } else if ($row['aspetto'] == "Brutto") {
                    $bonuseta = intval(1.2 * $row['crescita']);
                } else if ($row['aspetto'] == "Normale") {
                    $bonuseta = intval(1.3 * $row['crescita']);
                } else if ($row['aspetto'] == "Buono") {
                    $bonuseta = intval(1.4 * $row['crescita']);
                } else if ($row['aspetto'] == "Ottimo") {
                    $bonuseta = intval(1.5 * $row['crescita']);
                }
                //print("Bonus età: ".$bonuseta."<br>");
                $bonusatt = intval(e_rand(($bonuseta/2), $bonuseta))+1;
                $bonusdif = intval(e_rand(($bonuseta/2), $bonuseta))+1;
                $bonussof = intval(e_rand(($bonuseta/2), $bonuseta))+1;
                $bonuscar = intval(e_rand(($bonuseta/2), $bonuseta))+1;
                $bonusvit = intval($bonuseta)+1;
                //print("Bonus età: ".$bonuseta." Bonus Att: ".$bonusatt." Bonus Dif: ".$bonusdif." Bonus Sof: ".$bonussof." Bonus Car: ".$bonuscar." Bonus Vita: ".$bonusvit);
                $sqlbonus = "UPDATE draghi SET
       attacco = attacco+".$bonusatt.",
       difesa = difesa+".$bonusdif.",
       danno_soffio = danno_soffio+".$bonussof.",
       carattere = carattere+".$bonuscar.",
       vita = vita+".$bonusvit.",
       vita_restante = vita
       WHERE id = ".$session['user']['id_drago'];
                $resultbonus=db_query($sqlbonus) or die(db_error(LINK));
                $sqlseteta = "UPDATE draghi SET invecchia = 1 WHERE id = ".$session['user']['id_drago'];
                $resultseteta=db_query($sqlseteta) or die(db_error(LINK));

            }else {
                output("`2Il tuo drago ha già raggiunto la massima età consentita e non può invecchiare oltre. Congratulazioni per la costanza con cui lo hai allevato. !!!`n`n");
            }
        }

    }
    if ($session['user']['cavalcare_drago'] > 0){
        $perd = e_rand(0,intval($session['user']['cavalcare_drago']/10));
        $perd = intval($perd);
        $session['user']['cavalcare_drago'] -= $perd;
        output("`n`2Purtroppo perdi `^$perd`2 Punti Cavalcare Drago.`n");
    }
    //Excalibur: fine modifica crescita drago e perdita turni drago

    if ($drakil <= 36) {
        if ($drakil > 5) {
            $session['user']['gems'] += ($drakil - 5);
        }
    } else {
        output("`n`n<big>`\$`bNon credi che sia giunto il momento di reincarnarsi ? Hai ucciso il `@Drago Verde`\$ $drakil volte, ",true);
        output("e non otterrai più gemme fino a che non ti sarai reincarnat".($session['user']['sex']?"a":"o")."!!`n`n`b`0</big>",true);
    }
    if ($flawless) {
        $session['user']['gold'] += 3*getsetting("newplayerstartgold",50);
        $session['user']['gems'] += 1;
    }
    $session['user']['maxhitpoints']+=$hpgain;
    //Nel caso il player abbia perso degli HP che lo fanno scendere sotto 10
    if ($session['user']['maxhitpoints'] < 10) $session['user']['maxhitpoints']=10;
    //fine modifica
    $session['user']['hitpoints']=$session['user']['maxhitpoints'];

    $newtitle = $titles[$drakil][$session['user']['sex']];
    if ($newtitle == ""){
        $newtitle = ($session['user']['sex']?"Dea":"Dio");
    }
    if (substr($session['user']['title'],0,1) == "`" AND (substr($session['user']['title'],2,6) == "PigPen" OR
    substr($session['user']['title'],2,3) == "Dea" OR substr($session['user']['title'],2,3) == "Dio")) {
        $newtitle = substr($session['user']['title'],0,2) . $newtitle;
        //}else if ($session['user']['title'] == "PigPen"){
        //    $title = $newtitle;
    }else if ($session['user']['title'] != $titles[($drakil - 1)][$session['user']['sex']]
    AND $session['user']['title'] != "PigPen" AND $session['user']['title'] != "Dio" AND $session['user']['title'] != "Dea") {
        $newtitle = substr($session['user']['title'],0,2) . $newtitle;
    }

    // Handle custom titles
    if ($session['user']['ctitle'] != "") {
        $session['user']['name'] = substr($session['user']['name'],0,(strlen($session['user']['name'])-strlen($session['user']['ctitle'])));
    }
    if ($session['user']['title']!=""){
        $n = $session['user']['name'];
        $x = strpos($n,$session['user']['title']);
        if ($x!==false){
            $regname=substr($n,$x+strlen($session['user']['title']));
            $session['user']['name'] = substr($n,0,$x).$newtitle.$regname;
            $session['user']['title'] = $newtitle;
        }else{
            $regname = $session['user']['name'];
            $session['user']['name'] = $newtitle." ".$session['user']['name'];
            $session['user']['title'] = $newtitle;
        }
    }else{
        $regname = $session['user']['name'];
        $session['user']['name'] = $newtitle." ".$session['user']['name'];
        $session['user']['title'] = $newtitle;
    }
    if ($session['user']['ctitle'] != "") {
        $session['user']['name'] .=$session['user']['ctitle'];
    }
    //    } else {
    //        $regname = substr($session['user']['name'], strlen($session['user']['ctitle']));
    //        $session['user']['title'] = $newtitle;
    //    }
    while(list($key,$val)=each($session['user']['dragonpoints'])){
        if ($val=="at"){
            $session['user']['attack']++;
        }
        if ($val=="de"){
            $session['user']['defence']++;
        }
    }
    savesetting("newdk",addslashes("$newtitle $regname"));
    $session['user']['laston']=date("Y-m-d H:i:s",strtotime(date("r")."-1 day"));
    $session['user']['quest']=0;
    if ($dio == 3){
        output("`n`n`2Ti ritrovi vicino ad un villaggio, non ricordi quasi nulla di chi sei o
        perché sei giunto lì. Hai solo la vaga sensazione di aver sentito parlare di un grosso `@Drago Verde `2che
        abita in quelle zone, che può rendere estremamente potente chi lo serve fedelmente, e l'idea non ti sembra
        per nulla peregrina.`n`nTi incammini istintivamente verso la `@Gilda del Drago`2, dove vieni accolto come
        un eroe, e anche se non ne sai bene il motivo, questi tuoi nuovi compagni si rivolgono a te con un titolo
        che precede il tuo nome.`n");
    }elseif ($dio == 2){
        output("`n`2Quando ti risvegli sei nella Grotta di `\$Karnak`2. Molte persone sono attorno a te, le
        riconosci solo molto vagamente. Parlano parecchio, sembra una messa. Le poche cose che capisci riguardano
        il fatto che sei morto per `\$Karnak`2, ma che non potevi morire e che ora sei più potente poiché
        l’Oscuro Supremo ti ha premiato. Non ci capisci molto, ma ti da molta soddisfazione sentirti chiamare con il
        tuo nuovo appellativo.");
    }elseif ($dio == 1){
        output("`n`2Quando riprendi i sensi, non sai quanto tempo sia passato, ma scopri di essere nella Chiesa di `^Sgrios`2.`n  
        Intorno a te alcuni chierici si stanno occupando alacremente delle tue ferite. L’immenso dolore che hai provato ha danneggiato 
        la tua memoria e ripensando alla tua avventura ne ricordi solamente la sofferenza e lo strazio della tua carne che brucia.
        `nUn guaritore ti informa che il sangue del Drago ha corroso i tuoi muscoli e ha estirpato dalla tua mente ricondi e conoscenza.
        Dovrai quindi riacquisire esperienza per ritornare ad essere il prode e valente combattente di prima.
        Come ringraziamento per aver eliminato un male così grande, la cittadinanza ti ha riconosciuto un nuovo rango e con questo
        titolo sarai conosciuto da tutti, grande tra i grandi.");
    }else{
        output("`n`n`2Ti svegli nel mezzo di un gruppetto di alberi. Non lontano senti i rumori di un villaggio.  ");
        output("Ricordi vagamente di essere un nuovo guerriero, e qualcosa a proposito di un pericoloso Drago Verde che infesta ");
        output("l'area. Decidi che vorresti farti un nome, magari confrontandoti un giorno con questa ");
        output("vile creatura.");
    }
    //Excalibur: modifica per fattorie
    if ($session['user']['manager'] > 0){
        $perdita = 0;
        if ($session['user']['superuser']>0) print("Giorni dal DK: ".$etadk);
        $duecento = e_rand(1,100);
        if ($etadk > 15){
            $etadk-=15;
            $duecento+=($etadk*2);
        }
        if ($duecento <= 40){
            $duecento /= 1.2;
            $schiavipersi = intval(($duecento/100)*$session['user']['slaves']);
            $session['user']['slaves'] -= $schiavipersi;
            if ($duecento < 1) $duecento = 1;
            output("`n`n`&Apophis`\$, il tuo Manager delle Fattorie, ti informa che durante la tua assenza il `^".intval($duecento)."% `\$degli schiavi, ");
            output("cioè `^$schiavipersi `\$schiavi, si è ribellato dando fuoco alle coltivazioni prima di fuggire.`n");
            debuglog("perde $schiavipersi schiavi al DK");
        }
        $cento = e_rand(1,100);
        if ($etadk > 15){
            $etadk-=15;
            $cento+=$etadk;
        }
        if ($cento > 80 AND $session['user']['farms'] > 0){
            output("`n`5Purtroppo hai perso una delle tue `@Fattorie`5, bruciata dal `\$fuoco `5propagatosi dalle coltivazioni.`n");
            debuglog("perde 1 fattoria al DK");
            $session['user']['farms'] --;
            if ($session['user']['slaves'] > ($session['user']['farms']*100)) {
                $schiavipersi = $session['user']['slaves'] - ($session['user']['farms']*100);
                $session['user']['slaves'] = ($session['user']['farms']*100);
                output("Inoltre le tue fattorie attualmente possono ospitare solo ".($session['user']['farms']*100)."schiavi, e quindi ");
                output("$schiavipersi di loro non avendo dove lavorare ti abbandonano!!`n`n");
                debuglog("perde anche $schiavipersi schiavi perchè non li può ospitare nelle fattorie restanti");
            }
        }
        //Possibilità perdita acri
        $n=min($session['user']['reincarna'], 12);
        if ($session['user']['land'] > ($acrireinc[$n]+500)) {
            $inf = (int)($session['user']['land']/50);
            $sup = (int)($session['user']['land']/20);
            $perdita = e_rand($inf,$sup);
            $perdita = max(100,$perdita);
        }else{
            $cento = e_rand(1,100);
            if ($drakil > 9) {
                $sfiga = min(($drakil/2), 25);
                if ($session['user']['land'] <= ($acrireinc[$n]/2)) $sfiga = 0;
                if ($cento < $sfiga) $perdita = 100;
            }
            if ($drakil > 39) {
                if ($drakil > 49) $cento /= 2;
                if ($cento <= 5) $perdita = max(100, floor($session['user']['land']/100));
            }
        }
        /*if ($drakil > 9) {
            $duecento = e_rand(1,200);
            $n=min($session['user']['reincarna'], 12);
            $sfiga = $drakil;
            if ($session['user']['land'] > $acrireinc[$n]) $sfiga *= 2;
            if ($session['user']['land'] <= ($acrireinc[$n]/2)) $sfiga = 0;
            if ($sfiga > 50) $sfiga = 50;
            if ($duecento <= $sfiga) $perdita = 100;
        }
        if ($drakil > 39) {
             $cento = e_rand(1,100);
             if ($drakil > 49) $cento /= 2;
             if ($cento <= 5) $perdita = max(100, (int)($session['user']['land']/100));
        }*/
        if ($perdita > 0) {
             output("`n`5Una guarnigione straniera ha invaso le tue proprietà, reclamandone il possesso sotto le insegne di Eithgym!`n`\$Hai perduto $perdita acri");
             $session['user']['land'] -= $perdita;
             debuglog("perde $perdita acri al dk");
             if ($session['user']['farms'] > ($session['user']['land']/100)) {
                 $delta = ceil($session['user']['farms']-($session['user']['land']/100));
                 if ($delta == 1) {
                     output(" e la fattoria costruita al loro interno");
                 } elseif ($delta > 1) {
                     output(" e le ".$delta." fattorie costruite al loro interno");
                 }
                 output(".`n");
                 $session['user']['farms'] -= $delta;
                 debuglog("perde anche $delta fattorie");
             }
             if ($session['user']['slaves'] > ($session['user']['farms']*100)) {
                 $schiavipersi = $session['user']['slaves'] - ($session['user']['farms']*100);
                 $session['user']['slaves'] = ($session['user']['farms']*100);
                 output("Inoltre le tue fattorie attualmente possono ospitare solo ".($session['user']['farms']*100)." schiavi, e quindi ");
                 output("$schiavipersi di loro non avendo dove lavorare ti abbandonano!!`n`n");
                 debuglog("perde anche $schiavipersi schiavi perchè non li può ospitare nelle fattorie restanti");
             }
         }
    }
    //Excalibur: fine fattorie
        
    addnews("`#".$regname." ha acquisito il titolo di `&".$session['user']['title']."`# per aver ucciso il `@Drago Verde`& `^".$session[user][dragonkills]."`# volte!");
    output("`n`n`2Ora sei noto come `&".$session['user']['name']."`2!!");
    output("`n`n`2Ora hai ucciso il `@Drago`2 `&$drakil`2 volte, ottieni quindi un piccolo premio offerto dai cittadini di Rafflingate riconoscenti per averli liberati dalla mostruosa creatura. `nMantieni tutti i punti ferita aggiuntivi che hai acquisito o acquistato nelle tue precedenti avventure.`n");
    $session['user']['charm']+=5;
    output("`2Guadagni inoltre `^5`2 punti di fascino e hai accresciuto la tua fama di guerrier".($session['user']['sex']?"a":"o")." per aver sconfitto il `@Drago Verde`2!`n");
    debuglog("ha ucciso il Drago ed inizia con ".$session['user']['gold']." pezzi d'oro e ".$session['user']['gems']." gemme");

    //Modifica Spells
    $sql = "DELETE FROM items WHERE owner=".$session[user][acctid]." AND class='Spell'";
    db_query($sql) or die(db_error(LINK));
    //Fine Modifica Spells

    //Modifica PvP Online
    $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
    db_query($sql) or die(db_error(LINK));
    //Fine modifica PvP Online

}

if ($_GET['op']=="run"){
    output("La coda della creatura blocca l'unica uscita della tana!");
    $_GET['op']="fight";
}
if ($_GET['op']=="fight" || $_GET['op']=="run"){
    $battle=true;
}
if ($battle){
    include("battle.php");
    if ($victory){
        $flawless = 0;
        if ($badguy['diddamage'] != 1) $flawless = 1;
        $badguy=array();
        $premio=1000;
        $session['user']['badguy']="";
        $session['user']['dragonkills']++;
        output("`&Con un possente colpo finale, `@Il Drago Verde`& emette un tremendo ruggito e cade ai tuoi piedi, morto infine.`n`n");
        if ($session['user']['dio'] != 0) {
			switch ($session['user']['dio']) {
	        	case 1:
	        		savesetting("puntisgrios", getsetting("puntisgrios",0)+$premio);
	        	break;
	        	case 2:
	        		savesetting("puntikarnak", getsetting("puntikarnak",0)+$premio);
	        	break;
	        	case 3:
	        		savesetting("puntidrago", getsetting("puntidrago",0)+$premio);
	        	break;
        	}
        }
        if (getsetting("indovinello","") == "sbloccato" AND $session['user']['dio'] != 0) {
            switch ($session['user']['dio']) {
                case 1:
                $sql = "SELECT area FROM custom WHERE area1 = 'frasesgrios'";
                $result = db_query ($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $frase = stripslashes($row['area']);
                $sql = "SELECT area FROM custom WHERE area1 = 'frasesgriosnascosta'";
                $result = db_query ($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $frase1 = stripslashes($row['area']);
                break;
                case 2:
                $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnak'";
                $result = db_query ($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $frase = stripslashes($row['area']);
                $sql = "SELECT area FROM custom WHERE area1 = 'frasekarnaknascosta'";
                $result = db_query ($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $frase1 = stripslashes($row['area']);
                break;
                case 3:
                $sql = "SELECT area FROM custom WHERE area1 = 'frasedrago'";
                $result = db_query ($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $frase = stripslashes($row['area']);
                $sql = "SELECT area FROM custom WHERE area1 = 'frasedragonascosta'";
                $result = db_query ($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $frase1 = stripslashes($row['area']);
                break;
            }
            while ($i != 1) {
                $lunghezza = strlen($frase);
                $estrattonumero = e_rand1(0,($lunghezza-1));
                $estrattolettera = substr($frase1, $estrattonumero, 1);
                $estrattooriginale = substr($frase, $estrattonumero, 1);
                if ($estrattolettera == "-") $i = 1;
            }
            if ($session['user']['superuser'] > 3) {
                ("Frase originale: ".$frase."<br>Frase da indovinare: ".$frase1."<br><br>");
                print("Lunghezza Frasi: ".$lunghezza."<br>Posizione carattere estratto: ".$estrattonumero."<br>Carattere estratto: ".$estrattolettera."<br>Carattere estratto originale: ".$estrattooriginale."<br><br>");
            }
            $nuovafrase = substr($frase1, 0, $estrattonumero).$estrattooriginale.substr($frase1, ($estrattonumero+1), ($lunghezza-$estrattonumero));
            output("`#Ai piedi del `@Drago Verde`# noti qualcosa. Ti avvicini per raccoglierla e scopri con stupore essere la lettera \"<big>`\$".$estrattooriginale."</big>`#\".`n",true);
            output("La raccogli e ti domandi a cosa possa mai servire ... ma le vie del Drago spesso sono oscure. La infili nella tua ");
            output("bisaccia per usi futuri.`n");
            switch ($session['user']['dio']) {
                case 1:
                $sql = "UPDATE custom SET area='".addslashes($nuovafrase)."', dDate=now() WHERE area1 = 'frasesgriosnascosta'";
                $result = db_query ($sql) or die(db_error(LINK));
                break;
                case 2:
                $sql = "UPDATE custom SET area='".addslashes($nuovafrase)."', dDate=now() WHERE area1 = 'frasekarnaknascosta'";
                $result = db_query ($sql) or die(db_error(LINK));
                break;
                case 3:
                $sql = "UPDATE custom SET area='".addslashes($nuovafrase)."', dDate=now() WHERE area1 = 'frasedragonascosta'";
                $result = db_query ($sql) or die(db_error(LINK));
                break;
            }
            if ($session['user']['superuser'] > 3) {
                print("Nuova frase da indovinare: ".$nuovafrase."<br><br>");
            }
        }
        $session['user']['voodoouses'] -= 2;
        if ($session['user']['voodoouses'] < 0) $session['user']['voodoouses'] = 0;
        addnews("`&".$session['user']['name']." ha ucciso la creatura nota come `@Il Drago Verde`&.  Gente del paese, gioite!");
        addnav("Continua","dragon.php?op=prologue1&flawless=$flawless");
    }else{
        if($defeat){
            $session['user']['helpmaster'] += 1;
            addnav("Notizie Giornaliere","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session['user']['sex']?"her":"him"),$taunt['taunt']);
            $taunt = str_replace("%o",($session['user']['sex']?"she":"he"),$taunt);
            $taunt = str_replace("%p",($session['user']['sex']?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);
            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);
            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);
            $taunt = str_replace("%w",$session['user']['name'],$taunt);

            addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." quando ha incontrato `@Il Drago Verde`5!!!  Le sue ossa ora pavimentano l'ingresso della caverna, proprio come quelle di chi ci ha provato prima.`n$taunt");
            $session['user']['alive']=false;
            debuglog("ha perso ".$session['user']['gold']." quando è stato ucciso dal Drago Verde");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['badguy']="";
            output("`b`&Sei stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." da `%".$badguy['creaturename']."`&!!!`n");
            output("`4Tutto l'oro che avevi con te è andato perduto!`n");
            output("Potrai combattere di nuovo domani.");

            page_footer();
        }else{
            fightnav(true,false);
        }
    }
}
page_footer();
?>