<?php
class ManaualSalary extends Zend_Custom{


  
     public function getSalaryHead(){
	    $select = $this->_db->select()
                                ->from(array('UT'=>'employee_salary_amount'),array('*'))
                                ->joininner(array('SH'=>'salary_head'),"SH.salaryhead_id=UT.salaryhead_id",array("salary_title"))
                                ->where("user_id=91")
                                ->order("salaryhead_id ASC");
                                //echo $select->__toString();die;
           return  $this->getAdapter()->fetchAll($select);
						
		$results = $this->getAdapter()->fetchAll($select); 
	  return $results; 				
	 }
	 
	 public function generateManualSalary($data){
	    $filename = 'SALARY_'.mktime().".pdf";
	    $processdata = array();
		foreach($data['salary'] as $key=>$headamount){
		   $processdata['Earnings'][$key] =  $headamount;    
		}
		foreach($data['arrier'] as $key=>$headamount){
		   $processdata['ArrierEarnings'][$key] =  $headamount  ;  
		}
		
		Bootstrap::$LabelObj->outputparam = $this->_salaryData;
		Bootstrap::$LabelObj->SalarySlip();
		Bootstrap::$LabelObj->Output(Bootstrap::$root.'/public/salaryslip/'.$filename,'F');	 
		ob_end_clean();
	    Bootstrap::$LabelObj->Output($filename,'D');
	 }
}
?>