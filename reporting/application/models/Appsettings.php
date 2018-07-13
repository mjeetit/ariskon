<?php

class Appsettings extends Zend_Custom

{
    public $_getData = array();
  	public function getAppusers(){
		$filterparam = '';
			//Filter With Employee Name
			if(!empty($this->_getData['user_id'])){
				$filterparam .= " AND UD.user_id='".$this->_getData['user_id']."'";
			}
			//Filter With Headquarter
			if(!empty($this->_getData['designation_id'])){
				$filterparam .= " AND DS.designation_id='".$this->_getData['designation_id']."'";
			}
	    $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('*'))
						->joininner(array('AUD'=>'app_userdetails'),"AUD.user_id=UD.user_id",array('*'))
						->joininner(array('DS'=>'designation'),"DS.designation_id=UD.designation_id",array('designation_id','designation_name','designation_code'))
						->joinleft(array('EL'=>'emp_locations'),"EL.user_id=UD.user_id",array())
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
						->where("UD.delete_status='0'".$filterparam);
						//  echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);
	 return $result; 
    }
	
	
	public function getFilterdata(){
	    $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('*'))
						->joininner(array('AUD'=>'app_userdetails'),"AUD.user_id=UD.user_id",array('*'))
						->joininner(array('DS'=>'designation'),"DS.designation_id=UD.designation_id",array('designation_id','designation_name','designation_code'))
						->joinleft(array('EL'=>'emp_locations'),"EL.user_id=UD.user_id",array())
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
						->where("UD.delete_status='0'");
						//  echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);
	 return $result; 
    }
	
	public function UpdateSettings(){
	  $autosynchopt = (!empty($this->_getData['sync_options']))?implode(',',$this->_getData['sync_options']):'';
	  $this->_db->update('app_userdetails',array('reporing_lock'=>$this->_getData['reporing_lock'],'emei_check'=>$this->_getData['emei_check'],'auto_sync'=>$this->_getData['auto_sync'],'tp_check'=>$this->_getData['tp_check'],'lock_action'=>$this->_getData['lock_action'],'sync_options'=>$autosynchopt,'lock_limit'=>$this->_getData['lock_limit'],'check_validation'=>$this->_getData['check_validation']),"id='".$this->_getData['id']."'");
	}
}

?>