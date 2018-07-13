<?php
class TourplanController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
	    if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		/*Bootstrap::$_parent = 106;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();*/
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new Tourplan();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->ObjAjax  = new AjaxManager();
		$this->view->filter = $this->_data;
	}
    public function currenttourplanAction(){
	  if($this->_data['Export']=='Export'){
	     $this->ObjModel->ExportTourPlan(true);
	  }
	  $this->view->allusers = $this->ObjModel->getChildIncludeLogin();
	  $this->view->headquater = $this->ObjAjax->getHeadquarterLists();
	  $this->view->vistdetail = $this->ObjModel->getTourPlans();
	}
	
	public function nexttourplanAction(){
	   if($this->_data['Export']=='Export'){
	     $this->ObjModel->ExportTourPlan();
	  }
	   $this->view->allusers = $this->ObjModel->getChildIncludeLogin();
	   $this->view->headquater = $this->ObjAjax->getHeadquarterLists();
	   $this->view->vistdetail = $this->ObjModel->getNextTourPlans();
	}
	public function currenttourdetilAction(){
		if($this->_data['Export']=='Export'){
	     $this->ObjModel->exportcurrenttourdetil();
	  }
	   if(isset($this->_data['Approved']) && count($this->_data['approval_id'])>0){
	     $this->ObjModel->ApprovedTourPlan();
	  }elseif(isset($this->_data['Approved']) && count($this->_data['approval_id'])<=0){
	  
	  }
	  $this->view->vistdetail = $this->ObjModel->getTourDetail();
	}
	public function nexttourdetailAction(){
		//print_r($this->_data);die;
		if($this->_data['Export']=='Export'){
	     $this->ObjModel->exportnexttourdetil();
	  }
	  if(isset($this->_data['Approved']) && count($this->_data['approval_id'])>0){
	     $this->ObjModel->ApprovedTourPlan();
	  }elseif(isset($this->_data['Approved']) && count($this->_data['approval_id'])<=0){
	  
	  }
	   $this->view->vistdetail = $this->ObjModel->getNextTourPlansDetail();
	}
	public function rejecttpAction(){
	   $this->view->vistdetail = $this->ObjModel->RejectTP();
	}
	public function tpvsactualAction(){
     if($this->_data['Export']=='Export' && ($this->_data['zbm_id']!='' || $this->_data['rbm_id']!='' || $this->_data['abm_id']!='' || $this->_data['be_id']!='')){
		$this->ObjModel->ExporttpvsActual();
		$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}elseif($this->_data['Export']=='Export' && ($this->_data['zbm_id']=='' && $this->_data['rbm_id']=='' && $this->_data['abm_id']=='' && $this->_data['be_id']=='')){
		$_SESSION[ERROR_MSG] =  'Please select any employee(BE,ABM or RBM) to export this report !!';
		$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}
		$this->view->beDetails  = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8','parent_id'=>$this->_data['abm_id']));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7','parent_id'=>$this->_data['rbm_id']));
		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6','parent_id'=>$this->_data['zbm_id']));
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		$this->view->headquater = $this->ObjAjax->getHeadquarterLists();
		$this->view->getmonths = $this->ObjModel->getlastsixmonth();
		$this->view->vistdetail = $this->ObjModel->gettpvsActual();
}
	public function tpsummaryAction(){
	  if($this->_data['Export']=='Export'){
			$this->ObjModel->Exporttpsummary();
			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}
		$this->view->beDetails  = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8','parent_id'=>$this->_data['abm_id']));
		$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7','parent_id'=>$this->_data['rbm_id']));
		$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6','parent_id'=>$this->_data['zbm_id']));
		$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		$this->view->headquater = $this->ObjAjax->getHeadquarterLists();
		$this->view->getmonths = $this->ObjModel->getlastsixmonth();
		$this->view->vistdetail = $this->ObjModel->gettpsummary();
	}
}
?>
