<?php
require_once "common.php";
//--------------------------------------------------------------------------------------------------------
//| Written by:  rax (rax@sourceforge.net)
//| Get the latest code at: logd.voyageurperdu.com
//|
//| About:   The name came from a Johnny Cash song off the 1968 album "At Folsom Prison"
//|
//| Description:
//|            This mod allows you to marry other players.  The proposition takes place via mail, with user
//|     being ask to meet the other player at the chapel.  From here the player can either accept or
//|     decline the proposition.   If the feelings are mutual, then wedding bells ring, the db is
//|     updated and the rest is history.
//|            If players want to get a divorce they can go see XIUS the divorce lawyer.   Divorce does not
//|     require the consensus of both parties.
//|
//|    Please read the readme.txt file available in the zip file on sourceforge.
//| http://sourceforge.net/tracker/?group_id=76499&atid=547281
//|
//--------------------------------------------------------------------------------------------------------

//Settings for the Greystone Chapel
define(ALLOWMARRIAGE,      true);   //Allows marriage in this module
define(ALLOWDIVORCE,       true);   //Allows users to get a divorce
define(DIVORCEVIOLET,      true);   //Allows users to divorce Violet / Seth
define(ALLOWGAYMARRIAGE,  false);   //Allows same-sex marriages

define(ALLOWCONTRIB,       true);   //Allows users to get blessing
define(ALLOWPURGATORY,     true);   //Allows users to pay to get someone out of the shades
define(ALLOWNAMECHANGE,    true);   //Allows user to change name
define(ALLOWANNOUNCEMENT,  true);   //Allows user to post announcement
define(ALLOWSTEAL,         true);   //Allows user to steal from church

define(ALLOWNPC,           true);   //Allows Chapel to hook into Brother Thomas NPC
define(THOMASID,             58);   //Brother Thomas NPC acctid


page_header("The Greystone Chapel");
addnav("In the church");

output("<span style='color: #000000'>",true);
$sql = "SELECT name,acctid,marriedto,date FROM accounts, marriage WHERE marriage.proposed=".$session[user]['acctid']." AND accounts.acctid = marriage.suitor";
$result = db_query($sql);

if($_GET[op]!="give"){
    if (db_num_rows($result)>=1 && $session[user]['marriedto'] == 0 && ALLOWMARRIAGE){
        addnav("View your proposals", "chapel.php?op=view");
    }

    if ($session[user]['marriedto'] == 0 && ALLOWMARRIAGE){
        addnav("Ask someone to marry", "chapel.php?op=prop");
    }

    if(ALLOWCONTRIB)   addnav("Give to the church", "chapel.php?op=give");
    if(ALLOWSTEAL)     addnav("Steal from church", "chapel.php?op=steal");
    if(ALLOWMARRIAGE)  addnav("View Marriage List", "chapel.php?op=marriedlist");
    if(ALLOWPURGATORY) addnav("Purgatory", "chapel.php?op=purgatory");

}

