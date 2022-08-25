<?php
require_once "common.php";
isnewday(3);

page_header("Editor Draghi");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Crea un Drago","draghieditor.php?op=add");
//addnav("Dai Drago a Player","draghieditor.php?op=adddragoplayer");
addnav("Elenco Draghi","draghieditor.php?op=elenco");

if ($_GET['op']==""){
   output("`(Benvenuto nell'editor dei draghi. Qui puoi vedere i draghi esistenti nel reame,`n");
   output("crearne di nuovi o assegnarne uno ad un player.`n");
   output("Fai attenzione prima di effettuare una qualsiasi operazione, potresti avere effetti indesiderati !!`n`n");
   $sql = "SELECT * FROM draghi ORDER BY id";
   output("<table>",true);
   output("<tr><td>Ops</td><td>`b`#ID`b</td><td>`b`&Nome`b</td><td>Propr.</td><td>Tipo</td><td>Età</td>
            <td>Tipo Soffio</td><td>Danno Soffio</td><td>Att</td><td>Dif</td><td>Vita</td>
            <td>Carattere</td><td>Dove</td><td>Combatte</td><td>Bonus</td><td>Valore</td>
            <td>Aspetto</td><td>Crescita</td></tr>",true);
   $result = db_query($sql) or die(db_error(LINK));
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for ($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       output("<tr>",true);
       output("<td>[<a href='draghieditor.php?op=edit&id=".$row['id']."'>Edit</a>]",true);
       addnav("","draghieditor.php?op=edit&id=".$row['id']);
       output("<td>".$row['id']."</td><td>".$row['nome_drago']."</td><td>".$row['user_id']."</td>
       <td>".$row['tipo_drago']."</td><td>".$row['eta_drago']."</td><td>".$row['tipo_soffio']."</td>
       <td>".$row['danno_soffio']."</td><td>".$row['attacco']."</td><td>".$row['difesa']."</td>
       <td>".$row['vita']."</td><td>".$row['carattere']."</td><td>".$row['dove']."</td>
       <td>".$row['combatte']."</td><td>".$row['bonus']."</td><td>".$row['valore']."</td>
       <td>".$row['aspetto']."</td><td>".$row['crescita']."</td>",true);
       output("</tr>",true);
   }
   output("</table>",true);

}elseif ($_GET['op']=="elenco"){
   $sql = "SELECT * FROM draghi ORDER BY valore DESC";
   output("`c`b`&Draghi esistenti nel reame`b`c");
   output("<table cellspacing=0 cellpadding=2 align='center'><tr>
            <td>&nbsp;</td><td>`b`@Tipo`b</td>
            <td>`b`#Età `b</td>
            <td>`b`&Costo`b</td>
            <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
            <td>`b`@Carattere`b</td><td>&nbsp;</td>
            <td>`b`\$Att.`b</td><td>&nbsp;</td>
            <td>`b`^Dif.`b</td><td>&nbsp;</td>
            <td>`b`%Vita`b</td><td>&nbsp;</td>
            <td>`b`^Soffio`b</td><td>&nbsp;</td>
            <td>`b`&Danno Soffio`b</td>
            <td>`b`#ID`b</td>",true);
   $result = db_query($sql) or die(db_error(LINK));
   if (db_num_rows($result) == 0) {
      output("<tr><td colspan=4 align='center'>`&Non ci sono draghi nel reame`0</td></tr>", true);
   }else{
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i = 0;$i < db_num_rows($result);$i++) {
       $row = db_fetch_assoc($result);
       output("<tr class='" . ($i % 2?"trlight":"trdark") . "'><td align=right>" . ($i + 1) . ".</td>
                <td>".$row['tipo_drago']."</td>
                <td>`b`#".$row['eta_drago']."`b</td>
                <td>`b`&".$row['valore']."`b</td>
                <td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>
                <td align='center'>`b`@".$row['carattere']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`\$".$row['attacco']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`^".$row['difesa']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`%".$row['vita']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`^".$row['tipo_soffio']."`b</td><td>&nbsp;</td>
                <td align='center'>`b`&".$row['danno_soffio']."`b</td>
                <td>`b`#".$row['id']."`b</td>",true);
        }
        output("</tr>", true);
    }
    output("</table>", true);
    addnav("Home Editor Draghi","draghieditor.php");
}elseif($_GET['op']=="add"){
   addnav("Home Editor Draghi","draghieditor.php");
   creadraghi(array());
}elseif ($_GET['op']=="adddragoplayer"){
    addnav("Home Editor Draghi","draghieditor.php");
    output("Il tuo id: ".$session['user']['acctid']."`n`n");
    daidrago(array());
}elseif ($_GET['op']=="edit"){
    addnav("Home Editor Draghi","draghieditor.php");
    $sql = "SELECT * FROM draghi WHERE id='".$_GET['id']."'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`i`b`\$Drago non trovato.`i`b`0");
    }else{
        output("Editor Draghi:`n");
        $row = db_fetch_assoc($result);
        creadraghi($row);
    }
}elseif ($_GET['op']=="save"){  //Crea drago
        if (is_array($_POST)){
            reset($_POST);
            reset($_POST['mount']);
        }
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
            $sql="UPDATE draghi SET $sql WHERE id={$_GET['id']}";
            //print("sql vale ".$sql);
        }else{
            $sql="INSERT INTO draghi ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
            output("Drago salvato!");
        }else{
            output("Drago non salvato: $sql");
        }
        addnav("Home Editor Draghi","draghieditor.php");
}elseif ($_GET['op']=="salva"){
/*        if (is_array($_POST)){
            reset($_POST);
            reset($_POST['mount']);
        }
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
            $sql="UPDATE draghi SET $sql WHERE id='{$_GET['id']}'";
        }else{
            $sql="INSERT INTO draghi ($keys) VALUES ($vals)";
        }
*/      
  	$sqla = "SELECT * FROM draghi WHERE id='$_GET[id]'";
    $resulta = db_query($sqla) or die(db_error(LINK));
    if (db_num_rows($resulta)==1){
        $rowa = db_fetch_assoc($resulta);
        if ($rowa['user_id']==0){
            
        } else {
            output("Errore: Questo drago è già assegnato e deve prima essere liberato!");
        }
    } else {
        output("Errore: Questo drago non esiste!");
    }
        $sql1="UPDATE draghi SET user_id='{$_POST['mount']['user_id']}' WHERE id='{$_POST['mount']['id']}'";
        db_query($sql1);
        $sql2="UPDATE accounts SET id_drago='{$_POST['mount']['id']}' WHERE acctid='{$_POST['mount']['user_id']}'";
        db_query($sql2);
        if (db_affected_rows()>0){
            output("Drago assegnato!");
        }else{
            output("Drago non assegnato: $sql1");
        }
        addnav("Home Editor Draghi","draghieditor.php");
}

