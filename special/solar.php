<?php
/*  Solar Eclipse
    LoGD Forest Event for version 097
    Written by Robert of Maddnet
    INSTALL - EASY! - just drop into your Special Folder
    Revisited by Hugues 26/11/2011

*/
if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`n`n`2 Mentre ti aggiri nella foresta alla ricerca di orride creature da massacrare, ti accorgi che la luce solare sta diminuendo e che l'oscurità si sta lentamente impadronendo del bosco nonostante sia pieno giorno. ");
    output("`n`n`2 Alzi lo sguardo e sbirciando tra le fronde degli alberi riesci ad intravedere nel cielo la luna che sta lentamente oscurando il sole. Stai assistendo ad un fenomeno molto raro: un' `^Eclissi Solare`2. ");
    output("`n`n Momentaneamente sorpreso ti prendi un attimo di tempo per riflettere. `& Cosa fare? ");
    addnav("Eclisse Solare");
    addnav("Osserva l'evento","forest.php?op=watch");
    addnav("Prosegui nella Foresta!","forest.php?op=dont");
    $session['user']['specialinc']="solar.php";
}else if ($_GET['op']=="watch"){
  if ($session['user']['turns']>=6){
      output("`n`n`2 Vedi la `&LUNA `2che passa davanti al disco del `^SOLE`2. ");
      output("Il profilo lunare è ora diventato tangente all'interno di quello solare: è iniziata la totalità dell'eclisse e, mentre di colpo tutta la foresta precipita nel buio più scuro, ogni rumore sparisce e ti ritrovi nel più assoluto silenzio. ");
	  output("Il `^SOLE`2 è completamente oscurato dalla `&LUNA `2e la circonda con una corona di luce, siamo nella fase massima dell'eclisse."); 
	  output("Completamente al buio resti perfettamente immobile senza respirare, la paura ti sta attanagliando e sei come paralizzato di fronte alla magnificenza della natura.");       
	  output("La `&LUNA `2completa il suo passaggio davanti al `^SOLE`2 che riprende a risplendere in cielo. Con un profondo sospiro ti scuoti dal torpore che ti ha avvolto le membra e ti accorgi che ora torni a sentire i rumori di sottofondo del bosco.`n`n");
	  switch(e_rand(1,10)){
        case 1:
           output("Quello al quale hai appena assistito è un evento naturale magnifico oltre che raro e ricorderai per sempre questa giornata. ");
           output("`n`n Senti un impulso d'energia scorrere nelle tue vene, galvanizzato guadagni `&1`2 turno di combattimento. ");
           $session['user']['turns']++;
           break;
        case 2: 
        	output("Purtroppo non hai preso alcuna precauzione nell'osservare questo meraviglioso fenomeno della natura, e scopri di essere stato temporaneamente accecato!");
        	$session['bufflist']['agile'] = array(
	            "name"=>"`4Cecità temporanea",
	            "rounds"=>10,
	            "wearoff"=>"La cecità temporanea scompare e torni a vedere come prima.",
	            "atkmod"=>0.85,
	            "defmod"=>0.85,
	            "roundmsg"=>"Temporaneamente accecato dall'eclisse solare, non riesci a vedere i colpi del tuo avversario!",
	            "activate"=>"roundstart");
	    	break;
        case 3: 
        	output("Nonostante tu non abbia preso alcuna precauzione nell'osservare questo meraviglioso fenomeno della natura, rimanendo temporaneamente accecato, i tuoi sensi sono acuiti e riesci a combattere meglio!");
        	$session['bufflist']['agile'] = array(
	            "name"=>"`4Sensi più acuti",
	            "rounds"=>10,
	            "wearoff"=>"La cecità temporanea scompare e torni a vedere e a combattere come prima.",
	            "atkmod"=>1.15,
	            "defmod"=>1.15,
	            "roundmsg"=>"Anche se accecato dall'eclisse solare, riesci ad intuire meglio i colpi del tuo avversario!",
	            "activate"=>"roundstart");
	       break;
		case 4:
           output("Dopo aver osservato questo meraviglioso evento senza alcuna precauzione per i tuoi occhi, scopri di essere stato temporaneamente accecato e perdi parecchio tempo per riacquistare la vista!");
           if ($session['user']['turns'] > 5) {
              output("`n`n Perdi tutti i tuoi turni di combattimento tranne `&5`2. ");
              $session['user']['turns']=5;
           } else {
              output("`n`n Perdi tutti i tuoi turni di combattimento. ");
              $session['user']['turns']=0;
           }
           break;
        case 5: case 6: case 7: case 8: case 9:
           output("Questo eccezionale evento naturale rimarrà scolpito nella tua memoria per sempre. Purtroppo il tempo trascorso ad osservarlo ");
           output("ti ha stancato. Sei esausto e perdi `&1`2 turno di combattimento per riposare e riprenderti dalla fatica. ");
           $session['user']['turns']--;
           break;
        case 10:
           output("Mentre osservi questo evento epocale, ti senti parte dell'universo e guadagni `&1`2 punto vita `ipermanente`i!! ");
           $session['user']['maxhitpoints']++;
           break;
        }
    }else{
      output("`n`n`2 Vedi la `&LUNA `2che passa davanti al disco del `^SOLE`2. ");
      output("Il profilo lunare è ora diventato tangente all'interno di quello solare: è iniziata la totalità dell'eclisse e, mentre di colpo tutta la foresta precipita nel buio più scuro, ogni rumore sparisce e ti ritrovi nel più assoluto silenzio. ");
	  output("Il `^SOLE`2 è completamente oscurato dalla `&LUNA `2e la circonda con una corona di luce, siamo nella fase massima dell'eclisse."); 
	  output("Completamente al buio resti perfettamente immobile senza respirare, la paura ti sta attanagliando e sei come paralizzato di fronte alla magnificenza della natura.");       
	  output("La `&LUNA `2completa il suo passaggio davanti al `^SOLE`2 che riprende a risplendere in cielo. Con un profondo sospiro ti scuoti dal torpore che ti ha avvolto le membra e ti accorgi che ora torni a sentire i rumori di sottofondo del bosco.`n`n");
	  output("Quello appena osservato è stato un evento magnifico e ricorderai per sempre questa giornata. ");
    }
}else{
  output("`n`n`2Non volendo sprecare il tuo tempo ad osservare uno stupido evento della natura, prosegui per la tua strada. ");
  output("`n`n Mentre ti allontani nell'oscurità che cala sempre più, inciampi in una radice sporgente dal terreno e cadi .... che sia un segno del destino? ");
  $session['user']['hitpoints'] -= intval($session['user']['maxhitpoints']/3);
  if ($session['user']['hitpoints'] <= 0) {$session['user']['hitpoints'] = 1;}
}
?>