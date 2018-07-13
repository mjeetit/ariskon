<?php
class ActivityController extends Zend_Controller_Action {
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
		$this->ObjModel = new ActivityManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		//Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		//$this->ObjModel->checkPrivileged();
	}
	
    public function indexAction(){
	     $this->view->tableDatas = $this->ObjModel->getListData();
	}
	
	public function addAction(){
		$data = $this->_request->getParams();
		// Add New Chemist Details
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['activityAdd'])) == 'SAVE'){
			$filterFormData = $this->getfilterFormData(array('formData'=>$data)); //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   
		   	$filterFormData['tableData']['created_by'] = $_SESSION['AdminLoginID'];
		   	$filterFormData['tableData']['created_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   	$addChemistMain = $this->ObjModel->saveData(array('tableName'=>'crm_activity','tableData'=>$filterFormData['tableData']));		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}
	}
	
	public function getfilterFormData($data=array()) {
		$formData = (isset($data['formData']) && count($data['formData'])>0) ? $data['formData'] : array();
		 
		$tableData = array();
		$tableData['activity_name'] = (isset($formData['activity_name'])) ? trim($formData['activity_name']) : '';
		$tableData['isActive'] 		= (isset($formData['status']))    ? trim($formData['status']) : '1';
		 
		 return array('tableData'=>$tableData);
	}
}
?>