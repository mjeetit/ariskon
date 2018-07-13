<?php
class SettingController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 1;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new SettingManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->view->level = trim($this->_data['level']);
		 $this->view->type = trim($this->_data['type']);
		$this->view->back =  $this->ObjModel->BackAction();
	}
	
	public function masterdataAction(){
	 
	}
	public function companyAction(){
	    $this->view->company = $this->ObjModel->getCompany();
	}
	public function businesunitAction(){
	    $this->view->bussnessunit = $this->ObjModel->getBissnessUnit();
	}
	public function countryAction(){
	    $this->view->countries = $this->ObjModel->getCompanyCountry();
	}
	public function zoneAction(){
	    $this->view->zones = $this->ObjModel->getZone();
	}
	public function regionAction(){
	    $this->view->Region = $this->ObjModel->getRegion();
	}
	public function areaAction(){
	   $this->view->Area=$this->ObjModel->getArea();
	}
	public function headofficeAction(){
	   $this->view->headoffice = $this->ObjModel->getHeadOffice();
	}
	public function cityAction(){
	     $this->view->city = $this->ObjModel->getCity();
	}
	public function streetAction(){
	   $this->view->street = $this->ObjModel->getStreet();
	}
	
	public function addAction(){
	   if($this->_request->isPost()){
	       $action =  $this->ObjModel->AddMasterSetting();
		   $this->_redirect($this->_request->getControllerName().'/'. $action);
	   }
	    $this->view->ID = $this->_data['ID'];
	}
	public function editAction(){
	     if($this->_request->isPost()){
	       $action =  $this->ObjModel->EditMasterSetting();
		   $this->_redirect($this->_request->getControllerName().'/'. $action);
	   }
	  //$this->view->back =  $this->ObjModel->BackAction();
	   $this->view->EditRec = $this->ObjModel->getEdit();//print_r($this->view->EditRec);die;
   }
   public function designationAction(){
      $this->view->designation = $this->ObjModel->getDesignation();
   }
   public function salaryheadAction(){
      $this->view->salary = $this->ObjModel->getAllSalaryHead();
	  $this->view->Detectsalaryhead = $this->ObjModel->getDetectionSalaryhead();
   }
   public function addnewAction(){

   	/************************************************************************************
	 check for all field values at the time of adding designation to prevent from the 
	 blank designation adding by jm on 12072018
   	*************************************************************************************/
     if($this->_request->isPost() && !empty($this->_data['designation_name']) && !empty($this->_data['designation_code']) && !empty($this->_data['designation_level']) && !empty($this->_data['Designation']) ){

	       $this->ObjModel->AddDesignation();
		   $this->_redirect($this->_request->getControllerName().'/designation');
	   }

	  if($this->_request->isPost() && !empty($this->_data['Department'])){
	       $this->ObjModel->AddDepartment();
		   $this->_redirect($this->_request->getControllerName().'/department');
	   }
	    if($this->_request->isPost() && !empty($this->_data['Salaryhead']) || !empty($this->_data['Detectsalaryhead'])){
	       $this->ObjModel->AddSalaryhead();
		   $this->_redirect($this->_request->getControllerName().'/salaryhead');
	   }
	  
	   $this->view->backNew =  $this->ObjModel->BackNewAction();
   }
   public function departmentAction(){
      $this->view->department = $this->ObjModel->getDepartment();
   }
   public function editnewAction(){
       if($this->_request->isPost() && !empty($this->_data['Designation'])){
	       $this->ObjModel->EditDesignation();
		   $this->_redirect($this->_request->getControllerName().'/designation');
	   }
	  if($this->_request->isPost() && !empty($this->_data['Department'])){
	       $this->ObjModel->EditDepartment();
		   $this->_redirect($this->_request->getControllerName().'/department');
	   }
	  if($this->_request->isPost() && !empty($this->_data['Salaryhead'])){
	       $this->ObjModel->EditSalaryhead();
		   $this->_redirect($this->_request->getControllerName().'/salaryhead');
	   }
	   if($this->_request->isPost() && !empty($this->_data['salarytemplate'])){ print_r($this->_data);die;
	       $this->ObjModel->EditSalaryhead();
		   $this->_redirect($this->_request->getControllerName().'/salaryhead');
	   }
       $this->view->backNew =  $this->ObjModel->BackNewAction();
       $this->view->EditNewRec = $this->ObjModel->getEditNew();
   }
   public function salarytemplateAction(){
      $this->view->slarytemplate = $this->ObjModel->getTemplate();
   }
   public function addtemplateAction(){
       if($this->_request->isPost() && !empty($this->_data['add_template'])){ 
	       $last_id = $this->ObjModel->AddSalaryTemplate();
		   $this->view->templatehead = $this->ObjModel->getTemplatehead($last_id);
	   }
	    if($this->_request->isPost() && !empty($this->_data['add_amount'])){ 
	      $this->ObjModel->AddSalaryTemplateAmount();
		   $this->_redirect($this->_request->getControllerName().'/salarytemplate');
	   }
      //$this->view->templatehead = $this->ObjModel->getTemplatehead();
   }
   public function edittemplateAction(){
     if($this->_request->isPost() && !empty($this->_data['update_template'])){ 
	       $last_id = $this->ObjModel->editSalaryTemplate();
		    $this->ObjModel->editSalaryTemplateAmount();
			 $this->_redirect($this->_request->getControllerName().'/salarytemplate');
	   }
     $this->view->salaryTemplate =  $this->ObjModel->getTemplateRecordById();
	 $this->view->salaryTemplateAmount =  $this->ObjModel->getTemplateAmountById();
   }
   
   public function providentsettingAction(){
    if($this->_request->isPost()){
       $this->ObjModel->updateprovidentSettings();
	 }
	 $this->view->provident = $this->ObjModel->getMasterProvidentSetting(); 
   }
   
   public function processingdateAction(){
     if($this->_request->isPost()){
	    $this->ObjModel->UpdateSalaryDuration();
		$_SESSION[SUCCESS_MSG] = 'Duration Update Successfully';
		$this->_redirect('Setting/processingdate');
	 }
     $this->view->Duration = $this->ObjModel->getSalaryDuration();
   }
   public function expenseAction(){
        $grid = $this->grid();
        $select = $this->ObjModel->getExpenseSetting();
        $grid->query($select);
		$right = new Bvb_Grid_Extra_Column();
		$right->position('right')->name('Action')->title('Action')->decorator("<a href='".Bootstrap::$baseUrl."Setting/addexpsetting/Mode/Update/exp_setting_id/{{exp_setting_id}}'><img src='".Bootstrap::$baseUrl."public/admin_images/pencil.gif' /></a>");
		$grid->setRowAltClasses('odd','even');
		$grid->updateColumn('exp_setting_id',array('remove'=>true));
		$grid->setRecordsPerPage(100);
		$grid->addExtraColumns($right);
        $grid->setUseKeyEventsOnFilters(true);
		
        $this->view->pages = $grid->deploy();
        $this->render('expense');
   }
   
   public function addexpsettingAction(){
      if($this->_request->isPost()){
	    $this->ObjModel->AddUpdateExpenseSetting();
		$this->_redirect('Setting/expense');
	  }
	 $this->view->ExpSett = $this->ObjModel->getExpenseSettingById();
   }
   public function expenseheadAction(){
      $this->view->expensehead = $this->ObjModel->getExpenseHead();
   }
   public function addexpenseAction(){
     if($this->_request->isPost()){
	    $this->ObjModel->AddUpdateHeadExpense();
		$this->_redirect('Setting/expensehead');
	  }
	 if($this->_data['Mode']=='Edit'){
	    $headdata = $this->ObjModel->getExpenseHead();
	    $this->view->edithead = $headdata[0];  
	  }
     $this->view->mode = $this->_data['Mode'];
   }
   
    public function esisettingAction(){
      if($this->_request->isPost()){
	    $this->ObjModel->AddUpdateesiSetting();
	  }
	   $this->view->esisett = $this->ObjModel->EsiDetail();
   }
   
   public function holidayAction(){
       $this->view->hilidayslist = $this->ObjModel->getHolidays($this->_data);
   }
   public function addholidayAction(){
      if($this->_request->isPost() && $this->_data['addholiday']=='Add'){
	      //echo "<pre>";print_r($this->_data);die;
		  $this->ObjModel->SaveHoliday($this->_data);
		  $_SESSION[SUCCESS_MSG] = 'Hilidays Added Successfully';
		  $this->_redirect('Setting/holiday');
	   }
	   $this->view->hilidayslist = array();
	   if($this->_request->isPost() && $this->_data['submit']=='Add'){
	   	$holidays = $this->ObjModel->getHolidays($this->_data);
		foreach($holidays as $holiday){
		   $this->view->hilidayslist[$holiday['holiday_date']] =  $holiday;
		}
	   }
	   $this->view->Region = $this->ObjModel->getRegion();
       $this->view->submitdata = $this->_data;
   }
   /**
     * Simplify the datagrid creation process
     * @return Bvb_Grid_Deploy_Table
     */
    public function grid ($id = '')
    {
        $view = new Zend_View();
        $view->setEncoding('ISO-8859-1');
        $config = new Zend_Config_Ini('./application/grids/grid.ini', 'production');
        $grid = Bvb_Grid::factory('Table', $config, $id);
        $grid->setEscapeOutput(false);
        $grid->setExport(array( 'csv'));
        $grid->setView($view);


        #$grid->saveParamsInSession(true);
        #$grid->setCache(array('enable' => array('form'=>false,'db'=>false), 'instance' => Zend_Registry::get('cache'), 'tag' => 'grid'));
        return $grid;
    }
	
	public function filtersAction ()
    {

        $grid = $this->grid('ois');
		$select = $this->objModel->mygrid();
        $grid->setSource(new Bvb_Grid_Source_Zend_Select($select ));

        $filters = new Bvb_Grid_Filters();
        $filters->addFilter('Name', array('distinct' => array('field' => 'Name', 'name' => 'Name', 'order' => 'field desc')));
        $filters->addFilter('Continent', array('distinct' => array('field' => 'Continent', 'name' => 'Continent')));
        $filters->addFilter('LifeExpectancy', array('render' => 'number', 'distinct' => array('field' => 'LifeExpectancy', 'name' => 'LifeExpectancy')));
        $filters->addFilter('GovernmentForm', array('distinct' => array('field' => 'GovernmentForm', 'name' => 'GovernmentForm')));
        $filters->addFilter('HeadOfState');
        $filters->addFilter('Population', array('render' => 'number'));


        $grid->addFilters($filters);


        $grid->addExternalFilter('new_filter', 'people');
        $grid->addExternalFilter('filter_country', 'filterContinent');

        $this->view->pages = $grid->deploy();
        $this->render('zeroprice');
    }
   
}
function AddAutoCompleteToFields(Bvb_Grid_Event $event)
{

    $subject = $event->getSubject();
    $script = "$(document).ready(function() {";
    foreach ($subject->getVisibleFields() as $name) {
        $script .= "$(\"input#filter_$name\").autocomplete({focus: function(event, ui) "
                 . "{document.getElementById('filter_$name').value = ui.item.value },"
                 . " source: '{$subject->getAutoCompleteUrlForFilter($name)}'});\n";
    }
    $script .= "});";

    $subject->getView()->headScript()->appendScript($script);
}

function my_callback(Bvb_Grid_Event $event)
{
    /*
    $subject = $event->getSubject();

    $dateFieldName_to = $subject->getParam('dateFieldName_to');
    $subject->setParam('dateFieldName[to]',$dateFieldName_to);
    $subject->removeParam('dateFieldName_to');//This line is optional. You may need this value. I don't know you code.
*/
    #Others here....

}
?>
