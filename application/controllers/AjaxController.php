<?php

class AjaxController extends Zend_Controller_Action {

	var $session="";

	public $users;

	public $ObjModel = NULL;

	public $_data = NULL;



	public function init(){

		$this->session = Bootstrap::$registry->get('defaultNs');

		$this->_helper->layout->setLayout('main');

		$this->_data = $this->_request->getParams();

		$this->ObjModelUser = new Users();

		$this->ObjModelSetting = new SettingManager();

		$this->ObjModelAjax = new AjaxManager();

		$this->ObjModelUser->_getData = $this->_data;

		$this->ObjModelSetting->_getData = $this->_data;

		$this->ObjModelAjax->_getData = $this->_data;

		$this->view->ObjModel = $this->ObjModel;

	}

   public function changestatusAction(){

	  if($this->_data['Mode']=='Bunit'){

	     $departmentlist = $this->ObjModelUser->getDepartmentByBunitId();

		 $string = '<option value="">---Select--</option>';

		 if($departmentlist){

			 foreach($departmentlist as $department){

				$selected = '';

				if($this->_data['selected']==$department['department_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$department['department_id'].'" '.$selected.'>'.$department['department_name'].'</option>'; 

			 }

	     }

		 echo  $string;

		 exit;

	   }

	   if($this->_data['Mode']=='Department'){

	     $designationlist = $this->ObjModelUser->getDesignationByDepartmentId();

		 $string = '<option value="">---Select--</option>';

		 if($designationlist){

			 foreach($designationlist as $designation){

				$selected = '';

				if($this->_data['selected']==$designation['designation_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$designation['designation_id'].'" '.$selected.'>'.$designation['designation_name'].'</option>'; 

			 }

	     }

		 echo  $string;

		 exit;  

	   }

	    if($this->_data['Mode']=='Designation'){

	     $parnetlist = $this->ObjModelAjax->getParnetByDesignationId();

		 $string = '<option value="">---Select--</option>';
		 if($this->_data['selected']==0){

				  $selected = 'selected="selected"';

		 }
		 $string .= '<option value="44" '.$selected.'>Super Admin</option>';
			$selected = '';
		 if($parnetlist){

			 foreach($parnetlist as $parnet){

			    $selected = '';

				if($this->_data['selected']==$parnet['user_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].' ( '.$parnet['designation_code'].' ) - '.$parnet['employee_code'].'</option>'; 

			 }

	     }

		 echo  $string;

		 exit;  

	   }	 
	   
	   if($this->_data['Mode']=='Designation_Child'){
	     $designationlist = $this->ObjModelAjax->getDesignationChild();
		 $string = '<option value="">---Select--</option>';
		 if($designationlist){
			 foreach($designationlist as $designation){
				$selected = '';
				if($this->_data['selected']==$designation['designation_id']){
				  $selected = 'selected="selected"';
				}
				$string .='<option value="'.$designation['designation_id'].'" '.$selected.'>'.$designation['designation_name'].'</option>'; 
			 }
	     }
		 echo  $string;
		 exit;  
	   } 

	}

	

   public function changestatusrecordAction(){

      $string = '';

      switch($this->_data['level']){

	     case 5:

		    $datas = $this->ObjModelSetting->getAjaxData('zone','bunit_id'); 

		    $string = '<option value="">---Select Zone--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['zone_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['zone_id'].'" '.$selected.'>'.$data['zone_name'].'</option>'; 

			 }

	     }

		 break;

		 case 6:

		    $datas = $this->ObjModelSetting->getAjaxData('region','zone_id'); 

		    $string = '<option value="">---Select Region--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['region_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['region_id'].'" '.$selected.'>'.$data['region_name'].'</option>'; 

			 }

	     }

		 break;

		case 7:

		    $datas = $this->ObjModelSetting->getAjaxData('area','region_id'); 

		    $string = '<option value="">---Select Area--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['area_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['area_id'].'" '.$selected.'>'.$data['area_code'].'-'.$data['area_name'].'</option>'; 

			 }

	     }

		 break;
		case 8:

		    $datas = $this->ObjModelSetting->getAjaxData('headquater','area_id'); 

		    $string = '<option value="">---Select HeadQuater--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['headquater_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['headquater_id'].'" '.$selected.'>'.$data['headquater_name'].'</option>'; 

			 }

	     }

		 break;
		case 9:

		    $datas = $this->ObjModelSetting->getAjaxData('city','headquater_id'); 

