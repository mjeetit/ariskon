<?php
    class Admin extends Zend_Custom
    {
		/**
		*Variable Holds the Name of Section Table
		**/
		/**
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
			 $result = $this->getAdapter()->fetchRow($select);
			
			 if(!empty($result)){
			  	/*$select = $this->_db->select()
						->from(array('AU'=>'app_userdetails'),array('reporing_lock'))
						->where("AU.user_id='".$result['user_id']."'");
				$check= $this->getAdapter()->fetchRow($select);
					if($check['reporing_lock']==0){
				   	return $result;
				   	}else{
				    	$_SESSION[ERROR_MSG]="Your account has been locked";
				    	//return false;
				    }*/
						return $result;

			 	}else{
			    	$_SESSION[ERROR_MSG] = "Invalid username or password.";
			 	}
		}
	
		public function setSession($uservalue){
			/*session_register("AdminLoginID");
			session_register("AdminLevelID");
			session_register("AdminUserType");
			session_register("AdminName");
			session_register("AdminBunit");
			session_register("AdminDesignation");
			session_register("AdminDepartment");*/
			
			$usertype  = ($uservalue['user_type']==1 || $uservalue['user_type']==2)?$uservalue['user_type']:2;
			
			$_SESSION['AdminLoginID']     = $uservalue['user_id'];
			$_SESSION['AdminLevelID']     = $uservalue['level_id'];
			$_SESSION['AdminUserType']    = $uservalue['user_type'];
			
			$select = $this->_db->select()
				->from($this->Tables[$usertype],array('*'))
				->where("user_id='".$uservalue['user_id']."'");
			$result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
			
			/*********************************************************************
			update user first name and last name in employee_personaldetail table and
			assign the combination of first_name and last_name to the session variable
			by jm on 14072018  
			*********************************************************************/
			$_SESSION['AdminName']        = $result['first_name'].' '.$result['last_name'];
			$_SESSION['AdminBunit']    	  = $result['bunit_id'];
			$_SESSION['AdminDesignation'] = $result['designation_id'];
			$_SESSION['AdminDepartment']  = $result['department_id'];
			$_SESSION['LastLogin']  	  = $result['last_login'];
			$_SESSION['LastLoginIP']  	  = $result['last_login_ip'];

			/*********************************************************************************
			 below line is to set session variable for current root module either HRM(main) or crm or reporting by jm on 13072018
			**********************************************************************************/
			$_SESSION['ParentTab']  	  = "HRM";

			if($_SESSION['AdminLoginID']==1){
			  $table = 'admin_detail';
			}else{
			  $table = 'employee_personaldetail';
			}
			$this->_db->update($table,array('last_login'=>new Zend_Db_Expr('NOW()'),'last_login_ip'=>$_SERVER['REMOTE_ADDR']),"user_id='".$_SESSION['AdminLoginID']."'");
			//Newly inserted code
			$this->_db->insert('login_logout_details',array_filter(array('user_id'=>$_SESSION['AdminLoginID'],'login_time'=>date('Y-m-d H:i:s',time()),'login_via'=>0)));
		   						 
    	}
		public function unsetSession(){
			//Newly inserted Code
			$select = $this->_db->select()
							->from('login_logout_details',array('*'))
							->where("user_id='".$_SESSION['AdminLoginID']."'")
							->order('id DESC');//echo $select->__toString();die;
			$lastInserted= $this->getAdapter()->fetchRow($select);//print_r($lastInserted);die;
			$this->_db->update('login_logout_details',array('logout_time'=>date('Y-m-d H:i:s',time())),"id='".$lastInserted['id']."'","user_id='".$lastInserted['user_id']."'");
			//End of newly inserted code
			
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