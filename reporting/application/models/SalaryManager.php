<?php

class SalaryManager extends Zend_Custom

{
  public $FromDate = NULL;
  public $_Month = NULL;
  public $_year = NULL;
  public $AFromDate = NULL;
  public $AToDate = NULL;
  public $_salaryData = array();
  public $_getData = array();
  public $_salaryDate = NULL;

  

  public function __construct(){
    parent::__construct();
	$Duretion = $this->getSalaryDuration();
	$from = $Duretion['from_date'];
	$to = $Duretion['to_date'];
    $currentDate = date('d');
	if(date('d')<=$from && date('d')<=$to){
	  $this->FromDate = date('Y-m-d',mktime(0, 0, 0, date("m")-1, $from,  date("Y")));
	  $this->ToDate = date('Y-m-d',mktime(0, 0, 0, date("m"), $to,  date("Y")));
	  $this->AFromDate = date('Y-m-d',mktime(0, 0, 0, date("m")-2, $from,  date("Y"))); 
	  $this->AToDate = date('Y-m-d',mktime(0, 0, 0, date("m")-1, $to,  date("Y")));
	  $this->_Month = date('m')-1;
	 
	  $this->_year = date('Y',mktime(0, 0, 0, date("m")-1, 1,  date("Y")));
      $this->_AMonth = date('m')-2;
	   if($this->_Month==0){
	     $this->_Month = 12;
	  }
	   if($this->_AMonth==0){
	     $this->_Month = 12;
	  }
	  $this->_Ayear = date('Y',mktime(0, 0, 0, date("m")-2, 1,  date("Y")));
	  
	}
	elseif(date('d')>=$from && date('d')>=$to){
	  $this->FromDate = date('Y-m-d',mktime(0, 0, 0, date("m"), $from,  date("Y")));
	  $this->ToDate = date('Y-m-d',mktime(0, 0, 0, date("m")+1, $to,  date("Y")));
	  $this->AFromDate = date('Y-m-d',mktime(0, 0, 0, date("m")-1, $from,  date("Y")));
	  $this->AToDate = date('Y-m-d',mktime(0, 0, 0, date("m"), $to,  date("Y"))); 
	  $this->_Month = date('m'); 
	  $this->_year = date('Y',mktime(0, 0, 0, date("m"),1,  date("Y")));
          $this->_AMonth = date('m')-1;
	  $this->_Ayear = date('Y',mktime(0, 0, 0, date("m")-1, 1,  date("Y")));
	   if($this->_Month==0){
	     $this->_Month = 12;
	  }
	   if($this->_AMonth==0){
	     $this->_Month = 12;
	  }
	}
	elseif(date('d')>=$from && date('d')<=$to){
	  $this->FromDate = date('Y-m-d',mktime(0, 0, 0, date("m")-1, $from,  date("Y")));
	  $this->ToDate = date('Y-m-d',mktime(0, 0, 0, date("m"), $to,  date("Y")));
	  $this->AFromDate = date('Y-m-d',mktime(0, 0, 0, date("m")-2, $from,  date("Y"))); 
	  $this->AToDate = date('Y-m-d',mktime(0, 0, 0, date("m")-1, $to,  date("Y")));
	  $this->_Month = date('m')-1;
	  $this->_year = date('Y',mktime(0, 0, 0, date("m")-1, 1,  date("Y")));
      $this->_AMonth = date('m')-2;
	  $this->_Ayear = date('Y',mktime(0, 0, 0, date("m")-2, 1,  date("Y")));
	   if($this->_Month==0){
	     $this->_Month = 12;
	  }
	   if($this->_AMonth==0){
	     $this->_Month = 12;
	  }
	}
  }

