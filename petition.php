<?php
require_once "common.php";
require_once "common2.php";
if ($_GET['op']=="primer"){
popup_header("New Player Primer");
    output("
<a href='petition.php?op=faq'>Contents</a>`n`n
`^Benvenuti a Legend of the Green Dragon Nuovi Giocatori`n`n
`^`bLa Piazza del Villaggio`b`n`@
Legend of the Green Dragon (LotGD) Sta diventando un gioco abbastanza esteso, con molte aree da esplorare. È facile perdersi con tutto quello che c'è da fare là fuori,
perciò tenete a mente che la piazza del villaggio è in pratica il centro del gioco. Quest'area vi darà accesso a molte delle alrre aree che potete raggiungere, con poche eccezioni
(ne parleremo tra poco). Se vi perdete, o non siete sicuro di cosa sta succedendo, andate alla piazza del villaggio e riprendete da lì.`n
`n
`^`bIl primo giorno`b`n`@
Il primo giorno nel mondo può essere confuso! Vi viene presentata una grande quantità di informazioni, delle quali praticamente nessuna vi serve a qualcosa! È vero!  Una cosa a cui dovreste probabilmente fare attenzione sono i vostri punti ferita.  Li trovate sotto \"Vital Info.\" Qualunque professione scegliate, alla fine sarete una specie di guerriero o combattente, perciò dovete imparare come si combatte. Il modo migliore per farlo è cercare delle creature da uccidere nella foresta. Quando ne trovate una, controllatela ed assicuratevi che non sia di un livello più alto del vostro, perché altrimenti potreste non sopravvivere allo scontro.  Tenete a mente che potete sempre cercare di fuggire da qualcosa che avete incontrato, ma a volte occorre provare diverse volte prima di poter andare via. Potreste voler acquistare armi ed armature nella piazza del villaggio per avere migliori possibilità contro le creature della foresta.`n
`n
Una volta sconfitta una creatura, noterete che probabilmente siete stati danneggiati. Andate alla capanan del guaritore, e potrete essere rimessi in sesto rapidamente. Finché siete di livello 1, la guarigione è gratuita, ma successivamente diviene sempre più costosa. Ricordate anche che è più costoso guarire 1 punto e poi nuovamente 1 punto, piuttosto che guarirne due in una volta sola. Perciò se siete poco feriti e volete risparmiare soldi, potete rischiare di continuare a combattere contro uno o due mostri e poi guarire le ferite di tutti questi combattimenti in una volta sola.`n
`n
Dopo aver ucciso un po' di creature, dovreste ritornare al villaggio, entrare al Campo d'Allenamento e parlare al vostro maestro. Il maestro vi dirà quando siete pronti a sfidarlo, e quando siete pronti dovreste provarci (assicuratevi di essere completamente guariti prima, però!). Il maestro non vi ucciderà se perdete,
al contrario vi darà una pozione guaritrice in omaggio e vi rimanderà per la vostra strada.",true);
    if (getsetting("multimaster",1) == 0) {
        output(" Potete sfidare il maestro solo una volta al giorno.");
    }
output("
`n
`n
`^`bLa morte`b`n`@
La morte è una parte naturale di qualunque gioco che contiene una forma di combattimento. In Legend of the Green Dragon, la morte è solo una condizione temporanea. Morendo si perdono tutti i soldi che si hanno in tasca (quelli nella banca sono al sicuro!), ed un po' dell'esperienza accumulata. Mentre si è morti è possibile esplorare la terra delle ombre ed il cimitero. Nel cimitero, troverete Ramius il Signore della Morte. Egli ha alcune cose che vorrebbe farvi fare per lui, e in cambio vi darà dei poteri speciali o dei favori. Il cimitero è una di quelle aree che non si possono raggiungere dalla piazza del villaggio. In effetti, da morti non si può andare affatto nella piazza del villaggio!`n
`n
A meno che convinciate Ramius a farvi resuscitare, rimarrete morti fino all'inizio del giorno di gioco successivo. Ci sono ".getsetting("daysperday",2)." giorni di gioc in ogni giorno reale. Questi iniziano quando l'orologio del villaggio segna la mezzanotte.`n
`n
`^`bNuovi Giorni`b`n`@
Come abbiamo appena detto, ci sono ".getsetting("daysperday",2)." giorni di gioc in ogni giorno reale. Questi iniziano quando l'orologio del villaggio segna la mezzanotte. All'inizio di ogni nuovo giorno vi verranno dati nuovi combattimenti nella foresta, interessi sulle monete che avete in banca (se il banchiere è soddisfatto del vostor lavoro!), ed un mucchio di altre statistiche verranno aggiornate. Verrete anche resuscitati se eravate morti, ed avrete un'altra possibilità di conquistare il mondo. Se non vi collegate per un intero giorno di gioco, perderete la vostra opportunità di prendere parte a quel giorno (vale a dire che i nuovi giorni di gioc vengono assegnati solo se effettivamente vi collegate, stare lontano dal gioco non vi permetterà di accumulare un mucchietto di giorni). Combattimenti nella foresta, battaglie pvp, utilizzo di poteri speciali ed altre cose che vengono aggiornate giornalmente NON vengono portate avanti da un giorno al successivo (non potete accumularle).`n
`n",true);
if (getsetting("pvp",1)){
output("
`^`bPvP (Giocatore contro Giocatore)`b`n`@
Legend of the Green Dragon contiene un elemento di PvP, con i giocatori che possono attaccarsi a vicenda. Come nuovi giocatori siete protetti dontro questi attacchi per i primi ".getsetting("pvpimmunity",5) . " giorni di gioco o fino a quando accumulate " . getsetting("pvpminexp",1500) . ", a meno che non decidiate di attaccare un altro giocatore. In alcuni server questa possibilità può essere disabilitata, nel qual caso non c'è possibilità di essere attaccati da altri giocatori. Potete sapere se il PvP del server è attivo o meno cercando \"Uccidi un giocatore.\" nella piazza del villaggio. Se non c'è, non potete attaccare (o essere attaccati da) altri giocatori.`n
`n
Se morite in uno scontro PvP, perdete solo le monete che avete con voi e il " . getsetting("pvpdeflose", 5) . "% della vostra esperienza. Non perdete turni di combattimento nella foresta o altro. Seattaccate qualcun altro in PvP potete ottenere il " . getsetting("pvpattgain", 10) . "% della sua esperienza e tutte le monete che ha con sé. Tuttavia, se attaccate qualcuno e morite perderete il " . getsetting("pvpattlose", 15) . "% della vostra esperienza, oltre a tutte le monete che avevate. Se qualcuno vi attacca e perde, ottenete le monete che aveva con sé ed il " . getsetting("pvpdefgain", 10) . "% della sua esperienza. Potete attaccare solo qualcuno il cui livello è vicino al vostro, perciò non vi preoccupate che, essendo a livello 1, qualcuno di livello 15 venga a prendersela con voi.`n
`n
Se prendete una stanza nella locanda quando decidete di uscire dal gioco, sarete protetti contro attacchi casuali. Il solo modo per attaccarvi mentre siete nella locanda è corrompere il barista, cosa che può risultare costosa. Uscire nei campi significa che chiunque può attaccarvi senza dover dare soldi al barista. Non potete essere attaccati mentre state giocando, solo quando siete scollegati, perciò pià giocate più siete protetti ;-). Inoltre, se venite attaccati e morite, nessun altro può attaccarvi fino a quando non vi ricollegate, perciò non preoccupatevi di essere attaccati 30 o 40 volte per notte. Ricollegarvi al gioco vi rende di nuovo un possibile bersaglio per il  PvP se siete giò stati uccisi nello stesso giorno.`n
`n",true);
}
output("
`^`bPronti a conquistare il mondo!`b`n`@
Ora dovreste avere un'idea di come funzionano le basi del gioco, come procedere e come proteggervi. Ci sono molte altre cose nel mondo, perciò esploratelo!
Non abbiate paura di morire, specialmente finché siete di basso livello, perché anche da morti avete delle cose che potete fare!
",true);

}else if($_GET['op']=="faq3"){
popup_header("Domande Specifiche e Tecniche");
output("
<a href='petition.php?op=faq'>Contenuto</a>`n`n
`c`bDomande Specifiche e Tecniche`b`c
`^1.a. Come posso essere stato ucciso da un altro giocatore mentre stavo giocando?`@`n
La causa principale di ciò è che l'altro giocatore abbia iniziato ad attaccarvi mentre eravate scollegati, e terminato il combattimento dopo che vi siete collegati. Potrebbe succedere perfino quando avete giocatosenza sosta per l'ultima ora, quando qualcuno inizia un combattimento deve finirlo prima o poi. Se iniziano a combattervi e poi chiudono il browser, la prossima volta che si collegano dovranno finire il combattimento. In tal caso perdete la cifra minore tra le motete che avevate quando il combattimento è iniziato e quelle che avete quando è finito. Perciò se vi siete scollegati con 1 moneta in tasca, vi attaccano, vi collegate, accumulate 2000 monete, completano il combattimento e vi uccidono, vi porteranno via solo 1 moneta. Lo stesso vale se vi siete scollegati con 2000 monete in tasca e quando vi uccidono ne avete solo 1.`n
`n
`^1.b. Perché mi dice che sono stato ucciso nei campi se stavo dormando nella locanda?`@`n
La stessa cosa accade se qualcuno ha iniziato ad attaccarvi mentre eravante nei campi ed ha finito dopo che vi eravata ritirati nella locanda per la notte. Tenete in mente che se restate a lungo inattivi nel gioco diventate un facile bersaglio perché gli altri vi attacchino nei campi. Se dovete andare via dal computer per qualche minuto, può essere una buona idea tornare prima alla vostra stanza nella locanda, così non verrete attaccati mentre siete inattivi.`n
`n
`^2. Il gioco mi dice che non accetto i cookies, cosa sono e che devo fare?`@`n
I Cookies sono frammenti di informazione che i siti web conservano sul vostro computer per potervi distinguere dagli altri giocatori. A volte se avete un firewall questo li blocca, e ci sono browser che permettono di bloccare i cookies. Controllate la documentazione del vostro browser o firewall, o guardate nelle impostazioni delle preferenze per modificare il fatto che accettino o meno i cookies. Dovete accettare almeno i session cookies per poter giocare, anche se accettare tutti i cookies è meglio. `n
`n
",true);

}else if ($_GET['op']=="faq"){
popup_header("Frequently Asked Questions (FAQ)");
output("
`^Benvenuto a Legend of the Green Dragon. `n
`n`@
Ti svegli un giorno e per qualche motivo ti ritrovi in un villaggio. Fai un giro, stupito, fino a quando non capiti nella Piazza del Villaggio. Una volta qui inizi a fare un mucchio di domande stupide. La gente (che per qualche ragione è perlopiù nuda) ti lancia della roba contro. Fuggi infilandoti nella Locanda e scopri una pila di opuscoli accanto alla porta. Il titolo degli opuscoli è: \"Tutto quello che avreste voluto sapere riguardo LoGD, ma che avevate paura di chiedere.\" Guardandoti furtivamente attorno per assicurarti che nessuno ti stia osservando, ne apri uno e leggi:`n
`n
\"Dunque sei un Principiante. Benvenuto nel club. Qui troverai le risposte alle domande che ti affliggono. Beh, in effetti troverai le risposte alle domande che hanno afflitto NOI. Perciò, su, leggi e lasciaci in pace!\" `n
`n
`bContents:`b`n
<a href='petition.php?op=primer'>Nuovi Giocatori</a>`n
<a href='petition.php?op=faq1'>FAQ sul gioco (Generali)</a>`n
<a href='petition.php?op=faq2'>FAQ sul gioco (con anticipazioni)</a>`n
<a href='petition.php?op=faq3'>FAQ su Problemi Tecnici</a>`n
`n
~Grazie,`n
la Direzione.`n
",true);

}else if($_GET['op']=="faq1"){
popup_header("Domande Generali");
output("
<a href='petition.php?op=faq'>Contenuti</a>`n`n

`c`bDomande Generali`b`c
`^1.  Qual è lo scopo di questo gioco?`@`n
Rimorchiare le ragazze.`n
Seriamente, però. Lo scopo è uccidere il drago verde.`n
`n
`^2.  Come faccio a trovare il drago verde?`@`n
Non puoi.`n
Beh, più o meno. Non puoi trovarlo fino a quando non hai raggiunto un certo livello. Una volta a quel livello, diventerà immediatamente ovvio.`n
`n
`^3.  Come aumento il mio livello?`@`n
Mandaci dei soldi.`n
No, non mandare soldi - guadagni esperienza combattendo le creature della foresta.  Quando hai abbastanza esperienza, puoi sfidare il tuo maestro nel villaggio.`n
`n
Beh, puoi mandarci dei soldi comunque se vuoi (vedi link di PayPal)`n
`n
`^4.  Perchè non posso battere il mio maestro?`@`n
È troppo bravo per quelli come te.`n
Hai provato a domandargli se hai abbastanza esperienza?`n
Hai provato a comprare qualche arma o armatura nel villaggio?`n
`n
`^5.  Ho usato tutti i miei turni. Come faccio ad averne altri?`@`n
Manda soldi.`n
No, metti via il portafogli. Ci *sono* alcuni modi per ottenere uno o due turni extra, ma per lo più devi  aspettare l´indomani. All'arrivo di un nuov giorno avrai più energia.`n
Non stare a chiedere quali sono questi modi - alcune cose è più divertente scoprirle da soli.`n
`n
`^6.  Quando inizia il nuovo giorno?`@`n
Subito dopo la fine di quello vecchio.`n
`n
`^7.  Arghhh, mi state uccidendo con queste risposte argute - non potete semplicemente darmi una risposta diretta?`@`n
No.`n
Beh, okay, un nuovo giorno corrisponde all'orologio del villaggio (lo si può vedere anche nella locanda). Quando l'orologio raggiunge la mezzanotte, il nuovo giorno inizia. Il numero di volte in cui l'orologio di LoGD segna la mezzanotte in un giorno reale può cambiare secondo i server.  Il beta server ha 4 giorni di gioco per giorno reale,  il server SourceForge ne ha 2. Altri server dipendono dall'amministratore.`n
`n
 `^8. Qualcosa non ha funzionato!!! Come faccio ad avvisarvi?`@`n
Manda soldi. Meglio ancora, manda una petizione. Una petizione non dovrebbe dire 'questo non funziona' o 'sono fuori uso' o 'non posso entrare' o 'Ehy, ciao!'. Una petizione *dovrebbe* essere molto completa nella descrizione di *cosa* non funziona. Per favore diteci cosa è successo, qual è stato il messaggio di errore (copia e incolla è un tuo amico), quando si è verificato, e qualunque altra cosa che possa essere utile.  \"sono fuori uso\" non è utile.  \"Ci sono dei salmoni che volano fuori dal mio monitor quando tento di entrare nel gioco\" è molto più descrittivo. Ed umoristico. Anches e non c'è molto che possiamo farci. In generale, siate pazienti con queste richieste - ci sono molto giocatori, e fintantoché l'amministratore è sommerso da petizioni del tipo 'Ehy, ciao!', ci vorrà un po' prima che possa rispondere. `n
`n
`^9.  E se tutto quello che ho da dire è 'Ehy, ciao!'?`@`n
Se non hai niente di carino (o utile, o interessante, o creativo che aggiunge qualcosa allo sviluppo generale del gioco) da dire, non dire nulla.`n
Ma se vuoi conversare con qualcuno, manda un messaggio attraverso Il Vecchio Ufficio Postale.`n
`n
`^10.  Come uso gli emotes?`@`n
Digita :: prima del testo.`n
`n
`^11.  Cos'è un emote?`@`n
`&UnaDomandaOvvia ti da un pugno nello stomaco.`n
`@Questo è un emote. Puoi usare emote nel villaggio se vuoi fare un'azione piuttosto che parlare soltanto.`n
`n
`^12.  Come ottieni i colori nel nome?`@`n
Mangia dei buffi funghi.`n
No, metti via i funghi, i colori significano che il personaggio è parte integrante del processo di beta-testing - ricerca di un bug, aiuto nella creazione di creature, ecc, o è sposato con l'amministratore.`n
`n
`^13.  Raga, è karino usare le abbreviazioni comuni nel villaggio cmq? No xkè sono + veloci!`@`n
NO, per amor di pete, usate parole complete ed una buona grammatica, PER FAVORE! Queste non sono parole: xò, xkè, cmq, ki, e tutto il resto!`n
`n
",true);
}else if($_GET['op']=="faq2"){
popup_header("Domande Generali con Anticipazioni");
output("
<a href='petition.php?op=faq'>Contenuti</a>`n`n
`&(Attenzione, la parte che segue delle FAQ può contenere delle anticipazioni, perciò se volete davvero scoprire le cose per conto vostro, fate meglio a non leggere oltre. Questo non è un manuale, è un opuscolo di aiuto.)`&
`n
`n
`n
`n
`n
`n
`n
`n
`n
`n
`n
`n
`n
`^1.  Come si ottengono le gemme?`@`n
Andate a lavorare in miniera!!`n
In effetti non si trovano nelle miniere. Si possono ottenere nella foresta durante gli 'eventi speciali' che si verificano casualmente - se giocate abbastanza spesso, ne torverete una prima o poi.`n Inoltre, si possono ottenere a volte delle gemme negli scontri nella foresta",true);
if (getsetting("topwebid",0) != 0) {
    output("  Per finire, avrete una gemma gratis votando per questo server a Top Web Games (vedi link fuori dal villaggio).");
}
output("
`n
`n
`^2.  Perchè alcune persone sembrano avere tanti punti ferita ad un livello basso?`@`n
Perchè sono più grossi di te.`n
No, davvero, *sono* più grossi di te. Anche tu crescerai un giorno.`n
`n
`^3.  Ha qualcosa a che fare con i titoli che hanno le persone?`@`n
Ma sicuro!`n
In effetti, ogni volta che uccidi il drago ottieni un nuovo titolo e ritorni al livello uno. Perciò i giocatori di basso livello con dei titoli hanno avuto opportunità di migliorarsi.  (vedi Sala degli Eroi)`n
`n
`^4.  Perchè quel vecchietto continua a colpirmi con una bella/brutta bacchetta nella foresta?`@`n
Assomigli a una pentolaccia!`n
È un evento speciale che può aumentare o diminuire il fascino.`n
`n
`^5.  Beh, a che serve il fascino?`@`n
A rimorchiare le ragazze.`n
Beh, in effetti, è a quello che serve. Visita qualcuno alla locanda, e dovresti essere in grado di scoprirlo. Più fascino hai, più successo avrai con quelle persone.`n
`n
`^6.  Okay, ho visto l'uomo nella foresta e mi ha colpito con la brutta bacchetta, ma dice che sono più brutto della bacchetta e le ho fatto perdere un punto di fascino. Che sta succedendo?`@`n
Sei ovviamente la persona meno affascinante del pianeta. E se sei la persona che ha davvero fatto questa domanda, sei anche la più stupida. Usa un minimo di ingegno, vuoi? No. Davvero.`n
Okay, abbiamo detto che sei il più stupido, perciò: significa che al momento hai già zero punti di fascino.`n
`n
`^7.  Come controllo il mio fascino?`@`n
Guardati allo specchio una volta ogni tanto.`n
Scherziamo - non ci sono specchi. Devi chiedere ad un amico come gli sembri oggi - le risposte possono essere vaghe, ma ti danno un'idea di come stai andando.`n
`n
`^8.  Come si va in altri villaggi?`@`n
Prendi il treno in periferia. Proprio in periferia.`n
In realtà non ci sono altri villaggi. Qualunque riferimento ad essi (es. la gente di Eythgim che incontri nella foresta) esiste solo per dare più spessore al gioco. `n
`n
`^9.  Chi è la Direzione?`@`n
Sook, Barik, Poker e Essizard sono responsabili di queste FAQ, ma se qualcosa non funziona scrivete ad Excalibur . Lui è responsabile di tutto il resto. `n
`n
`^10.  Come fanno ad essere così attraenti, comunque?`@`n
Un sacco di maschere al viso, caro!! Excalibur in particolare preferisce la Maschera Facciale all'essenza di Uva.`n
",true);
}else{
    popup_header("Richiesta d'Aiuto");
    if (count($_POST)>0){
        $p = $session['user']['password'];
        unset($session['user']['password']);
        //mail(getsetting("gameadminemail","postmaster@localhost"),"LoGD Petition",output_array($_POST,"POST:").output_array($session,"Session:"));
        if($_POST['contaparole']>0 && time()-$_POST['opmet']>4 && $_POST['email']!="" && $_POST['categoria']!=null) {
            $sql = "INSERT INTO petitions (author,categoria,date,body,pageinfo) VALUES (".(int)$session['user']['acctid'].",".$_POST['categoria'].",now(),\"".addslashes(output_array($_POST))."\",\"".addslashes(output_array($session,"Session:"))."\")";
            db_query($sql);
            $_POST['categoria'] = $petizioni[$_POST['categoria']];
            report(2,"Petizione!","`^Autore:".$session['user']['name']."`^`n".output_array($_POST),"petizioni");
/*          $sql = "SELECT acctid,prefs FROM accounts WHERE superuser>=2";
            $result = db_query($sql);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $prefs = unserialize($row[prefs]);
                if ($prefs['petizioni']){
                   $_POST['categoria'] = $petizioni[$_POST['categoria']];
                   systemmail($row['acctid'],"Petitione",output_array($_POST),(int)$session['user']['acctid']);
                }
            }
*/          $session['user']['password']=$p;
            output("La tua petizione è stata inviata all'amministratore del server. Sii paziente, molti amministratori
            hanno lavori ed obblighi al di fuori del gioco, perciò a volte ci vuole tempo per avere una risposta.");
        }else{
            $session['user']['password']=$p;
            output("La tua petizione NON è stata inviata all'amministratore del server perché riconosciuta come spam!
            (o per qualche altra ragione).`nIn caso di buona fede ti invitiamo a riprovare e riempire con calma tutti
            i campi!");
            /*
            $f= @fopen("spam.txt", "a");
            @fwrite($f, addslashes(output_array($_POST)) . " \r\nkeydown: " . $_POST['contaparole'] . "\r\nip: " . $_SERVER['REMOTE_ADDR'] . " tempo: " .(time()-$_POST['opmet']) . "\r\n\r\n\r\n");
            @fclose($f);
            */
        }
    }else{
        output("<form action='petition.php?op=submit' method='POST' name=\"mypet\">
        Nome del tuo personaggio: <input name='charname'>`n
        Indirizzo e-mail: <input name='email'>`n
        Categoria: <select name=\"categoria\"><option value=\"\" selected>Scegli Categoria</option><br>",true);
        for ($i = 1; $i <= count($petizioni); $i++){
            output("<option value=\"".$i."\">".$petizioni[$i]."</option>",true);
        }
        output("</select>`n
        Descrizione del problema:`n
        <textarea name='description' cols='30' rows='5' class='input' onkeyup=\"cf()\"></textarea>`n
        <input type='submit' class='button' value='Sottoponi'>`n
        Per favore sii il più possibile descrittivo nella richiesta. Se hai delle domande sul funzionamento del gioco,
        leggi le <a href='petition.php?op=faq'>FAQ</a>. Le petizioni relative alle meccaniche di gioco il più delle volte non ricevono
        risposta, a meno che riguardino un bug.
        <input type=\"hidden\" name=\"contaparole\" value=\"0\">
        <input type=\"hidden\" name=\"opmet\" value=\"" .date("d-m-Y H:i",time()) . "\">
        </form>
        <script type=\"text/javascript\">
          kd= 0;
          function cf() {
               kd++;
               document.forms[\"mypet\"].contaparole.value= kd;
          }
        </script>
        ",true);
    }
}
popup_footer();
?>