<?php



class NotificationForm extends Zend_Form {

	

	public $_obj = NULL;

	public $element = array('ViewHelper',

						 'Description',

						 'Errors',

						 array(array('data' => 'HtmlTag'), array('tag' => 'td', 'align' => 'left')),

						 array('Label', array('tag' => 'td', 'class' => "bold_text")),

						 array(array('row' => 'HtmlTag'), array('tag' => 'tr')));
	
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

	  $this->_obj	= new NotificationManager();	

    }



	public function MessageForm(){

	    
	       $units = $this->_obj->getMessageUserID();
		 $designation_id = $this->CreateElement('multiselect', 'user_id')

		          ->setLabel('Employee', 'nl')->setAttribs(array("class"=>"input-medium",'style'=>"height:200px;"))

                  ->setDecorators($this->elementeven);

		 $designation_id->addMultiOptions(array(''=>'--Select--'));
		 foreach($units as $business){

		     if($data['bunit_id']==$business['bunit_id']){

			  $designation_id->setValue($business['bunit_id']);

			 }		  

	    	 $designation_id->addMultiOptions(array($business['user_id']=>$business['employee_code']." ".$business['first_name']." ".$business['last_name']." (".$business['designation_code'].")"));

		 }
		 //$designation_id->setAttrib('onchange', 'changeStatusdesignation(this.value,"'.$data['designation_id'].'")');
		if($_SESSION['AdminLoginID']==1){
		 //$zones = $this->_obj->getZomesList();
       
	     /*$zone_id = $this->CreateElement('select', 'zone_id')

		          ->setLabel('Zone', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementeven);
         $zone_id->addMultiOptions(array('0'=>'--Select Zone--'));
		 foreach($zones as $zone){

		     if($data['zone_id']==$business['zone_id']){

			  $zone_id->setValue($business['zone_id']);

			 }		  

	    	 $zone_id->addMultiOptions(array($zone['zone_id']=>$zone['zone_name']));

		 }

		 $zone_id->setAttrib('onchange', 'getNextRecord(this.value,6,"region_id","'.$data['zone_id'].'");');
		 
		 $region = $this->CreateElement('select', 'region_id')

		          ->setLabel('Region', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementodd);

		 $region->addMultiOptions(array(''=>'--Select--'));

		 $region->setAttrib('onchange', 'getNextRecord(this.value,7,"area_id","'.$data['region_id'].'");');
		 
		 
		 
		  $area_id = $this->CreateElement('select', 'area_id')

		          ->setLabel('Area', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementodd);

		 $area_id->addMultiOptions(array(''=>'--Select--'));

		 $area_id->setAttrib('onchange', 'getNextRecord(this.value,8,"headquater_id","'.$data['area_id'].'");');
		 
		  
		 $headquater_id = $this->CreateElement('select', 'headquater_id')

		          ->setLabel('HeadQuater', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementodd);

		 $headquater_id->addMultiOptions(array(''=>'--Select--'));

		 $headquater_id->setAttrib('onchange', 'getNextRecord(this.value,9,"city_id","'.$data['headquater_id'].'");');
		 
		  $city_id = $this->CreateElement('select', 'city_id')

		          ->setLabel('City', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementodd);*/

     }
				  

	    $editor = new My_Form_Element_Ckeditor('description', array('required'=>true, 'label'=>'template_message'));

		$editor->setAttrib('Rows','12');

		$editor->setAttrib('Cols','50');

		$editor->setAttrib('id','ckeditor');

		$editor->setLabel('Message:');

		$editor->setDecorators($this->elementodd);

		

		$submit = $this->CreateElement('submit', 'Add')
                  ->setDecorators($this->withoutlabel)->setAttrib("class","submit-green");

		$this->addElements(array($bunit,$department_id,$designation_id,$zone_id,$region,$area_id,$headquater_id,$city_id,$editor,$submit));

		return $this;

	}

	public function EventForm(){

	     $units = $this->_obj->getBusinessToCompany();

		 $event_name = $this->CreateElement('text', 'event_name')->setAttrib("class","input-medium")

		          ->setLabel('Event Name', 'nl')

                  ->setDecorators($this->elementeven);

				  

		 $event_date = $this->CreateElement('text', 'event_date')->setAttrib("class","input-short")

		          ->setLabel('Event Date', 'nl')

                  ->setDecorators($this->elementodd);

	     $bunit = $this->CreateElement('select', 'bunit_id')

		          ->setLabel('Business Unit', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementeven);

		 foreach($units as $business){

		     if($data['bunit_id']==$business['bunit_id']){

			  $bunit->setValue($business['bunit_id']);

			 }		  

	    	 $bunit->addMultiOptions(array($business['bunit_id']=>$business['bunit_name']));

		 }

		 $bunit->setAttrib('onchange', 'changeStatusBusiness(this.value,"'.$data['bunit_id'].'")');

		 

		 $department_id = $this->CreateElement('select', 'department_id')

		          ->setLabel('Department', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementodd)->setAttrib("class","input-medium");

		 $department_id->addMultiOptions(array(''=>'--Select--'));

		 $department_id->setAttrib('onchange', 'changeStatusDepartment(this.value,"'.$data['department_id'].'")');

				  
				  

		 $designation_id = $this->CreateElement('select', 'designation_id')

		          ->setLabel('Designation', 'nl')->setAttrib("class","input-medium")

                  ->setDecorators($this->elementeven);

		 $designation_id->addMultiOptions(array(''=>'--Select--'));

		 //$designation_id->setAttrib('onchange', 'changeStatusdesignation(this.value,"'.$data['designation_id'].'")');

	

	    $editor = new My_Form_Element_Ckeditor('event_description', array('required'=>true, 'label'=>'template_message'));

		$editor->setAttrib('Rows','12');

		$editor->setAttrib('Cols','50');

		$editor->setAttrib('id','ckeditor');

		$editor->setLabel('Event Description:');

		$editor->setDecorators($this->elementodd);

		

		$submit = $this->CreateElement('submit', 'Add')->setAttrib("class","submit-green")

                  ->setDecorators($this->withoutlabel);

		$this->addElements(array($event_name,$event_date,$bunit,$department_id,$designation_id,$editor,$submit));

		return $this;

	}



}

?>