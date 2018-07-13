<?php
class AttandanceManager extends Zend_Custom
{
  public $_AttandanceData = array();
  public $_getData = array();

  public function AttamdanceList($attandance_id=false){
	   if($attandance_id){
	     $where = " AND attandance_id='".$attandance_id."'";
	   }
       $select = $this->_db->select()
		 				->from(array('AT'=>'attandance'),array('*'))
						->joinleft(array('ED'=>'employee_personaldetail'),"ED.employee_code=AT.employee_code",array())
						->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_name'))
						->joinleft(array('DP'=>'department'),"DP.department_id=ED.department_id",array('department_name'))						
						->where('1'.$where);
						//echo $select->__toString();die;
	  $result = $this->getAdapter()->fetchAll($select);
	  return $result;
  }
  public function UploadeAttandance(){
        ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		$filename 	= CommonFunction::UploadFile('AttandanceSheet','xls');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		for ($i=1; $i<$k; $i+=1) {
			$j = 1;
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			unset($this->_getData);
		    $this->_getData['employee_code']  = $this->getCell($data,$i,$j++);
			$this->_getData['month']    = $this->getCell($data,$i,$j++);
			$this->_getData['year']     = $this->getCell($data,$i,$j++);
			$this->_getData['day1']     = $this->getCell($data,$i,$j++);
			$this->_getData['day2']     = $this->getCell($data,$i,$j++);
			$this->_getData['day3']     = $this->getCell($data,$i,$j++);
			$this->_getData['day4']     = $this->getCell($data,$i,$j++);
			$this->_getData['day5']     = $this->getCell($data,$i,$j++);
			$this->_getData['day6']     = $this->getCell($data,$i,$j++);
			$this->_getData['day7']     = $this->getCell($data,$i,$j++);
			$this->_getData['day8']     = $this->getCell($data,$i,$j++);
			$this->_getData['day9']     = $this->getCell($data,$i,$j++);
			$this->_getData['day10']     = $this->getCell($data,$i,$j++);
			$this->_getData['day11']     = $this->getCell($data,$i,$j++);
			$this->_getData['day12']     = $this->getCell($data,$i,$j++);
			$this->_getData['day13']     = $this->getCell($data,$i,$j++);
			$this->_getData['day14']     = $this->getCell($data,$i,$j++);
			$this->_getData['day15']     = $this->getCell($data,$i,$j++);
			$this->_getData['day16']     = $this->getCell($data,$i,$j++);
			$this->_getData['day17']     = $this->getCell($data,$i,$j++);
			$this->_getData['day18']     = $this->getCell($data,$i,$j++);
			$this->_getData['day19']     = $this->getCell($data,$i,$j++);
			$this->_getData['day20']     = $this->getCell($data,$i,$j++);
			$this->_getData['day21']     = $this->getCell($data,$i,$j++);
			$this->_getData['day22']     = $this->getCell($data,$i,$j++);
			$this->_getData['day23']     = $this->getCell($data,$i,$j++);
			$this->_getData['day24']     = $this->getCell($data,$i,$j++);
			$this->_getData['day25']     = $this->getCell($data,$i,$j++);
			$this->_getData['day26']     = $this->getCell($data,$i,$j++);
			$this->_getData['day27']     = $this->getCell($data,$i,$j++);
			$this->_getData['day28']     = $this->getCell($data,$i,$j++);
			$this->_getData['day29']     = $this->getCell($data,$i,$j++);
			$this->_getData['day30']     = $this->getCell($data,$i,$j++);
			$this->_getData['day31']     = $this->getCell($data,$i,$j++);
                        $this->_getData['total_salary_days']     = $this->getCell($data,$i,$j++);
			$this->calculateNoOfDays();
			$totalcount = @array_count_values($this->_getData);
                        $halflwppresebt = 0;
                        $halflwpabsent = 0;
                        $halflpresebt = 0;
                        $halflabsent = 0;
                         if($totalcount['HL']>0){
                              $halflwppresebt = $totalcount['HL']/2;
                              $halflwpabsent = $totalcount['HL']/2;
                           }
                       if($totalcount['H']>0){
                          $halflpresebt = $totalcount['H']/2;
                          $halflabsent = $totalcount['H']/2;
                       }
		     $this->_getData['total_present']    = $totalcount['P']+ $halflwpabsent + $halflpresebt + $totalcount['L'];
                     $this->_getData['total_absent']     = $totalcount['A'] + $halflwppresebt;
                     $this->_getData['total_leave']      = $totalcount['L'] + $halflabsent;//print_r($this->_getData);die;
                 if($this->_getData['total_present']>0 || $this->_getData['total_absent']>0 || $this->_getData['total_leave']>0){
		    $this->AddAttandance(array($this->_getData));
                }
		}//print_r($finalArr);die;
    }
	public function getCell(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}
   public function AddAttandance($insertdata){
      $this->insertInToTable('attandance', $insertdata);
	  $_SESSION[SUCCESS_MSG] = 'Attandance Uploaded Successfully';
   }
   public function ManualAttandance(){
      $this->calculateNoOfDays();
      $totalcount = @array_count_values($this->_getData);
       $halflwppresebt = 0;
        $halflwpabsent = 0;
        $halflpresebt = 0;
        $halflabsent = 0;
        if($totalcount['HL']>0){
          $halflwppresebt = $totalcount['HL']/2;
          $halflwpabsent = $totalcount['HL']/2;
       }
       if($totalcount['H']>0){
          $halflpresebt = $totalcount['H']/2;
          $halflabsent = $totalcount['H']/2;
       }
	  $this->_getData['total_present']    = $totalcount['P']+ $halflwpabsent + $halflpresebt + $totalcount['L'];
	  $this->_getData['total_absent']     = $totalcount['A'] + $halflwppresebt;
	  $this->_getData['total_leave']      = $totalcount['L'] + $halflabsent;
          //$this->_getData['total_salary_days'] = ;
          //$this->_getData['total_leave']      = $totalcount['L'];
          if($this->_getData['total_present']>0 || $this->_getData['total_absent']>0 || $this->_getData['total_leave']>0){
	     $this->insertInToTable('attandance', array($this->_getData));
             $_SESSION[SUCCESS_MSG] = 'Attandance Saved Successfully';
         }
	  
   }
   public function UpdateAttandance(){
      $totalcount = @array_count_values($this->_getData);
       if($totalcount['HL']>0){
          $halflwppresebt = $totalcount['HL']/2;
          $halflwpabsent = $totalcount['HL']/2;
       }
       if($totalcount['H']>0){
          $halflpresebt = $totalcount['H']/2;
          $halflabsent = $totalcount['H']/2;
       }
	  $this->_getData['total_present']    = $totalcount['P']+ $halflwpabsent + $halflpresebt+ $totalcount['L'];
	  $this->_getData['total_absent']     = $totalcount['A'] + $halflwppresebt;
	  $this->_getData['total_leave']      = $totalcount['L'] + $halflabsent;
	  $this->updateTable('attandance',$this->_getData,array('attandance_id'=>$this->_getData['attandance_id']));
	  $_SESSION[SUCCESS_MSG] = 'Attandance Updated Successfully';
  }
  public function calculateNoOfDays(){
     $days = @cal_days_in_month(CAL_GREGORIAN,$this->_getData['month'],$this->_getData['year']);
	 if($days==30){
	    unset($this->_getData['day31']);
	 }elseif($days==29){
	    unset($this->_getData['day30']);
		unset($this->_getData['day31']);
	 }elseif($days==28){
	    unset($this->_getData['day29']);
	    unset($this->_getData['day30']);
		unset($this->_getData['day31']);
	 }
  } 
   public function deleteAttandance(){
      $this->_db->delete('attandance',"attandance_id='".$this->_getData['attandance_id']."'");
	  $_SESSION[SUCCESS_MSG] = 'Attandnce Deleted Successfully!';
   } 
   
