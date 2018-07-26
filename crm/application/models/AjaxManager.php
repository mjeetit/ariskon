<?php

class AjaxManager extends Zend_Custom

{

	public $_getData = array();

	private $_userIDs	 	= array();

	private $_parentIDs	 	= array();

	private $_headquarters 	= array();

	

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

		

		// Get All Cities Details

		/*$AllCity = array();

		$cities = $this->getTableData(array('tableName'=>'city','tableColumn'=>array('city_id','city_name'),'columnName'=>'headquater_id','columnValue'=>$headquarterID,'returnRow'=>'all'));		

		if(count($cities)>0) {

			foreach($cities as $city) {

				$AllCity[strtoupper($city['city_name'])] = $city['city_id'];

			}

		}*/

	}

	

	public function getlocationinfo($data=array())
	{

		$response = 0;
		$locationInfo = $this->getlocation();

		if(!empty($locationInfo)){

			$response = $locationInfo['cityName'].'^'.$locationInfo['hqName'].'^'.$locationInfo['areaName'].'^'.$locationInfo['zoneName'].'^'.$locationInfo['regionName'];

			$BeAbmRsmDetail = $this->getBeAbmRsmDetail();

			$response .= '^';

			if(!empty($BeAbmRsmDetail)){
				$response .= $BeAbmRsmDetail['abmHQName'];
			}

			$response .= '^'.$locationInfo['buName'];
		}	 
		return $response;
	}

	
	public function getlocation($data=array())
	{
		$streetID = (isset($data['streetID']) && trim($data['streetID'])>0) ? trim($data['streetID']) : $this->_getData['token'];

		$query = $this->_db->select()
			->from(array('ST'=>'patchcodes'),array('ST.patch_id'))
			->joininner(array('CT'=>'city'),"CT.city_id=ST.city_id",array('CT.city_id','cityName'=>'CONCAT(CT.location_code," - ",CT.city_name)'))
			->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=ST.headquater_id",array('HQ.headquater_id','hqName'=>'CONCAT(HQ.location_code," - ",HQ.headquater_name)'))
			->joininner(array('AT'=>'area'),"AT.area_id=ST.area_id",array('AT.area_id','areaName'=>'CONCAT(AT.location_code," - ",AT.area_name)'))
			->joininner(array('RT'=>'region'),"RT.region_id=ST.region_id",array('RT.region_id','regionName'=>'CONCAT(RT.location_code," - ",RT.region_name)'))
			->joininner(array('ZT'=>'zone'),"ZT.zone_id=ST.zone_id",array('ZT.zone_id','zoneName'=>'CONCAT(ZT.location_code," - ",ZT.zone_name)'))
			->joininner(array('BT'=>'bussiness_unit'),"BT.bunit_id=ST.bunit_id",array('BT.bunit_id','buName'=>'CONCAT(BT.company_code," - ",BT.bunit_name )'))
			->where("ST.patch_id ='".$streetID."'");
		//echo "116 = ".$query->__toString();die;

		return $this->getAdapter()->fetchRow($query);
	}

	

	public function getAppointmentDetail($data=array())

	{

		$response = 0;

		$doctorInfo = $this->getdoctorinfo(); //echo "<pre>";print_r($doctorInfo);echo "</pre>";die;

		if(!empty($doctorInfo)){

			$response = $doctorInfo['svl_number'].'^'.$doctorInfo['speciality'].'^'.$doctorInfo['qualification'].'^'.$doctorInfo['address1'].'^'.$doctorInfo['address2'].'^'.$doctorInfo['postcode'].'^'.$doctorInfo['phone'].'^'.$doctorInfo['mobile'];

			

			/*$upHierarchyDetail = $this->userHierarchy(array('headquarterID'=>$doctorInfo['headquater_id']));

			if(count($upHierarchyDetail)>0){

				$response .= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['Name'])) ? '^'.$upHierarchyDetail['BE']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['HQName'])) ? '^'.$upHierarchyDetail['BE']['HQName'] : '^';

				$response .= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['Code'])) ? '^'.$upHierarchyDetail['BE']['Code'] : '^';

				$response .= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['Name'])) ? '^'.$upHierarchyDetail['ABM']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['HQName'])) ? '^'.$upHierarchyDetail['ABM']['HQName'] : '^';

				$response .= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['Code'])) ? '^'.$upHierarchyDetail['ABM']['Code'] : '^';

				$response .= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['Name'])) ? '^'.$upHierarchyDetail['RBM']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['HQName'])) ? '^'.$upHierarchyDetail['RBM']['HQName'] : '^';

				$response .= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['Code'])) ? '^'.$upHierarchyDetail['RBM']['Code'] : '^';

				$response .= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['Name'])) ? '^'.$upHierarchyDetail['ZBM']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['HQName'])) ? '^'.$upHierarchyDetail['ZBM']['HQName'] : '^';

				$response .= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['Code'])) ? '^'.$upHierarchyDetail['ZBM']['Code'] : '^';

			}*/

			

			$upHierarchyDetail = $this->getBeAbmRsmDetail(array('headquarterID'=>$doctorInfo['headquater_id'])); //print_r($upHierarchyDetail);die;

			if(count($upHierarchyDetail)>0){

				$response .= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['Name'])) ? '^'.$upHierarchyDetail['BE']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['HqName'])) ? '^'.$upHierarchyDetail['BE']['HqName'] : '^';

				$response .= (isset($upHierarchyDetail['BE']) && isset($upHierarchyDetail['BE']['EmpCode'])) ? '^'.$upHierarchyDetail['BE']['EmpCode'] : '^';

				$response .= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['Name'])) ? '^'.$upHierarchyDetail['ABM']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['HqName'])) ? '^'.$upHierarchyDetail['ABM']['HqName'] : '^';

				$response .= (isset($upHierarchyDetail['ABM']) && isset($upHierarchyDetail['ABM']['EmpCode'])) ? '^'.$upHierarchyDetail['ABM']['EmpCode'] : '^';

				$response .= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['Name'])) ? '^'.$upHierarchyDetail['RBM']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['HqName'])) ? '^'.$upHierarchyDetail['RBM']['HqName'] : '^';

				$response .= (isset($upHierarchyDetail['RBM']) && isset($upHierarchyDetail['RBM']['EmpCode'])) ? '^'.$upHierarchyDetail['RBM']['EmpCode'] : '^';

				$response .= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['Name'])) ? '^'.$upHierarchyDetail['ZBM']['Name'] : '^';

				$response .= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['HqName'])) ? '^'.$upHierarchyDetail['ZBM']['HqName'] : '^';

				$response .= (isset($upHierarchyDetail['ZBM']) && isset($upHierarchyDetail['ZBM']['EmpCode'])) ? '^'.$upHierarchyDetail['ZBM']['EmpCode'] : '^';

			}

			

			$doctorChemistDetails = $this->getdoctorChemistDetail();

			if(count($doctorChemistDetails)>0){

				foreach($doctorChemistDetails as $doctorChemistDetail) {

					$response .= '^'.$doctorChemistDetail['legacy_code'];

					$response .= '^'.$doctorChemistDetail['chemist_name'];

					$response .= '^'.$doctorChemistDetail['phone'];

				}

			}

		}

		return $response;

	}

	

	public function getProductPrice($data=array())

	{

		$response = 0;

		$productID = (isset($data['productID']) && trim($data['productID'])>0) ? trim($data['productID']) : $this->_getData['token'];

		$query = $this->_db->select()

				 ->from(array('PT'=>'crm_products'),array('PT.stockist_excl_vat'))

				 ->where("PT.product_id ='".$productID."'"); //echo $query->__toString();die;

		$product = $this->getAdapter()->fetchRow($query);

		if(!empty($product)){

			$response = $product['stockist_excl_vat'];

		}		 

		return $response;

	}

	

	public function getdoctorinfo($data=array())

	{

		$doctorID = (isset($data['doctorID']) && trim($data['doctorID'])>0) ? trim($data['doctorID']) : $this->_getData['token'];

		$query = $this->_db->select()

				 ->from(array('DT'=>'crm_doctors'),array('DT.*'))

				 ->joinleft(array('ST'=>'street'),"ST.street_id=DT.street_id",array('streetName'=>'CONCAT(ST.location_code," - ",ST.street_name)'))

				 ->joininner(array('CT'=>'city'),"CT.city_id=DT.city_id",array('cityName'=>'CONCAT(CT.location_code," - ",CT.city_name)'))

				 ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array('hqName'=>'CONCAT(HQ.location_code," - ",HQ.headquater_name)'))

				 ->joininner(array('AT'=>'area'),"AT.area_id=DT.area_id",array('areaName'=>'CONCAT(AT.location_code," - ",AT.area_name)'))

				 ->joininner(array('ZT'=>'zone'),"ZT.zone_id=DT.zone_id",array('zoneName'=>'CONCAT(ZT.location_code," - ",ZT.zone_name)'))

				 ->joininner(array('RT'=>'region'),"RT.region_id=DT.region_id",array('regionName'=>'CONCAT(RT.location_code," - ",RT.region_name)'))

				 ->where("DT.doctor_id ='".$doctorID."'"); //echo $query->__toString();die;

				 //->from(array('DT'=>'crm_doctors'),array('DT.svl_number','DT.speciality','DT.qualification','DT.address1','DT.address2','DT.postcode','DT.phone','DT.mobile'))

		return $this->getAdapter()->fetchRow($query);

	}

	

	public function getBeAbmRsmDetail($data=array())

	{

		$streetID = (isset($data['streetID']) && trim($data['streetID'])>0) ? trim($data['streetID']) : $this->_getData['token'];

		$headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : $this->_getData['token'];

		$query = $this->_db->select()

				 ->from(array('EPD'=>'employee_personaldetail'),array('beLevel'=>'EPD.designation_id','beId'=>'EPD.user_id','beParent'=>'EPD.parent_id','beCode'=>'EPD.employee_code','beName'=>"CONCAT(EPD.first_name,' ',EPD.last_name)"))

				 ->joininner(array('ELD'=>'emp_locations'),"ELD.user_id=EPD.user_id",array('beHQ'=>'ELD.headquater_id'))

				 ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=ELD.headquater_id",array('beHQName'=>'HT.headquater_name'))

				 ->joinleft(array('EPD1'=>'employee_personaldetail'),"EPD1.user_id=EPD.parent_id AND EPD1.delete_status='0'",array('abmLevel'=>'EPD1.designation_id','abmId'=>'EPD1.user_id','abmParent'=>'EPD1.parent_id','abmCode'=>'EPD1.employee_code','abmName'=>"CONCAT(EPD1.first_name,' ',EPD1.last_name)"))

				 ->joinleft(array('ELD1'=>'emp_locations'),"ELD1.user_id=EPD1.user_id",array('abmHQ'=>'ELD1.headquater_id'))

				 ->joinleft(array('HT1'=>'headquater'),"HT1.headquater_id=ELD1.headquater_id",array('abmHQName'=>'HT1.headquater_name'))

				 ->joinleft(array('EPD2'=>'employee_personaldetail'),"EPD2.user_id=EPD1.parent_id AND EPD2.delete_status='0'",array('rbmLevel'=>'EPD2.designation_id','rbmId'=>'EPD2.user_id','rbmParent'=>'EPD2.parent_id','rbmCode'=>'EPD2.employee_code','rbmName'=>"CONCAT(EPD2.first_name,' ',EPD2.last_name)"))

				 ->joinleft(array('ELD2'=>'emp_locations'),"ELD2.user_id=EPD2.user_id",array('rbmHQ'=>'ELD2.headquater_id'))

				 ->joinleft(array('HT2'=>'headquater'),"HT2.headquater_id=ELD2.headquater_id",array('rbmHQName'=>'HT2.headquater_name'))

				 ->joinleft(array('EPD3'=>'employee_personaldetail'),"EPD3.user_id=EPD2.parent_id AND EPD3.delete_status='0'",array('zbmLevel'=>'EPD3.designation_id','zbmId'=>'EPD3.user_id','zbmParent'=>'EPD3.parent_id','zbmCode'=>'EPD3.employee_code','zbmName'=>"CONCAT(EPD3.first_name,' ',EPD3.last_name)"))

				 ->joinleft(array('ELD3'=>'emp_locations'),"ELD3.user_id=EPD3.user_id",array('zbmHQ'=>'ELD2.headquater_id'))

				 ->joinleft(array('HT3'=>'headquater'),"HT3.headquater_id=ELD3.headquater_id",array('zbmHQName'=>'HT2.headquater_name'))

				 ->where("EPD.designation_id=8 AND ELD.headquater_id ='".$headquarterID."' AND EPD.delete_status='0'")

				 ->limit('1'); //echo $query->__toString();die;

		$levelDetail = $this->getAdapter()->fetchRow($query); //echo "<pre>";print_r($levelDetail);echo "</pre>";die;

		

		// Check First (BE) Level User

		$firstLevelData = array('Level'=>$levelDetail['beLevel'],'UserID'=>$levelDetail['beId'],'ParentID'=>$levelDetail['beParent'],'EmpCode'=>$levelDetail['beCode'],'EmpName'=>$levelDetail['beName'],'HqID'=>$levelDetail['beHQ'],'HqName'=>$levelDetail['beHQName']);		

		$response1 = $this->filterUserLevelByDesignation($firstLevelData);

		

		// Check Second (ABM) Level User

		$secondLevelData = array('Level'=>$levelDetail['abmLevel'],'UserID'=>$levelDetail['abmId'],'ParentID'=>$levelDetail['abmParent'],'EmpCode'=>$levelDetail['abmCode'],'EmpName'=>$levelDetail['abmName'],'HqID'=>$levelDetail['abmHQ'],'HqName'=>$levelDetail['abmHQName']);		

		$response2 = $this->filterUserLevelByDesignation($secondLevelData);

		

		// Check Third (RBM) Level User

		$thirdLevelData = array('Level'=>$levelDetail['rbmLevel'],'UserID'=>$levelDetail['rbmId'],'ParentID'=>$levelDetail['rbmParent'],'EmpCode'=>$levelDetail['rbmCode'],'EmpName'=>$levelDetail['rbmName'],'HqID'=>$levelDetail['rbmHQ'],'HqName'=>$levelDetail['rbmHQName']);		

		$response3 = $this->filterUserLevelByDesignation($thirdLevelData);

		

		// Check Fourth (ZBM) Level User

		$fourthLevelData = array('Level'=>$levelDetail['zbmLevel'],'UserID'=>$levelDetail['zbmId'],'ParentID'=>$levelDetail['zbmParent'],'EmpCode'=>$levelDetail['zbmCode'],'EmpName'=>$levelDetail['zbmName'],'HqID'=>$levelDetail['zbmHQ'],'HqName'=>$levelDetail['zbmHQName']);		

		$response4 = $this->filterUserLevelByDesignation($fourthLevelData);

		

		return array_merge($response1,$response2,$response3,$response4);

	}

	

	public function filterUserLevelByDesignation($levelDetail)

	{

		$response = array();

		switch($levelDetail['Level'])

		{

			case 8 :

				$response['BE'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);

				break;

			case 7 :

				$response['ABM'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);

				break;

			case 6 :

				$response['RBM'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);

				break;

			case 5 :

				$response['ZBM'] = array('Token'=>$levelDetail['UserID'],'Parent'=>$levelDetail['ParentID'],'EmpCode'=>$levelDetail['EmpCode'],'Name'=>$levelDetail['EmpName'],'HqID'=>$levelDetail['HqID'],'HqName'=>$levelDetail['HqName']);

				break;

			default :

				break;

		}

		

		return $response;

	}

	

	public function getBeAbmRsmDetailOld($data=array())

	{

		$streetID = (isset($data['streetID']) && trim($data['streetID'])>0) ? trim($data['streetID']) : $this->_getData['token'];

		$query = $this->_db->select()

				 ->from(array('EPD'=>'employee_personaldetail'),array('beId'=>'EPD.user_id','beParent'=>'EPD.parent_id','beCode'=>'EPD.employee_code','beName'=>'EPD.first_name'))

				 ->joininner(array('ELD'=>'emp_locations'),"ELD.user_id=EPD.user_id",array('beHQ'=>'ELD.headquater_id'))

				 ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=ELD.headquater_id",array('beHQName'=>'CONCAT(HT.location_code," - ",HT.headquater_name)'))

				 ->joinleft(array('EPD1'=>'employee_personaldetail'),"EPD1.user_id=EPD.parent_id AND EPD1.designation_id=7",array('abmId'=>'EPD1.user_id','abmParent'=>'EPD1.parent_id','abmCode'=>'EPD1.employee_code','abmName'=>'EPD1.first_name'))

				 ->joinleft(array('ELD1'=>'emp_locations'),"ELD1.user_id=EPD1.user_id",array('abmHQ'=>'ELD1.headquater_id'))

				 ->joinleft(array('HT1'=>'headquater'),"HT1.headquater_id=ELD1.headquater_id",array('abmHQName'=>'CONCAT(HT1.location_code," - ",HT1.headquater_name)'))

				 ->joinleft(array('EPD2'=>'employee_personaldetail'),"EPD2.user_id=EPD1.parent_id AND EPD2.designation_id=6",array('rsmId'=>'EPD2.user_id','rsmParent'=>'EPD2.parent_id','rsmCode'=>'EPD2.employee_code','rsmName'=>'EPD2.first_name'))

				 ->joinleft(array('ELD2'=>'emp_locations'),"ELD2.user_id=EPD2.user_id",array('rsmHQ'=>'ELD2.headquater_id'))

				 ->joinleft(array('HT2'=>'headquater'),"HT2.headquater_id=ELD2.headquater_id",array('rsmHQName'=>'CONCAT(HT2.location_code," - ",HT2.headquater_name)'))

				 ->where("EPD.designation_id=8 AND ELD.street_id ='".$streetID."'")

				 ->limit('1'); //echo $query->__toString();die;

		return $this->getAdapter()->fetchRow($query);

	}

	

	public function userHierarchy($data=array())

	{

		$headquarterID = (isset($data['headquarterID']) && trim($data['headquarterID'])>0) ? trim($data['headquarterID']) : $this->_getData['token'];

		

		$query = $this->_db->select()

				 ->from(array('EL'=>'emp_locations'),array())

				 ->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=EL.user_id",array('EPD.user_id','EPD.parent_id','EPD.designation_id','EPD.employee_code','EPD.first_name'))

				 ->joininner(array('HQT'=>'headquater'),"HQT.headquater_id=EL.headquater_id",array('HQT.location_code','HQT.headquater_name'))

				 ->where("EL.headquater_id ='".$headquarterID."' AND EPD.designation_id<9 AND EPD.user_status='1'")

				 ->order('EPD.designation_id DESC'); //echo $query->__toString();die;

		$records = $this->getAdapter()->fetchAll($query);

		$be  = array();

		$abm = array();

		$rbm = array();

		$zbm = array();

		

		if(count($records)>0) {

			foreach ($records as $index=>$record) {

				switch($record['designation_id']) {

					case 8 :

						$be['Key'] 	   = $record['user_id'];

						$be['Parent']  = $record['parent_id'];

						$be['Code']    = $record['employee_code'];

						$be['Name']	   = $record['first_name'];

						$be['HQCode']  = $record['location_code'];

						$be['HQName']  = $record['headquater_name'];

						break;

					

					case 7 :

						$abm['Key']    = $record['user_id'];

						$abm['Parent'] = $record['parent_id'];

						$abm['Code']   = $record['employee_code'];

						$abm['Name']   = $record['first_name'];

						$abm['HQCode'] = $record['location_code'];

						$abm['HQName'] = $record['headquater_name'];

						break;

						

					case 6 :

						$rbm['Key']    = $record['user_id'];

						$rbm['Parent'] = $record['parent_id'];

						$rbm['Code']   = $record['employee_code'];

						$rbm['Name']   = $record['first_name'];

						$rbm['HQCode'] = $record['location_code'];

						$rbm['HQName'] = $record['headquater_name'];

						break;

					

					case 5 :

						$zbm['Key']    = $record['user_id'];

						$zbm['Parent'] = $record['parent_id'];

						$zbm['Code']   = $record['employee_code'];

						$zbm['Name']   = $record['first_name'];

						$zbm['HQCode'] = $record['location_code'];

						$zbm['HQName'] = $record['headquater_name'];

						break;

						

					default : 

						$be  = array();

						$abm = array();

						$rbm = array();

						$zbm = array();

						break;

				}

			}

		}

		

		$response = array('BE'=>$be,'ABM'=>$abm,'RBM'=>$rbm,'ZBM'=>$zbm);

		return $response;

	}

	

	public function getdoctorChemistDetail($data=array())

	{

		$doctorID = (isset($data['doctorID']) && trim($data['doctorID'])>0) ? trim($data['doctorID']) : $this->_getData['token'];

		$query = $this->_db->select()

				 ->from(array('DCT'=>'crm_doctor_chemists'),array('DCT.chemist_id'))

				 ->joininner(array('CT'=>'crm_chemists'),"CT.chemist_id=DCT.chemist_id",array('CT.legacy_code','CT.chemist_name','CT.phone'))

				 ->where("DCT.doctor_id ='".$doctorID."' AND CT.isActive='1' AND CT.isDelete='0'"); //echo $query->__toString();die;

		return $this->getAdapter()->fetchAll($query);

	}

	

	public function getChemistsFromStreet($data=array())

	{

		$streetID = (isset($data['streetID']) && trim($data['streetID'])>0) ? trim($data['streetID']) : $this->_getData['token'];

		$select = $this->_db->select()->from('crm_chemists','*')->where("patch_id='".$streetID."'")->order('chemist_name','ASC'); //echo $select->__toString();die;

		return $this->getAdapter()->fetchAll($select);

	}	

	

	public function getProductLists($data=array())

	{

		$query = $this->_db->select()->from('crm_products',array('product_id','product_name'))->where("isActive='1'")->where("isDelete='0'")->order('product_name','ASC');

		//echo $query->__toString();die;

		return $this->getAdapter()->fetchAll($query);

	}

	

	public function getAllProductPrice($data=array())

	{

		$priceLists = array();

		$query = $this->_db->select()->from('crm_products',array('product_id','stockist_excl_vat'))->where("isActive='1'")->where("isDelete='0'")->order('product_name','ASC');

		//echo $query->__toString();die;

		$productPrices = $this->getAdapter()->fetchAll($query);

		if(count($productPrices)>0)

		{

			foreach($productPrices as $productPrice)

			{

				$priceLists[$productPrice['product_id']] = $productPrice['stockist_excl_vat'];

			}

		}

		return $priceLists;

	}

	

	public function getDoctorLists($data=array())

	{

		try {

			$where = 1; //print_r($_SESSION);die;

			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;

				$this->getHeadquarters($_SESSION['AdminLoginID']);

				$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			

			$query = $this->_db->select()->from('crm_doctors','*')->where($where)->where("doctor_name!='' AND isActive='1'")->order('doctor_name','ASC'); //echo $query->__toString();die;

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 

		}

	}

	

	public function getHeadquarterLists($data=array())

	{

		try {

			$where = 1; //print_r($_SESSION);die;

			if ($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLoginID']!=44) { //echo "<pre>";print_r($_SESSION);die;

				//Commented on 31/07/2017
				//$this->getHeadquarters($_SESSION['AdminLoginID']);
				//$where = 'headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$value = implode(',',$this->geHierarchyId());
				$where = 'headquater_id IN ('.$value.')';
			}

			

			$query = $this->_db->select()->from('headquater',array('headquater_id','headquater_name','location_code'))
										 ->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=headquater.bunit_id","bunit_name")->where($where)->order('headquater_name','ASC'); 

			//echo $query->__toString();die;

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 

		}

	}

	

		public function getDesignationWiseUserLists($data=array())

	{

		try {

			$where = 1;

			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $where .= " AND EL.headquater_id IN(".$value.")";
		    }

			/*if (($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLoginID'] != 44) && $_SESSION['AdminDesignation']<$data['designationID']) {

				$this->getParents($_SESSION['AdminLoginID']); //print_r($this->_parentIDs);die;

				$where = 'parent_id IN ('.implode(',',array_unique($this->_parentIDs)).')';

			}

			else if (($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLoginID'] != 44 && $_SESSION['AdminDesignation']!= 34) && $_SESSION['AdminDesignation']>$data['designationID']) {

				$loginUserDetails = $this->getUserParents($_SESSION['AdminLoginID']);

				$loginUserDetail = array_filter(explode(",",$loginUserDetails));

				$where = 'user_id IN ('.implode(',',array_unique($loginUserDetail)).')';

			}*/

			

			$query = $this->_db->select()

							->from(array('EPD'=>'employee_personaldetail'),array('user_id','first_name','last_name','employee_code'))
							
							->joininner(array('EL'=>'emp_locations'),"EL.user_id=EPD.user_id",array(''))
							
							->where($where)

							->where('EPD.designation_id=?',$data['designationID'])

							->where("EPD.user_status='1' AND EPD.delete_status='0'")

							->order('EPD.first_name','ASC'); 

			//if($data['designationID']==8){echo $query->__toString();die;}

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 

		}

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

	

	public function getHeadquarters($loggedIn)

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

	

	public function getParents($loggedIn)

	{

		$this->_parentIDs[] = $loggedIn;

		

		$query = $this->_db->select()->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;

		$results = $this->getAdapter()->fetchAll($query);

		

		if (count($results) > 0) {

			foreach($results as $key=>$child) {

				$countChild = $this->countChild($child['user_id']);

				if($countChild['CNT'] > 0) {

					$this->_parentIDs[] = $child['user_id'];

					$this->getParents($child['user_id']);

				}

				else {

				   	$this->_parentIDs[] = $child['user_id'];

				}

			}

		}

	}

	

	/**

	 * This function returns all doctor detail which have roi data

	 **/

	public function getroidoctorlist($data=array())

	{

		try {

			$where 		 = 1;

			$filterparam = '';

			if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadquarters($_SESSION['AdminLoginID']);

				$where = 'DT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			

			//$query = $this->_db->select()->from('crm_doctors','*')->where($where)->where("isActive='1'")->order('doctor_name','ASC'); echo $query->__toString();die;

			$query = $this->_db->select()

						 ->from(array('ROI'=>'crm_roi'),array())

						 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=ROI.doctor_id",array('DT.doctor_id','DT.doctor_name'))

						 ->where($where)

						 ->where("DT.isActive='1'")

						 ->where("DT.isDelete='0'")

						 ->group('ROI.doctor_id'); //echo $query->__toString();die;

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 

		}

	}

	

	/**

	 * This function returns all headquarter detail which doctor have roi data

	 **/

	public function getroiheadquarterlists($data=array())

	{

		try {

			$where = 1;

			if ($_SESSION['AdminLevelID'] != 1) {

				$this->getHeadquarters($_SESSION['AdminLoginID']);

				$where = 'HT.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';

			}

			

			$query = $this->_db->select()

						 ->from(array('ROI'=>'crm_roi'),array())

						 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=ROI.doctor_id",array())

						 ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DT.headquater_id",array('HT.headquater_id','HT.headquater_name','HT.location_code'))

						 ->where($where)

						 ->where("DT.isActive='1'")

						 ->where("DT.isDelete='0'")

						 ->group('HT.headquater_id')

						 ->order('HT.headquater_name'); //echo $query->__toString();die;

			

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 

		}

	}

	

	/**

	 * This function returns all BE detail which doctor have roi data

	 **/

	public function getroibelists($data=array())

	{

		try {

			$where = 1; //print_r($_SESSION);die;

			$hqArray = (isset($data['hq']) && count($data['hq'])>0) ? $data['hq'] : $this->getroiheadquarterlists();

			$hqID    = $this->filterID(array('arrayVariable'=>$hqArray,'filterIndex'=>'headquater_id')); //echo "<pre>";print_r($hqID);die;

			$where   = (count($hqID)>0) ? 'EL.headquater_id IN ('.implode(',',$hqID).')' : 1;

			

			$query = $this->_db->select()

							->from(array('EPD'=>'employee_personaldetail'),array('EPD.user_id','EPD.first_name','EPD.last_name','EPD.employee_code'))

							->joininner(array('EL'=>'emp_locations'),"EL.user_id=EPD.user_id",array())

							->where($where)

							->where('EPD.designation_id=?',$data['designationID'])

							->where("EPD.user_status='1' AND EPD.delete_status='0'")

							->order('EPD.first_name','ASC'); //echo $query->__toString();die;

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 

		}

	}

	

	/**

	 * This function returns all headquarter detail which doctor have roi data

	 **/

	public function filterID($data=array())

	{

		$arrayVariable = (isset($data['arrayVariable']) && count($data['arrayVariable'])>0) ? $data['arrayVariable'] : array();

		$filterIndex   = (isset($data['filterIndex']) && !empty($data['arrayVariable'])) ? $data['filterIndex'] : '';

		$returnArray   = array();

		if(count($arrayVariable)>0 && !empty($filterIndex)) {

			foreach($arrayVariable as $value) {

				$returnArray[] = $value[$filterIndex];

			}

		}

		return $returnArray;

	}

	

	/**

	 * This function returns all patch detail which doctor have roi data

	 **/

	public function getPatchList($data=array())

	{

		$hqID = (isset($data['hq']) && !empty($data['hq'])) ? trim($data['hq']) : 0;

		$query = $this->_db->select()

							->from(array('patchcodes'),array('patch_id','patch_name'))

							->where('headquater_id='.$hqID.' AND isActive="1" AND isDelete="0"')

							->order('patch_name','ASC'); //echo $query->__toString();die;

		return $this->getAdapter()->fetchAll($query);

	}

	

	/**

	 * This function returns all patch detail which doctor have roi data

	 **/

	public function getpatchlocation($data=array())

	{

		$patchID = (isset($data['patchID']) && !empty($data['patchID'])) ? trim($data['patchID']) : 0;

		$select = $this->_db->select()

		 				->from(array('PC'=>'patchcodes'),array())

						->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array('cname'=>'city_name'))

						//->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array('headquater_name'))

		 				->joininner(array('AT'=>'area'),"AT.area_id=PC.area_id",array('aname'=>'area_name'))

						->joininner(array('RT'=>'region'),"RT.region_id=PC.region_id",array('rname'=>'region_name'))

		 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=PC.zone_id",array('zname'=>'zone_name'))

						//->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=PC.bunit_id",array('bname'=>'bunit_name'))

						->where('PC.patch_id='.$patchID.' AND PC.isActive="1" AND PC.isDelete="0"'); //echo $select->__toString();die;

		return $this->getAdapter()->fetchRow($select);

	}

	

	/**

	 * This function returns all activity detail

	 **/

	public function getActivityLists($data=array())

	{

		try {

			//$query = $this->_db->select()->from('crm_activity','*')->where("isActive='1'")->order('activity_name','ASC');

			$query = $this->_db->select()->from('crm_activity','*')->where('isActive="1" AND isDelete="0"')->order('activity_name','ASC');

			//echo $query->__toString();die;

			return $this->getAdapter()->fetchAll($query);

		}

		catch(Exception $e){

			$_SESSION[ERROR_MSG] =  'There Is Some Error Please Try Agan'; 

		}

	}

	

	#########################################################################################################################################

	#########################################################################################################################################

	

	/**

	 * Previously Defined Functions

	 **/

  	public function getSalaryTemplateAmount(){

    $result = array();

	   $select = $this->_db->select()

		 				->from('salary_template',array('*'))

						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 

								 AND designation_id ='".$this->_getData['designation_id']."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result;

  }

  public function getSalaryEmployee(){

      $select = $this->_db->select()

		 				->from('employee_salary_amount',array('*'))

						->joinleft(array('SH'=>'salary_head'),'SH.salaryhead_id=employee_salary_amount.salaryhead_id',array())

						->where("user_id='".$this->_getData['user_id']."'")

						->order("employee_salary_amount.salaryheade_type ASC")

						->order("sequence ASC");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;

  }

  public function getAmountByTemplateId($template_id,$head_id){

       $select = $this->_db->select()

		 				->from('salary_template_amount',array('*'))

						->where("salary_template_id='".$template_id."' AND salaryhead_id ='".$head_id."'");

						//echo $select->__toString();die;

	  $result = $this->getAdapter()->fetchRow($select);

	  return $result['amount'];

  }

  public function getLeaveDetail(){

    if(!empty($this->_getData['user_id'])){

	   $select = $this->_db->select()

		 				->from(array('LD'=>'emp_leaves'),array('leave_id as leave_type_id','no_of_leave as leave_no','aproval_authority','user_id'))

						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_id",array('typeName'))

						->where("user_id='".$this->_getData['user_id']."'");

	   $result = $this->getAdapter()->fetchAll($select);

	}

	if(empty( $result)){

	  /*$sixmonthback = strtotime(date('Y-m-d',mktime(0, 0, 0, date("m")-6,date('d'),  date("Y"))));

	  $joiningdate =  strtotime($this->_getData['doj']);

		

		$year1 = date('Y', $sixmonthback);

		$year2 = date('Y', $joiningdate);

		

		$month1 = date('m', $sixmonthback);

		$month2 = date('m', $joiningdate);

		

	  $diff = (($year1-$year2) * 12) + ($month1 - $month2);*/

	  //print_r($diff);die;

      if($this->_getData['emp_type']==2){

      $select = $this->_db->select()

		 				->from(array('LD'=>'leavedistributions'),array('designation_id','leave_type_id','prob_leaveno as leave_no'))

						->joinleft(array('LP'=>'leaveapprovals'),"LP.designation_id=LD.designation_id",array('approval_no'))

						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_type_id",array('typeName'))

						->where("LD.designation_id='".$this->_getData['designation_id']."'");

      }else{

	      $select = $this->_db->select()

		 				->from(array('LD'=>'leavedistributions'),array('*'))

						->joinleft(array('LP'=>'leaveapprovals'),"LP.designation_id=LD.designation_id",array('approval_no'))

						->joinleft(array('LT'=>'leavetypes'),"LT.typeID=LD.leave_type_id",array('typeName'))

						->where("LD.designation_id='".$this->_getData['designation_id']."'");

	  }

						//echo $select->__toString();die;

	    $result = $this->getAdapter()->fetchAll($select);					

	 } 

	

	  return $result;

  }

 public function getParnetByDesignationId(){

     $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('*'))

						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))

						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())

						->where("DT1.designation_level>DT.designation_level  AND ED.delete_status='0'");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

	   return $result;

 } 

 public function ChangeStatusValue(){ 

    $this->_db->update($this->_getData['table'],array($this->_getData['change_fild']=>$this->_getData['changeMode']),"".$this->_getData['confield']."=".$this->_getData['con_id']."");

	echo $this->_getData['changeMode'];

	exit;

 }

 public function getUserListByDesignation(){

			$select = $this->_db->select()

								->from(array('ED'=>'employee_personaldetail'),array('*'))

								->where("designation_id='".$this->_getData['designation_id']."' AND department_id='".$this->_getData['department_id']."'");

								//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		$string = '<option value="">--Select--</option>';

		foreach($result as $users){

		  $selected = '';

		  if($this->_getData['user_id']==$users['user_id']){

		   $selected = 'selected="selected"';

		  }

		  $string .= '<option value="'.$users['user_id'].'" '.$selected.'>'.$users['employee_code'].'-'.$users['first_name'].' '.$users['last_name'].'</option>';

		}

	  echo $string;exit;		

 } 

  public function geteXtraHead(){

    $string = '';

   if(!empty($this->_getData['salaryhead'])){

    $string ='<tr class="odd"><td>'. $this->getSalaryHeadName($this->_getData['salaryhead']).'</td><td><input type="text" name="extra_amount[]" class="input-short"><input type="hidden" name="extra_head[]" value="'.$this->_getData['salaryhead'].'"><input type="hidden" name="extra_type[]" value="'.$this->_getData['type'].'"></td></tr>';

  }

	echo $string;exit;

 } 

 

  public function getExpenseHead(){

    if(!empty($this->_getData['exp_setting_id'])){

		$select = $this->_db->select()

									->from(array('ETA'=>'expense_template_amount'),array('*'))

									->joinleft(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))

									->where("exp_setting_id='".$this->_getData['exp_setting_id']."'"); 

	 }else{

	    $select = $this->_db->select()

									->from(array('EH'=>'expense_head'),array('*'))

									->where("expense_type='".$this->_getData['expense_type']."'");  

	 }							

								//echo $select->__toString();die;

	 $results = $this->getAdapter()->fetchAll($select);

	 $string = '';

	 if(!empty($results)){

	   foreach($results as $heads){

	      $string .= '<tr class="odd"><td><strong>'.$heads['head_name'].'</strong><input type="hidden" name="head_id[]" value="'.$heads['head_id'].'"></td><td><input type="text" name="expense_amount[]" value="'.$heads['expense_amount'].'" class="input-short"></td></tr>';

	   }

	 }

	 echo $string;exit();

  }

  

  public function getExpenseTemplate(){

    if(!empty($this->_getData['user_id'])){

      $select = $this->_db->select()

		 				->from(array('EEA'=>'emp_expense_amount'),array('*'))

						->joinleft(array('EH'=>'expense_head'),"EH.head_id=EEA.head_id",array('head_name'))

						->where("user_id='".$this->_getData['user_id']."'");

	  $result = $this->getAdapter()->fetchAll($select);

	  }//print_r($this->_getData['user_id']);print_r($result);die;

	 if(empty($this->_getData['user_id']) || empty($result)){

	   $select = $this->_db->select()

		 				->from(array('ES'=>'expense_setting'),array('*'))

						->joinleft(array('ETA'=>'expense_template_amount'),"ES.exp_setting_id=ETA.exp_setting_id",array('*'))

						->joinleft(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))

						->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 

								 AND designation_id ='".$this->_getData['designation_id']."'");

	  $result = $this->getAdapter()->fetchAll($select); 

	 } 

	 $string = '';

	 foreach($result as $expenses){

	    $string .= '<tr><td>'.$expenses['head_name'].'<input type="hidden" name="head_id[]" value="'.$expenses['head_id'].'"></td><td><input type="text" name="expense_amount[]" value="'.$expenses['expense_amount'].'" class="input-short"></td></tr>';

	 }

	 $string .= '<tr><td colspan="2"><table id="expenseauthority"><tr><th colspan="2">Expense Approval Authority</th></tr>';

	 $approval = $this->getNumberOfApprovalExpense();

	 $getapprovals = $this->getUsersExpenseApproval();

	 $total_app = ($approval>0)?$approval:3;

	 if(!empty($getapprovals)){

	    foreach($getapprovals as $getapproval){

		   $rest  = $total_app-1;

		   $parent = $this->expenseApproveAuthority($getapproval['parent_id'],$total_app,$rest,$getapproval['approval_user_id'],$getapproval['position']);

		   $string .= $parent[0];

		}   

	 }else{

	      $parent = $this->expenseApproveAuthority($this->_data['parent_id'],$total_app,$total_app-1,0,0);

	 	  $string .= $parent[0];

	 }

	 $string .= '</table>';

	 

	 $string .= '<table><th colspan="2">Extra Expense Head</th>';

	$string .= '<tr><td><select name="head_id[]" id="head_id" onchange="getExpenseAmount(this.value);" class="input-medium">';

	$string .=  '<option value="">--Select Site--</option>';

	$allexpensehead = $this->getAllExpenseHead();

    foreach($allexpensehead as $i=>$expenses){

		$string .='<option value="'.$expenses['head_id'].'">'.$expenses['head_name'].'</option>';

	 }

	 $string .='</select></td><td><input name="expense_amount[]" id="expense_amount" class="input-medium"></td></tr></table></td></tr>';

	 

	echo $string;exit;   

  }

  

  /*public function expenseApproveAuthority($parent_id,$i=1){

     $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('*'))

						->joininner(array('DES'=>'designation'),"DES.designation_id=UD.designation_id",array('designation_name','designation_code'))						->where("user_id='".$parent_id."'");

	$result = $this->getAdapter()->fetchRow($select);

	$parnetlist = $this->getParnetByDesignationId();

   	if(!empty($result)){

     $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="'.$parent_id.'"></td><td>'.$result['first_name'].' '.$result['last_name'].'-'.$result['designation_code'].'</td></tr>';

	}elseif(!empty($parnetlist)){

	     $string = '<tr><td>Approval Authority '.$i.'</td><td><select name=expese_approve_auth[]>';

			 foreach($parnetlist as $parnet){

				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 

			 }

   		 $string .= '</select></td></tr>';

	}else{

	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="44"></td><td> Super Admin</td></tr>';

	}

	return array($string,$result['parent_id']);

  }*/

  

  public function expenseApproveAuthority($parent_id,$total,$rest,$parent,$position){

    $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('*'))

						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))

						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())

						->where("DT1.designation_level>=DT.designation_id");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

   	if(!empty($result)){

	     $string = '<tr><td><select name=expese_approve_auth[]>';

		 $string .='<option value="">Select Approval Authority</option>';

			 foreach($result as $parnet){

			     $selected='';

			     if($parent==$parnet['user_id']){

				   $selected = 'selected="selected"'; 

				}

				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 

			 }

   		  $string .= '</select></td>';

		  

		 $string .= '<td><select name=position[] onchange="getExpenseAuthority(this.value,'.$this->_getData['designation_id'].','.$total.','.$rest.')">';

		 $string .='<option value="">Select Approval Position</option>';

		 //$iposition = ($this->_data['currentvalue']>0)?$this->_data['currentvalue']:1; 

			 for($i=1;$i<$total;$i++){

			    $selected='';

			     if($i==$position){

				   $selected = 'selected="selected"'; 

				}

				$string .='<option value="'.$i.'" '.$selected.'>Approval Authority'.$i.'</option>'; 

			 }

		 $selected='';

		  if($total==$position){

		   $selected = 'selected="selected"'; 

		 }

		 $string .='<option value="'.$total.'" '.$selected.'>Final Approval Authority</option>';	 

   		 $string .= '</select></td></tr>';

	}else{

	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="expese_approve_auth[]" value="44"></td><td>Supper Admin</td></tr>';

	}

	return array($string,$result['parent_id']);

  }

  

  public function getUserExpenseAmount(){

       $select = $this->_db->select()

	  			->from(array('EEA'=>'emp_expense_amount'),array('*'))

				->where("user_id='".$_SESSION['AdminLoginID']."' AND head_id='".$this->_getData['head_id']."'");

	   $amount =  $this->getAdapter()->fetchRow($select);

	   echo $amount['expense_amount'];exit();

  }

  

  public function leaaveApproveAuthority($parent_id,$total,$rest,$parent,$position){

     $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('*'))

						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))

						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())

						->where("DT1.designation_level>=DT.designation_id");

						//echo $select->__toString();die;

	   $result = $this->getAdapter()->fetchAll($select);

   	if(!empty($result)){

	     $string = '<tr><td><select name=leave_approve_auth[]>';

		 $string .='<option value="">Select Approval Authority</option>';

			 foreach($result as $parnet){

			     $selected='';

			     if($parent==$parnet['user_id']){

				   $selected = 'selected="selected"'; 

				}

				$string .='<option value="'.$parnet['user_id'].'" '.$selected.'>'.$parnet['first_name'].' '.$parnet['last_name'].'-'.$parnet['designation_code'].'</option>'; 

			 }

   		  $string .= '</select></td>';

		  

		 $string .= '<td><select name=authority_position[] onchange="getAuthority(this.value,'.$this->_getData['designation_id'].','.$total.','.$rest.')">';

		 $string .='<option value="">Select Approval Position</option>';

		 //$iposition = ($this->_data['currentvalue']>0)?$this->_data['currentvalue']:1; 

			 for($i=1;$i<$total;$i++){

			    $selected='';

			     if($i==$position){

				   $selected = 'selected="selected"'; 

				}

				$string .='<option value="'.$i.'" '.$selected.'>Approval Authority'.$i.'</option>'; 

			 }

		 $selected='';

		  if($total==$position){

		   $selected = 'selected="selected"'; 

		 }

		 $string .='<option value="'.$total.'" '.$selected.'>Final Approval Authority</option>';	 

   		 $string .= '</select></td></tr>';

	}else{

	  $string = '<tr><td>Approval Authority '.$i.'<input type="hidden" name="leave_approve_auth[]" value="44"></td><td>Supper Admin</td></tr>';

	}

	return array($string,$result['parent_id']);

  }

  public function getNumberOfApprovalLeave(){

     $select = $this->_db->select()

		 				->from(array('LA'=>'leaveapprovals'),array('*'))

						->where("designation_id	='".$this->_getData['designation_id']."'");

	 $result = $this->getAdapter()->fetchRow($select);

	 return $result['approval_no'];

  }

  public function getNumberOfApprovalExpense(){

    $select = $this->_db->select()

		 				->from(array('ES'=>'expense_setting'),array('*'))

						->where("designation_id ='".$this->_getData['designation_id']."'");

	  $result = $this->getAdapter()->fetchRow($select); 

	 return $result['number_of_approval'];

  }

 

 public function getUsersleaveApproval(){

  	$select = $this->_db->select()

		 				->from(array('ELA'=>'emp_leave_approval'),array('*','approval_user_id as parent_id'))

						->where("user_id='".$this->_getData['user_id']."'");

	  $result = $this->getAdapter()->fetchAll($select); 

	 return $result;

  }

  

 public function getUsersExpenseApproval(){

 	$select = $this->_db->select()

		 				->from(array('ELA'=>'expense_approval'),array('*','approval_user_id as parent_id'))

						->where("user_id='".$this->_getData['user_id']."'");

	  $result = $this->getAdapter()->fetchAll($select); 

	 return $result;

 }

 public function updateEmpExpense(){

   $this->_db->update('emp_expenses',array('head_id'=>$this->_getData['head_id'],'expense_amount'=>$this->_getData['expense_amount'],'expense_detail'=>$this->_getData['expense_detail'],'expense_date'=>$this->_getData['expense_date']),"expense_id='".$this->_getData['expense_id']."'");

   return true;

   $_SESSION[SUCCESS_MSG]= 'Expense Updated Successfully';

 }

  public function getRecordrecordOfLowerEmp(){

     if($this->_getData['Level']==1){

	 $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array(''))

						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array(''))

						->joinleft(array('DT1'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name as Value','designation_id AS Key'))

						->where("DT1.designation_level>=DT.designation_level  AND ED.user_id='".$_SESSION['AdminLoginID']."'");

						

	  }

	 $results = $this->getAdapter()->fetchAll($select); 

	  $string = '<option value="">---Select--</option>';

		 if($results){

			 foreach($results as $Record){

				$selected = '';

				if($this->_data['Key']==$Record['Key']){

				  $selected = 'selected="selected"';

				}

				$string .='<option value="'.$Record['Key'].'" '.$selected.'>'.$Record['Value'].'</option>'; 

			 }

	     }

		 return $string;

     }

    public function deletesalaryhead(){

        $this->_db->delete("salary_list","salaryhead_id='".$this->_getData['salaryhead_id']."' AND user_id='".$this->_getData['user_id']."' AND date='".$this->_getData['date']."'");

        return 'Salary Head has been deleted successfully';exit;

    }
	
	public function getChildselectedUser(){
			$where = ($this->_getData['user_id']!='')? "parent_id='".$this->_getData['user_id']."'" : '1';
			$select = $this->_db->select()

								->from(array('ED'=>'employee_personaldetail'),array('*'))
								->where($where)	
								->where("designation_id='".$this->_getData['design_id']."'")

								->where("user_status='1' AND delete_status='0'")
	
								->order('first_name','ASC');
								//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);
		if($this->_getData['design_id'] == 8){
		$string = '<option value="">-- Select BE --</option>';
		}elseif($this->_getData['design_id'] == 7){
		$string = '<option value="">-- Select ABM --</option>';
		}elseif($this->_getData['design_id'] == 6){
		$string = '<option value="">-- Select RBM --</option>';
		}else{
		$string = '<option value="">-- Select --</option>';
		}
		foreach($result as $users){/*

		  $selected = '';

		  if($this->_getData['user_id']==$users['user_id']){

		   $selected = 'selected="selected"';

		  }*/

		  $string .= '<option value="'.Class_Encryption::encode($users['user_id']).'">'.$users['employee_code'].' - '.$users['first_name'].' '.$users['last_name'].'</option>';

		}

	  echo $string;exit;		

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