<?php



class LoanRequestForm extends Zend_Form {

	

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
	
	public $elementeven = array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),
						 array('Label', array('tag' => 'td', 'class' => "bold_text")),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even")))	;
	
	public $elementodd = array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),
						 array('Label', array('tag' => 'td', 'class' => "bold_text")),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "odd")))	;

    public $withoutlabel = array('ViewHelper',

						 'Description',

						 'Errors',

						 array(array('data' => 'HtmlTag'), array('tag' => 'td','colspan'=>2, 'align' => 'center')),

						 array(array('row' => 'HtmlTag'), array('tag' => 'tr')));			 

						 

    public function init()

    {	

		

    }

	public function loanrequest(){

	   

	   

	     $loanamount = $this->CreateElement('text', 'loan_amount')

		          ->setLabel('Loan Amount', 'nl')->setAttrib('class','input-short')

                  ->setDecorators($this->elementeven);

						 

	    $editor = new My_Form_Element_Ckeditor('message', array('required'=>true, 'label'=>'template_message'));

		$editor->setAttrib('Rows','12');

		$editor->setAttrib('Cols','55');

		$editor->setAttrib('id','ckeditor');

		$editor->setLabel('Message:');

		$editor->setDecorators($this->elementodd);

		

		 $submit = $this->CreateElement('submit', 'Apply')->setAttrib('class','submit-green')

                  ->setDecorators($this->withoutlabel);

		$this->addElements(array($loanamount,$editor,$submit));

		return $this;

	}



}

?>