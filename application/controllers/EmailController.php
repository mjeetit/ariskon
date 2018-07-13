<?php
class EmailController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
	    if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	    }
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new EmailManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
	}
	public function indexAction(){
	   $getemaildata = $this->ObjModel->getEmailLogin();
	   if($getemaildata['webemail']!='' && $getemaildata['webpass']!=''){
	    //if($this->ObjModel->getregionID()!=2 || ($_SESSION['AdminLoginID']==156 || $_SESSION['AdminLoginID']==224 || $_SESSION['AdminLoginID']==179 || $_SESSION['AdminLoginID']==167)){
		   header("Location: http://webmail1.jclifecare.com/jclhrm.php?webemail=".$getemaildata['webemail']."&&webpass=".base64_encode($getemaildata['webpass'])."");
			exit();
		// }
		}else{
		  
		}
	}
	public function erroremailAction(){
	    $_SESSION[ERROR_MSG] = 'Invalid email or password';
		//$this->_redirect(Bootstrap::$baseUrl.'dashboard');
	}
}
?>
