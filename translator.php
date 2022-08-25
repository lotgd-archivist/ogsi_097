<?php
/* Format for translator.php
Each translatable page has its own entry below, locate the page where the text you want
to translate is, and populate the $replace array with "From"=>"To" translation combinations.
Only one translation per output() or addnav() call will occur, so if you have multiple
translations that have to occur on the same call, place them in to their own array
as an element in the $replace array.  This entire sub array will be replaced, and if any
matches are found, further replacements will not be made.

If you are replacing a single output() or addnav() call that uses variables in the middle,
you will have to follow the above stated process for each piece of text between the variables.
Example,
output("MightyE rules`nOh yes he does`n");
output("MightyE is Awesome $i times a day, and Superawesome $j times a day.");
you will need a replace array like this:
$replace = array(
"MightyE rules`nOh yes he does`n"=>"MightyE rulezors`nOh my yes`n"
,array(
"MightyE is Awesome"=>"MightyE is Awesomezor"
,"times a day, and Superawesome"=>"timez a dayzor, and Superawesomezor"
,"times a day."=>"timez a dayzor."
)
);

*/
$language = "";
if(isset($session['user']['prefs']['language'])) {

$language = $session['user']['prefs']['language'];
}
if ($language=="") $language=$_COOKIE['language_logd_ogsi'];
/**
 * Aggiungere all'inizio dell'index per attivare registrazione cookie language
 */
/*
$lingua_browser=substr($_SERVER[HTTP_ACCEPT_LANGUAGE],0,2);
if(strlen($_COOKIE['language_logd_ogsi'])!=2){
	// setto cookie lingua
	setcookie("language_logd_ogsi",$lingua_browser,strtotime(date("r")."+355 days"));
	$_COOKIE['language_logd_ogsi']=$lingua_browser;
}
*/

if ($language=="") $language=getsetting("defaultlanguage","it");// per altre lingue settare default de per tedesco
$raccogli_testo=getsetting("raccogli_testo","off");
$translate_page = $_SERVER['PHP_SELF'];
$translate_page = substr($translate_page,strrpos($translate_page,"/")+1);
//$no_translate contiene le pagine che non vogliamo tradurre
$no_translate = unserialize(getsetting("no_translate",""));

//echo " \$language :".$language." session : ".$session['user']['prefs']['language']." cookie : ".$_COOKIE['language_logd_ogsi']." default :".getsetting("defaultlanguage","it");


// In common.php aggiungere dove viene chiamata la funzione translate questo controllo  if($priv==false)  va eseguito solo in questo caso.

function replacer($input,$replace){
	$originput = $input;
	if (!is_array($replace)) return $input;
	while (list($s,$r)=each($replace)){
		if (is_array($r)){
			$input = str_replace(array_keys($r),array_values($r),$input);
		}else{
			$input = str_replace($s,$r,$input);
		}
		if ($originput!=$input) return $input;
	}
	return $input;
}

if($language=="it"){
	function translate($input){
		return $input;
	}
}else{
	function translate($input){
		global $translate_page,$language,$raccogli_testo,$replace_,$no_translate;
		if($raccogli_testo=='on' AND in_array($translate_page,$no_translate)==false){
			$sql = "SELECT $language FROM text WHERE it='".addslashes($input)."' AND page='$translate_page'";
			$result = db_query($sql);
			$num_row=db_num_rows($result);
			if($num_row == 0){
				if(strpos($input,'<')===false AND strpos($input,'>')===false AND strlen($input)>0){
					$sql = "INSERT INTO text (it,page) VALUES ('".addslashes($input)."','$translate_page')";
					db_query($sql) or die(db_error($link));
				}
			}else{
				$row = db_fetch_assoc($result);
				if(strlen($row[$language])>0)$replace[$input]=stripslashes($row[$language]);
			}
		}else{
			if(strlen($replace_[$input])>0)$replace[$input] = $replace_[$input];
		}
		return replacer($input,$replace);
	}
	if($raccogli_testo=='off'){
		$sql = "SELECT it,$language FROM text WHERE page='$translate_page'";
		$result = db_query($sql);
		$num_row = db_num_rows($result);
		for ($ixz = 0;$ixz < $num_row;$ixz++) {
			$row = db_fetch_assoc($result);
			if(strlen($row[$language])>0)$replace_[$row['it']] = $row[$language];
		}
		unset($result);
		//print_r($replace_);//TEST stampa l'array estratto dal db per la pagina aperta
	}

}
//echo "Lingua:$language Raccolta:$raccogli_testo Pagina:$translate_page";

?>
