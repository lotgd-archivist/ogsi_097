<?php
require_once("common.php");
require_once("common2.php");
$trash = getsetting("expiretrashacct",1);
$new = getsetting("expirenewacct",10);
$old = getsetting("expireoldacct",45);

checkban();

if ($_GET['op']=="val"){
    $sql = "SELECT login,password FROM accounts WHERE emailvalidation='$_GET[id]' AND emailvalidation!=''";
    $result = db_query($sql);
    if (db_num_rows($result)>0) {
	    
	    output('<!-- Google Code for Registrazione Conversion Page -->
	    <script language="JavaScript" type="text/javascript">
	    <!--
	    var google_conversion_id = 1065441291;
	    var google_conversion_language = "en";
	    var google_conversion_format = "1";
	    var google_conversion_color = "ffffff";var google_conversion_label = "signup";
	    //-->
	    </script>
	    <script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
	    </script>
	    <noscript>
	    <img height="1" width="1" border="0" src="http://www.googleadservices.com/pagead/conversion/1065441291/?label=signup&amp;guid=ON&amp;script=0"/>
	    </noscript>',true);
	    
	    
        /*output('<!-- Google Code for SIGNUP Conversion Page -->
        <script language="JavaScript" type="text/javascript">
        <!--
        var google_conversion_id = 1065441291;
        var google_conversion_language = "it";
        var google_conversion_format = "1";
        var google_conversion_color = "006600";
        if (1) {
            var google_conversion_value = 1;
        }
        var google_conversion_label = "SIGNUP";
        //-->
        </script>
        <script language="JavaScript" src="http://www.googleadservices.com/pagead/conversion.js">
        </script>
        <noscript>
        <img height=1 width=1 border=0 src="http://www.googleadservices.com/pagead/conversion/1065441291/?value=1&label=SIGNUP&script=0">
        </noscript>',true);*/
        $row = db_fetch_assoc($result);
        $sql = "UPDATE accounts SET emailvalidation='' WHERE emailvalidation='".$_GET['id']."' AND emailvalidation!=''";
        db_query($sql);
        //output("`#`cLa tua email è stata validata. Puoi fare il login. `c`0");
        output("<form action='login.php?az=validate' method='POST'><input name='name' value='".$row['login']."' type='hidden'><input name='password' value='".$row['password']."' type='hidden'>
        La tua email è stata validata, il tuo nome di login è `^".$row['login']."`0.  `n`n<input type='submit' class='button' value='Clicca qui per il login'></form>`n`n"
        .($trash>0?"Un personaggio che non si è mai connesso verrà cancellato dopo $trash giorni di inattività.`n":"")
        .($new>0?"Un personaggio che non ha mai raggiunto il livello 2 verrà cancellato dopo $new giorni di inattività.`n":"")
        .($old>0?"Un personaggio che ha raggiunto almeno una volta il livello 2 verrà cancellato dopo $old giorni di inattività.":"")
        ."",true);
        output("`n`n`2`b<big>L'utilizzo di un linguaggio volgare e/o aggressivo sarà perseguito con un ban di 3 giorni.</big>`n",true);
        output("<big>`3Scatenare Flame-War porterà alla cancellazione del personaggio ed al ban dal server.</big>`n",true);
        output("<big>`#Gli admin si riservano di intraprendere anche altre azioni nei confronti di chi sfrutterà</big>`n",true);
        output("<big>`@a proprio vantaggio 'buchi' di programmazione senza segnalarli agli admin stessi.</big>`n",true);
        output("<big>`6Altre situazioni non contemplate in queste poche righe, atte a screditate, offendere o attaccare`n</big>",true);
        output("<big>`^gli altri giocatori verranno trattare con il massimo della severità. </big>`n`n",true);
        output("<big>`%Si consiglia inoltre la lettura delle `^F.A.Q. `%e delle `^MiniFAQ `%prima di iniziare a giocare.`b</big>",true);
        addnav("","login.php?az=validate");
    }else{
        output("`#La tua email non può essere verificata.  Ciò può essere dovuto al fatto che hai già validato la tua email.  Fai il login, e se ciò non ti aiuta, usa il petition link in fondo alla pagina.");
    }
}
if ($_GET['op']=="forgot"){
    page_header("Recupero Password");
    if ($_POST['charname']!=""){
        $sql = "SELECT login,emailaddress,emailvalidation,password FROM accounts WHERE login='".$_POST['charname']."'";
        $result = db_query($sql);
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
            if (trim($row['emailaddress'])!=""){
                if ($row['emailvalidation']==""){
                    $row['emailvalidation']=substr("x".md5(date("Y-m-d H:i:s").$row['password']),0,32);
                    $sql = "UPDATE accounts SET emailvalidation='".$row['emailvalidation']."' where login='".$row['login']."'";
                    db_query($sql);
                }
                $ok=mail(
                $row['emailaddress'],
                "Verifica Account LoGD",
                "Qualcuno da ".$_SERVER['REMOTE_ADDR']." ha richiesto il recupero della password per il tuo account.  Se sei stato tu, ecco qua"
                ." il link, puoi cliccarlo per entrare nel tuo account e cambiare la password dalla pagina delle preference nel villaggio.\n\n"
                ."Se non hai richiesto questa mail, non lamentarti, tu sei quello che ha ricevuto questa email, non loro."
                ."\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=".$row['emailvalidation']."\n\nGrazie per giocare con LoGD Italia!",
                "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                );
                if ($ok=='' || $ok==1) {
                    output("`#Spedita una nuova validation email all'indirizzo di quell'account. Puoi utilizzare la email per loggarti e cambiare la tua password.");
                } else {
                    output("`n`n\$Il comando non ha funzionato e la mail non è stata inviata. Prova a ripetere l'operazione tra qualche minuto, e se l'operazione continua a fallire ");
                    output("contatta lo staff tramite petizione.`n");
                    report(3,"`3Fallito invio email","`3E' stato chiesto l'invio di una mail a ".$row['emailaddress']." per il recupero password di ".$row['login'].", ma l'invio mail non è andato a buon fine.`n`n`0");
                }                
            }else{
                output("`#Siamo spiacenti, ma quell'account non ha un indirizzo email associato, e quindi non possiamo aiutarti con la tua
                password.  Usa il link \"`4Richiesta d'Aiuto`#\" in basso alla pagina per
                la richiesta di aiuto a risolvere il tuo problema.");
            }
        }else{
            output("`#Non trovo nessun personaggio con quel nome.  Controlla l'Elenco Guerrieri alla pagina di login per verificare che il personaggio non sia spirato e sia stato cancellato.");
        }
    }else{
        output("<form action='create.php?op=forgot' method='POST'>
        `bPassword Dimenticata:`b`n`n
        Scrivi il nome del tuo personaggio: <input name='charname'>`n
        <input type='submit' class='button' value='Spediscimi la password via mail'>
        </form>",true);
    }
}
if ($_GET['op']!="forgot") page_header("Crea un Personaggio");
if ($_GET['op']=="create"){
    if(getsetting("spaceinname",0) == 0) {
        $shortname = preg_replace("([^[:alpha:]_-])","",$_POST['name']);
    } else {
        $shortname = preg_replace("([^[:alpha:] _-])","",$_POST['name']);
    }

    if (soap($shortname)!=$shortname){
        output("`\$Error`^: E' stato trovato del linguaggio scorretto nel tuo nome, riconsideralo.");
        $_GET['op']="";
    }else{
        $blockaccount=false;
        if (getsetting("blockdupeemail",0)==1 && getsetting("requireemail",0)==1){
            $sql = "SELECT login FROM accounts WHERE emailaddress='".$_POST['email']."'";
            $result = db_query($sql);
            if (db_num_rows($result)>0){
                $blockaccount=true;
                $msg.="Puoi avere un solo account.`n";
            }
        }
        if (strlen($_POST['pass1'])<=3){
            $msg.="La tua password DEVE essere lunga almeno 4 caratteri.`n";
            $blockaccount=true;
        }
        if ($_POST['pass1']!=$_POST['pass2']){
            $msg.="Password non verificata.`n";
            $blockaccount=true;
        }
        if (strlen($shortname)<4){
            $msg.="Il tuo nome DEVE essere lungo almeno 4 caratteri.`n";
            $blockaccount=true;
        }
        if (strlen($shortname)>25){
            $msg.="Il tuo nome non può superare i 25 caratteri.`n";
            $blockaccount=true;
        }
        if (getsetting("requireemail",0)==1 && is_email($_POST['email']) || getsetting("requireemail",0)==0){
        }else{
            $msg.="Devi scrivere un indirizzo email valido.`n";
            $blockaccount=true;
        }
        /*
        if ($_POST[pass1]==$_POST[pass2]
        && strlen($_POST[pass1])>3
        && strlen($shortname)>2
        && !$blockaccount
        && (
        getsetting("requireemail",0)==1
        && is_email($_POST[email])
        || getsetting("requireemail",0)==0
        )
        ){*/
        if (!$blockaccount){
            $sql = "SELECT name FROM accounts WHERE login='$shortname'";
            $result = db_query($sql) or die(db_error(LINK));
            $sqlbis = "SELECT name FROM accounts_deleted WHERE login='$shortname'";
            $resultbis = db_query($sqlbis) or die(db_error(LINK));
            if (db_num_rows($result)>0 OR db_num_rows($resultbis)>0){
                output("`\$Errore`^: Esiste (o esisteva) già qualcuno con quel nome in questo reame, cambialo per favore.");
                $_GET['op']="";
            }else{
                $title = ($_POST['sex']?"Contadina":"Contadino");
                if (getsetting("requirevalidemail",0)){
                    $emailverification=md5(date("Y-m-d H:i:s").$_POST['email']);
                }
                if ($_GET['r']>""){
                    $sql = "SELECT acctid FROM accounts WHERE login='".$_GET['r']."'";
                    $result = db_query($sql);
                    $ref = db_fetch_assoc($result);
                    $referer=$ref['acctid'];
                }else{
                    $referer=0;
                }
                // secure password mod
                $pass=md5($_POST['pass1']);
                //end mod
                $sql = "INSERT INTO accounts
                    (name,
                    title,
                    password,
                    sex,
                    login,
                    laston,
                    uniqueid,
                    lastip,
                    superuser,
                    gold,
                    emailaddress,
                    emailvalidation,
                    referer,
                    consono
                ) VALUES (
                    '$title $shortname',
                    '$title',
                    '$pass',
                    '".$_POST['sex']."',
                    '$shortname',
                    '".date("Y-m-d H:i:s",strtotime(date("r")."-1 day"))."',
                    '$_COOKIE[$DB_NAME]',
                    '".$_SERVER['REMOTE_ADDR']."',
                    ".getsetting("superuser",0).",
                    ".getsetting("newplayerstartgold",50).",
                    '".$_POST['email']."',
                    '$emailverification',
                    '$referer',
                    '0'
                )";
                db_query($sql) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0){
                    output("`\$Errore`^: Il tuo account non è stato creato per una ragione sconosciuta, riprova. ");
                }else{
                    if ($emailverification!=""){
                        savesetting("newplayer",addslashes("$title $shortname"));
                        $ok=mail(
                        $_POST['email'],
                        "LoGD Verifica Account",
                        "Per poter verificare il tuo account, devi cliccare sul link che trovi qui sotto.\n\n  http://".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."?op=val&id=$emailverification\n\nGrazie per giocare con noi!",
                        "From: ".getsetting("gameadminemail","postmaster@localhost.com")
                        );
                        if ($ok=='' || $ok==1) {
                            output("`4Una email è stata spedita a `\$".$_POST['email']."`4 per la verifica del tuo indirizzo.  Clicca il link nella email per attivare il tuo account.`0`n`n");
                        } else {
                            output("`n`n\$Il comando non ha funzionato e la mail non è stata inviata. Lo staff è già stato allertato dell'inconveniente e provvederà a contattarti tramite l'email inserita per la convalida dell'account, ma puoi comunque aprire una petizione e segnalare eventuali difficoltà.`n");
                            report(3,"`3Fallito invio email","`3E' stato chiesto l'invio di una mail a ".$_POST['email']." per la creazione del personaggio ".$shortname.", ma l'invio mail non è andato a buon fine.`n`n`0");
                        }
                    }else{
                        output("<form action='login.php' method='POST'><input name='name' value=\"$shortname\" type='hidden'><input name='password' value=\"$pass\" type='hidden'>
                        Il tuo personaggio è stato creato, il tuo nome utente è `^$shortname`0.  `n`n<input type='submit' class='button' value='Clicca qui per entrare'></form>`n`n"
                        .($trash>0?"Un personaggio che non si è mai connesso verrà cancellato dopo $trash giorni di inattività.`n":"")
                        .($new>0?"Un personaggio che non ha mai raggiunto il livello 2 verrà cancellato dopo $new giorni di inattività.`n":"")
                        .($old>0?"Un personaggio che ha raggiunto almeno una volta il livello 2 verrà cancellato dopo $old giorni di inattività.":"")
                        ."",true);
                        output("`n`n`2`b<big>L'utilizzo di un linguaggio volgare e/o aggressivo sarà perseguito con un ban di 3 giorni.</big>`n",true);
                        output("<big>`3Scatenare Flame-War porterà alla cancellazione del personaggio ed al ban dal server.</big>`n",true);
                        output("<big>`#Gli admin si riservano di intraprendere anche altre azioni nei confronti di chi sfrutterà</big>`n",true);
                        output("<big>`@a proprio vantaggio 'buchi' di programmazione senza segnalarli agli admin stessi.</big>`n",true);
                        output("<big>`6Altre situazioni non contemplate in queste poche righe, atte a screditate, offendere ed attaccare </big>",true);
                        output("<big>`^gli altri giocatori verranno trattare con il massimo della severità. `b</big>`n",true);
                        output("<big>`\$L'utilizzo di macro è assolutamente VIETATO comporta il BAN istantaneo e permanente. `b</big>`n",true);
                    }
                }
            }
        }else{
            /*
            output("`\$Error`^: Your password must be at least 4 characters long,
            your name must be at least 3 characters long,
            ".(getsetting("requireemail",0)==1?"you must enter a valid email address, ":"")."
            ".(getsetting("blockdupeemail",0)==1?"you must not have any other accounts by that email address, ":"")."
            and your passwords must match.");
            */
            output("`\$Errore`^:`n$msg");
            $_GET_['op']="";
        }
    }
}
if ($_GET['op']==""){
    output("`&`c`bCrea un Personaggio`b`c");
    output("`0<form action=\"create.php?op=create".($_GET['r']>""?"&r=".HTMLEntities($_GET['r']):"")."\" method='POST'>",true);
    output("Con che nome sarai noto in questo mondo? <input name='name'>`n",true);
    output("(Consulta il Regolamento al punto 3 per le regole sui nick accettati)`n");
    output("Digita una password: <input type='password' name='pass1'>`n",true);
    output("Riscrivi la password: <input type='password' name='pass2'>`n",true);
    output("Digita il tuo indirizzo e-mail: <input name='email'> ".(getsetting("requireemail",0)==0?"(opzionale -- tuttavia, se decidi di non inserirlo, non avrai nessuna possibilità di recuperare la password se te la dimentichi!)":"(required".(getsetting("requirevalidemail",0)==0?"":", una mail verrà inviata a questo indirizzo per verifica prima che tu possa fare il login").")")."`n",true);
    output("E sei <input type='radio' name='sex' value='1'>Femmina o <input type='radio' name='sex' value='0' checked>Maschio?`n",true);
    output("<input type='submit' class='button' value='Crea il tuo personaggio'></form><br>",true);
    output("<big>`^IMPORTANTE: per garantire l'invio della mail di convalida, ATTENDERE il caricamento della pagina di conferma</big> (può volerci del tempo)<br>",true);
}
addnav("Login","index.php");
page_footer();
?>