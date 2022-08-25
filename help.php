<?php
require_once("common.php");
require_once("common2.php");
addcommentary();
checkday();
    addnav("F.A.Q.");
    addnav("??F.A.Q. (per i principianti)", "petition.php?op=faq",false,true);
    addnav(",?`^`bMiniFAQ`b","hints.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("§?`(`bNuove FAQ`b","newfaq.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("#?`(`bFAQ Player`b","faqplayer.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("@?`%`bTermini di utilizzo`b","termini.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("+?`\$`bElenco Staff`b","about.php?op=staff&ret1=x");
    if(moduli('regolamento')=='on') addnav(",?`\$`bREGOLAMENTO`b","regolamento.php?ret=".urlencode($_SERVER['REQUEST_URI']));
    addnav("!?Preferenze","prefs.php");
    addnav(".?Elenco Guerrieri","list.php");
    addnav("Q?`S`bEsci`b`0 nei campi","login.php?op=logout",true);
    //la riga sottostante DEVE ESSERE == "logd2" nel server ufficiale
    if (getsetting("nomedb","logd") == "logd2"){
        if ($session['user']['gdr'] == "no"){
            addnav("Piazza del Villaggio","village1.php");
        }else{
            addnav("Piazza del Villaggio","village.php");
        }
    }else{
        addnav("Piazza del Villaggio","village.php");
    }
    //let users try to cheat, we protect against this and will know if they try.
    addnav("","superuser.php");
    addnav("","user.php");
    addnav("","taunt.php");
    addnav("","creatures.php");
    addnav("","configuration.php");
    addnav("","badword.php");
    addnav("","armoreditor.php");
    addnav("","bios.php");
    addnav("","badword.php");
    addnav("","donators.php");
    addnav("","referers.php");
    addnav("","retitle.php");
    addnav("","stats.php");
    addnav("","viewpetition.php");
    addnav("","weaponeditor.php");
page_header("L'Ufficio dei Consiglieri");
$session['user']['locazione'] = 163;
if ($session['user']['superuser'] > 2) {
   output("`c`@Ora del Server (`\$per Admin`@): ".date("d-m-Y H:i:s",time())."`c`n");
}
output("`@`c`bGiungi nell'ufficio dei Consiglieri dove potrai trovare le risposte alle domande che ti assillano.`b`c`n");
output("`n`n`%`@Alcuni guerrieri parlottano tra loro, chi chiede consiglio e chi dispensa risposte:`n");
viewcommentary("help","Aggiungi",30,10);

if($session['user']['euro']<1 AND $session['user']['superuser']==0)addnav('<center><script type="text/javascript"><!--
google_ad_client = "pub-8533296456863947";
google_ad_width = 125;
google_ad_height = 125;
google_ad_format = "125x125_as";
google_ad_type = "text_image";
google_ad_channel ="";
google_color_border = "804000";
google_color_bg = "78B749";
google_color_link = "804000";
google_color_text = "000000";
google_color_url = "6F3C1B";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script></center><br>','',true);
page_footer();
?>