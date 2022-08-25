<?php
/**************************************
Monastery druid -                     *
Written by Robert for Maddnet LoGD    *
Tradotto da Excalibur per www.ogsi.it *
***************************************/
require_once "common.php";

addnav(" Le Lezioni del Druido");
addnav("(C) Creature","mondruido.php?op=creatures");
//addnav("(N) Nani","mondruido.php?op=nani");
addnav("(N) Notizie Giornaliere","mondruido.php?op=news");
addnav("(F) Frutteto","mondruido.php?op=frutteto");
if (getsetting("nomedb","logd") == "logd2"){
   addnav("(O) Oro & Gemme","mondruido.php?op=orogemme");
   addnav("(B) Bagni Pubblici","mondruido.php?op=bagni");
}else{
   addnav("(G) Gemme","mondruido.php?op=gemme");
   addnav("(O) Oro","mondruido.php?op=oro");
}
addnav("(I) PIetre di Aris","monpietre.php");
addnav("(Z) Zingari","mondruido.php?op=zingari");
addnav("(P) PvP","mondruido.php?op=pvp");
addnav("(L) Log Off","mondruido.php?op=logoff");
addnav("(T) Titoli","mondruidotitoli.php");
addnav("(Q) Quest","mondruidoquest.php");
addnav("(A) Animali","monmount.php");
addnav("(R) Carriere","moncarriere.php");
addnav("(V) Vigneto","mondruido.php?op=vigneto");
addnav("??`^`bMiniFAQ`b","hints.php?ret=".urlencode($_SERVER['REQUEST_URI']));
addnav("Abbandona");
addnav("(M) Monastero","monastero.php");

page_header("Il Druido del Monastero");
output(" `^`c`bApprendi dal Druido del Monastero`b`c `n`n");
if (getsetting("nomedb","logd") == "logd2"){
output("`3Oggi sei venuto al monastero in cerca di conoscenza e come sai, devi rivolgere i tuoi quesiti al più ");
output("saggio ed anziano dei tutori, colui che ha insegnato a tutti i tutori del villaggio il loro mestiere, ");
output("le cose da dire e come farle apprendere ad un testone come te; sai che devi rivolgere i tuoi quesiti al ");
output("`&Venerabile Druido`3.`nSei un poco intimorito, ma sai che è un uomo molto saggio e benevolo con chi ha ");
output("mille curiosità come te.`nBussi alla porta della sua camera, una piccola stanzetta vicino alla biblioteca, ");
output("ove lui conserva i libri che reputa più preziosi, e attendi la sua risposta.`n`n");
}else{
  output(" `n`n`n`n`n`&Ti trovi di fronte al `3Druido del Monastero`&. `n");
  output(" Il Druido conosce molte lezioni e parole di speranza. `n");
  output(" Forse potresti apprendere qualcosa di interessante oggi.`n`n");
}

if ($_GET['op']=="nani"){

output(" `&E' giusto che tu sia messo in guardia al riguardo dei Nani. `n");
output(" `6Essi sono creature avide, e il furto è un'abilità loro innata dalla nascita.`n");
output(" Passare vicino ad un Nano può essere causa di una perdita essendo loro esperti nell'arte del borseggio. `n");
output(" Entrare nel loro regno, è come elemosinare problemi, non per loro ma per te!. `n");
}

if ($_GET['op']=="frutteto"){
output(" `&Il Frutteto del Monastero produce cibo per i Monaci che vivono qui. `n");
output(" `3Esso è protetto da forze divine contro i ladri. `n");

}
if ($_GET['op']=="zingari"){
output(" `&Gli Zingari viaggiano liberamente e vanno dove vogliono in questo mondo. `n");
output(" `3Essi hanno un campo vicino al Villaggio, e non amano i visitatori così si spostano spesso. `n");
output(" Molti di loro evitano ogni contatto, nascondendosi quando si avvicina un viaggiatore. `n");
output(" Mi è stato riferito, che alcuni Zingari posseggano il potere di leggere nel futuro.");
}

