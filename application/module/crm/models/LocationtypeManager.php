<?php
class Crm_Model_LocationtypeManager extends Zend_Custom
{
	public function getListData() {
		$query = $this->_db->select()
				 ->from(array('location_types'),'*')
				 ->order('location_type_name','ASC'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	public function saveData($data=array()){
		$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$tableData = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
		
		if(!empty($tableName) && count($tableData)>0) {
			return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
		}
		else {
			return 0;
		}
	}
	
	public function makeProductCode($data=array()) {
		$query = $this->_db->select()->from('crm_products','product_code')->order('product_id DESC')->limit(1); //echo $query->__toString();die;
		$lastCode = $this->getAdapter()->fetchRow($query);
		return (!empty($lastCode)) ? 'MED'.(int) (substr($lastCode['product_code'],2)+1) : 'MED100001';
	}
}
?>