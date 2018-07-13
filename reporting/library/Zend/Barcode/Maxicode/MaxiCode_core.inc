<?php

//  RMaxiCode for PHP
//  Copyright (C) Java4Less.com
//  All rights reserved
//
// Adquisition, use and distribution of this code is subject to restriction:
//  - You may modify the source code in order to adapt it to your needs.
//  - You may not remove this notice from the source code.
//  - This notice disclaim all warranties of all material.
//  - You may not copy and paste any code into external files.
//  - Use of this software on more than one server
//    requires the appropriate license.
//  - Redistribution of this source code (or a modified version)
//    is subject to the terms of the redistribution license.

define("ECI",chr(27));
define("NSHIFT",chr(31));
define("SHIFTA",chr(59));
define("SHIFT2A",chr(56));
define("SHIFT3A",chr(57));
define("SHIFTB",chr(59));
define("SHIFTC",chr(60));
define("LOCKC",chr(60));
define("SHIFTD",chr(61));
define("LOCKD",chr(61));
define("SHIFTE",chr(62));
define("LOCKE",chr(62));
define("LATCHA",chr(58));
define("LATCHB",chr(63));
define("LATCHA_B",chr(63));// when in mode B
define("PADAB",chr(33));
define("PADE",chr(29));

define('GS', chr(29));
define('RS', chr(30));


class MaxiCodecore
{

var $g; // Graphics object received from MaxiCode object

////////// KEY PARAMETERS

// maxicode mode. Values range from 2 to 6
var $mode;

// fields for primary structure (modes 2 and 3)
// postal code (numeric 30 bits in mode 2 or 6 chars from codeset A in mode 3)
// service class, country code  (both numeric 10 bits)

var $zipCode;
var $serviceClass;
var $country;

/** data to be encoded. In modes 2 and 3 if the postal code is empty 
   it will be extracted from the begining of this string
   (see maxicode specifications)
*/
var $data;

var $resolution;   // printer resolution in dpi
var $tolerance;

////////// PARAMETERS OBTAINED BY calcParams())

var $dpm;
var $tolerancePixels;

// center to center distance between adjacent modules == horizontal width of a module
var $WXpixels;

// vertical height of a module
var $Vpixels;

// vertical distance from the center line of modules in one row 
// to the center line of modules in an adjacent row
var $Ypixels;

// final size of symbol with mandatory quite zone,
// and without optional additional quite zone.
var $prefW;
var $prefH;

// center in pixels for circle finder pattern
var $centerX;
var $centerY;

////////// PATTERN RELATED PARAMETERS

/**
  Pattern used to print a hexagon. By default the class will calculate 
  the most appropiate pattern, but the user can specify it by calling
  to setHexagonPattern()
*/
var $pattern;

// pattern width & height in dots
var $patw;
var $path;

// auxiliary error fields to be retrieved by calling class
var $errorCode;
var $errorMsg;

var $TABLE1 = Array(
Array(0,'E',0),
Array(1,'E',1),
Array(2,'E',2),
Array(3,'E',3),
Array(4,'E',4),
Array(5,'E',5),
Array(6,'E',6),
Array(7,'E',7),
Array(8,'E',8),
Array(9,'E',9),
Array(10,'E',10),
Array(11,'E',11),
Array(12,'E',12),
Array(13,'E',13),
Array(13,'A',0),
Array(14,'E',14),
Array(15,'E',15),
Array(16,'E',16),
Array(17,'E',17),
Array(18,'E',18),
Array(19,'E',19),
Array(20,'E',20),
Array(21,'E',21),
Array(22,'E',22),
Array(23,'E',23),
Array(24,'E',24),
Array(25,'E',25),
Array(26,'E',26),
Array(27,'E',30),
Array(28,'E',32),
Array(28,'A',28),
Array(28,'B',28),
Array(28,'C',28),
Array(28,'D',28),
Array(29,'C',29),
Array(29,'D',29),
Array(29,'B',29),
Array(29,'A',29),
Array(29,'D',33),
Array(30,'C',30),
Array(30,'D',30),
Array(30,'B',30),
Array(30,'E',34),
Array(30,'A',30),
Array(31,'E',35),
Array(32,'D',59),
Array(32,'A',32),
Array(32,'E',59),
Array(32,'C',59),
Array(32,'B',47),
Array(33,'B',53),
Array(34,'A',34),
Array(35,'A',35),
Array(36,'A',36),
Array(37,'A',37),
Array(38,'A',38),
Array(39,'A',39),
Array(40,'A',40),
Array(41,'A',41),
Array(42,'A',42),
Array(43,'A',43),
Array(44,'A',44),
Array(44,'B',48),
Array(45,'A',45),
Array(46,'B',49),
Array(46,'A',46),
Array(47,'B',50),
Array(47,'A',47),
Array(48,'A',48),
Array(49,'A',49),
Array(50,'A',50),
Array(51,'A',51),
Array(52,'A',52),
Array(53,'A',53),
Array(54,'A',54),
Array(55,'A',55),
Array(56,'A',56),
Array(57,'A',57),
Array(58,'A',58),
Array(58,'B',51),
Array(59,'B',37),
Array(60,'B',38),
Array(61,'B',39),
Array(62,'B',40),
Array(63,'B',41),
Array(64,'B',52),
Array(65,'A',1),
Array(66,'A',2),
Array(67,'A',3),
Array(68,'A',4),
Array(69,'A',5),
Array(70,'A',6),
Array(71,'A',7),
Array(72,'A',8),
Array(73,'A',9),
Array(74,'A',10),
Array(75,'A',11),
Array(76,'A',12),
Array(77,'A',13),
Array(78,'A',14),
Array(79,'A',15),
Array(80,'A',16),
Array(81,'A',17),
Array(82,'A',18),
Array(83,'A',19),
Array(84,'A',20),
Array(85,'A',21),
Array(86,'A',22),
Array(87,'A',23),
Array(88,'A',24),
Array(89,'A',25),
Array(90,'A',26),
Array(91,'B',42),
Array(92,'B',43),
Array(93,'B',44),
Array(94,'B',45),
Array(95,'B',46),
Array(96,'B',0),
Array(97,'B',1),
Array(98,'B',2),
Array(99,'B',3),
Array(100,'B',4),
Array(101,'B',5),
Array(102,'B',6),
Array(103,'B',7),
Array(104,'B',8),
Array(105,'B',9),
Array(106,'B',10),
Array(107,'B',11),
Array(108,'B',12),
Array(109,'B',13),
Array(110,'B',14),
Array(111,'B',15),
Array(112,'B',16),
Array(113,'B',17),
Array(114,'B',18),
Array(115,'B',19),
Array(116,'B',20),
Array(117,'B',21),
Array(118,'B',22),
Array(119,'B',23),
Array(120,'B',24),
Array(121,'B',25),
Array(122,'B',26),
Array(123,'B',32),
Array(124,'B',54),
Array(125,'B',34),
Array(126,'B',35),
Array(127,'B',36),
Array(128,'C',48),
Array(129,'C',49),
Array(130,'C',50),
Array(131,'C',51),
Array(132,'C',52),
Array(133,'C',53),
Array(134,'C',54),
Array(135,'C',55),
Array(136,'C',56),
Array(137,'C',57),
Array(138,'D',47),
Array(139,'D',48),
Array(140,'D',49),
Array(141,'D',50),
Array(142,'D',51),
Array(143,'D',52),
Array(144,'D',53),
Array(145,'D',54),
Array(146,'D',55),
Array(147,'D',56),
Array(148,'D',57),
Array(149,'E',48),
Array(150,'E',49),
Array(151,'E',50),
Array(152,'E',51),
Array(153,'E',52),
Array(154,'E',53),
Array(155,'E',54),
Array(156,'E',55),
Array(157,'E',56),
Array(158,'E',57),
Array(159,'E',36),
Array(160,'E',37),
Array(161,'D',37),
Array(162,'E',38),
Array(163,'E',39),
Array(164,'E',40),
Array(165,'E',41),
Array(166,'D',42),
Array(167,'E',43),
Array(168,'D',38),
Array(169,'E',44),
Array(170,'C',37),
Array(171,'D',39),
Array(172,'C',38),
Array(173,'E',45),
Array(174,'E',46),
Array(175,'D',40),
Array(176,'D',41),
Array(177,'C',39),
Array(178,'C',40),
Array(179,'C',41),
Array(180,'D',42),
Array(181,'C',42),
Array(182,'E',47),
Array(183,'D',43),
Array(184,'D',44),
Array(185,'C',43),
Array(186,'C',44),
Array(187,'D',45),
Array(188,'C',45),
Array(189,'C',46),
Array(190,'C',47),
Array(191,'D',46),
Array(192,'C',0),
Array(193,'C',1),
Array(194,'C',2),
Array(195,'C',3),
Array(196,'C',4),
Array(197,'C',5),
Array(198,'C',6),
Array(199,'C',7),
Array(200,'C',8),
Array(201,'C',9),
Array(202,'C',10),
Array(203,'C',11),
Array(204,'C',12),
Array(205,'C',13),
Array(206,'C',14),
Array(207,'C',15),
Array(208,'C',16),
Array(209,'C',17),
Array(210,'C',18),
Array(211,'C',19),
Array(212,'C',20),
Array(213,'C',21),
Array(214,'C',22),
Array(215,'C',23),
Array(216,'C',24),
Array(217,'C',25),
Array(218,'C',26),
Array(219,'C',32),
Array(220,'C',33),
Array(221,'C',34),
Array(222,'C',35),
Array(223,'C',36),
Array(224,'D',0),
Array(225,'D',1),
Array(226,'D',2),
Array(227,'D',3),
Array(228,'D',4),
Array(229,'D',5),
Array(230,'D',6),
Array(231,'D',7),
Array(232,'D',8),
Array(233,'D',9),
Array(234,'D',10),
Array(235,'D',11),
Array(236,'D',12),
Array(237,'D',13),
Array(238,'D',14),
Array(239,'D',15),
Array(240,'D',16),
Array(241,'D',17),
Array(242,'D',18),
Array(243,'D',19),
Array(244,'D',20),
Array(245,'D',21),
Array(246,'D',22),
Array(247,'D',23),
Array(248,'D',24),
Array(249,'D',25),
Array(250,'D',26),
Array(251,'D',32),
Array(252,'C',33),
Array(253,'D',34),
Array(254,'D',35),
Array(255,'D',36)
);


var $PATTERN12DPM1 = Array(
	Array(0,0,0,0,0,0,0,0,0,0),
	Array(0,0,0,0,1,1,1,0,0,0),
	Array(0,0,0,1,1,1,1,1,0,0),
	Array(0,0,1,1,1,1,1,1,1,0),
	Array(0,1,1,1,1,1,1,1,1,1),
	Array(0,1,1,1,1,1,1,1,1,1),
	Array(0,1,1,1,1,1,1,1,1,1),
	Array(0,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,0),
	Array(0,0,0,1,1,1,1,1,0,0),
	Array(0,0,0,0,1,1,1,0,0,0),
	Array(0,0,0,0,0,0,0,0,0,0)
);

/**
 layout of the hexagons at 16 dpm
 */
var $PATTERN16DPM1 = Array(
	Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,1,1,0,0,0,0,0),
	Array(0,0,0,0,0,1,1,1,1,1,1,0,0,0),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,0),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,0),
	Array(0,0,0,0,0,1,1,1,1,1,1,0,0,0),
	Array(0,0,0,0,0,0,0,1,1,0,0,0,0,0)
);

