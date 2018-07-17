<?php
class PrivilegeController extends Zend_Controller_Action {
	var $session="";
	public $users;
	public $ObjModel = NULL;
	public $_data = NULL;

	public function init()
	{
		if(!isset($_SESSION['AdminLoginID'])){
	      $this->_redirect(Bootstrap::$baseUrl);
	   }
		$this->session = Bootstrap::$registry->get('defaultNs');
		Bootstrap::$ActionName  = $this->_request->getActionName();
		$this->_helper->layout->setLayout('main');
		$this->_data = $this->_request->getParams();
		$this->ObjModel = new PrivilegeManager();
		$this->ObjAjax = new AjaxManager();
		$this->view->ObjModel = $this->ObjModel;
		
		// Get and check access of module privilege
		Bootstrap::$menuPrivilege = $this->ObjModel->HeaderMenuItems();
		$this->ObjModel->checkPrivileged();
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
    public function levelprivilegeAction()
	{
		 $this->view->viewData = $this->ObjModel->getDesignationPrivilege();
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function editlevelprivilegeAction()
	{
		 $this->_helper->layout->setLayout('popupmain');
		 $this->view->filterdata = $this->_request->getParams();
		 $this->view->viewData = $this->moduleList(array('ParentID'=>0,'DesigID'=>Class_Encryption::decode($this->view->filterdata['token'])));
		 
		 //Validate Form Feild Values
		 if($this->_request->isPost()){
			if($this->ObjModel->addDefaultPrivileges($this->view->filterdata)) {
				$_SESSION[SUCCESS_MSG] = "Privilege updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName().'?token='.$this->view->filterdata['token']);
			}
			else {
				$_SESSION[ERROR_MSG] = "Privilege not updated!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName().'?token='.$this->view->filterdata['token']);
			}
		}
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function moduleList($data=array()) {
		$ParentID = (isset($data['ParentID'])) ? trim($data['ParentID']) : 0;
		$DesigID  = (isset($data['DesigID']))  ? trim($data['DesigID'])  : 0;
		
		$privilege = $this->ObjModel->getLevelPrivileges($DesigID);

		/***********************************************************************
		 getModule function name modified to make it separate to define in 
		 custom class file in library by jm on 16072018
		***********************************************************************/
		//$modules   = $this->ObjModel->getModules($ParentID);
		$modules   = $this->ObjModel->getModulesCRM($ParentID); 
				
		$output = "";
		if (count($modules) > 0) {
			$output .= '<ul style="list-style: none;">';
			foreach($modules as $key=>$child) {

				/***********************************************************************
				 getModule function name modified to make it separate to define in 
				 custom class file in library by jm on 16072018
				***********************************************************************/
				//$countChild = $this->ObjModel->getModules($child['module_id']);
				$countChild = $this->ObjModel->getModulesCRM($child['module_id']);
				$onclick = (count($countChild) > 0) ? 'onclick="$.ShowModule('.$child['module_id'].')"' : 'onclick="$.ShowAction('.$child['module_id'].')"';
				$checked = (in_array($child['module_id'],$privilege['Modules'],true)) ? 'checked="checked"' : '';
				$output .= '<li><input type="checkbox" name="modules[]" id="module'.$child['module_id'].'" value="'.$child['module_id'].'" '.$onclick.' '.$checked.' />&nbsp;&nbsp;'.$child['module_name'];					
				if(count($countChild) > 0) { 						
					$style   = (in_array($child['module_id'],$privilege['Modules'],true)) ? 'style="display:block;"' : 'style="display:none;"';
					$output .= '<div id="sub'.$child['module_id'].'" '.$style.'>';
					$sections= $this->ObjModel->getModuleSections($child['module_id']);
					if(count($sections) > 0) {
						$output .= '<ul>';
						foreach($sections as $index=>$section) {
							$check = ((isset($privilege['Sections'][$child['module_id']]) && in_array($section['section_id'],$privilege['Sections'][$child['module_id']],true))) ? 'checked="checked"' : '';
							$output .= '<input type="checkbox" name="'.$child['module_id'].'[]" id="'.$section['section_id'].'" value="'.$section['section_id'].'" '.$check.' />&nbsp;&nbsp;'.$section['section_name'].'&nbsp;&nbsp;';
						}
						$output .= '</ul>';
					}
					$output .= $this->moduleList(array('ParentID'=>$child['module_id'],'DesigID'=>$DesigID));
					$output .= '</div>';
				}
				else {
					$style   = (in_array($child['module_id'],$privilege['Modules'],true)) ? 'style="display:block;"' : 'style="display:none;"';
					$output .= '<div id="action'.$child['module_id'].'" '.$style.'>';
					$sections= $this->ObjModel->getModuleSections($child['module_id']);
					if(count($sections) > 0) {
						$output .= '<ul>';
						foreach($sections as $index=>$section) {
							$check = ((isset($privilege['Sections'][$child['module_id']]) && in_array($section['section_id'],$privilege['Sections'][$child['module_id']],true))) ? 'checked="checked"' : '';
							$output .= '&nbsp;&nbsp;<input type="checkbox" name="'.$child['module_id'].'[]" id="'.$section['section_id'].'" value="'.$section['section_id'].'" '.$check.' />&nbsp;'.$section['section_name'];
						}
						$output .= '</ul>';
					}
					$output .= "</div>";
				  }
				  $output .= '</li>';
			 }     
			 $output .= '</ul>';
		}
		
		return $output;
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function userprivilegeAction()
	{
		 $this->view->Filterdata = $this->_request->getParams();
		 $this->view->beDetails = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'8'));
		 $this->view->abmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'7'));
		 $this->view->rbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'6'));
		 $this->view->zbmDetails= $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>'5'));
		 $this->view->desigs    = $this->ObjModel->getDesignationPrivilege();
		 $this->view->viewData  = $this->ObjModel->getAllUserPrivilege($this->view->Filterdata);
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function edituserprivilegeAction()
	{
		 $this->_helper->layout->setLayout('popupmain');
		 $this->view->filterdata = $this->_request->getParams();
		 $this->view->viewData = $this->usermoduleList(array('ParentID'=>0,'UserID'=>Class_Encryption::decode($this->view->filterdata['token']),'LevelID'=>Class_Encryption::decode($this->view->filterdata['token1'])));
		 
		 //Validate Form Feild Values
		 if($this->_request->isPost()){
			if($this->ObjModel->addUserPrivileges($this->view->filterdata)) {
				$_SESSION[SUCCESS_MSG] = "Privilege updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName().'?token='.$this->view->filterdata['token'].'&token1='.$this->view->filterdata['token1']);
				echo '<script type="text/javascript">window.opener.location.reload();self.close();</script>';
			}
			else {
				$_SESSION[ERROR_MSG] = "Privilege not updated!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName().'?token='.$this->view->filterdata['token'].'&token1='.$this->view->filterdata['token1']);
			}
		}
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function usermoduleList($data=array()) {
		$ParentID = (isset($data['ParentID'])) ? trim($data['ParentID']) : 0;
		$LevelID  = (isset($data['LevelID']))  ? trim($data['LevelID'])  : 0;
		$UserID   = (isset($data['UserID']))   ? trim($data['UserID'])   : 0;
		$Action	  = (isset($data['Action']))   ? trim($data['Action'])   : 1;
		
		/***********************************************************************
		  getModule function name modified to make it separate to define in 
		  custom class file in library by jm on 16072018
		***********************************************************************/
		//$modules   		= $this->ObjModel->getModules($ParentID);
		$modules   		= $this->ObjModel->getModulesCRM($ParentID);
		$levelPrivilege = $this->ObjModel->getLevelPrivileges($LevelID);
		$userPrivilege  = $this->ObjModel->getUserPrivileges($UserID);

		$output = "";
		if (count($modules) > 0) {
			$output .= '<ul style="list-style: none;">';
			foreach($modules as $key=>$child) {
				if(in_array($child['module_id'],$levelPrivilege['Modules'],true)) {
					//$countChild = $this->ObjModel->getModules($child['module_id']);
					$countChild = $this->ObjModel->getModulesCRM($child['module_id']);
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
									//@$check = ((isset($levelPrivilege['Sections'][$child['module_id]) && in_array($section['section_id,$userPrivilege['Sections'][$child['module_id],true))) ? 'checked="checked"' : '';
									
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
						$output .= $this->usermoduleList(array('ParentID'=>$child['module_id'],'LevelID'=>$LevelID,'UserID'=>$UserID,'Action'=>$Action));
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
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function setprivilegetoallAction()
	{
		 $this->view->filterdata = $this->_request->getParams();
		 $this->view->desigs     = $this->ObjModel->getDesignationPrivilege();
		 
		 //Validate Form Feild Values
		 if($this->_request->isPost()){
			if($this->ObjModel->addUserPrivileges($this->view->filterdata)) {
				$_SESSION[SUCCESS_MSG] = "Privilege updated successfully!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
			}
			else {
				$_SESSION[ERROR_MSG] = "Privilege not updated!!";
				$this->_redirect($this->_request->getControllerName().'/'.$this->getRequest()->getActionName());
			}
		}
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function getuserAction()
	{
		$this->_helper->layout->setLayout('popupmain');
		$data = $this->_request->getParams();
		$users = $this->ObjAjax->getDesignationWiseUserLists(array('designationID'=>Class_Encryption::decode($data['token'])));
		
		$string = '';
		if(count($users)>0) {
			$string .= '<div class="container">';
			$string .= '&nbsp; <input type="checkbox" name="checkHead" id="checkHead" /> Check All/Uncheck All '.count($users).' User<br>';
			foreach($users as $user) {
				$string .= '&nbsp; <input type="checkbox" name="user[]" class="allchecked" value="'.Class_Encryption::encode($user['user_id']).'" /> '.$user['employee_code'].' - '.$user['first_name'].' '.$user['last_name'].' <br />';
			}
			$string .= '</div>';
			$string .= '<script type="text/javascript">
						$("#checkHead").click(function() {
							$(".allchecked").attr("checked", this.checked);
							$(".css-checkbox").attr("checked", this.checked);
						});
						</script>';
		}
		print_r($string);die;
	}
	
	/**
	 * Method moduleList() gets the module and section. 
	 * @access	public
	 * @param	$data , hold level ID, Parent ID, Gui.
	 * @return	array
	 */
	public function getprivilegeAction()
	{
		$this->_helper->layout->setLayout('popupmain');
		$data = $this->_request->getParams();
		print_r($this->moduleList(array('ParentID'=>0,'DesigID'=>Class_Encryption::decode($data['token']))));die;
	}
}
?>