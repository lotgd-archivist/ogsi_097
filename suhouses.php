<?php
/**ss**********************ss***************
/ Housing Script Superuser Panel (suhouses.php)
  ver 0.95 - Anpera and Strider's Estate Editor.
/ version 0.95  (LEGENDGARD FORK from Anpera's 0.93) by Strider
/ 4.27.04  (1st legendgard Edition) -scs-
______________________________________________________________________________________
/ About: This file allows you to edit your estates via the superuser panel.
----install-instructions--------------------------------------------------
/ -SS-SCALE: ****** :expert level - not for the squimish: -
 Version 0.95 is built for LOTGD 0.9.7
 You must install this with Houses.php.
 This is an involved script with many steps to install it. If you're not comfortable editing
 LotGD, you should be careful. Refer to the install instructions to walk you though.
 You should find the most current version of this script with install instructions and forums to
 help you at DRAGONPRIME - http://dragonprime.cawsquad.net
______________________________________________________________________________________
/ Version History:
This is the first version of the Estate Editor
* Original Author: Anpera
* First English Translation by Strider
* Enamel: logd@anpera.de
**ss**************************ss************/
//  Brought to you by Anpera and LoneStrider
// -Contributors: Anpera, Strider
// April 2004  - Legendgard Script Release
// Version: 24.04.2004
require_once("common.php");
isnewday(3);
page_header("Pannello di Amministrazione Tenute");
function disp_status(){
    output("<ul>",true);
    output("`n`@Stato tenuta:`n`^`b0:`b `6in costruzione`^`n`b1:`b `!occupata`^`n`b2:`b `^in vendita`^`n");
    output("`b3:`b `4Abbandonata`^`n`b4:`b `\$In Rovina`0");
    output("</ul>",true);
}
if ($_GET[op]=="drin"){
    addnav("Aggiungi Chiave","suhouses.php?op=keys&hid=".$_GET['id']);
    addnav("Edit Tenuta","suhouses.php?op=data&id=".$_GET['id']);
    addnav("Commenti","suhouses.php?op=comment&id=".$_GET['id']);
    addnav("Editor Tenuta","suhouses.php");
    $sql="SELECT * FROM houses WHERE houseid=".$_GET['id'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`n`6-Registro Tenute-`b");
    output("`n`@Numero Tenuta: `^`b".$row['houseid']."`b");
    output("`n`@Nome: `^`b".$row['housename']."`b");
    output("`n`@Descrizione: `^`b".$row['description']."`b`n`n");
    output("`n`6-Statistiche Tenuta-`b");
    output("`n`@Oro: `^`b".$row['gold']."`b");
    output("`n`@Gemme: `^`b".$row['gems']."`b");
    output("`n`@Stato: `^`b".$row['status']."`b (");
    if ($row['status']==0) output("`6In Costruzione`0");
    if ($row['status']==1) output("`!Occupata`0");
    if ($row['status']==2) output("`^In Vendita`0");
    if ($row['status']==3) output("`4Abbandonata`0");
    if ($row['status']==4) output("`\$In Rovina`0");
    $sql = "SELECT name FROM accounts WHERE acctid=".$row['owner'];
    $result2 = db_query($sql);
    $row2  = db_fetch_assoc($result2);
    output("`^)`n`@Proprietario: `^`b".$row['owner']."`b (".$row2['name']."`^)");
    output("`n`n`@Chiavi: `^`n");
    output("<table border='0' cellpadding='3' cellspacing='0'><tr><td># </td><td>ID Propietario (Nome)</td><td>Tenuta#</td><td>Chiave# (DB)</td><td>In uso?</td><td>Opzioni</td></tr>",true);
    $sql = "SELECT * FROM items WHERE value1=".$row['houseid']." AND class='Key' ORDER BY value2 ASC,id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=1; $i<$countrow; $i++){
    //for ($i=1;$i<=db_num_rows($result);$i++){
        $item = db_fetch_assoc($result);
        $sql = "SELECT acctid,name FROM accounts WHERE acctid=".$item['owner']." ORDER BY login DESC";
        $result2 = db_query($sql) or die(db_error(LINK));
        $row2 = db_fetch_assoc($result2);
        output("<tr><td>`b$i`b</td><td>".($row2['acctid']?"$row2[acctid] ($row2[name])":"0 (`4irrecuperabile `0)")."</td><td>$item[value1]</td><td>$item[value2]</td><td>$item[hvalue]</td><td>",true);
        if ($row2['name']==""){
            output("<a href='suhouses.php?op=keys&subop=change&hid=$_GET[id]&id2=$i&owner=$row[owner]'>Reset</a> | ",true);
            addnav("","suhouses.php?op=keys&subop=change&hid=$_GET[id]&id2=$i&owner=$row[owner]");
        }
        output("<a href='suhouses.php?op=keys&subop=edit&id=$item[id]&hid=$_GET[id]'>Edit</a> | <a href='suhouses.php?op=keys&subop=delete&id=$item[id]&hid=$_GET[id]' onClick=\"return confirm('Vuoi veramente cancellare questa chiave?');\">Cancella</a>",true);
        addnav("","suhouses.php?op=keys&subop=edit&id=$item[id]&hid=$_GET[id]");
        addnav("","suhouses.php?op=keys&subop=delete&id=$item[id]&hid=$_GET[id]");
        output("</td></tr>",true);
    }
    output("</table>`n",true);
}else if ($_GET['op']=="comment"){
    if ($_GET['subop']=="delete"){
        $sql = "DELETE FROM commentary WHERE commentid='".$_GET['commentid']."'";
        db_query($sql);
    }
    viewcommentary("house-$_GET[id]","X",100);
    addnav("Stato della Tenuta # $_GET[id]","suhouses.php?op=drin&id=$_GET[id]");
}else if ($_GET['op']=="info"){
    $sql="SELECT acctid,name,house,housekey FROM accounts WHERE house ORDER BY house ASC";
    output("<table cellpadding=2 align='center'><tr><td>`bacctid`b</td><td>`bName`b</td><td>`bhouse`b</td><td>`bhousekey`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iThere are no Estates`i`0</td></tr>",true);
    }else{
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<tr><td align='center'>".$row['acctid']."</td><td>".$row['name']."</td><td>".$row['house']."</td><td>".$row['housekey']."</td></tr>",true);
        }
    }
    output("</table>",true);
    addnav("Editor Tenute","suhouses.php");
}else if ($_GET['op']=="destroy"){ // bad idea! write this code on your own risk!
    addnav("Editor Tenute","suhouses.php");
}else if ($_GET['op']=="newhouse"){
    addnav("Editor Tenute","suhouses.php");
    if ($_GET['subop']=="save"){ // save new house
        if ($_POST['auto']=="true"){ // check given data
            $sql = "SELECT house,housekey FROM accounts WHERE acctid=$_POST[owner]";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if ($row['house']>0 && $_POST['owner']){
                output("`\$errore: Il player scelto ha già una tenuta o non esiste.");
            }else if (!$_POST['housename']){
                output("`\$errore: Devi inserire un nome per la Tenuta.");
            }else if ((int)$_POST['owner']<1 && (int)$_POST['status']>1){
                output("`\$errore: Il player scelto DEVE essere il proprietario della Tenuta.");
            }else{
                if ((int)$_POST[status]>1 && (int)$_POST[owner]>0){
                    output("`^warning: A house with this status can not be owned. Owner will be set to 0. `n");
                    $_POST[owner]="0";
                }
                output("`@Nuova Tenuta Creata.`n");
                $sql = "INSERT INTO houses (owner,status,gold,gems,housename,description) VALUES ($_POST[owner],$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[description]')";
                db_query($sql);
                $sql = "SELECT houseid FROM houses WHERE owner=$_POST[owner] AND housename=$_POST[housename]";
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                if ($_POST['status']=="1" || $_POST['status']=="2" || $_POST['status']=="3"){
                    for ($i=1;$i<10;$i++){
                        $sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Master Key',".($_POST[status]=="1"?"$_POST[owner]":"0").",'Key',$row2[houseid],$i,'Key for Estate Number $row2[houseid]')";
                        db_query($sql);
                    }
                    output("`@Le chiavi sono state registrate nel database`n");
                }
                if ($_POST['status']=="0" || $_POST['status']=="1"){
                    $sql="UPDATE accounts SET house=$row2[houseid],housekey=".($_POST['status']=="1"?"$row[houseid]":"0")." WHERE acctid=$_POST[owner]";
                    output("`@User Database Changed`n");
                    db_query($sql);
                }
            }
        }else{
            output("`@Nuova Tenuta Creata.");
            $sql = "INSERT INTO houses (owner,status,gold,gems,housename,description) VALUES ($_POST[owner],$_POST[status],$_POST[gold],$_POST[gems],'$_POST[housename]','$_POST[description]')";
            db_query($sql);
        }
    }else{
        output("`@Proprietà Nuova Tenuta:`n`n");
        output("`0<form action=\"suhouses.php?op=newhouse&subop=save\" method='POST'>",true);
        output("<table><tr><td>Nome </td><td><input name='housename' maxlength='25'></td></tr>",true);
        output("<tr><td>Oro </td><td><input type='text' name='gold' value='0'> </td></tr>",true);
        output("<tr><td>Gemme </td><td><input type='text' name='gems' value='0'></td></tr>",true);
        output("<tr><td>Descrizione </td><td><input type='text' name='description' maxlength='250'></td></tr>",true);
        output("<tr><td>Stato </td><td><input type='text' name='status' value='2'></td></tr>",true);
        output("<tr><td>`4Proprietario (ID)`0 </td><td><input type='text' name='owner' value='0'> `4(ATTENZIONE!)`0</td></tr>",true);
        output("<tr><td>`4Modo Sicuro`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(ATTENZIONE!)`0</td></tr></table>`n",true);
        output("<input type='submit' class='button' value='Salva'></form>",true);
        output("`0`n`nQuando apporti modifiche al proprietario o alle chiavi di una tenuta, ricordati di apportare le stesse modifiche nell'Editor Utenti! ");
        output("Considera chi ha le chiavi quando appporti delle modifiche!");
        disp_status();
        addnav("","suhouses.php?op=newhouse&subop=save");
    }
}else if ($_GET['op']=="keys"){
    addnav("Editor Tenute","suhouses.php");
    addnav("Stato della Tenuta # $_GET[hid]","suhouses.php?op=drin&id=$_GET[hid]");
    if ($_GET['subop']=="change"){ // reset key owner
        $sql="UPDATE items SET owner=$_GET[owner] WHERE value1=$_GET[hid] AND class='Key' AND value2=$_GET[id2]";
        db_query($sql);
        output("`@Chiave `^$_GET[id2]`@ della Tenuta # `^$_GET[hid]`@ rifiutata.");
    }else if ($_GET['subop']=="edit"){ // enter new values for key
        $sql = "SELECT * FROM items WHERE id=$_GET[id]";
        $result = db_query($sql) or die(db_error(LINK));
        $item = db_fetch_assoc($result);
        output("`@Chiave Numero $item[value2] (item-ID $_GET[id]) Per Tenuta $_GET[hid] :`n`n");
        output("`0<form action=\"suhouses.php?op=keys&subop=edit2&id=$_GET[id]&hid=$_GET[hid]\" method='POST'>",true);
        output("<table>",true);
        output("<tr><td>Proprietario della Chiave (acctid) </td><td><input type='text' name='owner' value='$item[owner]'></td></tr>",true);
        // output("<tr><td>Für Haus Nr. (value1) </td><td><input type='text' name='value1' value='$item[value1]'></td></tr>",true); // to change house delete the key and add a new key in other house
        output("<tr><td>In uso? (hvalue: 0 or Estate#) </td><td><input type='text' name='hvalue' value='$item[hvalue]'></td></tr>",true);
        output("<tr><td>`4ID-Chiave (value2: Attuale #)`0 </td><td><input type='text' name='value2' value='$item[value2]'> `4(ATTENZIONE!)`0</td></tr>",true);
        output("</table>`n",true);
        output("<input type='submit' class='button' value='Salva'></form>",true);
        output("`0`n`nNon puoi assegnare ID-Chiave duplicati.`nKey useless Owner become all irrecoverable  handled.");
        addnav("","suhouses.php?op=keys&subop=edit2&id=$_GET[id]&hid=$_GET[hid]");
    }else if ($_GET['subop']=="edit2"){ // save new values into DB
        $sql = "SELECT * FROM items WHERE id=$_GET[id]";
        $result = db_query($sql) or die(db_error(LINK));
        $item = db_fetch_assoc($result);
        $action=false;
        if ((int)$_POST['value2']!=(int)$item['value2']){
            $sql = "SELECT id FROM items WHERE class='Key' AND value1=$_GET[hid] AND value2=$_POST[value2]";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if ($row['id']){
                output("`\$Errore: Questo ID-Chiave è già assegnato.");
            }else{
                $action=true;
            }
        }
        if ((int)$item['owner']!=(int)$_POST['owner']){
            $action=false;
            $sql = "SELECT acctid FROM accounts WHERE acctid=$_POST[owner]";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if (!$row['acctid']){
                output("`\$Errore: Questo utente non esiste.");
            }else{
                $action=true;
            }
        }
        if ($action){
            $sql = "UPDATE items SET owner=$_POST[owner],value2=$_POST[value2],hvalue=$_POST[hvalue] WHERE id=$_GET[id]";
            db_query($sql);
            output("`@Modifiche accettate.");
        }
    }else if ($_GET['subop']=="savenew"){ // save new key
        if ($_POST['value2']){
            $sql = "SELECT value1,value2 FROM items WHERE class='Key' AND value2=$_POST[value2] AND value1=$_GET[hid]";
            $result = db_query($sql) or die(db_error(LINK));
            $item = db_fetch_assoc($result);
            $sql="SELECT acctid FROM accounts WHERE acctid=$_POST[owner]";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
        }
        if (!$_POST['value2']){
            output("`\$Errore: Devi inserire un ID-Chiave");
        }else if ((int)$item['value2']==(int)$_POST['value2']){
            output("`\$Errore: Questo ID-Chiave è già assegnato.");
        }else if (!$row['acctid']){
            output("`\$Errore: Questo utente non esiste.");
        }else{
            $sql = "INSERT INTO items (name,owner,class,value1,value2,hvalue,description) VALUES ('housekey',$_POST[owner],'Key',$_GET[hid],$_POST[value2],$_POST[hvalue],'Key for House Number $_GET[hid]')";
            db_query($sql);
            output("`@Chiave registrata.");
        }
    }else if ($_GET['subop']=="delete"){ // delete key
        output("`n`n`@Chiave Cancellata!");
        $sql = "DELETE FROM items WHERE id=$_GET[id]";
        db_query($sql);
    }else{ // enter new key
        output("`@Crea una nuova Chiave per la Tenuta: $_GET[hid] `n`n");
        output("`0<form action=\"suhouses.php?op=keys&subop=savenew&hid=$_GET[hid]\" method='POST'>",true);
        output("<table>",true);
        output("<tr><td>Proprietario (acctid) </td><td><input type='text' name='owner' value='0'></td></tr>",true);
        output("<tr><td>In uso? (hvalue: 0 for Tenuta # ) </td><td><input type='text' name='hvalue' value='0'></td></tr>",true);
        output("<tr><td>`4ID-Chiave (value2: Account # )`0 </td><td><input type='text' name='value2'> `4(ATTENZIONE!)`0</td></tr>",true);
        output("</table>`n",true);
        output("<input type='submit' class='button' value='Salva'></form>",true);
        output("`0`n`nSe l'ID-Chiave è già assegnato, la Chiave può diventare irrecuperabile.");
        addnav("","suhouses.php?op=keys&subop=savenew&hid=$_GET[hid]");
    }
}else if ($_GET['op']=="data"){
    addnav("Editor Tenute","suhouses.php");
    addnav("Stato della Tenuta # $_GET[id]","suhouses.php?op=drin&id=$_GET[id]");
    if ($_GET['subop']=="save"){ // save values
        $action=false;
        if ($_POST['auto']=="true"){ // check given data
            $sql = "SELECT * FROM houses WHERE houseid=$_GET[id]";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $sql = "SELECT house,housekey FROM accounts WHERE acctid=$_POST[owner]";
            $result2 = db_query($sql) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
            if ($row2['house']!=$_GET['id'] && $row2['house']>0){
                output("`\$Errore: L'utente occupa già un'altra Tenuta o la Tenuta non esiste. Il database non sarà aggiornato.");
            }else if ($row['status']!=$_POST['status'] && $row['owner']!=$_POST['owner']){
                output("`\$Errore: Stato e Proprietario possono essere in Safe Mode. Non sono accettate modifiche simultanee. Il database non sarà aggiornato.");
            }else{
                if ($row['owner']!=$_POST['owner'] && ($_POST['status']=="3" || $_POST['status']=="4")){
                    $_POST['status']="0";
                    output("`^Attenzione: la Tenuta non può essere modificata in questo stato mentre è in possesso di un altro player.");
                    output("`n`6Il proprietario di questa Tenuta sarà settato a 0.`n");
                }
                if ($row['status']!=$_POST['status'] && (int)$_POST['status']>2 && (int)$_POST['owner']>0){
                    $_POST['owner']="0";
                    output("`^Attenzione: lo stato di questa tenuta è perso con questo Proprietario. Settato Stato 0 (in costruzione).`n");
                }
                if ($row['status']!=$_POST['status'] && $row['owner']==0 && (int)$_POST['status']<3){
                    output("`^Attenzione: Questo stato richiede un Proprietario! Per favore setta un proprietario per questa tenuta!`n");
                }
                $action=true;
                if ((int)$_POST['status']!=(int)$row['status']){
                    if ($_POST['status']=="0" || $_POST['status']=="4"){
                        $sql="DELETE FROM items WHERE class='Key' AND value1=$_GET[id]";
                        db_query($sql) or die(db_error(LINK));
                        $house=0;
                        if ($_POST['status']=="0") $house=$_GET[id];
                        $housekey=0;
                        output("`@Tutte le chiavi nel database sono state resettate.`n");
                    }
                    if ($_POST['status']=="3" && $row['status']!=4 && $row['status']!=0){
                        $house=0;
                        $housekey=0;
                        $sql="UPDATE items SET owner=0 WHERE class='Key' AND owner=$row[owner] AND value1=$_GET[id]";
                        db_query($sql) or die(db_error(LINK));
                        output("`@No key was assigned to be reset`n");
                    }else if ($_POST['status']=="3"){
                        $house=0;
                        $housekey=0;
                        for ($i=1;$i<10;$i++){
                            $sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('housekey',0,'Key',$_GET[id],$i,'Key for House Number $_GET[id]')";
                            db_query($sql) or die(db_error(LINK));
                        }
                        output("`@Nuove Chiavi registrate nel Database`n");
                    }
                    if ($_POST['status']=="1" && ($row['status']==0 || $row['status']==4)){
                        for ($i=1;$i<10;$i++){
                            $sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('housekey',$_POST[owner],'Key',$_GET[id],$i,'Key for House Number $_GET[id]')";
                            db_query($sql) or die(db_error(LINK));
                        }
                        $house=$_GET['id'];
                        $housekey=$_GET['id'];
                        output("`@Le nuove Chiavi per questa Tenuta sono state create nel database.`n");
                    }elseif ($_POST['status']=="1"){
                        $sql="UPDATE items SET owner=$_POST[owner] WHERE class='Key' AND owner=0 AND value1=$_GET[id]";
                        db_query($sql) or die(db_error(LINK));
                        $house=$_GET['id'];
                        $housekey=$_GET['id'];
                    }
                    if ($_POST['status']=="2" && ($row['status']==0 || $row['status']==4)){
                        for ($i=1;$i<10;$i++){
                            $sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('housekey',0,'Key',$_GET[id],$i,'Key for House Number $_GET[id]')";
                            db_query($sql) or die(db_error(LINK));
                        }
                        $house=$_GET['id'];
                        $housekey=$_GET['id'];
                        output("`@Chiave registrata nel database`n");
                    }elseif ($_POST['status']=="2"){
                        $sql="UPDATE items SET owner=0 WHERE class='Key' AND value1=$_GET[id]";
                        db_query($sql) or die(db_error(LINK));
                        $house=$_GET['id'];
                        $housekey=0;
                    }
                    $sql="UPDATE accounts SET house=$house,housekey=$housekey WHERE acctid=$row[owner]";
                    db_query($sql) or die(db_error(LINK));
                }else{
                    $sql="UPDATE accounts SET house=0,housekey=0 WHERE acctid=$row[owner]";
                    db_query($sql) or die(db_error(LINK));
                    if ($_POST['status']=="1"){
                        $housekey=$_GET['id'];
                    }else{
                        $housekey=0;
                    }
                    $sql="UPDATE accounts SET house=$_GET[id],housekey=$housekey WHERE acctid=$_POST[owner]";
                    db_query($sql) or die(db_error(LINK));
                    $sql="UPDATE items SET owner=$_POST[owner] WHERE class='Key' AND owner=$row[owner] AND value1=$_GET[id]";
                    db_query($sql) or die(db_error(LINK));
                }
            }
        }else{
            $action=true;
        }
        if ($action){
            output("`@Dati Salvati.");
            $_POST[housename]=addslashes(rawurldecode($_POST[housename]));
            $_POST[description]=addslashes(rawurldecode($_POST[description]));
            $sql="UPDATE houses SET owner=$_POST[owner],housename='".$_POST[housename]."',gold=$_POST[gold],gems=$_POST[gems],fede=$_POST[fede],status=$_POST[status],description='".$_POST[description]."'WHERE houseid=$_GET[id]";
            db_query($sql) or die(db_error(LINK));
        }
    }else{
        $sql = "SELECT * FROM houses WHERE houseid=$_GET[id]";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`@Dati per la Tenuta `b$_GET[id]`b alter:`n`n");
        output("`0<form action=\"suhouses.php?op=data&subop=save&id=$_GET[id]\" method='POST'>",true);
        output("<table><tr><td>Nome </td><td><input name='housename' maxlength='25' value='".(rawurlencode($row[housename]))."'></td></tr>",true);
        output("<tr><td>Oro </td><td><input type='text' name='gold' value='$row[gold]'> </td></tr>",true);
        output("<tr><td>Gemme </td><td><input type='text' name='gems' value='$row[gems]'></td></tr>",true);
        output("<tr><td>Descrizione </td><td><input type='text' name='description' maxlength='250' value='".(rawurlencode($row[description]))."'></td></tr>",true);
        output("<tr><td>Fede </td><td><input type='text' name='fede' value='".$row[fede]."'></td></tr>",true);
        output("<tr><td>`4Stato`0 </td><td><input type='text' name='status' value='$row[status]'> `4(ATTENZIONE!)`0</td></tr>",true);
        output("<tr><td>`4Proprietario (ID)`0 </td><td><input type='text' name='owner' value='$row[owner]'> `4(ATTENZIONE!)`0</td></tr>",true);
        output("<tr><td>`4Safe Mode`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(ATTENZIONE!)`0</td></tr></table>`n",true);
        output("<input type='submit' class='button' value='Salva'></form>",true);
        output("`0`n`n`6Questo pannello di controllo modifica SOLO i dati nel database delle tenute. `n`7Le modifiche al proprietario non avranno pieno effetto fino a che non editi il proprietario della Tenuta. `nPer modificare il Proprietario della Tenuta, vai all'Editor Utenti.`n");
        addnav("","suhouses.php?op=data&subop=save&id=$_GET[id]");
        disp_status();
    }
}else{
    output("`@`b`cLe Tenute`c`b`n`n");
    output("`cScegli una Tenuta da Esaminare:`c`n");
    output("<table cellpadding=2 align='center'><tr><td>`bTenuta #.`b</td><td>`bNome`b</td></tr>",true);
    $ppp=25; // Player Per Page +1 to display
    if (!$_GET[limit]){
        $page=0;
    }else{
        $page=(int)$_GET['limit'];
        addnav("Pagina Precedente","suhouses.php?limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql = "SELECT houseid,housename FROM houses WHERE 1 ORDER BY houseid ASC LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","suhouses.php?limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iNon ci sono Tenute.`i`0</td></tr>",true);
    }else{
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);
            output("<tr><td align='center'>$row2[houseid]</td><td><a href='suhouses.php?op=drin&id=$row2[houseid]'>$row2[housename]</a></td></tr>",true);
            addnav("","suhouses.php?op=drin&id=$row2[houseid]");
        }
    }
    output("</table>",true);
    addnav("Opzioni");
    addnav("Utenti con Tenuta","suhouses.php?op=info");
    addnav("Nuova Tenuta","suhouses.php?op=newhouse");
}
addnav("Altro");
addnav("Torna alla Grotta","superuser.php");
addnav("Torna alla Mondanità","village.php");
output("`n<div align='right'>`)2004 by anpera</div>",true);
page_footer();
?>