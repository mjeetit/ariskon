<?php

class LoanController extends Zend_Controller_Action {

	var $session="";

	public $users;

	public $ObjModel = NULL;

	public $_data = NULL;



	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');

		$this->_helper->layout->setLayout('main');

		$this->_data = $this->_request->getParams();

		$this->ObjModel = new LoanManager();

		$this->ObjModel->_getData = $this->_data;

		$this->view->ObjModel = $this->ObjModel;

	}

    public function loanuserlistAction(){

	   $this->view->Users = $this->ObjModel->gerLoanUser();

	}

	 public function addloanuserAction(){

	   if($this->_request->isPost()){

	       $this->ObjModel->AddloanUser();

		   $this->_redirect('Loan/loanuserlist');

	   }

	   //$this->view->Users = $this->ObjModel->getUsers();

	}

	 public function editloanuserAction(){

	   if($this->_request->isPost()){

	       $this->ObjModel->editLoanUser();

		   $this->_redirect('Loan/loanuserlist');

	   }

	   $this->view->Users = $this->ObjModel->gerLoanUserById();

	}

}

?>