/**
 layout of the hexagons at 20 dpm
 */
var $PATTERN20DPM1 = Array(
	Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
    Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,1,1,1,1,1,1,0,0,0,0),
	Array(0,0,0,0,0,0,1,1,1,1,1,1,1,1,0,0,0),
	Array(0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
    Array(0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,0),
    Array(0,0,0,0,0,0,1,1,1,1,1,1,1,1,0,0,0),
    Array(0,0,0,0,0,0,0,1,1,1,1,1,1,0,0,0,0),
    Array(0,0,0,0,0,0,0,0,0,1,1,0,0,0,0,0,0)
);

/**
 layout of the hexagons at 24 dpm
 */
var $PATTERN24DPM1 = Array(
	Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
    Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0),
    Array(0,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0),
	Array(0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0),
    Array(0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,0,0,0,0),
	Array(0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
	Array(0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1),
    Array(0,0,0,0,0,1,1,1,1,1,1,1,1,1,1,1,1,1,0,0),
    Array(0,0,0,0,0,0,0,1,1,1,1,1,1,1,1,1,0,0,0,0),
    Array(0,0,0,0,0,0,0,0,0,1,1,1,1,1,0,0,0,0,0,0),
    Array(0,0,0,0,0,0,0,0,0,0,1,1,1,0,0,0,0,0,0,0)
);

/**
 layout of the hexagons at 8 dpm
 */