if ($_GET[op]==""){
    output("`c`7`bThe Greystone Chapel`b`c");
    output("`n`n`7As you walk along in the cool night breeze, you happen upon a little white chapel tucked somewhere between `%`bPegasus Armor`b`7 and `%`bThe Boar's Head Inn`b`7.   Dim candlelight emanates from the quaint little stone chapel, and curious you duck your head inside.   Once inside you are greeted by a small man in a black robe with a white collar.`n`n`&\"Welcome to the Greystone Chapel, my name is `4`bBrother Thomas`b`&.   Is there anything I can do for you?\"`7`n`n");

    //output("`^Welcome to RAX's Electric Light Chapel, where you won't have to sell your horse to get hitched.");
}else if($_GET[op]=="view") {
    output("`c`7`bThe Greystone Chapel`b`c");
    $sql = "SELECT name,acctid,marriedto,date FROM accounts, marriage WHERE marriage.proposed=".$session[user]['acctid']." AND accounts.acctid = marriage.suitor ORDER BY accounts.charm DESC";
    $result = db_query($sql);
    if (db_num_rows($result)>=1){
        output("`n`n`n`^`bYou have received marriage proposals from the following players:`b", true);
        output("`n`n<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style=\"width:350px\">From</td><td>Date</td><td colspan=2>&nbsp;</td></tr>",true);
        $i=0;
        while($row = db_fetch_assoc($result)) {
            output("<tr class='".($i%2?"trlight":"trdark")."'><td>".$row['name']."</td><td>`^".$row['date']."</td><td><a href=\"chapel.php?op=answeris&userid=".$row['acctid']."&answer=Yes\">`7[`b`@Accept`b`7]</a></td><td><a href=\"chapel.php?op=answeris&userid=".$row['acctid']."&answer=No\">`7[`b`4Reject`b`7]</a></td></tr>", true);
            addnav("","chapel.php?op=answeris&userid=".$row['acctid']."&answer=Yes");
            addnav("","chapel.php?op=answeris&userid=".$row['acctid']."&answer=No");
            $i++;
        }
        output("</table>`n`n",true);
    }else {
        output("`n`n`n`^`bYou haven't received any marriage proposals.`b", true);
    }
}else if ($_GET[op]=="prop"){
    output("`c`7`bThe Greystone Chapel`b`c");
    if($session[user]['marriedto'] > 0)
        output("`n`n`4`bBrother Thomas`b`7 looks at you and sighs. `&\"The church does not condone polygamy.   I'm afraid you can't get married again.  Of course, for a small fee I'm sure `#XIUS`7 the divorce attorney can help you out across the street.\"`7 he admits.");
    else {
        output("`n`n`&\"Aaah, looking to get married.  Splendid!  I can have one of my accolytes deliever the message to your special sweetheart.\"`7`4`bBrother Thomas`b`7 says. `&\"Who would you like to ask to marry you?\"`7`n`n");
        if(ALLOWGAYMARRIAGE)
            $sql="SELECT acctid, name, charm, race, sex, login FROM accounts WHERE marriedto = 0 ORDER BY accounts.charm DESC";
        else
            $sql="SELECT acctid, name, charm, race, sex, login FROM accounts WHERE marriedto = 0 AND sex !=".$session[user]['sex']." ORDER BY accounts.charm DESC";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td style=\"width:350px\">Name</td><td style=\"width:150px\">Looks</td><td>&nbsp;</td></tr>",true);
        $countrow = db_num_rows($result);
        for ($i=0; $i<$countrow; $i++){
        //for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            switch($row[charm]){
            case -3:
            case -2:
            case -1:
            case 0:
                $looks = "`4Coyote ugly";
                break;
            case 1:
            case 2:
            case 3:
                $looks = "`#Double bagger";
                break;
            case 4:
            case 5:
            case 6:
                $looks = "`&Paper sack";
                break;
            case 7:
            case 8:
            case 9:
                $looks = "`7Average";
                break;
            case 10:
            case 11:
            case 12:
                $looks = "`^Cute";
                break;
            case 13:
            case 14:
            case 15:
                if($row[sex] == 1)
                    $looks = "`5Pretty";
                else
                    $looks = "`5Handsome";
                break;
            case 16:
            case 17:
            case 18:
                $looks = "`3Beautiful";
                break;
            case 19:
            case 20:
            case 21:
                $looks = "`4HOT!!";
                break;
            case 22:
            case 23:
            case 24:
                $looks = "`7Smokin `4HOT!!";
                break;
            case 25:
            case 26:
            case 27:
                $looks = "`@Supersexy";
                break;
            case 28:
            case 29:
            case 30:
                if($row[sex] == 1)
                    $looks = "`^Girl of your Dreams";
                else
                    $looks = "`^Guy of your Dreams";
                break;
            default:
                $looks = "`2Fairest in the land";
        }

            output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
            output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
            output("<a href='bio.php?char=".rawurlencode($row['login'])."&ret=cchapel.php?op=prop'>",true);
            output("$row[name]</a></td><td>$looks</td><td>[<a href='chapel.php?op=findname&player=".rawurlencode($row[acctid])."'>Propose</a> ]</td></tr>",true);    addnav("","bio.php?char=".rawurlencode($row['login'])."&ret=cchapel.php?op=prop");
            addnav("","chapel.php?op=findname&player=".rawurlencode($row[acctid])."");
        }
        output("</table>",true);
    }

}else if ($_GET[op]=="findname"){
    output("`c`7`bThe Greystone Chapel`b`c");
    $string="%";
    for ($x=0;$x<strlen($_POST['to']);$x++){
        $string .= substr($_POST['to'],$x,1)."%";
    }
    $sql = "SELECT name,login,acctid,marriedto, charm FROM accounts WHERE acctid = ".$_GET[player]." ";
    $result = db_query($sql);
    if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            $charmdiff = $session[user]['charm'] - $row['charm'];
            if($charmdiff > 10 && $session[user]['charm'] < 19 || $row['charm'] < 19){
                output("`n`n`&\"I don't think ".$row['name']."`& is quite up to your standards.  I think you can do better.\"`7 `4`bBrother Thomas`b`7 suggests.`n",true);
                output("`n`n`c`b`^(Your charm is too high to marry this player)`7`b`c", true);
            }else if($charmdiff < 0){
                output("`n`n`&\"You don't have a chance with ".$row['name']."`&!  Perhaps you should lower your expectations.\"`7 `4`bBrother Thomas`b`7 suggests.`n",true);
                output("`n`n`c`b`^(Your charm is too low to marry this player)`7`b`c", true);
            }else{
                switch($row[charm]){
                    case -3:
                    case -2:
                    case -1:
                    case 0:
                        $looks = "`n`n`7".$row['name']."`7 is so ugly `4you want to knaw off your own arm to get away.`7`n`n";
                        break;
                    case 1:
                    case 2:
                    case 3:
                        $looks = "`n`n`7You would need to put `#two brown paper bags`7 over ".$row['name']."'s`7 head if you wanted to be in the same room with them.`n`n";
                        break;
                    case 4:
                    case 5:
                    case 6:
                        $looks = "`n`n`7You would need to put a `&brown paper bag`7 over ".$row['name']."'s`7 head if you wanted to be in the same room with them.`n`n";
                        break;
                    case 7:
                    case 8:
                    case 9:
                        $looks = "`n`n`7".$row['name']."`7 is `baverage`b, but nothing to write home about.`n`n";
                        break;
                    case 10:
                    case 11:
                    case 12:
                        $looks = "`n`n`7".$row['name']."`7 is `^cute`7, with a face you could learn to love.`n`n";
                        break;
                    case 13:
                    case 14:
                    case 15:
                        if($row[sex] == 1)
                            $looks = "`n`n`7".$row['name']."`7 is `5pretty`7 enough to take a picture (if you knew what that was)`n`n";
                        else
                            $looks = "`n`n`7".$row['name']."`7 is `5handsome`7 enough to take a picture (if you knew what that was)`n`n";
                        break;
                    case 16:
                    case 17:
                    case 18:
                        $looks = "`n`n`7".$row['name']."`7 is downright `3beautiful`7.`n`n";
                        break;
                        case 19:
                        case 20:
                        case 21:
                            $looks = "`n`n`7".$row['name']."`7 is probably the `4HOTTEST`7 warrior you've seen in a long time!!";
                            break;
                        case 22:
                        case 23:
                        case 24:
                            $looks = "`n`n`7".$row['name']."`7 is Smokin `4HOT!!`7  You'd better call the fire department because they are going to burn the house down.";
                            break;
                        case 25:
                        case 26:
                        case 27:
                            $looks = "`n`n`7".$row['name']."`7 is `@Supersexy`7.  What can I say?  They rock the casbah!";
                            break;
                        case 28:
                        case 29:
                        case 30:
                            if($row[sex] == 1)
                                $looks = "`n`n`7".$row['name']."`7 is without a doubt the `^girl of your dreams`7.  And who knows, sometimes dreams come true!";
                            else
                                $looks = "`n`n`7".$row['name']."`7 is without a doubt the `^guy of your dreams.  And who knows, sometimes dreams come true!";
                            break;
                    default:
                        $looks = "`n`n`7".$row['name']."`7 is the `2fairest in the land`7.  It makes you weak in the knees to think about them!`n`n";
                }

                output($looks, true);

                output("<form action='chapel.php?op=pickname&player=".$row['acctid']."' method='POST'>",true);
                output("`6Are you sure you want to propose?`n`n");
                output("<input type='submit' class='button' value='Propose marriage'></form>",true);
                addnav("","chapel.php?op=pickname&player=".$row['acctid']);
            }
        }else{
                output("`n`n`4`bBrother Thomas`b`7 says \"I'm afraid there is no one in the realm by that name\"");
        }

}else if ($_GET[op]=="pickname"){
    output("`c`7`bThe Greystone Chapel`b`c");
    $sql = "SELECT name,acctid,marriedto,sex FROM accounts WHERE acctid=".$_GET[player]." ";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $proposed = $row['acctid'];
    $proposed_name = $row['name'];

    if($session[user]['acctid'] == $proposed){
        output("`n`n`&\"Vanity is a sin my son, besides you can not marry your self\"`7 `4`bBrother Thomas`b`7 informs.", true);
        debuglog("tried to marry themself");
    }else if($row['marriedto'] > 0){
        output("`n`n`&\"I'm afraid that person is already married.  Perhaps you can kill off their spouse.\"`7 `4`bBrother Thomas`b`7 suggests.", true);
        debuglog("tried to marry someone who was already married");
    }else if($row['sex'] == $session[user]['sex'] && ALLOWGAYMARRIAGE){
        output("`n`n`&\"The church does not condone same-sex marriages.   Perhaps you should go to Hawaii or Vermont\"`7 `4`bBrother Thomas`b`7 suggests.", true);
        debuglog("tried to marry someone of the same sex");
    }
    else {
        $sql = "SELECT * FROM marriage WHERE proposed=".$proposed." AND suitor = ".$session[user]['acctid']." ";
        $result = db_query($sql);
        if(db_num_rows($result) >= 1)
        {
            output("`n`n`&\"I've already dispacted my accolyte to deliever your message.  Rest assured it will be recieved.  You will not need to send another.\" `4`bBrother Thomas`b`7 states.", true);
            debuglog("tried to send another proposal to their sweetheart");
        }
        else
        {
            $sql = "INSERT INTO marriage (suitor, proposed, date) VALUES(".$session[user]['acctid'].",".$proposed.",'".date("Y-m-d H:i:s",strtotime("-1 day"))."')";
            db_query($sql) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0){
                    output("\"`\$Error`^: Your marriage proposal could not be stored for an unknown reason, please try again. \" balks the database. ");
                }
                else {
                    systemmail($proposed,"`^For your eyes only`0","My dearest `^".$row['name']."`^,`n`nWhen I look into your eyes, I am taken aback by your beauty.   I can't imagine spending another day without you.   I have something very important to tell you, would you please meet me at the `7`bThe Greystone Chapel`b`^ at noon.`n`nSigned,`n`^".$session[user][name]."`^`n`nXXX 000 XXX`n`n`@(`@".$session[user][name]."`@ has proposed marriage)",$session[user]['acctid']);
                    output ("`n`n`^Your marriage proposal has been sent to ".$proposed_name)."`1";
                    debuglog("sent a marriage proposal to ".$proposed_name);
                }
            }
    }
}else if ($_GET[op]=="answeris"){
    output("`c`7`bThe Greystone Chapel`b`c");
    $sql = "SELECT name, acctid, sex, marriedto FROM accounts WHERE acctid = ".$_GET[userid]." ";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);

    if($_GET[answer] == "Yes"){
        //Is user already married?
        if($row['marriedto'] > 0){
            //Yes, do nothing.  Get rid of proposal.
            output("`n`n`7\"I'm afraid that person is already married.  Perhaps you can kill off their spouse.\" `4`b Brother Thomas`b`7 suggests.", true);
            debuglog("tried to marry someone who was already married (too bad)");
            //Delete all this player's proposals
            $sql = "DELETE FROM marriage WHERE proposed =".$row['acctid']." ";
            db_query($sql) or die(db_error(LINK));

            $sql = "DELETE FROM marriage WHERE suitor = ".$row['acctid']." ";
            db_query($sql) or die(db_error(LINK));

        }else{
            //Do the wedding thing
            if($session[user]['sex'] == 0){
                $groom = $session[user]['name'];
                $bride = $row['name'];
                $special = "`5Violet`7 is overcome with emotion and casts sad puppy eyes at ".$groom."`7.    I guess we'll always have those wonderful times at the inn.`n`n";
            }else{
                $bride = $session[user]['name'];
                $groom = $row['name'];
                $special = "`7As she appears `^Seth Able`7 can't help but chuckle and think back to all the wonderful times they'd had at the inn.   I guess those few times don't count.`n`n";
            }
            output("`n`n`n`n`7You gladly accept ".$row['name']."'s`7 proposal.    A few days later, you both return to `7`bThe Greystone Chapel`b to say your wedding vows.   The entire town gathers in the tiny church to catch a glimpse of the blushing bride and groom.  As ".$groom."`7 waits patiently beside the alter, ".$bride."`7 appears through the back of the chapel, wearing a white gown and looking radiant.`n`n",true);
            output($special, true);
            output("`7As you both stand before the `4`bBrother Thomas`b`7, you both exchange your vows and you can't help but imagine what your life together will be like.   Perhaps there will be the pitter-patter of tiny feet in the near future.`n`nAs you exit the chapel, friends and relatives pelt you with bits of bird seed (man those things hurt) and you make your getaway in a horse and carriage borrowed from `%Merick's stables`7.`n`n`c`b`^You got married!!`7`b`c",true);

            //Delete all this player's proposals
            $sql = "DELETE FROM marriage WHERE proposed=".$session[user]['acctid']." ";
            db_query($sql) or die(db_error(LINK));

            $sql = "DELETE FROM marriage WHERE suitor = ".$session[user]['acctid']." ";
            db_query($sql) or die(db_error(LINK));

            //Get hitched
            $sql = "UPDATE accounts SET marriedto = ".$session[user]['acctid']." WHERE acctid = ".$row['acctid']." ";
            db_query($sql) or die(db_error(LINK));
            $session[user]['marriedto'] = $row['acctid'];

            $sql = "UPDATE accounts SET marriedto = ".$row['acctid']." WHERE acctid = ".$session[user]['acctid']." ";    db_query($sql) or die(db_error(LINK));
            //I now pronounce you man and wife

            systemmail($row['acctid'],"`^Yes!!`0","`^Yes!! I will marry you!!`n`n`@(".$session[user][name]."`@ has accepted your proposal)",$session[user]['acctid']);

            addnews("`%".$row['name']."`& and `%".$session[user][name]."`& were joined in matrimony today!");
            debuglog("married ".$row['name']." at the chapel");
        }
    }
    else{
        //Get rid of rejected marriage proposal
        output("`n`n`7You reject ".$row['name']."'s plea for marriage`1", true);
        $sql = "DELETE FROM marriage WHERE suitor='".$_GET[userid]."'";
        db_query($sql) or die(db_error(LINK));

        systemmail($_GET[userid],"`^I'm sorry`0","I am sorry `^".$row['name']."`^, but I am afraid that I do not love you.   It's not you, it's me.   I'm just not ready to settle down right now.   I hope you understand and I hope that we will remain friends.`n`nSincerely,`n`^".$session[user][name]."`n`n`@(".$session[user][name]."`@ has declined your proposal)",$session[user]['acctid']);

        addnews("`%".$session[user][name]."`& rejected `%".$row['name']."`&'s marriage proposal! (what a loser)`1");
        debuglog("rejected ".$row['name']."'s marriage proposal.");
    }

}else if($_GET[op]=="give"){
    output("`c`7`bThe Greystone Chapel`b`c");
    $session[user][specialinc]="";
    output("`n`n`7You beckon `4`bBrother Thomas`b`7 closer, and as he walks to your side, you pull out a small pouch of gems from your cloak.    `4`bBrother Thomas`b`7 smiles knowingly and neglects to ask you where you've found your ill gotten gains.   He offers you a blessing in exchange for a donation.`7");
    //output("<a href=\"chapel.php?op=bless&action=yes\">`^Yes</a> `^/ <a href=\"chapel.php?op=bless&action=no\">`^No</a>", true);

    $g1 = round(($session['user']['gold']+$session['user']['goldinbank'])*.1);
    $g2 = round(($session['user']['gold']+$session['user']['goldinbank'])*.25);
    $g3 = round(($session['user']['gold']+$session['user']['goldinbank'])*.35);
    if ($_GET[type]==""){
        output("`n`nHow much would you like to offer him?");
        addnav("1 gem","chapel.php?op=bless&type=gem&amt=1");
        addnav("2 gems","chapel.php?op=bless&type=gem&amt=2");
        addnav("3 gems","chapel.php?op=bless&type=gem&amt=3");
        addnav("$g1 gold","chapel.php?op=bless&type=gold&amt=$g1");
        addnav("$g2 gold","chapel.php?op=bless&type=gold&amt=$g2");
        addnav("$g3 gold","chapel.php?op=bless&type=gold&amt=$g3");
    }

    //addnav("", "chapel.php?op=bless&action=yes");
    //addnav("", "chapel.php?op=bless&action=no");
}else if($_GET[op]=="bless"){
    output("`c`7`bThe Greystone Chapel`b`c");
    //Brother Thomas believes is looking after the weak, so give lower level players more rounds of protection
    $blessing = array("name"=>"`@Priest's Protection",
                                "rounds"=>((16-$session[user][level])*10),
                                "wearoff"=>"`!You feel all alone`0",
                                "defmod"=>1.2,
                                "roundmsg"=>"You feel an awesome power watching over you.",
                                "activate"=>"defense");

    if ($_GET[type]=="gem"){
        if ($session['user']['gems']<$_GET['amt']){
            $try=false;
            //output("You don't have {$_GET['amt']} gems!");
            output("`n`n`7You open your pouch and realize that you don't have {$_GET['amt']} gems.  `4`bBrother Thomas`b`7 sighs and tells you to return when you've had better luck in the forest.`n`n", true);
        }else{
          $tithe = $session['user']['gems']*.1;
          if($_GET[amt] >= $tithe){
            $chance = $_GET[amt]/4;
            $session[user][gems]-=$_GET[amt];
            if(ALLOWNPC) GemCollector($_GET[amt]);
            debuglog("contributed {$_GET['amt']} gems to the chapel");
            $try=true;
          }else{
            output("`4`bBrother Thomas`b`7 sternly clears his throat and says `&\"It seems that your not willing to contribute your `^`b10%`b`& to the church.  I'm sorry, but I can not help you.\"`7`n`n", true);
          }
        }
    }else{
        if ($session['user']['gold']<$_GET['amt']){
            //output("You don't have {$_GET['amt']} gold!");
            output("`n`n`7You open your pouch and realize that you don't have {$_GET['amt']} gold.  `4`bBrother Thomas`b`7 sighs and tells you to return when you've had better luck in the forest.`n`n", true);
            $try=false;
        }else{

            $tithe = ($session['user']['gold'] + $session['user']['goldingbank']) * .1;
            if($_GET[amt] >= $tithe){
                $try=true;
                $chance = $_GET[amt]/($session[user][level]*40);
                $session[user][gold]-=$_GET[amt];
                if(ALLOWNPC) GoldCollector($_GET[amt]);
                debuglog("contributed {$_GET['amt']} gold to the chapel");
            }else{
                output("`4`bBrother Thomas`b`7 sternly clears his throat and says `&\"It seems that your not willing to contribute your `^`b10%`b`& to the church.  I'm sorry, but I can not help you.\"`7`n`n", true);
            }
        }
    }
    $chance*=100;
    if($try){
        if(e_rand(0,100)<$chance){
                output("`n`n`7You bow your head and `4`bBrother Thomas`b`7 makes a sign of the cross in the air.   You feel an odd sensation come over you.`n`n", true);
                if($session['bufflist']['priestcurse'] != ""){
                    output("A foul demon has power over you... BEGON! He shouts and the curse is lifted.`n`n");
                    output("`^`c`bYou are no longer cursed!`c`b`7");
                    debuglog("gave a gem to have a curse lifted.");
                    unset($session['bufflist']['priestcurse']);
                }else{
                    switch(e_rand(1,5)){
                      case 1:
                      case 2:
                      output("You feel your body fill with warmth, and feel like you can take on anything...`n`n");
                      output("`^`c`bAll of your hitpoints have been temporarily increased!`c`b`7");                  $session[user][hitpoints]= + $session[user][maxhitpoints] + (e_rand(($session[user][maxhitpoints]/4),($session[user][maxhitpoints]/2)));
                      debuglog("gave a gem and was healed.");
                      break;
                      case 3:
                      case 4:
                      output("The clarity of the light of god shines upon you...`n`n");
                      output("`^`c`bYou are stone cold sober`c`b`7");
                      output("`^`c`bYou gain 1 charm for your cleaned up appearance`c`b`7");
                      $session[user][drunkenness] = 0;
                      $session[user][charm]++;
                      debuglog("gave a gem and was cured of drunkendness.");
                      break;
                      case 5:
                        output("You feel as though someone is watching over you...`n`n");
                        output("`^`c`bYou have gained temporary protection!`c`b`7");
                        $session['bufflist']['priestblessing'] = $blessing;
                        debuglog("gave a gem and gained Priest's Protection.");
                      break;
                    }
                }
        }else{
            //output("`n`n`7On second thought, if several thousand years of dogma have taught us anything (and it hasn't), it's never give your fair share to the church.  You snap close your pouch and flash an evil grin at `4`bBrother Thomas`b`7.   His smile turns to a frown and your almost certain you can hear him mutter a curse beneath his breath.   You'd better get out of here before you cause anymore trouble.", true);
            output("`n`n`7\"Your donation is too small, I'm afraid you'll have to do better than that\" `4`bBrother Thomas`b`7 says.", true);
        }

    }

}else if($_GET[op]=="steal"){

    //Brother Thomas doesn't like it when players steal from him, so he penalizes high level players with a longer curse
    output("`c`7`bSteal from the Church`7`b`c");
    debuglog("attempted to steal from the church");
    switch($session['user']['specialty']){
        case 1:
            if($session[user][darkartuses] > 0){
                output("`n`n`7When `4`bBrother Thomas`b`7 isn't looking, you sneak into the back of the church and find a dusty door with a sign that reads: \"`$`bExtreme Evil, Keep Out!`b`7\".   You summon forth the powers of the dark lord and attempt to open the door.", true);
                $session[user][darkartuses]--;
                $chance = ($session[user][darkarts]*5) + 5;
                $eresult = e_rand(1,100);
                //output("`n".$eresult." <= chance ".$chance."`n", true);
                if($eresult <= $chance){
                    //Darkarts user gained entry to the forbidden room
                    output("`nThere is a loud crash and the door swings wide.   You quickly hurry inside and see if there is anything valuable you can take.`n", true);
                    switch(e_rand(1,5)){
                        case 1:
                        case 2:
                            output("You see a small parchment.   You unroll it and find that it's written in blood on a dried piece of human flesh.  After you shake of the heebie-jeebies you are surprised to learn a few important lessons about being evil. Mwhahahaha!`n`n");
                            if($session[user][darkarts] <= 6){
                                output("`^`c`bYou gained an extra darkart skill!`c`b`7");
                                $session[user][darkarts]++;
                                debuglog("read the forbidden book in the chapel and gained a darkart skill");
                            }else{
                                output("`^`c`bYou find out nothing new.`c`b`7");
                                debuglog("read the forbidden book in the chapel but learned nothing");
                            }
                            break;
                        case 3:
                        case 4:
                            output("You see a small wooden staff, when you reach to pick it up it begins to vibrate wildly.   After a few seconds it morphs into a ".$session[user][weapon]." right before your eyes.  You decide to leave your old weapon here and take this one (it must be more powerful, right?)`n`n");
                            //Don't let them get too powerful
                            if(strchr($session[user][weapon],"Enchanted")){
                                output("`^`c`bYou already have an ".$session[user][weapon]." so you don't take the new one (why gamble?)`c`b`7");
                                debuglog("tried to gain an enchanted weapon in the chapel but already had one");
                            }else{
                                output("`^`c`bYour ".$session[user][weapon]." has gained +1 attack!`c`b`7");
                                $session[user][weapondmg]++;
                                $session[user][weapon] = "Enchanted ".$session[user][weapon];
                                debuglog("gained an enchanted weapon in the chapel");
                            }
                            break;
                        case 5:
                            output("You see a glowing orb of power.   As you move closer your hands are drawn toward it's mysterious power.  As a red bolt of evil passes through your body you feel recharged and ready to take on the world!! Mwhahahaha!`n`n");
                            output("`^`c`bYou have gained an extra darkart use!`c`b`7");
                            $session[user][darkartuses] ++;
                            debuglog("gained an extra darkart use in the chapel");
                            break;
                    }
                    addnews("`5Some items were stolen from the `7`bGreystone Chapel`b`5");
                }else{
                    output("`n`7As you concentrate on the door a huge bolt of lightening springs forth from the sign and strikes you right between the eyes.   When you wake up you find..`n", true);
                    //Darkarts user could not gain entry to the forbidden room
                    switch(e_rand(1,5)){
                          case 1:
                          case 2:
                          output("Your money pouch is missing.  That miserable `4`bBrother Thomas`b`7!!`n`n");
                          output("`^`c`bYou have lost all gold on hand!`c`b`7");
                          $session[user][gold] = 0;
                          debuglog("lost all their gold at the chapel trying to break and enter");
                          break;
                          case 3:
                          case 4:
                          output("Your head hurts and you can't concentrate.`n`n");
                          output("`^`c`bYou have lost focus!`c`b`7");
                          $session[bufflist]['lostfocus'] = array("name"=>"`%Lost Focus","rounds"=>20,"wearoff"=>"Clarity returns!","atkmod"=>0.50,"roundmsg"=>"You've can't seem to concentrate. ","activate"=>"offense");
                           debuglog("cursed with lack of concentration at the chapel.");
                          break;
                          case 5:
                            output("You feel funny, like something has drained all your power.`n`n");
                            output("`^`c`bYou lost all darkart special uses for today!`c`b`7");
                            //$session[user][darkartuses] = 0;
                            debuglog("was lost all darkart special uses for a day");
                          break;
                    }
                }
            }else{
                output("`n`n`7When `4`bBrother Thomas`b`7 isn't looking, you sneak into the back of the church but you can't seem to find anything.  `4`bBrother Thomas`b`7 comes around the corner and asks what you are doing.   You slyly say that your looking for the restroom, and slink out of the room.", true);
                debuglog("tried break into the forbidden room but was out of turns");
            }
            break;
        case 2:
            if($session[user][magicuses] > 0){
                output("`n`n`7When `4`bBrother Thomas`b`7 isn't looking, you sneak into his office and try to find the secret door to his chambers.  With your Mystical Powers you attempt to find the concealed door.1n", true);
                $session[user][magicuses]--;
                $chance = ($session[user][magic]*5) + 5;
                $eresult = e_rand(1,100);
                //output("`n".$eresult." <= chance ".$chance."`n", true);
                if($eresult <= $chance){
                    //Magic User succeeded and got into Brother Thomas's Chambers
                    output("`n`nNext to the bookcase you see the glowing outline of a door.  Quickly you open it and hurry inside, looking for something of value.`n", true);
                    switch(e_rand(1,5)){
                        case 1:
                        case 2:
                            output("You see a book.   You open it and find that it's written in an ancient dialect that you will have to decipher.  You quickly skim the text and try to pick out a powerful spell.`n`n");
                            if($session[user][magic] <= 6){
                                output("`^`c`bYou find a good spell and gain an extra magic skill!`c`b`7");
                                $session[user][magic]++;
                                debuglog("read a magic book in the chapel and gained a skill");
                            }else{
                                output("`^`c`bYou find nothing of interest.`c`b`7");
                                debuglog("tried to read a magic book in the chapel but learned nothing");
                            }
                            break;
                        case 3:
                        case 4:
                            output("You see a small wooden shield, when you reach to pick it up it begins to vibrate wildly.   After a few seconds it morphs into a ".$session[user][armor]." right before your eyes.  You decide to leave your old armour here and take this one (it must be more powerful, right?)`n`n");
                            //Only enchant weapon once
                            if(strchr($session[user][armor],"Enchanted")){
                                output("`^`c`bYou already have an ".$session[user][armor]." so you don't take the new one (why gamble?)`c`b`7");
                                debuglog("tried to gain enchanted armour in the chapel but already had it");
                            }else{
                                output("`^`c`bYour ".$session[user][armor]." has gained +1 attack!`c`b`7");
                                $session[user][armordef]++;
                                $session[user][armor] = "Enchanted ".$session[user][armor];
                                debuglog("gained enchanted armour in the chapel");
                            }
                            break;
                        case 5:
                            output("You see a glowing orb of power.   As you move closer your hands are drawn toward it's mysterious power.  As a blue bolt of lightening passes through your body you feel recharged and ready to take on the world!! Mwhahahaha!`n`n");
                            output("`^`c`bYou have gained a magic use!`c`b`7");
                            $session[user][magicuses] ++;
                            debuglog("gained an extra magic use in the chapel");
                            break;
                    }
                    addnews("`5Some items were stolen from the `7`bGreystone Chapel`b`5");
                }else{
                    //Magic User failed to get into Brother Thomas's Chambers
                    switch(e_rand(1,5)){
                          case 1:
                          case 2:
                          output("Your money pouch is missing.  That miserable `4`bBrother Thomas`b`7!!`n`n");
                          output("`^`c`bYou have lost all gold on hand!`c`b`7");
                          $session[user][gold] = 0;
                          debuglog("lost all their gold at the chapel trying to break and enter");
                          break;
                          case 3:
                          case 4:
                          output("Your head hurts and you can't concentrate.`n`n");
                          output("`^`c`bYou have lost focus!`c`b`7");
                          $session[bufflist]['lostfocus'] = array("name"=>"`%Lost Focus","rounds"=>20,"wearoff"=>"Clarity returns!","atkmod"=>0.50,"roundmsg"=>"You've can't seem to concentrate. ","activate"=>"offense");
                           debuglog("cursed with lack of concentration at the chapel.");
                          break;
                          case 5:
                            output("You feel funny, like something has drained all your power.`n`n");
                            output("`^`c`bYou lost all magic special uses for today!`c`b`7");
                            //$session[user][magicuses] = 0;
                            debuglog("was lost all darkart special uses for a day");
                          break;
                    }
                }
            }else{
                output("`n`n`7When `4`bBrother Thomas`b`7 isn't looking, you sneak into his office and try to find the secret door to his chambers but you can't seem to find anything.  `4`bBrother Thomas`b`7 comes around into his office and asks what you are doing.   You slyly say that your looking for the restroom, and slink out of the room.", true);
                debuglog("tried to break into Brother Thomas's secret room but had no turns");
            }
            break;
        case 3:
            if($session[user][thieveryuses] > 0){
                output("`n`n`7When `4`bBrother Thomas`b`7 isn't looking, you sneak into the back of the church and find a box labeled \"`@Children's Fund`7\".   With the deft skills of a thief, you pull out your lockpicks and begin working.", true);
                $session[user][thieveryuses]--;
                $chance = ($session[user][thievery]*5) + 5;
                $eresult = e_rand(1,100);
                //output("`n".$eresult." <= chance ".$chance."`n", true);
                if($eresult <= $chance){
                    //Thief successfully opened the cash box
                    if(ALLOWNPC) $pilfered = goldStealer();
                    else
                        $pilfered = e_rand(1,(100*$session[user][level]));
                    output("`n`nYou managed to jimmy the lock and pilfer `^".$pilfered." gold`7 from the cash box`n", true);
                    $session[user][gold] += $pilfered;
                    debuglog("pilfered ".$pilfered." gold from the church");
                    addnews("`5Some money was stolen from the `7`bGreystone Chapel`b`5");
                }else{
                    //Thief could not open the cash box
                    output("`nYou can't get the lock open.`n", true);
                    //bad stuff happens when you try to steal from the church.
                    switch(e_rand(1,5)){
                          case 1:
                          case 2:
                          output("You begin to doubt your skills as a thief...`n`n");
                          output("`^`c`bYou have lost some daily thievery uses!`c`b`7");
                          $session[user][thieveryuses] = round($session[user][thieveryuses]-2);
                          debuglog("was cursed by losing thievery uses at the chapel");
                          break;
                          case 3:
                          case 4:
                          output("You are depressed by your lack of success...`n`n");
                          output("`^`c`bYou go out and get drunk!`c`b`7");
                           output("`^`c`bYou lose 2 charm for your slovenly appearance.`c`b`7");
                          $session[user][drunkenness] = 99;
                          $session[user][charm] -= 2;
                          $session[bufflist][101] = array("name"=>"`#Buzz","rounds"=>20,"wearoff"=>"Your buzz fades.","atkmod"=>1.25,"roundmsg"=>"You've got a nice buzz going.","activate"=>"offense");
                           debuglog("cursed with drunkendness at the chapel.");
                          break;
                          case 5:
                            output("You remember the time you pulled off a butterflies wings?   Well this time what you've done is a lot worse.  You've booked a ticket on the hell bound express!`n`n");
                            output("`^`c`bYou been afflicted by the devil!`c`b`7");
                            $session['bufflist']['priestcurse'] = array("name"=>"`4Devil's Curse",
                                                                        "rounds"=>($session[user][level]*10),
                                                                        "wearoff"=>"`7The power of `4Satan`7 fades",
                                                                        "atkmod"=>0.75,
                                                                        "defmod"=>0.75,
                                                                        "roundmsg"=>"`7The dark lord `4Satan`7 screws up your rythmn.",
                                                                        "survivenewday"=>1,
                                                                        "newdaymessage"=>"`6Due to the power of Satan, you are tormented all night by a bought of explosive diahrea`6.","activate"=>"offense,defense");
                            debuglog("was afflicted by the devil at the chapel");
                          break;
                    }
                }
            }else{
                output("`n`n`7When `4`bBrother Thomas`b`7 isn't looking, you into the back of the church and look for the lock box but are unable to find it. `4`bBrother Thomas`b`7 comes around ithe corner and asks what you are doing.   You slyly say that your looking for the restroom, and slink away.", true);
                debuglog("tried to pilfer the cash box but was out of turns");
            }
            break;
        default:
            output("`n`n`7You are obviously unqualified for stealing from the church, which makes you better than Jim Baker.  (no not former Secretary of State James Baker, the former Televangelist Jim Baker)", true);
            debuglog("was unable to steal from the chapel");
            break;
    }

}else if($_GET[op]=="purgatory"){
    output("`c`7`bThe Greystone Chapel`b`c");
    output("`n`n`4`bBrother Thomas`b`7 says `&\"While I can not directly grant passage from the land of the dead, I can pray for the souls of the dead in the hope that they will escape from purgatory.   For a small fee I will say a few prayers and most likely your friend will be able to escape death's cold grasp once more.\"`n`n \"Whom would you like to say a prayer for?\"`7 he asks.`n`n");
    output("<table border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td style=\"width:350px\">Name</td><td style=\"width:150px\">Level</td><td>&nbsp;</td></tr>",true);

    $sql="SELECT acctid, name, alive, login, level FROM accounts WHERE alive = 0 ORDER BY accounts.level DESC";
    $result = db_query($sql) or die(db_error(LINK));
    $countrow = db_num_rows($result);
    for ($i=0; $i<$countrow; $i++){
    //for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true);
        output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true);
        output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
        output("$row[name]</a></td><td>`^".$row['level']."`7</td><td>[<a href='chapel.php?op=resurrect&player=".rawurlencode($row[acctid])."'>Pray for</a> ]</td></tr>",true);    addnav("","bio.php?char=".rawurlencode($row['login'])."");
        addnav("","chapel.php?op=resurrect&player=".rawurlencode($row[acctid])."");
    }
    output("</table>",true);
}else if($_GET[op]=="resurrect"){
    output("`c`7`bThe Greystone Chapel`b`c");
    $player=$_GET[player];
    if($player != "")
    {
        $sql="SELECT acctid, name, alive, login, level FROM accounts WHERE acctid =".$player;
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        $cost = $row['level'] * getsetting("resurrectcost",10000);

        if($_GET[action] == "yes"){
            if($session[user][gold] >= $cost){
                output("`n`n`7You decide to help ".$row['name']."`7 get their soul out of purgatory.   So you hand over the ".$cost." and `4`bBrother Thomas`b`7 says a prayer. \"".$rox['name']."'s soul is now worthy of resurrection, but they must still ask `4Ramius`7 for passage out of the shades.\" he states.",true);

                output("`n`n`^`b`cYou have given ".$row['name']."`^ 100 extra soulpoints`b`c`7", true);

                $sql="UPDATE accounts SET deathpower = deathpower + 100 WHERE acctid = ".$row['acctid'];
                db_query($sql) or die(db_error(LINK));

                $session[user][gold] = $session[user][gold] - $cost;

                systemmail($_GET[userid],"`^Sweet Salvation!`0",$session[user][name]." has payed off the dark lord of the underworld for you.`n`n`@(You have recieved 100 extra soul points for resurrection)`7",$session[user]['acctid']);
                debuglog($session[user][name]." paid to get ".$row['name']." ressurected");
            }else{
                output("`n`n`7You decide to help ".$row['name']."`7, but `4`bBrother Thomas`b`7 tells you that you don't have enough money.  ".$row['name']."'s`7 soul will just have to languish in the shades for another day.",true);
            }
        }else if($_GET[action] == "no"){
            output("`n`n`7You decide that it's not worth it to get ".$row['name']."'s`7 soul out of purgatory.   So you thank `4`bBrother Thomas`b`7 and leave his office.",true);

        }else{


            output("`n`n`4`bBrother Thomas`b`7 tells you that it will take about ".$cost." to get ".$row['name']." out of purgatory.`n`n   Would you like to resurrect ".$row['name']."?`n`n",true);

            output("<a href=\"chapel.php?op=resurrect&player=".$row['acctid']."&action=yes\">`^Yes</a> `^/ <a href=\"chapel.php?op=resurrect&player=".$row['acctid']."&action=no\">`^No</a>", true);
            addnav("", "chapel.php?op=resurrect&player=".$row['acctid']."&action=yes");
            addnav("", "chapel.php?op=resurrect&player=".$row['acctid']."&action=no");
        }
    }
}else if($_GET[op]=="marriedlist"){
    output("`c`7`bThe Greystone Chapel`b`c");
    $namestack = array();
    $acctidstack = array();
    $listed = array();

    output("`n`n`7You examine the marriage register and see the following names:`n");

    $sql =
        "SELECT acctid, name, marriedto FROM accounts where marriedto > 0 AND marriedto != 4294967295 ORDER BY marriedto DESC";
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        array_push($namestack, $row['name']);
        array_push($acctidstack, $row['acctid']);
    }

    $sql =
        "SELECT acctid, name, marriedto FROM accounts where marriedto > 0 AND marriedto != 4294967295 ORDER BY acctid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        $arrayloc = array_search($row['marriedto'], $acctidstack);
        //output("`narrayloc = ". $arrayloc."`n", true);
        if(in_array($row['marriedto'], $listed) == false){
            $name = $namestack[$arrayloc];
            output("`n`n`7&nbsp;&nbsp;".$row['name']."`5 is married to `7".$name."`5", true);
            array_push($listed, $row['acctid']);
        }
    }
}else if($_GET[op]=="changename"){
    output("`c`7`bThe Hall of Records`b`c");
    if($_GET[action]=="commit"){
            if($session[user][gold] >= getsetting("changenamecost",400)){

                output("`n`n`7You sign your name on the form and slide it across the counter along with your payment.`7",true);
                $newname = $session[user]['title'] ." ". $session[user]['ctitle'] ." ". $_POST[name];
                $session[user]['name'] = $newname;
                $session[user][gold] -= getsetting("changenamecost",400);
                //savesetting("name",$_POST[name]);
                output("`c`n`n`b`^Your name has been changed to`b`7 ".$session[user]['name']."`n`^`bPlease note this does not change your login name`b`c`7", true);
                debuglog("change their name to ".$row['name']." in the Greystone Chapel");

            }else{
                output("`n`n`7\"`&I'm sorry honey, but you don't have enough money to change your name.`7\"`n`n", true);
            }

    }else{

        output("`n`n`7You walk into the register of deeds office and you see a short, slightly wrinkled lady behind the counter.  \"`&What can I do for you young one.`7\" she says in a raspy voice. \"`&I guess you're here to change your name, aren't you?  Well just sign this form and attach the`^`b ".getsetting("changenamecost",400)." gold fee`b`& and I'll put these forms through in 6 to 8 weeks.`7", true);
        output("`n`n`7\"`&6 to 8 weeks!`7\" you cry.  \"`&I can't wait that long, there are people after me today!`7\"`n`n\"`&Don't get your panties in a bunch`7\" she calmly says.\"`&I'm just having some fun with you.  Go ahead and give me the information and we can change it now.`7\"`n`n",true);

        includePreview();

        output("<form action='chapel.php?op=changename&action=commit' method='POST'>",true);
        output("`n`n`6What would you like to change your name to?`n`n");
        output("<input type=\"text\" id='name' onKeyUp='previewtext(document.getElementById(\"name\").value);'; name=\"name\" class='input'>", true);
        output("<input type='submit' class='button' value='Change name'>",true);

        output("<div id='previewtext'></div></form>",true);


        addnav("", "chapel.php?op=changename&action=commit");

    }
}

