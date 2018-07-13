<?php

class Crm_AjaxController extends Zend_Controller_Action {

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

	/*	Newly inserted Code
		
		This function will return all the patchs from Database
	*/
	
	public function getallpatchlistAction()

	{
		$patchList = $this->ObjModelAjax->getPatchList(array("hq"=>Class_Encryption::decode($this->_data['head_id'])));
		
		 $string = '<option value="">---Select--</option>';

		 if($patchList){

			 foreach($patchList as $patch){

				$selected = '';

				if(Class_Encryption::decode($this->_data['selected'])==$patch['patch_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.Class_Encryption::encode($patch['patch_id']).'" '.$selected.'>'.$patch['patch_name'].'</option>'; 

			 }

	     }
		echo $string;

		exit;

	}

	//End of Newly inserted Code
	

	public function getlocationAction(){

		$response = "";

		if($this->_data['token']>0) {

			$response = $this->ObjModelAjax->getlocationinfo();

		}

		print_r($response);

		exit;

	}

	

	public function getchemistAction(){

		$response = "";

		if($this->_data['token']>0) {

			$chemists = $this->ObjModelAjax->getChemistsFromStreet();

			if(count($chemists) > 0) {

				foreach($chemists as $chemist) {

					$response .= '<input type="checkbox" name="chemists[]" value="'.$chemist['chemist_id'].'"> '.$chemist['chemist_name'].' &nbsp;&nbsp';

				}

			}

			else {

				$response = "";

			}

		}

		print_r($response);

		exit;

	}

	

	public function getdoctorAction(){

		$data = $this->_request->getParams();

		$response = "";

		if($data['token']>0) {

			$response = $this->ObjModelAjax->getAppointmentDetail();

		}

		print_r($response); exit;

	}

	

	/**

	 * Get Product Price

	 */

	public function getvalueAction(){

		$response = "";

		if($this->_data['token']>0) {

			$response = $this->ObjModelAjax->getProductPrice();

		}

		print_r($response);

		exit;

	}

	

	/**

	 * Method getpatchAction() get all patch data which are active and delete status is false.

	 * @access	public

	 * @param	hold hq ID

	 * @return	array

	 */

	public function getpatchAction()

	{

		if(!empty($this->_data['token'])) {

			$hqID = Class_Encryption::decode($this->_data['token']);

			$patches = $this->ObjModelAjax->getPatchList(array('hq'=>$hqID));

			$data[''] = 'Select Patch';

			foreach($patches as $patch){

				$data[Class_Encryption::encode($patch['patch_id'])] = $patch['patch_name'];

			}

			$finalData = array($data);

			header('Content-Type: application/x-json; charset=utf-8');

			print_r(json_encode($finalData));exit;

		}

	}

	

	/**

	 * Method patchAction() get location data from given patch.

	 * @access	public

	 * @param	hold patch ID

	 * @return	array

	 */

	public function patchAction()

	{

		if(!empty($this->_data['token'])) {

			$patchID = Class_Encryption::decode($this->_data['token']);

			$info = $this->ObjModelAjax->getpatchlocation(array('patchID'=>$patchID));

			header('Content-Type: application/x-json; charset=utf-8');

			print_r(json_encode($info));exit;

		}

	}

	

	/**

	 * Previously Defined Functions

	 **/

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

				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 

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

				//$string .='<option value="'.$data['area_id'].'" '.$selected.'>'.$data['area_code'].'-'.$data['area_name'].'</option>'; 

				$string .='<option value="'.$data['area_id'].'" '.$selected.'>'.$data['area_name'].'</option>'; 

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

		    $datas = $this->ObjModelSetting->getAjaxData('patchcodes','headquater_id');

		    $string = '<option value="">---Select Patch--</option>';

		 if($datas){

			 foreach($datas as $data){

				$selected = '';

				if($this->_data['selected']==$data['patch_id']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$data['patch_id'].'" '.$selected.'>'.$data['patchcode'].'-'.$data['patch_name'].'</option>'; 

			 }

	     }

		 break; 

	  }

	  echo $string;

	  exit; 

   }

    public function salarytemplateAction(){

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

				  $class = ($key%2==0)?'even':'odd';

			      $string .= '<tr class="'.$class.'"><td>'.$this->ObjModelAjax->getSalaryHeadName($additionalhead).'</td><td><input type="text" name="amount[1]['.$additionalhead.']" id="amount'.$template['salaryhead_id'].'" value="'.$this->ObjModelAjax->getAmountByTemplateId($templates['salary_template_id'],$additionalhead).'" class="input-medium"></td></tr>'; 

			  }

		 }

		 if(!empty($detectionheads)){

		   $string .= '<tr><td colspan="2"><b>Deduction</b></td></tr>';

		      foreach($detectionheads as $detectionhead){

				   $class = ($key%2==0)?'even':'odd';

			      $string .= '<tr class="'.$class.'"><td>'.$this->ObjModelAjax->getSalaryHeadName($detectionhead).'</td><td><input type="text" name="amount[2]['.$detectionhead.']" id="amount'.$template['salaryhead_id'].'" value="'.$this->ObjModelAjax->getAmountByTemplateId($templates['salary_template_id'],$detectionhead).'" class="input-medium"></td></tr>'; 

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

      	  if($this->_data['currentvalue']<$approval && $this->_data['rest']>0){

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
 
   public function getchildselecteduserAction(){
     $this->ObjModelAjax->_getData['user_id'] = Class_Encryption::decode($this->_data['user_id']);
	 $this->ObjModelAjax->_getData['design_id'] = $this->_data['design_id'];
   	 echo $this->ObjModelAjax->getChildselectedUser(); exit;
	 
  }

}

?>

