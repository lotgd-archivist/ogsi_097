<?php
require_once "common.php";
require_once("common2.php");
$chiesa = array(0=>"nessuno",
                1=>"sgrios",
                2=>"karnak",
                3=>"drago");
$dio=$session['user']['dio'];
page_header("Ambasciata Città dei Draghi");
$chiesavincente = getsetting("chiesavincente","nessuno");
$datachiesavittoria = getsetting("datachiesavittoria","0");
if ($_GET['op']==""){
    output("`@Arrivi davanti all'ambasciata della Città dei Draghi!");
    output("Due imponenti energumeni sorvegliano l'entrata.`n`n");
    if ($chiesa[$dio] == $chiesavincente AND (time()-$datachiesavittoria)<259200) {
          output("Le guardie ti squadrano ben bene e ti chiedono il lasciapassare rilasciato agli appartenenti`n");
          output("della setta di `#$chiesavincente`@ che ha vinto l'ultimo scontro.`n`n");
          output("Dopo aver verificato che tutto sia in regola, ti fanno passare congratulandosi per ");
          output("la recente vittoria.`n");
          addnav("E?Entra","ambasciata.php?op=entra");
    }elseif ($chiesa[$dio] == $chiesavincente AND (time()-$datachiesavittoria)>=259200 AND donazioni_usi('pass_draghi')==0) {
          output("Le guardie ti squadrano ben bene e ti chiedono il lasciapassare rilasciato agli appartenenti`n della ");
          output("setta di `#$chiesavincente`@ che ha vinto l'ultimo scontro.`n`n");
          output("Dopo aver verificato i tuoi documenti, ti dicono dispiaciute che si, la tua setta ha vinto ");
          output("il recente scontro, ma che sei arrivato in ritardo!`n");
    }elseif (donazioni('pass_draghi')==true){
       if (donazioni_usi('pass_draghi')==0){
          output("Tu mostri il tuo lasciapassare falsificato, ma loro dopo averlo esaminato te lo restituiscono`n");
          output("consigliandoti un falsario migliore di quello da cui ti sei servito, e ti bloccano l'accesso!`n`n");
          output("Non ti resta altro da fare che tornare ad invocare `#$chiesa[$dio]`@ con le pive nel sacco!`n`n");
       }else{
          $usi=donazioni_usi('pass_draghi');
          if ($chiesa[$dio] == $chiesavincente AND (time()-$datachiesavittoria)>=259200) {
             output("Le guardie ti squadrano ben bene e ti chiedono il lasciapassare rilasciato agli appartenenti`n della ");
             output("setta di `#$chiesavincente`@ che ha vinto l'ultimo scontro.`n`n");
             output("Dopo aver verificato i tuoi documenti, ti dicono dispiaciute che si, la tua setta ha vinto ");
             output("il recente scontro, ma che sei arrivato in ritardo! Controllano poi il tuo pass.`n`n");
          }
          output("Dopo aver verificato che tutto sia in regola con il tuo pass, ti fanno passare dicendoti che ti restano $usi accessi!");
          output("`n");
          debuglog("entra all'ambasciata con pass donazioni. Restano $usi accessi");
          addnav("E?Entra","ambasciata.php?op=entrapass");
       }
    }
    addnav("V?Torna al Villaggio","village.php");

}elseif ($_GET['op']=="entra"){
    addnav("Portale magico","terre_draghi.php?op=citta");
    output("`3Dopo aver attraversato il portale magico per la citta dei draghi, giungi ");
    output("finalmente in quella che è conosciuta come la `@Terra dei Draghi`3, lo straordinario ");
    output("spettacolo che ti si para dinanzi agli occhi ti lascia senza fiato!! Una inospitale ");
    output("ma quantomeno magnifica landa vulcanica, con i suoi tesori da scoprire e le sue ");
    output("sfide da affrontare.`n Ora, solo con il tuo fido drago, devi fronteggiare i tuoi ");
    output("pari per il dominio di questa terra ... sarai in grado di farlo?`n`n");
    output("A qualche lega di distanza noti un vulcano dormiente che sembra però in procinto di risvegliarsi`n`n");
    output("`n`n");
    addnav("Torna al Villaggio","village.php");
}elseif ($_GET['op']=="entrapass"){
    addnav("Portale magico","terre_draghi.php?op=citta&pass=on");
    output("`3Dopo aver attraversato il portale magico per la citta dei draghi, giungi ");
    output("finalmente in quella che è conosciuta come la `@Terra dei Draghi`3, lo straordinario ");
    output("spettacolo che ti si para dinanzi agli occhi ti lascia senza fiato!! Una inospitale ");
    output("ma quantomeno magnifica landa vulcanica, con i suoi tesori da scoprire e le sue ");
    output("sfide da affrontare.`n Ora, solo con il tuo fido drago, devi fronteggiare i tuoi ");
    output("pari per il dominio di questa terra ... sarai in grado di farlo?`n`n");
    output("A qualche lega di distanza noti un vulcano dormiente che sembra però in procinto di risvegliarsi`n`n");
    output("`n`n");
    addnav("Torna al Villaggio","village.php");
}

page_footer();

?>