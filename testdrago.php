<?php
require_once "common.php";
page_header("Test Drago");
addnav("Villaggio","village.php");
addnav("Test Drago","testdrago.php");
    //Excalibur: modifica per crescita drago
    if ($session['user']['id_drago'] != 0) {
       if (e_rand(1,100) > 97) {
          $sql = "SELECT crescita, aspetto, eta_drago FROM draghi WHERE id = ".$session['user']['id_drago'];
          $result=db_query($sql) or die(db_error(LINK));
          $row = db_fetch_assoc($result) or die(db_error(LINK));
          if ($row['eta_drago'] != "antico" ){
             output("`@Congratulazioni !!! Il tuo drago è cresciuto ed è passato da ".$etadrago[($row['eta_drago']+0)]);
             output("a ".$etadrago[($row['eta_drago']+1)]."!!!!`n`n");
             $sqlseteta = "UPDATE draghi SET eta_drago = eta_drago+1 WHERE id = ".$session['user']['id_drago'];
             $resultseteta=db_query($sqlseteta) or die(db_error(LINK));
          }else {
             output("`#Il tuo drago ha già raggiunto la massima età consentita e non può invecchiare oltre.`n");
             output("Congratulazioni per la costanza con cui hai allevato il tuo drago !!!`n`n");
          }
       }
       if ($row['aspetto'] == "Pessimo") {
          $bonuseta = 1 * $row['crescita'];
       } else if ($row['aspetto'] == "Brutto") {
          $bonuseta = 2 * $row['crescita'];
       } else if ($row['aspetto'] == "Normale") {
          $bonuseta = 3 * $row['crescita'];
       } else if ($row['aspetto'] == "Buono") {
          $bonuseta = 4 * $row['crescita'];
       } else if ($row['aspetto'] == "Ottimo") {
          $bonuseta = 5 * $row['crescita'];
       }
       $bonusatt = intval(e_rand(($bonuseta/2), $bonuseta));
       $bonusdif = intval(e_rand(($bonuseta/2), $bonuseta));
       $bonussof = intval(e_rand(($bonuseta/2), $bonuseta));
       $bonuscar = intval(e_rand(($bonuseta/2), $bonuseta));
       $bonusvit = intval($bonuseta);
       $sqlbonus = "UPDATE draghi SET
       attacco = attacco+".$bonusatt.",
       difesa = difesa+".$bonusdif.",
       danno_soffio = danno_soffio+".$bonussof.",
       carattere = carattere+".$bonuscar.",
       vita = vita+".$bonusvit.",
       vita_restante = vita
       WHERE id = ".$session['user']['id_drago'];
       $resultbonus=db_query($sqlbonus) or die(db_error(LINK));
    }
    //Excalibur: fine modifica crescita drago
page_footer();
?>