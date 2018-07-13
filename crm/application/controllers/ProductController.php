<?php
class ProductController extends Zend_Controller_Action {
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
		$this->ObjModel = new ProductManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		$this->ObjModel->checkPrivileged();
	}
	
    public function indexAction(){
	     $this->view->products = $this->ObjModel->getProducts();
	}
	
	public function addAction(){
		$data = $this->_request->getParams();
		// Add New Chemist Details
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['productAdd'])) == 'ADD_PRODUCT'){
			$filterFormData = $this->getfilterFormData(array('formData'=>$data)); //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   
		   	$filterFormData['productData']['created_by'] = $_SESSION['AdminLoginID'];
		   	$filterFormData['productData']['created_ip'] = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($filterFormData);echo "</pre>";die;
		   	$addChemistMain = $this->ObjModel->addProductData(array('tableName'=>'crm_products','tableData'=>$filterFormData['productData']));		     
		   	$this->_redirect($this->_request->getControllerName().'/index');
	   	}
		$this->view->types = $this->ObjModel->getMeasurements();
	}
	
	public function getfilterFormData($data=array()) {
		 $formData = (isset($data['formData']) && count($data['formData'])>0) ? $data['formData'] : array();
		 
		 $productData = array();
		 $productData['product_code'] 		= (isset($formData['product_code'])) ? trim($formData['product_code']) : $this->ObjModel->makeProductCode();
		 $productData['product_name'] 		= (isset($formData['product_name'])) ? trim($formData['product_name']) : '';
		 $productData['product_desc']		= (isset($formData['product_desc'])) ? trim($formData['product_desc']) : '';
		 $productData['pack_type'] 			= (isset($formData['pack_type'])) 		? trim($formData['pack_type']) : '';
		 $productData['mrp_incl_vat'] 		= (isset($formData['mrp_incl_vat'])) 		? trim($formData['mrp_incl_vat']) : '';
		 $productData['stockist_excl_vat']	= (isset($formData['stockist_excl_vat'])) 		? trim($formData['stockist_excl_vat']) : '';
		 $productData['retailer_excl_vat'] 	= (isset($formData['retailer_excl_vat'])) 		? trim($formData['retailer_excl_vat']) : '';
		 $productData['vat_charged'] 		= (isset($formData['vat_charged'])) 		? trim($formData['vat_charged']) : '';
		 
		 return array('productData'=>$productData);
	}
}
?>