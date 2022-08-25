<?php
require_once "common.php" ;
/**ss**********************ss***************
/ Gemstore.php - The Anpera and Lonestrider Gemstore  v2.0
/ 3.9.04  / Gemstore script, based on Anpera's German Script with Strider's Translation and mods.

 Additions for 2.0 by Strider (of Legendgard)
 - Added the ability to sell User Selectable multiple gems.
 - Added Legendgard's extra gem array
 - Completely rebuilt the gem price flux to be a little cleaner.
 - Cleaned up some of the older script that bothered me.

--------------------------------------------------------------------------
 To install, you need to add the following line to your configuration.php

 --  in configuration.php      search for
          "LOGINTIMEOUT"=>"Seconds of inactivity before auto-logoff,int",
 --  right under that line add:
  "Misc,title",
  "selledgems"=>"Gems on the market to sell,int",

Now connect village.php to your gemshop or where ever you'd like it.
It is currently configured for the Village by default.
--------------------------------------------------------------------------

/ Version History:
 Additions for 1.9b by BEM (of tqfgames)
  - added an exponential weighting for gem prices based on gems
      so that the price function would be smooth.

 Ver 1.9 by Anpera and Strider (of Legendgard)
   - This is a further evolution of vesa.php with variable gem prices,
       stock limited by what is sold and quite a bit more dialogue. (Anpera)
   - Translated from German by Strider for Anpera / Added more story and the character Jen (SS)
      for the curious, Strider choose "Jen" from the movie, "The Dark Crystal" by Jim Henson & Brian Froud
    cheap advertising for Brian and Strider ( http://www.labyrinthmasquerade.com )

concept and first gemshop -
Ver Alpha FORKED FROM : jen.php
and Alpha FORKED FROM : vessa.php
**ss**************************ss************/
// -Originally by: Anpera
// English Translation & Story by Lonestrider

// -Contributors: Anpera, BEM, Strider
// Mar 2004  - Legendgard Script Release (ss)

if ($session['user']['level']>14) {
    page_header("Jen's Gem Exchange");
  output("`n`nYou start to approach the Gem Exchange when a few shady characters come out of nowhere. One of them brushes against your shoulder and whispers
  `%'Don't you think it's time to kill the dragon?'");
  addnav("Return to the Village" ,"village.php");}
else{
  page_header ("Jen's Gem Exchange");

  // constants controlling the cost function
  $sprop = 0.50; // cost to sell a gem as a proportion of cost to buy a gem
  $b0 = 2000; // cost to buy a gem if Jen had infinite gems
  $b1 = 20000; // cost to buy a gem if Jen has zero gems
  $maxGems = 1000; // maximum number of gems Jen will buy
  $gemsHalf0 = 55; // number of gems at which Jen will buy at half max cost
  $pretifyGold = 10; // costs are in this multiple

  $k = -(log($b1-$b0)-log($b1/2-$b0))/$gemsHalf0;
  $b = $b1-$b0;
  $c = $b0;

  $gems = abs((int)$_POST[gemcount]);
//  $gems =array( 1=>1,2,3); //the original gem array before you could input your own.
  $curNumGems = getsetting("selledgems",0);
  $cgtc = 0;
  $scost = $pretifyGold*(round(round(($b*exp($k*($curNumGems))+$c)*$sprop)/$pretifyGold));
  foreach ($gems as $i => $value) {
      $cgc = $pretifyGold*(round(round($b*exp($k*$curNumGems)+$c)/$pretifyGold));
      $cgtc = $cgtc + $cgc;
      $costs[$i] = $cgtc;
      //$curNumGems--; // with this line added costs=buying 1 @ a time
      if ($curNumGems<0) {$curNumGems=0;}
  }

//buying gems//
  if ($_GET[op]=="buy"){
    if ($session[user][gold]>=$costs[$_GET[level]]){
      if (getsetting("selledgems",0) >= $_GET[level]){
        output ("`6Jen`5 takes your `^".($costs[$_GET[level]])." gold `5 and gives you `#".($gems[$_GET[level]])."`5 Gem".($gems[$_GET[level]]>=2?"s":"").".`n`n");
        $session[user][gold]-=$costs[$_GET[level]];
        $session[user][gems]+=$gems [$_GET[level]];
        if (getsetting("selledgems",0) -$_GET[level]<1){
          savesetting ("selledgems","0");
        }else{
          savesetting ("selledgems",getsetting("selledgems",0)-$_GET[level]);
        }
      }else{
         output ("`6Jen`5 stares at you with his mixmatched eyes and shakes his head. `#\"`3 I'm afraid I don't have anymore gems for sale.`#\"`5`n`n`n" );
      }
    }else{
      output ("`6Jen`5 glares at you and curses under his breath when he realizes, you don't have the money. He looks to a few of his shadowy friends in the room and you suddenly feel very uneasy.`n`n" );
    }
  addnav ("Return to Exchange" ,"gemstore.php");

