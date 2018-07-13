<?php

class GeneralModel extends Zend_Custom

{
  public $_getData = array();
  public function ModuleAndSubModule($parent_id = 0){

     $privillage = $this->Privillage();

	 if($privillage){

	   $where = " AND module_id IN(".$privillage.")";

	 }

      $select = $this->_db->select()

		 				->from('module',array('*'))

						->where("parent_id='".$parent_id."'".$where."");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchAll($select);

	  return $result;

  }

  public function Privillage(){

    if($_SESSION['AdminUserType']==1){

	   return false;

	}else{

	  return "1,5,24,33,34,35,38";

	}

  }

}

?>