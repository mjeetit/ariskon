<?php
class RequestmanagerController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		$this->session = Bootstrap::$registry->get('defaultNs');
                if(!isset($_SESSION['AdminLoginID']) && $this->_request->getActionName()!='acceptrejectbymail'){
                  $this->_redirect(Bootstrap::$baseUrl);
               }
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new RequestManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
   	
	public function leaverequestAction(){
   		$this->view->leavelists = $this->ObjModel->getLeaveLists(); 
   	}
	
	public function leavenotificationAction(){
   		$this->view->leavelists = $this->ObjModel->getLeaveNotificationLists(); 
   	}
	
	public function notificationreplyAction () {
		$data = $this->_request->getParams();
        //$typeID = Class_Encryption::decode($data['token']);
		$typeID = $data['token'];
		
		$this->view->notify = $this->ObjModel->getLeaveRequestDetail(array('typeID'=>$typeID));
			
	   	if($this->_request->isPost() && !empty($data['token'])){
	        $action =  $this->ObjModel->replyLeaveRequest();
		   	$this->_redirect($this->_request->getControllerName().'/leavenotification');
	   	}
	   
	   	$objform = new LeaveRequestForm();
		$this->view->replyForm = $objform->leavereply(array('ReqInfo'=>$this->view->notify));
	}
   	
	public function addleaveAction () {
		$leavetype  = $this->ObjModel->getLeaveType();
		$availLeave = $this->ObjModel->getRestLeaveOfUser();
	   	$objform = new LeaveRequestForm();
		$this->view->requestForm = $objform->leaverequest(array('LeaveTypes'=>$leavetype,'RestLeaves'=>$availLeave));
		
		if($this->_request->isPost()){
	       if ($objform->isValid($this->getRequest()->getPost())) {
				$action =  $this->ObjModel->addLeaveRequest();
		   		$this->_redirect($this->_request->getControllerName().'/leaverequest');
			} else {
				// redisplay the form with errors
				$this->view->messages = $objform->getMessages();
			}
	   	}
	}
	
	public function rejectrequestAction () {
		$this->_helper->layout->setLayout('popupmain');
		$data = $this->_request->getParams();
        //$typeID = Class_Encryption::decode($data['token']);
		$typeID = $data['token'];
		
		$this->view->notify = $this->ObjModel->getLeaveRequestDetail(array('typeID'=>$typeID));
			
	   	if($this->_request->isPost() && !empty($data['token'])){
	        $action =  $this->ObjModel->setStatus($data);
		   	$this->_redirect($this->_request->getControllerName().'/leavenotification');
	   	}
	   
	   	$objform = new LeaveRequestForm();
		$this->view->replyForm = $objform->leavermark(array('ReqInfo'=>$this->view->notify));
	}
	
	public function leavetypeAction () {
		$this->_helper->layout->setLayout('popupmain');
		$data = $this->_request->getParams();
        //$typeID = Class_Encryption::decode($data['token']);
		$typeID = $data['token'];
		
		$detail = $this->ObjModel->getLeaveRequestDetail(array('typeID'=>$typeID));
		$this->view->leavetype  = $this->ObjModel->getLeaveType(array('UserID'=>$detail['user_id']));
		$this->view->availLeave = $this->ObjModel->getRestLeaveOfUser(array('UserID'=>$detail['user_id']));
		$this->view->requesType = $this->ObjModel->getRequestedLeaveTypes(array('typeID'=>$typeID));//print_r($this->view->requesType);die;
		
	   	if($this->_request->isPost() && !empty($data['token'])){
	        $action =  $this->ObjModel->setStatus($data);
		   	$this->_redirect($this->_request->getControllerName().'/leavenotification');
	   	}
	}
	
	/**
	 * Function : setstatusAction()
	 * Set leave request status
	 **/
	public function setrequeststatusAction() {
		$data = $this->_request->getParams();
		print_r($this->ObjModel->setStatus($data));
		exit;
	}
   
   	public function loanrequestAction(){
      $this->view->loanrequest = $this->ObjModel->getLoanRequest(); 
   }
   
   	public function addleaverequastAction(){
     $objform = new LeaveRequestForm();
	 $this->view->requestForm = $objform->leaverequest();  
   }
  
	public function addloanrequestAction(){
     	if($this->_request->isPost()){
	     	$this->ObjModel->ApplyLoan(); 
		 	$this->_redirect('requestmanager/loanrequest');
	 	}
     	$objform = new LoanRequestForm();
	 	$this->view->requestForm = $objform->loanrequest();
   	}
   public function requestedAction(){
	     $this->view->leaverequested = $this->ObjModel->getRequestedLeaveDetail(); 
	}
	public function approveAction(){
	    if($this->_request->isPost()){
	     	$this->ObjModel->ApproveRejectLeave(); 
		 	$this->_redirect('Requestmanager/requested');
	 	}
		$leavetype  = $this->ObjModel->getLeaveType($this->_data);
		$availLeave = $this->ObjModel->getRestLeaveOfUser($this->_data);
		$requestdetail = $this->ObjModel->getRequestDetailOfUser();
	   	$objform = new LeaveRequestForm();
		$this->view->requestForm = $objform->leavereply(array('LeaveTypes'=>$leavetype,'RestLeaves'=>$availLeave,'RequestDetail'=>$requestdetail));  
	}
	public function acceptrejectbymailAction(){
	   $this->ObjModel->EmailApproveRejectLeave();
	}
}
?>
