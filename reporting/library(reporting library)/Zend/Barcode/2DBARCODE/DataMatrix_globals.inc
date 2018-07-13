<?php

//  RDataMatrix for PHP
//  Copyright (C) Java4Less.com
//  All rights reserved 
//
// Adquisition , use and distribution of this code is subject to restriction:
//  - You may modify the source code in order to adapt it to your needs.
//  - Redistribution of this (or a modified version) source code is prohibited.
//  - You may not remove this notice from the source code.
//  - This notice disclaim all warranties of all material.
//  - You may not copy and paste any code into external files.
//  - Use of this software on more than one server
//    requires the appropriate license.

define("ROW",0);
define("COL",1);
define("ROWDATA",2);
define("COLDATA",3);
define("REGIONS",4);
define("ROWMAP",5);
define("COLMAP",6);
define("TOTALDATA",7);
define("TOTALERR",8);
define("REEDDATA",9);
define("REEDERR",10);
define("REEDBLOCKS",11);

// datamatrix codes

define("C40_PAD",0);
define("PAD",129);
define("LATCH_C40",230);
define("LATCH_BASE256",231);
define("FNC1",232);
define("STRUCTURED_APPEND",233);
define("READER_PROGRAMMING",234);
define("SHIFT",235);
define("MACRO5",236);
define("MACRO6",237);
define("LATCH_TEXT",239);
define("ECI",241);
define("UNLATCH",254);

/**
 * ascii encoding (see Data Matrix Manual for more information ).
   This is appropiate for data that contains mainly ascii characters (1-127).
 */
define("E_ASCII",0);

/**
 * c40 encoding (see Data Matrix Manual for more information ). 
   This is appropiate for data that contains mainly upper case characters and digits.
 */
define("E_C40",1);

/**
 * text encoding (see Data Matrix Manual for more information ). 
   This is appropiate for data that contains mainly lower case characters and digits.
 */
define("E_TEXT",2);

/**
 * base 256 encoding. This is mainly used for binary data.
 */
define("E_BASE256",3);

/**
 *
 */
define("E_NONE",4);

/**
 * automatic encoding. The class decides dinamically which encoding 
   is more apporpiate for the data you want to encode.
 */
define("E_AUTO",5);


$CONFIGURATION = Array(
	Array(10,10,8,8,1,8,8,3,5,3,5,1), //10X10
	Array(12,12,10,10,1,10,10,5,7,5,7,1), // 12 X 12 ....
	Array(14,14,12,12,1,12,12,8,10,8,10,1),
	Array(16,16,14,14,1,14,14,12,12,12,12,1),
	Array(18,18,16,16,1,16,16,18,14,18,14,1),
	Array(20,20,18,18,1,18,18,22,18,22,18,1),
	Array(22,22,20,20,1,20,20,30,20,30,20,1),
	Array(24,24,22,22,1,22,22,36,24,36,24,1),
	Array(26,26,24,24,1,24,24,44,28,44,28,1),
	Array(32,32,14,14,4,28,28,62,36,62,36,1),
	Array(36,36,16,16,4,32,32,86,42,86,42,1),
	Array(40,40,18,18,4,36,36,114,48,114,48,1),
	Array(44,44,20,20,4,40,40,144,56,144,56,1),
	Array(48,48,22,22,4,44,44,174,68,174,68,1),
	Array(52,52,24,24,4,48,48,204,84,102,42,2),
	Array(64,64,14,14,16,56,56,280,112,140,56,2),
	Array(72,72,16,16,16,64,64,368,144,92,36,4),
	Array(80,80,18,18,16,72,72,456,192,114,48,4),
	Array(88,88,20,20,16,80,80,576,224,144,56,4),
	Array(96,96,22,22,16,88,88,696,272,174,68,4),
	Array(104,104,24,24,16,96,96,816,336,136,56,6),
	Array(120,120,18,18,36,108,108,1050,496,175,68,6),
	Array(132,132,20,20,36,120,120,1304,496,163,62,8),
	Array(144,144,22,22,36,132,132,1558,620,156,62,10),
	Array(8,18,6,16,1,6,16,5,7,5,7,1),
	Array(8,32,6,14,2,6,28,10,11,10,11,1),
	Array(12,26,10,24,1,10,24,16,14,16,14,1),
	Array(12,36,10,16,2,10,32,22,18,22,18,1),
	Array(16,36,14,16,2,14,32,32,24,32,24,1),
	Array(16,48,14,22,2,14,44,49,28,49,28,1));

