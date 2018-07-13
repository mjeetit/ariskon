<?php
class ReportingManagerold extends DataSettingManager
{
	public function getDoctorVisist()
	{
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.call_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.call_date,'%Y-%m')")
							   ->order("EE.call_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;	
	 }
	 
	public function productsdetail($data)
	{
	   $where = array();
	   if($data['product1']>0){
	      $where[] = $data['product1'];
	   }
	   if($data['product2']>0){
	      $where[] = $data['product2'];
	   }
	   if($data['product3']>0){
	      $where[] = $data['product3'];
	   }
	   if($data['product4']>0){
	      $where[] = $data['product4'];
	   }
	   if($data['product5']>0){
	      $where[] = $data['product5'];
	   }	
	    $productlist = array();
	   if(!empty($where)){  
	     foreach($where as $product_id){
			$select = $this->_db->select()
								   ->from(array('CP'=>'crm_products'),array('product_name','product_code'))
								   ->where("product_id='".$product_id."'");
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchRow($select);
    		
			   $productlist[] = $result['product_code'].'-'.$result['product_name'];
			}	
			return implode('<br>',$productlist);	  
		}
	 }
	
	public function visitWithdetail($data)
	{
	   $visitwith = array();
	   if($data['be_visit']>0){
		  $visitwith[] = $data['be_visit'];
		}
		if($data['abm_visit']>0){
		  $visitwith[] = $data['abm_visit'];
		}
		if($data['zbm_visit']>0){
		  $visitwith[] = $data['zbm_visit'];
		}
		if($data['rbm_visit']>0){
		  $visitwith[] = $data['rbm_visit'];
		}
		$visitusers = array();
		if(!empty($visitwith)){
	     $select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
							   ->where("ED.user_id IN(".implode(',',$visitwith).")");
		$result =  $this->getAdapter()->fetchAll($select);
			foreach($result as $employees){
			  $visitusers[] = $employees['first_name'].'('.$employees['designation_code'].')';
			}
		}
		
	    return implode('<br>',$visitusers);
	 }
	 
	public function getChemistVisist()
	{
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_chemist_visit'),array('*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','parent_id'))
							   ->joininner(array('CT'=>'crm_chemists'),"CT.chemist_id=EE.chemist_id",array('chemist_name'));
								
			$result =  $this->getAdapter()->fetchAll($select);
		return $result;	
	 }
	 
	public function getStockistVisist()
	{
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array('*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','parent_id'))
							   ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=EE.doctor_id",array('doctor_name'));
								
			$result =  $this->getAdapter()->fetchAll($select);
		return $result;	
	 }
	
	public function productunitdetail($data)
	{
	   $where = array();
	   if($data['product1']>0){
	      $where[] = $data['product1'];
	   }
	   if($data['product2']>0){
	      $where[] = $data['product2'];
	   }
	   if($data['product3']>0){
	      $where[] = $data['product3'];
	   }
	   if($data['product4']>0){
	      $where[] = $data['product4'];
	   }
	   if($data['product5']>0){
	      $where[] = $data['product5'];
	   }	
	    $productlist = array();
	   if(!empty($where)){  
	     $i=1;
	     foreach($where as $product_id){
			$select = $this->_db->select()
								   ->from(array('CP'=>'crm_products'),array('product_name','product_code'))
								   ->where("product_id='".$product_id."'");
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchRow($select);
    		
			   $productlist[] = $result['product_code'].'-'.$result['product_name'].'('.$data['unit'.$i].')';
			$i++;
			}	
			return implode('<br>',$productlist);	  
		}
	 }
	 
	public function getStockistVisist1()
	{
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array('*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','parent_id'))
							   ->joininner(array('ST'=>'crm_doctor'),"ST.doctor_id=EE.stockist_id",array('doctor_name as stockist_name'));
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }
	 
	public function getHeadquater()
	{
	    $select = $this->_db->select()
							   ->from(array('EE'=>'headquater'),array('*'));
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }
	 
	public function getDoctorVisistDetail($data)
	{
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('*'))
							   ->joininner(array('CD'=>'crm_doctors'),"CD.doctor_id=EE.doctor_id",array('doctor_name'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CD.patch_id",array('patch_name'))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where("EE.user_id='".$data['user_id']."'")
							   ->order("EE.call_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }

	public function getTableData($data=array())
	{
		try
		{
			$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';
			$tableColumn = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');
			$returnRow   = (isset($data['returnRow'])   && !empty($data['returnRow']))    ? $data['returnRow']       : 'single';
			
			$where = '1';
			if(isset($data['columnName']) && isset($data['columnValue'])) {
				$where .=  " AND ".$data['columnName']."='".$data['columnValue']."'";
			}
			if(isset($data['columnName1']) && isset($data['columnValue1'])) {
				$where .=  " AND ".$data['columnName1']."='".$data['columnValue1']."'";
			}
			
			$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //echo $select->__toString();die;
			return ($returnRow=='single') ? $this->getAdapter()->fetchRow($select) : $this->getAdapter()->fetchAll($select);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	 
	public function saveData($data=array())
	{
		try
		{
			$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
			$tableData = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
			
			if(!empty($tableName) && count($tableData)>0) {
				return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
			}
			else {
				return 0;
			}
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function updateTableData($data=array())
	{
		try
		{
			$tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
			$tableData   = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
			$whereColumn = (isset($data['whereColumn']) && !empty($data['whereColumn'])) ? trim($data['whereColumn']) : '';
			
			if(!empty($tableName) && count($tableData)>0 && !empty($whereColumn)) {
				return ($this->_db->update($tableName,array_filter($tableData),$whereColumn)) ? TRUE : FALSE;
			}
			else {
				return FALSE;
			}
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getAssignGift($data=array())
	{
		try
		{
		  $giftID = (isset($data['giftToken']) && !empty($data['giftToken'])) ? trim($data['giftToken']) : '0';
		  $select = $this->_db->select()
							->from(array('AG'=>'app_gift_assigned'),array('AG.assigned_quantity','AG.valid_from','AG.valid_to'))
							->joininner(array('GT'=>'app_gifts'),"GT.gift_id=AG.gift_id",array('GT.gift_name'))
							->joininner(array('UT'=>'employee_personaldetail'),"UT.user_id=AG.user_id",array('empName'=>"CONCAT(UT.first_name,' ',UT.last_name)"))
							->where('AG.gift_id='.$giftID)
							->order("AG.assigned_date DESC"); //echo $select->__toString();die;
			$result = $this->getAdapter()->fetchAll($select);
			return $result;
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getDesignationWiseUserLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;_headquarters
				$this->getParents($_SESSION['AdminLoginID']); //print_r($this->_parentIDs);die;
				$where = 'parent_id IN ('.implode(',',array_unique($this->_parentIDs)).')';
			}
			
			$query = $this->_db->select()
							->from('employee_personaldetail',array('user_id','first_name','last_name','employee_code'))
							->where($where)
							->where('designation_id=?',$data['designationID'])
							->where("user_status='1' AND delete_status='0'")
							->order('first_name','ASC'); 
			//echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}  
}
?>