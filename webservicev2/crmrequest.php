<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class CRMRequest extends commonclass{
    public function AddCRM($request){
	    //$request = json_decode($request['data'],true);
		
		$this->query("INSERT INTO app_test SET request_date='".json_encode($request)."'");
		$user_id = isset($request['user_id'])?$request['user_id']:0;
		$doctor_id = isset($request['doctor_id'])?$request['doctor_id']:0;
		$activitynature_id = isset($request['activitynature_id'])?$request['activitynature_id']:'3';
		$activity_cost = isset($request['activity_cost'])?$request['activity_cost']:'';
		$activity_detail = isset($request['activity_detail'])?$request['activity_detail']:'';
		$dd_chequedetail = isset($request['dd_chequedetail'])?$request['dd_chequedetail']:'';
		$paybleat = isset($request['paybleat'])?$request['paybleat']:'';
		if($doctor_id==0){
			echo json_encode(array('success'=>'NO','message'=>'Select Doctor then try again!'));exit;
		}
		$chemistlist = $this->getChemist($doctor_id);
		
		$product1 = isset($request['product1'])?$request['product1']:'0';
		$product2 = isset($request['product2'])?$request['product2']:'0';
		$product3 = isset($request['product3'])?$request['product3']:'0';
		$product4 = isset($request['product4'])?$request['product4']:'0';
		$product5 = isset($request['product5'])?$request['product5']:'0';
		$product6 = isset($request['product6'])?$request['product6']:'0';
		$product7 = isset($request['product7'])?$request['product7']:'0';
		$productArr = array_filter(array($product1,$product2,$product3,$product4,$product5,$product6,$product7));
		$exceute = $this->query("SELECT * FROM crm_products WHERE product_id IN(".implode(",",$productArr).")");
	    $products = $this->fetchAll($exceute);
		$productPrices = array();
		foreach($products as $result){
		   $productPrices[$result['product_id']] = $result['mrp_incl_vat'];
		}
	 $monthproductunit = array();
	 $month1 = date('Y-m-d', strtotime(date('Y-m')." -2 month"));
	 $month2 = date('Y-m-d', strtotime(date('Y-m')." -1 month"));
	 $month3 = date('Y-m-d', strtotime(date('Y-m')."  0 month"));
	 $month4 = date('Y-m-d', strtotime(date('Y-m')." +1 month"));
	 $month5 = date('Y-m-d', strtotime(date('Y-m')." +2 month"));
	 $month6 = date('Y-m-d', strtotime(date('Y-m')." +3 month"));
	 $month7 = date('Y-m-d', strtotime(date('Y-m')." +4 month"));
	 $month8 = date('Y-m-d', strtotime(date('Y-m')." +5 month"));
	 $month9 = date('Y-m-d', strtotime(date('Y-m')." +6 month"));
	 
	 //echo "<pre>".$month1."-".$month2."-".$month3."-".$month4."-".$month5."-".$month6."-".$month7."-".$month8."-".$month9;print_r($productMonth);die;
	 
	 //
	 $product1unit1 = isset($request['product1unit1'])?$request['product1unit1']:'0';
	 $product1unit2 = isset($request['product1unit2'])?$request['product1unit2']:'0';
	 $product1unit3 = isset($request['product1unit3'])?$request['product1unit3']:'0';
	 $product1unit4 = isset($request['product1unit4'])?$request['product1unit4']:'0';
	 $product1unit5 = isset($request['product1unit5'])?$request['product1unit5']:'0';
	 $product1unit6 = isset($request['product1unit6'])?$request['product1unit6']:'0';
	 $product1unit7 = isset($request['product1unit7'])?$request['product1unit7']:'0';
	 $product1unit8 = isset($request['product1unit8'])?$request['product1unit8']:'0';
	 $product1unit9 = isset($request['product1unit9'])?$request['product1unit9']:'0';
	 
	 if($product1>0){
	   $productMonth[$month1][$product1] = array('ProductUnit'=>$product1unit1,'ProductValue'=>$productPrices[$product1]*$product1unit1);
	   $productMonth[$month2][$product1] = array('ProductUnit'=>$product1unit2,'ProductValue'=>$productPrices[$product1]*$product1unit2);
	   $productMonth[$month3][$product1] = array('ProductUnit'=>$product1unit3,'ProductValue'=>$productPrices[$product1]*$product1unit3);
	   $productMonth[$month4][$product1] = array('ProductUnit'=>$product1unit4,'ProductValue'=>$productPrices[$product1]*$product1unit4);
	   $productMonth[$month5][$product1] = array('ProductUnit'=>$product1unit5,'ProductValue'=>$productPrices[$product1]*$product1unit5);
	   $productMonth[$month6][$product1] = array('ProductUnit'=>$product1unit6,'ProductValue'=>$productPrices[$product1]*$product1unit6);
	   $productMonth[$month7][$product1] = array('ProductUnit'=>$product1unit7,'ProductValue'=>$productPrices[$product1]*$product1unit7);
	   $productMonth[$month8][$product1] = array('ProductUnit'=>$product1unit8,'ProductValue'=>$productPrices[$product1]*$product1unit8);
	   $productMonth[$month9][$product1] = array('ProductUnit'=>$product1unit9,'ProductValue'=>$productPrices[$product1]*$product1unit9);
	 }
	  
	 //
	 $product2unit1 = isset($request['product2unit1'])?$request['product2unit1']:'0';
	 $product2unit2 = isset($request['product2unit2'])?$request['product2unit2']:'0';
	 $product2unit3 = isset($request['product2unit3'])?$request['product2unit3']:'0';
	 $product2unit4 = isset($request['product2unit4'])?$request['product2unit4']:'0';
	 $product2unit5 = isset($request['product2unit5'])?$request['product2unit5']:'0';
	 $product2unit6 = isset($request['product2unit6'])?$request['product2unit6']:'0';
	 $product2unit7 = isset($request['product2unit7'])?$request['product2unit7']:'0';
	 $product2unit8 = isset($request['product2unit8'])?$request['product2unit8']:'0';
	 $product2unit9 = isset($request['product2unit9'])?$request['product2unit9']:'0';
	 if($product2>0){
	   $productMonth[$month1][$product2] = array('ProductUnit'=>$product2unit1,'ProductValue'=>$productPrices[$product2]*$product2unit1);
	   $productMonth[$month2][$product2] = array('ProductUnit'=>$product2unit2,'ProductValue'=>$productPrices[$product2]*$product2unit2);
	   $productMonth[$month3][$product2] = array('ProductUnit'=>$product2unit3,'ProductValue'=>$productPrices[$product2]*$product2unit3);
	   $productMonth[$month4][$product2] = array('ProductUnit'=>$product2unit4,'ProductValue'=>$productPrices[$product2]*$product2unit4);
	   $productMonth[$month5][$product2] = array('ProductUnit'=>$product2unit5,'ProductValue'=>$productPrices[$product2]*$product2unit5);
	   $productMonth[$month6][$product2] = array('ProductUnit'=>$product2unit6,'ProductValue'=>$productPrices[$product2]*$product2unit6);
	   $productMonth[$month7][$product2] = array('ProductUnit'=>$product2unit7,'ProductValue'=>$productPrices[$product2]*$product2unit7);
	   $productMonth[$month8][$product2] = array('ProductUnit'=>$product2unit8,'ProductValue'=>$productPrices[$product2]*$product2unit8);
	   $productMonth[$month9][$product2] = array('ProductUnit'=>$product2unit9,'ProductValue'=>$productPrices[$product2]*$product2unit9);
	 }
	 //
	 $product3unit1 = isset($request['product3unit1'])?$request['product3unit1']:'0';
	 $product3unit2 = isset($request['product3unit2'])?$request['product3unit2']:'0';
	 $product3unit3 = isset($request['product3unit3'])?$request['product3unit3']:'0';
	 $product3unit4 = isset($request['product3unit4'])?$request['product3unit4']:'0';
	 $product3unit5 = isset($request['product3unit5'])?$request['product3unit5']:'0';
	 $product3unit6 = isset($request['product3unit6'])?$request['product3unit6']:'0';
	 $product3unit7 = isset($request['product3unit7'])?$request['product3unit7']:'0';
	 $product3unit8 = isset($request['product3unit8'])?$request['product3unit8']:'0';
	 $product3unit9 = isset($request['product3unit9'])?$request['product3unit9']:'0';
	 
	 if($product3>0){
	   $productMonth[$month1][$product3] = array('ProductUnit'=>$product3unit1,'ProductValue'=>$productPrices[$product3]*$product3unit1);
	   $productMonth[$month2][$product3] = array('ProductUnit'=>$product3unit2,'ProductValue'=>$productPrices[$product3]*$product3unit2);
	   $productMonth[$month3][$product3] = array('ProductUnit'=>$product3unit3,'ProductValue'=>$productPrices[$product3]*$product3unit3);
	   $productMonth[$month4][$product3] = array('ProductUnit'=>$product3unit4,'ProductValue'=>$productPrices[$product3]*$product3unit4);
	   $productMonth[$month5][$product3] = array('ProductUnit'=>$product3unit5,'ProductValue'=>$productPrices[$product3]*$product3unit5);
	   $productMonth[$month6][$product3] = array('ProductUnit'=>$product3unit6,'ProductValue'=>$productPrices[$product3]*$product3unit6);
	   $productMonth[$month7][$product3] = array('ProductUnit'=>$product3unit7,'ProductValue'=>$productPrices[$product3]*$product3unit7);
	   $productMonth[$month8][$product3] = array('ProductUnit'=>$product3unit8,'ProductValue'=>$productPrices[$product3]*$product3unit8);
	   $productMonth[$month9][$product3] = array('ProductUnit'=>$product3unit9,'ProductValue'=>$productPrices[$product3]*$product3unit9);
	 }
	 //
	 $product4unit1 = isset($request['product4unit1'])?$request['product4unit1']:'0';
	 $product4unit2 = isset($request['product4unit2'])?$request['product4unit2']:'0';
	 $product4unit3 = isset($request['product4unit3'])?$request['product4unit3']:'0';
	 $product4unit4 = isset($request['product4unit4'])?$request['product4unit4']:'0';
	 $product4unit5 = isset($request['product4unit5'])?$request['product4unit5']:'0';
	 $product4unit6 = isset($request['product4unit6'])?$request['product4unit6']:'0';
	 $product4unit7 = isset($request['product4unit7'])?$request['product4unit7']:'0';
	 $product4unit8 = isset($request['product4unit8'])?$request['product4unit8']:'0';
	 $product4unit9 = isset($request['product4unit9'])?$request['product4unit9']:'0';
	 
	 if($product4>0){
	    $productMonth[$month1][$product4] = array('ProductUnit'=>$product4unit1,'ProductValue'=>$productPrices[$product4]*$product4unit1);
		$productMonth[$month2][$product4] = array('ProductUnit'=>$product4unit2,'ProductValue'=>$productPrices[$product4]*$product4unit2);
		$productMonth[$month3][$product4] = array('ProductUnit'=>$product4unit3,'ProductValue'=>$productPrices[$product4]*$product4unit3);
		$productMonth[$month4][$product4] = array('ProductUnit'=>$product4unit4,'ProductValue'=>$productPrices[$product4]*$product4unit4);
		$productMonth[$month5][$product4] = array('ProductUnit'=>$product4unit5,'ProductValue'=>$productPrices[$product4]*$product4unit5);
		$productMonth[$month6][$product4] = array('ProductUnit'=>$product4unit6,'ProductValue'=>$productPrices[$product4]*$product4unit6);
		$productMonth[$month7][$product4] = array('ProductUnit'=>$product4unit7,'ProductValue'=>$productPrices[$product4]*$product4unit7);
		$productMonth[$month8][$product4] = array('ProductUnit'=>$product4unit8,'ProductValue'=>$productPrices[$product4]*$product4unit8);
		$productMonth[$month9][$product4] = array('ProductUnit'=>$product4unit9,'ProductValue'=>$productPrices[$product4]*$product4unit9);
	 }
	 //
	 $product5unit1 = isset($request['product5unit1'])?$request['product5unit1']:'0';
	 $product5unit2 = isset($request['product5unit2'])?$request['product5unit2']:'0';
	 $product5unit3 = isset($request['product5unit3'])?$request['product5unit3']:'0';
	 $product5unit4 = isset($request['product5unit4'])?$request['product5unit4']:'0';
	 $product5unit5 = isset($request['product5unit5'])?$request['product5unit5']:'0';
	 $product5unit6 = isset($request['product5unit6'])?$request['product5unit6']:'0';
	 $product5unit7 = isset($request['product5unit7'])?$request['product5unit7']:'0';
	 $product5unit8 = isset($request['product5unit8'])?$request['product5unit8']:'0';
	 $product5unit9 = isset($request['product5unit9'])?$request['product5unit9']:'0';
	 
	 if($product5>0){
	   $productMonth[$month1][$product5] = array('ProductUnit'=>$product5unit1,'ProductValue'=>$productPrices[$product5]*$product5unit1);
	   $productMonth[$month2][$product5] = array('ProductUnit'=>$product5unit2,'ProductValue'=>$productPrices[$product5]*$product5unit2);
	   $productMonth[$month3][$product5] = array('ProductUnit'=>$product5unit3,'ProductValue'=>$productPrices[$product5]*$product5unit3);
	   $productMonth[$month4][$product5] = array('ProductUnit'=>$product5unit4,'ProductValue'=>$productPrices[$product5]*$product5unit4);
	   $productMonth[$month5][$product5] = array('ProductUnit'=>$product5unit5,'ProductValue'=>$productPrices[$product5]*$product5unit5);
	   $productMonth[$month6][$product5] = array('ProductUnit'=>$product5unit6,'ProductValue'=>$productPrices[$product5]*$product5unit6);
	   $productMonth[$month7][$product5] = array('ProductUnit'=>$product5unit7,'ProductValue'=>$productPrices[$product5]*$product5unit7);
	   $productMonth[$month8][$product5] = array('ProductUnit'=>$product5unit8,'ProductValue'=>$productPrices[$product5]*$product5unit8);
	   $productMonth[$month9][$product5] = array('ProductUnit'=>$product5unit9,'ProductValue'=>$productPrices[$product5]*$product5unit9);
	 }
	 //
	 $product6unit1 = isset($request['product6unit1'])?$request['product6unit1']:'0';
	 $product6unit2 = isset($request['product6unit2'])?$request['product6unit2']:'0';
	 $product6unit3 = isset($request['product6unit3'])?$request['product6unit3']:'0';
	 $product6unit4 = isset($request['product6unit4'])?$request['product6unit4']:'0';
	 $product6unit5 = isset($request['product6unit5'])?$request['product6unit5']:'0';
	 $product6unit6 = isset($request['product6unit6'])?$request['product6unit6']:'0';
	 $product6unit7 = isset($request['product6unit7'])?$request['product6unit7']:'0';
	 $product6unit8 = isset($request['product6unit8'])?$request['product6unit8']:'0';
	 $product6unit9 = isset($request['product6unit9'])?$request['product6unit9']:'0';
	 
	 if($product6>0){
	    $productMonth[$month1][$product6] = array('ProductUnit'=>$product6unit1,'ProductValue'=>$productPrices[$product6]*$product6unit1);
		$productMonth[$month2][$product6] = array('ProductUnit'=>$product6unit2,'ProductValue'=>$productPrices[$product6]*$product6unit2);
		$productMonth[$month3][$product6] = array('ProductUnit'=>$product6unit3,'ProductValue'=>$productPrices[$product6]*$product6unit3);
		$productMonth[$month4][$product6] = array('ProductUnit'=>$product6unit4,'ProductValue'=>$productPrices[$product6]*$product6unit4);
		$productMonth[$month5][$product6] = array('ProductUnit'=>$product6unit5,'ProductValue'=>$productPrices[$product6]*$product6unit5);
		$productMonth[$month6][$product6] = array('ProductUnit'=>$product6unit6,'ProductValue'=>$productPrices[$product6]*$product6unit6);
		$productMonth[$month7][$product6] = array('ProductUnit'=>$product6unit7,'ProductValue'=>$productPrices[$product6]*$product6unit7);
		$productMonth[$month8][$product6] = array('ProductUnit'=>$product6unit8,'ProductValue'=>$productPrices[$product6]*$product6unit8);
		$productMonth[$month9][$product6] = array('ProductUnit'=>$product6unit9,'ProductValue'=>$productPrices[$product6]*$product6unit9);
	 }
	 //
	 $product7unit1 = isset($request['product7unit1'])?$request['product7unit1']:'0';
	 $product7unit2 = isset($request['product7unit2'])?$request['product7unit2']:'0';
	 $product7unit3 = isset($request['product7unit3'])?$request['product7unit3']:'0';
	 $product7unit4 = isset($request['product7unit4'])?$request['product7unit4']:'0';
	 $product7unit5 = isset($request['product7unit5'])?$request['product7unit5']:'0';
	 $product7unit6 = isset($request['product7unit6'])?$request['product7unit6']:'0';
	 $product7unit7 = isset($request['product7unit7'])?$request['product7unit7']:'0';
	 $product7unit8 = isset($request['product7unit8'])?$request['product7unit8']:'0';
	 $product7unit9 = isset($request['product7unit9'])?$request['product7unit9']:'0';
	 
	 if($product7>0){
	   $productMonth[$month1][$product7] = array('ProductUnit'=>$product7unit1,'ProductValue'=>$productPrices[$product7]*$product7unit1);
	   $productMonth[$month2][$product7] = array('ProductUnit'=>$product7unit2,'ProductValue'=>$productPrices[$product7]*$product7unit2);
	   $productMonth[$month3][$product7] = array('ProductUnit'=>$product7unit3,'ProductValue'=>$productPrices[$product7]*$product7unit3);
	   $productMonth[$month4][$product7] = array('ProductUnit'=>$product7unit4,'ProductValue'=>$productPrices[$product7]*$product7unit4);
	   $productMonth[$month5][$product7] = array('ProductUnit'=>$product7unit5,'ProductValue'=>$productPrices[$product7]*$product7unit5);
	   $productMonth[$month6][$product7] = array('ProductUnit'=>$product7unit6,'ProductValue'=>$productPrices[$product7]*$product7unit6);
	   $productMonth[$month7][$product7] = array('ProductUnit'=>$product7unit7,'ProductValue'=>$productPrices[$product7]*$product7unit7);
	   $productMonth[$month8][$product7] = array('ProductUnit'=>$product7unit8,'ProductValue'=>$productPrices[$product7]*$product7unit8);
	   $productMonth[$month9][$product7] = array('ProductUnit'=>$product7unit9,'ProductValue'=>$productPrices[$product7]*$product7unit9);
	 }
	// echo "<pre>";print_r($productMonth);die;
	 /*foreach($productMonth as $month=>$productmonth){
		 foreach($productmonth as $product_id=>$Unitvalues){
	 		if($Unitvalues['ProductUnit']<=0){
			echo json_encode(array('success'=>'NO','message'=>'Potential unit missing in month '.$productmonth));exit;
			}
	   }
	 }*/
	 
	 
	 $abm = $this->getParentLists($user_id,7);
	 $rbm = $this->getParentLists($abm,6);
	 $zbm = $this->getParentLists($zbm,5);
	 $apointmentcode = $this->getApoinmentCode();
	 $exceute = $this->query("INSERT INTO crm_appointments SET appointment_code='".$apointmentcode."',doctor_id='".$doctor_id."',expense_type='".$activitynature_id."',expense_cost='".$activity_cost."',expense_note='".$activity_detail."',be_id='".$user_id."',abm_id='".$abm."',rbm_id='".$rbm."',zbm_id='".$zbm."',total_value = '".$totalvalue."',chemist_1 ='".$chemistlist[0]['chemist_id']."',chemist_2='".$chemistlist[1]['chemist_id']."',favour='".$dd_chequedetail."',payble='".$paybleat."',created_by = '".$user_id."',created_date = NOW(),created_ip='".$_SERVER['REMOTE_ADDR']."',requested_by='".$user_id."'");
	 $apointment_id = mysql_insert_id();
	 $totalallvalue = 0;
	 foreach($productMonth as $month=>$productmonth){
	      $totalvalue = 0;
		  $this->query("INSERT INTO crm_appointment_potential_months SET appointment_id='".$apointment_id."',month='".$month."'");
			 $potential_month_id = mysql_insert_id();
		 foreach($productmonth as $product_id=>$Unitvalues){
		  if($Unitvalues['ProductUnit']>0){
			  $this->query("INSERT INTO crm_appointment_potential_month_products SET potential_month_id='".$potential_month_id."',product_id='".$product_id."',unit='".$Unitvalues['ProductUnit']."',value='".$Unitvalues['ProductValue']."'");
			 $totalvalue = $totalvalue + $Unitvalues['ProductValue']; 
			} 
		 }
		 $this->query("UPDATE crm_appointment_potential_months SET month_total_value = '".$totalvalue."' WHERE  potential_month_id='".$potential_month_id."'");		 
		 $totalallvalue = $totalallvalue + $totalvalue;
	 }
	 $this->query("UPDATE crm_appointments SET total_value = '".$totalallvalue."' WHERE  appointment_id='".$apointment_id."'");
	 
	 $exceute = $this->query("SELECT * FROM employee_personaldetail WHERE user_id='".$user_id."'");
   	 $emp_details = $this->fetchRow($exceute);
	 
	 $exceute = $this->query("SELECT doctor_name FROM crm_doctors WHERE doctor_id='".$doctor_id."'");
   	 $docor_detail = $this->fetchRow($exceute);
	 
	 $emailBody = 'A new CRM has been added by '.$emp_details['first_name'].' '.$emp_details['last_name'];
	 $emailBody .= '<br>Details is attached.PFA';
	 $maildata = array('SenderEmail'=>$emp_details['email'],'Sender'=>$emp_details['first_name'].' '.$emp_details['last_name'],'Body'=>$emailBody,'token'=>$apointment_id,'Doctor'=>$docor_detail['doctor_name']);
	 $this->sendMail($maildata);
	  echo json_encode(array('success'=>'YES','message'=>'CRM added successfully!'));exit;
	}
	
	public function getApoinmentCode(){
		 $exceute = $this->query("SELECT appointment_code FROM crm_appointments ORDER BY appointment_id DESC LIMIT 1");
   		$lastCode = $this->fetchRow($exceute);
		 return (!empty($lastCode)) ? 'APT'.(int) (substr($lastCode['appointment_code'],3)+1) : 'APT100001';
	} 
	
	public function getChemist($doctor_id){
	     $exceute = $this->query("SELECT * FROM crm_doctor_chemists WHERE doctor_id='".$doctor_id."'");
   		$data = $this->fetchAll($exceute);
		$chemits = array();
		foreach($data as $chemistids){
		  $chemits[] = $chemistids['chemist_id'];
		}
		return $chemits;
	}
	public function getParentLists($user_id,$designatin_id){
	  if($user_id>0){
	   $exceute = $this->query("SELECT EP.parent_id FROM employee_personaldetail EP WHERE EP.user_id='".$user_id."' AND EP.designation_id='".$designatin_id."'");
	   $parentdetail = $this->fetchRow($exceute);
	   return $parentdetail['parent_id'];	
	  }
	  return ''; 	 
	}
	
	public function sendMail($maildata=array()){
			require_once('class.phpmailer.php');
			$mail = new PHPMailer();
			date_default_timezone_set("Europe/Amsterdam");
			$mail->CharSet = 'utf-8';
			// START - Mail Function to Send From Server
			$mail->IsMail();
			$mail->IsHTML(true);
			
			//$data = file_get_contents('http://www.crm.jclifecare.com/Appointment/print/token/'.$maildata['token'].'/app/1');
			//file_put_contents('CRM.doc',$data);
			
			require_once("../library/Zend/MPDF/mpdf.php");
			$html = file_get_contents('http://www.crm.jclifecare.com/Appointment/print/token/'.$maildata['token'].'/app/1');
			$mpdf = new Zend_MPDF_mpdf('utf-8', 'A4');
			$mpdf->SetDisplayMode('fullpage');
			$mpdf->watermark_font = 'DejaVuSansCondensed';
			$mpdf->showWatermarkText = true;
			$mpdf->cacheTables 	   = true;
			$mpdf->simpleTables	   = true;
			$mpdf->packTableData	   = true;
			$mpdf->debug = true;

			$mpdf->WriteHTML($html);
			ob_end_clean();
			$content = $mpdf->Output('', 'S');
			file_put_contents('CRM.pdf',$content);

			$mail->MsgHTML($maildata['Body']);
			$mail->AddAttachment('CRM.pdf');
			// END - Mail Function to Send From Server
			$mail->Subject = 'Dr.:'.$maildata['Doctor'].'-CRM Request';
			$mail->AddAddress('tk.singh@jclifecare.com','Tarun Kumar Singh');
			//$mail->AddAddress('asanjeevsoftdot@gmail.com','Sanjeev');
			$mail->AddCC('subir.sarkar@jclifecare.com', 'Subir Sarkar');
			$mail->AddCC('kapildev.dang@jclifecare.com', ' Kapildev dang');
			$mail->AddCC('vajid.khan@jclifecare.com', 'Vajid khan');
			$sender = ($maildata['SenderEmail']!='')?$maildata['SenderEmail']:'info@jclifecare.com';
			$mail->SetFrom($sender, $maildata['Sender']);
			$mail->Send();

  }
}
$obj = new CRMRequest;
$obj->AddCRM($_REQUEST);
?>