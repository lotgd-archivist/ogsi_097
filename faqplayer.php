<?php
/*******************************************
/ faqplayer.php
/ Originally by Excalibur (www.ogsi.it)
/ 18 Jenuary 2007
/
--------------------------------------------
/ Version History:
/ Ver. 1.0 created by Excalibur (www.ogsi.it)
********************************************
/ Originally by: Excalibur
*/

require_once("common.php");
page_header("FAQ Player");
checkday();
if (!isset($session)) exit();
if ($session['return']==""){
   $session['return']=$_GET['ret'];
}
if ($session['user']['loggedin']) {
    checkday();
    if ($session['user']['alive']) {
        if ($session['return']==""){
        }else{
            $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
            $return = substr($return,strrpos($return,"/")+1);
            addnav("Torna da dove sei venuto",$return);
        }
    } else {
    }
}else{
    addnav("Torna al Login","login.php");
}
switch ($_GET['op']) {
case "":
     output("`&Queste FAQ (Frequently Asked Questions = Domande Frequenti) sono tratte direttamente ");
     output("dalle domande poste dei giocatori con le risposte date da Admin e Moderatori.`n");
     output("Potete inserire una parola (ad es. \"tenute\") per cercare le FAQ che riguardano le tenute, ");
     output("oppure leggere tutte le FAQ inserite in un solo colpo.`n`n");
     output("All'inizio non troverete molte informazioni, ma mano a mano che verranno inserite, questa sezione ");
     output("si arricchirà sempre più fino a diventare una vera e propria guida per avere una risposta alle proprie ");
     output("domande.`n");
     addnav ("Informazioni");
     addnav ("`&Leggi Tutte le FAQ","faqplayer.php?op=tutto");
     addnav ("`^Cerca parola","faqplayer.php?op=cerca");
     //addnav ("`7Torna al villaggio","village.php");
     break;

case "tutto":
     $sql = "SELECT * FROM faq ORDER BY id ASC";
     $result = db_query($sql) or die(db_error(LINK));
     if (db_num_rows($result) == 0) {
         output("`(`b`cNESSUNA FAQ TROVATA !!!`b`c");
     }else{
         output("<table cellspacing=2 cellpadding=2 align='center'>",true);
         output("<tr bgcolor='#FF0000'>",true);
         output("<td width='115'>`&`bDomanda`b`0</td><td width='115'>`&`bRisposta`b`0</td></tr>",true);
         $countrow = db_num_rows($result);
         for ($i=0; $i<$countrow; $i++){
         //for ($i=0;$i<db_num_rows($result);$i++){
             $row = db_fetch_assoc($result);
             output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>`&".$row['domanda']."`0</td>",true);
             output("<td>`@".$row['risposta']."`0</td></tr>", true);
         }
         output("</table>",true);
     }
     addnav ("T?Torna indietro","faqplayer.php");
     break;

case "cerca":
    output("`3Inserisci il testo da ricercare nella FAQ.`n");
    output("<form action=\"faqplayer.php?op=ricerca\" method=\"post\">",true);
    output("<input name=\"chiave\" type=\"text\" value=\"Scrivi\" parola di ricerca\" size=\"40\" maxlength=\"40\" />",true);
    output("<input type=\"submit\" class=\"button\" value=\"Invia\" /></form>",true);
    addnav("","faqplayer.php?op=ricerca");
    break;
case "ricerca":
    //echo ("chiave di ricerca: ".$_POST['chiave']."<br>");
    $sql = "SELECT * FROM faq WHERE domanda LIKE '%".$_POST['chiave']."%' OR risposta LIKE '%".$_POST['chiave']."%' ORDER BY id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("`(Nessuna FAQ trovata con la chiave di ricerca indicata.`n");
        addnav("Torna all'inizio","faqplayer.php");
    }else{
        output("<table cellspacing=2 cellpadding=2 align='center'>",true);
        output("<tr bgcolor='#FF0000'>",true);
        output("<td width='115'>`&`bDomanda`b`0</td><td width='115'>`&`bRisposta`b`0</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td>`&".$row['domanda']."`0</td>",true);
            output("<td>`@".$row['risposta']."`0</td></tr>", true);
        }
        output("</table>",true);
    }
     addnav ("T?Torna indietro","faqplayer.php");
     break;

}
rawoutput("<br><div style=\"text-align: right ;\"><a href=\"http://www.ogsi.it\" target=\"_blank\"><font color=\"#33FF33\">FAQ Player by Excalibur @ http://www.ogsi.it</font></a><br>");
page_footer();
?>

