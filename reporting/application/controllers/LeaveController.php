<?php
class LeaveController extends Zend_Controller_Action {
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
		$this->ObjModel = new LeaveManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
	
    public function indexAction(){
	     $this->view->types = $this->ObjModel->getLeaveTypes();
	}
	
	public function typelistAction(){
	     $this->view->types = $this->ObjModel->getLeaveTypes();
	}
	
	public function typeaddAction(){
		if($this->_request->isPost()){
	       $action =  $this->ObjModel->addLeaveType();
		   $this->_redirect($this->_request->getControllerName().'/typelist');
	   }
	}
	
	public function typeeditAction(){
		$data = $this->_request->getParams();
        //$typeID = Class_Encryption::decode($data['token']);
		$typeID = $data['token'];
		
		$this->view->info = $this->ObjModel->getLeaveByID(array('typeID'=>$typeID));
			
	   if($this->_request->isPost() && !empty($data['token'])){
	       $this->ObjModel->editLeaveType();  
		   $this->_redirect($this->_request->getControllerName().'/typelist');
	   }
	}
	
	public function distributionAction(){
		 $this->view->leaveInfo  = $this->ObjModel->getLeaveTypes();
		 $this->view->details    = $this->ObjModel->getLeaveDistribution();
	}
	
	public function distributioneditAction(){
	     $data = $this->_request->getParams();
        //$desigID = Class_Encryption::decode($data['token']);
		$desigID = $data['token'];
		$this->view->info = $this->ObjModel->getLeaveDistributionByID(array('desigID'=>$desigID));
		$this->view->leaveInfo  = $this->ObjModel->getLeaveTypes();
		 
		if($this->_request->isPost()){
			$response = $this->ObjModel->updateLeaveDistribution($data);
			$this->_redirect($this->_request->getControllerName().'/distribution');
		}
	}
	
	public function approvalAction(){
	     $this->view->leaveapprovals = $this->ObjModel->getLeaveApprovals();
	}
	
	public function approvaleditAction() {
	     $data = $this->_request->getParams();
        //$desigID = Class_Encryption::decode($data['token']);
		$desigID = $data['token'];
		$this->view->info = $this->ObjModel->getApprovalByID(array('desigID'=>$desigID));
		 
		if($this->_request->isPost() && $data['updateApproval'] == 'Update'){
			$response = $this->ObjModel->updateLeaveApproval($data); //print_r($response);die;
			$this->_redirect($this->_request->getControllerName().'/approval');
		}
	}
}
?>