function creadraghi($mount){
    global $output;
    output("<form action='draghieditor.php?op=save&id={$mount['id']}' method='POST'>",true);
    addnav("","draghieditor.php?op=save&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Nome:</td><td><input name='mount[nome_drago]' value=\"".HTMLEntities2($mount['nome_drago'])."\"></td></tr>";
    $output.="<tr><td>Proprietario:</td><td><input name='mount[user_id]' value=\"".HTMLEntities2($mount['user_id'])."\"> (Account ID)</td></tr>";
    $output.="<tr><td>Tipo Drago:</td><td><input name='mount[tipo_drago]' value=\"".HTMLEntities2($mount['tipo_drago'])."\"></td></tr>";
    $output.="<tr><td>Età Drago:</td><td><input name='mount[eta_drago]' value=\"".HTMLEntities2($mount['eta_drago'])."\"> (cucciolo,giovane,adulto,anziano,antico)</td></tr>";
    $output.="<tr><td>Tipo Soffio:</td><td><input name='mount[tipo_soffio]' value=\"".HTMLEntities2($mount['tipo_soffio'])."\"> (fuoco,acido,fulmine,gelo,morte)</td></tr>";
    $output.="<tr><td>Danno Soffio:</td><td><input name='mount[danno_soffio]' value=\"".HTMLEntities2($mount['danno_soffio'])."\"></td></tr>";
    $output.="<tr><td>Attacco:</td><td><input name='mount[attacco]' value=\"".HTMLEntities2($mount['attacco'])."\"></td></tr>";
    $output.="<tr><td>Difesa:</td><td><input name='mount[difesa]' value=\"".HTMLEntities2($mount['difesa'])."\"></td></tr>";
    $output.="<tr><td>Vita:</td><td><input name='mount[vita]' value=\"".HTMLEntities2($mount['vita'])."\"></td></tr>";
    $output.="<tr><td>Carattere:</td><td><input name='mount[carattere]' value=\"".HTMLEntities2($mount['carattere'])."\"></td></tr>";
    $output.="<tr><td>Dove:</td><td><input name='mount[dove]' value=\"".HTMLEntities2($mount['dove'])."\"></td></tr>";
    $output.="<tr><td>Combatte:</td><td><input name='mount[combatte]' value=\"".HTMLEntities2($mount['combatte'])."\"> (terra,volo)</td></tr>";
    $output.="<tr><td>Bonus:</td><td><input name='mount[bonus]' value=\"".HTMLEntities2($mount['bonus'])."\"></td></tr>";
    $output.="<tr><td>Valore:</td><td><input name='mount[valore]' value=\"".HTMLEntities2($mount['valore'])."\"></td></tr>";
    $output.="<tr><td>Aspetto:</td><td><input name='mount[aspetto]' value=\"".HTMLEntities2($mount['aspetto'])."\"> (Ottimo,Buono,Normale,Brutto,Pessimo)</td></tr>";
    $output.="<tr><td>Crescita:</td><td><input name='mount[crescita]' value=\"".HTMLEntities2($mount['crescita'])."\"></td></tr>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}
function daidrago($mount){    //Da sistemare !!!!
    global $output;
    output("<form action='draghieditor.php?op=salva&id={$mount['id']}' method='POST'>",true);
    addnav("","draghieditor.php?op=salva&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Id drago:</td><td><input name='mount[id]' value=\"".HTMLEntities2($mount['id'])."\"></td></tr>";
    $output.="<tr><td>Id player:</td><td><input name='mount[user_id]' value=\"".HTMLEntities2($mount['user_id'])."\"></td></tr>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}
page_footer();
?>