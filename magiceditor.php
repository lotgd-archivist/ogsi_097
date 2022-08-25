<?php
require_once "common.php";
isnewday(3);
page_header("Editor Oggetti Magici");
addnav("G?Torna alla Grotta","superuser.php");
addnav("M?Torna alla Mondanità","village.php");
addnav("Aggiungi un oggetto","magiceditor.php?op=add");

if ($_GET['op']==""){
        //gestione pagine, Sook
        $ppp=200; // Linee da mostrare per pagina
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("Pagina Precedente","magiceditor.php?limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT * FROM oggetti ORDER BY livello, valore, nome LIMIT $limit";
        $result = db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("Pagina Successiva","magiceditor.php?limit=".($page+1)."");
        //fine gestione pagine

        output("<table>",true);
        output("<tr><td>Ops</td><td>ID</td><td>Nome</td><td>Costo</td><td>Livello</td><td>Dove</td><td>Dove Ori</td><td>Descrizione</td></tr>",true);

        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<tr>",true);
                output("<td>[<a href='magiceditor.php?op=edit&id={$row['id_oggetti']}'>Edit</a>]",true);
                addnav("","magiceditor.php?op=edit&id={$row['id_oggetti']}");
                output("<td>{$row['id_oggetti']}</td>",true);
                output("<td>{$row['nome']}</td>",true);
                output("<td>{$row['valore']} gemme</td>",true);
                output("<td>{$row['livello']}</td>",true);
                output("<td>{$row['dove']}</td>",true);
                output("<td>{$row['dove_origine']}</td>",true);
                output("<td>{$row['descrizione']}</td>",true);
                output("</tr>",true);
        }
        output("</table>",true);
}elseif ($_GET['op']=="add"){
        output("`#Regole sul costo degli oggetti per chi li crea:`n");
        output("`3+1 ATT => 10 gemme -- +1 DEF => 10 gemme -- +1TURNO => 6 gemme`n");
        output("+1 500 oro => 10 gemme -- +1 gemma => 35 gemme -- +10vita => 10 gemme`n`n");
        output("`#Abilità con potere `n");
        output("`3+1 PVP => 25 gemme -- +5 HP => 2 gemme -- +1 quest => 20 gemme`n");
        output("Special_use 20 gemme per livello.`n");
        output("`#Potenziamenti `n");
        output("`315 gemme per ogni potenziamento disponibile!.`n`n");
        output("`#Per OGNI tipo di bonus aggiungere +5 gemme.`n");
        output("Il livello di un oggetto sale di 1 ogni 30 gemme di valore`n`n");
        addnav("Home Editor Oggetti","magiceditor.php");
        creaoggetto(array());
}elseif ($_GET['op']=="edit"){
        addnav("Editor Oggetti Home","magiceditor.php");
        output("`#Regole sul costo degli oggetti per chi li crea:`n");
        output("`3+1 ATT => 10 gemme -- +1 DEF => 10 gemme -- +1TURNO => 6 gemme`n");
        output("+1 500 oro => 10 gemme -- +1 gemma => 35 gemme -- +10vita => 10 gemme`n`n");
        output("`#Abilità con potere `n");
        output("`3+1 PVP => 25 gemme -- +5 HP => 2 gemme -- +1 quest => 20 gemme`n`n");
        output("Special_use 20 gemme per livello.`n");
        output("`#Potenziamenti `n");
        output("`315 gemme per ogni potenziamento disponibile!.`n`n");
        output("`#Per OGNI tipo di bonus aggiungere +5 gemme.`n");
        output("Da definire le regole sull'usura fisica e magica");
        output("Il livello di un oggetto sale di 1 ogni 30 gemme di valore`n`n");
        addnav("Home Editor Oggetti","magiceditor.php");
        $sql = "SELECT * FROM oggetti WHERE id_oggetti='{$_GET['id']}'";
        $result = db_query($sql);
        if (db_num_rows($result)<=0){
                output("`iOggetto non trovato.`i");
        }else{
                output("Editor Oggetti:`n");
                $row = db_fetch_assoc($result);
                creaoggetto($row);
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
                $sql="UPDATE oggetti SET $sql WHERE id_oggetti='{$_GET['id']}'";
        }else{
                $sql="INSERT INTO oggetti ($keys) VALUES ($vals)";
        }
        db_query($sql);
        if (db_affected_rows()>0){
                output("Oggetto salvato!");
        }else{
                output("Oggetto non salvato: $sql");
        }
        addnav("Home Editor Oggetti","magiceditor.php");
}

