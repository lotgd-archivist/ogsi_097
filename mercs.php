<?php
/*
Travelling Mercenaries - Forest Special
By Robert (Maddnet) and Talisman (DragonPrime)
version 0.5 May 15, 2004
Most recent copy available at http://dragonprime.cawsquad.net
Translated in italian by Excalibur www.ogsi.it/logd
*/

if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`n`n`2Ti imbatti in una carovana di guerrieri mercenari. Urlano come dei forsennati attirando la tua attenzione. `n
    Cercano di ottenere il tuo favore, desiderando unirsi a te nella battaglia.  `nPer il misero prezzo di 1 gemma, uno di essi si unirà a te. `n
    Che cosa vuoi fare ?");
    addnav("Getta una Gemma","forest.php?op=give");
    addnav("Non se ne parla","forest.php?op=dont");
    $session['user']['specialinc']="mercs.php";
}else if ($_GET['op']=="give"){
  if ($session['user']['gems']>0){
      output("`n`n`%Sei indeciso su quale mercenario scegliere, perciò dici loro di mettersi in fila dal lato opposto del carro, e lanci una gemma oltre il vagone.");
      output(" Colui che riuscirà ad afferrarla avrà l'onore di unirsi a te in battaglia ...`n`n ");
        $session['user']['gems']--;
        debuglog("paga 1 gemma alla carovana di mercenari");
          $mercturns=(e_rand(15,25));
        switch(e_rand(1,7)){
          case 1:
              output("Grimlock il Paladino afferra la gemma e combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Paladino","rounds"=>$mercturns,"wearoff"=>"Il Paladino si è stancato, e ti abbandona per andare in cerca di una birra.","defmod"=>1.2,"atkmod"=>1.5,"roundmsg"=>"Il Paladino estrae la sua spada e attacca con frenesia.","activate"=>"defense");
                break;
          case 2:
              output("Tryxlk il Troll Cieco riesce ad afferrare in qualche modo la gemma e combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Troll Cieco","rounds"=>$mercturns,"wearoff"=>"Il Troll Cieco si è perso.","defmod"=>1.2,"atkmod"=>1.0,"roundmsg"=>"Tryxlk sembra percepire la posizione di {badguy}, e lo attacca con la sua spada.","activate"=>"defense");
                break;
          case 3:
              output("Grog il Nano Ubriaco inciampa sulla gemma e la afferra. Combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Nano Ubriaco","rounds"=>($mercturns-2),"wearoff"=>"Grog scompare alla tua vista dietro un cespuglio.","defmod"=>.8,"atkmod"=>.8,"roundmsg"=>"Il Nano Ubriaco è più un problema che un aiuto, è inciampato contro di te!","activate"=>"defense");
                break;
          case 4:
              output("Longstepper il Ranger afferra la gemma al volo e combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Ranger","rounds"=>$mercturns,"wearoff"=>"Il Ranger scompare nella foresta.","defmod"=>1.3,"atkmod"=>1.4,"roundmsg"=>"Il Ranger attacca {badguy} con determinazione.","activate"=>"defense");
                break;
          case 5:
              output("Tasha l'Arciere Elfico raggiunge per primo la gemma e combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Arciere Elfico","rounds"=>$mercturns,"wearoff"=>"Tasha ha terminato le freccie e torna al suo clan.","defmod"=>1.0,"atkmod"=>1.4,"roundmsg"=>"La mira di Tasha è notoria, una freccia colpisce in pieno {badguy}.","activate"=>"defense");
                break;
          case 6:
              output("Dagnar il Cavaliere Veterano afferra la gemma al volo e combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Cavaliere","rounds"=>($mercturns+3),"wearoff"=>"Dagnar non riesce a reggere il tuo ritmo e si ritira.","defmod"=>1.5,"atkmod"=>1.5,"roundmsg"=>"L'abilità del Cavaliere con le armi non da tregua a {badguy}.","activate"=>"defense");
                break;
          case 7:
              output("Bjorn il Lanciere strappa la gemma agli altri contendenti e combatterà al tuo fianco per un po' !");
              $session['bufflist'][107] = array("name"=>"`#Lanciere","rounds"=>$mercturns,"wearoff"=>"Il Lanciere si ritrae quando la sua arma si frantuma.","defmod"=>1.3,"atkmod"=>1.1,"roundmsg"=>"{badguy} ha grosse difficoltà ad evitare i fendenti di Bjorn.","activate"=>"defense");
                break;
        }
    }else{
      $hploss=round($session['user']['hitpoints']*.09);
      $session['user']['hitpoints']-=$hploss;
      output("`n`n`2YPrometti di dare una gemma ai mercenari, ma quando apri il tuo borsellino, scopri di non averne ");
      output("neanche una.  I mercenari estraggono le loro armi e ti attaccano. `n`^Perdi $hploss HP prima di metterti in salvo.");
    }
}else{
      output("`n`n`2Non ritenendo i loro servizi degni di una gemma, auguri ai mercenari una buona giornata e continui per la tua strada. ");
}
?>