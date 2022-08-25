<?php
/********************
Crystal Ball
Written by Robert for Maddnets LoGD
The old Gypsy woman tells fortunes
for a gem.
*********************/
if (!isset($session)) exit();
if ($_GET['op']==""){
      page_header("La Sfera di Cristallo");
      output("`n`RTi imbatti in una carovana di Zingari con una vecchia e grassa `^Gitana`R che sporgendosi da un carrozzone ti dice:`n");
      output("\"`%`iEntra nella mia casa viaggiante, una magica `&Sfera di Cristallo `%io ho, e il tuo futuro ti predico per una sola `&gemma.`R`i\"`n`n`REsiti dibattut".($session[user][sex]?"a":"o")." tra la curiosità e lo scetticismo e guardi la vecchia zingara con aria ebete. Poi finalmente prendi una decisione. Cosa fai?");
      addnav("`@Dalle una gemma","forest.php?op=give");
      addnav("`\$Torna alla Foresta","forest.php?op=dont");
      $session['user']['specialinc']="crystalball.php";
}else if ($_GET['op']=="give"){
  if ($session['user']['gems']>0){
      output(" `R`nDopo qualche attimo di indecisione, durante il quale scruti il viso gonfio e segnato dal tempo della `^Gitana`R, decidi di tentare la sorte. Allunghi quindi la mano consegnandole una delle tue tanto sudate e per te preziosissime `&gemme`R.`n ");
      output(" 	Afferrandola con un gesto repentino, la donna ti fa segno di seguirla all'interno della tenda dove un basso tavolino sgangherato vi attende. `n");
      output(" 	Ti ritrovi quindi a seguire nel carrozzone l'opulenta `^Gitana`R, la cui enorme massa di grasso ondeggia sotto il vestito, ballonzolando quà e là senza che il busto riesca a contenerla : uno spettacolo non proprio affascinante.... ");
      output(" 	La stoffa vivacemente `5col`(or`^ata `Rche fa da porta alla tenda, si richiude alle tue spalle, lasciandoti improvvisamente immerso nel `9buio `Ra rimirare la misteriosa e affascinante `&Sfera di Cristallo `Rche brilla tra te e l'anziana donna.`n ");
      output(" 	Con occhi accesi dal riverbero della `&sfera `Re dalla concentrazione, la `^Gitana`R passa le mani, dale dita grassoccie tutte inanellate, sopra di `&essa `R, quasi ad accarezzarla. `nVi immerge lo `3sguardo`R, mentre la luce dentro al `&cristallo `Rsi offusca in un turbinio `&lattiginoso`R...`n ");
      output(" ... la sua voce pare aleggiare intorno a te mentre dice: `%`n`n`iIo vedo....`i`n`n");
        $session['user']['gems']--;
        debuglog("da 1 gemma alla vecchia Gitana nella foresta");
        switch(e_rand(1,7)){
            case 1:
               output("`i la sabbia fermarsi, i rintocchi cessare `n il cuore nel petto così rallentare `n il tempo bloccarsi coi piedi puntati `n e i turni residui di colpo aumentati!`i`R`n");
                $session['user']['turns']++;
                debuglog("guadagna un combattimento con sfera di cristallo");
                break;
            case 2:
                output("`i u".($session[user][sex]?"na":"")." guerrier".($session[user][sex]?"a":"o")." sperdut".($session[user][sex]?"a":"o")." nel folto del bosco `n col".($session[user][sex]?"ei":"ui")." che da qualche secondo conosco `n col".($session[user][sex]?"ei":"ui")." che davanti sedut".($session[user][sex]?"a":"o")." mi sta `n un turno di meno subito avrà!`i`R`n");
                $session['user']['turns']--;
                debuglog("perde un combattimento con sfera di cristallo");
                break;
            case 3:
                output("`i ogni tuo passo volgere altrove `n verso l'ignoto e non si sa dove `n via dal mio carro e dalle mie cose `n nella tua tasca due gemme preziose`i`R`n");
                $session['user']['gems']+=2;
                debuglog("trova 2 gemme dalla Gitana");
                break;
            case 4:
                output("`iil viso di colpo che volge al verdino `n la pelle che cede e diventa un budino `n due macchie marroni spuntare sul volto `n e la tua linfa vitale crollare di colpo!`i`R`n");
                $session['user']['clean'] += 1;
                $session['user']['hitpoints']=8;
                break;
            case 5:
                output("`i u".($session[user][sex]?"n'audace guerriera":"n baldo guerriero")." mi ritrovo davanti `n di bella presenza e occhi fiammanti. `n Attacco feroce, e difesa potente `n peccato soltanto non sia permanente`i`R`n");
                $session['user']['hitpoints']+=round($session['user']['maxhitpoints']*.4,0);
                break;
            case 6:
                output("`i come il sole nascente `n sarai bell".($session[user][sex]?"a":"o")." e attraente `n come luna calante `n diverrai affascinante`i`R`n");
                $session['user']['charm']+7;
                debuglog("guadagna 7 punti fascino con sfera di cristallo");
                break;
            case 7:
            	output("`i sia che lotti di spada o di lingua tagliente `n con sguardo assassino, con muscoli o mente `n parlando con morti, animali o legioni `n vivendo di furto o di pure intuizioni `n più bravo sarai, te lo posso giurare `n in essa di molto potrai migliorare.`i`R`n");	
                increment_specialty();
                debuglog("guadagna un livello abilità con sfera di cristallo");
                break;
        }
    }else{
      output("`n`RPrometti di dare la `&gemma`R che ti è stata richiesta alla vecchia `^Gitana`R, ma quando apri il tuo borsellino, scopri che ");
      output("non ne hai.`n E mentre imbarazzato, cerchi di biascicare qualche futile scusa, la grossa donna, sentendosi imbrogliata, ti lancia uno sguardo maligno con `4occhi FURIOSI`R e ti lancia la maledizione dell'`3`iOCCHIO DIABOLICO`i`R!! ");
      output("`nPoi sghignazzando ti dice che ora sei più brutt".($session[user][sex]?"a":"o")." del suo asino!");
      output("`n`nHai perso TUTTI i tuoi punti Fascino tranne `^1`R per aver cercato di imbrogliare la vecchia `^Gitana`R ");
      addnews("`2Sempre impeccabile e brillante, `&" . ($session['user']['name']) . "`2 è ora più brutt".($session[user][sex]?"a":"o")." dell'asino della vecchia `^Gitana`2.`n");
      debuglog("perde tutti punti fascino con sfera di cristallo");
      $session['user']['clean'] += 1;
      $session['user']['charm']=1;
    }
}else{
      output("`n`RNon volendo spartire le tue preziose `&gemme`R con nessuno altro, ");
      output(" auguri alla vecchia `^Gitana`R buona giornata e ti allontani dirigendoti verso il folto della foresta, facendo spallucce a quanto pronunciato in una strana lingua dalla grassa donna : sicuramente sarà una maledizione, ma tu non sei superstizios".($session[user][sex]?"a":"o").". ");
}


?>