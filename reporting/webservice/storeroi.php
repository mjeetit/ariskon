<?php
header('Content-type: application/json');
include 'config.php';
 $user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
 $designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
 $apointment_id = isset($_REQUEST['apointment_id'])?$_REQUEST['apointment_id']:0;
 $roiprod1 = isset($_REQUEST['roiprod1'])?$_REQUEST['roiprod1']:'';
 $roiprod2 = isset($_REQUEST['roiprod2'])?$_REQUEST['roiprod2']:'';
 $roiprod3 = isset($_REQUEST['roiprod3'])?$_REQUEST['roiprod3']:'';
 $roiprod4 = isset($_REQUEST['roiprod4'])?$_REQUEST['roiprod4']:'';
 $roiprod5 = isset($_REQUEST['roiprod5'])?$_REQUEST['roiprod5']:'';
 $roiprod6 = isset($_REQUEST['roiprod6'])?$_REQUEST['roiprod6']:'';
 $roiprod7 = isset($_REQUEST['roiprod7'])?$_REQUEST['roiprod7']:'';
 //
 $unitprod1 = isset($_REQUEST['unitprod1'])?$_REQUEST['unitprod1']:'';
 $unitprod2 = isset($_REQUEST['unitprod2'])?$_REQUEST['unitprod2']:'';
 $unitprod3 = isset($_REQUEST['unitprod3'])?$_REQUEST['unitprod3']:'';
 $unitprod4 = isset($_REQUEST['unitprod4'])?$_REQUEST['unitprod4']:'';
 $unitprod5 = isset($_REQUEST['unitprod5'])?$_REQUEST['unitprod5']:'';
 $unitprod6 = isset($_REQUEST['unitprod6'])?$_REQUEST['unitprod6']:'';
 $unitprod7 = isset($_REQUEST['unitprod7'])?$_REQUEST['unitprod7']:'';
 //
 $valueprod1 = isset($_REQUEST['valueprod1'])?$_REQUEST['valueprod1']:'';
 $valueprod2 = isset($_REQUEST['valueprod2'])?$_REQUEST['valueprod2']:'';
 $valueprod3 = isset($_REQUEST['valueprod3'])?$_REQUEST['valueprod3']:'';
 $valueprod4 = isset($_REQUEST['valueprod4'])?$_REQUEST['valueprod4']:'';
 $valueprod5 = isset($_REQUEST['valueprod5'])?$_REQUEST['valueprod5']:'';
 $valueprod6 = isset($_REQUEST['valueprod6'])?$_REQUEST['valueprod6']:'';
 $valueprod7 = isset($_REQUEST['valueprod7'])?$_REQUEST['valueprod7']:'';
  $productpotential = array();
  if($roiprod1>0){
     $productpotential[$roiprod1] = array('Unit'=>$unitprod1,'Value'=>$valueprod1); 
  }
  if($roiprod2>0){
     $productpotential[$roiprod2] = array('Unit'=>$unitprod2,'Value'=>$valueprod2); 
  }
  if($roiprod3>0){
     $productpotential[$roiprod3] = array('Unit'=>$unitprod3,'Value'=>$valueprod3); 
  }
  if($roiprod4>0){
     $productpotential[$roiprod4] = array('Unit'=>$unitprod4,'Value'=>$valueprod4); 
  }
  if($roiprod5>0){
     $productpotential[$roiprod5] = array('Unit'=>$unitprod5,'Value'=>$valueprod5); 
  }
  if($roiprod6>0){
     $productpotential[$roiprod6] = array('Unit'=>$unitprod6,'Value'=>$valueprod6); 
  }
  if($roiprod7>0){
     $productpotential[$roiprod7] = array('Unit'=>$unitprod7,'Value'=>$valueprod7); 
  }
 
 $sql = "SELECT * FROM crm_appoinments WHERE appointment_id='".$apointment_id."'";
 $execute = mysql_query($sql);
 $crmdetail = mysql_fetch_assoc($execute);
 
 $totalamount = $valueprod1 + $valueprod2 + $valueprod3 + $valueprod4 + $valueprod5 + $valueprod6 + $valueprod7;
 $roimonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y'))); 
 
 $insert = "INSERT INTO crm_roi SET doctor_id='".$crmdetail['doctor_id']."',roi_month='".$roimonth."',roi_total_amount='".$totalamount."',added_by='".$user_id."',added_ip='".$_SERVER['REMOTE_ADDR']."'";
 $execute = mysql_query($insert);
	 
  $roi_id = mysql_insert_id();
  foreach($productpotential as $key=>$potential){
     $insert = "INSERT INTO crm_roi_details SET roi_id='".$roi_id."',product_id='".$key."',unit='".$potential['Unit']."',value='".$potential['Value']."'";
	 mysql_query($insert);
  }
echo json_encode(array('success'=>1,'message'=>'Roi Requested Successfully'));
?>