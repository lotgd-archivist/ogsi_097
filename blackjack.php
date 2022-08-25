<?php
//Blackjack for logd
//version 1.0
//by Lonny Luberts of http://www.pqcomp.com
//copyright 2004 Lonny Luberts
//You may change this code as you see fit.. leave all comments and copyright intact.
//this file returns to casino.php which is simply my frontend for games of chance
//change this to fit your game or create your own casino.php
//all images are to go into your images folder
//add to accounts table cards,dealt
/*
MySql commands
ALTER TABLE accounts ADD `cards` text NOT NULL,
ALTER TABLE accounts ADD `dealt` int(4) NOT NULL default '0',
*/
	$cards = array(
		"2ofdiamonds",
		"3ofdiamonds",
		"4ofdiamonds",
		"5ofdiamonds",
		"6ofdiamonds",
		"7ofdiamonds",
		"8ofdiamonds",
		"9ofdiamonds",
		"10ofdiamonds",
		"jackofdiamonds",
		"queenofdiamonds",
		"kingofdiamonds",
		"aceofdiamonds",
		"2ofclubs",
		"3ofclubs",
		"4ofclubs",
		"5ofclubs",
		"6ofclubs",
		"7ofclubs",
		"8ofclubs",
		"9ofclubs",
		"10ofclubs",
		"jackofclubs",
		"queenofclubs",
		"kingofclubs",
		"aceofclubs",
		"2ofhearts",
		"3ofhearts",
		"4ofhearts",
		"5ofhearts",
		"6ofhearts",
		"7ofhearts",
		"8ofhearts",
		"9ofhearts",
		"10ofhearts",
		"jackofhearts",
		"queenofhearts",
		"kingofhearts",
		"aceofhearts",
		"2ofspades",
		"3ofspades",
		"4ofspades",
		"5ofspades",
		"6ofspades",
		"7ofspades",
		"8ofspades",
		"9ofspades",
		"10ofspades",
		"jackofspades",
		"queenofspades",
		"kingofspades",
		"aceofspades",
	);

require_once "common.php";
checkday();
page_header("Black Jack");
$session['user']['locazione'] = 110;
//checkevent();
output("`c`b`&Black Jack`0`b`c`n`n");
if ($_GET[op]==""){
output("`@Il vecchietto con velocità inaspettata estrae un tavolo di BlackJack.`n`n");
output("`2Mentre lo prepara ti guarda di soppiatto.  Borbotta qualcosa ma non riesci a capire cosa dica. ");
output("Poi ti dice:`n`#\"Il limite di puntata è di 500 pezzi d'oro... se vuoi puoi fare una puntata, altrimenti ");
output("non farmi perdere tempo.\"`n");
$_GET[op]="placebet";
}
if ($_GET[op]=="placebet"){
addnav("Torna alla Taverna","taverna.php");
//had planned on giving players a certain amount of plays per day that is what commented lines are for.... never did this
//if ($session[user][casino] > 0){
output("`@Quanto vuoi puntare ?`n"); 
output("<form action='blackjack.php?op=mybet' method='POST'><input name='mybet' id='mybet'><input type='submit' class='button' value='Fai la tua puntata'></form>",true); 
output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true); 
addnav("","blackjack.php?op=mybet");
//}else{
	//output("You are out of plays!  Return to the Floor and get some.");
	//}
}

if ($_GET[op]=="mybet"){
	$mybet=$_POST[mybet];
	if ($mybet > $session[user][gold]){
		output("`4Non hai $mybet pezzi d'oro.`n");
		addnav("Continue","blackjack.php?op=placebet");
	}elseif ($mybet < 1){
		output("`4Devi fare una puntata per giocare!`n");
		addnav("Continue","blackjack.php?op=placebet");
	}elseif ($mybet > 500){
		output("`4Il limite della casa è di 500 pezzi d'oro, la tua puntata è accettata come 500 pezzi d'oro.`n");
		$mybet=500;
		//$session[user][casino]-=1;
		$_GET[op]="shuffle"; 
	}else{
		//$session[user][casino]-=1;
		$_GET[op]="shuffle";
	}
}

