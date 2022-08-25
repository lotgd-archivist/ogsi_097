<?php
require_once "common.php";
isnewday(3);

page_header("Controllo Interazioni");
output("<form action='controllointerazioni.php?op=debuglog' method='POST'>
    Ricerca acctid personaggi: <input name='z' id='z'> (se non è vuoto i campi seguenti saranno ignorati)`n
    Acctid primo personaggio: <input name='p' id='p' value='".$_POST['p']."'>`n
    Acctid secondo personaggio: <input name='q' id='q' value='".$_POST['q']."''> (se vuoto verranno visualizzate tutte le interazioni del personaggio selezionato nel campo precedente)`n`n
    <input type='submit' class='button'></form>",true);
output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
addnav("","controllointerazioni.php?op=debuglog");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
//addnav("User Editor Home","user.php");

if ($_GET[op]=="debuglog"){
    if ($_POST['z']!="") {
        $sql = "SELECT acctid FROM accounts WHERE ";
        $where="
        login LIKE '%{$_POST['z']}%' OR
        acctid LIKE '%{$_POST['z']}%' OR
        name LIKE '%{$_POST['z']}%' OR
        emailaddress LIKE '%{$_POST['z']}%' OR
        lastip LIKE '%{$_POST['z']}%' OR
        uniqueid LIKE '%{$_POST['z']}%' OR
        gentimecount LIKE '%{$_POST['z']}%' OR
        level LIKE '%{$_POST['z']}%'";
        $result = db_query($sql.$where);
        if (db_num_rows($result)<=0){
            output("`\$No results found`0");
            $where="";
        }elseif (db_num_rows($result)>100){
            output("`\$Troppi risultati, restringi il campo di ricerca per favore.`0");
            $where="";
        }else{
            $sql = "SELECT acctid,login,name,level,lastip,uniqueid,emailaddress FROM accounts ".($where>""?"WHERE $where ":"");
            $result = db_query($sql) or die(db_error(LINK));
            output("<table>",true);
            output("<tr>
            <td>Acctid</td>
            <td>Login</td>
            <td>Nome</td>
            <td>Livello</td>
            <td>UltimoIP</td>
            <td>UltimoID</td>
            <td>Email</td>
            </tr>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row=db_fetch_assoc($result);
                output("<tr class='".($i%2?"trlight":"trdark")."'>",true);

                output("<td>",true);
                output($row[acctid]);
                output("</td><td>",true);
                output($row[login]);
                output("</td><td>",true);
                output($row[name]);
                output("</td><td>",true);
                output($row[level]);
                output("</td><td>",true);
                output($row[lastip]);
                output("</td><td>",true);
                output($row[uniqueid]);
                output("</td><td>",true);
                output($row[emailaddress]);
                output("</td>",true);
                output("</tr>",true);
            }
            output("</table>",true);
        }
    } elseif ($_POST['p']!="") {
        if ($_POST['q']=="") {
            $sql = "SELECT debuglog.*,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE (debuglog.actor='{$_POST['p']}' AND debuglog.target<>'0') OR (debuglog.target='{$_POST['p']}' AND debuglog.actor<>'0') ORDER by debuglog.date DESC,debuglog.id ASC";
        } else {
            $sql = "SELECT debuglog.*,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE (debuglog.actor='{$_POST['p']}' AND debuglog.target='{$_POST['q']}') OR (debuglog.target='{$_POST['p']}' AND debuglog.actor='{$_POST['q']}') ORDER by debuglog.date DESC,debuglog.id ASC";
        }
        $result = db_query($sql);
        $odate = "";

        // Ricerca interna by Xtramus
                output('
              <form onsubmit="return cerca()">
            Evidenzia<input type="text" id="ricerca">
            <input type="button" value="Vai" onClick="cerca()"><br>
            <input type="radio" name="modo" value="evidenziarosso" checked>Evidenzia in rosso
            <input type="radio" name="modo" value="evidenziagiallo">Evidenzia in giallo
            <input type="radio" name="modo" value="eliminaaltri">Elimina altri
            <input type="radio" name="modo" value="elimina selezionati">Elimina selezionati
            </form>
            <br><br>
                   ',true);
         // Continua dopo
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0; $i<db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $dom = date("D, M d",strtotime($row['date']));
            if ($odate != $dom){
                output("`n`b`@".$dom."`b`n`0");
                $odate = $dom;
            }
            $time = date("H:i:s", strtotime($row['date']));
            output("<div id=\"riga".($i+1)."n\" name=\"riga".($i+1)."n\" style=\"display:inline\">",true);
            output("`@$time`0 - {$row['actorname']} `3 {$row['message']}`0");
            //output("</span>",true);
            if ($row['target']) output(" {$row['targetname']}");
            output("</div>",true);
            output("`n");
        }
        // Ricerca interna
        output('<script type="text/javascript">
        function cerca() {
        parola=document.getElementById("ricerca").value.toLowerCase();
        bagcolor="no";
        modo= document.getElementsByName("modo");
        if(modo[0].checked) {
               bagcolor="#660000";
        } else if(modo[1].checked) {
               bagcolor="#666600";
        } else if(modo[2].checked) {
               eliminare= "altri";
        } else {
               eliminare= "selezionati";
        }
               for(var i=1; i<='.($i).'; i++) {
                    r= "riga"+i+"n";
                    dge= document.getElementById(r);
                    //alert(dge.innerHTML);
                    //break;
                    testo=dge.innerHTML.toLowerCase();
                    if(testo.indexOf(parola)!=-1) {
                         if(bagcolor!="no") {
                              dge.style.backgroundColor=bagcolor;
                              dge.style.display="inline";
                         }
                         else if(eliminare=="altri") {
                              dge.style.backgroundColor="";
                              dge.style.display="inline";
                         }
                         else {
                              dge.style.backgroundColor="";
                              dge.style.display="none";
                         }
                    }
                    else {
                         if(bagcolor!="no") {
                              dge.style.backgroundColor="";
                              dge.style.display="inline";
                         }
                         else if(eliminare=="altri") {
                              dge.style.display="none";
                         }
                         else {
                              dge.style.backgroundColor="";
                              dge.style.display="inline";
                         }
                    }
               }
               return false;
        }
        </script>
        ',true);

        // fine ricerca interna
    }
}
page_footer();
?>