if ($_GET['op']=="news"){
output(" `&Le Notizie Giornaliere sono MOLTO importanti per conoscere gli ultimi avvenimenti. `n");
output(" `6Un'occhiata alle News ogni tanto può essere interessante e divertente allo stesso tempo. `n");
output(" Alcuni eventi appaiono nelle News, eventi che riguardano te o i tuoi compagni di avventura. `n");
}

if ($_GET['op']=="traveler"){
output(" `&You stop a Traveler coming out of the Castle. `n");
output(" `6He says to you, parts of `3Castle Gwen `6is closed to Travelers for how long I don't know, `n");
output("Rumor is that Dwarves tried to sneak their way in. A warning to you, `bbeware of Dwarves`b, now you know! `n");
}

if ($_GET['op']=="vigneto"){
output(" `&I Monaci che vivono qui hanno un Vigneto e hanno coltivato le loro uve attraverso i secoli. `n");
output(" `6I Monaci mangiano i grappoli d'uva e producono dell'ottimo vino dagli acini, `n");
output(" il vino è spesso usato in cerimonie religiose all'interno del monastero e per un piccolo obolo essi `n");
output(" condivideranno il loro `i`5speciale Vin Santo`i`6 con ogni visitatore che verrà qui e farà un'offerta. `n");
}

if ($_GET['op']=="oro"){
output(" `&L'Oro è necessario per acquistare qualunque cosa. `n");
output(" `6Il suo scopo principale è per acquistare Armature e Armi per incrementare le tue abilità nel combattimento. `n");
output(" `6E' anche utilizzato per pagare alcuni oggetti nei negozi che sono aperti per commerciare. `n");
output(" L'Oro è il premio per l'uccisione delle creature nella foresta.`n");
output(" Lo si può trovare durante alcuni eventi speciali che incontri nella Foresta.`n");
output(" Lo si può anche estrarre dalla Miniera se vuoi, e se sei fortunato ad imbatterti in essa.`n");
//output(" Estrarre Oro alla Miniera degli Gnomi è molto pericoloso, poichè ti troverai nel loro regno.");
}

if ($_GET['op']=="gemme"){
output(" `&Le Gemme sono di vitale importanza e dovrai procurartene quante più possibile. `n");
output(" `6Puoi trovare le Gemme nella Foresta, combattendo le creature o grazie ad alcuni eventi speciali. `n");
output(" Le Gemme possono anche essere acquistate al Negozio di Vessa. `n");
output(" Le Gemme sono usate al posto dell'Oro come moneta in alcuni negozi per beni e servizi. `n");
output(" Non puoi acquistare Gemme quando è giunto il momento di uccidere il Drago Verde. `n");
}

if ($_GET['op']=="orogemme"){
output("`3La porta della camera si apre silenziosamente e ti ritrovi innanzi ad un anziano monaco dal viso ");
output("rubicondo e felice.`n\"`XL'oro e le gemme che vi procurerete in foresta, non solo uccidendo creature, ma anche con diversi ");
output("eventi speciali, vi serviranno per le vostre necessità quotidiane, a cominciare dall'acquistare pozioni ");
output("rigeneratrici alla capanna del guaritore (che sono gratis al primo livello e possono invece essere molto ");
output("care, man mano che si sale!) fino al mangiare, dormire e tenere pulita la vostra persona, ma non solo; ");
output("potrete acquistare armi, amuleti, pozioni magiche, pagare la vostra istruzione, il vostro apprendistato ");
output("presso la bottega di qualche bravo fabbro o il laboratorio di un mirabile mago e molte altre cose che ");
output("scoprirete visitando il villaggio e le sue botteghe.`nTra queste botteghe ve ne son alcune disposte anche ");
output("a barattare i vostri soldi con gemme preziose e viceversa, ma fate attenzione che il cambio quasi mai ");
output("andrà a vostro vantaggio.`3\"");
}

