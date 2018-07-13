<?php
class ReportingsController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init()
	{
	    $this->_redirect('Dashboard');
	    if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	    }
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new ReportingsManager();
		$this->ObjAjax = new AjaxManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->view->filter = $this->_data;
	}
	
	public function doctorvisitAction()
	{
		$data = $this->_request->getParams();
		$this->view->Filterdata = $data;		
		
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
	    $this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->beDetails  = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->patches    = $this->ObjAjax->getPatchLists();
		$this->view->doctors    = $this->ObjAjax->getDoctorLists();		
		$this->view->activities = $this->ObjAjax->getActivityLists();
		$this->view->products   = $this->ObjAjax->getProductLists(); //echo "<pre>";print_r($loginUserDetail);die;
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SUBMIT'){
			$tableData = $data['data'];
		   	$tableData['user_id'] = $_SESSION['AdminLoginID'];
			$tableData['date_added'] = date('Y-m-d h:i:s');
			$tableData['added_through'] = '1';
		   	if($this->ObjAjax->addTableData(array('tableName'=>'app_doctor_visit','tableData'=>$tableData))>0) {
				$_SESSION[SUCCESS_MSG] = "Data added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
			else {
				$_SESSION[ERROR_MSG] = "Data not added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
	   	}
		$this->view->postData = $data['data'];
	}
	
	public function chemistvisitAction()
	{
	   $data = $this->_request->getParams();
		$this->view->Filterdata = $data;		
		
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
	    $this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->beDetails  = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->patches    = $this->ObjAjax->getPatchLists();
		$this->view->chemists   = $this->ObjAjax->getChemistLists();		
		$this->view->products   = $this->ObjAjax->getProductLists(); //echo "<pre>";print_r($loginUserDetail);die;
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SUBMIT'){
			$tableData = $data['data'];
		   	$tableData['user_id'] = $_SESSION['AdminLoginID'];
			$tableData['date_added'] = date('Y-m-d h:i:s');
			$tableData['added_through'] = '1';
		   	if($this->ObjAjax->addTableData(array('tableName'=>'app_chemist_visit','tableData'=>$tableData))>0) {
				$_SESSION[SUCCESS_MSG] = "Data added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
			else {
				$_SESSION[ERROR_MSG] = "Data not added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
	   	}
		$this->view->postData = $data['data'];
	}
	
	public function stockistvisitAction()
	{
	   $data = $this->_request->getParams();
		$this->view->Filterdata = $data;		
		
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
	    $this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		$this->view->beDetails  = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->patches    = $this->ObjAjax->getPatchLists();
		$this->view->stockists  = $this->ObjAjax->getStockistLists(); //echo "<pre>";print_r($loginUserDetail);die;
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SUBMIT'){
			$tableData = $data['data'];
		   	$tableData['user_id'] = $_SESSION['AdminLoginID'];
			$tableData['date_added'] = date('Y-m-d h:i:s');
			$tableData['added_through'] = '1';
		   	if($this->ObjAjax->addTableData(array('tableName'=>'app_stockist_visit','tableData'=>$tableData))>0) {
				$_SESSION[SUCCESS_MSG] = "Data added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
			else {
				$_SESSION[ERROR_MSG] = "Data not added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
	   	}
		$this->view->postData = $data['data'];
	}
	
	public function meetingsAction()
	{
		$data = $this->_request->getParams();
		$this->view->Filterdata = $data;		
		
		$this->view->meetinglocations = $this->ObjAjax->getHeadquarterLists();
		$this->view->meetingtypes     = $this->ObjAjax->getMeetingTypeLists(); //echo "<pre>";print_r($this->view->meetinglocations);die;
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SUBMIT'){
			$tableData = $data['data'];
		   	$tableData['user_id'] = $_SESSION['AdminLoginID'];
			$tableData['date_added'] = date('Y-m-d h:i:s');
			$tableData['added_through'] = '1';
		   	if($this->ObjAjax->addTableData(array('tableName'=>'app_meeting','tableData'=>$tableData))>0) {
				$_SESSION[SUCCESS_MSG] = "Data added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
			else {
				$_SESSION[ERROR_MSG] = "Data not added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
	   	}
		$this->view->postData = $data['data'];
	}
	
	public function otheractivityAction()
	{
		$data = $this->_request->getParams();
		$this->view->Filterdata = $data;		
		
		$this->view->meetingtypes     = $this->ObjAjax->getActivityLists(); //echo "<pre>";print_r($this->view->meetinglocations);die;
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SUBMIT'){
			$tableData = $data['data'];
		   	$tableData['user_id'] = $_SESSION['AdminLoginID'];
			$tableData['date_added'] = date('Y-m-d h:i:s');
			$tableData['added_through'] = '1'; //echo "<pre>";print_r($tableData);die;
		   	if($this->ObjAjax->addTableData(array('tableName'=>'app_noncallreport','tableData'=>$tableData))>0) {
				$_SESSION[SUCCESS_MSG] = "Data added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
			else {
				$_SESSION[ERROR_MSG] = "Data not added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
	   	}
		$this->view->postData = $data['data'];
	}
	
	public function repordetailAction()
	{
	  if($this->_data['Mode']=='Doctor'){
	   $this->view->vistdetail = $this->ObjModel->getDoctorVisistDetail($this->_data);
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
}
?>
