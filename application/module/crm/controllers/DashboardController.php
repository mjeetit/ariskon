<?php
class Crm_DashboardController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){ 
	    
	    $this->ObjModel = new DashboardManager();

	
		if(!isset($_SESSION['AdminLoginID']) || !isset($_SESSION['AdminDesignation'])){
		    $this->ObjModel->CheckForeignsession($this->_request->getParams());
		}

		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('dashboard');
		//$this->_data = $this->_request->getParams();
		
		//$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		Bootstrap::$menuPrivilege  = $this->ObjModel->HeaderMenuItems();
	}
	
	public function indexAction(){
	  $this->view->notification = $this->ObjModel->getMessagesforEmp();
	  $this->view->salaryslip = $this->ObjModel->getSalaryHistory();
	  $this->view->events = $this->ObjModel->getEventsforEmp();
	  $this->view->requests = $this->ObjModel->getLast5leaveRequest();
	  if($_SESSION['AdminLoginID']==1){
	     $this->view->Users = $this->ObjModel->getLast5Userdetail();
	  }
	}
	
}
?>
