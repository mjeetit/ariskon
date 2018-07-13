<?php
	class SecondarySaleController extends Zend_Controller_Action 
	{
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

			$this->ObjModel = new SecondarySaleManager();
			
			$this->ObjAjax = new AjaxManager();

			$this->ObjModel->_getData = $this->_data;

			$this->view->ObjModel = $this->ObjModel;
		}
		public function indexAction(){
			$data = $this->_request->getParams();
			$this->view->Filterdata = $data;
			
			if(isset($data['Export'])){
				$this->ObjModel->ExportSales($data);
			}
			$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
			
			$this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
			
			$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
			
			$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
			
			$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
			
			$this->view->sales = $this->ObjModel->saleslist($data);
			
		}
		public function addAction(){
			if($_SESSION['AdminLevelID'] != 1){
				$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
			}
			if ($_SESSION['AdminLevelID'] == 1) {
				$this->view->users=$this->ObjModel->getUsers();
			}
			
			if($this->_request->isPost()){ 
				
				$data=$this->_request->getParams();
				$data['date']=date("Y-m-d H:i:s",time());
				$data['headquater_id']=Class_Encryption::decode($data['headquater_id']);
				
				if ($_SESSION['AdminLevelID'] == 1) {
				$data['user_id']=Class_Encryption::decode($data['user_id']);
				}
				
				//Intitializing the the BE,ABM and RBM user id
				if($_SESSION['AdminDesignation']==8 OR $_SESSION['AdminDesignation']==7 OR $_SESSION['AdminDesignation']==6){
				$data['user_id']=$_SESSION['AdminLoginID'];}
				
				//Finding the heaquarter of BE
				if($_SESSION['AdminDesignation'] == 8){
				
					$id=$this->ObjModel->HeaquarterById(array("id"=>$_SESSION['AdminLoginID']));
					$data['headquater_id']=$id[0]['headquater_id'];
					
				}
				
				//--------------------Validating the all fields-----------//
				if(empty($data['user_id'])){
					
					$_SESSION[ERROR_MSG] = "Please Select a <b>user</b> to add Secondary Sale!";
					$this->_redirect($this->_request->getControllerName().'/add/');
				}
				elseif(empty($data['headquater_id'])){
					
					$_SESSION[ERROR_MSG] = "Please Select a <b>headquarter</b> to add Secondary Sale!";
					$this->_redirect($this->_request->getControllerName().'/add/');
				}
				elseif(empty($data['from'])){
					
					$_SESSION[ERROR_MSG] = "Please Select <b>starting date</b> of Secondary Sale!";
					$this->_redirect($this->_request->getControllerName().'/add/');
				}
				elseif(empty($data['to'])){
					
					$_SESSION[ERROR_MSG] = "Please Select <b>end date</b> Secondary Sale!";
					$this->_redirect($this->_request->getControllerName().'/add/');
				}
				elseif(empty($data['amount'])){
				
					$_SESSION[ERROR_MSG] = "Please enter some <b>amount</b> of Secondary Sale!";
					$this->_redirect($this->_request->getControllerName().'/add/');
				}//End of Field Validation
				else{
					
					//----------------------Validating date-------------------//
					$data['from']=date('Y-m-d',strtotime($data['from']));
					if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$data['from'], $matches)) 
					{
						if(!checkdate($matches[2], $matches[3], $matches[1]))
						{ 
							$_SESSION[ERROR_MSG] = "Please enter a valid <b>Starting date</b> in YYYY-mm-dd format for Secondary Sale!";
							$this->_redirect($this->_request->getControllerName().'/add/');
						}
					}
					$data['to']=date('Y-m-d',strtotime($data['to']));
					if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$data['to'], $matches)) 
					{
						if(!checkdate($matches[2], $matches[3], $matches[1]))
						{ 
							$_SESSION[ERROR_MSG] = "Please enter a valid <b>End date</b> in YYYY-mm-dd format for Secondary Sale!";
							$this->_redirect($this->_request->getControllerName().'/add/');
						}
					}
					
					//---------------End of Date Validation--------------//
					
					$addSecndSale=$this->ObjModel->AddSecondarySale($data);
				
					//Redirecting and displaying response message
					if($addSecndSale) {
						$_SESSION[SUCCESS_MSG] = "Secondary Sale has been Added successfully!";
						$this->_redirect($this->_request->getControllerName().'/index');
					}
					else {
						$_SESSION[ERROR_MSG] = "Some problem occur while adding new Sales!";
						$this->_redirect($this->_request->getControllerName().'/add/');
					}
				}
			}
		}
		public function approveAction(){
			$data=$this->_request->getParams();
			if($this->ObjModel->approvesales($data)){
				
				$_SESSION[SUCCESS_MSG] = "Sale Approved successfully!";
				$this->_redirect($this->_request->getControllerName().'/index/');
				
			}else{
				
				$_SESSION[ERROR_MSG] = "Some problem occur while approving Sale!";
				$this->_redirect($this->_request->getControllerName().'/index/');
				
			}		
		}
		
		public function deleteAction(){
			$data=$this->_request->getParams();
			$data['date']=date("Y-m-d H:i:s",time());
			if($this->ObjModel->deleteSales($data)){
				
				$_SESSION[SUCCESS_MSG] = "Sale Deleted successfully!!";
				$this->_redirect($this->_request->getControllerName().'/index/');
				
			}else{
			
				$_SESSION[ERROR_MSG] = "Some problem occur while deleting Sale!";
				$this->_redirect($this->_request->getControllerName().'/index/');
				
			}		
		}
		public function excelAction(){
		}
	}
?>