var $PATTERN8DPM1 = Array(
	Array(0,0,0,0,0,0,0),
	Array(0,0,0,1,1,0,0),
	Array(0,0,1,1,1,1,0),
	Array(0,1,1,1,1,1,1),
	Array(0,1,1,1,1,1,1),
	Array(0,1,1,1,1,1,1),
	Array(0,0,1,1,1,1,0),
	Array(0,0,0,1,1,0,0),
);

var $LAYOUT1 = array (
   0 => array ( 0 => 122, 1 => 124, 2 => 126, 3 => 284, 4 => 286, 5 => 288, 6 => 290, 7 => 292, 8 => 294, 9 => 410, 10 => 412, 11 => 414, 12 => 416, 13 => 418, 14 => 420, 15 => 482, 16 => 484, 17 => 486, 18 => 488, 19 => 490, 20 => 492, 21 => 560, 22 => 562, 23 => 564, 24 => 566, 25 => 568, 26 => 570, 27 => 728, 28 => 730, 29 => 732, 30 => 734, 31 => 736, 32 => 738, ),
   1 => array ( 0 => 121, 1 => 123, 2 => 125, 3 => 283, 4 => 285, 5 => 287, 6 => 289, 7 => 291, 8 => 293, 9 => 409, 10 => 411, 11 => 413, 12 => 415, 13 => 417, 14 => 419, 15 => 481, 16 => 483, 17 => 485, 18 => 487, 19 => 489, 20 => 491, 21 => 559, 22 => 561, 23 => 563, 24 => 565, 25 => 567, 26 => 569, 27 => 727, 28 => 729, 29 => 731, 30 => 733, 31 => 735, 32 => 737, ),
   2 => array ( 0 => 128, 1 => 130, 2 => 132, 3 => 278, 4 => 280, 5 => 282, 6 => 296, 7 => 298, 8 => 300, 9 => 404, 10 => 406, 11 => 408, 12 => 422, 13 => 424, 14 => 426, 15 => 476, 16 => 478, 17 => 480, 18 => 494, 19 => 496, 20 => 498, 21 => 554, 22 => 556, 23 => 558, 24 => 572, 25 => 574, 26 => 576, 27 => 722, 28 => 724, 29 => 726, 30 => 740, 31 => 742, 32 => 744, ),
   3 => array ( 0 => 127, 1 => 129, 2 => 131, 3 => 277, 4 => 279, 5 => 281, 6 => 295, 7 => 297, 8 => 299, 9 => 403, 10 => 405, 11 => 407, 12 => 421, 13 => 423, 14 => 425, 15 => 475, 16 => 477, 17 => 479, 18 => 493, 19 => 495, 20 => 497, 21 => 553, 22 => 555, 23 => 557, 24 => 571, 25 => 573, 26 => 575, 27 => 721, 28 => 723, 29 => 725, 30 => 739, 31 => 741, 32 => 743, ),
   4 => array ( 0 => 134, 1 => 136, 2 => 138, 3 => 272, 4 => 274, 5 => 276, 6 => 302, 7 => 304, 8 => 306, 9 => 398, 10 => 400, 11 => 402, 12 => 428, 13 => 430, 14 => 432, 15 => 470, 16 => 472, 17 => 474, 18 => 500, 19 => 502, 20 => 504, 21 => 548, 22 => 550, 23 => 552, 24 => 578, 25 => 580, 26 => 582, 27 => 716, 28 => 718, 29 => 720, 30 => 746, 31 => 748, 32 => 750, ),
   5 => array ( 0 => 133, 1 => 135, 2 => 137, 3 => 271, 4 => 273, 5 => 275, 6 => 301, 7 => 303, 8 => 305, 9 => 397, 10 => 399, 11 => 401, 12 => 427, 13 => 429, 14 => 431, 15 => 469, 16 => 471, 17 => 473, 18 => 499, 19 => 501, 20 => 503, 21 => 547, 22 => 549, 23 => 551, 24 => 577, 25 => 579, 26 => 581, 27 => 715, 28 => 717, 29 => 719, 30 => 745, 31 => 747, 32 => 749, ),
   6 => array ( 0 => 140, 1 => 142, 2 => 144, 3 => 266, 4 => 268, 5 => 270, 6 => 308, 7 => 310, 8 => 312, 9 => 392, 10 => 394, 11 => 396, 12 => 104, 13 => 106, 14 => 108, 15 => 49, 16 => 50, 17 => 52, 18 => 98, 19 => 100, 20 => 102, 21 => 542, 22 => 544, 23 => 546, 24 => 584, 25 => 586, 26 => 588, 27 => 710, 28 => 712, 29 => 714, 30 => 752, 31 => 754, 32 => 756, ),
   7 => array ( 0 => 139, 1 => 141, 2 => 143, 3 => 265, 4 => 267, 5 => 269, 6 => 307, 7 => 309, 8 => 311, 9 => 391, 10 => 393, 11 => 395, 12 => 103, 13 => 105, 14 => 107, 15 => -1, 16 => 0, 17 => 51, 18 => 97, 19 => 99, 20 => 101, 21 => 541, 22 => 543, 23 => 545, 24 => 583, 25 => 585, 26 => 587, 27 => 709, 28 => 711, 29 => 713, 30 => 751, 31 => 753, 32 => 755, ),
   8 => array ( 0 => 146, 1 => 148, 2 => 150, 3 => 260, 4 => 262, 5 => 264, 6 => 314, 7 => 316, 8 => 318, 9 => 80, 10 => 82, 11 => 84, 12 => 56, 13 => 58, 14 => 60, 15 => 31, 16 => -1, 17 => 32, 18 => 62, 19 => 64, 20 => 66, 21 => 74, 22 => 76, 23 => 78, 24 => 590, 25 => 592, 26 => 594, 27 => 704, 28 => 706, 29 => 708, 30 => 758, 31 => 760, 32 => 762, ),
   9 => array ( 0 => 145, 1 => 147, 2 => 149, 3 => 259, 4 => 261, 5 => 263, 6 => 313, 7 => 315, 8 => 317, 9 => 79, 10 => 81, 11 => 83, 12 => 55, 13 => 57, 14 => 59, 15 => 0, 16 => 0, 17 => 0, 18 => 61, 19 => 63, 20 => 65, 21 => 73, 22 => 75, 23 => 77, 24 => 589, 25 => 591, 26 => 593, 27 => 703, 28 => 705, 29 => 707, 30 => 757, 31 => 759, 32 => 761, ),
   10 => array ( 0 => 152, 1 => 154, 2 => 156, 3 => 254, 4 => 256, 5 => 258, 6 => 320, 7 => 322, 8 => 324, 9 => -1, 10 => 41, 11 => 42, 12 => 17, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 18, 21 => 33, 22 => -1, 23 => -1, 24 => 596, 25 => 598, 26 => 600, 27 => 698, 28 => 700, 29 => 702, 30 => 764, 31 => 766, 32 => 768, ),
   11 => array ( 0 => 151, 1 => 153, 2 => 155, 3 => 253, 4 => 255, 5 => 257, 6 => 319, 7 => 321, 8 => 323, 9 => -1, 10 => -1, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 0, 23 => 34, 24 => 595, 25 => 597, 26 => 599, 27 => 697, 28 => 699, 29 => 701, 30 => 763, 31 => 765, 32 => 767, ),
   12 => array ( 0 => 158, 1 => 160, 2 => 162, 3 => 248, 4 => 250, 5 => 252, 6 => 326, 7 => 328, 8 => 330, 9 => 14, 10 => 16, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 8, 23 => 10, 24 => 602, 25 => 604, 26 => 606, 27 => 692, 28 => 694, 29 => 696, 30 => 770, 31 => 772, 32 => 774, ),
   13 => array ( 0 => 157, 1 => 159, 2 => 161, 3 => 247, 4 => 249, 5 => 251, 6 => 325, 7 => 327, 8 => 329, 9 => 13, 10 => 15, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 7, 23 => 9, 24 => 601, 25 => 603, 26 => 605, 27 => 691, 28 => 693, 29 => 695, 30 => 769, 31 => 771, 32 => 773, ),
   14 => array ( 0 => 164, 1 => 166, 2 => 168, 3 => 242, 4 => 244, 5 => 246, 6 => 332, 7 => 334, 8 => 336, 9 => 38, 10 => 40, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 36, 23 => 26, 24 => 608, 25 => 610, 26 => 612, 27 => 686, 28 => 688, 29 => 690, 30 => 776, 31 => 778, 32 => 780, ),
   15 => array ( 0 => 163, 1 => 165, 2 => 167, 3 => 241, 4 => 243, 5 => 245, 6 => 331, 7 => 333, 8 => 335, 9 => 37, 10 => 39, 11 => 0, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 35, 23 => 25, 24 => 607, 25 => 609, 26 => 611, 27 => 685, 28 => 687, 29 => 689, 30 => 775, 31 => 777, 32 => 779, ),
   16 => array ( 0 => 170, 1 => 172, 2 => 174, 3 => 236, 4 => 238, 5 => 240, 6 => 338, 7 => 340, 8 => 342, 9 => 3, 10 => 4, 11 => 6, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 0, 22 => 12, 23 => 0, 24 => 614, 25 => 616, 26 => 618, 27 => 680, 28 => 682, 29 => 684, 30 => 782, 31 => 784, 32 => 786, ),
   17 => array ( 0 => 169, 1 => 171, 2 => 173, 3 => 235, 4 => 237, 5 => 239, 6 => 337, 7 => 339, 8 => 341, 9 => 0, 10 => 0, 11 => 5, 12 => 0, 13 => 0, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0, 20 => 0, 21 => 11, 22 => -1, 23 => -1, 24 => 613, 25 => 615, 26 => 617, 27 => 679, 28 => 681, 29 => 683, 30 => 781, 31 => 783, 32 => 785, ),
   18 => array ( 0 => 176, 1 => 178, 2 => 180, 3 => 230, 4 => 232, 5 => 234, 6 => 344, 7 => 346, 8 => 348, 9 => 45, 10 => 0, 11 => 48, 12 => 21, 13 => 23, 14 => 0, 15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 29, 20 => 19, 21 => 68, 22 => 70, 23 => 72, 24 => 620, 25 => 622, 26 => 624, 27 => 674, 28 => 676, 29 => 678, 30 => 788, 31 => 790, 32 => 792, ),
   19 => array ( 0 => 175, 1 => 177, 2 => 179, 3 => 229, 4 => 231, 5 => 233, 6 => 343, 7 => 345, 8 => 347, 9 => 44, 10 => 46, 11 => 47, 12 => 20, 13 => 22, 14 => 24, 15 => 1, 16 => 0, 17 => 2, 18 => 27, 19 => 28, 20 => 30, 21 => 67, 22 => 69, 23 => 71, 24 => 619, 25 => 621, 26 => 623, 27 => 673, 28 => 675, 29 => 677, 30 => 787, 31 => 789, 32 => 791, ),
   20 => array ( 0 => 182, 1 => 184, 2 => 186, 3 => 224, 4 => 226, 5 => 228, 6 => 350, 7 => 352, 8 => 354, 9 => 110, 10 => 112, 11 => 114, 12 => 86, 13 => 88, 14 => 90, 15 => 54, 16 => -1, 17 => -1, 18 => 92, 19 => 94, 20 => 96, 21 => 116, 22 => 118, 23 => 120, 24 => 626, 25 => 628, 26 => 630, 27 => 668, 28 => 670, 29 => 672, 30 => 794, 31 => 796, 32 => 798, ),
   21 => array ( 0 => 181, 1 => 183, 2 => 185, 3 => 223, 4 => 225, 5 => 227, 6 => 349, 7 => 351, 8 => 353, 9 => 109, 10 => 111, 11 => 113, 12 => 85, 13 => 87, 14 => 89, 15 => 53, 16 => 0, 17 => 43, 18 => 91, 19 => 93, 20 => 95, 21 => 115, 22 => 117, 23 => 119, 24 => 625, 25 => 627, 26 => 629, 27 => 667, 28 => 669, 29 => 671, 30 => 793, 31 => 795, 32 => 797, ),
   22 => array ( 0 => 188, 1 => 190, 2 => 192, 3 => 218, 4 => 220, 5 => 222, 6 => 356, 7 => 358, 8 => 360, 9 => 386, 10 => 388, 11 => 390, 12 => 434, 13 => 436, 14 => 438, 15 => 464, 16 => 466, 17 => 468, 18 => 506, 19 => 508, 20 => 510, 21 => 536, 22 => 538, 23 => 540, 24 => 632, 25 => 634, 26 => 636, 27 => 662, 28 => 664, 29 => 666, 30 => 800, 31 => 802, 32 => 804, ),
   23 => array ( 0 => 187, 1 => 189, 2 => 191, 3 => 217, 4 => 219, 5 => 221, 6 => 355, 7 => 357, 8 => 359, 9 => 385, 10 => 387, 11 => 389, 12 => 433, 13 => 435, 14 => 437, 15 => 463, 16 => 465, 17 => 467, 18 => 505, 19 => 507, 20 => 509, 21 => 535, 22 => 537, 23 => 539, 24 => 631, 25 => 633, 26 => 635, 27 => 661, 28 => 663, 29 => 665, 30 => 799, 31 => 801, 32 => 803, ),
   24 => array ( 0 => 194, 1 => 196, 2 => 198, 3 => 212, 4 => 214, 5 => 216, 6 => 362, 7 => 364, 8 => 366, 9 => 380, 10 => 382, 11 => 384, 12 => 440, 13 => 442, 14 => 444, 15 => 458, 16 => 460, 17 => 462, 18 => 512, 19 => 514, 20 => 516, 21 => 530, 22 => 532, 23 => 534, 24 => 638, 25 => 640, 26 => 642, 27 => 656, 28 => 658, 29 => 660, 30 => 806, 31 => 808, 32 => 810, ),
   25 => array ( 0 => 193, 1 => 195, 2 => 197, 3 => 211, 4 => 213, 5 => 215, 6 => 361, 7 => 363, 8 => 365, 9 => 379, 10 => 381, 11 => 383, 12 => 439, 13 => 441, 14 => 443, 15 => 457, 16 => 459, 17 => 461, 18 => 511, 19 => 513, 20 => 515, 21 => 529, 22 => 531, 23 => 533, 24 => 637, 25 => 639, 26 => 641, 27 => 655, 28 => 657, 29 => 659, 30 => 805, 31 => 807, 32 => 809, ),
   26 => array ( 0 => 200, 1 => 202, 2 => 204, 3 => 206, 4 => 208, 5 => 210, 6 => 368, 7 => 370, 8 => 372, 9 => 374, 10 => 376, 11 => 378, 12 => 446, 13 => 448, 14 => 450, 15 => 452, 16 => 454, 17 => 456, 18 => 518, 19 => 520, 20 => 522, 21 => 524, 22 => 526, 23 => 528, 24 => 644, 25 => 646, 26 => 648, 27 => 650, 28 => 652, 29 => 654, 30 => 812, 31 => 814, 32 => 816, ),
   27 => array ( 0 => 199, 1 => 201, 2 => 203, 3 => 205, 4 => 207, 5 => 209, 6 => 367, 7 => 369, 8 => 371, 9 => 373, 10 => 375, 11 => 377, 12 => 445, 13 => 447, 14 => 449, 15 => 451, 16 => 453, 17 => 455, 18 => 517, 19 => 519, 20 => 521, 21 => 523, 22 => 525, 23 => 527, 24 => 643, 25 => 645, 26 => 647, 27 => 649, 28 => 651, 29 => 653, 30 => 811, 31 => 813, 32 => 815, ),
   28 => array ( 0 => -1, 1 => 817, 2 => 819, 3 => 820, 4 => 822, 5 => 823, 6 => 825, 7 => 826, 8 => 828, 9 => 829, 10 => 831, 11 => 832, 12 => 834, 13 => 835, 14 => 837, 15 => 838, 16 => 840, 17 => 841, 18 => 843, 19 => 844, 20 => 846, 21 => 847, 22 => 849, 23 => 850, 24 => 852, 25 => 853, 26 => 855, 27 => 856, 28 => 858, 29 => 859, 30 => 861, 31 => 862, 32 => 864, ),
   29 => array ( 0 => -1, 1 => 0, 2 => 818, 3 => 0, 4 => 821, 5 => 0, 6 => 824, 7 => 0, 8 => 827, 9 => 0, 10 => 830, 11 => 0, 12 => 833, 13 => 0, 14 => 836, 15 => 0, 16 => 839, 17 => 0, 18 => 842, 19 => 0, 20 => 845, 21 => 0, 22 => 848, 23 => 0, 24 => 851, 25 => 0, 26 => 854, 27 => 0, 28 => 857, 29 => 0, 30 => 860, 31 => 0, 32 => 863 )
);



