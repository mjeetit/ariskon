<?php
class EmployeeAccount extends Zend_Custom
{
   public $_getData = array();
   public function getAcceseriesUsers(){
     try{ 
	    $where = '';
	     $where = " AND EAA.emp_acc_id='".$this->_getData['emp_acc_id']."'";
       $select = $this->_db->select()
                    ->from(array('EAA'=>'emp_acceseries_account'), array('*','sum(acceseries_value) AS Amount'))
					->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=EAA.bunit_id",array('bunit_name'))
					->joininner(array('DP'=>'department'),"DP.department_id=EAA.department_id",array('department_name'))
					->joininner(array('DG'=>'designation'),"DG.designation_id=EAA.designation_id",array('designation_name'))
					->joininner(array('EMP'=>'employee_personaldetail'),"EMP.user_id=EAA.user_id",array('*'))
					->where("1".$where);
            //print_r($select->__toString());die;
           return $this->getAdapter()->fetchAll($select);
	   
	  }catch(Exception $e){
	     $_SESSION[ERROR_MSG] = 'There is some Error. Please Try Again';
	  }  
   }
   public function getItemsList(){
       $select = $this->_db->select()
                    ->from(array('EAA'=>'emp_acceseries_account'), array('*','sum(acceseries_value) AS Amount'))
					->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=EAA.bunit_id",array('bunit_name'))
					->joininner(array('DP'=>'department'),"DP.department_id=EAA.department_id",array('department_name'))
					->joininner(array('DG'=>'designation'),"DG.designation_id=EAA.designation_id",array('designation_name'))
					->joininner(array('EMP'=>'employee_personaldetail'),"EMP.user_id=EAA.user_id",array('*'))
					->where("1".$where)
					->group("EAA.user_id");
            //print_r($select->__toString());die;
           return $this->getAdapter()->fetchAll($select);
   }
   
   public function detailAllowetList($emp_acc_id=false){
     
     try{ 
	    $where = '';
	   if($emp_acc_id){
	     $where = " AND EAA.emp_acc_id='".$emp_acc_id."'";
	   }
       $select = $this->_db->select()
                    ->from(array('EAA'=>'emp_acceseries_account'), array('*'))
					->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=EAA.bunit_id",array('bunit_name'))
					->joininner(array('DP'=>'department'),"DP.department_id=EAA.department_id",array('department_name'))
					->joininner(array('DG'=>'designation'),"DG.designation_id=EAA.designation_id",array('designation_name'))
					->joininner(array('EMP'=>'employee_personaldetail'),"EMP.user_id=EAA.user_id",array('*'))
					->where("1 AND EAA.user_id='".$this->_getData['user_id']."'".$where);
            //print_r($select->__toString());die;
           return $this->getAdapter()->fetchAll($select);
	   
	  }catch(Exception $e){
	     $_SESSION[ERROR_MSG] = 'There is some Error. Please Try Again';
	  }  
   
   }
   
   public function AddAcceseriesUser(){
      $this->insertInToTable('emp_acceseries_account', array($this->_getData));
	  $_SESSION[SUCCESS_MSG] = 'Acceseries Record Added SuccessFully!';
   }
   
   public function UpdateAcceseriesUser(){
     $this->updateTable('emp_acceseries_account', $this->_getData, array('emp_acc_id'=>$this->_getData['emp_acc_id']));
	 $_SESSION[SUCCESS_MSG] = 'Acceseries Record Updated SuccessFully!';
   }
}
?>