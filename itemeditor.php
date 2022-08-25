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

page_header("Item Editor");
addnav("G?Back to Grotte","superuser.php");
addnav("M?Return to the Mundane","village.php");

if ($_GET['op']=="del"){
        $sql = "DELETE FROM items WHERE id=$_GET[id]";
        db_query($sql);
        $_GET['op']="";
        $_GET['show']=$_GET['show']; // huh? weshalb hab ich das geschrieben?
}

if ($_GET['op']=="add"){
        output("Add Item:`n");
        addnav("Item Editor","itemeditor.php");
        if ($_GET[show]) addnav("$_GET[show]","itemeditor.php?show=".urlencode($_GET[show])."");
        itemform(array("class"=>$_GET[show]));
}elseif ($_GET['op']=="edit"){
        addnav("Item Editor","itemeditor.php");
        if ($_GET[show]) addnav("$_GET[show]","itemeditor.php?show=".urlencode($_GET[show])."");
        $sql = "SELECT * FROM items WHERE id='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`iItem doesn't exist.`i");
        }else{
                output("Item Editor:`n");
                $row = db_fetch_assoc($result);
                $row['buff']=unserialize($row['buff']);
                itemform($row);
        }
}elseif ($_GET['op']=="save"){
        $buff = array();
        reset($_POST['item']['buff']);
        if (isset($_POST['item']['buff']['activate'])) $_POST['item']['buff']['activate']=join(",",$_POST['item']['buff']['activate']);
        while (list($key,$val)=each($_POST['item']['buff'])){
                if ($val>""){
                        $buff[$key]=stripslashes($val);
                }
        }
        $_POST['item']['buff']=$buff;
        reset($_POST['item']);
        $keys='';
        $vals='';
        $sql='';
        $i=0;
        while (list($key,$val)=each($_POST['item'])){
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
                $sql="UPDATE items SET $sql WHERE id='{$_GET['id']}'";
        }else{
                $sql="INSERT INTO items ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
                output("Item saved!");
        }else{
                output("Item not saved! Error: $sql");
        }
        addnav("Item Editor","itemeditor.php");
        if ($_GET[show]) addnav("$_GET[show]","itemeditor.php?show=".urlencode($_GET[show])."");
}else{
        if($_GET['show']){
                $ppp=50; // Player Per Page to display
                if (!$_GET[limit]){
                        $page=0;
                }else{
                        $page=(int)$_GET[limit];
                        addnav("Previous Page","itemeditor.php?show=".urlencode($_GET[show])."&limit=".($page-1)."");
                }
                $limit="".($page*$ppp).",".($ppp+1);
                $sql = "SELECT items.*,accounts.name AS ownername FROM items
                LEFT JOIN accounts ON accounts.acctid=items.owner WHERE class='$_GET[show]' ORDER BY id LIMIT $limit";
                output("<table>",true);
                output("<tr><td>Ops</td><td>Name</td><td>Owner</td><td>Description</td></tr>",true);
                $result = db_query($sql);
                if (db_num_rows($result)>$ppp) addnav("Next Page","itemeditor.php?show=".urlencode($_GET[show])."&limit=".($page+1)."");
                $cat = "";
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                //for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        output("<tr>",true);
                        output("<td>[ <a href='itemeditor.php?op=edit&id=$row[id]&show=".urlencode($row['class'])."'>Edit</a> |",true);
                        addnav("","itemeditor.php?op=edit&id=$row[id]&show=".urlencode($row['class'])."");
                        output(" <a href='itemeditor.php?op=del&id=$row[id]&show=".urlencode($row['class'])."' onClick=\"return confirm('Delete this item?');\">Delete</a> ]</td>",true);
                        addnav("","itemeditor.php?op=del&id=$row[id]&show=".urlencode($row['class'])."");
                        output("<td>$row[name]</td>",true);
                        output("<td>$row[ownername]</td>",true);
                        output("<td>$row[description]</td>",true);
                        output("</tr>",true);
                }
                output("</table>",true);
                addnav("Item Editor","itemeditor.php");
                addnav("Add Item","itemeditor.php?op=add&show=".urlencode($_GET[show])."");
        }else{
                $sql = "SELECT class FROM items ORDER BY class";
                output("Available \"classes\":`n`n<table>",true);
                output("<tr><td>Name</td></tr>",true);
                $result = db_query($sql);
                $cat = "";
                $countrow = db_num_rows($result);
                for ($i=0; $i<$countrow; $i++){
                //for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        if ($cat!=$row['class']){
                                output("<tr><td><a href='itemeditor.php?show=".urlencode($row['class'])."'>$row[class]</a></td></tr>",true);
                                $cat = $row['class'];
                                addnav("","itemeditor.php?show=".urlencode($row['class'])."");
                                output("</tr>",true);
                        }
                }
                output("</table>`n`n",true);
                addnav("Add Item","itemeditor.php?op=add");
        }
}