function creaoggetto($mount){
        global $output;
        output("<form action='magiceditor.php?op=save&id={$mount['id_oggetti']}' method='POST'>",true);
        addnav("","magiceditor.php?op=save&id={$mount['id_oggetti']}");
        $output.="<table>";
        $output.="<tr><td>Nome:</td><td><input name='mount[nome]' value=\"".HTMLEntities2($mount['nome'])."\"></td></tr>";
        $output.="<tr><td>Descrizione:</td><td><input name='mount[descrizione]' value=\"".HTMLEntities2($mount['descrizione'])."\"></td></tr>";
        $output.="<tr><td>Dove (1 emporio):</td><td><input name='mount[dove]' value=\"".HTMLEntities2($mount['dove'])."\"></td></tr>";
        $output.="<tr><td>Dove Originario:</td><td><input name='mount[dove_origine]' value=\"".HTMLEntities2($mount['dove_origine'])."\"></td></tr>";
        $output.="<tr><td>Attacco:</td><td><input name='mount[attack_help]' value=\"".HTMLEntities2((int)$mount['attack_help'])."\"></td></tr>";
        $output.="<tr><td>Difesa:</td><td><input name='mount[defence_help]' value=\"".HTMLEntities2((int)$mount['defence_help'])."\"></td></tr>";
        $output.="<tr><td>Speciale (quale):</td><td><input name='mount[special_help]' value=\"".HTMLEntities2((int)$mount['special_help'])."\"></td></tr>";
        $output.="<tr><td>Speciale usi:</td><td><input name='mount[special_use_help]' value=\"".HTMLEntities2((int)$mount['special_use_help'])."\"></td></tr>";
        $output.="<tr><td>Oro:</td><td><input name='mount[gold_help]' value=\"".HTMLEntities2((int)$mount['gold_help'])."\"></td></tr>";
        $output.="<tr><td>Turni:</td><td><input name='mount[turns_help]' value=\"".HTMLEntities2((int)$mount['turns_help'])."\"></td></tr>";
        $output.="<tr><td>Gemme:</td><td><input name='mount[gems_help]' value=\"".HTMLEntities2((int)$mount['gems_help'])."\"></td></tr>";
        $output.="<tr><td>Quest:</td><td><input name='mount[quest_help]' value=\"".HTMLEntities2((int)$mount['quest_help'])."\"></td></tr>";
        $output.="<tr><td>Vita:</td><td><input name='mount[hp_help]' value=\"".HTMLEntities2((int)$mount['hp_help'])."\"></td></tr>";
        $output.="<tr><td>PvP:</td><td><input name='mount[pvp_help]' value=\"".HTMLEntities2((int)$mount['pvp_help'])."\"></td></tr>";
        $output.="<tr><td>Esperienza:</td><td><input name='mount[exp_help]' value=\"".HTMLEntities2((int)$mount['exp_help'])."\"></td></tr>";
        $output.="<tr><td>Cura:</td><td><input name='mount[heal_help]' value=\"".HTMLEntities2((int)$mount['heal_help'])."\"></td></tr>";
        $output.="<tr><td>Livello:</td><td><input name='mount[livello]' value=\"".HTMLEntities2($mount['livello'])."\"></td></tr>";
        $output.="<tr><td>Costo (gemme):</td><td><input name='mount[valore]' value=\"".HTMLEntities2($mount['valore'])."\"></td></tr>";
        $output.="<tr><td>Potere:</td><td><input name='mount[potere]' value=\"".HTMLEntities2((int)$mount['potere'])."\"></td></tr>";
        $output.="<tr><td>Usi giornalieri potere:</td><td><input name='mount[potere_uso]' value=\"".HTMLEntities2((int)$mount['potere_uso'])."\"></td></tr>";
        $output.="<tr><td>Potenziamenti:</td><td><input name='mount[potenziamenti]' value=\"".HTMLEntities2((int)$mount['potenziamenti'])."\"></td></tr>";
        $output.="<tr><td>Usura fisica (-1 indistruttibile):</td><td><input name='mount[usura]' value=\"".HTMLEntities2((int)$mount['usura'])."\"></td></tr>";
        $output.="<tr><td>Usura fisica massima (-1 indistruttibile):</td><td><input name='mount[usuramax]' value=\"".HTMLEntities2((int)$mount['usuramax'])."\"></td></tr>";
        $output.="<tr><td>Bonus resistenza usura fisica:</td><td><input name='mount[usuraextra]' value=\"".HTMLEntities2((int)$mount['usuraextra'])."\"></td></tr>";
        $output.="<tr><td>Usura magica (-1 indistruttibile):</td><td><input name='mount[usuramagica]' value=\"".HTMLEntities2((int)$mount['usuramagica'])."\"></td></tr>";
        $output.="<tr><td>Usura magica massima (-1 indistruttibile):</td><td><input name='mount[usuramagicamax]' value=\"".HTMLEntities2((int)$mount['usuramagicamax'])."\"></td></tr>";
        $output.="<tr><td>Bonus resistenza usura magica:</td><td><input name='mount[usuramagicaextra]' value=\"".HTMLEntities2((int)$mount['usuramagicaextra'])."\"></td></tr>";
        $output.="</td></tr>";
        $output.="</table>";
        $output.="<input type='submit' class='button' value='Salva'></form>";
}

page_footer();
?>