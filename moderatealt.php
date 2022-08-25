<?php
require_once "common.php";
isnewday(1);
page_header("Moderazione Commenti");
output("<script type='text/javascript'>
function selectAll(x) {
for(var i=0,l=x.form.length; i<l; i++)
if(x.form[i].type == 'checkbox' && x.form[i].name != 'sAll')
x.form[i].checked=x.form[i].checked?false:true
}
</script>",true);
if ($session['user']['superuser'] > 1){
    addnav("G?Torna alla Grotta","superuser.php");
}else{
    addnav("V?Torna al Villaggio","village.php");
}
addnav("Inizio","moderatealt.php");
addnav("Ricerca per nome player","moderatealt.php?op=name");
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
   output("<div align=\"center\"><form method=\"POST\" name=\"section\" action=\"moderatealt.php\">",true);
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
   }elseif ($session['user']['superuser'] == 1) {
       //sezioni da non far vedere ai moderatori NPG"
       $where = "WHERE section NOT LIKE 'pet-%'
          AND section NOT LIKE 'house-%'
          AND section NOT LIKE 'superuser'
          AND section NOT LIKE 'todolist-%'
          AND section NOT LIKE 'salariunioni%' ";
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
   addnav("","moderatealt.php");
   commenti($sezione,"X",300,$chiave,"");
}elseif ($_GET['op'] == "name"){
   output("`3Inserisci il nome (o parte di esso) del player di cui vuoi cercare i commenti.");
   output("<form action='moderatealt.php?op=search' method='POST'>Nome da cercare: <input name='name' id='name'>
   <input type='submit' class='button'></form>",true);
   output("<script language='JavaScript'>document.getElementById('name').focus();</script>",true);
   addnav("","moderatealt.php?op=search");
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

}elseif ($_GET['op'] == "delete"){
    for ($i=0; $i < sizeof($_POST['canc']); $i++){
        //output($_POST['canc'][$i]);
        $sqlcanc = "SELECT * FROM commentary WHERE commentid = ".$_POST['canc'][$i];
        $resultcanc = db_query($sqlcanc);
        $rowcanc = db_fetch_assoc($resultcanc);
        $sqlcanc1 = "INSERT INTO commentdeleted VALUES
               ('',
               '".$rowcanc['section']."',
               '".$rowcanc['author']."',
               '".addslashes($rowcanc['comment'])."',
               '".$session['user']['acctid']."',
               '".$rowcanc['postdate']."')";
        db_query($sqlcanc1) or die(db_error(LINK));;
        $sqlcanc = "DELETE FROM commentary WHERE commentid = ".$_POST['canc'][$i];
        $resultcanc = db_query($sqlcanc);
    }
    //Il redirect serve per tornare alla sezione da cui si proviene
    redirect("moderatealt.php?sez=".$_GET['sez']);
}

function commenti($section,$message,$limit,$key,$account) {
    global $_POST,$session,$REQUEST_URI,$_GET,$_GET;
    $nobios = array("motd.php"=>true);
    if ($nobios[basename($_SERVER['SCRIPT_NAME'])]) $linkbios=false; else $linkbios=true;
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
    $valore = array();
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i < db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        $row['comment']=preg_replace("'[`][^123456789v!@#$%^&()V]'","",$row['comment']);
        $x=0;
        $ft="";
        for ($x=0;strlen($ft)<3 && $x<strlen($row['comment']);$x++){
            if (substr($row['comment'],$x,1)=="`" && strlen($ft)==0) {
                $x++;
            }else{
                $ft.=substr($row['comment'],$x,1);
            }
        }
        if (substr($ft,0,2)=="::") $ft = substr($ft,0,2);
        else
        if (substr($ft,0,1)==":") $ft = substr($ft,0,1);
        if ($ft=="::" || $ft=="/me" || $ft==":"){
            $x = strpos($row['comment'],$ft);
            if ($x!==false){
                $op[$i] = str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],0,$x)))
                ."`0\n`&".$row['name']."`0\n`& "
                .str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],$x+strlen($ft))))
                ."`0`n";
                $row['comment'] = str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],0,$x)))
                ."`0\n`&".$row['name']."`0\n`& "
                .str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],$x+strlen($ft))))
                ."`0";
            }
        }
        if ($op[$i]==""){
        $row['comment'] = "`0`&".$row['name']."`0`3 dice, \"`#"
        .str_replace("&amp;","&",HTMLEntities2($row['comment']))."`3\"`0";
        }
        $valore['text'][$i] = "`& ".$row['commentid']."`@ ".date("H:i",strtotime($row['postdate']))." `2".date("d/m",strtotime($row['postdate']));
        $valore['text'][$i].= " `0(".$row['section'].") ";
        $valore['text'][$i].=$row['comment'];
        $valore['index'][$i]=$row['commentid'];
    }
    $i--;
    output("<form method='POST' action='moderatealt.php?op=delete&sez=".$section."'>",true);
    for (;$i>=0;$i--){
        output("<input type='checkbox' name='canc[]' value='".$valore['index'][$i]."'>".$valore['text'][$i]."`n",true);
    }
    addnav("","moderatealt.php?op=delete&sez=".$section);
    output("<br><input type='checkbox' name='sAll' onclick='selectAll(this)' /> `@<b>Seleziona tutti</b>",true);
    output("`n`n<input type='submit' class='button' value='Cancella Selezionati'></form>`n`n",true);

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