if ($_GET['op']=="bagni"){
output("`3\"`XAvanti`3\"`nti dice una voce gentile.`nApri la porta e ti trovi di fronte la schiena del druido ");
output("curva su una spoglia scrivania, egli è intento a leggere, alla debole luce di un lume, alcune pergamene ");
output("dall'aria molto antica.`nSi volta e ti sorride benevolo.`n`n\"`XCaro giovane, forse non è a me che dovete ");
output("chiedere come prendervi cura della vostra persona, ma alla vostra dolce madre, però probabilmente voi ");
output("volete solo sapere ove si trovano in questo villaggio i luoghi per mantenervi pulito e curato ed io son ");
output("qui per rispondere alle vostre mille curiosità!`n`nAvete già notato che le attività quotidiane, le aspre ");
output("battaglie in foresta, gli allenamenti in palestra e gli esercizi di pratica dell'arte che avete scelto, ");
output("sporcano il vostro corpo ed i vostri abiti e vi fanno aumentare la fame, mentre il bere eccessivamente, ");
output("la birra alla Locanda di Cedrik o il Vin Santo dei monaci al monastero, ingrosserà la vescica vostra a ");
output("dismisura causando indebite danze nel tentativo di trattenervi.`n`nConseguenza ulteriore della scarsa cura ");
output("di voi la perdita continua di preziosi punti fascino, argomento di cui parleremo un altro giorno.`n`n");
output("Ogni giorno dovrete quindi recarvi alle docce pubbliche per prendere la vostra doccia ed evitare così di ");
output("emettere odori eccessivamente sgradevoli, causa di appellativi poco simpatici da parte degli altri ");
output("guerrieri, così malvagi a volte da poter arrivare a chiamarvi Pigpen fino al giorno in cui non ucciderete ");
output("il vostro prossimo `@Drago Verde `Xlavandovi così col suo sangue.`n`nE' sicuramente bene anche andare a ");
output("svuotare la vescica nei bagni pubblici al limitare della foresta, prima che sia troppo tardi; non siate ");
output("troppo tirchio quando vi andate e non scordate di lavarvi le mani, potreste trovare piacevoli sorprese ");
output("vicino ai lavandini. Anche alla Grotta dello Gnomo (presso Castel Excalibur) troverete bagni puliti e luminosi.`n`n");
}

