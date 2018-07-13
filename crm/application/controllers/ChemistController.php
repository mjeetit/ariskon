<?php
class ChemistController extends Zend_Controller_Action {
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
		$this->ObjModel = new ChemistManager();
		$this->ObjAjax  = new AjaxManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		$this->ObjModel->checkPrivileged();
	}
	
    public function indexAction(){
	     $data = $this->_request->getParams();
		 
		 $this->view->Filterdata = $data;
		 $this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
		 
		 $this->view->chemists = $this->ObjModel->getChemists($data);
	}
	
	public function addAction(){
		$data = $this->_request->getParams();
		// Add New Chemist Details
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['chemistAdd'])) == 'ADD_CHEMIST'){
			$filterFormData = $this->getfilterFormData(array('formData'=>$data)); //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   
		   	$filterFormData['chemistData']['created_by'] = $_SESSION['AdminLoginID'];
		   	$filterFormData['chemistData']['created_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   	$addChemistMain = $this->ObjModel->addChemistData(array('tableName'=>'crm_chemists','tableData'=>$filterFormData['chemistData']));
		   
		   	if($addChemistMain > 0) {
		   		if(isset($data['stockists']) && count($data['stockists']) > 0) {
					foreach($data['stockists'] as $stockist) {
						if(trim($stockist) != '') {
							$this->ObjModel->addChemistData(array('tableName'=>'crm_chemist_stockists','tableData'=>array('chemist_id'=>$addChemistMain,'stockist_name'=>$stockist)));
						}
					}
				}			
		   	}
		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}
		$this->view->streets = $this->ObjModel->getStreetCodes();
	}
	
	public function editAction(){
	   	$data = $this->_request->getParams(); //echo "<pre>";print_r($data);echo "</pre>";die;
		 
		// Get Patch data with location data
		if(!empty($data['chemistUpd']) && strtoupper(str_replace(' ','_',$data['chemistUpd'])) == 'UPDATE'){
			$filterFormData = $this->getfilterFormData(array('formData'=>$data));
			$filterFormData['chemistData']['isModify'] = '1';
			$filterFormData['chemistData']['modify_by'] = $_SESSION['AdminLoginID'];
			$filterFormData['chemistData']['modify_date'] = date('Y-m-d H:i:s');
		   	$filterFormData['chemistData']['modify_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r(array_filter($filterFormData['chemistData']));echo "</pre>";die;
			
			if($this->ObjModel->addChemistData(array('tableName'=>'crm_chemists','tableData'=>$filterFormData['chemistData'],'whereColumn'=>"chemist_id='".$data['token']."'"))) {
				$stockists = array_filter($data['stockists']);
				if(count($stockists)>0) {
					$this->ObjModel->deleteFromTable(array('tableName'=>'crm_chemist_stockists','whereColumn'=>"chemist_id='".$data['token']."'"));
					foreach($stockists as $stockist) {
						if(trim($stockist) != '') {
							$this->ObjModel->addChemistData(array('tableName'=>'crm_chemist_stockists','tableData'=>array('chemist_id'=>$data['token'],'stockist_name'=>$stockist)));
						}
					}
				}
				$_SESSION[SUCCESS_MSG] = "Chemist detail has updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->_request->getActionName().'/token/'.$data['token']);
			}
			else {
				$_SESSION[ERROR_MSG] = "Some problem found on update chemist detail, please try again!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->_request->getActionName().'/token/'.$data['token']);
			}
		}
	   
	   	$this->view->postData = $data;
		$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
		$this->view->chemist = $this->ObjModel->getChemists($data);
		$this->view->stokist = $this->ObjModel->getLocationData(array('tableName'=>'crm_chemist_stockists','tableColumn'=>array('stockist_name'),'columnName'=>'chemist_id','columnValue'=>$this->view->chemist[0]['chemist_id'])); //echo "<pre>";print_r($this->view->stokist);echo "</pre>";die;
	}
	
	public function getfilterFormData($data=array()) {
		 $formData = (isset($data['formData']) && count($data['formData'])>0) ? $data['formData'] : array();
		 
		 $chemistData = array();
		 $chemistData['legacy_code'] 	= (isset($formData['legacy_code'])) ? trim($formData['legacy_code']) : '';
		 $chemistData['chemist_name'] 	= (isset($formData['chemist_name'])) ? trim($formData['chemist_name']) : '';
		 $chemistData['contact_person']	= (isset($formData['contact_person'])) ? trim($formData['contact_person']) : '';
		 $chemistData['class'] 			= (isset($formData['class'])) 		? trim($formData['class']) : '';
		 $chemistData['email'] 			= (isset($formData['email'])) 		? trim($formData['email']) : '';
		 $chemistData['phone'] 			= (isset($formData['phone'])) 		? trim($formData['phone']) : '';
		 $chemistData['mobile'] 		= (isset($formData['mobile'])) 		? trim($formData['mobile']) : '';
		 $chemistData['address1'] 		= (isset($formData['address1'])) 	? trim($formData['address1']) : '';
		 $chemistData['address2'] 		= (isset($formData['address2'])) 	? trim($formData['address2']) : '';
		 $chemistData['postcode'] 		= (isset($formData['postcode'])) 	? trim($formData['postcode']) : '';	
		 
		 $locationInfo = $this->ObjAjax->getlocation(array('streetID'=>$formData['patchtoken']));
		 $chemistData['patch_id'] 		= $formData['patchtoken'];
		 $chemistData['city_id'] 		= $locationInfo['city_id'];
		 $chemistData['headquater_id'] 	= $locationInfo['headquater_id'];
		 $chemistData['area_id'] 		= $locationInfo['area_id'];
		 $chemistData['region_id'] 		= $locationInfo['region_id'];
		 $chemistData['zone_id'] 		= $locationInfo['zone_id'];
		 $chemistData['country_id']		= (isset($locationInfo['country_id'])) ? $locationInfo['bunit_id'] : '27';
		 $chemistData['bunit_id'] 		= $locationInfo['bunit_id'];
		 
		 return array('chemistData'=>$chemistData);
	}
}
?>