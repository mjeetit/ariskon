<?php
    /**
     * Controll the settind module
     * @Auth : JC Lifecare Pvt. Ltd.
     * @Created Date: 09-July-2014
     * @Description : Controll the functionality related to settings manager
     **/
    class Crm_GraphreportController extends Zend_Controller_Action
    {
        /**
         * $ObjModel varible is object of settingsmanager module
         **/	
        public $graphData = array();
        
        /**
         * Auto load NameSpace and create objects 
         * Function : init()
         * Auto call and loads the default namespace and create object of model and form
         **/
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
			$this->ObjModel = new Graphreport();
			$this->ObjAjax = new AjaxManager();
			$this->ObjModel->_getData = $this->_data;
			$this->view->ObjModel = $this->ObjModel;
			
			// Get and check access of module privilege
			Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
			$this->ObjModel->checkPrivileged();
			
			$data = $this->_request->getParams();
			$this->graphData = $this->ObjModel->getROIData($data);
        }
        
        /**
         * Call Routing Settings  Page 
         * Function : rotingpropertiesAction()
         * View the General settings page
         **/
        public function indexAction() {
            $data = $this->_request->getParams();
			//$this->view->doctors = $this->ObjAjax->getDoctorLists();
			//$this->view->headquarters = $this->ObjAjax->getHeadquarterLists();
			//$this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
			$this->view->doctors = $this->ObjAjax->getroidoctorlist();
		 	$this->view->headquarters = $this->ObjAjax->getroiheadquarterlists();
		 	$this->view->beDetails = $this->ObjAjax->getroibelists(array('designationID'=>'8','hq'=>$this->view->headquarters));
			
			$this->view->abmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
			$this->view->rbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
			$this->view->zbmDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
			$this->view->viewpostaction = $data;
		}
		
		/**
         * $ObjModel varible is object of settingsmanager module
         **/
		public function getlistAction() {
			$data = $this->_request->getParams();;
			$AllUser = array();
			$zbmID = (isset($data['token']) && $data['token'] != 0) ? Class_Encryption::decode($data['token']) : 0;
			$AllUser[] = $zbmID;
			
			// Get All RBM Details
			$AllRBM  = array();
			$rbmCondition = ($zbmID>0) ? array('parent_id'=>$zbmID,'designation_id'=>6,'user_status'=>1,'delete_status'=>0) : array('designation_id'=>6,'user_status'=>1,'delete_status'=>0);
			$rbms = $this->ObjModel->getTableData(array('tableName'=>'employee_personaldetail','tableColumn'=>array('user_id','name'=>"CONCAT(employee_code,' - ',first_name)"),'columnCondition'=>$rbmCondition,'returnRow'=>'all'));
			if(count($rbms)>0) {
				foreach($rbms as $rbm) {
					$AllRBM[$rbm['user_id']] = trim($rbm['name']);
					$AllUser[] = $rbm['user_id'];
				}
			}
			asort($AllRBM);
			$responseArray['levelrbm'] = $AllRBM; //print_r($AllUser);die;
			
			// Get All ABM Details
			$AllABM  = array();
			$abmCondition = ($zbmID>0) ? array('parent_id'=>$AllUser,'designation_id'=>7,'user_status'=>1,'delete_status'=>0) : array('designation_id'=>7,'user_status'=>1,'delete_status'=>0);
			$conditionOpr = array('parent_id'=>'IN');
			$rbms = $this->ObjModel->getTableData(array('tableName'=>'employee_personaldetail','tableColumn'=>array('user_id','name'=>"CONCAT(employee_code,' - ',first_name)"),'columnCondition'=>$abmCondition,'conditionOpr'=>$conditionOpr,'returnRow'=>'all'));
			if(count($rbms)>0) {
				foreach($rbms as $rbm) {
					$AllABM[$rbm['user_id']] = trim($rbm['name']);
					$AllUser[] = $rbm['user_id'];
				}
			}
			asort($AllABM);
			$responseArray['levelabm'] = $AllABM; //print_r(json_encode($responseArray));die;
			
			// Get All BE Details
			$AllBE  = array();
			$beCondition = ($zbmID>0) ? array('parent_id'=>$AllUser,'designation_id'=>8,'user_status'=>1,'delete_status'=>0) : array('designation_id'=>8,'user_status'=>1,'delete_status'=>0);
			$conditionOprBE = array('parent_id'=>'IN');
			$bes = $this->ObjModel->getTableData(array('tableName'=>'employee_personaldetail','tableColumn'=>array('user_id','name'=>"CONCAT(employee_code,' - ',first_name)"),'columnCondition'=>$beCondition,'conditionOpr'=>$conditionOprBE,'returnRow'=>'all'));
			if(count($bes)>0) {
				foreach($bes as $be) {
					$AllBE[$be['user_id']] = trim($be['name']);
					$AllUser[] = $be['user_id'];
				}
			}
			asort($AllBE);
			$responseArray['levelbe'] = $AllBE; //print_r(json_encode($responseArray));die;			
			
			header('Content-Type: application/x-json; charset=utf-8');
			print_r(json_encode($responseArray));exit;
		}
		
		/**
         * $ObjModel varible is object of settingsmanager module
         **/
		public function graphAction() {
            $graph = new Zend_PHPGraphLib_PHPGraphLib(1000,400);
			foreach($this->graphData as $data) {
				$graph->addData($data);
			}
			$graph->setBarColor('blue', 'green','yellow','red');
			$graph->setTitle('CRM vs ROI Graph');
			$graph->setupYAxis(12, 'blue');
			$graph->setupXAxis(20);
			$graph->setGrid(false);
			$graph->setLegend(true);
			$graph->setTitleLocation('left');
			$graph->setTitleColor('blue');
			$graph->setLegendOutlineColor('white');
			$graph->setLegendTitle('CRM Amount','ROI Amount');
			$graph->setXValuesHorizontal(false);
			echo $graph->createGraph();die;
		}
    }
?>