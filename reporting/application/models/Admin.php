<?php
    class Admin extends Zend_Custom
    {
		/**
		*Variable Holds the Name of Section Table
		**/

		public $parent_id = 1;				// For Getting submodule of application
		public $status    = '1';			// Status of module
		public $level_id  = NULL;			// Lavel of module.
		public $Tables  = array('1'=>'admin_detail','2'=>'employee_personaldetail');

		public function checkAuthentication($datavalue){

		    $select = $this->_db->select()
				->from(array('UD'=>'users'),array('*'))
				// ->joininner(array('EP'=>'employee_personaldetail'),array())
				->where("UD.username='".$datavalue['username']."' AND UD.password='".md5($datavalue['password'])."'");
			 $result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
			 if(!empty($result)){
			    return $result;
			 }else{
			    return false;
			 }
		}

		public function setSession($uservalue){
			
			$usertype  = ($uservalue['user_type']==1 || $uservalue['user_type']==2)?$uservalue['user_type']:2;
			$_SESSION['AdminLoginID']     = $uservalue['user_id'];
			$_SESSION['AdminLevelID']     = $uservalue['level_id'];
			$_SESSION['AdminUserType']    = $uservalue['user_type'];
			$select = $this->_db->select()
				->from($this->Tables[$usertype],array('*'))
				->where("user_id='".$uservalue['user_id']."'");
				//echo $select->__toString();die;
			$result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
			$_SESSION['AdminName']        = $result['first_name'].' '.$result['last_name'];
			$_SESSION['AdminBunit']    	  = $result['bunit_id'];
			$_SESSION['AdminDesignation'] = $result['designation_id'];
			$_SESSION['AdminDepartment']  = $result['department_id'];
			$_SESSION['LastLogin']  	  = $result['last_login'];
			$_SESSION['LastLoginIP']  	  = $result['last_login_ip'];
			/*********************************************************************************
			 below line is to set session variable for current root module either HRM(main) or crm or reporting by jm on 13072018
			**********************************************************************************/
			$_SESSION['ParentTab']  	  = "REPORTING";

			if($_SESSION['AdminLoginID']==1){
			  $table = 'admin_detail';
			}else{
			  $table = 'employee_personaldetail';
			}
			$this->_db->update($table,array('last_login'=>new Zend_Db_Expr('NOW()'),'last_login_ip'=>$_SERVER['REMOTE_ADDR']),"user_id='".$_SESSION['AdminLoginID']."'");					 
		   						 
		}
		public function unsetSession(){
			unset($_SESSION['AdminLoginID']);
			unset($_SESSION['AdminLevelID']);
			unset($_SESSION['AdminUserType']);
			unset($_SESSION['AdminName']);
			unset($_SESSION['AdminBunit']);
			unset($_SESSION['AdminDesignation']);
			unset($_SESSION['AdminDepartment']);
			unset($_SESSION['LastLogin']);
			unset($_SESSION['LastLoginIP']);
			/*********************************************************************************
			 below line is to set session variable for current root module either HRM(main) or crm or reporting by jm on 13072018
			**********************************************************************************/
			unset($_SESSION['ParentTab']);
			/*session_unregister($_SESSION['AdminLoginID']);
			session_unregister($_SESSION['AdminLevelID']);
			session_unregister($_SESSION['AdminUserType']);
			session_unregister($_SESSION['AdminName']);
			session_unregister($_SESSION['AdminBunit']);
			session_unregister($_SESSION['AdminDesignation']);
			session_unregister($_SESSION['AdminDepartment']);
			session_unregister($_SESSION['LastLogin']);
			session_unregister($_SESSION['LastLoginIP']);*/
			session_destroy();

		}
        public function getStatusCheck($uservalue){
		   $usertype  = ($uservalue['user_type']==1 || $uservalue['user_type']==2)?$uservalue['user_type']:2;
            $select = $this->_db->select()
                                     ->from($this->Tables[$usertype],array('*'))
                                     ->where("user_id='".$uservalue['user_id']."' AND delete_status='0'");
            $result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
            if(!empty($result)){
                return true;
            }else{
                return false;
            }
        }
	}
?>