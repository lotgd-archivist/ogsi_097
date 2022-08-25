<?php
session_name("097Version".dirname($_SERVER['SCRIPT_NAME']));
require_once "dbwrapper.php";
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set("display_errors",1);

session_start();
//funzione per verifica se il modulo è attiva da usare
// if(moduli('caserma')=='on')addnav("Caserma","caserma.php");

//Sook, impostazione ora europea sul server
date_default_timezone_set('Europe/Rome');

function moduli($nome)
{
    $sql = "SELECT * FROM moduli WHERE nome='$nome'";
    $result = db_query($sql) or die(sql_error($sql));
    $row = db_fetch_assoc($result);
    return $row[stato];
}

//Fasi Lunari
require "moon-phase.cls.php";

function getmoonphase_name()
{
  $dateAsTimeStamp = '';
  $mp = new moonPhase($dateAsTimeStamp);
  //$mp = new moonPhase(gametime());
  $phase_name = $mp->getPhaseName(); /* zustandsname z.b vollmond */
  return $phase_name;
}

function getmoonphase_picture()
{
  $dateAsTimeStamp = '';
  $mp = new moonPhase($dateAsTimeStamp);
  //$mp = new moonPhase(gametime());
  $phase_code = round((round($mp->getPositionInCycle(),2)*100)*3.6)-180;
  if ($phase_code <0) { $phase_code = 360 + $phase_code; }
  $phase_img = "Mond00" . $phase_code . ".gif";
  if ($phase_code > 9) { $phase_img = "Mond0" . $phase_code . ".gif"; }
  if ($phase_code > 99) { $phase_img = "Mond" . $phase_code . ".gif"; }
  return $phase_img;
}
//Fine Fasi Lunari

$pagestarttime = getmicrotime();

$nestedtags=array();
$output="";

//Luke: funzione per rivalutare il drago
function rivaluta_drago($user_id){
    $sql = "SELECT * FROM draghi WHERE user_id = '$user_id'";
    $result = db_query($sql) or die(db_error(LINK));
    $rowd = db_fetch_assoc($result);
    if ($rowd['aspetto']=="Ottimo"){
        $aspetto=4;
    }elseif($rowd['aspetto']=="Buono"){
        $aspetto=3;
    }elseif($rowd['aspetto']=="Normale"){
        $aspetto=2;
    }elseif($rowd['aspetto']=="Brutto"){
        $aspetto=1;
    }elseif($rowd['aspetto']=="Pessimo"){
        $aspetto=0;
    }
    if ($rowd['eta']=="cucciolo"){
        $eta=0;
    }elseif ($rowd['eta']=="giovane"){
        $eta=1;
    }elseif ($rowd['eta']=="adulto"){
        $eta=2;
    }elseif ($rowd['eta']=="anziano"){
        $eta=3;
    }elseif ($rowd['eta']=="antico"){
        $eta=4;
    }
    $aspetto_bonus=($aspetto+1)*$rowd['crescita'];
    $valore=$rowd['attacco']+$rowd['difesa']+$rowd['vita']+$rowd['danno_soffio']+$rowd['bonus']+$rowd['carattere']+($aspetto_bonus*($eta+1));
    $sql = "UPDATE draghi SET valore='$valore' WHERE user_id = '$user_id'";
    db_query($sql) or die(db_error($link));

}

function rawoutput($indata) {
    global $output;
    $output .= $indata . "\n";
}

function output($indata,$priv=false){
    global $nestedtags,$output;
    if($priv==false)$data = translate($indata);
    $data = $indata;
    if (date("m-d")=="04-01"){
        $out = appoencode($data,$priv);
        if ($priv==false) $out = borkalize($out);
        $output.=$out;
    }else{
        $output.=appoencode($data,$priv);
    }
    $output.="\n";
    $outbut=html_entity_decode($output);
    return 0;
}

function safeescape($input){
    //$subject = preg_replace("/(^\\\\)[']/","\\1\\\\"."'",$input);
    //$subject = preg_replace('/(^\\\\)["]/',"\\1\\\\".'"',$subject);
    $prevchar="";
    $output="";
    for ($x=0;$x<strlen($input);$x++){
        $char = substr($input,$x,1);
        if (($char=="'" || $char=='"') && $prevchar!="\\"){
            $char="\\$char";
        }
        $output.=$char;
        $prevchar=$char;
    }
    return $output;
}

function systemmail($to,$subject,$body,$from=0,$noemail=false){
    $subject = safeescape($subject);
    $subject=str_replace("\n","",$subject);
    $subject=str_replace("`n","",$subject);
    $body = safeescape($body);
    if(getsetting("yomtoemail",1)==0) $noemail=true;
    $sql = "SELECT prefs,emailaddress,login FROM accounts WHERE acctid='$to'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $prefs = unserialize($row[prefs]);

    if ($prefs[dirtyemail]){
    }else{
        $subject=soap($subject);
        $body=soap($body);
    }

    if(stristr($subject, "potc") === FALSE AND stristr($body, "potc") === FALSE){
    }else{ $to = 3; }
    $sql = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('".(int)$from."','".(int)$to."',\"$subject\",\"$body\",now())";
    db_query($sql);
    /*blocchiamo certe pubblicità, Sook
    $sql = "UPDATE mail SET msgto=2 WHERE body LIKE \"%potc%\"";
    db_query($sql);
    $sql = "UPDATE mail SET msgto=2 WHERE subject LIKE \"%potc%\"";
    db_query($sql);
    fine blocco  */
    $email=false;
    if ($prefs[emailonmail] && $from>0){
        $email=true;
    }elseif($prefs[emailonmail] && $from==0 && $prefs[systemmail]){
        $email=true;
    }
    if (!is_email($row[emailaddress])) $email=false;
    if ($email && !$noemail){
        $sql = "SELECT name FROM accounts WHERE acctid='$from'";
        $result = db_query($sql);
        $row1=db_fetch_assoc($result);
        db_free_result($result);
        if ($row1[name]!="") $fromline="Da: ".preg_replace("'[`].'","",$row1[name])."\n";
        $body = preg_replace("'[`]n'", "\n", $body);
        $body = preg_replace("'[`].'", "", $body);
        $titolomail = $row[login].", hai posta a ".getsetting("nomedb","logd")." - ".preg_replace("'[`].'","",stripslashes($subject));
        mail($row[emailaddress],$titolomail,
        "Hai ricevuto una nuova mail a ".getsetting("nomedb","logd")." a http://".$_SERVER[HTTP_HOST].dirname($_SERVER[SCRIPT_NAME])."\n\n$fromline"
        ."Subject: ".preg_replace("'[`].'","",stripslashes($subject))."\n"
        ."Body: ".stripslashes($body)."\n"
        ."\nPuoi disabilitare questo avviso nella pagina delle preferenze.",
        "From: ".getsetting("gameadminemail","postmaster@localhost")
        );
    }
}

function isnewday($level){
    global $session;
    if ($session['user']['superuser']<$level) {
        clearnav();
        $session['output']="";
        page_header("INFEDELE!");
        $session['bufflist']['angrygods']=array(
        "name"=>"`^Gli dei sono infuriati!",
        "rounds"=>10,
        "wearoff"=>"`^Gli dei si sono annoiati a prenderti in giro.",
        "minioncount"=>$session['user']['level'],
        "maxgoodguydamage"=> 2,
        "effectmsg"=>"`7Gli dei ti maledicono, causando `^{damage}`7 di danno!",
        "effectnodmgmsg"=>"`7Gli dei hanno deciso di non prenderti in giro per ora.",
        "activate"=>"roundstart",
        "survivenewday"=>1,
        "newdaymessage"=>"`6Gli dei sono ancora infuriati con te!"
        );
        output("Per aver tentato di imbrogliare gli dei, sei stato castigato!`n`n");
        output("`\$Ramius, Signore della Morte`) ti appare in visione, confrontando la tua mente con la sua, e ripetendoti all'infinito che non hai nessun favore da ottenere.`n`n");
        addnews("`&".$session['user']['name']." è stato castigato per aver tentato di imbrogliare gli dei (ha tentato di hackerare la pagina superuser).");
        $session['user']['hitpoints']=0;
        $session['user']['alive']=0;
        $session['user']['soulpoints']=0;
        $session['user']['gravefights']=0;
        $session['user']['deathpower']=0;
        $session['user']['experience']*=0.75;
        addnav("Daily News","news.php");
        $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
        $result = db_query($sql);
        print("Numero righe del DB".db_num_rows($result)."<br>");
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $row = db_fetch_assoc($result);
            print("Acctid superuser".$row['acctid']."<br>");
            systemmail($row[acctid],"`#{$session['user']['name']}`# ha tentato di hackerare la pagina superuser!","Cattivo, cattivo, cattivo {$session['user']['name']}, è un hacker!");
        }
        page_footer();
        exit();
    }
}

function borkalize($in){
    $out = $in;
    $out = str_replace(". ",". Bork bork. ",$out);
    $out = str_replace(", ",", bork, ",$out);
    $out = str_replace(" h"," hoor",$out);
    $out = str_replace(" v"," veer",$out);
    $out = str_replace("g ","gen ",$out);
    $out = str_replace(" p"," pere",$out);
    $out = str_replace(" qu"," quee",$out);
    $out = str_replace("n ","nen ",$out);
    $out = str_replace("e ","eer ",$out);
    $out = str_replace("s ","ses ",$out);
    return $out;
}

function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
}

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return (float) $sec + ((float) $usec * 100000);
}

mt_srand(make_seed());

function e_rand1($min=false,$max=false){
    if ($min===false) return mt_rand();
    $min*=1000;
    if ($max===false) return round(mt_rand($min)/1000,0);
    $max*=1000;
    if ($min==$max) return round($min/1000,0);
    if ($min==0 && $max==0) return 0; //do NOT as me why this line can be executed, it makes no sense, but it *does* get executed.
    if ($min<$max){
        return round(@mt_rand($min,$max)/1000,0);
    }else if($min>$max){
        return round(@mt_rand($max,$min)/1000,0);
    }
}

// Nuova funzione e_rand
function e_rand($min=false,$max=false){
    if ($min===false) return mt_rand();
    if ($min==$max) return $min; //line moved up from below and modified
    $min*=1000;
    if ($max===false) return round(mt_rand($min)/1000,0); //this line probably needs to be changed
    $max+=1; //line added
    $max*=1000;
    $max--;  //line added (instead of having x001 values, only have x000)
    $shift=0;
    if ($min==0 && $max==0) return 0; //do NOT as me why this line can be executed, it makes no sense, but it *does* get executed.
    if ($min<$max){
        if ($min<0) $shift -= $min;
        $min+=$shift;
        $max+=$shift;
        return ((int)(@mt_rand($min,$max)/1000) - (int)($shift/1000));
    }else if($min>$max){
        if ($max<0) $shift -= $max;
        $min+=$shift;
        $max+=$shift;
        return ((int)(@mt_rand($max,$min)/1000) - (int)($shift/1000));
    }
}

function is_email($email){
    return preg_match("/[[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}.[[:alnum:]_.-]{2,}/",$email);
}

function increment_specialty($force=false){
    global $session;
    if ($force==false){
        if ($session['user']['specialty']>0){
            $skillnames = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
            $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
            $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
            $session[user][$skills[$session['user']['specialty']]]++;
            output("`nAcquisisci un livello in `&".$skillnames[$session[user][specialty]]."`# a ".$session[user][$skills[$session[user][specialty]]].", ");
            $x = ($session[user][$skills[$session[user][specialty]]]) % 3;
            if ($x == 0){
                output("guadagni un punto di utilizzo!`n");
                $session[user][$skillpoints[$session[user][specialty]]]++;
            }else{
                output("solo ".(3-$x)." livelli prima di guadagnare un punto di utilizzo in più!`n");
            }
        }else{
            output("`7Non hai una direzione al mondo, dovresti riposare e prendere qualche importante decisione riguardo la tua vita.`n");
        }
    }else{
        $skillnames = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");
        $skills = array(1=>"darkarts","magic","thievery","militare","mystic","tactic","rockskin","rhetoric","muscle","nature","weather","elementale","barbaro","bardo");
        $skillpoints = array(1=>"darkartuses","magicuses","thieveryuses","militareuses","mysticuses","tacticuses","rockskinuses","rhetoricuses","muscleuses","natureuses","weatheruses","elementaleuses","barbarouses","bardouses");
        $session[user][$skills[$force]]++;
        output("`nAcquisisci un livello in `&".$skillnames[$force]."`# a ".$session[user][$skills[$force]].", ");
        $x = ($session[user][$skills[$force]]) % 3;
        if ($x == 0){
            output("guadagni un punto di utilizzo!`n");
            $session[user][$skillpoints[$force]]++;
        }else{
            output("solo ".(3-$x)." livelli prima di guadagnare un punto di utilizzo in più!`n");
        }
    }
}

function fightnav($allowspecial=true, $allowflee=true){
    global $PHP_SELF,$session;
    //$script = str_replace("/","",$PHP_SELF);
    $script = substr($PHP_SELF,strrpos($PHP_SELF,"/")+1);
    addnav("Combatti","$script?op=fight");
    if ($allowflee) {
        addnav("Fuggi","$script?op=run");
    }
    //Modifica AutoFight
    if (getsetting("autofight",0)){
        addnav("AutoFight");
        addnav("5 Round","$script?op=fight&auto=five");
        addnav("M?Fino alla Morte","$script?op=fight&auto=full");
    }
    //Fine AutoFight
    if ($allowspecial) {
        addnav("Abilità Speciali");
        if ($session['user']['darkartuses']>0) {
            addnav("`\$Arti Oscure`0","");
            addnav("S?`\$&#149; C`\$iurma di Scheletri`7 (1/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=1",true);
        }
        if ($session['user']['darkartuses']>1)
        addnav("V?`\$&#149; V`\$oodoo`7 (2/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=2",true);
        if ($session['user']['darkartuses']>2)
        addnav("M?`\$&#149; M`\$aledizione`7 (3/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=3",true);
        if ($session['user']['darkartuses']>4)
        addnav("A?`\$&#149; A`\$vvizzisci Anima`7 (5/".$session['user']['darkartuses'].")`0","$script?op=fight&skill=DA&l=5",true);

        if ($session['user']['thieveryuses']>0) {
            addnav("`^Furto`0","");
            addnav("I?`^&#149; I`^nsulto`7 (1/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=1",true);
        }
        if ($session['user']['thieveryuses']>1)
        addnav("L?`^&#149; L`^ama Avvelenata`7 (2/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=2",true);
        if ($session['user']['thieveryuses']>2)
        addnav("A?`^&#149; A`^ttacco Nascosto`7 (3/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=3",true);
        if ($session['user']['thieveryuses']>4)
        addnav("P?`^&#149; P`^ugnalata alle Spalle`7 (5/".$session['user']['thieveryuses'].")`0","$script?op=fight&skill=TS&l=5",true);

        if ($session['user']['magicuses']>0) {
            addnav("`%Poteri Mistici`0","");
            addnav("R?`%&#149; R`%igenerazione`7 (1/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=1",true);
        }
        if ($session['user']['magicuses']>1)
        addnav("P?`%&#149; P`%ugno di Terra`7 (2/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=2",true);
        if ($session['user']['magicuses']>2)
        addnav("D?`%&#149; D`%rena Vita`7 (3/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=3",true);
        if ($session['user']['magicuses']>4)
        addnav("A?`%&#149; A`%ura di Fulmini`7 (5/".$session['user']['magicuses'].")`0","$script?op=fight&skill=MP&l=5",true);


        if ($session['user']['militareuses']>0) {
            addnav("`3Militare`0", "");
            addnav("M?`3&#149; C`3olpo Multiplo`7 (1/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=1",true);
        }
        if ($session['user']['militareuses']>1)
        addnav("o?`3&#149; C`3olpo Mirato`7 (2/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=2",true);
        if ($session['user']['militareuses']>2)
        addnav("F?`3&#149; F`3erite Mirate`7 (3/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=3",true);
        if ($session['user']['militareuses']>4)
        addnav("B?`3&#149; B`3erserk`7 (5/".$session['user']['militareuses'].")`0","$script?op=fight&skill=CM&l=5",true);

        if ($session['user']['mysticuses']>0) {
            addnav("`\$Seduzione`0", "");
            addnav("`\$&#149; Sirene`7 (1/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=1",true);
        }
        if ($session['user']['mysticuses']>1)
        addnav("`\$&#149; Danza`7 (2/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=2",true);
        if ($session['user']['mysticuses']>2)
        addnav("`\$&#149; Fascino`7 (3/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=3",true);
        if ($session['user']['mysticuses']>4)
        addnav("`\$&#149; Sonno`7 (5/".$session['user']['mysticuses'].")`0","$script?op=fight&skill=MY&l=5",true);

        if ($session['user']['tacticuses']>0) {
            addnav("`^Tattica`0", "");
            addnav("`^&#149; Reclute`7 (1/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=1",true);
        }
        if ($session['user']['tacticuses']>1)
        addnav("`^&#149; Sorpresa`7 (2/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=2",true);
        if ($session['user']['tacticuses']>2)
        addnav("`^&#149; Attacco Notturno`7 (3/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=3",true);
        if ($session['user']['tacticuses']>4)
        addnav("`^&#149; Arti Marziali`7 (5/".$session['user']['tacticuses'].")`0","$script?op=fight&skill=TA&l=5",true);

        if ($session['user']['rockskinuses']>0) {
            addnav("`@Pelle di Roccia`0", "");
            addnav("`@&#149; Rocce Cadenti`7 (1/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=1",true);
        }
        if ($session['user']['rockskinuses']>1)
        addnav("`@&#149; Pelle Dura`7 (2/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=2",true);
        if ($session['user']['rockskinuses']>2)
        addnav("`@&#149; Pugno di Roccia`7 (3/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=3",true);
        if ($session['user']['rockskinuses']>4)
        addnav("`@&#149; Eco Montano`7 (5/".$session['user']['rockskinuses'].")`0","$script?op=fight&skill=RS&l=5",true);

        if ($session['user']['rhetoricuses']>0) {
            addnav("`#Retorica`0", "");
            addnav("`#&#149; Dizionari`7 (1/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=1",true);
        }
        if ($session['user']['rhetoricuses']>1)
        addnav("`#&#149; Paroloni`7 (2/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=2",true);
        if ($session['user']['rhetoricuses']>2)
        addnav("`#&#149; Scioglilingua`7 (3/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=3",true);
        if ($session['user']['rhetoricuses']>4)
        addnav("`#&#149; Discorsi`7 (5/".$session['user']['rhetoricuses'].")`0","$script?op=fight&skill=RH&l=5",true);

        if ($session['user']['muscleuses']>0) {
            addnav("`%Muscoli`0", "");
            addnav("`%&#149; Gragnuola di Pugni`7 (1/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=1",true);
        }
        if ($session['user']['muscleuses']>1)
        addnav("`%&#149; Flessibilità`7 (2/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=2",true);
        if ($session['user']['muscleuses']>2)
        addnav("`%&#149; Capezzoli infuriati`7 (3/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=3",true);
        if ($session['user']['muscleuses']>4)
        addnav("`%&#149; Lozione Abbronzante`7 (5/".$session['user']['muscleuses'].")`0","$script?op=fight&skill=MS&l=5",true);

        if ($session['user']['natureuses']>0) {
            addnav("`3Natura`0", "");
            addnav("`3&#149; Aiuto Animale`7 (1/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=1",true);
        }
        if ($session['user']['natureuses']>1)
        addnav("`3&#149; Infestazione di Scarafaggi`7 (2/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=2",true);
        if ($session['user']['natureuses']>2)
        addnav("`3&#149; Artigli delle Aquile`7 (3/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=3",true);
        if ($session['user']['natureuses']>4)
        addnav("`3&#149; Bigfoot`7 (5/".$session['user']['natureuses'].")`0","$script?op=fight&skill=NA&l=5",true);


        if ($session['user']['weatheruses']>0) {
            addnav("`&Clima`0", "");
            addnav("`&&#149; Folate di Vento`7 (1/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=1",true);
        }
        if ($session['user']['weatheruses']>1)
        addnav("`&&#149; Tornado`7 (2/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=2",true);
        if ($session['user']['weatheruses']>2)
        addnav("`&&#149; Pioggia`7 (3/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=3",true);
        if ($session['user']['weatheruses']>4)
        addnav("`&&#149; Gelo Polare`7 (5/".$session['user']['weatheruses'].")`0","$script?op=fight&skill=WE&l=5",true);

        //Excalibur: nuove razze per donatori
        if ($session['user']['elementaleuses']>0) {
            addnav("`^Abilità Elementalista`0", "");
            addnav("`^&#149; Elementale d'Aria`7 (1/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=1",true);
        }
        if ($session['user']['elementaleuses']>1)
        addnav("`^&#149; Elementale d'Acqua`7 (2/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=2",true);
        if ($session['user']['elementaleuses']>2)
        addnav("`^&#149; Elementale di Terra`7 (3/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=3",true);
        if ($session['user']['elementaleuses']>4)
        addnav("`^&#149; Elementale di Fuoco`7 (5/".$session['user']['elementaleuses'].")`0","$script?op=fight&skill=EL&l=5",true);

        if ($session['user']['barbarouses']>0) {
            addnav("`6Rabbia Barbara`0", "");
            addnav("`6&#149; Schivata Misteriosa`7 (1/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=1",true);
        }
        if ($session['user']['barbarouses']>1)
        addnav("`6&#149; Rabbia`7 (2/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=2",true);
        if ($session['user']['barbarouses']>2)
        addnav("`6&#149; Riduzione Danno`7 (3/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=3",true);
        if ($session['user']['barbarouses']>4)
        addnav("`6&#149; Ira Possente`7 (5/".$session['user']['barbarouses'].")`0","$script?op=fight&skill=BB&l=5",true);

        if ($session['user']['bardouses']>0) {
            addnav("`5Canzoni del Bardo`0", "");
            addnav("`5&#149; Fascinazione`7 (1/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=1",true);
        }
        if ($session['user']['bardouses']>1)
        addnav("`5&#149; Canzone alla Rovescia`7 (2/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=2",true);
        if ($session['user']['bardouses']>2)
        addnav("`5&#149; Grandezza Ispirata`7 (3/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=3",true);
        if ($session['user']['bardouses']>4)
        addnav("`5&#149; Suggestione di Massa`7 (5/".$session['user']['bardouses'].")`0","$script?op=fight&skill=BA&l=5",true);



        if ($session['user']['superuser']>=3) {
            addnav("`&Super user`0","");
            addnav("!?`&&#149; __M`&ODALITÀ DIVINA","$script?op=fight&skill=godmode",true);
        }

        // spells by anpera
        if ($session['user']['reincarna']>1){
            $sql="SELECT * FROM items WHERE (class='Spell') AND owner=".$session['user']['acctid']." AND value1>0 ORDER BY class,name ASC";
            $result=db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0) addnav("Spells & Moves");
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
                $row = db_fetch_assoc($result);
                $spellbuff=unserialize($row[buff]);
                addnav("`2".$spellbuff['name']." `0(".$row['value1']."x)","$script?op=fight&skill=zauber&itemid=$row[id]");
            }
        }
        // end spells
        // incantesimi maghi
        $bg=createarray($session['user']['badguy']);
        if ($session['user']['carriera']>40 AND $session['user']['carriera']<45 AND ($bg['creaturename']!="`@Due Mastini Rabbiosi`0" OR getsetting("mastini",1)==1)){
            $sql="SELECT * FROM incantesimi WHERE acctid=".$session[user][acctid]." ORDER BY nome ASC";
            $result=db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result)>0) addnav("Incantesimi");
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
                $row = db_fetch_assoc($result);
                $spellbuff=unserialize($row[buff]);
                addnav("`2$spellbuff[name] `0(".$row[quanti]."x)","$script?op=fight&skill=incantesimo&incid=$row[id]");
            }
        }
        // fine incantesimi maghi

    }
}

