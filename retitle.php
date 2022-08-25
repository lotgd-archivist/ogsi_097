<?php
require_once "common.php";
isnewday(3);

page_header("Retitler");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Rebuild TUTTI i titoli","retitle.php?op=rebuild");
if ($_GET['op']=="rebuild"){

    //output("<pre>".HTMLEntities2($titles)."</pre>",true);
    //output("<pre>".HTMLEntities2(output_array($titles))."</pre>",true);

    $sql = "SELECT name,title,dragonkills,acctid,sex,ctitle FROM accounts WHERE dragonkills>0";
    $result = db_query($sql);
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        //if ($i==0) echo "x".nl2br(output_array($titles));
        $newtitle = $titles[(int)$row['dragonkills']][(int)$row['sex']];
        if ($row['ctitle'] == "") {
            $oname = $row['name'];
            if ($row['title']!=""){
                $n = $row['name'];
                $x = strpos($n,$row['title']);
                if ($x!==false){
                    $regname=substr($n,$x+strlen($row['title']));
                    $row['name'] = substr($n,0,$x).$newtitle.$regname;
                }else{
                    $row['name'] = $newtitle." ".$row['name'];
                }
            }else{
                $row[name] = $newtitle." ".$row['name'];
            }
        }
        output("`@Changing `^$oname`@ to `^".$row['name']." `@($newtitle-".$row['dragonkills']."[".$row['sex']."](".$row['ctitle']."`@))`n");
        if ($session['user']['acctid']==$row['acctid']){
            $session['user']['title']=$newtitle;
            $session['user']['name']=$row['name'];
        }else{
            $sql = "UPDATE accounts SET name='".addslashes($row['name'])."', title='".addslashes($newtitle)."' WHERE acctid='".$row['acctid']."'";
            //output("`0$sql`n");
            (db_query($sql));
        }
    }
}else{
    output("Questo script ti consente di ricostruire i titoli quando sono stati cambiati nello script del Drago.");
}
page_footer();
?>