<?php
class ExpenseController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 138;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName  = $this->_request->getActionName();
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new ExpensManager();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
                $this->view->filter = $this->_data;
	}
	public function expenselistAction(){
	    $this->view->expenselist = $this->ObjModel->getExpenseList();
	}
	
	public function addexpenseAction(){
	   if($this->_request->isPost()){
	      $this->ObjModel->addExpense();
	   }
	   $this->view->userinfo = $this->ObjModel->logedInuserInfo();
	   $this->view->expensehead = $this->ObjModel->userExpenseHead();
	   $this->view->currentexpense =  $this->ObjModel->getCurrentExpenseList();
	   $this->view->filterHead = $this->ObjModel->getUserExpenseHead();
	}
	public function approveAction(){
	   
	   if($this->_request->isPost()){
	     $this->ObjModel->ApproveExpense();
		 $this->_redirect("Expense/expenserequestlist");
	   }
	    $this->view->getList = $this->ObjModel->getCurrentExpenseForApproval();
	    
	    $empData = $this->ObjModel->getEmpDetail(array('user_id'=>$this->_data['user_id']));
	    $this->view->empInfo = $empData[0];
	    //echo "<pre>";print_r($this->view->empInfo);die;
	}
	public function  expenserequestlistAction(){
		if($this->_request->isGet() && $this->_data['export_expense']=='Export Approved Expense'){
		   $this->ObjModel->ExportExpenseApproved();
		}
		$this->view->expenserequest = $this->ObjModel->ExpenseRequestList();
		$this->view->expenseusers = $this->ObjModel->getExpenseUsersListForFilter();
	}
	public function viewexpenseAction(){
	   $this->view->userinfo = $this->ObjModel->logedInuserInfo();
	   $this->view->expensehead = $this->ObjModel->userExpenseHead();
	   $this->view->currentexpense =  $this->ObjModel->getCurrentExpenseList();
	   $this->view->filterHead = $this->ObjModel->getUserExpenseHead();
	}
	public function manualexpenseAction(){
	    if($this->_request->isPost()){
	      $this->ObjModel->addExpense();
	   }
	   $this->view->userslist = $this->ObjModel->usersList();
	   $this->view->expensehead = $this->ObjModel->getAllExpenseHead();
	   
	   $this->view->filterHead = $this->ObjModel->getUserExpenseHead();
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
       // $grid->setExport(array( 'xml','csv','excel','pdf'));
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
	
	public function getexpenselistAction(){
	    if($this->_request->isPost() && count($this->_data['user_id'])>0){
		   $this->ObjModel->UpdateSalaryExpense();
		 }
	     $this->view->salaryexpense = $this->ObjModel->getSalariedExpense();
	}
	public function allexpenseAction(){
		if($this->_request->isGet() && $this->_data['export_expense']=='Export Approved Expense'){
		   $this->ObjModel->ExportExpenseApproved();
		}
		$this->view->expenserequest = $this->ObjModel->AllExpenseReques();
		$this->view->expenseusers = $this->ObjModel->getExpenseUsersListForFilter();
	}
   	public function exallexpenseAction(){
		if($this->_request->isGet() && $this->_data['export_expense']=='Export Approved Expense'){
			$this->ObjModel->ExportExExpenseApproved();
		}
		$this->view->expenserequest = $this->ObjModel->ExAllExpenseReques();
		$this->view->expenseusers = $this->ObjModel->ExgetExpenseUsersListForFilter();
	}
	public function deleteAction(){ 
		if($this->ObjModel->deleteExpense($this->_data)){
			$month=date('F-Y',strtotime($this->_data['month']));
			$_SESSION[SUCCESS_MSG] = "Expense Deleted successfully!!";
			$this->_redirect($this->_request->getControllerName().'/'.$this->_data['method'].'/user_id/'.$this->_data['user_id'].'/month/'.$month);	
		}else{
			$_SESSION[ERROR_MSG] = "Some problem occur while deleting Expense!";
			$this->_redirect($this->_request->getControllerName().'/'.$this->_data['method'].'/user_id/'.$this->_data['user_id'].'/month/'.$month);
		}
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
