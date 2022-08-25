<?php
require_once("common.php");
require_once("common2.php");
$month = array(
1=>"Gennaio",
2=>"Febbraio",
3=>"Marzo",
4=>"Aprile",
5=>"Maggio",
6=>"Giugno",
7=>"Luglio",
8=>"Agosto",
9=>"Settembre",
10=>"Ottobre",
11=>"Novembre",
12=>"Dicembre");
page_header("Inserimento data di nascita");
output("`b`c`&Inserimento Data di Nascita`c`b`n`n");
if ($_GET['op'] == "") {
   output("`@Benvenuto nello script tramite il quale potrai comunicarci la tua data di nascita, così che gli altri ");
   output("player di Rafflingate possano farti gli auguri quando compirai gli anni !!`n`n");
   output("Ovviamente puoi anche decidere di non comunicare la tua data di nascita, nel qual caso salteremo la ");
   output("procedura di inserimento e potrai tornare alla piazza del villaggio.`n");
   output("Ma non indugiamo oltre, e proseguiamo con l'inserimento dei dati, oppure torniamo alla piazza di Rafflingate.`n");
   addnav("Inserimento Anno","compleanno.php?op=year");
   addnav("Torna al Villaggio","compleanno.php?op=bypass");
}
if ($_GET['op'] == "year"){
   output("`@Scrivi l'anno di nascita`n");
   output("<form action='compleanno.php?op=yearbis' method='POST'><input name='year' value='0'><input type='submit' class='button' value='Scrivi Anno di Nascita'>`n",true);
   addnav("","compleanno.php?op=yearbis");
}elseif ($_GET['op'] == "yearbis"){
   $year = intval($_POST['year']);
   if (($year + 8) > date('Y') OR $year < 1920) {
       output("`%Non fare il furbo !! Come puoi giocare a LoGD essendo nato nel $year ?`n`n");
       addnav("Riscrivi anno","compleanno.php?op=year");
   } else {
       output("`@Bene, sei nato nel `&$year`@, proseguiamo con l'inserimento del mese.`n`n");
       $session['year']=$year;
       addnav("Inserimento Mese","compleanno.php?op=month");
   }
}
if ($_GET['op'] == "month"){
   if ($_POST['month'] == "") {
      output("<form action='compleanno.php?op=month' method='POST'><input type='submit' class='button' value='Scegli Mese'>`n`n",true);
      output("`2Seleziona il tuo mese di nascita`n");
      output("<input type='radio' name='month' value='1'> `@Gennaio&nbsp&nbsp&nbsp",true);
      output("<input type='radio' name='month' value='2'> Febbraio`n",true);
      output("<input type='radio' name='month' value='3'> Marzo&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp",true);
      output("<input type='radio' name='month' value='4'> Aprile`n",true);
      output("<input type='radio' name='month' value='5'> Maggio&nbsp&nbsp&nbsp&nbsp",true);
      output("<input type='radio' name='month' value='6'> Giugno`n",true);
      output("<input type='radio' name='month' value='7'> Luglio&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp",true);
      output("<input type='radio' name='month' value='8'> Agosto`n",true);
      output("<input type='radio' name='month' value='9'> Settembre",true);
      output("<input type='radio' name='month' value='10'> Ottobre`n",true);
      output("<input type='radio' name='month' value='11'> Novembre",true);
      output("<input type='radio' name='month' value='12'> Dicembre`n",true);
      addnav("","compleanno.php?op=month");
   }else{
      output("`@Bene, sei nato nel mese di `&".$month[$_POST['month']].".`@`n");
      output("Proseguiamo con il giorno di nascita.`n");
      $session['month'] = $_POST['month'];
      addnav("Inserimento Giorno","compleanno.php?op=day");
   }
}
if ($_GET['op'] == "day"){
   if ($_POST['day'] == "") {
      output("<form action='compleanno.php?op=day' method='POST'><input type='submit' class='button' value='Scegli'>`n`n",true);
      output("`2Seleziona il tuo giorno di nascita`n");
      output("<input type='radio' name='day' value='1'> `@1&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='2'> 2&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='3'> 3&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='4'> 4`n",true);
      output("<input type='radio' name='day' value='5'> 5&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='6'> 6&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='7'> 7&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='8'> 8`n",true);
      output("<input type='radio' name='day' value='9'> 9&nbsp&nbsp",true);
      output("<input type='radio' name='day' value='10'> 10",true);
      output("<input type='radio' name='day' value='11'> 11",true);
      output("<input type='radio' name='day' value='12'> 12`n",true);
      output("<input type='radio' name='day' value='13'> 13",true);
      output("<input type='radio' name='day' value='14'> 14",true);
      output("<input type='radio' name='day' value='15'> 15",true);
      output("<input type='radio' name='day' value='16'> 16`n",true);
      output("<input type='radio' name='day' value='17'> 17",true);
      output("<input type='radio' name='day' value='18'> 18",true);
      output("<input type='radio' name='day' value='19'> 19",true);
      output("<input type='radio' name='day' value='20'> 20`n",true);
      output("<input type='radio' name='day' value='21'> 21",true);
      output("<input type='radio' name='day' value='22'> 22",true);
      output("<input type='radio' name='day' value='23'> 23",true);
      output("<input type='radio' name='day' value='24'> 24`n",true);
      output("<input type='radio' name='day' value='25'> 25",true);
      output("<input type='radio' name='day' value='26'> 26",true);
      output("<input type='radio' name='day' value='27'> 27",true);
      output("<input type='radio' name='day' value='28'> 28`n",true);
      if ($session['month'] != 2 OR ($session['year']%4) == 0) {
         output("<input type='radio' name='day' value='29'> 29",true);
      }
      if ($session['month'] != 2) {
         output("<input type='radio' name='day' value='30'> 30",true);
      }
      if ($session['month'] != 2 AND $session['month'] != 4 AND $session['month'] != 6 AND $session['month'] != 9 AND $session['month'] != 11) {
         output("<input type='radio' name='day' value='31'> 31`n",true);
      }
      addnav("","compleanno.php?op=day");
   }else{
      output("`@Bene, sei nato il giorno `&".$_POST['day']."`@`n");
      output("Passiamo al riepilogo.`n");
      $session['day'] = $_POST['day'];
      addnav("Riepilogo","compleanno.php?op=final");
   }
}
if ($_GET['op'] == "final"){
   output("`@Ecco la tua data di nascita: `&".$session['day']." ".$month[$session['month']]." ".$session['year']);
   output("`n`@Confermi questa data o vuoi reinserirla ?");
   addnav("Reinserisci data","compleanno.php?op=year");
   addnav("Conferma data","compleanno.php?op=ok");
}
if ($_GET['op'] == "ok"){
   $date = $session['year']."-".$session['month']."-".$session['day'];
   $session['user']['compleanno'] = $date;
   output("`^Data di Nascita aggiornata. Quando sarà il tuo compleanno apparirà un avviso nel villaggio !");
   $mailmessage = "Il player ".$session['user']['name']." ha inserito il proprio compleanno, cioè $date";
   report(3,"`2Ha inserito il compleanno`2",$mailmessage,"compleanno");

   addnav("Torna al Villaggio","village.php");
}
if ($_GET['op'] == "bypass"){
   output("`(Sei sicuro di non voler far sapere agli altri abitanti di Rafflingate la tua data di nascita?`n");
   output("Se sei convinto, scegli `\$Torna al Villaggio`(, se invece ci hai ripensato  e vuoi condividere ");
   output("la data del tuo compleanno con tutti, scegli `@Inserisci Data`(.`n");
   addnav("`\$Torna al Villaggio","compleanno.php?op=bypassok");
   addnav("`@Inserisci Data","compleanno.php");
}
if ($_GET['op'] == "bypassok"){
   output("`%Bene, hai fatto la tua scelta. Gli altri abitanti non sapranno mai la tua data di nascita e `n");
   output("non potranno di conseguenza farti gli auguri quando compirai gli anni.");
   $session['user']['compleanno'] = "2050-00-00";
   addnav("Torna al Villaggio","village.php");
   $mailmessage = "Il player ".$session['user']['name']." non ha inserito il proprio compleanno.";
   report(3,"`2Non ha inserito il compleanno`2",$mailmessage,"compleanno");
}
page_footer();
?>