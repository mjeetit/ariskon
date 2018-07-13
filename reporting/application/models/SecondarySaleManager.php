<?php
class SecondarySaleManager extends Zend_Custom
{
	public function saleslist($data=array())
	{
		$filterparam='';
		$where=" AND UT.delete_status='0'";
		if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			$value = implode(',',$this->geHierarchyId());
		 	$filterparam .= " AND EL.headquater_id IN(".$value.")";
		}
		
		//Filter With ZBM Data
		if(!empty($data['token6'])){
			$where = ' 1';
			$filterparam .= ' AND UT.designation_id=5 AND UT.user_id='.Class_Encryption::decode($data['token6']);
		}
		
		//Filter With RBM Data
		if(!empty($data['token5'])){
			$where = ' 1';
			$filterparam .= ' AND UT.designation_id=6 AND UT.user_id='.Class_Encryption::decode($data['token5']);
		}
		
		//Filter With ABM Data
		if(!empty($data['token4'])){
			$where = ' 1';
			$filterparam .= ' AND UT.designation_id=7 AND UT.user_id='.Class_Encryption::decode($data['token4']);
		}
		//Filter With BE Data
		if(!empty($data['token3'])){
			$where = ' 1';
			$filterparam .= ' AND UT.designation_id=8 AND UT.user_id='.Class_Encryption::decode($data['token3']);
		}
		//Filter With Headquarter Data
		if(!empty($data['token2'])){
			$where = ' 1';
			$filterparam .= " AND HQ.headquater_id='".Class_Encryption::decode($data['token2'])."'";
		}
		
		//Filter With Date Range
		if(!empty($data['from_date']) && !empty($data['to_date'])){
			$filterparam .= " AND DATE(SS.insert_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		}else{
			$monday = date( 'Y-m-d', strtotime( '-1 month' ) );
			$saturday = date( 'Y-m-d',time());
			$filterparam .= " AND DATE(SS.insert_date) BETWEEN '".$monday."' AND '".$saturday."'";
		}
		
		$select = $this->_db->select()
		 				->from(array('SS'=>'secondary_sale',array('*')))
						
						->joininner(array('UT'=>'employee_personaldetail'),"UT.user_id=SS.user_id",array('employee_code as Emp_Code','CONCAT(UT.first_name," ",UT.last_name) as Emp_Name'))
						
						->joinleft(array('CS'=>'crm_stockists'),"CS.stockist_id=SS.stockist_id",array('stockist_name'))
						
						->joinleft(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name as Designation'))
						
                        ->joinleft(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('Department'=>'department_name'))
						
                        ->joininner(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array())
						
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=SS.headquarter_id",array('HQ.headquater_name'))
						
						->joinleft(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('RT.region_name'))

						->joinleft(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('ZT.zone_name'))
						
						->joinleft(array('US'=>'users'),"US.user_id=UT.user_id",array())
						
						->where("`SS`.`delete_status`='0'".$where.$filterparam."");
						//print_r($select->__toString());die;
	  $result = $this->getAdapter()->fetchAll($select);
	  return $result;
	}
	public function AddSecondarySale($data=array()){
	
	return $this->_db->insert('secondary_sale',array_filter(array('user_id'=>$data['user_id'],'headquarter_id'=>$data['headquater_id'],'date_from'=>$data['from'],'date_to'=>$data['to'],'amount'=>$data['amount'],'insert_date'=>$data['date'])));

	}
	public function HeaquarterById($data=array())
	{
		$select = $this->_db->select()
		 				->from(array('EL'=>'emp_locations',array('headquater_id')))
						->where("EL.user_id=".$data['id']."");
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	public function getUsers($data=array())
	{
		$select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array(
						'CONCAT(UT.first_name," ",UT.last_name," (",DES.designation_code,") -",UT.employee_code) as name','UT.user_id'))
						->joinleft(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array())
						->where(" `UT`.`delete_status`='0' AND UT.designation_id IN(8,7,6)");
						//print_r($select->__toString());die;
		$result = $this->getAdapter()->fetchAll($select);
		return $result;
	}
	public function ExportSales($data=array())
	{
		try{
			$totalRowData = $this->saleslist($data);
			 
				//echo "<pre>";print_r($totalRowData);die;
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();

				// Write Sheet Header
				$headers = array('0'=>'Employee Name','1'=>'Department','2'=>'Designation','3'=>'Employee Code','4'=>'Headquarter','5'=>'Stockist','6'=>'From','7'=>'To','8'=>'Date','9'=>'Amount');
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A1');

				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

				unset($styleArray);
				
				// Set title row bold

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);

				

				// Setting Auto Width

				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {

					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);

				}

				

				// Setting Column Background Color

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');

				

				// Setting Text Alignment Center

				$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				

				$reportRows = array();
				$i==0;
				foreach($totalRowData as $row)
				{
					$reportRows[$i]['Emp_Name']=$row['Emp_Name'];
					$reportRows[$i]['Department']=$row['Department'];
					$reportRows[$i]['Designation']=$row['Designation'];
					$reportRows[$i]['Emp_Code']=$row['Emp_Code'];
					$reportRows[$i]['headquater_name']=$row['headquater_name'];
					$reportRows[$i]['stockist_name']=$row['stockist_name'];
					$reportRows[$i]['date_from']=$row['date_from'];
					$reportRows[$i]['date_to']=$row['date_to'];
					$reportRows[$i]['insert_date']=$row['insert_date'];
					$reportRows[$i]['amount']=$row['amount'];
					$i++;
				}

				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.'2');

					

				// Set autofilter

				// Always include the complete filter range!

				// Excel does support setting only the caption

				// row, but that's not a best practise...

				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column

				$objPHPExcel->getActiveSheet()->setAutoFilter('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');

				

				// Rename sheet

				$objPHPExcel->getActiveSheet()->setTitle('Secondary Sales');

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);

									

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/vnd.ms-excel');

				header('Content-Disposition: attachment;filename="secondary_sales.xls"');

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
	
	public function approveSales($data=array())
	{
		return $this->_db->update('secondary_sale',array('status'=>'1'),"id='".Class_Encryption::decode($data['id'])."'");
	}
	
	public function deleteSales($data=array())
	{
		return $this->_db->update('secondary_sale',array('delete_status'=>'1','delete_date'=>$data['date']),"id='".Class_Encryption::decode($data['id'])."'");
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
   		}elseif($_SESSION['AdminDesignation']==6){
    		$where .= " AND EL.region_id='".$result['region_id']."'";
   		}elseif($_SESSION['AdminDesignation']==5){
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
}
?>