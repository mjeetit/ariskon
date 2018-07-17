<?php

class Users extends Zend_Custom

{
	 public $_getData = array();
  	 public function getDepartmentByBunitId(){

	    $select = $this->_db->select()

		 				->from(array('D2B'=>'department_to_bunit'),array('*'))

		 				->joininner(array('BU'=>'bussiness_unit'),"BU.bunit_id=D2B.bunit_id",array('bunit_name'))

						->joininner(array('DT'=>'department'),"DT.department_id=D2B.department_id",array('department_name'))

						->where("D2B.bunit_id='".$this->_getData['bunit_id']."'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}
	public function getEmpListOnly($arr=array()){
		$where="";
		if(isset($arr['designation_id']) && $arr['designation_id']!=''){
			$where.=" AND UT.designation_id=".$arr['designation_id']."";			
		}
		if(isset($arr['parent_id']) && $arr['parent_id']!=''){
			$where.=" AND UT.parent_id=".$arr['parent_id']."";
		}
		if(isset($arr['user_id']) && $arr['user_id']!=''){
			$where.=" AND UT.user_id=".$arr['user_id']."";
		}
		$select=$this->_db->select()
					->from(array('UT'=>'employee_personaldetail'),array('user_id','first_name'))
					->where("`UT`.`delete_status`='0'".$where);
		return $this->getAdapter()->fetchAll($select);
	}
	public function getEmpDetail($arr=array()){
		//It Will Select All BE of the respective ABM
		$where="";
		if(isset($arr['designation_id']) && $arr['designation_id']!=''){
			$where.=" AND UT.designation_id=".$arr['designation_id']."";			
		}
		if(isset($arr['parent_id']) && $arr['parent_id']!=''){
			$where.=" AND UT.parent_id=".$arr['parent_id']."";
		}
		if(isset($arr['user_id']) && $arr['user_id']!=''){
			$where.=" AND UT.user_id=".$arr['user_id']."";
		}

		$select = $this->_db->select()
                ->from(array('UT'=>'employee_personaldetail'),array('employee_code as Emp_Code','CONCAT(UT.first_name," ",UT.last_name) as Emp_Name','email','contact_number','doj','dob'))
				
                ->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name as Designation'))
				
                ->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array())
				
                ->joininner(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array())
				
				->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('HQ.headquater_name'))
				
				->joinleft(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('RT.region_name'))

				->joinleft(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('ZT.zone_name'))
				
				->joininner(array('US'=>'users'),"US.user_id=UT.user_id",array())
                ->where("`UT`.`delete_status`='0'".$where);
	echo $select->__toString();die;
		if(isset($arr['QueryCheck']) && $arr['QueryCheck']==1){
			echo $select->__toString();die;
		}
		return $this->getAdapter()->fetchAll($select);
	}
	public function ExportOrganographReport(){
		$result=array();
		$blankPerson=array();
		//It will select all ZBM
		$zbm =  $this->getEmpListOnly(array('designation_id'=>'5'));

		foreach($zbm as $z){
			
			//It will Select All RBM which are reporting to ZBM 
			$rbm =  $this->getEmpListOnly(array('designation_id'=>'6','parent_id'=>$z['user_id']));
			
			if(!empty($rbm)){
				
				foreach($rbm as $r){
					
					//It will Skip the Test RBM		
					if(trim($r['first_name'])=="Test"){
						echo $r['first_name'];
						continue;
					}

					//It will Select all the ABM inside the respective RBM
					$abm =  $this->getEmpListOnly(array('designation_id'=>'7','parent_id'=>$r['user_id']));
					
					if(!empty($abm)){
						
						foreach($abm as $a){
							//It Will Select All BE of the respective ABM
							$resultTemp=$this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$a['user_id']));
							$result=array_merge($result,$resultTemp);

							
							//It will Select the respective ABM details
							$resultTemp =  $this->getEmpDetail(array('designation_id'=>'7','user_id'=>$a['user_id']));
							$result=array_merge($result,$resultTemp);
						}
					} 	
					//It will Select all the BE which are directly reporting to respective RBM
					$resultTemp =  $this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$r['user_id']));
					$result=array_merge($result,$resultTemp);
				
