<?php
header('Content-type: application/json');
$mode 			= isset($_REQUEST['mode'])?$_REQUEST['mode']:'Test';
$parent 		= isset($_REQUEST['parent'])?$_REQUEST['parent']:0;
$user_id 		= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation 	= isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
$patch_id 		= isset($_REQUEST['patch_id'])?$_REQUEST['patch_id']:0;
include 'config.php';
/*$sql = "INSERT INTO app_test SET request_date='".json_encode($_REQUEST)."'";
mysql_query($sql);*/
switch($mode){
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
   case 'Doctor':
   		$designation = getDesignattion($parent);
		$doctors = getDoctors($parent,$designation,$patch_id);
	 foreach($doctors as $doctor){
	  	$data['Doctor'] = $doctor['doctor_id'];
	  	$data['DoctorName'] = $doctor['doctor_name'];
		$data['DoctorCode'] = $doctor['doctor_code'];
		$data['Street'] = $doctor['street_id'];
		$data['Patch'] = $doctor['patch_id'];
		$newdata[] = $data;
	  }
     break;	
   case 'Product':
		$products = getProducts($parent);
	 foreach($products as $product){
	  	$data['Product'] = $product['product_id'];
	  	$data['ProductName'] = $product['product_name'];
		$data['ProductCode'] = $product['product_code'];
		$data['Price'] = $product['retailer_excl_vat'];
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
     	$designation = getDesignattion($parent);
		$chemistlist = getChemist($parent,$designation,$patch_id);
	 foreach($chemistlist as $chemist){
	  	$data['Chemist'] 	 = $chemist['chemist_id'];
	  	$data['ChemistName'] = $chemist['chemist_name'];
		$data['ChemistCode'] = $chemist['legacy_code'];
		$data['Patch'] = $chemist['patch_id'];
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
   		$designation = getDesignattion($parent);
		$belists = getBElist($parent,$designation);
		 foreach($belists as $belist){
			$data['User'] 	  = $belist['user_id'];
			$data['Parent']   = $belist['parent_id'];
			$data['Name'] 	  = $belist['designation_code'].'-'.$belist['first_name'].' '.$belist['last_name'];
			$data['Designation']  = $belist['designation_id'];
	  		$data['Headquater']   = $belist['headquater_id'];
			$bedata[] 	  = $data;
		  }
	  $abmlists = getABMlist($parent,$designation);
	  foreach($abmlists as $abmlist){
	  	$data['User'] 	  = $abmlist['user_id'];
	  	$data['Parent']   = $abmlist['parent_id'];
		$data['Name'] 	  = $abmlist['designation_code'].'-'.$abmlist['first_name'].' '.$abmlist['last_name'];
		$data['Designation']  = $abmlist['designation_id'];
	  	$data['Headquater']   = $abmlist['headquater_id'];
		$abmdata[] 	  = $data;
	  }
	  $rbmlists = getRBMlist($parent,$designation);
	 foreach($rbmlists as $rbmlist){
	  	$data['User'] 	  = $rbmlist['user_id'];
	  	$data['Parent']   = $rbmlist['parent_id'];
		$data['Name'] 	  = $rbmlist['designation_code'].'-'.$rbmlist['first_name'].' '.$rbmlist['last_name'];
		$data['Designation']  = $rbmlist['designation_id'];
	  	$data['Headquater']   = $rbmlist['headquater_id'];
		$rbmdata[] 	  = $data;
	  }
	  $zbmlists = getZBMlist($parent,$designation);
	 foreach($zbmlists as $zbmlist){
	  	$data['User'] 	  = $zbmlist['user_id'];
	  	$data['Parent']   = $zbmlist['parent_id'];
		$data['Name'] 	  = $zbmlist['designation_code'].'-'.$zbmlist['first_name'].' '.$zbmlist['last_name'];
		$data['Designation']  = $zbmlist['designation_id'];
	  	$data['Headquater']   = $zbmlist['headquater_id'];
		$zbmdata[] 	  = $data;
	  }
	  $Arrforencode = array('A'=>'NO','B'=>'NO','C'=>'NO','D'=>'NO');
	  if(!empty($bedata)){
	    $Arrforencode['A'] =  'YES';
	  }
	  if(!empty($abmdata)){
	    $Arrforencode['B'] =  'YES';
	  }
	  if(!empty($rbmdata)){
	    $Arrforencode['C'] =  'YES';
	  }
	  if(!empty($zbmdata)){
	     $Arrforencode['D'] =  'YES';
	  }
	  echo json_encode(array('success'=>'YES','BE'=>$bedata,'ABM'=>$abmdata,'RBM'=>$rbmdata,'ZBM'=>$zbmdata)+$Arrforencode); exit;
     break;
	case 'BElist':
	 $designation = getDesignattion($parent);
	 $belists = getBElist($parent,$designation);
	 foreach($belists as $belist){
	  	$data['User'] 	  = $belist['user_id'];
	  	$data['Parent']   = $belist['parent_id'];
		$data['Name'] 	  = $belist['designation_code'].'-'.$belist['first_name'].' '.$belist['last_name'];
		$data['Designation']  = $belist['designation_id'];
	  	$data['Headquater']   = $belist['headquater_id'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'ABMlist':
	 $designation = getDesignattion($parent);
	 $abmlists = getABMlist($parent,$designation);
	 foreach($abmlists as $abmlist){
	  	$data['User'] 	  = $abmlist['user_id'];
	  	$data['Parent']   = $abmlist['parent_id'];
		$data['Name'] 	  = $abmlist['designation_code'].'-'.$abmlist['first_name'].' '.$abmlist['last_name'];
		$data['Designation']  = $abmlist['designation_id'];
	  	$data['Headquater']   = $abmlist['headquater_id'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'ZBMlist':
	 $designation = getDesignattion($parent);
	 $zbmlists = getZBMlist($parent,$designation);
	 foreach($zbmlists as $zbmlist){
	  	$data['User'] 	  = $zbmlist['user_id'];
	  	$data['Parent']   = $zbmlist['parent_id'];
		$data['Name'] 	  = $zbmlist['designation_code'].'-'.$zbmlist['first_name'].' '.$zbmlist['last_name'];
		$data['Designation']  = $zbmlist['designation_id'];
	  	$data['Headquater']   = $zbmlist['headquater_id'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'RBMlist':
	 $designation = getDesignattion($parent);
	 $rbmlists = getRBMlist($parent,$designation);
	 foreach($rbmlists as $rbmlist){
	  	$data['User'] 	  = $rbmlist['user_id'];
	  	$data['Parent']   = $rbmlist['parent_id'];
		$data['Name'] 	  = $rbmlist['designation_code'].'-'.$rbmlist['first_name'].' '.$rbmlist['last_name'];
		$data['Designation']  = $rbmlist['designation_id'];
	  	$data['Headquater']   = $rbmlist['headquater_id'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'MeetingType':
	 $meetings = getMeetingType($parent);
	 foreach($meetings as $meeting){
	  	$data['Type'] 	    = $meeting['type_id'];
	  	$data['TypeName']   = $meeting['type_name'];
		$data['WorkType']   = $meeting['act_id'];
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
	case 'PatchList':
	 $designation = getDesignattion($parent);
	 $getpatchlist = getPatchList($parent,$designation,$patch_id);
	 foreach($getpatchlist as $patch){
	  	$data['Patch'] 	  = $patch['patch_id'];
	  	$data['PatchCode'] = $patch['patchcode'];
		$data['PatchName'] = $patch['patch_name'];
		$data['City'] = $patch['city_id'];
		$data['Headquater'] = $patch['headquater_id'];
		$newdata[] 	  = $data;
	  }
	break;
	case 'Stockist':
	 $designation = getDesignattion($parent);
	 $stockists = getStockist($parent,$designation,$patch_id);
	 foreach($stockists as $stockist){
	  	$data['Stockist'] 	 = $stockist['stockist_id'];
		$data['StockistName'] 	 = $stockist['stockist_name'];
		$data['Headquater'] 		= $stockist['headquater_id'];
		$newdata[] = $data;
	  }
	break;
	case 'WorkWith':
	   $designation = getDesignattion($parent);
	   $belists = getBElist($parent,$designation);
	 foreach($belists as $belist){
	    $data = array();
	  	$data['User'] 	  = $belist['user_id'];
	  	$data['Parent']   = $belist['parent_id'];
		$data['Name'] 	  = $belist['designation_code'].'-'.$belist['first_name'].' '.$belist['last_name'];
		$bedata[] 	  = $data;
	  }
	  $abmlists = getABMlist($parent,$designation);
	 foreach($abmlists as $abmlist){
	    $data = array();
	  	$data['User'] 	  = $abmlist['user_id'];
	  	$data['Parent']   = $abmlist['parent_id'];
		$data['Name'] 	  = $abmlist['designation_code'].'-'.$abmlist['first_name'].' '.$abmlist['last_name'];
		$abmdata[] 	  = $data;
	  }
	  $rbmlists = getRBMlist($parent,$designation);
	 foreach($rbmlists as $rbmlist){
	    $data = array();
	  	$data['User'] 	  = $rbmlist['user_id'];
	  	$data['Parent']   = $rbmlist['parent_id'];
		$data['Name'] 	  = $rbmlist['designation_code'].'-'.$rbmlist['first_name'].' '.$rbmlist['last_name'];
		$rbmdata[] 	  = $data;
	  }
	  $getpatchlist = getPatchList($parent,$designation,$patch_id);
	 foreach($getpatchlist as $patch){
	    $data = array();
	  	$data['Patch'] 	  = $patch['patch_id'];
	  	$data['PatchCode'] = $patch['patchcode'];
		$data['PatchName'] = $patch['patch_name'];
		$data['City'] = $patch['city_id'];
		$data['Headquater'] = $patch['headquater_id'];
		$patchdata[] 	  = $data;
	  }
	  echo json_encode(array('success'=>'YES','message'=>$bedata,'message1'=>$abmdata,'message2'=>$rbmdata,'message3'=>$patchdata)); exit;
	break;
	case 'Gift':
		 $giftitems = getGiftItems($parent);
		 foreach($giftitems as $gifts){
			$data = array();
			$data['Gift'] 	  = $gifts['gift_id'];
			$data['GiftName'] = $gifts['gift_name'];
			$data['Quantity'] = $gifts['assigned_quantity'];
			$data['ValidFrom'] = $gifts['valid_from'];
			$data['ValidTo'] = $gifts['valid_to'];
			$data['Assined'] = $gifts['assigned_date'];
			$data['User'] = $gifts['user_id'];
			$newdata[] 	  = $data;
		  }
	break; 
}
if(!empty($newdata)){
  echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
}else{
  echo json_encode(array('success'=>'NO','message'=>'There is no record found!')); exit;
}

function getHeadquater($param=false){
   $where = '';
  // if($param){
     $where = " AND EL.user_id='".$param."'";
   //} 
   $designation = getDesignattion($param);
   $belist = getBElist($param,$designation);
   $users[] = $param;
   foreach($belist as $be){
     $users[] = $be['user_id'];
   }
   //print_r($users);die;
   $sql = "SELECT HT.* FROM headquater HT INNER JOIN emp_locations EL ON EL.headquater_id=HT.headquater_id WHERE EL.user_id IN(".implode(',',$users).") GROUP BY HT.headquater_id";
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
function getDoctors($param=false,$designation,$patch_id) {
	 $where = '';
	 $where = getconditionBasedBEHeadquater($param,$designation,$patch_id);
   	$sql = "SELECT DT.doctor_id,DT.doctor_code,DT.doctor_name,DT.street_id,PT.patch_id FROM crm_doctors AS DT INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id WHERE 1".$where." ORDER BY DT.doctor_name ASC";
  
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}
function getChemist($param=false,$designation,$patch_id){
   $where = '';
   $where = getconditionBasedBEHeadquater($param,$designation,$patch_id); 
   $sql = "SELECT CT.*,PT.patch_id FROM crm_chemists CT 
   		   INNER JOIN crm_doctor_chemists DCT ON DCT.chemist_id=CT.chemist_id
		   INNER JOIN crm_doctors DT ON DT.doctor_id=DCT.doctor_id 
		   INNER JOIN patchcodes AS PT ON PT.patch_id=DT.patch_id WHERE 1".$where." GROUP BY CT.chemist_id ORDER BY CT.chemist_name ASC";//print_r($sql);die;
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
	// $sql = "SELECT * FROM headquater WHERE 1";
	$designation = getDesignattion($param);
   $belist = getBElist($param,$designation);
   $users[] = $param;
   foreach($belist as $be){
     $users[] = $be['user_id'];
   }
   //print_r($users);die;
   $sql = "SELECT HT.* FROM headquater HT INNER JOIN emp_locations EL ON EL.headquater_id=HT.headquater_id WHERE EL.user_id IN(".implode(',',$users).") GROUP BY HT.headquater_id";
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
function getPatchList($param,$designation,$patch_id){
    $where = getconditionBasedBEHeadquater($param,$designation,$patch_id);
    $sql = "SELECT PT.patch_id,PT.patchcode,PT.patch_name,PT.city_id,PT.headquater_id FROM patchcodes AS PT  WHERE 1".$where." ORDER BY PT.patch_name ASC";
   //print_r($sql);die;
	$execute = mysql_query($sql);
	while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}

function getStockist($param=false,$designation,$patch_id) {
	$where = '';
	$where = getconditionBasedBEHeadquater($param,$designation,$patch_id);
   $sql = "SELECT PT1.* FROM crm_stockists PT1 INNER JOIN  crm_stockists_hq PT ON PT.stockist_id=PT1.stockist_id WHERE 1".$where."";
   //$sql = "SELECT PT1.* FROM crm_stockists PT1 INNER JOIN  `headquater` AT ON AT.headquater_id = PT1.headquater_id WHERE AT.region_id IN(SELECT region_id FROM `headquater` PT WHERE 1".$where.") ORDER BY PT1.stockist_name ASC";//print_r($sql);die;
   $execute = mysql_query($sql);
   $data = array();
   while($result = mysql_fetch_assoc($execute)){
   		$data[] = $result;
   }
   return $data;
}

 function getconditionBasedBEHeadquater($param=false,$designation,$patch_id){
      if($designation==8){
	     $belist = array($param);
	  }else{
	     $belists = getBElist($param,$designation); 
		 $belist = array();
		 foreach($belists as $beids){
		   $belist[] =  $beids['user_id'];
		 }
	  }
      if($patch_id>0){
	     $where = " AND PT.patch_id='".$patch_id."'";
		 return $where;
	  }
	  $sql = "SELECT * FROM emp_locations WHERE user_id IN(".implode(',',$belist).")"; 
	  $execute = mysql_query($sql);
	  while($locations = mysql_fetch_assoc($execute)){
	   $headquaters[] = $locations['headquater_id'];
	 }
	 $where = " AND PT.headquater_id IN(".implode(',',$headquaters).")";
   return $where;
 }
 
 function getBElist($parent,$designation){
	$childusers = getChildUser(array($parent),1);
	$belist = array();
	if($designation==8){
	   return array();
	}
	$sql = "SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=8 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$belist[] = $result;
	 }
	 return $belist;
}

function getABMlist($parent,$designation){
	$abmlist = array();
	if($designation==7){
	   return array();
	}
	if($designation<7){
	   $childusers = getChildUser(array($parent),1);
	}else{
	   $childusers = getParentUser(array($parent),1);
	}
	$sql = "SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=7 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$abmlist[] = $result;
	 }
	 return $abmlist;
}
function getRBMlist($parent,$designation){
	$rbmlist = array();
	if($designation==6){
	   return array();
	}
	if($designation<6){
	   $childusers = getChildUser(array($parent),1);
	}else{
	   $childusers = getParentUser(array($parent),1);
	}
	$sql = "SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=6 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$rbmlist[] = $result;
	 }
	 return $rbmlist;
}
function getZBMlist($parent,$designation){
	$rbmlist = array();
	if($designation==5){
	   return array();
	}
	$childusers = getParentUser(array($parent),1);
	$sql = "SELECT EL.*,DG.designation_code,EH.headquater_id FROM employee_personaldetail EL INNER JOIN designation DG ON DG.designation_id=EL.designation_id LEFT JOIN emp_locations EH ON EH.user_id=EL.user_id WHERE EL.user_id IN(".implode(',',$childusers).") AND EL.designation_id=5 AND EL.delete_status='0' GROUP BY EL.user_id ORDER BY EL.first_name ASC";
	$execute = mysql_query($sql);
	 while($result = mysql_fetch_assoc($execute)){
			$rbmlist[] = $result;
	 }
	 return $rbmlist;
}

function getChildUser($param,$count){
   $sql = "SELECT EL.user_id FROM employee_personaldetail EL
   		   WHERE EL.parent_id IN('".implode("','",$param)."') AND EL.delete_status='0' AND designation_id IN(8,7,6,5)";//print_r( $sql);die;
	  $execute = mysql_query($sql);
	  //$user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$param[] = $result['user_id'];
	   }
	   if($count<4){
	   		$count = $count+1;
		    return getChildUser($param,$count);
	   }else{
	     return $param;
	   }
}
function getParentUser($param,$count){
   $sql = "SELECT EL.parent_id FROM employee_personaldetail EL
   		   WHERE EL.user_id IN('".implode("','",$param)."') AND EL.delete_status='0' AND designation_id IN(8,7,6,5)";//print_r( $sql);die;
	  $execute = mysql_query($sql);
	  //$user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$param[] = $result['parent_id'];
	   }
	   if($count<4){
	   		$count = $count+1;
		    return getParentUser($param,$count);
	   }else{
	     return $param;
	   }
}
function getDesignattion($parent){
	$sql = "SELECT designation_id FROM employee_personaldetail EL WHERE EL.user_id='".$parent."'";//print_r( $sql);die;
	$execute = mysql_query($sql);
	$result = mysql_fetch_assoc($execute);
	return $result['designation_id'];
}
 function getGiftItems($param){
	 $where = '';
     $data = array();
	 $designation = getDesignattion($param);
	 if($designation==8){
	     $belist = array($param);
	  }else{
	     $belists = getBElist($param,$designation); 
		 $belist = array();
		 foreach($belists as $beids){
		   $belist[] =  $beids['user_id'];
		 }
	  }
	 $sql = "SELECT GA.*,AG.gift_name FROM employee_personaldetail EL
   		     INNER JOIN app_gift_assigned GA ON GA.user_id=EL.user_id
   		     INNER JOIN app_gifts AG ON AG.gift_id=GA.gift_id WHERE EL.user_id IN(".implode(',',$belist).") AND EL.delete_status='0' AND AG.isActive='1'";
	  $execute = mysql_query($sql);
	  $user_ids = array();
	   while($result = mysql_fetch_assoc($execute)){
			$data[] = $result;
			
	   }
   return $data;
 }
?>