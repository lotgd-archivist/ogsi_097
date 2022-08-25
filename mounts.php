<?php
require_once "common.php";
isnewday(3);

page_header("Editor Animali");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("A?Aggiungi un Animale","mounts.php?op=add");

if ($_GET['op']=="del"){
    $sql = "UPDATE mounts SET mountactive=0 WHERE mountid='{$_GET['id']}'";
    db_query($sql);
    $_GET['op']="";
}
if ($_GET['op']=="undel"){
    $sql = "UPDATE mounts SET mountactive=1 WHERE mountid='{$_GET['id']}'";
    db_query($sql);
    $_GET['op']="";
}
if ($_GET['op']=="remove"){
    $sql = "DELETE FROM mounts WHERE mountid='{$_GET['id']}'";
    db_query($sql);
    $_GET['op']="";
    output("Animale cancellato!`n`n");
}

if ($_GET['op']==""){
    $sql = "SELECT * FROM mounts ORDER BY mountcategory, mountcostgems, mountcostgold";
    output("<table>",true);
    output("<tr><td>Ops</td><td>Nome</td><td>Costo</td><td>&nbsp;</td></tr>",true);
    $result = db_query($sql);
    $cat = "";
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($cat!=$row['mountcategory']){
            output("<tr><td colspan='4'>Categoria: {$row['mountcategory']}</td></tr>",true);
            $cat = $row['mountcategory'];
        }
        output("<tr>",true);
        output("<td>[ <a href='mounts.php?op=edit&id={$row['mountid']}'>Edita</a> |",true);
        addnav("","mounts.php?op=edit&id={$row['mountid']}");
        if ($row['mountactive']) {
            output(" <a href='mounts.php?op=del&id={$row['mountid']}'>Disattiva</a> ]</td>",true);
            addnav("","mounts.php?op=del&id={$row['mountid']}");
        }else{
            output(" <a href='mounts.php?op=undel&id={$row['mountid']}'>Attiva</a> | <a href='mounts.php?op=removecheck&id={$row['mountid']}'>Elimina</a> ]</td>",true);
            addnav("","mounts.php?op=undel&id={$row['mountid']}");
            addnav("","mounts.php?op=removecheck&id={$row['mountid']}");
        }
        output("<td>{$row['mountname']}</td>",true);
        output("<td>{$row['mountcostgems']} gemme, {$row['mountcostgold']} oro</td>",true);
        //output("<td>{$row['mountbuff']}</td>",true);
        output("<td>Combattimenti: {$row['mountforestfights']}, Oro: {$row['goldmin']}-{$row['goldmax']}, Favori: {$row['favori']}, Fascino: {$row['charm']}, Cattiveria: {$row['evil']}, Taverna: {$row['tavern']}</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
}elseif ($_GET['op']=="removecheck"){
    addnav("Editor Animali","mounts.php");
    $sql = "SELECT acctid,name FROM accounts WHERE hashorse='{$_GET['id']}'";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    if ($countrow==0){
        $sql = "SELECT mountname FROM mounts WHERE mountid='{$_GET['id']}'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        output("`bATTENZIONE: Vuoi confermare la cancellazione dell'animale ".$row['mountname']."?`b`n`n");
        output("<a href='mounts.php?op=remove&id={$_GET['id']}'>Cancella animale</a>",true);
        addnav("","mounts.php?op=remove&id={$_GET['id']}");
    }else{
        output("`nQuesta operazione non può essere effettuata!`b`n`nI seguenti player sono in possesso di questo animale:`n`n");
        for ($i=0; $i<$countrow; $i++){
            $row = db_fetch_assoc($result);
            output($row['name']." (acctid: ".$row['acctid'].")`n");
        }
    }
}elseif ($_GET['op']=="add"){
    output("Add a mount:`n");
    addnav("Editor Animali","mounts.php");
    mountform(array());
}elseif ($_GET['op']=="edit"){
    addnav("Editor Animali","mounts.php");
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`iQuesto animale non è stato trovato.`i");
    }else{
        output("Editor Animali:`n");
        $row = db_fetch_assoc($result);
        $row['mountbuff']=unserialize($row['mountbuff']);
        mountform($row);
    }
}elseif ($_GET['op']=="save"){
    $buff = array();
    reset($_POST['mount']['mountbuff']);
    $_POST['mount']['mountbuff']['activate']=join(",",$_POST['mount']['mountbuff']['activate']);
    while (list($key,$val)=each($_POST['mount']['mountbuff'])){
        if ($val>""){
            $buff[$key]=stripslashes($val);
        }
    }
    //$buff['activate']=join(",",$buff['activate']);
    $_POST['mount']['mountbuff']=$buff;
    reset($_POST['mount']);
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
        $sql="UPDATE mounts SET $sql WHERE mountid='{$_GET['id']}'";
    }else{
        $sql="INSERT INTO mounts ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Animale Salvato!");
    }else{
        output("Animale non salvato: $sql");
    }
    addnav("Editor Animali","mounts.php");
}