		    $string = '<option value="">---Select City--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['city_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['city_id'].'" '.$selected.'>'.$data['city_name'].'</option>'; 

			 }

	     }

		 break;

	   case 10:

		    $datas = $this->ObjModelSetting->getAjaxData('street','city_id'); 

		    $string = '<option value="">---Select--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['street_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['street_id'].'" '.$selected.'>'.$data['street_name'].'</option>'; 

			 }

	     }

		 break; 
		 case 11:

		    $datas = $this->ObjModelAjax->getRegionsheadquater(); 

		    $string = '<option value="">---Select HeadQuater--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['headquater_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['headquater_id'].'" '.$selected.'>'.$data['headquater_name'].'</option>'; 

			 }

	     }

		 break;

	  }

	  echo $string;

	  exit; 

   }

    public function salarytemplateAction(){
	  //print_r($this->_data);die;
	  $credittype = $this->_data['credit_type'];
	  $ctc = $this->_data['ctc'];
	  $templates = $this->ObjModelAjax->getSalaryEmployee();

	  $string = '';

	 if(!empty($this->_data['user_id']) && !empty($templates)){

	   $ear = 0;

	   $ded = 0;

	    foreach($templates as $key=>$template){
			$class = ($key%2==0)?'even':'odd';
		  if($template['salaryheade_type']==1){

		   if($ear==0){

		    $string .= '<tr class="odd"><td colspan="2"><b>Earnings</b></td></tr>';

			}  

			 $string .= '<tr class="'.$class.'"><td>'.$this->ObjModelAjax->getSalaryHeadName($template['salaryhead_id']).'</td><td><input type="text" name="amount[1]['.$template['salaryhead_id'].']" id="amount'.$template['salaryhead_id'].'" value="'.$template['amount'].'" class="input-medium"></td></tr>'; 

		  $ear++;

		  }

		  if($template['salaryheade_type']==2){

		   if($ded==0){

		    $string .= '<tr class="'.$class.'"><td colspan="2"><b>Deduction</b></td></tr>'; 

			} 

			 $string .= '<tr><td>'.$this->ObjModelAjax->getSalaryHeadName($template['salaryhead_id']).'</td><td><input type="text" name="amount[2]['.$template['salaryhead_id'].']" id="amount'.$template['salaryhead_id'].'" value="'.$template['amount'].'" class="input-medium"></td></tr>'; 

		  $ded++;

		  }

		}

	 }

	  elseif(empty($this->_data['user_id']) || empty($templates)){  

	   $templates = $this->ObjModelAjax->getSalaryTemplateAmount();
   // print_r($templates);die;
	  if(!empty($templates)){

	     $additionalheads = explode(',',$templates['salaryhead_id']);

		 $detectionheads = explode(',',$templates['detsalaryhead_id']); 

		

		 if(!empty($additionalheads)){

		 	$string .= '<tr class="odd"><td colspan="2"><b>Earnings</b></td></tr>';

		      foreach($additionalheads as $key=>$additionalhead){
			      $ctcpercentage = $this->ObjModelAjax->getPercentage($additionalhead);
			      if($credittype==1 && $ctc>0 && $ctcpercentage>0){
					  $amount = ($ctc * $ctcpercentage)/100; 
					}else{
					  $amount = $this->ObjModelAjax->getAmountByTemplateId($templates['salary_template_id'],$additionalhead);
					}
				  $class = ($key%2==0)?'even':'odd';
			      $string .= '<tr class="'.$class.'"><td>'.$this->ObjModelAjax->getSalaryHeadName($additionalhead).'</td><td><input type="text" name="amount[1]['.$additionalhead.']" id="amount'.$template['salaryhead_id'].'" value="'.$amount.'" class="input-medium"></td></tr>'; 

			  }

		 }

		 if(!empty($detectionheads)){

		   $string .= '<tr><td colspan="2"><b>Deduction</b></td></tr>';

		      foreach($detectionheads as $detectionhead){
			  
			     $ctcpercentage = $this->ObjModelAjax->getPercentage($detectionhead);
			      if($credittype==1 && $ctc>0 && $ctcpercentage>0){
					  $amount = ($ctc * $ctcpercentage)/100; 
					}else{
					  $amount = $this->ObjModelAjax->getAmountByTemplateId($templates['salary_template_id'],$detectionhead);
					}
				   $class = ($key%2==0)?'even':'odd';
			      $string .= '<tr class="'.$class.'"><td>'.$this->ObjModelAjax->getSalaryHeadName($detectionhead).'</td><td><input type="text" name="amount[2]['.$detectionhead.']" id="amount'.$template['salaryhead_id'].'" value="'.$amount.'" class="input-medium"></td></tr>'; 

			  }

		 }

	  }else{

	     $string .= '<tr><td colspan="2" align="center">There is no Salary Head</td></tr>';

	  }

	 } 

	  echo $string;exit; 

   }

  public function leavedetailAction(){

     $leaves = $this->ObjModelAjax->getLeaveDetail();

	 $string = '';

	  foreach($leaves as $key=>$leave){
		   $class = ($key%2==0)?'even':'odd';
		   $string .='<tr  class="'.$class.'"><td>'.$leave['typeName'].'</td>';

		   $string .='<td><input type="text" name="no_of_leave[]" value="'.$leave['leave_no'].'" class="input-short"></td></tr>';

		   $string .='<input type="hidden" name="leave_id[]" value="'.$leave['leave_type_id'].'">';

	   }
	 $string .= '<tr><td colspan="2"><table id="leaveauthority"><tr><th colspan="2">Leave Approval Authority</th></tr>';  
	 $approval = $this->ObjModelAjax->getNumberOfApprovalLeave();
	 $getapprovals = $this->ObjModelAjax->getUsersleaveApproval();
	 $total_app = ($approval>0)?$approval:3;
	 if(!empty($getapprovals)){
	    foreach($getapprovals as $getapproval){
		   $rest  = $total_app-1;
		   $parent = $this->ObjModelAjax->leaaveApproveAuthority($getapproval['parent_id'],$total_app,$rest,$getapproval['approval_user_id'],$getapproval['position']);
		   $string .= $parent[0];
		}   
	 }else{
	      $parent = $this->ObjModelAjax->leaaveApproveAuthority($this->_data['parent_id'],$total_app,$total_app-1,0,0);
	 	  $string .= $parent[0];
	 }
	 $string .= '</table></td></tr>';
	 echo $string;exit; 
   }

   

   public function calcinteretstAction(){

      $noi = ($this->_data['noi']>0)?$this->_data['noi']:1;

	  $roi = ($this->_data['roi']>0)?($this->_data['roi']/100):0;

	  $year = $noi/12;

      if($this->_data['interest_type']==1){

		   $amount1 = $this->_data['amount']*(1 + $roi/$year);

		   $amount = $this->_data['amount']+(($amount1) * ($year));

	  }else{

	       $amount = $this->_data['amount']+($this->_data['amount'] * $roi * $year); 

	  }

	  echo $amount;exit;

   }

  public function documentsAction(){

    $string ='';

	$string .= '<tr><td>Documents Type<select name="document_type[]"><option value="">--Select Category--</option>';

	$doctypes = $this->ObjModelAjax->getDocumentsType();

	    foreach($doctypes as $doc){ 

		   $string .= '<option value="'.$doc['type_id'].'">'.$doc['type_name'].'</option>';

	        }

	$string .= '</select>';

	$string .= '</td><td><input type="file" name="documnet[]" /></td></tr>';

	echo $string;exit;

   }

  public function activeinactiveAction(){

     $this->ObjModelAjax->ChangeStatusValue();

  }
  
  public function userbydesignationAction(){
     $this->ObjModelAjax->getUserListByDesignation();
  }
  public function extraheadAction(){
      $this->ObjModelAjax->geteXtraHead();
  }
  public function expenseheadAction(){
     $this->ObjModelAjax->getExpenseHead();
  }
  public function expensetemplateAction(){
     $this->ObjModelAjax->getExpenseTemplate(); 
  }
  public function getuserexpenseAction(){
     $this->ObjModelAjax->getUserExpenseAmount();
  }
  public function getnextauthorityAction(){
      	  $string = '';
		  $approval = $this->ObjModelAjax->getNumberOfApprovalLeave();
      	  if($this->_data['currentvalue']<=$approval && $this->_data['rest']>0){
		      $rest = $this->_data['rest']-1;
			  $parent = $this->ObjModelAjax->leaaveApproveAuthority($this->_data['parent_id'],$this->_data['total'],$rest,0,0);
			  $string .= $parent[0];
		  }
	  echo $string;exit;	  
  }
 public function nextexpenseauthAction(){
   $string = '';
		  $approval = $this->ObjModelAjax->getNumberOfApprovalExpense();
      	  if($this->_data['currentvalue']<$approval && $this->_data['rest']>0){
		      $rest = $this->_data['rest']-1;
			  $parent = $this->ObjModelAjax->expenseApproveAuthority($this->_data['parent_id'],$this->_data['total'],$rest,0,0);
			  $string .= $parent[0];
		  }
	  echo $string;exit;
 } 
 public function updateexpenceAction(){
   echo $this->ObjModelAjax->updateEmpExpense();exit;
 }
 public function messagelocationAction(){
   echo $this->ObjModelAjax->getRecordrecordOfLowerEmp();exit;
 }
 public function deleteheadAction(){
    // print_r($this->_data);die;
    echo $this->ObjModelAjax->deletesalaryhead();exit;
 }
 
 public function testAction(){ 
    $this->ObjModelAjax->_getData['designation_id'] = 2;
    $this->ObjModelAjax->getParnetByDesignationId_old();
 }
}
?>
