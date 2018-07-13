<?php

class Zend_Custom_Class extends Zend_Db_Table_Abstract {

    

	public $_recordData = array();

	public $_table = array('1'=>'admin_detail','2'=>'employee_personaldetail');

	


	public function AdminModuleAndSubModule($parent_id = 0){

		if($_SESSION['AdminLoginID']==1 || $_SESSION['AdminLoginID']==44){

	   		$where .= " AND isAdmin='1'";

	 	}else{

	   		$where .= " AND isUser='1'"; 

	 	}

      	$select = $this->_db->select()

		 				->from('crm_modules',array('*'))

						->where("parent_id='".Bootstrap::$_parent."' AND level_id='".Bootstrap::$_level."'")

						->where("isActive='1'"); //echo $select->__toString();die;

	  	$result = $this->getAdapter()->fetchAll($select);

	  	return $result;

    }

	

	/**

     * Get Modules

     * Function : getModules()

     * Get modules

     * */

	public function getModules($parent_id=0){ 

		if($_SESSION['AdminLoginID']==1 || $_SESSION['AdminLoginID']==44){

		   $where .= " AND isAdmin='1'";

		 }else{

		   $where .= " AND isUser='1'"; 

		 }

		$select = $this->_db->select()

						->from('crm_modules',array('*'))

						->where("isActive='1'")

						->where("parent_id='".$parent_id."'"); //echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	}

	

		/**

		 *

		 **/

		public function checkPrivileged($data=array()) {

			$menuPrivilege = Bootstrap::$menuPrivilege;

			$userID 	 = (isset($_SESSION['AdminLoginID'])) ? trim($_SESSION['AdminLoginID']) : 0;

			$controllers = (isset($menuPrivilege['Controllers'])) ? $menuPrivilege['Controllers'] : array();

			$actions 	 = (isset($menuPrivilege['Actions'])) 	 ? $menuPrivilege['Actions'] 	: array();

			$controller  = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();

			$action 	 = Zend_Controller_Front::getInstance()->getRequest()->getActionName();  //echo "<pre>";print_r($action);die;

			

			if($userID != 1 && $userID != 44) {

				if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest')) {

					return TRUE;

				}

				else {

					if((!in_array(strtolower($controller),$controllers,TRUE)) || ($action != 'index' && !in_array($action,$actions[strtolower($controller)],TRUE))) {

						$_SESSION[ERROR_MSG] = "You don't have privilege to access this module, please contact to administrator!!";

		    			header("Location:".Bootstrap::$baseUrl.'Dashboard/');exit;

					}

					else {

						return TRUE;

					}

				}

			}

			else {

				return TRUE;

			}

		}

		

		/**

		 *

		 **/

		public function HeaderMenuItems() {

			$userID = (isset($_SESSION['AdminLoginID'])) ? trim($_SESSION['AdminLoginID']) : 0;			

			return ($userID==1 || $userID==44) ? $this->getSuperUserPrivileges() : $this->getUserPrivileges($userID);

		}

	

		/**

		 *

		 **/

		public function getSuperUserPrivileges() {

			$query = $this->_db->select()

				 ->from('crm_modules',array('module_id','parent_id','module_name','module_title','module_controller','module_action','module_icon'))

				 ->where('isActive="1"')

				 ->where('isDelete="0"')

				 ->order("set_order ASC"); //echo $query->__toString();die;

				 

			$allModules = $this->getAdapter()->fetchAll($query);

			$modules = array(); $moduleController = array(); $moduleAction = array(); $moduleTitle = array();

			

			if(count($allModules)>0) {

				foreach($allModules as $key=>$info) {

					if($info['parent_id'] == 0) {

						$modules[$key]['name'] 		 = $info['module_name'];

						$modules[$key]['controller'] = strtolower($info['module_controller']);

						$modules[$key]['action'] 	 = strtolower($info['module_action']);

						$modules[$key]['icon'] 	 	 = strtolower($info['module_icon']);

						$modules[$key]['submodule']  = $this->getSuperSubmoduleAccess(array('Module'=>$info['module_id']));

					}

					

					$moduleTitle[$info->module_controller] = ucfirst($info['module_title']);

					$moduleController[] = strtolower($info['module_controller']);

					$moduleAction[$info['module_controller']] = $this->getSuperModuleAction(array('Module'=>$info['module_id']));

				}

			}

			

			return array('Names'=>$modules,'Controllers'=>$moduleController,'Titles'=>$moduleTitle,'Actions'=>$moduleAction);

		}		

		

