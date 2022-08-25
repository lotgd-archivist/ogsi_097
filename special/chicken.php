<?php
page_header("Il Tagliagole");
output("`c`b`&Il Tagliagole`0`b`c`n`n");
$sql = "SELECT name,evil FROM accounts ORDER BY evil DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
$cattivo = $row['name'];
$cattiveria = $row['evil'];
   if ($session['user']['evil'] > 50 AND $session['user']['evil'] < 100){
       $premio = 10 * $session['user']['evil'];
       output("`5Girovagando nella foresta, alla ricerca di creature da massacrare per dar sfogo ai tuoi istinti malvagi,`n");
       output("ti imbatti nel più pericoloso criminale di Rafflingate, `\$$cattivo`5.`n");
       output("Dopo averti squadrato per bene ti dice:`n`n\"`6Molto bene `@".$session['user']['name']);
       output(" `6sono felice di aver incontrato un essere malvagio quasi quanto me. I tuoi `#".$session['user']['evil']."`6 punti cattiveria, ");
       output("fanno di te un pericoloso criminale, e credo di non sbagliarmi nel dire che ti ritieni soddisfatto del tuo comportamento. ");
       output("Ho deciso di premiare i guerrieri più malvagi del villaggio non appena ne ho la possibilità, ti prego quindi di accettare ");
       output("questi `b`@$premio`6`b pezzi d'oro!!`5\"`n`n Detto ciò ti consegna un borsellino contenente il tuo premio e si avvia ");
       output("nuovamente nella foresta.`n");
       $session['user']['gold'] += $premio;
       addnav("`@Continua","forest.php");
   }else if ($session['user']['evil'] > 99){
       $premio = 1000;
       output("`5Girovagando nella foresta, alla ricerca di creature da massacrare per dar sfogo ai tuoi istinti malvagi,`n");
       output("ti imbatti nel più pericoloso criminale di Rafflingate, `\$$cattivo`5.`n");
       output("Dopo averti squadrato per bene ti dice:`n`n\"`6Molto bene `@".$session['user']['name']);
       output(" `6sono felice di aver incontrato un essere malvagio quasi quanto me. I tuoi `#".$session['user']['evil']."`6 punti cattiveria, ");
       output("fanno di te un pericoloso criminale, e credo di non sbagliarmi nel dire che ti ritieni soddisfatto del tuo comportamento. ");
       output("Ho deciso di premiare i guerrieri più malvagi del villaggio non appena ne ho la possibilità, ti prego quindi di accettare ");
       output("questi `b`@$premio`6`b pezzi d'oro!!`5\"`n`n Detto ciò ti consegna un borsellino contenente il tuo premio e si avvia ");
       output("nuovamente nella foresta.`n");
       $session['user']['gold'] += $premio;
       addnav("`@Continua","forest.php");
   }else {
       output("`5Mentre ti aggiri furtivamente nella foresta, noti con la coda dell'occhio un movimento alla tua destra. `n");
       output("Estrai velocemente la tua `^".$session['user']['weapon']." `5e ti dirigi verso il cespuglio, per scoprire che `n");
       output("era solo un pollo. Cerchi di colpirlo con la tua arma, ma il pollo riesce a schivare il colpo ed allora `n");
       output("inizi ad inseguirlo, per procurarti qualcosa da mangiare.`n`n");
       if (e_rand(0,3) == 0){
          output("`5Dopo numerosi tentativi riesci alla fine a colpire il pollo e lo divori dopo averlo spennato,`n");
          output("anche se per inseguirlo hai dovuto utilizzare uno dei tuoi combattimenti in foresta.`n`n");
          $session['user']['hunger'] -= 30;
       }else{
          output("`5Dopo numerosi ed infruttuosi tentativi di catturare il pollo, sentendoti assalire dalla stanchezza, decidi`n");
          output("di lasciar perdere e torni sui tuoi passi, sapendo di aver sprecato un turno combattimento.`n`n");
       }
       $session['user']['turns'] --;
       addnav("`@Continua","forest.php");
   }
page_footer();
?>