////////////////////////// CONSTRUCTOR

function MaxiCodecore( &$g ) {

   // initializations
   $this->g = &$g;

   $this->postalCode = '';
   $this->serviceClass = '';
   $this->country = '';
   $this->data = '';
  
   // default values
   #$this->L = 25.5;
   $this->tolerance=0.12;
   $this->resolution = NULL;
   $this->pattern = NULL;
   $this->mode = 4; // standard mode
   $this->numberOfSymbols = 1;
   $this->errorMsg = NULL;
}      

////////////////////////// CALCULATE PARAMETERS

function calcParams( $r ) {

   $L = 25.5; // medium symbol length in mm
   $this->dpm = $r / 25.4; // dpi to dpm

   $predefined = TRUE;
   switch( $r ) {
   case 200:

      $this->pattern = $this->PATTERN8DPM1; //$GLOBALS['PATTERN8DPM'];
      $this->WXpixels = 7;
      $this->Vpixels = 8;
      break;
   case 300:

      $this->pattern = $this->PATTERN12DPM1;//$GLOBALS['PATTERN12DPM'];
      $this->WXpixels = 10;
      $this->Vpixels = 12;
      break;
   case 400:

      $this->pattern = $this->PATTERN16DPM1;//$GLOBALS['PATTERN16DPM'];
      $this->WXpixels = 14;
      $this->Vpixels = 16;
      break;
   case 500:

      $this->pattern = $this->PATTERN20DPM1; //$GLOBALS['PATTERN20DPM'];
      $this->WXpixels = 17;
      $this->Vpixels = 20;
      break;
   case 600:
        
      $this->pattern = $this->PATTERN24DPM1; //$GLOBALS['PATTERN24DPM'];
      $this->WXpixels = 20;
      $this->Vpixels = 24;
      break;
   default:
      $predefined = FALSE;
      break;
   }

   if ( $predefined ) {
      $this->Ypixels = round( $this->Vpixels * 0.75 );
      $this->calcParam2();
      $this->resolution = $r;
      return TRUE;
   }

   // NOT PREDEFINED RESOLUTIONS, CALCULATE

   $W = $L / 29;
   $V = ( 2 / sqrt(3) ) * $W;
   $Y = ( 1.5 / sqrt(3) ) * $W;
   $Lpixels = round ( $L * $this->dpm );
   $this->WXpixels = round ( $Lpixels / 29 );
   $this->Vpixels = round( ( 2 / sqrt(3) )   * $this->WXpixels );
   $this->Ypixels = round( ( 1.5 / sqrt(3) ) * $this->WXpixels );

   $La = $this->WXpixels * 29 / $this->dpm;
   $Ha = $this->Ypixels * 32 / $this->dpm;

   $inLimits = true;
   if ( $La < 24 || $La > 27 )     $inLimits = false;
   if ( $Ha < 22.9 || $Ha > 25.8 ) $inLimits = false;

   if ( ! $inLimits ) {

      // round WXpixels the other way
   
      if ( $this->WXpixels > $Lpixels / 29 ) 
         $this->WXpixels--;
      else
         $this->WXpixels++;

      $this->Vpixels = round( ( 2   / sqrt(3) ) * $this->WXpixels );
      $this->Ypixels = round( ( 1.5 / sqrt(3) ) * $this->WXpixels );

      $La = $this->WXpixels * 29 / $this->dpm;
      $Ha = $this->Ypixels * 32 / $this->dpm;

      $inLimits = true;
      if ( $La < 24 || $La > 27 )     $inLimits = false;
      if ( $Ha < 22.9 || $Ha > 25.8 ) $inLimits = false;

      if (!$inLimits) return false;
   }

   $this->calcParam2();
   
   $this->resolution = $r;
   return true;
}