		/**

		 *

		 **/

		public function getSuperSubmoduleAccess($data=array()) {

			$Module = (isset($data['Module'])) ? trim($data['Module']) : 0;

			

			$query = $this->_db->select()

					 ->from(array('MT'=>'crm_modules'),array('MT.module_name','MT.module_controller','MT.module_action'))

					 ->where('MT.parent_id='.(int)$Module)

					 ->where('MT.isActive="1"')

					 ->where('MT.isDelete="0"')

					 ->order("MT.set_order ASC"); //echo $query->__toString();die;

			

			return $this->getAdapter()->fetchAll($query);

		}

		

		/**

		 *

		 **/

		public function getSuperModuleAction($data=array()) {

			$Module = (isset($data['Module'])) ? trim($data['Module']) : 0;

			

			$query = $this->_db->select()

					 ->from(array('MS'=>'crm_modulesections'),array(''))

					 ->distinct()

					 ->joininner(array('ST'=>'crm_sections'),"ST.section_id=MS.section_id",array('ST.section_name'))

					 ->where('MS.module_id='.(int)$Module)

					 ->where('ST.status="1"')

					 ->where('ST.delete_status="0"')

					 ->order("ST.section_id ASC"); //echo $query->__toString();die;

			

			$allActions = $this->getAdapter()->fetchAll($query);

			$actions = array();

			if(count($allActions)>0) {

				foreach($allActions as $action) {

					$actions[] = strtolower(str_replace(' ','',$action['section_name']));

				}

			}

			return $actions;

		}

		

		/**

		 *

		 **/

		public function getUserPrivileges($UserID) {

			$query = $this->_db->select()

				 ->from(array('UP'=>'crm_userprivileges'),array(''))

				 ->distinct()

				 ->joininner(array('MT'=>'crm_modules'),"MT.module_id=UP.module_id",array('MT.module_id','MT.parent_id','MT.module_name', 'MT.module_title','MT.module_controller', 'MT.module_action','MT.module_icon'))

				 ->where('UP.user_id='.(int)$UserID)

				 ->where('MT.isActive="1"')

				 ->where('MT.isDelete="0"')

				 ->order("MT.set_order ASC"); //echo $query->__toString();die;

				 

			$allModules = $this->getAdapter()->fetchAll($query); //echo "<pre>";print_r($allModules);echo "</pre>";die;

			$modules = array(); $moduleController = array(); $moduleAction = array(); $moduleTitle = array();

			

			if(count($allModules)>0) {

				foreach($allModules as $key=>$info) {

					if($info['parent_id'] == 0) {

						$modules[$key]['name'] 		 = $info['module_name'];

						$modules[$key]['controller'] = strtolower($info['module_controller']);

						$modules[$key]['action'] 	 = strtolower($info['module_action']);

						$modules[$key]['icon'] 	 	 = strtolower($info['module_icon']);

						$modules[$key]['submodule']  = $this->getSubmoduleAccess(array('UserID'=>$UserID,'Module'=>$info['module_id']));

					}

					

					$moduleTitle[$info->module_controller] = ucfirst($info['module_title']);

					$moduleController[] = strtolower($info['module_controller']);

					$moduleAction[$info['module_controller']] = $this->getModuleAction(array('UserID'=>$UserID,'Module'=>$info['module_id']));

				}

			}

			

			return array('Names'=>$modules,'Controllers'=>$moduleController,'Titles'=>$moduleTitle,'Actions'=>$moduleAction);

		}

		

