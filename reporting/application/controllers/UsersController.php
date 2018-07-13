<?php
class UsersController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 5;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new Users();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
    public function userAction(){
	   $this->view->Users = $this->ObjModel->getUsers();
	}
	public function adduserAction(){
	  $this->view->form = 1;
	  if($this->_request->isPost()){ 
	      $this->view->form = 1;
		 // print_r($this->_data);die;
	       $this->ObjModel->addNewUser();
		   $this->_redirect($this->_request->getControllerName().'/user');
		}
	}
	public function edituserAction(){
	 if($this->_request->isPost()){
	       $this->ObjModel->editUser();
	       $this->_redirect($this->_request->getControllerName().'/user');
	 }
	   $this->view->UserDetail = $this->ObjModel->EditUserDetail();
	}
	public function employeesalaryAction(){
	   if($this->_request->isPost() && !empty($this->_data['updateamount'])){
	       $this->ObjModel->UpdateSalaryAmountForEmployee();
	   }
	   $this->view->salaryAmount = $this->ObjModel->getSalaryTemplateForEmployee();
	  // print_r($this->view->salaryAmount);die;
	}
   public function viewAction(){
     $this->_helper->layout->setLayout('popupmain');
     $this->view->UserDetail = $this->ObjModel->viewUserDetail();
   }
   
   public function privillageAction(){
     $this->_helper->layout->setLayout('popupmain'); 
	  if($this->_request->isPost()){
	       $this->ObjModel->AddPrivillage();
	   }
	 $obj =  new PrivillageForm();//print_r($this->ObjModel->getUsersPrivForEdit());die;
	 $this->view->privForm  = $obj->UserPrivillageForm($this->ObjModel->getUsersPrivForEdit());
   }
   public function deleteAction(){
     $this->_helper->layout->setLayout('popupmain');
	 if($this->_request->isPost()){
	       $this->ObjModel->deleteusers();
	   } 
	 $this->view->parentlist = $this->ObjModel->getParentListForAssign();
   }
   public function changepasswordAction(){
      $this->_helper->layout->setLayout('popupmain');
	  if($this->_request->isPost()){
	       $this->ObjModel->changePassword();
	   } 
	  $this->view->parentlist = $this->ObjModel->getParentListForAssign();
   }
   public function settlemnetAction(){
       if(!empty($this->_data['employee_code'])){
            $employeedetail = $this->ObjModel->usersettlementDetail();
			$this->view->detail     = $employeedetail['GeneralDetail'];
			$this->view->salarylist = $employeedetail['SalaryDetail'];
       }
	   if(!empty($this->_data['submit'])  && $this->_data['submit']=='Update'){
	       $this->ObjModel->finalsettlement();
	   }
       //$this->view->userlist =
   }
   public function previousemployeeAction(){
       $this->view->Users = $this->ObjModel->previusemployee();
   } 
    public function empleaveAction(){
     if($this->_request->isPost()){
	    $this->ObjModel->updateEmployeeLeaves();
	 }
     $this->view->empleaves = $this->ObjModel->getempleaves();
   }
   public function userhistoryAction(){
       //$this->_helper->layout->setLayout('popupmain'); 
       $this->view->history = $this->ObjModel->getUserHistory();
   } 	
}
?>