if ($_GET[op]=="shuffle"){
	//shuffle($cards);
	
	srand ((double) microtime() * 10000000);
	for ($i=0;$i<sizeof($cards);$i++) {
       $from=rand(0,sizeof($cards)-1);
       $old=$cards[$i];
       $cards[$i]=$cards[$from];
       $cards[$from]=$old;
	}    

	
	
	$session[user][cards]=implode($cards,",");
	$_GET[op]="deal";
}
if ($_GET[op]=="deal"){	
	if ($mybet < 1) $mybet=$_GET[mybet];
	output("`@`cPuntata: $mybet pezzi d'oro`c");
	$cards=explode(",",$session[user][cards]);
	for ($i=1;$i<52;$i++){
	$cardvalue[$i] = substr($cards[$i],0,1);
	if ($cardvalue[$i]=="1") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="j") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="q") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="k") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="a") $cardvalue[$i]="11";
	}
	$usercard[1]=$cards[1];
	$dealercard[1]=$cards[2];
	$usercard[2]=$cards[3];
	$dealercard[2]=$cards[4];
	$session[user][dealt]=4;
	$dealt=$session[user][dealt];
	output("`n`n`2Mano del banco: ");
    $mycard=$dealercard[1];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    rawoutput("<IMG SRC=\"images/cardback.gif\">\n");
	output("`n`n`2La tua mano: ");
    $mycard=$usercard[1];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    $mycard=$usercard[2];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    $ucardvalue=(intval($cardvalue[1]) + intval($cardvalue[3]));
	addnav("Carta","blackjack.php?op=hit&op2=$dealt&mybet=$mybet");
	addnav("Stai","blackjack.php?op=stand&op2=$dealt&op3=$ucardvalue&mybet=$mybet");
}

if ($_GET[op]=="hit"){	
	$mybet=$_GET[mybet];
	output("`@`cPuntata: $mybet pezzi d'oro`c");
	$ace=0;
	$dealt=($_GET[op2] + 1);
	$session[user][dealt]++;
	$cards=explode(",",$session[user][cards]);
	for ($i=1;$i<52;$i++){
	$cardvalue[$i] = substr($cards[$i],0,1);
	if ($cardvalue[$i]=="1") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="j") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="q") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="k") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="a") $cardvalue[$i]="11";
	}
	$usercard[1]=$cards[1];
	$dealercard[1]=$cards[2];
	$usercard[2]=$cards[3];
	$dealercard[2]=$cards[4];
	output("`n`n`2Mano del banco: ");
    $mycard=$dealercard[1];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    rawoutput("<IMG SRC=\"images/cardback.gif\">\n");
	output("`n`n`2La tua mano: ");
    $mycard=$usercard[1];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    $mycard=$usercard[2];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
	$ucardvalue=(intval($cardvalue[1]) + intval($cardvalue[3]));
	if ($cardvalue[1] == "11") $ace++;
	if ($cardvalue[3] == "11") $ace++;
	for ($i=5;$i<$dealt+1;$i++){
		$j=$i-2;
		$usercard[$j]=$cards[$i];
		if ($cardvalue[$i] == "11") $ace++;
		$ucardvalue+=intval($cardvalue[$i]);
		$mycard=$usercard[$j];
		$mycard.=".gif";
    	rawoutput("<IMG SRC=\"images/$mycard\">\n");
		if ($ucardvalue > 21 and $ace > 0){
			$ucardvalue-=10;
			$ace--;
		}
		if ($ucardvalue > 21 and $ace > 1){
			$ucardvalue-=10;
			$ace--;
		}
		if ($ucardvalue > 21 and $ace > 2){
			$ucardvalue-=10;
			$ace--;
		}
		if ($ucardvalue > 21 and $ace > 3){
			$ucardvalue-=10;
			$ace--;
		}
    }
	if ($ucardvalue > 21){
		$_GET[op] = "bust";
	}else{
		output("`n");
	}
	if ($_GET[op] <> "bust"){
	addnav("Carta","blackjack.php?op=hit&op2=$dealt&mybet=$mybet");
	addnav("Stai","blackjack.php?op=stand&op2=$dealt&op3=$ucardvalue&mybet=$mybet");
}
}

