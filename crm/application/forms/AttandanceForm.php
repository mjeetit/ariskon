<?php

class AttandanceForm extends Zend_Form {
	
	public $_obj = NULL;
	public $elementeven = array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),
						 array('Label', array('tag' => 'td', 'class' => "bold_text")),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr','class'=>'even')));
	
	public $elementodd = array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),
						 array('Label', array('tag' => 'td', 'class' => "bold_text")),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr','class'=>'odd')));
	 public $element1 = array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),
						 array('Label', array('tag' => 'td', 'class' => "bold_text")),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => 'odd','openOnly'=>true)))	;
    public $withoutlabel = array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td','colspan'=>2, 'align' => 'center')),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr')));			 
						 
    public function init()
    {	
	   $this->_obj	= new AttandanceManager();
    }
	public function EmpattandanceForm($data){
	     $this->addElement('hidden', 'empcode1', array(
			'description' => 'Employee Code',
			'ignore' => true,
			'decorators' => array(
				array('Description', array('escape'=>false, 'tag'=>'td', 'class' => 'bold_text')),
				array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "odd", 'openOnly' => true)),
			),
		));
		
		$this->addElement('hidden', 'empcode2', array(
			'description' => $data['employee_code'],
			'ignore' => true,
			'decorators' => array(
				array('Description', array('escape'=>false, 'tag'=>'td')),
				array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'closeOnly' => true)),
			),
		));
	   $month = $this->CreateElement('text', 'month')
		          ->setLabel('Month', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementeven)->setValue($data['month']);
		
	   $year = $this->CreateElement('text', 'year')
		          ->setLabel('Year', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementodd)->setValue($data['year']);
		$this->addElements(array($month,$year));
		
		for($i=1;$i<=31;$i++){
		 $day = $this->createElement('radio', 'day'.$i);
		 if($i%2==0){
		 	$day->setDecorators($this->elementodd); 
          }else{
		    $day->setDecorators($this->elementeven); 
		  }
		 $day->setLabel('Day '.$i)->addMultiOptions(array(
                    'P' => 'Present',
                    'A' => 'Absent',
		    'L' => 'Leave',
                      'H' => 'Half Leave',
                      'HL' => 'Half LWP',
                ))
                ->setSeparator('&nbsp;&nbsp;')->setValue($data['day'.$i]);
		  $this->addElements(array($day));
	    }
            $total_salary_days = $this->CreateElement('text', 'total_salary_days')
		          ->setLabel('Calculate Days', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementodd)->setValue($data['total_salary_days']);
		
		 $submit = $this->CreateElement('submit', 'Update')
                  ->setDecorators($this->withoutlabel)->setAttrib('class','submit-green');
		$this->addElements(array($total_salary_days,$submit));
		return $this;
	}

}
?>