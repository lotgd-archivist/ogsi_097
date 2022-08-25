<?php
/*
Abandonded Castle Maze
Author Lonny Luberts
with Mazes by Lonny, Kain (Paul Syverson), Tundrawolf, Hermione, Blayze of http://www.pqcomp.com/logd
version 1.1
June 2004

add to dragon.php after ,"beta"=>1
,"mazeedit"=>1

Mysql inclusions
ALTER TABLE accounts ADD `mazeedit` text NOT NULL
ALTER TABLE accounts ADD `maze` text NOT NULL
ALTER TABLE accounts ADD `mazeturn` int(11) NOT NULL default '0'
ALTER TABLE accounts ADD `pqtemp` text NOT NULL

pqtemp is used in a number of my mods for a temporary (recyclable) place to store info that
I do not want players to see on the url.
Mazes must always start at location 6!
Location 6 should ALWAYS be a piece with a south nav for continuity.
Mazes can end anywhere, and one should use every piece of the grid for their maze
There is no BLANK maze piece... I could code this, however I would rather make
dead ends for the player.  At present there is no limit to the number of times a
player can enter and do a maze.

I did not code this mod with any database access as an admin may want to let users
make maps!  A bad map could cause errors!  Make sure all maps do NOT have any X's
(there is checking for this and the app will not die, but your player will), make
sure all corridors connect or terminate properly or you will have a confusing and
unrealistic maze!  Do NOT use too many traps as players will no longer use a feature
that constantly kills them.  Do NOT use more than one exit... the app allows for this
however 2 exits will confuse the heck out of a player.

there is code for potions, chow and trading/lonny's castle items in the random event routine.. comment
these out if you are not using these mods!
*/
require_once "common.php";
checkday();
page_header("Il Castello Abbandonato");
$session['user']['locazione'] = 100;
if ($session['user']['hitpoints'] > 0){}else{
    redirect("shades.php");
}
//checkevent();
if ($_GET['op'] == "" and $_GET['loc'] == "" and $_GET['suicide'] == ""){
    output("`c`b`&Il Castello Abbandonato`0`b`c`n`n");
    if ($session['user']['dragonkills'] > 7){
        $session['user']['turns']-=1;
        $session['user']['playerfights']-=1;
        if ($session['user']['reincarna']) $session['user']['playerfights']-=1;
        output("`2Non appena varchi l'ingresso del Castello Abbandonato, la massiccia porta dietro di te si chiude con un sordo rumore. E, ");
        output("nonostante numerosi tentativi, non riesci a smuovere nemmeno di un millimetro il pesante portone! `n`nA quanto pare sembra che dovrai trovare ");
        output("un'altra via d'uscita per andartene da questo luogo tetro e spettrale!`n");
        output("Ti guardi attorno, e nella fioca luce intravedi che negli stretti passaggi dei corridoi del castello sono  ");
        output("abbandonati ovunque rifiuti e i resti scheletrici degli ospiti che sono transitati prima di te.`n");
        if ($session['user']['hashorse']>0){
            output("`nÈ un peccato che il tuo {$playermount['mountname']} non possa venire con te, ora sei completamente sol".($session[user][sex]?"a":"o").".`n");
        }
        output("Inoltre, questo luogo sembra risucchiare le tue energie ed ogni attacco speciale in tuo possesso non è utilizzabile.`n`n");
        /*if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
        $_GET['skill']="";
        if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
        $session['bufflist']=array();
        }
        */
        if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
            $_GET['skill']="";
            if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
            $session['bufflist']=array();
        }

        $locale=6;
        $session['user']['mazeturn']=0;
        //they have to do an unfinished maze.
        if ($session['user']['maze']==""){
            //maze generation array.  Mazes are premade.
            //as you add mazes make sure you change the e_rand value to match your quantity of mazes
            if ($session['user']['superuser'] > 2){
                $session['switch'] = $_POST['labi'];
            } else {
                $session['switch']= e_rand(1,76);
            }
            switch($session['switch']){
                case 1:
                    $session['author']= "Lonny";
                    //title: uno
                    $maze = array(j,d,d,d,b,c,k,o,d,d,k,f,d,b,d,a,n,i,d,b,d,e,i,d,c,k,m,j,d,p,g,o,e,o,b,k,i,k,g,j,n,f,k,g,o,e,g,j,e,i,a,b,a,h,g,j,h,i,h,i,n,g,i,e,j,e,i,d,k,j,d,d,h,l,i,h,g,j,d,h,g,o,b,d,c,d,d,h,i,b,d,c,k,g,j,d,b,d,k,j,c,d,d,e,i,a,d,a,d,e,i,d,d,n,i,k,i,k,i,d,e,o,d,d,b,b,a,k,i,d,d,h,o,d,d,h,m,z,i,d,d,d,n);
                    break;
                case 2:
                    $session['author']= "Kain";
                    //title: Kain's Klub
                    $maze = array(j,d,b,d,n,g,j,d,k,j,k,f,k,i,d,b,c,a,k,i,e,g,g,i,k,j,h,l,i,a,n,g,m,g,l,i,h,o,c,k,f,d,h,l,i,e,l,j,k,z,g,g,j,d,e,j,h,i,e,g,i,e,i,c,d,e,i,d,k,m,i,b,c,d,d,n,g,j,d,c,d,k,g,j,d,d,b,h,i,k,j,k,g,g,g,o,k,i,k,j,h,g,f,h,g,f,d,c,n,g,i,d,h,g,j,e,i,d,d,d,e,j,d,d,h,g,f,d,k,j,k,g,i,d,d,d,c,c,n,i,h,i,h);
                    break;
                case 3:
                    $session['author']= "TundraWolf";
                    //title: woof
                    $maze = array(o,b,k,j,k,f,d,d,b,d,k,j,e,f,c,c,a,d,k,f,k,m,g,g,f,n,l,f,n,f,e,f,k,g,g,i,k,f,a,b,h,i,h,g,i,h,l,i,e,m,f,d,d,n,g,j,b,c,k,m,o,a,b,d,k,g,g,i,b,c,b,b,e,i,d,h,g,i,k,g,j,h,g,i,b,b,k,m,l,i,h,m,j,h,j,e,i,c,k,f,b,n,z,g,j,h,f,b,k,g,g,g,j,h,m,g,j,c,e,g,g,g,i,h,o,k,m,m,j,c,e,g,i,d,d,d,c,d,d,c,d,h,m);
                    break;
                case 4:
                    $session['author']= "Hermione";
                    //title: easy 1
                    $maze = array(j,d,d,d,b,c,b,b,k,j,k,i,d,d,d,a,d,c,h,i,h,g,j,d,d,n,f,d,d,d,d,d,h,f,d,d,d,a,d,d,d,d,d,k,f,d,d,d,e,j,d,d,d,d,e,f,d,d,d,a,a,d,d,d,d,h,i,d,d,d,a,c,d,d,n,o,k,j,d,d,n,g,j,d,d,d,d,e,i,d,d,d,a,a,b,b,b,b,h,o,d,d,d,e,g,f,e,f,a,n,o,b,d,d,e,g,g,f,a,a,n,j,c,d,d,e,i,e,g,g,f,n,z,d,d,d,h,o,c,c,c,c,n);
                    break;
                case 5:
                    $session['author']= "TundraWolf";
                    //title: woof woof
                    $maze = array(j,b,b,k,j,a,b,b,b,d,k,f,a,a,h,g,g,f,c,e,j,e,f,a,e,j,h,g,f,d,h,g,g,f,c,h,g,o,h,i,d,k,g,m,g,j,d,h,j,d,d,k,g,i,k,g,i,d,k,f,d,k,g,g,j,h,f,b,k,g,i,k,g,g,g,i,k,f,e,g,i,k,g,g,g,g,j,h,f,a,h,l,i,h,g,m,g,i,k,f,a,d,a,d,d,h,z,g,j,h,f,a,k,g,j,b,k,g,g,i,k,g,i,e,f,c,a,e,g,m,j,e,m,o,h,i,d,c,h,i,d,h,m);
                    break;
                case 6:
                    $session['author']= "Hermione";
                    //title: eZ
                    $maze = array(j,d,d,d,d,e,z,d,d,d,k,i,d,d,d,k,m,j,d,d,k,g,j,d,d,k,i,d,h,o,k,g,g,g,j,k,i,d,d,d,d,e,g,g,g,g,i,d,d,d,d,k,g,g,g,g,g,j,d,d,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,g,l,g,g,g,g,g,g,g,g,g,g,i,h,g,g,g,g,g,g,g,g,i,d,d,h,g,g,g,g,g,g,i,d,d,d,d,h,g,g,g,g,i,d,d,d,d,d,d,h,g,g,i,d,d,d,d,d,d,d,d,c,h);
                    break;
                case 7:
                    $session['author']= "Lonny";
                    //title: deuce
                    $maze = array(j,d,b,k,j,a,d,k,j,d,k,i,k,g,g,g,g,j,h,f,d,e,j,c,e,g,f,h,i,b,a,b,h,i,b,c,a,e,z,o,h,i,a,n,j,c,n,m,g,g,j,k,o,a,n,g,j,b,n,g,i,h,g,l,i,k,f,h,i,n,i,d,n,g,f,d,e,i,b,d,d,d,d,n,g,i,d,e,j,h,o,d,k,o,k,f,d,k,g,f,b,b,d,c,n,g,i,n,g,g,g,g,g,o,d,k,f,n,j,h,g,g,i,e,j,k,g,g,j,h,j,e,i,n,i,h,i,c,c,h,o,h,m);
                    break;
                case 8:
                    $session['author']= "Lonny";
                    //title: MegaG
                    $maze = array(j,b,b,b,b,c,b,b,b,b,k,i,h,g,m,g,j,a,a,a,a,e,j,d,c,n,g,g,g,g,g,g,g,g,j,d,d,h,g,g,g,g,g,g,g,i,d,b,k,g,g,g,g,g,g,g,j,k,m,g,g,g,g,g,g,m,i,h,f,k,g,g,g,g,g,i,k,j,d,h,g,g,g,g,g,i,k,g,g,j,d,h,g,g,g,i,k,g,g,g,g,o,b,e,g,f,k,g,g,g,g,i,n,g,m,m,m,g,g,g,g,g,j,k,i,n,z,o,h,g,g,g,i,h,i,d,d,h,o,d,h,m,m);
                    break;
                case 9:
                    $session['author']= "Lonny";
                    //title: MegaD
                    $maze = array(j,d,d,d,d,c,d,d,d,d,k,f,d,d,d,d,d,d,d,d,k,g,f,d,d,d,d,d,d,d,d,h,g,f,d,d,d,d,d,d,d,d,k,g,f,d,d,d,d,d,d,d,k,g,g,f,d,d,d,d,d,d,n,g,g,g,f,d,d,d,d,d,n,j,h,m,m,f,d,d,d,d,d,d,h,j,n,z,f,d,d,d,d,d,d,d,h,l,g,f,d,d,d,d,d,d,d,d,h,g,f,d,d,d,d,d,d,d,d,d,h,f,d,d,d,d,d,d,d,d,d,n,i,d,d,d,d,d,d,d,d,d,n);
                    break;
                case 10:
                    $session['author']= "Hermione";
                    //title: Deadend City
                    $maze = array(o,b,b,b,b,a,b,b,b,b,n,o,a,e,m,m,g,g,m,f,a,n,o,a,e,j,k,g,i,k,f,a,n,o,a,e,g,g,i,k,g,f,a,n,o,a,e,g,i,k,g,g,f,a,n,o,a,e,i,k,g,g,g,f,a,n,o,a,e,l,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,f,e,g,g,g,f,a,n,o,a,a,a,a,a,a,a,a,a,n,j,a,a,a,a,a,a,a,a,a,k,m,m,m,m,m,m,m,m,m,m,z);
                    break;
                case 11:
                    $session['author']= "Blayze";
                    //title: Hot
                    $maze = array(j,k,o,k,j,e,j,b,b,d,k,g,i,k,g,g,g,m,m,i,k,g,i,k,g,g,g,f,d,k,j,h,g,j,h,g,g,g,f,k,i,c,n,g,i,k,f,h,g,g,g,j,d,k,g,j,h,i,k,g,g,g,i,k,i,h,i,k,j,h,g,g,i,k,i,d,k,j,h,i,d,h,g,o,a,d,d,h,i,d,b,d,d,c,d,c,d,b,n,j,n,g,j,n,j,k,l,l,i,k,g,z,m,f,d,h,f,a,e,j,h,g,i,d,c,d,d,h,g,g,i,k,i,d,d,d,d,d,d,h,i,d,h);
                    break;
                case 12:
                    $session['author']= "Kain";
                    //title: Kain's Krypt
                    $maze = array(j,n,l,j,d,c,k,j,k,o,k,i,b,a,c,b,b,c,h,f,k,g,j,e,f,d,h,i,k,j,h,f,e,m,g,i,k,l,j,c,e,l,g,g,j,c,k,m,f,c,b,c,h,f,h,f,n,f,d,c,d,e,j,d,c,k,i,b,h,o,b,z,i,c,d,k,g,o,a,b,n,i,k,o,d,d,a,h,j,h,f,n,l,i,d,k,l,i,k,i,b,c,d,c,k,j,a,c,k,g,l,g,l,j,d,h,g,g,j,e,m,f,h,f,e,j,d,a,c,e,i,k,i,d,h,i,c,n,i,n,i,n,m);
                    break;
                case 13:
                    $session['author']= "Lonny";
                    //title: dizzy
                    $maze = array(j,k,j,b,s,g,j,d,d,d,k,g,g,g,g,j,a,h,j,d,k,g,g,g,g,g,g,g,l,g,l,g,g,m,i,h,i,h,g,g,g,i,h,g,j,b,d,b,d,e,i,c,d,d,h,f,a,d,a,k,f,d,d,d,d,k,i,h,q,c,h,g,o,d,d,d,h,j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,k,g,j,d,d,k,g,g,g,j,k,g,g,g,j,k,g,g,g,g,z,g,g,g,g,g,m,g,g,g,i,d,h,g,g,g,i,d,h,g,i,d,d,d,h,m,i,d,d,d,h);
                    break;
                case 14:
                    $session['author']= "Kain";
                    //title: Kain's Korner
                    $maze = array(j,d,d,d,d,c,d,d,d,d,k,i,d,k,j,d,d,k,j,d,d,h,j,k,g,g,j,d,e,i,d,d,k,g,i,h,g,g,l,i,d,d,d,h,i,d,d,h,g,g,j,d,d,d,k,j,d,b,d,c,c,c,d,b,d,h,g,o,e,j,d,d,d,d,e,j,k,g,o,e,f,d,d,d,d,e,m,g,g,o,e,g,l,j,k,z,f,d,h,i,d,h,g,g,g,g,g,i,d,k,j,d,d,h,g,g,g,g,j,k,g,f,d,d,d,h,g,g,g,g,g,g,i,d,d,d,d,h,i,c,h,i,h);
                    break;
                case 15:
                    $session['author']= "Kain";
                    //title: Kain's Konfusion
                    $maze = array(j,d,d,k,j,c,d,d,d,d,k,i,d,k,g,g,l,j,d,d,k,g,j,d,e,i,h,i,c,d,k,i,h,g,j,e,j,k,j,d,n,g,j,k,g,g,i,h,g,i,k,j,h,g,g,g,i,k,j,h,j,h,i,d,h,g,g,o,h,g,j,h,j,d,k,j,h,i,k,j,h,i,b,e,z,g,i,k,j,h,m,j,k,g,i,d,e,j,h,g,j,k,z,g,f,d,n,g,i,k,g,g,g,j,h,g,j,d,c,k,g,f,h,g,g,j,e,g,o,d,h,g,i,d,h,i,h,i,c,d,d,d,h);
                    break;
                case 16:
                    $session['author']= "Lonny";
                    //title: jump down turnaround
                    $maze = array(j,d,d,d,d,c,d,d,d,d,k,i,d,d,d,d,b,d,d,d,d,h,j,d,d,d,d,c,d,d,d,d,k,g,j,b,d,d,d,d,d,d,d,h,g,g,g,j,k,j,k,j,k,j,k,g,g,i,h,i,h,i,h,i,h,g,g,i,d,d,d,d,k,j,d,d,h,i,d,d,d,d,k,g,i,d,d,k,j,b,b,b,b,h,g,j,d,d,h,f,a,a,a,e,j,h,f,b,d,n,f,a,r,a,e,g,j,a,e,j,k,f,a,a,a,e,g,i,c,h,g,g,i,c,c,c,h,i,d,d,d,h,z);
                    break;
                case 17:
                    $session['author']= "Lonny";
                    //title: Into the Vortex
                    $maze = array(j,b,b,b,b,c,b,b,b,b,k,f,h,m,m,i,p,h,m,m,i,e,f,d,d,d,d,d,d,d,d,d,e,f,n,j,d,d,d,d,d,k,o,e,f,n,g,j,d,d,d,k,g,o,e,f,n,g,g,j,d,k,g,g,o,e,f,n,g,g,g,z,h,g,g,o,e,f,n,g,g,i,d,d,h,g,o,e,f,n,g,i,d,d,d,d,h,o,e,f,n,i,d,d,d,d,d,k,o,e,f,d,k,j,d,d,d,d,h,o,e,f,k,g,g,l,l,l,l,l,j,e,i,c,c,c,c,c,c,c,c,c,h);
                    break;
                case 18:
                    $session['author']= "Kain";
                    //title: Halls of Konfusion
                    $maze = array(j,b,d,d,k,f,d,d,k,j,k,g,i,d,k,i,c,d,k,i,h,g,g,j,d,c,k,j,d,h,j,k,g,f,h,o,d,h,m,j,d,e,i,h,g,l,j,d,d,k,i,k,i,d,k,g,g,g,j,d,h,j,h,j,n,g,i,e,g,i,k,o,c,k,g,l,g,j,h,i,k,i,d,k,g,i,a,e,g,l,j,h,o,k,z,i,k,f,e,g,g,g,j,n,i,d,k,g,f,h,f,h,g,i,k,j,d,c,h,i,k,g,o,c,k,g,i,d,d,d,d,e,i,d,n,i,c,d,d,d,d,d,h);
                    break;
                case 19:
                    $session['author']= "TundraWolf";
                    //title: Twisted Dead End
                    $maze = array(j,d,d,d,k,g,o,d,d,d,k,g,j,d,n,i,a,d,d,d,d,h,g,g,j,d,k,i,b,b,d,d,k,g,g,f,d,a,n,g,i,d,d,e,g,g,g,l,g,j,a,d,d,n,g,g,g,i,h,g,f,h,o,d,d,e,g,g,o,d,a,h,o,d,d,d,e,g,g,j,k,f,d,d,d,d,k,g,g,g,g,i,a,k,l,j,k,g,g,g,g,f,d,h,i,h,i,c,e,g,g,g,g,j,k,z,s,d,k,g,g,g,g,g,g,g,i,d,k,g,m,g,i,h,i,h,i,d,d,h,i,d,h);
                    break;
                case 20:
                    $session['author']= "Lonny";
                    //title: nothing special
                    $maze = array(z,j,d,k,j,c,d,d,d,d,k,g,i,d,e,i,d,b,d,d,k,g,i,d,k,i,d,d,h,o,k,g,g,j,k,g,o,k,j,d,d,h,g,g,g,f,e,j,h,i,d,k,j,h,g,g,g,g,i,k,j,d,h,g,j,e,g,g,g,j,h,g,j,b,h,g,g,g,g,g,i,d,c,h,g,j,e,g,i,h,g,j,d,n,j,h,g,i,e,j,d,h,f,d,d,h,o,c,n,g,i,d,k,m,j,d,b,d,d,d,e,j,k,i,d,h,l,g,j,d,s,g,m,i,d,d,d,c,h,i,d,d,h);
                    break;
                case 21:
                    $session['author']= "Hermione";
                    //title: chamber of secrets
                    $maze = array(j,k,j,k,o,a,b,b,b,n,z,g,g,f,e,j,s,e,m,f,k,g,g,g,f,e,i,d,a,n,j,h,g,g,g,f,a,b,d,c,b,c,k,g,g,g,f,e,g,j,d,a,n,g,g,g,g,f,e,i,h,o,a,b,k,g,g,g,f,e,l,j,k,m,m,g,g,f,a,a,a,a,h,i,d,d,h,g,g,g,f,e,i,d,d,d,d,k,g,g,g,f,e,j,d,d,d,d,h,g,g,g,f,e,i,d,d,d,d,k,g,f,a,c,h,j,d,d,d,d,h,g,i,c,n,o,c,d,d,d,d,d,h);
                    break;
                case 22:
                    $session['author']= "Lonny";
                    //title: Crossroads
                    $maze = array(j,d,d,d,d,a,d,d,d,d,k,i,d,d,d,d,a,d,d,d,d,e,j,d,d,d,d,a,d,d,d,d,h,f,d,b,b,b,a,b,b,b,b,k,g,j,a,a,a,a,a,a,a,a,e,i,c,c,c,c,a,c,c,c,c,h,j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,a,d,d,d,k,g,g,g,j,b,d,a,d,d,k,g,s,g,g,f,a,d,a,d,d,e,g,z,g,g,i,c,d,a,d,d,h,g,g,g,i,d,d,d,c,d,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
                    break;
                case 23:
                    $session['author']= "Lonny";
                    //title: Loop D Loop
                    $maze = array(j,b,d,b,d,a,k,j,k,j,k,g,f,k,f,k,g,g,f,h,g,g,g,i,h,i,h,g,i,a,d,c,h,g,j,k,j,k,f,k,f,d,b,k,g,i,e,i,e,i,e,f,k,i,h,i,d,c,b,c,n,g,f,h,j,k,j,b,b,e,j,d,e,f,k,g,g,f,h,i,h,g,j,h,i,c,c,e,f,b,b,k,g,g,j,d,d,k,g,f,a,a,e,g,g,g,j,k,g,g,f,a,a,e,g,g,g,z,g,g,g,f,c,c,h,g,g,i,d,h,g,g,i,d,d,d,h,i,d,d,d,h,m);
                    break;
                case 24:
                    $session['author']= "Lonny";
                    //title: 143
                    $maze = array(o,d,b,d,d,c,k,j,d,d,k,j,d,a,d,d,n,f,h,o,d,h,g,o,c,n,j,k,f,d,d,b,k,f,d,d,d,c,e,f,d,d,h,m,i,d,d,d,n,g,f,d,d,d,k,j,d,b,b,d,h,g,j,d,d,h,f,k,g,g,l,o,h,i,d,b,n,g,g,g,g,g,z,d,d,k,i,k,g,g,i,h,g,i,d,n,g,j,h,g,g,j,d,a,d,d,d,e,i,k,g,g,g,l,g,j,d,n,g,j,h,g,g,g,g,g,g,o,d,h,i,k,m,i,c,h,m,i,d,d,d,d,h);
                    break;
                case 25:
                    $session['author']= "Lonny";
                    //title: 143 A&F
                    $maze = array(o,d,b,d,d,c,k,j,d,d,k,j,d,a,d,d,n,f,h,o,d,h,g,o,c,n,j,k,f,d,d,b,k,f,d,d,d,c,e,f,d,d,h,m,i,d,d,d,n,g,f,d,d,d,k,j,d,b,b,d,h,g,j,d,d,h,f,k,g,g,l,o,h,i,d,b,n,g,g,g,g,g,z,d,d,k,i,k,g,g,i,h,g,i,d,n,g,j,h,g,g,j,d,a,n,o,d,e,i,k,g,g,g,l,g,j,b,n,g,j,h,g,g,g,g,g,g,i,d,h,i,k,m,i,c,h,m,i,d,d,d,d,h);
                    break;
                case 26:
                    $session['author']= "Lonny";
                    //title: updown backforth
                    $maze = array(j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,e,j,b,b,b,b,b,b,b,b,b,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,c,c,c,c,c,c,c,c,c,h,f,d,d,d,d,d,d,d,d,d,k,g,j,d,d,d,d,d,d,d,k,g,g,i,d,d,d,d,k,j,n,z,g,i,d,d,d,d,d,h,i,d,d,h);
                    break;
                case 27:
                    $session['author']= "Excalibur";
                    //titolo: So near so far
                    $maze = array(j,d,d,d,d,c,d,d,b,d,k,g,j,k,j,b,z,q,k,g,l,g,g,g,g,g,m,l,g,f,a,c,h,g,g,i,h,j,h,g,g,i,d,k,g,i,d,d,c,k,g,i,b,d,h,g,j,d,d,d,h,f,p,g,j,k,g,i,d,d,d,b,c,k,i,h,g,g,o,d,d,d,h,j,h,j,k,g,g,j,d,d,d,k,g,j,h,f,e,g,i,d,d,k,f,h,i,k,g,g,i,d,d,d,a,r,b,d,h,g,g,j,d,s,d,c,c,c,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
                    break;
                case 28:
                    $session['author']= "Poker";
                    //titolo: l\'Hidra maledetta
                    $maze = array(j,d,k,j,k,g,j,d,d,b,k,f,k,i,h,i,c,h,o,d,c,e,g,g,o,b,d,d,k,l,j,d,e,g,i,d,c,d,d,e,i,e,s,e,f,k,o,d,d,d,a,b,a,d,e,g,g,j,d,d,d,a,a,c,n,g,m,g,g,j,k,j,e,g,j,b,h,l,g,m,g,m,g,g,g,g,f,k,g,g,o,c,d,c,e,g,f,e,g,f,a,d,d,k,j,c,a,a,h,m,g,i,d,k,g,f,n,g,g,j,k,i,k,j,h,m,m,j,e,g,m,g,o,h,i,d,n,z,c,h,i,d,h);
                    break;
                case 29:
                    $session['author']= "Poker";
                    //titolo: cerchio
                    $maze = array(j,d,d,d,d,a,d,d,d,d,k,f,d,p,b,d,c,d,b,r,d,e,f,d,d,c,n,z,o,c,d,d,e,g,j,d,k,j,c,k,j,d,k,g,g,g,j,h,i,b,h,i,k,g,g,g,g,i,d,n,i,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,f,d,d,d,d,a,d,d,d,d,e,g,l,j,b,k,g,j,d,d,k,g,g,i,h,s,m,f,h,j,k,g,g,g,o,b,h,j,e,j,h,m,g,g,g,o,c,d,h,g,i,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h);
                    break;
                case 30:
                    $session['author']= "Excalibur";
                    //titolo: Many rows
                    $maze = array(j,d,d,d,z,f,d,d,d,d,k,g,j,d,d,k,f,d,d,d,d,h,g,g,j,k,i,c,b,b,d,d,k,g,g,g,f,d,d,c,a,d,d,h,g,m,e,f,b,b,b,a,b,k,l,f,p,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,g,g,g,g,g,j,h,g,g,g,g,g,g,g,g,g,i,k,g,g,g,g,g,g,g,g,g,j,h,g,g,g,g,g,g,g,g,i,c,k,g,m,m,m,m,m,g,i,d,d,h,i,d,d,d,d,d,h);
                    break;
                case 31:
                    $session['author']= "Excalibur";
                    //titolo: Be Careful
                    $maze = array(j,d,d,d,d,a,d,d,d,d,k,r,j,d,d,d,c,k,j,d,d,h,j,h,j,d,d,d,h,g,o,d,k,m,j,h,j,d,d,k,i,d,k,g,z,g,j,h,j,k,i,d,d,h,g,g,g,i,d,h,i,d,d,d,b,e,g,i,b,b,b,b,b,b,k,f,e,i,k,g,f,e,f,e,f,e,f,e,l,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,g,g,g,f,e,f,e,f,e,f,e,i,c,h,i,h,i,h,i,h,i,h);
                    break;
                case 32:
                    $session['author']= "Aris";
                    //titolo: Aris 1
                    $maze = array(j,b,b,b,k,f,b,b,b,b,k,g,g,i,h,i,a,a,a,c,c,h,g,f,d,k,j,a,e,g,j,n,l,g,i,k,m,f,a,e,g,f,k,g,f,n,g,l,i,c,e,g,g,i,e,g,j,a,a,d,k,i,h,f,k,g,g,g,f,a,b,a,d,b,h,g,g,g,f,a,a,h,i,d,c,d,h,g,g,i,a,a,k,j,n,o,d,d,e,f,k,i,a,h,i,k,j,b,b,e,f,e,z,m,o,d,e,i,c,c,e,m,g,f,d,p,b,c,d,d,b,h,o,h,i,d,d,c,n,o,d,c,n);
                    break;
                case 33:
                    $session['author']= "Aris";
                    //titolo: Bastardo veramente
                    $maze = array(j,d,d,d,d,c,d,d,d,d,k,g,j,d,n,o,d,d,d,d,k,g,f,e,j,d,d,d,b,d,k,f,e,g,g,g,l,o,d,c,k,g,g,g,g,g,f,e,j,k,l,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,z,f,e,g,m,g,g,g,g,g,f,q,e,g,m,j,e,g,g,f,e,i,c,h,g,l,g,g,g,g,g,i,d,d,n,m,g,g,g,g,g,i,d,d,b,d,d,h,g,g,g,i,b,n,o,c,d,d,d,h,g,i,d,c,d,n,o,d,d,d,d,h);
                    break;
                case 34:
                    $session['author']= "Aris";
                    //titolo: multi trappola
                    $maze = array(j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,r,a,a,a,a,a,e,p,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,q,a,a,a,a,e,f,a,a,r,a,a,r,a,a,a,e,f,a,a,a,a,z,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,s,f,a,a,a,s,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,h);
                    break;
                case 35:
                    $session['author']= "Poker";
                    //titolo: Il cerchio
                    $maze = array(j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,c,d,d,d,k,g,g,g,j,d,d,b,d,d,k,g,g,g,g,g,j,d,c,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,f,p,g,g,g,g,g,g,g,g,f,e,z,e,g,f,e,g,g,g,g,g,g,p,e,g,g,g,g,g,g,g,g,i,d,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h);
                    break;
                case 36:
                    $session['author']= "Poker";
                    //titolo: il cerchio2
                    $maze = array(j,d,d,d,d,a,d,d,d,d,k,g,j,d,d,d,c,d,d,d,k,g,g,g,j,d,q,b,d,d,k,g,g,g,g,g,j,d,c,s,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,f,p,e,g,g,g,g,g,g,g,f,e,z,e,g,f,e,g,g,g,g,p,f,p,e,g,p,g,g,g,g,g,g,i,d,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,i,d,d,d,d,c,q,d,d,d,h);
                    break;
                case 37:
                    $session['author']= "Excalibur";
                    //titolo: Attenzione
                    $maze = array(j,d,d,z,o,c,b,d,d,d,k,g,j,d,b,b,d,c,d,d,d,e,g,i,b,e,g,j,b,d,d,d,e,g,j,a,c,c,c,c,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,e,g,g,f,d,d,d,d,d,d,d,h,g,g,f,d,d,d,d,d,d,d,s,i,h,f,d,d,d,d,d,d,d,e,j,b,a,d,d,d,d,d,d,d,e,i,c,c,d,d,d,d,d,d,d,h);
                    break;
                case 38:
                    $session['author']= "Excalibur";
                    //titolo: Spirale Maledetta
                    $maze = array(j,d,d,d,k,i,d,d,d,d,k,g,j,d,k,g,z,d,d,d,k,g,g,i,k,g,i,d,d,d,k,g,g,g,j,h,i,d,d,d,k,g,g,g,g,g,j,d,d,d,k,g,g,g,g,g,g,g,j,d,k,g,g,g,g,g,g,g,g,g,j,e,g,g,g,g,g,g,g,g,g,g,p,g,g,g,g,g,g,g,g,g,i,d,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,d,d,d,d,h,g,i,d,d,d,d,d,d,d,d,d,h);
                    break;
                case 39:
                    $session['author']= "Excalibur";
                    //titolo: Sei fortunato ?
                    $maze = array(j,b,k,j,k,g,j,b,b,b,r,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,a,e,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,a,e,i,a,a,e,g,g,f,a,a,a,e,j,a,a,e,g,g,f,a,a,a,e,i,a,a,e,g,g,f,a,a,a,e,j,c,a,e,g,g,f,a,a,a,e,i,b,a,e,g,g,f,a,c,a,e,j,h,i,e,g,g,i,h,j,e,g,g,j,k,p,i,c,d,d,h,g,g,i,h,i,c,n,z,d,d,d,h,m);
                    break;
                case 40:
                    $session['author']= "Aris";
                    //titolo: le tre rose bianche
                    $maze = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,p,g,g,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,c,h);
                    break;
                case 41:
                    $session['author']= "Aris";
                    //titolo: Le tre rose rosse
                    $maze = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,g,g,f,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,p,h);
                    break;
                case 42:
                    $session['author']= "Aris";
                    //titolo: le tre rose nere
                    $maze = array(j,d,b,b,b,c,d,d,b,d,k,g,l,g,m,f,b,k,l,i,k,g,g,g,i,k,g,g,g,g,j,h,g,g,g,j,e,g,g,g,f,a,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,f,h,g,g,g,g,g,m,g,g,g,g,j,h,g,m,g,g,j,h,m,g,m,i,k,g,j,h,g,g,j,d,e,o,b,h,g,m,j,h,g,m,j,h,j,c,k,g,j,c,k,g,j,c,k,g,z,e,g,p,z,g,g,g,z,g,i,d,h,m,i,d,h,m,i,d,h);
                    break;
                case 43:
                    $session['author']= "de Zent";
                    //title: merryChrismas
                    $maze = array(q,l,l,l,j,a,k,l,l,l,q,j,c,c,c,c,a,c,c,c,c,k,m,q,l,l,l,g,l,l,l,q,m,p,j,c,c,c,a,c,c,c,k,p,q,m,q,l,l,g,l,l,q,m,q,q,p,j,c,c,a,c,c,k,p,q,q,q,m,q,l,g,l,q,m,q,q,q,q,r,j,c,a,c,k,r,q,q,q,q,q,m,q,g,q,m,q,q,q,q,q,q,p,q,g,q,p,q,q,q,q,q,q,q,j,a,k,q,q,q,q,q,q,q,q,p,g,p,q,q,q,q,q,q,q,q,q,z,q,q,q,q,q);
                    break;
                case 44:
                    $session['author']= "Teg";
                    //titolo: Incroci
                    $maze=array(j,d,d,b,b,a,b,d,b,b,k,g,j,k,f,a,a,a,k,g,g,g,m,g,g,i,a,a,a,e,i,h,g,j,h,i,k,f,a,a,a,d,d,h,f,k,l,g,f,a,a,a,d,b,n,g,g,i,c,a,a,a,a,b,a,k,f,a,d,n,f,a,a,a,c,e,g,g,g,o,b,a,a,a,a,k,m,g,m,i,k,f,c,a,a,e,f,d,e,j,b,h,m,j,a,a,e,i,b,h,g,g,o,d,c,a,c,h,j,c,k,g,i,b,k,o,c,d,d,c,n,g,i,n,s,i,d,z,o,d,d,d,h);
                    break;
                case 45:
                    $session['author']= "Sook";
                    //titolo: 4 strade per la morte
                    $maze = array(o,d,d,d,d,a,d,d,d,d,k,j,d,b,b,k,g,j,d,d,d,h,f,n,m,m,g,g,f,d,d,d,g,i,k,z,b,e,g,g,p,z,l,g,l,g,p,f,e,g,g,g,o,c,h,f,h,i,h,m,g,i,h,o,d,k,i,d,d,d,d,a,d,d,d,d,h,j,d,d,d,k,g,j,d,d,d,k,g,j,d,k,g,g,g,j,d,k,g,g,g,z,g,g,g,g,g,z,g,g,g,g,p,h,g,g,g,i,p,g,g,g,i,d,d,h,g,i,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h);
                    break;
                case 46:
                    $session['author']= "Excalibur";
                    //titolo: ZigoZago
                    $maze = array(j,d,b,d,k,g,j,d,d,b,k,g,z,i,k,g,g,i,k,l,g,g,g,g,o,e,g,g,j,h,g,g,g,g,i,k,g,g,g,i,k,g,g,g,g,l,g,g,g,g,j,h,g,g,g,i,h,g,g,g,g,i,k,g,g,g,j,d,h,g,g,g,j,h,g,g,g,g,j,k,g,g,g,g,j,c,h,g,g,g,g,g,i,a,g,g,j,d,h,g,g,g,i,k,g,g,g,i,d,k,g,g,i,r,i,a,h,i,d,k,m,g,i,d,d,d,c,d,d,n,i,k,i,d,d,d,d,d,d,d,d,d,h);
                    break;
                case 47:
                     $session['author']= "Sook";
                     //titolo: Cripta Maledetta
                     $maze = array(q,q,q,q,o,a,n,q,q,q,q,q,a,a,a,b,a,b,a,a,a,q,q,a,s,s,a,s,a,s,s,a,q,q,a,s,a,a,a,a,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,a,s,a,a,a,a,a,s,a,q,q,a,a,a,r,a,r,a,a,a,q,q,a,s,a,a,a,a,a,s,a,q,q,a,s,a,r,a,r,a,s,a,q,q,e,o,a,r,a,r,a,n,f,q,q,q,q,q,q,z,q,q,q,q,q);
                     break;
                case 48:
                     $session['author']= "The_Dream";
                     //titolo: l\'impossibile
                     $maze = array(j,d,b,b,b,c,r,b,d,k,z,f,n,g,g,g,j,b,q,b,a,k,g,l,g,g,g,g,f,b,a,a,e,g,g,g,g,g,g,f,e,g,g,g,g,g,g,g,g,g,f,e,g,g,g,g,g,g,g,g,f,h,i,a,e,g,g,g,g,g,g,f,k,z,h,g,g,g,g,g,g,g,i,c,d,k,r,g,f,e,f,a,e,l,l,o,a,n,g,g,r,g,g,g,f,a,n,g,z,g,g,g,g,g,g,m,f,n,f,n,g,g,g,i,c,c,p,a,d,a,n,g,i,c,d,d,d,c,c,d,c,d,h);
                break;
                case 49:
                     $session['author']= "Sook";
                     //titolo: Benvenuti al Manicomio!
                     $maze = array(o,d,d,d,o,a,n,d,d,d,n,i,h,i,c,e,a,f,c,h,i,h,f,a,a,a,e,l,f,a,a,a,e,f,q,a,q,e,l,f,r,a,r,e,f,q,a,q,e,l,f,r,a,r,e,i,c,c,c,h,l,i,c,c,c,h,i,d,d,b,d,d,d,d,d,d,h,i,s,h,d,d,d,d,d,d,d,k,m,m,n,b,d,k,b,k,a,s,k,g,e,k,d,s,n,o,s,c,h,n,j,p,d,p,a,f,j,i,i,o,g,j,g,d,f,f,p,n,k,h,p,l,p,d,m,p,p,z,d,n,m,h,n);
                break;
                case 50:
                     $session['author']= "Caronte";
                     //title: Infernal Trap
                     $maze = array(l,j,d,b,b,a,b,b,d,b,n,i,h,p,h,p,a,p,i,d,c,k,o,d,b,d,d,c,k,j,k,j,h,j,d,c,k,j,d,h,g,g,i,k,g,j,k,m,g,j,d,h,g,j,h,g,g,f,k,g,f,d,k,g,i,k,f,h,g,m,g,g,l,m,g,j,h,g,o,a,n,g,i,c,k,g,i,k,f,k,i,k,f,n,j,h,g,j,h,g,i,k,g,g,j,a,p,g,i,k,i,k,g,g,g,g,g,l,z,j,h,j,h,g,g,p,g,g,g,p,i,k,m,o,h,i,d,h,i,h,i,d,h);
                break;
                case 51:
                     $session['author']= "Randir";
                     //titolo: Randir
                     $maze = array(j,b,b,b,b,a,b,b,b,b,k,g,g,g,g,g,s,g,g,g,g,g,g,f,a,a,a,a,a,g,i,e,g,g,g,a,a,a,h,i,a,a,e,g,g,g,f,e,g,z,b,r,i,e,g,g,g,f,a,e,p,a,e,o,e,g,g,g,g,g,f,i,c,a,b,e,g,g,g,g,g,m,o,b,c,a,e,g,g,f,g,g,j,b,a,k,i,e,g,g,f,c,c,m,i,a,q,d,e,g,g,f,k,l,o,k,f,c,d,e,g,g,m,g,i,d,c,a,d,b,e,g,m,o,c,d,d,d,c,d,c,h,m);
                break;
                case 52:
                     $session['author']= "Randir";
                     //titolo: Randir
                     $maze = array(j,b,b,b,b,a,b,r,b,b,k,g,f,e,f,q,a,a,a,e,g,g,g,f,e,g,i,a,c,s,a,e,g,g,f,e,i,d,a,d,h,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,p,a,e,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,f,e,o,d,a,d,n,g,g,g,g,i,h,o,d,a,d,n,m,g,g,i,d,d,d,d,c,d,d,d,h,z);
                break;
                case 53:
                     $session['author']= "Randir";
                     //titolo: Randir
                     $maze = array(j,n,j,b,b,c,d,d,d,d,r,f,d,e,f,q,z,p,j,d,n,g,g,j,a,a,h,i,e,i,k,l,g,g,f,e,g,j,k,f,d,h,g,g,g,g,g,g,g,g,f,d,b,e,g,g,g,g,i,e,g,s,k,g,g,g,g,g,g,l,g,g,g,f,e,g,g,g,g,f,h,g,g,g,f,e,g,g,g,g,f,k,g,g,g,f,e,g,g,g,g,g,m,g,g,g,f,a,e,g,g,g,g,l,g,g,i,a,e,f,h,g,i,c,c,h,g,o,c,c,c,k,i,d,d,d,d,c,d,d,d,d,h);
                break;
                case 54:
                     $session['author']= "Randir";
                     //titolo: Randir
                     $maze = array(o,d,k,j,k,i,k,j,d,d,k,l,j,a,h,g,j,h,i,d,k,m,i,h,f,n,i,a,k,j,k,f,k,l,j,a,b,b,e,i,h,i,h,g,i,h,g,c,a,a,n,j,k,j,e,l,j,a,k,f,n,j,h,i,a,e,i,h,f,a,e,o,a,b,d,a,h,l,j,e,g,i,k,m,i,k,i,k,i,e,i,a,a,h,j,k,z,j,h,l,f,k,i,c,d,h,m,j,h,l,i,e,g,l,j,k,j,k,i,k,g,o,e,g,i,h,i,h,i,d,a,e,o,c,c,d,d,d,d,d,d,c,h);
                break;
                case 55:
                     $session['author']= "Randir";
                     //titolo: Randir
                     $maze = array(j,d,d,d,b,a,d,d,d,d,k,g,z,d,b,r,c,b,b,b,k,g,g,j,k,i,c,k,m,m,m,g,g,g,g,g,j,k,i,d,k,l,g,g,g,g,g,g,g,j,k,i,c,e,g,g,g,g,g,g,g,g,j,k,m,g,g,g,g,g,g,g,g,g,g,l,g,g,g,g,g,g,g,g,g,i,h,g,g,g,g,g,g,g,i,h,j,k,g,g,g,g,g,g,g,l,j,a,e,g,g,g,g,g,i,h,g,g,f,h,g,g,g,g,g,j,d,c,c,a,n,g,i,h,i,h,i,d,d,d,c,d,h);
                break;
                case 56:
                     $session['author']= "Randir";
                     //titolo: Randir
                     $maze = array(p,b,b,b,b,a,b,b,b,b,p,g,g,j,n,f,a,e,f,h,i,e,g,g,i,k,f,a,e,i,k,j,e,g,g,j,h,f,a,e,j,h,f,e,g,g,i,k,f,a,e,i,k,f,e,g,g,j,h,f,a,e,j,h,f,e,g,g,g,j,r,a,r,i,n,f,e,g,g,f,a,a,a,a,b,e,f,e,g,g,f,a,a,a,e,f,e,f,e,g,g,f,c,c,a,e,g,g,f,e,g,g,f,k,j,a,e,g,g,f,e,g,g,f,s,a,s,e,m,g,f,e,i,h,m,z,m,z,c,z,q,i,h);
                break;
                case 57:
                     $session['author']= "Caronte";
                     //titolo: Infernal Lair
                     $maze = array(l,j,d,b,b,a,b,b,d,b,n,i,h,p,h,p,f,p,i,d,c,n,o,d,b,d,d,c,k,j,b,b,n,j,d,c,k,j,d,h,g,g,i,k,g,j,k,m,g,j,d,h,g,j,h,g,g,f,k,g,f,d,k,g,i,k,f,h,g,m,g,g,l,m,g,j,h,g,o,a,n,g,i,c,k,g,i,k,f,k,i,k,f,n,j,a,p,j,h,g,i,k,g,g,j,a,p,p,c,k,i,k,g,g,g,g,g,f,z,j,h,j,h,g,g,p,g,g,g,p,i,k,m,o,h,i,d,h,i,h,i,d,h,n);
                break;
                case 58:
                     $session['author']= "EvaLowrien";
                     //titolo: ????
                     $maze = array(j,d,d,d,b,l,x,x,l,l,x,g,x,x,x,m,i,d,d,c,a,k,i,k,o,d,d,d,d,b,d,a,e,x,i,k,x,x,x,r,a,b,a,h,j,d,h,x,x,x,x,g,i,c,k,g,p,x,x,x,j,n,g,x,l,g,f,a,d,d,k,g,x,g,x,g,g,m,g,x,x,g,g,l,g,l,g,g,x,g,x,x,a,a,a,a,a,a,h,x,g,x,x,x,x,g,i,a,a,k,x,g,j,b,d,n,g,x,i,k,g,x,f,a,a,z,o,e,x,x,g,g,x,s,c,c,d,d,h,x,x,i,h);
                break;
                case 59:
                     $session['author']= "EvaLowrien";
                     //titolo: EvaLowrien
                     $maze = array(j,d,d,d,d,e,o,d,d,d,k,g,j,d,b,d,c,b,k,o,k,g,g,g,o,e,j,d,h,i,k,i,e,g,g,o,e,g,j,k,j,c,d,h,g,f,n,g,g,i,a,a,d,d,k,g,g,o,e,i,k,i,c,d,k,g,g,f,n,g,j,c,k,l,p,h,g,g,g,o,e,i,d,h,i,d,k,g,g,f,d,c,d,d,k,z,d,c,e,m,f,d,d,d,d,c,d,d,n,g,o,a,d,d,d,d,d,d,d,k,g,j,a,b,b,b,b,b,b,n,g,g,m,m,i,c,c,c,c,c,d,s,h);
                break;
                case 60:
                     $session['author']= "Lilith";
                     //titolo: ????
                     $maze = array(j,d,d,d,d,a,d,d,b,d,k,g,j,d,k,j,a,b,d,c,k,g,g,g,j,h,g,f,e,j,d,h,g,g,g,i,k,g,g,g,g,j,k,g,g,g,j,h,g,g,g,g,g,g,g,g,g,i,k,g,g,g,f,a,a,h,g,g,j,h,g,g,g,g,f,a,k,g,g,i,k,g,g,m,g,g,g,g,g,g,q,g,g,i,d,a,h,g,g,g,g,g,g,g,o,d,a,d,h,g,g,g,f,h,i,b,d,p,d,d,h,f,h,z,d,d,a,d,a,d,d,k,i,d,d,d,d,c,d,c,d,d,h);
                break;
                case 61:
                     $session['author']= "Lilith";
                     //titolo: ????
                $maze = array(j,d,d,d,n,i,d,d,d,d,k,f,b,b,b,b,b,b,b,b,b,e,f,e,f,e,f,c,e,i,h,r,g,f,e,g,g,i,k,g,j,d,k,g,f,e,g,g,o,e,g,i,d,e,g,f,a,e,g,j,h,g,j,d,a,e,f,a,e,g,i,k,m,f,n,g,g,f,e,g,g,j,h,j,a,d,a,e,f,e,g,g,g,o,c,e,z,g,g,f,e,g,m,i,d,d,e,i,a,q,f,e,g,o,d,d,d,h,o,c,h,f,e,m,o,d,d,d,d,d,d,k,i,c,d,d,d,d,d,d,d,d,h);
                case 62:
                     $session['author']= "Lilith";
                     //titolo: ????
                $maze = array(p,b,d,d,d,c,b,b,b,b,k,j,a,d,d,d,d,a,h,m,f,e,f,e,j,d,d,k,i,d,k,f,e,f,h,g,o,h,h,j,k,g,g,m,i,n,g,j,d,d,h,i,h,i,k,l,o,e,g,j,b,b,b,k,j,h,f,k,i,h,f,h,s,h,g,i,k,f,a,d,k,g,l,z,j,h,j,h,g,g,j,h,f,c,h,i,k,i,k,g,m,f,d,c,n,j,d,h,j,h,i,d,h,l,j,k,i,d,k,i,k,j,d,n,g,f,a,d,d,c,n,g,i,d,d,c,c,c,d,d,d,d,h);
                break;
                case 63:
                     $session['author']= "Silver";
                     //titolo: yeayh
                $maze = array(j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,q,s,p,a,a,a,e,f,a,a,a,a,z,q,a,a,a,e,f,a,a,a,a,r,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,h);
                break;
                case 64:
                     $session['author']= "Caronte";
                     //titolo: infernal mad
                $maze = array(l,j,d,b,b,a,b,b,d,b,n,i,h,p,h,p,f,p,i,d,c,n,o,d,b,d,d,a,k,j,b,b,n,j,d,c,k,j,c,h,g,g,i,k,g,j,k,m,g,j,d,h,g,j,h,g,g,f,k,g,f,d,k,g,i,k,f,h,g,m,g,g,l,m,g,j,h,g,o,a,n,g,i,c,k,g,i,k,f,k,i,k,f,n,j,a,p,j,h,g,i,k,g,g,j,a,p,p,c,k,i,k,g,g,g,g,l,p,z,p,e,j,h,f,a,p,g,g,g,g,i,e,m,o,h,i,d,h,i,h,i,d,h,n);
				break;
                case 65:
                     $session['author']= "Caronte";
                     //titolo: Infernal Hole
                     $maze = array(l,j,d,b,b,a,b,b,d,b,n,i,h,p,h,p,a,p,i,d,c,n,o,d,b,d,d,a,k,j,b,b,n,j,d,c,b,b,p,h,g,g,i,k,g,j,k,m,g,j,d,h,g,j,h,g,g,f,k,g,f,d,k,g,i,k,f,h,g,m,g,g,l,m,g,j,h,g,o,a,n,g,i,c,k,g,i,k,f,k,i,k,f,n,j,a,p,j,h,g,i,k,g,g,j,a,p,p,c,k,i,k,g,g,g,g,g,f,z,j,h,j,h,p,p,p,g,g,g,p,i,k,i,d,c,c,c,h,i,h,i,d,h,n);
                break;
                case 66:
                    $session['author']= "de Zent";
                    //title: like merryChrismas
                    $maze = array(j,l,l,l,j,a,k,l,l,l,k,j,c,c,c,c,a,c,c,c,c,k,m,a,l,l,l,g,l,l,l,g,m,p,j,c,c,c,a,c,c,c,k,p,g,m,d,l,l,g,l,l,g,m,k,g,p,j,c,c,a,c,c,k,p,h,f,b,m,g,j,a,k,g,m,i,k,f,e,g,j,c,a,c,k,g,j,h,f,a,h,g,i,g,g,m,g,i,k,f,a,d,g,d,g,h,p,g,j,h,f,a,k,g,j,a,k,g,g,i,k,g,i,e,f,p,p,p,g,m,j,e,m,o,h,i,d,z,h,i,d,h,m,m);
                break;
                case 67:
                    $session['author']= "Aris";
                    //title: ???
                    $maze = array(j,b,b,b,b,a,b,b,b,b,k,f,a,a,a,a,g,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,f,a,a,a,p,a,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,e,p,a,a,a,a,a,a,a,a,a,e,f,a,a,a,c,p,c,a,a,a,e,f,a,a,p,a,a,p,c,a,a,e,f,a,a,a,a,z,a,a,a,a,e,f,a,a,a,a,a,a,a,a,a,p,f,a,a,a,p,a,a,a,a,a,e,i,c,c,c,c,c,c,c,c,c,h,e);
                break;
                case 68:
                    $session['author']= "Hermione";
                    //title: ???
                    $maze = array(o,b,b,b,b,a,b,b,b,b,n,o,a,e,m,m,g,g,m,f,a,n,o,a,e,j,k,g,i,k,f,a,n,o,a,e,g,g,i,k,g,f,a,n,o,a,e,g,i,k,g,g,f,a,n,o,a,e,i,k,g,g,g,f,a,n,o,a,e,l,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,g,g,g,g,g,f,a,n,o,a,e,f,e,g,g,g,f,a,n,o,a,a,a,a,a,a,c,p,a,n,j,a,a,a,a,a,a,a,p,p,k,m,m,m,m,m,m,p,i,d,d,z,n);
                break;
                case 69:
                    $session['author']= "Sook";
                    //title: 4 strade per la morte col trucco
                    $maze = array(o,d,d,d,d,a,d,d,d,d,k,j,d,b,b,k,g,j,d,d,d,h,f,n,m,m,g,g,f,b,d,d,k,i,k,z,b,e,g,g,p,z,l,g,l,g,p,f,e,g,g,g,o,c,h,f,h,i,h,m,g,i,h,o,d,k,i,d,b,n,o,a,d,d,d,d,h,j,d,c,d,k,g,j,d,d,d,k,g,j,d,k,g,g,g,j,d,k,g,g,g,z,g,g,g,g,g,z,g,g,g,g,p,h,g,g,g,i,p,g,g,g,i,d,d,h,g,i,d,d,h,g,i,d,d,d,d,c,d,d,d,d,h,k);
                break;
                case 70:
                    $session['author']= "Thana";
                    //titolo: Ciclope
					$maze = array(j,k,j,d,b,c,b,d,d,b,n,g,g,i,b,p,o,a,n,o,a,n,g,g,j,a,n,o,a,n,o,a,n,g,g,i,a,n,o,a,n,o,a,n,g,g,j,c,n,o,a,n,o,a,n,g,g,f,d,d,q,m,l,o,a,n,g,g,f,b,b,b,b,c,n,m,l,g,g,i,c,c,c,c,d,d,b,h,g,i,d,b,k,j,d,d,k,i,k,g,o,k,m,i,h,l,j,h,l,g,i,d,a,b,s,z,c,h,j,a,h,j,d,h,f,d,d,b,b,h,i,k,i,d,d,c,d,d,h,i,d,d,h);
				break;         
				case 71:
					$session['author']= "Thana";
					//titolo: La Camera delle Torture
					$maze = array(j,d,d,d,d,a,d,d,d,d,k,g,o,d,d,k,g,j,d,d,n,g,i,d,d,d,h,g,i,d,d,d,h,j,d,d,d,d,a,d,d,d,d,k,g,s,d,d,k,g,j,d,d,n,g,i,d,d,d,h,g,i,d,d,d,h,j,d,d,k,q,e,j,d,d,d,k,i,z,j,h,f,a,c,d,d,k,g,l,r,i,k,g,g,j,d,k,g,g,f,a,d,h,i,a,e,l,g,g,g,g,f,n,j,k,g,g,i,h,g,g,g,f,d,e,f,e,i,d,d,h,g,i,c,d,h,i,c,d,d,d,d,h);
				break; 
				case 72:
					$session['author']= "Thana";
					//titolo: Topazio
					$maze = array(j,d,k,l,j,c,d,d,d,k,l,f,k,i,h,i,b,d,k,j,a,e,g,i,d,n,j,a,k,m,f,p,g,f,d,d,b,h,r,g,o,c,k,g,g,j,k,g,l,i,c,d,k,g,g,g,g,f,e,g,j,d,k,g,g,g,g,g,g,g,f,c,k,i,e,g,g,g,g,f,h,g,p,a,k,g,g,g,g,g,s,z,g,j,a,h,m,g,g,g,g,g,g,g,g,g,j,k,g,g,g,i,h,g,i,h,g,g,i,h,m,i,b,p,c,d,k,g,i,d,d,k,o,c,d,d,d,h,i,d,d,d,h);
				break;
				case 73:
					$session['author']= "Thana"; 
					//titolo: Rubino
					$maze = array(j,d,d,d,d,h,o,d,d,b,k,i,b,d,b,d,b,d,b,d,c,e,l,g,l,g,l,g,l,g,j,d,e,g,g,g,g,g,g,i,h,g,j,e,g,g,g,g,g,g,j,k,g,g,g,g,g,i,a,c,a,e,g,g,m,g,f,h,j,c,k,g,m,i,a,d,h,r,d,c,d,h,g,j,b,a,b,k,j,d,d,d,d,a,e,g,g,g,g,g,j,k,j,k,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,g,z,g,g,i,h,g,i,h,i,h,i,d,h,m,o,d,c,d,n);
				break;
				case 74:
					$session['author']= "Thana"; 
					//titolo: Occhio di Tigre 
					$maze = array(j,d,d,d,k,f,d,d,d,d,k,g,j,d,k,i,a,o,d,d,k,g,g,i,k,i,d,a,d,d,k,g,g,f,k,i,d,k,p,j,n,g,g,g,g,i,p,k,g,g,i,d,h,g,g,g,j,d,h,f,a,d,d,k,g,g,i,c,d,d,c,p,d,d,a,e,g,j,d,d,d,d,d,d,d,h,l,g,g,j,d,d,k,j,d,d,k,g,g,g,i,d,k,g,g,j,k,g,g,g,f,p,z,h,g,g,g,i,h,g,g,g,o,b,p,g,g,i,d,d,h,g,i,d,c,d,h,i,d,d,d,d,h);
				break;
				case 75:
					$session['author']= "Thana"; 
					//titolo: Diamante
					$maze = array(j,b,b,b,b,a,b,b,b,b,k,f,h,q,p,q,p,q,p,q,i,e,g,j,d,d,d,d,d,d,d,k,g,g,g,j,d,p,b,d,d,k,g,g,g,g,g,j,q,a,p,b,e,g,g,g,g,g,f,b,q,b,e,g,g,g,g,f,e,g,g,z,q,g,g,g,p,g,g,g,g,i,c,h,g,g,g,g,g,g,g,i,d,d,d,h,g,g,g,g,g,i,d,d,d,d,d,h,g,g,g,i,d,d,d,b,d,d,d,h,g,f,k,p,q,p,g,q,p,q,j,e,i,c,c,c,c,c,c,c,c,c,h);
				break;
				case 76:
					$session['author']= "Thana"; 
					//titolo: Smeraldo
					$maze = array(j,d,d,d,d,a,d,d,d,d,k,g,o,d,d,b,a,b,d,d,n,g,i,d,d,d,h,g,i,b,d,d,p,j,d,d,d,d,a,d,c,d,d,k,g,p,d,d,k,g,j,d,d,n,g,i,d,d,d,h,g,i,b,d,d,p,j,d,d,k,p,e,j,c,d,d,k,i,z,j,h,f,a,c,d,d,k,g,l,p,i,k,g,g,j,d,k,g,g,f,a,d,h,i,a,e,l,g,g,g,g,f,p,j,k,g,g,i,h,g,g,g,f,p,e,f,e,i,d,d,h,g,i,c,d,h,i,c,d,d,d,d,h);
				break; 
            }
            if ($session['switch'] != "") {
                $session['user']['maze']=implode($maze,",");
            }
        }
        if ($session['user']['superuser'] == 0) addnav("Continua","abandoncastle.php?loc=6");
        //Excalibur: Modifica per consentire ai superuser di scegliere il labirinto
        //modifica luke al volo
        if ($session['switch'] == "" AND $session['user']['superuser'] > 0){
            output("`\$`bDEVI`b `&scegliere il labirinto (1-76)`n");
            output("<form action='abandoncastle.php' method='POST'><input name='labi' value='0'><input type='submit' class='button' value='N° Labirinto (1-76)'>`n",true);
            addnav("","abandoncastle.php");
        }else addnav("Continua","abandoncastle.php?loc=6");
        //Excalibur: fine modifica
    }else{
        output("Provi a tirare il portone, ma non riesci in alcun modo ad aprirlo.`n");
        output("Torna quando sarai un guerriero più esperto e potente.`n");
        addnav("Continua","village.php");
    }
}
//now let's navigate the maze
if ($_GET['op'] <> ""){
    $locale=$_GET['loc'];
    if ($_GET['op'] == "n"){
        $locale+=11;
        redirect("abandoncastle.php?loc=$locale");
    }
    if ($_GET['op'] == "s"){
        $locale-=11;
        redirect("abandoncastle.php?loc=$locale");
    }
    if ($_GET['op'] == "w"){
        $locale-=1;
        redirect("abandoncastle.php?loc=$locale");
    }
    if ($_GET['op'] == "e"){
        $locale+=1;
        redirect("abandoncastle.php?loc=$locale");
    }
}else if($_GET['suicide'] == ""){
    if ($_GET['loc'] <> ""){
        //now deal with random events good stuff first
        //good stuff diminshes the longer player is in the maze
        //this is big... with lots of cases to help keep options open for future events
        //the lower cases should be good things the best at the lowest number
        //and the opposite for bad things
        $maze=explode(",",$session['user']['maze']);
        $locale=$_GET['loc'];
        if ($locale=="") $locale=$session['user']['pqtemp'];
        $session['user']['pqtemp']=$locale;
        for ($i=0;$i<$locale-1;$i++){
        }
        $navigate=ltrim($maze[$i]);
        output("`4");
        if ($navigate <> "z"){
            if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){
                $_GET['skill']="";
                if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
                $session['bufflist']=array();
            }
            switch(e_rand($session['user']['mazeturn'],2500)){
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                case 6:
                case 7:
                case 8:
                case 9:
                case 10:
                    output("<big><big>`bGiorno Fortunato!  Hai trovato una Gemma!`b</big></big>",true);
                    $session['user']['gems']+=1;
                    break;
                case 11:
                case 12:
                case 13:
                case 14:
                case 15:
                case 16:
                case 17:
                case 18:
                case 19:
                case 20:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 1000 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=1000;
                    break;
                case 21:
                case 22:
                case 23:
                case 24:
                case 25:
                case 26:
                case 27:
                case 28:
                case 29:
                case 30:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 900 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=900;
                    break;
                case 31:
                case 32:
                case 33:
                case 34:
                case 35:
                case 36:
                case 37:
                case 38:
                case 39:
                case 40:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 800 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=800;
                    break;
                case 41:
                case 42:
                case 43:
                case 44:
                case 45:
                case 46:
                case 47:
                case 48:
                case 49:
                case 50:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 700 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=700;
                    break;
                case 51:
                case 52:
                case 53:
                case 54:
                case 55:
                case 56:
                case 57:
                case 58:
                case 59:
                case 60:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 600 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=600;
                    break;
                case 61:
                case 62:
                case 63:
                case 64:
                case 65:
                case 66:
                case 67:
                case 68:
                case 69:
                case 70:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 500 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=500;
                    break;
                case 71:
                case 72:
                case 73:
                case 74:
                case 75:
                case 76:
                case 77:
                case 78:
                case 79:
                case 80:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 400 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=400;
                    break;
                case 81:
                case 82:
                case 83:
                case 84:
                case 85:
                case 86:
                case 87:
                case 88:
                case 89:
                case 90:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 300 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=300;
                    break;
                case 91:
                case 92:
                case 93:
                case 94:
                case 95:
                case 96:
                case 97:
                case 98:
                case 99:
                case 100:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 200 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=200;
                    break;
                case 101:
                case 102:
                case 103:
                case 104:
                case 105:
                case 106:
                case 107:
                case 108:
                case 109:
                case 110:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 100 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=100;
                    break;
                case 111:
                case 112:
                case 113:
                case 114:
                case 115:
                case 116:
                case 117:
                case 118:
                case 119:
                case 120:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 100 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=100;
                    break;
                case 121:
                case 122:
                    //comment out potions for if you are not using potion mod!
                    if ($session['user']['potion']<5){
                        output("<big><big>`bGiorno Fortunato! Hai trovato una Pozione Guaritrice!`b</big></big>",true);
                        $session['user']['potion']+=1;
                    }
                    break;
                case 123:
                case 124:
                    //comment out chow if you are not using chow mod!
                    for ($i=0;$i<6;$i+=1){
                        $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                        if ($chow[$i] > 0) $userchow++;
                    }
                    if ($userchow<5){
                        switch(e_rand(1,7)){
                            case 1:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi una Fetta di Pane!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="1";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                            case 2:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi una Braciola di Maiale!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="2";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                            case 3:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi una Bistecca di Cavallo!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="3";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                            case 4:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi un Filetto!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="4";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                            case 5:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi un Pollo Intero!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="5";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                            case 6:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi una Bottiglia di Latte!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="6";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                            case 7:
                                output("`^<big><big>`bLa Fortuna ti sorride e trovi una Bottiglia d'Acqua!`b</big></big>`0",true);
                                for ($i=0;$i<6;$i+=1){
                                    $chow[$i]=substr(strval($session['user']['chow']),$i,1);
                                    if ($chow[$i]=="0" and $done < 1){
                                        $chow[$i]="7";
                                        $done = 1;
                                    }
                                    $newchow.=$chow[$i];
                                }
                                break;
                        }
                        $session['user']['chow']=$newchow;
                    }
                    break;
                case 125:
                case 126:
                case 127:
                case 128:
                case 129:
                case 130:
                    output("<big><big>`bGiorno Fortunato! Hai trovato 10 Pezzi d'Oro!`b</big></big>",true);
                    $session['user']['gold']+=10;
                    break;
                case 131:
                case 132:
                case 133:
                case 134:
                case 135:
                case 136:
                case 137:
                case 138:
                case 139:
                case 140:
                    output("<big><big>`b`&Hai trovato una pergamena. Leggendola senti che la tua cattiveria è diminuita!!`b`0</big></big>",true);
                    //comment out if you are not using the trading mod and lonny's castle!
                    $cattiveria=e_rand(1,10);
                    $session['user']['evil']-=$cattiveria;
                    if ($session['user']['evil']<0) $session['user']['evil']=0;
                    //find();
                    break;
				case 141:
				case 142:
				case 143:
				case 144:
				case 145:
				case 146:
				case 147:
				case 148:
				case 149:
				case 150:
                    output("<big><big>`b`&Una mano scheletrica indica il Nord, ma non sei così sicur".($session[user][sex]?"a":"o")." che quella sia la direzione giusta.`b</big></big>`0",true);
                    break;
                case 151:
				case 152:
				case 153:
				case 154:
				case 155:
				case 156:
				case 157:
				case 158:
				case 159:
				case 160:
                    output("<big><big>`b`&Una mano scheletrica indica il Sud, ma non sei così sicur".($session[user][sex]?"a":"o")." che quella sia la direzione giusta.`b</big></big>`0",true);
                    break;
                case 141:
				case 142:
				case 143:
				case 144:
				case 145:
				case 146:
				case 147:
				case 148:
				case 149:
				case 150:
                    output("<big><big>`b`&Una mano scheletrica indica l'Ovest, ma non sei così sicur".($session[user][sex]?"a":"o")." che quella sia la direzione giusta.`b</big></big>`0",true);
                    break;
                case 151:
				case 152:
				case 153:
				case 154:
				case 155:
				case 156:
				case 157:
				case 158:
				case 159:
				case 160:
                    output("<big><big>`b`&Una mano scheletrica indica l'Est, ma non sei così sicur".($session[user][sex]?"a":"o")." che quella sia la direzione giusta.`b</big></big>`0",true);
                    break;
                case 2321:
                case 2322:
                case 2323:
                case 2324:
                case 2325:
                case 2326:
                case 2327:
                case 2328:
                case 2329:
                case 2330:
                    output("<big><big>`b`&Senti un suono sconosciuto ed agghiacciante,come un ringhio sordo provenire da un luogo non definito non lontano da te.`b</big></big>`0",true);
                    break;
                case 2331:
                case 2332:
                case 2333:
                case 2334:
                case 2335:
                case 2336:
                case 2337:
                case 2338:
                case 2339:
                case 2340:
                    output("<big><big>`%`bSenti un urlo che ti gela il sangue nelle vene provenire da qualche parte.`b`0</big></big>",true);
                    break;
                case 2341:
                case 2342:
                case 2343:
                case 2344:
                case 2345:
                case 2346:
                case 2347:
                case 2348:
                case 2349:
                case 2350:
                    output("<big><big>`b`2Trovi una pozza maleodorante, e sfortunatamente ci cadi dentro..`b</big></big>`0",true);
                    $session['user']['clean']+=2;
                    break;
                case 2351:
                case 2352:
                case 2353:
                case 2354:
                case 2355:
                case 2356:
                case 2357:
                case 2358:
                case 2359:
                case 2360:
                    output("<big><big>`b`6C'è uno scheletro che giace nell'angolo. Povero diavolo, non ha trovato l'uscita.`b`0</big></big>",true);
                    break;
                case 2361:
                case 2362:
                case 2363:
                case 2364:
                case 2365:
                case 2366:
                case 2367:
                case 2368:
                case 2369:
                case 2370:
                    output("<big><big>`b`3Vedi un ratto masticare quella che sembra una mano`b.</big></big>`0",true);
                    break;
                case 2371:
                case 2372:
                case 2373:
                case 2374:
                case 2375:
                case 2376:
                case 2377:
                case 2378:
                case 2379:
                case 2380:
                    output("<big><big>`b`4Senti un ringhio provenire da molto vicino.`b`0</big></big>",true);
                    break;
                case 2381:
                case 2382:
                case 2383:
                case 2384:
                case 2385:
                case 2386:
                case 2387:
                case 2388:
                case 2389:
                case 2390:
                    output("<big><big>`b`#Un freddo intenso ti pervade, penetrando nel tuo corpo fino alle ossa.`b`0</big></big>",true);
                    break;
                case 2391:
                case 2392:
                case 2393:
                case 2394:
                case 2395:
                case 2396:
                case 2397:
                case 2398:
                case 2399:
                case 2400:
                    output("<big><big>`b`7Qualcuno in lontananza urla alla disperata ricerca d'aiuto.`b`0</big></big>",true);
                    break;
                case 2401:
                case 2402:
                case 2403:
                case 2404:
                case 2405:
                case 2406:
                case 2407:
                case 2408:
                case 2409:
                case 2410:
                    output("<big><big>`bSenti delle urla d'aiuto provenire da molto vicino.`b</big></big>",true);
                    break;
                case 2411:
                case 2412:
                case 2413:
                case 2414:
                case 2415:
                case 2416:
                case 2417:
                case 2418:
                case 2419:
                case 2420:
                    output("<big><big>`bSenti delle urla d'aiuto in lontananza. Improvvisamente le urla si interrompono.`b</big></big>",true);
                    break;
                case 2421:
                case 2422:
                case 2423:
                case 2424:
                case 2425:
                case 2426:
                case 2427:
                case 2428:
                case 2429:
                case 2430:
                    output("<big><big>`bOuch! Inciampi su qualcosa di appuntito e ti ferisci !!`b</big></big>",true);
                    $session['user']['hitpoints']-=5;
                    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
                    break;
                case 2431:
                case 2432:
                case 2433:
                case 2434:
                case 2435:
                case 2436:
                case 2437:
                case 2438:
                case 2439:
                case 2440:
                    output("<big><big>`bOuch! Sei stat".($session[user][sex]?"a":"o")." mors".($session[user][sex]?"a":"o")." da un ragno !!`b</big></big>",true);
                    $session['user']['hitpoints']-=6;
                    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
                    break;
                case 2441:
                case 2442:
                case 2443:
                case 2444:
                case 2445:
                case 2446:
                case 2447:
                case 2448:
                case 2449:
                case 2450:
                    output("<big><big>`bOuch! Sei stat".($session[user][sex]?"a":"o")." mors".($session[user][sex]?"a":"o")." da un ratto, che scappa velocemente lontano.`b</big></big>",true);
                    $session['user']['hitpoints']-=7;
                    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
                    break;
                case 2451:
                case 2452:
                case 2453:
                case 2454:
                case 2455:
                case 2456:
                case 2457:
                case 2458:
                case 2459:
                case 2460:
                    output("<big><big>`bOuch! Sei stat".($session[user][sex]?"a":"o")." mors".($session[user][sex]?"a":"o")." da una pantegana, che scappa velocemente lontano.`b</big></big>",true);
                    $session['user']['hitpoints']-=10;
                    if ($session['user']['hitpoints']<1) $session['user']['hitpoints']=1;
                    break;
                case 2461:
                case 2462:
                case 2463:
                    output("<big><big><big>`b`c`4Wham!`c<small>`n",true);
                    output("`3Mentre la vista ti si annebbia, vedi che delle grandi punte affilate sono spuntate dal terreno impalandoti.`b</big></big>`n",true);
                    $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                    $session['user']['gold']=0;
                    $session['user']['maze']="";
                    $session['user']['mazeturn']=0;
                    $session['user']['pqtemp']="";
                    $session['user']['hitpoints']=0;
                    $session['user']['alive']=false;
                    debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                    addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                    break;
                case 2464:
                case 2465:
                case 2466:
                case 2467:
                case 2468:
                case 2469:
                case 2470:
                case 2471:
                    redirect("mazemonster.php?op=ghost1");
                    break;
                case 2472:
                case 2473:
                case 2474:
                case 2475:
                case 2476:
                case 2477:
                case 2478:
                case 2479:
                    redirect("mazemonster.php?op=ghost2");
                    break;
                case 2480:
                case 2481:
                case 2482:
                case 2483:
                case 2484:
                case 2485:
                case 2486:
                    redirect("mazemonster.php?op=bat");
                    break;
                case 2487:
                case 2488:
                case 2489:
                case 2490:
                case 2491:
                case 2493:
                case 2494:
                    redirect("mazemonster.php?op=rat");
                    break;
                case 2495:
                case 2496:
                    redirect("mazemonster.php?op=minotaur");
                    break;
                case 2497:
                case 2498:
                    output("<big><big><big>`b`c`4Wham!`c<small>`n",true);
                    output("`3Mentre la vista ti si annebbia, vedi che delle grandi punte affilate sono spuntate dal terreno impalandoti..`b</big></big>`n",true);
                    debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                    $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                    $session['user']['gold']=0;
                    $session['user']['maze']="";
                    $session['user']['mazeturn']=0;
                    $session['user']['pqtemp']="";
                    $session['user']['hitpoints']=0;
                    $session['user']['alive']=false;
                    addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                    break;
                case 2499:
                case 2500:
                    output("<big><big><big>`b`c`4Shoop!`c<small>`n",true);
                    output("`3Mentre la vista ti si annebbia, vedi il tuo corpo cadere al suolo dove giace la tua testa.`b</big></big>`n",true);
                    debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                    $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                    $session['user']['gold']=0;
                    $session['user']['maze']="";
                    $session['user']['mazeturn']=0;
                    $session['user']['pqtemp']="";
                    $session['user']['hitpoints']=0;
                    $session['user']['alive']=false;
                    addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                    break;
            }
        }
        output("`7");
        if ($navigate<>"z"){
            if ($navigate=="x"){
                output("<big><big>`b`#Sei giunto al confine estremo del mondo, e non riuscendo a frenare il tuo slancio cadi dal bordo !!!`b</big></big>`0",true);
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                $session['user']['gold']=0;
                $session['user']['maze']="";
                $session['user']['mazeturn']=0;
                $session['user']['pqtemp']="";
            }
            if ($navigate=="p"){
                output("<big><big>`b`@Cadi in un crepaccio irto di spuntoni taglienti, la vista ti si annebbia mentre la vita ti abbandona lentamente.`b<big><big>`0`n",true);
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                $session['user']['gold']=0;
                $session['user']['maze']="";
                $session['user']['mazeturn']=0;
                $session['user']['pqtemp']="";
            }
            if ($navigate=="q"){
                output("<big><big>`b`&Inciampi su qualcosa sul pavimento. Senti un click e un gorgoglio d'acqua.`b</big></big>`n",true);
                output("Il passaggio si riempie velocemente d'acqua, e tu vedi il mondo annebbiarsi mentre i tuoi polmoni bruciano
                alla disperata ricerca d'aria.`0`n");
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                $session['user']['gold']=0;
                $session['user']['maze']="";
                $session['user']['mazeturn']=0;
                $session['user']['pqtemp']="";
            }
            if ($navigate=="r"){
                output("<big><big>`b`^Senti un rumore provenire da dietro di te.`b</big></big>`n Quando ti giri vedi che una porta ti ha bloccat".($session[user][sex]?"a":"o")." ",true);
                output("in una sezione del corridoio. `6Le mura iniziano a tremare e si chiudono su di te. Presto ti rendi conto ");
                output("che è come essere un insetto sotto il tuo piede.`0");
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                $session['user']['gold']=0;
                $session['user']['maze']="";
                $session['user']['mazeturn']=0;
                $session['user']['pqtemp']="";
            }
            if ($navigate=="s"){
                output("<big><big>`b`&Uscendo dal nulla una lama attraversa orizzontalmente il tuo cammino.`b</big></big>",true);
                output("`7Il mondo scompare alla tua vista mentra la metà superiore del tuo corpo scivola su quella inferiore.`0`n");
                $session['user']['hitpoints']=0;
                $session['user']['alive']=false;
                addnews("`%".$session['user']['name']."`5 è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non ne è più uscit".($session[user][sex]?"a":"o").".");
                debuglog("Muore nel labirinto ".$session['switch']." del castello e perde ".$session['user']['gold']." oro e 10% exp");
                $session['user']['experience'] = intval ($session['user']['experience'] * 0.90);
                $session['user']['gold']=0;
                $session['user']['maze']="";
                $session['user']['mazeturn']=0;
                $session['user']['pqtemp']="";
            }
            if ($session['user']['hitpoints'] > 0){
                if ($locale=="6"){
                    output("`nTi trovi all'ingresso con passaggi verso");
                }else{
                    output("`nSei in un buio corridoio con passaggi verso");
                }
                $session['user']['mazeturn']++;
                if ($navigate=="a" or $navigate=="b" or $navigate=="e" or $navigate=="f" or $navigate=="g" or $navigate=="j" or $navigate=="k" or $navigate=="l"){
                    addnav("Nord","abandoncastle.php?op=n&loc=$locale");
                    $directions.=" Nord";
                    $navcount++;
                }
                if ($navigate=="a" or $navigate=="c" or $navigate=="e" or $navigate=="f" or $navigate=="g" or $navigate=="h" or $navigate=="i" or $navigate=="m"){
                    if ($locale <> 6){
                        addnav("Sud","abandoncastle.php?op=s&loc=$locale");
                        $navcount++;
                        if ($navcount > 1) $directions.=",";
                        $directions.=" Sud";
                    }
                }
                if ($navigate=="a" or $navigate=="b" or $navigate=="c" or $navigate=="d" or $navigate=="e" or $navigate=="h" or $navigate=="k" or $navigate=="n"){
                    addnav("Ovest","abandoncastle.php?op=w&loc=$locale");
                    $navcount++;
                    if ($navcount > 1) $directions.=",";
                    $directions.=" Ovest";
                }
                if ($navigate=="a" or $navigate=="b" or $navigate=="c" or $navigate=="d" or $navigate=="f" or $navigate=="i" or $navigate=="j" or $navigate=="o"){
                    addnav("Est","abandoncastle.php?op=e&loc=$locale");
                    $navcount++;
                    if ($navcount > 1) $directions.=",";
                    $directions.=" Est";
                }
                if ($session['user']['mazeturn'] > 99){
                    addnav("X?Suicidati per la disperazione","abandoncastle.php?suicide=xxx");
                }
                output($directions.".`n");
            }else{
                addnav("`@Continua","shades.php");
            }
            //user map generation.... may make code to grey spots that a player has been
            $mazemap=$navigate;
            $mazemap.="maze.gif";
            output("<IMG SRC=\"images/$mazemap\">\n",true);
            output("`n");
            output("`n<small>`7Tu = <img src=\"./images/mcyan.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\">`7, Ingresso = <img src=\"./images/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\">`7, Uscita = <img src=\"./images/mred.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"><big>",true);
            $mapkey2="<table style=\"height: 130px; width: 110px; text-align: left;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td style=\"vertical-align: top;\">";
            for ($i=0;$i<143;$i++){
                if ($i==$locale-1){
                    $mapkey.="<img src=\"./images/mcyan.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
                }else{
                    if ($i==5){
                        $mapkey.="<img src=\"./images/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
                    }else{
                        if (ltrim($maze[$i])=="z"){
                            $exit=$i+1;
                            $mapkey.="<img src=\"./images/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
                        }else{
                            $mapkey.="<img src=\"./images/mblack.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
                        }
                    }
                }
                if ($i==10 or $i==21 or $i==32 or $i==43 or $i==54 or $i==65 or $i==76 or $i==87 or $i==98 or $i==109 or $i==120 or $i==131 or $i==142){
                    $mapkey="`n".$mapkey;
                    $mapkey2=$mapkey.$mapkey2;
                    $mapkey="";
                }
            }
            $mapkey2.="</td></tr></tbody></table>";
            output($mapkey2,true);
            if ($session['user']['superuser']>1){
                output("Mappa Superuser`n");
                $mapkey2="";
                $mapkey="";
                for ($i=0;$i<143;$i++){
                    $keymap=ltrim($maze[$i]);
                    $mazemap=$keymap;
                    $mazemap.="maze.gif";
                    $mapkey.="<img src=\"./images/$mazemap\" title=\"\" alt=\"\" style=\"width: 20px; height: 20px;\">";
                    if ($i==10 or $i==21 or $i==32 or $i==43 or $i==54 or $i==65 or $i==76 or $i==87 or $i==98 or $i==109 or $i==120 or $i==131 or $i==142){
                        $mapkey="`n".$mapkey;
                        $mapkey2=$mapkey.$mapkey2;
                        $mapkey="";
                    }
                }
                output($mapkey2,true);
            }
            if ($session['user']['superuser']>1) addnav("Uscita Superuser","abandoncastle.php?loc=$exit");
        }else{
            //found your way out!
            if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0) {
                $session['bufflist'] = unserialize($session['user']['buffbackup']);
                if (!is_array($session['bufflist'])) $session['bufflist'] = array();
            }
            if ($session['user']['hashorse']>0){
                output("`^<big><big>Il tuo {$playermount['mountname']} ti saluta allegramente all'uscita.</big></big>`n",true);
            }
            output("Sei riuscit".($session[user][sex]?"a":"o")." a trovare l'uscita!`n");
            addnews("`%".$session['user']['name']."`5 è riuscit".($session[user][sex]?"a":"o")." a sopravvivere al Castello Abbandonato!  In ".$session['user']['mazeturn']." mosse!");
            $session['user']['labsolved'] += 1;
            $session['user']['questcastle'] += 1;
            $reward = 5000 - ($session['user']['mazeturn']*10);
            if ($reward < 0) $reward = 0;
            $gemreward = 0;
            if ($session['user']['mazeturn'] < 101) $gemreward = 1;
            if ($session['user']['mazeturn'] < 76) $gemreward = 2;
            if ($session['user']['mazeturn'] < 51) $gemreward = 3;
            if ($session['user']['mazeturn'] < 26) $gemreward = 4;
            output("`2Hai completato il labirinto in ".$session['user']['mazeturn']." turni.`n");
            output("`2Hai trovato un tesoro di ".$reward." pezzi d'oro e ".$gemreward." gemme.`n`n");
            debuglog("è riuscito a trovare l'uscita del labirinto  ".$session['switch']." e guadagna $gemreward gemme e $reward oro");
            addnav("Continua","village.php");
            $session['user']['gold']+=$reward;
            $session['user']['gems']+=$gemreward;
            $session['user']['maze']="";
            $session['user']['mazeturn']=0;
            $session['user']['pqtemp']="";
        }
    }
}else if ($_GET['suicide'] == "xxx"){
    output("`4Ammettilo, hai completamente perso l'orientamento. Non sai dove ti trovi e continui a girare in tondo.
      Non ce la fai più, sei stremat".($session[user][sex]?"a":"o").", e questo dedalo di corridoi ti sta conducendo alla pazzia. Meglio farla finita
      prima di perdere completamente il lume della ragione. Estrai una fiala che avevi conservato per evenienze come
      questa e la ingurgiti tutto d'un fiato. Dopo qualche istante le gambe ti cedono, cadi rivers".($session[user][sex]?"a":"o")." a terra e le
      ultime immagini che si stampano sulle tue retine sono quelle dei topi che si avvicinano famelici al tuo corpo`n");
    output("`n`\$Per tua fortuna i topi non sono interessati al tuo oro !!`n");
    output("Perdi il 15% di esperienza !!`n");
    $exppersa = intval($session['user']['experience']*0.15);
    $session['user']['experience'] -= $exppersa;
    $session['user']['hitpoints']=0;
    $session['user']['maze']="";
    $session['user']['mazeturn']=0;
    $session['user']['pqtemp']="";
    $session['user']['alive'] = false;
    debuglog("si è suicidato nel labirinto ".$session['switch']." e perde 15% exp");
    addnews("`%".$session['user']['name']."`\$ è entrat".($session[user][sex]?"a":"o")." nel Castello Abbandonato e non trovando l'uscita si è suicidat".($session[user][sex]?"a":"o").".");
    addnav("Continua","shades.php");
}
//I cannot make you keep this line here but would appreciate it left in.
if ($session['user']['superuser'] > 0) rawoutput("<div style=\"text-align: left;\">Labirinto N° {$session['switch']}");
output("<div style=\"text-align: left;\">`6Autore Labirinto: `#".$session['author']."`n",true);
output("<div style=\"text-align: right;\">`!Abandonded Castle by Lonny`n",true);
page_footer();
?>