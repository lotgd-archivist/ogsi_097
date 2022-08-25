<?php
/********************
The Tunnels Written by Sixf00t4 for http://sixf00t4.com/dragon
resurrection mod for LotGD
Sixf00t4 - http://sixf00t4.com
Italian translation by Excalibur (www.ogsi.it)
March 2004
You need to add a link to tunnel.php in your graveyard.php:
Line 120 or near there under addnav("Return to the shades","shades.php");
ADD THIS ->    addnav("Take the tunnel","tunnel.php");
*********************/
require_once "common.php";
checkday();
page_header("Il Tunnel degli Inferi");
$session['user']['locazione'] = 185;
$sposo=$session['user']['marriedto'];
$sql = "SELECT name FROM accounts WHERE acctid = $sposo";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
			
if ($_GET['op']==""){
	output("`7Ti avvicini ad `4Ade`7, piazzato davanti all'entrata del tunnel.`n");
	output(" Ti senti solo e ti manca tanto `%");
	if ($session['user']['charisma']==4294967295){
		if ($session['user']['marriedto']==4294967295){
			output("".($session[user][sex]?"tuo marito Seth":"tua moglie Violet")."");
		}else{
			output("".($session[user][sex]?"tuo marito":"tua moglie")." $row[name],");
		}
	}else if ($session['user']['marriedto']==4294967295){
	  	output("il tuo amore ".($session[user][sex]?"Seth":"Violet").",");
	}else {
		output("il tuo amore $row[name],");
		}
	  output(" `7chiedi perciò se esiste la possibilità di tornare alla tua vita terrena.`n");
      output(" `4Ade `7ti concederà la `&Vita `7ma devi percorrere la lunga via attraverso il tunnel fino alla superficie. `n");
      output(" Non devi voltarti a guardare indietro o perderai 5 tormenti.`n");
      output(" A te la scelta. `n");
      addnav("`@Prendi il Tunnel","tunnel.php?op=agree");
      addnav("`\$Lascia stare","graveyard.php");



}else if ($_GET['op']=="agree"){
    if ($session[user][gravefights]>4) {
    $session[user][gravefights] -= 5;

    switch(e_rand(1,10)){
   case 1:
   case 2:
   case 3:
      output("`2Inizi la tua scalata nel tunnel.  Inizia a farsi via via più stretto, fino a che ti ritrovi bloccato!`n");
      output("Provi a muoverti ma non puoi neanche tornare indietro.  Senti `4Ade `2ridere di te dal fondo del tunnel. `n");
      output("`4Ade `7ti afferra per le gambe e ti tira fuori dal tunnel dicendoti che ti dice di lasciar perdere.`n");
      debuglog("viene fermato da Ade mentre risaliva il tunnel");
      addnav("`#Torna al Cimitero","graveyard.php");
      break;
   case 4:
   case 5:
   case 6:
   case 7:
   case 8:
  output(" `3Appena entri nel tunnel, senti un demone correre da `5Ramius `3per avvisarlo di ciò che stai per tentare.`n");
        output("Non appena `5Ramius `3riceve la notizia, vieni teleportato immediatamente fuori dal tunnel indietro nel cimitero. `n");
          output("`5Ramius `3dice al demone di tenerti d'occhio d'ora in avanti!`n");
          output("Hai perso tutti i tuoi favori con `5Ramius`3!`n");
          debuglog("viene scoperto da Ramius mentre risaliva il tunnel e perde tutti i favori");
          $session[user][deathpower] = 0;
          //$session[user][hashorse] = 5;
        addnav("`#Torna al Cimitero","graveyard.php");
      break;
   case 9:
   case 10:
      output("Non appena giungi all'altra estremità del tunnel, ti chiedi quanto realmente sia lungo il tunnel. `n");
      output("Inizi a voltarti per guardare indietro nel tunnel quando noti i resti di un guerriero che si è `n");
      output("voltato ... non ne resta granchè, solo qualche osso e brandelli di tessuto.`n");
      debuglog("riesce a fuggire dal cimitero risalendo il tunnel");
      $gems = e_rand(1,10);
      if ($gems == 2){
       output("Frughi nelle ossa a terra e noti una gemma!`n");
      debuglog("trova una gemma risalendo il tunnel");
      $session[user][gems] += 1;
       }else{
	   output("Meglio non voltarsi e imboccare l'uscita !`n");
	   }
       addnews("". ($session['user']['name']) . " ha percorso il tunnel ed è tornato in superficie! `n");
      addnav("`^Esci dal Tunnel","newday.php?resurrection=tunnel");
      break;
    }

}
else {
    output("Non hai abbastanza tormenti.  Ce ne vogliono almeno 5.");
    addnav("`#Torna al Cimitero","graveyard.php");
    }
}
page_footer();
?>