function calcParam2() {

   $this->centerY = ( (int)($this->Vpixels / 2) ) + ( 17 * $this->Ypixels );
   $this->centerX = ( (int)($this->WXpixels / 2) ) + ( 15 * $this->WXpixels );
   $this->tolerancePixels = round( $this->tolerance * $this->dpm );

   $this->prefW = $this->WXpixels * 32;
   $this->prefH = $this->Ypixels * 34 + $this->Vpixels;
}

//////////  LOAD PATTERN

function loadPattern(){

   //*** Load Hexagon Pattern

   if ( $this->pattern === NULL )
      $this->pattern = $this->selectPattern();
	  
   // module width & height in dots
   $this->patw = count( $this->pattern[0] );
   $this->path = count( $this->pattern );
}

//////////  SELECT PATTERN

// returns a standard hexagon pattern or a newly created one, 
// if a standard cannot be used.

function selectPattern() {

   // use hardcoded pattern
   
   if ( $this->WXpixels ==  7 && $this->Vpixels ==  8 )  return $GLOBALS['PATTERN8DPM'];
   if ( $this->WXpixels == 10 && $this->Vpixels == 12 )  return $GLOBALS['PATTERN12DPM'];
   if ( $this->WXpixels == 14 && $this->Vpixels == 16 )  return $GLOBALS['PATTERN16DPM'];
   if ( $this->WXpixels == 17 && $this->Vpixels == 20 )  return $GLOBALS['PATTERN20DPM'];
   if ( $this->WXpixels == 20 && $this->Vpixels == 24 )  return $GLOBALS['PATTERN24DPM'];

   // else create new pattern
   
   $trows = $this->Vpixels - $this->tolerancePixels;
   $tcols = $this->WXpixels - $this->tolerancePixels;
	  
   $p = $this->getArray2D( $this->Vpixels, $this->WXpixels, 0 );
   $ptmp = $this->getArray2D( $trows, $tcols, 1 );

   // arrays reset

   $overlap = (int) ( $this->Vpixels - $this->Ypixels );

   $hexagonTopSide = (int) floor( $tcols / 3 );
   $pixelsToRemove = ( $tcols - $hexagonTopSide ) / 2;

   // set to 0 the corners
   for ( $i = 0; $i < $pixelsToRemove; $i++ )
      for ( $j = 0; $j < ($pixelsToRemove - $i); $j++ ) {
         $ptmp[$j][$i]=0; // left top
         $ptmp[$j][ $tcols -1 -$i ] = 0;  // right top
         $ptmp[ $trows -$j -1 ][$i]=0; // left bottom
         $ptmp[ $trows -$j -1 ][ $tcols -1 -$i ] = 0;  // right bottom
      }

   for ( $i = 0; $i < $tcols; $i++ )
      for ( $j = 0; $j < $trows; $j++ )
         $p[ $j + $this->tolerancePixels ]
          [ $i + $this->tolerancePixels ]
          = $ptmp[$j][$i];

   return $p;
}

