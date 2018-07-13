<?php
class Crm_RoiController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init()
	{
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 18;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new RoiManager();
		$this->ObjAjax = new AjaxManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		$this->ObjModel->checkPrivileged();
	}
	
    public function indexAction()
	{
		$data = $this->_request->getParams();
		$this->view->Filterdata = $data;
		//$this->view->doctors = $this->ObjAjax->getDoctorLists();
		//$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
		//$this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		$this->view->doctors = $this->ObjAjax->getroidoctorlist();
	 	$this->view->headquarters = $this->ObjAjax->getroiheadquarterlists();
	 	$this->view->beDetails = $this->ObjAjax->getroibelists(array('designationID'=>'8','hq'=>$this->view->headquarters));
		 
		 $this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		 $this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		 $this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		 $rois = $this->ObjModel->getROIs($data); 
		 $this->view->rois = $rois['Records'];
		 //echo "<pre>";print_r($this->view->rois);echo "</pre>";die;
	}
	
	public function addAction()
	{
		$data = $this->_request->getParams();
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['roiADD'])) == 'ADD_ROI')
		{
			$roiData['doctor_id'] 		= (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
			$roiData['roi_month'] 		= (isset($data['month'])) ? trim($data['month']) : 0;
			$roiData['roi_total_amount']= (isset($data['tot_val'])) ? trim($data['tot_val']) : 0;
			$roiData['added_by'] 		= $_SESSION['AdminLoginID'];
		   	$roiData['added_ip'] 		= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($data);echo "</pre>";die;
		   	$addRoiMainID = $this->ObjModel->addProductData(array('tableName'=>'crm_roi','tableData'=>$roiData));
			if($addRoiMainID>0)
			{
				if(isset($data['token2']) && count($data['token2'])>0)
				{
					$roiDetailData['roi_id'] = $addRoiMainID;
					foreach($data['token2'] as $key=>$product)
					{
						if(isset($data['unit'][$key]) && $data['unit'][$key]>0)
						{
							$roiDetailData['product_id']= $product;//Class_Encryption::decode($product);
							$roiDetailData['unit'] 		= (isset($data['unit'][$key])) ? trim($data['unit'][$key]) : 0;
							$roiDetailData['value'] 	= (isset($data['value'][$key])) ? trim($data['value'][$key]) : 0;
							$this->ObjModel->addProductData(array('tableName'=>'crm_roi_details','tableData'=>$roiDetailData));
						}
					}
					$_SESSION[SUCCESS_MSG] = "ROI detail has been added!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
				else
				{
					$_SESSION[ERROR_MSG] = "Some problem in adding ROI detail, please try again!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
			}		     
			else
			{
		   		$_SESSION[ERROR_MSG] = "Some problem in adding ROI detail, please try again!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
	   	}
		
		$this->view->allProducts = $this->ObjAjax->getProductLists();
		$this->view->crmProducts = $this->ObjModel->getProductAgainstCRM(array('doctorID'=>Class_Encryption::decode($data['token'])));
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
	}
	
	public function editAction()
	{
		$data = $this->_request->getParams();
		$this->view->allProducts = $this->ObjAjax->getProductLists();
		$this->view->crmProducts = $this->ObjModel->getProductAgainstCRM(array('doctorID'=>Class_Encryption::decode($data['token'])));
		$this->view->productPrice = $this->ObjAjax->getAllProductPrice();
		$this->view->roiDetail = $this->ObjModel->getRoiDetail($data); //echo "<pre>";print_r($this->view->roiDetail);echo "</pre>";die;
		
		// Update and Approved ROI Data
		if($this->_request->isPost() && (str_replace(' ','_',strtoupper($data['approvalsame'])) == 'APPROVED_BE_DATA' || str_replace(' ','_',strtoupper($data['approvalown'])) == 'UPDATE_AND_APPROVED'))
		{
			$condition = (str_replace(' ','_',strtoupper($data['approvalsame'])) == 'APPROVED_BE_DATA') ? 'same_' : ((str_replace(' ','_',strtoupper($data['approvalown'])) == 'UPDATE_AND_APPROVED') ? 'own_' : '');
			if(!empty($condition))
			{
				$roiData['roi_id'] 			= (isset($data['roitoken'])) ? Class_Encryption::decode($data['roitoken']) : 0;
				$roiData['doctor_id'] 		= (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
				$roiData['roi_month'] 		= (isset($data['month'])) ? trim($data['month']) : 0;
				$roiData['roi_total_amount']= (isset($data[$condition.'tot_val'])) ? trim($data[$condition.'tot_val']) : 0;
				$roiData['added_by'] 		= $_SESSION['AdminLoginID'];
				$roiData['added_ip'] 		= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($data);echo "</pre>";die;
				$addRoiMainID = $this->ObjModel->addProductDataForEdit(array('tableName'=>'crm_roi_by_senior','tableData'=>$roiData));
				if($addRoiMainID)
				{
					if(isset($data[$condition.'token2']) && count($data[$condition.'token2'])>0)
					{
						$roiDetailData['roi_id'] = $roiData['roi_id'];
						$this->ObjModel->updateRecord('crm_roi',array('senior_approval'=>'1'),'roi_id='.$roiDetailData['roi_id']);
						
						foreach($data[$condition.'token2'] as $key=>$product)
						{
							if(isset($data[$condition.'unit'][$key]) && $data[$condition.'unit'][$key]>0)
							{
								$roiDetailData['product_id']= $product;//Class_Encryption::decode($product);
								$roiDetailData['unit'] 		= (isset($data[$condition.'unit'][$key])) ? trim($data[$condition.'unit'][$key]) : 0;
								$roiDetailData['value'] 	= (isset($data[$condition.'value'][$key])) ? trim($data[$condition.'value'][$key]) : 0;
								$this->ObjModel->addProductData(array('tableName'=>'crm_roi_details_by_senior','tableData'=>$roiDetailData));
							}
						}
						$_SESSION[SUCCESS_MSG] = "ROI detail has been approved!!";
						$this->_redirect($this->_request->getControllerName().'/index');
					}
					else
					{
						$_SESSION[ERROR_MSG] = "Some problem in approval of ROI detail, please try again!!";
						$this->_redirect($this->_request->getControllerName().'/index');
					}
				}		     
				else
				{
					$_SESSION[ERROR_MSG] = "Some problem in adding ROI detail, please try again!!";
					$this->_redirect($this->_request->getControllerName().'/index');
				}
			}
			else
			{
		   		$_SESSION[ERROR_MSG] = "Some problem, please try again!!";
				$this->_redirect($this->_request->getControllerName().'/index');
			}
	   	}
	}
}
?>