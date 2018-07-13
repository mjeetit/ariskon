<?php

	class DoctorManager extends Zend_Custom

	{

		private $_leaveType 		= "leavetypes";

		private $_leavedistribution = "leavedistributions";

		private $_leaveapproval 	= "leaveapprovals";

		private $_designation 		= "designation";

		private $_headquarters 	= array();

	

		public function getDoctors($data)

		{

			try {

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

				//Filter With Date Range

				if(!empty($data['from_date']) && !empty($data['to_date'])){

					$filterparam .= " AND DATE(DT.created_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";

				}

				//Filter With Doctor Type

				$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DT.isApproved='0'" : " AND DT.isApproved='1'";

				//Filter with Activity

				$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND DT.activity_id='".Class_Encryption::decode($data['atoken'])."'" : '';

				//Filter with Activity

				$filterparam .= (isset($data['dstat']) && $data['dstat']==0) ? " AND DT.isActive='0'" : " AND DT.isActive='1'";

				$filterparam .= " AND DT.isDelete='0' AND PT.isActive='1' AND PT.isDelete='0'";

				

				//Order

				$orderlimit = CommonFunction::OdrderByAndLimit($data,'DT.headquater_id');

				

				$countQuery = $this->_db->select()

								->from(array('DT'=>'crm_doctors'),array('COUNT(1) AS CNT'))

								->joininner(array('PT'=>'patchcodes'),"PT.patch_id=DT.patch_id",array())

								->joininner(array('CT'=>'city'),"CT.city_id=DT.city_id",array())

								->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array())

								->joininner(array('AT'=>'area'),"AT.area_id=DT.area_id",array())

								->joininner(array('ZT'=>'zone'),"ZT.zone_id=DT.zone_id",array())

								->joininner(array('RT'=>'region'),"RT.region_id=DT.region_id",array())

								->where($where.$filterparam)

								->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;

				$total = $this->getAdapter()->fetchAll($countQuery);

				

				$query = $this->_db->select()

								 ->from(array('DT'=>'crm_doctors'),array('DT.doctor_id','DT.svl_number','DT.doctor_name','DT.speciality','DT.qualification','DT.isActive','DT.class','DT.isApproved'))

								 ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=DT.patch_id",array('patchName'=>'PT.patch_name'))

								 ->joininner(array('CT'=>'city'),"CT.city_id=DT.city_id",array('cityName'=>'CT.city_name'))

								 ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array('hq'=>'HQ.headquater_name'))

								 ->joininner(array('AT'=>'area'),"AT.area_id=DT.area_id",array('areaName'=>'AT.area_name'))

								 ->joininner(array('ZT'=>'zone'),"ZT.zone_id=DT.zone_id",array('zoneName'=>'ZT.zone_name'))

								 ->joininner(array('RT'=>'region'),"RT.region_id=DT.region_id",array('regionName'=>'RT.region_name'))

								 ->where($where.$filterparam)

								 ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])

								 ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($query->__toString());die;

				

				$result = $this->getAdapter()->fetchAll($query);

			}

			catch(Exception $e){

				$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 

			}

			

			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);

		}

		

		public function doctorDetail($data=array())

		{

			try {

				$doctorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;

				$select = $this->_db->select()->from('crm_doctors','*')->where('doctor_id='.$doctorID);

				return $this->getAdapter()->fetchRow($select);

			}

			catch (Exception $e) {

				$_SESSION[ERROR_MSG] =  'There is some error ('.__LINE__.'), please try again !!'; 

			}

		}

		

		public function doctorchemist($data=array())

		{

			try {

				$doctorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;

				$select = $this->_db->select()->from('crm_doctor_chemists','*')->where('doctor_id='.$doctorID);

				return $this->getAdapter()->fetchAll($select);

			}

			catch (Exception $e) {

				$_SESSION[ERROR_MSG] =  'There is some error ('.__LINE__.'), please try again !!'; 

			}

		}

		

		public function chemistDetail($data=array())

		{

			try {

				$patchID = (isset($data['ptoken'])) ? Class_Encryption::decode($data['ptoken']) : 0;

				$select = $this->_db->select()->from('crm_chemists','*')->where('patch_id='.$patchID.' AND isActive="1" AND isDelete="0"');

				return $this->getAdapter()->fetchAll($select); //return echo $select->__toString();die;

			}

			catch (Exception $e) {

				$_SESSION[ERROR_MSG] =  'There is some error ('.__LINE__.'), please try again !!'; 

			}

		}

	

		public function makeDoctorCode($data=array())

		{

			$query = $this->_db->select()->from('crm_doctors','doctor_code')->order('doctor_id DESC')->limit(1); //echo $query->__toString();die;

			$lastCode = $this->getAdapter()->fetchRow($query);

			return (!empty($lastCode)) ? 'DR'.(int) (substr($lastCode['doctor_code'],2)+1) : 'DR100001';

		}

	

		public function getStreetCodes($data=array())

		{

			$select = $this->_db->select()->from('patchcodes','*')->order('headquater_id','ASC')->order('patchcode','ASC');

			return $this->getAdapter()->fetchAll($select);

		}

	

		public function addDoctorData($data=array())

		{

			$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';

			$tableData = (isset($data['tableData']) && count($data['tableData'])>0) ? $data['tableData'] : array();

			

			if(!empty($tableName) && count($tableData)>0) {

				return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;

			}

			else {

				return 0;

			}

		}

		

		public function updateTableData($data=array())

		{

			$tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';

			$tableData   = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();

			$whereColumn = (isset($data['whereColumn']) && !empty($data['whereColumn'])) ? trim($data['whereColumn']) : '';

			

			if(!empty($tableName) && count($tableData)>0 && !empty($whereColumn)) {

				return ($this->_db->update($tableName,$tableData,$whereColumn)) ? TRUE : FALSE;

			}

			else {

				return FALSE;

			}

		}

		

		public function deleteTableData($data=array())

		{

			$tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';

			$whereColumn = (isset($data['whereColumn']) && !empty($data['whereColumn'])) ? trim($data['whereColumn']) : '';

			

			if(!empty($tableName) && !empty($whereColumn)) {

				return ($this->_db->delete($tableName,$whereColumn)) ? TRUE : FALSE;

			}

			else {

				return FALSE;

			}

		}

	

		public function getTableAutoIncrement($data=array())

		{

			$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';

			$query = "SHOW TABLE STATUS LIKE '$tableName'";

			$result = $this->getAdapter()->fetchRow($query);

			return $result['Auto_increment'];

		}	

	

		public function UploadeDoctorFileOldWay($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('doctorFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			

			$headquarterID = Class_Encryption::decode($formData['hqt']);

			

			// Get All Cities Details

			$AllCity = array();

			$cities = $this->getTableData(array('tableName'=>'city','tableColumn'=>array('city_id','city_name'),'columnName'=>'headquater_id','columnValue'=>$headquarterID,'returnRow'=>'all'));		

			if(count($cities)>0) {

				foreach($cities as $city) {

					$AllCity[strtoupper($city['city_name'])] = $city['city_id'];

				}

			}

			

			// Get All Streets Details

			$AllStreet = array();

			$streets = $this->getTableData(array('tableName'=>'street','tableColumn'=>array('street_id','street_name'),'columnName'=>'headquater_id','columnValue'=>$headquarterID,'returnRow'=>'all'));		

			if(count($streets)>0) {

				foreach($streets as $street) {

					$AllStreet[strtoupper($street['street_name'])] = $street['street_id'];

				}

			}

			

			// 1st way :- Convert multidimensional array into single array

			/*$allArray =  new RecursiveIteratorIterator(new RecursiveArrayIterator($cities));

			$newArray = iterator_to_array($allArray, false);

			// 2nd way :- Convert multidimensional array into single array with only first index (This will be used for limited)

			$newArray1 = array_map('current',$cities);*/

	

			for ($i=1; $i<$k; $i+=1) {

				if ($isFirstRow) {

					$isFirstRow = FALSE;

					continue;

				}

				

				$queryField = array('tableName'=>'headquater','tableColumn'=>array('area_id','region_id','zone_id'),'columnName'=>'headquater_id','columnValue'=>$headquarterID,'returnRow'=>'single');//print_r($queryField);die;

				$headquarterData = $this->getTableData($queryField);

				

				$cityName = ($this->getCell($data,$i,23)!='') ? strtoupper($this->getCell($data,$i,23)) : strtoupper($this->getCell($data,$i,9));			

				if(isset($AllCity[$cityName])) {

					$doctorData['city_id'] = $AllCity[$cityName];

				}

				else {

					$CityLastID = $this->getTableAutoIncrement(array('tableName'=>'city'));

					$CityData   = array('tableName'=>'city','tableData'=>array('headquater_id'=>$headquarterID,'area_id'=>$headquarterData['area_id'],'zone_id'=>$headquarterData['zone_id'],'region_id'=>$headquarterData['region_id'],'bunit_id'=>1,'city_name'=>$cityName,'location_code'=>'C'.$CityLastID));

					$doctorData['city_id'] = $this->addDoctorData($CityData);

				}

				

				$doctorData['doctor_code']  	= $this->makeDoctorCode();

				$doctorData['svl_number']  		= $this->getCell($data,$i,7);

				$doctorData['street']  			= $this->getCell($data,$i,11);

				$doctorData['area_id']  		= $headquarterData['area_id']; 

				$doctorData['zone_id']  		= $headquarterData['zone_id']; 

				$doctorData['region_id']  		= $headquarterData['region_id']; 

				$doctorData['am_headquater_id'] = 0; 

				$doctorData['headquater_id']  	= $headquarterID;

				$doctorData['location_type']  	= $this->getCell($data,$i,10);

				$doctorData['doctor_name']  	= $this->getCell($data,$i,8); 

				$doctorData['speciality']  		= $this->getCell($data,$i,12); 

				$doctorData['class']  			= $this->getCell($data,$i,13); 

				$doctorData['qualification']  	= $this->getCell($data,$i,16); 

				$doctorData['visit_frequency']  = $this->getCell($data,$i,14); 

				$doctorData['gender']  			= $this->getCell($data,$i,15); 

				$doctorData['phone']  			= $this->getCell($data,$i,17); 

				$doctorData['mobile']  			= $this->getCell($data,$i,18); 

				$doctorData['email']  			= $this->getCell($data,$i,19); 

				$doctorData['dob']  			= $this->getCell($data,$i,20); 

				$doctorData['address1']  		= $this->getCell($data,$i,21); 

				$doctorData['address2']  		= $this->getCell($data,$i,22); 

				$doctorData['postcode']  		= $this->getCell($data,$i,24); 

				$doctorData['meeting_day']  	= $this->getCell($data,$i,25); 

				$doctorData['meeting_time']  	= $this->getCell($data,$i,26);

				$doctorData['doctor_potential'] = $this->getCell($data,$i,30);

				$doctorData['activity_id']  		= $this->getCell($data,$i,31);

				$doctorData['remarks']  		= $this->getCell($data,$i,32);

				$doctorData['create_type']  	= '2';

				$doctorData['created_by']  		= $_SESSION['AdminLoginID']; 

				$doctorData['created_ip'] 	 	= $_SERVER['REMOTE_ADDR']; 

				

				if($_SESSION['AdminLoginID']==1) {

					$doctorData['isApproved']    = '1';

					$doctorData['approved_by']   = $_SESSION['AdminLoginID']; 

					$doctorData['approved_date'] = date('Y-m-d h:i:s');

					$doctorData['approved_ip']   = $_SERVER['REMOTE_ADDR'];

				} //print_r($doctorData);die;

				

				$addDoctorMain = $this->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

			   

				/*if($addDoctorMain > 0) {

					if(isset($data['chemists']) && count($data['chemists']) > 0) {

						foreach($data['chemists'] as $chemist) {

							if(trim($chemist) != '') {

								$this->ObjModel->addDoctorData(array('tableName'=>'crm_doctor_chemists','tableData'=>array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist)));

							}

						}

					}			

				}*/

			}

		}

		

		public function UploadeDoctorFile($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('doctorFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

	

			for ($i=1; $i<$k; $i+=1) {

				if ($isFirstRow) {

					$isFirstRow = FALSE;

					continue;

				}

				

				// Get Headquarter with all above location Detail through headquarter name which has given in file

				$hqName = ($this->getCell($data,$i,6)!='') ? strtoupper($this->getCell($data,$i,6)) : '';			

				$hqQueryField = array('tableName'=>'headquater','tableColumn'=>array('headquater_id','area_id','region_id','zone_id','country_id','bunit_id'),'columnName'=>'headquater_name','columnValue'=>$hqName,'returnRow'=>'single');

				$headquarterData = $this->getIDbyName($hqQueryField);

				

				// Get City Detail through city name which has given in file

				$cityName = ($this->getCell($data,$i,7)!='') ? strtoupper($this->getCell($data,$i,7)) : $hqName;

				$cityQueryField = array('tableName'=>'city','tableColumn'=>array('city_id'),'columnName'=>'city_name','columnValue'=>$cityName,'columnName1'=>'headquater_id','columnValue1'=>$headquarterData['headquater_id'],'returnRow'=>'single');

				$cityData = $this->getIDbyName($cityQueryField);

				

				// Get City Detail through city name which has given in file

				$patchName = ($this->getCell($data,$i,8)!='') ? strtoupper($this->getCell($data,$i,8)) : '';

				$patchQueryField = array('tableName'=>'patchcodes','tableColumn'=>array('patch_id'),'columnName'=>'patch_name','columnValue'=>$patchName,'columnName1'=>'city_id','columnValue1'=>$cityData['city_id'],'columnName2'=>'isActive','columnValue2'=>'1','columnName3'=>'isDelete','columnValue3'=>'0','returnRow'=>'single');

				$patchData = $this->getIDbyName($patchQueryField);

				$patchID   = isset($patchData['patch_id']) ? trim($patchData['patch_id']) : 0;

				

				// If patch is not found by given patch name in file then system will add that patch detail which has given in file

				if((int) $patchID <= 0) {

					$lastpatch 					= $this->makePatchCode(array('headquarterID'=>$headquarterData['headquater_id']));

					$patchcode 					= ($lastpatch+1);

					$patchData['patchcode']  	= 'P'.(int)$patchcode;

					$patchData['patch_name']  	= $patchName;

					$patchData['city_id'] 		= $cityData['city_id'];

					$patchData['headquater_id'] = $headquarterData['headquater_id'];

					$patchData['area_id']  		= $headquarterData['area_id']; 

					$patchData['zone_id']  		= $headquarterData['zone_id']; 

					$patchData['region_id']  	= $headquarterData['region_id'];

					$patchData['country_id']  	= $headquarterData['country_id'];

					$patchData['bunit_id']  	= $headquarterData['bunit_id']; 

					$patchData['added_through'] = '3';

					$patchData['created_by']  	= $_SESSION['AdminLoginID']; 

					$patchData['created_ip'] 	= $_SERVER['REMOTE_ADDR'];

					

					$patchID = $this->addDoctorData(array('tableName'=>'patchcodes','tableData'=>$patchData));

				}

				

				$doctorData['doctor_code']  	= $this->makeDoctorCode();

				$doctorData['svl_number']  		= $this->getCell($data,$i,9);

				$doctorData['patch_id']  		= $patchID;

				$doctorData['street_id']  		= $patchID;

				$doctorData['city_id']  		= $cityData['city_id'];

				$doctorData['area_id']  		= $headquarterData['area_id'];

				$doctorData['zone_id']  		= $headquarterData['zone_id']; 

				$doctorData['region_id']  		= $headquarterData['region_id']; 

				$doctorData['am_headquater_id'] = 0; 

				$doctorData['headquater_id']  	= $headquarterData['headquater_id'];

				$doctorData['doctor_name']  	= $this->getCell($data,$i,10); 

				$doctorData['speciality']  		= $this->getCell($data,$i,11); 

				$doctorData['class']  			= $this->getCell($data,$i,13); 

				$doctorData['qualification']  	= $this->getCell($data,$i,12); 

				$doctorData['visit_frequency']  = $this->getCell($data,$i,14); 

				$doctorData['gender']  			= $this->getCell($data,$i,18); 

				$doctorData['phone']  			= $this->getCell($data,$i,20); 

				$doctorData['mobile']  			= $this->getCell($data,$i,21); 

				$doctorData['email']  			= $this->getCell($data,$i,22); 

				$doctorData['dob']  			= $this->getCell($data,$i,19); 

				$doctorData['address1']  		= $this->getCell($data,$i,23); 

				$doctorData['address2']  		= $this->getCell($data,$i,24); 

				$doctorData['postcode']  		= $this->getCell($data,$i,25);

				$doctorData['state']  		= $this->getCell($data,$i,26);

				$doctorData['meeting_day']  	= $this->getCell($data,$i,15); 

				$doctorData['meeting_time']  	= $this->getCell($data,$i,16);

				$doctorData['doctor_potential'] = $this->getCell($data,$i,27);

				$doctorData['activity_id']  		= $this->getCell($data,$i,17);

				$doctorData['remarks']  		= $this->getCell($data,$i,31);

				$doctorData['create_type']  	= '2';

				$doctorData['created_by']  		= $_SESSION['AdminLoginID']; 

				$doctorData['created_ip'] 	 	= $_SERVER['REMOTE_ADDR']; 

				

				if($_SESSION['AdminLoginID']==1) {

					$doctorData['isApproved']    = '1';

					$doctorData['approved_by']   = $_SESSION['AdminLoginID']; 

					$doctorData['approved_date'] = date('Y-m-d h:i:s');

					$doctorData['approved_ip']   = $_SERVER['REMOTE_ADDR'];

				} //print_r($doctorData);die;

				

				$addDoctorMain = $this->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

			}

		}

		

		/**

		 * Select Headquarter from drop down and then proceed to do Import doctor file, if doctor already exist for given patchcode then return error response

		 * Function : UploadDoctorExcel()

		 * This function import doctor file only use for first time. Because first time this function will validate all location data and add patch data into patch table.

		 **/

		public function UploadDoctorExcel($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('doctorFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			$rowError = array();

			$rowData  = array();

					

			// Headquarter Detail

			$headquaterID = Class_Encryption::decode($formData['hqt']);

			

			if($headquaterID > 0) {

				// Get All Location Data of Headquater

				$queryField = array('tableName'=>'headquater','tableColumn'=>array('headquater_name','area_id','region_id','zone_id','country_id','bunit_id'),'columnName'=>'headquater_id','columnValue'=>$headquaterID,'returnRow'=>'single');

				$headquaterData = $this->getTableData($queryField);

				

				// Get All Patchcode Data

				$getPatchcode = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id',"UPPER(replace(patchcode,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));

				

				// Get All Patchcode City Data

				$getPatchcity = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('city_id',"patch_id"),'columnName'=>'headquater_id','columnValue'=>$headquaterID)); //echo "<pre>";print_r($getPatchcity);echo "</pre>";die;

				

				if(count($getPatchcode) > 0) {			

					// Count Columns of each Row

					$totalColumns = $data->getHighestColumn();

					if($totalColumns == 'AG') {

						$sheetHeader = $data->rangeToArray('A1:'.$totalColumns.'1');

						$rowData[] = $sheetHeader[0];

						for ($i=1; $i<$k; $i+=1) {

							if ($isFirstRow) {

								$isFirstRow = FALSE;

								continue;

							}

							

							// Get the specified row as an array of all cells value

							$rowValue  = $data->rangeToArray('A'.($i+1).':'.$totalColumns.($i+1)); //echo "<pre>";print_r($totalColumns);echo "</pre>";die;

							

							$doctorData = array();

							$doctorData['business_unit_id'] = $headquaterData['bunit_id'];

							$doctorData['country_id'] 		= $headquaterData['country_id'];

							$doctorData['zone_id'] 			= $headquaterData['zone_id'];

							$doctorData['region_id'] 		= $headquaterData['region_id'];

							$doctorData['area_id'] 			= $headquaterData['area_id'];

							$doctorData['headquater_id'] 	= $headquaterID;

							

							$patchcode = ($this->getCell($data,$i,9)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,9))) : '';

							$doctorData['patch_id'] = (!empty($patchcode) && isset($getPatchcode[$patchcode])) ? $getPatchcode[$patchcode] : 0;

							

							if($doctorData['patch_id'] > 0) {

								$doctorData['city_id'] = isset($getPatchcity[$doctorData['patch_id']]) ? $getPatchcity[$doctorData['patch_id']] : 0;

								$doctorName = ($this->getCell($data,$i,12)!='') ? trim($this->getCell($data,$i,12)) : '';

								

								// Check Doctor name for given patch

								$doctorField = array('tableName'=>'crm_doctors','tableColumn'=>array('doctor_id'),'columnName'=>'patch_id','columnValue'=>$doctorData['patch_id'],'columnName1'=>"UPPER(replace(doctor_name,' ',''))",'columnValue1'=>str_replace(' ','',strtoupper($doctorName)),'returnRow'=>'single');

								$doctorRow = $this->getTableData($doctorField); //echo "<pre>";print_r($doctorID);echo "</pre>";die;

								

								if(trim($doctorRow['doctor_id']) < 1) {

									$doctorData['doctor_code']  	= $this->makeDoctorCode();

									$doctorData['org_svl_number']  	= $this->makeDoctorSVL();

									$doctorData['svl_number']  		= $this->makeDoctorSVL(array('headquarterID'=>$headquaterID));

									$doctorData['am_headquater_id'] = 0; 

									$doctorData['speciality']  		= $this->getCell($data,$i,13); 

									$doctorData['qualification']  	= $this->getCell($data,$i,14); 

									$doctorData['class']  			= $this->getCell($data,$i,15); 

									$doctorData['visit_frequency']  = $this->getCell($data,$i,16); 

									$doctorData['meeting_day']  	= $this->getCell($data,$i,17); 

									$doctorData['meeting_time']  	= $this->getCell($data,$i,18);

									$doctorData['activity_id']  		= $this->getCell($data,$i,19);												

									$doctorData['gender']  			= $this->getCell($data,$i,20); 

									$doctorData['dob']  			= $this->getCell($data,$i,21); 

									$doctorData['phone']  			= $this->getCell($data,$i,22); 

									$doctorData['mobile']  			= $this->getCell($data,$i,23); 

									$doctorData['email']  			= $this->getCell($data,$i,24); 

									$doctorData['address1']  		= $this->getCell($data,$i,25); 

									$doctorData['address2']  		= $this->getCell($data,$i,26); 

									$doctorData['postcode']  		= $this->getCell($data,$i,27);

									$doctorData['state']  			= $this->getCell($data,$i,28);

									$doctorData['doctor_potential'] = $this->getCell($data,$i,29);												

									$doctorData['remarks']  		= $this->getCell($data,$i,33);

									$doctorData['create_type']  	= '2';

									$doctorData['created_by']  		= $_SESSION['AdminLoginID']; 

									$doctorData['created_ip'] 	 	= $_SERVER['REMOTE_ADDR'];

									

									if($_SESSION['AdminLoginID']==1) {

										$doctorData['isApproved']    = '1';

										$doctorData['approved_by']   = $_SESSION['AdminLoginID']; 

										$doctorData['approved_date'] = date('Y-m-d h:i:s');

										$doctorData['approved_ip']   = $_SERVER['REMOTE_ADDR'];

									} //echo "<pre>";print_r($doctorData);echo "</pre>";die;

									

									$addDoctorMain = $this->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

									

									$chemist1 = ($this->getCell($data,$i,30)!='') ? trim($this->getCell($data,$i,30)) : '';

									if(trim($chemist1) != ''){

										$chemist1ID = $this->getChemistIdOrAdd($chemist1,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist1ID)));

									}

									

									$chemist2 = ($this->getCell($data,$i,31)!='') ? trim($this->getCell($data,$i,31)) : '';

									if(trim($chemist2) != ''){

										$chemist2ID = $this->getChemistIdOrAdd($chemist2,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist2ID)));

									}

									

									$chemist3 = ($this->getCell($data,$i,32)!='') ? trim($this->getCell($data,$i,32)) : '';

									if(trim($chemist3) != ''){

										$chemist3ID = $this->getChemistIdOrAdd($chemist3,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist3ID)));

									}

								}

								else {

									$rowError[] = array(($i+1),"Doctor (".$doctorName.") is already added under patch (".$patchcode.") !!");

									$rowData[]  = $rowValue[0];

								}

							}

							else {

								$rowError[] = array(($i+1),"Patchcode (".$patchcode.") is wrong !!");

								$rowData[]  = $rowValue[0];

							}

						}

					}

					else {

						$rowError[] = array('',"Column length didn't match, please verify file column header !!");

					}

				}

				else {

					$rowError[] = array('',"No any patch added yet for headquater (".$headquaterData['headquater_name'].") !!");

				}

			}

			else {

				$rowError[] = array('',"Please select headquater !!");

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

				

				if(count($rowData) > 0) {

					// Create a new worksheet, after the default sheet

					$objPHPExcel->createSheet();

					

					// Add some data to the second sheet, resembling some different data types

					$objPHPExcel->setActiveSheetIndex(1);

					//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');

					

					$objPHPExcel->getActiveSheet()->fromArray($rowData, NULL, 'A2');

					

					// Rename 2nd sheet

					$objPHPExcel->getActiveSheet()->setTitle('Error Data Row');

				}

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);					

								

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/xlsx');

				header('Content-Disposition: attachment;filename="error_response.xlsx"');

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

		

		/**

		 * Select Headquarter from drop down and then proceed to do Import doctor file, if doctor alreday exists for given patchcode then update, if not then add doctor data

		 * Function : firstImportDoctorFile()

		 * This function import doctor file only use for first time. Because first time this function will validate all location data and add patch data into patch table.

		 **/

		public function UpdateDoctorFromExcel($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('doctorFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			$rowError = array();

			$rowData  = array();

					

			// Headquarter Detail

			$headquaterID = Class_Encryption::decode($formData['hqt']);

			

			if($headquaterID > 0) {

				// Get All Location Data of Headquater

				$queryField = array('tableName'=>'headquater','tableColumn'=>array('headquater_name','area_id','region_id','zone_id','country_id','bunit_id'),'columnName'=>'headquater_id','columnValue'=>$headquaterID,'returnRow'=>'single');

				$headquaterData = $this->getTableData($queryField);

				

				// Get All Patchcode Data

				$getPatchcode = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id',"UPPER(replace(patchcode,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));

				

				// Get All Patchcode City Data

				$getPatchcity = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('city_id',"patch_id"),'columnName'=>'headquater_id','columnValue'=>$headquaterID)); //echo "<pre>";print_r($getPatchcity);echo "</pre>";die;

				

				if(count($getPatchcode) > 0) {			

					// Count Columns of each Row

					$totalColumns = $data->getHighestColumn();

					if($totalColumns == 'AI') {

						$sheetHeader = $data->rangeToArray('A1:'.$totalColumns.'1');

						$rowData[] = $sheetHeader[0];

						for ($i=1; $i<$k; $i+=1) {

							if ($isFirstRow) {

								$isFirstRow = FALSE;

								continue;

							}

			$hq_name = trim($this->getCell($data,$i,6));			
			$query = $this->_db->select()
							->from('headquater',array('*'))
							->where("LOWER(headquater_name)='".strtolower($hq_name)."'"); 
			//echo $query->__toString();die;
			$hq_detail = $this->getAdapter()->fetchRow($query);
			if(empty($hq_detail)){
			   print_r($hq_name);die;
			}
						
						$queryField = array('tableName'=>'headquater','tableColumn'=>array('headquater_name','area_id','region_id','zone_id','country_id','bunit_id'),'columnName'=>'headquater_id','columnValue'=>$hq_detail['headquater_id'],'returnRow'=>'single');

						$headquaterData = $this->getTableData($queryField);
		
						
		
						// Get All Patchcode Data
		
						$getPatchcode = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id',"UPPER(replace(patchcode,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$hq_detail['headquater_id']));
		
						
		
						// Get All Patchcode City Data
		
						$getPatchcity = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('city_id',"patch_id"),'columnName'=>'headquater_id','columnValue'=>$hq_detail['headquater_id']));

							// Get the specified row as an array of all cells value

							$rowValue  = $data->rangeToArray('A'.($i+1).':'.$totalColumns.($i+1)); //echo "<pre>";print_r($totalColumns);die;

							

							$doctorData = array();

							$doctorData['business_unit_id'] = $headquaterData['bunit_id'];

							$doctorData['country_id'] 		= $headquaterData['country_id'];

							$doctorData['zone_id'] 			= $headquaterData['zone_id'];

							$doctorData['region_id'] 		= $headquaterData['region_id'];

							$doctorData['area_id'] 			= $headquaterData['area_id'];

							$doctorData['headquater_id'] 	= $headquaterID;

							

							$patchcode = ($this->getCell($data,$i,9)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,9))) : '';

							$doctorData['patch_id'] = (!empty($patchcode) && isset($getPatchcode[$patchcode])) ? $getPatchcode[$patchcode] : 0;

							

							if($doctorData['patch_id'] > 0) {

								$doctorData['city_id'] = isset($getPatchcity[$doctorData['patch_id']]) ? $getPatchcity[$doctorData['patch_id']] : 0;

								$doctorID = ($this->getCell($data,$i,12)!='') ? trim($this->getCell($data,$i,12)) : '';

								$doctorID = (int)$doctorID;

								$doctorName = ($this->getCell($data,$i,13)!='') ? trim($this->getCell($data,$i,13)) : '';

								

								$doctorData['doctor_name'] 		= $doctorName;

								$doctorData['am_headquater_id'] = 0; 

								$doctorData['speciality']  		= $this->getCell($data,$i,14); 

								$doctorData['qualification']  	= $this->getCell($data,$i,15); 

								$doctorData['class']  			= $this->getCell($data,$i,16); 

								$doctorData['visit_frequency']  = $this->getCell($data,$i,17); 

								$doctorData['meeting_day']  	= $this->getCell($data,$i,18); 

								$doctorData['meeting_time']  	= $this->getCell($data,$i,19);

								$doctorData['activity_id']  	= $this->getCell($data,$i,20);												

								$doctorData['gender']  			= $this->getCell($data,$i,21); 

								$doctorData['dob']  			= $this->getCell($data,$i,22); 

								$doctorData['phone']  			= $this->getCell($data,$i,23); 

								$doctorData['mobile']  			= $this->getCell($data,$i,24); 

								$doctorData['email']  			= $this->getCell($data,$i,25); 

								$doctorData['address1']  		= $this->getCell($data,$i,26); 

								$doctorData['address2']  		= $this->getCell($data,$i,27); 

								$doctorData['postcode']  		= $this->getCell($data,$i,28);

								$doctorData['state']  			= $this->getCell($data,$i,29);

								$doctorData['doctor_potential'] = $this->getCell($data,$i,30);												

								$doctorData['remarks']  		= $this->getCell($data,$i,34); //echo "<pre>";print_r($doctorID);die;

								

								// Check Doctor name for given patch

								//$doctorField = array('tableName'=>'crm_doctors','tableColumn'=>array('doctor_id'),'columnName'=>'patch_id','columnValue'=>$doctorData['patch_id'],'columnName1'=>"UPPER(replace(doctor_name,' ',''))",'columnValue1'=>str_replace(' ','',strtoupper($doctorName)),'returnRow'=>'single');

								//$doctorRow = $this->getTableData($doctorField);

								

								//if(trim($doctorRow['doctor_id']) < 1) 

								//if(trim($doctorID) < 1) {

									$doctorData['doctor_code']  	= $this->makeDoctorCode();

									$doctorData['org_svl_number']  	= $this->makeDoctorSVL();

									$doctorData['svl_number']  		= $this->makeDoctorSVL(array('headquarterID'=>$headquaterID));

									$doctorData['create_type']  	= '2';

									$doctorData['created_by']  		= $_SESSION['AdminLoginID']; 

									$doctorData['created_ip'] 	 	= $_SERVER['REMOTE_ADDR'];

									

									if($_SESSION['AdminLoginID']==1) {

										$doctorData['isApproved']    = '1';

										$doctorData['approved_by']   = $_SESSION['AdminLoginID']; 

										$doctorData['approved_date'] = date('Y-m-d h:i:s');

										$doctorData['approved_ip']   = $_SERVER['REMOTE_ADDR'];
										$doctorData['old_doctor_id']   = trim($doctorID);

									} //echo "<pre>";print_r($doctorData);echo "</pre>";die;

									

									$addDoctorMain = $this->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

									

									$chemist1 = ($this->getCell($data,$i,31)!='') ? trim($this->getCell($data,$i,31)) : '';

									if(trim($chemist1) != ''){

										$chemist1ID = $this->getChemistIdOrAdd($chemist1,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist1ID)));

									}

									

									$chemist2 = ($this->getCell($data,$i,32)!='') ? trim($this->getCell($data,$i,32)) : '';

									if(trim($chemist2) != ''){

										$chemist2ID = $this->getChemistIdOrAdd($chemist2,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist2ID)));

									}

									

									$chemist3 = ($this->getCell($data,$i,33)!='') ? trim($this->getCell($data,$i,33)) : '';

									if(trim($chemist3) != ''){

										$chemist3ID = $this->getChemistIdOrAdd($chemist3,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist3ID)));

									}

								/*}

								else {

									$doctorData['isActive']  	= ($this->getCell($data,$i,35)==0) ? '0' : '1';

									$doctorData['isModify']  	= '1';

									$doctorData['modify_by']  	= $_SESSION['AdminLoginID'];

									$doctorData['modify_date'] 	= date('Y-m-d H:i:s');

									$doctorData['modify_ip'] 	= $_SERVER['REMOTE_ADDR'];

									$this->updateTableData(array('tableName'=>'crm_doctors','tableData'=>$doctorData,'whereColumn'=>'doctor_id='.$doctorID));

								}*/

							}

							else {

								$rowError[] = array(($i+1),"Patchcode (".$patchcode.") is not found !!");

								$rowData[]  = $rowValue[0];

							}

						}

					}

					else {

						$rowError[] = array('',"Column length didn't match, please verify file column header !!");

					}

				}

				else {

					$rowError[] = array('',"No any patch added yet for headquater (".$headquaterData['headquater_name'].") !!");

				}

			}

			else {

				$rowError[] = array('',"Please select headquater !!");

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

				

				if(count($rowData) > 0) {

					// Create a new worksheet, after the default sheet

					$objPHPExcel->createSheet();

					

					// Add some data to the second sheet, resembling some different data types

					$objPHPExcel->setActiveSheetIndex(1);

					//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');

					

					$objPHPExcel->getActiveSheet()->fromArray($rowData, NULL, 'A2');

					

					// Rename 2nd sheet

					$objPHPExcel->getActiveSheet()->setTitle('Error Data Row');

				}

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);					

								

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/xlsx');

				header('Content-Disposition: attachment;filename="error_response.xlsx"');

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

		

		public function UploadDoctorExcelUpdate($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('doctorFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			$rowError = array();

			$rowData  = array();

					

			// Headquarter Detail

			$headquaterID = Class_Encryption::decode($formData['hqt']);

			

			if($headquaterID > 0) {

				// Get All Location Data of Headquater

				$queryField = array('tableName'=>'headquater','tableColumn'=>array('headquater_name','area_id','region_id','zone_id','country_id','bunit_id'),'columnName'=>'headquater_id','columnValue'=>$headquaterID,'returnRow'=>'single');

				$headquaterData = $this->getTableData($queryField);

				

				// Get All City Data

				$getCity = $this->getLocationData(array('tableName'=>'city','tableColumn'=>array('city_id',"UPPER(replace(city_name,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));

				

				// Get All Patch Data

				$getPatch = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id',"UPPER(replace(patch_name,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));

				

				// Get All Patchcode Data

				$getPatchcode = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id',"UPPER(replace(patchcode,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));

				

				// Get All Patchcode City Data

				$getPatchcity = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('city_id',"patch_id"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));

				

				// Get All Patchcode City Data

				$getChemist = $this->getLocationData(array('tableName'=>'crm_chemists','tableColumn'=>array('chemist_id',"UPPER(replace(chemist_name,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$headquaterID));							

						

				// Count Columns of each Row

				$totalColumns = $data->getHighestColumn(); //echo "<pre>";print_r($totalColumns);echo "</pre>";die;	

				if($totalColumns == 'AG') {

					$sheetHeader = $data->rangeToArray('A1:'.$totalColumns.'1');

					$rowData[] = $sheetHeader[0];

					for ($i=1; $i<$k; $i+=1) {

						if ($isFirstRow) {

							$isFirstRow = FALSE;

							continue;

						}

						

						// Get the specified row as an array of all cells value

						$rowValue  = $data->rangeToArray('A'.($i+1).':'.$totalColumns.($i+1)); //echo "<pre>";print_r($totalColumns);echo "</pre>";die;

						

						$doctorData = array();

						$doctorData['business_unit_id'] = $headquaterData['bunit_id'];

						$doctorData['country_id'] 		= 27;//$headquaterData['country_id'];

						$doctorData['zone_id'] 			= $headquaterData['zone_id'];

						$doctorData['region_id'] 		= $headquaterData['region_id'];

						$doctorData['area_id'] 			= $headquaterData['area_id'];

						$doctorData['headquater_id'] 	= $headquaterID;

						

						$cityname  = ($this->getCell($data,$i,7)!='') ? $this->getCell($data,$i,7) : '';

						$patchname = ($this->getCell($data,$i,8)!='') ? $this->getCell($data,$i,8) : '';

						$patchcode = ($this->getCell($data,$i,9)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,9))) : '';

						

						//echo "<pre>";print_r($patchname);echo "</pre>";die;

						if(!empty($patchname) || !empty($patchcode)) {

							$patch_id = (!empty($patchcode) && isset($getPatchcode[$patchcode])) ? $getPatchcode[$patchcode] : 0;

							if($patch_id>0 || !empty($patchname)) {

								if($patch_id>0) {

									$doctorData['patch_id'] = $patch_id;

									$doctorData['city_id'] = isset($getPatchcity[$doctorData['patch_id']]) ? $getPatchcity[$doctorData['patch_id']] : 0;

								}

								else {

									$doctorData['city_id'] = $this->getCityIdOrAdd($cityname,$doctorData);

									$locationTypeID = 0;

									$doctorData['patch_id'] = $this->getPatchIdOrAdd($patchname,$locationTypeID,$doctorData);

								}

								

								$doctorData['doctor_name'] = ($this->getCell($data,$i,12)!='') ? trim($this->getCell($data,$i,12)) : '';

								$doctorData['am_headquater_id'] = 0; 

								$doctorData['speciality']  		= $this->getCell($data,$i,13); 

								$doctorData['qualification']  	= $this->getCell($data,$i,14); 

								$doctorData['class']  			= $this->getCell($data,$i,15); 

								$doctorData['visit_frequency']  = $this->getCell($data,$i,16); 

								$doctorData['meeting_day']  	= $this->getCell($data,$i,17); 

								$doctorData['meeting_time']  	= $this->getCell($data,$i,18);

								$doctorData['activity_id']  	= $this->getCell($data,$i,19);												

								$doctorData['gender']  			= $this->getCell($data,$i,20); 

								$doctorData['dob']  			= $this->getCell($data,$i,21); 

								$doctorData['phone']  			= $this->getCell($data,$i,22); 

								$doctorData['mobile']  			= $this->getCell($data,$i,23); 

								$doctorData['email']  			= $this->getCell($data,$i,24); 

								$doctorData['address1']  		= $this->getCell($data,$i,25); 

								$doctorData['address2']  		= $this->getCell($data,$i,26); 

								$doctorData['postcode']  		= $this->getCell($data,$i,27);

								$doctorData['state']  			= $this->getCell($data,$i,28);

								$doctorData['doctor_potential'] = $this->getCell($data,$i,29);												

								$doctorData['remarks']  		= $this->getCell($data,$i,33); 								

								

								// Check Doctor name for given patch

								$select = $this->_db->select()->from('crm_doctors',array('COUNT(1) AS CNT'))

															->where("UPPER(replace(doctor_name,' ',''))='".str_replace(' ','',strtoupper($doctorData['doctor_name']))."' AND patch_id=".$doctorData['patch_id']." AND isActive='1' AND isDelete='0'"); //echo $select->__toString();die;

								$doctorRow = $this->getAdapter()->fetchRow($select); //echo "<pre>";print_r($doctorRow);echo "</pre>";die;

								

								if(trim($doctorRow['CNT']) < 1) {

									$doctorData['doctor_code']  	= $this->makeDoctorCode();

									$doctorData['org_svl_number']  	= $this->makeDoctorSVL();

									$doctorData['svl_number']  		= $this->makeDoctorSVL(array('headquarterID'=>$headquaterID));

									$doctorData['create_type']  	= '2';

									$doctorData['created_by']  		= $_SESSION['AdminLoginID']; 

									$doctorData['created_ip'] 	 	= $_SERVER['REMOTE_ADDR'];

									

									if($_SESSION['AdminLoginID']==1) {

										$doctorData['isApproved']    = '1';

										$doctorData['approved_by']   = $_SESSION['AdminLoginID']; 

										$doctorData['approved_date'] = date('Y-m-d h:i:s');

										$doctorData['approved_ip']   = $_SERVER['REMOTE_ADDR'];

									} //echo "<pre>";print_r($doctorData);echo "</pre>";die;

									

									$addDoctorMain = $this->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

									

									$chemist1 = ($this->getCell($data,$i,30)!='') ? trim($this->getCell($data,$i,30)) : '';

									if(trim($chemist1) != ''){

										$chemist1ID = $this->getChemistIdOrAdd($chemist1,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist1ID)));

									}

									

									$chemist2 = ($this->getCell($data,$i,31)!='') ? trim($this->getCell($data,$i,31)) : '';

									if(trim($chemist2) != ''){

										$chemist2ID = $this->getChemistIdOrAdd($chemist2,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist2ID)));

									}

									

									$chemist3 = ($this->getCell($data,$i,32)!='') ? trim($this->getCell($data,$i,32)) : '';

									if(trim($chemist3) != ''){

										$chemist3ID = $this->getChemistIdOrAdd($chemist3,$doctorData);

										$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist3ID)));

									}

								}

								else {

									$doctorData['isModify']  	= '1';

									$doctorData['modify_by']  	= $_SESSION['AdminLoginID'];

									$doctorData['modify_date'] 	= date('Y-m-d H:i:s');

									$doctorData['modify_ip'] 	= $_SERVER['REMOTE_ADDR'];

									

									$this->updateTableData(array('tableName'=>'crm_doctors','tableData'=>$doctorData,'whereColumn'=>'doctor_id='.$doctorRow['doctor_id']));

								}

							}

							else {

								$rowError[] = array(($i+1),"Patchcode ".$patchcode." didn't match !!");

								$rowData[]  = $rowValue[0];

							}

						}

						else {

							$rowError[] = array(($i+1),"Patchname and Patchcode both are empty !!");

							$rowData[]  = $rowValue[0];

						}

					}

				}

				else {

					$rowError[] = array('',"Column length didn't match, please verify file column header !!");

				}

			}

			else {

				$rowError[] = array('',"Please select headquater !!");

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

				

				if(count($rowData) > 0) {

					// Create a new worksheet, after the default sheet

					$objPHPExcel->createSheet();

					

					// Add some data to the second sheet, resembling some different data types

					$objPHPExcel->setActiveSheetIndex(1);

					//$objPHPExcel->getActiveSheet()->setCellValue('A1', 'More data');

					

					$objPHPExcel->getActiveSheet()->fromArray($rowData, NULL, 'A2');

					

					// Rename 2nd sheet

					$objPHPExcel->getActiveSheet()->setTitle('Error Data Row');

				}

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);					

								

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/xlsx');

				header('Content-Disposition: attachment;filename="error_response.xlsx"');

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

		

		/**

		 * Import First time doctor file

		 * Function : firstImportDoctorFile()

		 * This function import doctor file only use for first time. Because first time this function will validate all location data and add patch data into patch table.

		 	Also this function will add chemist data.

		 **/

		public function UploadFirstDoctorFile($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('doctorFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			

			// Count Columns of each Row

			$totalColumns = $data->getHighestColumn();

			

			// Get All Business Unit Data

			$getBusinessUnit = $this->getLocationData(array('tableName'=>'bussiness_unit','tableColumn'=>array('bunit_id',"UPPER(replace(bunit_name,' ',''))")));

			

			// Get All Country Data

			$getCountry = $this->getLocationData(array('tableName'=>'country','tableColumn'=>array('country_id',"UPPER(replace(country_name,' ',''))")));

			

			// Get All Zone Data

			$getZone = $this->getLocationData(array('tableName'=>'zone','tableColumn'=>array('zone_id',"UPPER(replace(zone_name,' ',''))")));

			

			// Get All Region Data

			$getRegion = $this->getLocationData(array('tableName'=>'region','tableColumn'=>array('region_id',"UPPER(replace(region_name,' ',''))")));

			

			// Get All Area Data

			$getArea = $this->getLocationData(array('tableName'=>'area','tableColumn'=>array('area_id',"UPPER(replace(area_name,' ',''))")));

			

			// Get All Headquarter Data

			$getHQ = $this->getLocationData(array('tableName'=>'headquater','tableColumn'=>array('headquater_id',"UPPER(replace(headquater_name,' ',''))")));

			

			// Get All Headquarter Data

			$getLocation = $this->getLocationData(array('tableName'=>'location_types','tableColumn'=>array('location_type_id',"UPPER(replace(location_type_code,' ',''))")));

			

			// Get All City Data

			/*$getCity = array();

			foreach($getHQ as $hq) {

				$getCity[$hq] = $this->getLocationData(array('tableName'=>'city','tableColumn'=>array('city_id',"UPPER(replace(city_name,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$hq));

			}*/

			//echo "<pre>";print_r($getCity);echo "</pre>";die;

	

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

				

				if($totalColumns == 'AH') {

					$doctorData = array();

					$businessUnit = ($this->getCell($data,$i,1)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,1))) : '';

					$doctorData['business_unit_id'] = (!empty($businessUnit) && isset($getBusinessUnit[$businessUnit])) ? $getBusinessUnit[$businessUnit] : 0;

					

					if($doctorData['business_unit_id'] > 0) {

						$country = ($this->getCell($data,$i,2)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,2))) : '';

						$doctorData['country_id'] = (!empty($country) && isset($getCountry[$country])) ? $getCountry[$country] : 0;

						

						if($doctorData['country_id'] > 0) {

							$zone = ($this->getCell($data,$i,3)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,3))) : '';

							$doctorData['zone_id'] = (!empty($zone) && isset($getZone[$zone])) ? $getZone[$zone] : 0;

							

							if($doctorData['zone_id'] > 0) {

								$region = ($this->getCell($data,$i,4)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,4))) : '';

								$doctorData['region_id'] = (!empty($region) && isset($getRegion[$region])) ? $getRegion[$region] : 0;

								

								if($doctorData['region_id'] > 0) {

									$area = ($this->getCell($data,$i,5)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,5))) : '';

									$doctorData['area_id'] = (!empty($area) && isset($getArea[$area])) ? $getArea[$area] : 0;

									

									if($doctorData['area_id'] > 0) {

										$headQrt = ($this->getCell($data,$i,6)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,6))) : '';

										$doctorData['headquater_id'] = (!empty($headQrt) && isset($getHQ[$headQrt])) ? $getHQ[$headQrt] : 0;

										

										if($doctorData['headquater_id'] > 0) {

											/*$city = ($this->getCell($data,$i,7)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,7))) : '';

											$doctorData['city_id'] = (!empty($city) && isset($getCity[$doctorData['headquater_id']][$city])) ? $getCity[$doctorData['headquater_id']][$city] : 0;

											

											if($doctorData['city_id'] > 0) {*/

												$cityName = ($this->getCell($data,$i,7)!='') ? trim($this->getCell($data,$i,7)) : trim($this->getCell($data,$i,6));

												$doctorData['city_id'] = $this->getCityIdOrAdd($cityName,$doctorData);

												

												$patchName = ($this->getCell($data,$i,8)!='') ? trim($this->getCell($data,$i,8)) : '';

												if(!empty($patchName)) {

													$locationType = ($this->getCell($data,$i,9)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,9))) : '';

													$locationTypeID = (!empty($locationType) && isset($getLocation[$locationType])) ? $getLocation[$locationType] : 0;

													$doctorData['patch_id'] = $this->getPatchIdOrAdd($patchName,$locationTypeID,$doctorData);												

													

													$doctorData['doctor_code']  	= $this->makeDoctorCode();

													$doctorData['org_svl_number']  	= $this->makeDoctorSVL();

													$doctorData['svl_number']  		= $this->makeDoctorSVL(array('headquarterID'=>$doctorData['headquater_id']));

													$doctorData['am_headquater_id'] = 0; 

													$doctorData['doctor_name']  	= $this->getCell($data,$i,13); 

													$doctorData['speciality']  		= $this->getCell($data,$i,14); 

													$doctorData['qualification']  	= $this->getCell($data,$i,15); 

													$doctorData['class']  			= $this->getCell($data,$i,16); 

													$doctorData['visit_frequency']  = $this->getCell($data,$i,17); 

													$doctorData['meeting_day']  	= $this->getCell($data,$i,18); 

													$doctorData['meeting_time']  	= $this->getCell($data,$i,19);

													$doctorData['activity_id']  		= $this->getCell($data,$i,20);												

													$doctorData['gender']  			= $this->getCell($data,$i,21); 

													$doctorData['dob']  			= $this->getCell($data,$i,22); 

													$doctorData['phone']  			= $this->getCell($data,$i,23); 

													$doctorData['mobile']  			= $this->getCell($data,$i,24); 

													$doctorData['email']  			= $this->getCell($data,$i,25); 

													$doctorData['address1']  		= $this->getCell($data,$i,26); 

													$doctorData['address2']  		= $this->getCell($data,$i,27); 

													$doctorData['postcode']  		= $this->getCell($data,$i,28);

													$doctorData['state']  			= $this->getCell($data,$i,29);

													$doctorData['doctor_potential'] = $this->getCell($data,$i,30);												

													$doctorData['remarks']  		= $this->getCell($data,$i,34);

													$doctorData['create_type']  	= '2';

													$doctorData['created_by']  		= $_SESSION['AdminLoginID']; 

													$doctorData['created_ip'] 	 	= $_SERVER['REMOTE_ADDR'];

													

													if($_SESSION['AdminLoginID']==1) {

														$doctorData['isApproved']    = '1';

														$doctorData['approved_by']   = $_SESSION['AdminLoginID']; 

														$doctorData['approved_date'] = date('Y-m-d h:i:s');

														$doctorData['approved_ip']   = $_SERVER['REMOTE_ADDR'];

													} //echo "<pre>";print_r($doctorData);echo "</pre>";die;

													

													$addDoctorMain = $this->addDoctorData(array('tableName'=>'crm_doctors','tableData'=>$doctorData));

													

													$chemist1 = ($this->getCell($data,$i,31)!='') ? trim($this->getCell($data,$i,31)) : '';

													if(trim($chemist1) != ''){

														$chemist1ID = $this->getChemistIdOrAdd($chemist1,$doctorData);

														$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist1ID)));

													}

													

													$chemist2 = ($this->getCell($data,$i,32)!='') ? trim($this->getCell($data,$i,32)) : '';

													if(trim($chemist2) != ''){

														$chemist2ID = $this->getChemistIdOrAdd($chemist2,$doctorData);

														$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist2ID)));

													}

													

													$chemist3 = ($this->getCell($data,$i,33)!='') ? trim($this->getCell($data,$i,33)) : '';

													if(trim($chemist3) != ''){

														$chemist3ID = $this->getChemistIdOrAdd($chemist3,$doctorData);

														$this->_db->insert('crm_doctor_chemists',array_filter(array('doctor_id'=>$addDoctorMain,'chemist_id'=>$chemist3ID)));

													}

												}

												else {

													$rowError[] = array(($i+1),"Patch Name (".$patchName.") should not blank !!");

													$rowData[]  = $rowValue[0];

												}

											/*}

											else {

												$rowError[] = array(($i+1),"City (".$city.") not found !!");

												$rowData[]  = $rowValue[0];

											}*/

										}

										else {

											$rowError[] = array(($i+1),"Headquarter (".$headQrt.") not found !!");

											$rowData[]  = $rowValue[0];

										}

									}

									else {

										$rowError[] = array(($i+1),"Area (".$area.") not found !!");

										$rowData[]  = $rowValue[0];

									}

								}

								else {

									$rowError[] = array(($i+1),"Region (".$region.") not found !!");

									$rowData[]  = $rowValue[0];

								}

							}

							else {

								$rowError[] = array(($i+1),"Zone (".$zone.") not found !!");

								$rowData[]  = $rowValue[0];

							}

						}

						else {

							$rowError[] = array(($i+1),"Country (".$country.") not found !!");

							$rowData[]  = $rowValue[0];

						}

					}

					else {

						$rowError[] = array(($i+1),"Business Unit (".$businessUnit.") not found !!");

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

				header('Content-Type: application/xlsx');

				header('Content-Disposition: attachment;filename="error_response.xlsx"');

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

		

		public function getCityIdOrAdd($cityName,$data=array())

		{

			$cityData['city_name']  	= (isset($cityName) && !empty($cityName)) ? trim($cityName) : '0';

			$cityData['headquater_id'] = (isset($data['headquater_id']) && !empty($data['headquater_id'])) ? trim(strtoupper($data['headquater_id'])) : '0';

			$cityData['area_id']  		= (isset($data['area_id']) && !empty($data['area_id'])) ? trim(strtoupper($data['area_id'])) : '0';

			$cityData['region_id']  	= (isset($data['region_id']) && !empty($data['region_id'])) ? trim(strtoupper($data['region_id'])) : '0';

			$cityData['zone_id']  		= (isset($data['zone_id']) && !empty($data['zone_id'])) ? trim(strtoupper($data['zone_id'])) : '0';

			$cityData['country_id']  	= (isset($data['country_id']) && !empty($data['country_id'])) ? trim(strtoupper($data['country_id'])) : '0';

			$cityData['bunit_id']  	= (isset($data['business_unit_id']) && !empty($data['business_unit_id'])) ? trim(strtoupper($data['business_unit_id'])) : '0';

			//echo "<pre>";print_r($cityData);echo "</pre>";die;

			

			$query = $this->_db->select()->from('city','city_id')->where('headquater_id='.$cityData['headquater_id'])->where("UPPER(replace(city_name,' ','')) LIKE '".str_replace(' ','',strtoupper($cityData['city_name']))."%'")->limit(1); //echo $query->__toString();die;

			$patchRow = $this->getAdapter()->fetchRow($query);

			$cityID  = (isset($patchRow['city_id'])) ? $patchRow['city_id'] : 0;

			if($cityID<=0) {

				$lastcity = $this->getTableAutoIncrement(array('tableName'=>'city'));

				$citycode = ($lastcity+1);

				$cityData['location_code'] = 'C'.(int)$citycode;

				$cityData['added_through'] = 3;

				$cityData['created_by']  	= $_SESSION['AdminLoginID']; 

				$cityData['created_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($cityData);echo "</pre>";die;

				if(trim($cityData['city_name']) != '') {

					$cityID = ($this->_db->insert('city',array_filter($cityData))) ? $this->_db->lastInsertId() : 0;

				}

			}

			

			return $cityID;

		}

		

		public function getPatchIdOrAdd($patchName,$locationTypeID=0,$data=array())

		{

			$patchData['patch_name']  	= (isset($patchName) && !empty($patchName)) ? trim($patchName) : '0';

			$patchData['location_type_id'] = (isset($locationTypeID) && !empty($locationTypeID)) ? trim($locationTypeID) : '0';

			$patchData['city_id'] 		= (isset($data['city_id']) && !empty($data['city_id'])) ? trim(strtoupper($data['city_id'])) : '0';

			$patchData['headquater_id'] = (isset($data['headquater_id']) && !empty($data['headquater_id'])) ? trim(strtoupper($data['headquater_id'])) : '0';

			$patchData['area_id']  		= (isset($data['area_id']) && !empty($data['area_id'])) ? trim(strtoupper($data['area_id'])) : '0';

			$patchData['region_id']  	= (isset($data['region_id']) && !empty($data['region_id'])) ? trim(strtoupper($data['region_id'])) : '0';

			$patchData['zone_id']  		= (isset($data['zone_id']) && !empty($data['zone_id'])) ? trim(strtoupper($data['zone_id'])) : '0';

			$patchData['country_id']  	= (isset($data['country_id']) && !empty($data['country_id'])) ? trim(strtoupper($data['country_id'])) : '0';

			$patchData['bunit_id']  	= (isset($data['business_unit_id']) && !empty($data['business_unit_id'])) ? trim(strtoupper($data['business_unit_id'])) : '0';

			//echo "<pre>";print_r($patchData);echo "</pre>";die;

			

			$query = $this->_db->select()->from('patchcodes','patch_id')->where('city_id='.$patchData['city_id'])->where("UPPER(replace(patch_name,' ','')) LIKE '".str_replace(' ','',strtoupper($patchData['patch_name']))."%' AND isActive='1' AND isDelete='0'")->limit(1); //echo $query->__toString();die;

			$patchRow = $this->getAdapter()->fetchRow($query);

			$patchID  = (isset($patchRow['patch_id'])) ? $patchRow['patch_id'] : 0;

			if($patchID<=0) {

				$lastpatch = $this->makePatchCode(array('headquarterID'=>$patchData['headquater_id']));

				$patchcode = ($lastpatch+1);

				$patchData['patchcode'] = 'P'.(int)$patchcode;

				$patchData['added_through'] = 3;

				$patchData['created_by']  	= $_SESSION['AdminLoginID']; 

				$patchData['created_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($patchData);echo "</pre>";die;

				if(trim($patchData['patch_name']) != '') {

					$patchID = ($this->_db->insert('patchcodes',array_filter($patchData))) ? $this->_db->lastInsertId() : 0;

				}

			}

			

			return $patchID;

		}

		

		public function getChemistIdOrAdd($chemistName,$data=array())

		{

			$chemistData['chemist_name']  	= (isset($chemistName) && !empty($chemistName)) ? trim($chemistName) : '0';

			$chemistData['patch_id'] 		= (isset($data['patch_id']) && !empty($data['patch_id'])) ? trim(strtoupper($data['patch_id'])) : '0';

			$chemistData['city_id'] 		= (isset($data['city_id']) && !empty($data['city_id'])) ? trim(strtoupper($data['city_id'])) : '0';

			$chemistData['headquater_id'] = (isset($data['headquater_id']) && !empty($data['headquater_id'])) ? trim(strtoupper($data['headquater_id'])):'0';

			$chemistData['area_id']  		= (isset($data['area_id']) && !empty($data['area_id'])) ? trim(strtoupper($data['area_id'])) : '0';

			$chemistData['region_id']  	= (isset($data['region_id']) && !empty($data['region_id'])) ? trim(strtoupper($data['region_id'])) : '0';

			$chemistData['zone_id']  		= (isset($data['zone_id']) && !empty($data['zone_id'])) ? trim(strtoupper($data['zone_id'])) : '0';

			$chemistData['country_id']  	= (isset($data['country_id']) && !empty($data['country_id'])) ? trim(strtoupper($data['country_id'])) : '0';

			$chemistData['bunit_id']  	= (isset($data['business_unit_id']) && !empty($data['business_unit_id'])) ? trim(strtoupper($data['business_unit_id'])) : '0';

			//echo "<pre>";print_r($chemistData);echo "</pre>";die;

			

			$query = $this->_db->select()->from('crm_chemists','chemist_id')->where('patch_id='.$chemistData['patch_id'])->where("UPPER(replace(chemist_name,' ','')) LIKE '".str_replace(' ','',strtoupper($chemistData['chemist_name']))."%'")->limit(1); //echo $query->__toString();die;

			$chemistRow = $this->getAdapter()->fetchRow($query);

			$chemistID  = (isset($chemistRow['chemist_id'])) ? $chemistRow['chemist_id'] : 0;

			if($chemistID<=0) {

				$lastLegacyCode = $this->makeChemistLegacyCode(array('patchID'=>$chemistData['patch_id']));

				$LegacyCode = ($lastLegacyCode+1);

				$chemistData['legacy_code'] = 'LC'.(int)$LegacyCode;

				$chemistData['added_through'] = 2;

				$chemistData['created_by']  	= $_SESSION['AdminLoginID']; 

				$chemistData['created_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($chemistData);echo "</pre>";die;

				if(trim($chemistData['chemist_name']) != '') {

					$chemistID = ($this->_db->insert('crm_chemists',array_filter($chemistData))) ? $this->_db->lastInsertId() : 0;

				}

			}

			

			return $chemistID;

		}

		

		public function makeChemistLegacyCode($data=array())

		{

			$patchID = (isset($data['patchID']) && trim($data['patchID'])>0) ? trim($data['patchID']) : 0;

			$query = $this->_db->select()->from('crm_chemists',array('CNT'=>'COUNT(1)'))->where('patch_id='.$patchID);//echo $query->__toString();die;

			$lastCode = $this->getAdapter()->fetchRow($query);

			return (int) $lastCode['CNT'];

		}

		

		public function makeDoctorSVL($data=array())

		{

			$headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : 0;

			$column = "org_svl_number";

			$where = "1";

			if($headquarterID > 0) {

				$column = "svl_number";

				$where .= " AND headquater_id=".$headquarterID;

			}

			

			$query = $this->_db->select()->from('crm_doctors',array('CNT'=>'COUNT(1)'))->where($where);//echo $query->__toString();die;

			$lastRow = $this->getAdapter()->fetchRow($query);

			$svlCode = (int) $lastRow['CNT'];

			return ($svlCode+1);

		}

		

		public function getIDbyName($data=array())

		{

			$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';

			$tableColumn = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');

			$returnRow   = (isset($data['returnRow'])   && !empty($data['returnRow']))    ? $data['returnRow']       : 'single';

			

			$where = '1';

			if(isset($data['columnName']) && isset($data['columnValue'])) {

				$where .=  " AND UPPER(".$data['columnName'].") LIKE '".strtoupper($data['columnValue'])."%'";

			}

			if(isset($data['columnName1']) && isset($data['columnValue1'])) {

				$where .=  " AND ".$data['columnName1']."='".$data['columnValue1']."'";

			}

			if(isset($data['columnName2']) && isset($data['columnValue2'])) {

				$where .=  " AND ".$data['columnName2']."='".$data['columnValue2']."'";

			}

			if(isset($data['columnName3']) && isset($data['columnValue3'])) {

				$where .=  " AND ".$data['columnName3']."='".$data['columnValue3']."'";

			}

			if(isset($data['columnName4']) && isset($data['columnValue4'])) {

				$where .=  " AND ".$data['columnName4']."='".$data['columnValue4']."'";

			}

			

			$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //echo $select->__toString();die;

			//if($tableName=='patchcodes'){ echo $select->__toString();die; }

			return ($returnRow=='single') ? $this->getAdapter()->fetchRow($select) : $this->getAdapter()->fetchAll($select);

		}

	

		public function getCell(&$worksheet,$row,$col,$default_val='')

		{

			$col -= 1; // we use 1-based, PHPExcel uses 0-based column index

			$row += 1; // we use 0-based, PHPExcel used 1-based row index

			return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;

		}

	

		public function getTableData($data=array())

		{

			$tableName   = (isset($data['tableName'])   && !empty($data['tableName']))    ? trim($data['tableName']) : '';

			$tableColumn = (isset($data['tableColumn']) && count($data['tableColumn'])>0) ? $data['tableColumn']     : array('*');

			$returnRow   = (isset($data['returnRow'])   && !empty($data['returnRow']))    ? $data['returnRow']       : 'single';

			

			$where = '1';

			if(isset($data['columnName']) && isset($data['columnValue'])) {

				$where .=  " AND ".$data['columnName']."='".$data['columnValue']."'";

			}

			if(isset($data['columnName1']) && isset($data['columnValue1'])) {

				$where .=  " AND ".$data['columnName1']."='".$data['columnValue1']."'";

			}

			

			$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //echo $select->__toString();die;

			return ($returnRow=='single') ? $this->getAdapter()->fetchRow($select) : $this->getAdapter()->fetchAll($select);

		}

	

		/**

		 * Patch Details

		 * Function : getPatch()

		 * This function return patch details with location value till City

		 **/

		public function getPatch($data=array())

		{

		 	$where = 1;

			if(isset($data['headtoken']) && (int)$data['headtoken']>0) {

				$where .= ' AND PC.headquater_id='.$data['headtoken'];

			}

			else if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadquarters($_SESSION['AdminLoginID']);

				$where = 'PC.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			else if (isset($data['token']) && trim($data['token']) > 0) {

				$where = 'PC.patch_id='.trim($data['token']);

			}

			

			$select = $this->_db->select()

		 				->from(array('PC'=>'patchcodes'),array('PC.patch_id','PC.patchcode','PC.patch_name','PC.isActive'))

						->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array('city_name'))

						->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array('headquater_name'))

		 				->joininner(array('AT'=>'area'),"AT.area_id=PC.area_id",array('area_name'))

						->joininner(array('RT'=>'region'),"RT.region_id=PC.region_id",array('region_name'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=PC.zone_id",array('zone_name'))

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PC.bunit_id",array('bunit_name'))

						->where($where)

						->where('PC.isActive','1')

						->where('PC.isDelete','0')

						->order('PC.headquater_id')

						->order('PC.patch_id'); //echo $select->__toString();die;

		    $result = $this->getAdapter()->fetchAll($select);

			return $result; 			

		}

		

		public function getPatchList($data=array())

		{

			try {

			$where = "PC.isActive='1' AND PC.isDelete='0'";

			if(isset($data['headtoken']) && (int)$data['headtoken']>0) {

				$where .= ' AND PC.headquater_id='.$data['headtoken'];

			}

			else if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadquarters($_SESSION['AdminLoginID']);

				$where = 'PC.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			else if (isset($data['token']) && trim($data['token']) > 0) {

				$where = 'PC.patch_id='.trim($data['token']);

			}

				

				//Order

				$orderlimit = CommonFunction::OdrderByAndLimit($data,'PC.headquater_id');

				

				$countQuery = $this->_db->select()

								->from(array('PC'=>'patchcodes'),array('COUNT(1) AS CNT'))

								->joininner(array('CT'=>'city'),"CT.city_id=PC.city_id",array())

								->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array())

								->joininner(array('AT'=>'area'),"AT.area_id=PC.area_id",array())

								->joininner(array('RT'=>'region'),"RT.region_id=PC.region_id",array())

								->joininner(array('ZT'=>'zone'),"ZT.zone_id=PC.zone_id",array())

								->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PC.bunit_id",array())

								->where($where)

								->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;

				$total = $this->getAdapter()->fetchAll($countQuery);

				

				$select = $this->_db->select()

							->from(array('PC'=>'patchcodes'),array('PC.patch_id','PC.patchcode','PC.patch_name','PC.fair','PC.isActive'))

							->joininner(array('CT'=>'city'),"CT.city_id=PC.city_id",array('CT.city_name'))

							->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array('HQ.headquater_name'))

							->joininner(array('AT'=>'area'),"AT.area_id=PC.area_id",array('AT.area_name'))

							->joininner(array('RT'=>'region'),"RT.region_id=PC.region_id",array('RT.region_name'))

							->joininner(array('ZT'=>'zone'),"ZT.zone_id=PC.zone_id",array('ZT.zone_name'))

							->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PC.bunit_id",array('BU.bunit_name'))

							->where($where)

							->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])

							->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;

				$result = $this->getAdapter()->fetchAll($select);

				return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']); 

			}

			catch(Exception $e){

				$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 

			}			

		}

		

		public function getHeadquarters($loggedIn)

		{

			$userLocation = $this->getUserHeadquarter($loggedIn);//print_r($userLocation);die;

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

						$this->getHeadquarters($child['user_id']);

					}

					else {

						$this->_userIDs[] 	   = $child['user_id'];

						$this->_headquarters[] = $child['headquater_id'];

					}

				}

			}

			$this->_headquarters = array_filter($this->_headquarters);

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

						$this->getHeadquarters($child['user_id']);

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

	

		/**

		 * Export Patch Header

		 * Function : ExportPatchHeader()

		 * This function return parch same file with file header and location value till Headquarter

		 **/

		public function ExportPatchHeader($data)

		{

			try{

				$headquarterID = (isset($data['headtoken']) && trim($data['headtoken'])>0) ? trim($data['headtoken']) : 0;

				$exportData = $this->getHeadQuaters(array('headquarterID'=>$headquarterID)); //echo "<pre>";print_r($exportData);echo "</pre>";die;

				$_nxtcol = "\t";

				$_nxtrow = "\n";

				

				// Sheet Header

				$Header .= 	"\"BU \"".$_nxtcol.

							"\"Zone \"".$_nxtcol.

							"\"Region \"".$_nxtcol.

							"\"Area \"".$_nxtcol.

							"\"Headquarter \"".$_nxtcol.

							"\"City \"".$_nxtcol.

							"\"Patch Name \"".$_nxtcol;

				$Header .= $_nxtrow;

			

				$totalRowData = count($exportData);

				if($totalRowData>0) {

					// Report Sheet Row Data

					foreach($exportData as $index=>$rowData)

					{

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['bunit_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['zone_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['region_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['area_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['headquater_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", ''). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", ''). "\"" . $_nxtcol;

						$Header .= $_nxtrow;					

					}

				}

				else {

					$Header .= 	"\" No Data Found!! \"".$_nxtcol;

				}

						

				CommonFunction::ExportCsv($Header,'Patch_Sample','xls');

			}

			catch(Exception $e){

			   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  

			}		  

		}

		

		public function UploadPatchFileOld($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('patchFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			

			// Count Columns of each Row

			$totalColumns = $data->getHighestColumn();

			

			// Get Location Data according to given Headquarter

			$headquarterID   = (isset($formData['headtoken']) && trim($formData['headtoken'])>0) ? trim($formData['headtoken']) : 0;

			$headquarterData = $this->getHeadQuaters(array('headquarterID'=>$headquarterID));

			$lastpatch 		 = $this->makePatchCode(array('headquarterID'=>$headquarterID));

			$getAllCity	 	 = $this->getAllCity(array('hqID'=>$headquarterID)); //echo "<pre>";print_r($getAllCity);echo "</pre>";die;

	

			$j=1;

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

				$rowValue  = $data->rangeToArray('A'.($i+1).':'.$totalColumns.($i+1)); //echo "<pre>";print_r($rowData);echo "</pre>";die;

				

				if($totalColumns == 'G') {

					$cityName = $this->getCell($data,$i,6);					

					$getCityID = (!empty($cityName)) ? $getAllCity[strtoupper($cityName)] : $getAllCity[strtoupper($headquarterData[0]['headquater_name'])];

					//echo "<pre>";print_r($getCityID);echo "</pre>";die;

					

					if($getCityID > 0) {

						$patchcode = ($lastpatch+$j);

						$patchData['patchcode']  	= 'P'.(int)$patchcode;

						$patchData['patch_name']  	= $this->getCell($data,$i,7);

						$patchData['city_id'] 		= $getCityID;

						$patchData['headquater_id'] = $headquarterID;

						$patchData['area_id']  		= $headquarterData[0]['area_id']; 

						$patchData['zone_id']  		= $headquarterData[0]['zone_id']; 

						$patchData['region_id']  	= $headquarterData[0]['region_id'];

						$patchData['country_id']  	= $headquarterData[0]['country_id'];

						$patchData['bunit_id']  	= $headquarterData[0]['bunit_id']; 

						$patchData['added_through'] = 2;

						$patchData['created_by']  	= $_SESSION['AdminLoginID']; 

						$patchData['created_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($patchData);echo "</pre>";die;

						

						if(trim($patchData['patch_name']) != '') {

							$addPatch = $this->_db->insert('patchcodes',array_filter($patchData));

						}

						$j++;

					}

					else {

						$rowError[] = array(($i+1),"City didn't match !!");

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

				header('Content-Type: application/xlsx');

				header('Content-Disposition: attachment;filename="error_response.xlsx"');

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

		

		public function UploadPatchFile($formData)

		{

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('patchFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			

			// Count Columns of each Row

			$totalColumns = $data->getHighestColumn();

			

			// Get All Business Unit Data

			$getBusinessUnit = $this->getLocationData(array('tableName'=>'bussiness_unit','tableColumn'=>array('bunit_id','UPPER(bunit_name)')));

			

			// Get All Country Data

			$getCountry = $this->getLocationData(array('tableName'=>'country','tableColumn'=>array('country_id',"UPPER(replace(country_name,' ',''))")));

			

			// Get All Zone Data

			//$getZone = $this->getLocationData(array('tableName'=>'zone','tableColumn'=>array('zone_id',"UPPER(replace(zone_name,' ',''))")));

			$getZone = array();

			foreach($getBusinessUnit as $bu) {

				$getZone[$bu] = $this->getLocationData(array('tableName'=>'zone','tableColumn'=>array('zone_id',"UPPER(replace(zone_name,' ',''))"),'columnName'=>'bunit_id','columnValue'=>$bu));

			}

			

			// Get All Region Data

			//$getRegion = $this->getLocationData(array('tableName'=>'region','tableColumn'=>array('region_id',"UPPER(replace(region_name,' ',''))")));

			$getRegion = array();

			foreach($getZone as $zone) {

				foreach($zone as $zoneID) {

					$getRegion[$zoneID] = $this->getLocationData(array('tableName'=>'region','tableColumn'=>array('region_id',"UPPER(replace(region_name,' ',''))"),'columnName'=>'zone_id','columnValue'=>$zoneID));

				}

			}

			

			// Get All Area Data

			//$getArea = $this->getLocationData(array('tableName'=>'area','tableColumn'=>array('area_id',"UPPER(replace(area_name,' ',''))")));

			$getArea = array();

			foreach($getRegion as $region) {

				if(count($region)>0) {

					foreach($region as $regionID) {

						$getArea[$regionID] = $this->getLocationData(array('tableName'=>'area','tableColumn'=>array('area_id',"UPPER(replace(area_name,' ',''))"),'columnName'=>'region_id','columnValue'=>$regionID));

					}

				}

			}

			

			// Get All Headquarter Data

			//$getHQ = $this->getLocationData(array('tableName'=>'headquater','tableColumn'=>array('headquater_id',"UPPER(replace(headquater_name,' ',''))")));

			$getHQ = array();

			foreach($getArea as $area) {

				if(count($area)>0) {

					foreach($area as $areaID) {

						$getHQ[$areaID] = $this->getLocationData(array('tableName'=>'headquater','tableColumn'=>array('headquater_id',"UPPER(replace(headquater_name,' ',''))"),'columnName'=>'area_id','columnValue'=>$areaID));

					}

				}

			}

			

			// Get All City Data

			$getCity = array();

			foreach($getHQ as $hq) {

				if(count($hq)>0) {

					foreach($hq as $hqID) {

						$getCity[$hqID] = $this->getLocationData(array('tableName'=>'city','tableColumn'=>array('city_id',"UPPER(replace(city_name,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$hqID));

					}

				}

			}

			

			// Get All Location Type Data

			$getLocation = $this->getLocationData(array('tableName'=>'location_types','tableColumn'=>array('location_type_id',"UPPER(replace(location_type_code,' ',''))")));

			//echo "<pre>";print_r($getCity);echo "</pre>";die;

	

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

				

				if($totalColumns == 'I' || $totalColumns == 'J') {

					$patchData = array();

					$businessUnit = ($this->getCell($data,$i,1)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,1))) : '';

					$patchData['bunit_id'] = (!empty($businessUnit) && isset($getBusinessUnit[$businessUnit])) ? $getBusinessUnit[$businessUnit] : 0;

					

					if($patchData['bunit_id'] > 0) {

						$country = ($this->getCell($data,$i,2)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,2))) : '';

						$patchData['country_id'] = (!empty($country) && isset($getCountry[$country])) ? $getCountry[$country] : 0;

						

						if($patchData['country_id'] > 0) {

							$zone = ($this->getCell($data,$i,3)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,3))) : '';

							$patchData['zone_id'] = (!empty($zone) && isset($getZone[$patchData['bunit_id']][$zone])) ? $getZone[$patchData['bunit_id']][$zone] : 0;

							

							if($patchData['zone_id'] > 0) {

								$region = ($this->getCell($data,$i,4)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,4))) : '';

								$patchData['region_id'] = (!empty($region) && isset($getRegion[$patchData['zone_id']][$region])) ? $getRegion[$patchData['zone_id']][$region] : 0;

								

								if($patchData['region_id'] > 0) {

									$area = ($this->getCell($data,$i,5)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,5))) : '';

									$patchData['area_id'] = (!empty($area) && isset($getArea[$patchData['region_id']][$area])) ? $getArea[$patchData['region_id']][$area] : 0;

									

									if($patchData['area_id'] > 0) {

										$headQrt = ($this->getCell($data,$i,6)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,6))) : '';

										$patchData['headquater_id'] = (!empty($headQrt) && isset($getHQ[$patchData['area_id']][$headQrt])) ? $getHQ[$patchData['area_id']][$headQrt] : 0;

										

										if($patchData['headquater_id'] > 0) {

											$city = ($this->getCell($data,$i,7)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,7))) : '';

											$patchData['city_id'] = (!empty($city) && isset($getCity[$patchData['headquater_id']][$city])) ? $getCity[$patchData['headquater_id']][$city] : 0;

											

											if($patchData['city_id'] > 0) {

												$patchData['patch_name'] = ($this->getCell($data,$i,8)!='') ? trim($this->getCell($data,$i,8)) : '';

												$patch = ($this->getCell($data,$i,8)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,8))) : '';

												$patchID = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id'),'columnName'=>'city_id','columnValue'=>$patchData['city_id'],'columnName1'=>"UPPER(replace(patch_name,' ',''))",'columnValue1'=>$patch));

												

												if(!empty($patch) && count($patchID) < 1) {

													$locationType = ($this->getCell($data,$i,9)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,9))) : '';

													$patchData['location_type_id'] = (!empty($locationType) && isset($getLocation[$locationType])) ? $getLocation[$locationType] : 0;

													$lastpatch = $this->makePatchCode(array('headquarterID'=>$patchData['headquater_id']));

													$patchcode = ($lastpatch+1);

													$patchData['patchcode'] = 'P'.(int)$patchcode;

													$patchData['added_through'] = 3;

													$patchData['created_by']  	= $_SESSION['AdminLoginID']; 

													$patchData['created_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($patchData);echo "</pre>";die;

													$this->_db->insert('patchcodes',array_filter($patchData));

												}

												else {

													$rowError[] = array(($i+1),"Patch Name (".$patchData['patch_name'].") already added or should not blank !!");

													$rowData[]  = $rowValue[0];

												}

											}

											else {

												$rowError[] = array(($i+1),"City (".$city.") not found !!");

												$rowData[]  = $rowValue[0];

											}

										}

										else {

											$rowError[] = array(($i+1),"Headquarter (".$headQrt.") not found !!");

											$rowData[]  = $rowValue[0];

										}

									}

									else {

										$rowError[] = array(($i+1),"Area (".$area.") not found !!");

										$rowData[]  = $rowValue[0];

									}

								}

								else {

									$rowError[] = array(($i+1),"Region (".$region.") not found !!");

									$rowData[]  = $rowValue[0];

								}

							}

							else {

								$rowError[] = array(($i+1),"Zone (".$zone.") not found !!");

								$rowData[]  = $rowValue[0];

							}

						}

						else {

							$rowError[] = array(($i+1),"Country (".$country.") not found !!");

							$rowData[]  = $rowValue[0];

						}

					}

					else {

						$rowError[] = array(($i+1),"Business Unit (".$businessUnit.") not found !!");

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

				header('Content-Type: application/xlsx');

				header('Content-Disposition: attachment;filename="error_response.xlsx"');

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

		

		public function getAllCity($data=array())

		{

			$hqID  = (isset($data['hqID']) && !empty($data['hqID'])) ? trim($data['hqID']) : '0';

			$query = $this->_db->select()->from('city',array('city_id','city_name'))->where('headquater_id='.$hqID); //echo $query->__toString();die;

			$allCity = array();

			$cities  = $this->getAdapter()->fetchAll($query);

			if(count($cities)>0) {

				foreach($cities as $city) {

					$allCity[strtoupper($city['city_name'])] = $city['city_id'];

				}

			}

			return $allCity;

		}

		

		public function getCityID($data=array())

		{

			$cityName = (isset($data['city']) && !empty($data['city'])) ? trim(strtoupper($data['city'])) : '';

			$hqID     = (isset($data['hqID']) && !empty($data['hqID'])) ? trim($data['hqID']) : '0';

			$query = $this->_db->select()->from('city','city_id')->where('headquater_id='.$hqID)->where('UPPER(city_name) LIKE "'.$cityName.'%"')->limit(1);

			//echo $query->__toString();die;

			$lastCode = $this->getAdapter()->fetchRow($query);

			return (int) $lastCode['city_id'];

		}

		

		public function makePatchCode($data=array())

		{

			$headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : 0;

			$query = $this->_db->select()->from('patchcodes',array('CNT'=>'COUNT(1)'))->where('headquater_id='.$headquarterID);//echo $query->__toString();die;

			$lastCode = $this->getAdapter()->fetchRow($query);

			return (int) $lastCode['CNT'];

		}

		

		/**

		 * Export Patch Header

		 * Function : ExportPatchHeader()

		 * This function return parch same file with file header and location value till Headquarter

		 **/

		public function ExportPatchData($data)

		{

			try{

				$where = 1;

				if(isset($data['headtoken']) && (int)$data['headtoken']>0) {

					$where .= ' AND HQ.headquater_id='.$data['headtoken'];

				}

				else if ($_SESSION['AdminLevelID'] != 1) {

					$this->getHeadquarters($_SESSION['AdminLoginID']);

					$where = 'HQ.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

				}

				

				$query = $this->_db->select()

						 ->from(array('HQ'=>'headquater'),array('HQ.headquater_id','HQ'=>'HQ.headquater_name'))

						 ->joininner(array('AT'=>'area'),"AT.area_id=HQ.area_id",array('Area'=>'AT.area_name'))

						 ->joininner(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('Region'=>'RT.region_name'))

						 ->joininner(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('Zone'=>'ZT.zone_name'))

						 ->joinleft(array('CT'=>'country'),"CT.country_id=ZT.country_id",array('Country'=>'if(CT.country_id>0,CT.country_name,"India")'))

						 ->joininner(array('BT'=>'bussiness_unit'),"BT.bunit_id=HQ.bunit_id",array('BU'=>'BT.bunit_name'))

						 ->where($where)

						 ->order("HQ.headquater_name","ASC"); //echo $query->__toString();die;

				$headquarters = $this->getAdapter()->fetchAll($query);

				$totalRowData = count($headquarters);

				if($totalRowData>0) {

					ini_set("memory_limit","512M");

					ini_set("max_execution_time",180);

					ob_end_clean();

					$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;

					

					foreach($headquarters as $index=>$hq)

					{

						if($index>0)

						{

							// Create a new worksheet, after the default sheet

							$objPHPExcel->createSheet();

						}

						// Create a first sheet, representing sales data

						$objPHPExcel->setActiveSheetIndex($index);

						

						$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Business Unit')

													  ->setCellValue('B1', 'Country')

													  ->setCellValue('C1', 'Zone')

													  ->setCellValue('D1', 'Region')

													  ->setCellValue('E1', 'Area')

													  ->setCellValue('F1', 'Headquarter')

													  ->setCellValue('G1', 'City')

													  ->setCellValue('H1', 'Patch')

													  ->setCellValue('I1', 'Location Type')

													  ->setCellValue('J1', 'Patch Code')

													  ->setCellValue('K1', 'Fair');

						// Row Formatting

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

						

						$sql = $this->_db->select()

								 ->from(array('PC'=>'patchcodes'),array('PC.patch_id','PC.city_id','PC.location_type_id','PC.patch_name','PC.patchcode','PC.fair'))

								 ->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array('CName'=>'CT.city_name'))

								 ->joinleft(array('LT'=>'location_types'),"LT.location_type_id=PC.location_type_id",array('Type'=>'LT.location_type_code'))

								 ->where('PC.headquater_id='.$hq['headquater_id'])

								 ->where('PC.isActive','1')

								 ->where('PC.isDelete','0')

								 ->order("PC.city_id DESC"); //echo $sql->__toString();die;

						$cityPatches = $this->getAdapter()->fetchAll($sql); //echo "<pre>";print_r($cityPatches);die;

						$totalCityPatchData = count($cityPatches);

						$hqInfo = array();

						if($totalCityPatchData>0){

							foreach($cityPatches as $cityPatch){

								$hqInfo[] = array($hq['BU'],$hq['Country'],$hq['Zone'],$hq['Region'],$hq['Area'],$hq['HQ'],$cityPatch['CName'],$cityPatch['patch_name'],$cityPatch['Type'],$cityPatch['patchcode'],$cityPatch['fair']);

							}

						}

						else {

							$hqInfo[] = array($hq['BU'],$hq['Country'],$hq['Zone'],$hq['Region'],$hq['Area'],$hq['HQ']);

						}						

						

						$objPHPExcel->getActiveSheet()->fromArray($hqInfo, NULL, 'A2');

						

						// Rename sheet

						$objPHPExcel->getActiveSheet()->setTitle($hq['HQ']);

						

						// Set active sheet index to the first sheet, so Excel opens this as the first sheet

						$objPHPExcel->setActiveSheetIndex(0);					

					}

										

					// Redirect output to a client’s web browser (Excel5)

					header('Content-Type: application/xlsx');

					header('Content-Disposition: attachment;filename="Patch_Data.xlsx"');

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

		

		public function ExportPatchDataOld($data)

		{

			try{

				$exportData = $this->getPatch($data); //echo "<pre>";print_r($exportData);echo "</pre>";die;

				$_nxtcol = "\t";

				$_nxtrow = "\n";

				

				// Sheet Header

				$Header .= 	"\"BU \"".$_nxtcol.

							"\"Zone \"".$_nxtcol.

							"\"Region \"".$_nxtcol.

							"\"Area \"".$_nxtcol.

							"\"Headquarter \"".$_nxtcol.

							"\"City \"".$_nxtcol.

							"\"Patch Name \"".$_nxtcol.

							"\"Patch Code \"".$_nxtcol;

				$Header .= $_nxtrow;

			

				$totalRowData = count($exportData);

				if($totalRowData>0) {

					// Report Sheet Row Data

					foreach($exportData as $index=>$rowData)

					{

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['bunit_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['zone_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['region_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['area_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['headquater_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['city_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['patch_name']). "\"" . $_nxtcol;

						$Header .= 	"\"" . str_replace("\"", "\"\"", $rowData['patchcode']). "\"" . $_nxtcol;

						$Header .= $_nxtrow;					

					}

				}

				else {

					$Header .= 	"\" No Data Found!! \"".$_nxtcol;

				}

						

				CommonFunction::ExportCsv($Header,'Patch_Data','xls');

			}

			catch(Exception $e){

			   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  

			}		  

		}

		

		public function getLoggedUserData($data=array())

		{

			$select = $this->_db->select()

							->from(array('EL'=>'emp_locations'),array('EL.headquater_id'))

							->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('HT.headquater_name'))

							->where('EL.user_id='.$_SESSION['AdminLoginID']);

							//echo $select->__toString();die;

			$result = $this->getAdapter()->fetchRow($select);

			return $result;

		}

		

		public function getPatchDetail($data=array())

		{

			$select = $this->_db->select()->from('patchcodes',array('*'))->where('patch_id='.$data['token']);

							//echo $select->__toString();die;

			$result = $this->getAdapter()->fetchRow($select);

			return $result;

		}

		

		public function AddPatchData($data=array())

		{

			$lastpatch = $this->makePatchCode(array('headquarterID'=>$this->_getData['headquater_id']));

			$patchcode = ($lastpatch+1);

			$patchData['patchcode'] 	= 'P'.(int)$patchcode;

			$patchData['patch_name'] 	= $this->_getData['patch_name'];

			$patchData['city_id'] 		= $this->_getData['city_id'];

			$patchData['headquater_id'] = $this->_getData['headquater_id'];

			$patchData['area_id'] 		= $this->_getData['area_id'];

			$patchData['zone_id'] 		= $this->_getData['zone_id'];

			$patchData['region_id'] 	= $this->_getData['region_id'];

			$patchData['bunit_id'] 		= $this->_getData['bunit_id'];

			$patchData['added_through'] = 1;

			$patchData['created_by']  	= $_SESSION['AdminLoginID']; 

			$patchData['created_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($patchData);echo "</pre>";die;

			if(trim($patchData['patch_name']) != '') {

				return ($this->_db->insert('patchcodes',array_filter($patchData))) ? TRUE : FALSE;

			}

			else {

				return FALSE;

			}

		}

		

		public function UpdatePatchData($data=array())

		{

			/*$this->_db->update('patchcodes',array_filter(array(	'city_id'=>$this->_getData['city_id'],

					 											'headquater_id'=>$this->_getData['headquater_id'],

					 											'area_id'=>$this->_getData['area_id'],

														   		'zone_id'=>$this->_getData['zone_id'],

														   		'region_id'=>$this->_getData['region_id'],

														    	'bunit_id'=>$this->_getData['bunit_id'],

															 	'patch_name'=>$this->_getData['patch_name'])),

															"patch_id='".$this->_getData['token']."'");*/

			return ($this->_db->update('patchcodes',array_filter(array('patch_name'=>$this->_getData['patch_name'])),"patch_id='".$this->_getData['token']."'")) ? TRUE : FALSE;

		}

		

		/**

		 * Export All Headquarter Data

		 * Function : ExportAllHqHeader()

		 * This function return excel hedaer sheet which have seperate worksheet by each headquarter with that headquarter above and below location data.

		   From Business Unit to City Data

		 **/

		public function ExportAllHqHeader($data)

		{

			try{

				$query = $this->_db->select()

						 ->from(array('HQ'=>'headquater'),array('HQ.headquater_id','HQ'=>'HQ.headquater_name'))

						 ->joininner(array('AT'=>'area'),"AT.area_id=HQ.area_id",array('Area'=>'AT.area_name'))

						 ->joininner(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('Region'=>'RT.region_name'))

						 ->joininner(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('Zone'=>'ZT.zone_name'))

						 ->joininner(array('BT'=>'bussiness_unit'),"BT.bunit_id=HQ.bunit_id",array('BU'=>'BT.bunit_name'))

						 ->order("HQ.headquater_name","ASC"); //echo $query->__toString();die;

				$headquarters = $this->getAdapter()->fetchAll($query);

				$totalRowData = count($headquarters);

				if($totalRowData>0) {

					ini_set("memory_limit","512M");

					ini_set("max_execution_time",180);

					ob_end_clean();

					$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;

					

					foreach($headquarters as $index=>$hq)

					{

						if($index>0)

						{

							// Create a new worksheet, after the default sheet

							$objPHPExcel->createSheet();

						}

						// Create a first sheet, representing sales data

						$objPHPExcel->setActiveSheetIndex($index);

						

						$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Business Unit')

													  ->setCellValue('B1', 'Country')

													  ->setCellValue('C1', 'Zone')

													  ->setCellValue('D1', 'Region')

													  ->setCellValue('E1', 'Area')

													  ->setCellValue('F1', 'Headquarter')

													  ->setCellValue('G1', 'City')

													  ->setCellValue('H1', 'Patch')

													  ->setCellValue('I1', 'Patch Code')

													  ->setCellValue('J1', 'Org. SVL No.')

													  ->setCellValue('K1', 'HQ SVL No.')

													  ->setCellValue('L1', 'DCODE')

													  ->setCellValue('M1', 'Dr Name')

													  ->setCellValue('N1', 'Speciality')

													  ->setCellValue('O1', 'Qualification')

													  ->setCellValue('P1', 'Class')

													  ->setCellValue('Q1', 'Visit Frequency')

													  ->setCellValue('R1', 'Best Day To Meet')

													  ->setCellValue('S1', 'Best Time To Meet')

													  ->setCellValue('T1', 'Activity')

													  ->setCellValue('U1', 'Gender')

													  ->setCellValue('V1', 'Date of Birth')

													  ->setCellValue('W1', 'Phone')

													  ->setCellValue('X1', 'Mobile')

													  ->setCellValue('Y1', 'Email')

													  ->setCellValue('Z1', 'Address 1')

													  ->setCellValue('AA1', 'Address 2')

													  ->setCellValue('AB1', 'PIN')

													  ->setCellValue('AC1', 'State')

													  ->setCellValue('AD1', 'Potential of Doctor')

													  ->setCellValue('AE1', 'Chemist Code 1')

													  ->setCellValue('AF1', 'Chemist Code 2')

													  ->setCellValue('AG1', 'Chemist Code 3')

													  ->setCellValue('AH1', 'Remarks')

													  ->setCellValue('AI1', 'Active/In-active');

						// Row Formatting

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

						

						$sql = $this->_db->select()

								 ->from(array('CT'=>'city'),array('CT.city_id','CName'=>'CT.city_name'))

								 ->joinleft(array('PT'=>'patchcodes'),"PT.city_id=CT.city_id",array('PT.patch_id','PName'=>'PT.patch_name','PCode'=>'PT.patchcode'))

								 ->where('CT.headquater_id='.$hq['headquater_id'])

								 ->order("PT.city_id DESC"); //echo $sql->__toString();die;

						$cityPatches = $this->getAdapter()->fetchAll($sql); //echo "<pre>";print_r($cityPatches);die;

						$totalCityPatchData = count($cityPatches);

						$hqInfo = array();

						if($totalCityPatchData>0){

							foreach($cityPatches as $cityPatch){

								$hqInfo[] = array($hq['BU'],'India',$hq['Zone'],$hq['Region'],$hq['Area'],$hq['HQ'],$cityPatch['CName'],$cityPatch['PName'],$cityPatch['PCode']);

							}

						}

						else {

							$hqInfo[] = array($hq['BU'],'India',$hq['Zone'],$hq['Region'],$hq['Area'],$hq['HQ']);

						}						

						

						$objPHPExcel->getActiveSheet()->fromArray($hqInfo, NULL, 'A2');

						

						// Rename sheet

						$objPHPExcel->getActiveSheet()->setTitle($hq['HQ']);

						

						// Set active sheet index to the first sheet, so Excel opens this as the first sheet

						$objPHPExcel->setActiveSheetIndex(0);					

					}

										

					// Redirect output to a client’s web browser (Excel5)

					header('Content-Type: application/xlsx');

					header('Content-Disposition: attachment;filename="HQ_Wise_Doctor_Header.xlsx"');

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

		

		/**

		 * Export All Doctor Data

		 * Function : ExportDoctor()

		 * This function return doctor data sheet which will be seperated worksheet by each headquarter.

		 **/

		public function ExportDoctor($data)

		{

			try{

				$where 		 = 1; //echo "<pre>";print_r($data);echo "</pre>";die;

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

					$filterparam .= " AND DATE(DT.created_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";

				}

				

				$query = $this->_db->select()

								 ->from(array('DT'=>'crm_doctors'),array('DT.*'))

								 ->joinleft(array('PT'=>'patchcodes'),"PT.patch_id=DT.patch_id",array('PT.patchcode','PT.patch_name'))

								 ->joinleft(array('CT'=>'city'),"CT.city_id=DT.city_id",array('CT.city_name'))

								 ->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array('headquater_name'))

								 ->joinleft(array('AT'=>'area'),"AT.area_id=DT.area_id",array('AT.area_name'))

								 ->joinleft(array('RT'=>'region'),"RT.region_id=DT.region_id",array('RT.region_name'))

								 ->joinleft(array('ZT'=>'zone'),"ZT.zone_id=DT.zone_id",array('ZT.zone_name'))

								 ->joinleft(array('CN'=>'country'),"CN.country_id=DT.country_id",array('CN.country_name'))

								 ->joinleft(array('BT'=>'bussiness_unit'),"BT.bunit_id=DT.business_unit_id",array('BT.bunit_name'))

								// ->joinright(array('DC'=>'crm_doctor_chemists'),"DC.doctor_id=DT.doctor_id",array('DC.chemist_id'))

								 ->where($where.$filterparam)
								 ->where("DT.isActive='1' AND DT.isDelete='0' AND DT.isApproved='1' AND PT.isActive='1' AND PT.isDelete='0'")
								 ->order("HQ.headquater_name","ASC"); //echo $query->__toString();die;

				$doctors = $this->getAdapter()->fetchAll($query);

				$totalRowData = count($doctors);

				if($totalRowData>0) {

					ini_set("memory_limit","512M");

					ini_set("max_execution_time",180);

					ob_end_clean();

					$objPHPExcel = new PHPExcel(); //print_r($objPHPExcel);die;

					

					// Create a first sheet, representing sales data

					$objPHPExcel->setActiveSheetIndex(0);

					

					$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Business Unit')

												  ->setCellValue('B1', 'Country')

												  ->setCellValue('C1', 'Zone')

												  ->setCellValue('D1', 'Region')

												  ->setCellValue('E1', 'Area')

												  ->setCellValue('F1', 'Headquarter')

												  ->setCellValue('G1', 'City')

												  ->setCellValue('H1', 'Patch')

												  ->setCellValue('I1', 'Patch Code')

												  ->setCellValue('J1', 'Org. SVL No.')

												  ->setCellValue('K1', 'HQ SVL No.')

												  ->setCellValue('L1', 'DCODE')

												  ->setCellValue('M1', 'Dr Name')

												  ->setCellValue('N1', 'Speciality')

												  ->setCellValue('O1', 'Qualification')

												  ->setCellValue('P1', 'Class')

												  ->setCellValue('Q1', 'Visit Frequency')

												  ->setCellValue('R1', 'Best Day To Meet')

												  ->setCellValue('S1', 'Best Time To Meet')

												  ->setCellValue('T1', 'Activity')

												  ->setCellValue('U1', 'Gender')

												  ->setCellValue('V1', 'Date of Birth')

												  ->setCellValue('W1', 'Phone')

												  ->setCellValue('X1', 'Mobile')

												  ->setCellValue('Y1', 'Email')

												  ->setCellValue('Z1', 'Address 1')

												  ->setCellValue('AA1', 'Address 2')

												  ->setCellValue('AB1', 'PIN')

												  ->setCellValue('AC1', 'State')

												  ->setCellValue('AD1', 'Potential of Doctor')

												  ->setCellValue('AE1', 'Chemist Code 1')

												  ->setCellValue('AF1', 'Chemist Code 2')

												  ->setCellValue('AG1', 'Chemist Code 3')

												  ->setCellValue('AH1', 'Remarks')

												  ->setCellValue('AI1', 'Active/In-active');

					// Row Formatting

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

					

					$doctorInfo = array();

					$allDoctor = array();

					foreach($doctors as $index=>$doctor)

					{

						//echo "<pre>";print_r($doctor);echo "</pre>";die;

						$doctorInfo = array($doctor['bunit_name'],$doctor['country_name'],$doctor['zone_name'],$doctor['region_name'],$doctor['area_name'],$doctor['headquater_name'],$doctor['city_name'],$doctor['patch_name'],$doctor['patchcode'],$doctor['org_svl_number'],$doctor['svl_number'],$doctor['doctor_id'],$doctor['doctor_name'],$doctor['speciality'],$doctor['qualification'],$doctor['class'],$doctor['visit_frequency'],$doctor['meeting_day'],$doctor['meeting_time'],$doctor['activity_id'],$doctor['gender'],$doctor['dob'],$doctor['phone'],$doctor['mobile'],$doctor['email'],$doctor['address1'],$doctor['address2'],$doctor['postcode'],$doctor['state'],$doctor['doctor_potential']);

						

						// Check Doctor Chemist

						$sql = $this->_db->select()

								 ->from(array('DC'=>'crm_doctor_chemists'),'')

								 ->joininner(array('CT'=>'crm_chemists'),"CT.chemist_id=DC.chemist_id",array('CT.chemist_name'))

								 ->where('DC.doctor_id='.$doctor['doctor_id']); //echo $sql->__toString();die;

						$doctoChemists = $this->getAdapter()->fetchAll($sql);

						$totalDoctoChemist = count($doctoChemists);

						$dc = array();

						if($totalDoctoChemist>0){

							foreach($doctoChemists as $doctoChemist){

								$dc[] = $doctoChemist['chemist_name'];

							}

						}

						

						array_push($doctorInfo,(isset($dc[0])) ? $dc[0] : '');

						array_push($doctorInfo,(isset($dc[1])) ? $dc[1] : '');

						array_push($doctorInfo,(isset($dc[2])) ? $dc[2] : '');

						array_push($doctorInfo,$doctor['remarks']);

						array_push($doctorInfo,$doctor['isActive']);

						$allDoctor[] = $doctorInfo;

					}

					

					// Write Data

					$objPHPExcel->getActiveSheet()->fromArray($allDoctor, NULL, 'A2');	

					

					// Make Readonly of column L

					$objPHPExcel->getActiveSheet()->getStyle('A1:L45')->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_PROTECTED);

					

					// Rename sheet

					$objPHPExcel->getActiveSheet()->setTitle('Doctor Data');

					

					// Set active sheet index to the first sheet, so Excel opens this as the first sheet

					$objPHPExcel->setActiveSheetIndex(0);

										

					// Redirect output to a client’s web browser (Excel5)

					header('Content-Type: application/xlsx');

					header('Content-Disposition: attachment;filename="DoctorData.xlsx"');

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

		

		/**

		 * Export All Doctor Data

		 * Function : ExportDoctor()

		 * This function return doctor data sheet which will be seperated worksheet by each headquarter.

		 **/

		public function doctordelete($data)

		{

			try {

				if(count($data['dtoken'])>0) {

					$tableData['isDelete']     = '1';

					$tableData['delete_status']= '1';

					$tableData['deleted_by']   = $_SESSION['AdminLoginID'];

					$tableData['deleted_date'] = new Zend_Db_Expr('NOW()');

					$tableData['deleted_ip']   = $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($data);echo "</pre>";die;

					foreach ($data['dtoken'] as $did) {

						$this->_db->update('crm_doctors',array_filter($tableData),'doctor_id='.Class_Encryption::decode($did));

					}

					return TRUE;

				}

				else {

					return FALSE;

				}

			}

			catch (Exception $e) {

				$_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  

			}

		}

	}

?>