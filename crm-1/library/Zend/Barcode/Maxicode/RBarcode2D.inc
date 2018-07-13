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

/* Main Abstract Class */

class BarCode2D1
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

##### ERROR HANDLING

var $errorShowModes;
var $errorLogModes;
var $errorCode;
var $errorMsg;

/// CONSTRUCTOR

function BarCode2D1() {

   $this->g = new Graphics1;
   $this->setBGColor('WHITE');
   $this->setBarColor('BLACK');
   $this->logToImg = $this->logToPhp = 
      E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE;
	  
   $this->errorShowModes = array( 'IMG', 'IMG', NULL );
   $this->errorLogModes = array( 'PHP', 'PHP', 'PHP' );
   $this->errorTypes = array( 'ERROR', 'WARNING', 'NOTICE' );
   $this->errorCode = NULL;
   $this->errorMsg = NULL;
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

/////// ERROR HANDLING

function setErrorShowMode( $types, $mode, $predefined=NULL, $image_file=NULL ) {

   switch ( $mode ) {
   case 'TXT': break;
   case 'IMG': break;
   case 'NO': $mode = NULL; break;
   default: return; // invalid mode
   }

   for ( $i = 0; $i < strlen($types); $i++ ) {
      
	  switch ( $types[$i] ) {
	  case 'E': $j = 0; break;
	  case 'W': $j = 1; break;
	  case 'N': $j = 2; break;
	  default: continue;  // invalid error type, skip.
	  }
	  
      if ( $predefined || $image_file )
	    $this->errorShowModes[$j] = array( $mode, $predefined, $image_file );
      else 
        $this->errorShowModes[$j] = $mode;	  
   }

}

function setErrorLogMode( $types, $mode, $file=NULL ) {

   switch ( $mode ) {
   case 'PHP': break;
   case 'FILE': break;
   case 'NO': $mode = NULL; break;
   default: return; // invalid mode
   }

   for ( $i = 0; $i < strlen($types); $i++ ) {
      
	  switch ( $types[$i] ) {
	  case 'E': $j = 0; break;
	  case 'W': $j = 1; break;
	  case 'N': $j = 2; break;
	  default: continue;  // invalid error type, skip.
	  }
	  
      if ( $file )
	    $this->errorLogModes[$j] = array( $mode, $file );
      else 
        $this->errorLogModes[$j] = $mode;	  
   }

}


function getErrorCode() {
   return $this->errorCode;
}

function getErrorMsg() {
   return $this->errorMsg;
}

function doError( $code, $msg ) {
   
   $this->errorCode = $code;
   $this->errorMsg = $msg;
   switch ( $code[0] ) {
   case 'W': $type = 1; break;
   case 'N': $type = 2; break;
   case 'E': // continues below
   default: $type = 0;
   }
   $log = $this->errorLogModes[$type];
   $show = $this->errorShowModes[$type];
   $msg2 = $this->errorTypes[$type] . ": $msg"; 

   // Show   

   $predefined = NULL;
   $image_file = NULL;

   if ( is_array($show) ) {
      $predefined = $show[1];
	  $image_file = $show[2];
	  $show = $show[0];
   }

   switch( $show ){

   case 'TXT': 
      if ( $predefined )
         echo $predefined;
      else
	     echo "$msg2<br>";
      $this->g->showImg= FALSE;
	  break;

   case 'IMG': 
      if ( $image_file )
	     $this->g->errorImg = $image_file;

	  if ( $predefined === NULL )
         $this->g->errorMsg = $msg2;
      else
         $this->g->errorMsg = $predefined;
		 
      if ( $type == 0 ) $this->g->outputImage('ERROR');
	  break;
   }
 
   // Log 

   if ( is_array($log) ) {
      $file = $log[1];
	  $log = $log[0];
   }  else
      $file = NULL;

   if ( $log ) {
      switch ($type) {
	  // errors logged as PHP warnings so execution is not stopped
	  case 0: $php_type = E_USER_WARNING; break;
	  case 1: $php_type = E_USER_NOTICE; break;
  	  case 2: $php_type = E_USER_NOTICE; break;
	  }
   }
   
   switch( $log ) {
   case 'PHP': 
      // only log, avoid displaying PHP errors
	  // ( to see them just activate one of the error show options )
	  ini_set('display_errors', FALSE);
      trigger_error( "RMaxiCode $msg2", $php_type );
	  break;
   case 'FILE': 
      if ( $file )  error_log( date('d-M-y h:j:s')." $msg2\n", 3, $file );
	  break;
   }

}

} // of class RBarCode2D

?>
