<?php
class Crm_AppointmentController extends Zend_Controller_Action {
	var $session = "";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init()
	{
		if(!isset($_SESSION['AdminLoginID']) && $this->_request->getActionName()!='print'){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 18;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new AppointmentManager();
		$this->ObjAjax = new AjaxManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		if($this->_request->getActionName()!='print'){
			$this->ObjModel->checkPrivileged();
		}
	}
	
    public function indexAction()
	{
		 $data = $this->_request->getParams();
		 $this->view->Filterdata = $data;
		 if(isset($data['Export'])){
		    $this->ObjModel->ExportAll($data);
		 }
		 $this->view->doctors = $this->ObjAjax->getDoctorLists();
		 $this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		 $this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		 $this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		 $this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		 $this->view->appointments = $this->ObjModel->getAppointments($data);
	}
	
	public function addAction()
	{
		$formData = $this->_request->getParams();
		$this->view->doctors = $this->ObjAjax->getDoctorLists();
		$this->view->expenses = $this->ObjModel->getExpenseLists();
		$this->view->products = $this->ObjAjax->getProductLists();
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->tableWidth = 800 + (70*19);
		
		if($this->_request->isPost() && $formData['appointmentADD'] == 'Add CRM'){ //echo "<pre>";print_r($this->_request->getParams());echo "</pre>";die;
			$appointData['doctor_id'] 		= (isset($formData['doctor_id'])) ? trim($formData['doctor_id']) : 0;
			$doctorInfo 					= $this->ObjAjax->getdoctorinfo(array('doctorID'=>$appointData['doctor_id']));
			$upHierarchyDetail  			= $this->ObjAjax->getBeAbmRsmDetail(array('headquarterID'=>$doctorInfo['headquater_id']));
			$doctorChemistDetail			= $this->ObjAjax->getdoctorChemistDetail(array('doctorID'=>$appointData['doctor_id']));
			$appointData['appointment_code']= (isset($formData['appointment_code'])) ? trim($formData['appointment_code']) : $this->ObjModel->makeAppointmentCode();
			$appointData['expense_type'] 	= (isset($formData['expense_type'])) ? trim($formData['expense_type']) : 0;
			$appointData['expense_cost'] 	= (isset($formData['expense_cost'])) ? trim($formData['expense_cost']) : '0.00';
			$appointData['expense_note'] 	= (isset($formData['expense_note'])) ? trim($formData['expense_note']) : '';			
			$appointData['be_id'] 			= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['Token'])) ? $upHierarchyDetail['BE']['Token'] : 0;
			$appointData['abm_id'] 			= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['Token'])) ? $upHierarchyDetail['ABM']['Token'] : 0;
			$appointData['rbm_id'] 			= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['Token'])) ? $upHierarchyDetail['RBM']['Token'] : 0;
			$appointData['zbm_id'] 			= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['Token'])) ? $upHierarchyDetail['ZBM']['Token'] : 0;
			$appointData['total_value'] 	= (isset($formData['tot_val_crm'])) ? trim($formData['tot_val_crm']) : 0;
			$appointData['chemist_1'] 		= (isset($doctorChemistDetail[0]['chemist_id'])) ? trim($doctorChemistDetail[0]['chemist_id']) : 0;
			$appointData['chemist_2'] 		= (isset($doctorChemistDetail[1]['chemist_id'])) ? trim($doctorChemistDetail[1]['chemist_id']) : 0;
			$appointData['favour'] 			= (isset($formData['favour'])) ? trim($formData['favour']) : '';
			$appointData['payble'] 			= (isset($formData['payble'])) ? trim($formData['payble']) : '';
			$appointData['abm_comment'] 	= (isset($formData['abm_comment'])) ? trim($formData['abm_comment']) : '';
			$appointData['rbm_comment'] 	= (isset($formData['rbm_comment'])) ? trim($formData['rbm_comment']) : '';
			$appointData['zbm_comment'] 	= (isset($formData['zbm_comment'])) ? trim($formData['zbm_comment']) : '';
			$appointData['business_audit'] 	= (isset($formData['business_audit'])) ? trim($formData['business_audit']) : '0';
			$appointData['remarks'] 		= (isset($formData['remarks'])) ? trim($formData['remarks']) : '';			
		   	$appointData['created_by'] 		= $_SESSION['AdminLoginID'];
		   	$appointData['created_ip'] 		= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($appointData);echo "</pre>";die;
		   	if(empty($appointData['doctor_id']) OR $appointData['doctor_id']==0){
					
					$_SESSION[ERROR_MSG] = "Please Select a <b>doctor</b> to add CRM!";
					$this->_redirect($this->_request->getControllerName().'/add/');
			}elseif(empty($appointData['expense_type']) OR $appointData['expense_type']==0){
					
					$_SESSION[ERROR_MSG] = "Please Select a <b>Nature of Request</b> to add CRM!";
					$this->_redirect($this->_request->getControllerName().'/add/');
			}elseif(empty($appointData['expense_cost']) OR $appointData['expense_cost']==0){
					
					$_SESSION[ERROR_MSG] = "Please Fill <b>COST of Activity</b> to add CRM!";
					$this->_redirect($this->_request->getControllerName().'/add/');
			}elseif(empty($appointData['expense_note']) OR $appointData['expense_note']==''){
					
					$_SESSION[ERROR_MSG] = "Please Fill $appointData[expense_note]<b>Detail of Activity Planned</b> to add CRM!";
					$this->_redirect($this->_request->getControllerName().'/add/');
			}
		   	$appointmentID = $this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointments','tableData'=>$appointData));
		   
		   	if($appointmentID > 0) {
		   		$potentialData['appointment_id'] = $appointmentID;
				foreach($formData['month'] as $key=>$month) {
					$potentialData['month'] 			= $month;
					$potentialData['month_total_value']	= (isset($formData['tot_val_'.$month])) ? trim($formData['tot_val_'.$month]) : '0';
					$potentialMonthID = $this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potential_months','tableData'=>$potentialData));
					
					if($potentialMonthID > 0) {
						$productUnitData['potential_month_id'] = $potentialMonthID;
						foreach($formData['unit_'.$month] as $index=>$unit) {
							if($unit > 0) {
								$productUnitData['product_id'] = (isset($formData['token'][$index])) ? Class_Encryption::decode(trim($formData['token'][$index])) : 0;
								$productUnitData['unit'] 	   = $unit;
								$productUnitData['value']      = (isset($formData['value_'.$month][$index])) ? trim($formData['value_'.$month][$index]) : 0;
								$this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potential_month_products','tableData'=>$productUnitData));	
							}
						}
					}					
				}				
		   	}
		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}	   	
	}
	
	public function drpotentialAction()
	{
		$this->_helper->layout->setLayout('popupmain');
		$data = $this->_request->getParams();
		$this->view->showproduct = @explode(',',$data['token']); //print_r(explode(',',$data['token']));die;
		$this->view->products = $this->ObjAjax->getProductLists();
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->tableWidth = 800 + (70*19);
	}
	
	public function getdoctorAction()
	{
		$response = "";
		if($this->_data['token']>0) {
			$response = $this->ObjAjax->getAppointmentDetail();
		}
		print_r($response);
		exit;
	}
	
	public function editoldAction()
	{
		$data = $this->_request->getParams();
		$appointID = Class_Encryption::decode($data['token']);
	   	$this->view->doctors = $this->ObjAjax->getDoctorLists();
		$this->view->expenses = $this->ObjModel->getExpenseLists();
		$this->view->products = $this->ObjAjax->getProductLists();
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->appoints = $this->ObjModel->getAppointmentByID(array('appoinToken' => $appointID));//echo "<pre>";print_r($this->view->appoints);die;
		
		// Add New Routing Details
		if($this->_request->isPost() && $data['appointmentUPD'] == 'Update'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			$updateColumn = array(); $transactionColumn = array();
			if($_SESSION['AdminDesignation'] == 7 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['abm_id']) {
				$updateColumn['abm_comment_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['abm_comment_by'] 	= $_SESSION['AdminLoginID'];
				$updateColumn['abm_comment_ip'] 	= $_SERVER['REMOTE_ADDR'];
				$updateColumn['abm_comment'] 		= (isset($data['abm_comment'])) ? trim($data['abm_comment']) : '';
			}
			else if(($_SESSION['AdminDesignation'] == 5 || $_SESSION['AdminDesignation'] == 6) && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['rsm_id']) {
				$updateColumn['rbm_comment_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['rbm_comment_by'] 	= $_SESSION['AdminLoginID'];
				$updateColumn['rbm_comment_ip'] 	= $_SERVER['REMOTE_ADDR'];
				$updateColumn['rbm_comment'] 		= (isset($data['rbm_comment'])) ? trim($data['rbm_comment']) : '';
			}
			else if($_SESSION['AdminDesignation'] == 5 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['zbm_id']) {
				$updateColumn['zbm_comment_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['zbm_comment_by'] 	= $_SESSION['AdminLoginID'];
				$updateColumn['zbm_comment_ip'] 	= $_SERVER['REMOTE_ADDR'];
				$updateColumn['zbm_comment'] 		= (isset($data['zbm_comment'])) ? trim($data['zbm_comment']) : '';
			}			
			else if($_SESSION['AdminLoginID'] == 1) {
				$updateColumn['remarks'] 			= (isset($data['remarks'])) ? trim($data['remarks']) : '';
				$transactionColumn['appointment_id']= $appointID;
				$transactionColumn['amount']		= (isset($data['amount'])) ? trim($data['amount']) : '';
				$transactionColumn['mode']			= (isset($data['mode'])) ? trim($data['mode']) : '';
				$transactionColumn['dd_chq_no']		= (isset($data['dd_chq_no'])) ? trim($data['dd_chq_no']) : '';
				$transactionColumn['disburse_date']	= (isset($data['disburse_date'])) ? trim($data['disburse_date']) : '';
				$transactionColumn['added_by']		= $_SESSION['AdminLoginID'];
				$transactionColumn['added_ip']		= $_SERVER['REMOTE_ADDR'];
			}
			
			if(count($updateColumn) > 0) {
				if($this->ObjModel->updateAppointmentData(array('tableName'=>'crm_appoinment_details','tableData'=>$updateColumn,'whereColumn'=>'appointment_id='.$appointID))){
					if(count($transactionColumn)>0) {
						$this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_transactions','tableData'=>$transactionColumn));
					}
					$_SESSION[SUCCESS_MSG] = "Appointment detail has been updated successfully!!";
					$this->_redirect($this->_request->getControllerName());
				}
				else {
					$_SESSION[ERROR_MSG] = "Appointment detail has not been updated successfully, due to some problem!!";
					$this->_redirect($this->_request->getControllerName().'/'.$this->_request->getActionName());
				}
			}
			
		   	/*if($addAppintMain > 0) {
		   		$appointmentID['appointment_id'] = $addAppintMain;
				
				$filterFormData['appointDetail'] = array_merge($filterFormData['appointDetail'],$appointmentID);
				$this->ObjModel->addAppointmentData(array('tableName'=>'crm_appoinment_details','tableData'=>$filterFormData['appointDetail']));
				
				foreach($filterFormData['appointPotentials']['months'] as $key=>$formPotential) {
					$potentialData['appointment_id'] = $appointmentID['appointment_id'];
					$potentialData['month'] = $formPotential;
					$potentialData['product_id']=(isset($filterFormData['appointPotentials']['products'][$key])) ? trim($filterFormData['appointPotentials']['products'][$key]) : 0;
					$potentialData['unit'] 	 = (isset($filterFormData['appointPotentials']['units'][$key])) ? trim($filterFormData['appointPotentials']['units'][$key]) : 0;
					$potentialData['value']  = (isset($filterFormData['appointPotentials']['values'][$key])) ? trim($filterFormData['appointPotentials']['values'][$key]) : 0;
					$this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potentials','tableData'=>$potentialData));
				}				
		   	}
		     
		   	$this->_redirect($this->_request->getControllerName().'/index');*/
	   	}
	}
	
	public function editAction()
	{
		$data = $this->_request->getParams();
		$appointID = Class_Encryption::decode($data['token']);
	   	$this->view->products = $this->ObjAjax->getProductLists();
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->appoints = $this->ObjModel->getAppointmentByID(array('appoinToken' => $appointID));
		$dataTemp=$this->ObjModel->getAllHiearchy($this->view->appoints['appointData']['requested_by'],$this->view->appoints['appointData']['drHQ']);
		$this->view->appoints['appointData']['beCode']  =$dataTemp['beCode'];
		$this->view->appoints['appointData']['beName']	=$dataTemp['beName'];
		$this->view->appoints['appointData']['be_id']	=$dataTemp['be_id'];
		$this->view->appoints['appointData']['beHQ']	=$dataTemp['beHQ'];
		$this->view->appoints['appointData']['beHQName']=$dataTemp['beHQName'];
		$this->view->appoints['appointData']['abmCode']	=$dataTemp['abmCode'];
		$this->view->appoints['appointData']['abmName']	=$dataTemp['abmName'];
		$this->view->appoints['appointData']['abm_id']	=$dataTemp['abm_id'];
		$this->view->appoints['appointData']['abmHQ']	=$dataTemp['abmHQ'];
		$this->view->appoints['appointData']['abmHQName']=$dataTemp['abmHQName'];
		$this->view->appoints['appointData']['rsmCode']	=$dataTemp['rsmCode'];
		$this->view->appoints['appointData']['rsmName']	=$dataTemp['rsmName'];
		$this->view->appoints['appointData']['rbm_id']	=$dataTemp['rbm_id'];
		$this->view->appoints['appointData']['rsmHQ']	=$dataTemp['rsmHQ'];
		$this->view->appoints['appointData']['rsmHQName']=$dataTemp['rsmHQName'];
		$this->view->userhierarchy = $this->ObjAjax->userHierarchy(array('headquarterID'=>$this->view->appoints['appointData']['drHQ']));
		$this->view->tableWidth = 800 + (70*19); //echo "<pre>";print_r($this->view->appoints);echo "</pre>";die;
		
		// Update CRM Information by Up level of BE with BE Data
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['approvalsame'])) == 'APPROVED_BE_DATA'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			
			$potentialData['appointment_id'] = Class_Encryption::decode($data['token']);
			$potentialData['added_by'] 		 = $_SESSION['AdminLoginID'];
		   	$potentialData['added_ip'] 		 = $_SERVER['REMOTE_ADDR'];
		   
			foreach($data['same_month'] as $key=>$month) {
				$potentialData['month'] 			= $month;
				$potentialData['month_total_value']	= (isset($data['same_tot_val_'.$month])) ? trim($data['same_tot_val_'.$month]) : '0';
				$potentialMonthID = $this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potential_months_by_admin','tableData'=>$potentialData));
				
				if($potentialMonthID > 0) {
					$productUnitData['potential_month_id'] = $potentialMonthID;
					foreach($data['same_unit_'.$month] as $index=>$unit) {
						if($unit > 0) {
							$productUnitData['product_id'] = (isset($data['same_token1'][$index])) ? Class_Encryption::decode(trim($data['same_token1'][$index])) : 0;
							$productUnitData['unit'] 	   = $unit;
							$productUnitData['value']      = (isset($data['same_value_'.$month][$index])) ? trim($data['same_value_'.$month][$index]) : 0;
							$this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potential_month_products_by_admin','tableData'=>$productUnitData));	
							$updateAppoint = true;
						}
					}
				}					
			}
			
			if($updateAppoint) {
				$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
			else {
				$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
				$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
			}
	   	}
		
		// Update CRM Information by Up level of BE with own data
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['approval'])) == 'UPDATE_AND_APPROVED'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			
			$potentialData['appointment_id'] = Class_Encryption::decode($data['token']);
			$potentialData['added_by'] 		 = $_SESSION['AdminLoginID'];
		   	$potentialData['added_ip'] 		 = $_SERVER['REMOTE_ADDR'];
		   
			foreach($data['month'] as $key=>$month) {
				$potentialData['month'] 			= $month;
				$potentialData['month_total_value']	= (isset($data['tot_val_'.$month])) ? trim($data['tot_val_'.$month]) : '0';
				$potentialMonthID = $this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potential_months_by_admin','tableData'=>$potentialData));
				
				if($potentialMonthID > 0) {
					$productUnitData['potential_month_id'] = $potentialMonthID;
					foreach($data['unit_'.$month] as $index=>$unit) {
						if($unit > 0) {
							$productUnitData['product_id'] = (isset($data['token1'][$index])) ? Class_Encryption::decode(trim($data['token1'][$index])) : 0;
							$productUnitData['unit'] 	   = $unit;
							$productUnitData['value']      = (isset($data['value_'.$month][$index])) ? trim($data['value_'.$month][$index]) : 0;
							$this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_potential_month_products_by_admin','tableData'=>$productUnitData));	
							$updateAppoint = true;
						}
					}
				}					
			}
			
			if($updateAppoint) {
				$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
			else {
				$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
				$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
			}
	   	}
		
		// Update CRM status by ABM
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['abmApproval'])) == 'UPDATED_BY_ABM'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			$updateColumn = array();
			//if($_SESSION['AdminDesignation'] == 7 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['abm_id']) {
				$updateColumn['abm_approval'] 		= (isset($data['abm_approval'])) ? trim($data['abm_approval']) : '0';
				$updateColumn['abm_comment'] 		= (isset($data['abm_comment'])) ? trim($data['abm_comment']) : '';
				$updateColumn['abm_comment_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['abm_comment_by'] 	= $_SESSION['AdminLoginID'];
				$updateColumn['abm_comment_ip'] 	= $_SERVER['REMOTE_ADDR'];				
			//}
			
			if(count($updateColumn) > 0) {
				$updateAppoint = $this->ObjModel->updateAppointmentData(array('tableName'=>'crm_appointments','tableData'=>$updateColumn,'whereColumn'=>'appointment_id='.$appointID));
				if($updateAppoint) {
					$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
				else {
					$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
					$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
				}
			}
	   	}
		
		// Update CRM status by RBM
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['rbmApproval'])) == 'UPDATED_BY_RBM'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			$updateColumn = array();
			//if($_SESSION['AdminDesignation'] == 6 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['rbm_id']) {
				$updateColumn['rbm_approval'] 		= (isset($data['rbm_approval'])) ? trim($data['rbm_approval']) : '0';
				$updateColumn['rbm_comment'] 		= (isset($data['rbm_comment'])) ? trim($data['rbm_comment']) : '';
				$updateColumn['rbm_comment_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['rbm_comment_by'] 	= $_SESSION['AdminLoginID'];
				$updateColumn['rbm_comment_ip'] 	= $_SERVER['REMOTE_ADDR'];				
			//}
			
			if(count($updateColumn) > 0) {
				$updateAppoint = $this->ObjModel->updateAppointmentData(array('tableName'=>'crm_appointments','tableData'=>$updateColumn,'whereColumn'=>'appointment_id='.$appointID));
				if($updateAppoint) {
					$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
				else {
					$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
					$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
				}
			}
	   	}
		
		// Update CRM status by ZBM
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['zbmApproval'])) == 'UPDATED_BY_ZBM'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			$updateColumn = array();
			//if($_SESSION['AdminDesignation'] == 5 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['zbm_id']) {
				$updateColumn['zbm_approval'] 		= (isset($data['zbm_approval'])) ? trim($data['zbm_approval']) : '0';
				$updateColumn['zbm_comment'] 		= (isset($data['zbm_comment'])) ? trim($data['zbm_comment']) : '';
				$updateColumn['zbm_comment_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['zbm_comment_by'] 	= $_SESSION['AdminLoginID'];
				$updateColumn['zbm_comment_ip'] 	= $_SERVER['REMOTE_ADDR'];				
			//}
			
			if(count($updateColumn) > 0) {
				$updateAppoint = $this->ObjModel->updateAppointmentData(array('tableName'=>'crm_appointments','tableData'=>$updateColumn,'whereColumn'=>'appointment_id='.$appointID));
				if($updateAppoint) {
					$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
				else {
					$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
					$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
				}
			}
	   	}
		//Update CRM status by GM
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['GMApproval'])) == 'UPDATED_BY_GM'){ 
			//echo "<pre>";print_r($data);echo "</pre>";die;
			$updateColumn = array();
			//if($_SESSION['AdminDesignation'] == 5 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['zbm_id']) {
				$updateColumn['gm_audit_status'] 	= (isset($data['gm_audit_status'])) ? trim($data['gm_audit_status']) : '0';
				$updateColumn['gm_audit_comment'] 	= (isset($data['gm_audit_comment'])) ? trim($data['gm_audit_comment']) : '';
				$updateColumn['gm_audit_date'] 		= date("Y-m-d h:i:s");
				$updateColumn['gm_audit_by'] 		= $_SESSION['AdminLoginID'];
				$updateColumn['gm_audit_ip'] 		= $_SERVER['REMOTE_ADDR'];				
			//}
			
			if(count($updateColumn) > 0) {
				$updateAppoint = $this->ObjModel->updateAppointmentData(array('tableName'=>'crm_appointments','tableData'=>$updateColumn,'whereColumn'=>'appointment_id='.$appointID));
				if($updateAppoint) {
					$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
				else {
					$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
					 //$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
				}
			}
	   	}
		// Update CRM status by HO/Admin
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['hoApproval'])) == 'UPDATED_BY_HO'){ //echo "<pre>";print_r($data);echo "</pre>";die;
			$updateColumn = array();
			//if($_SESSION['AdminDesignation'] == 5 && $_SESSION['AdminLoginID'] == $this->view->appoints['appointData']['zbm_id']) {
				$updateColumn['business_audit_status'] 	= (isset($data['business_audit_status'])) ? trim($data['business_audit_status']) : '0';
				$updateColumn['business_audit_comment'] = (isset($data['business_audit_comment'])) ? trim($data['business_audit_comment']) : '';
				$updateColumn['business_audit_date'] 	= date("Y-m-d h:i:s");
				$updateColumn['business_audit_by'] 		= $_SESSION['AdminLoginID'];
				$updateColumn['business_audit_ip'] 		= $_SERVER['REMOTE_ADDR'];				
			//}
			
			if(count($updateColumn) > 0) {
				$updateAppoint = $this->ObjModel->updateAppointmentData(array('tableName'=>'crm_appointments','tableData'=>$updateColumn,'whereColumn'=>'appointment_id='.$appointID));
				if($updateAppoint) {
					$_SESSION[SUCCESS_MSG] = "CRM status has been updated successfully!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
				else {
					$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
					$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
				}
			}
	   	}
		
		// Update CRM status by HO/Admin
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['admTrans'])) == 'AMOUNT_APPROVED'){
			$transactionColumn['remarks'] 		= (isset($data['remarks'])) ? trim($data['remarks']) : '';
			$transactionColumn['appointment_id']= $appointID;
			$transactionColumn['amount']		= (isset($data['amount'])) ? trim($data['amount']) : '';
			$transactionColumn['mode']			= (isset($data['mode'])) ? trim($data['mode']) : '';
			$transactionColumn['dd_chq_no']		= (isset($data['dd_chq_no'])) ? trim($data['dd_chq_no']) : '';
			$transactionColumn['disburse_date']	= (isset($data['disburse_date'])) ? trim($data['disburse_date']) : '';
			$transactionColumn['added_by']		= $_SESSION['AdminLoginID'];
			$transactionColumn['added_ip']		= $_SERVER['REMOTE_ADDR'];
			$addTransactionData = $this->ObjModel->addAppointmentData(array('tableName'=>'crm_appointment_transactions','tableData'=>$transactionColumn));
			
			if($addTransactionData) {
				$_SESSION[SUCCESS_MSG] = "CRM amount trasaction has been successfully!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
			else {
				$_SESSION[ERROR_MSG] = "Some problem occur during CRM updation!!";
				$this->_redirect($this->_request->getControllerName().'/index/token/'.$data['token']);
			}
		}
	}
	
	public function viewAction()
	{
		$data = $this->_request->getParams();
		$appointID = Class_Encryption::decode($data['token']);
	   	$this->view->products = $this->ObjAjax->getProductLists();
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->appoints = $this->ObjModel->getAppointmentByID(array('appoinToken' => $appointID));
		$dataTemp=$this->ObjModel->getAllHiearchy($this->view->appoints['appointData']['requested_by'],$this->view->appoints['appointData']['drHQ']);
		$this->view->appoints['appointData']['beCode']  =$dataTemp['beCode'];
		$this->view->appoints['appointData']['beName']	=$dataTemp['beName'];
		$this->view->appoints['appointData']['be_id']	=$dataTemp['be_id'];
		$this->view->appoints['appointData']['beHQ']	=$dataTemp['beHQ'];
		$this->view->appoints['appointData']['beHQName']=$dataTemp['beHQName'];
		$this->view->appoints['appointData']['abmCode']	=$dataTemp['abmCode'];
		$this->view->appoints['appointData']['abmName']	=$dataTemp['abmName'];
		$this->view->appoints['appointData']['abm_id']	=$dataTemp['abm_id'];
		$this->view->appoints['appointData']['abmHQ']	=$dataTemp['abmHQ'];
		$this->view->appoints['appointData']['abmHQName']=$dataTemp['abmHQName'];
		$this->view->appoints['appointData']['rsmCode']	=$dataTemp['rsmCode'];
		$this->view->appoints['appointData']['rsmName']	=$dataTemp['rsmName'];
		$this->view->appoints['appointData']['rbm_id']	=$dataTemp['rbm_id'];
		$this->view->appoints['appointData']['rsmHQ']	=$dataTemp['rsmHQ'];
		$this->view->appoints['appointData']['rsmHQName']=$dataTemp['rsmHQName'];
		$this->view->userhierarchy = $this->ObjAjax->userHierarchy(array('headquarterID'=>$this->view->appoints['appointData']['drHQ']));
		$this->view->tableWidth = 800 + (70*19);
	}
	
	public function printAction()
	{
		$this->_helper->layout->setLayout('popupmain');
		$data = $this->_request->getParams();
		$appointID = Class_Encryption::decode($data['token']);
		if($data['app']==1){
		  $appointID =  $data['token'];
		}
	   	$this->view->products = $this->ObjAjax->getProductLists();
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->appoints = $this->ObjModel->getAppointmentByID(array('appoinToken' => $appointID));
		$dataTemp=$this->ObjModel->getAllHiearchy($this->view->appoints['appointData']['requested_by'],$this->view->appoints['appointData']['drHQ']);
		$this->view->appoints['appointData']['beCode']  =$dataTemp['beCode'];
		$this->view->appoints['appointData']['beName']	=$dataTemp['beName'];
		$this->view->appoints['appointData']['be_id']	=$dataTemp['be_id'];
		$this->view->appoints['appointData']['beHQ']	=$dataTemp['beHQ'];
		$this->view->appoints['appointData']['beHQName']=$dataTemp['beHQName'];
		$this->view->appoints['appointData']['abmCode']	=$dataTemp['abmCode'];
		$this->view->appoints['appointData']['abmName']	=$dataTemp['abmName'];
		$this->view->appoints['appointData']['abm_id']	=$dataTemp['abm_id'];
		$this->view->appoints['appointData']['abmHQ']	=$dataTemp['abmHQ'];
		$this->view->appoints['appointData']['abmHQName']=$dataTemp['abmHQName'];
		$this->view->appoints['appointData']['rsmCode']	=$dataTemp['rsmCode'];
		$this->view->appoints['appointData']['rsmName']	=$dataTemp['rsmName'];
		$this->view->appoints['appointData']['rbm_id']	=$dataTemp['rbm_id'];
		$this->view->appoints['appointData']['rsmHQ']	=$dataTemp['rsmHQ'];
		$this->view->appoints['appointData']['rsmHQName']=$dataTemp['rsmHQName'];
		$this->view->userhierarchy = $this->ObjAjax->userHierarchy(array('headquarterID'=>$this->view->appoints['appointData']['drHQ']));
		
		// Export Doctor Visit Data in Excel Sheet
		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){
			$this->ObjModel->ExportCrm($this->view->appoints,$this->view->products);
			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}
		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_PDF'){
			try {
				//$this->_helper->layout()->disableLayout();
				//$this->_helper->viewRenderer->setNoRender(true);
				$html = $this->view->render('appointment/test.php');
		 	 	$this->mpdf = new Zend_MPDF_mpdf('utf-8', 'A4');
		 	 	$this->mpdf->SetDisplayMode('fullpage');
				$this->mpdf->watermark_font = 'DejaVuSansCondensed';
		 		$this->mpdf->showWatermarkText = true;
				$this->mpdf->cacheTables 	   = true;
				$this->mpdf->simpleTables	   = true;
				$this->mpdf->packTableData	   = true;
				$mpdf->debug = true;
				$this->mpdf->WriteHTML($html);
		 		ob_end_clean();
		 		$this->mpdf->Output($file_name, 'D');
			}catch(Zend_Mpdf_MpdfException $e) { 
				// Note: safer fully qualified exception 
	            // name used for catch
	    		// Process the exception, log, print etc.
	    		echo $e->getMessage();
			}
			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}
	}
	
	public function getfilterFormData($data=array())
	{
		$formData = (isset($data['formData']) && count($data['formData'])>0) ? $data['formData'] : array();
		 
		$appointData = array();
		$appointData['appointment_code'] 	= (isset($formData['appointment_code'])) ? trim($formData['appointment_code']) : $this->ObjModel->makeAppointmentCode();
		$appointData['doctor_id'] 			= (isset($formData['doctor_id'])) ? trim($formData['doctor_id']) : 0;
		$appointData['expense_type'] 		= (isset($formData['expense_type'])) ? trim($formData['expense_type']) : 0;
		$appointData['expense_cost'] 		= (isset($formData['expense_cost'])) ? trim($formData['expense_cost']) : '0.00';
		$appointData['expense_note'] 		= (isset($formData['expense_note'])) ? trim($formData['expense_note']) : '';
		
		$appointDetail = array();
		$doctorInfo 						= $this->ObjAjax->getdoctorinfo(array('doctorID'=>$appointData['doctor_id']));
		$BeAbmRsmDetail  					= $this->ObjAjax->getBeAbmRsmDetail(array('streetID'=>$doctorInfo['street_id']));
		$appointDetail['be_id'] 			= (isset($formData['be'])) ? trim($formData['be']) : $BeAbmRsmDetail['beId'];
		$appointDetail['abm_id'] 			= (isset($formData['abm'])) ? trim($formData['abm']) : $BeAbmRsmDetail['abmId'];
		$appointDetail['rsm_id'] 			= (isset($formData['rsm'])) ? trim($formData['rsm']) : $BeAbmRsmDetail['rsmId'];
		$appointDetail['zbm_id'] 			= 0;
		$appointDetail['total_value'] 		= (isset($formData['tot_val'])) ? trim($formData['tot_val']) : 0;
		$appointDetail['favour'] 			= (isset($formData['favour'])) ? trim($formData['favour']) : '';
		$appointDetail['payble'] 			= (isset($formData['payble'])) ? trim($formData['payble']) : '';
		$appointDetail['abm_comment'] 		= (isset($formData['abm_comment'])) ? trim($formData['abm_comment']) : '';
		$appointDetail['rbm_comment'] 		= (isset($formData['rbm_comment'])) ? trim($formData['rbm_comment']) : '';
		$appointDetail['zbm_comment'] 		= (isset($formData['zbm_comment'])) ? trim($formData['zbm_comment']) : '';
		$appointDetail['business_audit'] 	= (isset($formData['business_audit'])) ? trim($formData['business_audit']) : '0';
		$appointDetail['remarks'] 			= (isset($formData['remarks'])) ? trim($formData['remarks']) : '';
		 
		$appointPotentials = array();
		$appointPotentials['months']  	= (isset($formData['month'])) ? $formData['month'] : array();
		$appointPotentials['products']  = (isset($formData['product'])) ? $formData['product'] : array();
		$appointPotentials['units']  	= (isset($formData['unit'])) ? $formData['unit'] : array();
		$appointPotentials['values']  	= (isset($formData['value'])) ? $formData['value'] : array();
		 
		return array('appointData'=>$appointData,'appointDetail'=>$appointDetail,'appointPotentials'=>$appointPotentials);
	}
	
	public function manualcrmAction(){
	
	  $this->ObjModel->manualCRM();
	  die('Done');
	}
	
	public function editproductAction(){
		$data = $this->_request->getParams();
		if($this->_request->isPost()){
	      $this->ObjModel->UpdateCrmproduct($data);
	   }
	    //$this->view->crmlist = $this->ObjModel->getProducts($data);
		$this->view->products = $this->ObjModel->productList();
	} 
}
?>