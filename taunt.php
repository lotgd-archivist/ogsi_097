<?php
require_once "common.php";
isnewday(2);

page_header("Taunt Editor");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
if ($_GET[op]=="edit"){
    addnav("Torna all'Editor di Frasi","taunt.php");
    output("<form action='taunt.php?op=save&tauntid=$_GET[tauntid]' method='POST'>",true);
    addnav("","taunt.php?op=save&tauntid=$_GET[tauntid]");
    if ($_GET[tauntid]!=""){
        $sql = "SELECT * FROM taunts WHERE tauntid=\"$_GET[tauntid]\"";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $taunt = $row[taunt];
        $taunt = str_replace("%s","lui",$taunt);
        $taunt = str_replace("%o","egli",$taunt);
        $taunt = str_replace("%p","suo",$taunt);
        $taunt = str_replace("%x","Pointy Twig",$taunt);
        $taunt = str_replace("%X","Sharp Teeth",$taunt);
        $taunt = str_replace("%W","Large Green Rat",$taunt);
        $taunt = str_replace("%w","JoeBloe",$taunt);
        output("Preview: $taunt`0`n`n");
    }
    $output.="Taunt: <input name='taunt' value=\"".HTMLEntities($row[taunt])."\" size='70'><br>";
    output("Sono supportati i seguenti codici (presta attenzione a maiusc/minusc):`n");
    output("%w = Nome del Perdente`n");
    output("%x = Arma del Perdente`n");
    output("%s = Soggettivo del Perdente (lui lei)`n");
    output("%p = Possessivo Perdente (suo sua)`n");
    output("%o = Oggettivo del Perdente (egli ella)`n");
    output("%W = Nome del Vincitore`n");
    output("%X = Arma del Vincitore`n");
    output("<input type='submit' class='button' value='Salva'>",true);
    output("</form>",true);
}else if($_GET[op]=="del"){
    $sql = "DELETE FROM taunts WHERE tauntid=\"$_GET[tauntid]\"";
    db_query($sql) or die(db_error(LINK));
    redirect("taunt.php?c=x");
}else if($_GET[op]=="save"){
    if ($_GET[tauntid]!=""){
        $sql = "UPDATE taunts SET taunt=\"$_POST[taunt]\",editor=\"".addslashes($session[user][login])."\" WHERE tauntid=\"$_GET[tauntid]\"";
    }else{
        $sql = "INSERT INTO taunts (taunt,editor) VALUES (\"$_POST[taunt]\",\"".addslashes($session[user][login])."\")";
    }
    db_query($sql) or die(db_error(LINK));
    redirect("taunt.php?c=x");
}else{
    $sql = "SELECT * FROM taunts";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table>",true);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row=db_fetch_assoc($result);
        output("<tr>",true);
        output("<td>",true);
        output("[<a href='taunt.php?op=edit&tauntid=$row[tauntid]'>Edita</a>|<a href='taunt.php?op=del&tauntid=$row[tauntid]' onClick='return conferma(\"Sei sicuro di voler cancellare questa frase?\");'>Del</a>]",true);
        addnav("","taunt.php?op=edit&tauntid=$row[tauntid]");
        addnav("","taunt.php?op=del&tauntid=$row[tauntid]");
        output("</td>",true);
        output("<td>",true);
        output($row[taunt]);
        output("</td>",true);
        output("<td>",true);
        output($row[editor]);
        output("</td>",true);
        output("</tr>",true);
    }
    addnav("","taunt.php?c=$_GET[c]");
    output("</table>",true);
    addnav("F?Aggiungi una nuova Frase","taunt.php?op=edit");
}
page_footer();
?>