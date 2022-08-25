<?php
/*
 * Author: Chaosmaker <webmaster@chaosonline.de>
 * Server: http://logd.chaosonline.de
 * Traduzione: Excalibur <excalibur@fastwebnet.it>
 * (www.ogsi.it/logd)
 *
 * Todolist for superuser grotto

CREATE TABLE IF NOT EXISTS `todolist` (
  `taskid` int(10) unsigned NOT NULL auto_increment,
  `acctid` int(10) unsigned NOT NULL default '0',
  `postdate` datetime NOT NULL default '0000-00-00 00:00:00',
  `title` varchar(50) NOT NULL default '',
  `task` text NOT NULL,
  `importance` enum('insignificante','non urgente','normale','urgente','urgentissima') NOT NULL default 'normale',
  `implementation` int(10) unsigned NOT NULL default '0',
  `finished` datetime NOT NULL default '0000-00-00 00:00:00',
  `status` enum('rifiutata','in sospeso','accettata','chiusa') NOT NULL default 'rifiutata',
  `userinfo` enum('privata','pubblica') NOT NULL default 'privata',
  PRIMARY KEY  (`taskid`),
  KEY `status` (`status`),
  KEY `finished` (`finished`)
) TYPE=MyISAM;

 */

require_once "common.php";
require_once "common2.php";
isnewday(2);

page_header("ToDo List");



addnav("G?Torna alla Grotta","superuser.php");

if ($_GET['op']=="inserttask") {
    if (trim($_POST['title'])!="" && trim($_POST['task'])!="") {
        $sql = "INSERT INTO todolist (acctid,postdate,title,task,importance)
                VALUES (".$session['user']['acctid'].",NOW(),'".$_POST['title']."','".$_POST['task']."',
                '".$_POST['importance']."')";
        db_query($sql);
        $id = db_insert_id(LINK);
        // adminlog();
        redirect("todolist.php?op=viewtask&id=".$id);
    }
    else {
        output("`4`bErrore: Per favore scrivi anche la descrizione oltre al titolo!`b`0`n`n");
        $_GET['op'] = "newtask";
    }
}
elseif ($_GET['op']=="deltask") {
    $sql = "SELECT * FROM commentary WHERE section='todolist-".$_GET['id']."'";
    db_query($sql);
    $sql = "DELETE FROM todolist WHERE taskid=".$_GET['id'];
    db_query($sql);
    $_GET['op'] = "";
}


