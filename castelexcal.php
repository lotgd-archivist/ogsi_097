<?php
require_once("common.php");
require_once("common2.php");
manutenzione(getsetting("manutenzione",3));
addcommentary();
checkday();
if (isset($session['sort'])){
   unset ($session['sort']);
   unset ($session['modo']);
}
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
addnav("Castello Excalibur");
addnav("");
addnav("Cortile");
addnav("A?`2Arena","arena.php");
addnav("P?`2Palestra","swarzy.php");
addnav("C?Cooperativa Mercenari","mercguild.php");
addnav("E?`4Eros Esotico","eros.php");
addnav("F?`7La Farmacia","adriana.php");
addnav("G?`&Grotta dello Gnomo","grotto.php");
addnav("M?`%Emporio Magico","emporio_mag.php");
addnav("D?Casa Del Piacere","casachiusa.php");
addnav("Esterno del Castello");
if ($session['user']['dragonkills'] > 4 OR $session['user']['reincarna'] > 0) {
   addnav("T?`^Torneo di LoGD","torneo.php");
   addnav("C?`^Classifica Torneo","torneoclas.php");
}
addnav("Sentieri");
addnav("V?`@Torna al Villaggio","village.php");
addnav("F?`\$Vai alla Foresta","forest.php");
page_header("Castel Excalibur");
$session['user']['locazione'] = 114;
output("`3`c`bCastello Excalibur`b`c `n `n");
output(" `2Benvenuto al `3Castel Excalibur`2. Poichè ci sono stati tafferugli ultimamente `n");
output(" è consentito l'ingresso solo ad alcune sezioni del castello. `nPresto riapriremo anche le sezioni attualmente chiuse.`n");
output("`2Ogni lato di `3Castel Excalibur `2è circondato da un fossato e da una oscura foresta.`n`n");
output("L'orologio della torre batte le  `^".getgametime()."`@.");
output("`n`n`6Nei pressi odi altri viaggiatori chiaccherare tra di loro:`n");
viewcommentary("Castel Excalibur","parla con i viaggiatori",15,10);

page_footer();
?>