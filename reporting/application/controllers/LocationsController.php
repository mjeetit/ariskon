<?php
class LocationsController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

		public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->ObjModel = new SettingManager();
		Bootstrap::$_parent = 2;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new SettingManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->view->level = trim($this->_data['level']);
		$this->view->type = trim($this->_data['type']);
		$this->view->back =  $this->ObjModel->BackAction();
	}
	
	public function masterdataAction(){
	 
	}
	public function companyAction(){
	    $this->view->company = $this->ObjModel->getCompany();
	}
	public function businesunitAction(){
	    $this->view->bussnessunit = $this->ObjModel->getBissnessUnit();
	}
	public function countryAction(){
	    $this->view->countries = $this->ObjModel->getCompanyCountry();
	}
	public function zoneAction(){
	    $this->view->zones = $this->ObjModel->getZone();
	}
	public function regionAction(){
	    $this->view->Region = $this->ObjModel->getRegion();
	}
	public function areaAction(){
	   $this->view->Area=$this->ObjModel->getArea();
	}
	public function headofficeAction(){
	   $this->view->headoffice = $this->ObjModel->getHeadOffice();
	}
	public function cityAction(){
	     $this->view->city = $this->ObjModel->getCity();
	}
	public function streetAction(){
	   $this->view->street = $this->ObjModel->getStreet();
	}

        public function patchcodeAction(){
	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;
		 
		// Get Patch sample csv file with header and location data
		if(!empty($data['expheader']) && strtoupper(str_replace(' ','_',$data['expheader'])) == 'EXPORT_HEADER'){
			$this->ObjModel->ExportPatchHeader($data);
		}
		
		// Upload Patch data file
		if(!empty($data['uploadpatch']) && strtoupper(str_replace(' ','_',$data['uploadpatch'])) == 'UPLOAD'){
		 	$this->ObjModel->UploadePatchFile($data); 
			$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
		}
		
		// Get Patch data with location data
		if(!empty($data['expdata']) && strtoupper(str_replace(' ','_',$data['expdata'])) == 'EXPORT_DATA'){
			$this->ObjModel->ExportPatchData($data);
		}
	   
	   	$this->view->postData = $data;
		$this->view->headquarters = $this->ObjModel->getHeadQuaters();
	   	$this->view->patch = $this->ObjModel->getPatch($data);
	}

	public function officeAction(){
	   //$this->view->office = $this->ObjModel->getStreet();
	}
	public function addofficeAction(){
	
	}
	
	public function addAction(){
	   if($this->_request->isPost()){
	       $action =  $this->ObjModel->AddMasterSetting();
		   $this->_redirect($this->_request->getControllerName().'/'. $action);
	   }
	}
	public function editAction(){
	     if($this->_request->isPost()){
	       $action =  $this->ObjModel->EditMasterSetting();
		   $this->_redirect($this->_request->getControllerName().'/'. $action);
	   }
	  //$this->view->back =  $this->ObjModel->BackAction();
	   $this->view->EditRec = $this->ObjModel->getEdit();//print_r($this->view->EditRec);die;
   }
   public function designationAction(){
      $this->view->designation = $this->ObjModel->getDesignation();
   }
   public function salaryheadAction(){
      $this->view->salary = $this->ObjModel->getSalaryhead();
	  $this->view->Detectsalaryhead = $this->ObjModel->getDetectionSalaryhead();
   }
   public function addnewAction(){
     if($this->_request->isPost() && !empty($this->_data['Designation'])){
	       $this->ObjModel->AddDesignation();
		   $this->_redirect($this->_request->getControllerName().'/designation');
	   }
	  if($this->_request->isPost() && !empty($this->_data['Department'])){
	       $this->ObjModel->AddDepartment();
		   $this->_redirect($this->_request->getControllerName().'/department');
	   }
	    if($this->_request->isPost() && !empty($this->_data['Salaryhead']) || !empty($this->_data['Detectsalaryhead'])){
	       $this->ObjModel->AddSalaryhead();
		   $this->_redirect($this->_request->getControllerName().'/salaryhead');
	   }
	  
	   $this->view->backNew =  $this->ObjModel->BackNewAction();
   }
   public function departmentAction(){
      $this->view->department = $this->ObjModel->getDepartment();
   }
   public function editnewAction(){
       if($this->_request->isPost() && !empty($this->_data['Designation'])){
	       $this->ObjModel->EditDesignation();
		   $this->_redirect($this->_request->getControllerName().'/designation');
	   }
	  if($this->_request->isPost() && !empty($this->_data['Department'])){
	       $this->ObjModel->EditDepartment();
		   $this->_redirect($this->_request->getControllerName().'/department');
	   }
	  if($this->_request->isPost() && !empty($this->_data['Salaryhead'])){
	       $this->ObjModel->EditSalaryhead();
		   $this->_redirect($this->_request->getControllerName().'/salaryhead');
	   }
	   if($this->_request->isPost() && !empty($this->_data['salarytemplate'])){ //print_r($this->_data);die;
	       $this->ObjModel->EditSalaryhead();
		   $this->_redirect($this->_request->getControllerName().'/salaryhead');
	   }
       $this->view->backNew =  $this->ObjModel->BackNewAction();
       $this->view->EditNewRec = $this->ObjModel->getEditNew();
   }
   public function salarytemplateAction(){
      $this->view->slarytemplate = $this->ObjModel->getTemplate();
   }
   public function addtemplateAction(){
       if($this->_request->isPost() && !empty($this->_data['add_template'])){ 
	       $last_id = $this->ObjModel->AddSalaryTemplate();
		   $this->view->templatehead = $this->ObjModel->getTemplatehead($last_id);
	   }
	    if($this->_request->isPost() && !empty($this->_data['add_amount'])){ 
	      $this->ObjModel->AddSalaryTemplateAmount();
		   $this->_redirect($this->_request->getControllerName().'/salarytemplate');
	   }
      //$this->view->templatehead = $this->ObjModel->getTemplatehead();
   }
   public function edittemplateAction(){
     if($this->_request->isPost() && !empty($this->_data['update_template'])){ 
	       $last_id = $this->ObjModel->editSalaryTemplate();
		    $this->ObjModel->editSalaryTemplateAmount();
			 $this->_redirect($this->_request->getControllerName().'/salarytemplate');
	   }
     $this->view->salaryTemplate =  $this->ObjModel->getTemplateRecordById();
	 $this->view->salaryTemplateAmount =  $this->ObjModel->getTemplateAmountById();
   }
   public function headquaterAction(){
     $this->view->headquater =  $this->ObjModel->getHeadQuaters();
   }
}
?>
