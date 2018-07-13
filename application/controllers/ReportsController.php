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
          if($this->_request->isGet() && !empty($this->_data['export'])){
		     if($this->_data['export_option']==1){
              $this->ObjModel->ExportExpenseReportHqwise();
			 }
			 if($this->_data['export_option']==2 && ($this->_data['user_id']>0 || $this->_data['tocken_abm']>0 || $this->_data['tocken_be']>0)){
              $this->ObjModel->ExportExpenseReportempwise();
			 }else if($this->_data['export_option']==2 && $this->_data['user_id']<=0 && $this->_data['tocken_abm']<=0 && $this->_data['tocken_be']<=0){
			    $_SESSION[ERROR_MSG] = 'Please select an employee for Employee Wise Report';
			    $this->_redirect($this->_request->getControllerName().'/'.$this->_request->getActionName());
			 }

          }
		$this->view->empandHQ = $this->ObjModel->getDesigAndHQ();  
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
	
	public function expensedetailsAction(){ 
	   $this->view->expensesrep = $this->ObjModel->getExpenseReportDetail();
	}
	  	
}
?>