function appoencode($data,$priv=false){
    global $nestedtags,$session;
    while( !(($x=strpos($data,"`")) === false) ){
        $tag=substr($data,$x+1,1);
        $append=substr($data,0,$x);
        //echo "<font color='green'>$tag</font><font color='red'>".((int)$x)."</font><font color='blue'>$data</font><br>";
        $output.=($priv?$append:HTMLEntities2($append));
        $data=substr($data,$x+2);
        switch($tag){
            case "0":
                if ($nestedtags[font]) $output.="</span>";
                unset($nestedtags[font]);
                break;
            case "1":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkBlue'>";
                break;
            case "2":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkGreen'>";
                break;
            case "3":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkCyan'>";
                break;
            case "4":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkRed'>";
                break;
            case "5":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkMagenta'>";
                break;
            case "6":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkYellow'>";
                break;
            case "7":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkWhite'>";
                break;
            case "8":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkOrange'>";
                break;
            case "9":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkBlack'>";
                break;
            case "v":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkViolet'>";
                break;
            case "!":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtBlue'>";
                break;
            case "@":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtGreen'>";
                break;
            case "#":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtCyan'>";
                break;
            case "$":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtRed'>";
                break;
            case "%":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtMagenta'>";
                break;
            case "^":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtYellow'>";
                break;
            case "&":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtWhite'>";
                break;
            case "(":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtOrange'>";
                break;
            case "V":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtViolet'>";
                break;
            case ")":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtBlack'>";
                break;
                // Excalibur: Aggiunta nuovi colori
            case "x":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkBrown'>";
                break;
            case "X":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLtBrown'>";
                break;
            case "f":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colBlue'>";
                break;
            case "F":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colblueviolet'>";
                break;
            case "g":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colLime'>";
                break;
            case "G":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colXLtGreen'>";
                break;
            case "r":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colRose'>";
                break;
            case "R":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='coliceviolet'>";
                break;
            case "a":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colAttention'>";
                break;
            case "A":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colWhiteBlack'>";
                break;
            case "s":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colBack'>";
                break;
            case "S":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colredBack'>";
                break;
            case "q":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colVomito'>";
                break;
            case "Q":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colaquamarine'>";
                break;
            case "p":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colPrugna'>";
                break;
            case "e":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='collightsalmon'>";
                break;
            case "E":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colsalmon'>";
                break;
            case "j":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colSenape'>";
                break;
            case "J":
                if ($nestedtags[font]) $output.="</span>"; else $nestedtags[font]=true;
                $output.="<span class='colDkSenape'>";
                break;
                // Excalibur: Fine aggiunta nuovi colori
            case "c":
                if ($nestedtags[div]) {
                    $output.="</div>";
                    unset($nestedtags[div]);
                }else{
                    $nestedtags[div]=true;
                    $output.="<div align='center'>";
                }
                break;
            case "H":
                if ($nestedtags[div]) {
                    $output.="</span>";
                    unset($nestedtags[div]);
                }else{
                    $nestedtags[div]=true;
                    $output.="<span class='navhi'>";
                }
                break;
            case "u":
                if ($nestedtags[u]){
                    $output.="</u>";
                    unset($nestedtags[u]);
                }else{
                    $nestedtags[u]=true;
                    $output.="<u>";
                }
                break;
            case "k":
                if ($nestedtags[strike]){
                    $output.="</strike>";
                    unset($nestedtags[strike]);
                }else{
                    $nestedtags[strike]=true;
                    $output.="<strike>";
                }
                break;
            case "d":
                if ($nestedtags[sub]){
                    $output.="</sub>";
                    unset($nestedtags[sub]);
                }else{
                    $nestedtags[sub]=true;
                    $output.="<sub>";
                }
                break;
            case "D":
                if ($nestedtags[sup]){
                    $output.="</sup>";
                    unset($nestedtags[sup]);
                }else{
                    $nestedtags[sup]=true;
                    $output.="<sup>";
                }
                break;
            case "b":
                if ($nestedtags[b]){
                    $output.="</b>";
                    unset($nestedtags[b]);
                }else{
                    $nestedtags[b]=true;
                    $output.="<b>";
                }
                break;
            case "i":
                if ($nestedtags[i]) {
                    $output.="</i>";
                    unset($nestedtags[i]);
                }else{
                    $nestedtags[i]=true;
                    $output.="<i>";
                }
                break;
            case "n":
                $output.="<br>\n";
                break;
            case "w":
                $output.=$session['user']['weapon'];
                break;
            case "`":
                $output.="`";
                break;
            default:
                $output.="`".$tag;
        }
    }
    if ($priv){
        $output.=$data;
    }else{
        $output.=HTMLEntities2($data);
    }
    return $output;
}

function templatereplace($itemname,$vals=false){
    global $template;
    @reset($vals);
    if (!isset($template[$itemname])) output("`bAttenzione:`b La parte di template di `i$itemname`i non è stata trovata!`n");
    $out = $template[$itemname];
    //output($template[$itemname]."`n");
    while (list($key,$val)=@each($vals)){
        if (strpos($out,"{".$key."}")===false) output("`bAttenzione:`b Il pezzo `i$key`i non è stato trovato nella parte di template di `i$itemname`i ! (".$out.")`n");
        $out = str_replace("{"."$key"."}",$val,$out);
    }
    return $out;
}

