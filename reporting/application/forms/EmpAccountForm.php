<?php

class EmpAccountForm extends Zend_Form {
	
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
	   $this->_obj	= new EmployeeAccount();
    }
	public function EmployeeAccountForm($data){
	     $units = $this->_obj->getBusinessToCompany();
		 $bunit = array();
	     $bunit = $this->CreateElement('select', 'bunit_id')
		          ->setLabel('Business Unit', 'nl')
                  ->setDecorators($this->elementeven)->setAttrib('class','input-medium');
		  $bunit->addMultiOptions(array(''=>'--Select--'));
		 foreach($units as $business){
		     if($data['bunit_id']==$business['bunit_id']){
			  $bunit->setValue($business['bunit_id']);
			 }		  
	    	 $bunit->addMultiOptions(array($business['bunit_id']=>$business['bunit_name']));
		 }
		 $bunit->setAttrib('onchange', 'changeStatusBusiness(this.value,"'.$data['department_id'].'")');
		 
		 $department_id = $this->CreateElement('select', 'department_id')
		          ->setLabel('Department', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementodd);
		 $department_id->addMultiOptions(array(''=>'--Select--'));
		 $department_id->setAttrib('onchange', 'changeStatusDepartment(this.value,"'.$data['designation_id'].'")');
				  
				  
		 $designation_id = $this->CreateElement('select', 'designation_id')
		          ->setLabel('Designation', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementeven);
		 $designation_id->addMultiOptions(array(''=>'--Select--'));
		 $designation_id->setAttrib('onchange', 'getUsersByDesignation(this.value,"'.$data['user_id'].'")');
		 
		 $user_id = $this->CreateElement('select', 'user_id')
		          ->setLabel('Employee Name', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementodd);
		 $user_id->addMultiOptions(array(''=>'--Select--'));
				  
		 $acceseries_name = $this->CreateElement('text', 'acceseries_name')
		          ->setLabel('Item Name', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementeven)->setValue($data['acceseries_name']);
		
		 $acceseries_value = $this->CreateElement('text', 'acceseries_value')
		          ->setLabel('Item Value(In Amount)', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementodd)->setValue($data['acceseries_value']);
		
		 $refundable = $this->createElement('radio', 'refundable')->setDecorators($this->elementodd)->setAttrib('onclick','showmonthtextbox(this.value)');
         $refundable->setLabel('Refundable')->addMultiOptions(array(
                    '1' => 'Yes',
                    '0' => 'No'
                ))
                ->setSeparator('&nbsp;&nbsp;')->setValue(($data['refundable']!=''?$data['refundable']:1))->setAttrib('class','input-medium');
		 $refundable_not_after = $this->CreateElement('text', 'refundable_not_after')
		          ->setLabel('Refundable Period', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators(array('ViewHelper',
						 'Description',
						 'Errors',
						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),
						 array('Label', array('tag' => 'td', 'class' => "bold_text")),
						 array(array('row' => 'HtmlTag'), array('tag' => 'tr','id'=>'refun_tr','class'=>'even','style'=>'display:none'))))
						 ->setValue($data['refundable_not_after']);		  		  		  
		
		 $allowt_date = $this->CreateElement('text', 'allowt_date')
		          ->setLabel('Date Of Allowtment', 'nl')->setAttrib('class','input-medium')
                  ->setDecorators($this->elementodd)->setValue($data['allowt_date']);
		
		 $submit = $this->CreateElement('submit', $data['Mode'])
                  ->setDecorators($this->withoutlabel)->setAttrib('class','submit-green');
		$this->addElements(array($bunit,$department_id,$designation_id,$user_id,$acceseries_name,$acceseries_value,$refundable,$refundable_not_after,$allowt_date,$submit));
		return $this;
	}

}
?>