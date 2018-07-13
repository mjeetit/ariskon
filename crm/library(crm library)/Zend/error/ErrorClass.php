<?php
/*
 * CommonFunction of Logicparcel ERP System
 * @PHP version  : 5.1.37
 * @ZEND version : 1.11
 * @author       : SJM Softech Private Limited <contact@sjmsoftech.com>
 * @created on   : 14-Dec,2012
 * @link         : http://www.logicparcel.net
 * Description   : 
 */
ini_set("memory_limit", "-1");
class Zend_Error_Class extends Zend_Custom_Class {
		public $inputdata = NULL;
		public $outputdata = NULL;
	   
	public function generateRouteInformationForImport () {	
	  
	//=============================== INPUT PARAMETERS =================================================//
	//$this->outputdata['CountryCode'] 			= $this->inputdata['CountryCode'];
	$this->outputdata['ServiceCode'] 			= $this->inputdata['serviceCode'];
	//$this->outputdata['RoutingDepot'] 			= $this->inputdata[ROUTE_DEPOT_NO];
	//$this->outputdata['DestinationPostCode']  	= $this->inputdata[SHIPMENT_ZIPCODE];
	//$this->outputdata['DestinationArea'] 		= $this->inputdata[SHIPMENT_STREET];
	//$this->outputdata['DestinationCity'] 		= $this->inputdata[SHIPMENT_CITY];
	
	$rsCountryCode =$this->validateCountry();
	if($rsCountryCode=='') {
		return 1;
	}
	
	//============================ Validate Routing Depot from DEPOTS table =================================
	$rsDepots = $this->ValidateRoutingDepot();
	if($this->inputdata[ROUTE_DEPOT_NO] <= 0) {
		return 4;
	} 
	//=========================== Validate service code from SERVICE table ================================
	$rsService = $this->ValidateServiceCode();
	if(empty($rsService)) {
		return 5;
	}
	$rsServiceInfo = $this->ServiceInfo();
	
	$this->outputdata['DPDService'] 		= $rsService['ServiceText'];
	$this->outputdata['Indentification'] 	= $rsService['ServiceMark'];
	$this->outputdata['ServiceInfo'] 	= $rsServiceInfo['ServicefFieldInfo'];
	
	//============================= Validate country from routes table ==================================
	$rsRouteCountry = $this->ValidCountryRoute();
	if($rsRouteCountry['COUNTRY_COUNT'] <= 0) {
		return 1;
	}
	
	    $rsRouteServiceCode = $this->RouteServiceCode();
		if($rsRouteServiceCode['COUNT_ROUTE'] <= 0)
		{  
				$rsRouteServiceCode = $this->RouteServiceCodeBetweenPostcode();
				if($rsRouteServiceCode['COUNT_ROUTE']  <= 0)
				{
					$newPostcode = $this->inputdata[SHIPMENT_ZIPCODE] - 400;
					$newPostcode = ($newPostcode > 0) ? $newPostcode : '1';
					$postCodeQuery = "AND CAST(BeginPostCode AS SIGNED) >= '".$newPostcode."' AND BeginPostCode < '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode<='".$this->inputdata[SHIPMENT_ZIPCODE]."'";
					$rsRouteServiceCode = $this->RouteCountWithPostcodeQuery($postCodeQuery);
					if($rsRouteServiceCode['COUNT_ROUTE'] <= 0) {
						return 5;
					}
					else {
						$serviceCodeQuery = "AND ServiceCodes = '' ";
					}
				}else{
						$postCodeQuery = "AND BeginPostCode <= '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode >= '".$this->inputdata[SHIPMENT_ZIPCODE]."' ";
						$serviceCodeQuery = "AND ServiceCodes = '' ";
					}
			}else {
				$serviceCodeQuery = "AND ServiceCodes = '' ";
				$postCodeQuery = "AND BeginPostCode = '".$this->inputdata[SHIPMENT_ZIPCODE]."'";
		}
	$rsRouteDepotsCountry = $this->ISOAlphaCountryCode();
	if($rsRouteDepotsCountry=='') {
		return 7;
	}
	$rsRouteRoutingDepot = $this->RouteRoutingDepotWithPlaces($postCodeQuery,$serviceCodeQuery,$rsRouteDepotsCountry);
	if($rsRouteRoutingDepot > 0) { 
		$routingDepotQuery = " AND RoutingPlaces LIKE '%".$rsRouteDepotsCountry."%' ";
	}
	else
	{  
		$rsRouteRoutingDepot = $this->RouteRoutingDepotWithoutPlaces($postCodeQuery,$serviceCodeQuery);
		if($rsRouteRoutingDepot <= 0) {
			return 4;
		}
		$routingDepotQuery = " AND RoutingPlaces = '' ";
	}
	/*$sending_date = $date.' '.$time;
	$rsRouteSendingDate = $this->RouteRoutingDepotWithSendinDate($postCodeQuery,$serviceCodeQuery,$routingDepotQuery,$sending_date);
	if(count($rsRouteSendingDate) > 0) {
		//
	}
	else
	{*/
		$rsRouteSendingDate = $this->RouteRoutingDepotWithoutSendinDate($postCodeQuery,$serviceCodeQuery,$routingDepotQuery);
		if(count($rsRouteSendingDate) <= 0) {
			return 8;
		}
	//}
	
	if(count($rsRouteSendingDate) > 1) {
		return 9;
	}
	
	foreach($rsRouteSendingDate as $outdata){
		$this->outputdata['OSort'] 				= $outdata['O_Sort'];
		$this->outputdata['DSort'] 				= $outdata['D_Sort'];
		$this->outputdata['GroupingPriority'] 	= $outdata['GroupingPriority'];
		$this->outputdata['BarcodeID'] 			= $outdata['BarcodeID'];
		$this->outputdata['DestinationDepot'] 	= $outdata['D_Depot'];
		$this->outputdata['Version'] 			= date('Ymd');
	}
	
	return $this->outputdata;
   }
   
   
    public function generateRouteInformation () {	
	   	//$this->outputdata['CountryCode'] 			= $this->inputdata['CountryCode'];
		$this->outputdata['ServiceCode'] 			= $this->inputdata['serviceCode'];
		//$this->outputdata['RoutingDepot'] 			= $this->inputdata[ROUTE_DEPOT_NO];
		//$this->outputdata['DestinationPostCode']  	= $this->inputdata[SHIPMENT_ZIPCODE];
		//$this->outputdata['DestinationArea'] 		= $this->inputdata[SHIPMENT_STREET];
		//$this->outputdata['DestinationCity'] 		= $this->inputdata[SHIPMENT_CITY];
	//================================= Validate Country table ==========================================
	$rsCountryCode =$this->validateCountry();
	if($rsCountryCode=='') {
		return 1;
	}
	$this->outputdata['Country'] 			= $rsCountryCode;
	//============================== Validate Post Code ===================================================
	if(trim($this->inputdata[SHIPMENT_ZIPCODE]) == '') {
		if(trim($this->inputdata[SHIPMENT_STREET]) == '' || trim($this->inputdata[SHIPMENT_CITY]) == '') {
			return 2;
		}
		//======================== Translate PostCode from lovcation table ===============================
		$rsTranslation = $this->validatePostCode();
		if(empty($rsTranslation)) {
			return 3;
		}
		//$this->outputdata['DestinationPostCode'] = $rsTranslation['PostCode'];
	 }
	//============================ Validate Routing Depot from DEPOTS table =================================
	$rsDepots = $this->ValidateRoutingDepot();
	if($this->inputdata[ROUTE_DEPOT_NO] <= 0) {
		return 4;
	} 
	
	//=========================== Validate service code from SERVICE table ================================
	$rsService = $this->ValidateServiceCode();
	if(empty($rsService)) {
		return 5;
	}
	$rsServiceInfo = $this->ServiceInfo();
	
	$this->outputdata['DPDService'] 		= $rsService['ServiceText'];
	$this->outputdata['Indentification'] 	= $rsService['ServiceMark'];
	$this->outputdata['ServiceInfo'] 		= $rsServiceInfo['ServicefFieldInfo'];
	
	//============================= Validate country from routes table ==================================
	$rsRouteCountry = $this->ValidCountryRoute();
	if($rsRouteCountry['COUNTRY_COUNT'] <= 0) {
		return 1;
	}
	
	//============================= Validate Postcode from routes table ==================================
	$rsRoutePostCode = $this->validatePostcodeFromRoute();
	if($rsRoutePostCode > 0) {
		$postCodeQuery = "AND BeginPostCode = '".$this->inputdata[SHIPMENT_ZIPCODE]."'";
		$postCodeQueryNo = 1;
	}
	else
	{
		$rsRoutePostCode = $this->RoutePostCodeBetweenPostcode();
		if($rsRoutePostCode> 0) { 
			$postCodeQuery = "AND BeginPostCode <= '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode >= '".$this->inputdata[SHIPMENT_ZIPCODE]."' ";
			$postCodeQueryNo = 2;
		}
		else
		{ 
			$rsRoutePostCode = $this->PostcodeCountWithBlankBiginingPostcode();
			if($rsRoutePostCode <= 0) {
				return 6;
			}
			$postCodeQuery = " AND BeginPostCode = '' ";
			$postCodeQueryNo = 3;
		}
	}
	
	//============================= Validate Service Code from routes table ==================================
	$rsRouteServiceCode = $this->ValidateServiceCodeFromRoute($postCodeQuery);
	if($rsRouteServiceCode > 0) { 
		$serviceCodeQuery = "AND (ServiceCodes REGEXP '".$serviceCode."[[:>:]]' OR ServiceCodes REGEXP '[[:<:]]S".$serviceCode."') ";
	}
	else
	{ 
		$rsRouteServiceCode = $this->ValidateServiceCodeWithPostcodeQueryAndBlankService($postCodeQuery);
		if($rsRouteServiceCode <= 0)
		{  
			if($postCodeQueryNo == 1)
			{
				$rsRouteServiceCode = $this->RouteServiceCodeBetweenPostcode();
				if($rsRouteServiceCode<= 0)
				{
					$newPostcode = $this->inputdata[SHIPMENT_ZIPCODE] - 400;
					$newPostcode = ($newPostcode > 0) ? $newPostcode : '1';
					$postCodeQuery = "AND CAST(BeginPostCode AS SIGNED) >= '".$newPostcode."' AND BeginPostCode < '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode<='".$this->inputdata[SHIPMENT_ZIPCODE]."'";
					$rsRouteServiceCode = $this->RouteCountWithPostcodeQuery($postCodeQuery);
					if($rsRouteServiceCode['COUNT_ROUTE'] <= 0) {
						return 5;
					}
					else {
						$serviceCodeQuery = "AND ServiceCodes = '' ";
					}
				}
				else
					{
						$postCodeQuery = "AND BeginPostCode <= '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode >= '".$this->inputdata[SHIPMENT_ZIPCODE]."' ";
						$serviceCodeQuery = "AND ServiceCodes = '' ";
						
					}

			}
			else
			{
				$newPostcode = $this->inputdata[SHIPMENT_ZIPCODE] - 400;
				$newPostcode = ($newPostcode > 0) ? $newPostcode : '1';
				$postCodeQuery = "AND CAST(BeginPostCode AS SIGNED) >= '".$newPostcode."' AND BeginPostCode < '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode<='".$this->inputdata[SHIPMENT_ZIPCODE]."'";
				$rsRouteServiceCode = $this->RouteCountWithPostcodeQuery($postCodeQuery);
				if($rsRouteServiceCode <= 0) {
					return 5;
				}
				else {
					$serviceCodeQuery = "AND ServiceCodes = '' ";
				}

			}
			

		}
		else {
			$serviceCodeQuery = "AND ServiceCodes = '' ";
		}
	}
	//echo $Query;die;

	$rsRouteDepotsCountry = $this->ISOAlphaCountryCode();
	if($rsRouteDepotsCountry=='') {
		return 7;
	}
	$rsRouteRoutingDepot = $this->RouteRoutingDepotWithPlaces($postCodeQuery,$serviceCodeQuery,$rsRouteDepotsCountry);
	if($rsRouteRoutingDepot > 0) { 
		$routingDepotQuery = " AND RoutingPlaces LIKE '%".$rsRouteDepotsCountry."%' ";
	}
	else
	{  
		$rsRouteRoutingDepot = $this->RouteRoutingDepotWithoutPlaces($postCodeQuery,$serviceCodeQuery);
		if($rsRouteRoutingDepot <= 0) {
			return 4;
		}
		$routingDepotQuery = " AND RoutingPlaces = '' ";
	}
	$rsRouteSendingDate = $this->RouteRoutingDepotWithoutSendinDate($postCodeQuery,$serviceCodeQuery,$routingDepotQuery);
		if(count($rsRouteSendingDate) <= 0) {
			return 8;
		}
	
	if(count($rsRouteSendingDate) > 1) {
		return 9;
	}
	
	foreach($rsRouteSendingDate as $outdata){
		$this->outputdata['OSort'] 				= $outdata['O_Sort'];
		$this->outputdata['DSort'] 				= $outdata['D_Sort'];
		$this->outputdata['GroupingPriority'] 	= $outdata['GroupingPriority'];
		$this->outputdata['BarcodeID'] 			= $outdata['BarcodeID'];
		$this->outputdata['DestinationDepot'] 	= $outdata['D_Depot'];
		$this->outputdata['Version'] 			= date('Ymd');
	}
	return $this->outputdata;
	}
	 //============================Validate Servicecode With Blanck Servicecode and PostcodeQuery========
	  public function ValidateServiceCodeWithPostcodeQueryAndBlankService($postCodeQuery){
	     $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as SERVICE_CODE_COUNT'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery)
								->where("ServiceCodes=''");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['SERVICE_CODE_COUNT'];			
				
	  }
	//=============================VAlidate ServiceCode From ROute Table =================================
	  public function ValidateServiceCodeFromRoute($postCodeQuery){
	     $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as SERVICE_CODE_COUNT'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery)
								->where("ServiceCodes REGEXP '".$this->inputdata['serviceCode']."[[:>:]]' OR ServiceCodes REGEXP '[[:<:]]S".$this->inputdata['serviceCode']."'");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['SERVICE_CODE_COUNT'];			
				
	  }
	//=============================PostCode Count With Blanck Biginig Postcode============================
	public function PostcodeCountWithBlankBiginingPostcode(){
	   $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as POST_CODE_COUNT'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'")
								->where("BeginPostCode <= ''");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['POST_CODE_COUNT'];
	}
	//=============================Postcode Code Between Bigin,end Postcode==================================
    public function RoutePostCodeBetweenPostcode(){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNT_ROUTE_POSTCODE'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'")
								->where("BeginPostCode <= '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode >= '".$this->inputdata[SHIPMENT_ZIPCODE]."'");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNT_ROUTE_POSTCODE'];
   }
	//==================================Validate PostCode fROM rOUTE tABLE=========================
	public function validatePostcodeFromRoute(){
	    $select = $this->_db->select()
									->from('forwarderdpdroutes',array('COUNT(1) as COUNT_POSTCODE'))
									->where("DestinationCountry='".$this->inputdata['CountryCode']."'")
									->where("BeginPostCode = '".$this->inputdata[SHIPMENT_ZIPCODE]."'");
									//print_r($select->__toString());die;
			$record = $this->getAdapter()->fetchRow($select);
		return  $record['COUNT_POSTCODE'];
	}	
	//================================= Validate Post Code ========================================== 
	public function validatePostCode(){
			$select = $this->_db->select()
									->from('forwarderdpdlocation',array('PostCode'))
									->where("AreaName='".$this->inputdata[SHIPMENT_STREET]."'")
									->where("CityName = '".$this->inputdata[SHIPMENT_CITY]."'");
									//print_r($select->__toString());die;
			$record = $this->getAdapter()->fetchRow($select);
		return  $record;
	}
	//================================= Validate Country table ========================================== 
	public function validateCountry(){
			$select = $this->_db->select()
									->from('forwarderdpdcountries',array('countrycode'))
									->where("a2countrycode='".$this->inputdata['CountryCode']."'");
									//print_r($select->__toString());die;
			$record = $this->getAdapter()->fetchRow($select);
		return  $record['countrycode'];
	}
  //============================ Validate Routing Depot from DEPOTS table =================================
   public function ValidateRoutingDepot(){
      	$select = $this->_db->select()
									->from('forwarderdpddepots',array('COUNT(1) as COUNT_DEPOT'))
									->where("GeoPostDepotNumber='".$this->inputdata[ROUTE_DEPOT_NO]."'");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	  return  $record['COUNT_DEPOT'];
   }
   //=========================== Validate service code ================================
   public function ValidateServiceCode(){
       $select = $this->_db->select()
								->from('forwarderdpdservices',array('ServiceText','ServiceMark'))
								->where("ServiceCode='".$this->inputdata['serviceCode']."'");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	  return  $record;
   }
   //=========================== Service Info ================================
   public function ServiceInfo(){
       $select = $this->_db->select()
								->from('forwarderdpdserviceinfo',array('ServicefFieldInfo'))
								->where("ServiceCode='".$this->inputdata['serviceCode']."'");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	  return  $record;
   }
   //============================= Validate country from routes table ==================================
    public function ValidCountryRoute(){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNTRY_COUNT'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNTRY_COUNT'];
   }
   //============================= Route Service Code==================================
    public function RouteServiceCode(){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNT_ROUTE'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'")
								->where("BeginPostCode='".$this->inputdata[SHIPMENT_ZIPCODE]."'")
								->where("ServiceCodes=''");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNT_ROUTE'];
   }
   //============================= Route Service Code Between Bigin,end Postcode==================================
    public function RouteServiceCodeBetweenPostcode(){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNT_ROUTE'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'")
								->where("BeginPostCode <= '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode >= '".$this->inputdata[SHIPMENT_ZIPCODE]."'")
								->where("ServiceCodes=''");
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNT_ROUTE'];
   }
   //============================= Route Coun With Post Code Query==================================
   public function RouteCountWithPostcodeQuery($postCodeQuery){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNT_ROUTE'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery)
								/*->where("BeginPostCode <= '".$this->inputdata[SHIPMENT_ZIPCODE]."' AND EndPostCode >= '".$this->inputdata[SHIPMENT_ZIPCODE]."'".$postCodeQuery)*/
								->where("ServiceCodes=''")
								->order("BeginPostCode DESC")
								->limit(1);
									//print_r($select->__toString());die;
	  	$record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNT_ROUTE'];
   }
  //============================= ISO Alpha Country Code==================================
   public function ISOAlphaCountryCode(){
       $select = $this->_db->select()
								->from('forwarderdpddepots',array('ISO_Alpha2CountryCode'))
								->where("GeoPostDepotNumber='".$this->inputdata[ROUTE_DEPOT_NO]."'");
									//print_r($select->__toString());die;
	   $record = $this->getAdapter()->fetchRow($select);
	 return  $record['ISO_Alpha2CountryCode'];
   }
   //============================= Routing Route Code Error With Routing Places==================================
   public function RouteRoutingDepotWithPlaces($postCodeQuery,$serviceCodeQuery,$rsRouteDepotsCountry){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNT_ROUTE'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery.$serviceCodeQuery)
								->where("RoutingPlaces LIKE '%".$rsRouteDepotsCountry."%'");
									//print_r($select->__toString());die;
	   $record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNT_ROUTE'];
   }
   //============================= Routing Route Code Error Without Routing Places==================================
   public function RouteRoutingDepotWithoutPlaces($postCodeQuery,$serviceCodeQuery){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('COUNT(1) as COUNT_ROUTE'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery.$serviceCodeQuery)
								->where("RoutingPlaces=''");
									//print_r($select->__toString());die;
	   $record = $this->getAdapter()->fetchRow($select);
	 return  $record['COUNT_ROUTE'];
   }
  //============================= Routing Route Code Error With Sending Date==================================
   public function RouteRoutingDepotWithSendinDate($postCodeQuery,$serviceCodeQuery,$routingDepotQuery,$sending_date){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('*'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery.$serviceCodeQuery.$routingDepotQuery)
								->where("SendingDate='".$sending_date."'")
								->order("BeginPostCode DESC")
								->limit(1);
									//print_r($select->__toString());die;
	   $record = $this->getAdapter()->fetchAll($select);
	 return  $record;
   }
   //============================= Routing Route Code Error With Sending Date==================================
   public function RouteRoutingDepotWithoutSendinDate($postCodeQuery,$serviceCodeQuery,$routingDepotQuery){
       $select = $this->_db->select()
								->from('forwarderdpdroutes',array('*'))
								->where("DestinationCountry='".$this->inputdata['CountryCode']."'".$postCodeQuery.$serviceCodeQuery.$routingDepotQuery)
								->where("SendingDate=''")
								->order("BeginPostCode DESC")
								->limit(1);
									//print_r($select->__toString());die;
	   $record = $this->getAdapter()->fetchAll($select);
	 return  $record;
   }
}

?>