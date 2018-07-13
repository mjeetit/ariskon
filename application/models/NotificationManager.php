<?php

    class NotificationManager extends Zend_Custom

    {

		/**

		*Variable Holds the Name of Section Table
		**/

		/**

		**/

	

		public $parent_id = 1;				// For Getting submodule of application

		public $status    = '1';			// Status of module

		public $_getData  = array();		// Lavel of module.

		

		public function getMessages(){

		   $select = $this->_db->select()

                ->from('notification',array('*'))

				->where("(created_by='".$_SESSION['AdminLoginID']."' OR find_in_set(".$_SESSION['AdminLoginID'].",user_id))");

           $result = $this->getAdapter()->fetchAll($select);

		   return $result;			

		}

		public function addMessage(){
		    $this->_getData['user_id'] = implode(',',$this->_getData['user_id']);  	
		   $this->_getData['created_by'] = $_SESSION['AdminLoginID'];

		   $this->_getData['notification_date'] = date('Y-m-d');
		    if($_SESSION['AdminLoginID']!=1){
		   	   $locations = $this->getCurrentEmplocation();
			   $this->_getData['zone_id'] = $locations['zone_id'];
			   $this->_getData['region_id'] = $locations['region_id'];
			   $this->_getData['area_id'] = $locations['area_id'];
			   $this->_getData['headquater_id'] = $locations['headquater_id'];
			   $this->_getData['city_id'] = $locations['city_id'];
			}

		   $this->insertInToTable('notification',array($this->_getData));

		   /*Bootstrap::$Mail->_DataArray = array('SenderEmail'	=>'testerp@jclifecare.com',

		   										'SenderName'	=>'testerp@jclifecare.com',

												'ReceiverEmail'	=>'testerp@jclifecare.com',

												'ReceiverName'	=>'testerp@jclifecare.com',

												'Subject'		=>'Test',

												'Body'			=>$this->_getData['description'],

												'Attachment'	=>'');;*/

		   //Bootstrap::$Mail->SetEmailData();

		}

		public function getEvents(){

		   $select = $this->_db->select()

                ->from('events',array('*'))

				->where("created_by='".$_SESSION['AdminLoginID']."'");

           $result = $this->getAdapter()->fetchAll($select);

		   return $result;

	   }

	   public function addEvent(){

	       $this->_getData['created_by'] = $_SESSION['AdminLoginID'];

		   $this->insertInToTable('events',array($this->_getData));

	   }
	 public function getCurrentEmplocation(){
	      $select = $this->_db->select()
							->from('emp_locations',array('*'))
							->where("user_id='".$_SESSION['AdminLoginID']."'");
           $result = $this->getAdapter()->fetchrow($select);
		   return $result;
	   }
    public function getMessageByLocation(){
	       $locations = $this->getCurrentEmplocation();
		   $cond = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id='".$_SESSION['AdminDesignation']."' AND zone_id='".$locations['zone_id']."' AND region_id='".$locations['region_id']."'	AND area_id='".$locations['area_id']."' AND headquater_id='".$locations['headquater_id']."'  AND city_id='".$locations['city_id']."'";
		   
		   $cond1 = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id='".$_SESSION['AdminDesignation']."' AND zone_id='".$locations['zone_id']."' AND region_id='".$locations['region_id']."'	AND area_id='".$locations['area_id']."' AND headquater_id='".$locations['headquater_id']."'  AND city_id<=0";
		   
		   $cond2 = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id='".$_SESSION['AdminDesignation']."' AND zone_id='".$locations['zone_id']."' AND region_id='".$locations['region_id']."'	AND area_id='".$locations['area_id']."' AND headquater_id<=0  AND city_id<=0";
		   
		   $cond3 = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id='".$_SESSION['AdminDesignation']."' AND zone_id='".$locations['zone_id']."' AND region_id='".$locations['region_id']."'	AND area_id<=0 AND headquater_id<=0  AND city_id<=0";
		   
		   $cond4 = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id='".$_SESSION['AdminDesignation']."' AND zone_id='".$locations['zone_id']."' AND region_id<=0	AND area_id<=0 AND headquater_id<=0  AND city_id<=0";
		   
		   $cond5 = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id='".$_SESSION['AdminDesignation']."' AND zone_id<=0 AND region_id<=0 AND area_id<=0 AND headquater_id<=0  AND city_id<=0";
		   
		   $cond6 = "department_id='".$_SESSION['AdminDepartment']."' AND designation_id<=0 AND zone_id<=0 AND region_id<=0 AND area_id<=0 AND headquater_id<=0  AND city_id<=0";
			
		   $cond7 = "department_id<=0 AND designation_id<=0 AND zone_id<=0 AND region_id<=0 AND area_id<=0 AND headquater_id<=0  AND city_id<=0";
		   
		  $allconditions =  array($cond,$cond1,$cond2,$cond3,$cond4,$cond5,$cond6,$cond7);
		  $data = array();
		  foreach($allconditions as $condition){
		   $select = $this->_db->select()
						->from('notification',array('*'))
						->where($condition);
		   $results = $this->getAdapter()->fetchAll($select);
			 foreach($results as $result){
				$data[] = $result;  
			 }
		  }
		  return $data;
	   }
	   public function getMessageUserID(){
	       $select = $this->_db->select()
		   						   ->from(array('UD'=>'employee_personaldetail'),array('*'))
								   ->joininner(array('DT'=>'designation'),"UD.designation_id=DT.designation_id",array('designation_code'))
								   ->where("UD.parent_id='".$_SESSION['AdminLoginID']."'");
			$result = $this->getAdapter()->fetchAll($select);
			$select = $this->_db->select()
		   						   ->from(array('UD'=>'employee_personaldetail'),array('*'))
								   ->joininner(array('UD1'=>'employee_personaldetail'),"UD1.parent_id=UD.user_id",array(''))
								   ->joininner(array('DT'=>'designation'),"UD.designation_id=DT.designation_id",array('designation_code'))
								   ->where("UD1.user_id='".$_SESSION['AdminLoginID']."'");
			$result1 = $this->getAdapter()->fetchAll($select);	
			if($_SESSION['AdminLoginID']==1){
			  $select = $this->_db->select()
		   						   ->from(array('UD'=>'employee_personaldetail'),array('*'))
								   ->joininner(array('DT'=>'designation'),"UD.designation_id=DT.designation_id",array('designation_code'))
								   ->where("UD.delete_status='0'");
			$result = $this->getAdapter()->fetchAll($select);
			 
			return $result;
			}
			$result = array_merge($result,$result1);
			return $result;				   
	   }

	

}

?>