<?php
class DocumentController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 125;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new DocumentManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
   public function documentlistAction(){
     $this->view->Documents = $this->ObjModel->getDocumentOfEmp();
   } 
}
?>