if($_GET[op]!="give") addnav("Across the street");

if ($session[user]['marriedto'] > 0 && $_GET[op]!="give" && ALLOWMARRIAGE && ALLOWDIVORCE){
    if ($_GET[op]!="divorce")
        if($session[user][marriedto] == 4294967295 && DIVORCEVIOLET)
            addnav("Divorce Court", "chapel.php?op=divorce");
        else if($session[user][marriedto] < 4294967295)
            addnav("Divorce Court", "chapel.php?op=divorce");
    else{
        output("`c`7`bThe office of `#XIUS `&Divorce Attorney`7`b`c");
        if ($_GET[action]=="divorce"){
            //Do you have the money?
            if($session[user][gold] >= getsetting("divorcecost",400)){
                //Get a quicky divorce
                if($session[user][marriedto] == 4294967295){
                    if($session['user']['sex']?$spouse = "Seth":$spouse = "Violet");
                    $session[user][marriedto] = 0;
                }else {
                    $sql = "SELECT name, acctid, marriedto FROM accounts WHERE acctid = ".$session[user][marriedto]." ";
                    $result = db_query($sql);
                    $row = db_fetch_assoc($result);

                    $spouse = $row['name'];

                    $sql = "UPDATE accounts SET marriedto = 0 WHERE acctid = ".$row['acctid']." ";
                    db_query($sql) or die(db_error(LINK));

                    $session[user][marriedto] = 0;

                    systemmail($row['acctid'],"`^I want a divorce!`0","I am sorry `^".$row['name']."`^, but I am afraid that I do not love you anymore.   It's not you, it's me.   I'm just need my own space.   I hope you understand and I hope that we can stay friends.`n`n`@(".$session[user][name]."`@ has filed for a divorce)",$session[user][acctid]);
                    debuglog("got a divorce from ".$row['name']." at XIUS's");
                }
                addnews("`%".$session[user][name]."`& filed for a divorce from `%".$spouse."`&.");
                output("`n`n`7Sign your name on the divorce papers and slide them to `#XIUS`7.   Soon one of his young interns will serve them to ".$spouse."`7 and you'll be free as a bird. `#XIUS`7 cracks open a bottle of Cedrick's Special Reserve and lifts a toast to your new found freedom.`1", true);
                output("`n`n`c`^`bYou are no longer married`c`b`7", true);
                output("`n`n`c`^`bYour wallet feels ".getsetting("divorcecost",400)." gold lighter`c`b`7", true);
                $session[user][gold] = $session[user][gold] - getsetting("divorcecost",400);
            }else{
                //XIUS doesn't work for free
                output("`n`n`#XIUS`7 yanks his lucky signing pen out of your hand.   I'm sorry, but I'm afraid I can't get you out of the bondage of wedlock for free!   Come back when you've got the money!",true);
                addnav("Divorce Court", "chapel.php?op=divorce");
            }
        }else {
            //Want an divorce?
            output("`n`n`7Disillusioned with married life, you head across the street to a dingy office building with a sign in front that reads `#XIUS `7Divorce Attorney at large.  You wonder what an attorney could be running from, but then you decide that you'd rather not know and head in through the door.  A small chime rings as you enter the office, and a stocky gentleman with a goatee greets you.`n`n `&\"Hi, I'm `#XIUS`& and I want to represent YOU!\"`7 he says as he sticks out his hand.  `&\"Come sit down and tell me all about your troubles.  I specialize in getting you the divorce you want and need, all for a nominal fee of `^".getsetting("divorcecost",400)."`& gold. Now what can I do for you?`7\"",true);
            if(ALLOWDIVORCE) addnav("File for divorce", "chapel.php?op=divorce&action=divorce");
        }
    }
}