					//It will Select all the RBM Data
					$resultTemp =  $this->getEmpDetail(array('designation_id'=>'6','user_id'=>$r['user_id']));
					$result=array_merge($result,$resultTemp);
				}
				
			}else{
				
				//It will Select all the ABM which are directly reporting to  ZBM
				$abm =  $this->getEmpListOnly(array('designation_id'=>'7','parent_id'=>$z['user_id']));
					
				if(!empty($abm)){
						
					foreach($abm as $a){
						
						//It Will Select All BE of the respective ABM
						$resultTemp=$this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$a['user_id']));
						$result=array_merge($result,$resultTemp);

							
						//It will Select the respective ABM details
						$resultTemp =  $this->getEmpDetail(array('designation_id'=>'7','user_id'=>$a['user_id']));
						$result=array_merge($result,$resultTemp);
					}
				}
			}
			//It Will Select All BE who are directly reporting to zbm
			$resultTemp=$this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$z['user_id']));
			$result=array_merge($result,$resultTemp);
				
			if( (!empty($rbm)) || (!empty($abm)) || (!empty($resultTemp)) ){
				//It will Select all the data of Respective ZBM 
				$resultTemp =  $this->getEmpDetail(array('designation_id'=>'5','user_id'=>$z['user_id']));
				$result=array_merge($result,$resultTemp);
			}else{
				$blankPerson[]=$z['user_id'];
			}	
		}

		##It will select all the RBM who are not reporting to ZBM or have no one as reporting person 
		$select=$this->_db->select()
						->from(array('UT'=>'employee_personaldetail'),array('user_id'))
						->joinleft(array('RP'=>'employee_personaldetail'),"RP.user_id=UT.Parent_id",array())
						->where("`UT`.`delete_status`='0' AND UT.designation_id=6 AND (RP.designation_id!=5 OR UT.Parent_id=0)");
						//echo $select->__toString();die;
		$rbm =  $this->getAdapter()->fetchAll($select);
		if(!empty($rbm)){
			
			foreach($rbm as $r){
				
				//It will Skip the Test RBM		
				if(trim($r['first_name'])=="Test"){
					continue;
				}

				//It will Select all the ABM who are reporting to the current RBM
				$abm =  $this->getEmpListOnly(array('designation_id'=>'7','parent_id'=>$r['user_id']));
				
				if(!empty($abm)){
					
					foreach($abm as $a){
						//It Will Select All BE of the respective ABM
						$resultTemp=$this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$a['user_id']));
						$result=array_merge($result,$resultTemp);

						
						//It will Select the respective ABM details
						$resultTemp =  $this->getEmpDetail(array('designation_id'=>'7','user_id'=>$a['user_id']));
						$result=array_merge($result,$resultTemp);
					}
				}
				//It will Select all the BE which are directly reporting to respective RBM
				$resultTemp =  $this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$r['user_id']));
				$result=array_merge($result,$resultTemp); 	
				//It will Select all the RBM Data
				$resultTemp =  $this->getEmpDetail(array('designation_id'=>'6','user_id'=>$r['user_id']));
				$result=array_merge($result,$resultTemp);
			}
			
		}
		
		//It will Select all the ABM whose reporting Person is not RBM or either it have no one as reportig person 
		$select=$this->_db->select()
						->from(array('UT'=>'employee_personaldetail'),array('user_id'))
						->joinleft(array('RP'=>'employee_personaldetail'),"RP.user_id=UT.Parent_id",array())
						->where("`UT`.`delete_status`='0' AND UT.designation_id=7 AND ((RP.designation_id!=6 AND RP.designation_id!=5) OR UT.Parent_id=0)");
						//echo $select->__toString();die;
		$abm =  $this->getAdapter()->fetchAll($select);
		
		if($abm){
			foreach($abm as $a){
				
				//It will select All BE of the ABM
				$resultTemp = $this->getEmpDetail(array('designation_id'=>'8','parent_id'=>$a['user_id']));
				if(!$resultTemp){
					$blankPerson[]=$a['user_id'];
					continue;
				}
				$result=array_merge($result,$resultTemp);
				$resultTemp =  $this->getEmpDetail(array('user_id'=>$a['user_id']));
				$result=array_merge($result,$resultTemp);
			}
		}
		
		//It will Select All BE that does not have parent 
		$be =  $this->getEmpListOnly(array('designation_id'=>'8','parent_id'=>'0'));
		foreach($be as $b){
			$blankPerson[]=$b['user_id'];
		}
		foreach($blankPerson as $bpid){
			$resultTemp =  $this->getEmpDetail(array('user_id'=>$bpid));
			$resultTemp[0]['blank']=1;
			$result=array_merge($result,$resultTemp);
		}
		//Code for getting remaining headquarters
		$headquarters=array_unique(array_filter($this->array_column($result,'headquater_name')));
		$Remaining=$this->getHeadqurterDetails($headquarters);
		$result=array_merge($result,$Remaining);
		$totalRowData=$result;
		//echo "<pre>";print_r($totalRowData);die;
		if(count($totalRowData)>0) {
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel();
			
			// Write Sheet Header
			$headers = array('0'=>'Employee Code','1'=>'Employee Name','2'=>'Designation','3'=>'Headquarter','4'=>'Region','5'=>'Zone','6'=>'Email','7'=>'Mobile Number','8'=>'Date of Birth','9'=>'Date of Joining');
			
			$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL,'A1');
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
			$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('C5BE97');
			
			$data=array();
			$i=0;
			foreach($totalRowData as $row)
			{
				if($row['Emp_Code']=="JC00002"){
					continue;
				}
				$data[$i]['Emp_Code']=$row['Emp_Code'];
				$data[$i]['Emp_Name']=$row['Emp_Name'];
				$data[$i]['Designation']=$row['Designation'];
				$data[$i]['headquater_name']=$row['headquater_name'];
				$data[$i]['region_name']=$row['region_name'];
				$data[$i]['zone_name']=$row['zone_name'];
				$data[$i]['email']=$row['email'];
				$data[$i]['contact_number']=$row['contact_number'];
				$data[$i]['dob']=$row['dob'];
				$data[$i]['doj']=$row['doj'];
				if($row['Designation']=="Regional Business Manager")
				{
					$pos=$i+2;
					//Setting Color for Particular Row
					$objPHPExcel->getActiveSheet()->getStyle('A'.$pos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$pos)->applyFromArray(
					array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' =>'00FF99')),'font'  => array('bold' => true)));
				}
				if($row['Designation']=="Area Business Manager")
				{
					$pos=$i+2;
					//Setting Color for Particular Row
					$objPHPExcel->getActiveSheet()->getStyle('A'.$pos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$pos)->applyFromArray(
					array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' =>'FAC090')),'font'  => array('bold' => true)));
				}
				if($row['Designation']=="Zonal Business Manager")
				{
					$pos=$i+2;
					//Setting Color for Particular Row
					$objPHPExcel->getActiveSheet()->getStyle('A'.$pos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$pos)->applyFromArray(
					array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' =>'F39A9A')),'font'  => array('bold' => true)));
				}
				if($row['blank']==1)
				{
					$pos=$i+2;
					//Setting Color for Particular Row
					$objPHPExcel->getActiveSheet()->getStyle('A'.$pos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$pos)->applyFromArray(
					array('fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID,'color' => array('rgb' =>'CCC0DA')),'font'  => array('bold' => true)));
				}
				$i++;
			}
			//print_r($data);die;
			// Setting Text Alignment Center
			$objPHPExcel->getActiveSheet()->getStyle('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			// Write Row Data
			$objPHPExcel->getActiveSheet()->fromArray($data, NULL,'A2');
			
			// Filter on All Column
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1'.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');
			
			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('Organo Gram Report');
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
			
			// Redirect output to a client�s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="organo_gram_report.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
			//$objWriter->save('organo_gram_report.xls');  //THIS WORKS
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);die;
			}
			else {

				$Header .= 	"\" No Data Found!! \"".$_nxtcol;

			}
    }
	public function getDesignationByDepartmentId(){

	    $select = $this->_db->select()

		 				->from(array('D2D'=>'designation_to_department'),array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=D2D.designation_id AND DES.parent_designation=0",array('designation_name'))

						->where("D2D.department_id='".$this->_getData['department_id']."'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result; 

	}

	public function addNewUser(){

	    $this->_getData['passwowrd_text']=$this->_getData['password'];
		
		$this->_getData['password'] = md5($this->_getData['password']);

	    $user_id = $this->insertInToTable('users',array($this->_getData));

		$this->_getData['user_id'] = $user_id;

		$this->_getData['employee_code'] = $this->generateEmployeeCode();

		$this->insertInToTable('employee_personaldetail',array($this->_getData));

		$this->insertEducation();

		$this->insertslaryAmount();

	    $this->insertEmployeementDetail();

	    $this->insertleavedetail();

		$this->DocumentUploade();

		$this->insertUpdateAccountDetail();
		
		$this->insertUpdateLocations();
		$this->insertUpdateExpenses();

	}

	public function editUser(){ 
	  unset($this->_getData['id']);
	  if(!empty($this->_getData['officialdetail']) || !empty($this->_getData['personal_detail'])){
			$record = $this->getStoreOldRecord();
			unset($record['id']);
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_ip']=$_SERVER['REMOTE_ADDR'];
			$record['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_personaldetail',array($record));
			
			//------------Newly inserted Code---------------------//
			if(isset($this->_getData['designation_id'])){
				if($this->_getData['designation_id']!=$record['designation_id']){
				
					//Selecting respective salary template
					$select = $this->_db->select()
							->from('salary_template',array('*'))
							->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' AND designation_id ='".$this->_getData['designation_id']."'");
							//echo $select->__toString();die;
							
					$templates = $this->getAdapter()->fetchRow($select);
					$additionalheads = explode(',',$templates['salaryhead_id']);
					$detectionheads = explode(',',$templates['detsalaryhead_id']);
					
					//Backing up employee salary amount
					$select = $this->_db->select()
							->from(array('ES'=>'employee_salary_amount'),array('*','CURDATE() AS modify_date'))
							->where("ES.user_id='".$this->_getData['user_id']."'");
					$salaries = $this->getAdapter()->fetchAll($select);
					unset($salaries['id']);
					$this->insertInToTable('edit_emp_salary_amount',$salaries);
					
					//This will delete all previous salary of respective user 
					$this->_db->delete('employee_salary_amount',"user_id='".$this->_getData['user_id']."'");
					
					//Use to insert salary amount in the form of additional and detection heads
					foreach($additionalheads as $head_id){
					
						$select = $this->_db->select()
							->from('salary_template_amount',array('*'))
							->where("salary_template_id='".$templates['salary_template_id']."' AND salaryhead_id ='".$head_id."'");
							//echo $select->__toString();die;
						$head_amount = $this->getAdapter()->fetchRow($select);
						
						$this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$head_id,'salaryheade_type'=>1,'amount'=>$head_amount['amount'])));
					}
					
					foreach($detectionheads as $head_id){
					
						$select = $this->_db->select()
							->from('salary_template_amount',array('*'))
							->where("salary_template_id='".$templates['salary_template_id']."' AND salaryhead_id ='".$head_id."'");
							//echo $select->__toString();die;
						$head_amount = $this->getAdapter()->fetchRow($select);
						
						$this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$head_id,'salaryheade_type'=>2,'amount'=>$head_amount['amount'])));
					}
					
					//It will update the old expenses
					
					$select = $this->_db->select()
									->from(array('ES'=>'emp_expense_amount'),array('*','CURDATE() AS modify_date'))
									->where("ES.user_id='".$this->_getData['user_id']."'");
					$Expenses = $this->getAdapter()->fetchAll($select);
				
					if(!empty($Expenses)){
			
						unset($Expenses['id']);
		
						$this->insertInToTable('edit_emp_exp_amount',$Expenses);
		
						$this->_db->delete('emp_expense_amount',"user_id='".$this->_getData['user_id']."'");
		
						$select = $this->_db->select()
										->from(array('ES'=>'expense_setting'),array('*'))
										->joininner(array('ETA'=>'expense_template_amount'),"ES.exp_setting_id=ETA.exp_setting_id",array('*'))
										->joininner(array('EH'=>'expense_head'),"EH.head_id=ETA.head_id",array('head_name'))
										->where("bunit_id='".$this->_getData['bunit_id']."' AND department_id ='".$this->_getData['department_id']."' 
											AND designation_id ='".$this->_getData['designation_id']."'")
										->group("EH.head_id");
					
						$result = $this->getAdapter()->fetchAll($select);
		     
						foreach($result as $expense){
							$this->_db->insert('emp_expense_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'head_id'=>$expense['head_id'],'expense_amount'=>$expense['expense_amount'])));
						}
					}
					//End of Expense updation code

				}
			}
			//--------------End of Newly inserted code-------------------//
			
			$this->updateTable('employee_personaldetail',$this->_getData,array('user_id'=>$this->_getData['user_id']));
			$this->updateTable('emp_bank_account',$this->_getData,array('user_id'=>$this->_getData['user_id']));
			$this->_db->update('users',array('user_type'=>$this->_getData['user_type']),"user_id='".$this->_getData['user_id']."'");
	  }
	  if(!empty($this->_getData['education'])){
			$Education	  = $this->getEducations($this->_getData['user_id'],'CURDATE() AS modify_date');
			unset($Education['id']);
			$this->insertInToTable('edit_emp_education',$Education);
			$this->insertEducation();
	  }
	  if(!empty($this->_getData['employeement'])){
			$Employeement = $this->getEmployeements($this->_getData['user_id'],'CURDATE() AS modify_date');
			unset($Employeement['id']);
			$this->insertInToTable('edit_employeement_detail',$Employeement);
			$this->insertEmployeementDetail();
	  }
	  if(!empty($this->_getData['salary_deatil'])){ 
	      $select = $this->_db->select()
		 				->from(array('ES'=>'employee_salary_amount'),array('*','CURDATE() AS modify_date'))
						->where("ES.user_id='".$this->_getData['user_id']."'");
		    $salaries = $this->getAdapter()->fetchAll($select);
			unset($salaries['id']);
			$this->insertInToTable('edit_emp_salary_amount',$salaries);
		    $this->updateslary();
			if(!empty($this->_getData['ctc_change_date']) && $this->_getData['ctc_change_date']!='0000-00-00'){
			  $this->_db->update('employee_personaldetail',array('ctc_change_date'=>$this->_getData['ctc_change_date']),"user_id='".$this->_getData['user_id']."'");
			}
	  }
	  if(!empty($this->_getData['documents'])){
	     $Documents    = $this->getDocumentsInfo($this->_getData['user_id'],'CURDATE() AS modify_date');
		 unset($Documents['id']);
		$this->insertInToTable('edit_emp_education',$Documents);
		 $this->DocumentUploade();
	  }
	  if(!empty($this->_getData['leaves'])){
	        $select = $this->_db->select()
		 				->from(array('ES'=>'emp_leaves'),array('*','CURDATE() AS modify_date'))
						->where("ES.user_id='".$this->_getData['user_id']."'");
		    $leaves = $this->getAdapter()->fetchAll($select);
			 unset($leaves['id']);
			$this->insertInToTable('edit_emp_leaves',$leaves);
	        $this->insertleavedetail();
	  }
	  if(!empty($this->_getData['accounts'])){
	        $Account      = $this->getBankAccountDetail($this->_getData['user_id']);
			unset($Account['id']);
			$Account['user_id'] = $this->_getData['user_id'];
			$Account['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_bank_account',array($Account));
		    $this->insertUpdateAccountDetail(false);
	  }
	  if(!empty($this->_getData['location'])){
			$record = $this->getStoreOldRecord();
			unset($record['id']);
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_locations',array($record));
			$this->insertUpdateLocations(false);
	  }
	  if(!empty($this->_getData['expense'])){
	        $select = $this->_db->select()
		 				->from(array('ES'=>'emp_expense_amount'),array('*','CURDATE() AS modify_date'))
						->where("ES.user_id='".$this->_getData['user_id']."'");
		    $Expenses = $this->getAdapter()->fetchAll($select);
			unset($Expenses['id']);
			$this->insertInToTable('edit_emp_exp_amount',$Expenses);
	        $this->insertUpdateExpenses();
	  }
	 
	}

	

	public function insertEducation(){
     if(!empty($this->_getData['degree'])){
	   $this->_db->delete('employee_education',"user_id='".$this->_getData['user_id']."'");
	   foreach($this->_getData['degree'] as $key=>$education){

	       $this->_db->insert('employee_education',array_filter(array('user_id'=>$this->_getData['user_id'],

		   					  'degree'=>$education,'degree_name'=>$this->_getData['degree_name'][$key],

		   					  'collage'=>$this->_getData['collage'][$key],'board'=>$this->_getData['board'][$key],

							  'per_mark'=>$this->_getData['per_mark'][$key],'year_passing'=>$this->_getData['year_passing'][$key])));

	   }
	  }

	}

	

	public function insertslaryAmount(){
	  if(!empty($this->_getData['amount'][1]) || $this->_getData['amount'][2]){
	   $this->_db->delete('employee_salary_amount',"user_id='".$this->_getData['user_id']."'");

	    foreach($this->_getData['amount'][1] as $key=>$sal_amount){

	       $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$key,'salaryheade_type'=>1,

		   					  'amount'=>$sal_amount)));

	    }

	   foreach($this->_getData['amount'][2] as $key=>$sal_amount){

	       $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$key,'salaryheade_type'=>2,

		   					  'amount'=>$sal_amount)));

	    }
	  }	
    if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	      $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));
		 }					  
	  }			

	}
	
	public function updateslary(){  
	  if(!empty($this->_getData['amount'][1]) || $this->_getData['amount'][2]){
	    if(isset($this->_getData['chage_current_sal']) && $this->_getData['chage_current_sal']==1){
		       $salaryobj = new SalaryManager();
		 }
		foreach($this->_getData['amount'][1] as $key=>$sal_amount){
		   $this->_db->update('employee_salary_amount',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=1");
		   if(isset($this->_getData['chage_current_sal']) && $this->_getData['chage_current_sal']==1 && $key!=3){
		       $this->_db->update('salary_list',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=1 AND date='".$salaryobj->FromDate."'");  
		   }
	    }

	   foreach($this->_getData['amount'][2] as $key=>$sal_amount){
			$this->_db->update('employee_salary_amount',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=2");
			if(isset($this->_getData['chage_current_sal']) && $this->_getData['chage_current_sal']==1){
		       $this->_db->update('salary_list',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=2 AND date='".$salaryobj->FromDate."'");  
		   }
	    }
	  }	
    if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	      $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));
		  if(isset($this->_getData['chage_current_sal']) && $this->_getData['chage_current_sal']==1){
			    $this->_db->insert('salary_list',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount,'date'=>$salaryobj->FromDate)));  
		   }
		 }					  
	  }			

	}

	

	public function insertEmployeementDetail(){

	   $this->_db->delete('emp_employeement_detail',"user_id='".$this->_getData['user_id']."'");

	   $companies = array_filter($this->_getData['company']);

	   foreach($companies as $key=>$company){

	       $this->_db->insert('emp_employeement_detail',array_filter(array('user_id'=>$this->_getData['user_id'],'compnay'=>$company,'designation'=>$this->_getData['designation'][$key],'from_year'=>$this->_getData['from_year'][$key],'from_month'=>$this->_getData['from_month'][$key],'to_year'=>$this->_getData['to_year'][$key],'to_month'=>$this->_getData['to_month'][$key],'joining_ctc'=>$this->_getData['joining_ctc'][$key],'leaving_ctc'=>$this->_getData['leaving_ctc'][$key],'reasion_of_leaving'=>$this->_getData['reasion_of_leaving'][$key])));

	    }

	}

	

    public function DocumentUploade() {
	  if(!empty($this->_getData['document_type'])){
        $upload = new Zend_File_Transfer();

        $file = $upload->getFileInfo();

		$time = time();

		foreach($this->_getData['document_type'] as $key=>$docuploade){

		    $Filename = $time.$file['documnet_'.$key.'_']['name']; 

			$upload->addFilter('Rename', array('target' => Bootstrap::$root.'/public/DocumentDirectory/'.$Filename, 'overwrite' => true));

			$upload->receive($file['documnet_'.$key.'_']['name']);

			 $this->_db->insert('emp_docoments',array_filter(array('user_id'=>$this->_getData['user_id'],'file_name'=>$Filename,'document_type'=>$docuploade)));

			

		}
	  }	

    }

	

	public function insertleavedetail(){
      if(!empty($this->_getData['leave_id'])){
	   $this->_db->delete('emp_leaves',"user_id='".$this->_getData['user_id']."'");
		foreach($this->_getData['leave_id'] as $key=>$leave_id){
	       $this->_db->insert('emp_leaves',array_filter(array('user_id'=>$this->_getData['user_id'],'leave_id'=>$leave_id,'no_of_leave'=>$this->_getData['no_of_leave'][$key],'aproval_authority'=>implode(',',$this->_getData['aproval_authority']))));

	    }
	  }
	  
	  if(!empty($this->_getData['leave_approve_auth'])){
	     $this->_db->delete('emp_leave_approval',"user_id='".$this->_getData['user_id']."'");
            foreach($this->_getData['leave_approve_auth'] as $key=>$approveAuth){
			  $this->_db->insert('emp_leave_approval',array_filter(array('user_id'=>$this->_getData['user_id'],'approval_user_id'=>$approveAuth,'position'=>$this->_getData['authority_position'][$key])));
			}
		}
	}

	

	public function insertUpdateAccountDetail($insert=true){

		if($insert==true){

		    $this->insertInToTable('emp_bank_account',array($this->_getData));

		}else{

		    $this->updateTable('emp_bank_account',$this->_getData,array('user_id'=>$this->_getData['user_id']));

		}	

	}
	
	public function insertUpdateLocations($insert=true){
	    if(isset($this->_getData['region_id']) && ($this->_getData['headquater_id']==NULL)){ 
		    $searchString='';
		    if(isset($this->_getData['region_id']) && ($this->_getData['area_id']==NULL) ){
			    $select = $this->_db->select()
									->from(array('RE'=>'region'),array('region_name'))
									->where("RE.region_id='".$this->_getData['region_id']."'");
				$result = $this->getAdapter()->fetchRow($select);
				$searchString=$result['region_name'];
			}else if(isset($this->_getData['area_id'])){
			    $select = $this->_db->select()
									->from(array('AR'=>'area'),array('area_name'))
									->where("AR.area_id='".$this->_getData['area_id']."'");
				$result = $this->getAdapter()->fetchRow($select);
				$searchString=$result['area_name'];
			}
			
			$select = $this->_db->select()
								->from(array('HE'=>'headquater'),array('headquater_id'))
								->where("HE.headquater_name LIKE '%".$searchString."%'")
								->Order("HE.headquater_id ASC");//echo $select->__toString();die;
			$result = $this->getAdapter()->fetchRow($select);
			$this->_getData['headquater_id']=($result['headquater_id']>0)?$result['headquater_id']:'';
		}
	    if($insert){
		      $this->insertInToTable('emp_locations',array($this->_getData));
		}else{
		      $this->updateTable('emp_locations',$this->_getData,array('user_id'=>$this->_getData['user_id']));
		}
	}
 
   public function insertUpdateExpenses(){		
		if(!empty($this->_getData['head_id'])){
		   $this->_db->delete('emp_expense_amount',"user_id='".$this->_getData['user_id']."'");
            foreach($this->_getData['head_id'] as $key=>$head_id){
			  $this->_db->insert('emp_expense_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'head_id'=>$head_id,'expense_amount'=>$this->_getData['expense_amount'][$key])));
			}
		}
		if(!empty($this->_getData['expese_approve_auth'])){
		   $this->_db->delete('expense_approval',"user_id='".$this->_getData['user_id']."'");
            foreach($this->_getData['expese_approve_auth'] as $key=>$approveAuth){
			  $this->_db->insert('expense_approval',array_filter(array('user_id'=>$this->_getData['user_id'],'approval_user_id'=>$approveAuth,'position'=>$this->_getData['position'][$key])));
			}
		}
 	}
		

	public function getUsers(){
	     $filter = '';
		 if($this->_getData['user_id']>0){
		   $filter .= " AND UT.user_id='".$this->_getData['user_id']."'";
		 }
		 if($this->_getData['designation_id']>0){
		   $filter .= " AND DES.designation_id='".$this->_getData['designation_id']."'";
		 }
		 if($this->_getData['department_id']>0){
		   $filter .= " AND DEP.department_id='".$this->_getData['department_id']."'";
		 }
		 if($this->_getData['headquater_id']>0){
		   $filter .= " AND EL.headquater_id='".$this->_getData['headquater_id']."'";
		 }
		 if($this->_getData['search_word']!=''){
		   $filter .= " AND (UT.employee_code='".$this->_getData['search_word']."' OR UT.first_name LIKE '".$this->_getData['search_word']."' OR US.username='".$this->_getData['search_word']."')";
		 }
	     $orderlimit = CommonFunction::OdrderByAndLimit($this->_getData,'UT.employee_code');
	    
		//Now no use of below lines 
		/*$select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array('COUNT(1) AS CNT'))EditUserDetail
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array(''))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array(''))
						->joininner(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array(''))
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array(''))
						->joininner(array('US'=>'users'),"US.user_id=UT.user_id",array(''))
						->where("delete_status='0'".$filter); 
		$total = $this->getAdapter()->fetchRow($select);*/

	     $select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array('*'))
 
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->joininner(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array(''))
						->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
						->joininner(array('US'=>'users'),"US.user_id=UT.user_id",array('username','passwowrd_text'))
						->where("delete_status='0'".$filter)
						->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']);
						//->limit($orderlimit['Toshow'],$orderlimit['Offset']);

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		//return array('Total'=>$total['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		return array('Records'=>$result);
	}

     

	public function EditUserDetail(){

	     $select = $this->_db->select()

		 				->from(array('UT'=>'users'),array('*'))

		 				->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=UT.user_id",array('*'))

						->where("UT.user_id='".$this->_getData['user_id']."'");

		//echo $select->__toString();die;

		 $result = $this->getAdapter()->fetchRow($select);

		 

		 $select = $this->_db->select()

		 				->from(array('EE'=>'employee_education'),array('*'))

						->where("EE.user_id='".$this->_getData['user_id']."'")
						->order("degree");

		 $education = $this->getAdapter()->fetchAll($select);

		 $result['Education'] = $education;

		 

		 $select = $this->_db->select()

		 				->from(array('ED'=>'emp_employeement_detail'),array('*'))

						->where("ED.user_id='".$this->_getData['user_id']."'");

		 $employeement = $this->getAdapter()->fetchAll($select);

		 $result['Employeement'] = $employeement;

		 

		 $select = $this->_db->select()

		 				->from(array('DT'=>'emp_docoments'),array('*'))

						->where("DT.user_id='".$this->_getData['user_id']."'");

		 $documents = $this->getAdapter()->fetchAll($select);

		 $result['Document'] = $documents;

		 

		 $select = $this->_db->select()

		 				->from(array('AD'=>'emp_bank_account'),array('*'))

						->where("AD.user_id='".$this->_getData['user_id']."'");

		 $accountDetail = $this->getAdapter()->fetchRow($select);

		 $result['AccountDetail'] = $accountDetail;

		 $result['Location'] = $this->getlocationInfo($this->_getData['user_id']);
			///print_r($result['Location']);die;
		return $result; 

	}

    

    public function UpdateUserDetail(){

       $updata = $this->_getData;

       unset($updata['user_id']);

       $this->updateTable('user_detail',$updata,array('user_id'=>$this->_getData['user_id']));

    }

	

    public function generateEmployeeCode(){

       $select = $this->_db->select()

		 				->from(array('ED'=>'employee_personaldetail'),array('employee_code'))

						->order("employee_code DESC")

						->limit(1);

	   $emp_code = $this->getAdapter()->fetchRow($select);

	   if($emp_code['employee_code']!=''){

	     $substr = substr($emp_code['employee_code'],3);

	     $empcode = 'JC'. str_pad(($substr + 1),5,'0',STR_PAD_LEFT);

	   }else{

	     $empcode = 'JC00001';

	   }

	   return $empcode;

   }

   public function viewUserDetail(){

       $user_type = $this->getUserType($user_id);

	   $dataarray = array();

       $select = $this->_db->select()

		 				->from(array('UD'=>'employee_personaldetail'),array('*'))

						->joinleft(array('PU'=>'employee_personaldetail'),"PU.user_id=UD.parent_id",array('CONCAT(PU.first_name," ",PU.last_name) as Parent'))

						->joinleft(array('BU'=>'bussiness_unit'),"BU.bunit_id=UD.bunit_id",array('bunit_name'))

						->joinleft(array('DP'=>'department'),"DP.department_id=UD.department_id",array('department_name'))

						->joinleft(array('DG'=>'designation'),"DG.designation_id=UD.designation_id",array('designation_name'))

						->joinleft(array('HO'=>'headoffice'),"HO.headoff_id=UD.office_id",array('office_name'))

						->where("UD.user_id='".$this->_getData['user_id']."'");

	   $dataarray = $this->getAdapter()->fetchRow($select);

	   $dataarray['Education'] 	  = $this->getEducations($this->_getData['user_id']);

	   $dataarray['Employeement'] = $this->getEmployeements($this->_getData['user_id']);

	   $dataarray['Account']      = $this->getBankAccountDetail($this->_getData['user_id']);

	   $dataarray['Documents']    = $this->getDocumentsInfo($this->_getData['user_id']);
	   $dataarray['Location']    = $this->getlocationInfo($this->_getData['user_id']);

	  return $dataarray; 

   }				
   public function AddPrivillage(){ //print_r($this->_getData);die;
      $this->_db->delete('user_privillage',"user_id='".$this->_getData['user_id']."'");
	  if(!empty($this->_getData['tree'])){
		  foreach($this->_getData['tree'] as $module_id){
			 $this->_db->insert('user_privillage',array('user_id'=>$this->_getData['user_id'],'module_id'=>$module_id)); 
		  }
	 }
   }
   public function getUsersPrivForEdit(){
      $select = $this->_db->select()
	  					   ->from('user_privillage',array('*'))
						   ->where("user_id='".$this->_getData['user_id']."'");
	   $provillages = $this->getAdapter()->fetchAll($select);
	    $PrivillageArr = array();
	   if(!empty($provillages))	{
	        foreach($provillages as $priv){
			   $PrivillageArr[] = $priv['module_id'];
			 }
	   }//print_r($PrivillageArr);die;
	   return  $PrivillageArr;				   
   }
   
   public function getParentListForAssign(){
      /*$select = $this->_db->select()
	  					   ->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name'))
						   ->joinleft(array('PED'=>'employee_personaldetail'),"PED.user_id='".$this->_getData['user_id']."'",array())
						   ->joinleft(array('DG'=>'designation'),"DG.designation_id=ED.designation_id",array('designation_code'))
						   ->where("ED.designation_id=PED.designation_id AND ED.department_id=PED.department_id AND ED.user_id!=PED.user_id");*/
		 $select = $this->_db->select()
		 				->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name'))
						->joinleft(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
						->joinleft(array('DT1'=>'designation'),"DT1.designation_id='".$this->_getData['designation_id']."'",array())
						->where("DT1.designation_level>=DT.designation_level AND ED.delete_status='0'");
	   $parents = $this->getAdapter()->fetchAll($select);
	   return $parents;
   }
   public function deleteusers(){
          $this->_db->update('employee_personaldetail',array('parent_id'=>$this->_getData['parent_id']),"parent_id='".$this->_getData['user_id']."'");
	  $this->_db->update('emp_leave_approval',array('approval_user_id'=>$this->_getData['parent_id']),"approval_user_id='".$this->_getData['user_id']."'");
	  $this->_db->update('expense_approval',array('approval_user_id'=>$this->_getData['parent_id']),"approval_user_id='".$this->_getData['user_id']."'");
	  $this->_db->update('employee_personaldetail',array_filter(array('delete_status'=>'1','reasion'=>$this->_getData['reasion'])),"user_id='".$this->_getData['user_id']."'");
	  
	  $_SESSION[SUCCESS_MSG] = 'Employee Deleted Successfully';
   }
   
   public function changePassword(){
      $this->_db->update('users',array('password'=>md5($this->_getData['password']),'passwowrd_text'=>addslashes($this->_getData['password'])),"user_id='".$this->_getData['user_id']."'");
	  $_SESSION[SUCCESS_MSG] = 'Password Hase been change successfully';
   }
   public function previusemployee(){
     $select = $this->_db->select()

		 				->from(array('UT'=>'employee_personaldetail'),array('*'))

		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))

						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->joinleft(array('US'=>'users'),"US.user_id=UT.user_id",array('username','passwowrd_text'))
						->where("delete_status='1'");

		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;
   }
   
    public function getStoreOldRecord(){
         $select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array('*'))
		 				->joinleft(array('EL'=>'emp_locations'),"EL.user_id=UT.user_id",array('*'))
						->where("UT.user_id='".$this->_getData['user_id']."'");
		//echo $select->__toString();die;
		$result = $this->getAdapter()->fetchRow($select);//print_r($result);die;
		return $result;
   }	
   
    public function getOldRecordOfEmployee(){
	   $select = $this->_db->select()
		 				->from(array('UT'=>'emp_old_record'),array('*'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'));
		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;
	}
   public function storeHistory(){
			$Education	  = $this->getEducations($this->_getData['user_id']);
			$Employeement = $this->getEmployeements($this->_getData['user_id']);
			$Account      = $this->getBankAccountDetail($this->_getData['user_id']);
			$Documents    = $this->getDocumentsInfo($this->_getData['user_id']);
			//$Location     = $this->getlocationInfo($this->_getData['user_id']);
	     	$record = $this->getStoreOldRecord();
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_ip']=$_SERVER['REMOTE_ADDR'];
			//$record['modify_by']=$_SESSION['AdminLoginID'];
			$record['modify_date']=date('Y-m-d');
		    $this->insertInToTable('edit_emp_personaldetail',array($record));
			$this->insertInToTable('edit_emp_locations',array($record));
			//$this->insertInToTable('edit_emp_bank_account',array($record));
			//$this->insertInToTable('edit_emp_locations',array($record));
			//$this->insertInToTable('edit_emp_locations',array($record));
   }
   public function getUserHistory(){
     $select = $this->_db->select()
		 				->from(array('UT'=>'edit_emp_personaldetail'),array('*'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->joinleft(array('EL'=>'edit_emp_locations'),"EL.user_id=UT.user_id",array('*'))
						->where("UT.user_id='".$this->_getData['user_id']."'");
		//echo $select->__toString();die;

		$result = $this->getAdapter()->fetchAll($select);

		return $result;
   }
  public function getempleaves(){
    $select = $this->_db->select()
		 				->from(array('UT'=>'employee_personaldetail'),array('employee_code','user_id'))
		 				->joininner(array('DES'=>'designation'),"DES.designation_id=UT.designation_id",array('designation_name'))
						->joininner(array('DEP'=>'department'),"DEP.department_id=UT.department_id",array('department_name'))
						->where("delete_status='0'");
	$results = $this->getAdapter()->fetchAll($select);
	
	$select = $this->_db->select()
		 				->from(array('LT'=>'leavetypes'),array('*'));//echo $select->__toString();die;
	$leavetype = $this->getAdapter()->fetchAll($select);
	   
	foreach($results as $leave){
	   $select = $this->_db->select()
		 				->from(array('EL'=>'emp_leaves'),array('*'))
						->where("user_id='".$leave['user_id']."'");//echo $select->__toString();die;
	   $empleaves = $this->getAdapter()->fetchAll($select);//print_r($empleaves);die;
	   foreach($empleaves as $empleave){
	     $leave['Leave'][$empleave['leave_id']] = $empleave['no_of_leave'];
	   }
	   $finaldata[] = $leave;  
	} //print_r($finaldata);die;
	return array($finaldata,$leavetype);
  }
  
  public function updateEmployeeLeaves(){
    //echo "<pre>"; print_r($this->_getData); echo "<pre>";die;
	 if(!empty($this->_getData['user_id'])){
	    foreach($this->_getData['user_id'] as $user_id){
		       $select = $this->_db->select()
		 				->from(array('EL'=>'emp_leaves'),array('COUNT(1) AS CNT'))
						->where("user_id='".$user_id."'");//echo $select->__toString();die;
	   		   $empleaves = $this->getAdapter()->fetchRow($select);
			if($empleaves['CNT']>0){  
                         foreach($this->_getData['leave'][$user_id] as $key=>$leaves){
                                 $this->_db->update('emp_leaves',array('no_of_leave'=>$leaves),"user_id='".$user_id."' AND leave_id='".$key."'");
                             }
		     }else{
			    foreach($this->_getData['leave'][$user_id] as $key=>$leaves){ 
			    $this->_db->insert('emp_leaves',array_filter(array('no_of_leave'=>$leaves,'user_id'=>$user_id,'leave_id'=>$key)));
			  }	
			}	 
		}
	 }
  }
  public function usersettlementDetail(){
        $select = $this->_db->select()
                                ->from(array('EPD'=>'employee_personaldetail'),array(''))
                                ->joininner(array('EAA'=>'emp_acceseries_account'),"EAA.user_id=EPD.user_id",array('*'))
                                ->where("EPD.employee_code='".$this->_getData['employee_code']."'");//echo $select->__toString();die;
	$acceseries['GeneralDetail'] = $this->getAdapter()->fetchAll($select);
	
	$select = $this->_db->select()
                                ->from(array('UT'=>'employee_salary_amount'),array('*'))
                                ->joininner(array('SH'=>'salary_head'),"SH.salaryhead_id=UT.salaryhead_id",array("salary_title"))
								->joininner(array('EPD'=>'employee_personaldetail'),"EPD.user_id=UT.user_id",array(''))
                                ->where("EPD.employee_code='".$this->_getData['employee_code']."'")
                                ->order("salaryhead_id ASC");
                                //echo $select->__toString();die;
	 $acceseries['SalaryDetail']  = $this->getAdapter()->fetchAll($select);							
      return $acceseries;
  }
  public function finalsettlement(){
	 if(!empty($this->_getData['final_settlement'])){
	     foreach($this->_getData['final_settlement'] as $key=>$account_id){
	     	$this->_db->update('emp_acceseries_account',array('final_settlement'=>$account_id),"emp_acc_id='".$key."'");
		 }
		 $this->_db->insert('emp_settlement',array_filter(array('resign_date'=>$this->_getData['resign_date'],'user_id'=>$this->_getData['user_id'],'notice_day'=>$this->_getData['user_id'],'sett_amount'=>$this->_getData['sett_amount'],'cheque_number'=>$this->_getData['cheque_number'])));
	 }
	 $_SESSION[SUCCESS_MSG] = 'Record Has been update succesfully';
  }
  
  public function updateslaryAmount(){
	  if(!empty($this->_getData['amount'][1]) || $this->_getData['amount'][2]){
	    foreach($this->_getData['amount'][1] as $key=>$sal_amount){
	       $this->_db->update('employee_salary_amount',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=1");

	    }
	   foreach($this->_getData['amount'][2] as $key=>$sal_amount){
	       $this->_db->update('employee_salary_amount',array('amount'=>$sal_amount),"user_id='".$this->_getData['user_id']."' AND salaryhead_id='".$key."' AND salaryheade_type=2");

	    }
	  }	
    if(count($this->_getData['extra_amount'])>0){
	  foreach($this->_getData['extra_amount'] as $key=>$amount){
	      $this->_db->insert('employee_salary_amount',array_filter(array('user_id'=>$this->_getData['user_id'],'salaryhead_id'=>$this->_getData['extra_head'][$key],'salaryheade_type'=>$this->_getData['extra_type'][$key],'amount'=>$amount)));
		 }					  
	  }			

	}
	public function editUserprofile(){ //echo "<pre>";print_r($this->_getData);die;
			$record = $this->getStoreOldRecord();
			unset($record['id']);
			$record['user_id'] = $this->_getData['user_id'];
			$record['modify_ip']=$_SERVER['REMOTE_ADDR'];
			$record['modify_date']=date('Y-m-d');
			$this->insertInToTable('edit_emp_personaldetail',array($record));
			unset($record['user_id']);
			$this->updateTable('employee_personaldetail',$this->_getData,array('user_id'=>$this->_getData['user_id']));
 
 			$Education	  = $this->getEducations($this->_getData['user_id'],'CURDATE() AS modify_date');
			unset($Education['id']);
			$this->insertInToTable('edit_emp_education',$Education);
			$this->insertEducation();
	}
	
		/**
		 * Method getModules() get list of module_id, parent_id, level_id, module_name on the basis of module id and gui=web and which
		   have delete_status=0 and status=1..
		 * @access	public
		 * @param	$moduleID , hold module ID and $gui holds web
		 * @return	array.
		 */
		//public function getModules($moduleID=0){
		public function getModulesHRM($moduleID=0){	

			echo "1203 user.php model page getModuleHRM funciton"; die;
			$select = $this->_db->select()
                                ->from('crm_modules',array('module_id','parent_id','level_id','module_name'))
                                ->where("parent_id='".(int)$moduleID."' AND isActive='1' AND isDelete='0'")
								->order('module_id ASC'); //echo $select->__toString();die;
			return $this->getAdapter()->fetchAll($select);
		}
		
		
		/**
		 * Method getModuleSections() get list of all section_id and section names on the basis of module id.
		 * @access	public
		 * @param	$moduleID holds module_id
		 * @return	array
		 */
		public function getModuleSections($moduleID){
			$select = $this->_db->select()
                                ->from(array('MS'=>'crm_modulesections'),array('MS.section_id'))
                                ->joininner(array('ST'=>'crm_sections'),"ST.section_id=MS.section_id AND ST.status='1' AND ST.delete_status='0'",array('ST.section_name'))
                                ->where("MS.module_id='".(int)$moduleID."'")
								->order('ST.section_name ASC'); //echo $select->__toString();die;
			return $this->getAdapter()->fetchAll($select);
		}
		
		
		/**
		 * Method getLevelPrivileges() get list of module_id, section_id, status, delete_status on the basis of level_id and 
		   which have delete_status=0, status=1.
		 * @access	public
		 * @param	$levelID holds level_id
		 * @return	array
		 */
		public function getLevelPrivileges($levelID){
			$select = $this->_db->select()
                                ->from(array('LP'=>'crm_leveldefaultprivileges'),array('LP.module_id','LP.section_id'))
                                ->joininner(array('CM'=>'crm_modules'),"CM.module_id=LP.module_id AND CM.isActive='1' AND CM.isDelete='0'",'')
								->joinleft(array('ST'=>'crm_sections'),"ST.section_id=LP.section_id",array('ST.status','ST.delete_status'))
                                ->where("LP.level_id='".(int)$levelID."'"); //echo $select->__toString();die;
			$results = $this->getAdapter()->fetchAll($select);
			$modules = array(); $sections = array();
			foreach($results as $info) {
				$modules[] = $info['module_id'];
				if($info['status'] == 1 && $info['delete_status'] == 0) {
					$sections[$info['module_id']][] = $info['section_id'];
				}
			}
			return array('Modules'=>$modules,'Sections'=>$sections);
		}
		
		
		/**
		 * Method getUserPrivileges() get module_id, section_id, status, delete_status for the given user_id which have delete_status=0, status=1.
		 * @access	public
		 * @param	$userID holds user_id
		 * @return	array
		 */
		public function getUserPrivileges($userID){
			$select = $this->_db->select()
                                ->from(array('UP'=>'crm_userprivileges'),array('UP.module_id','UP.section_id'))
                                ->joininner(array('CM'=>'crm_modules'),"CM.module_id=UP.module_id AND CM.isActive='1' AND CM.isDelete='0'",'')
								->joinleft(array('ST'=>'crm_sections'),"ST.section_id=UP.section_id",array('ST.status','ST.delete_status'))
                                ->where("UP.user_id='".(int)$userID."'"); //echo $select->__toString();die;
			$results = $this->getAdapter()->fetchAll($select);
			$modules = array(); $sections = array();
			foreach($results as $info) {
				$modules[] = $info['module_id'];
				if($info['status'] == 1 && $info['delete_status'] == 0) {
					$sections[$info['module_id']][] = $info['section_id'];
				}
			}
			return array('Modules'=>$modules,'Sections'=>$sections);
		}
		
		/**
		 * Method addUserPrivileges() insert level_id,module_id, section_id to level privilege table on the basis of level_id to 
		   add default privilege for userlevels.
		 * @access	public
		 * @param	$data
		 * @return	boolean
		 */
		public function addUserPrivileges($data){
			$this->_db->delete('crm_userprivileges',"user_id='".$data['token']."'");
	   		if(count($data['modules']) > 0) {
				foreach($data['modules'] as $moduleID) {
					if(is_array($data[$moduleID])) {
						foreach($data[$moduleID] as $sectionID) {
							$this->_db->insert('crm_userprivileges',array_filter(array('user_id'=>$data['token'],'module_id'=>$moduleID,'section_id'=>$sectionID)));
						}
					}
					else {
						$this->_db->insert('crm_userprivileges',array_filter(array('user_id'=>$data['token'],'module_id'=>$moduleID,'section_id'=>'0')));
					}
				}
			}
			return TRUE;
		}

	public function getHeadqurterDetails($data=array()){
		if(!empty($data)){
			$select = $this->_db->select()
			 				->from(array('HQ'=>'headquater'),array(new Zend_Db_Expr("'--VACANT--' as Emp_Code,'--VACANT--' as Emp_Name,'--VACANT--' as email,'--VACANT--' as contact_number,'--VACANT--' as doj,'--VACANT--' as dob,'--VACANT--' as Designation"),'HQ.headquater_name'))
							->joininner(array('RT'=>'region'),"RT.region_id=HQ.region_id",array('region_name'))
			 				->joininner(array('ZT'=>'zone'),"ZT.zone_id=HQ.zone_id",array('zone_name'))
							//->where($where)
							->where("headquater_name NOT IN (?)",$data)
							->order('headquater_name ASC'); //echo $select->__toString();die;
			$result = $this->getAdapter()->fetchAll($select);
			return $result;
		}else{
			return array();
		}
	}

    function array_column(array $input, $columnKey, $indexKey = null){
        $array = array();
        foreach ($input as $value) {
            if ( !array_key_exists($columnKey, $value)) {
                trigger_error("Key \"$columnKey\" does not exist in array");
                return false;
            }
            if (is_null($indexKey)) {
                $array[] = $value[$columnKey];
            }
            else {
                if ( !array_key_exists($indexKey, $value)) {
                    trigger_error("Key \"$indexKey\" does not exist in array");
                    return false;
            	}
	            if ( ! is_scalar($value[$indexKey])) {
	                trigger_error("Key \"$indexKey\" does not contain scalar value");
	                    return false;
	            }
            	$array[$value[$indexKey]] = $value[$columnKey];
            }
        }
        return $array;
    }
}

?>