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

class reed6 {

var $gfi;
var $gflog;
	
var $K;
var $N;

var $p10;
var $p20;
var $p28;

function reed6() {
	
    $this->N=63;  
	$this->gfi = array(1,2,4,8,16,32,3,6,12,24,48,35,5,10,20,40,19,38,15,30,60,59,53,41,17,34,7,14,28,56,51,37,9,18,36,11,22,44,27,54,47,29,58,55,45,25,50,39,13,26,52,43,21,42,23,46,31,62,63,61,57,49,33,0);
    $this->gflog = array(0,0,1,6,2,12,7,26,3,32,13,35,8,48,27,18,4,24,33,16,14,52,36,54,9,45,49,38,28,41,19,56,5,62,25,11,34,31,17,47,15,23,53,51,37,44,55,40,10,61,46,30,50,22,39,43,29,60,42,21,20,59,57,58);   
   
    // polynomials generator
	$this->p10 = array(31,28,39,42,57,2,3,49,44,46);
	$this->p20= array(23,44,11,33,27,8,22,37,57,36,15,48,22,17,38,33,31,19,23,59);
	$this->p28= array(22,45,53,10,41,55,35,10,22,29,23,13,61,45,34,55,40,37,46,49,34,41,9,43,7,20,11,28);	
}
	
function mult( &$a, &$b )
{
  if ( $a == 0 || $b == 0 ) return 0;
  $sum_log = $this->gflog[$a] + $this->gflog[$b];
  if ($sum_log >= $this->N) $sum_log -= $this->N;
  return $this->gfi[$sum_log];
}

function calcRS( &$data, $k, $t2 )
{
   $this->setpolynomial($t2);

   // append t2 bytes at the end for error correction 
   // with initial value binary 0 
   $data .= str_pad( '', $t2, chr(0));
   
   for( $i = 0; $i < $k; $i++ ) {

      $tmp = ord($data[$k]) ^ ord($data[$i]);
	  
      for( $j = 0; $j < $t2-1; $j++ )
         $data[$k + $j] = chr(
		    ord( $data[$k + $j + 1] ) ^ $this->mult( $tmp, $this->p[$j] )
			);

      $data[$k+$t2-1] = chr( $this->mult( $tmp, $this->p[$t2-1] ) );
   }
}

function setpolynomial($t2) {

   switch( $t2 ) {
   case 20: $this->p = $this->p20; break;
   case 28: $this->p = $this->p28; break;
   default: $this->p = $this->p10;
   }
}

} // of class

?>