if($_GET[op]=="announce"){
        if($_GET[action]=="confirm"){
            if($session[user][gold] > getsetting("announcementcost",400)){
                output("`n`n`7Your message is desciminated throughout the realm", true);
                addnews("`5\"`6".stripslashes($_POST[message])."`5\" ".$session[user][name]."`5 announces.");
                output("`n`n`c`b`^(You announce:`& ".stripslashes($_POST[message])."`^)`b`c`7", true);
                $session[user][gold] -= getsetting("announcementcost",400);
            }else{
                output("`n`n`7You do not have enough money to make an announcement", true);
            }
        }else{
            includePreview();
            output("`c`7`b(CCR) - Communications Center of the Realm`7`b`c");
            output("`n`n`7You walk into the `b`%CCR`b`7 office and are greeted by `$`bRAX`b`7.`n`n`&So you've got something important to say?  Well, it's going to cost you.  The going rate is `^`b".getsetting("announcementcost",400)." gold`b`& for a standard Daily News headline.  You are free to use colors as needed and you will be able to preview the note before you click submit.  But be warned, once you submit this message it will not go away until it expires, so be careful what you say.`7`n", true);
            output("<form action='chapel.php?op=announce&action=confirm' method='POST'>",true);
            output("`n`n`&What would you like to say?`7`n`n");
            output("<input type=\"text\" style='width:300;' id='message' onKeyUp='previewtext(document.getElementById(\"message\").value);'; name=\"message\" class='input'>", true);
            output("<input type='submit' class='button' value='Submit Announcement'>",true);
            output("`6<div id='previewtext'></div></form>",true);
            addnav("","chapel.php?op=announce&action=confirm");
        }

}

