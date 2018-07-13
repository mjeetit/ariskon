<?php
class Crm_Model_ProductManager extends Zend_Custom
{
	public function getProducts() {
		$query = $this->_db->select()
				 ->from(array('PT'=>'crm_products'),array('PT.product_id','PT.product_name','PT.mrp_incl_vat','PT.stockist_excl_vat','PT.retailer_excl_vat','PT.vat_charged','PT.isActive'))
				 ->joininner(array('PPT'=>'crm_product_packtypes'),"PPT.pack_type=PT.pack_type",array('typeName'=>'PPT.type_name'))
				 ->order('PT.product_name','ASC');
		//echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	public function getMeasurements($data=array()) {
		$select = $this->_db->select()->from('crm_product_packtypes','*')->order('type_name','ASC');
		return $this->getAdapter()->fetchAll($select);
	}
	
	public function addProductData($data=array()){
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