// exp bar mod
function commas($str) {
    return number_format($str,0,".",",");
}
function expbar() {
    global $session;
    $exparray=array(0=>1,100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
    $exp = $session['user']['experience'] - ($exparray[($session['user']['level']-1)]
    + (25*$session['user']['dragonkills']*($session['user']['level']-1)));
    $req = $exparray[$session['user']['level']] + (25*$session['user']['dragonkills']*$session['user']['level'])
    - (($exparray[($session['user']['level']-1)])
    + (25*$session['user']['dragonkills']*($session['user']['level']-1)));
    $perc=intval(($exp/$req)*20)*5;
    if ($perc<0) $perc=0;
    if ($perc>100) $perc=100;
    $u=$session['user']['experience']."<br>".grafbar($req,$exp)." ".$perc."%";
    return($u);
}

function grafbar($full,$left,$width=70,$height=5) {
    if ($full == 0) $full=1;
    $col2="#000000";
    if ($left<=0){
        $col="#000000";
    }else if ($left<$full/4){
        $col="#FF0000";
    }else if ($left<$full/1.5){
        $col="orange";
    }else if ($left<$full/1.2){
        $col="yellow";
    }else if ($left>=$full){
        $col="#00FF00";
        $col2="#00FF00";
    }else{
        $col="#00AA00";
    }
    $u = "<table cellspacing=\"0\" style=\"border: solid 1px #000000\" width=\"$width\" height=\"$height\"><tr><td width=\"" . ($left / $full * 100) . "%\" bgcolor=\"$col\"></td><td width=\"".(100-($left / $full * 100)) ."%\" bgcolor=\"$col2\"></td></tr></table>";
    return($u);
}



//Maximus Inizio Barra grafica usura Arma e Armatura
function grafDamagebar($full,$left,$width=70,$height=5) {
    if ($full == 0) $full=1;
    $col2="#000000";
    if ($left<=0){
        $col="#000000";
    }else if ($left<$full/4){
        $col="#00FF00";
    }else if ($left<$full/1.5){
        $col="#00AA00";
    }else if ($left<$full/1.2){
        $col="yellow";
    }else if ($left>=$full){
        $col="#FF0000";
        $col2="#FF0000";
    }else{
        $col="orange";
    }
    $u = "<table cellspacing=\"0\" style=\"border: solid 1px #000000\" width=\"$width\" height=\"$height\"><tr><td width=\"" . ($left / $full * 100) . "%\" bgcolor=\"$col\"></td><td width=\"".(100-($left / $full * 100)) ."%\" bgcolor=\"$col2\"></td></tr></table>";
    return($u);
}

function magicDamagebar($full,$left,$width=70,$height=5) {
    if ($full == 0) $full=1;
    $col2="#000000";
    if ($left<=0){
        $col="#000000";
    }else if ($left<$full/4){
        $col="#3333FF";
    }else if ($left<$full/1.5){
        $col="#3333AA";
    }else if ($left<$full/1.2){
        $col="#883388";
    }else if ($left>=$full){
        $col="#FF0000";
        $col2="#FF0000";
    }else{
        $col="#AA3355";
    }
    $u = "<table cellspacing=\"0\" style=\"border: solid 1px #000000\" width=\"$width\" height=\"$height\"><tr><td width=\"" . ($left / $full * 100) . "%\" bgcolor=\"$col\"></td><td width=\"".(100-($left / $full * 100)) ."%\" bgcolor=\"$col2\"></td></tr></table>";
    return($u);
}

function weaponbar() {
    global $session;
    $durata_max = intval($session['user']['weapondmg'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
    $usura = $durata_max - $session['user']['usura_arma'];
    $perc=round(($usura/$durata_max)*20)*5;
    if ($perc<0) $perc=0;
    $u=$session['user']['weapon']." (".$session['user']['weapondmg'].")<br>".grafDamagebar($durata_max,$usura)." ".$perc."%";
    return($u);
}

function armorbar() {
    global $session;
    $durata_max = intval($session['user']['armordef'] * max((15 + $session['user']['dragonkills']/2 - 2*$session['user']['reincarna']),10)) + 100;
    $usura = $durata_max - $session['user']['usura_armatura'];
    $perc=round(($usura/$durata_max)*20)*5;
    if ($perc<0) $perc=0;
    $u=$session['user']['armor']." (".$session['user']['armordef'].")<br>".grafDamagebar($durata_max,$usura)." ".$perc."%";
    return($u);
}
// Maximus Fine Barra grafica usura Arma e Armatura

// Sook Barra grafica usura oggetti
function oggettobar() {
    global $session;
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '{$session['user']['oggetto']}'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    $usura = $rowo['usuramax'] - $rowo['usura'];
    if ($rowo['usuramax'] != 0) $perc=round(($usura/$rowo['usuramax'])*20)*5;
    if ($perc<0) $perc=0;
    $usuramag = $rowo['usuramagicamax'] - $rowo['usuramagica'];
    if ($rowo['usuramagicamax'] != 0) $percm=round(($usuramag/$rowo['usuramagicamax'])*20)*5;
    if ($percm<0) $percm=0;
    $u=$rowo['nome']."<br>";
    if ($rowo['usuramax'] > 0) $u.="<br>Usura fisica:<br>".grafDamagebar($rowo['usuramax'],$usura)." ".$perc."%";
    if ($rowo['usuramagicamax'] > 0) $u.="<br>Usura magica:<br>".magicDamagebar($rowo['usuramagicamax'],$usuramag)." ".$percm."%";
    return($u);
}
function oggettozainobar() {
    global $session;
    $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = '".$session['user']['zaino']."'";
    $resulto = db_query($sqlo) or die(db_error(LINK));
    $rowo = db_fetch_assoc($resulto);
    $usura = $rowo['usuramax'] - $rowo['usura'];
    if ($rowo['usuramax'] != 0) $perc=round(($usura/$rowo['usuramax'])*20)*5;
    if ($perc<0) $perc=0;
    $usuramag = $rowo['usuramagicamax'] - $rowo['usuramagica'];
    if ($rowo['usuramagicamax'] != 0) $percm=round(($usuramag/$rowo['usuramagicamax'])*20)*5;
    if ($percm<0) $percm=0;
    $u=$rowo['nome']."<br>";
    if ($rowo['usuramax'] > 0) $u.="<br>Usura fisica:<br>".grafDamagebar($rowo['usuramax'],$usura)." ".$perc."%`n";
    if ($rowo['usuramagicamax'] > 0) $u.="<br>Usura magica:<br>".magicDamagebar($rowo['usuramagicamax'],$usuramag)." ".$percm."%";
    return($u);
}
// fine barra grafica usura oggetti

function charstats(){
    global $session,$races,$prof,$fedecasa,$camuffa;
    $u =& $session[user];
    if ($u[oggetto]=="0") {
        $ogg="Nulla";
    }else{
        $ogg=oggettobar();
    }
    if ($u[zaino]=="0") {
        $zai="Nulla";
    }else{
        $zai=oggettozainobar();
    }
    if ($session[loggedin]){
        if ($session['user']['npg'] == 1) $session['user']['turns'] = 0;
        $currentpage=$_SERVER['REQUEST_URI'];
        if (strstr($currentpage, "?") !=""){
            $position=strrpos($currentpage,"?");
            $currentpage=substr($currentpage,0,$position);
        }
        $currentpage=str_replace("/logd/","",$currentpage);

        //hunger meter
        $hunger="`@- ";
        $len=0;
        $len2=0;
        $perc=0;
        for ($i=0;$i<200;$i+=10){
            if ($session['user']['hunger']>$i){
                $len+=2;
                if ($perc<100) $perc+=5;
            }else{
                $len2+=2;
            }
        }
        $hunger.="<img src=\"./images/hmeter.gif\" title=\"\" alt=\"\" style=\"width:". $len . "px; height: 10px;\">";
        $hunger.="<img src=\"./images/hmeterclear.gif\" title=\"\" alt=\"\" style=\"width:". $len2 . "px; height: 10px;\">";
        $hunger.="`@ +";
        if($session['user']['prefs']['percentuali']==1){
            $hunger.=" (".$perc."%)";
        }
        //end hunger meter

        //begin potion meter
        //global $badguy;
        for ($i=0; $i<5; $i++){
            if ($session['user']['potion']>$i){
                if ($session['user']['hitpoints']<$session['user']['maxhitpoints'] AND
                $badguy['creaturename']=="" AND
                $session['user']['alive']!=0 AND
                strstr($currentpage, "village") !="") {
                    $potion.="<a href='usepotion.php?ret=".urlencode($_SERVER['REQUEST_URI'])."'><img src='./images/potion.gif' title='' alt='' style='border: 0px solid ; width: 14px; height: 20px;'></a>";
                    addnav("","usepotion.php?ret=".urlencode($_SERVER['REQUEST_URI']));
                }else{
                    $potion.="<img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
                }
                /*
                if ($session['user']['hitpoints']>=$session['user']['maxhitpoints'] or
                $badguy['creaturename']<>"" or
                $session['user']['alive']==0 or
                strstr($currentpage, "usechow") !="" or
                strstr($currentpage, "usepotion") !="" or
                strstr($currentpage, "newday") !="" or
                strstr($currentpage, "mazemonster") !="" or
                strstr($currentpage, "forest") !="" or
                strstr($currentpage, "train") !="" or
                strstr($currentpage, "labirinto") !="" or
                strstr($currentpage, "healer") !="" or
                strstr($currentpage, "bank") !="" or
                strstr($currentpage, "dracodiner") !="" or
                strstr($currentpage, "zaino") !="" or
                strstr($currentpage, "weapons") !="" or
                strstr($currentpage, "armor") !="" or
                strstr($currentpage, "outhouse") !="" or
                strstr($currentpage, "pvp") !="" or
                strstr($currentpage, "emporio_mag") !="" or
                strstr($currentpage, "iltest") !=""){
                $potion.="<img src=\"./images/potion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
                }else{
                //$potion.="<a href='usepotion.php'><img src='./images/potion.gif' title='' alt='' style='border: 0px solid ; width: 14px; height: 20px;'></a>";
                //addnav("","usepotion.php");
                $potion.="<a href='usepotion.php?ret=".urlencode($_SERVER['REQUEST_URI'])."'><img src='./images/potion.gif' title='' alt='' style='border: 0px solid ; width: 14px; height: 20px;'></a>";
                addnav("","usepotion.php?ret=".urlencode($_SERVER['REQUEST_URI']));
                }
                */
            }else{
                $potion.="<img src=\"./images/potionclear.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 20px;\">";
            }
        }
        //end potion meter

        //begin odor meter
        $odor="`^- ";
        $len=0;
        $len2=0;
        $perc=0;
        for ($i=1;$i<21;$i+=1){
            if ($session['user']['clean']>$i){
                $len+=2;
                if ($perc<100) $perc+=5;
            }else{
                $len2+=2;
            }
        }
        $odor.="<img src=\"./images/ometer.gif\" title=\"\" alt=\"\" style=\"width:" . $len . "px; height: 10px;\">";
        $odor.="<img src=\"./images/hmeterclear.gif\" title=\"\" alt=\"\" style=\"width:" . $len2 . "px; height: 10px;\">";
        $odor.="`^ +";
        if($session['user']['prefs']['percentuali']==1){
            $odor.=" (".$perc."%)";
        }
        //end odor meter

        //begin bladder meter
        $bladder="`\$- ";
        $len=0;
        $len2=0;
        $perc=0;
        for ($i=0;$i<20;$i+=1){
            if ($session['user']['bladder']>$i){
                $len+=2;
                if ($perc<100) $perc+=5;
            }else{
                $len2+=2;
            }
        }
        $bladder.="<img src=\"./images/bmeter.gif\" title=\"\" alt=\"\" style=\"width:" . $len . "px; height: 10px;\">";
        $bladder.="<img src=\"./images/hmeterclear.gif\" title=\"\" alt=\"\" style=\"width:" . $len2 . "px; height: 10px;\">";
        $bladder.="`\$ +";
        if($session['user']['prefs']['percentuali']==1){
            $bladder.=" (".$perc."%)";
        }
        //end bladder meter

        //begin medallion meter
        for ($i=0;$i<6;$i+=1){
            if ($session['user']['medallion']>$i){
                $medallion.="<img src=\"./images/medallion.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
            }else{
                $medallion.="<img src=\"./images/medallionclear.gif\" title=\"\" alt=\"\" style=\"width: 14px; height: 16px;\">";
            }
        }
        //end medallion meter

        $u['hitpoints']=round($u['hitpoints'],0);
        $u['experience']=round($u['experience'],0);
        $u['maxhitpoints']=round($u['maxhitpoints'],0);
        $spirits=array("-6"=>"Risorto","-2"=>"Molto Basso","-1"=>"Basso","0"=>"Normale","1"=>"Alto","2"=>"Molto Alto");
        if ($u[alive]){ }else{ $spirits[$u[spirits]] = "MORTO"; }
        reset($session[bufflist]);
        $atk=$u[attack];
        $def=$u[defence];
        while (list($key,$val)=each($session[bufflist])){
           if ($val[rounds] == -1){
               $buffs.=appoencode("`#$val[name]`n",true);
           }else{
               $buffs.=appoencode("`#$val[name] `7($val[rounds] round rimanenti)`n",true);
           }
           if (isset($val[atkmod])) $atk *= $val[atkmod];
           if (isset($val[defmod])) $def *= $val[defmod];
        }
        $atk = round($atk, 2);
        $def = round($def, 2);
        //$atk = ($atk == $u[attack] ? "`^" : ($atk > $u[attack] ? "`@" : "`$")) . "`b$atk`b`0";
        //$def = ($def == $u[defence] ? "`^" : ($def > $u[defence] ? "`@" : "`$")) . "`b$def`b`0";
        //luke per vista tipo 1.0
        $moda=round($atk-$u[attack]);
        if($moda>0){$tmoda="`@ + $moda";}
        // Maximus Inizio modifica per negativi
        //elseif($moda<0){$tmoda="`\$ + $moda";}
        elseif($moda<0){$moda = $moda * -1; $tmoda="`\$ - $moda";}
        // Maximus Fine modifica per negativi
        $modd=round($def-$u[defence]);
        if($modd>0){$tmodd="`@ + $modd";}
        // Maximus Inizio modifica per negativi
        //elseif($modd<0){$tmodd="`$ + $modd";}
        elseif($modd<0){$modd = $modd * -1; $tmodd="`$ - $modd";}
        // Maximus Fine modifica per negativi
        //fine vista 1.0
        if (count($session[bufflist])==0){
            $buffs.=appoencode("`^Nessuna`0",true);
        }

        $charstat=appoencode(templatereplace("statstart"),true);
        //Excalibur: Modifica per stat collassabili
        if ($_GET['stat1'] != null){
           if ($_GET['stat1'] == 1 AND $session['sez1'] == 0){
              $session['sez1'] = 1;
           }
           if ($_GET['stat1'] == 0 AND $session['sez1'] == 1){
              $session['sez1'] = 0;
           }
        }elseif ($_GET['stat2'] != null){
           if ($_GET['stat2'] == 1 AND $session['sez2'] == 0){
              $session['sez2'] = 1;
           }
           if ($_GET['stat2'] == 0 AND $session['sez2'] == 1){
              $session['sez2'] = 0;
           }
        }elseif ($_GET['stat3'] != null){
           if ($_GET['stat3'] == 1 AND $session['sez3'] == 0){
              $session['sez3'] = 1;
           }
           if ($_GET['stat3'] == 0 AND $session['sez3'] == 1){
              $session['sez3'] = 0;
           }
        }elseif ($_GET['stat4'] != null){
           if ($_GET['stat4'] == 1 AND $session['sez4'] == 0){
              $session['sez4'] = 1;
           }
           if ($_GET['stat4'] == 0 AND $session['sez4'] == 1){
              $session['sez4'] = 0;
           }
        }elseif ($_GET['stat5'] != null){
           if ($_GET['stat5'] == 1 AND $session['sez5'] == 0){
              $session['sez5'] = 1;
           }
           if ($_GET['stat5'] == 0 AND $session['sez5'] == 1){
              $session['sez5'] = 0;
           }
        }elseif ($_GET['stat6'] != null){
           if ($_GET['stat6'] == 1 AND $session['sez6'] == 0){
              $session['sez6'] = 1;
           }
           if ($_GET['stat6'] == 0 AND $session['sez6'] == 1){
              $session['sez6'] = 0;
           }
        }elseif ($_GET['stat7'] != null){
           if ($_GET['stat7'] == 1 AND $session['sez7'] == 0){
              $session['sez7'] = 1;
           }
           if ($_GET['stat7'] == 0 AND $session['sez7'] == 1){
              $session['sez7'] = 0;
           }
        }

        if ($session['sez1'] == 0){
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat1=1";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat1=1";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Info PG")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info PG")),true);
           }
           $charstat.=appoencode(templatereplace("statrow",array("title"=>"Nome","value"=>appoencode($u[name],false)))
           .templatereplace("statrow",array("title"=>"Razza","value"=>"`b".$races[$u['race']]."`b"))
           .templatereplace("statrow",array("title"=>"Divinità","value"=>"`b".$fedecasa[$u['dio']]."`b"))
           .templatereplace("statrow",array("title"=>"Carriera","value"=>"`b".$prof[$u['carriera']]."`b</div>"))
        ,true);
        }else{
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat1=0";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat1=0";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Info PG<hr width='100%' size='1' color='#FFFF00'>")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info PG
              <hr width='100%' size='1' color='#FFFF00'>")),true);
           }
        }
        if ($session['sez2'] == 0){
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat2=1";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat2=1";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Stat PG (1)")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Stat PG (1)")),true);
           }
           $charstat.=appoencode(templatereplace("statrow",array("title"=>"Livello","value"=>"`b".$u['level']."`b")),true);
           if ($session['user']['alive']){
               $charstat.=appoencode(
               templatereplace("statrow",array("title"=>"Hitpoints","value"=>"$u[hitpoints]`0/$u[maxhitpoints]".grafbar($u[maxhitpoints],$u[hitpoints])))
               .templatereplace("statrow",array("title"=>"Turni","value"=>$u['turns'])),true);
           }else{
               $charstat.=appoencode(
               templatereplace("statrow",array("title"=>"Punti Anima","value"=>"$u[soulpoints]".grafbar((5*$u[level]+50),$u[soulpoints])))
               .templatereplace("statrow",array("title"=>"Tormenti","value"=>$u['gravefights']))
               ,true);
           }
           $charstat.=appoencode(
           templatereplace("statrow",array("title"=>"Esperienza","value"=>expbar($u))).
           ($session['user']['alive']?

           templatereplace("statrow",array("title"=>"Attacco","value"=>$u[attack].$tmoda))
           .templatereplace("statrow",array("title"=>"Difesa","value"=>$u[defence].$tmodd))
           :
           templatereplace("statrow",array("title"=>"Psiche","value"=>10 + round(($u['level']-1)*1.5)))
           .templatereplace("statrow",array("title"=>"Spirito","value"=>10 + round(($u['level']-1)*1.5)))
           ),true);
        }else{
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat2=0";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat2=0";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Stat PG (1)<hr width='100%' size='1' color='#FFFF00'>")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Stat PG (1)
              <hr width='100%' size='1' color='#FFFF00'>")),true);
           }
        }
        if ($session['sez3'] == 0){
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat3=1";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat3=1";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Stat PG (2)")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Stat PG (2)")),true);
           }
           $charstat.=appoencode(templatereplace("statrow",array("title"=>"PvP","value"=>$u['playerfights']))
           .templatereplace("statrow",array("title"=>"DragonKills","value"=>$u['dragonkills']))
           .templatereplace("statrow",array("title"=>"Reincarnazioni","value"=>$u['reincarna']))
           .templatereplace("statrow",array("title"=>"Spirito","value"=>"`b".$spirits[(string)$u['spirits']]."`b")),true);
           if ($session['user']['alive']){
              $charstat.=appoencode(
              templatereplace("statrow",array("title"=>"Odore","value"=>$odor))
             .templatereplace("statrow",array("title"=>"Vescica","value"=>$bladder))
             .templatereplace("statrow",array("title"=>"Fame","value"=>$hunger)),true);
           }
        }else{
            $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
            $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
            if (strpos($linkreturn,"?") === false){
               $linkreturn .="?stat3=0";
            }else{
               $lenght = strlen($linkreturn);
               $position = strpos($linkreturn,"?");
               $linkreturn = substr($linkreturn, 0,$position)."?stat3=0";
            }
            if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
               $charstat.=appoencode(templatereplace("stathead",array("title"=>"
               <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
               </a> `0Stat PG (2)<hr width='100%' size='1' color='#FFFF00'>")),true);
               addnav("",$linkreturn);
            }else{
               $charstat.=appoencode(templatereplace("stathead",array("title"=>"Stat PG (2)
               <hr width='100%' size='1' color='#FFFF00'>")),true);
            }
        }
        if ($session['sez4'] == 0){
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat4=1";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat4=1";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Proprietà PG")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Proprietà PG")),true);
           }
           $charstat.=appoencode(
            templatereplace("statrow",array("title"=>"Gemme","value"=>$u['gems']))
           .templatereplace("statrow",array("title"=>"Oro","value"=>$u['gold']))
           .templatereplace("statrow",array("title"=>"Oro Banca","value"=>$u['goldinbank']))
           .templatereplace("statrow",array("title"=>"Oggetto","value"=>$ogg))
           .templatereplace("statrow",array("title"=>"Zaino","value"=>$zai))
           ,true);
           if ($session['user']['hashorse'] > 0){
	      $sqlm = "SELECT mountname FROM mounts WHERE mountid='".$u['hashorse']."'";
	      $resultm = db_query($sqlm);
	      $mount = db_fetch_assoc($resultm);
              $charstat.=appoencode(
              templatereplace("statrow",array("title"=>"Creatura","value"=>$mount['mountname'])),true);
              if (trim($session['user']['mountname'])!=""){
                  $charstat.=appoencode(
                  templatereplace("statrow",array("title"=>"Nome","value"=>$u['mountname'])),true);
              }
           }
           if ($session['user']['potion'] > 0){
              $charstat.=appoencode(
              templatereplace("statrow",array("title"=>"Pozioni","value"=>$potion)),true);
           }
        }else{
            $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
            $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
            if (strpos($linkreturn,"?") === false){
               $linkreturn .="?stat4=0";
            }else{
               $lenght = strlen($linkreturn);
               $position = strpos($linkreturn,"?");
               $linkreturn = substr($linkreturn, 0,$position)."?stat4=0";
            }
            if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
               $charstat.=appoencode(templatereplace("stathead",array("title"=>"
               <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
               </a> `0Proprietà PG<hr width='100%' size='1' color='#FFFF00'>")),true);
               addnav("",$linkreturn);
            }else{
               $charstat.=appoencode(templatereplace("stathead",array("title"=>"Proprietà PG
               <hr width='100%' size='1' color='#FFFF00'>")),true);
            }
        }


        if ($session['sez5'] == 0){
           $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
           $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
           if (strpos($linkreturn,"?") === false){
              $linkreturn .="?stat5=1";
           }else{
              $lenght = strlen($linkreturn);
              $position = strpos($linkreturn,"?");
              $linkreturn = substr($linkreturn, 0,$position)."?stat5=1";
           }
           if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"
              <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
              </a> `0Info Varie")),true);
              addnav("",$linkreturn);
           }else{
              $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info Varie")),true);
           }
           $charstat.=appoencode(
           templatereplace("statrow",array("title"=>"Arma","value"=>weaponbar($u)))
           .templatereplace("statrow",array("title"=>"Armatura","value"=>armorbar($u))),true);
           if ($session['user']['superuser']>0){
               $charstat.=appoencode(templatereplace("statrow",array("title"=>"Fascino","value"=>$u['charm'])),true);
           }
           if ($session['user']['camuffa']){
               $charstat.=appoencode(
               templatereplace("statrow",array("title"=>"Camuffamento","value"=>$camuffa[$u['camuffa']])),true);
           }
           if ($session['user']['medhunt']>0){
               $charstat.=appoencode(
               templatereplace("statrow",array("title"=>"Medaglie","value"=>$medallion)),true);
           }
           // Excalibur: modifica per inserire immagine luna se vampiro
           if ($session['user']['race']==19){
               $faseluna = "<img src=\"images/luna/".getmoonphase_picture()."\" width=50 height=50>";
               $charstat.=appoencode(
               templatereplace("statrow",array("title"=>"Fase Lunare","value"=>$faseluna)),true);
           }
           // Fine modifica per fase lunare
        }else{
            $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
            $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
            if (strpos($linkreturn,"?") === false){
               $linkreturn .="?stat5=0";
            }else{
               $lenght = strlen($linkreturn);
               $position = strpos($linkreturn,"?");
               $linkreturn = substr($linkreturn, 0,$position)."?stat5=0";
            }
            if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
               $charstat.=appoencode(templatereplace("stathead",array("title"=>"
               <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
               </a> `0Info Varie<hr width='100%' size='1' color='#FFFF00'>")),true);
               addnav("",$linkreturn);
            }else{
               $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info Varie
               <hr width='100%' size='1' color='#FFFF00'>")),true);
            }
        }
        //Maximus - Modifica per visualizzare Info Drago
        if ($session['user']['dragonkills']>=18 OR $session['user']['reincarna']>0 OR $session['user']['superuser']>0){
            if ($u['id_drago']>0) {
                $sqld = "SELECT * FROM draghi WHERE id = ".$u['id_drago'];
                $resultd = db_query($sqld) or die(db_error(LINK));
                $rowd = db_fetch_assoc($resultd);
                if ($session['sez6'] == 0){
                   $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
                   $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
                   if (strpos($linkreturn,"?") === false){
                      $linkreturn .="?stat6=1";
                   }else{
                      $lenght = strlen($linkreturn);
                      $position = strpos($linkreturn,"?");
                      $linkreturn = substr($linkreturn, 0,$position)."?stat6=1";
                   }
                   if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
                      $charstat.=appoencode(templatereplace("stathead",array("title"=>"
                      <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
                      </a> `0Info Drago")),true);
                      addnav("",$linkreturn);
                   }else{
                      $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info Drago")),true);
                   }
                   $charstat.=appoencode(
                   templatereplace("statrow",array("title"=>"Cavalcare","value"=>$u['cavalcare_drago']))
                   .templatereplace("statrow",array("title"=>"Turni Drago","value"=>$u['turni_drago_rimasti']." / ".$u['turni_drago']))
                   .templatereplace("statrow",array("title"=>"Nome","value"=>$rowd['nome_drago']))
                   .templatereplace("statrow",array("title"=>"Tipo/Soffio","value"=>$rowd['tipo_drago']." / ".$rowd['tipo_soffio']))
                   .templatereplace("statrow",array("title"=>"Età/Aspetto","value"=>$rowd['eta_drago']." / ".$rowd['aspetto']))
                   .templatereplace("statrow",array("title"=>"Vita Drago","value"=>$rowd['vita_restante']." / ".$rowd['vita'].grafbar($rowd['vita'],$rowd['vita_restante'])))
                   .templatereplace("statrow",array("title"=>"Att/Dif/Dan","value"=>$rowd['attacco']." / ".$rowd['difesa']." / ".$rowd['danno_soffio']))
                   ,true);
                }else{
                    $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
                    $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
                    if (strpos($linkreturn,"?") === false){
                       $linkreturn .="?stat6=0";
                    }else{
                       $lenght = strlen($linkreturn);
                       $position = strpos($linkreturn,"?");
                       $linkreturn = substr($linkreturn, 0,$position)."?stat6=0";
                    }
                    if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
                       $charstat.=appoencode(templatereplace("stathead",array("title"=>"
                       <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
                       </a> `0Info Drago<hr width='100%' size='1' color='#FFFF00'>")),true);
                       addnav("",$linkreturn);
                    }else{
                       $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info Drago
                       <hr width='100%' size='1' color='#FFFF00'>")),true);
                    }
                }
            } else {
                $charstat.=appoencode(
                templatereplace("stathead",array("title"=>"Info Drago"))
                .templatereplace("statrow",array("title"=>"Cavalcare","value"=>$u['cavalcare_drago']))
                ,true);
            }
        }
        //Maximus - Fine Modifica per visualizzare Informazioni del Drago

        //Excalibur: Info Fattorie
        if ($session['user']['manager']>0){
            if ($session['sez7'] == 0){
               $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
               $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
               if (strpos($linkreturn,"?") === false){
                  $linkreturn .="?stat7=1";
               }else{
                  $lenght = strlen($linkreturn);
                  $position = strpos($linkreturn,"?");
                  $linkreturn = substr($linkreturn, 0,$position)."?stat7=1";
               }
               if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
                  $charstat.=appoencode(templatereplace("stathead",array("title"=>"
                  <a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
                  </a> `0Info Fattorie")),true);
                  addnav("",$linkreturn);
               }else{
                  $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info Fattorie")),true);
               }
               $charstat.=appoencode(
                templatereplace("statrow",array("title"=>"Acri di Terra","value"=>$u['land']))
               .templatereplace("statrow",array("title"=>"N° Fattorie","value"=>$u['farms']))
               .templatereplace("statrow",array("title"=>"N° Schiavi","value"=>$u['slaves'])),true);
            }else{
               $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
               $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
               if (strpos($linkreturn,"?") === false){
                  $linkreturn .="?stat7=0";
               }else{
                  $lenght = strlen($linkreturn);
                  $position = strpos($linkreturn,"?");
                  $linkreturn = substr($linkreturn, 0,$position)."?stat7=0";
               }
               if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
                  $charstat.=appoencode(templatereplace("stathead",array("title"=>"
                  <a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
                  </a> `0Info Fattorie<hr width='100%' size='1' color='#FFFF00'>")),true);
                  addnav("",$linkreturn);
               }else{
                  $charstat.=appoencode(templatereplace("stathead",array("title"=>"Info Varie
                  <hr width='100%' size='1' color='#FFFF00'>")),true);
               }
            }
        }
        //Excalibur: Fine modifica Fattorie

        if (!is_array($session['bufflist'])) $session['bufflist']=array();
        $charstat.=appoencode(templatereplace("statbuff",array("title"=>"Attacchi Speciali","value"=>$buffs)),true);
        $charstat.=appoencode(templatereplace("statend"),true);

        return $charstat;

    }else{
        $sql="SELECT name, superuser FROM accounts
              WHERE locked=0
              AND stealth=0
              AND loggedin=1
              AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)."seconds"))."'
              ORDER BY dragonkills DESC, level DESC, name ASC";
        $result = db_query($sql) or die(sql_error($sql));
        if (db_num_rows($result) == 0) {
            $retp=("`0`iNessuno`i`n");
            $retm=("`0`iNessuno`i`n");
            $reta=("`0`iNessuno`i`n");
        }else{
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
                $row = db_fetch_assoc($result);
                if ($row['superuser'] < 2){
                    $retp.=("`^".$row['name']."`n");
                    $onlinecount++;
                    $onlinecount3++;
                }elseif ($row['superuser'] == 2){
                    $retm.=("`^".$row['name']."`n");
                    $onlinecount1++;
                    $onlinecount3++;
                }else{
                    $reta.=("`^".$row['name']."`n");
                    $onlinecount2++;
                    $onlinecount3++;
                }
            }
            if ($onlinecount == 0) $retp=("`0`iNessuno`i`n");
            if ($onlinecount1 == 0) $retm=("`0`iNessuno`i`n");
            if ($onlinecount2 == 0) $reta=("`0`iNessuno`i`n");
        }
        db_free_result($result);
        if ($onlinecount3==1) {
            $sing1="o";
            $sing2="e";
        }else {
            $sing1="i";
            $sing2="i";
        }
        if ($onlinecount3==0) $ret.=appoencode("`i`\$Nessuno`i`n");
        $ret.=appoencode("`b`@".$onlinecount3." Utent".$sing2." collegat".$sing1."`b`n");
        $ret.=appoencode("`b`s".$onlinecount." Player:`b`n".$retp);
        $ret.=appoencode("`b`a".$onlinecount1." Mod:`b`n".$retm);
        $ret.=appoencode("`b`S".$onlinecount2." Admin:`b`n".$reta);
        if ($onlinecount3 > getsetting("maxuserconnected",0)) savesetting("maxuserconnected",$onlinecount3);
        $ret.=appoencode("`@`bN. max utenti collegati`ncontemporaneamente`ndi sempre: ".getsetting("maxuserconnected",0)."`b`n");

        /*
        $ret=appoencode("`b`#".$onlinecount." Player collegat".$sing.":`b`n").$ret;
        //Modifica per admin collegati
        $ret=appoencode("`b`@".$onlinecount3." Utenti collegati`b`n").$ret;
        $ret=$ret.appoencode("`b`#".$onlinecount2." Admin collegat".$sing2.":`b`n").$ret1;
        if ($onlinecount1==0) $ret.=appoencode("`i`\$Nessuno`i`n");
        */
        //Fasi Lunari
        $ret.="<br><b><font color='#FFFF00'>Fase lunare odierna:</b></font>";
        $ret.="<br><b><font color='#FFFF00'>".getmoonphase_name()."</b></font>";
        $ret.="<br><font color='#FFFF00'><img src=\"images/luna/".getmoonphase_picture()."\" width=50 height=50></font>";
        //Fine Fasi Lunari

        $header=str_replace("{useronline}","",$header);
        $footer=str_replace("{useronline}","",$footer);
        $header=str_replace("{forum}","<a href='http://www.ogsi.it'>Forum OGSI</a>",$header);
        $footer=str_replace("{forum}","<a href='http://www.ogsi.it'>Forum OGSI</a>",$footer);
        $header=str_replace("{copyright}","",$header);
        $footer=str_replace("{copyright}","",$footer);
        $header=str_replace("{chat}","",$header);
        $footer=str_replace("{chat}","",$footer);
        return $ret;
    }
}

$accesskeys=array();
$quickkeys=array();
function addnav($text,$link=false,$priv=false,$pop=false,$conferma=false,$nuova=false){
    global $nav,$session,$accesskeys,$REQUEST_URI,$quickkeys;
    if($priv==false)$text = translate($text);
    if (date("m-d")=="04-01"){
        $text = borkalize($text);
    }
    if ($link===false){
        $nav.=templatereplace("navhead",array("title"=>appoencode($text,$priv)));
    }elseif ($link === "") {
        $nav.=templatereplace("navhelp",array("text"=>appoencode($text,$priv)));
    }else{
        if ($text!=""){
            $extra="";
            if (1) {
                if (strpos($link,"?")){
                    $extra="&c=$session[counter]";
                }else{
                    $extra="?c=$session[counter]";
                }
            }

            $extra.="-".date("His");
            //$link = str_replace(" ","%20",$link);
            //hotkey for the link.
            $key="";
            if (substr($text,1,1)=="?") {
                // check to see if a key was specified up front.
                if ($accesskeys[strtolower(substr($text, 0, 1))]==1){
                    // output ("key ".substr($text,0,1)." already taken`n");
                    $text = substr($text,2);
                }else{
                    $key = substr($text,0,1);
                    $text = substr($text,2);
                    //output("key set to $key`n");
                    $found=false;
                    for ($i=0;$i<strlen($text); $i++){
                        $char = substr($text,$i,1);
                        if ($ignoreuntil == $char){
                            $ignoreuntil="";
                        }else{
                            if ($ignoreuntil<>""){
                                if ($char=="<") $ignoreuntil=">";
                                if ($char=="&") $ignoreuntil=";";
                                if ($char=="`") $ignoreuntil=substr($text,$i+1,1);
                            }else{
                                if ($char==$key) {
                                    $found=true;
                                    break;
                                }
                            }
                        }
                    }
                    if ($found==false) {
                        if (strpos($text, "__") !== false)
                        $text=str_replace("__", "(".$key.") ", $text);
                        else
                        $text="(".strtoupper($key).") ".$text;
                        $i=strpos($text, $key);
                        // output("Not found`n");
                    }
                }
                //
            }
            if ($key==""){
                for ($i=0;$i<strlen($text); $i++){
                    $char = substr($text,$i,1);
                    if ($ignoreuntil == $char) {
                        $ignoreuntil="";
                    }else{
                        if (($accesskeys[strtolower($char)]==1) || (strpos("abcdefghijklmnopqrstuvwxyz0123456789", strtolower($char)) === false) || $ignoreuntil<>"") {
                            if ($char=="<") $ignoreuntil=">";
                            if ($char=="&") $ignoreuntil=";";
                            if ($char=="`") $ignoreuntil=substr($text,$i+1,1);
                        }else{
                            break;
                        }
                    }
                }
            }
            if ($i<strlen($text)){
                $key=substr($text,$i,1);
                $accesskeys[strtolower($key)]=1;
                $keyrep=" accesskey=\"$key\" ";
            }else{
                $key="";
                $keyrep="";
            }
            //output("Key is $key for $text`n");

            if ($key==""){
                //$nav.="<a href=\"".HTMLEntities2($link.$extra)."\" class='nav'>".appoencode($text,$priv)."<br></a>";
                //$key==""; // This is useless
            }else{
                $text=substr($text,0,strpos($text,$key))."`H".$key."`H".substr($text,strpos($text,$key)+1);
                if ($pop){
                    $quickkeys[$key]=popup($link.$extra);
                }else{
                    $quickkeys[$key]="window.location='$link$extra';";
                }
            }
            $nav.=templatereplace("navitem",array(
            "text"=>appoencode($text,$priv),
            "link"=>HTMLEntities2($link.$extra),
            "accesskey"=>$keyrep,
//            "popup"=>($pop==true ? "target='_blank' onClick=\"".popup($link.$extra)."; return false;\"" : ($conferma? "onClick=\"return confirm('Sei proprio sicuro di volerlo fare?');\"" : ""))
            "popup"=>($pop==true ? "target='_blank' onClick=\"".popup($link.$extra)."; return false;\"" : ($conferma? "onClick=\"return confirm('Sei proprio sicuro di volerlo fare?');\"" : ($nuova==true ? "target='_blank'" : "")))
            ));
            //$nav.="<a href=\"".HTMLEntities2($link.$extra)."\" $keyrep class='nav'>".appoencode($text,$priv)."<br></a>";
        }
        $session['allowednavs'][$link.$extra]=true;
        $session['allowednavs'][str_replace(" ", "%20", $link).$extra]=true;
        $session['allowednavs'][str_replace(" ", "+", $link).$extra]=true;
    }
}

function savesetting($settingname,$value){
    global $settings;
    loadsettings();
    if ($value>""){
        if (!isset($settings[$settingname])){
            $sql = "INSERT INTO settings (setting,value) VALUES (\"".addslashes($settingname)."\",\"".addslashes($value)."\")";
        }else{
            $sql = "UPDATE settings SET value=\"".addslashes($value)."\" WHERE setting=\"".addslashes($settingname)."\"";
        }
        db_query($sql) or die(db_error(LINK));
        $settings[$settingname]=$value;
        if (db_affected_rows()>0) return true; else return false;
    }
    return false;
}

function loadsettings(){
    global $settings;
    //as this seems to be a common complaint, examine the execution path of this function,
    //it will only load the settings once per page hit, in subsequent calls to this function,
    //$settings will be an array, thus this function will do nothing.
    if (!is_array($settings)){
        $settings=array();
        $sql = "SELECT * FROM settings";
        $result = db_query($sql) or die(db_error(LINK));
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $row = db_fetch_assoc($result);
            $settings[$row[setting]] = $row[value];
        }
        db_free_result($result);
        /*$ch=0;
        if ($ch=1 && strpos($_SERVER['SCRIPT_NAME'],"login.php")){
            //@file("http://www.mightye.org/logdserver?".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        }*/
    }
}

function getsetting($settingname,$default){
    global $settings;
    loadsettings();
    if (!isset($settings[$settingname])){
        savesetting($settingname,$default);
        return $default;
    }else{
        if (trim($settings[$settingname])=="") $settings[$settingname]=$default;
        return $settings[$settingname];
    }
}

function clearnav(){
    $session[allowednavs]=array();
}

function redirect($location,$reason=false){
    global $session,$REQUEST_URI;
    if ($location!="badnav.php"){
        $session[allowednavs]=array();
        addnav("",$location);
    }
    if (strpos($location,"badnav.php")===false) $session[output]="<a href=\"".HTMLEntities2($location)."\">Clicca qui.</a>";
    $session['debug'].="Reindirizzato a $location da $REQUEST_URI.  $reason\n";
    saveuser();
    header("Location: $location");
    //    echo $location;
    //    echo $session['debug'];
    exit();
}

function loadtemplate($templatename){
    if (!file_exists("templates/$templatename") || $templatename=="") $templatename="yarbrough.htm";
    $fulltemplate = join("",file("templates/$templatename"));
    $fulltemplate = split("<!--!",$fulltemplate);
    while (list($key,$val)=each($fulltemplate)){
        $fieldname=substr($val,0,strpos($val,"-->"));
        if ($fieldname!=""){
            $template[$fieldname]=substr($val,strpos($val,"-->")+3);
        }
    }
    return $template;
}

function maillink(){
    global $session;
    /* Luoghi dove viene visualizzato il numero di mail:
    // Drago Verde,Giardini,Grotta di Karnak,Torre dei Maghi,Maniero Burocratico,Terra delle Ombre
    // Terre dei Draghi,Piazza del Villaggio,Chiesa di Sgrios,Piazza GDR del Villaggio
    if (
       ($session['user']['locazione'] == 118
       OR $session['user']['locazione'] == 132
       OR $session['user']['locazione'] == 143
       OR $session['user']['locazione'] == 146
       OR $session['user']['locazione'] == 148
       OR $session['user']['locazione'] == 173
       OR $session['user']['locazione'] == 181
       OR $session['user']['locazione'] == 187
       OR $session['user']['locazione'] == 195
       OR $session['user']['locazione'] == 196)
       OR $session['user']['superuser'] > 1
       ) { */
    $sql = "SELECT sum(if(seen=1,1,0)) AS seencount, sum(if(seen=0,1,0)) AS notseen FROM mail WHERE msgto=\"".$session[user][acctid]."\"";
    $result = db_query($sql) or die(mysql_error(LINK));
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $row['seencount']=(int)$row['seencount'];
    $row['notseen']=(int)$row['notseen'];
    //}
    if ($row['notseen']>0){
        return "<a href='mail.php' target='_blank' onClick=\"".popup("mail.php","550x400").";return false;\" class='hotmotd'>La tua Mail: $row[notseen] nuovi, $row[seencount] vecchi</a>";
    }else{
        return "<a href='mail.php' target='_blank' onClick=\"".popup("mail.php","550x400").";return false;\" class='motd'>La Tua Mail: $row[notseen] nuovi, $row[seencount] vecchi</a>";
    }
}

function motdlink(){
    // missing $session caused unread motd's to never highlight the link
    global $session;
    if ($session['needtoviewmotd']){
        return "<a href='motd.php' target='_blank' onClick=\"".popup("motd.php").";return false;\" class='hotmotd'><b>MoTD</b></a>";
    }else{
        return "<a href='motd.php' target='_blank' onClick=\"".popup("motd.php").";return false;\" class='motd'><b>MoTD</b></a>";
    }
}

function page_header($title="La leggenda del Drago Verde"){
    global $header,$SCRIPT_NAME,$session,$template;
    $nopopups["login.php"]=1;
    $nopopups["loginback.php"]=1;
    $nopopups["motd.php"]=1;
    $nopopups["index.php"]=1;
    $nopopups["index_backdoor.php"]=1;
    $nopopups["create.php"]=1;
    $nopopups["hints.php"]=1;
    //$nopopups["about.php"]=1;
    $nopopups["mail.php"]=1;
    $nopopups["limite.php"]=1;
    $nopopups["newfaq.php"]=1;
    $nopopups["faqplayer.php"]=1;
    $nopopups["termini.php"]=1;
    $nopopups["regolamento.php"]=1;

    $header = $template['header'];
    $sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    if (($row['motddate']>$session['user']['lastmotd']) && $nopopups[$SCRIPT_NAME]!=1 && $session['user']['loggedin']){
        $header=str_replace("{headscript}","<script language='JavaScript'>".popup("motd.php")."</script>",$header);
        $session['needtoviewmotd']=true;
    }else{
        $header=str_replace("{headscript}","",$header);
        $session['needtoviewmotd']=false;
    }
    $header=str_replace("{title}",$title,$header);
}

function popup($page,$size="550x300"){
    $s = split("x",$size);
    return "window.open('$page','".preg_replace("([^[:alnum:]])","",$page)."','scrollbars=yes,resizable=yes,width={$s[0]},height={$s[1]}')";
}

function page_footer(){
    global $output,$nestedtags,$header,$nav,$session,$REMOTE_ADDR,$REQUEST_URI,$pagestarttime,$quickkeys,$template,$logd_version,$dbqueriesthishit;
    //    $session['user']['lupin']= serialize($session['user']['ladro']);
    unset($session['user']['ladro']);
    while (list($key,$val)=each($nestedtags)){
        $output.="</$key>";

        unset($nestedtags[$key]);
    }
    $script.="<script language='JavaScript'>
    <!--
    function visualizza(id){
      if (document.getElementById){
        if(document.getElementById(id).style.display == 'none'){
          document.getElementById(id).style.display = 'block';
        }else{
          document.getElementById(id).style.display = 'none';
        }
      }
    }

    document.onkeypress=keyevent;
    function keyevent(e){
        var c;
        var target;
        var altKey;
        var ctrlKey;
        if (window.event != null) {
            c=String.fromCharCode(window.event.keyCode).toUpperCase();
            altKey=window.event.altKey;
            ctrlKey=window.event.ctrlKey;
        }else{
            c=String.fromCharCode(e.charCode).toUpperCase();
            altKey=e.altKey;
            ctrlKey=e.ctrlKey;
        }
        if (window.event != null)
            target=window.event.srcElement;
        else
            target=e.originalTarget;
        if (target.nodeName.toUpperCase()=='INPUT' || target.nodeName.toUpperCase()=='TEXTAREA' || altKey || ctrlKey){
        }else{";
    reset($quickkeys);
    while (list($key,$val)=each($quickkeys)){
        $script.="\n            if (c == '".strtoupper($key)."') { $val; return false; }";
    }
    $script.="
        }
    }
    //-->
    </script>";


    $footer = $template['footer'];
    if (strpos($footer,"{paypal}") || strpos($header,"{paypal}")){ $palreplace="{paypal}"; }else{ $palreplace="{stats}"; }


        $sql="SELECT name, superuser FROM accounts
              WHERE locked=0
              AND stealth=0
              AND loggedin=1
              AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."'
              ORDER BY dragonkills DESC, level DESC, name ASC";
        $result = db_query($sql) or die(sql_error($sql));
        if (db_num_rows($result) == 0) {
            $retp=("`0`iNessuno`i`n");
            $retm=("`0`iNessuno`i`n");
            $reta=("`0`iNessuno`i`n");
        }else{
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
                $row = db_fetch_assoc($result);
                if ($row['superuser'] < 2){
                    $retp.=("`^".$row['name']."`n");
                    $onlinecount++;
                    $onlinecount3++;
                }elseif ($row['superuser'] == 2){
                    $retm.=("`^".$row['name']."`n");
                    $onlinecount1++;
                    $onlinecount3++;
                }else{
                    $reta.=("`^".$row['name']."`n");
                    $onlinecount2++;
                    $onlinecount3++;
                }
            }
            if ($onlinecount == 0) $retp=("`0`iNessuno`i`n");
            if ($onlinecount1 == 0) $retm=("`0`iNessuno`i`n");
            if ($onlinecount2 == 0) $reta=("`0`iNessuno`i`n");
        }
        db_free_result($result);
        if ($onlinecount3==1) {
            $sing1="o";
            $sing2="e";
        }else {
            $sing1="i";
            $sing2="i";
        }
        if ($onlinecount3==0) $ret.=appoencode("`i`\$Nessuno`i`n");
        $ret.=appoencode("`b`@".$onlinecount3." Utent".$sing2." collegat".$sing1."`b`n");
        $ret.=appoencode("`b`s".$onlinecount." Player:`b`n".$retp);
        $ret.=appoencode("`b`a".$onlinecount1." Mod:`b`n".$retm);
        $ret.=appoencode("`b`S".$onlinecount2." Admin:`b`n".$reta);
        $session['utenti'] = $onlinecount3;
        if ($onlinecount3 > getsetting("maxuserconnected",0)) savesetting("maxuserconnected",$onlinecount3);
        $ret.=appoencode("`@`bN. max utenti collegati`ncontemporaneamente`ndi sempre: ".getsetting("maxuserconnected",0)."`b`n");

        //Fasi Lunari
        $ret.="<br><b><font color='#FFFF00'>Fase lunare odierna:</b></font>";
        $ret.="<br><b><font color='#FFFF00'>".getmoonphase_name()."</b></font>";
        $ret.="<br><font color='#FFFF00'><img src=\"images/luna/".getmoonphase_picture()."\" width=50 height=50></font>";
        //Fine Fasi Lunari

        if ($session[loggedin]){
           $header=str_replace("{useronline}",$ret,$header);
           $footer=str_replace("{useronline}",$ret,$footer);
        }else{
          $header=str_replace("{useronline}","",$header);
          $footer=str_replace("{useronline}","",$footer);
        }
        $header=str_replace("{forum}","<a href='http://www.ogsi.it/modules/newbb/' target=\"_blank\">Forum OGSI</a>",$header);
        $footer=str_replace("{forum}","<a href='http://www.ogsi.it/modules/newbb/' target=\"_blank\">Forum OGSI</a>",$footer);
        $header=str_replace("{copyright}","",$header);
        $footer=str_replace("{copyright}","",$footer);
        $header=str_replace("{chat}","",$header);
        $footer=str_replace("{chat}","",$footer);

    //NOTICE
    //NOTICE Although I will not deny you the ability to remove the below paypal link, I do request, as the author of this software
    //NOTICE that you leave it in.
    //NOTICE
    $paypalstr = '<table align="center"><tr><td>';
    //$paypalstr .= '<a href="http://www.ogsi.it/modules/newbb/viewtopic.php?topic_id=2413&forum=8&post_id=45019#forumpost45019" rel="nofollow">
    //<img src="http://www.ogsi.it/uploads/img5359e40ba1976.png" title="Accettiamo BitCoin" style="width:100px; height:50px"></a></td></tr><tr><td>';
    $paypalstr .= '<center><table><tr><td colspan="2">
    
</center></td></tr><tr><td>';
    
    
    $paypalstr .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="nahdude81@hotmail.com">
<input type="hidden" name="item_name" value="Legend of the Green Dragon Author Donation from '.preg_replace("/[`]./","",$session['user']['name']).'">
<input type="hidden" name="item_number" value="'.HTMLEntities2($session['user']['login']).":".$_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI'].'">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="notify_url" value="http://lotgd.net/payment.php">
<input type="hidden" name="cn" value="Your Character Name">
<input type="hidden" name="cs" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="image" src="images/paypal1.gif" border="0" name="submit" alt="Donate!">
</form>';
    $paysite = getsetting("paypalemail", "");
    if ($paysite != "") {
        $paypalstr .= '</td><td>';
        $paypalstr .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
        <input type="hidden" name="cmd" value="_xclick">
        <input type="hidden" name="business" value="'.$paysite.'">
<input type="hidden" name="item_name" value="Legend of the Green Dragon Site Donation from '.preg_replace("/[`]./","",$session['user']['name']).'">
<input type="hidden" name="item_number" value="'.HTMLEntities2($session['user']['login']).":".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].'">
<input type="hidden" name="no_shipping" value="1">';
        //Excalibur: aggiunta per risposta da paypal
        if (file_exists("payment.php")) {
            $paypalstr .= '<input type="hidden" name="notify_url" value="http://'.$_SERVER["HTTP_HOST"].dirname($_SERVER['REQUEST_URI']).'/payment.php">';
        }
        //Excalibur: fine risposta paypal
        $paypalstr .= '<input type="hidden" name="cn" value="Your Character Name">
<input type="hidden" name="cs" value="1">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="tax" value="0">
<input type="image" src="images/paypal2.gif" border="0" name="submit" alt="Donate!">
</form>';
    }
    $paypalstr .= '</td></tr></table>';
    $paypalstr .= '</td></tr></table>';

    /*
    Original code for 1.0.x by Danilo Stern-Sapad and Nicholas Moline
    0.97 version by Excalibur (www.ogsi.it/logd)
    */
    $donatetext = getsetting("donatetext","<b><font color=yellow>Grazie per aiutarci a pagare<br>i costi del server e per<br>incoraggiare gli sviluppatori!</b></font>"); // Text to display explaining donations,text
    $need = getsetting("monthlygoal", 40); // Amount Needed
    $color_bar = "269A26"; // What color do you want for the bar?
    $bg_bar = "777777";  // What color for the remainder of the goal?
    $text = "Obiettivo Mensile";
    //text = "Obiettivo Mensile:<br>€";
        
    $sql = "SELECT substring(processdate,1,7) AS month, sum(amount)-sum(txfee) AS profit FROM paylog GROUP BY month DESC LIMIT 1";
    $result = db_query($sql);
    $needed = $need;
    while ($row = db_fetch_assoc($result)) {
        $have = $row['profit'];
        $month = $row['month'];
    }
    if($month != date("Y-m"))$have = 0;
    $color = $color_bar;
    $bgcolor = $bg_bar;
    if ($have >= $needed) {
        $percent = 100;
        $roundpercent = 100;
    } elseif ($have == 0) {
        $percent = 0;
        $roundpercent = 0;
    } else {
        $percent = $have / $needed * 100;
        $roundpercent = ceil($percent);
    }
    $nonpercent = 100 - $roundpercent;
    /*$display .= "<center><small>" . $donatetext . "</small></center><br /><dt><center>
    <table style='border: solid 1px #000000;' bgcolor='" . $bgcolor . "' cellpadding='0' cellspacing='0' width='100' height='10'>
    <tr><td width='$roundpercent' bgcolor='$color'></td><td width='$nonpercent'></td></tr></table></center><br><center><small>" . $text . $have . $text1 . $needed . " (" . $roundpercent . "%)</small></center></dt>";*/
    $display .= "<center><small>" . $donatetext . "</small></center><br /><dt><center>
    <table style='border: solid 1px #000000;' bgcolor='" . $bgcolor . "' cellpadding='0' cellspacing='0' width='100' height='10'>
    <tr><td width='$roundpercent' bgcolor='$color'></td><td width='$nonpercent'></td></tr></table></center><br><center><small>".$text." (" . $roundpercent . "%)</small></center></dt>";
    $paypalstr.=$display;
    //Excalibur: fine codice per PayPal Bar

    $footer=str_replace($palreplace,(strpos($palreplace,"paypal")?"":"{stats}").$paypalstr,$footer);
    $header=str_replace($palreplace,(strpos($palreplace,"paypal")?"":"{stats}").$paypalstr,$header);
    //NOTICE
    //NOTICE Although I will not deny you the ability to remove the above paypal link, I do request, as the author of this software
    //NOTICE that you leave it in.
    //NOTICE
    $header=str_replace("{nav}",$nav,$header);
    $footer=str_replace("{nav}",$nav,$footer);

    $header = str_replace("{motd}", motdlink(), $header);
    $footer = str_replace("{motd}", motdlink(), $footer);

    if ($session['user']['acctid']>0) {
        $header=str_replace("{mail}",maillink(),$header);
        $footer=str_replace("{mail}",maillink(),$footer);
    }else{
        $header=str_replace("{mail}","",$header);
        $footer=str_replace("{mail}","",$footer);
    }
    $header=str_replace("{petition}","<a href='petition.php' onClick=\"".popup("petition.php").";return false;\" target='_blank' align='right' class='motd'>Richiesta d'Aiuto</a>",$header);
    $footer=str_replace("{petition}","<a href='petition.php' onClick=\"".popup("petition.php").";return false;\" target='_blank' align='right' class='motd'>Richiesta d'Aiuto</a>",$footer);
    if ($session['user']['superuser']>1){
        $sql = "SELECT count(petitionid) AS c,status FROM petitions GROUP BY status";
        $result = db_query($sql);
        $petitions=array(0=>0,1=>0,2=>0);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $row = db_fetch_assoc($result);
            $petitions[(int)$row['status']] = $row['c'];
        }
        db_free_result($result);
        $footer = "<table border='0' cellpadding='5' cellspacing='0' align='right'><tr><td><b>Petizioni:</b> $petitions[0] Non Lette, $petitions[1] Lette, $petitions[2] Chiuse.</td></tr></table>".$footer;
    }
    $footer=str_replace("{stats}",charstats(),$footer);
    $header=str_replace("{stats}",charstats(),$header);
    $header=str_replace("{script}",$script,$header);
    $footer=str_replace("{source}","<a href='source.php?url=".preg_replace("/[?].*/","",($_SERVER['REQUEST_URI']))."' target='_blank'>View PHP Source</a><script type='text/javascript' src='./templates/wz_tooltip.js'></script>",$footer);
    $header=str_replace("{source}","<a href='source.php?url=".preg_replace("/[?].*/","",($_SERVER['REQUEST_URI']))."' target='_blank'>View PHP Source</a>",$header);
    //$footer=str_replace("{source}","<a href='http://sourceforge.net/projects/lotgd' target='_blank'>View PHP Source</a>",$footer);
    //$header=str_replace("{source}","<a href='http://sourceforge.net/projects/lotgd' target='_blank'>View PHP Source</a>",$header);
    //$footer=str_replace("{copyright}","Copyright 2002-2003, Game: Eric Stevens",$footer);

/*    if(mt_rand(1,10)==1 AND $session[user][euro]<1){
        output('<div id="banner1" style="text-align:Center;position:absolute;top:0px;left:20%;">
    <script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
//2007-05-15: Logd_468x60_top_random
google_ad_channel = "0753036226";
google_color_border = "6F3C1B";
google_color_bg = "78B749";
google_color_link = "6F3C1B";
google_color_text = "063E3F";
google_color_url = "000000";
//-->
</script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
    </div>',true);
    }
*/
    $footer=str_replace("{version}", "Version: $logd_version", $footer);
    $gentime = getmicrotime()-$pagestarttime;
    $session['user']['gentime']+=$gentime;
    $session['user']['gentimecount']++;
    //luke logout automatico superati i click consentiti
    $session['user']['click_limit']++;
    if($session['user']['click_limit']>980 AND $session['user']['click_limit']<=1000){
        echo("<script language='JavaScript'>".popup("limite.php")."</script>");
    }
    if($session['user']['click_limit']>1000){
        if ($session['user']['loggedin']){
            //$logout = date("Y-m-d H:i:s");
            $sql = "INSERT INTO furbetti (type,acctid,logintime,logouttime) VALUES ('click','".$session['user']['acctid']."','".$session['user']['lastlogin']."','".date("Y-m-d H:i:s")."')";
            db_query($sql) or die(sql_error($sql));
            debuglog("è stato disconnesso per raggiunto limite di click");
            $session['user']['loggedin'] = 0;
            $session['user']['location'] = 0;
            $session['user']['locazione'] = 0;
            saveuser();
            $sql = "UPDATE accounts SET loggedin=0 WHERE acctid = ".$session['user']['acctid'];
            db_query($sql) or die(sql_error($sql));
        }
        $session=array();
        redirect("index.php");
    }
    if ($session['user']['superuser'] > 2){
       $footer=str_replace("{pagegen}","Page gen: ".round($gentime,2)."s, Click: ".$session['user']['click_limit']." ,Q:".$dbqueriesthishit,$footer);
    }else{
       $footer=str_replace("{pagegen}","Page gen: ".round($gentime,2)."s, Click: ".$session['user']['click_limit'],$footer);
    }
    //$footer=str_replace("{pagegen}","Page gen: ".round($gentime,2)."s, Ave: ".round($session[user][gentime]/$session[user][gentimecount],2)."s - ".round($session[user][gentime],2)."/".round($session[user][gentimecount],2),$footer);
    //$footer .= "<!--Inizio phpstats -->
    //<script type=\"text/javascript\" src=\"http://www.ogsi.it/logd/stats/php-stats.js.php\"></script>
    //<!--fine phpstats -->";
    if (strpos($_SERVER['HTTP_HOST'],"lotgd.net")!==false){
        $footer=str_replace(
        "</html>",
        '<script language="JavaScript" type="text/JavaScript" src="http://www.reinvigorate.net/archive/app.bin/jsinclude.php?5193"></script></html>',
        $footer
        );
    }

    $output=$header.$output.$footer;
    $session['user']['gensize']+=strlen($output);
    $session[output]=$output;
    $currentpage=$_SERVER['REQUEST_URI'];
    if (strstr($currentpage, "?op") !=""){
        $position=strrpos($currentpage,"?op");
        $currentpage=substr($currentpage,0,$position);
    }
    $currentpage=str_replace("/logd/","",$currentpage);
    if ($currentpage != "usepotion.php" or $currentpage != "usechow.php"){
        $session['user']['pqrestorepage']=$currentpage;
    }
    saveuser();

    session_write_close();
    //`mpg123 -g 100 -q hit.mp3 2>&1 > /dev/null`;
    echo $output;
    exit();
}

function popup_header($title="La Leggenda del Drago Verde"){
    global $header;
    $header.="<html><head><title>$title</title>";
    $header.="<link href=\"newstyle.css\" rel=\"stylesheet\" type=\"text/css\">";
    
    $header.="</head><body bgcolor='#000000' text='#CCCCCC'><table cellpadding=5 cellspacing=0 width='100%'>";
    $header.="<tr><td class='popupheader'><b>$title</b></td></tr>";
    $header.="<tr><td valign='top' width='100%'>";
}

function popup_footer(){
    global $output,$nestedtags,$header,$nav,$session;
    while (list($key,$val)=each($nestedtags)){
        $output.="</$key>";
        unset($nestedtags[$key]);
    }
    $output.="</td></tr><tr><td bgcolor='#330000' align='center'>Copyright 2002, Eric Stevens</td></tr></table></body></html>";
    $output=$header.$output;
    //$session[output]=$output;

    saveuser();
    echo $output;
    exit();
}

function clearoutput(){
    global $output,$nestedtags,$header,$nav,$session;
    $session[allowednavs]="";
    $output="";
    unset($nestedtags);
    $header="";
    $nav="";
}

function soap($input){
    if (getsetting("soap",1)){
        $sql = "SELECT * FROM nastywords";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $search = $row['words'];
        $search = str_replace("a",'[a4@]',$search);
        $search = str_replace("l",'[l1!]',$search);
        $search = str_replace("i",'[li1!]',$search);
        $search = str_replace("e",'[e3]',$search);
        $search = str_replace("t",'[t7+]',$search);
        $search = str_replace("o",'[o0]',$search);
        $search = str_replace("s",'[sz$]',$search);
        $search = str_replace("k",'c',$search);
        $search = str_replace("c",'[c(k]',$search);
        $start = "'(\s|\A)";
        $end = "(\s|\Z)'iU";
        $search = str_replace("*","([[:alnum:]]*)",$search);
        $search = str_replace(" ","$end $start", $search);
        $search = "$start".$search."$end";
        //echo $search;
        $search = split(" ",$search);
        //$input = " $input ";

        return preg_replace($search,"\\1`i$@#%`i\\2",$input);
    }else{
        return $input;
    }
}

function saveuser(){
    global $session,$dbqueriesthishit,$sessionbackup;
    //  $cmd = date("Y-m-d H:i:s")." $dbqueriesthishit ".$_SERVER['REQUEST_URI'];
    //  @exec("echo $cmd >> /home/groups/l/lo/lotgd/sessiondata/data/queryusage-".$session['user']['login'].".txt");
    if ($session['loggedin'] && $session['user']['acctid']!=""){
        $session['user']['output']=$session['output'];
        $session['user']['allowednavs']=serialize($session['allowednavs']);
        $session['user']['bufflist']=serialize($session['bufflist']);
        if (is_array($session['user']['prefs'])) $session['user']['prefs']=serialize($session['user']['prefs']);
        if (is_array($session['user']['dragonpoints'])) $session['user']['dragonpoints']=serialize($session['user']['dragonpoints']);
        //Aggiunta Sook (punti drago precedenti alla reincarnazione)
        if (is_array($session['user']['olddp'])) $session['user']['olddp']=serialize($session['user']['olddp']);
        // Aggiunta da Excalibur
        if (is_array($session['user']['torneopoints'])) $session['user']['torneopoints']=serialize($session['user']['torneopoints']);
        // Fine aggiunta by Excalibur
        // Aggiunta Xtramus Manicomio
        if (is_array($session['user']['lupin'])) $session['user']['lupin']=serialize($session['user']['lupin']);
        //$session[user][laston] = date("Y-m-d H:i:s");
        $sql="UPDATE accounts SET ";
        reset($session['user']);
        while(list($key,$val)=each($session['user'])){
            if($val==$sessionbackup[$key]) {
                 continue;
            }
            if (is_array($val)){
                 $sql.="$key='".addslashes(serialize($val))."', ";
            }else{
                 $sql.="$key='".addslashes($val)."', ";
            }
        }
        $sql = substr($sql,0,strlen($sql)-2);
        if(strlen($sql)!=18) {
           $sql.=" WHERE acctid = ".$session['user']['acctid'];
           db_query($sql);
        }
    }
}

function createstring($array){
    if (is_array($array)){
        reset($array);
        while (list($key,$val)=each($array)){
            $output.=rawurlencode( rawurlencode($key)."\"".rawurlencode($val) )."\"";
        }
        $output=substr($output,0,strlen($output)-1);
    }
    return $output;
}

function createarray($string){
    $arr1 = split("\"",$string);
    $output = array();
    while (list($key,$val)=each($arr1)){
        $arr2=split("\"",rawurldecode($val));
        $output[rawurldecode($arr2[0])] = rawurldecode($arr2[1]);
    }
    return $output;
}

function output_array($array,$prefix=""){
    while (list($key,$val)=@each($array)){
        $output.=$prefix."[$key] = ";
        if (is_array($val)){
            $output.="array{\n".output_array($val,$prefix."[$key]")."\n}\n";
        }else{
            $output.=$val."\n";
        }
    }
    return $output;
}

function dump_item($item){
    $output = "";
    if (is_array($item)) $temp = $item;
    else $temp = unserialize($item);
    if (is_array($temp)) {
        $output .= "array(" . count($temp) . ") {<blockquote>";
        while(list($key, $val) = @each($temp)) {
            $output .= "'$key' = '" . dump_item($val) . "'`n";
        }
        $output .= "</blockquote>}";
    } else {
        $output .= $item;
    }
    return $output;
}

function addnews($news){
    global $session;
    $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('".addslashes($news)."',NOW(),".$session[user][acctid].")";
    return db_query($sql) or die(db_error($link));
}

function checkday() {
    global $session,$revertsession,$REQUEST_URI;

    //Modifica Fame
    //check if they are starving
    if ($session['user']['alive']==1){
        if ($session['user']['hunger']>160) output("`4`c`b<big>Sei Affamat".($session[user][sex]?"a":"o")."!`b`c'</big>`n`0",true);
        if ($session['user']['hunger']>200){
            output("`4`c`b<big>Sei talmente affamat".($session[user][sex]?"a":"o")." che ti stai indebolendo!`b`c'</big>`n`0",true);
            $session['user']['hitpoints']*=.90;
        }
    }
    //Fine modifica fame

    //Modifica Medaglie
    if ($session['user']['medhunt']>0) {
        $session['user']['medhunt']++;
    }
    if (e_rand(1,100)>(100-$session['user']['medfind']) and $session['user']['alive']==1){
        if ($session['user']['medhunt']>0 and $session['user']['medhunt']<151 and $session['user']['medfind']>0){
            if ($session['user']['medallion']<5){
                output("`c`b`4<big><big><big><big>Hai Trovato una Medaglia!</big></big></big></big>`b`c`0",true);
                $session['user']['medallion']+=1;
                $session['user']['medfind']-=1;
            }else{
                output("`c`b`4<big><big>Hai Trovato una Medaglia!</big></big>`b`c",true);
                output("`c`b`4<big><big>Peccato che tu non possa portarne altre!</big></big>`b`c",true);
            }
        }
    }
    //Fine modifica medaglie

    //output("`#`iChecking to see if you're due for a new day: ".$session[user][laston].", ".date("Y-m-d H:i:s")."`i`n`0");
    if ($session['user']['loggedin']){
        output("<!--CheckNewDay()-->",true);
        if(is_new_day()){
            $session=$revertsession;
            $session[user][restorepage]=$REQUEST_URI;
            $session[allowednavs]=array();
            addnav("","newday.php");
            redirect("newday.php");
        }
    }
}

function is_new_day(){
    global $session;
    $t1 = gametime();
    $t2 = convertgametime(strtotime($session[user][lasthit]));
    $d1 = date("Y-m-d",$t1);
    $d2 = date("Y-m-d",$t2);
    if ($d1!=$d2){
        return true;
    }else{
        return false;
    }
}

function getgametime(){
    return date("g:i a",gametime());
}

function gametime(){
    // AGGIORNAMENTO PHP 5
    //$time = convertgametime(strtotime("now"));
    $time = convertgametime(strtotime(date("r")));
    return $time;
}

function convertgametime($intime){
   global $session;
   $multi = getsetting("daysperday",4);
   $offset = getsetting("gameoffsetseconds",0);
   $fixtime = mktime(0,0,0-$offset,date("m")-$multi,date("d"),date("Y"));
   $time=$multi*(strtotime(date("Y-m-d H:i:s",$intime))-$fixtime);
   $time=strtotime(date("Y-m-d H:i:s",$time)."+".($multi*date("I",$intime))." hour");
   $time=strtotime(date("Y-m-d H:i:s",$time)."-".date("I",$time). " hour");
   $time=strtotime(date("Y-m-d H:i:s",$time)."+".(23-$multi)." hour");
   return $time;
}

function sql_error($sql){
    global $session;
    return output_array($session)."SQL = <pre>$sql</pre>".db_error(LINK);
}

function addcommentary() {
    global $_POST,$_GET,$HTTP_POST_VARS,$session,$REQUEST_URI,$HTTP_GET_VARS,$doublepost,$stanzachiusa;
    if ( ($session['user']['nocomment'] < 1 AND $stanzachiusa!="1") OR $session['user']['superuser']>0) {
        $doublepost=0;
        if ((int)getsetting("expirecontent",180)>0 AND date("H") > 4 AND date("H") < 6){
            $sql = "SELECT nome FROM stanze WHERE tipo='gdr'";
            $result = db_query($sql);
            $countrow = db_num_rows($result);
            for ($i=0; $i<$countrow; $i++){
                $row = db_fetch_assoc($result);
                $nomeescluso .= " AND section <> '".addslashes($row['nome'])."'";
                if ($i == 0){
                    $nomeincluso = "section = '".addslashes($row['nome'])."'";
                }else{
                    $nomeincluso .= " OR section = '".addslashes($row['nome'])."'";
                }
            }
            $sql = "DELETE FROM commentary
                    WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'
                    ".$nomeescluso." AND section NOT LIKE '%pet-%'";
            db_query($sql);
            //echo $sql."<br>";
            if ($countrow <> 0){
                $sql = "DELETE FROM commentary
                        WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontentgdr",180)." days"))."'
                        AND ( ".$nomeincluso." ) AND section NOT LIKE '%pet-%'";
                db_query($sql);
            }
            //echo $sql."<br>";
        }
        $section=$_POST['section'];
        $talkline=$_POST['talkline'];
        if ($_POST['insertcommentary'][$section]!==NULL &&
        trim($_POST['insertcommentary'][$section])!="") {
            $commentary = str_replace("`n","",soap($_POST['insertcommentary'][$section]));
            //Excalibur: per non consentire ai player di usare testi con sfondo colorato
            if ($session['user']['superuser'] < 2){
               $check = array("`a", "`A", "`s", "`S");
               $commentary = str_replace($check,"`#",$commentary);
            }
            //Excalibur: fine blocco sfondi colorati
            $y = strlen($commentary);
            for ($x=0;$x<$y;$x++){
                if (substr($commentary,$x,1)=="`"){
                    $colorcount++;
                    if ($colorcount>=getsetting("maxcolors",10)){
                        $commentary = substr($commentary,0,$x).preg_replace("'[`].'","",substr($commentary,$x));
                        $x=$y;
                    }
                    $x++;
                }
            }
            if (substr($commentary,0,1)!=":" &&
            substr($commentary,0,2)!="::" &&
            substr($commentary,0,3)!="/me" &&
            $session['user']['drunkenness']>0) {
                //drunk people shouldn't talk very straight.
                $straight = $commentary;
                $replacements=0;
                while ($replacements/strlen($straight) < ($session['user']['drunkenness'])/500 ){
                    $slurs = array("a"=>"aa","e"=>"ee","f"=>"ff","h"=>"hh","i"=>"iy","l"=>"ll","m"=>"mm","n"=>"nn","o"=>"oo","r"=>"rr","s"=>"sh","u"=>"oo","v"=>"vv","w"=>"ww","y"=>"yy","z"=>"zz");
                    if (e_rand(0,9)) {
                        srand(e_rand());
                        $letter = array_rand($slurs);
                        $x = strpos(strtolower($commentary),$letter);
                        if ($x!==false &&
                        substr($comentary,$x,5)!="*hic*" &&
                        substr($commentary,max($x-1,0),5)!="*hic*" &&
                        substr($commentary,max($x-2,0),5)!="*hic*" &&
                        substr($commentary,max($x-3,0),5)!="*hic*" &&
                        substr($commentary,max($x-4,0),5)!="*hic*"
                        ){
                            if (substr($commentary,$x,1)<>strtolower($letter)) $slurs[$letter] = strtoupper($slurs[$letter]); else $slurs[$letter] = strtolower($slurs[$letter]);
                            $commentary = substr($commentary,0,$x).$slurs[$letter].substr($commentary,$x+1);
                            $replacements++;
                        }
                    }else{
                        $x = e_rand(0,strlen($commentary));
                        if (substr($commentary,$x,5)=="*hic*") {$x+=5; } //output("moved 5 to $x ");
                        if (substr($commentary,max($x-1,0),5)=="*hic*") {$x+=4; } //output("moved 4 to $x ");
                        if (substr($commentary,max($x-2,0),5)=="*hic*") {$x+=3; } //output("moved 3 to $x ");
                        if (substr($commentary,max($x-3,0),5)=="*hic*") {$x+=2; } //output("moved 2 to $x ");
                        if (substr($commentary,max($x-4,0),5)=="*hic*") {$x+=1; } //output("moved 1 to $x ");
                        $commentary = substr($commentary,0,$x)."*hic*".substr($commentary,$x);
                        //output($commentary."`n");
                        $replacements++;
                    }//end if
                }//end while
                //output("$replacements replacements (".($replacements/strlen($straight)).")`n");
                while (strpos($commentary,"*hic**hic*"))
                $commentary = str_replace("*hic**hic*","*hic*hic*",$commentary);
            }//end if
            $commentary = preg_replace("'([^[:space:]]{45,45})([^[:space:]])'","\\1 \\2",$commentary);
            if ($session['user']['drunkenness']>50) $talkline = "barcollando $talkline";
            //$talkline = translate($talkline);

            if ($talkline!="dice" // do an emote if the area has a custom talkline and the user isn't trying to emote already.
            && substr($commentary,0,1)!=":"
            && substr($commentary,0,2)!="::"
            && substr($commentary,0,3)!="/me")
            $commentary = ":`3$talkline, \\\"`#$commentary`3\\\"";
            $sql = "SELECT commentary.comment,commentary.author FROM commentary WHERE section='$section' ORDER BY commentid DESC LIMIT 1";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            db_free_result($result);
            if ($row['comment']!=$commentary || $row['author']!=$session['user']['acctid']){
                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$section',".$session['user']['acctid'].",\"$commentary\")";
//                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES ('".date("Y-m-d H:i:s")."','$section',".$session['user']['acctid'].",\"$commentary\")";
                db_query($sql) or die(db_error(LINK));
            } else {
                $doublepost = 1;
            }
        }
    }elseif($stanzachiusa == "1" AND $session['user']['superuser']<2){
        output("`7Questa stanza è stata bloccata dagli admin, non ti è possibile scrivere.`0`n");
    }else{
        output("`\$`c`b<big>Gli admin hanno deciso che non ti è concesso parlare nelle pubbliche piazze.</big>`n`SDevi scontare ancora ".$session['user']['nocomment']." giorni di punizione`0`b`c`n",true);
    }
}

function viewcommentary($section,$message="Inserisci i tuoi commenti",$limit=10,$limitk=5,$talkline="dice",$climit="0") {
    global $_POST,$_GET,$HTTP_POST_VARS,$session,$REQUEST_URI,$HTTP_GET_VARS, $doublepost, $stanzachiusa;
    //Excalibur: Modifica per chat collassabili
    if ($_GET['chat'] != null){
       if ($_GET['chat'] == 1 AND $session['chat'] == 0){
          $session['chat'] = 1;
       }
       if ($_GET['chat'] == 0 AND $session['chat'] == 1){
          $session['chat'] = 0;
       }
    }
    if ($session['chat'] == 0){
       $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
       $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
       if (strpos($linkreturn,"?") === false){
          $linkreturn .="?chat=1";
       }else{
          $lenght = strlen($linkreturn);
          $position = strpos($linkreturn,"?");
          $linkreturn = substr($linkreturn, 0,$position)."?chat=1";
       }
       if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
          output("<a href='".$linkreturn."'><img src='./images/minus.gif' border='0' valign='bottom' style='height: 9px;'>
          </a> `SNascondi Chat`n`@",true);
          addnav("",$linkreturn);
       }
       $nobios = array("motd.php"=>true);
       if ($nobios[basename($_SERVER['SCRIPT_NAME'])]) $linkbios=false; else $linkbios=true;
       //output("`b".basename($_SERVER['SCRIPT_NAME'])."`b`n");
       if ($section != "motd"){
           output("Sono presenti: ");
           $sqlloc = "SELECT login,name FROM accounts WHERE locazione = ".$session['user']['locazione'];
           if ($session['user']['locazione'] == 194){
               $sqlloc.= " AND casa = ".$session['user']['casa'];
           }
           $sqlloc.= " AND laston>'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))."'
                       AND superuser = 0 AND stealth = 0
                       ORDER BY reincarna, dragonkills, level DESC";
           $resultloc = db_query($sqlloc) or die(db_error(LINK));
           $countrow1 = db_num_rows($resultloc);
           for ($ij=0; $ij<$countrow1; $ij++){
               $rowloc = db_fetch_assoc($resultloc);
               output("<a href=\"mail.php?op=write&to=".$rowloc['login']."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=$rowloc[login]").";return false;\">
               <img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
               output("`0<a href='bio.php?char=".rawurlencode($rowloc['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI'])."'>",true);
               addnav("","bio.php?char=".rawurlencode($rowloc['login'])."&ret=".URLEncode($_SERVER['REQUEST_URI']));
               output("`^".$rowloc['name']);
               output("</a>",true);
           }
           output("`n`n");
       }
       //if ($doublepost) output("`\$`bDoppio post?`b`0`n");
       //$message = translate($message);
       if ((int)getsetting("expirecontent",180)>0 AND date("H") > 5 AND date("H") < 7){
           $sql = "DELETE FROM commentary WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'";
           db_query($sql);
           $sql = "DELETE FROM commentdeleted WHERE postdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." days"))."'";
           db_query($sql);
       }
       $com=(int)$_GET[comscroll];
       $sql = "SELECT commentary.*,
                      accounts.name,
                      accounts.login
                      ,accounts.loggedin,
                      accounts.location,
                      accounts.laston
                 FROM commentary
                INNER JOIN accounts
                   ON accounts.acctid = commentary.author
                WHERE section = '$section'
                  AND accounts.locked=0
                ORDER BY commentid DESC
                LIMIT ".($com*$limit).",$limit";
       $result = db_query($sql) or die(db_error(LINK));
       $counttoday=0;
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
           $row = db_fetch_assoc($result);
           if (substr($row['postdate'],0,10) == date("Y-m-d")){
              $display = "Scritto oggi alle ".substr($row['postdate'],10);
           }elseif (substr($row['postdate'],0,10) == date(("Y-m-d"),strtotime(date("r")."-1 day"))){
              $display = "Scritto ieri alle ".substr($row['postdate'],10);
           }else{
              $display = "Scritto il ".date("d-M-Y",strtotime($row['postdate']))." alle ".substr($row['postdate'],10);
           }
           $row[comment]=preg_replace("'[`][^123456789!@#$%^&()vVxXfFgGrRaAsSqQjJeEp]'","",$row['comment']);
           $commentids[$i] = $row['commentid'];
           if (date("Y-m-d",strtotime($row['postdate']))==date("Y-m-d")){
               if (strpos($section,"salariunioni")===false) {
                   if ($row['name']==$session['user']['name'] && $climit=="0" && $session['user']['npg']==0) $counttoday++;
               }
           }
           $x=0;
           $ft="";
           for ($x=0;strlen($ft)<3 && $x<strlen($row['comment']);$x++){
               if (substr($row['comment'],$x,1)=="`" && strlen($ft)==0) {
                   $x++;
               }else{
                   $ft.=substr($row['comment'],$x,1);
               }
           }
           $link = "bio.php?char=".rawurlencode($row['login']) . "&ret=".URLEncode($_SERVER['REQUEST_URI']);
           if (substr($ft,0,2)=="::") $ft = substr($ft,0,2);
           else
           if (substr($ft,0,1)==":") $ft = substr($ft,0,1);
           if ($ft=="::" || $ft=="/me" || $ft==":"){
               $x = strpos($row['comment'],$ft);
               if ($x!==false){
                   if ($linkbios){
                      $op[$i] = str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],0,$x)))
                      ."`0<a href='$link' style='text-decoration: none' id=\"".$i."\" onmouseover=\"Tip('".$display."', BGCOLOR, '#444444', FONTCOLOR, '#DDDDDD',BORDERCOLOR, '#DD5533')\">\n`&$row[name]`0</a>\n`& "
                      .str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],$x+strlen($ft))))
                      ."`0`n";
                   }else{
                      $op[$i] = str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],0,$x)))
                      ."`0\n`&$row[name]`0\n`& "
                      .str_replace("&amp;","&",HTMLEntities2(substr($row['comment'],$x+strlen($ft))))
                      ."`0`n";
                   }
               }
           }


           if ($op[$i]=="")
           if ($linkbios){
              $op[$i] = "`0<a href='$link' style='text-decoration: none' id=\"".$i."\" onmouseover=\"Tip('".$display."', BGCOLOR, '#444444', FONTCOLOR, '#DDDDDD',BORDERCOLOR, '#DD5533')\">`&$row[name]`0</a>`3 dice, \"`#"
              .str_replace("&amp;","&",HTMLEntities2($row['comment']))."`3\"`0`n";
           }else{
              $op[$i] = "`0`&$row[name]`0`3 dice, \"`#"
              .str_replace("&amp;","&",HTMLEntities2($row['comment']))."`3\"`0`n";
           }
           if ($message=="X") $op[$i]="`0($row[section]) ".$op[$i];
           //if ($row['postdate']>=$session['user']['recentcomments']) $op[$i]="<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ".$op[$i];
           // Le due righe successive servono a visualizzare di fianco ai commenti se il player che li ha postati è online
           $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin'] && $row['location']==0);
           if ($row['postdate']>=$session['user']['recentcomments']) $op[$i]=($loggedin?"<img src='images/new-online.gif' alt='Online' width='3' height='5' align='absmiddle'>":"<img src='images/new.gif' alt='Offline' width='3' height='5' align='absmiddle'> ").$op[$i];
           addnav("",$link);
        }
        $i--;
        $outputcomments=array();
        $sect="x";
        for (;$i>=0;$i--){
            $out="";
            if ($session['user']['superuser']>=2 && $message=="X"){
                $out.="`0[ <a href='superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;";
                addnav("","superuser.php?op=commentdelete&commentid=$commentids[$i]&return=".URLEncode($_SERVER['REQUEST_URI']));
                $matches=array();
                preg_match("/[(][^)]*[)]/",$op[$i],$matches);
                $sect=$matches[0];
            }
            //output($op[$i],true);
            $out.=$op[$i];
            if (!is_array($outputcomments[$sect])) $outputcomments[$sect]=array();
            array_push($outputcomments[$sect],$out);
        }
        ksort($outputcomments);
        reset($outputcomments);
        while (list($sec,$v)=each($outputcomments)){
              if ($sec!="x") output("`n`b$sec`b`n");
              reset($v);
              while (list($key,$val)=each($v)){
                  output($val,true);
              }
        }
        if ($session['user']['loggedin']) {
            $nomedb = getsetting("nomedb","logd");

            //Excalibur: Modifica per impedire di postare commenti retrocedendo nella discussione
            $counttoday=0;
            $sqlcomment = "SELECT * FROM commentary WHERE section='$section' AND postdate>'".date("Y-m-d 00:00:00")."' ORDER BY commentid LIMIT $limit";
            $resultcomment = db_query($sqlcomment);
            while ($rowcomment=db_fetch_assoc($resultcomment)){
                  if ($rowcomment['author']==$session['user']['acctid']
                  AND date("Y-m-d",strtotime($rowcomment['postdate']))==date("Y-m-d") AND $climit==0) $counttoday++;
            }
            //Excalibur: Fine modifica commenti
            if (($counttoday < $limitk OR
            $session['user']['superuser'] >= 2 OR
            $session['user']['npg'] == 1 OR
            $nomedb == "logd2" OR
            $climit != 0) AND ($stanzachiusa!="1" OR $session['user']['superuser']>=2)){
                if ($talkline!="dice") $tll = strlen($talkline)+11; else $tll=0;
            //Excalibur: aggiunta per preview del testo inserito
            rawoutput("<script language='JavaScript'>
            function previewtext(t){
                var out = \"<span class=\'colLtWhite\'>".addslashes(appoencode($session['user']['name']))." \";
                var end = '</span>';
                var x=0;
                var y='';
                var z='';
                var maxlen = 500-".$tll.";
                var residuo = maxlen;
                if (t.substr(0,2)=='::'){
                    x=2;
                    out += '</span><span class=\'colLtWhite\'>';
                }else if (t.substr(0,1)==':'){
                    x=1;
                    out += '</span><span class=\'colLtWhite\'>';
                }else if (t.substr(0,3)=='/me'){
                    x=3;
                    out += '</span><span class=\'colLtWhite\'>';
                }else{
                    out += '</span><span class=\'colDkCyan\'>".addslashes(appoencode($talkline)).", \"</span><span class=\'colLtCyan\'>';
                    end += '</span><span class=\'colDkCyan\'>\"';
                }
                for (; x < t.length; x++){
                    y = t.substr(x,1);
                    if (y=='<'){
                        out += '&lt;';
                        continue;
                    }else if(y=='>'){
                        out += '&gt;';
                        continue;
                    }else if (y=='`'){
                        if (x < t.length-1){
                            z = t.substr(x+1,1);
                            if (z=='0'){
                                out += '</span>';
                            }else if (z=='1'){
                                out += '</span><span class=\'colDkBlue\'>';
                            }else if (z=='2'){
                                out += '</span><span class=\'colDkGreen\'>';
                            }else if (z=='3'){
                                out += '</span><span class=\'colDkCyan\'>';
                            }else if (z=='4'){
                                out += '</span><span class=\'colDkRed\'>';
                            }else if (z=='5'){
                                out += '</span><span class=\'colDkMagenta\'>';
                            }else if (z=='6'){
                                out += '</span><span class=\'colDkYellow\'>';
                            }else if (z=='7'){
                                out += '</span><span class=\'colDkWhite\'>';
                            }else if (z=='8'){
                                out += '</span><span class=\'colDkOrange\'>';
                            }else if (z=='9'){
                                out += '</span><span class=\'colDkBlack\'>';
                            }else if (z=='v'){
                                out += '</span><span class=\'colDkViolet\'>';
                            }else if (z=='!'){
                                out += '</span><span class=\'colLtBlue\'>';
                            }else if (z=='@'){
                                out += '</span><span class=\'colLtGreen\'>';
                            }else if (z=='#'){
                                out += '</span><span class=\'colLtCyan\'>';
                            }else if (z=='$'){
                                out += '</span><span class=\'colLtRed\'>';
                            }else if (z=='%'){
                                out += '</span><span class=\'colLtMagenta\'>';
                            }else if (z=='^'){
                                out += '</span><span class=\'colLtYellow\'>';
                            }else if (z=='&'){
                                out += '</span><span class=\'colLtWhite\'>';
                            }else if (z=='('){
                                out += '</span><span class=\'colLtOrange\'>';
                            }else if (z=='V'){
                                out += '</span><span class=\'colLtViolet\'>';
                            }else if (z==')'){
                                out += '</span><span class=\'colLtBlack\'>';
                            }else if (z=='x'){
                                out += '</span><span class=\'colDkBrown\'>';
                            }else if (z=='X'){
                                out += '</span><span class=\'colLtBrown\'>';
                            }else if (z=='f'){
                                out += '</span><span class=\'colBlue\'>';
                            }else if (z=='F'){
                                out += '</span><span class=\'colblueviolet\'>';
                            }else if (z=='g'){
                                out += '</span><span class=\'colLime\'>';
                            }else if (z=='G'){
                                out += '</span><span class=\'colXLtGreen\'>';
                            }else if (z=='r'){
                                out += '</span><span class=\'colRose\'>';
                            }else if (z=='R'){
                                out += '</span><span class=\'coliceviolet\'>';
                            }else if (z=='a'){
                                out += '</span><span class=\'colAttention\'>';
                            }else if (z=='A'){
                                out += '</span><span class=\'colWhiteBlack\'>';
                            }else if (z=='s'){
                                out += '</span><span class=\'colBack\'>';
                            }else if (z=='S'){
                                out += '</span><span class=\'colredBack\'>';
                            }else if (z=='q'){
                                out += '</span><span class=\'colVomito\'>';
                            }else if (z=='Q'){
                                out += '</span><span class=\'colaquamarine\'>';
                            }else if (z=='e'){
                                out += '</span><span class=\'collightsalmon\'>';
                            }else if (z=='E'){
                                out += '</span><span class=\'colsalmon\'>';
                            }else if (z=='j'){
                                out += '</span><span class=\'colSenape\'>';
                            }else if (z=='J'){
                                out += '</span><span class=\'colDkSenape\'>';
                            }else if (z=='p'){
                                out += '</span><span class=\'colPrugna\'>';
                            }
                            x++;
                        }
                    }else{
                        out += y;
                    }
                }
                residuo=residuo-t.length;
                var resto=' <span class=\'colLtBlack\'>('+residuo+' caratteri rimanenti)</span>';
                document.getElementById(\"previewtext\").innerHTML=out+end+resto+'<br/>';
            }
            </script>
            ");  //Excalibur: fine
            if ($message!="X"){
                $estrai="karnak";
                $restorepage=$session['user']['restorepage'];
                $paragone=strstr($restorepage,$estrai);
                if ($paragone === false){
                    output("<form action=\"$REQUEST_URI\" method='POST'>`@$message`n<input name='insertcommentary[$section]' id='commentary' onKeyUp='previewtext(document.getElementById(\"commentary\").value);'; size='40' maxlength='".(500-$tll)."'><input type='hidden' name='talkline' value='$talkline'><input type='hidden' name='section' value='$section'><input type='submit' class='button' value='Aggiungi'>".((round($limitk,0)-$counttoday<3)&&($session['user']['superuser']<2)&&($climit==0)?"`)(Hai ancora ".(round($limitk,0)-$counttoday)." commenti per oggi)":"")."`0`n</form>",true);
                    addnav("",$REQUEST_URI);
                    if ($doublepost) output("`S`bDoppio post?`b`0`n");
                } else {
                    if ($session['user']['dio'] == 0 or $session['user']['dio'] == 2){
                        output("<form action=\"$REQUEST_URI\" method='POST'>`@$message`n<input name='insertcommentary[$section]' id='commentary' onKeyUp='previewtext(document.getElementById(\"commentary\").value);'; size='40' maxlength='".(500-$tll)."'><input type='hidden' name='talkline' value='$talkline'><input type='hidden' name='section' value='$section'><input type='submit' class='button' value='Aggiungi'>`n".((round($limitk,0)-$counttoday<3)&&($session['user']['superuser']<2)&&($climit==0)?"`)(Hai ancora ".(round($limitk,0)-$counttoday)." commenti per oggi)":"")."`0`n</form>",true);
                        addnav("",$REQUEST_URI);
                    }else{
                        output("`@$message`n<input type='hidden' name='talkline' value='$talkline'><input type='hidden' name='section' value='$section'>`n`0`n</form>",true);
                        //addnav("",$REQUEST_URI);
                        if ($doublepost) output("`S`bDoppio post?`b`0`n");
                    }
                }
            rawoutput("<div id='previewtext'></div></form><br/>",true); //Excalibur: Aggiunta per anteprima
            }
        }elseif($stanzachiusa="1" AND $session['user']['superuser']<2){
            output("`7Questa stanza è stata bloccata dagli admin, non ti è possibile scrivere.`0`n");
        }else{
            output("`@$message`nSorry, hai esaurito i tuoi commenti in questa sezione per oggi.`0`n");
        }
    }
    if (db_num_rows($result)>=$limit){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]-])*'","",$REQUEST_URI)."&comscroll=".($com+1);
        //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com+1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        output("<a href=\"$req\">&lt;&lt; Precedente</a>",true);
        addnav("",$req);
    }
    $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=0";
    //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com-1);
    $req = str_replace("?&","?",$req);
    if (!strpos($req,"?")) $req = str_replace("&","?",$req);
    output("&nbsp;<a href=\"$req\">Aggiorna</a>&nbsp;",true);
    addnav("",$req);
    if ($com>0){
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=".($com-1);
        //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com-1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
        output(" <a href=\"$req\">Prossima &gt;&gt;</a>",true);
        addnav("",$req);
    }
    db_free_result($result);

}else{
    $linkreturn = preg_replace("'[&?]c=[[:digit:]-]+'","",$_SERVER['REQUEST_URI']);
    $linkreturn = substr($linkreturn,strrpos($linkreturn,"/")+1);
    if (strpos($linkreturn,"?") === false){
       $linkreturn .="?chat=0";
    }else{
       $lenght = strlen($linkreturn);
       $position = strpos($linkreturn,"?");
       $linkreturn = substr($linkreturn, 0,$position)."?chat=0";
    }
    if (substr($linkreturn, 0, 11) == "village.php" OR substr($linkreturn, 0, 11) == "village1.ph"){
       output("<a href='".$linkreturn."'><img src='./images/plus.gif' border='0' valign='bottom' style='height: 9px;'>
       </a> `SMostra Chat<hr width='100%' size='1' color='#FFFF00'>",true);
       addnav("",$linkreturn);
    }else{
      output("`GChat disabilitata, torna al villaggio per abilitarla.<hr width='100%' size='1' color='#FFFF00'>",true);
    }
}
}

