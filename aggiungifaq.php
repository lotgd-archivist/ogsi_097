<?php
require_once "common.php";
isnewday(2);
page_header("Visore Petizioni");
if ($_GET['op'] == "" OR $_GET['op'] == "modifica"){
    if ($_GET['op'] == "modifica"){
        $_POST['domanda'] = $session['domanda'];
        $_POST['risposta'] = $session['risposta'];
        //echo ("ID: ".$_GET['key']."<br>");
    }
    output("`3Inserisci il testo della domanda (FAQ) e della risposta`n");
    output("<form action='aggiungifaq.php?op=inserisci&key=".$_GET['key']."' method='POST'>",true);
    output("<textarea class='input' name='domanda' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['domanda']))."</textarea>`n",true);
    output("`n`n");
    output("<textarea class='input' name='risposta' cols='40' rows='10'>".HTMLEntities(stripslashes($_POST['risposta']))."</textarea>`n",true);
    output("<input type='submit' class='button' value='Invia'></form>",true);
    addnav("","aggiungifaq.php?op=inserisci&key=".$_GET['key']);
    addnav("Correggi FAQ","aggiungifaq.php?op=correggi");
} elseif ($_GET['op'] == "inserisci"){
    //echo ("ID: ".$_GET['key']."<br>");
    $session['domanda'] = $_POST['domanda'];
    $session['risposta'] = $_POST['risposta'];
    output("<table cellspacing=0 cellpadding=2 align='center' name='Aggiungi FAQ'><tr>",true);
    output("<td>`bDomanda`b</td><td>`bRisposta`b</td></tr>",true);
    output("<tr class='trlight'><td align='left'>`@".stripslashes($session['domanda'])."</td><td>`%".stripslashes($session['risposta'])."</td></tr>",true);
    output("</table>",true);
    output("`n`n`\$`nConfermi che vuoi inserire nelle FAQ la domanda/risposta sopra riportate ?`n");
    if ($_GET['key'] == 0){
       addnav("S?`@SI, aggiungi","aggiungifaq.php?op=aggiungi");
    }else{
       addnav("S?`@SI, aggiungi","aggiungifaq.php?op=aggiungiold&key=".$_GET['key']);
    }
    addnav("N?`\$NO, modifica","aggiungifaq.php?op=modifica");
} elseif ($_GET['op'] == "aggiungi"){
    $sql = "INSERT INTO faq VALUES ('','".$session['domanda']."','".$session['risposta']."')";
    $result = db_query($sql) or die(db_error(LINK));
    output("`#FAQ Aggiunta !!!`n`n");
    addnav("A?Aggiungi FAQ","aggiungifaq.php");
    addnav("P?Torna alle Petizioni","viewpetition.php");
} elseif ($_GET['op'] == "aggiungiold"){
    //echo ("ID: ".$_GET['key']."<br>");
    $sql = "UPDATE faq SET domanda ='".$session['domanda']."', risposta='".$session['risposta']."' WHERE id = ".$_GET['key'];
    $result = db_query($sql) or die(db_error(LINK));
    output("`#FAQ Aggiunta !!!`n`n");
    addnav("A?Aggiungi FAQ","aggiungifaq.php");
    addnav("P?Torna alle Petizioni","viewpetition.php");
} elseif ($_GET['op'] == "correggi"){
    output("`3Inserisci il testo da ricercare nella FAQ.`n");
    output("<form action=\"aggiungifaq.php?op=ricerca\" method=\"post\">",true);
    output("<input name=\"chiave\" type=\"text\" value=\"Scrivi\" parola di ricerca\" size=\"40\" maxlength=\"40\" />",true);
    output("<input type=\"submit\" class=\"button\" value=\"Invia\" /></form>",true);
    addnav("","aggiungifaq.php?op=ricerca");
} elseif ($_GET['op'] == "ricerca"){
    //echo ("chiave di ricerca: ".$_POST['chiave']."<br>");
    $sql = "SELECT * FROM faq WHERE domanda LIKE '%".$_POST['chiave']."%' OR risposta LIKE '%".$_POST['chiave']."%' LIMIT 1";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) == 0) {
        output("`(Nessuna FAQ trovata con la chiave di ricerca indicata.`n");
        addnav("Torna all'inizio","aggiungifaq.php");
    }else{
        $row= db_fetch_assoc($result);
        $session['domanda'] = $row['domanda'];
        $session['risposta'] = $row['risposta'];
        output("<table cellspacing=0 cellpadding=2 align='center' name='Aggiungi FAQ'><tr>",true);
        output("<td>`bDomanda`b</td><td>`bRisposta`b</td></tr>",true);
        output("<tr class='trlight'><td align='left'>`@".stripslashes($session['domanda'])."</td><td>`%".stripslashes($session['risposta'])."</td></tr>",true);
        output("</table>",true);
        output("`n`n`\$`nVuoi modificare questa FAQ ?`n");
        addnav("S?`@SI, modifica","aggiungifaq.php?op=modifica&key=".$row['id']);
        addnav("N?`\$NO, cercane un'altra","aggiungifaq.php?op=correggi");
    }
}
//addnav("`#C?Correggi FAQ","aggiungifaq.php?op=correggi.php");
addnav("`^G?Torna alla Grotta","superuser.php");
page_footer();
?>