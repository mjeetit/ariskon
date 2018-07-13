<?php
class NoncallreportingManager extends Zend_Custom
{
  	private $_headquarters 	= array();
		
	public function getMeeting($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			
			$filterparam = '';
			//Filter With Meeting Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.meetingtype_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EE.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.meeting_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
				
			if(!empty($data['year']) && !empty($data['month']) && empty($data['from_date']) && empty($data['to_date'])) {
				$filterparam .= " AND DATE_FORMAT(EE.meeting_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.meeting_date');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_meeting'),array('COUNT(1) AS CNT'))
							->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_meeting'),array("CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.meeting_date,'%Y-%m') AS visit_month",'*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->joinleft(array('MT'=>'app_meetingtype'),"MT.type_id=EE.meetingtype_id",array('type_name'))
							   ->where($where.$filterparam)
							   ->order('meeting_date DESC')
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getMeetingOld($data)
	{
	     $where = '1';
		  if($_SESSION['AdminDesignation']==8){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminDesignation']==7){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }elseif($_SESSION['AdminDesignation']==6){
		     $childs =  $this->getChilllds("ED");
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
		  }elseif($_SESSION['AdminDesignation']==5){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }
	    $select = $this->_db->select()
							   ->from(array('EE'=>'app_meeting'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.meeting_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where($where)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.meeting_date,'%Y-%m')")
							   ->order("EE.meeting_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;	
	}
	
	public function getOtherActivity($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			
			$filterparam = '';
			//Filter With Activity Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.meetingtype_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EL.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.activity_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
				
			if(!empty($data['year']) && !empty($data['month'])) {
				$filterparam .= " AND DATE_FORMAT(EE.activity_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.activity_date');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_noncallreport'),array('(COUNT(1)/COUNT(DISTINCT(EE.activity_date))) AS Call_Avg'))
							->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam)
						    ->group("EE.user_id")
						    ->group("DATE_FORMAT(EE.activity_date,'%Y-%m')".$having); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_noncallreport'),array("CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.activity_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.activity_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.activity_date))) AS Call_Avg"))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->where($where.$filterparam)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.activity_date,'%Y-%m')".$having)
							   //->having("Call_Avg BETWEEN '0' AND '10'")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getOtherActivityOld()
	{
	   $where = '1';
		  if($_SESSION['AdminDesignation']==8){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminDesignation']==7){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }elseif($_SESSION['AdminDesignation']==6){
		     $childs =  $this->getChilllds("ED");
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
		  }elseif($_SESSION['AdminDesignation']==5){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }
	    $select = $this->_db->select()
							   ->from(array('EE'=>'app_noncallreport'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.activity_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where($where)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.activity_date,'%Y-%m')")
							   ->order("EE.activity_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;	
	}
	
	public function getHeadQuaters($loggedIn)
	{
		$userLocation = $this->getUserHeadquarter($loggedIn);
		$this->_userIDs[] 	   = $userLocation['user_id'];
		$this->_headquarters[] = $userLocation['headquater_id'];
		
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))
				 ->joininner(array('ELT'=>'emp_locations'),"ELT.user_id=EPT.user_id",array('ELT.headquater_id'))
				 ->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		$results = $this->getAdapter()->fetchAll($query);
		
		if (count($results) > 0) {
			foreach($results as $key=>$child) {
				$countChild = $this->countChild($child['user_id']);
				if($countChild['CNT'] > 0) {
					$this->_userIDs[] 	   = $child['user_id'];
					$this->_headquarters[] = $child['headquater_id'];
					$this->getHeadQuaters($child['user_id']);
				}
				else {
					$this->_userIDs[] 	   = $child['user_id'];
					$this->_headquarters[] = $child['headquater_id'];
				}
			}
		}
		$this->_headquarters = array_filter($this->_headquarters);
	}
	
	public function countChild($loggedIn)
	{
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('CNT'=>'count(1)'))
				 ->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
	}
	
	public function getUserHeadquarter($userID)
	{
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))
				 ->joininner(array('ELT'=>'emp_locations'),"ELT.user_id=EPT.user_id",array('ELT.headquater_id'))
				 ->where("EPT.user_id =".$userID." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
	}
	// //export data added 28 may 2016
	public function Exportmeetings($headers=array(),$filterData=array())
	{
		try{
			$totalRowData = $filterData['Records'];
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Write Sheet Header
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A1');
				
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);
				unset($styleArray);
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				//echo '<pre>';print_r($totalRowData); die;
				foreach($totalRowData as $index=>$row)
				{
					$reportRows[] = array($row['Emp'],$row['employee_code'],$row['designation_name'],$row['headquater_name'],$row['type_name'],$row['metting_detail'],date('M-Y-d',strtotime($row['meeting_date'])),date('H:i:s',strtotime($row['meetingtime_start'])),date('H:i:s',strtotime($row['meetingtime_end'])));					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Summary Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="meeting_details.xls"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				ob_end_clean();
				$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
				//$objWriter->save('test.xlsx');  //THIS WORKS
				$objPHPExcel->disconnectWorksheets();
				unset($objPHPExcel);die;
			}
			else {
				$Header .= 	"\" No Data Found!! \"".$_nxtcol;
			}
		}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}		  
	}
	public function getMeetingExportdata($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			
			$filterparam = '';
			//Filter With Meeting Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.meetingtype_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EE.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.meeting_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
				
			if(!empty($data['year']) && !empty($data['month']) && empty($data['from_date']) && empty($data['to_date'])) {
				$filterparam .= " AND DATE_FORMAT(EE.meeting_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.meeting_date');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_meeting'),array('COUNT(1) AS CNT'))
							->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_meeting'),array("CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.meeting_date,'%Y-%m') AS visit_month",'*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->joinleft(array('MT'=>'app_meetingtype'),"MT.type_id=EE.meetingtype_id",array('type_name'))
							   ->where($where.$filterparam)
							   ->order('meeting_date DESC'); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
}
?>