<?php
   abstract class AbstractClass extends Zend_Db_Table_Abstract
   {
      public $parent_id = '';
	  public $level_id = '';
      public function getDefaultPrivilege($id=0){
	     $this->parent_id = $id;
	     return $this->getValue();
	  }
   
   }
?>