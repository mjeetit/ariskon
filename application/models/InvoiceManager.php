<?php

class InvoiceManager extends Zend_Custom

{

  public $_getData  = array();	

  public function getInvoiceList(){

    if(!empty($this->_getData['invoice_number'])){

	   $where = "invoice_number='".$this->_getData['invoice_number']."'";

	}else{

	   $where = "1";

	}

	 $select = $this->_db->select()

						->from('invoices',array('*'))

						->where($where);

	  $result = $this->getAdapter()->fetchAll($select);

	 return $result; 

  }
  public function excelread(){
	    //set_error_handler('error_handler_for_export',E_ALL);
		//register_shutdown_function('fatal_error_shutdown_handler_for_export');
		
		ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		//set_time_limit( 60 );require_once "Classes/PHPExcel/IOFactory.php";
		//chdir( Bootstrap::$root.'/library/Excel/PHPExcel' );
		//require_once( Bootstrap::$root.'/library/Excel/PHPExcel.php' );
		//require_once( Bootstrap::$root.'/library/Excel/PHPExcel/IOFactory.php' );
		//chdir( '../../model' );
		
		$filename 	= CommonFunction::UploadFile('ImportShipment');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		for ($i=1; $i<$k; $i+=1) {
			$j = 1;
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			$atData = array();
		    $atData['EmpCode']     = $this->getCell($data,$i,$j++);
			$atData['Presence']     = $this->getCell($data,$i,$j++);
			$atData['SL']     = $this->getCell($data,$i,$j++);
			$atData['CL']     = $this->getCell($data,$i,$j++);
			$totalArr[] = $atData;
		}
		//$product_id= $this->getCell($data,2,2);
		echo '<pre>';print_r($totalArr);echo '<pre>';die;
	}
  function getCell123(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}

   public function uploadedata(){
        ini_set("memory_limit","512M");
		ini_set("max_execution_time",180);
		$filename 	= CommonFunction::UploadFile('AttandanceSheet','xls');
		$inputFileType = PHPExcel_IOFactory::identify($filename);
		$objReader = PHPExcel_IOFactory::createReader($inputFileType);
		$objReader->setReadDataOnly(true);
		$reader = $objReader->load($filename);
		$data = $reader->getSheet(0);
		$k = $data->getHighestRow();
		for ($i=1; $i<$k; $i+=1) {
			$j = 1;
			if ($isFirstRow) {
				$isFirstRow = FALSE;
				continue;
			}
			unset($this->_getData);
			$rowbarcode   = '';
		    $rowbarcode 		 = trim($this->getCell($data,$i,$j++));
			$barcode = str_replace("'","",$rowbarcode);
			//print_r($barcode);die;
			$this->_db->query("INSERT INTO barcode SET barcode='". $barcode."'");
           }
    }
  
   public function getCell(&$worksheet,$row,$col,$default_val='') {
		$col -= 1; // we use 1-based, PHPExcel uses 0-based column index
		$row += 1; // we use 0-based, PHPExcel used 1-based row index
		return ($worksheet->cellExistsByColumnAndRow($col,$row)) ? $worksheet->getCellByColumnAndRow($col,$row)->getValue() : $default_val;
	}
	
	 public function chnagetime($time){
      $hour = explode(':',$time);
	  $searchArr = array('00','01','02','03','04','05','06','07','08','09','10','11');
	  $replaceArr = array('12','13','14','15','16','17','18','19','20','21','22','23');
		 if(trim($hour[0])=='00' && trim($hour[1])=='00'){
			 return $time;
		  }else{
			 return  str_replace($searchArr,$replaceArr,$hour[0]).':'.$hour[1].':00'; 
		  }
   }
}

?>