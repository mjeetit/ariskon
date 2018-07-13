<?php
class Crm_Form_LeaveRequestForm extends Zend_Form {
	
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
						 array(array('data'=>'HtmlTag'),array('tag'=>'td','colspan'=>2,'align'=>'left')),
						 array(array('row'=>'HtmlTag'),array('tag'=>'tr')));			 
						 
    public function init() {
		//
    }
	
	public function leaverequest($data=array()){
			
			$this->addElement('hidden','from',array(
				'description' => '<strong>Leave From</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));							
			
			$this->addElement('hidden','from1',array(
				'description' => '<input id="leave_from" name="leave_from" class="input-short">&nbsp;<span id="errfrom" class="error"></span>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));
			
			$this->addElement('hidden','to',array(
				'description' => '<strong>Leave To</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));							
			
			$this->addElement('hidden','to1',array(
				'description' => '<input id="leave_to" name="leave_to" class="input-short">&nbsp;<span id="errto" class="error"></span>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));
		
		$times = array(1=>'Half Day');
	     $jountume = $this->CreateElement('select', 'halfday_time')
		          ->setLabel('Half Day', 'nl')->setAttrib("class","input-short")
                  ->setDecorators($this->elementeven);
         $jountume->addMultiOptions(array(''=>'--Select if Need Half day--'));
		 foreach($times as $key=>$time){
		  
	     $jountume->addMultiOptions(array($key=>$time));

		 }
		$this->addElements(array($jountume));
		
		
		
		/*foreach($data['LeaveTypes'] as $key=>$leaveType) {
			$leaves .= $leaveType['typeID'].',';
			$leaveTypes = $this->CreateElement('text','leaveDays_'.$leaveType['typeID'])
		          			   ->setLabel($leaveType['typeName'].' ['.$data['RestLeaves'][$leaveType['typeID']].']', 'nl')
                  			   ->setDecorators($this->element)
							   ->setOptions(array('size'=>'4','onblur'=>"$.checkLeave(".$leaveType['typeID'].");"))
							   ->setRequired(true)
							   ->addValidator(new Zend_Validate_Int())
							   ->addValidator(new Zend_Validate_Between(1,25));				
			
			$valid = $this->CreateElement('hidden',"rest_".$leaveType['typeID'])->setValue($data['RestLeaves'][$leaveType['typeID']]);
			$this->addElements(array($leaveTypes,$valid));
		}*/
		
		foreach($data['LeaveTypes'] as $key=>$leaveType) {
			$leaves .= $leaveType['typeID'].',';
			
			$this->addElement('hidden','leav'.$key,array(
				'description' => '<strong>'.$leaveType['typeName'].' ['.$data['RestLeaves'][$leaveType['typeID']].']</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));							
			
			$this->addElement('hidden','leave'.$key,array(
				'description' => '<input id="leaveDays_'.$leaveType['typeID'].'" class="input-short" type="text" name="leaveDays_'.$leaveType['typeID'].'" onblur="$.checkLeave('.$leaveType['typeID'].');" onclick="autofileleave('.$leaveType['typeID'].');"><input type="hidden" id="rest_'.$leaveType['typeID'].'" value="'.$data['RestLeaves'][$leaveType['typeID']].'">&nbsp;<span id="err_'.$leaveType['typeID'].'"></span>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));
		}
		
		//print_r($leaves);die;
		$leaveIDs = $this->CreateElement('hidden',"loop")->setValue(substr($leaves,0,-1));
		$subject = $this->CreateElement('text','subject')
		          		->setLabel('Subject', 'nl')->setAttrib('class','input-short')
                  		->setDecorators($this->elementeven)
						->setOptions(array('size'=>'32','autocomplete'=>'off'))
					    ->setRequired(true)
					    ->addValidator(new Zend_Validate_Alpha(true));
						 
	    $editor = new My_Form_Element_Ckeditor('contents',array('required'=>true,'label'=>'template_message'));
		$editor->setAttrib('Rows','12');
		$editor->setAttrib('Cols','45');
		$editor->setAttrib('id','ckeditor');
		$editor->setLabel('Leave Contents :');
		$editor->setDecorators($this->elementodd);
		
		$submit = $this->CreateElement('button','Send Request')
                  ->setDecorators($this->withoutlabel)->setAttrib('class','submit-green')->setAttrib('onclick','checkDatevalidate()');
				  
		$this->addElements(array($leaveIDs,$subject,$editor,$submit));
		
		return $this;
	}
	
	public function leavereply($data){
	    $approved_approval = $this->CreateElement('hidden', 'approved_approval')
							->setValue($data['RequestDetail']['approved_approval'])
							->removeDecorator('label')
							->removeDecorator('HtmlTag');
		$rejected_approval = $this->CreateElement('hidden', 'rejected_approval')
							->setValue($data['RequestDetail']['rejected_approval'])
							->removeDecorator('label')
							->removeDecorator('HtmlTag');
		$this->addElements(array($approved_approval,$rejected_approval));

                $this->addElement('hidden','from_label',array(
				'description' => '<strong>From Date</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));

		$this->addElement('hidden','from_text'.$key,array(
				'description' => $data['RequestDetail']['leave_from'],
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));

                 $this->addElement('hidden','to_label',array(
				'description' => '<strong>To Date</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));

		$this->addElement('hidden','to_text'.$key,array(
				'description' => $data['RequestDetail']['leave_to'],
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));

  		
		foreach($data['LeaveTypes'] as $key=>$leaveType) {
			$leaves .= $leaveType['typeID'].',';
			
			$this->addElement('hidden','leav'.$key,array(
				'description' => '<strong>'.$leaveType['typeName'].' ['.$data['RestLeaves'][$leaveType['typeID']].']</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));							
			
			$this->addElement('hidden','leave'.$key,array(
				'description' => '<input id="leaveDays_'.$leaveType['typeID'].'" class="input-short" type="hidden" name="leaveDays_'.$leaveType['typeID'].'" onblur="$.checkLeave('.$leaveType['typeID'].');" value="'.$data['RequestDetail']['leave_types'][$leaveType['typeID']].'"><input type="hidden" id="rest_'.$leaveType['typeID'].'" value="'.$data['RestLeaves'][$leaveType['typeID']].'">&nbsp;<span id="err_'.$leaveType['typeID'].'">'.$data['RequestDetail']['leave_types'][$leaveType['typeID']].'</span>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));
		}
		
		//print_r($leaves);die;
		$leaveIDs = $this->CreateElement('hidden',"loop")->setValue(substr($leaves,0,-1));
		
		$this->addElement('hidden','subject_label',array(
				'description' => '<strong>Subject</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));							
			
		$this->addElement('hidden','subject_text'.$key,array(
				'description' => $data['RequestDetail']['subject'],
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));
		$this->addElement('hidden','message_label',array(
				'description' => '<strong>Message</strong>',
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left','class'=>'bold_text')),
				  array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'openOnly'=>true))
				),
			));							
			
		$this->addElement('hidden','message_text'.$key,array(
				'description' => $data['RequestDetail']['contents'],
				'ignore' => true,
				'decorators' => array(
					array('Description', array('escape'=>false, 'tag'=>'td','align'=>'left')),
					array(array('row' => 'HtmlTag'), array('tag' => 'tr', 'class' => "even",'closeOnly'=>true))
				),
			));
						 
	    $editor = new My_Form_Element_Ckeditor('remarks',array('required'=>true,'label'=>'template_reply'));
		$editor->setAttrib('Rows','12');
		$editor->setAttrib('Cols','45');
		$editor->setAttrib('id','ckeditor');
		$editor->setLabel('Remarks :')->setValue($data['RequestDetail']['remarks']);
		$editor->setDecorators($this->elementodd);
		
		$approve = $this->CreateElement('submit','Approve')
                  ->setDecorators($this->withoutlabel)->setAttrib('class','submit-green');
		$reject = $this->CreateElement('submit','Reject')
                  ->setDecorators($this->withoutlabel)->setAttrib('class','submit-green');		  
				  
		$this->addElements(array($leaveIDs,$subject,$editor,$approve,$reject));
		
		return $this;
	}
	
	public function leavermark($data){
	    $editor = new My_Form_Element_Ckeditor('remarks',array('required'=>true,'label'=>'template_message'));
		$editor->setAttrib('Rows','12');
		$editor->setAttrib('Cols','55');
		$editor->setAttrib('id','ckeditor');
		$editor->setLabel('Remark Messages :');
		$editor->setDecorators($this->element);
		
		$type = $this->CreateElement('hidden',"type")->setValue(4);
		$submit = $this->CreateElement('submit','Remark')->setDecorators($this->withoutlabel);				  
		$this->addElements(array($editor,$type,$submit));
		
		return $this;
	}
	
	public function leavereply_Old($data){
	    $requestBy = $this->CreateElement('text','Name')
		          		  ->setLabel('Requested By :','nl')
                  		  ->setDecorators($this->element)
				  		  ->setValue($data['Name'])
						  ->setAttrib('readonly','readonly');
		
		$leaveFrom = $this->CreateElement('text','leave_from')
		          		  ->setLabel('Leave From','nl')
                  		  ->setDecorators($this->element)
				  		  ->setValue($data['leave_from'])
						  ->setAttrib('readonly','readonly');
				  
		$leaveTo = $this->CreateElement('text','leave_to')
		          		->setLabel('Leave To :','nl')
                  		->setDecorators($this->element)
				  		->setValue($data['leave_to'])
						->setAttrib('readonly','readonly');
				  
		$reqstDate = $this->CreateElement('text','request_date')
		          		  ->setLabel('Leave Request Date :','nl')
                  		  ->setDecorators($this->element)
				  		  ->setValue($data['request_date'])
						  ->setAttrib('readonly','readonly');
		
		$subject = $this->CreateElement('text','subject')
		          		->setLabel('Subject :','nl')
                  		->setDecorators($this->element)
				  		->setValue($data['subject'])
						->setAttrib('readonly','readonly');
		
		$content = $this->CreateElement('text','mesg')
		          		->setLabel('Leave Message :','nl')
                  		->setDecorators($this->element)
				  		->setValue(strip_tags($data['contents']))
						->setAttrib('readonly','readonly');
						 
	    $editor = new My_Form_Element_Ckeditor('contents',array('required'=>true,'label'=>'template_message'));
		$editor->setAttrib('Rows','12');
		$editor->setAttrib('Cols','55');
		$editor->setAttrib('id','ckeditor');
		$editor->setLabel('Reply Messages :');
		$editor->setDecorators($this->element);
		
		$submit = $this->CreateElement('submit','Reply')->setDecorators($this->withoutlabel);
				  
		$this->addElements(array($requestBy,$leaveFrom,$leaveTo,$reqstDate,$subject,$content,$editor,$submit));
		
		return $this;
	}
}
?>