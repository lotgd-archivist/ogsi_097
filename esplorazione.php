<?php
require_once("common.php");
require_once("common2.php");

if($_GET['luogo']>0) {
    //determinazione nome del luogo
    $sql = "SELECT nome FROM mappe_foresta_luoghi WHERE id=".$_GET['luogo'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $luogo=$row['nome'];

    //determinazione tipo di premio
    $sql2 = "SELECT premio FROM mappe_foresta_player WHERE luogo='".$_GET['luogo']."' AND acctid=".$session['user']['acctid'];
    $result2 = db_query($sql2) or die(db_error(LINK));
    $row2 = db_fetch_assoc($result2);
    $premio = $row2['premio'];
}

page_header($row['nome']);
$session['user']['locazione'] = 122;

output("`n`@Seguendo le indicazioni della la mappa acquistata da `(Merk`@ sei riuscito a raggiungere questa località segreta, conosciuta solo da pochi.`n`n");
output("Inizi subito a perlustrare il luogo, in cerca di fortune, e...`n`n`n");

if ($_GET['op']=="") {
    switch ($premio) {
        case "":
            output("prosegui ad esplorare `0$luogo`@ per parecchio tempo, ma non trovi nulla di interessante o qualche anfratto che valga la pena esaminare con molta più attenzione.`n");
            output("Infine ti stanchi di continuare in quella che è solo una inutile perdita di tempo e decidi di tornare nella foresta senza ulteriori indugi.");
            $session['user']['turns'] -= 2;
            debuglog("esplora $luogo ma non trova niente");
            addnav("Torna nella foresta","forest.php");
        break;
        case "oro":
            $oro=e_rand((5*$session['user']['level']),(100*$session['user']['level']));
            output("Ad un certo punto, ti imbatti in uno zaino. Lo apri e trovi `^$oro pezzi d'oro.`@`n");
            output("Li raccogli e rapidamente te li metti nelle tue capienti tasche, prima di ritornare nella foresta.");
            $session['user']['gold'] += $oro;
            $session['user']['turns'] -= 2;
            debuglog("trova $oro pezzi d'oro esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "gemme":
            $gemme=e_rand(0,4);
            if ($gemme!=0) {
                output("Ad un certo punto, ti accorgi di aver calpestato un sacchetto. Lo raccogli ed aprendolo e trovi `&$gemme gemm".($gemme==1?"a":"e").".`@`n");
                output("L".($gemme==1?"a":"e")." prendi e te ne impossessi, prima di tornare nella foresta.");
            } else {
                output("Ad un certo punto, ti accorgi di aver calpestato un sacchetto. Raccogliendolo però ti accorgi che è vuoto, con un pò di delusione decidi di tornare nella foresta.`n");
            }
            $session['user']['gems'] += $gemme;
            $session['user']['turns'] -= 2;
            debuglog("trova $gemme gemme esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "tesoro":
            $gemme=e_rand(5,10);
            $oro=e_rand((1000*$session['user']['level']),(3000*$session['user']['level']));
            output("Mentre cammini, ti ritrovi sopra ad una grande `b`4X`b`@ tracciata sul terreno. Inizi a scavare nel punto indicato e, dopo pochi colpi di badile, trovi un grosso baule. Piano piano riesci a disseppellire il pesante baule, ");
            output("ma l'operazione di scavo ti costa `#2`@ turni di combattimento aggiuntivi. La perdita di tempo è però ben ripagata, aprendo il baule trovi infatti `&$gemme gemme `@e `^$oro pezzi d'oro!");
            $session['user']['gold'] += $oro;
            $session['user']['gems'] += $gemme;
            $session['user']['turns'] -= 4;
            addnews("`V".$session['user']['name']."`V ha scoperto un tesoro esplorando `&$luogo");
            debuglog("trova $gemme gemme e $oro pezzi d'oro in un baule esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "hpmax":
            output("Ad un certo punto, noti davanti ai tuoi piedi una pozione abbandonata. La bevi, e scopri che i tuoi `#hp`@ massimi sono aumentati di `#1`@.`n");
            output("Liet".($session['user']['sex']?"a":"o")." per l'effetto benefico della pozione da te ingerita, decidi di avviarti verso la foresta.");
            $session['user']['maxhitpoints'] ++;
            $session['user']['hitpoints'] ++;
            $session['user']['turns'] -= 2;
            debuglog("guadagna 1 hp permanente esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "hp":
            output("Mentre stai esplorando `0$luogo`@ trovi una fiala contenente una pozione. Senza paura la bevi, e scopri che i tuoi hitpoints sono temporaneamente aumentati del `#5%`@.`n");
            output("Seppur si tratta di un beneficio temporaneo sei content".($session['user']['sex']?"a":"o")." per l'effetto positivo della pozione che ti ha rinvigorito e ritorni nella foresta pront".($session['user']['sex']?"a":"o")."  ad affrontare nuove avventure.");
            $session['user']['hitpoints']=round($session['user']['hitpoints']*1.05,0);
            $session['user']['turns'] -= 2;
            debuglog("guadagna 1 hp permanente esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "attacco": //evento unico
            output("Esplorando `0$luogo`@ trovi in un piccolo anfratto una pozione abbandonata. Vincendo il timore iniziale la bevi tutta d'un fiato, e scopri con piacere che la tua capacità in `&attacco`@ è aumentata `b`@permanentemente`@ `bdi `#1`@ punto.`n");
            output("Contentissim".($session['user']['sex']?"a":"o")." per l'effetto decisamente tonificante della pozione appena ingerita, decidi di ritornare nella foresta.");
            $session['user']['attack'] ++ ;
            $session['user']['bonusattack'] ++ ;
            $session['user']['turns'] -= 2;
            debuglog("guadagna 1 punto attacco permanente esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "difesa": //evento unico
            output("Esplorando `0$luogo`@ trovi in un piccolo anfratto una pozione abbandonata. Vincendo il timore iniziale la bevi tutta d'un fiato, e scopri con piacere che la tua capacità in `&difesa`@ è aumentata `b`@permanentemente`@ `bdi `#1`@ punto.`n");
            output("Contentissim".($session['user']['sex']?"a":"o")." per l'effetto decisamente positivo della pozione appena ingerita, decidi di ritornare nella foresta.");
            $session['user']['defence'] ++ ;
            $session['user']['bonusdefence'] ++ ;
            $session['user']['turns'] -= 2;
            debuglog("guadagna 1 punto difesa permanente esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "exp":
            $exp=e_rand((5*$session['user']['level']),(50*$session['user']['level']));
            output("Ad un certo punto, noti davanti ai tuoi piedi una pozione abbandonata. La bevi e immediatamente scopri che la tua esperienza è aumentata, hai guadagnato `#$exp`@ punti esperienza.`n");
            output("Content".($session['user']['sex']?"a":"o")." per l'effetto positivo di questa pozione, ritorni nella foresta.");
            $session['user']['experience'] += $exp;
            $session['user']['turns'] -= 2;
            debuglog("guadagna $exp punti esperienza esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "oblio":
            output("Mentre sei concentratissim".($session['user']['sex']?"a":"o")." nell'esplorare `0$luogo`@ hai la fortuna di trovare una pozione abbandonata. La bevi tutta in un fiato e.... tutto sembra ruotare intorno a te.... i tuoi ricordi sono meno definiti... e quando ti riprendi scopri che hai perso il `#5%`@ dei tuoi punti esperienza.");
            output("Forse la prossima volta ti converrà stare più attent".($session['user']['sex']?"a":"o")." con le pozioni, ma per tua fortuna non succede nient'altro.`nI punti persi li potrai recuperare rapidamente, meglio quindi ritornare nella foresta e darsi da fare.");
            $session['user']['experience']=round($session['user']['experience']*.95,0);
            $session['user']['turns'] -= 2;
            debuglog("perde il 5% dei suoi punti esperienza esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "pvp": //evento unico
            output("Hai appena iniziato ad esplorare `0$luogo`@ quando trovi vicino ad un cespuglio una fiala contenente una pozione. Senza timore alcuno la bevi e immediatamente senti dentro di te una grande energia, hai guadagnato `#1`@ combattimento `#PVP`@!`n");
            output("Liet".($session['user']['sex']?"a":"o")." per l'effetto benefico della pozione appena ingerita ti avvii rapidamente verso la foresta determinato ad utilizzare al meglio quanto hai appena guadagnato con l'esplorazione.");
            $session['user']['playerfights'] += 1;
            $session['user']['turns'] -= 2;
            debuglog("guadagna $exp punti esperienza esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "turnicosto":
            output("Nel bel mezzo della tua esplorazione scopri un piccolo anfratto dove trovi una pozione abbandonata. Decidi di berla e immediatamente ti senti un po' più riposat".($session['user']['sex']?"a":"o").", hai guadagnato `#2`@ turni di combattimento nella foresta.`n");
            output("Peccato che lo stesso numero di turni li hai spesi per poter effettuare l'esplorazione, ma almeno non ci hai rimesso nulla! In ogni caso decidi che sia meglio ritornare nella foresta alla ricerca di qualche mostruosa creatura da affrontare.");
            debuglog("recupera i 2 turni dell'esplorazione, esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "turni":
            output("Dopo aver esplorato per parecchio tempo `0$luogo`@, trovi in mezzo all'erba una pozione abbandonata. Decidi di berla e la cosa si rivela un'ottima scelta dal momento che non solo ti senti più riposat".($session['user']['sex']?"a":"o").", ma scopri che hai anche guadagnato `#5`@ turni di combattimento nella foresta.`n");
            output("Felice per l'effetto benefico della pozione appena bevuta, decidi di ritornare immediatamente nella foresta in cerca di nuove avventure.");
            $session['user']['turns'] += 3;
            debuglog("guadagna 5 turni esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "turniperdi":
            output("Esplori attentamente `0$luogo`@ e raccogli da terra una fiala contenente una pozione. La bevi, ma forse non è stata una buona scelta perchè una pesante sonnolenza ti avvolge, le palpebre si chiudono e hai giusto il tempo di sdraiarti che cadi in un sonno profondo.");
            output("Quando ti risvegli scopri che il pisolino ti è costato `#5`@ turni di combattimento nella foresta.`n Non ti resta quindi che abbandonare rapidamente `0$luogo`@ e ritornare velocemente nella foresta per non perdere ulteriore tempo.");
            $session['user']['turns'] -= 7;
            debuglog("perde 5 turni esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "illusione":
            output("Cammini per ore e ore mentre continui ad esplorare `0$luogo`@, ma... forse hai continuato a girare inutilmente in tondo visitando sempre gli stessi posti. Ti rendi conto che anche con la mappa di `(Merk`@, non è così semplice orientarsi,");
            output("forse sei prigionier".($session['user']['sex']?"a":"o")."  di un'illusione. `n Quando riesci a ritrovare il senso di orientamento e scopri che hai perso `#2`@ ulteriori turni di combattimento, ti dirigi velocemente verso la foresta per cercare di recuperare in maniera profiqua il tempo perduto.");
            $session['user']['turns'] -= 4;
            debuglog("perde 2 turni esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "pozione_nulla":
            output("`^Ad un certo punto, noti davanti ai tuoi piedi una pozione abbandonata. La bevi, e... buona! Però non succede niente.");
            output("Non hai guadagnato assolutamente nulla in questa esplorazione, perciò ritorni nella foresta.");
            $session['user']['turns'] -= 2;
            debuglog("beve una pozione senza poteri esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "drink":
            output("Ti imbatti in una borraccia contenente un liquido ambrato e dall'ottimo aroma. Incuriosito ne ingolli alcune sorsate e.... uhm buono! Talmente buono che continui a bere fino all'ultima goccia ma.... sicuramente deve trattarsi di uno dei famosi liquori di `0Sook`@, e questo significa che...`n");
            output("Infatti *HIC*, come volevasi *HIC* dimostrare, ti gira la testa e sei completamente ubriaco *HIC*. Barcollante e malfermo sulle gambe ritorni nella foresta.");
            $session['user']['turns'] -= 2;
            $session['user']['drunkenness'] += 80;
            $session['user']['bladder'] += 5;
            debuglog("beve una pozione e si ubriaca esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "bladder":
            output("Ti imbatti in una borraccia contenente un liquido ambrato e dall'ottimo aroma. Assetato ne ingolli alcune sorsate e... ottimo, fresco e dissetante! Però, poco dopo senti un forte stimolo per espletare i tuoi bisogni.`n");
            output("Hai assolutamente bisogno di un bagno e per trovarne uno devi per forza ritornare nella foresta.");
            $session['user']['turns'] -= 2;
            $session['user']['drunkenness'] += 15;
            $session['user']['bladder'] += 15;
            debuglog("beve una pozione esplorando $luogo ed ora deve andare al bagno");
            addnav("Torna nella foresta","forest.php");
        break;
        case "quasimorte":
            output("Hai trovato una fiala contenente uno strano liquido rossastro, vinto dalla curiosità ne ingerisci il contenuto, ma sin dalla prima sorsata, ti rendi conto che qualcosa non va..... la testa incomincia a girare mentre la vista ti si appanna e un forte dolore ti avvolge i polmoni impedendoti quasi di respirare.");
            output("`nTi accasci al suolo ed inizi a sputare sangue... ma fortunatamente per te il veleno che hai bevuto non era molto potente e non si è rivelato mortale, ma");
            output("oltre a perdere `#1`@ ulteriore turno di combattimento nella foresta, ti ritrovi debolissim".($session['user']['sex']?"a":"o")." con `#un`@ solo hitpoints!`n Meglio tornare nella foresta facendo molta attenzione ad evitare ogni spiacevole incontro prima di riuscire a passare dal guaritore!");
            $session['user']['turns'] -= 3;
            $session['user']['hitpoints']=1;
            debuglog("beve un veleno esplorando $luogo ma sopravvive! Perde 1 turno e si ritrova con 1 HP");
            addnav("Torna nella foresta","forest.php");
        break;
        case "morte": //evento unico
            output("Hai trovato una fiala contenente uno strano liquido rossastro, vinto dalla curiosità ne ingerisci il contenuto, ma sin dalla prima sorsata, ti rendi conto che qualcosa non va..... la testa incomincia a girare mentre la vista ti si appanna e un forte dolore ti avvolge i polmoni impedendoti di respirare.");
            output("`nTi accasci al suolo ed inizi a sputare sangue... La tua gola brucia sempre più e il respiro ti viene a mancare.... puoi solo restare immobile e agonizzante al suolo in attesa della tua morte.");
            output("`n`nForse la prossima volta farai più attenzione alle pozioni trovate nei luoghi sconosciuti!`n`n`\$Sei Mort".($session['user']['sex']?"a":"o")."! `@e perdi il `#10%`@ della tua esperienza, oltre a tutti i `^pezzi d'oro`@ che avevi con te.");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.90,0);
            $session['user']['alive']=false;
            $session['user']['turns']=0;
            debuglog("beve un veleno esplorando $luogo e muore!");
            addnews("`8".$session['user']['name']."`8 è mort".($session['user']['sex']?"a":"o")." mentre esplorava `0$luogo");
            addnav("Notizie Giornaliere","news.php");
        break;
        case "trappolamortale": //evento unico
            output("Ad un certo punto della tua esplorazione, il terreno improvvisamente cede sotto i tuoi piedi. Sei cadut".($session['user']['sex']?"a":"o")." in una trappola per animali, e non fai in tempo a renderti conto di cosa sta accadendo perchè batti la testa contro una pietra rimanendo uccis".($session['user']['sex']?"a":"o")." all'istante.`n");
            output("`n`nForse la prossima volta farai più attenzione a dove metti i piedi, i luoghi speciali di `(Merk l'Esploratore`@ si dimostrano spesso molto pericolosi!`n`n`\$Sei Mort".($session['user']['sex']?"a":"o")."! `@e perdi il `#10%`@ della tua esperienza, oltra a tutti i `^pezzi d'oro`@ che avevi con te.");
            $session['user']['gold']=0;
            $session['user']['hitpoints']=0;
            $session['user']['experience']=round($session['user']['experience']*.90,0);
            $session['user']['alive']=false;
            $session['user']['turns']=0;
            debuglog("cade in una trappola esplorando $luogo e muore!");
            addnews("`8".$session['user']['name']."`8 è mort".($session['user']['sex']?"a":"o")." mentre esplorava `0$luogo");
            addnav("Notizie Giornaliere","news.php");
        break;
        case "trappola":
            output("Mentre stai esaminando attentamente un anfratto non ti accorgi di aver fatto scattare un trabocchetto e il terreno frana improvvisamente sotto ai tuoi piedi. Sei cadut".($session['user']['sex']?"a":"o")." in una trappola per animali! Fortunatamente per te, l'impatto al suolo terra non è molto violento perchè attutito da un grosso cespuglio e riesci a sopravvivere.`n");
            output("`n`nForse la prossima volta farai più attenzione a dove metti i piedi, i luoghi speciali di `(Merk l'Esploratore`@ si dimostrano spesso molto pericolosi e irti di pericoli! Nella caduta hai perso il `#50%`@ dei tuoi hitpoints e pensi che ti convenga recarti immediatamente dal guaritore per rimetterti in forma.");
            
            $session['user']['hitpoints']=round($session['user']['hitpoints']/2,0);
            if ($session['user']['hitpoints']<1) $session['user']['hitpoints']==1;
            $session['user']['turns']-=2;
            debuglog("cade in una trappola esplorando $luogo e perde metà dei suoi HP");
            addnav("Torna nella foresta","forest.php");
        break;
        case "favori":
            output("Ti accorgi che sul terreno giace una pozione abbandonata. Dopo averla raccolta, la bevi, e ti ritrovi in preda alle allucinazioni...`nTi sembra infatti di essere al fianco di `\$Ramius`@ mentre entrambi ");
            output("infliggete dolore alle anime dei guerrieri defunti. L'effetto allucinatorio del liquido appena ingerito non dura molto, ma quando riprendi il pieno possesso delle tue facoltà mentali sei convint".($session['user']['sex']?"a":"o")." che, forse, qualcosa di vero c'era! Scopri infatti di aver guadagnato alcuni favori da `\$Ramius il Signore della Morte`@.`n");
            output("Scoss".($session['user']['sex']?"a":"o")." per l'avventura vissuta e content".($session['user']['sex']?"a":"o")." per l'effetto benefico della pozione bevuta, ritorni nella foresta.");
            $favori=e_rand(5,20);
            $session['user']['deathpower']+=$favori;
            $session['user']['turns']-=2;
            debuglog("guadagna $favori favori esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "good":
            output("Ad un certo punto, inciampi in una fiala contentente una pozione. La bevi nonostante il suo sapore estremamente amaro, e hai la sensazione che la tua anima sia diventata più buona. Sara vero ? ");
            output("Lieto per l'effetto benefico di questa pozione, ritorni nella foresta.");
            $session['user']['evil']-=10;
            $session['user']['turns']-=2;
            debuglog("perde 10 punti cattiveria esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "evil":
            output("Ad un certo punto, noti davanti ai tuoi piedi una pozione abbandonata. La bevi nonostante il suo sapore estremamente aspro e hai come la sensazione che la tua anima sia diventata più crudele e cattiva di prima. Che sia l'effetto di un maleficio ?");
            output("Dopo aver bevuto questa pozione, ritorni nella foresta.");
            $session['user']['evil']+=10;
            $session['user']['turns']-=2;
            debuglog("si becca 10 punti cattiveria esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "bonus":
            output("Ad un certo punto, trovi nell'erba una pozione abbandonata. La bevi velocemente nonostante il suo sapore estremamente salato, e ti senti più forte! Hai guadagnato `#un bonus`@ in combattimento.");
            output("Lieto per l'effetto benefico di questa pozione, ritorni nella foresta.");
            $session['bufflist'][375] = array("name"=>"`VPozione misteriosa","rounds"=>30,"wearoff"=>"Gli effetti della pozione che hai trovato si sono esauriti","defmod"=>1.25,"atkmod"=>1.25,"roundmsg"=>"L'aver bevuto la pozione che hai trovato ti fa combattere meglio!","activate"=>"offense");
            $session['user']['turns']-=2;
            debuglog("guadagna un buff positivo esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "malus":
            output("Ad un certo punto vedi per terra una pozione abbandonata. La bevi, il sapore è dolcissimo, ma il contenuto è un liquido drogato! Era meglio non bere questa pozione, ora ti senti debole e avrai `#una penalità`@  in combattimento.");
            output("Avvilito per l'errore commesso, ritorni nella foresta.");
            $session['bufflist'][375] = array("name"=>"`vPozione drogata","rounds"=>10,"wearoff"=>"Ti sei ripreso, e la pozione ha esaurito il suo effetto","defmod"=>0.4,"atkmod"=>0.4,"roundmsg"=>"La pozione che hai bevuto ti rende molto debole","activate"=>"defense");
            $session['user']['turns']-=2;
            debuglog("si becca 10 punti cattiveria esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "pergamena":
            if ($session['user']['carriera']>40 AND $session['user']['carriera']<45) {
                $pergamena=1;
                switch ($session['user']['carriera']) {
                    case 41:
                        $sql="SELECT id, nome FROM materiali WHERE tipo='P' AND livello='A' ORDER BY rand() LIMIT 1";
                    break;
                    case 42:
                        $sql="SELECT id, nome FROM materiali WHERE tipo='P' AND ( livello='A' OR livello='B' ) ORDER BY rand() LIMIT 1";
                    break;
                    case 43:
                    case 44:
                        $sql="SELECT id, nome FROM materiali WHERE tipo='P' AND ( livello='A' OR livello='B' OR livello='D' ) ORDER BY rand() LIMIT 1";
                    break;
                }
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                output("Ad un certo punto, noti davanti ai tuoi piedi un rotolo di pelle legato da un fiocco di seta. Delicatamente lo apri, e scopri che al suo interno è contenuta una pergamena. Hai trovato una `&".$row[nome]."`@ con la quale potrai evocare un potente incantesimo alla Torre di Magia!`n");
                if (!zainoPieno($session['user']['acctid'])){
                    $sql="INSERT INTO zaino (idoggetto,idplayer) VALUES ('".$row['id']."','".$session['user']['acctid']."')";
                    db_query($sql) or die(db_error(LINK));
                    output("Felice per essere stat".($session[user][sex]?"a":"o")." ripagat".($session[user][sex]?"a":"o")." dall'esplorazione con una preziosa pergamena, ritorni nella foresta.");
                } else {
                    output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
                    output("Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
                    output("lo zaino e far posto ad eventuali materiali di maggior valore che potresti trovare nella foresta.`n");
                }
                $session['user']['turns']-=2;
                debuglog("ha trovato ".$row['nome']." esplorando $luogo");
            }
        case "ricetta":
            if ($pergamena!=1) {
            	if ($session['user']['carriera']>4 AND $session['user']['carriera']<9) {
                	$fabbro = 1 ;
                } else {
                	$fabbro = 0 ;
				}
                $sql="SELECT id, nome FROM materiali WHERE tipo='R' ORDER BY rand() LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                if ($fabbro!=1) {
                	output("Ad un certo punto, noti davanti ai tuoi piedi un rotolo di pelle legato da un cordoncino. Lo apri delicatamente, e scopri che al suo interno è contenuta una ricetta. Hai trovato una `&".$row[nome]."`@ che potrai vendere da `#Oberon il Fabbro`@!`n");
                } else {
                	output("Ad un certo punto, noti davanti ai tuoi piedi un rotolo di pelle legato da un cordoncino. Lo apri delicatamente, e scopri che al suo interno è contenuta una ricetta. Hai trovato una `&".$row[nome]."`@ con la quale potrai forgiare un oggetto nella bottega di `#Oberon il Fabbro`@!`n");
                }
                if (!zainoPieno($session['user']['acctid'])){
                    $sql="INSERT INTO zaino (idoggetto,idplayer) VALUES ('".$row['id']."','".$session['user']['acctid']."')";
                    db_query($sql) or die(db_error(LINK));
                    output("Felice per la preziosa ricetta trovata durante l'esplorazione, ritorni nella foresta.");
                } else {
                    output("`%È un vero peccato che tu abbia lo zaino pieno e non possa raccoglierla !!`n");
                    output("Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
                    output("lo zaino e far posto ad eventuali materiali di maggior valore che potresti trovare nella foresta.`n");
                }
                $session['user']['turns']-=2;
                debuglog("ha trovato ".$row['nome']." esplorando $luogo");
            }
            addnav("Torna nella foresta","forest.php");
        break;
        case "simbolo":
            $fede=e_rand(0,3);
            $dio = array(0=>"`!di una divinità pagana",1=>"`^di Sgrios",2=>"`\$di Karnak",3=>"`b`@del Drago Verde`b");
            $chiesa = array(0=>"`!a nessuna Chiesa",1=>"`^Chiesa di Sgrios",2=>"`\$Grotta di Karnak",3=>"`b`@Gilda del Drago Verde`b");
            output("Nel mezzo dell'esplorazione di uno dei luoghi speciali descritti in una delle mappe di `(Merk`@ in tuo possesso, trovi semi seppellito nel terreno un piccolo amuleto. Osservandolo meglio, scopri che è il simbolo sacro $dio[$fede]`@.`n");
            if ($session['user']['dio']==$fede AND $session['user']['dio']>0) {
                $session['user']['punti_generati'] += 100;
                $session['user']['punti_carriera'] += 100;
                $fama = (200*$session['user']['fama_mod']);
                $session['user']['fama3mesi'] += $fama;
                switch($session['user']['dio']) {
                    case 1:
                        savesetting("puntisgrios", getsetting("puntisgrios",0)+100);
                    break;
                    case 2:
                        savesetting("puntikarnak", getsetting("puntikarnak",0)+100);
                    break;
                    case 3:
                        savesetting("puntidrago", getsetting("puntidrago",0)+100);
                    break;
                }
                output("Ma questo è il simbolo della tua fede! Mentre lo sollevi da terra, lo vedi brillare e dissolversi nelle tue mani. Tuttavia, senti di aver guadagnato punti carriera,");
                output("e senti anche che la forza della tua setta si è accresciuta. E con questa consapevolezza ritorni nella foresta.");
                debuglog("ha trovato un simbolo sacro esplorando $luogo ed ha guadagnato 100 punti carriera, 100 punti setta e $fama punti fama");
            }elseif ($session['user']['dio']>0) {
                output("Essendo fedele alla ".$chiesa[$session['user']['dio']]."`@, non ci pensi un attimo prima di distruggerlo.`n");
                output("E subito dopo ritorni nella foresta, in cerca del proprietario di quest'oggetto, per fargli rimpiangere di aver scelto la fede sbagliata.");
                debuglog("ha trovato un simbolo sacro esplorando $luogo e lo ha distrutto");
            }else {
                output("Non ti importa granchè di quel che significa, ma notando che è fatto di `^oro`@, lo raccogli sapendo che potrai successivamente rivenderlo a qualcuno. Guadagni in questo modo `^200 monete d'oro`@.`n");
                output("Felice per essere stato ripagato del tempo impiegato nell'esplorazione, ritorni nella foresta.");
                $session['user']['gold']+=200;
                debuglog("ha trovato un simbolo sacro esplorando $luogo e lo ha venduto per 200 pezzi d'oro");
            }
            $session['user']['turns']-=2;
            addnav("Torna nella foresta","forest.php");
        break;
        case "evento":
            output("Ad un certo punto, mentre sei impegnato nell'esplorazione ti imbatti in un evento speciale...");
            if ($handle = opendir("special")){
                $events = array();
                while (false !== ($file = readdir($handle))){
                    if (strpos($file,".php")>0){
                        // Skip the darkhorse if the horse knows the way
                        if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0 && strpos($file, "darkhorse") !== false) continue;
                        array_push($events,$file);
                    }
                }
                $tot=0; //calcolo somma dei totali degli eventi per poi effettuare il sorteggio
                for($i=0;$i<(count($events));$i++) {
                    $sqlch="SELECT foresta FROM peso_eventi WHERE nomefile='".$events[$i]."' ";
                    $resultch = db_query($sqlch) or die(db_error(LINK));
                    $rowch = db_fetch_assoc($resultch);
                    $tot += $rowch['foresta'];
                }
                if (count($events)==0){
                    output("`b`@Ahi, il tuo Amministratore ha deciso che non ti è permesso avere eventi speciali. Prenditela con lui, non con me.");
                }else{
                    $x=e_rand(1,$tot); //numero estratto per la selezione evento
                    $sqlevents="SELECT nomefile, foresta FROM peso_eventi ORDER BY rand()";
                    $resultevents = db_query($sqlevents) or die(db_error(LINK));
                    $j=0; //indice pesato per la ricerca dell'evento estratto
                    while ($j<$x) {
                        $rowevents = db_fetch_assoc($resultevents);
                        $i = $rowevents['nomefile'];
                        $k=0; //controllo che l'evento possa essere scelto
                        for ($l=0;$l<(count($events));$l++) {
                            if ($i==$events[$l]) $k=1; //ok, l'evento è stato trovato ed il giocatore può averlo
                        }
                        if ($k==1) $j += $rowevents['foresta']; //incremento indice peso
                    }
                    $session['user']['specialinc']= $i;
                }
            }else{
                output("`c`b`\$ERRORE!!!`b`c`&Non riesco ad aprire gli eventi speciali! Per favore avverti l'Amministratore!!");
            }
            $session['user']['turns'] -= 2;
            debuglog("esplorando $luogo si imbatte nell'evento speciale $i");
            addnav("Prosegui","forest.php");
        break;
        case "cavalcare":
            $bonus=e_rand(2,3);
            output("Dopo aver esplorato per parecchio tempo senza risultato `0$luogo`@ inizio a sentirti stanco, ti siedi ai piedi di un albero appoggiando la schiena al tronco e ti accorgi che in un incavo di una radice sporgente dal terreno è celato un piccolo libretto. Delicatamente lo estrai dal suo nascondiglio e scopri che è un piccolo manuale rilegato in pelle e che parla di `2Draghi`@.`n");
            output("Si tratta infatti di un trattato su queste nobili creature e la tua curiosità a riguardo è tale che inizi subito a leggerlo con molta attenzione, perdendo la nozione del tempo, cosa che ti costa `#1`@ altro turno di combattimento, ma che ti fa guadagnare invece `^$bonus`@ punti cavalcare!`n");
            $session['user']['turns']-=3;
            $session['user']['cavalcare_drago']+=$bonus;
            debuglog("ha guadagnato $bonus punti cavalcare esplorando $luogo");
            addnav("Torna nella foresta","forest.php");
        break;
        case "grizzly":
            $battle=true;
            fightnav(true,false);
            $session['user']['specialinc']="esplorazione.php";
            output("Distratto dall'attento esame della mappa del luogo che stai esplorando, non ti accorgi del pericolo che ti si avvicina, ad un tratto ti volti istintivamente, e un enorme orso si erge in piedi in posizione di attacco!`n");
            output("Ti trovi di fronte ad un feroce `8Grizzly`@ che ti ha scambiato per una facile preda, e se vorrai salvare la pellaccia e raccontare una nuova avventura, dovrai combattere ed ucciderlo!`n");
            $badguy = array(
                    "creaturename"=>"Orso Grizzly",
                    "creaturelevel"=>$session['user']['level'],
                    "creatureweapon"=>"Morsi ed Artigli",
                    "creatureattack"=>$session['user']['attack']+$session['user']['level'],
                    "creaturedefense"=>$session['user']['defence']+$session['user']['level'],
                    "creaturehealth"=>$session['user']['maxhitpoints']+$session['user']['attack']/5+$session['user']['defence']/5+$session['user']['level']*8,
                    "diddamage"=>0
                    );
            $badguy['creaturegold']=e_rand(50,100)*$session['user']['level'];
            $badguy['creaturegems']=e_rand(1,3);
            $badguy['creatureexp']= e_rand(80,120)*$session['user']['level'];
            $session['user']['badguy']=createstring($badguy);
            require("battle.php");
            //    $session['user']['badguy']=createstring($badguy);
        break;
        case "alligatore":
            $battle=true;
            fightnav(true,false);
            $session['user']['specialinc']="esplorazione.php";
            output("Distratto dall'attento esame della mappa del luogo che stai esplorando, non ti accorgi di esserti avvicinato pericolosamente ai bordi di una landa paludosa, territorio di caccia di un gigantesco `3Alligatore`@!`n");
            output("Un fruscio alle tua spalle ti fa voltare di scatto e un enorme bocca irta di denti si spalanca tentando di afferrarti!`n");
            output("Sei quasi finito nelle fauci di un `3Alligatore Gigante`@, e se vorrai salvare la pelle, dovrai combattere ed ucciderlo!`n");
            $badguy = array(
                    "creaturename"=>"Alligatore Gigante",
                    "creaturelevel"=>$session['user']['level']+2,
                    "creatureweapon"=>"Morso Potente",
                    "creatureattack"=>$session['user']['attack']+ e_rand(1,(($session['user']['attack']/10)*$session['user']['level'])),
                    "creaturedefense"=>$session['user']['defence']+ e_rand(1,(($session['user']['attack']/10)*$session['user']['level'])),
                    "creaturehealth"=>round(($session['user']['maxhitpoints']*2)+$session['user']['attack']/2+$session['user']['defence']/2+(($session['user']['maxhitpoints']/10)*$session['user']['level'])),
                    "diddamage"=>0
                    );
            $points = 0;
            while(list($key,$val)=each($session['user']['dragonpoints'])){
                if ($val=="at" || $val == "de") $points++;
            }
            $points += (int)(($session['user']['maxhitpoints'] - 150)/10);
            //Modifica di Excalibur per tener conto dei PA e PD PERMANENTI
            $bnsatk=(int)(($session['user']['bonusattack']/1.5)+0.99);
            $bnsdef=(int)(($session['user']['bonusdefence']/1.5)+0.99);
            // Fine modifica ... vedi anche sotto
            //luke per potenziare un po il drago verde per i reincarnati
            $bnsatk+=(int)(($session['user']['reincarna']* e_rand(($bnsatk/2), $bnsatk))/4);
            $bnsdef+=(int)(($session['user']['reincarna']* e_rand(($bnsdef/2), $bnsdef))/4);
            //fine modifica luke
            $atkflux = e_rand(0, ($bnsatk+$points));
            $defflux = e_rand(0,($points-$atkflux+$bnsdef));
            //$hpflux = ($points + $bnsatk + $bnsdef - ($atkflux+$defflux)) * 5;

            $badguy['creatureattack']+=$atkflux;
            $badguy['creaturedefense']+=$defflux;
            $badguy['creaturehealth']+=$hpflux;
            $badguy['creaturegold']=e_rand(100,150)*$session['user']['level'];
            $badguy['creaturegems']=e_rand(1,3);
            $badguy['creatureexp']= e_rand(100,200)*$session['user']['level'];
            $session['user']['badguy']=createstring($badguy);
            require("battle.php");
            //    $session['user']['badguy']=createstring($badguy);
        break;
        case "wyrm": //evento unico
            $battle=true;
            fightnav(true,false);
            $session['user']['specialinc']="esplorazione.php";
            output("Ad un tratto, senti dietro di te un forte sibilo, ti volti istintivamente per guardarti alle spalle, e appare ai tuoi occhi il più grosso serpente che tu abbia mai visto.`n");
            output("Purtroppo per te sei finito nella tana di un `&Wyrm`@, e se vorrai rimanere vivo, dovrai combattere ed ucciderlo!`n");
            $badguy = array(
                    "creaturename"=>"Wyrm",
                    "creaturelevel"=>$session['user']['level']+10,
                    "creatureweapon"=>"Morso Mortale",
                    "creatureattack"=>$session['user']['attack']*2+ e_rand(1,(($session['user']['attack']/10)*$session['user']['level'])),
                    "creaturedefense"=>$session['user']['defence']*2+ e_rand(1,(($session['user']['attack']/10)*$session['user']['level'])),
                    "creaturehealth"=>round(($session['user']['maxhitpoints']*5)+$session['user']['attack']+$session['user']['defence']+(($session['user']['maxhitpoints']/10)*$session['user']['level'])),
                    "diddamage"=>0
                    );
            $points = 0;
            while(list($key,$val)=each($session['user']['dragonpoints'])){
                if ($val=="at" || $val == "de") $points++;
            }
            $points += (int)(($session['user']['maxhitpoints'] - 150)/10);
            //Modifica di Excalibur per tener conto dei PA e PD PERMANENTI
            $bnsatk=(int)(($session['user']['bonusattack']/1.5)+0.99);
            $bnsdef=(int)(($session['user']['bonusdefence']/1.5)+0.99);
            // Fine modifica ... vedi anche sotto
            //luke per potenziare un po il drago verde per i reincarnati
            $bnsatk+=(int)(($session['user']['reincarna']* e_rand(($bnsatk/2), $bnsatk))/4);
            $bnsdef+=(int)(($session['user']['reincarna']* e_rand(($bnsdef/2), $bnsdef))/4);
            //fine modifica luke
            $atkflux = e_rand(0, ($bnsatk+$points));
            $defflux = e_rand(0,($points-$atkflux+$bnsdef));
            //$hpflux = ($points + $bnsatk + $bnsdef - ($atkflux+$defflux)) * 5;

            $badguy['creatureattack']+=$atkflux;
            $badguy['creaturedefense']+=$defflux;
            $badguy['creaturehealth']+=$hpflux;
            $badguy['creaturegold']=e_rand(100,1000)*$session['user']['level'];
            $badguy['creaturegems']=e_rand(1,10);
            $badguy['creatureexp']= e_rand(200,300)*$session['user']['level'];
            $session['user']['badguy']=createstring($badguy);
            require("battle.php");
            //    $session['user']['badguy']=createstring($badguy);
        break;
        case "basilisco": //evento unico
            output("Ad un tratto, un rumore attira la tua attenzione e appare alla tua vista il `^Re`@ dei `2Serpenti`@ tanto decantato dalle antiche leggende.`n");
            output("Hai incontrato il temibile `&Basilisco`@, e la terribile creatura tiene fede a quanto è stato raccontato dai bardi e alle storie a cui non hai mai voluto credere.`n");
            switch(e_rand(1,10)){
                case 1:
            		output("`n`nIl suo sguardo assassino si fissa nei tuoi occhi e tu vedi solo la morte che sopraggiunge istantanea!`n`n`\$SEI MORTO! `@Perdi il `#10%`@ della tua esperienza, e tutto l'oro che avevi con te.");
            		$session['user']['gold']=0;
            		$session['user']['hitpoints']=0;
            		$session['user']['experience']=round($session['user']['experience']*.90,0);
            		$session['user']['alive']=false;
            		$session['user']['turns']=0;
            		debuglog("viene ucciso dallo sguardo del Basilisco");
            		addnews("`8".$session['user']['name']."`8 è stat".($session['user']['sex']?"a":"o")." uccis".($session['user']['sex']?"a":"o")." dallo sguardo del Basilisco mentre esplorava $luogo");
            		addnav("Notizie Giornaliere","news.php");
                	break;
                case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:  
                	$battle=true;
            		fightnav(true,false);
            		$session['user']['specialinc']="esplorazione.php";                
            		$badguy = array(
                    "creaturename"=>"Basilisco",
                    "creaturelevel"=>$session['user']['level']+20,
                    "creatureweapon"=>"Sguardo Assassino",
                    "creatureattack"=>$session['user']['attack']*3+ e_rand(1,(($session['user']['attack']/10)*$session['user']['level'])),
                    "creaturedefense"=>$session['user']['defence']*3+ e_rand(1,(($session['user']['attack']/10)*$session['user']['level'])),
                    "creaturehealth"=>round(($session['user']['maxhitpoints']*10)+$session['user']['attack']+$session['user']['defence']+(($session['user']['maxhitpoints']/10)*$session['user']['level'])),
                    "diddamage"=>0
                    );
		            $points = 0;
		            while(list($key,$val)=each($session['user']['dragonpoints'])){
		                if ($val=="at" || $val == "de") $points++;
		            }
		            $points += (int)(($session['user']['maxhitpoints'] - 150)/10);
		            //Modifica di Excalibur per tener conto dei PA e PD PERMANENTI
		            $bnsatk=(int)(($session['user']['bonusattack']/1.5)+0.99);
		            $bnsdef=(int)(($session['user']['bonusdefence']/1.5)+0.99);
		            // Fine modifica ... vedi anche sotto
		            //luke per potenziare un po il drago verde per i reincarnati
		            $bnsatk+=(int)(($session['user']['reincarna']* e_rand(($bnsatk/2), $bnsatk))/4);
		            $bnsdef+=(int)(($session['user']['reincarna']* e_rand(($bnsdef/2), $bnsdef))/4);
		            //fine modifica luke
		            $atkflux = e_rand(0, ($bnsatk+$points));
		            $defflux = e_rand(0,($points-$atkflux+$bnsdef));
		            //$hpflux = ($points + $bnsatk + $bnsdef - ($atkflux+$defflux)) * 5;
		
		            $badguy['creatureattack']+=$atkflux;
		            $badguy['creaturedefense']+=$defflux;
		            $badguy['creaturehealth']+=$hpflux;
		            $badguy['creaturegold']=e_rand(100,2000)*$session['user']['level'];
		            $badguy['creaturegems']=e_rand(1,20);
		            $badguy['creatureexp']= e_rand(200,300)*$session['user']['level'];
		            $session['user']['badguy']=createstring($badguy);
		            require("battle.php");
             	break;
    		}
        break;
    }
    $sql="UPDATE mappe_foresta_player SET visitato=1 WHERE luogo=".$_GET['luogo']." AND acctid=".$session['user']['acctid'];
    db_query($sql) or die(db_error(LINK));
}else if($_GET['op']=="fight") {
    $session['user']['specialinc']="esplorazione.php";
    $badguy= createarray($session['user']['badguy']);
    $battle=true;
    require("battle.php");
    if($victory) {
        output("`@Sei riuscit".($session['user']['sex']?"a":"o")." a sopravvivere, hai ucciso ".$badguy['creaturename']."!`n`n");
        output("Prosegui l'esplorazione, e nei paraggi riesci a trovare il tesoro che questa terribile creatura custodiva!`n`n");
        output("`@In questo modo ti sei arricchit".($session['user']['sex']?"a":"o")." con `^".  $badguy['creaturegold']. " pezzi d'oro`@ e `&" . $badguy['creaturegems'] . " gemme`@!!!`n");
        output("Guadagni inoltre `#" . $badguy['creatureexp'] . "`@ punti esperienza");
        addnews("`%".$session['user']['name']." `6si è imbattut".($session['user']['sex']?"a":"o")." in un `@".$badguy['creaturename']."`6 e lo ha ucciso!");
        debuglog("uccide ".$badguy['creaturename']." esplorando $luogo. Guadagna ".$badguy['creaturegold']." oro, ".$badguy['creaturegems']." gemme e ".$badguy['creatureexp']." esperienza");
        $session['user']['specialinc']="";
        $session['user']['badguy']="";
        $session['user']['experience']+=$badguy['creatureexp'];
        $session['user']['gold']+=$badguy['creaturegold'];
        $session['user']['gems']+=$badguy['creaturegems'];
        $session['user']['turns']-=2;
        $session['user']['specialinc']="";
        addnav("Torna nella foresta","forest.php");
    }else if($defeat) {
        output("`n`n`b`$ Sei stato uccis".($session['user']['sex']?"a":"o")." da ".$badguy['creaturename']."!!!`n");
        output("Perdi tutto il tuo oro e il 15% di esperienza`b");
        addnews("`%".$session['user']['name']." `6si è imbattut".($session['user']['sex']?"a":"o")." in un `@".$badguy['creaturename']."`6 ed è stat".($session['user']['sex']?"a":"o")." divorat".($session['user']['sex']?"a":"o").".");
        debuglog("perde il 15% exp e ".$session['user']['gold']." ucciso da ".$badguy['creaturename']." esplorando $luogo");;
        $session['user']['specialinc']="";
        $session['user']['gold']=0;
        $session['user']['hitpoints']=0;
        $session['user']['experience']=round($session['user']['experience']*.85,0);
        $session['user']['badguy']="";
        $session['user']['alive']=false;
        $session['user']['turns']=0;
        addnav("Notizie Giornaliere","news.php");
    }else {
        fightnav(true,false);
    }
}elseif ($_GET['op']=="run"){
    output("Sei in trappola, non hai nessuna possibilità di scappare!");
    $_GET['op']="fight";
}

// fine combattimento

page_footer();
?>