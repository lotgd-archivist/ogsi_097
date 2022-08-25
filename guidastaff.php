<?php
require_once "common.php";
isnewday(2);
page_header("GUIDA PER LO STAFF");
isnewday(2);
addnav("Torna alla Grotta","superuser.php");

output("Che siate nuovi arrivati o esperti veterani, ecco una breve guida a ciò che un moderatore ha il potere di fare e a 
        come esercitare tale potere.`n`n`bPiazza`b`nLa piazza è pressocché identica a quella che qualsiasi altro personaggio 
        vede. Le grosse differenze sono le seguenti:`n`n");
output("- `iTrump Tower`i: è una stanza speciale all'interno della quale il personaggio può modificare autonomamente se 
        stesso, fondamentalmente per scopi di test. Le varie voci sono sostanzialmente autoesplicative: si possono ottenere oro, 
        gemme, esperienza, punti cavalcare, cattiveria... si puó aumentare o diminuire il numero di DK del personaggio, visitare 
        direttamente il borgo della fanciulla (ad esempio per scopi di moderazione dei commenti), ed ovviamente cambiare sesso, 
        cosa questa utilissima perché... beh... perché sì.`n`n");
output("- `iFissa link`i: traduzione fantasiosa dall'inglese \"fix link\", permette al moderatore di ricercare un giocatore 
        tramite il suo nome e sbloccarne la navigazione. Se avete presente lo sporadico bug di Heimdall, la (ex-)malfamata 
        agenzia matrimoniale eccetera saprete già che a volte i personaggi si ritrovano bloccati in una determinata pagina 
        senza possibilità di recupero. Quando ricevete una segnalazione, `ifissa link`i è il luogo in cui recarvi per rimediare. 
        Il funzionamento è assolutamente intuitivo.`n`n");
output("- `iSuicidati`i: le anime dei morti sono più afflitte del solito? Ai tormenti di Ramius si sono aggiunti quelli 
        derivanti da gente scortese e caciarona? C'è bisogno, insomma, di avere accesso alla terra delle ombre? Non c'è 
        bisogno di combattere in foresta fino alla morte... noi siamo furbi, ci suicidiamo!`n");
output("* Una volta morti, vi ritroverete nella sezione delle notizie. Lì comparirà un comodo link per ottenere un `inuovo 
        giorno`i, ossia risorgere.`n");
output("** Nella terra delle ombre, invece, troverete il link `iresurrezione`i. Per quanto il risultato ai fini delle vostre 
        mansioni sia praticamente identico, questo link vi farà risorgere nello stesso modo in cui un normale personaggio 
        risorge grazie a Ramius. Avrete dunque la metà dei turni, ma soprattutto `iverrà generata una notizia riguardante 
        la vostra resurrezione`i.`n`n");
output("- Anche in piazza potrete trovare un link al `inuovo giorno`i, con funzionalità identiche a quello precedentemente 
        analizzato.`n`n");
output("- `iPreferenze`i: all'interno del menu troverete una voce dedicata allo staff, `iRicevi mail quando inserita 
        petizione?`i`n");
output("Se scegliete il `isì`i, riceverete una notifica via PM per ogni petizione inserita da un giocatore. Utile per sapere 
        immediatamente se c'è qualcuno che ha bisogno di aiuto.`n");
output("Il sottomenu `ipreferenze superutente`i è poco utile per i moderatori, poiché la maggior parte degli avvisi (se non 
        tutti, non ricordo bene) sono riservati agli admin. In ogni caso, potrete attivare o disattivare singolarmente ciascuna 
        tipologia di avviso inserendo rispettivamente un `b1`b o uno `b0`b nel relativo campo di testo.`n`n");
output("E poi, ovviamente, troverete un link alla`n`n");
output("`bGrotta del Superutente`b`n`n");
output("Questo è sostanzialmente un hub che raccoglie tutte azioni che vi è possibile eseguire, oltre ad una stanza dedicata 
        alla discussione con il resto dello staff.`n`n");
output("- `iTorna alla mondanità`i: banalmente, vi permette di tornare in piazza.`n`n");
output("- `iGuarda petizioni`i: qui si possono leggere tutte le petizioni inviate dagli utenti. Che abbiate scelto o meno 
        di ricevere una notifica all'inserimento delle petizioni, le troverete tutte elencate in questo luogo. Come spiegato 
        anche lì, se ne leggete una ma non sapete risolvere il problema ricordate di segnalarla come `ida leggere`i. Se avete 
        commenti da fare potete inserirli in calce ad ogni petizione. Se risolvete il problema, ricordatevi di segnalare la 
        petizione come chiusa. Altrimenti qualcun altro risponderà dopo di voi, e ci faremo la figura dei disorganizzati!`n`n");
output("- `iModera commenti (1 e 2)`i: in queste due sezioni, sostanzialmente equivalenti, potrete cancellare eventuali 
        commenti inopportuni. La differenza tra le due? Nella prima potrete cancellare i commenti singolarmente con un 
        apposito tasto, nella seconda potrete selezionarne più d'uno e cancellarli in blocco. Anche qui, il funzionamento 
        è molto intuitivo.`n`n");
output("- `iBio giocatori`i: qui sono elencate tutte le biografie recentemente modificate. Utile per controllare se qualcuno 
        si è divertito a scrivere cose inopportune. Avete anche la possibilità di bloccare singole biografie (se avete il 
        dubbio che qualcosa non vada, in modo da poterle leggerle e magari discuterne con calma insieme al giocatore e/o al 
        resto dello staff), o di bloccarle tutte (utile nel caso delle caratteristiche \"feste in maschera\", per rendere i 
        personaggi non riconoscibili). Potete anche modificare le biografie per eliminare contenuti inopportuni.`n`n");
output("- `iMute and fixnavs player`i: questa è un'estensione, anzi, è l'\"originale\", del `ifissa link`i presente in piazza. 
        All'interno troverete:`n`n");
output("1) Un elenco dei personaggi silenziati`n");
output("2) Uno storico dei mute assegnati`n");
output("3) L'elenco completo dei personaggi, che potrete comunque anche ricercare.`n`n");
output("Per ogni personaggio avrete a disposizione quattro opzioni:`n`n");
output("a) `iFissa link`i: sono sicuro che siate persone molto attente, quindi già sapete a cosa serve.`n");
output("b) Lo sapete, vero?`n");
output("c) `iMute`i: indovinate a cosa serve? Potete selezionare la durata del mute (espressa in newday, non giorni reali), 
        indicare la causa del mute che rimarrà nello storico delle punizioni accessibile in grotta (a seguire una spiegazione) 
        ed il testo del PM da inviare al personaggio che subisce il mute. Semplicissimo!`n");
output("d) `iPM`i: permette di inviare un `iavvertimento`i. Attenzione: un `iavvertimento`i è a tutti gli effetti un 
        provvedimento di gioco! Ciò implica che non si limita ad un normale PM: permette di inoltrare un messaggio al 
        personaggio selezionato e contemporaneamente di indicarne il motivo, che verrà salvato e rimarrà a disposizione dello 
        staff nello storico delle punizioni.`n`n");
output("Ciò permette di individuare eventuali individui recidivi: se il moderatore X avvisa con un normale PM il giocatore A 
        di non usare turpiloquio, e il giorno successivo il moderatore Y trova un altro episodio di turpiloquio, non potrà sapere 
        che il giocatore A era già stato avvisato. Se invece il moderatore X invia un richiamo formale al giocatore A, il 
        moderatore Y potrà immediatamente appurare che A era già stato avvisato, e provvedere di conseguenza.`n`n");
output("- `iPunizioni`i: lo storico di tutte le punizioni assegnate dall'alba dei tempi (più o meno). Se un personaggio si 
        comporta in modo scorretto, prima di intervenire verificate qui se è la prima volta o se ci sono stati altri episodi 
        simili. Una prima infrazione può spesso essere solo oggetto di un richiamo, ma alla seconda o terza volta è bene 
        prendere provvedimenti più seri.`n`n");
output("- Vari editor: credo siano abbastanza intuitivi.`n`n");
output("- `iGDR`i: qui potete leggere i brani inviati per il concorso GDR ed assegnare ad ognuno una votazione. La media 
        dei voti di tutto lo staff determinerà i vincitori della gara.`n`n");
output("- `iEditor creature`i: qui potete modificare, aggiungere od eliminare (ma perché farlo, poi? Sono tutte così 
        adorabili!) le creature presenti in foresta. Decisamente intuitivo!`n`n");
output("- `iEditor labirinti`i: se il vostro sogno è far impazzire quanta più gente possibile con labirinti intricatissimi, 
        questo è il posto che fa per voi.`n");
output("O, almeno, lo sarebbe... se non fosse che, a quanto mi dicono dalla regia, attualmente è impossibile salvare i 
        labirinti dopo averci perso su ore. Evitate di provarci se avete a cuore la vostra sanità mentale, insomma.`n`n");
output("Il resto potete evitarlo.`n`n");
output("- La lista delle cose da fare è... una lista delle cose da fare. Se doveste accorgervi di qualcosa che non va (bug, 
        errori nei testi in gioco eccetera) potete segnalare la cosa in questa sede.`n`n");
output("- Statistiche eccetera saranno solo una curiosità per voi.`n`n");
output("Spero di non aver dimenticato nulla, e che questa guida possa esservi utile.`n`n");
output("Ai nuovi arrivati: benvenuti!`n`n");
output("Made by Mandos");

page_footer();
?>