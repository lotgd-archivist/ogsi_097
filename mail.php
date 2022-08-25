<?php
require_once "common.php";
require_once "common2.php";
if ($_GET['op']=="del2")
  {
    if ($_POST['delart']=="sys")
      {
        $sql = "DELETE FROM mail WHERE msgto='".$session['user']['acctid']."' AND msgfrom=0";
        db_query($sql);
        header("Location: mail.php");
        exit();
      }
    elseif ($_POST['delart']=="ugdel")
      {
        $sql = "DELETE FROM mail WHERE msgto='".$session['user']['acctid']."' AND seen=0";
        db_query($sql);
        header("Location: mail.php");
        exit();
      }
    else
      {
        $sql = "DELETE FROM mail WHERE msgto='".$session['user']['acctid']."' AND seen=1";
        db_query($sql);
        header("Location: mail.php");
        exit();
      }
  }
if (date("H") > 4 AND date("H") < 7){
    $sql = "DELETE FROM mail WHERE sent<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("oldmail",14)."days"))."'";
    db_query($sql);
}

/**************************************
 *                                    *
 * Dati di testa, sono sempre qui     *
 *                                    *
 *************************************/

popup_header("Il Vecchio Ufficio Postale");
output("<a href='mail.php' class='motd'>Posta Ricevuta</a><a href='mail.php?op=address' class='motd'>Scrivi Mail</a>`n`n",true);
output("<a href='mail.php?op=buch' class='motd'>Rubrica</a>",true);  // www.plueschdrache.de Adressbuch
output("<a href='mail.php?op=block' class='motd'>Ignore List</a>`n`n",true); // www.anaras.ch Blockliste
output("<a href='mail.php?op=outbox' class='motd'>Posta Inviata</a>`n`n",true);

/**************************************
 *                                    *
 * Wir gucken jetzt mal was genau     *
 * gemacht wird                       *
 *                                    *
 *************************************/

