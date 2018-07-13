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

/* Main Abstract Class */

class BarCode2D
{

/* Graphic Container where de barcode will be drawn */
var $g;
var $rotation;

/** top & left margins of the barcode (also know as quite zone). 
 */
var $topMargin;
var $leftMargin;

##### COLORS

// Description and color identifier (used by graphic funcions)
// for background and bars 

var $bgColorDesc;
var $bgColorId;
var $barColorDesc;
var $barColorId;

/// CONSTRUCTOR

function BarCode2D() {

   $this->g = new Graphics;
   $this->setBGColor('WHITE');
   $this->setBarColor('BLACK');
}

/// PARAMETERS

function setQuiteZone( $m ) {
   $this->topMargin  = (int) $m;
   $this->leftMargin = (int) $m;
}

function setTopMargin( $m ) {
   $this->topMargin  = (int) $m;
}

function setLeftMargin( $m ) {
   $this->leftMargin = (int) $m;
}

function setBGColor( $c ) {
  $this->bgColorDesc = $c;
}

function setBarColor( $c ) {
  $this->barColorDesc = $c;
}

function setRotation( $r ) {
  $this->g->setRotation( $r );
}

function setImageType( $t, $q=0 ) {
   $this->g->setType($t,$q);
}

function setFilePath( $fp ) {
   $this->g->setFilePath( $fp );
}


} // of class RBarCode2D

?>
