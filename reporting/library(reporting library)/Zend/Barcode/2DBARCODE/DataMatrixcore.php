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

class DataMatrixcore
{

   var $g; // Graphics object received from DataMatrix object

   /**
    * Size in pixels of the square modules that made up the symbol.
    */
   var $dotPixels; // length of side of the dot in pixels

   /**
    * if true (default is false) the class will process the ~ character in the input data (see Data Matrix Manual for more information ).
    */
   var $processTilde;
	
	var $internalCode;

	var $bitmap;

	var $currentEncoding;

	// symbol attributtes of the configurations
	var $rows;
	var $cols;
	var $datarows;
	var $datacols;
	var $maprows;
	var $mapcols;
	var $regions;
	var $totaldata;
	var $totalerr;
	var $reeddata;
	var $reederr;
	var $reedblocks;

	var $C49rest;

    // format used
    var $calculatedFormat;

   /**
    * selects the encoding to be used: 
    * E_AUTO, E_ASCII (default), E_C40, E_TEXT or E_BASE256.
    */
   var $encoding;

   /**
    * if -1 (default), the format of the symbol will be automaticaly selected.
    *  Otherwise a value must be specifed.
    */
   var $preferredFormat;
   
   var $topMarginPixels;
   var $leftMarginPixels;
   var $code;

////////// CONSTRUCTOR

function DataMatrixcore( &$g ) {

   $this->g = &$g;
   $this->dotPixels=4;
   $this->code='';
   $this->topMarginPixels=0;
   $this->leftMarginPixels=0;
   $this->processTilde=false;
   $this->internalCode="";
   $this->preferredFormat=-1;
   $this->C49rest=0;
   #$this->encoding=E_ASCII;
   $this->currentEncoding=E_ASCII;
   // format used
   $this->calculatedFormat=0;	

}

// 255 randomizing algorithm

function random255($code,$position) {

		$random=(149*$position) % 255;
			$random++;

			$tmp=$code+$random;

			if ($tmp<=255) return $tmp;
			else return ($tmp-256);

 }


function isDigit($c) { return ( $c >= 48 && $c <= 57 ); }

function copyArray( &$src, &$dest, $initSrc, $initDest, $longi ) {

   for ($i=0;$i<$longi;$i++) $dest[$initDest+$i]=$src[$initSrc+$i];
}

// select which encoding system is more appropiate

function lookAheadTest( $Data, $currentEnc, $pos, &$specialSymbols ) {

   // datamatrix speficiations algorithm

   global $C40CODES;

   // step J
   $asciiCount=0;
   $c40Count=1;
   $textCount=1;
   $b256Count=1.25;

   $initialPos=$pos;

   if ($currentEnc!=E_ASCII) {
      $asciiCount=1;
      $c40Count=2;
      $textCount=2;
      $b256Count=2.25;
   }

   if ($currentEnc==E_C40) $c40Count=0;
   if ($currentEnc==E_TEXT) $textCount=0;
   if ($currentEnc==E_BASE256) $b256Count=0;

   while ($pos<sizeof($Data))  {
      $ic = $Data[$pos];
      $c = chr($ic);

      // step L, ascii count
	   if ($this->isDigit($ic)) $asciiCount=$asciiCount+0.5;
	   else if ( $ic > 127 ) $asciiCount=round($asciiCount)+2;
	   else  $asciiCount=round($asciiCount)+1;

	   $tmp = $C40CODES[$ic];
	   // step M c40 Count
	   if (sizeof($tmp)==1) $c40Count=$c40Count+0.66; // native c40
	   else  if  ( $ic > 127 ) $c40Count=$c40Count+2.66;
	   else $c40Count=$c40Count+1.33;

	   // step N, text count
	   $cText=$c;
	   $tmp=''.$c;
	   if (($c>='A') && ($c<='Z')) {
	   		$cText=strtoLower($tmp);
			$cText=$cText{0};
			}
	   if (($c>='a') && ($c<='z'))  {
		   $cText=strtoUpper($tmp);
		   $cText=$cText{0};
	   }

   	   if (sizeof($C40CODES[ord($cText)])==1) $textCount=$textCount+0.66; // native c40
	   else  if  ($cText>chr(127)) $textCount=$textCount+2.66;
	   else $textCount=$textCount+1.33;


	   // step Q, b256 count
	   $b256Count++;

	   // if there is a special symbol, use ASCII
	   if ($specialSymbols[$pos]!='') return E_ASCII;


	   // step R
	   if (($pos-$initialPos)>=4) {

	     if (( ($asciiCount+1)<=$c40Count) &&  ( ($asciiCount+1)<=$textCount) &&  ( ($asciiCount+1)<=$b256Count) ) return E_ASCII;
		 if ( ($b256Count+1)<=$asciiCount)  return E_BASE256;
		 if (( ($b256Count+1)<$textCount) &&  ( ($b256Count+1)<$c40Count) ) return E_BASE256;
	     if (( ($textCount+1)<$asciiCount) &&  ( ($textCount+1)<$c40Count) &&  ( ($textCount+1)<$b256Count) ) return E_TEXT;
		 if (( ($c40Count+1)<$asciiCount) &&  ( ($c40Count+1)<$textCount) &&  ( ($c40Count+1)<$b256Count) ) return E_C40;

	   }

	   $pos++;

   } // while


	// step K, end of data

      // round up
	$asciiCount=round($asciiCount);
	$c40Count=round($c40Count);
	$textCount=round($textCount);
	$b256Count=round($b256Count);


	if (( $asciiCount<=$c40Count) &&  ( $asciiCount<=$textCount) &&  ( $asciiCount<=$b256Count) ) return E_ASCII;
	if (( $textCount<$asciiCount) &&  ( $textCount<$c40Count) &&  ( $textCount<$b256Count) ) return E_TEXT;
	if (( $b256Count<$asciiCount) &&  ( $b256Count<$textCount) &&  ( $b256Count<$c40Count) ) return E_BASE256;

	return E_C40;
 }


// create an array of the given length

function getArray($len, $ivalue = 0 ) {

   $len = (int) $len;
   if ( $len <= 0 )
      return array();
   else
      return array_fill(0, $len, $ivalue );
}

// create and array of the given length
function getArray2D($len, $len2)   {

   $len = (int) $len;
   if ( $len <= 0 )
      return array();
   else
      return array_fill(0, $len, array_fill( 0, $len2, (int) 0 ) );

}

// encode automatic

function encodeAuto( $longi, &$in, &$out, &$specialSymbols ){

   $tmp= $this->getArray(6000);
   $tmpResult=$this->getArray(6000);
   $tmpResultLen=0;
   $posResult=0;
   $proposedEncoding=E_ASCII;
   $Initial=$this->getArray(1);
   $InitialOut=$this->getArray(1);
   $previousEncoding=E_ASCII;

   $specialSymbolsTMP= $this->getArray(10,'');
   //for ($i=0;$i<sizeof($specialSymbolsTMP);$i++) $specialSymbolsTMP[$i]='';

   $pos=0;
   $done=false;

   // datamatrix speficiations algorithm

   // step A

   $this->currentEncoding=E_ASCII;

   while ( $pos < $longi ) {

      // step B
      while ( $this->currentEncoding==E_ASCII && $pos < $longi ) {

         // System.out.println("ASCII");

         $done=false;

         // 2 digits?

         if ( $pos+1 < $longi )
            if ( $this->isDigit($in[$pos]) && $this->isDigit($in[$pos+1]) ) {

               if (($previousEncoding!=E_ASCII) && ($previousEncoding!=E_BASE256)) $out[$posResult++]=UNLATCH; // fix 18.10.2005

               $tmp[0]=$in[$pos];
               $tmp[1]=$in[$pos+1];

               // copy to final array
               $tmpResultLen=$this->encodeAscii(2,$tmp,$tmpResult,$specialSymbolsTMP);
               $this->copyArray($tmpResult,$out,0,$posResult,$tmpResultLen);
               $posResult=$posResult+$tmpResultLen;

               $pos++;
               $pos++;
               $done=true;

               $previousEncoding=E_ASCII;
            }



      if (! $done) {
         // make test
         $proposedEncoding = 
            $this->lookAheadTest( $in, $this->currentEncoding, $pos, $specialSymbols);
         // change proposed encoding
         if ( $proposedEncoding != E_ASCII ) {
            $previousEncoding = $this->currentEncoding;
            $this->currentEncoding = $proposedEncoding;
         }
      }


      if ( !$done && $this->currentEncoding == E_ASCII ) {
         if ( $previousEncoding != E_ASCII )
		 	if ($previousEncoding!=E_BASE256) $out[$posResult++]=UNLATCH; // fix 18.10.2005
			
         $tmp[0]=$in[$pos];
         $specialSymbolsTMP[0]=$specialSymbols[$pos]; // set parameter for special symbols
         $tmpResultLen=$this->encodeAscii(1,$tmp,$tmpResult,$specialSymbolsTMP);
         $specialSymbolsTMP[0]='';
         // copy to final array
         $this->copyArray($tmpResult,$out,0,$posResult,$tmpResultLen);
         $posResult=$posResult+$tmpResultLen;
         $pos++;
         $previousEncoding=E_ASCII;
      }

  } // B


  // step C
  while  ( $this->currentEncoding == E_C40 && $pos < $longi ) {

    //System.out.println("C40");

      if (($previousEncoding!=E_ASCII) && ($previousEncoding!=E_C40) && ($previousEncoding!=E_BASE256)) $out[$posResult++]=UNLATCH; // fix 18.10.2005

	  // what does test tell us?
	  $Initial[0]=$pos;
	  $tmpResultLen=$this->encodeC40($longi,$Initial,$in,$tmpResult,false,($previousEncoding!=E_C40),true);
	  $pos=$Initial[0];

	  $this->copyArray($tmpResult,$out,0,$posResult,$tmpResultLen);
	  $posResult=$posResult+$tmpResultLen;

	  // make test
      $proposedEncoding=$this->lookAheadTest($in,$this->currentEncoding,$pos,$specialSymbols);
	  $previousEncoding=$this->currentEncoding;
	  $this->currentEncoding=$proposedEncoding;

  } // C


  // step D
  while  (($this->currentEncoding==E_TEXT)&& ($pos<$longi)) {

     // System.out.println("Text");

      if (($previousEncoding!=E_ASCII) && ($previousEncoding!=E_TEXT) && ($previousEncoding!=E_BASE256)) $out[$posResult++]=UNLATCH; // fix 18.10.2005

	  // what does test tell us?
	  $Initial[0]=$pos;
	  $tmpResultLen=$this->encodeC40($longi,$Initial,$in,$tmpResult,true,($previousEncoding!=E_TEXT),true);
	  $pos=$Initial[0];

	  $this->copyArray($tmpResult,$out,0,$posResult,$tmpResultLen);
	  $posResult=$posResult+$tmpResultLen;

	  // make test
      $proposedEncoding=$this->lookAheadTest($in,$this->currentEncoding,$pos,$specialSymbols);
	  $previousEncoding=$this->currentEncoding;
	  $this->currentEncoding=$proposedEncoding;

  } // D


  // step G
  if  ($this->currentEncoding==E_BASE256){

      if (($previousEncoding!=E_ASCII) && ($previousEncoding!=E_BASE256)) $out[$posResult++]=UNLATCH;

	  $Initial[0]=$pos;
	  $InitialOut[0]=$posResult;
	  $this->encodeBase256($longi,$Initial,$in,$InitialOut,$out,true,$specialSymbols);
	  $pos=$Initial[0];
	  $posResult=$InitialOut[0];

	  // make test
      $proposedEncoding=$this->lookAheadTest($in,$this->currentEncoding,$pos,$specialSymbols);
	  $previousEncoding=$this->currentEncoding;
	  $this->currentEncoding=$proposedEncoding;

  } // G


  } // while


  return $posResult;


}


// encode Base 256
function encodeBase256( $longi, &$initial, &$in, &$outInit, &$out, $makeLookAhead, &$specialSymbols ){

  $count = 0;
  $tmp =$this->getArray(6000);
  $tmpInit=$outInit[0];
  $tmpInitCount=$outInit[0];
  $pos=0;;
  $k=0;

   // move to tmp, to count the number of bytes in B256 mode
   for($k =$initial[0]; $k < $longi; $k++)
   {
   	   $tmp[$count] =$in[$k];
   	   $count++;

	   $pos=$k+1;
	   if ($makeLookAhead)
	       if ($this->lookAheadTest($in,E_BASE256,$pos,$specialSymbols)!=E_BASE256) {
		   		$k++;
		   		break;
		   }
   }

   // return current position
   $initial[0]=$k;

   $out[$tmpInitCount++] = LATCH_BASE256;

   if ($count<250)  {$out[$tmpInitCount] =$this->random255($count,$tmpInitCount+1);
					$tmpInitCount++;
   }
   else  {
	   $out[$tmpInitCount] =$this->random255(249+(($longi-($longi%250))/250),$tmpInitCount+1);
	   $tmpInitCount++;
	   $out[$tmpInitCount] =$this->random255($longi%250,$tmpInitCount+1);
	   $tmpInitCount++;
   }


   for ($k=0; $k<$count;$k++) {
	   $out[$tmpInitCount]=$this->random255($tmp[$k],$tmpInitCount+1);
	   $tmpInitCount++;
   }

   $outInit[0]=$tmpInitCount;

   return $tmpInitCount;


}

// encode C40

function encodeC40( $longi, &$initial, &$in, &$out, $doTextEncoding, $inititaLatch, $onlyOneWord ) {

   global $C40CODES;

   $count = 0;
   $countMod3 = 0;
   $tmp = Array(0, 0, 0);
   $charac=0;

   $specialSymbolsTMP= $this->getArray(10,'');
   // with c40 and text we do not encode special symbols
   //for ($i=0;$i<sizeof($specialSymbolsTMP);$i++) $specialSymbolsTMP[$i]='';


   if ($inititaLatch) {
      if ($doTextEncoding) $out[$count++] = LATCH_TEXT;
      else $out[$count++] = LATCH_C40;
   }

   for($k = $initial[0]; $k < $longi; $k++) {

      $charac=$in[$k];

      // if do text encoding, swith Upper and Lowercase

      if ($doTextEncoding) {

         $tmpS='' . chr($charac);
         // if it is lower, convert to upper
         // if it is upper, convert to lower
         if ((chr($charac)>='a') && (chr($charac)<='z'))
            $tmpS=strtoupper($tmpS);
         if ((chr($charac)>='A') && (chr($charac)<='Z'))
            $tmpS=strtolower($tmpS);

         $charac=ord($tmpS{0});
      }

      $codes = $C40CODES[$charac];


      for($h = 0; $h < sizeof($codes); $h++) {

         $tmp[$countMod3++] = $codes[$h];

         // one codeword completed
         if($countMod3 == 3) {
            $t = $tmp[0] * 1600 + $tmp[1] * 40 + $tmp[2] + 1;
            $out[$count++] = floor($t / 256);
            $out[$count++] = $t % 256;
            $countMod3 = 0;
         }
      }

      if (($onlyOneWord) && ($countMod3==0)) {
         $this->C49rest=$countMod3;
         $initial[0]=$k+1;
         if ($initial[0]==$longi) {$out[$count++] =UNLATCH;}
         return $count;
      }
   }


   $initial[0]=$longi;

   if ($countMod3 >0) {

      if ($countMod3 ==2) {
         // use C40 PAD, 0 as third value
         $t = $tmp[0] * 1600 + $tmp[1] * 40 + C40_PAD + 1;
         $out[$count++] = floor($t / 256);
         $out[$count++] = $t % 256;
         $out[$count++] =UNLATCH;
      }

      if ($countMod3 ==1) {

         // unlatch to ASCII
         $out[$count++] =UNLATCH;

         // encode last char as ASCII
         $encodedASCII=$this->getArray(2);
         $toencode=$this->getArray($countMod3);
         for ($h=0;$h<$countMod3;$h++)  $toencode[$h]=$in[$longi-($countMod3-$h)];
         $asciiLen= $this->encodeAscii($countMod3, $toencode,$encodedASCII,$specialSymbolsTMP);
         for ($h=0;$h<$asciiLen;$h++)  $out[$count++]=$encodedASCII[$h]; // character
      }

   }
   else {
      $out[$count++] =UNLATCH;
   }

   $this->C49rest=$countMod3;

   return $count;
}

/**
 * paints the barcode in the specified graphic context.
 */

function paint() {

   // paint in the graphics context

   $x=$this->leftMarginPixels;
   $y=$this->topMarginPixels;

   for ($j1 = 0; $j1 < $this->rows; $j1++)
      for ($i1 = 0; $i1 < $this->cols; $i1++)
         if ($this->bitmap[$i1][$j1] != 0)
            $this->paintDot( 
               $x+($this->dotPixels*$i1),
               $y+($this->dotPixels*$j1),
               $this->dotPixels );

}

// 253 randomizing algorithm for PAD characters

function random253($code,$position) {

		$random=(149*$position) % 253;
			$random++;

			$tmp=$code+$random;

			if ($tmp<=254) return $tmp;
			else return ($tmp-254);

}


function applyTilde( $code, &$specialSymbol ) {

		$i=0;

	   $c=0;
	   $longi=strlen($code);
	   $result="";
	   $done=false;

	   for ($i=0;$i<$longi;$i++) {

		   $c=ord($code{$i});

		   if ($c==ord('~')) {
			   // process special cases

			   if ($i<($longi-1)) {

				 $nextc=ord($code{$i+1});

			   // comes a char <= 26

			   if (($nextc>=64) && ($nextc<=(64+26)))  {
					   $i++;
					   $result=$result . chr($nextc-64);
			   }

			   // 2
			   if ($nextc==ord('~')) {
				  $result=$result.'~';
				  $i++;
			   }

			   // 1
			   if ($nextc==ord('1')) {
				   if ((strlen($result)==0) || (strlen($result)==1) || (strlen($result)==4) || (strlen($result)==5)) { // first or second position
					   $specialSymbol[strlen($result)]=' ';
					   $result=$result . chr(FNC1);
				   }
				   else  $result=$result . chr(29); // transmit as separator

				   $i++;
			   }

			   // 2
			   if ($nextc==ord('2'))
			     if ($i<($longi-4)) {
					 // add symbol secuence and file identifier ( the next 3 codewords)
				   $specialSymbol[strlen($result)]='' . $code{i+2} . $code{i+3} . $code{i+4};
				   $result=$result . chr(STRUCTURED_APPEND);
				   $i=$i+4;
			   }

			   // 3
			   if (($nextc==ord('3')) && (strlen($result)==0)) {
				   $specialSymbol[strlen($result)]=' ';
				   $result=$result . chr(READER_PROGRAMMING);
				   $i++;
			   }

			   // 5
			   if (($nextc==ord('5')) && (strlen($result)==0))  {
				   $specialSymbol[strlen($result)]=' ';
				   $result=$result . chr(MACRO5);
				   $i++;
			   }

			   // 6
			   if (($nextc==ord('6')) && (strlen($result)==0))  {
				   $specialSymbol[strlen($result)]=' ';
				   $result=$result . chr(MACRO6);
				   $i++;
			   }

			   // 7
			   if ($nextc==ord('7'))
				   if ($i<($longi-7)) {
				   $eciString=substr($code,i+2,6);

				   $eci=0;
				   $eci= $eciString;
				   //$eci=sscanf($eciString,"%d");

				   if ($eci<=126) {
					   $specialSymbol[strlen($result)]='' . chr($eci+1);
					   $result=$result. chr(ECI );

				   }

				   if (($eci>=127)&& ($eci<=16382)) {
					   $c1= floor((($eci-127)/254)+128);
					   $c2= (($eci-127)%254)+1;

					   $specialSymbol[strlen($result)]='' . chr($c1) . chr( $c2);
					   $result=$result .  chr( ECI );
				   }

				   if ($eci>=16383) {
					   $c1=floor(($eci-16383)/64516)+192;
					   $c2=(($eci-16383)/254);
					   $c2=($c2%254)+1;
					   $c3=(($eci-16383)%254)+1;

					   $specialSymbol[strlen($result)]='' . chr($c1) . chr($c2) . chr($c3);
					   $result=$result . chr(ECI );
				   }

				   $i=$i+7;

			   }

			   // dNNN
			   if ($nextc==ord('d'))
				   if ($i<($longi-3)) {
				   $ascString=substr($code,$i+2,3);

				   $asc=0;

				    //$asc= sscanf($ascString,"%d");
					$asc= $ascString;


				   if ($asc>255) $asc=255;

				   // the ascii value
				   $result=$result .  chr($asc);

				   $i=$i+4;
			   }

			   }

		   } else {
			   // no escape
			   $result=$result .  chr($c);
		   }



	   }

	   return $result;

 }


////////// DO CODE

function doCode() {

   $specialSymbol= $this->getArray(5000,'');

   $this->internalCode=$this->code;
   if ($this->processTilde) $this->internalCode=$this->applyTilde($this->code,$specialSymbol);
	 
   if (strlen($this->internalCode)==0) return;

   $c=$this->getArray(strlen($this->internalCode));
   for ($i=0;$i<strlen($this->internalCode);$i++) $c[$i]=ord($this->internalCode{$i});	 
	 
   $this->bitmap=$this->drawBarcode($c,$specialSymbol);
}

// draw barcode

function drawBarcode( $codeStr, &$specialSymbols ) {

   global $CONFIGURATION;

   $encoded=$this->getArray(5000);

   $dummy=$this->getArray(1);
   $dummy2=$this->getArray(1);

   $elen=0;

   if ($this->encoding!=E_AUTO)
      $this->currentEncoding=$this->encoding;

   if ($this->encoding==E_AUTO)
      $elen=$this->encodeAuto(sizeof($codeStr),$codeStr,$encoded,$specialSymbols);

   if ($this->encoding==E_ASCII)
      $elen=$this->encodeAscii(sizeof($codeStr),$codeStr,$encoded,$specialSymbols);
   if ($this->encoding==E_C40)
      $elen=$this->encodeC40(sizeof($codeStr),$dummy,$codeStr,$encoded,false,true,false);
   if ($this->encoding==E_TEXT)
      $elen=$this->encodeC40(sizeof($codeStr),$dummy,$codeStr,$encoded,true,true,false);
   if ($this->encoding==E_BASE256)
      $elen=$this->encodeBase256(sizeof($codeStr),$dummy,$codeStr,$dummy2,$encoded,false,$specialSymbols);
   if ($this->encoding==E_NONE) {
      $elen=strlen($codeStr);
      for ($i=0;$i<$elen;$i++)  $encoded[i]=$codeStr[i];
   }

   // which configuration id appropiate?
   $i=0;
   if ($this->preferredFormat!=-1) {
      $i=$this->preferredFormat;
      // if preferred is too small, then make auto
      if ($elen > $CONFIGURATION[$i][TOTALDATA]) $i=0;
   }

   for(; $elen > $CONFIGURATION[$i][TOTALDATA] && $i < 30; $i++) {

      if ( $this->currentEncoding==E_C40 || $this->currentEncoding == E_TEXT ) {
         if ( $this->C49rest==1 && $encoded[$elen-2]==UNLATCH
              && $CONFIGURATION[$i][TOTALDATA] == $elen-1 ) {
            $encoded[$elen-2]=$encoded[$elen-1];
            $encoded[$elen-1]=0;
            $elen--;
            break;
         }
         // remove last unlacht
         if ( $this->C49rest == 0 && $encoded[$elen-1] == UNLATCH
            && $CONFIGURATION[$i][TOTALDATA] == $elen-1 )  {
            $encoded[$elen-1]=0;
            $elen--;
            break;
         }
      }
   }

   if ($i == 30) return null; // no configuration available

   $config=$i;

   // remember format used. The user might read it with getCalculatedFormat()

   $this->calculatedFormat=$config;

   // read configuration

   $this->rows= $CONFIGURATION[$config][ROW];
   $this->cols= $CONFIGURATION[$config][COL];
   $this->datarows= $CONFIGURATION[$config][ROWDATA];
   $this->datacols= $CONFIGURATION[$config][COLDATA];
   $this->maprows= $CONFIGURATION[$config][ROWMAP];
   $this->mapcols= $CONFIGURATION[$config][COLMAP];
   $this->regions= $CONFIGURATION[$config][REGIONS];
   $this->totaldata= $CONFIGURATION[$config][TOTALDATA];
   $this->totalerr= $CONFIGURATION[$config][TOTALERR];
   $this->reeddata= $CONFIGURATION[$config][REEDDATA];
   $this->reederr= $CONFIGURATION[$config][REEDERR];
   $this->reedblocks= $CONFIGURATION[$config][REEDBLOCKS];

	   // remove last UNLATCH
	   if ((  $this->currentEncoding==E_C40) || (  $this->currentEncoding==E_TEXT))
		   if (($this->C49rest==0) && ($elen== $this->totaldata) && ($encoded[$elen-1]==UNLATCH)) $encoded[$elen-1]=129;

	   // do we need more then 1 reed solomon block
	   $blocks=$this->getArray2D(10,255);

	   // pad encoded data
	   $firstPad=true;
	   for ($h=$elen;$h< $this->totaldata;$h++) {
		   if ($firstPad) $encoded[$h]=PAD;
		   else {
			   // apply 253-state randomizin algorithm
			   $encoded[$h]= $this->random253(PAD,$h+1);
		   }

		   $firstPad=false;
	   }

	   // interleaved blocks
	   $tmp=0;
	   $tmpcol=0;
	   for ($h=1;$h<= $this->totaldata;$h++) {
		   $blocks[$tmp][$tmpcol]=$encoded[$h-1];
		   $tmp++;
		   if ($tmp==$this->reedblocks) {$tmp=0;
								 $tmpcol++;}
	   }


	   $blocksLen= $this->getArray(10);
	   $totalLen=0;

	   // calculate reedsol for each block
	   $reedsol=new reed();
	   $reedsol->K= $this->reeddata;
	   for ($h=0;$h< $this->reedblocks;$h++) {

		   $blocksLen[$h]= $this->reeddata+ $this->reederr;

		   $tmpReedData= $this->reeddata;

		   // special case 144
		   if  (( $this->rows==144) && ($h>7)) {
			    $blocksLen[$h]= $this->reeddata+ $this->reederr-1;
				$tmpReedData=155;
		   }


		   $reedsol->calcRS($blocks[$h],$tmpReedData, $this->reederr);

		   $totalLen=$totalLen+$blocksLen[$h];
	   }

	   // mix interleaved blocks
	   $finalBlock= $this->getArray($totalLen);
       $finalBlockLen=0;
	   $c=0;
		for ($j=0;$j<$blocksLen[0];$j++)
		  for ($h=0;$h<$this->reedblocks;$h++)
		   if ($j<$blocksLen[$h])  // this condition is for the special case 144
		    {
				$finalBlock[$c++]=$blocks[$h][$j];
				$finalBlockLen++;
	        }


	   $bitmap= $this->createBitmap($finalBlock);

       return $bitmap;

	}

	
function debugMatrix($m,$c, $r) {

    $s='';
	print_r($s);
	print_r($s);
	print_r($s);
	for ($j1 = 0; $j1 < $r; $j1++)

        {
		$s='';
            for ($i1 = 0; $i1 < $c; $i1++)

            {
                if ($m[$i1][$j1] != 0)

                    $s=$s.'X';

                else
                    $s=$s.' ';

                }
            print_r($s.chr(13));
	}
	print_r('');
	print_r('');
	}


// craete bitmap of the code

function createBitmap($codeStr) {


	   $result=$this->getArray2D($this->cols,$this->rows);

	   // draw side
	   $curx=0;
	   $cury=0;
	   if ($this->regions==2) {
		   $this->drawBorders($result,$curx,$cury,$this->datacols+2,$this->datarows+2);
		   $this->drawBorders($result,$curx+$this->datacols+2,$cury,$this->datacols+2,$this->datarows+2);
	   }
	   else {
	   	$tmp=floor(sqrt($this->regions));
	   		for ($i=0;$i<$tmp;$i++) {
		 		for ($j=0;$j<$tmp;$j++) {
		   			$this->drawBorders($result,$curx+($i*($this->datacols+2)),$cury+($j*($this->datarows+2)),$this->datacols+2,$this->datarows+2);
		 	}
	   		}
	   } // else


	   // place bits
	   $tmpMap=$this->getArray(($this->mapcols+10)*$this->maprows);
	   $placer= new charPlacer();
	   $placer->ncol=$this->mapcols;
	   $placer->nrow=$this->maprows;
	   $placer->placerarray=$tmpMap;
	   $placer->make();
	   $tmpMap=$placer->placerarray;


	   // move data from tmpMap (without pattern) to resuls (with patterns)
	   $vertical=1;
	   $r2=0;
	   $c2=0;
	   for ($r=0;$r<$this->maprows;$r++)
	   {
	       $horizontal=1;
		   for ($c=0;$c<$this->mapcols;$c++) {
			   $c2=$c+$horizontal;
			   $r2=$r+$vertical;

			   if ($tmpMap[($r*$this->mapcols)+$c]>9) {
			     $character = floor($tmpMap[($r*$this->mapcols)+$c] / 10);
                 //System.out.println("CC "+character );
                 $bit = $tmpMap[($r*$this->mapcols)+$c] % 10;
                 $dot = $codeStr[$character - 1] & (1 << (8 - $bit));

			     $result[$c2][$r2]=$dot;
			   }
			   else $result[$c2][$r2]=$tmpMap[($r*$this->mapcols)+$c];

			   if (($c>0) && ((($c+1) % $this->datacols)==0)) $horizontal=$horizontal+2;
	    }

		if (($r>0) && ((($r+1) % $this->datarows)==0)) $vertical=$vertical+2;

	   }

	   //$this->debugMatrix($result,$this->cols,$this->rows);

	   return $result;

}

	
function drawBorders( &$map, $x, $y, $w, $h ) {


		// draw borders of a region
		$alternative=0;

		// horizontal
		for ($i=0;$i<$w;$i++) {

			if (($i % 2)==0) $alternative=1;
			else $alternative=0;

			$map[$x+$i][$y+$h-1]=1;
			$map[$x+$i][$y]=$alternative;
		}

		$alternative=0;
		// vertical
		for ($i=0;$i<$h;$i++) {

			if ((($i+1) % 2)==0) $alternative=1;
			else $alternative=0;

			$map[$x][$y+$i]=1;
			$map[$x+$w-1][$y+$i]=$alternative;
		}


}

// encode as ascii

function encodeAscii( $longi, &$in, &$out, &$specialSymbols ) {

		$outp = 0;
		$done=false;

		for($j=0; $j < ($longi); $j++) {

			$done=false;

		    if ($j<($longi-1)) {
			  // two consecutive digits
              if (($in[$j]>=48) && ($in[$j]<=57) && ($in[$j+1]>=48) && ($in[$j+1]<=57) && ($j < $longi))
              {
                $tmp = ($in[$j] - 48) * 10 + ($in[$j + 1] - 48);
                $out[$outp++] = 130 + $tmp;
                $j++;
				$done=true;
              }
		    }

			// encode special symbols
			if (! $done)
				if ($specialSymbols[$j]!='') {

					// special symbol without parameters
					if (($in[$j]==READER_PROGRAMMING) || ($in[$j]==MACRO6) || ($in[$j]==MACRO5) || ($in[$j]==FNC1) ) {
						$out[$outp++] = $in[$j];
						$done=true;
					}

					// special symbol with parameters
					if (($in[$j]==STRUCTURED_APPEND) || ($in[$j]==ECI)) {
						$out[$outp++] = $in[$j];
						for ($l=0;$l<strlen($specialSymbols[$j]);$l++) $out[$outp++] = $specialSymbols[$j]{l};
						$done=true;
					}

			}


			// ascii
			if (! $done) {
              if($in[$j] < 128)
              {
                $out[$outp++] = $in[$j] + 1;
              } else // extended ascii
              {
                $out[$outp++] = SHIFT;
                $out[$outp++] = ($in[$j] - 128) + 1;
              }
			} // doPair
		} // for


        return $outp;

 }


// paint a dot

function paintDot( $x, $y, $w) { 

   $this->g->fillRect( $x, $y, $w, $w);
}


} // of class DataMatrixcore


?>
