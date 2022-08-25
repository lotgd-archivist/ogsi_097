<?php
//Xtramus Ottobre 2006 for www.ogsi.it e chi più ne ha più ne metta
/*
campi:
ALTER TABLE `accounts` ADD `lupin` TEXT NOT NULL AFTER `carriera` ;
*/
require_once "common.php";
addcommentary();
checkday();
page_header("Il Manicomio");
$session['user']['locazione'] = 147;
output("`c`b`(Il Manicomio di Rafflingate`c`b`n`n`n");
if($_GET['op']=="") {
     $frasipazze= Array(
          "`3Sei tu il Draaaago Verdee???",
          "`\$Karnak?? Karnak??? Sei il figlio di Karnak?? Sei l'AntiSgrios?",
          "`^Ooooh Sgrios... come sei bello... come sei buono... Sei tu vero?",
          "`%Sei tuuu un Diooo????",
          "`^Ehy tu!! Quante dita vedi? Tre??? Ecco un altro matto!!",
          "`7Assumiamo X uguale a tutte le quantità possibili... quanto vale Y?",
          "`1Tra un pò ci saranno molte donne... molte donne... le vedrai?",
          "`(Più peloso di una scimmia c'è solo un'altra scimmia?",
          "`1Che pesci sono quelli? come squali?? squelli!! Che pesci sono?",
          "`3Lo stivale calcerà forte e vincerà il mondo... lo vedrai??",
          "`4Novantasetteics... BAAANG! il futuro del rock'n roll! Li conosci?"
          );
     output("`nApri la porta del Manicomio... Vedi davanti a te scene bizzarre (del resto non potevi aspettarti altro!)`n");
     output("Un nano, un elfo e un umano fissano abbracciati la parete bianca, ti dà rabbia sapere che vedono cose che tu non vedi!`n");
     output("Un umano continua a sorseggiare un boccale completamente vuoto`n");
     output("Un troll in calzamaglia piroetta al centro della stanza con due lich che battono le mani");
     output("`nAd un tratto un piccolo nano si avvicina a te e ti dice:`n`n \"".$frasipazze[e_rand(0,count($frasipazze)-1)]. "`(\"");
     output("`n`n`(Rispondi impacciatamente, sicuramente spiazzato dalla domanda, quando ad un tratto il nano urla e scappa via!");
     output("`n`(Che strano posto... pensi!");
     addnav("Mi sento a casa! Voglio internarmi!!","manicomio.php?op=internati");
     addnav("Voglio andarmene!!","village.php");
}
else if($_GET['op']=="internati") {
     output("`(Cerchi di chiedere informazioni! Vuoi assolutamente farti internare per stare coi tuoi amici!`n");
     output("`nPurtroppo non trovi nessuno a cui chiedere... nessun sano di mente ovviamente!`n");
     output("`nLe indicazioni sono confuse, al posto della sala visite c'è il bagno e le indicazioni per le scale ti riportano all'ingresso!`n");
     output("`nCi sono centinaia di cartelli e non uno  porta nel luogo indicato!`n`n");
     output("`nSu tutti però è disegnato un diverso animale:`n`n");
     //per semplicità metto animali che vanno con l'articolo "del" e "il" e "un"
     $animali=Array("Minotauro","Ratto","Serpente","Drago","Pegaso","Grizzly","Manticora","Pixie","Cavallo","Pony","Falco","Coccodrillo","Gatto");
     foreach($animali as $k=>$v) {
          $i=$k%10;
          addnav("Segui il `b`$i" . "$v`b","manicomio.php?op=segui$v&w=$i");
          //output("`(un `b`$i" . "$v`b, ");
     }
     if($session['user']['lupin']['carriera']==1) {
          output("`%Il simbolo del serpente, però, sembra uguale a quello disegnato sulla pietra che ti ha dato il malvagio nella foresta!`n");
          output("Metti a confronto i due disegni e scopri che coincidono perfettamente!");
          output("`n`n`^Cosa vuoi fare?");

          addnav("Torna al villaggio","village.php");
     }
     else {
          output("`n`^Cosa vuoi fare?");
          addnav("Torna al Villaggio","village.php");
     }
}
elseif(substr($_GET['op'],0,5)=="segui") {
     $animale= substr($_GET['op'],5);
     output("`(Segui la freccia del `b`" . $_GET['w'] . "$animale`b`(... Ti trovi di fronte ad una porta chiusa e senza maniglie e con una strana serratura triangolare.`n");
     if($session['user']['lupin']['carriera']==1) {
          output("Sembra che sia della stessa forma della pietra!");
          output("`n`^Coraggio Sherlock Holmes, son sicuro che sai benissimo cosa fare!!");
          addnav("Inserisci la pietra nella fessura","manicomio.php?op=entra");
          addnav("Forse sono Holmes ma di sicuro non Indiana Jones! Me ne vado a casa!","village.php");
     } else {
          output("Bussi alla porta e una voce ti dice:`n`n");
          output("`^\"Parola d'ordine?\"`n`n");
          output("`(Cosa rispondi? `n`n");
          addnav("Nulla! Torno indietro","manicomio.php?op=internati");
          output("
               <form action=\"manicomio.php?op=paroladordine&freccia=$animale\" method=\"post\">
               <input type=\"password\" name=\"pass\">
               <input type=\"submit\" value=\"Parla\">
               </form>
               ",true);
          addnav("","manicomio.php?op=paroladordine&freccia=$animale");
     }
}
elseif($_GET['op']=="paroladordine") {
     if($_GET['freccia']=="Serpente") {
          if($session['user']['lupin']['carriera']<2) {
               $noentry=true;
          }
          elseif($_POST['pass']==$session['user']['lupin']['pass']) {
               output("Dici la tua parola d'ordine e la porta si apre");
               addnav("Entra nel covo","covoladri.php");
          }
          else {
               $noentry=true;
          }
          //addnav("Torna indietro","manicomio.php?op=internati");
     }
     else {
          $noentry=true;
     }
     if($noentry) {
          output("`(Dici la tua parola d'ordine...`n`n`n");
          output("...................un muro di silenzio...................`n`n`n");
          output("Decidi di tornare indietro");
          addnav("Torna indietro","manicomio.php?op=internati");
     }
}
elseif($_GET['op']=="entra") {
     output("`(Inserisci la pietra nella fessura e a fianco alla porta si apre uno scompartimento scavato nel muro. Dentro di esso ci sono varie mascherine, una leva e un cartello con su scritto: \"Indossare una maschera e tirare la leva\"!`n");
     output("Dopo aver seguito le istruzioni");
     output("la porta si apre ed entri in una stanza dove ti attende in piedi un umano anch'egli in maschera!`n`n");
     output("`^\"Benvenuto nel nostro covo, ti stavamo aspettando!");
     output("Come puoi intuire questo Manicomio è solo una copertura per nascondere il `\$Covo dei Ladri `^! Menti diaboliche che si riuniscono e organizzano qui i più difficili colpi");
     output("Se sei qui è perché il nostro capo ha riposto in te fiducia! Ti ritiene degno di entrare a far parte della nostra organizzazione!");
     output("Se accetti dovrai prima affrontare un piccolo test! Non accettiamo mica tutti, sai?\"`n`n");
     output("`(La proposta sembra allettante! Entrare in una organizzazione di ladri può essere, oltre che divertente, abbastanza fruttuosa. `^Cosa vuoi fare?");
     addnav("ACCETTA la proposta!","manicomio.php?op=accetta");
     addnav("RIFIUTA la proposta","manicomio.php?op=rifiuta");
}
elseif($_GET['op']=="rifiuta") {
     output("`%\"No grazie! La proposta è allettante ma il crimine non paga\"`n`n");
     output("`(Saluti tutti e cerchi di andar via, ovviamente dando le spalle ai ladri!`n`n");
     output("`^Ti risvegli ai piedi della Roccia Curiosa con un incredibile malditesta e non ricordi assolutamente nulla di ciò che è successo!");
     addnav("Alzati e torna al villaggio","village.php");
     $session['user']['lupin']['carriera']=-1;
}
elseif($_GET['op']=="accetta") {
     output("`%\"D'accordo! Accetto la proposta!\"`n");
     output("`(L'umano sorride maleficamente!`n`n");
     output("`^\"Bene... La prova per poter essere dei nostri è semplice:`n`n");
     output("`\$`bDovrai derubare la banca e portare qui il bottino`b!");
     output("<u>",true);
     output("`n`n`^Non avrai più bisogno della pietra per entrare: segui la via del `2`bSerpente`b`^, bussa la porta ed usa la parola d'ordine \"`b`(serpente`b`^\"! C'è sempre qualcuno pronto ad aprirti!\"");
     output("</u>",true);
     $session['user']['lupin']['pass']="serpente";
     output("`n`n`(Annuisci... ormai non puoi tirarti indietro! I briganti ti indicano l'uscita e ti incammini per la tua strada pronto ad assolvere il tuo compito!");
     $session['user']['lupin']['carriera']=2;
     addnav("Torna al Villaggio","village.php");
}
page_footer();
?>