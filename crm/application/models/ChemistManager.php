<?php
class ChemistManager extends Zend_Custom
{
	private $_leaveType 		= "leavetypes";
	private $_leavedistribution = "leavedistributions";
	private $_leaveapproval 	= "leaveapprovals";
	private $_designation 		= "designation";
	
	public function getChemists($data=array())
	{
		try {
			$where = 1;
			$filterparam = '';
				if(isset($data['headtoken']) && (int)$data['headtoken']>0) {
				$where .= ' AND CT.headquater_id='.$data['headtoken'];
			}
			else if ($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLoginID'] != 44) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'CT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			else if (isset($data['token']) && trim($data['token']) > 0) {
				$where = 'CT.chemist_id='.trim($data['token']);
			}
			
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND CT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(CT.created_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'CT.headquater_id');
			
			// Count Total Record
			$countQuery = $this->_db->select()
								 ->from(array('CT'=>'crm_chemists'),array('COUNT(1) AS CNT'))
								 ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CT.patch_id",array())
								 ->joininner(array('CTy'=>'city'),"CTy.city_id=CT.city_id",array())
								 ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=CT.headquater_id",array())
								 ->joininner(array('BT'=>'bussiness_unit'),"BT.bunit_id=CT.bunit_id",array())
								 ->where($where.$filterparam)
								 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			
			// Get all detail
			$query = $this->_db->select()
					 ->from(array('CT'=>'crm_chemists'),array('CT.chemist_id','CT.bunit_id','CT.country_id','CT.zone_id','CT.region_id','CT.area_id','CT.headquater_id','CT.city_id','CT.patch_id','CT.chemist_name','CT.legacy_code','CT.class','CT.contact_person','CT.email','CT.address1','CT.address2','CT.postcode','CT.phone','CT.mobile','CT.isActive'))
					 ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CT.patch_id",array('patch'=>'PT.patch_name',''))
					 ->joininner(array('CTy'=>'city'),"CTy.city_id=CT.city_id",array('cityName'=>'CTy.city_name'))
					 ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=CT.headquater_id",array('hqName'=>'HQ.headquater_name'))
					 ->joininner(array('BT'=>'bussiness_unit'),"BT.bunit_id=CT.bunit_id",array('buName'=>'BT.bunit_name'))
					 ->where($where.$filterparam)
					 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
					 ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($query->__toString());die;
			$result = $this->getAdapter()->fetchAll($query);
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}
	}
	
	public function getLocationData($data=array())
	{
		try {
			$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';
			$tableColumn = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');
			
			$where = '1';
			if(isset($data['columnName']) && isset($data['columnValue'])) {
				$where .=  " AND ".$data['columnName']."='".$data['columnValue']."'";
			}
			if(isset($data['columnName1']) && isset($data['columnValue1'])) {
				$where .=  " AND ".$data['columnName1']."='".$data['columnValue1']."'";
			}
			
			$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //echo $select->__toString();die;
			$tableData = $this->getAdapter()->fetchAll($select);
			
			$responseData = array();
			if(count($tableData) > 0) {
				foreach($tableData as $key=>$data) {
					//$responseData[$data[$tableColumn[1]]] = $data[$tableColumn[0]];
					$responseData[] = $data[$tableColumn[0]];
				}
			}
			
			return $responseData;
		}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}
	}
	
	public function getStreetCodes($data=array()){
		$select = $this->_db->select()->from('street','*')->order('location_code','ASC');
		return $this->getAdapter()->fetchAll($select);
	}
	
	public function addChemistData($data=array())
	{
		$tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$tableData   = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
		$whereColumn = (isset($data['whereColumn'])) ? trim($data['whereColumn']) : ''; //echo "<pre>";print_r($whereColumn);echo "</pre>";die;
		
		if(!empty($tableName) && count($tableData)>0 && empty($whereColumn)) {
			return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
		}
		else if(!empty($tableName) && count($tableData)>0 && !empty($whereColumn)) {
			return ($this->_db->update($tableName,array_filter($tableData),$whereColumn)) ? TRUE : FALSE;
		}
		else {
			return 0;
		}
	}
	
	public function deleteFromTable($data=array()){
        $tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$whereColumn = (isset($data['whereColumn'])) ? trim($data['whereColumn']) : ''; //echo "<pre>";print_r($whereColumn);echo "</pre>";die;
		
		return ($this->_db->delete($tableName,$whereColumn)) ? TRUE : FALSE;
    }
}
?>