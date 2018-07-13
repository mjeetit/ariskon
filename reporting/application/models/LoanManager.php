<?php

class LoanManager extends Zend_Custom

{
     public $_getData = array();
  	 public function AddloanUser(){

	    $this->_getData['user_id'] =$this->_getData['parent_id'];

		$this->_getData['emi_amount'] =$this->_getData['loan_including_tax']/$this->_getData['no_of_emi'];

	    $this->insertInToTable('emp_loan',array($this->_getData));

	 }

	 public function editLoanUser(){

	    $this->_getData['user_id'] =$this->_getData['parent_id'];

		$this->_getData['emi_amount'] =$this->_getData['loan_including_tax']/$this->_getData['no_of_emi'];

	    $this->updateTable('emp_loan',$this->_getData,array("emp_loan_id"=>$this->_getData['emp_loan_id']));

	 }

	 

	 public function gerLoanUser(){

	     $select = $this->_db->select()

		 				->from(array('EL'=>'emp_loan'),array('*'))

						->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=EL.user_id",array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=EL.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=EL.department_id",array('department_name'));

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	 }

	 public function gerLoanUserById(){

	     $select = $this->_db->select()

		 				->from(array('EL'=>'emp_loan'),array('*'))

						->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=EL.user_id",array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=EL.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=EL.department_id",array('department_name'))

						->where("emp_loan_id='".$this->_getData['emp_loan_id']."'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchRow($select);

		return $result; 

	 }

}

?>