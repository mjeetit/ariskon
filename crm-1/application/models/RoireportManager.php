<?php
class RoireportManager extends Zend_Custom
{
	/**
	 * Doctor which ROI has been filled
	 * Function : getROIs()
	 * Function to get all doctor roi report which crm approved and roi data has been filled
	 **/
	public function getROIs($data) {
		try {
			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
			
			$where 		 = 1;
			$filterparam = '';
			if ($_SESSION['AdminLevelID'] != 1) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND AT.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With CRM Activity (Expense) Type
			if(!empty($data['activitytoken'])){
				$filterparam .= " AND AT.expense_type='".Class_Encryption::decode($data['activitytoken'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(AT.created_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'AT.created_date');
			
			$countQuery = $this->_db->select()
							->from(array('AT'=>'crm_appointments'),array('COUNT(1) AS CNT'))
							->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",array())
							->where($where.$filterparam)
							->where("AT.business_audit_status='1'")
							->where("AT.isActive='1'")
							->where("AT.isDelete='0'")
							->group('AT.doctor_id')
							->group('AT.doctor_id')
							->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
				
			$query = $this->_db->select()
					 ->from(array('AT'=>'crm_appointments'),array('AT.doctor_id','AT.abm_id','AT.rbm_id','AT.zbm_id','expenseCost'=>'SUM(AT.expense_cost)','crmAmount'=>'SUM(AT.total_value)','roiAmount'=>'getRoiAmount(AT.doctor_id)'))
					 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",array('DT.doctor_name'))
					 ->where($where.$filterparam)
					 ->where("AT.business_audit_status='1'")
					 ->where("AT.isActive='1'")
					 ->where("AT.isDelete='0'")
					 ->group('AT.doctor_id')
					 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
					 ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //echo $query->__toString();die;
			$result = $this->getAdapter()->fetchAll($query);
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	/**
	 * Export Shipment History
	 * Function : ExportHistory()
	 * Function Export The shipment History
	 **/
	public function ExportHistory($data){
	  	try{
			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
			
			$where 		 = 1;
			$filterparam = '';
			if ($_SESSION['AdminLevelID'] != 1) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}	
					
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}			
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND DT.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With CRM Activity (Expense) Type
			if(!empty($data['activitytoken'])){
				$filterparam .= " AND AT.expense_type='".Class_Encryption::decode($data['activitytoken'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(RT.roi_month) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			$query = "SELECT  
						  Zone,Region,HQ,Doctor,
						  sums.May, sums.June, sums.July,
						  sums.May + sums.June + sums.July AS ROISum
						FROM ( 
							  SELECT                                
								ZT.zone_name AS Zone,RG.region_name AS Region,HT.headquater_name AS HQ,DT.doctor_name AS Doctor,
								SUM(IF(RT.roi_month='2014-05-01',RT.roi_total_amount,0)) AS 'May', 
								SUM(IF(RT.roi_month='2014-06-01',RT.roi_total_amount,0)) AS 'June',
								SUM(IF(RT.roi_month='2014-07-01',RT.roi_total_amount,0)) AS 'July'
							  FROM crm_roi AS RT
							  INNER JOIN crm_appointments AS AT ON AT.doctor_id=RT.doctor_id
							  INNER JOIN crm_doctors AS DT ON DT.doctor_id=RT.doctor_id
							  INNER JOIN headquater AS HT ON HT.headquater_id=DT.headquater_id
							  INNER JOIN region AS RG ON RG.region_id=DT.region_id
							  INNER JOIN zone AS ZT ON ZT.zone_id=DT.zone_id
							  WHERE ".$where.$filterparam."
							  GROUP BY RT.doctor_id WITH ROLLUP 
							 ) AS sums"; //echo $query;die;
						
	   		$exportData = $this->getAdapter()->fetchAll($query); //print_r($exportData[0]);die;
	   		$totalRowData = count($exportData);
			if($totalRowData>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;
				
				$objPHPExcel->setActiveSheetIndex(0);
				
				// Report Sheet Header
				$reportHeader = array();
				foreach($exportData[0] as $headerKey=>$rowHeader)
				{
					$reportHeader[] = $headerKey;
				}//print_r($reportHeader);die;				
				$objPHPExcel->getActiveSheet()->fromArray($reportHeader, NULL, 'A1');
				
				// Report Sheet Row Data
				$reportData  = array();
				$zoneArray   = array();
				$regionArray = array();
				$hqArray     = array();
				foreach($exportData as $index=>$rowData)
				{
					$j=0;
					$reportData[$index]  = array();
					foreach($rowData as $key=>$rowValue)
					{
						$columnData = ($j>3) ? number_format($rowValue,2) : utf8_decode($rowValue);
						$columnData = ($key=='Zone')   ? (!in_array($rowValue,array_filter($zoneArray))   ? $columnData : '') : $columnData;
						$columnData = ($key=='Region') ? (!in_array($rowValue,array_filter($regionArray)) ? $columnData : '') : $columnData;
						$columnData = ($key=='HQ')     ? (!in_array($rowValue,array_filter($hqArray))     ? $columnData : '') : $columnData;
						$columnData = ($totalRowData==($index+1)) ? ($j>3 ? $columnData : '') : $columnData;
						$columnData = ($totalRowData==($index+1)) ? ($j==0 ? 'Grand Total' : $columnData) : $columnData;
						
						$reportData[$index][] = $columnData;
						$j++;
					}
					
					$zoneArray[]   = (!in_array($rowData['Zone'],$zoneArray))     ? $rowData['Zone']   : '';
					$regionArray[] = (!in_array($rowData['Region'],$regionArray)) ? $rowData['Region'] : '';
					$hqArray[]     = (!in_array($rowData['HQ'],$hqArray))         ? $rowData['HQ']     : '';					
				} //echo "<pre>";print_r($reportData);echo "</pre>";die;
				
				$objPHPExcel->getActiveSheet()->fromArray($reportData, NULL, 'A2');
				
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle("ROI Report");
				
				// Redirect output to a client’s web browser (Excel5)
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
			else {
				$Header .= 	"\" No Data Found!! \"".$_nxtcol;
			}
	  	}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
	  	}		  
	}
	
	public function ExportHistoryWithoutPHPExcelLibrary($data){
	  	try{
			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
			
			$where 		 = 1;
			$filterparam = '';
			if ($_SESSION['AdminLevelID'] != 1) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}	
					
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}			
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND DT.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With CRM Activity (Expense) Type
			if(!empty($data['activitytoken'])){
				$filterparam .= " AND AT.expense_type='".Class_Encryption::decode($data['activitytoken'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(RT.roi_month) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			$query = "SELECT  
						  Zone,Region,HQ,Doctor,
						  sums.May, sums.June, sums.July,
						  sums.May + sums.June + sums.July AS ROISum
						FROM ( 
							  SELECT                                
								ZT.zone_name AS Zone,RG.region_name AS Region,HT.headquater_name AS HQ,DT.doctor_name AS Doctor,
								SUM(IF(RT.roi_month='2014-05-01',RT.roi_total_amount,0)) AS 'May', 
								SUM(IF(RT.roi_month='2014-06-01',RT.roi_total_amount,0)) AS 'June',
								SUM(IF(RT.roi_month='2014-07-01',RT.roi_total_amount,0)) AS 'July'
							  FROM crm_roi AS RT
							  INNER JOIN crm_appointments AS AT ON AT.doctor_id=RT.doctor_id
							  INNER JOIN crm_doctors AS DT ON DT.doctor_id=RT.doctor_id
							  INNER JOIN headquater AS HT ON HT.headquater_id=DT.headquater_id
							  INNER JOIN region AS RG ON RG.region_id=DT.region_id
							  INNER JOIN zone AS ZT ON ZT.zone_id=DT.zone_id
							  WHERE ".$where.$filterparam."
							  GROUP BY RT.doctor_id WITH ROLLUP 
							 ) AS sums"; //echo $query;die;
						
	   		$exportData = $this->getAdapter()->fetchAll($query); //print_r($RecordExport[0]);die;
	   		$_nxtcol = "\t";
	   		$_nxtrow = "\n";
			$Header .= $_nxtrow.$_nxtrow;
	   	
			$totalRowData = count($exportData);
			if($totalRowData>0) {
				// Report Sheet Header
				foreach($exportData[0] as $headerKey=>$rowHeader)
				{
					$Header .= 	"\"".$headerKey." \"".$_nxtcol;
				}
				$Header .= $_nxtrow.$_nxtrow;
				
				// Report Sheet Row Data
				$zoneArray   = array();
				$regionArray = array();
				$hqArray     = array();
				foreach($exportData as $index=>$rowData)
				{
					$j=0;
					foreach($rowData as $key=>$rowValue)
					{
						$columnData = ($j>3) ? number_format($rowValue,2) : utf8_decode($rowValue);
						$columnData = ($key=='Zone')   ? (!in_array($rowValue,array_filter($zoneArray))   ? $columnData : '') : $columnData;
						$columnData = ($key=='Region') ? (!in_array($rowValue,array_filter($regionArray)) ? $columnData : '') : $columnData;
						$columnData = ($key=='HQ')     ? (!in_array($rowValue,array_filter($hqArray))     ? $columnData : '') : $columnData;
						$columnData = ($totalRowData==($index+1)) ? ($j>3 ? $columnData : '') : $columnData;
						$columnData = ($totalRowData==($index+1)) ? ($j==0 ? 'Grand Total' : $columnData) : $columnData;
						
						$Header .= 	"\"" . str_replace("\"", "\"\"", $columnData). "\"" . $_nxtcol;
						$j++;
					}
					$Header .= $_nxtrow;
					
					$zoneArray[]   = (!in_array($rowData['Zone'],$zoneArray))     ? $rowData['Zone']   : '';
					$regionArray[] = (!in_array($rowData['Region'],$regionArray)) ? $rowData['Region'] : '';
					$hqArray[]     = (!in_array($rowData['HQ'],$hqArray))         ? $rowData['HQ']     : '';					
				}
			}
			else {
				$Header .= 	"\" No Data Found!! \"".$_nxtcol;
			}
					
			CommonFunction::ExportCsv($Header,'ROI_Report','xls');
	  	}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
	  	}		  
	}
	
	/**
	 * Doctor which ROI has been filled
	 * Function : getRoiReportDetail()
	 * Function to get all doctor roi report which crm approved and roi data has been filled
	 **/
	public function getRoiReportDetail($data){
	  	try{
			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));
			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));
			
			$where 		 = 1;
			$filterparam = '';
			if ($_SESSION['AdminLevelID'] != 1) {
				$this->getHeadquarters($_SESSION['AdminLoginID']);
				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}	
					
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadquarters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}			
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DT.headquater_id='".(int) Class_Encryption::decode($data['token2'])."'";
			}			
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND DT.doctor_id='".(int) Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Specific Doctor Data which had requested from report lists
			if(!empty($data['token'])){
				$filterparam .= " AND DT.doctor_id='".(int) Class_Encryption::decode($data['token'])."'";
			}
			//Filter With CRM Activity (Expense) Type
			if(!empty($data['activitytoken'])){
				$filterparam .= " AND AT.expense_type='".(int) Class_Encryption::decode($data['activitytoken'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(RT.roi_month) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			$query = "SELECT  
						  Zone,Region,HQ,Doctor,
						  sums.May, sums.June, sums.July,
						  sums.May + sums.June + sums.July AS ROI_Sum
						FROM ( 
							  SELECT                                
								ZT.zone_name AS Zone,RG.region_name AS Region,HT.headquater_name AS HQ,DT.doctor_name AS Doctor,
								SUM(IF(RT.roi_month='2014-05-01',RT.roi_total_amount,0)) AS 'May', 
								SUM(IF(RT.roi_month='2014-06-01',RT.roi_total_amount,0)) AS 'June',
								SUM(IF(RT.roi_month='2014-07-01',RT.roi_total_amount,0)) AS 'July'
							  FROM crm_roi AS RT
							  INNER JOIN crm_appointments AS AT ON AT.doctor_id=RT.doctor_id
							  INNER JOIN crm_doctors AS DT ON DT.doctor_id=RT.doctor_id
							  INNER JOIN headquater AS HT ON HT.headquater_id=DT.headquater_id
							  INNER JOIN region AS RG ON RG.region_id=DT.region_id
							  INNER JOIN zone AS ZT ON ZT.zone_id=DT.zone_id
							  WHERE ".$where.$filterparam."
							  GROUP BY RT.doctor_id WITH ROLLUP 
							 ) AS sums"; //echo $query;die;
						
	   		return $this->getAdapter()->fetchAll($query); //print_r($RecordExport[0]);die;
	  	}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
	  	}		  
	}
	
	/**
	 * CRM Expense (Activity) Type Lists
	 * Function : getExpenseLists()
	 * Function to get all CRM Expense (Activity) Type Lists
	 **/
	public function getExpenseLists($data=array()) {
		$query = $this->_db->select()->from('crm_expense_types','*')->where("isActive='1'")->order('type_name','ASC'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
}
?>