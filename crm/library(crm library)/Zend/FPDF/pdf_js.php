<?php
require_once(Bootstrap::$root."/library/Zend/FPDF/fpdf.php");
require_once(Bootstrap::$root."/library/Zend/FPDF/code128.php");

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