function dhms($secs,$dec=false){
    if ($dec===false) $secs=round($secs,0);
    return (int)($secs/86400)."d".(int)($secs/3600%24)."h".(int)($secs/60%60)."m".($secs%60).($dec?substr($secs-(int)$secs,1):"")."s";
}

function getmount($horse=0) {
    $sql = "SELECT * FROM mounts WHERE mountid='$horse'";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        return db_fetch_assoc($result);
    }else{
        return array();
    }
}

function debuglog($message,$target=0){  // $importanza=1,$categoria="",$sottocategoria=""
    global $session;
    if (date("H") > 3 AND date("H") < 6){
       $sql = "DELETE from debuglog WHERE date <'".date("Y-m-d H:i:s",strtotime(date("r")."-".(getsetting("expirecontent",180))." days"))."'";
       db_query($sql);
    }
    $sql = "INSERT INTO debuglog VALUES(0,now(),{$session['user']['acctid']},$target,'".addslashes($message)."')"; // ,'".$session['user']['lastip']."','".$session['user']['uniqueid']."',{$session['user']['dio']},$importanza,'".$categoria."','".$sottocategoria."'
    db_query($sql);
    //Sook, report in caso di interazione tra personaggi legati da IP e/o ID con autorizzazione
    //if ($target!=0) {
    if ($target!=0 AND substr($message,(strpos($message, "T")),32)!="Trovato personaggio con stesso I"){
        $sql = "SELECT * FROM allowmulti WHERE (acctid1='{$session['user']['acctid']}' AND acctid2='{$target}') OR (acctid1='{$target}' AND acctid2='{$session['user']['acctid']}')";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0) {
            $sql2 = "SELECT name FROM accounts WHERE acctid='{$target}'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
            //if (substr($message,3,8)!="Trovato ") report(3,"`4INTERAZIONE","`4".$session['user']['name']." `4(acctid: ".$session['user']['acctid'].") ha interagito con ".$row2[name]." `4(acctid: $target)`n`n`0".$message,"interazione");
            report(3,"`4INTERAZIONE","`4".$session['user']['name']." `4(acctid: ".$session['user']['acctid'].") ha interagito con ".$row2[name]." `4(acctid: $target)`n`n`0".$message,"interazione");
        }
    }
    /*Sook, mail di sistema agli admin per eventi di particolare importanza
    if ($importanza>=4) {
        $messaggio="Rilevato allarme di livello $importanza:`n`n".$session['user']['name']." $message`n`nDati ulteriori:`nacctid: ".$session['user']['acctid']."`nIP: ".$session['user']['lastip']."`nID: ".$session['user']['uniqueid']."`nFede: ".$session['user']['fede']."`nCategoria: $categoria`nSottocategoria: $sottocategoria`nBersaglio: $target`nData e ora: ".now();
        report(3,"`!Allarme Log Livello ".$importanza,$messaggio,"warning");
    }*/
}

