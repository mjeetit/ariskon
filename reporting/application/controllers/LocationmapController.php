<?php
class LocationmapController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	    }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 3;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new DataSettingManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
	
	public function businesstocompanyAction(){
	     $this->view->bunittocompany = $this->ObjModel->getBusinessToCompany();
	}
	public function b2cAction(){
	   if($this->_request->isPost() && !empty($this->_data['b2c'])){
	       $this->ObjModel->Addb2c();
		   $this->_redirect($this->_request->getControllerName().'/businesstocompany');
	   }
	}
	public function departmenttobunitAction(){
	    $this->view->departmenttobunit = $this->ObjModel->getDepartmentToBusinessUnit();
	}
    public function d2bAction(){
	   if($this->_request->isPost()){
	       $this->ObjModel->Addd2b();
		   $this->_redirect($this->_request->getControllerName().'/departmenttobunit');
	   }
	}
	public function desigtodepartAction(){
	 $this->view->desigtodepart = $this->ObjModel->getDesignationToDepartment();
	}
	public function d2dAction(){
	   if($this->_request->isPost() && !empty($this->_data['d2d'])){
	       $this->ObjModel->Addd2d();
		   $this->_redirect($this->_request->getControllerName().'/desigtodepart');
	   }
	}
	public function businesstocountryAction(){
      $this->view->bunittocountry = $this->ObjModel->getBusinessToCountry();
	}
	public function bunittocountryAction(){
	   if($this->_request->isPost() && !empty($this->_data['bunittocountry'])){
	       $this->ObjModel->AddBunitToCountry();
		   $this->_redirect($this->_request->getControllerName().'/businesstocountry');
	   }
	}
	public function editlocationmapAction(){
	  if($this->_request->isPost()){
	       $return = $this->ObjModel->Editmaping();
		   $this->_redirect($this->_request->getControllerName().'/'.$return);
	   } 
	   $this->view->editdata  = $this->ObjModel->EditdataLocation();
	}	
}
?>
