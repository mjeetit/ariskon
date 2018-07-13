<?php
class AppointmentManager extends Zend_Custom
{
	private $_leaveType 		= "leavetypes";
	private $_leavedistribution = "leavedistributions";
	private $_leaveapproval 	= "leaveapprovals";
	private $_designation 		= "designation";
	private $_userIDs	 		= array();
	private $_headquarters 		= array();
	
	public function countChild($loggedIn) {
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('CNT'=>'count(1)'))
				 ->where("EPT.parent_id =".$loggedIn." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
	}
	
	public function getAppointments($data) {
		try {
			$where = "1";
			$filterparam = '';
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44 && $_SESSION['AdminDesignation'] != 34){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND HQ.headquater_id IN(".$value.")";
		    }
			//Newely inserted code
			//Filter With Status Data
			if(!empty($data['token7'])){
				$where = '1';
				$decode=Class_Encryption::decode($data['token7']);
				if($decode==3)
					{$filterparam .=" AND AT.business_audit_status='0'";}
				else
					{$filterparam .=" AND AT.business_audit_status='".$decode."'";}
			}
			//End of newly inserted code
			
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND HQ.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND HQ.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND HQ.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND HQ.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DT.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND AT.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(AT.created_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'AT.created_date');
			//print_r($orderlimit);die;
			$countQuery = $this->_db->select()
							->from(array('AT'=>'crm_appointments'),array('COUNT(1) AS CNT'))
							->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",array())
							->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array())
							->joininner(array('ET'=>'crm_expense_types'),"ET.expense_type=AT.expense_type",array())
							->joinleft(array('DA'=>'app_activities'),"DA
							.activity_id=DT.activity_id",'')
							//---------------End of newly inserted code------------//
							->where($where.$filterparam)
							->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']);//print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			
			$limit = $orderlimit['Toshow'];
			$offset= $orderlimit['Offset'];
			if(isset($data['Export']) && $data['Export']=='ExportCRM') {
				$limit = '';
			}
			//echo $limit."<br/>";die;
			$query = $this->_db->select()
							 ->from(array('AT'=>'crm_appointments'),array('AT.appointment_id','AT.requested_by','AT.appointment_code','AT.expense_cost','AT.zbm_id','AT.abm_approval','AT.rbm_approval','AT.zbm_approval','AT.business_audit_status','AT.gm_audit_status','AT.created_date'))
							 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",array('doctorName'=>'DT.doctor_name'))
							 ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array('hqName'=>'HQ.headquater_name','drHQ'=>'HQ.headquater_id'))
							 ->joininner(array('ET'=>'crm_expense_types'),"ET.expense_type=AT.expense_type",array('expenseName'=>'ET.type_name'))
							
							->where($where.$filterparam)
							->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							->limit($limit,$offset);
							//->offset(100);
							//print_r($query->__toString());die;
			$result = $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
		
