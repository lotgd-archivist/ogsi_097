<?php
require_once "common.php";
$nomedb = getsetting("nomedb","logd");
page_header("REGOLAMENTO");

/* Sook, vecchio regolamento - mantenuto per la modalit� con cui distingueva i 2 server

    output("<big>`n`b`c`&REGOLAMENTO DI LEGEND OF THE GREEN DRAGON`0`c`b</big>`n`n`n",true);
if($nomedb=="logd2"){
    output("`#SU QUESTO SERVER � PERMESSO GESTIRE UN SOLO PERSONAGGIO PER GIOCATORE. PER INFORMAZIONI PI�
    DETTAGLIATE SIETE INVITATI A LEGGERE IL REGOLAMENTO SPECIFICO`n");
    output("<big><big><big><big><big>
            <a href=\"http://www.ogsi.it/modules/newbb/viewtopic.php?topic_id=679&forum=17\" target=\"_blank\">
            <b><font color='#FF0000'>`cREGOLAMENTO SERVER 2`c</font></b></a></big></big></big></big></big>
            <br><font color='#FF0000'>`c(cliccate sulla scritta)`c</font><br>",true);
}
    output("<big>`b`c`^1) SVILUPPO GIOCO`0`c`b</big>`n`n",true);
    output("`#Il gioco � perennemente in fase Beta, per cui pu� subire modifiche in qualsiasi momento e
    senza nessun avviso (per quanto in genere i MoTD avvisano di eventuali modifiche al gioco).
    `n`nLe modifiche decise dallo staff possono essere discusse SOLAMENTE tramite petizione
    (privatamente) o nel forum di gioco. E' VIETATO discuterne nelle stanze di gioco.
    `n`nEventuali danni ad un personaggio dovuti a problemi di gioco o a bug non saranno risarciti.
    `n`nE' richiesto ai giocatori di segnalare eventuali bug trovati, ed � TASSATIVAMENTE VIETATO
    sfruttare eventuali bug per avvantaggiarsi in gioco.
    `n`nNelle segnalazioni cercate di essere il pi� descrittivi possibile e di scrivere i dettagli che
    notate. I dettagli spesso fanno la differenza e sono estremamente importanti per risolvere i problemi di gioco.`n`n`n`0");

    output("<big>`b`c`^2) UTILIZZO DI PROGRAMMI ESTERNI`0`c`b</big>`n`n",true);
    output("`#E' proibito l'utilizzo di qualsiasi programma in grado di generare script automatici o
    semiautomatici in grado di generare comandi di gioco o di interagire con il database del gioco.`n`n`n`0");

    output("<big>`b`c`^3) MULTIACCOUNT`0`c`b</big>`n`n",true);
if($nomedb!="logd2"){
    output("`#E' generalmente permesso creare pi� personaggi.
    `n`nTuttavia tali personaggi riconducibili ad una stessa persona NON POSSONO AVERE ALCUNA INTERAZIONE
    DIRETTA TRA LORO. Si intende con interazione diretta il passagio di soldi, gemme, oggetti o
    materiali da un personaggio ad un altro, anche quando tale passaggio avvenga coinvolgendo altri
    personaggi. Non fa alcuna differenza se il passaggio favorisce il personaggio pi� sviluppato o
    quello meno sviluppato.
    `n`nE' VIETATO creare personaggi appositamente con lo scopo di danneggiare altri giocatori, in
    qualsiasi modo; � inoltre vietato l'utilizzo di nomi volgari o che possono essere confusi con il
    nome di altri personaggi.`n`n`n`0");
}else{
    output("`#NON � permesso creare pi� personaggi.`n`n`n`0");
}
    output("<big>`b`c`^4) CONDIVISIONE ACCOUNT`0`c`b</big>`n`n",true);
if($nomedb!="logd2"){
    output("`#E' generalmente permesso prestare i propri personaggi a terzi, per non perdere sessioni
    di gioco o per altri motivi. Tuttavia, il proprietario dell'account ne rimane responsabile, e
    subir� eventuali punizioni dovute ad un utilizzo inappropriato del personaggio. Ci� non esclude
    provvedimenti anche verso l'affidatario del personaggio.
    `n`nLo staff non prender� in considerazione richieste di risarcimento di oro o gemme dovute al
    danneggiamento di un personaggio da parte di altre persone, o di riduzione di eventuali
    punizioni in gioco. Le uniche richieste a cui avrete risposta sono quelle relative al cambio di
    password. Prima di passare i dati di accesso a qualcuno, accertatevi bene della persona a cui li
    state passando.
    `n`nL'interazione tra personaggi propri e personaggi presi in affidamento pu� essere sanzionata.`n`n`n`0");
}else{
    output("`#NON � permesso prestare i propri personaggi a terzi, sia per non perdere sessioni
    di gioco che per altri motivi.`n`n`n`0");
}
    output("<big>`b`c`^5) STANZE DI GIOCO E MESSAGGI PRIVATI`0`c`b</big>`n`n",true);
    output("`#In molte stanze di gioco � possibile conversare o interagire con gli altri giocatori.
    Alcune stanze sono pubbliche, altre invece sono private e riservate solo ad alcuni personaggi.
    Alcune di queste possono avere regole particolari, ma in tutte � VIETATO, in qualsiasi caso e
    per qualsiasi motivo, l'utilizzo di linguaggio volgare. Nonostante esista un sistema automatico
    di censura, se questo fallisce verranno presi provvedimenti.
    `n`nE' vietato tentare l'aggiramento del sistema di censura storpiando le parole.
    `n`nMinacce o insulti in \"gioco di ruolo\" tra due personaggi sono accettati, a condizione che non
    siano violate altre regole. E' per� vietato e sar� sanzionato pesantemente qualsiasi insulto,
    minaccia o riferimento a fatti personali di un giocatore all'infuori del gioco.
    `n`nI messaggi privati non vengono controllati tranne che su segnalazione da parte di un utente. Ma
    in caso di gravi insulti o di spam prolungato possono essere presi provvedimenti verso il
    mittente di tali messaggi.
    `n`nProvocazioni e segnalazioni esplicitamente atte a far prendere provvedimenti contro altri utenti
    sono vietate e punite.`n`n
    L'unica area dove � consentito chiedere consigli sul gioco � quella relativa alla `%Sezione Help`#,
    e comunque le eventuali risposte, se riguardano parti `^`isensibili`i`# devono essere date esclusivamente
    tramite MP.`n`n`n`0");

    output("<big>`b`c`^6) ACCANIMENTO`0`c`b</big>`n`n",true);
    output("`#In linea generale tutte le azioni di gioco mirate contro altri personaggi, non negano
    la  possibilit� di avanzamento. Inoltre, il gioco prevede diverse possibilit� tramite cui anche un
    giocatore pi� debole pu� creare problemi ad uno pi� forte e vendicarsi di un torto subito.
    `n`nMa in casi di prolungata e continua ostilit� contro gli stessi giocatori, ostilit� CHE VERRA'
    VALUTATA CASO PER CASO SU SEGNALAZIONE, pu� essere imposta a personaggi o giocatori una tregua
    da rispettare. La tregua verr� per� decisa solo in casi particolarmente gravi, prolungati e che
    sfruttano pi� opzioni di gioco. Non verr� mai concessa una tregua ad un giocatore solo per pochi
    attacchi, specialmente quando questi risultano essere azioni di vendetta, e quando non
    impediscono l'avanzamento del personaggio. E' inoltre condizione necessaria, per ottenere una
    tregua, che le azioni ostili siano subite unilateralmente, e che non ci sia risposta a tali
    azioni, frasi esplicitamente provocatorie incluse. La tregua inoltre viene decisa tra alcuni
    giocatori, ma non consentir� mai l'immunit� da tutti i giocatori contemporaneamente.
    `n`nRichiedere una tregua quando non sussistono tutte le condizioni precedenti pu� portare a
    provvedimenti.
    `n`nL'obbligo di tregua verr� sempre comunicato da parte dello staff prima di entrare in vigore; la
    violazione di tale tregua sar� sanzionata.`n`n`n`0");

    output("<big>`b`c`^7) SEGNALAZIONI E COMUNICAZIONE CON LO STAFF`0`c`b</big>`n`n",true);
    output("`#Per qualsiasi segnalazione o dubbio potete aprire una petizione con il link \"Richiesta
    d'Aiuto\".
    `n`nLa richiesta pu� essere effettuata sia quando si � connessi, sia quando non lo si �. Tuttavia,
    lo staff non � sempre presente e potrebbe passare del tempo prima che la petizione venga letta.
    Potete utilizzare le petizioni anche per proporre modifiche per migliorare ulteriormente il
    gioco.
    `n`nPer garantire ordine ed una pi� veloce risposta da parte dello staff, � consigliato ai giocatori
    di scrivere segnalazioni da connessi (quando sia possibile farlo) e con lo stesso personaggio a
    cui � riferita la segnalazione.
    `n`nPrima di aprire petizioni per dubbi di gioco, � richiesta la lettura delle FAQ e delle MiniFAQ.
    `n`nE' inoltre consigliata anche una visita al druido del monastero. Lo staff non risponder� a
    domande la cui risposta � gi� contenuta in tali pagine.
    `n`nSegnalazioni non veritiere o create appositamente con lo scopo di generare spam sono vietate e
    punite.
    `n`nE' sconsigliato l'utilizzo di messaggi privati (le petizioni sono lette da tutto lo staff, i
    messaggi privati solo da una persona), inoltre in caso di reclamo per provvedimenti disciplinari
    questi non saranno considerati.`n`n`n`0");

    output("<big>`b`c`^8) PROVVEDIMENTI DI GIOCO`0`c`b</big>`n`n",true);
    output("`#I provvedimenti presi da parte dello staff sono i seguenti:
    `n`n`\$- Avvertimento `#(segnalazione di un giocatore, ma senza ulteriori
    conseguenze. Tuttavia reiterati avvertimenti portano agli altri provvedimenti)
    `n`n`\$- Silenziamento di un personaggio `#(impossibilit� di conversare nelle stanze di gioco),
    per un numero determinato di sessioni di gioco (che verr� deciso e comunicato caso per caso).
    `n`n`\$- Ban di un personaggio `#(impossibilit� di connettere il personaggio), per un numero
    determinato di giorni o permanente.
    `n`n`\$- Ban di tutti i personaggi di un giocatore`#, per un numero determinato di giorni o permanente.
    `n`n`\$- Riduzione dei parametri di un personaggio `#(come valore delle caratteristiche, delle abilit�,
    o dei suoi averi)
    `n`n`\$- Cancellazione di un personaggio dal gioco`#
    `n`nLa scelta di un provvedimento e la sua entit� viene valutata singolarmente caso per caso a
    seconda dell'infrazione commessa, delle circostanze e dell'esistenza di precedenti infrazioni o
    avvertimenti. La presenza di punti donazione (presente o passata) non ha nessuna importanza.
    `n`n`@ESCLUSIVAMENTE PER IL PROPRIETARIO DELL'ACCOUNT`#, � possibile appellarsi ad eventuali
    provvedimenti `@PRIVATAMENTE `#ed ESCLUSIVAMENTE tramite petizione; la discussione di eventuali
    provvedimenti nelle stanze di gioco o nel forum � vietata e sar� punita. Tale richiesta verr�
    discussa internamente dallo staff, tuttavia solo chi ha attuato il provvedimento o un membro
    dello staff di grado superiore pu� annullare un provvedimento.
    `n`nI provvedimenti vengono discussi esclusivamente con i diretti interessati. Lo staff non �
    tenuto a dare comunicazione o spiegazioni ad altri giocatori.
    `n`nIl gioco prevede inoltre per alcuni personaggi la possibilit� di emettere punizioni di altro
    tipo verso altri personaggi. Questi provvedimenti non sono mai di entit� tale da impedire ad un
    personaggio l'avanzamento nel gioco. Tali punizioni sono lasciate PER SCELTA ai giocatori e lo
    staff non interverr� per modificarle.`n`n`n`0");

    output("<big>`b`c`^9) REGOLE MINORI DI GIOCO`0`c`b</big>`n`n",true);
    output("`#E' lasciata ad alcuni personaggi che occupano posizioni di rilievo nel gioco, e limitatamente
    ad alcune possibilit� e stanze di gioco, la possibilit� di decretare ulteriori regole e di farle
    rispettare con strumenti interni al gioco e punizioni.
    `n`nL'osservanza e l'infrazione di tali regole non verr� mai controllata dallo staff che non
    prender� alcun provvedimento a riguardo. Allo stesso modo, lo staff non interverr� nelle
    eventuali sanzioni cui chi infrange tali regole andr� incontro.`n`n`n`0");

    output("<big>`b`c`^10) CASI E AZIONI NON PREVISTI DAL REGOLAMENTO`0`c`b</big>`n`n",true);
    output("`#Eventuali situazioni non previste dal regolamento saranno valutate caso per caso dallo staff.`n`n`n`0");

Da qui nuovo regolamento*/

    output("<big>`n`b`c`&REGOLAMENTO DI LEGEND OF THE GREEN DRAGON`0`c`b</big>`n`n`n",true);

    output("`b`\$IMPORTANTE: LEGGERE E CONOSCERE IL REGOLAMENTO � OBBLIGATORIO`b`#.`n`n");
    output("`#Qualsiasi infrazione accompagnata dalla scusa \"io non lo sapevo\" verr� punita con il `b`\$TRIPLO`b`# 
            della pena.`n`n`n`n`n");

    output("<big>`b`c`^A) SVILUPPO GIOCO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `b`@Legend of the Green Dragon`b`# (`b`@LoGD`b`#) � costantemente in `u`&fase beta`u`#: ci� significa 
            che `b`@ogni`b`# suo aspetto pu� andare incontro in qualsiasi momento ad improvvise e ripetute modifiche 
            allorch� lo staff lo ritenga necessario. Alcune di queste modifiche verranno notificate ai 
            giocatori tramite i `b`@Messaggi del Giorno`b`# (`b`@MoTD`b`#), altre saranno introdotte silentemente.`n`n");
    output("`#- Registrandosi al gioco, gli utenti dichiarano implicitamente di accettare ogni eventuale futura 
            modifica di qualsivoglia natura.`n`n");
    output("`#- Dichiarano altres� di accettare che qualsiasi tipo di danno ad un personaggio dovuto a problemi 
            o bug del gioco non verr� risarcito, salvo situazioni di natura realmente eccezionale che verranno 
            in ogni caso discussi privatamente dallo staff, il cui giudizio resta sempre `b`@insindacabile`b`#.`n");
    output("`#- Eventuali discussioni riguardanti le modifiche apportate al gioco devono essere portate avanti 
            nel forum, `b`@mai`b`# nelle stanze di gioco.`n`n`n");
    output("`%`b2`b) `#� fatta esplicita richiesta a tutti i giocatori di `b`@segnalare immediatamente`b`# qualsiasi 
            `ubug`u venga riscontrato.`n`n");
    output("`#- La segnalazione deve essere inviata allo staff `b`@esclusivamente attraverso il sistema di petizioni`b`#, 
            e deve riportare tutte le informazioni e i dettagli possibili, per permettere allo staff di identificare 
            quanto prima la natura del problema.`n`n`n");
    output("`%`b3`b) `#Va da s� che � `b`@TASSATIVAMENTE VIETATO`b`# sfruttare bug e debolezze del sistema per avvantaggiarsi 
              in gioco.`n`n");
    output("`#- Lo sfruttamento di un bug comporta il `b`@BAN`b`# immediato del personaggio, di durata proporzionale alla 
            gravit� della situazione. In caso di gravi violazioni il provvedimento pu� essere allargato a `b`@TUTTI`b`# 
            i personaggi di un utente, e la sua durata pu� essere estesa `b`@indefinitamente`b`#.`n`n`n`n`n");

    output("<big>`b`c`^B) MULTIACCOUNT`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#� permesso creare e gestire fino a `b`@SEI personaggi`b`# per ogni connessione.`n`n");
    output("`#- Se due o pi� persone che giocano a LoGD risiedono nella stessa abitazione, `b`@devono dunque spartirsi 
            i sei personaggi disponibili`b`# come meglio credono.`nGli utenti che ignoreranno il limite di sei 
            personaggi verranno puniti con un `b`@BAN`b`# non inferiore a `b`@cinque giorni`b`# ed esteso a `b`@tutti`b`# 
            i loro personaggi.`nI personaggi in eccesso andranno inoltre incontro ad `b`@immediata cancellazione`b`#, 
            partendo da quelli con il numero pi� basso di DK e reincarnazioni.`nIn tale circostanza, agli utenti puniti � 
            `b`@tassativamente vietato`b`# creare nuovi personaggi, pena l'immediata cancellazione di questi ultimi ed 
            il `b`@raddoppio`b`# della durata del provvedimento.`n`n`n");
    output("`%`b2`b) `#I personaggi appartenenti ad uno stesso giocatore devono `b`@necessariamente`b`# essere registrati 
            tramite lo stesso indirizzo e-mail.`n`n`n");
    output("`%`b3`b) `#� `b`@assolutamente vietata`b`# qualsiasi forma di `b`@interazione`b`# fra personaggi 
            appartenenti allo stesso giocatore.`n`n");
    output("`#- Col termine �interazione� si intendono passaggi - diretti o indiretti, cio� utilizzando l'aiuto 
            di terzi che fungano da tramite - di denaro, gemme, oggetti o territori tra i personaggi, nonch� 
            il matrimonio, il tutoraggio, gli attacchi reciproci o destinati ad uno stesso bersaglio, e qualsiasi 
            altra azione che possa collegare in qualche modo i personaggi.`n`n");
    output("`#- � generalmente concesso ai personaggi di uno stesso giocatore di convivere nella stessa tenuta, 
            purch� non si configurino le violazioni di cui sopra. Lo staff si riserva il diritto di chiedere - 
            ed ottenere senza alcun indugio, pena l'uso della forza - l'allontanamento di uno o pi� personaggi 
            da una specifica tenuta per evitare spiacevoli inconvenienti.`n`n`n");
    output("`%`b4`b) `#In `b`@nessun caso`b`# � consentito `b`@affidare temporaneamente`b`# i propri personaggi ad 
            altri giocatori.`n`n");
    output("`#- � fatto `b`@assoluto divieto`b`# a tutti i giocatori di utilizzare personaggi appartenenti ad altri, 
            con o senza l'autorizzazione dei proprietari.`n Gli utenti che non rispetteranno questa regola riceveranno 
            un `b`@BAN immediato`b`# ed esteso a `b`@tutti i personaggi appartenenti a ciascuno dei giocatori coinvolti nella 
            violazione del regolamento`b`#.`n La durata del ban sar� proporzionale all'entit� dell'infrazione.`n`n`n");
    output("`%`b5`b) `#Nel momento in cui due o pi� giocatori si trovino a giocare dallo stesso luogo (ad esempio 
            perch� familiari), devono richiedere allo staff l'`b`@autorizzazione`b`# alla creazione dei loro personaggi, che 
            non potranno eccedere il numero totale di `b`@SEI`b`#.`n`n");
    output("`#- Tale autorizzazione deve essere richiesta `b`@individualmente`b`# - `b`@ogni giocatore`b`# interessato deve 
            dunque attivarsi e `b`@fare richiesta per conto proprio`b`# - attraverso il forum del gioco, scrivendo nella 
            apposita <a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>sezione petizioni</font></b></a>.`n`n",true);
    output("`#- � `b`@assolutamente vietato`b`# procedere alla creazione dei personaggi prima che `b`@tutti i giocatori`b`# 
            interessati abbiano ricevuto l'`b`@autorizzazione dello staff`b`#.`n`n");
    output("`#- I personaggi appartenenti a giocatori residenti nello stesso luogo devono `b`@tassativamente`b`# evitare 
            `b`@qualsiasi tipo`b`# di interazione, come gi� spiegato al punto `b`@3`b`# di questa sezione. I trasgressori 
            verranno puniti con un `b`@BAN`b`# esteso a `b`@tutti i personaggi`b`#, di durata proporzionale all'entit� 
            dell'infrazione.`n`n`n");
    output("`%`b6`b) `#Allorch� due o pi� giocatori `b`@normalmente estranei`b`# tra loro si trovino obbligati a giocare 
            `b`@dallo stesso luogo`b`# (ad esempio nel caso di una breve visita dell'uno a casa dell'altro), essi devono 
            `b`@avvisare preventivamente lo staff`b`# ed attenderne l'`b`@autorizzazione`b`#.`n`n");
    output("`#- Come nel caso analizzato al punto `b`@5`b`# di questa sezione, tale `b`@autorizzazione`b`# deve essere 
            `b`@richiesta individualmente`b`# - `b`@ogni giocatore`b`# interessato deve dunque attivarsi e fare `b`@richiesta 
            per conto proprio`b`# - attraverso il forum del gioco, scrivendo nella apposita 
            <a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>sezione petizioni</font></b></a>.`n`n",true);
    output("`#- � `b`@assolutamente vietato`b`# effettuare il `b`@login dalla medesima postazione`b`# prima che `b`@tutti 
            i giocatori`b`# interessati abbiano ricevuto l'autorizzazione dello staff.`n`n");
    output("`#- Anche in questo caso i personaggi appartenenti a giocatori diversi devono `b`@tassativamente`b`# 
            evitare `b`@qualsiasi tipo`b`# di interazione, come gi� spiegato al punto `b`@3`b`# di questa sezione. I trasgressori 
            verranno puniti con un `b`@BAN`b`# esteso a `b`@tutti i personaggi`b`#, di durata proporzionale all'entit� 
            dell'infrazione.`n`n`n");
    output("`%`b7`b) `#Qualora un giocatore `b`@gi� in possesso di sei personaggi`b`# decida di abbandonarne uno, prima della 
            eventuale creazione di un nuovo personaggio deve `b`@attendere l'autocancellazione per inattivit�`b`# di quello 
            abbandonato.`n`n");
    output("`#- La creazione di un nuovo personaggio prima della cancellazione del precedente si configura come violazione della 
            regola numero `b`@1`b`# di questa sezione, con tutte le conseguenze del caso.`n`n`n");
    output("`%`b8`b) `#� consentito ai giocatori abbandonare i propri personaggi `b`@cedendoli definitivamente`b`# 
            ad altri utenti.`n`n");
    output("`#- Un giocatore pu� entrare in possesso di un nuovo personaggio `b`@solo nel caso`b`# in cui non ne 
            possieda gi� altri sei.`n`n");
    output("`#- Il `b`@cambio di proprietario`b`# deve essere sancito dal `b`@cambio dell'indirizzo e-mail`b`# associato 
            al personaggio.`n`n");
    output("`#- L'`b`@autorizzazione alla cessione`b`# deve essere `b`@richiesta individualmente`b`# - ogni giocatore 
            interessato deve dunque attivarsi e fare richiesta per conto proprio - attraverso il forum del gioco, scrivendo 
            nella apposita <a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>sezione petizioni</font></b></a>.`n`n",true);
    output("`#- Con la cessione del personaggio, il proprietario originale `b`@rinuncia ad ogni diritto`b`# su 
            quel personaggio.`n`n`n`n`n");

    output("<big>`b`c`^C) PRATICHE SCORRETTE`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#� `b`@severamente proibito`b`# l'uso di qualsivoglia programma in grado di generare script 
            automatici o semiautomatici che possano interagire con il gioco o il database.`n`n");
    output("`#- Gli utenti scoperti a fare uso di simili stratagemmi saranno passibili di `b`@BAN`b`# immediato, di durata 
            variabile e potenzialmente estensibile a `b`@tutti`b`# i personaggi, al pari di quanto avviene per lo 
            sfruttamento di bug.`n`n`n");
    output("`%`b2`b) `#� altres� `b`@vietato`b`# effettuare il `b`@login`b`# avvalendosi dell'uso di `b`@proxy`b`#.`n`n`n");
    output("`%`b3`b) `#In `b`@nessun caso`b`# � consentito `i`u`&rushare`u`i`# un personaggio utilizzandone un altro. Col termine 
            `i`u`&rushare`u`i`# si intende l'atto del favorire un personaggio consentendogli di crescere a velocit� pi� 
            elevata del normale. Le pratiche vietate comprendono:`n`n");
    output("`#- Il passaggio di grandi quantit� di oro tra due o pi� personaggi. Tale passaggio � vietato `b`@sia`b`# 
            attraverso le tenute, `b`@sia`b`# tramite qualsiasi altra via. � comunque generalmente permesso aiutare un 
            personaggio nuovo o in difficolt� regalandogli dell'oro, purch� ci� avvenga `b`@entro i limiti del buon senso`b`#.`n`n");
    output("`#- Il passaggio di gemme tra due o pi� personaggi. Tenete presente che c'� un preciso motivo se tale 
            possibilit� `b`@non � contemplata`b`# all'interno delle tenute. Evitate quindi di regalare le gemme dei 
            vostri personaggi a quelli appartenenti ad altri giocatori.`n`n");
    output("`#- In caso di violazione del regolamento, `b`@tutti`b`# i personaggi coinvolti verranno puniti con un `b`@BAN`b`# di 
            durata proporzionale alla gravit� dell'infrazione. Inoltre, `b`@tutti`b`# gli averi indebitamente ottenuti dai 
            personaggi verranno immediatamente `b`@sottratti`b`#, insieme a oggetti, punti statistiche o quant'altro sia 
            stato gi� acquistato con tali averi.`n`n");
    output("`%`b4`b) `# � `b`@tassativamente vietata`b`# la vendita di oggetti, oro, gemme, territori, personaggi o quant'altro 
            in cambio di denaro reale. Chi verr� scoperto a proporre o accettare simili accordi verr� punito con un 
            `b`@BAN immediato a vita`b`#.`n`n`n`n`n");

    output("<big>`b`c`^D) NOMI DEI PERSONAGGI`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Al momento della creazione di un nuovo personaggio, `b`@bisogna attendere`b`# che lo staff vagli 
            e `b`@approvi il nome`b`# scelto dal giocatore. Esso:`n`n");
    output("`#- `b`@deve`b`# essere attinente al mondo fantasy`n`n");
    output("`#- `b`@non deve`b`# essere scritto in maiuscolo (es. `&BILBOBAGGINS`#)`n`n");
    output("`#- `b`@non deve`b`# essere scritto con alternanza di caratteri maiuscoli e minuscoli (es. `&BiLbObAgGiNs`#)`n`n");
    output("`#- `b`@pu�`b`# contenere maiuscole laddove si voglia evidenziare un eventuale cognome (es. `&BilboBaggins`#)`n`n");
    output("`#- `b`@non deve`b`# contenere titoli nobiliari (es. `&Re_Lear`#) n� epiteti (es. `&Jack_the_Ranger`#, `&The_King`#)`n`n");
    output("`#- `b`@non deve`b`# contenere termini offensivi e/o volgari, siano essi palesi o sottintesi, in nessuna 
            lingua o dialetto`n`n`n");
    output("`%`b2`b) `#Se la vostra fantasia viene meno e non riuscite a pensare ad un bel nome per il vostro personaggio, 
            potete aiutarvi utilizzando un generatore di nomi fantasy. Se ne trovano molti in rete, un esempio �
            <a href=\"http://www.wizards.com/dnd/article5.asp?x=dnd/dx20010202b,0\" target=\"_blank\">
            <b><font color='#DDFFBB'>QUESTO</font></b></a>.`n`n`n",true);
    output("`%`b3`b) `#Lo staff si riserva il diritto di `b`@cancellare immediatamente`b`# personaggi i cui nomi non 
            rispondano ai criteri sopra elencati.`n`n`n");
    output("`%`b4`b) `#Scegliete `b`@con molta attenzione`b`# i nomi dei vostri personaggi, poich� `b`@non sar� possibile 
            cambiarli`b`# al termine della registrazione.`n`n");
    output("`#- In `b`@casi particolari`b`#, e solo per `b`@motivazioni particolari`b`#, lo staff potrebbe accettare di cambiare 
            il nome di un personaggio `b`@che non abbia ancora ucciso il Drago Verde`b`#, e che sia quindi ancora un 
            `b`@contadino`b`#.`n`n`n`n`n");

    output("<big>`b`c`^E) STANZE DI GIOCO E MESSAGGI PRIVATI`0`c`b</big>`n`n",true);
    output("`%`b1`b) `b`@LoGD � un gioco di ruolo (GdR) testuale`b`#: gli utenti hanno dunque la possibilit� di 
            intervenire nelle stanze di gioco descrivendo azioni, pensieri e parole dei propri personaggi, 
            interagendo con quelli degli altri giocatori.`nMolte stanze sono completamente pubbliche, 
            alcune sono invece accessibili solo a cerchie pi� o meno ristrette di personaggi.`n`n");
    output("`#- In `b`@tutte`b`# le stanze � `b`@assolutamente vietato`b`# l'uso di termini volgari e/o osceni, Sono 
            altres� vietati riferimenti espliciti a sessualit� e/o estrema brutalit�. Ricordate che in gioco 
            potrebbero essere presenti dei minori. Chi contravverr� a questa regola verr� `b`@immediatamente punito`b`# 
            con un provvedimento di natura e durata proporzionali alla gravit� dell'infrazione.`n`b`@Eventuali 
            riferimenti a tematiche illegali (es. pedopornografia, spaccio di droga, ecc.) verranno immediatamente 
            riportati alle autorit� competenti`b`#.`n`n");
    output("`#- Non � ammesso l'uso di bestemmie, siano esse esplicite o velate. Eventuali esclamazioni che 
            richiamino chiaramente � pur se indirettamente - delle bestemmie sono altrettanto poco gradite. 
            Esiste `b`@sempre`b`# la possibilit� che qualcuno possa risentirsi per simili modi di dire, quindi evitate, 
            cortesemente. Esistono tante altre possibili esclamazioni che potete usare.`n`n");
    output("`#- Un sistema automatico di censura provvede ad oscurare un gran numero di parole non ammesse: `b`@� 
            fatto divieto`b`# di tentare di aggirare in qualsiasi modo tale sistema, pena il `b`@silenziamento immediato`b`# 
            di `b`@tutti`b`# i personaggi dei giocatori che contravvengano alla regola, di durata proporzionale alla gravit� 
            dell'infrazione.`n`n`n");
    output("`%`b2`b) `#Le stanze pubbliche sono dedicate al gioco di ruolo: � `b`@vietato`b`# parlare di argomenti inerenti alla 
            vita reale. Contravvenire a tale regola causer� il `b`@silenziamento immediato`b`# di `b`@tutti`b`# i personaggi 
            del giocatore.`n`n");
    output("`#- Le `b`@tenute`b`#, in quanto stanze private, sono le `b`@uniche`b`# in cui � concesso parlare di argomenti 
            non inerenti al gioco.`nAnche nelle `b`@sale di ritrovo `i`uprivate`u`i delle sette`b`# tale pratica � tollerata, 
            purch� non si esageri.`n`n");
    output("`#- La `b`@sezione help`b`#, accessibile dal link in alto a sinistra nella piazza del villaggio, � l'`b`@unica 
            stanza`b`# in cui sia concesso porre domande inerenti al gioco. Per `b`@evitare possibili spoiler`b`# a ignari 
            giocatori, lo staff `b`@consiglia caldamente`b`# ai pi� esperti di rispondere alle domande facendo uso dei 
            messaggi privati. Tale pratica diventa `b`@obbligatoria`b`# nel caso in cui le informazioni riguardino segreti 
            e tattiche di gioco, ed il mancato adempimento potrebbe essere punito.`n`n");
    output("`#- In `b`@NESSUNA`b`# stanza � consentito discutere dei provvedimenti di gioco, delle modifiche e delle punizioni 
            decise dallo staff. Il mancato adempimento porter� ad un `b`@silenziamento`b`# di `b`@tutti`b`# i personaggi 
            del giocatore, della durata minima di `b`@trenta newday`b`#.`n`n`n");
    output("`%`b3`b) `#� `b`@tassativamente vietato`b`# insultare gli altri giocatori. La pena per una simile infrazione sar� 
            un `b`@BAN immediato`b`# della durata di `b`@almeno`b`# una settimana, con possibilit� di estensione del 
            provvedimento `b`@a vita`b`#.`n`n");
    output("`#- Sono accettati insulti da personaggio a personaggio nell'ambito del gioco di ruolo e nei limiti del 
            buon gusto. Lo staff si riserva il diritto di discutere privatamente caso per caso e decidere `b`@a proprio 
            insindacabile giudizio`b`# in quali situazioni un insulto sia o meno reale.`n`n");
    output("`#- Qualora un giocatore si senta insultato da qualcuno, deve riferirlo `b`@immediatamente`b`# allo staff facendo 
            uso del sistema di `b`@petizioni`b`# e riportando tutti i dettagli utili al caso, compresa una spiegazione 
            esauriente del motivo secondo cui l'insulto viene percepito come reale e non fittizio.`n`n");
    output("`#- `b`@Solo il giocatore che si ritiene insultato`b`# ha il permesso di segnalare l'accaduto allo staff: � 
            `b`@vietato`b`# a terzi, salvo casi realmente palesi, l'invio di petizioni riguardanti presunte offese ricevute 
            da chicchessia. Questo per evitare che lo staff venga sommerso di segnalazioni ogni volta che qualcuno 
            definisce scemino qualcun altro. Lasciate decidere `b`@al diretto interessato`b`# se l'offesa lo tocca personalmente 
            o meno. Segnalazioni da parte di utenti non coinvolti in prima persona potranno essere punite.`n`n`n");
    output("`%`b4`b) `#In caso di segnalazione di presunti insulti inviati tramite messaggi privati, lo staff si 
            riserva il diritto di esaminare le caselle di posta di `b`@tutti`b`# i personaggi di `b`@tutti`b`# i giocatori 
            coinvolti ed eventualmente punire chi abbia lanciato le offese. Si consiglia dunque a chi dovesse ricevere ingiurie 
            di conservare i messaggi in qualit� di prova e contattare `b`@immediatamente`b`# lo staff tramite il sistema di 
            `b`@petizioni`b`#.`n`n");
    output("`#- I messaggi privati potranno essere esaminati anche ogniqualvolta lo staff dovesse ricevere segnalazioni 
            riguardanti un uso errato di tale strumento, ad esempio nel caso in cui un giocatore lo sfruttasse per 
            trattare questioni illegali o profondamente immorali, per screditare lo staff, per discutere su come 
            violare il regolamento senza essere scoperto o per istigare qualcun altro a contravvenire ad esso. Le 
            punizioni ricalcheranno eventualmente quelle gi� indicate nei rispettivi paragrafi del regolamento stesso.`n`n`n");
    output("`%`b5`b) `#Il sistema dei messaggi privati mette a disposizione dei giocatori una lista in cui inserire 
            gli utenti da ignorare. Quando il personaggio `b`@A`b`# inserisce il personaggio `b`@B`b`# nella lista degli 
            ignorati, il personaggio `b`@B`b`# non pu� pi� inviare messaggi privati al personaggio `b`@A`b`#.`n`n");
    output("`#- � `b`@vietato`b`# agli utenti aggirare `b`@in qualsiasi modo`b`# questo sistema. Se qualcuno non vuole 
            ricevere un vostro messaggio, non deve riceverlo. `b`@Punto`b`#. La punizione per chi dovesse aggirare il sistema 
            consister� in un `b`@BAN`b`# di durata variabile a seconda della situazione.`n`n");
    output("`#- Chi dovesse ricevere per vie traverse messaggi da utenti bloccati � pregato di `b`@non cancellare`b`# tali 
            messaggi e di informare `b`@immediatamente`b`# lo staff attraverso l'invio di una `b`@petizione`b`#.`n`n`n");
    output("`%`b6`b) `#In tutte le stanze di gioco � `b`@vietato`b`# l'utilizzo di abbreviazioni in stile SMS. Toglietevi quindi 
            dalla testa i vari `i`&xk�`i`#, `i`&cmq`i`#, `i`&tvb`i`#. La violazione di questa regola porter� al 
            `b`@silenziamento immediato`b`# di `b`@tutti`b`# i personaggi del giocatore.`n`n");
    output("`#- Nella `b`@maggior parte`b`# delle stanze � `b`@vietato`b`# l'uso di smiley, faccine, emoticon o comunque le si 
            voglia chiamare.`nLo staff si mostrer� pi� tollerante nei confronti di simili pratiche `b`@solo`b`# nelle seguenti 
            stanze: `i`u`&la piazza`u`i`#, `i`u`&il municipio`u`i`#, `i`u`&le tenute`u`i`# e `i`u`&le sale comuni delle sette`u`i`#. 
            In tali ambienti � consentito fare uso di simboli, purch� ci� avvenga `b`@entro i limiti del buon senso`b`# ed 
            evitando di spammare inserendone troppi tutti insieme.`nL'uso di qualunque simbolo in tutte le altre stanze verr� 
            punito con il `b`@silenziamento immediato`b`# di `b`@tutti`b`# i personaggi del giocatore.`n`n");
    output("`#- In tutte le stanze � consentito l'uso dei colori, `b`@entro i limiti del buon senso`b`# ed evitando di 
            creare inutili arcobaleni rendendo il testo illeggibile.`n`n");
    output("`#- � `b`@vietato`b`# l'uso di lingue straniere nelle stanze di gioco, se non per esclamazioni o frasi molto brevi 
            e senza abusarne. Lo scopo del gioco � `b`@comunicare`b`# con `b`@tutti`b`# gli altri giocatori, `b`@non`b`# 
            rendere le proprie parole intellegibili solo per alcuni. Inoltre, un moderatore che dovesse trovarsi di fronte 
            ad una lingua estranea potrebbe trovare difficolt� nello svolgimento dei suoi compiti.`nSe volete citare un 
            testo straniero, citatene `b`@la traduzione`b`#.`n`n");
    output("`#- � `b`@caldamente sconsigliato`b`# l'uso di espressioni eccessivamente gergali, dialettali o comunque legate 
            al mondo moderno. Lo staff si riserva il diritto di intervenire cancellando tali espressioni laddove lo ritenga 
            necessario.`n`n");
    output("`#- � altres� `b`@sconsigliato`b`# l'uso di terminologie moderne (es. `i`&radio`i`#, `i`&computer`i`#, `i`&shuttle`i`#, 
            ecc.), che risultino dunque anacronistiche rispetto all'ambientazione del gioco.`nLo staff si riserva comunque 
            la possibilit� di essere pi� o meno tollerante nei confronti di tali termini a seconda dell'atmosfera 
            di gioco, che verr� valutata di volta in volta.`nGradiremmo evitare di essere inondati di petizioni 
            riguardanti l'utilizzo di occhiali, pop corn, farmaci, oggetti tecnologici e quant'altro, fintantoch� 
            tali concetti vengano espressi nei limiti della decenza ed in situazioni rilassate.`n`n`n`n`n");

    output("<big>`b`c`^F) ACCANIMENTO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Il gioco mette a disposizione degli utenti la possibilit� di scontrarsi fra loro in svariati 
            modi. Gli scontri fra gli utenti hanno scopo `b`@ludico`b`# e non devono `b`@mai`b`# sfociare nell'impossibilit� 
            per l'uno o l'altro contendente di avanzare nel gioco a causa della contesa stessa.`n`n`n");
    output("`%`b2`b) `#In caso di prolungata e continua ostilit� da parte di un personaggio nei confronti di un altro, 
            lo staff potrebbe decidere di imporre una tregua forzata tra i due.`n`n");
    output("`#- Tale decisione verr� presa solo in casi di `b`@grave`b`# accanimento, soprattutto - `b`@ma non necessariamente`b`# 
            - se il personaggio che subisce gli attacchi (qualsiasi sia la natura di tali attacchi) si trovasse 
            a causa di questi ultimi nell'impossibilit� di avanzare nel gioco in modo regolare.`n`n");
    output("`#- Lo staff analizzer� `b`@caso per caso`b`# ogni presunto accanimento, solo `b`@previo segnalazione`b`# tramite 
            `b`@petizione`b`#, e provveder� a prendere una decisione ed a comunicarla ai diretti interessati nel pi� breve 
            tempo possibile.`n`n");
    output("`#- Come gi� specificato nel caso delle offese tra giocatori, `b`@solo colui che si ritiene vittima di 
            accanimento`b`# ha il permesso di segnalare l'accaduto allo staff: � `b`@assolutamente vietato`b`# a terzi l'invio 
            di petizioni riguardanti presunti accanimenti nei confronti di chicchessia. Lasciate decidere al diretto 
            interessato se gli attacchi subiti sono sopportabili o meno. Segnalazioni da parte di utenti non coinvolti 
            in prima persona potranno essere punite.`n`n");
    output("`#- Condizione necessaria alla stipula di una tregua forzata � che gli attacchi siano `b`@unidirezionali`b`#: 
            il personaggio attaccato non deve dunque rispondere alle angherie subite, n� con la forza, n� con eventuali 
            provocazioni esplicite in pubblico.`n`n");
    output("`#- La tregua forzata protegge un personaggio dagli attacchi di `b`@un`b`# altro personaggio, `b`@non`b`# da quelli 
            di `b`@tutti`b`# i personaggi presenti in citt�.`n`n`n");
    output("`%`b3`b) `#La violazione della tregua forzata comporter� il `b`@BAN`b`# del personaggio della durata `b`@non 
            inferiore`b`# a `b`@tre`b`# giorni.`n`n");
    output("`#- L'uso di pi� personaggi per attaccare un altro giocatore si configurer� `b`@sia`b`# come reato di multiaccount, 
            `b`@sia`b`# come reato di accanimento, portando ad un `b`@BAN`b`# immediato della durata `b`@non inferiore`b`# 
            ad `b`@una settimana`b`#.`n`n");
    output("`#- Va da s� che � `b`@vietato`b`# creare nuovi personaggi al solo scopo di danneggiare un altro giocatore. Tale 
            pratica verr� punita con la `b`@cancellazione immediata`b`# del nuovo personaggio e con un `b`@BAN`b`# di un giorno 
            esteso a `b`@tutti`b`# i personaggi del colpevole.`n`n`n`n`n");

    output("<big>`b`c`^G) TIPOLOGIE DI PROVVEDIMENTI`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Tra i provvedimenti che lo staff pu� prendere nei confronti dei giocatori sono compresi i seguenti:`n`n");
    output("`#- `b`^Avvertimento`b`#: Avviso privato al giocatore atto a spiegare nei dettagli quando e come egli abbia 
            contravvenuto al regolamento.`n`b`@Avvertimenti reiterati porteranno automaticamente a provvedimenti di natura 
            pi� seria`b`#.`n`n");
    output("`#- `b`^Silenziamento`b`# (`&Mute`#): Revoca del permesso di scrivere nelle stanze pubbliche. Pu� avere durata 
            variabile ed interessa `b`@tutti i personaggi di uno stesso giocatore`b`#.`n`n");
    output("`#- `b`^Riduzione delle statistiche`b`#: Modifica delle caratteristiche di un personaggio � quali attacco, 
            difesa, HP o altro - e/o dei suoi averi, ivi compresi oro, gemme, animali, oggetti, tenute, fattorie, 
            eccetera.`n`n");
    output("`#- `b`^Esclusione dal gioco`b`# (`&Ban`#): Revoca dell'autorizzazione ad effettuare il login per un numero 
            variabile di giorni. Si tratta di un provvedimento molto serio, inflitto in casi di gravi violazioni del 
            regolamento. Pu� interessare `b`@uno o pi� personaggi`b`# di un giocatore, a seconda della gravit� dell'infrazione. 
            In situazioni eccezionali la sua durata pu� essere estesa `b`@indefinitamente`b`#.`n`n");
    output("`#- `b`^Cancellazione immediata di un personaggio`b`#: Normalmente utilizzata per eliminare dal gioco nuovi 
            personaggi creati con intenti malevoli.`n`n");
    output("`#- Lo staff si riserva il diritto di comminare provvedimenti non presenti in questa lista qualora lo ritenga 
            opportuno, a sua totale discrezione.`n`n`n");
    output("`%`b2`b) `#Il possesso di un qualsiasi ammontare di punti donazione, siano essi dieci o un milione, 
            `b`@non`b`# influir� `b`@mai`b`# sulle decisioni prese dallo staff in tema di provvedimenti. Sia chiaro, a 
            tal proposito, che `b`@un personaggio bannato a vita perde automaticamente la possibilit� di far fruttare i 
            punti donazione acquisiti`b`#.`n`n`n");
    output("`%`b3`b) `#I provvedimenti `b`@non sono contestabili pubblicamente`b`#, n� in gioco n� nel forum. Per richiedere 
            spiegazioni e/o tentare di chiarire la propria posizione gli utenti devono fare uso del sistema di `b`@petizioni`b`# 
            o dell'<a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>apposita sezione</font></b></a> del forum, attraverso cui possono discutere 
            privatamente con i membri dello staff.`n`n",true);
    output("`#- I provvedimenti presi verranno discussi `b`@solo`b`# con i diretti interessati. Lo staff non ha alcun 
            obbligo, n� interesse, nello spiegare la propria posizione a giocatori non coinvolti in prima persona.`n`n`n");
    output("`%`b4`b) `#In alcuni casi lo staff potrebbe decidere di richiedere ad un giocatore di chiarire la propria 
            posizione prima di infliggere una punizione.`n`n");
    output("`#- In questi casi, lo staff invier� un `b`@messaggio privato`b`# al personaggio coinvolto nella violazione del 
            regolamento. Il giocatore avr� a disposizione `b`@24 ore per rispondere`b`#.`n`n");
    output("`#- In caso di `b`@login`b`# del personaggio e `b`@mancata risposta`b`# al messaggio privato, `b`@la punizione verr� 
            inflitta 24 ore dopo l'invio di quest'ultimo`b`#.`n`n");
    output("`#- In caso di `b`@mancato login`b`# del personaggio nelle 24 ore successive all'invio del messaggio privato, verr� 
            inviato un `b`@avviso tramite e-mail`b`#. La mancata risposta porter� `b`@automaticamente`b`# alla punizione 
            `b`@24 ore dopo l'invio della mail`b`#.`n`n`n");
    output("`%`b5`b) `#Sia chiaro che `b`@ogni reiterazione`b`# della violazione del regolamento comporter� un `b`@aumento 
            della pena inflitta`b`#.`n`n`n`n`n");

    output("<big>`b`c`^H) REGOLE MINORI DI GIOCO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#� lasciata ad alcuni personaggi con posizioni di rilievo nel gioco la possibilit� di decretare 
            ulteriori regole. A tali personaggi vengono altres� forniti strumenti per punire gli eventuali trasgressori.`n`n");
    output("`#- `b`@Salvo casi realmente eccezionali`b`#, l'osservanza o l'infrazione di tali regole non saranno mai 
            di interesse per lo staff, che `b`@non`b`# prender� alcun provvedimento a riguardo.`n`n`n`n`n");

    output("<big>`b`c`^I) COMUNICAZIONE CON LO STAFF`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#L'`b`@unico`b`# modo per comunicare con lo staff � rappresentato dal sistema di `b`@petizioni`b`#. Per 
            aprire una petizione, cliccate sul link `b`@Richiesta d'aiuto`b`# in alto a sinistra. `b`@Tale link � accessibile 
            anche prima di effettuare il login`b`#.`n`n");
    output("`#- Ricordate che una petizione viene notificata all'intero staff, permettendovi di ottenere una risposta 
            nel pi� breve tempo possibile. I messaggi privati, d'altro canto, vengono letti solo da chi li riceve, 
            allungando i tempi di risposta e creando confusione nella comunicazione tra staff e utenti.`n`n");
    output("`#- I membri dello staff si riservano il diritto di `b`@non rispondere`b`# a semplici messaggi privati ricevuti 
            dai giocatori. Utenti recidivi (che inviino svariati messaggi privati a singoli membri dello staff anzich� 
            aprire petizioni) `b`@potranno essere puniti`b`#.`n`n`n");
    output("`%`b2`b) `#Quando inviate una petizione, sceglietene con cura la `b`@categoria`b`#. In tal modo 
            velocizzate l'intervento dei membri dello staff deputati alla risoluzione del problema.`n`n");
    output("`#- Tenete presente che lo staff � fatto di persone con una propria vita privata: qualora la risposta al vostro 
            quesito dovesse tardare ad arrivare, pazientate almeno `b`@24 ore`b`# prima di inviare una nuova petizione.`n`n`n");
    output("`%`b3`b) `#� consigliato agli utenti, `b`@quando sia possibile`b`#, di scrivere le petizioni `b`@dopo aver 
            effettuato il login con il personaggio cui la petizione si riferisce`b`#. Ci� consente di minimizzare 
            i tempi di intervento.`n`n`n");
    output("`%`b4`b) `#Prima di aprire petizioni per dubbi riguardanti le meccaniche di gioco, prendetevi un po' di 
            tempo per leggere le varie `b`@FAQ`b`# raggiungibili sia dalla pagina di login che dalla `b`@sezione help`b`#.`n 
            � inoltre caldamente consigliata una visita al `b`@druido`b`# che dimora nel monastero in foresta.`n`n");
    output("`#- Lo staff si riserva il diritto di `b`@non`b`# rispondere a dubbi inerenti argomenti gi� trattati nelle 
            succitate sezioni.`n`n");
    output("`#- Lo staff si impegna a `b`@non`b`# divulgare esplicitamente segreti e tattiche di gioco. Per conoscere tali 
            informazioni dovete studiare personalmente il gioco in tutte le sue sfaccettature.`n`n`n");
    output("`%`b5`b) `#Segnalazioni non veritiere o inviate con il semplice intento di spammare verranno sanzionate.`n`n`n");
    output("`%`b6`b) `#Atteggiamenti in gioco volti unicamente a screditare pubblicamente l'operato dello staff verranno 
            duramente sanzionati.`n`n`n`n`n");

    output("<big>`b`c`^J) CASI NON PREVISTI DAL REGOLAMENTO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Nel caso in cui lo staff si trovi ad affrontare un evento non descritto in queste righe, si 
           impegna a:`n`n");
    output("`#- Discutere privatamente con il diretto interessato per comunicargli che la sua azione � potenzialmente 
            sanzionabile.`n`n");
    output("`#- Modificare immediatamente il regolamento per rendere tale azione sanzionabile per il futuro.`n`n`n");
    output("`%`b2`b) `#In casi gravi la modifica al regolamento potrebbe risultare `b`@retroattiva`b`#, portando quindi 
            alla punizione dell'utente che abbia creato col suo comportamento la necessit� della modifica stessa.`n`n");

// fine regolamento

    output("<form action='termininewplayer.php' method='POST'><input type='submit' class='button' value='Leggi i Termini di Utilizzo'>`n",true);
    addnav("","termininewplayer.php");
page_footer();
?>