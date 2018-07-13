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
	
		public function setSession($uservalue){
			/*session_register("AdminLoginID");
			session_register("AdminLevelID");
			session_register("AdminUserType");
			session_register("AdminName");
			session_register("AdminBunit");
			session_register("AdminDesignation");
			session_register("AdminDepartment");*/			
			
			$_SESSION['AdminLoginID']     = $uservalue->user_id;
			$_SESSION['AdminLevelID']     = $uservalue->level_id;
			$_SESSION['AdminUserType']    = $uservalue->user_type;
			$tables = ($uservalue->user_id==1) ? 1 : 2;
			$select = $this->_db->select()
								 ->from($this->Tables[$tables],array('*'))
								 ->where("user_id='".$uservalue->user_id."'");
								 //echo $select->__toString();die;
			$result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
			$_SESSION['AdminName']        = $result['first_name'];
			$_SESSION['AdminBunit']    	  = $result['bunit_id'];
			$_SESSION['AdminDesignation'] = $result['designation_id'];
			$_SESSION['AdminDepartment']  = $result['department_id'];
			$_SESSION['LastLogin']  	  = $result['last_login'];
			$_SESSION['LastLoginIP']  	  = $result['last_login_ip'];
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
                    $select = $this->_db->select()
                                             ->from($this->Tables[$uservalue->user_type],array('*'))
                                             ->where("user_id='".$uservalue->user_id."' AND delete_status='0'");//echo $select->__toString();die;
                    $result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
                    if(!empty($result)){
                        return true;
                    }else{
                        return false;
                    }
                }
	}
?>