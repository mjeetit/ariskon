<?php
class ReportingManager extends Zend_Custom
{
	private $_headquarters 	= array();
	
	public function getDoctorVisit($data)
	{
		try {
			$where = "ED.delete_status='0'"; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				//$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
				$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
				//$childs =  $this->getChilllds("ED");
				//$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			
			$filterparam = '';
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EL.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}elseif(!empty($data['year']) && !empty($data['month'])) {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			else {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".date('Y-m')."'";
			}
			//Filter With Doctor Type
			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'ED.employee_code');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_doctor_visit'),array('(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg'))
							->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam)
						    ->group("EE.user_id")
						    ->group("DATE_FORMAT(EE.call_date,'%Y-%m')".$having)
							->order("ED.employee_code"); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg"))
							   ->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->where($where.$filterparam)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.call_date,'%Y-%m')".$having)
							   //->having("Call_Avg BETWEEN '0' AND '10'")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getDoctorVisitReport($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			/*if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}*/
			
			$filterparam = '';			
			// Filter with perticular user
			$visitorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
			if($visitorID>0){
				$filterparam .= " AND EE.user_id='".$visitorID."'";
			}
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Patch Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND PC.patch_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$filterparam .= " AND EE.be_visit='".Class_Encryption::decode($data['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$filterparam .= " AND EE.abm_visit='".Class_Encryption::decode($data['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$filterparam .= " AND EE.rbm_visit='".Class_Encryption::decode($data['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$filterparam .= " AND EE.zbm_visit='".Class_Encryption::decode($data['token6'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			
			//Filter With Product Data
			$productID = (isset($data['ptoken']) && !empty($data['ptoken'])) ? Class_Encryption::decode($data['ptoken']) : 0;
			if(!empty($data['ptoken'])){
				$filterparam .= " AND (EE.product1='".$productID."' OR EE.product2='".$productID."' OR EE.product3='".$productID."' OR EE.product4='".$productID."' OR EE.product5='".$productID."')";
			}
			//Filter With Doctor Type
			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.call_date');
			
			$countQuery = $this->_db->select()
								->from(array('EE'=>'app_doctor_visit'),array('COUNT(1) AS CNT'))
								->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')
								->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array("city_name"))
							   	/*->joinleft(array('PT1'=>'crm_products'),"PT1.product_id=EE.product1",'')
							   	->joinleft(array('PT2'=>'crm_products'),"PT2.product_id=EE.product2",'')
							   	->joinleft(array('PT3'=>'crm_products'),"PT3.product_id=EE.product3",'')
							   	->joinleft(array('PT4'=>'crm_products'),"PT4.product_id=EE.product4",'')
							   	->joinleft(array('PT5'=>'crm_products'),"PT5.product_id=EE.product5",'')*/
							   	->joinleft(array('AT'=>'app_activities'),"AT.activity_id=EE.activities",'')
						    	->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('EE.visit_id','DD.doctor_name','EE.doctor_id','PC.patch_name','DD.patch_id',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit",'EE.zbm_visit',"CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit",'EE.rbm_visit',"CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit",'EE.abm_visit',"CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.be_visit',"EE.call_date",'EE.call_time','HT.headquater_name','EE.date_added'))
							   ->joinleft(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')
								->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array("city_name"))
							  /* ->joinleft(array('PT1'=>'crm_products'),"PT1.product_id=EE.product1",'')
							   ->joinleft(array('PT2'=>'crm_products'),"PT2.product_id=EE.product2",'')
							   ->joinleft(array('PT3'=>'crm_products'),"PT3.product_id=EE.product3",'')
							   ->joinleft(array('PT4'=>'crm_products'),"PT4.product_id=EE.product4",'')
							   ->joinleft(array('PT5'=>'crm_products'),"PT5.product_id=EE.product5",'')*/
							   ->joinleft(array('AT'=>'app_activities'),"AT.activity_id=EE.activities",'')
							   ->where($where.$filterparam)
							   ->order("EE.call_date DESC")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function filterDoctorReport($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			/*if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}*/
			
			$filterparam = '';
			// Filter with perticular user
			$visitorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
			if($visitorID>0){
				$filterparam .= " AND EE.user_id='".$visitorID."'";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			//Filter With Doctor Type
			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
			
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('DD.doctor_name','EE.doctor_id','PC.patch_name','DD.patch_id',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit",'EE.zbm_visit',"CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit",'EE.rbm_visit',"CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit",'EE.abm_visit',"CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.be_visit','PT1.product_name AS pname1','EE.product1','PT2.product_name AS pname2','EE.product2','PT3.product_name AS pname3','EE.product3','PT4.product_name AS pname4','EE.product4','PT5.product_name AS pname5','EE.product5','AT.activity_name','EE.activities'))
							   ->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							   ->joinleft(array('PT1'=>'crm_products'),"PT1.product_id=EE.product1",'')
							   ->joinleft(array('PT2'=>'crm_products'),"PT2.product_id=EE.product2",'')
							   ->joinleft(array('PT3'=>'crm_products'),"PT3.product_id=EE.product3",'')
							   ->joinleft(array('PT4'=>'crm_products'),"PT4.product_id=EE.product4",'')
							   ->joinleft(array('PT5'=>'crm_products'),"PT5.product_id=EE.product5",'')
							   ->joinleft(array('AT'=>'app_activities'),"AT.activity_id=EE.activities",'')
							   ->where($where.$filterparam); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return $result;
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	 
	public function getChemistVisist($data)
	{
		try {
			$where = "ED.delete_status='0'"; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				//$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
				$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
			}
			
			$filterparam = '';
			//Filter With Chemist Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.chemist_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EL.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.date_added) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}elseif(!empty($data['year']) && !empty($data['month'])) {
				$filterparam .= " AND DATE_FORMAT(EE.date_added,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}else{
			    $filterparam .= " AND DATE_FORMAT(EE.date_added,'%Y-%m')='".date('Y-m')."'";
			}
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.date_added');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_chemist_visit'),array('(COUNT(1)/COUNT(DISTINCT(EE.date_added))) AS Call_Avg'))
							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam)
						    ->group("EE.user_id")
						    ->group("DATE_FORMAT(EE.date_added,'%Y-%m')".$having); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_chemist_visit'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.date_added,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.date_added)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.date_added))) AS Call_Avg"))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->where($where.$filterparam)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.date_added,'%Y-%m')".$having)
							   //->having("Call_Avg BETWEEN '0' AND '10'")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch (Exception $e) {
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	 }
	
	public function getChemistVisitReport($data)
	{
		try {
			$where = "1"; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				//$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
				$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
			}
			
			$filterparam = '';
			// Filter with perticular user
			$visitorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
			if($visitorID>0){
				$filterparam .= " AND EE.user_id='".$visitorID."'";
			}
			//Filter With Chemist Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.chemist_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.date_added) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.date_added,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.date_added');
		
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_chemist_visit'),array('COUNT(1) AS CNT'))
							->joininner(array('DD'=>'crm_chemists'),"DD.chemist_id=EE.chemist_id",'')
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_chemist_visit'),array('DD.chemist_name','PC.patch_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit","CONCAT(IF(PT1.product_name!='',CONCAT(PT1.product_name,' : ',EE.unit1),''),' | ',IF(PT2.product_name!='',CONCAT(PT2.product_name,' : ',EE.unit2),''),' | ',IF(PT3.product_name!='',CONCAT(PT3.product_name,' : ',EE.unit3),''),' | ',IF(PT4.product_name!='',CONCAT(PT4.product_name,' : ',EE.unit4),''),' | ',IF(PT5.product_name!='',CONCAT(PT5.product_name,' : ',EE.unit5),'')) AS productunit","EE.date_added"))
							   ->joininner(array('DD'=>'crm_chemists'),"DD.chemist_id=EE.chemist_id",'')
							   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							   ->joinleft(array('PT1'=>'crm_products'),"PT1.product_id=EE.product1",'')
							   ->joinleft(array('PT2'=>'crm_products'),"PT2.product_id=EE.product2",'')
							   ->joinleft(array('PT3'=>'crm_products'),"PT3.product_id=EE.product3",'')
							   ->joinleft(array('PT4'=>'crm_products'),"PT4.product_id=EE.product4",'')
							   ->joinleft(array('PT5'=>'crm_products'),"PT5.product_id=EE.product5",'')
							   ->where($where.$filterparam)
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	 
	public function getStockistVisist($data)
	{
		try {
			$where = "ED.delete_status='0'"; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				//$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
				$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
			}
			
			$filterparam = '';
			//Filter With Stockist Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.stockist_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EL.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.date_added) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			if(!empty($data['year']) && !empty($data['month'])) {
				$filterparam .= " AND DATE_FORMAT(EE.date_added,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.date_added');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_stockist_visit'),array('(COUNT(1)/COUNT(DISTINCT(EE.date_added))) AS Call_Avg'))
							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam)
						    ->group("EE.user_id")
						    ->group("DATE_FORMAT(EE.date_added,'%Y-%m')".$having); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.date_added,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.date_added)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.date_added))) AS Call_Avg"))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->where($where.$filterparam)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.date_added,'%Y-%m')".$having)
							   //->having("Call_Avg BETWEEN '0' AND '10'")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch (Exception $e) {
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	 }
	 
	public function getStockistVisitReport12($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			
			$filterparam = '';
			// Filter with perticular user
			$visitorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
			if($visitorID>0){
				$filterparam .= " AND EE.user_id='".$visitorID."'";
			}
			//Filter With Stockist Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.stockist_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.date_added) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.date_added,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.date_added');
		
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_stockist_visit'),array('COUNT(1) AS CNT'))
							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
						    ->where($where.$filterparam); print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array("ED.employee_code","CONCAT(ED.first_name,' ',ED.last_name) AS Emp","DT.designation_name","HT.headquater_name",'DD.stockist_name',"EE.date_added"))
							   ->joininner(array('DD'=>'crm_stockists'),"DD.stockist_id=EE.stockist_id",'')
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->where($where.$filterparam)
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getStockistVisitReport($data)
	{
		try {
			$where = "1"; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				//$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
				$childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
			}
			
			$filterparam = '';
			// Filter with perticular user
			$visitorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
			if($visitorID>0){
				$filterparam .= " AND EE.user_id='".$visitorID."'";
			}
			//Filter With Stockist Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.stockist_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.date_added) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.date_added,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.date_added');
		
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_stockist_visit'),array('COUNT(1) AS CNT'))
							->joininner(array('DD'=>'crm_stockists'),"DD.stockist_id=EE.stockist_id",'')
							   //->joinleft(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')
							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array('DD.stockist_name','HT.headquater_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit","EE.date_added"))
							   ->joininner(array('DD'=>'crm_stockists'),"DD.stockist_id=EE.stockist_id",'')
							   ->joinleft(array('EL'=>'emp_locations'),"EL.user_id=EE.user_id",'')
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							   ->where($where.$filterparam)
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getReportee($data)
	{
		try {
			$select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array("ED.employee_code","CONCAT(ED.first_name,' ',ED.last_name) AS Emp","DT.designation_name","HT.headquater_name"))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   ->where("ED.delete_status='0' AND ED.user_id=".$data); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchRow($select); //echo "<pre>";print_r($result);die;
			return $result;
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
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
	 
	public function productsdetail($data)
	{
	   $where = array();
	   if($data['product1']>0){
	      $where[] = $data['product1'];
	   }
	   if($data['product2']>0){
	      $where[] = $data['product2'];
	   }
	   if($data['product3']>0){
	      $where[] = $data['product3'];
	   }
	   if($data['product4']>0){
	      $where[] = $data['product4'];
	   }
	   if($data['product5']>0){
	      $where[] = $data['product5'];
	   }	
	    $productlist = array();
	   if(!empty($where)){  
	     foreach($where as $product_id){
			$select = $this->_db->select()
								   ->from(array('CP'=>'crm_products'),array('product_name','product_code'))
								   ->where("product_id='".$product_id."'");
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchRow($select);
    		
			   $productlist[] = $result['product_code'].'-'.$result['product_name'];
			}	
			return implode('<br>',$productlist);	  
		}
	 }
	
	public function visitWithdetail($data)
	{
	   $visitwith = array();
	   if($data['be_visit']>0){
		  $visitwith[] = $data['be_visit'];
		}
		if($data['abm_visit']>0){
		  $visitwith[] = $data['abm_visit'];
		}
		if($data['zbm_visit']>0){
		  $visitwith[] = $data['zbm_visit'];
		}
		if($data['rbm_visit']>0){
		  $visitwith[] = $data['rbm_visit'];
		}
		$visitusers = array();
		if(!empty($visitwith)){
	     $select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array('first_name','last_name'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_code'))
							   ->where("ED.user_id IN(".implode(',',$visitwith).")");
		$result =  $this->getAdapter()->fetchAll($select);
			foreach($result as $employees){
			  $visitusers[] = $employees['first_name'].'('.$employees['designation_code'].')';
			}
		}
		
	    return implode('<br>',$visitusers);
	 }
	 
	public function getStockistVisistOld()
	{
	      $where = '1';
		  if($_SESSION['AdminDesignation']==8){
		     $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
		  }elseif($_SESSION['AdminDesignation']==7){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }elseif($_SESSION['AdminDesignation']==6){
		     $childs =  $this->getChilllds("ED");
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
		  }elseif($_SESSION['AdminDesignation']==5){
		    $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
		  }
		 $select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.date_added,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where($where)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.date_added,'%Y-%m')")
							   ->order("EE.date_added DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		return $result;	
	 }
	
	public function productunitdetail($data)
	{
	   $where = array();
	   if($data['product1']>0){
	      $where[] = $data['product1'];
	   }
	   if($data['product2']>0){
	      $where[] = $data['product2'];
	   }
	   if($data['product3']>0){
	      $where[] = $data['product3'];
	   }
	   if($data['product4']>0){
	      $where[] = $data['product4'];
	   }
	   if($data['product5']>0){
	      $where[] = $data['product5'];
	   }	
	    $productlist = array();
	   if(!empty($where)){  
	     $i=1;
	     foreach($where as $product_id){
			$select = $this->_db->select()
								   ->from(array('CP'=>'crm_products'),array('product_name','product_code'))
								   ->where("product_id='".$product_id."'");
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchRow($select);
    		
			   $productlist[] = $result['product_code'].'-'.$result['product_name'].'('.$data['unit'.$i].')';
			$i++;
			}	
			return implode('<br>',$productlist);	  
		}
	 }
	 
	public function getStockistVisist1()
	{
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_stockist_visit'),array('*'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','parent_id'))
							   ->joininner(array('ST'=>'crm_doctor'),"ST.doctor_id=EE.stockist_id",array('doctor_name as stockist_name'));
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }
	 
	public function getHeadquater()
	{
	    $select = $this->_db->select()
							   ->from(array('EE'=>'headquater'),array('*'));
								   // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }
	 
	public function getDoctorVisitDetail($data)
	{
	    $filter = "";
	     if(!empty($data['patch_id'])){
		     $filter = " AND PT.patch_id='".$data['patch_id']."'";
		 }
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('*'))
							   ->joininner(array('CD'=>'crm_doctors'),"CD.doctor_id=EE.doctor_id",array('doctor_name'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CD.patch_id",array('patch_name'))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where("EE.user_id='".$data['user_id']."' AND ED.delete_status='0'".$filter)
							   ->order("EE.call_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }
	 
	 public function getChemistVisitDetail($data)
	{
	    $filter = "ED.delete_status='0'";
	     if(!empty($data['patch_id'])){
		     $filter = " AND PT.patch_id='".$data['patch_id']."'";
		 }
	       $select = $this->_db->select()
							   ->from(array('EE'=>'app_chemist_visit'),array('*','date_added as call_date'))
							   ->joininner(array('CD'=>'crm_chemists'),"CD.chemist_id=EE.chemist_id",array('chemist_name as doctor_name'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CD.patch_id",array('patch_name'))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->where("EE.user_id='".$data['user_id']."'".$filter)
							   ->order("EE.date_added DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	 }
	
	public function getTableData($data=array())
	{
		try
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
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	 
	public function saveData($data=array())
	{
		try
		{
			$tableName = (isset($data['tableName']) && !empty($data['tableName'])) ? trim($data['tableName']) : '';
			$tableData = (isset($data['tableData']) && count($data['tableName'])>0) ? $data['tableData'] : array();
			
			if(!empty($tableName) && count($tableData)>0) {
				return ($this->_db->insert($tableName,array_filter($tableData))) ? $this->_db->lastInsertId() : 0;
			}
			else {
				return 0;
			}
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function updateTableData($data=array())
	{
		try
		{
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
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getAssignGift($data=array())
	{
		try
		{
		  $giftID = (isset($data['giftToken']) && !empty($data['giftToken'])) ? trim($data['giftToken']) : '0';
		  $select = $this->_db->select()
							->from(array('AG'=>'app_gift_assigned'),array('AG.assigned_quantity','AG.valid_from','AG.valid_to'))
							->joininner(array('GT'=>'app_gifts'),"GT.gift_id=AG.gift_id",array('GT.gift_name'))
							->joininner(array('UT'=>'employee_personaldetail'),"UT.user_id=AG.user_id",array('empName'=>"CONCAT(UT.first_name,' ',UT.last_name)"))
							->where('AG.gift_id='.$giftID)
							->order("AG.assigned_date DESC"); //echo $select->__toString();die;
			$result = $this->getAdapter()->fetchAll($select);
			return $result;
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function getDesignationWiseUserLists($data=array())
	{
		try {
			$where = 1; //print_r($_SESSION);die;
			if ($_SESSION['AdminLevelID'] != 1) { //echo "<pre>";print_r($_SESSION);die;_headquarters
				$this->getParents($_SESSION['AdminLoginID']); //print_r($this->_parentIDs);die;
				$where = 'parent_id IN ('.implode(',',array_unique($this->_parentIDs)).')';
			}
			
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
	
	public function getPatchlist($user_id)
	{
	      $select = $this->_db->select()
							   ->from(array('ED'=>'employee_personaldetail'),array(''))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array(""))
							   ->joininner(array('PT'=>'patchcodes'),"EL.headquater_id=PT.headquater_id",array('patch_id','patch_name'))
							   ->where("ED.user_id='".$user_id."'")
							   ->order("PT.patch_name ASC");
							 // echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			return $result;
	}
	
	public function daybyCallCount()
	{
	    $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.call_date,"%Y-%m") AS visit_month'))
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))
							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))
							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.call_date,'%Y-%m')")
							   ->order("EE.call_date DESC");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select); 
	}
	
	public function productPromoted($visit_id)
	{
	   		$select = $this->_db->select()
							   ->from(array('PP'=>'app_doctorvisit_product'),array('*'))
							   ->joininner(array('P'=>'crm_products'),"P.product_id=PP.product",array('product_name'))
							   ->where("PP.visit_id=".$visit_id."");
							  //  echo $select->__toString();die;
			$result =  $this->getAdapter()->fetchAll($select);
			$prodstring = '';
			foreach($result as $products){
			  $prodstring .= $products['product_name'].':'.$products['unit'].'|';
			}
		 return substr($prodstring,0,-1);	
	}
	
	public function ExportDoctorvisit($data)
	{
		$filterparam = '';			
		// Filter with perticular user
		$visitorID = (isset($data['token'])) ? Class_Encryption::decode($data['token']) : 0;
		if($visitorID>0){
			$filterparam .= " AND EE.user_id='".$visitorID."'";
		}
		//Filter With Doctor Data
		if(!empty($data['token1'])){
			$filterparam .= " AND EE.doctor_id='".Class_Encryption::decode($data['token1'])."'";
		}
		$where = '1';
		//Filter With Patch Data
		if(!empty($data['token2'])){
			
			$filterparam .= " AND PC.patch_id='".Class_Encryption::decode($data['token2'])."'";
		}
		//Filter With BE Data
		if(!empty($data['token3'])){
			$filterparam .= " AND EE.be_visit='".Class_Encryption::decode($data['token3'])."'";
		}
		//Filter With ABM Data
		if(!empty($data['token4'])){
			$filterparam .= " AND EE.abm_visit='".Class_Encryption::decode($data['token4'])."'";
		}
		//Filter With RBM Data
		if(!empty($data['token5'])){
			$filterparam .= " AND EE.rbm_visit='".Class_Encryption::decode($data['token5'])."'";
		}
		//Filter With ZBM Data
		if(!empty($data['token6'])){
			$filterparam .= " AND EE.zbm_visit='".Class_Encryption::decode($data['token6'])."'";
		}
		//Filter With Date Range
		if(!empty($data['from_date']) && !empty($data['to_date'])){
			$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
		}
		if(!empty($data['monthrange'])) {
			$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";
		}
		//Filter With Product Data
		$productID = (isset($data['ptoken']) && !empty($data['ptoken'])) ? Class_Encryption::decode($data['ptoken']) : 0;
		if(!empty($data['ptoken'])){
			//$filterparam .= " AND (EE.product1='".$productID."' OR EE.product2='".$productID."' OR EE.product3='".$productID."' OR EE.product4='".$productID."' OR EE.product5='".$productID."')";
		}
		//Filter With Doctor Type
		$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
		//Filter with Activity
		$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
		$select = $this->_db->select()
						   ->from(array('EE'=>'app_doctor_visit'),array("CONCAT(UD.first_name,' ',UD.last_name) AS username",'UD.employee_code','EE.visit_id','DD.doctor_name','EE.doctor_id','PC.patch_name','DD.patch_id',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit",'EE.zbm_visit',"CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit",'EE.rbm_visit',"CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit",'EE.abm_visit',"CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.be_visit',"EE.call_date",'EE.call_time','HT.headquater_name'))
						   ->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
						   ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
						   ->joinleft(array('UD'=>'employee_personaldetail'),"UD.user_id=EE.user_id",'')
						   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
						   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
						   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
						   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
						   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')
						   ->joinleft(array('AT'=>'app_activities'),"AT.activity_id=EE.activities",'')
						    ->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array("city_name"))
						   ->where($where.$filterparam)
						   ->order("EE.call_date DESC");  //print_r($select->__toString());die;
		$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
		$_nxtcol   = "\t";
		$_nxtrow  = "\n";
		$Header .= "\"Employee Name \"".$_nxtcol.
					"\"Employee Code\"".$_nxtcol.
					"\"Doctor Name \"".$_nxtcol.
					"\"Patch Name \"".$_nxtcol.
					"\"City\"".$_nxtcol.
					"\"HQ \"".$_nxtcol.
					"\"Visit With ZBM \"".$_nxtcol.
					"\"Visit With RBM \"".$_nxtcol.
					"\"Visit With ABM \"".$_nxtcol.
					"\"Visit With BE \"".$_nxtcol.
					"\"Product Promoted \"".$_nxtcol.
					"\"Call Date \"".$_nxtcol.
				"\"Call Time\"".$_nxtrow.$_nxtrow;
		foreach($result as $row){
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['username']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['employee_code']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['doctor_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['patch_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['city_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['headquater_name']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['zbmvisit']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['rbmvisit']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['abmvisit']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['bevisit']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$this->productPromoted($row['visit_id'])) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['call_date']) . "\"" . $_nxtcol;
			$Header .= "\"" . str_replace( "\"", "\"\"",$row['call_time']) . "\"" . $_nxtcol;
			$Header .="\n";
		}
		header("Content-type: application/xls");
		header("Content-Disposition: attachment; filename=DoctorVisitReport.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		echo $Header;
		exit();
	}
	
	/**
	 * Export All Doctor Visit Data
	 * Function : ExportDoctorVisit()
	 * This function return doctor visit data sheet.
	 **/
	public function ExportDoctorSummaryVisit($headers=array(),$filterData=array())
	{
		try{
			$totalRowData = $filterData['Records'];
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Write Sheet Header
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A1');
				
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);
				unset($styleArray);
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				foreach($totalRowData as $index=>$row)
				{
					$reportRows[] = array($row['employee_code'],$row['Emp'],$row['designation_name'],$row['headquater_name'],number_format($row['Call_Avg'],2),$row['CNT'],date('M-Y',strtotime($row['visit_month'])));					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Summary Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="doctor_summary_report.xls"');
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
	 * Get All Doctor Visit Frequency Report
	 * Function : getDoctorVisitFrequency()
	 * This function return doctor visit frequency data.
	 **/
	public function getDoctorVisitFrequency($data)
	{
		try {
			$where = "1"; 
			
			$filterparam = '';
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND EE.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND DD.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				//$where = '1';$this->_headquarters = array();
				$filterparam = " AND UT.user_id= '".Class_Encryption::decode($data['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($data['token4']) && empty($data['token3'])){
				//$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				//$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam = " AND UT.user_id= '".Class_Encryption::decode($data['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
				//$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				//$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam .= " AND UT.user_id= '".Class_Encryption::decode($data['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
				//$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				//$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
				$filterparam .= " AND UT.user_id= '".Class_Encryption::decode($data['token6'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}elseif(!empty($data['year']) && !empty($data['month']) && empty($data['from_date']) && empty($data['to_date'])) {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			else if(empty($data['from_date']) && empty($data['to_date'])){
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".date('Y-m')."'";
			}
			//Filter With Doctor Type
			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			if($_SESSION['AdminLevelID'] != 1){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND EL.headquater_id IN(".$value.")";
		   }
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'DD.doctor_name');
			
			$countQuery = $this->_db->select()
								->from(array('EE'=>'app_doctor_visit'),array('UT.user_id'))
								->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
								->joinleft(array('UT'=>'employee_personaldetail'),"EE.user_id=UT.user_id",array(''))
								->joininner(array('EL'=>'emp_locations'),"EL.user_id=EE.user_id",'')
						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   	->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')
							   	->where($where.$filterparam)
								->group("DD.doctor_id"); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   //echo '<pre>';print_r($orderlimit);die;
			$limit = $orderlimit['Toshow'];
			$limit1 = $orderlimit['Offset'];
			if(isset($data['exportVisit']) && strtoupper(str_replace(' ','_',$data['exportVisit'])) == 'EXPORT_IN_EXCEL') {
				$limit = '';
				$limit1 = '';
			}
			
			$select = $this->_db->select()
							   	->from(array('EE'=>'app_doctor_visit'),array('DD.doctor_name','DD.speciality','DD.class','CA.activity_name',"DATE_FORMAT(EE.call_date,'%M, %Y') AS visit_month","GROUP_CONCAT(DISTINCT DATE_FORMAT(EE.call_date,'%d') ORDER BY DATE_FORMAT(EE.call_date,'%d') ASC) AS Days",'EE.doctor_id',"GROUP_CONCAT(DISTINCT EE.call_date SEPARATOR ', ') AS period",'UT.employee_code'))
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=EE.user_id",'')
						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",array('headquater_name'))
								->joinleft(array('EHT'=>'headquater'),"EHT.headquater_id=EL.headquater_id",array('headquater_name AS emp_hq'))
								->joininner(array('UT'=>'employee_personaldetail'),"EE.user_id=UT.user_id",array('CONCAT(UT.first_name," ",UT.last_name) AS name','user_id','designation_id'))
								->joinleft(array('ABM'=>'employee_personaldetail'),"((ABM.user_id=UT.parent_id OR ABM.user_id=UT.user_id) AND ABM.designation_id=7)",array('CONCAT(ABM.first_name," ",ABM.last_name) AS ABM_name'))
								->joinleft(array('RBM'=>'employee_personaldetail'),"((RBM.user_id=ABM.parent_id OR RBM.user_id=UT.parent_id) AND RBM.designation_id=6)",array('CONCAT(RBM.first_name," ",RBM.last_name) AS RBM_name'))
								->joinleft(array('CT'=>'city'),"HT.headquater_id=CT.headquater_id",array('city_name'))
								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')
								->where("UT.delete_status='0'")
							   	->where($where.$filterparam)
							   	->group("EE.doctor_id")
								->order("DATE_FORMAT(EE.call_date,'%M, %Y')")
							   	->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   	->limit($limit,$limit1); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>count($total),'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  $e->getMessage(); 
		}
	}
	
	/**
	 * Export All Doctor Visit Frequency Data
	 * Function : ExportDoctorVisitFrequency()
	 * This function return doctor visit data sheet.
	 **/
	public function ExportDoctorVisitFrequency($headers=array(),$filterData=array(),$filters)
	{
		try{
			$totalRowData = $filterData['Records'];
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				$monthsdata = array();
				$months = array();
				
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:H'.(count($totalRowData)+1))
												->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$reportRows = array();
				$objPHPExcel->getActiveSheet()->fromArray(array('Name Of Dr','Place /Patch','Town/HQ','Reportee','Emp.Code','Month','Visit Dates','Visit frequency'), NULL, 'A1'); 
				foreach($totalRowData as $index=>$row)
				{
					$dataRow = array();
					$dataRow[] = $row['doctor_name'];
					$dataRow[] = $row['city_name'];
					$dataRow[] = $row['headquater_name'];
					$dataRow[] = $row['name'];
					$dataRow[] = $row['employee_code'];
					$dataRow[] = $row['visit_month'];
					$dataRow[] = $row['Days'];
					$dataRow[] = count(explode(',',$row['Days']));
					/*foreach($months as $month){
					  $dataRow[] = $monthsdata[$row['doctor_id']][$month];
					}*/
					//echo "<pre>";print_r($reportRows);die;					
					$reportRows[] = $dataRow;
				}
				
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:H1'); // Filter on All Column
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Dr. Visit Frequency Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="doctor_visit_frequency_report.xls"');
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
	 * Get All Doctor Visit Detail Report
	 * Function : getDoctorVisitDetailReport()
	 * This function return doctor visit detail data.
	 **/
	public function getDoctorVisitDetailReport($data)
	{
		try {
			$where = "RT.delete_status='0'"; //echo "<pre>";print_r($data);die;
			
			$filterparam = '';
			//Filter With BE Data
			if(!empty($data['token3'])){
				//$filterparam .= " AND EE.be_visit='".Class_Encryption::decode($data['token3'])."'";
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($data['token4']) && empty($data['token3'])){
				//$filterparam .= " AND EE.abm_visit='".Class_Encryption::decode($data['token4'])."'";
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
				//$filterparam .= " AND EE.rbm_visit='".Class_Encryption::decode($data['token5'])."'";
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
				//$filterparam .= " AND EE.zbm_visit='".Class_Encryption::decode($data['token6'])."'";
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token6'])."'";
			}
			if(!empty($data['token2'])){
				$filterparam .= " AND PC.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND PC.headquater_id IN(".$value.")";
		   }
			
			//Filter With Product Data
			$productID = (isset($data['ptoken']) && !empty($data['ptoken'])) ? Class_Encryption::decode($data['ptoken']) : 0;
			if(!empty($data['ptoken'])){
				$filterparam .= " AND (EE.product1='".$productID."' OR EE.product2='".$productID."' OR EE.product3='".$productID."' OR EE.product4='".$productID."' OR EE.product5='".$productID."')";
			}
			//Filter With Doctor Type
			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			if(!empty($data['from_date']) && !empty($data['to_date'])){
			    $filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}else{
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')";
			}
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.call_date');
			
			$countQuery = $this->_db->select()
								->from(array('EE'=>'app_doctor_visit'),array('COUNT(1) AS CNT'))
								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
						    	->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;
			
			$toshow = $orderlimit['Toshow'];
			$offset = $orderlimit['Offset'];
			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {
				$toshow = '';
				$offset = '';
			}
			  
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array("CONCAT(RT.first_name,' ',RT.last_name) AS reportee",'PC.patch_name','DD.doctor_name','DD.speciality','DD.class',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.call_date','EE.call_time','RT.employee_code','CT.city_name','RT.employee_code'))
							  	->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
								->joininner(array('HT'=>'headquater'),"HT.headquater_id=PC.headquater_id",array('headquater_name'))
								->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",'')
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							   	->where($where.$filterparam)
							   	->order("EE.call_date DESC")
							   	->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   	->limit($toshow,$offset); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){ 
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	/**
	 * Get All Doctor Visit Detail Report
	 * Function : getDoctorVisitDetailReport()
	 * This function return doctor visit detail data.
	 **/
	public function getFilterDetail($data)
	{
		$filterparam = " ED.user_id=0";
		//Filter With BE
		if(!empty($data['token3'])){
			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token3'])."'";
		}
		//Filter With ABM
		if(!empty($data['token4']) && empty($data['token3'])){
			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token4'])."'";
		}
		//Filter With RBM
		if(!empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token5'])."'";
		}
		//Filter With ZBM
		if(!empty($data['token6']) && empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token6'])."'";
		}
		
		
		
		
		$select = $this->_db->select()
							   	->from(array('ED'=>'employee_personaldetail'),array('ED.employee_code',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","DT.designation_name","HT.headquater_name","RG.region_name"))
							  	->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
								->joininner(array('RG'=>'region'),"RG.region_id=EL.region_id",'')
							   	->where($filterparam); //print_r($select->__toString());die;
		$result =  $this->getAdapter()->fetchRow($select); //echo "<pre>";print_r($result);die;
		$returnData = array();
		if(!empty($result)>0) {
			$returnData['Emp. Code']= $result['employee_code'];
			$returnData['Name'] 	= $result['Emp'];
			$returnData['Degi.'] 	= $result['designation_name'];
			$returnData['Region'] 	= $result['region_name'];
			$returnData['HQ'] 		= $result['headquater_name'];
			$returnData['Date From']= $data['from_date'];
			$returnData['Date To'] 	= $data['to_date'];
		}
		return $returnData;
	}
	
	/**
	 * Export All Doctor Visit Detail Data
	 * Function : ExportDoctorVisitDetailReport()
	 * This function return doctor visit data sheet.
	 **/
	public function ExportDoctorVisitDetailReport($data=array())
	{
		try{
			$allData = $this->getDoctorVisitDetailReport($data);
			$totalRowData = $allData['Records']; //echo "<pre>";print_r($allData);die;
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Filter User Information
				$filterUser = $this->getFilterDetail($data); //echo "<pre>";print_r($filterUser);die;
				$rowPos = (count($filterUser)>0) ? 4 : 1;
				
				// Write Sheet Header
				$headers = array('0'=>'Date','1'=>'Place of work','2'=>'City/Town','3'=>'Headquater','4'=>'Reportee Name','5'=>'Emp. Code','6'=>'Dr. Name','7'=>'Specialty','8'=>'Class of Dr.','9'=>'Work With');
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);
				
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+$rowPos))->applyFromArray($styleArray);
				unset($styleArray);
				
				$rowPos = 1;
				if(count($filterUser)>0) {
					// Write User Header
					$objPHPExcel->getActiveSheet()->fromArray(array_keys($filterUser), NULL, 'A1');
					// Set title row bold
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);
					// Setting Column Background Color
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
					// Setting Text Alignment Center
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					// Write User Detail
					$objPHPExcel->getActiveSheet()->fromArray(array_values($filterUser), NULL, 'A2');
					$rowPos = 4;
				}
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				$visitWith = '';
				foreach($totalRowData as $index=>$row)
				{
					$visitWith = (!empty($row['zbmvisit'])) ? $row['zbmvisit'].'[ZBM]' : (!empty($row['rbmvisit']) ? $row['rbmvisit'].'[RBM]' : (!empty($row['abmvisit']) ? $row['abmvisit'].'[ABM]' : (!empty($row['bevisit']) ? $row['bevisit'].'[ABM]' : '')));
					$reportRows[] = array($row['call_date'],$row['patch_name'],$row['city_name'],$row['headquater_name'],$row['reportee'],$row['employee_code'],$row['doctor_name'],$row['speciality'],$row['class'],$visitWith);					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.($rowPos+1));
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos);
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Dr. Visit Detail Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="doctor_visit_detail_report.xls"');
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
		   $_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code : '.__LINE__;  
		}		  
	}
	
	/**
	 * Get Non Listed Doctor Visit Detail Report
	 * Function : getNonListedDoctorReport()
	 * This function return doctor visit detail data.
	 **/
	public function getNonListedDoctorReport($data)
	{
		try {
			$where = "RT.delete_status='0'  AND DD.isApproved='0'";			
			$filterparam = '';
			//Filter With BE Data
			if(!empty($data['token3'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($data['token4']) && empty($data['token3'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token5']) && empty($data['token4']) && empty($data['token3'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token6'])."'";
			}
			//HQ
			if(!empty($data['token2'])){
				$filterparam .= " AND PC.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}else{
			   $filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m') =DATE_FORMAT(CURDATE(),'%Y-%m')";
			}
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND PC.headquater_id IN(".$value.")";
		   }
			
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.call_date');
			
			$countQuery = $this->_db->select()
								->from(array('EE'=>'app_doctor_visit'),array('COUNT(1) AS CNT'))
								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",array(''))
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
						    	->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;
			
			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];
			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {
				$limit = '';
			}
			  
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array("CONCAT(RT.first_name,' ',RT.last_name) AS reportee",'PC.patch_name','DD.doctor_name','DD.speciality','DD.class',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.call_date','EE.call_time','RT.employee_code'))
							  	->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
								->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array('headquater_name'))
								->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",array('city_name'))
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							   	->where($where.$filterparam)
							   	->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   	->limit($limit); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	/**
	 * Export Non Listed Doctor Visit Data
	 * Function : ExportNonListedDoctorVisitReport()
	 * This function return non listed doctor visit data sheet.
	 **/
	public function ExportNonListedDoctorVisitReport($data=array())
	{
		try{
			$allData = $this->getNonListedDoctorReport($data);
			$totalRowData = $allData['Records']; //echo "<pre>";print_r($allData);die;
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Filter User Information
				$filterUser = $this->getFilterDetail($data); //echo "<pre>";print_r($filterUser);die;
				$rowPos = (count($filterUser)>0) ? 4 : 1;
				
				// Write Sheet Header
				$headers = array('0'=>'Date','1'=>'Place of work','2'=>'City/Town','3'=>'Headquater','4'=>'Reportee','5'=>'Emp. Code','6'=>'Non Listed Dr. Name','7'=>'Specialty','8'=>'Class of Dr.','9'=>'Work With');
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);
				
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+$rowPos))->applyFromArray($styleArray);
				unset($styleArray);
				
				//$rowPos = 1;
				if(count($filterUser)>0) {
				     $rowPos = 1;
					// Write User Header
					$objPHPExcel->getActiveSheet()->fromArray(array_keys($filterUser), NULL, 'A1');
					// Set title row bold
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);
					// Setting Column Background Color
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
					// Setting Text Alignment Center
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					// Write User Detail
					$objPHPExcel->getActiveSheet()->fromArray(array_values($filterUser), NULL, 'A2');
					$rowPos = 4;
				}
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				$visitWith = '';
				foreach($totalRowData as $index=>$row)
				{
					$visitWith = (!empty($row['zbmvisit'])) ? $row['zbmvisit'].'[ZBM]' : (!empty($row['rbmvisit']) ? $row['rbmvisit'].'[RBM]' : (!empty($row['abmvisit']) ? $row['abmvisit'].'[ABM]' : (!empty($row['bevisit']) ? $row['bevisit'].'[BE]' : '')));
					$reportRows[] = array($row['call_date'],$row['patch_name'],$row['city_name'],$row['headquater_name'],$row['reportee'],$row['employee_code'],$row['doctor_name'],$row['speciality'],$row['class'],$visitWith);					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.($rowPos+1));
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos);
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Non Listed Doctor Visit Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="non_listed_doctor_visit_report.xls"');
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
		   $_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code : '.__LINE__;  
		}		  
	}
	
	
	/**
	 * Get Mised Doctor Call
	 * Function : getMissedDoctorReport()
	 * This function all mised doctor reported.
	 **/
	public function getMissedDoctorReport($data)
	{
		try {
			$where = "RT.delete_status='0'  AND DD.isApproved='0'";			
			$filterparam = '';
			//Filter With BE Data
			$this->_headquarters = array();
			if(!empty($data['token3'])){
				$where = '1';
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4']) && empty($data['token3'])){
				$where = '1';
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token4'])  && empty($data['token3'])){
				$where = '1';
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token5'])  && empty($data['token4']) && empty($data['token3'])){
				$where = '1';
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			if(!empty($data['token2'])){
				$filterparam .= " AND PC.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				//$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND PC.headquater_id IN(".$value.")";
		   }
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'DD.doctor_name');
			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {
				$limit = '';
			}
			$arrays = array();
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$i = date("Ym", strtotime($data['from_date']));
				while($i <= date("Ym", strtotime($data['to_date']))){
					$arrays[] = $i;
					if(substr($i, 4, 2) == "12")
						$i = (date("Y-", strtotime($i."01")) + 1)."01";
					else
						$i++;
				}
			}else{
			  $arrays = array(date('Ym'));
			}
			
			//print_r($arrays);die;
			
			$records = array();
			foreach($arrays as $array){
			      $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('doctor_id'))
								->where("DATE_FORMAT(EE.call_date,'%Y%m')='".$array."'")
								->group("EE.doctor_id");
					//print_r($select->__toString());die;
				  $doctors =  $this->getAdapter()->fetchAll($select);
				  $doctorids = array();
				  foreach($doctors as $doctor){
				    $doctorids[] = $doctor['doctor_id'];
				  }
					
				  $select = $this->_db->select()
							   ->from(array('DD'=>'crm_doctors'),array('doctor_id','doctor_name','speciality',new Zend_Db_Expr("'$array' AS Month")))
							   ->joininner(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",array('patch_name'))
							   ->joininner(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",array('headquater_name'))
							   ->joinleft(array('CT'=>'city'),"CT.headquater_id=HQ.headquater_id",array('city_name'))
							   ->joinleft(array('EL'=>'emp_locations'),"EL.headquater_id=HQ.headquater_id",'')
							   ->joininner(array('EM'=>'employee_personaldetail'),"EL.user_id=EM.user_id AND EM.designation_id=8 AND EL.user_id!=2 AND EM.delete_status='0'",array('CONCAT(EM.first_name," ",EM.last_name) AS doct_be'))
							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EM.parent_id AND ABM.designation_id=7",array('CONCAT(ABM.first_name," ",ABM.last_name) AS doct_abm'))
							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=ABM.parent_id AND RBM.designation_id=6",array('CONCAT(RBM.first_name," ",RBM.last_name) AS doct_rbm'))
							   
							   ->where("DD.isApproved='1' AND DD.isDelete='0' AND DD.doctor_id NOT IN(".implode(',',$doctorids).")".$filterparam."")
							   ->group("DD.doctor_name")
							   ->group("PC.patch_id")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType']);
							   //print_r($select->__toString());die;
				 $result =  $this->getAdapter()->fetchAll($select);
				 //$result['month'] = $array;
				 $records = array_merge($records,$result);  			
			}  
			return array('Total'=>count($records),'Records'=>$records,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){ 
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	/**
	 * Export Non Listed Doctor Visit Data
	 * Function : ExportNonListedDoctorVisitReport()
	 * This function return non listed doctor visit data sheet.
	 **/
	public function ExportMissedDoctorReportReport($data=array())
	{
		try{
			$allData = $this->getMissedDoctorReport($data);
			$totalRowData = $allData['Records']; //echo "<pre>";print_r($allData);die;
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Filter User Information
				$filterUser = $this->getFilterDetail($data); //echo "<pre>";print_r($filterUser);die;
				$rowPos = (count($filterUser)>0) ? 4 : 1;
				
				// Write Sheet Header
				//$headers = array('0'=>'Date','1'=>'Place of work','2'=>'Headquater','3'=>'Reportee','4'=>'Sl No','5'=>'Non Listed Dr. Name','6'=>'Specialty','7'=>'Class of Dr.','8'=>'Activity Type','9'=>'Work With');
				//$objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
				//$objPHPExcel->getActiveSheet()->mergeCells('F1:L1');
				
				//$objPHPExcel->getActiveSheet()->fromArray(array('Months'), NULL, 'L1');
				
				$rowPos = 1;
				$reportRows = array();
				$visitWith = ''; 
				
				foreach($totalRowData as $index=>$row)
				{
					$reports[$row['Month']] = $row['Month'];
					$reports1[$row['Month']] = date('F-Y',strtotime(substr($row['Month'],0,-2).'-'.substr($row['Month'],-2)));
					$getdoctors[$row['Month']][$row['doctor_id']] = $row['Month'];					
				}
				$objPHPExcel->getActiveSheet()->fromArray(array('Name of Doctor','Patch','Town','HQ','BE','ABM','RBM','ZBM')+$reports1, NULL, 'A1');
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				
				
				$objPHPExcel->getActiveSheet()->getStyle('A2:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
				//echo "<pre>";print_r($reports);die;
				
				foreach($totalRowData as $index=>$row)
				{
				      $data =array();
					  $data[]  = $row['doctor_name'];
					  $data[]  = $row['patch_name'];
					  $data[]  = $row['city_name'];
					  $data[]  = $row['headquater_name'];
					  $data[]  = $row['doct_be'];
					  $data[]  = $row['doct_abm'];
					  $data[]  = $row['doct_rbm'];
					  $data[]  = $row['doct_zbm'];
					foreach($reports as $report){
					   if(isset($getdoctors[$row['Month']][trim($row['doctor_id'])])){
					   	 $data[] = 'Missed';
					   }else{
					     $data[] = '';
					   } 
					}
					$reportRows[] = $data;
					//print_r($reportRows);die;					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Non Listed Doctor Visit Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="missed_call_doctor.xls"');
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
		   $_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code : '.__LINE__;  
		}		  
	}
	
	/**
	 * Get Non Listed Doctor Visit Detail Report
	 * Function : getNonListedDoctorReport()
	 * This function return doctor visit detail data.
	 **/
	public function getDoctorWiseCallReport($data)
	{
		try {
			$where = "RT.delete_status='0'  AND DD.isApproved='1'";			
			$filterparam = '';
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND DD.doctor_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($data['token4']) && empty($data['token3'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token3'])  && empty($data['token4'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token3'])  && empty($data['token4']) && empty($data['token5'])){
				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token6'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}else{
			    $filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')=DATE_FORMAT(CURDATE(),'%Y-%m')";
			}
			if(!empty($data['monthrange'])) {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";
			}
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLoginID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND PC.headquater_id IN(".$value.")";
		   }
			
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'DD.doctor_name');
			
			/*$countQuery = $this->_db->select()
								->from(array('EE'=>'app_doctor_visit'),array('COUNT(1) AS CNT'))
								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
						    	->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);*/ //print_r($total);die;
			
			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];
			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {
				$limit = '';
			}
			  
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('DD.doctor_name',"CONCAT(RT.first_name,' ',RT.last_name) AS reportee",'PC.patch_name','DD.speciality','DD.class','HQ.headquater_name AS HQ','CT.city_name AS city',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS With_Zbm","CONCAT(RBM.first_name,' ',RBM.last_name) AS With_Rbm","CONCAT(ABM.first_name,' ',ABM.last_name) AS With_Abm","CONCAT(BE.first_name,' ',BE.last_name) AS With_Be",'EE.call_date' ,'EE.call_time'))
							  	->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')
							   	->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   	->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",'')
								->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=PC.headquater_id",'')
								->joinleft(array('CT'=>'city'),"CT.city_id=PC.city_id",'')
							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')
							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')
							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')
							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')
							   	->where($where.$filterparam)
								->order("EE.call_date DESC")
							   	->order("DD.doctor_name");
							   	//->limit($limit); //print_r($select->__toString());die;
								
				if(!$data['exportVisit']){
				  return $select;
				}				
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	/**
	 * Export Non Listed Doctor Visit Data
	 * Function : ExportDoctorWiseCallReport()
	 * This function return non listed doctor visit data sheet.
	 **/
	public function ExportDoctorWiseCallReport($data=array())
	{
		try{
			$allData = $this->getDoctorWiseCallReport($data);
			//$totalRowData =  $this->getAdapter()->fetchAll($select);
			$totalRowData = $allData['Records']; 
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Filter User Information
				$filterUser = $this->getFilterDetail($data); //echo "<pre>";print_r($filterUser);die;
				$rowPos = (count($filterUser)>0) ? 4 : 1;
				
				// Write Sheet Header
				$headers = array('Doctor Name','Specialty','Class of Dr.','Place of work','City/Town','HQ','Date','Call Time','Reported By','BE Name','ABM Name','RBM Name');
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);
				
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+4))->applyFromArray($styleArray);
				unset($styleArray);
				
				$rowPos = 1;
				if(count($filterUser)>0) {
					// Write User Header
					$objPHPExcel->getActiveSheet()->fromArray(array_keys($filterUser), NULL, 'A1');
					// Set title row bold
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);
					// Setting Column Background Color
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
					// Setting Text Alignment Center
					$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					
					// Write User Detail
					$objPHPExcel->getActiveSheet()->fromArray(array_values($filterUser), NULL, 'A2');
					$rowPos = 4;
				}
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFont()->setBold(true);				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				$visitWith = '';
				foreach($totalRowData as $index=>$row)
				{
					$reportRows[] = array($row['doctor_name'],$row['speciality'],$row['class'],$row['patch_name'],$row['HQ'],$row['city'],$row['call_date'],$row['call_time'],$row['reportee'],$row['With_Be'],$row['With_Abm'],$row['With_Rbm']);					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A'.($rowPos+1));
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				//$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				$objPHPExcel->getActiveSheet()->setAutoFilter('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().$rowPos);
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Doctor Wise Call Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="doctor_wise_call_report.xls"');
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
		   $_SESSION[ERROR_MSG] = 'There is some error, please try again. Error Code : '.__LINE__;  
		}		  
	}
	
	/**
	 * Get All Doctor Visit Frequency Report
	 * Function : getDoctorVisitFrequency()
	 * This function return doctor visit frequency data.
	 **/
	public function getChemistVisitFrequency($data)
	{
		try {
			$where = '1'; //echo "<pre>";print_r($data);die;
			/*if($_SESSION['AdminDesignation']==8){
				$where .= " AND CE.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (CE.user_id='".$_SESSION['AdminLoginID']."' OR RT.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (CE.user_id='".$_SESSION['AdminLoginID']."' OR RT.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (CE.user_id='".$_SESSION['AdminLoginID']."' OR RT.parent_id='".$_SESSION['AdminLoginID']."')";
			}*/
			
			$filterparam = '';
			//Filter With Doctor Data
			if(!empty($data['token1'])){
				$filterparam .= " AND CE.chemist_id='".Class_Encryption::decode($data['token1'])."'";
			}
			//Filter With Headquarter Data
			if(!empty($data['token2'])){
				$where = '1';
				$filterparam .= " AND EL.headquater_id='".Class_Encryption::decode($data['token2'])."'";
			}
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With RBM Data
			if(!empty($data['token5'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With ZBM Data
			if(!empty($data['token6'])){
				$where = '1';$this->_headquarters = array();
				$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= ' AND EL.headquater_id IN ('.implode(',',array_unique($this->_headquarters)).')';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(CE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
				
			if(!empty($data['year']) && !empty($data['month'])) {
				$filterparam .= " AND DATE_FORMAT(CE.call_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";
			}
			else {
				$filterparam .= " AND DATE_FORMAT(CE.call_date,'%Y-%m')='".date('Y-m')."'";
			}
			if($_SESSION['AdminLevelID'] != 1){
			   $value = implode(',',$this->geHierarchyId());
			   $filterparam .= " AND EL.headquater_id IN(".$value.")";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'CC.chemist_name');
			
			$countQuery = $this->_db->select()
								->from(array('CE'=>'app_chemist_visit'),array('COUNT(1) AS CNT'))
								->joininner(array('CC'=>'crm_chemists'),"CC.chemist_id=CE.chemist_id",'')
							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=CE.user_id",'')
								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=CE.user_id",'')
						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')
							   	->where($where.$filterparam); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];
			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {
				$limit = '';
			}
			
			$select = $this->_db->select()
							   	->from(array('CE'=>'app_chemist_visit'),array('CC.chemist_name','CC.class',"DATE_FORMAT(CE.call_date,'%M, %Y') AS visit_month","GROUP_CONCAT(DISTINCT DATE_FORMAT(CE.call_date,'%d') SEPARATOR ', ') AS Days"))
							   	->joininner(array('CC'=>'crm_chemists'),"CC.chemist_id=CE.chemist_id",'')
							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=CE.user_id",'')
								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=CE.user_id",array('CONCAT(first_name," ",last_name) AS Reportee'))
						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))
								->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CC.patch_id",array('patch_name'))
							   	->where($where.$filterparam)
							   	->group("CE.chemist_id")
							   	->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   	->limit($limit); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	/**
	 * Export All Doctor Visit Frequency Data
	 * Function : ExportDoctorVisitFrequency()
	 * This function return doctor visit data sheet.
	 **/
	public function ExportChemistVisitFrequency($headers=array(),$filterData=array())
	{
		try{
			$totalRowData = $filterData['Records'];
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				// Write Sheet Header
				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A1');
				
				$styleArray = array(
							  'borders' => array(
								'allborders' => array(
								  'style' => PHPExcel_Style_Border::BORDER_THIN
								)
							  )
							);
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);
				unset($styleArray);
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				foreach($totalRowData as $index=>$row)
				{
					$reportRows[] = array($row['chemist_name'],$row['Reportee'],$row['patch_name'],$row['headquater_name'],$row['class'],$row['visit_month'],$row['Days']);					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Chemist Visit Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="chemist_visit_report.xls"');
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
	public function getjointWorkingReport($data){ //echo "<pre>";print_r($data);die;
	       $filterparam ='';
		   //Filter With BE Data
			$this->_headquarters = array();
			//Filter With ABM Data
			if(!empty($data['token4'])){
				$where = '1';
				$filterparam .= ' AND EE.user_id="'.Class_Encryption::decode($data['token4']).'"';
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token4'])){
				$where = '1';
				$filterparam .= ' AND EE.user_id="'.Class_Encryption::decode($data['token4']).'"';
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token5'])  && empty($data['token4'])){
				$where = '1';
				$filterparam .= ' AND EE.user_id="'.Class_Encryption::decode($data['token4']).'"';
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}else{
			   $filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".date('Y-m')."'";
			}
			if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam .= " AND PT.headquater_id IN(".$value.")";
		   }
		   //Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.call_date');
			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];
			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {
				$limit = '';
			}
		   $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('visit_id'))
							   ->joininner(array('CD'=>'crm_doctors'),"CD.doctor_id=EE.doctor_id",array(''))
							   ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CD.patch_id",array(''))
						       ->joininner(array('CT'=>'city'),"CT.headquater_id=CD.headquater_id",array(''))
							   ->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=EE.user_id",array(''))
							   ->where("((EE.be_visit>0 AND UD.designation_id=7) OR (EE.be_visit>0 AND UD.designation_id=6)) AND (UD.designation_id=7 OR UD.designation_id=6)".$filterparam)
							   ->order("call_date DESC")
							   ->group(array('EE.call_date','EE.user_id','EE.doctor_id'));
					//print_r($select->__toString());die;
		   $total =  $this->getAdapter()->fetchAll($select);
		   $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('*','(SELECT region_name from region RT where RT.region_id=PT.region_id) AS region_name'))
							   ->joininner(array('CD'=>'crm_doctors'),"CD.doctor_id=EE.doctor_id",array('CD.doctor_name'))
							   ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CD.patch_id",array('patch_name'))
						       ->joininner(array('CT'=>'city'),"CT.headquater_id=CD.headquater_id",array('city_name'))
							   ->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=EE.user_id",array('UD.designation_id','if(UD.designation_id=7,CONCAT(UD.first_name," ",UD.last_name),"") AS abm_name','if(UD.designation_id=6,CONCAT(UD.first_name," ",UD.last_name),"") AS rbm_name'))
							   ->joinleft(array('UD1'=>'employee_personaldetail'),"UD1.user_id=EE.be_visit",array('CONCAT(UD1.first_name," ",UD1.last_name) AS be_name'))
							   ->where("((EE.be_visit>0 AND UD.designation_id=7) OR (EE.be_visit>0 AND UD.designation_id=6)) AND (UD.designation_id=7 OR UD.designation_id=6)".$filterparam)
							   ->group(array('EE.call_date','EE.user_id','EE.doctor_id'))
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($limit);
					//print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //print_r($select->__toString());die;
			//$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			//return array('Records'=>$result);
			return array('Total'=>count($total),'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
	}
	public function exportjointWorkingreport($filterData){
	    try{
			$totalRowData = $filterData['Records'];
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				// Write Sheet Header
				
				$objPHPExcel->getActiveSheet()->fromArray(array('Region',$totalRowData[0]['region_name'],'Period'), NULL, 'A1');
				$objPHPExcel->getActiveSheet()->fromArray(array('','','ABM','','','RBM',''), NULL, 'A4');
				//$objPHPExcel->getActiveSheet()->mergeCells('B4:D4');
				$objPHPExcel->getActiveSheet()->fromArray(array('Date','Name Of ABM','Work With BE','Work with Manager','Name of RBM','Work With BE','Work With Manager','Doctor Name','Patch','Town'), NULL, 'A5');				
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+5))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				$objPHPExcel->getActiveSheet()->getStyle('A4:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				$objPHPExcel->getActiveSheet()->getStyle('A4:J5')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				foreach($totalRowData as $index=>$report)
				{
				   $data= array();
					$data[] = $report['call_date'];

						 $data[] = $report['abm_name'];
						$data[] = ($report['designation_id']==7)?$report['be_name']:'';
						 $data[] = ($report['designation_id']==7 && $report['work_with_ho']>0)?'Yes':'';

						 $data[] = $report['rbm_name']; 

						 $data[] = ($report['designation_id']==6)?$report['be_name']:''; 
						 
						 $data[] = ($report['designation_id']==6 && $report['work_with_ho']>0)?'Yes':'';

						 $data[] = $report['doctor_name'];

						 $data[] = $report['patch_name'];

						 $data[] = $report['city_name'];
					     $reportRows[] = $data;					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A6');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter('A5:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'5');
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Joint Working Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="joint_working_report.xls"');
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
		catch(Exception $e){ echo $e->getMessage();die;
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}
	}
	
	public function getActivitySummaryReport($data){
	   try {
			$where = "ED.delete_status='0'"; //echo "<pre>";print_r($data);die;
			if($_SESSION['AdminDesignation']==8){
				$where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
			}
			elseif($_SESSION['AdminDesignation']==7){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			elseif($_SESSION['AdminDesignation']==6){
				$childs =  $this->getChilllds("ED");
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
			}
			elseif($_SESSION['AdminDesignation']==5){
				$where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
			}
			
			$filterparam = '';
			//Filter With BE Data
			if(!empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token3']));
				$filterparam .= " AND ED.user_id='".Class_Encryption::decode($data['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($data['token4']) && empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token4']));
				$filterparam .= " AND ED.user_id='".Class_Encryption::decode($data['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($data['token5']) && empty($data['token4'])  && empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token5']));
				$filterparam .= " AND ED.user_id='".Class_Encryption::decode($data['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($data['token6']) && empty($data['token5'])  && empty($data['token4']) && empty($data['token3'])){
				$where = '1';$this->_headquarters = array();
				//$this->getHeadQuaters(Class_Encryption::decode($data['token6']));
				$filterparam .= " AND ED.user_id='".Class_Encryption::decode($data['token6'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				$filterparam .= " AND EE.call_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}
			elseif($data['year']!='' && $data['month']!='') {
				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".$data['year'].'-'.$data['month']."'";
			}else{
			  $filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".date('Y-m')."'";
			}
			//Filter With Doctor Type
			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";
			//Filter with Activity
			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';
			
			$having = '';
			if(!empty($data['call']) && trim($data['month']) != 'all') {
				$extractCall = explode('-',$data['call']);
				$startAvg = (isset($extractCall[0])) ? $extractCall[0] : '0';
				$lastAvg  = (isset($extractCall[1])) ? $extractCall[1] : '10';
				$having   = " HAVING Call_Avg BETWEEN '".$startAvg."' AND '".$lastAvg."'";
			}
			
			//Order
			$orderlimit = CommonFunction::OdrderByAndLimit($data,'ED.employee_code');
			
			$countQuery = $this->_db->select()
							->from(array('EE'=>'app_doctor_visit'),array('(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg'))
							->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')
						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')
						   // ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')
						    ->where($where.$filterparam)
						    ->group("EE.user_id")
						    ->group("DATE_FORMAT(EE.call_date,'%Y-%m')".$having)
							->order("ED.employee_code"); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchAll($countQuery);
			   
			$select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",
	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg",'MAX(EE.call_date) AS last_reporting','GROUP_CONCAT(EE.doctor_id) AS ALL_DOCOTR','GROUP_CONCAT(DD.doctor_name) AS ALL_DOCOTRNAME',"(SELECT SUM(LR.total_days) FROM leaverequests LR WHERE LR.user_id=EE.user_id AND DATE_FORMAT(LR.leave_from,'%Y-%m')=DATE_FORMAT(EE.call_date,'%Y-%m') GROUP BY LR.user_id) AS total_leave","COUNT(IF(DD.isApproved='0',1,0)) AS Total_NLD",'(SELECT COUNT(DISTINCT(AM.meeting_date)) FROM app_meeting AM WHERE AM.user_id=EE.user_id AND DATE_FORMAT(EE.call_date,"%Y-%m")=DATE_FORMAT(AM.meeting_date,"%Y-%m")) AS Total_NC','(SELECT COUNT(DISTINCT(CV.chemist_id)) FROM app_chemist_visit CV WHERE CV.user_id=EE.user_id AND DATE_FORMAT(EE.call_date,"%Y-%m")=DATE_FORMAT(CV.call_date,"%Y-%m")) AS Total_CV'))
							   ->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=EE.doctor_id",'')
							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",'')
							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_id'))
							   //->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')
							   ->joininner(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')
							   //->where("DD.isApproved='1'")
							   ->where($where.$filterparam)
							   ->group("EE.user_id")
							   ->group("DATE_FORMAT(EE.call_date,'%Y-%m')".$having)
							   //->having("Call_Avg BETWEEN '0' AND '10'")
							   ->order($orderlimit['OrderBy'].' '.$orderlimit['OrderType'])
							   ->limit($orderlimit['Toshow'],$orderlimit['Offset']); //print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;
			return array('Total'=>$total[0]['CNT'],'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
		}
		catch(Exception $e){
			$_SESSION[ERROR_MSG] =  'There is some error, please try again !!'; 
		}
	}
	
	public function GetNonvisitedDR($doctors,$user_id,$designation_id){
	    $onclause = '';
		switch($designation_id){
		   case 8:
		      $onclause = "DD.headquater_id=EL.headquater_id";
		     break;
		   case 7:
		      $onclause = "DD.area_id=EL.area_id";
		     break;
		   case 6:
		      $onclause = "DD.region_id=EL.region_id";
		     break;
		   case 5:
		      $onclause = "DD.zone_id=EL.zone_id";
		     break; 	 	 
		}
		$countQuery = $this->_db->select()
							->from(array('DD'=>'crm_doctors'),array('COUNT(1) AS CNT'))
							->joininner(array('EL'=>'emp_locations'),"".$onclause."",'')
						    ->where("EL.user_id='".$user_id."' AND DD.doctor_id NOT IN(".$doctors.") AND DD.isDelete='0'"); //print_r($countQuery->__toString());die;
			$total = $this->getAdapter()->fetchRow($countQuery);
		return $total['CNT'];	
	}
	public function exportActivitySummaryreport($filterData){
	    try{
			$totalRowData = $filterData['Records'];
			if(count($totalRowData)>0) {
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				// Write Sheet Header
				
				
				$objPHPExcel->getActiveSheet()->fromArray(array('Name','EmployeeCode','designation','HQ','Month','No. of days worked	','No of non call activity day','No of Leave','last reported date','No. of Doctor Visited more then thrice','No of DR Visited Thrice','No. of dr Visited twice','No. of Dr Visited once','No. of Dr Not Visited','Call Average','No of Non listed Call','No Of Chemist call'), NULL, 'A1');				
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				foreach($totalRowData as $index=>$report)
				{
				   $data= array();
				        $data[] = $report['Emp'];
						$data[] = $report['employee_code'];
						$data[] = $report['designation_name'];
						$data[] = $report['headquater_name']; 
						$data[] = date('M-Y',strtotime($report['visit_month'])); 
						
						$data[] = $report['DAY_CNT']; 
						
						$data[] = $report['Total_NC']; 
						
						$data[] = $report['total_leave']; 
						
						$data[] = $report['last_reporting'];
						$getcounts = array_count_values(explode(',',$report['ALL_DOCOTR']));
						 //print_r($getcounts);die;
						   $morethantrice = 0;
						   $thrice = 0;
						   $twice = 0;
						   $once = 0;
						   foreach($getcounts as $getcount){
						      if($getcount>3){
							     $morethantrice = $morethantrice +1;
							  }
							  if($getcount==3){
							     $thrice = $thrice +1;
							  }
							  if($getcount==2){
							     $twice = $twice +1;
							  }
							  if($getcount==1){
							     $once = $once +1;
							  }
						   }
						 $data[] = $morethantrice;
						 $data[] = $thrice;
						 $data[] = $twice;
						 $data[] = $once;
						 $data[] = $this->GetNonvisitedDR($report['ALL_DOCOTR'],$report['user_id'],$report['designation_id']);
						 $data[] = $report['Call_Avg'];
						 $data[] = $report['Total_NLD'];
						 $data[] = $report['Total_CV'];
					     $reportRows[] = $data;					
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('Activity Summry Report');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="Activity_Summry_Report.xls"');
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
		catch(Exception $e){ echo $e->getMessage();die;
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}
	}
	public function ExportJointWorkSummary($data){
  
    $condition = '';
    if(!empty($data['from_date']) && !empty($data['to_date'])){
         $condition = " AND EE.call_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
    }else{
	    $condition = " AND EE.call_date='".date('Y-m')."'";
	}
    if(isset($data['region']) && !empty($data['region'])){
         $condition.= " AND HQ.region_id = '".$data['region']."'";
    }
   
   ini_set("memory_limit","-1");
   set_time_limit( 0 );
   ob_end_clean();
   $objPHPExcel = new PHPExcel();
   $objWorkSheet = $objPHPExcel->createSheet();
   
     foreach(range('A','N') as $columnid){
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnid)->setAutoSize(true);
     }
     $objPHPExcel->getActiveSheet()->getStyle('F1:I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
     $objPHPExcel->getActiveSheet()->fromArray(array('','','','','','F:I'=>'No of day Worked'), NULL, 'A1');
     $objPHPExcel->setActiveSheetIndex(0)->mergeCells('F1:I1');
     $objPHPExcel->getActiveSheet()->fromArray(array('Name of ABM/RBM','HQ','Work With','HQ','Month','HQ','EX','OUT','Total','No Of Dr Visited','No Of KOL Visited','No of non Listed Call','No of Stockiest met','No of Chemist Call'), NULL, 'A2');
     $objPHPExcel->getActiveSheet()->getStyle('A2:N2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
       $incr =0;
	   
	   $select = $this->_db->select()
							   ->from(array('EE'=>'app_doctor_visit'),array('visit_id',
							   '(SELECT region_name from region RT where RT.region_id=PT.region_id) AS region_name','COUNT(DISTINCT EE.call_date) AS TOTAL_DAYS','DATE_FORMAT(EE.call_date,"%M-%Y") AS visit_month',
							   "(SELECT COUNT(1) FROM app_doctor_visit DV INNER JOIN crm_doctors DC ON DC.doctor_id=DV.doctor_id AND DC.isApproved='1' WHERE DV.user_id=EE.user_id AND EE.call_date=DV.call_date) AS no_of_doctor",
							   "(SELECT COUNT(1) FROM app_doctor_visit DV1 LEFT JOIN crm_doctors DC1 ON DC1.doctor_id=DV1.doctor_id AND DC1.isApproved='0' WHERE DV1.user_id=EE.user_id AND EE.call_date=DV1.call_date) AS nonlisted_doctor",
							   "(SELECT COUNT(1) FROM app_chemist_visit CV WHERE CV.user_id=EE.user_id AND EE.call_date=CV.call_date AND EE.be_visit=CV.be_visit) AS no_chemist",
							   "(SELECT COUNT(1) FROM app_stockist_visit SV WHERE SV.user_id=EE.user_id AND DATE_FORMAT(EE.call_date,'%Y-%m')=DATE_FORMAT(SV.date_added,'%Y-%m') AND EE.be_visit=SV.be_visit) AS no_stockist",
							   "(SELECT COUNT(DISTINCT E.expense_date) FROM emp_expenses E WHERE EE.user_id=E.user_id AND E.expense_date=EE.call_date AND E.head_id=3) AS exstation",
							   "(SELECT COUNT(DISTINCT E1.expense_date) FROM emp_expenses E1 WHERE EE.user_id=E1.user_id AND E1.expense_date=EE.call_date AND E1.head_id=4) AS outstation"))
							   ->joininner(array('CD'=>'crm_doctors'),"CD.doctor_id=EE.doctor_id",array('CD.doctor_name'))
							   ->joininner(array('PT'=>'patchcodes'),"PT.patch_id=CD.patch_id",array('patch_name'))
						       ->joininner(array('CT'=>'city'),"CT.headquater_id=CD.headquater_id",array('city_name'))
							   ->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=EE.user_id",array('UD.designation_id','if(UD.designation_id=7,CONCAT(UD.first_name," ",UD.last_name),"") AS abm_name','if(UD.designation_id=6,CONCAT(UD.first_name," ",UD.last_name),"") AS rbm_name'))
							   ->joinleft(array('UD1'=>'employee_personaldetail'),"UD1.user_id=EE.be_visit",array('CONCAT(UD1.first_name," ",UD1.last_name) AS be_name'))
							   ->joininner(array('HQ'=>'headquater'),"CD.headquater_id=HQ.headquater_id",array('headquater_name'))
							   ->where("((EE.be_visit>0 AND UD.designation_id=7) OR (EE.be_visit>0 AND UD.designation_id=6)) AND (UD.designation_id=7 OR UD.designation_id=6)".$condition)
							   ->group(array('DATE_FORMAT(EE.call_date,"%Y-%m")','EE.user_id','UD1.user_id'))
							   ->order("DATE_FORMAT(EE.call_date,'%Y-%m') DESC");
					//print_r($select->__toString());die;
			$result =  $this->getAdapter()->fetchAll($select);//echo "<pre>";print_r($result);die;
   foreach($result as $key=> $record){ //echo $record['patch_name'];die;
      $cordinate = $incr+3; //print_r($record['TOTAL_DAYS']);die;
	  $reportee = ($record['designation_id']==7)?$record['abm_name']:$record['rbm_name'];
     $objPHPExcel->getActiveSheet()->fromArray(array($reportee,$record['headquater_name'],$record['be_name'],$record['headquater_name'],$record['visit_month'],($record['TOTAL_DAYS']-($record['exstation']+$record['outstation'])),$record['exstation'],$record['outstation'],$record['TOTAL_DAYS'],$record['no_of_doctor'],'',$record['nonlisted_doctor'],$record['no_stockist'],$record['no_chemist'],0,0), NULL, 'A'.$cordinate);
     $incr = $incr+1;
   }
   if($cordinate == '') 
   {
	$cordinate = 1;
   }
   else
   {
   $cordinate;
   }
   $styleArray = array('borders'=>array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN)));
   $objPHPExcel->getActiveSheet()->getStyle('A1:'.'N'.$cordinate)->applyFromArray($styleArray);
  
  header('Content-Type: application/xlsx');
  header('Content-Disposition: attachment;filename="Jointworksummry.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  ob_end_clean();
  $objWriter->save('php://output'); 
  $objPHPExcel->disconnectWorksheets();
  unset($objPHPExcel);die;
 }
 
   public function getRegion(){
    $select = $this->_db->select()
        ->from(array('region'),array('*'))
        ->order("region_name ASC");
           //echo $select->__toString();die;
   $records =  $this->getAdapter()->fetchAll($select);
   return $records;
 }
 
 public function ExportMissedCall($data){
  	 $filterparam = '1';
     $condition = '';
     if(!empty($data['from_date']) && !empty($data['to_date'])){
         $fromdate= date('Y-m',strtotime($data['from_date']));
       $todate= date('Y-m',strtotime($data['to_date']));
       $condition = " AND ADV.call_date BETWEEN '".$fromdate."-01' AND '".$todate."-31'";
     }else{
          $fromdate= date('Y-m-d', strtotime("first day of -1 month")); 
       $todate= date('Y-m-t');
       $condition = " AND ADV.call_date BETWEEN '".$fromdate."' AND '".$todate."'";
     }
	 if($_SESSION['AdminLevelID'] != 1 && $_SESSION['AdminLevelID'] != 44){
			  $value = implode(',',$this->geHierarchyId());
			  $filterparam = "PC.headquater_id IN(".$value.")";
		   }
    $select = $this->_db->select()
          ->from(array('ADV'=>'app_doctor_visit'),array('DATE_FORMAT(ADV.call_date,"%Y-%m") AS VISIT_MONTH'))
        ->where("ADV.doctor_id!='0'".$condition)
        ->group(array('DATE_FORMAT(ADV.call_date,"%Y-%m")'));
     //print_r($select->__toString());die;
      $getmonths =  $this->getAdapter()->fetchAll($select);
     // echo "<pre>";print_r($getmonths); die;
      $select = $this->_db->select()
          ->from(array('DD'=>'crm_doctors'),array('doctor_id','doctor_name'))
          ->joinleft(array('PC'=>'patchcodes'),"PC.patch_id=DD.patch_id",array('patch_name'))
          ->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=DD.headquater_id",array('headquater_name'))
          ->joinleft(array('CT'=>'city'),"CT.city_id=DD.city_id",array('city_name'))
          ->joinleft(array('EL'=>'emp_locations'),"EL.headquater_id=DD.headquater_id",array('EL.user_id'))
          ->joinleft(array('EPD'=>'employee_personaldetail'),"EPD.user_id=EL.user_id",array('CONCAT(EPD.first_name," ",EPD.last_name) AS BE_NAME'))
          ->where("DD.isApproved='1' AND EPD.delete_status='0' AND EPD.user_status='1' AND EPD.designation_id='8' AND DD.isDelete='0'")
		  ->where($filterparam)
          ->group(array('DD.doctor_id'))
          ->order('DD.doctor_name');
          //print_r($select->__toString());die;
     $result =  $this->getAdapter()->fetchAll($select);
        foreach($result as $key=>$data){
          foreach($getmonths as $abc=>$month){
           $select = $this->_db->select()
          ->from(array('ADV'=>'app_doctor_visit'),array('count(ADV.call_date) AS CNTDATE'))
          ->where("ADV.doctor_id!='0' AND ADV.doctor_id= '".$data['doctor_id']."' AND ADV.call_date LIKE '".$month['VISIT_MONTH']."%'");
        $count =  $this->getAdapter()->fetchRow($select);
        $result[$key]['status'][$abc] = ($count['CNTDATE'] > 0 ) ? $count['CNTDATE'] :'Not Visited';
      }
      
     }
   //echo "<pre>";print_r($getmonths); die;
   ini_set("memory_limit","-1");
   set_time_limit( 0 );
   ob_end_clean();
   $abc = 'E';
       $col= array('A','B','C','D','E');
     foreach($getmonths as $month){
    $abc++;
    $col[] = $abc;
     }
   $objPHPExcel = new PHPExcel();
   $objWorkSheet = $objPHPExcel->createSheet();
      foreach($col as $columnid){
      $objPHPExcel->getActiveSheet()->getColumnDimension($columnid)->setAutoSize(true);
     }
     $objPHPExcel->getActiveSheet()->getStyle('A1:'.$abc.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
     $objPHPExcel->getActiveSheet()->fromArray(array('A'=>'','B'=>'','C'=>'','E'=>''), NULL, 'A1');
     //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('A1:E1');
     
     //$objPHPExcel->setActiveSheetIndex(0)->mergeCells('F1:'.$abc.'1');
      $objPHPExcel->getActiveSheet()->fromArray(array('F1'=>'Months'), NULL, 'F1');
     $header = array('Name of BE','HQ','Name of Doctor','Patch','Town');
     foreach($getmonths as $kay=>$month){
     $index = 5+$kay;
     $header[$index] = date('M-Y',strtotime($month['VISIT_MONTH']));
     }
     $objPHPExcel->getActiveSheet()->fromArray($header, NULL, 'A1');
     $objPHPExcel->getActiveSheet()->getStyle('A1:'.$abc.'1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
     
       $incr =2;
   foreach($result as $key=> $record){
     $check = array();
     foreach($record['status'] as $remain_data){
         ($remain_data == 'Visited')? $check[] = 1 :  $check[] = 0;
       }
     if(in_array("0", $check)){
       $cordinate = 3;
       $objPHPExcel->getActiveSheet()->fromArray(array($record['BE_NAME'],$record['headquater_name'],$record['doctor_name'],$record['patch_name'],$record['city_name']), NULL, 'A'.$incr);
       $x = 'E';
       foreach($record['status'] as $remain_data){
      $x++;
        $objPHPExcel->getActiveSheet()->fromArray(array($remain_data), NULL, $x.$incr);
         }
          $incr = $incr+1;
      } 
    
   }
   $styleArray = array('borders'=>array('allborders'=>array('style'=>PHPExcel_Style_Border::BORDER_THIN)));
   $objPHPExcel->getActiveSheet()->getStyle('A1:'.$abc.'1')->applyFromArray($styleArray);
   //$objPHPExcel->getActiveSheet()->getStyle('A2:'.$abc.'2')->applyFromArray($styleArray);
  
  header('Content-Type: application/xlsx');
  header('Content-Disposition: attachment;filename="MissedCallSummary.xlsx"');
  header('Cache-Control: max-age=0');
  $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
  ob_end_clean();
  $objWriter->save('php://output'); 
  $objPHPExcel->disconnectWorksheets();
  unset($objPHPExcel);die;
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
        ->from(array('EL'=>'emp_locations'),array('headquater_id'))
        ->joininner(array('EPD'=>'employee_personaldetail'),"EL.user_id=EPD.user_id",array(''))
        ->where($where);
        //print_r($select->__toString());die;
    $result = $this->getAdapter()->fetchAll($select);
    $res_id = array();
    foreach($result as $key=>$value){
    $res_id[] = $value['headquater_id'];
    }
     
     return $res_id;
   }
   
   public function ondemandExport($filterData){
	    try{
			$totalRowData = array();
			
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				// Write Sheet Header
				
				//Filter With Date Range
				$filterparam = '';	
				/*if(!empty($data['from_date']) && !empty($data['to_date'])){
					$filterparam .= " AND DATE(EE.date_added) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
				}*/
				
				if(!empty($filterData['year']) && !empty($filterData['month'])) {
					$filterparam = " AND DATE_FORMAT(DV.call_date,'%Y-%m')='".trim($filterData['year']).'-'.trim($filterData['month'])."'";
					
				}else{
				    $filterparam = " AND DATE_FORMAT(DV.call_date,'%Y-%m')='".date('Y-m')."'";
					$filterData['year'] = date('Y');
					$filterData['month'] = date('m');
				}
			if(!empty($filterData['token3'])){
				$filterparam .= " AND UD.user_id='".Class_Encryption::decode($filterData['token3'])."'";
			}
			//Filter With ABM Data
			if(!empty($filterData['token4']) && empty($filterData['token3'])){
				$filterparam .= " AND UD.user_id='".Class_Encryption::decode($filterData['token4'])."'";
			}
			//Filter With RBM Data
			if(!empty($filterData['token5']) && empty($filterData['token4'])  && empty($filterData['token3'])){
				$filterparam .= " AND UD.user_id='".Class_Encryption::decode($filterData['token5'])."'";
			}
			//Filter With ZBM Data
			if(!empty($filterData['token6']) && empty($filterData['token5'])  && empty($filterData['token4']) && empty($filterData['token3'])){
				$filterparam .= " AND UD.user_id='".Class_Encryption::decode($filterData['token6'])."'";
			}
			//Filter With Date Range
			if(!empty($data['from_date']) && !empty($data['to_date'])){
				//$filterparam .= " AND EE.call_date BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";
			}			
				$days = array();
				$headers = array('Name','EmployeeCode','Designation','HQ');
				foreach(range(1,30) as $day){
				  $days[] = date('Y-m-d',strtotime(trim($filterData['year']).'-'.trim($filterData['month'])."-".$day));
				}
				$headers = array_merge($headers,$days);
				array_push($headers, "Total Call");
				//$headers = array_merge($headers,'Total Call');
				$objPHPExcel->getActiveSheet()->fromArray($headers, NULL, 'A1');				
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:AI1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
				$reportRows = array();
				$select = $this->_db->select()
											->from(array('DV'=>'app_doctor_visit'),array('user_id'))
											->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=DV.user_id",array('designation_id','employee_code','first_name','last_name'))
											->joinleft(array('EL'=>'emp_locations'),"EL.user_id=DV.user_id",array())
											->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=EL.headquater_id",array('headquater_name'))
											->where("UD.delete_status='0' AND UD.user_status='1'".$filterparam)
											->group("DV.user_id");
											//print_r($select->__toString());die;
				$result = $this->getAdapter()->fetchAll($select);
				$desigcode = array(5=>'ZBM',6=>'RBM',7=>'ABM',8=>'BE');
				// Set to border
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($result)+1))->applyFromArray(array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN))));
				//print_r($days);die;
				$alluserid = '';
				foreach($result as $employes){
				   $dataarray = array();
				   $dataarray[] = $employes['first_name'].' '.$employes['last_name'];
				   $dataarray[] = $employes['employee_code'];
				   $dataarray[] = $desigcode[$employes['designation_id']];
				   $dataarray[] = $employes['headquater_name'];
				   $alluserid.=$employes['user_id'].',';
				   $total = 0;
				    foreach($days as $date){
					    $select = $this->_db->select()
											->from(array('DV'=>'app_doctor_visit'),array('COUNT(DISTINCT doctor_id) AS CNT'))
											->where("DV.user_id='".$employes['user_id']."' AND call_date='".$date."'");
											//print_r($select->__toString());die;
						$visits = $this->getAdapter()->fetchRow($select);
						if($visits['CNT']>0){
						  $dataarray[]  = $visits['CNT'];
						  $total= $total+$visits['CNT'];
						}else{
						 $select = $this->_db->select()
											->from(array('AM'=>'app_meeting'),array('COUNT(meeting_date) AS MeetCNT'))
											->joininner(array('AMT'=>'app_meetingtype'),"AMT.type_id=AM.meetingtype_id",array('type_name'))
											->where("AM.user_id='".$employes['user_id']."' AND AM.meeting_date='".$date."'");
											//print_r($select->__toString());die;
						$meetings = $this->getAdapter()->fetchRow($select);
						//echo '<pre>';print_r($meetings);
						if($meetings['MeetCNT']>0){
						  $dataarray[]  = $meetings['type_name'];
						}else{
						  $select = $this->_db->select()
											->from(array('LR'=>'leaverequests'),array('( CASE WHEN final_approval = "1" THEN "Leave - Approved" ELSE "Leave - Not Approved" END) AS Status'))
											->where("LR.user_id='".$employes['user_id']."' AND LR.leave_from <='".$date."' AND LR.leave_to >='".$date."'");
											//print_r($select->__toString());die;
						  $leaves = $this->getAdapter()->fetchRow($select);
							  if($leaves['Status']!=''){
								 $dataarray[]  = $leaves['Status'];
							  }elseif(date("l", strtotime($date)) == 'Sunday'){
							      $dataarray[]  = 'Sunday';
							  }else{
								 $dataarray[]  = $visits['CNT'];
							  }
						 }
						
					  }
					 
					}
					$dataarray[]  = $total;
					$reportRows[] = $dataarray;	
				}
				$alluserid = rtrim($alluserid,",");
				$totaldatewise = array('','','','Total Date Wise Call');
				foreach($days as $date){
					    $select = $this->_db->select()
											->from(array('DV'=>'app_doctor_visit'),array('COUNT(DISTINCT doctor_id) AS CNT'))
											->where("DV.user_id IN(".$alluserid.") AND call_date='".$date."'");
											//print_r($select->__toString());die;
						$datewisevisits = $this->getAdapter()->fetchRow($select);
						$totaldatewise[] = $datewisevisits['CNT'];
				}
				$reportRows[] = $totaldatewise;
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('MonthlyActivityReport');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="Activity_Summry_Report.xls"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				ob_end_clean();
				$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
				//$objWriter->save('test.xlsx');  //THIS WORKS
				$objPHPExcel->disconnectWorksheets();
				unset($objPHPExcel);die;
			
		}
		catch(Exception $e){ echo $e->getMessage();die;
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}
	}
	
	public function getMonthlyWorkedSummary($data){
	     $where = '1';
   $filter = '';
    if($_SESSION['AdminDesignation']==8){
       $where .= " AND ED.user_id='".$_SESSION['AdminLoginID']."'";
    }elseif($_SESSION['AdminDesignation']==7){
      $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";
    }elseif($_SESSION['AdminDesignation']==6){
       $childs =  $this->getChilllds("ED");
      $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";
    }elseif($_SESSION['AdminDesignation']==5){
	  $childusers =$this->getReportedusers(array($_SESSION['AdminLoginID']),1);
	  $where .= " AND (ED.user_id='".$_SESSION['AdminLoginID']."' OR ED.user_id IN(".implode(',',$childusers)."))";
    }
			$filter = '';
		   //Filter With BE Data
			if(!empty($data['be_id'])){
				$filter .= ' AND UD.user_id="'.Class_Encryption::decode($data['be_id']).'"';
			}
			//Filter With RBM Data
			if(!empty($data['abm_id']) && empty($data['be_id'])){
				$filter .= ' AND UD.user_id="'.Class_Encryption::decode($data['abm_id']).'"';
			}
			//Filter With ZBM Data
			if(!empty($data['rbm_id']) && empty($data['abm_id'])  && empty($data['be_id'])){
				$filter .= ' AND UD.user_id="'.Class_Encryption::decode($data['rbm_id']).'"';
			}
			if(!empty($data['zbm_id']) && empty($data['rbm_id']) && empty($data['abm_id'])  && empty($data['be_id'])){
				$filter .= ' AND UD.user_id="'.Class_Encryption::decode($data['zbm_id']).'"';
			}
			if($data['headquater_id']>0){
				$filter .= ' AND DD.headquater_id="'.$data['headquater_id'].'"';
			}
	
    $orderlimit = CommonFunction::OdrderByAndLimit($this->_getData,'DV.employee_code');
    //Filter With Date Range
   if(!empty($this->_getData['from_date']) && !empty($this->_getData['to_date'])){
    $filter .= " AND DATE_FORMAT(DV.call_date,'%Y-%m-%d') BETWEEN '".$this->_getData['from_date']."' AND '".$this->_getData['to_date']."'";
   }else{
     $filter .=" AND date_format(DV.call_date,'%Y-%m') = date_format(CURDATE(),'%Y-%m')";
   }
   		$select = $this->_db->select()
								->from(array('DV'=>'app_doctor_visit'),array('user_id'))
								->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=DV.doctor_id",'')
								->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=DV.user_id",array('designation_id','employee_code','first_name','last_name'))
								->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=DD.headquater_id",array('headquater_name'))
								->where("UD.delete_status='0' AND UD.user_status='1'".$filter)
								->group('DV.call_date')
								->group('DV.user_id');
											//print_r($select->__toString());die;
		$total = $this->getAdapter()->fetchAll($select);
   		//Order
		$orderlimit = CommonFunction::OdrderByAndLimit($data,'UD.employee_code');
		$limit = '';
		if(!$data['Export']){
		  $limit = " LIMIT ".$orderlimit['Offset'].",".$orderlimit['Toshow']."";
		}
	    $select = $this->_db->select()
								->from(array('DV'=>'app_doctor_visit'),array('*','user_id','date_format(call_date,"%M %Y") call_month','count(DISTINCT DV.doctor_id) AS TDC','GROUP_CONCAT(DISTINCT DD.doctor_name) AS dtrs','(SELECT COUNT(DISTINCT CV.chemist_id) FROM app_chemist_visit CV WHERE CV.user_id=DV.user_id AND DV.call_date=CV.call_date) AS CVC',''))
								->joininner(array('DD'=>'crm_doctors'),"DD.doctor_id=DV.doctor_id",'')
								->joininner(array('UD'=>'employee_personaldetail'),"UD.user_id=DV.user_id",array('designation_id','employee_code','first_name','last_name'))
								->joinleft(array('TP'=>'app_tourplan'),"DV.user_id=TP.user_id AND DV.call_date=TP.tour_date",array('be_visit as tpbe_visit','rbm_visit as tprbm_visit','abm_visit as tpabm_visit','zbm_visit as tpzbm_visit'))
								->joinleft(array('PC1'=>'patchcodes'),"PC1.patch_id=TP.location_id",array('patch_name'))
          					 ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=PC1.headquater_id",array('headquater_name AS planned_hq')) 
								->joinleft(array('DG'=>'designation'),"DG.designation_id=UD.designation_id",array('designation_name'))
								->joinleft(array('HQ'=>'headquater'),"HQ.headquater_id=DD.headquater_id",array('headquater_name'))
								->where("UD.delete_status='0' AND UD.user_status='1'".$filter)
								->group('DV.call_date')
								->group('DV.user_id')
								->order('DV.call_date DESC'.$limit);
								//->limit($orderlimit['Offset'],$orderlimit['Toshow']);
											//print_r($select->__toString());die;
		$result = $this->getAdapter()->fetchAll($select);
		return array('Total'=>count($total),'Records'=>$result,'Toshow'=>$orderlimit['Toshow'],'Offset'=>$orderlimit['Offset']);
	}
	public function monthyworksummaryExport($filterData){
	    try{
			    $totalRowData = array();
				$totalRowData = $filterData['Records'];
				ini_set("memory_limit","512M");
				ini_set("max_execution_time",180);
				ob_end_clean();
				$objPHPExcel = new PHPExcel();
				
				//$headers = array_merge($headers,'Total Call');
				$objPHPExcel->getActiveSheet()->fromArray(array('Name','Emp. Code','Desig','Month','Date','Day','HQ Plaaned','Plaaned With','HQ of worked','Worked with','Doctors Call','Chemist Call'), NULL, 'A1');				
				
				// Set title row bold
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getFont()->setBold(true);
				
				// Setting Auto Width
				foreach(range('A',$objPHPExcel->getActiveSheet()->getHighestColumn()) as $columnID) {
					$objPHPExcel->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
				}
				
				// Setting Column Background Color
				
				$objPHPExcel->getActiveSheet()->getStyle('A1:L1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF00');
				
				// Setting Text Alignment Center
				$objPHPExcel->getActiveSheet()->getStyle('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				foreach($totalRowData as $datas){
				   $dataarray = array();
				   $dataarray[] = $datas['first_name'].' '.$datas['last_name'];
				   $dataarray[] = $datas['employee_code'];
				   $dataarray[] = $datas['designation_name'];
				   $dataarray[] = $datas['call_month'];
				   $dataarray[] =$datas['call_date'];
				   $dataarray[] =date('D',strtotime($datas['call_date']));
				   $dataarray[] =$datas['planned_hq'];
				   $dataarray[] =$this->getEmpname($datas['tpbe_visit']).",".$this->getEmpname($datas['tpabm_visit']).",".$this->getEmpname($datas['tprbm_visit']).",".$this->getEmpname($datas['tpzbm_visit']);
				   $dataarray[] =$employes['headquater_name'];
				   $dataarray[] =$this->getEmpname($datas['be_visit']).",".$this->getEmpname($datas['abm_visit']).",".$this->getEmpname($datas['rbm_visit']).",".$this->getEmpname($datas['zbm_visit']);
				   $dataarray[] = $datas['TDC'];
				   $dataarray[] = $datas['CVC'];
				   
					$reportRows[] = $dataarray;
				}
				
				// Write Row Data
				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');
					
				// Set autofilter
				// Always include the complete filter range!
				// Excel does support setting only the caption
				// row, but that's not a best practise...
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:'.$objPHPExcel->getActiveSheet()->getHighestColumn().'1');
				
				// Rename sheet
				$objPHPExcel->getActiveSheet()->setTitle('MonthlySummaryReport');
				
				// Set active sheet index to the first sheet, so Excel opens this as the first sheet
				$objPHPExcel->setActiveSheetIndex(0);
									
				// Redirect output to a client’s web browser (Excel5)
				header('Content-Type: application/vnd.ms-excel');
				header('Content-Disposition: attachment;filename="MonthlySummaryReport.xls"');
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				ob_end_clean();
				$objWriter->save('php://output'); // $objWriter->save('-'); //THIS DOES NOT WORK WHY?
				//$objWriter->save('test.xlsx');  //THIS WORKS
				$objPHPExcel->disconnectWorksheets();
				unset($objPHPExcel);die;
			
		}
		catch(Exception $e){ echo $e->getMessage();die;
		   $_SESSION[ERROR_MSG] = 'There is Some Error Please try again';  
		}
	}
	
}
?>