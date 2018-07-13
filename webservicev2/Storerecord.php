<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class Storerecord extends commonclass{
    public function DoctorVisit($postdata){
		
		$user_id = isset($postdata['user_id'])?$postdata['user_id']:0;
		$doctor_id = isset($postdata['doctor_id'])?$postdata['doctor_id']:0;
		$activity_name = isset($postdata['activity_name'])?$postdata['activity_name']:'';
		$calldate = isset($postdata['call_date'])?date('Y-m-d',strtotime($postdata['call_date'])):'';
		$call_detail = isset($postdata['call_detail'])?$postdata['call_detail']:'';
		$call_time = isset($postdata['call_time'])?$postdata['call_time']:'';
		$be_visit = isset($postdata['be_visit'])?$postdata['be_visit']:'';
		$abm_visit = isset($postdata['abm_visit'])?$postdata['abm_visit']:'';
		$rbm_visit = isset($postdata['rbm_visit'])?$postdata['rbm_visit']:'';
		$zbm_visit = isset($postdata['zbm_visit'])?$postdata['zbm_visit']:'';
		
		$gift1 = isset($postdata['gift1'])?$postdata['gift1']:'';
		$gift2 = isset($postdata['gift2'])?$postdata['gift2']:'';
		$giftquantity1 = isset($postdata['giftquantity1'])?$postdata['giftquantity1']:'';
		$giftquantity2 = isset($postdata['giftquantity2'])?$postdata['giftquantity2']:'';
		$work_with_ho = isset($postdata['work_with_ho'])?$postdata['work_with_ho']:0;
		$imei_no = isset($postdata['imei_no'])?$postdata['imei_no']:'';
		$loc_lat = isset($postdata['loc_lat'])?$postdata['loc_lat']:'';
		$loc_lng = isset($postdata['loc_lng'])?$postdata['loc_lng']:'';
		
		$exceute = $this->query("SELECT COUNT(1) AS CNT FROM app_doctor_visit WHERE user_id='".$user_id."' AND doctor_id='".$doctor_id."' AND call_date='".$calldate."'");
	    $check_duplicate = $this->fetchRow($exceute);
		if($check_duplicate['CNT']>1){
		  return array('success'=>'NO','message'=>'For selected doctor report already submitted for this date!');
		}
		$exceute = $this->query("SELECT reporing_lock FROM app_userdetails WHERE user_id='".$user_id."'");
	    $lockcheck = $this->fetchRow($exceute);
		if($lockcheck['reporing_lock']=='1'){
		  return array('success'=>'NO','message'=>'Your reporting has been blocked.Please contact your administrator!');
		}
		$syncstatus = '';
		if($sync){
			$syncstatus = ",sync=1,sync_date=NOW()"; 
			$this->query("UPDATE app_userdetails SET last_sync_date=NOW() WHRE user_id='".$user_id."'");  
		}
		$check = $this->checkPreviusReport($user_id,$calldate);
		//return array('success'=>'NO','message'=>$check);
		//print_r($check);die;
		if($check['response']=='0'){
		   return array('success'=>'NO','message'=>$check['message']);
		}
		$sql = "INSERT INTO app_doctor_visit SET 
											 user_id='".$user_id."',
											 doctor_id='".$doctor_id."',
											 call_date='".$calldate."',
											 call_time='".$call_time."',
											 call_duration='".$call_duration."',
											 activities='".$activity_name."',
											 call_detail='".$call_detail."',
											 be_visit='".$be_visit."',
											 abm_visit='".$abm_visit."',
											 zbm_visit='".$zbm_visit."',
											 rbm_visit='".$rbm_visit."',
											 date_added=NOW(),
											 gift1='".$gift1."',
											 gift2='".$gift2."',
											 gift1_unit='".$giftquantity1."',
											 gift2_unit='".$giftquantity2."',
											 work_with_ho='".$work_with_ho."',
											 imei_no='".$imei_no."',
											 loc_lat='".$loc_lat."',
											 loc_lng='".$loc_lng."'".$syncstatus;
											 
			$execute = $this->query($sql);
			$visit_id = mysql_insert_id();
			$product[] = isset($postdata['product1'])?$postdata['product1']:0;
			$product[] = isset($postdata['product2'])?$postdata['product2']:0;
			$product[] = isset($postdata['product3'])?$postdata['product3']:0;
			$product[] = isset($postdata['product4'])?$postdata['product4']:0;
			$product[] = isset($postdata['product5'])?$postdata['product5']:0;
			$product[] = isset($postdata['product6'])?$postdata['product6']:0;
			$product[] = isset($postdata['product7'])?$postdata['product7']:0;
			$product[] = isset($postdata['product8'])?$postdata['product8']:0;
			$product[] = isset($postdata['product9'])?$postdata['product9']:0;
			$product[] = isset($postdata['product10'])?$postdata['product10']:0;
			$product[] = isset($postdata['product11'])?$postdata['product11']:0;
			$product[] = isset($postdata['product12'])?$postdata['product12']:0;
			
			$productunit[] = isset($postdata['productunit1'])?$postdata['productunit1']:0;
			$productunit[] = isset($postdata['productunit2'])?$postdata['productunit2']:0;
			$productunit[] = isset($postdata['productunit3'])?$postdata['productunit3']:0;
			$productunit[] = isset($postdata['productunit4'])?$postdata['productunit4']:0;
			$productunit[] = isset($postdata['productunit5'])?$postdata['productunit5']:0;
			$productunit[] = isset($postdata['productunit6'])?$postdata['productunit6']:0;
			$productunit[] = isset($postdata['productunit7'])?$postdata['productunit7']:0;
			$productunit[] = isset($postdata['productunit8'])?$postdata['productunit8']:0;
			$productunit[] = isset($postdata['productunit9'])?$postdata['productunit9']:0;
			$productunit[] = isset($postdata['productunit10'])?$postdata['productunit10']:0;
			$productunit[] = isset($postdata['productunit11'])?$postdata['productunit11']:0;
			$productunit[] = isset($postdata['productunit12'])?$postdata['productunit12']:0;
			foreach($product as $key=>$productid){
				if($productid>0){
				 	$this->query("INSERT INTO app_doctorvisit_product SET visit_id='".$visit_id."',product='".$productid."',`unit`='".$productunit[$key]."'");
				
				}
		 }
		 $this->query("UPDATE app_userdetails SET last_reported='".$calldate."' WHERE user_id='".$user_id."'");
		 return array('success'=>'YES','message'=>'Record has been Inserted');									 
	}
	
	public function DoctorvisitSync($postData){
	   $allData = json_decode($postData['syncData'], true);	   
	   $finalretun = array();
	   foreach($allData as $postvisit){
	       // $this->query("INSERT INTO app_test SET request_date='".json_encode($postvisit)."'");
		   $postvisit['sync'] = 1;
		   $response = $this->DoctorVisit($postvisit);
		   if($response['success']=='YES'){
		     $visitid = array();
			 $visitid['visit_id'] = $postvisit['visit_id'];
			 $finalretun[] = $visitid;
		   }else{
		     return array('success'=>'NO','message'=>$response['message'],'response'=>$finalretun);
		   }
	   }
	  return array('success'=>'YES','message'=>'Data Synched','response'=>$finalretun);
	}
	public function checkPreviusReport_old($user_id,$call_date){
	   $exceute = $this->query("SELECT * FROM app_userdetails WHERE user_id='".$user_id."'");
	   $last_reporting = $this->fetchRow($exceute);
	   //$date1 = $call_date;
	   $date2 = $last_reporting['last_reported'];
	   $allowdates = $last_reporting['allow_manual_report'];
	    
		$days_ago3 = date('Y-m-d', strtotime('-3 day',strtotime(date('Y-m-d'))));
		if(strtotime($call_date) > strtotime(date('Y-m-d'))){
		  return array('response'=>0,'message'=>'Your report could not be accepted because '.$call_date.' is greater than todays date!!');
		}
		if($last_reporting['check_validation']=='0'){
		   //return array('response'=>1,'message'=>'Validation By pass by admin');
		}
		if(in_array($call_date,explode(',',$allowdates)) && $allowdates!=''){
		   return array('response'=>1,'message'=>'Manual Date allow!');
		}
		if(strtotime($call_date) < strtotime($days_ago3) && $last_reporting['check_validation']=='1'){
		  return array('response'=>0,'message'=>'You can not report more than 3 days back!');
		}
		
		if($date2!='0000-00-00' && (strtotime($call_date) < strtotime($date2))){
			  return array('response'=>0,'message'=>'your last reporting date is '.$date2.'. You can not submit report before this date!!');
		}
		
		$days_ago4 = date('Y-m-d', strtotime('+1 day',strtotime($date2)));
		//return array('response'=>0,'message'=>$days_ago4);
		if($days_ago4!='0000-00-00' && (strtotime($call_date) > strtotime($days_ago4))){
			  //return array('response'=>0,'message'=>'your last reporting date is '.$date2.'. You can not skip report date!!');
		}
		$days = floor((strtotime($call_date)- strtotime($date2))/(60*60*24));
		//return array('response'=>0,'message'=>$days);
		if($date2!='0000-00-00' && $days>1){
		     //$date2 = $last_reporting['last_reported'];
		     $current = strtotime('+1 day',strtotime($date2));
			 $last = strtotime('-1 day',strtotime($call_date));
			 //$last = strtotime($call_date);
			 while( $current <= $last ) {
				$vacant_date = date('Y-m-d', $current);
				$current = strtotime('+1 day', $current);
				
				$exceute = $this->query("SELECT COUNT(1) AS CNT FROM holidays WHERE holiday_date='".$vacant_date."'");
	   		   $holiday = $this->fetchRow($exceute);
			   if($holiday['CNT']<=0){			   
				   $leavecheck = $this->query("SELECT COUNT(1) AS CNT FROM leaverequests WHERE leave_from>='".$vacant_date."' AND leave_to<='".$vacant_date."'");
	   		    $leavs = $this->fetchRow($leavecheck);
			    if($leavs['CNT']<=0 && date('l', strtotime($vacant_date))!='Sunday'){
				   return array('response'=>0,'message'=>'You can not skip reporting date('.$vacant_date.')!!');
				}else{
				   return array('response'=>1,'message'=>'vacant date was holiday or leave!!');
				}
			   }	
			  }
		}
	  return array('response'=>1,'message'=>'correcte date!!');	
		
	}
	
	public function checkPreviusReport($user_id,$call_date){
	   $exceute = $this->query("SELECT * FROM app_userdetails WHERE user_id='".$user_id."'");
	   $last_reporting = $this->fetchRow($exceute);
	   //$date1 = $call_date;
	   $lastreporting = $last_reporting['last_reported'];
	   $allowdates = $last_reporting['allow_manual_report'];
	    
		$days_ago3 = date('Y-m-d', strtotime('-3 day',strtotime(date('Y-m-d'))));
		$lastreporting_next1 = date('Y-m-d', strtotime('+1 day',strtotime($lastreporting)));
		$lastreporting_next2 = date('Y-m-d', strtotime('+2 day',strtotime($lastreporting)));
		//Next Date Report
		if($call_date > date('Y-m-d')){
		  return array('response'=>0,'message'=>'Your report could not be accepted because '.$call_date.' is greater than todays date!!');
		}
		//Previous Date Report
		if($call_date<$lastreporting){
			  return array('response'=>0,'message'=>'your last reporting date is '.$lastreporting.'. You can not submit report before this date!!');
		}
		$validate = 0;
		if($call_date>$lastreporting_next1){
			 while($call_date>$lastreporting_next1){
			    //Weekend off
				if(date('l', strtotime($lastreporting_next1))!='Sunday'){
				   //Holiday Check
					$exceute = $this->query("SELECT COUNT(1) AS CNT FROM holidays WHERE holiday_date='".$lastreporting_next1."'");
					$holiday = $this->fetchRow($exceute);
					if($holiday['CNT']<=0){
					   /*$leavecheck = $this->query("SELECT COUNT(1) AS CNT FROM leaverequests WHERE user_id='".$user_id."' AND leave_from>='" .$lastreporting_next1."' AND leave_to<='".$lastreporting_next1."'");
					   //return "SELECT COUNT(1) AS CNT FROM leaverequests WHERE leave_from>='" .$lastreporting_next1."' AND leave_to<='".$lastreporting_next1."'";
						$leavs = $this->fetchRow($leavecheck);
						if($leavs['CNT']<=0){
						   return array('response'=>0,'message'=>'You can not skip reporting date('.$lastreporting_next1.')!!'); 
						}*/
						return array('response'=>0,'message'=>'You can not skip reporting date('.$lastreporting_next1.')!!');
					} 
				}
				$lastreporting_next1 = date('Y-m-d', strtotime('+1 day',strtotime($lastreporting_next1)));
			 }
		  }	 
		  
		if($call_date < $days_ago3 && $last_reporting['check_validation']=='1'){
		  return array('response'=>0,'message'=>'You can not report more than 3 days back!');
		}  
		
		  
	  return array('response'=>1,'message'=>'correcte date!!');	
   }
	
	public function ChemistvisitSync($postdata){
			$user_id = isset($postdata['user_id'])?$postdata['user_id']:0;
			$chemist_id = isset($postdata['chemist_id'])?$postdata['chemist_id']:0;
			$call_detail = isset($postdata['call_detail'])?$postdata['call_detail']:'';
			$product1 = isset($postdata['product1'])?$postdata['product1']:0;
			$product2 = isset($postdata['product2'])?$postdata['product2']:0;
			$product3 = isset($postdata['product3'])?$postdata['product3']:0;
			$product4 = isset($postdata['product4'])?$postdata['product4']:0;
			$product5 = isset($postdata['product5'])?$postdata['product5']:0;
			$unit1 = isset($postdata['unit1'])?$postdata['unit1']:0;
			$unit2 = isset($postdata['unit2'])?$postdata['unit2']:0;
			$unit3 = isset($postdata['unit3'])?$postdata['unit3']:0;
			$unit4 = isset($postdata['unit4'])?$postdata['unit4']:0;
			$unit5 = isset($postdata['unit5'])?$postdata['unit5']:0;
			$be_visit = isset($postdata['be_visit'])?$postdata['be_visit']:'';
			$abm_visit = isset($postdata['abm_visit'])?$postdata['abm_visit']:'';
			$zbm_visit = isset($postdata['zbm_visit'])?$postdata['zbm_visit']:'';
			$rbm_visit = isset($postdata['rbm_visit'])?$postdata['rbm_visit']:'';
			$calldate = isset($postdata['call_date'])?date('Y-m-d',strtotime($postdata['call_date'])):'';
			$imei_no = isset($postdata['imei_no'])?$postdata['imei_no']:'';
			$loc_lat = isset($postdata['loc_lat'])?$postdata['loc_lat']:'';
			$loc_lng = isset($postdata['loc_lng'])?$postdata['loc_lng']:'';
			$sync = isset($postdata['sync'])?$postdata['sync']:'';
			if($sync){
				$syncstatus = ",sync=1,sync_date=NOW()"; 
				$this->query("UPDATE app_userdetails SET last_sync_date=NOW() WHRE user_id='".$user_id."'");  
			}
			
			$sql = "INSERT INTO app_chemist_visit 
							SET user_id		=	'".$user_id."',
								chemist_id	=	'".$chemist_id."',
								call_date	=	'".$calldate."',
								product1	=	'".$product1."',
								product2	=	'".$product2."',
								product3	=	'".$product3."',
								product4	=	'".$product4."',
								product5	=	'".$product5."',
								unit1		=	'".$unit1."',
								unit2		=	'".$unit2."',
								unit3		=	'".$unit3."',
								unit4		=	'".$unit4."',
								unit5		=	'".$unit5."',
								call_detail	=	'".$call_detail."',
								be_visit	=	'".$be_visit."',
								abm_visit	=	'".$abm_visit."',
								zbm_visit	=	'".$zbm_visit."',
								rbm_visit	=	'".$rbm_visit."',
								date_added	=	NOW(),
								imei_no='".$imei_no."',
								loc_lat='".$loc_lat."',
								loc_lng='".$loc_lng."'".$syncstatus;
			//echo "INSERT INTO app_test SET request_date='".$sql."'";die;								 
			$execute = $this->query($sql);
			//$this->query("INSERT INTO app_test SET request_date='".$sql."'");
		return array('success'=>'YES','message'=>'Record has been Submitted');	
	}
	
	public function StockistvisitSync($postdata){
			$user_id = isset($postdata['user_id'])?$postdata['user_id']:0;
			$stockist_id = isset($postdata['stockist_id'])?$postdata['stockist_id']:0;
			$order_detail = isset($postdata['order_detail'])?$postdata['order_detail']:'';
			$issues_detail = isset($postdata['issues_detail'])?$postdata['issues_detail']:'';
			$be_visit = isset($postdata['be_visit'])?$postdata['be_visit']:'';
			$abm_visit = isset($postdata['abm_visit'])?$postdata['abm_visit']:'';
			$zbm_visit = isset($postdata['zbm_visit'])?$postdata['zbm_visit']:'';
			$rbm_visit = isset($postdata['rbm_visit'])?$postdata['rbm_visit']:'';
			$sync = isset($postdata['sync'])?$postdata['sync']:'';
			$imei_no = isset($postdata['imei_no'])?$postdata['imei_no']:'';
			$loc_lat = isset($postdata['loc_lat'])?$postdata['loc_lat']:'';
			$loc_lng = isset($postdata['loc_lng'])?$postdata['loc_lng']:'';
			$call_date = isset($postdata['call_date'])?$postdata['call_date']:'';
			if($sync){
				$syncstatus = ",sync=1,sync_date=NOW()"; 
				$this->query("UPDATE app_userdetails SET last_sync_date=NOW() WHRE user_id='".$user_id."'");  
			}  
			 $sql = "INSERT INTO app_stockist_visit SET 
								user_id='".$user_id."',
								stockist_id='".$stockist_id."',
								orderdetail='".$order_detail."',
								issues='".$issues_detail."',
								be_visit='".$be_visit."',
								abm_visit='".$abm_visit."',
								zbm_visit='".$zbm_visit."',
								rbm_visit='".$rbm_visit."',
								date_added=NOW(),
								work_with_ho='".$work_with_ho."',
								call_date = '".$call_date."'".$syncstatus.$locations;
		$execute = $this->query($sql);
		//$this->query("INSERT INTO app_test SET request_date='".json_encode($postdata)."'");
		return array('success'=>'YES','message'=>'Record has been Submitted');
	}
	
	public function MeetingSync($postdata){
		$user_id = isset($postdata['user_id'])?$postdata['user_id']:0;
		$headquater_id = isset($postdata['headquater_id'])?$postdata['headquater_id']:0;
		$meetingtype_id = isset($postdata['meetingtype_id'])?$postdata['meetingtype_id']:'';
		$metting_detail = isset($postdata['metting_detail'])?$postdata['metting_detail']:'';
		$meeting_date = isset($postdata['meeting_date'])?date('Y-m-d',strtotime($postdata['meeting_date'])):'';
		$meetingtime_start = isset($postdata['meetingtime_start'])?$postdata['meetingtime_start']:'';
		$meetingtime_end = isset($postdata['meetingtime_end'])?$postdata['meetingtime_end']:'';
		$sync = isset($postdata['sync'])?$postdata['sync']:'';
		$imei_no = isset($postdata['imei_no'])?$postdata['imei_no']:'';
		$loc_lat = isset($postdata['loc_lat'])?$postdata['loc_lat']:'';
		$loc_lng = isset($postdata['loc_lng'])?$postdata['loc_lng']:'';
		$other_city = isset($postdata['et_other_city'])?$postdata['et_other_city']:'';
		$this->query("INSERT INTO app_test SET request_date='".json_encode($postdata)."'");
		if($meetingtype_id!=7 && strlen($metting_detail)>50){
		   return array('success'=>'NO','message'=>'Detail can not be more than 50 character!');
		}
		if($meetingtype_id==7 && strlen($metting_detail)<50){
		   return array('success'=>'NO','message'=>'Detail can not be less than 50 character!');
		}
		$exceute = $this->query("SELECT COUNT(1) AS CNT FROM app_meeting WHERE user_id='".$user_id."' AND meeting_date='".$meeting_date."'");
	    $check_duplicate = $this->fetchRow($exceute);
		if($check_duplicate['CNT']>1){
		  return array('success'=>'NO','message'=>'Meeting already submitted for this date!');
		}
		$check = $this->checkPreviusReport($user_id,$meeting_date);
		//return array('success'=>'NO','message'=>$check);
		//print_r($check);die;
		if($check['response']=='0'){
		   return array('success'=>'NO','message'=>$check['message']);
		}
		$syncstatus = '';
		if($sync){
			$syncstatus = ",sync=1,sync_date=NOW()"; 
			$this->query("UPDATE app_userdetails SET last_sync_date=NOW() WHRE user_id='".$user_id."'");  
		}	 			 
	    $sql = "INSERT INTO app_meeting SET 
							user_id='".$user_id."',
							headquater_id='".$headquater_id."',
							meetingtype_id='".$meetingtype_id."',
							metting_detail='".$metting_detail."',
							meeting_date='".$meeting_date."',
							meetingtime_start='".$meetingtime_start."',
							meetingtime_end='".$meetingtime_end."',
							other_city ='".$other_city."',
							date_added=NOW()".$syncstatus;
		$execute = $this->query($sql);
		$this->query("UPDATE app_userdetails SET last_reported='".$meeting_date."' WHERE user_id='".$user_id."'");
		return array('success'=>'YES','message'=>'Record has been Submitted');					
	}
	
	public function StoreTourPlan($postdata){
			$tour_id = isset($postdata['tour_id'])?$postdata['tour_id']:0;
			$user_id = isset($postdata['user_id'])?$postdata['user_id']:0;
			$tour_date = isset($postdata['tour_date'])?$postdata['tour_date']:0;
			$location_id = isset($postdata['patch_id'])?$postdata['patch_id']:'';
			$be_visit = isset($postdata['be_visit'])?$postdata['be_visit']:'';
			$abm_visit = isset($postdata['abm_visit'])?$postdata['abm_visit']:'';
			$zbm_visit = isset($postdata['zbm_visit'])?$postdata['zbm_visit']:'';
			$rbm_visit = isset($postdata['rbm_visit'])?$postdata['rbm_visit']:'';
			$call_type = isset($postdata['call_type'])?$postdata['call_type']:'';
			$other_city = isset($postdata['other_city'])?$postdata['other_city']:'';
			$activity_id = isset($postdata['activity_id'])?$postdata['activity_id']:'';
			$headquater_id = isset($postdata['headquater_id'])?$postdata['headquater_id']:'';
			$loc_type = isset($postdata['loc_type'])?$postdata['loc_type']:'';
			 
			$sql = "INSERT INTO app_tourplan SET 
							user_id='".$user_id."',
							tour_date='".$tour_date."',
							location_id='".$location_id."',
							be_visit='".$be_visit."',
							abm_visit='".$abm_visit."',
							rbm_visit='".$rbm_visit."',
							zbm_visit='".$zbm_visit."',
							call_type='".$call_type."',
							other_city='".$other_city."',
							activity_id='".$activity_id."',
							headquater_id='".$headquater_id."',
							date_added=NOW(),
							local_tour_id='".$tour_id."',
							loc_type='".$loc_type."'";
		$execute = $this->query($sql);
		return array('success'=>'YES','message'=>'Record has been Submitted');
	}
	
	public function updtesyncdate($user_id){
	
	}
	public function ApproveRejectTP($requestData){
	  if($requestData['appstatus']==0){
	  	$update = "accepted=1,accepte_by='".$requestData['user_id']."',accepted_date=NOW()";
	  }elseif($requestData['appstatus']==1){
	  	$update = "rejected=1";
	  }
	  $sql = "UPDATE app_tourplan SET ".$update." WHERE user_id='".$requestData['empid']."' AND (DATE_FORMAT(tour_date,'%Y-%m')= DATE_FORMAT(CURDATE(),'%Y-%m') OR DATE_FORMAT(tour_date,'%Y-%m')= DATE_FORMAT(DATE_ADD(CURDATE(), INTERVAL 1 MONTH),'%Y-%m'))";		//echo $sql;die;
	  
	    $execute = $this->query($sql);
		return array('success'=>'YES','message'=>'Request has been Submitted');
	}
	
	public function NonlistedCall($requestData){
	   $exceute = $this->query("SELECT * FROM patchcodes WHERE patch_id='".$requestData['patch_id']."'");
	    $patchDetails = $this->fetchRow($exceute);
		
	   $this->query("INSERT INTO crm_doctors SET 
	   				 doctor_code='".date('ymdhi').$requestData['patch_id']."',	
					 patch_id='".$requestData['patch_id']."',
					 city_id='".$patchDetails['city_id']."',
					 area_id='".$patchDetails['area_id']."',
					 region_id='".$patchDetails['region_id']."',
					 zone_id='".$patchDetails['zone_id']."',
					 country_id='".$patchDetails['country_id']."',
					 business_unit_id='".$patchDetails['bunit_id']."',
					 headquater_id='".$patchDetails['headquater_id']."',
					 doctor_name='".$requestData['doctor_name']."',
					 create_type=3,
					 created_by='".$requestData['user_id']."'");
	   $requestData['doctor_id'] = mysql_insert_id();
	   //$this->query("INSERT INTO app_test SET request_date='".json_encode($requestData)."'");
	   return $this->DoctorVisit($requestData);
	   //return array('success'=>'NO','message'=>'Request has been Submitted');
	}
}
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
$obj = new Storerecord();

switch($mode){
   case 'DoctorVisit':
      $response = $obj->DoctorVisit($_REQUEST);
   break;
   case 'DVisitSync':
      $response = $obj->DoctorvisitSync($_REQUEST);
   break;
   case 'CVisitSync':
      $response = $obj->ChemistvisitSync($_REQUEST);
   break;
   case 'STisitSync':
      $response = $obj->StockistvisitSync($_REQUEST);
   break;
   case 'MTisitSync':
      $response = $obj->MeetingSync($_REQUEST);
   break;
   case 'TourPlan':
      $response = $obj->StoreTourPlan($_REQUEST);
   break;
   case 'CheckTour':
   break;
   case 'ApprRegectTour':
   	  $response = $obj->ApproveRejectTP($_REQUEST);
   break;
   case 'NonlistedCall':
      $response = $obj->NonlistedCall($_REQUEST); 
   break;
}
echo json_encode($response);exit;

?>
