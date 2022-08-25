<?php
require_once "common.php";
addcommentary();
session_write_close();
popup_header("Messaggio del Giorno di LoGD (MoTD)");
output(($session['user']['superuser']>=2?" [<a href='motd.php?op=add'>Aggiungi MoTD</a>":""),true);
output(($session['user']['superuser']>=3?"|<a href='motd.php?op=addpoll'>Aggiungi Sondaggio</a>":""),true);
output(($session['user']['superuser']>=2?"]`n":""),true);
function motditem($subject,$body){
    output("`b$subject`b`n",true);
    output("$body");
    output("<hr>",true);
}
function pollitem($id,$subject,$body){
    global $session;
    $sql = "SELECT count(resultid) AS c, MAX(choice) AS choice FROM pollresults WHERE motditem='$id' AND account='{$session['user']['acctid']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $choice = $row['choice'];
    $body = unserialize($body);
    if ($row['c']==0 && 0){
        output("<form action='motd.php?op=vote' method='POST'>",true);
        output("<input type='hidden' name='motditem' value='$id'>",true);
        output("`bSondaggio: $subject`b`n",true);
        output(stripslashes($body['body']));
        while (list($key,$val)=each($body['opt'])){
            if (trim($val)!=""){
                output("`n<input type='radio' name='choice' value='$key'>",true);
                output(stripslashes($val));
            }
        }
        output("`n<input type='submit' class='button' value='Vota'>",true);
        output("</form>",true);
    }else{
        output("<form action='motd.php?op=vote' method='POST'>",true);
        output("<input type='hidden' name='motditem' value='$id'>",true);
        output("`bSondaggio: $subject`b`n",true);
        output(stripslashes($body['body']));
        $sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";
        $result = db_query($sql);
        $choices=array();
        $totalanswers=0;
        $maxitem = 0;
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $choices[$row['choice']]=$row['c'];
            $totalanswers+=$row['c'];
            if ($row['c']>$maxitem) $maxitem = $row['c'];
        }
        while (list($key,$val)=each($body['opt'])){
            if (trim($val)!=""){
                if ($totalanswers<=0) $totalanswers=1;
                $percent = round($choices[$key] / $totalanswers * 100,1);
                output("`n<input type='radio' name='choice' value='$key'".($choice==$key?" controllato":"").">",true);
                output(stripslashes($val)." (".(int)$choices[$key]." - $percent%)");
                if ($maxitem==0){ $width=1; } else { $width = round(($choices[$key]/$maxitem) * 400,0); }
                $width = max($width,1);
                output("`n<img src='images/rule.gif' width='$width' height='2' alt='$percent'>",true);
                //output(stripslashes($val)."`n");
            }
        }
        output("`n<input type='submit' class='button' value='Vota'></form>",true);
    }
    output("<hr>",true);
}
if ($_GET['op']=="vote"){
    $sql = "DELETE FROM pollresults WHERE motditem='{$_POST['motditem']}' AND account='{$session['user']['acctid']}'";
    db_query($sql);
    $sql = "INSERT INTO pollresults (choice,account,motditem) VALUES ('{$_POST['choice']}','{$session['user']['acctid']}','{$_POST['motditem']}')";
    db_query($sql);
    header("Location: motd.php");
    exit();
}
if ($_GET['op']=="addpoll"){
    if($session['user']['superuser']>=3){
        if ($_POST['subject']=="" || $_POST['body']==""){
            output("<form action='motd.php?op=addpoll' method='POST'>",true);
            addnav("","motd.php?op=add");
            output("<input type='text' size='50' name='subject' value=\"".HTMLEntities2(stripslashes($_POST['subject']))."\">`n",true);
            output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities2(stripslashes($_POST['body']))."</textarea>`n",true);
            output("Opt <input name='opt[]'>`n",true);
            output("Opt <input name='opt[]'>`n",true);
            output("Opt <input name='opt[]'>`n",true);
            output("Opt <input name='opt[]'>`n",true);
            output("Opt <input name='opt[]'>`n",true);
            output("Opt <input name='opt[]'>`n",true);
            output("<input type='submit' class='button' value='Add'></form>",true);
        }else{
            $body = array("body"=>$_POST['body'],"opt"=>$_POST['opt']);
            $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdtype) VALUES (\"$_POST[subject]\",\"".addslashes(serialize($body))."\",now(),1)";
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    }else{
        if ($session['user']['loggedin']){
            //$session[user][hitpoints]=0;
            //$session[user][alive]=0;
            $session['user']['experience']=round($session['user']['experience']*0.9,0);
            addnews($session['user']['name']." è stato punito per aver tentato di ingannare gli dei.");
            output("Hai tentato di ingannare gli dei. Vieni colpito da una bacchetta della dimenticanza. Qualcosa di ciò che sapevi, non la sai più.");
            saveuser();
        }
    }
}
if ($_GET[op]=="add"){
    if ($session['user']['superuser']>=2){
        if ($_POST['subject']=="" || $_POST['body']==""){
            output("<form action='motd.php?op=add' method='POST'>",true);
            addnav("","motd.php?op=add");
            output("<input type='text' size='50' name='subject' value=\"".HTMLEntities2(stripslashes($_POST[subject]))."\">`n",true);
            output("<textarea class='input' name='body' cols='37' rows='5'>".HTMLEntities2(stripslashes($_POST[body]))."</textarea>`n",true);
            output("<input type='submit' class='button' value='Add'></form>",true);
        }else{
            $_POST['subject'] = "`#".date("Y-m-d H:i")." - ".$_POST['subject'];
            $_POST['body'] = $_POST['body']."`n`n".addslashes($session['user']['name']);
            $sql = "INSERT INTO motd (motdtitle,motdbody,motddate) VALUES (\"$_POST[subject]\",\"$_POST[body]\",now())";
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    }else{
        if ($session['user']['loggedin']){
            //$session[user][hitpoints]=0;
            //$session[user][alive]=0;
            $session['user']['experience']=round($session['user']['experience']*0.9,0);
            addnews($session['user']['name']." è stato punito per aver tentato di ingannare gli dei.");
            output("Hai tentato di ingannare gli dei. Vieni colpito da una bacchetta della dimenticanza. Qualcosa di ciò che sapevi, non la sai più.");
            saveuser();
        }
    }
}
if ($_GET['op']=="del"){
    if ($session['user']['superuser']>=3){
            $sql = "DELETE FROM motd WHERE motditem=\"$_GET[id]\"";
            db_query($sql);
            header("Location: motd.php");
            exit();
    }else{
        if ($session['user']['loggedin']){
            //$session[user][hitpoints]=0;
            //$session[user][alive]=0;
            $session['user']['experience']=round($session['user']['experience']*0.9,0);
            addnews($session['user']['name']." è stato punito per aver tentato di ingannare gli dei.");
            output("Hai tentato di ingannare gli dei. Vieni colpito da una bacchetta della dimenticanza. Qualcosa di ciò che sapevi, non la sai più.");
            saveuser();
        }
    }
}


if ($_GET['op']==""){
    output("`&");
    motditem("Beta!","Per favore leggete il messaggio di seguito.");
    output("`%");

    $sql = "SELECT * FROM motd ORDER BY motddate DESC limit 20";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($row['motddate']>$session['user']['lastmotd'] || $i<15){
            if ($row['motdtype']==0){
                motditem($row['motdtitle'].($session['user']['superuser']>=3?"[<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return conferma('Sei sicuro di voler cancellare questo item?');\">Del</a>]":""),$row['motdbody']);
            }else{
                pollitem($row['motditem'],$row['motdtitle'].($session['user']['superuser']>=3?"[<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return conferma('Sei sicuro di voler cancellare questo item?');\">Del</a>]":""),$row['motdbody']);
            }
        }
    }
    output("`&");
    motditem("Beta!","Per quelli che ancora non l'hanno capito, questo sito web è tuttora in fase beta.  Ci lavoro su quando trovo il tempo, che generalmente vuol dire un paio di volte a settimana.  Lasciate commenti, suggerimenti, e vi consiglio di leggere le `^`bMiniFAQ !!`b`0");
    output("`@Commenti:`0`n");
    viewcommentary("motd","",10,5);
}

$session['needtoviewmotd']=false;

    $sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $session['user']['lastmotd']=$row['motddate'];

popup_footer();
?>