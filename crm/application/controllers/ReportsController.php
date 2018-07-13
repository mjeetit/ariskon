<?php

class ReportsController extends Zend_Controller_Action {

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

		Bootstrap::$_parent = 18;

		Bootstrap::$_level = 2;

		Bootstrap::$ActionName  = $this->_request->getActionName();

		$this->_helper->layout->setLayout('main');

		$this->_data = $this->_request->getParams();

		$this->ObjModel = new ReportsManager();

		$this->ObjAjax = new AjaxManager();

		$this->ObjModel->_getData = $this->_data;

		$this->view->ObjModel = $this->ObjModel;

		

		// Get and check access of module privilege

		// If condition is temporary to see all patch code

		if($this->_request->getActionName() != 'detailreport') {

			Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();

			//$this->ObjModel->checkPrivileged();

		}

	}

	

    public function indexAction()

	{

	     $data = $this->_request->getParams();

		 

		 // Get report in Excel Sheet

		 if(!empty($data['Export'])){

		   $this->ObjModel->ExportHistory($data);

		 }

		 

		 $this->view->Filterdata = $data;

		 $this->view->doctors = $this->ObjAjax->getroidoctorlist();

		 $this->view->headquarters = $this->ObjAjax->getroiheadquarterlists();

		 //$this->view->beDetails = (count($this->view->headquarters)>0) ? $this->ObjAjax->getroibelists(array('designationID'=>'8','hq'=>$this->view->headquarters)) : array();

		 

		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		 $this->view->abmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		 $this->view->rbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		 $this->view->zbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

		 $this->view->expenses 	= $this->ObjModel->getExpenseLists();

		 $this->view->rois 		= $this->ObjModel->getROIs($data); //echo "<pre>";print_r($this->view->rois);die;

	}

	

	public function detailreportAction()

	{

	     $data = $this->_request->getParams();

		 

		 // Get report in Excel Sheet

		 if(!empty($data['Export'])){

		   $this->ObjModel->ExportHistory($data);

		 }

		 

		 $this->view->Filterdata = $data;

		 $this->view->doctors = $this->ObjAjax->getroidoctorlist();

		 $this->view->headquarters = $this->ObjAjax->getroiheadquarterlists();

		 //$this->view->beDetails = (count($this->view->headquarters)>0) ? $this->ObjAjax->getroibelists(array('designationID'=>'8','hq'=>$this->view->headquarters)) : array();

		 

		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		 $this->view->abmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		 $this->view->rbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		 $this->view->zbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

		 $this->view->expenses 	= $this->ObjModel->getExpenseLists();

		 $this->view->detailreport = $this->ObjModel->getRoiReportDetail($data);

	}

	

	public function crmreportsAction()

	{

	     $data = $this->_request->getParams();

		 

		 // Get report in Excel Sheet

		 if(!empty($data['Export'])){

		   $this->ObjModel->ExportHistory($data);

		 }

		 

		 $this->view->Filterdata = $data;

		 $this->view->doctors = $this->ObjAjax->getroidoctorlist();

		 $this->view->headquarters = $this->ObjAjax->getroiheadquarterlists();

		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		 $this->view->abmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		 $this->view->rbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		 $this->view->zbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

		 $this->view->expenses 	= $this->ObjModel->getExpenseLists();

		 $this->view->appointments = $this->ObjModel->getCrmReport($data); //echo "<pre>";print_r($this->view->rois);die;

		 

		 // Export Doctor Visit Data in Excel Sheet

		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportCrmReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}

	}

	

	public function roireportsAction()

	{

	     $data = $this->_request->getParams();

		 

		 // Get report in Excel Sheet

		 if(!empty($data['Export'])){

		   $this->ObjModel->ExportHistory($data);

		 }

		 

		 $this->view->Filterdata = $data;

		 $this->view->doctors = $this->ObjAjax->getroidoctorlist();

		 $this->view->headquarters = $this->ObjAjax->getroiheadquarterlists();

		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));

		 $this->view->abmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));

		 $this->view->rbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));

		 $this->view->zbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));

		 $this->view->expenses 	= $this->ObjModel->getExpenseLists();

		 $this->view->appointments = $this->ObjModel->getRoiReport($data); //echo "<pre>";print_r($this->view->rois);die;

		 

		 // Export Doctor Visit Data in Excel Sheet

		if(!empty($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL'){

		 	$this->ObjModel->ExportRoiReport($data);

			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());

		}

	}
	
	public function roieditAction(){
	   $data = $this->_request->getParams();
	   if($this->_request->isPost()){
	      $this->ObjModel->UpdateROI($data);
	   }
	   $this->view->products = $this->ObjModel->productList();
	   $this->view->roidetails = $this->ObjModel->getROIDatas($data);
	}

}

?>