   public function updateSalaryAmount(){
       ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		$filename 	= CommonFunction::UploadFile('AttandanceSheet','xls');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		for ($i=1; $i<$k; $i+=1) {
			$j = 1;
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			unset($this->_getData);
		    $this->_getData['employee_code']  = $this->getCell($data,$i,$j++);
			$this->_getData['head_amount']['7']    = round($this->getCell($data,$i,$j++));
			$this->_getData['head_amount']['1']     = round($this->getCell($data,$i,$j++));
			$this->_getData['head_amount']['4']     = round($this->getCell($data,$i,$j++));
			$this->_getData['head_amount']['5']     = round($this->getCell($data,$i,$j++));
			$this->_getData['head_amount']['6']     = round($this->getCell($data,$i,$j++));
			$this->_getData['head_amount']['8']     = round($this->getCell($data,$i,$j++));
			$this->_getData['head_amount']['9']     = round($this->getCell($data,$i,$j++));
			
			//echo "<pre>";print_r($this->_getData);die;
			$salarydata[] = $this->_getData;
		} 
		foreach($salarydata as $salarydat){
		   foreach($salarydat['head_amount'] as $key=>$head_amounts){
		        $select = $this->_db->select()
		 				->from(array('ESH'=>'employee_salary_amount'),array('*'))
						->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=ESH.user_id",array())					
						->where("ED.employee_code='".$salarydat['employee_code']."' AND ESH.salaryhead_id='".$key."'");
						//echo $select->__toString();die;
	  			$result = $this->getAdapter()->fetchRow($select);
				if((round($result['amount'])-$head_amounts)<>0){
				  $this->_db->update('employee_salary_amount',array('amount'=>$head_amounts),"id='".$result['id']."' AND user_id='".$result['user_id']."' AND salaryhead_id='".$result['salaryhead_id']."'");
				  echo round($result['amount'])."=".$head_amounts."=".$result['user_id']."=".$result['salaryhead_id']."<br>";
				}
		   }
		}
		 echo "<pre>";print_r($salarydata);die;
   }
   
   
    public function update_test(){
       ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		$filename 	= CommonFunction::UploadFile('AttandanceSheet','xls');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		for ($i=1; $i<$k; $i+=1) {
			$j = 1;
		/*	if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}*/
			unset($this->_getData);
		    $this->_getData['date']  = date("Y-m-d h:m:s",($this->getCell($data,$i,$j++)- 25569) * 43200);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$this->_getData['status']      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$error_desc1      = $this->getCell($data,$i,$j++);
			$this->_getData['reference']    = $this->getCell($data,$i,$j++);print_r($this->_getData);die;
			/*$this->_getData['rec_name']  =  $this->_getData['error_numeric'].'.'.$this->_getData['error_alpha'];
			$this->_getData['error_alpha']    = $this->_getData['error_numeric'];
			$this->_getData['error_desc']      = $this->getCell($data,$i,$j++);*/
		
			
			//echo "<pre>";print_r($this->_getData);die;
			$salarydata[] = $this->_getData;
		} 
		   foreach($salarydata as $key=>$head_amounts){
		    if($head_amounts['status']=='Order deliver done'){
		      $this->_db->insert('error_code',array_filter(array('date'=>$head_amounts['date'],'reference'=>$head_amounts['reference'])));
		   }
		}
		 echo "<pre>";print_r($salarydata);die;
   }
   
   public function getemailpass(){
       ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		$filename 	= CommonFunction::UploadFile('AttandanceSheet','xls');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		for ($i=1; $i<$k; $i+=1) {
			$j = 1;
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			unset($this->_getData);
		    $this->_getData['employee_code']  = $this->getCell($data,$i,$j++);
			$this->_getData['month']    = $this->getCell($data,$i,$j++);
			$this->_getData['year']     = $this->getCell($data,$i,$j++);
			$this->_getData['day1']     = $this->getCell($data,$i,$j++);
			$this->_getData['email']     = $this->getCell($data,$i,$j++);
			$this->_getData['email_password']     = $this->getCell($data,$i,$j++);
			
			$emaildata[] = $this->_getData;
		}
		//echo "<pre>";print_r($emaildata);die;
		foreach($emaildata as $emaildat){
		  $this->_db->update('employee_personaldetail',array('email'=>trim($emaildat['email']),'email_password'=>trim($emaildat['email_password'])),"employee_code='".trim($emaildat['employee_code'])."'");
		  echo $emaildat['employee_code']."<br>";
		}
   }	
}
?>