if($_GET[op]!="give"){
    if(ALLOWNAMECHANGE) addnav("Change My Name", "chapel.php?op=changename");
    if(ALLOWANNOUNCEMENT) addnav("Make an Announcement", "chapel.php?op=announce");
    addnav("Other");
    if($session[user]['beta'] == 1) addnav("Back to Chapel", "chapel.php");
    addnav("Return to Village","village.php");
}

page_footer();


function gemCollector($gems){
//Brother Thomas (NPC) pockets the profit
    global $session;

    $sql = "UPDATE accounts SET gems = ".$gems." WHERE acctid = ".THOMASID;
    db_query($sql) or die(db_error(LINK));

    systemmail(THOMASID,"`#Gem Donation`0",$session[user][name]."`^ donated ". $gems ." gems to the church", $session[user]['acctid']);
}

function goldCollector($gold){
//Brother Thomas (NPC) pockets the profit
    global $session;

    $sql = "UPDATE accounts SET goldinbank = ".$gold." WHERE acctid = ".THOMASID;
    db_query($sql) or die(db_error(LINK));

    systemmail(THOMASID,"`#Gold Donation`0",$session[user][name]."`^ donated ". $gold ." gold to the church", $session[user]['acctid']);

}

function goldStealer(){
    global $session;

    $sql = "SELECT goldinbank FROM accounts WHERE acctid = ".THOMASID;
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $pilfered = e_rand(1, $row[goldinbank]);

    $sql = "UPDATE accounts SET goldinbank = ".($row[goldinbank] - $pilfered)." WHERE acctid = ".THOMASID;
    db_query($sql) or die(db_error(LINK));

    systemmail(THOMASID,"`#Gold Stolen`0",$session[user][name]."`^ stole ". $pilfered ." gold to the church", $session[user]['acctid']);
    return $pilfered;
}

