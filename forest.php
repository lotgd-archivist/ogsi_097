<?php
require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));
$dio = $session['user']['dio'];
$balance = getsetting("creaturebalance", 0.33);
if ($session['user']['reincarna'] == 1) {
    $balance += 0.07;
}
// Handle updating any commentary that might be around.
addcommentary();
if ($session['user']['superuser'] > 0){
   print_r($_GET);
   echo "<br>";
   echo "Special: ".$session['user']['specialinc'];
}
//savesetting("creaturebalance","0.33");
if ($_GET['op']=="darkhorse"){
    $_GET['op']="";
    $session['user']['specialinc']="darkhorse.php";
}

//Sook, ripristino specialinc per newday negli eventi speciali foresta
if (($_GET['op']=="tavern" || $_GET['op']=="oldman") &&  $session['user']['specialinc']==""){
    $session['user']['specialinc']="darkhorse.php";
}
if ($_GET['op']=="entrataverna" &&  $session['user']['specialinc']==""){
    $session['user']['specialinc'] = "scagliaverde.php";
}

$fight = false;
page_header("La Foresta");
$session['user']['locazione'] = 129;
// Excalibur
// modifica per consentire accesso agli eventi speciali
// contenuti nella cartella "speciali" ai tester
// if ($session[user][superuser]>1 && $_GET[specialinc]!=""){
if ($session['user']['superuser']>=1 && $_GET['specialinc']!=""){
  $session['user']['specialinc'] = $_GET['specialinc'];
}
if ($session['user']['specialinc']!=""){
  //echo "$x including special/".$session[user][specialinc];

    output("`^`c`bQualcosa di speciale!`c`b`0");
    $specialinc = $session['user']['specialinc'];
    $session['user']['specialinc'] = "";
    include("special/".$specialinc);
    if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0) {
        forest(true);
        //output(serialize($session['allowednavs']));
    }
    page_footer();
    exit();
}
if ($_GET['op']=="run" AND $session['user']['race']==12){
    if (e_rand()%2 == 0){
        output ("`c`b`&Essendo un `@Satiro`& sei riuscito a sfuggire al nemico!`0`b`c`n");
        $_GET['op']="";
    }else{
        output("`c`b`\$Non riesci a sfuggire al nemico!`0`b`c");
    }

}elseif ($_GET['op']=="run" AND $session['user']['race']!=12){
    if(e_rand()%3 == 0){
        output ("`c`b`&Sei riuscito a sfuggire al nemico!`0`b`c`n");
        $_GET['op']="";
    }else{
        output("`c`b`\$Non riesci a sfuggire al nemico!`0`b`c");
    }
}
if ($_GET['op']=="dragon"){
    addnav("Entra nella caverna","dragon.php");
    addnav("Scappa come un bambino","inn.php");
    if ($dio == 3){
       output("`2Finalmente ti senti pronto per compiere ciò per cui ti sei addestrato così duramente,
       ciò che la tua Gilda ti ha insegnato, ciò che hai fatto divenire il tuo scopo per la vita:
       affrontare il `@Grande Drago Verde`2. Sei di fronte alla grotta in cui abita Colui che adori al
       pari di una divinità.`n`n");
    }elseif ($dio == 2){
       output("`4Finalmente sei giunto.`nOgni tuo muscolo, ogni tuo osso, ogni tua singola goccia di sangue
       reclama la vita del Grande Traditore per l’Oscuro Signore Karnak.`nRimembri ancora ciò che ti hanno
       raccontato ...`n`n\"`8Tanto tempo fa, forse secoli, millenni, se non addirittura di più, una creatura
       più di tutte attrasse il malefico sguardo del nostro Immondo Signore: il `@Drago Verde`8.`nLa terribile
       bestia mieteva vittime umane a decine ogni giorno, nessuno, nemmeno il più spietato tra i Demoni inondava
       con tante sangue mortale il terreno, e la sua ira era quanto di più terribile si potesse immaginare.`n
       `\$Karnak `8lo vide, e subito comprese che la Belva sarebbe stato il più grande tra i suoi servi.`n
       Così inviò al suo cospetto i demoni suoi servitori.`nIl Primo giorno, un Portatore di Morte si avvicinò
       al Drago, chiedendogli Sangue per il suo Battesimo nella fede di `\$Karnak`8. La Bestia brindò con il sangue
       del Portatore, ma `\$Karnak `8ancora lo voleva.`nIl Secondo giorno, il Falciatore di Anime in persona chiese
       al Drago teschi per il trono di `\$Karnak`8, perché Egli lo perdonasse. Il Primo tra i Draghi aggiunse le ossa
       del Falciatore alle altre nella sua tana, ma `\$Karnak`8 ancora lo voleva.`nIl Terzo giorno, un vecchio si
       avvicinò al Drago, chiedendogli perché rifiutasse con così tanta ostinazione quello che era evidentemente
       il suo destino.`n`n- `^Sono la più potente tra le creature, perché mai dovrei inchinarmi ad un altro?`n`n`8fu
       la risposta.`nAllora `\$Karnak`8 si rivelò in tutta la sua maestosità, abbandonando il suo travestimento.`n
       - `\$Poiché mi rifiuti, io ti maledico. Sarai odiato da chiunque, e persino chi ti venera e ti rispetta
       desidererà ucciderti. Non vorrai uccidere, soffrirai per ogni teschio che rotolerà per mano tua, ma non
       potrai farne a meno. Non morirai mai, e morirai infinite volte, questa è la tua condanna per aver respinto
       la proposta dell’onnipotente Karnak!`4\"`n`nMemore di questo insegnamento, attendi davanti alla grotta,
       il cui esterno è ricoperto di corpi sventrati e bruciati, con un odore di sangue stantio che satura l’aria.`n
       Sarai pronto per portare avanti la maledizione che il Tuo Signore ha imposto su quello che poteva essere
       il Primo tra i suoi servi?`n`n");
    }elseif($dio == 1){
       output("`2Finalmente il gran giorno è giunto.`nHai passato tutta la mattinata a pregare, ti sei nutrito ed
       hai preparato i tuoi oggetti.`nTu sarai la mano di `^Sgrios`2, e devi rispecchiare la Sua purezza. La tua
       arma brilla come se fosse stata appena forgiata, la tua armatura sembra quasi non aver mai ricevuto un sol
       colpo.`nLa volontà di `^Sgrios `2è chiara: il `@Drago Verde `2ha mietuto troppe vittime e tu non puoi
       permettere che questo numero continui ad aumentare.`nI seguaci del maligno `\$Karnak `2e quei folli che
       adorano il Drago ti considerano debole, poiché adori una divinità fondamentalmente buona ... ma loro non
       sanno, loro non hanno mai conosciuto la sua Divina Collera.`nGiustizia, questo è ciò che `^Sgrios `2vuole.
       E in questo caso, il manto bianco ed immacolato di `^Sgrios `2diverrà rosso come il sangue della Bestia
       e nero come la morte che la aspetta.`n`nOra, innanzi alla tana dell’immonda Belva, ti chiedi se ce la farai.
       La tua fede è forte, ma la tua determinazione lo sarà altrettanto?`n`nTi guardi attorno ... non c’è vita nei
       paraggi. Da questo terreno bruciato nulla potrà mai nascere. Ci sono tracce di creature viventi, ma devono
       essere fuggite da tempo. Nidi abbandonati sulle cime di alberi parzialmente carbonizzati e tane, palesemente
       vuote, un tempo nascoste dall’erba alta, fanno da contorno a quello che sembra un cimitero a cielo aperto.`n
       Scheletri di ogni razza che conosci sono qui davanti: umani, elfi, troll, nani ... a sinistra un centauro ed
       un paio di teschi giganteschi, a destra i resti di creature che nemmeno conosci ...`n`nIl pensiero che formuli
       è che non dovrebbero chiamarlo né Drago, né Bestia, né uno qualunque dei nomi con cui hai sentito riferirsi a
       lui, ma semplicemente Mostro.`nLa collera del Giusto è necessaria, il sangue che questa immonda creatura ha
       versato è troppo, e non potrà essere lavato che con altro sangue.`n`nAncora un minuto per riflettere ... Sarai
       in grado di portare la Sua Divina Luce in questo luogo di oscurità, o diverrai semplicemente un nuovo cadavere
       in questo blasfemo cimitero?`n`n");
    }
    output("`\$Ti avvicini all'oscuro ingresso della caverna nella foresta, sebbene");
    output(" gli alberi siano ridotti a ceppi bruciati per un centinaio di metri intorno ad essa.  ");
    output("Un sottile filo di fumo esce dalla caverna, e viene spazzato via ");
    output("da un improvviso soffio di vento freddo. L'imboccatura della caverna attende a circa cinque ");
    output("metri dalla foresta, scavata nel fianco di un picco, con dei detriti che formano una ");
    output("rampa conica fino all'apertura. Stalattiti e stalagmiti presso l'entrata ");
    output("colpiscono la tua immaginazione facendoti sembrare l'apertura ");
    output("la bocca di un immenso mostro.  ");
    output("`n`nTi avvicini con cautela all'ingresso della caverna, e nel farlo senti, ");
    output("o piuttosto percepisci un rombo sordo che dura circa trenta secondi, prima di cessare ");
    output("con un soffio di aria sulfurea che spazza la caverna. Il suono ricomincia e si ferma ");
    output("di nuovo, con un ritmo regolare.  ");
    output("`n`nScali la pila di detriti fino alla bocca della caverna, i tuoi piedi calpestano rumorosamente ");
    output("gli apparenti resti dei precedenti eroi, o forse degli antipasti.`n`n");
    if ($dio == 3){
        output("`2Ti tremano le gambe ... non sai se sarai pronto, se ti mostrerai all'altezza... Il `@Grande Drago
        `2ti ucciderà se non ti dimostrerai degno. Sei consapevole che per Lui tutto questo è un gioco e nulla più,
        che ti sfrutta, come sfrutta tutti gli abitanti della città per vincere la noia, ma questo non è sufficiente
        a farti vincere la paura, anzi, tremi ancor di più nel pensare quale sia la Sua vera potenza, se non si
        trattenesse per evitare di dilaniare in un sol colpo i miseri corpi mortali di chi lo affronta e far durare
        un po' di più questo suo sadico divertimento.`n`nIl `@Grande Drago Verde `2è molto generoso con chi riesce a
        divertirlo, lo sai, ed è per questo che ora sei qua. Questo ti rincuora, ma sull'altro piatto della bilancia
        c'è la morte certa, nel caso ti dimostrassi debole.`nUn dubbio che ti tiene per qualche interminabile istante
        fuori dalla grotta, indeciso sul da farsi.`nDopo qualche minuto che a te pare durare secoli, prendi infine
        la tua decisione, anche se ");
    }
    output("Ogni fibra del tuo corpo vorrebbe correre, e velocemente, verso il tepore amichevole ");
    if ($session['user']['housekey']){
        output("della tua casa");
    }else {
        output("della locanda");
    }
    if ($session['user']['marriedto']){
        if ($session['user']['marriedto']==4294967295){
            output(", e l'ancor più cald".($session['user']['sex']?"o Seth":"a Violet").".  Cosa fai?");
        }else{
            $sql = "SELECT name FROM accounts WHERE acctid='".$session['user']['marriedto']."'";
            $result = db_query($sql);
            $partner = db_fetch_assoc($result);
            output(", e l'ancor più cald".($session['user']['sex']?"o":"a")." `@".$partner['name'].".`2`n`nCosa fai?");
        }
    }else{
        output("`2`n`nCosa fai?");
    }
    $session['user']['seendragon']=1;
}
if ($_GET['op']=="search"){
    checkday();
  if ($session['user']['turns']<=0){
    output("`\$`bSei troppo stanco per continuare a cercare oggi. Forse domani avrai più energia.`b`0");
    $_GET['op']="";
  }else{
      $session['user']['drunkenness']=round($session['user']['drunkenness']*.9,0);

    $specialtychance = e_rand1()%7;
    if ($_GET['type']=="event") $specialtychance=0;
    if ($specialtychance==0){
        output("`^`c`bQualcosa di Speciale!`c`b`0");
            if ($handle = opendir("special")){
                $events = array();
                while (false !== ($file = readdir($handle))){
                  if (strpos($file,".php")>0){
                      // Skip the darkhorse if the horse knows the way
                      if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0 && strpos($file, "darkhorse") !== false) continue;
                      // Saltiamo Merk se il personaggio non ha almeno 4 reincarnazioni
                      if ($session['user']['reincarna'] < 4 && strpos($file, "merk") !== false) continue;
                      array_push($events,$file);
                   }
                }
                $tot=0; //calcolo somma dei totali degli eventi per poi effettuare il sorteggio
                for($i=0;$i<(count($events));$i++) {
                   $sqlch="SELECT foresta FROM peso_eventi WHERE nomefile='".$events[$i]."' ";
                   $resultch = db_query($sqlch) or die(db_error(LINK));
                   $rowch = db_fetch_assoc($resultch);
                   $tot += $rowch[foresta];
                }
                //srand ((double) microtime() * 10000000);
//              $x = e_rand(0,count($events)-1);
//Sook, diversificazione delle probabilità dei vari eventi
/*              $sqltotal="SELECT sum(foresta) as tot FROM peso_eventi";
                $resulttotal = db_query($sqltotal) or die(db_error(LINK));
                $rowtotal = db_fetch_assoc($resulttotal);*/
                if (count($events)==0){
                    output("`b`@Ahi, il tuo Amministratore ha deciso che non ti è permesso avere eventi speciali. Prenditela con lui, non con me.");
                }else{
                    $x=e_rand(1,$tot); //numero estratto per la selezione evento
                    $sqlevents="SELECT nomefile, foresta FROM peso_eventi ORDER BY rand()";
                    $resultevents = db_query($sqlevents) or die(db_error(LINK));
                    $j=0; //indice pesato per la ricerca dell'evento estratto
                    while ($j<$x) {
                        $rowevents = db_fetch_assoc($resultevents);
                        $evento = $rowevents[nomefile];
                        $k=0; //controllo che l'evento possa essere scelto
                        for ($l=0;$l<(count($events));$l++) {
                            if ($evento == $events[$l]) $k=1; //ok, l'evento è stato trovato ed il giocatore può averlo
                        }
                        if ($k==1) $j += $rowevents[foresta]; //incremento indice peso
                    }
                    $y = $_GET['op'];
                    //$YY = $_GET['op'];
                    //$_GET['op']="";
                    $_GET['op']="";
                    //include("special/".$events[$x]);
                  	//@(include("special/".$events[$x])) or redirect("forest.php");
                    @(include("special/".$evento)) or redirect("forest.php"); //si usa un altro indice
                    $_GET['op']=$y;
                    //$_GET['op'] = $yy;
                }
            }else{
              output("`c`b`\$ERRORE!!!`b`c`&Non riesco ad aprire gli eventi speciali! Per favore avverti l'Amministratore!!");
            }
        if ($nav=="") forest(true);
    }else{
      $session['user']['turns']--;
        $battle=true;
            if (e_rand(0,2)==1){
                $plev = (e_rand(1,5)==1?1:0);
                $nlev = (e_rand(1,3)==1?1:0);
            }else{
              $plev=0;
                $nlev=0;
            }
            if ($_GET['type']=="slum"){
              $nlev++;
                output("`\$Ti dirigi verso l'area della foresta che sai contenere creature con cui ti senti più a tuo agio.`0`n");
            }
            if ($_GET['type']=="thrill"){
              $plev++;
                output("`0Ti dirigi verso l'area della foresta che sai essere popolata dalle creature dei tuoi incubi, sperando di trovarne una ferita.`0`n`n");
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
            $badguy['playerstarthp']=$session['user']['hitpoints'];
            $dk = 0;
            while(list($key, $val)=each($session['user']['dragonpoints'])) {
                if ($val=="at" || $val=="de") $dk++;
            }
            $dk += (int)(($session['user']['maxhitpoints']-($session['user']['level']*10))/5);
            if (!$beta) $dk = round($dk * 0.25, 0);
            else $dk = round($dk,0);
            //Modifica di Excalibur per tener conto dei PA e PD PERMANENTI
            $bnsatk=(int)(($session['user']['bonusattack']/2.5)+0.99);
            $bnsdef=(int)(($session['user']['bonusdefence']/2.5)+0.99);
            // Fine modifica ... vedi anche sotto
            $atkflux = e_rand(0, ($dk+$bnsatk));
            if ($beta) $atkflux = min($atkflux, round($dk/4));
            $defflux = e_rand(0, ($dk-$atkflux+$bnsdef));

            if ($session['user']['acctid']==679) { //Modifica per limitare i perfect di Labat
               $atkflux += 10;
               $defflux += 8;
            }

            if ($beta) $defflux = min($defflux, round($dk/4));
            $hpflux = ($dk + $bnsatk + $bnsdef - ($atkflux+$defflux)) * 5;
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
            $cleanchance=e_rand(0,4);
            if ($session['user']['superuser'] >= 3) output("Debug: Chance di sporcarsi $cleanchance`n");
            if ($cleanchance == 0) $session['user']['clean'] += 2;
            if ($session['user']['superuser'] >= 3) output("`#Debug: badguy gets `%$dk`# dk points, `%+$atkflux`# attack, `%+$defflux`# defense, +`%$hpflux`# hitpoints.`n");
            if ($beta) {
                if ($session['user']['superuser']>=3){
                    output("Debug: $dk dragon points.`n");
                    output("Debug: +$atkflux attack.`n");
                    output("Debug: +$defflux defense.`n");
                    output("Debug: +$hpflux health.`n");
                }
            }
        }
    }
}
if ($_GET['op']=="fight" || $_GET['op']=="run"){
    $battle=true;
}
if ($battle){
  include("battle.php");
//  output(serialize($badguy));
    if ($victory){
        if (getsetting("dropmingold",0)){
            $badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);
        }else{
            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);
        }
        $expbonus = round(
            ($badguy['creatureexp'] *
                (1 + .25 *
                    ($badguy['creaturelevel']-$session['user']['level'])
                )
            ) - $badguy['creatureexp'],0
        );
        output("`n`b`&".$badguy['creaturelose']."`0`b`n");
        output("`n`b`\$Hai ucciso ".$badguy['creaturename']."!`0`b`n");
        output("`#Ricevi `^".$badguy['creaturegold']."`# pezzi d'oro!`n");
        if ($badguy['creaturegold']) {
            debuglog("riceve ".$badguy['creaturegold']." pezzi d'oro per l'uccisione di una creatura.");
        }
        if (e_rand(1,25) == 1) {
          output("`&Trovi UNA GEMMA!`n`#");
          $session['user']['gems']++;
          debuglog("trova una gemma uccidendo un mostro.");
        }

        //Modifica Spells
        if (e_rand(1,50)==3 AND $session['user']['reincarna']>1){ // find spell
            $sql="SELECT * FROM items WHERE class='Spell.Prot' ORDER BY rand(".e_rand().") LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result);
            if ($row2['name']){
                $row2['description'].=" (used)";
                $row2['value1']=e_rand(1,$row2['value2']);
                $row2['gold']=$row2['gold']*(($row2['value1']+1)/($row2['value2']+1));
                $sql="INSERT INTO items(name,class,owner,gold,gems,value1,value2,hvalue,description,buff) VALUES ('".addslashes($row2[name])."','Spell',".$session[user][acctid].",$row2[gold],0,$row2[value1],$row2[value2],$row2[hvalue],'".addslashes($row2[description])."','".addslashes($row2[buff])."')";
                db_query($sql) or die(sql_error($sql));
                output("`n`2Perquisendo il corpo di ".$badguy['creaturename']." `2trovi `&".$row2['name']."`2! (".$row2['description'].")`n`n`#");
            }
        }
        if ($expbonus>0){
          output("`#***Vista la difficoltà di questa battaglia, ricevi in più `^$expbonus`# esperienza! `n(".$badguy['creatureexp']." + ".abs($expbonus)." = ".($badguy['creatureexp']+$expbonus).") ");
        }else if ($expbonus<0){
          output("`#***Data la facilità di questa battaglia, vieni penalizzato di `^".abs($expbonus)."`# esperienza! `n(".$badguy['creatureexp']." - ".abs($expbonus)." = ".($badguy['creatureexp']+$expbonus).") ");
        }
        //Usura armi, armatura, oggetti
        if(!(($session['user']['weapon']=='Pugni') OR ($session['user']['weapon']=='Pugni Benedetta'))) $session['user']['usura_arma']-= e_rand(0,4);
        if($session['user']['armor']!='T-Shirt') $session['user']['usura_armatura']-=e_rand(0,4);
        if ($session['user']['oggetto']!=0) {
            $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
            $resulto = db_query($sqlo) or die(db_error(LINK));
            $rowo = db_fetch_assoc($resulto);
            if ($rowo['usuramax'] > 0 AND ($rowo['attack_help']>0 OR $rowo['defence_help']>0)) {
                if ($rowo[usura] == 1) {
                    output("`n`n`bIl tuo ".$rowo['nome']." si è spaccato in più pezzi, adesso non hai più un oggetto in mano...`b");
                    $session['user']['attack'] -= $rowo['attack_help'];
                    $session['user']['defence'] -= $rowo['defence_help'];
                    $session['user']['bonusattack'] -= $rowo['attack_help'];
                    $session['user']['bonusdefence'] -= $rowo['defence_help'];
                    if ($rowo['usuramagica']!=0) $session['user']['maxhitpoints'] -= $rowo['hp_help'];
                    if ($rowo['usuramagica']!=0) $session['user']['hitpoints'] -= $rowo['hp_help'];
                    if ($rowo['usuramagica']!=0) $session['user']['playerfights'] -= $rowo['pvp_help'];
                    if ($rowo['usuramagica']!=0) $session['user']['turns'] = $session['user']['turns'] - $rowo['turns_help'];
                    if ($rowo['usuramagica']!=0) $session['user']['bonusfight'] -= $rowo['turns_help'];
                    debuglog("ha rotto ".$rowo[nome]." per eccessiva usura");
                    $sql = "DELETE FROM oggetti WHERE id_oggetti='{rowo[id_oggetti]}'";
                    db_query($sql) or die(db_error(LINK));
                    $session['user']['oggetto']=0;
                } elseif (e_rand(1,3)==1) {
                    $sqlu = "UPDATE oggetti SET usura = '".($rowo['usura']-1)."' WHERE id_oggetti = '".$session['user']['oggetto']."'";
                    db_query($sqlu) or die(db_error($link));
                }
            }
        }
//fine usura
        output("Ricevi `^".($badguy['creatureexp']+$expbonus)."`# punti di esperienza in totale!`n`0");
        $session['user']['gold']+=$badguy['creaturegold'];
        $session['user']['experience']+=($badguy['creatureexp']+$expbonus);
        $creaturelevel = $badguy['creaturelevel'];
        $_GET['op']="";
        //if ($session[user][hitpoints] == $session[user][maxhitpoints]){
        if ($badguy['diddamage']!=1){
            if ($session['user']['level']>=getsetting("lowslumlevel",4) AND $session['user']['level']<=$creaturelevel){
                output("`b`c`&~~ Combattimento Perfetto! ~~`\$`n`bGuadagni un turno extra!`c`0`n");
                $session['user']['turns']++;
                $session['user']['perfect']++;
            }elseif ($session['user']['level']>=getsetting("lowslumlevel",4)) {
                output("`b`c`&~~ Combattimento Perfetto! ~~`b`\$`nSe fosse stato più difficile avresti guadagnato un turno extra.`c`n`0");
            }else{
                output("`b`c`&~~ Combattimento Perfetto! ~~`b`c`n`0");
            }
        }
        $dontdisplayforestmessage=true;
        addhistory(($badguy['playerstarthp']-$session['user']['hitpoints'])/max($session['user']['maxhitpoints'],$badguy['playerstarthp']));
        $badguy=array();
    }else{
        if($defeat){
            addnav("Notizie quotidiane","news.php");
            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";

            $result = db_query($sql) or die(db_error(LINK));
            $taunt = db_fetch_assoc($result);
            $taunt = str_replace("%s",($session[user][sex]?"her":"him"),$taunt[taunt]);
            $taunt = str_replace("%o",($session[user][sex]?"she":"he"),$taunt);
            $taunt = str_replace("%p",($session[user][sex]?"her":"his"),$taunt);
            $taunt = str_replace("%x",($session[user][weapon]),$taunt);
            $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
            $taunt = str_replace("%W",$badguy[creaturename],$taunt);
            $taunt = str_replace("%w",$session[user][name],$taunt);
            addhistory(1);
            addnews("`%".$session['user']['name']."`5 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." nella foresta da ".$badguy['creaturename']."`n$taunt");
            $session['user']['alive']=false;
            debuglog("ha perso ".$session['user']['gold']." pezzi d'oro quando è morto nella foresta");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.9,0);
            $session['user']['badguy']="";
            output("`b`&Sei stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." da `%".$badguy['creaturename']."`&!!!`n");
            output("`4Tutto l'oro che avevi con te è andato perduto!`n");
            output("`1Hai perso il 10% della tua esperienza!`n");
            output("`&Potrai combattere di nuovo domani.`b");

            page_footer();
        }else{
          fightnav();
        }
    }
}

if ($_GET['op']==""){
    // Need to pass the variable here so that we show the forest message
    // sometimes, but not others.
    forest($dontdisplayforestmessage);
}

page_footer();

function addhistory($value){
/*
    global $session,$balance;
    $history = unserialize($session['user']['history']);
    $historycount=50;
    for ($x=0;$x<$historycount;$x++){
        if (!isset($history[$x])) $history[$x]=$balance;
    }
    array_shift($history);
    array_push($history,$value);
    $history = array_values($history);
    for ($x=0;$x<$historycount;$x++){
        $history[$x] = round($history[$x],4);
        if ($session['user']['superuser']>=3) output("History: {$history[$x]}`n");
    }
    $session['user']['history']=serialize($history);
 */
}
?>