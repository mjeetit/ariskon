<?php

class ReportingController extends Zend_Controller_Action {

	var $session="";

	public $users;

	public $ObjModel = NULL;

	public $_data = NULL;

	

	public function init()

	{

	    if(!isset($_SESSION['AdminLoginID'])){

	      $this->_redirect(Bootstrap::$baseUrl);

	    }

		$this->session = Bootstrap::$registry->get('defaultNs');

		$this->_helper->layout->setLayout('main');

		$this->_data = $this->_request->getParams();

		$this->ObjModel = new ReportingManager();

		$this->ObjAjax  = new AjaxManager();

		$this->ObjModel->_getData = $this->_data;

		$this->view->ObjModel = $this->ObjModel;

		$this->view->filter = $this->_data;

	}

	

	public function doctorvisitAction()

	{

	   	$data = $this->_request->getParams();

		$this->view->Filterdata = $data;

		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->activities = $this->ObjAjax->getActivityLists();

		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   

	   	$this->view->vistdetail = $this->ObjModel->getDoctorVisit($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

		

		$this->view->tableHeader = array('ED.employee_code'=>'Employee Code','ED.first_name'=>'Employee Name','DT.designation_name'=>'Designation','HT.headquater_name'=>'Headquater','Call_Avg'=>'Call Average','CNT'=>'Total Visit','EE.call_date'=>'Month');

		

		// Export Doctor Visit Data in Excel Sheet

		 if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$filterData = $this->ObjModel->getDoctorVisit($data);

			$this->ObjModel->ExportDoctorSummaryVisit($this->view->tableHeader,$filterData);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }

	}

	

	public function doctordetailreportAction()

	{

	   	$data = $this->_request->getParams();

		$visitorID  = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;

		if($visitorID>0) {

			$this->view->Filterdata = $data;

			if($data['export']=='Export'){

			   $this->ObjModel->ExportDoctorvisit($data);

			}

			$this->view->reporteedetail = $this->ObjModel->getReportee($visitorID);

			$this->view->detailreport = $this->ObjModel->getDoctorVisitReport($data);

			$filterDoctorReport = $this->ObjModel->filterDoctorReport($data); //echo "<pre>";print_r($filterDoctorReport);die;

			$pulldown = array();

			if(count($filterDoctorReport)>0){

				foreach($filterDoctorReport as $key=>$report) {

					if(isset($report['zbm_visit']) && $report['zbm_visit']>0) {

						$pulldown['zbm'][$report['zbm_visit']] = $report['zbmvisit'];

					}

					if(isset($report['rbm_visit']) && $report['rbm_visit']>0) {

						$pulldown['rbm'][$report['rbm_visit']] = $report['rbmvisit'];

					}

					if(isset($report['abm_visit']) && $report['abm_visit']>0) {

						$pulldown['abm'][$report['abm_visit']] = $report['abmvisit'];

					}

					if(isset($report['be_visit']) && $report['be_visit']>0) {

						$pulldown['be'][$report['be_visit']] = $report['bevisit'];

					}

					if(isset($report['doctor_id']) && $report['doctor_id']>0) {

						$pulldown['doctor'][$report['doctor_id']] = $report['doctor_name'];

					}

					if(isset($report['patch_id']) && $report['patch_id']>0) {

						$pulldown['patch'][$report['patch_id']] = $report['patch_name'];

					}

					if(isset($report['product1']) && $report['product1']>0) {

						$pulldown['product'][$report['product1']] = $report['pname1'];

					}

					if(isset($report['product2']) && $report['product2']>0) {

						$pulldown['product'][$report['product2']] = $report['pname2'];

					}

					if(isset($report['product3']) && $report['product3']>0) {

						$pulldown['product'][$report['product3']] = $report['pname3'];

					}

					if(isset($report['product4']) && $report['product4']>0) {

						$pulldown['product'][$report['product4']] = $report['pname4'];

					}

					if(isset($report['product5']) && $report['product5']>0) {

						$pulldown['product'][$report['product5']] = $report['pname5'];

					}

					if(isset($report['activities']) && $report['activities']>0) {

						$pulldown['activity'][$report['activities']] = $report['activity_name'];

					}

				}

			}

			@asort($pulldown['zbm']);

			@asort($pulldown['rbm']);

			@asort($pulldown['abm']);

			@asort($pulldown['be']);

			@asort($pulldown['doctor']);

			@asort($pulldown['patch']);

			@asort($pulldown['product']);

			@asort($pulldown['activity']); //echo "<pre>";print_r($pulldown);die;

									

			$this->view->beDetails 	= (isset($pulldown['be'])) ? $pulldown['be'] : array(); //$this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

			$this->view->abmDetails = (isset($pulldown['abm'])) ? $pulldown['abm'] : array(); //$this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

			$this->view->rbmDetails = (isset($pulldown['rbm'])) ? $pulldown['rbm'] : array(); //$this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

			$this->view->zbmDetails = (isset($pulldown['zbm'])) ? $pulldown['zbm'] : array(); //$this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

			$this->view->doctors 	= (isset($pulldown['doctor'])) ? $pulldown['doctor'] : array(); //$this->ObjAjax->getDoctorLists();

			$this->view->patches 	= (isset($pulldown['patch'])) ? $pulldown['patch'] : array(); //$this->ObjAjax->getPatchLists();

			//$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

			$this->view->products 	= (isset($pulldown['product'])) ? $pulldown['product'] : array(); //$this->ObjAjax->getProductLists();

			$this->view->activities	= (isset($pulldown['activity'])) ? $pulldown['activity'] : array(); //echo "<pre>";print_r($this->view->products);die;

		}

		else {

			$_SESSION[SUCCESS_MSG] = "Wrong parameter!!";

			$this->_redirect($this->getRequest()->getControllerName().'/doctorvisit');

		}		

	}

	
	public function chemistvisitAction()
	{
		$data = $this->_request->getParams();
		$this->view->Filterdata = $data;
		$this->view->doctors = $this->ObjAjax->getChemistLists();
		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
		$this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		$this->view->vistdetail = $this->ObjModel->getChemistVisist($data);

		/*********************************************************************************
	     function name modify on the basis of main menu either HRM, CRM or Reporting 
	     by jm on 16072018
	   	*********************************************************************************/
	   	//$this->view->allusers = $this->ObjModel->getAllUsersForSalary();
	   	$this->view->allusers = $this->ObjModel->getAllUsersForSalaryReporting();

		$this->view->headquater = $this->ObjModel->getHeadquater();
	}

	

