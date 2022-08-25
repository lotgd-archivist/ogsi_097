<?php
/***************************************
The High Priest
Written by Robert for Maddnet.com LoGD
Belongs with the Monastery
22Feb2004
Tradotto da Excalibur x www.ogsi.it/logd
****************************************/
require_once "common.php";
checkday();
$base=300;
$dk = $session['user']['dragonkills'] + ($session['user']['reincarna']*15);
$lvl = $session['user']['level'];
$prezzo=$base+($dk*50)+($lvl*10);
addnav("Benedizioni Cimiteriali");
addnav("(1) Favori - $prezzo oro","monpriore.php?op=favor");
addnav("(2) Tormenti - ".($prezzo*2)." oro","monpriore.php?op=fight");
addnav("(3) Anima - $prezzo oro","monpriore.php?op=soul");
addnav("Abbandona");
addnav("(T) Torna al Monastero","monastero.php");
page_header("Padre Priore del Monastero");
if ($_GET[op]==""){
output("`c<font size='+1'>`3Padre Priore del Monastero</font>`c`n",true);
output("`n`2Entri in un misterioso edificio, il luogo è buio, illuminato solo da qualche candela.`n");
output(" Ti avvicini lentamente alla figura nel retro. Che sembra assorto in profonda preghiera.`n");
output(" Mentre ti avvicini , la figura si alza girandosi. Di fronte a te c'è il `3Padre Priore del Monastero`2.`n");
output(" Ti sono giunte voci dai monaci che il Priore sia in contatto con il soprannaturale.`n");
output(" Il `3Padre Priore `2inizia a parlare dicendoti che una donazione al `3Monastero`2 ti farà guadagnare`n");
output("una benedizione che ti porterà vantaggi nella `4Terra delle Ombre`2, nel caso tu dovessi morire oggi. `n");

}else if ($_GET[op]=="favor"){
if ($session[user][gold] >= $prezzo){
     $favori=e_rand(8,12);
     $session['user']['deathpower']+=$favori;
     $session[user][gold]-=$prezzo;
     output("`n`n`2Il `3Padre Priore `2accetta la tua offerta di `^$prezzo pezzi d'oro`2,`n");
     output(" ti dice di inginocchiarti, e mentre lo fai inizia a cantare in una lingua a te sconosciuta.`n");
     output(" Dopo alcuni minuti - un`b`&  L A M P O  di luce `b`2sembra attraversare il tuo corpo.`n");
     output(" I tuoi occhi si adattano lentamente, ma percepisci nel profondo di te stesso un senso di vicinanza con `4Ramius`2.`n");
     output(" Guadagni $favori favori con `4Ramius`2.`n");
     debuglog("offre $prezzo oro al Padre Priore per $favori favori con Ramius");
} else {
             output("`n`n`&Non ti preoccupare - non puoi permetterti un'offerta - pregherò per te.");
}
}else if ($_GET[op]=="fight"){
if ($session[user][gold] >= ($prezzo*2)){
     $fights=e_rand(1,3);
     $session['user']['gravefights']+=$fights;
     $session[user][gold]-=$prezzo*2;
     output("`n`n`2Il `3Padre Priore `2accetta la tua offerta di `^$prezzo pezzi d'oro`2,`n");
     output(" ti dice di inginocchiarti, e mentre lo fai inizia a cantare in una lingua a te sconosciuta.`n");
     output(" Dopo alcuni minuti - `b`&   V i b r a z i o n i `b`2 attraversano il tuo corpo.`n");
     output(" Il tuo corpo si adatta lentamente, e percepisci nel profondo di te stesso un'abilità maggiore di uccidere fantasmi.`n");
     output(" Guadagni $fights Tormenti nel Cimitero.`n");
     debuglog("offre $prezzo oro al Padre Priore e guadagna $fights tormenti");
} else {
             output("`n`n`&Non ti preoccupare - non puoi permetterti un'offerta - pregherò per te.");
}
}else if ($_GET[op]=="soul"){
if ($session[user][gold] > ($prezzo-1)){
     $anima=e_rand(5,10);
     $session['user']['soulpoints']+=$anima;
     $session[user][gold]-=$prezzo;
     output("`n`n`2Il `3Padre Priore `2accetta la tua offerta di `^$prezzo pezzi d'oro`2,`n");
     output(" ti dice di inginocchiarti, e mentre lo fai inizia a cantare in una lingua a te sconosciuta.`n");
     output(" Dopo alcuni minuti - un`b`&  F o r m i c o l i o `b`2sembra attraversare il tuo corpo.`n");
     output(" Il tuo corpo ritorna all normalità e percepisci nel profondo di te stesso che la tua paura della morte è diminuita.`n");
     output(" Guadagni $anima SoulPoints.`n");
     debuglog("offre $prezzo oro al Padre Priore e guadagna $anima SoulPoints");
} else {
             output("`n`n`&Non ti preoccupare - non puoi permetterti un'offerta - pregherò per te.");
}
}
page_footer();
?>