<?php
class AttandanceController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
	    if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	    }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 4;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new AttandanceManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
	public function attandancelistAction(){
	  if($this->_request->isPost() && $this->_data['Mode']=='Delete'){
		  $this->ObjModel->deleteAttandance(); 
		  $this->_redirect('Attandance/attandancelist');
		}
	  $this->view->Attandancelist = $this->ObjModel->AttamdanceList();
	}
	public function uploadeattandanceAction(){
	  if($this->_request->isPost()){
	    $this->ObjModel->UploadeAttandance(); 
		$this->_redirect('Attandance/attandancelist');
	  }
	}
	public function editattandanceAction(){
	   if($this->_request->isPost()){
	      $this->ObjModel->UpdateAttandance(); 
		  $this->_redirect('Attandance/attandancelist'); 
	   }
	  $attandance = $this->ObjModel->AttamdanceList($this->_data['attandance_id']);
	  $formobj = new AttandanceForm();
	  $this->view->attandanceFrom = $formobj->EmpattandanceForm($attandance[0]);
	}
	public function manualattandanceAction(){
	   if($this->_request->isPost()){
	      $this->ObjModel->ManualAttandance(); 
		  $this->_redirect('Attandance/attandancelist'); 
	   }
	}
	public function testuploadAction(){
	  if($this->_request->isPost()){
	    $this->ObjModel->update_test(); 
	  }
	}
  
}
?>
