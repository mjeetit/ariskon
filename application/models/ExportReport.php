<?php
class VisitReport extends ReportManager
{
	private $_headquarters 	= array();
	
	
	public function ReportType($data){
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
		  $this->ExpenseExcelReport($data,$filter);
		}
	}
	public function DoctorExcelReport($data,$reportusers)
	{
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel(); 
				$i = 0;
				foreach($reportusers as $users){ 
					if($i>0){
					 $objPHPExcel->createSheet();
					}
					
					$objPHPExcel->setActiveSheetIndex($i);
					// Report Sheet Header
					$empdetails = $this->getemployeeDetail(array('user_id'=>$users));
					$objPHPExcel->getActiveSheet()->fromArray(array('Emp. Code','Name','Desig.','Region','HQ','Month'), NULL, 'A1');
					$objPHPExcel->getActiveSheet()->fromArray(array($empdetails['employee_code'],$empdetails['Emp'],$empdetails['designation_name'],$empdetails['region_name'],$empdetails['headquater_name'],$bunchdetail['visit_month']), NULL, 'A2');
					
					$objPHPExcel->getActiveSheet()->fromArray(array('No.of Working Days','No. Of Field Work Days','Leave','Admin Day','Rev. Meeting','Cycle Meeting','CME/Conf.','Holiday','Other','Total Dr. Call','Total Che. Call','Dr. Call Avg.','Ch Call Avg.'), NULL, 'A5');
					$objPHPExcel->getActiveSheet()->fromArray(array($empdetails['employee_code'],$empdetails['Emp'],$empdetails['designation_name'],$empdetails['region_name'],$empdetails['headquater_name'],$bunchdetail['CNT'],$bunchdetail['Avg'],$bunchdetail['Avg'],$bunchdetail['CNT']), NULL, 'A6');
					$objPHPExcel->getActiveSheet()->fromArray(array('Date','Work Planned','Actual','No. of Dr. Visit','No. of Chemist Visit','No. Stockist Visit','JFW/Indepent Work'), NULL, 'A8');
					 $objPHPExcel->getActiveSheet()->setTitle($empdetails['employee_code']);
				  $i++;
				}		
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				
				// Rename sheet
				
				
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
	
	public function getemployeeDetail($data){
	    $select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array('ED.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name"))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->joinleft(array('RT'=>'region'),"RT.region_id=EL.region_id",array('region_name'))
							   ->where("ED.user_id='".$data['user_id']."'"); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchRow($select);
			return $result;
	    
	}
	public function getBunchDetail($data){
	    $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array("DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg"))
							   ->where("EE.user_id='".$data['user_id']."'"); //print_r($select->__toString());die;

			$result =  $this->getAdapter()->fetchRow($select);
			return $result;
	}
	public function totaldoctorCall($data){
	     $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array("DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg"))
							   ->where("EE.user_id='".$data['user_id']."'");
		$result =  $this->getAdapter()->fetchRow($select);
		return $result;
	}
	
	public function totalchemistCall($data){
	     $select = $this->_db->select()
							   ->from(array('EE'=>'app_chemist_visit'),array("DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg"))
							   ->where("EE.user_id='".$data['user_id']."'");
		$result =  $this->getAdapter()->fetchRow($select);
		return $result;
	}
	public function totalMeetings($data){
	     $select = $this->_db->select()
							   ->from(array('EE'=>'app_chemist_visit'),array("DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg"))
							   ->where("EE.user_id='".$data['user_id']."'");
		$result =  $this->getAdapter()->fetchRow($select);
		return $result;
	}
	
}
?>