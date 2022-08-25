<?php
/*
autore:             Xtramus (chumkiu)
idea:               Sonicwar
file necessari:     alchimista.php, thieves.php, cavernaoscura.php
descrizione:        un semplice evento che può richiamare altri eventi!
                    Il pg vede un animale che porta una borsetta di cuoio.
                    Deve decidere se seguirlo per prendersi la borsetta
                    oppure capire dove l'ha trovata. I primi array contengono
                    gli eventi speciali che possono essere collegati!
                    Non è matematico il richiamo ad altri eventi!
*/
function continua_ES() {
global $session;
$session['user']['specialinc']="lanimale.php";
}
// eventi che hanno grotte o bottini nascosti
$eventi_ES= Array("thieves.php", "yagor.php", "cavernaoscura.php");
//eventi che riguardano borsette e borsellini
$eventiinsegui_ES= Array("alchimista.php");

page_header("L'animale!");

// irrilevante ma giusto per dare un tocco di casualità :))))
$animale= Array("`3volpe","`7gazza","`^gatta","`@gazzella","`8martora","`%scrofa","`(cerbiatta","`&capra");

output("`n`n`c`b`\$ L'animale!`0`b`c`n`n");

if($_GET['op']=="") {
output("`2Sei in cerca di qualcosa da uccidere. Trovi una " . $animale[e_rand(0,(count($animale)-1))] . "`2 che ");
output("si aggira guardinga nella foresta! Stai per sferrare l'attacco quando noti che ha legato al collo un piccolo ");
output("sacchetto... sembra un borsellino!`n`n`^ Cosa vuoi fare?");
addnav("Insegui l'animale", "forest.php?op=insegui");
addnav("Scopri da dove è venuto (segui a ritroso le tracce)", "forest.php?op=scopri");
continua_ES();
}
else if($_GET['op']=="insegui") {
output("`@Insegui l'animale, vuoi conoscere a tutti i costi il contenuto di quel sacchetto: potrebbe rivelarsi molto interessante!`n");

     if(e_rand(1,5)==1) {
          output("`2Ti avvicini furtivamente all'animale e con un movimento repentino riesci a sottrargli il sacchetto dal collo.");
          output("`n`nLo apri velocemente e al suo interno trovi `5" . $g_ES= e_rand(1,2) . " gemm".(($g_ES==1)?"a":"e")."!`n`n`2Perdi ");
          output("comunque un turno per l'inseguimento.`n");
          $session['user']['gems']+= $g_ES;
          $session['user']['turns']-=1;
     }
     else if(e_rand(1,4)!=1) {
          output("`nMentre inizi a fantasticare sul possibile contenuto del sacchetto, su quale importante e ricca ");
          output("personalità potrebbe averlo legato al collo dell'animale, ne perdi le tracce!`nPerdi un turno nel ");
          output("tentativo, inutile, di ritrovarlo!`n");
          $session['user']['turns']-=1;
     }
     else {
          output("`nMentre inizi a fantasticare sul possibile contenuto del sacchetto, su quale importante e ricca ");
          output("personalità potrebbe averlo legato al collo dell'animale, ne perdi le tracce!`n`n");
          output("`^Sai che è andato verso ovest e decidi di continuare la ricerca in quella direzione! Nulla e nessuno ");
          output("possono separarti proprio ora dal tuo tesoro !!`n");
          continua_ES();
          addnav("Continua la ricerca", "forest.php?op=cerca");
     }
}
else if($_GET['op']=="cerca") {
     $session['user']['turns']-=1;
     if(e_rand(1,3)==1) {
          output("Dopo aver girovagato per ore non trovi nulla! L'animale è letteralmente sparito!`n");
          output("Perdi un turno nell'infruttosa ricerca.`n");
     }
     else {
          output("`^Eccolo là! Ritrovi l'animale sotto un albero ma sembra aver perso il sacchetto!`n");
          output("Deve essere qui intorno... continua la ricerca!");
          addnav("Girovaga nelle vicinanze", "forest.php");
          $session['user']['specialinc']=$eventiinsegui_ES[e_rand(0,(count($eventiinsegui_ES)-1))];
     }
}
else if($_GET['op']=="scopri") {
     output("`2\"`@Ma dove avrà preso quel sacchetto?`2\" pensi.`nDecidi di resistere al primo impulso di inseguire ");
     output("l'animale, e cominci a seguire le tracce a ritroso!");
     if(e_rand(1,2)==1) {
     output("`n`nDopo un'ora che segui le tracce, scopri di essere ritornato al punto di partenza!`n`nPerdi un turno!");
     $session['user']['turns']-=1;
     }
     else {
     output("`n`nDopo un'ora di indagine scopri una piccola grotta! Credi che forse la grotta è un nascondiglio ");
     output("per qualcuno e decidi di entrare!`n`nMa al primo passo hai una piccola sorpresa...");
//     $eventi_ES= Array("thieves.php", "yagor.php", "cavernaoscura.php");
     $session['user']['specialinc']=$eventi_ES[e_rand(0,(count($eventi_ES)-1))];

     if($session['user']['specialinc']=="cavernaoscura.php") {
          // se becco l'evento della caverna salto il passo di introduzione
          $addget_ES= "&op=entra";
     }

     addnav("Continua...", "forest.php?" . $addget_ES);
     }
}
else {
// dimenticato nulla? :)
output("Ritorni in foresta");
}
?>