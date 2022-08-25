<?php
require_once("common.php");
require_once("common2.php");
checkday();
page_header("Gestione oggetti");

$skills = array(1 => "`\$Arti Oscure", "`%Poteri Mistici", "`^Furto", "`3Militare","`\$Seduzione","`^Tattica","`@Pelle di Roccia","`#Retorica","`%Muscoli","`3Natura","`&Clima","`^Elementalista","`6Rabbia Barbara","`5Canzoni del Bardo");

if ($_GET['az'] == "") {
    $oggetto = $session['user']['oggetto'];
    $oggettozaino = $session['user']['zaino'];
    $valorizza = true ;
    $testo = "Sei equipaggiato con :";
    $testozaino = "Nello zaino hai :";
    output ("`2Possiedi questi oggetti.`n`n`n");
    visualizzaoggetto($oggetto,$valorizza,$testo);
    output ("`n`n`n");
    visualizzaoggetto($oggettozaino,$valorizza,$testozaino);
    
} elseif ($_GET['az'] == "usa") {
    if (!$_GET['oid']) {
        output ("Non hai nulla da usare.`n`n");
    } else{
        $sqlo = "SELECT * FROM oggetti WHERE id_oggetti = ".$_GET['oid']."";
        $resulto = db_query($sqlo) or die(db_error(LINK));
        $row = db_fetch_assoc($resulto);
        output ("`2Mettendo in campo tutta la tua esperienza di guerriero temprato da molte battaglie, ti concentri fortemente sull'oggetto `nche stai impugnando nel tentativo di evocarne i poteri magici.`n");
        output ("`2Ogni stilla di energia è dedicata a questo sforzo immane, il tuo corpo irrigidito incomincia a tremare e le giunture a scricchiolare `ncome se fossero sul punto di frantumarsi in mille pezzi.`n");
        if ($row['potere']== 0 ) {
            output ("`2Non succede assolutamente nulla ed esausto cadi in ginocchio cercando di recuperare le forze. `n`# Il tuo oggetto non è dotato di alcun potere da poter evocare!`n`n");
        }else{
            if ($row['potere_uso']== 0) {
	            output ("`2Non succede nulla e stremato dalla fatica ti accasci al suolo per cercare di recuperare recuperare le forze. `n`# Hai già evocato il potere del tuo oggetto oggi e non è possibile evocarlo ancora!`n`n");
            }else{
	            
	            output ("`3Un lampo luminoso scaturisce da `@".$row['nome']." `3danzando sulla sua levigata superficie e, dopo averti avvolto completamente`n");
	            output ("con una fredda luce azzurrognola, penetra nel tuo corpo in un pulsare di magica energia.`n");
	            output ("Immediatamente una sensazione di benefico calore si diffonde dal tuo petto agli arti poi, l'onda di energia ritorna verso il tuo `n");
	            output ("magico oggetto portando con sè una parte di te e fondendosi in esso, facendoti in tal modo acquisire il suo immenso potere.`n`n");
                if ($row['heal_help']>0) {
                    output ("`#Ti sei curato per ".$row['heal_help']." punti vita.`n`n");
                    $session['user']['hitpoints']+=$row['heal_help'];
                    $resto = $row['potere_uso']-1;
                    $sqlu = "UPDATE oggetti SET potere_uso=$resto WHERE id_oggetti=".$_GET['oid']."";
                    db_query($sqlu) or die(db_error(LINK));
                }
                if ($row['quest_help']>0) {
                    output ("`#Hai guadagnato ".$row['quest_help']." punti quest.`n`n");
                    $session['user']['quest']-=$row['quest_help'];
                    $resto = $row['potere_uso']-1;
                    $sqlu = "UPDATE oggetti SET potere_uso=$resto WHERE id_oggetti=".$_GET['oid']."";
                    db_query($sqlu) or die(db_error(LINK));
                }
                if ($row['exp_help']>0) {
                    $esperienza=intval($session['user']['experience']*$row['exp_help']/100);
                    $session['user']['experience'] += $esperienza;
                    output ("`#Hai guadagnato {$esperienza} punti esperienza.`n`n");
                    $resto = $row['potere_uso']-1;
                    $sqlu = "UPDATE oggetti SET potere_uso=$resto WHERE id_oggetti=".$_GET['oid']."";
                    db_query($sqlu) or die(db_error(LINK));
                }
                if ($row['special_help']>0) {
                    if($row['special_help'] == 1 ) {
                        $session['user']['darkartuses']+=$row['special_use_help'];
                    }else if($row['special_help'] == 2 ) {
                        $session['user']['magicuses']+=$row['special_use_help'];
                    }else if($row['special_help'] == 3 ) {
                        $session['user']['thieveryuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 4 ) {
                        $session['user']['militareuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 5 ) {
                        $session['user']['mysticuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 6 ) {
                        $session['user']['tacticuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 7 ) {
                        $session['user']['rockskinuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 8 ) {
                        $session['user']['rhetoricuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 9 ) {
                        $session['user']['muscleuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 10 ) {
                        $session['user']['natureuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 11 ) {
                        $session['user']['weatheruses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 12 ) {
                        $session['user']['elementaleuses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 13 ) {
                        $session['user']['barbarouses']+=$row['special_use_help'];
                    }else if ($row['special_help'] == 14 ) {
                        $session['user']['bardouses']+=$row['special_use_help'];
                    }
                    $indiceabilita =$row['special_help'];
                    $special = $skills[$indiceabilita];
                    output ("`#Ora hai guadagnato `^".$row['special_use_help']."`# punti nell'abilità $special  `n`n");
                    $resto = $row['potere_uso']-1;
                    $sqlu = "UPDATE oggetti SET potere_uso=$resto WHERE id_oggetti=".$_GET['oid']."";
                    db_query($sqlu) or die(db_error(LINK));
                
                }
            }
        }
    }

}  elseif ($_GET['az'] == "scambia") {
	
	$oggetto = $session['user']['oggetto'];
    $oggettozaino = $session['user']['zaino'];
	scambiaoggetto($oggetto,$oggettozaino);
	$oggetto = $session['user']['oggetto'];
    $oggettozaino = $session['user']['zaino'];
    $valorizza = true ;
    $testo = "Sei equipaggiato con :";
    $testozaino = "Nello zaino hai :";
    output ("`2Possiedi questi oggetti.`n`n`n");
    visualizzaoggetto($oggetto,$valorizza,$testo);
    output ("`n`n`n");
    visualizzaoggetto($oggettozaino,$valorizza,$testozaino);
    
}

addnav("Azioni");
addnav("V?Valuta i tuoi Oggetti", "gestione_mag.php?az=");
addnav("U?Usa `#Potere `3Oggetto", "gestione_mag.php?az=usa&oid=".$session['user']['oggetto']."");
addnav("S?Scambia gli Oggetti", "gestione_mag.php?az=scambia");
addnav("Exit");
addnav("T?Torna in piazza", "village.php");


page_footer();

?>