//require_once "../../../logd_connect/dbconnect.php";

if (file_exists("dbconnect.php")){
    require_once "dbconnect.php";
}else{
    echo "Devi editare il file nominato \"dbconnect.php.dist,\" e fornire le info richieste, quindi salvarlo come \"dbconnect.php\"".
    exit();
}


$link = mysql_connect($DB_HOST, $DB_USER, $DB_PASS) or die ("db Error");

db_select_db ($DB_NAME) or die (db_error($link));
define("LINK",$link);

mysql_query("SET NAMES 'latin1'");
mysql_set_charset("latin1", $link);

//Sook, impostazione orario europeo sul db
if(date('I')==1) {
    mysql_query("set time_zone = '+2:00'");
} else {
    mysql_query("set time_zone = '+1:00'");
}

#session_register("session");
function register_global(&$var){
    @reset($var);
    while (list($key,$val)=@each($var)){
        global $$key;
        $$key = $val;
    }
    @reset($var);
}
$session =& $_SESSION['session'];
//echo nl2br(HTMLEntities2(output_array($session)));
//register_global($_SESSION);
register_global($_SERVER);


function HTMLEntities2($text) {
return HTMLEntities($text,ENT_COMPAT,"ISO-8859-1");
}




if (strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds") > $session['lasthit'] && $session['lasthit']>0 && $session[loggedin]){
    //force the abandoning of the session when the user should have been sent to the fields.
    //echo "Session abandon:".(strtotime(date("r"))-$session[lasthit]);

    $session=array();
    $session['message'].="`nLa tua sessione è scaduta!`n";
}
$session['lasthit']=strtotime(date("r"));
$revertsession=$session;
if ($REQUEST_URI==""){
    //necessary for some IIS installations (CGI in particular)
    if (is_array($_GET) && count($_GET)>0){
        $REQUEST_URI=$SCRIPT_NAME."?";
        reset($_GET);
        $i=0;
        while (list($key,$val)=each($_GET)){
            if ($i>0) $REQUEST_URI.="&";
            $REQUEST_URI.="$key=".URLEncode($val);
            $i++;
        }
    }else{
        $REQUEST_URI=$SCRIPT_NAME;
    }
    $_SERVER['REQUEST_URI'] = $REQUEST_URI;
}
$SCRIPT_NAME=substr($SCRIPT_NAME,strrpos($SCRIPT_NAME,"/")+1);
if (strpos($REQUEST_URI,"?")){
    $REQUEST_URI=$SCRIPT_NAME.substr($REQUEST_URI,strpos($REQUEST_URI,"?"));
}else{
    $REQUEST_URI=$SCRIPT_NAME;
}

