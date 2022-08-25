<?php

// 11072004

// Item Editor
// by anpera; based on mount editor
//
// This is for administer items of all kind with anpera's item table
// (first introduced in houses mod)
// items table REQUIRED!
//
// insert:
//         if ($session[user][superuser]>=2) addnav("Item Editor","itemeditor.php");
// into menu of superuser.php
//

require_once "common.php";

page_header("Preferenze Superutenti");

if ($_GET['op']=="save"){
        reset($_POST['item']);
        $keys='';
        $vals='';
        $sql='';
        $i=0;
        while (list($key,$val)=each($_POST['item'])){
                if ($val!=0) $val=1;
                if ($_GET['id']>""){
                        $sql.=($i>0?",":"")."$key='$val'";
                }else{
                        $keys.=($i>0?",":"")."$key";
                        $vals.=($i>0?",":"")."'$val'";
                }
                $i++;
        }
        if ($_GET['id']>""){
                $sql="UPDATE suprefs SET $sql WHERE acctid='{$_GET['id']}'";
        }else{
                $sql="INSERT INTO suprefs ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
                output("Preferenze salvate!`n`n");
        }else{
                output("`\$Preferenze non salvate! Errore: $sql`n`n`0");
        }
}

$sql="SELECT * FROM suprefs WHERE acctid=".$session['user']['acctid']; 
$result = db_query($sql) or die(db_error(LINK));
if(db_num_rows($result)==0) {
	$sql="INSERT INTO suprefs (acctid) VALUES ('".$session['user']['acctid']."')"; 
	$result = db_query($sql) or die(db_error(LINK));
	$sql="SELECT * FROM suprefs WHERE acctid=".$session['user']['acctid']; 
	$result = db_query($sql) or die(db_error(LINK));
}
$row = db_fetch_assoc($result);
 
//Form delle preferenze
output("`n`nSettare a 1 per ricevere gli avvisi in messaggio di sistema, a 0 per disattivarli.`nNon sono escludi i controlli sul livello di superutente.`n`n");
output("<form action='suprefs.php?op=save&id=".$session['user']['acctid']."' method='POST'>",true);
addnav("","suprefs.php?op=save&id=".$session['user']['acctid']);
output("<table>",true);
$campi=array_keys($row);
for($i=1;$i<(count($row));$i++) {
   	output("<tr><td>".$campi[$i].":</td><td><input name='item[".$campi[$i]."]' value=\"".HTMLEntities2($row[$campi[$i]])."\" maxlength='1'></td></tr>",true);
}
output("<Br/></td></tr></table>",true);
output("<input type='submit' class='button' value='Salva'></form>",true);

//Menù
addnav("P?Torna alle Preferenze","prefs.php");
addnav("R?Resetta Pagina","suprefs.php");

page_footer();
?>