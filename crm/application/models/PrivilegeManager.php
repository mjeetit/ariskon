<?php
class PrivilegeManager extends Zend_Custom
{
	/**
	 * Method getModuleSections() get list of all section_id and section names on the basis of module id.
	 * @access	public
	 * @param	$moduleID holds module_id
	 * @return	array
	 */
	public function getDesignationPrivilege() {
		$query = $this->_db->select()
				 ->from(array('DT'=>'designation'),array('DT.designation_id','DT.designation_code','DT.designation_name',"GROUP_CONCAT(DISTINCT CM.module_name SEPARATOR ' &rsaquo;&rsaquo; ') AS module"))
				 ->joinleft(array('DP'=>'designationprivileges'),'DP.designation_id=DT.designation_id','')
				 ->joinleft(array('CM'=>'crm_modules'),"CM.module_id=DP.module_id AND CM.module_type='1' AND CM.parent_id=0",'')
				 ->where("DT.crm_privilege='1'")
				 ->group('DT.designation_id')
				 ->order('DT.designation_name','ASC'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	/**
	 * Method getLevelPrivileges() get list of module_id, section_id, status, delete_status on the basis of level_id and 
	   which have delete_status=0, status=1.
	 * @access	public
	 * @param	$levelID holds level_id
	 * @return	array
	 */
	public function getLevelPrivileges($DesigID) {
		$query = $this->_db->select()
				 ->from(array('DP'=>'designationprivileges'),array('DP.module_id','DP.section_id'))
				 ->joininner(array('CM'=>'crm_modules'),"CM.module_id=DP.module_id AND CM.isDelete='0' AND CM.isActive='1'",'')
				 ->joinleft(array('CS'=>'crm_sections'),"CS.section_id=DP.section_id AND CS.status='1' AND CS.delete_status='0'",'')
				 ->where("DP.designation_id=".$DesigID); //echo $query->__toString();die;
		$records = $this->getAdapter()->fetchAll($query);
		$modules = array(); $sections = array();
		if(count($records)>0) {
			foreach($records as $info) {
				$modules[] = $info['module_id'];
				$sections[$info['module_id']][] = $info['section_id'];
			}
		}
		return array('Modules'=>$modules,'Sections'=>$sections);
	}
	
	/**
	* Method getModules() get list of module_id, parent_id, level_id, module_name on the basis of module id and gui=web and which
	have delete_status=0 and status=1..
	* @access	public
	* @param	$moduleID , hold module ID and $gui holds web
	* @return	array.
	*/
	//public function getModulesCRM($parentID){   
	public function getModulesCRM($parentID){
		$query = $this->_db->select()
				 ->from(array('CM'=>'crm_modules'),array('CM.module_id','CM.parent_id','CM.level_id','CM.module_name'))
				 ->where("CM.isDelete='0' AND CM.isActive='1' AND CM.parent_id=".(int)$parentID); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	/**
	 * Method getModuleSections() get list of all section_id and section names on the basis of module id.
	 * @access	public
	 * @param	$moduleID holds module_id
	 * @return	array
	 */
	public function getModuleSections($moduleID){
		$query = $this->_db->select()
				 ->from(array('CMS'=>'crm_modulesections'),array('CS.section_id','CS.section_name'))
				 ->joininner(array('CS'=>'crm_sections'),"CS.section_id=CMS.section_id AND CS.status='1' AND CS.delete_status='0'",'')
				 ->where("CMS.module_id=".(int)$moduleID)
				 ->order('CS.section_name ASC'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	/**
	 * Method addDefaultPrivileges() insert level_id,module_id, section_id to level privilege table on the basis of level_id to 
	   add default privilege for userlevels.
	 * @access	public
	 * @param	$levelID holds level_id
	 * @return	boolean
	 */
	public function addDefaultPrivileges($data){
		$desigID = Class_Encryption::decode($data['token']);
		$this->_db->delete('designationprivileges','designation_id='.$desigID);
		if(count($data['modules']) > 0) {
			foreach($data['modules'] as $moduleID) {
				if(is_array($data[$moduleID])) {
					foreach($data[$moduleID] as $sectionID) {
						$this->_db->insert('designationprivileges',array('designation_id'=>$desigID,'module_id'=>$moduleID,'section_id'=>$sectionID,'assigned_by'=>$_SESSION['AdminLoginID'],'assigned_ip'=>$_SERVER['REMOTE_ADDR']));
					}
				}
				else {
					$this->_db->insert('designationprivileges',array('designation_id'=>$desigID,'module_id'=>$moduleID,'section_id'=>0,'assigned_by'=>$_SESSION['AdminLoginID'],'assigned_ip'=>$_SERVER['REMOTE_ADDR']));
				}
			}
		}
		return TRUE;
	}
	
	/**
	 * Method getUserPrivilege() get list of module_id, section_id, status, delete_status on the basis of level_id and 
	   which have delete_status=0, status=1.
	 * @access	public
	 * @param	null
	 * @return	array
	 */
	public function getAllUserPrivilege($data) {
		try {
			$filterparam = '';
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$filterparam .= ' AND UT.user_id='.Class_Encryption::decode($data['token6']);
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$filterparam .= ' AND UT.user_id='.Class_Encryption::decode($data['token5']);
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$filterparam .= ' AND UT.user_id='.Class_Encryption::decode($data['token4']);
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$filterparam .= ' AND UT.user_id='.Class_Encryption::decode($data['token3']);
			}
			//Filter With Designation Data
			if(!empty($data['token7'])){
				$filterparam = ' AND EPD.designation_id='.Class_Encryption::decode($data['token7']);
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'DT.designation_name');
			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];
			
			$countQuery = $this->_db->select()
							->from(array('UT'=>'users'),array('COUNT(1) AS CNT'))
							->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=UT.user_id",'')
							->joininner(array('DT'=>'designation'),"DT.designation_id=EPD.designation_id",'')
							->joinleft(array('PT'=>'userprivileges'),"PT.user_id=UT.user_id",'')
							->joinleft(array('CM'=>'crm_modules'),"CM.module_id=PT.module_id AND CM.module_type='1' AND CM.parent_id=0",'')
							->where("DT.crm_privilege='1' AND EPD.user_status='1' AND EPD.login_status='1' AND UT.user_id!=1".$filterparam);
							//print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			
			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];
			
			$query = $this->_db->select()
					 ->from(array('UT'=>'users'),array('UT.user_id','EPD.employee_code','EPD.designation_id',"CONCAT(EPD.first_name,' ',EPD.last_name) AS emp","CONCAT(DT.designation_code,' (',DT.designation_name,')') AS desig","GROUP_CONCAT(DISTINCT CM.module_name SEPARATOR ' &rsaquo;&rsaquo; ') AS module"))
					 ->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=UT.user_id",'')
					 ->joininner(array('DT'=>'designation'),"DT.designation_id=EPD.designation_id",'')
					 ->joinleft(array('PT'=>'userprivileges'),"PT.user_id=UT.user_id",'')
					 ->joinleft(array('CM'=>'crm_modules'),"CM.module_id=PT.module_id AND CM.module_type='1' AND CM.parent_id=0",'')
					 ->where("DT.crm_privilege='1' AND EPD.user_status='1' AND EPD.login_status='1' AND UT.user_id!=1".$filterparam)
					 ->group('UT.user_id')
					 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
					 ->order('EPD.first_name ASC')
					 ->limit($limit); //print_r($query->__toString());die;
			$result = $this->getAdapter()->fetchAll($query);
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 
		}
	}
	
	/**
	 * Method getLevelPrivileges() get list of module_id, section_id, status, delete_status on the basis of level_id and 
	   which have delete_status=0, status=1.
	 * @access	public
	 * @param	$levelID holds level_id
	 * @return	array
	 */
	public function getUserPrivileges($UserID) {
		$query = $this->_db->select()
				 ->from(array('DP'=>'userprivileges'),array('DP.module_id','DP.section_id'))
				 ->joininner(array('CM'=>'crm_modules'),"CM.module_id=DP.module_id AND CM.isDelete='0' AND CM.isActive='1'",'')
				 ->joinleft(array('CS'=>'crm_sections'),"CS.section_id=DP.section_id AND CS.status='1' AND CS.delete_status='0'",'')
				 ->where("DP.user_id=".$UserID); //echo $query->__toString();die;
		$records = $this->getAdapter()->fetchAll($query);
		$modules = array(); $sections = array();
		if(count($records)>0) {
			foreach($records as $info) {
				$modules[] = $info['module_id'];
				$sections[$info['module_id']][] = $info['section_id'];
			}
		}
		return array('Modules'=>$modules,'Sections'=>$sections);
	}
	
	/**
	 * Method addDefaultPrivileges() insert level_id,module_id, section_id to level privilege table on the basis of level_id to 
	   add default privilege for userlevels.
	 * @access	public
	 * @param	$levelID holds level_id
	 * @return	boolean
	 */
	public function addUserPrivileges($data){
		if((count($data['user'])>0)) {
			foreach($data['user'] as $user) {
				$desigID = Class_Encryption::decode($user);
				$this->_db->delete('userprivileges','user_id='.$desigID);
				if(count($data['modules']) > 0) {
					foreach($data['modules'] as $moduleID) {
						if(is_array($data[$moduleID])) {
							foreach($data[$moduleID] as $sectionID) {
								$this->_db->insert('userprivileges',array('user_id'=>$desigID,'module_id'=>$moduleID,'section_id'=>$sectionID,'assigned_by'=>$_SESSION['AdminLoginID'],'assigned_ip'=>$_SERVER['REMOTE_ADDR']));
							}
						}
						else {
							$this->_db->insert('userprivileges',array('user_id'=>$desigID,'module_id'=>$moduleID,'section_id'=>0,'assigned_by'=>$_SESSION['AdminLoginID'],'assigned_ip'=>$_SERVER['REMOTE_ADDR']));
						}
					}
				}
			}
		}
		return TRUE;
	}
}
?>