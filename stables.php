<?php
require_once "common.php";
page_header("Stalla di Merick");
$session['user']['locazione'] = 177;
addnav("`@Torna al Villaggio","village.php");
$repaygold = round($playermount['mountcostgold']*2/3,0);
$repaygems = round($playermount['mountcostgems']*2/3,0);
//$futtercost = $session[user][level]*(90+$session[user][dragonkills]+$session[user][level]);
$futtercost =intval($session['user']['level']*(50+$session['user']['dragonkills']+($session['user']['level']/2))*0.8);
if ($_GET['op']==""){
    checkday();
    output("`7Dietro alla locanda, e un po' a sinistra del carrozzone di Pegasus, ci sono delle belle stalle come
    chiunque si aspetterebbe di trovarne in un villaggio.
    Qui Merick, un nano dall'aspetto scontroso, si prende cura di vari animali.
    `n`n
    Ti avvicini e lui si gira, puntando un forcone nella tua direzione, \"`&Ach,
    scusa ".($session['user']['sex']?"ragazza":"ragazzo").", non ti ebbi sentito arrivare, pensassi
    sicuro fosse Cedrik; cerca sempre di migliorare nel lancio del nano.  Bheeee, che
    potessi fare per te?`7\" ti domanda.  ");
}elseif($_GET['op']=="examine"){
    $sql = "SELECT * FROM mounts WHERE mountid='".$_GET['id']."'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, non avessi una bestia del genere qui!`7\" urla il nano!");
    }else{
        if ($session['user']['hashorse']>0){
                output("`\$`bAttenzione!`b Stai per vendere il tuo ".$playermount['mountname']."!!! Vuoi continuare?`n`n");
        }
        output("`7\"`&Aye, questa fosse proprio una bella bestia!`7\" commenta il nano.`n`n");
        $mount = db_fetch_assoc($result);
        output("`7Creatura: `&".$mount['mountname']."`n");
        output("`7Descrizione: `&".$mount['mountdesc']."`n");
        output("`7Costo: `^".$mount['mountcostgold']."`& oro, `%".$mount['mountcostgems']."`& gemme".($mount['mountcostpd'] > 0?(", `\$".$mount['mountcostpd']." punti donazione"):"")."`n");
        output("`n`n");
        output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
        output("<tr class='trlight'><td><a href=stables.php?op=buymount&id=".$mount['mountid'].">`bConferma l'acquisto`b</a></td></tr>", true);
        output("</table>",true);
        addnav("","stables.php?op=buymount&id=".$mount['mountid']);
    }
}elseif($_GET['op']=='buymount'){
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)<=0){
        output("`7\"`&Ach, non avessi una bestia del genere qui!`7\" urla il nano!");
    }else{
        $mount = db_fetch_assoc($result);
        if (
            ($session['user']['gold']+$repaygold) < $mount['mountcostgold']
             ||
            ($session['user']['gems']+$repaygems) < $mount['mountcostgems']
        ){
            output("`7Merick ti guarda storto.  \"`&Be', cosa pensassi che facessi? Non vedi che questo ");
            output($mount['mountname']." costa `^".$mount['mountcostgold']."`& pessi doro e `%");
            output($mount['mountcostgems']."`& gemme?`7\"");
        }elseif (($session['user']['donation']-$session['user']['donationspent']) < $mount['mountcostpd']){
            output("`7Merick ti guarda storto.  \"`&Be', cosa pensassi che facessi? Non vedi che questo ");
            output($mount['mountname']." costa `\$".$mount['mountcostpd']."`& punti donasione??`7\"");            
        }else{
            if ($session['user']['hashorse']>0){
                output("`7Consegni le redini del tuo ".$playermount['mountname']." e il prezzo del tuo nuovo ");
                output("animaletto, e Merick porta fuori uno splendido `&".$mount['mountname']."`7 per te!`n`n");
            }else{
                output("`7Paghi il prezzo del tuo nuovo animaletto, e Merick porta fuori uno splendido ");
                output("`&".$mount['mountname']."`7 per te!`n`n");
            }
            $session['user']['hashorse']=$mount['mountid'];
            $session['user']['mountname'] = "";
            $goldcost = $repaygold-$mount['mountcostgold'];
            $session['user']['gold']+=$goldcost;
            $gemcost = $repaygems-$mount['mountcostgems'];
            $session['user']['gems']+=$gemcost;
            if ($mount['mountcostpd'] > 0) {
                $session['user']['donationspent'] += $mount['mountcostpd'];
                $sql = "INSERT INTO donazioni (nome,idplayer,tipo)
                     VALUES ('animale: ". $mount['mountname'] ."','".$session['user']['acctid']."','P')";
                db_query($sql) or die(db_error($link));
            }
            debuglog(($goldcost <= 0?"spende ":"guadagna ") . abs($goldcost) ." oro e ". ($gemcost <= 0?"spende ":"guadagna ") . abs($gemcost) ." gemme". 
                ($mount['mountcostpd'] > 0?(" e utilizza ".$mount['mountcostpd']." punti donazione"):"") ." per un nuovo animale: ".$mount['mountname']);
            $session['bufflist']['mount']=unserialize($mount['mountbuff']);
            // Recalculate so the selling stuff works right
            $playermount = getmount($mount['mountid']);
            $repaygold = round($playermount['mountcostgold']*2/3,0);
            $repaygems = round($playermount['mountcostgems']*2/3,0);
        }
    }
}elseif($_GET['op']=='futter'){
   if ($session['user']['gold']>=$futtercost) {
              $buff = unserialize($playermount['mountbuff']);
              if ($session['bufflist']['mount']['rounds'] == $buff['rounds']) {
         output("Il ".$playermount['mountname']." è sazio e non guarda il cibo. Merick sbuffa, ma ti rende i soldi.");
      }else if ($session['bufflist']['mount']['rounds'] > $buff['rounds']*.5) {
         $futtercost=$futtercost/2;
         output("Il ".$playermount['mountname']." mangia una parte del cibo e lascia il resto. ");
         output($playermount['mountname']." è sazio.. ");
         output("Merick ti rende il 50% del prezzo pagato.`nPaghi solo $futtercost pezzi d'oro.");
         $session['user']['gold']-=$futtercost;
      }else{
         $session['user']['gold']-=$futtercost;
         output("Il  ".$playermount['mountname']." si avventa sul cibo che gli porgi.`n");
         output("Il ".$playermount['mountname']." è sazio e dai a Merick $futtercost pezzi d'oro.");
      }
             $session['bufflist']['mount']=$buff;
   } else {
      output("`7Non hai oro a sufficenza per sfamare il tuo compagno.");
   }
}elseif($_GET['op']=='sellmount'){
    output("`\$`bAttenzione!`b Stai per vendere il tuo ".$playermount['mountname']."!!! Vuoi continuare?.`n`n");
    output("<table border=0 cellpadding=2 cellspacing=1 align=center>",true);
    output("<tr class='trlight'><td><a href=stables.php?op=sellmount2>`bConferma la vendita`b</a></td></tr>", true);
    output("</table>",true);
    addnav("","stables.php?op=sellmount2");
}elseif($_GET['op']=='sellmount2'){
    $session['user']['gold']+=$repaygold;
    $session['user']['gems']+=$repaygems;
    debuglog("guadagna $repaygold oro e $repaygems gemme vendendo il suo animale: ".$playermount['mountname']);
    unset($session['bufflist']['mount']);
    $session['user']['hashorse']=0;
    $session['user']['mountname'] = "";
    output("`7Per quanto sia triste farlo, consegni il tuo prezioso ".$playermount['mountname'].", e ti sfugge ");
    output("una lacrima.`n`n");
    output("Ma, nel momento in cui vedi ".($repaygold>0?"i `^$repaygold`7 pezzi d'oro ".($repaygems>0?" e le ":""):"le ").($repaygems>0?"`%$repaygems`7 gemme":"").", scopri di sentirti molto meglio.");
}
if ($session['user']['hashorse']>0){
    addnav("");
    addnav("`&Cibo per ".$playermount['mountname']." (`^$futtercost Oro`0)","stables.php?op=futter");
    output("`n`nMerick ti offre `^$repaygold`& pezzi d'oro e `%$repaygems`& gemme per il tuo ".$playermount['mountname'].".");
    addnav("");
    addnav("W?`\$Vendi il tuo ".$playermount['mountname'],"stables.php?op=sellmount",false,false,true);
}
$sql = "SELECT mountname,mountid,mountcategory FROM mounts WHERE mountactive=1 ORDER BY mountcategory,mountcostgems,mountcostgold";
$result = db_query($sql) or die(db_error(LINK));
$category="";
$countrow = db_num_rows($result);
for ($i=0; $i<$countrow; $i++){
//for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    if ($category!=$row['mountcategory']){
        addnav($row['mountcategory']);
        $category = $row['mountcategory'];
    }
    addnav("`#Esamina ".$row['mountname']."`0","stables.php?op=examine&id=".$row['mountid']);
}
page_footer();
?>