if ($_GET['op']=="pvp"){
if (getsetting("nomedb","logd") == "logd2"){
  output("`3Il Druido vi apre silenziosamente la porta.`n\"`XManifestate grande curiosità, car".($session['user']['sex']?"a":"o")." ");
  output($session['user']['name']."`X, e questa dev'essere giustamente ricompensata; dunque sia, oggi vi narrerò ");
  output("degli scontri `(PvP `X(Player versus Player) caro pupillo, sedete qui vicino a me.`3\"`nSi accomoda e batte ");
  output("leggermente la mano su un piccolo scranno.`n`n\"`XSe intrapresi con la solita attenzione di cui sempre mi ");
  output("raccomando, essi possono portarvi più velocemente ad apprendere le tecniche che il maestro tanto brama ");
  output("veder usare quando lo sfidate (acquisisci esperienza più in fretta).`nPotrete cercare i vostri nemici lì ");
  output("ove riposan le stanche membra, durante il loro sonno quotidiano ... taluni incauti nei campi;  tal altri ");
  output("in locanda, avrete accesso alle loro stanze dando una piccola mancia al sempre gentile Cedrik, non siate ");
  output("nè troppo tirchio nè troppo genereso con lui e vi darà le chiavi delle stanze di coloro che son pari ");
  output("livello al vostro!`nTal altri ancora li troverete al dormitorio del municipio, ma dovete esser assai ");
  output("malvagio per potervi entrare così di soppiatto ... e data la vostra cattiveria, ovviamente non potrete ");
  output("riposarvi voi stesso; infine potrete trovarli nelle loro tenute o in quelle di amici ... ma vi sconsiglio ");
  output("per ora di cercali in tale luogo, almeno finchè non avrete acquisito ultreriori capacità!`3\"`n`nFacendosi ");
  output("pensieroso aggiunge`n\"`XScusate ".($session['user']['sex']?"milady":"messere").", un dubbio atroce ");
  output("attanaglia la mente mia ... non credo di essermi ben spiegato per quanto riguarda l'accesso dei campi ... ");
  output("non dovete addentrarvi in essi per le porte principali, attraverso le quali diventereste voi stesso preda ");
  output("dei predoni, ma passando dal sentiero che sinuoso procede dalla `4Via della Lame `X(opzione uccidi un ");
  output("giocatore).`3\"`n`nSorride amorevolmente`n\"`XVi prego di scusarmi ancora se son stato poco chiaro nella ");
  output("mia spiegazione, spero ora di aver fugato ogni dubbio ... sebbene ve lo debbo confessare, pochi ormai son ");
  output("gli incauti che dormono nei campi, il più delle volte sarete costretto a spender denaro per uccider i ");
  output("vostri nemici.`3\"");
}else{
  output("`&È possibile Uccidere altri Player. Nel caso tu dovessi scegliere quest'azione, clicca sul link nel ");
  output("Villaggio e troverai la tua `bvittima`b nei campi. Puoi anche corrompere l'Oste Cedrick e penetrare in ");
  output("una stanza `ndella locanda. Fare PvP potrebbe costarti molto in termini di Esperienza. Attenzione però, ");
  output("se ti fai la reputazione di assassino (uccidendo gli altri player nel sonno), aspettati che il ");
  output("`ifavore`i ti venga ricambiato. `nSe uccidi qualcuno nel sonno, puoi fare un gesto carino mandando loro ");
  output("un regalo dal negozio di Cassandra (lo trovi nei Giardini).`n");
}
}
if ($_GET['op']=="logoff"){
if (getsetting("nomedb","logd") == "logd2"){
  output("`3Lentamente si alza, al suono di una mano che bussa alla sua porta, e viene ad aprirvi:`n");
  output("\"`XBenvenut".($session['user']['sex']?"a Lady":"o Sir")."`3\"`nvi saluta`n\"`XNuovamente al mio cospetto, volete ");
  output("ordunque sapere quale sia il miglior luogo ove riposar le vostre stanche membra, ed io debbo confessarvi ");
  output("che tutto dipende da quanto siete disposto ad investire per il vostro riposo.`nIl modo più semplice e ");
  output("meno oneroso è indubbiamente stender il mantello vostro sull'erba umida di rugiada dei `@Campi `Xe ");
  output("chiuder gli occhi, incurante dei condottieri che intorno al vostro corpo, nell'ombra, s'aggirano in cerca ");
  output("di incauti da derubare.`nSe invece siete desideroso di una maggior tutela e siete disposto ad investire ");
  output("in essa parte del vostro oro avrete due alternative, valide entrambe ma non completamente pari, ove poter ");
  output("riposare al sicuro, da tutto, tranne che dalla malvagità dell'altrui cuore: potrete affittare quindi, per ");
  output("l'intera giornata, una camera alla `@Locanda `Xo, una volta diventato cittadino di Rafflingate, occupare ");
  output("un letto al dormitorio allestito nei meandri più bui del `#Municipio`X.`nRicordate però che né nell'uno, ");
  output("né nell'altro luogo sarete completamente al sicuro ... qui chiunque lo desideri potrà, corrompendo il ");
  output("rubicondo Cedrik con pochi ori o gemme, ottenere la chiave della vostra stanza, lì, alcuni dotati di una ");
  output("malvagità superiore, accederanno senza pericoli al vostro giaciglio; ed in entrambe i casi vi uccideranno ");
  output("senza pietà ripulendo le vostre tasche dai tesori che vi tenete celati.`nInfine poi, il miglior connubio ");
  output("tra sicurezza ed economicità è potersi stendere tra le soffici e calde lenzuola di una tenuta, nella ");
  output("privacy di una stanza tutta vostra.`nAvrete visto sicuramente, quelle già costruite nell'area ");
  output("residenziale, esse appartengono a color che tra voi hanno già compiuto un giro intero della loro vita e ");
  output("si son reincarnati ... voi per adesso potete solo sperare di trovar un'anima pia che, con grande ");
  output("generosità, vi consegni una delle chiavi della sua tenuta!`nInvero la malvagità altrui non risparmia ");
  output("nessuno, ma in tenuta sarete sicuramente più tutelato, da quanto siete forte voi, se la casa vi ");
  output("appartiene, ovvero da quanto son forti coloro che dormon insieme con voi.`3\"`n`n");
}else{
  output(" `&Ci sono 3 modi per uscire dal game, andando Nei Campi, affittando una camera alla Locanda o,`n se ti sei reincarnato andando alla Tua Dimora. `n");
  output(" `6Esci nei Campi ti farà uscire dal gioco, e li dormirai fino al tuo ritorno, gli altri guerrieri `n");
  output(" possono trovarti nei campi e combattere con te. Non puoi fare nulla per impedirglielo. `n");
  output(" Tutti coloro che scelgono di dormire nei Campi sono giocatori leali nei confronti di tutti gli altri. `n");
  output(" Affittando una camera alla Locanda, sarai al sicuro da tutto tranne che dalla malvagità dei giocatori che `n");
  output(" corrompendo l'oste Cedrik, otterranno un passpartout per la tua stanza. `n");
  output(" Dormendo nella tua dimora, sarai più protetto che alla locanda, infatti chi tenterà di ottenere la chiave `n");
  output(" della tua casa dai briganti, oltre che pagare profumatamente la chiave non avrà la sicurezza di riuscire `n");
  output(" ad aprire la porta. Le chance di riuscita dipendono dal numero di reincarnazioni che avrai fatto, maggiore `n");
  output(" sarà il numero di volte in cui ti sei reincarnato e minore sarà la possibilità che avranno i malintenzionati `n");
  output(" di penetrare all'interno.");
}
}

