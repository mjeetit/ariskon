<?php
class AjaxManager extends Zend_Custom
{
	public $_getData 		= array();
	private $_userIDs	 	= array();
	private $_parentIDs	 	= array();
	private $_headquarters 	= array();
	
	public function getUserUpperLevel($data=array())
	{
		$headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : $this->_getData['token'];
		$query = $this->_db->select()
				 ->from(array('EPD'=>'employee_personaldetail'),array('beLevel'=>'EPD.designation_id','beId'=>'EPD.user_id','beParent'=>'EPD.parent_id','beCode'=>'EPD.employee_code','beName'=>"CONCAT(EPD.first_name,' ',EPD.last_name)"))
				 ->joininner(array('ELD'=>'emp_locations'),"ELD.user_id=EPD.user_id",array('beHQ'=>'ELD.headquater_id'))
				 ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=ELD.headquater_id",array('beHQName'=>'CONCAT(HT.location_code," - ",HT.headquater_name)'))
				 ->joinleft(array('EPD1'=>'employee_personaldetail'),"EPD1.user_id=EPD.parent_id",array('abmLevel'=>'EPD1.designation_id','abmId'=>'EPD1.user_id','abmParent'=>'EPD1.parent_id','abmCode'=>'EPD1.employee_code','abmName'=>"CONCAT(EPD1.first_name,' ',EPD1.last_name)"))
				 ->joinleft(array('ELD1'=>'emp_locations'),"ELD1.user_id=EPD1.user_id",array('abmHQ'=>'ELD1.headquater_id'))
				 ->joinleft(array('HT1'=>'headquater'),"HT1.headquater_id=ELD1.headquater_id",array('abmHQName'=>'CONCAT(HT1.location_code," - ",HT1.headquater_name)'))
				 ->joinleft(array('EPD2'=>'employee_personaldetail'),"EPD2.user_id=EPD1.parent_id",array('rbmLevel'=>'EPD2.designation_id','rbmId'=>'EPD2.user_id','rbmParent'=>'EPD2.parent_id','rbmCode'=>'EPD2.employee_code','rbmName'=>"CONCAT(EPD2.first_name,' ',EPD2.last_name)"))
				 ->joinleft(array('ELD2'=>'emp_locations'),"ELD2.user_id=EPD2.user_id",array('rbmHQ'=>'ELD2.headquater_id'))
				 ->joinleft(array('HT2'=>'headquater'),"HT2.headquater_id=ELD2.headquater_id",array('rbmHQName'=>'CONCAT(HT2.location_code," - ",HT2.headquater_name)'))
				 ->joinleft(array('EPD3'=>'employee_personaldetail'),"EPD3.user_id=EPD2.parent_id",array('zbmLevel'=>'EPD3.designation_id','zbmId'=>'EPD3.user_id','zbmParent'=>'EPD3.parent_id','zbmCode'=>'EPD3.employee_code','zbmName'=>"CONCAT(EPD3.first_name,' ',EPD3.last_name)"))
				 ->joinleft(array('ELD3'=>'emp_locations'),"ELD3.user_id=EPD3.user_id",array('zbmHQ'=>'ELD2.headquater_id'))
				 ->joinleft(array('HT3'=>'headquater'),"HT3.headquater_id=ELD3.headquater_id",array('zbmHQName'=>'CONCAT(HT2.location_code," - ",HT2.headquater_name)'))
				 ->where("EPD.designation_id=8 AND ELD.headquater_id ='".$headquarterID."'")
				 ->limit('1'); //echo $query->__toString();die;
		$levelDetail = $this->getAdapter()->fetchRow($query); //echo "<pre>";print_r($levelDetail);echo "</pre>";die;
		
		// Check First (BE) Level User
		$firstLevelData = array('Level'=>$levelDetail['beLevel'],'UserID'=>$levelDetail['beId'],'ParentID'=>$levelDetail['beParent'],'EmpCode'=>$levelDetail['beCode'],'EmpName'=>$levelDetail['beName'],'HqID'=>$levelDetail['beHQ'],'HqName'=>$levelDetail['beHQName']);		
		$response1 = $this->filterUserLevelByDesignation($firstLevelData);
		
		// Check Second (ABM) Level User
		$secondLevelData = array('Level'=>$levelDetail['abmLevel'],'UserID'=>$levelDetail['abmId'],'ParentID'=>$levelDetail['abmParent'],'EmpCode'=>$levelDetail['abmCode'],'EmpName'=>$levelDetail['abmName'],'HqID'=>$levelDetail['abmHQ'],'HqName'=>$levelDetail['abmHQName']);		
		$response2 = $this->filterUserLevelByDesignation($secondLevelData);
		
		// Check Third (RBM) Level User
		$thirdLevelData = array('Level'=>$levelDetail['rbmLevel'],'UserID'=>$levelDetail['rbmId'],'ParentID'=>$levelDetail['rbmParent'],'EmpCode'=>$levelDetail['rbmCode'],'EmpName'=>$levelDetail['rbmName'],'HqID'=>$levelDetail['rbmHQ'],'HqName'=>$levelDetail['rbmHQName']);		
		$response3 = $this->filterUserLevelByDesignation($thirdLevelData);
		
		// Check Fourth (ZBM) Level User
		$fourthLevelData = array('Level'=>$levelDetail['zbmLevel'],'UserID'=>$levelDetail['zbmId'],'ParentID'=>$levelDetail['zbmParent'],'EmpCode'=>$levelDetail['zbmCode'],'EmpName'=>$levelDetail['zbmName'],'HqID'=>$levelDetail['zbmHQ'],'HqName'=>$levelDetail['zbmHQName']);		
		$response4 = $this->filterUserLevelByDesignation($fourthLevelData);
		
		return array_merge($response1,$response2,$response3,$response4);
	}
	
