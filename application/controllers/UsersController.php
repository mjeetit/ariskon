<?php
class UsersController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init(){
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$_parent = 5;
		Bootstrap::$_level = 2;
		Bootstrap::$ActionName = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new Users();
		$this->ObjModel->_getData = $this->_data;
		$this->view->ObjModel = $this->ObjModel;
		$this->view->filter = $this->_data; 
	}
    public function userAction(){
	if($this->_request->isGet() && !empty($this->_data['export'])){
		$this->ObjModel->ExportOrganographReport();
	   }
	   $this->view->Users = $this->ObjModel->getUsers();
	   $this->view->filteruser = $this->ObjModel->getAllUsersForSalary();
	   $this->view->filterdesignation = $this->ObjModel->getDesignation();
	   $this->view->filterdepartment = $this->ObjModel->getDepartment();
	   $this->view->filterheadquater = $this->ObjModel->getHeadquaters();
	}
	public function adduserAction(){
	  $this->view->form = 1;
	  if($this->_request->isPost()){ 
	      $this->view->form = 1;
		 // print_r($this->_data);die;
	       $this->ObjModel->addNewUser();
		   $this->_redirect($this->_request->getControllerName().'/user');
		}
	}
	public function edituserAction(){
	 if($this->_request->isPost()){
	       $this->ObjModel->editUser();
	       $this->_redirect($this->_request->getControllerName().'/user');
	 }
	   $this->view->UserDetail = $this->ObjModel->EditUserDetail();
	}
	public function employeesalaryAction(){
	   if($this->_request->isPost() && !empty($this->_data['updateamount'])){
	       $this->ObjModel->UpdateSalaryAmountForEmployee();
	   }
	   $this->view->salaryAmount = $this->ObjModel->getSalaryTemplateForEmployee();
	  // print_r($this->view->salaryAmount);die;
	}
   public function viewAction(){
     $this->_helper->layout->setLayout('popupmain');
     $this->view->UserDetail = $this->ObjModel->viewUserDetail();
   }
   
   public function privillageAction(){
     $this->_helper->layout->setLayout('popupmain'); 
	  if($this->_request->isPost()){
	       $this->ObjModel->AddPrivillage();
	   }
	 $obj =  new PrivillageForm();//print_r($this->ObjModel->getUsersPrivForEdit());die;
	 $obj->privilege = $this->ObjModel->getUsersPrivForEdit();
	 $this->view->privForm  = $obj->UserPrivillageForm($this->ObjModel->getUsersPrivForEdit());
   }
   public function deleteAction(){
     $this->_helper->layout->setLayout('popupmain');
	 if($this->_request->isPost()){
	       $this->ObjModel->deleteusers();
	   } 
	 $this->view->parentlist = $this->ObjModel->getParentListForAssign();
   }
   public function changepasswordAction(){
      $this->_helper->layout->setLayout('popupmain');
	  if($this->_request->isPost()){
	       $this->ObjModel->changePassword();
	   } 
	  $this->view->parentlist = $this->ObjModel->getParentListForAssign();
   }
   public function settlemnetAction(){
       if(!empty($this->_data['employee_code'])){
            $employeedetail = $this->ObjModel->usersettlementDetail();
			$this->view->detail     = $employeedetail['GeneralDetail'];
			$this->view->salarylist = $employeedetail['SalaryDetail'];
       }
	   if(!empty($this->_data['submit'])  && $this->_data['submit']=='Update'){
	       $this->ObjModel->finalsettlement();
	   }
       //$this->view->userlist =
   }
   public function previousemployeeAction(){
       $this->view->Users = $this->ObjModel->previusemployee();
   } 
    public function empleaveAction(){
     if($this->_request->isPost()){
	    $this->ObjModel->updateEmployeeLeaves();
	 }
     $this->view->empleaves = $this->ObjModel->getempleaves();
   }
   public function userhistoryAction(){
       //$this->_helper->layout->setLayout('popupmain'); 
       $this->view->history = $this->ObjModel->getUserHistory();
   } 
   public function profileAction(){
     $this->ObjModel->_getData['user_id'] = $_SESSION['AdminLoginID'];
     $this->view->UserDetail = $this->ObjModel->EditUserDetail();
   }	
   public function editprofileAction(){
     $this->ObjModel->_getData['user_id'] = $_SESSION['AdminLoginID'];
	 if($this->_request->isPost()){
	       $this->ObjModel->editUserprofile();
	       $this->_redirect($this->_request->getControllerName().'/profile');
	 }
	 $this->view->UserDetail = $this->ObjModel->EditUserDetail();
   }
   
   public function crmprivilegeAction(){
     $this->_helper->layout->setLayout('popupmain'); 
	 $data = $this->_request->getParams();
	 if($this->_request->isPost()){
	       $this->ObjModel->addUserPrivileges($data);
	       $_SESSION[SUCCESS_MSG] = "CRM privileges updated successfully!!";
		   $this->_redirect($this->_request->getControllerName().'/crmprivilege/token/'.$data['token']);
	 }
	 $this->view->UserDetail = $this->ObjModel->EditUserDetail();
	 $this->view->privilegecrm = $this->modulePrivilege(array('ParentID'=>0,'LevelID'=>8,'UserID'=>$data['token'],'Action'=>1));
   }
   
	/**
	 * Method modulePrivilege() gets those module which the user have privilege. 
	 * @access	public
	 * @param	$data , hold user ID, Parent ID, level ID, action, gui
	 * @return	array
	 */
	public function modulePrivilege($data=array())
	{
		$ParentID = (isset($data['ParentID'])) ? trim($data['ParentID']) : 0;
		$LevelID  = (isset($data['LevelID']))  ? trim($data['LevelID'])  : 8;
		$UserID   = (isset($data['UserID']))   ? trim($data['UserID'])   : 0;
		$Action   = (isset($data['Action']))   ? trim($data['Action'])   : 0;
		
		$modules   = $this->ObjModel->getModules($ParentID);
		$levelPrivilege = $this->ObjModel->getLevelPrivileges($LevelID);
		$userPrivilege  = $this->ObjModel->getUserPrivileges($UserID); //echo "<pre>";print_r($userPrivilege);die;
		$output = "";
		if (count($modules) > 0) {
			$output .= '<ul style="list-style: none;">';
			foreach($modules as $key=>$child) {
				if(in_array($child['module_id'],$levelPrivilege['Modules'],true)) {
					$countChild = $this->ObjModel->getModules($child['module_id']);
					$onclick = (count($countChild) > 0) ? 'onclick="$.ShowModule('.$child['module_id'].')"' : 'onclick="$.ShowAction('.$child['module_id'].')"';
					$chkcond = ($Action==1) ? $userPrivilege['Modules'] : $levelPrivilege['Modules'];
					$checked = (in_array($child['module_id'],$chkcond,true)) ? 'checked="checked"' : '';
					$output .= '<li><input type="checkbox" name="modules[]" id="module'.$child['module_id'].'" value="'.$child['module_id'].'" '.$onclick.' '.$checked.' />&nbsp;&nbsp;'.$child['module_name'];					
					if(count($countChild) > 0) { 						
						$style   = (in_array($child['module_id'],$chkcond,true)) ? 'style="display:block;"' : 'style="display:none;"';
						$output .= '<div id="sub'.$child['module_id'].'" '.$style.'>';
						$sections= $this->ObjModel->getModuleSections($child['module_id']);
						if(count($sections) > 0) {
							$output .= '<ul>';
							foreach($sections as $index=>$section) {
								if(in_array($section['section_id'],$levelPrivilege['Sections'][$child['module_id']],true)) {
									$check = '';
									if(isset($levelPrivilege['Sections'][$child['module_id']]) && count($userPrivilege['Sections'])>0 && in_array($section['section_id'],$userPrivilege['Sections'][$child['module_id']],true)) {
										$check = 'checked="checked"';
									}
									else if(isset($levelPrivilege['Sections'][$child['module_id']]) && count($userPrivilege['Sections'])<=0 && in_array($section['section_id'],$levelPrivilege['Sections'][$child['module_id']],true)) {
										$check = 'checked="checked"';
									}
									else {
										$check = '';
									}
									$output .= '<input type="checkbox" name="'.$child['module_id'].'[]" id="'.$section['section_id'].'" value="'.$section['section_id'].'" '.$check.' />&nbsp;&nbsp;'.$section['section_name'].'&nbsp;&nbsp;';
								}
							}
							$output .= '</ul>';
						}
						$output .= $this->modulePrivilege(array('ParentID'=>$child['module_id'],'LevelID'=>$LevelID,'UserID'=>$UserID,'Action'=>$Action));
						$output .= '</div>';
					}
					else {
						$style   = (in_array($child['module_id'],$chkcond,true)) ? 'style="display:block;"' : 'style="display:none;"';
						$output .= '<div id="action'.$child['module_id'].'" '.$style.'>';
						$sections= $this->ObjModel->getModuleSections($child['module_id']);
						if(count($sections) > 0) {
							$output .= '<ul>';
							foreach($sections as $index=>$section) {
								if(in_array($section['section_id'],$levelPrivilege['Sections'][$child['module_id']],true)) {	
									@$sects = ($Action==1) ? $userPrivilege['Sections'][$child['module_id']] : $levelPrivilege['Sections'][$child['module_id']];
									@$check = ((isset($levelPrivilege['Sections'][$child['module_id']]) && in_array($section['section_id'],$sects,true))) ? 'checked="checked"' : '';
									
									$output .= '&nbsp;&nbsp;<input type="checkbox" name="'.$child['module_id'].'[]" id="'.$section['section_id'].'" value="'.$section['section_id'].'" '.$check.' />&nbsp;'.$section['section_name'];
								}
							}
							$output .= '</ul>';
						}
						$output .= "</div>";
					}
					$output .= '</li>';
				}
			 }     
			$output .= '</ul>';
		}
		
		return $output;
	}
}
?>
