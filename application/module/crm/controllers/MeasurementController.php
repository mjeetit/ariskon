<?php
class Crm_MeasurementController extends Zend_Controller_Action {
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
		$this->ObjModel = new MeasurementManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		$this->ObjModel->checkPrivileged();
	}
	
    public function indexAction()
	{
	     $this->view->tableDatas = $this->ObjModel->getListData();
	}
	
	public function addAction()
	{
		$data = $this->_request->getParams();
		// Add New Measurement Details
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['locationtypeAdd'])) == 'ADD_DETAIL'){
			$filterFormData = $this->getfilterFormData(array('formData'=>$data)); //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   
		   	$filterFormData['tableData']['created_by'] = $_SESSION['AdminLoginID'];
		   	$filterFormData['tableData']['created_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   	$addChemistMain = $this->ObjModel->addtabledata(array('tableName'=>'crm_product_packtypes','tableData'=>$filterFormData['tableData']));		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}
	}
	
	public function editAction()
	{
		$data = $this->_request->getParams();
		$this->view->formdata = $data;
		$this->view->info = $this->ObjModel->getdetail($data);
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['updform'])) == 'UPDATE_DETAIL'){
			$data['data']['isModify']    = '1';
			$data['data']['modify_by']   = $_SESSION['AdminLoginID'];
			$data['data']['modify_date'] = new Zend_Db_Expr('NOW()');
			$data['data']['modify_ip']   = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($data);echo "</pre>";die;
		   	if($this->ObjModel->updateTableData(array('tableName'=>'crm_product_packtypes','tableData'=>$data['data'],'whereColumn'=>'pack_type='.Class_Encryption::decode($data['token'])))) {		     
				$_SESSION[SUCCESS_MSG] = "Data updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
			else {
				$_SESSION[ERROR_MSG] = "Data not updated!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
	   	}
	}
	
	public function getfilterFormData($data=array()) {
		$formData = (isset($data['formData']) && count($data['formData'])>0) ? $data['formData'] : array();
		 
		$tableData = array();
		$tableData['type_name'] = (isset($formData['type_name'])) ? trim($formData['type_name']) : '';
		$tableData['isActive'] 	= (isset($formData['status']))    ? trim($formData['status']) : '1';
		 
		 return array('tableData'=>$tableData);
	}
}
?>