<?php

// 25072004

/*
Part of the spell mod by anpera
available at www.dragonprime.net
*/

require_once("common.php");

page_header("Incantesimi e Mosse Speciali");
$session['user']['locazione'] = 176;

$shopowner="Hatetepe";

output("`b`c`(Incantesimi-Istantanei di ogni genere`c`b`0`n");
if ($_GET[action]=="sell"){ // Zauberladen (written on a cassiopeia while taking a bath)
    if (isset($_GET['id'])){
        $sql="SELECT * FROM items WHERE id=$_GET[id]";
        $result=db_query($sql);
        $row = db_fetch_assoc($result);
        output("`2$shopowner prende ".$row['name']."`2 e ti da in cambio ".($row[gold]?"`^$row[gold] `2pezzi d'oro":"")." ".($row[gems]?"`#$row[gems]`2 gemme":"").". ");
        debuglog("vende ad Hatetepe incantesimo ".$row['name']." e prende ".$row['gold']." pezzi d'oro e ".$row['gems']." gemme");
        addnav("`#Vendi Ancora","spellshop.php?action=sell");
        $sql="DELETE FROM items WHERE id=$_GET[id]";
        $session['user']['gold']+=$row['gold'];
        $session['user']['gems']+=$row['gems'];
        db_query($sql);
    }else{
        $sql="SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND (gold>0 OR gems>0) AND class='Spell' ORDER BY name ASC";
        $result=db_query($sql);
        if (db_num_rows($result)){
            output("`2 Mostri a $shopowner i tuoi incantesimi ed egli ti dice quanto te li pagherà.`n`n");
            output("<table border='0' cellpadding='1' cellspacing='3'>",true);
            output("<tr class='trhead'><td>`bNome`b</td><td>`bPrezzo`b</td></tr>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td><a href='spellshop.php?action=sell&id=$row[id]'>$row[name]</a></td><td align='right'>`^$row[gold]`0 Oro, `#$row[gems]`0 Gemme</td></tr><tr class='$bgcolor'><td colspan='2'>$row[description]</td></tr>",true);
                addnav("","spellshop.php?action=sell&id=$row[id]");
            }
                output("</table>",true);
        } else {
            output("`2Non hai neanche un incantesimo che possa interessare a $shopowner.");
        }
    }
    addnav("`6Torna al Negozio","spellshop.php");
}else if ($_GET['action']=="buy"){ // ok, water's getting cold ^^
    if (isset($_GET['id'])){
        $sql="SELECT * FROM items WHERE id=$_GET[id]";
        $result=db_query($sql);
        $row = db_fetch_assoc($result);
        if ($session['user']['gems']<$row['gems'] || $session['user']['gold']<$row['gold']){
            output("`2Non puoi permettertelo.");
            addnav("`%Compra qualcos'altro","spellshop.php?action=buy");
        }else if (db_num_rows(db_query("SELECT id FROM items WHERE name='$row[name]' AND owner=".$session['user']['acctid']." AND class='Spell'"))>0){
            output("`2Hai già questo incantesimo. Usalo o rivendilo.");
            addnav("`%Compra qualcos'altro","spellshop.php?action=buy");
        }else{
            output("`2Con il tuoi dito indichi \"`3$row[name]`2\". $shopowner te lo consegna e tu lo paghi ".($row['gold']?"`^".$row['gold']." `2pezzi d'oro":"")." ".($row['gems']?"e `#".$row['gems']."`2 gemme":"")." per esso. ");
            debuglog("compra da Hatetepe incantesimo ".$row['name']." e paga ".$row['gold']." pezzi d'oro e ".$row['gems']." gemme");
            addnav("`%Compra qualcos'altro","spellshop.php?action=buy");
            $sql="INSERT INTO items(name,class,owner,value1,value2,hvalue,gold,gems,description,buff) VALUES ('$row[name]','Spell',".$session['user']['acctid'].",$row[value1],$row[value2],$row[hvalue],$row[gold],$row[gems],'".addslashes($row[description])."','".addslashes($row[buff])."')";
            $session['user']['gold']-=$row['gold'];
            $session['user']['gems']-=$row['gems'];
            db_query($sql);
        }
    }else{
        output("`2Cosa desideri?`n`n");
        $ppp=25; // Parts Per Page to display
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET['limit'];
            addnav("`!Pagina precedente","spellshop.php?action=buy&limit=".($page-1));
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql="SELECT * FROM items WHERE (owner=0 AND class='Spell') OR class='Spell.Prot' AND gold<=".$session['user']['gold']." AND gems<=".$session['user']['gems']." ORDER BY name,class ASC LIMIT $limit";
        $result=db_query($sql);
        if (db_num_rows($result)>$ppp) addnav("`^Pagina Successiva","spellshop.php?actino=buy&limit=".($page+1));
        if (db_num_rows($result)){
            output("<table border='0' cellpadding='2' cellspacing='2'>",true);
            output("<tr class='trhead'><td>`bNome`b</td><td>`bPrezzo`b</td></tr>",true);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
            //for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td><a href='spellshop.php?action=buy&id=$row[id]'>$row[name]</a></td><td align='right'>`^$row[gold]`0 Oro, `#$row[gems]`0 Gemme</td></tr><tr class='$bgcolor'><td colspan='2'>$row[description]</td></tr>",true);
                addnav("","spellshop.php?action=buy&id=$row[id]");
            }
            output("</table>",true);

        } else {
            output("`2\"`3Non abbiamo nessun incantesimo che tu ti possa permettere`2\"");
        }
    }
    addnav("`#Torna al Negozio","spellshop.php");
}else{
    output("`2Entri nel negozio degli incantesimi di $shopowner. L'aria densa di fumo ti impedisce di scorgere chiaramente
    le merci esposte sopra gli scaffali, come anche i lineamenti del venditore che si trova dietro al bancone e che sembra
    indaffarato nella preparazione di qualcosa di importante. Mentre ti avvicini riesci a leggere le etichette sugli scaffali,
    e noti tra le altre \"`@Pioggia di Fuoco`2\", \"`@Attacco Potenziato`2\" mentre altre etichette sono talmente consunte
    che ogni tuo sforzo per decifrarle risulta vano.`n Nel frattempo il venditore ha interrotto il suo lavoro e ti squadra
    con occhi inquisitori da dietro un paio di occhiali unti e anneriti, e rivolgendoti la parola dice: `n\"`3Cosa potere fare
    io per te potente gueriero ? Miei inkantezimi che io prepara con mie mani, sono a tua disposizionen per poki pezzi di oro
    o qualche gemma, ma essi saranno molto di aiuto te per battaglie contro nemici. Dimmi qwuale esso desideri che io mostri
    te, sarò moltissimo onorato ja di fare osservare`2\".`n Dette queste parole riprende il suo lavoro sbirciando di tanto
    in tanto nella tua direzione. `nCosa vuoi fare ?`n");
    // put your story in here!
    addnav("`\$Vendi un Incantesimo","spellshop.php?action=sell");
    addnav("`@Compra un Incantesimo","spellshop.php?action=buy");
}
addnav("`@Torna al Villaggio","village.php");

page_footer();
?>