function itemform($item){
        global $output;
        output("<form action='itemeditor.php?op=save&id=$item[id]' method='POST'>",true);
        addnav("","itemeditor.php?op=save&id=$item[id]");
        $output.="<table>";
        $output.="<tr><td>Item Name:</td><td><input name='item[name]' value=\"".HTMLEntities2($item['name'])."\" maxlength='25'></td></tr>";
        $output.="<tr><td>Item Description:</td><td><input name='item[description]' value=\"".HTMLEntities2($item['description'])."\" maxlength='255'></td></tr>";
        $output.="<tr><td>Item Class:</td><td><input name='item[class]' value=\"".HTMLEntities2($item['class'])."\" maxlength='25'></td></tr>";
        $output.="<tr><td>Owner ID:</td><td><input name='item[owner]' value=\"".HTMLEntities2((int)$item['owner'])."\" size='5'></td></tr>";
        $output.="<tr><td>Item Cost (Gems):</td><td><input name='item[gems]' value=\"".HTMLEntities2((int)$item['gems'])."\" size='5'></td></tr>";
        $output.="<tr><td>Item Cost (Gold):</td><td><input name='item[gold]' value=\"".HTMLEntities2((int)$item['gold'])."\" size='5'></td></tr>";
        $output.="<tr><td>Item Value1:</td><td><input name='item[value1]' value=\"".HTMLEntities2((int)$item['value1'])."\" size='5'></td></tr>";
        $output.="<tr><td>Item Value2:</td><td><input name='item[value2]' value=\"".HTMLEntities2((int)$item['value2'])."\" size='5'></td></tr>";
        $output.="<tr><td>Hidden Value (HValue):</td><td><input name='item[hvalue]' value=\"".HTMLEntities2((int)$item['hvalue'])."\" size='5'></td></tr>";
        $output.="<tr><td valign='top'>Item Buff:</td><td>";
        $output.="<b>Messages:</b><Br/>";
        $output.="Buff Name: <input name='item[buff][name]' value=\"".HTMLEntities2($item['buff']['name'])."\"><Br/>";
        //output("Initial Message: <input name='mount[mountbuff][startmsg]' value=\"".HTMLEntities2($mount['mountbuff']['startmsg'])."\">`n",true);
        $output.="Message each round: <input name='item[buff][roundmsg]' value=\"".HTMLEntities2($item['buff']['roundmsg'])."\"><Br/>";
        $output.="Wear off message: <input name='item[buff][wearoff]' value=\"".HTMLEntities2($item['buff']['wearoff'])."\"><Br/>";
        $output.="Effect Message: <input name='item[buff][effectmsg]' value=\"".HTMLEntities2($item['buff']['effectmsg'])."\"><Br/>";
        $output.="Effect No Damage Message: <input name='item[buff][effectnodmgmsg]' value=\"".HTMLEntities2($item['buff']['effectnodmgmsg'])."\"><Br/>";
        $output.="Effect Fail Message: <input name='item[buff][effectfailmsg]' value=\"".HTMLEntities2($item['buff']['effectfailmsg'])."\"><Br/>";
        $output.="<Br/><b>Effects:</b><Br/>";
        $output.="Rounds to last (from activation): <input name='item[buff][rounds]' value=\"".HTMLEntities2($item['buff']['rounds'])."\" size='5'><Br/>";
        $output.="Player Atk mod: <input name='item[buff][atkmod]' value=\"".HTMLEntities2($item['buff']['atkmod'])."\" size='5'><Br/>";
        $output.="Player Def mod: <input name='item[buff][defmod]' value=\"".HTMLEntities2($item['buff']['defmod'])."\" size='5'><Br/>";
        $output.="Regen: <input name='item[buff][regen]' value=\"".HTMLEntities2($item['buff']['regen'])."\"><Br/>";
        $output.="Minion Count: <input name='item[buff][minioncount]' value=\"".HTMLEntities2($item['buff']['minioncount'])."\"><Br/>";
        $output.="Min Badguy Damage: <input name='item[buff][minbadguydamage]' value=\"".HTMLEntities2($item['buff']['minbadguydamage'])."\" size='5'><Br/>";
        $output.="Max Badguy Damage: <input name='item[buff][maxbadguydamage]' value=\"".HTMLEntities2($item['buff']['maxbadguydamage'])."\" size='5'><Br/>";
        $output.="Lifetap: <input name='item[buff][lifetap]' value=\"".HTMLEntities2($item['buff']['lifetap'])."\" size='5'><Br/>";
        $output.="Damage shield: <input name='item[buff][damageshield]' value=\"".HTMLEntities2($item['buff']['damageshield'])."\" size='5'> (multiplier)<Br/>";
        $output.="Protective shield: <input name='item[buff][protectiveshield]' value=\"".HTMLEntities2($item['buff']['protectiveshield'])."\" size='5'> (additive)<Br/>";
        $output.="Badguy Damage mod: <input name='item[buff][badguydmgmod]' value=\"".HTMLEntities2($item['buff']['badguydmgmod'])."\" size='5'> (multiplier)<Br/>";
        $output.="Badguy Atk mod: <input name='item[buff][badguyatkmod]' value=\"".HTMLEntities2($item['buff']['badguyatkmod'])."\" size='5'> (multiplier)<Br/>";
        $output.="Badguy Def mod: <input name='item[buff][badguydefmod]' value=\"".HTMLEntities2($item['buff']['badguydefmod'])."\" size='5'> (multiplier)<Br/>";
        //$output.=": <input name='mount[mountbuff][]' value=\"".HTMLEntities2($mount['mountbuff'][''])."\">`n",true);

        $output.="<Br/><b>Aktiviert bei:</b><Br/>";
        $output.="<input type='checkbox' name='item[buff][activate][]' value=\"roundstart\"".(strpos($item['buff']['activate'],"roundstart")!==false?" checked":"")."> Round Start<Br/>";
        $output.="<input type='checkbox' name='item[buff][activate][]' value=\"offense\"".(strpos($item['buff']['activate'],"offense")!==false?" checked":"")."> On Attack<Br/>";
        $output.="<input type='checkbox' name='item[buff][activate][]' value=\"defense\"".(strpos($item['buff']['activate'],"defense")!==false?" checked":"")."> On Defend<Br/>";
        $output.="<input type='checkbox' name='item[buff][activate][]' value=\"endround\"".(strpos($item['buff']['activate'],"endround")!==false?" checked":"")."> Round End<Br/>";
        $output.="<Br/>";
        $output.="<input type='checkbox' name='item[buff][deactivate][]' value=\"battleend\"".(strpos($item['buff']['deactivate'],"battleend")!==false?" checked":"")."> Buff terminate at the battle's end<Br/>";
        $output.="<Br/>";
        $output.="</td></tr>";
        $output.="</table>";
        $output.="<input type='submit' class='button' value='Save'></form>";
}

page_footer();
?>