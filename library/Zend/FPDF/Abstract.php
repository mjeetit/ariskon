<?php

/**********************************************************************************************
below path is modified to cope crm package with outer library instead of its own library
by jm on 13072018
***********************************************************************************************/
//include Bootstrap::$root.'/library/Zend/FPDF/pdf_js.php';

include '/opt/lampp/htdocs/ariskon/library/Zend/FPDF/pdf_js.php';

abstract class Zend_FPDF_Abstract extends PDF_JavaScript
{
  public function AutoPrint($dialog=false)
	{
		//Open the print dialog or start printing immediately on the standard printer
		$param  = ($dialog ? 'true' : 'false');
		$script = "print($param);";
		$this->IncludeJS($script);
	}
	
   public function AutoPrintToPrinter($server, $printer, $dialog=false)
	{
		//Print on a shared printer (requires at least Acrobat 6)
		$script = "var pp = getPrintParams();";
		if($dialog)
			$script .= "pp.interactive = pp.constants.interactionLevel.full;";
		else
			$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
			$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
			$script .= "print(pp);";
			$this->IncludeJS($script);
	}
}