switch($_GET['op']) {


/**************************************
 *                                    *
 * Die einzelnen Optionen,            *
 * die ausgeführt werden              *
 *                                    *
 *************************************/


case "del":
    /**************************************
     *                                    *
     * Eine Mail löschen                  *
     *                                    *
     *************************************/
    $sql = "DELETE FROM mail WHERE msgto='".$session['user']['acctid']."' AND messageid='$_GET[id]'";
    db_query($sql);
    header("Location: mail.php");
    exit();

    break;

case "process":
    /**************************************
     *                                    *
     * mehrere Mails löschen              *
     *                                    *
     *************************************/

    // keine Mails ausgewählt
    if (!is_array($_POST['msg']) || count($_POST['msg'])<1){
        $session['message'] = "`\$`bNon puoi cancellare zero messaggi!  Che significa? Hai premuto \"Cancella Selezionati\" ma non ci sono messaggi selezionati! Che razza di mondo è questo in cui la gente preme pulsanti che non hanno senso?!?`b`0";
        header("Location: mail.php");

    // Mails ausgewählt
    } else {
        $sql = "DELETE FROM mail WHERE msgto='".$session['user']['acctid']."' AND messageid IN ('".join("','",$_POST['msg'])."')";
        db_query($sql);
        header("Location: mail.php");
        exit();
    }

    break;


case "send":
    /**************************************
     *                                    *
     * Eine Mail senden                   *
     *                                    *
     *************************************/

        $sql = "SELECT acctid FROM accounts WHERE login='".$_POST['to']."'";
        $result = db_query($sql);
        if (db_num_rows($result)>0){
            $row1 = db_fetch_assoc($result);
            $sql = "SELECT count(messageid) AS count FROM mail WHERE msgto='".$row1['acctid']."' AND seen=0";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            $sqlb = "SELECT blocked FROM block WHERE blocker='".$row1['acctid']."' AND blocked='".$session['user']['acctid']."'";
            $resultb = db_query($sqlb);
            $block = db_fetch_assoc($resultb);
            $sqlb1 = "SELECT blocked FROM block WHERE blocker='".$session['user']['acctid']."' AND blocked='".$row1['acctid']."'";
            $resultb1 = db_query($sqlb1);
            $block1 = db_fetch_assoc($resultb1);
            if(db_num_rows($resultb)>0){
                output("`\$`bQuesta persona ti ha bloccato!`b`n`n`0");

            } else if(db_num_rows($resultb1)>0){
                output("`\$`bHai bloccato questa persona!`b`n`n`0");

            } else if ($row[count]>getsetting("inboxlimit",50)) {
                output("Il destinatario ha la casella piena e non può ricevere posta!");

            }else{
                //$_POST['subject']=closetags(str_replace("`n","",$_POST['subject']),'`c`i`b');
                $_POST['subject']=str_replace("`n","",$_POST['subject']);
                $_POST['body']=str_replace("`n","\n",$_POST['body']);
                $_POST['body']=str_replace("\r\n","\n",$_POST['body']);
                $_POST['body']=str_replace("\r","\n",$_POST['body']);
                $_POST['body']=addslashes(substr(stripslashes($_POST['body']),0,(int)getsetting("mailsizelimit",1024)));
                //$_POST['body'] = closetags($_POST['body'],'`c`i`b');
                //systemmail($row1['acctid'],HTMLEntities2($_POST['subject']),HTMLEntities2($_POST['body']),$session['user']['acctid']);
                systemmail($row1['acctid'],$_POST['subject'],$_POST['body'],$session['user']['acctid']);
                output("Messaggio inviato!`n");
            }
        }else{
            output("Destinatario non trovato, riprova.`n");
        }
    break;

/**************************************
 *                                    *
 * Die Standardansichten              *
 *                                    *
 *************************************/


default:
    /**************************************
     *                                    *
     * Posteingang                        *
     *                                    *
     **************************************/
    output("`b`iCasella Postale`i`b");
    output($session['message']);
    $session['message']="";
    $sql = "SELECT mail.subject,mail.messageid,accounts.name,mail.msgfrom,mail.seen,mail.sent
            FROM mail
            LEFT JOIN accounts ON accounts.acctid=mail.msgfrom
            WHERE mail.msgto=\"".$session['user']['acctid']."\" ORDER BY mail.seen,mail.sent";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("<form action='mail.php?op=process' method='POST'><table>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);

            if ((int)$row['msgfrom']==0) {
               $row['name']="`i`^Sistema`0`i";
            }
            output("<tr>",true);
            output("<td nowrap><input id='checkbox$i' type='checkbox' name='msg[]' value='$row[messageid]'><img src='images/".($row['seen']?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row['seen']?"Vecchi":"Nuovi")."'></td>",true);
            output("<td><a href='mail.php?op=read&id=$row[messageid]'>",true);
            output(html_entity_decode($row['subject']));
            output("</a></td><td><a href='mail.php?op=read&id=$row[messageid]'>",true);
            output($row['name']);
            output("</a></td><td><a href='mail.php?op=read&id=$row[messageid]'>".date("M d, h:i a",strtotime($row['sent']))."</a></td>",true);
            output("</tr>",true);
        }

        output("</table>",true);

        $out="<input type='button' value='Seleziona Tutto' class='button' onClick='";
        for ($i=$i-1;$i>=0;$i--){
            $out.="document.getElementById(\"checkbox$i\").checked=true;";
        }
        $out.="'>";

        output($out,true);
        output("<input type='submit' class='button' value='Cancella Selezionati'>",true);
        output("</form>",true);
        output("<br><form action='mail.php?op=del2' method='POST'>"
              ."<select name='delart'>"
              ."<option value='sys'>Cancella Mail di Sistema"
              ."<option value='ugdel'>Cancella non Lette"
              ."<option value='gdel'>Cancella Lette"
              ."</select>"
              ."<input type='submit' class='button' value='Esegui'>"
              ,true);

    }else{
        output("`iYawn, non hai posta, che tristezza.`i");

    }

    output("`n`n`iHai ".db_num_rows($result)." messaggi nella tua casella.`nPuoi avere al massimo ".getsetting('inboxlimit',50)." messaggi nella tua casella.`nI messaggi vengono cancellati dopo ".getsetting("oldmail",14)." giorni.");
    break;

    /**********************************
    *      Postausgang                *
    **********************************/

