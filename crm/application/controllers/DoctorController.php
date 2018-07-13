<?php

class DoctorController extends Zend_Controller_Action {

	var $session="";

	public $users;

	public $ObjModel = NULL;

	public $_data = NULL;



	public function init(){

		if(!isset($_SESSION['AdminLoginID'])){

	      $this->_redirect(Bootstrap::$baseUrl);

	   }

		$this->session = Bootstrap::$registry->get('defaultNs');

		Bootstrap::$_parent = 18;

		Bootstrap::$_level = 2;

		Bootstrap::$ActionName  = $this->_request->getActionName();

		$this->_helper->layout->setLayout('main');

		$this->_data = $this->_request->getParams();

		$this->ObjModel = new DoctorManager();

		$this->ObjAjax  = new AjaxManager();

		$this->ObjModel->_getData = $this->_data;

		$this->view->ObjModel = $this->ObjModel;

		

		// Get and check access of module privilege

		// If condition is temporary to see all patch code

		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();

		/*if($this->_request->getActionName() != 'patchcode' || $this->_request->getActionName() != 'patchaction') {

			Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();

			$this->ObjModel->checkPrivileged();

		}*/

	}

	

	public function indexAction(){

		$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;

		 

		// Delete Doctor Detail in Bunch

		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['listaction'])) == 'DELETE'){

		 	if(count($data['dtoken'])>0) {

				if($this->ObjModel->doctordelete($data)) {

					$_SESSION[SUCCESS_MSG] = "Selected doctor has been deleted successfully!!";

					$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

				}

			}

			else {

				$_SESSION[ERROR_MSG] = "Please select doctor to perform delete action!!";

				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

			}

		}

		 

		 // When Doctor file will be upload

		 if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['uplDr'])) == 'UPLOAD'){

		 	$this->ObjModel->UpdateDoctorFromExcel($data); //First time upload doctor file

			//$this->ObjModel->UploadDoctorExcel($data); //Before Upload Headquarter should have to selected and check if doctor already found then return error file

			//$this->ObjModel->UploadDoctorExcelUpdate($data); //Before Upload Headquarter should have to selected and check if doctor already found then update, if not then add

			

			$_SESSION[SUCCESS_MSG] = "Doctor excel file imported and doctor data added successfully!!";

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }

		 

		 // Export All Headquarter Header File with till City Data

		 if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['expAllHq'])) == 'DOWNLOAD_ALL_HEADQUARTER_HEADER'){

		 	$this->ObjModel->ExportAllHqHeader($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }

		 

		 // Export Doctor Data

		 if(!empty($data['exportDoctor']) && strtoupper(str_replace(' ','_',$data['exportDoctor'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportDoctor($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }

		 

		 $this->view->Filterdata = $data;

		 $this->view->doctors = $this->ObjAjax->getDoctorLists();

		 $this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		 $this->view->activities = $this->ObjAjax->getActivityLists();

		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		 $this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		 $this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		 $this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

		 

		 $this->view->doctorLists = $this->ObjModel->getDoctors($data);

	}

	

	public function addAction(){

		$data = $this->_request->getParams();

		// Add New Doctor Details

		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['doctorAdd'])) == 'ADD_NEW'){

			$formData = $this->_request->getParams();		   	

			$doctorData = array();



			$doctorData['doctor_name'] 	= (isset($formData['doctor_name'])) ? trim($formData['doctor_name']) : '';

			$doctorData['speciality']		= (isset($formData['speciality'])) 	? trim($formData['speciality']) : '';

			$doctorData['class'] 			= (isset($formData['class'])) 		? trim($formData['class']) : '';

			$doctorData['qualification'] 	= (isset($formData['qualification'])) ? trim($formData['qualification']) : '';

			$doctorData['visit_frequency']	= (isset($formData['visit_frequency'])) ? trim($formData['visit_frequency']) : '';

			$doctorData['gender'] 			= (isset($formData['gender'])) 		? trim($formData['gender']) : '';

			$doctorData['dob'] 			= (isset($formData['dob'])) 		? trim($formData['dob']) : '';

			$doctorData['meeting_day'] 	= (isset($formData['meeting_day'])) ? trim($formData['meeting_day']) : '';

			$doctorData['meeting_time'] 	= (isset($formData['meeting_time']))? trim($formData['meeting_time']) : '';

			$doctorData['email'] 			= (isset($formData['email'])) 		? trim($formData['email']) : '';

			$doctorData['phone'] 			= (isset($formData['phone'])) 		? trim($formData['phone']) : '';

			$doctorData['mobile'] 			= (isset($formData['mobile'])) 		? trim($formData['mobile']) : '';

			$doctorData['patch_id'] 		= (isset($formData['patchtoken'])) 	? Class_Encryption::decode($formData['patchtoken']) : 0;

			$doctorData['address1'] 		= (isset($formData['address1'])) 	? trim($formData['address1']) : '';

			$doctorData['address2'] 		= (isset($formData['address2'])) 	? trim($formData['address2']) : '';

			$doctorData['postcode'] 		= (isset($formData['postcode'])) 	? trim($formData['postcode']) : '';	

			

			$locationInfo = $this->ObjAjax->getlocation(array('streetID'=>$doctorData['patch_id']));	 

			$doctorData['city_id'] 	 = (isset($formData['city_id'])) 		? trim($formData['city_id']) : $locationInfo['city_id'];

			$doctorData['area_id'] 	 = (isset($formData['area_id'])) 		? trim($formData['area_id']) : $locationInfo['area_id'];

			$doctorData['zone_id'] 	 = (isset($formData['zone_id'])) 		? trim($formData['zone_id']) : $locationInfo['zone_id'];

			$doctorData['region_id'] 	 = (isset($formData['region_id'])) 		? trim($formData['region_id']) : $locationInfo['region_id'];

			$doctorData['headquater_id'] = (isset($formData['headquater_id'])) ? trim($formData['headquater_id']) : $locationInfo['headquater_id'];

			$doctorData['country_id'] = 27;

			$doctorData['business_unit_id'] = 1;

			

			$doctorData['doctor_code']  	= $this->ObjModel->makeDoctorCode();

			$doctorData['org_svl_number']  	= $this->ObjModel->makeDoctorSVL();

			$doctorData['svl_number']  		= $this->ObjModel->makeDoctorSVL(array('headquarterID'=>$doctorData['headquater_id']));

			

			$doctorData['created_by'] = $_SESSION['AdminLoginID'];

		   	$doctorData['created_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($doctorData);echo "</pre>";die;

			

			$doctorID = $this->ObjModel->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

		   	if($doctorID>0) {

		   		if(isset($formData['chemist']) && count($formData['chemist']) > 0) {

					foreach($formData['chemist'] as $chemist) {

						if(trim($chemist) != '') {

							$this->ObjModel->addDoctorData(array('tableName'=>'crm_doctor_chemists','tableData'=>array('doctor_id'=>$doctorID,'chemist_id'=>Class_Encryption::decode($chemist))));

						}

					}

				}			

		   	}

		   	$this->_redirect($this->_request->getControllerName().'/index');

	   	}

		

		$this->view->formdata = $data;

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists(); //echo "<pre>";print_r($this->view->info);die;

	}

	

	public function editAction(){

		$data = $this->_request->getParams();

		// Add New Doctor Details

		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['doctorAdd'])) == 'UPDATE'){

			$formData = $this->_request->getParams();		   	

			$doctorData = array();

			$doctorID = (isset($formData['token'])) ? Class_Encryption::decode($formData['token']) : 0;

			$doctorData['doctor_name'] 	= (isset($formData['doctor_name'])) ? trim($formData['doctor_name']) : '';

			$doctorData['speciality']		= (isset($formData['speciality'])) 	? trim($formData['speciality']) : '';

			$doctorData['class'] 			= (isset($formData['class'])) 		? trim($formData['class']) : '';

			$doctorData['qualification'] 	= (isset($formData['qualification'])) ? trim($formData['qualification']) : '';

			$doctorData['visit_frequency']	= (isset($formData['visit_frequency'])) ? trim($formData['visit_frequency']) : '';

			$doctorData['gender'] 			= (isset($formData['gender'])) 		? trim($formData['gender']) : '';

			$doctorData['dob'] 			= (isset($formData['dob'])) 		? trim($formData['dob']) : '';

			$doctorData['meeting_day'] 	= (isset($formData['meeting_day'])) ? trim($formData['meeting_day']) : '';

			$doctorData['meeting_time'] 	= (isset($formData['meeting_time']))? trim($formData['meeting_time']) : '';

			$doctorData['email'] 			= (isset($formData['email'])) 		? trim($formData['email']) : '';

			$doctorData['phone'] 			= (isset($formData['phone'])) 		? trim($formData['phone']) : '';

			$doctorData['mobile'] 			= (isset($formData['mobile'])) 		? trim($formData['mobile']) : '';

			$doctorData['patch_id'] 		= (isset($formData['patchtoken'])) 	? Class_Encryption::decode($formData['patchtoken']) : 0;

			$doctorData['address1'] 		= (isset($formData['address1'])) 	? trim($formData['address1']) : '';

			$doctorData['address2'] 		= (isset($formData['address2'])) 	? trim($formData['address2']) : '';

			$doctorData['postcode'] 		= (isset($formData['postcode'])) 	? trim($formData['postcode']) : '';	

			

			$locationInfo = $this->ObjAjax->getlocation(array('streetID'=>$doctorData['patch_id']));	 

			$doctorData['city_id'] 	 = (isset($formData['city_id'])) 		? trim($formData['city_id']) : $locationInfo['city_id'];

			$doctorData['area_id'] 	 = (isset($formData['area_id'])) 		? trim($formData['area_id']) : $locationInfo['area_id'];

			$doctorData['zone_id'] 	 = (isset($formData['zone_id'])) 		? trim($formData['zone_id']) : $locationInfo['zone_id'];

			$doctorData['region_id'] 	 = (isset($formData['region_id'])) 		? trim($formData['region_id']) : $locationInfo['region_id'];

			$doctorData['headquater_id']= (isset($formData['headquater_id'])) ? trim($formData['headquater_id']) : $locationInfo['headquater_id'];

			$doctorData['isActive']= (isset($formData['dastatus'])) ? $formData['dastatus'] : '0';

			$doctorData['isApproved']= (isset($formData['daappr'])) ? $formData['daappr'] : '0';

			

		   	$doctorData['isModify'] = '1';

			$doctorData['modify_by'] = $_SESSION['AdminLoginID'];

			$doctorData['modify_date'] = date('Y-m-d H:i:s');

		   	$doctorData['modify_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($doctorData);echo "</pre>";die;

			$addDoctorMain = $this->ObjModel->updateTableData(array('tableName'=>'crm_doctors','tableData'=>$doctorData,'whereColumn'=>"doctor_id=".$doctorID));

		   

		   	if($addDoctorMain) {

		   		if(isset($formData['chemist']) && count($formData['chemist']) > 0) {

					$this->ObjModel->deleteTableData(array('tableName'=>'crm_doctor_chemists','whereColumn'=>"doctor_id=".$doctorID));

					foreach($formData['chemist'] as $chemist) {

						if(trim($chemist) != '') {

							$this->ObjModel->addDoctorData(array('tableName'=>'crm_doctor_chemists','tableData'=>array('doctor_id'=>$doctorID,'chemist_id'=>Class_Encryption::decode($chemist))));

						}

					}

				}			

		   	}

		     

		   	$this->_redirect($this->_request->getControllerName().'/index');

	   	}

		

		$this->view->formdata = $data;

		$this->view->info = $this->ObjModel->doctorDetail($data); //echo "<pre>";print_r($this->view->info);die;

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

	}

	

	public function showchemistAction(){

		$data = $this->_request->getParams();

		$doctorInfo = $this->ObjModel->doctorchemist($data);

		$doctorChemist = array();

		if(count($doctorInfo)>0) {

			foreach($doctorInfo as $chemist) {

				$doctorChemist[] = $chemist['chemist_id'];

			}

		}

		

		$chemistInfo = $this->ObjModel->chemistDetail($data); //echo "<pre>";print_r($chemistInfo);die;

		$string = '';

		if(count($chemistInfo)>0) {

			foreach($chemistInfo as $key=>$chm) {

				$checked = (in_array($chm['chemist_id'],$doctorChemist)) ? 'checked="checked"' : '';

				$string .= '<input type="checkbox" name="chemist[]" id="chm" '.$checked.' value="'.Class_Encryption::encode($chm['chemist_id']).'">&nbsp;'.$chm['chemist_name'].'<br>';

			}

		}	

		

		echo $string;

		exit;

	}

	

	public function getfilterFormData($data=array()) {

		 $formData = (isset($data['formData']) && count($data['formData'])>0) ? $data['formData'] : array();

		 

		 $doctorData = array();

		 $doctorData['doctor_code']		= (isset($formData['doctor_code'])) ? trim($formData['doctor_code']) : $this->ObjModel->makeDoctorCode();

		 $doctorData['svl_number'] 		= (isset($formData['svl_number'])) 	? trim($formData['svl_number']) : '';

		 $doctorData['doctor_name'] 	= (isset($formData['doctor_name'])) ? trim($formData['doctor_name']) : '';

		 $doctorData['speciality']		= (isset($formData['speciality'])) 	? trim($formData['speciality']) : '';

		 $doctorData['class'] 			= (isset($formData['class'])) 		? trim($formData['class']) : '';

		 $doctorData['qualification'] 	= (isset($formData['qualification'])) ? trim($formData['qualification']) : '';

		 $doctorData['visit_frequency']	= (isset($formData['visit_frequency'])) ? trim($formData['visit_frequency']) : '';

		 $doctorData['gender'] 			= (isset($formData['gender'])) 		? trim($formData['gender']) : '';

		 $doctorData['dob'] 			= (isset($formData['dob'])) 		? trim($formData['dob']) : '';

		 $doctorData['meeting_day'] 	= (isset($formData['meeting_day'])) ? trim($formData['meeting_day']) : '';

		 $doctorData['meeting_time'] 	= (isset($formData['meeting_time']))? trim($formData['meeting_time']) : '';

		 $doctorData['email'] 			= (isset($formData['email'])) 		? trim($formData['email']) : '';

		 $doctorData['phone'] 			= (isset($formData['phone'])) 		? trim($formData['phone']) : '';

		 $doctorData['mobile'] 			= (isset($formData['mobile'])) 		? trim($formData['mobile']) : '';

		 $doctorData['street_id'] 		= (isset($formData['street_id'])) 	? trim($formData['street_id']) : 0;

		 $doctorData['address1'] 		= (isset($formData['address1'])) 	? trim($formData['address1']) : '';

		 $doctorData['address2'] 		= (isset($formData['address2'])) 	? trim($formData['address2']) : '';

		 $doctorData['postcode'] 		= (isset($formData['postcode'])) 	? trim($formData['postcode']) : '';	

		 

		 $locationInfo = $this->ObjAjax->getlocation(array('streetID'=>$doctorData['street_id']));	 

		 $doctorData['city_id'] 	 = (isset($formData['city_id'])) 		? trim($formData['city_id']) : $locationInfo['city_id'];

		 $doctorData['area_id'] 	 = (isset($formData['area_id'])) 		? trim($formData['area_id']) : $locationInfo['area_id'];

		 $doctorData['zone_id'] 	 = (isset($formData['zone_id'])) 		? trim($formData['zone_id']) : $locationInfo['zone_id'];

		 $doctorData['region_id'] 	 = (isset($formData['region_id'])) 		? trim($formData['region_id']) : $locationInfo['region_id'];

		 $doctorData['headquater_id']= (isset($formData['headquater_id'])) 	? trim($formData['headquater_id']) : $locationInfo['headquater_id'];

		 

		 $BeAbmRsmDetail = $this->ObjAjax->getBeAbmRsmDetail(array('street'=>$doctorData['street_id']));

		 $doctorData['am_headquater_id'] = (isset($formData['am_headquater_id']))? trim($formData['am_headquater_id']) : $BeAbmRsmDetail['abmHQ'];

		 

		 return array('doctorData'=>$doctorData);

	}

	

	public function patchcodeAction(){

	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;

		 

		// Get Patch sample csv file with header and location data

		if(!empty($data['expheader']) && strtoupper(str_replace(' ','_',$data['expheader'])) == 'EXPORT_HEADER'){

			$this->ObjModel->ExportPatchHeader($data);

		}

		

		// Upload Patch data file

		if(!empty($data['uploadpatch']) && strtoupper(str_replace(' ','_',$data['uploadpatch'])) == 'UPLOAD'){

		 	$this->ObjModel->UploadPatchFile($data); 

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}

		

		// Get Patch data with location data

		if(!empty($data['expdata']) && strtoupper(str_replace(' ','_',$data['expdata'])) == 'EXPORT_DATA'){

			$this->ObjModel->ExportPatchData($data);

		}

	   

	   	$this->view->postData = $data;

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

	   	$this->view->patch = $this->ObjModel->getPatchList($data);

		$this->view->loggedUserData = $this->ObjModel->getLoggedUserData($data);

	}

	

	public function patchaddAction(){

	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;

		 

		// Get Patch data with location data

		if(!empty($data['addPatch']) && strtoupper(str_replace(' ','_',$data['addPatch'])) == 'SAVE'){

			if($this->ObjModel->AddPatchData($data)) {

				$_SESSION[SUCCESS_MSG] = "Patch detail has aded successfully!!";

				$this->_redirect($this->_request->getControllerName().'/patchcode');

			}

			else {

				$_SESSION[ERROR_MSG] = "Some problem found on add patch detail, please try again!!";

				$this->_redirect($this->_request->getControllerName().'/patchadd');

			}

		}

	   

	   	$this->view->postData = $data;

	}

	

	public function patchactionAction(){

	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;

		 

		// Get Patch data with location data

		if(!empty($data['updatePatch']) && strtoupper(str_replace(' ','_',$data['updatePatch'])) == 'UPDATE'){

			if($this->ObjModel->UpdatePatchData($data)) {

				$_SESSION[SUCCESS_MSG] = "Patch detail has updated successfully!!";

				$this->_redirect($this->_request->getControllerName().'/'.$this->_request->getActionName().'/token/'.$data['token']);

			}

			else {

				$_SESSION[ERROR_MSG] = "Some problem found on update patch detail, please try again!!";

				$this->_redirect($this->_request->getControllerName().'/'.$this->_request->getActionName().'/token/'.$data['token']);

			}

		}

	   

	   	$this->view->postData = $data;

		//$this->view->patch = $this->ObjModel->getPatchDetail($data);

		$this->view->patch = $this->ObjModel->getPatch($data);

	}

}

?>