<?php
class Crm_Model_StockistManager extends Zend_Custom
{
	public function getStockistList($data=array())
	{
		try {
			$where = 1;
			if(isset($data['headtoken']) && (int)$data['headtoken']>0) {
				$where .= ' AND SHQ.headquater_id='.$data['headtoken'];
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'ST.stockist_name');
			
			$countQuery = $this->_db->select()
							->from(array('ST'=>'crm_stockists'),array('COUNT(1) AS CNT'))
							->joinleft(array('SHQ'=>'crm_stockists_hq'),"SHQ.stockist_id=ST.stockist_id",array())
							->where($where)
							->where('ST.isActive','1')
							->where('ST.isDelete','0'); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			
			$select = $this->_db->select()
						->from(array('ST'=>'crm_stockists'),array('DISTINCT(ST.stockist_id)','ST.stockist_name','ST.address','ST.isActive'))
						->joinleft(array('SHQ'=>'crm_stockists_hq'),"SHQ.stockist_id=ST.stockist_id",array())
						->where($where)
						->where('ST.isActive','1')
						->where('ST.isDelete','0')
						->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
						->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$results = $this->getAdapter()->fetchAll($select);
			$stockistData = array();
			foreach($results as $key=>$result) {
				$stockistData[$key]['stockist_id'] = $result['stockist_id'];
				$stockistData[$key]['stockist_name'] = $result['stockist_name'];
				$stockistData[$key]['address'] = $result['address'];
				$stockistData[$key]['isActive'] = $result['isActive'];
				$stockistData[$key]['hq'] = $this->StockistHQ($result['stockist_id']);
			}
			
			return array('Total'=>$total[0]['CNT'],'Records'=>$stockistData,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']); 
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}			
	}
	
	public function StockistHQ($stockistID=0)
	{
		try {
			$select = $this->_db->select()
						->from(array('SHQ'=>'crm_stockists_hq'),array("GROUP_CONCAT(HQ.headquater_name SEPARATOR ' | ') AS hq"))
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=SHQ.headquater_id",array())
						->where('SHQ.stockist_id='.$stockistID)
						->order('HQ.headquater_name ASC'); //print_r($select->__toString());die;
			$results = $this->getAdapter()->fetchRow($select);			
			return $results['hq']; 
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}			
	}
	
	public function UploadStockist($formData)
	{
		ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		$filename 	= CommonFunction::UploadFile('stockistFile','xls');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		
		// Count Columns of each Row
		$totalColumns = $data->getHighestColumn();
		
		// Get All City Data
		$getHQ = $this->getLocationData(array('tableName'=>'headquater','tableColumn'=>array('headquater_id',"UPPER(replace(headquater_name,' ',''))")));
		//echo "<pre>";print_r($getHQ);echo "</pre>";die;

		$rowError = array();
		$rowData  = array();
		$sheetHeader = $data->rangeToArray('A1:'.$totalColumns.'1');
		$rowData[] = $sheetHeader[0];
		for ($i=1; $i<$k; $i+=1) {
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			
			// Get the specified row as an array of all cells value
			$rowValue  = $data->rangeToArray('A'.($i+1).':'.$totalColumns.($i+1)); //echo "<pre>";print_r($totalColumns);echo "</pre>";die;
			
			if($totalColumns == 'C') {
				$tableData = array();
				
				$tableData['name'] 	  = ($this->getCell($data,$i,2)!='') ? trim($this->getCell($data,$i,2)) : '';
				$tableData['address'] = ($this->getCell($data,$i,3)!='') ? trim($this->getCell($data,$i,3)) : '';
				
				$hqName = ($this->getCell($data,$i,1)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,1))) : '';
				$allHQ  = explode(',',$hqName);
				$hqData = array();
				if(count($allHQ)>0) {
					foreach($allHQ as $hq) {
						$hqData[] = (!empty($hq) && isset($getHQ[$hq])) ? $getHQ[$hq] : 0;
					}
				} //print_r($hqData);die;
				
				if(count($hqData) > 0) {
					$tableData['hq'] = $hqData; //echo "<pre>";print_r($tableData);echo "</pre>";die;
					if($this->saveDetail($tableData)) {
						$rowError[] = array(($i+1),"Stockist has been added !!");
						$rowData[]  = $rowValue[0];
					}
					else {
						$rowError[] = array(($i+1),"Stockist couldn't added !!");
						$rowData[]  = $rowValue[0];
					}
				}
				else {
					$rowError[] = array(($i+1),"Headquater (".$hqName.") not found !!");
					$rowData[]  = $rowValue[0];
				}
			}
			else {
				$rowError[] = array(($i+1),"Column length didn't match !!");
				$rowData[]  = $rowValue[0];
			}
		}
		//echo "<pre>";print_r($rowData);echo "</pre>";die;
		
