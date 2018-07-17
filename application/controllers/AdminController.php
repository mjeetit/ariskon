<?php
class AdminController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('login');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new Admin();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		/*
		if($_SERVER['SERVER_NAME']!='www.ariskon.jclifecare.com'){ 
		   $this->_redirect(Bootstrap::$baseUrl);  
		} */
	}
	
	public function indexAction(){
	  	
	  	if($this->_request->isPost()) {
            /*$auth = Zend_Auth::getInstance();
            $authAdapter = new Zend_Auth_Adapter_DbTable($this->ObjModel->getAdapter(),'users');
			
            $authAdapter->setIdentityColumn('username')
                    	->setCredentialColumn('password');
            $authAdapter->setIdentity($this->_data['username'])
                    	->setCredential(md5($this->_data['password']));
            $result = $auth->authenticate($authAdapter);*/

			$checklogin = $this->ObjModel->checkAuthentication($this->_data);
			
			if(!empty($checklogin) && $this->ObjModel->getStatusCheck($checklogin)){

				$this->ObjModel->setSession($checklogin);
				$this->_redirect(Bootstrap::$baseUrl.'Home');
			}
        }
	}
	public function logoutAction(){
	  $this->ObjModel->unsetSession();
	  $this->_redirect(Bootstrap::$baseUrl);
	}
	
}
?>
