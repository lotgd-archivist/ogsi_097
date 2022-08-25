<?php
require_once "common.php";
if ($session['return']==""){
   $session['return']=$_GET['ret'];
}
if ((int)getsetting("expirecontent",180)>0 AND date("H") > 2 AND date("H") < 4){
    $sql = "DELETE FROM news WHERE newsdate<'".date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("expirecontent",180)." day"))."'";
    //echo $sql;
    db_query($sql); //prova
}
if ($session['user']['slainby']!=""){
    page_header("Sei stato ucciso!");
        output("`\$Sei stato ucciso nella ".$session['user']['killedin']."`\$ da `%".$session['user']['slainby']."`\$.  Ti è costato il 5% della tua esperienza, e tutto l'oro che avevi con te. Non pensi sia il momento di vendicarsi un po'?");
    addnav("Continua",$REQUEST_URI);
    $session['user']['slainby']="";
    page_footer();
}else{

    if ($session['user']['loggedin']) checkday();
    $newsperpage=50;
    page_header("LoGD News");
     if($session['user']['euro']<1)output('`n<center>
<script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 468;
google_ad_height = 60;
google_ad_format = "468x60_as";
google_ad_type = "text_image";
//2007-03-29: Logd_468x60_news_top
google_ad_channel = "9451465343";
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
 </center>',true);
    //Modifica per inserire news
    if ($session['user']['superuser']>=3){
       output("`0<form action=\"news.php\" method='POST'>",true);
       output("[Admin] Inserisci News? <input name='meldung' size='40'> ",true);
       output("<input type='submit' class='button' value='Insert'>`n`n",true);
       addnav("","news.php");
       if ($_POST['meldung']){
          $_POST['meldung']=stripslashes($_POST['meldung']);
          $sql = "INSERT INTO news (newstext,newsdate,accountid) VALUES ('".addslashes($_POST[meldung])."',NOW(),0)";
          db_query($sql) or die(db_error($link));
          $_POST['meldung']="";
       }
       addnav("","news.php");
    }
    //Fine Modifica

    $offset = (int)$_GET['offset'];
    $timestamp=strtotime((0-$offset)." days");
    $sql = "SELECT count(newsid) AS c FROM news WHERE newsdate='".date("Y-m-d",$timestamp)."'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $totaltoday=$row['c'];
    $pageoffset = (int)$_GET['page'];
    if ($pageoffset>0) $pageoffset--;
    $pageoffset*=$newsperpage;
    $sql = "SELECT * FROM news WHERE newsdate='".date("Y-m-d",$timestamp)."' ORDER BY newsid DESC LIMIT $pageoffset,$newsperpage";
    $result = db_query($sql) or die(db_error(LINK));
    //page_header("LoGD News");
    $date=date("D, M j, Y",$timestamp);

    output("`c`b`!Notizie del $date".($totaltoday>$newsperpage?" (Items ".($pageoffset+1)." - ".min($pageoffset+$newsperpage,$totaltoday)." of $totaltoday)":"")."`c`b`0");

    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
        if ($session['user']['superuser']>=3){
            output("[ <a href='superuser.php?op=newsdelete&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI'])."'>Del</a> ]&nbsp;",true);
            addnav("","superuser.php?op=newsdelete&newsid=$row[newsid]&return=".URLEncode($_SERVER['REQUEST_URI']));
        }
        output($row['newstext']."`n");
    }
    if (db_num_rows($result)==0){
        output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
        output("`1`b`c Oggi non è accaduto nulla che sia degno di nota. Una giornata completamente noiosa. `c`b`0");
    }
    output("`c`2-=-`@=-=`2-=-`@=-=`2-=-`@=-=`2-=-`0`c");
    if ($totaltoday>$newsperpage){
        addnav("Notizie di oggi");
        for ($i=0;$i<$totaltoday;$i+=$newsperpage){
            addnav("Pagina ".($i/$newsperpage+1),"news.php?offset=$offset&page=".($i/$newsperpage+1));
        }
        addnav("Altro");
    }
    if (!$session['user']['loggedin']) {
        addnav("Pagina Iniziale", "index.php");
    } else if ($session['user']['alive']){
        if ($session['return']==""){
           addnav("Piazza del Villaggio","village.php");
        }else{
            $return = preg_replace("'[&?]c=[[:digit:]-]+'","",$session['return']);
            $return = substr($return,strrpos($return,"/")+1);
            addnav("Torna da dove sei venuto",$return);
        }
    }else{
        if($session['user']['sex'] == 1) {
            addnav("`!`bSei morto, amica!`b`0");
        } else {
            addnav("`!`bSei morto, amico!`b`0");
        }
        addnav("Preferenze","prefs.php");
        addnav("Terra delle Ombre","shades.php");
        addnav("Esci","login.php?op=logout");
        addnav("Notizie");
    }
    addnav("Notizie Precedenti","news.php?offset=".($offset+1));
    if ($offset>0){
        addnav("Notizie Successive","news.php?offset=".($offset-1));
    }
    addnav("Info di questo gioco","about.php");
    if ($session['user']['superuser']){
        addnav("Nuovo Giorno","newday.php");
    }
    if ($session['user']['superuser'] > 2){
        addnav("Grotta Admin","superuser.php");
    }
    page_footer();
}
?>