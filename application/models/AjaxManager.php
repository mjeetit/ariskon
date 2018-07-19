<?php

class AjaxManager extends Zend_Custom

{

  public $_getData = array();

  public function getSalaryTemplateAmount(){

    $result = array();

	   $select = $this->_db->select()

		 				->from('salary_template',array('*'))

						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 

								 AND designation_id ='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result;

  }

  public function getSalaryEmployee(){

      $select = $this->_db->select()

		 				->from('employee_salary_amount',array('*'))
						->joininner(array('SH'=>'salary_head'),'SH.salaryhead_id=employee_salary_amount.salaryhead_id',array())
						->where("user_id='".$this->_getData['user_id']."'")
						->order("employee_salary_amount.salaryheade_type ASC")
						->order("sequence ASC");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);
	   if(empty($result)){
	      $select = $this->_db->select()
		 				->from(array('ST'=>'salary_template'),array('*'))
						->joininner(array('SA'=>'salary_template_amount'),'SA.salary_template_id=ST.salary_template_id',array('*'))
						->joininner(array('SH'=>'salary_head'),'SH.salaryhead_id=SA.salaryhead_id',array('*','salary_type AS salaryheade_type'))
						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' AND designation_id ='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchAll($select);//print_r($result );die;
	   }

	   return $result;

  }

  public function getAmountByTemplateId($template_id,$head_id){

       $select = $this->_db->select()

		 				->from('salary_template_amount',array('*'))

						->where("salary_template_id='".$template_id."' AND salaryhead_id ='".$head_id."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result['amount'];

  }

  public function getLeaveDetail(){

    if(!empty($this->_getData['user_id'])){

	   $select = $this->_db->select()

		 				->from(array('LD'=>'emp_leaves'),array('leave_id as leave_type_id','no_of_leave as leave_no','aproval_authority','user_id'))

						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_id",array('typeName'))

						->where("user_id='".$this->_getData['user_id']."'");

	   $result = $this->getAdapter()->fetchAll($select);

	}

	if(empty( $result)){
	  /*$sixmonthback = strtotime(date('Y-m-d',mktime(0, 0, 0, date("m")-6,date('d'),  date("Y"))));
	  $joiningdate =  strtotime($this->_getData['doj']);
		
		$year1 = date('Y', $sixmonthback);
		$year2 = date('Y', $joiningdate);
		
		$month1 = date('m', $sixmonthback);
		$month2 = date('m', $joiningdate);
		
	  $diff = (($year1-$year2) * 12) + ($month1 - $month2);*/
	  //print_r($diff);die;
      if($this->_getData['emp_type']==2){
      $select = $this->_db->select()
		 				->from(array('LD'=>'leavedistributions'),array('designation_id','leave_type_id','prob_leaveno as leave_no'))
						->joinleft(array('LP'=>'leaveapprovals'),"LP.designation_id=LD.designation_id",array('approval_no'))
						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_type_id",array('typeName'))
						->where("LD.designation_id='".$this->_getData['designation_id']."'");
      }else{
	      $select = $this->_db->select()
		 				->from(array('LD'=>'leavedistributions'),array('*'))
						->joinleft(array('LP'=>'leaveapprovals'),"LP.designation_id=LD.designation_id",array('approval_no'))
						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_type_id",array('typeName'))
						->where("LD.designation_id='".$this->_getData['designation_id']."'");
	  }
						//echo $select->__toString();die;

	    $result = $this->getAdapter()->fetchAll($select);					

	 } 

	

	  return $result;

  }
  public function getParentdesig($desgnation_id){
       $select = $this->_db->select()
		 				->from(array('DT'=>'designation'),array('*'))
						->where("DT.designation_id='".$desgnation_id."'");
						//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchRow($select); 
	   if(!empty($result) && $result['designation_id']>1){
		   array_push($this->_getData['Desig'],$result['designation_level']);
	     return $this->getParentdesig($result['designation_level']);
	   }else{
	     return $this->_getData['Desig'];
	   }
  }

 public function getParnetByDesignationId(){
 		$this->_getData['Desig'] = array() ;
         $designations = $this->getParentdesig($this->_getData['designation_id']);//print_r($this->_getData['designation_id']);die;
           $select = $this->_db->select()
		 				->from(array('DTD'=>'designation_to_department'),array('*'))
						->where("DTD.designation_id='".$this->_getData['designation_id']."'");
		$department = $this->getAdapter()->fetchRow($select);
        // print_r($designations);print_r($result);die;
		if(!empty($designations) && $this->_getData['designation_id']==7){
		   $designations[] = 7;
		}
	 if(!empty($designations)){
	 $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name','user_id','employee_code'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->where("(ED.designation_id IN(".implode(',',$designations)."))  AND ED.delete_status='0'")
						->order("ED.designation_id ASC");
					//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;
	 }else{
	   return array();
	 }  

 } 
 
 	public function getDesignationChild(){
	    $select = $this->_db->select()
		 				->from(array('DT'=>'designation'),array('*'))
						->where("DT.parent_designation='".$this->_getData['designation_id']."'");//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchAll($select);
		return $result; 
	}
 public function getParnetByDesignationId_old(){

     $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('*'))
						->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joininner(array('DTD'=>'designation_to_department'),"DTD.department_id=ED.department_id",array(''))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())

						->where("DT1.designation_level>DT.designation_level  AND ED.delete_status='0' AND DTD.designation_id='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;

 } 

 public function ChangeStatusValue(){ 

    $this->_db->update($this->_getData['table'],array($this->_getData['change_fild']=>$this->_getData['changeMode']),"".$this->_getData['confield']."=".$this->_getData['con_id']."");

	echo $this->_getData['changeMode'];

	exit;

 }
 public function getUserListByDesignation(){
			$select = $this->_db->select()
								->from(array('ED'=>'employee_personaldetail'),array('*'))
								->where("designation_id='".$this->_getData['designation_id']."' AND department_id='".$this->_getData['department_id']."'");
								//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchAll($select);
		$string = '<option value="">--Select--</option>';
		foreach($result as $users){
		  $selected = '';
		  if($this->_getData['user_id']==$users['user_id']){
		   $selected = 'selected="selected"';
		  }
		  $string .= '<option value="'.$users['user_id'].'" '.$selected.'>'.$users['employee_code'].'-'.$users['first_name'].' '.$users['last_name'].'</option>';
		}
	  echo $string;exit;		
 } 
  public function geteXtraHead(){
    $string = '';
   if(!empty($this->_getData['salaryhead'])){
    $string ='<tr class="odd"><td>'. $this->getSalaryHeadName($this->_getData['salaryhead']).'</td><td><input type="text" name="extra_amount[]" class="input-short"><input type="hidden" name="extra_head[]" value="'.$this->_getData['salaryhead'].'"><input type="hidden" name="extra_type[]" value="'.$this->_getData['type'].'"></td></tr>';
  }
	echo $string;exit;
 } 
 
  public function getExpenseHead(){
    if(!empty($this->_getData['exp_setting_id'])){
		$select = $this->_db->select()
			->from(array('ETA'=>'expense_template_amount'),array('*'))
			->joinleft(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))
			->where("exp_setting_id='".$this->_getData['exp_setting_id']."'"); 
	 }else{
	    $select = $this->_db->select()
			->from(array('EH'=>'expense_head'),array('*'))
			->where("expense_type='".$this->_getData['expense_type']."'");  
	 }							
	//echo $select->__toString();die;
	$results = $this->getAdapter()->fetchAll($select);
	$string = '';
	if(!empty($results)){
		foreach($results as $heads){

		/***********************************************************************
		add place hoder for the input field for Expense Amount in HRM Expence 
		Setting form by jm on 18072018
	   	***********************************************************************/

	    $string .= '<tr class="odd"><td><strong>'.$heads['head_name'].'</strong><input type="hidden" name="head_id[]" value="'.$heads['head_id'].'"></td><td><input type="text" name="expense_amount[]" placeholder="Enter Amount" value="'.$heads['expense_amount'].'" class="input-short"></td></tr>';
	   }
	 }
	 echo $string;exit();
  }
  
  public function getExpenseTemplate(){
    if(!empty($this->_getData['user_id'])){
      $select = $this->_db->select()
		 				->from(array('EEA'=>'emp_expense_amount'),array('*'))
						->joinleft(array('EH'=>'expense_head'),"EH.head_id=EEA.head_id",array('head_name'))
						->where("user_id='".$this->_getData['user_id']."'");
	  $result = $this->getAdapter()->fetchAll($select);
	  }//print_r($this->_getData['user_id']);print_r($result);die;
	 if(empty($this->_getData['user_id']) || empty($result)){
	   $select = $this->_db->select()
		 				->from(array('ES'=>'expense_setting'),array('*'))
						->joininner(array('ETA'=>'expense_template_amount'),"ES.exp_setting_id=ETA.exp_setting_id",array('*'))
						->joininner(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))
						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 
								 AND designation_id ='".$this->_getData['designation_id']."'")
						->group("EH.head_id");
	  $result = $this->getAdapter()->fetchAll($select); 
	 } 
	 $string = '';
	 foreach($result as $expenses){
	    $string .= '<tr><td>'.$expenses['head_name'].'<input type="hidden" name="head_id[]" value="'.$expenses['head_id'].'"></td><td><input type="text" name="expense_amount[]" value="'.$expenses['expense_amount'].'" class="input-short"></td></tr>';
	 }
	 $string .= '<tr><td colspan="2"><table id="expenseauthority"><tr><th colspan="2">Expense Approval Authority</th></tr>';
	 $approval = $this->getNumberOfApprovalExpense();
	 $getapprovals = $this->getUsersExpenseApproval();
	 $total_app = ($approval>0)?$approval:3;
	 if(!empty($getapprovals)){
	    foreach($getapprovals as $getapproval){
		   $rest  = $total_app-1;
		   $parent = $this->expenseApproveAuthority($getapproval['parent_id'],$total_app,$rest,$getapproval['approval_user_id'],$getapproval['position']);
		   $string .= $parent[0];
		}   
	 }else{
	      $parent = $this->expenseApproveAuthority($this->_data['parent_id'],$total_app,$total_app-1,0,0);
	 	  $string .= $parent[0];
	 }
	 $string .= '</table>';
	 
	 $string .= '<table><th colspan="2">Extra Expense Head</th>';
	$string .= '<tr><td><select name="head_id[]" id="head_id" onchange="getExpenseAmount(this.value);" class="input-medium">';
	$string .=  '<option value="">--Select Site--</option>';
	$allexpensehead = $this->getAllExpenseHead();
    foreach($allexpensehead as $i=>$expenses){
		$string .='<option value="'.$expenses['head_id'].'">'.$expenses['head_name'].'</option>';
	 }
	 $string .='</select></td><td><input name="expense_amount[]" id="expense_amount" class="input-medium"></td></tr></table></td></tr>';
	 
	echo $string;exit;   
  }
  
  /*public function expenseApproveAuthority($parent_id,$i=1){
     $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('*'))
						->joininner(array('DES'=>'designation'),"DES.designation_id=UD.designation_id",array('designation_name','designation_code'))						->where("user_id='".$parent_id."'");
	$result = $this->getAdapter()->fetchRow($select);
	$parnetlist = $this->getParnetByDesignationId();
   	if(!empty($result)){
     $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="'.$parent_id.'"></td><td>'.$result['first_name'].' '.$result['last_name'].'-'.$result['designation_code'].'</td></tr>';
	}elseif(!empty($parnetlist)){
	     $string = '<tr><td>Approval Authority '.$i.'</td><td><select name=expese_approve_auth[]>';
			 foreach($parnetlist as $parnet){
				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 
			 }
   		 $string .= '</select></td></tr>';
	}else{
	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="44"></td><td> Super Admin</td></tr>';
	}
	return array($string,$result['parent_id']);
  }*/
  
  public function expenseApproveAuthority($parent_id,$total,$rest,$parent,$position){
    $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('*'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())
						->where("(DT1.designation_level>=DT.designation_id OR DT1.designation_id=DT.designation_id OR DT.designation_id=34) AND ED.delete_status='0'");
						//echo $select->__toString();die;
	   $result = $this->getParnetByDesignationId();//$this->getAdapter()->fetchAll($select);
   	if(!empty($result)){
	     $string = '<tr><td><select name=expese_approve_auth[]>';
		 $string .='<option value="">Select Approval Authority</option>';
			 foreach($result as $parnet){
			     $selected='';
			     if($parent==$parnet['user_id']){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 
			 }
   		  $string .= '</select></td>';
		  
		 $string .= '<td><select name=position[] onchange="getExpenseAuthority(this.value,'.$this->_getData['designation_id'].','.$total.','.$rest.')">';
		 $string .='<option value="">Select Approval Position</option>';
		 //$iposition = ($this->_data['currentvalue']>0)?$this->_data['currentvalue']:1; 
			 for($i=1;$i<$total;$i++){
			    $selected='';
			     if($i==$position){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$i.'" '.$selected.'>Approval Authority'.$i.'</option>'; 
			 }
		 $selected='';
		  if($total==$position){
		   $selected = 'selected="selected"'; 
		 }
		 $string .='<option value="'.$total.'" '.$selected.'>Final Approval Authority</option>';	 
   		 $string .= '</select></td></tr>';
	}else{
	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="44"></td><td>Supper Admin</td></tr>';
	}
	return array($string,$result['parent_id']);
  }
  
  public function getUserExpenseAmount(){
       $select = $this->_db->select()
	  			->from(array('EEA'=>'emp_expense_amount'),array('*'))
				->joininner(array('PD'=>'employee_personaldetail'),"PD.user_id=EEA.user_id",array('expense_type AS emp_etype'))
				->joininner(array('EH'=>'expense_head'),"EH.head_id=EEA.head_id",array('expense_type AS orig_etype'))
				->where("EEA.user_id='".$_SESSION['AdminLoginID']."' AND EEA.head_id='".$this->_getData['head_id']."'");
				//echo $select->__toString();die;
	   $amount =  $this->getAdapter()->fetchRow($select);
	   $exxpensetype = 'F';
	   if($amount['orig_etype']==$amount['emp_etype'] && $amount['emp_etype']==3){
	     $exxpensetype = 'T';
	   }
	   if($amount['head_id']==15){
	      $exxpensetype = 'T';
	   }
	   echo $amount['expense_amount'].'^'.$exxpensetype;exit();
  }
  
  public function leaaveApproveAuthority($parent_id,$total,$rest,$parent,$position){
     /*$select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('*'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())
						->where("DT1.designation_level>=DT.designation_id");
						//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);*/
	   $result = $this->getParnetByDesignationId();
   	if(!empty($result)){
	     $string = '<tr><td><select name=leave_approve_auth[]>';
		 $string .='<option value="">Select Approval Authority</option>';
			 foreach($result as $parnet){
			     $selected='';
			     if($parent==$parnet['user_id']){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 
			 }
   		  $string .= '</select></td>';
		  
		 $string .= '<td><select name=authority_position[] onchange="getAuthority(this.value,'.$this->_getData['designation_id'].','.$total.','.$rest.')">';
		 $string .='<option value="">Select Approval Position</option>';
		 //$iposition = ($this->_data['currentvalue']>0)?$this->_data['currentvalue']:1; 
			 for($i=1;$i<$total;$i++){
			    $selected='';
			     if($i==$position){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$i.'" '.$selected.'>Approval Authority'.$i.'</option>'; 
			 }
		 $selected='';
		  if($total==$position){
		   $selected = 'selected="selected"'; 
		 }
		 $string .='<option value="'.$total.'" '.$selected.'>Final Approval Authority</option>';	 
   		 $string .= '</select></td></tr>';
	}else{
	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="leave_approve_auth[]" value="44"></td><td>Supper Admin</td></tr>';
	}
	return array($string,$result['parent_id']);
  }
  public function getNumberOfApprovalLeave(){
     $select = $this->_db->select()
		 				->from(array('LA'=>'leaveapprovals'),array('*'))
						->where("designation_id	='".$this->_getData['designation_id']."'");
	 $result = $this->getAdapter()->fetchRow($select);
	 return $result['approval_no'];
  }
  public function getNumberOfApprovalExpense(){
    $select = $this->_db->select()
		 				->from(array('ES'=>'expense_setting'),array('*'))
						->where("designation_id ='".$this->_getData['designation_id']."'");
	  $result = $this->getAdapter()->fetchRow($select); 
	 return $result['number_of_approval'];
  }
 
 public function getUsersleaveApproval(){
  	$select = $this->_db->select()
		 				->from(array('ELA'=>'emp_leave_approval'),array('*','approval_user_id as parent_id'))
						->where("user_id='".$this->_getData['user_id']."'");
	  $result = $this->getAdapter()->fetchAll($select); 
	 return $result;
  }
  
 public function getUsersExpenseApproval(){
 	$select = $this->_db->select()
		 				->from(array('ELA'=>'expense_approval'),array('*','approval_user_id as parent_id'))
						->where("user_id='".$this->_getData['user_id']."'");
	  $result = $this->getAdapter()->fetchAll($select); 
	 return $result;
 }
 public function updateEmpExpense(){
   $this->_db->update('emp_expenses',array('head_id'=>$this->_getData['head_id'],'expense_amount'=>$this->_getData['expense_amount'],'expense_detail'=>$this->_getData['expense_detail'],'expense_date'=>$this->_getData['expense_date']),"expense_id='".$this->_getData['expense_id']."'");
   return true;
   $_SESSION[SUCCESS_MSG]= 'Expense Updated Successfully';
 }
  public function getRecordrecordOfLowerEmp(){
     if($this->_getData['Level']==1){
	 $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array(''))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array(''))
						->joinleft(array('DT1'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name as Value','designation_id AS Key'))
						->where("DT1.designation_level>=DT.designation_level  AND ED.user_id='".$_SESSION['AdminLoginID']."'");
						
	  }
	 $results = $this->getAdapter()->fetchAll($select); 
	  $string = '<option value="">---Select--</option>';
		 if($results){
			 foreach($results as $Record){
				$selected = '';
				if($this->_data['Key']==$Record['Key']){
				  $selected = 'selected="selected"';
				}
				$string .='<option value="'.$Record['Key'].'" '.$selected.'>'.$Record['Value'].'</option>'; 
			 }
	     }
		 return $string;
     }
    public function deletesalaryhead(){
        $this->_db->delete("salary_list","salaryhead_id='".$this->_getData['salaryhead_id']."' AND user_id='".$this->_getData['user_id']."' AND date='".$this->_getData['date']."'");
        return 'Salary Head has been deleted successfully';exit;
    }
	public function getPercentage($salaryhead_id){
	  $select = $this->_db->select()

		 				->from('salary_head',array('*'))

						->where("salaryhead_id='".$salaryhead_id."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result['ctc_percentage'];
	}
	public function getRegionsheadquater(){
       $select = $this->_db->select()
		 				->from(array('HQ'=>'headquater'),array('*'))
						->where("HQ.region_id='".$this->_getData['region_id']."'");
						//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select); 
	   return $result;
	 }  
}

?>