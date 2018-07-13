<?php
class EmailManager extends Zend_Custom

{
    public $_getData = array();
  	public function getEmailLogin(){
	  if($_SESSION['AdminLoginID']==1){
	  }else{
	    $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('email as webemail','email_password as webpass'))
						->where("UD.user_id='".$_SESSION['AdminLoginID']."'");
	  }
	 $result = $this->getAdapter()->fetchRow($select);
	 return $result; 
    }
	public function getregionID(){
	       $select = $this->_db->select()
		 				->from(array('EL'=>'emp_locations'),array('region_id'))
						->where("EL.user_id='".$_SESSION['AdminLoginID']."'");
	 $result = $this->getAdapter()->fetchRow($select);
	 return $result['region_id']; 
    }

}

?>