		/**

		 *

		 **/

		public function getSubmoduleAccess($data=array()) {

			$UserID = (isset($data['UserID'])) ? trim($data['UserID']) : 0;

			$Module = (isset($data['Module'])) ? trim($data['Module']) : 0;

			

			$query = $this->_db->select()

					 ->from(array('UP'=>'crm_userprivileges'),array(''))

					 ->distinct()

					 ->joininner(array('MT'=>'crm_modules'),"MT.module_id=UP.module_id",array('MT.module_name','MT.module_controller','MT.module_action'))

					 ->where('UP.user_id='.(int)$UserID)

					 ->where('MT.parent_id='.(int)$Module)

					 ->where('MT.isActive="1"')

					 ->where('MT.isDelete="0"')

					 ->order("MT.set_order ASC"); //echo $query->__toString();die;

			

			return $this->getAdapter()->fetchAll($query);

		}

		

		/**

		 *

		 **/

		public function getModuleAction($data=array()) {

			$UserID = (isset($data['UserID'])) ? trim($data['UserID']) : 0;

			$Module = (isset($data['Module'])) ? trim($data['Module']) : 0;

			

			$query = $this->_db->select()

					 ->from(array('UP'=>'crm_userprivileges'),array(''))

					 ->distinct()

					 ->joininner(array('MS'=>'crm_modulesections'),"MS.module_id=UP.module_id",array(''))

					 ->joininner(array('ST'=>'crm_sections'),"ST.section_id=UP.section_id",array('ST.section_name','ST.show_position'))

					 ->where('UP.user_id='.(int)$UserID)

					 ->where('UP.module_id='.(int)$Module)

					 ->where('ST.status="1"')

					 ->where('ST.delete_status="0"')

					 ->order("ST.section_id ASC"); //echo $query->__toString();die;

			

			$allActions = $this->getAdapter()->fetchAll($query);

			$actions = array();

			if(count($allActions)>0) {

				foreach($allActions as $action) {

					$actions[] = strtolower(str_replace(' ','',$action['section_name']));

				}

			}

			return $actions;

		}

	

   /**

     * Insert nto Table

     * Function : insertInToTable()

     * Insert data into table.

     * */

    public function insertInToTable($tablename, $insertdata) {

        try {

            $columns = $this->columnName($tablename);

            for ($i = 0; $i < count($insertdata); $i++) {

                foreach ($columns as $key => $value) {

                    if (array_key_exists($value, $insertdata[$i])) {

                        $isertArr[$value] = trim(preg_replace('/\s+/', ' ', str_replace('"', '', $insertdata[$i][$value])));

                    }

                }

                $this->_db->insert($tablename, $isertArr);

            }

        } catch (Exception $e) {

            $_SESSION[ERROR_MSG] = $e->getMessage();

        }

        return $this->getAdapter()->lastInsertId(); //true;

    }



    /**

     * Insert nto Table

     * Function : insertInToTable()

     * Insert data into table.

     * */

    public function updateTable($tablename, $updatedata, $where) {

        $columns = $this->columnName($tablename);

        $whereClause = '';

        $whereClause = " 1 ";

        foreach ($columns as $key => $value) {

            if (array_key_exists($value, $updatedata)) {

                $updateArr[$value] = trim(preg_replace('/\s+/', ' ', str_replace('"', '', $updatedata[$value])));

            }

            if (array_key_exists($value, $where)) {

                $whereClause .= "AND " . $value . "='" . $where[$value] . "'";

            }

        }

        $this->_db->update($tablename, $updateArr, $whereClause);

        return;

    }

	

	

	/**

     * Column Name List

     * Function : columnName()

     * Fetch All the column Name of specific table.

     * */

