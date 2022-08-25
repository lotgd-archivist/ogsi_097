# phpMyAdmin MySQL-Dump
# version 2.4.0
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generato il: 14 Feb, 2004 at 04:18 PM
# Versione MySQL: 3.23.58
# Versione PHP: 4.3.1
# Database : `logd`
# --------------------------------------------------------

#
# Struttura della tabella `armor`
#

CREATE TABLE armor (
  armorid int(11) unsigned NOT NULL auto_increment,
  armorname varchar(128) default NULL,
  value int(11) NOT NULL default '0',
  defense int(11) NOT NULL default '1',
  level int(11) NOT NULL default '0',
  PRIMARY KEY  (armorid)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `armor`
#

INSERT INTO armor VALUES (1, 'Pantofole Pelose', 48, 1, 0);
INSERT INTO armor VALUES (2, 'Pigiama di Flanella', 225, 2, 0);
INSERT INTO armor VALUES (3, 'Mutandoni fatti in casa', 585, 3, 0);
INSERT INTO armor VALUES (4, 'Maglietta della Salute', 990, 4, 0);
INSERT INTO armor VALUES (5, 'Calze di Lana', 1575, 5, 0);
INSERT INTO armor VALUES (6, 'Guanti di Lana', 2250, 6, 0);
INSERT INTO armor VALUES (7, 'Vecchi stivali di Cuoio', 2790, 7, 0);
INSERT INTO armor VALUES (8, 'Pantaloni fatti in casa', 3420, 8, 0);
INSERT INTO armor VALUES (9, 'Tunica fatta in casa', 4230, 9, 0);
INSERT INTO armor VALUES (10, 'Cappa degli Zingari', 5040, 10, 0);
INSERT INTO armor VALUES (11, 'Vecchio Cappello di Cuoio', 5850, 11, 0);
INSERT INTO armor VALUES (12, 'Vecchi Bracciali di Cuoio', 6840, 12, 0);
INSERT INTO armor VALUES (13, 'Scudo da Viaggiatore', 8010, 13, 0);
INSERT INTO armor VALUES (14, 'Vecchi Pantaloni di Cuoio', 9000, 14, 0);
INSERT INTO armor VALUES (15, 'Vecchia Tunica di Cuoio', 10350, 15, 0);
INSERT INTO armor VALUES (16, 'Ciabatte', 48, 1, 1);
INSERT INTO armor VALUES (17, 'Costume a Bagno e Asciugamani', 225, 2, 1);
INSERT INTO armor VALUES (18, 'Maglia Intima di Cotone', 585, 3, 1);
INSERT INTO armor VALUES (19, 'Calze di Lana', 990, 4, 1);
INSERT INTO armor VALUES (20, 'Guanti di Lana', 1575, 5, 1);
INSERT INTO armor VALUES (21, 'Stivali di Cuoio', 2250, 6, 1);
INSERT INTO armor VALUES (22, 'Cappello di Cuoio', 2790, 7, 1);
INSERT INTO armor VALUES (23, 'Bracciali di Cuoio', 3420, 8, 1);
INSERT INTO armor VALUES (24, 'Gambali di Cuoio', 4230, 9, 1);
INSERT INTO armor VALUES (25, 'Tunica di Cuoio', 5040, 10, 1);
INSERT INTO armor VALUES (26, 'Cappa di Cuoio', 5850, 11, 1);
INSERT INTO armor VALUES (27, 'Gambali di Pelle di Daino', 6840, 12, 1);
INSERT INTO armor VALUES (28, 'Cintura di Pelle di Daino', 8010, 13, 1);
INSERT INTO armor VALUES (29, 'Tunica di Pelle di Daino', 9000, 14, 1);
INSERT INTO armor VALUES (30, 'Piccolo Scudo di Pelle', 10350, 15, 1);
INSERT INTO armor VALUES (31, 'Stivali da Lavoro', 48, 1, 2);
INSERT INTO armor VALUES (32, 'Camice da Lavoro', 225, 2, 2);
INSERT INTO armor VALUES (33, 'Guanti di Cuoio Rinforzato', 585, 3, 2);
INSERT INTO armor VALUES (34, 'Bracciali di Cuoio Rinforzato', 990, 4, 2);
INSERT INTO armor VALUES (35, 'Stivali di Cuoio Rinforzato', 1575, 5, 2);
INSERT INTO armor VALUES (36, 'Elmo di Cuoio Rinforzato', 2250, 6, 2);
INSERT INTO armor VALUES (37, 'Pantaloni di Cuoio Rinforzato', 2790, 7, 2);
INSERT INTO armor VALUES (38, 'Tunica di Cuoio Rinforzato', 3420, 8, 2);
INSERT INTO armor VALUES (39, 'Cappa di Cuoio Rinforzato', 4230, 9, 2);
INSERT INTO armor VALUES (40, 'Elmo da Boscaiolo', 5040, 10, 2);
INSERT INTO armor VALUES (41, 'Guanti da Boscaiolo', 5850, 11, 2);
INSERT INTO armor VALUES (42, 'Bracciali da Boscaiolo', 6840, 12, 2);
INSERT INTO armor VALUES (43, 'Gambali da Boscaiolo', 8010, 13, 2);
INSERT INTO armor VALUES (44, 'Tunica da Boscaiolo', 9000, 14, 2);
INSERT INTO armor VALUES (45, 'Scudo da Boscaiolo', 10350, 15, 2);
INSERT INTO armor VALUES (46, 'Cuffia da Bagno e Asciugamani', 48, 1, 3);
INSERT INTO armor VALUES (47, 'Accappatoio', 225, 2, 3);
INSERT INTO armor VALUES (48, 'Guanti di Pelle di Lupo', 585, 3, 3);
INSERT INTO armor VALUES (49, 'Stivali di Pelle di Lupo', 990, 4, 3);
INSERT INTO armor VALUES (50, 'Bracciali di Pelle di Lupo', 1575, 5, 3);
INSERT INTO armor VALUES (51, 'Pantaloni di Pelle di Lupo', 2250, 6, 3);
INSERT INTO armor VALUES (52, 'Tunica di Pelle di Lupo', 2790, 7, 3);
INSERT INTO armor VALUES (53, 'Cappa di Pelle di Lupo', 3420, 8, 3);
INSERT INTO armor VALUES (54, 'Bracciali del Signore dei Lupi', 4230, 9, 3);
INSERT INTO armor VALUES (55, 'Guanti del Signore dei Lupi', 5040, 10, 3);
INSERT INTO armor VALUES (56, 'Elmo del Signore dei Lupi', 5850, 11, 3);
INSERT INTO armor VALUES (57, 'Gambali del Signore dei Lupi', 6840, 12, 3);
INSERT INTO armor VALUES (58, 'Giustacuore del Signore dei Lupi', 8010, 13, 3);
INSERT INTO armor VALUES (59, 'Cappa del Signore dei Lupi', 9000, 14, 3);
INSERT INTO armor VALUES (60, 'Scudo del Signore dei Lupi', 10350, 15, 3);
INSERT INTO armor VALUES (61, 'Pantaloncini', 48, 1, 4);
INSERT INTO armor VALUES (62, 'Maglietta', 225, 2, 4);
INSERT INTO armor VALUES (63, 'Elmo di Cuoio Rinforzato', 585, 3, 4);
INSERT INTO armor VALUES (64, 'Guanti di Cuoio Rinforzato', 990, 4, 4);
INSERT INTO armor VALUES (65, 'Stivali di Cuoio Rinforzato', 1575, 5, 4);
INSERT INTO armor VALUES (66, 'Gambali di Cuoio Rinforzato', 2250, 6, 4);
INSERT INTO armor VALUES (67, 'Tunica di Cuoio Rinforzato', 2790, 7, 4);
INSERT INTO armor VALUES (68, 'Cappa di Cuoio Rinforzato', 3420, 8, 4);
INSERT INTO armor VALUES (69, 'Elmo di Maglia di Ferro Arrugginita', 4230, 9, 4);
INSERT INTO armor VALUES (70, 'Guanti di Maglia di Ferro Arrugginita', 5040, 10, 4);
INSERT INTO armor VALUES (71, 'Bracciali di Maglia di Ferro Arrugginita', 5850, 11, 4);
INSERT INTO armor VALUES (72, 'Stivali di Maglia di Ferro Arrugginita', 6840, 12, 4);
INSERT INTO armor VALUES (73, 'Gambali di Maglia di Ferro Arrugginita', 8010, 13, 4);
INSERT INTO armor VALUES (74, 'Tunica di Maglia di Ferro Arrugginita', 9000, 14, 4);
INSERT INTO armor VALUES (75, 'Grosso Scudo d´Acciaio', 10350, 15, 4);
INSERT INTO armor VALUES (76, 'Pantofole a Coniglietto', 48, 1, 5);
INSERT INTO armor VALUES (77, 'Pigiama Pesante', 225, 2, 5);
INSERT INTO armor VALUES (78, 'Comoda Sottoveste di Cuoio', 585, 3, 5);
INSERT INTO armor VALUES (79, 'Elmo di Maglia d´Acciaio', 990, 4, 5);
INSERT INTO armor VALUES (80, 'Guanti di Maglia d´Acciaio', 1575, 5, 5);
INSERT INTO armor VALUES (81, 'Bracciali di Maglia d´Acciaio', 2250, 6, 5);
INSERT INTO armor VALUES (82, 'Stivali di Maglia d´Acciaio', 2790, 7, 5);
INSERT INTO armor VALUES (83, 'Gambali di Maglia d´Acciaio', 3420, 8, 5);
INSERT INTO armor VALUES (84, 'Tunica di Maglia d´Acciaio', 4230, 9, 5);
INSERT INTO armor VALUES (85, 'Bracciali dei Soldati del Drago', 5040, 10, 5);
INSERT INTO armor VALUES (86, 'Guanti dei Soldati del Drago', 5850, 11, 5);
INSERT INTO armor VALUES (87, 'Stivali dei Soldati del Drago', 6840, 12, 5);
INSERT INTO armor VALUES (88, 'Gambali dei Soldati del Drago', 8010, 13, 5);
INSERT INTO armor VALUES (89, 'Armatura dei Soldati del Drago', 9000, 14, 5);
INSERT INTO armor VALUES (90, 'Scudo dei Soldati del Drago', 10350, 15, 5);
INSERT INTO armor VALUES (91, 'Blue Jeans', 48, 1, 6);
INSERT INTO armor VALUES (92, 'Maglia di Flanella', 225, 2, 6);
INSERT INTO armor VALUES (93, 'Elmo di Bronzo', 585, 3, 6);
INSERT INTO armor VALUES (94, 'Guanti di Bronzo', 990, 4, 6);
INSERT INTO armor VALUES (95, 'Bracciali di Bronzo', 1575, 5, 6);
INSERT INTO armor VALUES (96, 'Stivali di Bronzo', 2250, 6, 6);
INSERT INTO armor VALUES (97, 'Gambali di Bronzo', 2790, 7, 6);
INSERT INTO armor VALUES (98, 'Armatura di Bronzo', 3420, 8, 6);
INSERT INTO armor VALUES (99, 'Elmo di Bronzo Incantato', 4230, 9, 6);
INSERT INTO armor VALUES (100, 'Guanti di Bronzo Incantato', 5040, 10, 6);
INSERT INTO armor VALUES (101, 'Bracciali di Bronzo Incantato', 5850, 11, 6);
INSERT INTO armor VALUES (102, 'Stivali di Bronzo Incantato', 6840, 12, 6);
INSERT INTO armor VALUES (103, 'Gambali di Bronzo Incantato', 8010, 13, 6);
INSERT INTO armor VALUES (104, 'Corazza di Bronzo Incantato', 9000, 14, 6);
INSERT INTO armor VALUES (105, 'Cappa di Pelle di Unicorno', 10350, 15, 6);
INSERT INTO armor VALUES (106, 'Barile', 48, 1, 7);
INSERT INTO armor VALUES (107, 'Paralume', 225, 2, 7);
INSERT INTO armor VALUES (108, 'Elmo d´Acciaio', 585, 3, 7);
INSERT INTO armor VALUES (109, 'Guanti d´Acciaio', 990, 4, 7);
INSERT INTO armor VALUES (110, 'Stivali d´Acciaio', 1575, 5, 7);
INSERT INTO armor VALUES (111, 'Bracciali d´Acciaio', 2250, 6, 7);
INSERT INTO armor VALUES (112, 'Gambali d´Acciaio', 2790, 7, 7);
INSERT INTO armor VALUES (113, 'Corazza d´Acciaio', 3420, 8, 7);
INSERT INTO armor VALUES (114, 'Cappa di Penne di Grifone', 4230, 9, 7);
INSERT INTO armor VALUES (115, 'Elmo Nanico', 5040, 10, 7);
INSERT INTO armor VALUES (116, 'Guanti Nanici', 5850, 11, 7);
INSERT INTO armor VALUES (117, 'Stivali Nanici', 6840, 12, 7);
INSERT INTO armor VALUES (118, 'Bracciali Nanici', 8010, 13, 7);
INSERT INTO armor VALUES (119, 'Gambali Nanici', 9000, 14, 7);
INSERT INTO armor VALUES (120, 'Armatura Nanica', 10350, 15, 7);
INSERT INTO armor VALUES (121, 'Foglia di Fico', 48, 1, 8);
INSERT INTO armor VALUES (122, 'Kilt', 225, 2, 8);
INSERT INTO armor VALUES (123, 'Elmo d´Oro', 585, 3, 8);
INSERT INTO armor VALUES (124, 'Guanti d´Oro', 990, 4, 8);
INSERT INTO armor VALUES (125, 'Stivali d´Oro', 1575, 5, 8);
INSERT INTO armor VALUES (126, 'Bracciali d´Oro', 2250, 6, 8);
INSERT INTO armor VALUES (127, 'Gambali d´Oro', 2790, 7, 8);
INSERT INTO armor VALUES (128, 'Armatura d´Oro', 3420, 8, 8);
INSERT INTO armor VALUES (129, 'Scudo d´Oro', 4230, 9, 8);
INSERT INTO armor VALUES (130, 'Cappa di Fili d´Oro', 5040, 10, 8);
INSERT INTO armor VALUES (131, 'Anello di Rubino Incantato', 5850, 11, 8);
INSERT INTO armor VALUES (132, 'Anello di Zaffiro Incantato', 6840, 12, 8);
INSERT INTO armor VALUES (133, 'Anello di Giada Incantato', 8010, 13, 8);
INSERT INTO armor VALUES (134, 'Anello di Ametista Incantato', 9000, 14, 8);
INSERT INTO armor VALUES (135, 'Anello di Diamante Incantato', 10350, 15, 8);
INSERT INTO armor VALUES (136, 'Bottone', 48, 1, 9);
INSERT INTO armor VALUES (137, 'Camicia da Notte di Seta Elfica', 225, 2, 9);
INSERT INTO armor VALUES (138, 'Guanti di Seta Elfica', 585, 3, 9);
INSERT INTO armor VALUES (139, 'Pantofole di Seta Elfica', 990, 4, 9);
INSERT INTO armor VALUES (140, 'Fascia da Polso di Seta Elfica', 1575, 5, 9);
INSERT INTO armor VALUES (141, 'Gambali di Seta Elfica', 2250, 6, 9);
INSERT INTO armor VALUES (142, 'Tunica di Seta Elfica', 2790, 7, 9);
INSERT INTO armor VALUES (143, 'Cappa di Seta Elfica', 3420, 8, 9);
INSERT INTO armor VALUES (144, 'Anello della Notte', 4230, 9, 9);
INSERT INTO armor VALUES (145, 'Anello del Giorno', 5040, 10, 9);
INSERT INTO armor VALUES (146, 'Anello della Solitudine', 5850, 11, 9);
INSERT INTO armor VALUES (147, 'Anello della Pace', 6840, 12, 9);
INSERT INTO armor VALUES (148, 'Anello del Coraggio', 8010, 13, 9);
INSERT INTO armor VALUES (149, 'Anello della Virtù', 9000, 14, 9);
INSERT INTO armor VALUES (150, 'Anello della Vita', 10350, 15, 9);
INSERT INTO armor VALUES (151, 'Cappa di Pegasus', 5040, 10, 10);
INSERT INTO armor VALUES (152, 'Armatura di Pegasus', 4230, 9, 10);
INSERT INTO armor VALUES (153, 'Gambali Pegasus', 3420, 8, 10);
INSERT INTO armor VALUES (154, 'Stivali di Pegasus', 2790, 7, 10);
INSERT INTO armor VALUES (155, 'Stivali di Pegasus', 2250, 6, 10);
INSERT INTO armor VALUES (156, 'Bracciali di Pegasus', 1575, 5, 10);
INSERT INTO armor VALUES (157, 'Guanti di Pegasus', 990, 4, 10);
INSERT INTO armor VALUES (158, 'Elmo di Pegasus', 585, 3, 10);
INSERT INTO armor VALUES (159, 'Scarpe da Piattaforma', 225, 2, 10);
INSERT INTO armor VALUES (160, 'Abito del Tempo Libero', 48, 1, 10);
INSERT INTO armor VALUES (161, 'Ciondolo Piumato di Pegasus', 5850, 11, 10);
INSERT INTO armor VALUES (162, 'Cintura Piumata di Pegasus', 6840, 12, 10);
INSERT INTO armor VALUES (163, 'Scudo Blasonato di Pegasus', 8010, 13, 10);
INSERT INTO armor VALUES (164, 'Anello Blasonato di Pegasus', 9000, 14, 10);
INSERT INTO armor VALUES (165, 'Corona Blasonata di Pegasus', 10350, 15, 10);
INSERT INTO armor VALUES (166, 'Vestiti Nuovi', 48, 1, 11);
INSERT INTO armor VALUES (167, 'Costume da Gallina', 225, 2, 11);
INSERT INTO armor VALUES (168, 'Guanti della Grazia', 585, 3, 11);
INSERT INTO armor VALUES (169, 'Bracciali della Bellezza', 990, 4, 11);
INSERT INTO armor VALUES (170, 'Elmo della Salute', 1575, 5, 11);
INSERT INTO armor VALUES (171, 'Gambali della Buona Sorte', 2250, 6, 11);
INSERT INTO armor VALUES (172, 'Stivali dell´Audacia', 2790, 7, 11);
INSERT INTO armor VALUES (173, 'Tunica della Tolleranza', 3420, 8, 11);
INSERT INTO armor VALUES (174, 'Cappa della Confidenza', 4230, 9, 11);
INSERT INTO armor VALUES (175, 'Anello della Giustizia', 5040, 10, 11);
INSERT INTO armor VALUES (176, 'Collana del Narcicismo', 5850, 11, 11);
INSERT INTO armor VALUES (177, 'Pendente del Potere', 6840, 12, 11);
INSERT INTO armor VALUES (178, 'Corazza della Benevolenza', 8010, 13, 11);
INSERT INTO armor VALUES (179, 'Scudo della Superiorità', 9000, 14, 11);
INSERT INTO armor VALUES (180, 'Scettro della Forza', 10350, 15, 11);
INSERT INTO armor VALUES (181, 'Elmo di Pelle di Drago', 48, 1, 12);
INSERT INTO armor VALUES (182, 'Guanti di Pelle di Drago', 225, 2, 12);
INSERT INTO armor VALUES (183, 'Stivali di Pelle di Drago', 585, 3, 12);
INSERT INTO armor VALUES (184, 'Bracciali di Pelle di Drago', 990, 4, 12);
INSERT INTO armor VALUES (185, 'Gambali di Pelle di Drago', 1575, 5, 12);
INSERT INTO armor VALUES (186, 'Tunica di Pelle di Drago', 2250, 6, 12);
INSERT INTO armor VALUES (187, 'Cappa di Pelle di Drago', 2790, 7, 12);
INSERT INTO armor VALUES (188, 'Elmo di Scaglie di Drago', 3420, 8, 12);
INSERT INTO armor VALUES (189, 'Guanti di Scaglie di Drago', 4230, 9, 12);
INSERT INTO armor VALUES (190, 'Stivali di Scaglie di Drago', 5040, 10, 12);
INSERT INTO armor VALUES (191, 'Bracciali di Scaglie di Drago', 5850, 11, 12);
INSERT INTO armor VALUES (192, 'Gambali di Scaglie di Drago', 6840, 12, 12);
INSERT INTO armor VALUES (193, 'Armatura di Scaglie di Drago', 8010, 13, 12);
INSERT INTO armor VALUES (194, 'Cappa di Scaglie di Drago', 9000, 14, 12);
INSERT INTO armor VALUES (195, 'Scudo di Scaglie di Drago', 10350, 15, 12);
# --------------------------------------------------------

#
# Struttura della tabella `creatures`
#

CREATE TABLE creatures (
  creatureid int(11) NOT NULL auto_increment,
  creaturename varchar(50) default NULL,
  creaturelevel int(11) default NULL,
  creatureweapon varchar(50) default NULL,
  creaturelose varchar(120) default NULL,
  creaturewin varchar(120) default NULL,
  creaturegold int(11) default NULL,
  creatureexp int(11) default NULL,
  creaturehealth int(11) default NULL,
  creatureattack int(11) default NULL,
  creaturedefense int(11) default NULL,
  oldcreatureexp int(11) default NULL,
  createdby varchar(50) default NULL,
  location tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (creatureid),
  KEY creaturelevel (creaturelevel)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `creatures`
#

INSERT INTO creatures VALUES (1, 'Ladro Kender', 1, 'Hoopack Roteante', 'Vorresti solo poter borseggiare le tasche del ladro per riprenderti i tuoi soldi.', NULL, 36, 14, 10, 1, 1, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (2, 'Studente Sgarbato', 1, 'Libro di testo malconcio', 'Hai mandato lo studente in detenzione permanente', NULL, 36, 14, 10, 1, 1, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (3, 'Baby Unicorno', 1, 'Corno Appuntito', 'Ti senti veramente idiota ad uccidere una cosa tanto carina.', NULL, 36, 14, 10, 1, 1, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (4, 'Topolino Purpureo', 1, 'Codino rosa', 'È scritto nero su bianco: questo strano topo in technicolor non esiste più.', NULL, 36, 14, 10, 1, 1, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (5, 'Maialetto Grufolante', 1, 'Muso agitato', 'Mmm... Bacon', NULL, 36, 14, 10, 1, 1, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (6, 'Vecchia Zitella Isterica', 2, 'Odiose lamentele', 'Non faceva altro che lagnarsi... lagnarsi... lagnarsi...', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (7, 'Uistiti Nano', 2, 'Pezzi di corteccia', 'La strana scimmietta cade dagli alberi e resta immobile.', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (8, 'Uomo Corpulento', 15, 'Assorbenza Incredibile', 'Fantastica, Fantastica sul Ciccione... Eeeh, Eeeh, Eeeh!', NULL, 531, 189, 155, 29, 21, 14, 'Bluspring', 0);
INSERT INTO creatures VALUES (9, 'Banshee Urlante', 2, 'Splendida Voce Ossessionante', 'Affondi la tua arma nel suo seno, zittendo il suo canto.', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (10, 'Orsa Minore', 2, 'Luci Intermittenti', 'Dopo una battaglia astronomica, è l´Orsa Minore a vedere le stelle.', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (11, 'Troll di Pietra', 2, 'Fauci Spalancate', 'Quel troll è rocciosamente brutto.', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (12, 'Aspide viscido', 2, 'Occhi ipnotici', 'Interrompi lo sguardo mortale del serpente per salvarti la vita', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (13, 'Windan Il Barbaro', 2, 'Lancia Piumata', 'Era solo un peso piuma.', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (14, 'Giovane stregone studioso', 2, 'Incantesimi appena memorizzati', 'Forse avrebbe dovuto studiare di più.', NULL, 97, 24, 21, 3, 3, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (15, 'Ventiquattro merli', 3, 'Torte appena sfornate', 'Non è forse un pasto degno di un re?', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (16, 'Amazzone', 3, 'Arco e frecce', 'La bellissima donna guerriera ha subito la sua prima sconfitta.', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (17, 'Madre ossessiva e possessiva', 3, 'Coprifuoco alle 9 di sera', 'Con un colpo ben assestato hai salvato la tua vita sociale!', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (18, 'Grosso Programmatore Calvo', 3, 'Fronte Rilucente', 'Ti sembra di aver già visto questo tipo al villaggio.', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (19, 'Nonno di qualcuno', 3, 'Storie senza capo né coda', 'Questo ti ricorda di quando ti serviva un tacco nuovo per la scarpa.', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (20, 'Delinquente Giovanile', 3, 'Pessimo Atteggiamento', 'Con un tonfo di soddisfazione, gli togli il ghigno dalla faccia.', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (21, 'Splendido Spiritello dei Boschi', 3, 'Gentilezza e tranquillità', 'Lo hai davvero ridotto ai minimi termini.', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (22, 'Bella di Società', 3, 'Fascino del Sud', 'Ora non arriverà in tempo al ballo!', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (23, 'Grosso Pesce Rosso Fallito', 3, 'Orribili jingle pubblicitari', 'Ecco un pesce che ha sorriso finché non gli hai staccato la testa.', NULL, 148, 34, 32, 5, 4, 14, 'Appleshiner', 0);
INSERT INTO creatures VALUES (24, 'Folla inferocita', 4, 'Torce', 'Non c´è giustizia per una folla inferocita.', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (25, 'Orso Polare', 4, 'Tremendi artigli', 'Quell´orso aveva davvero bisogno di una lezione di comportamento.', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (26, 'Scheletro in Decomposizione', 4, 'Spada Arruginita', 'La sua tibia dovrebbe essere un bel gioco per il tuo cane.', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (27, 'Willie il Super Criceto', 4, 'Semi di Girasole Nucleari', 'Fai un sorriso di scherno a Willie e mangiucchi qualche seme tranquillamente', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (28, 'Demone Alato della Morte', 4, 'Sguardo Infuocato', 'Decapiti il demone per assicurarti che sia morto.', NULL, 162, 45, 43, 7, 6, 15, 'foilwench', 0);
INSERT INTO creatures VALUES (29, 'Piccolo Drago', 4, 'Aria calda', 'Per un attimo hai pensato che QUESTO fosse il Drago Verde. Ma, ahimé, sbagliavi.', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (30, 'Mostruoso Coniglio della Polvere', 4, 'Attacchi di starnuti', 'Spazzi il povero coniglio sotto il tappeto quando nessuno ti sta guardandp', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (31, 'Coniglietto', 4, 'Dentini appuntiti', 'E pensare che stavi quasi per scappare!', NULL, 162, 45, 43, 7, 6, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (32, 'Unicorno Maturo', 5, 'Potente Corno', 'L´unicorno è un animale splendido, anche da morto.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (33, 'Gnomo Negromante', 5, 'Scheletro Familliare', 'Una volta ucciso il familiare, lo Gnomo era una cosa da niente.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (34, 'Dinosauro Purpureo', 5, 'Natura gentile', 'Sembra che ci sia una persona in quello strano dinosauro, ti domandi perché.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (35, 'Emissario Orco', 5, 'Diplomazia', 'Non sei mai stato un tipo diplomatico.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (36, 'Spiritello Oscuro', 5, 'Magia Nera', 'Lo spiritello esala un ultimo respiro mentre metti fine alla sua patetica vita.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (37, 'Tazza del bagno incantata', 5, 'Scarico Ninja', 'Questo era proprio strano!', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (38, 'Elementale dell´Acqua Gigante', 5, 'Pioggia Torrenziale', 'L´enorme creatura è ridotta ad una pioggerellina primaverile.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (39, 'Violini', 5, 'Note stonate', 'Qualcuno può far tacere i violini PER FAVORE?', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (40, 'Crema di piselli', 5, 'Melma verde', 'Dovremmo tutti pregare per la crema di piselli.', NULL, 198, 55, 53, 9, 7, 15, 'Appleshiner', 0);
INSERT INTO creatures VALUES (41, 'Fungo magico', 6, 'Colori cangianti', 'Credo tu non debba chiedere ad Alice adesso.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (42, 'Uomo Invisibile', 6, 'Qualcosa di Invisibile e affilato', 'Non sei del tutto sicuro che sia morto e non nascosto... dopo tuto è invisibile.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (43, 'Clown della Morte', 6, 'Animali fatti con i Palloncini dell´Aldilà', 'Strombazzi il naso del clown prima di andartene.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (44, 'Cuore di Pietra', 6, 'Pensieri non romantici', 'Ahia, gli hai spezzato il cuore!', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (45, 'Guerriero Troll', 6, 'Spadone d´Acciaio', 'Wow, puzzava terribilmente già da vivo, ci pensi come puzzerà domani?', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (46, 'Bettie l´Incredibile Farfalla Kung-Fu', 6, 'Splendide Ali da Kung-Fu', 'Sei sopravvissuto in uno scontro all´ultimo sangue con una farfalla. C´è da andarne fieri.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (47, 'Oca dalle Uova d´Oro', 6, 'Uova d´Oro a 14 carati', 'Hai ucciso l´oca ma è troppo pesante per portarla in città. Quanta ricchezza sprecata.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (48, 'Tigre Nera', 6, 'Artigli', 'Pesti il corpo della tigre pensando allo splendido tappeto che ne verrà fuori.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (49, 'Lambert il Leone Codardo', 6, 'Timido Ruggito', 'Tu hai combattuto come un leone e lui è morto come un agnello.', NULL, 234, 66, 64, 11, 8, 16, 'Appleshiner', 0);
INSERT INTO creatures VALUES (50, 'Marsha della Jungla', 7, 'Scimmie addestrate', 'Povera Marsha, pensava di averle addestrate meglio quelle scimmie!', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (51, 'Moe', 7, 'Due coltelli', 'Forse quei coltelli non erano un granché.', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (52, 'Bara', 7, 'Premonizioni Terrificanti', 'Il miglior modo per liberarsene sarebbe darle fuoco.', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (53, 'Alligatore Verde', 7, 'Poderose Mascelle', 'Cielo!  Avrebbe potuto ucciderti!', NULL, 268, 77, 74, 13, 10, 17, 'foilwench', 0);
INSERT INTO creatures VALUES (54, 'Lindsey, Figlia di Erin lo Scoiattolo Ninja', 7, 'Uno sparanoccioline', 'Questi scoiattoli sono pazzi.', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (55, 'Studente Anziano della Scuola di Magia', 7, 'Incantesimo della Memoria', 'Al diavolo! Si è preso la tua memoria prima che lo uccidessi. Ma tu chi sei, a proposito?', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (56, 'Cesto della biancheria sporca incantato', 7, 'Calzini Puzzolenti del Terrore', 'Allora ecco cosa succede ai calzini perduti!', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (57, 'Mike Bongiorno', 7, 'Un quiz visto un milione di volte', 'Deve essere stata la battaglia più soddisfacente della tua vita.', NULL, 268, 77, 74, 13, 10, 17, 'CMT', 0);
INSERT INTO creatures VALUES (58, 'Computer Macintosh', 7, 'Colori incredibilmente vividi', 'Allora è questo che succede quando clicchi di destro un Mac.', NULL, 268, 77, 74, 13, 10, 17, 'Appleshiner', 0);
INSERT INTO creatures VALUES (59, 'Arredatore di interni', 8, 'Spada e Scudo in colori coordinati', 'Non ti piaceva il suo stile', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 0);
INSERT INTO creatures VALUES (60, 'Figlia del Diavolo', 8, 'Aspetto peccaminoso', 'È la ragazza da cui tua madre ti ha sempre detto di tenerti alla larga.', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 1);
INSERT INTO creatures VALUES (61, 'Cavalli selvaggi', 8, 'Trascinamento', 'Penso che il detto sia vero, un cavallo selvaggio non può portarti via.', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 0);
INSERT INTO creatures VALUES (62, 'Elementale del Vapore', 8, 'getti di aria rovente', 'Non c´era abbastanza vapore per fare un buon espresso', NULL, 302, 89, 84, 15, 11, 19, 'foilwench', 0);
INSERT INTO creatures VALUES (63, 'Gordon il Divoratore di Formaggio', 8, 'Natura Odorosa', 'Lo hai affettato!', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 0);
INSERT INTO creatures VALUES (64, 'Narcolettico', 8, 'Sonnolenza', 'Questo narcolettico non si sveglierà tanto presto.', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 0);
INSERT INTO creatures VALUES (65, 'Sciame di Api', 8, 'Punture', 'Ti senti come un puntaspilli, ma almeno sei vivo.', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 0);
INSERT INTO creatures VALUES (66, 'Mago Halfling', 8, 'Incantesimi Dolorosi', 'Non è stata una battaglia difficile come pensavi.', NULL, 302, 89, 84, 15, 11, 19, 'Appleshiner', 0);
INSERT INTO creatures VALUES (67, 'Cittadino del Villaggio di Eythgim', 9, 'Insulti', 'Mamma mia, ma che gli avevi fatto?', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (68, 'Vecchia strega', 9, 'Mela rossa', 'È il suo turno di dormire il sonno incantato.', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (69, 'Bufalo in Carica', 9, 'Zoccoli', 'Sai come fermare un bufalo che carica? Togligli la carta di credito!', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (70, 'Pedina', 9, 'Tattica semplice', 'La sua era una pessima mossa.', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (71, 'Romulano', 9, 'Confusione di ambientazione', 'Sembravano più forti in TV.', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (72, 'Grosso Grifone', 9, 'Becco e Artigli', 'La creatura mitologica non esiste più.', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (73, 'Tipico nemico di James Bond', 9, 'Cellulare Letale', 'Ma dico, chi ha tirato un cellulare? Fa male!', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (74, 'Furetto Peloso', 9, 'Dolcezza Disarmante', 'Chi se lo immaginava che i furetti fossero tanto violenti!', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (75, 'Artista Denutrito', 9, 'Pennelli appuntiti', 'Adesso sembra un quadro astratto!', NULL, 336, 101, 94, 17, 13, 21, 'Appleshiner', 0);
INSERT INTO creatures VALUES (76, 'Guardia del Villaggio di Eythgim', 10, 'Spadino', 'La gente di quel villaggio sembra arrabbiata!', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (77, 'Nano da Giardino', 10, 'Appiccicosità Dolorosa', 'Stupidi nani da giardino, ma poi che ci fanno nella foresta!', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (78, 'Gallina', 10, 'Coccodé', 'La fortuna ti sorride, stasera pollo arrosto!', NULL, 369, 114, 105, 19, 14, 24, 'foilwench', 0);
INSERT INTO creatures VALUES (79, 'Cecchino', 10, 'Mira infallibile', 'Ci ha messo tanto a prendere la mira che intanto lo hai raggiunto e preso a calci.', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (80, 'Scatola di Fazzolettini Incantata', 10, 'Germi del Raffreddore', 'Prendi la scatola e te la metti in tasca. Non si sa mai quando può servire un fazzolettino', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (81, 'Boscaiolo Fantasma', 10, 'Ascia Spettrale', 'Non sai bene come, ma hai ucciso una cosa che era già morta. Tanto meglio per te.', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (82, 'Zombie', 10, 'Carni in decomposizione', 'È proprio ridotto a pezzi, non è vero?', NULL, 369, 114, 105, 19, 14, 24, 'foilwench', 0);
INSERT INTO creatures VALUES (83, 'Fortula il Gatto delle Pianure', 10, 'Denti e Artigli', 'Adesso è il gatto di sotto le pianure.', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (84, 'Erin lo Scoiattolo Ninja', 10, 'Furia di ghiande', 'Quello scoiattolo era leggermente pazzo', NULL, 369, 114, 105, 19, 14, 24, 'Appleshiner', 0);
INSERT INTO creatures VALUES (85, 'Uccello azzurro della felicità', 11, 'Gradevole Melodia', 'Comunque la felicità è sopravvalutata', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (86, 'Opinionista', 11, 'Opinioni Personali', 'Tutto bene, era solo un chiacchierone.', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (87, 'Drago Nano', 11, 'Soffio di fuoco', 'Speriamo non fosse un parente del Drago Verde', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (88, 'Fotografo Zelante', 11, 'Flash', 'È la maledizione di quelli ricchi e famosi.', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (89, 'Cérto', 11, 'Cérto', 'Non hai ancora capito cos´era, ma è morto, cérto.', NULL, 402, 127, 115, 21, 15, 27, 'CMT', 0);
INSERT INTO creatures VALUES (90, 'Soldato del Villaggio di Eythgim', 11, 'Grido di Battaglia', 'Eythgim sembra averti dichiarato guerra.', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (91, 'Rockstar Glam degli ´80', 11, 'Makeup Accecante', 'Conosci il tuo motto: Vivi e lascia morire.', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (92, 'Vigilante', 11, 'Sacco di pomoli di porta', 'Questo massacro ti disgusta.', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 0);
INSERT INTO creatures VALUES (93, 'Il Diavolo', 11, 'Dannazione Eterna', 'Per essere il Diavolo, non era tanto cattivo.', NULL, 402, 127, 115, 21, 15, 27, 'Appleshiner', 1);
INSERT INTO creatures VALUES (94, 'Mercenario', 12, 'Sguardo raggelante', 'Ti andrebbe una birra.', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (95, 'Specchio Magico', 12, 'Adulazioni', 'Specchio specchio delle mie brame, ti ho ridotto a del ciarpame.', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (96, 'Revival', 12, 'Musiche d´epoca', 'Quando è troppo è troppo!', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (97, 'Burro lo Spiritello', 12, 'Polvere di Fata', 'Burro?  Ma che razza di nome è Burro?', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (98, 'Padre Tempo', 12, 'Età Avanzata', 'La vittoria non ti consola, lo sai che prima o poi lui ti prenderà.', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (99, 'Arceri del Villaggio di Eythgim', 12, 'Frecce Infuocate', 'L´albero sta bruciando, forse dovresti provare a spegnerlo.', NULL, 435, 141, 125, 23, 17, 31, 'foilwench', 0);
INSERT INTO creatures VALUES (100, 'Nubi Viventi', 12, 'Fulmini', 'La tempesta è finita, puoi vedere la luce del sole affacciarsi tra le nubi.', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (101, 'Bradipo dai Tre Pollici', 12, 'Riflessi lenti', 'Le tue rapide mosse erano troppo veloci per il bradipo.', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (102, 'Morte', 12, 'Tocco gelido', 'Eccola lì!  Morta!!!!!', NULL, 435, 141, 125, 23, 17, 31, 'Appleshiner', 0);
INSERT INTO creatures VALUES (103, 'Cerbero', 13, 'Tre fauci sbavanti', 'Ognuno dei tre aveva l´alito peggiore del precedente!', NULL, 467, 156, 135, 25, 18, 36, 'Appleshiner', 0);
INSERT INTO creatures VALUES (104, 'Uomo della scura scura, fitta fitta, scura foresta', 13, 'Ringhi e morsi', 'Ora la creatura della scura scura, fitta fitta, scura foresta è finalmente morta.', NULL, 467, 156, 135, 25, 18, 36, 'cmt', 0);
INSERT INTO creatures VALUES (105, 'Diavolo in vestito blu', 13, 'Promesse tentatrici', 'Comunque preferisci gli abiti neri.', NULL, 467, 156, 135, 25, 18, 36, 'Appleshiner', 0);
INSERT INTO creatures VALUES (106, 'Gigante', 13, 'Mazza', 'Cielo, ti è quasi caduto addosso!', NULL, 467, 156, 135, 25, 18, 36, 'Appleshiner', 0);
INSERT INTO creatures VALUES (107, 'Comandante dell´esercito di Eythgim', 13, 'Tattiche Vincenti', 'Hai sconfitto il loro esercito uccidendone il capo.', NULL, 467, 156, 135, 25, 18, 36, 'cmt', 0);
INSERT INTO creatures VALUES (108, 'Drago del Ghiaccio', 13, 'Soffio di Gelo', 'Lo hai sconfitto, ma ti sei beccato il raffreddore.', NULL, 467, 156, 135, 25, 18, 36, 'Appleshiner', 0);
INSERT INTO creatures VALUES (109, 'Quarantaquattro Gatti', 13, 'Code attorcigliate', 'Ma dove sono finiti i due di resto?', NULL, 467, 156, 135, 25, 18, 36, 'CMT', 0);
INSERT INTO creatures VALUES (110, 'Balena', 13, 'Coda', 'Violet non ci crederà mai che una balena ti ha attaccato nella foresta.', NULL, 467, 156, 135, 25, 18, 36, 'Appleshiner', 0);
INSERT INTO creatures VALUES (111, 'Gorma il Lebbroso', 13, 'Malattia Contagiosa', 'Sembra che la strategia di Gorma sia andata in pezzi...', NULL, 467, 156, 135, 25, 18, 36, 'foilwench', 0);
INSERT INTO creatures VALUES (112, 'Maestro Samurai', 14, 'Doppie Scimitarre', 'Ti inchini al maestro caduto prima di andartene.', NULL, 499, 172, 145, 27, 20, 42, 'Appleshiner', 0);
INSERT INTO creatures VALUES (113, 'Barista', 14, 'Anatra', 'Non è un´anatra! È un pollo!', NULL, 499, 172, 145, 27, 20, 42, 'Appleshiner', 0);
INSERT INTO creatures VALUES (114, 'Principe del Villaggio di Eythgim', 14, 'Lusinghe d´oro', 'Non sei facile da corrompere, e lui era un mortale nemico.', NULL, 499, 172, 145, 27, 20, 42, 'Appleshiner', 0);
INSERT INTO creatures VALUES (115, 'Mostro di Loch Ness', 14, 'Apparizione Shockante', 'Ma per quale ragione quel mostro avrebbe dovuto essere in questa foresta? Deve essere stato un sogno!', NULL, 499, 172, 145, 27, 20, 42, 'Appleshiner', 0);
INSERT INTO creatures VALUES (116, 'Puffo Quattrocchi', 14, 'Commento fuori luogo', 'Evitiamo di commentare che è meglio.', NULL, 499, 172, 145, 27, 20, 42, 'CMT', 0);
INSERT INTO creatures VALUES (117, 'Principessa del Villaggio di Eythgim', 15, 'Parole Ingannatrici', 'Ha cercato di tentarti, ma hai resistito.', NULL, 531, 189, 155, 29, 21, 42, 'cmt', 0);
INSERT INTO creatures VALUES (118, 'Orrida Arpia', 14, 'Soffio Velenoso', 'Il suo alito era peggiore del suo morso.', NULL, 499, 172, 145, 27, 20, 42, 'Appleshiner', 0);
INSERT INTO creatures VALUES (119, 'Topo di Campagna', 14, 'Masticazione', 'Che potenza di topo!', NULL, 499, 172, 145, 27, 20, 42, 'Appleshiner', 0);
INSERT INTO creatures VALUES (120, 'Monarca del Villaggio di Eythgim', 16, 'Comandi Reali', 'Suppongo che questo ti abbia reso re del loro villaggio.', NULL, 563, 207, 166, 31, 22, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (121, 'Ciclope Guerriero', 15, 'Occhio di fuoco', 'L´occhio del ciclope morto ti fissa con sguardo vacuo.', NULL, 531, 189, 155, 29, 21, 49, 'foilwench', 0);
INSERT INTO creatures VALUES (122, 'Cupido', 15, 'Frecce Rosa dell´Amore', 'Hai ucciso Cupido, non troverai mai il vero amore.', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (123, 'Giornalista Scandalistica', 15, 'Storie tirate per i capelli', 'Se vuoi saperlo da me, LEI era l´alieno con sei braccia che ha dato alla luce il più famoso impersonatore di Elvis.', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (124, 'Mago Malvagio', 15, 'Anime Tormentate', 'Hai liberato le anime torturate.', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (125, 'Artemide, Dea della caccia', 15, 'Segugi alati', 'La luna stessa piange la morte di Artemide', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (126, 'Centauro Arcere', 15, 'Precisione di tiro letale', 'L´uomo-animale giace esanime.', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (127, 'Mimo', 15, 'Qualcosa di immaginario', 'La sua morte è una triste sciarada.', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (128, 'Cuoco del Chili', 15, 'Pepe della Follia', 'Questo sì che è del buon chili.', NULL, 531, 189, 155, 29, 21, 49, 'Appleshiner', 0);
INSERT INTO creatures VALUES (129, 'Brownie', 16, 'Armi microscopiche', 'Il piccolo guerriero muore con uno squittio.', NULL, 563, 207, 166, 31, 22, 57, 'Appleshiner', 0);
INSERT INTO creatures VALUES (130, 'Oscurità', 16, 'Terrore autoindotto', 'Non hai più paura del buio.', NULL, 563, 207, 166, 31, 22, 57, 'Appleshiner', 0);
INSERT INTO creatures VALUES (131, 'Re della Montagna', 16, 'Furia Divina', 'Il Re è morto, lunga vita al Re.', NULL, 563, 207, 166, 31, 22, 57, 'Appleshiner', 0);
INSERT INTO creatures VALUES (134, 'Solitudine', 17, 'Silenzio', 'Qual è il suono di una mano che applaude?', NULL, 36, 0, 1, 0, 0, 0, NULL, 0);
INSERT INTO creatures VALUES (135, 'Solitudine', 18, 'Silenzio', 'Qual è il suono di una mano che applaude?', NULL, 0, 0, 1, 0, 25, 0, NULL, 0);
INSERT INTO creatures VALUES (136, 'Gatto con gli stivali', 15, 'Bugie a fin di bene', 'Dovrebbero restargli altre otto vite... o erano sei?', '', 531, 189, 155, 29, 21, 0, 'CMT', 0);
INSERT INTO creatures VALUES (138, 'Crema di formaggio', 8, 'Eccesso di Colesterolo', 'Questa non te la ritroverai sui fianchi domani.', NULL, 302, 89, 84, 15, 11, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (139, 'Sciame di Coccinelle', 3, 'Residui puzzolenti', 'Non sembra che portassero molta fortuna.', NULL, 148, 34, 32, 5, 4, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (140, 'Tifoso di Calcio', 6, 'Inni Rauchi', 'Non ha più tanto da inneggiare.', NULL, 234, 66, 64, 11, 8, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (141, 'Re Agrippa', 13, 'Dardi Infuocati', 'Mentre guardi il suo corpo fumante, ti meravigli dell´inutilità dei dardi infuocati.', NULL, 467, 156, 135, 25, 18, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (142, 'Bel Bambino Birichino', 1, 'Domande Curiouse', 'Pensi che sia stata l´allitterazione a farlo perdere.', NULL, 36, 14, 10, 1, 1, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (143, 'La Peppina', 5, 'Il caffè della Peppina', 'Fiuu... per un attimo hai temuto di doverlo bere!', NULL, 198, 55, 53, 9, 7, NULL, 'CMT', 0);
INSERT INTO creatures VALUES (144, 'Vecchia Zia', 11, 'Dolcini, coccole e caramelle', 'Ti sei salvato dalla carie e dal diabete in un colpo solo.', NULL, 402, 127, 115, 21, 15, NULL, 'CMT', 0);
INSERT INTO creatures VALUES (145, 'Universitaria in camicia da notte', 7, 'Lotta coi cuscini', 'Peccato che sia morta, ti piaceva il suos tile di combattimento.', NULL, 268, 77, 74, 13, 10, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (146, 'Pinocchio', 14, 'Bugie Ridicole', 'E adesso chi lo dice alla Fata dai Capelli Turchini?', NULL, 499, 172, 145, 27, 20, NULL, 'CMT', 0);
INSERT INTO creatures VALUES (147, 'Audrey la Pazza', 9, 'Gattini Ingordi', 'Con una buona pedata, Audrey e i gattini non ci sono più.', NULL, 336, 101, 94, 17, 13, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (148, 'Shining Happy People', 5, 'LaLaLaLaLa LaLa LaLaLa', 'Volevano solo esserti amici, rude!', NULL, 198, 55, 53, 9, 7, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (149, 'Grosso Pianeta', 8, 'Spinta Gravitazionale', 'Questa sì che era una situazione grave.', NULL, 302, 89, 84, 15, 11, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (150, 'Sig. Orso', 2, 'Stanchezza Schiacciante', 'Il Sig. Orso era troppo stanco per finire di giocare ed è tornato a dormire.', NULL, 97, 24, 21, 3, 3, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (151, 'Maniaco degli SMS', 12, 'T.V.T.T.B.', 'Xò ke battaglia!', NULL, 435, 141, 125, 23, 17, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (152, 'La piccola Cocobean', 4, 'Il suo arrosto speciale', 'Ti ha davvero fatto battere il cuore, ma adesso è crollata in terra.', NULL, 162, 45, 43, 7, 6, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (153, 'Enorme Ciuffo di Peli', 5, 'Otturazione dello scarico', 'La massa di peli e capelli è stata scaricata in mare!', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (154, 'Capo Chef', 4, 'Anguille Flambeè!', 'Adesso è uno Chef de-capi-tato!', NULL, 162, 45, 43, 7, 6, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (155, 'Il Governo', 7, 'Tasse', 'Adesso hai diritto ad un rimborso!', NULL, 268, 77, 74, 13, 10, NULL, 'Bluspring', 0);
INSERT INTO creatures VALUES (156, 'Ubriacone', 1, 'Alcolismo', 'È un bene che l´abbia steso tu prima che lo facesse la cirrosi!', NULL, 36, 14, 10, 1, 1, NULL, 'Bluspring', 0);
INSERT INTO creatures VALUES (157, 'Uomo Metano', 7, 'Gas Sgradevoli', 'Sevono essere stati tutti quei fagioli!', NULL, 268, 77, 74, 13, 10, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (158, 'Mammut Lanoso', 8, 'Zanne', 'Scali i resti del Mammut e ti dichiari re (o regina) per un giorno!', NULL, 302, 89, 84, 15, 11, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (159, 'Osceno mangiatore d´aglio', 10, 'Alito Pestilenziale', 'Meno male che non sei un vampiro!', NULL, 369, 114, 105, 19, 14, NULL, 'CMT', 0);
INSERT INTO creatures VALUES (160, 'Uomo Nero', 3, 'Paura', 'Forse aveva solo bisogno di una bella doccia!', NULL, 148, 34, 32, 5, 4, NULL, 'foilwench', 0);
INSERT INTO creatures VALUES (161, 'Vagabondo', 1, 'Bastone da Passeggio', 'Le sue ultime parole furono "..devo proteggere il drago"', NULL, 36, 14, 10, 1, 1, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (162, 'Campeggiatore', 2, 'Bastoncino per arrostire i Marshmallow', 'Con l´ultimo respiro esclama "...e non ho nemmeno mai visto il drago."', NULL, 97, 24, 21, 3, 3, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (163, 'Ranger Forestale', 3, 'Fucile da Caccia', 'Nel suo zaino trovi una copia di "Ricerca dei draghi for Dummies"', NULL, 148, 34, 32, 5, 4, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (164, 'Ambientalista', 4, 'Volantini di Propaganda', 'I volantini dicono "Chi proteggerà il povero Drago indifeso?"', NULL, 162, 45, 43, 7, 6, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (165, 'Eremita della Foresta', 5, 'Fioda fatta a mano', 'Dice solo questo mentre muore: "Devi abbandonare la Foresta del Drago."', NULL, 198, 55, 53, 9, 7, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (166, 'Membro degli Amanti Anonimi dei Draghi', 6, 'Drago di pelouche', 'Se uno indossa una spilla con scritto "Amante Anonimo dei Draghi" non è poi tanto anonimo quanto crede.', NULL, 234, 66, 64, 11, 8, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (167, 'Osservatore di Draghi Iniziato', 7, 'Binocolo', 'Tutto ciò che è riuscito a dire è stato "Shhh, mi spaventi i draghi!"', NULL, 268, 77, 74, 13, 10, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (168, 'Osservatore di Draghi Junior', 8, 'Scocciatura', 'Trovi un libro di testo sull´osservazione dei draghi nel suo zaino.', NULL, 302, 89, 84, 15, 11, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (169, 'Osservatore di Draghi Senior', 9, 'Equipaggiamento da speleologo', 'Trovi una copia di "Draghi di caverna e dove trovarli." nel suo zaino.', NULL, 336, 101, 94, 17, 13, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (170, 'Maestro Osservatore di Draghi', 10, 'Stealth', 'Ti domandi chi osservi gli osservatori', NULL, 369, 114, 105, 19, 14, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (171, 'Domatore di Draghi Iniziato', 11, 'Asta per drago di 3 metri', 'È una fortuna che quell´asta funzioni solo sui draghi di 3 metri!', NULL, 402, 127, 115, 21, 15, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (172, 'Domatore di Draghi Junior', 12, 'Grossa Frusta', 'Se non distingue la differenza tra te e un drago, ha parecchio da imparare.', NULL, 435, 141, 125, 23, 17, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (173, 'Domatore di Draghi Senior', 13, 'Cerchio Molto Grande', 'Ti dispiace averlo ucciso, ti sarebbe piaciuto vederlo far slatare un drago attraverso quel cerchio.', NULL, 467, 156, 135, 25, 18, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (174, 'Maestro Domatore di Draghi', 14, 'Drago Domato', 'Una volta ucciso il domatore, il drago è fuggito', NULL, 499, 172, 145, 27, 20, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (175, 'Cavalcadraghi in Addestramento', 15, 'Toro Meccanico', 'Imparano a cavalcare i draghi usando un toro meccanico?', NULL, 531, 189, 155, 29, 21, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (176, 'Cavalcadraghi', 16, 'Drago Volante', 'Un colpo ben piazzato atterra il drago volante ed il suo cavaliere.', NULL, 563, 207, 166, 31, 22, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (177, 'Cucciolo di pipistrello', 1, 'Alucce battenti', 'Stupido pipistrellino', NULL, 36, 14, 10, 1, 1, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (178, 'Piccolo Pipistrello', 2, 'Zanne leggermente velenose', 'Il piccolo mammifero cade al suolo', NULL, 97, 24, 21, 3, 3, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (179, 'Pipistrello', 3, 'Strilli sonori', 'Lo hai ucciso solo per farlo stare zitto.', NULL, 148, 34, 32, 5, 4, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (180, 'Grosso Pipistrello', 4, 'Guano', 'Senti il bisogno di fare un bagno.', NULL, 162, 45, 43, 7, 6, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (181, 'Pipistrello Gigante', 5, 'Aggrapparsi e mordere', 'Perchè i pipistrelli dei film devono sempre attaccarsi ai capelli della gente?', NULL, 198, 55, 53, 9, 7, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (182, 'Cucciolo di Pipistrello Vampiro', 6, 'Denti pungenti', 'Sembra più una zanzara che un pipistrello.', NULL, 234, 66, 64, 11, 8, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (183, 'Piccolo Pipistrello Vampiro', 7, 'Zanne Letali', 'Sferri un calcio al seccante succhiasangue dopo averlo abbattuto.', NULL, 268, 77, 74, 13, 10, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (184, 'Pipistrello Vampiro Medio', 8, 'Ecoradar', 'Chi ha deciso che questo pipistrello è "Medio"?  Magari è un pipistrello gigante che non è ancora cresciuto del tutto.', NULL, 302, 89, 84, 15, 11, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (185, 'Grosso Pipistrello Vampiro', 9, 'Vampirismo', 'Hai dato più sangue alla Croce Rossa', NULL, 336, 101, 94, 17, 13, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (186, 'Vampiro in forma di Pipistrello', 10, 'Brutto accento Rumeno', '"Voglio succhiarti il saaaaaaaaangue!"', NULL, 369, 114, 105, 19, 14, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (187, 'Principe dei Pipistrelli Vampiro', 11, 'Presa Mortale', 'E sì che credevi che i pipistrelli fossero repubblicani.', NULL, 402, 127, 115, 21, 15, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (188, 'Re dei Pipistrelli Vampiro', 12, 'Occhi Rossi', 'Decidi di andartene prima che i sudditi vengano a cercare il re', NULL, 435, 141, 125, 23, 17, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (189, 'Vampiro Stregone', 13, 'Incantesimi Pietrificanti', 'Un brivido freddo ti corre lungo la schiena. Preferiresti essere con Violet', NULL, 467, 156, 135, 25, 18, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (221, 'Vampirus,il Dio Pipistrello', 16, 'Potere Divino', 'Sei più divino di lui.', NULL, 563, 207, 166, 31, 22, NULL, 'Appleshiner', 1);
INSERT INTO creatures VALUES (220, 'Pipistrelli vampiri impazziti', 15, 'Numerosi attacchi', 'Perchè ci sono così tanti dannati pipistrelli in questa foresta?', NULL, 531, 189, 155, 29, 21, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (219, 'Seducente Vampira', 14, 'Mosse Appassionate', 'È stato un peccato ucciderla.', NULL, 499, 172, 145, 27, 20, NULL, 'Appleshiner', 0);
INSERT INTO creatures VALUES (222, 'Vecchio Inverno', 9, 'Freddo Pungente', 'Agiti il pugno sul suo patetico cadavere.', NULL, 336, 101, 94, 17, 13, NULL, NULL, 0);
INSERT INTO creatures VALUES (224, 'Troll a due teste', 10, 'Mazza enorme', 'Dovevi colpirlo tu, dice una testa, no era compito tuo ...... e muiono', NULL, 369, 114, 105, 19, 14, NULL, 'ADMIN', 0);
INSERT INTO creatures VALUES (225, 'Serpente', 1, 'Morso velenoso', 'Ti ho morso, morirai con il mio veleno...', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (227, 'Raptus', 9, 'Telefonate Infestanti', 'Dannato virus ...', NULL, 336, 101, 94, 17, 13, NULL, 'ADMIN', 0);
INSERT INTO creatures VALUES (228, 'Minotauro Giovane', 3, 'Corna', 'mio fratello te la farà pagare', NULL, 148, 34, 32, 5, 4, NULL, 'ADMIN', 0);
INSERT INTO creatures VALUES (229, 'Minotauro Addestrato', 6, 'Ascia da guerra', 'Muuuuuuuuaaaaaaaa ..... mi vendicheranno !!', NULL, 234, 66, 64, 11, 8, NULL, 'ADMIN', 0);
INSERT INTO creatures VALUES (230, 'Minotauro Guerriero', 9, 'Ascia da guerra', 'Pagherai con la vita ....', NULL, 336, 101, 94, 17, 13, NULL, 'ADMIN', 0);
INSERT INTO creatures VALUES (231, 'Minotauro Berserker', 12, 'Ascia da guerra enorme', 'Bwooooooooooaoaaaaargghhhhhhh !!!', NULL, 435, 141, 125, 23, 17, NULL, 'ADMIN', 0);
INSERT INTO creatures VALUES (232, 'Minotauro Eroe', 15, 'Due enormi Asce da guerra', 'Non è possibile! Sconfitto da un miserabile come te!!!', NULL, 531, 189, 155, 29, 21, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (234, 'Gnomo Squilibrato', 1, 'Specchio coperto', 'Che succede se faccio QUESTO?', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (235, 'Spiritello dell\'Acqua', 1, 'Spruzzo d\'acqua', 'È stata una battaglia rinfrescante', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (236, 'Ninfa dell\'Acqua', 3, 'un torrente d\'acqua', 'Non mi serviva un bagno, grazie lo stesso', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (237, 'Signora del Lago', 10, 'Excalibur', 'Prendi questo, dama annacquata!', NULL, 369, 114, 105, 19, 14, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (238, 'Alberello astuto', 1, 'Arrrr!', 'Ah Ha! Uno stuzzicadenti!', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (239, 'Albero Pirata', 5, 'un sonoro ARRRRR!', 'Arrrrrr! appunto.', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (240, 'Centurione Non morto', 5, 'Lancia di Bronzo', 'Hmm Scarso combattente anche la seconda volta...', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (241, 'Bardo folle', 2, 'Pentametro Giambico', 'Gli strali dell\'avversa fortuna? Nah! Solo un sacco di botte.', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (242, 'Uomo ramoscello', 1, 'Rovi e spine', 'L\'hai spezzato come... beh... un ramoscello', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (243, 'Topo rabbioso', 1, 'Dentini appuntiti', 'Quei dentini FANNO MALE!', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (244, 'Gatto randagio', 1, 'una serenata di mezzanotte', 'Finalmente si può dormire in pace!', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (245, 'Grosso Ragno Peloso', 1, 'ragnatela appiccicosa', 'Io *ODIO* i ragni', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (246, 'Lacché Goblin', 1, 'Coltello consunto', 'Chi sapeva che i goblin avessero dei lacché?', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (247, 'Chihuahua pazzo', 1, 'Avanzi del Fast food', 'Qui taco taco taco..', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (248, 'Bugs Bunny', 1, 'Battute argute', 'Th- Th- That\'s all folks!', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (249, 'Rematore non morto', 1, 'Remo decomposto', 'Strano... non vedo acqua nei dintorni', NULL, 36, 14, 10, 1, 1, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (250, 'Fantasmino perduto', 2, 'pianto triste', 'Beh, sono sicuro che questo lo aiuterà a trovare la strada...', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (251, 'Cinciallegra arrabbiata', 2, 'Cinguettio incessante', 'Tiri giù dal cielo l\'uccellino senza sforzo', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (252, 'Lucertola gigante', 2, 'lingua biforcuta', 'Le hai staccato la coda ma la lucertola è fuggita', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (253, 'Piccolo Uomo Nero', 2, 'Rumori nel buio', 'Hmm non fa più così paura ora che sei cresciuto...', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (254, 'Grosso Uomo Nero', 3, 'Ditate mentre dormi', 'OK. Era strano... Ma non spaventoso... No davvero... Comunque penso che lascerò la luce accesa stanotte...', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (255, 'Coblynau', 2, 'Giochetti maleducati', 'Forse la prossima volta dovrei dargli un po\' del mio pranzo...', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (256, 'Jack Lanterna', 2, 'Eterno Vagabondare', 'La luce ha abbandonato la sua vita', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (257, 'Ghoul appena morto', 2, 'Unghie lunghe e appuntite', 'Lo abbandoni alla pietà degli altri ghoul', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (258, 'Scheletro rinsecchito', 2, 'un pugnale arrugginito', 'Lo scheletro si riduce ad un mucchietto di polvere', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (259, 'Zombie Appena Morto', 2, 'Pugni', 'Forse ora potrà riposare in pace', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (260, 'Segugio fatato', 2, 'Occhi brillanti', 'Le fiammelle nei suoi occhi vacillano e si spengono', NULL, 97, 24, 21, 3, 3, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (261, 'Apprendista Negromante', 3, 'Incantesimi di Risucchio Vitale', 'Esala l\'ultimo respiro e si scioglie in un liquido melmoso', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (262, 'Doppleganger', 3, 'Cambiamento d\'aspetto', 'Lo uccidi e ritorna alla sua forma normale', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (263, 'Ladro della Foresta', 3, 'Spada corta avvelenata', 'Aveva le tasche quasi vuote, non doveva essere un granché come ladro', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (264, 'Scheletro Ammuffito', 3, 'Pugni decomposti', 'Heeeeey, non lahi visto in città la settimana scorsa?', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (265, 'Fata Infuriata', 3, 'Polvere Fatata Furibonda', 'La fata scompare in una nube di polvere fatata', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (266, 'Cacciatore Goblin', 3, 'un arco corto', 'Solo uno? Non girano in gruppi di solito?', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (267, 'Cucciolo di Lince', 3, 'Artigli Affilati', 'Anche i bei gattini pelosi sono pericolosi da queste parti', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (268, 'Mucchio di spazzatura animato', 3, 'Puzza di 1000 patate andate a male', 'Dovrebbero chiamarti De-Animatore!', NULL, 148, 34, 32, 5, 4, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (269, 'Negromante', 4, 'a Strength Draining Touch', 'In una nube di fumo nero, il suo corpo viene spazzato via dalla brezza', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (270, 'Golem di Canne', 4, 'Pugni di spine', 'La sola cosa rimasta di lui è una balla di fieno', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (271, 'Sciamano Goblin', 4, 'Magia Primitiva', '"Oooga Booga"', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (272, 'Spirito del Vapore', 4, 'Vapore rovente', 'Non sei più così caldo, eh?', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (273, 'Zombie Inquieto', 4, 'Spadone spezzato', 'Ora è in pace finalmente', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (274, 'Soldato Zombie', 4, 'Lancia arrugginita', 'Il suo ultimo ordine è stato marciare all\'inferno', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (275, 'Demonietto Oscuro', 4, 'Dubbi atroci', 'Il demonietto esplode in una palla di fuoco', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (276, 'Ragno Gigante', 4, 'Zanne gocciolanti di veleno', 'Io odio, odio, odio i ragni', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (277, 'Gattino non morto', 4, 'Miagolii letali', 'Un altro gatto infernale mandato all\'inferno', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (278, 'Pugnale Volante', 4, 'Punta d\'Acciaio affilata', 'Il pugnale va in frantumi appena tocca terra', NULL, 162, 45, 43, 7, 6, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (279, 'Guerriero Goblin', 5, 'Spada Corta Curva', 'Andato... e dimenticato...', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (280, 'Negromante Esperto', 5, 'un gruppetto di Zombies', 'Il negromante si trasforma in un mucchietto di sabbia nera e viene spazzato via dal vento', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (281, 'Spiritello del Fuoco', 5, 'Fiammella', 'Lo hai raffreddato in un attimo...', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (282, 'Uomo di Fango', 5, 'Fango Incantato', 'Sconfitto, l\'Uomo di Fango si secca al sole', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (283, 'Lupe il Chihuahua Ipnotico', 5, 'Sguardo della morte', 'Riesci a liberarti dei suoi profondi occhi ipnotici...', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (284, 'Ghoul', 5, 'Fame di Carne', 'Il ghoul crolla in terra con un brandello di carne tra i denti', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (285, 'Tagliagole', 5, 'Pugnali affilati', 'Un bandito in meno nella foresta', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (286, 'un Orco', 5, 'Ramo spezzato', 'Cielo, quella faccia non doveva piacere nemmeno a sua madre', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (287, 'Boscaiolo Pazzo', 5, 'Ascia ammaccata', 'Lo hai abbattuto...', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (288, 'Draghetto Volante', 5, 'coda a frusta', 'Abbatti la piccola, maestosa creatura', NULL, 198, 55, 53, 9, 7, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (289, 'Gatto della Foresta', 6, 'Artigli Minacciosi', 'Qui micio micio micio', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (290, 'Negromante Girovago', 6, 'un Gigante non morto', 'Il negromante viene spazzato via in una nube di fumo nero', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (291, 'Scheletro Gelido', 6, 'il freddo della tomba', 'Credo proprio che uno di questi riuscirebbe a raffreddare la birra di Cedrik. Forse.', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (292, 'Spirito del Freddo', 6, 'Freddo Raggelante', 'Un altro tipo freddo messo a terra', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (293, 'Fantasma di Ammazzadraghi', 6, 'Avvertimenti Oscuri', 'Tutti si chiedevano che fine avesse fatto...', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (294, 'Mago Ettin', 6, 'Incantesimi doppiamente pericolosi', 'In questo caso due teste non sono meglio di una', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (295, 'Brownie Impazzito', 6, 'Incantesimi fatati', 'Questo è pazzo!', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (296, 'Branco di Lupi Fantasma', 6, 'Ululati Spettrali', 'La quiete ti circonda. Finalmente', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (297, 'Golem di Legno', 6, 'Pugni di quercia', 'Ottimo per il camino', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (298, 'Ent Ammalato', 6, 'Rami contorti', 'Devono esserci cinquanta modi per tagliarti (Groan)', NULL, 234, 66, 64, 11, 8, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (299, 'Druido Oscuro', 7, 'Natura Corrotta', 'Mi ha perso nel punto in cui diceva di corromperla per salvarla', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (300, 'Uomo-Lucertola Scout', 7, 'Arco', 'So dove posso farmi fare un bel paio di stivali con quel che ne resta', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (301, 'Fante Diabolico', 7, 'Mazza forgiata da un demone', 'La sua armatura sfrigola mentre si scioglie e penetra nel suolo', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (302, 'Gigantesco Ragno degli Alberi', 7, 'Proiettili di Ragnatela', 'Dopo averlo tagliato in due non sembra più tanto grosso', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (303, 'Zombie Fiammeggiante', 7, 'Pugni incendiati', 'Spegni le fiamme e lo zombie crolla al suolo fumante', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (304, 'Stregone', 7, 'Incantesimo dell\'Oscurità', 'Lo decapiti solo per vederlo scomparire nel nulla, lasciando dei vestiti vuoti', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (305, 'Maestro Negromante', 7, 'un esercito di Morti', 'Uccidi il negromante ed i suoi schiavi non morti si disintegrano sotto i tuoi occhi', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (306, 'Oscurità Vivente', 7, 'l\'Oscurità di Milioni di Notti', 'La luce torna lentamente in questa parte della foresta', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (307, 'Statua Guardiana del Tempio', 7, 'Pugni di Marmo', 'E dove sarebbe il tempio?', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (308, 'OrsoGufo', 7, 'Artigli e stretta da Orso', 'Non voglio neppure sapere dove li allevano', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (309, 'L\'Uomo in Nero', 8, 'Incantesimi Negromantici', 'Finalmente ho potuto ammazzarlo', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (310, 'Gnomo Illusionista', 8, 'Illusioni Terrificanti', 'Era tutto nella sua testa', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (311, 'Scheletro demoniaco', 8, 'Rune diaboliche', 'Gli spiriti delle sue vittime lo trascinano all\'inferno', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (312, 'Maestro Druido Folle', 8, 'La terra stessa', 'La sua risata diabolica muore con lui', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (313, 'Elementalista dell\'Acqua', 8, 'Incantesimi dell\'Acqua', 'Ora sei tutto bagnato!', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (314, 'Elementalista della Terra', 8, 'Incantesimi della Terra', 'È stato un combattimento sporco', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (315, 'Guerriero Ettin', 8, 'Due Mazze di Ferro', 'Stavano andando bene prima di colpirsi l\'un l\'altro', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (316, 'Gigante della Foresta', 8, 'Albero Sradicato', 'Ho, Ho, Ho l\'omone verde.', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (317, 'Cavaliere Demoniaco', 8, 'Spada Diabolica', 'Quella spada deve averla rubata al Grande Mazinga...', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (318, 'Zombie Gigante', 8, 'Pugni Giganti', 'Quando sono così grossi PUZZANO PEGGIO!', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (319, 'Spettro della Brina', 9, 'Il gelido tocco della tomba', 'Mentre ti riprendi dopo lo scontro, lo spettro si scioglie come neve al sole.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (320, 'Piccolo Drago della Foresta', 9, 'Mascelle Scattanti', 'Drago... Giusto. Verde... Giusto.  Grosso... Uh... No. Peccato.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (321, 'Branco di Lemuri Carnivori', 9, 'Strilli e Ululati', 'Ma che diavolo è un lemure?!?', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (322, 'Orso delle Cripte Albino', 9, 'Puzza ed Artigli', 'Uccidi l\'orso e ti domandi se ce ne sono altri nei dintorni.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (323, 'Cavaliere Nero', 9, 'Spada d\'Ebano', 'Morendo ti maledice e ti dice che non troverai mai il drago.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (324, 'Spia di Elmearian', 9, 'Piccolo Pugnale', 'Ingoia le sue informazioni prima di morire.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (325, 'Ombra Cacciatrice', 9, 'Dita dell\'Oscurità', 'Rispedisci la creatura da dove è venuta', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (326, 'Arcimago Negromante', 9, 'Urla di Migliaia di Anime', 'Col suo ultimo respiro esplode in una colonna fi fuoco nero!', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (327, 'Golem di Ferro', 9, 'Pugni Arrugginiti', 'Il golem si immobilizza e lo spingi in terra.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (328, 'Segugio Infernale', 9, 'Soffio Feroce', 'Esplode in una fiammata e viene risucchiato dal terreno.', NULL, 336, 101, 94, 17, 13, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (329, 'L\'Ape Maia', 7, 'VolaVolaVola', 'Ma non dovrebbe avere un centinaio d\'anni ormai?', NULL, 268, 77, 74, 13, 10, NULL, 'cmt', 0);
INSERT INTO creatures VALUES (330, 'Avvocato', 8, 'Incartamenti Giudiziari', 'Ecco un avvocato che non ti preoccuperà più!', NULL, 302, 89, 84, 15, 11, NULL, 'cmt', 1);
INSERT INTO creatures VALUES (331, 'Diablo', 16, 'Fiamme Infernali', 'Volevo cuocerti a fuoco lento ma sono rimasto scottato', NULL, 563, 207, 166, 31, 22, NULL, 'Excal', 0);
INSERT INTO creatures VALUES (332, 'Sciame di Vespe', 13, 'Pungiglioni avvelenati', 'I nostri pungiglioni si sono spuntati', NULL, 467, 156, 135, 25, 18, NULL, 'Excal', 0);
# --------------------------------------------------------

#
# Struttura della tabella `logdnet`
#

CREATE TABLE logdnet (
  serverid int(11) unsigned NOT NULL auto_increment,
  address varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  priority double NOT NULL default '100',
  lastupdate datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (serverid)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `logdnet`
#

INSERT INTO logdnet VALUES (1, '', '', '100', '2003-11-15 20:40:40');
# --------------------------------------------------------

#
# Struttura della tabella `masters`
#

CREATE TABLE masters (
  creatureid int(11) unsigned NOT NULL auto_increment,
  creaturename varchar(50) default NULL,
  creaturelevel int(11) default NULL,
  creatureweapon varchar(50) default NULL,
  creaturelose varchar(120) default NULL,
  creaturewin varchar(120) default NULL,
  creaturegold int(11) default NULL,
  creatureexp int(11) default NULL,
  creaturehealth int(11) default NULL,
  creatureattack int(11) default NULL,
  creaturedefense int(11) default NULL,
  PRIMARY KEY  (creatureid)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `masters`
#

INSERT INTO masters VALUES (1, 'Mireraband', 1, 'Piccolo Pugnale', 'Ben fatto %W, Avrei dovuto immaginarlo che fossi migliorato.', 'Come pensavo, %w, le tue capacità non sono nulla in confronto alle mie!', NULL, NULL, 11, 2, 2);
INSERT INTO masters VALUES (2, 'Fie', 2, 'Spada Corta', 'Ben fatto %W, sai davvero come usare il tuo %X.', 'Avresti dovuto sapere che non potevi vincere contro la mia %X', NULL, NULL, 22, 4, 4);
INSERT INTO masters VALUES (3, 'Glynyc', 3, 'Mazza Ferrata', 'Aah, Sconfitto da uno come te! Se continua così, perfino Mireraband mi verrà contro!', 'Haha, forse dovresti tornare nella classe di Mireraband.', NULL, NULL, 33, 6, 6);
INSERT INTO masters VALUES (4, 'Guth', 4, 'Bastone Ferrato', 'Ha!  Hahaha, battaglia eccellente %W!  Non ne vedo una così da quando ero nella RAF!', 'Ai tempi della RAF, lo avremmo mangiato vivo uno come te! Vai ad allenarti ragazzino!', NULL, NULL, 44, 8, 8);
INSERT INTO masters VALUES (5, 'Unélith', 5, 'Controllo Mentale', 'La tua mente supera la mia. Ammetto la sconfitta.', 'I tuoi poteri mentali sono scarsi. Medita su questo fallimento e forse un giorno mi sconfiggerai.', NULL, NULL, 55, 10, 10);
INSERT INTO masters VALUES (6, 'Adwares', 6, 'Ascia da Battaglia Nanica', 'Ach!  Hai talento con quel %Xl!', 'Har!  Devi fare pratica ragazzo!', NULL, NULL, 66, 12, 12);
INSERT INTO masters VALUES (7, 'Gerrard', 7, 'Arco da Battaglia', 'Hmm, è possibile che io ti abbia sottovalutato.', 'Come pensavo.', NULL, NULL, 77, 14, 14);
INSERT INTO masters VALUES (8, 'Ceiloth', 8, 'Spadone Orchesco', 'Ben fatto %W, vedo grandi cose nel tuo futuro!', 'Stai diventando forte, ma non così forte.', NULL, NULL, 88, 16, 16);
INSERT INTO masters VALUES (9, 'Dwiredan', 9, 'Spade Gemelle', 'Forse avrei dovuto considerare il tuo %X...', 'Forse dovresti valutare meglio le mie spade gemelle prima di riprovare?', NULL, NULL, 99, 18, 18);
INSERT INTO masters VALUES (10, 'Sensei Noetha', 10, 'Arti Marziali', 'Il tuo stile era superiore, la tua forma migliore. Mi inchino davanti a te.', 'Impara ad adattare il tuo stile e prevarrai.', NULL, NULL, 110, 20, 20);
INSERT INTO masters VALUES (11, 'Celith', 11, 'Aureole da Lancio', 'Wow, come hai schivato tutte quelle aureole?', 'Occhio all´ultima aureola, sta tornando indietro!', NULL, NULL, 121, 22, 22);
INSERT INTO masters VALUES (12, 'Gadriel il Ranger Elfico', 12, 'Arco Lungo Elfico', 'Posso accettare che tu mi abbia sconfitto, poichè tutti gli elfi sono immotali e tu non lo sei, dunque alla fine la vitt', 'Non dimenticare che gli elfi sono immortali. I mortali non potranno mai sconfiggere il popolo fatato.', NULL, NULL, 132, 24, 24);
INSERT INTO masters VALUES (13, 'Adoawyr', 13, 'Spadone Gigante', 'Se avessi potuto sollevare quella spada, probabilmente avrei fatto meglio!', 'Haha, non sono nemmeno riuscito a sollevare la spada, e ho vinto lo stesso!', NULL, NULL, 143, 26, 26);
INSERT INTO masters VALUES (14, 'Yoresh', 14, 'Tocco di Morte', 'Bene, hai evitato il mio tocco. Onore a te!', 'Attento al mio tocco la prossima volta!', NULL, NULL, 154, 28, 28);
# --------------------------------------------------------

#
# Struttura della tabella `mounts`
#

CREATE TABLE mounts (
  mountid int(11) unsigned NOT NULL auto_increment,
  mountname varchar(50) NOT NULL default '',
  mountdesc tinytext,
  mountcategory varchar(50) NOT NULL default '',
  mountbuff text,
  mountcostgems int(11) unsigned NOT NULL default '0',
  mountcostgold int(11) unsigned NOT NULL default '0',
  mountactive int(11) unsigned NOT NULL default '1',
  mountforestfights int(11) NOT NULL default '0',
  tavern tinyint(4) unsigned NOT NULL default '0',
  newday tinytext NOT NULL,
  recharge text NOT NULL,
  partrecharge text NOT NULL,
  mine_canenter int(10) unsigned NOT NULL default '0',
  mine_candie int(10) unsigned NOT NULL default '0',
  mine_cansave int(10) unsigned NOT NULL default '0',
  mine_tethermsg text NOT NULL,
  mine_deathmsg text NOT NULL,
  mine_savemsg text NOT NULL,
  PRIMARY KEY  (mountid),
  KEY mountid (mountid)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `mounts`
#

INSERT INTO mounts VALUES (3, 'Stallone', 'Questa nobile bestia è forte e possente!', 'Cavalli', 'a:5:{s:4:"name";s:24:"`&Attacco dello Stallone";s:8:"roundmsg";s:32:"Il tuo stallone combatte con te!";s:6:"rounds";s:2:"60";s:6:"atkmod";s:3:"1.2";s:8:"activate";s:7:"offense";}', 16, 0, 1, 3, 1, 'Leghi il tuo {weapon} alle bisacce del tuo stallone e vai in cerca di avventure', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo stallone, decidi che è un ottimo momento per rilassarti e gli permetti di pascolare un po´ nel prato. Ti assopisci godendoti questa serenità.`0', '`&Smonti dal tuo stallone permettergli di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba a guardare le nuvole, mentre il tuo stallone trotterella nel sottobosco. Lo cerchi per qualche minuto prima di ritrovarlo con un energico ghigno equino sul muso.`0', 0, 0, 0, '', '', '');
INSERT INTO mounts VALUES (1, 'Pony', 'Questo docile animale è ancora giovane', 'Cavalli', 'a:5:{s:4:"name";s:18:"`&Attacco del Pony";s:8:"roundmsg";s:28:"Il tuo pony combatte con te!";s:6:"rounds";s:2:"20";s:6:"atkmod";s:3:"1.2";s:8:"activate";s:7:"offense";}', 6, 0, 1, 1, 0, 'Leghi il tuo {weapon} alle bisacce del tuo pony e vai in cerca di avventure', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo pony, decidi che è un ottimo momento per rilassarti e gli permetti di pascolare un po´ nel prato. Ti assopisci godendoti questa serenità.`0', '`&Smonti dal tuo pony permettergli di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba a guardare le nuvole, mentre il tuo pony trotterella nel sottobosco. Lo cerchi per qualche minuto prima di ritrovarlo con un energico ghigno equino sul muso.`0', 0, 0, 0, '', '', '');
INSERT INTO mounts VALUES (2, 'Puledro', 'Questa potente bestia è fieramente fedele.', 'Cavalli', 'a:5:{s:4:"name";s:21:"`&Attacco del Puledro";s:8:"roundmsg";s:31:"Il tuo puledro combatte con te!";s:6:"rounds";s:2:"40";s:6:"atkmod";s:3:"1.2";s:8:"activate";s:7:"offense";}', 10, 0, 1, 2, 1, 'Leghi il tuo {weapon} alle bisacce del tuo puledtro e vai in cerca di avventure', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo puledro, decidi che è un ottimo momento per rilassarti e gli permetti di pascolare un po´ nel prato. Ti assopisci godendoti questa serenità.`0', '`&Smonti dal tuo puledro permettergli di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba a guardare le nuvole, mentre il tuo puledro trotterella nel sottobosco. Lo cerchi per qualche minuto prima di ritrovarlo con un energico ghigno equino sul muso.`0', 0, 0, 0, '', '', '');
INSERT INTO mounts VALUES (4, 'Cane Lupo', 'Un fedele cane che ti sarà di grande aiuto', 'Cane', 'a:5:{s:4:"name";s:20:"`&Il cane ti difende";s:8:"roundmsg";s:29:"Il cane distrae il tuo nemico";s:6:"rounds";s:2:"80";s:12:"badguyatkmod";s:3:"0.9";s:8:"activate";s:7:"defense";}', 1, 500, 1, 0, 0, 'Il tuo Cane Lupo si siede al tuo fianco pronto a seguirti.', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo cane, decidi che è un ottimo momento per rilassarti e gli permetti di cacciarsi del cibo nel prato. Ti assopisci godendoti le sue tecniche di caccia.`0', '`&Permetti al tuo cane di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba a guardare le nuvole, mentre il tuo cane rincorre gli scoiattoli.`0', 0, 0, 0, '', '', '');
INSERT INTO mounts VALUES (5, 'Orso Bruno', 'Un grosso Orso con un aria pacifica, ma sai per certo che in battaglia deve essere terribile', 'Orso', 'a:6:{s:4:"name";s:22:"`&Attacco del tuo Orso";s:8:"roundmsg";s:20:"Il tuo orso combatte";s:6:"rounds";s:2:"75";s:6:"atkmod";s:4:"1.25";s:6:"defmod";s:4:"1.05";s:8:"activate";s:7:"offense";}', 20, 2500, 1, 4, 0, 'Vai a riprendere il tuo orso che avevi portato alla stalla la sera prima.', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo orso, decidi che è un ottimo momento per lasciarlo libero di cacciare un po´. `0', '`&Lasci il tuo orso in modo da  permettergli di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba a guardare le nuvole, mentre il tuo orso si rotola nel sottobosco. Lo cerchi per qualche minuto prima di ritrovarlo che pesca dei pesci in un fiume.`0', 0, 0, 0, '', '', '');
INSERT INTO mounts VALUES (6, 'Centauro', 'Creatura metà umana e metà cavallo', 'Miti', 'a:6:{s:4:"name";s:22:"`&Attacco del Centauro";s:8:"roundmsg";s:34:"Il centauro combatte al tuo fianco";s:6:"rounds";s:3:"100";s:6:"atkmod";s:4:"1.35";s:6:"defmod";s:4:"1.15";s:8:"activate";s:15:"offense,defense";}', 100, 100000, 1, 5, 0, 'Il centauro ti sveglia, pronto per nuove avventure.', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo centauro, decidi che è un ottimo momento per accamparvi e mangiare. `0', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo centauro, decidi che è un ottimo momento per accamparvi e mangiare.', 0, 0, 0, '', '', '');
INSERT INTO mounts VALUES (7, 'Pixie', 'Piccola fatina guaritrice', 'Miti', 'a:6:{s:4:"name";s:18:"`&Pixie guaritrice";s:8:"roundmsg";s:16:"La pixie ti cura";s:6:"rounds";s:2:"75";s:5:"regen";s:1:"3";s:11:"minioncount";s:1:"1";s:8:"activate";s:15:"offense,defense";}', 50, 20000, 1, 6, 0, 'La Pixie ti fa sentire forte e vigoroso.', 'Lasci la Pixie in un fiore per farla sfamare con del buon nettare.', 'Lasci la Pixie in un fiore per farla sfamare con del buon nettare', 100, 0, 80, '', '', '');
INSERT INTO mounts VALUES (8, 'Grizzly', 'Un gigantesco Grizzly, con zanne e artigli affilatissimi. Non vorresti trovarlo di fronte a te come nemico in battaglia', 'Orso', 'a:6:{s:4:"name";s:31:"`&Le artigliate del tuo Grizzly";s:8:"roundmsg";s:23:"Il tuo Grizzly combatte";s:6:"rounds";s:2:"80";s:6:"atkmod";s:4:"1.28";s:6:"defmod";s:4:"1.07";s:8:"activate";s:7:"offense";}', 25, 5000, 1, 4, 1, 'Vai a riprendere il tuo Grizzly che avevi lasciato legato ad un albero ai margini della foresta.', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo Grizzly, decidi che è un ottimo momento per lasciarlo libero di cacciare un po´. `0', '`&Lasci il tuo Grizzly in modo da  permettergli di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba osservando le nuvole, mentre il tuo Grizzly si rotola nel sottobosco. Lo cerchi per qualche minuto prima di ritrovarlo che pesca dei pesci in un fiume.`0', 15, 5, 50, '', '', '');
INSERT INTO mounts VALUES (9, 'Pastore Maremmano', 'Un cane fedele che ti accompagnera\' nelle tue esplorazioni in miniera', 'Cane', 'a:7:{s:4:"name";s:20:"`&Il cane ti difende";s:8:"roundmsg";s:40:"Il cane abbaia, distraendo il tuo nemico";s:7:"wearoff";s:24:"Il cane stanco si ritira";s:6:"rounds";s:1:"5";s:12:"badguyatkmod";s:1:"1";s:12:"badguydefmod";s:1:"1";s:8:"activate";s:7:"defense";}', 2, 3000, 0, 0, 0, 'Il tuo Pastore Maremmano si accuccia di fianco a te, in attesa di un tuo gesto.', '`&Ricordandoti che è passato molto tempo dall´ultima volta che hai nutrito il tuo cane, decidi che è un ottimo momento per rilassarti e gli permetti di cacciarsi del cibo nel sottobosco. Ti assopisci godendoti le sue tecniche venatorie.`0', '`&Permetti al tuo cane di riposare per un momento anche se è stato nutrito di recente. Ti sdrai sull\'erba osservando il cielo, mentre il tuo cane rincorre gli uccellini.`0', 100, 100, 0, '`6Mine tether message 1`0', '`4Mine death message 2`0', '`2Mine tether message 3`0');
# --------------------------------------------------------

#
# Struttura della tabella `nastywords`
#

CREATE TABLE nastywords (
  words longtext
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `nastywords`
#

INSERT INTO nastywords VALUES ('*dyke *fuck* *nigger* *phuck* *shit* affanculo andskota arschloch arse* ass asshole atouche ayir bastard bitch* boiolas bollock* buceta butt* butt-pirate cabron cawk cazzo* cazzuto chink chraa chuj cipa clit cock* coglion* culo cum cunt* dago daygo dego dick* dildo dio dirsa dupa dziwka ejackulate ejaculate ekrem* ekto enculer faen fag* fanculo fanny fatass fcuk feces feg felcher ficken figa flikker foreskin fuk* fut futkretzn fuxor gay gook guiena hell helvete hoer* honkey honky hor hore huevon hui injun jism kanker* kawk kike klootzak knulle kraut kuk kuksuger kurac kurwa kusi* kyrpä* leitch lesbian lesbo mamhoon masturbat* merd* merde mibun minchia monkleigh mouliewop muie mulkku muschi nazis nepesaurio nigga* nigger nutsack orospu paska* pd pendejo penis perse phuck phuck picka pierdol* pillu* pimmel pimpis pirla piss pizda pm poontsee poop porn preteen preud prick pron pula pule pusse pussy puta puto puttana qahbeh queef* queer* qweef rautenberg schaffer scheiss* scheisse schlampe schmuck screw scrotum sharmuta sharmute shemale shipal shiz shpincter skribz skurwysyn slut smut sphencter spic spierdalaj splooge stronzo suka teets teez testicle tits titties titty troia twat twaty vaffanculo vittu votze wank* wetback* whoar whore wichser woose wop yed zabourah');
# --------------------------------------------------------

#
# Struttura della tabella `riddles`
#

CREATE TABLE riddles (
  id int(11) NOT NULL auto_increment,
  riddle mediumtext NOT NULL,
  answer tinytext NOT NULL,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `riddles`
#

INSERT INTO riddles VALUES (1, 'Mangi qualcosa che non pianti e non semini.`nÈ figlio dell´acqua ma se l´acqua lo tocca muore.', 'Sale');
INSERT INTO riddles VALUES (2, 'Maestro, apri il tuo libro.', 'Una farfalla');
INSERT INTO riddles VALUES (3, 'Le mie punte sono lunghe.`nLe mie punte sono corte.`nLe mie punte finiscono`nNon appena le vedi.', 'Fulmine; lampo');
INSERT INTO riddles VALUES (4, 'Mettici sulla schiena`nEd aprici la pancia`nSarai il più saggio degli uomini`nAnche partendo stupido.', 'Un libro');
INSERT INTO riddles VALUES (5, 'Seppelliscimi in profondità,`nSotto tumuli di pietre,`nEppure io`nScaverò fuori gli scheletri.', 'Ricordo');
INSERT INTO riddles VALUES (6, 'C´è una volta in ogni minuto`ndue in ogni momento`nE nessuna in un centinaio di anni.', 'La lettera \'M\'; M');
INSERT INTO riddles VALUES (7, 'Mai davanti, sempre alle spalle,`nEppure fuggo veloce;`nPer un bambino sono eterna,`nPer gli adulti sono sfuggita via troppo presto.', 'Infanzia');
INSERT INTO riddles VALUES (8, 'Due cavalli, i più veloci,`nSempre in coppia, e`nfissi verso luoghi`nDistanti da loro.', 'gli occhi; il sole e la luna');
INSERT INTO riddles VALUES (9, 'Si può dire:`nD´oro è buono;`nDi pietra è nulla;`nDi vetro è fragile;`nFreddo è crudele.`nSenza metafore, cosa sono?', 'Un cuore');
INSERT INTO riddles VALUES (10, 'È tonda, e piatta come una tavola`nAltare del Signore dei Lupi.`nGioiello sul nero velluto, perla nel mare`nMai modificata ma eternamente cangiante.', 'La Luna');
INSERT INTO riddles VALUES (11, 'Ha una testa d´oro ed una croce d´oro ma nessun collo.', 'Una moneta; Una moneta di oro');
INSERT INTO riddles VALUES (12, 'Dite amici ed entrate!', 'Amici');
INSERT INTO riddles VALUES (13, 'Un serpente di cuoio,`nDal morso pungente,`nResta sempre arrotolato,`nSe non devo lottare.', 'Una frusta');
INSERT INTO riddles VALUES (14, 'Cosa ha radici mai viste,`nÈ più alta degli alberi,`nVa sempre più in alto,`nEppure non cresce mai?', 'Una montagna');
INSERT INTO riddles VALUES (15, 'Trentasei cavalli bianchi su una collina rossa,`nPrima pestano,`nPoi schiacciano,`nE poi restano fermi.', 'I denti');
INSERT INTO riddles VALUES (16, 'Urla senza voce,`nVola senza ali,`nMorde senza denti,`nSospira senza bocca.', 'Il vento');
INSERT INTO riddles VALUES (17, 'Non si vede, e non si tocca,`nNon si sente, e non si annusa.`nSta dietro le stelle e sotto i colli,`nE riempie ogni vuoto.', 'L´oscurità');
INSERT INTO riddles VALUES (18, 'Uno scrigno senza chiave, coperchio o serratura,`nche racchiude l´oro.', 'uovo');
INSERT INTO riddles VALUES (19, 'Vive senza respirare,`nÈ freddo come la morte;`nBeve sempre ma non ha mai sete,`nVeste d´acciaio ma non fa rumore.', 'Un pesce');
INSERT INTO riddles VALUES (20, 'Costui divora ogni cosa:`nUccelli, animali, alberi, fiori;`nMastica il ferro, morder l´acciaio;`nRiduce in polvere le rocce;`nUccide i re e riduce le città in rovina,`nEd abbatte le alte montagne.', 'Tempo');
INSERT INTO riddles VALUES (21, 'Lo senti ma non lo vedi, e mai lo vedrai.', 'Il tuo cuore');
INSERT INTO riddles VALUES (22, 'Devi tenerla dopo averla data.', 'La tua parola');
INSERT INTO riddles VALUES (23, 'È leggero come una piuma ma non puoi trattenerlo per dieci minuti.', 'Il respiro');
INSERT INTO riddles VALUES (24, 'Ha una bocca ma non parla, ha un letto ma non dorme.', 'Un fiume');
INSERT INTO riddles VALUES (25, 'Più scorrevole di una rima, ama cadere ma non si può arrampicare!', 'Acqua');
INSERT INTO riddles VALUES (26, 'Se lo nomini lo rompi!', 'Silenzio');
INSERT INTO riddles VALUES (27, 'Passa davanti al sole e non getta ombra.', 'Aria');
INSERT INTO riddles VALUES (28, 'Se lo alimenti vive, se gli dai da bere muore.', 'Il fuoco');
INSERT INTO riddles VALUES (29, 'Un rosso tamburo che batte`nsenza essere toccato`nE diviene silente,`nSe lo si tocca.', 'Il cuore');
INSERT INTO riddles VALUES (30, 'Un raccolto seminato e raccolto nello stesso giorno`nIn un campo non arato,`nChe aumenta senza crescere,`nResta intero anche se mangiato`nÈ inutile eppure regge le nazioni.', 'Guerra');
INSERT INTO riddles VALUES (31, 'Se mi rompi`nnon smetto di esistere,`nSe mi tocchi`nMi puoi intrappolare,`nSe mi perdi`nNiente ha più importanza.', 'Speranza');
INSERT INTO riddles VALUES (32, 'È ovunque ma non si vede,`nPuò essere catturato ma non trattenuto`nNon ha voce ma si può sentire.', 'suono; rumore');
INSERT INTO riddles VALUES (33, 'Cammino in cerchio,`nMa vado sempre dritta`nNon mi lamento mai,`nDovunque venga portata.', 'ruota');
INSERT INTO riddles VALUES (34, 'Sono più leggero di ciò che mi compone,`nQuello che vedi di me è meno di quel che non vedi.', 'iceberg');
INSERT INTO riddles VALUES (35, 'Se un uomo portasse ciò che porto,`nSi spezzerebbe la schiena.`nNon sono ricca,`nMa lascio argento sul mio passaggio.', 'lumaca; chiocciola');
INSERT INTO riddles VALUES (36, 'La mia vita si misura in ore,`nIl mio scopo e farmi divorare.`nSe sono snella sono veloce`nSe sono grassa sono lenta`nIL vento è mio nemico.', 'candela');
INSERT INTO riddles VALUES (37, 'Pesi nella mia cinta,`nAlberi sulla mia schiena,`nChiodi nei miei fianchi,`nI miei piedi son mancanti.', 'barca');
INSERT INTO riddles VALUES (38, 'Altro non vedi`nQuando mi guardi in viso`nIo ti fisso negli occhi`nE non ti mento maie.', 'specchio');
INSERT INTO riddles VALUES (39, 'Ho sempre fame,`ne devo esser cibato,`nIl dito che lecco`nDiviene arrossato.', 'fuoco');
INSERT INTO riddles VALUES (40, 'Tre vite ho io.`nTanto gentile da accarezzare la pelle,`nTanto leggera da sfiorare il cielo`nTento dura da spaccare la roccia.', 'Acqua');
INSERT INTO riddles VALUES (41, 'Punte brillanti`nChe guardano in basso,`nLance scintillanti`nChe non arrugginiscono.', 'ghiaccioli');
INSERT INTO riddles VALUES (42, 'Ogni mattina appaio`nPer giacere ai tuoi piedi,`nTutto il giorno ti seguo`nNon importa quanto corri,`nEppure muoio`nNel sole di mezzogiorno.', 'ombra');
INSERT INTO riddles VALUES (43, 'Ho chiavi senza serratura`nEppure apro l´anima.', 'piano; arpa');
INSERT INTO riddles VALUES (44, 'Sono così semplice,`nChe posso solo indicare`nEppure guido gli uomini`nIn tutto il mondo.', 'bussola');
INSERT INTO riddles VALUES (45, 'Per la nostra ambrosia Giove ci ha donato,`nuna puntura mortale.`nAnche se alcuni ci credono inermi,`nAbbiamo soffocato il soffio del drago.`nChi siamo?', 'api');
INSERT INTO riddles VALUES (46, 'Colorata come le guance di una vergine,`nil tempo non c´era quando sono nata;`ndal giardino sono stta rubata,`nio da sola ho fatto cadere l´uomo.`nCosa sono?', 'una mela');
INSERT INTO riddles VALUES (47, 'Uno dove nessuno dovrebbe stare,`no dove dovrebbero essere due,`ncerco la purezza,`ntra gli alberi del re.`nCosa sono?', 'un unicorno');
INSERT INTO riddles VALUES (48, 'Un dente per mordere,`ndistrugge la foresta.`nUn dente per combattere,`ncome tutti sanno.`nCos´è?', 'un´ascia');
INSERT INTO riddles VALUES (49, 'La parte dell´uccello`nche non sta in cielo,`nche può nuotare nell´oceano`ne restare sempre asciutta.`nCos´è?', 'ombra; l\'ombra dell\'uccello');
INSERT INTO riddles VALUES (50, 'La radice è sopra il tronco`nin questa cosa al contrario,`nche cresce in inverno`ne muore in primavera.`nCos´è?', 'un ghiacciolo');
INSERT INTO riddles VALUES (51, 'Ne tocca uno, e ne lega due,`nun anello di catena`nche lega chi è leale,`n\'finché la morte non lo spezza.`nCos´è?', 'anello; anello nuziale');
INSERT INTO riddles VALUES (52, 'L´uomo saggio e sapiente ne è sicuro.`nAnche l´ignorante lo sa.`nIl ricco lo vuole.`nIl più grande degli eroi lo teme.`nEppure il peggiore dei codardi lo affronta senza timore.`nCos´è?', 'Nulla');
INSERT INTO riddles VALUES (53, 'Cosa è meglio di Dio,`nPeggio del Diavolo,`nI morti lo mangiano,`nma se lo mangi muori.', 'niente');
INSERT INTO riddles VALUES (54, 'Sono un grande aiuto per la donna,`nNon faccio male a nessuno tranne a chi mi uccide.`nSono radicata in un alto letto.`nLe donne coraggiose mi afferrano`nMi levano la pelle rossa e mi tengono stretta`nE mi staccano la testa`nLa donna che mi prende in fretta se ne pentirà`nEd avrà gli occhi umidi.', 'cipolla');
INSERT INTO riddles VALUES (55, 'Potere e tesoro di un principe,`nDura e dalle guance ripide, cinta di rosso`ned oro, strappata da una pianura`ndi fiori brillanti, ricordo del fuoco`ne del ferro, bellezza tagliente`nLa mia presa fa piancere i guerrieri,`nla mia puntura è una minaccia`nper la mano che prende l´oro.`nPer il mio signore ed i nemici sono sempre sicura e terribile`nE letale, mutando aspetti e forme.', 'una spada');
INSERT INTO riddles VALUES (56, 'Mentre andavo alle colonne,`nincontrai un uomo e sette donne;`nOgni donna aveva un sacco,`nIn ogni sacco sette gatti,`nOgni gatto aveva sette gattini:`nGattini, gatti, sacchi e donne,`nIn quanti andavano alle colonne?', 'uno');
INSERT INTO riddles VALUES (57, 'Il giorno si fa grigio,`nE io devo andar lontano.`nMa quando tornerò,`nIl giorno rivedremo.', 'il sole');
INSERT INTO riddles VALUES (58, 'Nell´oscuro e nel profondo,`nPosso essere trovato.`nMa se mi porti al sole`nAvrò tutto illuminato`nE se mi saprai tagliare`nSempre più potrò brillare', 'siamante');
INSERT INTO riddles VALUES (59, 'Cosa va in forno ma non lievita?`nCresce al calore ma odia la luce del sole?`nAffonda nell´acqua ma si solleva con l´aria?`nSembra pelle ma è sottile come un capello?', 'lievito');
INSERT INTO riddles VALUES (60, 'Il piccolo Johnny Walker,`nEra un chiacchierone!`nMa non diceva una parola!`nQuando lo mostravo,`ntutti indicavano e urlavano!`nE mi dicevano di metterlo via.', 'le tue opinioni');
INSERT INTO riddles VALUES (61, 'Sono tanti ed uno,`nSi muovono e si urtano,`nCoprono gli sguardi,`nE ti seguono dovunque.', 'le mani');
INSERT INTO riddles VALUES (62, 'Stomp, stomp,`nChomp, chomp,`nRomp, romp.`nStanno fermi,`nnei garmenti.', 'cavalli');
INSERT INTO riddles VALUES (63, 'Dolce dente,`ndolorante,`ngià finito,`nne vogliamo,`nancora un po´.', 'caramella');
INSERT INTO riddles VALUES (64, 'Entra su zampe feline,`nNon è aspra né dolce.`nFluttua nell´aria,`nE poi svanisce.', 'nebbia');
INSERT INTO riddles VALUES (65, 'Una risata,`nUn pianto,`nUn lamento,`nUn sospiro.', 'Emozioni');
INSERT INTO riddles VALUES (66, 'A cosa devi rispondere?`nMa per rispondere devi chiedere?`nE per chiedere devi parlare?`nE per parlare devi sapere la risposta?', 'un indovinello');
INSERT INTO riddles VALUES (67, 'Posso colpirti negli occhi,`nEppure brillare nel cielo,`nCrescendo quando muoio,`nCosa pensi che io sia?', 'una stella');
INSERT INTO riddles VALUES (68, 'Squish,`nSquash,`nLo levo se mi lavo,`nPosso averlo tra i capelli,`nE non li fa apparire belli.', 'fango');
INSERT INTO riddles VALUES (69, 'Sopra il colle,`nSotto il colle,`nPosso andare tutto il giorno,`nMa dopo tutto il mio cammino,`nNessun posto è come quella a cui torno.', 'la casa');
INSERT INTO riddles VALUES (70, 'È una cosa sorprendente.`nPuò essere acuta come un ago,`nO piatta come un foglio.`nEd in tutto questo,`nè del tutto naturale.', 'Musica; Note');
INSERT INTO riddles VALUES (71, 'Scendon sempre più in basso.`nAllargandosi ad ogni passo.`nDi aria non hanno mai bisogno.`nE a volte son sottili come tela di ragno.', 'Radici');
INSERT INTO riddles VALUES (72, 'Oh Signore! Io non son degno!`nPiego i miei arti verso il suolo.`nPiango senza emettere un suono.`nLasciami bere acque profonde.`nE in silenzio piangerò sulle loro sponde.', 'salice');
INSERT INTO riddles VALUES (73, 'Mi muovo, mi sposto e vado alla deriva.`nSotto di me dorme chi un tempo viveva.`nÈ incessante il mio turbinare`nMa dove son io acqua non puoi trovare.', 'il deserto');
INSERT INTO riddles VALUES (74, 'Borbotto e rido`nE ti getto acqua addosso.`nNon sono una signora`nE pizzi non indosso.', 'una fontana');
INSERT INTO riddles VALUES (75, 'Cosa ha ali,`nMa non può volare.`nÈ racchiuso,`nMa può essere all´aperto.`nSi può aprire,`nO chiudere.`nÈ il posto di re e regine,`nE di burle di ogni genere.`nSu che cosa dunque sto?', 'un palcoscenico');
INSERT INTO riddles VALUES (76, 'Non lamentartene,`nPerchè è il fato di ogni uomo.`nEppure è temuta,`nE sfuggita ovunque.`nCausa problemi e a volte vuoti,`nAbbatte il forte e indebolisce la memoria.`nQual è questo pericolo che tutti dobbiamo affrontare?', 'vecchiaia');
INSERT INTO riddles VALUES (77, 'Di queste cose, due ne ho.`nUna la tengo, l´altra te la do.`nE quando il prezzo mi chiederai,`nSolo un sorriso riceverai.', 'condividere');
INSERT INTO riddles VALUES (78, 'Sono una strana creatura,`nIn aria sto sospeso,`nMi muovo da un posto all´altro,`nVeloce come il vento.`nC´è chi dice che io canto,`nE chi che non ho voce.`nNel dubbio io solo mormoro.`nE volo via veloce.', 'colibrì');
INSERT INTO riddles VALUES (79, 'Dormo durante il giorno,`nE mi nascondo.`nSto attenta di notte,`nE giungo al mattino.`nMa solo per un istante,`nPoi mi nascondo,`ne dormo durante il giorno.', 'l\'alba');
INSERT INTO riddles VALUES (80, 'Sembra acqua,`nMa è calore.`nSiede sulla sabbia,`nGiace sul cemento.`nSi sa di gente,`nche l´ha seguito in ogni dove.`nMa non porta in nessun luogo,`ne l´hano solo potuto guardare.', 'un miraggio');
INSERT INTO riddles VALUES (81, 'Una parte del cielo,`nche tocca la terra.`nPer alcuni vale oro,`nper altri nulla.', 'un arcobaleno');
INSERT INTO riddles VALUES (82, 'I stand,`nAnd look across the sea,`nWith its waves, crests, troughs, and valleys.`nI stride,`nAcross this water, my horse following after,`nAnd while it laps against his withers,`nAnd brushes against my thighs,`nI fill the emptiness with laughter.`nAnd he - with his sighs.`nWhether do we go?`nOr do we go at all?`nOr are we simply out here wading,`nTo the next port of call.`nWhere the sea ends,`nWhere the loam lays firm beneath my feet,`nAnd I can mount my steed again,`nAnd continue til next we meet.`nWhat is really being talked about?', 'The open plains; plain');
INSERT INTO riddles VALUES (83, 'Sono nata cieca,`nE non ho potuto vedere,`nFino alle tre e un quarto.`nE non ho potuto sorridere,`nFino alle sei e mezza,`nE le mie braccia e le mie gambe`nSono fatte di bastoni.', 'una bambola');
INSERT INTO riddles VALUES (84, 'Ah!  Il mio respiro trema,`nI miei arti son sottili,`nIl mio ventre duole.`nCanuta è la mia testa,`nE le tracce che lascio,`nSicure non sono.`nGuardo da occhi umidi,`nE sembro dare il mio addio.`nL´oscurità mi si avvicina,`nE verso di lei mi inchino.', 'vecchiaia');
INSERT INTO riddles VALUES (85, 'Hick-a-more, Hack-a-more,`nSulla porta del re.`nTutti i cavalli,`nE tutti gli uomini del re,`nTogliere non potevano Hick-a-more, Hack-a-more,`nDalla porta del re.', 'la luce del sole');
INSERT INTO riddles VALUES (86, 'Mi chiesero che si poteva fare con me,`nE da me la gente fu nutrita.`nMi chiesero che si poteva fare con me,`nE da me le case vennero costruite.`nMi chiesero che si poteva fare con me,`nE su me cose vennero scritte.`nMi chiesero che si poteva fare con me,`nE da me il suolo venne concimato.`nMa quando mi chiesero che altro si poteva fare con me,`nNon trovarono più niente.', 'un albero');
INSERT INTO riddles VALUES (87, 'Puoi farci cose meravigliose.`nGuardare cose vicine e lontane,`nVedere cose grandi,`nO vedere cose piccole.`nO magari non vederne affatto.`nCe ne sono in tanti colori e sfumature,`nA volte verdi, a volte blu.', 'gli occhi');
INSERT INTO riddles VALUES (88, 'Oh quanto amo i miei piedi danzanti!`nStanno assieme così tanti.`nE se dritto vado avanti,`nLoro procedono scattanti.`nNe conto tanti, più di cento,`nE corro lungo il pavimento.', 'millepiedi');
INSERT INTO riddles VALUES (89, 'Un rombo udii dall´aia,`nE mi fermai a guardare.`nE cosa vidi?`nUna bestia massiccia e muscolosa.`nCon aculei sulla fronte spaziosa,`nMa che con la sua forza spropositata,`nNon poteva uscire da quella staccionata.', 'un toro');
INSERT INTO riddles VALUES (90, 'Twas the night of the day`nin which I must relay`nthat in which I took part in.`nFor the sun was out`nand without so much as a shout`nhe quietly went in.`nTwas ever so queer`nI thought he would leer`nbut never a word did I get in.`nFor without another word`n(at least that\'s what I heard)`nHe was back to the place he\'d been in.', 'An eclipse');
INSERT INTO riddles VALUES (91, 'Dall´alba al tramonto guardo il mare.`nDal tramonto all´alba guardo.`nMa se col sole in cielo posso solo ammiccare.`nSenza il sole in cielo va lontano il mio sguardo.', 'un faro');
INSERT INTO riddles VALUES (92, 'Tanti capelli e tante braccia ho`nPosso farti ombra e perfino l´aria ti do.', 'un albero');
INSERT INTO riddles VALUES (93, 'Era Dicembre o Giugno, non so,`nQuando la signora saltò.`nLe caddero i capelli,`nE perse gli occhiali.`nQuando lei urlò,`nIn modo quasi osceno.`nPuntandomi contro un dito,`nE urlando ”Eeeeee!  Eeeeee!”`nDevo dire che era troppo,`nPoichè nessuno l´aveva toccata.`nCosì decisi di andare via,`nE tornare quando se ne fosse andata.', 'un topo');
INSERT INTO riddles VALUES (94, 'Vado alla deriva,`nlenta come un fiume pigro.`nDanzo,`nsu un soffio di aria.`nFaccio le capriole,`nMeglio di un acrobata.', 'una foglia');
INSERT INTO riddles VALUES (95, 'Rosso di petto.`nSolo uno tra tanti.`nNato da un uovo.`nPortato a cantare.', 'Pettirosso');
INSERT INTO riddles VALUES (96, 'Quattro ne ho,`nCon le punte uguali.`nTante cose possono fare,`nE mai mi fanno del male.`nSe non le pungo con uno spillo,`nCos´è che posso agitare a piacimento?`nE usare stando fermo?', 'dita');
INSERT INTO riddles VALUES (97, 'Quando guardai le fiamme della sua passione,`nE la freddezza del suo tocco,`nCapii la tragedia che al loro unione avrebbe portato.`nE infatti quando si unirono,`nL´oscurità regnò nel mondo.', 'eclissi');
INSERT INTO riddles VALUES (98, 'Cosa ti abbraccia ma non per simpatia?`nChi ha un sorriso che non vuoi vedere?`nDa chi fuggono i coraggiosi?`nChe ha le dita artigliate?`nE il cui sonno dura per mesi', 'un orso');
INSERT INTO riddles VALUES (99, 'Puoi rotolartici,`nCoprirtici,`nBruciarlo,`nCoprirci il pavimento,`nGli animali lo mangiano,`nE assorbe tutto quello che ci versi sopra.', 'Fieno');
INSERT INTO riddles VALUES (100, 'Si trovano nel frutto della passione,`nE ancor di più nel melograno.`nSono allineati in una mela,`nMa altri frutti ne hanno più ancora.', 'Semi');
INSERT INTO riddles VALUES (101, '\'Twas whispered in Heaven, \'twas muttered in hell,`nAnd echo caught faintly the sound as it fell;`nOn the confines of earth \'twas permitted to rest,`nAnd in the depths of the ocean its presence confes\'d;`n\'Twill be found in the sphere when \'tis riven asunder,`nBe seen in the lightning and heard in the thunder;`n\'Twas allotted to man with his earliest breath,`nAttends him at birth and awaits him at death,`nPresides o\'er his happiness, honor and health,`nIs the prop of his house, and the end of his wealth.`nIn the heaps of the miser \'tis hoarded with care,`nBut is sure to be lost on his prodigal heir;`nIt begins every hope, every wish it must bound,`nWith the husbandman toils, and with monarchs is crowned;`nWithout it the soldier and seaman may roam,`nBut woe to the wretch who expels it from home!`nIn the whispers of conscience its voice will be found,`nNor e\'er in the whirlwind of passion be drowned;`n\'Twill soften the heart; but though deaf be the ear,`nIt will make him acutely and instantly hear.`nSet in shade, let it rest like a delicate flower;`nAh!  Breathe on it softly, it dies in an hour', 'The letter H; H');
INSERT INTO riddles VALUES (102, 'Siamo piccole creature d´aria,`nOgnuna d´aspetto e voce differente;`nUna di noi è nell´afa,`nUn´altra nel té,`nPuoi vedere la terza nei tini,`nE la quarta dentro il bosco;`nMa se la quinta vuoi trovare,`nDa dove sei tu non si può allontanare.', 'le vocali');
INSERT INTO riddles VALUES (103, 'Sono una strana contraddizione; Sono nuovo e sono vecchio,`nspesso evsto di stracci e spesso d´oro.`nNon so leggere ma sono letterato;`nSono cieco ma faccio luce; sono sciolto ma legato,`nSono sempre in nero, e sempre in bianco;`nSono serio e sono allegro, sono leggero e sono pesante`nNon ho carne né ossa, ma sono coperto di pelle;`nHo più punti della bussola;`ncanto senza voce, confuto senza parlare.`nSono inglese, sono tedesco, sono francese, ed italiano;`nAlcuni mi amano troppo, altri troppo mi evitano;`nSpesso muoio presto, ma talvolta vivo per secoli.', 'un libro');
INSERT INTO riddles VALUES (104, 'Mentre giravo per il giardino,`nHo inconctrato testa rossa!`nCon un bastone in mano ed una pietra in gola.', 'ciliegia');
INSERT INTO riddles VALUES (105, 'La piccola Nancy Etticote,`nCol vestitino bianco,`nCon il naso rosso;`nPiù tempo sta in piedi`nPiù diventa corta.', 'una candela');
INSERT INTO riddles VALUES (106, 'Ho una sorellina;`nChe sta più su della montagna;`nE ha un solo occhio poverina;`nMa se vado per mare mi accompagna.', 'una stella');
INSERT INTO riddles VALUES (107, 'Li ho visti marciare,`nMarciare sopra il mare.`nE mentre li guardavo,`nCosa fossero mi chiedevo`nPerchè vedevo un cavallo,`nEd una mucca,`nEd una nave con i marinai,`nEd alberi e case, ma cosa erano mai?', 'nuvole');
INSERT INTO riddles VALUES (108, 'Sono su.`nSono giù.`nSono tutto intorno.`nMa non mi puoi trovare.`nChi sono?', 'il vento');
INSERT INTO riddles VALUES (109, 'Posso essere spostata.`nPosso essere rotolata.`nMa non posso reggere nulla.`nSono rossa e sono blu.`nE posso avere altri colori.`nNon ho una testa ma le assomiglio,`nNon ho occhi ma vado dappertutto.`nCosa sono?', 'una palla');
INSERT INTO riddles VALUES (110, 'Su di me tu puoi inciampare,`nTi porto in altri luoghi,`nSono alta e sono bassa.`nDiverto i bambini`nMa gli adulti non mi pensano molto.`nCosa sono?', 'scala');
INSERT INTO riddles VALUES (111, 'Cos´è che fa crescere le cose?`nSpiana le montagne?`nAsciuga i laghi?`nEd è come poche altre cose,`nperchè p eterno?', 'tempo');
INSERT INTO riddles VALUES (112, 'Sedeva sopra un salice,`nE cantava per me.`nLenendo il mio dolore.', 'un uccello');
INSERT INTO riddles VALUES (113, 'Puoi nutrirlo ma solo ai danni di qualcuno,`nPuoi portarlo ma non tra le braccia,`nPuoi seppellirlo ma non nella terra.', 'odio');
INSERT INTO riddles VALUES (114, 'Profondo come una ciotola, tondo come una coppa,`nMa un intero oceano non lo potrebbe riempire.', 'colapasta; scolapasta');
INSERT INTO riddles VALUES (115, 'Per gli uomini del deserto un dio sono stato,`nDa quelli di oggi matto vengo chiamato,`nperchè agito la coda quando sono affamato,`nE ruggisco quando mi son saziato.', 'gatto');
INSERT INTO riddles VALUES (116, 'Ho sentito di un esercito invasore`nche ha spazzato la campagna;`nvincendo ogni resistenza e conquistando tutto.`nPortava con sè il buio, oscurando la luce.`nGli uomini si sono rinchiusi in casa, mentre fuori`nle lance pungevano, intaccando i muri di pietra.`nInnumerevoli soldati sono caduti al suolo,`nma ognuno dava la vita cadendo;`ne quando l´esercito si è spostato a nord,`nha lasciato la terra verde e rinfrescata.', 'un temporale, un acquazzone');
INSERT INTO riddles VALUES (117, 'Posso trovare una cosa che non posso vedere e vedere una cosa che non posso trovare.`nLa prima è il tempo, la seconda un punto davanti ai miei occhi.', 'tempo');
INSERT INTO riddles VALUES (118, 'Posso sentire una cosa che non posso toccare, e toccare una cosa che non posso sentire.`nLa prima è triste e desolata, la seconda è il tuo cuore.', 'il tuo cuore');
INSERT INTO riddles VALUES (119, 'Mai davanti, sempre dietro,`nEppure volo veloce,`nPer un bambino sono eterna,`nPer una dulto sono passata troppo in fretta.', 'giovinezza');
INSERT INTO riddles VALUES (120, 'E alto ed è tondo come una scodella,`nMa tutti i cavalli del re`nNon lo possono sollevare.', 'un pozzo');
INSERT INTO riddles VALUES (121, 'Più ce n´è,`nMeno vedi.', 'buio; oscurità');
INSERT INTO riddles VALUES (122, 'Cosa non è abbastanza per me,`nVa bene per due,`nMa è troppo per tre?', 'un segreto');
INSERT INTO riddles VALUES (123, 'Cos´è che più asciuga più diventa umido?', 'asciugamani; strofinaccio');
INSERT INTO riddles VALUES (124, 'Un lungo serpente`nDal morso tagliente,`nResta arrotolato`nSe non devo combattere.', 'frusta');
INSERT INTO riddles VALUES (125, 'Un guerriero tra i fiori,`nImpugna una spada.`nChe è pronto ad usare,`nPer difendere il suo oro.', 'ape');
INSERT INTO riddles VALUES (126, 'Il portatore di pesi, il guerriero,`nLo spaventato, il coraggioso,`nLa flotta a piedi dalle scarpe di ferro`nIl fedele, lo schiavo', 'cavallo');
INSERT INTO riddles VALUES (127, 'Cammina nel vento`nCorre nella pioggia`nAsciuga gli oceani nel Sole`nConta il tempo, ferma gli orologi`nIngoia i regni, mastica la roccia.', 'tempo');
INSERT INTO riddles VALUES (128, 'Colline rotolanti, cuore che batte in eterno,`nPaese che non cambia mai, e non sta mai fermo`nSeminato da chi viaggia lontano, mai coltivato,`nBianco di rabbia, verde di pace, e sempre blu.', 'mare; oceano');
INSERT INTO riddles VALUES (129, 'Ascolta attentamente, sono difficile da capire`nCome la sabbia sono sfuggente.`nE se mi aferri dovrai sapermi dire`nquello che ho dimenticato.', 'un indovinello');
INSERT INTO riddles VALUES (130, 'Cosa passa per la porta senza aprirla?`nTocca la stufa senza bruciarsi?`nSiede sulla tavola e non se ne vergogna?', 'Il sole');
INSERT INTO riddles VALUES (131, 'Mentre ero seduto ad aspettare`nLa morta che i vivi porta vidi passare`nCosa vidi?', 'una nave');
INSERT INTO riddles VALUES (132, 'Conosco una parola di due lettere, lo sai,`nAggiungine due e meno ne avrai.', 'no');
INSERT INTO riddles VALUES (133, 'Chi la fa non ne ha bisogno.`nChi al compra non la usa.`nChi la usa non la vede.', 'una bara');
INSERT INTO riddles VALUES (134, 'L´uomo che l´ha fatta non ne aveva bisogno.`nL´uomo che l´ha comprata non l´ha usata.`nL´uomo che l´ha usata non la voleva.', 'una bara');
INSERT INTO riddles VALUES (135, 'Mi hai afferrato ma sono sfuggita`nMi hai visto sfuggire e non hai motuto trattenermi`nMi stirngi nella mano e te la ritrovi vuota.`nCosa sono?', 'neve');
INSERT INTO riddles VALUES (136, 'Cosa ha quattro gambe al mattino,`nDue al pomeriggio,`nE tre la sera?', 'uomo');
INSERT INTO riddles VALUES (137, 'Cosa è sordo, cieco e stupido`ne dice sempre la verità?', 'uno specchio');
INSERT INTO riddles VALUES (138, 'Cos´è che hai sempre davanti`nma non puoi vedere?', 'il futuro');
INSERT INTO riddles VALUES (139, 'Gli uomini lo amano più della vita,`ne lo odiano più della morte;`nI poveri lo possiedono, i ricchi ne hanno bisogno;`nI miseri lo spendono, gli spendaccioni lo risparmiano,`ne tutti gli uomini se lo portano nella tomba.', 'niente');
INSERT INTO riddles VALUES (140, 'Vive più a lungo di qualsiasi uomo,`nmuore ogni anno e ogni anno rinasce.', 'un albero');
INSERT INTO riddles VALUES (141, 'Negli occhi acceca,`nnelle narici fa starnutire;`nAppure alcuni lo aspirano,`ne ne sono contenti.', 'fumo');
INSERT INTO riddles VALUES (142, 'Sola sta, senza scheletro o forma.`nNon fa mai alcun torto,`nma può fare del male.`nPuò essere malleabile,`nma è sempre retta come una freccia.', 'La verità');
INSERT INTO riddles VALUES (143, 'Cosa impiega la sfinge,`nche diverte il giocatore?', 'un indovinello');
INSERT INTO riddles VALUES (144, 'C´è qualcuno a cui sono sempre accanto`nMa che scompare al buio.`nÈ il solo a cui sono leale,`nAnche se al suo risveglio devo faticare.`nNon mi sente anche se sempre ci tocchiamo;`nSe io mi perdessi non perderebbe molto.`nEd ora la mia sorpresa,`nLui sei tu: chi sono io?', 'ombra');
INSERT INTO riddles VALUES (145, 'Spesso trattenuta, raramente toccata;`nSempre umida, mai arrugginita;`nA volte agitata e a volte morsa;`nPer usarmi bene, devi essere saggio.', 'lingua');
INSERT INTO riddles VALUES (146, 'Seduta alla finestra lei piangeva.`nE con ogni lacrima un p´ di vita perdeva.', 'una candela');
INSERT INTO riddles VALUES (147, 'Non sono che buchi legati ad altri buchi;`nSono forte come acciaio ma non rigida come un palo.', 'una catena');
INSERT INTO riddles VALUES (148, 'Ho poca forza, ma grande potere;`nGuardo piccoli tesori e grandi torri.`nMa se il mio padrone si allontana,`nDeve tenermi al sicuro.', 'una chiave');
INSERT INTO riddles VALUES (149, 'Portato da un alito sottile,`nanche gli eroi posso far impazzire.', 'un indovinello');
INSERT INTO riddles VALUES (150, 'Di giorno sto nascosta negli angoli,`nDi notte mi spando come la nebbia.`nStriscio nei forzieri chiusi`nE nei tuoi pugni stretti.`nMi vedi meglio quando non vedi,`nPerché io non esisto.', 'buio; oscurità');
INSERT INTO riddles VALUES (151, 'Diavoli e ladri non conoscono altro,`ntranne la luce delle stelle.', 'buio');
INSERT INTO riddles VALUES (152, 'Ce l´hanno i re, le piante e gli animali,`nPerfino i sassi, ma non sono tutti uguali.', 'regno');
INSERT INTO riddles VALUES (153, 'La mia fonte in cima al monte.', 'noce di cocco');
INSERT INTO riddles VALUES (154, 'Tre mura e poi raggiungi l´acqua.', 'noce di cocco');
INSERT INTO riddles VALUES (155, 'Il mio ceppo di velluto`nche si muove sempre senza riposo.', 'il mare');
INSERT INTO riddles VALUES (156, 'Quattro gambe al mattino,`ndue gambe a mezzogiorno,`ntre gambe la sera.', 'l\'uomo');
INSERT INTO riddles VALUES (157, 'Il mio uomo che non si può ferire.', 'l\'ombra');
INSERT INTO riddles VALUES (158, 'Le mie canoe che vanno notte e giorno,`ndieci bompressi, due poppe.', 'i piedi');
INSERT INTO riddles VALUES (159, 'Rossa caverna, `ncon bianchi soldati in fila.', 'la bocca');
INSERT INTO riddles VALUES (160, 'Il mio uomo che piange notte e giorno,`ntutti i giorni di ogni anno.', 'il mare');
# --------------------------------------------------------

#
# Struttura della tabella `settings`
#

CREATE TABLE settings (
  setting varchar(20) NOT NULL default '',
  value varchar(255) NOT NULL default '',
  PRIMARY KEY  (setting)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `settings`
#

INSERT INTO settings VALUES ('defaultlanguage', 'it');
INSERT INTO settings VALUES ('LOGINTIMEOUT', '900');
INSERT INTO settings VALUES ('expireoldacct', '45');
INSERT INTO settings VALUES ('expirenewacct', '20');
INSERT INTO settings VALUES ('expiretrashacct', '2');
INSERT INTO settings VALUES ('daysperday', '4');
INSERT INTO settings VALUES ('loginbanner', '*BETA*  `6Javella e Jabba `3Reincarnatevi e acquistate una casa.`n`4Segnalate eventuali problemi agli Admin`n`2Ultimo aggiornamento 25-01-04 `n`3Per qualsiasi problema consultate il forum di OGSI');
INSERT INTO settings VALUES ('beta', '0');
INSERT INTO settings VALUES ('expirecontent', '180');
INSERT INTO settings VALUES ('turns', '10');
INSERT INTO settings VALUES ('maxinterest', '10');
INSERT INTO settings VALUES ('mininterest', '1');
INSERT INTO settings VALUES ('pvpday', '2');
INSERT INTO settings VALUES ('fightsforinterest', '4');
INSERT INTO settings VALUES ('specialtybonus', '1');
INSERT INTO settings VALUES ('gravefightsperday', '10');
INSERT INTO settings VALUES ('automaster', '1');
INSERT INTO settings VALUES ('pvp', '1');
INSERT INTO settings VALUES ('gameadminemail', 'luke@ogsi.it');
INSERT INTO settings VALUES ('soap', '1');
INSERT INTO settings VALUES ('superuser', '0');
INSERT INTO settings VALUES ('requireemail', '1');
INSERT INTO settings VALUES ('requirevalidemail', '0');
INSERT INTO settings VALUES ('blockdupeemail', '0');
INSERT INTO settings VALUES ('dropmingold', '1');
INSERT INTO settings VALUES ('allowgoldtransfer', '0');
INSERT INTO settings VALUES ('gameoffsetseconds', '900');
INSERT INTO settings VALUES ('logdnet', '1');
INSERT INTO settings VALUES ('newplayerstartgold', '50');
INSERT INTO settings VALUES ('lowslumlevel', '4');
INSERT INTO settings VALUES ('pvptimeout', '600');
INSERT INTO settings VALUES ('borrowperlevel', '20');
INSERT INTO settings VALUES ('oldmail', '14');
INSERT INTO settings VALUES ('inboxlimit', '50');
INSERT INTO settings VALUES ('logdnetserver', 'http://lotgd.net/');
INSERT INTO settings VALUES ('maxcolors', '10');
INSERT INTO settings VALUES ('serverurl', 'http://www.ogsi.it/logd/');
INSERT INTO settings VALUES ('serverdesc', '`@Legend of the `7Green Dragon `$ITALIANO');
INSERT INTO settings VALUES ('mailsizelimit', '1024');
INSERT INTO settings VALUES ('multimaster', '1');
INSERT INTO settings VALUES ('lastdboptimize', '2004-02-14 11:32:21');
INSERT INTO settings VALUES ('creaturebalance', '0.33');
INSERT INTO settings VALUES ('spaceinname', '0');
INSERT INTO settings VALUES ('selfdelete', '0');
INSERT INTO settings VALUES ('pvpimmunity', '5');
INSERT INTO settings VALUES ('pvpminexp', '1500');
INSERT INTO settings VALUES ('maxbounties', '5');
INSERT INTO settings VALUES ('bountyfee', '10');
INSERT INTO settings VALUES ('bountymin', '50');
INSERT INTO settings VALUES ('bountymax', '400');
INSERT INTO settings VALUES ('bountylevel', '3');
INSERT INTO settings VALUES ('innfee', '5%');
INSERT INTO settings VALUES ('pvpattgain', '10');
INSERT INTO settings VALUES ('pvpdeflose', '5');
INSERT INTO settings VALUES ('pvpattlose', '15');
INSERT INTO settings VALUES ('pvpdefgain', '10');
INSERT INTO settings VALUES ('paypalemail', 'luke@dnet.it');
INSERT INTO settings VALUES ('hasegg', '50');
INSERT INTO settings VALUES ('selledgems', '25');
# --------------------------------------------------------

#
# Struttura della tabella `taunts`
#

CREATE TABLE taunts (
  tauntid int(11) unsigned NOT NULL auto_increment,
  taunt text,
  editor varchar(50) default NULL,
  PRIMARY KEY  (tauntid)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `taunts`
#

INSERT INTO taunts VALUES (1, '`5"`6Aspetta solo la mia vendetta, `4%W`6. Sarà fulminea!`5" %w dichiara.', 'Bluspring');
INSERT INTO taunts VALUES (2, '`5"`6Mi divertirò con questo nuovo `4%x`6 che aveva %w`6,`5" ha esclamato %W.', 'joe');
INSERT INTO taunts VALUES (3, '`5"`6Aah, così è a `bquesto`b che serve il `4%X`6!`5" ha esclamato %W', 'joe');
INSERT INTO taunts VALUES (4, '`5"`6Oh cielo! Non pensavo ce l´avessi, `5%W`6,`5" %w ha esclamato.', 'Bluspring');
INSERT INTO taunts VALUES (5, '`5Hanno sentito %W dire, "`6Il suo `4%x`6 non era niente in confronto al mio `4%X`6!`5"', 'cmt');
INSERT INTO taunts VALUES (6, '`5"`6Sai, non dovresti girare con un `4%x`6 se non sai come usarlo,`5" ha suggerito %W.', 'Bluspring');
INSERT INTO taunts VALUES (7, '`5"`6`bARRRGGGGGGGH`b!!`5" Urla %w frustrato.', 'Bluspring');
INSERT INTO taunts VALUES (8, '`5"`6Come ho potuto essere così debole?`5" Si lamenta %w.', 'Bluspring');
INSERT INTO taunts VALUES (9, '`5"`6Forse non sono robusto come pensavo...!`5" ammette %w.', 'Bluspring');
INSERT INTO taunts VALUES (10, '`5"`6Guardati le spalle, `4%W`6 Sto venendo per te!`5" avverte %w.', 'Bluspring');
INSERT INTO taunts VALUES (11, '`5"`6Questo fa schifo!`5" lamenta %w.', 'Bluspring');
INSERT INTO taunts VALUES (12, '`5"`6Ho visto Londra, ho visto la Francia, ho visto le mutande di `4%w\'s`6!`5" rivela %W.', 'Bluspring');
INSERT INTO taunts VALUES (13, '`5"`6Il guaritore non ti può aiutare adesso, `4%w`6!,`5" borbotta %W.', 'Bluspring');
INSERT INTO taunts VALUES (14, '`5%W sorride.  "`6Sei troppo lento. Sei troppo debole.`5"', 'Bluspring');
INSERT INTO taunts VALUES (15, '`5%w sbatte la testa contro una pietra..."`6Stupido, stupido, stupido!`5" lo hanno sentito dire.', 'cmt');
INSERT INTO taunts VALUES (16, '`5"`6Il mio ego non può reggere tanto!`5" esclama %w.', 'Bluspring');
INSERT INTO taunts VALUES (17, '`5"`6Perché non sono diventato un dottore come voleva mio padre?`5" si chiede %w ad alta voce.', 'Bluspring');
INSERT INTO taunts VALUES (18, '`5"`6Forse la prossima volta non farai tanto il galletto!`5" ride %W', 'Bluspring');
INSERT INTO taunts VALUES (19, '`5"`6Un bambino avrebbe usato meglio quel `4%x `6!`5" proclama %W.', 'Bluspring');
INSERT INTO taunts VALUES (20, '`5"`6Avresti dovuto restartene a letto.`5" suggerisce %W.', 'Bluspring');
INSERT INTO taunts VALUES (21, '`5"`6Non è un bel calcio nel basso ventre?!`5" osserva %w.', 'Bluspring');
INSERT INTO taunts VALUES (22, '`5"`6Riprovaci quando avrai imparato a combattere.`5" schernisce %W.', 'cmt');
INSERT INTO taunts VALUES (23, '`5"`6La prossima volta mangia tutti i tuoi spinaci.`5" suggerisce %W.', 'Bluspring');
INSERT INTO taunts VALUES (24, '`5 "`6Sei disonorevole, `4%W`6!`5" urla %w.', 'Bluspring');
INSERT INTO taunts VALUES (25, '`5"`4%w`6, la tua mancanza di stile è una disgrazia.`5" afferma %W. ', 'Bluspring');
INSERT INTO taunts VALUES (26, '`5"`6Sai, `4%w`6 doveva arrivarci dopo tutte le cose che ho detto su sua madre`5," ha commentato %W.', 'cmt');
INSERT INTO taunts VALUES (27, '`5"`6Un neonato avrebbe fatto un figura migliore!`5" dice %W sghignazzando.', 'Excal');
# --------------------------------------------------------

#
# Struttura della tabella `weapons`
#

CREATE TABLE weapons (
  weaponid int(11) unsigned NOT NULL auto_increment,
  weaponname varchar(128) default NULL,
  value int(11) NOT NULL default '0',
  damage int(11) NOT NULL default '1',
  level int(11) NOT NULL default '0',
  PRIMARY KEY  (weaponid)
) TYPE=MyISAM;

#
# Dump dei dati per la tabella `weapons`
#

INSERT INTO weapons VALUES (1, 'Rastrello', 48, 1, 0);
INSERT INTO weapons VALUES (2, 'Paletta', 225, 2, 0);
INSERT INTO weapons VALUES (3, 'Vanga', 585, 3, 0);
INSERT INTO weapons VALUES (4, 'Azza', 990, 4, 0);
INSERT INTO weapons VALUES (5, 'Zappa da Giardino', 1575, 5, 0);
INSERT INTO weapons VALUES (6, 'Torcia', 2250, 6, 0);
INSERT INTO weapons VALUES (7, 'Forcone', 2790, 7, 0);
INSERT INTO weapons VALUES (8, 'Pala', 3420, 8, 0);
INSERT INTO weapons VALUES (9, 'Cesoie', 4230, 9, 0);
INSERT INTO weapons VALUES (10, 'Accetta', 5040, 10, 0);
INSERT INTO weapons VALUES (11, 'Coltello da Intaglio', 5850, 11, 0);
INSERT INTO weapons VALUES (12, 'Ascia da Taglialegna di Ferro Arrugginito', 6840, 12, 0);
INSERT INTO weapons VALUES (13, 'Ascia da Taglialegna di Acciaio Ammaccato', 8010, 13, 0);
INSERT INTO weapons VALUES (14, 'Ascia da Taglialegna d´Acciaio Affilata', 9000, 14, 0);
INSERT INTO weapons VALUES (15, 'Ascia da Boscaiolo', 10350, 15, 0);
INSERT INTO weapons VALUES (16, 'Sassolini', 48, 1, 1);
INSERT INTO weapons VALUES (17, 'Pietre', 225, 2, 1);
INSERT INTO weapons VALUES (18, 'Rocce', 585, 3, 1);
INSERT INTO weapons VALUES (19, 'Piccolo Ramo', 990, 4, 1);
INSERT INTO weapons VALUES (20, 'Grosso Ramo', 1575, 5, 1);
INSERT INTO weapons VALUES (21, 'Asta Molto Imbottita', 2250, 6, 1);
INSERT INTO weapons VALUES (22, 'Asta Poco Imbottita', 2790, 7, 1);
INSERT INTO weapons VALUES (23, 'Asta di Legno', 3420, 8, 1);
INSERT INTO weapons VALUES (24, 'Spada di Legno da Allenamento', 4230, 9, 1);
INSERT INTO weapons VALUES (25, 'Spada Corta di Bronzo senza Filo', 5040, 10, 1);
INSERT INTO weapons VALUES (26, 'Spada Corta di Bronzo Ben Lavorata', 5850, 11, 1);
INSERT INTO weapons VALUES (27, 'Spada Corta d´Acciaio Arrugginita', 6840, 12, 1);
INSERT INTO weapons VALUES (28, 'Spada Corta d´Acciaio senza Filo', 8010, 13, 1);
INSERT INTO weapons VALUES (29, 'Spada Corta d´Acciaio Affilata', 9000, 14, 1);
INSERT INTO weapons VALUES (30, 'Spada Corta da Scudiero', 10350, 15, 1);
INSERT INTO weapons VALUES (31, 'Spada di Bronzo senza Filo', 48, 1, 2);
INSERT INTO weapons VALUES (32, 'Spada di Bronzo', 225, 2, 2);
INSERT INTO weapons VALUES (33, 'Spada di Bronzo Ben Lavorata', 585, 3, 2);
INSERT INTO weapons VALUES (34, 'Spada di Ferro senza Filo', 990, 4, 2);
INSERT INTO weapons VALUES (35, 'Spada di Ferro', 1575, 5, 2);
INSERT INTO weapons VALUES (36, 'Spada Incantata', 9000, 14, 2);
INSERT INTO weapons VALUES (37, 'Spada di Ferro Ben Lavorata', 2250, 6, 2);
INSERT INTO weapons VALUES (38, 'Spada d´Acciaio Arrugginita', 2790, 7, 2);
INSERT INTO weapons VALUES (39, 'Spada d´Acciaio senza Filo', 3420, 8, 2);
INSERT INTO weapons VALUES (40, 'Spada d´Acciaio Ben Lavorata', 4230, 9, 2);
INSERT INTO weapons VALUES (41, 'Spada d´Acciaio Intagliata', 5040, 10, 2);
INSERT INTO weapons VALUES (42, 'Spada d´Acciaio con Elsa Ingioiellata', 5850, 11, 2);
INSERT INTO weapons VALUES (43, 'Spada con Elsa Dorata', 6840, 12, 2);
INSERT INTO weapons VALUES (44, 'Spada con Elsa di Platino', 8010, 13, 2);
INSERT INTO weapons VALUES (45, 'Spada da Adepto', 10350, 15, 2);
INSERT INTO weapons VALUES (46, 'Spada Lunga d´Acciaio', 48, 1, 3);
INSERT INTO weapons VALUES (47, 'Spada Lunga d´Acciaio Inciso', 585, 3, 3);
INSERT INTO weapons VALUES (48, 'Spada Lunga d´Acciaio Temperato', 225, 2, 3);
INSERT INTO weapons VALUES (49, 'Spada Lunga d´Acciaio Ben Bilanciata', 990, 4, 3);
INSERT INTO weapons VALUES (50, 'Spada Lunga d´Acciaio Bilanciata Perfettamente', 1575, 5, 3);
INSERT INTO weapons VALUES (51, 'Spada Lunga d´Acciaio Intagliata', 2250, 6, 3);
INSERT INTO weapons VALUES (52, 'Spada Lunga con Elsa Argentata', 2790, 7, 3);
INSERT INTO weapons VALUES (53, 'Spada Lunga con Elsa Dorata', 3420, 8, 3);
INSERT INTO weapons VALUES (54, 'Spada Lunga con Elsa di Oro Massiccio', 4230, 9, 3);
INSERT INTO weapons VALUES (55, 'Spada Lunga con Elsa di Platino', 5040, 10, 3);
INSERT INTO weapons VALUES (56, 'Spada Lunga di Argento Lunare', 5850, 11, 3);
INSERT INTO weapons VALUES (57, 'Spada Lunga di Oro Autunnale', 6840, 12, 3);
INSERT INTO weapons VALUES (58, 'Spada Lunga di Argento Elfico', 8010, 13, 3);
INSERT INTO weapons VALUES (59, 'Spada Lunga Incantata', 9000, 14, 3);
INSERT INTO weapons VALUES (60, 'Spada Lunga del Signore dei Lupi', 10350, 15, 3);
INSERT INTO weapons VALUES (61, 'Spada Bastarda Sbilanciata', 48, 1, 4);
INSERT INTO weapons VALUES (62, 'Spada Bastarda Ossidata', 225, 2, 4);
INSERT INTO weapons VALUES (63, 'Spada Bastarda di Ferro', 585, 3, 4);
INSERT INTO weapons VALUES (64, 'Spada Bastarda d´Acciaio', 990, 4, 4);
INSERT INTO weapons VALUES (65, 'Spada Bastarda Ben Bilanciata', 1575, 5, 4);
INSERT INTO weapons VALUES (66, 'Spada Bastarda Perfettamente Bilanciata', 2250, 6, 4);
INSERT INTO weapons VALUES (67, 'Spada Bastarda con Rune Incise', 2790, 7, 4);
INSERT INTO weapons VALUES (68, 'Spada Bastarda Bordata di Bronzo', 3420, 8, 4);
INSERT INTO weapons VALUES (69, 'Spada Bastarda Bordata d´Argento', 4230, 9, 4);
INSERT INTO weapons VALUES (70, 'Spada Bastarda Bordata di Oro', 5040, 10, 4);
INSERT INTO weapons VALUES (71, 'Spada Bastarda di Argento Notturno', 5850, 11, 4);
INSERT INTO weapons VALUES (72, 'Spada Bastarda di Oro Mattutino', 6840, 12, 4);
INSERT INTO weapons VALUES (73, 'Spada Bastarda Risplendente', 8010, 13, 4);
INSERT INTO weapons VALUES (74, 'Spada Bastarda Elfica Incantata', 9000, 14, 4);
INSERT INTO weapons VALUES (75, 'Spada Bastarda da Nobile', 10350, 15, 4);
INSERT INTO weapons VALUES (76, 'Spadone di Ferro Ossidato', 48, 1, 5);
INSERT INTO weapons VALUES (77, 'Spadobe di Ferro Temperato', 225, 2, 5);
INSERT INTO weapons VALUES (78, 'Spadone d´Acciaio Arrugginito', 585, 3, 5);
INSERT INTO weapons VALUES (79, 'Spadone d´Acciaio', 990, 4, 5);
INSERT INTO weapons VALUES (80, 'Fine Spadone d´Acciaio', 1575, 5, 5);
INSERT INTO weapons VALUES (81, 'Spadone Scozzese', 2250, 6, 5);
INSERT INTO weapons VALUES (82, 'Spada da Guerra Vichinga', 2790, 7, 5);
INSERT INTO weapons VALUES (83, 'Spada da Barbaro', 3420, 8, 5);
INSERT INTO weapons VALUES (84, 'Spadone con Elsa Sozzese', 4230, 9, 5);
INSERT INTO weapons VALUES (85, 'Spada d´Acciaio di Agincourt', 5040, 10, 5);
INSERT INTO weapons VALUES (86, 'Spada da Battaglia Celtica', 5850, 11, 5);
INSERT INTO weapons VALUES (87, 'Spada Normanna', 6840, 12, 5);
INSERT INTO weapons VALUES (88, 'Spada da Cavaliere', 8010, 13, 5);
INSERT INTO weapons VALUES (89, 'Spadone con Leone Rampante', 9000, 14, 5);
INSERT INTO weapons VALUES (90, 'Spadone dei Soldati del Drago', 10350, 15, 5);
INSERT INTO weapons VALUES (91, 'Due Spade Corte Rotte', 48, 1, 6);
INSERT INTO weapons VALUES (92, 'Due Spade Corte', 225, 2, 6);
INSERT INTO weapons VALUES (93, 'Scimitarra di Ferro', 585, 3, 6);
INSERT INTO weapons VALUES (94, 'Scimitarra Bilanciata', 990, 4, 6);
INSERT INTO weapons VALUES (95, 'Scimitarra d´Acciaio Arrugginito', 1575, 5, 6);
INSERT INTO weapons VALUES (96, 'Scimitarra d´Acciaio Ossidato', 2250, 6, 6);
INSERT INTO weapons VALUES (97, 'Scimitarra d´Acciaio', 2790, 7, 6);
INSERT INTO weapons VALUES (98, 'Scimitarra con Elsa di Bronzo', 3420, 8, 6);
INSERT INTO weapons VALUES (99, 'Scimitarra con Elsa d´Oro', 4230, 9, 6);
INSERT INTO weapons VALUES (100, 'Scimitarra con Elsa di Platino', 5040, 10, 6);
INSERT INTO weapons VALUES (101, 'Scimitarra di Adamantio', 5850, 11, 6);
INSERT INTO weapons VALUES (102, 'Scimitarra di Adamantio Ben Lavorata', 6840, 12, 6);
INSERT INTO weapons VALUES (103, 'Scimitarra Incantata', 8010, 13, 6);
INSERT INTO weapons VALUES (104, 'Scimitarra dei Drow', 9000, 14, 6);
INSERT INTO weapons VALUES (105, 'Scimitarra dell´Unicorno', 10350, 15, 6);
INSERT INTO weapons VALUES (106, 'Ascia di Ferro Scheggiata', 48, 1, 7);
INSERT INTO weapons VALUES (107, 'Ascia di Ferro', 225, 2, 7);
INSERT INTO weapons VALUES (108, 'Ascia d´Acciaio Arrugginito', 585, 3, 7);
INSERT INTO weapons VALUES (109, 'Ascia d´Acciaio', 990, 4, 7);
INSERT INTO weapons VALUES (110, 'Ascia del Taglialegna', 1575, 5, 7);
INSERT INTO weapons VALUES (111, 'Ascia da Battaglia Scarsa', 2250, 6, 7);
INSERT INTO weapons VALUES (112, 'Ascia da Battaglia Media', 2790, 7, 7);
INSERT INTO weapons VALUES (113, 'Ottima Ascia da Battaglia', 3420, 8, 7);
INSERT INTO weapons VALUES (114, 'Ascia Bipenne', 4230, 9, 7);
INSERT INTO weapons VALUES (115, 'Ascia da Battaglia Bpienne', 5040, 10, 7);
INSERT INTO weapons VALUES (116, 'Ascia da Battaglia Dorata', 5850, 11, 7);
INSERT INTO weapons VALUES (117, 'Ascia da Battaglia di Platino', 6840, 12, 7);
INSERT INTO weapons VALUES (118, 'Ascia da Battaglia Incantata', 8010, 13, 7);
INSERT INTO weapons VALUES (119, 'Ascia da Battaglia dei Fabbri Nani', 9000, 14, 7);
INSERT INTO weapons VALUES (120, 'Ascia da Battaglia dei Guerrieri Nani', 10350, 15, 7);
INSERT INTO weapons VALUES (121, 'Mazza di Ferro Rotta', 48, 1, 8);
INSERT INTO weapons VALUES (122, 'Mazza di Ferro Ossidato', 225, 2, 8);
INSERT INTO weapons VALUES (123, 'Mazza di Ferro', 585, 3, 8);
INSERT INTO weapons VALUES (124, 'Mazza di Ferro Temperato', 990, 4, 8);
INSERT INTO weapons VALUES (125, 'Mazza d´Acciaio', 1575, 5, 8);
INSERT INTO weapons VALUES (126, 'Mazza d´Acciaio Temperato', 2250, 6, 8);
INSERT INTO weapons VALUES (127, 'Doppia Mazza Sbilanciata', 2790, 7, 8);
INSERT INTO weapons VALUES (128, 'Doppia Mazza Bilanciata', 3420, 8, 8);
INSERT INTO weapons VALUES (129, 'Mazza da Battaglia', 4230, 9, 8);
INSERT INTO weapons VALUES (130, 'Mazza da Battaglia del Capitano', 5040, 10, 8);
INSERT INTO weapons VALUES (131, 'Stella del Mattino del Capitano', 5850, 11, 8);
INSERT INTO weapons VALUES (132, 'Stella del Mattino Nanica', 6840, 12, 8);
INSERT INTO weapons VALUES (133, 'Stella del Mattino Nanica', 8010, 13, 8);
INSERT INTO weapons VALUES (134, 'Stella del Mattino dei Signori dei Nani', 9000, 14, 8);
INSERT INTO weapons VALUES (135, 'Stella del Mattino Incantata', 10350, 15, 8);
INSERT INTO weapons VALUES (136, 'Coltello', 48, 1, 9);
INSERT INTO weapons VALUES (137, 'Coltello da Lancio', 225, 2, 9);
INSERT INTO weapons VALUES (138, 'Blackjack', 585, 3, 9);
INSERT INTO weapons VALUES (139, 'Shaken', 990, 4, 9);
INSERT INTO weapons VALUES (140, 'Shuriken', 1575, 5, 9);
INSERT INTO weapons VALUES (141, 'Aculei da Lancio', 2250, 6, 9);
INSERT INTO weapons VALUES (142, 'Atlatl', 2790, 7, 9);
INSERT INTO weapons VALUES (143, 'Qilamitautit Bolo', 3420, 8, 9);
INSERT INTO weapons VALUES (144, 'Quoait da Guerra', 4230, 9, 9);
INSERT INTO weapons VALUES (145, 'Cha Kran', 5040, 10, 9);
INSERT INTO weapons VALUES (146, 'Fei Piau', 5850, 11, 9);
INSERT INTO weapons VALUES (147, 'Jen Piau', 6840, 12, 9);
INSERT INTO weapons VALUES (148, 'Gau dim Piau', 8010, 13, 9);
INSERT INTO weapons VALUES (149, 'Ascia da Lancio Incantata', 9000, 14, 9);
INSERT INTO weapons VALUES (150, 'Shuriken Ninja di Teksolo', 10350, 15, 9);
INSERT INTO weapons VALUES (151, 'Arco e Frecce di Legno del Fattore', 48, 1, 10);
INSERT INTO weapons VALUES (152, 'Arco e Frecce con Punta di Pietra del Fattore', 225, 2, 10);
INSERT INTO weapons VALUES (153, 'Arco e Frecce con Punta d´Acciaio del Fattore', 585, 3, 10);
INSERT INTO weapons VALUES (154, 'Arco e Frecce di Legno del Cacciatore', 990, 4, 10);
INSERT INTO weapons VALUES (155, 'Arco e Frecce con Punta di Pietra del Cacciatore', 1575, 5, 10);
INSERT INTO weapons VALUES (156, 'Arco e Frecce con Punta d´Acciaio del Cacciatore', 2250, 6, 10);
INSERT INTO weapons VALUES (157, 'Arco e Frecce di Legno del Ranger', 2790, 7, 10);
INSERT INTO weapons VALUES (158, 'Arco e Frecce con Punta di Pietra del Ranger', 3420, 8, 10);
INSERT INTO weapons VALUES (159, 'Arco e Frecce con Punta d´Acciaio del Ranger', 4230, 9, 10);
INSERT INTO weapons VALUES (160, 'Arco Lungo', 5040, 10, 10);
INSERT INTO weapons VALUES (161, 'Balestra', 5850, 11, 10);
INSERT INTO weapons VALUES (162, 'Arco Lungo Elfico', 6840, 12, 10);
INSERT INTO weapons VALUES (163, 'Arco Lungo Elfico con Frecce Infuocate', 8010, 13, 10);
INSERT INTO weapons VALUES (164, 'Arco Lungo Elfico con Frecce Incantate', 9000, 14, 10);
INSERT INTO weapons VALUES (165, 'Arco Lungo del Re degli Elfi', 10350, 15, 10);
INSERT INTO weapons VALUES (166, 'Spada Corta di MightyE', 225, 2, 11);
INSERT INTO weapons VALUES (167, 'Spada Lunga di MightyE', 48, 1, 11);
INSERT INTO weapons VALUES (168, 'Spada Bastarda di MightyE', 585, 3, 11);
INSERT INTO weapons VALUES (169, 'Scimitarra di MightyE', 990, 4, 11);
INSERT INTO weapons VALUES (170, 'Ascia da Battaglia di MightyE', 1575, 5, 11);
INSERT INTO weapons VALUES (171, 'Martello da Lancio di MightyE', 2250, 6, 11);
INSERT INTO weapons VALUES (172, 'Stella del Mattino di MightyE', 2790, 7, 11);
INSERT INTO weapons VALUES (173, 'Arco Composito di MightyE', 3420, 8, 11);
INSERT INTO weapons VALUES (174, 'Spadino di MightyE', 4230, 9, 11);
INSERT INTO weapons VALUES (175, 'Sciabola Leggera di MightyE', 5040, 10, 11);
INSERT INTO weapons VALUES (176, 'Sciabola di MightyE', 5850, 11, 11);
INSERT INTO weapons VALUES (177, 'Wakizashi di MightyE', 6840, 12, 11);
INSERT INTO weapons VALUES (178, 'Spada a Due Mani di MightyE', 8010, 13, 11);
INSERT INTO weapons VALUES (179, 'Ascia da battaglia a Due Mani di MightyE', 9000, 14, 11);
INSERT INTO weapons VALUES (180, 'Spadone di MightyE', 10350, 15, 11);
INSERT INTO weapons VALUES (181, 'Incantesimo del Fuoco', 48, 1, 12);
INSERT INTO weapons VALUES (182, 'Incantesimo del Terremoto', 225, 2, 12);
INSERT INTO weapons VALUES (183, 'Incantesimo dell´Alluvione', 585, 3, 12);
INSERT INTO weapons VALUES (184, 'Incantesimo dell´Uragano', 990, 4, 12);
INSERT INTO weapons VALUES (185, 'Incantesimo del Controllo Mentale', 1575, 5, 12);
INSERT INTO weapons VALUES (186, 'Incantesimo del Fulmine', 2250, 6, 12);
INSERT INTO weapons VALUES (187, 'Incantesimo della Debolezza', 2790, 7, 12);
INSERT INTO weapons VALUES (188, 'Incantesimo della Paura', 3420, 8, 12);
INSERT INTO weapons VALUES (189, 'Incantesimo del Veleno', 4230, 9, 12);
INSERT INTO weapons VALUES (190, 'Incantesimo della Possessione', 5040, 10, 12);
INSERT INTO weapons VALUES (191, 'Incantesimo della Disperazione', 5850, 11, 12);
INSERT INTO weapons VALUES (192, 'Incantesimo di Evocazione dei Ratti', 6840, 12, 12);
INSERT INTO weapons VALUES (193, 'Incantesimo di Evocazione dei Lupi', 8010, 13, 12);
INSERT INTO weapons VALUES (194, 'Incantesimo di Evocazione degli Unicorni', 9000, 14, 12);
INSERT INTO weapons VALUES (195, 'Incantesimo di Evocazione dei Draghi', 10350, 15, 12);

