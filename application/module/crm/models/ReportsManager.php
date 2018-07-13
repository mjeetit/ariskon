<?php

class Crm_Model_ReportsManager extends Zend_Custom

{

	/**

	 * Doctor which ROI has been filled

	 * Function : getROIs()

	 * Function to get all doctor roi report which crm approved and roi data has been filled

	 **/

	public function getROIs($data)

	{

		try {

			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));

			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));

			

			$where 		 = 1;

			$filterparam = '';

			if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			//Filter With RBM Data

			if(!empty($data['token5'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			//Filter With ABM Data

			if(!empty($data['token4'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			//Filter With BE Data

			if(!empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));

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

			$_SESSION[ERROR_MSG] =  'There is some error, please try again. Error Code: '.__LINE__; 

		}

	}

	

	/**

	 * Export Shipment History

	 * Function : ExportHistory()

	 * Function Export The shipment History

	 **/

	public function ExportHistory($data)

	{

	  	try{

			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));

			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));

			

			$where 		 = 1;

			$filterparam = '';

			if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}	

					

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With RBM Data

			if(!empty($data['token5'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With ABM Data

			if(!empty($data['token4'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With BE Data

			if(!empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));

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

		   $_SESSION[ERROR_MSG] =  'There is some error, please try again. Error Code: '.__LINE__;   

	  	}		  

	}

	

	public function ExportHistoryWithoutPHPExcelLibrary($data)

	{

	  	try{

			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));

			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));

			

			$where 		 = 1;

			$filterparam = '';

			if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}	

					

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With RBM Data

			if(!empty($data['token5'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With ABM Data

			if(!empty($data['token4'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With BE Data

			if(!empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));

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

		   $_SESSION[ERROR_MSG] =  'There is some error, please try again. Error Code: '.__LINE__;  

	  	}		  

	}

	

	/**

	 * Doctor which ROI has been filled

	 * Function : getRoiReportDetail()

	 * Function to get all doctor roi report which crm approved and roi data has been filled

	 **/

	public function getRoiReportDetail($data)

	{

	  	try{

			$previousMonth = date("Y-m-01",mktime(0,0,0,date('m')-1,date('d'),date('Y')));

			$financialYear = date("Y-m-d",mktime(0,0,0,date('m')-12,date('d'),date('Y')));

			

			$where 		 = 1;

			$filterparam = '';

			if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}	

					

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With RBM Data

			if(!empty($data['token5'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With ABM Data

			if(!empty($data['token4'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}			

			//Filter With BE Data

			if(!empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));

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

		   $_SESSION[ERROR_MSG] =  'There is some error, please try again. Error Code: '.__LINE__;   

	  	}		  

	}

	

	/**

	 * CRM Expense (Activity) Type Lists

	 * Function : getExpenseLists()

	 * Function to get all CRM Expense (Activity) Type Lists

	 **/

	public function getExpenseLists($data=array())

	{

		$query = $this->_db->select()->from('crm_expense_types','*')->where("isActive='1'")->order('type_name','ASC'); //echo $query->__toString();die;

		return $this->getAdapter()->fetchAll($query);

	}

	

	/**

	 * CRM Report

	 * Function : getCRM()

	 * Function to get all CRM Lists

	 **/

	public function getCrmReport($data)

	{

		try {

			$where = "EP.delete_status='0'";
			$filterparam = '';
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44 && $_SESSION['AdminDesignation'] != 34){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND EL.headquater_id IN(".$value.")";
		    }
			
			//Newely inserted code
			
			//Filter With Status Data
			if(!empty($data['token7'])){
				$where = '1';
				$decode=Class_Encryption::decode($data['token7']);
				if($decode==3)
					{$filterparam .=" AND AT.business_audit_status='0'";}
				else
					{$filterparam .=" AND AT.business_audit_status='".$decode."'";}
			}
			//End of newly inserted code
			
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.user_id IN ('.Class_Encryption::decode($data['token3']).')';
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

			//Filter With Headquarter Data

			if(!empty($data['token2'])){

				$where = '1';

				$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";

			}

			//Filter With Doctor Data

			if(!empty($data['token1'])){

				$filterparam .= " AND AT.doctor_id='".Class_Encryption::decode($data['token1'])."'";

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

							->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array())

							->joininner(array('ET'=>'crm_expense_types'),"ET.expense_type=AT.expense_type",array())
							
							//---------------Newly inserted code-----------------//
							->joinleft(array('EP'=>'employee_personaldetail'),"EP.user_id=if(AT.requested_by,AT.requested_by,AT.be_id)",'')
							->joininner(array('EL'=>'emp_locations'),"EL.user_id=EP.user_id",'')
							->joinleft(array('DA'=>'app_activities'),"DA
							.activity_id=DT.activity_id",'')
							//---------------End of newly inserted code------------//
							
							->joinleft(array('EPD'=>'employee_personaldetail'),"EPD.user_id=AT.be_id",array())
							->joinleft(array('EPD1'=>'employee_personaldetail'),"EPD1.user_id=AT.abm_id",array())

							->joinleft(array('EPD2'=>'employee_personaldetail'),"EPD2.user_id=AT.rbm_id",array())

							->where($where.$filterparam)

							->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery);

			

			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];

			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {

				$limit = '';

			}

			

			$query = $this->_db->select()

							 ->from(array('AT'=>'crm_appointments'),array('AT.appointment_id','AT.appointment_code','AT.expense_cost','AT.abm_approval','AT.rbm_approval','AT.zbm_approval','AT.business_audit_status','AT.gm_audit_status','AT.be_id','AT.abm_id','AT.rbm_id','AT.zbm_id','AT.created_date','DT.doctor_name','DT.doctor_code','DT.qualification','DT.class','DA.activity_name','PC.patch_name','ET.type_name','HQ.headquater_name','EP.first_name','EP.last_name'))

							->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",'')
							->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array())
							
							 ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DT.patch_id",'')

							 ->joininner(array('ET'=>'crm_expense_types'),"ET.expense_type=AT.expense_type",'')
							//---------------Newly inserted code-----------------//
							->joinleft(array('EP'=>'employee_personaldetail'),"EP.user_id=if(AT.requested_by,AT.requested_by,AT.be_id)",'')
							->joininner(array('EL'=>'emp_locations'),"EL.user_id=EP.user_id",'')
							//---------------End of newly inserted code------------//
							 ->joinleft(array('DA'=>'app_activities'),"DA.activity_id=DT.activity_id",'')
							 ->joinleft(array('EPD'=>'employee_personaldetail'),"EPD.user_id=AT.be_id",array('beCode'=>'EPD.employee_code','beName'=>'EPD.first_name'))

							 ->joinleft(array('EPD1'=>'employee_personaldetail'),"EPD1.user_id=AT.abm_id",array('abmCode'=>'EPD1.employee_code','abmName'=>'EPD1.first_name'))

							 ->joinleft(array('EPD2'=>'employee_personaldetail'),"EPD2.user_id=AT.rbm_id",array('rsmCode'=>'EPD2.employee_code','rsmName'=>'EPD2.first_name'))

							 ->where($where.$filterparam)

							 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])

							 ->limit($limit);
							 //print_r($query->__toString());die;
			$result = $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){
			$_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 
		}

		

		return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);

	}

	

	/**

	 * Export CRM Data

	 * Function : ExportCrmReport()

	 * This function return all CRM data sheet.

	 **/

	public function ExportCrmReport($data=array())

	{

		try{

			$allData = $this->getCrmReport($data);

			$totalRowData = $allData['Records']; //	echo "<pre>";print_r($totalRowData);die;

			if(count($totalRowData)>0) {

				ini_set("memory_limit","512M");

				ini_set("max_execution_time",180);

				ob_end_clean();

				$objPHPExcel = new PHPExcel();

				

				// Filter User Information

				$filterUser = array();//$this->getFilterDetail($data); //echo "<pre>";print_r($filterUser);die;

				$rowPos = (count($filterUser)>0) ? 4 : 1;

				

				// Write Sheet Header

				$headers = array('0'=>'Date','1'=>'CRM No','2'=>'Dr. Name','3'=>'Headquarter','4'=>'Requested By','5'=>'Quality','6'=>'Patch','7'=>'Class of Dr.','8'=>'Activity Type','9'=>'Expense Type','10'=>'Expense Cost','11'=>'ABM Status','12'=>'RBM Status','13'=>'ZBM Status','14'=>'HO Status','15'=>'GM Status');

				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);

				

				$styleArray = array(

							  'borders' => array(

								'allborders' => array(

								  'style' => PHPExcel_Style_Border::BORDER_THIN

								)

							  )

							);

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

				unset($styleArray);

				

				$rowPos = 1;

				if(count($filterUser)>0) {

					// Write User Header

					$objPHPExcel->getActiveSheet()->fromArray(array_keys($filterUser), NULL, 'A1');

					// Set title row bold

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);

					// Setting Column Background Color

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

				

					// Setting Text Alignment Center

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					

					// Write User Detail

					$objPHPExcel->getActiveSheet()->fromArray(array_values($filterUser), NULL, 'A2');

					$rowPos = 4;

				}

				

				// Set title row bold

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);

				

				// Setting Auto Width

				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {

					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

				}

				

				// Setting Column Background Color

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

				

				// Setting Text Alignment Center

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				

				$reportRows = array();

				$visitWith = '';

				$approvalArray = array(0=>'Pending',1=>'Approved',2=>'Rejected');

				foreach($totalRowData as $index=>$row)

				{

					$abmStatus = ($row['abm_id']>0) ? $approvalArray[$row['abm_approval']] : '--NR--';

					$rbmStatus = ($row['rbm_id']>0) ? $approvalArray[$row['rbm_approval']] : '--NR--';

					$zbmStatus = ($row['zbm_id']>0) ? $approvalArray[$row['zbm_approval']] : '--NR--';

					$hoStatus  = $approvalArray[$row['business_audit_status']];

					$GMStatus  = $approvalArray[$row['gm_audit_status']];

					$reportRows[] = array(date('Y-m-d',strtotime($row['created_date'])),$row['appointment_code'],$row['doctor_name'],$row['headquater_name'],$row['first_name']." ".$row['last_name'],$row['qualification'],$row['patch_name'],$row['class'],$row['activity_name'],$row['type_name'],$row['expense_cost'],$abmStatus,$rbmStatus,$zbmStatus,$hoStatus,$GMStatus);

				}

				

				// Write Row Data

				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.($rowPos+1));

					

				// Set autofilter

				// Always include the complete filter range!

				// Excel does support setting only the caption

				// row, but that's not a best practise...

				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column

				$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos);

				

				// Rename sheet

				$objPHPExcel->getActiveSheet()->setTitle('CRM aReport');

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);

									

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/vnd.ms-excel');

				header('Content-Disposition: attachment;filename="crm_report.xls"');

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

		   $_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 

		}		  

	}

	

	/**

	 * ROI Report

	 * Function : getRoiReport()

	 * Function to get all ROI Lists

	 **/

	public function getRoiReport($data)

	{

		try {

			$where 		 = 1;

			$filterparam = '';

			/*if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}*/

			if ($_SESSION['AdminLevelID'] == 1) {

				$where = 1;

			}

			else if ($_SESSION['AdminDesignation'] == 8) {

				//$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = ' AT.be_id='.$_SESSION['AdminLoginID'];//'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			else if ($_SESSION['AdminDesignation'] == 7) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			else if ($_SESSION['AdminDesignation'] == 6) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			else if ($_SESSION['AdminDesignation'] == 5) {

				$this->getHeadQuaters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			//Filter With BE Data

			if(!empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

				//$filterparam .= ' AND AT.be_id='.Class_Encryption::decode($data['token3']);

			}
			//Filter With ABM Data

			if(!empty($data['token4']) && empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

				//$filterparam .= ' AND AT.abm_id='.Class_Encryption::decode($data['token4']);

			}
			//Filter With RBM Data

			if(!empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

				//$filterparam .= ' AND AT.rbm_id='.Class_Encryption::decode($data['token5']);

			}


			//Filter With ZBM Data

			if(!empty($data['token6']) && empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){

				$where = '1';$this->_headquarters = array();

				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));

				$filterparam .= ' AND DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

				//$filterparam .= ' AND AT.zbm_id='.Class_Encryption::decode($data['token6']);

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

			//Filter With Date Range

			if(!empty($data['from_date']) && !empty($data['to_date'])){

				$filterparam .= " AND DATE(RT.roi_month) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";

			}

			

			//Order

			$orderlimit = CommonFunction::OdrderByAndLimit($data,'DT.doctor_name');

			

			$countQuery = $this->_db->select()

							->from(array('RT'=>'crm_roi'),array('COUNT(DISTINCT ROD.roi_id) CNT'))

							->joininner(array('ROD'=>'crm_roi_details'),"ROD.roi_id=RT.roi_id",'')

							 ->joininner(array('PT'=>'crm_products'),"PT.product_id=ROD.product_id",'')

							 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=RT.doctor_id",'')

							 ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DT.headquater_id",'')

							 ->joininner(array('RG'=>'region'),"RG.region_id=HT.region_id",'')

							 ->joininner(array('AR'=>'area'),"AR.area_id=HT.area_id",'')

							 ->joinleft(array('DA'=>'app_activities'),"DA.activity_id=DT.activity_id",'')

							->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery);

			

			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];

			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {

				$limit = '';

			}

			

			$query = $this->_db->select()

							 ->from(array('RT'=>'crm_roi'),array("RG.region_name","AR.area_name","HT.headquater_name","DT.doctor_name","DA.activity_name","GROUP_CONCAT(PT.product_name SEPARATOR ' | ') AS product","GROUP_CONCAT(ROD.unit SEPARATOR ' | ') AS unit","GROUP_CONCAT(FORMAT((ROD.value/ROD.unit),2) SEPARATOR ' | ') AS price", "GROUP_CONCAT(FORMAT(ROD.value,2) SEPARATOR ' | ') AS totalvalue","FORMAT(SUM(ROD.value),2) AS overallvalue","DATE_FORMAT(RT.roi_month,'%M, %Y') AS roimonth","DATE_FORMAT(RT.added_date,'%d %M, %Y') AS roidate",'roi_id'))

							 ->joininner(array('ROD'=>'crm_roi_details'),"ROD.roi_id=RT.roi_id",'')

							 ->joininner(array('PT'=>'crm_products'),"PT.product_id=ROD.product_id",'')

							 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=RT.doctor_id",'')

							 ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DT.headquater_id",'')

							 ->joininner(array('RG'=>'region'),"RG.region_id=HT.region_id",'')

							 ->joininner(array('AR'=>'area'),"AR.area_id=HT.area_id",'')

							 ->joinleft(array('DA'=>'app_activities'),"DA.activity_id=DT.activity_id",'')

							 ->where($where.$filterparam)

							 ->group('RT.roi_id')
							->order("RT.roi_month DESC")
							 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							 

							 ->limit($limit); //print_r($query->__toString());die;

			$result = $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 

		}

		

		return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);

	}

	

	/**

	 * Export ROI Data

	 * Function : ExportRoiReport()

	 * This function return all ROI data sheet.

	 **/

	public function ExportRoiReport($data=array())

	{

		try{

			$allData = $this->getRoiReport($data);

			$totalRowData = $allData['Records']; //echo "<pre>";print_r($totalRowData);die;

			if(count($totalRowData)>0) {

				ini_set("memory_limit","512M");

				ini_set("max_execution_time",180);

				ob_end_clean();

				$objPHPExcel = new PHPExcel();

				

				// Filter User Information

				$filterUser = array();//$this->getFilterDetail($data); //echo "<pre>";print_r($filterUser);die;

				$rowPos = (count($filterUser)>0) ? 4 : 1;

				

				// Write Sheet Header

				$headers = array('0'=>'Region','1'=>'Area','2'=>'Headquarter','3'=>'Doctor','4'=>'Activity','5'=>'Product','6'=>'Unit','7'=>'Unit Price','8'=>'Price','9'=>'Total Price','10'=>'Date');

				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);

				

				$styleArray = array(

							  'borders' => array(

								'allborders' => array(

								  'style' => PHPExcel_Style_Border::BORDER_THIN

								)

							  )

							);

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

				unset($styleArray);

				

				$rowPos = 1;

				if(count($filterUser)>0) {

					// Write User Header

					$objPHPExcel->getActiveSheet()->fromArray(array_keys($filterUser), NULL, 'A1');

					// Set title row bold

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);

					// Setting Column Background Color

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

				

					// Setting Text Alignment Center

					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

					

					// Write User Detail

					$objPHPExcel->getActiveSheet()->fromArray(array_values($filterUser), NULL, 'A2');

					$rowPos = 4;

				}

				

				// Set title row bold

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);

				

				// Setting Auto Width

				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {

					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

				}

				

				// Setting Column Background Color

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

				

				// Setting Text Alignment Center

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				

				$reportRows = array();

				$visitWith = '';

				$approvalArray = array(0=>'Pending',1=>'Approved',2=>'Rejected');

				foreach($totalRowData as $index=>$row)

				{

					$reportRows[] = array($row['region_name'],$row['area_name'],$row['headquater_name'],$row['doctor_name'],$row['activity_name'],$row['product'],$row['unit'],$row['price'],$row['totalvalue'],$row['overallvalue'],$row['roidate']);

				}

				

				// Write Row Data

				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.($rowPos+1));

					

				// Set autofilter

				// Always include the complete filter range!

				// Excel does support setting only the caption

				// row, but that's not a best practise...

				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column

				$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos);

				

				// Rename sheet

				$objPHPExcel->getActiveSheet()->setTitle('ROI Report');

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);

									

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/vnd.ms-excel');

				header('Content-Disposition: attachment;filename="roi_report.xls"');

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

		   $_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code: '.__LINE__; 

		}		  

	}
	
		public function countChild($loggedIn)

		{

			$query = $this->_db->select()

					 ->from(array('EPT'=>'employee_personaldetail'),array('CNT'=>'count(1)'))

					 ->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;

			return $this->getAdapter()->fetchRow($query);

		}
	 public function getROIDatas($data){
	      $query = $this->_db->select()

							 ->from(array('RT'=>'crm_roi'),array('*'))

							 ->joininner(array('ROD'=>'crm_roi_details'),"ROD.roi_id=RT.roi_id",array('*'))

							 ->joininner(array('PT'=>'crm_products'),"PT.product_id=ROD.product_id",array('product_name'))

							 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=RT.doctor_id",array('doctor_name'))

							 ->where("RT.roi_id='".$data['token']."'")
							->order("product_name ASC"); //print_r($query->__toString());die;

			$result = $this->getAdapter()->fetchAll($query);
			return $result;
	 }
	 
	 public function UpdateROI($data){
	    //echo "<pre>";print_r($data);die;
		$total = 0;
		foreach($data['unit'] as $product=>$unit){ 
		   $this->_db->update('crm_roi_details',array('unit'=>$unit,'value'=>$data['value'][$product]),"roi_id='".$data['roi_id']."'");
		   $total = $total + $data['value'][$product];
		}
		
		foreach($data['extra_unit'] as $extra_product=>$extra_unit){
		  if($extra_unit>0){ 
		   $this->_db->insert('crm_roi_details',array('roi_id'=>$data['roi_id'],'product_id'=>$data['product'][$extra_product],'unit'=>$extra_unit,'value'=>$data['extra_value'][$extra_product]));
		   $total = $total + $data['extra_value'][$extra_unit];
		   }
		}
		$this->_db->update('crm_roi',array('roi_total_amount'=>$total),"roi_id='".$data['roi_id']."'");
	 }
	 
	 public function productList(){
	    $query = $this->_db->select()

					 ->from(array('CP'=>'crm_products'),array('*'))
					 ->order("product_name"); //echo $query->__toString();die;

			return $this->getAdapter()->fetchAll($query);
	}
	public function geHierarchyId(){
    	
    	$select = $this->_db->select()
        			->from(array('EL'=>'emp_locations'),array('*'))
        			->where("user_id='".$_SESSION['AdminLoginID']."'");
        		//print_r($select->__toString());die;
     	$result = $this->getAdapter()->fetchRow($select);
     	$where = "EPD.user_status='1' AND EPD.delete_status='0'";
   		if($_SESSION['AdminDesignation']==8){
    		$where .= " AND EL.user_id='".$_SESSION['AdminLoginID']."'";
   		}elseif($_SESSION['AdminDesignation']==7){
    		$where .= " AND EL.area_id='".$result['area_id']."'";
   		}
   		elseif($_SESSION['AdminDesignation']==6){
    		$where .= " AND EL.region_id='".$result['region_id']."'";
  		}
   		elseif($_SESSION['AdminDesignation']==5){
    		$where .= " AND EL.zone_id='".$result['zone_id']."'";
   		}
   		$select = $this->_db->select()
        			->from(array('EL'=>'emp_locations'),array('headquater_id'))
        			->joininner(array('EPD'=>'employee_personaldetail'),"EL.user_id=EPD.user_id",array(''))
        			->where($where);
        //print_r($select->__toString());die;
    	$result = $this->getAdapter()->fetchAll($select);
    	$res_id = array();
    	foreach($result as $key=>$value){
    		$res_id[] = $value['headquater_id'];
    	} 
    	return array_unique($res_id);
  	}
  	public function getUserHeadquarter($userID)
	{
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))
				 ->joininner(array('ELT'=>'emp_locations'),"ELT.user_id=EPT.user_id",array('ELT.headquater_id'))
				 ->where("EPT.user_id =".$userID." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
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
}

?>