  public function ReCalculationSalary(){
      $select = $this->_db->select()
                                ->from(array('SL'=>'salary_list'),array('user_id'))
                                ->where("date='".$this->FromDate."' AND salary_processed='0'")
								->group("user_id");
						//echo $select->__toString();die; 
	 $userslist = $this->getAdapter()->fetchAll($select);
	 foreach($userslist as $user){
      $select = $this->_db->select()
                                ->from(array('SL'=>'salary_list'),array('*'))
                                ->where("date='".$this->FromDate."' AND user_id='".$user['user_id']."' AND salary_processed='0' AND salaryhead_id IN(3)")
								->order("salaryhead_id");
						//echo $select->__toString();die; 
	 $salarylists = $this->getAdapter()->fetchAll($select);
         $basic = 0;
	 foreach($salarylists as $salary){ 
           switch($salary['salaryhead_id']){
       case 3:
        $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('sum(approve_amount) as Approve','GROUP_CONCAT(expense_id) as expense_id'))
				->where("date_format(expense_date,'%Y-%m')< '".date('Y-m',strtotime($salary['date']))."' AND approve_status='1' AND salary_status='0' AND user_id='".$salary['user_id']."'")
				->group("EE.user_id");
				//echo $select->__toString();die;
	    $expense  =  $this->getAdapter()->fetchRow($select);//print_r($expense);die;
	if(!empty($expense)){
            $this->_db->update('salary_list',array('amount'=>$salary['amount']+$expense['Approve']),"user_id='".$salary['user_id']."' AND salaryhead_id=3 AND date='".$this->FromDate."'");
            $this->_db->update('emp_expenses',array('salary_status'=>'1','salary_date'=>$this->FromDate),"expense_id IN(".$expense['expense_id'].")");
         } 
       break;
      }
      
   } 
   }   
  
  }
  
  
  public function ReCalculationSalaryBack(){
      $select = $this->_db->select()
                                ->from(array('SL'=>'salary_list'),array('user_id','date'))
                                ->where("salary_processed='0'")
								->group("user_id")
								->group("date");
						//echo $select->__toString();die; 
	 $userslist = $this->getAdapter()->fetchAll($select);
	 foreach($userslist as $user){
      $select = $this->_db->select()
                                ->from(array('SL'=>'salary_list'),array('*'))
                                ->where("date='".$user['date']."' AND user_id='".$user['user_id']."' AND salary_processed='0' AND salaryhead_id IN(1,2,3,15)")
								->order("salaryhead_id");
						//echo $select->__toString();die; 
	 $salarylists = $this->getAdapter()->fetchAll($select);
         $basic = 0;
	 foreach($salarylists as $salary){ 
           switch($salary['salaryhead_id']){
            case 1:
               $basic = $salary['amount'];
                 // print_r($basic);
               break;
            case 2:
			   // if($salary['user_id']==44){  
				//   print_r($basic);die;
				//}
                 $userinfo = $this->UserInfo($salary['user_id']);
                 $masterprivident = $this->getMasterProvidentSetting();
                 $providentdeduct = 0;
                if($userinfo['provident']==1 && $userinfo['provident_pecentage']>0 && $masterprivident['provident_status']=='1'){
                            if($userinfo['provident_type']=='0'){
                                
                               $providentdeduct =  $basic*str_replace('%','',$userinfo['provident_pecentage'])/100;
                               //print_r($basic);print_r($userinfo['provident_pecentage']);print_r($providentdeduct);die;
                            }else{
                               $providentdeduct =  $userinfo['provident_pecentage'];
                            }
                     }else{
                        $providentdeduct = 0;
                     }
                
               $this->_db->update('salary_list',array('amount'=>$providentdeduct),"user_id='".$salary['user_id']."' AND salaryhead_id=2 AND date='".$user['date']."'");
          //print_r($providentdeduct);die;
		  break;
       case 3:
        $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('sum(approve_amount) as Approve','GROUP_CONCAT(expense_id) as expense_id'))
				->where("date_format(expense_date,'%Y-%m')< '".date('Y-m',strtotime($user['date']))."' AND approve_status='1' AND salary_status='0' AND user_id='".$salary['user_id']."'")
				->group("EE.user_id");
				//echo $select->__toString();die;
	    $expense  =  $this->getAdapter()->fetchRow($select);//print_r($expense);die;
	if(!empty($expense)){
            $this->_db->update('salary_list',array_filter(array('amount'=>$salary['amount']+$expense['Approve'])),"user_id='".$salary['user_id']."' AND salaryhead_id=3 AND date='".$user['date']."'");
            $this->_db->update('emp_expenses',array('salary_status'=>'1'),"expense_id IN(".$expense['expense_id'].")");
         } 
       break;
     case 15:
          $this->_salaryDate = $user['date'];
          $esideduct = $this->ESICalculation($salary['user_id']);
          $this->_db->update('salary_list',array('amount'=>$esideduct),"user_id='".$salary['user_id']."' AND salaryhead_id=15 AND date='".$user['date']."'");
         break;
      }
      
   } 
   }   
  
  }
  
 /*Salary calculate and display
 **Function : SalaryList()
 **Description : Function calculate the salary of current month and dispaly
 */
  public function SalaryList(){
     if(!empty($this->FromDate)){
	     $Allusers = $this->getAllUsersForSalary();
		  $select = $this->_db->select()
							->from(array('SL'=>'salary_list'),array('COUNT(1) AS CNT'))
							->where("date='".$this->FromDate."' AND (salary_processed='0' OR salary_processed='1')");
							//echo $select->__toString();die;
		  $salarycount = $this->getAdapter()->fetchRow($select);
	  if($salarycount['CNT']<=0){
		 foreach($Allusers as $users){
		      $this->_getData['user_id'] = $users['user_id'];
			  $amountdatas = $this->getUserSalaryHead();
			 foreach($amountdatas as $salaryamount){
			     $providentAmount[$salaryamount['salaryhead_id']] = $salaryamount['amount'];
				 switch($salaryamount['salaryhead_id']){
				    case 2:
					    $this->getProvidentfund($users['user_id'],$providentAmount);
					  break;
					case 3:
					    $this->getExpenseAmount($users['user_id']);
					  break;
					//case 15:
					    //$this->getEsiAmount($users['user_id'],$providentAmount);
					//  break;
					case 16:
					  break;    
					 default :
					   $this->_db->insert('salary_list',array_filter(array('user_id'=>$users['user_id'],
																		'salaryhead_id'=>$salaryamount['salaryhead_id'],
																		'amount'=>$salaryamount['amount'],
																		'salaryheade_type'=>$salaryamount['salaryheade_type'],
																		'date'=>$this->FromDate)));
					  break;   
				 }
			 }
		 }
		 return $this->SalaryList(); 
	   }
	   else{
		  foreach($Allusers as $result){
			  $recordData = array();
			  //$userDetail = $this->getAllUsersForSalary($result['user_id']);
			  $amountdetail = $this->SalaryAmount($result['user_id'],$this->FromDate,true);
			  $recordData['user_id'] 		=  $result['user_id'];
			  $recordData['name']	 		=  $result['name'];
			  $recordData['employee_code']  =  $result['employee_code'];
			  $recordData['designation'] 	=  $result['designation_name'];
			  $recordData['department'] 	=  $result['department_name'];
			  $recordData['earnings'] 		=  $amountdetail[0]['EDamount'];
			  $recordData['dedection'] 		=  $amountdetail[1]['EDamount'];
			  $recordData['date']	   		=  date('F Y',strtotime($amountdetail[0]['date']));
              $recordData['salary_date']	   	= $amountdetail[0]['date'];
		  	  $finalData[] = $recordData; 
	       } 
	  }
	  return $finalData;
	}
  }

  public function SalaryAmount($user_id,$date,$group=false){
     if($group){
	      $select = $this->_db->select()
		 				->from(array('ESA'=>'salary_list'),array('sum(amount) as EDamount','date'))
						->where("user_id='".$user_id."' AND salary_processed='0'")
						->where("date='".$date."'")
						->group("ESA.salaryheade_type")
						->group("ESA.user_id");
	 }else{
		$select = $this->_db->select()
		 				->from(array('ESA'=>'salary_list'),array('*'))
						->joininner(array('SH'=>'salary_head'),"SH.salaryhead_id=ESA.salaryhead_id",array('salary_title','prodata_status'))
						->where("user_id='".$user_id."' AND salary_processed='0'")
						//->where("user_id='".$user_id."' AND salary_processed='1'")
						->where("date='".$date."'")
						->order("SH.sequence ASC")
						->order("ESA.salaryheade_type ASC");
						
	  }					
	  //echo $select->__toString();die;
	  $results = $this->getAdapter()->fetchAll($select); 
	  return $results; 
  }
  
  public function getArrierSlalaryAmount($user_id,$slarydate,$flag=false){
       $date1   =   mktime(0, 0, 0, date("m", strtotime($slarydate))-1, date("d", strtotime($slarydate)), date("Y", strtotime($slarydate)));
       $arrier_date = date('Y-m', $date1);
	  // $arrier_date = date('Y-m-d', $date1);
      $select = $this->_db->select()
		 				->from(array('ESA'=>'salary_arear_list'),array('*','if(salary_processed,1,0) as satus'))
						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=ESA.salaryhead_id",array('salary_type','prodata_status','salary_title'))
						->where("user_id='".$user_id."' AND salary_processed='0'")
						//->where("date='".$arrier_date."' AND salary_processed='0'")
						->where("date_format(date,'%Y-%m')='".$arrier_date."' AND salary_processed='0'")
						->order("SH.sequence ASC")
						->order("ESA.salaryheade_type ASC");
						
	  $result = $this->getAdapter()->fetchAll($select);
	 if(empty($result) && !$flag){
		  $select = $this->_db->select()
							->from(array('ESA'=>'salary_list'),array('*'))
							->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=ESA.salaryhead_id",array('salary_type','prodata_status','salary_title'))
							->where("user_id='".$user_id."' AND salary_processed='0'")
							//->where("date='".$arrier_date."' AND salary_processed='0'")
							->where("date_format(date,'%Y-%m')='".$arrier_date."' AND salary_processed='0'")
							->order("SH.sequence ASC")
							->order("ESA.salaryheade_type ASC");
             //echo $select->__toString();die;
			$result = $this->getAdapter()->fetchAll($select);
	 }	
	  return $result;  
  }

  public function EditsalaryDetail(){
        $select = $this->_db->select()
		 				->from(array('SL'=>'salary_list'),array('*'))
						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=SL.salaryhead_id",array('salary_type','prodata_status','salary_title'))
						->where("date='".$this->_getData['salary_date']."' AND salary_processed='0' AND SL.user_id='".$this->_getData['user_id']."'")
						->order("SH.sequence ASC")
						->order("SL.salaryheade_type ASC");//echo $select->__toString();die;
	    $results = $this->getAdapter()->fetchAll($select);//print_r($results);die;
	    return $results;
  }

  public function UpdateSalaryDetail(){

     foreach($this->_getData['amount'] as $headid=>$amount){

	     $this->_db->update('salary_list',array('amount'=>$amount),"user_id='".$this->_getData['user_id']."' AND date='".$this->_getData['date']."' AND salaryhead_id='".$headid."'");

	 }

	 if(!empty($this->_getData['arear_status'])){ //print_r($this->_getData['arear_status']);die; 

	       $select = $this->_db->select()

		 				->from(array('ESA'=>'salary_arear_list'),array('*'))

						->where("user_id='".$this->_getData['user_id']."' AND date='".$this->_getData['arear_status']."' AND salary_processed='0'");

			 $results = $this->getAdapter()->fetchAll($select);

			// echo $select->__toString();die;
                        //print_r($results);die;
		if(!empty($results)){

		   foreach($this->_getData['arear_amount'] as $headid=>$amount){

	     $this->_db->update('salary_arear_list',array('amount'=>$amount),"user_id='".$this->_getData['user_id']."' AND date='".$this->_getData['arear_status']."' AND salaryhead_id='".$headid."'");

	 			}

		}else{

		  foreach($this->_getData['arear_amount'] as $headid=>$amount){
	     $this->_db->insert('salary_arear_list',array('amount'=>$amount,'user_id'=>$this->_getData['user_id'],'date'=>$this->_getData['arear_status'],'salaryhead_id'=>$headid,'salaryheade_type'=>$this->_getData['salaryhead_type'][$headid]));

			 }

		}	 

	 }else{  // print_r($this->_getData['arear_status']);die;
           $this->_db->delete('salary_arear_list',"user_id='".$this->_getData['user_id']."' AND salary_processed='0'");
         }
	 
	 if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	       $this->_db->insert('salary_list',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount,'date'=>$this->_getData['date'])));
		  
      if($this->_getData['edit_template']==1){//print_r($this->_getData);die;
	      $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));	  
		 }	
	    }				  
	  }

  }

  public function GenerateSalary(){
         $filename = 'SALARY_'.mktime().".pdf";
		if(count($this->_getData['user_id'])>1){  
         foreach($this->_getData['user_id'] as $user_id){
			$this->CalculateSalary($user_id,$filename);
			Bootstrap::$LabelObj->Output(Bootstrap::$root.'/public/salaryslip/'.$filename,'F');	 		   
		 }
		}else{
            $this->_salaryDate = $this->_getData['salary_date'];
		    $this->CalculateSalary($this->_getData['user_id'],$filename);
		    Bootstrap::$LabelObj->Output(Bootstrap::$root.'/public/salaryslip/'.$filename,'F');	 
		} 
	   ob_end_clean();
	   Bootstrap::$LabelObj->Output($filename,'D');
  }

  public function CalculateSalary($user_id,$filename){
       $empsalaries =  $this->SalaryAmount($user_id,$this->_salaryDate);
	   $userinfo 	= $this->getAllUsersForSalary($user_id);
	   $changectc = 0;
	   $changectc = $this->checkCTCChange($user_id,$this->_salaryDate);
	  
	   $attandance 	= $this->getFinalAttandance($userinfo['employee_code'],$this->_salaryDate,$changectc);
	   $this->_salaryData['EarningsTotal'] = 0;
	   $this->_salaryData['DeductionsTotal'] = 0;
	   $this->_salaryData['ArrEarningTotal'] = 0;
	   $this->_salaryData['ArrDeductionTotal'] = 0;//print_r($changectc);die;
	   $this->_salaryData['Deduction'] = array();
 // =======================Current Salary Calculation==============================	  
	   foreach($empsalaries as $salary){
	        $this->_salaryData['date'] = $salary['date'];
	     switch($salary['salaryheade_type']){
		     case 1:
			    if($salary['salaryhead_id']==1){
				  //$onedaypf = $salary['amount'] / $attandance['total_salary_days'] ;
				  $this->_salaryData['Basic'] = $salary['amount'];
				}
			    if($salary['salaryhead_id']==7){
				      $PFAmount = $this->PFCalculation($userinfo,$this->_salaryData['Basic'],$attandance);
					  $this->_salaryData['PFComp'] = $PFAmount['CompanyContPF'];
				      $EsiAmount = $this->ESICalculation($userinfo,$empsalaries,$attandance);
					  //$EsiAmount = $this->ESICalculationNEW($userinfo,$this->_salaryData['Basic'],$attandance);
					  $salary['amount'] = $salary['amount'] - (round($EsiAmount['CompanyContribution']) + round($PFAmount['CompanyContPF']));
					  $this->_salaryData['ESIComp'] = $EsiAmount['CompanyContribution'];
			     }
			    if(trim($salary['prodata_status'])=='1'){
			      $onedaysalay = $salary['amount'] / $attandance['total_salary_days'] ;
				  $earning[$salary['salaryhead_id']] = $onedaysalay * $attandance['total_present'];
				  $this->_salaryData['Earnings'] = $earning; 
				  $this->_salaryData['EarningsTotal'] = $this->_salaryData['EarningsTotal'] + $earning[$salary['salaryhead_id']];
				}else{
				  $earning[$salary['salaryhead_id']] = $salary['amount'];
				  $this->_salaryData['Earnings'] = $earning;
				  $this->_salaryData['EarningsTotal'] = $this->_salaryData['EarningsTotal'] + $earning[$salary['salaryhead_id']];
				}  
			    break;
			 case 2:
			    if($salary['salaryhead_id']==15){
				  $salary['amount'] = $EsiAmount['EmployeeContribution'];
				}
				if($salary['salaryhead_id']==2){
				      $salary['amount'] = $PFAmount['EmployeeContPF'];
					  $this->_salaryData['PFEmp'] = $PFAmount['EmployeeContPF'];
			     } 
				if(trim($salary['prodata_status'])=='1'){
					 $perday = $salary['amount'] / $attandance['total_salary_days'];
					 $deduct[$salary['salaryhead_id']] = $perday * $attandance['total_present'];
					 $this->_salaryData['Deduction'] = $deduct;
					 $this->_salaryData['DeductionsTotal'] = $this->_salaryData['DeductionsTotal'] + $deduct[$salary['salaryhead_id']];
				}else{
					 $deduct[$salary['salaryhead_id']] = $salary['amount'];
					 $this->_salaryData['Deduction'] = $deduct;
					 $this->_salaryData['DeductionsTotal'] = $this->_salaryData['DeductionsTotal'] + $deduct[$salary['salaryhead_id']]; 
					}
				 break; 	
		    }
	   }
	   
//=========================Change CTC Calculation================================
      if($changectc>0){
	     $this->ChangeCTCSalaryCalculation($user_id,$userinfo,$changectc,$this->_salaryDate);
	  }		   
	   
 //===================Arear salary Calculation================================   
	$arearsalaries =  $this->getArrierSlalaryAmount($user_id,$this->_salaryDate,true);
	 $mode = '';
	   if(!empty($arearsalaries)){
	         $getmanual 	= $this->getManaualArrier($user_id,$this->_salaryDate);
			 if(!empty($getmanual)){
			    $Aattandance 	=   $getmanual;//print_r($getmanual);die;
				$mode =  ($getmanual['arr_mode']=='1')?'-':'';
			 }else{ 
	   			$Aattandance 	= $this->getFinalArrearAttandance($userinfo['employee_code'],$this->_salaryDate);
                $totaldays = ($Aattandance['total_salary_days']>0)?$Aattandance['total_salary_days']:cal_days_in_month(CAL_GREGORIAN,$this->_AMonth,$this->_Ayear);
			 }	
		  foreach($arearsalaries as $arearsalary){
	     switch(trim($arearsalary['salaryheade_type'])){ 
		     case 1:
			     if($arearsalary['salaryhead_id']==1){
				   //$onedaypf = $arearsalary['amount'] / $Aattandance['total_salary_days'] ;
				   $this->_salaryData['Basic'] = $arearsalary['amount'];
				 }
			    if($arearsalary['salaryhead_id']==7){
				      $ArrPFAmount = $this->PFCalculation($userinfo,$this->_salaryData['Basic'],$Aattandance);
					  $this->_salaryData['ArrPFComp'] = $ArrPFAmount['CompanyContPF'];
				      $ArrEsiAmount = $this->ESICalculation($userinfo,$arearsalaries,$Aattandance);
					  //$ArrEsiAmount = $this->ESICalculationNEW($userinfo,$this->_salaryData['Basic'],$attandance);
					  $arearsalary['amount'] = $arearsalary['amount'] - (round($ArrEsiAmount['CompanyContribution']) + round($ArrPFAmount['CompanyContPF']));
					  $this->_salaryData['ArrESIComp'] = $ArrEsiAmount['CompanyContribution'];
			     }
			    if($arearsalary['prodata_status']=='1'){
			      $onedaysalay = $arearsalary['amount'] / $Aattandance['total_salary_days'] ;
				  $Arrearning[$arearsalary['salaryhead_id']] = $mode.($onedaysalay * $Aattandance['total_present']);
				  $this->_salaryData['ArrierEarnings'] =  $Arrearning;
				  $this->_salaryData['ArrEarningTotal'] = $this->_salaryData['ArrEarningTotal'] + $Arrearning[$arearsalary['salaryhead_id']];
				}else{
				  $Arrearning[$arearsalary['salaryhead_id']] = $mode.$arearsalary['amount'];
				  $this->_salaryData['ArrierEarnings'] = $Arrearning;
				  $this->_salaryData['ArrEarningTotal'] = $this->_salaryData['ArrEarningTotal'] + $Arrearning[$arearsalary['salaryhead_id']];
				}  
			    break;
			 case 2:
			    if($arearsalary['salaryhead_id']==15){
				  $arearsalary['amount'] = $ArrEsiAmount['EmployeeContribution'];
				} 
				if($arearsalary['salaryhead_id']==2){ 
				      $arearsalary['amount'] 	     = $ArrPFAmount['EmployeeContPF'];
					  //$this->_salaryData['ArrPFEmp'] = $ArrPFAmount['EmployeeContPF'];
			     } 
				if($arearsalary['prodata_status']=='1'){
					 $perday = $arearsalary['amount'] / $Aattandance['total_salary_days'];
					 $Arrdeduct[$arearsalary['salaryhead_id']] = $mode.($perday * $Aattandance['total_present']);
					 $this->_salaryData['ArrierDeduction'] = $Arrdeduct;
					 $this->_salaryData['ArrDeductionTotal'] = $this->_salaryData['ArrDeductionTotal'] + $Arrdeduct[$arearsalary['salaryhead_id']];
				}else{
					 $Arrdeduct[$salary['salaryhead_id']] = $mode.$arearsalary['amount'];
					 $this->_salaryData['ArrierDeduction'] = $Arrdeduct;
					 $this->_salaryData['ArrDeductionTotal'] = $this->_salaryData['ArrDeductionTotal'] + $Arrdeduct[$arearsalary['salaryhead_id']]; 
					}
				break; 	
		    }
	   }		
	 }
          
	   $grandtotal = (round($this->_salaryData['EarningsTotal']) + round($this->_salaryData['ArrEarningTotal']))-round($this->_salaryData['DeductionsTotal'] + $this->_salaryData['ArrDeductionTotal']);
       $this->_salaryData['Paid_days'] 	 = $attandance['total_present'] + $Aattandance['total_present'] + $changectc;
       $this->_salaryData['Total_leave'] = $attandance['total_leave'] + $Aattandance['total_leave']+$attandance['total_absent'] + $Aattandance['total_absent'];
	   $this->_salaryData['user_id']  	 = $user_id;  
	   $this->_salaryData['Leaves'] 	 = $this->LeaveDetails($user_id);
	   $this->_salaryData['Locations']   = $this->UserLocationDetails($user_id);
	   $this->_salaryData['Bank']   	= $this->getBankAccountDetail($user_id);
	   $this->_salaryData['Cheuqe']      = $this->getchequeNumber($user_id);
	   $this->_salaryData['EarningTotal'] = round($this->_salaryData['EarningsTotal']) + round($this->_salaryData['ArrEarningTotal']);
	   $this->_salaryData['DeductionTotal'] = round($this->_salaryData['DeductionsTotal'] + $this->_salaryData['ArrDeductionTotal']);
	   $this->_salaryData['GrandTotal']     = round($grandtotal);
	   $this->_salaryData['Filename'] = $filename; 
	   $this->_salaryData['LoanAmount'] = $loanamount;
	   $this->_salaryData['LoanActionType'] = $loancredebtype;
	   $this->_salaryData['ProvidentAmount'] = $this->_salaryData['Deduction'][2];
       $this->_salaryData['EsiAmount'] = $this->_salaryData['Deduction'][15];
	   if($grandtotal>0){
		$this->_salaryData['TotalText'] = $this->ConvertToWords($grandtotal);
	   }
	   $this->_salaryData['UserInfo'] = $userinfo; 
	    if($user_id==44){
	       $this->_salaryData['ExpMonth']   = date('M Y',strtotime($this->_salaryDate));
		}else{
	       $this->_salaryData['ExpMonth']   = $this->getExpenseMonth($user_id);
		}
	   //$this->_salaryData['ExpMonth']   =   date("M Y",mktime(0, 0, 0, date("m", strtotime($this->_salaryDate))-1, date("d", strtotime($this->_salaryDate)), date("Y", strtotime($this->_salaryDate))));
	   //echo '<pre>'; print_r($this->_salaryData);echo '<pre>';die;
	   if(!empty($this->_getData['Type'])){
		   Bootstrap::$LabelObj->outputparam = $this->_salaryData;
		   Bootstrap::$LabelObj->SalarySlip();
	   }
	 
	  
     if($this->_getData['Type']=='Final'){
       $this->SaveSalaryRecord($this->_salaryData);
	   $this->UpdateSalaryUsers($user_id,$this->_salaryDate);
	   //$this->UpdateLoanTransaction($this->_salaryData);
	   $this->UpdateProvidentTransaction($this->_salaryData);
       $this->UpdateEsiTransaction($this->_salaryData);
	 }  

  }

  

  public function SaveSalaryRecord($record){
     $deduction = array();
	 if(!empty($record['Deduction'])){
	   $deduction = $record['Deduction'];
	 }
     $this->_db->insert('salary_history',array_filter(array('user_id'=>$record['user_id'],'earning_amount'=>$record['EarningTotal'],'deduction_amount'=>$record['DeductionTotal'],'net_amount'=>$record['GrandTotal'],'paid_days'=>$record['Paid_days'],'leave_days'=>$record['Total_leave'],'salary_date'=>$record['date'],'release_date'=>date('Y-m-d'),'salary_slip_file'=>$record['Filename'],'final_salary_encoded'=>json_encode($record['Earnings']+$deduction+array('2A'=>$this->ObjModel->_salaryData['PFComp'])+ array('15A'=>$this->ObjModel->_salaryData['ESIComp'])+ array('2B'=>$this->ObjModel->_salaryData['ArrPFComp'])+ array('15B'=>$this->ObjModel->_salaryData['ArrESIComp'])))));
  }

 

  public function UpdateSalaryUsers($user_id,$salarydate){
    $this->_db->update('salary_list',array('salary_processed'=>'1'),"date='".$salarydate."'  AND salary_processed='0' AND user_id='".$user_id."'");

  }

  public function UpdateProvidentTransaction($data){
     if($data['Deduction'][2]>0){
	   $Arrcomp_provident =  0;
	   $comp_provident = 0;
       $providenttransec = $this->getlastProvidentTransaction($data['user_id']);
	  //$masterprivident  = $this->getMasterProvidentSetting();
		if($data['UserInfo']['comp_prov_status']=='1'){
		   $basicamount = $data['Earnings'][1];
		   $comp_provident =  ($basicamount)*str_replace('%','',$data['UserInfo']['prov_percentage_comp'])/100;
		}
		if($data['ArrierDeduction'][2]>0){
		   $basicamount = $data['ArrierEarnings'][1];
		   $Arrcomp_provident =  ($basicamount)*str_replace('%','',$data['UserInfo']['prov_percentage_comp'])/100;
		}
	  //$comp_provident = ($data['UserInfo']['ctc'] * $data['UserInfo']['provident_pecentage'])/100;

	   $totalprov = $comp_provident + $Arrcomp_provident + $data['Deduction'][2] + $data['ArrierDeduction'][2] + $providenttransec['total_pf'];
       $this->_db->insert('provident_fund_transaction',array('user_id'			=>$data['user_id'],
	   														 'deduct_from_sal'	=> ($data['Deduction'][2] + $data['ArrierDeduction'][2]),
															 'earn_by_comp'		=> ($comp_provident + $Arrcomp_provident),
															 'total_pf'			=> $totalprov,
															 'transaction_date'	=>new Zend_Db_Expr('NOW()')));
      }															 
	   return;
  }
  
  public function getlastProvidentTransaction($user_id){

     $select = $this->_db->select()

		 				->from(array('PT'=>'provident_fund_transaction'),array('*'))

						->where("user_id='".$user_id."'")

						->order("provident_transaction_id DESC")

						->limit(1);

		    $result = $this->getAdapter()->fetchRow($select);

	return 	$result;	

  }
  
  public function UpdateEsiTransaction($data){
     if($data['Deduction'][15]>0){
       $esitransec = $this->getlastEsiTransaction($data['user_id']);
	   $totalesi = $data['ESIComp'] + $data['ArrESIComp'] + $data['Deduction'][15] + $data['ArrierDeduction'][15] + $esitransec['total_esi'];
       $this->_db->insert('esi_fund_transaction',array('user_id'			=>$data['user_id'],
	   														 'deduct_from_sal'	=> ($data['Deduction'][15] + $data['ArrierDeduction'][15]),
															 'earn_by_comp'		=> ($data['ESIComp'] + $data['ArrESIComp']),
															 'total_esi'			=> $totalesi,
															 'transaction_date'	=>new Zend_Db_Expr('NOW()')));

      }															 

	   return;

  }
  
  public function getlastEsiTransaction($user_id){

     $select = $this->_db->select()

		 				->from(array('PT'=>'esi_fund_transaction'),array('*'))

						->where("user_id='".$user_id."'")

						->order("esi_transaction_id DESC")

						->limit(1);

		    $result = $this->getAdapter()->fetchRow($select);

	return 	$result;	

  }
  
  public function UpdateLoanTransaction($data){
	$select = $this->_db->select()
							->from('emp_loan',array('*'))
							->where("user_id='".$data['user_id']."' AND final_status='0' AND approve_status=3");
							//echo $select->__toString();die;
    $loandetail = $this->getAdapter()->fetchRow($select);

	if(!empty($loandetail)){

		$earning_amount = 0;

		$deduct_amount = 0;

	   if($loandetail['allowt_status']=='0'){

	       $earning_amount = $data['LoanAmount'];

		   $emiamount = 0; 

		   $loanpaid  = 0;

		   $no_of_emi = 0;

		   $final_status = '0';

	   }else{

	       $deduct_amount = $data['LoanAmount'];

		   $emiamount = $loandetail['emi_amount'];

		   $loan_paid = $loandetail['loan_paid'] + $data['LoanAmount'];

		   $no_of_emi = $loandetail['no_of_emi_paid'] + floor($data['LoanAmount'] / $loandetail['emi_amount']);

		   $final_status = (($loandetail['loan_including_tax'] - $loan_paid)<=0)?'1':'0';

	   }

	  $LoanArr = array('loan_id'=>$loandetail['emp_loan_id'],'user_id'=>$data['user_id'],'emi_amount'=>$emiamount,'earning_amount'=>$earning_amount,'deduct_amount'=>$deduct_amount,'transaction_date'=>new Zend_Db_Expr('NOW()')); 

	  $this->_db->insert('loan_tansaction',array_filter($LoanArr));

	  $this->_db->update('emp_loan',array('loan_paid'=>$loan_paid,'no_of_emi_paid'=>$no_of_emi,'allowt_status'=>'1','final_status'=>$final_status),"emp_loan_id='".$loandetail['emp_loan_id']."' AND user_id='".$data['user_id']."'");

	}							

  }

  public function getSalaryHistory(){

     $where = "1";

	 if($_SESSION['AdminLoginID']!=1){

	   $where .= " AND SH.user_id='".$_SESSION['AdminLoginID']."'";

	 }
	  $filterparam='';
	 if(!empty($this->_getData['user_id'])){
	     $filterparam .= " AND SH.user_id='".$this->_getData['user_id']."'";
	 }
	 if(!empty($this->_getData['department_id'])){
	    $filterparam .= " AND DEP.department_id='".$this->_getData['department_id']."'";
	 }
	 if(!empty($this->_getData['designation_id'])){
	    $filterparam .= " AND DES.designation_id='".$this->_getData['designation_id']."'";
	 }
	 if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
	   $filterparam .= " AND SH.release_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
	 }
         if(!empty($this->_getData['Bank'])){
            if($this->_getData['Bank']==1){
                $filterparam .= " AND EB.bank_name='AXIS BANKK LTD'";
            }
            if($this->_getData['Bank']==2){
                $filterparam .= " AND EB.bank_name='ICICI BANK LTD'";
            }
         }

     $select = $this->_db->select()
		 				->from(array('SH'=>'salary_history'),array('*'))
						->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=SH.user_id",array('CONCAT(first_name," ",last_name) as name'))
						->joininner(array('DES'=>'designation'),"DES.designation_id=EPD.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=EPD.department_id",array('department_name'))
                                                ->joinleft(array('EB'=>'emp_bank_account'),"EB.user_id=SH.user_id",array(''))
						->where($where.$filterparam)
						->order("SH.release_date DESC");

	 $result = $this->getAdapter()->fetchAll($select);
	 return $result; 

  }

  public function getLoandetail($user_id){

      $select = $this->_db->select()

		 				->from(array('EL'=>'emp_loan'),array('*'))

						->where("user_id='".$user_id."' AND final_status='0' AND approve_status=3");

	  $result = $this->getAdapter()->fetchRow($select);

	  $loanAmount = array();

	  if(!empty($result)){

		  if($result['allowt_status']=='0'){

		    $loanAmount['amount'] = $result['loan_amount'];

			$loanAmount['salaryheade_type'] = 1;

		  }elseif($result['deduct_from_sal']=='1'){

		    $loanAmount['amount'] = $result['emi_amount'];

			$loanAmount['salaryheade_type'] = 2;

		  }

	  }

	  if(!empty($loanAmount)){

				$this->_db->insert('salary_list',array_filter(array('user_id'=>$user_id,

																	'salaryhead_id'=>100,

																	'amount'=>$loanAmount['amount'],

																	'salaryheade_type'=>$loanAmount['salaryheade_type'],

																	'date'=>date('Y-m-d'))));

		} 

	return; 

  }

  

  public function getProvidentfund($userid,$providentamount){//print_r($providentamount);die;
         $userinfo = $this->UserInfo($userid);
		 $masterprivident = $this->getMasterProvidentSetting();
		if($userinfo['provident']==1 && $userinfo['provident_pecentage']>0 && $masterprivident['provident_status']=='1'){
			if($userinfo['provident_type']=='0'){
			   $providentdeduct =  $providentamount[1]*str_replace('%','',$userinfo['provident_pecentage'])/100;
			}else{
			   $providentdeduct =  $userinfo['provident_pecentage'];
			}
		 }else{
		      $providentdeduct = 0;
		 }
				$this->_db->insert('salary_list',array_filter(array('user_id'			=>$userid,
																	'salaryhead_id'		=>2,
																	'amount'			=>round($providentdeduct),
																	'salaryheade_type'	=>2,
																	'date'				=>$this->FromDate)));
      

	  return;

  }
  
  
   public function getEsiAmount($userid,$esiamount){//print_r($providentamount);die;

         $userinfo = $this->UserInfo($userid);
		 $masteresi = $this->getMasterEsiSettings();
		if($userinfo['esi_status']==1 && $userinfo['esi_percentage']>0 && $masteresi['esi_status']=='1'){
			  $esideduct =  $esiamount[1]*str_replace('%','',$userinfo['esi_percentage'])/100;
		 }else{
		     $esideduct = 0;
		 }
				$this->_db->insert('salary_list',array_filter(array('user_id'			=>$userid,
																	'salaryhead_id'		=> 15,
																	'amount'			=>round($esideduct),
																	'salaryheade_type'	=>2,
																	'date'				=>$this->FromDate)));
      

	  return;

  }

 

  public function UserInfo($user_id){

     $select = $this->_db->select()

	 					 ->from('users',array('*'))

						 ->where("user_id='".$user_id."'");

	  $result = $this->getAdapter()->fetchRow($select);

	  

      $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('*'))

						->joinleft(array('BA'=>'emp_bank_account'),"BA.user_id=UD.user_id",array('*'))
						->joinleft(array('DE'=>'designation'),"DE.designation_id=UD.designation_id",array('designation_code'))

						->where("UD.user_id='".$user_id."'");

						//echo $select->__toString();die;

	  $userinfo = $this->getAdapter()->fetchRow($select);

	  return $userinfo;

  }

  

   public function ExpotSalaryDetail(){
        $filterparam='';
	 if(!empty($this->_getData['user_id'])){
	     $filterparam .= " AND SH.user_id='".$this->_getData['user_id']."'";
	 }
	 if(!empty($this->_getData['department_id'])){
	    $filterparam .= " AND DEP.department_id='".$this->_getData['department_id']."'";
	 }
	 if(!empty($this->_getData['designation_id'])){
	    $filterparam .= " AND DES.designation_id='".$this->_getData['designation_id']."'";
	 }
	 if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
	   $filterparam .= " AND SH.release_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
	 }
         if(!empty($this->_getData['Bank'])){
            if($this->_getData['Bank']==1){
                $filterparam .= " AND UBD.bank_name='AXIS BANKK LTD'";
            }
            if($this->_getData['Bank']==2){
                $filterparam .= " AND UBD.bank_name='ICICI BANK LTD'";
            }
         }
         $select = $this->_db->select()
                                ->from(array('SH'=>'salary_history'),array('*'))
                                ->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=SH.user_id",array('first_name','last_name','employee_code'))
                                ->joinleft(array('UBD'=>'emp_bank_account'),"UBD.user_id=SH.user_id",array('*'))
                                ->where("SH.earning_amount>0".$filterparam)
								->order("employee_code ASC");
                                 //echo $select->__toString();die;

	$result = $this->getAdapter()->fetchAll($select);
	   $_nxtcol   = "\t";
	   $_nxtrow  = "\n";
	if($this->_getData['ExportSummary']=='Download Pay summary'){
	   $Header .= "\"Employee Name \"".$_nxtcol.
						"\"Employee Code\"".$_nxtcol.
						"\"Account Number \"".$_nxtcol.
						"\"Bank Name \"".$_nxtcol.
						"\"Date \"".$_nxtcol.
						"\"Expence Amount \"".$_nxtcol.
					"\"Payble Amount\"".$_nxtrow.$_nxtrow;
		foreach($result as $record){
		  $expamount  = $this->getLastmonthExpense($record['user_id'],$record['release_date']);
		    $Header .= "\"" . str_replace( "\"", "\"\"",$record['first_name'].' '.$record['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['employee_code']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"","'".$record['account_number']."'") . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['bank_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['release_date']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expamount) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['net_amount']) . "\"" . $_nxtcol;
			$Header .="\n";
		}
	}else{
	  $Header .= "\"Employee Name \"".$_nxtcol.
						"\"Employee Code\"".$_nxtcol.
						"\"Account Number \"".$_nxtcol.
						"\"Bank Name \"".$_nxtcol.
						"\"Date \"".$_nxtcol.
						"\"Earning Amount \"".$_nxtcol.
						"\"Deduction Amount \"".$_nxtcol.
						"\"Paid Days \"".$_nxtcol.
						"\"Leave Days\"".$_nxtcol.
						"\"Expence Amount \"".$_nxtcol.
					    "\"Payble Amount\"".$_nxtrow.$_nxtrow;
		foreach($result as $record){
		    $expamount  = $this->getLastmonthExpense($record['user_id'],$record['release_date']);
		    $Header .= "\"" . str_replace( "\"", "\"\"",$record['first_name'].' '.$record['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['employee_code']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"","'".$record['account_number']."'") . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['bank_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['release_date']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['earning_amount']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['deduction_amount']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['paid_days']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['leave_days']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$expamount) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['net_amount']) . "\"" . $_nxtcol;
			$Header .="\n";
		}
		
	}
	 	header("Content-type: application/xls");
        header("Content-Disposition: attachment; filename=PaySummary.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $Header;
        exit();
  } 
  
  public function ProvidentHistory(){
  	$where = "1";

	 if($_SESSION['AdminLoginID']!=1){
	   $where .= " AND PT.user_id='".$_SESSION['AdminLoginID']."'";
	 }

     $select = $this->_db->select()
		 				->from(array('PT'=>'provident_fund_transaction'),array('*'))
						->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=PT.user_id",array('CONCAT(first_name," ",last_name) as name'))
						->joininner(array('DES'=>'designation'),"DES.designation_id=EPD.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=EPD.department_id",array('department_name'))
						->where($where)
						->order("PT.transaction_date DESC");

	 $result = $this->getAdapter()->fetchAll($select);

	 return $result; 

  }
  
  public function ExpotPerovidentDetail(){
      $select = $this->_db->select()
		 				->from(array('PT'=>'provident_fund_transaction'),array('*'))
						->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=PT.user_id",array('first_name','last_name','employee_code'))
						->joinleft(array('UBD'=>'emp_bank_account'),"UBD.user_id=PT.user_id",array('*'))
						->where("transaction_date BETWEEN '".$this->FromDate."' AND '".$this->ToDate."'");

	$result = $this->getAdapter()->fetchAll($select);
	   $_nxtcol   = "\t";
	   $_nxtrow  = "\n";
	   $Header .= "\"Employee Name \"".$_nxtcol.
						"\"Employee Code\"".$_nxtcol.
						"\"Provident Account Number \"".$_nxtcol.
						"\"Deduct From Salary \"".$_nxtcol.
						"\"Deposited By Company \"".$_nxtcol.
					"\"Total Amount\"".$_nxtrow.$_nxtrow;
		foreach($result as $record){
		    $Header .= "\"" . str_replace( "\"", "\"\"",$record['first_name'].' '.$record['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['employee_code']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['prov_account_number']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['deduct_from_sal']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['earn_by_comp']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['total_pf']) . "\"" . $_nxtcol;
			$Header .="\n";
		}
	
	 	header("Content-type: application/xls");
        header("Content-Disposition: attachment; filename=PaySummary.xls");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $Header;
        exit();
  } 
  public function getFinalAttandance($employeecode,$salaryDate,$ctcchange = 0){ 
     $month = date('m',strtotime($salaryDate));
     $year = date('Y',strtotime($salaryDate));
     $select = $this->_db->select()
		 				->from(array('AT'=>'attandance'),array('*'))
						->where("month=".$month." AND year='".$year."' AND employee_code='".$employeecode."'");
						//echo $select->__toString();die;
					
	$attandance =  $this->getAdapter()->fetchRow($select);
	$attandance['total_salary_days'] = ($attandance['total_salary_days']>0)?$attandance['total_salary_days']:cal_days_in_month(CAL_GREGORIAN,$month,$year);
	$attandance['total_present'] = ($attandance['total_present']>0)?$attandance['total_present']:cal_days_in_month(CAL_GREGORIAN,$month,$year);
	if($ctcchange>0){
	   $attandance['total_present'] = $attandance['total_present'] - $ctcchange;
	}
	//print_r($attandance);die;
	return $attandance;
	 
  }
  public function getFinalArrearAttandance($employeecode,$salaryDate){ 
     $onemonthback = date('Y-m', strtotime($salaryDate." -1 month"));
	 $month = date('m',strtotime($onemonthback));
     $year = date('Y',strtotime($onemonthback));
     $select = $this->_db->select()
		 				->from(array('AT'=>'attandance'),array('*'))
						->where("month=".$month." AND year='".$year."' AND employee_code='".$employeecode."'");
						//echo $select->__toString();die;
					
	$attandance =  $this->getAdapter()->fetchRow($select);
	$attandance['total_salary_days'] = ($attandance['total_salary_days']>0)?$attandance['total_salary_days']:cal_days_in_month(CAL_GREGORIAN,$month,$year);
	$attandance['total_present'] = ($attandance['total_present']>0)?$attandance['total_present']:cal_days_in_month(CAL_GREGORIAN,$month,$year);
	//print_r($attandance);die;
	return $attandance;
	 
  }

  public function getExpenseAmount($user_id){
        $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('sum(approve_amount) as Approve','GROUP_CONCAT(expense_id) as expense_id'))
				->where("date_format(expense_date,'%Y-%m')<= '".date('Y-m')."' AND approve_status='1' AND salary_status='0' AND user_id='".$user_id."'")
				->group("EE.user_id");
						//echo $select->__toString();die;
	    $expense  =  $this->getAdapter()->fetchRow($select);//print_r($expense);die;
		
		$this->_db->insert('salary_list',array_filter(array('user_id'=>$user_id,'salaryhead_id'=>3,'amount'=>$expense['Approve'],'date'=>$this->FromDate,'salaryheade_type'=>1)));
	
	if(!empty($expense)){	
        $this->_db->update('emp_expenses',array('salary_status'=>'1','salary_date'=>$this->FromDate),"expense_id IN(".$expense['expense_id'].")");
     }
  }
  
  
  public function getPreviousHeadSetting(){
        $select = $this->_db->select()
						->from('salary_perticular_mounth',array('*'));
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
   }
   public function UpdatePerticularHeadSetting(){
      $this->_db->delete('salary_perticular_mounth',"1");
      if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	       $this->_db->insert('salary_perticular_mounth',array_filter(array('salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount,'date'=>$this->_getData['date'])));
		  
      if($this->_getData['edit_template']=='on'){
	      $this->_db->insert('salary_perticular_mounth',array_filter(array('salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));	  
		 }	
	    }				  
	  }
   }
   
   public function LeaveDetails($userid){
       $select = $this->_db->select()
	  			->from(array('EL'=>'emp_leaves'),array('*'))
				->joinleft(array('LT'=>'leavetypes'),"LT.typeID=EL.leave_id",array('typeName'))
				->where("user_id='".$userid."'");
						//echo $select->__toString();die;
	   $leaves  =  $this->getAdapter()->fetchAll($select);//print_r($leaves);die;
	   $leavedetail = array();
	   foreach($leaves as $leave){
	       if(strtolower($leave['typeName'])=='casual leave'){
		       $leavedetail['CL'] = $leave['no_of_leave'];
		   }elseif(strtolower($leave['typeName'])=='privilege leave'){
		       $leavedetail['PL'] = $leave['no_of_leave'];
		   }elseif(strtolower($leave['typeName'])=='sick leave'){
		       $leavedetail['SL'] = $leave['no_of_leave'];
		   }
	   }
	   return  $leavedetail;
	   
   }
   public function UserLocationDetails($user_id){
      $select = $this->_db->select()
		 				->from(array('EL'=>'emp_locations'),array(''))
						->joinleft(array('ST'=>'street'),"ST.street_id=EL.street_id",array('street_name'))
						->joinleft(array('CT'=>'city'),"CT.city_id=EL.city_id",array('city_name'))
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
		 				->joinleft(array('AT'=>'area'),"AT.area_id=EL.area_id",array('area_name'))
						->joinleft(array('RT'=>'region'),"RT.region_id=EL.region_id",array('region_name'))
		 				->joinleft(array('ZT'=>'zone'),"ZT.zone_id=EL.zone_id",array('zone_name'))
						->where("user_id='".$user_id."'");
						//echo $select->__toString();die;
	  $result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
	return $result;
   }
   public function ExportCurrentSalary(){
       $_nxtcol   = "\t";
	   $_nxtrow  = "\n";
	   $Header .= "\"Employee Code \"".$_nxtcol;
      
	  $select = $this->_db->select()
	 					 ->from('salary_head',array('*'))
						 ->where("salaryhead_id<16")
						 ->order("sequence ASC");
						 //echo $select->__toString();die;
	  $salaryheads = $this->getAdapter()->fetchAll($select);
	  foreach($salaryheads as $heads){
	     $Header .= "\"" . str_replace( "\"", "\"\"",$heads['salary_title']) . "\"" . $_nxtcol;
	  }
	  $Header .= "\"Paid Days \"".$_nxtcol;
	  $Header .= "\"Total Earning \"".$_nxtcol;
	  $Header .= "\"Total Deduction \"".$_nxtcol;
	  $Header .= "\"Net Total \"".$_nxtrow;
	  
	  $select = $this->_db->select()
	 					 ->from('salary_list',array('*'))
						 ->where("salary_processed='0' AND date='".$this->FromDate."'")
						 ->group("user_id");
	  $results = $this->getAdapter()->fetchAll($select);
	  //Generate Salary
	  foreach($results as $result){
	    $this->_salaryDate = $result['date'];
	    $this->CalculateSalary($result['user_id'],'');
		//print_r($this->_salaryData);die;
		$Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['UserInfo']['employee_code']) . "\"" . $_nxtcol;
		 foreach($salaryheads as $heads){
		 switch($heads['salary_type']){
		   case 1:
		     $Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['Earnings'][$heads['salaryhead_id']]) . "\"" . $_nxtcol;
		   break;
		   case 2:
		     $Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['Deduction'][$heads['salaryhead_id']]) . "\"" . $_nxtcol;
		   break;
		 }
	   }
	  $Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['Paid_days']) . "\"" . $_nxtcol;
	  $Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['EarningsTotal']) . "\"" . $_nxtcol;
	  $Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['DeductionsTotal']) . "\"" . $_nxtcol;
	  $Header .= "\"" . str_replace( "\"", "\"\"",$this->_salaryData['GrandTotal']) . "\"" . $_nxtcol;
	  $Header .=  $_nxtrow;
	  }//print_r($Header);die;
	  header("Content-type: application/xls");
      header("Content-Disposition: attachment; filename=SalaryDetail.xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $Header;
      exit(); 
   }
   public function getAmountByUser($user_id){
      $select = $this->_db->select()
		 				->from(array('ESA'=>'salary_list'),array('*'))
						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=ESA.salaryhead_id",array(''))
						->where("user_id='".$user_id."' AND salary_processed='0'")
						->where("date='".$this->FromDate."' AND salary_processed='0'")
						->order("sequence ASC");
	  $resuls = $this->getAdapter()->fetchAll($select);
	  $recorddata = array();
	  $headtype = array();
	  foreach($resuls as $amount){
	    $recorddata[$amount['salaryhead_id']] = $amount['amount'];
		$headtype[$amount['salaryhead_id']] = $amount['salaryheade_type'];
	   }
	  return array($recorddata,$headtype);
   }
   public function getchequeNumber($user_id){
      $select = $this->_db->select()
		 				->from(array('SCN'=>'salary_cheque_number'),array('*'))
						->where("user_id='".$user_id."'")
						->where("month_of_salary='".$this->_salaryDate."'");
	  $resuls = $this->getAdapter()->fetchRow($select);
	  return $resuls;
   }
   public function AddUpdateChequeNumber(){
     if(!empty($this->_getData['cheque_id'])){
	    $this->_db->update('salary_cheque_number',array('cheque_number'=>$this->_getData['cheque_number']),"cheque_id='".$this->_getData['cheque_id']."'");
		 $_SESSION[SUCCESS_MSG]= 'Cheque Number Updated Suucessfully';
	 }else{
	     $this->_db->insert('salary_cheque_number',array('cheque_number'=>$this->_getData['cheque_number'],'user_id'=>$this->_getData['user_id'],'month_of_salary'=>$this->_getData['salary_date']));
		 $_SESSION[SUCCESS_MSG]= 'Cheque Number Added Suucessfully';
	 } 
   } 
   public function getUserFilterHistory(){
      $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('CONCAT(employee_code,"-",first_name," ",last_name) as name','user_id'))
						->where("delete_status='0'")
						->order("employee_code");
	  $resuls = $this->getAdapter()->fetchAll($select);
	  return $resuls;
   }
   
  public function getBackSalaryList(){
	    $select = $this->_db->select()
		 				->from(array('UD'=>'employee_personaldetail'),array('*'))
						//->where("user_status='1' AND delete_status='0'")
						->where("user_status='1'")
						->order("employee_code ASC");
						//echo $select->__toString();die;
	     $Allusers = $this->getAdapter()->fetchAll($select);
		  foreach($Allusers as $result){
                      $select = $this->_db->select()
		 				->from(array('ESA'=>'salary_list'),array('date','salaryheade_type','user_id'))
						->where("salary_processed='0' AND ESA.user_id='".$result['user_id']."'")
						->group("ESA.user_id")
						->group("ESA.date");
						//echo $select->__toString();die; 
                      $results = $this->getAdapter()->fetchAll($select);
                      if(!empty($results)){
                           foreach($results as $record){
                                $recordData = array();
                                $amountdetail                       = $this->AllSalarylistAmount($result['user_id'],$record['date']);
                                $recordData['user_id'] 		=  $result['user_id'];
                                $recordData['name']	 		=  $result['first_name'].' '.$result['last_name'];
                                $recordData['employee_code']        =  $result['employee_code'];
                                $recordData['earnings'] 		=  $amountdetail[0]['EDamount'];
                                $recordData['dedection'] 		=  $amountdetail[1]['EDamount'];
                                $recordData['date']	   		=  date('F Y',strtotime($amountdetail[0]['date']));
                                $recordData['salary_date']	   		=  $amountdetail[0]['date'];
                                $finalData[] = $recordData;
                           }
                       }
//			  $recordData = array();
//			  $userDetail = $this->getUserDetail($result['user_id']);
//			  $amountdetail = $this->AllSalarylistAmount($result['user_id']);
//			  $recordData['user_id'] 		=  $result['user_id'];
//			  $recordData['name']	 		=  $userDetail['name'];
//			  $recordData['employee_code']  =  $userDetail['employee_code'];
//			  $recordData['designation'] 	=  $userDetail['designation_name'];
//			  $recordData['department'] 	=  $userDetail['department_name'];
//			  $recordData['earnings'] 		=  $amountdetail[0]['EDamount'];
//			  $recordData['dedection'] 		=  $amountdetail[1]['EDamount'];
//			  $recordData['date']	   		=  date('F Y',strtotime($amountdetail[0]['date']));
//		  	  $finalData[] = $recordData;
	       } 
	  return $finalData;
	
  }
  
  public function AllSalarylistAmount($user_id,$date){
          $select = $this->_db->select()
                                    ->from(array('ESA'=>'salary_list'),array('sum(amount) as EDamount','date','salaryheade_type'))
                                    ->where("salary_processed='0' AND user_id='".$user_id."' AND date='".$date."'")
                                    ->group("ESA.salaryheade_type")
                                    ->group("ESA.date");
                                    //echo $select->__toString();die;
	  $results = $this->getAdapter()->fetchAll($select); 
	  return $results; 
  }
  public function getUserSalaryHead(){
            $select = $this->_db->select()
                                ->from(array('UT'=>'employee_salary_amount'),array('*'))
                                ->joininner(array('SH'=>'salary_head'),"SH.salaryhead_id=UT.salaryhead_id",array("salary_title"))
                                ->where("user_id='".$this->_getData['user_id']."'")
                                ->order("salaryhead_id ASC");
                                //echo $select->__toString();die;
           return  $this->getAdapter()->fetchAll($select);

  }
  public function addArrearSalaryhead(){
        //print_r($this->_getData);die;
		$Duretion = $this->getSalaryDuration();
		$from = $Duretion['from_date'];
        $date = substr($this->_getData['date'],0,8).$from;
        foreach ($this->_getData['amount'] as $key=>$salary_amount){
            //print_r(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$key,'amount'=>$salary_amount,'salaryheade_type'=>$this->_getData['salaryhead_id'][$key],'date'=>$date,'salary_processed'=>'0'));die;
           $this->_db->insert('salary_list',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$key,'amount'=>$salary_amount,'salaryheade_type'=>$this->_getData['salaryhead_id'][$key],'date'=>$date,'salary_processed'=>'0')));
        }
  }
  public function ESICalculation($userinfo,$empsalaries,$attandance){
	   $earningtotal = 0;
	   $deductiontotal = 0;
	   $grandtotal = 0;
	   $head_id  = array(3,14,15,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,59,51,52,53,54,55,56,57,58,59,60,61,62,63,64,65,66,67,68,69,70);
	   foreach($empsalaries as $salary){ 
	     switch($salary['salaryheade_type']){
		     case 1:
			    if($salary['salaryhead_id']==7){
				  $salary['amount'] = $salary['amount'] - $this->_salaryData['PFComp'];
				}
			    if($salary['prodata_status']=='1' && !in_array($salary['salaryhead_id'],$head_id)){
				  if($this->_salaryData['CompanyContribution']>0 && $salary['salaryhead_id']==7){
				   //$salary['amount'] = $salary['amount'] - $this->_salaryData['CompanyContribution'];
				  }
			      $onedayamount = $salary['amount'] / $attandance['total_salary_days'] ;
				  //$earningtotal = $earningtotal + ($onedayamount * $attandance['total_present']);
				  $earningtotal = $earningtotal + $salary['amount'];
				}elseif($salary['prodata_status']=='0' && !in_array($salary['salaryhead_id'],$head_id)){
				  $earningtotal = $earningtotal + $salary['amount'];
				}
			    break;
			 case 2:
				if($salary['prodata_status']=='1' && !in_array($salary['salaryhead_id'],$head_id)){
					 //$onedayamount = $salary['amount'] / $attandance['total_salary_days'];
					 //$deductiontotal = $deductiontotal + ($onedayamount * $attandance['total_present']);
					 $deductiontotal = $deductiontotal + $salary['amount'];
				}elseif($salary['prodata_status']=='0' && !in_array($salary['salaryhead_id'],$head_id)){
					 $deductiontotal = $deductiontotal + $salary['amount'];
				}
				break;
		    }
	   }
            
	   $grandtotal = $earningtotal - $deductiontotal;
	    
		if($userinfo['esi_percentage_comp']!='' && $userinfo['comp_esi_status']==1 && !$this->_salaryData['CompanyCalc']){
				  $this->_salaryData['CompanyContribution'] =  $grandtotal * str_replace('%','',$userinfo['esi_percentage_comp'])/100; 
				  $this->_salaryData['CompanyCalc'] = true;
				  return $this->ESICalculation($userinfo,$empsalaries,$attandance);
		 }
		 if($userinfo['esi_status']=='1' && $userinfo['esi_percentage']!=''){
				$this->_salaryData['EmployeeContribution'] =  $grandtotal * str_replace('%','',$userinfo['esi_percentage'])/100;
				return array('CompanyContribution'=>$this->_salaryData['CompanyContribution'],'EmployeeContribution'=>$this->_salaryData['EmployeeContribution']);
		 }
    }
	
	
 public function PFCalculation($userinfo,$basics,$attandance){
     if($userinfo['provident_pecentage']!='' && $userinfo['provident']==1){ 
	    $empContribute =  $basics * str_replace('%','',$userinfo['provident_pecentage'])/100;
	 }
	 if($userinfo['prov_percentage_comp']!='' && $userinfo['comp_prov_status']==1){ 
	    $onedaybasic = $basics / $attandance['total_salary_days'];
		$monthalybasc = $onedaybasic * $attandance['total_present'];
	    $CompContribute = $basics * str_replace('%','',$userinfo['prov_percentage_comp'])/100;
	 }
	 return array('EmployeeContPF'=>$empContribute,'CompanyContPF'=>$CompContribute);
 }	
 
 public function ESICalculationNEW($userinfo,$basics,$attandance){
        if($userinfo['esi_percentage_comp']!='' && $userinfo['comp_esi_status']==1){
	 			  $this->_salaryData['CompanyContribution'] =  $basics * str_replace('%','',$userinfo['esi_percentage_comp'])/100;
		 }
		 if($userinfo['esi_status']=='1' && $userinfo['esi_percentage']!=''){
				$this->_salaryData['EmployeeContribution'] =  $basics * str_replace('%','',$userinfo['esi_percentage'])/100;
		 }
	 return array('CompanyContribution'=>$this->_salaryData['CompanyContribution'],'EmployeeContribution'=>$this->_salaryData['EmployeeContribution']);
 }	
  
  public function getLastmonthExpense($user_id,$date){
		 $select = $this->_db->select()
					->from('salary_list',array('SUM(amount) AS AMOUNT')) 
					->where("salaryhead_id=3 AND user_id='".$user_id."' AND date_format(date,'%Y-%m')='".date('Y-m',strtotime($date.' -1 month'))."'");
		$result = $this->getAdapter()->fetchRow($select);
		return $result['AMOUNT'];			
  }
  public function ExportSalaryBYHead($data){
  	 	$_nxtcol   = "\t";
	   $_nxtrow  = "\n";
	   $Header .= "\"Employee Code \"".$_nxtcol;
	   $Header .= "\"Month \"".$_nxtcol;
      
	  $select = $this->_db->select()
	 					 ->from('salary_head',array('*'))
						 ->where("salary_type=1")
						 ->order("sequence ASC");
						 //echo $select->__toString();die;
	  $salaryheads = $this->getAdapter()->fetchAll($select);
	  foreach($salaryheads as $heads){
	     $Header .= "\"" . str_replace( "\"", "\"\"",$heads['salary_title']) . "\"" . $_nxtcol;
	  }
	  $Header .= "\"Earning Total \"".$_nxtcol;
	  $select = $this->_db->select()
	 					 ->from('salary_head',array('*'))
						 ->where("salary_type=2")
						 ->order("sequence ASC");
						 //echo $select->__toString();die;
	  $deductions = $this->getAdapter()->fetchAll($select);

	  array_push($deductions,array('salaryhead_id'=>'2A','salary_title'=>'PF Comp. Contribution'));
	  array_push($deductions,array('salaryhead_id'=>'15A','salary_title'=>'ESI Comp. Contribution'));
	  array_push($deductions,array('salaryhead_id'=>'2B','salary_title'=>'Arr PF Comp. Contribution'));
	  array_push($deductions,array('salaryhead_id'=>'15B','salary_title'=>'Arr ESI Comp. Contribution'));
	  foreach($deductions as $heads){
	     $Header .= "\"" . str_replace( "\"", "\"\"",$heads['salary_title']) . "\"" . $_nxtcol;
	  }
	  
	  $Header .= "\"Deduction Total \"".$_nxtcol;
	  $Header .= "\"Net Total \"".$_nxtrow;
	  $params  = '';
	  if($data['user_id']>0){
	     $params .= " AND SH.user_id='".$data['user_id']."'";
	  }
	   if(!empty($data['user'])){
	     //$params .= " AND SH.user_id IN(".implode(',',$data['user']).")";
	  }
	  if($data['department_id']>0){
	     $params .= " AND ED.department_id='".$data['department_id']."'";
	  }
	  if($data['designation_id']>0){
	     $params .= " AND ED.designation_id='".$data['designation_id']."'";
	  }
	   if($data['Bank']>0){
	     $bankname = ($data['Bank']==1)?'AXIS BANKK LTD':(($data['Bank']==1)?'ICICI BANK LTD':'AXIS BANKK LTD');
	     $params .= " AND BK.bank_name='".$bankname."'";
	  }
	  if(!empty($data['from_date']) && !empty($data['to_date'])){
	     $params .= " AND SH.release_date>='".$data['from_date']."' AND SH.release_date<='".$data['to_date']."'";
	  }
	  $select = $this->_db->select()
	 					 ->from(array('SH'=>'salary_history'),array('*'))
						 ->joininner(array('ED'=>'employee_personaldetail'),"SH.user_id=ED.user_id",array('employee_code'))
						 ->joinleft(array('BK'=>'emp_bank_account'),"SH.user_id=BK.user_id",array(''))
						 ->where("1".$params)
						 ->order('employee_code ASC');
						// echo $select->__toString();die;
	  $results = $this->getAdapter()->fetchAll($select);
	  foreach($results as $salaries){
         if(!empty($salaries['final_salary_encoded'])){
		     $jsonedecode  = array();
		     $jsoneobj  = json_decode($salaries['final_salary_encoded']);
			 if(!empty($jsoneobj)){
			   $jsonedecode  =CommonFunction::customAssociative($jsoneobj);
			 }
			 $Header .= "\"" . str_replace( "\"", "\"\"",$salaries['employee_code']) . "\"" . $_nxtcol;
			 $Header .= "\"" . str_replace( "\"", "\"\"",date('M-Y',strtotime($salaries['salary_date']))) . "\"" . $_nxtcol;
			 $total = 0 ;
			 foreach($salaryheads as $heads){
				 $Header .= "\"" . str_replace( "\"", "\"\"",$jsonedecode[$heads['salaryhead_id']]) . "\"" . $_nxtcol;
				 $total = $total + $jsonedecode[$heads['salaryhead_id']];
			  }
		  $Header .= "\"" . str_replace( "\"", "\"\"", $salaries['earning_amount']) . "\"" . $_nxtcol;
		  foreach($deductions as $deducheads){
				 $Header .= "\"" . str_replace( "\"", "\"\"",$jsonedecode[$deducheads['salaryhead_id']]) . "\"" . $_nxtcol;
				 $total = $total + $jsonedecode[$deducheads['salaryhead_id']];
			  }
		  $Header .= "\"" . str_replace( "\"", "\"\"", $salaries['deduction_amount']) . "\"" . $_nxtcol;  
		  $Header .= "\"" . str_replace( "\"", "\"\"", $salaries['net_amount']) . "\"" . $_nxtcol;
		  $Header .=  $_nxtrow;
		 } 
	  }
	 //print_r($results);die;
	  //Generate Salary
	  header("Content-type: application/xls");
      header("Content-Disposition: attachment; filename=SalaryDetail.xls");
      header("Pragma: no-cache");
      header("Expires: 0");
      echo $Header;
      exit(); 
  }
  public function GenerateCurrentPrevSalary(){
   
       foreach($this->_getData['current_amount'] as $key=>$makeasarray){
	      $salaries['salaryheade_type'] = $this->_getData['salaryheade_type'][$key];
		  $salaries['prodata_status']   = $this->_getData['prodata_status'][$key];
		  $salaries['amount']  			= 	$makeasarray;
		  $salaries['salaryhead_id']  = $this->_getData['salaryhead_id'][$key];	
		  $empsalaries[]  =  $salaries;
	   }
	   echo "<pre>";print_r($empsalaries);die;
	   print_r($this->_getData);die;
	   $userinfo 	= $this->getAllUsersForSalary($this->_getData['user_id']);
	   //$attandance 	= array('total_salary_days'=>$this->_getData['current_sal_days'],'total_present'=>);
	   $this->_salaryData['EarningsTotal'] = 0;
	   $this->_salaryData['DeductionsTotal'] = 0;
	   $this->_salaryData['ArrEarningTotal'] = 0;
	   $this->_salaryData['ArrDeductionTotal'] = 0;
 // =======================Current Salary Calculation==============================	  
	   foreach($empsalaries as $salary){
	        $this->_salaryData['date'] = $salary['date'];
	     switch($salary['salaryheade_type']){
		     case 1:
			    if($salary['salaryhead_id']==1){
				  //$onedaypf = $salary['amount'] / $attandance['total_salary_days'] ;
				  $this->_salaryData['Basic'] = $salary['amount'];
				}
			    if($salary['salaryhead_id']==7){
				      $PFAmount = $this->PFCalculation($userinfo,$this->_salaryData['Basic'],$attandance);
					  $this->_salaryData['PFComp'] = $PFAmount['CompanyContPF'];
				      $EsiAmount = $this->ESICalculation($userinfo,$empsalaries,$attandance);
					  $salary['amount'] = $salary['amount'] - (round($EsiAmount['CompanyContribution']) + round($PFAmount['CompanyContPF']));
					  $this->_salaryData['ESIComp'] = $EsiAmount['CompanyContribution'];
			     }
			    if(trim($salary['prodata_status'])=='1'){
			      $onedaysalay = $salary['amount'] / $attandance['total_salary_days'] ;
				  $earning[$salary['salaryhead_id']] = $onedaysalay * $attandance['total_present'];
				  $this->_salaryData['Earnings'] = $earning; 
				  $this->_salaryData['EarningsTotal'] = $this->_salaryData['EarningsTotal'] + $earning[$salary['salaryhead_id']];
				}else{
				  $earning[$salary['salaryhead_id']] = $salary['amount'];
				  $this->_salaryData['Earnings'] = $earning;
				  $this->_salaryData['EarningsTotal'] = $this->_salaryData['EarningsTotal'] + $earning[$salary['salaryhead_id']];
				}  
			    break;
			 case 2:
			    if($salary['salaryhead_id']==15){
				  $salary['amount'] = $EsiAmount['EmployeeContribution'];
				}
				if($salary['salaryhead_id']==2){
				      $salary['amount'] = $PFAmount['EmployeeContPF'];
					  $this->_salaryData['PFEmp'] = $PFAmount['EmployeeContPF'];
			     } 
				if(trim($salary['prodata_status'])=='1'){
					 $perday = $salary['amount'] / $attandance['total_salary_days'];
					 $deduct[$salary['salaryhead_id']] = $perday * $attandance['total_present'];
					 $this->_salaryData['Deduction'] = $deduct;
					 $this->_salaryData['DeductionsTotal'] = $this->_salaryData['DeductionsTotal'] + $deduct[$salary['salaryhead_id']];
				}else{
					 $deduct[$salary['salaryhead_id']] = $salary['amount'];
					 $this->_salaryData['Deduction'] = $deduct;
					 $this->_salaryData['DeductionsTotal'] = $this->_salaryData['DeductionsTotal'] + $deduct[$salary['salaryhead_id']]; 
					}
				 break; 	
		    }
	   }
	   
 //===================Arear salary Calculation================================   
	$arearsalaries =  $this->getArrierSlalaryAmount($user_id,$this->_salaryDate);
	   if(!empty($arearsalaries)){ 
	   			$Aattandance 	= $this->getFinalArrearAttandance($userinfo['employee_code'],$this->_salaryDate);
                $totaldays = ($Aattandance['total_salary_days']>0)?$Aattandance['total_salary_days']:cal_days_in_month(CAL_GREGORIAN,$this->_AMonth,$this->_Ayear);
		  foreach($arearsalaries as $arearsalary){
	     switch(trim($arearsalary['salaryheade_type'])){ 
		     case 1:
			     if($arearsalary['salaryhead_id']==1){
				   //$onedaypf = $arearsalary['amount'] / $Aattandance['total_salary_days'] ;
				   $this->_salaryData['Basic'] = $arearsalary['amount'];
				 }
			    if($arearsalary['salaryhead_id']==7){
				      $ArrPFAmount = $this->PFCalculation($userinfo,$this->_salaryData['Basic'],$Aattandance);
					  $this->_salaryData['ArrPFComp'] = $ArrPFAmount['CompanyContPF'];
				      $ArrEsiAmount = $this->ESICalculation($userinfo,$arearsalaries,$Aattandance);
					  $arearsalary['amount'] = $arearsalary['amount'] - (round($ArrEsiAmount['CompanyContribution']) + round($ArrPFAmount['CompanyContPF']));
					  $this->_salaryData['ArrESIComp'] = $ArrEsiAmount['CompanyContribution'];
			     }
			    if($arearsalary['prodata_status']=='1'){
			      $onedaysalay = $arearsalary['amount'] / $Aattandance['total_salary_days'] ;
				  $Arrearning[$arearsalary['salaryhead_id']] = $onedaysalay * $Aattandance['total_present'];
				  $this->_salaryData['ArrierEarnings'] =  $Arrearning;
				  $this->_salaryData['ArrEarningTotal'] = $this->_salaryData['ArrEarningTotal'] + $Arrearning[$arearsalary['salaryhead_id']];
				}else{
				  $Arrearning[$arearsalary['salaryhead_id']] = $arearsalary['amount'];
				  $this->_salaryData['ArrierEarnings'] = $Arrearning;
				  $this->_salaryData['ArrEarningTotal'] = $this->_salaryData['ArrEarningTotal'] + $Arrearning[$arearsalary['salaryhead_id']];
				}  
			    break;
			 case 2:
			    if($arearsalary['salaryhead_id']==15){
				  $arearsalary['amount'] = $ArrEsiAmount['EmployeeContribution'];
				} 
				if($arearsalary['salaryhead_id']==2){ 
				      $arearsalary['amount'] 	     = $ArrPFAmount['EmployeeContPF'];
					  //$this->_salaryData['ArrPFEmp'] = $ArrPFAmount['EmployeeContPF'];
			     } 
				if($arearsalary['prodata_status']=='1'){
					 $perday = $arearsalary['amount'] / $Aattandance['total_salary_days'];
					 $Arrdeduct[$arearsalary['salaryhead_id']] = $perday * $Aattandance['total_present'];
					 $this->_salaryData['ArrierDeduction'] = $Arrdeduct;
					 $this->_salaryData['ArrDeductionTotal'] = $this->_salaryData['ArrDeductionTotal'] + $Arrdeduct[$arearsalary['salaryhead_id']];
				}else{
					 $Arrdeduct[$salary['salaryhead_id']] = $arearsalary['amount'];
					 $this->_salaryData['ArrierDeduction'] = $Arrdeduct;
					 $this->_salaryData['ArrDeductionTotal'] = $this->_salaryData['ArrDeductionTotal'] + $Arrdeduct[$arearsalary['salaryhead_id']]; 
					}
				break; 	
		    }
	   }		
	 }
          
	   $grandtotal = (round($this->_salaryData['EarningsTotal']) + round($this->_salaryData['ArrEarningTotal']))-round($this->_salaryData['DeductionsTotal'] + $this->_salaryData['ArrDeductionTotal']);
       $this->_salaryData['Paid_days'] 	 = $attandance['total_present'] + $Aattandance['total_present'];
       $this->_salaryData['Total_leave'] = $attandance['total_leave'] + $Aattandance['total_leave']+$attandance['total_absent'] + $Aattandance['total_absent'];
	   $this->_salaryData['user_id']  	 = $user_id;  
	   $this->_salaryData['Leaves'] 	 = $this->LeaveDetails($user_id);
	   $this->_salaryData['Locations']   = $this->UserLocationDetails($user_id);
	   $this->_salaryData['Bank']   	= $this->getBankAccountDetail($user_id);
	   $this->_salaryData['Cheuqe']      = $this->getchequeNumber($user_id);
	   $this->_salaryData['EarningTotal'] = round($this->_salaryData['EarningsTotal']) + round($this->_salaryData['ArrEarningTotal']);
	   $this->_salaryData['DeductionTotal'] = round($this->_salaryData['DeductionsTotal'] + $this->_salaryData['ArrDeductionTotal']);
	   $this->_salaryData['GrandTotal']     = round($grandtotal);
	   $this->_salaryData['Filename'] = $filename; 
	   $this->_salaryData['LoanAmount'] = $loanamount;
	   $this->_salaryData['LoanActionType'] = $loancredebtype;
	   $this->_salaryData['ProvidentAmount'] = $this->_salaryData['Deduction'][2];
       $this->_salaryData['EsiAmount'] = $this->_salaryData['Deduction'][15];
	   if($grandtotal>0){
		$this->_salaryData['TotalText'] = $this->ConvertToWords($grandtotal);
	   }
	   $this->_salaryData['UserInfo'] = $userinfo; 
	   $this->_salaryData['ExpMonth']   =   date("M Y",mktime(0, 0, 0, date("m", strtotime($this->_salaryDate))-1, date("d", strtotime($this->_salaryDate)), date("Y", strtotime($this->_salaryDate))));
	   //echo '<pre>'; print_r($this->_salaryData);echo '<pre>';die;
	   if(!empty($this->_getData['Type'])){
		   Bootstrap::$LabelObj->outputparam = $this->_salaryData;
		   Bootstrap::$LabelObj->SalarySlip();
	   }
	 
	  
     if($this->_getData['Type']=='Final'){
       $this->SaveSalaryRecord($this->_salaryData);
	   $this->UpdateSalaryUsers($user_id,$this->_salaryDate);
	   //$this->UpdateLoanTransaction($this->_salaryData);
	   $this->UpdateProvidentTransaction($this->_salaryData);
       $this->UpdateEsiTransaction($this->_salaryData);
	 }  

  	
     
 }
 
  public function insertManualSalary(){
      $date =  date('Y-m-d',mktime(0, 0, 0, date("m",strtotime($this->_getData['date'])), 02,  date("Y",strtotime($this->_getData['date']))));
      $this->_db->insert('salary_manual_arrier',array_filter(array('user_id'=>$this->_getData['user_id'],'salary_days'=>$this->_getData['salary_days'],'arr_mode'=>$this->_getData['arr_mode'],'date'=>$date)));
	  $arearsalaries =  $this->getArrierSlalaryAmount($user_id,$date);
	  if(empty($arearsalaries)){
		  $amountdatas = $this->getUserSalaryHead();
		  $masterprivident = $this->getMasterProvidentSetting();
		  $userinfo = $this->UserInfo($this->_getData['user_id']);
		  foreach($amountdatas as $salaryamount){
			 $providentAmount[$salaryamount['salaryhead_id']] = $salaryamount['amount'];
			 switch($salaryamount['salaryhead_id']){
				case 2:
					if($userinfo['provident']==1 && $userinfo['provident_pecentage']>0 && $masterprivident['provident_status']=='1'){
						if($userinfo['provident_type']=='0'){
						   $providentdeduct =  $providentamount[1]*str_replace('%','',$userinfo['provident_pecentage'])/100;
						}else{
						   $providentdeduct =  $userinfo['provident_pecentage'];
						}
					 }else{
						  $providentdeduct = 0;
					 }
					$this->_db->insert('salary_arear_list',array_filter(array('user_id'	=>$this->_getData['user_id'],
																		'salaryhead_id'		=>2,
																		'amount'			=>round($providentdeduct),
																		'salaryheade_type'	=>2,
																		'date'				=>$date)));
				  break;
				case 3:
					$this->_db->insert('salary_arear_list',array_filter(array('user_id'	=>$this->_getData['user_id'],
																		'salaryhead_id'		=>3,
																		'amount'			=>'0.00',
																		'salaryheade_type'	=>1,
																		'date'				=>$date)));
				  break;   
				 default :
				   $this->_db->insert('salary_arear_list',array_filter(array('user_id'=>$this->_getData['user_id'],
																	'salaryhead_id'=>$salaryamount['salaryhead_id'],
																	'amount'=>$salaryamount['amount'],
																	'salaryheade_type'=>$salaryamount['salaryheade_type'],
																	'date'=>$date)));
				  break;   
			 }
		 }
	 }
  }
  
  public function getManaualArrier($user_id,$slarydate){
       $date1   =   mktime(0, 0, 0, date("m", strtotime($slarydate))-1, date("d", strtotime($slarydate)), date("Y", strtotime($slarydate)));
       $arrier_date = date('Y-m-d', $date1);
       $select = $this->_db->select()
					->from('salary_manual_arrier',array('*')) 
					->where("user_id='".$user_id."' AND date='".$arrier_date."' AND salary_processed='0'");
		$result = $this->getAdapter()->fetchRow($select);
		if(!empty($result)){
		    $month = date('m',strtotime($arrier_date));
			$year = date('Y',strtotime($arrier_date));
			$attandance['total_salary_days'] = cal_days_in_month(CAL_GREGORIAN,$month,$year);
			$attandance['total_present'] = $result['salary_days'];
			return $attandance;
		}else{
		    return array();
		}
  }
  
  public function ChangeCTCSalaryCalculation($user_id,$userinfo,$changectc,$salaryDate){
        $empsalaries = $this->getPreviusSalaryAmounts($user_id,$salaryDate);
		$userinfo = $this->getDetailOFEmployee($user_id);
		$month = date('m',strtotime($salaryDate));
		$year = date('Y',strtotime($salaryDate));
		$attandance['total_salary_days'] = cal_days_in_month(CAL_GREGORIAN,$month,$year);
		$attandance['total_present'] = $changectc ; 
		 
		 foreach($empsalaries as $salary){
	     switch($salary['salaryheade_type']){
		     case 1:
			    if($salary['salaryhead_id']==1){
				  $this->_salaryData['Basic'] = $salary['amount'];
				}
			    if($salary['salaryhead_id']==7){
				      $PFAmount = $this->PFCalculation($userinfo,$this->_salaryData['Basic'],$attandance);
					  $this->_salaryData['PFComp'] = $this->_salaryData['PFComp'] + $PFAmount['CompanyContPF'];
				      $EsiAmount = $this->ESICalculation($userinfo,$empsalaries,$attandance);
					  $salary['amount'] = $salary['amount'] - (round($EsiAmount['CompanyContribution']) + round($PFAmount['CompanyContPF']));
					  $this->_salaryData['ESIComp'] = $this->_salaryData['ESIComp'] + $EsiAmount['CompanyContribution'];
			     }
			    if(trim($salary['prodata_status'])=='1'){
			      $onedaysalay = $salary['amount'] / $attandance['total_salary_days'] ;
				  //echo $onedaysalay."-".$attandance['total_salary_days']."-".$attandance['total_present']; die;
				  $this->_salaryData['Earnings'][$salary['salaryhead_id']] = $this->_salaryData['Earnings'][$salary['salaryhead_id']] + ($onedaysalay * $attandance['total_present']);
				  $this->_salaryData['EarningsTotal'] = $this->_salaryData['EarningsTotal'] + ($onedaysalay * $attandance['total_present']);
				}else{
				  $this->_salaryData['Earnings'][$salary['salaryhead_id']] = $this->_salaryData['Earnings'][$salary['salaryhead_id']] + $salary['amount'];
				  $this->_salaryData['EarningsTotal'] = $this->_salaryData['EarningsTotal'] + $salary['amount'];
				}  
			    break;
			 case 2:
			    if($salary['salaryhead_id']==15){
				  $salary['amount'] = $EsiAmount['EmployeeContribution'];
				}
				if($salary['salaryhead_id']==2){
				      $salary['amount'] = $PFAmount['EmployeeContPF'];
					  $this->_salaryData['PFEmp'] = $this->_salaryData['PFEmp'] + $PFAmount['EmployeeContPF'];
			     } 
				if(trim($salary['prodata_status'])=='1'){
					 $perday = $salary['amount'] / $attandance['total_salary_days'];
					 $this->_salaryData['Deduction'][$salary['salaryhead_id']] = $this->_salaryData['Deduction'][$salary['salaryhead_id']] + ($perday * $attandance['total_present']);
					 $this->_salaryData['DeductionsTotal'] = $this->_salaryData['DeductionsTotal'] + ($perday * $attandance['total_present']);
				}else{
					 $this->_salaryData['Deduction'][$salary['salaryhead_id']] = $this->_salaryData['Deduction'][$salary['salaryhead_id']] + $salary['amount'];
					 $this->_salaryData['DeductionsTotal'] = $this->_salaryData['DeductionsTotal'] +$salary['amount']; 
					}
				 break; 	
		    }
	   }
	    //echo "<pre>"; print_r($this->_salaryData);die;
  }
  
 public function UpdateEncode($date,$user_id,$encoded_data,$salarymont){
    $this->_db->update('salary_history',array('final_salary_encoded'=>$encoded_data,'salary_date'=>$salarymont),"user_id='".$user_id."' AND date_format(release_date,'%Y-%m')='".date('Y-m',strtotime($date))."'"); 
  }
  
}

?>