<?php
class EmployeeaccountController extends Zend_Controller_Action {
	
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){

	    if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	    }
	    
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 106;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new EmployeeAccount();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
    public function allowtAction(){
	   $this->view->AccUsers = $this->ObjModel->getItemsList();  
	}
    public function allowetaccesseriesAction(){
	   if($this->_request->isPost()){
	      $this->ObjModel->AddAcceseriesUser();
		  $this->_redirect('Employeeaccount/allowt');
	   }
	   $formobj = new EmpAccountForm();
	   $this->_data['Mode'] = 'Add';
	   $this->view->empAccForm = $formobj->EmployeeAccountForm($this->_data);  
	}
	public function editallowtAction(){
	  if($this->_request->isPost()){
	      $this->ObjModel->UpdateAcceseriesUser();
		  $this->_redirect('Employeeaccount/allowt');
	   }
	  $formobj = new EmpAccountForm();
	  $acceseries = $this->ObjModel->getAcceseriesUsers($this->_data['emp_acc_id']);
	  $acceseriesdata = $acceseries[0];
	  $acceseriesdata['Mode'] = 'Update';
	  $this->view->empAccForm = $formobj->EmployeeAccountForm($acceseriesdata);
	  $this->view->acceseriesdata = $acceseriesdata;
	}
	public function viewallowtAction(){
	   $this->view->AccUsers = $this->ObjModel->detailAllowetList();  
	}		
}
?>
