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


function arrayKeyExists1( &$key, &$arr ) {

  if ( function_exists('array_key_exists') )
    return array_key_exists( $key, $arr );
  else
    return key_exists( $key, $arr );
}

/*********** GRAPHICS CLASS **********

This class provides basicallly functions for:

- creating the image object
- draw pixels or bars (rectangles) on it 
- draw text
- rotate the image
- reduce its size
- show eror message if required

*************************************/

class Graphics1 
{

    var $stdColors1 = array(
   "WHITE"  => array( 255, 255, 255 ),
   "RED"    => array( 255,   0,   0 ),
   "GREEN"  => array(   0, 255,   0 ),
   "BLUE"   => array(   0,   0, 255 ),
   "YELLOW" => array( 255, 255,   0 ),
   "CYAN"   => array(   0, 255, 255 ),
   "ORANGE" => array( 255, 128,   0 ),
   "GRAY"   => array( 128, 128, 128 ),
   "BLACK"  => array(   0,   0,   0 ) );

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

// error related
var $errorMsg;
var $errorImg;
var $showImg;
 
########## CONSTRUCTOR

function Graphics1() {

   $this->img = NULL;
   $this->filePath = NULL;
   $this->setType("PNG");
   $this->setRotation(0);
   $this->errorMsg = NULL;
   $this->errorImg = NULL;
   $this->showImg = TRUE;
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

function outputImage( $code, $filename='' ) {

   // output to browser but don't show image, do nothing
   // return NULL as image won't show
   // ( output already started by some error message for instance )
   if ( $this->filePath === NULL && ! $this->showImg ) return NULL;

   if ( $this->errorMsg || $this->errorImg ) $this->drawErrorImg();

   if ( $this->filePath === NULL ) {
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

########## DRAW ERROR IMAGE (new or added to current one)

function drawErrorImg() {

   // image for errors set, discard what we may have and use it

   if ( $this->errorImg ) {

      $img_type = NULL;

      if ( is_file($this->errorImg) ) {
         // obtain & verify file extension
         $img_type = strtolower( substr( strchr($this->errorImg,'.'), 1) );
         switch ( $img_type ) {
         case 'png': break;
         case 'jpeg': break;
         case 'jpg': $img_type = 'jpeg'; break;
         case 'gif': break;
		 default: $img_type = NULL;
         }
      }

	  if ( $img_type ) {
         // replace/create image with the one from file 
         if ( $this->img ) ImageDestroy($this->img);
		 $imgfunc = "imagecreatefrom$img_type";
		 $this->img = $imgfunc($this->errorImg);	  
         $this->colorIds = array();
		 $this->width = imagesx($this->img);
		 $this->height = imagesy($this->img);
	  } else {
         // image could not be obtained, show at least some error indicator
		 if ( ! $this->errorMsg ) $this->errorMsg = ' *** ERROR *** ';	  
	  }

   // create error message image or superpose to current one

   } 
   
   if ( strlen($this->errorMsg) > 0 ) {

      if ( $this->img  ) {
	     $oldimg = $this->img;
         $this->create( $this->width, $this->height + 20 );
	     imagecopyresized( $this->img, $oldimg, 0, 20, 0, 0,
		    $this->width, $this->height, $this->width, $this->height );
	     imagedestroy( $oldimg );  
	  } 
	  if ( ! $this->img ) $this->create(300,20);
      imagefilledrectangle( $this->img, 0, 0, $this->width, 20, $this->useColor('RED') );
      imageString( $this->img, 2, 5, 2, $this->errorMsg, $this->useColor('WHITE') );
   }

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

//$colourscom = array('WHITE'=>0, 'BLACK'=>1);
//$desc = $colourscom;
   if ( ! arrayKeyExists1( $desc, $this->colorIds ) ) {

      // Color not used previously -- obtain its RGB numbers
      // and create a new color Id for it (allocate it)

       $stdColors = $this->stdColors1;
      //print_r($stdColors); die;
      if ( arrayKeyExists1( $desc, $stdColors ) )

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

##### DRAW LINE

function setPixel( $x, $y ) {

  if ( $this->rotate != 0 )
    $this->rotatePixel($x,$y);

   imagesetpixel( $this->img, $x, $y, $this->currentColor);
}

##### FILL RECTANGLE

function fillRect($x,$y,$w,$h) {

  if ( $this->rotate != 0 )
    $this->rotateRect($x,$y,$w,$h);

   imagefilledrectangle( $this->img, $x, $y, $x+$w-1, $y+$h-1, $this->currentColor);
}

##### CIRCLE

function circle( $x, $y, $radius ) {

  if ( $this->rotate != 0 )
    $this->rotatePixel($x,$y);

   imagefilledellipse( $this->img, $x, $y, $radius*2, $radius*2, $this->currentColor);
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

##### ROTATE PIXEL

function rotatePixel( &$x, &$y ) {

   switch( $this->rotate ) {
   case 90:
      $x0 = $x; 
      $x = $y; 
      $y = $this->height - $x0 - 1;
      break;
   case 180:
      $x = $this->width - $x - 1;
      $y = $this->height - $y - 1;
      break;
   case 270:
      $y0 = $y; 
      $y = $x;
      $x = $this->width - $y0 - 1;
      break;
   }

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
