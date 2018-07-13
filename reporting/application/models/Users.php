<?php

class Users extends Zend_Custom

{
	 public $_getData = array();
  	 public function getDepartmentByBunitId(){

	    $select = $this->_db->select()

		 				->from(array('D2B'=>'department_to_bunit'),array('*'))

		 				->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=D2B.bunit_id",array('bunit_name'))

						->joininner(array('DT'=>'department'),"DT.department_id=D2B.department_id",array('department_name'))

						->where("D2B.bunit_id='".$this->_getData['bunit_id']."'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	public function getDesignationByDepartmentId(){

	    $select = $this->_db->select()

		 				->from(array('D2D'=>'designation_to_department'),array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=D2D.designation_id",array('designation_name'))

						->where("D2D.department_id='".$this->_getData['department_id']."'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	public function addNewUser(){

	    $this->_getData['password'] = md5($this->_getData['password']);

	    $user_id = $this->insertInToTable('users',array($this->_getData));

		$this->_getData['user_id'] = $user_id;

		$this->_getData['employee_code'] = $this->generateEmployeeCode();

		$this->insertInToTable('employee_personaldetail',array($this->_getData));

		$this->insertEducation();

		$this->insertslaryAmount();

	    $this->insertEmployeementDetail();

	    $this->insertleavedetail();

		$this->DocumentUploade();

		$this->insertUpdateAccountDetail();
		
		$this->insertUpdateLocations();
		$this->insertUpdateExpenses();

	}

	public function editUser(){
	  unset($this->_getData['id']);
	  if(!empty($this->_getData['officialdetail']) || !empty($this->_getData['personal_detail'])){
			$record = $this->getStoreOldRecord();
			unset($record['id']);
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_ip']=$_SERVER['REMOTE_ADDR'];
			$record['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_personaldetail',array($record));
			$this->updateTable('employee_personaldetail',$this->_getData,array('user_id'=>$this->_getData['user_id']));
			$this->updateTable('emp_bank_account',$this->_getData,array('user_id'=>$this->_getData['user_id']));
			$this->_db->update('users',array('user_type'=>$this->_getData['user_type']),"user_id='".$this->_getData['user_id']."'");
	  }
	  if(!empty($this->_getData['education'])){
			$Education	  = $this->getEducations($this->_getData['user_id'],'CURDATE() AS modify_date');
			unset($Education['id']);
			$this->insertInToTable('edit_emp_education',$Education);
			$this->insertEducation();
	  }
	  if(!empty($this->_getData['employeement'])){
			$Employeement = $this->getEmployeements($this->_getData['user_id'],'CURDATE() AS modify_date');
			unset($Employeement['id']);
			$this->insertInToTable('edit_employeement_detail',$Employeement);
			$this->insertEmployeementDetail();
	  }
	  if(!empty($this->_getData['salary_deatil'])){ 
	      $select = $this->_db->select()
		 				->from(array('ES'=>'employee_salary_amount'),array('*','CURDATE() AS modify_date'))
						->where("ES.user_id='".$this->_getData['user_id']."'");
		    $salaries = $this->getAdapter()->fetchAll($select);
			unset($salaries['id']);
			$this->insertInToTable('edit_emp_salary_amount',$salaries);
		    $this->insertslaryAmount();
			if(!empty($this->_getData['ctc_change_date']) && $this->_getData['ctc_change_date']!='0000-00-00'){
			  $this->_db->update('employee_personaldetail',array('ctc_change_date'=>$this->_getData['ctc_change_date']),"user_id='".$this->_getData['user_id']."'");
			}
	  }
	  if(!empty($this->_getData['documents'])){
	     $Documents    = $this->getDocumentsInfo($this->_getData['user_id'],'CURDATE() AS modify_date');
		 unset($Documents['id']);
		$this->insertInToTable('edit_emp_education',$Documents);
		 $this->DocumentUploade();
	  }
	  if(!empty($this->_getData['leaves'])){
	        $select = $this->_db->select()
		 				->from(array('ES'=>'emp_leaves'),array('*','CURDATE() AS modify_date'))
						->where("ES.user_id='".$this->_getData['user_id']."'");
		    $leaves = $this->getAdapter()->fetchAll($select);
			 unset($leaves['id']);
			$this->insertInToTable('edit_emp_leaves',$leaves);
	        $this->insertleavedetail();
	  }
	  if(!empty($this->_getData['accounts'])){
	        $Account      = $this->getBankAccountDetail($this->_getData['user_id']);
			unset($Account['id']);
			$Account['user_id'] = $this->_getData['user_id'];
			$Account['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_bank_account',array($Account));
		    $this->insertUpdateAccountDetail(false);
	  }
	  if(!empty($this->_getData['location'])){
			$record = $this->getStoreOldRecord();
			unset($record['id']);
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_locations',array($record));
			$this->insertUpdateLocations(false);
	  }
	  if(!empty($this->_getData['expense'])){
	        $select = $this->_db->select()
		 				->from(array('ES'=>'emp_expense_amount'),array('*','CURDATE() AS modify_date'))
						->where("ES.user_id='".$this->_getData['user_id']."'");
		    $Expenses = $this->getAdapter()->fetchAll($select);
			unset($Expenses['id']);
			$this->insertInToTable('edit_emp_exp_amount',$Expenses);
	        $this->insertUpdateExpenses();
	  }
	 
	}

	

	public function insertEducation(){
     if(!empty($this->_getData['degree'])){
	   $this->_db->delete('employee_education',"user_id='".$this->_getData['user_id']."'");
	   foreach($this->_getData['degree'] as $key=>$education){

	       $this->_db->insert('employee_education',array_filter(array('user_id'=>$this->_getData['user_id'],

		   					  'degree'=>$education,'degree_name'=>$this->_getData['degree_name'][$key],

		   					  'collage'=>$this->_getData['collage'][$key],'board'=>$this->_getData['board'][$key],

							  'per_mark'=>$this->_getData['per_mark'][$key],'year_passing'=>$this->_getData['year_passing'][$key])));

	   }
	  }

	}

	

	public function insertslaryAmount(){
	  if(!empty($this->_getData['amount'][1]) || $this->_getData['amount'][2]){
	   $this->_db->delete('employee_salary_amount',"user_id='".$this->_getData['user_id']."'");

	    foreach($this->_getData['amount'][1] as $key=>$sal_amount){

	       $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$key,'salaryheade_type'=>1,

		   					  'amount'=>$sal_amount)));

	    }

	   foreach($this->_getData['amount'][2] as $key=>$sal_amount){

	       $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$key,'salaryheade_type'=>2,

		   					  'amount'=>$sal_amount)));

	    }
	  }	
    if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	      $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));
		 }					  
	  }			

	}

	

	public function insertEmployeementDetail(){

	   $this->_db->delete('emp_employeement_detail',"user_id='".$this->_getData['user_id']."'");

	   $companies = array_filter($this->_getData['company']);

	   foreach($companies as $key=>$company){

	       $this->_db->insert('emp_employeement_detail',array_filter(array('user_id'=>$this->_getData['user_id'],'compnay'=>$company,'designation'=>$this->_getData['designation'][$key],'from_year'=>$this->_getData['from_year'][$key],'from_month'=>$this->_getData['from_month'][$key],'to_year'=>$this->_getData['to_year'][$key],'to_month'=>$this->_getData['to_month'][$key],'joining_ctc'=>$this->_getData['joining_ctc'][$key],'leaving_ctc'=>$this->_getData['leaving_ctc'][$key],'reasion_of_leaving'=>$this->_getData['reasion_of_leaving'][$key])));

	    }

	}

	

    public function DocumentUploade() {
	  if(!empty($this->_getData['document_type'])){
        $upload = new Zend_File_Transfer();

        $file = $upload->getFileInfo();

		$time = time();

		foreach($this->_getData['document_type'] as $key=>$docuploade){

		    $Filename = $time.$file['documnet_'.$key.'_']['name']; 

			$upload->addFilter('Rename', array('target' => Bootstrap::$root.'/public/DocumentDirectory/'.$Filename, 'overwrite' => true));

			$upload->receive($file['documnet_'.$key.'_']['name']);

			 $this->_db->insert('emp_docoments',array_filter(array('user_id'=>$this->_getData['user_id'],'file_name'=>$Filename,'document_type'=>$docuploade)));

			

		}
	  }	

    }

	

	public function insertleavedetail(){
      if(!empty($this->_getData['leave_id'])){
	   $this->_db->delete('emp_leaves',"user_id='".$this->_getData['user_id']."'");
		foreach($this->_getData['leave_id'] as $key=>$leave_id){
	       $this->_db->insert('emp_leaves',array_filter(array('user_id'=>$this->_getData['user_id'],'leave_id'=>$leave_id,'no_of_leave'=>$this->_getData['no_of_leave'][$key],'aproval_authority'=>implode(',',$this->_getData['aproval_authority']))));

	    }
	  }
	  
	  if(!empty($this->_getData['leave_approve_auth'])){
	     $this->_db->delete('emp_leave_approval',"user_id='".$this->_getData['user_id']."'");
            foreach($this->_getData['leave_approve_auth'] as $key=>$approveAuth){
			  $this->_db->insert('emp_leave_approval',array_filter(array('user_id'=>$this->_getData['user_id'],'approval_user_id'=>$approveAuth,'position'=>$this->_getData['authority_position'][$key])));
			}
		}
	}

	

	public function insertUpdateAccountDetail($insert=true){

		if($insert==true){

		    $this->insertInToTable('emp_bank_account',array($this->_getData));

		}else{

		    $this->updateTable('emp_bank_account',$this->_getData,array('user_id'=>$this->_getData['user_id']));

		}	

	}
	
	public function insertUpdateLocations($insert=true){
	    if($insert){
		      $this->insertInToTable('emp_locations',array($this->_getData));
		}else{
		      $this->updateTable('emp_locations',$this->_getData,array('user_id'=>$this->_getData['user_id']));
		}
	}
 
   public function insertUpdateExpenses(){		
		if(!empty($this->_getData['head_id'])){
		   $this->_db->delete('emp_expense_amount',"user_id='".$this->_getData['user_id']."'");
            foreach($this->_getData['head_id'] as $key=>$head_id){
			  $this->_db->insert('emp_expense_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'head_id'=>$head_id,'expense_amount'=>$this->_getData['expense_amount'][$key])));
			}
		}
		if(!empty($this->_getData['expese_approve_auth'])){
		   $this->_db->delete('expense_approval',"user_id='".$this->_getData['user_id']."'");
            foreach($this->_getData['expese_approve_auth'] as $key=>$approveAuth){
			  $this->_db->insert('expense_approval',array_filter(array('user_id'=>$this->_getData['user_id'],'approval_user_id'=>$approveAuth,'position'=>$this->_getData['position'][$key])));
			}
		}
 	}
		

	public function getUsers(){

	     $select = $this->_db->select()

		 				->from(array('UT'=>'employee_personaldetail'),array('*'))

		 				->joinleft(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))

						->joinleft(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->joinleft(array('US'=>'users'),"US.user_id=UT.user_id",array('username','passwowrd_text'))
						->where("delete_status='0'")
						->order("employee_code");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

     

	public function EditUserDetail(){

	     $select = $this->_db->select()

		 				->from(array('UT'=>'users'),array('*'))

		 				->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=UT.user_id",array('*'))

						->where("UT.user_id='".$this->_getData['user_id']."'");

		//echo $select->__toString();die;

		 $result = $this->getAdapter()->fetchRow($select);

		 

		 $select = $this->_db->select()

		 				->from(array('EE'=>'employee_education'),array('*'))

						->where("EE.user_id='".$this->_getData['user_id']."'")
						->order("degree");

		 $education = $this->getAdapter()->fetchAll($select);

		 $result['Education'] = $education;

		 

		 $select = $this->_db->select()

		 				->from(array('ED'=>'emp_employeement_detail'),array('*'))

						->where("ED.user_id='".$this->_getData['user_id']."'");

		 $employeement = $this->getAdapter()->fetchAll($select);

		 $result['Employeement'] = $employeement;

		 

		 $select = $this->_db->select()

		 				->from(array('DT'=>'emp_docoments'),array('*'))

						->where("DT.user_id='".$this->_getData['user_id']."'");

		 $documents = $this->getAdapter()->fetchAll($select);

		 $result['Document'] = $documents;

		 

		 $select = $this->_db->select()

		 				->from(array('AD'=>'emp_bank_account'),array('*'))

						->where("AD.user_id='".$this->_getData['user_id']."'");

		 $accountDetail = $this->getAdapter()->fetchRow($select);

		 $result['AccountDetail'] = $accountDetail;

		 $result['Location'] = $this->getlocationInfo($this->_getData['user_id']);
			///print_r($result['Location']);die;
		return $result; 

	}

    

    public function UpdateUserDetail(){

       $updata = $this->_getData;

       unset($updata['user_id']);

       $this->updateTable('user_detail',$updata,array('user_id'=>$this->_getData['user_id']));

    }

	

    public function generateEmployeeCode(){

       $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('employee_code'))

						->order("employee_code DESC")

						->limit(1);

	   $emp_code = $this->getAdapter()->fetchRow($select);

	   if($emp_code['employee_code']!=''){

	     $substr = substr($emp_code['employee_code'],3);

	     $empcode = 'JC'. str_pad(($substr + 1),5,'0',STR_PAD_LEFT);

	   }else{

	     $empcode = 'JC00001';

	   }

	   return $empcode;

   }

   public function viewUserDetail(){

       $user_type = $this->getUserType($user_id);

	   $dataarray = array();

       $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('*'))

						->joinleft(array('PU'=>'employee_personaldetail'),"PU.user_id=UD.parent_id",array('CONCAT(PU.first_name," ",PU.last_name) as Parent'))

						->joinleft(array('BU'=>'bussiness_unit'),"BU.bunit_id=UD.bunit_id",array('bunit_name'))

						->joinleft(array('DP'=>'department'),"DP.department_id=UD.department_id",array('department_name'))

						->joinleft(array('DG'=>'designation'),"DG.designation_id=UD.designation_id",array('designation_name'))

						->joinleft(array('HO'=>'headoffice'),"HO.headoff_id=UD.office_id",array('office_name'))

						->where("UD.user_id='".$this->_getData['user_id']."'");

	   $dataarray = $this->getAdapter()->fetchRow($select);

	   $dataarray['Education'] 	  = $this->getEducations($this->_getData['user_id']);

	   $dataarray['Employeement'] = $this->getEmployeements($this->_getData['user_id']);

	   $dataarray['Account']      = $this->getBankAccountDetail($this->_getData['user_id']);

	   $dataarray['Documents']    = $this->getDocumentsInfo($this->_getData['user_id']);
	   $dataarray['Location']    = $this->getlocationInfo($this->_getData['user_id']);

	  return $dataarray; 

   }				
   public function AddPrivillage(){
      $this->_db->delete('user_privillage',"user_id='".$this->_getData['user_id']."'");
	  if(!empty($this->_getData['tree'])){
		  foreach($this->_getData['tree'] as $module_id){
			 $this->_db->insert('user_privillage',array('user_id'=>$this->_getData['user_id'],'module_id'=>$module_id)); 
		  }
	 }
   }
   public function getUsersPrivForEdit(){
      $select = $this->_db->select()
	  					   ->from('user_privillage',array('*'))
						   ->where("user_id='".$this->_getData['user_id']."'");
	   $provillages = $this->getAdapter()->fetchAll($select);
	    $PrivillageArr = array();
	   if(!empty($provillages))	{
	        foreach($provillages as $priv){
			   $PrivillageArr[] = $priv['module_id'];
			 }
	   }
	   return  $PrivillageArr;				   
   }
   
   public function getParentListForAssign(){
      /*$select = $this->_db->select()
	  					   ->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name'))
						   ->joinleft(array('PED'=>'employee_personaldetail'),"PED.user_id='".$this->_getData['user_id']."'",array())
						   ->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_code'))
						   ->where("ED.designation_id=PED.designation_id AND ED.department_id=PED.department_id AND ED.user_id!=PED.user_id");*/
		 $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())
						->where("DT1.designation_level>=DT.designation_level AND ED.delete_status='0'");
	   $parents = $this->getAdapter()->fetchAll($select);
	   return $parents;
   }
   public function deleteusers(){
          $this->_db->update('employee_personaldetail',array('parent_id'=>$this->_getData['parent_id']),"parent_id='".$this->_getData['user_id']."'");
	  $this->_db->update('emp_leave_approval',array('approval_user_id'=>$this->_getData['parent_id']),"approval_user_id='".$this->_getData['user_id']."'");
	  $this->_db->update('expense_approval',array('approval_user_id'=>$this->_getData['parent_id']),"approval_user_id='".$this->_getData['user_id']."'");
	  $this->_db->update('employee_personaldetail',array_filter(array('delete_status'=>'1','reasion'=>$this->_getData['reasion'])),"user_id='".$this->_getData['user_id']."'");
	  
	  $_SESSION[SUCCESS_MSG] = 'Employee Deleted Successfully';
   }
   
   public function changePassword(){
      $this->_db->update('users',array('password'=>md5($this->_getData['password']),'passwowrd_text'=>addslashes($this->_getData['password'])),"user_id='".$this->_getData['user_id']."'");
	  $_SESSION[SUCCESS_MSG] = 'Password Hase been change successfully';
   }
   public function previusemployee(){
     $select = $this->_db->select()

		 				->from(array('UT'=>'employee_personaldetail'),array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->joinleft(array('US'=>'users'),"US.user_id=UT.user_id",array('username','passwowrd_text'))
						->where("delete_status='1'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;
   }
   
    public function getStoreOldRecord(){
         $select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array('*'))
		 				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array('*'))
						->where("UT.user_id='".$this->_getData['user_id']."'");
		//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
		return $result;
   }	
   
    public function getOldRecordOfEmployee(){
	   $select = $this->_db->select()
		 				->from(array('UT'=>'emp_old_record'),array('*'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'));
		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;
	}
   public function storeHistory(){
			$Education	  = $this->getEducations($this->_getData['user_id']);
			$Employeement = $this->getEmployeements($this->_getData['user_id']);
			$Account      = $this->getBankAccountDetail($this->_getData['user_id']);
			$Documents    = $this->getDocumentsInfo($this->_getData['user_id']);
			//$Location     = $this->getlocationInfo($this->_getData['user_id']);
	     	$record = $this->getStoreOldRecord();
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_ip']=$_SERVER['REMOTE_ADDR'];
			//$record['modify_by']=$_SESSION['AdminLoginID'];
			$record['modify_date']=date('Y-m-d');
		    $this->insertInToTable('edit_emp_personaldetail',array($record));
			$this->insertInToTable('edit_emp_locations',array($record));
			//$this->insertInToTable('edit_emp_bank_account',array($record));
			//$this->insertInToTable('edit_emp_locations',array($record));
			//$this->insertInToTable('edit_emp_locations',array($record));
   }
   public function getUserHistory(){
     $select = $this->_db->select()
		 				->from(array('UT'=>'edit_emp_personaldetail'),array('*'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->joinleft(array('EL'=>'edit_emp_locations'),"EL.user_id=UT.user_id",array('*'))
						->where("UT.user_id='".$this->_getData['user_id']."'");
		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;
   }
  public function getempleaves(){
    $select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array('employee_code','user_id'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->where("delete_status='0'");
	$results = $this->getAdapter()->fetchAll($select);
	
	$select = $this->_db->select()
		 				->from(array('LT'=>'leavetypes'),array('*'));//echo $select->__toString();die;
	$leavetype = $this->getAdapter()->fetchAll($select);
	   
	foreach($results as $leave){
	   $select = $this->_db->select()
		 				->from(array('EL'=>'emp_leaves'),array('*'))
						->where("user_id='".$leave['user_id']."'");//echo $select->__toString();die;
	   $empleaves = $this->getAdapter()->fetchAll($select);//print_r($empleaves);die;
	   foreach($empleaves as $empleave){
	     $leave['Leave'][$empleave['leave_id']] = $empleave['no_of_leave'];
	   }
	   $finaldata[] = $leave;  
	} //print_r($finaldata);die;
	return array($finaldata,$leavetype);
  }
  
  public function updateEmployeeLeaves(){
    //echo "<pre>"; print_r($this->_getData); echo "<pre>";die;
	 if(!empty($this->_getData['user_id'])){
	    foreach($this->_getData['user_id'] as $user_id){
		       $select = $this->_db->select()
		 				->from(array('EL'=>'emp_leaves'),array('COUNT(1) AS CNT'))
						->where("user_id='".$user_id."'");//echo $select->__toString();die;
	   		   $empleaves = $this->getAdapter()->fetchRow($select);
			if($empleaves['CNT']>0){  
                         foreach($this->_getData['leave'][$user_id] as $key=>$leaves){
                                 $this->_db->update('emp_leaves',array('no_of_leave'=>$leaves),"user_id='".$user_id."' AND leave_id='".$key."'");
                             }
		     }else{
			    foreach($this->_getData['leave'][$user_id] as $key=>$leaves){ 
			    $this->_db->insert('emp_leaves',array_filter(array('no_of_leave'=>$leaves,'user_id'=>$user_id,'leave_id'=>$key)));
			  }	
			}	 
		}
	 }
  }
  public function usersettlementDetail(){
        $select = $this->_db->select()
                                ->from(array('EPD'=>'employee_personaldetail'),array(''))
                                ->joininner(array('EAA'=>'emp_acceseries_account'),"EAA.user_id=EPD.user_id",array('*'))
                                ->where("EPD.employee_code='".$this->_getData['employee_code']."'");//echo $select->__toString();die;
	$acceseries['GeneralDetail'] = $this->getAdapter()->fetchAll($select);
	
	$select = $this->_db->select()
                                ->from(array('UT'=>'employee_salary_amount'),array('*'))
                                ->joininner(array('SH'=>'salary_head'),"SH.salaryhead_id=UT.salaryhead_id",array("salary_title"))
								->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=UT.user_id",array(''))
                                ->where("EPD.employee_code='".$this->_getData['employee_code']."'")
                                ->order("salaryhead_id ASC");
                                //echo $select->__toString();die;
	 $acceseries['SalaryDetail']  = $this->getAdapter()->fetchAll($select);							
      return $acceseries;
  }
  public function finalsettlement(){
	 if(!empty($this->_getData['final_settlement'])){
	     foreach($this->_getData['final_settlement'] as $key=>$account_id){
	     	$this->_db->update('emp_acceseries_account',array('final_settlement'=>$account_id),"emp_acc_id='".$key."'");
		 }
		 $this->_db->insert('emp_settlement',array_filter(array('resign_date'=>$this->_getData['resign_date'],'user_id'=>$this->_getData['user_id'],'notice_day'=>$this->_getData['user_id'],'sett_amount'=>$this->_getData['sett_amount'],'cheque_number'=>$this->_getData['cheque_number'])));
	 }
	 $_SESSION[SUCCESS_MSG] = 'Record Has been update succesfully';
  }
  
  public function updateslaryAmount(){
	  if(!empty($this->_getData['amount'][1]) || $this->_getData['amount'][2]){
	    foreach($this->_getData['amount'][1] as $key=>$sal_amount){
	       $this->_db->update('employee_salary_amount',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=1");

	    }
	   foreach($this->_getData['amount'][2] as $key=>$sal_amount){
	       $this->_db->update('employee_salary_amount',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=2");

	    }
	  }	
    if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	      $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));
		 }					  
	  }			

	}
}

?>