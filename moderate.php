<?php
require_once "common.php";
isnewday(2);

page_header("Moderazione Commenti");
addnav("G?Torna alla Grotta","superuser.php");
addnav("Inizio","moderate.php");
addnav("Ricerca per nome player","moderate.php?op=name");
if ($session['user']['superuser'] > 3) {
    addnav("Commenti Cancellati","moderateadmin.php");
}
if ($_GET['op'] == ""){
   if (isset($_POST['sezione'])){
       $sezione = $_POST['sezione'];
       $ora = $_POST['start'];
       if (strlen($ora) == 1){
           $ora = "0".$ora;
       }
       $ora.=":00:00";
       $giorno = $_POST['giorno'];
       $chiave = $giorno." ".$ora;
       //print "Sezione: ".$sezione." Ora Inizio: ".$ora." Giorno: ".$giorno." Chiave: ".$chiave."<br>";
   }elseif ($_GET['sez']!= ""){
       $sezione=$_GET['sez'];
   }else{
       $sezione="village";
   }
   output("<div align=\"center\"><form method=\"POST\" name=\"section\" action=\"moderate.php\">",true);
   output("<select name=\"start\" class=\"input\">",true);
   output("<option value=\"\" selected>Ora Inizio</option><br>",true);
   for($i=0;$i<24;$i++){
       output(" <option value=\"".$i."\">".$i."</option>",true);
   }
   output("</select>",true);


   output("<select name=\"giorno\" class=\"input\">",true);
   output("<option value=\"\" selected>Data</option><br>",true);
   for($i=19;$i>-1;$i--){
       $data = (date("Y-m-d", strtotime(date("r")."-$i day")));
       output(" <option value=\"".$data."\">".$data."</option>",true);
   }
   output("</select>",true);

   output("<select name=\"sezione\" class=\"input\" OnChange=\"document.section.submit();\">",true);
   output("<option value=\"\" selected>Scegli Sezione</option><br>",true);
   if ($session['user']['superuser'] > 3) {
       $where = "";
   }else{
       //sezioni da non far vedere ai moderatori e admin "normali"
       $where = "WHERE section NOT LIKE 'pet-%'
          AND section NOT LIKE 'house-%'
          AND section NOT LIKE 'superuser'
          AND section NOT LIKE 'todolist-%'
          AND section NOT LIKE 'salariunioni%' ";
   }
   $sql = "SELECT DISTINCT section
          FROM commentary
          ".$where."
          ORDER BY section ASC";
   $result = db_query($sql) or die(sql_error($sql));
   output(" <option value=\"%%\">Tutte le Aree</option>",true);
   $countrow = db_num_rows($result);
   for ($i=0; $i<$countrow; $i++){
   //for($i=0;$i<db_num_rows($result);$i++){
       $row = db_fetch_assoc($result);
       output(" <option value=\"".$row['section']."\">".$row['section']."</option>",true);
   }
   output("</select>",true);

   output("</form></div><br>",true);
   addnav("","moderate.php");
   commenti($sezione,"X",100,$chiave,"");
}elseif ($_GET['op'] == "name"){
   output("`3Inserisci il nome (o parte di esso) del player di cui vuoi cercare i commenti.");
   output("<form action='moderate.php?op=search' method='POST'>Nome da cercare: <input name='name' id='name'>
   <input type='submit' class='button'></form>",true);
   output("<script language='JavaScript'>document.getElementById('name').focus();</script>",true);
   addnav("","moderate.php?op=search");
}elseif ($_GET['op'] == "search"){
    if ($_GET['acctid']=="" AND $_POST['name']!="") {
        $sql = "SELECT acctid FROM accounts WHERE login = '".$_POST['name']."' LIMIT 1";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
            output("`\$`bNessun player trovato`b`0");
        }else{
            $result = db_query($sql) or die(sql_error($sql));
            $row = db_fetch_assoc($result);
        }
    }else{
        $row['acctid'] = $_GET['acctid'];
    }
    commenti('%%',"X",100,$chiave,$row['acctid']);

}



