<?php
class Crm_StockistController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 18;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new StockistManager();
		$this->ObjAjax  = new AjaxManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		$this->ObjModel->checkPrivileged();
	}
	
	public function indexAction(){
	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;
		 
		// Get Stockist sample csv file with header
		if(!empty($data['expheader']) && strtoupper(str_replace(' ','_',$data['expheader'])) == 'EXPORT_HEADER'){
			$this->ObjModel->ExportStockistHeader($data);
		}
		
		// Upload Stockist data file
		if(!empty($data['uploadfile']) && strtoupper(str_replace(' ','_',$data['uploadfile'])) == 'UPLOAD_NEW'){
		 	$this->ObjModel->UploadStockist($data); 
			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}
		
		// Get Stockist data with location data
		if(!empty($data['expdata']) && strtoupper(str_replace(' ','_',$data['expdata'])) == 'EXPORT_DATA'){
			$this->ObjModel->ExportStockistData($data);
		}
	   
	   	$this->view->postData = $data;
		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
	   	$this->view->stockists = $this->ObjModel->getStockistList($data);
	}
	
	public function addAction(){
		$formdata = $this->_request->getParams();//echo "<pre>";print_r($data);echo "</pre>";die;
		// Add New Stockist Details
		if($this->_request->isPost() && $formdata['addaction'] == 'Save'){
			$add = $this->ObjModel->saveDetail($formdata);		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}
		$this->view->postData = $formdata;
		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
	}
	
	public function editAction(){
		$formdata = $this->_request->getParams();//echo "<pre>";print_r($data);echo "</pre>";die;
		// Add New Stockist Details
		if($this->_request->isPost() && $formdata['addaction'] == 'Update'){
			$add = $this->ObjModel->updateDetail($formdata);
			if($add){
				$_SESSION[SUCCESS_MSG] = "Stockist detail has been updated !";
			}else{
				$_SESSION[ERROR_MSG] = "Stockist detail has not been updated. Try Again !";
			}		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}
		$this->view->postData = $formdata;
		$this->view->detail = $this->ObjModel->getStockistDetail($formdata);
		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
	}
}
?>