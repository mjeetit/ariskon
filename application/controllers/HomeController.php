<?php
class HomeController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('home');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new DashboardManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
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
