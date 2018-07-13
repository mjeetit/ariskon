<?php
class My_Form_Element_CKEditor extends Zend_Form_Element_Textarea{
 
	public function __construct($spec, $options = null){
		parent::__construct($spec, $options);
		$this->setAttrib('class', 'ckeditor');
	  }
}
?>