$C40CODES = Array(
   Array(0,0),Array(0,1),Array(0,2),Array(0,3),Array(0,4),
   Array(0,5), Array(0,6),Array(0,7),Array(0,8),Array(0,9),
   Array(0,10),Array(0,11),Array(0,12),Array(0,13),Array(0,14),
   Array(0,15),Array(0,16),Array(0,17),Array(0,18),Array(0,19),
   Array(0,20),Array(0,21),Array(0,22),Array(0,23),Array(0,24),
   Array(0,25),Array(0,26),Array(0,27),Array(0,28),Array(0,29),
   Array(0,30),Array(0,31),Array(3),
   Array(1,0),Array(1,1),Array(1,2),Array(1,3),Array(1,4),
   Array(1,5),Array(1,6),Array(1,7),Array(1,8),Array(1,9),
   Array(1,10),Array(1,11),Array(1,12),Array(1,13),Array(1,14),
   Array(4),Array(5),Array(6),Array(7),Array(8),Array(9),
   Array(10),Array(11),Array(12),Array(13),
   Array(1,15),Array(1,16), Array(1,17),Array(1,18),Array(1,19),
   Array(1,20),Array(1,21),
   Array(14),Array(15),Array(16),Array(17),Array(18),Array(19),
   Array(20),Array(21),Array(22),Array(23),Array(24),
   Array(25),Array(26),Array(27),Array(28),Array(29),
   Array(30),Array(31),Array(32),Array(33),Array(34),
   Array(35),Array(36),Array(37),Array(38),Array(39),
   Array(1,22),Array(1,23),Array(1,24),Array(1,25),Array(1,26),
   Array(2,0),Array(2,1),Array(2,2),Array(2,3), Array(2,4),
   Array(2,5),Array(2,6),Array(2,7),Array(2,8),Array(2,9),
   Array(2,10),Array(2,11),Array(2,12),Array(2,13),Array(2,14),
   Array(2,15),Array(2,16),Array(2,17),Array(2,18),Array(2,19),
   Array(2,20),Array(2,21),Array(2,22),Array(2,23),Array(2,24),
   Array(2,25),Array(2,26),Array(2,27),Array(2,28),Array(2,29),
   Array(2,30),Array(2,31),
   Array(1,30,0,0),Array(1,30,0,1), Array(1,30,0,2),Array(1,30,0,3),
   Array(1,30,0,4),Array(1,30,0,5),Array(1,30,0,6),Array(1,30,0,7),
   Array(1,30,0,8),Array(1,30,0,9),Array(1,30,0,10),Array(1,30,0,11),
   Array(1,30,0,12),Array(1,30,0,13),Array(1,30,0,14),Array(1,30,0,15),
   Array(1,30,0,16),Array(1,30,0,17),Array(1,30,0,18),Array(1,30,0,19),
   Array(1,30,0,20),Array(1,30,0,21), Array(1,30,0,22),Array(1,30,0,23),
   Array(1,30,0,24),Array(1,30,0,25),Array(1,30,0,26),Array(1,30,0,27),
   Array(1,30,0,28),Array(1,30,0,29),Array(1,30,0,30),Array(1,30,0,31),
   Array(1,30,3),
   Array(1,30,1,0),Array(1,30,1,1),Array(1,30,1,2),Array(1,30,1,3),
   Array(1,30,1,4),Array(1,30,1,5),Array(1,30,1,6),Array(1,30,1,7),
   Array(1,30,1,8), Array(1,30,1,9),Array(1,30,1,10),Array(1,30,1,11),
   Array(1,30,1,12),Array(1,30,1,13),Array(1,30,1,14),
   Array(1,30,4),Array(1,30,5),Array(1,30,6),Array(1,30,7),
   Array(1,30,8),Array(1,30,9),Array(1,30,10),Array(1,30,11),
   Array(1,30,12),Array(1,30,13),Array(1,30,1,15),Array(1,30,1,16),
   Array(1,30,1,17),Array(1,30,1,18), Array(1,30,1,19),Array(1,30,1,20),
   Array(1,30,1,21),
   Array(1,30,14),Array(1,30,15),Array(1,30,16),Array(1,30,17),
   Array(1,30,18),Array(1,30,19),Array(1,30,20), Array(1,30,21),
   Array(1,30,22),Array(1,30,23),Array(1,30,24),Array(1,30,25),
   Array(1,30,26),Array(1,30,27),Array(1,30,28),Array(1,30,29),
   Array(1,30,30), Array(1,30,31),Array(1,30,32),Array(1,30,33),
   Array(1,30,34),Array(1,30,35),Array(1,30,36),Array(1,30,37),
   Array(1,30,38),Array(1,30,39),
   Array(1,30,1,22), Array(1,30,1,23),Array(1,30,1,24),Array(1,30,1,25),
   Array(1,30,1,26),
   Array(1,30,2,0),Array(1,30,2,1),Array(1,30,2,2),Array(1,30,2,3),
   Array(1,30,2,4), Array(1,30,2,5), Array(1,30,2,6),Array(1,30,2,7),
   Array(1,30,2,8),Array(1,30,2,9),Array(1,30,2,10),Array(1,30,2,11),
   Array(1,30,2,12),Array(1,30,2,13),Array(1,30,2,14),Array(1,30,2,15),
   Array(1,30,2,16),Array(1,30,2,17),Array(1,30,2,18),Array(1,30,2,19),
   Array(1,30,2,20),Array(1,30,2,21),Array(1,30,2,22),Array(1,30,2,23),
   Array(1,30,2,24),Array(1,30,2,25),Array(1,30,2,26),Array(1,30,2,27),
   Array(1,30,2,28),Array(1,30,2,29),Array(1,30,2,30),Array(1,30,2,31)
   );

?>
