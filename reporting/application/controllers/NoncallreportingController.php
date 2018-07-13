<?php
class NoncallreportingController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
	    if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	    }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 4;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new NoncallreportingManager();
		$this->ObjAjax  = new AjaxManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
	public function meetingsAction(){
	  	$data = $this->_request->getParams();
		$this->view->Filterdata 	= $data;
		$this->view->doctors 		= $this->ObjAjax->getMeetingTypeLists();
		$this->view->headquarters 	= $this->ObjAjax->getHeadquarterLists();
		$this->view->beDetails 		= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->abmDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->rbmDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->zbmDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   
	   	$this->view->vistdetail 	= $this->ObjModel->getMeeting($data); //echo "<pre>";print_r($this->view->zbmDetails);die;
		
		//export data added 28 may 2016
		$this->view->tableHeader = array('ED.first_name'=>'Employee Name','ED.employee_code'=>'Employee Code','DT.designation_name'=>'Designation','HT.headquater_name'=>'Headquater','MT.type_name'=>'Meeting Type','Call_Avg'=>'Details','EE.meeting_date'=>'Activity Date','EE.meetingtime_start'=>'Time Start','EE.meetingtime_end'=>'Time End');

		

		// Export Doctor Visit Data in Excel Sheet

		 if(!empty($data['exportmeeting']) && strtoupper(str_replace(' ','_',$data['exportmeeting'])) == 'EXPORT_IN_EXCEL'){

		 	$filterData = $this->ObjModel->getMeetingExportdata($data);

			$this->ObjModel->Exportmeetings($this->view->tableHeader,$filterData);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		 }
		
	}
	
	public function otheractivityAction(){
	    $data = $this->_request->getParams();
		$this->view->Filterdata 	= $data;
		$this->view->doctors 		= $this->ObjAjax->getActivityLists();
		$this->view->headquarters 	= $this->ObjAjax->getHeadquarterLists();
		$this->view->beDetails 		= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->abmDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->rbmDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->zbmDetails 	= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));	   
	   	$this->view->vistdetail 	= $this->ObjModel->getOtherActivity($data); //echo "<pre>";print_r($this->view->zbmDetails);die;
	}
}
?>
