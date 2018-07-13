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

$stdColors = array( 
   "WHITE"  => array( 255, 255, 255 ),
   "RED"    => array( 255,   0,   0 ),
   "GREEN"  => array(   0, 255,   0 ),
   "BLUE"   => array(   0,   0, 255 ),
   "YELLOW" => array( 255, 255,   0 ),
   "CYAN"   => array(   0, 255, 255 ),
   "ORANGE" => array( 255, 128,   0 ),
   "GRAY"   => array( 128, 128, 128 ),
   "BLACK"  => array(   0,   0,   0 ) );


function arrayKeyExists( &$key, &$arr ) {

  if ( function_exists('array_key_exists') )
    return array_key_exists( $key, $arr );
  else
    return key_exists( $key, $arr );
}

/*********** GRAPHICS CLASS **********

This class provides basicallly functions for:

- creating the image object
- draw bars on it (rectangles)
- draw text
- rotate de image
- reduce its size

*************************************/

class Graphics 
{

##### GENERAL

// image resource to be used
var $img;

// Type (GIF;JPEG;PNG;WBMP)
var $imgType; 
var $imgMime; 
var $imgQuality; 

var $filePath;

// witdh & height of img 
var $width;
var $height;

// flag for vertical drawing
var $rotate;
var $vertical;

##### COLORS
var $stdColors;
var $colorIds;
var $currentColor;
 
########## CONSTRUCTOR

function Graphics() {

   $this->setType("PNG");
   $this->setRotation(0);
}

########## WHOLE IMAGE FUNCTIONS
################################

########## CREATE (INITIALIZE) IMAGE

// Note that rotation must be set before calling this function.

function create($w,$h) {

   if ( $this->vertical ) {
      $this->width  = $h;
      $this->height = $w;
   } else {
      $this->width  = $w;
      $this->height = $h;
   }

   $this->img = imageCreate($this->width,$this->height);
   $this->colorIds = array();
}

function setType($t,$q=0) {

   $t = strtolower($t);

   switch ( $t ) {
   case "gif": 
   case "jpeg": 
   case "png": 
   case "wbmp": 
      if ( function_exists( "image".$t ) ) {
         $this->imgType = $t;
         $this->imgQuality = 100;
         if ( $q > 0 and $t == "jpeg" ) $this->imgQuality = $q;
         if ( $t == "wbmp" ) 
            $this->imgMime = "vnd.wap.wbmp";
         else
            $this->imgMime = $t;
      }
      break;
   }
}

function setFilePath( $fp ) {

   $this->filePath = $fp;
}

########## REDUCE IMAGE'S SIZE

function reduceSize( $w, $h ) {

   $x = 0;
   $y = 0;
   if ( $this->rotate != 0 )
      $this->rotateRect($x,$y,$w,$h);
   
   $reduced = imageCreate( $w, $h );
   imagecopy( $reduced, $this->img, 0,0, $x,$y, $w, $h );
   ImageDestroy($this->img); 
   $this->img = $reduced;
}

########## OUTPUT IMAGE TO BROWSER OR FILE

function outputImage( $code, $filename ) {

   if ( empty( $this->filePath ) ) {
     #Header( "Content-type:  text/plain"); 
     Header( "Content-type:  image/".$this->imgMime); 
   } else {
      if ( empty($filename) ) $filename = "$code.".$this->imgType;
      $filename = $this->filePath.$filename;
   }

   $func = "image".$this->imgType;
  
   if ( $this->imgQuality < 100 ) 

      // Output JPEG with lower quality to browser or file
	  
      $func($this->img, $filename,$this->imgQuality); 

   else {
      if ( $filename ) 

         // Output image to file
		
         $func($this->img, $filename); 
      else

         // Output image to browser

         $func($this->img); 
   }

   ImageDestroy($this->img);
   return $filename;
}

########## COLOR RELATED FUNCTIONS
##################################

##### USE COLOR

/** This function returns a color ID that will be used by setColor()

  It accepts a color description parameter. It can have to formats :

  - Any standard color defined in $stdColors
  - RGB values as a comma-separated string. e.g. "255,255,255"

**/


function useColor($desc) {

   if ( ! arrayKeyExists( $desc, $this->colorIds ) ) {

      // Color not used previously -- obtain its RGB numbers
      // and create a new color Id for it (allocate it)

      global $stdColors;

      if ( arrayKeyExists( $desc, $stdColors ) )

         // It's a standard color, obtain its RGB values from $stdColors[]

         $rgb = $stdColors[$desc];

      else {

         // Not in standard list, see if we have a string with 
         // comma-separated RGB values

         $rgb = explode( ",", $desc );
         $ok = FALSE;
         if ( is_array( $rgb ) ) {
            if ( count($rgb) == 3 ) {
               if ( $rgb[0] < 255 & $rgb[1] < 255 & $rgb[2] < 255 ) {
                   $ok = TRUE;
               }
            }
         }          
         // if we fail to get RGB numbers, use red color as an error flag 
         if ( ! $ok ) $desc = "RED"; 
      }
       
      // allocate color and store obtained Id

      $this->colorIds[$desc] = 
         ImageColorAllocate( $this->img, $rgb[0], $rgb[1], $rgb[2] );
   }

   // Retun color Id, either newly obtained or previously stored.
   return $this->colorIds[$desc];
}

##### SET COLOR

// Sets the color to be used from now on.
// Paramenter must be a color Id returned by useColor()

function setColor( $c ) {
   $this->currentColor = $c;
}



########## BAR DRAWING FUNCTIONS
################################

##### FILL RECTANGLE

function fillRect($x,$y,$w,$h) {

  if ( $this->rotate != 0 )
    $this->rotateRect($x,$y,$w,$h);

   #echo "          x = $x; y = $y; w = $w; h = $h; <br>";

   imagefilledrectangle( $this->img, $x, $y, $x+$w-1, $y+$h-1, $this->currentColor);
}

########## ROTATION FUNCTIONS
#############################

##### SET ROTATION

function setRotation( $r ) {

   // only 0,90,180,270
   if ( $r > 270 ) return;
   if ( $r % 90 != 0 ) return;

   $this->rotate = $r;
   $this->vertical = ( $r == 90 or $r == 270 );
}

##### ROTATE RECTANGLE

function rotateRect( &$x, &$y, &$w, &$h ) {

   switch( $this->rotate ) {
   case 90:
      $x0 = $x; 
      $x = $y; 
      $y = $this->height - $x0 - $w;
      break;
   case 180:
      $x = $this->width - $x - $w;
      $y = $this->height - $y - $h;
      break;
   case 270:
      $y0 = $y; 
      $y = $x;
      $x = $this->width - $y0 - $h;
      break;
   }

   switch( $this->rotate ) {
   case 90:
   case 270:
      $w0 = $w;
      $w = $h;
      $h = $w0;
      break;
   } 
}

##### CAN ROTATE FONT

function canRotateFont() {

if ( empty($this->fontFile) )
   return ( $this->rotate <= 90 );

return TRUE;

}


##### ROTATE FONT

function rotateFont( &$x, &$y, &$s ) {

   switch( $this->rotate ) {
   case 90:
      $x0 = $x; 
      $x = $y; 
      $y = $this->height - $x0;
      break;
   case 180:
      $x = $this->width - $x;
      $y = $this->height - $y;
      break;
   case 270:
      $y0 = $y; 
      $y = $x;
      $x = $this->width - $y0;
      break;
   }
}


} // of class Graphics

?>