/***********************************************
*Diese Box darf nicht entfernt werden!        *
*-------------------------------------        *
*Outbox von Tweety und Kelko                  *
*                                             *
*www.tugc-lotgd.6x.to                         *
***********************************************/
case "outbox":

    output("`b`iInviata`i`b");
    output($session['message']);
    $session['message']="";
    $sql = "SELECT mail.subject,mail.messageid,accounts.name,mail.msgfrom,mail.seen,mail.sent FROM mail INNER JOIN accounts ON accounts.acctid=mail.msgto WHERE mail.msgfrom=\"".$session['user']['acctid']."\" ORDER BY mail.seen,mail.sent";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("<table>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);

            output("<tr>",true);
            output("<td nowrap><img src='images/".($row['seen']?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row['seen']?"Vecchi":"Nuovi")."'></td>",true);
            output("<td><a href='mail.php?op=readSend&id=$row[messageid]'>",true);
            output(html_entity_decode($row['subject']));
            output("</a></td><td><a href='mail.php?op=readSend&id=$row[messageid]'>",true);
            output($row['name']);
            output("</a></td><td><a href='mail.php?op=readSend&id=$row[messageid]'>".date("M d, h:i a",strtotime($row['sent']))."</a></td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
            output("</form>",true);
       }else{
            output("`iYawn, non hai posta, che tristezza.`i");

       }
       output("`n`n`iHai ".db_num_rows($result)." messaggi nella tua casella.`nPuoi avere al massimo ".getsetting('inboxlimit',50)." messaggi nella tua casella.`nI messaggi vengono cancellati dopo ".getsetting("oldmail",14)." giorni.");
    break;
case "readSend":
    /**************************************
     *                                    *
     * Mails ausgang lesen                         *
     *                                    *
     **************************************/

    $sql = "SELECT mail.*,accounts.name FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE mail.messageid=\"".$_GET['id']."\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);

        output("`b`2Destinatario:`b `^$row[name]`n");
        output("`b`2Soggetto:`b `^$row[subject]`n");
        output("`b`2Inviata:`b `^{$row['sent']}`n");
        output("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output(html_entity_decode(str_replace("\n","`n","$row[body]")));
        output("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);

    }else{
        output("Uff, il messaggio non è stato trovato!");

    }
    break;
case "read":
    /**************************************
     *                                    *
     * Mail lesen                         *
     *                                    *
     **************************************/

    $sql = "UPDATE mail SET seen=1 WHERE  msgto=\"".$session['user']['acctid']."\" AND messageid=\"".$_GET['id']."\"";
    db_query($sql);
    $sql = "SELECT mail.*,accounts.name FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto=\"".$session['user']['acctid']."\" AND mail.messageid=\"".$_GET['id']."\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);

        if ((int)$row['msgfrom']==0) {
            $row['name'] = "`i`^Sistema`0`i";
        }

        output("`b`2Mittente:`b `^".$row['name']."`n");
        output("`b`2Soggetto:`b `^".html_entity_decode($row['subject'])."`n");
        output("`b`2Inviata:`b `^".$row['sent']."`n");
        output("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output(html_entity_decode(str_replace("\n","`n",$row['body'])));
        output("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output("<a href='mail.php?op=write&replyto=$row[messageid]' class='motd'>Rispondi</a><a href='mail.php?op=del&id=$row[messageid]' class='motd'>Cancella</a>",true);

    }else{
        output("Uff, il messaggio non è stato trovato!");

    }
    break;


case "address":
    /**************************************
     *                                    *
     * Empfänger wählen                   *
     *                                    *
     **************************************/
    output("<form action='mail.php?op=write' method='POST'>",true);
    output("`b`2Destinatario:`b`n");
    output("`2<u>A</u>: <input name='to' accesskey='a'> <input type='submit' class='button' value='Cerca'></form>",true);

    break;

case "write":
    /**************************************
     *                                    *
     * Eine Mail schreiben                *
     *                                    *
     **************************************/

    $subject="";
    $body="";
    output("<form action='mail.php?op=send' method='POST'>",true);
    if ($_GET['replyto']!=""){
        $sql = "SELECT mail.body,mail.subject,accounts.login,accounts.name
                FROM mail
                LEFT JOIN accounts ON accounts.acctid=mail.msgfrom
                WHERE mail.msgto=\"".$session['user']['acctid']."\" AND mail.messageid=\"".$_GET['replyto']."\"";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
            if ($row['login']=="") {
                output("Non puoi rispondere ad un messaggio di sistema.`n");
                $row=array();
            }
        }else{
            output("Eek, non è stato trovato quel messaggio!`n");
        }
    }
    if ($_GET['to']!=""){
        $sql = "SELECT login,name FROM accounts WHERE login='".$_GET['to']."'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
        }
    }
    if ($_POST['to']!=""){
        $sql = "SELECT login,name FROM accounts WHERE login='".$_POST['to']."'";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
        }
    }
    if (is_array($row)){
        if ($row['subject']!=""){
            $subject=$row['subject'];
            if (substr($subject,0,4)!="RE: ") $subject="RE: $subject";
        }
        if ($row['body']!=""){
            $body="\n\n---Messaggio Originale---\n".$row['body'];
        }
    }
    if ($row['login']!=""){
        output("<input type='hidden' name='to' value=\"".HTMLEntities2($row['login'])."\">",true);
        output("`2A: `^".$row['name']."`n");
    }else{
        output("`2A: ");
        $string ="%".$_POST['to']."%";
        /*Excalibur: modifica per trovare correttamente i nomi dei player
        $string="%";
        for ($x=0;$x<strlen($_POST['to']);$x++){
            $string .= substr($_POST['to'],$x,1)."%";
        } */
        $sql = "SELECT login,name FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY login";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            output("<input type='hidden' name='to' value=\"".HTMLEntities2($row['login'])."\">",true);
            output("`^".$row['name']."`n");
        }else{
            output("<select name='to'>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".HTMLEntities2($row['login'])."\">",true);
                output(preg_replace("/[`]./","",$row['name']));
            }
            output("</select>`n",true);
        }
    }
    output("`2Soggetto:");
    $output.=("<input name='subject' value=\"".HTMLEntities2($subject).HTMLEntities2(stripslashes($_GET['subject']))."\">");
    $output.=("<div id='warning' style='visibility: hidden; display: none;'></div>");
    output("`n`2Testo:`n");
    $output.="<textarea name='body' class='input' cols='40' rows='9' onKeyUp='sizeCount(this);'>".HTMLEntities2($body).HTMLEntities2(stripslashes($_GET['body']))."</textarea><br>";
    output("<input type='submit' class='button' value='Invia'><div id='sizemsg'></div>`n",true);
    if ($row['petitionid']>0) output('<input type="hidden" name="petitionid" value="'.$row['petitionid'].'">',true);
    output("</form>",true);
    $sizemsg = "`#Dimensione max messaggio: `@%s`#, hai ancora `^XX`# caratteri.";
    //$sizemsg = translate_inline($sizemsg);
    $sizemsg = sprintf($sizemsg,getsetting("mailsizelimit",1024));
    $sizemsgover = "`\$Dimensione max messaggio: `@%s`\$, sei fuori di `^XX`\$ caratteri!";
    //$sizemsgover = translate_inline($sizemsgover);
    $sizemsgover = sprintf($sizemsgover,getsetting("mailsizelimit",1024));
    $sizemsg = explode("XX",$sizemsg);
    $sizemsgover = explode("XX",$sizemsgover);
    $usize1 = addslashes("<span>".appoencode($sizemsg[0])."</span>");
    $usize2 = addslashes("<span>".appoencode($sizemsg[1])."</span>");
    $osize1 = addslashes("<span>".appoencode($sizemsgover[0])."</span>");
    $osize2 = addslashes("<span>".appoencode($sizemsgover[1])."</span>");

    rawoutput("
    <script language='JavaScript'>
        var maxlen = ".getsetting("mailsizelimit",1024).";
        function sizeCount(box){
            var len = box.value.length;
            var msg = '';
            if (len <= maxlen){
                msg = '$usize1'+(maxlen-len)+'$usize2';
            }else{
                msg = '$osize1'+(len-maxlen)+'$osize2';
            }
            document.getElementById('sizemsg').innerHTML = msg;
        }
        sizeCount(document.getElementById('textarea'));

        function check_su_warning(){
            var to = document.getElementById('to');
            var warning = document.getElementById('warning');
            if (superusers[to.value]){
                warning.style.visibility = 'visible';
                warning.style.display = 'inline';
            }else{
                warning.style.visibility = 'hidden';
                warning.style.display = 'none';
            }
        }
        check_su_warning();

    </script>");

    break;

/**************************************
 *                                    *
 * Das Adressbuch                     *
 *                                    *
 **************************************/

case "buch":

    //Adressbuch einsehen

    /***********************************************
     *Diese Box darf nicht entfernt werden!        *
     *-------------------------------------        *
     *Adressbuch von deZent und draKarr            *
     *Version: 0.5                                 *
     *www.plueschdrache.de                         *
     ***********************************************/

    $sql   ="SELECT DISTINCT player, descr FROM  mailadressen WHERE acctid=".$session['user']['acctid']." ORDER BY player;";
    $result=mysql_query($sql);
    $menge =mysql_num_rows($result);
    output("`c`bRubrica`b`c`n`n");
    output("<table>",true);
    for ($i=0;$i<$menge;$i++){
    output("<tr><td><a href='mail.php?op=write&to=".mysql_result($result,$i,"player")."'>&raquo </a></td>
        <td><a href='mail.php?op=write&to=".mysql_result($result,$i,"player")."'>".mysql_result($result,$i,"player")."</a></td>
        <td>&nbsp;&nbsp;</td>
        <td> ".mysql_result($result,$i,"descr")."</td>
        </tr> ",true);
    }
    if (!$menge){
    output("`n`\$Non hai nessun contatto!`7`n");}
    output("</table>",true);
    output("`n`n");
    output("<a href='mail.php?op=neuerkontakt' class='motd'>Nuovo Contatto</a>
        <a href='mail.php?op=delkontakt' class='motd'>Cancella Contatto</a>",true);
    break;

case "neuerkontakt":
    //Neuen Kontakt hinzufügen 1
    //Namen suchen

    output("<form action='mail.php?op=neuerkontakt2' method='POST'>",true);
    output("`b`2Nome:`b`n");
    output("<input name='to' accesskey='a' value='".$_GET['name']."'> <input type='submit' class='button' value='Cerca Contatto'></form>",true);
    break;


case "neuerkontakt2":
    //Neuen Kontakt hinzufügen 2
    //Namen auswählen

    output("`2Nome: ");
    $string="%";
    for ($x=0;$x<strlen($_POST['to']);$x++){
        $string .= substr($_POST['to'],$x,1)."%";
    }

    $sql = "SELECT login,name FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY login";
    $result = db_query($sql);
    output("<form action='mail.php?op=neuerkontakt3' method='POST'>",true);
    if (db_num_rows($result)==1){
        $row = db_fetch_assoc($result);
        output("<input type='hidden' name='to' value=\"".HTMLEntities2($row['login'])."\">",true);
        output("`^".$row['name']."`n");
    }else{
        output("<select name='to'>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<option value=\"".HTMLEntities2($row['login'])."\">",true);
            output(preg_replace("/[`]./","",$row['name']));
        }
        output("</select><br>`n",true);
    }

    output("<br>Descrizione [max.80]:<input type='text' name='descr' maxlenght='80' size='40'>",true);
    output("<br><br><input type='submit' name='s1' value='Aggiungi Contatto'>`n",true);
    output("</form>`n",true);
    break;

case "neuerkontakt3":
    //Neuen Kontakt hinzufügen 3
    //Eintrag schreiben

    $sql = "SELECT COUNT(*) as menge FROM mailadressen WHERE player='".$_POST['to']."'";
    $result = mysql_query($sql);
    $anzahl = mysql_result($result,0,"menge");

    if ($menge>0){
        output("<font size=+1>Questo contatto è già nella Rubrica!",true);

    } else {
        $descr = mysql_escape_string($_POST['descr']);
        $sql="INSERT INTO mailadressen (row_id, acctid, player, descr)
        VALUES (NULL, ".$session['user']['acctid'].", '".$_POST['to']."', '".$descr."')";
        mysql_query($sql);
        output("<font size=+1>Il Contatto è stato aggiunto.</font>",true);
    }

    output("<a href='mail.php?op=neuerkontakt' class='motd'>Nuovo Contatto</a>
        <a href='mail.php?op=delkontakt' class='motd'>Cancella Contatto</a>",true);
    break;

case "delkontakt":
    //Kontakt entfernen 1
    //Kontakt auswählen

    $sql   ="SELECT DISTINCT row_id,player FROM  mailadressen WHERE acctid=".$session['user']['acctid']." ORDER BY player;";
    $result=mysql_query($sql);
    $menge =mysql_num_rows($result);
    output("`c`bCancella Contatto`b`c`n`n");

    for ($i=0;$i<$menge;$i++){
        output("<a href='mail.php?op=delkontakt2&row=".mysql_result($result,$i,"row_id")."'>`\$[del] </a>`7 ".mysql_result($result,$i,"player")."<br>",true);
    }
    break;

case "delkontakt2":
    //Kontakt entfernen 2
    //Löschung durchführen

    $sql="DELETE FROM mailadressen WHERE row_id='".$_GET['row']."' LIMIT 1 ";
    mysql_query($sql);
    output("`\$<font size+1>Contatto Cancellato!</font>",true);
    break;

/**************************************
 *                                    *
 * Die Blockliste                     *
 *                                    *
 **************************************/


case "block":

    //Liste einsehen

    /**********************************************
     *Diese Box darf nicht entfernt werden!       *
     *--------------------------------------------*
     *Blockliste von Hadriel                      *
     *Version: 1.1                                *
     *www.anaras.ch                               *
     **********************************************/
    $sql ="SELECT blocked FROM block WHERE blocker=".$session['user']['acctid']." ORDER BY id";
    $result=db_query($sql);
    $blocki =db_num_rows($result);
    //output($block);
    output("`c`bIgnore List`b`c`n");
    output("<table>",true);
    //$sql ="SELECT blocked FROM block WHERE blocker=".$session['user']['acctid']." ORDER BY id";
    //$result=db_query($sql);
    //$block =db_fetch_assoc($result);

    for ($i=0;$i<$blocki;$i++){
        $block =db_fetch_assoc($result);
        $res= db_query("SELECT name,login,acctid FROM accounts WHERE acctid='".$block['blocked']."'");
        $player=db_fetch_assoc($res);
        output("<tr><td><a href='mail.php?op=delblock&to=".$player['acctid']."'>".$player['name']." `SSblocca</a></td>
        </tr> ",true);
    }

    if (!$blocki){
        output("`n`\$Nessun player nella Ignore List`7`n");
    }

    output("</table>",true);
    output("`n");
    output("<a href='mail.php?op=block1' class='motd'>Nuovo Player</a>",true);
    break;

case "block1":
    //Jemanden blocken 1
    //Namen eingeben

    output("<form action='mail.php?op=block2' method='POST'>",true);
    output("`b`2Nome:`b`n<input type='text' style='text-align : left' name='to'>",true);
    output("<input type='submit' class='button' value='Ricerca Player'></form>",true);
    break;

case "block2":
    //Jemanden blocken 2
    //Auswählen
    output("`2Nome: ");
    $string="%";

    for ($x=0;$x<strlen($_POST['to']);$x++){
        $string .= substr($_POST['to'],$x,1)."%";
    }

    $sql = "SELECT acctid,login,name FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY login";
    $result = db_query($sql);
    output("<form action='mail.php?op=block3' method='POST'>",true);
    if (db_num_rows($result)==1){
        $row = db_fetch_assoc($result);
        output("<input type='hidden' name='to' value=\"".HTMLEntities2($row['acctid'])."\">",true);
        output("`^".$row['name']."`n");
    }else{
        output("<select name='to'>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<option value=\"".HTMLEntities2($row['acctid'])."\">",true);
            output(preg_replace("/[`]./","",$row['name']));
        }

    output("</select><br>`n",true);

    }
    output("<br><br><input type='submit' name='s1' value='Ignora Player'><br>",true);
    output("</form>`n",true);
    break;

case "block3":
    //Jemanden Blocken
    //Block setzen

    $sql = "SELECT COUNT(*) as block FROM block WHERE blocked='".$_POST['to']."'";
    $result = db_query($sql);
    $anzahl = mysql_result($result,0,"block");
    $res= db_query("SELECT name,login,acctid,superuser FROM accounts WHERE acctid='".$_POST['to']."'");
    $player=db_fetch_assoc($res);

    //Überprüfen, ob man ignorieren darf
    if($player['superuser']==2){
        output("<font size=+1>`^Questo giocatore è `%Moderatore`^! </font>`^ Non puoi bloccarlo!",true);

    } else if($player['superuser']>=3){
        output("<font size=+1>`^Questo giocatore è `SAdmin`^!</font>`^ Non puoi bloccarlo!",true);

    } else if ($block>0){
        output("<font size=+1>Questo player è già ignorato!",true);

    } else {
// $descr = mysql_escape_string($_POST[descr]);
        $sql="INSERT INTO block (blocker, blocked)
            VALUES ('".$session['user']['acctid']."', '".$_POST['to']."')";
        db_query($sql);
        output("<font size=+1>Il player è stato aggiunto alla Ignore List.</font>",true);
    }

    output("<a href='mail.php?op=block1' class='motd'>Nuovo Player</a>
        <a href='mail.php?op=delblock' class='motd'>cancella Player</a>",true);
    break;

case "delblock":
    //Block aufheben 1
    //Block auswählen

    $sql ="SELECT DISTINCT id,blocker,blocked FROM block WHERE blocker=".$session['user']['acctid']." ORDER BY blocked;";
    $result=db_query($sql);
    $blocki =db_num_rows($result);
    //$sql ="SELECT id,blocked FROM block WHERE blocker=".$session['user']['acctid']." ORDER BY blocked;";
    //$result=db_query($sql);
    output("`c`bGiocatori nella Ignore List`b`c`n`n");

    for ($i=0;$i<$blocki;$i++){
        $block =db_fetch_assoc($result);
        $res= db_query("SELECT name, login, acctid FROM accounts WHERE acctid='".$block['blocked']."'");
        $player=db_fetch_assoc($res);
        output("<a href='mail.php?op=delblock2&row=".$block['id']."'>`\$[del] </a>`7 ".$player['name']." <br>",true);
    }
    break;

case "delblock2":
    //Block aufheben 2
    //Aus der liste löschen

    $sql="DELETE FROM block WHERE id='".$_GET['row']."' LIMIT 1 ";
    db_query($sql);
    output("`\$<font size+1>Player cancellato dalla Ignore List</font>",true);
    break;

//Ende vom switch
}

/**************************************
 *                                    *
 * Fußdaten, stehen immer da          *
 *                                    *
 *************************************/

popup_footer();
?>