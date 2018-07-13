<?php

class ReportManager extends Zend_Custom

{
	 public $_getData = array();
	 
	 public function getExpenseReport(){
	      $where = 1;
		  $childUser = $this->getChildUsersofApproval('expense_approval');
		  if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     $where .= " AND EE.user_id IN ('".implode(',',$childUser )."') OR EE.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND EE.user_id='".$_SESSION['AdminLoginID']."'";
		  }
		  $filter = '';
		   if(!empty($this->_getData['user_id'])){
				//$filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
				$childusers = $this->getChildUser(array($this->_getData['user_id']),1);
				$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['tocken_abm'])){
				//$filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
				$childusers = $this->getChildUser(array($this->_getData['tocken_abm']),1);
				$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['tocken_be'])){
				//$filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
				$childusers = $this->getChildUser(array($this->_getData['tocken_be']),1);
				$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['department_id'])){
				$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
		   }
		   if(!empty($this->_getData['designation_id'])){
				$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
		   }
		   if(!empty($this->_getData['headquater_id'])){
				$filter .= " AND EL.headquater_id='".$this->_getData['headquater_id']."'";
		   }
		   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
				$filter .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		   }
		
		    $orderlimit = CommonFunction::OdrderByAndLimit($this->_getData,'ED.employee_code');
	        $orderlimit1 = CommonFunction::OdrderByAndLimit($this->_getData,'DATE_FORMAT(EE.expense_date,"%Y-%m")','DESC');
			
			$select = $this->_db->select()
							   ->from(array('EE'=>'emp_expenses'),array('COUNT(1) AS CNT'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array(''))
								->where($where.$filter)
								->group(array('EE.user_id','DATE_FORMAT(EE.expense_date,"%Y-%m")'));
			$total =  $this->getAdapter()->fetchAll($select);
			$select = $this->_db->select()
							   ->from(array('EE'=>'emp_expenses'),array('*','SUM(expense_amount) AS expense','SUM(fare) AS fare_amount','SUM(mixed_amount) AS mixed_amount','SUM(approve_amount) AS approve_am','DATE_FORMAT(EE.expense_date,"%Y-%m") AS expense_date'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id AND ED.delete_status='0'",array('employee_code','first_name','last_name'))
							   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
								->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
								->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(''))
								->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
								->where($where.$filter)
								->group(array('EE.user_id','DATE_FORMAT(EE.expense_date,"%Y-%m")'))
								->order($orderlimit1['OrderBy'].' '.$orderlimit1['OrderType'])
								->order("ED.employee_code ASC");
								//->limit($orderlimit1['Toshow'],$orderlimit1['Offset']);
								//echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
		//return array('Total'=>count($total),'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']); 
		return array('Records'=>$result);			
	 }
	 public function getLeaveReport(){
	      $where = 1;
		  $childUser = $this->getChildUsersofApproval('emp_leave_approval');
		  if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     $where .= " AND LR.user_id IN ('".implode(',',$childUser )."')";
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND LR.user_id='".$_SESSION['AdminLoginID']."'";
		  }
		  
		   	   $filter = '';
			   if(!empty($this->_getData['user_id'])){
					$filter .= " AND LR.user_id='".$this->_getData['user_id']."'";
			   }
			   if(!empty($this->_getData['department_id'])){
					$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
			   } 
			   if(!empty($this->_getData['designation_id'])){
					$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
			   }
			   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
					$filter .= " AND date(LR.request_date) BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
			   }
	    
			$select = $this->_db->select()
							   ->from(array('LR'=>'leaverequests'),array('*'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"LR.user_id=ED.user_id",array('employee_code'))
							   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
								->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
								->where($where.$filter);
								
			$result =  $this->getAdapter()->fetchAll($select);
		return $result;					
	 }
	 
	  public function getAttandanceReport(){
	      	$where = 1;
		  if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     $where .= " AND ED.parent_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }
		 $select = $this->_db->select()
		 				->from(array('AT'=>'attandance'),array('*'))
						->joinleft(array('ED'=>'employee_personaldetail'),"ED.employee_code=AT.employee_code",array())
						->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
						->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))						
						->where($where);
						//echo $select->__toString();die;
	  	$result = $this->getAdapter()->fetchAll($select);
	  return $result;
	 }
	 
	 public function getChildUsersofApproval($table){
	    $select = $this->_db->select()
							   ->from(array('AT'=>$table),array('*'))
							   ->where("approval_user_id='".$_SESSION['AdminLoginID']."'")
							   ->group("user_id");
		$result =  $this->getAdapter()->fetchAll($select);
		if(!empty($result)){
		  foreach($result as $user_id){
		     $ids[] = $user_id['user_id']; 
		  }
		}else{
		  $ids = array();
		}
		return $ids;		   
	 }
        public function ExportExpenseReportHqwise(){
           $filter = '';
		   if(!empty($this->_getData['user_id'])){
				$filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
				//$childusers = $this->getChildUser(array($this->_getData['user_id']),1);
				//$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['tocken_abm'])){
				$filter .= " AND ED.user_id='".$this->_getData['tocken_abm']."'";
				//$childusers = $this->getChildUser(array($this->_getData['tocken_abm']),1);
				//$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['tocken_be'])){
				$filter .= " AND ED.user_id='".$this->_getData['tocken_be']."'";
				//$childusers = $this->getChildUser(array($this->_getData['tocken_be']),1);
				//$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['department_id'])){
				$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
		   }
		   if(!empty($this->_getData['designation_id'])){
				$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
		   }
		   if(!empty($this->_getData['headquater_id'])){
				$filter .= " AND EL.headquater_id='".$this->_getData['headquater_id']."'";
		   }
		   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
				$filter .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		   }
		   
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;
				
			$select = $this->_db->select()
                                   ->from(array('EE'=>'emp_expenses'),array('user_id'))
                                   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id AND ED.delete_status='0'",array('employee_code'))
                                   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array(''))
                                   ->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array(''))
									->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(''))
									->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name','headquater_id'))
                                   ->where("1".$filter)
								   ->group("HQ.headquater_name")
								   ->group("ED.user_id")
								    ->order("HQ.headquater_name ASC");

	    $allemployee =  $this->getAdapter()->fetchAll($select);
		//print_r($allemployee);die;
	
			$tatalexpense = 0;	
			$totalfare = 0;	
			$totalapprove  =0;	
		$sheetindex = 0 ;	
		foreach($allemployee as $key=>$employee){
		        if($sheetindex>0){
		          $objWorkSheet = $objPHPExcel->createSheet();
				  $objPHPExcel->setActiveSheetIndex(intval($sheetindex));
				}$styleArray = array(

							  'borders' => array(

								'allborders' => array(

								  'style' => PHPExcel_Style_Border::BORDER_THIN

								)

							  )

							);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H40')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
							
		        $objPHPExcel->getActiveSheet()->fromArray(array('Employee Code','Employee Name','Designation','Headquater','Month Of Expense','Total Claim','Total Approve','Status'), NULL, 'A1');
			
				
			$select = $this->_db->select()
							   ->from(array('EE'=>'emp_expenses'),array('*','SUM(expense_amount) AS expense','SUM(fare) AS fare_amount','SUM(mixed_amount) AS mixed_amount','SUM(approve_amount) AS approve_am','DATE_FORMAT(EE.expense_date,"%Y-%m") AS expense_date','salary_date'))
							   ->joininner(array('EH'=>'expense_head'),"EH.head_id=EE.head_id",array('head_name'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('employee_code','first_name','last_name'))
							   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
							   ->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
								->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(''))
								->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where("HQ.headquater_id='".$employee['headquater_id']."' AND ED.user_id='".$employee['user_id']."'".$filter)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.expense_date,'%Y-%m')")
							   ->order("DATE_FORMAT(EE.expense_date,'%Y-%m') DESC")
								->order("ED.employee_code ASC");

	        $result =  $this->getAdapter()->fetchAll($select);//print_r($result);die;
			foreach($result as $i=>$record){
			  $objPHPExcel->getActiveSheet()->fromArray(array($record['employee_code'],$record['first_name'].' '.$record['last_name'],$record['designation_name'],$record['headquater_name'],date('F -Y',strtotime($record['expense_date'])),$record['expense']+$record['fare_amount']+$record['mixed_amount'],$record['approve_am'],($record['salary_date']!='0000-00-00')?date('F -Y',strtotime($record['salary_date'])):'Pending'), NULL, 'A'.($i+2));
			}
		        
			$tatalexpense = $tatalexpense + $record['expense']+$record['fare_amount']+$record['mixed_amount'];
			$totalapprove = $totalapprove + $record['approve_am'];
			$objPHPExcel->getActiveSheet()->setTitle($employee['employee_code'].'-'.$employee['headquater_name']);
			$sheetindex = $sheetindex+ 1;
			$objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':H'.($i+3))->getFont()->setBold(true);
			 $objPHPExcel->getActiveSheet()->fromArray(array('','','','','Total',$tatalexpense,$totalapprove,''), NULL, 'A'.($i+3));		
			
		}
		//$Header .= "\"" . str_replace( "\"", "\"\"",$totalapprove) . "\"" . $_nxtcol;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="name_of_file.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
		//$objWriter->save('test.xlsx');  //THIS WORKS
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);die;
        }
		
		public function ExportExpenseReportempwise(){
           $_nxtcol   = "\t";
	       $_nxtrow  = "\n";

           $filter = '';
		   if(!empty($this->_getData['user_id'])){
				$filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
				//$childusers = $this->getChildUser(array($this->_getData['user_id']),1);
				//$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['tocken_abm'])){
				$filter .= " AND ED.user_id='".$this->_getData['tocken_abm']."'";
				//$childusers = $this->getChildUser(array($this->_getData['tocken_abm']),1);
				//$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['tocken_be'])){
				$filter .= " AND ED.user_id='".$this->_getData['tocken_be']."'";
				//$childusers = $this->getChildUser(array($this->_getData['tocken_be']),1);
				//$filter .= " AND ED.user_id IN (".implode(',',$childusers).")";
		   }
		   if(!empty($this->_getData['department_id'])){
				$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
		   }
		   if(!empty($this->_getData['designation_id'])){
				$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
		   }
		   if(!empty($this->_getData['headquater_id'])){
				$filter .= " AND EL.headquater_id='".$this->_getData['headquater_id']."'";
		   }
		   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
				$filter .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		   }
		   
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;
				
			$select = $this->_db->select()
                                   ->from(array('EE'=>'emp_expenses'),array('user_id','expense_date'))
                                   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id AND ED.delete_status='0'",array('employee_code','first_name','last_name'))
                                   ->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
									->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(''))
									->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name','headquater_id'))
                                   ->where("expense_date!='0000-00-00'".$filter)
								   ->group("EE.user_id")
									->group("date_format(expense_date,'%m-%Y')")
								    ->order("date_format(expense_date,'%m-%Y') DESC");

	    $allemployee =  $this->getAdapter()->fetchAll($select);
		//print_r($allemployee);die;
	
			$tatalexpense = 0;	
			$totalfare = 0;	
			$totalapprove  =0;	
		$sheetindex = 0 ;	
	         foreach($allemployee as $key=>$employee){
		        if($sheetindex>0){
		          $objWorkSheet = $objPHPExcel->createSheet();
				  $objPHPExcel->setActiveSheetIndex(intval($sheetindex));
				}
		          //$objWorkSheet = $objPHPExcel->createSheet();
				  //$objPHPExcel->setActiveSheetIndex(intval($sheetindex));
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H40')->applyFromArray($styleArray);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);			
							
		        $objPHPExcel->getActiveSheet()->fromArray(array('Employee Code','Employee Name','Designation','Headquater','Month Of Expense'), NULL, 'A1');
				
				$objPHPExcel->getActiveSheet()->fromArray(array($employee['employee_code'],$employee['first_name'].' '.$employee['last_name'],$employee['designation_name'],$employee['headquater_name'],date('F -Y',strtotime($employee['expense_date']))), NULL, 'A2');
				
				$objPHPExcel->getActiveSheet()->fromArray(array('Date','Place','HQ|EX|out-station','Travel','From|To','Fare','Total Claim','Approve'), NULL, 'A5');
				$objPHPExcel->getActiveSheet()->getStyle('A5:H1')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('A5:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				
			 $select = $this->_db->select()
	  			->from(array('EE'=>'emp_expenses'),array('*'))
				->joinleft(array('EH'=>'expense_head'),"EH.head_id=EE.head_id",array('head_name'))
				->where("user_id='".$employee['user_id']."' AND date_format(expense_date,'%m-%Y')= '".date('m-Y',strtotime($employee['expense_date']))."'");
                                    //echo $select->__toString();die;
	   		$result =  $this->getAdapter()->fetchAll($select);	
			foreach($result as $i=>$record){
			  $objPHPExcel->getActiveSheet()->fromArray(array(date('d-m',strtotime($record['expense_date'])),$employee['headquater_name'],$record['head_name'],$record['travel_destination'],$record['expense_detail'],$record['fare']+$record['mixed_amount'],$record['expense_amount']+$record['fare']+$record['mixed_amount'],$record['approve_amount']), NULL, 'A'.($i+7));
			  
				$totalfare = $totalfare + $record['fare']+$record['mixed_amount'];
				$tatalexpense = $tatalexpense + $record['expense']+$record['fare']+$record['mixed_amount'];
				$totalapprove = $totalapprove + $record['approve_amount'];
			}
		     $objPHPExcel->getActiveSheet()->setTitle($employee['employee_code'].'-'.date('F -Y',strtotime($employee['expense_date'])));
			$sheetindex = $sheetindex+ 1;  
			$objPHPExcel->getActiveSheet()->fromArray(array('','','','','Total',$totalfare,$tatalexpense,$totalapprove), NULL, 'A'.($i+9));
			/*$tatalexpense = $tatalexpense + $record['expense']+$record['fare_amount']+$record['mixed_amount'];
			$totalapprove = $totalapprove + $record['approve_am'];
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.($i+3).':H'.($i+3))->getFont()->setBold(true);
			 $objPHPExcel->getActiveSheet()->fromArray(array('','','','Total',$tatalexpense,$totalapprove,''), NULL, 'A'.($i+3));	*/	
			
		}
		//$Header .= "\"" . str_replace( "\"", "\"\"",$totalapprove) . "\"" . $_nxtcol;
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="Expense Report.xls"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
		//$objWriter->save('test.xlsx');  //THIS WORKS
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);die;
        }
       
	    public function ExportLeaveReport(){
            $_nxtcol   = "\t";
	   $_nxtrow  = "\n"; 

		  $filter = '';
		   if(!empty($this->_getData['user_id'])){
				$filter .= " AND LR.user_id='".$this->_getData['user_id']."'";
		   }
		   if(!empty($this->_getData['department_id'])){
				$filter .= " AND DP.department_id='".$this->_getData['department_id']."'";
		   } 
		   if(!empty($this->_getData['designation_id'])){
				$filter .= " AND DG.designation_id='".$this->_getData['designation_id']."'";
		   }
		   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
				$filter .= " AND date(LR.request_date) BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		}

           $select = $this->_db->select()
                                   ->from(array('LR'=>'leaverequests'),array('*'))
                                   ->joininner(array('ED'=>'employee_personaldetail'),"LR.user_id=ED.user_id",array('employee_code','first_name','last_name'))
                                   ->joininner(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))
                                   ->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
                                   ->where("1".$filter);
        // echo $select->__toString();die;

	   $result =  $this->getAdapter()->fetchAll($select);
	   $Header .= "\"Employee Name \"".$_nxtcol.
                        "\"Employee Code\"".$_nxtcol.
                        "\"Leave From \"".$_nxtcol.
                        "\"Leave To \"".$_nxtcol.
						"\"Request Date \"".$_nxtcol.
                        "\"Leave Days\"".$_nxtcol.
                        "\"SL \"".$_nxtcol.
                        "\"CL \"".$_nxtcol.
                        "\"PL \"".$_nxtrow.$_nxtrow;
			$tatalexpense = 0;
			$totalfare = 0;
			$totalapprove  =0;
		foreach($result as $record){
		    $Header .= "\"" . str_replace( "\"", "\"\"",$record['first_name'].' '.$record['last_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['employee_code']) . "\"" . $_nxtcol;
            $Header .= "\"" . str_replace( "\"", "\"\"",$record['leave_from']) . "\"" . $_nxtcol;
            $Header .= "\"" . str_replace( "\"", "\"\"",$record['leave_to']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['request_date']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['total_days']) . "\"" . $_nxtcol;
			/*$Header .= "\"" . str_replace( "\"", "\"\"",$record['approve_amount']) . "\"" . $_nxtcol;
                        $Header .= "\"" . str_replace( "\"", "\"\"",'') . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$record['expense_date']) . "\"" . $_nxtcol;
			$tatalexpense = $tatalexpense + $record['expense_amount'];
			$totalfare = $totalfare + $record['fare'];
			$totalapprove = $totalapprove + $record['approve_amount'];*/
			$Header .="\n";
		}
//		$Header .= "\n\t\t\t\t";
//		$Header .= "\"" . str_replace( "\"", "\"\"",'Total') . "\"" . $_nxtcol;
//		$Header .= "\"" . str_replace( "\"", "\"\"",$tatalexpense) . "\"" . $_nxtcol;
//		$Header .= "\"" . str_replace( "\"", "\"\"",$totalfare) . "\"" . $_nxtcol;
//		$Header .= "\"" . str_replace( "\"", "\"\"",$totalapprove) . "\"" . $_nxtcol;

	 	header("Content-type: application/xls");
                header("Content-Disposition: attachment; filename=LeaveList.xls");
                header("Pragma: no-cache");
                header("Expires: 0");
                echo $Header;
                exit();
        }
	public function getDesigAndHQ(){
	      $select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array('user_id','CONCAT(employee_code,"-",first_name," ",last_name," (",DT.designation_code,")") AS name'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
								->where("ED.delete_status='0' AND ED.designation_id=6")
								->order("ED.first_name ASC");
			$rbmlist =  $this->getAdapter()->fetchAll($select);
			
			$select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array('user_id','CONCAT(employee_code,"-",first_name," ",last_name," (",DT.designation_code,")") AS name'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
								->where("ED.delete_status='0' AND ED.designation_id=7")
								->order("ED.first_name ASC");
			$abmlist =  $this->getAdapter()->fetchAll($select);
			
			$select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array('user_id','CONCAT(employee_code,"-",first_name," ",last_name," (",DT.designation_code,")") AS name'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
								->where("ED.delete_status='0' AND ED.designation_id=8")
								->order("ED.first_name ASC");
			$belist =  $this->getAdapter()->fetchAll($select);
			
			$select = $this->_db->select()
							   ->from(array('HQ'=>'headquater'),array('*'))
							   ->order("HQ.headquater_name ASC");
			$headquaterlist =  $this->getAdapter()->fetchAll($select);
			return array('RBM'=>$rbmlist,'ABM'=>$abmlist,'BE'=>$belist,'HQ'=>$headquaterlist);
	}
	
	public function getExpenseReportDetail(){
	      $where = 1;
		  
		  /*if($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']!='self'){
		     	if(!empty($childUser)){
		     		$where .= " AND EE.user_id IN ('".implode(',',$childUser )."') OR EE.user_id='".$_SESSION['AdminLoginID']."'";
		  		}else{
		  			$where .= " AND EE.user_id='".$_SESSION['AdminLoginID']."'";
		  		}	
		  }elseif($_SESSION['AdminLoginID']!=1 && $this->_getData['Mode']=='self'){
		     $where .= " AND EE.user_id='".$_SESSION['AdminLoginID']."'";
		  }*/
		  $filter = '';
		   if(!empty($this->_getData['user_id'])){
				$filter .= " AND ED.user_id='".$this->_getData['user_id']."'";
				
		   }
		   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
				$filter .= " AND EE.expense_date BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
		   }
		   if(!empty($this->_getData['month'])){
				$filter .= " AND DATE_FORMAT(EE.expense_date,'%Y-%m')= '".$this->_getData['month']."'";
		   }
		
		    $orderlimit = CommonFunction::OdrderByAndLimit($this->_getData,'EE.expense_date');
			
			$select = $this->_db->select()
							   ->from(array('EE'=>'emp_expenses'),array('COUNT(1) AS CNT'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array(''))
								->where($where.$filter);
			$total =  $this->getAdapter()->fetchRow($select);
			$select = $this->_db->select()
							   ->from(array('EE'=>'emp_expenses'),array('*'))
							   ->joinleft(array('EH'=>'expense_head'),"EE.head_id=EH.head_id",array('head_name'))
							    ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id AND ED.delete_status='0'",array('employee_code','first_name','last_name'))
							   
								->joininner(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
								->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(''))
								->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
								->where($where.$filter)
								->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
								->limit($orderlimit['Toshow'],$orderlimit['Offset']);
								//echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
		return array('Total'=>$total['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']); 				
	 }	
}

?>