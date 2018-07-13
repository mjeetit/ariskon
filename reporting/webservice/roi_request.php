<?php
header('Content-type: application/json');
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'Test';
$parent = isset($_REQUEST['parent'])?$_REQUEST['parent']:0;
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
switch($mode){
     case 'Zone':
	    //$data['Zone'] = '0';
	  	//$data['ZoneName'] ='-Select Zone-';
		$zones = getZone();
		//$newdata[] = $data;
	 foreach($zones as $zone){
	  	$data['Zone'] = $zone['zone_id'];
	  	$data['ZoneName'] = $zone['zone_name'];
		$newdata[] = $data;
	  }
	 // $dataArr = array('schools'=>$newdata);
	 break;
	 case 'Region':
		$regions = getRegion($parent);
	 foreach($regions as $region){
	  	$data['Region'] = $region['region_id'];
		$data['Zone'] = $region['zone_id'];
		$data['Region'] = $region['region_id'];
	  	$data['RegionName'] = $region['region_name'];
		$data['Location'] = $region['location_code'];
		$newdata[] = $data;
	  }
	 break;
	case 'Area':
		$areas = getArea($parent);
	 foreach($areas as $area){
	  	$data['Area'] = $area['area_id'];
		$data['Zone'] = $area['zone_id'];
		$data['Region'] = $area['region_id'];
	  	$data['AreaName'] = $area['area_name'];
		$data['Location'] = $area['location_code'];
		$newdata[] = $data;
	  }
	break;
	case 'Headquater':
		$headquaters = getHeadquater($parent);
	 foreach($headquaters as $headquater){
	    $data['Area'] = $headquater['area_id'];
		$data['Zone'] = $headquater['zone_id'];
		$data['Region'] = $headquater['region_id'];
		$data['Location'] = $headquater['location_code'];
	  	$data['Headquater'] = $headquater['headquater_id'];
	  	$data['HeadquaterName'] = $headquater['headquater_name'];
		$newdata[] = $data;
	  }
	break;
   case 'City':
		$cities = getCity($parent);
	 foreach($cities as $city){
	    $data['Area'] = $city['area_id'];
		$data['Zone'] = $city['zone_id'];
		$data['Region'] = $city['region_id'];
		$data['Location'] = $city['location_code'];
	  	$data['Headquater'] = $city['headquater_id'];
	  	$data['City'] = $city['city_id'];
	  	$data['CityName'] = $city['city_name'];
		$newdata[] = $data;
	  }
	break;
  case 'Street':
		$streets = getStreet($parent);
	 foreach($streets as $street){
	    $data['Area'] = $street['area_id'];
		$data['Zone'] = $street['zone_id'];
		$data['Region'] = $street['region_id'];
		$data['Location'] = $street['location_code'];
	  	$data['Headquater'] = $street['headquater_id'];
	  	$data['City'] = $street['city_id'];
	  	$data['Street'] = $street['street_id'];
	  	$data['StreetName'] = $street['street_name'];
		$newdata[] = $data;
	  }
	break; 
   case 'Users':
		$users = getUsers($parent);
	 foreach($users as $user){
	    $data['User'] = $user['user_id'];
		$data['Username'] = $user['username'];
		$data['Password'] = $user['passwowrd_text'];
		$newdata[] = $data;
	  }
	break; 	
   case 'Doctor':
		$doctors = getDoctors($parent,$designation);
	 foreach($doctors as $doctor){
	  	$data['Doctor'] = $doctor['doctor_id'];
	  	$data['DoctorName'] = $doctor['doctor_name'];
		$data['DoctorCode'] = $doctor['doctor_code'];
		$data['Street'] = $doctor['street_id'];
		$newdata[] = $data;
	  }
     break;	
   case 'Product':
		$products = getProducts($parent);
	 foreach($products as $product){
	  	$data['Product'] = $product['product_id'];
	  	$data['ProductName'] = $product['product_name'];
		$data['ProductCode'] = $product['product_code'];
		$newdata[] = $data;
	  }
     break;	
  case 'Expense':
		$Expenses = getEmpExpenseAmount($parent);
	 foreach($Expenses as $Expense){
	  	$data['Expense'] 	 = $Expense['head_id'];
	  	$data['ExpenseName'] = $Expense['head_name'];
		$data['Amount'] 	 = $Expense['expense_amount'];
		$data['Notimes']  = $Expense['no_of_times'];
		$newdata[] = $data;
	  }
     break;
  case 'Chemist':
		$chemistlist = getChemist($parent,$designation);
	 foreach($chemistlist as $chemist){
	  	$data['Chemist'] 	 = $chemist['chemist_id'];
	  	$data['ChemistName'] = $chemist['chemist_name'];
		$data['ChemistCode'] = $chemist['legacy_code'];
		//$data['Notimes']  = $Expense['no_of_times'];
		$newdata[] = $data;
	  }
     break;
   case 'Activity':
		$Activities = getActivities($parent);
	 foreach($Activities as $activity){
	  	$data['Activity'] 	  = $activity['activity_id'];
	  	$data['ActivityName'] = $activity['activity_name'];
		$newdata[] = $data;
	  }
     break;
   case 'Employee':
		$abmlists = getEmployeeParent($parent,true);
	 foreach($abmlists as $abmlist){
	  	$data['User'] 	  = $abmlist['user_id'];
	  	$data['Parent'] = $abmlist['parent_id'];
		$data['Name'] 	= $abmlist['designation_code'].'-'.$abmlist['first_name'].' '.$abmlist['last_name'];
		$newdata[] = $data;
		if($abmlist['designation']==7){ 
		  $zbmid = $abmlist['parent_id'];
		}else{
		  $zbmid = $abmlist['user_id'];
		}
	  }
	  $zbmlists = getEmployeeParent($zbmid);
	 foreach($zbmlists as $zbmlist){
	  	$data['User'] 	  = $zbmlist['user_id'];
	  	$data['Parent'] = $zbmlist['parent_id'];
		$data['Name'] 	= $zbmlist['designation_code'].'-'.$zbmlist['first_name'].' '.$zbmlist['last_name'];
		$newdata1[] = $data;
		$rbmid = $zbmlist['user_id'];
	  }
	  $rbmlists = getEmployeeParent($rbmid);
	 foreach($rbmlists as $rbmlist){
	  	$data['User'] 	  = $rbmlist['user_id'];
	  	$data['Parent'] = $rbmlist['parent_id'];
		$data['Name'] 	= $rbmlist['designation_code'].'-'.$rbmlist['first_name'].' '.$rbmlist['last_name'];
		$newdata2[] = $data;
		//$abmid = $rbmlist['parent_id'];
	  }
	  echo json_encode(array('success'=>'YES','ABM'=>$newdata,'ZBM'=>$newdata1,'RBM'=>$newdata2)); exit;
     break;
	case 'BElist':
	 $belists = getBElist($parent,$designation);
	 foreach($belists as $belist){
	  	$data['User'] 	  = $belist['user_id'];
	  	$data['Parent']   = $belist['parent_id'];
		$data['Name'] 	  = $belist['designation_code'].'-'.$belist['first_name'].' '.$belist['last_name'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'ABMlist':
	 $abmlists = getABMlist($parent,$designation);
	 foreach($abmlists as $abmlist){
	  	$data['User'] 	  = $abmlist['user_id'];
	  	$data['Parent']   = $abmlist['parent_id'];
		$data['Name'] 	  = $abmlist['designation_code'].'-'.$abmlist['first_name'].' '.$abmlist['last_name'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'ZBMlist':
	 $zbmlists = getZBMlist($parent,$designation);
	 foreach($zbmlists as $zbmlist){
	  	$data['User'] 	  = $zbmlist['user_id'];
	  	$data['Parent']   = $zbmlist['parent_id'];
		$data['Name'] 	  = $zbmlist['designation_code'].'-'.$zbmlist['first_name'].' '.$zbmlist['last_name'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'RBMlist':
	 $rbmlists = getRBMlist($parent,$designation);
	 foreach($rbmlists as $rbmlist){
	  	$data['User'] 	  = $rbmlist['user_id'];
	  	$data['Parent']   = $rbmlist['parent_id'];
		$data['Name'] 	  = $rbmlist['designation_code'].'-'.$rbmlist['first_name'].' '.$rbmlist['last_name'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'MeetingType':
	 $meetings = getMeetingType($parent);
	 foreach($meetings as $meeting){
	  	$data['Type'] 	    = $meeting['type_id'];
	  	$data['TypeName']   = $meeting['type_name'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'HeadQuater':
	 $headquaters = getHeadquaters($parent,$designation);
	 foreach($headquaters as $headquater){
	  	$data['Headquater'] 	  = $headquater['headquater_id'];
	  	$data['Name']  			 = $headquater['headquater_name'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'CRMEXPENSE':
	 $crmexpensetype = getCRMExpense($parent);
	 foreach($crmexpensetype as $crmexpense){
	  	$data['Type'] 	  = $crmexpense['expense_type'];
	  	$data['TypeName'] = $crmexpense['type_name'];
		$newdata[] 	  = $data;
	  }
	break; 
}
echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;

function getZone($param=false){
   $sql = "SELECT * FROM zone WHERE 1";
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getRegion($param=false){
   $where = '';
   if($param){
     $where = " AND zone_id='".$param."'";
   } 
   $sql = "SELECT * FROM region WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getArea($param=false){
   $where = '';
   if($param){
     $where = " AND region_id='".$param."'";
   } 
   $sql = "SELECT * FROM area WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getHeadquater($param=false){
   $where = '';
   if($param){
     $where = " AND area_id='".$param."'";
   } 
   $sql = "SELECT * FROM headquater WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getCity($param=false){
   $where = '';
   if($param){
     $where = " AND headquater_id='".$param."'";
   } 
   $sql = "SELECT * FROM city WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getStreet($param=false){
   $where = '';
   if($param){
     $where = " AND city_id='".$param."'";
   } 
   $sql = "SELECT * FROM street WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getUsers($param=false){
   $where = '';
   if($param){
     //$where = " AND city_id='".$param."'";
   } 
   $sql = "SELECT * FROM users WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getEmpExpenseAmount($param=false){
   $where = '';
   if($param){
     $where = " AND user_id='".$param."'";
   } 
   $sql = "SELECT EEH.head_id,EEH.expense_amount,EH.head_name,EH.no_of_times FROM emp_expense_amount EEH INNER JOIN expense_head EH ON EH.head_id=EEH.head_id WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getDoctors($param=false,$designation) {
   $where = '';
   if($designation==7 || $designation==6 || $designation==5){
     $sql = "SELECT * FROM emp_locations WHERE user_id='".$param."'"; 
	 $execute = mysql_query($sql);
	 $locations = mysql_fetch_assoc($execute);
	 $where = " AND city_id='".$locations['city_id']."'";
   }
   if($param){
     //$where = " AND city_id='".$param."'";
   } 
   $sql = "SELECT * FROM crm_doctors WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getChemist($param=false,$designation){
   $where = '';
   if($designation==7 || $designation==6 || $designation==5){
     $sql = "SELECT * FROM emp_locations WHERE user_id='".$param."'"; 
	 $execute = mysql_query($sql);
	 $locations = mysql_fetch_assoc($execute);
	 $where = " AND city_id='".$locations['city_id']."'";
   }
   if($param){
     //$where = " AND city_id='".$param."'";
   } 
   $sql = "SELECT * FROM crm_chemists WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getProducts($param=false){
   $where = '';
   if($param){
     //$where = " AND city_id='".$param."'";
   } 
   $sql = "SELECT * FROM crm_products WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}

function getActivities($param=false){
   $where = '';
   if($param){
     //$where = " AND city_id='".$param."'";
   } 
   $sql = "SELECT * FROM app_activities WHERE 1".$where;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getEmployeeParent($param,$checkdesignation=false){
   $where = '';
   //if($param>0){
     $where = " AND EL.user_id='".$param."'";
   //}
   if($checkdesignation){
        $sql = "SELECT designation_id,parent_id FROM employee_personaldetail WHERE user_id='".$param."'";
		$execute = mysql_query($sql);
		$descheck = mysql_fetch_assoc($execute);
		if($descheck['designation_id']==2 || $descheck['designation_id']==8){
		   $sql = "SELECT PEL.*,DG.designation_code FROM employee_personaldetail EL
				   INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
				   INNER JOIN designation DG ON DG.designation_id=PEL.designation_id WHERE 1".$where; 
		 }elseif($descheck['designation_id']==7){
		   $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
				   INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.parent_id='".$param."'";
		 }		   
		   $execute = mysql_query($sql);
		   $data = array();
		   while($result = mysql_fetch_assoc($execute)){
		        $result['designation']  = $descheck['designation_id'];
				$data[] = $result;
		   }
		   
   }else{
       $sql = "SELECT PEL.*,DG.designation_code FROM employee_personaldetail EL
			   INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
			   INNER JOIN designation DG ON DG.designation_id=PEL.designation_id WHERE 1".$where; 
	   $execute = mysql_query($sql);
	   $data = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
	   }
   }
   return $data;
}

function getBElist($param,$designation){
   $where = '';
   //if($param>0){
     $where = " AND EL.user_id='".$param."'";
   //} 
    if($designation==7){ //abm
	 $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.parent_id='".$param."'";
	}elseif($designation==5){
	  $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE PEL.parent_id='".$param."' AND EL.designation_id=8";
			 
	}elseif($designation==6){
	  $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
			  INNER JOIN  employee_personaldetail REL ON EL.parent_id=REL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE REL.parent_id='".$param."' AND EL.designation_id=8";
			 
	}
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getABMlist($param,$designation){
   $where = '';
    if($designation==5){ //zbm
	 $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.parent_id='".$param."'";
	}elseif($designation==6){
	  $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE PEL.parent_id='".$param."' AND EL.designation_id=7";
			 
	}
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}

function getZBMlist($param,$designation){
   $where = '';
   if($designation==7){ //zbm
	 $sql = "SELECT PEL.*,DG.designation_code FROM employee_personaldetail EL
	 		 INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
   		     INNER JOIN designation DG ON DG.designation_id=PEL.designation_id WHERE EL.user_id='".$param."'";
	}
    if($designation==6){ //rbm
	 $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.parent_id='".$param."'";
	}
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}

function getRBMlist($param,$designation){
   $where = '';
   if($designation==7){ //abm
	 $sql = "SELECT REL.*,DG.designation_code FROM employee_personaldetail EL
   		      INNER JOIN  employee_personaldetail PEL ON EL.parent_id=PEL.user_id
			  INNER JOIN  employee_personaldetail REL ON PEL.parent_id=REL.user_id
   		      INNER JOIN designation DG ON DG.designation_id=REL.designation_id WHERE EL.user_id='".$param."'";
	}
    if($designation==5){ //zbm
	 $sql = "SELECT EL.*,DG.designation_code FROM employee_personaldetail EL
   		     INNER JOIN designation DG ON DG.designation_id=EL.designation_id WHERE EL.parent_id='".$param."'";
	}
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getMeetingType($param){
   $where = '';
	 $sql = "SELECT * FROM app_meetingtype WHERE 1";
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getHeadquaters($param){
   $where = '';
	 $sql = "SELECT * FROM headquater WHERE 1";
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}

function getCRMExpense($param){
   $where = '';
	 $sql = "SELECT * FROM crm_expense_types WHERE isActive='1' AND isDelete='0'";
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
?>