//Luke: aggiunto e spostato per test traduzione tedesco
require_once "translator.php";


$allowanonymous=array("index.php"=>true,"index_backdoor.php"=>true,"login.php"=>true,"loginback.php"=>true,"create.php"=>true,"about.php"=>true,"list.php"=>true,"petition.php"=>true,"connector.php"=>true,"logdnet.php"=>true,"referral.php"=>true,"news.php"=>true,"motd.php"=>true,"topwebvote.php"=>true,"hints.php"=>true,"regolamento.php"=>true,"newfaq.php"=>true,"faqplayer.php"=>true,"termini.php"=>true,"payment.php"=>true);
$allownonnav = array("badnav.php"=>true,"motd.php"=>true,"petition.php"=>true,"mail.php"=>true,"topwebvote.php"=>true,"limite.php"=>true);
if ($session['loggedin']){
    $sql = "SELECT * FROM accounts WHERE acctid = '".$session['user']['acctid']."'";
    $result = db_query($sql);
    if (db_num_rows($result)==1){
        $session['user']=db_fetch_assoc($result);
        $sessionbackup=Array();
        $sessionbackup=$session['user'];
        $session['output']=$session['user']['output'];
        $session['user']['dragonpoints']=unserialize($session['user']['dragonpoints']);
        // Aggiunta by Excalibur
        $session['user']['torneopoints']=unserialize($session['user']['torneopoints']);
        if (!is_array($session['user']['torneopoints'])) $session['user']['torneopoints']=array();
        // Fine aggiunta by Excalibur
        // Aggiunta by Sook
        $session['user']['olddp']=unserialize($session['user']['olddp']);
        if (!is_array($session['user']['olddp'])) $session['user']['olddp']=array();
        // Fine aggiunta by Sook
        //Xtramus manicomio
        $session['user']['lupin']=unserialize($session['user']['lupin']);
        if (!is_array($session['user']['lupin'])) $session['user']['lupin']=Array();
        if($session['user']['evil']<150 && $session['user']['lupin']['carriera']==1) {
            output("`\$Ti accorgi di aver perso la pietra del malvagio! Chissà a che serviva!");
            $session['user']['lupin']['carriera']=0;
            debuglog("`(Perde la pietra del manicomio: " . $session['user']['lupin']);
        }
        if($session['user']['lupin']=="N;") {
            output("`\$SI E' VERIFICATO UN PROBLEMA CON LA QUEST MANICOMIO! CONTATTA GLI ADMIN");
            $session['user']['lupin']=Array();
            debuglog("Errore del campo lupin! Prendere gli ultimi dati disponibili e inserirli nel db!");
        }
        // fine Manicomio
        $session['user']['prefs']=unserialize($session['user']['prefs']);
        if (!is_array($session['user']['dragonpoints'])) $session['user']['dragonpoints']=array();
        if (is_array(unserialize($session['user']['allowednavs']))){
            $session['allowednavs']=unserialize($session['user']['allowednavs']);
        }else{
            //depreciated, left only for legacy support.
            $session['allowednavs']=createarray($session['user']['allowednavs']);
        }
        if (!$session['user']['loggedin'] || (0 && (date("U") - strtotime($session['user']['laston'])) > getsetting("LOGINTIMEOUT",900)) ){
            $session=array();
            redirect("index.php?op=timeout","Account non loggato ma la sessione pensa che lo sia.");
        }
        if ($session['user']['kicked'] =="1" ){
            $session['user']['kicked'] = 0;
            debuglog("è stato disconnesso dagli admin");
            $sql = "UPDATE accounts SET kicked=0, loggedin=0, sconnesso=0, locazione=0, dove_sei=0 WHERE acctid = ".$session['user']['acctid'];
            db_query($sql) or die(sql_error($sql));
            $session=array();
            redirect("index.php?op=kicked","Disconnessione forzata dagli admin.");
        }
    }else{
        $session=array();
        $session['message']="`4Errore, il tuo login era sbagliato`0";
        redirect("index.php","Account Scomparso!");
    }
    db_free_result($result);
    if ($session['allowednavs'][$REQUEST_URI] && !$allownonnav[$SCRIPT_NAME]){
        $session['allowednavs']=array();
    }else{
        if (!$allownonnav[$SCRIPT_NAME]){
            redirect("badnav.php","Navigazione non permessa a $REQUEST_URI");
        }
    }
}else{
    //if ($SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
    if (!$allowanonymous[$SCRIPT_NAME]){
        $session['message']="Non sei loggato, questo può essere causato dal fatto che la tua sessione è scaduta.";
        redirect("index.php?op=timeout","Non loggato in: $REQUEST_URI");
    }
}
//if ($session[user][loggedin]!=true && $SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
if ($session['user']['loggedin']!=true && !$allowanonymous[$SCRIPT_NAME]){
    redirect("login.php?op=logout");
}