////// GET ARRAY 2D 

function getArray2D($len, $len2, $value )   {

   $len = (int) $len;
   if ( $len <= 0 )
      return array();
   else
      return array_fill(0, $len, array_fill( 0, $len2, $value ) );

}

////////////////////////// CREATE PRIMARY

// create primary message in mode 2 and 3

function createPrimary() {

   if ( $this->mode <> 2 && $this->mode <> 3 )
      return '';

   if ( $this->postalCode )
   // obtain bits for service class & country

   $bits = $this->getBits( $this->serviceClass, 10 );
   $bits .= $this->getBits( $this->country, 10 );

   // obtain bits for postal code & mode
   
  if ( $this->mode == 2 ) {

     $bits .= $this->getBits( strlen($this->postalCode), 6 );
     $bits .= $this->getBits( $this->postalCode, 30 );
	 $bits .= '0010';

   } else {
   
      for ( $i = 0; $i < 6; $i++ ) {
	     $char = $this->charSet( $this->postalCode[$i], 'A');
		 if ( $char === NULL ) {
            $this->errorCode = 'E_INVALID_DATA';
            $this->errorMsg = 'Invalid character in postal code';
            return NULL;
		 }
         $bits .= $this->getBits( ord($char), 6 );  
	  }
	 $bits .= '0011';   
   }

   // encode 60 bits into 10 codewords

   $primary = '';
   
   for ( $j = 9; $j >= 0; $j-- ) {
       $sixbits = substr( $bits, $j * 6, 6 );
       $primary .= $this->parseBits( $sixbits );
   }
   
   return $primary;
}

//////////////////////////   ENCODE

// encodes a string in the maxicode character set
// param $max_cw : maximum number of codewords to use
// param $plus2 : flag to indicate that in case $max_cw is not reached, 
//    two pad characters must be appended
// implicit param $this->data : data to be encoded. 
//    Enconded portion is removed from it.
// returns: continuous codeword stream, with pad chars if necessary,
//    but without error correction codewords.

