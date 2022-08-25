<?php
require_once "common.php";
require_once "common2.php";
if ($session['user']['loggedin'] && $session['loggedin']){
     debuglog("Rilevato Badnav in ". $session['user']['restorepage']);
     report(3,"`%BADNAV","`4".$session['user']['name']." `4 ha avuto un badnav in ".$session['user']['restorepage'],"badnav");
	if (strpos($session[output],"<!--CheckNewDay()-->")){
		checkday();
	}
	while (list($key,$val)=each($session['allowednavs'])){
		if (
		    trim($key)=="" ||
		    $key===0 ||
		    substr($key,0,8)=="motd.php" ||
		    substr($key,0,8)=="mail.php"
		) unset($session['allowednavs'][$key]);
	}
	if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0 || $session['output']=="") {
		$session['allowednavs']=array();
		addnav("","village.php");
		echo "<a href='village.php'>La tua navigazione si è corrotta, torna al villaggio.</a>";
	}
	echo $session['output'];
	$session['debug']="";
	$session['user']['allowednavs']=$session['allowednavs'];
	saveuser();
}else{
	$session=array();
	redirect("index.php");
}

?>
