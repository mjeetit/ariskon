<?php
class NotificationsController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 111;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new NotificationManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
   public function messagesAction(){
     $this->view->Messagelist = $this->ObjModel->getMessages();
   }
   public function addmessagesAction(){
     if($this->_request->isPost()){
	       $this->ObjModel->addMessage(); 
		   $this->_redirect('Notifications/messages');
	 }
     $obj = new NotificationForm();
	 $this->view->messageFrom = $obj->MessageForm();
   }
   public function eventAction(){
     $this->view->EventList = $this->ObjModel->getEvents();
   }
   public function addeventAction(){
      if($this->_request->isPost()){
	       $this->ObjModel->addEvent(); 
		   $this->_redirect('Notifications/event');
	 }
     $obj = new NotificationForm();
	 $this->view->eventFrom = $obj->EventForm();
   } 
   
  public function notificationlistAction(){
    $this->view->notlist = $this->ObjModel->getMessageByLocation();
  } 
}
?>
