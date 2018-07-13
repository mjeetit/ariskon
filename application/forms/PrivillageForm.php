<?php



class PrivillageForm extends Zend_Form {

	

	public $_obj = NULL;
	public $privilege = array();

	public $element = array('ViewHelper',

						 'Description',

						 'Errors',

						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),

						 array('Label', array('tag' => 'td', 'class' => "bold_text")),

						 array(array('row' => 'HtmlTag'), array('tag' => 'tr')));

	 public $element1 = array('ViewHelper',

						 'Description',

						 'Errors',

						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),

						 array('Label', array('tag' => 'td', 'class' => "bold_text")),

						 array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "odd",'openOnly'=>true)))	;

    public $withoutlabel = array('ViewHelper',

						 'Description',

						 'Errors',

						 array(array('data' => 'HtmlTag'), array('tag' => 'td','colspan'=>2, 'align' => 'center')),

						 array(array('row' => 'HtmlTag'), array('tag' => 'tr')));	

	public $decorator = array('ViewHelper',

							array('Description',

							   array('placement' => Zend_Form_Decorator_Abstract::APPEND,

									 'tag' => 'span',

									 'class' => '',

									 'style' => 'display:none;color:#FF0000;',

									 'id' => '$id')),

							'Errors',

							   array(array('data' => 'HtmlTag'), array('tag' => 'td', 'colspan' => '3')),

							      array('Label',

								     array('requiredPrefix' => '<span class="err">*</span>&nbsp;',

										   'escape' => false,

										   'tag' => 'td',

										   'class' => "bold_text")),

									array(array('row' => 'HtmlTag'),

									array('tag' => 'tr')));

		 

						 

    public function init()

    {	

	  $this->_obj	= new NotificationManager();	

    }



	public function UserPrivillageForm(){

	    $privilege = new ZC_Form_Element_Privilege('priv');

		$privilege->setLabel('Set privilege :')->setDecorators($this->decorator)

					   ->setvalue($this->privilege);//print_r($privilege);die;

		

		$submit = $this->CreateElement('submit', 'Add')

                  ->setDecorators($this->withoutlabel);

		$this->addElements(array($privilege,$submit));

		return $this;

	}



}

?>