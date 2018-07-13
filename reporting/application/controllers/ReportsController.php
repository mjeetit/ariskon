<?php
class ReportsController extends Zend_Controller_Action {
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
		$this->ObjModel = new ReportManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
                $this->view->filter = $this->_data;
                $this->view->filteruserList = $this->ObjModel->usersList();
                $this->view->filterdapartment = $this->ObjModel->getDepartment();
                $this->view->filterdesignation = $this->ObjModel->getDesignation();
	}
    public function expensereportAction(){
          if($this->_request->isPost() && !empty($this->_data['export'])){
              $this->ObjModel->ExportExpenseReport();

          }
	   $this->view->expensesrep = $this->ObjModel->getExpenseReport();
	}
	
	public function leavereportAction(){
           if($this->_request->isPost() && !empty($this->_data['export'])){
              $this->ObjModel->ExportLeaveReport();

          }
	  $this->view->leavereport = $this->ObjModel->getLeaveReport();
	}
	
	public function attandancereportAction(){
           if($this->_request->isPost() && !empty($this->_data['export'])){
              $this->ObjModel->ExportAttandanceReport();

           }
	   $this->view->attandnceRep = $this->ObjModel->getAttandanceReport();
	}
	  	
}
?>
