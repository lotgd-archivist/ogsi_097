<?php
if ($session['user']['sex']>0){
if (!isset($session)) exit();
if ($_GET['op']==""){
    output("`%Mentre ti inoltri nella foresta, un'affascinante figura ti si avvicina.`n`n `^Sono il dio Fexez. Le gesta delle tue performance d'amore sono giunte fino a me. Seguimi...`%`n`n Cosa fai?");
    addnav("`2Lo Segui","forest.php?op=do");
    addnav("`4Scappi Via","forest.php?op=dont");
    $session['user']['specialinc']="aphrodite.php";
}else if ($_GET['op']=="do"){
      output("`%Prendi la mano del dio e lo segui in una radura vicina. Dopo qualche minuto, il dio ");
        output("geme di piacere. Incapace di stare al passo con un dio, perdi conoscenza. ");
        output("Quando ti svegli, scopri che...`n`n`^");
        $session['user']['clean'] += 1;
        switch(e_rand(1,10)){
            case 1: case 2:
              output("ti ha lasciata senza neanche salutarti. Ti senti esausta, e perdi un combattimento nella foresta.");
                $session['user']['turns']-=1;
                $session['user']['experience']+=200;
                debuglog("perde un turno e guadagna 200 exp con un dio");
                addnews($session['user']['name']." si è intrattenuta con una divinità.");
                break;
            case 3: case 4:
              output("ti ha lasciata senza neanche salutarti. Ti senti in forma, puoi combattere un'altra creatura.");
                $session['user']['turns']+=1;
                debuglog("guadagna un turno 200 exp con un dio");
                $session['user']['experience']+=200;
                addnews($session['user']['name']." si è intrattenuta con una divinità.");
                break;
            case 5: case 6:
                output("ti ha lasciato una borsa con delle gemme. Ti senti usata, ma accetti il dono!");
                $session['user']['gems']+=3;
                $session['user']['experience']+=200;
                debuglog("guadagna 3 gemme 200 exp con un dio");
                addnews($session['user']['name']." si è intrattenuta con una divinità.");
                break;
            case 7:
                output("ti ha lasciato una mela dorata. Affamata, divori il cibo degli dei, ed i tuoi HitPoints sono aumentati `i`bpermanentemente`b`i!");
                $session['user']['maxhitpoints']+=5;
                debuglog("guadagna 5 HP permanenti e 200 exp con un dio");
                $session['user']['experience']+=200;
                addnews($session['user']['name']." si è intrattenuta con una divinità.");
                break;
            case 8:
            case 9:
            case 10:
                increment_specialty();
                debuglog("guadagna un'abilità con un dio");
                addnews($session['user']['name']." si è intrattenuta con una divinità.");
                break;
            case 11:
            if (!zainoPieno($session['user']['acctid'])){
	            $sqldr="INSERT INTO zaino (idoggetto,idplayer) VALUES ('1','{$session['user']['acctid']}')";
	            db_query($sqldr);
	            output("`^Ti da in dono una preziosa scaglia di ferro!`n`n");
	            debuglog("riceve 1 scaglia di ferro da una dea");
	            output("`^Perdi un turno mentre scavavi.`n`n");
	            addnews($session['user']['name']." si è intrattenuta con una divinità.");
        	}else{
	        	output("`^Ti offre una prezione scaglia di ferro, ma purtroppo non hai spazio nel tuo zaino!!!");
        	}
	        if ($session['user']['turns']>0) $session['user']['turns']--;
            break;
}
}else{
  output("Temendo la gelosia del tuo amato Seth, scappi via. La divinità sospira e scompare avvolta dalla nebbia.");
}

}else{

if (!isset($session)) exit();
if ($_GET['op']==""){
        output("`%Mentre ti inoltri nella foresta, un'affascinante figura ti si avvicina.`n`n  `^Sono la dea Aphrodite. Le notizie della tua abilità di guerriero sono giunte fino alla lontana Atene.`n Vorrei scoprire fino a che punto arrivano le tue abilità. Vieni con me...`%`n`n Cosa fai?");
        addnav("`@La Segui","forest.php?op=do");
        addnav("`\$Scappi Via","forest.php?op=dont");
        $session['user']['specialinc']="aphrodite.php";
}else if ($_GET['op']=="do"){
        output("`%La dea prende la tua mano e tu la segui in una radura vicina. Dopo diversi minuti, lei ");
        output("si contorce dal piacere. Incapace di sostenere il suo ritmo, perdi conoscenza. ");
        output("Quando ti svegli, scopri che ...`n`n`^");
        switch(e_rand(1,10)){
            case 1:
            case 2:
              output("ti ha lasciato senza neanche dirti addio. Ti senti esausto e perdi un combattimento nella foresta.");
                $session['user']['turns']-=1;
                $session['user']['experience']+=200;
                debuglog("perde un turno e guadagna 200 exp con un dea");
                addnews($session['user']['name']." si è intrattenuto con una dea.");
                break;
            case 3:
            case 4:
              output("ti ha lasciato senza neanche dirti addio. Ti senti in splendida forma, e puoi combattere un'altra creatura.");
                $session['user']['turns']+=1;
                $session['user']['experience']+=200;
                debuglog("guadagna 1 turno e 200 exp con un dea");
                addnews($session['user']['name']." si è intrattenuto con una dea.");
                break;
            case 5:
            case 6:
                output("ti ha lasciato una borsa con delle gemme. Ti senti usato, ma accetti il dono!");
                $session['user']['gems']+=3;
                $session['user']['experience']+=200;
                debuglog("guadagna 3 gemme e 200 exp con un dea");
                addnews($session['user']['name']." si è intrattenuto con una dea.");
                break;
            case 7:
                output("ti ha lasciato una mela dorata. Affamato, divori il cibo degli dei, ed i tuoi HitPoints sono aumentati `i`bpermanentemente`b`i!");
                $session['user']['maxhitpoints']+=5;
                $session['user']['experience']+=200;
                debuglog("guadagna 5 HP Permanenti e 200 exp con un dea");
                addnews($session['user']['name']." si è intrattenuto con una dea.");
                break;
            case 8:
            case 9:
            case 10:
                increment_specialty();
                debuglog("guadagna 1 abilità con un dea");
                addnews($session['user']['name']." si è intrattenuto con una dea.");
                break;
        }
}else{
  output("Temendo la gelosia della tua amata Violet, scappi via. La dea sospira e scompare avvolta dalla nebbia.");
}
}
?>