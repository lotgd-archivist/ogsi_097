<?php
page_header("Lo Sceriffo");
output("`c`b`&Lo Sceriffo`0`b`c`n`n");
if ($_GET['op']==""){
   if ($session['user']['evil'] < 100){
       $premio = 1000 - (10 * $session['user']['evil']);
       output("`2Girovagando nella foresta ti imbatti nello Sceriffo del villaggio. E' in perlustrazione alla ricerca `n");
       output("di tipi poco raccomandabili ... pare che ultimamente la banca sia stata rapinata svariate volte.`n");
       output("Dopo averti squadrat".($session[user][sex]?"a":"o")." per bene estrae un quadernetto e ti dice:`n`n\"`#Molto bene `@".$session['user']['name']);
       output(" `#sono felice di comunicarti che hai ".$session['user']['evil']." punti cattiveria, e che gli onesti cittadini ");
       output("di `^Rafflingate`# hanno deciso di promuovere una campagna contro il crimine, elargendo un premio a tutti ");
       output("i guerrieri che non hanno superato la soglia dei 100 punti cattiveria con un premio in oro. Grazie a loro ");
       output("guadagni $premio pezzi d'oro!!`2\"`n`n Detto ciò ti consegna un borsellino contenente il tuo premio e si avvia ");
       output("nuovamente nella foresta.`n");
       $session['user']['gold'] += $premio;
       debuglog("incontra lo Sceriffo che gli consegna $premio oro. EVIL=".$session['user']['evil']);
       addnav("`@Continua","forest.php");
   }else if ($session['user']['evil'] > 99 AND $session['user']['gems'] > 7){
         output("`%Mentre ti aggiri furtivamente nella foresta, noti con la coda dell'occhio un movimento alla tua destra. `n");
         output(" È lo Sceriffo che mentre punta la sua balestra nella tua direzione se la ride di gusto per aver catturato `n");
         output(" un".($session[user][sex]?"a":"")." criminale della peggiore specie. Non hai nessuna via di fuga ed il tuo destino sembra segnato ma ... `n");
         output(" forse potresti tentare di corrompere lo sceriffo offrendogli qualcuna delle tue gemme. `n");
         output(" Si, potrebbe funzionare ! Vuoi offrigli delle gemme per la tua fuga ?`n");
         output("<form action='forest.php?op=corrompi' method='POST'><input name='gemme' value='0'><input type='submit' class='button' value='Offri Gemme'>`n",true);
         addnav("","forest.php?op=corrompi");
         addnav("`\$No, Consegnati","forest.php?op=arrendi");
         $session['user']['specialinc']="sceriffo.php";
   }else {
       output("`5Mentre ti aggiri furtivamente nella foresta, noti con la coda dell'occhio un movimento alla tua destra. `n");
       output("Ma prima di poter capire chi o cosa sia ti ritrovi faccia a terra, incatenat".($session[user][sex]?"a":"o")." e lo Sceriffo che ti punta `n");
       output("una corta spada alla nuca dicendo: \"Fai una sola mossa e sei mort".($session[user][sex]?"a":"o")."!\" ");
       $session['user']['jail']=1;
       $name=$session['user']['name'];
       addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." catturat".($session[user][sex]?"a":"o")." dallo Sceriffo nella foresta e sbattut".($session[user][sex]?"a":"o")." in Prigione!");
       debuglog("è stato catturato dallo Sceriffo nella foresta e sbattuto in Prigione. EVIL=".$session['user']['evil']);
       addnav("`^Continua","constable.php?op=twiddle");
   }
}else if ($_GET['op']=="corrompi") {
    $session['user']['specialinc']="";
    $gemme = abs((int)$_POST['gemme']);
    if ($gemme > $session['user']['gems']){
       output("`\$Lo sceriffo ti guarda arcigno, e poi prendendo meglio la mira con la balestra dice: \"`@Pensavi di potermi`n");
       output("ingannare promettendomi gemme che non hai ? La tua natura malvagia non si è smentita neanche in questa `n");
       output("occasione! Ma potrai rifletterci sopra scontando un giorno di prigione in più ! Puoi scommetterci !\"`\$`n`n");
       output("Dette queste parole ti ammanetta e ti conduce alla prigione dove potrai riflettere sul fatto che è meglio `n");
       output("non tentare di ingannare uno sceriffo!`n");
       $session['user']['evil']+=25;
       $session['user']['jail']=1;
       $name=$session['user']['name'];
       addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." catturat".($session[user][sex]?"a":"o")." dallo Sceriffo nella foresta e sbattut".($session[user][sex]?"a":"o")." in Prigione!");
       debuglog("è stato catturato dallo Sceriffo nella foresta e sbattuto in Prigione, fallendo nel tentativo di corromperlo. EVIL=".$session['user']['evil']);
       addnav("`^Continua","constable.php?op=twiddle");
    }else {
        $gemme1 = $session['user']['gems'];
        if ($gemme1<=20 AND $gemme >= intval($gemme1/2)) $flag=true;
        if ($gemme >= 10) $flag=true;
        if ($flag) {
           output(" `3Lo sceriffo afferra le tue $gemme gemme, e dopo essersi guardato in giro ti urla: `n");
           output(" \"`^Cosa aspetti? Che accorra l'intero villaggio prima di scappare ? `n");
           output(" Oppure che cambi idea e ti arresti comunque ? Scappa ... scappa !!!!`3\"`n");
           output("Incredul".($session[user][sex]?"a":"o")." di essere riuscit".($session[user][sex]?"a":"o")." a corrompere lo sceriffo, inizi a scappare e ti fermi solo dopo una decina di`n");
           output("minuti con il cuore che ti batte forte in gola! La tua fuga è costata cara ma la libertà non ha prezzo!!");
           $session['user']['gems']-=$gemme;
           debuglog("ha corrotto lo Sceriffo nella foresta e non viene sbattuto in Prigione. EVIL=".$session['user']['evil']);
           addnav("`@Torna alla Foresta","forest.php");
        } else if ($gemme == 0){
             output(" `#Lo sceriffo inizia a sghignazzare fragorosamente, e continua per almeno un paio di minuti. Quando finalmente `n");
             output(" sembra aver ripreso il controllo di se stesso ti dice: `n");
             output(" \"`^Speri forse che ti lasci scappare per nulla ?? La prossima volta offrimi `n");
             output(" qualcosa di concreto se vuoi avere una speranza che ti lasci fuggire !!`#\" `n");
             output(" E detto ciò ti ammanetta e ti scorta in prigione.`n");
             $session['user']['jail']=1;
             $name=$session['user']['name'];
             addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." catturat".($session[user][sex]?"a":"o")." dallo Sceriffo nella foresta e sbattut".($session[user][sex]?"a":"o")." in Prigione!");
             debuglog("è stato catturato dallo Sceriffo nella foresta e sbattuto in Prigione. EVIL=".$session['user']['evil']);
             addnav("Continua","constable.php?op=twiddle");
        } else {
             output(" `%Lo sceriffo soppesa nella mano le tue $gemme gemme, ti guarda e poi tornare ad osservare le gemme. `n");
             output(" Finalmente dopo qualche minuto in cui sembrava combattuto se lasciarti andare o meno sembra aver raggiunto `n");
             output(" una decisione. Sei sulle spine e attendi con ansia che lo sceriffo abbassi la sua balestra e ti lasci libero. `n");
             output(" Ma lo sceriffo continua a puntarti addosso la temibile arma e con tono contrito ti dice: `n");
             output(" \"`#Sono stato quasi tentato di accettare la tua offerta, ma sono un uomo di legge e non saranno certo le `n");
             output(" tue $gemme gemme che riusciranno a farmi cambiare idea sul bene e sul male. Ti scorterò alla prigione dove `n");
             output(" sconterai le tue pene. ");
             $chance=e_rand(0,9);
             if ($chance == 0) {
                output("Sequestrerò inoltre le tue $gemme gemme come prova della tua tentata corruzione.`n");
             } else output("`n");
             $session['user']['jail']=1;
             $name=$session['user']['name'];
             addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." catturat".($session[user][sex]?"a":"o")." dallo Sceriffo nella foresta e sbattut".($session[user][sex]?"a":"o")." in Prigione!");
             debuglog("è stato catturato dallo Sceriffo nella foresta e sbattuto in Prigione, fallendo nel tentativo di corromperlo. EVIL=".$session['user']['evil']);
             addnav("Continua","constable.php?op=twiddle");
        }
    }
} else if ($_GET['op']=="arrendi"){
    $session['user']['specialinc']="";
    output(" `3Capisci che non hai nessuna possibilità di riuscire a corrompere lo Sceriffo e per cui non opponi `n");
    output(" resistenza e ti lasci catturare. Speri solo che questa volta nella cella di fianco alla tua non ci `n");
    output(" sia quell'ubriacone dell'ultima volta, che con il suo russare non ti ha fatto dormire tutta la notte !`n");
    $session['user']['jail']=1;
    $name=$session['user']['name'];
    addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." catturat".($session[user][sex]?"a":"o")." dallo Sceriffo nella foresta e sbattut".($session[user][sex]?"a":"o")." in Prigione!");
    debuglog("è stato catturato dallo Sceriffo nella foresta e sbattuto in Prigione. EVIL=".$session['user']['evil']);
    addnav("Continua","constable.php?op=twiddle");
}
page_footer();
?>