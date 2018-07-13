<?php
class MeasurementManager extends Zend_Custom
{
	public function getListData() {
		$query = $this->_db->select()
				 ->from(array('crm_product_packtypes'),'*')
				 ->order('type_name','ASC'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	public function getdetail($data) {
		try {
			$query = $this->_db->select()
					 ->from(array('crm_product_packtypes'),'*')
					 ->where('pack_type='.Class_Encryption::decode($data['token'])); //echo $query->__toString();die;
			return $this->getAdapter()->fetchRow($query);
		}
		catch (Exception $e) {
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function addtabledata($data=array()){
		$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$tableData = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
		
		if(!empty($tableName) && count($tableData)>0) {
			return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
		}
		else {
			return 0;
		}
	}
	
	public function updateTableData($data=array()){
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
}
?>