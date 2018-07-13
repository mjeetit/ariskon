<?php
header('Content-type: application/json');
$mode = isset($_REQUEST['mode'])?$_REQUEST['mode']:'';
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
include 'config.php';
switch($mode){
     case 'DoctorVisit':
	 $doctor_id = isset($_REQUEST['doctor_id'])?$_REQUEST['doctor_id']:0;
	 $activity_name = isset($_REQUEST['activity_name'])?$_REQUEST['activity_name']:'';
	 $calldate = isset($_REQUEST['calldate'])?$_REQUEST['calldate']:'';
	 $call_duration = isset($_REQUEST['call_duration'])?$_REQUEST['call_duration']:'';
	 $call_detail = isset($_REQUEST['call_detail'])?$_REQUEST['call_detail']:'';
	 $call_time = isset($_REQUEST['calltime'])?$_REQUEST['calltime']:'';
	 $product1 = isset($_REQUEST['product1'])?$_REQUEST['product1']:0;
	 $product2 = isset($_REQUEST['product2'])?$_REQUEST['product2']:0;
	 $product3 = isset($_REQUEST['product3'])?$_REQUEST['product3']:0;
	 $product4 = isset($_REQUEST['product4'])?$_REQUEST['product4']:0;
	 $product5 = isset($_REQUEST['product5'])?$_REQUEST['product5']:0;
	 $productunit1 = isset($_REQUEST['productunit1'])?$_REQUEST['productunit1']:0;
	 $productunit2 = isset($_REQUEST['productunit2'])?$_REQUEST['productunit2']:0;
	 $productunit3 = isset($_REQUEST['productunit3'])?$_REQUEST['productunit3']:0;
	 $productunit4 = isset($_REQUEST['productunit4'])?$_REQUEST['productunit4']:0;
	 $productunit5 = isset($_REQUEST['productunit5'])?$_REQUEST['productunit5']:0;
	 $be_visit = isset($_REQUEST['be_visit'])?$_REQUEST['be_visit']:'';
	 $abm_visit = isset($_REQUEST['abm_visit'])?$_REQUEST['abm_visit']:'';
	 $zbm_visit = isset($_REQUEST['zbm_visit'])?$_REQUEST['zbm_visit']:'';
	 $rbm_visit = isset($_REQUEST['rbm_visit'])?$_REQUEST['rbm_visit']:'';
	 
	 $sql = "INSERT INTO app_doctor_visit SET user_id='".$user_id."',doctor_id='".$doctor_id."',call_date='".$calldate."',call_time='".$call_time."',call_duration='".$call_duration."',product1='".$product1."',product2='".$product2."',product3='".$product3."',product4='".$product4."',product5='".$product5."',unit1='".$productunit1."',unit2='".$productunit2."',unit3='".$productunit3."',unit4='".$productunit4."',unit5='".$productunit5."',activities='".$activity_name."',call_detail='".$call_detail."',be_visit='".$be_visit."',abm_visit='".$abm_visit."',zbm_visit='".$zbm_visit."',rbm_visit='".$rbm_visit."',date_added=NOW()";
     $execute = mysql_query($sql);
	 break;
	 case 'ChemistVisit':
			 $chemist_id = isset($_REQUEST['chemist_id'])?$_REQUEST['chemist_id']:0;
			 $call_detail = isset($_REQUEST['call_detail'])?$_REQUEST['call_detail']:'';
			 $product1 = isset($_REQUEST['product1'])?$_REQUEST['product1']:0;
			 $product2 = isset($_REQUEST['product2'])?$_REQUEST['product2']:0;
			 $product3 = isset($_REQUEST['product3'])?$_REQUEST['product3']:0;
			 $product4 = isset($_REQUEST['product4'])?$_REQUEST['product4']:0;
			 $product5 = isset($_REQUEST['product5'])?$_REQUEST['product5']:0;
			 $unit1 = isset($_REQUEST['unit1'])?$_REQUEST['unit1']:0;
			 $unit2 = isset($_REQUEST['unit2'])?$_REQUEST['unit2']:0;
			 $unit3 = isset($_REQUEST['unit3'])?$_REQUEST['unit3']:0;
			 $unit4 = isset($_REQUEST['unit4'])?$_REQUEST['unit4']:0;
			 $unit5 = isset($_REQUEST['unit5'])?$_REQUEST['unit5']:0;
			 $be_visit = isset($_REQUEST['be_visit'])?$_REQUEST['be_visit']:'';
			 $abm_visit = isset($_REQUEST['abm_visit'])?$_REQUEST['abm_visit']:'';
			 $zbm_visit = isset($_REQUEST['zbm_visit'])?$_REQUEST['zbm_visit']:'';
			 $rbm_visit = isset($_REQUEST['rbm_visit'])?$_REQUEST['rbm_visit']:'';
			 
			 $sql = "INSERT INTO app_chemist_visit SET user_id='".$user_id."',chemist_id='".$chemist_id."',product1='".$product1."',product2='".$product2."',product3='".$product3."',product4='".$product4."',product5='".$product5."',unit1='".$unit1."',unit2='".$unit2."',unit3='".$unit3."',unit4='".$unit4."',unit5='".$unit5."',call_detail='".$call_detail."',be_visit='".$be_visit."',abm_visit='".$abm_visit."',zbm_visit='".$zbm_visit."',rbm_visit='".$rbm_visit."',date_added=NOW()";
			 $execute = mysql_query($sql);
	 break;
	 case 'StockistVisit':
			 $stockist_id = isset($_REQUEST['stockist_id'])?$_REQUEST['stockist_id']:0;
			 $order_detail = isset($_REQUEST['order_detail'])?$_REQUEST['order_detail']:'';
			 $issues_detail = isset($_REQUEST['issues_detail'])?$_REQUEST['issues_detail']:'';
			 $be_visit = isset($_REQUEST['be_visit'])?$_REQUEST['be_visit']:'';
			 $abm_visit = isset($_REQUEST['abm_visit'])?$_REQUEST['abm_visit']:'';
			 $zbm_visit = isset($_REQUEST['zbm_visit'])?$_REQUEST['zbm_visit']:'';
			 $rbm_visit = isset($_REQUEST['rbm_visit'])?$_REQUEST['rbm_visit']:'';
			 
			 $sql = "INSERT INTO app_stockist_visit SET user_id='".$user_id."',stockist_id='".$stockist_id."',orderdetail='".$order_detail."',issues='".$issues_detail."',be_visit='".$be_visit."',abm_visit='".$abm_visit."',zbm_visit='".$zbm_visit."',rbm_visit='".$rbm_visit."',date_added=NOW()";
			 $execute = mysql_query($sql);
	 break;
	 case 'Meeting':
			 $headquater_id = isset($_REQUEST['headquater_id'])?$_REQUEST['headquater_id']:0;
			 $meetingtype_id = isset($_REQUEST['meetingtype_id'])?$_REQUEST['meetingtype_id']:'';
			 $metting_detail = isset($_REQUEST['metting_detail'])?$_REQUEST['metting_detail']:'';
			 $meeting_date = isset($_REQUEST['meeting_date'])?$_REQUEST['meeting_date']:'';
			 $meetingtime_start = isset($_REQUEST['meetingtime_start'])?$_REQUEST['meetingtime_start']:'';
			 $meetingtime_end = isset($_REQUEST['meetingtime_end'])?$_REQUEST['meetingtime_end']:'';
			 			 
			 $sql = "INSERT INTO app_meeting SET user_id='".$user_id."',headquater_id='".$headquater_id."',meetingtype_id='".$meetingtype_id."',metting_detail='".$metting_detail."',meeting_date='".$meeting_date."',meetingtime_start='".$meetingtime_start."',meetingtime_end='".$meetingtime_end."',date_added=NOW()";
			 $execute = mysql_query($sql);
	 break;
	 case 'NonActvity':
			 $activity_date = isset($_REQUEST['activity_date'])?$_REQUEST['activity_date']:0;
			 $activity_detail = isset($_REQUEST['activity_detail'])?$_REQUEST['activity_detail']:'';
			 			 
			 $sql = "INSERT INTO app_nonactivity SET user_id='".$user_id."',activity_date='".$activity_date."',activity_detail='".$activity_detail."',date_added=NOW()";
			 $execute = mysql_query($sql);
	 break;
	 case 'LeaveRequest':
	     $leve_from = isset($_REQUEST['leave_from'])?$_REQUEST['leave_from']:'';
	     $leave_to = isset($_REQUEST['leave_to'])?$_REQUEST['leave_to']:'';
		 $leave_cl = isset($_REQUEST['type_cl'])?$_REQUEST['type_cl']:'';
		 $leave_sl = isset($_REQUEST['type_sl'])?$_REQUEST['type_sl']:'';
		 $leave_pl = isset($_REQUEST['type_pl'])?$_REQUEST['type_pl']:'';
		 $leave_subject = isset($_REQUEST['leave_subject'])?$_REQUEST['leave_subject']:'';
		 $leave_message = isset($_REQUEST['leave_message'])?$_REQUEST['leave_message']:'';
	     $totalDays = (strtotime($leve_from) - strtotime($leave_to)) / (60 * 60 * 24) + 1;
		 $sql = "INSERT INTO app_testleave SET user_id='".$user_id."',leave_from='".$leve_from."',leave_to='".$leave_to."',total_days='".$totalDays."',subject='".$leave_subject."',contents='".$leave_message."',request_date=NOW(),request_type='1'";
		 $execute = mysql_query($sql);
		 $request_id = mysql_insert_id($execute);
		 if($leave_cl>0){
		    $leavetype = 1;
		 }
		 if($leave_sl>0){
		    $leavetype = 1;
		 }
		 if($leave_pl>0){
		    $leavetype = 1;
		 }
		 //$sql = "INSERT INTO leaverequesttype SET user_id='".$user_id."',request_id='".$request_id."',leave_typeID='".$leavetype."',no_of_days='".$totalDays."',user_by='".$user_id."'";	
		 //$execute = mysql_query($sql);
		
	 break;
	 case 'ExpenseRequest':
	     $exp_amount = isset($_REQUEST['exp_amount'])?$_REQUEST['exp_amount']:'';
		 $exp_fare = isset($_REQUEST['exp_fare'])?$_REQUEST['exp_fare']:'';
		 $exp_destination = isset($_REQUEST['exp_destination'])?$_REQUEST['exp_destination']:'';
		 $exp_detail = isset($_REQUEST['exp_detail'])?$_REQUEST['exp_detail']:'';
		 $exp_date = isset($_REQUEST['exp_date'])?$_REQUEST['exp_date']:'';
		 $exp_mixedamount = isset($_REQUEST['exp_mixedamount'])?$_REQUEST['exp_mixedamount']:'';
		 $expense_id = isset($_REQUEST['expense_id'])?$_REQUEST['expense_id']:'';
		 $sql = "INSERT INTO app_expense_request SET user_id='".$user_id."',expense_amount='".$exp_amount."',travel_destination='".$exp_destination."',fare='".$exp_fare."',expense_detail='".$exp_detail."',expense_date='".$exp_date."',head_id='".$expense_id."',exp_mixedamount='".$exp_mixedamount."',date_added=NOW()";
		 $execute = mysql_query($sql);
	 break;
	 case 'TourPlan':
	        
			 $tour_date = isset($_REQUEST['tour_date'])?$_REQUEST['tour_date']:0;
			 $be_visit = isset($_REQUEST['be_visit'])?$_REQUEST['be_visit']:'';
			 $abm_visit = isset($_REQUEST['abm_visit'])?$_REQUEST['abm_visit']:'';
			 $zbm_visit = isset($_REQUEST['zbm_visit'])?$_REQUEST['zbm_visit']:'';
			 $rbm_visit = isset($_REQUEST['rbm_visit'])?$_REQUEST['rbm_visit']:'';
			 $location_id = isset($_REQUEST['patch_id'])?$_REQUEST['patch_id']:'';
			 
			 $sql = "INSERT INTO app_tourplan SET user_id='".$user_id."',tour_date='".date('Y-m-d',strtotime($tour_date))."',be_visit='".$be_visit."',abm_visit='".$abm_visit."',zbm_visit='".$zbm_visit."',rbm_visit='".$rbm_visit."',location_id='".$location_id."',date_added=NOW()";
			 $execute = mysql_query($sql);
	 break;
	 case 'OtherActivity':
	 		 $start_time = isset($_REQUEST['start_time'])?$_REQUEST['start_time']:'';
			 $end_time = isset($_REQUEST['end_time'])?$_REQUEST['end_time']:'';
			 $activity_detail = isset($_REQUEST['activity_detail'])?$_REQUEST['activity_detail']:'';
			 $activity_date = isset($_REQUEST['activity_date'])?$_REQUEST['activity_date']:'';
			 $sql = "INSERT INTO app_noncallreport SET user_id='".$user_id."',activity_date='".$activity_date."',start_time='".$start_time."',end_time='".$end_time."',activity_detail='".$activity_detail."',date_added=NOW()";
			 $execute = mysql_query($sql);
	 break;
}
?>
