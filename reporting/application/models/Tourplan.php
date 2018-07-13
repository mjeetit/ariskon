<?php

    class Tourplan extends Zend_Custom

    {

	public $_getData = array();
	
	public function getTourPlans(){
	       $where = '1';
		  if($_SESSION['AdminDesignation']==8){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminDesignation']==7){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }elseif($_SESSION['AdminDesignation']==6){
		     $childs =  $this->getChilllds("ED");
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
		  }elseif($_SESSION['AdminDesignation']==5){
		    //$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
			$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
		  }
		   
		   $filterparam = '';
			//Filter With Employee Name
			if(!empty($this->_getData['user_id'])){
				$filterparam .= " AND ED.user_id='".$this->_getData['user_id']."'";
			}
			//Filter With Headquarter
			if(!empty($this->_getData['hedquater_id'])){
				$filterparam .= " AND EL.headquater_id='".$this->_getData['hedquater_id']."'";
			}
			
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_tourplan'),array('*','GROUP_CONCAT(EE.tour_date) AS CNT','DATE_FORMAT(EE.tour_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joinleft(array('ED1'=>'employee_personaldetail'),"ED1.user_id=EE.accepte_by",array('CONCAT(ED1.first_name," ",ED1.last_name) AS approved_by','employee_code AS app_emp_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where($where.$filterparam)
							   ->where("DATE_FORMAT(EE.tour_date,'%Y-%m')='".date('Y-m')."'")
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.tour_date,'%Y-%m')")
							   ->order("EE.tour_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;
	
	}
	public function getTourDetail(){
	     $select = $this->_db->select()
							   ->from(array('EE'=>'app_tourplan'),array('*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code','designation_id'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(""))
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=EE.location_id",array('patch_name'))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EE.headquater_id",array('IF(EE.call_type=1,EE.other_city,headquater_name) AS headquater_name'))
							   ->joinleft(array('CT'=>'city'),"CT.headquater_id=EE.headquater_id",array('IF(EE.call_type=1,EE.other_city,city_name) AS city_name'))
							   ->where("EE.user_id='".$this->_getData['user_id']."' AND  DATE_FORMAT(EE.tour_date,'%Y-%m')='".date('Y-m')."'")
							   ->group("PC.patch_id")
							   ->group("EE.tour_date")
							   ->order("EE.tour_date ASC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;
	}
	public function visitwith($data){
	  if(($data['designation_id']==8)){
	  
	  
	  }
	}
	
    public function getNextTourPlans(){
	    $where = '1';
		  if($_SESSION['AdminDesignation']==8){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminDesignation']==7){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }elseif($_SESSION['AdminDesignation']==6){
		     $childs =  $this->getChilllds("ED");
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
		  }elseif($_SESSION['AdminDesignation']==5){
		    //$childs =  $this->getChilllds("ED");
		    //$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
			$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
		  }//print_r($this->_getData);die;
		  if(!empty($this->_getData['user_id'])){
		    $where .= " AND ED.user_id='".$this->_getData['user_id']."'";
		  }
		  if(!empty($this->_getData['hedquater_id'])){
		    $where .= " AND HT.headquater_id='".$this->_getData['hedquater_id']."'";
		  }
		   
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_tourplan'),array('*','GROUP_CONCAT(EE.tour_date) AS CNT','DATE_FORMAT(EE.tour_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where($where)
							   ->where("DATE_FORMAT(EE.tour_date,'%Y-%m')=DATE_FORMAT( NOW( ) + INTERVAL 1 
MONTH ,  '%Y-%m' ) ")
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.tour_date,'%Y-%m')")
							   ->order("EE.tour_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;
	}
	public function getNextTourPlansDetail(){
	   $select = $this->_db->select()
							   ->from(array('EE'=>'app_tourplan'),array('*','GROUP_CONCAT(tour_id) AS approval_id'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code','designation_id'))
							   ->joinleft(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(""))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=EE.location_id",array('GROUP_CONCAT(patch_name) AS patch_name'))
							   ->where("EE.user_id='".$this->_getData['user_id']."' AND DATE_FORMAT(EE.tour_date,'%Y-%m')=DATE_FORMAT( NOW( ) + INTERVAL 1 MONTH ,  '%Y-%m' ) ")
							   ->group("PC.patch_id")
							   ->group("tour_date")
							   ->order("EE.tour_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;
	}
	public function ExportTourPlan($current=false){
	    $where = '1';
		  if($_SESSION['AdminDesignation']==8){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminDesignation']==7){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }elseif($_SESSION['AdminDesignation']==6){
		     $childs =  $this->getChilllds("ED");
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
		  }elseif($_SESSION['AdminDesignation']==5){
		    //$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
			$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
		  }
		  if($current){
		       $condition = "DATE_FORMAT(EE.tour_date,'%Y-%m')=DATE_FORMAT( NOW( ) ,  '%Y-%m' )";
		  }else{
		       $condition = "DATE_FORMAT(EE.tour_date,'%Y-%m')=DATE_FORMAT( NOW( ) + INTERVAL 1 MONTH ,  '%Y-%m' )";
		  }
		   
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_tourplan'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.tour_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where($where)
							   ->where($condition)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.tour_date,'%Y-%m')")
							   ->order("EE.tour_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			
			ini_set("memory_limit","-1");
			set_time_limit( 0 );
			ob_end_clean();
			$objPHPExcel = new PHPExcel();
			//$objWorkSheet = $objPHPExcel->createSheet();
			$page = 0;
		foreach($result as $emp){
		   $row = 1;
		   if($page>0){
		          $objWorkSheet = $objPHPExcel->createSheet();
				  $objPHPExcel->setActiveSheetIndex(intval($page));
				}
		   $objPHPExcel->getActiveSheet()->fromArray(array('Name',$emp['first_name'].' '.$emp['last_name'],'Desig',$emp['designation_name'],'HQ',$emp['headquater_name']), NULL, 'A'.$row);
		   $row+=2;
		   
		   //$objPHPExcel->getActiveSheet()->fromArray(array('Name of ABM',$this->getABM($emp['user_id']),'Name Of RBM',$this->getRBM($emp['user_id']),'Name Of ZBM',''), NULL, 'A'.$row);
		   $objPHPExcel->getActiveSheet()->fromArray(array('Month',date('F, Y',strtotime($emp['tour_date']))), NULL, 'A'.$row);
		   $row+=2;
		   foreach(array('A','B','C','D','E','F') as $columnid){
		    $objPHPExcel->getActiveSheet()->getColumnDimension($columnid)->setAutoSize(true);
		   }
		   $objPHPExcel->getActiveSheet()->getStyle('A5:F5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
		   
		   
		   $objPHPExcel->getActiveSheet()->fromArray(array('Date','Day','Place/Town','Patch','HQ','Work Plan With'), NULL, 'A'.$row);
		   $row++;
		   $monthofdays = cal_days_in_month(CAL_GREGORIAN,date('m',strtotime($emp['tour_date'])),date('Y',strtotime($emp['tour_date'])));
		   //print_r($monthofdays);die;
		  for($i=1;$i<=$monthofdays;$i++){
		   $tour_date = date('Y-m',strtotime($emp['tour_date'])).'-'.str_pad($i,2,0,STR_PAD_LEFT);
		   $select = $this->_db->select()
							   ->from(array('EE'=>'app_tourplan'),array('*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code','designation_id'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(""))
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=EE.location_id",array('GROUP_CONCAT(patch_name) AS patch_name'))
							    ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=PC.headquater_id",array('headquater_name'))
							   ->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array("city_name"))
							   ->where("EE.user_id='".$emp['user_id']."'")
							   ->where("tour_date='".$tour_date."'");
							    //echo $select->__toString();die;
			$record =  $this->getAdapter()->fetchRow($select);
			$patches = array_unique(explode(',',$record['patch_name']));
			$day = date('l',strtotime($tour_date));
			if($record['tour_date']!='' && $record['tour_date']!='0000-00-00' && $record['headquater_name']!=''){
			   //echo "<pre>";print_r($record);
			  $objPHPExcel->getActiveSheet()->fromArray(array($record['tour_date'],$day,$record['city_name'],implode(',',$patches),$record['headquater_name'],$this->getEmpname($record['be_visit'])."\n".$this->getEmpname($record['abm_visit'])."\n".$this->getEmpname($record['rbm_visit'])), NULL, 'A'.$row);
			  }elseif($record['tour_date']!='' && $record['tour_date']!='0000-00-00' && $record['headquater_name']==''){
			   //echo "<pre>";print_r($record);
			   $objPHPExcel->getActiveSheet()->fromArray(array($record['tour_date'],$day,'Non-Call','Non-Call','Non-Call','Non-Call'), NULL, 'A'.$row);
			   $objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.'F'.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
			  }else{
			     $notour = ($day=='Sunday')?'Sunday':'NA';
				 $color = ($day=='Sunday')?'008000':'FF0000';
			    $objPHPExcel->getActiveSheet()->fromArray(array($tour_date,$day,$notour,$notour,$notour,$notour), NULL, 'A'.$row);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$row.':'.'F'.$row)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB($color);
			  } 
			  $row++;
			   
			}
			
			$page++;
			//$objPHPExcel->getActiveSheet()->getStyle('A1:H'.$row)->getAlignment()->setWrapText(true); 
			$styleArray = array('borders'=>array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN)));
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.'F'.$row)->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->setAutoFilter('A5:F5');
			$objPHPExcel->getActiveSheet()->setTitle($emp['employee_code']);
		}
		header('Content-Type: application/xlsx');
		header('Content-Disposition: attachment;filename="TourPlan.xlsx"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		ob_end_clean();
		$objWriter->save('php://output'); 
		$objPHPExcel->disconnectWorksheets();
		unset($objPHPExcel);die;
	}
	
	public function RejectTP(){
	  $this->_db->delete('app_tourplan',"user_id='".$this->_getData['user_id']."' AND date_format(tour_date,'%Y-%m')='".$this->_getData['month']."'");
	}
	public function ApprovedTourPlan(){//print_r($this->_getData);die;
	  foreach($this->_getData['approval_id'] as $tpid){ //echo "user_id='".$this->_getData['user_id']."' AND tour_id IN(".$tpid.")";die;
	   $this->_db->update('app_tourplan',array('admin_approval'=>1),"user_id='".$this->_getData['user_id']."' AND tour_id IN(".$tpid.")");
	   }
	}
	
	public function getABM($user_id){
	   $select = $this->_db->select()
						   ->from(array('ED'=>'employee_personaldetail'),array('*'))
						   ->joininner(array('ED1'=>'employee_personaldetail'),"ED1.parent_id=ED.user_id",array())
						   ->where("ED1.user_id='".$user_id."' AND ED.designation_id=7");
							  //  echo $select->__toString();die;
			$records =  $this->getAdapter()->fetchRow($select);
			return $records['first_name'].' '.$records['last_name'];
	}
	public function getRBm($user_id){
	   $select = $this->_db->select()
					   ->from(array('ED'=>'employee_personaldetail'),array('*'))
					   ->joininner(array('ED1'=>'employee_personaldetail'),"ED1.parent_id=ED.user_id",array())
					   ->joininner(array('ED2'=>'employee_personaldetail'),"ED2.parent_id=ED1.user_id",array())
					   ->where("ED2.user_id='".$user_id."' AND ED.designation_id=6");
							  //  echo $select->__toString();die;
			$records =  $this->getAdapter()->fetchRow($select);
			return $records['first_name'].' '.$records['last_name'];
	}
	
 public function gettpvsActual(){
   $where = '1';
   $filter = '1';
    if($_SESSION['AdminDesignation']==8){
       $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
    }elseif($_SESSION['AdminDesignation']==7){
      $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
    }elseif($_SESSION['AdminDesignation']==6){
       $childs =  $this->getChilllds("ED");
      $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
    }elseif($_SESSION['AdminDesignation']==5){
      //$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
	  $childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
	  $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
    }
    
    //Filter With Date Range
   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
    $filter .= " AND DATE_FORMAT(EE.tour_date,'%Y-%m') BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
   }else{
     $filter .=" AND date_format(EE.tour_date,'%Y-%m') = date_format(CURDATE(),'%Y-%m')";
   }
   
    if($this->_getData['hedquater_id']>0){
      $filter .= " AND EL.headquater_id='".$this->_getData['hedquater_id']."'";
    }
    if($this->_getData['be_id'] !=''){
      $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['be_id'])."'";
    }elseif($this->_getData['abm_id']!=''){
     $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['abm_id'])."'";
    }elseif($this->_getData['rbm_id']!=''){
      $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['rbm_id'])."'";
    }elseif($this->_getData['zbm_id']!=''){
      $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['zbm_id'])."'";
    }
    $orderlimit = CommonFunction::OdrderByAndLimit($this->_getData,'EE.tour_date');
    
    $select = $this->_db->select()
          ->from(array('EE'=>'app_tourplan'),array('user_id'))
          ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array())
          //->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("EL.headquater_id"))
          ->joininner(array('DV'=>'app_doctor_visit'),"DV.user_id=EE.user_id AND DV.call_date=EE.tour_date",array())
          ->joinleft(array('CD'=>'crm_doctors'),"CD.doctor_id=DV.doctor_id",array(''))
          ->where($where)
          ->where($filter)
          //->where("date_format(EE.tour_date,'%Y-%m') = date_format(CURDATE(),'%Y-%m')")
          ->group("DV.user_id")
          ->group("CD.patch_id")
          ->group("DV.call_date");//echo $select->__toString();die;
     $total =  $this->getAdapter()->fetchAll($select);
     
        $select = $this->_db->select()
          ->from(array('EE'=>'app_tourplan'),array('*'))
          ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
          ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
		  ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=EE.location_id",array('patch_name'))
          ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=PC.headquater_id",array('headquater_name')) 
		  ->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array("city_name"))
          //->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
                   
          ->joininner(array('DV'=>'app_doctor_visit'),"DV.user_id=EE.user_id AND DV.call_date=EE.tour_date",array("doctor_id",'be_visit as abe_visit','abm_visit as aabm_visit','rbm_visit as arbm_visit'))
          ->joininner(array('CD'=>'crm_doctors'),"CD.doctor_id=DV.doctor_id",array(''))
          ->joininner(array('PC1'=>'patchcodes'),"PC1.patch_id=CD.patch_id",array('patch_name as actual_patch'))
		  ->joininner(array('HT1'=>'headquater'),"HT1.headquater_id=PC.headquater_id",array('headquater_name AS actual_hq'))
          ->joinleft(array('CT1'=>'city'),"CT1.city_id=PC1.city_id",array("city_name as actual_city"))
          ->where($where)
          ->where($filter)
          //->where("date_format(EE.tour_date,'%Y-%m') = date_format(CURDATE(),'%Y-%m')")
          ->group("DV.user_id")
          ->group("CD.patch_id")
          ->group("DV.call_date")
          ->order("EE.tour_date DESC")
          ->order("ED.first_name ASC")
          ->limit($orderlimit['Toshow'],$orderlimit['Offset']);
           //echo $select->__toString();die;
   $result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
   
   
  return array('Total'=>count($total),'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
 }
	
	public function getDesignationWiseUserLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			/*if ($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44) {
				$this->getParents($_SESSION['AdminLoginID']); //print_r($this->_parentIDs);die;
				$where = 'parent_id IN ('.implode(',',array_unique($this->_parentIDs)).')';
			}
			*/
			$query = $this->_db->select()
							->from('employee_personaldetail',array('user_id','first_name','last_name','employee_code'))
						->where($where)
							->where('designation_id=?',$data['designationID'])
							->where("user_status='1' AND delete_status='0'")
							->order('first_name','ASC'); 
			//echo $query->__toString();die;
			return $this->getAdapter()->fetchAll($query);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function ExporttpvsActual(){
     try{
   $totalRowData = $this->gettpvsActual();
   $totalRowData = $totalRowData['Records'];
   if(count($totalRowData)>0) {
    ini_set("memory_limit","512M");
    ini_set("max_execution_time",180);
    ob_end_clean();
    $objPHPExcel = new PHPExcel();
    // Write Sheet Header
    
    $objPHPExcel->getActiveSheet()->fromArray(array('Name',$totalRowData[0]['first_name'].' '.$totalRowData[0]['last_name'],'Desig.',$totalRowData[0]['designation_name'],'HQ',$totalRowData[0]['headquater_name']), NULL, 'A1');
    
    $objPHPExcel->getActiveSheet()->fromArray(array('Month',date('F, Y',strtotime($totalRowData[0]['tour_date']))), NULL, 'A2');
    
    $objPHPExcel->getActiveSheet()->fromArray(array('Date','Planned HQ','Patch/Town','Patch','Work Planned With','Actual HQ','Place/Town','Patch','Work With'), NULL, 'A4');    
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+4))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
    
    // Set title row bold
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
    
    // Setting Auto Width
    foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Setting Column Background Color
    
    $objPHPExcel->getActiveSheet()->getStyle('A4:I4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    
    // Setting Text Alignment Center
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    
    $reportRows = array();
    $matchs = array();
    foreach($totalRowData as $index=>$report)
    {  
         $data= array();
            $data[] = $report['tour_date'];
			$data[] = $report['headquater_name'];
      $data[] = $report['city_name'];
      $data[] = $report['patch_name']; 
      $data[] = $this->getEmpname($report['be_visit'])."\n".$this->getEmpname($report['abm_visit'])."\n".$this->getEmpname($report['rbm_visit']); 
      $data[] = $report['actual_hq'];
      $data[] = $report['actual_city']; 
      
      $data[] = $report['actual_patch']; 
      
      $data[] = $this->getEmpname($report['abe_visit'])."\n".$this->getEmpname($report['aabm_visit'])."\n".$this->getEmpname($report['arbm_visit']);      
      $matchs[] = ($report['city_name'] == $report['actual_city'] && $report['patch_name'] == $report['actual_patch'])? "match":"notmatch";
      
          $reportRows[] = $data;
    }
    
    // Write Row Data
    //$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A5');
    foreach($matchs as $key=>$match){
      $cordi =  $key+5;
             if($match != 'notmatch'){
          $objPHPExcel->getActiveSheet()->fromArray($reportRows[$key], NULL, 'A'.$cordi); 
       $objPHPExcel->getActiveSheet()->getStyle('A'.$cordi.':I'.$cordi)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('34A853');
       }else{
          $objPHPExcel->getActiveSheet()->fromArray($reportRows[$key], NULL, 'A'.$cordi); 
       } 
     } 
     //die;
    // Set autofilter
    // Always include the complete filter range!
    // Excel does support setting only the caption
    // row, but that's not a best practise...
    $objPHPExcel->getActiveSheet()->setAutoFilter('A4:G4');
    
    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle('TP VS Actual');
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
         
    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="tp_vs_actual.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    $objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
    //$objWriter->save('test.xlsx');  //THIS WORKS
    $objPHPExcel->disconnectWorksheets();
    unset($objPHPExcel);die;
   }
   else {
    $Header .=  "\" No Data Found!! \"".$_nxtcol;
   }
  }
  catch(Exception $e){ echo $e->getMessage();die;
     $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
  }
 }
	
	public function gettpsummary(){
  $filter = '1';
    $where = '1';
    if($_SESSION['AdminDesignation']==8){
       $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
    }elseif($_SESSION['AdminDesignation']==7){
      $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
    }elseif($_SESSION['AdminDesignation']==6){
       //$childs =  $this->getChilllds("ED");
      $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
    }elseif($_SESSION['AdminDesignation']==5){
      //$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
	  $childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
	  $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
    }//print_r($this->_getData);die;
      //Filter With Date Range
   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
    $filter .= " AND DATE_FORMAT(EE.tour_date,'%Y-%m') BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
   }
    
    if($this->_getData['hedquater_id']>0){
      $filter .= " AND EL.headquater_id='".$this->_getData['hedquater_id']."'";
    }
    if($this->_getData['be_id'] !=''){
      $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['be_id'])."'";
    }elseif($this->_getData['abm_id']!=''){
     $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['abm_id'])."'";
    }elseif($this->_getData['rbm_id']!=''){
      $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['rbm_id'])."'";
    }elseif($this->_getData['zbm_id']!=''){
      $filter .= " AND EE.user_id='".Class_Encryption::decode($this->_getData['zbm_id'])."'";
    }
    $orderlimit = CommonFunction::OdrderByAndLimit($this->_getData,'EE.tour_date');
    $select = $this->_db->select()
          ->from(array('EE'=>'app_tourplan'),array('user_id'))
          ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array())
          ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array())
          ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array())
          ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array())
          ->where($where)
          ->where($filter)
          ->where("DATE_FORMAT(EE.tour_date,'%Y-%m')<=DATE_FORMAT( NOW( ) + INTERVAL 1 
MONTH ,  '%Y-%m' ) ")
          ->group("EE.user_id")
          ->group("DATE_FORMAT(EE.tour_date,'%Y-%m')");
         //  echo $select->__toString();die;
   $total =  $this->getAdapter()->fetchAll($select);
     
        $select = $this->_db->select()
          ->from(array('EE'=>'app_tourplan'),array('*','GROUP_CONCAT(EE.tour_date) AS CNT','DATE_FORMAT(EE.tour_date,"%Y-%m") AS visit_month',''))
          ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','user_id','employee_code'))
          ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
          ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
          ->joininner(array('RG'=>'region'),"RG.region_id=EL.region_id",array("RG.region_name AS region"))
          ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
          ->where($where)
          ->where($filter)
          ->where("DATE_FORMAT(EE.tour_date,'%Y-%m')<=DATE_FORMAT( NOW( ) + INTERVAL 1 MONTH ,  '%Y-%m' ) ")
          ->group("EE.user_id")
          ->group("DATE_FORMAT(EE.tour_date,'%Y-%m')")
          ->order("EE.tour_date DESC");
         //  echo $select->__toString();die;
   $result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
                   
  return array('Total'=>count($total),'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
 }
	
	public function Exporttpsummary(){
    try{
   $totalRowData = $this->gettpsummary();
   $totalRowData = $totalRowData['Records'];
   if(count($totalRowData)>0) {
    ini_set("memory_limit","512M");
    ini_set("max_execution_time",180);
    ob_end_clean();
    $objPHPExcel = new PHPExcel();
    // Write Sheet Header
    $month = '';
    if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
     $month = $this->_getData['to_date'].' To '.$this->_getData['from_date'];
    
    }
        
    $region = array();
    $reportRows = array();
    foreach($totalRowData as $index=>$report)
    {
         $data= array();
            $data[] = $report['first_name'];
      $data[] = $report['designation_name'];
      $data[] = $report['headquater_name']; 
      $data[] = date('F, Y',strtotime($report['visit_month']));
      
      $data[] = count(array_unique(explode(',',$report['CNT'])));
      
      $data[] = count(array_unique(explode(',',$report['CNT']))) - ($report['ex_count']+$report['out_count']); 
      
      $data[] = $report['ex_count'];  
      $data[] = $report['out_count'];  
      $reportRows[] = $data;
      $region[] = $report['region'];   
    }
    $objPHPExcel->getActiveSheet()->fromArray(array('Region',implode(', ',array_unique($region)),'Period',$month), NULL, 'A1');
    $objPHPExcel->getActiveSheet()->fromArray(array('Employee Name','Designation','HQ','Month','No Of days work planned','HQ','EX','Out'), NULL, 'A4');    
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+4))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
    
    // Set title row bold
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
    
    // Setting Auto Width
    foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
     $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
    }
    
    // Setting Column Background Color
    
    $objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
    
    // Setting Text Alignment Center
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    // Write Row Data
    $objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A5');
     
    // Set autofilter
    // Always include the complete filter range!
    // Excel does support setting only the caption
    // row, but that's not a best practise...
    $objPHPExcel->getActiveSheet()->setAutoFilter('A4:G4');
    
    // Rename sheet
    $objPHPExcel->getActiveSheet()->setTitle('TP Summary');
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
         
    // Redirect output to a client’s web browser (Excel5)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="tp_summary.xls"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    $objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
    //$objWriter->save('test.xlsx');  //THIS WORKS
    $objPHPExcel->disconnectWorksheets();
    unset($objPHPExcel);die;
   }
   else {
    $Header .=  "\" No Data Found!! \"".$_nxtcol;
   }
  }
  catch(Exception $e){ 
     $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
  }
 }
	function getlastsixmonth(){
      $months = array();
     $months[] = date("Y-m");
     for($i=1;$i<=6;$i++){
     $months[] = date("Y-m", strtotime("-".$i." months", strtotime('now')));
      }
   
      return $months;
     }
	
		   public function getChildIncludeLogin(){
   		  
   
		   $select = $this->_db->select()
								->from(array('EL'=>'emp_locations'),array('*'))
								->where("user_id='".$_SESSION['AdminLoginID']."'");
								//print_r($select->__toString());die;
		   $result = $this->getAdapter()->fetchRow($select);
		   $where = "EPD.user_status='1' AND EPD.delete_status='0'";
			if($_SESSION['AdminDesignation']==8){
			    
				$where .= " AND EL.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND EL.area_id='".$result['area_id']."'";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$where .= " AND EL.region_id='".$result['region_id']."'";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND EL.zone_id='".$result['zone_id']."'";
			}
			$select = $this->_db->select()
								->from(array('EL'=>'emp_locations'),array('user_id'))
								->joininner(array('EPD'=>'employee_personaldetail'),"EL.user_id=EPD.user_id",array('*'))
								->where($where)
								->order("EPD.first_name");
								//print_r($select->__toString());die;
			 $result = $this->getAdapter()->fetchAll($select);
			 $res_id = array();
			 foreach($result as $value){
				$res_id[$value['user_id']] = $value['first_name'].' '.$value['last_name'].' - '.$value['employee_code'];
			 }
			  
			  return $res_id;
	}	
	
	public function exportcurrenttourdetil()
	{	
     	try{
		   $totalRowData = $this->getTourDetail();
		   //$totalRowData = count($totalRowData);
		   if(count($totalRowData)>0) {
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel();
			//$filterUser = $this->getFilterDetail($this->_getData);
			// Write Sheet Header			
			$objPHPExcel->getActiveSheet()->fromArray(array('HQ','Patch Name','BE','RBM','ABM','ZBM','Approved By','Call Date','Call Type'), NULL, 'A1');    
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
			
			// Set title row bold
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			
			// Setting Auto Width
			foreach(range('A1',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
			 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			// Setting Column Background Color
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
			
			// Setting Text Alignment Center
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$reportRows = array();
			foreach($totalRowData as $index=>$report)
			{  
			  $data= array();
			  $data[] = $report['headquater_name'];
			  $data[] = $report['patch_name'];
			  $data[] = $this->getEmpname($report['be_visit']);
			  $data[] = $this->getEmpname($report['abm_visit']);
			  $data[] = $this->getEmpname($report['rbm_visit']);
			  $data[] = $this->getEmpname($report['zbm_visit']); 
			  $data[] = $this->getEmpname($report['accepte_by']); 
			  $data[] = $report['tour_date']; 
			  $data[] = ($report['call_type']=='1')?"Non-Call":'call';
			  
				  $reportRows[] = $data;
			}
			
			// Write Row Data
			$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
			 
			 //die;
			// Set autofilter
			// Always include the complete filter range!
			// Excel does support setting only the caption
			// row, but that's not a best practise...
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:I1');
			
			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('CurrentTourDetail');
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
				 
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="currenttourdetail.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
			//$objWriter->save('test.xlsx');  //THIS WORKS
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);die;
		   }
		   else {
			$Header .=  "\" No Data Found!! \"".$_nxtcol;
		   }
		  }
		  catch(Exception $e){ echo $e->getMessage();die;
			 $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		  }

 
	}	


	public function exportnexttourdetil()
	{	
     	try{
		   $totalRowData = $this->getNextTourPlansDetail();
		   //$totalRowData = count($totalRowData);
		   if(count($totalRowData)>0) {
			ini_set("memory_limit","512M");
			ini_set("max_execution_time",180);
			ob_end_clean();
			$objPHPExcel = new PHPExcel();
			// Write Sheet Header			
			$objPHPExcel->getActiveSheet()->fromArray(array('HQ','Patch Name','BE','RBM','ABM','ZBM','Approved By','Call Date','Call Type'), NULL, 'A1');    
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
			
			// Set title row bold
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
			
			// Setting Auto Width
			foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
			 $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
			}
			
			// Setting Column Background Color
			
			$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
			
			// Setting Text Alignment Center
			$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			
			$reportRows = array();
			foreach($totalRowData as $index=>$report)
			{  
			  $data= array();
			  $data[] = $report['headquater_name'];
			  $data[] = $report['patch_name'];
			  $data[] = $this->getEmpname($report['be_visit']);
			  $data[] = $this->getEmpname($report['abm_visit']);
			  $data[] = $this->getEmpname($report['rbm_visit']);
			  $data[] = $this->getEmpname($report['zbm_visit']); 
			  $data[] = $this->getEmpname($report['accepte_by']); 
			  $data[] = $report['tour_date']; 
			  $data[] = ($report['call_type']=='0')?'Call Activity':'Non-Call Activity';
			  
				  $reportRows[] = $data;
			}
			
			// Write Row Data
			$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
			 
			 //die;
			// Set autofilter
			// Always include the complete filter range!
			// Excel does support setting only the caption
			// row, but that's not a best practise...
			//$objPHPExcel->getActiveSheet()->setAutoFilter('A1:G1');
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:I1');
			// Rename sheet
			$objPHPExcel->getActiveSheet()->setTitle('NextTourDetail');
			
			// Set active sheet index to the first sheet, so Excel opens this as the first sheet
			$objPHPExcel->setActiveSheetIndex(0);
				 
			// Redirect output to a client’s web browser (Excel5)
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="nexttourplandetail.xls"');
			header('Cache-Control: max-age=0');
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			ob_end_clean();
			$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
			//$objWriter->save('test.xlsx');  //THIS WORKS
			$objPHPExcel->disconnectWorksheets();
			unset($objPHPExcel);die;
		   }
		   else {
			$Header .=  "\" No Data Found!! \"".$_nxtcol;
		   }
		  }
		  catch(Exception $e){ echo $e->getMessage();die;
			 $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		  }

 
	}

}

?>