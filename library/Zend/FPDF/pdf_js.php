<?php

/**********************************************************************************************
below path is modified to cope crm package with outer library instead of its own library
by jm on 13072018
***********************************************************************************************/
//require_once(Bootstrap::$root."/library/Zend/FPDF/fpdf.php");
//require_once(Bootstrap::$root."/library/Zend/FPDF/code128.php");

$main_library_path = dirname(__FILE__);
require_once($main_library_path."/fpdf.php");
require_once($main_library_path."/code128.php");


class PDF_JavaScript extends PDF_Code128 {

	var $javascript;
	var $n_js;

	public function IncludeJS($script) {
		$this->javascript=$script;
	}

	public function _putjavascript() {
		$this->_newobj();
		$this->n_js=$this->n;
		$this->_out('<<');
		$this->_out('/Names [(EmbeddedJS) '.($this->n+1).' 0 R]');
		$this->_out('>>');
		$this->_out('endobj');
		$this->_newobj();
		$this->_out('<<');
		$this->_out('/S /JavaScript');
		$this->_out('/JS '.$this->_textstring($this->javascript));
		$this->_out('>>');
		$this->_out('endobj');
	}

	public function _putresources() {
		parent::_putresources();
		if (!empty($this->javascript)) {
			$this->_putjavascript();
		}
	}

	public function _putcatalog() {
		parent::_putcatalog();
		if (!empty($this->javascript)) {
			$this->_out('/Names <</JavaScript '.($this->n_js).' 0 R>>');
		}
	}
}
?>