	public function filterUserLevelByDesignation($levelDetail)
	{
		$response = array();
		switch($levelDetail['Level'])
		{
			case 8 :
				$response['BE'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);
				break;
			case 7 :
				$response['ABM'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);
				break;
			case 6 :
				$response['RBM'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);
				break;
			case 5 :
				$response['ZBM'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);
				break;
			default :
				break;
		}
		
		return $response;
	}
	
	public function getTableData($data=array())
	{
		try {
			$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';
			$tableColumn = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');
			$returnRow   = (isset($data['returnRow'])   && !empty($data['returnRow']))    ? $data['returnRow']       : 'single';
			
			$where = '1';
			if(isset($data['columnName']) && isset($data['columnValue'])) {
				$where .=  " AND UPPER(".$data['columnName'].") LIKE '".strtoupper($data['columnValue'])."%'";
			}
			if(isset($data['columnName1']) && isset($data['columnValue1'])) {
				$where .=  " AND ".$data['columnName1']."='".$data['columnValue1']."'";
			}
			if(isset($data['columnName2']) && isset($data['columnValue2'])) {
				$where .=  " AND ".$data['columnName2']."='".$data['columnValue2']."'";
			}
			if(isset($data['columnName3']) && isset($data['columnValue3'])) {
				$where .=  " AND ".$data['columnName3']."='".$data['columnValue3']."'";
			}
			if(isset($data['columnName4']) && isset($data['columnValue4'])) {
				$where .=  " AND ".$data['columnName4']."='".$data['columnValue4']."'";
			}
			$orderColumn = (isset($data['orderColumn']) && !empty($data['orderColumn'])) ? $data['orderColumn'] : $tableColumn[0];
			$orderType   = (isset($data['orderType'])   && !empty($data['orderType']))   ? $data['orderType']   : 'ASC';
			
			$select = $this->_db->select()->from($tableName,$tableColumn)->where($where)->order($orderColumn,'ASC'); //echo $select->__toString();die;
			//if($tableName=='patchcodes'){ echo $select->__toString();die; }
			return ($returnRow=='single') ? $this->getAdapter()->fetchRow($select) : $this->getAdapter()->fetchAll($select);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function addTableData($data=array())
	{
		try {
			$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
			$tableData = (isset($data['tableData']) && count($data['tableData'])>0) ? $data['tableData'] : array();
			
			if(!empty($tableName) && count($tableData)>0) {
				return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
			}
			else {
				return 0;
			}
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
		
	public function updateTableData($data=array())
	{
		try {
			$tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
			$tableData   = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
			$whereColumn = (isset($data['whereColumn']) && !empty($data['whereColumn'])) ? trim($data['whereColumn']) : '';
			
			if(!empty($tableName) && count($tableData)>0 && !empty($whereColumn)) {
				return ($this->_db->update($tableName,array_filter($tableData),$whereColumn)) ? TRUE : FALSE;
			}
			else {
				return FALSE;
			}
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getUserParents($loggedIn)
	{
		$query = $this->_db->select()->from(array('EPD'=>'employee_personaldetail'),array())->joinleft(array('EPD1'=>'employee_personaldetail'),"EPD1.user_id=EPD.parent_id",array('EPD1.user_id','EPD1.parent_id','EPD1.designation_id'))->where("EPD.user_id =".$loggedIn." AND EPD.designation_id<=8"); //echo $query->__toString();die;
		$result = $this->getAdapter()->fetchAll($query);//print_r($results);die;
		
		$userParents = array();
		$parents = '';
		if (count($result) > 0) {
			foreach($result as $results) {
				if($results['parent_id'] > 0) {
					$userParents[] = array($results['user_id'],$results['designation_id']);
					$parents .= $results['user_id'].',';
					$parents .= $this->getUserParents($results['user_id']);
				}
				else {
					$userParents[] = array($results['user_id'],$results['designation_id']);
					$parents .= $results['user_id'].',';
				}
			}
		}
		//$parentsArr = explode(',',$parents);
		return $parents;
	}
	
	public function getDesignationWiseUserLists($data=array())
	{
		try {
			$where = 1;
			if ($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminDesignation']<$data['designationID']) {
				$this->getParents($_SESSION['AdminLoginID']); //print_r($this->_parentIDs);die;
				$where = 'parent_id IN ('.implode(',',array_unique($this->_parentIDs)).')';
			}
			else if ($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminDesignation']>$data['designationID']) {
				$loginUserDetails = $this->getUserParents($_SESSION['AdminLoginID']);
				$loginUserDetail = array_filter(explode(",",$loginUserDetails));
				$where = 'user_id IN ('.implode(',',array_unique($loginUserDetail)).')';
			}
			
			$query = $this->_db->select()
							->from('employee_personaldetail',array('user_id','first_name','last_name','employee_code'))
							->where($where)
							->where('designation_id=?',$data['designationID'])
							->where("user_status='1' AND delete_status='0'")
							->order('first_name','ASC'); //echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getParents($loggedIn)
	{
		$this->_parentIDs[] = $loggedIn;
		
		$query = $this->_db->select()->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		$results = $this->getAdapter()->fetchAll($query);
		
		if (count($results) > 0) {
			foreach($results as $key=>$child) {
				$countChild = $this->countChild($child['user_id']);
				if($countChild['CNT'] > 0) {
					$this->_parentIDs[] = $child['user_id'];
					$this->getParents($child['user_id']);
				}
				else {
				   	$this->_parentIDs[] = $child['user_id'];
				}
			}
		}
	}
	
	public function countChild($loggedIn)
	{
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('CNT'=>'count(1)'))
				 ->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
	}
	
	public function getUserHeadquarter($userID)
	{
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))
				 ->joininner(array('ELT'=>'emp_locations'),"ELT.user_id=EPT.user_id",array('ELT.headquater_id'))
				 ->where("EPT.user_id =".$userID." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
	}
	
	public function getHeadquarters($loggedIn)
	{
		$userLocation = $this->getUserHeadquarter($loggedIn);
		$this->_userIDs[] 	   = $userLocation['user_id'];
		$this->_headquarters[] = $userLocation['headquater_id'];
		
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))
				 ->joininner(array('ELT'=>'emp_locations'),"ELT.user_id=EPT.user_id",array('ELT.headquater_id'))
				 ->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		$results = $this->getAdapter()->fetchAll($query);
		
		if (count($results) > 0) {
			foreach($results as $key=>$child) {
				$countChild = $this->countChild($child['user_id']);
				if($countChild['CNT'] > 0) {
					$this->_userIDs[] 	   = $child['user_id'];
					$this->_headquarters[] = $child['headquater_id'];
					$this->getHeadquarters($child['user_id']);
				}
				else {
				   	$this->_userIDs[] 	   = $child['user_id'];
					$this->_headquarters[] = $child['headquater_id'];
				}
			}
		}
		$this->_headquarters = array_filter($this->_headquarters);
	}
	
	public function getHeadquarterLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			$query = $this->_db->select()->from('headquater',array('headquater_id','headquater_name','location_code'))->where($where)->order('headquater_name','ASC'); 
			//echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getPatchLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			$query = $this->_db->select()->from('patchcodes','*')->where($where)->where("isActive='1'")->order('patch_name','ASC'); //echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getDoctorLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			$query = $this->_db->select()->from('crm_doctors','*')->where($where)->where("isActive='1'")->order('doctor_name','ASC'); //echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getChemistLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			$query = $this->_db->select()->from('crm_chemists','*')->where($where)->where("isActive='1'")->order('chemist_name','ASC');
			//echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getStockistLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			$query = $this->_db->select()->from('crm_stockists','*')->where($where)->where("isActive='1'")->order('stockist_name','ASC');
			//echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getChemistStockistLists($data=array())
	{
		try {
			$where = 1;
			if ($_SESSION['AdminLevelID'] != 1) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'CT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			$query = $this->_db->select()
					 ->from(array('CT'=>'crm_chemists'),array())
					 ->joininner(array('ST'=>'crm_chemist_stockists'),"ST.chemist_id=CT.chemist_id",array('ST.stockist_id','ST.stockist_name'))
					 ->where($where)->where("isActive='1'")->order('ST.stockist_name','ASC'); //echo $query->__toString();die;
			
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getActivityLists($data=array())
	{
		try {
			//$query = $this->_db->select()->from('crm_activity','*')->where("isActive='1'")->order('activity_name','ASC');
			$query = $this->_db->select()->from('app_activities','*')->order('activity_name','ASC');
			//echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getProductLists($data=array())
	{
		try {
			$query = $this->_db->select()->from('crm_products','*')->where("isActive='1'")->order('product_name','ASC'); //echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	public function getMeetingTypeLists($data=array())
	{
		try {
			$query = $this->_db->select()->from('app_meetingtype','*')->order('type_name','ASC'); //echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 
		}
	}
	
	/**
	 * Old Function
	**/

  public function getSalaryTemplateAmount(){

    $result = array();

	   $select = $this->_db->select()

		 				->from('salary_template',array('*'))

						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 

								 AND designation_id ='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result;

  }

  public function getSalaryEmployee(){

      $select = $this->_db->select()

		 				->from('employee_salary_amount',array('*'))
						->joinleft(array('SH'=>'salary_head'),'SH.salaryhead_id=employee_salary_amount.salaryhead_id',array())
						->where("user_id='".$this->_getData['user_id']."'")
						->order("employee_salary_amount.salaryheade_type ASC")
						->order("sequence ASC");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;

  }

  public function getAmountByTemplateId($template_id,$head_id){

       $select = $this->_db->select()

		 				->from('salary_template_amount',array('*'))

						->where("salary_template_id='".$template_id."' AND salaryhead_id ='".$head_id."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result['amount'];

  }

  public function getLeaveDetail(){

    if(!empty($this->_getData['user_id'])){

	   $select = $this->_db->select()

		 				->from(array('LD'=>'emp_leaves'),array('leave_id as leave_type_id','no_of_leave as leave_no','aproval_authority','user_id'))

						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_id",array('typeName'))

						->where("user_id='".$this->_getData['user_id']."'");

	   $result = $this->getAdapter()->fetchAll($select);

	}

	if(empty( $result)){
	  /*$sixmonthback = strtotime(date('Y-m-d',mktime(0, 0, 0, date("m")-6,date('d'),  date("Y"))));
	  $joiningdate =  strtotime($this->_getData['doj']);
		
		$year1 = date('Y', $sixmonthback);
		$year2 = date('Y', $joiningdate);
		
		$month1 = date('m', $sixmonthback);
		$month2 = date('m', $joiningdate);
		
	  $diff = (($year1-$year2) * 12) + ($month1 - $month2);*/
	  //print_r($diff);die;
      if($this->_getData['emp_type']==2){
      $select = $this->_db->select()
		 				->from(array('LD'=>'leavedistributions'),array('designation_id','leave_type_id','prob_leaveno as leave_no'))
						->joinleft(array('LP'=>'leaveapprovals'),"LP.designation_id=LD.designation_id",array('approval_no'))
						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_type_id",array('typeName'))
						->where("LD.designation_id='".$this->_getData['designation_id']."'");
      }else{
	      $select = $this->_db->select()
		 				->from(array('LD'=>'leavedistributions'),array('*'))
						->joinleft(array('LP'=>'leaveapprovals'),"LP.designation_id=LD.designation_id",array('approval_no'))
						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_type_id",array('typeName'))
						->where("LD.designation_id='".$this->_getData['designation_id']."'");
	  }
						//echo $select->__toString();die;

	    $result = $this->getAdapter()->fetchAll($select);					

	 } 

	

	  return $result;

  }
  public function getParentdesig($desgnation_id){
       $select = $this->_db->select()
		 				->from(array('DT'=>'designation'),array('*'))
						->where("DT.designation_id='".$desgnation_id."'");
						//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchRow($select); 
	   if(!empty($result) && $result['designation_id']>1){
		   array_push($this->_getData['Desig'],$result['designation_level']);
	     return $this->getParentdesig($result['designation_level']);
	   }else{
	     return $this->_getData['Desig'];
	   }
  }

 public function getParnetByDesignationId(){
 		$this->_getData['Desig'] = array() ;
         $designations = $this->getParentdesig($this->_getData['designation_id']);//print_r($designations);die;
           $select = $this->_db->select()
		 				->from(array('DTD'=>'designation_to_department'),array('*'))
						->where("DTD.designation_id='".$this->_getData['designation_id']."'");
		$department = $this->getAdapter()->fetchRow($select);
        // print_r($designations);print_r($result);die;
	 if(!empty($designations)){
	 $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name','user_id'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->where("ED.designation_id IN(".implode(',',$designations).")  AND ED.delete_status='0'")
						->order("ED.designation_id ASC");
					//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;
	 }else{
	   return array();
	 }  

 } 
 public function getParnetByDesignationId_old(){

     $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('*'))
						->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joininner(array('DTD'=>'designation_to_department'),"DTD.department_id=ED.department_id",array(''))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())

						->where("DT1.designation_level>DT.designation_level  AND ED.delete_status='0' AND DTD.designation_id='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;

 } 

 public function ChangeStatusValue(){ 

    $this->_db->update($this->_getData['table'],array($this->_getData['change_fild']=>$this->_getData['changeMode']),"".$this->_getData['confield']."=".$this->_getData['con_id']."");

	echo $this->_getData['changeMode'];

	exit;

 }
 public function getUserListByDesignation(){
			$select = $this->_db->select()
								->from(array('ED'=>'employee_personaldetail'),array('*'))
								->where("designation_id='".$this->_getData['designation_id']."' AND department_id='".$this->_getData['department_id']."'");
								//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchAll($select);
		$string = '<option value="">--Select--</option>';
		foreach($result as $users){
		  $selected = '';
		  if($this->_getData['user_id']==$users['user_id']){
		   $selected = 'selected="selected"';
		  }
		  $string .= '<option value="'.$users['user_id'].'" '.$selected.'>'.$users['employee_code'].'-'.$users['first_name'].' '.$users['last_name'].'</option>';
		}
	  echo $string;exit;		
 } 
  public function geteXtraHead(){
    $string = '';
   if(!empty($this->_getData['salaryhead'])){
    $string ='<tr class="odd"><td>'. $this->getSalaryHeadName($this->_getData['salaryhead']).'</td><td><input type="text" name="extra_amount[]" class="input-short"><input type="hidden" name="extra_head[]" value="'.$this->_getData['salaryhead'].'"><input type="hidden" name="extra_type[]" value="'.$this->_getData['type'].'"></td></tr>';
  }
	echo $string;exit;
 } 
 
  public function getExpenseHead(){
    if(!empty($this->_getData['exp_setting_id'])){
		$select = $this->_db->select()
									->from(array('ETA'=>'expense_template_amount'),array('*'))
									->joinleft(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))
									->where("exp_setting_id='".$this->_getData['exp_setting_id']."'"); 
	 }else{
	    $select = $this->_db->select()
									->from(array('EH'=>'expense_head'),array('*'))
									->where("expense_type='".$this->_getData['expense_type']."'");  
	 }							
								//echo $select->__toString();die;
	 $results = $this->getAdapter()->fetchAll($select);
	 $string = '';
	 if(!empty($results)){
	   foreach($results as $heads){
	      $string .= '<tr class="odd"><td><strong>'.$heads['head_name'].'</strong><input type="hidden" name="head_id[]" value="'.$heads['head_id'].'"></td><td><input type="text" name="expense_amount[]" value="'.$heads['expense_amount'].'" class="input-short"></td></tr>';
	   }
	 }
	 echo $string;exit();
  }
  
  public function getExpenseTemplate(){
    if(!empty($this->_getData['user_id'])){
      $select = $this->_db->select()
		 				->from(array('EEA'=>'emp_expense_amount'),array('*'))
						->joinleft(array('EH'=>'expense_head'),"EH.head_id=EEA.head_id",array('head_name'))
						->where("user_id='".$this->_getData['user_id']."'");
	  $result = $this->getAdapter()->fetchAll($select);
	  }//print_r($this->_getData['user_id']);print_r($result);die;
	 if(empty($this->_getData['user_id']) || empty($result)){
	   $select = $this->_db->select()
		 				->from(array('ES'=>'expense_setting'),array('*'))
						->joinleft(array('ETA'=>'expense_template_amount'),"ES.exp_setting_id=ETA.exp_setting_id",array('*'))
						->joinleft(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))
						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 
								 AND designation_id ='".$this->_getData['designation_id']."'");
	  $result = $this->getAdapter()->fetchAll($select); 
	 } 
	 $string = '';
	 foreach($result as $expenses){
	    $string .= '<tr><td>'.$expenses['head_name'].'<input type="hidden" name="head_id[]" value="'.$expenses['head_id'].'"></td><td><input type="text" name="expense_amount[]" value="'.$expenses['expense_amount'].'" class="input-short"></td></tr>';
	 }
	 $string .= '<tr><td colspan="2"><table id="expenseauthority"><tr><th colspan="2">Expense Approval Authority</th></tr>';
	 $approval = $this->getNumberOfApprovalExpense();
	 $getapprovals = $this->getUsersExpenseApproval();
	 $total_app = ($approval>0)?$approval:3;
	 if(!empty($getapprovals)){
	    foreach($getapprovals as $getapproval){
		   $rest  = $total_app-1;
		   $parent = $this->expenseApproveAuthority($getapproval['parent_id'],$total_app,$rest,$getapproval['approval_user_id'],$getapproval['position']);
		   $string .= $parent[0];
		}   
	 }else{
	      $parent = $this->expenseApproveAuthority($this->_data['parent_id'],$total_app,$total_app-1,0,0);
	 	  $string .= $parent[0];
	 }
	 $string .= '</table>';
	 
	 $string .= '<table><th colspan="2">Extra Expense Head</th>';
	$string .= '<tr><td><select name="head_id[]" id="head_id" onchange="getExpenseAmount(this.value);" class="input-medium">';
	$string .=  '<option value="">--Select Site--</option>';
	$allexpensehead = $this->getAllExpenseHead();
    foreach($allexpensehead as $i=>$expenses){
		$string .='<option value="'.$expenses['head_id'].'">'.$expenses['head_name'].'</option>';
	 }
	 $string .='</select></td><td><input name="expense_amount[]" id="expense_amount" class="input-medium"></td></tr></table></td></tr>';
	 
	echo $string;exit;   
  }
  
  /*public function expenseApproveAuthority($parent_id,$i=1){
     $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('*'))
						->joininner(array('DES'=>'designation'),"DES.designation_id=UD.designation_id",array('designation_name','designation_code'))						->where("user_id='".$parent_id."'");
	$result = $this->getAdapter()->fetchRow($select);
	$parnetlist = $this->getParnetByDesignationId();
   	if(!empty($result)){
     $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="'.$parent_id.'"></td><td>'.$result['first_name'].' '.$result['last_name'].'-'.$result['designation_code'].'</td></tr>';
	}elseif(!empty($parnetlist)){
	     $string = '<tr><td>Approval Authority '.$i.'</td><td><select name=expese_approve_auth[]>';
			 foreach($parnetlist as $parnet){
				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 
			 }
   		 $string .= '</select></td></tr>';
	}else{
	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="44"></td><td> Super Admin</td></tr>';
	}
	return array($string,$result['parent_id']);
  }*/
  
  public function expenseApproveAuthority($parent_id,$total,$rest,$parent,$position){
    $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('*'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())
						->where("DT1.designation_level>=DT.designation_id");
						//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);
   	if(!empty($result)){
	     $string = '<tr><td><select name=expese_approve_auth[]>';
		 $string .='<option value="">Select Approval Authority</option>';
			 foreach($result as $parnet){
			     $selected='';
			     if($parent==$parnet['user_id']){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 
			 }
   		  $string .= '</select></td>';
		  
		 $string .= '<td><select name=position[] onchange="getExpenseAuthority(this.value,'.$this->_getData['designation_id'].','.$total.','.$rest.')">';
		 $string .='<option value="">Select Approval Position</option>';
		 //$iposition = ($this->_data['currentvalue']>0)?$this->_data['currentvalue']:1; 
			 for($i=1;$i<$total;$i++){
			    $selected='';
			     if($i==$position){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$i.'" '.$selected.'>Approval Authority'.$i.'</option>'; 
			 }
		 $selected='';
		  if($total==$position){
		   $selected = 'selected="selected"'; 
		 }
		 $string .='<option value="'.$total.'" '.$selected.'>Final Approval Authority</option>';	 
   		 $string .= '</select></td></tr>';
	}else{
	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="44"></td><td>Supper Admin</td></tr>';
	}
	return array($string,$result['parent_id']);
  }
  
  public function getUserExpenseAmount(){
       $select = $this->_db->select()
	  			->from(array('EEA'=>'emp_expense_amount'),array('*'))
				->joininner(array('PD'=>'employee_personaldetail'),"PD.user_id=EEA.user_id",array('expense_type AS emp_etype'))
				->joininner(array('EH'=>'expense_head'),"EH.head_id=EEA.head_id",array('expense_type AS orig_etype'))
				->where("EEA.user_id='".$_SESSION['AdminLoginID']."' AND EEA.head_id='".$this->_getData['head_id']."'");
				//echo $select->__toString();die;
	   $amount =  $this->getAdapter()->fetchRow($select);
	   $exxpensetype = 'F';
	   if($amount['orig_etype']==$amount['emp_etype'] && $amount['emp_etype']==3){
	     $exxpensetype = 'T';
	   }
	   echo $amount['expense_amount'].'^'.$exxpensetype;exit();
  }
  
  public function leaaveApproveAuthority($parent_id,$total,$rest,$parent,$position){
     $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('*'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())
						->where("DT1.designation_level>=DT.designation_id");
						//echo $select->__toString();die;
	   $result = $this->getAdapter()->fetchAll($select);
   	if(!empty($result)){
	     $string = '<tr><td><select name=leave_approve_auth[]>';
		 $string .='<option value="">Select Approval Authority</option>';
			 foreach($result as $parnet){
			     $selected='';
			     if($parent==$parnet['user_id']){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 
			 }
   		  $string .= '</select></td>';
		  
		 $string .= '<td><select name=authority_position[] onchange="getAuthority(this.value,'.$this->_getData['designation_id'].','.$total.','.$rest.')">';
		 $string .='<option value="">Select Approval Position</option>';
		 //$iposition = ($this->_data['currentvalue']>0)?$this->_data['currentvalue']:1; 
			 for($i=1;$i<$total;$i++){
			    $selected='';
			     if($i==$position){
				   $selected = 'selected="selected"'; 
				}
				$string .='<option value="'.$i.'" '.$selected.'>Approval Authority'.$i.'</option>'; 
			 }
		 $selected='';
		  if($total==$position){
		   $selected = 'selected="selected"'; 
		 }
		 $string .='<option value="'.$total.'" '.$selected.'>Final Approval Authority</option>';	 
   		 $string .= '</select></td></tr>';
	}else{
	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="leave_approve_auth[]" value="44"></td><td>Supper Admin</td></tr>';
	}
	return array($string,$result['parent_id']);
  }
  public function getNumberOfApprovalLeave(){
     $select = $this->_db->select()
		 				->from(array('LA'=>'leaveapprovals'),array('*'))
						->where("designation_id	='".$this->_getData['designation_id']."'");
	 $result = $this->getAdapter()->fetchRow($select);
	 return $result['approval_no'];
  }
  public function getNumberOfApprovalExpense(){
    $select = $this->_db->select()
		 				->from(array('ES'=>'expense_setting'),array('*'))
						->where("designation_id ='".$this->_getData['designation_id']."'");
	  $result = $this->getAdapter()->fetchRow($select); 
	 return $result['number_of_approval'];
  }
 
 public function getUsersleaveApproval(){
  	$select = $this->_db->select()
		 				->from(array('ELA'=>'emp_leave_approval'),array('*','approval_user_id as parent_id'))
						->where("user_id='".$this->_getData['user_id']."'");
	  $result = $this->getAdapter()->fetchAll($select); 
	 return $result;
  }
  
 public function getUsersExpenseApproval(){
 	$select = $this->_db->select()
		 				->from(array('ELA'=>'expense_approval'),array('*','approval_user_id as parent_id'))
						->where("user_id='".$this->_getData['user_id']."'");
	  $result = $this->getAdapter()->fetchAll($select); 
	 return $result;
 }
 public function updateEmpExpense(){
   $this->_db->update('emp_expenses',array('head_id'=>$this->_getData['head_id'],'expense_amount'=>$this->_getData['expense_amount'],'expense_detail'=>$this->_getData['expense_detail'],'expense_date'=>$this->_getData['expense_date']),"expense_id='".$this->_getData['expense_id']."'");
   return true;
   $_SESSION[SUCCESS_MSG]= 'Expense Updated Successfully';
 }
  public function getRecordrecordOfLowerEmp(){
     if($this->_getData['Level']==1){
	 $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array(''))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array(''))
						->joinleft(array('DT1'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name as Value','designation_id AS Key'))
						->where("DT1.designation_level>=DT.designation_level  AND ED.user_id='".$_SESSION['AdminLoginID']."'");
						
	  }
	 $results = $this->getAdapter()->fetchAll($select); 
	  $string = '<option value="">---Select--</option>';
		 if($results){
			 foreach($results as $Record){
				$selected = '';
				if($this->_data['Key']==$Record['Key']){
				  $selected = 'selected="selected"';
				}
				$string .='<option value="'.$Record['Key'].'" '.$selected.'>'.$Record['Value'].'</option>'; 
			 }
	     }
		 return $string;
     }
    public function deletesalaryhead(){
        $this->_db->delete("salary_list","salaryhead_id='".$this->_getData['salaryhead_id']."' AND user_id='".$this->_getData['user_id']."' AND date='".$this->_getData['date']."'");
        return 'Salary Head has been deleted successfully';exit;
    }
}

?>