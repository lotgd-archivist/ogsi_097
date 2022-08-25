<?php
/*******************************************
/ termini.php
/ Originally by Excalibur (www.ogsi.it)
/ 29 June 2007
/
--------------------------------------------
/ Version History:
/ Ver. 1.0 created by Excalibur (www.ogsi.it)
********************************************
/ Originally by: Excalibur
*/

require_once("common.php");
page_header("TERMINI DI UTILIZZO");
output("`c`b`@<big>TERMINI E CONDIZIONI DI UTILIZZO DEI SERVIZI`nDI LEGEND OF THE GREEN DRAGON (LoGD)</big>`n`n`c`b",true);
output("`b`^1.    ACCETTAZIONE DEI TERMINI DI UTILIZZO`b`n
`#1.1`6   Usando i servizi messi a disposizione da LoGD, l’utente dichiara di aver attentamente letto ed espressamente accettato tutti i termini e le condizioni del servizio, qui di seguito espressamente indicate.`n
`#1.2`6   Il browser game, nel prosieguo denominato ”LoGD” è fornito da ad ogni utente, secondo i termini e le condizioni di utilizzo del presente accordo.`n`n
`b`^2.    INFORMAZIONI SULL’ISCRIZIONE E DESCRIZIONE DEL SERVIZIO`b`n
`#2.1`6      LoGD offre la possibilità di giocare e dialogare in tempo reale con altri utenti collegati senza sostenere alcun costo per la messa a disposizione del servizio. In nessun caso può essere ritenuto responsabile del mancato o inesatto adempimento da parte dell’utente di ogni eventuale procedura di legge o di regolamento in relazione a tale accordo.`n`n
`^`b3.    CONTENUTO DEI MESSAGGI`b`n
`#3.1`6 L’utente è l’unico responsabile del contenuto dei messaggi trasmessi per il tramite di LoGD e, inoltre, è l’unico e personale responsabile delle eventuali conseguenze pregiudizievoli che tali messaggi potrebbero comportare a terze persone, e ciò con riferimento alla vigente normativa in materia civile e penale.`n
`#3.2`6 L’utente si impegna a non usare il servizio per scopi illegali ovvero contro la morale o l’ordine pubblico, per trasmettere materiale pornografico e/o comunque, osceno, volgare, diffamatorio, abusivo, nonché a non trasmettere materiale e/o messaggi che incoraggino terzi a mettere in atto una condotta illecito e/o criminosa passibile di responsabilità penale o civile. Si impegna inoltre a non far usare a terzi il proprio login e password qualora registrato. E’ comunque vietato utilizzare il servizio per contravvenire in modo diretto o indiretto alla vigenti Leggi dello Stato italiano e comunque di altro Stato estero. L’utente si impegna, inoltre, a non utilizzare il servizio, per inviare messaggi commerciali.`n
`#3.3`6 LoGD si riserva il diritto di cancellare, in qualsiasi momento e senza preavviso, la registrazione dell’utente di LoGD qualora venisse a conoscenza ovvero determinasse, a propria esclusiva discrezione, che l’utente abbia, ovvero stia violando le prescrizioni previste dal presente accordo o dalla normativa vigente.`n
`#3.4`6 In caso di violazione delle prescrizioni contenute nel presente accordo, l’utente si impegna comunque a manlevare e mantenere integralmente indenne LoGD per qualsiasi responsabilità civile e penale derivante dall’utilizzo illecito, improprio o anormale del servizio, anche se causato da terzi attraverso la password dell’Utente e da ogni e qualsiasi richiesta, anche di risarcimento danni, proposta nei confronti della stessa a seguito della condotta dell’utente.`n
`#3.5`6 Alcuni utenti che dispongono di privilegi particolari, definiti “moderatori” possono intervenire per silenziare temporaneamente da LoGD gli account il cui comportamento non sia conforme ai termini dell’Accordo o alle norme della Netiquette. La cancellazione definitiva dell’account in caso ne ricorressero le condizioni, verrà effettuato esclusivamente dagli Admin di LoGD secondo le condizioni di cui al punto 1.3.`n`n
`b`^4.  ACCOUNT, NICKNAME E PASSWORD`b`n
`#4.1`6 L’utente è il solo ed unico responsabile del mantenimento e della riservatezza del proprio nickname e della propria password e, conseguentemente, rimane il solo ed unico responsabile per tutti gli usi del suo nick e della sua password, siano essi autorizzati ovvero non autorizzati dall’utente stesso.`n
`#4.2`6 In relazione a quanto sopra, l’utente si impegna a comunicare immediatamente a LoGD eventuale furto o smarrimento ovvero uso non autorizzato da parte di terzi del proprio nickname o password non appena venutone a conoscenza, impegnandosi comunque sin d’ora a manlevare a mantenere indenne LoGD da ogni e qualsiasi richiesta, anche di risarcimento danni, proposta e/o derivante, direttamente ovvero indirettamente, dal sopra indicato uso o abuso del servizio.`n
`#4.3`6 LoGD non contatterà mai l’utente per chiedere nickname e password o altre informazioni riservate. Ogni richiesta di questo tipo da parte di terzi è da considerare come una scorretta intrusione di altri utenti nella sfera di riservatezza personale dell’utente è può essere segnalata a ");
output("<a href='mailto:luke@ogsi.it'>luke@ogsi.it</a> oppure a  <a href='mailto:excalibur@ogsi.it'>excalibur@ogsi.it</a>`n`n",true);
output("`^`b5.  MODIFICA, SOSPENSIONE O INTERRUZIONE DEL SERVIZIO`b`n
`#5.1`6 LoGD potrà modificare in qualsiasi momento ed a propria esclusiva discrezione i termini di utilizzo e le condizioni del servizio.`n
`#5.2`6 LoGD si riserva, inoltre, il diritto di modificare, sospendere o interrompere il servizio di LoGD e del Forum o comunque anche la sola autorizzazione all’utilizzo del nickname dell’utente, declinando sin d’ora ogni eventuale responsabilità nei confronti dell’utente ovvero di terzi.`n
`#5.3`6 Resta inteso che LoGD non sarà in alcun caso e per alcun motivo considerata responsabile nei confronti dell’utente ovvero di terze persone per la avvenuta interruzione o cessazione del Servizio
");
$session['user']['lasthit'] = date("Y-m-d H:i:s");
output("<form action='newday.php' method='POST'><input type='submit' class='button' value='Prosegui'>`n",true);
addnav("","newday.php");

rawoutput("<br><div style=\"text-align: right ;\"><a href=\"http://www.ogsi.it\" target=\"_blank\"><font color=\"#33FF33\">Termini Utilizzo LoGD (http://www.ogsi.it)</font></a><br>");
page_footer();
?>

