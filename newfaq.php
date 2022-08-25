<?php

/*
* newfaq.php
*
* @version 0.9.7 jt
*
* creazione nata dalla collaborazione di Danny, Gildur, Sterminatore ed Enialis
*
*/

require_once("common.php");
page_header("MiniFAQ");
checkday();


if (!isset($session)) exit();
switch ($_GET[op]) {
case "":
     output ("`n`\$ QUESTA É UNA VERSIONE `@*BETA*`\$, PER CUI IL GIOCO É SOGGETTO A CONTINUI CAMBIAMENTI`n");
     output ("`n `n");
     output ("`n`&Prima di tutto vi do il benvenuto, a nome di tutto lo staff, a nome degli admin e a
            nome mio. Spero che giocando con noi vi divertirete il più possibile.`n");
     output ("`n `@Legend of the Green Dragon (LotGD) `& è un gioco appartenente al tipo GDR. Cosa significa??
            La sigla GDR stà a significare Role Playing Game, ovvero Gioco di Ruolo. Questo tipo di gioco nacque
            circa un trentennio fa e come possiamo vedere, tuttora sono tra i tipi di giochi più famosi
            al mondo. LotGD nasce nel 2002-2003, da Eric Stevens, e Chris Yarbrough, prendendo spunto
            da 'Legend of the Red Dragon' di Seth Able Robinson. Attualmente esistono varie versioni
            del gioco, ma non penso di dirvi bugie affermando che questa è la versione più completa, la
            quale conta numerosi player. Sole le versioni inglesi ne contano di più, ma indubbiamente
            sono più povere per quanto riguarda la struttura del gioco. `#Ma cosa comporta essere un
            GDR? `& In termini di gioco significa che il gioco non si basa sulla costruzione di grandi
            città, di potenti eserciti o di chi vince una partita a calcio o una gara con le macchine.
            Si tratta semplicemente di crearsi un personaggio e accudirlo. Immaginate di dovervi trovare
            in un altro mondo, dove invece di essere impiegati, operai, commessi, etc. siete dei
            guerrieri o dei maghi, e così via dicendo. Immaginate inoltre che oltre ad esserci umani,
            ci siano anche nani, elfi, troll,. Insomma, un vero e proprio mondo dove vivere.
            Comincerete il gioco, che sarete dei contadini con la zappa e finirete che sarete divinità.
            Tra questi avrete modo di sperimentare cosa si prova ad essere angeli, imperatori,
            gladiatori (non vi dico tutto altrimenti vi rovino il gusto di scoprirlo da soli). Una
            volta divenuti divinità però il gioco finisce, mi potreste dire. Invece no. Grazie ad
            alcune menti geniali, divenute divinità (anche prima), ci si può reincarnare e continuare
            il gioco. Qui troverete non solo un mondo in cui potete interagire con altri player, ma
            anche dove potrete gareggiare con loro, sia individualmente che in squadra (a voi la
            scelta). Come in tutti giochi di ruolo, non solo potrai incrementare la forza del tuo
            personaggio, ma potrai invece migliorarlo in altre direzioni, come per esempio la bellezza,
            o la cattiveria, o la ricchezza,..
            Nella scheda del vostro Pg, troverete due parametri: 'Attacco' e 'Difesa'. Beh ragazzi,
            non ci vuole un genio per capire cosa siano e a cosa servono. Per chi invece non ci arriva,
            sappia che più bassi saranno questi valori, più facilmente morirà. Il gioco si basa su
            turni. In una giornata reale, ovvero in 24 ore, avrete 4 turni o giorni di gioco, il che vuol
            dire che ogni 6 ore reali, avrete un giorno di gioco. I giorni sono alle 00:00, alle 6:00, alle 12:00 e
            alle 18:00. `\$ATTENZIONE`&, se non doveste collegarvi entro sei ore, per esempio dalle 6 alle 12, non
            avrete la possibilità di recuperare il giorno, che sarà perso per sempre. Ogni giorno di gioco
            o 'newday', ricarica i turni di gioco in foresta. Sono questi che vi permettono di cercare
            avventure. Se li finite, dovrete aspettare un nuovo giorno, perché possiate nuovamente affrontare
            nuove avventure. `nChe altro dirvi, buona fortuna e buon divertimento.`n");
     output ("`n `n");
     output ("`n `n");
     output ("`n`\$ Questa è la guida che vi aiuterà durante il vostro gioco. Vi sono raccolte molte
            informazioni che vi permetteranno di capire meglio le dinamiche del medesimo. Prima di mandare
            messaggi agli admin consultate questa guida (e le altre FAQ disponibili) e vedrete che molti problemi verranno
            risolti in pochi attimi. Ciò nonostante essa non contiene tutte le informazioni pertinenti
            al mondo di `@Legend of the Green Dragon`\$ (`@LoGD`\$). Se avrete bisogno di altre delucidazioni,
            potete contattare uno degli admin o tutti gli altri che hanno collaborato, o semplicemente chiedere al
            villaggio, sperando che qualche buona anima vi aiuti.`n");
     addnav ("Informazioni");
     addnav ("`&Area residenziale","newfaq.php?op=Area residenziale");
     addnav ("`&Armature/armi/oggetti","newfaq.php?op=Armature/armi/oggetti");
     addnav ("`&Banca","newfaq.php?op=Banca");
     addnav ("`&Carriere","newfaq.php?op=Carriere");
     addnav ("`&Cimitero","newfaq.php?op=Cimitero");
     addnav ("`(Informazioni utili","newfaq.php?op=Informazioni utili");
     addnav ("`&Livello e DK","newfaq.php?op=Livello e DK");
     addnav ("`&Merick","newfaq.php?op=Merick");
     addnav ("`&Miniera","newfaq.php?op=Miniera");
     addnav ("`(MoTD e MP","newfaq.php?op=MoTD e MP");
     addnav ("`&Odore/vescica/fame","newfaq.php?op=Odore/vescica/fame");
     addnav ("`&PVP","newfaq.php?op=PVP");
     addnav ("`&Reincarnazioni","newfaq.php?op=Reincarnazioni");
     addnav ("`&Terre dei draghi","newfaq.php?op=Terre dei draghi");
     addnav ("`7Tornei","newfaq.php?op=Tornei");
     addnav ("");
if ($session['return']==""){
   $session['return']=$_GET['ret'];
}
if ($session['user']['loggedin']) {
    checkday();
    if ($session['user']['alive']) {
        if ($session['return']==""){
            addnav("Torna al Login","login.php");
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
     //addnav ("`7Torna al villaggio","village.php");
     rawoutput ("<br><br><br><div style=\"text-align: right;\"></font><font color=\"#FFFFFF\">Enialis<br></font><font color=\"#FF9900\">Danny<br></font><font color=\"#B00000\">Gildur<br></font><font color=\"#FFFF00\">Sterminatore<br><font color=\"#0099FF\">Helyes<br><font color=\"#66CC00\">Chumkiu<br></font><br>");
     break;


case "Area residenziale":
     output ("`n`& Cosa sono le tenute?? Sono semplicemente le vostre case, che però possono essere
            costruite solo da chi si è reincarnato. Comunque, per i non reincarnati, è possibile
            acquistare una casa già costruita. Alla casa potrete dare un nome, una religione di
            appartenenza e una descrizione. Potrete usufruire dei privilegi di essa, non appena avrete
            pagato il suo prezzo di costruzione. Quali sono i privilegi?? Intanto potrai dormire in
            essa (Logout), senza pagare il becco di un quattrino. Vi sarà inoltre una cassaforte privata
            dove poter depositare i tuoi soldi, ma anche le gemme. Attenzione, i soldi in cassa saranno
            in comune con tutti gli inquilini, e ciò significa che ognuno potrà prelevarli. Al contrario,
            le gemme depositate saranno solo di chi le ha depositate. Ogni trasferimento, sia di soldi
            che di gemme, viene registrato nell'area di chat, che potrà essere visualizzata
            unicamente da chi ha la chiave. Oltre alla guardia privata, potrai consegnare fino a 10
            chiavi ad altri giocatori che difenderanno la tua tenuta; Ma pensa bene a chi darai le
            chiavi. Riprendertela potrebbe essere costoso ... anche se la possibilità di togliere una
            chiave consegnata esiste.`n
            La tenuta può essere anche venduta o abbandonata se il proprietario non si colleghi per +
            di 45 giorni.`n
            Un player può possedere solo una casa alla volta, ma può avere quante chiavi vuole
            (che danno accesso ad altre abitazioni).`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Armature/armi/oggetti":
     output ("`n`& Per quanto possiate crescere, i vostri personaggi non saranno mai forti abbastanza per
            rinunciare ad alcuni oggetti che in più di uno scontro faranno la differenza. Tra i più
            importanti oggetti, sicuramente rientrano le armi e le armature. Le prime le potete trovare
            da MightyE, mentre le armature da Pegasus. Andando dai rispettivi mercanti, vi comparirà una
            tabella delle armi o armature. Esse avranno un livello di potenza e un rispettivo costo,
            proporzionato al loro livello. Il valore che viene indicato, indica di quanto aumenterà il
            vostro attacco o la vostra difesa. Ad ogni passaggio di DK, i nomi delle armi verranno
            cambiati, ma non vi preoccupate, non perderete niente a parte il fatto che forse è più figo
            andare avanti con uno spadone del dragone che con un'ascia da boscaiolo.`n");
     output ("`n`& Pensate sia tutto?? Beh, se la pensate così potete anche fermarvi qui, ma se pensate
            che ci sia qualcos'altro che dovreste sapere, allora potete continuare a leggere.
            Effettivamente ci sono ancora delle cose che dovreste sapere. Andando al castello di
            Excalibur, potrete trovare un abile mercante di nome Brax. Questo simpatico ometto vi
            mostrerà la sua merce, e vi assicuro che vale la pena fargli una visita. State attenti però,
            potrebbe anche succedere che lui vi rifili una bidonata, facendovi pagare tante gemme per
            oggetti che non valgono nulla. Molti degli oggetti che vende Brax, possono aumentarvi
            l'attacco, la difesa, gli HP, o altro. Possono avere anche un livello di potenziamento, che
            potrete sfruttare solo se apparterrete alla chiesa di Sgrios o Karnak e solo quando potrete
            maledire o benedire i vostri oggetti. Gli oggetti magici potete anche trovarli da Oberon.
            C'è anche un altro modo per avere un oggetto magico (oltre che crearselo da soli, essendo
            fabbri), ma questo ve lo lascio scoprire. Potete portare solo due oggetti magici alla volta,
            ma utilizzarne solo uno alla volta, e l'altro rimarrà nel vostro zaino. A proposito, vi devo
            dire ancora qualcosa, gli oggetti magici hanno un livello che ne limita l'utilizzo: se avete
            il livello richiesto potrete utilizzarlo altrimenti potrete solo venderlo! Ciò per evitare di avere
            contadini con oggetti troppo potenti perché possano controllarli. Per vedere qual è il
            livello degli oggetti che potete controllare, consultate la tabella sottostante.`n");
     output("<table cellspacing=2 cellpadding=2 align='center'>",true);
            output("<tr bgcolor='#FF0000'><td align='center'>`&`bReincarnazioni`b</td><td align='center'>`&`bDK`b</td><td align='center'>`b`&Livello Oggetto`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b0`b</td><td align='center'>`&`b0 ~ 9`b</td>   <td align='center'>`&`b1`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b0`b</td><td align='center'>`&`b10 ~ 19`b</td> <td align='center'>`&`b2`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b0`b</td><td align='center'>`&`b20 ~ xx`b</td> <td align='center'>`&`b3`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`3`b1`b</td><td align='center'>`3`b0 ~ 9`b</td>   <td align='center'>`3`b4`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`3`b1`b</td><td align='center'>`3`b10 ~ 19`b</td> <td align='center'>`3`b5`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`3`b1`b</td><td align='center'>`3`b20 ~ xx`b</td> <td align='center'>`3`b6`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`2`b2`b</td><td align='center'>`2`b0 ~ 9`b</td>   <td align='center'>`2`b7`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`2`b2`b</td><td align='center'>`2`b10 ~ 19`b</td> <td align='center'>`2`b8`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`2`b2`b</td><td align='center'>`2`b20 ~ xx`b</td> <td align='center'>`2`b9`b</td></tr>",true);
     output("</table>",true);
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Banca":
     output ("`n`& Se dopo aver finito i turni andate a dormire, e qualcuno vi uccide, cosa succede al
            vostro oro?? Purtroppo per voi, chi vi uccide, vi deruba anche. Cosa potete fare per
            impedire questo?? Semplice. Basta andare alla banca dove potrete depositare e poi prelevare
            i vostri soldi quando vorrete. Qui verranno tenuti nella cassaforte, sorvegliata da Guido.
            Ovviamente la banca non è infallibile, e accade pure che il guardiano venga sconfitto e che
            parte dei vostri soldi vengano rubati.`n");
     output ("`n`& Oltre a questo la banca vi dà anche degli interessi, che possono variare dal `\$4%`& al
            `\$10%`&, a seconda di quanto depositato. Se siete ricercati dallo sceriffo o per troppo tempo
            avete lasciato i vostri soldi nella banca, non avrete diritto a nuovi interessi.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Carriere":
     output ("`n`& Questa è la parte più interessante della guida. Qui si decide il vostro futuro.
            Esagerato, ma efficace. Una volta che vi siete ambientati nel mondo di LoGD, scoprirete che
            oltre a combattere, potrete avere anche una carriera in cui migliorare e una fede di cui
            far parte. Cominciamo con la fede, quindi cliccate su 'FEDE' e vi spiegherò come funziona.
            Una volta che avrete compreso come funziona, tornate indietro e selezionate 'CARRIERA', che
            vedremo insieme anche come funziona il vostro futuro mestiere.`n");
     addnav ("F?`&Fede","newfaq.php?op=Prosegui&op1=Fede");
     addnav ("C?`&Carriera","newfaq.php?op=Prosegui&op1=Carriera");
     addnav (" ");
     addnav ("`7Torna indietro","newfaq.php");
     break;
     if (!isset($session)) exit();
        case "Prosegui":
        switch ($_GET['op1']) {
             case "Fede":
                  $session['user']['specialinc']="";
                  output ("`n`& Bene, adesso ascoltami attentamente che ti spiegherò come funziona la
                         fede nel mondo di LoGD e cosa comporta. Ci sono tre fedi che si contendono
                         Rafflingate. Ovviamente nessuno v'impedisce di essere un agnostico, un senza
                         fede, ma a quel punto sarete bersagliati da tutti, e non riceverete protezione
                         dai vostri confratelli.`n
                         Ma cosa comporta avere una fede?? In primo luogo, potrai riunirti con tutti
                         quelli della tua stessa fede in luoghi a cui solo voi avrete accesso. Potrete
                         essere ospitati più facilmente da quelli della tua stessa fede e combatterete
                         insieme a loro perché la vostra divinità vinca. Ovviamente ciò comporta che
                         per i player di altra fede, sarete il bersaglio preferito, in quanto infedeli.
                         Un altro grande vantaggio che deriva dall'avere una fede, è la possibilità di
                         partecipare alla messa. Ultimamente giocando, mi sono accorto che molti nuovi
                         giocatori spesso chiedono come funzioni la messa, quindi cercherò di
                         spiegarvelo in questa guida. Se dopo ciò, troverò un altro contadino che mi
                         chiede come funziona la messa, giuro che lo uccido, ma non nel gioco, bensì
                         nella realtà ^_^ . `@Ora ascoltatemi. `& Per prima cosa, dovrete sapere il
                         giorno e l'ora della messa. Una volta che lo saprete vi recherete nella sala
                         riunioni delle rispettive divinità qualche minuto prima dell'inizio (evitate
                         di morire, perché perdere una messa significa essere più deboli rispetto agli
                         altri). Qui potete parlare tranquillamente, però dovete cliccare ogni volta
                         aggiorna. Quando la messa avrà inizio, sul lato sinistro della schermata, sotto
                         'azioni', vi comparirà una scritta 'partecipa alla messa.'. Una volta cliccata,
                         vi compariranno tre opzioni: vita, soldi, abilità. Inutile dirvi che l'opzione
                         che sceglierete vi farà aumentare i soldi, gli HP (la vita), o i punti carriera.`n");
                  output ("`n`^ Sgrios`& - Divinità del bene, è la più antica. Sgrios. Fu la prima
                         divinità di Rafflingate.`n
                         `n`\$ Karnak`& - Divinità del male. Da sempre rivaleggia con Sgrios per il
                         controllo su Rafflingate.`n
                         `n`@ Grande Drago Verde`& - Divinità neutrale. Questa nuova fazione, da poco si
                         è affacciata sul villaggio e ancora nessuno sa bene quale ruolo svolgerà
                         la lotta tra il bene e il male.`n");
                  addnav ("`7Torna indietro","newfaq.php?op=Carriere");
                  break;
             case "Carriera":
                  $session['user']['specialinc']="";
                  output ("`n`& Ora che abbiamo visto bene le fedi, vedremo insieme le carriere che
                         potrete fare al villaggio. Vi ricordo che è possibile avere una sola carriera,
                         quindi fate bene la vostra scelta. Tra i mestieri che potrete fare, vi sono due
                         fazioni. O seguirete la stessa carriera della vostra fede, arrivando nel fulcro
                         dello scontro tra le sette, o vi allontanerete un po' dalla vostra divinità
                         seguendo mestieri più tranquilli. Ovviamente avere una carriera, vi darà enormi
                         benefici.`n");
                   output ("`n `n");
                   output("<table cellspacing=2 cellpadding=2 align='center'>",true);
                            output("<tr bgcolor='#339933'><td align='center'>`&`bDK`b</td>  <td align='center'>`&`bPunti carriera`b</td>  <td align='center'>`b`%Fabbro`b</td>        <td align='center'>`b`!Mago`b</td>          </tr></tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b0`b</td>              <td align='center'>`&`b0`b</td>      <td align='center'>`b`%Garzone`b</td>      <td align='center'>`b`!Iniziato`b</td>      </tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b11`b</td>             <td align='center'>`&`b5000`b</td>   <td align='center'>`b`%Apprendista`b</td>  <td align='center'>`b`!Stregone`b</td>      </tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b16`b</td>             <td align='center'>`&`b20000`b</td>  <td align='center'>`b`%Fabbro`b</td>        <td align='center'>`b`!Mago`b</td>          </tr>",true);
                            output("<tr class='trdark'><td align='center'>`&`b-`b</td>               <td align='center'>`&`bmax`b</td>    <td align='center'>`b`%Mastro Fabbro`b</td> <td align='center'>`b`!Arcimago`b</td>      </tr>",true);
                   output("</table>",true);
                   output ("`n `n");
                   output("<table cellspacing=2 cellpadding=2 align='center'>",true);
                            output("<tr bgcolor='#006699'><td align='center'>`&`bDK`b</td>  <td align='center'>`&`bPunti carriera`b</td>   <td align='center'>`b`^Sgrios`b</td>          <td align='center'>`b`\$Karnak`b</td>                <td align='center'>`b`@Grande Drago Verde`b</td>    </tr></tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b0`b</td>              <td align='center'>`&`b0`b</td>       <td align='center'>`b`^Seguace`b</td>         <td align='center'>`b`\$Invasato`b</td>              <td align='center'>`b`@Stalliere dei Draghi`b</td>  </tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b6`b</td>             <td align='center'>`&`b4000`b</td>    <td align='center'>`b`^Accolito`b</td>        <td align='center'>`b`\$Fanatico`b</td>              <td align='center'>`b`@Scudiero dei Draghi`b</td>   </tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b11`b</td>             <td align='center'>`&`b10000`b</td>   <td align='center'>`b`^Chierico`b</td>        <td align='center'>`b`\$Posseduto`b</td>             <td align='center'>`b`@Cavaliere dei Draghi`b</td>  </tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`b16`b</td> <td align='center'>`&`b40000`b</td>   <td align='center'>`b`^Sacerdote`b</td>       <td align='center'>`b`\$Maestro delle Tenebre`b</td> <td align='center'>`b`@Maestro dei Draghi`b</td>    </tr>",true);
                            output("<tr class='trlight'><td align='center'>`&`bReincarnazione`b</td> <td align='center'>`&`b100000`b</td>  <td align='center'>`b`^Sommo Chierico`b</td>  <td align='center'>`b`\$Portatore di Morte`b</td>    <td align='center'>`b`@Cancelliere dei Draghi`b</td></tr>",true);
                            output("<tr class='trdark'><td align='center'>`&`b-`b</td>               <td align='center'>`&`bmax`b</td>     <td align='center'>`b`^Gran Sacerdote`b</td> <td align='center'>`b`\$Falciatore di Anime`b</td>   <td align='center'>`b`@Dominatore dei Draghi`b</td> </tr>",true);
                   output("</table>",true);
                   addnav ("Carriere non religiose");
                   addnav ("F?`%Fabbro","newfaq.php?op=Prosegui&op1=Car&op2=Fabro");
                   addnav ("M?`!Mago","newfaq.php?op=Prosegui&op1=Car&op2=Mago");
                   addnav ("Carriere religiose");
                   addnav ("S?`^Sgrios","newfaq.php?op=Prosegui&op1=Car&op2=Sgrios");
                   addnav ("K?`\$Karnak","newfaq.php?op=Prosegui&op1=Car&op2=Karnak");
                   addnav ("G?`@Grande Drago Verde","newfaq.php?op=Prosegui&op1=Car&op2=Grande Drago Verde");
                   addnav ("");
                   addnav ("`7Torna indietro","newfaq.php?op=Carriere");
                   break;
                   if (!isset($session)) exit();
                      case "Car":
                      switch ($_GET['op2']) {
                             case "Fabro":
                             $session['user']['specialinc']="";
                                   output ("`n`&Dopo aver fatto visita ad Oberon il Fabbro potrete diventare
                                          suoi Garzoni. Appena iniziate a lavorare siete dei semplici Garzoni e potrete
                                          solamente esercitarvi con il mantice, consumando turni e migliorando così nell'arte
                                          della manipolazione dei metalli (aumentando i vostri punti carriera), ed una volta
                                          che Oberon vi giudicherà pronti, vi nominerà Apprendisti e successivamente Fabbri. Da
                                          Apprendisti potrete allenarvi all'incudine, e potrete anche creare oggetti piccoli.
                                          Una volta Fabbri potrete creare oggetti maggiori ed ottenere spade potenti da
                                          vendere o da utilizzare voi stessi. La creazione degli oggetti è la parte più
                                          interessante del fabbro. Per la creazione degli oggetti si deve avere una Ricetta
                                          (che potrete procurarvi da Heimdall) e gli Ingredienti che troverete indicati nella
                                          ricetta stessa. Una volta che avrete tutto, andate alla forgia e modellate l'oggetto
                                          migliore che potete. La produzione d'oggetti consuma una piccola quantità di abilità
                                          (punti carriera). Una caratteristica interessante della professione è la possibilità
                                          di eseguire delle riparazioni. In questo modo potrete accumulare una paga che
                                          Oberon vi consegnerà quando ne farete richiesta!`n");
                                   addnav ("`7Torna indietro","newfaq.php?op=Prosegui&op1=Carriera");
                                   break;
                             case "Mago":
                             $session['user']['specialinc']="";
                                   output ("`n`& Dopo aver fatto visita a Ithine potrete diventare iniziati. Potrete solamente
                                          esercitarvi nel controllo del mana, consumando turni e migliorare così nell'arte della
                                          manipolazione dell'energia (aumentando i vostri punti carriera), ed una volta che Ithine vi
                                          giudicherà pronti vi nominerà Stregoni e successivamente Maghi. Potrete così creare piccoli
                                          incantesimi. Una volta Maghi potrete creare incantesimi così potenti da vendere o da
                                          utilizzare voi stessi. Per la creazione degli incantesimi si deve avere una Pergamena e i
                                          materiali che troverete indicati nella pergamena stessa. Una volta che avrete tutto, andate
                                          al laboratorio e modellate l'energia per creare ciò di cui avete bisogno. La creazione
                                          consuma una piccola quantità di abilità (punti carriera). Una caratteristica interessante
                                          della professione è la possibilità di eseguire delle riparazioni di oggetti magici.`n");
                                   addnav ("`7Torna indietro","newfaq.php?op=Prosegui&op1=Carriera");
                                   break;
                             case "Sgrios":
                             $session['user']['specialinc']="";
                                   output ("`n`&  Seguendo la carriera religiosa, potrete pregare la vostra divinità affinché vi aiuti
                                          a convertire gli infedeli. Sarete nel fulcro della battaglia tra le sette e dovrete fare di
                                          tutto affinché prevalga la vostra. All'inizio sarete dei semplici seguaci, ma pregando
                                          (accumulando punti carriera) Sgrios, e facendo alcuni doni passerete al rango di accoliti e
                                          poi di chierici, fino ad arrivare a sacerdoti. Quando il vostro nome farà tremare gli
                                          avversari, riceverete il titolo di Sommi Chierici. Oltre tale titolo, se sarete il più fedele tra
                                          tutti i seguaci, avrete poteri supplementari, e potrete diventare il Gran
                                          Sacerdote. Avrete così la possibilità di guidare le legioni della vostra divinità alla
                                          vittoria. Da Sommi Chierici o Gran Sacerdote, potrete celebrare la messa. Il rango di
                                          Gran Sacerdote vi permetterà anche di punire, chi infrangerà le regole della vostra
                                          comunità. Se doveste trovarmi in difficoltà, potrete anche supplicare la divinità perché
                                          venga in vostro aiuto. Usando così un po' di punti carriera potrete ricevere dalla vostra
                                          divinità, soldi o gemme o oggetti potenti.`n");
                                   addnav ("`7Torna indietro","newfaq.php?op=Prosegui&op1=Carriera");
                                   break;
                             case "Karnak":
                             $session['user']['specialinc']="";
                                   output ("`n`& Seguendo la carriera religiosa, potrete pregare la vostra divinità affinché vi aiuti a
                                          convertire gli infedeli. Sarete nel fulcro della battaglia tra le sette e dovrete fare di tutto a
                                          finché prevalga la vostra. All'inizio sarete dei semplici invasati, ma pregando (accumulando punti
                                          carriera) Karnak, e facendo alcuni doni passerete al rango di fanatici e poi di posseduti, fino
                                          ad arrivare a Maestri delle Tenebre. Quando il vostro nome farà tremare gli avversari, riceverete
                                          il titolo di Portatore di Morte. Oltre tale titolo, se sarete il più fedele tra tutti i seguaci,
                                          avrete poteri supplementari, e potrete diventare il Falciatore di Anime. Avrete così la possibilità
                                          di guidare le legioni della vostra divinità alla vittoria. Da Portatori di Morte o Falciatore di
                                          Anime, potrete celebrare la messa. Il rango di Falciatore di Anime vi permetterà anche di punire,
                                          chi infrangerà le regole della vostra comunità. Se doveste trovarmi in difficoltà, potrete anche
                                          supplicare la divinità perché venga in vostro aiuto. Usando così un po' di punti carriera potrete
                                          ricevere dalla vostra divinità, soldi o gemme o oggetti potenti.`n");
                                   addnav ("`7Torna indietro","newfaq.php?op=Prosegui&op1=Carriera");
                                   break;
                             case "Grande Drago Verde":
                             $session['user']['specialinc']="";
                                   output ("`n`& Seguendo la carriera religiosa, potrete pregare la vostra divinità affinché vi aiuti
                                          a convertire gli infedeli. Sarete nel fulcro della battaglia tra le sette e dovrete fare di
                                          tutto a finché prevalga la vostra. All'inizio sarete dei semplici stallieri, ma pregando
                                          (accumulando punti carriera) Grande Drago Verde, e facendo alcuni doni passerete al rango di
                                          scudieri e poi di cavalieri dei Draghi, fino ad arrivare a Cancellieri dei Draghi. Quando il
                                          vostro nome farà tremare gli avversari, riceverete il titolo di Dominatore di Draghi. Se sarete
                                          il più fedele alla vostra divinità, essa ti darà dei poteri supplementari, permettendoti di
                                          diventare un Dominatore dei Draghi. Avrete così la possibilità di guidare le legioni della
                                          vostra divinità alla vittoria. Da Cancellieri dei Draghi o Dominatore dei Draghi, potrete
                                          celebrare la messa. Il rango di Dominatore dei Draghi vi permetterà anche di punire, chi
                                          infrangerà le regole della vostra comunità. Se doveste trovarmi in difficoltà, potrete anche
                                          supplicare la divinità perché venga in vostro aiuto. Usando così un po' di punti carriera
                                          potrete ricevere dalla vostra divinità, soldi o gemme o oggetti potenti.`n");
                                   addnav ("`7Torna indietro","newfaq.php?op=Prosegui&op1=Carriera");
                                   break;
                             }
                             break;
             }
             break;

case "Cimitero":
     output ("`n`# Ma cosa succede se per caso un mostro dovesse uccidervi in foresta?? `&Niente paura,
            il gioco continua. Si, il vostro corpo sarà ridotto a brandelli, ma la vostra anima verrà
            spedita al cimitero. Qui troverete altre anime disgraziate che vi faranno compagnia. Inoltre
            potrete combattere contro altre anime, guadagnandovi così il rispetto di Ramius. Accumulati
            tot favori, potrete utilizzarli per tormentare qualche anima, o pure per ritornare in
            `\$vita`&. Tuttavia, anche se in questo modo avrete un Newday, i benefici derivanti da esso
            saranno molto minori. Avrete infatti per esempio meno turni rispetto al normale. Se invece
            non doveste riuscire ad accumulare abbastanza favori per tornare in vita, dovrete aspettare
            il prossimo turno che vi riporterà automaticamente in vita.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Informazioni utili":
     output ("`n`& Oltre a questa guida, che spero troverete interessante, vi sono altri modi per
            conoscere il mondo di LoGD o LotGD. Tra questi rientra sicuramente l'output `7Osservatorio
            `&. Guardando attraverso il binocolo, potrete scoprire molte cose interessanti. Anche
            l'`7interazione tra player`& vi renderà più sapienti per quanto riguarda il gioco, visto
            che lo scopo principale è proprio questo. Altra fonte di grande sapienza è il `7druido`&
            che si trova al monastero. Qui troverete informazioni e tabelle che vi saranno molto utili.
            (io le consulto ancora adesso, soprattutto quella delle carriere, hehe). Per trovare invece
            informazioni sulle razze o sulle abilità basta che facciate una visita alla `7biblioteca`&,
            che si trova sempre al monastero. In alcuni casi avrete anche la possibilità di visitare
            `7Taverna del Cavallo Nero`& dove potrete scoprire interessanti informazioni sui vostri
            avversari, ma anche sui vostri amici.`n");
     output ("`n`&  `n");
     output ("`n`& Molto spesso, vi capiterà anche di avere dei problemi che non sono compresi in questa
            guida, probabilmente saranno bug e farete bene ad avvisare gli admin. Ma non è di questo
            che vi voglio parlare. Per migliorare le vostre informazioni sul mondo di LoGD, è necessario
            seguire il forum. Troverete così che molte domande che vi ponete sono condivise da altri
            giocatori. Discutendo insieme di ciò che vi è gradito e non vi soddisfa, potremo insieme
            trovare la soluzione e migliorare il gioco. Per cui vi consiglio caldamente di visitare
            questo forum `n----> `@www.ogsi.it`@");
     output ("`n`&  `n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Livello e DK":
     output ("`n`& Combattendo in foresta, affrontando i vari mostri, come in ogni gioco di ruolo che si
            rispetti, vedrete crescere il vostro personaggio. In termini di gioco, questo significa che
            guadagnerete dell'esperienza. Quando la barra di questa sarà interamente verde, potrete
            andare al Campo d'allenamento di Bluespring e sfidare il vostro maestro. Se riuscite a
            vincere contro di lui, avanzerete di livello guadagnando così nuovi poteri, oltre al fatto
            che il vostro attacco, difesa e i vostri Hp, aumenteranno. Arrivati al livello 15, potrete
            sfidare il famigerato Drago Verde. Abbattendolo, otterrete un nuovo titolo, per esempio da
            contadino passerete ad esploratore. Con la sconfitta del drago, tutti i benefici che avrete
            guadagnato con la sconfitta dei maestri (solo quelli ottenuti direttamente dalla loro
            sconfitta) si azzereranno, ma guadagnerete 5 punti fascino e avrete la possibilità di
            scegliere se prendere 1 punto attacco, 1 punto difesa, 5 punti ferita, o 1 turno in più ogni
            nuovo giorno (a voi la scelta). Con l'avanzare dei titoli, avrete a disposizione dei punti
            quest che potrete utilizzare per fare alcuni eventi speciali, che troverete andando a
            'Roccia Curiosa', `1Se andate dal druido, potrete sapere quale sarà il vostro prossimo
            titolo e la quest che potete fare al vostro livello e titolo. `& Ogni volta che ucciderete
            il drago, potrete anche cambiare le vostre abilità speciali e perfino razza. Con l'aumentare
            di uccisioni, DK (dragon kills), potrete scegliere abilità e razze sempre più potenti. `\$
            Ricordatevi che se supererete di molto l'esperienza che serve ad affrontare un maestro, lui
            vi verrà a cercare. Se avrete più di 60 giorni di gioco, il drago vi verrà a
            cercare.`n"); // modificato "Se avrete più di 60 giorni di gioco AL LIVELLO 15"
     output ("`n`& Tabella dei maestri per avanzare ai vari livelli`n");
     output("<table cellspacing=2 cellpadding=2 align='center'>",true);
            output("<tr bgcolor='#666600'><td align='center'>`&`bLivello`b<td align='center'>`&`bMaestri`b</td><td align='center'>`&`bArma utilizzata`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`72`b</td> <td align='left'>`&`bMireraband`b<td align='center'>               `&`bPiccolo Pugnale`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`73`b</td> <td align='left'>`&`bFie`b<td align='center'>                      `&`bSpada Corta`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`74`b</td> <td align='left'>`&`bGlynyc`b<td align='center'>                   `&`bMazza Ferrata`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`75`b</td> <td align='left'>`&`bGuth`b<td align='center'>                     `&`bBastone Ferrato`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`76`b</td> <td align='left'>`&`bUnélith`b<td align='center'>                  `&`bControllo Mentale`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`77`b</td> <td align='left'>`&`bAdwares`b<td align='center'>                  `&`bAscia da Battaglia Nanica`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`78`b</td> <td align='left'>`&`bGerrard`b<td align='center'>                  `&`bArco da Battaglia`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`79`b</td> <td align='left'>`&`bCeiloth`b<td align='center'>                  `&`bSpadone Orchesco`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`710`b</td><td align='left'>`&`bDwiredan`b<td align='center'>                 `&`bSpade Gemelle`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`711`b</td><td align='left'>`&`bSensei Noetha`b<td align='center'>            `&`bArti Marziali`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`712`b</td><td align='left'>`&`bCelith`b<td align='center'>                   `&`bAureole da Lancio`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`713`b</td><td align='left'>`&`bGadriel the Elven Ranger`b<td align='center'> `&`bArco Lungo Elfico`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`714`b</td><td align='left'>`&`bAdoawyr`b<td align='center'>                  `&`bSpadone Gigante`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`b`715`b</td><td align='left'>`&`bYoresh`b<td align='center'>                   `&`bTocco di Morte`b</td></td></tr>",true);
     output("</table>",true);
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Merick":
     output ("`n`& Tra le varie attrazioni che scoprirete nel villaggio, troverete anche le stalle di
            Merick, un simpatico nano che vi potrà vendere per qualche gemma e qualche soldo, delle
            creature che vi faranno compagnia durante la vostra permanenza a Rafflingate. Questi
            animali, dal semplice usignolo al mitico pegaso, vi forniranno aiuto distraendo
            l'avversario, curandovi, attaccando l'avversario, etc. (andate dal druido per scoprire le
            qualità dei vari animali). Le creature, oltre al fatto che avranno i poteri ricaricati ogni
            nuovo giorno, potranno riacquistare i propri poteri anche se verranno nutrite.");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Miniera":
     output ("`n`&  Per entrare in miniera avete bisogno di un piccone. Potete trovarlo da Brax oppure al
            mercatino di Oberon se qualche fabbro del luogo l'ha messo in vendita. Una volta comprato
            il piccone avrete accesso alla miniera dove dovrete decidere quanto lavorare: ogni turno di
            gioco vale 5 turni miniera. Ad ogni 'buon colpo' verrà estratto un pezzetto di materiale
            che finirà dritto da Oberon (voi non lo vedrete nemmeno e non guadagnerete nulla da esso).
            In base al vostro livello, ogni turno miniera utilizzato vi porterà un pò di monete (ve lo
            dice l'individuo di guardia ogni volta). Ovviamente riceverete degli extra ogni tanto: potrà
            capitarvi di trovare una o più gemme, e piccole altre sorprese... tra le quali un bel masso
            dritto in testa che vi regalerà un viaggio turistico nella terra delle ombre. Più lavorerete
            e più avrete esperienza 'da minatore'... l'esperienza vi sarà detta pagando il vecchio
            tirchiaccio in miniera. Un esercito di minatori esperti potrà finalmente spostare l'enorme
            macigno che si trova in profondità (le indicazioni ve le dirà il vecchio giù), e svelando
            il tesoro che nasconde. E' impossibile spostare il macigno da soli: solo più minatori
            esperti possono spostarlo se lavorano all'unisono.`n");
     output ("`n`& Tabella tunnel");
            output("<table cellspacing=2 cellpadding=2 align='left'>",true);
            output("<tr bgcolor='#00FFFF'><td align='center'>`\$`bTunnel`b</td><td align='center'>`\$`bEstrazione`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`bTunnel illuminato`b</td>    <td align='center'>`bCarbone`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`bTunnel buio`b</td>          <td align='center'>`bFerro`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`bTunnel in profondità`b</td> <td align='center'>`bRame`b</td></tr>",true);
            output("</table>",true);
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "MoTD e MP":
     output ("`n`& Che cosa sono i MoTD?? In parole povere sono tutti gli avvisi che vi danno gli admin:
            aggiunto nuovo evento, organizzazione di un torneo, etc... Se ad un nuovo giorno, dovessero
            esserci nuovi MoTD, la finestra dovrebbe aprirvisi automaticamente. In caso contrario
            significa che sono bloccati i pop-pup del sito. Il sito non contiene niente che potrebbe
            danneggiarvi in alcun modo, quindi vi consiglio di consentire i pop-pup di questo sito.`n");
     output ("`n`& Vi ho già parlato di quanto sia importante l'interazione tra player. Ovviamente ci sono
            vari modi per interagire, tra cui parlare al villaggio o in altri luoghi. Ci sono però altri
            metodi, tra cui il forum di ogsi, vi ricordo il sito `@www.ogsi.it `&. Quello però di cui
            voglio parlarvi sono gli MP o messaggi privati. Grazie a questa funzione di LoGD, potrete
            mandare dei messaggi ad altri player, senza che nessun altro possa leggerli tranne il
            destinatario.`n");
     output ("`n  `n");
     output ("`n`\$ ATTENZIONE `&--- Vi cito alcuni frammenti del regolamento, che potrete trovarlo al forum di ogsi. Ricordatevi che chi dovesse violare questo regolamento sarà bannato, per un numero di giorni dipendente dalla gravità della violazione. Se, scaduto il ban, la situazione dovesse ripresentarsi, verranno presi provvedimenti più drastici (di competenza degli admin).  `n");
     output ("`n`@1)`& Non è consentito insultare, offendere, minacciare altri utenti con post pubblici o
            con messaggi privati.`n
            `@2)`& Non è consentito un atteggiamento razzista o manifestazioni di ideologie razziste.`n
            `@3)`& Non è consentito un linguaggio blasfemo.`n
            `@10)`& Come recita la legislazione italiana: 'La pubblicazione di corrispondenza privata,
            interamente o a parti, è vietata, fatto salvo il caso di espresso consenso di tutti i citati
            nel documento stesso. La violazione di cio' può dare luogo a responsabilità per danni
            d'immagine e/o patrimoniali, ai sensi dell'art. 2043 del codice civile, nei confronti delle
            persone coinvolte'. A questo si aggiunge il ban.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Odore/vescica/fame":
     output ("`n`& Vi chiederete sicuramente cosa siano le barre che vedete a sinistra, nella scheda del
            vostro personaggio. Esse sono, come indica il nome la `@fame`&, la `\$vescica`& e l'`^odore
            `&.`n");
     output ("`n`& La `\$vescica`& è contrassegnata da una barra rossa; quando arriverà alla fine, vi farete
            la pipì addosso e tutto il villaggio lo verrà a sapere, potete quindi capire che umiliazione.
            Per evitare questo, potrete semplicemente andare ai bagni, che si trovano nella foresta.
            Avrete quindi la possibilità di scegliere la qualità dei bagni; ovviamente i gabinetti
            privati sono costosi.`n");
     output ("`n`& Durante la vostra permanenza nel mondo di LoGD dovrete anche fare attenzione all'`^
            odore`& che emanate, indicato dalla barra gialla. Se il valore dovesse arrivare alla fine,
            puzzerete come un caprone di montagna e guadagnerete il titolo di PigPen.
            Questo titolo vi rimarrà finché non ucciderete il drago.`n");
     output ("`n`& Parliamo ora della `@fame`&, poiché essa è la caratteristica più importante. Infatti,
            se la barra, contrassegnata dal colore verde, dovesse arrivare alla fine, cominceremo a
            perdere le forze finché non ci rimarrà solo un briciolo di energia in corpo, e ciò vuol dire
            che saremo molto vulnerabili. Per ricaricarci e non sentire i morsi della fame, basta che
            mangiamo qualcosa e ovviamente, più caro sarà il cibo più diminuirà la barra della fame. Con
            l'aumentare dell'esperienza che accumulerete, ogni volta che verrà sconfitto il famigerato
            drago verde, il costo del cibo aumenterà.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "PVP":
     output ("`n`& All'inizio di questa guida vi ho spiegato cosa siano i turni di gioco e a cosa
            servono. Vi sono però altri tipi di turni che ho omesso di spiegarvi. Sono dei turni molto
            speciali e soprattutto pochi. Vengono comunemente definiti PVP e ne avrete 2 a disposizione
            ogni nuovo giorno. Questi turni servono ad uccidere gli altri giocatori. `#Vi chiederete tutto
            qua?? `&In verità no, questi turni vi permetteranno di derubare le tenute o fare le quest
            (giocando capirete che queste sono molto importanti). Insomma, vi permetteranno di crescere
            molto più velocemente. Utilizzateli quindi con molta attenzione, perché anche uno solo dei
            PVP vi può cambiare la vita.. Ho enfatizzato troppo, ma avete capito il concetto.`n
            I PVP potrete utilizzarli nell'arena (dove non rischiate di morire) oppure potrete
            utilizzarli per uccidere altri players. Tra i vari modi, vi è quello tradizionale, ovvero
            cercare qualche povero sventurato che dorme nei campi; potrete provare a corrompere il
            locandiere Cedrik perché vi dia le chiavi per intrufolarvi nelle varie stanze. Infine
            potrete utilizzarli per saccheggiare le tenute. Ricordatevi che `\$uccidendo, la vostra
            cattiveria aumenterà, `&e se supererete la soglia dei 100 punti, lo sceriffo comincerà a
            darvi la caccia. Questo comporterà alcuni malus, che non intendo svelarvi ^_^. Oltre a
            questo, riceverete una taglia per la vostra testa, e quindi personaggi più forti, verranno
            a farvi visita per prendere la vostra testa. Per scontare la cattiveria, dovete recarvi in
            prigione dallo sceriffo. Per ogni giorno che passerete in prigione vi verranno scalati 20
            punti cattiveria (`b`3per farvi scalare la cattiveria, dovete collegarvi`&`b) e ogni giorno vi
            verrà scalato 1 punto cattiveria.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Reincarnazioni":
     output ("`n`& Cos'è una reincarnazione?? Semplice, una volta arrivati a titoli ultraterreni vi viene
            data la possibilità di ricominciare da capo, partendo nuovamente con la zappa sulle spalle.
            Una volta superato il titolo di Imperatore, hai la possibilità di tornare contadino. Vi
            starete chiedendo: '`#Come si fa??`&'Un momento e ve lo dico... Ci si può reincarnare avendo con
            se l'`^uovo d'oro`&. Quest'uovo si può trovare in foresta oppure addosso ad un altro player.
            (attenti però che sia l'ovetto che cercate). Quando sarete in possesso dell'`^uovo`&, lo potrete
            rendere ad una signora molto disponibile, chiamata `5Javella`& (si può trovare ai giardini), che
            fa la collezione di `^uova d'oro`&. E. puff... per incanto vi ritroverete contadini, però
            colorati (ovvero il vostro nick si colorerà come per incanto con un colore predefinito stile
            Admin). Attenzione però, `5Javella`& si potrebbe prendere l'`^uovo`& senza darvi nulla in cambio!!
            (Piccolo consiglio: più DK avete, più è facile che la reincarnazione riesca). Ora vi
            chiederete: '`#Ma a che pro tornare contadino???`&' Beh in realtà ci sono vantaggi e svantaggi...
            Vi posso dire che perderete qualcosa in termini di attacco, difesa e hp del vostro pg... Però
            potrete rifarvi in seguito (a voi capire come)... Tra i vantaggi della reincarnazione c'è
            senz'altro la possibilità di potersi costruire una casa nelle tenute reali per proteggervi
            durante il sonno. Come ben sappiamo però i cavalieri malintenzionati non si fermano davanti a
            nulla. Al termine della costruzione della casa avrete 9 chiavi da distribuire ad altrettanti
            guerrieri, per difendere al meglio la vostra nuova costruzione. Le tenute sono difese da un
            vigilante che si parerà tra voi e gli eventuali assalitori. Tenete presente che tutti gli
            abitanti della casa hanno accesso alla cassaforte e che avrete in ogni caso la possibilità
            di togliere le chiavi a chi le avete distribuite in caso di ripensamento. Ovviamente ci sono
            anche altri vantaggi. Ma lascio a voi scoprire quali. Se vi dico tutto io che giocate a fare?
            Buona fortuna e buon divertimento.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Terre dei draghi":
     output ("`n`&  Cosa sono le terre dei Draghi?? Sono territori leggendari accessibili solo ai
            combattenti più esperti (con almeno 18 DK o reincarnati), in possesso di un drago. Ma fate
            attenzione, non è facile allevare un drago... Quindi siate molto vigili!!! Una volta
            comprato il drago dall'apposito mercante (vi ricordo che potrete domarlo solo se il vostro
            Cavalcare sia pari o superiore al carattere del drago), potrete esplorare le leggendarie
            terre dei draghi! Potrete accedere e viaggiare col vostro draghetto da Rafflingate verso
            le terre dei draghi spendendo 10 turni (ricordatevi che se la vostra chiesa dovesse vincere,
            avrete dei vantaggi anche nella Terra dei Draghi). Arrivati a destinazione potrete
            esplorarle (costo tre turni foresta per ogni esplorazione). In queste esplorazioni potrete
            trovare molte cose, tra cui territori leggendari da difendere in onore della vostra divinità!
            Questo porterà vantaggi sia alla vostra setta che ai vostri punti carriera!! Vi potrà
            capitare anche di trovare altri draghi contro cui combattere: attenzione!!! Se sono troppo
            forti per voi lasciate stare o rischierete di compromettere l'aspetto del vostro drago, che
            morirà! Quando troverete un territorio leggendario dovrete difenderlo da possibili incursioni
            nemiche. Come?? Dovrete attivare difese e alzare barriere, al massimo potrete mettere il
            vostro drago a protezione! Ricordate, per lo scontro fra le chiese questo influirà parecchio!
            C'è anche la possibilità di poter rafforzare il proprio drago temporaneamente recandosi da
            Erold, che, dietro compenso, vi aiuterà a potenziarlo. Infine avrete la possibilità di
            trovare la Leggendaria Città dei Draghi... Su quest'ultima lascio scoprire tutto a voi!!
            Ahh!!!! Mi stavo per scordare, il drago crescerà con voi, da cucciolo, fino ad antico.
            Ricordatevi che per la sua crescita, è molto importante il suo aspetto, che determinerà
            la sua forza dopo la crescita.`n");
     output ("`n `n");
     output ("`n  Tabella dei draghi");
     output("<table cellspacing=2 cellpadding=2 align='center'>",true);
            output("<tr bgcolor='#FF0000'><td align='center'>`&`bTipo drago`b</td><td align='center'>`&`bAttacco`b</td><td align='center'>`b`&Difesa`b</td><td align='center'>`b`&Soffio`b</td><td align='center'>`b`&Carattere`b</td><td align='center'>`b`&Vita`b</td><td align='center'>`b`&Bonus territorio`b</td><td align='center'>`b`&Crescita`b</td><td align='center'>`b`&Livello Drago`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bNero`b</td>      <td align='center'>`&`b10 ~ 15`b</td>  <td align='center'>`&`b5 ~ 10`b</td>   <td align='center'>`&`b10 ~ 15`b</td>  <td align='center'>`&`b5 ~ 15`b</td>   <td align='center'>`&`b20 ~ 30`b</td>  <td align='center'>`&`b1 ~ 5`b</td>  <td align='center'>`&`b3 ~ 5`b</td>  <td align='center'>`&`b1`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bRosso`b</td>     <td align='center'>`&`b5 ~ 10`b</td>   <td align='center'>`&`b5 ~ 10`b</td>   <td align='center'>`&`b15 ~ 20`b</td>  <td align='center'>`&`b5 ~ 15`b</td>   <td align='center'>`&`b20 ~ 30`b</td>  <td align='center'>`&`b1 ~ 5`b</td>  <td align='center'>`&`b3 ~ 5`b</td>  <td align='center'>`&`b1`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bBlu`b</td>       <td align='center'>`&`b5 ~ 10`b</td>   <td align='center'>`&`b10 ~ 15`b</td>  <td align='center'>`&`b10 ~ 15`b</td>  <td align='center'>`&`b5 ~ 15`b</td>   <td align='center'>`&`b20 ~ 30`b</td>  <td align='center'>`&`b1 ~ 5`b</td>  <td align='center'>`&`b3 ~ 5`b</td>  <td align='center'>`&`b1`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bVerde`b</td>     <td align='center'>`3`b5 ~ 10`b</td>   <td align='center'>`3`b5 ~ 10`b</td>   <td align='center'>`3`b10 ~ 15`b</td>  <td align='center'>`3`b5 ~ 15`b</td>   <td align='center'>`3`b30 ~ 40`b</td>  <td align='center'>`3`b1 ~ 5`b</td>  <td align='center'>`3`b3 ~ 5`b</td>  <td align='center'>`3`b2`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bBianco`b</td>    <td align='center'>`3`b5 ~ 10`b</td>   <td align='center'>`3`b5 ~ 10`b</td>   <td align='center'>`3`b10 ~ 15`b</td>  <td align='center'>`3`b10 ~ 20`b</td>  <td align='center'>`3`b25 ~ 35`b</td>  <td align='center'>`3`b1 ~ 5`b</td>  <td align='center'>`3`b3 ~ 5`b</td>  <td align='center'>`3`b2`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bZombie`b</td>    <td align='center'>`3`b15 ~ 20`b</td>  <td align='center'>`3`b5 ~ 10`b</td>   <td align='center'>`3`b10 ~ 15`b</td>  <td align='center'>`3`b5 ~ 15`b</td>   <td align='center'>`3`b20 ~ 30`b</td>  <td align='center'>`3`b1 ~ 5`b</td>  <td align='center'>`3`b3 ~ 5`b</td>  <td align='center'>`3`b2`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bScheletro`b</td> <td align='center'>`2`b10 ~ 15`b</td>  <td align='center'>`2`b5 ~ 10`b</td>   <td align='center'>`2`b15 ~ 20`b</td>  <td align='center'>`2`b10 ~ 20`b</td>  <td align='center'>`2`b20 ~ 30`b</td>  <td align='center'>`2`b1 ~ 5`b</td>  <td align='center'>`2`b3 ~ 5`b</td>  <td align='center'>`2`b3`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bBronzo`b</td>    <td align='center'>`2`b5 ~ 10`b</td>   <td align='center'>`2`b15 ~ 20`b</td>  <td align='center'>`2`b10 ~ 15`b</td>  <td align='center'>`2`b10 ~ 20`b</td>  <td align='center'>`2`b20 ~ 30`b</td>  <td align='center'>`2`b1 ~ 5`b</td>  <td align='center'>`2`b3 ~ 5`b</td>  <td align='center'>`2`b3`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`bArgento`b</td>   <td align='center'>`2`b5 ~ 10`b</td>   <td align='center'>`2`b10 ~ 15`b</td>  <td align='center'>`2`b10 ~ 15`b</td>  <td align='center'>`2`b10 ~ 20`b</td>  <td align='center'>`2`b25 ~ 35`b</td>  <td align='center'>`2`b1 ~ 5`b</td>  <td align='center'>`2`b3 ~ 5`b</td>  <td align='center'>`2`b3`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`6`bOro`b</td>     <td align='center'>`6`b10 ~ 15`b</td>  <td align='center'>`6`b10 ~ 15`b</td>  <td align='center'>`6`b10 ~ 15`b</td>  <td align='center'>`6`b5 ~ 15`b</td>   <td align='center'>`6`b25 ~ 35`b</td>  <td align='center'>`6`b1 ~ 5`b</td>  <td align='center'>`6`b4 ~ 6`b</td>  <td align='center'>`6`b4`b</td></tr>",true);
     output("</table>",true);
     output ("`n `n");
     output ("`n  Tabella per il potenziamento del drago in base all'aspetto.");
     output("<table cellspacing=2 cellpadding=2 align='left'>",true);
            output("<tr bgcolor='#FF0000'><td align='center'>`&`bAspetto`b</td><td align='center'>`&`bBonus`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`\$`bPessimo`b</td>  <td align='center'>`\$`bX 1`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`%`bBrutto`b</td>   <td align='center'>`%`bX 2`b</td></td></tr>",true);
            output("<tr class='trlight'><td align='center'>`@`bNormale`b</td>  <td align='center'>`@`bX 3`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`6`bBuono`b</td>    <td align='center'>`6`bX 4`b</td></tr>",true);
            output("<tr class='trlight'><td align='center'>`&`bOttimo`b</td>   <td align='center'>`&`bX 5`b</td></tr>",true);
     output("</table>",true);
     addnav ("`7Torna indietro","newfaq.php");
     break;

case "Tornei":
     output ("`n`& Tra le varie interazioni tra player, scoprirete che il mondo di LoGD è pieno di
            attrazioni. Tra le più mozzafiato, vi aspettano i tornei. Questi potranno essere permanente,
            come il `!Torneo delle medaglie`& o quello di `!Logd`&. Scoprirete anche tornei nuovi che
            verranno organizzati per la festa di una divinità, come per esempio le `!gare all'ippodromo
            `&. Insomma, ci saranno molte occasioni in cui poter rivaleggiare con gli altri personaggi
            sia individualmente che in gruppo. Vi auguro perciò un buon divertimento.`n");
     addnav ("`7Torna indietro","newfaq.php");
     break;
     break;
}
page_footer();
?>

