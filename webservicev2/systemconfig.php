<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class systemConfig  extends commonclass{
   public function getSystemConfig($request){
   $exceute = $this->query("SELECT AU.*,EP.designation_id,EP.contact_number 
   							FROM app_userdetails AU 
							INNER JOIN employee_personaldetail EP ON EP.user_id=AU.user_id 
							WHERE AU.user_id='".$request['user_id']."'");
   $data = $this->fetchRow($exceute);
   $dataArr = array();
   $dataArr['reporting_lock'] = $data['reporing_lock'];
   $dataArr['designation'] = $data['designation_id'];
   $dataArr['auto_sync'] = $data['auto_sync'];
   
   $date1 = $data['last_reported'];
   $diff = floor((strtotime(date('Y-m-d'))- strtotime($date1))/(60*60*24));
   $lock_days_limit = ($data['lock_limit']>0)?$data['lock_limit']:4;
   if($diff>$lock_days_limit && $data['lock_action']=='1' && $data['last_reported']!='0000-00-00'){
      $sql = "UPDATE app_userdetails SET reporing_lock='1',lock_date = NOW() WHERE user_id='".$request['user_id']."'";
	  $this->query($sql);
      $sql="INSERT INTO app_log(user_id,on_reporting_lock_date,lock_via) values('".$request['user_id']."','".date('Y-m-d H:i:s',time())."','1')";
      $this->query($sql);
	  $dataArr['reporting_lock'] = 1;
   }
   echo json_encode(array('success'=>'YES')+$dataArr); exit;
   }		
}
$obj = new systemConfig;
$obj->getSystemConfig($_REQUEST);   
?>