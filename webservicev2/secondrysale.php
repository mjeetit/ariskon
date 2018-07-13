<?php
header('Content-type: application/json');
include 'config.php';
include 'common_class.php';
class SecondrySale extends commonclass{
    public function addsale($postdata){
		$user_id = isset($postdata['user_id'])?$postdata['user_id']:0;
		$headquater_id = isset($postdata['headquater_id'])?$postdata['headquater_id']:0;
		$stockist_id = isset($postdata['stockist_id'])?$postdata['stockist_id']:0;
		//$from_date = isset($postdata['from'])?date('Y-m-d',strtotime($postdata['from'])):'';
		//$to_date = isset($postdata['to'])?date('Y-m-d',strtotime($postdata['to'])):'';
		
	    $amount = isset($postdata['amount'])?$postdata['amount']:0;
		
		//-------Newly inserted Code-----//
		if(isset($postdata['from'])){
			$from_date=date('Y-m-d',strtotime($postdata['from']));
			if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$from_date, $matches)){
				if(!checkdate($matches[2], $matches[3], $matches[1]))
				{ 
					return array('success'=>'NO','message'=>'Please Enter valid starting date!');
				}
			}	
		}else{
			return array('success'=>'NO','message'=>'Please Select some starting date!');
		}
		
		if(isset($postdata['to'])){
			$to_date=date('Y-m-d',strtotime($postdata['to']));
			if(preg_match("/^(\d{4})-(\d{2})-(\d{2})$/",$to_date,$matches)){
				if(!checkdate($matches[2], $matches[3], $matches[1]))
				{ 
					return array('success'=>'NO','message'=>'Please Enter valid end date!');
				}
			}	
		}else{
			return array('success'=>'NO','message'=>'Please Select some end date!');
		}
		if($stockist_id<=0){
		  return array('success'=>'NO','message'=>'Please Select Stockist!');
		}
		//----------End of Newly inserted code------------//
		
		$sql = "INSERT INTO secondary_sale SET 
											 user_id='".$user_id."',
											 headquarter_id='".$headquater_id."',
											 date_from='".$from_date."',
											 date_to='".$to_date."',
											 amount='".$amount."',
											 stockist_id='".$stockist_id."',
											 gui='1',
											 insert_date=NOW()";
											 
		$execute = $this->query($sql);
		if($execute){	
		 return array('success'=>'YES','message'=>'Record has been Added successfully!');									 		}else{
		 return array('success'=>'NO','message'=>'There is some problem please try again!');
	  }
	}

}
$obj = new SecondrySale();
$response = $obj->addsale($_REQUEST);
echo json_encode($response);exit;

?>
