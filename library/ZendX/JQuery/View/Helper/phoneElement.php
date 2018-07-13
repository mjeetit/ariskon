<?php
	class ZC_view_Helper_PhoneElement extends Zend_View_Helper_FormElement
	{	
		protected $html = '';
		public function phoneElement($name, $value = null, $attribs = null)
		{	
			$helper = new Zend_View_Helper_FormText();
			$helper->setView($this->view);
			
			//var_dump($value);
			
			$this->html .= $helper->formText($name. '[areanum]' , '' , array()).' ';
			$this->html .= $helper->formText($name. '[geonum]'  , '' , array()).' ';
			$this->html .= $helper->formText($name. '[localnum]', '' , array());
			return $this->html;
		
		}
	
	
	}



?>