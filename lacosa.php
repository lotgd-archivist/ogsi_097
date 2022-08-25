<?php
require_once "common.php";
if ($session['user']['playerfights'] == 0) {
    page_header("Sei moltooo stanco");
    addnav("Torna al Villaggio","village.php");
    output("`5`nNon hai abbastanza tempo oggi per cercare avventure così impegnativi.");
} else {
    $session['user']['playerfights']--;
    page_header("Ti lanci all'inseguimento");
    output("`n`6Inizi ad avvicinarti a quei grandi occhi che ti guardano da dietro i cespugli senza farti notare. ");
    output("`n`Ti avvicini abbastanza per sentire un terribile puzzo, e la piccola creatura alta poco più di un ");
    output("metro e venti inizia a correre nella foresta verso sud.");
    output("`nCorri dietro quel piccolo essere puzzolente per diversi minuti, quando riesci quasi a raggiungerlo ");
    output("e ad agguantarlo sbatti la testa contro un ramo e cadi a terra.");
    output("`nCerchi di alzarti quasi subito e vedi la creatura che ti sbeffeggia e la riconosci come un Goblin.");
    output("`#Cosa fai?");
    addnav("Insegui ancora","lacosa2.php");
    addnav("Fai finta di essere morto","goblin.php");
    addnav("Torna al villaggio","village.php");
    }
page_footer();
?>