//selling gems//
  }elseif( $_GET[op]== "sell"){
    page_header ("Jen's Gem Exchange");
    if ( $session[user][gems]<1){
      output ("`6Jen`5 shouts `#\"`3 You bloody freeloader. What are you trying to pull!!!`#\"`5`n" );
      output ("The young half-elf calms down in a moment, then reminds you that you have no gems to sell him.`n" );
    }else{
      output ("`6Jen`5 takes your gem and gives you $scost gold.`n`n" );
      $session[user][gold]+= $scost ;
      $session[user][gems]-= 1;
      savesetting ("selledgems" ,getsetting ("selledgems",0)+ 1);
    }
    addnav ("Gem Exchange","gemstore.php");
    addnav ("Return to the Village" ,"village.php");

//need multi-gem support??//
  }elseif( $_GET[op]== "buygems"){
    output ("`6Jen`5 smiles softly and lays a black cloth on the table `#\"`3 How many gems would you like to buy?`#\"`5`n" );
    output ("Jen's mixmatched eyes shimmer once at the prospect of this transaction and he waits patiently for your gems.`n" );
  output("<form action='gemshop.php?op=buy' method='POST'><input name='gemcount' value='0'><input type='submit' class='button' value='Sell'>`n",true);
  /*/ - Legendgard options only - Strider / AKA Lonestrider
  output("What type of gems are they?`n"); - /*/
//  output("<input type='radio' name='type' value='1'> Gems`n",true);
//  output("<input type='radio' name='type' value='2'> Firegems`n",true);
//  output("<input type='radio' name='type' value='3'> Crystals</form>",true);
  addnav("","gemstore.php?op=buy");

  }else{
    checkday ();
    page_header ("Rogue's Gem Exchange");
    output("`c`bThe Rogue Gem Exchange`b`c");
    output ("`5`nYou walk into `6Jen's Gem Exchange`5 and notice several gypsy rogues standing around the desk where
    `6Jen `5sits by his scales. The shimmer of gems dances upon the ceiling and the group of suspecious characters stroll to the back of the shop.
  They watch with a focused intensity as you enter. The young half-elf stares at you with a pair of mix-matched eyes, one green and one blue.
  His colorful clothing and carefree hair reminds you of the gypsy caravans that frequent the deep forests. He points you to the current gem prices with a small smile and bright eyes.");
    output ("`n`n`6Jen`5 tells you he currently has `#" .getsetting ("selledgems" ,0). "`5 Gems in the open market." );
    if (getsetting ("selledgems",0)>=1000) output("`7 `nAs you examine the prices, Jen shakes his head and says, `n`n`#\"`3I'm not interested in buying any more gems. But, we're selling them REAL cheap! These prices won't last!`#\"`7" );
    if (getsetting("selledgems",0)<=2) output("`7 `nAs you look over the prices, Jen sighs loudly. He shakes his head and says, `n`n`#\"`3I have to tell you, we're REALLY low on gems. But you can see I'm willing to pay premium if you've got some to sell!`#\"`7" );
    addnav ("Gem Exchange");
    if ( $session['user']['level']<15){
/*/// Old Navigation options
    addnav ("Buy 1 Gem ($costs[1] Gold)" ,"gemstore.php?op=buy&level=1" );
    addnav ("Buy 2 Gems ($costs[2] Gold)" ,"gemstore.php?op=buy&level=2" );
    addnav ("Buy 3 Gems ($costs[3] Gold)" ,"gemstore.php?op=buy&level=3" );
/*///

//New Navigation options
    addnav ("Buy 3 Gems ($costs[3] Gold)" ,"gemstore.php?op=buy&level=3" );
    }
    if ($session[user][level]>3 && getsetting("selledgems",0)<1000) addnav ("Sell 1 Gem ($scost Gold)" ,"gemstore.php?op=sell" );
    addnav ("Other");


    addnav ("Return to the Village" ,"village.php");
  }
}
page_footer ();
?>