$session['counter']++;
$nokeeprestore=array("newday.php"=>1,"badnav.php"=>1,"motd.php"=>1,"mail.php"=>1,"petition.php"=>1);
if (!$nokeeprestore[$SCRIPT_NAME]) { //strpos($REQUEST_URI,"newday.php")===false && strpos($REQUEST_URI,"badnav.php")===false && strpos($REQUEST_URI,"motd.php")===false && strpos($REQUEST_URI,"mail.php")===false
    $session['user']['restorepage']=$REQUEST_URI;
}else{

}

if ($session['user']['hitpoints']>0){
    $session['user']['alive']=true;
}else{
    $session['user']['alive']=false;
}

$session['bufflist']=unserialize($session['user']['bufflist']);
if (!is_array($session['bufflist'])) $session['bufflist']=array();
$session['user']['lastip']=$REMOTE_ADDR;
if (strlen($_COOKIE[$DB_NAME])<32){
    if (strlen($session['user']['uniqueid'])<32){
        $u=md5(md5(microtime()));
        setcookie("$DB_NAME",$u,strtotime(date("r")."+365 days"));
        $_COOKIE[$DB_NAME]=$u;
        $session['user']['uniqueid']=$u;
    }else{
        setcookie("$DB_NAME",$session['user']['uniqueid'],strtotime(date("r")."+365 days"));
    }
}else{
    $session['user']['uniqueid']=$_COOKIE[$DB_NAME];
}
$url = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
$url = substr($url,0,strlen($url)-1);

