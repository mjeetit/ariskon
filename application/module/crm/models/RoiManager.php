<?php
class Crm_Model_RoiManager extends Zend_Custom
{
	public function getROIs($data)
	{
		try {
			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
			
			$where 		 = 1;
			$filterparam = '';
			/*if ($_SESSION['AdminLevelID'] != 1) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}*/
			if ($_SESSION['AdminLevelID'] == 1) {
				$where = 1;
			}
			else if ($_SESSION['AdminDesignation'] == 8) {
				//$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = ' AT.be_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			else if ($_SESSION['AdminDesignation'] == 7) {
				//$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = ' AT.abm_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			else if ($_SESSION['AdminDesignation'] == 6) {
				//$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = ' AT.rbm_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			else if ($_SESSION['AdminDesignation'] == 5) {
				//$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = ' AT.zbm_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadquarters(Class_Encryption::decode($data['token6']));
				//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam .= ' AND AT.zbm_id='.Class_Encryption::decode($data['token6']);
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadquarters(Class_Encryption::decode($data['token5']));
				//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam .= ' AND AT.rbm_id='.Class_Encryption::decode($data['token5']);
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadquarters(Class_Encryption::decode($data['token4']));
				//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam .= ' AND AT.abm_id='.Class_Encryption::decode($data['token4']);
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadquarters(Class_Encryption::decode($data['token3']));
				//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam .= ' AND AT.be_id='.Class_Encryption::decode($data['token3']);
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND AT.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE_FORMAT(ROI.roi_month,'%Y-%m') BETWEEN '".date("Y-m", strtotime($data['from_date']))."' AND '".date("Y-m", strtotime($data['to_date']))."'";
			}
			
			//Order DATE_FORMAT(EE.tour_date,'%Y-%m')='2016-01'
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'ROI.roi_month');
			
			$countQuery = $this->_db->select()
						 ->from(array('ROI'=>'crm_roi'),array('COUNT(1) AS CNT'))
						 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=ROI.doctor_id",array())
						 ->joininner(array('AT'=>'crm_appointments'),"AT.doctor_id=DT.doctor_id",array())
						 ->where($where.$filterparam)
						 ->where("DATE(AT.business_audit_date)>'".$financialYear."'")
						 ->where("AT.business_audit_status='1'")
						 ->where("AT.isActive='1'")
						 ->where("AT.isDelete='0'")
						 ->group('AT.doctor_id' )
						 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
				
			$query = $this->_db->select()
					 ->from(array('ROI'=>'crm_roi'),array('roiAmount'=>'getRoiAmount(ROI.doctor_id)','roiApproval'=>'getLastRoiApproval(ROI.doctor_id)','roi_month'=>'DATE_FORMAT(ROI.roi_month,"%M-%Y")'))
					 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=ROI.doctor_id",array('DT.doctor_name'))
					 ->joininner(array('AT'=>'crm_appointments'),"AT.doctor_id=DT.doctor_id",array('AT.doctor_id','AT.abm_id','AT.rbm_id','AT.zbm_id','expenseCost'=>'SUM(AT.expense_cost)','crmAmount'=>'SUM(AT.total_value)'))
					 ->where($where.$filterparam)
					 ->where("DATE(AT.business_audit_date)>'".$financialYear."'")
					 ->where("AT.business_audit_status='1'")
					 ->where("AT.isActive='1'")
					 ->where("AT.isDelete='0'")
					 ->group('AT.doctor_id' )
					 ->group('ROI.roi_month' )
					 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
					 ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //echo $query->__toString();die;
			$result = $this->getAdapter()->fetchAll($query);
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}		
	}
	
	public function getRoiDetail ($data)
	{
		$financialYear  = date("Y-m-d",mktime(0,0,0,date('m')-11,date('d'),date('Y')));
		$doctorID		= (isset($data['token'])) ? Class_Encryption::decode(trim($data['token'])) : 0;
		
		$query = $this->_db->select()
				 ->from(array('RD'=>'crm_roi_details'),array('*'))
				 ->joininner(array('RT'=>'crm_roi'),"RT.roi_id=RD.roi_id",array())
				 ->where("RT.doctor_id='".$doctorID."'"); //echo $query->__toString();die;
		$products = $this->getAdapter()->fetchAll($query);
		$allProducts = array();
		$productPrice = array();
		foreach($products as $product)
		{
			$allProducts[] = $product['product_id'];
			$roiToken = $product['roi_id'];
			$productPrice[$product['product_id']]['unit'] = $product['unit'];
			$productPrice[$product['product_id']]['value'] = $product['value'];			
		}
		
		$crmquery = $this->_db->select()
				 ->from(array('AT'=>'crm_appointments'),array('AT.abm_id','AT.rbm_id','AT.zbm_id'))
				 ->where("AT.doctor_id='".$doctorID."'")
				 ->where("AT.business_audit_status='1'")
				 ->where("AT.isActive='1'")
				 ->where("AT.isDelete='0'")
				 ->order('AT.created_date DESC')
				 ->limit(1); //echo $crmquery->__toString();die;
		$lastCRMDetail = $this->getAdapter()->fetchRow($crmquery);
		
		$roiquery = $this->_db->select()
				 ->from(array('RT'=>'crm_roi'),array('CNT'=>'COUNT(1)'))
				 ->where("RT.doctor_id='".$doctorID."'")
				 ->where("RT.senior_approval='0'")
				 ->order('RT.added_date DESC')
				 ->limit(1); //echo $roiquery->__toString();die;
		$unapprovalROICount = $this->getAdapter()->fetchRow($roiquery);
		
		return array('products'=>$allProducts,'productPrice'=>$productPrice,'roiToken'=>$roiToken,'lastCRMDetail'=>$lastCRMDetail,'unapprovalROICount'=>$unapprovalROICount);
	}
		
	public function getProductAgainstCRM($data=array())
	{
		$financialYear  = date("Y-m-d",mktime(0,0,0,date('m')-11,date('d'),date('Y')));
		$doctorID		= (isset($data['doctorID'])) ? trim($data['doctorID']) : 0;
		
		$query = $this->_db->select()
				 ->from(array('AP'=>'crm_appointments'),array())
				 ->joininner(array('AMT'=>'crm_appointment_potential_months'),"AMT.appointment_id=AP.appointment_id",array())
				 ->joininner(array('AMPT'=>'crm_appointment_potential_month_products'),"AMPT.potential_month_id=AMT.potential_month_id",array('DISTINCT(AMPT.product_id)'))
				 ->where("AP.doctor_id='".$doctorID."'")
				 ->where("DATE(AP.created_date)>'".$financialYear."'")
				 ->where("AP.isActive='1'")
				 ->where("AP.isDelete='0'"); //echo $query->__toString();die;
		$products = $this->getAdapter()->fetchAll($query);
		$allProducts = array();
		foreach($products as $product)
		{
			$allProducts[] = $product['product_id'];
		}
		return $allProducts;
	}
	
	public function getROIsOLD()
	{
		$query = $this->_db->select()
				 ->from(array('DT'=>'crm_doctors'),array('DT.doctor_id','DT.doctor_code','DT.doctor_name'))
				 ->joinleft(array('ROI'=>'crm_roi'),"ROI.doctor_id=DT.doctor_id",array('roiAmount'=>'SUM(ROI.roi_total_amount)'))
				 ->where("DT.isActive='1'")
				 ->where("DT.isDelete='0'")
				 ->order('DT.doctor_name','ASC'); //echo $query->__toString();die;
		print_r($this->getAdapter()->fetchAll($query));die;
	}
	
	public function getDoctorROI($data=array())
	{
		$query = $this->_db->select()
				 ->from(array('DT'=>'crm_doctors'),array('DT.doctor_id','DT.doctor_code','DT.doctor_name'))
				 ->joinleft(array('ROI'=>'crm_roi'),"ROI.doctor_id=DT.doctor_id AND ROI.roi_year='".date('Y')."'",array('ROI.*'))
				 ->where("DT.doctor_id=".$data['doctorID'])
				 ->where("DT.isActive='1'")
				 ->where("DT.isDelete='0'")
				 ->order('DT.doctor_name','ASC'); echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	public function addProductData($data=array())
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
	
	public function addProductDataForEdit($data=array())
	{
		$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$tableData = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
		
		if(!empty($tableName) && count($tableData)>0) {
			return ($this->_db->insert($tableName,array_filter($tableData))) ? TRUE : FALSE;
		}
		else {
			return FALSE;
		}
	}
	
	public function getReportROI($data)
	{
		$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
		
		$where 		 = 1;
		$filterparam = '';
		/*if ($_SESSION['AdminLevelID'] != 1) {
			$this->getHeadquarters($_SESSION['AdminLoginID']);
			$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
		}*/
		if ($_SESSION['AdminLevelID'] == 1) {
			$where = 1;
		}
		else if ($_SESSION['AdminDesignation'] == 8) {
			//$this->getHeadquarters($_SESSION['AdminLoginID']);
			$where = ' AT.be_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
		}
		else if ($_SESSION['AdminDesignation'] == 7) {
			//$this->getHeadquarters($_SESSION['AdminLoginID']);
			$where = ' AT.abm_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
		}
		else if ($_SESSION['AdminDesignation'] == 6) {
			//$this->getHeadquarters($_SESSION['AdminLoginID']);
			$where = ' AT.rbm_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
		}
		else if ($_SESSION['AdminDesignation'] == 5) {
			//$this->getHeadquarters($_SESSION['AdminLoginID']);
			$where = ' AT.zbm_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
		}
		
		//Filter With ZBM Data
		if(!empty($data['token6'])){
			$where = '1';$this->_headquarters = array();
			$this->getHeadquarters(Class_Encryption::decode($data['token6']));
			//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			$filterparam .= ' AND AT.zbm_id='.Class_Encryption::decode($data['token6']);
		}
		//Filter With RBM Data
		if(!empty($data['token5'])){
			$where = '1';$this->_headquarters = array();
			$this->getHeadquarters(Class_Encryption::decode($data['token5']));
			//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			$filterparam .= ' AND AT.rbm_id='.Class_Encryption::decode($data['token5']);
		}
		//Filter With ABM Data
		if(!empty($data['token4'])){
			$where = '1';$this->_headquarters = array();
			$this->getHeadquarters(Class_Encryption::decode($data['token4']));
			//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			$filterparam .= ' AND AT.abm_id='.Class_Encryption::decode($data['token4']);
		}
		//Filter With BE Data
		if(!empty($data['token3'])){
			$where = '1';$this->_headquarters = array();
			$this->getHeadquarters(Class_Encryption::decode($data['token3']));
			//$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			$filterparam .= ' AND AT.be_id='.Class_Encryption::decode($data['token3']);
		}
		//Filter With Headquarter Data
		if(!empty($data['token2'])){
			$where = '1';
			$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
		}
		//Filter With Doctor Data
		if(!empty($data['token1'])){
			$filterparam .= " AND AT.doctor_id='".Class_Encryption::decode($data['token1'])."'";
		}
		//Filter With Date Range
		if(!empty($data['from_date']) && !empty($data['to_date'])){
			$filterparam .= " AND DATE(AT.created_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		}
			
		$query = $this->_db->select()
				 ->from(array('AT'=>'crm_appointments'),array('AT.doctor_id','AT.abm_id','AT.rbm_id','AT.zbm_id','expenseCost'=>'SUM(AT.expense_cost)','crmAmount'=>'SUM(AT.total_value)'))
				 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",array('DT.doctor_name'))
				 //->joininner(array('ROI'=>'crm_roi'),"ROI.doctor_id=AT.doctor_id",array('roiAmount'=>'getRoiAmount(AT.doctor_id,"'.$financialYear.'")','ROI.roi_month'))
				 ->joininner(array('ROI'=>'crm_roi'),"ROI.doctor_id=AT.doctor_id",array('roiAmount'=>'getRoiAmount(AT.doctor_id)','ROI.roi_month'))
				 ->where($where.$filterparam)
				 ->where("DATE(AT.business_audit_date)>'".$financialYear."'")
				 ->where("AT.business_audit_status='1'")
				 ->where("AT.isActive='1'")
				 ->where("AT.isDelete='0'")
				 ->group('AT.doctor_id'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	public function addRecord($table,$data)
	{
		return ($this->_db->insert($table,$data)) ? TRUE : FALSE;
	}
	
	public function updateRecord($table,$data,$condition)
	{
         return ($this->_db->update($table,$data,$condition)) ? TRUE : FALSE;
    }
}
?>