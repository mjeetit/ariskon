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

require("Graphics.inc");
require("RBarcode2D.inc");
require("MaxiCode_globals.inc");
require("MaxiCode_core.inc");
require("ReedMC.inc");

class MaxiCode extends BarCode2D
{

////////// WORKING VARIABLES

var $core;

// offsets used when more than one symbol
var $XOffset;
var $YOffset;

////////// SOME PARAMETERS

var $processTilde;
var $numberOfSymbols;
var $SALayout;
var $browserDPI;
var $oneMoreXPixel;
var $oneMoreYPixel;

var $LAYOUT2 = array (
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


///////// CONSTRUCTOR

function MaxiCode() {

   parent::BarCode2D();
   $this->core = new MaxiCodecore( $this->g );
   $this->setQuiteZone(0);
   $this->processTilde = TRUE;
   $this->numberOfSymbols = 8;
   $this->SALayout = 'LR';
   $this->browserDPI = 96;
   $this->oneMoreXPixel = 0;
   $this->oneMoreYPixel = 0;
}

//////// SET MAXICODE SPECIFIC PARAMETERS

//  Set resolution of the printer in dpi.
//  Calculates a number of parameters based on it

function setResolution($r) {

   if ( ! is_numeric($r) ) return FALSE;

   // fix unreasonable values
   if ( $r <200 ) $r = 200;
   if ( $r > 1200 ) $r = 1200;
   
   if ( ! $this->core->calcParams($r) ) {
      // invalid resolution, use the next 100 multiple 
	  // (all 100 multiples -should- work)
      $r = 100 * round( ( $r  / 100 ) + 0.5 );
	  $this->core->calcParams($r);
   }
}

function setBrowserDPI ( $dpi ) {
   if ( is_numeric($dpi) )
      $this->browserDPI = $dpi;
}

// user-provided hexagon printing pattern
// see MaxiCode_globals.inc for examples

function setHexagonPattern( $p ) {
   $this->core->pattern = $p;
}

// MaxiCode mode. Values range from 2 to 6.

function setMode($m) {
   if ( ! is_numeric($m) || $m < 2 || $m > 6 ) {
      $this->doWarning( 'W_INVALID_PARAMETER', "Invalid mode $m");
	  return;
   }
   
   $this->core->mode = $m;
}

// set to TRUE to replace ~999 by ascii char 999

function setProcessTilde( $pt ) {
   $this->processTilde = $pt;
}

// maximum number of symbols in structured appended mode

function  setNumberOfSymbols( $n ) {
    if ( ! is_numeric( $n ) || $n < 1 || $n > 8 ) {
       $this->doWarning( 'W_INVALID_PARAMETER',"Invalid number of symbols $n");
       return;
	}
    $this->numberOfSymbols = $n;
}

function setSALayout( $l ) {

if ( $l == 'LR' || $l == 'RL' || $l == 'TB' || $l == 'BT')
   $this->SALayout = $l;

}

////// ""Structured Carrier Message" parameters (modes 2 and 3)

function setPostalCode( $pc ) {
   if ( strlen( $pc ) > 0 )  $this->core->postalCode = $pc;
}

function setCountry( $c ) {
   if ( is_numeric($c) )  $this->core->country = $c;
}

function setServiceClass( $sc ) {
   if ( is_numeric($sc) )  $this->core->serviceClass = $sc;
}

/////////////////////////////
//       P A I N T
/////////////////////////////

function paint( $data, $filename='' ) {

   // INITIALIZATIONS

   $this->core->data = $data;
   if ( $this->processTilde ) $this->applyTilde( $this->core->data );
   
   if ( ! $this->verifyParameters() ) return NULL;
         
   // for modes 2 or 3, encode the Structured Carrier Message
   // that will be placed on the MaxiCode primary message 

   $primary = $this->core->createPrimary();
   if ( $primary === NULL ) {
	  $this->doError( $this->core->errorCode, $this->core->errorMsg);
	  return NULL;
   }   
   
   // obtain maximum codeword capacity of the symbol depending on the mode
   
   switch ( $this->core->mode ) {
   case 5:
      $max_cw = 77; // 9 primary + 68 secondary -Enhanced error correction
	  break;
   case 4:
   case 6:
      $max_cw = 93; // 9 primary + 84 secondary - Standard error correction
      break;
   default:
      $max_cw = 84; // only secondary - Standard error correction
	  break;
   }

   // several symbols: deduct 2 codewords used for Structured Append
   if ( $this->numberOfSymbols > 1 ) $max_cw = $max_cw - 2;		 

   // ENCODE PROVIDED DATA 

   // Encodes user data for each symbol (if Structured Append is used).
   // Encoded strings are stored in $encode_arr.
   // If the maximum number of symbols is filled, remaining data is ignored.
   // Also obtains $ns, which is the number of symbols effetively used, 
   // which can be less than the maximum provided by $this->numberOfSymbols.
   
   $ns = 0; 
   while( $ns < $this->numberOfSymbols ) {

      // end of data to encode, finish
      if ( $ns > 0 && strlen($this->core->data) == 0 ) break;
	  
	  $ns++;
	  	  
      // in case fits in one symbol, add two pad chars 
	  // to compensate initial $max_cw deduction
	  $plus2 = ( $ns == 1 && $this->numberOfSymbols > 1 ); 
	  
      if ( ( $encoded_arr[$ns] = $this->core->encode( $max_cw, $plus2 ) ) === NULL ){
	     $this->doError( $this->core->errorCode, $this->core->errorMsg );
		 return NULL;
	  }
   }
      
   if ( strlen($this->core->data) > 0 )
      $this->doError( 'W_TRUNCATED', 'Data truncated: Last '.strlen($this->core->data).' chars lost' );
   
   // use default of 300 dpi if resolution was not set
   if ( $this->core->resolution === NULL )
      $this->setResolution(300);
	  
   // calculate symbol size,
   // image size (may contain several symbols),
   // and initial offsets (where the first symbol will be placed)
   $symbol_w = $this->core->prefW + $this->leftMargin * 2;
   $symbol_h = $this->core->prefH + $this->topMargin * 2;
  
   if ( $this->browserDPI > 0 )
      $this->adjustToBrowserDPI( $symbol_w, $symbol_h );
  
   $img_w = $symbol_w;
   $img_h = $symbol_h;
   $this->XOffset = $this->leftMargin;
   $this->YOffset = $this->topMargin;

   if ( $ns > 1 ) {
   
      if ( $this->SALayout == 'LR' || $this->SALayout == 'RL' )
         $img_w = $symbol_w * $ns;
	  else
         $img_h = $symbol_h * $ns;

      if ( $this->SALayout == 'RL' )
	     $this->XOffset += $img_w - $symbol_w; 
   
      if ( $this->SALayout == 'BT' )
	     $this->YOffset += $img_h - $symbol_h; 
   }
	  
   //*** image initializations
   
   $this->g->create( $img_w, $img_h );

   // Initialize colors & set background color (this fills image)
   $this->bgColorId   = $this->g->useColor( $this->bgColorDesc ); 
   $this->barColorId  = $this->g->useColor( $this->barColorDesc ); 
   $this->g->setColor( $this->bgColorId );

   //*** more initializations
   // hexagon pattern to be used for painting modules
   $this->core->loadPattern();

   ////// COMPLETE ENCODING & DRAW SYMBOL

   for ( $i = 1; $i <= $ns; $i++ ) {
   
      // structured append codewords   
      if ( $ns > 1 )
         $sa = PADAB. $this->core->parseBits(
		    $this->core->getBits( $i -1, 3 )
			.$this->core->getBits( $ns -1, 3 ) 
			);
      else
         $sa= '';

      // build complete encoded string & add error correction 
	  // note $primary already contains mode in modes 2 & 3
	  //    but it is empty for modes >3 
	  // $sa is empty if only one symbol

	  $encoded = '';
      if ( $this->core->mode > 3 ) $encoded = chr( $this->core->mode );
      $encoded .= $primary . $sa . $encoded_arr[$i];
      $finalData = $this->core->addReedSolomon( $encoded );

	  // Paint one symbol
	  
      $this->drawFinderPattern();
         
      // Set bar color as it will be used from now on
      $this->g->setColor( $this->barColorId );
      $this->core->paintLayout( $finalData, $this->XOffset , $this->YOffset );

      // update offsets if more than one symbol
      switch ( $this->SALayout ) {
	     case 'LR': $this->XOffset += $symbol_w; break;
	     case 'RL': $this->XOffset -= $symbol_w; break;
	     case 'TB': $this->YOffset += $symbol_h; break;
	     case 'BT': $this->YOffset -= $symbol_h; break;   
      }

      #if ( $i >0 ) $this->core->showCodes($finalData);  // debug
   }

 
   // all done, generate image     
  $this->result = $this->g->outputImage( md5($data), $filename );
  return $this->result;
}


///////////////////////////////////////////////////////////
//       OTHER USER FUNCTIONS THAT CAN BE USED AFTER PAINT
///////////////////////////////////////////////////////////

function getImgTagWidth() {
   return round( $this->g->width * $this->browserDPI / $this->core->resolution );
}

function getImgTagHeight() {
   return round( $this->g->height * $this->browserDPI / $this->core->resolution );
}

function getImgTag( $src=NULL ) {
   
   if ( $src === NULL ) $src = $this->result;
   return "<img src=\"$src\" width=\"".$this->getImgTagWidth()
      ."\" height=\"".$this->getImgTagHeight().
	  "\" border=\"0\" alt=\"MaxiCode Symbol\" />\n";
}

///////////////////////////////////////////////////////////
//       AUXILIARY FUNCTIONS 
///////////////////////////////////////////////////////////

////////////////////////// VERIFY / ADJUST PARAMETER VALUES

function verifyParameters() {
  
   // data to encode
   if ( $this->core->mode > 3 ) { 
      if ( strlen( $this->core->data ) == 0 ) {
         $this->doError( 'E_NO_DATA', 'No data to encode.' );
	     return FALSE;
      }	else {
	     return TRUE;
	  }
   }

   // alias
   $pc = &$this->core->postalCode;
	  
   // postal code not set, try extract primary messsage fields 
   // from provided data string to encode
   
   if ( ! $pc ) 
      if ( ! $this->extractPrimaryData() )
	     return FALSE;

   // if alphanumeric postal code, switch to mode 3
   if ( $this->core->mode == 2 && ! is_numeric($pc) ) {
      $this->setMode(3);
      $this->doError( 'N_MODE_CHANGED', "Non numeric postal code $pc, switched to mode 3.");		 
   }

   // Verify / adjust Postal Code 
   
   if ( $this->core->mode == 2 ) {
      if ( $pc <= 0 || $pc > pow(2,30) ) {
         $this->doError( 'E_INVALID_DATA', "Invalid postal code for mode 2: $pc");
	     return FALSE;
	  }
   } else {
      // truncate or pad to 6 chars
      $pc = str_pad( substr( $pc, 0, 6 ), 6 );
   }
	  
   // verify Country 
   $c = &$this->core->country;
   if ( ! is_numeric($c) || $c <= 0 || $c > pow(2,10) ) {
      $this->doError( 'E_INVALID_DATA', "Invalid country code: $c");
      return FALSE;
   }	  
  
   // verify Service Class
   
   $sc = &$this->core->serviceClass;
   if ( ! is_numeric($sc) || $sc <= 0 || $sc > pow(2,10) ) {
      $this->doError( 'E_INVALID_DATA', "Invalid service class: $sc");
	  return FALSE;
   }	  
   
   // all verifications passed
   return TRUE; 
}

////////// EXTRACT PRIMARY DATA

// Obtains Structured Carrier Message fields (postal code, country and service)
// when included in data starting with the sequence "[)<RS>01<GS>" 

function extractPrimaryData() {
   
   $data = &$this->core->data;
   
   if ( substr( $data, 0 , 7 ) == '[)>'.RS.'01'.GS ) {
      $start = 9;
      $isec = substr( $data, 0, 9 );    
	  $data = substr( $data, 9 );
   } else {
      $start = 0;
      $isec = '';      
   }

   $fields = explode( GS, $data, 4 );

   if ( count( $fields ) < 3 ) {
      $this->doError( 'E_INVALID_DATA', 'Some mandatory field is missing' );
	  return FALSE;
   }

   $this->core->postalCode = $fields[0];
   $this->core->country = $fields[1];
   $this->core->serviceClass = $fields[2];

   // secondary message
   if ( count( $fields ) == 3 )
      $data = '';   
   else
      $data = $isec . $fields[3];

   return TRUE;
}

////////// APPLY TILDE

function applyTilde( &$data ) {

   $len = strlen($data);
   $result = '';
   $i = 0;

   while ( $i < $len ) {
        
      // search next tilde
      $j = strpos( $data, '~' , $i );
	  
	  // no more tildes or no room for three digits, finish
	  if ( $j === FALSE || $j + 3 >= $len  ) {
	     $result .= substr( $data, $i );
		 break;
	  }

      // add chars until tilde		 
      if ( $j > $i ) {
	     $result .= substr( $data, $i, $j - $i );
         $i = $j;
	  }

      // check if a valid 3 digits number follows
      $ascii = substr( $data, $j+1, 3 );
	  if ( ! is_numeric( $ascii ) || $ascii > 255 ) {
	     $result .= '~';
		 $i++;
		 continue;
	  }

      // OK, found.
	  
	  $result .= chr($ascii);
	  $i += 4;	       	 
   }

   $data = $result;
}


////////// DRAW FINDER PATTERN

function drawFinderPattern() {

   $this->g->setColor( $this->barColorId );
   $this->finderCircle( 3.87 );
   $this->g->setColor( $this->bgColorId );
   $this->finderCircle( 3.20 );
   $this->g->setColor( $this->barColorId );   
   $this->finderCircle( 2.53 );
   $this->g->setColor( $this->bgColorId );
   $this->finderCircle( 1.86 );
   $this->g->setColor( $this->barColorId );   
   $this->finderCircle( 1.18 );
   $this->g->setColor( $this->bgColorId );
   $this->finderCircle( 0.51 );
}

function finderCircle( $r ) {

   $this->g->circle( 
      $this->core->centerX + $this->XOffset,
      $this->core->centerY + $this->YOffset,
      round( $r * $this->core->dpm )
   );
}

//////////// ADJUSTMENTS TO BROWSER PRINTING DENSITY (DOTS-PER-INCH)

function  adjustToBrowserDPI( &$symbol_w, &$symbol_h ) {

   // obtain additional pixels to add
   // second parameter : maximum MaxiCode size in mm plus a 5%
   // (we can do it greater than maximum as we only increase the quite zone)
   $xadd = $this->adjustSize( $symbol_w, 29.79 * 1.05 ); 
   $yadd = $this->adjustSize( $symbol_h, 28.49 * 1.05 ); // max height in mm   

   // add adjusment pixels to symbol size
   $symbol_w += $xadd;
   $symbol_h += $yadd;
      
   // increase margins to keep symbol centered
   $this->leftMargin += (int) ( $xadd  / 2 );
   $this->topMargin += (int) ( $yadd  / 2 );
}

function adjustSize( $actual, $max_mm ) {

   // minumm & maximium values for HTML IMG tag
   $tag_min = (int) round( $actual * $this->browserDPI / $this->core->resolution );
   $tag_max = (int) ( ($max_mm/25.5) * $this->browserDPI );    
   
   $difer = 10;
   $best = $actual;
   
   for ( $i = $tag_min; $i <= $tag_max; $i++ ) {  
      $new = (int) 0;
      $new = $i * $this->core->resolution / $this->browserDPI;
	  #echo "<br> NEW $i - $new ";
      if ( is_int( $new ) )
         // found exact match
         return $new - $actual;
	  else {
	     $new_difer = $new - (int) $new;
		 $new = (int) $new;
		 if ( $new_difer > 0.5 ) {
		    $new++;
			$new_difer = 1 - $new_difer;
		 }
		 #echo " difer $new_difer";

		 if ( $new_difer < $difer ) {
		    $difer = $new_difer;
			$best = $new;
			#echo " -- best $best";
		}  

	  }
	  
   }
   // none exact found, use smaller
   return $best - $actual;
}

} // of class MaxiCode

?>