function encode( &$max_cw, $plus2 ) {

   $tmp = '';
   $set = 'A';
   $pos = 0;
   
   while ( $pos < strlen($this->data) && strlen($tmp) < $max_cw ) {

      // 9 DIGITS
  
      if ( $this->nineDigits( $pos ) ) { 
         $tmp .= NSHIFT;
         $tmp .= $this->encode9Digits( substr( $this->data, $pos, 9 ) );
         $pos += 9;
         continue;
      }

      // CHARSET B

      if ( $set == 'B' ) {

         $bytes = $this->inCharSet($pos,'B',1);
         if ( $bytes !== NULL ) { 
            $tmp .= $bytes;
            $pos++;
            continue;
         }

         // charset B, 4 chars A

         $bytes = $this->inCharSet($pos,'A',4,$set);

         if ( $bytes  !== NULL ) {
            $tmp .= LATCHA_B . $bytes[0];
            $pos++;
            $set='A';
            continue;
         }

         // charset B, 3 chars A

         $bytes = $this->inCharSet($pos,'A',3,$set);

         if ( $bytes  !== NULL ) {
            $tmp .= SHIFT3A . $bytes;
            $pos += 3;
            continue;
         }

         // charset B, 2 chars A

         $bytes = $this->inCharSet($pos,'A',2,$set);

         if ( $bytes  !== NULL ) {
            $tmp .= SHIFT2A . $bytes;
            $pos += 2;
            continue;
         }

         // charset B, 1 char A

         $bytes = $this->inCharSet($pos,'A',1);

         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTA . $bytes;
            $pos++;
            continue;
         }

      } // of if charset B

      // CHARSET A

      if ( $set == 'A' ) {

         $bytes = $this->inCharSet($pos,'A',1);

         if ( $bytes  !== NULL ) {
            $tmp .= $bytes;
            $pos++;
            continue;
         }

         // charset A , 2 chars B

         $bytes = $this->inCharSet($pos,'B',2,$set);

         if ( $bytes  !== NULL ) {
            $tmp .= LATCHB . $bytes[0];
            $pos++;
            $set='B';
            continue;
         }

         // charset A , 1 chars B

         $bytes = $this->inCharSet($pos,'B',1);

         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTB . $bytes;
            $pos++;
            continue;
         }

      } // of if charset A


      // CHARSET A or B

      if ( $set <= 'B' ) {

         // set A or B, then 4 chars C,D or E

         $bytes = $this->inCharSet($pos,'C',4,$set);
         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTC . LOCKC . $bytes[0];
            $pos++;
            $set='C';
            continue;
         }

         $bytes = $this->inCharSet($pos,'D',4,$set);
         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTD . LOCKD . $bytes[0];
            $pos++;
            $set='D';
            continue;
         }

         $bytes = $this->inCharSet($pos,'E',4,$set);
         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTE . LOCKE . $bytes[0];
            $pos++;
            $set='E';
            continue;
         }

         // set A or B, then 1 char C,D or E

         $bytes = $this->inCharSet($pos,'C',1);
         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTC . $bytes;
            $pos++;
            continue;
         }

         $bytes = $this->inCharSet($pos,'D',1);
         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTD . $bytes;
            $pos++;
            continue;
         }

         $bytes = $this->inCharSet($pos,'E',1);
         if ( $bytes  !== NULL ) {
            $tmp .= SHIFTE . $bytes;
            $pos++;
            continue;
         }

      } // of if charset A or B

      //  CHARSET C, D or E

      if ( $set > 'B' ) {

         // if now A

         $bytes = $this->inCharSet($pos,'A',1);
         if ( $bytes  !== NULL ) {
            $tmp .= LATCHA . $bytes;
            $pos++;
            $set='A';
            continue;
         }

         // if now B

         $bytes = $this->inCharSet($pos,'B',1);
         if ( $bytes  !== NULL ) {
            $tmp .= LATCHB . $bytes;
            $pos++;
            $set='B';
            continue;
         }

         // if more than 1 C , D or E

         if ( $set != 'C' ) {
            $bytes = $this->inCharSet($pos,'C',2);
            if ( $bytes  !== NULL ) {
               $tmp .= SHIFTC . LOCKC . $bytes[0];
               $pos++;
               $set='C';
               continue;
            }
         }

         if ( $set != 'D' ) {
            $bytes = $this->inCharSet($pos,'D',2);
            if ( $bytes  !== NULL ) {
               $tmp .= SHIFTD . LOCKD . $bytes[0];
               $pos++;
               $set='D';
               continue;
            }
         }

         if ( $set != 'E' ) {
            $bytes = $this->inCharSet($pos,'E',2);
            if ( $bytes  !== NULL ) {
               $tmp .= SHIFTE . LOCKE . $bytes[0];
               $pos++;
               $set='E';
               continue;
            }
         }

         // if only 1 C , D or E

         $bytes = $this->inCharSet($pos,'C',1);
         if ( $bytes  !== NULL ) {
            if ( $set != 'C' ) $tmp .= SHIFTC;
            $tmp .= $bytes;
            $pos++;
            continue;
         }

         $bytes = $this->inCharSet($pos,'D',1);
         if ( $bytes  !== NULL ) {
            if ( $set != 'D' ) $tmp .= SHIFTD;
            $tmp .= $bytes;
            $pos++;
            continue;
         }

         $bytes = $this->inCharSet($pos,'E',1);
         if ( $bytes  !== NULL ) {
            if ( $set != 'E' ) $tmp .= SHIFTE;
            $tmp .= $bytes;
            $pos++;
            continue;
         }

      } // of charset C, D or E

      // this should never happen
      $this->errorCode = 'E_INTERNAL';
      $this->errorMsg = 'ASCII '. ord($this->data[$pos]).' not encoded';
      return NULL;
	  
   }  // of while


   // remove already encoded part from $this->data

   $this->data = substr( $this->data, $pos );
   if ( $plus2 && strlen($this->data) == 0 ) $max_cw = $max_cw + 2;
   
   //** reached maximum symbol capacity

   if ( strlen($tmp) == $max_cw ) return $tmp;
   
   //** space left, use pad codeword to fill it

   //>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 
   if ( $set > 'B' ) $tmp .= LATCHA;
   #$tmp .= LATCHB;

   return str_pad( $tmp, $max_cw, PADAB );

} // of encode()

////////////////////////// AUXILIARY FUNCTIONS FOR CREATEPRIMARY() & ENCODE()

/////// PARSE BITS : convert from 6 bits string to byte value -char-

function parseBits( $s ) {

   $v = 0;

   if ( $s[0] == '1' ) $v += 32;
   if ( $s[1] == '1' ) $v += 16;
   if ( $s[2] == '1' ) $v += 8;
   if ( $s[3] == '1' ) $v += 4;
   if ( $s[4] == '1' ) $v += 2;
   if ( $s[5] == '1' ) $v += 1;
   
   return chr($v);
}

////// ENCODE 9 DIGITS : encode 9 consecutive digits

function encode9Digits( $n ) {

   $n = (int) $n;
   $tmp = $this->getBits( $n, 30 );
   $r = '';
   for ( $i = 0; $i < 30; $i += 6 )
      $r .= $this->parseBits( substr( $tmp, $i, 6 ) );
   return $r;
}