	public function chemistdetailreportAction()

	{

	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);die;

		$visitorID  = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;

		

		if($visitorID>0) {

			$this->view->reporteedetail = $this->ObjModel->getReportee($visitorID);

			$this->view->detailreport = $this->ObjModel->getChemistVisitReport($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

		}

		else {

			$_SESSION[SUCCESS_MSG] = "Wrong parameter!!";

			$this->_redirect($this->getRequest()->getControllerName().'/chemistvisit');

		}

	}

	

	public function stockistvisitAction(){

		$data = $this->_request->getParams();
	
		$this->view->Filterdata = $data;
		$this->view->doctors = $this->ObjAjax->getStockistLists();
		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
		$this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		$this->view->vistdetail = $this->ObjModel->getStockistVisist($data);

		/*********************************************************************************
		function name modify on the basis of main menu either HRM, CRM or Reporting 
		by jm on 16072018
		*********************************************************************************/
		//$this->view->allusers = $this->ObjModel->getAllUsersForSalary();
		$this->view->allusers = $this->ObjModel->getAllUsersForSalaryReporting();
		$this->view->headquater = $this->ObjModel->getHeadquater();
	}

	

	public function stockistdetailreportAction()
	{
	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);die;
		$visitorID  = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;

		if($visitorID>0) {
			$this->view->reporteedetail = $this->ObjModel->getReportee($visitorID);
			$this->view->detailreport = $this->ObjModel->getStockistVisitReport($data); //echo "<pre>";print_r($this->view->zbmDetails);die;
		}else {
			$_SESSION[SUCCESS_MSG] = "Wrong parameter!!";
			$this->_redirect($this->getRequest()->getControllerName().'/chemistvisit');
		}
	}

	

	public function repordetailAction()
	{
	  	if($this->_data['Mode']=='Doctor'){
		   $this->view->vistdetail = $this->ObjModel->getDoctorVisitDetail($this->_data);
	  	}elseif($this->_data['Mode']=='Chemist'){
			$this->view->vistdetail = $this->ObjModel->getChemistVisitDetail($this->_data);
		} 
		$this->view->patchlists = $this->ObjModel->getPatchlist($this->_data['user_id']);
    }

	

	public function giftAction()
	{

	  $this->view->tableDatas = $this->ObjModel->getTableData(array('tableName'=>'app_gifts','tableColumn'=>array(),'columnName'=>'isActive','columnValue'=>'1','returnRow'=>'all'));
	}

	

	public function addgiftAction()
	{
		$data = $this->_request->getParams();

		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SAVE'){

			$tableData = $data['table'];
		   	$tableData['rest_quantity'] = $data['table']['quantity'];
			$tableData['created_by'] = $_SESSION['AdminLoginID'];
		   	$tableData['created_ip'] = $_SERVER['REMOTE_ADDR'];
		   	if($this->ObjModel->saveData(array('tableName'=>'app_gifts','tableData'=>$tableData))>0) {

				$_SESSION[SUCCESS_MSG] = "Gift added successfully!!";

				$this->_redirect($this->getRequest()->getControllerName().'/gift');

			}

			else {

				$_SESSION[ERROR_MSG] = "Gift not added successfully!!";

				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());

			}

	   	}

		$this->view->postData = $data['table'];

	}

	

	public function assigngiftAction()

	{

		$data = $this->_request->getParams();

		$this->view->postData = $data['table'];

		$this->view->gift = $this->ObjModel->getTableData(array('tableName'=>'app_gifts','tableColumn'=>array(),'columnName'=>'gift_id','columnValue'=>$data['token']));

		$this->view->beDetails = $this->ObjModel->getDesignationWiseUserLists(array('designationID'=>'8'));

		

		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SAVE'){

			$tableData = $data['table'];

		   	$tableData['valid_from']  = $this->view->gift['valid_from'];

			$tableData['valid_to'] 	  = $this->view->gift['valid_to'];

			$tableData['assigned_by'] = $_SESSION['AdminLoginID'];

		   	$tableData['assigned_ip'] = $_SERVER['REMOTE_ADDR'];

		   	if($this->ObjModel->saveData(array('tableName'=>'app_gift_assigned','tableData'=>$tableData))>0) {

				$giftData['rest_quantity'] = ($this->view->gift['rest_quantity']-$tableData['assigned_quantity']);

				$giftData['isModify']  	= '1';

				$giftData['modify_by']  = $_SESSION['AdminLoginID'];

				$giftData['modify_date']= date('Y-m-d H:i:s');

				$giftData['modify_ip'] 	= $_SERVER['REMOTE_ADDR'];

				if($this->ObjModel->updateTableData(array('tableName'=>'app_gifts','tableData'=>$giftData,'whereColumn'=>'gift_id='.$data['token']))) {

					$_SESSION[SUCCESS_MSG] = "Gift assigned successfully!!";

					$this->_redirect($this->getRequest()->getControllerName().'/gift');

				}

				else {

					$_SESSION[ERROR_MSG] = "Gift quantity not updated!!";

					$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName().'/'.$data['token']);

				}

			}

			else {

				$_SESSION[ERROR_MSG] = "Gift not assigned successfully!!";

				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName().'/'.$data['token']);

			}

	   	}

	}

	

	public function assignedgiftAction()

	{

		$data = $this->_request->getParams();

	  	$this->view->tableDatas = $this->ObjModel->getAssignGift(array('giftToken'=>$data['token']));	

	}

	

	public function adddoctorvisitAction(){

	}

	

	public function visitfrequencyAction()

	{

	   	$data = $this->_request->getParams();
		// Export Doctor Visit Data in Excel Sheet

		 if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$filterData = $this->ObjModel->getDoctorVisitFrequency($data);

			$this->ObjModel->ExportDoctorVisitFrequency($this->view->tableHeader,$filterData,$data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }/*elseif(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL' && empty($data['token3']) && empty($data['token4']) && empty($data['token5'])){
		   $_SESSION[ERROR_MSG] =  'Please select any employee(BE,ABM or RBM) to export this report !!';
		   $this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		 }*/
		$this->view->Filterdata = $data;

		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->activities = $this->ObjAjax->getActivityLists();

		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   

	   	$this->view->vistdetail = $this->ObjModel->getDoctorVisitFrequency($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

		

		$this->view->tableHeader = array('DD.doctor_name'=>'Doctor Name','DD.speciality'=>'Speciality','DD.class'=>'Clas of Dr.','DATE_FORMAT(EE.call_date,"%M, %Y")'=>'Month','EE.call_date'=>'Dates of Visit','Name','Emp. Code','Headquater');

	}

	

	public function visitdetailAction()

	{  

	   	$data = $this->_request->getParams();

		$this->view->Filterdata = $data;
		// Export Doctor Visit Data in Excel Sheet

		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportDoctorVisitDetailReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}

		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->activities = $this->ObjAjax->getActivityLists();

		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   
   
	   	$this->view->vistdetail = $this->ObjModel->getDoctorVisitDetailReport($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

	}

	

	public function nonlisteddoctorAction()

	{

	   	$data = $this->_request->getParams();

		$this->view->Filterdata = $data;

		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->activities = $this->ObjAjax->getActivityLists();

		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   

	   	$this->view->vistdetail = $this->ObjModel->getNonListedDoctorReport($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

		

		// Export Doctor Visit Data in Excel Sheet

		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportNonListedDoctorVisitReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}

	}

	

	public function doctorwisecallAction()

	{
		$data = $this->_request->getParams();
		$this->view->Filterdata = $data;
		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportDoctorWiseCallReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}
	    $grid = $this->grid();
		$select = $this->ObjModel->getDoctorWiseCallReport($data);//print_r($select);die;
		$grid->query($select);
		//$right = new Bvb_Grid_Extra_Column();
		//$right->position('right')->name('Action')->title('Action')->decorator("<a href='".Bootstrap::$baseUrl."Setting/addexpsetting/Mode/Update/exp_setting_id/{{exp_setting_id}}'><img src='".Bootstrap::$baseUrl."public/admin_images/pencil.gif' /></a>");
		$grid->setRowAltClasses('odd','even');
		//$grid->updateColumn('exp_setting_id',array('remove'=>true));
		$grid->setRecordsPerPage(100);
		
		//$grid->addExtraColumns($right);
		$grid->setUseKeyEventsOnFilters(true);
		
		$this->view->pages = $grid->deploy();
		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();
		$this->render('doctorwisecall');

	   	
		
		/*$data = $this->_request->getParams();

		$this->view->Filterdata = $data;

		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->activities = $this->ObjAjax->getActivityLists();

		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   

	   	$this->view->vistdetail = $this->ObjModel->getDoctorWiseCallReport($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

		

		// Export Doctor Visit Data in Excel Sheet

		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportDoctorWiseCallReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}*/

	}

	

	public function missedcallAction()

	{

	   	$data = $this->_request->getParams();
		// Export Doctor Visit Data in Excel Sheet

		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportMissedDoctorReportReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}// Export Dctor Missed call Data in Excel Sheet
		  if(isset($data['exportmissedcall']) && !empty($data['exportmissedcall'])){
		
			$this->ObjModel->ExportMissedCall($data);
		
		   $this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		  }
		$this->view->Filterdata = $data;

		$this->view->doctors 	= $this->ObjAjax->getDoctorLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->activities = $this->ObjAjax->getActivityLists();

		$this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   

	   	$this->view->vistdetail = $this->ObjModel->getMissedDoctorReport($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

	}

	

	public function chemistvisitdetailAction()

	{

		$data = $this->_request->getParams();

		$this->view->Filterdata = $data;

		$this->view->doctors = $this->ObjAjax->getChemistLists();

		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();

		$this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

	   	$this->view->vistdetail = $this->ObjModel->getChemistVisitFrequency($data); //echo "<pre>";print_r($this->view->zbmDetails);die;

		

		$this->view->tableHeader = array('CC.chemist_name'=>'Chemist Name','Reportee'=>'Reportee','Patch'=>'Patch','Headquater'=>'Headquater','CC.class'=>'Clas of Chemist','CE.call_date'=>'Month','Days'=>'Dates of Visit');

		

		// Export Doctor Visit Data in Excel Sheet

		 if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$filterData = $this->ObjModel->getChemistVisitFrequency($data);

			$this->ObjModel->ExportChemistVisitFrequency($this->view->tableHeader,$filterData);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }

	}
	
	public function jointworkingAction(){
	    $data = $this->_request->getParams();
	    
		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){
		 	$filterData = $this->ObjModel->getjointWorkingReport($data);
			$this->ObjModel->exportjointWorkingreport($filterData);
		 }
		 
	   if( isset($data['jointworksummury']) && !empty($data['jointworksummury'])){
			$this->ObjModel->ExportJointWorkSummary($data);
		 }
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		
		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   
		$this->view->regions = $this->ObjModel->getRegion();
		$this->view->vistdetail = $this->ObjModel->getjointWorkingReport($data);
	}
	public function activitysummaryAction(){
	   $data = $this->_request->getParams();
	   if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){
		 	$filterData = $this->ObjModel->getActivitySummaryReport($data);
			$this->ObjModel->exportActivitySummaryreport($filterData);
		 }
		 if(!empty($data['exportSummary']) && strtoupper(str_replace(' ','_',$data['exportSummary'])) == 'EXPORT_SUMMARY'){
			$this->ObjModel->ondemandExport($data);
		 }
	    $this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   
	 
	   $this->view->vistdetail = $this->ObjModel->getActivitySummaryReport($data);
	}
	public function monthlysummaryeportAction(){
		$data = $this->_request->getParams();
		 if(!empty($data['Export']) && $data['Export'] == 'Export'){
		    $this->view->vistdetail = $this->ObjModel->getMonthlyWorkedSummary($data);
			$this->ObjModel->monthyworksummaryExport($this->view->vistdetail);
		 }
	     $this->view->beDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   
		 $this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
	     $this->view->vistdetail = $this->ObjModel->getMonthlyWorkedSummary($data);
	}
	/**
     * Simplify the datagrid creation process
     * @return Bvb_Grid_Deploy_Table
     */
    public function grid ($id = '')
    {
        $view = new Zend_View();
        $view->setEncoding('ISO-8859-1');
        $config = new Zend_Config_Ini('./application/grids/grid.ini', 'production');
        $grid = Bvb_Grid::factory('Table', $config, $id);
        $grid->setEscapeOutput(false);
        $grid->setExport(array( 'excel'));
        $grid->setView($view);


        #$grid->saveParamsInSession(true);
        #$grid->setCache(array('enable' => array('form'=>false,'db'=>false), 'instance' => Zend_Registry::get('cache'), 'tag' => 'grid'));
        return $grid;
    }
	
	public function filtersAction ()
    {

        $grid = $this->grid('ois');
		$select = $this->objModel->mygrid();
        $grid->setSource(new Bvb_Grid_Source_Zend_Select($select ));

        $filters = new Bvb_Grid_Filters();
        $filters->addFilter('Name', array('distinct' => array('field' => 'Name', 'name' => 'Name', 'order' => 'field desc')));
        $filters->addFilter('Continent', array('distinct' => array('field' => 'Continent', 'name' => 'Continent')));
        $filters->addFilter('LifeExpectancy', array('render' => 'number', 'distinct' => array('field' => 'LifeExpectancy', 'name' => 'LifeExpectancy')));
        $filters->addFilter('GovernmentForm', array('distinct' => array('field' => 'GovernmentForm', 'name' => 'GovernmentForm')));
        $filters->addFilter('HeadOfState');
        $filters->addFilter('Population', array('render' => 'number'));


        $grid->addFilters($filters);


        $grid->addExternalFilter('new_filter', 'people');
        $grid->addExternalFilter('filter_country', 'filterContinent');

        $this->view->pages = $grid->deploy();
        $this->render('zeroprice');
    }
	
	

}

?>