function includePreview(){
output("<script language='JavaScript'>
    function previewtext(t){
        var out = \"<span class=\'colLtWhite\'>\";
        var end = '</span>';
        var x=0;
        var y='';
        var z='';
        if (t.substr(0,2)=='::'){
            x=2;
            out += '</span><span class=\'colLtWhite\'>';
        }else if (t.substr(0,1)==':'){
            x=1;
            out += '</span><span class=\'colLtWhite\'>';
        }else if (t.substr(0,3)=='/me'){
            x=3;
            out += '</span><span class=\'colLtWhite\'>';
        }else{
            out += '</span><span class=\'colDkCyan\'>{$talkline} \"</span><span class=\'colLtCyan\'>';
            end += '</span><span class=\'colDkCyan\'>\"';
        }
        for (; x < t.length; x++){
            y = t.substr(x,1);
            if (y=='`'){
                if (x < t.length-1){
                    z = t.substr(x+1,1);
                    if (z=='1'){
                        out += '</span><span class=\'colDkBlue\'>';
                    }else if (z=='2'){
                        out += '</span><span class=\'colDkGreen\'>';
                    }else if (z=='3'){
                        out += '</span><span class=\'colDkCyan\'>';
                    }else if (z=='4'){
                        out += '</span><span class=\'colDkRed\'>';
                    }else if (z=='5'){
                        out += '</span><span class=\'colDkMagenta\'>';
                    }else if (z=='6'){
                        out += '</span><span class=\'colDkYellow\'>';
                    }else if (z=='7'){
                        out += '</span><span class=\'colDkWhite\'>';
                    }else if (z=='q'){
                        out += '</span><span class=\'colDkOrange\'>';
                    }else if (z=='!'){
                        out += '</span><span class=\'colLtBlue\'>';
                    }else if (z=='@'){
                        out += '</span><span class=\'colLtGreen\'>';
                    }else if (z=='#'){
                        out += '</span><span class=\'colLtCyan\'>';
                    }else if (z=='$'){
                        out += '</span><span class=\'colLtRed\'>';
                    }else if (z=='%'){
                        out += '</span><span class=\'colLtMagenta\'>';
                    }else if (z=='^'){
                        out += '</span><span class=\'colLtYellow\'>';
                    }else if (z=='&'){
                        out += '</span><span class=\'colLtWhite\'>';
                    }else if (z=='Q'){
                        out += '</span><span class=\'colLtOrange\'>';
                    }
                    x++;
                }
            }else{
                out += y;
            }
        }
        document.getElementById(\"previewtext\").innerHTML=out+end+'<br/>';
    }
    </script>
    ",true);
}

?>