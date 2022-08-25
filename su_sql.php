<?php
// dbrong
require_once("common.php");
page_header("Lettura Personalizzata dei DB SQL");
output("`c`bScrivi la tua query SELECT`b`c`n");
addnav("Aggiorna query SQL","su_sql.php");
addnav("G?Grotta Superutente","superuser.php");
addnav("Trump Tower","trumptower.php");
addnav("","su_sql.php?op=lookup");

$_POST[sql]=str_replace("\'","'",$_POST[sql]);

    output("<form method=post action=su_sql.php?op=lookup>",true);
    output("SELECT <input type=text name=items size=35 maxsize=250 value=\"$_POST[items]\"> (no spaces)`n",true);
    output("FROM <input type=text name=from size=35 maxsize=250 value=\"$_POST[from]\">`n",true);
    output("WHERE <input type=text name=where size=35 maxsize=250 value=\"$_POST[where]\">`n",true);
    output("ORDER BY <input type=text name=orderby size=35 maxsize=250 value=\"$_POST[orderby]\">`n",true);
    //output("`nOR SQL = <input type=text name=sql size=60 maxsize=250 value=\"$_POST[sql]\">`n",true);
    output("`n<input type=submit value=Esegui>",true);
    output("</form>",true);

if ($_GET[op]=='lookup') {
    if ($_POST[sql]=='') {
        $sql = "SELECT $_POST[items] FROM $_POST[from]";
        if ($_POST[where]!='') $sql.= " WHERE ".$_POST[where];
        if ($_POST[orderby]!='') $sql.=" ORDER BY ".$_POST[orderby];
        output("`n$sql`n`n");
        $loop = explode(",", $_POST[items]);
        $result = db_query($sql) or die(db_error(LINK));
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            foreach ($loop as $l){
                output($l."`0 = ".$row["$l"]."`0 - ");
            }
        output("`n");
        }
    } else {
        output("`nRunning custom SQL (updates or deletes)");
        output("`nSQL: ".$_POST[sql]."`n");
        $result = db_query($_POST[sql]) or die(db_error(LINK));
    }
}
page_footer();
?>