//////  GET BITS

// convert from int to bit string

function getBits( $c, $num ) {

   $bits = decbin($c);  
   $difer = strlen( $bits ) - $num;

   //  result may be long : ignore digits on the left    
   if ( $difer >= 0 )
	   return substr( $bits, $difer );

   // else: result is short, left zero pad 	  
   return str_pad( $bits, $num, '0', STR_PAD_LEFT);    
}

//////  IS DIGIT

function  isDigit( $c ) {

   return ( $c >= '0' && $c <= '9' );
}

//////  NINE DIGITS

function  nineDigits( $pos ) {

   $end = $pos + 9;
   if ( $end > strlen($this->data) ) return FALSE;
   
   while( $pos < $end ){
      $c = $this->data[$pos];
      if ( $c < '0' ) return FALSE; 
      if ( $c > '9' ) return FALSE; 
	  $pos++;
   } 
   return TRUE;
}

////// IN CHAR SET

// Checks if a char sequence inside $this->data can be encoded with a
// specific char set and encodes if so.
// param $pos : starting position in $this->data
// param $cs : char set
// param $count : number of chars from $pos to check
// param $set : current char set. if assigned it means that is required
//   that no char in the sequence can be encoded with the current set
//   (because then it may be better to use a 'shift' instead of a 'latch')
//  returns : encoded codeword sequence for those chars
//  or NULL if not possible or advisable to encode the complete sequence 
//  with the specified set.

function inCharSet( $pos, $cs, $count, $set = '' ) {

   $s = '';
   $count = $pos + $count;
	  
   if ( $count > strlen($this->data) ) 
      return NULL;

   $first = $pos;
   $valid = 0;
   while ( $pos < $count ) {
	  if ( $set && $pos <> $first ) {
         $v = $this->charSet( $this->data[$pos], $set );
         if ( $v !== NULL ) return NULL;
	  }
      $v = $this->charSet( $this->data[$pos], $cs );
      if ( $v === NULL ) return NULL;
      $s .= $v;
      $pos++;
   }
   
   return $s;
}


////// CHAR SET

// returns the codeword value of the char $ in the character set $cs,
// or NULL if not in the character set.

function charSet( $c, $cs ) {

   //global $TABLE;

    $TABLE = $this->TABLE1;
   $c = ord($c);
   $i = $c -1;
   if ( $i < 0 ) $i = 0;

   while ( $i < count($TABLE) ) {
      if ( $TABLE[$i][0] == $c )
         if ( $TABLE[$i][1] == $cs )
            return chr($TABLE[$i][2]);
      // the index is too high
      if ( $TABLE[$i][0] > $c ) break;
      $i++;
   }

   return NULL;
}


////////////////////////// ADD REED SOLOMON

//  Apply Reed Solomon algorithm to previously encoded codeword stream

function addReedSolomon( $encoded ) {

   $rs = new reed6();

   // PRIMARY MESSAGE
   
   $primary = substr( $encoded, 0, 10 );
   // Enhanced Error Correction
   // Error correcion codewords are appended to first parameter
   $rs->calcRS( $primary, 10, 10 );
   
   // SECONDARY MESSAGE

   // extract interleaved blocks
   // $encoded length is 78 or 94

   $secondaryEven = '';
   $secondaryOdd = '';
   $i = 10;
   $len = strlen($encoded);
  
   while ( $i < $len ) {
      $secondaryEven .= $encoded[ $i++ ];
      $secondaryOdd  .= $encoded[ $i++ ];
   }

   // calculate RS for interleaved blocks

   if ( $this->mode == 5 ) {  // Enhanced Error Correction
      $k = 34;
      $t2 = 28;
   } else {      // Standard Error Correction
      $k = 42;
      $t2 = 20;
   }

   $rs->CalcRS( $secondaryEven, $k, $t2 );
   $rs->calcRS( $secondaryOdd,  $k, $t2 );

   // compose secondary message 

   $secondary = '';
   for ( $i = 0; $i < 62; $i++ )
      $secondary .= $secondaryEven[$i] . $secondaryOdd[$i];

   // result

   return $primary . $secondary;
}


////////////////////////// PAINT LAYOUT

// param $finalData: codewords stream (must be of length 144)
// params $left, $top : coordinates where it will be drawn

function  paintLayout( &$finalData, $left, $top ) {

   //global $LAYOUT;
   $LAYOUT = $this->LAYOUT1;
   $tmpy = $top + $this->Ypixels;
   $even = true;

   // for each row
   
   for ( $j = 0; $j < 33; $j++ ) {

      $tmpx = $left +$this->WXpixels;
      if ( ! $even ) $tmpx += (int) ($this->WXpixels / 2);

      // for each column
	  
      for ( $i = 0; $i < 30; $i++ ) {

         // obtain module number of this position ( 1- 863 )
         $module = $LAYOUT[$i][$j];

         // always black module

         if ( $module == -1 )
            $this->paintModule( $tmpx, $tmpy );

         // normal value (not white -0-)
		 // obtain bit value from $finalData

         if ( $module > 0 ) {
		 		    
            // calc. codeword index & bit that corresponds to this module
            $codeword = (int) floor( ( $module -1 ) / 6 );
            $bit = $module - $codeword * 6;

            // mask to extract current bit
            $bitValue = 0;
            switch( $bit ) {
			case 1: $bitValue = 32; break;
			case 2: $bitValue = 16; break;
			case 3: $bitValue = 8; break;
			case 4: $bitValue = 4; break;
			case 5: $bitValue = 2; break;
			case 6: $bitValue = 1; break;
			}

            // bit is 1, paint black module
	        if ( ord($finalData[$codeword]) & $bitValue ) 		
			   $this->paintModule( $tmpx, $tmpy );
         }
		 
         $tmpx += $this->WXpixels;
      }
      $even = ! $even;
      $tmpy += $this->Ypixels;
   }
}

////// PAINT MODULE

function paintModule( $x, $y  ) {

   for ( $i=0; $i < $this->patw; $i++ )
      for ( $j = 0; $j < $this->path; $j++) {
        if ( $this->pattern[$j][$i] == 1 ) $this->g->setPixel( $x+$i, $y+$j );
      }
}

// for debugging

function showCodes(&$s) {

   $codes = '';
   for ( $i=0; $i<strlen($s); $i++)
      $codes .= ' '.sprintf('%02d',ord($s[$i]));

   for ( $j=0; $j<12; $j++)	  {
      imageString( $this->g->img, 5, 0, $j*20, substr($codes, $j*36, 36), $this->g->useColor('RED') );
   }

}

}  // of class

?>