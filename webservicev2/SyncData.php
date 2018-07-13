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
	 
	 $gift1 = isset($_REQUEST['gift1'])?$_REQUEST['gift1']:'';
	 $gift2 = isset($_REQUEST['gift2'])?$_REQUEST['gift2']:'';
	 $giftquantity1 = isset($_REQUEST['giftquantity1'])?$_REQUEST['giftquantity1']:'';
	 $giftquantity2 = isset($_REQUEST['giftquantity2'])?$_REQUEST['giftquantity2']:'';
	 
	 $sql = "INSERT INTO app_doctor_visit SET user_id='".$user_id."',doctor_id='".$doctor_id."',call_date='".$calldate."',call_time='".$call_time."',call_duration='".$call_duration."',product1='".$product1."',product2='".$product2."',product3='".$product3."',product4='".$product4."',product5='".$product5."',unit1='".$productunit1."',unit2='".$productunit2."',unit3='".$productunit3."',unit4='".$productunit4."',unit5='".$productunit5."',activities='".$activity_name."',call_detail='".$call_detail."',be_visit='".$be_visit."',abm_visit='".$abm_visit."',zbm_visit='".$zbm_visit."',rbm_visit='".$rbm_visit."',date_added=NOW(),gift1='".$gift1."',gift2='".$gift2."',gift1_unit='".$giftquantity1."',gift2_unit='".$giftquantity2."'";
     $execute = mysql_query($sql);
	 break;
}	 
?>