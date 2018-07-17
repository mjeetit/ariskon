<?php
class ReportingController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
	    //if(!isset($_SESSION['AdminLoginID'])){
	    //  $this->_redirect(Bootstrap::$baseUrl);
	    //}
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new ReportingManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
	public function doctorvisitAction(){
	   $this->view->vistdetail = $this->ObjModel->getDoctorVisist();
	   /*********************************************************************************
	     function name modify on the basis of main menu either HRM, CRM or Reporting 
	     by jm on 16072018
	   *********************************************************************************/
	   //$this->view->allusers = $this->ObjModel->getAllUsersForSalary();
	   $this->view->allusers = $this->ObjModel->getAllUsersForSalaryHRM();
	   $this->view->headquater = $this->ObjModel->getHeadquater();
	}
	public function chemistvisitAction(){
	   $this->view->vistdetail = $this->ObjModel->getChemistVisist();
	}
	public function stockist1Action(){
	   $this->view->vistdetail = $this->ObjModel->getDoctorVisist();
	}
	public function stockistvisitAction(){
	  $this->view->vistdetail = $this->ObjModel->getStockistVisist();
	}
	public function repordetailAction(){
	   $this->view->vistdetail = $this->ObjModel->getDoctorVisistDetail($this->_data);
	   //$this->view->patchlists = $this->ObjModel->getPatchList();
    }
	
	public function giftAction()
	{
	  $this->view->tableDatas = $this->ObjModel->getTableData(array('tableName'=>'app_gifts','tableColumn'=>array(),'columnName'=>'isActive','columnValue'=>'1','returnRow'=>'all'));	
	}
	
	public function addgiftAction()
	{
		$data = $this->_request->getParams();
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SAVE'){
			$tableData = $data['table'];
		   	$tableData['rest_quantity'] = $data['table']['quantity'];
			$tableData['created_by'] = $_SESSION['AdminLoginID'];
		   	$tableData['created_ip'] = $_SERVER['REMOTE_ADDR'];
		   	if($this->ObjModel->saveData(array('tableName'=>'app_gifts','tableData'=>$tableData))>0) {
				$_SESSION[SUCCESS_MSG] = "Gift added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/gift');
			}
			else {
				$_SESSION[ERROR_MSG] = "Gift not added successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName());
			}
	   	}
		$this->view->postData = $data['table'];
	}
	
	public function assigngiftAction()
	{
		$data = $this->_request->getParams();
		$this->view->postData = $data['table'];
		$this->view->gift = $this->ObjModel->getTableData(array('tableName'=>'app_gifts','tableColumn'=>array(),'columnName'=>'gift_id','columnValue'=>$data['token']));
		$this->view->beDetails = $this->ObjModel->getDesignationWiseUserLists(array('designationID'=>'8'));
		
		if($this->_request->isPost() && str_replace(' ','_',strtoupper($data['addnewdata'])) == 'SAVE'){
			$tableData = $data['table'];
		   	$tableData['valid_from']  = $this->view->gift['valid_from'];
			$tableData['valid_to'] 	  = $this->view->gift['valid_to'];
			$tableData['assigned_by'] = $_SESSION['AdminLoginID'];
		   	$tableData['assigned_ip'] = $_SERVER['REMOTE_ADDR'];
		   	if($this->ObjModel->saveData(array('tableName'=>'app_gift_assigned','tableData'=>$tableData))>0) {
				$giftData['rest_quantity'] = ($this->view->gift['rest_quantity']-$tableData['assigned_quantity']);
				$giftData['isModify']  	= '1';
				$giftData['modify_by']  = $_SESSION['AdminLoginID'];
				$giftData['modify_date']= date('Y-m-d H:i:s');
				$giftData['modify_ip'] 	= $_SERVER['REMOTE_ADDR'];
				if($this->ObjModel->updateTableData(array('tableName'=>'app_gifts','tableData'=>$giftData,'whereColumn'=>'gift_id='.$data['token']))) {
					$_SESSION[SUCCESS_MSG] = "Gift assigned successfully!!";
					$this->_redirect($this->getRequest()->getControllerName().'/gift');
				}
				else {
					$_SESSION[ERROR_MSG] = "Gift quantity not updated!!";
					$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName().'/'.$data['token']);
				}
			}
			else {
				$_SESSION[ERROR_MSG] = "Gift not assigned successfully!!";
				$this->_redirect($this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName().'/'.$data['token']);
			}
	   	}
	}
	
	public function assignedgiftAction()
	{
		$data = $this->_request->getParams();
	  	$this->view->tableDatas = $this->ObjModel->getAssignGift(array('giftToken'=>$data['token']));	
	}
}
?>