    public function columnName($tablename) {

        try {

            $column = $this->_db->describeTable($tablename);

            $columnNames = array_keys($column);

        } catch (Exception $e) {

            $_SESSION[ERROR_MSG] = $e->getMessage();

        }

        return $columnNames;

    }

	

	/**

     * Column Name List

     * Function : getBusinessToCompany()

     * Fetch All the column Name of specific table.

     * */

    public function getBusinessToCompany(){

	    $select = $this->_db->select()

		 				->from(array('B2C'=>'bussiness_to_comapny'),array('*'))

		 				->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=B2C.bunit_id",array('bunit_name'))

						->joininner(array('CT'=>'company'),"CT.company_code=B2C.company_code",array('company_name'));

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	/**

     * Column Name List

     * Function : getDepartmentToBusinessUnit()

     * Fetch All the column Name of specific table.

     * */

	public function getDepartmentToBusinessUnit(){

	    $select = $this->_db->select()

		 				->from(array('D2B'=>'department_to_bunit'),array('*'))

		 				->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=D2B.bunit_id",array('bunit_name'))

						->joininner(array('DT'=>'department'),"DT.department_id=D2B.department_id",array('department_name'));

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	/**

     * Column Name List

     * Function : getDesignationToDepartment()

     * Fetch All the column Name of specific table.

     * */

	public function getDesignationToDepartment(){

	    $select = $this->_db->select()

		 				->from(array('D2D'=>'designation_to_department'),array('*'))

		 				->joininner(array('DEP'=>'department'),"DEP.department_id=D2D.department_id",array('department_name'))

						->joininner(array('DES'=>'designation'),"DES.designation_id=D2D.designation_id",array('designation_name'));

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	/**

     * Column Name List

     * Function : getBusinessToCountry()

     * Fetch All the column Name of specific table.

     * */

    public function getBusinessToCountry(){

       $select = $this->_db->select()

		 				->from(array('BUC'=>'business_to_country'),array('*'))

		 				->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=BUC.bunit_id",array('bunit_name'))

						->joininner(array('CT'=>'country'),"CT.country_id=BUC.country_id",array('country_name'));

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

  }

     /**

     * Column Name List

     * Function : getBusinessToCountry()

     * Fetch All the column Name of specific table.

     * */

     public function getAllUsersForSalary(){

	     $select = $this->_db->select()

		 				->from(array('UT'=>'user_detail'),array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'));

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	 /**

     * Column Name List

     * Function : getAjaxData()

     * Fetch All the column Name of specific table.

     * */

	 public function getAjaxData($table,$condition){

		$select = $this->_db->select()

						->from($table,array('*'))

						->where("".$condition."='".$this->_getData['id']."'");

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	}

	 /**

     * Column Name List

     * Function : getDesignation()

     * Fetch All the column Name of specific table.

     * */

	public function getDesignation(){

	    $select = $this->_db->select()

						->from('designation',array('*'))

						->joinleft(array('PD'=>'designation'),"PD.designation_id=designation.designation_level",array('designation_name as parentdesig'))

                                                ->order("designation.serial_no ASC");

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	 }

	  /**

     * Column Name List

     * Function : getDepartment()

     * Fetch All the column Name of specific table.

     * */

    public function getDepartment(){

	    $select = $this->_db->select()

						->from('department',array('*'));

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	 }

	 /**

     * Column Name List

     * Function : getSalaryhead()

     * Fetch All the column Name of specific table.

     * */

    public function getSalaryhead(){

	    $select = $this->_db->select()

						->from('salary_head',array('*'))

						->where("salary_type='1'")

						->order("sequence ASC");

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	 }

	/**

     * Column Name List

     * Function : getDetectionSalaryhead()

     * Fetch All the column Name of specific table.

     * */

    public function getDetectionSalaryhead(){

	    $select = $this->_db->select()

						->from('salary_head',array('*'))

						->where("salary_type='2'")

						->order("sequence ASC");

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	 } 

	 /**

     * Column Name List

     * Function : getCompany()

     * Fetch All the column Name of specific table.

     * */

	 public function getCompany(){

		    $select = $this->_db->select()

						->from('company',array('*'));

		     $result = $this->getAdapter()->fetchAll($select);

			return $result;  			

		}

	 /**

     * Column Name List

     * Function : getBissnessUnit()

     * Fetch All the column Name of specific table.

     * */	

	public function getBissnessUnit(){

		     $select = $this->_db->select()

						->from(array('BU'=>'bussiness_unit'),array('bunit_id','bunit_name'));

		     $result = $this->getAdapter()->fetchAll($select);

			return $result;  			

	}

	 /**

     * Column Name List

     * Function : gerCountryList()

     * Fetch All the column Name of specific table.

     * */

	public function gerCountryList(){

		    $select = $this->_db->select()

						->from('country',array('*'));

		     $result = $this->getAdapter()->fetchAll($select);

			return $result;  

	}

	 /**

     * Column Name List

     * Function : getSalaryHeadName()

     * Fetch All the column Name of specific table.

     * */

	 public function getSalaryHeadName($salaryhead_id){

	     $select = $this->_db->select()

						->from('salary_head',array('salary_title'))

						->where("salaryhead_id='".$salaryhead_id."'");

		     $result = $this->getAdapter()->fetchRow($select);

			return $result['salary_title']; 

	 }

	 /**

     * Convert number to word

     * Function : convert_number_to_words()

     * Function return after converting number to word.

     * */

	 public function convert_number_to_words($number) {

   

    $hyphen      = ' ';

    $conjunction = ' ';

    $separator   = ', ';

    $negative    = 'negative ';

    $decimal     = ' point ';

    $dictionary  = array(

        0                   => 'zero',

        1                   => 'one',

        2                   => 'two',

        3                   => 'three',

        4                   => 'four',

        5                   => 'five',

        6                   => 'six',

        7                   => 'seven',

        8                   => 'eight',

        9                   => 'nine',

        10                  => 'ten',

        11                  => 'eleven',

        12                  => 'twelve',

        13                  => 'thirteen',

        14                  => 'fourteen',

        15                  => 'fifteen',

        16                  => 'sixteen',

        17                  => 'seventeen',

        18                  => 'eighteen',

        19                  => 'nineteen',

        20                  => 'twenty',

        30                  => 'thirty',

        40                  => 'fourty',

        50                  => 'fifty',

        60                  => 'sixty',

        70                  => 'seventy',

        80                  => 'eighty',

        90                  => 'ninety',

        100                 => 'hundred',

        1000                => 'thousand',

        1000000             => 'Lakh',

        1000000000          => 'billion',

        1000000000000       => 'trillion',

        1000000000000000    => 'quadrillion',

        1000000000000000000 => 'quintillion'

    );

   

    if (!is_numeric($number)) {

        return false;

    }

   

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {

        // overflow

        trigger_error(

            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,

            E_USER_WARNING

        );

        return false;

    }



    if ($number < 0) {

        return $negative . convert_number_to_words(abs($number));

    }

   

    $string = $fraction = null;

   

    if (strpos($number, '.') !== false) {

        list($number, $fraction) = explode('.', $number);

    }

   

    switch (true) {

        case $number < 21:

            $string = $dictionary[$number];

            break;

        case $number < 100:

            $tens   = ((int) ($number / 10)) * 10;

            $units  = $number % 10;

            $string = $dictionary[$tens];

            if ($units) {

                $string .= $hyphen . $dictionary[$units];

            }

            break;

        case $number < 1000:

            $hundreds  = $number / 100;

            $remainder = $number % 100;

            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];

            if ($remainder) {

                $string .= $conjunction . convert_number_to_words($remainder);

            }

            break;

        default:

            $baseUnit = pow(1000, floor(log($number, 1000)));

            $numBaseUnits = (int) ($number / $baseUnit);

            $remainder = $number % $baseUnit;

            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];

            if ($remainder) {

                $string .= $remainder < 100 ? $conjunction : $separator;

                $string .= convert_number_to_words($remainder);

            }

            break;

    }

   

    if (null !== $fraction && is_numeric($fraction)) {

        $string .= $decimal;

        $words = array();

        foreach (str_split((string) $fraction) as $number) {

            $words[] = $dictionary[$number];

        }

        $string .= implode(' ', $words);

    }

   

    return $string;

}

 /**

     * Notification

     * Function : getNotification()

     *Notification

     * */

	 public function getNotification(){

	         $select = $this->_db->select()

						->from('notification',array('*'));

		     $result = $this->getAdapter()->fetchAll($select);

			return $result; 

	 }

   /**

     * Documents Type

     * Function : getDocumentsType()

     *Documents

     * */

	public function getDocumentsType($type_id=false){

	   $where = '';

	   if($type_id){

	       $where = " AND type_id='".$type_id."'";

	    }

       $select = $this->_db->select()

		 				->from(array('DFT'=>'document_file_type'),array('*'))

						->where("1".$where);

	   $doc_type = $this->getAdapter()->fetchAll($select);

	 return  $doc_type;  

	}

	

	/**

     * Get UserDetail

     * Function : getUserinfo()

     *Info

     **/

	public function getUserinfo($user_id,$user_type){// print_r($user_type);

	if($user_id!=1){

	  $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('*'))

						->joininner(array('DES'=>'designation'),"DES.designation_id=UD.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=UD.department_id",array('department_name'))

						->where("user_id='".$user_id."'");

						//echo $select->__toString();die;

	 }else{

	    $select = $this->_db->select()

		 				->from(array('UD'=>'admin_detail'),array('*','first_name as department_name'))

						->where("user_id='".$user_id."'");

						//echo $select->__toString();die;

	 }					

	  $detail = $this->getAdapter()->fetchRow($select); 

	   return $detail;

	}

	/**

     * Get Messages

     * Function : getMessagesforEmp()

     * Get All the messages of Emp

     **/

	public function getMessagesforEmp(){

	        $RecordArr =array();

	        $select = $this->_db->select()

		 				->from(array('NT'=>'notification'),array('*'))

						->where("(bunit_id='".$_SESSION['AdminBunit']."' AND department_id='".$_SESSION['AdminDepartment']."' 

								 AND designation_id='".$_SESSION['AdminDesignation']."') OR (bunit_id='".$_SESSION['AdminBunit']."' 

								 AND department_id='".$_SESSION['AdminDepartment']."' AND designation_id=0) OR (bunit_id='".$_SESSION['AdminBunit']."' 

								 AND department_id=0 AND designation_id=0)");

			//echo $select->__toString();die;

		$RecordArr =$this->getAdapter()->fetchAll($select);

	   //print_r($RecordArr);die;

	   return $RecordArr;

	}

	/**

     * Get Events

     * Function : getEventsforEmp()

     * Get All the Events of Emp

     **/

	public function getEventsforEmp(){

	   $RecordArr =array();

	        $select = $this->_db->select()

		 				->from(array('NT'=>'events'),array('*'))

						->where("(bunit_id='".$_SESSION['AdminBunit']."' AND department_id='".$_SESSION['AdminDepartment']."' 

								 AND designation_id='".$_SESSION['AdminDesignation']."') OR (bunit_id='".$_SESSION['AdminBunit']."' 

								 AND department_id='".$_SESSION['AdminDepartment']."' AND designation_id=0) OR (bunit_id='".$_SESSION['AdminBunit']."' 

								 AND department_id=0 AND designation_id=0)");

			//echo $select->__toString();die;

		$RecordArr =$this->getAdapter()->fetchAll($select);

	   //print_r($RecordArr);die;

	   return $RecordArr;

	}

	/**

     * Get Office Info

     * Function : getOfficeInfo()

     * Get All the offices

     **/

	public function getOfficeInfo(){

	    $select = $this->_db->select()

						->from('headoffice',array('*'));

		     $result = $this->getAdapter()->fetchAll($select);

			return $result; 

	} 

    /**

     * Get User Type

     * Function : getUserType()

     * Get User Type

     **/

	public function getUserType($user_id){

	   $select = $this->_db->select()

						->from('users',array('user_type'));

	   $result = $this->getAdapter()->fetchRow($select);

	  return $result['user_type']; 

	}

	/**

     * Get Employee Education

     * Function : getEducations()

     * Get All Informations of User's Educatons

     **/

	public function getEducations($user_id){

	   $select = $this->_db->select()

		 				->from(array('EE'=>'employee_education'),array('*'))

						->where("EE.user_id='".$user_id."'");

	   $education = $this->getAdapter()->fetchAll($select);

	  return $education; 

	}

	/**

     * Get Employee Employeement Detail

     * Function : getEmployeements()

     * Get Informations about Employeement

     **/

	public function getEmployeements($user_id){

	   $select = $this->_db->select()

		 				->from(array('ET'=>'emp_employeement_detail'),array('*'))

						->where("ET.user_id='".$user_id."'");

	   $employeement = $this->getAdapter()->fetchAll($select);

	  return $employeement; 

	}

	/**

     * Get Bank Details

     * Function : getBankAccountDetail()

     * Get Informations of Bank Account

     **/

	public function getBankAccountDetail($user_id){

	   $select = $this->_db->select()

		 				->from(array('AD'=>'emp_bank_account'),array('*'))

						->where("AD.user_id='".$user_id."'");

		 return $this->getAdapter()->fetchRow($select);

	}

	/**

     * Get Informations of Documents

     * Function : getDocumentsInfo()

     * Get Informations about Employee Documents

     **/

	public function getDocumentsInfo($user_id){

	   $select = $this->_db->select()

		 				->from(array('DT'=>'emp_docoments'),array('*'))

						->where("DT.user_id='".$this->_getData['user_id']."'");

		 return $this->getAdapter()->fetchAll($select);

	}

	/**

     * Get Informations of Salary Duration

     * Function : getSalaryDuration()

     * Duration In wich salary will be processing

     **/ 

	public function getSalaryDuration(){

	     $select = $this->_db->select()

		 				->from(array('SD'=>'salary_duration'),array('*'));

		 return $this->getAdapter()->fetchRow($select);

	}

	/**

     * Information of providend setting

     * Function : getMasterProvidentSetting()

     * Providend settings for salary processing

     **/ 

   public function getMasterProvidentSetting(){

        $select = $this->_db->select()

		 				->from(array('PS'=>'provident_setting'),array('*'));

		 return $this->getAdapter()->fetchRow($select);

   }

   

   /**

     * Information of ESI setting

     * Function : getMasterEsiSettings()

     * ESI settings for salary processing

     **/ 

   public function getMasterEsiSettings(){

        $select = $this->_db->select()

		 				->from(array('ES'=>'esi_setting'),array('*'));

		 return $this->getAdapter()->fetchRow($select);

   }

   /**

     * Location info

     * Function : getlocationInfo()

     * Employee belongs to pericular location and headquater

     **/ 

   public function getlocationInfo($user_id){

      $select = $this->_db->select()

		 				->from(array('UL'=>'emp_locations'),array('*'))

						->where("user_id='".$user_id."'");

		 return $this->getAdapter()->fetchRow($select);

    }

	/**

     * Current user Info

     * Function : logedInuserInfo()

     * The information about current loged in user

     **/ 

  public function logedInuserInfo(){

	    $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('*'))

						->where("user_id='".$_SESSION['AdminLoginID']."'");

						//print_r($select->__toString());die;

		 return $this->getAdapter()->fetchRow($select);

	}

   /**

     * Expense Head of user

     * Function : getUserExpenseHead()

     * Return ALl the head of user

     **/	

   public function getUserExpenseHead(){

	  $select = $this->_db->select()

	  			->from(array('EEA'=>'emp_expense_amount'),array('*'))

				->joinleft(array('EH'=>'expense_head'),"EEA.head_id=EH.head_id",array('head_name'))

				->where("user_id='".$_SESSION['AdminLoginID']."'");

				//echo $select->__toString();die;

	   return $this->getAdapter()->fetchAll($select);

	}

 /**

 * Expense Head All

 * Function : getAllExpenseHead()

 * Return ALl Expense Head

 **/	

   public function getAllExpenseHead(){

	  $select = $this->_db->select()

	  			->from(array('EEA'=>'expense_head'),array('*'));

				//echo $select->__toString();die;

	   return $this->getAdapter()->fetchAll($select);

	}			

	/**

     * Convert Number Into

     * Function : ConvertToWords()

     * The Function Change the number into words

     **/

  public function ConvertToWords($n, $followup='Yes')

	{

	

	if($n==0)

	{

	if($followup=='no')

	{

	return "";

	exit();

	}

	else

	{

	return "zero";

	exit();

	}

	}

	switch($n)

	{

	case 1: return "one"; break;

	case 2: return "two"; break;

	case 3: return "three"; break;

	case 4: return "four"; break;

	case 5: return "five"; break;

	case 6: return "six"; break;

	case 7: return "seven"; break;

	case 8: return "eight"; break;

	case 9: return "nine"; break;

	case 10: return "ten"; break;

	case 11: return "eleven"; break;

	case 12: return "twelve"; break;

	case 13: return "thirteen"; break;

	case 14: return "fourteen"; break;

	case 15: return "fifteen"; break;

	case 16: return "sixteen"; break;

	case 17: return "seventeen"; break;

	case 18: return "eighteen"; break;

	case 19: return "nineteen"; break;

	case 20: return "twenty"; break;

	case 30: return "thirty"; break;

	case 40: return "forty"; break;

	case 50: return "fifty"; break;

	case 60: return "sixty"; break;

	case 70: return "seventy"; break;

	case 80: return "eighty"; break;

	case 90: return "ninety"; break;

	case 100: return "one hundred"; break;

	case 1000: return "one thousand"; break;

	case 100000: return "one lakh"; break;

	default:

	{

	if($n<100)

	{

	return $this->ConvertToWords(floor($n/10)*10, 'no')." ".$this->ConvertToWords($n%10, 'no'); break;

	}

	elseif($n<1000)

	{

	return $this->ConvertToWords(floor($n/100), 'no')." hundred ".$this->ConvertToWords($n%100, 'no'); break;

	}

	elseif($n<100000)

	{

	return $this->ConvertToWords(floor($n/1000), 'no')." thousand ".$this->ConvertToWords($n%1000, 'no'); break;

	}

	elseif($n<10000000)

	{

	return $this->ConvertToWords(floor($n/100000), 'no')." lakh ".$this->ConvertToWords($n%100000, 'no'); break;

	}

	else

	{

	return "Something else"; break;

	}

	}

	}

	}	

  /**

     * All User List

     * Function : usersList()

     * The information about return all users List

     **/ 

  public function usersList(){

	    $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('CONCAT(UD.first_name," ",UD.last_name) as name','*'))

                                                ->where("delete_status='0'")

						//->joinleft(array('DG'=>'designation'),"UD.designation_id=DG.designation_id",array('designation_code'));

						->order("employee_code");

						//print_r($select->__toString());die;

		 return $this->getAdapter()->fetchAll($select);

	}	

}



?>
