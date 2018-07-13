<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class StoreROI extends commonclass{
    public function SaveROI($request){
		$user_id = isset($request['user_id'])?$request['user_id']:0;
		$designation = isset($request['designation'])?$request['designation']:0;
		$apointment_id = isset($request['apointment_id'])?$request['apointment_id']:0;
		$roiprod1 = isset($request['roiprod1'])?$request['roiprod1']:'';
		$roiprod2 = isset($request['roiprod2'])?$request['roiprod2']:'';
		$roiprod3 = isset($request['roiprod3'])?$request['roiprod3']:'';
		$roiprod4 = isset($request['roiprod4'])?$request['roiprod4']:'';
		$roiprod5 = isset($request['roiprod5'])?$request['roiprod5']:'';
		$roiprod6 = isset($request['roiprod6'])?$request['roiprod6']:'';
		$roiprod7 = isset($request['roiprod7'])?$request['roiprod7']:'';
		//
		$unitprod1 = isset($request['unitprod1'])?$request['unitprod1']:'';
		$unitprod2 = isset($request['unitprod2'])?$request['unitprod2']:'';
		$unitprod3 = isset($request['unitprod3'])?$request['unitprod3']:'';
		$unitprod4 = isset($request['unitprod4'])?$request['unitprod4']:'';
		$unitprod5 = isset($request['unitprod5'])?$request['unitprod5']:'';
		$unitprod6 = isset($request['unitprod6'])?$request['unitprod6']:'';
		$unitprod7 = isset($request['unitprod7'])?$request['unitprod7']:'';
		//
		$valueprod1 = isset($request['valueprod1'])?$request['valueprod1']:'';
		$valueprod2 = isset($request['valueprod2'])?$request['valueprod2']:'';
		$valueprod3 = isset($request['valueprod3'])?$request['valueprod3']:'';
		$valueprod4 = isset($request['valueprod4'])?$request['valueprod4']:'';
		$valueprod5 = isset($request['valueprod5'])?$request['valueprod5']:'';
		$valueprod6 = isset($request['valueprod6'])?$request['valueprod6']:'';
		$valueprod7 = isset($request['valueprod7'])?$request['valueprod7']:'';
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
		$extraProd1 = isset($request['ext_product1'])?$request['ext_product1']:'';
		$extraProd2 = isset($request['ext_product2'])?$request['ext_product2']:'';
		$extraProd3 = isset($request['ext_product3'])?$request['ext_product3']:'';
		
		$ext_unit1 = isset($request['ext_unit1'])?$request['ext_unit1']:'';
		$ext_unit2 = isset($request['ext_unit2'])?$request['ext_unit2']:'';
		$ext_unit3 = isset($request['ext_unit3'])?$request['ext_unit3']:'';
		
		$ext_value1 = isset($request['ext_value1'])?$request['ext_value1']:'';
		$ext_value2 = isset($request['ext_value2'])?$request['ext_value2']:'';
		$ext_value3 = isset($request['ext_value3'])?$request['ext_value3']:'';
		
		$exceute = $this->query("SELECT * FROM crm_appointments WHERE appointment_id='".$apointment_id."'");
	    $crmdetail = $this->fetchRow($exceute);
		$newprod = array();
		if($extraProd1>0){
			$productpotential[$extraProd1] = array('Unit'=>$ext_unit1,'Value'=>$ext_value1);
			$newprod[] = array('product_id'=>$extraProd1,'unit'=>$ext_unit1,'value'=>$ext_value3); 
		}
		if($extraProd2>0){
			$productpotential[$extraProd2] = array('Unit'=>$ext_unit2,'Value'=>$ext_value2);
			$newprod[] = array('product_id'=>$extraProd2,'unit'=>$ext_unit2,'value'=>$ext_value2);  
		}
		if($extraProd3>0){
			$productpotential[$extraProd3] = array('Unit'=>$ext_unit3,'Value'=>$ext_value3);
			$newprod[] = array('product_id'=>$extraProd3,'unit'=>$ext_unit3,'value'=>$ext_value3);   
		}
		$totalamount = $valueprod1 + $valueprod2 + $valueprod3 + $valueprod4 + $valueprod5 + $valueprod6 + $valueprod7 + $ext_value1 + $ext_value2 + $ext_value3;
 		$roimonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y'))); 
		
		foreach($newprod as $product){
		  $this->query("INSERT INTO crm_appointment_potential_months SET appointment_id='".$apointment_id."',month='".date('Y-m-d')."',month_total_value='".$totalamount."'");
		  $potential_month_id = mysql_insert_id();
		  $this->query("INSERT INTO crm_appointment_potential_month_products SET potential_month_id='".$potential_month_id."',product_id='".$product['product_id']."',unit='".$product['unit']."',value='".$product['unit']."'");
		}
		
		$this->query("INSERT INTO crm_roi SET doctor_id='".$crmdetail['doctor_id']."',roi_month='".$roimonth."',roi_total_amount='".$totalamount."',added_by='".$user_id."',added_ip='".$_SERVER['REMOTE_ADDR']."'");
		$roi_id = mysql_insert_id();
  foreach($productpotential as $key=>$potential){
       $this->query("INSERT INTO crm_roi_details SET roi_id='".$roi_id."',product_id='".$key."',unit='".$potential['Unit']."',value='".$potential['Value']."'");
  }
		return array('success'=>'YES','message'=>'ROI enrty successfully'); 
	}
}
$obj = new StoreROI();
$response = $obj->SaveROI($_REQUEST);
echo json_encode($response);exit;
?>