function commenti($section,$message,$limit,$key,$account) {
    global $_POST,$session,$REQUEST_URI,$_GET;
    $nobios = array("motd.php"=>true);
    if ($nobios[basename($_SERVER['SCRIPT_NAME'])]) $linkbios=false; else $linkbios=true;
    //$message = translate($message);
    $com=(int)$_GET[comscroll];
    if ($section != "%%"){
        $section1 = "LIKE '$section'";
    }elseif ($section == "%%" AND $session['user']['superuser']<4){
        $section1 = "NOT LIKE 'salariunioni%'
                     AND section NOT LIKE 'house-%'
                     AND section NOT LIKE 'pet-%'
                     AND section NOT LIKE 'superuser'
                     AND section NOT LIKE 'todolist-%'";
    }else{
        $section1 = "LIKE '%%'";
    }
    $sql = "SELECT commentary.*,
                   accounts.name,
                   accounts.login,
                   accounts.loggedin,
                   accounts.location,
                   accounts.laston
              FROM commentary
             INNER JOIN accounts
                ON accounts.acctid = commentary.author
             WHERE section ".$section1."
               AND accounts.locked=0
               AND postdate > '$key'";
               if ($account != ""){
                  $sql .= " AND acctid = '$account' ";
               }
             $sql .="ORDER BY commentid DESC
             LIMIT ".($com*$limit).",$limit";
    //print "SQL = ".$sql."<br>";
    $result = db_query($sql) or die(db_error(LINK));

    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i < db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $row[comment]=preg_replace("'[`][^123456789v!@#$%^&()V]'","",$row[comment]);
        $commentids[$i] = $row[commentid];
        $x=0;
        $ft="";
        for ($x=0;strlen($ft)<3 && $x<strlen($row[comment]);$x++){
            if (substr($row[comment],$x,1)=="`" && strlen($ft)==0) {
                $x++;
            }else{
                $ft.=substr($row[comment],$x,1);
            }
        }
        $link = "bio.php?char=".rawurlencode($row[login]) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
        if (substr($ft,0,2)=="::") $ft = substr($ft,0,2);
        else
        if (substr($ft,0,1)==":") $ft = substr($ft,0,1);
        if ($ft=="::" || $ft=="/me" || $ft==":"){
            $x = strpos($row[comment],$ft);
            if ($x!==false){
                if ($linkbios)
                $op[$i] = str_replace("&amp;","&",HTMLEntities2(substr($row[comment],0,$x)))
                ."`0<a href='$link' style='text-decoration: none'>\n`&$row[name]`0</a>\n`& "
                .str_replace("&amp;","&",HTMLEntities2(substr($row[comment],$x+strlen($ft))))
                ."`0`n";
                else
                $op[$i] = str_replace("&amp;","&",HTMLEntities2(substr($row[comment],0,$x)))
                ."`0\n`&$row[name]`0\n`& "
                .str_replace("&amp;","&",HTMLEntities2(substr($row[comment],$x+strlen($ft))))
                ."`0`n";
            }
        }
        if ($op[$i]=="")
        if ($linkbios)
        $op[$i] = "`0<a href='$link' style='text-decoration: none'>`&$row[name]`0</a>`3 dice, \"`#"
        .str_replace("&amp;","&",HTMLEntities2($row[comment]))."`3\"`0`n";
        else
        $op[$i] = "`0`&$row[name]`0`3 dice, \"`#"
        .str_replace("&amp;","&",HTMLEntities2($row[comment]))."`3\"`0`n";
        if ($message=="X") $op[$i]="`@".date("H:i",strtotime($row[postdate]))." `2".date("d/m",strtotime($row[postdate]))." `0($row[section]) ".$op[$i];
        //if ($row['postdate']>=$session['user']['recentcomments']) $op[$i]="<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ".$op[$i];
        // Le due righe successive servono a visualizzare di fianco ai commenti se il player che li ha postati è online
        $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin] && $row[location]==0);
        if ($row['postdate']>=$session['user']['recentcomments']) $op[$i]=($loggedin?"<img src='images/new-online.gif' alt='Online' width='3' height='5' align='absmiddle'>":"<img src='images/new.gif' alt='Offline' width='3' height='5' align='absmiddle'> ").$op[$i];
        addnav("",$link);
    }
    $i--;
    $outputcomments=array();
    $sect="x";
    for (;$i>=0;$i--){
        $out="";
        if ($session[user][superuser]>=2 && $message=="X"){
            if (!strpos(URLEncode($_SERVER['REQUEST_URI']),"sez%3D") AND !strpos(URLEncode($_SERVER['REQUEST_URI']),"search")) {
               $out.="`0[ <a href='superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."?sez=".$section."'>Del</a> ]&nbsp;";
               addnav("","superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."?sez=".$section);
            }elseif (strpos(URLEncode($_SERVER['REQUEST_URI']),"search") AND !strpos(URLEncode($_SERVER['REQUEST_URI']),"%26acctid")) {
               $out.="`0[ <a href='superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."%26acctid=".$account."'>Del</a> ]&nbsp;";
               addnav("","superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."%26acctid=".$account);
            }else{
               $out.="`0[ <a href='superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;";
               addnav("","superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI']));
            }
            $matches=array();
            preg_match("/[(][^)]*[)]/",$op[$i],$matches);
            $sect=$matches[0];
        }
        $out.=$op[$i];
        if (!is_array($outputcomments[$sect])) $outputcomments[$sect]=array();
        array_push($outputcomments[$sect],$out);
    }
    ksort($outputcomments);
    reset($outputcomments);
    while (list($sec,$v)=each($outputcomments)){
        if ($sec!="x") output("`n`b$sec`b`n");
        reset($v);
        while (list($key,$val)=each($v)){
            output($val,true);
        }
    }
    if (db_num_rows($result)>=$limit){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]-])*'","",$REQUEST_URI)."&comscroll=".($com+1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        if (!strpos($req,$section)) $req.="&sez=".$section;
        output("<a href=\"$req\">&lt;&lt; Precedente</a>",true);
        addnav("",$req);
    }
    $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=0";
    $req = str_replace("?&","?",$req);
    if (!strpos($req,"?")) $req = str_replace("&","?",$req);
    if (!strpos($req,$section)) $req.="&sez=".$section;
    output("&nbsp;<a href=\"$req\">Aggiorna</a>&nbsp;",true);
    addnav("",$req);
    if ($com>0){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=".($com-1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        if (!strpos($req,$section)) $req.="&sez=".$section;
        output(" <a href=\"$req\">Prossima &gt;&gt;</a>",true);
        addnav("",$req);
    }
    db_free_result($result);
}
page_footer();
?>