<?php
page_header("Lo Gnomo Salterino");
require_once("common.php");
require_once("common2.php");
$costo = 2;
function raccogli($coloremateriale,$materiale,$tipomateriale,$costo) {
	
	global $session;
	
        if ( !zainoPieno($idplayer)) {
           $sqli="INSERT INTO zaino (idoggetto, idplayer) VALUES ('".$tipomateriale."', '".$session['user']['acctid']."')";
           db_query($sqli) or die(db_error(LINK));
           debuglog("compra $coloremateriale$materiale`3 e spende $costo gemme dallo gnomo della foresta");
        }else{
             output("`%È un vero peccato che tu abbia lo zaino talmente pieno che non può contenere altro materiale !!`n");
             output("Forse faresti meglio a vendere qualcuno dei materiali che ti porti appresso per alleggerire ");
             output("lo zaino e far posto ad eventuali altri materiali `ndi maggior pregio che potresti trovare nelle tue prossime avventure nei meandri della foresta.`n");
             debuglog("spende $costo gemme dallo gnomo della foresta ma ha lo zaino pieno");
        }
                
}

if ($_GET['op'] == ""){
   output("`7`nMentre gironzoli senza meta nella foresta, ti imbatti in uno strano essere,");
   output("a metà tra un leprechaun ed un nano. `nNon sta fermo un istante, e continua a saltellare da una gamba ");
   output("all'altra, come in preda ad un delirio febbrile. `nMentre esegue quello che potrebbe essere un rituale ");
   output("come anche una strana danza, ti nota e, sempre danzando, ti si avvicina.`n`n");
   output("\"`@Ihh, un cuerriero tel fillaccio! Ahh, che fortuna tu essere cui con me!`7\" dice nel suo strano ");
   output("linguaggio. `nPoi prosegue: \"`@Io posso fare molto tanto per te, se tu fare me fellice. `nIo avere cosa ");
   output("molto merafiliosa e pressiosa molto, che fare te moltissimo più potentissimo cuerriero!!`7\"`n`n");
   output("Vuoi continuare ad ascoltare quello che ha da offrirti lo gnometto, o preferisci proseguire nella tua ");
   output("perlustrazione della foresta ?");
   addnav("Ascolta lo gnomo","forest.php?op=prosegui");
   addnav("Prosegui nella foresta","forest.php?op=continua&op2=primo");
   $session['user']['specialinc'] = "gnomoarma.php";
}elseif ($_GET['op'] == "prosegui"){
   output("`7`nGuardi con fare diffidente l'esserino, ma gli chiedi cosa mai sia questa meraviglia di tale siffatta ");
   output("potenza.`nRiprendendo la sequenza di ballo, lo gnometto, sempre più eccitato dice: `n\"`@Ehh, io sapessi ");
   output("tu non lasciasse persa occasione d'oro come questa! `nIo ho rubasto ricetta per fabbrico di arma ");
   output("oltre potenza tu possa immaginarsi, ed io cedetti per la facessia di soli `^$costo`& `bgemme`b!`7\"`n`n");
   output("Cosa fai, accetti la proposta dello gnomo?");
   addnav("Accetta","forest.php?op=accetta");
   addnav("Prosegui nella foresta","forest.php?op=continua&op2=secondo");
   $session['user']['specialinc'] = "gnomoarma.php";
}elseif ($_GET['op'] == "accetta"){
   output("`7`nDecidi di accettare la proposta delle gnomo, anche se non ti senti affatto sicuro della veridicità ");
   output("delle sue affermazioni.`n");
   if ($session['user']['gems'] < $costo){
      output("`7Metti mano al tuo borsellino per scoprire che, purtroppo, non possiedi le `^$costo `&`bgemme`b `7");
      output("necessarie all'acquisto della ricetta.`n");
      addnav("Prosegui nella foresta","forest.php?op=continua&op2=terzo");
      $session['user']['specialinc'] = "gnomoarma.php";
   } else {
      output("`7`nMetti mano al borsellino e ne tiri fuori `^$costo `&`bgemme`b `7alla cui vista gli occhi dello gnomo iniziano ");
      output("a brillare di luce propria mentre dice:`n \"`@Ohh, `^$costo `@brillantissime `&`bgemme`b `@! Io devi dare, io non ");
      output("posso risistire senza luce di gemme. Tu dare me subito, dare, Dare, DARE DARE DARE!!!!`7\"`n`n");
      output("Qualche istante dopo l'incessante cantilena, ti strappa le gemme dalla mano ed inizia a coccolarle, ");
      output("mentre con fare distratto ti getta una pergamena sgualcita, e si allontana nel profondo della ");
      output("foresta.`n`nSenti la sua voce attenuarsi mentre si inoltra nella macchia, ed abbassi lo sguardo ");
      output("sulla pergamena che ti ha lasciato.`n`n");
      $session['user']['gems'] -= $costo;
      $caso = e_rand(0,9);
      output("`2La srotoli delicatamente per non danneggiare il prezioso documento e scopri con stupore ");
      switch($caso){
		case 0:
          	$coloremateriale="`#";
		  	$tipomateriale="18";
			$materiale="ricetta Spada Media";
          	output("e meraviglia che sei il proprietario di una ricetta `nper la creazione di una `#`bSpada Media`b`2!!`n`n");
          	output("Non riesci a crederci ... sei riuscito ad aggiudicarti un documento di grandissimo valore per ");
          	output("un'inezia!!`nOggi è stata proprio una giornata fortunata.`n`n");
           	raccogli($coloremateriale,$materiale,$tipomateriale,$costo);
        break;
        case 1:
          	$coloremateriale="`@";
		  	$tipomateriale="13";
			$materiale="ricetta Arma Media";
          	output("e meraviglia che sei il proprietario di una ricetta `nper la creazione di un'`@`bArma Media`b`2!!`n`n");
          	output("Non riesci a crederci ... sei riuscito ad aggiudicarti un documento di grandissimo valore per ");
          	output("un'inezia!!`nOggi è stata proprio una giornata fortunata.`n`n");
          	raccogli($coloremateriale,$materiale,$tipomateriale,$costo);
        break;
        case 2:
          	$coloremateriale="`6";
		  	$tipomateriale="7";
			$materiale="oro";
           	output("che è un semplicissimo pezzo di pergamena! `nMentre stai per gettarlo con rabbia senti che al ");
          	output("suo interno c'è qualcosa di duro. Lo apri completamente e trovi una rilucente `^`bScaglia d'Oro`b`2!!`n`n");
          	raccogli($coloremateriale,$materiale,$tipomateriale,$costo);
        break;
        case 3: case 4:
          	$coloremateriale="`&";
			$tipomateriale="5";
			$materiale="argento";
          	output("che è un semplicissimo pezzo di pergamena! `nMentre stai per gettarlo con rabbia senti che al ");
          	output("suo interno c'è qualcosa di duro. Lo apri completamente e trovi una rilucente `&`bScaglia d'Argento`b`2!!`n`n");
          	raccogli($coloremateriale,$materiale,$tipomateriale,$costo);
        break;
        case 5: case 6: case 7: case 8:
          	$coloremateriale="`(";
			$tipomateriale="2";
			$materiale="rame";
          	output("che è un semplicissimo pezzo di pergamena! `nMentre stai per gettarlo con rabbia senti che al ");
         	output("suo interno c'è qualcosa di duro. Lo apri completamente e trovi una rilucente `^`iScaglia d'Oro`i`2!!`n");
          	output("La rimiri tra le mani, e ti accorgi che le tue dita si stanno colorando di `^oro`2 ... colore che sta ");
          	output("abbandonando la scaglia di metallo! `nDopo averla ripulita scopri che altro non è che una semplice ");
          	output("`8`bScaglia di `(Rame`b`2!!`n`nLo gnomo ti ha imbrogliato!!`nComunque hai pur sempre una `8`bScaglia di ");
          	output("`(Rame`2`b, che potrai rivendere per ripagarti parzialmente delle gemme perse.`n`n");
          	raccogli($coloremateriale,$materiale,$tipomateriale,$costo);
        break;
        case 9:
          	$coloremateriale="`#";
			$tipomateriale="1";
			$materiale="ferro";
          	output("che è un semplicissimo pezzo di pergamena! `nMentre stai per gettarlo con rabbia senti che al ");
    	    output("suo interno c'è qualcosa di duro. Lo apri completamente e trovi una rilucente `&`iScaglia d'Argento`i`2!!`n");
        	output("La rimiri tra le mani, e ti accorgi che le tue dita si stanno colorando di `&argento`2 ... colore che sta ");
          	output("abbandonando la scaglia di metallo! `nDopo averla ripulita scopri che altro non è che una semplice ");
          	output("`3`bScaglia di `#Ferro`b`2!!`n`nLo gnomo ti ha imbrogliato!!`nComunque hai pur sempre una `3`bScaglia di ");
          	output("`#Ferro`b`2, che potrai rivendere per ripagarti parzialmente delle gemme perse.`n`n");
          	raccogli($coloremateriale,$materiale,$tipomateriale,$costo);
		break;
      }
      
      $session['user']['specialinc'] = "";
   }
}elseif ($_GET['op'] == "continua"){
   $session['user']['specialinc'] = "";
   
   if ($_GET['op2'] == "primo"){
      output("`7Decidi di non perdere tempo con un essere alto poco più di 80 centimetri, e senza neanche degnarlo ");
      output("di uno sguardo prosegui nella perlustrazione della foresta.`n`n");
   }elseif ($_GET['op2'] == "secondo" OR $_GET['op2'] == "terzo"){
      $per = 1;
      if ($_GET['op2'] == "terzo"){
         $per = 2;
      }
      if ($_GET['op2'] == "secondo"){
         output("`7`nDecidi di non fare affidamento su di un essere alto poco più di 80 centimetri e che parla strampalato come se ");
         output("fosse sotto gli effetti `ndi un'abbondante libagione di birra dei nani. Ti volti salutando lo gnometto ed inizi ad allontanarti.`n");
      }else{
         output("`7`nTi inventi una scusa banale mentre ti congedi dallo gnomo ");
         output("che ti guarda con fare minaccioso mentre ti allontani.`n");
      }
      $perdita = intval(e_rand(20, 40) * $session['user']['maxhitpoints'] / 100) * $per;
      if ($perdita >= $session['user']['hitpoints']) {
         $perdita = $session['user']['hitpoints']-1;
      }
      $session['user']['hitpoints'] -= $perdita;
      output("`nAlle tue spalle senti lo gnomo pronunciare quella che sembra una formula magica, ed uno strano brivido ");
      output("percorre la tua schiena.`n`n`^Sei stato colto dalla `%Maledizione dello Gnomo`^!!! Inoltre perdi anche `%$perdita`^ HitPoints!! ");
      output("`n`n`7Forse la prossima volta sarai più rispettoso nei confronti degli essere magici della foresta!`n");
      
      $session['bufflist']['gnomo'] = array("name"=>"`%Maledizione dello Gnomo","rounds"=>(10*$per),"wearoff"=>"La Maledizione dello Gnomo si dissolve così come è venuta","defmod"=>(0.9/$per),"roundmsg"=>"La Maledizione dello Gnomo riduce le tue difese e {badguy} ne approfitta menando pericolosi fendenti.","activate"=>"defense");
   }
}
?>