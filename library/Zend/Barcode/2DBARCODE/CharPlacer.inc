<?php

//  RDatamatrix for PHP
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

class charPlacer {

    var $nrow;
    var $ncol;
    var $placerarray;
	
	function charPlacer() {
		$this->placerarray=array();
	}

	/* "utah" places the 8 bits of a utah-shaped symbol character in ECC200 */
    function utah($row, $col,$chr)
    {

       $this->module($row - 2, $col - 2, $chr, 1);
       $this->module($row - 2, $col - 1, $chr, 2);
       $this->module($row - 1, $col - 2, $chr, 3);
       $this->module($row - 1, $col - 1, $chr, 4);
       $this->module($row - 1, $col,$chr, 5);
       $this->module($row, $col - 2, $chr, 6);
       $this->module($row, $col - 1, $chr, 7);
       $this->module($row, $col, $chr, 8);

    }



	/* "cornerN" places 8 bits of the four special corner cases in ECC200 */
     function corner1($chr)
    {

        $this->module($this->nrow - 1, 0, $chr, 1);
        $this->module($this->nrow - 1, 1, $chr, 2);
        $this->module($this->nrow - 1, 2, $chr, 3);
        $this->module(0, $this->ncol - 2, $chr, 4);
        $this->module(0, $this->ncol - 1, $chr, 5);
        $this->module(1, $this->ncol - 1, $chr, 6);
        $this->module(2, $this->ncol - 1, $chr, 7);
        $this->module(3, $this->ncol - 1, $chr, 8);

    }

   function corner2($chr) {

        $this->module($this->nrow - 3, 0, $chr, 1);
        $this->module($this->nrow - 2, 0, $chr, 2);
        $this->module($this->nrow - 1, 0, $chr, 3);
        $this->module(0, $this->ncol - 4, $chr, 4);
        $this->module(0, $this->ncol - 3, $chr, 5);
        $this->module(0, $this->ncol - 2, $chr, 6);
        $this->module(0, $this->ncol - 1, $chr, 7);
        $this->module(1, $this->ncol - 1, $chr, 8);
    }


   function corner3($chr) {

        $this->module($this->nrow - 3, 0, $chr, 1);
        $this->module($this->nrow - 2, 0, $chr, 2);
        $this->module($this->nrow - 1, 0, $chr, 3);
        $this->module(0, $this->ncol - 2, $chr, 4);
        $this->module(0, $this->ncol - 1, $chr, 5);
        $this->module(1, $this->ncol - 1, $chr, 6);
        $this->module(2, $this->ncol - 1, $chr, 7);
        $this->module(3, $this->ncol - 1, $chr, 8);
    }

   function corner4($chr) {

        $this->module($this->nrow - 1, 0, $chr, 1);
        $this->module($this->nrow - 1, $this->ncol - 1, $chr, 2);
        $this->module(0, $this->ncol - 3, $chr, 3);
        $this->module(0, $this->ncol - 2, $chr, 4);
        $this->module(0, $this->ncol - 1, $chr, 5);
        $this->module(1, $this->ncol - 3, $chr, 6);
        $this->module(1, $this->ncol - 2, $chr, 7);
        $this->module(1, $this->ncol - 1, $chr, 8);
    }

/* "ecc200" fills an nrow x ncol array with appropriate values for ECC200 */

function make() {

        /* Starting in the correct location for character #1, bit 8,... */
        $chr = 1;
        $row = 4;
        $col = 0;

		/* First, fill the array[] with invalid entries */
       // for ($r = 0; $r < $this->nrow; $r++)
       //     for ($c = 0; $c < $this->ncol; $c++)
       //         $this->placerarray[$r * $this->ncol + $c] = 0;

        do

        {
          /* repeatedly first check for one of the special corner cases, then... */
            if (($row == $this->nrow) && ($col == 0))   $this->corner1($chr++);
            if (($row == $this->nrow - 2 ) && ($col == 0) && ($this->ncol % 4 != 0) )      $this->corner2($chr++);
            if (($row == $this->nrow - 2 ) && ($col == 0) && ($this->ncol % 8 == 4) )      $this->corner3($chr++);
            if (($row == $this->nrow + 4 ) && ($col == 2) && ($this->ncol % 8 == 0) )      $this->corner4($chr++);

            do
           /* sweep upward diagonally, inserting successive characters,... */
            {

                if (($row < $this->nrow) && ($col >= 0) &&( $this->placerarray[$row * $this->ncol + $col] == 0))  $this->utah($row, $col, $chr++);
                $row -= 2;
                $col += 2;

            }   while (($row >= 0) && ($col < $this->ncol));

            $row++;
            $col += 3;
            /* & then sweep downward diagonally, inserting successive characters,... */
            do

            {

                if (($row >= 0) && ($col < $this->ncol) && ($this->placerarray[$row * $this->ncol + $col] == 0))  $this->utah($row, $col, $chr++);
                $row += 2;
                $col -= 2;

            }  while (($row < $this->nrow) && ($col >= 0));
            $row += 3;
            $col++;

			/* ... until the entire array is scanned */
        } while ($row < $this->nrow || $col < $this->ncol);

		/* Lastly, if the lower righthand corner is untouched, fill in fixed pattern */
        if ($this->placerarray[$this->nrow * $this->ncol - 1] == 0)
                    $this->placerarray[$this->nrow * $this->ncol - 1] = $this->placerarray[$this->nrow * $this->ncol - $this->ncol - 2] = 1;


}


/* "module" places "chr+bit" with appropriate wrapping within array[] */

function module($r, $c, $chr, $bit) {

        if ($r < 0)
        {
            $r += $this->nrow;
            $c += 4 - ($this->nrow + 4) % 8;
        }

        if ($c < 0)
        {
            $c += $this->ncol;
            $r += 4 - ($this->ncol + 4) % 8;
        }
        $this->placerarray[$r * $this->ncol + $c] = 10 * $chr + $bit;


}

} // of class

?>