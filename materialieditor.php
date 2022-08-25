<?php
require_once "common.php";
isnewday(3);
reset($_POST);
if (is_array($_POST['mount'])) reset($_POST['mount']);

page_header("Editor Materiali");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiungi un materiale","materialieditor.php?op=add");
addnav("Dai materiale a player","materialieditor.php?op=addmatpla");
addnav("Situazione ricette oggetti","materialieditor.php?op=ro");
addnav("Migliori","materialieditor.php?op=migliori");
addnav("Ricarica","materialieditor.php");
if ($session['user']['superuser'] > 3) {
   addnav("Heimdall","materialieditor.php?op=heimdall");
}
addnav("Pulizia DB zaino","materialieditor.php?op=pulizia");
if ($_GET['op']==""){
    $sql = "SELECT * FROM materiali ORDER BY tipo ASC";
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td>Ops</td><td>ID</td><td>Nome</td><td>Costo Monete</td>
    <td>Costo Gemme</td><td>Tipo</td><td>Livello</td></tr>",true);
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'>",true);
        output("<td>[<a href='materialieditor.php?op=edit&id=".$row['id']."'>Edit</a>]",true);
        addnav("","materialieditor.php?op=edit&id=".$row['id']);
        output("<td align='right'>".$row['id']."</td>",true);
        output("<td>".$row['nome']."</td>",true);
        output("<td>".$row['valoremo']." oro</td>",true);
        output("<td>".$row['valorege']." gemme</td>",true);
        output("<td>".$row['tipo']."</td>",true);
        output("<td>".$row['livello']."</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
}elseif ($_GET['op']=="add"){
    addnav("Home Editor Materiali","materialieditor.php");
    creamateriali(array());
}elseif ($_GET['op']=="addmatpla"){
    addnav("Home Editor Materiali","materialieditor.php");
    output("Il tuo id: ".$session[user][acctid]."`n`n");
    daimateriali(array());
}elseif ($_GET['op']=="edit"){
    addnav("Editor Oggetti Home","materialieditor.php");
    $sql = "SELECT * FROM materiali WHERE id='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`iMateriale non trovato.`i");
    }else{
        output("Editor Materiali:`n");
        $row = db_fetch_assoc($result);
        creamateriali($row);
    }
}elseif ($_GET['op']=="save"){
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    while (list($key,$val)=each($_POST['mount'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['id']>""){
            $sql.=($i>0?",":"")."$key='$val'";
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
    if ($_GET['id']>""){
        $sql="UPDATE materiali SET $sql WHERE id='{$_GET['id']}'";
    }else{
        $sql="INSERT INTO materiali ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Materiale salvato!");
    }else{
        output("Materiale non salvato: $sql");
    }
    addnav("Home Editor Materiali","materialieditor.php");
}elseif ($_GET['op']=="salva"){
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    while (list($key,$val)=each($_POST['mount'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['id']>""){
            $sql.=($i>0?",":"")."$key='$val'";
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
    if ($_GET['id']>""){
        $sql="UPDATE zaino SET $sql WHERE id='{$_GET['id']}'";
    }else{
        $sql="INSERT INTO zaino ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Materiale salvato!");
    }else{
        output("Materiale non salvato: $sql");
    }
    addnav("Home Editor Materiali","materialieditor.php");
}elseif ($_GET['op']=="ro"){
    $sql = "SELECT * FROM materiali where tipo='R' ORDER BY id";
    output("<table>",true);
    output("<tr><td>ID</td><td>Ricetta</td><td>Serbatoio</td><td>ricette in game</td><td>oggetti nel serbatoio</td></tr>",true);
    $result = db_query($sql);

    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr>",true);
        output("<td>{$row['id']}</td>",true);
        output("<td>{$row['nome']}</td>",true);
        output("<td>{$row['dove_oggetto_creato']}</td>",true);
        $sqlz = "SELECT * FROM zaino where idoggetto='{$row['id']}'";
        $resultz = db_query($sqlz);
        $numero_ricette = db_num_rows($resultz);
        $sqlo = "SELECT * FROM oggetti where dove='{$row['dove_oggetto_creato']}'";
        $resulto = db_query($sqlo);
        $numero_oggetti = db_num_rows($resulto);
        output("<td>$numero_ricette</td>",true);
        output("<td>$numero_oggetti</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
}
if ($_GET[op]=="migliori") {
     output("In un angolo su una lavagnetta Oberon indica i migliori fabbri. Solo il primo può fregiarsi del titolo di Mastro.`n`n");
    $sqlo = "SELECT * FROM accounts WHERE punti_carriera >= 1 AND carriera >=5 AND carriera <=8 ORDER BY punti_carriera DESC";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bPunti`b</td></tr>", true);
   if (db_num_rows($resulto) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono fabbri in paese`0</td></tr>", true);
        }
        $countrowo = db_num_rows($resulto);
        for ($i=0; $i<$countrowo; $i++){
        //for ($i = 0;$i < db_num_rows($resulto);$i++) {
            $rowo = db_fetch_assoc($resulto);
             if ($rowo[superuser] == 0) {
                if ($row[name] == $session[user][name]) {
                    output("<tr bgcolor='#007700'>", true);
                } else {
                    output("<tr class='" . (($i+$k) % 2?"trlight":"trdark") . "'>", true);
                }
                output("<td>" . ($i + 1 + $k) . ".</td><td>$rowo[name]</td><td>$rowo[punti_carriera]</td></tr>", true);
             }else {
                $k = $k - 1;
                }
        }
        output("</table>", true);
        // CHIESA
        output("Specchio di Sgrios.`n`n");
    $sqlo = "SELECT * FROM accounts WHERE punti_carriera >= 1 AND ((carriera >=1 AND carriera <=4) OR carriera=9) ORDER BY punti_carriera DESC";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>&nbsp;</td><td>`bNome`b</td><td>`bPunti`b</td></tr>", true);
   if (db_num_rows($resulto) == 0) {
            output("<tr><td colspan=4 align='center'>`&Non ci sono religiosi in paese`0</td></tr>", true);
        }
        $countrowo = db_num_rows($resulto);
        for ($i=0; $i<$countrowo; $i++){
        //for ($i = 0;$i < db_num_rows($resulto);$i++) {
            $rowo = db_fetch_assoc($resulto);
             if ($rowo[superuser] == 0) {
                if ($row[name] == $session[user][name]) {
                    output("<tr bgcolor='#007700'>", true);
                } else {
                    output("<tr class='" . (($i+$k) % 2?"trlight":"trdark") . "'>", true);
                }
                output("<td>" . ($i + 1 + $k) . ".</td><td>$rowo[name]</td><td>$rowo[punti_carriera]</td></tr>", true);
             }else {
                $k = $k - 1;
                }
        }
        output("</table>", true);


}
if ($_GET[op]=="heimdall") {
$ultima_volta = getsetting("ultima_volta", 0);
$prossima_visita = getsetting("prossima_volta", 0);
$data_oggi = time();
$tornagiorni = (($ultima_volta+$prossima_visita-86400)-$data_oggi)/3600;
output("Heimdall tornerà tra $tornagiorni ore.");

}
if ($_GET[op]=="pulizia") {
    output("Operazione che può durare alcuni minuti attendi!.`n");
    $sqlo = "SELECT idplayer FROM zaino";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $tot=db_num_rows($resulto);
    for ($i = 0;$i < $tot;$i++) {
            $rowo = db_fetch_assoc($resulto);
            $sqlc = "SELECT acctid FROM accounts WHERE acctid='".$rowo['idplayer']."'";
            $resultc = db_query($sqlc) or die(db_error(LINK));
            if(db_num_rows($resultc)==0){
                $sqle = "DELETE FROM zaino WHERE idplayer='".$rowo['idplayer']."'";
                db_query($sqle);
                output("`\$Eliminato materiale $rowo[idplayer]`n");
            }
     output("`#Esaminato materiale $i su $tot`n");
     }
     output("Operazione completata");

}
function creamateriali($mount){
    global $output;
    output("<form action='materialieditor.php?op=save&id={$mount['id']}' method='POST'>",true);
    addnav("","materialieditor.php?op=save&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Nome:</td><td><input name='mount[nome]' value=\"".HTMLEntities2($mount['nome'])."\"></td></tr>";
    $output.="<tr><td>Tipo (M/R/P):</td><td><input name='mount[tipo]' value=\"".HTMLEntities2($mount['tipo'])."\"> Materiale/Ricetta/Pergamena</td></tr>";
    $output.="<tr><td>Livello:</td><td><input name='mount[livello]' value=\"".HTMLEntities2($mount['livello'])."\"> (A-B-C ...)</td></tr>";
    $output.="<tr><td>Costo oro:</td><td><input name='mount[valoremo]' value=\"".HTMLEntities2($mount['valoremo'])."\"></td></tr>";
    $output.="<tr><td>Costo gemme:</td><td><input name='mount[valorege]' value=\"".HTMLEntities2($mount['valorege'])."\"></td></tr>";
    $output.="<tr><td>Descrizione:</td><td><input name='mount[descrizione]' value=\"".HTMLEntities2($mount['descrizione'])."\"></td></tr>";
    //ingredienti solo ricette
    $output.="<tr><td>Dove Oggetto creato:</td><td><input name='mount[dove_oggetto_creato]' value=\"".HTMLEntities2($mount['dove_oggetto_creato'])."\"></td></tr>";
    $output.="<tr><td>Utilizzatore (F/M/R):</td><td><input name='mount[utilizzatore]' value=\"".HTMLEntities2($mount['utilizzatore'])."\"></td></tr>";
    $output.="<tr><td>Costo EXP:</td><td><input name='mount[costo_exp]' value=\"".HTMLEntities2($mount['costo_exp'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente1:</td><td><input name='mount[ingrediente1]' value=\"".HTMLEntities2($mount['ingrediente1'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente2:</td><td><input name='mount[ingrediente2]' value=\"".HTMLEntities2($mount['ingrediente2'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente3:</td><td><input name='mount[ingrediente3]' value=\"".HTMLEntities2($mount['ingrediente3'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente4:</td><td><input name='mount[ingrediente4]' value=\"".HTMLEntities2($mount['ingrediente4'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente5:</td><td><input name='mount[ingrediente5]' value=\"".HTMLEntities2($mount['ingrediente5'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente6:</td><td><input name='mount[ingrediente6]' value=\"".HTMLEntities2($mount['ingrediente6'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente7:</td><td><input name='mount[ingrediente7]' value=\"".HTMLEntities2($mount['ingrediente7'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente8:</td><td><input name='mount[ingrediente8]' value=\"".HTMLEntities2($mount['ingrediente8'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente9:</td><td><input name='mount[ingrediente9]' value=\"".HTMLEntities2($mount['ingrediente9'])."\"></td></tr>";
    $output.="<tr><td>Ingrediente10:</td><td><input name='mount[ingrediente10]' value=\"".HTMLEntities2($mount['ingrediente10'])."\"></td></tr>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}
function daimateriali($mount){
    global $output;
    output("<form action='materialieditor.php?op=salva&id={$mount['id']}' method='POST'>",true);
    addnav("","materialieditor.php?op=salva&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Id materiale:</td><td><input name='mount[idoggetto]' value=\"".HTMLEntities2($mount['idoggetto'])."\"></td></tr>";
    $output.="<tr><td>Id player:</td><td><input name='mount[idplayer]' value=\"".HTMLEntities2($mount['idplayer'])."\"></td></tr>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}
page_footer();
?>