		return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
	}
	
	public function getAppointmentByID($data=array()) {
		$ajaxObj = new AjaxManager();
		$query = $this->_db->select()
				 ->from(array('AT'=>'crm_appointments'),array(
				 	'AT.appointment_id',
				 	'AT.appointment_code',
				 	'AT.doctor_id',
				 	'AT.expense_type',
				 	'AT.expense_cost',
				 	'AT.expense_note',
				 	'AT.zbm_id',
				 	'AT.total_value',
				 	'AT.chemist_1',
				 	'AT.chemist_2',
				 	'AT.favour',
				 	'AT.payble',
				 	'AT.abm_approval',
				 	'AT.abm_comment',
				 	'AT.abm_comment_date',
				 	'AT.abm_comment_by',
				 	'AT.abm_comment_ip',
				 	'AT.rbm_approval',
				 	'AT.rbm_comment',
				 	'AT.rbm_comment_date',
				 	'AT.rbm_comment_by',
				 	'AT.rbm_comment_ip',
				 	'AT.zbm_approval',
				 	'AT.zbm_comment',
				 	'AT.zbm_comment_date',
				 	'AT.zbm_comment_by',
				 	'AT.zbm_comment_ip',
				 	'AT.business_audit_status',
				 	'AT.business_audit_comment',
				 	'AT.business_audit_date',
				 	'AT.business_audit_by',
				 	'AT.business_audit_ip',
				 	'AT.gm_audit_status',
				 	'AT.gm_audit_comment',
				 	'AT.gm_audit_date',
				 	'AT.gm_audit_by',
				 	'AT.gm_audit_ip',
				 	'AT.remarks',
				 	'AT.isActive',
				 	'AT.created_by',
				 	'AT.created_date',
				 	'AT.created_ip',
				 	'AT.isModify',
				 	'AT.modify_by',
				 	'AT.modify_date',
				 	'AT.modify_ip',
				 	'AT.isDelete',
				 	'AT.deleted_by',
				 	'AT.deleted_date',
				 	'AT.deleted_ip',
				 	'if(AT.requested_by,AT.requested_by,(
															SELECT `EPT`.`user_id` FROM `employee_personaldetail` as `EPT`
															INNER JOIN `emp_locations` AS `ELD`
															ON `ELD`.`user_id`=`EPT`.`user_id`
															WHERE `ELD`.`headquater_id`=`DT`.`headquater_id` 
															AND (`EPT`.`designation_id`=8 OR `EPT`.`designation_id`=7) LIMIT 1
														)
				 	) as requested_by',
					'if(DT.address1,DT.address1,DT.address2) as doctorAddress'))
				 ->joininner(array('DT'=>'crm_doctors'),"DT.doctor_id=AT.doctor_id",array('doctorName'=>'DT.doctor_name','drHQ'=>'DT.headquater_id'))
				 ->joininner(array('PC'=>'patchcodes'),"PC.patch_id=DT.patch_id",array('Patch'=>'PC.patch_name','PC.patch_id'))
				 ->joininner(array('CT'=>'city'),"CT.city_id=DT.city_id",array('City'=>'CT.city_name','CT.city_id'))
				 ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=DT.headquater_id",array('hqName'=>'HQ.headquater_name'))
				 ->joininner(array('ET'=>'crm_expense_types'),"ET.expense_type=AT.expense_type",array('expenseName'=>'ET.type_name'))
				 
				 ->joinleft(array('CT1'=>'crm_chemists'),"CT1.chemist_id=AT.chemist_1",array('legacy1'=>'CT1.legacy_code','cname1'=>'CT1.chemist_name','cphone1'=>'CT1.phone'))
				 ->joinleft(array('CT2'=>'crm_chemists'),"CT2.chemist_id=AT.chemist_2",array('legacy2'=>'CT2.legacy_code','cname2'=>'CT2.chemist_name','cphone2'=>'CT2.phone'))
				 ->where('AT.appointment_id='.$data['appoinToken']); 
				 //echo $query->__toString();die;
		
		$appointData = $this->getAdapter()->fetchRow($query);
		
		$potential = $this->_db->select()->from(array('APT'=>'crm_appointment_potential_months'),array('APT.potential_month_id','APT.month','APT.month_total_value'))->where('APT.appointment_id='.$data['appoinToken'])->order('APT.month','ASC');
		$potentials = $this->getAdapter()->fetchAll($potential);
		$potentialData = array();
		$crmproducts   = array();
		foreach($potentials as $potential) {
			$monthProduct = $this->_db->select()->from(array('MP'=>'crm_appointment_potential_month_products'),array('MP.product_id','MP.unit','MP.value'))->where('MP.potential_month_id='.$potential['potential_month_id']);
			$monthProducts = $this->getAdapter()->fetchAll($monthProduct);
			foreach($monthProducts as $monthProduct) {
				$crmproducts[] = $monthProduct['product_id'];;
				$potentialData[$potential['month']][$monthProduct['product_id']]['unit'] = $monthProduct['unit'];
				$potentialData[$potential['month']][$monthProduct['product_id']]['value'] = $monthProduct['value'];
			}
		}
		
		$editpotential = $this->_db->select()->from(array('APT'=>'crm_appointment_potential_months_by_admin'),array('CNT'=>'COUNT(1)'))->where('APT.appointment_id='.$data['appoinToken']);
		$editpotentials = $this->getAdapter()->fetchRow($editpotential); //echo $editpotential->__toString();die;
		
		$potentialEditData = array();
		$crmproductEdits   = array();
		if($editpotentials['CNT']>0) {
			$potentialedit = $this->_db->select()->from(array('APT'=>'crm_appointment_potential_months_by_admin'),array('APT.potential_month_id','APT.month','APT.month_total_value'))->where('APT.appointment_id='.$data['appoinToken'])->order('APT.month','ASC');
			$potentialedits = $this->getAdapter()->fetchAll($potentialedit);
			foreach($potentialedits as $potential_edit) {
				$monthProductedit = $this->_db->select()->from(array('MP'=>'crm_appointment_potential_month_products_by_admin'),array('MP.product_id','MP.unit','MP.value'))->where('MP.potential_month_id='.$potential_edit['potential_month_id']);
				$monthProductedits = $this->getAdapter()->fetchAll($monthProductedit);
				foreach($monthProductedits as $monthProduct_edit) {
					$crmproductEdits[] = $monthProduct_edit['product_id'];;
					$potentialEditData[$potential_edit['month']][$monthProduct_edit['product_id']]['unit'] = $monthProduct_edit['unit'];
					$potentialEditData[$potential_edit['month']][$monthProduct_edit['product_id']]['value'] = $monthProduct_edit['value'];
				}
			}
		}
		
		$transaction = $this->_db->select()->from(array('AT'=>'crm_appointment_transactions'),array('AT.*'))->where('AT.appointment_id='.$data['appoinToken'])->order('AT.added_date','DESC');
		$transactions = $this->getAdapter()->fetchAll($transaction);		
				 
		return array('appointData'=>$appointData,'potentails'=>$potentials,'potentialData'=>$potentialData,'editpotential'=>$editpotentials,'potentailEdits'=>$potentialedits,'potentialEditData'=>$potentialEditData,'doctorInfo'=>$ajaxObj->getdoctorinfo(array('doctorID'=>$appointData['doctor_id'])),'transactions'=>$transactions,'crmproducts'=>$crmproducts);
	}
	
	public function getExpenseLists($data=array()) {
		$query = $this->_db->select()->from('crm_expense_types','*')->where("isActive='1'")->order('type_name','ASC'); //echo $query->__toString();die;
		return $this->getAdapter()->fetchAll($query);
	}
	
	public function makeAppointmentCode($data=array()) {
		$query = $this->_db->select()->from('crm_appointments','appointment_code')->order('appointment_id DESC')->limit(1); //echo $query->__toString();die;
		$lastCode = $this->getAdapter()->fetchRow($query);
		return (!empty($lastCode)) ? 'APT'.(int) (substr($lastCode['appointment_code'],3)+1) : 'APT100001';
	}
	
	public function addAppointmentData($data=array()){
		$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$tableData = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
		
		if(!empty($tableName) && count($tableData)>0) {
			return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
		}
		else {
			return 0;
		}
	}
	
	public function updateAppointmentData($data=array()){
		$tableName   = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
		$tableData   = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
		$whereColumn = (isset($data['whereColumn']) && !empty($data['whereColumn'])) ? trim($data['whereColumn']) : '';
		
		if(!empty($tableName) && count($tableData)>0 && !empty($whereColumn)) {
			return ($this->_db->update($tableName,array_filter($tableData),$whereColumn)) ? TRUE : FALSE;
		}
		else {
			return FALSE;
		}
	}
	
	/**
	 * Export All Doctor Visit Frequency Data
	 * Function : ExportDoctorVisitFrequency()
	 * This function return doctor visit data sheet.
	 **/
	public function ExportCrm($crmDetail=array(),$products=array())
	{
		try{
		
			//print_r($crmDetail);die;
			if(count($crmDetail)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Set Cell Border
				$styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
				$objPHPExcel->getActiveSheet()->getStyle('A1:S25')->applyFromArray($styleArray);
				unset($styleArray);
				
				// Write Sheet				
				$objPHPExcel->getActiveSheet()->setCellValue('A1','Appintment ID');
				$objPHPExcel->getActiveSheet()->setCellValue('B1',$crmDetail['appointData']['appointment_code']);
				
				$dataArray = array(
								0=>array('Doctor Name',$crmDetail['appointData']['doctorName'],'Address',$crmDetail['appointData']['Patch'].", ".$crmDetail['appointData']['City']),
								1=>array('Qualification',$crmDetail['doctorInfo']['qualification']),
								2=>array('Speciality',$crmDetail['doctorInfo']['speciality']),
								3=>array('SVL No /Code No',$crmDetail['doctorInfo']['svl_number']),
								5=>array('Tel No (with STD)',$crmDetail['doctorInfo']['phone'],'Mobile No',$crmDetail['doctorInfo']['mobile']),
								6=>array('BE',$crmDetail['appointData']['beName'],'HQ',$crmDetail['appointData']['beHQName'],'Emp. Code',$crmDetail['appointData']['beCode']),
								7=>array('ABM',$crmDetail['appointData']['abmName'],'HQ',$crmDetail['appointData']['abmHQName'],'Emp. Code',$crmDetail['appointData']['abmCode']),
								8=>array('RBM',$crmDetail['appointData']['rsmName'],'HQ',$crmDetail['appointData']['rsmHQName'],'Emp. Code',$crmDetail['appointData']['rsmCode']),
								9=>array('Nature of Request',$crmDetail['appointData']['expenseName'],'COST of Activity',$crmDetail['appointData']['expense_cost']),
								10=>array('Detail of Activity Planned',$crmDetail['appointData']['expense_note']),
								11=>array('Potential of Dr for JCL Products')
							 );
				
				$objPHPExcel->getActiveSheet()->fromArray($dataArray, NULL, 'A2');
				$objPHPExcel->getActiveSheet()->getStyle('A1:A200')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->getStyle('C1:C200')->getFont()->setBold(true);
				$objPHPExcel->getActiveSheet()->mergeCells('B11:D11');
				
				$objPHPExcel->getActiveSheet()->setCellValue('B13','Current 3 Months');
				$objPHPExcel->getActiveSheet()->mergeCells('B13:G13');
				$objPHPExcel->getActiveSheet()->getStyle('B13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->setCellValue('H13','Expected 6 Months');
				$objPHPExcel->getActiveSheet()->mergeCells('H13:S13');
				
				$objPHPExcel->getActiveSheet()->setCellValue('A14','Products');
				$objPHPExcel->getActiveSheet()->mergeCells('A14:A15');
				$objPHPExcel->getActiveSheet()->getStyle('A14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$objPHPExcel->getActiveSheet()->getStyle('A14')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
				
				$monthCell = array(0=>'B14',1=>'D14',2=>'F14',3=>'H14',4=>'J14',5=>'L14',6=>'N14',7=>'P14',8=>'R14',9=>'T14',10=>'V14',11=>'X14',12=>'Z14',13=>'AB14',14=>'AD14',15=>'AF15');
				$mergeMonth = array(0=>'B14:C14',1=>'D14:E14',2=>'F14:G14',3=>'H14:I14',4=>'J14:K14',5=>'L14:M14',6=>'N14:O14',7=>'P14:Q14',8=>'R14:S14',9=>'T14:U14',10=>'V14:W14',11=>'X14:Y14',12=>'Z14:AA14',13=>'AB14:AC14',14=>'AD14:AE14',15=>'AF14:AG14');
				foreach($crmDetail['potentails'] as $key=>$potential) {
					/*if($key>=9){
						continue;
					}*/
					$objPHPExcel->getActiveSheet()->setCellValue($monthCell[$key],date("M Y",strtotime($potential['month'])));
					$objPHPExcel->getActiveSheet()->mergeCells($mergeMonth[$key]);
					$objPHPExcel->getActiveSheet()->getStyle($monthCell[$key])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				}
				$unitValue = array(0=>'Unit',1=>'Value',2=>'Unit',3=>'Value',4=>'Unit',5=>'Value',6=>'Unit',7=>'Value',8=>'Unit',9=>'Value',10=>'Unit',11=>'Value',12=>'Unit',13=>'Value',14=>'Unit',15=>'Value',16=>'Unit',17=>'Value');
				$objPHPExcel->getActiveSheet()->fromArray($unitValue, NULL, 'B15');
				
				$objPHPExcel->getActiveSheet()->getStyle('A13:Z15')->getFont()->setBold(true);
				
				//Product, Unit and Value
				$potArr = array();
				foreach($products as $i=>$product) {
					if(in_array($product['product_id'],$crmDetail['crmproducts'])) {
			  			$str = $product['product_name'].',';
						foreach($crmDetail['potentails'] as $potential) {
							if (@array_key_exists($product['product_id'], $crmDetail['potentialData'][$potential['month']])) { 
								$str .= number_format($crmDetail['potentialData'][$potential['month']][$product['product_id']]['unit'],0).','.$crmDetail['potentialData'][$potential['month']][$product['product_id']]['value'].',';
							} else { 
								$str .= '--,--,'; 
							}
						}
						$potArr[] = explode(',',substr($str,0,-1));//print_r($potArr);die;
					}
				}
				$objPHPExcel->getActiveSheet()->fromArray($potArr, NULL, 'A16');
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle($crmDetail['appointData']['appointment_code']);
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
				
				// Redirect output to a client’s web browser (Excel5)
				header("Content-type: application/xls");
				header("Content-Disposition: attachment; filename=crm_".$crmDetail['appointData']['appointment_code'].".xlsx");
				header("Pragma: no-cache");
				header("Expires: 0");
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
	public function ExportAll($data=array())
	{
		try{
			$allData = $this->getAppointments($data);
			$totalRowData = $allData['Records']; 
			//	echo "<pre>";print_r($totalRowData);die;
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();

				// Write Sheet Header
				$headers = array('0'=>'Date','1'=>'CRM No','2'=>'Dr. Name','3'=>'Headquarter','4'=>'Requested By','5'=>'Expense Type','6'=>'Expense Cost','7'=>'ABM Status','8'=>'RBM Status','9'=>'ZBM Status','10'=>'HO Status','11'=>'GM Status');
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

				$visitWith = '';

				$approvalArray = array(0=>'Pending',1=>'Approved',2=>'Rejected');

				foreach($totalRowData as $index=>$row)

				{

					$abmStatus = ($row['abm_id']>0) ? $approvalArray[$row['abm_approval']] : '--NR--';

					$rbmStatus = ($row['rbm_id']>0) ? $approvalArray[$row['rbm_approval']] : '--NR--';

					$zbmStatus = ($row['zbm_id']>0) ? $approvalArray[$row['zbm_approval']] : '--NR--';

					$hoStatus  = $approvalArray[$row['business_audit_status']];

					$GMStatus  = $approvalArray[$row['gm_audit_status']];

					$reportRows[] = array(date('Y-m-d',strtotime($row['created_date'])),$row['appointment_code'],$row['doctorName'],$row['hqName'],$row['first_name']." ".$row['last_name'],$row['expenseName'],$row['expense_cost'],$abmStatus,$rbmStatus,$zbmStatus,$hoStatus,$GMStatus);

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

				$objPHPExcel->getActiveSheet()->setTitle('CRM Report');

				

				// Set active sheet index to the first sheet, so Excel opens this as the first sheet

				$objPHPExcel->setActiveSheetIndex(0);

									

				// Redirect output to a client’s web browser (Excel5)

				header('Content-Type: application/vnd.ms-excel');

				header('Content-Disposition: attachment;filename="crm_report.xls"');

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
   		}
   		elseif($_SESSION['AdminDesignation']==6){
    		$where .= " AND EL.region_id='".$result['region_id']."'";
  		}
   		elseif($_SESSION['AdminDesignation']==5 || $_SESSION['AdminDesignation']==40){
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
  	public function getUserHeadquarter($userID)
	{
		$query = $this->_db->select()
				 ->from(array('EPT'=>'employee_personaldetail'),array('EPT.user_id'))
				 ->joininner(array('ELT'=>'emp_locations'),"ELT.user_id=EPT.user_id",array('ELT.headquater_id'))
				 ->where("EPT.user_id =".$userID." AND EPT.designation_id<=8"); //echo $query->__toString();die;
		return $this->getAdapter()->fetchRow($query);
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
					$this->getHeadQuaters($child['user_id']);
				}
				else {
					$this->_userIDs[] 	   = $child['user_id'];
					$this->_headquarters[] = $child['headquater_id'];
				}
			}
		}
		$this->_headquarters = array_filter($this->_headquarters);
	}

	function getAllHiearchy($requested_by=NULL,$headquater=NULL){
		//echo $requested_by."<br/>".$headquater;die;
		if($headquater==""){
			$data['beCode']		='N/A';
			$data['beName']		='N/A';
			$data['be_id']		='N/A';
			$data['beHQ']		='N/A';
			$data['beHQName']	='N/A';

			$data['abmCode']	='N/A';
			$data['abmName']	='N/A';
			$data['abm_id']		='N/A';
			$data['abmHQ']		='N/A';
			$data['abmHQName']	='N/A';

			$data['rsmCode']	='N/A';
			$data['rsmName']	='N/A';
			$data['rbm_id']		='N/A';
			$data['rsmHQ']		='N/A';
			$data['rsmHQName']	='N/A';

			return $data;exit;
		}
		$data=array();
		$query=$this->_db->select()
				->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
				->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
				->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
				->where('HT.headquater_id='.$headquater)
				->where("EP.delete_status='0'")
				->where("EP.user_id!='2'")
				->where('EP.designation_id=8');//print_r($query->__toString());die;
		
				//->where('EP.user_id='.$requested_by);
		$results = $this->getAdapter()->fetchRow($query);
		if(!empty($results)){
			
			$data['beCode']		=$results['employee_code'];
			$data['beName']		=$results['first_name']." ".$results['last_name'];
			$data['be_id']		=$results['user_id'];
			$data['beHQ']		=$results['EPHQID'];
			$data['beHQName']	=$results['EPHQNM'];

			$query1=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where("EP.delete_status='0'")
						->where("EP.user_id!='139'")
						->where('EP.designation_id=7')
						->where('EP.user_id='.$results['parent_id']);
			$results1 = $this->getAdapter()->fetchRow($query1);

			$query2=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where('HT.headquater_id='.$headquater)
						->where("EP.delete_status='0'")
						->where("EP.user_id!='139'")
						->where('EP.designation_id=7');
			$resultsTemp = $this->getAdapter()->fetchRow($query2);
			if(!empty($results1)){
				$results1=$results1;
			}elseif (!empty($resultsTemp)) {
				$results1=$resultsTemp;
			}else{
				$results1=array();
			}

			if(!empty($results1)){
				
				$data['abmCode']	=$results1['employee_code'];
				$data['abmName']	=$results1['first_name']." ".$results1['last_name'];
				$data['abm_id']		=$results1['user_id'];
				$data['abmHQ']		=$results1['EPHQID'];
				$data['abmHQName']	=$results1['EPHQNM'];

				$query=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where("EP.delete_status='0'")
						->where("EP.user_id!='182'")
						->where('EP.designation_id=6')
						->where('EP.user_id='.$results1['parent_id']);
				$results2 = $this->getAdapter()->fetchRow($query);
				
				$query=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where('HT.headquater_id='.$headquater)
						->where("EP.delete_status='0'")
						->where("EP.user_id!='182'")
						->where('EP.designation_id=6');
				$resultsTemp = $this->getAdapter()->fetchRow($query);
				if(!empty($results2)){
					$results2=$results2;
				}elseif (!empty($resultsTemp)) {
					$results2=$resultsTemp;
				}else{
					$results2=array();
				}
				
				if(!empty($results2)){

					$data['rsmCode']	=$results2['employee_code'];
					$data['rsmName']	=$results2['first_name']." ".$results2['last_name'];
					$data['rbm_id']		=$results2['user_id'];
					$data['rsmHQ']		=$results2['EPHQID'];
					$data['rsmHQName']	=$results2['EPHQNM'];
				}else{

					$data['rsmCode']	='N/A';
					$data['rsmName']	='N/A';
					$data['rbm_id']		='N/A';
					$data['rsmHQ']		='N/A';
					$data['rsmHQName']	='N/A';
				}
			}else{
				
				$data['abmCode']	='N/A';
				$data['abmName']	='N/A';
				$data['abm_id']		='N/A';
				$data['abmHQ']		='N/A';
				$data['abmHQName']	='N/A';
				$query=$this->_db->select()
								->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
								->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
								->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
								->where('HT.headquater_id='.$headquater)
								->where("EP.delete_status='0'")
								->where("EP.user_id!='182'")
								->where('EP.designation_id=6');
				$resultsTemp = $this->getAdapter()->fetchRow($query);
				if(!empty($resultsTemp)){
					$data['rsmCode']	=$results1['employee_code'];
					$data['rsmName']	=$results1['first_name']." ".$results1['last_name'];
					$data['rbm_id']		=$results1['user_id'];
					$data['rsmHQ']		=$results1['EPHQID'];
					$data['rsmHQName']	=$results1['EPHQNM'];
				}else{
					$data['rsmCode']	='N/A';
					$data['rsmName']	='N/A';
					$data['rbm_id']		='N/A';
					$data['rsmHQ']		='N/A';
					$data['rsmHQName']	='N/A';
				}
			}
		}else if(empty($data)){
			$data['beCode']		='N/A';
			$data['beName']		='N/A';
			$data['be_id']		='N/A';
			$data['beHQ']		='N/A';
			$data['beHQName']	='N/A';
			$query=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where('HT.headquater_id='.$headquater)
						->where("EP.delete_status='0'")
						->where("EP.user_id!='139'")
						->where('EP.designation_id=7');
			$resultsTemp = $this->getAdapter()->fetchRow($query);
			if(!empty($resultsTemp)){
				
				$data['abmCode']	=$resultsTemp['employee_code'];
				$data['abmName']	=$resultsTemp['first_name']." ".$resultsTemp['last_name'];
				$data['abm_id']		=$resultsTemp['user_id'];
				$data['abmHQ']		=$resultsTemp['EPHQID'];
				$data['abmHQName']	=$resultsTemp['EPHQNM'];

				$query=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where("EP.delete_status='0'")
						->where("EP.user_id!='182'")
						->where('EP.designation_id=6')
						->where('EP.user_id='.$resultsTemp['parent_id']);
				$results2 = $this->getAdapter()->fetchRow($query);
				
				$query=$this->_db->select()
						->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
						->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
						->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
						->where('HT.headquater_id='.$headquater)
						->where("EP.delete_status='0'")
						->where("EP.user_id!='182'")
						->where('EP.designation_id=6');
				$resultsTemp = $this->getAdapter()->fetchRow($query);
				if(!empty($results2)){
					$results2=$results2;
				}elseif (!empty($resultsTemp)) {
					$results2=$resultsTemp;
				}else{
					$results2=array();
				}
				
				if(!empty($results2)){

					$data['rsmCode']	=$results2['employee_code'];
					$data['rsmName']	=$results2['first_name']." ".$results2['last_name'];
					$data['rbm_id']		=$results2['user_id'];
					$data['rsmHQ']		=$results2['EPHQID'];
					$data['rsmHQName']	=$results2['EPHQNM'];
				}else{

					$data['rsmCode']	='N/A';
					$data['rsmName']	='N/A';
					$data['rbm_id']		='N/A';
					$data['rsmHQ']		='N/A';
					$data['rsmHQName']	='N/A';
				}
			}else{
				
				$data['abmCode']	='N/A';
				$data['abmName']	='N/A';
				$data['abm_id']		='N/A';
				$data['abmHQ']		='N/A';
				$data['abmHQName']	='N/A';
				$query=$this->_db->select()
								->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
								->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
								->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
								->where('HT.headquater_id='.$headquater)
								->where("EP.delete_status='0'")
								->where("EP.user_id!='182'")
								->where('EP.designation_id=6');
				$resultsTemp = $this->getAdapter()->fetchRow($query);
				if(!empty($resultsTemp)){
					$data['rsmCode']	=$results1['employee_code'];
					$data['rsmName']	=$results1['first_name']." ".$results1['last_name'];
					$data['rbm_id']		=$results1['user_id'];
					$data['rsmHQ']		=$results1['EPHQID'];
					$data['rsmHQName']	=$results1['EPHQNM'];
				}else{
					$data['rsmCode']	='N/A';
					$data['rsmName']	='N/A';
					$data['rbm_id']		='N/A';
					$data['rsmHQ']		='N/A';
					$data['rsmHQName']	='N/A';
				}
			}
		}else if(empty($data)){
			$data['beCode']		='N/A';
			$data['beName']		='N/A';
			$data['be_id']		='N/A';
			$data['beHQ']		='N/A';
			$data['beHQName']	='N/A';

			$data['abmCode']	='N/A';
			$data['abmName']	='N/A';
			$data['abm_id']		='N/A';
			$data['abmHQ']		='N/A';
			$data['abmHQName']	='N/A';

			$query=$this->_db->select()
								->from(array('EP'=>'employee_personaldetail'),array('EP.parent_id','EP.designation_id','EP.employee_code','EP.first_name','EP.last_name','EP.user_id'))
								->joinleft(array('EPL'=>'emp_locations'),"EPL.user_id=EP.user_id",array('EPHQID'=>'EPL.headquater_id'))
								->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EPL.headquater_id",array('EPHQNM'=>'HT.headquater_name'))
								->where('HT.headquater_id='.$headquater)
								->where("EP.delete_status='0'")
								->where("EP.user_id!='182'")
								->where('EP.designation_id=6');
			$resultsTemp = $this->getAdapter()->fetchRow($query);
			if(!empty($resultsTemp)){
				$data['rsmCode']	=$results1['employee_code'];
				$data['rsmName']	=$results1['first_name']." ".$results1['last_name'];
				$data['rbm_id']		=$results1['user_id'];
				$data['rsmHQ']		=$results1['EPHQID'];
				$data['rsmHQName']	=$results1['EPHQNM'];
			}else{
				$data['rsmCode']	='N/A';
				$data['rsmName']	='N/A';
				$data['rbm_id']		='N/A';
				$data['rsmHQ']		='N/A';
				$data['rsmHQName']	='N/A';
			}
		}else{
			$data['beCode']		='N/A';
			$data['beName']		='N/A';
			$data['be_id']		='N/A';
			$data['beHQ']		='N/A';
			$data['beHQName']	='N/A';

			$data['abmCode']	='N/A';
			$data['abmName']	='N/A';
			$data['abm_id']		='N/A';
			$data['abmHQ']		='N/A';
			$data['abmHQName']	='N/A';

			$data['rsmCode']	='N/A';
			$data['rsmName']	='N/A';
			$data['rbm_id']		='N/A';
			$data['rsmHQ']		='N/A';
			$data['rsmHQName']	='N/A';
		}
		
		return $data;
	}
}
?>