<?php
require_once "common.php";
if ($session['return']==""){
   $session['return']=$_GET['ret'];
}
if ($session['user']['loggedin']) {
    checkday();
    if ($session['user']['alive']) {
        if ($session['return']==""){
        }else{
            $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
            $return = substr($return,strrpos($return,"/")+1);
            addnav("Torna da dove sei venuto",$return);
        }
    } else {
    }
}else{
    addnav("Torna al Login","login.php");
}
page_header("Mini F.A.Q.");
addnav("1?Parte 1","hints.php");
addnav("2?Parte 2","hints.php?op=2");
addnav("3?Parte 3","hints.php?op=3");
addnav("4?Parte 4","hints.php?op=4");
addnav("R?Reincarnazione","hints.php?op=5");
addnav("M?Oggetti Magici","hints.php?op=9");
addnav("A?Abitazioni","hints.php?op=6");
addnav("C?Le Carriere","hints.php?op=7");
addnav("L?Le Religioni","hints.php?op=8");
addnav("O?`^Odore `&- `@Vescica `&- `\$Fame","hints.php?op=10");
addnav("T?Terre dei `#Draghi","hints.php?op=11");
if ($_GET['op']==""){
output("`@Legend of the Green Dragon`7 è un gioco online che ha avuto origine da `\$Legend of the Red Dragon`7 (L.o.R.D.)
di Seth Able Robinson. `@Legend of the Green Dragon`7 (LotGD) è molto più giocabile essendo facilmente modificabile.`n");
output("Se sei nuovo di LotGD, prosegui nella lettura mio caro Uccisore di Draghi e verrai ricompensato! (Spiacente ne
`&gemme`7 ne `^oro`7, solo informazioni).`n`n");

output("Ti avviso che ciò che stai per leggere, include alcuni 'spoilers' (per coloro che amano le sorprese) e non è
certamente da considerarsi una guida COMPLETA.`n");

output("Ci sono così tante cose da apprendere su LotGD, perciò iniziamo con la lettura e smettiamola di lamentarci.`n`n");

output("Livelli dei Player`n`nQuando inizi il gioco come `&Contadino/Contadina`7, ti trovi al più basso, più vulnerabile e debole livello! `nDovrai
aspettarti di morire ... frequentemente. (ancora a lamentarti?!? continua a leggere ragazzone).`n");
output("Ad ogni titolo `&Contadino/Contadina`7, `3Esploratore`7, `2Scudiero`7, `6Legionario`7 etc . . dovrai completare 15 livelli. `n
Mentre cresci attraverso i livelli di gioco accrescerai la tua Esperienza, Armi e Armature per poter essere `nforte
abbastanza da confrontarti con il `@Drago Verde`7.`n`n");
output("Al livello 15 di ogni titolo, sarai in grado di trovare e confrontarti con il `@Drago Verde`7. Nel caso tu riesca a
battere `nil `@Drago Verde`7, verrai automaticamente avanzato al successivo titolo: `&Contadino/Contadina`7 guadagnerà il
titolo di `n`3ESPLORATORE`7. Un `3esploratore`7 che sconfigge il `@Drago Verde`7 sarà promosso a `2SCUDIERO`7 e così via ..`n`n");
output("Ricorda che essere `&Contadino/Contadina`7 vuol dire trovarsi al livello più basso e vulnerabile. Scegliere di iniziare `n
una battaglia PvP vuol dire in pratica buttar via i propri giorni. Tu sei la mosca e la maggior parte degli altri
giocatori `nsono lo schiacciamosche. `nPerdere un combattimento PvP è peggio che morire per mano di un Piccolo Coniglio
Rosa con i Denti da Roditore `nperchè non solo perderai tutto l'`^oro`7 che hai con te,
ma anche un percentuale della tua Esperienza (10% o più) `ne morirai. Se ciò non fosse sufficiente, verrai pubblicamente
umiliato con la pubblicazione della tua sconfitta `nnelle `^Notizie Giornaliere`7 !! `nSe deciderai di non seguire il mio
consiglio, almeno scegli un giocatore più piccolo (di livello più basso) di te!`n`n");
output("Sappi comunque che i combattimenti Player versus Player (PvP) SONO parte del gioco. I `&Contadini/Contadine`7 `nsono molto
ricercati perchè sono i giocatori più vulnerabili. Praticamente sei il modo più veloce e più facile per `nfare punti
Esperienza e, magari, anche un po' di `^oro`7. `nSei, ad ogni modo di vedere, un pasto gratis. Prendi il rischio e goditi
il gioco. La morte è solo temporanea `n(guarda sotto) e non è mai qualcosa di personale. A meno che, ovviamente,
non diventi un chiaccherone che si vanta  .....`n`n Alla `!Taverna del Cavallo Nero`7 (un evento speciale della foresta) hai
la possibilità di ottenere informazioni `nsugli altri guerrieri, che armi o armature stanno utilizzando, il loro potenziale
di `\$attacco`7 e `@difesa`7, quindi se `ndecidi di attaccare un altro player potresti documentarti in anticipo sulle sue statistiche.
Puoi arrivare `nalla `!Taverna del Cavallo Nero`7 anche con alcuni animali disponibili alle stalle di Merrick.`n");
output("Se, a questo punto, ti stai ancora lamentando  ... o la smetti di leggere e vai a giocare a Solitario o te ne fai `nuna
ragione e apprezzi il gioco per quello che è!`n`n");
}
if ($_GET['op']=="2"){
output("Durante il Gioco`n`n`bCapanna del Guaritore`b - si trova nella `2Foresta`7. Visitala frequentemente quando sei `&Contadino`7 (è gratis al Livello 1!).
Dopo il Livello 1, la visita può diventare costosa per una guarigione completa perciò prepara molto `^oro`7 quando ci vai.`n`n");
output("`bBagni Pubblici`b - ancora, nella `2Foresta`7. Fai una visita ogni giorno e non dimenticarti di lavare le mani. Non fare i
tuoi bisogni nella `2Foresta`7 se non qui!`n`n`bLa Taverna`b - situata nel `3Villaggio`7. C'è molto da vedere e da fare. Parla con gli altri Players e ubriacati!`n`n");
output("`bLa Vecchia Banca`b - situata nel `3Villaggio`7.`nOttieni `^oro`7 in seguito ai combattimenti vinti nella `2Foresta`7. Non rovinare tutto perdendo i tuoi sudati `nguadagni perchè
perdi un combattimento! Utilizza spesso la `bVecchia Banca`b per depositare il tuo dannato `^oro`7!`n`n");
output("Come la tua banca in R.L., La `bVecchia Banca`b ti pagherà gli interessi sull'`^oro`7 depositato. Giornalmente. (Sono sicuro`n
che la tua banca non ti ha mai dato condizioni così favorevoli). Idealmente dovresti depositare quanto più `^oro`7 possibile,`n
ogni giorno, nella Banca. Come `&Contadino`7 il tuo `^oro`7 è veramente sudato, perciò permetti alla `bVecchia Banca`b di aiutarti`n
a guadagnare più `^oro`7 tramite gli interessi (C'è un'eccezione a questa regola: non otterrai interessi se hai depositato `n
più di 100.000 pezzi d'`^oro`7, questo per evitare un accumulo sconsiderato da parte di player poco `iragionevoli`i). `n
Ritira il tuo `^oro`7 il giorno successivo e migliora la tua arma o la tua armatura all'inizio di ogni `@Nuovo Giorno`7.`n`n");
output("Se ne hai, dai una gemma alla `%FATA`7 che incontri quando te lo chiede. Ti ricompenserà SEMPRE per il tuo gesto.`n`n");
output("NON sei forte abbastanza per andare `iin cerca di brividi`i. Scegli `iTrova Qualcosa da Uccidere`i invece - è nel tuo `n
stesso interesse al livello di `&Contadino`7 di non incontrare quei demoni che sono molti più forti di te. `nQuando vai in
`iCerca di Brividi`i, ti confronterai con demoni, mostri e creature della `2Foresta`7 che sono molto più forti `ndi te sia
`\$ATTACCO`7 che in `@DIFESA`7. `iCercare i Brividi`i ti farà combattere una creatura che può essere uno o più livelli `nsopra
di te! (Non fare lo stupido quindi, a meno che a te non piacca quel genere di cose, maledetto autolesionista).`n`n");
output("La Morte (al contrario del Mondo Reale) è una condizione temporanea. Si può considerare simile ad un mal di testa `n
dopo una sbronza di birra. Quando muori (e morirai sicuramente) verrai trasportato nella Terra delle Ombre. `nPotrai andare
al Cimitero e Tormentare delle creature o cercare favori da Ramius per la tua Resurrezione! `nPuoi anche semplicemente
scollegarti e ripartire il seguente giorno di gioco. Puoi anche tentare l'avventura del Tunnel, `nma ricordati che ti
costerà ben 5 tormenti.`n`n");
output("Accompagna il Vecchietto. Perderai un turno ma la tua Coscienza sarà tranquilla. (La Coscienza non ha alcun `neffetto
sul gioco) Potrebbe offrirti qualcosa di interessante per il tuo nobile gesto!`n`n");
output("Scava nella Miniera. Qui hai una grossa chance. Nella maggior parte dei casi guadagnerai `^Oro`7, `&Gemme`7 o `^Oro`7 e `&Gemme`7. `n
Consumerai un turno o potrai morire e perdere TUTTE le tue `&Gemme`7. A te decidere. I giocatori di livello più elevato
non `ndovrebbero avere la necessità di scavare alla ricerca di `^Oro`7 e `&Gemme`7. Dovrebbero invece, togliersi immediatamente
di torno!`n`n");
}
if ($_GET['op']=="3"){
output("Hai poco `^Oro`7 e HP? `iVisita i Bassifondi`i! Affronta le creature deboli e timide e prendi il loro `^Oro`7. Un ottimo
metodo per raggranellare quell'`&Oro`7 che ti manca per migliorare la tua Armatura. (Si, la T-Shirt fa proprio pena).`n`n");
output("Altre informazioni sui Livelli, come avanzare e alcune info generali`n`n");
output("Combattere il Maestro è il modo per avanzare. Troverai il tuo Maestro al Campo di Allenamento di BlueSpring. `nInterrogalo
e scoprirai a che punto sei. Quando il tuo Maestro ti dice `b`iOh, i tuoi muscoli stanno diventando `npiù grossi dei miei...`i`b
sarai pronto a sfidarlo. Puoi anche continuare con i tuoi affari e lasciare che sia lui `na venirti a cercare. Sarai più che
pronto a quel punto.`n`n");
output("Per un aumento temporaneo dei `\$Punti Attacco`7, bevi una birra alla Locanda. La Birra ti costerà 10 pezzi d'`^Oro`7 `nper livello
e durerà per 10 turni in fase di combattimento. A meno che tu non abbia abbastanza `^oro`7, `nContadino, sarà meglio che tu
conservi i tuoi pezzi d'`^Oro`7 e migliori la tua Armatura o le tue Armi. (Ricordati, `nla T-Shirt fa veramente pena!)`n`n");
output("Puoi trovare le `&Gemme`7 nei posti più curiosi. Guardati attorno.`n`n");
output("Conserva le tue `&gemme`7 per acquistare un animale alle Stalle. Un animale aumenta il tuo `\$attacco`7, la tua difesa `no entrambe,
oppure indebolisce un avversario per un numero variabile di turni ad ogni Nuovo Giorno. `nSarà sempre al tuo fianco ad ogni
Nuovo Giorno per combattere finchè lo terrai. Puoi rifocillare il tuo animale `nalle Stalle per un prezzo equo, quando ha
terminato i suoi turni. Ricordati di averlo sempre efficiente al tuo fianco.`n`n");
output("Dare le `&gemme`7 all'oste Cedrik non è una cosa saggia per i player di livello basso. Salva le tue `&Gemme`7 per comprare `n
un compagno di avventura, potrai sempre rivenderlo per acquistarne uno migliore, poi potrai anche usare le `&Gemme`7 `nda
Cedrik per acquistare VITALITÁ. Ciò aumenterà i tuoi HP di uno, e questo upgrade è permanente! `nTi sei mai chiesto come
facciano gli altri players ad avere così tanti HP? `n`n");
output("Hai terminato i tuoi turni per oggi? Ovviamente puoi tentare la fortuna nei Campi sperando che nessuno ti noti `n
e ti uccida, OPPURE puoi spendere ~10 pezzi d'`^Oro`7 per livello e dormire sonni più tranquilli alla Locanda. `nTra le altre cose,
rimanere nella Locanda ti avvicina maggiormente a Violet! (o Seth se così ti aggrada)`n`n");
output("Puoi vedere la BIO degli altri giocatori andando all'elenco dei player.`n`n");
output("Ci sono 15 livelli per ogni Titolo. Devi passare ogni livello ed uccidere il `@Drago Verde`7 per passare al successivo Titolo. `n
(Smettila con i piagnistei, tutti devono farlo.) perchè 15 livelli? Il `@Drago Verde`7 te lo spiegherà dopo che l'avrai
sconfitto.`n`n");
}
if ($_GET['op']=="4"){
output("Quando sarai di livello 11 come Contadino i tuoi punti `\$Attacco`7/`@Difesa`7 dovrebbero essere 25 o più. `nQuando sarai di livello 15
dovranno essere non meno di 30.`n`n");
output("Cerca di tenere i tuoi punti `\$ATTACCO`7 e `@DIFESA`7 allo stesso livello - un piccolo vantaggio a favore dell'`\$ATTACCO`7 dovrebbe
consentirti di battere la maggior parte delle creature che incontrerai.`n`n");
output("Quando/se raggiungerai il livello 15 e hai meno di 40,000 Punti Esperienza - la tua battaglia con il `n`@Drago Verde`7 sarà
ESTREMAMENTE difficile. Continua ad uccidere altre creature fino a che guadagnerai `nabbastanza Punti Esperienza.
Maggiore sarà l'esperienza che avrai, più facile sarà il compito di uccidere `nil `@Drago Verde`7. Hai conservato le tue `&Gemme`7
per comprarti un animale decente o le hai sprecate in altre cose `nche non ti aiuteranno nell'affrontare il `@Drago Verde`7?`n`n");
output("Ricordati, tu sei un semplice Contadino che ha la speranza di diventare un esperto guerriero. `nNon sei nato guerriero,
dovrai guadagnarti quel titolo con il sudore ed il sacrificio.`n`n");
output("Nel Monastero che trovi nella Foresta, c'è un Druido al quale puoi chiedere informazioni. È molto informato `ne
sono sicuro ti svelerà molti altri segreti del gioco (sempre che tu voglia rovinarti la sorpresa.)`n`n");
output("Sia derubare la banca che attaccare altri player nella locanda (non nei campi) comporta l'accumulo dei `\$Punti Cattiveria`7. `n
Superata una certa soglia si diventa ricercati, e a questo punto se si incontra lo sceriffo o il vicesceriffo, o un altro player `n
durante uno scontro PvP, ci si ritrova al fresco ;-)`n
Ogni giorno viene scontata una certa quantità di `\$Punti Cattiveria`7 (è necessario loggarsi perchè vengano scalati) e se riscende `n
sotto la soglia critica si viene rilasciati di prigione. Inoltre ogni giorno viene scalato un punto cattiveria ... `n
basta rigare diritto per un po' che non si verrà neanche tenuti d'occhio dallo sceriffo ;-)`n`n");
output("Ci sono molte altre cose da scoprire mentre giocherai a `@Legend of the Green Dragon`7. Fortunatamente, `ni nostri consigli
sono quelli che ti permetteranno di sopravvivere più agevolmente e renderanno il gioco più avvincente.`n`n`n`n");
}
if ($_GET['op']=="5"){
page_header("Reincarnazione");
output("Dopo un certo numero di `@Dragon Kill`7 ci viene concessa la possibilità di reincarnarsi nuovamente in un contadino.
Per poterlo fare si deve essere in possesso dell'`^Uovo d'Oro`7. `n
Recandosi da `%Javella`7 si potrà effettuare il processo, che ci costerà in termini di difesa e attacco`n
e che sarà evidenziato dal nostro nome `3c`4o`5l`6o`7r`2a`1t`7o (stile admin). `n
Beh direte voi tutto qui ? Ci perdo solamente qualcosa, dov'è il vantaggio ?`n
Il vantaggio è di poter costruire una casa nelle `@Tenute Reali`7 per proteggerci durante il sonno.`n
Come ben sappiamo però i cavalieri malintenzionati non si fermano davanti a nulla, ma al termine `n
della costruzione della casa avrete 9 chiavi da distribuire ad altrettanti guerrieri, `n
per difendere al meglio la vostra nuova costruzione. Inoltre le tenute sono difese da un vigilante `n
che si parerà tra voi e gli eventuali assalitori. Inoltre le case sono dotate di cassaforte dove potrete depositare`n
anche le vostre preziose `&gemme`7, e visto che tutt'ora esistono alcuni eventi in cui è possibile perderle, `n
è una feature molto interessante. Tenete presente che tutti gli abitanti della casa hanno accesso alla cassaforte,`n
e che avrete comunque la possibilità di togliere le chiavi a chi le avete distribuite in caso di ripensamento.`n`n");
output("Un altro vantaggio della reincarnazione è dato dal fatto che equivale a 3 livelli per il calcolo di accesso`n
agli oggetti. Ma di questo ne parliamo in un'altra parte di queste `^miniFAQ`7.`n");
}
if ($_GET['op']=="6"){
page_header("Abitazioni");
output("`b<span style='color:#FF0000'>Elenco delle Caratteristiche:`b</span>",true);
output("`n`n`@È possibile costruire la casa un po' per volta:`n
`7si consuma un turno combattimento per ogni step di costruzione. Cercate di pagare `n
quanto più oro e gemme possibile per avanzare velocemente nella costruzione della casa. `n
È possibile accedere alla costruzione delle case `b`\$SOLAMENTE`7`b se si è effettuata `n
la reincarnazione. E' possibile comunque, per i non reincarnati, acquistare una casa già costruita. `n
Il prezzo di vendita sarà stabilito dal venditore.`n
`@Dormi nella tua casa (Logout):`n`7
La tua nuova casa - non appena terminata - offre un luogo sicuro ed economico dove dormire.`n
Una guardia di quartiere protegge te e la tua casa.`n
`@Dai le chiavi ad altri player:`7`n
Puoi consegnare fino a 9 chiavi ad altrettanti giocatori per offire loro un posto dove dormire.`n
Ma pensa bene a chi darai le chiavi ! Riprendertela potrebbe essere costoso ...`n
`@Il tuo luogo esclusivo dove chiaccherare:`n`7
Ogni casa ha la sua area privata dove poter chattare che non può essere visionata da chi non ha le chiavi. `n
Si posso formare dei clan ed è possibile complottare in tutta segretezza.`n
`@Cassaforte Privata:`n`7
Condividi con gli altri abitanti le tue risorse: Ogni abitazione ha una cassaforte dove custodire oro e gemme. `n
Tutti gli occupanti della casa condividono questa cassaforte. Ogni trasferimento viene registrato nell'area di chat.`n
`@Dai un nome alla tua casa e descrivila:`7`n
Solo il proprietario può farlo.`n
`@Vendi la tua casa:`n`7
Non vuoi più mantenere la tua casa perchè ti sei trasferito da amici? Puoi semplicemente venderla direttamente `n
ad un altro giocatore o all'agenzia di intermediazione. Ma ricorda che il successivo proprietario potrà leggere `n
quello che è stato scritto nell'area di chat! Tutti gli occupanti della casa dovranno abbandonarla.`n
`@Compra una casa:`n`7
Se hai abbastanza denaro puoi comprare una casa messa in vendita da un altro giocatore. `n
Informati sul numero di chiavi disponibili prima di spendere il tuo sudato denaro. `n
Puoi acquistare una casa in vendita, abbandonata o ancora in costruzione.`n
`@Effrazione:`n`7
Una delle principali caratteristiche. Puoi penetrare all'interno di una casa. Ma prima di avere accesso ad una parte `n
della cassaforte devi prima battere il guardiano ... ed il campione della casa. Gli HP del guardiano sono la differenza `n
tra il tuo prossimo avversario ed i tuoi. Il suo attacco e la sua difesa sono uguali ai tuoi. Questo per bilanciare ogni `n
differenza tra i vari player, perchè non c'è nessuna restrizione di livello per il PvP! Gli occupanti hanno un grande vantaggio `n
dato dal fatto di combattere in casa (attacco speciale) e quindi è possibile per un Esploratore di livello 1 battere `n
un Imperatore di livello 15 con un grande guadagno di esperienza. Non puoi MAI sapere in anticipo chi dorme in quale `n
casa prima di trovarti di fronte agli occupanti. Le case vuote sono custodite da un guardiano più potente.`n
Il numero di effrazioni giornaliere sono controllate dal settaggio del PvP. Se il PvP è disabilitato, anche le effrazioni lo saranno.`n
`@I personaggi eliminati sono totalmente controllati dagli script: `n`7
Le case in possesso di giocatori cancellati diventano \"abbandonate\" e possono essere acquistate da uno degli altri `n
occupanti o da un altro player. Le case ancora in costruzione possono anche essere acquistate e la costruzione può `n
essere terminata dal nuovo proprietario. Le chiavi in possesso di giocatori cancellati sono perse. (Il proprietario `n
ha comunque sempre accesso alla sua casa, non necessita di una chiave.)`n
Un player può possedere solo una casa alla volta, ma può avere quante chiavi vuole (che danno accesso ad altre abitazioni).`n");
}
if ($_GET['op']=="7"){
	
output("`c<font size='+2'>`3Le Carriere</font>`c`n`n",true);
output("`7E' possibile intraprendere una carriera professionale anzichè quella religiosa della quale troverete informazioni nel paragrafo `n
relativo alle religioni. Ricordatevi però che la scelta è molto importante e cambiare idea in corsa può diventare molto costoso.`n
Quindi pensate molto alla via che state per intraprendere e non esitate a chiedere consiglio ai giocatori più esperti prima di `n
prendere una decisione. `n`n ");
		
output("`c<font size='+2'>`#Il Fabbro</font>`c`n`n",true);
output("`7Dopo aver fatto visita ad `#Oberon il Fabbro`7 con una piccola quota associativa farete parte della `#Corporazione dei Fabbri`7 diventando uno dei suoi `2Garzoni`7. `n
Il `2Garzone`7 dovrà sottoporsi a lunghe esercitazioni al mantice, consumando turni ma acquisendo punti carriera. Si migliora nell'arte della `n
manipolazione dei metalli, ed una volta che `#Oberon `7vi giudicherà pronti provvederà a promuovervi nominandovi `2Apprendisti`7. `n
Da `2Apprendisti`7 vi allenerete all'incudine, e avrete la possibilità di forgiare piccoli oggetti che venduti vi faranno guadagnare qualcosa. `n
La creazione di oggetti è la parte più interessante della carriera di un `2fabbro `7. Per procedere alla forgiatura di un oggetto si deve possedere una `n
`&Ricetta`7 e gli `&Ingredienti`7 necessari che troverete indicati nella ricetta stessa. Una volta che vi sarete procurati tutti i materiali necessari `n
potrete usufruire della forgia di Oberon e modellare l'oggetto consumando una parte dei punti carriera che avrete in precedenza accumulato.`n
Quando `#Oberon `7 vi reputerà pronti sarete nominati `2Fabbri `7a tutti gli effetti: il `2Fabbro `7 col maggior numero di punti carriera verrà nominato 
`@Mastro Fabbro`7 .`n`n");

output("`c<font size='+2'>`6Il Mago</font>`c`n`n",true);
output("`7Dopo aver fatto visita ad `^Ithine il Mago`7 con una piccola quota associativa farete parte della `^Corporazione dei Maghi`7 diventando uno dei suoi `6Iniziati`7. Ricordatevi che la scelta `n
della carriera è definitiva, quindi fate attentamente la vostra scelta.`n
L' `6Iniziato`7 dovrà sottoporsi a lunghe esercitazioni nel controllo del mana, consumando turni ma acquisendo punti carriera. Si migliora nell'arte della `n
manipolazione dell'energia, ed una volta che `^Ithine `7vi giudicherà pronti provvederà a promuovervi nominandovi `6Stregoni`7. `n
Da `6Stregonii`7 vi allenerete con esercizi sempre più complessi, e avrete la possibilità di preparare piccoli incantesimi che potranno essere utili contro i vostri nemici. `n
La preparazione di incantesimi è la parte più interessante della carriera di un `6mago`7. Per procedere alla preparazione di un incantesimo si deve possedere una `n
`&Pergamena`7 e gli `&Ingredienti`7 necessari che troverete indicati nella pergamena stessa. Una volta che vi sarete procurati tutti i materiali necessari `n
potrete creare l'incantesimo consumando una parte dei punti carriera che avrete in precedenza accumulato.`n
Quando `^Ithine `7 vi reputerà pronti sarete nominati `6Maghi `7a tutti gli effetti: il `6Mago `7 col maggior numero di punti carriera verrà nominato 
`VArcimago`7 .`n`n");

}
if ($_GET['op']=="8"){
	
output("`c<font size='+1'>`3Le Religioni</font>`c`n",true);
output("`7Inizialmente un personaggio è `5Agnostico`7 in quanto non affiliato ad alcuna setta religiosa.`n");
output("`7Dopodichè può restare `5Agnostico`7, o scegliere tra `6la Chiesa di Sgrios`7,`\$ la Grotta di Karnak`7 o `@la Gilda del Drago`7.`n");

output("`n`c<font size='+1'>`6La Chiesa di Sgrios</font>`c`n",true);
output("`7Una volta entrati nella chiesa potrete diventare `3Seguace`7 o `&Fedele`7.`n
`&Fedele`7 lo può diventare chiunque, anche un Fabbro o un Mago, che acquisirà così la possibilità di partecipare alle messe, argomento che tratteremo più avanti.`n
Il `3Seguace`7 invece è il primo gradino della scala gerarchica di chi intraprende la carriera di religioso della `6Chiesa di Sgrios`7.`n
Da `3Seguace `7si può pregare ottenendo in cambio punti fede e supplicare spendendo i punti fede guadagnati ottenendo favori, attacchi speciali, oro o gemme. `n
Si possono anche offrire come dono oro o gemme alla propria divinità in cambio di punti fede. Una volta raggiunto un livello accettabile di punti fede`n
diventerai `3Accolito`7, da `3Accolito`7 potrai fare le stesse cose del `3Seguace`7, inoltre avrai la possibilità di tenere delle cerimonie, sempre per `n
accumulare fede, oppure di utilizzare una Supplica Superiore spendendo più fede rispetto alla Supplica Normale ma ottenendo in cambio favori maggiori.`n
Il passo successivo ad `3Accolito`7 è quello di `3Chierico`7. Il `3Chierico`7 ha la possibilità di tenere una processione per accumulare fede ed è in grado`n
di benedire oggetti, soprattutto quelli forgiati dai fabbri. In seguito diventerete `3Sacerdote `7e effettuare Benedizioni Superiori che daranno all'oggetto `n
benefici maggiori. Il livello successivo è quello di `@Sommo Chierico`7 il quale ha il potere di poter di celebrare la messa. La messa può essere tenuta ogni `n
n giorni, in base al numero di `@Sommi Chierici`7 esistenti e tutti i `&Fedeli`7 ed i `6Religiosi`7 potranno partecipare alla messa nella stanza del `#Gran Sacerdote`7`n
che è il `@Sommo Chierico`7con più punti fede e viene considerato il Capo Setta. Più giocatori saranno presenti, maggiore sarà la ricompensa che i partecipanti al rito riceveranno.`n");

output("`n`c<font size='+1'>`\$La Grotta di Karnak</font>`c`n",true);
output("`7Una volta entrati nella grotta potrete diventare `4Invasato`7 o `&Fedele`7.`n
`&Fedele`7 lo può diventare chiunque, anche un Fabbro o un Mago, che acquisirà così la possibilità di partecipare alle messe, argomento che tratteremo più avanti.`n
L'`4Invasato`7 invece è il primo gradino della scala gerarchica di chi intraprende la carriera di religioso della `\$Grotta di Karnak`7.`n
Da `4Invasato`7si può pregare ottenendo in cambio punti fede e supplicare spendendo i punti fede guadagnati ottenendo favori, attacchi speciali, oro o gemme. `n
Si possono anche offrire come dono oro o gemme alla propria divinità in cambio di punti fede. Una volta raggiunto un livello accettabile di punti fede`n
diventerai `4Fanatico`7, da `4Fanatico`7 potrai fare le stesse cose dell'`4Invasato`7, inoltre avrai la possibilità di tenere delle cerimonie, sempre per `n
accumulare fede, oppure di utilizzare una Supplica Superiore spendendo più fede rispetto alla Supplica Normale ma ottenendo in cambio favori maggiori.`n
Il passo successivo a `4Fanatico`7 è quello di `4Posseduto`7. Il `4Posseduto`7 ha la possibilità tenere una processione per accumulare fede ed è in grado `n
di benedire oggetti, soprattutto quelli forgiati dai fabbri. In seguito diventerete `%Maestro delle Tenebre`7 e effettuare Benedizioni Superiori che daranno `n
all'oggetto benefici maggiori. Il livello successivo è quello di `(Portatore di Morte`7 il quale ha il potere di celebrare la messa. La messa può essere tenuta `n
ogni n giorni, in base al numero di `(Portatori di Morte`7 esistenti e tutti i `&Fedeli`7 ed i `\$Religiosi`7 potranno partecipare alla messa nella stanza del `\$Falciatore di Anime`7 `n
che è il `(Portatore di Morte`7 con più punti fede e viene considerato il Capo Setta. Più giocatori saranno presenti, maggiore sarà la ricompensa che i partecipanti al rito riceveranno.`n");

output("`n`c<font size='+1'>`@La Gilda del Drago</font>`c`n",true);
output("`7Una volta entrati nella gilda potrete diventare `8Stalliere dei Draghi`7 o `&Fedele`7.`n
`&Fedele`7 lo può diventare chiunque, anche un Fabbro o un Mago, che acquisirà così la possibilità di partecipare alle messe, argomento che tratteremo più avanti.`n
Lo `8Stalliere dei Draghi`7 invece è il primo gradino della scala gerarchica di chi intraprende la carriera di religioso della `@Gilda del Drago`7. `n
Da `8Stalliere dei Draghi`7 si può pregare ottenendo in cambio punti fede e supplicare spendendo i punti fede guadagnati ottenendo favori, attacchi speciali, oro o gemme. `n
Si possono anche offrire come dono oro o gemme alla propria divinità in cambio di punti fede. Una volta raggiunto un livello accettabile di punti fede`n
diventerai `8Scudiero dei Draghi`7, da `8Scudiero dei Draghi`7 potrai fare le stesse cose dello`8Stalliere dei Draghi`7, inoltre avrai la possibilità di tenere delle cerimonie, sempre per `n
per accumulare fede, oppure di utilizzare una Supplica Superiore spendendo più fede rispetto alla Supplica Normale ma ottenendo in cambio favori maggiori.`n
Il passo successivo a `8Scudiero dei Draghi`7 è quello di `8Cavaliere dei Draghi`7. Il `8Cavaliere dei Draghi`7 ha la possibilità tenere una processione per accumulare fede `n
ed è in grado di benedire oggetti, soprattutto quelli forgiati dai fabbri. In seguito diventerete `(Mastro dei Draghi`7 e effettuare Benedizioni Superiori che daranno `n
all'oggetto benefici maggiori. Il livello successivo è quello di `(Cancelliere dei Draghi`7 il quale ha il potere di celebrare la messa. La messa può essere tenuta ogni `n
n giorni, in base al numero di `(Cancellieri dei Draghi`7 esistenti e tutti i `&Fedeli`7 ed i `@Religiosi`7 potranno partecipare alla messa nella stanza del `(Dominatore dei Draghi`7 `n
che è il `(Cancelliere dei Draghi`7 con più punti fede e viene considerato il Capo Setta. Più giocatori saranno presenti, maggiore sarà la ricompensa che i partecipanti al rito riceveranno.`n");

}
if ($_GET['op']=="9"){
output("`c<font size='+2'>`^Gli Oggetti di Brax</font>`c`n`n",true);
output("`#Brax il Mercante`7, il cui emporio è situato all'interno di `@Castel Excalibur`7, ha alcuni`n
oggetti magici che mette a disposizione dei guerrieri del villaggio dietro pagamento di alcune gemme. `n
Ogni oggetto ha delle caratteristiche uniche che ne determinano il prezzo. Queste proprietà magiche possono `n
aumentare l'attacco o la difesa di chi lo indossa, oppure aumentare gli HP, come anche fruttare un certo `n
quantitativo di oro o una gemma ogni nuovo giorno. Inoltre le proprietà di ogni oggetto ne determinano il livello, `n
per evitare che oggetti troppo potenti finiscano tra le mani di utenti inesperti. `n
Gli oggetti magici possono anche essere trovati in un evento particolare, ma lascio a voi scoprire quale.`n
Per sapere di quanti DK avete bisogno per avere accesso ad ogni livello potete consultare la tabella sottostante.`n
Potete possedere un massimo di due oggetti, uno equipaggiato che varierà le vostre stat, ed uno nello zaino.
Tramite il link `b`@Gestione Oggetto`b`7 presente nel villaggio potrete scambiare i due oggetti in vostro possesso.`n`n");
output("<table cellspacing=2 cellpadding=2 align='center'>",true);
output("<tr bgcolor='#FF0000'><td align='center'>`&`bReincarnazioni`b</td><td align='center'>`&`bDK`b</td><td align='center'>`b`&Livello Oggetto`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`&`b0`b</td><td align='center'>`&`b0 ~ 9`b</td><td align='center'>`&`b1`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`&`b0`b</td><td align='center'>`&`b10 ~ 19`b</td><td align='center'>`&`b2`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`&`b0`b</td><td align='center'>`&`b20 ~ xx`b</td><td align='center'>`&`b3`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`3`b1`b</td><td align='center'>`3`b0 ~ 9`b</td><td align='center'>`3`b4`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`3`b1`b</td><td align='center'>`3`b10 ~ 19`b</td><td align='center'>`3`b5`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`3`b1`b</td><td align='center'>`3`b20 ~ xx`b</td><td align='center'>`3`b6`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`2`b2`b</td><td align='center'>`2`b0 ~ 9`b</td><td align='center'>`2`b7`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`2`b2`b</td><td align='center'>`2`b10 ~ 19`b</td><td align='center'>`2`b8`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`2`b2`b</td><td align='center'>`2`b20 ~ xx`b</td><td align='center'>`2`b9`b</td></tr>",true);
output("</table>",true);
}
if ($_GET['op']=="10"){
output("`c<font size='+2'>`&Le Barre `^Odore `&/ `@Vescica `&/ `\$Fame</font>`c`n`n",true);
output("`7Nella sezione `b`#Info Vitali`b`7 noterete 3 barre colorate che salgono durante le normali attività che svolgete
nel game. `6`n`nCombattere le creature della foresta, cadere in una pozzanghera, e altre attività vi faranno sporcare. `n`n`2Bere una
birra da Cedrik o farsi un `igoccetto`i alla vigna del Monastero ingrosserà la vostra vescica.
`n`n`\$I combattimenti nella foresta consumano energie e faranno quindi aumentare la vostra fame. `n`n`7Tenere sotto controllo tutte
queste variabili è semplice ma importante. `n`n`6Essere sporchi avrà come primo sintomo il fatto che emetterete un odore decisamente
sgradevole, fino a farvi puzzare in maniera insopportabile e farvi guadagnare il titolo di `^PigPen`6. Questo titolo
persisterà fino a quando non ucciderete il `@Drago Verde`6. A questo punto riacquisterete il titolo che vi spetterà in base
ai vostri DK. Emettere un odore sgradevole avrà inoltre come conseguenza la perdita di punti fascino ad ogni Nuovo Giorno, a meno che prima
di coricarvi per riposare le vostre stanche membra non facciate una doccia purificatrice. `n`n`2Avere la vescica troppo gonfia
prima di andare a dormire potrebbe causare delle `i`@perdite notturne`i`2 con conseguente perdita di punti fascino. Vi consiglio
quindi di fare un salto alla ritirata della foresta o alla Grotta dello Gnomo (situato presso Castel Excalibur per svuotarla.`n`n
`\$Non nutrirsi a sufficienza e far salire troppo l'indice della fame, potrebbe farvi perdere energia vitale, non dimeticate di
rifocillarvi alla Taverna del Drago, e portatevi sempre appresso delle scorte di viveri, non si sa mai dove potreste capitare !");
}
if ($_GET['op']=="11"){
output("`c<font size='+2'>`^Le Terre dei Draghi</font>`c`n`n",true);
output("`#1) Requisiti : `n
`7Per poter accedere alla terra dei draghi bisogna aver raggiunto i 19 DK oppure bisogna essere reincarnati.
Per poter cavalcare un drago la propria caratteristica `&`iCavalcare Draghi`7`i deve essere superiore al carattere del drago.`n
`n
`#2) Draghi :`n
`7Tabella creazione draghi e relativi parametri.`n`n");
output("<table cellspacing=2 cellpadding=2 align='center'>",true);
output("<tr bgcolor='#FF0000'><td align='center'>`&`bTipo drago`b</td><td align='center'>`&`bAttacco`b</td><td align='center'>`b`&Difesa`b</td><td align='center'>`b`&Soffio`b</td><td align='center'>`b`&Carattere`b</td><td align='center'>`b`&Vita`b</td><td align='center'>`b`&Bonus territorio`b</td><td align='center'>`b`&Crescita`b</td><td align='center'>`b`&Livello Drago`b</td></tr>",true);

output("<tr class='trlight'><td align='center'>`9`bNero`b</td><td align='center'>`&`b10 ~ 15`b</td><td align='center'>`&`b5 ~ 10`b</td><td align='center'>`&`b10 ~ 15`b</td><td align='center'>`&`b5 ~ 15`b</td><td align='center'>`&`b20 ~ 30`b</td><td align='center'>`&`b1 ~ 5`b</td><td align='center'>`&`b3 ~ 5`b</td><td align='center'>`&`b1`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`\$`bRosso`b</td><td align='center'>`&`b5 ~ 10`b</td><td align='center'>`&`b5 ~ 10`b</td><td align='center'>`&`b15 ~ 20`b</td><td align='center'>`&`b5 ~ 15`b</td><td align='center'>`&`b20 ~ 30`b</td><td align='center'>`&`b1 ~ 5`b</td><td align='center'>`&`b3 ~ 5`b</td><td align='center'>`&`b1`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`!`bBlu`b</td><td align='center'>`&`b5 ~ 10`b</td><td align='center'>`&`b10 ~ 15`b</td><td align='center'>`&`b10 ~ 15`b</td><td align='center'>`&`b5 ~ 15`b</td><td align='center'>`&`b20 ~ 30`b</td><td align='center'>`&`b1 ~ 5`b</td><td align='center'>`&`b3 ~ 5`b</td><td align='center'>`&`b1`b</td></tr>",true);

output("<tr class='trlight'><td align='center'>`@`bVerde`b</td><td align='center'>`3`b5 ~ 10`b</td><td align='center'>`3`b5 ~ 10`b</td><td align='center'>`3`b10 ~ 15`b</td><td align='center'>`3`b5 ~ 15`b</td><td align='center'>`3`b30 ~ 40`b</td><td align='center'>`3`b1 ~ 5`b</td><td align='center'>`3`b3 ~ 5`b</td><td align='center'>`3`b2`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`&`bBianco`b</td><td align='center'>`3`b5 ~ 10`b</td><td align='center'>`3`b5 ~ 10`b</td><td align='center'>`3`b10 ~ 15`b</td><td align='center'>`3`b10 ~ 20`b</td><td align='center'>`3`b25 ~ 35`b</td><td align='center'>`3`b1 ~ 5`b</td><td align='center'>`3`b3 ~ 5`b</td><td align='center'>`3`b2`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`0`bZombie`b</td><td align='center'>`3`b15 ~ 20`b</td><td align='center'>`3`b5 ~ 10`b</td><td align='center'>`3`b10 ~ 15`b</td><td align='center'>`3`b5 ~ 15`b</td><td align='center'>`3`b20 ~ 30`b</td><td align='center'>`3`b1 ~ 5`b</td><td align='center'>`3`b3 ~ 5`b</td><td align='center'>`3`b2`b</td></tr>",true);

output("<tr class='trlight'><td align='center'>`8`bScheletro`b</td><td align='center'>`2`b10 ~ 15`b</td><td align='center'>`2`b5 ~ 10`b</td><td align='center'>`2`b15 ~ 20`b</td><td align='center'>`2`b10 ~ 20`b</td><td align='center'>`2`b20 ~ 30`b</td><td align='center'>`2`b1 ~ 5`b</td><td align='center'>`2`b3 ~ 5`b</td><td align='center'>`2`b3`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`(`bBronzo`b</td><td align='center'>`2`b5 ~ 10`b</td><td align='center'>`2`b15 ~ 20`b</td><td align='center'>`2`b10 ~ 15`b</td><td align='center'>`2`b10 ~ 20`b</td><td align='center'>`2`b20 ~ 30`b</td><td align='center'>`2`b1 ~ 5`b</td><td align='center'>`2`b3 ~ 5`b</td><td align='center'>`2`b3`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`7`bArgento`b</td><td align='center'>`2`b5 ~ 10`b</td><td align='center'>`2`b10 ~ 15`b</td><td align='center'>`2`b10 ~ 15`b</td><td align='center'>`2`b10 ~ 20`b</td><td align='center'>`2`b25 ~ 35`b</td><td align='center'>`2`b1 ~ 5`b</td><td align='center'>`2`b3 ~ 5`b</td><td align='center'>`2`b3`b</td></tr>",true);

output("<tr class='trlight'><td align='center'>`6`bOro`b</td><td align='center'>`$`b10 ~ 15`b</td><td align='center'>`$`b10 ~ 15`b</td><td align='center'>`$`b10 ~ 15`b</td><td align='center'>`$`b5 ~ 15`b</td><td align='center'>`$`b25 ~ 35`b</td><td align='center'>`$`b1 ~ 5`b</td><td align='center'>`$`b4 ~ 6`b</td><td align='center'>`$`b4`b</td></tr>",true);
output("</table>",true);
output("`n`7Tabella Tipo di soffio`7`n");
output("<table cellspacing=2 cellpadding=2 align='center'>",true);
output("<tr bgcolor='#FF0000'><td align='center'>`&`bTipo soffio`b</td><td align='center'>`&`bAttacco`b</td><td align='center'>`b`&Soffio`b</td><td align='center'>`b`&Carattere`b</td></tr>",true);

output("<tr class='trlight'><td align='center'>`\$`bFuoco`b</td><td align='center'>`\$`b`b</td><td align='center'>`\$`b15`b</td><td align='center'>`\$`b`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`&`bGelo`b</td><td align='center'>`&`b10`b</td><td align='center'>`&`b5`b</td><td align='center'>`&`b`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`2`bAcido`b</td><td align='center'>`2`b`b</td><td align='center'>`2`b`b</td><td align='center'>`2`b15`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`8`bMorte`b</td><td align='center'>`8`b15`b</td><td align='center'>`8`b`b</td><td align='center'>`8`b`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`#`bFulmine`b</td><td align='center'>`#`b5`b</td><td align='center'>`#`b5`b</td><td align='center'>`#`b5`b</td></tr>",true);
output("</table>",true);
output("`n`7In base alle tabelle sopra riportate il drago viene creato con i parametri casuali della tabella superiore e poi sommato al bonus portato al drago dalla tabella tipo di soffio.`n`n
Un parametro che entra in gioco a questo punto è l'età che può essere : `@Cucciolo `#Giovane `(Adulto `!Anziano `\$Antico `7`n`n
Se il drago in fase di creazione è supponiamo Adulto il suo valore di attacco per un drago verde sarà generato da 5-10 + 5-10 + 5-10 quindi verranno generati 3 numeri casuali compresi tra 5 e 15 e poi sommati !`n`n
Durante il gioco può capitare che il drago invecchi, a quel punto entra in gioco l'aspetto del vostro drago!
`n`n`6 Aspetto del drago `n`7 L'aspetto del vostro drago ha un ruolo molto importante, considerate che un drago acquistato da cucciolo ed invecchiato naturalmente al livello di giovane ha buone possibilità di essere molto più forte di un drago acquistato direttamente con età giovane, se l'aspetto del drago è buono.`n
L'invecchiamento del drago provoca un miglioramento di tutto le caratteristiche proporzionale all'aspetto del drago e funziona in questo modo.`n`n");
output("<table cellspacing=2 cellpadding=2 align='center'>",true);
output("<tr bgcolor='#FF0000'><td align='center'>`&`bAspetto`b</td><td align='center'>`&`bBonus`b</td></tr>",true);

output("<tr class='trlight'><td align='center'>`\$`bPessimo`b</td><td align='center'>`\$`bX 1`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`8`bBrutto`b</td><td align='center'>`8`bX 2`b</td></td></tr>",true);
output("<tr class='trlight'><td align='center'>`(`bNormale`b</td><td align='center'>`(`bX 3`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`6`bBuono`b</td><td align='center'>`6`bX 4`b</td></tr>",true);
output("<tr class='trlight'><td align='center'>`^`bOttimo`b</td><td align='center'>`^`bX 5`b</td></tr>",true);
output("</table>",true);
output("`n`7Esempio`n
Un drago invecchia da cucciolo a giovane cosa accade ? Tutti i parametri del drago aumentano di Bonus relativo alla tabella aspetto * bonus crescita relativo alla prima tabella drago, il parametro crescita è nascosto, e non potrete conoscerlo!`n
L'ultima caratteristica che vediamo è il territorio preferito di combattimento, che può essere `(Terra`7 o `#Volo`7. Nel caso il combattimento avvenga nel terreno preferito del vostro drago usufruirete del bonus di combattimento.`n
`n
`#3) Combattimento`n
`n`7Il combattimento può avvenire in 4 modi differenti:`n
`n`8Drago contro Drago vagante (nella fase di esplorazione delle `^Terre dei Draghi`8)`n
`7In questo caso il confronto avviene in questo modo`n
Attacco Player Casuale = random (1 ~ 20)`n
Difesa Player Casuale = random (1 ~ 20)`n
Danno Player Casuale = random (danno soffio/2 ~ danno soffio)`n
Attacco Drago = cavalcare drago/2 + carattere/2 + Attacco + Attacco Player Casuale`n
Difesa Drago = cavalcare drago/2 + carattere/2 + Difesa + Difesa Player Casuale`n
Se il combattimento avviene nel terreno preferito del vostro drago attacco e difesa verranno maggiorate del bonus territorio`n
Attacco Casuale CPU = random (1 ~ 20)`n
Difesa Casuale CPU = random (1 ~ 20)`n
Danno Casuale CPU = random (danno soffio/2 ~ danno soffio)`n
Attacco Drago CPU = Carattere + Attacco + Attacco Casuale CPU`n
Difesa Drago CPU = Carattere + Difesa + Difesa Casuale CPU`n
Applicato il bonus terreno si calcolano i danno effettuati`n
Danni Subiti Player = Attacco Drago - Difesa Drago CPU + Danno Player Casuale`n
Danni Subiti CPU = Attacco Drago CPU - Difesa Drago + Danno Casuale CPU;`n
`n`4Drago contro Drago per il controllo di una terra `n
`7In questo caso il calcolo è uguale al precedente con la variante che il calcolo nel caso CPU utilizza la stessa formula del player.`n
`n`4Drago contro Drago usando il soffio (a distanza) `n
`7E' la forma più vantaggiosa per attaccare ma consuma 1 turno PvP `n
Attacco Casuale = random (1 ~ 20)`n
Difesa Casuale = random (1 ~ 20)`n
Attacco = Attacco*2 + Carattere/2 + Cavalcare drago/2 + Attacco Casuale`n
Difesa = Difesa + Carattere/2 + Cavalcare drago/2 + Difesa Casuale`n
Danno Casuale = random (danno soffio/2 ~ danno soffio)`n
Danno = Attacco - Difesa + Danno Casuale`n
`n`4Drago contro difese territorio leggendario`n
`7Attacco Player Casuale = random (1 ~ 20)`n
Difesa Player Casuale = random (1 ~ 20)`n
Danno Attacco Casuale = random (danno soffio/2 ~ danno soffio)`n
Attacco Drago = Cavalcare drago/2 + Carattere/2 + Attacco + Attacco Player Casuale`n
Difesa Drago = Cavalcare drago/2 + Carattere/2 + Difesa + Difesa Player Casuale`n
Addizioniamo i bonus delle terreno:
Attacco Casuale CPU = random (1 ~ 20)`n
Difesa Casuale CPU = random (1 ~ 20)`n
Attacco Terra Casuale = Attacco + Attacco Casuale CPU`n
Difesa Terra CPU = Difesa + Difesa Casuale CPU`n
Danni Subiti Player = Attacco Drago - Difesa Drago CPU + Danno Attacco Casuale`n
Danni Subiti CPU = Attacco Drago CPU - Difesa Drago`n`n
`#5) Territori speciali`n
`7I territori speciali sono dei territori che possono essere scoperti durante le esplorazioni della terra dei draghi.`n
Ogni territorio può essere controllato da un giocatore che mantenendolo sotto il suo controllo ne acquisisce i benefici.`n
Per ogni ora intera che un territorio viene controllato si guadagnano un certo numero di punti carriera.`n`n
`#6) Città dei Draghi`n
`7La citta dei draghi è un luogo leggendario che dovrete scoprire esplorando il mondo di `@Legend of the Green Dragon`n
");
}
page_footer();
?>