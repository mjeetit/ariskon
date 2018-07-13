<?php

class SettingForm extends Zend_Form {
	
	public $_obj = NULL;
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
						 
    public function init()
    {	
	   $this->_obj	= new SettingManager();
    }
	public function addprovidentForm($data){
	     $units = $this->_obj->getBusinessToCompany();
	     $bunit = $this->CreateElement('select', 'bunit_id')
		          ->setLabel('Business Unit', 'nl')
                  ->setDecorators($this->element);
		  $bunit->addMultiOptions(array(''=>'--Select--'));
		 foreach($units as $business){
		     if($data['bunit_id']==$business['bunit_id']){
			  $bunit->setValue($business['bunit_id']);
			 }		  
	    	 $bunit->addMultiOptions(array($business['bunit_id']=>$business['bunit_name']));
		 }
		 $bunit->setAttrib('onchange', 'changeStatusBusiness(this.value,"'.$data['bunit_id'].'")');
		 
		 $department_id = $this->CreateElement('select', 'department_id')
		          ->setLabel('Department', 'nl')
                  ->setDecorators($this->element);
		 $department_id->addMultiOptions(array(''=>'--Select--'));
		 $department_id->setAttrib('onchange', 'changeStatusDepartment(this.value,"'.$data['department_id'].'")');
				  
				  
		 $designation_id = $this->CreateElement('select', 'designation_id')
		          ->setLabel('Designation', 'nl')
                  ->setDecorators($this->element);
		 $designation_id->addMultiOptions(array(''=>'--Select--'));
		 //$designation_id->setAttrib('onchange', 'changeStatusdesignation(this.value,"'.$data['designation_id'].'")');
				  
		 $provident_percentage = $this->CreateElement('text', 'provident_percentage')
		          ->setLabel('Provident Percentage', 'nl')
                  ->setDecorators($this->element)->setValue($data['provident_percentage']);		  		  		  
		
		
		 $submit = $this->CreateElement('submit', $data['Mode'])
                  ->setDecorators($this->withoutlabel);
		$this->addElements(array($bunit,$department_id,$designation_id,$provident_percentage,$submit));
		return $this;
	}

}
?>