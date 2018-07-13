<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class ServerData extends commonclass{
    public $headquaters = array();
	public $checkSync = array();
	public $designation = 0;
    function getAllSearverData($user_id){
	    //EMployee Detail
		if($_REQUEST['autosync']=='1'){
		   $this->checkAutosync($user_id);
		}
		$designation_id = $this->getDesignation($user_id);
		$this->designation = $designation_id;
		$returnarray = array();
		
		$Employee = $this->getUserlist($user_id);//1
		$MeetingType = $this->meetingType($user_id);//2
		$Headquaters = $this->getHeadquaters($user_id,$designation_id);//3
		$Location = $this->getHeadquaters($user_id,$designation_id);//4
		$PatchList = $this->getPatchList($user_id);//5
		$Doctors = $this->getDoctors($user_id);//6
		$Chemist = $this->getChemist($user_id);//7
		$Stackist = $this->getStackist($user_id);//8
		$Product = $this->getProduct($user_id);//9
		$Activity = $this->getActivities($user_id);//10
		$CRMExpense = $this->getCrmExpense($user_id);//11
		$Gifts = $this->getGifts($user_id);//12
		
		$exceute = $this->query("UPDATE app_userdetails SET last_sync_date=NOW() WHERE user_id='".$user_id."'");
		
		return array('success'=>'YES','Employee'=>$Employee,'MeetingType'=>$MeetingType,'Headquaters'=>$Headquaters,'Location'=>$Location,'PatchList'=>$PatchList,'Doctors'=>$Doctors,'Chemist'=>$Chemist,'Stackist'=>$Stackist,'Product'=>$Product,'Activity'=>$Activity,'CRMExpense'=>$CRMExpense,'Gifts'=>$Gifts,'EmpRes'=>(!empty($Employee)?'YES':'NO'),'PatchRes'=>(!empty($PatchList)?'YES':'NO'),'MeetingRes'=>(!empty($MeetingType)?'YES':'NO'),'HqRes'=>(!empty($Headquaters)?'YES':'NO'),'LocationRes'=>(!empty($Location)?'YES':'NO'),'DocRes'=>(!empty($Doctors)?'YES':'NO'),'ChemRes'=>(!empty($Chemist)?'YES':'NO'),'StackistRes'=>(!empty($Stackist)?'YES':'NO'),'ProductRes'=>(!empty($Product)?'YES':'NO'),'ActRes'=>(!empty($Activity)?'YES':'NO'),'CRMExpRes'=>(!empty($CRMExpense)?'YES':'NO'),'GiftRes'=>(!empty($Gifts)?'YES':'NO'));
	}
	public function getUserlist($user_id){
		if($_REQUEST['autosync']=='1' && !in_array('1',$this->checkSync)){
		    return array();
		}
		$parentUsers = $this->getParentuser(array($user_id),1);
		$chiledUsers = $this->getChilduser(array($user_id),1);
		$Allusers = array_merge($parentUsers,$chiledUsers);
		
	   $exceute = $this->query("SELECT EL.user_id,EL.parent_id,EL.designation_id,EH.headquater_id,EL.first_name,EL.last_name,DT.designation_code 
	   		FROM employee_personaldetail EL
	        LEFT JOIN designation DT ON DT.designation_id=EL.designation_id
			LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id  
   		   WHERE EL.user_id IN('".implode("','",$Allusers)."') AND EL.delete_status='0' AND EL.designation_id IN(8,7,6,5) AND EL.user_id !='".$user_id."'");
	  $listusers = $this->fetchAll($exceute);
	  $dataArr = array();
	  $Found = 'NO';
	  foreach($listusers as $user){
	    $data = array();
		$data['User'] 	  = $user['user_id'];
		$data['Parent']   = $user['parent_id'];
		$data['Name'] 	  = $user['designation_code'].'-'.$user['first_name'].' '.$user['last_name'];
		$data['Designation']  = $user['designation_id'];
		$data['Headquater']   = $user['headquater_id'];
		$this->headquaters[] = $user['headquater_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
	}
	
	public function meetingType(){
		if($_REQUEST['autosync']=='1' && !in_array('2',$this->checkSync)){
		    return array();
		}
	  $exceute = $this->query("SELECT * FROM app_meetingtype ORDER BY type_name ASC");
	  $meetings = $this->fetchAll($exceute);
	  $dataArr = array();
	  $Found = 'NO';
	  foreach($meetings as $meeting){
	    $data = array();
	  	$data['Type'] 	    = $meeting['type_id'];
	  	$data['TypeName']   = $meeting['type_name'];
		$data['WorkType']   = $meeting['act_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
	}
	
	public function getHeadquaters($user_id,$designation){
	if($_REQUEST['autosync']=='1' && !in_array('3',$this->checkSync)){
		    return array();
		}
	$data = array();
	$headquaters = array();
    switch($designation){
	     case 8:
		   $sql = "SELECT EL.* FROM emp_locations EL WHERE user_id='".$user_id."'";
		 break;
		  case 7:
		   $sql = "SELECT HQ.* FROM emp_locations EL INNER JOIN headquater HQ ON HQ.area_id=EL.area_id WHERE user_id='".$user_id."'"; 
		 break;
		  case 6:
		   $sql = "SELECT HQ.* FROM emp_locations EL INNER JOIN headquater HQ ON  HQ.region_id=EL.region_id WHERE user_id='".$user_id."'";
		 break;
		  case 5:
		   $sql = "SELECT HQ.* FROM emp_locations EL INNER JOIN headquater HQ ON  HQ.zone_id=EL.zone_id WHERE user_id='".$user_id."'";
		 break;
		 
	  }
	   $execute = $this->query($sql);
	   $elheadquaters = $this->fetchAll($execute);
	   foreach($elheadquaters as $result){
			$data[] = $result['headquater_id'];
	   }
	 //Child Headquater
	 $chiledUsers = $this->getChilduser(array($user_id),1);
	 $exceute = $this->query("SELECT EH.headquater_id 
	   		FROM employee_personaldetail EL
			LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id  
   		    WHERE EL.user_id IN('".implode("','",$chiledUsers)."')");
	 $listusershq = $this->fetchAll($exceute);  
	 foreach($listusershq as $resulthq){
			$data[] = $resulthq['headquater_id'];
	 }  
	   
   $execute = $this->query("SELECT HQ.* FROM headquater HQ WHERE headquater_id IN(".implode(',',$data).") GROUP BY headquater_id");
   $headquaters = $this->fetchAll($execute);
	  $dataArr = array();
	  $Found = 'NO';
	  foreach($headquaters as $headquater){
	    $data = array();
	  	$data['Headquater'] 	  = $headquater['headquater_id'];
	  	$data['Name']  			 = $headquater['headquater_name'];
		$this->headquaters[] = $headquater['headquater_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
  }
    
	public function getPatchList($user_id){
	if($_REQUEST['autosync']=='1' && !in_array('4',$this->checkSync)){
		    return array();
		}
	   $execute = $this->query("SELECT PT.patch_id,PT.patchcode,PT.patch_name,PT.city_id,PT.headquater_id,CT.city_name FROM patchcodes AS PT LEFT JOIN city CT ON CT.headquater_id=PT.headquater_id  WHERE PT.isDelete='0' AND PT.headquater_id IN(".implode(',',array_unique($this->headquaters)).") ORDER BY PT.patch_name ASC");
	   $getpatchlist = $this->fetchAll($execute);
	   $dataArr = array();
	  $Found = 'NO';
	  foreach($getpatchlist as $patch){
	    $data = array();
	  	$data['Patch'] 	  = $patch['patch_id'];
	  	$data['PatchCode'] = $patch['patchcode'];
		$data['PatchName'] = $patch['patch_name'];
		$data['City'] = $patch['city_id'];
		$data['Headquater'] = $patch['headquater_id'];
		$data['CityName'] = $patch['city_name'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
	}
	
	public function getDoctors($user_id) {
	   if($_REQUEST['autosync']=='1' && !in_array('5',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT DT.doctor_id,DT.doctor_code,DT.doctor_name,PT.headquater_id,PT.patch_id FROM crm_doctors AS DT INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id WHERE DT.delete_status='0' AND PT.isDelete='0' AND DT.isApproved='1' AND PT.headquater_id IN(".implode(',',array_unique($this->headquaters)).") GROUP BY DT.doctor_name,DT.patch_id,DT.headquater_id ORDER BY DT.doctor_name ASC");
		$getdoctorlist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($getdoctorlist as $doctor){
	    $data = array();
	  	$data['Doctor'] = $doctor['doctor_id'];
	  	$data['DoctorName'] = $doctor['doctor_name'];
		$data['DoctorCode'] = $doctor['doctor_code'];
		$data['Headquater'] = $doctor['headquater_id'];
		$data['Patch'] = $doctor['patch_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
    public function getChemist($user_id) {
	  if($_REQUEST['autosync']=='1' && !in_array('6',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT DISTINCT CT.chemist_id,CT.*,PT.patch_id,PT.headquater_id FROM crm_chemists CT 
		   INNER JOIN patchcodes AS PT ON PT.patch_id=CT.patch_id WHERE PT.headquater_id IN(".implode(',',array_unique($this->headquaters)).") AND CT.isDelete='0' GROUP BY CT.chemist_name,CT.headquater_id ORDER BY CT.chemist_name ASC");
		   
		$getchemistlist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($getchemistlist as $chemist){
	    $data = array();
	  	$data['Chemist'] 	 = $chemist['chemist_id'];
	  	$data['ChemistName'] = $chemist['chemist_name'];
		$data['ChemistCode'] = $chemist['legacy_code'];
		$data['Patch'] 		 = $chemist['patch_id'];
		$data['Headquater'] = $chemist['headquater_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
    public function getStackist($user_id) {
	    if($_REQUEST['autosync']=='1' && !in_array('7',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT DISTINCT PT1.stockist_id,PT1.*,PT.headquater_id FROM crm_stockists PT1 INNER JOIN  crm_stockists_hq PT ON PT.stockist_id=PT1.stockist_id WHERE PT.headquater_id IN(".implode(',',array_unique($this->headquaters)).")");
		   
		$getstackistlist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($getstackistlist as $stockist){
	    $data = array();
	  	$data['Stockist'] 	 = $stockist['stockist_id'];
		$data['StockistName']= $stockist['stockist_name'];
		$data['Headquater']  = $stockist['headquater_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
    public function getProduct($user_id) {
	   if($_REQUEST['autosync']=='1' && !in_array('8',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT * FROM crm_products ORDER BY product_name ASC");
		   
		$productlist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($productlist as $product){
	    $data = array();
	  	$data['Product'] = $product['product_id'];
	  	$data['ProductName'] = $product['product_name'];
		$data['ProductCode'] = $product['product_code'];
		$data['Price'] = $product['retailer_excl_vat'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
    public function getActivities($user_id) {
	   if($_REQUEST['autosync']=='1' && !in_array('9',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT * FROM app_activities ORDER BY activity_name");
		   
		$Activitieslist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($Activitieslist as $activity){
	    $data = array();
	  	$data['Activity'] 	  = $activity['activity_id'];
	  	$data['ActivityName'] = $activity['activity_name'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
    public function getCrmExpense($user_id) {
	if($_REQUEST['autosync']=='1' && !in_array('10',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT * FROM crm_expense_types WHERE isActive='1' AND isDelete='0' ORDER BY type_name");
		   
		$crmexpenselist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($crmexpenselist as $crmexpense){
	    $data = array();
	  	$data['Type'] 	  = $crmexpense['expense_type'];
	  	$data['TypeName'] = $crmexpense['type_name'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
    public function getGifts($user_id) {
	if($_REQUEST['autosync']=='1' && !in_array('11',$this->checkSync)){
		    return array();
		}
		$execute = $this->query("SELECT GA.*,AG.gift_name FROM employee_personaldetail EL
   		     INNER JOIN app_gift_assigned GA ON GA.user_id=EL.user_id
   		     INNER JOIN app_gifts AG ON AG.gift_id=GA.gift_id WHERE EL.user_id='".$user_id."' AND EL.delete_status='0' AND AG.isActive='1'");
		   
		$giftslist = $this->fetchAll($execute);
		$dataArr = array();
		$Found = 'NO';
	  foreach($giftslist as $gifts){
	    $data = array();
		$data['Gift'] 	  = $gifts['gift_id'];
		$data['GiftName'] = $gifts['gift_name'];
		$data['Quantity'] = $gifts['assigned_quantity'];
		$data['ValidFrom'] = $gifts['valid_from'];
		$data['ValidTo'] = $gifts['valid_to'];
		$data['Assined'] = $gifts['assigned_date'];
		$data['User'] = $gifts['user_id'];
		$dataArr[] = $data;
		$Found = 'YES';
	  }
	  return $dataArr;
   }
   
   public function checkAutosync($user_id){
       $exceute = $this->query("SELECT * FROM app_userdetails WHERE user_id='".$user_id."'");
	   //echo "SELECT * FROM app_userdetails WHERE user_id='".$user_id."'";die;
       $data = $this->fetchRow($exceute);
	   if($data['auto_sync']==1 && $data['sync_options']!=''){
	      $this->checkSync = explode(',',$data['sync_options']);
	  	  $this->query("UPDATE app_userdetails SET auto_sync=0,sync_options='' WHERE user_id='".$user_id."'");
	   }
   }
}
$user_id 		= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$obj = new ServerData();
$response = $obj->getAllSearverData($user_id);
//echo "<pre>";print_r($response);die;
echo json_encode($response);exit;

?>