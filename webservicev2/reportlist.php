<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class reportlist  extends commonclass{
     public function getResponse($request){
	    $this->query("INSERT INTO app_test SET request_date='".json_encode($request)."'");
	     switch($request['Mode']){
		     case 'DoctorVisit':
			    $reponse = $this->doctorVisitReport($request);
			 break;
			 case 'ChemistVisit':
			 	$reponse = $this->chemistVisitReport($request);
			 break;
			 case 'StockistVisit':
			 	$reponse = $this->stackistVisitReport($request);
			 break;
			 case 'Meeting':
			 	$reponse = $this->meetingReport($request);
			 break;
			 case 'OtherReport':
			 	$reponse = $this->otherReport($request);
			 break;
			 case 'TourPlan':
			 	$reponse = $this->TourPlanReport($request);
			 break;
			 case 'Unpublished':
			 	$reponse = $this->Unpublished($request);
			 break;
			 default:
			 	$reponse = array('success'=>'NO','Invalid Action!');
		 } 
		 echo json_encode($reponse);die; 
	 }
	 
	 public function doctorVisitReport($request){
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
			if($request['patchid']!='' && $request['patchid']!='null' && $request['patchid']>0){
				$where .= " AND PT.patch_id='".$request['patchid']."'"; 
			}
			if($request['doctorid']!='' && $request['doctorid']!='null' && $request['doctorid']>0){
				$where .= " AND CD.doctor_id='".$request['doctorid']."'";
			}
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND DATE(ADV.call_date) BETWEEN '".date('Y-m-d',strtotime($request['start_date']))."' AND '".date('Y-m-d',strtotime($request['end_date']))."'"; 
		}else{
			$where .= " AND MONTH(ADV.call_date)='".date('m')."'";
		}
		$exceute = $this->query("SELECT CD.doctor_name,PT.patch_name,ADV.date_added,EP.first_name,EP.last_name,CT.city_name,HQ.headquater_name,call_date 
	        FROM app_doctor_visit ADV 
			INNER JOIN crm_doctors CD ON CD.doctor_id=ADV.doctor_id 
			INNER JOIN patchcodes AS PT ON PT.patch_id=CD.patch_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=ADV.user_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id 
			LEFT JOIN headquater HQ ON HQ.headquater_id=PT.headquater_id  
			WHERE ".$where." GROUP BY ADV.doctor_id,ADV.call_date ORDER BY ADV.call_date DESC");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
		  $records = array();
		  $records['Doctor'] = $data['doctor_name'];
		  $records['Patch'] =  $data['patch_name'];
		  $records['Emp'] =  $data['first_name'].' '.$data['last_name'];
		  $records['City'] =  $data['city_name'];
		  $records['Headquater'] =  $data['headquater_name'];
		  $records['Date'] = date('d-m-Y',strtotime($data['call_date']))." ".$data['call_time'];
		  $datecount[$data['call_date']] = 1;
		  $newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata,'monthly'=>number_format(count($newdata)/count($datecount),1));
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
	
	 public function chemistVisitReport($request){
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
			if($request['patchid']!='' && $request['patchid']!='null' && $request['patchid']>0){
				$where .= " AND PT.patch_id='".$request['patchid']."'"; 
			}
			if($request['doctorid']!='' && $request['doctorid']!='null' && $request['doctorid']>0){
				$where .= " AND CC.chemist_id='".$request['doctorid']."'";
			}
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND DATE(ACV.call_date)>='".$request['start_date']."' AND DATE(ACV.call_date)<='".$request['end_date']."'"; 
		}else{
			$where .= " AND MONTH(ACV.call_date)='".date('m')."'";
		}
		$exceute = $this->query("SELECT CC.chemist_name,PT.patch_name,ACV.date_added,EP.first_name,EP.last_name,CT.city_name,HQ.headquater_name ,ACV.call_date
	        FROM app_chemist_visit ACV 
			INNER JOIN crm_chemists CC ON CC.chemist_id=ACV.chemist_id
			INNER JOIN patchcodes AS PT ON PT.patch_id=CC.patch_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=ACV.user_id
			INNER JOIN city CT ON CT.city_id=PT.city_id
			INNER JOIN headquater HQ ON HQ.headquater_id=PT.headquater_id  WHERE ".$where."");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
		  	$records = array();
			$records['Doctor'] = $data['chemist_name'];
			$records['Patch'] =  $data['patch_name'];
			$records['Emp'] =  $data['first_name'].' '.$data['last_name'];
			$records['City'] =  $data['city_name'];
			$records['Headquater'] =  $data['headquater_name'];
			$records['Date'] =  date('d-m-Y',strtotime($data['call_date']));
			$datecount[date('d-m-Y',strtotime($data['call_date']))] = 1;
			$newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata,'monthly'=>number_format(count($newdata)/count($datecount),1));
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
	
	 public function stackistVisitReport($request){
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
			if($request['patchid']!='' && $request['patchid']!='null' && $request['patchid']>0){
				$where .= " AND PT.patch_id='".$request['patchid']."'"; 
			}
			
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND DATE(ASV.call_date)>='".$request['start_date']."' AND DATE(ASV.call_date)<='".$request['end_date']."'"; 
		}else{
			$where .= " AND MONTH(ASV.call_date)='".date('m')."'";
		}
		$exceute = $this->query("SELECT DISTINCT PT.stockist_name,ASV.date_added,EP.first_name,EP.last_name,(SELECT headquater_name FROM headquater HQ WHERE HQ.headquater_id=SH.headquater_id GROUP BY HQ.headquater_id) AS  headquater_name
	        FROM app_stockist_visit ASV 
			INNER JOIN crm_stockists AS PT ON PT.stockist_id=ASV.stockist_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=ASV.user_id
			INNER JOIN crm_stockists_hq SH ON SH.stockist_id=PT.stockist_id
		    WHERE ".$where." GROUP BY ASV.call_date");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
		  	$records = array();
			$records['Doctor'] = $data['stockist_name'];
			$records['Patch'] =  $data['headquater_name'];
			$records['Emp'] =  $data['first_name'].' '.$data['last_name'];
			$records['City'] =  $data['headquater_name'];
			$records['Headquater'] =  $data['headquater_name'];
			$records['Date'] =  date('d-m-Y',strtotime($data['date_added']));
			$datecount[date('d-m-Y',strtotime($data['date_added']))] = 1;
			$newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata,'monthly'=>number_format(count($newdata)/count($datecount),1));
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
	 
	 public function meetingReport($request){
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND AM.meeting_date>='".$request['start_date']."' AND AM.meeting_date<='".$request['end_date']."'"; 
		}else{
			$where .= " AND MONTH(AM.meeting_date)='".date('m')."'";
		}
		$exceute = $this->query("SELECT HQ.headquater_name,AM.meeting_date,EP.first_name,EP.last_name,MT.type_name ,AM.meetingtime_start,AM.meetingtime_end,AM.headquater_id,AM.other_city
	        FROM app_meeting AM 
			INNER JOIN app_meetingtype MT ON MT.type_id=AM.meetingtype_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=AM.user_id
			LEFT JOIN headquater HQ ON HQ.headquater_id=AM.headquater_id  WHERE ".$where.$filter." ORDER BY meeting_date DESC");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
		  	$records = array();
			$records['Patch'] = ($data['headquater_id']=="500")?$data['other_city']:$data['headquater_name'];
			$records['Emp'] =  $data['first_name'].' '.$data['last_name'];
			$records['Doctor'] =  $data['type_name'];
			$records['Start'] =  substr($data['meetingtime_start'],0,5);
			$records['End'] =  substr($data['meetingtime_end'],0,5);
			$records['Date'] =  date('d-m-Y',strtotime($data['meeting_date']));
			$records['City'] =  'Time:'.substr($data['meetingtime_start'],0,5).' To '.substr($data['meetingtime_end'],0,5);
			$datecount[date('d-m-Y',strtotime($data['meeting_date']))] = 1;
			$newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata,'monthly'=>number_format(count($newdata)/count($datecount),1));
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
	
	public function OtherReport($request){
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND AM.activity_date>='".$request['start_date']."' AND AM.activity_date<='".$request['end_date']."'"; 
		}else{
			$where .= " AND MONTH(AM.activity_date)='".date('m')."'";
		}
		$exceute = $this->query("SELECT AM.activity_date,EP.first_name,EP.last_name,MT.type_name ,AM.start_time,AM.end_time
	        FROM app_noncallreport AM 
			INNER JOIN app_meetingtype MT ON MT.type_id=AM.meetingtype_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=AM.user_id WHERE ".$where."");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
			$records = array();
			$records['Emp'] =  $data['first_name'].' '.$data['last_name'];
			$records['Doctor'] =  $data['type_name'];
			$records['Patch'] =  substr($data['start_time'],0,5).' To '.substr($data['end_time'],0,5);
			$records['City'] =  $data['activity_detail'];
			$records['Date'] =  date('d-m-Y',strtotime($data['activity_date']));
			$datecount[date('d-m-Y',strtotime($data['activity_date']))] = 1;
			$newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata);
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
	
	public function TourPlanReport($request){
		$request['user_id'] = ($request['user_id']>0)?$request['user_id']:$request['user'];
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND TP.tour_date>='".$request['start_date']."' AND TP.tour_date<='".$request['end_date']."'"; 
		}else{
			$where .= " AND MONTH(TP.tour_date)='".date('m')."'";
		}
		$exceute = $this->query("SELECT TP.tour_date,PT.patch_name,CT.city_name,TP.*,CONCAT( EP1.first_name,' ',EP1.last_name) AS bevisit,CONCAT( EP2.first_name,' ',EP2.last_name) AS abmvisit,(SELECT CONCAT( EP3.first_name,' ',EP3.last_name) FROM employee_personaldetail EP3 WHERE EP3.user_id=TP.zbm_visit) AS rbmvisit,(SELECT CONCAT( EP4.first_name,' ',EP4.last_name) FROM employee_personaldetail EP4 WHERE EP4.user_id=TP.zbm_visit) AS zbmvisit
	        FROM app_tourplan TP 
			INNER JOIN patchcodes AS PT ON PT.patch_id=TP.location_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=TP.user_id
			LEFT JOIN employee_personaldetail EP1 ON EP1.user_id=TP.be_visit
			LEFT JOIN employee_personaldetail EP2 ON EP2.user_id=TP.abm_visit
			INNER JOIN city CT ON CT.city_id=PT.city_id WHERE DATE_FORMAT( tour_date,  '%Y-%m' ) = DATE_FORMAT( CURDATE( ), '%Y-%m' ) AND ".$where." ORDER BY TP.tour_date DESC");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
			$records = array();
			$records['Date'] =  date('d-m-Y',strtotime($data['tour_date']));
				$records['Patch'] =  $data['patch_name'];
				$string = '';
			if($data['be_visit']>0){
				$string .= 'BE,';
			}
			if($data['abm_visit']>0){
				$string .= 'ABM,';
			}
			if($data['rbm_visit']>0){
				$string .= 'RBM,';
			}
			if($data['zbm_visit']>0){
				$string .= 'ZBM,';
			}
			$records['Work'] =  ($string!='')?substr($string,0,-1):'Self';
			$records['City'] =  $data['city_name'];
			$newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata);
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
	
	public function Unpublished($request){
		if($request['empid']!='' && $request['empid']!='null' && $request['empid']>0){
			$where = "EP.user_id='".$request['empid']."'"; $reportof = true;
		}else{
			$where = "EP.user_id='".$request['user_id']."'";
		}
		if($request['start_date']!='' && $request['end_date']!='' && strlen($request['end_date'])>7 && strlen($request['start_date'])>7){
			$where .= " AND TP.tour_date>='".$request['start_date']."' AND TP.tour_date<='".$request['end_date']."'"; 
		}
		$exceute = $this->query("SELECT TP.tour_date,PT.patch_name,CT.city_name,TP.*,
	        CONCAT(TP.user_id,',',be_visit,',',abm_visit,',',rbm_visit,',',zbm_visit,',',location_id,',',tour_date,',',EP.designation_id,',',TP.call_type,',',TP.other_city,',',TP.activity_id,',',TP.loc_type,',',TP.headquater_id) AS allinone,
			(SELECT CONCAT( EP1.first_name,' ',EP1.last_name) FROM employee_personaldetail EP1 WHERE EP1.user_id=TP.be_visit) AS bevisit,
			 (SELECT CONCAT( EP2.first_name,' ',EP2.last_name) FROM employee_personaldetail EP2 WHERE EP2.user_id=TP.abm_visit) AS abmvisit,
			(SELECT CONCAT( EP3.first_name,' ',EP3.last_name) FROM employee_personaldetail EP3 WHERE EP3.user_id=TP.zbm_visit) AS rbmvisit,
			(SELECT CONCAT( EP4.first_name,' ',EP4.last_name) FROM employee_personaldetail EP4 WHERE EP4.user_id=TP.zbm_visit) AS zbmvisit,AT.type_name
	        FROM app_tourplan TP 
			LEFT JOIN patchcodes AS PT ON PT.patch_id=TP.location_id
			INNER JOIN employee_personaldetail EP ON EP.user_id=TP.user_id
			LEFT JOIN city CT ON CT.city_id=PT.city_id
			LEFT JOIN app_meetingtype AT ON AT.type_id=TP.activity_id 
			WHERE TP.status='0' AND (DATE_FORMAT(tour_date,'%Y-%m' ) = DATE_FORMAT(DATE_ADD(CURDATE( ),INTERVAL 1 MONTH ) ,'%Y-%m' ) OR DATE_FORMAT(tour_date,'%Y-%m' ) = DATE_FORMAT(CURDATE( ) ,'%Y-%m' )) AND ".$where." AND accepted!=1 ORDER BY TP.tour_date ASC");
	    $doccisit = $this->fetchAll($exceute);
		$newdata = array();
		foreach($doccisit as $data){
			$records = array();
			$records['Date'] =  date('d-m-Y',strtotime($data['tour_date'])); 
			$records['Patch'] =  ($data['patch_name']!='')?$data['patch_name']:$data['type_name'];
			$string = array();
			if($data['be_visit']>0 && !empty($data['bevisit'])){
				$string[] = $data['bevisit'];
			}
			if($data['abm_visit']>0 && !empty($data['abmvisit'])){
				$string[] = $data['abmvisit'];
			}
			if($data['rbm_visit']>0 && !empty($data['rbmvisit'])){
				$string[] = $data['rbmvisit'];
			}
			if($data['zbm_visit']>0 && !empty($data['zbmvisit'])){
				$string[] = $data['zbmvisit'];
			}
			$records['Work'] =  (!empty($string))?implode(', ',$string):'Self';
			$records['City'] =  ($data['city_name']!='')?$data['city_name']:$data['other_city'];
			$records['All'] =  $data['allinone'];
			$newdata[] = $records;
		}
		if(!empty($newdata)){
		  return array('success'=>'YES','message'=>$newdata);
		}else{
		  return array('success'=>'NO','message'=>'No record found');
		}
	}
}
$obj = new reportlist;
$obj->getResponse($_REQUEST); 

?>