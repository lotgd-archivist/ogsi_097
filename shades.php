<?php
require_once "common.php";
$session['chat'] = 0;
page_header("Terra delle Ombre");
$session['user']['locazione'] = 173;
addcommentary();
checkday();
$session['user']['risorto'] = "1";
if ($session['user']['alive']) redirect("village.php");
if ($session['user']['acctid']==getsetting("hasegg",0)) addnav("Usa l'`6Uovo d'Oro","newday.php?resurrection=egg");
if ($session['user']['npg']==1 OR $session['user']['superuser']>0) addnav("Resurrezione","newday.php?resurrection=true");

output("`\$Ora cammini tra i morti, sei un'ombra. Intorno a te ci sono le anime di coloro che sono morti in battaglia, di vecchiaia,
ed in spiacevoli incidenti. Ognuno ha i segni del modo in cui ha incontrato la sua fine.
`n`n
Le loro anime sussurrano i loro tormenti, ossessionando la tua mente con la loro disperazione:`n");
viewcommentary("shade","Disperati",30,30,"si dispera");
//Excalibur: modifica per voto web
/*$ora = strftime("%H",time());
if (( (getsetting("topwebid", 0) != "") AND (strtotime($session['user']['lastwebvote']) < strtotime(date("Y-n-j")-86400) ) ) OR $session['user']['donation']==0) {
    if($session['user']['donation'] == 0) $session['user']['donation']=1;
    addnav("Miglior Sito");
    addnav("V?`&Vota per OGSI", "village1.php");
}
*/
//Excalibur: fine modifica per voto web
addnav("Il Cimitero","graveyard.php");
addnav("Torna alle Notizie","news.php");
if ($session['user']['superuser']>=2){
  addnav("Grotta del SuperUtente","superuser.php");
}

page_footer();
?>