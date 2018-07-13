<?php

    class DataSettingManager extends Zend_Custom

    {

	public $_getData = array();

	public function Addb2c(){

	   $this->_db->insert('bussiness_to_comapny',array('bunit_id'=>$this->_getData['bunit_id'],'company_code'=>$this->_getData['company_code']));

	}

	

	public function Addd2b(){

	   $this->_db->insert('department_to_bunit',array('department_id'=>$this->_getData['department_id'],'bunit_id'=>$this->_getData['bunit_id'])); 

	}

	public function Addd2d(){

	   $this->_db->insert('designation_to_department',array('designation_id'=>$this->_getData['designation_id'],'department_id'=>$this->_getData['department_id'])); 

	}

	

   public function AddBunitToCountry(){

      $this->_db->insert('business_to_country',array('bunit_id'=>$this->_getData['bunit_id'],'country_id'=>$this->_getData['country_id'])); 

  }
  
  public function Editmaping(){
      if($this->_getData['Mode']=='d2d'){
	        $this->_db->update('designation_to_department',array('designation_id'=>$this->_getData['designation_id'],'department_id'=>$this->_getData['department_id']),"desig_to_depart_id='".$this->_getData['desig_to_depart_id']."'"); 
			return 'desigtodepart'; 
	  }
  }
  public function EditdataLocation(){
     if($this->_getData['Mode']=='d2d'){
	   $table = 'designation_to_department';
	   $where = "desig_to_depart_id='".$this->_getData['desig_to_depart_id']."'";
	 }
    $select = $this->_db->select()
								->from(array('DD'=>$table),array('*'))
								->where($where);
								//echo $select->__toString();die;
	$result = $this->getAdapter()->fetchRow($select);
	return $result; 
  }

}

?>