if ($_GET['op']=="creatures"){
if (getsetting("nomedb","logd") == "logd2"){
  output("`3La porta della camera si apre silenziosamente e ti ritrovi innanzi ad un anziano monaco dal viso ");
  output("rubicondo e felice.`n  Ti sorride intuendo già la tua domanda:`n\"`XEntrate giovane ".$session['user']['title']." ");
  output("`Xsedete con me oggi parleremo a lungo`3\"`nSi accomoda su morbidi cuscini e inizia il suo racconto`n\"`X");
  output("Poiché siete appena giunto in questo villaggio capisco che siate ansioso, come i vostri pari, di sapere ");
  output("come fanno gli abitanti a vivere in queste terre desolate ... dovete sapere che qui esiste un'unica legge ");
  output("... quella del più forte. Ogni uomo ed ogni donna del villaggio deve, ad ogni alba che si sussegue, ");
  output("dimostrare di esser il più forte, di sapersi procacciare da solo il cibo, l'oro e le gemme per sopravvivere ");
  output("e vi è un unico luogo, da tutti frequentato, ove questo è possibile, esso è: la `2Foresta`X.`nLì incontrerete ");
  output("miriadi di creature, le più disparate e bizzarre, disposte a battersi fino alla morte per ottenere quel ");
  output("ch'anche voi cercate.`nLa vostra forza e ricchezza s'accrescerà in relazione a quante più creature sarete ");
  output("in grado di ammazzare, ma state attento, anche le loro capacità si accresceranno di pari passo alle vostre.`n");
  output("Per trovare la Foresta, seguite sicuro la via delle Lame ed una volta giunto al suo limitare, potrete ");
  output("sceglier di seguire strade diverse: potrete tranquillamente cercar vostri pari o più forti e temibil ");
  output("davvero, ma se siete pavido o troppo debole, potrete sicuramente addentrarvi ove le creature son meno ");
  output("minacciose e cercar ivi la vostra fortuna.`3\"`nFinito che ha di parlare osserva i tuoi occhi e il tuo volto ");
  output("stupefatto per le cose che ti ha rivelato, sorride compiaciuto della tua attenzione e prosegue con altre ");
  output("rivelazioni importanti.`n`n");
}else{
  output(" `&Le creature della Foresta possono essere le più disparate o bizzarre che mai incontrerete. `n");
  output(" `6Il tuo scopo principale è di incontrare e uccidere le creature della Foresta. `n");
  output(" Facendo ciò guadagnerai oro ed esperienza. `n");
  output(" Stai certo del fatto che mentre procedi e migliori le tue abilità, così faranno le creature della Foresta, `n");
  output(" e mentre i tuoi HitPoints aumenteranno, le creature diventeranno più forti e toste da affrontare. `n");
}
}
page_footer();
?>