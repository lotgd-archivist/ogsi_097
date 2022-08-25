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
$translate_page = $_SERVER['PHP_SELF'];
$translate_page = substr($translate_page,strrpos($translate_page,"/")+1);
function translate($input){
	global $translate_page;
	//echo $_SERVER['SCRIPT_FILENAME'];
	//echo $translate_page;
	switch ($translate_page){
	case "index.php":
		$replace = array(
			"The current time in the village is"=>"L'ora nel villaggio è:"
			,"About LoGD"=>"Informazioni su LoGD"
			,"List Warriors"=>"Elenco dei guerrieri"
			,"LoGD Net"=>"LoGD Net"
			,"`cWelcome to Legend of the Green Dragon, a shameless knockoff of Seth Able's Legend of the Red Dragon.`n"=>"`cBenvenuti su Legend of the Green Dragon, basato sul gioco di Seth Able: The legend of the Red Dragon`n"
			,"`@Next new game day in: `$"=>"`@Il prossimo giorno di gioco avrà inizio tra: `$"
			," Your session has timed out, you must log in again.`n"=>" La tua sessione è scaduta, devi rifare il login.`n"
			,"`c`2Game server running version: "=>"`c`2Questo server usa la versione: "
			,"Daily News"=>"Notizie Giornaliere"
			,"Game Setup Info"=>"Info sul Setup del Gioco"
			,"Enter your name and password to enter the realm.`n"=>"Inserisci il tuo nome utente e la tua password per entrare nel reame.`n"
			,"Log in"=>"Entra"
			,"Other"=>"Altro"
			,"FAQ (for new players)"=>"F.A.Q. (per i nuovi giocatori)"
			,"Create a character"=>"Crea un personaggio"
			,"Forgotten Password"=>"Hai dimenticato la password?"
			,"New to LoGD?"=>"Nuovo di qui?"
			,array(
				"<u>U</u>sername:"=>"Nome:"
				,"<u>P</u>assword:"=>"<u>P</u>assword:"
				)
		);
		break;
	case "create.php":
		$replace = array(
			"How will you be known to this world?"=>"Con che nome sarai noto in questo mondo?"
			,"Enter a password:"=>"Digita una password:"
			,"Re-enter it for confirmation:"=>"Riscrivi la password:"
			,"Enter your email address:"=>"Digita il tuo indirizzo e-mail:"
			,array(
				"And are you a"=>"E sei"
				,"Female or a"=>"Femmina o"
				,"Male?"=>"Maschio?"
				)
			,"Create your character"=>"Crea il tuo personaggio"
			,array(
				"Characters that have never been logged in to will be deleted after"=>"Un personaggio che non si è mai connesso verrà cancellato dopo"
				,"day(s) of no activity."=>"giorni di inattivià;."
				,"Characters that have never reached level 2 will be deleted after"=>"Un personaggio che non ha mai raggiunto il livello 2 verrà cancellato dopo"
				,"days of no activity."=>"giorni di inattività;."
				,"Characters that have reached level 2 at least once will be deleted after"=>"Un personaggio che ha raggiunto almeno una volta il livello 2 verrà cancellato dopo"
				)
			,"Your account was created, your login name is"=>"Il tuo personaggio è stato creato, il tuo nome utente è"
			,"Click here to log in"=>"Clicca qui per entrare"
			,"Login"=>"Login"
		);
		break;

	case "village.php":
		$replace = array(
			"`bCombat`b"=>"`bAvventure`b"
			,"Forest"=>"Foresta"
			,"I?The Inn`0"=>"La locanda"
			,"Bluspring's Warrior Training"=>"Campo d'Allenamento"
			,"Slay Other Players"=>"Uccidi un giocatore"
			,"Commerce"=>"Commercio"
			,"Market Street"=>"Piazza del Mercato"
			,"W?MightyE's Weaponry"=>"Armeria di MightyE"
			,"Pegasus Armor"=>"Armature da Pegasus"
			,"B?Ye Olde Bank"=>"B?La Vecchia Banca"
			,"Merick's Stables"=>"Stalla di Merick"
			,"Blades Boulevard"=>"Viale delle Lame"
			,"Tavern Street"=>"Strada delle Taverne"
			,"G?The Garden"=>"G?I Giardini"
			,"Curious Looking Rock"=>"Roccia Curiosa"
			,"T?Gypsy Tent"=>"Campo degli Zingari"
			,"`bOther`b"=>"`bAltro`b"
			,"F.A.Q. (newbies start here)"=>"F.A.Q. (per i principianti)"
			,"Daily News"=>"Notizie giornaliere"
			,"Preferences"=>"Preferenze"
			,"List Warriors"=>"Elenco guerrieri"
			,"Hall o' Fame"=>"Sala degli eroi"
			,"<font color='#FF00FF'>Quit</font> to the fields"=>"<font color='#FF00FF'>Esci</font> nei campi"
			,"`bSuperuser Grotto`b"=>"Grotta del Superutente"
			,"New Day"=>"Nuovo Giorno"
			,"`@`c`bVillage Square`b`cThe village hustles and bustles.  No one really notices that you're standing there."=>"`@`c`bPiazza del villaggio`b`cIl villaggio è in trambusto. Nessuno si accorge veramente della tua presenza."
			,"You see various shops and businesses along main street.  There is a curious looking rock to one side."=>"Vedi vari negozi ed attività lungo la strada principale. C'è una strana roccia da un lato."
			,"On every side the village is surrounded by deep dark forest.`n`n"=>"Da ogni lato il villaggio è circondato da una fitta ed oscura foresta.`n`n"
			,array(
				"The clock on the inn reads"=>"L'orologio della locanda segna le"
				,"Village"=>"Villaggio"
				,"`n`n`%`@Nearby some villagers talk:`n"=>"`n`n`%`@Alcuni abitanti del villaggio parlano nelle vicinanze:`n"
				,"Add"=>"Aggiungi"
//				,"says"=>"dice"
				,"`%Quit`0 to the fields"=>"`%Esci`0 nei campi"
				,"Refresh"=>"Aggiorna"
								)
		);
		break;
	case "about.php":
		$replace = array(
		
		);
		break;
	case "armor.php":
		$replace = array(
		"`c`b`%Pegasus Armor`0`b`c"=>"`c`b`%Armature da Pegasus`0`b`c"
		,"`5The fair and beautiful `#Pegasus`5 greets you with a warm smile as you stroll over to her brightly colored "=>"`5La dolce e bellissima `#Pegasus`5 ti saluta con un caldo sorriso mentre sali sul suo carrozzone da zingara "
		,"gypsy wagon, which is placed, not out of coincidence, right next to `!MightyE`5's weapon shop.  Her outfit is "=>"dai vividi colori, che, non a caso, si trova proprio accanto all'armeria di `!MightyE`5.  I suoi abiti sono "
		,"as brightly colored and outrageous as her wagon, and it is almost (but not quite) enough to make you look away from her huge "=>"di colori brillanti e sfarzosi quasi quanto il suo carrozzone, ed è quasi (ma non del tutto) sufficiente a farti distogliere lo sguardo dai suoi grandi "
		,"gray eyes and flashes of skin between her not-quite-sufficent gypsy clothes."=>"occhi grigi e dai tratti di pelle che si intravvedono tra i suoi non proprio sufficienti abiti zingari."
		,"Browse `#Pegasus`0' wares"=>"Guarda le merci di `#Pegasus`0"
		,"Return to the village"=>"Torna al villaggio"
		,"`5You look over the various pieces of apparal, and wonder if `#Pegasus`5 would be so good as to try some of them "=>"`5Guardi i vari articoli di equipaggiamento, e ti domandi se `#Pegasus`5 sarebbe tanto carina da provarne qualcuno "
		,"on for you, when you realize that she is busy staring dreamily at `!MightyE`5 through the window of his shop "=>"per te, quando ti rendi conto che è impegnata a fissare con sguardo sognante `!MightyE`5 attraverso la vetrina del suo negozio "
		,"as he, barechested, demonstrates the use of one of his fine wares to a customer.  Noticing for a moment that " =>"mentre lui, a torso nudo, da una dimostrazione dell'uso di una delle sue armi ad un cliente. Notando per un attimo che "
		,array(
			"you are browsing her wares, she glances at your" => "stai guardando la sua merce, da un'occhiata al tuo "
			," and says that she'll give you " => " e dice che ti darà "
			, "for them." => "monete in cambio." 
			)
		,"<tr class='trhead'><td>`bName`b</td><td align='center'>`bDefense`b</td><td align='right'>`bCost`b</td></tr>"=>"<tr class='trhead'><td>`bNome`b</td><td align='center'>`bDifesa`b</td><td align='right'>`bCosto`b</td></tr>"
		,"Return to the village"=>"Torna al villaggio"
		,"`#Pegasus`5 looks at you, confused for a second, then realizes that you've apparently taken one too many bonks on the head, and nods and smiles."=>"`#Pegasus`5 ti guarda per un secondo, confusa, poi capisce che evidentemente devi aver presto troppe botte in testa, annuisce e ti sorride."
		,"Try again?"=>"Ritenta"
		,"Return to the village"=>"Torna al villaggio"
		,array(
			"`5Waiting until `#Pegasus`5 looks away, you reach carefully for the"=>"`5Aspettando che `#Pegasus`5 sia distratta, raggiungi attentamente il"
			,"which you silently remove from the stack of clothes on which "=>"che rimuovi attentamente dalla pila di tessuti sui cui "
			)
		,"it sits.  Secure in your theft, you begin to turn around only to realize that your turning action is hindered by a fist closed tightly around your "=>"si trova. Certo del tuo furto, inizi a voltarti solo per scoprire che il tuo movimento è ostacolato da un pugno chiuso strettamente intorno alla tua "
		,"throat.  Glancing down, you trace the fist to the arm on which it is attached, which in turn is attached to a very muscular `!MightyE`5.  You try to "=>"gola. Guardando in basso, segui il pugno fino al braccio a cui è attaccato, che è a sua volta attaccato ad un muscolosissimo `!MightyE`5. Tenti di "
		,"explain what happened here, but your throat doesn't seem to be able to open up to let your voice through, let alone essential oxygen.  "=>"spiegare cosa è successo, ma la tua gola non sembra in grado di aprirsi per far passare la voce, per non parlare dell'essenziale ossigeno.  "
		,"`n`nAs darkness creeps in on the edge of your vision, you glance pleadingly, but futilly at `%Pegasus`5 who is staring dreamily at `!MightyE`5, her "=>"`n`nMentre la vista ti si oscura, mandi uno sguardo pietoso ma inutile a `%Pegasus`5 che sta osservando con aria sognante `!MightyE`5, con le "
		,"hands clutched next to her face, which is painted with a large admiring smile."=>"mani giunte accanto al viso adornato da un grande sorriso di ammirazione."
		,"`b`&You have been slain by `!MightyE`&!!!`n"=>"`b`&Sei stato ucciso da `!MightyE`&!!!`n"
		,"`4All gold on hand has been lost!`n"=>"`4Hai perso tutte le monete che avevi con te!`n"
		,"`410% of experience has been lost!`n"=>"`4Hai perso il 10% della tua esperienza!`n"
		,"You may begin fighting again tomorrow."=>"Potrai ricominciare a combattere domani."
		,"Daily news"=>"Notizie giornaliere"
		,"`5 has been slain by `!MightyE`5 for trying to steal from `#Pegasus`5' Armor Wagon."=>"`5 è stato ucciso da `!MightyE`5 per aver tentato di rubare dal carrozzone di `#Pegasus`5."
		,array(
			"`#Pegasus`5 takes your gold, and much to your surprise she also takes your "=>"`#Pegasus`5 prende le tue monete e, con tua grande sorpresa, prende anche il tuo "
			,"`5 and promptly puts a price on it, setting it neatly on another stack of clothes. "=>"`5 rapidamente gli attacca un prezzo e lo mette in bella mostra su un'altra pila di abiti. "
			)
		,"`n`nIn return, she hands you a beautiful new"=>"`n`nIn cambio ti da uno splendido nuovo"
		,array(
			"`n`nYou begin to protest, \"`@Won't I look silly wearing nothing but a "=>"`n`nInizi a protestare, \"`@Non sembrerò stupido indossando solo "
			,"`@?`5\" you ask.  You ponder it a moment, and then realize that everyone else in "=>"`@?`5\" domandi. Ci pernsi per un attimo e poi ti rendi conto che tutti quanti in città stanno "
			)
		,"the town is doing the same thing.  \"`@Oh well, when in Rome...`5\""=>"facendo lo stesso.  \"`@Oh beh, quando sei a Roma...`5\""
		,"Return to the village"=>"Torna al villaggio"
		);
		break;
	case "armoreditor.php":
		$replace = array(
		
		);
		break;
	case "bank.php":
		$replace = array(
		"`^`c`bYe Olde Bank`b`c`6"=>"`^`c`bLa Vecchia Banca`b`c`6"
		,"A short man in a immaculately arranged suit greets you from behind reading spectacles.`n`n"=>"Un uomo basso con un abito immacolato ti saluta da dietro i suoi occhiali da lettura.`n`n"
		,"\"`5Hello my good man,`6\" you greet him, \"`5Might I inquire as to my balance this fine day?`6\"`n`n"=>"\"`5Salute buon uomo,`6\" rispondi, \"`5Potrei chiedere a quanto ammonta il mio saldo in questo splendido giorno?`6\"`n`n"
		,array(
			"The banker mumbles, \"`3Hmm, "=>"Il banchiere borbotta, \"`3Hmm, "
			,"`3, let's see.....`6\" as he scans down a page "=>"`3, vediamo...`6\" mentre scruta una pagina "
			)
		,"in his ledger.  "=>"nel suo registro.  "
		,array(
			"\"`3Aah, yes, here we are.  You have `^"=>"\"`3Aah, sì, ecco qui.  Hai `^"
			,"\"`3Aah, yes, here we are.  You have a `&debt`3 of `^"=>"\"`3Aah, sì, ecco.  Hai un `&debito`3 di `^"
			," gold`3 in our "=>" monete`3 nella nostra  "
			)
		,"prestigious bank.  Is there anything else I can do for you?`6\""=>"prestigiosa banca. C´è altro che posso fare per te?`6\""
		,"`6`bTransfer Money`b:`n"=>"`6`bTrasferisci Denaro`b:`n"
		,array(
			"You can only transfer `^"=>"Puoi trasferire solo `^"
			,"`6 gold per the recipient's level.`n"=>"`6 monete per livello del destinatario.`n"
			)
		,"<form action='bank.php?op=transfer2' method='POST'>Transfer <u>h</u>ow much: <input name='amount' id='amount' accesskey='h' width='5'>`n"=>"<form action='bank.php?op=transfer2' method='POST'>Quanto vuoi <u>t</u>rasferire: <input name='amount' id='amount' accesskey='t' width='5'>`n"
		,"T<u>o</u>: <input name='to' accesskey='o'> (partial names are ok, you will be asked to confirm the transaction before it occurs).`n"=>"<u>A</u>: <input name='to' accesskey='a'> (vanno bene nomi incompleti, ti verrà chiesta conferma prima di completare la transazione).`n"
		,"<input type='submit' value='Preview Transfer'></form>"=>"<input type='submit' value='Anteprima Trasferimento'></form>"
		,"`6The little old banker tells you that he refuses to transfer money for someone who is in debt."=>"`6Il piccolo banchiere ti dice che si rifiuta di trasferire denaro per qualcuno che ha un debito."
		,"`6`bConfirm Transfer`b:`n"=>"`6`bConferma Trasferimento`b:`n"
		,array(
			"`6Transfer `^"=>"`6Trasferisci `^"
			,"`6 to `&"=>"`6 a `&"
			)
		,"<input type='submit' value='Complete Transfer'>"=>"<input type='submit' value='Completa Trasferimento'>"
		,array(
			"`6Transfer `^"=>"`6Trasferisci `^"
			,"`6 to "=>"`6 a "
			)
		,"<input type='submit' value='Complete Transfer'>"=>"<input type='submit' value='Completa Trasferimento'>"
		,"`6No one matching that name could be found!  Please try again."=>"`6Non è stato trovato nessuno con quel nome! Per favore ritenta."
		,"`6`bTransfer Completion`b`n"=>"`6`bCompletamento del Trasferimento`b`n"
		,array(
			"`6How can you transfer `^"=>"`6Come puoi trasferire `^"
			,"`6 gold when you only possess "=>"monete se ne possiedi solo "
			)
		,array(
			"`6The transfer was not completed: `&"=>"`6Il trasferimento non è stato completato: `&"
			,"`6 can only receive up to `^"=>"`6 può ricevere al massimo `^"
			,"`6 gold."=>"`6 monete."
			)
		,"`6 has received too many transfers today, you will have to wait until tomorrow."=>"`6 haricevuto troppi trasferimenti di denaro oggi, dovrai aspettare domani."
		,"`6You might want to send a worthwhile transfer, at least as much as your level."=>"`6Vorrai fare un trasferimento decente, almeno pari al tuo livello."
		,"`6You cannot transfer money to yourself!  That makes no sense!"=>"`6Non puoi mandare dei soldi a te stesso! Non ha senso!"
		,"`6Transfer Completed!"=>"`6Trasferimento Completato!"
		,array(
			"`^You have received a money transfer!`0"=>"`^Hai ricevuto del denaro!`0"
			,"`6 has transferred `^"=>"`6 ha trasferito `^"
			,"`6 gold to your bank account!"=>"`6 monete sul tuo conto in bancat!"
			)
		,"`6Transfer could not be completed, please try again!"=>"`6Non è stato possibile completare il trasferimento, sei pregato di ritentare!"
		,"`^".($session[user][goldinbank]>=0?"Deposit":"Pay off")." <u>h</u>ow much? <input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' value='Deposit'>`n`iEnter 0 or nothing to deposit it all`i</form>"=>"`^<u>Q</u>uanto vuoi ".($session[user][goldinbank]>=0?"depositare":"restituire")."? <input id='input' name='amount' width=5 accesskey='q'> <input type='submit' class='button' value='Deposita'> `n`iDigita 0 o niente per depositare tutto`i</form>"
			
		,"`\$ERROR: Not enough gold in hand to deposit.`^`n`n"=>"`\$ERRORE: Non hai così tanti soldi da depositare.`^`n`n"
		,array(
			"You plunk your `&"=>"Lasci le tue `&"
			,"`^ gold on the counter and declare that you would like to deposit all `&"=>"`^ monete sul bancone e dichiari di voler depositare `&"
			,"`^ gold of it."=>"`^ monete."
			)
		,"`n`nThe little old man stares blankly for a few seconds until you become self conscious and count your money again, realizing your mistake."=>"`n`nL´ometto ti fissa con sguardo vacuo per qualche secondo fino a quando te ne rendi conto e conti di nuovo i tuoi soldi, accorgendoti dell´errore."
		,array(
			"`^`bYou deposit `&"=>"`^`bHai depositato `&"
			,"`^ gold in to your bank account, "=>"`^ monete nel tuo conto in banca, "
			)
		,array(
			"leaving you with ".($session[user][goldinbank]>=0?"a balance of":"a debt of")." `&"=>"restando con ".($session[user][goldinbank]>=0?"un saldo di":"un debito di")." `&"
			,"`^ gold in your account and `&"=>"`^ monete nel tuo conto e `&"
			,"`^ gold in hand.`b"=>"`^ monete in tasca.`b"
			)
		,array(
			"You have "=>"Hai "
			,"a balance of"=>"un saldo di"
			,"a debt of"=>"un debito di"
			," gold in the bank.`n"=>" monete.`n"
			)
		,array(
			"`^Borrow <u>h</u>ow much (you may borrow a max of "=>"`^<u>Q</u>uanto vuoi chiedere in prestito (massimo "
			," total at your level)? <input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' name='borrow' value='Borrow'>`n(Money will be withdrawn until you have none left, the remainder will be borrowed)</form>"=>" monete in tutto al tuo livello)? <input id='input' name='amount' width=5 accesskey='q'> <input type='submit' class='button' name='borrow' value='Chiedi Prestito'>`n(I soldi verranno prelevati dal tuo conto finché ne hai, il resto verrà preso in prestito)</form>"
			)
		,array(
			"You have "=>"Hai "
			," gold in the bank.`n"=>" monete in banca.`n"
			)
		,"`^Withdraw <u>h</u>ow much? <input id='input' name='amount' width=5 accesskey='h'> <input type='submit' class='button' value='Withdraw'>`n`iEnter 0 or nothing to withdraw it all`i</form>"=>"`^<u>Q</u>uanto vuoi prelevare? <input id='input' name='amount' width=5 accesskey='q'> <input type='submit' class='button' value='Preleva'>`n`iDigita 0 o niente per prelevare tutto`i</form>"
		,"`\$ERROR: Not enough gold in the bank to to withdraw.`^`n`n"=>"`\$ERRORE: Non hai così tanti soldi sul conto.`^`n`n"
		,array(
			"Having been informed that you have `&"=>"Essendo stato informato che hai `&"
			,"`^ gold in your account, you declare that you would like to withdraw all `&$_POST[amount]`^ of it."=>"`^ monete sul tuo conto, dichiari di volerne prelevare `&$_POST[amount]`^."
			)
		,"`n`nThe little man stares blankly at you for a second, then advises you take basic arithmetic.  You realize your folly and think you should try again."=>"`n`nL´ometto ti fissa per un secondo con uno sguardo vacuo, poi ti consiglia di riprendere a studiare l´aritmetica. Ti rendi conto della tua stupidaggine e pensi che dovresti riprovare."
		,array(
			"`6You withdraw your remaining `^"=>"`6Prelevi le restanti `^"
			,"`6 gold, and "=>"`6 monete, e "
			)
		,"`6You "=>"`6Tu "
		,"ask to borrow `^$lefttoborrow`6 gold.  The short little man looks up your account and informs you that you may only borrow up to `^$maxborrow`6 gold."=>"chiedi in prestito `^$lefttoborrow`6 monete. L´ometto guarda il tuo conto e ti informa che puoi chiedere al massimo `^$maxborrow`6 monete."
		,"borrow `^$lefttoborrow`6 gold."=>"ricevi in prestito `^$lefttoborrow`6 monete."
		,array(
			"`6Considering the `^{$session[user][goldinbank]}`6 gold in your account, you ask to borrow `^"=>"`6Tenendo conto delle `^{$session[user][goldinbank]}`6 monete nel tuo conto, ne chiedi in prestito `^"
			,"`6, but
			the short little man looks up your account and informs you that you may only borrow up to `^"=>"`6, ma l´ometto guarda il tuo conto e ti informa che puoi averne al massimo `^"
			,"`6 gold at your level."=>"`6 al tuo livello."
			)
		,array(
			"`^`bYou withdraw `&$_POST[amount]`^ gold from your bank account, "=>"`^`bPrelevi `&$_POST[amount]`^ monete dal tuo conto in banca, "
			,"leaving you with `&"=>"restando con `&"
			,"`^ gold in your account and `&"=>"`^ monete nel tuo conto e `&"
			,"`^ gold in hand.`b"=>"`^ monete in tasca.`b"
			)
		,"Return to the Village"=>"Torna al Villaggio"
		,"Withdraw"=>"Preleva"
		,"Deposit"=>"Deposita"
		,"Take out a loan"=>"Prendi un prestito"
		,"Pay off debt"=>"Paga il debito"
		,"Transfer Money"=>"Trasferisci denaro"
		,"Borrow more"=>"Chiedi altri soldi"
		);
		break;
	case "battle.php":
		$replace = array(
		array(
			"`@You have encountered `^"=>"`@Incontri `^"
			,"`@ which lunges at you with `%"=>"`@ che ti attacca con "
			)
		,"`2Level: `6"=>"`2Livello: `6"
		,"`2Level: `6Undead`0`n"=>"`2Livello: `6Non-Morto`0`n"
		,"`2`bStart of round:`b`n"=>"`2`bInizio del round:`b`n"
		);
		break;
	case "bio.php":
		$replace = array(
		"Character Biography: "=>"Biografia del Personaggio: "
	,array(
				"Unspecified"=>"Non specificata"
		,"Dark Arts"=>"Arti Oscure" 
		,"Mystical Powers"=>"Poteri Mistici" 
		,"Thieving Skills"=>"Furto" 
		,"None"=>"Nessuno" 
		,"`^Specialty: `@"=>"`^Specialità: `@"
				,"`^Horse: `@"=>"`^Cavallo: `@"
		)
		,"None"=>"Nessuno"
		,"Gelding"=>"Castrone"
		,"Stallion"=>"Stallone"
		,"`^Biography for "=>"Biografia di "
		,"`^Title:"=>"`^Titolo:"
		,"`^Level:"=>"`^Livello:"
		,"`^Resurrections:"=>"`^Resurrezioni:"
		,array(
		"`^Gender:"=>"`^Sesso:"
		,"Female"=>"Femmina"
		,"Male"=>"Maschio"
		)
		,"`n`^Recent accomplishments (and defeats) of"=>"`n`^Imprese (e sconfitte) recenti di"
		,"Return to the warrior list"=>"Torna all'elenco guerrieri"
		,"Return whence you came"=>"Torna da dove sei venuto"
		);
		break;
	case "configuration.php":
		$replace = array(

		);
		break;
	case "creatures.php":
		$replace = array(
		
		);
		break;
	case "dragon.php":
		$replace = array(
		"The Green Dragon!"=>"Il Drago Verde!"
		,"`\$Fighting down every urge to flee, you cautiously enter the cave entrance, intent "=>"`\$Combattendo ogni istinto di fuga, entri con cautela nella caverna, con "		,"on catching the great green dragon sleeping, so that you might slay him with a minimum "=>"l'intenzione di trovare il grande drago addormentato, in modo da poterlo uccidere con il minimo "
		,"of pain.  Sadly, this is not to be the case, for as you round a corner within the cave "=>"sforzo. Tristemente, non è questo il caso, poiché non appena svolti un angolo della caverna "
		,"you discover the great beast sitting on its haunches on a huge pile of gold, picking its "=>"trovi la grande bestia seduta su un'enorme pila di monete, intenta a stuzzicarsi "
		,"teeth with what looks to be a rib."=>"i denti con quella che sembra essere una costola."
		,"`@Victory!`n`n"=>"`@Vittoria!`n`n"
		,"`2Before you, the great dragon lies immobile, its heavy breathing like acid to your lungs.  "=>"`2Davanti a te giace immobile il grande drago, il suo pesante respiro sembra acido per i tuoi polmoni.  "
		,"You are covered, head to toe, with the foul creature's thick black blood.  "=>"Sei coperto dalla testa ai piedi del sangue nero della creatura.  "
		,"The great beast begins to move its mouth.  You spring back, angry at yourself for having been "=>"La grande bestia inizia a muovere la bocca. Balzi indietro, furente con te stesso per esserti lasciato ingannare "
		,"fooled by its ploy of death, and watch for its huge tail to come sweeping your way.  But it does "=>"dalla sua morte simulata, e ti aspetti che la sua possente coda inizi a muoversi per colpirti. Ma non lo fa. "
		,"not.  Instead the dragon begins to speak.`n`n"=>" Invece, il drago inizia a parlare.`n`n"
		,"\"`^Why have you come here mortal?  What have I done to you?`2\" it says with obvious effort.  "=>"\"`^Perché sei venuto qui mortale? Che cosa ti ho fatto?`2\" dice con sforzo evidente.  "
		,"\"`^Always my kind are sought out to be destroyed.  Why?  Because of stories from distant lands "=>"\"`^Da sempre la mia razza viene cacciata e distrutta. Perché? A causa di storie da terre lontane "
		,"that tell of dragons preying on the weak?  I tell you that these stories come only from misunderstanding "=>"che parlano di draghi che predano i più deboli? Io ti dico che tali storie provengono solo da equivoci "
		,"of us, and not because we devour your children.`2\"  The beast pauses, breathing heavily before continuing, "=>"su di noi, e non dal fatto che divoriamo i vostri figli.`2\"  La bestia fa una pausa, respirando pesantemente prima di continuare, "
		,"\"`^I will tell you a secret.  Behind me now are my eggs.  They will hatch, and the young will battle "=>"\"`^Ti dirò un segreto. Dietro di me ci sono le mie uova. Si schiuderanno, e i piccoli si combatteranno "
		,"each other.  Only one will survive, but she will be the strongest.  She will quickly grow, and be as "=>"tra loro. Solo una sopravviverà, ma sarà la più forte. Crescerà in fretta, e sarà "
		,"powerful as me.`2\"  Breath comes shorter and shallower for the great beast.`n`n"=>"potente quanto me.`2\" Il respiro della grande bestia si mozza e diventa irregolare.`n`n"
		,"\"`#Why do you tell me this?  Don't you know that I will destroy your eggs?`2\" you ask.`n`n"=>"\"`#Perchè mi dici questo? Non sai che ora distruggerò le tue uova?`2\" domandi.`n`n"
		,"\"`^No, you will not, for I know of one more secret that you do not.`2\"`n`n"=>"\"`^No, non lo farai, perché io conosco un altro segreto che tu non conosci.`2\"`n`n"
		,"\"`#Pray tell oh mighty beast!`2\"`n`n"=>"\"`#Dimmi allora oh potente bestia!`2\"`n`n"
		,"The great beast pauses, gathering the last of its energy.  \"`^Your kind cannot tolerate the blood of "=>"La grande bestia fa una pausa, raccogliendo le sue ultime energie. \"`^La tua razza non può sopportare il sangue "
		,"my kind.  Even if you survive, you will be a feeble human, barely able to hold a weapon, your mind "=>"della mia. Se anche sopravvivi, sarai un umano debole, a malapena in grado di impugnare un'arma, la tua mente "
		,"blank of all that you have learned.  No, you are no threat to my children, for you are already dead!`2\"`n`n"=>"vuotata di tutto ciò che hai appreso. No, non sei una minaccia per la mia prole, perché sei già morto!`2\"`n`n"
		,"Realizing that already the edges of your vision are a little dim, you flee from the cave, bound to reach "=>"Rendendoti conto che la tua vista si sta già sfocando, fuggi dalla caverna, cercando di raggiungere "
		,"the healer's hut before it is too late.  Somewhere along the way you lose your weapon, and finally you "=>"la capanna del guaritore prima che sia troppo tardi. Da qualche parte lungo la strada perdi la tua arma, ed infine "
		,"trip on a stone in a shallow stream, sight now limited to only a small circle that seems to float around "=>"inciampi in una pietra in un torrentello, con la vista ormai limitata ad un piccolo circolo che sembra galleggiare intorno a te "
		,"your head.  As you lay, staring up through the trees, you think that nearby you can hear the sounds of the "=>" Mentre giaci, guardando in alto verso gli alberi, credi di sentire non distante i suoni "
		,"village.  Your final thought is that although you defeated the dragon, you reflect on the irony that it "=>"del villaggio. Il tuo ultimo pensiero è che sebbene tu abbia sconfitto il drago, rifletti sull'ironia "
		,"defeated you.`n`n"=>"del fatto che esso abbia sconfitto te.`n`n"
		,"As your vision winks out, far away in the dragon's lair, an egg shuffles to its side, and a small crack "=>"Mentre la vista ti si spegne, lontano, nella tana del drago, un uovo si ribalta di lato, ed una piccola crepa "
		,"appears in its thick leathery skin."=>"compare nel suo spesso guscio."
		,"`^You gain FIVE charm points for having defeated the dragon!`n"=>"`^Guadagni CINQUE punti di fascino per aver sconfitto il drago!`n"
		,"It is a new day"=>"È un nuovo giorno"
		,array(
			"Baronness"=>"Baronessa"
			,"Baron"=>"Barone"
			,"Dutchess"=>"Duchessa"
			,"Duke"=>"Duca"
			,"Countess"=>"Contessa"
			,"Count"=>"Conte"
			,"Viscountess"=>"Viscontessa"
			,"Viscount"=>"Visconte"
			,"Marchioness"=>"Marchesa"
			,"Marquis"=>"Marchese"
			,"Princess"=>"Principessa"
			,"Prince"=>"Principe"
			,"Queen"=>"Regina"
			,"King"=>"Re"
			,"Empress"=>"Imperatrice"
			,"Emperor"=>"Imperatore"
			,"Goddess"=>"Dea"
			,"God"=>"Dio"
			)
		,"`n`nYou wake up in the midst of some trees.  Nearby you hear the sounds of a village.  "=>"`n`nTi svegli nel mezzo di un gruppetto di alberi. Non lontano senti i rumori di un villaggio.  "
		,"Dimly you remember that you are a new warrior, and something of a dangerous Green Dragon that is plaguing "=>"Ricordi vagamente di essere un nuovo guerriero, e qualcosa a proposito di un pericoloso Drago Verde che infesta "
		,"the area.  You decide you would like to earn a name for yourself by perhaps some day confronting this "=>"l'area. Decidi che vorresti farti un nome, magari confrontandoti un giorno con questa "
		,"vile creature."=>"vile creatura."
		,array(
			" has earned the title `&"=>" ha acquisito il titolo di `&"
			,"`# for having slain the `@Green Dragon`& `^"=>"`# per aver ucciso il `@Drago Verde`& `^"
			,"`# times!"=>"`# volte!"
			)
		,"`n`n`^You are now known as `&"=>"`n`n`^Ora sei noto come `&"
		,array(
			"`n`n`&Because you have slain the dragon "=>"`n`n`&Avendo ucciso il drago "
			," times, you start with some extras.  You also keep additional hitpoints you've earned or purchased.`n"=>" volte, parti con alcuni extra. Inoltre mantieni tutti i punti ferita aggiuntivi che hai acquisito o acquistato.`n"
			)
		,"The creature's tail blocks the only exit to its lair!"=>"La coda della creatura blocca l'unica uscita della tana!"
		,"`&With a mighty final blow, `@The Green Dragon`& lets out a tremendous bellow and falls to your feet, dead at last."=>"`&Con un possente colpo finale, `@Il Drago Verde`& emette un tremendo ruggito e cade ai tuoi piedi, morto infine."
		," has slain the hideous creature known as `@The Green Dragon`&.  Across all the lands, people rejoice!"=>" ha ucciso la creatura nota come `@Il Drago Verde`&.  Gente del paese, gioite!"
		,"Continue"=>"Continua"
		,"Daily news"=>"Notizie giornaliere"
		,"`b`&You have been slain by `%"=>"`b`&Sei stato ucciso da `%"
		,"`4All gold on hand has been lost!`n"=>"`4Hai perso tutto il denaro che avevi con te!`n"
		,"You may begin fighting again tomorrow."=>"Potrai ricominciare a combattere domani."
		
		,"You feel mortal again."=>"Ti senti di nuovo mortale."
		,"`n`&You feel godlike`n`n"=>"`n`&Ti senti un dio`n`n"
		,"`n`^You begin to regenerate!`n`n"=>"`n`^Inizi a rigenerarti!`n`n"
		,"`%Regeneration"=>"`%Rigenerazione"
		,"You have stopped regenerating"=>"Hai smesso di rigenerarti"
		,"`% is clutched by a fist of earth and slammed to the ground!`n`n"=>"`% viene afferrato da un pugno di terra e sbattuto al suolo!`n`n"
		,"`%Earth Fist"=>"`%Pugno di Terra"
		,"The earthen fist crumbles to dust."=>"Il pugno di terra si sgretola."
		,"`n`^Your weapon glows with an unearthly presence.`n`n"=>"`n`^La tua arma emette un bagliore ultraterreno.`n`n"
		,"`%Siphon Life"=>"`%Drena Vita"
		,"Your weapon's aura fades."=>"L´aura della tua arma svanisce."
		,"`n`^Your skin sparkles as you assume an aura of lightning`n`n"=>"`n`^La tua pelle scintilla e vieni avvolto da un´aura di fulmini`n`n"
		,"`%Lightning Aura"=>"`%Aura di Fulmini"
		,"With a fizzle, your skin returns to normal."=>"Crepitando, la tua pelle torna normale."
		,array(
			"`nYou furrow your brow and call on the powers of the elements.  A tiny flame appears.  "=> "`nAggrotti la fronte ed evochi il potere degli elementi. Una fiammella compare. "
			," lights a cigarrette from it, giving you a word of thanks before swinging at you again."=>" la usa per accendersi una sigaretta e ti ringrazia prima di ricominciare a colpirti."
			)
		,array(
			"`n`\$You call on the spirits of the dead, and skeletal hands claw at "=>"`n`\$Evochi gli spiriti dei morti, e delle mani scheletriche artigliano "
			," from beyond the grave.`n`n"=>" da dentro la tomba.`n`n"
			)
		,"`\$Skeleton Crew"=>"`\$Ciurma di Scheletri"
		,"Your skeleton minions crumble to dust."=>"I tuoi servi scheletrici si riducono in polvere."
		,array(
			"`n`\$You pull out a tiny doll that looks like "=>"`n`\$Estrai una bambolina che sembra "
			)
		,array(
			"`n`\$You place a curse on "=>"`n`\$Lanci una maledizione contro gli antenati di "
			,"'s ancestors.`n`n"=>".`n`n"
			)
		,"`\$Curse Spirit"=>"`\$Maledizione"
		,"Your curse has faded."=>"La tua maledizione si esaurisce."
		,array(
			"`n`\$You hold out your hand and "=>"`n`\$Punti il dito e le orecchie di "
			," begins to bleed from its ears.`n`n"=>" iniziano a sanguinare.`n`n"
			)
		,"`\$Whither Soul"=>"`\$Avvizzisci Anima"
		,"Your victim's soul has been restored."=>"L´anima della tua vittima è stata curata."
		,array(
			"`nExhausted, you try your darkest magic, a bad joke.  "=>"`nEsausto, tenti la tua magia più oscura, una pessima battuta.  "
			," looks at you for a minute, thinking, and finally gets the joke.  Laughing, it swings at you again.`n`n"=>" ti guarda pensieroso per un minuto, poi finalmente capisce la battuta. Ridendo, riprende a picchiarti.`n`n"
			)
		,array(
			"`n`^You call "=>"`n`^Chiami "
			," a bad name, making it cry.`n`n"=>" con un soprannome, facendolo piangere.`n`n"
		)
		,"`^Insult"=>"`^Insulto"
		," stops crying and wipes its nose."=>" smette di piangere e si soffia il naso."
		,"`n`^You apply some poison to your "=>"`n`^Metti del veleno sul tuo "
		,"`^Poison Attack"=>"`^Veleno"
		,"Your victim's blood has washed the poison from your blade."=>"Il sangue della tua vittima ha lavato via il veleno dall´arma."
		,array(
			"`n`^With the skill of an expert thief, you virtually dissapear, and attack "=>"`n`^Con la tua esperienza di ladro, scompari ed attacchi "
			," from a safer vantage point.`n`n"=>" da una posizione di vantaggio.`n`n"
			)
		,"`^Hidden Attack"=>"`^Attacco nascosto"
		,"Your victim has located you."=>"La tua vittima ti ha localizzato."
		,array(
			"`n`^Using your skills as a thief, dissapear behind "=>"`n`^Usando i tuoi talenti di ladro, scompari dietro "
			," and slide a thin blade between its vertibrae!`n`n"=>" e infili una lama sottile tra le sue vertebre!`n`n"
			)
		,"`^Backstab"=>"`^Pugnalata alle spalle"
		,"Your victim won't be so likely to let you get behind it again!"=>"È improbabile che la tua vittima ti faccia passare di nuovo dietro di lei!"
		,"`nYou try to attack $badguy[creaturename] by putting your best thievery skills in to practice, but instead, you trip over your feet."=>"`nTenti di attaccare $badguy[creaturename] mettendo in pratica i tuoi migliori talenti di ladro, ma inciampi nel tuo piede."
		,"`\$`c`b~ ~ ~ Fight ~ ~ ~`b`c`0"=>"`\$`c`b~ ~ ~ Combattimento ~ ~ ~`b`c`0"
		,"`&The gods have stripped you of any special effects!`n"=>"`&Gli dei ti hanno privato di tutti gli effetti speciali!`n"
		,"is hit for `^"=>"subisce `^"
		,"staggers under the weight of your curse, and deals only half damage.`n"=>"trema sotto il peso della tua maledizione, e causa solo metà del danno.`n"
		,"claws at its eyes, trying to release its own soul, and cannot attack or defend.`n"=>"si artiglia gli occhi, tentando di liberare la sua anima, e non può attaccare né difendersi.`n"
		,"feels dejected, and cannot attack as well."=>"si sente depresso e non attacca."
		,"`)Your attack damage is multiplied"=>"`)Il tuo danno d'attacco è aumentato"
		,", as is your defense"=>", ed anche la tua difesa"
		," cannot locate you.`n"=>" non riesce a localizzarti.`n"
		,array(
			"`)You regenerate for "=>"`)Rigeneri "
			,"`)A huge fist of earth pummels your opponent for `^$r points.`n"=>"`)Un grosso pugno di terra causa alla tua vittima `^$r punti di danno.`n"
			," points.`n"=>" punti.`n"
			)
		,"`)You've got a nice buzz going`n"=>"`)Stai emettendo un bel ronzio`n"
		,"`)You feel godlike`n"=>"`)Ti senti un dio`n"
		,"`)You are healed for $healhp points.`n"=>"`)Vieni guarito di $healhp punti.`n"
		,"`)You are healed for $healhp points.`n"=>"`)Vieni guarito di $healhp punti.`n"
		,"is hit for `^"=>"subisce `^"
		,"'s skill allows them to get the first round of attack!"=>"`\$ è tanto abile da attaccare per primo!`0`b`n`n"
		,"`\$ surprises you and gets the first round of attack!`0`b`n`n"=>"`\$ ti prende di sorpresa e attacca per primo!`0`b`n`n"
		,"Your skill allows you to get the first attack!"=>"La tua abilità ti consente di attaccare per primo!"
		,"`&`bYou execute a <font size='+1'>MEGA</font> power move!!!`b`n"=>"`&`bHai usato una <font size='+1'>MEGA</font> mossa speciale!!!`b`n"
		,"`&`bYou execute a DOUBLE power move!!!`b`n"=>"`&`bHai usato una DOPPIA mossa speciale!!!`b`n"
		,"`&`bYou execute a power move!!!`b`0`n"=>"`&`bHai usato una mossa speciale!!!`b`0`n"
		,"`7`bYou execute a minor power move!`b`0`n"=>"`7`bHai usato una piccola mossa speciale!`b`0`n"
		,array(
			"`@You have encountered `^"=>"`@Incontri `^"
			,"`@ which lunges at you with `%"=>"`@ che ti attacca con `%"
			,"Great Flaming Maw"=>"Enormi Fauci Fiammeggianti"
			,"`2Level: `6"=>"`2Livello: `6"
			,"`2`bStart of round:`b`n"=>"`2`bInizio del round:`b`n"
			,"Hitpoints"=>"Punti ferita"
			,"Soulpoints"=>"Punti anima"
			,"`2's "=>"`2 ha "
			,"`2YOUR "=>"`2TU HAI "
			,"Return to the Village"=>"Torna al villaggio"
			,"`5 has been slain when "=>"`5 è stato ucciso quando "
			," encountered `@The Green Dragon`5!!!  "=>" incontrato `@Il Drago Verde`5!!!  "
			,"Her"=>"Le sue"
			,"His"=>"Le sue"
			," bones now litter the cave entrance, just like the bones of those who came before."=>" ossa ora pavimentano l'ingresso della caverna, proprio come quelle di chi ci ha provato prima."
			,"`4You try to hit `^"=>"`4Cerchi di colpire `^"
			,"`)An undead minion hits "=>"`)Un servo non morto colpisce "
			,"`) damage.`n"=>" punti di danno.`n"
			,"`)An undead minion tries to hit "=>"`)Un servo non morto tenta di colpire "
			," but `\$MISSES`)!`n"=>" ma `\$MANCA`)!`n"
			,"You thrust a pin into the "=>"Pungi la bambola di "
			," doll hurting it for"=>" con uno spillone causando"
			,"`4 but `\$MISS!`n"=>"`4 ma `\$MANCHI!`n"
			,"`4 but are `\$RIPOSTED `4for `\$"=>"`4 ma `\$CONTRATTACCA e ti causa `\$"
			,"`4You hit `^"=>"`4Colpisci `^"
			,"`4 is hit for `^"=>"`4 subisce `^"
			,"`4 hits you for `\$"=>"`4 ti colpisce causando `\$"
			,"`4 tries to hit you but you `^RIPOSTE`4 for `^"=>"`4 cerca di colpirti ma tu `^CONTRATTACCHI`4 e gli causi `^"
			,"`4 for `^"=>"`4 causando `^"
			,"`4 tries to hit you but `\$MISSES!`n"=>"`4 cerca di colpirti ma `\$MANCA!`n"
			,"`4 points of damage!`n"=>"`4 punti di danno!`n"
			,"`@The Green Dragon`0"=>"`@Il Drago Verde`0"
			,"she"=>"ha"
			,"he"=>"ha"
			,"points!"=>"punti di danno!"
			)
		,"`4You are too busy trying to run away like a cowardly dog to try to fight"=>"`4Sei troppo impegnato a tentare di scappare come un coniglio per combattere"
		,"`2`bEnd of Round:`b`n"=>"`2`bFine del Round:`b`n"

		,"Fight"=>"Combatti"
		,"Run"=>"Fuggi"
		,"`bSpecial Abilities`b"=>"`bAbilità Speciali`b"
		,"`\$Dark Arts`0"=>"`\$Arti Oscure`0"
		,"`\$&#149; Skeleton Crew`7 (1/"=>"`\$&#149; Ciurma di Scheletri`7 (1/"
		,"`\$&#149; C`\$urse Spirit`7 (3/"=>"`\$&#149; M`\$aledizione`7 (3/"
		,"`\$&#149; W`\$hither Soul`7 (5/"=>"`\$&#149; A`\$vvizzisci Anima`7 (5/"
		,"`^Thieving Skills`0"=>"`^Furto`0"
		,"`^&#149; Insult`7 (1/"=>"`^&#149; Insulto`7 (1/"
		,"`^&#149; P`^oison Blade`7 (2/"=>"`^&#149; V`^eleno`7 (2/"
		,"`^&#149; H`^idden Attack`7 (3/"=>"`^&#149; A`^ttacco Nascosto`7 (3/"
		,"`^&#149; B`^ackstab`7 (5/"=>"`^&#149; P`^ugnalata alle Spalle`7 (5/"
		,"`%Mystical Powers`0"=>"`%Poteri Mistici`0"
		,"g?`%&#149; Regeneration`7 (1/"=>"`%&#149; Rigenerazione`7 (1/"
		,"`%&#149; E`%arth Fist`7 (2/"=>"`%&#149; P`%ugno di Terra`7 (2/"
		,"`%&#149; S`%iphon Life`7 (3/"=>"`%&#149; D`%rena Vita`7 (3/"
		,"`%&#149; L`%ightning Aura`7 (5/"=>"`%&#149; A`%ura di Fulmini`7 (5/"
		,"`&&#149;G`&OD MODE"=>"`&&#149;M`&ODALITÀ DIVINA"
		,"`^None`0"=>"`^Nessuna`0"

		);
		break;
	case "forest.php":
		$replace = array(
			"Something Special"=>"Qualcosa di speciale"
			,"The Forest, home to evil creatures and evil doers of all sorts."=>"La foresta, casa di creature malefiche e malvagi di ogni sorta."
			,"The Forest"=>"La Foresta"
			,"Return to the forest"=>"Torna alla foresta"
			,"You have successfully fled your oponent!"=>"Sei riuscito a sfuggire al nemico!"
			,"You failed to flee your oponent!"=>"Non riesci a sfuggire al nemico!"
			,"Enter the cave"=>"Entra nella caverna"
			,"Run away like a baby"=>"Scappa come un bambino"
			,"You approach the blackened entrance of a cave deep in the forest, though"=>"Ti avvicini all'oscuro ingresso della caverna nella foresta, sebbene"
			,"the trees are scorched to stumps for a hundred yards all around."=>"gli alberti siano ridotti a ceppi bruciati per un centinaio di yarde intorno ad essa."
			,"A thin tendril of smoke escapes the roof of the cave's entrance, and is whisked away"=>"Un sottile filo di fumo esce dalla caverna, e viene spazzato via"
			,"by a suddenly cold and brisk wind.  The mouth of the cave lies up a dozen"=>"da un improvviso soffio di vento freddo. L'imboccatura della caverna attende a circa cinque,"
			,"feet from the forest floor, set in the side of a cliff, with debris making a"=>"metri dalla foresta, scavata nel fianco di un picco, on dei detriti che formano ina"
			,"conical ramp to the opening.  Stalactites and stalagmites near the entrance"=>"rampa conica fino all'apertura. Stalattiti e stalagmiti presso l'entrata"
			,"trigger your imagination to inspire thoughts that the opening is really"=>"colpiscono la tua immaginazione facendoti sembrare che l'apertura sia"
			,"the mouth of a great leach."=>"la bocca di un immenso mostro."
			,"You cautiously approach the entrance of the cave, and as you do, you hear,"=>"Ti avvicini con cautela all'ingresso della caverna, e nel farlo senti,"
			,"or perhaps feel a deep rumble that lasts thirty seconds or so, before silencing"=>"o piuttosto percepisci un rombo sordo che dura trenta secondo o giù di lì prima di cessare"
			,"to a breeze of sulfur-air which wafts out of the cave.  The sound starts again, and stops"=>"con un soffio di aria sulfurea che spazza la caverna. Il suono ricomincia e si ferma"
			,"again in a regular rhythm."=>"di nuovo, con un ritmo regolare."
			,"You clamber up the debris pile leading to the mouth of the cave, your feet crunching"=>"Scali la pila di detriti fino alla bocca della caverna, i tuoi piedi calpestano rumorosamente"
			,"on the apparent remains of previous heroes, or herhaps hors d'ouvers."=>"gli apparenti resti dei precedenti eroi, o forse degli antipasti."
			,"Every instinct in your body wants to run, and run quickly, back to the warm inn, and"=>"Ogni fibra del tuo corpo vorrebbe correre, e velocemente, verso il tempore amichevole della locanda, e"
			,array(
				"the even warmer"=>"l'ancor più caldo"
				,"What do you do?"=>"Cosa fai?"
			)
			,"You are too tired to search the forest any longer today.  Perhaps tomorrow you will have more energy."=>"Sei troppo stanco per continuare a cercare oggi. Forse domani avrai più energia."
			,"Something Special!"=>"Qualcosa di speciale!"
			,"Aww, your administrator has decided you're not allowed to have any special events.  Complain to them, not me."=>"Ahi, il tuo Amministratore ha deciso che non ti è permesso avere eventi speciali. Prenditela con lui, non con me."
			,"ERROR!!!`b`c`&Unable to open the special events!  Please notify the administrator!!"=>"ERRORE!!!`b`c`&Non riesco ad aprire gli eventi speciali! Per favore avverti l'Amministratore!!"
			,"Return to the forest"=>"Torna alla foresta"
			,"You head for the section of forest you know to contain foes that you're a bit more comfortable with."=>"Ti dirigi verso l'area della foresta che sai contenere creature con cui ti senti più a tuo agio."
			,"You head for the section of forest which contains creatures of your nightmares, hoping to find one of them injured."=>"Ti dirigi verso l'area della foresta che sai essere popolata dalle creature dei tuoi incubi, sperando di trovarne una ferita."
			,array(
				"You have slain"=>"Hai ucciso"
				,"!`0`b`n"=>"!`0`b`n"
				)
			,array(
				"Because of the difficult nature of this fight, you are awarded an additional"=>"Vista la difficoltà di questa battaglia, ricevi in più"
				,"Because of the simplistic nature of this fight, you are penalized"=>"Data la facilità; di questa battaglia, vieni penalizzato di"
				,"total experience!"=>"punti di esperienza in totale!"
				,"experience!"=>"esperienza!"
				,"~~ Flawless Fight! ~~`\$`n`bYou receive an extra turn!"=>"~~ Combattimento perfetto! ~~`\$`n`bGuadagni un turno extra!"
				,"You receive"=>"Ricevi"
				,"gold!"=>"monete!"
				)
			,"~~ Flawless Fight! ~~`b`\$`nA more difficult fight would have yielded an extra turn."=>"~~ Combattimento perfetto! ~~`b`\$`nSe fosse stato più difficile avresti guadagnato un turno extra!"
			,"Daily news"=>"Notizie quotidiane"
			,"has been slain in the forest by"=>"è stato ucciso nella foresta da"
			,"You have been slain by"=>"Sei stato ucciso da"
			,"All gold on hand has been lost!"=>"Tutto l'oro che avevi con te è andato perduto!"
			,"10% of experience has been lost!"=>"Hai perso il 10% della tua esperienza!"
			,"You may begin fighting again tomorrow."=>"Potrai combattere di nuovo domani."
			,"`&You find A GEM!`n`#"=>"`&Trovi UNA GEMMA!`n`#"
			,"H?Healer's Hut"=>"Guaritore"
			,"L?Look for Something to kill"=>"Trova qualcosa da uccidere"
			,"S?Go Slumming"=>"Visita i bassifondi"
			,"T?Go Thrillseeking"=>"Vai in cerca di brividi"
			,"Take horse to Dark Horse Tavern"=>"Vai alla Taverna del Cavallo Nero"
			,"Return to the Village"=>"Torna al villaggio"
			,"Seek out the Green Dragon"=>"Cerca il Drago Verde"
			,"Other"=>"Altro"
			,"The Outhouse"=>"I Bagni Pubblici"
			,"The Forest, home to evil creatures and evil doers of all sorts."=>"La foresta, casa di creature malefiche e malvagi di ogni sorta."
			,"The thick foliage of the forest restricts view to only a few yards in most places."=>"Le fitte fronde riducono il campo visivo a pochi metri in gran parte della zona."
			,"The paths would be imperceptible except for your trained eye.  You move as silently as"=>"Il sentiero è quasi impercettibile anche ai tuoi occhi esperti. Ti muovi silenziosamente come"
			,"a soft breeze across the thick mould covering the ground, wary to avoid stepping on"=>"una brezza leggera sullo spesso strato di muschio che ricopre il terreno, attento a non calpestare"
			,"a twig or any of numerous bleached pieces of bone that perforate the forest floor, lest"=>"un rametto o uno dei tanti frammenti di ossa sbiancate che perforano il terreno della foresta, per non"
			,"you belie your presence to one of the vile beasts that wander the forest."=>"rivelare la tua presenza ad una delle vili bestie che vagabondano per la foresta."
//			,"The Forest"=>"La foresta"

		,"You feel mortal again."=>"Ti senti di nuovo mortale."
		,"`n`&You feel godlike`n`n"=>"`n`&Ti senti un dio`n`n"
		,"`n`^You begin to regenerate!`n`n"=>"`n`^Inizi a rigenerarti!`n`n"
		,"`%Regeneration"=>"`%Rigenerazione"
		,"You have stopped regenerating"=>"Hai smesso di rigenerarti"
		,"`% is clutched by a fist of earth and slammed to the ground!`n`n"=>"`% viene afferrato da un pugno di terra e sbattuto al suolo!`n`n"
		,"`%Earth Fist"=>"`%Pugno di Terra"
		,"The earthen fist crumbles to dust."=>"Il pugno di terra si sgretola."
		,"`n`^Your weapon glows with an unearthly presence.`n`n"=>"`n`^La tua arma emette un bagliore ultraterreno.`n`n"
		,"`%Siphon Life"=>"`%Drena Vita"
		,"Your weapon's aura fades."=>"L´aura della tua arma svanisce."
		,"`n`^Your skin sparkles as you assume an aura of lightning`n`n"=>"`n`^La tua pelle scintilla e vieni avvolto da un´aura di fulmini`n`n"
		,"`%Lightning Aura"=>"`%Aura di Fulmini"
		,"With a fizzle, your skin returns to normal."=>"Crepitando, la tua pelle torna normale."
		,array(
			"`nYou furrow your brow and call on the powers of the elements.  A tiny flame appears.  "=> "`nAggrotti la fronte ed evochi il potere degli elementi. Una fiammella compare. "
			," lights a cigarrette from it, giving you a word of thanks before swinging at you again."=>" la usa per accendersi una sigaretta e ti ringrazia prima di ricominciare a colpirti."
			)
		,array(
			"`n`\$You call on the spirits of the dead, and skeletal hands claw at "=>"`n`\$Evochi gli spiriti dei morti, e delle mani scheletriche artigliano "
			," from beyond the grave.`n`n"=>" da dentro la tomba.`n`n"
			)
		,"`\$Skeleton Crew"=>"`\$Ciurma di Scheletri"
		,"Your skeleton minions crumble to dust."=>"I tuoi servi scheletrici si riducono in polvere."
		,array(
			"`n`\$You pull out a tiny doll that looks like "=>"`n`\$Estrai una bambolina che sembra "
			,", and thrust a pin in to it.`n`n"=>", e la pungi con uno spillone.`n`n"
			)
		,array(
			"`n`\$You place a curse on "=>"`n`\$Lanci una maledizione contro gli antenati di "
			,"'s ancestors.`n`n"=>".`n`n"
			)
		,"`\$Curse Spirit"=>"`\$Maledizione"
		,"Your curse has faded."=>"La tua maledizione si esaurisce."
		,array(
			"`n`\$You hold out your hand and "=>"`n`\$Punti il dito e le orecchie di "
			," begins to bleed from its ears.`n`n"=>" iniziano a sanguinare.`n`n"
			)
		,"`\$Whither Soul"=>"`\$Avvizzisci Anima"
		,"Your victim's soul has been restored."=>"L´anima della tua vittima è stata curata."
		,array(
			"`nExhausted, you try your darkest magic, a bad joke.  "=>"`nEsausto, tenti la tua magia più oscura, una pessima battuta.  "
			," looks at you for a minute, thinking, and finally gets the joke.  Laughing, it swings at you again.`n`n"=>" ti guarda pensieroso per un minuto, poi finalmente capisce la battuta. Ridendo, riprende a picchiarti.`n`n"
			)
		,array(
			"`n`^You call "=>"`n`^Chiami "
			," a bad name, making it cry.`n`n"=>" con un soprannome, facendolo piangere.`n`n"
		)
		,"`^Insult"=>"`^Insulto"
		,"Your victim stops crying and wipes its nose."=>"La tua vittima smette di piangere e si soffia il naso."
		,"`n`^You apply some poison to your "=>"`n`^Metti del veleno sul tuo "
		,"`^Poison Attack"=>"`^Veleno"
		,"Your victim's blood has washed the poison from your blade."=>"Il sangue della tua vittima ha lavato via il veleno dall´arma."
		,array(
			"`n`^With the skill of an expert thief, you virtually dissapear, and attack "=>"`n`^Con la tua esperienza di ladro, scompari ed attacchi "
			," from a safer vantage point.`n`n"=>" da una posizione di vantaggio.`n`n"
			)
		,"`^Hidden Attack"=>"`^Attacco nascosto"
		,"Your victim has located you."=>"La tua vittima ti ha localizzato."
		,array(
			"`n`^Using your skills as a thief, dissapear behind "=>"`n`^Usando i tuoi talenti di ladro, scompari dietro "
			," and slide a thin blade between its vertibrae!`n`n"=>" e infili una lama sottile tra le sue vertebre!`n`n"
			)
		,"`^Backstab"=>"`^Pugnalata alle spalle"
		,"Your victim won't be so likely to let you get behind it again!"=>"È improbabile che la tua vittima ti faccia passare di nuovo dietro di lei!"
		,"`nYou try to attack $badguy[creaturename] by putting your best thievery skills in to practice, but instead, you trip over your feet."=>"`nTenti di attaccare $badguy[creaturename] mettendo in pratica i tuoi migliori talenti di ladro, ma inciampi nel tuo piede."
		,"`\$`c`b~ ~ ~ Fight ~ ~ ~`b`c`0"=>"`\$`c`b~ ~ ~ Combattimento ~ ~ ~`b`c`0"
		,array(
			"`@You have encountered `^"=>"`@Incontri `^"
			,"`@ which lunges at you with `%"=>"`@ che ti attacca con `%"
			)
		,"`2Level: `6"=>"`2Livello: `6"
		,"`2Level: `6Undead`0`n"=>"`2Livello: `6Non-Morto`0`n"
		,"`2`bStart of round:`b`n"=>"`2`bInizio del round:`b`n"
		,array(
			"Hitpoints"=>"Punti ferita"
			,"Soulpoints"=>"Punti anima"
			,"`2's "=>"`2 ha "
			,"`2YOUR "=>"`2TU HAI "
			)
		,"`&The gods have stripped you of any special effects!`n"=>"`&Gli dei ti hanno privato di tutti gli effetti speciali!`n"
		,"is hit for `^"=>"subisce `^"
		,"staggers under the weight of your curse, and deals only half damage.`n"=>"trema sotto il peso della tua maledizione, e cauisa solo metà del danno.`n"
		,"claws at its eyes, trying to release its own soul, and cannot attack or defend.`n"=>"si artiglia gli occhi, tentando di liberare la sua anima, e non può attaccare né difendersi.`n"
		,"feels dejected, and cannot attack as well.`n"=>"si sente depresso e non attacca.`n"
		,"`)Your attack damage is multiplied"=>"`)Il tuo danno d'attacco è aumentato"
		,", as is your defense"=>", ed anche la tua difesa"
		," cannot locate you.`n"=>" non riesce a localizzarti.`n"
		,array(
			"`)You regenerate for "=>"`)Rigeneri "
			," points.`n"=>" punti.`n"
			)
		,"`)A huge fist of earth pummels your opponent for `^$r points.`n"=>"`)Un grosso pugno di terra causa alla tua vittima `^$r punti di danno.`n"
		,"`)You've got a nice buzz going`n"=>"`)Stai emettendo un bel ronzio`n"
		,"`)You feel godlike`n"=>"`)Ti senti un dio`n"
		,"`)You are healed for $healhp points.`n"=>"`)Vieni guarito di $healhp punti.`n"
		,"`)You are healed for $healhp points.`n"=>"`)Vieni guarito di $healhp punti.`n"
		,"is hit for `^"=>"subisce `^"
		,"'s skill allows them to get the first round of attack!"=>"`\$ è tanto abile da attaccare per primo!`0`b`n`n"
		,"`\$ surprises you and gets the first round of attack!`0`b`n`n"=>"`\$ ti prende di sorpresa e attacca per primo!`0`b`n`n"
		,"`b`\$Your skill allows you to get the first attack!`b`n`n"=>"`b`\$La tua abilità ti consente di attaccare per primo!`b`n`n"
		,"`&`bYou execute a <font size='+1'>MEGA</font> power move!!!`b`n"=>"`&`bHai usato una <font size='+1'>MEGA</font> mossa speciale!!!`b`n"
		,"`&`bYou execute a DOUBLE power move!!!`b`n"=>"`&`bHai usato una DOPPIA mossa speciale!!!`b`n"
		,"`&`bYou execute a power move!!!`b`0`n"=>"`&`bHai usato una mossa speciale!!!`b`0`n"
		,"`7`bYou execute a minor power move!`b`0`n"=>"`7`bHai usato una piccola mossa speciale!`b`0`n"
			,"`!You miss "=>"`!Ti manca "
			,"Your lover inspires you to keep safe!"=>"Il tuo amante ti ispira a mantenerti al sicuro!"
		,array(
			"`4You try to hit `^"=>"`4Cerchi di colpire `^"
			,"`)An undead minion hits "=>"`)Un servo non morto colpisce "
			,"`) damage.`n"=>" punti di danno.`n"
			,"`)An undead minion tries to hit "=>"`)Un servo non morto tenta di colpire "
			," but `\$MISSES`)!`n"=>" ma `\$MANCA`)!`n"
			,"`4 but `\$MISS!`n"=>"`4 ma `\$MANCHI!`n"
			,"`4 but are `\$RIPOSTED `4for `\$"=>"`4 ma `\$CONTRATTACCA e ti causa `\$"
			,"`4You hit `^"=>"`4Colpisci `^"
			,"`4 is hit for `^"=>"`4 subisce `^"
			,"`4 hits you for `\$"=>"`4 ti colpisce causando `\$"
			,"`4 tries to hit you but you `^RIPOSTE`4 for `^"=>"`4 cerca di colpirti ma tu `^CONTRATTACCHI`4 e gli causi `^"
			,"`4 for `^"=>"`4 causando `^"
			,"`4 points of damage!`n"=>"`4 punti di danno!`n"
			)
		,"`4You are too busy trying to run away like a cowardly dog to try to fight"=>"`4Sei troppo impegnato a tentare di scappare come un coniglio per combattere"
		,"`4 tries to hit you but `\$MISSES!`n"=>"`4 cerca di colpirti ma `\$MANCA!`n"
		,"`2`bEnd of Round:`b`n"=>"`2`bFine del Round:`b`n"

		,"Fight"=>"Combatti"
		,"Run"=>"Fuggi"
		,array(
			"D?Take "=>"Porta "
			," to Dark Horse Tavern"=>" alla Taverna del Cavallo Nero"
			)
		,"`bSpecial Abilities`b"=>"`bAbilità Speciali`b"
		,"`\$D`\$ark Arts`n&#149; Skeleton Crew`7 (1/"=>"`\$A`\$rti Oscure`n&#149; Ciurma di Scheletri`7 (1/"
		,"`\$&#149; C`\$urse Spirit`7 (3/"=>"`\$&#149; M`\$aledizione`7 (3/"
		,"`\$&#149; W`\$hither Soul`7 (5/"=>"`\$&#149; A`\$vvizzisci Anima`7 (5/"
		,"`^T`^hieving Skills`n&#149; Insult`7 (1/"=>"`^F`^urto`n&#149; Insulto`7 (1/"
		,"`^&#149; P`^oison Blade`7 (2/"=>"`^&#149; V`^eleno`7 (2/"
		,"`^&#149; H`^idden Attack`7 (3/"=>"`^&#149; A`^ttacco Nascosto`7 (3/"
		,"`^&#149; B`^ackstab`7 (5/"=>"`^&#149; P`^ugnalata alle Spalle`7 (5/"
		,"`%M`%ystical Powers`n&#149; Regeneration`7 (1/"=>"`%P`%oteri Mistici`n&#149; Rigenerazione`7 (1/"
		,"`%&#149; E`%arth Fist`7 (2/"=>"`%&#149; P`%ugno di Terra`7 (2/"
		,"`%&#149; S`%iphon Life`7 (3/"=>"`%&#149; D`%rena Vita`7 (3/"
		,"`%&#149; L`%ightning Aura`7 (5/"=>"`%&#149; A`%ura di Fulmini`7 (5/"
		,"`&&#149;G`&OD MODE"=>"`&&#149;M`&ODALITÀ DIVINA"
		,"`^None`0"=>"`^Nessuna`0"
		,array(
			"Normal"=>"Normale"
			,"High"=>"Alto"
			,"Low"=>"Basso"
			,"Very "=>"Molto "
			)

		);
		break;
	case "gypsy.php":
		$replace = array(
		"Gypsy Seer's tent"=>"Tenda della veggente zingara"
		,"Return to the village"=>"Torna al villaggio"
		,array(
			"`5You offer the old gypsy woman your `^"=>"`5Offri all'anziana zingara le tue `^"
			,"`5 gold for your gen-u-wine say-ance, however she informs you that the dead 
		may be dead, but they ain't cheap."=>"`5 monete per una ge-nu-ina veg-genza, ma lei ti informa che i morti possono anche 
		essere morti, ma non sono economici."
			)
		,"In a deep trance, you talk with the shades"=>"In trance profonda, parli con le ombre"
		,"`5While in a deep trance, you are able to talk with the dead:`n"=>"`5Mentre sei in trance profonda, sei in grado di parlare con i morti:`n"
		,"Project"=>"Trasmetti"
		,"despairs"=>"si dispera"
		,"Snap out of your trance"=>"Esci dalla trance"
		,array(
			"`5You duck in to a gypsy tent behind `%Pegasus'`5 wagon which promises to let you talk with the deceased.  In typical gypsy style, the old woman sitting behind
	a somewhat smudgy crystal ball informs you that the dead only speak with the paying.  Your price is `^"=>"`5Ti infili nella tenda di una zingara che si trova dietro il carrozzone di `%Pegasus`5 e che promette di lasciarti parlare con i defunti. In tipico stile gitano, l'anziana donna seduta dietro una sfera di cristallo
	ti informa che i morti parlano solo con i clienti paganti. Il prezzo è `^"
			,"`5 gold."=>"`5 monete."
			)
		,"Pay to talk to the dead"=>"Parla con i morti"
		,"Forget it"=>"Lascia perdere"
		,"Superuser Entry"=>"Ingresso superutente"
		);
		break;

	case "graveyard.php":
		$replace = array(
		array(
			"G?Return to the Graveyard"=>"Torna al Cimitero"
			,"Return to The Graveyard"=>"Torna al Cimitero"
			,"The Graveyard"=>"Il Cimitero"
			)
		,"`\$`bYour soul can bear no more torment in this afterlife.`b`0"=>"`\$`bLa tua anima non può sopportare altri tormenti in quest'altra vita.`b`0"
		,"`\$Ramius`) commands you not to flee, you must fight!`n`n"=>"`\$Ramius`) ti ordina di non fuggire, devi combattere!`n`n"
		,"`b`\$You have tormented "=>"`b`\$Hai tormentato "
		,array(
			"`#You recieve `^"=>"`#Ricevi `^"
			,"`# favor with `\$Ramius`#!`n`0"=>"`# favori con `\$Ramius`#!`n`0"
			)
		,"Return to the shades"=>"Torna tra le ombre"
		,"`) has been defeated in the graveyard by "=>"`) è stato sconfitto nel cimitero da "
		,"`b`&You have been defeated by `%"=>"`b`&Sei stato sconfitto da `%"
		,"You may begin tormenting again the next time you cross over in to the spirit world."=>"Potrai ricominciare a tormentare la prossima volta che attraverserai il mondo degli spiriti."
		,"Torment"=>"Tormenta"
		,"Flee"=>"Fuggi"
		,"`)`c`bThe Graveyard`b`c"=>"`)`c`bIl Cimitero`b`c"
		,array(
			"Your spirit wanders in to a lonely graveyard, overgrown with sickly weeds which seem to grab at your spirit as you float past them."=>"Il tuo spirito vaga in un cimitero deserto, ricoperto da erbacce che sembrano afferrare il tuo spirito mentre fluttui al di là di esse."
			,"Around you are the remains of many broken tombstones, some lying on their face, some shattered to pieces.  You can almost hear the"=>"Attorno a te ci sono i resti di numerose lapidi distrutte, alcune giacciono a faccia in giù, altre sono ridotte a pezzi. Puoi quasi udire i"
			,"wails of the souls trapped within each plot lamenting their fates."=>"lamenti delle anime intrappolate in ogni luogo che si lamentano del loro triste destino."
			,"In the center of the graveyard is an ancient looking mausoleum which has been worn by the effects of untold years.  A sinister"=>"Al centro del cimitero si trova un mausoleo dall'aspetto antico che è stato messo a dura prova dagli effetti di innumerevoli anni trascorsi. Un gargoyle"
			,"looking gargoyle adorns the apex of its roof; its eyes seem to follow  you, and its mouth gapes with sharp stone teeth."=>"dall'aria sinistra adorna l'apice del suo tetto; i suoi occhi sembrano seguirti, e la sua bocca è adornata da affilati denti di pietra."
			,"The plaque above the door reads `\$Ramius, Overlord of Death`)."=>"La placca sulla porta dice `\$Ramius, Signore della Morte`)."
		)
		,"Search for something to torment"=>"Trova qualcosa da tormentare"
		,"Enter the Mausoleum"=>"Entra nel Mausoleo"
		,"List Warriors"=>"Elenco Guerrieri"
		,"`)`b`cThe Mausoleum`c`b"=>"`)`b`cIl Mausoleo`c`b"
		,array(
			"You enter the mausoleum and find yourself in a cold, stark marble chamber.  The air around you carries the chill of death itself."=>"Entri nel mausoleo e ti ritrovi in una fredda camera di marmo. L'aria intorno a te ha il freddo della morte stessa."
		,"From the darkness, two black eyes stare into your soul.  A clammy grasp seems to clutch your mind, and fill it with the words of the Overlord of Death, `\$Ramius`) himself.`n`n"=>"Dall'oscurità, due occhi neri scrutano la tua anima. Una forte presa sembra afferrarti la mente, e riempirla con le parole del Signore della Morte, `\$Ramius`) in persona.`n`n"
		,"\"`7Your mortal coil has forsaken you.  Now you turn to me.  There are those within this land that have eluded my grasp and posess a life beyond life.  To prove your worth to me "=>"\"`7Le tue spoglie mortali ti hanno abbandonato. Ora ti rivolgi a me. Vi sono in queste terre coloro che hanno eluso la mia presa e possiedono una vita oltre la vita.  Per provarmi il tuo valore"
		,"and earn my favor, go out and torment their souls.  Should you gain enough of my favor, I will reward you.`)\""=>"e guadagnare i miei favori, esci e tormenta le loro anime.  Se guadagnerai i miei favori a sufficienza, ti ricompenserò.`)\""
			)
		,"Question `\$Ramius`0 about the worth of your soul"=>"Chiedi a `\$Ramius`0 quanto vale la tua anima"
		,array(
			"S?Restore Your Soul ("=>"Ripristina la tua Anima ("
			," favor)"=>" favori)"
			)
		,"`)`b`cThe Mausoleum`c`b"=>"`)`b`cIl Mausoleo`c`b"
		,array(
			"`\$Ramius`) calls you weak for needing restoration, but as you have enough favor with him, he grants your request at the cost of `4"=>"`\$Ramius`) ti chiama debole per il tuo bisogno di ristorazione, ma poichè hai abbastanza favori da riscuotere, ti concede la tua richiesta al costo di `4"
			,"`n`nYou have `6"=>"`n`nHai `6"
			,"`) favor with `\$Ramius`)."=>"`) favori con `\$Ramius`)."
			,"`) favor."=>"`) favori."
			)
		,"`\$Ramius`) curses you and throws you from the Mausoleum, you must gain more favor with him before he will grant restoration."=>"`\$Ramius`) ti maledice e ti caccia via dal Mausoleo, devi guadagnare più favori prima che ti conceda la ristorazione."
		,"`\$Ramius`) sighs and mumbles something about, \"`7just 'cause they're dead, does that mean they don't have to think?`)\"`n`n"=>"`\$Ramius`) sospira e mormora qualcosa a proposito di, \"`7solo perché sono morti, vuol dire che non devono pensare?`)\"`n`n"
		,"Perhaps you'd like to actually `ineed`i restoration before you ask for it."=>"Forse vorresti aver `ibisogno`i di ristorazione, prima di chiederla."
		,"`\$Ramius`) speaks, \"`7You have impressed me indeed.  I shall grant you the ability to visit your foes in the mortal world.`)\""=>"`\$Ramius`) dice \"`7Mi hai veramente impressionato. Ti concedo la possibilità di visitare i tuoi nemici nel mondo dei mortali.`)\""
		,"Ramius Favors"=>"Favori di Ramius"
		,"H?Haunt a foe (25 favor)"=>"Tormenta un nemico (25 favori)"
		,"Resurrection"=>"Resurrezione"
		,"Other"=>"Altro"
		,"`\$Ramius`) speaks, \"`7I am not yet impressed with your efforts.  Continue my work, and we may speak further.`)\""=>"`\$Ramius`) dice: \"`7I tuoi sforzi non mi hanno ancora impressionato. Continua il mio lavoro, e potremo riparlare.`)\""
		,"`\$Ramius`) speaks, \"`7I am moderately impressed with your efforts.  A minor favor I now grant to you, but continue my work, and I may yet have more power to bestow.`)\""=>"`\$Ramius`) dice, \"`7I tuoi sforzi mi hanno moderatamente impressionato. Per ora ti concedo un piccolo favore, ma continua il mio lavoro e potrò darti maggiori poteri.`)\""
		,"`\$Ramius`) is impressed with your actions, and grants you the power to haunt a foe.`n`n"=>"`\$Ramius`) è impressionato dalle tue azioni, e ti concede il potere di tormentare un tuo nemico.`n`n"
		,"Who would you like to haunt? <input name='name' id='name'> <input type='submit' value='Search'>"=>"Chi vorresti tormentare? <input name='name' id='name'> <input type='submit' value='Cerca'>"
		,"Return to the mausoleum"=>"Torna al mausoleo"
		,"`\$Ramius`) could find no one who matched the name you gave him."=>"`\$Ramius`) non è riuscito a trovare nessuno con il nome che gli hai dato."
		,"`\$Ramius`) will allow you to try to haunt these people:`n"=>"`\$Ramius`) ti permetterà di tormentare queste persone:`n"
		,"<tr class='trhead'><td>Name</td><td>Level</td></tr>"=>"<tr class='trhead'><td>Nome</td><td>Livello</td></tr>"
		,"That person has already been haunted, please select another target"=>"Questa persona è già stata tormentata, per favore scegli un altro bersaglio"
		,"You have successfully haunted `7"=>"Hai tormentato `7"
		,array(
			"`)You have been haunted"=>"Sei stato tormentato"
			,"`)You have been haunted by "=>"`)Sei stato tormentato da "
			)
		,"`) unsuccessfully haunted `7"=>"`) ha tentato inutilmente di tormentare `7"
		,array(
			"Just as you were about to haunt `7"=>"Proprio quando tentavi di tormentare `7"
			,"`) good, they sneezed, and missed it completely."=>"`) ha starnutito e non si è accorto di niente."
			)
		,array(
			"You haunt `7"=>"Tormenti molto bene `7"
			,"`) real good like, but unfortunately they're sleeping and are completely unaware of your presence."=>"`) ma sfortunatamente sta dormendo e non si accorge neppure della tua presenza."
			)
		,array(
			"You're about to haunt `7"=>"Stai per tormentare `7"
			,"`), but trip over your ghostly tail and land flat on your, um... face."=>"`), ma inciampi sulla tua coda fantasma e finisci di, uhm... faccia... a terra."
			)
		,array(
			"You go to haunt `7"=>"Vai a tormentare `7"
			,"`) in their sleep, but they look up at you, and roll over mumbling something about eating sausage just before going to bed."=>"`) nel sonno, ma ti guarda e si volta dall'altra parte borbottando qualcosa a proposito di non mangiare salsicce subito prima di andare a dormire."
			)
		,array(
			"You wake `7"=>"Svegli `7"
			,"`) up, who looks at you for a moment before declaring, \"Neat!\" and trying to catch you."=>"`), che ti guarda per un momento prima di dichiarare, \"Forte!\" e cercare di acchiapparti."
			)
		,array(
			"You go to scare `7"=>"Vai a spaventare `7"
			,", but catch a glimpse of yourself in the mirror and panic at the sight of a ghost!"=>"`), ma scorgi il tuo riflesso in uno spacchio e vai in panico alla vista di un fantasma!"
			)
		,"`\$Ramius`) has lost their concentration on this person, you cannot haunt them now."=>"`\$Ramius`) ha perso la concentrazione su questa persona, non puoi tormentarla adesso."
		
		,array(
			"`@You have encountered `^"=>"`@Incontri `^"
			,"`@ which lunges at you with `%"=>"`@ che ti attacca con  `%"
			)
		,"`2Level: `6Undead`0`n"=>"`2Livello: `6Non-Morto`0`n"
		,"`2`bStart of round:`b`n"=>"`2`bInizio del round:`b`n"
		,array(
			"Soulpoints"=>"Punti anima"
			,"`2's "=>"`2 ha "
			,"`2YOUR "=>"`2TU HAI "
			)

		,"is hit for `^"=>"subisce `^"
		,"'s skill allows them to get the first round of attack!"=>"`\$ è tanto abile da attaccare per primo!`0`b`n`n"
		,"`\$ surprises you and gets the first round of attack!`0`b`n`n"=>"`\$ ti prende di sorpresa e attacca per primo!`0`b`n`n"
		,"`b`\$Your skill allows you to get the first attack!`b`n`n"=>"`b`\$La tua abilità ti consente di attaccare per primo!`b`n`n"
		,"`&`bYou execute a <font size='+1'>MEGA</font> power move!!!`b`n"=>"`&`bHai usato una <font size='+1'>MEGA</font> mossa speciale!!!`b`n"
		,"`&`bYou execute a DOUBLE power move!!!`b`n"=>"`&`bHai usato una DOPPIA mossa speciale!!!`b`n"
		,"`&`bYou execute a power move!!!`b`0`n"=>"`&`bHai usato una mossa speciale!!!`b`0`n"
		,"`7`bYou execute a minor power move!`b`0`n"=>"`7`bHai usato una piccola mossa speciale!`b`0`n"
		,array(
			"`4You try to hit `^"=>"`4Cerchi di colpire `^"
			,"`) damage.`n"=>" punti di danno.`n"
			," but `\$MISSES`)!`n"=>" ma `\$MANCA`)!`n"
			,"`4 but `\$MISS!`n"=>"`4 ma `\$MANCHI!`n"
			,"`4 but are `\$RIPOSTED `4for `\$"=>"`4 ma `\$CONTRATTACCA e ti causa `\$"
			,"`4You hit `^"=>"`4Colpisci `^"
			,"`4 is hit for `^"=>"`4 subisce `^"
			,"`4 hits you for `\$"=>"`4 ti colpisce causando `\$"
			,"`4 tries to hit you but you `^RIPOSTE`4 for `^"=>"`4 cerca di colpirti ma tu `^CONTRATTACCHI`4 e gli causi `^"
			,"`4 for `^"=>"`4 causando `^"
			,"`4 points of damage!`n"=>"`4 punti di danno!`n"
			)
		,"`4You are too busy trying to run away like a cowardly dog to try to fight"=>"`4Sei troppo impegnato a tentare di scappare come un coniglio per combattere"
		,"`4 tries to hit you but `\$MISSES!`n"=>"`4 cerca di colpirti ma `\$MANCA!`n"
		,"`2`bEnd of Round:`b`n"=>"`2`bFine del Round:`b`n"


		);
		break;

	case "healer.php":
		$replace = array(
		"H?Healer's Hut"=>"Guaritore"
		,"Healer's Hut"=>"Guaritore"
		,"L?Look for Something to kill"=>"Trova qualcosa da uccidere"
		,"S?Go Slumming"=>"Visita i bassifondi"
		,"T?Go Thrillseeking"=>"Vai in cerca di brividi"
		,"Take horse to Dark Horse Tavern"=>"Vai alla Taverna del Cavallo Nero"
		,"Return to the Village"=>"Torna al villaggio"
		,"Seek out the Green Dragon"=>"Cerca il Drago Verde"
		,"Other"=>"Altro"
		,"The Outhouse"=>"I Bagni Pubblici"
		,"`#`b`cHealer's Hut`c`b`n"=>"`#`b`cGuaritore`c`b`n"
		,"`3A very petite and beautiful brunette looks up as you enter.  \"`6Ahh.. You must be "=>"`3Una piccola e bella brunetta ti guarda mentre entri \"`6Ahh.. Tu devi essere "
		,"`6  I was told to expect you.  Come in.. come in!`3\" she exclaims.`n`nYou make your way deeper into the hut.`n`n"=>"`6 Mi è stato detto di aspettarti. Entra.. entra!`3\" esclama.`n`nTi fai strada verso l'interno della capanna.`n`n"
		,"`3You duck in to the small smoke filled grass hut.  The pungent aroma makes you cough, attracting the attention of a grizzled old person that does a remarkable job of reminding you of a rock, which probably explains why you didn't notice them until now.  Couldn't be your failure as a warrior.  Nope, definitely not.`n`n"=>"`3Entri nella piccola capanna di frasche piena di fumo. L'aroma pungente ti fa tossire, attraendo l'attenzione di un'anziana persona brizzolata che si impegna molto a ricordarti una pietra, il che probabilmente spiega perché non l'hai notata prima. Di certo non può essere una tua mancanza da guerriero quale sei. Decisamente no.`n`n"
		,array(
			"`3\"`6Now.. Let's see here.  Hmmm. Hmmm. You're a bit banged up it seems.`3\"`n`n\"`5Uh, yeah.  I guess.  What will this cost me?`3\" you ask, looking sheepish. \"`5I don't normally get this hurt you know.`3\"`n`n\"`6I know.  I know.  None of you `^ever`6 does.  Anyhow, I can set you right as rain for `$`b"=>"`3\"`6Ora... Vediamo un po'. Hmmm. Hmmm. Sembra che ti abbiano un po' ammaccato.`3\"`n`n\"`5Uh, si. Credo. Quanto mi costerà?`3\" domandi con un'aria da ebete. \"`5Solitamente non mi faccio così male, sai.`3\"`n`n\"`6Lo so. Lo so. Nessuno di voi lo fa `^mai`6. Comunque, posso rimetterti in sella per `$`b"
			,"`b`6 gold pieces.  I can also give you partial doses at a lower price if you cannot afford a full potion,`3\" says Golinda, smiling."=>"`b`6 pezzi d'oro. Posso anche darti delle dosi ridotte per un prezzo minore se non ti puoi permettere una pozione intera,`3\" dice Golinda, sorridendo."
			)
		,array(
			"\"`6See you, I do.  Before you did see me, I think, hmm?`3\" the old thing remarks.  \"`6Know you, I do; healing you seek.  Willing to heal am I, but only if willing to pay are you.`3\"`n`n\"`5Uh, um.  How much?`3\" you ask, ready to be rid of the smelly old thing.`n`nThe old being thumps your ribs with a gnarly staff.  \"`6For you... `$`b"=>"\"`6Vedo, io ti.  Prima che tu vedi me, io penso, hmm?`3\" dice il vecchio coso.  \"`6Conosco, io ti; cure tu cerchi. Curare voglio io, ma solo se pagare vuoi tu.`3\"`n`n\"`5Uh, um.  Quanto?`3\" chiedi, pronto a liberarti di quel vecchio coso puzzolente. `n`nL'anziano essere ti picchia nelle costole con un bastone tarlato.  \"`6Per te... `$`b"
		,"`b`6 gold pieces for a complete heal!!`3\" it says as it bends over and pulls a clay vial from behind a pile of skulls sitting in the corner.  The view of the thing bending over to remove the vial almost does enough mental damage to require a larger potion.  \"`6I also have some, erm... 'bargain' potions available,`3\" it says as it gestures at a pile of dusty, cracked vials.  \"`6They'll heal a certain percent of your `idamage`i.`3\""=>"`b`6 monete per una guarigione completa!!`3\" dice mentre si piega ed estrae una fialetta di argilla da dietro una pila di teschi che si trova in un angolo. La vista della cosa che si piega in avanti per prendere la fiala ti fa quasi abbastanza danni mentali da farti avere bisogno di una pozione più potente.  \"`6Ho anche alcune, ehm... pozioni 'in saldo' disponibili,`3\" dice facendo cenni in direzione di una pila di fiale crepate e polverose.  \"`6Cureranno una certa percentuale delle tue `iferite`i.`3\""
		)
		,"Potions"=>"Pozioni"
		,"`^Complete Healing`0"=>"`^Guarigione Completa`0"
		,"`3Golinda looks you over carefully.  \"`6Well, you do have that hangnail there, but other than that, you seem in perfect health! `^I`6 think you just came in here because you were lonely,`3\" she chuckles.`n`nRealizing that she is right, and that you are keeping her from other patients, you wander back out to the forest."=>"`3Golinda ti guarda attentamente.  \"`6Beh, hai un calletto lì, ma a parte quello sembri in ottima salute! `^Io`6 penso che tus ia venuto qui solo perché ti senti solo,`3\" ridacchia.`n`nComprendendo che ha ragione, e che la stai trattenendo dal curare altri pazienti, torni a vagare nella foresta."
		,"`3The old creature grunts as it looks your way. \"`6Need a potion, you do not.  Wonder why you bother me, I do.`3\" says the hideous thing.  The aroma of its breath makes you wish you hadn't come in here in the first place.  You think you had best leave."=>"`3La vecchia creatura grugnisce guardando verso di te. \"`6Bisogno di una pozione, tu non hai.  Perché tu disturbi me, domando io.`3\" dice lo strano coso. L'aroma del suo respiro ti fa desiderare di non essere mai venuto qui. Pensi che faresti meglio ad andartene."
		,array(
			"`3Golinda looks you over carefully.  \"`6My, my! You don't even have a hangnail for me to fix!  You are a perfect speciman of "=>"`3Golinda ti guarda attentamente. \"`6Cielo, cielo! Non hai neppure un calletto da farti curare! Sei un perfetto esemplare di "
			,"!  Do come back if you get hurt, please,`3\" she says, turning back to her potion mixing.`n`n\"`6I will,`3\"you stammer, unaccountably embarrased as you head back out to the forest."=>"!  Torna quando sarai stato ferito, per favore,`3\" dice, voltandosi per riprendere a miscelare pozioni.`n`n\"`6Certo,`3\"borbotti, incredibilmente inbarazzato, mentre torni nella foresta."
			,"manhood"=>"mascolinità"
			,"womanhood"=>"femminilità"
			)
		,"`3The old creature glances at you, then in a `^whirlwind of movement`3 that catches you completely off guard, brings its gnarled staff squarely in contact with the back of your head.  You gasp as you collapse to the ground.`n`nSlowly you open your eyes and realize the beast is emptying the last drops of a clay vial down your throat.`n`n\"`6No charge for that potion.`3\" is all it has to say.  You feel a strong urge to leave as quickly as you can."=>"`3La vecchia creatura ti guarda, poi in un `^turbinio di movimenti`3 che ti prende  completamente alla sprovvista, porta la sua staffa tarlata in contatto con il retro della tua testa. Ansimi mentre crolli sul pavimento.`n`nLentamente apri gli occhi e ti rendi conto che la bestia sta svuotando le ultime gocce di una fiala di argilla nella tua gola.`n`n\"`6Questa pozione non si paga.`3\" è tutto ciò che dice. Senti una forte urgenza di andartene il più velocemente possibile."
		,"`3Expecting a foul concoction you begin to up-end the potion.  As it slides down your throat however, you taste cinnamon, honey, and a fruit flavor.  You feel warmth spread throughout your body as your muscles knit themselves back together.  Clear-headed and feeling much better, you hand Golinda the gold you owe and head back to the forest."=>"`3Aspettandoti un'orrenda mistura, inizi a bere la poszione. Mentre ti scende nella gola, però, senti un gusto di cinnamomo, miele e frutta. Senti il calore spandersi nel tuo corpo mentre i tuoi muscoli si rinsaldano. Sentendoti molto meglio, dai a Golinda le monete che le devi e torni nella foresta."
		,"`3With a grimace, you up-end the potion the creature hands you, and despite the foul flavor, you feel a warmth spreading through your veins as your muscles knit back together.  Staggering some, you hand it your gold and are ready to be out of here."=>"`3Con una smorfia, ti scoli la pozione che la creatura ti consegna, e nonostante l'orribile sapore senti un calore che ti si diffonde nelle vene mentre i tuoi muscoli si ristorano. Ballonzolando un po', consegni il pagamento e sei pronto ad andartene."
		,array(
			"`n`n`#You have been healed for "=>"`n`n`#Sei stato curato per "
			," points!"=>" punti!"
		)
		,array(
			"`3\"`6Tsk, tsk!`3\" Golinda murmers.  \"`6Maybe you should go visit the Bank and return when you have `b`\$"=>"`3\"`6Tsk, tsk!`3\" mormora Golinda.  \"`6Forse dovresti fare un salto in banca e tornare quando avrai `b`\$"
		,"`6`b gold?`3\" she asks.`n`nYou stand there feeling sheepish for having wasted her time.`n`n\"`6Or maybe a cheaper potion would suit you better?`3\" she suggests kindly."=>"`6`b monete?`3\" domanda.`n`nStai lì sentendoti stupido per averle fatto perdere tempo.`n`n\"`6O forse sarebbe il caso di prendere una pozione meno costosa?`3\" suggerisce lei gentilmente."
			)
		,array(
			"`3The old creature pierces you with a gaze hard and cruel.  Your lightning quick reflexes enable you to dodge the blow from its gnarled staff.  Perhaps you should get some more money before you attempt to engage in local commerce.`n`nYou recall that the creature had asked for `b`\$"=>"`3La vecchia creatura ti fulmina con uno sguardo crudele. I tuoi riflessi fulminei ti permettono di schivare il colpo del suo bastone tarlato. Forse dovresti procurarti un po' più denaro prima di tentare di fare affari.`n`nTi ricordi che la creatura aveva chiesto `b`\$"
			,"`3`b gold."=>"`3`b monete."
			)
		,"`bReturn`b"=>"`bIndietro`b"
		,"Back to the forest"=>"Torna alla foresta"
		,"Back to the village"=>"Torna al villaggio"
		);
		break;
	case "hof.php":
		$replace = array(
		"`c`b`&Heroes of the realm`b`c"=>"`c`b`&Eroi del reame`b`c"
		,"<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bName`b</td><td>`bKills`b</td></tr>"=>"<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bNome`b</td><td>`bUccisioni`b</td></tr>"
		,"<tr><td colspan=2 align='center'>`&There are no heroes in the land`0</td></tr>"=>"<tr><td colspan=2 align='center'>`&Non ci sono eroi in queste terre`0</td></tr>"
		,"Return to the village"=>"Torna al villaggio"
		);
		break;

	case "inn.php":
		$replace = array(
		"You have been slain!"=>"Sei stato ucciso!"
		,array(
			"`\$You were slain in "=>"`\$Sei stato ucciso nella "
			,"`\$ by `%"=>"`\$ da `%"
			,"`\$.  They cost you 5% of your experience, and took any gold you had.  Don't you think it's time for some revenge?"=>"`\$.  Ti è costato il 5% della tua esperienza, e tutto l'oro che avevi con te. Non credi che sia il caso di vendicarsi?"
			)
		,"Continue"=>"Continua"
		,"`c`bThe Boar's Head Inn`b`c"=>"`c`bLocanda \"alla Testa del Cinghiale\"`b`c"
		,"You stroll down the stairs of the inn, once again ready for adventure!  "=>"Discendi le scale della locanda, pronto per nuove avventure!  "
		,"You duck in to a dim tavern that you know well.  The pungent aroma of pipe tobacco fills "=>"Entri in a una taverna fumosa che conosci bene. L'aroma pungente di tabacco di pipa riempie "
		,"the air."=>"l'aria."
		,array(
			" You wave to several patrons that you know, and wink at "=>"Saluti diversi clienti che conosci, e strizzi l'occhio a "
			,"`^Seth`0 who is tuning his harp by the fire."=>"`^Seth`0 che sta accordando la sua arpa accanto al fuoco."
			,"`5Violet`0 who is serving ale to some locals."=>"`5Violet`0 che sta servendo birra ai tavoli."
			," Cedrik the innkeep stands behind his counter, chatting with someone.  You can't quite"=>" Cedrik l'oste sta dietro il suo bancone e chiacchiera con un avventore. Non riesci a"
			," make out what he is saying, but it's something about "=>" sentire quello che dice, ma ti sembra di capire che parli di "
			)
		,"dragons."=>"draghi."
		,"fine ales"=>"buona birra."
		," Dag Durnick sits, sulking in the corner with a pipe clamped firmly in his mouth. "=>" Dag Durnick sta seduto in un angolo con una pipa serrata tra le labbra. "
		,"`n`nThe clock on the mantle reads `6"=>"`n`nL'orologio sulla parete segna le `6"
		,"Things to do"=>"Cose da fare"
		,"Flirt with Violet"=>"Corteggia Violet"
		,"Chat with Violet"=>"Chiacchiera con Violet"
		,"Talk to Seth the Bard"=>"Parla con il bardo Seth"
		,"Converse with patrons"=>"Parla con altri clienti"
		,"B?Talk to Cedrik the Barkeep"=>"Parla con l'oste Cedrik"
		,"Talk to Dag Durnick"=>"Parla con Dag Durnick"
		,"Other"=>"Altro"
		,"Get a room (log out)"=>"Affitta una camera (ESCI)"
		,"Return to the village"=>"Torna al villaggio"
		,"Gossip"=>"Spettegola"
		,array(
			"Ask if your "=>"Chiedi se il tuo "
			," makes you look fat"=>" ti fa sembrare grassa"
			)
		,"You go over to `5Violet`0 and help her with the ales she is carrying.  Once they are passed out, "=>"Raggiungi `5Violet`0 e la aiuti con le birre che sta portando. Una volta finito, "
		,"she takes a cloth and wipes the sweat off of her brow, thanking you much.  Of course you didn't "=>"lei si asciuga il sudore dalla fronte con un panno e ti ringrazia moltissimo. Ovviamente a te "
		,"mind, as she is one of your oldest and truest friends!"=>"non è pesato, visto che lei è una delle tue più care amiche!"
		,"You and `5Violet`0 gossip quietly for a few minutes about not much at all.  She offers you a pickle.  "=>"Tu e `5Violet`0 spettegolate tranquillamente per qualche minuto senza un argomento in particolare. Lei ti offre un sottaceto.  "
		,"You accept, knowing that it's in her nature to do so as a former pickle wench.  After a few minutes, "=>"Tu accetti sapendo che farlo è nella sua natura di ex ragazza dei sottaceti. Dopo qualche minuto, "
		,"Cedrik begins to cast burning looks your way, and you decide you had best let Violet get back to work."=>"Cedrik inizia a mandare delle occhiatacce nella tua direzione, e decidi che sarebbe meglio lasciar tornare Violet al lavoro."
		,"Violet looks you up and down very seriously.  Only a friend can be truly honest, and that is why you "=>"Violet ti scruta attentamente dall'alto in basso con aria molto seria. Solo un'amica può essere veramente onesta, ed è per questo che "
		,"asked her.  Finally she reaches a conclusion and states, \"`%"=>"lo hai chiesto a lei. Infine raggiunge una conclusione ed afferma, \"`%"
		,"Your outfit doesn't leave much to the imagination, but some things are best not thought about at all!  Get some less revealing clothes as a public service!"=>"I tuoi abiti non lasciano molto all'immaginazione, ma ci sono cose a cui è meglio non pensare affatto. Prenditi qualcosa di meno rivelatore, vedilo come un sevizio al pubblico!"
		,"I've seen some lovely ladies in my day, but I'm afraid you aren't one of them."=>"Ho visto delle donne adorabili nella mia vita, ma temo che tu non sia una di queste."
		,"I've seen worse my friend, but only trailing a horse."=>"Ho visto di peggio amica mia, ma solo attaccato ad un cavallo."
		,"You're of fairly average appearance my friend."=>"Hai un aspetto abbastanza medio amica mia."
		,"You certainly are something to look at, just don't get too big of a head about it, eh?"=>"Vale davvero la pena di guardarti, solo non montarti troppo la testa adesso, eh?"
		,"You're quite a bit better than average!"=>"Sei un bel po' sopra la media!"
		,"Few women could count themselves to be in competition with you!"=>"Poche donne potrebbero reggere il confronto con te!"
		,"I hate you, why, you are simply the most beautiful woman ever!"=>"Ti odio, sei la donna più bella che abbia mai visto!"
		,"You stare dreamily across the room at `5Violet`0, who leans across a table "=>"Guardi trasognato verso `5Violet`0, dall'altra parte della stanza, che si piega su un tavolo "
		,"to serve a patron a drink.  In doing so, she shows perhaps a bit more skin "=>"per servire da bere ad un cliente. Nel farlo, mostra forse un po' più di pelle "
		,"than is necessary, but you don't feel the need to object."=>"del necessario, ma non ti sembra il caso di obiettare."
		,"Wink"=>"Strizza l'occhio"
		,"Kiss her hand"=>"Baciale la mano"
		,"Peck her on the lips"=>"Sfiorale le labbra"
		,"Sit her on your lap"=>"Prendila in grembo"
		,"Grab her backside"=>"Toccale il didietro"
		,"Carry her upstairs"=>"Portala in camera"
		,"Marry her"=>"Sposala"
		,"You wink at `5Violet`0, and she gives you a warm smile in return."=>"Strizzi l'occhio a `5Violet`0, e lei ti fa un caldo sorriso in cambio."
		,"You wink at `5Violet`0, but she pretends not to notice."=>"Strizzi l'occhio a `5Violet`0, ma lei fa finta di non accorgersene."
		,"You stroll confidently across the room toward `5Violet`0.  Taking hold of her "=>"Attraversi baldanzoso la stanza andando verso `5Violet`0.  Le prendi la "
		,"hand, you kiss it gently, your lips remaining for only a few seconds.  `5Violet`0 "=>"mano e gliela baci gentilmente, le tue labbra si soffermano solo per pochi secondi. `5Violet`0 "
		,"blushes and tucks a strand of hair behind her ear as you walk away, then presses "=>"arrossisce e si mette una ciocca di capelli dietro l'orecchio mentre ti allontani, poi preme "
		,"the back side of her hand longingly against her cheek while watching your retreat."=>"il dorso della mano contro la sua guancia mentre ti guarda allontanarti."
		,"You stroll confidently across the room toward `5Violet`0, and grab at her hand.  "=>"Attraversi baldanzoso la stanza andando verso `5Violet`0 e le prendi la mano."
		,"`n`nBut `5Violet`0 takes her hand back and asks if perhaps you'd like an ale."=>"`n`nMa `5Violet`0 se la riprende e ti domanda se per caso vorresti una birra."
		,"Standing with your back against a wooden column, you wait for `5Violet`0 to wander "=>"Dando le spalle ad una colonna di legno, attendi che `5Violet`0 capiti "
		,"your way when you call her name.  She approaches, a hint of a smile on her face.  "=>"da quelle parti e la chiami per nome. Lei si avvicina, con un principio di sorriso sul volto.  "
		,"You grab her chin, lift it slightly, and place a firm but quick kiss on her plump "=>"Le prendi il mento, glielo sollevi appena, ele dai un bacio risoluto ma rapido sulle sue "
		,"lips."=>"morbide labbra."
		,"Standing with your back against a wooden column, you wait for `5Violet`0 to wander "=>"Dando le spalle ad una colonna di legno, attendi che `5Violet`0 capiti "
		,"your way when you call her name.  She smiles and apologizes, insisting that she is "=>"da quelle parti e la chiami per nome. Lei sorride e si scusa ripetendo che è troppo occupata "
		,"simply too busy to take a moment from her work."=>"per poter interrompere quello che sta facendo."
		,"Sitting at a table, you wait for `5Violet`0 to come your way.  When she does so, you "=>"Seduto ad un tavolo, aspetti che `5Violet`0 passi da te. Quando lo fa, "
		,"reach up and grab her firmly by the waist, pulling her down on to your lap.  She laughs "=>"ti allunghi e la afferri saldamente per la vita, facendotela sedere in grembo. Lei ride "
		,"and throws her arms around your neck in a warm hug before thumping you on the chest, "=>"e ti mette le braccia attorno al collo in un caldo abbraccio prima di colpirti sul petto, "
		,"standing up, and insisting that she really must get back to work."=>"alzarsi e dirti che deve per forza tornare al lavoro."
		,"Sitting at a table, you wait for `5Violet`0 to come your way.  When she does so, you "=>"Seduto ad un tavolo, aspetti che `5Violet`0 passi da te. Quando lo fa, "
		,"reach up to grab her by the waist, but she deftly dodges, careful not to spill the "=>"ti allunghi per afferrarla alla vita, ma lei ti schiva con destrezza, attenta a non versare la "
		,"ale that she's carrying."=>"birra che sta portando."
		,"Waiting for `5Violet`0 to brush by you, you firmly palm her backside.  She turns and "=>"Aspetti che `5Violet`0 ti passi vicino e le tasti il didietro. Lei si volta e "
		,"gives you a warm, knowing smile."=>"ti fa un caldo sorriso complice."
		,"Waiting for `5Violet`0 to brush by you, you firmly palm her backside.  She turns and "=>"Aspetti che `5Violet`0 ti passi vicino e le tasti il didietro. Lei si volta e "
		,"slaps you across the face.  Hard.  Perhaps you should go a little slower."=>"ti da un ceffone in faccia. Forte. Forse dovresti andarci un po' più piano"
		,"Like a whirlwind, you sweep through the inn, grabbing `5Violet`0, who throws her arms "=>"Come un tornado, attraversi la locanda, afferrando `5Violet`0, che ti mette le braccia "
		,"around your neck, and whisk her upstairs to her room there.  Not more than 10 minutes later "=>"al collo, e la porti nella sua stanza al piano di sopra. Non più di 10 minuti dopo "
		,"you stroll down the stairs, smoking a pipe, and grinning from ear to ear.  "=>"ridiscendi le scale, con una pipa in bocca ed un sorriso da un orecchio all'altro.  "
		,"You feel exhausted, and couldn't possibly face another forest fight today.  "=>"Ti senti esausto e non riusciresti ad affrontare un altro combattimento nella foresta per oggi.  "
		," and `5Violet`@ were seen heading up the stairs in the inn together."=>" e `5Violet`@ sono stati visti salire le scale della locanda insieme."
		,"Like a whirlwind, you sweep through the inn, and grab for `5Violet`0.  She turns and "=>"Come un tornado, attraversi la locanda e tenti di afferrare `5Violet`0. Lei si volta e "
		,"slaps your face!  \"`%What sort of girl do you think I am, anyhow?`0\" she demands! "=>"ti da una sberla!  \"`%Che genere di ragazza pensi che sia?`0\" ti domanda! "
		,"`5Violet`0 is working feverishly to serve patrons of the inn.  You stroll up to her "=>"`5Violet`0 sta lavorando febbrilmente per servire i clienti della locanda. La raggiungi e "
		,"and take the mugs out of her hand, placing them on a nearby table.  Admidst her protests "=>"le togli i boccali dalle mani, mettendoli su un tavolo vicino. Tra le sue proteste "
		,"you kneel down on one knee, taking her hand in yours.  She quiets as you stare up at her "=>"ti pieghi su un ginocchio, prendendole la mano. Lei si calma mentre alzi lo sguardo verso di lei "
		,"and utter the question that you never thought you'd utter.  She stares at you and you "=>"e fai la domanda che non avresti mai pensato di fare. Lei ti fissa e tu "
		,"immediately know the answer by the look on her face. "=>"capisci subito la risposta dall'espressione del suo volto. "
		,"`n`nIt is a look of exceeding happiness.  \"`%Yes!`0\" she says, \"`%Yes, yes yes!!!`0\""=>"`n`nÈ un'espressione di gioia immensa.  \"`%Sì!`0\" dice, \"`%Sì, sì sì!!!`0\""
		,"  Her final confirmations are burried in a flurry of kisses about your face and neck. "=>"  Le sue ultime conferme sono seppellite in una cascata di baci sulla tua faccia ed il tuo collo. "
		,"`n`nThe next days are a blur; you and `5Violet`0 are married in the abbey down the street, "=>"`n`nI giorni successivi fuggono; tu e `5Violet`0 vi sposate nell'abbazia in fondo alla strada, "
		,"in a gorgeous ceremony with many frilly girly things."=>"in una splendica cerimonia con un mucchio di frivole cosette femminili."
		," and `%Violet`& are joined today in joyous matrimony!!!"=>" e `%Violet`& si sono gioiosamente uniti oggi in matrimonio!!!"
		,"`n`nIt is a look of sadness.  \"`%No`0,\" she says, \"`%I'm not yet ready to settle down`0.\""=>"`n`nÈ un'espressione di tristezza.  \"`%No`0,\" dice, \"`%non sono ancora pronta a mettere su famiglia`0.\""
		,"`n`nDisheartened, you no longer possess the will to pursue any more forest adventures today."=>"`n`nScoraggiato, non hai più voglia di avventurarti nella foresta per oggi."
		,"`n`n`^You gain a charm point!"=>"`n`n`^Guadagni un punto di fascino!"
		,"`n`n`\$You LOSE a charm point!"=>"`n`n`\$PERDI un punto di fascino!"
		,"You think you had better not push your luck with `5Violet`0 today."=>"Pensi che sia meglio non tentare troppo la fortuna con `5Violet`0 per oggi."
		,"Seth looks at you expectantly."=>"Seth ti guarda incuriosito."
		,"Ask Seth to entertain"=>"Chiedi a Seth di intrattenere"
		,"Flirt"=>"Corteggia"
		,"Wink"=>"Strizza l'occhio"
		,"Flutter Eyelashes"=>"Sbatti le ciglia"
		,"Drop Hankey"=>"Fai cadere il fazzoletto"
		,"Ask him to buy you a drink"=>"Chiedigli di offrirti da bere"
		,"Kiss him soundly"=>"Bacialo"
		,"Completely seduce the bard"=>"Seducilo totalmente"
		,"Marry him"=>"Sposalo"
		,"Ask Seth how he likes your new "=>"Chiedi a Seth se gli piace il tuo nuovo "
		,"Seth looks you up and down very seriously.  Only a friend can be truly honest, and that is why you "=>"Seth ti scruta dall'alto in basso con aria seria. Solo un amico può essere veramente onesto, ed è per questo che  "
		,"asked him.  Finally he reaches a conclusion and states, \"`%"=>"hai chiesto a lui. Infine raggiunge una conclusione e ti dice, \"`%"
		,"You make me glad I'm not gay!"=>"Mi rendi felice di non essere gay!"
		,"I've seen some handsome men in my day, but I'm afraid you aren't one of them."=>"Ho visto dei begli uomini ai miei tempi, ma temo che tu non sia fra questi."
		,"I've seen worse my friend, but only trailing a horse."=>"Ho visto di peggio amico mio, ma era attaccato ad un cavallo."
		,"You're of fairly average appearance my friend."=>"Hai un aspetto alquanto normale amico mio."
		,"You certainly are something to look at, just don't get too big of a head about it, eh?"=>"Sei di certo qualcuno che vale la pena guardare, ma adesso non ti montare la testa, eh?"
		,"You're quite a bit better than average!"=>"Sei decisamente sopra la media!"
		,"Few women would be able to resist you!"=>"Poche donne potrebbero resisterti!"
		,"I hate you, why, you are simply the most handsome man ever!"=>"Ti odio, sei l'uomo più bello che si sia mai visto!"
		,"Seth clears his throat and drinks some water.  \"I'm sorry, my throat is just too dry.\""=>"Seth si schiarisce la gola e beve un po' d'acqua. \"Mi spiace, ho la gola troppo secca.\""
		,"Return to the inn"=>"Torna alla locanda"
		,"Seth clears his throat and begins:`n`n`^"=>"Seth si schiarisce la gola e comincia:`n`n`^"
		,"`@Green Dragon`^ is green. `n`@Green Dragon`^ is fierce. `nI fancy for `na `@Green Dragon`^ to pierce. "=>"`@Il Drago Verde`^ è verde. `n`@Il Drago Verde`^ è il re della foresta. `nSogno `nun `@Drago Verde`^ a cui tagliar la testa. "
		,"`n`n`0You gain TWO forest fights for today!"=>"`n`n`0Guadagni DUE combattimenti nella foresta per oggi!"
		,"Mireraband I scoff at thee and tickeleth your toes. `nFor they smell most foul and seethe a stench far greater than you know! "=>"Mireraband io ti derido e ti solletico i ditoni.`nPerchè mandano più puzza di una mandria di caproni! "
		,"`n`n`0You feel jovial, and gain an extra forest fight."=>"`n`n`0Ti senti allegro e guadagni un combattimento nella foresta."
		,array(
			"Membrain Man, Membrain Man. `nMembrain man hates "=>"Uomo Membrana, Uomo Membrana. `nUomo Membrana odia "
			,"`^ man. `nThey have a fight, Membrain wins. `nMembrain Man. "=>"`^. `nCombattono, vince Uomo Membrana. `nUomo Membrana. "
			)
		,"`n`n`0You're not quite sure what to make of this... you merely back away, and think you'll visit Seth when he's feeling better.  Having"=>"`n`n`0Non sei del tutto certo di cosa pensare... ti limiti ad andare via, e decidi che farai di nuovo visita a Seth quando starà meglio. Essendoti "
		,"rested a while though, you think you could face another badguy."=>"riposato per un po', però, pensi di poter afrontare un altro cattivone."
		,"Gather 'round and I'll tell you a tale `nmost terrible and dark `nof Cedrik and his unclean beer `nand how he hates this bard! "=>"Riunitevi e vi racconterò una storia `nterribile ed oscura `nsu Cedrik e la sua birra sporca `ne su quanto odia questo bardo! "
		,"`n`n`0You realize he's right, Cedrik's beer is really nasty.  That's why most patrons prefer his ale.  Though you don't really gain anything from the tale from Seth, you do happen to notice a few gold on the ground!"=>"`n`n`0Ti rendi conto che ha ragione, la birra di Cedrik è davvero cattiva. Anche se in effetti non guadagni nulla dalla storia di Seth, ti capita di trovare alcune monete sul pavimento!"
		,"So a priate goes in to a bar with a steering wheel in his pants. `nThe bartender says, \"You know you have a steering wheel in your pants.\" `nThe pirate replies, \"Yaaarr, 'tis drivin' me nuts!\" "=>"Un pirata entra in un bar con una ruota del timone nei pantaloni. `nIl barista dice, \"Sai che hai una ruota del timone nei pantaloni?\" `nIl pirata risponde, \"Yaaarr, me le sta facendo girare!\" "
		,"`n`n`0With a good hearty chuckle in your soul, you advance on the world, ready for anything!"=>"`n`n`0Reprimendo un rislino, esci nel mondo, pronto ad affrontare qualunque cosa!"
		,"Listen close and hear me well:  every second we draw even closer to death.  *wink*"=>"Avvicinati ed ascolta attentamente: ogni secondo che passa ci avvicina alla morte."
		,"`n`n`0Depressed, you head for home... and lose a forest fight!"=>"`n`n`0Depresso, ti dirigi verso casa... e perdi un combattimento nella foresta!"
		,"I love MightyE, MightyE weaponry, I love MightyE, MightyE weaponry, I love MightyE, MightyE weaponry, nothing kills as good as MightyE... WEAPONRY!"=>"Amo MightyE, le armi di MightyE, amo MightyE, le armi di MightyE, amo MightyE, le armi MightyE, niente uccide bene quanto... LE ARMI DI MightyE!"
		,"`n`n`0You think Seth is quite correct... you want to go out and kill something.  You leave and think about bees and fish for some reason."=>"`n`n`0Pensi che Seth abbia ragione... vuoi uscire ed uccidere qualcosa. Te ne vai e per qualche ragione ti viene di pensare ad api e pesci."
		,"`0Seth seems to sit up and prepare himself for something impressive. He then burps loudly in your face.  \"`^Was that entertaining enough?`0\""=>"`0Seth sembra sedersi e prepararsi per qualcosa di notevole. Poi ti rutta rumorosamente in faccia.  \"`^Com'era come intrattenimento?`0\""
		,"`n`n`0The smell is overwhelming, you feel a little ill and lose some hitpoints."=>"`n`n`0L'odore è devastante, non ti senti troppo bene e perdi alcuni punti ferita."
		,"`0\"`^What is the sound of one hand clapping?`0\" asks Seth.  While you ponder this connundrum, Seth \"liberates\" a small entertainment fee from your purse."=>"`0\"`^Qual è il suono di una mano che applaude?`0\" chiede Seth. Mentre ponderi su questo enigma, Seth \"libera\" una piccola tassa di intrattenimento dal tuo borsellino."
		,"`n`nYou lose 5 gold!"=>"`n`nPerdi 5 monete!"
		,"  Well, you would have if you had had enough gold.  Seth only got away with a few coins."=>"  Beh, le avresti perse se le avessi avute. Seth porta via quello che gli riesce di trovare."
		,"What do you call a fish with no eyes?`n`nA fsshh."=>"Cosa sono i vulcani?`n`nDelle vulbestie che fanno vulbau vulbau."
		,"`n`nYou groan as Seth laughs heartily.  Shaking your head, you notice a gem in the dust."=>"`n`nFai un gemito mentre Seth si ammazza dal ridere. Scuotendo la testa, noti una gemma tra la polvere."
		,"Seth plays a soft but haunting melody."=>"Seth suona una melodia dolce ma inquietante."
		,"`n`nYou feel relaxed, and your wounds seem to fade away."=>"`n`nTi senti rilassato e le tue ferite sembrano dissolversi."
		,"Seth plays a melancholy dirge for you."=>"Seth suona per te una malinconica litania funebre."
		,"`n`nYou feel lower in spirits, you may not be able to face as many villians today."=>"`n`nTi senti depresso, potresti non essere in grado di affrontare molti nemici oggi."
		,"The ants go marching one by one, hoorah, hoorah.`nThe ants go marching one by one, hoorah, hoorah!`nThe ants go marching one by one and the littlest one stops to suck his thumb,`nand they all go marching down, to the ground, to get out of the rain...`nbum bum bum`nThe ants go marching two by two, hoorah, hoorah!...."=>"Le formiche marciano una ad una, urrà, urrà.`nLe formiche marciano una ad una, urrà, urrà!`nLe formiche marciano una ad una e la più piccola si ferma per succhiarsi il pollice,`ned esse marciano tutte, verso il terreno, per allontanarsi dalla pioggia...`nbum bum bum`nLe formiche marciano a due a due, urrà, urrà!...."
		,"`n`n`0Seth continues to sing, but not wishing to learn how high he can count, you quietly leave.`n`nHaving rested a while, you feel refreshed."=>"`n`n`0Seth continua a cantare, ma non volendo scoprire fino a quanto riesce a contare, te ne vai silenziosamente.`n`nEssendoti riposato un po', ti senti più fresco."
		,"There once was a lady from Venus, her body was shaped like a ..."=>"C'era una volta una donna di Venere, il suo corpo aveva la forma di ..."
		,"`n`n`0Seth is cut short by a curt slap across his face!  Feeling rowdy, you gain a forest fight."=>"`n`n`0Seth viene interrotto da un brusco ceffone in faccia! Sentendoti violenta, guadagni un combattimento nella foresta."
		,"`n`n`0Seth is cut short as you burst out in laughter, not even having to hear the end of the rhyme. Feeling inspired, you gain a forest fight."=>"`n`n`0Seth si interrompe quando scoppi a ridere senza nemmeno aver sentito la fine della rima. Sentendoti ispirato, guadagni un combattimento nella foresta."
		,"Seth plays a rousing call-to-battle that wakes the warrior spirit inside of you."=>"Seth suona un canto di battaglia che risveglia il tuo spirito guerriero."
		,"`n`n`0You gain a forest fight!"=>"`n`n`0Guadagni un combattimento nella foresta!"
		,"Seth seems preoccupied with your... eyes."=>"Seth sembra interessato ai tuoi... occhi."
		,"`n`n`0You receive one charm point!"=>"`n`n`0Guadagni un punto di fascino!"
		,"`n`n`0Furious, you stomp out of the bar!  You gain a forest fight in your fury."=>"`n`n`0Furioso, marci fuori della locanda! Nella tua furia guadagni un combattimento nella foresta."
		,"Seth begins to play, but a lute string snaps, striking you square in the eye.`n`n`0\"`^Whoops, careful, you'll shoot your eye out kid!`0\""=>"Seth inizia a suonare, ma una corda del liuto si spezza, colpendoti dritto nell'occhio.`n`n`0\"`^Ooops, attento, ci rimetterai un occhio così!`0\""
		,"`n`nYou lose some hitpoints!"=>"`n`nPerdi alcuni punti ferita!"
		,"Seth begins to play, but a rowdy patron stumbles past, spilling beer on you.  You miss the performance as you wipe the swill from your "=>"Seth inizia a suonare, ma un rozzo cliente ti passa davanti versandoti la sua birra addosso. Ti perdi la performance per ripulire dalla bevanda il tuo "
		,"`0Seth stares at you thoughtfully, obviously rapidly composing an epic poem...`n`n`^U-G-L-Y, You ain't got no aliby -- you ugly, yeah yeah, you ugly!"=>"`0Seth ti guarda pensieroso, ovviamente intento a comporre rapidamente un poema epico...`n`n`^B-R-U-T-T-O, Non hai nessun alibi --sei brutto, yeah yeah, sei brutto!"
		,"`n`n`0If you had any charm, you'd have been offended, instead, Seth breaks a lute string."=>"`n`n`0Se avessi avuto un minimo di fascino ti saresti offeso, invece una corda del liuto di Seth si rompe."
		,"`n`n`0Depressed, you lose a charm point."=>"`n`n`0Depresso, perdi un punto di fascino."
		,"Seth grins a big toothy grin.  My, isn't the dimple in his chin cute??"=>"Seth fa un ampio sorriso a 32 denti. Cielo, non è carina la fossetta nel suo mento??"
		,"Seth raises an eyebrow at you, and asks if you have something in your eye."=>"Seth inarca un sopracciglio e ti domanda se per caso hai qualcosa nell'occhio."
		,"Seth smiles at you and says, \"`^My, what pretty eyes you have`0\""=>"Seth ti sorride e dice, \"`^Cielo, che begli occhi che hai`0\""
		,"Seth smiles, and waves... to the person standing behind you."=>"Seth sorride e saluta con la mano... la persona dietro di te."
		,"Seth bends over and retrieves your hankey, while you admire his firm prosterior."=>"Seth si piega e recupera il tuo fazzoletto, mentre tu ammiri il suo sodo posteriore."
		,"Seth bends over and retrieves your hankey, wipes his nose with it, and gives it back."=>"Seth sipiega e recupera il tuo fazzoletto, si soffia il naso e te lo restituiscebends over and retrieves your hankey, wipes his nose with it, and gives it back."
		,"Seth places his arm around your waist, and escorts you to the bar where he buys you one of the Inn's fine swills."=>"Seth ti mette un braccio intorno alla vita e ti scorta fino al bancone dove ti offre una delle migliori brodaglie della locanda."
		,"Seth apologizes, \"`^I'm sorry m'lady, I have no money to spare,`0\" as he turns out his moth-riddled pocket."=>"Seth si scusa, \"`^Mi spiace milady, non ho soldi da spendere,`0\" mentre rovescia il suo borsellino mangiucchiato dalle tarme."
		,"You walk up to Seth, grab him by the shirt, pull him to his feet, and plant a firm, long kiss right on his handsome lips.  He collapses after, hair a bit disheveled, and short on breath."=>"Ti avvicini a Seth, lo afferri dalla maglia, lo fai alzare in piedi e gli dai un deciso, lungo bacio sulle labbra. Lui collassa subito dopo, con i capelli un po' disordinati e il respiro mozzato."
		,"You duck down to kiss Seth on the lips, but just as you do so, he bends over to tie his shoe."=>"Ti abbassi per baciare Seth sulle labbra, ma appena lo fai lui si piega per allacciarsi una scarpa."
		,"Standing at the base of the stairs, you make a come-hither gesture at Seth.  He follows you like a puppydog."=>"Stando alla base delle scale, fai cenno a Seth di avvicinarsi. Ti segue come un cagnolino."
		,"You feel exhausted, and couldn't possibly face another forest fight today.  "=>"Ti senti esausta e non puoi reggere un altro combattimento nella foresta per oggi. "
		," and `^Seth`@ were seen heading up the stairs in the inn together."=>" e `^Seth`@ sono stati visti salire insieme le scale della locanda."
		,"\"`^I'm sorry m'lady, but I have a show in 5 minutes`0\""=>"\"`^Mi spiace milady ma ho un'esibizione tra 5 minuti`0\""
		,"Walking up to Seth, you simply demand that he marry you.`n`nHe looks at you for a few seconds.`n`n"=>"Avvicinandoti a Seth, gli chiedi semplicemente di sposarti.`n`nLui ti guarda per qualche secondo.`n`n"
		,"\"`^Of course my love!`0\" he says.  The next weeks are a blur as you plan the most wonderous wedding, paid for entirely by Seth, and head on off to the deep forest for your honeymoon."=>"\"`^Certo amore!`0\" dice. Le settimane successive fuggono mentre perparate il meraviglioso matrimonio, pagato interamente da Seth, e vi dirigete verso il fitto della foresta per la luna di miele."
		," and `^Seth`& are joined today in joyous matrimony!!!"=>" e `^Seth`& oggi si sono gioiosamente uniti in matrimonio!!!"
		,"Seth says, \"`^I'm sorry, apparently I've given you the wrong impression, I think we should just be friends.`0\""=>"Seth dice, \"`^Mi spiace, apparentemente ti ho dato un'impressione sbagliata, penso che dovremmo essere solo buoni amici.`0\""
		,"`n`n`^You gain a charm point!"=>"`n`n`^Guadagni un punto di fascino!"
		,"`n`n`\$You LOSE a charm point!"=>"`n`n`\$PERDI un punto di fascino!"
		,"You think you had better not push your luck with `^Seth`0 today."=>"Pensi che sia meglio non sfidare oltre la fortuna con `^Seth`0 oggi."
		,"You stroll over to a table, place your foot up on the bench and listen in on the conversation:`n"=>"Ti avvicini ad un tavolo, metti i piedi sulla panca ed ascolti le conversazioni:`n"
		,"Add to the conversation?"=>"Aggiungi alla conversazione?"
		,"Cedrik looks at you sort-of sideways like.  He never was the sort who would trust a man any "=>"Cedrik ti guarda più o meno di sghimbescio. Non è mai stato il tipo che si fida di un uomo più di quanto possa lanciare, "
		,"farther than he could throw them, which gave dwarves a decided advantage, except in provinces "=>"cosa che avvantaggia alquanto i nani, tranne che nelle province in cui "
		,"where dwarf tossing was made illegal.  Cedrik polishes a glass, holds it up to the light of the door as "=>"il lancio del nano è stato dichiarato illegale. Cedrik lucida un bicchiere, lo mette in controluce verso la porta "
		,"another patron opens it to stagger out in to the street.  He then makes a face, spits on the glass "=>"mentre un altro cliente la apre per barcollare in strada. Fa una strana faccia, sputa sul bicchiere "
		,"and goes back to polishing it.  \"`%What d'ya want?`0\" he asks gruffly."=>"e ricomincia a pulirlo.  \"`%Cos'è che vuoi?`0\" domanda."
		,"Bribe"=>"Corrompi"
		,"Gems"=>"Gemme"
		,array(
			"Ale (`^"=>"Birra (`^"
			,"`0 gold)"=>"`0 monete)"
			)
		,array(
			"You now feel "=>"Ti senti "
			,"stone cold sober"=>"terribilmente sobrio"
			,"quite sober"=>"piuttosto sobrio"
			,"barely buzzed"=>"leggermente brillo"
			,"pleasantly buzzed"=>"alticcio"
			,"almost drunk"=>"quasi ubriaco"
			,"barely drunk"=>"leggermente ubriaco"
			,"solidly drunk"=>"ubriaco"
			,"sloshed"=>"molto ubriaco"
			,"hammered"=>"sbronzo"
			,"really hammered"=>"completamente sbronzo"
			,"almost unconscious"=>"quasi privo di sensi"
			)
		,"\"`%You have gems, do ya?`0\" Cedrik asks.  \"`%Well, I'll make you a magic elixir for `^two gems`%!`0\""=>"\"`%Hai delle gemme, vero?`0\" chiede Cedrik. \"`%Beh, ti preparerò un elisir magico per `^due gemme`%!`0\""
		,"`n`nGive him how many gems?"=>"`n`nQuante gemme gli dai?"
		,"<form action='inn.php?op=bartender&act=gems' method='POST'><input name='gemcount' value='0'><input type='submit' value='Give'>`n"=>"<form action='inn.php?op=bartender&act=gems' method='POST'><input name='gemcount' value='0'><input type='submit' value='Dai'>`n"
		,"And what do you wish for?`n<input type='radio' name='wish' value='1' checked> Charm`n<input type='radio' name='wish' value='2'> Vitality`n"=>"E cosa desideri?`n<input type='radio' name='wish' value='1' checked> Fascino`n<input type='radio' name='wish' value='2'> Vitalità`n"
		,"<input type='radio' name='wish' value='3'> Health`n"=>"<input type='radio' name='wish' value='3'> Salute`n"
		,"<input type='radio' name='wish' value='4'> Forgetfulness`n"=>"<input type='radio' name='wish' value='4'> Dimenticanza`n"
		,"<input type='radio' name='wish' value='5'> Transmutation</form>"=>"<input type='radio' name='wish' value='5'> Transmutazione</form>"
		,"Cedrik stares at you blankly.  \"`%You don't have that many gems, `bgo get some more gems!`b`0\" he says."=>"Cedrik ti guarda con occhi vacui.  \"`%Non hai tutte queste gemme, `bvai a procurartene delle altre!`b`0\" dice."
		,array(
			"`#You place "=>"`#Metti "
			," gems on the counter."=>" gemme sul bancone."
			)
		,"  Cedrik, knowing about your fundamental misunderstanding of "=>"  Cedrik, sapendo dei tuoi problemi di base con la matematica "
		,"math, hands one of them back to you."=>"te ne restituisce una."
		,"  You drink the potion Cedrik hands you in exchange for your gems, and.....`n`n"=>"  Bevi la pozione che Cedrik ti da in cambio delle tue gemme, e.....`n`n"
		,"`&You feel charming! `^(You gain charm points)"=>"`&Ti senti affascinante! `^(Guadagni punti di fascino)"
		,"`&You feel vigorous! `^(You gain max hitpoints)"=>"`&Ti senti vigoroso! `^(Il tuo massimo di punti ferita aumenta)"
		,"`&You feel healthy! `^(You gain temporary hitpoints)"=>"`&Ti senti bene! `^(Guadagni punti ferita temporanei)"
		,"`&You feel completely directionless in life.  You should rest and make some important decisions about your life! `^(Your specialty has been reset)"=>"`&Ti senti completamente privo di direzione nella vita. Dovcresti riposare e prendere delle importanti decisioni per il tuo futro! `^(La tua specialità è stata resettata)"
		,"`n`nYou feel as though your gems would be better used elsewhere, not on some smelly potion."=>"`n`nSenti che le tue gemme potrebbero essere usate meglio in altro modo, non per delle pozioni puzzolenti."
		,"`@You double over retching from the effects of transformation potion as your bones turn to gelatin!`n`^(Your race has been reset and you will be able to chose a new one tomorrow.)"=>"`@Ti pieghi in due tremando per l'effetto della pozione di trasformazione, mentre le tue ossa diventano gelatina!`n`^(La tua razza è stata cancellata, ne potrai scegliere una nuova domani.)"
		,"How much would you like to offer him?"=>"Quanto vuoi offrirgli?"
		,"1 gem"=>"1 gemma"
		,"2 gems"=>"2 gemme"
		,"3 gems"=>"3 gemme"
		,"Cedrik leans over the counter toward you.  \"`%What can I do for you kid?`0\" he asks."=>"Cedrik si piega sopra il bancone verso di te.  \"`%Che posso fare per te?`0\" domanda."
		,"Who's upstairs?"=>"Chi c'è di sopra?"
		,"Tell me about colors"=>"Dimmi dei colori"
		,"Switch specialty"=>"Cambia specialità"
		,"Cedrik begins to wipe down the counter top, an act that really needed doing a long time ago.  "=>"Cedrik inizia a pulire la parte di sopra del bancone, una cosa che doveva essere fatta da molto tempo. "
		,array(
			"When he finished, your "=>"Quando finisce le tue "
			," gem"=>" gemm"
			,"s are"=>"e sono"
//			," is"=>"a è"
			,"gold is"=>"monete sono"
			)
		," gone.  You inquire about the loss, and he stares blankly back at you."=>"Scomparse. Gli chiedi che fine hanno fatto e lui ti guarda con espressione stranita."
		,"Pounding your fist on the bar, you demand an ale"=>"Sbattendo il pugno sul bancone, chiedi una birra"
		,array(
			", but Cedrik continues to clean the glass he was working on.  \"`%You've had enough "=>", ma Cedrik continua a pulire i bicchieri su cui stava lavorando.  \"`%Ne hai avuta abbastanza "
			,"lass"=>"ragazza"
			,"lad"=>"ragazzo"
			,",`0\" he declares."=>",`0\" dichiara."
//			,"says"=>"dice"
			)
		,".  Cedrik pulls out a glass, and pours a foamy ale from a tapped barrel behind him.  "=>".  Cedrik tira fuori un bicchiere e versa una birra spumosa da un barile dietro di lui.  "
		,"He slides it down the bar, and you catch it with your warrior-like reflexes.  "=>"Lo lancia lungo il bancone e tu lo afferri con i tuoi riflessi da guerriero. "
		,"`n`nTurning around, you take a big chug of the hearty draught, and give "=>"`n`nVoltandoti, bevi un grosso sorso della bevanda, e rivolgi a "
		," an ale-foam mustache smile.`n`n"=>" un sorriso con i baffi di schiuma.`n`n"
		,"`&You feel healthy!"=>"`&Ti senti in salute!"
		,"`&You feel vigorous!"=>"`&Ti senti vigoroso!"
		,"`#Buzz"=>"`#Ronzio"
		,"Your buzz fades."=>"Il tuo ronzio svanisce."
		,"You don't have enough money.  How can you have any ale if you don't have any money!?!"=>"Non hai abbastanza soldi. Come pensi di comprare della birra senza soldi!?!"
		,"Cedrik lays out a set of keys on the counter top, and tells you which key opens whose room.  The choice is yours, you may sneak in and attack any one of them."=>"Cedrik mette un mazzo di chiavi sul bancone, e ti dice a quali porte corrispondono. La scelta è tua, puoi intrufolarti nelle stanze ed attaccare chiunque."
		,"Cedrik leans on the bar.  \"`%So you want to know about colors, do you?`0\" he asks."=>"Cedrik si piega in avanti sul bancone.  \"`%Così vuoi sapere dei colori, vero?`0\" domanda."
		,"  You are about to answer when you realize the question was posed in the rhetoric.  "=>"  Stai per rispondergli quando ti rendoi conto che era una domanda retorica.  "
		,"Cedrik continues, \"`%To do colors, here's what you need to do.  First, you use a &#0096; mark "=>"Cedrik continua, \"`%Per fare i colori, ecco cosa devi fare.  Prima cosa, usa un segno &#0096; "
		,"(found right above the tab key) followed by 1, 2, 3, 4, 5, 6, 7, !, @, #, $, %, ^, &.  Each of those corresponds with "=>"(ALT + 096) seguito da 1, 2, 3, 4, 5, 6, 7, !, @, #, $, %, ^, &.  Ognuno corrisponde ad "
		,"a color to look like this: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 "=>"un colore come questo: `n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 "
		,"`% got it?`0\"  You can practice below:"=>"`% capito?`0\" Puoi fare pratica qui:"
		,"You entered "=>"Hai scritto "
		,"It looks like "=>"Viene fuori "
		,"<input name='testtext'><input type='submit' value='Try'></form>"=>"<input name='testtext'><input type='submit' value='Prova'></form>"
		,"`0`n`nThese colors can be used in your name, and in any conversations you have."=>"`0`n`nQuesti colori possono essere usati per il tuo nome e per qualunque conversazione."
		,"\"`2I want to change my specialty,`0\" you announce to Cedrik.`n`n"=>"\"`2Voglio cambiare specialità,`0\" annunci a Cedrik.`n`n"
		,"With out a word, Cedrik grabs you by the shirt, pulls you over the counter, and behind the "=>"Senza una parola, Cedrik ti afferra dalla maglietta, ti tira oltre il bancone e dietro "
		,"barrels behind him.  There, he rotates the tap on a small keg labeled \"Fine Swill XXX\""=>"i barili dietro di esso. Qui, gira il rubinetto di una botticella etichettata \"Buona Brodaglia XXX\""
		,"`n`nYou look around for the secret door that you know must be opening nearby when Cedrik "=>"`n`nTi guardi intorno cercando la porta segreta che sai dovrebbe aprirsi nei dintorni quando Cedrik "
		,"rotates the tap back, and lifts up a freshly filled foamy mug of what is apparently his fine swill, blue-green "=>"gira di nuovo il rubinetto e solleva una tazza spumosa piena di quella che apparentemente è la sua buona brodaglia, colore blu-verde "
		,"tint and all."=>"e tutto."
		,"`n`n\"`3What?  Were you expecting a secret room?`0\" he asks.  \"`3Now then, you must be more "=>"`n`n\"`3Che? Ti aspettavi una stanza segreta?`0\" domanda.  \"`3Ora, dovresti essere più "
		,"careful about how loudly you say that you want to change your specialty, not everyone looks "=>"cauto quando dici cose del tipo che vuoi cambiare specialità, non a tutti "
		,"favorably on that sort of thing.`n`n`0\"`3What new specialty did you have in mind?`0\""=>"piace questo genere di cose.`n`n`0\"`3Che nuova specialità avevi in mente?`0\""
		,"Dark Arts"=>"Arti Oscure"
		,"Mystical Powers"=>"Poteri Mistici"
		,"Thieving Skills"=>"Furto"
		,"\"`3Ok then,`0\" Cedrik says, You're all set.`n`n\"`2That's it?`0\" you ask him."=>"\"`3OK allora,`0\" dice Cedrik, a posto.`n`n\"`2Tutto qui?`0\" gli chiedi."
		,"`n`n\"`3Yep.  What'd you expect, some sort of fancy arcane ritual???`0\"  Cedrik "=>"`n`n\"`3Yep. Che ti aspettavi, Qualche strano rituale???`0\" Cedrik "
		,"begins laughing loudly.  \"`3You're all right, kid... just don't ever play poker, eh?`0"=>"Inizia a ridere fragorosamente.  \"`3Sei a posto, ragazzo... solo non giocare mai a poker, eh?`0"
		,"`n`n\"`3Oh, one more thing.  Your old use points and skill level still apply to that skill, "=>"`n`n\"`3Oh, un'ultima cosa. I tuoi vecchi punti di utilizzo e livello di abilità valgono ancora per quel talento, "
		,"you'll have to build up some points in this one to be very good at it.`0\""=>"dovrai guadagnartene qualcuno in questo per essere bravo.`0\""
		,"Return to the inn"=>"Torna alla locanda"
		,"\"Aah, so that's how it is,\" Cedrik says as he puts the key he had retrieved back on to its hook "=>"\"Aah, è così,\" dice Cedrik mentre rimette la chiave che aveva preso sul gancio "
		,"behind his counter.  Perhaps you'd like to get sufficient funds before you attempt to engage in "=>"dietro il bancone. Forse dovresti procurarti dei fondi sufficienti prima di cercare di "
		,"local commerce."=>"fare spese da queste parti."
		,"You already paid for a room for the day."=>"Hai già pagato la tua stanza per oggi."
		,"Go to room"=>"Vai alla stanza"
		,array(
			"Show him your coupons for "=>"Mostragli il tuo buono per "
			," inn stays"=>" giornate nella locanda"
			)
		,array(
		"You stroll over to the bartender and request a room.  He eyes you up and says that it will cost `\$"=>"Ti avvicini al barista e gli domandi una stanza. Lui ti guarda e dice che ti costerà `\$"
			,"Give him "=>"Dagli "
			," for the night.`n`nYou debate the issue, not wanting to part with your money when the fields offer a place to sleep, "=>" per una notte.`n`nSei combattuto, non volendoti separare dai tuoi soldi quando i campi offrono un buon posto per dormire, "
			,"however, you realize that the inn is a considerably safer place to sleep, it is far harder for vagabonds to get you "=>"tuttavia comprendi che la locanda è un posto considerevolmente più sicuro, è ben più difficile per i vagabondi prenderti "
			,"in your room while you sleep."=>"nella tua stanza mentre dormi."
			,"`0 gold"=>"`0 monete"
			)			
		,"Return to the inn"=>"Torna alla locanda"
		,array(
			"`!Lover's Protection"=>"`!Protezione dell'Amante"
			,"`!You miss "=>"`!Ti manca "
			,"Your lover inspires you to keep safe!"=>"Il tuo amante ti ispira a mantenerti al sicuro!"
			)
		,"You  and `5Violet`0 take some time to yourselves, and you leave the inn, positively glowing!"=>"Tu e `5Violet`0 vi prendete qualche momento per voi, e lasci la locanda raggiante!"
		,"You head over to cuddle Violet and kiss her about the face and neck, but she grumbles something about"=>"Ti avvicini a Violet e le baci il viso ed il collo, ma lei grugnisce qualcosa a proposito "
		,"being  too  busy serving these pigs,"=>"di essere troppo impegnata a servire questi porci,"
		,"\"that time of month,\""=>"di \"quel periodo del mese,\""
		,"\"a   little   cold...  *cough cough* see?\""=>"di \"un po' di raffreddore...  *coff coff* vedi?\""
		,"men all being pigs,"=>" del fatto che gli uomini sono tutti maiali,"
		,"and  with a comment like that, you storm away from her!"=>" e dopo un commento del genere ti allontani rapidamente da lei!"
		,"You  head  over  to  snuggle up to Seth  and  kiss him about the face and neck, but he grumbles something about"=>"Ti avvicini a Seth per coccolarlo un po' e gli baci la faccia ed il collo, ma lui borbotta qualcosa riguardo "
		,"being   too  busy  tuning  his lute,"=>" l'essere troppo occupato ad accordare il suoi liuto,"
		,"\"that time of month,\""=>" \"quel periodo del mese,\""
		,"wanting  you  to  fetch  him a beer,"=>"del fatto che vorrebbe che gli portassi una birra,"
		,"and  with a comment like that, you storm away from him!"=>" e dopo un commento del genere, te ne vai!"
		,"You  and  Seth  take  some time to yourselves, and you leave the inn, positively glowing!"=>"Tu e Seth vi prendete qualche momento per voi, e lasci la locanda raggiante!"
		,"You LOSE a charm point!"=>"PERDI un punto di fascino!"
		);
		break;
	case "dag.php":
		$replace = array(
		"Dag Durnick's Table"=>"Il Tavolo di Dag Durnick"
		,"`c`bDag Durnick's Table`b`c"=>"`c`bIl Tavolo di Dag Durnick`b`c"
		,"Dag fishes a small leather bound book out from under his cloak, flips through it to a certain page and holds it up for you to see."=>"Dag pesca un libretto rilegato in pelle da sotto il mantello, lo apre ad una certa pagina e lo alza in modo che tu possa vederlo."
		,"`c`bThe Bounty List`b`c`n"=>"`c`bElenco delle Taglie`b`c`n"
		,"Bounty Amount</b></td><td><b>Level</b></td><td><b>Name</b></td><td><b>Location</b></td><td><b>Sex</b></td><td><b>Last on</b></tr>"=>"Valore della Taglia</b></td><td><b>Livello</b></td><td><b>Nome</b></td><td><b>Posizione</b></td><td><b>Sesso</b></td><td><b>Ultimo collegamento</b></tr>"
		,array(
			"`3Boar's Head Inn`0"=>"`3Locanda \"alla Testa del Cinghiale\"`0"
			,"`3The Fields`0"=>"`3Nei Campi`0"
			,"`!Female`0"=>"`!Femmina`0"
			,"`!Male`0"=>"`!Maschio`0"
			," days"=>" giorni"
			,"Today"=>"Oggi"
			,"Yesterday"=>"Ieri"
			,"Now"=>"Ora"
			)
		,"Dag gives you a piercing look. `7\"Ye be thinkin' I be an assassin or somewhat?  Ye already be placin' more than 'nuff bounties for t'day.  Now, be ye gone before I stick a bounty on yer head fer annoyin' me.`n`n"=>"Dag ti rivolge uno sguardo penetrante. `7\"Pensi che io sia un'assassino o qualcosa del genere? Hai già oferto più che abbastanza taglie per oggi. Ora vattene prima che metta io una taglia su di te per avermi importunato.`n`n"
		,array(
			"Dag Durnick glances up at you and adjusts the pipe in his mouth with his teeth.`n`7\"So, who ye be wantin' to place a hit on? Just so ye be knowing, they got to be legal to be killin', they got to be at least level "=>"Dag Durnick guarda verso di te e si aggiusta la pipa in mocca con i denti.`n`7\"Così vuoi mettere una taglia eh? Giusto perché tu lo sappia, deve essere legale ammazzarlo, deve essere almeno di livello "
			,", and they can't be having too much outstandin' bounty nor be getting hit to frequent like, so if they ain't be listed, they can't be contracted on!  We don't run no slaughterhouse here, we run a.....business.  Also, there be a "=>", e non deve avere troppe taglie in sospeso o essere attaccato troppo di frequente, perciò se non è su questa lista, non si accettano contratt! Non abbiamo un mattatoio qui, abbiamo... affari. E c'è anche un "
			,"% listin' fee fer any hit ye be placin'.\""=>"% di tassa per ogni taglia offerta.\""
			)
		,"`2Target: "=>"`2Bersaglio: "
		,"`2Amount to Place: "=>"`2Valore della Taglia: "
		,"<input type='submit' class='button' value='Finalize Contract'></form>"=>"<input type='submit' class='button' value='Concludi Contratto'></form>"
		,"Dag Durnick sneers at you, `7\"There not be anyone I be knowin' of by that name.  Maybe ye should come back when ye got a real target in mind?\""=>"Dag Durnick ti guarda storto, `7\"Non conosco nessuno con questo nome. Forse dovresti tornare quando avrai qualcuno che esiste in mente!\""
		,"Dag Durnick scratches his head in puzzlement, `7\"Ye be describing near half th' town ye fool?  Why don't ye be giving me a better name now?\""=>"Dag Durnick si gratta la testa confuso, `7\"Hai descritto mezza città! Perché non mi dai un nome decente?\""
		,"Dag Durnick searches through his list for a moment, `7\"There be a couple of 'em that ye could be talkin' about.  Which one ye be meaning?\"`n"=>"Dag Durnick controlla per un attimo nella sua lista, `7\"Ce ne sono un paio che potrebbero corrispondere. Di quale stai parlando?\"`n"
		,"Dag Durnick slaps his knee laughing uproariously, `7\"Ye be wanting to take out a contract on yerself?  I ain't be helping no suicider, now!\""=>"Dag Durnick si da una manata sul ginocchio ridendo sguaiatamente, `7\"Vuoi mettere una taglia sulla tua testa? Non ho intenzione di aiutare un suicida!\""
		,"Dag Durnick stares at you angrily, `7\"I told ye that I not be an assassin.  That ain't a target worthy of a bounty.  Now get outta me sight!\""=>"Dag Durnick ti guarda infuriato,`7\"Ti ho detto che non sono un assassino. Quel bersaglio non merita una taglia. Fuori dai piedi!\""
		,array(
			"Dag Durnick scowls, `7\"Ye think I be workin' for that pittance?  Be thinkin' again an come back when ye willing to spend some real coin.  That mark be needin' at least "=>"Dag Durnick si acciglia, `7\"Pensi che lavorerò per questa miseria? Ripensaci e torna quando avrai voglia di spendere dei soldi veri. Devono essere almeno "
			," gold to be worth me time.\""=>" monete perché ci perda del tempo.\""
			)
		,"Dag Durnick scowls, `7\"Ye don't be havin enough gold to be settin' that contract.  Wastin' my time like this, I aught to be puttin' a contract on YE instead!"=>"Dag Durnick si acciglia, `7\"Non hai abbastanza soldi per questo contratto. Farmi perdere tempo in questo modo! Dovrei metterla su di TE una taglia!"
		,array(
			"Dag looks down at the pile of coin and just leaves them there. `7\"I'll just be passin' on that contract.  That's way more'n"=>"Dag guarda la pila di monete e la lascia dove si trova. `7\"Non accetto il contratto. È più di quanto "
			,"`7be worth and ye know it.  I ain't no durned assassin. A bounty o'"=>"`7valga e lo sai. Non sono un dannato assassino. Ha già una taglia di "
			,"already be on their head.  I might be willin' t'up it to "=>" monete sulla sua testa. Potrei alzarla al massimo fino a "
			,", after me "=>", dopo aver ricevuto il mio"
			,"% listin' fee of course\""=>"% ovviamente\""
			)
		,array(
			"You slide the coins towards Dag Durnick, who deftly palms them from the table. `7\"I'll just be takin' me"=>"Spingi i soldi verso Dag Durnick, che rapidamente li spazza via dal tavolo. `7\"Mi prenderò da qui il mio"
			,"% listin' fee offa the top.  The word be put out that ye be wantin' "=>"% E farò girare la voce che vuoi che ci si occupi di "
			,"`7taken care of. Be patient, and keep yer eyes on the news.\""=>"`7. Sii paziente e tieni d'occhio le notizie.\""
			)
		,"You stroll over to Dag Durnick, who doesn't even bother to look up at you. He takes a long pull on his pipe.`n"=>"Ti avvicini a Dag Durnick, che neppure si degna di guardarti. Tira una grossa boccata dalla sua pipa.`n"
		,"`7\"Ye probably be wantin' to know if there's a price on yer head, ain't ye.\"`n`n"=>"`7\"Probabilmente sei qui per sapere se c'è una taglia sulla tua testa, eh?\"`n`n"
		,array(
			"\"`3Well, it be lookin like ye have `^"=>"\"`3Beh, sembra che ci sia una taglia di `^"
			," gold`3 on yer head currently. Ye might wanna be watchin yourself.\""=>" monete`3 sulla tua testa al momento. Credo che dovresti guardarti le spalle.\""
			)
		,"\"`3Ye don't have no bounty on ya.  I suggest ye be keepin' it that way.\""=>"\"`3Non ci sono taglie su di te. Ti suggerisco di continuare così.\""
		,"Check the Wanted List"=>"Lista dei Ricercati"
		,"Set a Bounty"=>"Offri una Taglia"
		,"Talk to Dag Durnick"=>"Parla con Dag Durnick"
		,"Return to the inn"=>"Torna alla locanda"
		);
		break;

	case "list.php":
		$replace = array(
		"Return to the village"=>"Torna al villaggio"
		,"Currently Online"=>"Collegati al momento"
		,"Login Screen"=>"Pagina Iniziale"
		,"Currently Online"=>"Collegati al momento"
		,"List Warriors"=>"Elenco Guerrieri"
		,"Pages"=>"Pagine"
		,"Page "=>"Pagina "
		,"`c`bWarriors Currently Online`b`c"=>"`c`bGuerrieri Collegati al Momento`b`c"
		,"`c`bWarriors in the realm (Page "=>"`c`bGuerrieri del reame (Pagina "
		,"<form action='list.php?op=search' method='POST'>Search by name: <input name='name'><input type='submit' value='Search'></form>"=>"<form action='list.php?op=search' method='POST'>Cerca per nome: <input name='name'><input type='submit' value='Cerca'></form>"
		,"<tr class='trhead'><td><b>Alive</b></td><td><b>Level</b></td><td><b>Name</b></td><td><b>Location</b></td><td><b>Sex</b></td><td><b>Last on</b></tr>"=>"<tr class='trhead'><td><b>Alive</b></td><td><b>Livello</b></td><td><b>Nome</b></td><td><b>Posizione</b></td><td><b>Sesso</b></td><td><b>Ultimo collegamento</b></tr>"
		,"`3Boar's Head Inn`0"=>"`3Locanda Testa del Cinghiale`0"
		,"`#Online`0"=>"`#Collegato`0"
		,"`3The Fields`0"=>"`3Nei campi`0"
		,"`!Female`0"=>"`!Femmina`0"
		,"`!Male`0"=>"`!Maschio`0"
		,"Today"=>"Oggi"
		,"Yesterday"=>"Ieri"
		,"Now"=>"Adesso"
		);
		break;
	case "logdnet.php":
		$replace = array(
		"Return to the login page"=>"Torna alla pagina di login"
		,"`@Below are a list of other LoGD servers that have registered with the LoGD Net."=>"`@Questo è un elenco di altri server LoGD registrati con LoGD Net."
		,"Another LoGD Server"=>"Un altro server LoGD"
		);
		break;
	case "mail.php":
		$replace = array(
		"`\$`bYou cannot delete zero messages!  What does this mean?  You pressed \"Delete Checked\" but there are no messages checked!  What sort of world is this that people press buttons that have no meaning?!?`b`0"=>"`\$`bNon puoi cancellare zero messaggi!  Che significa? Hai premuto \"Cancella Selezionati\" ma non ci sono messaggi selezionati! Che razza di mondo è questo in cui la gente preme pulsanti che non hanno senso?!?`b`0"
		,"Ye Olde Poste Office"=>"Il Vecchio Ufficio Postale"
		,"<a href='mail.php' class='motd'>Inbox</a><a href='mail.php?op=address' class='motd'>Write</a>`n`n"=>"<a href='mail.php' class='motd'>Posta Ricevuta</a><a href='mail.php?op=address' class='motd'>Scrivi</a>`n`n"
		,"`b`iMail Box`i`b"=>"`b`iCasella Postale`i`b"
		,"<td nowrap><input id='checkbox$i' type='checkbox' name='msg[]' value='$row[messageid]'><img src='images/".($row[seen]?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row[seen]?"Old":"New")."'></td>"=>"<td nowrap><input id='checkbox$i' type='checkbox' name='msg[]' value='$row[messageid]'><img src='images/".($row[seen]?"vecchi":"nuovi")."scroll.GIF' width='16' height='16' alt='".($row[seen]?"Vecchi":"Nuovi")."'></td>"
		,"<input type='button' value='Check All' onClick='"=>"<input type='button' value='Seleziona tutto' onClick='"
		,"<input type='submit' class='button' value='Delete Checked'>"=>"<input type='submit' class='button' value='Cancella Selezionati'>"
		,"`iAww, you have no mail, how sad.`i"=>"`iAww, non hai posta, che tristezza.`i"
		,array(
			"`n`n`iYou have "=>"`n`n`iHai "
			," messages in your inbox.`nYou may only have "=>" messaggi nella tua casella.`nPuoi avere al massimo "
			," messages in your inbox.  `nMessages are deleted after "=>" messaggi nella tua casella.  `nI messaggi vengono cancellati dopo "
			," days"=>" giorni"
			)
		,"`iSystem`i"=>"`iSistema`i"
		,"`b`2From:`b"=>"`b`2Da:`b"
		,"`b`2Subject:`b"=>"`b`2Oggetto:`b"
		,"<a href='mail.php?op=write&replyto=$row[messageid]' class='motd'>Reply</a><a href='mail.php?op=del&id=$row[messageid]' class='motd'>Del</a>"=>"<a href='mail.php?op=write&replyto=$row[messageid]' class='motd'>Rispondi</a><a href='mail.php?op=del&id=$row[messageid]' class='motd'>Cancella</a>"
		,"Eek, no such message was found!"=>"Aaaaa! Messaggio non trovato!"
		,"`b`2Address:`b`n"=>"`b`2Indirizzo:`b`n"
		,"`2T<u>o</u>: <input name='to' accesskey='o'> <input type='submit' value='Search'></form>"=>"`2<u>A</u>: <input name='to' accesskey='a'> <input type='submit' value='Cerca'></form>"
		,"You cannot reply to a system message.`n"=>"Non si può rispondere a un messaggio di sistema.`n"
		,"Could not find that person.`n"=>"Persona non trovata.`n"
		,"\n\n---Original Message---\n"=>"\n\n---Messaggio Originale---\n"
		,"`2To: `^"=>"`2A: `^"
		,"`2To: "=>"`2A: "
		,"`2Subject: <input name='subject' value=\""=>"`2Oggetto: <input name='subject' value=\""
		,"`2Body:`n"=>"`2Testo:`n"
		,"You cannot send that person mail, their mailbox is full!"=>"Il destinatario ha la casella piena e non può ricevere posta!"
		,"Your message was sent!"=>"Messaggio inviato!"
		,"Could not find the recipient, please try again."=>"Destinatario non trovato, ritenta."
		,"<input type='submit' class='button' value='Send'>`n"=>"<input type='submit' class='button' value='Invia'>`n"
		);
		break;
	case "common.php":
		$replace = array(
			array(
				"Dark Arts"=>"Arti Oscure"
				,"Mystical Powers"=>"Poteri Mistici"
				,"Thievery"=>"Furto"
				,"`nYou gain a level in `&"=>"`nAcquisisci un livello in `&"
				,"`# to "=>"`# a "
				)
		,array(
			"only "=>"solo altri "
			," more skill levels until you gain an extra use point!`n"=>" livelli prima di guadagnare un punto di utilizzo in più!`n"
			,"you gain an extra use point!`n"=>"guadagni un punto di utilizzo!`n"
			)
		,"`7You have no direction in the world, you should rest and make some important decisions about your life.`n"=>"`7Non hai una direzione al mondo, dovresti riposare e prendere qualche importante decisione riguardo la tua vita.`n"

		);
		break;
	case "motd.php":
		$replace = array(
		array(
			"LoGD Message of the Day (MoTD)"=>"Messagggio del Giorno di LoGD (MoTD)"
			," [<a href='motd.php?op=add'>Add MoTD</a>|<a href='motd.php?op=addpoll'>Add Poll</a>]"=>" [<a href='motd.php?op=add'>Aggiungi MoTD</a>|<a href='motd.php?op=addpoll'>Aggiungi Sondaggio</a>]"
			)
		,"`bPoll: "=>"`bSondaggio: "
		,"`n<input type='submit' value='Vote'>"=>"`n<input type='submit' value='Vota'>"
		," was penalized for attempting to defile the gods."=>" è stato punito per aver tentato di ingannare gli dei."
		,"You've attempted to defile the gods.  You are struck with a wand of forgetfulness.  Some of what you knew, you no longer know."=>"Hai tentato di ingannare gli dei. Vieni colpito da una bacchetta della dimenticanza. Qualcosa di ciò che sapevi, non la sai più."
		,"Please see the beta message below."=>"Per favore leggete il messaggio di seguito."
		,"For those who might be unaware, this website is still in beta mode.  I'm working on it when I have time, which generally means a couple of changes a week.  Feel free to drop suggestions, I'm open to anything :-)"=>"Per chi non lo sapesse, questo sito web è ancora in versione beta. Ci sto lavorando quando ho tempo, il che significa in genere un paio di cambiamenti alla settimana. Sentitevi liberi di mandare suggerimenti, sono aperto a tutto. :-)"
		,"`@Commentary:`0`n"=>"`@Commenti:`0`n"
		);
		break;
	case "newday.php":
		$replace = array(
		"It is a new day!"=>"È un nuovo giorno!"
		,"It is a New Day!"=>"È un nuovo giorno!"
		,"Ye Olde Mail"=>"Messaggi"
		,"Max Hitpoints + 5"=>"Punti ferita Max + 5"
		,"Forest Fights + 1"=>"Turni +1"
		,"Attack + 1"=>"Attacco + 1"
		,"Defense + 1"=>"Difesa + 1"
		,"`n`%You're  married,  so there's no reason to keep up that perfect image, and you let yourself go a little today.`n"=>"`n`%Sei sposato, perciò non c'è nessun motivo di mantenere un'immagine perfetta, ed oggi ti lasci andare un po'.`n"
		,"`bWhen  you  wake  up, you find a note next to you, reading`n`5Dear "=>"`bQuando ti svegli, trovi accanto a te un biglietto che dice`n`5Caro "
		,"`5,`nDespite  many  great  kisses, I find that I'm simply no longer attracted to you the way I used to be.`n`n"=>"`5,`nNonostante i bellissimi baci, scopro di non provare più attrazione per te nel modo in cui la provavo prima.`n`n"
		,"Call  me fickle, call me flakey, but I need to move on.  There are other warriors in the village, and I think"=>"Chiamami volubile, ma ho bisogno di andarmene. Ci sono altri guerrieri nel villaggio e penso che"
		,"some of them are really hot.  So it's not you, it's me, etcetera etceterea."=>"alcuni siano parecchio attraenti. Perciò non sei tu, sono io, eccetera ecceterea."
		,"`n`nNo hard feelings, Love,`n"=>"`n`nSenza rancore, Baci,`n"
		,array(
			"`@You have `^"=>"`@Hai `^"
			,"`@ unspent dragon points.  How do you wish to spend them?`n`n"=>"`@ punti drago inutilizzati. Come vuoi spenderli?`n`n"
			)
		,"You earn one dragon point each time you slay the dragon.  Advancements made by spending dragon points are permanent!"=>"Guadagni un punto drago ogni volta che uccidi il drago. Gli avanzamenti ottenuti spendendo punti drago sono permanenti!"
		,"A little history about yourself"=>"Un po' della tua storia"
		,"`2As a troll, and having always fended for yourself, the ways of battle are not foreign to you.`n`^You gain an attack point!"=>"`2Essendo un troll, ed essendoti sempre difeso da solo, i metodi di combattimento non ti sono ignoti.`n`^Guadagni un punto di attacco!"
		,"`^As an elf, you are keenly aware of your surroundings at all times, very little ever catches you by surprise.`nYou gain a defense point!"=>"`^Essendo un elfo, sei consapevole di tutto quello che ti circonda in ogni momento, poche cose riescono a prenderti di sorpresa.`nGuadagni un punto di difesa!"
		,"`&As a human, your size and strength permit you the ability to effortlessly wield weapons, tireing much less quickly than other races.`n`^You gain an extra forest fight each day!"=>"`&Essendo umano, la tua taglia e la tua forza ti danno la capacità di impugnare armi senza sforzo, stancandoti molto più lentamente rispetto alle altre razze.`n`^Guadagni un combattimento in più nella foresta ogni giorno!"
		,"`#As a dwarf, you are more easily able to identify the value of certain goods.`n`^You gain extra gold from forest fights!"=>"`#Essendo un nano, sei più abile nell'identificare il valore di alcuni oggetti.`n`^Guadagni denaro extra dai combattimenti nella foresta!"
		,"Where do you recall growing up?`n`n"=>"Dove ricordi di essere cresciuto?`n`n"
		,"In the swamps of Glukmoore</a> as a `2troll`0, fending for yourself from the very moment you crept out of your leathery egg, slaying your yet unhatched siblings, and feasting on their bones.`n`n"=>"Nelle paludi di Glukmoore</a> come `2troll`0, combattendo fin dal primo istante in cui sei uscito dal tuo uovo, ucidendo i tuoi fratelli ancora non schiusi, e pasteggiando con le loro ossa.`n`n"
		,"High among the trees</a> of the Glorfindal forest, in frail looking elaborate `^Elvish`0 structures that look as though they might collapse under the slightest strain, yet have existed for centuries.`n`n"=>"Sugli alti alberi della</a> foresta di Glorfindal, in elaborate strutture `^Elfiche`0 di fragile aspetto che sembravano poter collassare sotto il minimo peso, eppure esistono da secoli.`n`n"
		,"On the plains in the city of Romar</a>, the city of `&men`0; always following your father and looking up to his every move, until he sought out the `@Green Dragon`0, never to be seen again.`n`n"=>"sulle pianure nella città di Romar</a>, la città degli `&uomini`0; seguendo sempre tuo padre e guardando ogni sua mossa fino a quando non è andato a caccia del `@Drago Verde`0, e non ha mai fatto ritorno.`n`n"
		,"Deep in the subterranean strongholds of Qexelcrag</a>, home to the noble and fierce `#Dwarven`0 people whose desire for privacy and treasure bears no resemblance to their tiny stature.`n`n"=>"Nelle profonde fortezze sotterranee di Qexelcrag</a>, casa del nobile e fiero popolo dei `#Nani`0 il cui desiderio di privacy e tesori non si sposa affatto con la loro scarsa altezza.`n`n"
		,"Choose your Race"=>"Scegli la tua razza"
		,"`^Elf`0"=>"`^Elfo`0"
		,"`&Human`0"=>"`&Umano`0"
		,"`#Dwarf`0"=>"`#Nano`0"
		,"Growing up as a child, you remember:`n`n"=>"Ricordi che da bambino, crescendo:`n`n"
		,"<a href='newday.php?setspecialty=1'>Killing a lot of woodland creatures (`\$Dark Arts`0)</a>`n"=>"<a href='newday.php?setspecialty=1'>Uccidevi molte creature della foresta (`\$Arti Oscure`0)</a>`n"
		,"<a href='newday.php?setspecialty=2'>Dabbling in mystical forces (`%Mystical Powers`0)</a>`n"=>"<a href='newday.php?setspecialty=2'>Sguazzavi nelle forze mistiche (`%Poteri Mistici`0)</a>`n"
		,"<a href='newday.php?setspecialty=3'>Stealing from the rich and giving to yourself (`^Thievery`0)</a>`n"=>"<a href='newday.php?setspecialty=3'>Rubavi ai ricchi e davi a te stesso (`^Furto`0)</a>`n"
		,"`\$Dark Arts"=>"`\$Arti Oscure"
		,"`%Mystical Powers"=>"`%Poteri Mistici"
		,"`^Thievery"=>"`^Furto"
		,"Continue"=>"Continua"

		,"`5Growing up, you recall killing many small woodland creatures, insisting that they were "=>"`5Ricordi che nell'infanzia uccidevi numerose piccole creature della foresta, ripetendo che "
		,"plotting against you.  Your parents, concerned that you had taken to killing the creatures "=>"stavano tramando contro di te. I tuoi genitori, preoccupati di vederti uccidere tali creature "
		,"barehanded, bought you your very first pointy twig.  It wasn't until your teenage years that "=>"a mani nude, ti comprarono il tuo primo bastone appuntito. Fu solo negli anni dell'adolescenza che "
		,"you began performing dark rituals with the creatures, dissapearing into the forest for days "=>"iniziasti ad eseguire rituali oscuri con le creature, scomparendo nella foresta per giorni"
		,"on end, no one quite knowing where those sounds came from."=>"alla fine, senza che nessuno sapesse da dove provenivano quei suoni."
		,"Mystical Forces"=>"Poteri Mistici"
		,"`3Growing up, you remember knowing there was more to the world than the physical, and what you "=>"`3Ricordi che da bambino sapevi che c'era dell'altro oltre il mondo fisico, e ciò che "
		,"could place your hands on.  You realized that your mind itself, with training, could be turned "=>"potevi toccare con le mani. Comprendesti che la tua stessa mente, con l'allenamento, poteva diventare "
		,"in to a weapon.  Over time, you began to control the thoughts of small creatuers, commanding "=>"un'arma. Col tempo, iniziasti a controllare i pensieri di piccole creature, ordinando loro "
		,"them to do your bidding, and also to begin to tap in to the mystical force known as mana, "=>"di piegarsi ai tuoi voleri, ed iniziasti anche ad attingere alla forza mistica nota come mana, "
		,"which could be shaped in to numerous elemental forms, fire, water, ice, earth, wind, and also "=>"che poteva essere modellata in numerose forme elementali, fuoco, acqua, ghiaccio, vento, ed anche "
		,"used as a weapon against your foes."=>"usata come arma contro i tuoi nemici."
//		,"Thievery"=>"Furto"
		,"`6Growing up, you recall discovering that a casual bump in a crowded room could earn you "=>"`6Ricordi di aver scoperto crescendo che un urto casuale in una stanza affollata poteva farti guadagnare "
		,"the coin purse of someone otherwise more fortunate than you.  You also discovered that "=>"il borsellino di qualcuno solitamente più fortunato di te. Scopristi anche che "
		,"the back side of your enemies were considerably more prone to a narrow blade than the "=>"la parte posteriore dei tuoi nemici era meglio predisposta per una lama sottile di "
		,"front side was to even a powerful weapon."=>"quanto non lo fosse quella anteriore anche per un'arma più potente."
		,"You have been slain!"=>"Sei stato ucciso!"
		,array(
			"`\$You were slain in the "=>"`\$Sei stato ucciso nella "
			," by `%"=>" da `%"
			,"`\$.  They cost you 5% of your experience, and took any gold you had.  Don't you think it's time for some revenge?"=>"`\$.  Ti è costato il 5% della tua esperienza, e tutto l'oro che avevi con te. Non pensi sia il momento di vendicarsi un po'?"
			)
		,"Continue"=>"Continua"
		,"It is a new day!"=>"Inizia un nuovo giorno!"
		,array(
			"`@You are resurrected!  This is your "=>"`@Sei risorto! Questa è la tua "
			," resurrection.`0`n"=>" resurrezione.`0`n"
			)
		,array(
			"You open your eyes to discover that a new day has been bestowed upon you, it is your `^"=>"Apri gli occhi per scoprire che un nuovo giorno è iniziato, è il tuo `^"
			,"`0 day.  "=>"`0 giorno.  "
			)
		,"You feel refreshed enough to take on the world!`n"=>"Ti senti abbastanza riposato da affrontare il mondo!`n"
		,"`2Turns for today set to `^"=>"`2I turni per oggi sono `^"
		,"`2Today's interest rate: `^0% (Bankers in this village only give interest to those who work for it)`n"=>"`2Tasso di interesse di oggi: `^0% (I banchieri in questo villaggio danno interessi solo a chi lavora per guadagnarseli)`n"
		,"`2Today's interest rate: `^"=>"`2Tasso di interesse di oggi: `^"
		,"`2Gold earned from interest: `^"=>"`2Monete guadagnate con gli interessi: `^"
		,array(
			"`2Interest Accrued: `^"=>"`2Interesse accumulato: `^"
			,"`2 gold.`n"=>"`2 monete.`n"
			)
		,"`2Hitpoints have been restored to `^"=>"`2I tuoi Punti Ferita sono stati riportati a `^"
		,array(
			"`2For being interested in `&"=>"`2Per il tuo interesse per `&"
			,"`2, you receive "=>"`2, ricevi "
			," extra `&"=>" uso extra di `&"
			,"`2 use for today.`n"=>"`2 per oggi.`n"
			,"Dark Arts"=>"Arti Oscure"
			,"Mystical Powers"=>"Poteri Mistici"
			,"Thievery"=>"Furto"
			)
		,"`n`2Guadagni `^$dkff`2 combattimenti nella foresta per i punti drago spesi!"
		,array(
			"`n`2You are in `^"=>"`n`2Il tuo spirito è `^"
			,"`2 spirits today!`n"=>"`2 quest'oggi!`n"
			,"Low"=>"Basso"
			,"High"=>"Alto"
			,"Normal"=>"Normale"
			,"Very "=>"Molto "
			)
		,"Continue"=>"Continua"
		,"`&Coming off of a hangover, you lose 1 forest fight today"=>"`&Per i postumi della sbornia, perdi 1 combattimento nella foresta oggi"
		,array(
			"`n`&You strap your `%"=>"`n`&Leghi il tuo `%"
			,"`& to your back and head out for some adventure.`0"=>"`& alla schiena e vai in cerca di avventure`0"
			,"`& to your "=>"`& alle bisacce del tuo "
			,"'s saddlebags and head out for some adventure.`0"=>" e vai in cerca di avventure`0"
			,"`n`&Because you have a "=>"`n`&Poiché hai un "
			,"Because you are "=>"Poiché sei "
			,"`2As a result, you `^"=>"`2Perciò `^"
			,"lose "=>"perdi "
			," forest fights`2 for today!`n"=>" combattimenti nella foresta`2 per oggi!`n"
			,", you gain "=>", guadagni "
			,"gain "=>"guadagni "
			," forest fights for today!`n`0"=>" combattimenti nella foresta per oggi!`n`0"
			," forest fight for today!`n`0"=>" combattimento nella foresta per oggi!`n`0"
			,"human"=>"umano"
			,"stallion"=>"stallone"
			,"gelding"=>"puledro"
			)
		,"`@You gain an extra turn from points spent on `^{$val['bought']}`@."=>"`@Guadagni un turno extra per i punti spesi a `^{$val['bought']}`@."
		,array(
			"  You have `^"=>"  Hai `^"
			,"`@ days left on this buy.`n"=>"`@ giorni rimanenti di questo acquisto.`n"
			)
		,"  This buy has expired.`n"=>"  Questo acquisto è scaduto.`n"
		,"`n`n`)Sei stato tormentato da {$session['user']['hauntedby']}`), e per questo perdi un combattimento nella foresta!"
		);
		break;
	case "news.php":
		$replace = array(
		"You have been slain!"=>"Sei stato ucciso!"
		,array(
			"`\$You were slain in the "=>"`\$Sei stato ucciso nella "
			," by `%"=>" da `%"
			,"`\$.  They cost you 5% of your experience, and took any gold you had.  Don't you think it's time for some revenge?"=>"`\$.  Ti è costato il 5% della tua esperienza, e tutto l'oro che avevi con te. Non pensi sia il momento di vendicarsi un po'?"
			)
		,array(
			"`3 was hunted down by their master `^"=>"`3 è stato inseguito dal suo maestro `^"
			,"`3 for being truant."=>"`3 per aver marinato la scuola."
			)
		,"Continue"=>"Continua"
		,"LoGD News"=>"Notizie di LoGD"
		,"`c`b`!News for $date"=>"`c`b`!Notizie del $date"
		,"`1`b`c Nothing of note happened this day.  All in all a boring day. `c`b`0"=>"`1`b`c Oggi non è accaduto nulla che sia degno di nota. Una giornata completamente noiosa. `c`b`0"
		,"Today's news"=>"Notizie di oggi"
		,"Page "=>"Pagina "
		,"Other"=>"Altro"
		,"Village Square"=>"Piazza del villaggio"
		,"`!`bYou're dead, Jim!`b`0"=>"`!`bSei morto, amico!`b`0"
		,"Preferences"=>"Preferenze"
		,"Land of Shades"=>"Terra delle ombre"
		,"Log out"=>"Esci"
		,"Previous News"=>"Notizie precedenti"
		,"Next News"=>"Notizie successive"
		,"About this game"=>"Info di questo gioco"
		,"New Day"=>"Nuovo giorno"
		,"You have been slain!"=>"Sei stato ucciso!"
		,"`c`b`!News for $date"=>"`c`b`!News di $date"
		," has slain the hideous creature known as `@The Green Dragon`&.  Across all the lands, people rejoice!"=>" ha ucciso la creatura nota come `@Il Drago Verde`&.  Gente del paese, gioite!"
		,array(
			" has earned the title `&"=>" ha acquisito il titolo di `&"
			,"`# for having slain the `@Green Dragon`& `^"=>"`# per aver ucciso il `@Drago Verde`& `^"
			,"`# times!"=>"`# volte!"
			)
		,array(
			"`2Always cool, "=>"`2Sempre fine, "
			," was seen walking around 
		 with a long string of toilet paper stuck to "=>" è stato visto girare con una lunga striscia di carta igienica attaccata al "
			," foot.`n"=>" piede.`n"
			,"his"=>"suo"
			,"her"=>"suo"
			)
		);
		break;
	case "petition.php":
		$replace = array(
		
		);
		break;
	case "prefs.php":
		$replace = array(
		
		);
		break;
	case "pvp.php":
		$replace = array(
		"PvP Combat!"=>"Combattimento PvP!"
		,array(
			"`4You head out to the fields, where you know some unwitting warriors are sleeping.`n`nYou have `^"=>"`4Ti dirigi verso i campi, dove sai che alcuni guerrieri poco saggi stanno dormendo.`n`nHai `^"
			,"`4 PvP fights left for today."=>"`4 combattimenti PvP rimasti per oggi."
			)
		,"List Warriors"=>"Elenco Guerrieri"
		,"<table border=0 cellpadding=0><tr><td>Name</td><td>Level</td></tr>"=>"<table border=0 cellpadding=0><tr><td>Nome</td><td>Livello</td></tr>"
		,"'><td>$row[name]</td><td>$row[level]</td><td>`i(Attacked too recently)`i</td></tr>"=>"'><td>$row[name]</td><td>$row[level]</td><td>`i(Attaccato troppo recentemente)`i</td></tr>"	
		,"`\$Error:`4 That user is out of your level range!"=>"`\$Errore:`4 Il livello di questo giocatore è troppo lontano dal tuo!"
		,"`\$Oops:`4 That user is currently engaged by someone else, you'll have to wait your turn!"=>"`\$Oops:`4 Quel giocatore al momento sta combattendo qualcun altro, dovrai aspettare il tuo turno!"
		,"`\$Error:`4 That user is now online."=>"`\$Errore:`4 Quel giocatore al momento è collegato."
		,"`\$Error:`4 That user is not in a location that you can attack them."=>"`\$Errore:`4 Quel giocatore non è in un posto in cui puoi attaccarlo."
		,"`\$Error:`4 That user is not alive."=>"`\$Error:`4 Quel personaggio non è vivo."
		,"`4Judging by how tired you are, you think you had best not engage in another player battle today."=>"`4Visto quanto sei stanco, pensi che sia meglio evitare un'altra battaglia giocatore contro giocatore per oggi."
		,"`\$Error:`4 That user was not found!  How'd you get here anyhow?"=>"`\$Errore:`4 Utente non trovato! E come ci sei arrivato qui, poi?"
		,"Your honor prevents you from running"=>"Sarebbe disonorevole fuggire!"
		,"Your honor prevents you from using a special ability"=>"Sarebbe disonorevole usare un'abilità speciale"
		,"`b`\$You have slain "=>"`b`\$Hai ucciso "
		,array(
			"`#You receive `^"=>"`#Ricevi `^"
			,"You receive `^"=>"Ricevi `^"
			,"`#***Because of the difficult nature of this fight, you are awarded an additional `^"=>"`#***A causa della difficoltà dello scontro, vieni premiato con un bonus di `^"
			,"`#***Because of the simplistic nature of this fight, you are penalized `^"=>"`#***Data la facilità dello scontro, vieni penalizzato di `^"	
			,"`# gold!`n"=>"`# monete!`n"
			,"`# experience!`n"=>"`# esperienza!`n"
			)

		,array(
			"`3 defeated `4"=>"`3 ha sconfitto `4"
			,"`3 by sneaking in to their room in the inn!"=>"`3 intrufolandosi nella sua stanza alla locanda!"
			,"`3 in fair combat in the fields."=>"`3 in un onorevole scontro nei campi."
			)
		,array(
			"`2 attacked you in "=>"`2 ti ha attaccato nella "
			,"`2 with "=>"`2 con "
			,"`2, and defeated you!"=>"`2, e ti ha sconfitto!"
			," `n`nYou noticed "=>" `n`nHai notato che "
			," had a maximum hp of `^"=>" aveva un massimo di punti ferita di `^"
			,"`2 and just before you died %o had `^"=>"`2 e subito prima che tu morissi gliene restavano `^"
			,"`2 remaining."=>"`2."
			," `n`nAs a result, you lost `\$5%`2 of your experience, and `^"=>" `n`nCome risultato, hai perso il `\$5%`2 della tua esperienza e `^"
			,"`2You were successful in "=>"`2Sei stato vittorioso nella "
			,"`2 attacked you in "=>"`2 ti ha attaccato nella "
			,"`2, but you were victorious!`n`nAs a result, you recieved `^"=>"`2, ma lo hai battuto!`n`nDi conseguenza, hai ricevuto `^"
			,"`2 experience and `^"=>"`2 esperienza e `^"
			,"`2 gold."=>"`2 monete."
			," `n`nDon't you think it's time for some revenge?"=>" `n`nNon pensi sia il momento di vendicarsi?"
			)

		,"`2You were killed in "=>"`2Sei stato ucciso nella "
		,"Daily news"=>"Notizie giornaliere"
		,"`6The Inn"=>"`6Locanda"
		,"`@The Fields"=>"`@campagna"
		,array(
			"`@You have encountered `^"=>"`@Incontri `^"
			,"`@ which lunges at you with `%"=>"`@ che ti attacca con `%"
			)
		,"`2Level: `6"=>"`2Livello: `6"
		,"`2`bStart of round:`b`n"=>"`2`bInizio del round:`b`n"
		,array(
			"Hitpoints"=>"Punti ferita"
			,"Soulpoints"=>"Punti anima"
			,"`2's "=>"`2 ha "
			,"`2YOUR "=>"`2TU HAI "
			,"`!You miss "=>"`!Ti manca "
			,"Your lover inspires you to keep safe!"=>"Il tuo amante ti ispira a mantenerti al sicuro!"
			)
		,array(
			"`4You try to hit `^"=>"`4Cerchi di colpire `^"
			,"`)An undead minion hits "=>"`)Un servo non morto colpisce "
			,"`) damage.`n"=>" punti di danno.`n"
			,"`)An undead minion tries to hit "=>"`)Un servo non morto tenta di colpire "
			," but `\$MISSES`)!`n"=>" ma `\$MANCA`)!`n"
			,"`4 but `\$MISS!`n"=>"`4 ma `\$MANCHI!`n"
			,"`4 but are `\$RIPOSTED `4for `\$"=>"`4 ma `\$CONTRATTACCA e ti causa `\$"
			,"`4You hit `^"=>"`4Colpisci `^"
			,"`4 is hit for `^"=>"`4 subisce `^"
			,"`4 hits you for `\$"=>"`4 ti colpisce causando `\$"
			,"`4 tries to hit you but you `^RIPOSTE`4 for `^"=>"`4 cerca di colpirti ma tu `^CONTRATTACCHI`4 e gli causi `^"
			,"`4 for `^"=>"`4 causando `^"
			,"`4 points of damage!`n"=>"`4 punti di danno!`n"
			)
		,"`4You are too busy trying to run away like a cowardly dog to try to fight"=>"`4Sei troppo impegnato a tentare di scappare come un coniglio per combattere"
		,"`4 tries to hit you but `\$MISSES!`n"=>"`4 cerca di colpirti ma `\$MANCA!`n"
		,"`2`bEnd of Round:`b`n"=>"`2`bFine del Round:`b`n"
		,"`&`bYou execute a <font size='+1'>MEGA</font> power move!!!`b`n"=>"`&`bHai usato una <font size='+1'>MEGA</font> mossa speciale!!!`b`n"
		,"`&`bYou execute a DOUBLE power move!!!`b`n"=>"`&`bHai usato una DOPPIA mossa speciale!!!`b`n"
		,"`&`bYou execute a power move!!!`b`0`n"=>"`&`bHai usato una mossa speciale!!!`b`0`n"
		,"`7`bYou execute a minor power move!`b`0`n"=>"`7`bHai usato una piccola mossa speciale!`b`0`n"

		,"Fight"=>"Combatti"
		,"Run"=>"Fuggi"
		,"`bSpecial Abilities`b"=>"`bAbilità Speciali`b"
		,"`\$D`\$ark Arts`n&#149; Skeleton Crew`7 (1/"=>"`\$A`\$rti Oscure`n&#149; Ciurma di Scheletri`7 (1/"
		,"`\$&#149; C`\$urse Spirit`7 (3/"=>"`\$&#149; M`\$aledizione`7 (3/"
		,"`\$&#149; W`\$hither Soul`7 (5/"=>"`\$&#149; A`\$vvizzisci Anima`7 (5/"
		,"`^T`^hieving Skills`n&#149; Insult`7 (1/"=>"`^F`^urto`n&#149; Insulto`7 (1/"
		,"`^&#149; P`^oison Blade`7 (2/"=>"`^&#149; V`^eleno`7 (2/"
		,"`^&#149; H`^idden Attack`7 (3/"=>"`^&#149; A`^ttacco Nascosto`7 (3/"
		,"`^&#149; B`^ackstab`7 (5/"=>"`^&#149; P`^ugnalata alle Spalle`7 (5/"
		,"`%M`%ystical Powers`n&#149; Regeneration`7 (1/"=>"`%P`%oteri Mistici`n&#149; Rigenerazione`7 (1/"
		,"`%&#149; E`%arth Fist`7 (2/"=>"`%&#149; P`%ugno di Terra`7 (2/"
		,"`%&#149; S`%iphon Life`7 (3/"=>"`%&#149; D`%rena Vita`7 (3/"
		,"`%&#149; L`%ightning Aura`7 (5/"=>"`%&#149; A`%ura di Fulmini`7 (5/"
		,"`&&#149;G`&OD MODE"=>"`&&#149;M`&ODALITÀ DIVINA"
		,"`^None`0"=>"`^Nessuna`0"
		,array(
			"`5 has been slain when "=>"`5 è stato ucciso quando "
			," attacked $badguy[creaturename] in "=>"ha attaccato $badguy[creaturename] nella "
			)
		,"`b`&You have been slain by `%"=>"`b`&Sei stato ucciso da `%"
		,"`4All gold on hand has been lost!`n"=>"`4Hai perso tutti i soldi che avevi con te!`n"
		,"`415% of experience has been lost!`n"=>"`4Hai perso il 15% della tua esperienza!`n"
		,"You may begin fighting again tomorrow."=>"Potrai ricominciare a combattere domani."
			,array(
				"'s skill allows them to get the first round of attack!"=>"è tanto abile da attaccare per primo!"
				,"Return to the Village"=>"Torna al villaggio"
				,"she"=>" "
//				,"he" =>" "
				)
		);
		break;
	case "shades.php":
		$replace = array(
		"Land of the Shades"=>"Terra delle Ombre"
		,array(
			"`\$You walk among the dead now, you are a shade.  Everywhere around you are the souls of those who have fallen in battle, in old age, "=>"`\$Ora cammini tra i morti, sei un'ombra. Intorno a te ci sono le anime di coloro che sono morti in battaglia, di vecchiaia, "
			,"and in grievous accidents.  Each bears telltale signs of the means by which they met their end."=>"e in spiacevoli incidenti. Ognuno ha i segni del modo in cui ha incontrato la sua fine."
			,"Their souls whisper their torments, haunting your mind with their despair:`n"=>"Le loro anime sussurrano i loro tormenti, ossessionando la tua mente con la loro disperazione:`n"
			)
		,"The Graveyard"=>"Il Cimitero"
		,"Return to the news"=>"Torna alle notizie"
		,"Superuser Grotto"=>"Grotta del Superutente"
		,"despairs"=>"si dispera"
		,"Despair"=>"Disperati"
		);
		break;
	case "stables.php":
		$replace = array(
//		"Buy a pony (6 gems)"=>"Compra un pony (6 gemme)"
//		,"Buy a gelding (10 gems)"=>"Compra un puledro (10 gemme)"
//		,"Buy a stallion (16 gems)"=>"Compra uno stallone (16 gemme)"
		"Return to the village"=>"Torna al villaggio"
		,"Examine "=>"Esamina "
		,"Merick's Stables"=>"Stalla di Merick"
		,array(
			"Behind the inn, and a little to the left of Pegasus Armor, is as fine a stable as one"=>"Dietro la locanda, e un po' a dsinistra del carrozzone di Pegasus Armor, ci sono delle belle stalle come"
	,"might expect to find in any village."=>" chiunque si aspetterebbe di trovarne in qualunque villaggio."
	,"In it, Merick, a burly looking dwarf tends to various beasts."=>"Qui Merick, un nano dall'aspetto scontroso, si prende cura di vari animali."
			,"You approach, and he whirls around, pointing a pitchfork in your general direction, \"`&Ach,"=>"Ti avvicini e lui si gira, puntando un forcone nella tua direzione, \"`&Ach,"
			,"sorry m'"=>"scusa "
			,", I dinnae hear ya' comin' up on me, an' I thoht"=>", non ti ebbi sentito arrivare, pensassi"
			,"fer sure ye were Cedrik; he what been tryin' to improve on his dwarf tossin' skills.  Naahw, wha'"=>"sicuro fosse Cedrik; cerca sempre di migliorare nel lancio del nano.  Bheeee, che"
		,"can oye do fer ya?`7\" he asks.  "=>"potessi fare per te?`7\" ti domanda.  "
			,"lass"=>"ragazza"
			,"lad"=>"ragazzo"
			)
		,"`7Creature: "=>"`7Creatura: "
		,"`7Description:"=>"`7Descrizione:"
		,array(
			"`7Cost:"=>"`7Costo:"
			,"`7Merick looks at you sorta sideways.  \"`&'Ere, whadday ya think yeer doin'?  Cannae ye see that"=>"`7Merick ti guarda storto.  \"`&Be', cosa pensassi che facessi? Non vedi che questo"
			,"costs"=>"costa"
			,"However, the moment you spot the "=>"Ma, nel momento in cui vedi le "
			,", you find that you're feeling quite a bit better."=>", scopri di sentirti molto meglio."
			,"`7As sad as it is to do so, you give up your precious "=>"`7Per quanto sia triste farlo, consegni il tuo prezioso "
			,"and a lone tear escapes your eye."=>"e ti sfugge una lacrima."
			,"`7You hand over the reins to your "=>"`7Consegni le redini del tuo "
			,"and the purchase price of your new critter, and Merick leads out a fine new "=>"e il prezzo del tuo nuovo animaletto, e Merick porta fuori uno splendido "
			,"`7You hand over the purchase price of your new critter, and Merick leads out a fine"=>"`7Paghi il prezzo del tuo nuovo animaletto, e Merick porta fuori uno splendido "
			,"for you!"=>"per te!"
			,"`n`nMerick offers you"=>"`n`nMerick ti offre"
			,"gems for your "=>"gemme per il tuo "
			,"gold an' "=>"monete e "
			,"and"=>"e"
			,"gold"=>"monete"
			,"gems"=>"gemme"
			)
		,"Sell your "=>"Vendi il tuo "			
		,"Buy this creature"=>"Compra questa creatura"
			,"`7\"`&Ach!  Dinnae y' loyke th' `^"=>"`7\"`&Ach!  Non guardasti il `^"
			,"`& oye sold ye?
		Well, tis a shame fer sure, how'eer all sales are foynal.  Sorry "=>"`& che ti vendetti?
		Beh, peccato, comunque non prendessi merce indietro. Mi spiace "
			,",`7\"
		the little dwarf says."=>",`7\"
		dice il nanetto."
			,"`7\"`&Oye, tha' be a foyne lot of gems fer sure!  Oy've got a fine new `^"=>"`7\"`&Oye, avessi di certo un bel po' di gemme! Ecco un bel `^"
			,"`& fer ye.`7\"
			After taking your gems, he leads out the steed he promised.  You pat it on the nose, "=>"`& nuovo per te.`7\"
			Dopo aver preso le tue gemme, porta fuori il destriero come promesso. Gli dai una pacca sul naso, "
			," and head off to the fields to let your "=>" e vai verso i campi per lasciare libero il tuo "
			," loose."=>"."
			," and head back to the village."=>" e ritorni al villaggio."
			,"It was in this stable that you bought your `2"=>"È in queste stalle che hai comprato il tuo `2"
		,"`7\"`&Ach!  Dinnae y' look at th' proyces?  Perhap ye ha' better get some readin' glasses 'afore ye
			troy to engage in commerce!`7\" suggests the dwarf."=>"`7\"`&Ach!  Non guardassi i prezzi? Forse dovessi comprare degli occhiali prima che
			cercassi di fare spese!`7\" suggerisce il nano."	
		,"`7\"`&Ach, thar dinnae be any such beestie here!`7\" shouts the dwarf!"=>"`7\"`&Ach, non avessi una bestia del genere qui!`7\" urla il nano!"
		,"`7\"`&Aye, tha' be a foyne beestie indeed!`7\" comments the dwarf.`n`n"=>"`7\"`&Aye, questa fosse proprio una bella bestia!`7\" commenta il nano.`n`n"
		,"`n`n`^This horse adds to your attack against monsters!`0`n"=>"`n`n`^Questo cavallo incrementa il tuo attacco contro i mostri!`0`n"		
		);
		break;
	case "superuser.php":
		$replace = array(
		
		);
		break;
	case "taunt.php":
		$replace = array(
		
		);
		break;
	case "train.php":
		$replace = array(
		"Bluspring's Warrior Training"=>"Campo di allenamento"
		,"The sound of conflict surrounds you.  The clang of weapons in grizzly battle inspires your warrior heart. "=>"Il suono della battaglia ti circonda. Il rumore delle armi ispira il tuo cuore guerriero."
		,"Question Master"=>"Parla con il tuo maestro"
		,"`n`nYour master is `^"=>"`n`nIl tuo maestro è `^"
		,"Challenge Master"=>"Sfida il maestro"
		,"Return to the Village"=>"Torna al villaggio"
		,"You think that, perhaps, you've seen enough of your master for today, the lessons you learned earlier prevent you from so willingly "=>"Pensi che, forse, hai visto abbastanza del tuo maestro per oggi. Le lezioni che hai appreso ti impediscono di esporti volontariamente "
		,"subjecting yourself to that sort of humiliation again."=>"ad una simile umiliazione un'altra volta."
		,array(
			"`@You have encountered `^"=>"`@Incontri `^"
			,"`@ which lunges at you with `%"=>"`@ che ti attacca con `%"
			)
		,"`2Level: `6"=>"`2Livello: `6"
		,"`2Level: `6Undead`0`n"=>"`2Livello: `6Non-Morto`0`n"
		,"`2`bStart of round:`b`n"=>"`2`bInizio del round:`b`n"
		,array(
			"Hitpoints"=>"Punti ferita"
			,"Soulpoints"=>"Punti anima"
			,"`2's "=>"`2 ha "
			,"`2YOUR "=>"`2TU HAI "
			)
		,array(
			"You ready your "=>"Prepari i tuoi "
			," and approach `^"=>" e ti avvicini a `^"
			,"`0.`n`nA small crowd of onlookers "=>"`0.`n`nUna piccola folla di spettatori "
			)
		,array(
			"has gathered, and you briefly notice the smiles on their faces, but you feel confident.  You bow before "=>"si è raccolta, e noti di sfuggita i sorrisi sulle lor faccie, ma ti senti fiducioso. Ti inchini di fronte a "
			,"`0, and execute "=>"`0, ed esegui "
			)
		,array(
			"a perfect spin-attack, only to realize that you are holding NOTHING! "=>"un perfetto attacco roteante, solo per coprire che non stai impugnando NULLA! "
			,"`0 stands before you holding your weapon.  "=>"`0 ti sta davanti, con la tua arma in mano.  "
			)
		,array(
			"Meekly you retrieve your "=>"Riprendi umilmente il tuo "
			,", and slink out of the training grounds to the sound of boisterous guffaw's."=>" ed esci dal campo di allenamento seguito dal suono di risate soffocate."
			)
		,array(
			"You approach `^"=>"Ti avvicini timidamente a  `^"
			,"`0 timidly and inquire as to your standing in his class."=>"`0 e gli domandi come stai andando nel suo corso."
			)
		,"`0 says, \"Gee, your muscles are getting bigger than mine...\""=>"`0 dice, \"Oh, i tuoi muscoli stanno diventando più grossi dei miei...\""
		,array(
			"`0 states that you will need `%"=>"`0 afferma che ti servono altri `%"
			,"`0 more experience before you are ready to challenge him in battle."=>"`0 punti di esperienza prima di essere pronto a sfidarlo in battaglia."
			)
		,"Fight Your Master"=>"Combatti il tuo maestro"
		,array(
			"`0 has heard of your prowess as a warrior, and heard of rumors that you think
		you are so much more powerful than him that you don't even need to fight him to prove anything.  His ego is
		understandably bruised, and so he has come to find you.  "=>"`0 ha saputo della tua prodezza di guerriero, e sentito dire  heard che pensi
		di essere tanto più potente di lui che non hai nemmeno bisogno di batterti con lui per provarlo. Il suo ego è
		comprensibilmente ferito, e perciò è venuto a cercarti.  "
			,"`0 Demands an immediate
		battle from you, and your own pride prevents you from refusing his demand."=>"`0 Esige una sfida immediata con te, 
		ed il tuo stesso orgoglio ti impedisce di rifiutare la richiesta."
			)
		,"`n`nBeing a fair person, your master gives you a healing potion before the fight begins."=>"`n`nEssendo una persona leale, il tuo maestro ti da una pozione guaritrice prima di iniziare la battaglia."
		,array(
			"`3 was hunted down by their master `^"=>"`3 è stato inseguito dal suo maestro `^"
			,"`3 for being truant."=>"`3 per aver marinato la scuola."
			)
		,"`\$Your honor prevents you from running from this conflict!`0"=>"`\$Sarebeb disonorevole fuggire da questo scontro!`0"
		,"`&Your honor prevents you from using any special abilities!`0"=>"`&Sarebbe disonorevole usare abilità speciali!`0"
		,"`b`\$You have defeated "=>"`b`\$Hai sconfitto "
		,"`#You advance to level `^"=>"`#Avanzi al livello `^"
		,"Your maximum hitpoints are now `^"=>"Il tuo massimo di punti ferita ora è `^"
		,"You gain an attack point!`n"=>"Guadagni un punto di attacco!`n"
		,"You gain a defense point!`n"=>"Guadagni un punto di difesa!`n"
		,"You have a new master.`n"=>"Ora hai un nuovo maestro.`n"
			,array(
				"`nYou gain a level in `&"=>"`nAcquisisci un livello in `&"
				,"Dark Arts"=>"Arti Oscure"
				,"Mystical Powers"=>"Poteri Mistici"
				,"Thievery"=>"Furto"
				,"`# to "=>"`# a "
				)
		,"you gain an extra use point!`n"=>"guadagni un punto di utilizzo!`n"
		,array(
			"`3 has defeated "=>"`3 ha sconfitto il "
			,"You stroll in to the battle grounds.  Younger warriors huddle together and point as you pass by.  "=>"Vai sul campo di battaglia. I guerrieri più giovani si riuniscono in gruppo e ti indicano mentre passi.  "
			,"You know this place well.  Bluspring hails you, and you grasp her hand firmly.  There is nothing "=>"Conosci bene questo posto. Non c'è rimasto nulla "
			,"left for you here but memories.  You remain a moment longer, and look at the warriors in training "=>"per te qui a parte i ricordi. Resti ancora un momento, a guardare gli altri guerrieri che si allenano, "
			,"before you turn to return to the village."=>"prima di voltarti per tornare al villaggio."
			,"Return to the village"=>"Torna al villaggio"
			,"her"=>"suo"
			,"his"=>"suo"
			," master, `%"=>" maestro, `%"
			,"`3 to advance to level `^"=>"`3 per avanzare al livello `^"
			,"`3 on "=>"`3 nel "
			,"`3 day!!"=>"`3 giorno!!"
			)
		,"Daily news"=>"Notizie giornaliere"
		,array(
			"`5 has challenged their master, "=>"`5 ha sfidato il suo maestro, "
			," and lost!`n"=>" ed ha perso!`n"
			)
		,"`&`bYou have been defeated by `%"=>"`&`bSei stato sconfitto da `%"
		,"`\$ halts just before delivering the final blow, and instead extends a hand to help you to your feet, and hands you a complimentary healing potion.`n"=>"`\$ si ferma un attimo prima di darti il colpo finale, e invece allunga una mano per aiutarti ad alzarti, e ti regala una pozione guaritrice.`n"
		,array(
			"`4You try to hit `^"=>"`4Cerchi di colpire `^"
			,"`)An undead minion hits "=>"`)Un servo non morto colpisce "
			,"`) damage.`n"=>" punti di danno.`n"
			,"`)An undead minion tries to hit "=>"`)Un servo non morto tenta di colpire "
			," but `\$MISSES`)!`n"=>" ma `\$MANCA`)!`n"
			,"`4 but `\$MISS!`n"=>"`4 ma `\$MANCHI!`n"
			,"`4 but are `\$RIPOSTED `4for `\$"=>"`4 ma `\$CONTRATTACCA e ti causa `\$"
			,"`4You hit `^"=>"`4Colpisci `^"
			,"`4 is hit for `^"=>"`4 subisce `^"
			,"`4 hits you for `\$"=>"`4 ti colpisce causando `\$"
			,"`4 tries to hit you but you `^RIPOSTE`4 for `^"=>"`4 cerca di colpirti ma tu `^CONTRATTACCHI`4 e gli causi `^"
			,"`4 for `^"=>"`4 causando `^"
			,"`4 points of damage!`n"=>"`4 punti di danno!`n"
			)
		,"`4You are too busy trying to run away like a cowardly dog to try to fight"=>"`4Sei troppo impegnato a tentare di scappare come un coniglio per combattere"
		,"`4 tries to hit you but `\$MISSES!`n"=>"`4 cerca di colpirti ma `\$MANCA!`n"
		,"`2`bEnd of Round:`b`n"=>"`2`bFine del Round:`b`n"
		,"`&`bYou execute a <font size='+1'>MEGA</font> power move!!!`b`n"=>"`&`bHai usato una <font size='+1'>MEGA</font> mossa speciale!!!`b`n"
		,"`&`bYou execute a DOUBLE power move!!!`b`n"=>"`&`bHai usato una DOPPIA mossa speciale!!!`b`n"
		,"`&`bYou execute a power move!!!`b`0`n"=>"`&`bHai usato una mossa speciale!!!`b`0`n"
		,"`7`bYou execute a minor power move!`b`0`n"=>"`7`bHai usato una piccola mossa speciale!`b`0`n"

		,"Fight"=>"Combatti"
		,"Run"=>"Fuggi"
		,"`bSpecial Abilities`b"=>"`bAbilità Speciali`b"
		,"`\$D`\$ark Arts`n&#149; Skeleton Crew`7 (1/"=>"`\$A`\$rti Oscure`n&#149; Ciurma di Scheletri`7 (1/"
		,"`\$&#149; C`\$urse Spirit`7 (3/"=>"`\$&#149; M`\$aledizione`7 (3/"
		,"`\$&#149; W`\$hither Soul`7 (5/"=>"`\$&#149; A`\$vvizzisci Anima`7 (5/"
		,"`^T`^hieving Skills`n&#149; Insult`7 (1/"=>"`^F`^urto`n&#149; Insulto`7 (1/"
		,"`^&#149; P`^oison Blade`7 (2/"=>"`^&#149; V`^eleno`7 (2/"
		,"`^&#149; H`^idden Attack`7 (3/"=>"`^&#149; A`^ttacco Nascosto`7 (3/"
		,"`^&#149; B`^ackstab`7 (5/"=>"`^&#149; P`^ugnalata alle Spalle`7 (5/"
		,"`%M`%ystical Powers`n&#149; Regeneration`7 (1/"=>"`%P`%oteri Mistici`n&#149; Rigenerazione`7 (1/"
		,"`%&#149; E`%arth Fist`7 (2/"=>"`%&#149; P`%ugno di Terra`7 (2/"
		,"`%&#149; S`%iphon Life`7 (3/"=>"`%&#149; D`%rena Vita`7 (3/"
		,"`%&#149; L`%ightning Aura`7 (5/"=>"`%&#149; A`%ura di Fulmini`7 (5/"
		,"`&&#149;G`&OD MODE"=>"`&&#149;M`&ODALITÀ DIVINA"
		,"`^None`0"=>"`^Nessuna`0"

		);
		break;
	case "user.php":
		$replace = array(
		
		);
		break;
	case "viewpetition.php":
		$replace = array(
		
		);
		break;
	case "weaponeditor.php":
		$replace = array(
		
		);
		break;
	case "weapons.php":
		$replace = array(
		"Return to the village"=>"Torna al villaggio"
		,"Peruse Weapons"=>"Esamina le armi"
		,"MightyE's Weapons"=>"Armeria di MightyE"
		,array(
			"`!MightyE `7stands behind a counter and appears to pay little attention to you as you enter, "=>"`!MightyE `7sta in piedi dietro il suo banco, e non ti considera nemmeno quando entri , "
			,"but you know from experience that he has his eye on every move you make.  He may be a humble "=>"ma sai dall'esperienza che lui osserva ogni tuo movimento. Può anche essere un umile "
			,"weapons merchant, but he still carries himself with the grace of a man who has used his weapons "=>"mercante di armi, ma sai che in passato è stato un uomo che ha usato le sue armi "
			,"to kill mightier ".($session[user][gender]?"women":"men")." than you.`n`n"=>"per uccidere ".($session[user][gender]?"donne":"uomini")." più valorosi di te.`n`n"
			,"The massive hilt of a claymore protrudes above his shoulder; its gleam in the torch light not "=>"L'elsa massiccia di uno spadone spunta da dietro la sua spalla, e brilla alla luce della torcia "
			,"much brighter than the gleam off of `!MightyE's`7 bald forehead, kept shaved mostly as a strategic "=>"quasi intensamente come la testa calva di `!MightyE`7, che sembra tenuta rasa per ottenere un vantaggio strategico. "
			,"advantage, but in no small part because nature insisted that some level of baldness was necessary. "=>"Ma in questo caso la natura ha molto insistito per concedere questo VANTAGGIO." 
			,"`n`n`!MightyE`7 finally nods to you, stroking his goatee and looking like he wished he could "=>"`n`n`!MightyE`7 alla fine ti fa un cenno con il capo, si accarezza il pizzetto, e capisce di avere forse l'opportunità "
			,"have an opportunity to use one of these weapons."=>"di usare una di quelle armi."
			)
		,array(	
			"`7You stroll up the counter and try your best to look like you know what most of these contraptions do. "=>"`7Cammini avanti e indietro osservando le armie e facendo finta di esaminarle."
			,"`!MightyE`7 looks at you and says, \"`#I'll give you `^"=>"`!MightyE`7 ti guarda e dice, \"`#Ti darò `^"
			,"tradein value for your `5"=>"monete di sconto in cambio del tuo `5"
			,"`#.  Just click on the weapon you wish to buy, what ever 'click' means`7,\" and "=>"`#. Basta che clicchi sull'arma che vuoi acquistare, qualunque cosa significhi 'cliccare' `7,\" e "
			,"looks utterly confused.  He stands there a few seconds, snapping his fingers and wondering if that is what "=>"appare confuso. Resta fermo qualche secondo a schioccare le dita, chiedendosi se è questo"
			,"is meant by \"click,\" before returning to his work: standing there and looking good."=>"che si intende per \"cliccare\", prima di tornarsene al suo lavoro: star lì con l'aria soddisfatta."
			)
		,"`!MightyE`7 looks at you, confused for a second, then realizes that you've apparently taken one too many bonks on the head, and nods and smiles."=>"`!MightyE`7 ti guarda confuso per un secondo, poi capisce che evidentemente devi aver preso troppe botte in testa, così annuisce e ti sorride."
		,"Try again?"=>"Ritenti?"
		,array(
			"Waiting until `!MightyE`7 looks away, you reach carefully for the `5"=>"Aspetti che `!MightyE`7 si distragga e raggiungi attentamente il `5"
			,", which you silently remove from the rack upon which "=>", che rimuovi con cura dalla rastrelliera su cui "
			)
		,"it sits.  Secure in your theft, you turn around and head for the door, swiftly, quietly, like a ninja, only to discover that upon reaching "=>"si trova. Sicuro della riuscita del tuo furto, ti volti e vai verso la porta velocemente, silenziosamente, come un ninja, solo per scoprire dopo averla raggiunta "
		,"the door, the ominous `!MightyE`7 stands, blocking your exit.  You execute a flying kick.  Mid flight, you hear the \"SHING\" of a sword "=>"che il massiccio `!MightyE`7 le sta proprio davanti, bloccandoti l'uscita. Esegui un calcio volante. A mezz'aria senti lo \"SHING\" di una spada "
		,"leaving its sheath.... your foot is gone.  You land on your stump, and `!MightyE`7 stands in the doorway, claymore once again in its back holster, with "=>"che viene estratta dal suo fodero... il tuo piede è andato. Atterri sul moncherino e `!MightyE`7 sta davanti all'uscita, con lo spadone nuovamente infilato nel fodero dietro la schiena, senza "
		,"no sign that it had been used, his arms folded menacingly across his burly chest.  \"`#Perhaps you'd like to pay for that?`7\" is all he has "=>"alcun indizio che riveli che è stato usato, con le braccia incrociate sul petto muscolos. \"`#Forse ti andrebbe di pagare?`7\" è tutto quello che "
		,"to say as you collapse at his feet, lifeblood staining the planks under your remaining foot."=>"ti dice mentre crolli al suolo, con il sangue che irrora le tavole del pavimento sotto il piede che ti è rimasto."
		,"`b`&You have been slain by `!MightyE`&!!!`n"=>"`b`&Sei stato ucciso da `!MightyE`&!!!`n"
		,"`4All gold on hand has been lost!`n"=>"`4Hai perso tutto il denaro che avevi con te!`n"
		,"`410% of experience has been lost!`n"=>"`4Hai perso il 10% della tua esperienza!`n"
		,"You may begin fighting again tomorrow."=>"Potrai ricominciare a combattere domani."
		,"Daily news"=>"Notizie giornaliere"
		,"`5 has been slain for trying to steal from `!MightyE`5's Weapons Shop."=>"`5 è stato ucciso mentre tentava di rubare dall'armeria di `!MightyE`5."
		,array(
			"`!MightyE`7 takes your `5"=>"`!MightyE`7 prende il tuo `5"
			,"`7 and promptly puts a price on it, setting it out for display with the rest of his weapons. "=>"`7 e rapidamente gli mette un cartellino con il prezzo e lo mette in vendita assieme al resto delle sue armi. "
			)
		,array(
			"`n`nIn return, he hands you a shiny new `5"=>"`n`nIn cambio ti da un `5"
			,"`7 which you swoosh around the room, nearly taking off `!MightyE`7's head, which he "=>"`7 nuovo fiammante che tu rotei nella stanza, quasi staccando la testa di `!MightyE`7, che "
			,"deftly ducks; you're not the first person to exuberantly try out a new weapon."=>"si abbassa prontamente: non sei il primo che prova una nuova arma con un po' troppa esuberanza."
			)
		,"Return to the village"=>"Torna al villaggio"
		);
		break;

		case "outhouse.php":
		$replace=array(
		"H?Healer's Hut"=>"Guaritore"
		,"Other"=>"Altro"
		,"`2The village has two outhouses, which it keeps way out here in the forest because of the "=>"`2Il villaggio ha due bagni pubblici, che tiene nella foresta a causa del loro "
		,array(
			"warding effect of their smell on creatures.`n`nIn typical caste style, there is a priviliged "=>"effetto di tenere lontane le creature ostili con il loro odore.`n`nIn tipico stile di casta, c'è un bagno privilegiato "
			,"warding effect of their smell on creatures."=>"effetto di tenere lontane le creature ostili con il loro odore."
			)
		,"outhouse, and an underpriviliged outhouse.  The choice is yours!`0`n`n"=>"ed uno di seconda categoria. La scelta è tua!`0`n`n"
		,array(
			"`7You pay your "=>"`7Paghi le tue "
			," gold to the Toilet Gnome for the privilege of using the paid outhouse.`n"=>" monete allo Gnomo del Bagno per il privilegio di usare la toilette a pagamento.`n"
			)
		,"This is the cleanest outhouse in the land!`n"=>"Questo è il bagno più pulito della regione!`n"
		,"`&Aren't you the lucky one to find a gem there by the doorway!`0`n"=>"`&Non sei fortunato a trovare una gemma accanto alla porta?`0`n"
		,"The Toilet Paper Gnome tells you if you need anything, just ask`n"=>"Lo Gnomo della Carta Igienica ti dice che se ti serve qualcosa devi solo chiedere`n"
		,"Wash your hands"=>"Lavati le mani"
		,"Leave"=>"Esci"
		,"`2The smell is so strong your eyes tear up and your nose hair curls!`n"=>"`2L'odore è così forte che ti lacrimano gli occhi e ti si arricciano i peli del naso!`n"
		,"After blowing his nose with it, the Toilet Paper Gnome gives you 1 sheet of single-ply TP to use.`n"=>"Dopo essercisi soffiato il naso, lo Gnomo della Carta Igienica ti da un quadratino di carta igienica da usare.`n"
		,"After looking at the stuff covering his hands, you think you might not want to use it.`n`n"=>"Dopo aver dato un'occhiata alla roba sulle sue mani, pensi che forse non hai molta voglia di usarla.`n`n"
		,array(
			"While "=>"Mentre stai "
			,"squating"=>"accosciata sopra il"
			,"standing"=>"in piedi davanti al"
			," over the big hole"=>" grosso buco al"
	 		,"in the middle of the room with the TP Gnome observing you closeley; you almost slip in.`n"=>" centro della stanza, con lo Gnomo che ti osserva attentamente, per poco non ci cadi dentro.`n"
			)
		,"But you go ahead and take care of busines as fast as you can, you can only hold your breath so long.`n"=>"Ma vai avanti e fai quel che devi fare nel minor tempo possibile, non puoi trattenere il respiro in eterno.`n"
		,"Wash Stand"=>"Lavatoio"
		,array(
			"`2Washing your hands is always a good thing.  You tidy up, straighten your"=>"`2Lavarsi le mani è sempre un bene. Ti sistemi, aggiusti la tua"
			," in your reflection in the water, and head on your way.`0`n"=>" guardando il tuo riflesso nell'acqua e ti incammini.`0`n"
			)
		,"`^The Wash Room Fairy blesses you!`n"=>"`^La Fatina del Lavatoio ti benedice!`n"
		,array(
			"`7You receive `^"=>"`7Ricevi `^"
			,"`7gold for being sanitary and clean!`0`n"=>"`7monete per essere stato igienista e pulito!`0`n"
			)
		,"`&You gained a turn!`0`n"=>"`&Hai guadagnato un turno!`0`n"
		,"`&Leaving the outhouse, you feel a little more sober!`n`0"=>"`&Lasciando i bagni, ti senti un po' più sobrio!`n`0"
		,array(
			"`&You notice a small bag containing "=>"`&Noti una piccola borsa contenente "
			," `7gold that someone left by the washstand.`0"=>" `7monete che qualcuno deve aver dimenticato accanto al lavabo.`0"
			)
		,"Stinky Hands"=>"Mani Puzzolenti"
		,"`2Your hands are soiled and real stinky!`n"=>"`2Le tue mani sono sporche e puzzolenti!`n"
		,"Didn't your mother teach you any better?`n"=>"È questo che ti ha insegnato tua madre?`n"
		,array(
			"`nThe Toilet Paper Gnome has thrown you to the slimy, filthy floor and extracted "=>"`nLo Gnomo della Carta Igienica ti ha fatto cadere sul lurido pavimento e ti ha preso "
			," gold piece"=>" pezzi d'oro"
			," from you due to your slovenliness!`n"=>" per la tua sciatteria!`n"
			)
		,"Aren't you glad an embarassing moment like this isn't in the news?`n"=>"Non sei lieto che un momento così imbarazzante non sia nelle notizie?`n"
		,"Toilets"=>"Bagni"
		,array(
			"Private Toilet: "=>"Toilette Privata: "
			,"`2The Private Toilet costs `^"=>"`2Usare la Toilette privata costa `^"
			,"`2gold. Looks like you are going to have to hold it or use the Public Toilet!"=>"`2monete. Sembra che dovrai trattenere o usare il Bagno Pubblico!"
			,"Private Toilet"=>"Toilette Privata"
			,"gold"=>"monete"
			,"Public Toilet!"=>"Bagno Pubblico!"
			)
		,"Public Toilet:  (free)"=>"Bagno Pubblico:  (gratuito)"
		,"Hold it"=>"Trattieni"
		,array(
			"The Outhouses are closed for repairs.`nYou will have to hold it till tomorrow!"=>"I Bagni sono chiusi per riparazioni.`nDovrai trattenerla fino a domani!"
			,"The Outhouses"=>"I Bagni Pubblici"
			)
		,"As you draw close to the Outhouses, you realize that you simply don't think you can bear the smell of another visit to the Outhouses today."=>"Avvicinandoti ai Bagni, ti rendi conto che non riusciresti a sopportare l'odore di un'altra visita per oggi."
		,"You really don't have anything left to relieve today!"=>"Oggi non hai davvero nessuno stimolo!"
		,"`n`n`7You return to the forest.`0"=>"`n`n`7Torni alla foresta.`0"
			,"L?Look for Something to kill"=>"Trova qualcosa da uccidere"
			,"S?Go Slumming"=>"Visita i bassifondi"
			,"T?Go Thrillseeking"=>"Vai in cerca di brividi"
			,array(
				"D?Take "=>"Porta "
				,"to Dark Horse Tavern"=>"alla Taverna del Cavallo Nero"
				)
			,"Return to the Village"=>"Torna al villaggio"
			,"Seek out the Green Dragon"=>"Cerca il Drago Verde"
			,"Other"=>"Altro"
			,"The Outhouse"=>"I Bagni Pubblici"
		,array(
			"He"=>"Lui"
			,"She"=>"Lei"
			," politely turns "=>" ti volta educatamente "
			,"her"=>" "
			,"his"=>" "
			," back to you and finishes cleaning the wash stand.`n"=>" le spalle e continua a lavare i bagni.`n"
			)
		);
		break;
		
	case "rock.php":
	$replace=array(
		"`n`n`4Something in you compels you to examine the curious rock.  Some dark magic, locked up in age old horrors."=>"`n`n`4Qualcosa ti spinge ad esaminare la roccia curiosa. Una qualche magia oscura, imprigionata in orrori antichi di ere."
		,"`n`nWhen you arrive at the rock, an old scar on your arm begins to throb in succession with a mysterious light that "=>"`n`nQuando arrivi alla roccia, una vecchia cicatrice sul tuo braccio inizia a pulsare a ritmo con una misteriosa luce che "
		,"now seems to come from the rock.  As you stare at it, the rock shimmers, shaking off an illusion.  You realize that this is "=>"ora sembra provenire dalla roccia.  Mentre la fissi, la roccia brilla, scrollandosi di dosso un'illusione. Ti rendi conto che questa è "
		,"more than a rock.  It is, in fact, a doorway, and over the threshold, you see others, bearing an identical scar to yours.  It "=>"più che una semplice roccia. In effetti è una porta, ed oltre l'uscio vedi altre persone che hanno cicatrici identiche alla tua.  Ti "
		,"somehow reminds you of the head of one of the great serpents from legend.  You have discovered The Veteran's Club."=>"ricorda in qualche modo uno dei grandi serpenti delle leggende. Hai scoperto il Club dei Veterani"
		,"The Veteran's Club"=>"Club dei Veterani"
		,array(
			"Boast here"=>"Vantati qui"
			,"boasts"=>"si vanta"
			)
		,"Curious looking rock"=>"Roccia curiosa"
		,"You approach the curious looking rock.  After staring, and looking at it for a little while, it continues to look just like a curious looking rock.`n`n"=>"Ti avvicini alla roccia curiosa. Dopo averla fissata e guardata per un po', continua a sembrare una roccia curiosa e basta.`n`n"
		,"Bored, you decide to return to the village."=>"Annoiato, decidi di tornare al villaggio."
		,"Return to the village"=>"Torna al villaggio"
		);
		break;

	case "gardens.php":
	$replace=array(
		"The Gardens"=>"I Giardini"
		,"`n`n You walk through a gate and on to one of the many winding paths that makes its way through the well-tended gardens.  From the flowerbeds that bloom even in darkest winter, to the hedges whose shadows promise forbidden secrets, these gardens provide a refuge for those seeking out the Green Dragon; a place where they can forget their troubles for a while and just relax."=>"`n`n Oltrepassi un cancello e procedi su uno di tanti sentieri tortuosi che si fanno strada attraverso i ben curati giardini. Dalle aiuole che fioriscono anche nei più scuri inverni alle siepi le cui ombre promettono segreti proibiti, questi giardini forniscono un rifugio a coloro che cercano il Drago Verde; un luogo in cui possono dimenticare per un po' i loro problemi e rilassarsi."
		,"`n`nOne of the fairies buzzing about the garden flies up to remind you that the garden is a place for roleplaying, and to confine out-of-character comments to the other areas of the game."=>"`n`nUna delle fate che svolazzano nei giardini ti si avvicina per ricordarti che i giardini sono un posto per giocare di ruolo, e che i commenti fuori dal personaggio vanno confinati in altre aree del gioco."
		,array(
			"Whisper here"=>"Sussurra qui"
			,"whispers"=>"sussurra"
			)
		,"Return to the village"=>"Torna al villaggio"
		);
		break;

	}
	return replacer($input,$replace);
}

?>
