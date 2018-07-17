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
    public function salaryprocessingAction11(){
	     if(isset($_SESSION['SALARY_SLIP'])){ 
	      Bootstrap::$LabelObj->_filePath = $_SESSION['SALARY_SLIP']; 
		  unset($_SESSION['SALARY_SLIP']);
	      Bootstrap::$LabelObj->printLabel();
	    }
		
	   if($this->_request->isGet() && !empty($this->_data['user_id'])){
	       $this->ObjModel->SalarySlip();  
		   $this->_redirect('Salary/salaryprocessing');
	   }
	   $this->view->finalsalary = $this->ObjModel->FinalSalaryList();
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
	  }
	  if($this->_request->isPost() && !empty($this->_data['recalculate'])){
		   $this->ObjModel->ReCalculationSalary();
	   }
	  if($this->_request->isPost() && !empty($this->_data['Exportsalary'])){ 
		   $this->ObjModel->ExportCurrentSalary();
	   }
	  $this->view->salarylist = $this->ObjModel->SalaryList();
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
			//$this->view->userinfo = $this->ObjModel->getAllUsersForSalary($this->_data['user_id']);
			$this->view->userinfo = $this->ObjModel->getAllUsersForSalaryReporting($this->_data['user_id']);
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
      	$this->view->filteruserList = $this->ObjModel->getAllUsersForSalaryReporting();
   	}
}
?>
