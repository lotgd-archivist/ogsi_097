<?php

// 11072004

// Editor Incantesimi
// based on item editor
//
// Richiede tabella PROTOINC
//
// insert:
//         if ($session[user][superuser]>=2) addnav("Editor Incantesimi Maghi","incanteditor.php");
// into menu of superuser.php
//

require_once "common.php";

page_header("Editor di incantesimi");
addnav("G?Ritorna alla Grotta","superuser.php");
addnav("M?Torna alla mondanità","village.php");

if ($_GET['op']=="del"){
        $sql = "DELETE FROM protoinc WHERE id=$_GET[id]";
        db_query($sql);
        $_GET['op']="";
}

if ($_GET['op']=="add"){
        output("Aggiungi un incantesimo:`n");
        addnav("Editor di incantesimi","incanteditor.php");
        itemform(array());
}elseif ($_GET['op']=="addincpla"){
        addnav("Editor di incantesimi","incanteditor.php");
        output("Il tuo id: ".$session[user][acctid]."`n`n");
        daiincantesimi(array());
}elseif ($_GET['op']=="edit"){
        addnav("Editor di incantesimi","incanteditor.php");
        $sql = "SELECT * FROM protoinc WHERE id='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`iL'incantesimo selezionato non esiste.`i");
        }else{
                output("Editor di incantesimi:`n");
                $row = db_fetch_assoc($result);
                $row['buff']=unserialize($row['buff']);
                itemform($row);
        }
}elseif ($_GET['op']=="save"){
        $buff = array();
        reset($_POST['protoinc']['buff']);
        if (isset($_POST['protoinc']['buff']['activate'])) $_POST['protoinc']['buff']['activate']=join(",",$_POST['protoinc']['buff']['activate']);
        if (isset($_POST['protoinc']['buff']['deactivate'])) $_POST['protoinc']['buff']['deactivate']=join(",",$_POST['protoinc']['buff']['deactivate']);
        while (list($key,$val)=each($_POST['protoinc']['buff'])){
                if ($val>""){
                        $buff[$key]=stripslashes($val);
                }
        }
        $_POST['protoinc']['buff']=$buff;
        reset($_POST['protoinc']);
        $keys='';
        $vals='';
        $sql='';
        $i=0;
        while (list($key,$val)=each($_POST['protoinc'])){
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
                $sql="UPDATE protoinc SET $sql WHERE id='{$_GET['id']}'";
        }else{
                $sql="INSERT INTO protoinc ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
                output("Incantesimo salvato!");
        }else{
                output("Incantesimo NON salvato! Error: $sql");
        }
        addnav("Editor di incantesimi","incanteditor.php");
}elseif ($_GET['op']=="salva"){
        $sqlsp="SELECT * FROM protoinc WHERE pergamena='".$_POST['mount']['idoggetto']."'";
        $resultsp=db_query($sqlsp);
        if (db_num_rows($resultsp) == 0) {
            output("`3Questa pergamena non è collegata a nessun incantesimo, segnala il problema all'admin indicando il nome della pergamena usata.`n");
        }else{
            $rowsp = db_fetch_assoc($resultsp);
            $sql="INSERT INTO incantesimi(acctid,nome,quanti,buff) VALUES ('".$_POST['mount']['idplayer']."','".$rowsp['nome']."','".$rowsp['quanti']."','".addslashes($rowsp[buff])."')";
            db_query($sql);
        }
        if (db_affected_rows()>0){
            output("Incantesimo assegnato!");
        }else{
            output("Incantesimo non assegnato: $sql");
        }
        addnav("Editor di incantesimi","incanteditor.php");
}elseif ($_GET[op]=="pulizia") {
    output("Operazione che può durare alcuni minuti attendi!.`n");
    $sqlo = "SELECT acctid FROM incantesimi";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $tot=db_num_rows($resulto);
    for ($i = 0;$i < $tot;$i++) {
            $rowo = db_fetch_assoc($resulto);
            $sqlc = "SELECT acctid FROM accounts WHERE acctid='".$rowo['acctid']."'";
            $resultc = db_query($sqlc) or die(db_error(LINK));
            if(db_num_rows($resultc)==0){
                $sqle = "DELETE FROM incantesimi WHERE acctid='".$rowo['acctid']."'";
                db_query($sqle);
                output("`\$Eliminato incantesimo $rowo[acctid]`n");
            }
     output("`#Esaminato incantesimo $i su $tot`n");
     }
     output("Operazione completata");
     addnav("Editor di incantesimi","incanteditor.php");
}else{
        $ppp=50; // Player Per Page to display
        if (!$_GET[limit]){
              $page=0;
        }else{
              $page=(int)$_GET[limit];
              addnav("Pagina Precedente","incanteditor.php?limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT protoinc.* FROM protoinc ORDER BY id LIMIT $limit";
        output("<table>",true);
        output("<tr><td>Ops</td><td>Nome</td><td>Livello</td><td>Pergamena</td><td>Descrizione</td></tr>",true);
        $result = db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","incanteditor.php?limit=".($page+1)."");
        $cat = "";
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
              output("<tr>",true);
              output("<td>[ <a href='incanteditor.php?op=edit&id=$row[id]'>Modifica</a> |",true);
              addnav("","incanteditor.php?op=edit&id=$row[id]");
              output(" <a href='incanteditor.php?op=del&id=$row[id]' onClick=\"return confirm('Cancella questo incantesimo?');\">Cancella</a> ]</td>",true);
              addnav("","incanteditor.php?op=del&id=$row[id]");
              output("<td>`^$row[nome]`0</td>",true);
              output("<td>`#$row[livello]`0</td>",true);
              output("<td>`V$row[pergamena]`0</td>",true);
              output("<td>`&$row[descrizione]`0</td>",true);
              output("</tr>",true);
        }
        output("</table>",true);
        addnav("Aggiungi un incantesimo","incanteditor.php?op=add");
        addnav("Dai incantesimo a player","incanteditor.php?op=addincpla");
        addnav("Pulizia DB incantesimi","incanteditor.php?op=pulizia");
}

function itemform($protoinc){
        global $output;
        output("<form action='incanteditor.php?op=save&id=$protoinc[id]' method='POST'>",true);
        addnav("","incanteditor.php?op=save&id=$protoinc[id]");
        $output.="<table>";
        $output.="<tr><td>Nome:</td><td><input name='protoinc[nome]' value=\"".HTMLEntities2($protoinc['nome'])."\" maxlength='40'></td></tr>";
        $output.="<tr><td>Livello (1-3):</td><td><input name='protoinc[livello]' value=\"".HTMLEntities2((int)$protoinc['livello'])."\" size='2'></td></tr>";
        $output.="<tr><td>Descrizione:</td><td><input name='protoinc[descrizione]' value=\"".HTMLEntities2($protoinc['descrizione'])."\" maxlength='255'></td></tr>";
        $output.="<tr><td>Quanti (n°volte attivabile):</td><td><input name='protoinc[quanti]' value=\"".HTMLEntities2((int)$protoinc['quanti'])."\" size='5'></td></tr>";
        $output.="<tr><td>Pergamena collegata (tabella materiali):</td><td><input name='protoinc[pergamena]' value=\"".HTMLEntities2((int)$protoinc['pergamena'])."\" size='5'></td></tr>";
        $output.="<tr><td valign='top'>Buff incantesimo:</td><td>";
        $output.="<b>Messaggi:</b><Br/>";
        $output.="Buff Name: <input name='protoinc[buff][name]' value=\"".HTMLEntities2($protoinc['buff']['name'])."\"><Br/>";
        //output("Initial Message: <input name='mount[mountbuff][startmsg]' value=\"".HTMLEntities2($mount['mountbuff']['startmsg'])."\">`n",true);
        $output.="Message each round: <input name='protoinc[buff][roundmsg]' value=\"".HTMLEntities2($protoinc['buff']['roundmsg'])."\"><Br/>";
        $output.="Wear off message: <input name='protoinc[buff][wearoff]' value=\"".HTMLEntities2($protoinc['buff']['wearoff'])."\"><Br/>";
        $output.="Effect Message: <input name='protoinc[buff][effectmsg]' value=\"".HTMLEntities2($protoinc['buff']['effectmsg'])."\"><Br/>";
        $output.="Effect No Damage Message: <input name='protoinc[buff][effectnodmgmsg]' value=\"".HTMLEntities2($protoinc['buff']['effectnodmgmsg'])."\"><Br/>";
        $output.="Effect Fail Message: <input name='protoinc[buff][effectfailmsg]' value=\"".HTMLEntities2($protoinc['buff']['effectfailmsg'])."\"><Br/>";
        $output.="<Br/><b>Effetti:</b><Br/>";
        $output.="Rounds to last (from activation): <input name='protoinc[buff][rounds]' value=\"".HTMLEntities2($protoinc['buff']['rounds'])."\" size='5'><Br/>";
        $output.="Player Atk mod: <input name='protoinc[buff][atkmod]' value=\"".HTMLEntities2($protoinc['buff']['atkmod'])."\" size='5'><Br/>";
        $output.="Player Def mod: <input name='protoinc[buff][defmod]' value=\"".HTMLEntities2($protoinc['buff']['defmod'])."\" size='5'><Br/>";
        $output.="Regen: <input name='protoinc[buff][regen]' value=\"".HTMLEntities2($protoinc['buff']['regen'])."\"><Br/>";
        $output.="Minion Count: <input name='protoinc[buff][minioncount]' value=\"".HTMLEntities2($protoinc['buff']['minioncount'])."\"><Br/>";
        $output.="Min Badguy Damage: <input name='protoinc[buff][minbadguydamage]' value=\"".HTMLEntities2($protoinc['buff']['minbadguydamage'])."\" size='5'><Br/>";
        $output.="Max Badguy Damage: <input name='protoinc[buff][maxbadguydamage]' value=\"".HTMLEntities2($protoinc['buff']['maxbadguydamage'])."\" size='5'><Br/>";
        $output.="Lifetap: <input name='protoinc[buff][lifetap]' value=\"".HTMLEntities2($protoinc['buff']['lifetap'])."\" size='5'><Br/>";
        $output.="Damage shield: <input name='protoinc[buff][damageshield]' value=\"".HTMLEntities2($protoinc['buff']['damageshield'])."\" size='5'> (multiplier)<Br/>";
        $output.="Protective shield: <input name='protoinc[buff][protectiveshield]' value=\"".HTMLEntities2($protoinc['buff']['protectiveshield'])."\" size='5'> (additive)<Br/>";
        $output.="Badguy Damage mod: <input name='protoinc[buff][badguydmgmod]' value=\"".HTMLEntities2($protoinc['buff']['badguydmgmod'])."\" size='5'> (multiplier)<Br/>";
        $output.="Badguy Atk mod: <input name='protoinc[buff][badguyatkmod]' value=\"".HTMLEntities2($protoinc['buff']['badguyatkmod'])."\" size='5'> (multiplier)<Br/>";
        $output.="Badguy Def mod: <input name='protoinc[buff][badguydefmod]' value=\"".HTMLEntities2($protoinc['buff']['badguydefmod'])."\" size='5'> (multiplier)<Br/>";
        //$output.=": <input name='mount[mountbuff][]' value=\"".HTMLEntities2($mount['mountbuff'][''])."\">`n",true);

        $output.="<Br/><b>Attivazione:</b><Br/>";
        $output.="<input type='checkbox' name='protoinc[buff][activate][]' value=\"roundstart\"".(strpos($protoinc['buff']['activate'],"roundstart")!==false?" checked":"")."> Round Start<Br/>";
        $output.="<input type='checkbox' name='protoinc[buff][activate][]' value=\"offense\"".(strpos($protoinc['buff']['activate'],"offense")!==false?" checked":"")."> On Attack<Br/>";
        $output.="<input type='checkbox' name='protoinc[buff][activate][]' value=\"defense\"".(strpos($protoinc['buff']['activate'],"defense")!==false?" checked":"")."> On Defend<Br/>";
        $output.="<input type='checkbox' name='protoinc[buff][activate][]' value=\"endround\"".(strpos($protoinc['buff']['activate'],"endround")!==false?" checked":"")."> Round End<Br/>";
        $output.="<Br/>";
        $output.="<input type='checkbox' name='protoinc[buff][deactivate][]' value=\"battleend\"".(strpos($protoinc['buff']['deactivate'],"battleend")!==false?" checked":"")."> Buff terminate at the battle's end<Br/>";
        $output.="<Br/>";
        $output.="</td></tr>";
        $output.="</table>";
        $output.="<input type='submit' class='button' value='Salva'></form>";
}
function daiincantesimi($mount){
    global $output;
    output("<form action='incanteditor.php?op=salva&id={$mount['id']}' method='POST'>",true);
    addnav("","incanteditor.php?op=salva&id={$mount['id']}");
    $output.="<table>";
    $output.="<tr><td>Id materiale:</td><td><input name='mount[idoggetto]' value=\"".HTMLEntities2($mount['idoggetto'])."\"></td></tr>";
    $output.="<tr><td>Id player:</td><td><input name='mount[idplayer]' value=\"".HTMLEntities2($mount['idplayer'])."\"></td></tr>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>