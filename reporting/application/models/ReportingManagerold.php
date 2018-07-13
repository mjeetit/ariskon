<?php

class ReportingManagerold extends Zend_Custom

{

	private $_headquarters 	= array();

	

	public function getDoctorVisit($data)

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

			}

				

			if(!empty($data['year']) && !empty($data['month1'])) {

				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";

			}

			else {

				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='2015-09'";

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

							->from(array('EE'=>'app_doctor_visit_5Oct15'),array('(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg'))

							->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

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

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.call_date,'%Y-%m') AS visit_month",

	"COUNT(1) AS CNT","COUNT(DISTINCT(EE.call_date)) AS DAY_CNT","(COUNT(1)/COUNT(DISTINCT(EE.call_date))) AS Call_Avg"))

							   ->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

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

								->from(array('EE'=>'app_doctor_visit_5Oct15'),array('COUNT(1) AS CNT'))

								->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')

							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')

							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')

							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')

							   	/*->joinleft(array('PT1'=>'crm_products'),"PT1.product_id=EE.product1",'')

							   	->joinleft(array('PT2'=>'crm_products'),"PT2.product_id=EE.product2",'')

							   	->joinleft(array('PT3'=>'crm_products'),"PT3.product_id=EE.product3",'')

							   	->joinleft(array('PT4'=>'crm_products'),"PT4.product_id=EE.product4",'')

							   	->joinleft(array('PT5'=>'crm_products'),"PT5.product_id=EE.product5",'')*/

							   	->joinleft(array('AT'=>'app_activities'),"AT.activity_id=EE.activities",'')

						    	->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;

			   

			$select = $this->_db->select()

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array('EE.visit_id','DD.doctor_name','EE.doctor_id','PC.patch_name','DD.patch_id',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit",'EE.zbm_visit',"CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit",'EE.rbm_visit',"CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit",'EE.abm_visit',"CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.be_visit',"EE.call_date",'EE.call_time'))

							   ->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   ->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')

							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')

							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')

							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')

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

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array('DD.doctor_name','EE.doctor_id','PC.patch_name','DD.patch_id',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit",'EE.zbm_visit',"CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit",'EE.rbm_visit',"CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit",'EE.abm_visit',"CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.be_visit','PT1.product_name AS pname1','EE.product1','PT2.product_name AS pname2','EE.product2','PT3.product_name AS pname3','EE.product3','PT4.product_name AS pname4','EE.product4','PT5.product_name AS pname5','EE.product5','AT.activity_name','EE.activities'))

							   ->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   ->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

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

							->from(array('EE'=>'app_chemist_visit_5Oct15'),array('(COUNT(1)/COUNT(DISTINCT(EE.date_added))) AS Call_Avg'))

							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')

						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')

						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')

						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

						    ->where($where.$filterparam)

						    ->group("EE.user_id")

						    ->group("DATE_FORMAT(EE.date_added,'%Y-%m')".$having); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery);

			   

			$select = $this->_db->select()

							   ->from(array('EE'=>'app_chemist_visit_5Oct15'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.date_added,'%Y-%m') AS visit_month",

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

							->from(array('EE'=>'app_chemist_visit_5Oct15'),array('COUNT(1) AS CNT'))

							->joininner(array('DD'=>'crm_chemists'),"DD.chemist_id=EE.chemist_id",'')

							   ->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')

							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')

							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')

							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')

						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;

			   

			$select = $this->_db->select()

							   ->from(array('EE'=>'app_chemist_visit_5Oct15'),array('DD.chemist_name','PC.patch_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit","CONCAT(IF(PT1.product_name!='',CONCAT(PT1.product_name,' : ',EE.unit1),''),' | ',IF(PT2.product_name!='',CONCAT(PT2.product_name,' : ',EE.unit2),''),' | ',IF(PT3.product_name!='',CONCAT(PT3.product_name,' : ',EE.unit3),''),' | ',IF(PT4.product_name!='',CONCAT(PT4.product_name,' : ',EE.unit4),''),' | ',IF(PT5.product_name!='',CONCAT(PT5.product_name,' : ',EE.unit5),'')) AS productunit","EE.date_added"))

							   ->joininner(array('DD'=>'crm_chemists'),"DD.chemist_id=EE.chemist_id",'')

							   ->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

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

							->from(array('EE'=>'app_stockist_visit_5Oct15'),array('(COUNT(1)/COUNT(DISTINCT(EE.date_added))) AS Call_Avg'))

							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')

						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')

						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')

						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

						    ->where($where.$filterparam)

						    ->group("EE.user_id")

						    ->group("DATE_FORMAT(EE.date_added,'%Y-%m')".$having); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery);

			   

			$select = $this->_db->select()

							   ->from(array('EE'=>'app_stockist_visit_5Oct15'),array('EE.user_id',"CONCAT(ED.first_name,' ',ED.last_name) AS Emp","ED.employee_code","DT.designation_name","HT.headquater_name","DATE_FORMAT(EE.date_added,'%Y-%m') AS visit_month",

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

							->from(array('EE'=>'app_stockist_visit_5Oct15'),array('COUNT(1) AS CNT'))

							->joininner(array('ED'=>'employee_personaldetail'),"ED.user_id=EE.user_id",'')

						    ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",'')

						    ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",'')

						    ->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;

			   

			$select = $this->_db->select()

							   ->from(array('EE'=>'app_stockist_visit_5Oct15'),array("ED.employee_code","CONCAT(ED.first_name,' ',ED.last_name) AS Emp","DT.designation_name","HT.headquater_name",'DD.stockist_name',"EE.date_added"))

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

							->from(array('EE'=>'app_stockist_visit_5Oct15'),array('COUNT(1) AS CNT'))

							->joininner(array('DD'=>'crm_stockists'),"DD.stockist_id=EE.stockist_id",'')

							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')

							   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')

							   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')

							   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')

							   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')

						    ->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery); //print_r($total);die;

			   

			$select = $this->_db->select()

							   ->from(array('EE'=>'app_stockist_visit_5Oct15'),array('DD.stockist_name','HT.headquater_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit","EE.date_added"))

							   ->joininner(array('DD'=>'crm_stockists'),"DD.stockist_id=EE.stockist_id",'')

							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=DD.headquater_id",'')

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

							   ->where('ED.user_id='.$data); //print_r($select->__toString());die;

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

							   ->from(array('EE'=>'app_stockist_visit_5Oct15'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.date_added,"%Y-%m") AS visit_month'))

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

							   ->from(array('EE'=>'app_stockist_visit_5Oct15'),array('*'))

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

	    $filter = '';

	     if(!empty($data['patch_id'])){

		     $filter = " AND PT.patch_id='".$data['patch_id']."'";

		 }

	       $select = $this->_db->select()

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array('*'))

							   ->joininner(array('CD'=>'crm_doctors_5Oct15'),"CD.doctor_id=EE.doctor_id",array('doctor_name'))

							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))

							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))

							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))

							   ->joininner(array('PT'=>'patchcodes_5Oct15'),"PT.patch_id=CD.patch_id",array('patch_name'))

							   ->joinleft(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",array('headquater_name'))

							   ->where("EE.user_id='".$data['user_id']."'".$filter)

							   ->order("EE.call_date DESC");

							  //  echo $select->__toString();die;

			$result =  $this->getAdapter()->fetchAll($select);

			return $result;

	 }

	 

	 public function getChemistVisitDetail($data)

	{

	    $filter = '';

	     if(!empty($data['patch_id'])){

		     $filter = " AND PT.patch_id='".$data['patch_id']."'";

		 }

	       $select = $this->_db->select()

							   ->from(array('EE'=>'app_chemist_visit_5Oct15'),array('*','date_added as call_date'))

							   ->joininner(array('CD'=>'crm_chemists'),"CD.chemist_id=EE.chemist_id",array('chemist_name as doctor_name'))

							   ->joininner(array('ED'=>'employee_personaldetail'),"EE.user_id=ED.user_id",array('first_name','last_name','parent_id','employee_code'))

							   ->joininner(array('DT'=>'designation'),"DT.designation_id=ED.designation_id",array('designation_name'))

							   ->joininner(array('EL'=>'emp_locations'),"EL.user_id=ED.user_id",array("if(EL.user_id,1,1) AS DAY_CNT"))

							   ->joininner(array('PT'=>'patchcodes_5Oct15'),"PT.patch_id=CD.patch_id",array('patch_name'))

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

							   ->joininner(array('PT'=>'patchcodes_5Oct15'),"EL.headquater_id=PT.headquater_id",array('patch_id','patch_name'))

							   ->where("ED.user_id='".$user_id."'")

							   ->order("PT.patch_name ASC");

							 // echo $select->__toString();die;

			$result =  $this->getAdapter()->fetchAll($select);

			return $result;

	}

	

	public function daybyCallCount()

	{

	    $select = $this->_db->select()

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array('*','COUNT(1) AS CNT','DATE_FORMAT(EE.call_date,"%Y-%m") AS visit_month'))

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

							   ->from(array('PP'=>'app_doctorvisit_product_5Oct15'),array('*'))

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

						   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array("CONCAT(UD.first_name,' ',UD.last_name) AS username",'UD.employee_code','EE.visit_id','DD.doctor_name','EE.doctor_id','PC.patch_name','DD.patch_id',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit",'EE.zbm_visit',"CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit",'EE.rbm_visit',"CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit",'EE.abm_visit',"CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.be_visit',"EE.call_date",'EE.call_time'))

						   ->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

						   ->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

						   ->joinleft(array('UD'=>'employee_personaldetail'),"UD.user_id=EE.user_id",'')

						   ->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')

						   ->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')

						   ->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')

						   ->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')

						   ->joinleft(array('AT'=>'app_activities'),"AT.activity_id=EE.activities",'')

						   ->where($where.$filterparam)

						   ->order("EE.call_date DESC");  //print_r($select->__toString());die;

		$result =  $this->getAdapter()->fetchAll($select); //echo "<pre>";print_r($result);die;

		$_nxtcol   = "\t";

		$_nxtrow  = "\n";

		$Header .= "\"Employee Name \"".$_nxtcol.

					"\"Employee Code\"".$_nxtcol.

					"\"Doctor Name \"".$_nxtcol.

					"\"Patch Name \"".$_nxtcol.

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

			}

				

			if(!empty($data['year']) && !empty($data['month1'])) {

				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['year']).'-'.trim($data['month'])."'";

			}

			else {

				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='2015-09'";

			}

			//Filter With Doctor Type

			$filterparam .= (isset($data['dtype']) && $data['dtype']==0) ? " AND DD.isApproved='0'" : " AND DD.isApproved='1'";

			//Filter with Activity

			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';

			

			//Order

			$orderlimit = CommonFunction::OdrderByAndLimit($data,'DD.doctor_name');

			

			$countQuery = $this->_db->select()

								->from(array('EE'=>'app_doctor_visit_5Oct15'),array('COUNT(1) AS CNT'))

								->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

								->joininner(array('EL'=>'emp_locations'),"EL.user_id=EE.user_id",'')

						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

							   	->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

							   	->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery);

			   

			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];

			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {

				$limit = '';

			}

			

			$select = $this->_db->select()

							   	->from(array('EE'=>'app_doctor_visit_5Oct15'),array('DD.doctor_name','DD.speciality','DD.class','CA.activity_name',"DATE_FORMAT(EE.call_date,'%M, %Y') AS visit_month","GROUP_CONCAT(DISTINCT DATE_FORMAT(EE.call_date,'%d') SEPARATOR ', ') AS Days"))

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=EE.user_id",'')

						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

							   	->where($where.$filterparam)

							   	->group("EE.doctor_id")

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

	public function ExportDoctorVisitFrequency($headers=array(),$filterData=array())

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

					$reportRows[] = array($row['doctor_name'],$row['speciality'],$row['class'],$row['activity_name'],$row['visit_month'],$row['Days']);					

				}

				

				// Write Row Data

				$objPHPExcel->getActiveSheet()->fromArray($reportRows, NULL, 'A2');

					

				// Set autofilter

				// Always include the complete filter range!

				// Excel does support setting only the caption

				// row, but that's not a best practise...

				$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension()); // Filter on All Column

				

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

			//Filter With BE Data

			if(!empty($data['token3'])){

				//$filterparam .= " AND EE.be_visit='".Class_Encryption::decode($data['token3'])."'";

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token3'])."'";

			}

			//Filter With ABM Data

			if(!empty($data['token4'])){

				//$filterparam .= " AND EE.abm_visit='".Class_Encryption::decode($data['token4'])."'";

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token4'])."'";

			}

			//Filter With RBM Data

			if(!empty($data['token5'])){

				//$filterparam .= " AND EE.rbm_visit='".Class_Encryption::decode($data['token5'])."'";

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token5'])."'";

			}

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				//$filterparam .= " AND EE.zbm_visit='".Class_Encryption::decode($data['token6'])."'";

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token6'])."'";

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

								->from(array('EE'=>'app_doctor_visit_5Oct15'),array('COUNT(1) AS CNT'))

								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

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

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array("CONCAT(RT.first_name,' ',RT.last_name) AS reportee",'PC.patch_name','DD.doctor_name','DD.speciality','DD.class','CA.activity_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.call_date','EE.call_time'))

							  	->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

							   	->joinleft(array('ZBM'=>'employee_personaldetail'),"ZBM.user_id=EE.zbm_visit",'')

							   	->joinleft(array('RBM'=>'employee_personaldetail'),"RBM.user_id=EE.rbm_visit",'')

							   	->joinleft(array('ABM'=>'employee_personaldetail'),"ABM.user_id=EE.abm_visit",'')

							   	->joinleft(array('BE'=>'employee_personaldetail'),"BE.user_id=EE.be_visit",'')

							   	->where($where.$filterparam)

							   	->order("EE.call_date DESC")

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

	 * Get All Doctor Visit Detail Report

	 * Function : getDoctorVisitDetailReport()

	 * This function return doctor visit detail data.

	 **/

	public function getFilterDetail($data)

	{

		$filterparam = " ED.user_id=0";

		//Filter With ZBM

		if(!empty($data['token6'])){

			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token6'])."'";

		}

		//Filter With RBM

		if(!empty($data['token5'])){

			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token5'])."'";

		}

		//Filter With ABM

		if(!empty($data['token4'])){

			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token4'])."'";

		}

		//Filter With BE

		if(!empty($data['token3'])){

			$filterparam = " ED.user_id='".Class_Encryption::decode($data['token3'])."'";

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

		if(count($result)>0) {

			$returnData['Emp. Code']= $result['employee_code'];

			$returnData['Name'] 	= $result['Emp'];

			$returnData['Degi.'] 	= $result['designation_name'];

			$returnData['Region'] 	= $result['region_name'];

			$returnData['HQ'] 		= $result['headquater_name'];

			$returnData['Date From']= '';

			$returnData['Date To'] 	= '';

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

				$headers = array('0'=>'Date','1'=>'Place of work','2'=>'Sl No','3'=>'Dr. Name','4'=>'Specialty','5'=>'Class of Dr.','6'=>'Activity Type','7'=>'Work With');

				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);

				

				$styleArray = array(

							  'borders' => array(

								'allborders' => array(

								  'style' => PHPExcel_Style_Border::BORDER_THIN

								)

							  )

							);

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

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

					$visitWith = (!empty($row['zbmvisit'])) ? $row['zbmvisit'] : (!empty($row['rbmvisit']) ? $row['rbmvisit'] : (!empty($row['abmvisit']) ? $row['abmvisit'] : (!empty($row['bevisit']) ? $row['bevisit'] : '')));

					$reportRows[] = array($row['call_date'],$row['patch_name'],($index+1),$row['doctor_name'],$row['speciality'],$row['class'],$row['activity_name'],$visitWith);					

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

			$where = "1  AND DD.isApproved='0'";			

			$filterparam = '';

			//Filter With BE Data

			if(!empty($data['token3'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token3'])."'";

			}

			//Filter With ABM Data

			if(!empty($data['token4'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token4'])."'";

			}

			//Filter With RBM Data

			if(!empty($data['token5'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token5'])."'";

			}

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token6'])."'";

			}

			//Filter With Date Range

			if(!empty($data['from_date']) && !empty($data['to_date'])){

				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";

			}

			if(!empty($data['monthrange'])) {

				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";

			}

			

			//Filter with Activity

			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';

			//Order

			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.call_date');

			

			$countQuery = $this->_db->select()

								->from(array('EE'=>'app_doctor_visit_5Oct15'),array('COUNT(1) AS CNT'))

								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

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

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array("CONCAT(RT.first_name,' ',RT.last_name) AS reportee",'PC.patch_name','DD.doctor_name','DD.speciality','DD.class','CA.activity_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.call_date','EE.call_time'))

							  	->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

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

				$headers = array('0'=>'Date','1'=>'Place of work','2'=>'Sl No','3'=>'Non Listed Dr. Name','4'=>'Specialty','5'=>'Class of Dr.','6'=>'Activity Type','7'=>'Work With');

				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);

				

				$styleArray = array(

							  'borders' => array(

								'allborders' => array(

								  'style' => PHPExcel_Style_Border::BORDER_THIN

								)

							  )

							);

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

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

					$visitWith = (!empty($row['zbmvisit'])) ? $row['zbmvisit'] : (!empty($row['rbmvisit']) ? $row['rbmvisit'] : (!empty($row['abmvisit']) ? $row['abmvisit'] : (!empty($row['bevisit']) ? $row['bevisit'] : '')));

					$reportRows[] = array($row['call_date'],$row['patch_name'],($index+1),$row['doctor_name'],$row['speciality'],$row['class'],$row['activity_name'],$visitWith);					

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

	 * Get Non Listed Doctor Visit Detail Report

	 * Function : getNonListedDoctorReport()

	 * This function return doctor visit detail data.

	 **/

	public function getDoctorWiseCallReport($data)

	{

		try {

			$where = "1  AND DD.isApproved='1'";			

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

			if(!empty($data['token4'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token4'])."'";

			}

			//Filter With RBM Data

			if(!empty($data['token5'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token5'])."'";

			}

			//Filter With ZBM Data

			if(!empty($data['token6'])){

				$filterparam .= " AND EE.user_id='".Class_Encryption::decode($data['token6'])."'";

			}

			//Filter With Date Range

			if(!empty($data['from_date']) && !empty($data['to_date'])){

				$filterparam .= " AND DATE(EE.call_date) BETWEEN '".$data['from_date']."' AND '".$data['to_date']."'";

			}

			if(!empty($data['monthrange'])) {

				$filterparam .= " AND DATE_FORMAT(EE.call_date,'%Y-%m')='".trim($data['monthrange'])."'";

			}

			

			//Filter with Activity

			$filterparam .= (isset($data['atoken']) && !empty($data['atoken'])) ? " AND EE.activities='".Class_Encryption::decode($data['atoken'])."'" : '';

			//Order

			$orderlimit = CommonFunction::OdrderByAndLimit($data,'EE.call_date');

			

			$countQuery = $this->_db->select()

								->from(array('EE'=>'app_doctor_visit_5Oct15'),array('COUNT(1) AS CNT'))

								->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

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

							   ->from(array('EE'=>'app_doctor_visit_5Oct15'),array("CONCAT(RT.first_name,' ',RT.last_name) AS reportee",'PC.patch_name','DD.doctor_code','DD.doctor_name','DD.speciality','DD.class','CA.activity_name',"CONCAT(ZBM.first_name,' ',ZBM.last_name) AS zbmvisit","CONCAT(RBM.first_name,' ',RBM.last_name) AS rbmvisit","CONCAT(ABM.first_name,' ',ABM.last_name) AS abmvisit","CONCAT(BE.first_name,' ',BE.last_name) AS bevisit",'EE.call_date','EE.call_time'))

							  	->joininner(array('RT'=>'employee_personaldetail'),"RT.user_id=EE.user_id",'')

							   	->joininner(array('DD'=>'crm_doctors_5Oct15'),"DD.doctor_id=EE.doctor_id",'')

							   	->joinleft(array('PC'=>'patchcodes_5Oct15'),"PC.patch_id=DD.patch_id",'')

								->joinleft(array('CA'=>'crm_activity'),"CA.activity_id=DD.activity_id",'')

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

	 * Function : ExportDoctorWiseCallReport()

	 * This function return non listed doctor visit data sheet.

	 **/

	public function ExportDoctorWiseCallReport($data=array())

	{

		try{

			$allData = $this->getDoctorWiseCallReport($data);

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

				$headers = array('0'=>'Doctor Code','1'=>'Doctor Name','2'=>'Place of work','3'=>'Specialty','4'=>'Class of Dr.','5'=>'Activity Type','6'=>'Date','7'=>'BE Name','8'=>'ABM Name','9'=>'RBM Name');

				$objPHPExcel->getActiveSheet()->fromArray(array_values($headers), NULL, 'A'.$rowPos);

				

				$styleArray = array(

							  'borders' => array(

								'allborders' => array(

								  'style' => PHPExcel_Style_Border::BORDER_THIN

								)

							  )

							);

				$objPHPExcel->getActiveSheet()->getStyle('A'.$rowPos.':'.$objPHPExcel->getActiveSheet()->getHighestColumn().(count($totalRowData)+1))->applyFromArray($styleArray);

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

					$reportRows[] = array($row['doctor_code'],$row['doctor_name'],$row['patch_name'],$row['speciality'],$row['class'],$row['activity_name'],$row['call_date'],$row['bevisit'],$row['abmvisit'],$row['rbmvisit']);					

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

			if($_SESSION['AdminDesignation']==8){

				$where .= " AND CE.user_id='".$_SESSION['AdminLoginID']."'";

			}

			elseif($_SESSION['AdminDesignation']==7){

				$where .= " AND (CE.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";

			}

			elseif($_SESSION['AdminDesignation']==6){

				$childs =  $this->getChilllds("ED");

				$where .= " AND (CE.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."'".$childs.")";

			}

			elseif($_SESSION['AdminDesignation']==5){

				$where .= " AND (CE.user_id='".$_SESSION['AdminLoginID']."' OR ED.parent_id='".$_SESSION['AdminLoginID']."')";

			}

			

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

				$filterparam .= " AND DATE_FORMAT(CE.call_date,'%Y-%m')='2015-09'";

			}

			

			//Order

			$orderlimit = CommonFunction::OdrderByAndLimit($data,'CC.chemist_name');

			

			$countQuery = $this->_db->select()

								->from(array('CE'=>'app_chemist_visit_5Oct15'),array('COUNT(1) AS CNT'))

								->joininner(array('CC'=>'crm_chemists'),"CC.chemist_id=CE.chemist_id",'')

							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=CE.user_id",'')

						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

							   	->where($where.$filterparam); //print_r($countQuery->__toString());die;

			$total = $this->getAdapter()->fetchAll($countQuery);

			   

			$limit = $orderlimit['Toshow'].','.$orderlimit['Offset'];

			if(isset($data['exportVisit']) && $data['exportVisit']=='Export in Excel') {

				$limit = '';

			}

			

			$select = $this->_db->select()

							   	->from(array('CE'=>'app_chemist_visit_5Oct15'),array('CC.chemist_name','CC.class',"DATE_FORMAT(CE.call_date,'%M, %Y') AS visit_month","GROUP_CONCAT(DISTINCT DATE_FORMAT(CE.call_date,'%d') SEPARATOR ', ') AS Days"))

							   	->joininner(array('CC'=>'crm_chemists'),"CC.chemist_id=CE.chemist_id",'')

							   	->joininner(array('EL'=>'emp_locations'),"EL.user_id=CE.user_id",'')

						    	->joininner(array('HT'=>'headquater'),"HT.headquater_id=EL.headquater_id",'')

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

					$reportRows[] = array($row['chemist_name'],$row['class'],$row['visit_month'],$row['Days']);					

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

}

?>