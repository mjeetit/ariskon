<?php

class DocumentManager extends Zend_Custom

{
    public $_getData = array();
  	public function getDocumentOfEmp(){

	    $select = $this->_db->select()

		 				->from(array('EDT'=>'emp_docoments'),array('*'))

						->joininner(array('DFT'=>'document_file_type'),"DFT.type_id=EDT.document_type",array('type_name'))

						->where("EDT.user_id='".$_SESSION['AdminLoginID']."'");

	   $doc_data = $this->getAdapter()->fetchAll($select);

	   return  $doc_data;

	}

}

?>