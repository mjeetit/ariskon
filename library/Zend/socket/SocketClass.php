<?php
/*
 * CommonFunction of Logicparcel ERP System
 * @PHP version  : 5.1.37
 * @ZEND version : 1.11
 * @author       : SJM Softech Private Limited <contact@sjmsoftech.com>
 * @created on   : 09-Oct,2012
 * @link         : http://www.logicparcel.net
 * Description   : 
 */
ini_set("memory_limit", "-1");
class Zend_Socket_Class extends Zend_Custom_Class {
        public $SocketData = array();
	    public $SocketForwarder = NULL;
		public $unicode = NULL;
	   
	   public function SocketReturn(){
	        $this->SocketDataFormation();
		 if($this->SocketData['SOCKET']){
	        $socketResult =  $this->ConnectSocket();
		  }else{ 
		    $socketResult =  $this->RerouteBarcodeData();
		  }
		  return $socketResult;
	   }
	   public function SocketDataFormation(){
	      //Service Flag
			 if($this->getInternalCode($this->SocketData[SERVICE_ID])=='E'){
			    $Servicetag = 'EP'; 
			 }else{
			    $Servicetag = 'BP'; 
			 }
	   $this->unicode = "\\\\\\\\\\GLS\\\\\\\\\\".
			   'T8904:'.str_pad($this->SocketData['ShipmentCount'],3,'0',STR_PAD_LEFT).
			   "|".'T8905:'.str_pad($this->SocketData[SHIPMENT_QUANTITY],3,'0',STR_PAD_LEFT).
			   "|".'T100:'.$this->countryCode($this->SocketData[COUNTRY_ID]).
			   "|".'T330:'.substr($this->SocketData[SHIPMENT_ZIPCODE],0,10).
			   "|".'T853:'.substr($this->SocketData[REFERENCE],0,20).
			   "|".'T530:'.str_replace(".",",",substr($this->SocketData[SHIPMENT_WEIGHT],0,4)).
			   "|".'T800:'.substr('Afzender:',0,15).
			   "|".'T8914:'.substr($this->SocketForwarder[UNIQUE_CONTACT_ID],0,10).
			   "|".'T8915:'.substr($this->SocketForwarder[SAP_NUMBER],0,10).
			   "|".'T810:'.substr($this->SocketData['SOURCE_ADDRESS']['SenderAddress'][0],0,50).
			   "|".'T820:'.substr($this->SocketData['SOURCE_ADDRESS']['SenderAddress'][2],0,50).
			   "|".'T821:'.substr($this->SocketData['SOURCE_ADDRESS']['SenderAddress'][5],0,2).
			   "|".'T822:'.substr($this->SocketData['SOURCE_ADDRESS']['SenderAddress'][4],0,10).
			   "|".'T823:'.substr($this->SocketData['SOURCE_ADDRESS']['SenderAddress'][3],0,10).
			   "|".'T860:'.substr($this->SocketData[SHIPMENT_NAME],0,30).
			   "|".'T863:'.substr($this->SocketData[SHIPMENT_STREET].' '.$this->SocketData[SHIPMENT_STREETNR],0,30).
			   "|".'T864:'.substr($this->SocketData[SHIPMENT_CITY],0,30).
			   "|".'T882:'.substr($this->SocketData[SHIPMENT_STREETNR],0,8).
			   "|".'T805:'.substr($this->SocketData['glscustomer_no'],0,8).
			   "|".'T206:'.substr($Servicetag,0,2).
			   "|".'T620:'.substr($this->SocketData[SHIPMENT_BARCODE],0,14).
			   "|".'T854:'.substr($this->SocketData['OnetimeRef'],0,10).
			   "|".'T8700:'.substr('NL'.$this->SocketData['glsdepot_no'],0,6)."|"."/////GLS/////";;
			  
	 } 
	 
	   public function ConnectSocket(){
	       $socket = socket_create(AF_INET,SOCK_STREAM, SOL_TCP) or die("Could not create socket\n");
		   if($this->SocketForwarder[SECONDRY_SOCKET] != '' && $this->SocketForwarder[PORT_PRIMARY] != '') {
			$result = @socket_connect($socket, $this->SocketForwarder[SECONDRY_SOCKET], $this->SocketForwarder[PORT_SECONDRY]);
			}
			if($result != 1){
				$result = @socket_connect($socket, $this->SocketForwarder[STANDER_SOCKET],$this->SocketForwarder[PORT_PRIMARY]);
				if($result == ''){
					$_SESSION[ERROR_MSG] = 'Could not connect with the socket';
					//redirect_admin("shipment/admin_customer_add_shipment.php");
				}
			}
		   //$result = @socket_connect($socket, $this->SocketForwarder[STANDER_SOCKET], $this->SocketForwarder[PORT_PRIMARY]);
		   $write_data = socket_write($socket,$this->unicode, strlen($this->unicode));
		   $fullResult='';
		   while($resp = socket_read($socket, 5000)) {
			   $fullResult .= $resp;
			   if (strpos($fullResult, "/////GLS/////") !== false) break;
			}
			socket_close($socket);//print_r($fullResult);die;
			$reroute = strstr($fullResult,"\\\\\GLS\\\\");
			$fullResult = explode('|',$fullResult);
			$val = array();
			for($i=1;$i<count($fullResult);$i++)
			{
				if(strstr($fullResult[$i],':')) {
					$bre = explode(":",$fullResult[$i]);
					$val[$bre[0]] = $bre[1];
				}
				
			}
		  return array($reroute,$val);	
	} 
	public function Create2Dbarcode($primary,$secondry,$tacenr){
	    	//2D code
			$DataMatrix = new Zend_Barcode_2DBARCODE_DataMatrix();
			$FILEPATH = GLSNL_LABEL_LINK."img/";

			$CODE = NULL;
			$CODE1 = NULL;
			$CODE        = $primary;
			$findarr = array('¬','?');
			$replacearr = array("|","|");
			$CODE1 = str_replace($findarr,$replacearr,$secondry);
			
			$IMAGE_NAME_P = 'P_'.$tacenr.'.png';
			$IMAGE_NAME_S = 'S_'.$tacenr.'.png';
			
			
			
			$DataMatrix->setBGColor("WHITE");
			$DataMatrix->setBarColor("BLACK");
			$DataMatrix->setRotation("0");
			$DataMatrix->setImageType("PNG", 40 );
			$DataMatrix->setQuiteZone("10");
			$DataMatrix->setEncoding("AUTO");
			$DataMatrix->setFormat("36");
			$DataMatrix->setTilde("Y");
			$DataMatrix->setModuleSize("3");
	
			$DataMatrix->setFilePath($FILEPATH);
			$DataMatrix->paint($CODE,$IMAGE_NAME_P);
			$DataMatrix->paint($CODE1,$IMAGE_NAME_S);
			unset($DataMatrix);
			return array("Pri"=>$FILEPATH.$IMAGE_NAME_P,"Sec"=>$FILEPATH.$IMAGE_NAME_S);
	}
	public function RerouteBarcodeData(){
		$fullResult = explode('|',$this->SocketData['reroute_barcode']);
		$val = array();
		for($i=1;$i<count($fullResult);$i++)
		{
			if(strstr($fullResult[$i],':')) {
				$bre = explode(":",$fullResult[$i]);
				$val[$bre[0]] = $bre[1];
			}
		}
	  return array($reroute,$val); 
	}
	
}

?>