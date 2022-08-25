<?php
require_once "common.php";
isnewday(3);
page_header("Editor Caserma");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiungi un soldato","casermaeditor.php?op=add");

if ($_GET['op']==""){
        //gestione pagine, Sook
        $ppp=200; // Linee da mostrare per pagina
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("Pagina Precedente","casermaeditor.php?limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT * FROM caserma ORDER BY grado DESC,strategia+attacco+difesa DESC, acctid LIMIT $limit";
        $result = db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","casermaeditor.php?limit=".($page+1)."");
        //fine gestione pagine

        output("<table>",true);
        output("<tr><td align='center'>Ops</td><td align='center'>ID Caserma</td><td align='center'>ACCTID (Nome)</td><td align='center'>`&Grado</td>
            <td align='center'>`@Totale Militare</td><td align='center'>`^Strategia</td><td align='center'>`\$Attacco</td><td align='center'>`%Difesa</td>
            <td align='center'>`#Scuola</td><td align='center'>`!Corso</td><td align='center'>`!Durata Corso</td><td align='center'>`0Lezione Privata</td>
            <td align='center'>Attacco Eythgim</td></tr>",true);

        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $sql2 = "SELECT acctid,name FROM accounts WHERE acctid=".$row['acctid']." ORDER BY login DESC";
                $result2 = db_query($sql2) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                $totale_militare = $row['strategia'] + $row['attacco'] + $row['difesa'];
                output("<tr>",true);
                output("<td align='center'>[<a href='casermaeditor.php?op=edit&id={$row['id']}'>Edit</a>]",true);
                addnav("","casermaeditor.php?op=edit&id={$row['id']}");
                output("<td align='center'>{$row['id']}</td>",true);
                output("<td>{$row['acctid']} (".$row2['name']."`0)</td>",true);
                output("<td align='center'>`&{$row['grado']}</td>",true);
                output("<td align='right'>`@{$totale_militare}</td>",true);
                output("<td align='right'>`^{$row['strategia']}</td>",true);
                output("<td align='right'>`\${$row['attacco']}</td>",true);
                output("<td align='right'>`%{$row['difesa']}</td>",true);
                output("<td align='center'>`#{$row['scuola']}</td>",true);
                output("<td align='center'>`!{$row['corso']}</td>",true);
                output("<td align='center'>`!{$row['durata_corso']}</td>",true);
                output("<td align='center'>`0{$row['lezione_privata']}</td>",true);
                output("<td align='center'>{$row['attacco_eythgim']}</td>",true);
                output("</tr>",true);
        }
        output("</table>",true);
        addnav("Aggiorna Pagina","casermaeditor.php");
}elseif ($_GET['op']=="add"){
        output("`#Regole sul funzionamento della caserma:`n");
        output("`3Per l'avanzamento a comandante conta la somma di `^(strategia + attacco + difesa)`3.`n");
        output("Strategia, attacco e difesa sono numerici con 3 cifre decimali.`n`n");
        output("Il corso è quello attualmente seguito e la durata il numero di giorni rimanenti per completarlo.`n`n");
        output("Grado: `^Generale, Colonnello, Maggiore, Capitano, Tenente, Sergente, Soldato`3.`n`n");
        output("Lezione privata `^(Si/No)`3: indica se è già stata presa nel giorno in corso.`n");
        output("Attacco a Eythgim `^(Si/No)`3: indica se il giocatore è impegnato in battaglia.`n`n");
        addnav("Home Editor Caserma","casermaeditor.php");
        crearecord(array());
}elseif ($_GET['op']=="edit"){
        output("`#Regole sul funzionamento della caserma:`n");
        output("`3Per l'avanzamento a comandante conta la somma di `^(strategia + attacco + difesa)`3.`n");
        output("Strategia, attacco e difesa sono numerici con 3 cifre decimali.`n`n");
        output("Grado: `^Generale, Colonnello, Maggiore, Capitano, Tenente, Sergente, Soldato`3.`n`n");
        output("Il corso è quello attualmente seguito e la durata il numero di giorni rimanenti per completarlo.`n`n");
        output("Lezione privata `^(Si/No)`3: indica se è già stata presa nel giorno in corso.`n");
        output("Attacco a Eythgim `^(Si/No)`3: indica se il giocatore è impegnato in battaglia.`n`n");
        addnav("Home Editor Caserma","casermaeditor.php");
        $sql = "SELECT * FROM caserma WHERE id='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`iRecord non trovato.`i");
        }else{
                output("Editor Caserma:`n");
                $row = db_fetch_assoc($result);
                crearecord($row);
        }
}elseif ($_GET['op']=="save"){
        if (is_array($_POST)){
            reset($_POST);
            reset($_POST['mount']);
        }
        $keys='';
        $vals='';
        $sql='';
        $i=0;
        while (list($key,$val)=each($_POST['mount'])){
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
                $sql="UPDATE caserma SET $sql WHERE id='{$_GET['id']}'";
        }else{
                $sql="INSERT INTO caserma ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
                output("Record salvato!");
        }else{
                output("Record non salvato: $sql");
        }
        addnav("Home Editor Caserma","casermaeditor.php");
}

function crearecord($mount){
        global $output;
        output("<form action='casermaeditor.php?op=save&id={$mount['id']}' method='POST'>",true);
        addnav("","casermaeditor.php?op=save&id={$mount['id']}");
        $output.="<table>";
        $output.="<tr><td>ACCTID:</td><td><input name='mount[acctid]' value=\"".HTMLEntities2($mount['acctid'])."\"></td></tr>";
        $output.="<tr><td>Grado:</td><td><input name='mount[grado]' value=\"".HTMLEntities2($mount['grado'])."\"></td></tr>";
        $output.="<tr><td>Scuola:</td><td><input name='mount[scuola]' value=\"".HTMLEntities2($mount['scuola'])."\"></td></tr>";
        $output.="<tr><td>Strategia:</td><td><input name='mount[strategia]' value=\"".HTMLEntities2($mount['strategia'])."\"></td></tr>";
        $output.="<tr><td>Attacco:</td><td><input name='mount[attacco]' value=\"".HTMLEntities2($mount['attacco'])."\"></td></tr>";
        $output.="<tr><td>Difesa:</td><td><input name='mount[difesa]' value=\"".HTMLEntities2($mount['difesa'])."\"></td></tr>";
        $output.="<tr><td>Corso:</td><td><input name='mount[corso]' value=\"".HTMLEntities2((int)$mount['corso'])."\"></td></tr>";
        $output.="<tr><td>Durata Corso:</td><td><input name='mount[durata_corso]' value=\"".HTMLEntities2((int)$mount['durata_corso'])."\"></td></tr>";
        $output.="<tr><td>Lezione Privata:</td><td><input name='mount[lezione_privata]' value=\"".HTMLEntities2($mount['lezione_privata'])."\"></td></tr>";
        $output.="<tr><td>Attacco Eythgim:</td><td><input name='mount[attacco_eythgim]' value=\"".HTMLEntities2($mount['attacco_eythgim'])."\"></td></tr>";
        $output.="</td></tr>";
        $output.="</table>";
        $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>