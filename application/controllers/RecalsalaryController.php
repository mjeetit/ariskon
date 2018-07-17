<?php
class RecalsalaryController extends Zend_Controller_Action {
	public function init(){
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->ObjModel = new SalaryManager();
		$this->_helper->layout->setLayout('main');
		
	}
    	
	public function getrecalculateAction(){
	    $salarydate = array(0=>'2013-04-25',1=>'2013-05-25',2=>'2013-06-25',3=>'2013-07-25',4=>'2013-08-25',5=>'2013-09-25',6=>'2013-10-25',7=>'2013-11-25',8=>'2013-12-25',9=>'2014-01-25',10=>'2014-02-25');
		$updatedate = array(0=>'2013-04-25',1=>'2013-05-25',2=>'2013-06-25',3=>'2013-07-25',4=>'2013-08-25',5=>'2013-09-25',6=>'2013-10-25',7=>'2013-11-25',8=>'2013-12-25',9=>'2014-01-25','2014-02-25');
		foreach($salarydate as $key=>$dates){
		  /************************************************************************************
		   function name modified as per the current module to distinguish in main library 
		   custom class functions by jitender maithani on 16072018
		  ************************************************************************************/
		  //$Allusers = $this->ObjModel->getAllUsersForSalary();
		  $Allusers = $this->ObjModel->getAllUsersForSalaryHRM();
		  $this->ObjModel->_salaryDate = $dates;
		  foreach($Allusers as $users){
		     $this->ObjModel->CalculateSalary($users['user_id'],'test.pdf');
			  $encoded_data = json_encode($this->ObjModel->_salaryData['Earnings']+$this->ObjModel->_salaryData['Deduction']+ array('2A'=>$this->ObjModel->_salaryData['PFComp'])+ array('15A'=>$this->ObjModel->_salaryData['ESIComp'])+ array('2B'=>$this->ObjModel->_salaryData['ArrPFComp'])+ array('15B'=>$this->ObjModel->_salaryData['ArrESIComp']));
			  if(!empty($encoded_data)){
			     $this->ObjModel->UpdateEncode($updatedate[($key+1)],$users['user_id'],$encoded_data,$dates);
			  }
		  }
		}die('Done');
	}
	
	
	public function manualsalaryAction(){
	   	$obj = new ManaualSalary();
		$data = $this->_request->getParams();
		if($this->_request->isPost()){
		   echo "<pre>";print_r($data);die;
		   $obj->generateManualSalary($data);
		}
		$this->view->salaryhead = $obj->getSalaryHead();
	}
}
?>
