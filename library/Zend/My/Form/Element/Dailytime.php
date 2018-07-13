<?php
class My_Form_Element_Dailytime extends Zend_Form_Element_Text{
	public function __construct($spec, $options = null){
		parent::__construct($spec, $options);
		$this->setAttrib('class', 'ckeditor');
	}
}
?>