if ($_GET['op']=="viewtask") {
    addnav("Aggiorna","todolist.php?op=viewtask&id=".$_GET['id']);
    addnav("Torna all'inizio","todolist.php");
    output("`c`bToDo List - Dettaglio dei compiti`b`c`n`n");

    addcommentary(false);
    if ($_POST['edittask']!="") {
        if (trim($_POST['title'])!="" && trim($_POST['task'])!="") {
            $sql = "UPDATE todolist SET title='".$_POST['title']."',task='".$_POST['task']."',
                    importance='".$_POST['importance']."',status='".$_POST['status']."',
                    userinfo='".$_POST['userinfo']."'".($_POST['status']=="chiusa"?",finished=NOW()":",finished=''")." WHERE taskid=".$_GET['id'];
            db_query($sql);
        }
        else {
            output("`4`bErrore: Per favore scrivi anche la descrizione oltre al titolo!`b`0`n`n");
        }
    }
    elseif ($_GET['act']=="taketask") {
        $sql = "UPDATE todolist SET implementation=".$session['user']['acctid']." WHERE taskid=".$_GET['id'];
        db_query($sql);
        redirect("todolist.php?op=viewtask&id=".$_GET['id']);
    }
    elseif ($_GET['act']=="droptask") {
        $sql = "UPDATE todolist SET implementation=0 WHERE taskid=".$_GET['id'];
        db_query($sql);
        redirect("todolist.php?op=viewtask&id=".$_GET['id']);
    }

    $session['todolist'][$_GET['id']] = date("Y-m-d H:i:s");

    $sql = "SELECT t.*, a1.name AS poster, a2.name AS implementor FROM todolist t LEFT JOIN accounts a1 USING(acctid) LEFT JOIN accounts a2 ON a2.acctid=t.implementation WHERE t.taskid=".$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);

    if ($row['implementation']==0) {
        $row['implementor'] = "`inessuno`i [<a href='todolist.php?op=viewtask&act=taketask&id=".$_GET['id']."'>accetta</a>]";
        addnav("","todolist.php?op=viewtask&act=taketask&id=".$_GET['id']);
    }
    else {
        if ($row['implementation']==$session['user']['acctid']) {
            $row['implementor'] .= " [<a href='todolist.php?op=viewtask&act=droptask&id=".$_GET['id']."'>rifiuta</a>]";
            addnav("","todolist.php?op=viewtask&act=droptask&id=".$_GET['id']);
        }
        else {
            $row['implementor'] .= " [<a href='todolist.php?op=viewtask&act=droptask&id=".$_GET['id']."'>togli</a> ";
            addnav("","todolist.php?op=viewtask&act=droptask&id=".$_GET['id']);
            $row['implementor'] .= "| <a href='todolist.php?op=viewtask&act=taketask&id=".$_GET['id']."'>accetta</a>]";
            addnav("","todolist.php?op=viewtask&act=taketask&id=".$_GET['id']);
        }
    }

    if ($row['finished']<=0) $row['finished'] = '---';

    output("<form action='todolist.php?op=viewtask&id=".$_GET['id']."' method='post'>",true);
    addnav("","todolist.php?op=viewtask&id=".$_GET['id']);
    output("<input type='hidden' name='edittask' value='1' />",true);
    $row['task']=html_entity_decode(HTMLEntities2($row['task']));
    $form = array(
            "title"=>"Titolo (max. 50 char)",
            "task"=>"Descrizione,textarea,60,10",
            "postdate"=>"Inserito il,viewonly",
            "poster"=>"Da,viewonly",
            "implementor"=>"Realizzato da,viewonly",
            "importance"=>"Urgenza,enum,insignificante,,non urgente,,normale,,urgente,,urgentissima,",
            "status"=>"Stato,enum,in sospeso,,accettata,,rifiutata,,chiusa,",
            "userinfo"=>"Info,enum,privata,,pubblica,",
            "finished"=>"Ultimata,viewonly"
            );
    showform($form,$row);
    output("</form>",true);
    output("<form action='todolist.php?op=deltask&id=".$_GET['id']."' method='post'>",true);
    addnav("","todolist.php?op=deltask&id=".$_GET['id']);
    output("<input type='submit' class='button' value='Elimina' onClick='return confirm(\"Sei sicuro di voler cancellare il dato?\");' />",true);
    output("</form>",true);

    output("`n`@Commenti:`n");
    viewcommentary("todolist-".$_GET['id'],"commenta",200);
}
elseif ($_GET['op']=="newtask") {
    addnav("Torna all'inizio","todolist.php");
    output("`c`bToDo List - Aggiungi Compito`b`c`n`n");
    output("<form action='todolist.php?op=inserttask' method='post'>",true);
    addnav("","todolist.php?op=inserttask");
    $form = array(
            "title"=>"Titolo (max. 50 char)",
            "task"=>"Descrizione,textarea,60,10",
            "importance"=>"Urgenza,enum,insignificante,,non urgente,,normale,,urgente,,urgentissima,"
            );
    $row = array("title"=>$_POST['title'],"task"=>$_POST['task'],"importance"=>$_POST['importance']);
    showform($form,$row);
    output("</form>",true);
}
else {
    addnav("Aggiorna","todolist.php");
    output("`c`bToDo List - Compiti Attuali`b`c`n`n");
    output("<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
    output("<tr class='trhead'><td><b>Compito</b></td><td><b>Inserita</b></td><td><b>Da</b></td>
            <td><b>In Carico a</b></td><td><b>Commenti</b></td><td><b>Ultimo Commento</b></td>
            <td><b>Priorità</b></td><td><b>Stato</b></td><td><b>Info</b></td></tr>",true);
    $i = 0;
    $sql = "SELECT t.*, a1.name AS poster, a2.name AS implementor,
            IF(c.section IS NULL,0,COUNT(*)) AS commentcount, MAX(c.postdate) AS lastcomment
            FROM todolist t
            LEFT JOIN accounts a1 USING(acctid)
            LEFT JOIN accounts a2 ON a2.acctid=t.implementation
            LEFT JOIN commentary c ON c.section=CONCAT('todolist-',t.taskid)
            GROUP BY t.taskid
            ORDER BY t.status ASC, t.importance DESC, lastcomment DESC, postdate DESC";
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
        if (max($row['postdate'],$row['lastcomment'])>max($session['lastlogoff'],$session['todolist'][$row['taskid']])) {
            output('`4*`0');
        }
        output("<a href='todolist.php?op=viewtask&id=".$row['taskid']."'>",true);
        addnav("","todolist.php?op=viewtask&id=".$row['taskid']);
        output($row['title']);
        output("</a>",true);
        output("</td><td>",true);
        output($row['postdate']);
        output("</td><td>",true);
        output($row['poster']);
        output("</td><td>",true);
        if ($row['implementation']>0) output($row['implementor']);
        else output("---");
        output("</td><td>",true);
        output($row['commentcount']);
        output("</td><td>",true);
        if ($row['lastcomment']>0) output($row['lastcomment']);
        else output("---");
        output("</td><td>",true);
        output($row['importance']);
        output("</td><td>",true);
        output($row['status']);
        output("</td><td>",true);
        output($row['userinfo']);
        output("</td></tr>",true);
        $i++;
    }
    output("</table>",true);
    addnav("Aggiungi Compito","todolist.php?op=newtask");
}

page_footer();
?>