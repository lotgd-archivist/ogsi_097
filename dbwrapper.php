<?php
//do some cleanup here to make sure magic_quotes_gpc is ON, and magic_quotes_runtime is OFF, and error reporting is all but notice.
error_reporting (E_ALL & ~E_NOTICE & ~E_WARNING & ~E_STRICT & ~E_DEPRECATED);
if (!get_magic_quotes_gpc()){
    set_magic_quotes($_GET);
    set_magic_quotes($_POST);
    set_magic_quotes($_SESSION);
    set_magic_quotes($_COOKIE);
    set_magic_quotes($HTTP_GET_VARS);
    set_magic_quotes($HTTP_POST_VARS);
    set_magic_quotes($HTTP_COOKIE_VARS);
    ini_set("magic_quotes_gpc",1);
}
set_magic_quotes_runtime(0);

//Excalibur: Funzioni per debug output slow query
function debug($text){
  global $session;
  if ($session['user']['superuser']>2){
    if (is_array($text)){
      $text = appoencode(dump_item($text),true);
    }
    //rawoutput("<div class='debug'>$text</div>");
    output("<div class='debug'>$text</div>",true);
  }
}
//Excalibur: fine
function set_magic_quotes(&$vars) {
    //eval("\$vars_val =& \$GLOBALS[$vars]$suffix;");
    if (is_array($vars)) {
        reset($vars);
        while (list($key,$val) = each($vars))
            set_magic_quotes($vars[$key]);
    }else{
        $vars = addslashes($vars);
        //eval("\$GLOBALS$suffix = \$vars_val;");
    }
}

define('DBTYPE',"mysql");

$dbqueriesthishit=0;

function db_query($sql){
    global $session,$dbqueriesthishit;
    $dbqueriesthishit++;
    $fname = DBTYPE."_query";
    $starttime = getmicrotime();
    $r = $fname($sql) or die(($session[user][superuser]>=3 || 1?"<pre>".HTMLEntities($sql)."</pre>":"").db_error(LINK));
    //$x = strpos($sql,"WHERE");
    //if ($x!==false) {
    //  $where = substr($sql,$x+6);
    //  $x = strpos($where,"ORDER BY");
    //  if ($x!==false) $where = substr($where,0,$x);
    //  $x = strpos($where,"LIMIT");
    //  if ($x!==false) $where = substr($where,0,$x);
    //  $where = preg_replace("/'[^']*'/","",$where);
    //  $where = preg_replace('/"[^"]*"/',"",$where);
    //  $where = preg_replace("/[^a-zA-Z ]/","",$where);
    //  mysql_query("INSERT DELAYED INTO queryanalysis VALUES (0,\"".addslashes($where)."\",0)");
    //}
    $endtime = getmicrotime();
    if (is_array($session['user']['prefs'])){
       if ($endtime - $starttime >= 5){
           $stringa = addslashes($_SERVER['REQUEST_URI']);
           $sqlslow = "INSERT INTO slowquery (acctid,time,query,link,orario,numplayer)
                   VALUES (".$session['user']['acctid'].",".($endtime - $starttime).",
                   '".addslashes($sql)."','".substr($stringa,(strrpos($stringa, "/")+1))."',
                   '".date("Y-m-d H:i:s",strtotime(date("r")))."',".$session['utenti'].")";
           @mysql_query($sqlslow);
       }
       if ($endtime - $starttime >= 0.25 && ($session['user']['superuser']>2 && $session['user']['prefs']['query'])){
           $s = trim($sql);
           if (strlen($s) > 800) $s = substr($s,0,400)." ... ".substr($s,strlen($s)-400);
           debug("`SSlow Query (".round($endtime-$starttime,2)."s): ".(HTMLEntities($s))."`n");
       }
    }
    return $r;
}

function db_insert_id($link=false) {
global $dbtimethishit;
$dbtimethishit -= getmicrotime();
    $fname = DBTYPE."_insert_id";
    if ($link===false) {
        $r = $fname();
    }else{
        $r = $fname($link);
    }
    $dbtimethishit += getmicrotime();
    return $r;
}

function db_error($link){
    $fname = DBTYPE."_error";
    $r = $fname($link);
    return $r;
}

function db_fetch_assoc($result){
    $fname = DBTYPE."_fetch_assoc";
    $r = $fname($result);
    return $r;
}

function db_num_rows($result){
    $fname = DBTYPE."_num_rows";
    $r = $fname($result);
    return $r;
}

function db_affected_rows($link=false){
    $fname = DBTYPE."_affected_rows";
    if ($link===false) {
        $r = $fname();
    }else{
        $r = $fname($link);
    }
    return $r;
}

function db_pconnect($host,$user,$pass){
    $fname = DBTYPE."_connect";
    $r = $fname($host,$user,$pass);
    return $r;
}

function db_select_db($dbname){
    $fname = DBTYPE."_select_db";
    $r = $fname($dbname);
    return $r;
}
function db_free_result($result){
    $fname = DBTYPE."_free_result";
    $r = $fname($result);
    return $r;
}
?>