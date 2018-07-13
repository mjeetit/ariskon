<?php
header('Content-type: application/json');
$mode = isset($_REQUEST['Mode'])?$_REQUEST['Mode']:'';
$user_id = isset($_REQUEST['user_id'])?$_REQUEST['user_id']:0;
$designation = isset($_REQUEST['designation'])?$_REQUEST['designation']:0;
include 'config.php';
include 'common_class.php';
class Webservice1 extends commonclass{
    public function HolidayList($user_id){
	      $returndata = array();
		  $execute = $this->query("SELECT * FROM holidays");
	      $holidays = $this->fetchAll($execute);
		  $holidays[] = array('holiday_name'=>'Test Holiday','holiday_date'=>'2016-02-12');
		  foreach($holidays as $holiday){
				$data['Holidayname'] = $holiday['holiday_name'];
				$data['Holidaydate'] = $holiday['holiday_date'];
				$returndata[] = $data;
	  		}
	  return $returndata;
  }
}
$wObject = new Webservice1();
switch($mode){
     case 'Holiday':
		$newdata = $wObject->HolidayList($user_id);
	 break;	
}
if(!empty($newdata)){
  echo json_encode(array('success'=>'YES','message'=>$newdata)); exit;
}else{
  echo json_encode(array('success'=>'NO','message'=>'There are no record found')); exit;
}
?>