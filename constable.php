<?php
// add to user.php
// under
// "drunkenness"=>"Drunkenness (0-100),int",
// add
// "jail"=>"In Jail,bool",
// "evil"=>"How Evil,int",
// add to newday.php
// if (e_rand(1,100) < 11){
//            $sesion['user']['evil']-=1;
//            output("You are feeling really nice today!");
//         }
//         if ($session['user']['jail']==1){
//             $session['user']['evil']-=10;
//            redirect("constable.php?op=newday");
//        }
// if ($session['user']['evil']>99){
//            if $session['user']['bounty']<($session['user']['level']*1000) $session['user']['bounty']=($session['user']['level']*1000);
//        }
// add to village.php under checkday()
// if ($session['user']['jail']==1) redirect("constable.php?op=twiddle");
// add jail to accounts table tinyint(1) nonull dv(0)
// add evil to accounts table int(4) nonull dv(0)
// add $session['user']['evil'] + or - entries into your modules
// 100 evil points can land you in jail
// add to pvp.php after //$sql = "UPDATE accounts SET alive=0, killedin='$killedin', goldinbank=goldinbank-IF(gold<$badguy[creaturegold],gold-$badguy[creaturegold],0),gold=gold-$badguy[creaturegold], experience=experience*.95, slainby=\"".addslashes($session['user']['name'])."\" WHERE acctid=$badguy[acctid]";
// check if wanted
// $sql = "SELECT evil FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
//        $result = db_query($sql);
//        $row = db_fetch_assoc($result);
//        if ($result > 99){
//            $sql = "UPDATE accounts SET jail=1 WHERE acctid=".(int)$badguy[acctid]."";
//            addnews("`4".$session['user']['name']."`3 turned `4{$badguy['creaturename']} over to the constable!");
//        }
// $sql = "SELECT jail FROM accounts WHERE acctid='".(int)$badguy['acctid']."'";
//        $result = db_query($sql);
//        $row = db_fetch_assoc($result);
//        if ($result==1) addnews("`4{$badguy['creaturename']} must have slipped out of jail.");
require_once "common.php";
// Maxiums, rimesso il checkday(), altrimenti si potevano fare newday infiniti con scalo di cattiveria ogni volta...
// checkday();
checkday();
// fine Maximus
addcommentary();
page_header("La Prigione");
output("`c`b`&La Prigione`0`b`c`n`n");
$session['user']['locazione'] = 198;
if ($session['user']['evil']>99 and $_GET['op'] == ""){
    $session['user']['jail']=1;
    output("`4Lo Sceriffo ti squadra, e poi guarda ai poster dei ricercati!  Preme un pulsante ");
    output("sotto la sua scrivania e la porta si blocca!  Sei in trappola!  Lo Sceriffo requisisce le tue armi ");
    output("e ti conduce nella tua cella.`n`n");
    $name=$session['user']['name'];
    debuglog("viene sbattuto in prigione dallo sceriffo dopo essersi presentato da lui. EVIL = ".$session['user']['evil']);
    addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." sbattut".($session[user][sex]?"a":"o")." in prigione!");
}elseif($session['user']['evil']<100 AND $session['user']['locazione']==198 AND $session['user']['jail']==1){
    output("Lo Sceriffo ti dice \"Penso che tu abbia imparato la lezione, sei liber".($session[user][sex]?"a":"o").", ");
    output("ma terrò comunque un occhio su di te.  Meglio che righi diritto!\"`n");
    // Maximus, il newday è stato già fatto, modificato l'addnav per mandarlo al villaggio
    if ($session['user']['jail']==1) addnav("Continua","village.php");
    // fine Maximus
    //if ($session['user']['jail']==0) redirect("constable.php");
    $session['user']['jail']=0;
    $name=$session['user']['name'];
    addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." rilasciat".($session[user][sex]?"a":"o")." dalla prigione!");
    debuglog("$name è stato rilasciato dalla prigione. EVIL = ".$session['user']['evil']);
    //Sook, cancellazione di eventuali taglie al rilascio
    if($session['user']['bounty'] > 0) $session['user']['bounty'] = 0;
    //fine cancellazione taglie
}
if ($session['user']['jail'] > 0 or $_GET['op'] == "newday"){
    if ($session['user']['gold']>0 or $session['user']['goldinbank']>0){
    output("`2Ti vengono requisiti tutti i tuoi averi per ripagare i danni che hai causato alla comunità.`n");
    if ($session['user']['gold']>0) {
        output("`2Perdi`6 ".$session['user']['gold']." `2pezzi d'oro che avevi con te!!`n");
        debuglog("perde ".$session['user']['gold']." oro in mano requisiti dallo sceriffo. EVIL = ".$session['user']['evil']);
        $session['user']['gold']=0;
    }
    if ($session['user']['goldinbank']>0) {
        output("`2Perdi`6 ".$session['user']['goldinbank']." `2pezzi d'oro che avevi in banca!!`n");
        debuglog("perde ".$session['user']['goldinbank']." oro in banca requisiti dallo sceriffo. EVIL = ".$session['user']['evil']);
        $session['user']['goldinbank']=0;
    }
}
    if ($_GET['op'] == "newday"){
        if ($session['user']['jail'] == 1) output("Ti risvegli nella tua cella.`n");
        if ($session['user']['evil'] < 100){
            output("Lo Sceriffo ti dice \"Penso che tu abbia imparato la lezione, sei liber".($session[user][sex]?"a":"o").", ");
            output("ma terrò comunque un occhio su di te.  Meglio che righi diritto!\"`n");
            // Maximus, il newday è stato già fatto, modificato l'addnav per mandarlo al villaggio
            //if ($session['user']['jail']==1) addnav("Continua","newday.php");
            if ($session['user']['jail']==1) addnav("Continua","village.php");
            // fine Maximus
            if ($session['user']['jail']==0) redirect("constable.php");
            $session['user']['jail']=0;
            $name=$session['user']['name'];
            addnews("`1$name `1è stat".($session[user][sex]?"a":"o")." rilasciat".($session[user][sex]?"a":"o")." dalla prigione!");
            debuglog("$name è stato rilasciato dalla prigione. EVIL = ".$session['user']['evil']);
            //Sook, cancellazione di eventuali taglie al rilascio
            if($session['user']['bounty'] > 0) $session['user']['bounty'] = 0;
            //fine cancellazione taglie
        }else{
    output("`7Sei nella tua cella, non hai niente da fare.`n`n");
    debuglog("è in prigione ed al newday ha EVIL = ".$session['user']['evil']);
    output("`^In questo momento hai <big>`$".$session['user']['evil']."</big> `^Punti Cattiveria. Verrai rilasciat".($session[user][sex]?"a":"o")." quando ne avrai meno di 100.`n`n`n",true);
    viewcommentary("jail","Lamentati di essere in Prigione",20,5,"si lamenta");
    if ($session['user']['superuser'] >=2) {
       addnav("Grotta Superutente","superuser.php");
       addnav("Nuovo Giorno","newday.php");
    }
    if ($session['user']['jail'] == 1) {addnav("Chiama lo Sceriffo","scerifchance.php");}
    addnav("Gira i pollici","constable.php?op=twiddle");
    addnav("Vai a Dormire","constable.php?op=logout");
    addnav("??`^`bMiniFAQ`b","hints.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("N?`#Notizie Giornaliere","news.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("??`@Lista Guerrieri","list.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    if ($_GET['op'] == "twiddle") output("`n`7Ti giri i pollici per un po'.`n");
}
    }else{
    output("`7Sei nella tua cella, non hai niente da fare.`n`n");
    output("`^In questo momento hai <big>`$".$session['user']['evil']."</big> `^Punti Cattiveria. Verrai rilasciat".($session[user][sex]?"a":"o")." quando ne avrai meno di 100.`n`n`n",true);
    viewcommentary("jail","Lamentati di essere in Prigione",20,5,"si lamenta");
    if ($session['user']['superuser'] >=2) {
       addnav("Grotta Superutente","superuser.php");
       addnav("Nuovo Giorno","newday.php");
    }
    if ($session['user']['jail'] == 1) {addnav("Chiama lo Sceriffo","scerifchance.php");}
    addnav("Gira i pollici","constable.php?op=twiddle");
    addnav("Vai a Dormire","login.php?op=logout",true);
    addnav("??`^`bMiniFAQ`b","hints.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("N?`#Notizie Giornaliere","news.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("??`@Lista Guerrieri","list.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    if ($_GET['op'] == "twiddle") output("`n`7Ti giri i pollici per un po'.`n");
}
}else{
    if ($_GET['op'] == ""){
       output("`8Lo Sceriffo è seduto alla sua scrivania, e osserva con soddisfazione i criminali che ha arrestato.");
       output("Ci sono dei vagabondi in alcune celle, altre sono vuote, e a volte ci sono dei guerrieri un queste ");
       output("celle.  Sono rinchiusi qui per aver commesso atti criminosi come derubare la banca.`n");
       output("Puoi far visita a quelli che sono rinchiusi in Prigione.`n");
       addnav("Controlla i Ricercati","constable.php?op=wanted");
       output("`n`b`i`2Giocatori in Prigione.`b`i`n`8");
       $sql = "SELECT acctid,jail,name,loggedin,laston,sex FROM accounts WHERE jail > 0 AND superuser = 0";
       $result = db_query($sql);
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i=0;$i< db_num_rows($result);$i++){
           $row = db_fetch_assoc($result);
           $injail = $row['name'];
           output("$injail`7 è `6");
           if ($row['jail']==1 and $row['loggedin']==1 AND $row['laston'] > date("Y-m-d H:i:s",strtotime(date("r")."-".getsetting("LOGINTIMEOUT",900)." seconds"))){
               output("`2svegli".($row[sex]?"a":"o")."`7...`6");
           }else{
               output("`4addormentat".($row[sex]?"a":"o")."`7...`6");
           }
       }
       output("`n`n");
       viewcommentary("jail","Tormenta i prigionieri",20,5,"tormenta");
       addnav("Torna al Villaggio","village.php");
    }
    if ($_GET['op'] == "wanted"){
       output("`8I Ricercati per Crimini contro la Società avranno una taglia sulle lore teste, è tuo ");
       output("dovere in qualità di cittadino del villaggio catturarli e condurli dallo Sceriffo.`n`n");
       output("`^Lista Ricercati`n`n");
       $sql = "SELECT acctid,evil,jail,name,loggedin,laston,sex FROM accounts
               WHERE evil>50
               AND jail=0
               AND superuser=0
               ORDER BY evil DESC";
       $result = db_query($sql);
       $countrow = db_num_rows($result);
       for ($i=0; $i<$countrow; $i++){
       //for ($i=0;$i< db_num_rows($result);$i++){
           $row = db_fetch_assoc($result);
           if ($row['evil']>99){
               $isevil = $row['name'];
               output("`\$$isevil`5 è ricercat".($row[sex]?"a":"o")." per crimini contro la società.`6`n");
           }else{
               $isevil = $row['name'];
               output("`2Lo Sceriffo tiene d'occhio `($isevil.`2`n");
           }
       }
       addnav("Continua","constable.php");
    }
    if ($_GET['op'] == "logout"){
        /*
        //Excalibur: registrazione dei tempi di connessione
        // per chi è rimasto connesso per più di X ore (X = 2)
        $check = getsetting("onlinetime",7200); //secondi oltre i quali viene registrato il tempo di connessione  7200 = 2 ore
        if ( (strtotime($session['user']['laston']) - strtotime($session['user']['lastlogin']) ) > $check) {
            $sql1 = "INSERT INTO furbetti
                     (type,acctid,logintime,logouttime)
                     VALUES ('time','".$session['user']['acctid']."','".$session['user']['lastlogin']."','".$session['user']['laston']."')";
            $result1 = db_query($sql1) or die(db_error(LINK));
        }
        //Excalibur: fine
        */
       $session['user']['loggedin'] = 0;
       $session['user']['sconnesso'] = 0;
       $session['user']['location'] = 5;
       $session['user']['locazione'] = 0;
       debuglog("va a dormire nella cella della prigione");
       $sql = "UPDATE accounts SET loggedin=0,location=5,locazione=0 WHERE acctid = ".$session['user']['acctid'];
       db_query($sql) or die(sql_error($sql));
       saveuser();
       $session=array();
       redirect("index.php");
    }
}

page_footer();
?>