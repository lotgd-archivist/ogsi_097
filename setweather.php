<?php
/*setweather.php
An element of the global weather mod Version 0.5
Written by Talisman
Latest version available at http://dragonprime.cawsquad.net
*/
require_once "common.php";
	calcolastagione($stagione);
	switch($stagione){
            case 1:
            
				switch(e_rand(1,8)){
			            case 1:
			 				$clouds="nuvoloso e freddo, con locali schiarite";
			            break;
			            case 2:
			  				$clouds="temperato con un tiepido sole";
			            break;
			            case 3:
			  				$clouds="freddo e piovoso";
			            break;
			            case 4:
			  				$clouds="nuvoloso e freddo con nevicate sparse";
			            break;	
			            case 5:
			  				$clouds="nebbioso";
			            break;
			            case 6:
			 				$clouds="sereno ma freddo";
			            break;
			            case 7:
			  				$clouds="ventoso con rovesci locali";
			            break;
			            case 8:
			  				$clouds="nuvoloso con forti nevicate";
			            break;    
				}
			break;
			case 2:			

				switch(e_rand(1,8)){
			            case 1:
			 				$clouds="nuvoloso e freddo, con frequenti schiarite";
			            break;
			            case 2:
			  				$clouds="temperato con sole";
			            break;
			            case 3:
			  				$clouds="umido e piovoso";
			            break;
			            case 4:
			  				$clouds="sereno con foschie locali";
			           	break;
			            case 5:
			 				$clouds="sereno con gelate notturne";
			            break;
			            case 6:
			  				$clouds="caldo e soleggiato";
			            break;
			            case 7:
			  				$clouds="ventoso con forti acquazzoni";
			           	break;
			            case 8:
			  				$clouds="nuvoloso con rovesci temporaleschi";
			           	break;         
			              
				}
			break;
			case 3:			

				switch(e_rand(1,8)){
			            case 1:
			 				$clouds="nuvoloso, con precipitazioni sparse";
			            break;
			            case 2:
			  				$clouds="soleggiato e temperato";
			            break;
			            case 3:
			  				$clouds="piovoso";
			            break;
			            case 4:
			  				$clouds="sereno e poco nuvoloso";
			           	break;
			            case 5:
			 				$clouds="sereno con nuvolosità locale";
			            break;
			            case 6:
			  				$clouds="caldo e afoso";
			            break;
			            case 7:
			  				$clouds="sereno con nuvolosità cumuliforme";
			           	break;
			            case 8:
			  				$clouds="nuvoloso con rovesci locali";
			           	break;         
			              
				}
			break;
			case 4:			

				switch(e_rand(1,8)){
			            case 1:
			 				$clouds="nuvoloso, con forti precipitazioni ";
			            break;
			            case 2:
			  				$clouds="soleggiato";
			            break;
			            case 3:
			  				$clouds="molto piovoso";
			            break;
			            case 4:
			  				$clouds="sereno con nuvolosità variabile";
			           	break;
			            case 5:
			 				$clouds="sereno con locali gelate notturne";
			            break;
			            case 6:
			  				$clouds="nuvoloso e freddo con schiarite";
			            break;
			            case 7:
			  				$clouds="ventoso con rovesci temporaleschi";
			           	break;
			            case 8:
			  				$clouds="nuvoloso con piogge sparse";
			           	break;         
			              
				}
			break;
			
	}
savesetting("weather",$clouds);
?>
