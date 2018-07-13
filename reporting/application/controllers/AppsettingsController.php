<?php
class AppsettingsController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		$this->session = Bootstrap::$registry->get('defaultNs');
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new Appsettings();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->view->filter = $this->_data;
	}
	
	public function appsettingsAction(){
	   $this->view->appusers = $this->ObjModel->getAppusers();
	   $this->view->getFilterdata = $this->ObjModel->getFilterdata();
	}
	public function appdownloadAction(){
	  $filepath = Bootstrap::$baseUrl.'public/DocumentDirectory/MobileReporting5.1.apk';
	  if ($fd = fopen ($filepath, "rb")) {
			$fsize = filesize($filepath);
			$path_parts = pathinfo($filepath);
			$ext = strtolower($path_parts["extension"]);
			header("Content-type: application/apk"); // add here more headers for diff. extensions
			header("Content-Disposition: attachment; filename=\"".$path_parts["basename"]."\""); // use 'attachment' to force a download
			header("Cache-Control: public");
			header("Content-Description: File Transfer");
			header("Content-Transfer-Encoding: binary");
			header("Content-length: $fsize");
			header("Cache-control: private"); //use this to open files directly
			ob_end_clean();
			while(!feof($fd)) {
				$buffer = fread($fd, 2048);
				echo $buffer;
			}
		}
	   fclose ($fd);
	   exit;
	  $this->_redirect('Dashboard');
	   
	}
	public function settingAction(){
	  if($this->_request->isPost()){
	      $this->ObjModel->UpdateSettings();
		  $_SESSION[SUCCESS_MSG] = 'Setting Updated Successfully!';
		  $this->_redirect('Appsettings/setting/user_id/'.$this->_data['user_id']);
	  }
	   $this->view->appusers = $this->ObjModel->getAppusers();
	}
}
?>
