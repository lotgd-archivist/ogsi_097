<?php

/*
* osservatorio.php
*
* Script del villaggio/centro del paese
*
* @version 0.9.7 jt
* @written by Enialis of www.ogsi.it
*
*/

require_once("common.php");
require_once("common2.php");
page_header("L'Osservatorio");
$session['user']['locazione'] = 158;
addcommentary();
checkday();


if (!isset($session)) exit();
switch ($_GET[op]) {
    case "":
        output ("`n`& Entri nell'edificio, sali le scale a chiocciola, scoprendo di essere arrivato molto
            più in alto di quello che pensavi. Ti ritrovi così in una grande stanza dove puoi notare
            sei strani oggetti. Hanno forma cilindrica e all'estremità puntata verso l'esterno ti
            sembra di vedere delle gemme incastonate. Guardandoti meglio attorno, noti che dietro le
            tue spalle c'è un cartello con sopra scritto:`n");
        output ("`n`@Questi che vedi sono dei cannocchiali e servono a vedere il paesaggio di Rafflingate.
            Puoi usarli a tuo piacimento perché sono gratis. Stai `4ATTENTO`@, non tutto quello che vedi
            potrebbe essere vero, infatti, questo villaggio è magico e molte cose cambiano grazie all'immaginazione
            dei player.`n");
        addnav ("Osserva");
        addnav ("F?`&Foresta","osservatorio.php?op=foresta");
        addnav ("P?`&Periferia","osservatorio.php?op=periferia");
        addnav ("c?`&Piazza centrale","osservatorio.php?op=piazzacentrale");
        addnav ("m?`&Piazza del mercato","osservatorio.php?op=piazzadelmercato");
        addnav ("S?`&Strada della taverna","osservatorio.php?op=stradadellataverna");
        addnav ("V?`&Viale delle lame","osservatorio.php?op=vialedellelame");
        addnav ("Altro");
        addnav ("g?`4Prova a rubare una gemma","osservatorio.php?op=fregalegemme");
        addnav ("v?`4Torna al villaggio","village.php");
        rawoutput ("<br><br><div style=\"text-align: right;\"><font color=\"#b00000\">Osservatorio -  </font><font color=\"#33FF33\">Enialis</font> <font color=\"#00ffff\"></font><br>-www.ogsi.it</font><br>");
        rawoutput ("<br><br><div style=\"text-align: left;\">");
        viewcommentary ("Osservatorio", "Conversa con gli altri",30,25,"dice");
        break;


    case "fregalegemme":
        if ($session ['user']['turns'] >= 2){
            //Modifica di Excalibur, portato e_rand a 7
            $furto = e_rand (1,10);
            if ($furto==3){
                output ("`n`@Ti avvicini a uno degli strani oggetti, e tirando dalla tua tasca un
                              piccolo coltellino. Senza farti beccare dal custode che ti sta dando le
                              spalle, provi a staccare una delle gemme che lo adornano. Dopo qualche secondo noti che la gemma si
                              sta muovendo.`n");
                $sfortuna=e_rand (1,3);
                if ($sfortuna==3){
                    output("Allunghi la mano, prendi la gemma e te la infili in tasca.`n");
                    $session ['user']['gems'] += 1;
                    //aggiunte di Barik per controllare i furbi
                    debuglog("ha rubato una gemma all'osservatorio");
                    report(3,"`(Osservatorio","`\$".$session['user']['name']." ha rubato una gemma all'osservatorio!","osservatorio");
                    //fine aggiunte
                }elseif ($sfortuna==2){
                    output("Il custode ti vede e prova ad inseguirti, fortunatamente la tua nota velocità
                    nello scappare come un coniglio, per questa volta ti salva, ti nascondi in un fosso
                    nelle vicinanze. Perdi 4 turni nascosto nel fosso!
                    .`n");
                    $session ['user']['turns'] -= 4;
                }else{
                    output("Il custode ti vede , ti acchiappa, e ti scaraventa negli scarichi dell'osservatorio.
                    Sei tutto sporco e non hai fatto una gran figura. Perdi 2 turni a uscire dagli scarichi!
                    .`n");
                    $session ['user']['turns'] -= 2;
                    $session ['user']['clean'] += 12;
                }
                $session ['user']['turns'] -= 2;
                addnav ("T?`4Torna al villaggio","village.php");
            }else{
                output ("`n`@Ti avvicini a uno degli strani oggetti estraendo dalla tua tasca un
                              piccolo coltellino. Senza farti beccare dal custode che ti sta dando le
                              spalle, provi a staccare una delle gemme che lo adornano. Purtroppo, proprio quando senti che la
                              gemma sta per cedere, noti che il custode si trova davanti a te e ti guarda
                              con aria minacciosa.`n");
                if ($session ['user']['gems'] >= 0){
                    output ("`n`4'Stavi per caso cercando di derubare le gemme? Vedo anche che sei riuscito
                                 a prenderne una. Bene, questa me la riprendo. E adesso fuori
                                 da qui. E guai a te se ti rivedo da queste parti!'`n");
                    output ("`n`@Il custode ti prende per il collo, ti solleva e ti butta fuori dall'osservatorio`n");
                    $session ['user']['gems'] -= 1;
                    $botta = $session ['user']['level']*3;
                    $session ['user']['hitpoints'] -= $botta;
                    addnav ("T?`4Torna al villaggio","village.php");
                }else{
                    output ("`n`4'Stavi per caso cercando di derubare le gemme?'");
                    output ("`n`@Il custode ti prende per il collo, ti solleva e ti butta fuori dall'osservatorio`n");
                    $botta = $session ['user']['level']*3;
                    $session ['user']['hitpoints'] -= $botta;
                    addnav ("T?`4Torna al villaggio","village.php");
                }
            }
        }else{
            output ("`n`@Sei troppo stanco per provare a fregare il guardiano, che secondo il tuo parere
                è abbastanza sveglio per beccarti. Te ne ritorni al villaggio.`n");
            addnav ("T?`4Torna al villaggio","village.php");
        }
        break;


    case "foresta":
        output ("`n`&Spostando il tuo sguardo dal villaggio, noti la fitta foresta che circonda
                Rafflingate. La foresta, casa di creature malefiche e malvagie di ogni sorta. Osservi
                come le fitte fronde riducano il campo visivo a pochi metri in gran parte della zona. Il
                sentiero è quasi invisibile. La terra è ricoperta da uno spesso strato di muschio. Nella
                foresta regna ovunque il buio più completo, non dando possibilità di vedere quasi niente a
                chiunque decida di entrare, nascondendo anche agli occhi esperti degli `3elfi`&,
                `3troll`&, `3orchi`& e `3goblin`&, i suoi segreti più profondi. Persino i `3Lich`&, `3vampiri`&
                e i `3nani`& hanno problemi nonostante siano capaci di vedere al buio completo, in quanto
                l'oscurità non è solo naturale ma anche magica. Per questo anche i `3druidi`&, abituati a manipolare
                l'energia, temono la foresta. Aumentando al massimo la potenza, senti scorrere dei brividi
                lungo la tua schiena. Improvvisamente senti un grido che lacera il silenzio in cui eri
                immerso. Sai che un altro esploratore è morto nella foresta. Pur avendo paura, riaccosti
                l'occhio al binocolo e ti sembra di vedere gli alberi ridere. Decidi per cui di distogliere
                lo sguardo. Quasi all'inizio della foresta noti i gabinetti mentre nel profondo di essa noti
                la casa della strega.`n");
        output ("`n`&  `n");
        output ("`n`&Bagni pubblici `7- Il villaggio ha due gabinetti pubblici, che tiene nella foresta
                a causa del loro effetto di tenere lontane le creature ostili con il loro odore. C'è un
                gabinetto privilegiato ed uno di seconda categoria. Ogni tanto vedi passare da un bagno
                all'altro lo Gnomo della Carta Igienica.`n");
        output ("`n`&  `n");
        output ("`n`&Casa della strega `7- Nel profondo della foresta incontri la vecchia casa della strega.
                Dal camino esce un fumo denso e grigio che impregna l'aria. Qui vive una tipica strega,
                magra e gobba con una veste grigia ed un cappello a punta nero. Pur sapendo d'essere
                molto lontano ti senti impaurito e decidi di distogliere lo sguardo.`n");
        if ($session ['user']['dragonkills']<7){ // cambio descrizione per chi può già accedere al giardino incantato
            output ("`n`&  `n");
            output ("`n`&Giardino incantato `7- Quasi subito dopo le mura noti un grosso giardino. Pur
                          sforzando la vista, non noti niente di particolare in questo luogo. Sai però che
                          del tutto normale non è, perché a volte passandoci senti delle voci, anche se non
                          sei in grado di distinguere da dove vengano.`n");
        }else{
            output ("`n`&  `n");
            output ("`n`&Giardino incantato `7- Quasi subito dopo le mura noti un grosso giardino. Non noti
                          niente, ma sai per esperienza che questo luogo è accessibile solo ai veterani. Spesso il
                          giardino viene usato come scorciatoia per alcuni luoghi precisi della foresta. Non è
                          nient'altro che un portale magico.`n");
        }
        addnav ("C?`&Castel Excalibur","osservatorio.php?op=prosegui&op1=Castel Excalibur");
        addnav ("M?`&Monastero","osservatorio.php?op=prosegui&op1=Monastero");
        addnav ("Torna");
        addnav ("O?`&Osservatorio","osservatorio.php");
        addnav ("T?`4Torna al villaggio","village.php");
        break;

        if (!isset($session)) exit();
    case "prosegui":
        switch ($_GET['op1']) {
            case "Castel Excalibur":
                $session['user']['specialinc']="";
                output ("`n`&Guardando il giardino incantato presso il villaggio, vedi una grande strada
                   entrare nella foresta. Segui con il binocolo la strada e vedi che porta ad un immenso
                   castello. Questo si trova quasi alla fine della foresta, sui pendii delle montagne
                   che circondano il villaggio. Questo è il famoso Castel Excalibur. La fortezza è
                   circondata, oltre che dall'oscura foresta, anche da un profondo fossato in cui
                   probabilmente è preferibile non cadere. Non sai quali bestie magiche potresti trovare
                   una volta caduto. Oltre il fossato vedi le possenti mura, che se pur vecchie di
                   qualche secolo, non presentano grandi crepe. All'interno del castello si erge una
                   grande arena, simile a quella che si trova nel villaggio. Sulla strada principale,
                   l'unica che puoi visitare, a causa delle ristrutturazioni che stanno avvenendo, puoi
                   notare un'infinità di negozi e botteghe; tra tutte riconosci il negozio di Brax, un
                   vecchio nano rugoso che ha un campionario di oggetti unici, di ottima fattura e
                   spesso con proprietà magiche. Proseguendo avanti trovi la cooperativa dei mercenari,
                   dove per qualche soldo puoi reclutare i soldati di professione in modo che ti
                   aiutino nelle tue imprese. Più avanti ancora vedi la Farmacia di Adriana; una giovane
                   donna che può migliorare le tue capacità grazie ad alcuni farmaci. Quasi
                   immediatamente accanto vi è l'Eros Esotico, famoso per le pozioni magiche che ti
                   aiutano a farti bell".($session ['user']['sex']?"a":"o").". Infine vi è la Grotta
                   dello Gnomo, dove vengono servite le migliori Gallette della zona.

                   Avendo visitato per filo e per segno tutte le abitazioni, sei curioso di vedere cosa
                   ci sia nelle strade a cui non si ha accesso, a causa dei lavori, ma quando provi a
                   puntare il binocolo in quella direzione, una strana forza ti respinge.`n");
                addnav ("O?`&Osservatorio","osservatorio.php");
                addnav ("T?`4Torna al villaggio","village.php");
                break;

            case "Monastero":
                $session['user']['specialinc']="";
                output ("`n`&Nel profondo della Foresta, in quasi totale isolamento hai trovato il Monastero.
                   L'area all'esterno è ben curata. Dietro le mura dell'imponente monastero noti anche un
                   piccolo frutteto. Non molto distante dal frutteto, noti anche il vigneto, da cui i
                   monaci ricavano ottimo vino. Al centro della costruzione, si erge in alto la grande
                   chiesa, dove i monaci abitualmente si riuniscono per pregare la loro divinità. Sul lato
                   sinistro del monastero, sono invece disposte le abitazioni dei vari monaci. Nonostante
                   il loro isolamento, i monaci che vivono qui sono abbastanza amichevoli. Nel monastero
                   vivono anche alcuni druidi e molti saggi del villaggio, che aiutano i monaci in cambio
                   di vitto e alloggio.  Ogni lato del monastero è circondato da una densa e buia foresta.
                   In alcuni punti puoi notare come alcuni alberi siano cresciuti molto vicino ai muri.
                   In questi punti, ai piedi delle fortificazioni vi sono evidenti crepe, causate dalle
                   radici degli alberi. Anche il cancello principale è arrugginito e in uno stato così
                   pessimo che prima o poi potrebbe cedere e crollare su qualcuno.
                   Notando lo stato di povertà in cui i monaci vivono, ti senti in colpa e pensi che forse
                   non sarebbe una cattiva idea andare a fare una piccola donazione.`n");
                addnav ("O?`&Osservatorio","osservatorio.php");
                addnav ("T?`4Torna al villaggio","village.php");
                break;
        }
        break;


    case "periferia":
        output ("`n`&Osservando la piazza del mercato, noti che subito dopo di questa, vi è un campo di
                zingari. Le tende di costoro, divise dalla piazza e dalle stalle di Merick, solo da
                piccoli edifici, si trovano subito dietro il capannone di Pegasus. Nonostante questo
                però, l'accampamento degli zingari viene ritenuto già periferia del villaggio. Osservando
                meglio il campo, noti una stradina che l'attraversa, la quale porta verso le montagne.
                Aumentando la potenza del binocolo, vedi una grotta con un grosso orco che vi la guardia.
                Non molto lontano noti anche un'altra grotta, la quale però non è sorvegliata da nessuno.
                Oltre a questo non scorgi nient'altro d'interessante, tranne qualche fattoria troppo
                lontana dalla sua tenuta.`n");
        output ("`n`&  `n");
        output ("`n`&Miniera `7- Scoperta da poco, la vecchia miniera è già da qualche anno che viene
                continuamente esplorata dai cercatori di ricchezze. Nonostante attiri molte persone, la
                miniera rimane ancora quasi del tutto inesplorata, per cui ci sono frequenti crolli nei
                cunicoli situati nei livelli inferiori. Davanti all'entrata della minierà, c'è un grosso
                orco con una gamba di legno che tiene in mano una frusta.`n");
        output ("`n`&  `n");
        output ("`n`&Grotta di Karnak `7- Luogo spettrale da cui vedi distintamente uscire fumi di
                zolfo. Questo è il luogo di riunione per i malvagi del villaggio. Al lato della grotta,
                vi è un piccolo canale che fa fuoriuscire il sangue degli sgriossini che vengono
                abitualmente sacrificati dai seguaci di Karnak.`n");
        output ("`n`&  `n");
        output ("`n`&Campo degli zingari `7- Campo nomadi situato dietro la piazza, residenza degli zingari
                della zona, popolo a cui appartiene anche Pegasus. Per quanto questi esseri umani siano 
                disprezzati, sai che nelle loro tende puoi trovare cose molto interessanti.`n");
        addnav ("O?`&Osservatorio","osservatorio.php");
        addnav ("T?`4Torna al villaggio","village.php");
        break;


    case "piazzacentrale": //centro del paese
    output ("`n`&Guardando dal telescopio vedi la piazza del villaggio e le varie piazze. Tra queste
                riconosci `1Piazza centrale`&. Questa piazza, situata al centro del villaggio,
                è situata accanto ad un'altra piazza, Piazza del mercato, quasi da formare un tutt'uno. Al
                centro, a dividere le due piazze si trova una fontana con tre statue all'interno dell'acqua.
                Aumentando la potenza del tuo binocolo riesci a riconoscere le tre statue. Tutte e tre
                hanno forma umana, ma la prima ha dietro delle ali da pipistrello, mentre la seconda ha
                delle ali piumate. Le prime due statue sono in piedi e impugnano rispettivamente una
                scure e una spada, puntandosi le armi contro. La terza statua, al centro tra le due,
                rappresenta invece un nano barbuto che seduto su uno sgabello con un martello da fabbro
                che lavora su qualche arma. Alla sua sinistra, ai piedi della prima statua vedi scuri e
                asce, mentre alla sua destra noti spade, ai piedi della seconda statua. Accanto alla
                fontana sono situate delle panchine, sempre di pietra, divise da grandi vasi di fiori.
                Oltre alla fontana, nelle due piazze trovi anche qualche albero gigantesco che con la
                sua folta chioma protegge dalla pioggia ma anche dal sole accecante. Le due piazze sono
                attraversate dalla via principale, 'Viale delle lame'. All'estremità della `1Piazza
                centrale`& noti l'imponente chiesa di Sgrios. Accanto noti l'edificio del fabbro Oberon.
                Dalla piazza, inoltre scorgi una piccola stradina che porta alle tenute residenziali:
                le case dei Signori. La stradina porta anche alle fattorie che si trovano accanto alle
                tenute dei signori.`n");
    output ("`n`&  `n");
    output ("`n`&Chiesa di Sgrios `7- Imponente chiesa che si erge tra i piccoli tetti delle case
                vicino. Luogo di riunione per i buoni del villaggio. Al lato della chiesa vi è una
                piccola fontanella da cui sgorga il sangue rosso dei karnakkiani che vengono abitualmente
                sacrificati dai fedeli. `n");
    output ("`n`&  `n");
    output ("`n`&Oberon il fabbro `7- Officina dove vengono prodotte armi di qualsiasi tipo, per
                buoni e malvagi. Qui hanno luogo le riunioni dei vari fabbri che decidendo di non
                appartenere né al bene né al male, non si vogliono immischiare nella guerra tra le sette.`n");
    output ("`n`&  `n");
    output ("`n`&  Area residenziale `7- Dalla piazza, seguendo una piccola stradina, arrivi fuori
                dalla città. Subito dopo aver oltrepassato i muri di Rafflingate trovi che la stradina
                si divide in tanti piccoli sentieri che portano ognuno ad una tenuta diversa. Aguzzando
                lo sguardo, vedi anche le fattorie dei vari signori e i contadini che lavorano la terra.`n");
    addnav ("O?`&Osservatorio","osservatorio.php");
    addnav ("T?`4Torna al villaggio","village.php");
    break;


    case "piazzadelmercato":
        output ("`n`&Guardando dal telescopio vedi la piazza del villaggio e le varie piazze. Tra queste
                riconosci `1Piazza del mercato`&. Questa piazza, situata quasi al centro del villaggio,
                è situata accanto ad un'altra piazza, Piazza centrale, quasi da formare un tutt'uno. Al
                centro, a dividere le due piazze si trova una fontana con tre statue all'interno dell'acqua.
                Aumentando la potenza del tuo binocolo riesci a riconoscere le tre statue. Tutte e tre
                hanno forma umana, ma la prima ha dietro delle ali da pipistrello, mentre la seconda ha
                delle ali piumate. Le prime due statue sono in piedi e impugnano rispettivamente una
                scure e una spada, puntandosi le armi contro. La terza statua, al centro tra le due,
                rappresenta invece un nano barbuto che seduto su uno sgabello con un martello da fabbro
                che lavora su qualche arma. Alla sua sinistra, ai piedi della prima statua vedi scuri e
                asce, mentre alla sua destra noti spade, ai piedi della seconda statua. Accanto alla
                fontana sono situate delle panchine, sempre di pietra, divise da grandi vasi di fiori.
                Oltre alla fontana, nelle due piazze trovi anche qualche albero gigantesco che con la
                sua folta chioma protegge dalla pioggia ma anche dal sole accecante. Le due piazze sono
                attraversate dalla via principale, 'Viale delle lame'. All'estremità della `1Piazza del
                mercato`& noti la 'Vecchia Banca' con accanto la prigione e la casa dello sceriffo.
                Spostando più in la il tuo sguardo, noti l'armeria di Mighty e il carrozzone di Pegasus,
                dove rispettivamente puoi comprare le varie armi e armature. Dietro al carrozzone della
                vecchia zingara noti le stalle di Merick.`n");
        output ("`n`& `n");
        output ("`n`&Armi di Mighty `7- Edificio accanto al carrozzone di Pegasus, è gestito da un
                grande guerriero e mercante d'armi, Mighty. Anche fuori del negozio puoi notare
                l'esposizione delle varie armi, dai piccoli coltellini a grandi spadoni. Sai però che
                non ti conviene provare a rubare qualcosa, perchè potresti finire da Ramius.`n");
        output ("`n`& `n");
        output ("`n`&Armature di Pegasus `7- Carrozzone accanto all'edificio di Mighty, è gestito da
                una giovane gitana di nome Pegasus. Dietro il carrozzone noti le stalle di Merick.
                Nonostante sia una giovincella, sai che le sue armature sono affidabili e molte volte
                ti hanno salvato dai tuoi nemici. `n");
        output ("`n`& `n");
        output ("`n`&Vecchia banca `7- Uno dei primi edifici di Rafflingate e uno dei pochi ancora
                costruito in legno. La vecchia banca si trova accanto alla casa dello sceriffo e alla
                prigione. Oltre a questo noti con il binocolo, uscire dalla banca un uomo grosso almeno
                il triplo di te, che si guarda intorno per scoprire eventuali malintenzionati, e poi
                torna dentro la banca. Il tizio in questione è il famigerato Guido, assassino da quando
                aveva 6 anni, che decidendo di cambiare stile di vita, venne assoldato dalla banca come
                guardiano.`n");
        output ("`n`& `n");
        output ("`n`&Sceriffo `7- L'unico difensore della legge al villaggio di Rafflingate, insieme al
                vice-sceriffo, abita in una vecchia casa situata accanto alla banca. Accanto alla casa,
                invece è situata la prigione, dove i malfattori acchiappati dallo sceriffo vanno a
                finire.`n");
        addnav ("O?`&Osservatorio","osservatorio.php");
        addnav ("T?`4Torna al villaggio","village.php");
        break;


    case "stradadellataverna":
        output ("`n`&Guardando dal telescopio vedi la piazza del villaggio e le varie strade. Tra
                queste riconosci la `1Strada della taverna`&. Una piccola stradina che parte dalla strada
                principale, 'Viale delle lame', ed arriva fino alla taverna, dopodiché gira intorno a questa
                finendo in un vicolo. Di fronte alla taverna invece troviamo la locanda. Dietro a questa,
                un po' a sinistra del carrozzone di Pegasus troviamo una piccola capanna accanto ad una
                grande stalla.  Accanto, vedi un'altra piccola stradina. Sforzando la vista e la memoria
                riconosci la stradina che usi spesso per andare ai giardini. Aumentando la precisione
                del cannocchiale riesci anche a vedere il bivio dove la piccola stradina si divide. I
                due sentieri che si formano portano reciprocamente ai giardini, quello a sinistra, e
                alla Roccia curiosa. Ritornando con lo sguardo sulla `1Strada della Taverna`& noti le
                varie abitazioni tra cui riconosci la casa che ospita le docce pubbliche. Scorgi inoltre,
                anche un altro senti ero che parte dalle stalle e che porta ad un vecchio Capanno da caccia.`n");
        output ("`n`& `n");
        output ("`n`&Taverna del Drago `7- Taverna di Rafflingate. Si trova alla fine della 'Strada
                della taverna' da cui quest'ultima prende appunto il nome. Costruita interamente di
                legno, è stata una delle prime costruzioni presenti nel villaggio. Sopra la porta, pende
                una grossa insegna '`@Tavola calda del Drago`7'. Dietro la taverna, la strada finisce in
                un vicolo. Qui vi sono solo gatti randagi e la spazzatura dei resti del cibo che ogni
                giorno viene sprecato in taverna.`n");
        output ("`n`& `n");
        output ("`n`& Locanda `7- Si trova davanti alla taverna. Anche questa costruzione in legno,
                presenta una grossa insegna con su scritto '`@Locanda Testa di Cinghiale`7'. Dalle
                porte e dalle finestre, puoi notare come il fumo di pipa e tabacco, esca formando una
                grossa cortina di foschia. Accanto alla locanda, vi è una piccola stradina che porta sul
                retro. Qui, Cedrik, tiene i suoi barili di birra uno sopra l'altro. Ti accorgi subito
                come l'altezza a cui arrivano i barili possa esserti molto utile nell'intrufolarti nella
                locanda per ammazzare qualcuno.`n");
        output ("`n`& `n");
        output ("`n`&Stalla di Merick `7- Dietro alla locanda, un po' a sinistra del carrozzone di
                Pegasus vedi un piccolo capanno, e una grande stalla accanto. Davanti alla stalla vi è
                un piccolo magazzino in cui Merick tiene il fieno per i cavalli. Oltre alla stalla
                principale, dove vi sono cavalli, poni, puledri, vi è un'altra stalla più piccola
                rinforzata con il metallo dove Merick tiene gli orsi bruni, i grizzly e le bestie mitiche.
                Gli uccelli, pixie e l'oca d'oro sono tenuti nella capanna di Merick.`n");
        output ("`n`& `n");
        output ("`n`& Docce pubbliche `7- Tra le varie abitazioni, riconosci quella dove si trovano le
                docce. Riconosci immediatamente l'abitazione grazie alle sporcizie, che escono dalla
                casa. Guardando gli oggetti che si trovano per terra noti asciugamani, saponette,
                spazzole, e altra sporcizia.`n");
        output ("`n`& `n");
        output ("`n`& Capanno da caccia `7- Accanto alle stalle scorgi un sentiero che porta ad un capanno.
                Questo in passato era usato come capanno da caccia, da cui ha preso il nome, ma adesso
                che la foresta è più lontana a causa del continuo bisogno di legno, rimane inutilizzata.
                Ultimamente, sai che ci dimora un vecchio poveraccio.`n");
        output ("`n`& `n");
        output ("`n`&Roccia curiosa `7- Seguendo il sentiero con il cannocchiale, arrivi a guardare una
                grossa roccia nera, simile a quella che si trova presso l'arena al villaggio, in cui è
                incastrata la spada. Il sentiero che porta alla roccia, continua verso la foresta.
                Non noti nient'altro d'interessante.`n");
        addnav ("G?`&Giardini","osservatorio.php?op=giardini&op1=Giardini");
        addnav ("O?`&Osservatorio","osservatorio.php");
        addnav ("T?`4Torna al villaggio","village.php");
        break;

        if (!isset($session)) exit();
    case "giardini":
        switch ($_GET['op1']) {
            case "Giardini":
                $session['user']['specialinc']="";
                output ("`n`&Giardini `@- Il sentiero porta ai giardini dove percorri attraverso un arco.
                   Noti più avanti i tanti sentieri che s'intrecciano nei giardini ben curati. Dalle
                   aiuole di fiori, che fioriscono anche d'inverno, alle siepi, le cui ombre promettono
                   segreti, questi giardini sono un rifugio per tutti quelli che sono alla ricerca del
                   Drago Verde. Qui possono dimenticare le loro preoccupazioni e rilassarsi un po'.
                   Noti anche che nel giardino svolazzano tante fate. Al centro del giardino si trova
                   un grande lago, dove si può fare anche il bagno. All'estremità nord, una piccola
                   cascata alimenta il laghetto. Alla sinistra, noti una grande quercia con una fune
                   legata ad uno dei suoi rami. È stata ovviamente usata molte volte per divertimento.
                   Osservando cosa fanno le varie coppie, noti ogni tanto il `1Padrone del reame `@che
                   passeggia per i giardini e noti divertito come tutti scappino davanti a lui.`n");
                addnav ("O?`&Osservatorio","osservatorio.php");
                addnav ("T?`4Torna al villaggio","village.php");
                break;
        }
        break;


    case "vialedellelame":
        output ("`n`&Guardando dal telescopio vedi la piazza del villaggio e le varie strade. Tra
                queste riconosci quella principale `1Viale delle lame `&. A dispetto di quello che
                pensavi noti con molta curiosità che questa strada è molto frequentata, e non solo da
                gente ricca e ben vestita, ma anche da straccioni, contadini, minatori e altre persone
                che con la ricchezza non ci azzeccano nulla. Sui bordi della strada scorgi un'infinità
                di negozi, grandi e piccini. Tra questi riconosci la tua palestra dove spesso ti alleni.
                Subito accanto vedi un grande edificio. L'edificio visto da qua è ancora più imponente.
                Riconosci che l'edificio è la Sala degli eroi. In fondo alla strada noti una grande
                arena. Vicino a questa, si trova una grande roccia nera, con una spada conficcata al
                centro.`n");
        output ("`n`& `n");
        output ("`n`&Campo d'allenamento `7- Questa palestra se pur grande, rimane piccolina a confronto
                con il grande edificio che si trova accanto. Davanti all'entrata vi è la scritta :
                `@It's time to fight. `7Guardando attraverso il cannocchiale ti diverti a vedere come
                i maestri rincorrono i propri allievi perché non si presentano ai corsi. Purtroppo sai
                che anche tu spesso sei rincorso da loro.`n");
        output ("`n`& `n");
        output ("`n`&Sala degli eroi `7- Enorme edificio, situato accanto alla palestra, rappresenta le
                tue massime aspirazioni. Infatti, è proprio in questo luogo che sono descritte le opere
                degli eroi. Sai inoltre che per poter paragonare le tue abilità con gli altri, basta
                visitare la sala centrale dove appaiono i nomi dei migliori.`n");
        output ("`n`& `n");
        output ("`n`&Spada nella roccia `7- In fondo alla strada principale vedi l'arena e accanto a
                questa una grossa pietra con dentro una spada. Dalla tua postazione, puoi vedere come
                ognuno si sforzi di tirarla fuori, senza però grandi risultati. Sai però che quella
                spada, se venisse estratta, conferirebbe grandi poteri al suo possessore.`n");
        output ("`n`& `n");
        output ("`n`&Arena `7- In fondo alla strada principale noti l'arena. Enorme e possente, sai
                che la puoi veramente vedere quanto vali. Infatti vi vengono organizzati i vari
                combattimenti. Inoltre, puoi andare all'arena anche per assistere ai combattimenti tra
                altri e per incitare i tuoi beniamini.`n");
        addnav ("O?`&Osservatorio","osservatorio.php");
        addnav ("T?`4Torna al villaggio","village.php");
        break;


}
page_footer();
?>

