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
    addnav("");
    addnav("Regolamento sul Forum","http://logd.forumfree.it/?t=70645371",false,false,false,true);
    addnav("Guida al Role (Forum)","http://logd.forumfree.it/?t=70600636",false,false,false,true);
        }
    } else {
    }
}else{
    addnav("Torna al Login","login.php");
    addnav("Regolamento sul Forum","http://logd.forumfree.it/?t=70645371",false,false,false,true);
    addnav("Guida al Role (Forum)","http://logd.forumfree.it/?t=70600636",false,false,false,true);
}
page_header("REGOLAMENTO");
//if ($_GET['op']==""){
    output("<big>`n`b`c`&REGOLAMENTO DI LEGEND OF THE GREEN DRAGON`0`c`b</big>`n`n`n",true);

    output("`b`\$IMPORTANTE: LEGGERE E CONOSCERE IL REGOLAMENTO È OBBLIGATORIO`b`#.`n`n");
    output("`#Qualsiasi infrazione accompagnata dalla scusa \"io non lo sapevo\" verrà punita con il `b`\$TRIPLO`b`# 
            della pena.`n`n`n`n`n");

    output("<big>`b`c`^A) SVILUPPO GIOCO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `b`@Legend of the Green Dragon`b`# (`b`@LoGD`b`#) è costantemente in `u`&fase beta`u`#: ciò significa 
            che `b`@ogni`b`# suo aspetto può andare incontro in qualsiasi momento ad improvvise e ripetute modifiche 
            allorché lo staff lo ritenga necessario. Alcune di queste modifiche verranno notificate ai 
            giocatori tramite i `b`@Messaggi del Giorno`b`# (`b`@MoTD`b`#), altre saranno introdotte silentemente.`n`n");
    output("`#- Registrandosi al gioco, gli utenti dichiarano implicitamente di accettare ogni eventuale futura 
            modifica di qualsivoglia natura.`n`n");
    output("`#- Dichiarano altresì di accettare che qualsiasi tipo di danno ad un personaggio dovuto a problemi 
            o bug del gioco non verrà risarcito, salvo situazioni di natura realmente eccezionale che verranno 
            in ogni caso discussi privatamente dallo staff, il cui giudizio resta sempre `b`@insindacabile`b`#.`n");
    output("`#- Eventuali discussioni riguardanti le modifiche apportate al gioco devono essere portate avanti 
            nel forum, `b`@mai`b`# nelle stanze di gioco.`n`n`n");
    output("`%`b2`b) `#È fatta esplicita richiesta a tutti i giocatori di `b`@segnalare immediatamente`b`# qualsiasi 
            `ubug`u venga riscontrato.`n`n");
    output("`#- La segnalazione deve essere inviata allo staff `b`@esclusivamente attraverso il sistema di petizioni`b`#, 
            e deve riportare tutte le informazioni e i dettagli possibili, per permettere allo staff di identificare 
            quanto prima la natura del problema.`n`n`n");
    output("`%`b3`b) `#Va da sé che è `b`@TASSATIVAMENTE VIETATO`b`# sfruttare bug e debolezze del sistema per avvantaggiarsi 
              in gioco.`n`n");
    output("`#- Lo sfruttamento di un bug comporta il `b`@BAN`b`# immediato del personaggio, di durata proporzionale alla 
            gravità della situazione. In caso di gravi violazioni il provvedimento può essere allargato a `b`@TUTTI`b`# 
            i personaggi di un utente, e la sua durata può essere estesa `b`@indefinitamente`b`#.`n`n`n`n`n");

    output("<big>`b`c`^B) MULTIACCOUNT`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#È permesso creare e gestire fino a `b`@SEI personaggi`b`# per ogni connessione.`n`n");
    output("`#- Se due o più persone che giocano a LoGD risiedono nella stessa abitazione, `b`@devono dunque spartirsi 
            i sei personaggi disponibili`b`# come meglio credono.`nGli utenti che ignoreranno il limite di sei 
            personaggi verranno puniti con un `b`@BAN`b`# non inferiore a `b`@cinque giorni`b`# ed esteso a `b`@tutti`b`# 
            i loro personaggi.`nI personaggi in eccesso andranno inoltre incontro ad `b`@immediata cancellazione`b`#, 
            partendo da quelli con il numero più basso di DK e reincarnazioni.`nIn tale circostanza, agli utenti puniti è 
            `b`@tassativamente vietato`b`# creare nuovi personaggi, pena l'immediata cancellazione di questi ultimi ed 
            il `b`@raddoppio`b`# della durata del provvedimento.`n`n`n");
    output("`%`b2`b) `#I personaggi appartenenti ad uno stesso giocatore devono `b`@necessariamente`b`# essere registrati 
            tramite lo stesso indirizzo e-mail.`n`n`n");
    output("`%`b3`b) `#È `b`@assolutamente vietata`b`# qualsiasi forma di `b`@interazione`b`# fra personaggi 
            appartenenti allo stesso giocatore.`n`n");
    output("`#- Col termine “interazione” si intendono passaggi - diretti o indiretti, cioè utilizzando l'aiuto 
            di terzi che fungano da tramite - di denaro, gemme, oggetti o territori tra i personaggi, nonché 
            il matrimonio, il tutoraggio, gli attacchi reciproci o destinati ad uno stesso bersaglio, e qualsiasi 
            altra azione che possa collegare in qualche modo i personaggi.`n`n");
    output("`#- È generalmente concesso ai personaggi di uno stesso giocatore di convivere nella stessa tenuta, 
            purché non si configurino le violazioni di cui sopra. Lo staff si riserva il diritto di chiedere - 
            ed ottenere senza alcun indugio, pena l'uso della forza - l'allontanamento di uno o più personaggi 
            da una specifica tenuta per evitare spiacevoli inconvenienti.`n`n`n");
    output("`%`b4`b) `#In `b`@nessun caso`b`# è consentito `b`@affidare temporaneamente`b`# i propri personaggi ad 
            altri giocatori.`n`n");
    output("`#- È fatto `b`@assoluto divieto`b`# a tutti i giocatori di utilizzare personaggi appartenenti ad altri, 
            con o senza l'autorizzazione dei proprietari.`n Gli utenti che non rispetteranno questa regola riceveranno 
            un `b`@BAN immediato`b`# ed esteso a `b`@tutti i personaggi appartenenti a ciascuno dei giocatori coinvolti nella 
            violazione del regolamento`b`#.`n La durata del ban sarà proporzionale all'entità dell'infrazione.`n`n`n");
    output("`%`b5`b) `#Nel momento in cui due o più giocatori si trovino a vivere insieme, o comunque a condividere a tempo indeterminato 
            dispositivi e/o connessione (ad esempio perché familiari), devono richiedere allo staff l'`b`@autorizzazione`b`# alla creazione 
            dei loro personaggi, che non potranno eccedere il numero totale di `b`@SEI`b`#.`n`n");
    output("`#- Tale autorizzazione deve essere richiesta `b`@individualmente`b`# - `b`@ogni giocatore`b`# interessato deve 
            dunque attivarsi e `b`@fare richiesta per conto proprio`b`# - attraverso il forum del gioco, scrivendo nella 
            apposita <a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>sezione petizioni</font></b></a>.`n`n",true);
    output("`#- È `b`@assolutamente vietato`b`# procedere alla creazione dei personaggi prima che `b`@tutti i giocatori`b`# 
            interessati abbiano ricevuto l'`b`@autorizzazione dello staff`b`#.`n`n");
    output("`#- I personaggi appartenenti a giocatori residenti nello stesso luogo devono `b`@tassativamente`b`# evitare 
            `b`@qualsiasi tipo`b`# di interazione, come già spiegato al punto `b`@3`b`# di questa sezione. I trasgressori 
            verranno puniti con un `b`@BAN`b`# esteso a `b`@tutti i personaggi`b`#, di durata proporzionale all'entità 
            dell'infrazione.`n`n`n");
    output("`%`b6`b) `#Allorché due o più giocatori `b`@che eseguono normalmente il login da postazioni differenti`b`# si trovino obbligati a giocare 
            `b`@dallo stesso luogo o dispositivo`b`# (ad esempio nel caso di una breve visita dell'uno a casa dell'altro), essi devono 
            `b`@avvisare preventivamente lo staff`b`# ed attenderne l'`b`@autorizzazione`b`#.`n`n");
    output("`#- Come nel caso analizzato al punto `b`@5`b`# di questa sezione, tale `b`@autorizzazione`b`# deve essere 
            `b`@richiesta individualmente`b`# - `b`@ogni giocatore`b`# interessato deve dunque attivarsi e fare `b`@richiesta 
            per conto proprio`b`# - attraverso il forum del gioco, scrivendo nella apposita 
            <a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>sezione petizioni</font></b></a>.`n`n",true);
    output("`#- È `b`@assolutamente vietato`b`# effettuare il `b`@login dalla medesima postazione`b`# prima che `b`@tutti 
            i giocatori`b`# interessati abbiano ricevuto l'autorizzazione dello staff.`n`n");
    output("`#- Anche in questo caso i personaggi appartenenti a giocatori diversi devono `b`@tassativamente`b`# 
            evitare `b`@qualsiasi tipo`b`# di interazione, come già spiegato al punto `b`@3`b`# di questa sezione. I trasgressori 
            verranno puniti con un `b`@BAN`b`# esteso a `b`@tutti i personaggi`b`#, di durata proporzionale all'entità 
            dell'infrazione.`n`n");
    output("`#- I giocatori possono fare richiesta di multiaccount temporaneo `b`@non più di una volta ogni trenta giorni`b`#. 
            Lo staff si riserva il diritto di non accettare richieste eccessivamente frequenti, ripetute nel tempo o comunque `b`@ritenute sospette`b`#.`n`n`n");
    output("`%`b7`b) `#Qualora un giocatore `b`@già in possesso di sei personaggi`b`# decida di abbandonarne uno, prima della 
            eventuale creazione di un nuovo personaggio deve `b`@attendere l'autocancellazione per inattività`b`# di quello 
            abbandonato.`n`n");
    output("`#- La creazione di un nuovo personaggio prima della cancellazione del precedente si configura come violazione della 
            regola numero `b`@1`b`# di questa sezione, con tutte le conseguenze del caso.`n`n`n");
    output("`%`b8`b) `#È consentito ai giocatori abbandonare i propri personaggi `b`@cedendoli definitivamente`b`# 
            ad altri utenti.`n`n");
    output("`#- Un giocatore può entrare in possesso di un nuovo personaggio `b`@solo nel caso`b`# in cui non ne 
            possieda già altri sei.`n`n");
    output("`#- Il `b`@cambio di proprietario`b`# deve essere sancito dal `b`@cambio dell'indirizzo e-mail`b`# associato 
            al personaggio.`n`n");
    output("`#- L'`b`@autorizzazione alla cessione`b`# deve essere `b`@richiesta individualmente`b`# - ogni giocatore 
            interessato deve dunque attivarsi e fare richiesta per conto proprio - attraverso il forum del gioco, scrivendo 
            nella apposita <a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>sezione petizioni</font></b></a>.`n`n",true);
    output("`#- Con la cessione del personaggio, il proprietario originale `b`@rinuncia ad ogni diritto`b`# su 
            quel personaggio.`n`n`n`n`n");

    output("<big>`b`c`^C) PRATICHE SCORRETTE`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#È `b`@severamente proibito`b`# l'uso di qualsivoglia programma in grado di generare script 
            automatici o semiautomatici che possano interagire con il gioco o il database.`n`n");
    output("`#- Gli utenti scoperti a fare uso di simili stratagemmi saranno passibili di `b`@BAN`b`# immediato, di durata 
            variabile e potenzialmente estensibile a `b`@tutti`b`# i personaggi, al pari di quanto avviene per lo 
            sfruttamento di bug.`n`n`n");
    output("`%`b2`b) `#È altresì `b`@vietato`b`# effettuare il `b`@login`b`# avvalendosi dell'uso di `b`@proxy`b`#.`n`n`n");
    output("`%`b3`b) `#In `b`@nessun caso`b`# è consentito `i`u`&rushare`u`i`# un personaggio utilizzandone un altro. Col termine 
            `i`u`&rushare`u`i`# si intende l'atto del favorire un personaggio consentendogli di crescere a velocità più 
            elevata del normale. Le pratiche vietate comprendono:`n`n");
    output("`#- Il passaggio di grandi quantità di oro tra due o più personaggi. Tale passaggio è vietato `b`@sia`b`# 
            attraverso le tenute, `b`@sia`b`# tramite qualsiasi altra via. È comunque generalmente permesso aiutare un 
            personaggio nuovo o in difficoltà regalandogli dell'oro, purché ciò avvenga `b`@entro i limiti del buon senso`b`#.`n`n");
    output("`#- Il passaggio di gemme tra due o più personaggi. Tenete presente che c'è un preciso motivo se tale 
            possibilità `b`@non è contemplata`b`# all'interno delle tenute. Evitate quindi di regalare le gemme dei 
            vostri personaggi a quelli appartenenti ad altri giocatori.`n`n");
    output("`#- In caso di violazione del regolamento, `b`@tutti`b`# i personaggi coinvolti verranno puniti con un `b`@BAN`b`# di 
            durata proporzionale alla gravità dell'infrazione. Inoltre, `b`@tutti`b`# gli averi indebitamente ottenuti dai 
            personaggi verranno immediatamente `b`@sottratti`b`#, insieme a oggetti, punti statistiche o quant'altro sia 
            stato già acquistato con tali averi.`n`n");
    output("`%`b4`b) `# È `b`@tassativamente vietata`b`# la vendita di oggetti, oro, gemme, territori, personaggi o quant'altro 
            in cambio di denaro reale. Chi verrà scoperto a proporre o accettare simili accordi verrà punito con un 
            `b`@BAN immediato a vita`b`#.`n`n`n`n`n");

    output("<big>`b`c`^D) NOMI DEI PERSONAGGI`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Al momento della creazione di un nuovo personaggio, `b`@bisogna attendere`b`# che lo staff vagli 
            e `b`@approvi il nome`b`# scelto dal giocatore. Esso:`n`n");
    output("`#- `b`@deve`b`# essere attinente al mondo fantasy`n`n");
    output("`#- `b`@non deve`b`# essere scritto in maiuscolo (es. `&BILBOBAGGINS`#)`n`n");
    output("`#- `b`@non deve`b`# essere scritto con alternanza di caratteri maiuscoli e minuscoli (es. `&BiLbObAgGiNs`#)`n`n");
    output("`#- `b`@può`b`# contenere maiuscole laddove si voglia evidenziare un eventuale cognome (es. `&BilboBaggins`#)`n`n");
    output("`#- `b`@non deve`b`# contenere titoli nobiliari (es. `&Re_Lear`#) né epiteti (es. `&Jack_the_Ranger`#, `&The_King`#)`n`n");
    output("`#- `b`@non deve`b`# contenere termini offensivi e/o volgari, siano essi palesi o sottintesi, in nessuna 
            lingua o dialetto`n`n`n");
    output("`%`b2`b) `#Se la vostra fantasia viene meno e non riuscite a pensare ad un bel nome per il vostro personaggio, 
            potete aiutarvi utilizzando un generatore di nomi fantasy. Se ne trovano molti in rete, un esempio è
            <a href=\"http://www.wizards.com/dnd/article5.asp?x=dnd/dx20010202b,0\" target=\"_blank\">
            <b><font color='#DDFFBB'>QUESTO</font></b></a>.`n`n`n",true);
    output("`%`b3`b) `#Lo staff si riserva il diritto di `b`@cancellare immediatamente`b`# personaggi i cui nomi non 
            rispondano ai criteri sopra elencati.`n`n`n");
    output("`%`b4`b) `#Scegliete `b`@con molta attenzione`b`# i nomi dei vostri personaggi, poiché `b`@non sarà possibile 
            cambiarli`b`# al termine della registrazione.`n`n");
    output("`#- In `b`@casi particolari`b`#, e solo per `b`@motivazioni particolari`b`#, lo staff potrebbe accettare di cambiare 
            il nome di un personaggio `b`@che non abbia ancora ucciso il Drago Verde`b`#, e che sia quindi ancora un 
            `b`@contadino`b`#.`n`n`n`n`n");

    output("<big>`b`c`^E) STANZE DI GIOCO E MESSAGGI PRIVATI`0`c`b</big>`n`n",true);
    output("`%`b1`b) `b`@LoGD è un gioco di ruolo (GdR) testuale`b`#: gli utenti hanno dunque la possibilità di 
            intervenire nelle stanze di gioco descrivendo azioni, pensieri e parole dei propri personaggi, 
            interagendo con quelli degli altri giocatori.`nMolte stanze sono completamente pubbliche, 
            alcune sono invece accessibili solo a cerchie più o meno ristrette di personaggi.`n`n");
    output("`#- In `b`@tutte`b`# le stanze è `b`@assolutamente vietato`b`# l'uso di termini volgari e/o osceni, Sono 
            altresì vietati riferimenti espliciti a sessualità e/o estrema brutalità. Ricordate che in gioco 
            potrebbero essere presenti dei minori. Chi contravverrà a questa regola verrà `b`@immediatamente punito`b`# 
            con un provvedimento di natura e durata proporzionali alla gravità dell'infrazione.`n`b`@Eventuali 
            riferimenti a tematiche illegali (es. pedopornografia, spaccio di droga, ecc.) verranno immediatamente 
            riportati alle autorità competenti`b`#.`n`n");
    output("`#- Non è ammesso l'uso di bestemmie, siano esse esplicite o velate. Eventuali esclamazioni che 
            richiamino chiaramente – pur se indirettamente - delle bestemmie sono altrettanto poco gradite. 
            Esiste `b`@sempre`b`# la possibilità che qualcuno possa risentirsi per simili modi di dire, quindi evitate, 
            cortesemente. Esistono tante altre possibili esclamazioni che potete usare.`n`n");
    output("`#- Un sistema automatico di censura provvede ad oscurare un gran numero di parole non ammesse: `b`@è 
            fatto divieto`b`# di tentare di aggirare in qualsiasi modo tale sistema, pena il `b`@silenziamento immediato`b`# 
            di `b`@tutti`b`# i personaggi dei giocatori che contravvengano alla regola, di durata proporzionale alla gravità 
            dell'infrazione.`n`n`n");
    output("`%`b2`b) `#Le stanze pubbliche sono dedicate al gioco di ruolo: è `b`@vietato`b`# parlare di argomenti inerenti alla 
            vita reale. Contravvenire a tale regola causerà il `b`@silenziamento immediato`b`# di `b`@tutti`b`# i personaggi 
            del giocatore.`n`n");
    output("`#- Le `b`@tenute`b`#, in quanto stanze private, sono le `b`@uniche`b`# in cui è concesso parlare di argomenti 
            non inerenti al gioco.`nAnche nelle `b`@sale di ritrovo `i`uprivate`u`i delle sette`b`# tale pratica è tollerata, 
            purché non si esageri.`n`n");
    output("`#- La `b`@sezione help`b`#, accessibile dal link in alto a sinistra nella piazza del villaggio, è l'`b`@unica 
            stanza`b`# in cui sia concesso porre domande inerenti al gioco. Per `b`@evitare possibili spoiler`b`# a ignari 
            giocatori, lo staff `b`@consiglia caldamente`b`# ai più esperti di rispondere alle domande facendo uso dei 
            messaggi privati. Tale pratica diventa `b`@obbligatoria`b`# nel caso in cui le informazioni riguardino segreti 
            e tattiche di gioco, ed il mancato adempimento potrebbe essere punito.`n`n");
    output("`#- In `b`@NESSUNA`b`# stanza è consentito discutere dei provvedimenti di gioco, delle modifiche e delle punizioni 
            decise dallo staff. Il mancato adempimento porterà ad un `b`@silenziamento`b`# di `b`@tutti`b`# i personaggi 
            del giocatore, della durata minima di `b`@trenta newday`b`#.`n`n`n");
    output("`%`b3`b) `#È `b`@tassativamente vietato`b`# insultare gli altri giocatori. La pena per una simile infrazione sarà 
            un `b`@BAN immediato`b`# della durata di `b`@almeno`b`# una settimana, con possibilità di estensione del 
            provvedimento `b`@a vita`b`#.`n`n");
    output("`#- Sono accettati insulti da personaggio a personaggio nell'ambito del gioco di ruolo e nei limiti del 
            buon gusto. Lo staff si riserva il diritto di discutere privatamente caso per caso e decidere `b`@a proprio 
            insindacabile giudizio`b`# in quali situazioni un insulto sia o meno reale.`n`n");
    output("`#- Qualora un giocatore si senta insultato da qualcuno, deve riferirlo `b`@immediatamente`b`# allo staff facendo 
            uso del sistema di `b`@petizioni`b`# e riportando tutti i dettagli utili al caso, compresa una spiegazione 
            esauriente del motivo secondo cui l'insulto viene percepito come reale e non fittizio.`n`n");
    output("`#- `b`@Solo il giocatore che si ritiene insultato`b`# ha il permesso di segnalare l'accaduto allo staff: è 
            `b`@vietato`b`# a terzi, salvo casi realmente palesi, l'invio di petizioni riguardanti presunte offese ricevute 
            da chicchessia. Questo per evitare che lo staff venga sommerso di segnalazioni ogni volta che qualcuno 
            definisce scemino qualcun altro. Lasciate decidere `b`@al diretto interessato`b`# se l'offesa lo tocca personalmente 
            o meno. Segnalazioni da parte di utenti non coinvolti in prima persona potranno essere punite.`n`n`n");
    output("`%`b4`b) `#In caso di segnalazione di presunti insulti inviati tramite messaggi privati, lo staff si 
            riserva il diritto di esaminare le caselle di posta di `b`@tutti`b`# i personaggi di `b`@tutti`b`# i giocatori 
            coinvolti ed eventualmente punire chi abbia lanciato le offese. Si consiglia dunque a chi dovesse ricevere ingiurie 
            di conservare i messaggi in qualità di prova e contattare `b`@immediatamente`b`# lo staff tramite il sistema di 
            `b`@petizioni`b`#.`n`n");
    output("`#- I messaggi privati potranno essere esaminati anche ogniqualvolta lo staff dovesse ricevere segnalazioni 
            riguardanti un uso errato di tale strumento, ad esempio nel caso in cui un giocatore lo sfruttasse per 
            trattare questioni illegali o profondamente immorali, per screditare lo staff, per discutere su come 
            violare il regolamento senza essere scoperto o per istigare qualcun altro a contravvenire ad esso. Le 
            punizioni ricalcheranno eventualmente quelle già indicate nei rispettivi paragrafi del regolamento stesso.`n`n`n");
    output("`%`b5`b) `#Il sistema dei messaggi privati mette a disposizione dei giocatori una lista in cui inserire 
            gli utenti da ignorare. Quando il personaggio `b`@A`b`# inserisce il personaggio `b`@B`b`# nella lista degli 
            ignorati, il personaggio `b`@B`b`# non può più inviare messaggi privati al personaggio `b`@A`b`#.`n`n");
    output("`#- È `b`@vietato`b`# agli utenti aggirare `b`@in qualsiasi modo`b`# questo sistema. Se qualcuno non vuole 
            ricevere un vostro messaggio, non deve riceverlo. `b`@Punto`b`#. La punizione per chi dovesse aggirare il sistema 
            consisterà in un `b`@BAN`b`# di durata variabile a seconda della situazione.`n`n");
    output("`#- Chi dovesse ricevere per vie traverse messaggi da utenti bloccati è pregato di `b`@non cancellare`b`# tali 
            messaggi e di informare `b`@immediatamente`b`# lo staff attraverso l'invio di una `b`@petizione`b`#.`n`n`n");
    output("`%`b6`b) `#In tutte le stanze di gioco è `b`@vietato`b`# l'utilizzo di abbreviazioni in stile SMS. Toglietevi quindi 
            dalla testa i vari `i`&xké`i`#, `i`&cmq`i`#, `i`&tvb`i`#. La violazione di questa regola porterà al 
            `b`@silenziamento immediato`b`# di `b`@tutti`b`# i personaggi del giocatore.`n`n");
    output("`#- Nella `b`@maggior parte`b`# delle stanze è `b`@vietato`b`# l'uso di smiley, faccine, emoticon o comunque le si 
            voglia chiamare.`nLo staff si mostrerà più tollerante nei confronti di simili pratiche `b`@solo`b`# nelle seguenti 
            stanze: `i`u`&la piazza`u`i`#, `i`u`&il municipio`u`i`#, `i`u`&le tenute`u`i`# e `i`u`&le sale comuni delle sette`u`i`#. 
            In tali ambienti è consentito fare uso di simboli, purché ciò avvenga `b`@entro i limiti del buon senso`b`# ed 
            evitando di spammare inserendone troppi tutti insieme.`nL'uso di qualunque simbolo in tutte le altre stanze verrà 
            punito con il `b`@silenziamento immediato`b`# di `b`@tutti`b`# i personaggi del giocatore.`n`n");
    output("`#- In tutte le stanze è consentito l'uso dei colori, `b`@entro i limiti del buon senso`b`# ed evitando di 
            creare inutili arcobaleni rendendo il testo illeggibile.`n`n");
    output("`#- È `b`@vietato`b`# l'uso di lingue straniere nelle stanze di gioco, se non per esclamazioni o frasi molto brevi 
            e senza abusarne. Lo scopo del gioco è `b`@comunicare`b`# con `b`@tutti`b`# gli altri giocatori, `b`@non`b`# 
            rendere le proprie parole intellegibili solo per alcuni. Inoltre, un moderatore che dovesse trovarsi di fronte 
            ad una lingua estranea potrebbe trovare difficoltà nello svolgimento dei suoi compiti.`nSe volete citare un 
            testo straniero, citatene `b`@la traduzione`b`#.`n`n");
    output("`#- È `b`@caldamente sconsigliato`b`# l'uso di espressioni eccessivamente gergali, dialettali o comunque legate 
            al mondo moderno. Lo staff si riserva il diritto di intervenire cancellando tali espressioni laddove lo ritenga 
            necessario.`n`n");
    output("`#- È altresì `b`@sconsigliato`b`# l'uso di terminologie moderne (es. `i`&radio`i`#, `i`&computer`i`#, `i`&shuttle`i`#, 
            ecc.), che risultino dunque anacronistiche rispetto all'ambientazione del gioco.`nLo staff si riserva comunque 
            la possibilità di essere più o meno tollerante nei confronti di tali termini a seconda dell'atmosfera 
            di gioco, che verrà valutata di volta in volta.`nGradiremmo evitare di essere inondati di petizioni 
            riguardanti l'utilizzo di occhiali, pop corn, farmaci, oggetti tecnologici e quant'altro, fintantoché 
            tali concetti vengano espressi nei limiti della decenza ed in situazioni rilassate.`n`n`n`n`n");

    output("<big>`b`c`^F) ACCANIMENTO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Il gioco mette a disposizione degli utenti la possibilità di scontrarsi fra loro in svariati 
            modi. Gli scontri fra gli utenti hanno scopo `b`@ludico`b`# e non devono `b`@mai`b`# sfociare nell'impossibilità 
            per l'uno o l'altro contendente di avanzare nel gioco a causa della contesa stessa.`n`n`n");
    output("`%`b2`b) `#In caso di prolungata e continua ostilità da parte di un personaggio nei confronti di un altro, 
            lo staff potrebbe decidere di imporre una tregua forzata tra i due.`n`n");
    output("`#- Tale decisione verrà presa solo in casi di `b`@grave`b`# accanimento, soprattutto - `b`@ma non necessariamente`b`# 
            - se il personaggio che subisce gli attacchi (qualsiasi sia la natura di tali attacchi) si trovasse 
            a causa di questi ultimi nell'impossibilità di avanzare nel gioco in modo regolare.`n`n");
    output("`#- Lo staff analizzerà `b`@caso per caso`b`# ogni presunto accanimento, solo `b`@previo segnalazione`b`# tramite 
            `b`@petizione`b`#, e provvederà a prendere una decisione ed a comunicarla ai diretti interessati nel più breve 
            tempo possibile.`n`n");
    output("`#- Come già specificato nel caso delle offese tra giocatori, `b`@solo colui che si ritiene vittima di 
            accanimento`b`# ha il permesso di segnalare l'accaduto allo staff: è `b`@assolutamente vietato`b`# a terzi l'invio 
            di petizioni riguardanti presunti accanimenti nei confronti di chicchessia. Lasciate decidere al diretto 
            interessato se gli attacchi subiti sono sopportabili o meno. Segnalazioni da parte di utenti non coinvolti 
            in prima persona potranno essere punite.`n`n");
    output("`#- Condizione necessaria alla stipula di una tregua forzata è che gli attacchi siano `b`@unidirezionali`b`#: 
            il personaggio attaccato non deve dunque rispondere alle angherie subite, né con la forza, né con eventuali 
            provocazioni esplicite in pubblico.`n`n");
    output("`#- La tregua forzata protegge un personaggio dagli attacchi di `b`@un`b`# altro personaggio, `b`@non`b`# da quelli 
            di `b`@tutti`b`# i personaggi presenti in città.`n`n`n");
    output("`%`b3`b) `#La violazione della tregua forzata comporterà il `b`@BAN`b`# del personaggio della durata `b`@non 
            inferiore`b`# a `b`@tre`b`# giorni.`n`n");
    output("`#- L'uso di più personaggi per attaccare un altro giocatore si configurerà `b`@sia`b`# come reato di multiaccount, 
            `b`@sia`b`# come reato di accanimento, portando ad un `b`@BAN`b`# immediato della durata `b`@non inferiore`b`# 
            ad `b`@una settimana`b`#.`n`n");
    output("`#- Va da sé che è `b`@vietato`b`# creare nuovi personaggi al solo scopo di danneggiare un altro giocatore. Tale 
            pratica verrà punita con la `b`@cancellazione immediata`b`# del nuovo personaggio e con un `b`@BAN`b`# di un giorno 
            esteso a `b`@tutti`b`# i personaggi del colpevole.`n`n`n`n`n");

    output("<big>`b`c`^G) TIPOLOGIE DI PROVVEDIMENTI`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Tra i provvedimenti che lo staff può prendere nei confronti dei giocatori sono compresi i seguenti:`n`n");
    output("`#- `b`^Avvertimento`b`#: Avviso privato al giocatore atto a spiegare nei dettagli quando e come egli abbia 
            contravvenuto al regolamento.`n`b`@Avvertimenti reiterati porteranno automaticamente a provvedimenti di natura 
            più seria`b`#.`n`n");
    output("`#- `b`^Silenziamento`b`# (`&Mute`#): Revoca del permesso di scrivere nelle stanze pubbliche. Può avere durata 
            variabile ed interessa `b`@tutti i personaggi di uno stesso giocatore`b`#.`n`n");
    output("`#- `b`^Riduzione delle statistiche`b`#: Modifica delle caratteristiche di un personaggio – quali attacco, 
            difesa, HP o altro - e/o dei suoi averi, ivi compresi oro, gemme, animali, oggetti, tenute, fattorie, 
            eccetera.`n`n");
    output("`#- `b`^Esclusione dal gioco`b`# (`&Ban`#): Revoca dell'autorizzazione ad effettuare il login per un numero 
            variabile di giorni. Si tratta di un provvedimento molto serio, inflitto in casi di gravi violazioni del 
            regolamento. Può interessare `b`@uno o più personaggi`b`# di un giocatore, a seconda della gravità dell'infrazione. 
            In situazioni eccezionali la sua durata può essere estesa `b`@indefinitamente`b`#.`n`n");
    output("`#- `b`^Cancellazione immediata di un personaggio`b`#: Normalmente utilizzata per eliminare dal gioco nuovi 
            personaggi creati con intenti malevoli.`n`n");
    output("`#- Lo staff si riserva il diritto di comminare provvedimenti non presenti in questa lista qualora lo ritenga 
            opportuno, a sua totale discrezione.`n`n`n");
    output("`%`b2`b) `#Il possesso di un qualsiasi ammontare di punti donazione, siano essi dieci o un milione, 
            `b`@non`b`# influirà `b`@mai`b`# sulle decisioni prese dallo staff in tema di provvedimenti. Sia chiaro, a 
            tal proposito, che `b`@un personaggio bannato a vita perde automaticamente la possibilità di far fruttare i 
            punti donazione acquisiti`b`#.`n`n`n");
    output("`%`b3`b) `#I provvedimenti `b`@non sono contestabili pubblicamente`b`#, né in gioco né nel forum. Per richiedere 
            spiegazioni e/o tentare di chiarire la propria posizione gli utenti devono fare uso del sistema di `b`@petizioni`b`# 
            o dell'<a href=\"http://logd.forumfree.it/?f=64522527\" target=\"_blank\">
            <b><font color='#DDFFBB'>apposita sezione</font></b></a> del forum, attraverso cui possono discutere 
            privatamente con i membri dello staff.`n`n",true);
    output("`#- I provvedimenti presi verranno discussi `b`@solo`b`# con i diretti interessati. Lo staff non ha alcun 
            obbligo, né interesse, nello spiegare la propria posizione a giocatori non coinvolti in prima persona.`n`n`n");
    output("`%`b4`b) `#In alcuni casi lo staff potrebbe decidere di richiedere ad un giocatore di chiarire la propria 
            posizione prima di infliggere una punizione.`n`n");
    output("`#- In questi casi, lo staff invierà un `b`@messaggio privato`b`# al personaggio coinvolto nella violazione del 
            regolamento. Il giocatore avrà a disposizione `b`@24 ore per rispondere`b`#.`n`n");
    output("`#- In caso di `b`@login`b`# del personaggio e `b`@mancata risposta`b`# al messaggio privato, `b`@la punizione verrà 
            inflitta 24 ore dopo l'invio di quest'ultimo`b`#.`n`n");
    output("`#- In caso di `b`@mancato login`b`# del personaggio nelle 24 ore successive all'invio del messaggio privato, verrà 
            inviato un `b`@avviso tramite e-mail`b`#. La mancata risposta porterà `b`@automaticamente`b`# alla punizione 
            `b`@24 ore dopo l'invio della mail`b`#.`n`n`n");
    output("`%`b5`b) `#Sia chiaro che `b`@ogni reiterazione`b`# della violazione del regolamento comporterà un `b`@aumento 
            della pena inflitta`b`#.`n`n`n`n`n");

    output("<big>`b`c`^H) REGOLE MINORI DI GIOCO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#È lasciata ad alcuni personaggi con posizioni di rilievo nel gioco la possibilità di decretare 
            ulteriori regole. A tali personaggi vengono altresì forniti strumenti per punire gli eventuali trasgressori.`n`n");
    output("`#- `b`@Salvo casi realmente eccezionali`b`#, l'osservanza o l'infrazione di tali regole non saranno mai 
            di interesse per lo staff, che `b`@non`b`# prenderà alcun provvedimento a riguardo.`n`n`n`n`n");

    output("<big>`b`c`^I) COMUNICAZIONE CON LO STAFF`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#L'`b`@unico`b`# modo per comunicare con lo staff è rappresentato dal sistema di `b`@petizioni`b`#. Per 
            aprire una petizione, cliccate sul link `b`@Richiesta d'aiuto`b`# in alto a sinistra. `b`@Tale link è accessibile 
            anche prima di effettuare il login`b`#.`n`n");
    output("`#- Ricordate che una petizione viene notificata all'intero staff, permettendovi di ottenere una risposta 
            nel più breve tempo possibile. I messaggi privati, d'altro canto, vengono letti solo da chi li riceve, 
            allungando i tempi di risposta e creando confusione nella comunicazione tra staff e utenti.`n`n");
    output("`#- I membri dello staff si riservano il diritto di `b`@non rispondere`b`# a semplici messaggi privati ricevuti 
            dai giocatori. Utenti recidivi (che inviino svariati messaggi privati a singoli membri dello staff anziché 
            aprire petizioni) `b`@potranno essere puniti`b`#.`n`n`n");
    output("`%`b2`b) `#Quando inviate una petizione, sceglietene con cura la `b`@categoria`b`#. In tal modo 
            velocizzate l'intervento dei membri dello staff deputati alla risoluzione del problema.`n`n");
    output("`#- Tenete presente che lo staff è fatto di persone con una propria vita privata: qualora la risposta al vostro 
            quesito dovesse tardare ad arrivare, pazientate almeno `b`@24 ore`b`# prima di inviare una nuova petizione.`n`n`n");
    output("`%`b3`b) `#È consigliato agli utenti, `b`@quando sia possibile`b`#, di scrivere le petizioni `b`@dopo aver 
            effettuato il login con il personaggio cui la petizione si riferisce`b`#. Ciò consente di minimizzare 
            i tempi di intervento.`n`n`n");
    output("`%`b4`b) `#Prima di aprire petizioni per dubbi riguardanti le meccaniche di gioco, prendetevi un po' di 
            tempo per leggere le varie `b`@FAQ`b`# raggiungibili sia dalla pagina di login che dalla `b`@sezione help`b`#.`n 
            È inoltre caldamente consigliata una visita al `b`@druido`b`# che dimora nel monastero in foresta.`n`n");
    output("`#- Lo staff si riserva il diritto di `b`@non`b`# rispondere a dubbi inerenti argomenti già trattati nelle 
            succitate sezioni.`n`n");
    output("`#- Lo staff si impegna a `b`@non`b`# divulgare esplicitamente segreti e tattiche di gioco. Per conoscere tali 
            informazioni dovete studiare personalmente il gioco in tutte le sue sfaccettature.`n`n`n");
    output("`%`b5`b) `#Segnalazioni non veritiere o inviate con il semplice intento di spammare verranno sanzionate.`n`n`n");
    output("`%`b6`b) `#Atteggiamenti in gioco volti unicamente a screditare pubblicamente l'operato dello staff verranno 
            duramente sanzionati.`n`n`n`n`n");

    output("<big>`b`c`^J) CASI NON PREVISTI DAL REGOLAMENTO`0`c`b</big>`n`n",true);
    output("`%`b1`b) `#Nel caso in cui lo staff si trovi ad affrontare un evento non descritto in queste righe, si 
           impegna a:`n`n");
    output("`#- Discutere privatamente con il diretto interessato per comunicargli che la sua azione è potenzialmente 
            sanzionabile.`n`n");
    output("`#- Modificare immediatamente il regolamento per rendere tale azione sanzionabile per il futuro.`n`n`n");
    output("`%`b2`b) `#In casi gravi la modifica al regolamento potrebbe risultare `b`@retroattiva`b`#, portando quindi 
            alla punizione dell'utente che abbia creato col suo comportamento la necessità della modifica stessa.`n`n");

//}

page_footer();
?>