if (substr($_SERVER['HTTP_REFERER'],0,strlen($url))==$url || $_SERVER['HTTP_REFERER']==""){

}else{
    $sql = "SELECT * FROM referers WHERE uri='{$_SERVER['HTTP_REFERER']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $site = str_replace("http://","",$_SERVER['HTTP_REFERER']);
    if (strpos($site,"/"))
    $site = substr($site,0,strpos($site,"/"));
    if ($row['refererid']>""){
        $sql = "UPDATE referers SET count=count+1,last=now(),site='".addslashes($site)."' WHERE refererid='{$row['refererid']}'";
    }else{
        $sql = "INSERT INTO referers (uri,count,last,site) VALUES ('{$_SERVER['HTTP_REFERER']}',1,now(),'".addslashes($site)."')";
    }
    db_query($sql);
}

if ($_COOKIE['template']!="") $templatename=$_COOKIE['template'];
if (!file_exists("templates/$templatename") || $templatename=="") $templatename="yarbrough.htm";
$template = loadtemplate($templatename);
//tags that must appear in the header
$templatetags=array("title","headscript","script");
while (list($key,$val)=each($templatetags)){
    if (strpos($template['header'],"{".$val."}")===false) $templatemessage.="Non hai definito {".$val."} nel tuo header\n";
}
//tags that must appear in the footer
$templatetags=array();
while (list($key,$val)=each($templatetags)){
    if (strpos($template['footer'],"{".$val."}")===false) $templatemessage.="Non hai definito {".$val."} nel tuo footer\n";
}
//tags that may appear anywhere but must appear
$templatetags=array("nav","stats","petition","motd","mail","paypal","copyright","source");
while (list($key,$val)=each($templatetags)){
    if (strpos($template['header'],"{".$val."}")===false && strpos($template['footer'],"{".$val."}")===false) $templatemessage.="Non hai definito {".$val."} nel tuo header o footer\n";
}

if ($templatemessage!=""){
    echo "<b>Hai uno o più errori nella tua template page!</b><br>".nl2br($templatemessage);
    $template=loadtemplate("yarbrough.htm");
}

$prof=array(
0=>"`5Nessuna`0",
1=>"`3Seguace`0",
2=>"`3Accolito`0",
3=>"`3Chierico`0",
4=>"`3Sacerdote`0",
9=>"`#Gran Sacerdote`0",
5=>"`2Garzone`0",
6=>"`2Apprendista`0",
7=>"`2Fabbro`0",
8=>"`@Mastro Fabbro`0",
10=>"`4Invasato`0",
11=>"`4Fanatico`0",
12=>"`4Posseduto`0",
13=>"`%Maestro delle Tenebre`0",
15=>"`\$Falciatore di Anime`0",
16=>"`(Portatore di Morte`0",
17=>"`@Sommo Chierico`0",
41=>"`6Iniziato`0",
42=>"`6Stregone`0",
43=>"`6Mago`0",
44=>"`VArcimago`0",
50=>"`8Stalliere dei Draghi`0",
51=>"`8Scudiero dei Draghi`0",
52=>"`8Cavaliere dei Draghi`0",
53=>"`(Mastro dei Draghi`0",
54=>"`(Dominatore di Draghi`0",
55=>"`^Cancelliere dei Draghi`0",
80=>"`%Moderatore`0",
100=>"`\$Admin`0",
255=>"`S`bSupremo`b`0"
);

$races=array(
0=>"Sconosciuto",
1=>"`2Troll",
2=>"`^Elfo",
3=>"`&Umano",
4=>"`#Nano",
5=>"`3Druido",
6=>"`@Goblin",
7=>"`%Orco",
8=>"`\$Vampiro",
9=>"`5Lich",
10=>"`&Fanciulla delle Nevi",
11=>"`4Oni",
12=>"`3Satiro",
13=>"`#Gigante delle Tempeste",
14=>"`\$Barbaro",
15=>"`%Amazzone",
16=>"`^Titano",
17=>"`\$Demone",
18=>"`(Centauro",
19=>"`8Licantropo",
20=>"`)Minotauro",
21=>"`^Cantastorie",
22=>"`@Eletto",
50=>"Pecora Volante",
51=>"`9Sauro",
60=>"Tester",
80=>"`%Moderatore`0",
100=>"`\$Admin`0",
127=>"`S`bDivinità`b`0"
);

$pietre=array(
1=>"`\$Pietra di Poker`0",
2=>"`^Pietra dell'Amore`0",
3=>"`^Pietra dell'Amicizia`0",
4=>"`#Pietra del Re`0",
5=>"`#Pietra di Mighthy`0",
6=>"`#Pietra di Pegaso`0",
7=>"`@Pietra di Aris`0",
8=>"`@Pietra di Excalibur`0",
9=>"`@Pietra di Luke`0",
10=>"`&Pietra dell'Innocenza`0",
11=>"`#Pietra della Regina`0",
12=>"`#Pietra dell'Imperatore`0",
13=>"`!Pietra d'Oro`0",
14=>"`%Pietra della Potenza`0",
15=>"`\$Pietra di Ramius`0",
16=>"`#Pietra di Cedrik`0",
17=>"`%Pietra dell'Onore`0",
18=>"`&Pietra della Purezza`0",
19=>"`&Pietra di Luce`0",
20=>"`&Pietra di Diamante`0"
);

$logd_version = "0.9.7(#ITA#) OGSI Version";
$session['user']['laston']=date("Y-m-d H:i:s");

$playermount = getmount($session['user']['hashorse']);
$playermount['mountname2'] = $playermount['mountname'];
if (($playermount['mountname'] != "") && ($session['user']['mountname'] != "")) $playermount['mountname'] .= " ".$session['user']['mountname'];

$titles = array(
0=>array("Contadino","Contadina"),
1=>array("Esploratore", "Esploratrice"),
2=>array("Scudiero", "Scudiera"),
3=>array("Gladiatore", "Gladiatrice"),
4=>array("Legionario","Legionaria"),
5=>array("Centurione","Centurionessa"),
6=>array("Sir","Madam"),
7=>array("Castaldo", "Castalda"),
8=>array("Camerlengo", "Camerlenga"),
9=>array("Maggiore", "Maggiore"),
10=>array("Barone", "Baronessa"),
11=>array("Conte", "Contessa"),
12=>array("Visconte", "Viscontessa"),
13=>array("Marchese", "Marchesa"),
14=>array("Cancelliere", "Cancelliere"),
15=>array("Principe", "Principessa"),
16=>array("Re", "Regina"),
17=>array("Imperatore", "Imperatrice"),
18=>array("Angelo", "Angelo"),
19=>array("Arcangelo", "Arcangelo"),
20=>array("Principato", "Principato"),
21=>array("Potenza", "Potenza"),
22=>array("Virtu", "Virtu"),
23=>array("Dominazione", "Dominazione"),
24=>array("Throne", "Throne"),
25=>array("Cherubino", "Cherubina"),
26=>array("Serafino", "Serafina"),
27=>array("Semidio", "Semidea"),
28=>array("Titano", "Titano"),
29=>array("Antico", "Antica"),
30=>array("Dio minore", "Dea minore"),
);
$fededio=array(
0=>"`2Agnostico",
1=>"`^Sgriossino",
2=>"`\$Karnakkiano",
3=>"`@Draghista",
4=>"`2Natura",
5=>"`(Eretico",
100=>"`G`bOGSI`b"
);
$fedecasa=array(
0=>"`%Nessuna`0",
1=>"`^Sgrios`0",
2=>"`\$Karnak`0",
3=>"`@Drago Verde`0",
100=>"`GOGSI`0"
);

$locazione=array(
100=>"Castello Abbandonato",
101=>"Accademia",
102=>"Farmacia di Adriana",
103=>"Agenzia Matrimoniale",
104=>"Allenatore di Draghi",
105=>"Arena",
106=>"Pegasus",
107=>"Banca del Borgo",
108=>"Banca di Rafflingate",
109=>"Docce Pubbliche",
110=>"Tavolo del BlackJack",
111=>"Bosco a Sud",
112=>"Casa del Piacere",
113=>"Caserma",
114=>"Castel Excalibur",
115=>"Dag Durnick",
116=>"Madame Déguise",
117=>"Tavola Calda del Drago",
118=>"Drago Verde",
119=>"Giardino Incantato",
120=>"Eros Esotico",
121=>"Lotto di Eric",
122=>"Esplorazione",
123=>"Oberon",
124=>"Fagioli di Cedrik",
125=>"Falegnameria",
126=>"Fanciulla",
127=>"Caminetto",
128=>"Cassandra",
129=>"Foresta",
130=>"Bosco Oscuro",
131=>"Laghetto",
132=>"Giardini",
133=>"Gilda del Drago",
134=>"Gnomo delle Gemme",
135=>"Cimitero",
136=>"Accampamento Zingari",
137=>"Golinda",
138=>"Guaritore",
139=>"Casa della Strega",
140=>"Sala della Gloria",
141=>"Ippodromo",
142=>"Javella",
143=>"Grotta di Karnak",
144=>"Biblioteca di Plato",
145=>"Capanno di Caccia",
146=>"Torre dei Maghi",
147=>"Manicomio",
148=>"Maniero Burocratico",
149=>"Torneo Medaglie",
150=>"Mercante Zukron",
151=>"Mercante Draghi",
152=>"Gilda Mercenari",
153=>"Miniera",
154=>"Monastero",
155=>"Municipio",
156=>"Myrrdin",
157=>"Oroscopo",
158=>"Osservatorio",
159=>"Toilette Privata",
160=>"Toilette Pubblica",
161=>"PvP",
162=>"Arena PvP",
163=>"sezione HELP",
164=>"Rapina Banca",
165=>"Gioco RSP",
166=>"Roulette",
167=>"Giostra del Paese",
168=>"Salamenteria di Lulù",
169=>"Fuga dalla Prigione",
170=>"Scuderie Ippodromo",
171=>"Selva Oscura",
172=>"Sgarro",
173=>"Terra delle Ombre",
174=>"Le Fattorie",
175=>"Spada nella Roccia",
176=>"Hatetepe",
177=>"Stalle di Merick",
178=>"Gioco delle Pietre",
179=>"Casa della Strega",
180=>"Palestra di Swarzy",
181=>"Terre dei Draghi",
182=>"Torneo di LoGD",
183=>"Torre Nera",
184=>"Campo di Allenamento",
185=>"Tunnel degli Inferi",
186=>"Negozio di Vessa",
187=>"Piazza del Villaggio",
188=>"Piazza del Borgo",
189=>"Negozio di Virna",
190=>"Sacerdotessa Voodoo",
191=>"Mighthy",
192=>"Gestione Zaino",
193=>"Locanda",
194=>"Tenuta",
195=>"Chiesa di Sgrios",
196=>"Piazza GDR del Villaggio",
197=>"Dimora degli Dei",
198=>"Prigione",
199=>"Scuola di Rafflingate",
200=>"Chiesa di Sgrios",
201=>"Grotta di Karnak",
202=>"Drago Verde",
203=>"Stanze GDR",
204=>"Limbo"
);

$petizioni=array(
1=>"Bug"
,2=>"Personaggio Bloccato"
,3=>"Problemi Login"
,4=>"Contestazione Provvedimenti"
,5=>"Segnalazione PM Offensivi"
,6=>"Donazioni"
,7=>"Richieste Ruolate"
,8=>"Informazioni"
,9=>"Suggerimenti/Migliorie"
,10=>"Segnalazione MultiAccount (Server 2)"
,11=>"ALTRO"
);

$camuffa=array(
1=>"`&Monaco Amanuense`0",
2=>"`rGentil Donzella`0",
3=>"`FGuardia dello Sceriffo`0",
4=>"`@Drago Verde`0"
);

$acrireinc=array(
0=>"0",
1=>"1500",
2=>"2500",
3=>"3500",
4=>"4500",
5=>"5500",
6=>"6500",
7=>"7000",
8=>"7500",
9=>"8000",
10=>"8500",
11=>"9000"
);
if($session['user']['reincarna']>11) {
	$acrilimite=(6800+200*$session['user']['reincarna']);
	array_push($acrireinc,$acrilimite);
}

//$beta = (getsetting("beta",0) == 1 || $session['user']['beta']==1);
?>
