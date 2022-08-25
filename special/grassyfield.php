<?php
page_header("La distesa erbosa");
output("`n`n`&Cammina e cammina raggiungi il limite estremo della foresta. Gli alberi si diradano e di colpo ");
output("ti ritrovi di fronte ad una sterminata pianura erbosa e uno splendido panorama che ti lascia senza ");
output("fiato dallo stupore.`nUna landa verde sconfinata si estende davanti a te per chilometri e chilometri, ");
output("un vastissimo oceano verde senza fine che, fondendosi solo all'orizzonte con un terso cielo azzurro ");
output("punteggiato qua e là da bianche nubi cumuliformi, ti dà la sensazione di essere un minuscolo moscerino ");
output("di fronte all'immensità dello spazio.");
addnav("`@Torna alla foresta","forest.php");
if ($session['user']['hashorse']>0){
    output("`n`n`&Lasci scorrazzare nell'erba il tuo fedele `6".$playermount['mountname']."`& mentre ti godi questa eccelsa ");
    output("visione per minuti che si trasformano in ore e, dopo esserti rotolato nell'erba profumata, ti assopisci ");
    output("al sole.`nQuando ti svegli hai consumato un combattimento nella foresta, ma sei completamente ristorato ");
    output("e pronto ad affrontare nuove avventure mentre il tuo `6".$playermount['mountname']."`& si è nutrito ed ");
    output("è nuovamente in splendida forma.");
    output("`n`n`^Perdi un combattimento nella foresta per oggi.`n");
    $buff = unserialize($playermount['mountbuff']);
    $session['bufflist']['mount']=$buff;
    $session['user']['turns'] -= 1;
    if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
    }
} else {
    output("`n`n`&Ti godi questa eccelsa visione per minuti che si trasformano in ore e, dopo esserti rotolato ");
    output("nell'erba profumata, ti assopisci al sole.`nQuando ti svegli hai consumato un combattimento nella ");
    output("foresta, ma sei completamente ristorato e pronto ad affrontare nuove avventure.`n`n");
    if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
        $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
}
page_footer();
?>