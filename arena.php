<?php
require_once "common.php"; 
addcommentary(); 

if ($_GET[op]=="vedi"){ 
  //  if ($session[user][gold]>=10 and $session[user][turns]>0){ 
  		if ($session[user][gold]>=10){
        $session[user][gold]-=10; 
        debuglog("spesi 10 oro per entrare nell'Arena"); 
        redirect("arena.php?op=esci"); 
    }else{ 
        page_header("Biglietteria dell'Arena"); 
       // if ($session[user][gold]<10) {
        output("`5Vorresti pagare `^10 pezzi d'oro`3 per essere ammesso, ma ti trovi un po' a corto di soldi."); 
   	//	}
		//if ($session[user][turns]<1) {
		//output("`5Sei troppo stanco, è meglio se ti riposi prima di affrontare combattimenti nell'arena.");
	//	}
    	addnav("Ritorna a Castel Excalibur","castelexcal.php"); 
	} 
}elseif ($_GET[op]==""){ 
    page_header("Arena dei combattimenti di Excalibur"); 
    $session['user']['locazione'] = 105;
    output(" `3`c`bArena dei Combattimenti di Excalibur`b`c `n`n`n"); 
    output("`3Mentre ti aggiri nei vari stands, puoi osservare i combattimenti che si stanno svolgendo nell'arena.`n"); 
    output(" Pensi che potresti anche divertirti. E inizi ad immaginarti che ... `n"); 
    output(" In qualità di guerriero, sei fuori posto qui, tra gli stends. Dovresti essere NELL'Arena.`n"); 
    addnav("Entra nell'Arena - 10 Oro","arena.php?op=vedi");
	addnav("Abbandona l'Arena","castelexcal.php"); 
}else{ 
    checkday();
	page_header("Biglietteria dell'Arena"); 
output(" `3`c`bArena dei Combattimenti di Excalibur`b`c`n`n"); 
output(" `&Entri nell'`3Arena di Excalibur`&, "); 
output(" ci sono centinaia di persone sugli spalti, che tifano per i loro beniamini."); 
output(" Qui puoi combattere con alcuni guerrieri esperti. `n"); 
output(" Ogni combattimento ti costerà 1 Combattimento Foresta più il biglietto d'ingresso.`n"); 
output(" Il costo per ogni combattimento è elencato di fianco ad ogni guerriero.`n"); 
output(" Se vinci il combattimento, guadagnerai la metà del costo d'ingresso,`n"); 
output(" se perdi, ci rimetterai per intero il costo del biglietto d'ingresso.`n"); 
output(" Se il combattimento finisce in parità, riceverai la metà del costo del biglietto. `n"); 
output(" `3Potresti essere ferito, ma questa non è una sfida `4all'ultimo sangue. `b`3Questo è sport!`b `&`n"); 
output(" Ma se combatti quando sei gravemente ferito, potresti morire!`n"); 

    addnav("Guerrieri"); 
    addnav("Saruman   -  60 oro","arenasaruman.php"); 
    addnav("Luke      -  80 oro","arenaluke.php"); 
    addnav("Excalibur - 100 oro","arenaexcal.php" );   
    addnav("Abbandona"); 
    addnav("Torna al Villaggio","village.php");   
     
} 
page_footer(); 
?> 