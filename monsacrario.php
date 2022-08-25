<?php
require_once "common.php";
require_once("common2.php");
output("<span style='color:#FF0000'>",true);
page_header("Il Sacrario del Monastero");

output("`c<font size='+2'>`3Il Sacrario del Monastero</font>`c`n`n",true);

addcommentary();
checkday();

output("<span style='color:#99CCCC'>",true);
if ($_GET['op'] == "purificazione") {
    $cattiveria=e_rand(1, 5);
    $turni=ceil($session['user']['turnimax']/5);
    output("Assistito da questa presenza eterea, prendi alcune altre pergamene ed esegui i riti da esse descritti.");
    output("La purificazione è impegnativa, ma non senti fatica, anzi ti senti estremamente rilassato e perdi la cognizione del tempo.");
    output("`n`nInfine il processo termina e, congedandoti dalla voce del Sacrario, esci da questa stanza.");
    output("`n`n`^Hai perso $turni turni, ma la tua cattiveria è diminuita di `\$$cattiveria `^punti.");
    $session['user']['evil']-=$cattiveria;
    $session['user']['turns']-=$turni;
    debuglog("utilizza il sacrario e perde $cattiveria punti cattiveria spendendo $turni turni");
    set_pref_pond("sacrario",1);
} else {
    output("Ti trovi al centro di uno tempio circolare di pietra granitica con una scultura religiosa, ");
    output("prendi una delle tante pergamene ed inizi a muovere le labbra seguendo gli antichi testi ...
        `nSperimenti una sensazione di formicolio per tutto il corpo... e prima che tu ti renda conto...");
    if (get_pref_pond("sacrario",0)==0 AND $session['user']['evil']>49 AND $session['user']['turns']>($session['user']['turnimax']/5)) {
        output("`n Senti una voce parlarti: \"`&Benvenuto, viandante. In questo luogo potrai purificare la tua anima, per diventare un po' più buono.");
        output("Io ti assisterò in questo processo di purificazione, ma ti devo avvertire che non sarà una cosa veloce e che consumerai ".ceil($session['user']['turnimax']/5)." turni.`n");
        output("Vogliamo procedere?`0\"");
        addnav("Purificazione","monsacrario.php?op=purificazione");
    } else {
        output(" `n Non succede nulla ...  Ti senti stupido ...");
    }
}
addnav("Allontanati dal Sacrario","monastero.php");

page_footer();
?>