if ($_GET[op]=="stand"){
	$mybet=$_GET[mybet];
	output("`@`cPuntata: $mybet pezzi d'oro`c");
	$ucardvalue=$_GET[op3];
	$dealt=$_GET[op2];
	$ace=0;
	$cards=explode(",",$session[user][cards]);
	for ($i=1;$i<52;$i++){
	$cardvalue[$i] = substr($cards[$i],0,1);
	if ($cardvalue[$i]=="1") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="j") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="q") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="k") $cardvalue[$i]="10";
	if ($cardvalue[$i]=="a") $cardvalue[$i]="11";
	}
	$usercard[1]=$cards[1];
	$dealercard[1]=$cards[2];
	$usercard[2]=$cards[3];
	$dealercard[2]=$cards[4];
	output("`n`n`2Mano del banco: ");
    $mycard=$dealercard[1];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    $mycard=$dealercard[2];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    if ($cardvalue[2] == "11") $ace++;
	if ($cardvalue[4] == "11") $ace++;
    $dcardvalue=($cardvalue[2] + $cardvalue[4]);
	$ddealt = $dealt + 1;
		if ($dcardvalue > 21 and $ace > 0){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 1){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 2){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 3){
			$dcardvalue-=10;
			$ace--;
		}
	if ($dcardvalue < $ucardvalue){
		$dealercard[3]=$cards[$ddealt];
		$mycard=$dealercard[3];
		$mycard.=".gif";
    	rawoutput("<IMG SRC=\"images/$mycard\">\n");
    	$dcardvalue = $dcardvalue + intval($cardvalue[$ddealt]);
    	if ($cardvalue[$ddealt] == "11") $ace++;
    	if ($dcardvalue > 21 and $ace > 0){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 1){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 2){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 3){
			$dcardvalue-=10;
			$ace--;
		}
		$ddealt+=1;
	}
	if ($dcardvalue < $ucardvalue){
		$dealercard[4]=$cards[$ddealt];
		$mycard=$dealercard[4];
		$mycard.=".gif";
    	rawoutput("<IMG SRC=\"images/$mycard\">\n");
    	$dcardvalue = $dcardvalue + intval($cardvalue[$ddealt]);
    	if ($cardvalue[$ddealt] == "11") $ace++;
    	if ($dcardvalue > 21 and $ace > 0){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 1){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 2){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 3){
			$dcardvalue-=10;
			$ace--;
		}
    	$ddealt+=1;
	}
	if ($dcardvalue < $ucardvalue){
		$dealercard[5]=$cards[$ddealt];
		$mycard=$dealercard[5];
		$mycard.=".gif";
    	rawoutput("<IMG SRC=\"images/$mycard\">\n");
    	$dcardvalue = $dcardvalue + intval($cardvalue[$ddealt]);
    	if ($cardvalue[$ddealt] == "11") $ace++;
    	if ($dcardvalue > 21 and $ace > 0){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 1){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 2){
			$dcardvalue-=10;
			$ace--;
		}
		if ($dcardvalue > 21 and $ace > 3){
			$dcardvalue-=10;
			$ace--;
		}
		$ddealt+=1;
	}
	if ($dcardvalue > 21){
		$_GET[op] = "win";
		output("`\$`bSballato!`b");
	}
	output("`n`n`2La tua mano: ");
    $ace=0;
	$mycard=$usercard[1];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
    $mycard=$usercard[2];
	$mycard.=".gif";
    rawoutput("<IMG SRC=\"images/$mycard\">\n");
	$ucardvalue=(intval($cardvalue[1]) + intval($cardvalue[3]));
	if ($cardvalue[1] == "11") $ace++;
	if ($cardvalue[3] == "11") $ace++;
	for ($i=5;$i<$dealt+1;$i++){
		$j=$i-2;
		$usercard[$j]=$cards[$i];
		if ($cardvalue[$i] == "11") $ace++;
		$ucardvalue+=intval($cardvalue[$i]);
		$mycard=$usercard[$j];
		$mycard.=".gif";
    	rawoutput("<IMG SRC=\"images/$mycard\">\n");
		if ($ucardvalue > 21 and $ace > 0) $ucardvalue-=10;
		if ($ucardvalue > 21 and $ace > 1) $ucardvalue-=10;
		if ($ucardvalue > 21 and $ace > 2) $ucardvalue-=10;
		if ($ucardvalue > 21 and $ace > 3) $ucardvalue-=10;
		}
    			
	if ($ucardvalue < $dcardvalue and $dcardvalue < 22) $_GET[op] = "loose";
	if ($ucardvalue > $dcardvalue and $ucardvalue < 22 and $dcardvalue < 22) $_GET[op] = "win";
	if ($ucardvalue == $dcardvalue and $dcardvalue < 22) $_GET[op] = "push";
}

if ($_GET[op]=="bust"){
	output("`\$`bSballato!`b`n");
	$_GET[op] = "loose";
}
if ($_GET[op]=="push"){
	output("`n`#`bParità! Rigioca.`b`n");
	addnav("Continua","blackjack.php?op=shuffle&mybet=$mybet");
}
if ($_GET[op]=="loose"){
	output("`n`n`\$`bHai perso $mybet pezzi d'oro!`b`n");
	$session[user][gold]-=$mybet;
	if ($session[user][gold] >= $mybet) addnav("Punta ancora $mybet pezzi d'oro","blackjack.php?op=shuffle&mybet=$mybet");
	debuglog("perde $mybet oro al BlackJack");
	addnav("Cambia la tua Puntata","blackjack.php?op=placebet");
	addnav("Torna alla Taverna","taverna.php");
}

if ($_GET[op]=="win"){
	output("`n`n`@`bHai vinto $mybet pezzi d'oro!`b`n");
	debuglog("vince $mybet oro al BlackJack");
	$session[user][gold]+=$mybet;
	addnav("Punta ancora $mybet pezzi d'oro","blackjack.php?op=shuffle&mybet=$mybet");
	addnav("Cambia la tua Puntata","blackjack.php?op=placebet");
	addnav("Torna alla Taverna","taverna.php");
}
//I cannot make you keep this line here but would appreciate it left in.
output("`n`n`n`n");
rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Blackjack di Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?>