		if(count($rowError)>0) {
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;
			
			// Create a first sheet, representing sales data
			$objPHPExcel->setActiveSheetIndex(0);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Row Number')
										  ->setCellValue('B1', 'Error Description');
			// Set title row bold
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			
			// Setting Auto Width
			//$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
			foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			// Setting Column Background Color
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
			
			// Setting Text Alignment Center
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Write Data
			$objPHPExcel->getActiveSheet()->fromArray($rowError, NULL, 'A2');
			
			// Freez Pane of Top Row
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			
			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('Error Detail');
			
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();
			

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(1);
			//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');
			
			$objPHPExcel->getActiveSheet()->fromArray($rowData, NULL, 'A2');
			
			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('Error Data Row');
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);					
							
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="error_response.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
			//$objWriter->save('test.xlsx');  //THIS WORKS
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);die;
		}
		
		return TRUE;
	}
	
	public function getCell(&$worksheet,$row,$col,$default_val='')
	{
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}
	
	public function getLocationData($data=array())
	{
		$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';
		$tableColumn = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');
		
		$where = '1';
		if(isset($data['columnName']) && isset($data['columnValue'])) {
			$where .=  " AND ".$data['columnName']."='".$data['columnValue']."'";
		}
		if(isset($data['columnName1']) && isset($data['columnValue1'])) {
			$where .=  " AND ".$data['columnName1']."='".$data['columnValue1']."'";
		}
		
		$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //echo $select->__toString();die;
		$tableData = $this->getAdapter()->fetchAll($select);
		
		$responseData = array();
		if(count($tableData) > 0) {
			foreach($tableData as $key=>$data) {
				$responseData[$data[$tableColumn[1]]] = $data[$tableColumn[0]];
			}
		}
		
		return $responseData;
	}
		
	public function saveDetail($data=array())
	{
		$tableData['stockist_name'] = (isset($data['name'])) ? trim($data['name']) : '';
		$tableData['address'] = (isset($data['address'])) ? $data['address'] : '';
		$tableData['created_by'] = $_SESSION['AdminLoginID'];
		$tableData['created_ip'] = $_SERVER['REMOTE_ADDR'];
		$hqData = (isset($data['hq'])) ? $data['hq'] : array(); //echo "<pre>";print_r($tableData);echo "</pre>";die;
		
		if($this->_db->insert('crm_stockists',array_filter($tableData))) {
			$stockistID = $this->_db->lastInsertId();
			return ($this->saveStockistHq(array('stockistID'=>$stockistID,'hq'=>$hqData))) ? TRUE : FALSE;
		}
		else {
			return FALSE;
		}
	}
	
	public function updateDetail($data=array())
	{
		$stockistID = (isset($data['stockist_id'])) ? trim($data['stockist_id']) : '';
		$tableData['stockist_name'] = (isset($data['name'])) ? trim($data['name']) : '';
		$tableData['address'] = (isset($data['address'])) ? $data['address'] : '';
		$tableData['isModify'] = '1';
		$tableData['modify_by'] = $_SESSION['AdminLoginID'];
		$tableData['modify_date'] = date('Y-m-d:H:i:s');
		$tableData['modify_ip'] = $_SERVER['REMOTE_ADDR'];
		$hqData = (isset($data['hq'])) ? $data['hq'] : array(); //echo "<pre>";print_r($tableData);echo "</pre>";die;
		
		if($this->_db->update('crm_stockists',$tableData,"stockist_id=".$stockistID)) {
			return ($this->saveStockistHq(array('stockistID'=>$stockistID,'hq'=>$hqData))) ? TRUE : FALSE;
		}
		else {
			return FALSE;
		}
	}
	
	public function saveStockistHq($data=array())
	{
		$stockistID = (isset($data['stockistID'])) ? trim($data['stockistID']) : '0';
		$hqData 	= (isset($data['hq'])) ? $data['hq'] : array(); //echo "<pre>";print_r($hqData);echo "</pre>";die;
		
		if($stockistID>0 && count($hqData)>0) {
			$this->_db->delete('crm_stockists_hq',"stockist_id=".$stockistID);
			foreach($hqData as $hq) {
				$this->_db->insert('crm_stockists_hq',array_filter(array('stockist_id'=>$stockistID,'headquater_id'=>$hq)));
			}
		}
		return TRUE;
	}
	
	public function getStockistDetail($data=array())
	{
		try {
			$stockistID = (isset($data['stockist_id']) && $data['stockist_id']>0) ? trim($data['stockist_id']) : 0;
			$select = $this->_db->select()
						->from(array('ST'=>'crm_stockists'),array('DISTINCT(ST.stockist_id)','ST.stockist_name','ST.address',"GROUP_CONCAT(SHQ.headquater_id SEPARATOR ',') AS hq"))
						->joinleft(array('SHQ'=>'crm_stockists_hq'),"SHQ.stockist_id=ST.stockist_id",array())
						->where('ST.stockist_id=?',$stockistID)
						->where('ST.isActive','1')
						->where('ST.isDelete','0'); //print_r($select->__toString());die;
			return $this->getAdapter()->fetchRow($select); 
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}			
	}
	
	/**
	 * Export Stockist Header
	 * Function : ExportStockistHeader()
	 * This function return stockist file with header sheet
	 **/
	public function ExportStockistHeader($data)
	{
		try {
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;
			
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();
			
			// Create a first sheet, representing sales data
			$objPHPExcel->setActiveSheetIndex(0);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Headquarter')
										  ->setCellValue('B1', 'Stockist Name')
										  ->setCellValue('C1', 'Stockist Address');
			// Row Formatting : Set title row bold
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			
			// Setting Auto Width
			//$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
			foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			// Add Column Comment
			//$objPHPExcel->getActiveSheet()->getComment('A1')->getText()->createTextRun("\r\n");
			$objPHPExcel->getActiveSheet()->getComment('A1')->getText()->createTextRun('Add More HQ then use comma (,) after each HQ!!');
			
			// Add Freeze pane
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			
			// Setting Column Background Color
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
			
			// Setting Text Alignment Center
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);					
								
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Stockist_Header.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
			//$objWriter->save('test.xlsx');  //THIS WORKS
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);die;
		
		}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}		  
	}
	
	/**
	 * Export Stockist Header
	 * Function : ExportStockistData()
	 * This function return stockist excel sheet
	 **/
	public function ExportStockistData($data)
	{
		try{
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;
			
		
			// Create a new worksheet, after the default sheet
			$objPHPExcel->createSheet();
			
			// Create a first sheet, representing sales data
			$objPHPExcel->setActiveSheetIndex(0);
			
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Headquarter')
										  ->setCellValue('B1', 'Stockist Name')
										  ->setCellValue('C1', 'Stockist Address');
			// Row Formatting : Set title row bold
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			
			// Setting Auto Width
			//$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
			foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
				$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			// Add Freeze pane
			$objPHPExcel->getActiveSheet()->freezePane('A2');
			
			// Setting Column Background Color
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
			
			// Setting Text Alignment Center
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$stockists = $this->getStockistList($data); //echo "<pre>";print_r($stockists);echo "</pre>";die; 
			$stockistInfo = array();
			if(count($stockists['Records'])>0){
				$i=1;
				foreach($stockists['Records'] as $stockist){
					$stockistInfo[] = array($stockist['hq'],$stockist['stockist_name'],$stockist['address']);
					$i++;
				}
				$objPHPExcel->getActiveSheet()->fromArray($stockistInfo, NULL, 'A2');
				
				// Set Auto Filter
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:A'.$i);
			}
			else {
				$objPHPExcel->getActiveSheet()->mergeCells('A2:C2')
											  ->setCellValue('A2','No Data Found!!')
											  ->getStyle('A2:C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			}						
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);					
									
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="Stockist_Data.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
			//$objWriter->save('test.xlsx');  //THIS WORKS
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);die;
		}
		catch(Exception $e){
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}		  
	}
}
?>