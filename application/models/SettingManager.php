<?php

    class SettingManager extends Zend_Custom
    {

		/**
		*Variable Holds the Name of Section Table
		**/
		public $parent_id = 1;				// For Getting submodule of application
		public $status    = '1';			// Status of module
		public $_getData  = array();			// Lavel of module.

		public function AddMasterSetting(){

		  	if(!empty($this->_getData['1'])){
				$this->_db->insert('company',array('company_name'=>$this->_getData['company_name'],'company_address'=>$this->_getData['company_address']));

				return 'company';   
		    }		   

			if(!empty($this->_getData['2'])){

				$this->_db->insert('bussiness_unit',array_filter(array('bunit_name'=>$this->_getData['bunit_name'])));

				return 'businesunit';  
			 }		

			if(!empty($this->_getData['3'])){

				$this->_db->insert('company_country',array_filter(array('country_id'=>$this->_getData['country_id'])));

				return 'country';  
			  }		

			if(!empty($this->_getData['4'])){

				$this->_db->insert('zone',array_filter(array('bunit_id'=>$this->_getData['bunit_id'],'zone_name'=>$this->_getData['zone_name'])));

				return 'zone';  
			}

			if(!empty($this->_getData['5'])){

			    $this->_db->insert('region',array_filter(array('zone_id'=>$this->_getData['zone_id'],'bunit_id'=>$this->_getData['bunit_id'],'region_name'=>$this->_getData['region_name'])));

				return 'region';  

			}

			if(!empty($this->_getData['6'])){

				$this->_db->insert('area',array_filter(array('zone_id'=>$this->_getData['zone_id'],'bunit_id'=>$this->_getData['bunit_id'],'region_id'=>$this->_getData['region_id'],'area_code'=>$this->_getData['area_code'],'area_name'=>$this->_getData['area_name'])));

				return 'area';  

			}

			if(!empty($this->_getData['7'])){

				$this->insertInToTable('headoffice', array($this->_getData));

				/* $this->_db->insert('headoffice',array_filter(array('area_id'=>$this->_getData['area_id'], 			'zone_id'=>$this->_getData['zone_id'],'region_id'=>$this->_getData['region_id'],
					    'bunit_id'=>$this->_getData['bunit_id'], 'headoffice_address' =>$this->_getData['headoffice_address'])));
				*/

				return 'headoffice';  
			}

			if(!empty($this->_getData['8'])){

				$this->insertInToTable('headquater', array($this->_getData));
				
				return 'headquater';  
		 }

		 if(!empty($this->_getData['9'])){

			         $this->insertInToTable('city', array($this->_getData));

				return 'city';  

		 }

		  if(!empty($this->_getData['10'])){

		  		$lastpatch = $this->makePatchCode(array('headquarterID'=>$this->_getData['headquater_id']));

				$patchcode = ($lastpatch+1);

				$this->_getData['patchcode'] = 'P'.(int)$patchcode;

				$this->_getData['added_through'] = '1';

				$this->_getData['created_by'] = $_SESSION['AdminLoginID'];

				$this->_getData['created_ip'] = $_SERVER['REMOTE_ADDR'];//echo "<pre>";print_r($this->_getData);die;

			    $this->insertInToTable('patchcodes', array($this->_getData));

				return 'patchcode';  

			}	
			if(!empty($this->_getData['11'])){
			     $this->insertInToTable('headoffice', array($this->_getData));
				return 'office';  
		    }

		}

		public function EditMasterSetting(){

		   if(!empty($this->_getData['1'])){

			       $this->_db->update('company',array('company_name'=>$this->_getData['company_name'],'company_address'=>$this->_getData['company_address']),

			 					"company_code='".$this->_getData['company_code']."'");

				return 'company';   

		    }		   

			if(!empty($this->_getData['2'])){

			        $this->_db->update('bussiness_unit',array_filter(array('bunit_name'=>$this->_getData['bunit_name'])),"bunit_id='".$this->_getData['bunit_id']."'");

				return 'businesunit';  

			 }		

			if(!empty($this->_getData['3'])){

			       $this->_db->update('company_country',array_filter(array('country_id'=>$this->_getData['country_id'])),"id='".$this->_getData['id']."'");

				return 'country';  

			  }		

			if(!empty($this->_getData['4'])){

			      $this->_db->update('zone',array_filter(array('bunit_id'=>$this->_getData['bunit_id'], 'zone_name'=>$this->_getData['zone_name'])),"zone_id='".$this->_getData['zone_id']."'");
				  $this->ChangeLocationMap();	

				return 'zone';  

			}

			if(!empty($this->_getData['5'])){

			    $this->_db->update('region',array_filter(array('zone_id'=>$this->_getData['zone_id'], 'bunit_id'=>$this->_getData['bunit_id'],  'region_name'=>$this->_getData['region_name'])),"region_id='".$this->_getData['region_id']."'");
			    $this->ChangeLocationMap();	

				return 'region';
			}

		 if(!empty($this->_getData['6'])){

			      $this->_db->update('area',array_filter(array('zone_id'=>$this->_getData['zone_id'],

														   'bunit_id'=>$this->_getData['bunit_id'],

														   'region_id'=>$this->_getData['region_id'],

														   'area_code'=>$this->_getData['area_code'],

														   'area_name'=>$this->_getData['area_name'])),"area_id='".$this->_getData['area_id']."'");
														   $this->ChangeLocationMap();	

				return 'area';    

			}

			if(!empty($this->_getData['7'])){

			         $this->_db->update('headoffice',array_filter(array('area_id'=>$this->_getData['area_id'],

														   'zone_id'=>$this->_getData['zone_id'],

														   'region_id'=>$this->_getData['region_id'],

														    'bunit_id'=>$this->_getData['bunit_id'],

															 'office_name'=>$this->_getData['office_name'],

															 'headoffice_address'=>$this->_getData['headoffice_address'])),"headoff_id='".$this->_getData['headoff_id']."'");
															 $this->ChangeLocationMap();	

				return 'headoffice';  

			}

			if(!empty($this->_getData['8'])){

			         $this->_db->update('headquater',array_filter(array('area_id'=>$this->_getData['area_id'],

														   'zone_id'=>$this->_getData['zone_id'],

														   'region_id'=>$this->_getData['region_id'],

														   'bunit_id'=>$this->_getData['bunit_id'],

														   'headquater_name'=>$this->_getData['headquater_name'],

														   'headquater_address'=>$this->_getData['headquater_address'])),"headquater_id='".$this->_getData['headquater_id']."'");
														   $this->ChangeLocationMap();	

				return 'headquater';  

			}

			if(!empty($this->_getData['9'])){

			         $this->_db->update('city',array_filter(array('headquater_id'=>$this->_getData['headquater_id'],

					 									   'area_id'=>$this->_getData['area_id'],

														   'zone_id'=>$this->_getData['zone_id'],

														   'region_id'=>$this->_getData['region_id'],

														   'bunit_id'=>$this->_getData['bunit_id'],

														   'city_name'=>$this->_getData['city_name'])),"city_id='".$this->_getData['city_id']."'");
														   $this->ChangeLocationMap();	

				return 'city';  

			}

		   if(!empty($this->_getData['10'])){

					 $this->_db->update('patchcodes',array_filter(array('city_id'=>$this->_getData['city_id'],

					 										'headquater_id'=>$this->_getData['headquater_id'],

					 										'area_id'=>$this->_getData['area_id'],

														   'zone_id'=>$this->_getData['zone_id'],

														   'region_id'=>$this->_getData['region_id'],

														    'bunit_id'=>$this->_getData['bunit_id'],

															'location_type_id'=>$this->_getData['location_type_id'],

															'isModify'=>'1',

															'modify_date'=>new Zend_Db_Expr('NOW()'),

															'modify_by'=>$_SESSION['AdminLoginID'],

															'modify_ip'=>$_SERVER['REMOTE_ADDR'],

															 'patch_name'=>$this->_getData['patch_name'])),"patch_id='".$this->_getData['patch_id']."'");
															 $this->ChangeLocationMap();	

				return 'patchcode';  

			}
			if(!empty($this->_getData['11'])){

					 $this->_db->update('headoffice',array_filter(array('city_id'=>$this->_getData['city_id'],

					 										'headquater_id'=>$this->_getData['headquater_id'],

					 										'area_id'=>$this->_getData['area_id'],

														   'zone_id'=>$this->_getData['zone_id'],

														   'region_id'=>$this->_getData['region_id'],

														    'bunit_id'=>$this->_getData['bunit_id'],

															'isModify'=>'1',

															'modify_date'=>new Zend_Db_Expr('NOW()'),

															'modify_by'=>$_SESSION['AdminLoginID'],

															'modify_ip'=>$_SERVER['REMOTE_ADDR'],

															 'office_name'=>$this->_getData['office_name'],
															 'headoffice_address'=>$this->_getData['headoffice_address'],
															 'office_type'=>$this->_getData['office_type'])),"headoff_id='".$this->_getData['headoff_id']."'");

				return 'office';  

			}
			

		}

		

		public function getLocationType(){

		 $select = $this->_db->select()->from(array('location_types'),array('*'))->order('location_type_name ASC');//echo $select->__toString();die;

		 $result = $this->getAdapter()->fetchAll($select);

		 return $result; 

		

		}

		

		public function getZone(){

			$select = $this->_db->select()
				->from(array('ZT'=>'zone'),array('*'))
				->joininner(array('BU'=>'bussiness_unit'),"ZT.bunit_id=BU.bunit_id",array('bunit_name'))
				->joininner(array('CM'=>'company'),"CM.company_code=BU.company_code",array('company_name'));
				//->joininner(array('CT'=>'country'),"CT.country_id=B2C.country_id",array('country_name'))

			//echo "316 setting manager = ".$select->__toString();die;
			$result = $this->getAdapter()->fetchAll($select);

			return $result; 
		}

		

		public function getCompanyCountry(){

		    $select = $this->_db->select()

						->from(array('CC'=>'company_country'),array('country_id','id'))

						->joinleft(array('CT'=>'country'),"CT.country_id=CC.country_id",array('country_name'));

		     $result = $this->getAdapter()->fetchAll($select);

			return $result;  			

		}

		public function getRegion(){

		 $select = $this->_db->select()

		 				->from(array('RT'=>'region'),array('*'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=RT.zone_id",array('zone_name'))

						->joininner(array('BU'=>'bussiness_unit'),"RT.bunit_id=BU.bunit_id",array('bunit_name'));

						//echo $select->__toString();die;

		     $result = $this->getAdapter()->fetchAll($select);

			return $result; 

		

		}

		public function getArea(){

		 $select = $this->_db->select()

		                ->from(array('AT'=>'area'),array('*'))

		 				->joininner(array('RT'=>'region'),"RT.region_id=AT.region_id",array('region_name'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=AT.zone_id",array('zone_name'))

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=AT.bunit_id",array('bunit_name'));

						//echo $select->__toString();die;

		     $result = $this->getAdapter()->fetchAll($select);

			return $result; 

		

		}

		public function getHeadOffice(){

		$data = array();

			 $select = $this->_db->select()

							->from(array('HO'=>'headoffice'),array('*'))

							->joininner(array('AT'=>'area'),"AT.area_id=HO.area_id",array('area_name'))

							->joinleft(array('CT'=>'city'),"CT.city_id=HO.city_id",array('city_name'))

							->joininner(array('RT'=>'region'),"RT.region_id=HO.region_id",array('region_name'))

							->joininner(array('ZT'=>'zone'),"ZT.zone_id=HO.zone_id",array('zone_name'))

							->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=HO.bunit_id",array('bunit_name'));

							//echo $select->__toString();die;

				 $data = $this->getAdapter()->fetchAll($select);

			return $data; 

		

		}

	  public function getHeadQuaters($data=array()){

	    $headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : 0;

			

			$where = 1;

			if($headquarterID > 0) {

				$where .= ' AND HQ.headquater_id='.$headquarterID;

			}



$select = $this->_db->select()

		 				->from(array('HQ'=>'headquater'),array('*'))

		 				->joininner(array('AT'=>'area'),"AT.area_id=HQ.area_id",array('area_name'))

						->joininner(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('region_name'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('zone_name'))

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=HQ.bunit_id",array('bunit_name'))

						->where($where)

						->order('headquater_name ASC'); //echo $select->__toString();die;

		     $result = $this->getAdapter()->fetchAll($select);

			return $result;

	  }	

	  public function getCity(){

		 $select = $this->_db->select()

		 				->from(array('CT'=>'city'),array('*'))

						->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=CT.headquater_id",array('headquater_name'))

		 				->joininner(array('AT'=>'area'),"AT.area_id=CT.area_id",array('area_name'))

						->joininner(array('RT'=>'region'),"RT.region_id=CT.region_id",array('region_name'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=CT.zone_id",array('zone_name'))

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=CT.bunit_id",array('bunit_name'));

						//echo $select->__toString();die;

		     $result = $this->getAdapter()->fetchAll($select);

			return $result; 

	 }

	 public function getStreet(){

		 $select = $this->_db->select()

		 				->from(array('ST'=>'street'),array('*'))

						->joininner(array('CT'=>'city'),"CT.city_id=ST.city_id",array('city_name'))

						->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=ST.headquater_id",array('headquater_name'))

		 				->joininner(array('AT'=>'area'),"AT.area_id=ST.area_id",array('area_name'))

						->joininner(array('RT'=>'region'),"RT.region_id=ST.region_id",array('region_name'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=ST.zone_id",array('zone_name'))

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=ST.bunit_id",array('bunit_name'));

						//echo $select->__toString();die;

		     $result = $this->getAdapter()->fetchAll($select);

			return $result; 

		

	}

	

		public function getPatchList($data=array()){

			try {

				$where = 1;

				if(isset($data['headtoken']) && (int)$data['headtoken']>0) {

					$where .= ' AND PC.headquater_id='.$data['headtoken'];

				}

				if(isset($data['typetoken']) && (int)$data['typetoken']>0) {

					$where .= ' AND PC.location_type_id='.$data['typetoken'];

				}

				

				//Order

				$orderlimit = CommonFunction::OdrderByAndLimit($data,'PC.headquater_id');
				/*
				//-------Pagination is not in use -----//
				$countQuery = $this->_db->select()

								->from(array('PC'=>'patchcodes'),array('COUNT(1) AS CNT'))

								->joininner(array('CT'=>'city'),"CT.city_id=PC.city_id",array())

								->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array())

								->joininner(array('AT'=>'area'),"AT.area_id=PC.area_id",array())

								->joininner(array('RT'=>'region'),"RT.region_id=PC.region_id",array())

								->joininner(array('ZT'=>'zone'),"ZT.zone_id=PC.zone_id",array())

								->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PC.bunit_id",array())

								->joinleft(array('LT'=>'location_types'),"LT.location_type_id=PC.location_type_id",array())

								->where($where)

								->where('PC.isActive','1')

								->where('PC.isDelete','0')

								->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($countQuery->__toString());die;

				$total = $this->getAdapter()->fetchAll($countQuery);
				*/
				$select = $this->_db->select()

							->from(array('PC'=>'patchcodes'),array('PC.patch_id','PC.patchcode','PC.patch_name','PC.fair','PC.isActive'))

							->joininner(array('CT'=>'city'),"CT.city_id=PC.city_id",array('CT.city_name'))

							->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array('HQ.headquater_name'))

							->joinleft(array('AT'=>'area'),"AT.area_id=PC.area_id",array('AT.area_name'))

							->joinleft(array('RT'=>'region'),"RT.region_id=PC.region_id",array('RT.region_name'))

							->joinleft(array('ZT'=>'zone'),"ZT.zone_id=PC.zone_id",array('ZT.zone_name'))

							->joinleft(array('BU'=>'bussiness_unit'),"BU.bunit_id=PC.bunit_id",array('BU.bunit_name'))

							->joinleft(array('LT'=>'location_types'),"LT.location_type_id=PC.location_type_id",array('LT.location_type_name'))

							->where($where)

							->where('PC.isActive','1')

							->where('PC.isDelete','0')

							->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']); //print_r($select->__toString());die;

				$result = $this->getAdapter()->fetchAll($select);

				//---------No need of returnig count,limit and offset---------//
				//return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']); 
				return array('Records'=>$result); 

			}

			catch(Exception $e){

				$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 

			}			

		}



          public function getPatch($data=array()){

		 	$where = 1;

			if(isset($data['headtoken']) && (int)$data['headtoken']>0) {

				$where .= ' AND PC.headquater_id='.$data['headtoken'];

			}

			

			$select = $this->_db->select()

		 				->from(array('PC'=>'patchcodes'),array('PC.patch_id','PC.patchcode','PC.patch_name','PC.fair','PC.isActive'))

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

						->order('PC.patch_id');

						//echo $select->__toString();die;

		    $result = $this->getAdapter()->fetchAll($select);

			return $result; 			

		}

	

		/**

		 * Export Patch Header

		 * Function : ExportPatchHeader()

		 * This function return parch same file with file header and location value till Headquarter

		 **/

		public function ExportPatchHeader($data){

			try{

				$headquarterID = (isset($data['headtoken']) && trim($data['headtoken'])>0) ? trim($data['headtoken']) : 0;

				//$exportData = $this->getHeadQuaters(array('headquarterID'=>$headquarterID)); //echo "<pre>";print_r($exportData);echo "</pre>";die;

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

							"\"Fair \"".$_nxtcol;

				$Header .= $_nxtrow;

			

				/*$totalRowData = count($exportData);

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

				}*/

						

				CommonFunction::ExportCsv($Header,'Patch_Sample','xls');

			}

			catch(Exception $e){

			   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  

			}		  

		}

		

		public function UploadePatchFileOldWay24Dec14($formData){

			ini_set("memory_limit","512M");

			ini_set("max_execution_time",180);

			$filename 	= CommonFunction::UploadFile('patchFile','xls');

			$inputFileType = PHPExcel_IOFactory::identify($filename);

			$objReader = PHPExcel_IOFactory::createReader($inputFileType);

			$objReader->setReadDataOnly(true);

			$reader = $objReader->load($filename);

			$data = $reader->getSheet(0);

			$k = $data->getHighestRow();

			

			// Get Location Data according to given Headquarter

			$headquarterID   = (isset($formData['headtoken']) && trim($formData['headtoken'])>0) ? trim($formData['headtoken']) : 0;

			$headquarterData = $this->getHeadQuaters(array('headquarterID'=>$headquarterID));

			$lastpatch 		 = $this->makePatchCode(array('headquarterID'=>$headquarterID));

			$getCityID 		 = $this->getCityID(array('hqID'=>$headquarterID,'city'=>$headquarterData[0]['headquater_name']));

			//echo "<pre>";print_r($getCityID);echo "</pre>";die;

	

			$j=1;

			for ($i=1; $i<$k; $i+=1) {

				if ($isFirstRow) {

					$isFirstRow = FALSE;

					continue;

				}

				

				$cityName = $this->getCell($data,$i,6);

				if(!empty($cityName)) {

					$getCityID = $this->getCityID(array('hqID'=>$headquarterID,'city'=>$cityName)); //echo "<pre>";print_r($getCityID);echo "</pre>";die;

				}

				

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

				

				$addPatch = $this->_db->insert('patchcodes',array_filter($patchData));

				$j++;

			}

			return TRUE;

		}

		

		public function UploadePatchFile($formData)
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
				$rowValue  = $data->rangeToArray('A'.($i+1).':'.$totalColumns.($i+1)); 
				//echo "<pre>";print_r($totalColumns);echo "</pre>";die;

				if($totalColumns == 'I' || $totalColumns == 'J') {

					$patchData = array();

				/***********************************************************
				below line of code commented and modify because str_replace 
				function remove all the spaces between the business unit name
				value due to which it fails to get the id of business unit and 
				code return error. resolved by jm on 19072018 by using trim
				************************************************************/
				//$businessUnit = ($this->getCell($data,$i,1)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,1))) : '';

				$businessUnit = ($this->getCell($data,$i,1)!='') ? strtoupper(trim(($this->getCell($data,$i,1)),' ')) : '';

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
											if($patchData['city_id']<=0){
											    
												$cityData = $patchData;
												$cityData['city_name'] = $city;
												$patchData['city_id'] =$this->insertInToTable('city', array($cityData));
											}
											//echo "<pre>";print_r($patchData);die;
											

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

					}else {

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

		

		public function UpdatePatchNameFair($formData)

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

			$hqID = (isset($formData['headtoken']) && $formData['headtoken']>0) ? $formData['headtoken'] : 0;

			

			// Get All City Data

			$getPatchCodes = $this->getLocationData(array('tableName'=>'patchcodes','tableColumn'=>array('patch_id',"UPPER(replace(patchcode,' ',''))"),'columnName'=>'headquater_id','columnValue'=>$hqID));

			//echo "<pre>";print_r($getPatchCodes);echo "</pre>";die;

	

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

				

				if($totalColumns == 'K') {

					$patchData = array();

					

					$patchData['patch_name'] = ($this->getCell($data,$i,8)!='') ? trim($this->getCell($data,$i,8)) : '';

					$patchData['fair'] 		 = ($this->getCell($data,$i,11)!='') ? trim($this->getCell($data,$i,11)) : '0.00';

					

					$patchCode = ($this->getCell($data,$i,10)!='') ? str_replace(' ','',strtoupper($this->getCell($data,$i,10))) : '';

					$patch_id  = (!empty($patchCode) && isset($getPatchCodes[$patchCode])) ? $getPatchCodes[$patchCode] : 0;

					

					if($patch_id > 0) {

						$patchData['isModify'] 		= '1';

						$patchData['modify_by']  	= $_SESSION['AdminLoginID'];

						$patchData['modify_date']  	= new Zend_Db_Expr('NOW()'); 

						$patchData['modify_ip'] 	= $_SERVER['REMOTE_ADDR']; //echo "<pre>";print_r($patchData);echo "</pre>";die;

						if($this->_db->update('patchcodes',array_filter($patchData),'patch_id='.$patch_id)) {

							$rowError[] = array(($i+1),"Patch Fair (".$patchData['fair'].") has been updated !!");

							$rowData[]  = $rowValue[0];

						}

						else {

							$rowError[] = array(($i+1),"Patch Fair (".$patchData['fair'].") couldn't updated !!");

							$rowData[]  = $rowValue[0];

						}

					}

					else {

						$rowError[] = array(($i+1),"Patch Code (".$patchCode.") not found !!");

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

			

			$select = $this->_db->select()->from($tableName,$tableColumn)->where($where); //if($tableName=='city'){echo $select->__toString();die;}

			$tableData = $this->getAdapter()->fetchAll($select);

			

			$responseData = array();

			if(count($tableData) > 0) {

				foreach($tableData as $key=>$data) {

					$responseData[$data[$tableColumn[1]]] = $data[$tableColumn[0]];

				}

			}

			

			return $responseData;

		}

		

		public function getCityID($data=array()) {

			$cityName = (isset($data['city']) && !empty($data['city'])) ? trim(strtoupper($data['city'])) : '';

			$hqID     = (isset($data['hqID']) && !empty($data['hqID'])) ? trim($data['hqID']) : '0';

			$query = $this->_db->select()->from('city','city_id')->where('headquater_id='.$hqID)->where('UPPER(city_name) LIKE "'.$cityName.'%"')->limit(1);

			//echo $query->__toString();die;

			$lastCode = $this->getAdapter()->fetchRow($query);

			return (int) $lastCode['city_id'];

		}

		

		public function makePatchCode($data=array()) {

			$headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : 0;

			$query = $this->_db->select()->from('patchcodes',array('CNT'=>'patchcode'))->where('headquater_id='.$headquarterID)->order("patch_id DESC")->limit(1);
			//$query = $this->_db->select()->from('patchcodes',array('CNT'=>'COUNT(1)'))->where('headquater_id='.$headquarterID);//echo $query->__toString();die;

			$lastCode = $this->getAdapter()->fetchRow($query);

			return (int) substr($lastCode['CNT'],1);
			//return (int) $lastCode['CNT'];

		}

		

		public function getCell(&$worksheet,$row,$col,$default_val='') {

			$col -= 1; // we use 1-based, PHPExcel uses 0-based column index

			$row += 1; // we use 0-based, PHPExcel used 1-based row index

			return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;

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



						$whereFilter = '1';

						if(isset($data['typetoken']) && (int)$data['typetoken']>0) {

							$whereFilter = 'PC.location_type_id='.$data['typetoken'];

						}

						$sql = $this->_db->select()

								 ->from(array('PC'=>'patchcodes'),array('PC.patch_id','PC.city_id','PC.location_type_id','PC.patch_name','PC.patchcode','PC.fair'))

								 ->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array('CName'=>'CT.city_name'))

								 ->joinleft(array('LT'=>'location_types'),"LT.location_type_id=PC.location_type_id",array('Type'=>'LT.location_type_code'))

								 ->where($whereFilter)

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

					header('Content-Type: application/vnd.ms-excel');

					header('Content-Disposition: attachment;filename="Patch_Data.xls"');

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

		

		public function ExportPatchDataSimpleExcel24Dec14($data){

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

							"\"Patch Code \"".$_nxtcol.

							"\"Fair \"".$_nxtcol;

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

						$Header .= 	"\"" . str_replace("\"", "\"\"", number_format($rowData['fair'],2)). "\"" . $_nxtcol;

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

		

	   public function getEdit(){

	       switch($this->_getData['level']){

		       case 1:

			       return $this->getCompanyById();

			   break;

			   case 2:

			   	    return $this->getBussinessUnitById();

			   break;

			   case 3:

			   	    return $this->getCountryById();

			   break;

			    case 4:

			   	    return $this->getZoneById();

			   break;

			   case 5:

			   	    return $this->getRegionById();

			   break;

			   case 6:

			   	    return $this->getAreaById();

			   break;

			    case 7:

			   	    return $this->getHeadQuaterById();

			   case 8:

			   	    return $this->getheadquaterOfCity();	

			   break;

			   case 9:

			   	    return $this->getCityById();	

			   break;

			   case 10:

			   	    return $this->getPatchById();	

			   break; 		   

		   }

	   }

	  public function getCompanyById(){

	         $select = $this->_db->select()

						->from('company',array('*'))

						->where("company_code='".$this->_getData['company_code']."'");

		     $result = $this->getAdapter()->fetchRow($select);

			return $result;

	  }

	  public function getBussinessUnitById(){

	        $select = $this->_db->select()

						->from('bussiness_unit',array('*'))

						->where("bunit_id='".$this->_getData['bunit_id']."'");

		     $result = $this->getAdapter()->fetchRow($select);

			return $result;

	  }

	  public function getCountryById(){

	        $select = $this->_db->select()

						->from('company_country',array('*'))

						->where("id='".$this->_getData['id']."'");

		     $result = $this->getAdapter()->fetchRow($select);

			return $result;

	  }

	  public function getZoneById(){

	        $select = $this->_db->select()

						->from('zone',array('*'))

						->where("zone_id='".$this->_getData['zone_id']."'");

		     $result = $this->getAdapter()->fetchRow($select);

			return $result;

	  }

	 public function getRegionById(){

	     $select = $this->_db->select()

						->from('region',array('*'))

						->where("region_id='".$this->_getData['region_id']."'");

		 $result = $this->getAdapter()->fetchRow($select);

		return $result;

	  }

	  public function getAreaById(){

	     $select = $this->_db->select()

						->from('area',array('*'))

						->where("area_id='".$this->_getData['area_id']."'");

		 $result = $this->getAdapter()->fetchRow($select);

		return $result;

	  } 

	  public function getHeadQuaterById(){

	     $select = $this->_db->select()

						->from('headoffice',array('*'))

						->where("headoff_id='".$this->_getData['headoff_id']."'");

		 $result = $this->getAdapter()->fetchRow($select);

		return $result;

	  }

	 public function getheadquaterOfCity(){

	    $select = $this->_db->select()

						->from('headquater',array('*'))

						->where("headquater_id='".$this->_getData['headquater_id']."'");

		 $result = $this->getAdapter()->fetchRow($select);

		return $result;

	 } 

	 public function getCityById(){

	      $select = $this->_db->select()

						->from('city',array('*'))

						->where("city_id='".$this->_getData['city_id']."'");

		 $result = $this->getAdapter()->fetchRow($select);

		return $result;

	 } 

	 public function getPatchById(){

	      $select = $this->_db->select()

						->from('patchcodes',array('*'))

						->where("patch_id='".$this->_getData['patch_id']."'");

		 $result = $this->getAdapter()->fetchRow($select);

		return $result;

	 }  

	 public function BackAction(){

	   switch($this->_getData['level']){

	     case 1:

		    return 'company';

			break;

		 case 2:

		   return 'businesunit';

		   break;

		 case 3:

		   return 'country';

		   break;

	    case 4:

	     return 'zone';

	     break;	

		  case 5:

	     return 'region';

	     break;

		  case 6:

	     return 'area';

	     break;

		case 7:

	     return 'headoffice';

	     break;

		case 8:

	     return 'headquater';

	     break;

		 case 9:

	     return 'city';

	     break;

		 case 10:

	     return 'patch';

	     break;  

	   }

	 } 

  public function AddDesignation(){

	    $this->_db->insert('designation',array_filter(array('designation_name'=>$this->_getData['designation_name'],'designation_code'=>$this->_getData['designation_code'],'designation_level'=>$this->_getData['designation_level'])));

	 }

  public function AddDepartment(){

	    $this->_db->insert('department',array_filter(array('department_name'=>$this->_getData['department_name'])));

	 }

    public function AddSalaryhead(){

	  /* if(!empty($this->_getData['Detectsalaryhead'])){

	      $this->_db->insert('salary_head',array_filter(array('salary_title'=>$this->_getData['salary_title'],'salary_type'=>'2')));

	   }else{

	      $this->_db->insert('salary_head',array_filter(array('salary_title'=>$this->_getData['salary_title'])));

	   }*/

	   $this->_db->insert('salary_head',array_filter(array('salary_title'=>$this->_getData['salary_title'],'salary_type'=>$this->_getData['salary_type'],'prodata_status'=>$this->_getData['prodata_status'],'sequence'=>$this->_getData['sequence'],'credit_type'=>$this->_getData['credit_type'],'ctc_percentage'=>$this->_getData['ctc_percentage'])));

	 }

  

	 

   public function getEditNew(){

      switch($this->_getData['type']){

	     case 'Designation':

		    return $this->getDesignationByID();

			break;

		 case 'Department':

		    return $this->getDepartmentByID();

		   break;

		 case 'Salaryhead':

		   return $this->getSalaryheadByID();

		   break;	

	   }

   }

    public function getDesignationByID(){

	    $select = $this->_db->select()

						->from('designation',array('*'))

						->where("designation_id='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchRow($select);

		return $result;

	 }

   public function getDepartmentByID(){

	    $select = $this->_db->select()

						->from('department',array('*'))

						->where("department_id='".$this->_getData['department_id']."'");

		$result = $this->getAdapter()->fetchRow($select);

		return $result;

	 }

	public function getSalaryheadByID(){

	    $select = $this->_db->select()

						->from('salary_head',array('*'))

						->where("salaryhead_id='".$this->_getData['salaryhead_id']."'");

		$result = $this->getAdapter()->fetchRow($select);

		return $result;

	 }	

	public function BackNewAction(){

	   switch($this->_getData['type']){

	      case 'Designation':

		    return 'designation';

			break;

		  case 'Department':

		   return 'department';

		   break;

		 case 'Salaryhead':

		   return 'salaryhead';

		   break;	

	   }

	 }

  public function EditDesignation(){

	    $this->_db->update('designation',array('designation_name'=>$this->_getData['designation_name'],'designation_code'=>$this->_getData['designation_code'],'designation_level'=>$this->_getData['designation_level']),"designation_id='".$this->_getData['designation_id']."'");

	 }

  public function EditDepartment(){

	    $this->_db->update('department',array('department_name'=>$this->_getData['department_name']),"department_id='".$this->_getData['department_id']."'");

	 } 

  public function EditSalaryhead(){

	    $this->_db->update('salary_head',array('salary_title'=>$this->_getData['salary_title'],'salary_type'=>$this->_getData['salary_type'],'prodata_status'=>$this->_getData['prodata_status'],'sequence'=>$this->_getData['sequence'],'credit_type'=>$this->_getData['credit_type'],'ctc_percentage'=>$this->_getData['ctc_percentage']),"salaryhead_id='".$this->_getData['salaryhead_id']."'");

	 }

  public function getCountry(){

         $select = $this->_db->select()

						->from('country',array('*'));

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

  }

  public function AddSalaryTemplate(){

	 if(!empty($this->_getData['salaryhead_id'])){

	    $addhead = implode(',',$this->_getData['salaryhead_id']);

	  }

	   if(!empty($this->_getData['detsalaryhead_id'])){

	      $detecthead = implode(',',$this->_getData['detsalaryhead_id']);

	  }

		 $this->_db->insert('salary_template',array_filter(array('bunit_id'=>$this->_getData['bunit_id'],

													'department_id'=>$this->_getData['department_id'],

													'designation_id'=>$this->_getData['designation_id'],

													'salaryhead_id'=>$addhead,

													'detsalaryhead_id'=>$detecthead)));

		 return $this->getAdapter()->lastInsertId(); //true;											

	

   }

   public function getTemplate(){

     $select = $this->_db->select()

						->from(array('ST'=>'salary_template'),array('*'))

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=ST.bunit_id",array('bunit_name'))

						->joininner(array('DP'=>'department'),"DP.department_id=ST.department_id",array('department_name'))

						->joininner(array('DT'=>'designation'),"DT.designation_id=ST.designation_id",array('designation_name'));

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

   }

   public function getTemplatehead($template_id){

       $select = $this->_db->select()

						->from('salary_template',array('*'))

						->where("salary_template_id='".$template_id."'");

		$result = $this->getAdapter()->fetchRow($select);

		return $result;

   }

   public function AddSalaryTemplateAmount(){

     foreach($this->_getData['salaryhead_id'] as $key=>$salaryhead){

	      $this->_db->insert('salary_template_amount',array_filter(array('salary_template_id'=>$this->_getData['salary_template_id'],

													'salaryhead_id'=>$salaryhead,

													'amount'=>$this->_getData['amount'][$key]))); 

	 }

   }

   public function getTemplateRecordById(){

      $select = $this->_db->select()

						->from(array('ST'=>'salary_template'),array('*'))

						->where("ST.salary_template_id='".$this->_getData['salary_template_id']."'");

	 $result = $this->getAdapter()->fetchRow($select);

     return $result;					

   }

   public function getTemplateAmountById(){

      $select = $this->_db->select()

						->from(array('STA'=>'salary_template_amount'),array('*'))

						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=STA.salaryhead_id",array('salary_type'))

						->where("STA.salary_template_id='".$this->_getData['salary_template_id']."'");

	 $results = $this->getAdapter()->fetchAll($select);

		 foreach($results as $result){

		     $amount[$result['salaryhead_id']] = $result['amount'];

		 }

     return $amount;	 

   }

   public function editSalaryTemplate(){

       if(!empty($this->_getData['salaryhead_id'])){

	    $addhead = implode(',',$this->_getData['salaryhead_id']);

	  }

	   if(!empty($this->_getData['detsalaryhead_id'])){

	      $detecthead = implode(',',$this->_getData['detsalaryhead_id']);

	  }

		 $this->_db->update('salary_template',array_filter(array('bunit_id'=>$this->_getData['bunit_id'],

													'department_id'=>$this->_getData['department_id'],

													'designation_id'=>$this->_getData['designation_id'],

													'salaryhead_id'=>$addhead,

													'detsalaryhead_id'=>$detecthead)),"salary_template_id='".$this->_getData['salary_template_id']."'");

   }

   public function editSalaryTemplateAmount(){

      $this->_db->delete('salary_template_amount',"salary_template_id='".$this->_getData['salary_template_id']."'");

      foreach($this->_getData['salaryhead_id'] as $key=>$salaryhead){

	      $this->_db->insert('salary_template_amount',array_filter(array('salary_template_id'=>$this->_getData['salary_template_id'],

													'salaryhead_id'=>$salaryhead,

													'amount'=>$this->_getData['amount'][$key]))); 

	 }

   }	

  

	/*public function getProvidentSettings($cond=false){

	   $where = ''; 

	   if($cond){

	      $where = " AND setting_id='".$cond."'";

	   }

	   $select = $this->_db->select()

	   							 ->from(array('PS'=>'provident_setting'),array('*'))

								 ->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PS.bunit_id",array('bunit_name'))

								 ->joininner(array('DT'=>'department'),"DT.department_id=PS.department_id",array('department_name'))

								 ->joininner(array('DG'=>'designation'),"DG.designation_id=PS.designation_id",array('designation_name'))

								 ->where("1".$where);

		$results = $this->getAdapter()->fetchAll($select);

		return $results;

	}*/

	

   public function updateprovidentSettings(){

      $this->_db->update('provident_setting',array('provident_type'=>$this->_getData['provident_type'],

	  											   'provident_value'=>$this->_getData['provident_value'],

												   'provident_by_company'=>$this->_getData['provident_by_company'],

												   'comapny_provident_value'=>$this->_getData['comapny_provident_value'],

												   'provident_on'=>$this->_getData['provident_on'],

												   'extra_provident_on'=>$this->_getData['extra_provident_on'],

												   'provident_status'=>$this->_getData['provident_status']));

	  $_SESSION[SUCCESS_MSG] = 'Settings Updated SuccessFully';

   }	

   public function UpdateSalaryDuration(){

       $this->_db->update('salary_duration',array('from_date'=>$this->_getData['from_date'],'to_date'=>$this->_getData['to_date']));

   }

   

   public function UpdateProvidentSetting(){

      

   } 

   public function getAllSalaryHead(){

	    $select = $this->_db->select()

						->from('salary_head',array('*'));

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

	 }

   public function getExpenseSetting(){

      /* $column  = array('BU.bunit_name','DP.department_name','DT.designation_name',new Zend_Db_Expr('CASE EXP.expense_type WHEN 1 THEN "Actual" WHEN 2 THEN "Fixed" ELSE "Actual+Fixed" END Type'),'EXP.amount','EXP.mobile_bill','ADT.designation_name as Approved Designation','EXP.exp_setting_id');*/

	   

	   $column  = array('BU.bunit_name','DP.department_name','DT.designation_name','EXP.number_of_approval','EXP.exp_setting_id','(SELECT SUM(ETA.expense_amount) FROM expense_template_amount AS ETA WHERE ETA.exp_setting_id=EXP.exp_setting_id) as Expense Amount');

       $select = $this->_db->select()

						->from(array('EXP'=>'expense_setting'),$column)

						->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=EXP.bunit_id",array())

						->joininner(array('DP'=>'department'),"DP.department_id=EXP.department_id",array())

						->joininner(array('DT'=>'designation'),"DT.designation_id=EXP.designation_id",array());

						//->joininner(array('ET'=>'expense_template_amount'),"ET.desigexp_setting_idnation_id=EXP.exp_setting_id",array());

						//->joinleft(array('ADT'=>'designation'),"ADT.designation_id=EXP.approved_designation",array());

						//print_r($select->__toString());die;

		return $select;				

   }

   public function getExpenseSettingById(){

       $select = $this->_db->select()

						->from(array('EXP'=>'expense_setting'),array('*'))

						->where("exp_setting_id='".$this->_getData['exp_setting_id']."'"); 

	   $result = $this->getAdapter()->fetchRow($select);

	  return $result;

   }

   public function AddUpdateExpenseSetting(){//print_r($this->_getData);die;

      if($this->_getData['Mode']=='Add'){

	       $setting_id = $this->insertInToTable('expense_setting', array($this->_getData));

		     if(!empty($this->_getData['expense_amount'])){

			     foreach($this->_getData['expense_amount'] as $key=>$expenseamount){

				     $this->_db->insert('expense_template_amount',array('exp_setting_id'=>$setting_id,'head_id'=>$this->_getData['head_id'][$key],'expense_amount'=>$expenseamount));

				 }

			  }

		   $_SESSION[SUCCESS_MSG] = "Setting has been Added Successfully";

	  }else{

	       $this->updateTable('expense_setting',$this->_getData,array("exp_setting_id"=>$this->_getData['exp_setting_id']));

		   $this->_db->delete('expense_template_amount',"exp_setting_id='".$this->_getData['exp_setting_id']."'");

		   if(!empty($this->_getData['expense_amount'])){

			     foreach($this->_getData['expense_amount'] as $key=>$expenseamount){

				     $this->_db->insert('expense_template_amount',array('exp_setting_id'=>$this->_getData['exp_setting_id'],'head_id'=>$this->_getData['head_id'][$key],'expense_amount'=>$expenseamount));

				 }

			  }

		   $_SESSION[SUCCESS_MSG] = "Setting has been Updated Successfully";

	  }

	   if($this->_getData['updateemp']=='on'){

		       $this->updateEmployeeExpense();

	   }

   }

   public function getExpenseHead(){

       $where = "1";

	    if(!empty($this->_getData['head_id'])){

		  $where .=" AND head_id='".$this->_getData['head_id']."'";

		}

        $select = $this->_db->select()

						->from(array('EH'=>'expense_head'),array('*'))

						->joinleft(array('SH'=>'salary_head'),"SH.salaryhead_id=EH.salary_head",array('salary_title'))

						->where($where);

		$result = $this->getAdapter()->fetchAll($select);

		return $result;

   }

   public function AddUpdateHeadExpense(){

       if($this->_getData['Mode']=='Add'){

	      $this->insertInToTable('expense_head', array($this->_getData));

		  $_SESSION[SUCCESS_MSG] = "Setting has been Added Successfully";

	   }

	   if($this->_getData['Mode']=='Edit'){ 

	      $this->updateTable('expense_head',$this->_getData,array('head_id'=>$this->_getData['head_id']));

		  $_SESSION[SUCCESS_MSG] = "Setting has been Updated Successfully";

	   }

   }

   public function updateEmployeeExpense(){

      $select = $this->_db->select()

						->from(array('UD'=>'employee_personaldetail'),array('user_id'))

						->where("designation_id='".$this->_getData['designation_id']."' AND department_id='".$this->_getData['department_id']."' AND delete_status='0'");

	  $result = $this->getAdapter()->fetchAll($select);//print_r($result);die;

	  foreach($result as $users){

	     if(!empty($this->_getData['head_id'])){

		   $this->_db->delete('emp_expense_amount',"user_id='".$users['user_id']."'");

            foreach($this->_getData['expense_amount'] as $key=>$expenseamount){

			  $this->_db->insert('emp_expense_amount',array_filter(array('user_id'=>$users['user_id'],'head_id'=>$this->_getData['head_id'][$key],'expense_amount'=>$expenseamount)));

			  			  

			}

		}

	  }

    }

	

	public function AddUpdateesiSetting(){

	    $detail = $this->EsiDetail();

		if(!empty($detail)){

		   $this->_db->update('esi_setting',array('esi_type'	=>	$this->_getData['esi_type'],

		   										  'esi_value'	=> 	$this->_getData['esi_value'],

												  'esi_by_company'=>$this->_getData['esi_by_company'],

												  'comapny_esi_value'=>$this->_getData['comapny_esi_value'],

												  'esi_on'		=>	$this->_getData['esi_on'],

												  'extra_esi_on'=>	$this->_getData['extra_esi_on'],

												  'esi_status'	=>	$this->_getData['esi_status']));

		}else{

		    $this->_db->insert('esi_setting',array_filter(array('esi_type'=>$this->_getData['esi_type'],

																'esi_value'=>$this->_getData['esi_value'],

																'esi_by_company'=>$this->_getData['esi_by_company'],

																'comapny_esi_value'=>$this->_getData['comapny_esi_value'],

																'esi_on'=>$this->_getData['esi_on'],

																'extra_esi_on'=>$this->_getData['extra_esi_on'],

																'esi_status'=>$this->_getData['esi_status'])));

		}

	}

	public function EsiDetail(){

	   $select = $this->_db->select()

						->from(array('ES'=>'esi_setting'),array('*'));

	  $result = $this->getAdapter()->fetchRow($select);//print_r($result);die;

	  return $result;

	}
	public function getHolidays($data){

		/*******************************************************************
		 code and code condition commented and modified to get the list of holidays in list view of HRM->holiday calendar by jm on 18072018
		********************************************************************/
	   	//$where =  '1';
	   	
	   	if(!empty($data['months'])){
	    
	    	$exploded_date = explode(',',$data['months']);
			$month = $exploded_date[0];
			$year = $exploded_date[1];
			$where .=  " AND DATE_FORMAT(HD.holiday_date,'%Y-%m')='".$year.'-'.$month."'";
	   	}

	   	//$where .=  "AND HD.region_id = '".$data['region_id']."'";
	    $where .=  "HD.region_id != '".$data['region_id']."'";
	   
	   	$select = $this->_db->select()
			->from(array('HD'=>'holidays'),array('DATE_FORMAT(HD.holiday_date,"%Y-%m") AS month','*'))
			->where($where)
			->order("HD.holiday_date ASC");
		//echo "<br><br>2999 = ".$select->__toString();//die;
	   	$result = $this->getAdapter()->fetchAll($select);
		
		return $result;
	}
	
	public function SaveHoliday($data){
	    $this->_db->delete('holidays',"DATE_FORMAT(holiday_date,'%m,%Y')='".$data['months']."'");
		foreach($data['holidays'] as $date=>$holiday){
		   $this->_db->insert('holidays',array_filter(array('holiday_name'=>$data['holiday_name'][$date],'holiday_date'=>$date,'day'=>$data['day'][$date],'region_id'=>$data['region_id'])));
		}
	}
	
	public function ChangeLocationMap(){
	   //echo "<pre>";print_r($this->_getData);die;
	   if(!empty($this->_getData['8'])){
	   $this->_db->update('patchcodes',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id']),
											  "headquater_id='".$this->_getData['headquater_id']."'");
	   $this->_db->update('crm_doctors',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id']),
											  "headquater_id='".$this->_getData['headquater_id']."'");
	  $this->_db->update('crm_chemists',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id']),
											  "headquater_id='".$this->_getData['headquater_id']."'");
	  $this->_db->update('emp_locations',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id']),
											  "headquater_id='".$this->_getData['headquater_id']."'");
		$this->_db->update('city',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id']),
											  "headquater_id='".$this->_getData['headquater_id']."'");									  										  										  										  
	  }
	  if(!empty($this->_getData['10'])){ 
	      $this->_db->update('crm_doctors',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id'],
											  'headquater_id'=>$this->_getData['headquater_id'],
											  'city_id'=>$this->_getData['city_id']),
											  "patch_id='".$this->_getData['patch_id']."'");
											  
		$this->_db->update('crm_chemists',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id'],
											  'headquater_id'=>$this->_getData['headquater_id'],
											  'city_id'=>$this->_getData['city_id']),
											  "patch_id='".$this->_getData['patch_id']."'");									  
	  }
	  
	  if(!empty($this->_getData['9'])){
	      $this->_db->update('crm_doctors',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id'],
											  'headquater_id'=>$this->_getData['headquater_id']),
											  "city_id='".$this->_getData['city_id']."'");
											  
		$this->_db->update('crm_chemists',array('area_id'=>$this->_getData['area_id'],
		   									  'region_id'=>	$this->_getData['region_id'],
											  'area_id'=>$this->_getData['area_id'],
											  'zone_id'=>$this->_getData['zone_id'],
											  'headquater_id'=>$this->_getData['headquater_id']),
											  "city_id='".$this->_getData['city_id']."'");									  
	  }
	}

}

?>