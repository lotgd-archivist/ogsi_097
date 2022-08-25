<?php
if (isset($_POST['template'])){
    setcookie("template",$_POST['template'],strtotime(date("r")."+45 days"));
    $_COOKIE['template']=$_POST['template'];
}
require_once("common.php");
require_once("common2.php");
page_header("Preferenze");

global $session ;

if ($_GET['op']=="suicide" && getsetting("selfdelete",0)!=0) {
    if($session['user']['acctid']==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
    if($session['user']['acctid']==getsetting("hasblackegg",0)) savesetting("hasblackegg",stripslashes(0));
    //Modifica PvP Online
    $sql = "DELETE FROM pvp WHERE acctid2=$_GET[userid] OR acctid1=$_GET[userid]";
    db_query($sql) or die(db_error(LINK));
    //Fine modifica PvP Online
    $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$_GET[userid]";
    db_query($sql);
    $sql = "DELETE FROM accounts WHERE acctid='$_GET[userid]'";
    db_query($sql);
    $sql = "UPDATE items SET owner = 0 WHERE owner=$_GET[userid]";
    db_query($sql);
    $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$_GET[userid] AND status=1";
    db_query($sql);
    $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$_GET[userid] AND status=0";
    db_query($sql);

    output("Il tuo personaggio è stato cancellato!");
    addnews("`#{$session['user']['name']} si è suicidato.");
    addnav("Pagina di Login", "index.php");
    $session=array();
    $session['user'] = array();
    $session['loggedin'] = false;
    $session['user']['loggedin'] = false;
    //inventory/////////////////////////////////////

}else if ($_GET['op']=="inventory") {
    output("`c`bLe Proprietà di ".$session['user']['name']."`b`c`n`n");
    output(" ".$session['user']['name']." hai `^".$session['user']['gold']."`0 Oro e `#".$session['user']['gems']."`0 Gemme `n");
    output("<table cellspacing = 0 cellpadding = 2 align =' center'><tr><td>`bOggetto`b</td><td>`bClasse`b</td><td>`bValore 1`b</td><td>`bValore 2`b</td><td>`bPrezzo di Vendita`b</td></tr>",true);
    $sql = "SELECT * FROM items WHERE owner =".$session['user']['acctid']." and class <> 'Key' ORDER BY class ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iNon hai nulla nell'Inventario`i`0</td></tr>",true);
    }else{
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
            $item = db_fetch_assoc($result);
            ($i%2==1?"trlight":"trdark");
            output("<tr class='$bgcolor'><td>`&".$item['name']."`0</td><td>`!".$item['class']."`0</td><td align ='right'>".$item['value1']."</td><td align ='right'>".$item['value2']."</td><td>",true);
            if ($item['gold']==0 && $item['gems']==0){
                output("`4Non in vendita`0");
            }else{
                output("`^".$item['gold']."`0 Oro, `#".$item['gems']."`0 Gemme" );
            }
            // output("</td></tr><tr class='$bgcolor'><td align =' right'>Descrizione:</td><td colspan = 4>".$item['description']."</td></tr>",true);
        }
    }
    output("</table>",true);
    addnav("Preferenze","prefs.php");

    //inventory/////////////////////////////////////

} else {

    checkday();
    if ($_GET['az']=="incassa") pickup_to_pg();
    if ($session['user']['alive']){
        addnav("Incassa Premiazione","prefs.php?az=incassa");
        addnav("Torna al Villaggio","village.php");
    }else{
        addnav("Torna alle Notizie","news.php");
    }
    addnav("Biografia Personaggio","bioeditor.php?id=".$session['user']['acctid']." ");
    if ($session['user']['superuser'] > 1) addnav("Preferenza Superutente","suprefs.php");
    if (count($_POST)==0){
    }else{
        $pass1 = md5($_POST['pass1']);
        $pass2 = md5($_POST['pass2']);
        if ($_POST['pass1']!=$_POST['pass2']){
            output("`#La password non corrisponde.`n");
        }else{
            if ($_POST['pass1']!=""){
                if (strlen($_POST['pass1'])>3){
                    $session['user']['password']=$pass1;
                    output("`#La password è stata modificata.`n");
                }else{
                    output("`#La password è troppo corta.  Deve essere almeno di 4 caratteri.`n");
                }
            }
        }
        reset($_POST);
        $nonsettings = array("pass1"=>1,"pass2"=>1,"email"=>0,"template"=>1,"bio"=>1);
        //$nonsettings = array("pass1"=>1,"pass2"=>1,"email"=>1,"template"=>1,"bio"=>1);
        while (list($key,$val)=each($_POST)){
            if (!$nonsettings[$key]) $session['user']['prefs'][$key]=$_POST[$key];
        }
       /* $resultbio = db_query("SELECT * FROM bio WHERE bioacctid=".$session['user']['acctid']." ");
   		$rowbio = db_fetch_assoc($resultbio);
        if (stripslashes($_POST['bio'])!=$rowbio['bio']){
            //print("Lunghezza bio: ".strlen($_POST['bio']));
            if ($rowbio['biotime']>"9000-01-01"){
                output("`n`\$Non puoi modificare la tua bio, è stata bloccata dagli amministratori!`0`n");
            }elseif (strlen($_POST['bio'])>500){
                output("`n`\$<big>La bio supera di ".(strlen($_POST['bio'])-500)." caratteri la lunghezza massima consentita!</big>`0`n",true);
            }else{
                report(3,"`GModifica Bio di `&".$session['user']['login']."`0","`&".$session['user']['login']."`2 ha modificato/inserito la propria bio`n".stripslashes($_POST['bio']),"biografia");
                $rowbio['bio'] = stripslashes($_POST['bio']);
                $rowbio['biotime'] = date("Y-m-d H:i:s");
                $sql = "UPDATE bio SET bio='".$rowbio['bio']."',biotime='".$rowbio['biotime']."' WHERE bioacctid = ".$session['user']['acctid']." ";
    			db_query($sql);
                //$session['user']['bio']=stripslashes($_POST['bio']);
                //$session['user']['biotime']=date("Y-m-d H:i:s");
            }
        }*/
        if ($_POST['email']!=$session['user']['emailaddress']){
            if (is_email($_POST['email'])){
                if (getsetting("requirevalidemail",0)==1){
                    output("`#`uNon puoi modificare la tua mail`u, i settaggi di sistema te lo impediscono.
                    (Le email non possono essere cambiate se il server richiede un indirizzo email valido).
                    Usa il link Richiesta d'Aiuto per chiedere all'amministratore di cambiare la tua mail
                    se questa non è più valida.`n`n");
                }else{
                    output("`#Il tuo indirizzo mail è stato modificato.`n");
                    $session['user']['emailaddress']=$_POST['email'];
                }
            }else{
                if (getsetting("requireemail",0)==1){
                    output("`#Questa non è una mail valida.`n");
                }else{
                    output("`#Il tuo indirizzo mail è stato modificato.`n");
                    $session['user']['emailaddress']=$_POST['email'];
                }
            }
        }
        output("Settaggi Salvati");
    }

    if ($session['user']['superuser'] > 1){
        $form=array(
        "Preferenze,title"
        ,"firma"=>"Attiva banner per firma?,bool"
        ,"emailonmail"=>"Spedisci mail quando ricevi LoGD Mail?,bool"
        ,"systemmail"=>"Spedisci mail per i messaggi di sistema?,bool"
        ,"dirtyemail"=>"Consenti profanità nei messaggi di posta di LoGD?,bool"
        ,"percentuali"=>"Visualizza percentuali nelle barre di stato?,bool"
        ,"villaggio_nav"=>"Espandi i link all'interno del villaggio?,bool"
        ,"language"=>"Linguaggi (Non Completati),enum,en,English,de,Deutsch,dk,Danish,es,Español,it,Italiano"
        ,"petizioni"=>"Ricevi mail quando inserita petizione?,bool"
        ,"query"=>"Visualizza Slow Query?,bool"
        //,"bio"=>"Piccola Biografia Personaggio (500 chars max),text"
        );
    }else{
        $form=array(
        "Preferenze,title"
        ,"firma"=>"Attiva banner per firma?,bool"
        ,"emailonmail"=>"Spedisci mail quando ricevi LoGD Mail?,bool"
        ,"systemmail"=>"Spedisci mail per i messaggi di sistema?,bool"
        ,"dirtyemail"=>"Consenti profanità nei messaggi di posta di LoGD?,bool"
        ,"percentuali"=>"Visualizza percentuali nelle barre di stato?,bool"
        ,"villaggio_nav"=>"Espandi i link all'interno del villaggio?,bool"
        ,"language"=>"Linguaggi (Non Completati),enum,en,English,de,Deutsch,dk,Danish,es,Español,it,Italiano"
        //,"bio"=>"Piccola Biografia Personaggio (500 chars max),text"
        );
    }
    output("
    <form action='prefs.php?op=save' method='POST'>",true);
    if ($handle = @opendir("templates")){
        $skins = array();
        while (false !== ($file = @readdir($handle))){
            if (strpos($file,".htm")>0){
                array_push($skins,$file);
            }
        }
        sort($skins);
        if (count($skins)==0){
            output("`b`@Aww, il tuo amministratore ha deciso che non ti è permesso avere nessuna skin.  Lamentati con lui, non con me.`n");
        }else{
            output("<b>Skin:</b><br>",true);
            while (list($key,$val)=each($skins)){
                output("<input type='radio' name='template' value='$val'".($_COOKIE['template']==""&&$val=="yarbrough.htm" || $_COOKIE['template']==$val?" checked":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
            }
        }
    }else{
        output("`c`b`\$ERRORE!!!`b`c`&Non posso aprire la cartella templates!  Per favore avvisa l'amministratore!!");
    }

    output("
    Nuova Password: <input name='pass1' type='password'> (lascia vuoto se non vuoi cambiarla)`n
    Riscrivi Password: <input name='pass2' type='password'>`n
    Indirizzo Mail: <input name='email' value=\"".HTMLEntities($session['user']['emailaddress'])."\">`n
    ",true);
    $prefs = $session['user']['prefs'];
	
    $prefs['bio'] = $rowbio['bio'];
    showform($form,$prefs);
    output("
    </form>",true);
    addnav("","prefs.php?op=save");
    addnav("Inventario","prefs.php?op=inventory");
    // Stop clueless users from deleting their character just because a
    // monster killed them.
    if ($session['user']['alive'] && getsetting("selfdelete",0)!=0) {
        output("<form action='prefs.php?op=suicide&userid={$session['user']['acctid']}' method='POST'>",true);
        output("<input type='submit' class='button' value='Cancella Personaggio' onClick='Return conferma(\"Sei sicuro di voler cancellare il tuo personaggio?\");'>", true);
        output("</form>",true);
        addnav("","prefs.php?op=suicide&userid={$session['user']['acctid']}");
    }
    if($session['user']['prefs']['firma']==1){
        output('La prima immagine verrà generata allo scadere della prossima ora fino a quel momento non visualizzerai l\'immagine. Appena verrà generata la vedrai sotto il codice, da quel momento puoi inserire la firma nei forum che vuoi!`n');
        output('Codice firma per forum BBCODE:`n');
        output('[img align=left]http://www.ogsi.it/logd/images/tmp/'.strtolower($session['user']['login']).'.jpg[/img]`n`n');
        output('Codice firma per forum con link referral BBCODE:`n');
        output('[url=http://www.ogsi.it/logd/referral.php?r='.$session['user']['login'].'][img align=left]http://www.ogsi.it/logd/images/tmp/'.strtolower($session['user']['login']).'.jpg[/img][/url]`n`n');
        output('Codice firma HTML:`n');
        output('<img src="http://www.ogsi.it/logd/images/tmp/'.strtolower($session['user']['login']).'.jpg">`n`n');
        output('Codice firma HTML con link referral:`n');
        output('<a href="http://www.ogsi.it/logd/referral.php?r='.$session['user']['login'].'"><img src="http://www.ogsi.it/logd/images/tmp/'.strtolower($session['user']['login']).'.jpg"></a>`n`n');
        $sql = "SELECT * FROM banner WHERE id_player='".$session['user']['acctid']."'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if($row['id_player']!=$session['user']['acctid']){
            $sqli = "INSERT INTO banner (id_player) VALUES ('".$session['user']['acctid']."')";
            $resulti=db_query($sqli);
        }
        output('<img src="http://www.ogsi.it/logd/images/tmp/'.strtolower($session['user']['login']).'.jpg">`n`n',true);
    }else{
        //$sql = "DELETE FROM banner WHERE id_player = '".$session['user']['acctid']."'";
        //db_query($sql);
    }
}
page_footer();
?>