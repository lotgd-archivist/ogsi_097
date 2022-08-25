<?php
if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`5`nIncontri una radura stranamente silenziosa, ai piedi di un albero sono posate tre ceste ben chiuse. `nIncuriosito ti avvicini cautamente quando ti sembra di riconoscere il flebile miagolio di un gattino.`n 
    Stai per sollevare il coperchio della prima cesta quando una strana signora dall'aria bizzarra sbuca fuori dal nulla delirando febbrilmente su un qualcosa a te poco comprensibile, forse gattini colorati.... mah... `n");
    output("Scruti le fattezze della donna con espressione incuriosita.. abiti logorati e una capigliatura stravagante assomigliante ad un nido di rovi intrecciato, e non puoi che reprimere una leggera risata a tal vista.`n
    Stai per domandarle qualcosa ma colei che viene chiamata dagli abitanti del villaggio, `3Audrey la Pazza`5, ti osserva con sguardo torvo mentre tira a sé le ceste quasi come a proteggerle da chissà quali pericoli.`n");
    output("Sorpreso, decidi che è il caso di chiederle qualcosa a proposito di questi gattini.`n`n
    \"`#Dimmi, buona donna,`5\" cominci...`n`n
    \"`%BUONA BUONA buona buona buonabuonabuonabuonabuona...`5\" attacca a ripetere `3Audrey`5. Abbattuto, persisti`n`n
    \"`#Cosa sono questi gattini di cui parli?`5\"`n`n
    Sorprendentemente, `3Audrey la Pazza`5 si calma all´improvviso ed inizia a parlare con un tono regale, dolce e melodioso al tempo stesso.`n`n
    \"`%Come tu noti ho quì tre cestini,`n
    ciascun contiene quattro gattini.`n
    Quando i miei versi termineranno,`n
    sol tre di loro fuor balzeranno.`n
    Le tue fortune o i tuoi dolori,`n
    saran legati ai loro colori.`n
    Se sembreranno esser gemelli, `n
    avrai un premio di quelli belli.`n
    Ma se saranno invece diversi,`n
    alcuni turni andranno persi.`n
    Questo ti offro coi miei micetti,`n
    ora tu dimmi : `^Scappi o accetti? `5\"`n`n");
    addnav("`@Gioca","forest.php?op=play");
    addnav("`4Scappa via da `3Audrey","forest.php?op=run");
    $session['user']['specialinc']="audrey.php";
}else if($_GET['op']=="throw"){   
	$session['user']['specialinc']="";
    output("`n`5Non hai mai creduto a quanto vanno in giro a raccontare nelle taverne i cantastorie ubriaconi, immaginiamoci quindi quando addirittura si parla di pozioni magiche o di intrugli dotati di immensi poteri. Non esiste assolutamente un balsamo miracoloso!`n"); 
	output("Pertanto lanci il tutto in mezzo ai cespugli e te ne ritorni nella foresta alla ricerca di nuove e più stimolanti avventure.`n"); 
}else if($_GET['op']=="rub"){
	$session['user']['specialinc']="";
	$gain = $_GET['gain']; 
	output("`n`5Strofini con molta energia il balsamo sui tuoi alluci, ad un certo punto senti come una forte ondata di calore che parte dai tuoi piedi e sale, sale sempre più...`n"); 
	switch (e_rand(1, 10)) {
		case 1: case 2: case 3: 
			output("`5Immediatamente senti una potente energia salire dai tuoi piedi e pervadere tutto il corpo, ti senti agile e scattante come un felino! `n");
	        $session['bufflist']['agile'] = array(
	            "name"=>"`4Agile come un felino",
	            "rounds"=>10,
	            "wearoff"=>"L'agilità da felino scompare e ritorni alla normalità.",
	            "atkmod"=>1.15,
	            "defmod"=>1.15,
	            "roundmsg"=>"Sei agile e scattante come un leopardo e questo migliora i tuoi attacchi e la tua difesa!",
	            "activate"=>"roundstart");
	    	switch($gain) {
		        case 1: 
		        	$session['user']['turns'] += $gain;
		        	output("`n`^Hai guadagnato $gain turno di combattimenti nella foresta!`n"); 
		            break;
		        case 2: case 3: case 4: case 5:
		        	$session['user']['turns']+= $gain;
		        	output("`n`^Hai guadagnato $gain turni di combattimenti nella foresta!`n"); 
		            break;
		    }
	    
	        break;
	    case 4: 
	    	output("`5Poi non succede più nulla, forse il miracoloso balsamo è una storia inventata da qualche bardo ubriacone per raggranellare i soldi necessari a un ulteriore bevuta alla salute dei creduloni che sono convinti dell'esistenza di alchimisti e fattucchiere e delle loro pozioni mirabolanti!`n");
	        break;
	    case 5: 
	    	output("`5Immediatamente sui tuoi alluci crescono due enormi verruche,`# Ahi che male !!  `5 Altro che balsamo miracoloso.... `n");
	        $session['bufflist']['verruche'] = array(
	            "name"=>"`4Verruche sugli alluci",
	            "rounds"=>10,
	            "wearoff"=>"I tuoi alluci guariscono dalle verruche e riprendi a camminare perfettamente.",
	            "atkmod"=>.95,
	            "defmod"=>.95,
	            "roundmsg"=>"Fai molta fatica a stare in piedi per il dolore causato dalle verruche sui tuoi alluci!",
	            "activate"=>"roundstart");
	    	break;
	    case 6: case 7: case 8: case 9: case 10:
	    	switch($gain) {
		        case 1: 
		        	$session['user']['turns'] += $gain;
		        	output("`n`^Hai guadagnato $gain turno di combattimenti nella foresta!`n"); 
		            break;
		        case 2: case 3: case 4: case 5:
		        	$session['user']['turns']+= $gain;
		        	output("`n`^Hai guadagnato $gain turni di combattimenti nella foresta!`n"); 
		            break;
		    }
	    
	        break;
	}    
}else if($_GET['op']=="run"){
    $session['user']['specialinc']="";
    output("`n`5Fuggi via, correndo disperatamente, intimorito da quella pazza stralunata.");
}else if($_GET['op']=="play"){
    //$session['user']['specialinc']="audrey.php";
    $kittens = array("`^C`&a`6l`7i`^c`7o","`7T`&i`7g`&e`7r","`6Arancio","`&Bianco","`^`bPorcospino`b");
    $gain = 0 ;
    $c1 = e_rand(0,3);
    $c2 = e_rand(0,3);
    $c3 = e_rand(0,3);
    if (e_rand(1,20)==1) {
        $c1=4; $c2=4; $c3=4;
    }
    output("`5`nNonostante il tuo scetticismo, accetti l´assurdo gioco della stravagante signora e lei bussa sul coperchio della prima cesta. `n`nImmediatamente un tenero gattino {$kittens[$c1]}`5 tira fuori timidamente la testolina.`n`n");
    output("Poi la bizzarra donna solleva il coperchio della seconda cesta e un gattino {$kittens[$c2]}`5 ne esce con un debole miagolio.`n`n");
    output("Successivamente bussa sul coperchio della terza cesta e con un balzo un gattino {$kittens[$c3]}`5 esce arrampicandosi poi agilmente sulle spalle della folle vecchietta.`n`n");
    if ($c1==$c2 && $c2==$c3){
        if ($c1==4){
	        $gain = 5 ;
            output("\"`%Porcospini? PORCOSPINI??  Hahahahaha, PORCOSPINI!!!!`5\" urla `3Audrey la Pazza`5 mentre li afferra tutti per la collottola rimettendoli rapidamente nelle sue ceste. Poi urlando si getta in una folle corsa nella foresta scomparendo rapidamente alla tua vista.`n");
            output("Nella sua precipitosa fuga ha abbandonato una piccola borsa che contiene $gain piccoli vasetti, borsa che raccogli prontamente. ");
        }else{
	        $gain = 2 ;
            output("\"`%Argh, siete TUTTI dei gattini cattivi!`5\" urla `3Audrey la Pazza`5 prima di prendere il gattino dalle sue spalle e rimetterlo nella cesta.
             `n\"`%Visto che tutti i gattini erano uguali, ti regalo $gain dei miei balsami miracolosi. `n");
        }
    }elseif ($c1==$c2 || $c2==$c3 || $c1==$c3){
	    $gain = 1 ;
        output("\"`%Garr, gattini pazzi, che ne sapete voi? Perché vi ho dipinto tutti di colori diversi!`5\"`n`n Nonostante il tono di minaccia
        `3Audrey la Pazza`5 coccola il gattino sulle sue spalle prima di rimetterlo nella cesta e ti consegna $gain un piccolo vasetto. ");
    }else{
	    $gain = 0 ;
        output("\"`%Ben fatto piccolini!`5\" urla `3Audrey la Pazza`5. Proprio in quel momento il gattino sulle sue spalle ti salta addosso. Tentando di allontanarlo,
        perdi parecchio del tuo prezioso tempo. Dopo una lunga serie di infruttuosi tentativi, il piccolo micio ritorna docilmente nella sua cesta e tutto ritorna alla calma iniziale. `n`3Audrey la Pazza`5 ridacchia tranquillamente mentre ti guarda divertita come se ti stesse prendendoti in giro.");
        output("`n`n`^Perdi un combattimento nella foresta!");
        $session['user']['turns']--;
    }
    if ( $gain > 0 ) {
	    switch($gain) {
        case 1: 
        	output("`5Scettico esamini il vasetto che la strana donna ti ha regalato, contiene una mistura di un colore disgustoso e puzza terribilmente.`n");
        	break;
        case 2: 
        	output("`5Scettico esamini i vasetti che la strana donna ti ha regalato, contengono una mistura di un colore disgustoso e che puzza terribilmente.`n");
        	break;
        case 3: case 4: case 5: 
        	output("`5Scettico esamini i vasetti che la strana donna ha lasciato nella borsa da lei persa, contengono una mistura di un colore disgustoso e che puzza terribilmente.`n");
            break;
    }
    	output("\"`#Che sia veramente il balsamo miracoloso che una volta strofinato sugli alluci consente di acquisire magici poteri come si racconta in giro per Rafflingate ?`#\"`n`5Ti domandi molto perplesso. `n`n"); 
        output("`^Che fare ? Buttare via tutto o strofinare quel vomitevole intruglio sulle dita dei piedi ? `n");     
	    addnav("`@Strofina ","forest.php?op=rub&gain=$gain");
	    addnav("`4Butta","forest.php?op=throw");
	    $session['user']['specialinc']="audrey.php";
	}   
    //addnav("Play Again","forest.php?op=play");
    //addnav("Run away from Crazy Audrey","forest.php?op=run");
}
?>