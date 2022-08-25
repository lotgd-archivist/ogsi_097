<?php
require_once "common.php";
page_header("Il TORNEO");
$session['user']['locazione'] = 182;
output("<font size='+2'>`b`c`!Il TORNEO di LoGD`0`c`b`n</font>",true);

if ($session['user']['superuser'] > 2){
   output("`@Scrivi data scadenza torneo`n");
   output("<form action='torneo.php?op1=data' method='POST'><input name='data' value='0'><input type='submit' class='button' value='Data Scadenza Torneo'>`n",true);
   addnav("","torneo.php?op1=data");
}
if ($_GET['op1'] == "data"){
   $data = $_POST['data'];
   savesetting("datatorneo", $data);
}


if ($session['user']['torneo']==0 AND $_GET[op]==""){
    output("`3Non ti sei ancora iscritto alla `#Ennesima Edizione del Grande Torneo`3 di `@Legend of the Green Dragon`3`n");
    output("La tassa di iscrizione è di `b`&1 Gemma`b`3 e `b`^1000 Pezzi d'Oro`b`3.`n ");
    output("Per questa sesta edizione del Torneo i premi che saranno consegnati ai primi tre guerrieri classificati sono i seguenti:`n`n");
    output("<font size='+1'>`b`c`\$1° Classificato: 10 Gemme - 5 HP Permanenti`n",true);
    output("`%2° Classificato:  8 Gemme -  4 HP Permanenti`n",true);
    output("`!3° Classificato:  5 Gemme -  3 HP Permanenti`b`c`n</font>",true);
    output("`n`c`#Non sei tentato dai premi ? Cosa aspetti ad iscriverti ... potresti essere proprio tu il vincitore ");
    output("di questo`n`n <font size='+2'>`!`bI`^m`@m`#e`%n`\$s`&o `^Tesoro !!!`c`b</font>`n",true);
    output("`n`n`@L'Ennesima Edizione del Torneo si chiuderà il giorno `%".getsetting("datatorneo","")."`@.`n");
    addnav("S?`@Si, voglio iscrivermi","torneo.php?op=iscrivi");
    addnav("N?`\$No, non ci penso nemmeno","castelexcal.php");
}elseif ($session['user']['torneo']!=0){
    output("`#E' giunto il momento di mettere alla prova le tue capacità ... raggiungi l'Arena del Torneo.");
    addnav("V?`@Vai all'Arena del Torneo","torneo2.php");
    addnav("T?`#Torna a Castel Excalibur","castelexcal.php");
}elseif($_GET[op]=="iscrivi" AND $session['user'][gems]>0 AND $session['user'][gold]>999){
    output("`2Bene, ti sei iscritto al torneo. Adesso hai accesso alle diverse prove, una per livello.`n");
    output("Sfrutta tutte le prove, ognuna ti darà dei punti per avanzare nella classifica. Non rilassarti, gli ");
    output("altri giocatori non avranno pietà di te se rimarrai indietro !!`n Adesso vai, la prima prova ti attende, ");
    output("non perdere altro tempo !!");
    //$session[user][torneopoints]=array();
    //$session['user']['torneopoints']['0']="zero";
    $session['user']['gems']-=1;
    $session['user']['gold']-=1000;
    $session['user']['torneo']=1;
    /* Per azzerare un player
    $ident=829;
    $sql="UPDATE `accounts` SET `torneopoints` = '' WHERE `acctid` = $ident";
    $result=db_query($sql); */
    addnav("V?`^Vai al torneo","torneo.php");
}else{
    output("`%Mi spiace, ma non hai gemme/oro a sufficienza !! Ti ricordo che i pezzi d'oro devi averli in mano, ");
    output("non basta averli in banca. Quando avrai la gemma ed i 1000 pezzi d'oro ritorna, sarò ben felice di ");
    output("accettare la tua iscrizione.`n");
    addnav("T?`2Torna a Castel Excal","castelexcal.php");
    addnav("V?`3Torna al Villaggio","village.php");
}
page_footer();
?>