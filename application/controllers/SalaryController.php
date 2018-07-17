<?php
class SalaryController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 4;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new SalaryManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->view->filter = $this->_data;
		
	}
   
	public function salaryamountAction(){
	  if($this->_request->isPost() && !empty( $this->_data['Addamount'])){ 
	      $this->ObjModel->addSalaryAmount();
		  $this->_redirect($this->_request->getControllerName().'/salarytemplate');
		}
	  $this->view->salaryhead = $this->ObjModel->getSalaryhead();
	  $this->view->amountdetail = $this->ObjModel->AmountDetail();
	  $this->view->user_id = $this->_data['user_id'];
	}
	public function salaryprocessingAction(){
		if(!empty($this->_data['Mode']) && !empty($this->_data['user_id'])){
			$this->ObjModel->GenerateSalary();
			$_SESSION[SUCCESS_MSG] = 'Salary slip has been generated';
			$this->_redirect($this->_request->getControllerName().'/salaryprocessing');
		}
		if($this->_request->isPost() && !empty($this->_data['recalculate'])){
			$this->ObjModel->ReCalculationSalary();
		}
		if($this->_request->isPost() && !empty($this->_data['Exportsalary'])){ 
			$this->ObjModel->ExportCurrentSalary();
		}
		$this->view->salarylist = $this->ObjModel->SalaryList();
		/*********************************************************************************
		function name modify on the basis of main menu either HRM, CRM or Reporting 
		by jm on 16072018
		*********************************************************************************/
		//$this->view->filteruser = $this->ObjModel->getAllUsersForSalary();
		$this->view->filteruser = $this->ObjModel->getAllUsersForSalaryHRM();
		$this->view->filterdesignation = $this->ObjModel->getDesignation();
		$this->view->processingdate = $this->ObjModel->getSalaryDuration();
	}
   public function editsalaryAction(){
      if($this->_request->isPost()){
	     $this->ObjModel->UpdateSalaryDetail();
		 $this->_redirect($this->_request->getControllerName().'/salaryprocessing');
	  }
           $this->ObjModel->_salaryDate = $this->_data['salary_date'];
	   $this->view->user_id = $this->_data['user_id'];
	  $this->view->editsalarydetail = $this->ObjModel->EditsalaryDetail();
	}
  public function salaryhistoryAction(){
     if(!empty($this->_data['ExportSummary']) || !empty($this->_data['ExportDetail'])){
	    $this->ObjModel->ExpotSalaryDetail();
	  }
	  if(!empty($this->_data['Export']) && $this->_data['Export']=='Export'){ 
	    $this->ObjModel->ExportSalaryBYHead($this->_data);
	  }
	  if(!empty($this->_data['print']) && $this->_data['print']=='Print'){ 
	    $this->ObjModel->printSalarySlip($this->_data);
	  }
    $this->view->salaryhistory = $this->ObjModel->getSalaryHistory();
	$this->view->filteruserList = $this->ObjModel->getUserFilterHistory();
	$this->view->filterdapartment = $this->ObjModel->getDepartment();
	$this->view->filterdesignation = $this->ObjModel->getDesignation();
  }	
  public function providenttransactionsAction(){
     if(!empty($this->_data['Mode'])){
	  $this->ObjModel->ExpotPerovidentDetail();
	}
    $this->view->providenttransaction = $this->ObjModel->ProvidentHistory();
  }
  public function extrasettingAction(){
      if($this->_request->isPost()){
	     $this->ObjModel->UpdatePerticularHeadSetting();
		 $this->_redirect($this->_request->getControllerName().'/extrasetting');
	  }
	  $this->view->previoussetting = $this->ObjModel->getPreviousHeadSetting();
  }	
  public function chequenumberAction(){
     $this->_helper->layout->setLayout('popupmain');
      if($this->_request->isPost()){
	     $this->ObjModel->AddUpdateChequeNumber();
	  }
	  $this->view->chequenumber = $this->ObjModel->getchequeNumber($this->_data['user_id']);
  }
   public function arriercurrentsalarylistAction(){
      if($this->_request->isPost() && !empty($this->_data['user_id'])){
	       $usersdata = $this->_data['user_id'];
		   foreach($this->_data['user_id'] as $key=>$user_ids){
		     $filename = 'SALARY_'.$key.'_'.mktime().".pdf";
			 $this->ObjModel->_salaryDate = $user_ids;
			 $this->ObjModel->_getData['Type']='Final';
			 $this->ObjModel->CalculateSalary($key,$filename);
			 //$this->ObjModel->updateslip($key,$filename);
			 $_SESSION[SUCCESS_MSG] = 'Salary Slip has been Uploaded';
			 Bootstrap::$LabelObj->Output(Bootstrap::$root.'/public/salaryslip/'.$filename,'F');
			 ob_end_clean();
		   }
	   }elseif($this->_request->isPost() && empty($this->_data['user_id'])){
	     $_SESSION[ERROR_MSG] = 'There is no record found';
	   }
      $this->view->salarylist = $this->ObjModel->getBackSalaryList();
   	} 
   	public function addarearAction(){
    	$usersalaryhead  = array();
     	if($this->_request->isPost() && !empty($this->_data['submit'])){
			$usersalaryhead  = $this->ObjModel->getUserSalaryHead();
         	/*********************************************************************************
			   	function name modify on the basis of main menu either HRM, CRM or Reporting 
				by jm on 16072018
			*********************************************************************************/
	//$this->view->userinfo 	= $this->ObjModel->getAllUsersForSalary($this->_data['user_id']);
	$this->view->userinfo 	= $this->ObjModel->getAllUsersForSalaryHRM($this->_data['user_id']);
    	}
     	if($this->_request->isPost() && !empty($this->_data['submit_arrear'])){
        	$this->ObjModel->addArrearSalaryhead();
      	}
      	$this->view->usersalaryhead = $usersalaryhead;
      	$this->view->filteruserList = $this->ObjModel->getUserFilterHistory();
   	}
   
	public function manualarrierAction(){
		if($this->_request->isPost() && !empty($this->_data['submit'])){
			$this->ObjModel->insertManualSalary();
		}
		/*********************************************************************************
		  function name modify on the basis of main menu either HRM, CRM or Reporting 
		  by jm on 16072018
		*********************************************************************************/
		//$this->view->filteruserList = $this->ObjModel->getAllUsersForSalary();		
		$this->view->filteruserList = $this->ObjModel->getAllUsersForSalaryHRM();
	}

   	public function addarrierAction(){
        if($this->_request->isPost() && !empty($this->_data['submit'])){
		    $this->ObjModel->insertBunchArrier();
			$_SESSION[SUCCESS_MSG] = 'Arrier Added for selected employee(s)';
		}
		/*********************************************************************************
		  function name modify on the basis of main menu either HRM, CRM or Reporting 
		  by jm on 16072018
		*********************************************************************************/
		//$this->view->filteruserList = $this->ObjModel->getAllUsersForSalary();
       	$this->view->filteruserList = $this->ObjModel->getAllUsersForSalaryHRM();
   	}
}
?>