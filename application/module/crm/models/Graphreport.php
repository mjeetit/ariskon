<?php
    /**
     * All the Function Related to Settings
     * @Auth : JC Lifecare Pvt. Ltd
     * @Create Date : 09-July-2014
     * @Description : This module Consists All the methods wich manage the Setting
     **/
    class Crm_Model_Graphreport extends Zend_Custom
    {
        /**
         *
         **/
		public function getTableData($data=array()) {
			try {
				$tableName   	 = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';
				$tableColumn 	 = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');
				$returnRow   	 = (isset($data['returnRow'])   && !empty($data['returnRow']))    ? $data['returnRow']       : 'single';
				$columnCondition = (isset($data['columnCondition']) && count($data['columnCondition'])>0) ? $data['columnCondition'] : array();
				$ConditionOperat = (isset($data['conditionOpr']) && count($data['conditionOpr'])>0) ? $data['conditionOpr'] : array();
				
				$where = '1';
				if(count($columnCondition)>0) {
					foreach($columnCondition as $columnName=>$columnValue) {
						if(isset($ConditionOperat[$columnName]) && strtoupper($ConditionOperat[$columnName])=='IN') {
							$where .=  " AND ".$columnName." IN (".implode(',',$columnValue).")";
						}
						else {
							$where .=  " AND ".$columnName."='".$columnValue."'";
						}
					}
				}
				
				$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //echo $select->__toString();die;
				return ($returnRow=='single') ? $this->getAdapter()->fetchRow($select) : $this->getAdapter()->fetchAll($select);
			}
			catch(Exception $e){
				$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
			}
		}
		
		/**
         *
         **/
		public function getROIData12($data){//print_r($data);die;
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
				//Filter With Date Range
				if(!empty($data['from_date']) && !empty($data['to_date'])){
					$filterparam .= " AND DATE(RT.roi_month) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
				}
				
				$query = "SELECT  
							  Zone,Region,HQ,Doctor,
							  sums.April, sums.May, sums.June,
							  sums.April + sums.May + sums.June AS ROISum
							FROM ( 
								  SELECT                                
									ZT.zone_name AS Zone,RG.region_name AS Region,HT.headquater_name AS HQ,DT.doctor_name AS Doctor,
									SUM(IF(RT.roi_month='2014-04-01',RT.roi_total_amount,0)) AS 'April', 
									SUM(IF(RT.roi_month='2014-05-01',RT.roi_total_amount,0)) AS 'May', 
									SUM(IF(RT.roi_month='2014-06-01',RT.roi_total_amount,0)) AS 'June' 
								  FROM crm_roi AS RT
								  INNER JOIN crm_doctors AS DT ON DT.doctor_id=RT.doctor_id
								  INNER JOIN headquater AS HT ON HT.headquater_id=DT.headquater_id
								  INNER JOIN region AS RG ON RG.region_id=DT.region_id
								  INNER JOIN zone AS ZT ON ZT.zone_id=DT.zone_id
								  WHERE ".$where.$filterparam."
								  GROUP BY RT.doctor_id WITH ROLLUP 
								 ) AS sums"; //echo $query;die;
							
				$exportData = $this->getAdapter()->fetchAll($query); //print_r($RecordExport[0]);die;
			}
			catch(Exception $e){
			   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
			}
        }
		
		/**
         *
         **/
		public function getROIData_Original($data){//print_r($data);die;
            $roiData = array(
				array('Darbhanga'=>232,'Delhi'=>14,'Bhagalpur'=>173),
				array('Darbhanga'=>322,'Delhi'=>214,'Bhagalpur'=>203));
            return $roiData;
		}
		
		/**
		 * Export Shipment History
		 * Function : ExportHistory()
		 * Function Export The shipment History
		 **/
		public function getROIData($data){
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
				//Filter With Date Range
				if(!empty($data['from_date']) && !empty($data['to_date'])){
					$filterparam .= " AND DATE(RT.roi_month) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
				}
				
				$query = "SELECT sums.HQ,sums.AprilCRM,sums.MayCRM,sums.JuneCRM,sums.JulyCRM,sums.AprilCRM + sums.MayCRM + sums.JuneCRM + sums.JulyCRM AS CRMSum,
								 sums.AprilROI,sums.MayROI,sums.JuneROI,sums.JulyROI,sums.AprilROI + sums.MayROI + sums.JuneROI + sums.JulyROI AS ROISum
							FROM 
							  ( SELECT HT.headquater_name AS HQ, 
								 SUM(IF(EXTRACT(YEAR_MONTH FROM AT.created_date)='201404',AT.total_value,0)) AS 'AprilCRM', 
								 SUM(IF(RT.roi_month='2014-04-01',RT.roi_total_amount,0)) AS 'AprilROI', 
								 SUM(IF(EXTRACT(YEAR_MONTH FROM AT.created_date)='201405',AT.total_value,0)) AS 'MayCRM', 
								 SUM(IF(RT.roi_month='2014-05-01',RT.roi_total_amount,0)) AS 'MayROI', 
								 SUM(IF(EXTRACT(YEAR_MONTH FROM AT.created_date)='201406',AT.total_value,0)) AS 'JuneCRM',
								 SUM(IF(RT.roi_month='2014-06-01',RT.roi_total_amount,0)) AS 'JuneROI',
								 SUM(IF(EXTRACT(YEAR_MONTH FROM AT.created_date)='201407',AT.total_value,0)) AS 'JulyCRM',
								 SUM(IF(RT.roi_month='2014-07-01',RT.roi_total_amount,0)) AS 'JulyROI' 
								FROM crm_roi AS RT 
								INNER JOIN crm_doctors AS DT ON DT.doctor_id=RT.doctor_id 
								INNER JOIN crm_appointments AS AT ON AT.doctor_id=RT.doctor_id
								INNER JOIN headquater AS HT ON HT.headquater_id=DT.headquater_id
								  WHERE ".$where.$filterparam."
								  GROUP BY DT.headquater_id WITH ROLLUP 
							  ) AS sums"; //echo $query;die;
							
				$exportData   = $this->getAdapter()->fetchAll($query); //echo "<pre>";print_r($exportData);die;			
				$totalRowData = count($exportData);
				$CRMData      = array();
				$ROIData      = array();
				if($totalRowData>0) {
					$j=1;
					foreach($exportData as $index=>$rowData)
					{
						if($j<$totalRowData) {
							$CRMData[$rowData['HQ']] = $rowData['CRMSum'];
							$ROIData[$rowData['HQ']] = $rowData['ROISum'];
						}
						$j++;
					} //echo "<pre>";print_r(array($CRMData,$ROIData));die;
				}
				return array($CRMData,$ROIData);
			}
			catch(Exception $e){
			   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
			}		  
		}
	}
?>