<?php
require_once("common.php");
require_once("common2.php");
$nomedb = getsetting("nomedb","logd");
function  multiallowed($a,$b) {
     $id1= $a<$b? $a : $b;
     $id2= $a<$b? $b : $a;
     $sql= "select id from allowmulti where acctid1=$id1 && acctid2=$id2";
     $dom= db_query($sql);
     if(db_num_rows($dom)>0) {
          return true;
     } elseif (getsetting("nomedb","logd")=="logd2") {
          //Excalibur: modifica per evitare segnalazione di MultiAccount con NPG
          $sql = "SELECT acctid FROM accounts WHERE (acctid=$id1  OR acctid=$id2) AND npg = 0";
          $dom= db_query($sql);
          if(db_num_rows($dom)<2) {
               return true;
          }else{
              return false;
          }
     }else{
          return false;
     }
}
if ($_POST['name']!=""){
    if ($session['loggedin']){
        if (is_new_day()){
            addnav("Torna al Villaggio","village.php");
        }else{
            redirect("badnav.php");
        }
    }else{
        if(0){
        }else{
            if ($_GET['az'] == "validate"){
                $pass = $_POST['password'];
            }else{
                $pass = md5($_POST['password']);
            }
            $sql = "SELECT * FROM accounts WHERE login = '".$_POST['name']."' AND password='$pass' AND superuser>2 AND locked=0";
            $result = db_query($sql);
            if (db_num_rows($result)>0){
                $session['user']=db_fetch_assoc($result);
                $sessionbackup=$session['user'];
                debuglog("`^Si connette a LoGD da backdoor. IP = ".$REMOTE_ADDR." (IP precedente: ".$session['user']['lastip'].",`n
                          ID = ".$_COOKIE[$DB_NAME]." `n(ID precedente:".$session['user']['uniqueid'].")");
                if (!$session['user']['loggedin']) debuglog("Logout precedente effettuato correttamente.");
                //Sook, log dati di accesso
                //controllo stesso ID
                $sqly = "SELECT acctid,lastip,uniqueid,login,loggedin,laston,emailaddress
                         FROM accounts WHERE uniqueid = '".$_COOKIE[$DB_NAME]."'
                         AND acctid <> ".$session['user']['acctid'];
                $resulty = db_query($sqly);
                $countrowy = db_num_rows($resulty);
                for ($i=0; $i<$countrowy; $i++){
                //for ($i = 0;$i < db_num_rows($resulty);$i++) {
                    $rowy = db_fetch_assoc($resulty);
                    debuglog("`\$Trovato personaggio con stesso ID. `n^Nome: ".$rowy['login'].", IP:".$rowy['lastip'].",`n
                                 ID:".$rowy['uniqueid'].", `nEmail:".$rowy['emailaddress'].",
                                 `7Ultima attività:".$rowy['laston'].", ".
                                 (($rowy['loggedin']="1" AND $rowy['laston']>=date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec")))?"CONNESSO":"non connesso")."`0", $rowy[acctid]);
                    if(!multiallowed($session['user']['acctid'],$rowy['acctid'])) {
                         report(3,"`\$MULTIACCOUNT!`0","`&".$rowy['login']."`2 ha lo stesso ID di ".$session['user']['login']." (IP:".$rowy['lastip'].", ID:".$rowy['uniqueid'].")","multiaccount");
                    }else {
                         report(3,"`@multiaccount!`0","`&".$rowy['login']."`2 ha lo stesso ID di ".$session['user']['login']." (IP:".$rowy['lastip'].", ID:".$rowy['uniqueid'].")","allowmultiaccount");
                    }
                }
                //controllo stesso IP
                $sqly = "SELECT acctid,lastip,uniqueid,login,loggedin,laston,emailaddress
                         FROM accounts WHERE lastip = '".$REMOTE_ADDR."'
                         AND acctid <> '".$session['user']['acctid']."'
                         AND uniqueid <> '".$_COOKIE[$DB_NAME]."'";
                $resulty = db_query($sqly);
                $countrowy = db_num_rows($resulty);
                for ($i=0; $i<$countrowy; $i++){
                //for ($i = 0;$i < db_num_rows($resulty);$i++) {
                    $rowy = db_fetch_assoc($resulty);
                    debuglog("`\$Trovato personaggio con stesso IP. `n^Nome: ".$rowy['login'].", IP:".$rowy['lastip'].", `nID:
                    ".$rowy['uniqueid'].", `nEmail:".$rowy['emailaddress'].", `7Ultima attività:".$rowy['laston'].", ".(($rowy['loggedin']="1"
                    AND $rowy['laston']>=date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec")))?"CONNESSO":"non connesso")."`0", $rowy[acctid]);
                    if(!multiallowed($session['user']['acctid'],$rowy['acctid'])) {
                         report(3,"`\$MULTIACCOUNT!`0","`&".$rowy['login']."`2 ha lo stesso IP di ".$session['user']['login']." (IP:".$rowy['lastip'].", ID:".$rowy['uniqueid'].")","multiaccount");
                    }else {
                         report(3,"`@multiaccount`0","`&".$rowy['login']."`2 ha lo stesso IP di ".$session['user']['login']." (IP:".$rowy['lastip'].", ID:".$rowy['uniqueid'].")","allowmultiaccount");
                    }
                }
                report(3,"`gACCESSO DA BACKDOOR!`0","`&".$session['user']['login']."`2 effettua login da pagina di accesso secondario - backdoor");
                //fine log accesso
                //echo "Ooga Booga";
                //flush();
                //exit();
                checkban($session['user']['login']); //check if this account is banned
                checkban(); //check if this computer is banned

                if ($session['user']['emailvalidation']!="" && substr($session['user']['emailvalidation'],0,1)!="x"){
                    $session['user']=array();
                    $session['message']="<font color='FF44FF'><big><b>Errore, devi validare l'indirizzo mail prima di avere accesso al game.</b></big></font>";
                    echo $session['message'];
                    //header("Location: index.php");
                    exit();
                }else{
                    //loaduser($session['user']);
                    //Sook: inserimento di IP e ID nell'archivio
                    $sqlarc="INSERT INTO accessi (acctid, data, ip, id) VALUES ('".$session['user']['acctid']."',now(),'".$REMOTE_ADDR."','".$_COOKIE[$DB_NAME]."')";
                    db_query($sqlarc) or die(db_error(LINK));
                    $session['loggedin']=true;
                    $session['sconnesso']=true;
                    $session['output']=$session['user']['output'];
                    $session['laston']=date("Y-m-d H:i:s");
                    $session['sentnotice']=0;
                    $session['user']['kicked']=0;
                    $session['user']['dragonpoints']=unserialize($session['user']['dragonpoints']);
                    $session['user']['prefs']=unserialize($session['user']['prefs']);
                    $session['bufflist']=unserialize($session['user']['bufflist']);
                    if (!is_array($session['user']['dragonpoints'])) $session['user']['dragonpoints']=array();
                    if ($session['user']['loggedin']){
                        if ($session['user']['laston']>=date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec"))) {
                              if(substr($session['user']['restorepage'],0,20)=="houses.php?op=inside") {
                                   //report(3,"`\$Doppio Login in Tenuta`0",$session['user']['login']." ha ripetuto il login in tenuta! Controllare gemme (al momento {$session['user']['gems']})","DoppiLogin");
                                   /*
                                   if($session['user']['emailaddress']=="mimmo12@t-online.de") {
                                        $session['user']['gold']=0;
                                        $session['user']['gems']=0;
                                        $session['allowednavs']=unserialize($session['user']['allowednavs']);
                                        addnav("","battleAdmin.php");
                                        saveuser();
                                        header("location:battleAdmin.php");
                                        exit();
                                   }
                                   */
                              }
                            debuglog("`\$`bNOTA: l'utente risulta connesso al momento del login`b. `n`7Ultima attività: ".$session['user']['laston']."`0");
                            //report(3,"`)Rilevato Doppio Login`0",$session['user']['login']." ha ripetuto il login mentre era già connesso!","DoppiLogin");
                        } else {
                            debuglog("`\$NOTA: l'utente non ha effettuato correttamente l'ultimo logout. `n`7Ultima attività: ".$session['user']['laston']."`0");
                        }
                        $session['allowednavs']=unserialize($session['user']['allowednavs']);
                        saveuser();
                        header("Location: ".$session['user']['restorepage']);

                        exit();
                        //redirect($session['user']['page']);//"badnav.php");
                    }
                    if (getsetting("logdnet",0)){
                        //register with LoGDnet
                        @file(getsetting("logdnetserver","http://lotgd.net/")."logdnet.php?addy=".URLEncode(getsetting("serverurl","http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI'])))."&desc=".URLEncode(getsetting("serverdesc","Another LoGD Server"))."&version=".urlencode($logd_version)."");
                    }
                    //$controllo = strtotime ("+ 2 minutes");
                    //while (strtotime(date("r")) < $controllo) {
                    //      $controllo1 = strtotime(date("r"));
                    //}
                    db_query("UPDATE accounts SET loggedin=".true.", sconnesso=".true.", location=0, locazione=0 WHERE acctid = ".$session[user][acctid]);
                    $session['user']['loggedin']=true;
                    $session['user']['sconnesso']=true;
                    $location = $session['user']['location'];
                    $session['user']['location']=0;
                    $session['user']['locazione']=0;
                    $session['user']['click_limit']=0;
                    $session['user']['lastlogin']=date("Y-m-d H:i:s");
                    if ($session['user']['alive']==0 && $session['user']['slainby']!=""){
                        //they're not really dead, they were killed in pvp.
                        $session['user']['alive']=true;
                    }
                    if ($location==0){
                        redirect("news.php");
                    }else if($location==1){
                        redirect("inn.php?op=strolldown");
                    }else if($location==2){
                        redirect("houses.php?op=newday");
                    }else if($location==3){
                        redirect("municipio.php?op=risveglio");
                    }else if($location==11){
                        redirect("terre_draghi.php?op=risveglio");
                    }else{
                        saveuser();
                        header("Location: ".$session['user']['restorepage']);
                        exit();
                    }
                }
            }else{
                $session[message]="`4Errore, il tuo login è sbagliato`0";
                //now we'll log the failed attempt and begin to issue bans if there are too many, plus notify the admins.
                $sql = "DELETE FROM faillog WHERE date<'".date("Y-m-d H:i:s",strtotime(date("r")."-".(getsetting("expirecontent",180)/4)." days"))."'";
                checkban();
                db_query($sql);
                $sql = "SELECT acctid FROM accounts WHERE login='".$_POST['name']."'";
                $result = db_query($sql);
                if (db_num_rows($result)>0){ // just in case there manage to be multiple accounts on this name.
                    while ($row=db_fetch_assoc($result)){
                        $sql = "INSERT INTO faillog VALUES (0,now(),'".addslashes(serialize($_POST))."','".$_SERVER['REMOTE_ADDR']."','".$row['acctid']."','{$_COOKIE[$DB_NAME]}')";
                        db_query($sql);
                        $sql = "SELECT faillog.*,accounts.superuser,name,login FROM faillog INNER JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='{$_SERVER['REMOTE_ADDR']}' AND date>'".date("Y-m-d H:i:s",strtotime(date("r")."-1 day"))."'";
                        $result2 = db_query($sql);
                        $c=0;
                        $alert="";
                        $su=false;
                        while ($row2=db_fetch_assoc($result2)){
                            if ($row2['superuser']>0) {$c+=1; $su=true;}
                            $c+=1;
                            $alert.="`3".$row2['date']."`7: Accesso errato da `&".$row2['ip']."`7 [`3".$row2['id']."`7] di login a `^".$row2['login']."`7 (".$row2['name']."`7)`n";
                        }
                        if ($c>=10){ // 5 failed attempts for superuser, 10 for regular user
                            $sql = "INSERT INTO bans VALUES ('".$_SERVER['REMOTE_ADDR']."','','".date("Y-m-d H:i:s",strtotime(date("r")."+".($c*3)." hours"))."','Ban di Sistema Automatico: Troppi tentativi errati di login.','')";
                            db_query($sql);
                            if ($su){ // send a system message to admins regarding this failed attempt if it includes superusers.
                                $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
                                $result2 = db_query($sql);
                                $subj = "`#".$_SERVER['REMOTE_ADDR']." troppi tentativi di login errati!";
                                $countrow2 = db_num_rows($result2);
                                for ($i=0; $i<$countrow2; $i++){
                                //for ($i=0;$i<db_num_rows($result2);$i++){
                                    $row2 = db_fetch_assoc($result2);
                                    //delete old messages that
                                    $sql = "DELETE FROM mail WHERE msgto=".$row2['acctid']." AND msgfrom=0 AND subject = '$subj' AND seen=0";
                                    db_query($sql);
                                    if (db_affected_rows()>0) $noemail = true; else $noemail = false;
                                    systemmail($row2['acctid'],"$subj","Questo messaggio è generato come risultato di uno o più account avente accesso da superuser.  Segue Log:`n`n$alert",0,$noemail);
                                }//end for
                            }//end if($su)
                        }//end if($c>=10)
                        //$log="`7Accesso errato da `&{$row2['ip']}`7 [`3{$row2['id']}`7] di login a `^{$row2['login']}`7 ({$row2['name']}`7). Tentativo numero $c`0";
                        $log="`\$Accesso errato `7Tentativo numero $c.`0 , IP:".$REMOTE_ADDR.", ID:".$_COOKIE[$DB_NAME];
                        if ($c>=10) $log.="`\$`bSegue BAN automatico dell'IP`b`0";
                        $sql = "INSERT INTO debuglog VALUES(0,now(),".$row['acctid'].",0,'".addslashes($log)."')";
                        db_query($sql);
                        //Sook, log dati di accesso
                        //controllo stesso IP
                        $sqly = "SELECT lastip,uniqueid,login,loggedin,laston,emailaddress FROM accounts WHERE lastip = '".$REMOTE_ADDR."'";
                        $resulty = db_query($sqly);
                        $countrowy = db_num_rows($resulty);
                        for ($i=0; $i<$countrowy; $i++){
                        //for ($i = 0;$i < db_num_rows($resulty);$i++) {
                            $rowy = db_fetch_assoc($resulty);
                            $log="`\$Trovato personaggio con stesso IP. `n^Nome: ".$rowy['login'].", IP:".$rowy['lastip'].", `nID:".$rowy['uniqueid'].", `nEmail:".$rowy['emailaddress'].", `7Ultima attività:".$rowy['laston'].", ".(($rowy['loggedin']="1" AND $rowy['laston']>=date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec")))?"CONNESSO":"non connesso")."`0";
                            $sql = "INSERT INTO debuglog VALUES(0,now(),".$row['acctid'].",0,'".addslashes($log)."')";
                            db_query($sql);
                        }
                        //controllo stesso ID
                        $sqly = "SELECT lastip,uniqueid,login,loggedin,laston,emailaddress FROM accounts WHERE uniqueid = '".$_COOKIE[$DB_NAME]."' AND lastip <> '".$REMOTE_ADDR."'";
                        $resulty = db_query($sqly);
                        $countrowy = db_num_rows($resulty);
                        for ($i=0; $i<$countrowy; $i++){
                        //for ($i = 0;$i < db_num_rows($resulty);$i++) {
                            $rowy = db_fetch_assoc($resulty);
                            $log="`\$Trovato personaggio con stesso ID. `n^Nome: ".$rowy['login'].", IP:".$rowy['lastip'].", `nID:".$rowy['uniqueid'].", `nEmail:".$rowy['emailaddress'].", `7Ultima attività:".$rowy['laston'].", ".(($rowy['loggedin']="1" AND $rowy['laston']>=date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." sec")))?"CONNESSO":"non connesso")."`0";
                            $sql = "INSERT INTO debuglog VALUES(0,now(),".$row['acctid'].",0,'".addslashes($log)."')";
                            db_query($sql);
                        }
                    //fine log accesso
                    }//end while
                }else{

                }//end if (db_num_rows)
                redirect("index_backdoor.php");
            }
        }
    }
}
// If you enter an empty username, don't just say oops.. do something useful.
$session=array();
$session['message']="`4Errore, il tuo login è sbagliato`0";
redirect("index_backdoor.php");
?>