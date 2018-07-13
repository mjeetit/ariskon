<?php

//  RDataMatrix for PHP
//  Copyright (C) Java4Less.com
//  All rights reserved
//
//  Acquisition, use and distribution of this code is subject to restriction:
//  - You may modify the source code in order to adapt it to your needs.
//  - Redistribution of this (or a modified version) source code is prohibited.
//  - You may not remove this notice from the source code.
//  - This notice disclaim all warranties of all material.
//  - You may not copy and paste any code into external files.
//  - Use of this software on more than one server
//    requires the appropriate license.

global $stdColors, $CONFIGURATION, $C40CODES;


require("Graphics.inc");
require("RBarcode2D.inc");
require("DataMatrix_globals.inc");
require("DataMatrix_core.inc");
require("ReedDM.inc");
require("CharPlacer.inc");

class DataMatrixClass extends BarCode2D
{

var $core;

//////// CONSTRUCTOR

function DataMatrixClass() {

   parent::BarCode2D();
   $this->core = new DataMatrixcore( $this->g );
   $this->setQuiteZone(6);
}

//////// SET DATA MATRIX SPECIFIC PARAMETERS

function setModuleSize( $s ) {

   $s = (int) $s;
   if ( $s > 0 & $s < 40 )
     $this->core->dotPixels = (int) $s;
}

function setTilde( $t ) {

   $this->core->processTilde = ( $t == TRUE );
}

function setEncoding( $e ) {

   switch( $e ) {
   case "C40": $e = E_C40; break;;
   case "TEXT": $e = E_TEXT; break;;
   case "BASE256": $e = E_BASE256; break;;
   case "ASCII": $e = E_ASCII; break;;
   default: $e = E_AUTO; break;;
   } 

   $this->core->encoding = $e;
   //$this->core->currentEncoding = $e;
}

function setFormat( $f ) {
	
   $fid = -1; // auto

   switch ( $f ) {
   case "8x18": $fid = 24; break;;
   case "8x32": $fid = 25; break;;
   case "12x26": $fid = 26; break;;
   case "12x36": $fid = 27; break;;
   case "16x36": $fid = 28; break;;
   case "16x48": $fid = 29; break;;
   default:

      $f = (int) $f;

      switch ( $f ) {
      case 10: $fid = 0; break;
      case 12: $fid = 1; break;
      case 16: $fid = 3; break;
      case 18: $fid = 4; break;
      case 20: $fid = 5; break;
      case 22: $fid = 6; break;
      case 24: $fid = 7; break;
      case 26: $fid = 8; break;
      case 32: $fid = 9; break;
      case 36: $fid = 10; break;
      case 40: $fid = 11; break;
      case 44: $fid = 12; break;
      case 48: $fid = 13; break;
      case 52: $fid = 14; break;
      case 64: $fid = 15; break;
      case 72: $fid = 16; break;
      case 80: $fid = 17; break;
      case 88: $fid = 18; break;
      case 96: $fid = 19; break;
      case 104: $fid = 20; break;
      case 120: $fid = 21; break;
      case 132: $fid = 22; break;
      case 144: $fid = 23; break;
      }
   }

   $this->core->preferredFormat = $fid;
}

///////// PAINT

function paint( $code, $filename='' ) {

   if (strlen($code)==0) return ;

   $this->core->code = $code;
   $this->core->topMarginPixels = $this->topMargin;
   $this->core->leftMarginPixels = $this->leftMargin;

   $this->core->doCode();

   // calulate sizes & create graphic container
   // rotation must be defined from the begining

   $w = $this->leftMargin * 2 + $this->core->cols * $this->core->dotPixels;
   $h = $this->topMargin * 2 + $this->core->rows * $this->core->dotPixels;

   $this->g->create($w,$h);

   // Initialize colors & set background color (this fills image)
   $this->bgColorId   = $this->g->useColor( $this->bgColorDesc ); 
   $this->barColorId  = $this->g->useColor( $this->barColorDesc ); 
   $this->g->setColor( $this->bgColorId );

   // Set bar color as it will be used from now on
   $this->g->setColor( $this->barColorId );

   // Paint it

   $this->core->paint();
   return $this->g->outputImage( $code, $filename );
}


} // of class DataMatrix

?>