function mountform($mount){
    global $output;
    output("<form action='mounts.php?op=save&id={$mount['mountid']}' method='POST'>",true);
    addnav("","mounts.php?op=save&id={$mount['mountid']}");
    $output.="<table>";
    $output.="<tr><td>Nome Animale:</td><td><input name='mount[mountname]' value=\"".HTMLEntities2($mount['mountname'])."\"></td></tr>";
    $output.="<tr><td>Descrizione:</td><td><input name='mount[mountdesc]' value=\"".HTMLEntities2($mount['mountdesc'])."\"></td></tr>";
    $output.="<tr><td>Categoria:</td><td><input name='mount[mountcategory]' value=\"".HTMLEntities2($mount['mountcategory'])."\"></td></tr>";
    $output.="<tr><td>Costo (Gemme):</td><td><input name='mount[mountcostgems]' value=\"".HTMLEntities2((int)$mount['mountcostgems'])."\"></td></tr>";
    $output.="<tr><td>Costo (Oro):</td><td><input name='mount[mountcostgold]' value=\"".HTMLEntities2((int)$mount['mountcostgold'])."\"></td></tr>";
    $output.="<tr><td>Costo (Punti Donazione):</td><td><input name='mount[mountcostpd]' value=\"".HTMLEntities2((int)$mount['mountcostpd'])."\"></td></tr>";
    $output.="<tr><td>Combattimenti Supplementari:</td><td><input name='mount[mountforestfights]' value=\"".HTMLEntities2((int)$mount['mountforestfights'])."\" size='5'></td></tr>";
    $output.="<tr><td>Abilita Taverna:</td><td><input name='mount[tavern]' value=\"".HTMLEntities2((int)$mount['tavern'])."\" size='1'></td></tr>";
    $output.="<tr><td>Messaggio Nuovo Giorno:</td><td><input name='mount[newday]' value=\"".HTMLEntities2($mount['newday'])."\" size='40'></td></tr>";
    $output.="<tr><td>Messaggio di Piena Ricarica:</td><td><input name='mount[recharge]' value=\"".HTMLEntities2($mount['recharge'])."\" size='40'></td></tr>";
    $output.="<tr><td>Messaggio di Ricarica Parziale:</td><td><input name='mount[partrecharge]' value=\"".HTMLEntities2($mount['partrecharge'])."\" size='40'></td></tr>";
    $output.="<tr><td>Chance di entrare in miniera (percento):</td><td><input name='mount[mine_canenter]' value=\"".HTMLEntities2((int)$mount['mine_canenter'])."\"></td></tr>";
    $output.="<tr><td>Chance di morire in miniera (percento):</td><td><input name='mount[mine_candie]' value=\"".HTMLEntities2((int)$mount['mine_candie'])."\"></td></tr>";
    $output.="<tr><td>Chance di salvare il giocatore in miniera (percento):</td><td><input name='mount[mine_cansave]' value=\"".HTMLEntities2((int)$mount['mine_cansave'])."\"></td></tr>";
    $output.="<tr><td>Messaggio Mine tether:</td><td><input name='mount[mine_tethermsg]' value=\"".HTMLEntities2($mount['mine_tethermsg'])."\" size='40'></td></tr>";
    $output.="<tr><td>Messaggio di morte in miniera:</td><td><input name='mount[mine_deathmsg]' value=\"".HTMLEntities2($mount['mine_deathmsg'])."\" size='40'></td></tr>";
    $output.="<tr><td>Messaggio di salvataggio in miniera:</td><td><input name='mount[mine_savemsg]' value=\"".HTMLEntities2($mount['mine_savemsg'])."\" size='40'></td></tr>";
    $output.="<tr><td valign='top'>Attacco Animale:</td><td>";
    $output.="<b>Messaggi:</b><Br/>";
    $output.="Nome Attacchi: <input name='mount[mountbuff][name]' value=\"".HTMLEntities2($mount['mountbuff']['name'])."\"><Br/>";
    //output("Initial Message: <input name='mount[mountbuff][startmsg]' value=\"".HTMLEntities2($mount['mountbuff']['startmsg'])."\">`n",true);
    $output.="Messaggio ogni round: <input name='mount[mountbuff][roundmsg]' value=\"".HTMLEntities2($mount['mountbuff']['roundmsg'])."\"><Br/>";
    $output.="Messaggio Termine Attacco: <input name='mount[mountbuff][wearoff]' value=\"".HTMLEntities2($mount['mountbuff']['wearoff'])."\"><Br/>";
    $output.="Messaggio Attacco Riuscito: <input name='mount[mountbuff][effectmsg]' value=\"".HTMLEntities2($mount['mountbuff']['effectmsg'])."\"><Br/>";
    $output.="Messaggio Nessun Danno: <input name='mount[mountbuff][effectnodmgmsg]' value=\"".HTMLEntities2($mount['mountbuff']['effectnodmgmsg'])."\"><Br/>";
    $output.="Messaggio Attacco Mancato: <input name='mount[mountbuff][effectfailmsg]' value=\"".HTMLEntities2($mount['mountbuff']['effectfailmsg'])."\"><Br/>";
    $output.="<Br/><b>Effetti:</b><Br/>";
    $output.="Turni Durata Attacco (dal new day): <input name='mount[mountbuff][rounds]' value=\"".HTMLEntities2((int)$mount['mountbuff']['rounds'])."\" size='5'><Br/>";
    $output.="Modif Attacco Giocatore: <input name='mount[mountbuff][atkmod]' value=\"".HTMLEntities2($mount['mountbuff']['atkmod'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Modif Difesa Giocatore: <input name='mount[mountbuff][defmod]' value=\"".HTMLEntities2($mount['mountbuff']['defmod'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Rigenerazione: <input name='mount[mountbuff][regen]' value=\"".HTMLEntities2($mount['mountbuff']['regen'])."\"><Br/>";
    $output.="Minion Count: <input name='mount[mountbuff][minioncount]' value=\"".HTMLEntities2($mount['mountbuff']['minioncount'])."\"><Br/>";
    $output.="Danno Min Badguy: <input name='mount[mountbuff][minbadguydamage]' value=\"".HTMLEntities2($mount['mountbuff']['minbadguydamage'])."\" size='5'><Br/>";
    $output.="Danno Max Badguy: <input name='mount[mountbuff][maxbadguydamage]' value=\"".HTMLEntities2($mount['mountbuff']['maxbadguydamage'])."\" size='5'><Br/>";
    $output.="Lifetap: <input name='mount[mountbuff][lifetap]' value=\"".HTMLEntities2($mount['mountbuff']['lifetap'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Scudo Danni: <input name='mount[mountbuff][damageshield]' value=\"".HTMLEntities2($mount['mountbuff']['damageshield'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Modif Danno Badguy: <input name='mount[mountbuff][badguydmgmod]' value=\"".HTMLEntities2($mount['mountbuff']['badguydmgmod'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Modif Attacco Badguy: <input name='mount[mountbuff][badguyatkmod]' value=\"".HTMLEntities2($mount['mountbuff']['badguyatkmod'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Modif Dif Badguy: <input name='mount[mountbuff][badguydefmod]' value=\"".HTMLEntities2($mount['mountbuff']['badguydefmod'])."\" size='5'> (moltiplicatore)<Br/>";
    //$output.=": <input name='mount[mountbuff][]' value=\"".HTMLEntities2($mount['mountbuff'][''])."\">`n",true);

    $output.="<Br/><b>Attivazione:</b><Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"roundstart\"".(strpos($mount['mountbuff']['activate'],"roundstart")!==false?" checked":"")."> Inizio Round<Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"offense\"".(strpos($mount['mountbuff']['activate'],"offense")!==false?" checked":"")."> Quando Attacchi<Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"defense\"".(strpos($mount['mountbuff']['activate'],"defense")!==false?" checked":"")."> Quando Difendi<Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"endround\"".(strpos($mount['mountbuff']['activate'],"endround")!==false?" checked":"")."> Fine Round<Br/>";
    $output.="<Br/></td></tr>";
    $output.="<tr><td valign='top'>Altre opzioni combattimento:</td><td>";
    $output.="Oro guadagnato: <input name='mount[goldfight]' value=\"".HTMLEntities2($mount['goldfight'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Esperienza guadagnata: <input name='mount[expfight]' value=\"".HTMLEntities2($mount['expfight'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="</td></tr>";
    $output.="<tr><td valign='top'>Bonus newday:</td><td>";
    $output.="Oro guadagnato (min): <input name='mount[goldmin]' value=\"".HTMLEntities2($mount['goldmin'])."\" size='5'> (negativo per perdere oro)<Br/>";
    $output.="Oro guadagnato (max): <input name='mount[goldmax]' value=\"".HTMLEntities2($mount['goldmax'])."\" size='5'> (negativo per perdere oro)<Br/>";
    $output.="Oro guadagnato (chance): <input name='mount[goldchance]' value=\"".HTMLEntities2($mount['goldchance'])."\" size='5'> %<Br/>";
    $output.="Gemma guadagnata (chance - negativa per gemma persa): <input name='mount[gemchance]' value=\"".HTMLEntities2($mount['gemchance'])."\" size='5'> %<Br/>";
    $output.="Se viene trovata la gemma si può comunque guadagnare oro: <input name='mount[gem_oro]' value=\"".HTMLEntities2($mount['gem_oro'])."\" size='5'> (0:sì - 1:no)<Br/>";
    $output.="Favori guadagnati: <input name='mount[favori]' value=\"".HTMLEntities2($mount['favori'])."\" size='5'> (negativo per perdere favori)<Br/>";
    $output.="Favori guadagnati (chance): <input name='mount[favorichance]' value=\"".HTMLEntities2($mount['favorichance'])."\" size='5'> %<Br/>";
    $output.="Punti Fascino guadagnati: <input name='mount[charm]' value=\"".HTMLEntities2($mount['charm'])."\" size='5'> (negativo per perdere fascino)<Br/>";
    $output.="Punti Fascino guadagnati (chance): <input name='mount[charmchance]' value=\"".HTMLEntities2($mount['charmchance'])."\" size='5'> %<Br/>";
    $output.="Punti Cattiveria scalati: <input name='mount[evil]' value=\"".HTMLEntities2($mount['evil'])."\" size='5'> (negativo per aumentare cattiveria)<Br/>";
    $output.="Punti Cattiveria scalati (chance): <input name='mount[evilchance]' value=\"".HTMLEntities2($mount['evilchance'])."\" size='5'> %<Br/>";
    $output.="PVP extra (chance - negativa per PVP perso): <input name='mount[pvpchance]' value=\"".HTMLEntities2($mount['pvpchance'])."\" size='5'> %<Br/>";
    $output.="I bonus newday vengono dati anche alla resurrezione: <input name='mount[resbonus]' value=\"".HTMLEntities2($mount['resbonus'])."\" size='5'> (0:no - 1:sì)<Br/>";
    $output.="</td></tr>";
    $output.="<tr><td valign='top'>Bonus cimitero:</td><td>";
    $output.="Tormenti Supplementari: <input name='mount[mounttorments]' value=\"".HTMLEntities2((int)$mount['mounttorments'])."\" size='5'><Br/>";
    $output.="Modif Psiche Giocatore: <input name='mount[tormentatkmod]' value=\"".HTMLEntities2($mount['tormentatkmod'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="Modif Spirito Giocatore: <input name='mount[tormentdefmod]' value=\"".HTMLEntities2($mount['tormentdefmod'])."\" size='5'> (moltiplicatore)<Br/>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>