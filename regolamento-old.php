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
page_header("REGOLAMENTO");
//if ($_GET['op']==""){
    output("<big>`n`b`c`&REGOLAMENTO DI LEGEND OF THE GREEN DRAGON`0`c`b</big>`n`n`n",true);
    output("<big>`b`c`^1) SVILUPPO GIOCO`0`c`b</big>`n`n",true);
    output("`#Il gioco è perennemente in fase Beta, per cui può subire modifiche in qualsiasi momento e
    senza nessun avviso (per quanto in genere i MoTD avvisano di eventuali modifiche al gioco).
    `n`nLe modifiche decise dallo staff possono essere discusse SOLAMENTE tramite petizione
    (privatamente) o nel forum di gioco. E' VIETATO discuterne nelle stanze di gioco.
    `n`nEventuali danni ad un personaggio dovuti a problemi di gioco o a bug non saranno risarciti.
    `n`nE' richiesto ai giocatori di segnalare eventuali bug trovati, ed è TASSATIVAMENTE VIETATO
    sfruttare eventuali bug per avvantaggiarsi in gioco.
    `n`nNelle segnalazioni cercate di essere il più descrittivi possibile e di scrivere i dettagli che
    notate. I dettagli spesso fanno la differenza e sono estremamente importanti per risolvere i problemi di gioco.`n`n`n`0");

    output("<big>`b`c`^2) UTILIZZO DI PROGRAMMI ESTERNI`0`c`b</big>`n`n",true);
    output("`#E' proibito l'utilizzo di qualsiasi programma in grado di generare script automatici o
    semiautomatici in grado di generare comandi di gioco o di interagire con il database del gioco.`n`n`n`0");

    output("<big>`b`c`^3) NOMI DEI PERSONAGGI`0`c`b</big>`n`n",true);
    output("`#L'alias (o nick) che identifica il player:`n`n
    - `bDEVE`b essere attinente al mondo Fantasy di LoGD`n
    - `bNON PUÒ`b essere scritto tutto in maiuscolo`n
    - `bNON PUÒ`b essere scritto con alternanza di caratteri maiuscoli e minuscoli (es. ExCalIbUr)`n
    - `bNON PUÒ`b contenere titoli nobiliari (Sir, Re, etc etc)`n`n");
    output("Se la vostra fantasia non vi aiuta nella creazione di un nick interessante, potete sempre fare
    un salto a <a href=\"http://www.wizards.com/dnd/article5.asp?x=dnd/dx20010202b,0\" target=\"_blank\">
            <b><big><font color='#DDFFBB'>QUESTO SITO</font></big></b></a> per farvi dare un aiuto.`n`n",true);
    output("Ogni personaggio il cui nick rientra nelle categorie di cui sopra verrà cancellato senza preavviso.`n
    Lo Staff di riserva il diritto di cancellare ogni personaggio con nick che verranno ritenuti non adatti.`n`n`n`0");

    output("<big>`b`c`^4) MULTIACCOUNT`0`c`b</big>`n`n",true);
    output("`#E' generalmente permesso creare più personaggi.
    `n`nTuttavia tali personaggi riconducibili ad una stessa persona NON POSSONO AVERE ALCUNA INTERAZIONE,
    NE DIRETTA NE INDIRETTA, TRA LORO. Si intende con interazione il passagio di soldi, gemme, oggetti o
    materiali da un personaggio ad un altro, anche quando tale passaggio avvenga coinvolgendo altri
    personaggi. Non fa alcuna differenza se il passaggio favorisce il personaggio più sviluppato o quello
    meno sviluppato. Vengono considerati interazione anche gli attacchi multipli alle Terre dei Draghi o alla
    tenuta di un avversario, quando questi attacchi avvengono con personaggi diversi ma controllati da un solo
    giocatore.`n`nE' VIETATO creare personaggi appositamente con lo scopo di danneggiare altri giocatori, in
    qualsiasi modo; è inoltre vietato l'utilizzo di nomi volgari o che possono essere confusi con il
    nome di altri personaggi.`n`n`n`0");

    output("<big>`b`c`^5) CONDIVISIONE ACCOUNT`0`c`b</big>`n`n",true);
    output("`#E' generalmente permesso prestare i propri personaggi a terzi, per non perdere sessioni
    di gioco o per altri motivi. Tuttavia, il proprietario dell'account ne rimane responsabile, e
    subirà eventuali punizioni dovute ad un utilizzo inappropriato del personaggio. Ciò non esclude
    provvedimenti anche verso l'affidatario del personaggio.
    `n`nLo staff non prenderà in considerazione richieste di risarcimento di oro o gemme dovute al
    danneggiamento di un personaggio da parte di altre persone, o di riduzione di eventuali
    punizioni in gioco. Le uniche richieste a cui avrete risposta sono quelle relative al cambio di
    password. Prima di passare i dati di accesso a qualcuno, accertatevi bene della persona a cui li
    state passando.
    `n`nL'interazione tra personaggi propri e personaggi presi in affidamento può essere sanzionata.`n`n`n`0");

    output("<big>`b`c`^6) STANZE DI GIOCO E MESSAGGI PRIVATI`0`c`b</big>`n`n",true);
    output("`#In molte stanze di gioco è possibile conversare o interagire con gli altri giocatori.
    Alcune stanze sono pubbliche, altre invece sono private e riservate solo ad alcuni personaggi.
    Alcune di queste possono avere regole particolari, ma in tutte è VIETATO, in qualsiasi caso e
    per qualsiasi motivo, l'utilizzo di linguaggio volgare. Nonostante esista un sistema automatico
    di censura, se questo fallisce verranno presi provvedimenti.
    `n`nE' vietato tentare l'aggiramento del sistema di censura storpiando le parole.
    `n`nMinacce o insulti in \"gioco di ruolo\" tra due personaggi sono accettati, a condizione che non
    siano violate altre regole. E' però vietato e sarà sanzionato pesantemente qualsiasi insulto,
    minaccia o riferimento a fatti personali di un giocatore all'infuori del gioco.
    `n`nI messaggi privati non vengono controllati tranne che su segnalazione da parte di un utente. Ma
    in caso di gravi insulti o di spam prolungato possono essere presi provvedimenti verso il
    mittente di tali messaggi.
    `n`nProvocazioni e segnalazioni esplicitamente atte a far prendere provvedimenti contro altri utenti
    sono vietate e punite.`n`n
    L'unica area dove è consentito chiedere consigli sul gioco è quella relativa alla `%Sezione Help`#,
    e comunque le eventuali risposte, se riguardano parti `^`isensibili`i`# devono essere date esclusivamente
    tramite MP.`n`n`n`0");

    output("<big>`b`c`^7) ACCANIMENTO`0`c`b</big>`n`n",true);
    output("`#In linea generale tutte le azioni di gioco mirate contro altri personaggi, non negano
    la  possibilità di avanzamento. Inoltre, il gioco prevede diverse possibilità tramite cui anche un
    giocatore più debole può creare problemi ad uno più forte e vendicarsi di un torto subito.
    `n`nMa in casi di prolungata e continua ostilità contro gli stessi giocatori, ostilità CHE VERRA'
    VALUTATA CASO PER CASO SU SEGNALAZIONE, può essere imposta a personaggi o giocatori una tregua
    da rispettare. La tregua verrà però decisa solo in casi particolarmente gravi, prolungati e che
    sfruttano più opzioni di gioco. Non verrà mai concessa una tregua ad un giocatore solo per pochi
    attacchi, specialmente quando questi risultano essere azioni di vendetta, e quando non
    impediscono l'avanzamento del personaggio. E' inoltre condizione necessaria, per ottenere una
    tregua, che le azioni ostili siano subite unilateralmente, e che non ci sia risposta a tali
    azioni, frasi esplicitamente provocatorie incluse. La tregua inoltre viene decisa tra alcuni
    giocatori, ma non consentirà mai l'immunità da tutti i giocatori contemporaneamente.
    `n`nRichiedere una tregua quando non sussistono tutte le condizioni precedenti può portare a
    provvedimenti.
    `n`nL'obbligo di tregua verrà sempre comunicato da parte dello staff prima di entrare in vigore; la
    violazione di tale tregua sarà sanzionata.`n`n`n`0");

    output("<big>`b`c`^8) SEGNALAZIONI E COMUNICAZIONE CON LO STAFF`0`c`b</big>`n`n",true);
    output("`#Per qualsiasi segnalazione o dubbio potete aprire una petizione con il link \"Richiesta
    d'Aiuto\".
    `n`nLa richiesta può essere effettuata sia quando si è connessi, sia quando non lo si è. Tuttavia,
    lo staff non è sempre presente e potrebbe passare del tempo prima che la petizione venga letta.
    Potete utilizzare le petizioni anche per proporre modifiche per migliorare ulteriormente il
    gioco.
    `n`nPer garantire ordine ed una più veloce risposta da parte dello staff, è consigliato ai giocatori
    di scrivere segnalazioni da connessi (quando sia possibile farlo) e con lo stesso personaggio a
    cui è riferita la segnalazione.
    `n`nPrima di aprire petizioni per dubbi di gioco, è richiesta la lettura delle FAQ e delle MiniFAQ.
    `n`nE' inoltre consigliata anche una visita al druido del monastero. Lo staff non risponderà a
    domande la cui risposta è già contenuta in tali pagine.
    `n`nSegnalazioni non veritiere o create appositamente con lo scopo di generare spam sono vietate e
    punite.
    `n`nE' sconsigliato l'utilizzo di messaggi privati (le petizioni sono lette da tutto lo staff, i
    messaggi privati solo da una persona), inoltre in caso di reclamo per provvedimenti disciplinari
    questi non saranno considerati.`n`n`n`0");

    output("<big>`b`c`^9) PROVVEDIMENTI DI GIOCO`0`c`b</big>`n`n",true);
    output("`#I provvedimenti presi da parte dello staff sono i seguenti:
    `n`n`\$- Avvertimento `#(segnalazione di un giocatore, ma senza ulteriori
    conseguenze. Tuttavia reiterati avvertimenti portano agli altri provvedimenti)
    `n`n`\$- Silenziamento di un personaggio `#(impossibilità di conversare nelle stanze di gioco),
    per un numero determinato di sessioni di gioco (che verrà deciso e comunicato caso per caso).
    `n`n`\$- Ban di un personaggio `#(impossibilità di connettere il personaggio), per un numero
    determinato di giorni o permanente.
    `n`n`\$- Ban di tutti i personaggi di un giocatore`#, per un numero determinato di giorni o permanente.
    `n`n`\$- Riduzione dei parametri di un personaggio `#(come valore delle caratteristiche, delle abilità,
    o dei suoi averi)
    `n`n`\$- Cancellazione di un personaggio dal gioco`#
    `n`nLa scelta di un provvedimento e la sua entità viene valutata singolarmente caso per caso a
    seconda dell'infrazione commessa, delle circostanze e dell'esistenza di precedenti infrazioni o
    avvertimenti. La presenza di punti donazione (presente o passata) non ha nessuna importanza.
    `n`n`@ESCLUSIVAMENTE PER IL PROPRIETARIO DELL'ACCOUNT`#, è possibile appellarsi ad eventuali
    provvedimenti `@PRIVATAMENTE `#ed ESCLUSIVAMENTE tramite petizione; la discussione di eventuali
    provvedimenti nelle stanze di gioco o nel forum è vietata e sarà punita. Tale richiesta verrà
    discussa internamente dallo staff, tuttavia solo chi ha attuato il provvedimento o un membro
    dello staff di grado superiore può annullare un provvedimento.
    `n`nI provvedimenti vengono discussi esclusivamente con i diretti interessati. Lo staff non è
    tenuto a dare comunicazione o spiegazioni ad altri giocatori.
    `n`nIl gioco prevede inoltre per alcuni personaggi la possibilità di emettere punizioni di altro
    tipo verso altri personaggi. Questi provvedimenti non sono mai di entità tale da impedire ad un
    personaggio l'avanzamento nel gioco. Tali punizioni sono lasciate PER SCELTA ai giocatori e lo
    staff non interverrà per modificarle.`n`n`n`0");

    output("<big>`b`c`^10) VENDITA OGGETTI O PG`0`c`b</big>`n`n",true);
    output("`#È assolutamente vietato vendere o richiedere la vendita di oggetti (gemme-oro-oggetti-etc)
    o PG per denaro reale. Coloro che verranno scoperti a fare offerte a tale scopo
    verranno bannati a vita.`n`n`n`0");

    output("<big>`b`c`^11) REGOLE MINORI DI GIOCO`0`c`b</big>`n`n",true);
    output("`#E' lasciata ad alcuni personaggi che occupano posizioni di rilievo nel gioco, e limitatamente
    ad alcune possibilità e stanze di gioco, la possibilità di decretare ulteriori regole e di farle
    rispettare con strumenti interni al gioco e punizioni.
    `n`nL'osservanza e l'infrazione di tali regole non verrà mai controllata dallo staff che non
    prenderà alcun provvedimento a riguardo. Allo stesso modo, lo staff non interverrà nelle
    eventuali sanzioni cui chi infrange tali regole andrà incontro.`n`n`n`0");

    output("<big>`b`c`^12) CASI E AZIONI NON PREVISTI DAL REGOLAMENTO`0`c`b</big>`n`n",true);
    output("`#Eventuali situazioni non previste dal regolamento saranno valutate caso per caso dallo staff.`n`n`n`0");

//}

page_footer();
?>